<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Providers;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Port\TransactionManager;
use App\Contexts\Adjudication\Application\Service\AdjudicationService;
use App\Contexts\Adjudication\Application\Service\CoordinatesAdjudication;
use App\Contexts\Adjudication\Application\Service\TransactionalAdjudicationService;
use App\Contexts\Adjudication\Domain\Repository\DeterminationRepository;
use App\Contexts\Adjudication\Infrastructure\Identity\UuidIdentityGenerator;
use App\Contexts\Adjudication\Infrastructure\Outbox\OutboxEventAdapter;
use App\Contexts\Adjudication\Infrastructure\Repositories\EloquentDeterminationRepository;
use App\Contexts\Adjudication\Infrastructure\Transaction\LaravelTransactionManager;
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

        // AdjudicationService = transactional decorator over the (frozen)
        // CoordinatesAdjudication coordinator.
        $this->app->bind(AdjudicationService::class, function (Application $app): AdjudicationService {
            /** @var DeterminationRepository $repository */
            $repository = $app->make(DeterminationRepository::class);
            /** @var EventOutbox $outbox */
            $outbox = $app->make(EventOutbox::class);
            /** @var TransactionManager $transactions */
            $transactions = $app->make(TransactionManager::class);

            return new TransactionalAdjudicationService(
                new CoordinatesAdjudication($repository, $outbox),
                $transactions,
            );
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Tenant');
    }
}
