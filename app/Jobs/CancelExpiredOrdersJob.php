<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Plant;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();

        // PRD 4.3 & 4.4: Find unpaid orders past expiration
        $expiredOrders = Order::whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', $now)
            ->whereIn('payment_status', ['pending', 'rejected', 'pay_in_store_pending'])
            ->where('order_status', '!=', 'cancelled')
            ->get();

        foreach ($expiredOrders as $order) {
            try {
                DB::transaction(function () use ($order) {
                    /** @var Order $lockedOrder */
                    $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
                    if (!$lockedOrder || !in_array($lockedOrder->payment_status, ['pending', 'rejected', 'pay_in_store_pending'], true)) {
                        return; // Already resolved
                    }

                    $lockedOrder->payment_status = 'expired';
                    $lockedOrder->order_status = 'cancelled';
                    $lockedOrder->cancelled_at = Carbon::now();
                    $lockedOrder->cancel_reason = 'Payment deadline expired automatically';

                    // Restore stock exactly once
                    if ($lockedOrder->stock_restored_at === null) {
                        foreach ($lockedOrder->items as $item) {
                            Plant::where('id', $item->plant_id)->increment('stock', $item->quantity);
                        }
                        $lockedOrder->stock_restored_at = Carbon::now();
                    }

                    if ($lockedOrder->gift) {
                        $lockedOrder->gift->update(['status' => 'cancelled']);
                    }

                    $lockedOrder->save();

                    AuditLog::record('order_expired_cancelled', $lockedOrder, null, [
                        'payment_status' => 'expired',
                        'order_status' => 'cancelled',
                    ], 'Automatic cancellation by CancelExpiredOrdersJob.');
                });
            } catch (\Throwable $e) {
                Log::error('CancelExpiredOrdersJob error on order ' . $order->id, ['error' => $e->getMessage()]);
            }
        }
    }
}
