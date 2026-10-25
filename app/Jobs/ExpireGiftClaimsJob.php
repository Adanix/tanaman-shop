<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\Gift;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireGiftClaimsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();

        // PRD 5.3: Mark claimable gifts expired if expires_at <= now()
        $expiredGifts = Gift::where('status', 'claimable')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        foreach ($expiredGifts as $gift) {
            try {
                $gift->update(['status' => 'expired']);

                AuditLog::record('gift_claim_expired', $gift, null, [
                    'token' => $gift->token,
                    'recipient_email' => $gift->recipient_email,
                ], 'Gift claim period expired automatically.');
            } catch (\Throwable $e) {
                Log::error('ExpireGiftClaimsJob error on gift ' . $gift->id, ['error' => $e->getMessage()]);
            }
        }
    }
}
