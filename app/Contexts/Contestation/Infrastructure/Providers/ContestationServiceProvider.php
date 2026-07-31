<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Providers;

use App\Contexts\Contestation\Application\AdjudicateChallengeHandler;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\Port\IdentityGenerator;
use App\Contexts\Contestation\Application\Port\TransactionManager;
use App\Contexts\Contestation\Application\ResolveChallengeHandler;
use App\Contexts\Contestation\Application\Service\ContestationService;
use App\Contexts\Contestation\Application\Service\CoordinatesContestation;
use App\Contexts\Contestation\Application\Service\TransactionalContestationService;
use App\Contexts\Contestation\Infrastructure\Identity\UuidIdentityGenerator;
use App\Contexts\Contestation\Infrastructure\Transaction\LaravelTransactionManager;
use Illuminate\Contracts\Foundation\Application;
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

        // WP-5: the raise path. Context-LOCAL ports (Adjudication's identical ones
        // may not be imported — contract R-1/R-2); the coordinator is wrapped in its
        // transactional boundary so `route()` saves the aggregate and writes its
        // outbox row atomically (ADR-T1).
        $this->app->bind(IdentityGenerator::class, UuidIdentityGenerator::class);
        $this->app->bind(TransactionManager::class, LaravelTransactionManager::class);
        $this->app->bind(ContestationService::class, function (Application $app): ContestationService {
            /** @var CoordinatesContestation $inner */
            $inner = $app->make(CoordinatesContestation::class);
            /** @var TransactionManager $transactions */
            $transactions = $app->make(TransactionManager::class);

            return new TransactionalContestationService($inner, $transactions);
        });
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
