<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Services\CartService;
use App\Services\MoneyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected MoneyService $moneyService
    ) {}

    public function index(): View
    {
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $count = $this->cartService->getCount();

        return view('pages.cart', compact('items', 'subtotal', 'count'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'plant_id' => ['required', 'integer', 'exists:plants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $success = $this->cartService->addItem((int) $validated['plant_id'], $quantity);

        if (!$success) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'This plant is currently out of stock.'], 422);
            }
            return back()->with('error', 'This plant is currently out of stock.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $this->cartService->getCount(),
                'subtotal' => $this->moneyService->format($this->cartService->getSubtotal()),
            ]);
        }

        return back()->with('status', 'Plant added to your cart successfully!');
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'plant_id' => ['required', 'integer', 'exists:plants,id'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateQuantity((int) $validated['plant_id'], (int) $validated['quantity']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $this->cartService->getCount(),
                'subtotal' => $this->moneyService->format($this->cartService->getSubtotal()),
            ]);
        }

        return back();
    }

    public function remove(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'plant_id' => ['required', 'integer'],
        ]);

        $this->cartService->removeItem((int) $validated['plant_id']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $this->cartService->getCount(),
            ]);
        }

        return back()->with('status', 'Item removed from your cart.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clearCart();
        return redirect()->route('cart.index')->with('status', 'Cart cleared.');
    }
}
