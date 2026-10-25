<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Gift;
use App\Models\Order;
use App\Models\Plant;
use App\Models\StoreSetting;
use App\Models\User;
use App\Models\UserGarden;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class GardenService
{
    /**
     * Adopt plants from a delivered, non-gift order to Digital Garden (PRD Section 6.1).
     * Creates one record PER UNIT (unit_number 1..quantity).
     *
     * @return array<int, UserGarden>
     */
    public function adoptFromOrder(Order $order): array
    {
        if ($order->order_status !== 'delivered') {
            return [];
        }

        if ($order->is_gift) {
            // PRD 6.1: Gift orders are adopted only through recipient Gift Claim!
            return [];
        }

        if (!$order->user_id) {
            // Guest order: will be adopted when guest creates or verifies account
            return [];
        }

        $user = $order->user;
        if (!$user) {
            return [];
        }

        return DB::transaction(function () use ($order, $user) {
            $created = [];
            $acquiredAt = $order->delivered_at ?? Carbon::now();
            $timezone = $user->timezone ?: (string) StoreSetting::get('store_timezone', 'Asia/Jakarta');

            foreach ($order->items as $item) {
                /** @var Plant $plant */
                $plant = $item->plant;
                if (!$plant) {
                    continue;
                }

                for ($unit = 1; $unit <= $item->quantity; $unit++) {
                    // Check idempotency via unique (order_item_id, unit_number)
                    $existing = UserGarden::where('order_item_id', $item->id)
                        ->where('unit_number', $unit)
                        ->first();

                    if ($existing) {
                        $created[] = $existing;
                        continue;
                    }

                    // Compute next_watering_at: 08:00 local time + water_interval_days, saved in UTC
                    $nextWateringAt = Carbon::parse($acquiredAt, 'UTC')
                        ->setTimezone($timezone)
                        ->addDays($plant->water_interval_days)
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC');

                    $nextFertilizingAt = Carbon::parse($acquiredAt, 'UTC')
                        ->setTimezone($timezone)
                        ->addDays($plant->fertilize_interval_days)
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC');

                    $garden = UserGarden::create([
                        'user_id' => $user->id,
                        'plant_id' => $plant->id,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'unit_number' => $unit,
                        'nickname' => ($item->quantity > 1) ? "{$plant->name} #{$unit}" : $plant->name,
                        'location_type' => $plant->default_location_type,
                        'acquired_at' => $acquiredAt,
                        'next_watering_at' => $nextWateringAt,
                        'next_fertilizing_at' => $nextFertilizingAt,
                        'health_status' => 'healthy',
                    ]);

                    $created[] = $garden;
                }
            }

            AuditLog::record('garden_adopted_from_order', $order, null, [
                'gardens_count' => count($created),
            ], 'Plants adopted to Digital Garden post-delivery.', $user->id);

            return $created;
        });
    }

    /**
     * Adopt plants from a Gift claim into recipient's Digital Garden (PRD Section 5.4).
     *
     * @return array<int, UserGarden>
     */
    public function adoptFromGift(Gift $gift, User $recipient): array
    {
        $order = $gift->order;
        if (!$order || $order->order_status !== 'delivered') {
            throw new RuntimeException('Gift cannot be claimed because the order has not been delivered yet.');
        }

        if (!$gift->isClaimable()) {
            throw new RuntimeException('This gift claim link is either expired, already claimed, or inactive.');
        }

        // Email match verification (PRD Section 5.4)
        if (strtolower(trim($recipient->email)) !== strtolower(trim($gift->recipient_email))) {
            throw new InvalidArgumentException('Your account email does not match the designated gift recipient email.');
        }

        return DB::transaction(function () use ($gift, $order, $recipient) {
            $lockedGift = Gift::where('id', $gift->id)->lockForUpdate()->firstOrFail();

            if ($lockedGift->status === 'claimed' || $lockedGift->claimed_at !== null) {
                throw new RuntimeException('This gift has already been claimed.');
            }

            $created = [];
            $acquiredAt = Carbon::now();
            $timezone = $recipient->timezone ?: (string) StoreSetting::get('store_timezone', 'Asia/Jakarta');

            foreach ($order->items as $item) {
                $plant = $item->plant;
                if (!$plant) continue;

                for ($unit = 1; $unit <= $item->quantity; $unit++) {
                    $existing = UserGarden::where('order_item_id', $item->id)
                        ->where('unit_number', $unit)
                        ->first();

                    if ($existing) {
                        $created[] = $existing;
                        continue;
                    }

                    $nextWateringAt = Carbon::now($timezone)
                        ->addDays($plant->water_interval_days)
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC');

                    $nextFertilizingAt = Carbon::now($timezone)
                        ->addDays($plant->fertilize_interval_days)
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC');

                    $garden = UserGarden::create([
                        'user_id' => $recipient->id,
                        'plant_id' => $plant->id,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'unit_number' => $unit,
                        'nickname' => ($item->quantity > 1) ? "Gift: {$plant->name} #{$unit}" : "Gift: {$plant->name}",
                        'location_type' => $plant->default_location_type,
                        'acquired_at' => $acquiredAt,
                        'next_watering_at' => $nextWateringAt,
                        'next_fertilizing_at' => $nextFertilizingAt,
                        'health_status' => 'healthy',
                    ]);

                    $created[] = $garden;
                }
            }

            // Update Gift record to claimed
            $lockedGift->status = 'claimed';
            $lockedGift->claimed_by_user_id = $recipient->id;
            $lockedGift->opened_at = $lockedGift->opened_at ?? Carbon::now();
            $lockedGift->claimed_at = Carbon::now();
            $lockedGift->save();

            AuditLog::record('gift_claimed', $lockedGift, null, [
                'recipient_id' => $recipient->id,
                'gardens_count' => count($created),
            ], 'Recipient successfully claimed gift and adopted plants into Garden.', $recipient->id);

            return $created;
        });
    }

    /**
     * Adopt guest order into member's Digital Garden after login/email verification (PRD Section 6.1).
     *
     * @return array<int, UserGarden>
     */
    public function adoptGuestOrder(Order $order, User $user): array
    {
        if ($order->is_gift) {
            throw new InvalidArgumentException('Gift orders must be claimed using the gift link.');
        }

        if ($order->user_id && $order->user_id !== $user->id) {
            throw new RuntimeException('This order is already associated with another account.');
        }

        // Link order to user
        $order->update(['user_id' => $user->id]);

        return $this->adoptFromOrder($order);
    }

    /**
     * Mark plant as watered (PRD Section 6.2).
     */
    public function markWatered(UserGarden $garden): UserGarden
    {
        $plant = $garden->plant;
        $user = $garden->user;
        $timezone = $user?->timezone ?: (string) StoreSetting::get('store_timezone', 'Asia/Jakarta');

        $nextWatering = Carbon::now($timezone)
            ->addDays($plant->water_interval_days)
            ->setTime(8, 0, 0)
            ->setTimezone('UTC');

        $garden->update([
            'last_watered_at' => Carbon::now(),
            'next_watering_at' => $nextWatering,
            'last_watering_reminder_sent_at' => null,
        ]);

        AuditLog::record('garden_watered', $garden, null, [
            'last_watered_at' => $garden->last_watered_at,
            'next_watering_at' => $garden->next_watering_at,
        ], 'Marked plant as watered.');

        return $garden;
    }

    /**
     * Mark plant as fertilized (PRD Section 6.2).
     */
    public function markFertilized(UserGarden $garden): UserGarden
    {
        $plant = $garden->plant;
        $user = $garden->user;
        $timezone = $user?->timezone ?: (string) StoreSetting::get('store_timezone', 'Asia/Jakarta');

        $nextFertilizing = Carbon::now($timezone)
            ->addDays($plant->fertilize_interval_days)
            ->setTime(8, 0, 0)
            ->setTimezone('UTC');

        $garden->update([
            'last_fertilized_at' => Carbon::now(),
            'next_fertilizing_at' => $nextFertilizing,
            'last_fertilizing_reminder_sent_at' => null,
        ]);

        AuditLog::record('garden_fertilized', $garden, null, [
            'last_fertilized_at' => $garden->last_fertilized_at,
            'next_fertilizing_at' => $garden->next_fertilizing_at,
        ], 'Marked plant as fertilized.');

        return $garden;
    }
}
