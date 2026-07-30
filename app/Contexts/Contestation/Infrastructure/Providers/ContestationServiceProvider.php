<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Providers;

use App\Contexts\Contestation\Application\AdjudicateChallengeHandler;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\ResolveChallengeHandler;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeAdjudicatedHydrator;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeOutboxAdapter;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeResolvedHydrator;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeRoutedHydrator;
use App\Contexts\Contestation\Infrastructure\Repositories\EloquentChallengeRepository;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Contestation context's Infrastructure. 5B: persistence. 5C: messaging —
 * the outbox adapter, the two hydrators, and inbox-handler registration.
 */
final class ContestationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChallengeRepository::class, EloquentChallengeRepository::class);
        $this->app->bind(ChallengeEventOutbox::class, ChallengeOutboxAdapter::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations/Tenant');

        // Register the outbox hydrators so the relay can re-emit Contestation's events.
        /** @var EventHydratorRegistry $hydrators */
        $hydrators = $this->app->make(EventHydratorRegistry::class);
        $hydrators->register(new ChallengeAdjudicatedHydrator());
        $hydrators->register(new ChallengeResolvedHydrator());
        // ADR-T21: ChallengeRouted is published language — registration completes it.
        $hydrators->register(new ChallengeRoutedHydrator());

        // Register the two inbox consumers (resolved by (Contestation, <eventType>)).
        /** @var InboxHandlerRegistry $inbox */
        $inbox = $this->app->make(InboxHandlerRegistry::class);
        /** @var AdjudicateChallengeHandler $adjudicate */
        $adjudicate = $this->app->make(AdjudicateChallengeHandler::class);
        /** @var ResolveChallengeHandler $resolve */
        $resolve = $this->app->make(ResolveChallengeHandler::class);
        $inbox->register($adjudicate);
        $inbox->register($resolve);
    }
}
