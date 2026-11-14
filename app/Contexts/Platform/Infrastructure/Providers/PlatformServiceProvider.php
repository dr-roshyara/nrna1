<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Providers;

use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\PostgresLegacyOrganisationRegister;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * PBDIGIT-345: Platform's production wiring. The registry lives in the CENTRAL database (the default connection), never
 * in an organisation database (D-193, D-222). ResolutionService needs no binding of its own (autowired).
 */
final class PlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServedOrganisationRepository::class, static fn (): PostgresServedOrganisationRepository => new PostgresServedOrganisationRepository(DB::connection()));
        // task 5a: L-2 reads the legacy `organisations` table on the central database
        $this->app->bind(LegacyOrganisationRegister::class, static fn (): PostgresLegacyOrganisationRegister => new PostgresLegacyOrganisationRegister(DB::connection()));
        // task 3: one connection per organisation; logins from REAL environment variables (survive config:cache)
        $this->app->singleton(OrganisationConnectionFactory::class, static fn (): OrganisationConnectionFactory => new OrganisationConnectionFactory(
            (array) config('organisation_routing.clusters', []),
            new EnvironmentDatabaseLogin(getenv()),
        ));
        // task 4: the organisation migration set (Platform's and Membership's paths), applied by provisioning only
        $this->app->bind(OrganisationMigrations::class, static fn ($app): OrganisationMigrations => new OrganisationMigrations(
            $app->make('migrator'),
            array_values((array) config('organisation_routing.migration_paths', [])),
        ));
    }
}
