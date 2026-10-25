<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\StripeWebhookEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Refund as StripeRefund;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function __construct(
        protected MoneyService $moneyService
    ) {
        $secretKey = config('services.stripe.secret') ?? env('STRIPE_SECRET');
        if ($secretKey) {
            Stripe::setApiKey($secretKey);
        }
    }

    /**
     * Create Stripe Hosted Checkout Session with explicit expires_at (PRD Section 4.4).
     *
     * @return array{session_id: string, url: string, expires_at: Carbon}
     */
    public function createCheckoutSession(Order $order, string $successUrl, string $cancelUrl): array
    {
        $currency = strtolower($order->currency_code);

        // Build Stripe line items
        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $this->moneyService->toMinorUnits((float) $item->unit_price, $order->currency_code),
                    'product_data' => [
                        'name' => $item->plant_name_snapshot,
                        'metadata' => ['sku' => $item->sku_snapshot],
                    ],
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Add shipping if applicable
        if ((float) $order->shipping_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $this->moneyService->toMinorUnits((float) $order->shipping_amount, $order->currency_code),
                    'product_data' => [
                        'name' => 'Flat Rate Delivery',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        // Add tax if applicable
        if ((float) $order->tax_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $this->moneyService->toMinorUnits((float) $order->tax_amount, $order->currency_code),
                    'product_data' => [
                        'name' => 'Sales Tax',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        // PRD 4.4: Enforce explicit expires_at between 31 and 1439 minutes
        $expiresAtTimestamp = Carbon::now()->addMinutes(31)->timestamp;

        try {
            // Idempotency key per order
            $idempotencyKey = "checkout-order-{$order->id}-" . Carbon::now()->format('YmdHi');

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $order->customer_email,
                'client_reference_id' => (string) $order->id,
                'expires_at' => $expiresAtTimestamp,
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'order_number' => $order->order_number,
                ],
            ], [
                'idempotency_key' => $idempotencyKey,
            ]);

            $expiresAt = Carbon::createFromTimestamp($session->expires_at);

            $order->update([
                'stripe_checkout_session_id' => $session->id,
                'stripe_checkout_url' => $session->url,
                'payment_expires_at' => $expiresAt,
            ]);

            return [
                'session_id' => $session->id,
                'url' => $session->url,
                'expires_at' => $expiresAt,
            ];
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout session creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw new RuntimeException('Failed to initiate online payment session. Please try again.');
        }
    }

    /**
     * Handle incoming Stripe webhook with deduplication and transactional locking.
     */
    public function handleWebhook(string $payload, string $sigHeader): array
    {
        $endpointSecret = config('services.stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Throwable $e) {
            Log::warning('Stripe Webhook Signature Verification Failed', ['error' => $e->getMessage()]);
            throw new RuntimeException('Invalid webhook signature.', 400);
        }

        return DB::transaction(function () use ($event) {
            // Check deduplication (PRD Section 4.4 & 9.2)
            $existing = StripeWebhookEvent::where('event_id', $event->id)->first();
            if ($existing) {
                return ['status' => 'duplicate', 'event_id' => $event->id];
            }

            // Record event
            StripeWebhookEvent::create([
                'event_id' => $event->id,
                'event_type' => $event->type,
                'processed_at' => Carbon::now(),
            ]);

            $dataObject = $event->data->object;

            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleSessionCompleted($dataObject);
                    break;

                case 'checkout.session.expired':
                    $this->handleSessionExpired($dataObject);
                    break;
            }

            return ['status' => 'processed', 'event_type' => $event->type];
        });
    }

    /**
     * Handle checkout.session.completed event.
     */
    protected function handleSessionCompleted(object $session): void
    {
        if (($session->payment_status ?? '') !== 'paid') {
            return;
        }

        $sessionId = $session->id;
        /** @var Order|null $order */
        $order = Order::where('stripe_checkout_session_id', $sessionId)->lockForUpdate()->first();

        if (!$order || $order->isPaid()) {
            return; // Idempotent no-op
        }

        $order->payment_status = 'paid';
        $order->stripe_payment_intent_id = $session->payment_intent ?? null;
        $order->order_status = 'processing';
        $order->save();

        AuditLog::record('stripe_payment_completed', $order, null, [
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'session_id' => $sessionId,
        ], 'Stripe webhook confirmed successful checkout payment.');
    }

    /**
     * Handle checkout.session.expired event.
     */
    protected function handleSessionExpired(object $session): void
    {
        $sessionId = $session->id;
        /** @var Order|null $order */
        $order = Order::where('stripe_checkout_session_id', $sessionId)->lockForUpdate()->first();

        if (!$order || $order->isPaid() || $order->isCancelled()) {
            return;
        }

        $order->payment_status = 'expired';
        $order->order_status = 'cancelled';
        $order->cancelled_at = Carbon::now();
        $order->cancel_reason = 'Payment session expired';

        // Single stock restoration
        if ($order->stock_restored_at === null) {
            foreach ($order->items as $item) {
                $item->plant->increment('stock', $item->quantity);
            }
            $order->stock_restored_at = Carbon::now();
        }

        if ($order->gift) {
            $order->gift->update(['status' => 'cancelled']);
        }

        $order->save();

        AuditLog::record('stripe_session_expired', $order, null, [
            'payment_status' => 'expired',
            'order_status' => 'cancelled',
        ], 'Stripe session expired without payment.');
    }

    /**
     * Expire an active Stripe session before releasing inventory (PRD Section 4.4).
     */
    public function expireSession(Order $order): bool
    {
        if (!$order->stripe_checkout_session_id) {
            return true;
        }

        try {
            $session = StripeSession::retrieve($order->stripe_checkout_session_id);
            if ($session->status === 'open') {
                $session->expire();
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('Failed to expire Stripe session on cancel', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Create a full refund through Stripe API (PRD Section 4.9).
     */
    public function refundOrder(Order $order, string $reason): string
    {
        if (!$order->stripe_payment_intent_id) {
            throw new RuntimeException('Cannot refund order: missing Stripe payment intent reference.');
        }

        try {
            $refund = StripeRefund::create([
                'payment_intent' => $order->stripe_payment_intent_id,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'reason' => $reason,
                ],
            ]);

            return $refund->id;
        } catch (\Throwable $e) {
            Log::error('Stripe refund API call failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw new RuntimeException('Failed to process refund with Stripe: ' . $e->getMessage());
        }
    }
}
