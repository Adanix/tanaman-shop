<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Models\BankAccount;
use App\Models\Gift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Plant;
use App\Models\Refund;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class OrderService
{
    public function __construct(
        protected MoneyService $moneyService,
        protected CartService $cartService
    ) {}

    /**
     * Create an order atomically with inventory locking.
     *
     * @param array<string, mixed> $data
     */
    public function createOrder(array $data, ?User $user = null): Order
    {
        $items = $this->cartService->getItems($user);
        if (empty($items)) {
            throw new RuntimeException('Your cart is empty. Please add plants before checkout.');
        }

        $fulfillmentType = $data['fulfillment_type'] ?? 'delivery';
        if (!in_array($fulfillmentType, ['delivery', 'store_pickup'], true)) {
            throw new InvalidArgumentException('Invalid fulfillment type.');
        }

        $paymentMethod = $data['payment_method'] ?? 'online_stripe';
        if (!in_array($paymentMethod, ['online_stripe', 'bank_transfer', 'pay_in_store'], true)) {
            throw new InvalidArgumentException('Invalid payment method.');
        }

        // Verify payment method is enabled
        $methodConfig = PaymentMethod::where('code', $paymentMethod)->first();
        if ($methodConfig && !$methodConfig->is_enabled) {
            throw new RuntimeException('The selected payment method is currently disabled.');
        }

        // PRD 4.6: Pay in Store restrictions
        if ($paymentMethod === 'pay_in_store') {
            if ($fulfillmentType !== 'store_pickup') {
                throw new InvalidArgumentException('Pay in Store is only available for store pickup orders.');
            }

            $totalUnits = array_sum(array_column($items, 'quantity'));
            if ($totalUnits > 5) {
                throw new RuntimeException('Pay in Store orders are limited to a maximum of 5 plant units.');
            }

            // Limit active unpaid pay in store orders per customer
            $email = strtolower(trim((string) ($data['customer_email'] ?? $user?->email)));
            $activeUnpaid = Order::where('customer_email', $email)
                ->where('payment_status', 'pay_in_store_pending')
                ->where('order_status', '!=', 'cancelled')
                ->count();

            if ($activeUnpaid >= 2) {
                throw new RuntimeException('You already have 2 active Pay in Store reservations. Please complete them before reserving more.');
            }
        }

        // PRD 5.1: Gift validation
        $isGift = !empty($data['is_gift']);
        if ($isGift) {
            if (empty($data['recipient_name']) || empty($data['recipient_email'])) {
                throw new InvalidArgumentException('Recipient name and email are required for gift orders.');
            }
        }

        // Calculate expiration time based on payment method config
        $expiresAfterMinutes = $methodConfig ? $methodConfig->expires_after_minutes : match ($paymentMethod) {
            'online_stripe' => 31,
            'bank_transfer' => 1440,
            'pay_in_store' => 2880,
        };

        return DB::transaction(function () use ($data, $items, $fulfillmentType, $paymentMethod, $isGift, $expiresAfterMinutes, $user) {
            // Lock all plants in cart to guarantee inventory
            $plantIds = array_keys($items);
            $lockedPlants = Plant::whereIn('id', $plantIds)->lockForUpdate()->get()->keyBy('id');

            $itemsSubtotal = 0.0;
            foreach ($items as $plantId => $itemData) {
                /** @var Plant|null $plant */
                $plant = $lockedPlants->get($plantId);
                if (!$plant || !$plant->is_active || $plant->stock < $itemData['quantity']) {
                    $name = $plant ? $plant->name : 'Item';
                    throw new RuntimeException("We are sorry, {$name} just sold out or has insufficient stock. Your cart has been updated.");
                }

                // Decrement stock atomically
                $plant->decrement('stock', $itemData['quantity']);
                $itemsSubtotal += round((float) $plant->price * $itemData['quantity'], 2);
            }

            // Calculate server-side financial snapshot
            $totals = $this->moneyService->calculateTotals($itemsSubtotal, $fulfillmentType);

            // Generate unique numbers
            $orderNumber = $this->generateOrderNumber();
            $trackingToken = Str::random(64);
            $pickupCode = ($fulfillmentType === 'store_pickup') ? $this->generatePickupCode() : null;

            // Initial statuses based on payment method
            $paymentStatus = ($paymentMethod === 'pay_in_store') ? 'pay_in_store_pending' : 'pending';
            $orderStatus = ($paymentMethod === 'pay_in_store') ? 'ready_for_pickup' : 'awaiting_payment';
            $paymentExpiresAt = Carbon::now()->addMinutes($expiresAfterMinutes);

            // Bank transfer snapshot if applicable
            $bankAccountId = null;
            $bankNameSnapshot = null;
            $accountNumberSnapshot = null;
            $accountHolderSnapshot = null;

            if ($paymentMethod === 'bank_transfer') {
                $bankAccount = BankAccount::where('is_active', true)->first();
                if (!$bankAccount) {
                    throw new RuntimeException('No active bank account is currently available for manual transfer.');
                }
                $bankAccountId = $bankAccount->id;
                $bankNameSnapshot = $bankAccount->bank_name;
                $accountNumberSnapshot = $bankAccount->account_number;
                $accountHolderSnapshot = $bankAccount->account_holder_name;
            }

            // Create Order
            $order = Order::create([
                'user_id' => $user?->id,
                'order_number' => $orderNumber,
                'tracking_token' => $trackingToken,
                'customer_name' => trim((string) ($data['customer_name'] ?? $user?->name)),
                'customer_email' => strtolower(trim((string) ($data['customer_email'] ?? $user?->email))),
                'customer_phone' => $data['customer_phone'] ?? null,
                'fulfillment_type' => $fulfillmentType,
                'shipping_address' => ($fulfillmentType === 'delivery') ? ($data['shipping_address'] ?? null) : null,
                'shipping_city' => ($fulfillmentType === 'delivery') ? ($data['shipping_city'] ?? null) : null,
                'shipping_postal_code' => ($fulfillmentType === 'delivery') ? ($data['shipping_postal_code'] ?? null) : null,
                'items_subtotal' => $totals['items_subtotal'],
                'shipping_amount' => $totals['shipping_amount'],
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'currency_code' => $totals['currency_code'],
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,
                'payment_expires_at' => $paymentExpiresAt,
                'bank_account_id' => $bankAccountId,
                'bank_name_snapshot' => $bankNameSnapshot,
                'account_number_snapshot' => $accountNumberSnapshot,
                'account_holder_snapshot' => $accountHolderSnapshot,
                'pickup_code' => $pickupCode,
                'is_gift' => $isGift,
            ]);

            // Create OrderItems snapshots
            foreach ($items as $plantId => $itemData) {
                $plant = $lockedPlants->get($plantId);
                OrderItem::create([
                    'order_id' => $order->id,
                    'plant_id' => $plant->id,
                    'plant_name_snapshot' => $plant->name,
                    'sku_snapshot' => $plant->sku,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => (float) $plant->price,
                    'subtotal' => round((float) $plant->price * $itemData['quantity'], 2),
                ]);
            }

            // Create Gift record if gift
            if ($isGift) {
                Gift::create([
                    'order_id' => $order->id,
                    'token' => Str::random(48),
                    'recipient_name' => trim((string) $data['recipient_name']),
                    'recipient_email' => strtolower(trim((string) $data['recipient_email'])),
                    'message' => !empty($data['gift_message']) ? trim((string) $data['gift_message']) : null,
                    'status' => 'pending_delivery',
                ]);
            }

            // Clear Cart
            $this->cartService->clearCart($user);

            AuditLog::record('order_created', $order, null, $order->toArray(), 'New order created at checkout.');

            return $order;
        });
    }

    /**
     * Cancel an unpaid order and restore inventory exactly once.
     */
    public function cancelUnpaidOrder(Order $order, string $reason = 'Cancelled by customer', ?User $actor = null): bool
    {
        return DB::transaction(function () use ($order, $reason, $actor) {
            /** @var Order $lockedOrder */
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if (!$lockedOrder->canBeCancelledByCustomer() && !($actor?->isAdmin())) {
                throw new RuntimeException('This order cannot be cancelled in its current state.');
            }

            if ($lockedOrder->order_status === 'cancelled' || $lockedOrder->payment_status === 'cancelled') {
                return true; // Idempotent no-op
            }

            // Update statuses
            $lockedOrder->order_status = 'cancelled';
            $lockedOrder->payment_status = ($lockedOrder->payment_status === 'pay_in_store_pending') ? 'cancelled' : 'cancelled';
            $lockedOrder->cancelled_at = Carbon::now();
            $lockedOrder->cancelled_by_user_id = $actor?->id;
            $lockedOrder->cancel_reason = $reason;

            // Restore inventory atomically exactly once
            if ($lockedOrder->stock_restored_at === null) {
                foreach ($lockedOrder->items as $item) {
                    Plant::where('id', $item->plant_id)->increment('stock', $item->quantity);
                }
                $lockedOrder->stock_restored_at = Carbon::now();
            }

            // Cancel gift if exists
            if ($lockedOrder->gift) {
                $lockedOrder->gift->update(['status' => 'cancelled']);
            }

            $lockedOrder->save();

            AuditLog::record('order_cancelled', $lockedOrder, null, [
                'order_status' => 'cancelled',
                'cancel_reason' => $reason,
            ], $reason, $actor?->id);

            return true;
        });
    }

    /**
     * Submit Bank Transfer payment proof (PRD Section 4.5).
     */
    public function submitBankTransferProof(
        Order $order,
        string $proofPath,
        ?string $senderBank = null,
        ?string $senderAccount = null,
        ?float $amount = null
    ): Order {
        return DB::transaction(function () use ($order, $proofPath, $senderBank, $senderAccount, $amount) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if (!in_array($lockedOrder->payment_status, ['pending', 'rejected', 'awaiting_verification'], true)) {
                throw new RuntimeException('Cannot upload payment proof for this order.');
            }

            $lockedOrder->payment_proof_path = $proofPath;
            $lockedOrder->sender_bank_name = $senderBank;
            $lockedOrder->sender_account_name = $senderAccount;
            $lockedOrder->transfer_amount = $amount ?? $lockedOrder->total_amount;
            $lockedOrder->payment_submitted_at = Carbon::now();

            // Set to awaiting verification and PAUSE timer (PRD Section 4.5)
            $lockedOrder->payment_status = 'awaiting_verification';
            $lockedOrder->order_status = 'awaiting_verification';
            $lockedOrder->payment_expires_at = null;
            $lockedOrder->save();

            AuditLog::record('bank_proof_submitted', $lockedOrder, null, [
                'proof_path' => $proofPath,
                'transfer_amount' => $amount,
            ], 'Customer submitted bank transfer proof.');

            return $lockedOrder;
        });
    }

    /**
     * Approve Bank Transfer payment by Store Admin (PRD Section 4.5).
     */
    public function approveBankTransfer(Order $order, User $admin): Order
    {
        return DB::transaction(function () use ($order, $admin) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->payment_status === 'paid') {
                return $lockedOrder; // Idempotent
            }

            if ($lockedOrder->payment_status !== 'awaiting_verification') {
                throw new RuntimeException('Order is not currently awaiting verification.');
            }

            $lockedOrder->payment_status = 'paid';
            $lockedOrder->payment_verified_by = $admin->id;
            $lockedOrder->payment_verified_at = Carbon::now();
            $lockedOrder->payment_rejection_reason = null;

            // Move to processing
            $lockedOrder->order_status = 'processing';
            $lockedOrder->save();

            AuditLog::record('bank_transfer_approved', $lockedOrder, null, [
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ], 'Admin approved bank transfer proof.', $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Reject Bank Transfer payment by Store Admin with mandatory reason (PRD Section 4.5).
     */
    public function rejectBankTransfer(Order $order, string $reason, User $admin): Order
    {
        if (strlen(trim($reason)) < 10) {
            throw new InvalidArgumentException('Rejection reason must be at least 10 characters.');
        }

        return DB::transaction(function () use ($order, $reason, $admin) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->payment_status !== 'awaiting_verification') {
                throw new RuntimeException('Order is not currently awaiting verification.');
            }

            $graceMinutes = (int) StoreSetting::get('bank_transfer_rejection_grace_minutes', '720');

            $lockedOrder->payment_status = 'rejected';
            $lockedOrder->order_status = 'awaiting_payment';
            $lockedOrder->payment_verified_by = $admin->id;
            $lockedOrder->payment_verified_at = Carbon::now();
            $lockedOrder->payment_rejection_reason = $reason;
            $lockedOrder->payment_expires_at = Carbon::now()->addMinutes($graceMinutes);
            $lockedOrder->save();

            AuditLog::record('bank_transfer_rejected', $lockedOrder, null, [
                'payment_status' => 'rejected',
                'rejection_reason' => $reason,
                'grace_minutes' => $graceMinutes,
            ], $reason, $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Prepare prepaid pickup order for customer (PRD Section 4.7).
     */
    public function markReadyForPickup(Order $order, User $admin): Order
    {
        return DB::transaction(function () use ($order, $admin) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->fulfillment_type !== 'store_pickup') {
                throw new InvalidArgumentException('This order is not for store pickup.');
            }

            if ($lockedOrder->order_status === 'ready_for_pickup') {
                return $lockedOrder; // Idempotent
            }

            $lockedOrder->order_status = 'ready_for_pickup';
            $lockedOrder->save();

            AuditLog::record('order_ready_for_pickup', $lockedOrder, null, [
                'order_status' => 'ready_for_pickup',
            ], 'Admin marked order ready for pickup.', $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Confirm In-Store Pickup / POS Handover (PRD Section 4.6 & 4.7).
     */
    public function confirmPickupHandover(Order $order, User $admin, ?string $pickupPaymentMethod = 'cash'): Order
    {
        return DB::transaction(function () use ($order, $admin, $pickupPaymentMethod) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->order_status === 'delivered') {
                return $lockedOrder; // Idempotent
            }

            if ($lockedOrder->fulfillment_type !== 'store_pickup') {
                throw new InvalidArgumentException('This order is not configured for store pickup.');
            }

            if ($lockedOrder->payment_status === 'pay_in_store_pending') {
                $lockedOrder->payment_status = 'paid';
                $lockedOrder->pickup_payment_method = $pickupPaymentMethod ?? 'cash';
            }

            $lockedOrder->order_status = 'delivered';
            $lockedOrder->picked_up_at = Carbon::now();
            $lockedOrder->delivered_at = Carbon::now();
            $lockedOrder->delivered_by_user_id = $admin->id;
            $lockedOrder->save();

            AuditLog::record('pickup_handover_confirmed', $lockedOrder, null, [
                'order_status' => 'delivered',
                'payment_status' => $lockedOrder->payment_status,
                'picked_up_at' => $lockedOrder->picked_up_at,
            ], 'Admin confirmed store pickup handover.', $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Mark delivery order as shipped with tracking details (PRD Section 4.8).
     */
    public function markShipped(Order $order, ?string $carrier, ?string $trackingNumber, User $admin): Order
    {
        return DB::transaction(function () use ($order, $carrier, $trackingNumber, $admin) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->fulfillment_type !== 'delivery') {
                throw new InvalidArgumentException('Only delivery orders can be marked shipped.');
            }

            if ($lockedOrder->payment_status !== 'paid') {
                throw new RuntimeException('Cannot ship an unpaid order.');
            }

            $lockedOrder->shipping_carrier = $carrier;
            $lockedOrder->tracking_number = $trackingNumber;
            $lockedOrder->shipped_at = Carbon::now();
            $lockedOrder->order_status = 'shipped';
            $lockedOrder->save();

            AuditLog::record('order_shipped', $lockedOrder, null, [
                'order_status' => 'shipped',
                'carrier' => $carrier,
                'tracking_number' => $trackingNumber,
            ], 'Admin marked order shipped.', $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Mark delivery order as delivered (PRD Section 4.8).
     */
    public function markDelivered(Order $order, User $admin): Order
    {
        return DB::transaction(function () use ($order, $admin) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->order_status === 'delivered') {
                return $lockedOrder; // Idempotent
            }

            if ($lockedOrder->payment_status !== 'paid') {
                throw new RuntimeException('Cannot mark an unpaid order as delivered.');
            }

            $lockedOrder->order_status = 'delivered';
            $lockedOrder->delivered_at = Carbon::now();
            $lockedOrder->delivered_by_user_id = $admin->id;
            $lockedOrder->save();

            AuditLog::record('order_delivered', $lockedOrder, null, [
                'order_status' => 'delivered',
                'delivered_at' => $lockedOrder->delivered_at,
            ], 'Admin confirmed delivery completion.', $admin->id);

            return $lockedOrder;
        });
    }

    /**
     * Process full refund for paid order (PRD Section 4.9).
     */
    public function processFullRefund(Order $order, string $reason, User $admin, ?string $providerReference = null): Refund
    {
        if (strlen(trim($reason)) < 10) {
            throw new InvalidArgumentException('Refund reason must be at least 10 characters.');
        }

        return DB::transaction(function () use ($order, $reason, $admin, $providerReference) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if (!$lockedOrder->canBeRefunded()) {
                throw new RuntimeException('This order cannot be refunded.');
            }

            $refund = Refund::updateOrCreate(
                ['order_id' => $lockedOrder->id],
                [
                    'amount' => $lockedOrder->total_amount,
                    'currency_code' => $lockedOrder->currency_code,
                    'method' => match ($lockedOrder->payment_method) {
                        'online_stripe' => 'stripe',
                        'bank_transfer' => 'bank_transfer',
                        'pay_in_store' => 'pay_in_store',
                    },
                    'status' => 'completed',
                    'reason' => $reason,
                    'provider_reference' => $providerReference,
                    'initiated_by_user_id' => $admin->id,
                    'completed_by_user_id' => $admin->id,
                    'completed_at' => Carbon::now(),
                ]
            );

            $lockedOrder->payment_status = 'refunded';

            // PRD 4.9: If not yet shipped or delivered, cancel order and restore stock
            if (!in_array($lockedOrder->order_status, ['shipped', 'delivered'], true)) {
                $lockedOrder->order_status = 'cancelled';
                if ($lockedOrder->stock_restored_at === null) {
                    foreach ($lockedOrder->items as $item) {
                        Plant::where('id', $item->plant_id)->increment('stock', $item->quantity);
                    }
                    $lockedOrder->stock_restored_at = Carbon::now();
                }
            }

            $lockedOrder->save();

            AuditLog::record('order_refunded', $lockedOrder, null, [
                'refund_id' => $refund->id,
                'amount' => $refund->amount,
                'status' => 'refunded',
            ], $reason, $admin->id);

            return $refund;
        });
    }

    /**
     * Generate unique Order Number in format ORD-YYYYMMDD-XXXXXXXX.
     */
    protected function generateOrderNumber(): string
    {
        $date = Carbon::now()->format('Ymd');
        do {
            $random = strtoupper(Str::random(8));
            $orderNumber = "ORD-{$date}-{$random}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Generate 6-character pickup code excluding ambiguous chars (0, O, 1, I).
     */
    protected function generatePickupCode(): string
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $max = strlen($chars) - 1;
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, $max)];
            }
        } while (Order::where('pickup_code', $code)->exists());

        return $code;
    }
}
