<?php

namespace App\Providers;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Newsletter\NewsletterEmailFailed;
use App\Events\Newsletter\NewsletterEmailSent;
use App\Listeners\Newsletter\UpdateNewsletterCounters;
use App\Events\Membership\MembershipApplicationRejected;
use App\Events\MembershipFeePaid;
use App\Events\Membership\MembershipRenewed;
use App\Listeners\InvalidateMembershipDashboardCache;
use App\Listeners\CreateIncomeForMembershipFee;
use App\Listeners\Membership\RecalculateMemberFeeStatus;
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
        MembershipFeePaid::class             => [
            InvalidateMembershipDashboardCache::class,
            RecalculateMemberFeeStatus::class,
            CreateIncomeForMembershipFee::class,
        ],
        MembershipRenewed::class             => [InvalidateMembershipDashboardCache::class],
        // MembershipExpired::class — event not yet created (Phase 4 job)

        // MembershipExpired::class — event not yet created (Phase 4 job) (duplicate removed)
    ];

    /**
     * Disable auto-discovery so listeners are only registered via $listen above.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // ── Newsletter send counters + kill switch ───────────────────────────
        Event::listen(NewsletterEmailSent::class, [UpdateNewsletterCounters::class, 'handleSent']);
        Event::listen(NewsletterEmailFailed::class, [UpdateNewsletterCounters::class, 'handleFailed']);

        // ── Membership fee paid event listeners ───────────────────────────────
        Event::listen(MembershipFeePaid::class, [CreateIncomeForMembershipFee::class, 'handle']);
    }
}
