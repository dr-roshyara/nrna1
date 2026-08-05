<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\PeriodicSynchronizations;
use App\Contexts\Membership\Infrastructure\Jobs\GenerateAnnualMembershipFees;
use App\Contexts\Membership\Infrastructure\Jobs\SendRenewalReminder;
use App\Contexts\Membership\Infrastructure\Jobs\MarkOverdueMembers;
use App\Contexts\Membership\Infrastructure\Jobs\CleanupExpiredInvitations;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Jobs
|--------------------------------------------------------------------------
|
| Define the application's command schedule here.
|
*/
Schedule::job(PeriodicSynchronizations::class)->everyFifteenMinutes();

// Clear voter count/stats caches for elections that have memberships
// expiring in the next hour. Covers the gap where expires_at passes
// naturally without firing any Eloquent model events.
Schedule::command('elections:flush-expiring-caches')->hourly();

// Membership: auto-reject expired applications + mark overdue fees (daily)
Schedule::command('membership:process-expiry')->daily();

// Membership: transition active→expired for members past their expiry date (daily)
Schedule::command('membership:expire')->daily();

// Membership: Scheduled jobs for automation (Phase 3A)
Schedule::job(GenerateAnnualMembershipFees::class)->daily()->at('01:00'); // 1 AM daily
Schedule::job(SendRenewalReminder::class)->daily()->at('08:00');         // 8 AM daily
Schedule::job(MarkOverdueMembers::class)->daily()->at('00:30');          // 12:30 AM daily
Schedule::job(CleanupExpiredInvitations::class)->daily()->at('02:00');   // 2 AM daily

// Phase 4A: Outbox event processor (process pending events every minute)
Schedule::command('outbox:process')->everyMinute();

// PB-003-C5: Inbox re-drive (recover due parked events; deadline → dead) every minute
Schedule::command('inbox:redrive')->everyMinute();

// Audit: delete election audit folders older than 30 days (daily at 3 AM)
Schedule::command('audit:cleanup')->dailyAt('03:00');
