<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Providers;

use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\Ports\ContactAccounts;
use App\Contexts\Platform\Application\Ports\OrganisationOperationReport;
use App\Contexts\Platform\Application\Ports\OrganisationRestoreDrill;
use App\Contexts\Platform\Application\Ports\OrganisationSchemaRepair;
use App\Contexts\Platform\Infrastructure\PgDumpRestoreDrill;
use App\Contexts\Platform\Application\Ports\TechnicalOperationLog;
use App\Contexts\Platform\Infrastructure\Console\OperateOnOrganisationCommand;
use App\Contexts\Platform\Infrastructure\Outbox\TechnicalOperationPerformedHydrator;
use App\Contexts\Platform\Infrastructure\OutboxOrganisationOperationReport;
use App\Contexts\Platform\Infrastructure\PostgresTechnicalOperationLog;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Platform\Infrastructure\ContainerRoutedOrganisation;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;
use App\Contexts\Platform\Infrastructure\Console\FanOutCommand;
use App\Contexts\Platform\Infrastructure\PostgresRegisteredOrganisations;
use App\Contexts\Platform\Application\Ports\IdentityBindings;
use App\Contexts\Platform\Application\Ports\SignedInAccount;
use App\Contexts\Platform\Infrastructure\Http\OrganisationEntry;
use App\Contexts\Platform\Infrastructure\IdentitySignedInAccount;
use App\Contexts\Platform\Infrastructure\NoIdentityBindingsYet;
use Illuminate\Routing\Router;
use App\Contexts\Platform\Application\Ports\OrganisationProvisioner;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Infrastructure\IdentityContactAccounts;
use App\Contexts\Platform\Infrastructure\PostgresOrganisationReadinessProbe;
use App\Contexts\Platform\Infrastructure\Console\PrepareOrganisationDatabaseCommand;
use App\Contexts\Platform\Infrastructure\ProcessOrganisationProvisioner;
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
        // task 5b: founding step 3 runs in its own process (the child inherits this process's environment)
        $this->app->bind(OrganisationProvisioner::class, static fn ($app): ProcessOrganisationProvisioner => new ProcessOrganisationProvisioner($app->basePath()));
        // task 11 (E-4): migration repair in the same dedicated process; the central record; the report to the organisation
        $this->app->bind(OrganisationSchemaRepair::class, static fn ($app): ProcessOrganisationProvisioner => new ProcessOrganisationProvisioner($app->basePath()));
        $this->app->bind(TechnicalOperationLog::class, static fn (): PostgresTechnicalOperationLog => new PostgresTechnicalOperationLog(DB::connection()));
        $this->app->bind(OrganisationOperationReport::class, OutboxOrganisationOperationReport::class);
        // task 12b (T-17, D-234): the restore drill on a never-routable scratch copy
        $this->app->bind(OrganisationRestoreDrill::class, static fn ($app): PgDumpRestoreDrill => new PgDumpRestoreDrill(
            $app->make(ServedOrganisationRepository::class),
            $app->make(OrganisationConnectionFactory::class),
            $app->make(OrganisationReadinessProbe::class),
            (string) config('organisation_routing.restore_drill.directory', sys_get_temp_dir()),
            array_values((array) config('organisation_routing.restore_drill.tool_prefix', [])),
            config('organisation_routing.restore_drill.tool_host'),
            config('organisation_routing.restore_drill.tool_port'),
        ));
        // task 5c: step 5 reads the L-1 facts (no migrator) and the contacts' accounts (through Identity, one adapter)
        $this->app->bind(OrganisationReadinessProbe::class, static fn ($app): PostgresOrganisationReadinessProbe => new PostgresOrganisationReadinessProbe(
            $app->make(ServedOrganisationRepository::class),
            $app->make(OrganisationConnectionFactory::class),
            array_values((array) config('organisation_routing.migration_paths', [])),
        ));
        $this->app->bind(ContactAccounts::class, IdentityContactAccounts::class);
        // task 10a (D-233): the published routing contract, one adapter for both interfaces
        $this->app->bind(RoutedOrganisation::class, ContainerRoutedOrganisation::class);
        $this->app->bind(RoutedOrganisationConnection::class, ContainerRoutedOrganisation::class);
        // task 8 (E-3): the registry listing the fan-out walks (central database)
        $this->app->bind(RegisteredOrganisations::class, static fn (): PostgresRegisteredOrganisations => new PostgresRegisteredOrganisations(DB::connection()));
        // task 6 (E-1): who is signed in (Identity), and Identity's bindings: NONE until its store exists (PBDIGIT-349)
        $this->app->bind(SignedInAccount::class, IdentitySignedInAccount::class);
        $this->app->bind(IdentityBindings::class, NoIdentityBindingsYet::class);
    }

    public function boot(): void
    {
        // task 11 (E-4): the organisation's operator-channel report is hydrated by Platform (it is Platform's event)
        $this->app->make(EventHydratorRegistry::class)->register(new TechnicalOperationPerformedHydrator());
        // task 6 (E-1): the registry entry middleware and the selection after login (shared hostname, D-219 (2))
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('organisation.entry', OrganisationEntry::class);
        $this->loadRoutesFrom(dirname(__DIR__) . '/Http/routes.php');   // route:cache compatible

        if ($this->app->runningInConsole()) {
            $this->commands([
                PrepareOrganisationDatabaseCommand::class,   // task 5b: the dedicated provisioning process
                FanOutCommand::class,                        // task 8: E-3 fan-out
                OperateOnOrganisationCommand::class,         // task 11: E-4 operator channel (technical only)
            ]);
        }
    }
}
