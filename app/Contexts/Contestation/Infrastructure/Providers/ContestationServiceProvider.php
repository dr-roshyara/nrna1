<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Providers;

use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Contestation\Infrastructure\Repositories\EloquentChallengeRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Contestation context's Infrastructure. Step 5B: the Challenge persistence.
 * Messaging (outbox adapter, hydrators, inbox-handler registration) is deferred to 5C.
 */
final class ContestationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChallengeRepository::class, EloquentChallengeRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations/Tenant');
    }
}
