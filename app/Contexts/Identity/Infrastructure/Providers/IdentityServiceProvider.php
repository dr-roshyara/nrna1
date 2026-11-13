<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure\Providers;

use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Infrastructure\LaravelCurrentAccount;
use App\Contexts\Identity\Infrastructure\PostgresAccountDirectory;
use App\Contexts\Identity\Infrastructure\PostgresContextPersonRefs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * Identity's ports (D-225 IG-1 … IG-3; bound in PBDIGIT-346 G-4f, deferred from G-0). The CENTRAL (default)
 * connection: Identity is central (D-222: Laravel's default connection stays central).
 */
final class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CurrentAccount::class, static fn (): LaravelCurrentAccount => new LaravelCurrentAccount(Auth::guard()));
        $this->app->bind(AccountDirectory::class, static fn (): PostgresAccountDirectory => new PostgresAccountDirectory(DB::connection()));
        $this->app->bind(ContextPersonRefs::class, static fn (): PostgresContextPersonRefs => new PostgresContextPersonRefs(DB::connection()));
    }
}
