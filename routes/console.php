<?php

use App\Jobs\CancelExpiredOrdersJob;
use App\Jobs\ExpireGiftClaimsJob;
use App\Jobs\SendWateringRemindersJob;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes & Task Scheduling — Plantify Official v2.1
|--------------------------------------------------------------------------
*/

// Cancel expired unpaid orders and restore inventory every 5 minutes (PRD Section 4.4, 4.5, 4.6)
Schedule::job(new CancelExpiredOrdersJob)->everyFiveMinutes();

// Check watering and fertilizing reminders every 15 minutes with weather deferral (PRD Section 6.3 & 6.4)
Schedule::job(new SendWateringRemindersJob)->everyFifteenMinutes();

// Expire unredeemed gift claims every 15 minutes (PRD Section 5.3)
Schedule::job(new ExpireGiftClaimsJob)->everyFifteenMinutes();
