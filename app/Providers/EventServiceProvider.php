<?php

namespace App\Providers;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Membership\MembershipApplicationRejected;
use App\Events\Membership\MembershipFeePaid;
use App\Events\Membership\MembershipRenewed;
use App\Listeners\InvalidateMembershipDashboardCache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            'App\Listeners\CreateUserOrganisationRole',
        ],
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        // ── Membership dashboard cache invalidation ──────────────────────────
        MembershipApplicationApproved::class => [InvalidateMembershipDashboardCache::class],
        MembershipApplicationRejected::class => [InvalidateMembershipDashboardCache::class],
        MembershipFeePaid::class             => [InvalidateMembershipDashboardCache::class],
        MembershipRenewed::class             => [InvalidateMembershipDashboardCache::class],
        // MembershipExpired::class — event not yet created (Phase 4 job)
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
