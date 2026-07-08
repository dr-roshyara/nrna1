<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Providers;

use App\Contexts\Election\Application\Port\AppliedDeterminationStore;
use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Election\Infrastructure\Acl\LegacyElectionExistenceAdapter;
use App\Contexts\Election\Infrastructure\Persistence\EloquentAppliedDeterminationStore;
use App\Contexts\Election\Infrastructure\Repository\CompositeElectionRepository;
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
        $this->app->bind(AppliedDeterminationStore::class, EloquentAppliedDeterminationStore::class);
        $this->app->bind(ElectionRepository::class, CompositeElectionRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations/Tenant');
    }
}
