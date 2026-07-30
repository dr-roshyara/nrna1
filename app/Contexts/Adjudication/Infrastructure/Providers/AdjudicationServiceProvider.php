<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Providers;

use App\Contexts\Adjudication\Application\ChallengeRoutedReactionHandler;
use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Port\TransactionManager;
use App\Contexts\Adjudication\Application\Service\AdjudicationService;
use App\Contexts\Adjudication\Application\Service\CoordinatesAdjudication;
use App\Contexts\Adjudication\Application\Service\TransactionalAdjudicationService;
use App\Contexts\Adjudication\Domain\Repository\DeterminationRepository;
use App\Contexts\Adjudication\Infrastructure\Identity\UuidIdentityGenerator;
use App\Contexts\Adjudication\Infrastructure\Outbox\DeterminationIssuedHydrator;
use App\Contexts\Adjudication\Infrastructure\Outbox\OutboxEventAdapter;
use App\Contexts\Adjudication\Infrastructure\Repositories\EloquentAdjudicationProcessStore;
use App\Contexts\Adjudication\Infrastructure\Repositories\EloquentDeterminationRepository;
use App\Contexts\Adjudication\Infrastructure\Transaction\LaravelTransactionManager;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class AdjudicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IdentityGenerator::class, UuidIdentityGenerator::class);
        $this->app->bind(TransactionManager::class, LaravelTransactionManager::class);
        $this->app->bind(EventOutbox::class, OutboxEventAdapter::class);
        $this->app->bind(DeterminationRepository::class, EloquentDeterminationRepository::class);
        // WP-2: the APM's process store (orchestration state, not a domain repository — EPIC-004K §11).
        $this->app->bind(AdjudicationProcessStore::class, EloquentAdjudicationProcessStore::class);

        // AdjudicationService = transactional decorator over the (frozen)
        // CoordinatesAdjudication coordinator.
        $this->app->bind(AdjudicationService::class, function (Application $app): AdjudicationService {
            /** @var DeterminationRepository $repository */
            $repository = $app->make(DeterminationRepository::class);
            /** @var EventOutbox $outbox */
            $outbox = $app->make(EventOutbox::class);
            /** @var TransactionManager $transactions */
            $transactions = $app->make(TransactionManager::class);

            /** @var IdentityGenerator $identities */
            $identities = $app->make(IdentityGenerator::class);

            return new TransactionalAdjudicationService(
                new CoordinatesAdjudication($repository, $outbox, $identities),
                $transactions,
            );
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Tenant');

        // Event Registry (Blueprint Push B §6, §16 step 4): Adjudication owns
        // the hydrators for the events it produces.
        /** @var EventHydratorRegistry $registry */
        $registry = $this->app->make(EventHydratorRegistry::class);
        $registry->register(new DeterminationIssuedHydrator());

        // WP-4: Adjudication registers ITSELF as a consumer of Contestation's
        // published `ChallengeRouted`, so the relay/redrive resolves it by
        // (consumerContext='Adjudication', eventType='ChallengeRouted').
        // PB-006 *Registration =/= Delivery*: the CONSUMER decides that it
        // consumes; Contestation never names Adjudication (contract R-7).
        /** @var InboxHandlerRegistry $inboxRegistry */
        $inboxRegistry = $this->app->make(InboxHandlerRegistry::class);
        /** @var ChallengeRoutedReactionHandler $handler */
        $handler = $this->app->make(ChallengeRoutedReactionHandler::class);
        $inboxRegistry->register($handler);
    }
}
