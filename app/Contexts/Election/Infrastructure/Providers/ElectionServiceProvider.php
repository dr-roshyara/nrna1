<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Providers;

use App\Contexts\Election\Application\DeterminationIssuedReactionHandler;
use App\Contexts\Election\Application\Port\AppliedDeterminationLedger;
use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Application\Port\EvidenceAnchorResolver;
use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Election\Infrastructure\Acl\LegacyElectionExistenceAdapter;
use App\Contexts\Election\Infrastructure\Config\ConfiguredEvidencePreservationDurations;
use App\Contexts\Election\Infrastructure\Config\TemporaryDefaultAnchorResolver;
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

        // WP-7 slice 7A. Election's OWN consumer-side durations port (R-44): it reads
        // CW and LSM from `config/election_preservation.php` and MAD from its one home,
        // `config/adjudication.php` — without importing Adjudication's port, which TP-1
        // forbids and Deptrac would fail.
        $this->app->bind(EvidencePreservationDurations::class, ConfiguredEvidencePreservationDurations::class);

        // WP-7B-R1: the EPW ANCHOR is an open Q-2 decision. Binding it here is what makes
        // that ruling a one-line substitution instead of a service edit (R-60 · R-70).
        $this->app->bind(EvidenceAnchorResolver::class, TemporaryDefaultAnchorResolver::class);
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
