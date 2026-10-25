<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\UserGarden;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendWateringRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(WeatherService $weatherService): void
    {
        $now = Carbon::now();

        // Find gardens where watering is due
        $dueGardens = UserGarden::with(['user', 'plant'])
            ->whereNotNull('next_watering_at')
            ->where('next_watering_at', '<=', $now)
            ->get();

        foreach ($dueGardens as $garden) {
            $user = $garden->user;
            if (!$user || !$user->hasVerifiedEmail()) {
                continue;
            }

            $timezone = $user->timezone ?: 'Asia/Jakarta';
            $localToday = Carbon::now($timezone)->toDateString();

            // Deduplication: maximum 1 reminder per day (PRD Section 6.3)
            if ($garden->last_watering_reminder_sent_at) {
                $lastSentLocal = Carbon::parse($garden->last_watering_reminder_sent_at)->setTimezone($timezone)->toDateString();
                if ($lastSentLocal === $localToday) {
                    continue;
                }
            }

            try {
                DB::transaction(function () use ($garden, $user, $weatherService, $timezone, $localToday) {
                    /** @var UserGarden $lockedGarden */
                    $lockedGarden = UserGarden::where('id', $garden->id)->lockForUpdate()->first();
                    if (!$lockedGarden) return;

                    // Weather check for outdoor plants (PRD Section 6.4)
                    if ($lockedGarden->isOutdoor() && !empty($user->city)) {
                        $weather = $weatherService->checkWateringCondition($user->city);
                        if ($weather['should_defer']) {
                            // Defer watering by 1 day to 08:00 local time
                            $deferredDate = Carbon::now($timezone)->addDay()->setTime(8, 0, 0)->setTimezone('UTC');
                            $lockedGarden->next_watering_at = $deferredDate;
                            $lockedGarden->last_watering_reminder_sent_at = Carbon::now();
                            $lockedGarden->save();

                            AuditLog::record('watering_deferred_by_weather', $lockedGarden, null, [
                                'city' => $user->city,
                                'reason' => $weather['reason'],
                                'new_next_watering_at' => $deferredDate,
                            ], 'Weather check deferred outdoor watering reminder.', $user->id);

                            return;
                        }
                    }

                    // Record reminder sent timestamp
                    $lockedGarden->last_watering_reminder_sent_at = Carbon::now();
                    $lockedGarden->save();

                    AuditLog::record('watering_reminder_dispatched', $lockedGarden, null, [
                        'plant_name' => $lockedGarden->plant?->name,
                        'user_email' => $user->email,
                    ], 'Watering reminder email dispatched.', $user->id);
                });
            } catch (\Throwable $e) {
                Log::error('SendWateringRemindersJob failed for garden ' . $garden->id, ['error' => $e->getMessage()]);
            }
        }
    }
}
