<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Order;
use App\Services\GardenService;
use App\Services\MoneyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function __construct(
        protected MoneyService $moneyService,
        protected GardenService $gardenService
    ) {}

    public function show(string $token): View
    {
        $order = Order::where('tracking_token', $token)->with(['items.plant', 'gift', 'bankAccount', 'refund'])->firstOrFail();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('orders.track', compact('order', 'bankAccounts'));
    }

    public function showTrackForm(): View
    {
        return view('orders.search');
    }

    public function search(Request $request): RedirectResponse
    {
        // PRD Section 2.1: Rate-limit 5 attempts per minute per IP
        $throttleKey = 'track-order|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many tracking attempts. Please try again in {$seconds} seconds.");
        }

        RateLimiter::hit($throttleKey, 60);

        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $orderNumber = trim($validated['order_number']);
        $email = strtolower(trim($validated['customer_email']));

        $order = Order::where('order_number', $orderNumber)
            ->where('customer_email', $email)
            ->first();

        if (!$order) {
            return back()->withInput()->with('error', 'No order found matching the provided order number and email address.');
        }

        RateLimiter::clear($throttleKey);
        return redirect()->route('order.track', ['token' => $order->tracking_token]);
    }

    public function adoptToGarden(string $token): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in or create an account to adopt these plants to your Digital Garden.');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('error', 'Please verify your email address before adopting plants into your Digital Garden.');
        }

        $order = Order::where('tracking_token', $token)->firstOrFail();

        try {
            $gardens = $this->gardenService->adoptGuestOrder($order, $user);
            $count = count($gardens);

            return redirect()->route('garden.index')->with('status', "Congratulations! {$count} plant(s) from order {$order->order_number} have been adopted to your Digital Garden!");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
