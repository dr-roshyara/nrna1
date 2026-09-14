<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

/**
 * Records users.last_login_at on successful authentication.
 *
 * Illuminate\Auth\Events\Login is fired by Laravel's SessionGuard for every
 * login entry point in this app (password login, Google/social login,
 * voter-invitation auto-login) — one listener covers all of them, since it
 * hooks the framework event rather than any specific controller.
 *
 * Records successful authentication only: a failed attempt never dispatches
 * this event, so no explicit failure-handling is needed here.
 */
class RecordLastLoginTimestamp
{
    public function handle(Login $event): void
    {
        $event->user->updateQuietly(['last_login_at' => now()]);
    }
}
