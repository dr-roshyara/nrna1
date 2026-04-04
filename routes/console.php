<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\PeriodicSynchronizations;

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
