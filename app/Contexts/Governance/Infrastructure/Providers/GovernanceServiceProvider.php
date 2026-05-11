<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Providers;

use App\Contexts\Governance\Application\Ports\CommitteeGovernanceProjectorInterface;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Governance\Application\Ports\CommitteeProjectionRebuildRepository;
use App\Contexts\Governance\Application\Ports\GovernanceClock;
use App\Contexts\Governance\Application\Ports\LockInterface;
use App\Contexts\Governance\Application\Ports\RebuildRunRepository;
use App\Contexts\Governance\Application\UseCases\GetCommitteeHierarchy;
use App\Contexts\Governance\Application\Services\GovernanceProjectionRebuilder;
use App\Contexts\Governance\Infrastructure\Clock\SystemClock;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjector;
use App\Contexts\Governance\Infrastructure\Repositories\EloquentCommitteeProjectionRebuildRepository;
use App\Contexts\Governance\Infrastructure\Repositories\EloquentRebuildRunRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\ServiceProvider;

final class GovernanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ports
        $this->app->bind(GovernanceClock::class, SystemClock::class);
        $this->app->bind(CommitteeGovernanceProjectorInterface::class, CommitteeGovernanceProjector::class);
        $this->app->bind(CommitteeHierarchyQueryInterface::class, GetCommitteeHierarchy::class);

        $this->app->bind(CommitteeProjectionRebuildRepository::class, EloquentCommitteeProjectionRebuildRepository::class);

        $this->app->bind(RebuildRunRepository::class, EloquentRebuildRunRepository::class);

        // Lock: wraps Laravel's cache lock API
        $this->app->bind(LockInterface::class, function ($app) {
            $cache = $app->make(CacheManager::class);
            return new class($cache) implements LockInterface {
                public function __construct(private CacheManager $cache) {}
                public function acquire(string $key, int $ttlSeconds): bool
                {
                    return $this->cache->lock($key, $ttlSeconds)->get();
                }
                public function release(string $key): void
                {
                    $this->cache->lock($key)->forceRelease();
                }
            };
        });

        // Rebuilder
        $this->app->bind(GovernanceProjectionRebuilder::class, function ($app) {
            return new GovernanceProjectionRebuilder(
                projector: $app->make(CommitteeGovernanceProjectorInterface::class),
                rebuildRepository: $app->make(CommitteeProjectionRebuildRepository::class),
                rebuildRunRepository: $app->make(RebuildRunRepository::class),
                lock: $app->make(LockInterface::class),
                clock: $app->make(GovernanceClock::class),
            );
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Landlord');
    }
}
