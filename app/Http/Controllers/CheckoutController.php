<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\StoreSetting;
use App\Services\CartService;
use App\Services\MoneyService;
use App\Services\OrderService;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected MoneyService $moneyService,
        protected OrderService $orderService,
        protected StripeService $stripeService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $items = $this->cartService->getItems();
        if (empty($items)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty. Please select plants first.');
        }

        $user = Auth::user();
        $subtotal = $this->cartService->getSubtotal();
        $paymentMethods = PaymentMethod::where('is_enabled', true)->get()->keyBy('code');
        $bankAccounts = BankAccount::where('is_active', true)->get();

        $storeInfo = [
            'name' => (string) StoreSetting::get('store_name', 'Plantify Official'),
            'address' => (string) StoreSetting::get('store_address', 'Jl. Agrowisata Hijau No. 24'),
            'city' => (string) StoreSetting::get('store_city', 'Jakarta Selatan'),
            'hours' => (string) StoreSetting::get('store_hours', 'Tuesday - Sunday, 08:30 - 17:00'),
        ];

        // Preliminary estimate for delivery
        $totals = $this->moneyService->calculateTotals($subtotal, 'delivery');

        return view('checkout.index', compact('items', 'subtotal', 'totals', 'paymentMethods', 'bankAccounts', 'storeInfo', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'min:2', 'max:100'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'fulfillment_type' => ['required', 'in:delivery,store_pickup'],
            'shipping_address' => ['required_if:fulfillment_type,delivery', 'nullable', 'string', 'max:255'],
            'shipping_city' => ['required_if:fulfillment_type,delivery', 'nullable', 'string', 'max:120'],
            'shipping_postal_code' => ['required_if:fulfillment_type,delivery', 'nullable', 'string', 'max:32'],
            'payment_method' => ['required', 'in:online_stripe,bank_transfer,pay_in_store'],
            'is_gift' => ['nullable', 'boolean'],
            'recipient_name' => ['required_if:is_gift,1', 'nullable', 'string', 'max:100'],
            'recipient_email' => ['required_if:is_gift,1', 'nullable', 'string', 'email', 'max:255'],
            'gift_message' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $order = $this->orderService->createOrder($validated, Auth::user());

            // Handle online Stripe Checkout
            if ($order->payment_method === 'online_stripe') {
                if (config('app.demo_mode')) {
                    // Demo mode bypass: automatically mark order as paid & processing
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status' => 'processing',
                    ]);
                    if ($order->is_gift && $order->gift) {
                        $order->gift->update(['status' => 'claimable']);
                    }
                    return redirect()->route('order.success', ['token' => $order->tracking_token])
                        ->with('status', 'Demo Mode: Payment completed automatically without card charge.');
                }

                $session = $this->stripeService->createCheckoutSession(
                    $order,
                    route('order.success', ['token' => $order->tracking_token]),
                    route('order.track', ['token' => $order->tracking_token])
                );

                if (!empty($session['url'])) {
                    return redirect()->away($session['url']);
                }
            }

            return redirect()->route('order.success', ['token' => $order->tracking_token])
                ->with('status', 'Your order has been placed successfully!');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success(string $token): View
    {
        $order = Order::where('tracking_token', $token)->with(['items.plant', 'gift', 'bankAccount'])->firstOrFail();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('checkout.success', compact('order', 'bankAccounts'));
    }

    public function resumeStripe(string $token): RedirectResponse
    {
        $order = Order::where('tracking_token', $token)->firstOrFail();

        if ($order->isPaid() || $order->isCancelled()) {
            return redirect()->route('order.track', ['token' => $token]);
        }

        if (config('app.demo_mode')) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
            if ($order->is_gift && $order->gift) {
                $order->gift->update(['status' => 'claimable']);
            }
            return redirect()->route('order.success', ['token' => $order->tracking_token])
                ->with('status', 'Demo Mode: Payment completed automatically.');
        }

        try {
            $session = $this->stripeService->createCheckoutSession(
                $order,
                route('order.success', ['token' => $order->tracking_token]),
                route('order.track', ['token' => $order->tracking_token])
            );

            return redirect()->away($session['url']);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancelUnpaid(string $token): RedirectResponse
    {
        $order = Order::where('tracking_token', $token)->firstOrFail();

        try {
            // Expire Stripe session if open
            if ($order->payment_method === 'online_stripe') {
                $this->stripeService->expireSession($order);
            }

            $this->orderService->cancelUnpaidOrder($order, 'Cancelled by customer via tracking page', Auth::user());

            return redirect()->route('order.track', ['token' => $token])
                ->with('status', 'Order has been cancelled and stock reservation released.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function uploadTransferProof(Request $request, string $token): RedirectResponse
    {
        $order = Order::where('tracking_token', $token)->firstOrFail();

        $validated = $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sender_bank_name' => ['nullable', 'string', 'max:120'],
            'sender_account_name' => ['nullable', 'string', 'max:160'],
            'transfer_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            // PRD 0.2: Store on private disk
            $path = $request->file('payment_proof')->store('transfer_proofs', 'local');

            $this->orderService->submitBankTransferProof(
                $order,
                $path,
                $validated['sender_bank_name'] ?? null,
                $validated['sender_account_name'] ?? null,
                isset($validated['transfer_amount']) ? (float) $validated['transfer_amount'] : null
            );

            return redirect()->route('order.track', ['token' => $token])
                ->with('status', 'Payment receipt uploaded successfully. Our store team will verify it shortly.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
