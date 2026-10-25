<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CartItem;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'plantify_cart';

    public function __construct(
        protected MoneyService $moneyService
    ) {}

    /**
     * Get all items currently in cart with product details.
     *
     * @return array<int, array{plant: Plant, quantity: int, unit_price: float, subtotal: float}>
     */
    public function getItems(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        $rawItems = $this->getRawItems($user);
        $result = [];

        if (empty($rawItems)) {
            return [];
        }

        $plants = Plant::whereIn('id', array_keys($rawItems))->where('is_active', true)->get()->keyBy('id');

        foreach ($rawItems as $plantId => $qty) {
            /** @var Plant|null $plant */
            $plant = $plants->get($plantId);
            if (!$plant || $plant->stock <= 0) {
                // Remove out-of-stock or deactivated plants
                $this->removeItem($plantId, $user);
                continue;
            }

            // Cap quantity to available stock
            $adjustedQty = min($qty, $plant->stock);
            if ($adjustedQty !== $qty) {
                $this->updateQuantity($plantId, $adjustedQty, $user);
            }

            $price = (float) $plant->price;
            $subtotal = round($price * $adjustedQty, 2);

            $result[$plantId] = [
                'plant' => $plant,
                'quantity' => $adjustedQty,
                'unit_price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        return $result;
    }

    /**
     * Add a plant to the cart.
     */
    public function addItem(int $plantId, int $quantity = 1, ?User $user = null): bool
    {
        $plant = Plant::where('id', $plantId)->where('is_active', true)->first();
        if (!$plant || $plant->stock <= 0) {
            return false;
        }

        $user = $user ?? Auth::user();
        $raw = $this->getRawItems($user);
        $currentQty = $raw[$plantId] ?? 0;
        $newQty = min($plant->stock, $currentQty + max(1, $quantity));

        if ($user) {
            CartItem::updateOrCreate(
                ['user_id' => $user->id, 'plant_id' => $plantId],
                ['quantity' => $newQty]
            );
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            $cart[$plantId] = $newQty;
            Session::put(self::SESSION_KEY, $cart);
        }

        return true;
    }

    /**
     * Update quantity for a plant in cart.
     */
    public function updateQuantity(int $plantId, int $quantity, ?User $user = null): void
    {
        $user = $user ?? Auth::user();
        if ($quantity <= 0) {
            $this->removeItem($plantId, $user);
            return;
        }

        $plant = Plant::find($plantId);
        if (!$plant) {
            $this->removeItem($plantId, $user);
            return;
        }

        $qty = min($quantity, $plant->stock);

        if ($user) {
            CartItem::updateOrCreate(
                ['user_id' => $user->id, 'plant_id' => $plantId],
                ['quantity' => $qty]
            );
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            $cart[$plantId] = $qty;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $plantId, ?User $user = null): void
    {
        $user = $user ?? Auth::user();
        if ($user) {
            CartItem::where('user_id', $user->id)->where('plant_id', $plantId)->delete();
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            unset($cart[$plantId]);
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    /**
     * Clear all cart contents.
     */
    public function clearCart(?User $user = null): void
    {
        $user = $user ?? Auth::user();
        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        }
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Merge guest cart into member cart upon login/register (PRD Section 2.2).
     */
    public function mergeGuestCartToMember(User $user): void
    {
        $guestCart = Session::get(self::SESSION_KEY, []);
        if (empty($guestCart)) {
            return;
        }

        foreach ($guestCart as $plantId => $qty) {
            $plant = Plant::where('id', $plantId)->where('is_active', true)->first();
            if (!$plant || $plant->stock <= 0) {
                continue;
            }

            $existing = CartItem::where('user_id', $user->id)->where('plant_id', $plantId)->first();
            $existingQty = $existing ? $existing->quantity : 0;
            $mergedQty = min($plant->stock, $existingQty + $qty);

            CartItem::updateOrCreate(
                ['user_id' => $user->id, 'plant_id' => $plantId],
                ['quantity' => $mergedQty]
            );
        }

        Session::forget(self::SESSION_KEY);
    }

    /**
     * Calculate items subtotal.
     */
    public function getSubtotal(?User $user = null): float
    {
        $items = $this->getItems($user);
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
        }
        return round($subtotal, 2);
    }

    /**
     * Count total quantity of items in cart.
     */
    public function getCount(?User $user = null): int
    {
        $items = $this->getItems($user);
        $count = 0;
        foreach ($items as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Raw array of plant_id => qty.
     *
     * @return array<int, int>
     */
    protected function getRawItems(?User $user): array
    {
        if ($user) {
            return CartItem::where('user_id', $user->id)
                ->pluck('quantity', 'plant_id')
                ->toArray();
        }

        return Session::get(self::SESSION_KEY, []);
    }
}
