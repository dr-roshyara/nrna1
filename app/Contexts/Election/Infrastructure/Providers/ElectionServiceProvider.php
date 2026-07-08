<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Providers;

use App\Contexts\Election\Application\DeterminationIssuedReactionHandler;
use App\Contexts\Election\Application\Port\AppliedDeterminationLedger;
use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Election\Infrastructure\Acl\LegacyElectionExistenceAdapter;
use App\Contexts\Election\Infrastructure\Outbox\ElectionCorrectionAppliedHydrator;
use App\Contexts\Election\Infrastructure\Outbox\ReactionOutboxAdapter;
use App\Contexts\Election\Infrastructure\Persistence\EloquentAppliedDeterminationLedger;
use App\Contexts\Election\Infrastructure\Repository\CompositeElectionRepository;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Election context's Infrastructure. The `ElectionExistencePort` binding is the
 * Strangler seam: today → legacy ACL adapter; a future greenfield lifecycle capability
 * replaces it here, with no change to the domain or the composite repository.
 */
final class ElectionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ElectionExistencePort::class, LegacyElectionExistenceAdapter::class);
        $this->app->bind(AppliedDeterminationLedger::class, EloquentAppliedDeterminationLedger::class);
        $this->app->bind(ReactionEventOutbox::class, ReactionOutboxAdapter::class);
        $this->app->bind(ElectionRepository::class, CompositeElectionRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations/Tenant');

        // Register Election's inbox consumer so the relay/redrive resolves it by
        // (consumerContext='Election', eventType='DeterminationIssued').
        /** @var InboxHandlerRegistry $inboxRegistry */
        $inboxRegistry = $this->app->make(InboxHandlerRegistry::class);
        /** @var DeterminationIssuedReactionHandler $handler */
        $handler = $this->app->make(DeterminationIssuedReactionHandler::class);
        $inboxRegistry->register($handler);

        // Register the outbox hydrator so the relay can re-emit ElectionCorrectionApplied
        // (published Integration event; consumed downstream by Contestation).
        /** @var EventHydratorRegistry $hydratorRegistry */
        $hydratorRegistry = $this->app->make(EventHydratorRegistry::class);
        $hydratorRegistry->register(new ElectionCorrectionAppliedHydrator());
    }
}
