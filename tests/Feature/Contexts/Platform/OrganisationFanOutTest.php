<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresRegisteredOrganisations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Fixtures\Platform\Queue\FanOutVisitJob;
use Tests\Fixtures\Platform\Queue\LegacyStyleJob;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 8 on the isolated cluster: the fan-out E-3 (tactical design E-3, Q-8; T-5).
 * - `php artisan platform:fan-out <operation>` reads the REGISTRY and queues ONE E-2 job per routable organisation; the
 *   non-operational one is skipped and reported; no organisation database is opened by the fan-out itself;
 * - T-5: three operational organisations, one whose job fails (in its own database): a REAL `queue:work organisation`
 *   worker completes the other two and records the failure; the pending one never gets a job;
 * - an unknown operation, or one mapped to a class that is not an OrganisationJob, queues nothing;
 * - a repeated run queues one job per organisation again (at least once: jobs are idempotent, design §3.4).
 */
final class OrganisationFanOutTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const C = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const PENDING = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';

    /** @var array<string, string> */
    private array $childEnv;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        Artisan::call('migrate', ['--database' => 'platform_central', '--force' => true, '--path' => [
            'database/migrations/2026_03_05_000014_create_standard_laravel_tables.php',
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
            'database/migrations/2026_11_14_320000_create_organisation_jobs_table.php',
        ]]);
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
        $registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b', self::C => 'platform_pg_org_c'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            if ($id !== self::C) {   // C's job will fail in C's own database
                $context->connection->statement('CREATE TABLE t8_visits (identity text)');
            }
            $factory->release($context);
            $registry->add(self::operational($id, $locator));
        }
        $registry->add(ServedOrganisation::register(TenantId::fromString(self::PENDING), self::application()));

        $this->app->instance(OrganisationConnectionFactory::class, $factory);
        $this->app->instance(ServedOrganisationRepository::class, $registry);
        $this->app->instance(RegisteredOrganisations::class, new PostgresRegisteredOrganisations(DB::connection('platform_central')));
        config([
            'queue.connections.organisation.connection' => 'platform_central',
            'organisation_routing.fan_out' => ['t8.visit' => FanOutVisitJob::class, 't8.not-an-organisation-job' => LegacyStyleJob::class],
        ]);

        $this->childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql', 'QUEUE_CONNECTION' => 'sync',
            'APP_ROUTES_CACHE' => 'bootstrap/cache/membership-pg-never-a-route-cache.php',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $s['host'], 'port' => (int) $s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $s['username'], 'ORG_TEST_PASSWORD' => (string) $s['password']];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $this->childEnv += [$prefix . 'HOST' => (string) $s['host'], $prefix . 'PORT' => (string) $s['port'], $prefix . 'DATABASE' => 'platform_pg_central',
                $prefix . 'USERNAME' => (string) $s['username'], $prefix . 'PASSWORD' => (string) $s['password']];
        }
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function operational(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator($locator);
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    /** @return list<string> organisation ids of the queued jobs, in queue order */
    private static function queuedFor(): array
    {
        return array_map(static function ($row): string {
            $command = unserialize(json_decode($row->payload, true)['data']['command']);

            return $command->organisationId;
        }, DB::connection('platform_central')->select('SELECT payload FROM organisation_jobs ORDER BY id'));
    }

    /** @return list<string> */
    private static function openOrganisationConnections(): array
    {
        return array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_')));
    }

    public function test_the_registry_listing_holds_every_registered_organisation_in_a_stable_order(): void
    {
        $ids = array_map(static fn (TenantId $id): string => $id->value(), app(RegisteredOrganisations::class)->ids());
        $this->assertSame([self::A, self::B, self::C, self::PENDING], $ids);
    }

    public function test_t5_the_fan_out_queues_one_job_per_operational_organisation_and_skips_the_pending_one(): void
    {
        $exit = Artisan::call('platform:fan-out', ['operation' => 't8.visit']);
        $output = Artisan::output();

        $this->assertSame(0, $exit, $output);
        $this->assertSame([self::A, self::B, self::C], self::queuedFor(), 'one each; never the pending organisation');
        $this->assertStringContainsString('dispatched: 3, skipped: 1, failed: 0', $output);
        $this->assertStringContainsString(self::PENDING . ' skipped: not-operational', $output);
        $this->assertSame([], self::openOrganisationConnections(), 'the fan-out itself opens no organisation database');
    }

    public function test_t5_a_real_worker_completes_the_others_while_one_organisation_fails(): void
    {
        Artisan::call('platform:fan-out', ['operation' => 't8.visit']);

        $worker = new Process([PHP_BINARY, base_path('artisan'), 'queue:work', 'organisation', '--stop-when-empty', '--no-interaction'], base_path(), $this->childEnv, null, 300);
        $worker->run();

        $this->assertTrue($worker->isSuccessful(), $worker->getErrorOutput());
        $this->assertSame([self::A], array_map(static fn ($r) => $r->identity, DB::connection('platform_org_a')->select('SELECT identity FROM t8_visits')));
        $this->assertSame([self::B], array_map(static fn ($r) => $r->identity, DB::connection('platform_org_b')->select('SELECT identity FROM t8_visits')));
        $failed = DB::connection('platform_central')->table('failed_jobs')->get();
        $this->assertCount(1, $failed, "only C's job failed");
        $this->assertSame('organisation', $failed[0]->connection);
        $this->assertStringContainsString(self::C, $failed[0]->payload);
        $this->assertSame(0, (int) DB::connection('platform_central')->table('organisation_jobs')->count(), 'nothing left waiting');
    }

    public function test_an_unknown_operation_or_a_class_that_is_not_an_organisation_job_queues_nothing(): void
    {
        config(['organisation_routing.fan_out.t8_wrong_constructor' => \Tests\Fixtures\Platform\Queue\RecordVisitJob::class]);
        foreach ([
            't8.unknown' => 'Unknown fan-out operation',
            't8.not-an-organisation-job' => 'refused: A fanned-out operation is an OrganisationJob.',
            't8_wrong_constructor' => 'refused: A fanned-out job is constructed with the organisation id only.',
        ] as $operation => $message) {
            $this->assertSame(1, Artisan::call('platform:fan-out', ['operation' => $operation]), $operation);
            $this->assertStringContainsString($message, Artisan::output(), $operation);
            $this->assertSame([], self::queuedFor(), $operation);
        }
    }

    public function test_a_run_whose_dispatches_fail_reports_each_and_exits_non_zero(): void
    {
        DB::connection('platform_central')->statement('ALTER TABLE organisation_jobs RENAME TO t8_organisation_jobs_away');   // the queue is unavailable

        $exit = Artisan::call('platform:fan-out', ['operation' => 't8.visit']);
        $output = Artisan::output();

        $this->assertSame(1, $exit, 'a scheduler or operator sees the failure');
        $this->assertStringContainsString('dispatched: 0, skipped: 1, failed: 3', $output);
        $this->assertStringContainsString(self::A . ' failed: Illuminate\\Database\\QueryException', $output, 'per organisation, with the error class only');
    }

    public function test_a_repeated_run_queues_one_job_per_organisation_again(): void
    {
        Artisan::call('platform:fan-out', ['operation' => 't8.visit']);
        Artisan::call('platform:fan-out', ['operation' => 't8.visit']);

        $this->assertSame([self::A, self::B, self::C, self::A, self::B, self::C], self::queuedFor(),
            'at least once across runs, exactly once per run: the fanned-out jobs must be idempotent (§3.4)');
    }

    public function test_production_wiring(): void
    {
        $this->app->forgetInstance(RegisteredOrganisations::class);
        $this->assertInstanceOf(PostgresRegisteredOrganisations::class, app(RegisteredOrganisations::class));
        $this->assertSame([], (require config_path('organisation_routing.php'))['fan_out'], 'the shipped configuration fans out no operation yet');
    }
}
