<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BankAccount;
use App\Models\Category;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Plant;
use App\Models\StoreSetting;
use App\Models\User;
use App\Services\GardenService;
use App\Services\MoneyService;
use App\Services\OrderService;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected MoneyService $moneyService,
        protected GardenService $gardenService,
        protected StripeService $stripeService
    ) {}

    public function dashboard(): View
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $pendingVerifications = Order::where('payment_status', 'awaiting_verification')->count();
        $pendingPickups = Order::where('order_status', 'ready_for_pickup')->count();
        $pendingDeliveries = Order::where('order_status', 'processing')->where('fulfillment_type', 'delivery')->count();
        $lowStockCount = Plant::where('is_active', true)->whereColumn('stock', '<=', 'low_stock_threshold')->count();

        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingVerifications',
            'pendingPickups',
            'pendingDeliveries',
            'lowStockCount',
            'recentOrders'
        ));
    }

    public function orders(Request $request): View
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->input('order_status'));
        }

        if ($request->filled('fulfillment_type')) {
            $query->where('fulfillment_type', $request->input('fulfillment_type'));
        }

        if ($request->filled('search')) {
            $s = trim((string) $request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                    ->orWhere('customer_email', 'like', "%{$s}%")
                    ->orWhere('customer_name', 'like', "%{$s}%")
                    ->orWhere('pickup_code', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail(int $id): View
    {
        $order = Order::with(['items.plant', 'gift', 'bankAccount', 'refund', 'user'])->findOrFail($id);
        $auditLogs = AuditLog::where('auditable_type', Order::class)->where('auditable_id', $order->id)->latest()->get();

        $proofUrl = null;
        if ($order->payment_proof_path) {
            $proofUrl = URL::temporarySignedRoute(
                'admin.proof.view',
                Carbon::now()->addMinutes(30),
                ['path' => urlencode($order->payment_proof_path)]
            );
        }

        return view('admin.orders.detail', compact('order', 'auditLogs', 'proofUrl'));
    }

    public function verifications(): View
    {
        $orders = Order::where('payment_status', 'awaiting_verification')
            ->with(['bankAccount', 'items'])
            ->latest('payment_submitted_at')
            ->get();

        return view('admin.verifications.index', compact('orders'));
    }

    public function approveTransfer(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $this->orderService->approveBankTransfer($order, Auth::user());

        return back()->with('status', "Bank transfer payment for order {$order->order_number} has been approved.");
    }

    public function rejectTransfer(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $order = Order::findOrFail($id);
        $this->orderService->rejectBankTransfer($order, $validated['reason'], Auth::user());

        return back()->with('status', "Bank transfer payment for order {$order->order_number} has been rejected with grace period.");
    }

    public function pickup(Request $request): View
    {
        $order = null;
        $search = trim((string) $request->input('code'));

        if ($search !== '') {
            $cleanCode = strtoupper(str_replace(['-', ' '], '', $search));
            $order = Order::where(function ($q) use ($cleanCode, $search) {
                $q->where('pickup_code', $cleanCode)
                    ->orWhere('order_number', $search);
            })->where('fulfillment_type', 'store_pickup')->first();
        }

        $readyOrders = Order::where('fulfillment_type', 'store_pickup')
            ->whereIn('order_status', ['ready_for_pickup', 'processing'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.pickup.index', compact('order', 'readyOrders', 'search'));
    }

    public function markReadyForPickup(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $this->orderService->markReadyForPickup($order, Auth::user());

        return back()->with('status', "Order {$order->order_number} marked ready for pickup.");
    }

    public function confirmPickupHandover(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $method = $request->input('pickup_payment_method', 'cash');

        $order = $this->orderService->confirmPickupHandover($order, Auth::user(), $method);

        // Adopt plants to Digital Garden if non-gift!
        if (!$order->is_gift) {
            $this->gardenService->adoptFromOrder($order);
        } else {
            // Activate gift claim
            $order->gift?->update([
                'status' => 'claimable',
                'expires_at' => Carbon::now()->addDays((int) StoreSetting::get('gift_claim_validity_days', '30')),
            ]);
        }

        return redirect()->route('admin.pickup')->with('status', "Handover confirmed for order {$order->order_number}. Order completed.");
    }

    public function delivery(): View
    {
        $processingOrders = Order::where('fulfillment_type', 'delivery')
            ->where('order_status', 'processing')
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $shippedOrders = Order::where('fulfillment_type', 'delivery')
            ->where('order_status', 'shipped')
            ->latest('shipped_at')
            ->get();

        return view('admin.delivery.index', compact('processingOrders', 'shippedOrders'));
    }

    public function markShipped(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'carrier' => ['nullable', 'string', 'max:120'],
            'tracking_number' => ['nullable', 'string', 'max:160'],
        ]);

        $order = Order::findOrFail($id);
        $this->orderService->markShipped($order, $validated['carrier'] ?? null, $validated['tracking_number'] ?? null, Auth::user());

        return back()->with('status', "Order {$order->order_number} marked as shipped.");
    }

    public function markDelivered(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order = $this->orderService->markDelivered($order, Auth::user());

        // PRD 4.8 & 6.1: Adopt plants to Digital Garden on delivery!
        if (!$order->is_gift) {
            $this->gardenService->adoptFromOrder($order);
        } else {
            // Activate gift claim
            $order->gift?->update([
                'status' => 'claimable',
                'expires_at' => Carbon::now()->addDays((int) StoreSetting::get('gift_claim_validity_days', '30')),
            ]);
        }

        return back()->with('status', "Delivery confirmed for order {$order->order_number}. Digital Garden entries created.");
    }

    public function refund(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $order = Order::findOrFail($id);

        try {
            $providerRef = null;
            if ($order->payment_method === 'online_stripe' && $order->stripe_payment_intent_id) {
                $providerRef = $this->stripeService->refundOrder($order, $validated['reason']);
            }

            $this->orderService->processFullRefund($order, $validated['reason'], Auth::user(), $providerRef);

            return back()->with('status', "Full refund processed for order {$order->order_number}.");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function plants(): View
    {
        $plants = Plant::with('category')->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.plants.index', compact('plants', 'categories'));
    }

    public function deactivatePlant(int $id): RedirectResponse
    {
        $plant = Plant::findOrFail($id);
        $plant->update(['is_active' => false]);

        return back()->with('status', "Plant '{$plant->name}' deactivated. Historical orders remain intact.");
    }

    public function settings(): View
    {
        $settings = StoreSetting::all()->pluck('value', 'key')->toArray();
        $methods = PaymentMethod::all();
        $bankAccounts = BankAccount::all();

        return view('admin.settings.index', compact('settings', 'methods', 'bankAccounts'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_address' => ['required', 'string', 'max:255'],
            'store_city' => ['required', 'string', 'max:120'],
            'store_hours' => ['required', 'string', 'max:120'],
            'delivery_flat_fee' => ['required', 'numeric', 'min:0'],
            'tax_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_shipping' => ['required', 'in:true,false'],
            'gift_claim_validity_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        foreach ($validated as $key => $val) {
            StoreSetting::set($key, $val);
        }

        // Update payment method expiry settings if passed
        if ($request->has('methods')) {
            foreach ($request->input('methods') as $code => $data) {
                $pm = PaymentMethod::where('code', $code)->first();
                if ($pm) {
                    $pm->update([
                        'is_enabled' => !empty($data['is_enabled']),
                        'expires_after_minutes' => (int) ($data['expires_after_minutes'] ?? $pm->expires_after_minutes),
                    ]);
                }
            }
        }

        return back()->with('status', 'Store settings and payment methods updated successfully.');
    }

    public function viewPrivateProof(Request $request): BinaryFileResponse
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Signed link expired or invalid.');
        }

        $path = urldecode((string) $request->input('path'));
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Payment proof file not found.');
        }

        return response()->file(Storage::disk('local')->path($path));
    }
}
