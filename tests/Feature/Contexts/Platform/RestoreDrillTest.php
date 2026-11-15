<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Ports\OrganisationRestoreDrill;
use App\Contexts\Platform\Application\Ports\OrganisationSchemaRepair;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\Ports\TechnicalOperationLog;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PgDumpRestoreDrill;
use App\Contexts\Platform\Infrastructure\PostgresOrganisationReadinessProbe;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\PostgresTechnicalOperationLog;
use App\Contexts\Platform\Infrastructure\ProcessOrganisationProvisioner;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-345 task 12b on the isolated cluster: T-17 under D-234 (Option A, restore in place; the locator stays
 * immutable). Through the REAL operator channel, with the REAL PostgreSQL tools (pg_dump, pg_restore):
 * - the restore drill backs A up, restores the backup into a SCRATCH database (never in the registry, so never
 *   routable), verifies A's identity and L-1 facts ON THE COPY, then removes the copy and the backup; the registry
 *   is untouched (row_version);
 * - a backup that does not verify fails the drill and changes nothing;
 * - the in-place recovery procedure: back up, (damage), take out for restore (unroutable, status kept), restore into
 *   A's OWN database, migration-repair: routable again only through identity and L-1, the locator unchanged, A's data
 *   back; B routable throughout and untouched;
 * - a WRONG backup (B's) restored into A is refused by the repair: A stays unroutable.
 */
final class RestoreDrillTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    /** @var array{host: string, port: string|int, username: string, password: string} */
    private array $s;
    private OrganisationConnectionFactory $factory;
    private PostgresServedOrganisationRepository $registry;
    private string $work;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        Artisan::call('migrate', ['--database' => 'platform_central', '--force' => true, '--path' => [
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
            'database/migrations/2026_11_15_330000_create_platform_technical_operations.php',
        ]]);
        $this->s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $this->s['host'], 'port' => $this->s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $this->s['username'], 'ORG_TEST_PASSWORD' => $this->s['password']]),
        );
        $this->registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            $this->factory->release($context);
            $this->registry->add(self::operational($id, $locator));
        }
        $repo = new PostgresMemberRepository(DB::connection('platform_org_a'), new ScriptedLedgerClockReader(static fn (): int => 1_760_000_000_000_123));
        $m = $repo->create(TenantId::fromString(self::A), MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();
        $repo->append(TenantId::fromString(self::A), $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(),
            Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');

        $this->work = sys_get_temp_dir() . '/t17-' . bin2hex(random_bytes(4));
        mkdir($this->work, 0700);
        $childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql', 'QUEUE_CONNECTION' => 'sync',
            'APP_ROUTES_CACHE' => 'bootstrap/cache/membership-pg-never-a-route-cache.php',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $this->s['host'], 'port' => (int) $this->s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $this->s['username'], 'ORG_TEST_PASSWORD' => (string) $this->s['password']];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $childEnv += [$prefix . 'HOST' => (string) $this->s['host'], $prefix . 'PORT' => (string) $this->s['port'], $prefix . 'DATABASE' => 'platform_pg_central',
                $prefix . 'USERNAME' => (string) $this->s['username'], $prefix . 'PASSWORD' => (string) $this->s['password']];
        }
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory);
        $this->app->instance(ServedOrganisationRepository::class, $this->registry);
        $this->app->instance(TechnicalOperationLog::class, new PostgresTechnicalOperationLog(DB::connection('platform_central')));
        $this->app->instance(OrganisationSchemaRepair::class, new ProcessOrganisationProvisioner(base_path(), $childEnv));
        $this->app->instance(OrganisationRestoreDrill::class, new PgDumpRestoreDrill($this->registry, $this->factory,
            new PostgresOrganisationReadinessProbe($this->registry, $this->factory, array_values((array) config('organisation_routing.migration_paths'))), $this->work,
            $this->toolPrefix(), '127.0.0.1', '5432'));
    }

    protected function tearDown(): void
    {
        foreach (glob($this->work . '/*') ?: [] as $file) {
            unlink($file);
        }
        if (is_dir($this->work)) {
            rmdir($this->work);
        }
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function operational(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']));
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator($locator);
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    private function operate(string $organisation, string $operation): array
    {
        $exit = Artisan::call('platform:operate', ['organisation' => $organisation, 'operation' => $operation, '--operator' => 'ops:alice']);

        return [$exit, Artisan::output()];
    }

    /**
     * The cluster's OWN client tools (PostgreSQL 15, inside the harness container): the host's tools are newer and
     * write settings a 15 server rejects (SET transaction_timeout). The same rule binds administrators (admin notes).
     *
     * @return list<string>
     */
    private function toolPrefix(): array
    {
        $container = (string) getenv('MEMBERSHIP_PG_CONTAINER');
        $this->assertNotSame('', $container, 'the harness exports MEMBERSHIP_PG_CONTAINER');

        return ['podman', 'exec', '-i', '-e', 'PGPASSWORD', $container];
    }

    /** the documented procedure's tools, as an administrator runs them (admin notes); the file streams via stdin/stdout */
    private function pgTool(?string $stdinFile, ?string $stdoutFile, string ...$args): void
    {
        $p = new Process([...$this->toolPrefix(), ...$args, '-h', '127.0.0.1', '-p', '5432', '-U', (string) $this->s['username']],
            null, ['PGPASSWORD' => (string) $this->s['password']], $stdinFile === null ? null : fopen($stdinFile, 'rb'), 120);
        $out = $stdoutFile === null ? null : fopen($stdoutFile, 'wb');
        $p->run($out === null ? null : static function (string $type, string $chunk) use ($out): void {
            if ($type === Process::OUT) {
                fwrite($out, $chunk);
            }
        });
        if ($out !== null) {
            fclose($out);
        }
        $this->assertTrue($p->isSuccessful(), $args[0] . ': ' . $p->getErrorOutput());
    }

    private function dump(string $database, string $file): void
    {
        $this->pgTool(null, $file, 'pg_dump', '-Fc', '-d', $database);
    }

    private function restoreInPlace(string $file, string $database): void
    {
        DB::disconnect('platform_org_a');
        $this->pgTool($file, null, 'pg_restore', '--clean', '--if-exists', '--no-owner', '--exit-on-error', '-d', $database);
    }

    private function rowVersion(string $id): int
    {
        return (int) DB::connection('platform_central')->selectOne('SELECT row_version AS v FROM platform_served_organisations WHERE id = ?::uuid', [$id])->v;
    }

    /** @return list<string> */
    private static function scratchDatabases(): array
    {
        return array_map(static fn ($r) => $r->datname, DB::connection('platform_central')->select("SELECT datname FROM pg_database WHERE datname LIKE 'scratch_restore_%'"));
    }

    private function routes(string $id): bool
    {
        try {
            (new ResolutionService($this->registry))->resolve($id);

            return true;
        } catch (OrganisationNotRoutable) {
            return false;
        }
    }

    public function test_t17_the_drill_verifies_a_restored_copy_that_is_never_routable_and_touches_nothing(): void
    {
        $version = $this->rowVersion(self::A);

        [$exit, $output] = $this->operate(self::A, 'restore-drill');

        $this->assertSame(0, $exit, $output);
        $this->assertSame($version, $this->rowVersion(self::A), 'the registry is untouched (row_version)');
        $this->assertSame([], self::scratchDatabases(), 'the scratch copy is removed');
        $this->assertSame([], glob($this->work . '/*'), 'the backup file is removed');
        $this->assertSame([], DB::connection('platform_central')->select("SELECT 1 FROM platform_served_organisations WHERE locator_database LIKE 'scratch_restore_%'"),
            'no registry entry ever pointed at the copy');
        $this->assertTrue($this->routes(self::A));
        $this->assertSame('platform_pg_org_a', $this->registry->find(TenantId::fromString(self::A))->locator()->database, 'the locator is unchanged');
        $this->assertSame([['succeeded', true]], array_map(static fn ($r) => [$r->status, $r->reported],
            DB::connection('platform_central')->select('SELECT status, reported FROM platform_technical_operations')));
    }

    public function test_t17_a_backup_that_does_not_verify_fails_the_drill_and_changes_nothing(): void
    {
        DB::connection('platform_org_a')->statement('DROP FUNCTION org_membership_ledger_readiness(uuid)');   // the backup will lack it
        $version = $this->rowVersion(self::A);

        [$exit, $output] = $this->operate(self::A, 'restore-drill');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('failed: restore-not-verified', $output);
        $this->assertSame($version, $this->rowVersion(self::A));
        $this->assertSame([], self::scratchDatabases(), 'removed even when it fails');
        $this->assertSame([], glob($this->work . '/*'));
    }

    public function test_t17_the_drill_judges_the_restored_copy_not_the_live_database(): void
    {
        // the restore is corrupted in transit: after pg_restore, the COPY loses its identity table. A is healthy.
        $wrapper = $this->work . '/corrupting-restore.sh';
        file_put_contents($wrapper, "#!/usr/bin/env bash\n"
            . 'podman exec -i -e PGPASSWORD ' . escapeshellarg((string) getenv('MEMBERSHIP_PG_CONTAINER')) . ' "$@" || exit $?' . "\n"
            . 'if [ "$1" = pg_restore ]; then db=""; prev=""; for a in "$@"; do [ "$prev" = "-d" ] && db="$a"; prev="$a"; done' . "\n"
            . '  podman exec -e PGPASSWORD ' . escapeshellarg((string) getenv('MEMBERSHIP_PG_CONTAINER')) . ' psql -h 127.0.0.1 -p 5432 -U '
            . escapeshellarg((string) $this->s['username']) . ' -d "$db" -qc "DROP TABLE organisation_identity CASCADE" >/dev/null || exit 9; fi' . "\n");
        $this->app->instance(OrganisationRestoreDrill::class, new PgDumpRestoreDrill($this->registry, $this->factory,
            new PostgresOrganisationReadinessProbe($this->registry, $this->factory, array_values((array) config('organisation_routing.migration_paths'))), $this->work,
            ['bash', $wrapper], '127.0.0.1', '5432'));
        $version = $this->rowVersion(self::A);

        [$exit, $output] = $this->operate(self::A, 'restore-drill');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('failed: restore-not-verified', $output, 'the copy lacks its identity: the drill must see THAT, not the healthy live A');
        $this->assertTrue($this->routes(self::A), 'the live organisation is untouched');
        $this->assertSame($version, $this->rowVersion(self::A));
        $this->assertSame([], self::scratchDatabases());
        unlink($wrapper);
    }

    public function test_t17_the_in_place_recovery_procedure_returns_a_only_through_repair_and_leaves_b_untouched(): void
    {
        $backup = $this->work . '/a.dump';
        $this->dump('platform_pg_org_a', $backup);                                              // 1. a backup
        DB::connection('platform_org_a')->statement('DROP TABLE org_membership_commands CASCADE');   // the failure
        $bBefore = [$this->rowVersion(self::B), DB::connection('platform_org_b')->select('SELECT migration FROM migrations ORDER BY id')];

        [$exit, $output] = $this->operate(self::A, 'take-out-for-restore');                    // 2. out of routing
        $this->assertSame(0, $exit, $output);
        $a = $this->registry->find(TenantId::fromString(self::A));
        $this->assertSame(PlatformStatus::Operational, $a->status(), 'status kept');
        $this->assertFalse($this->routes(self::A), 'unroutable during the restore');
        $this->assertTrue($this->routes(self::B), 'B routable throughout');

        $this->restoreInPlace($backup, 'platform_pg_org_a');                                    // 3. into A's OWN database
        $this->assertFalse($this->routes(self::A), 'a completed restore alone does not route');

        [$exit, $output] = $this->operate(self::A, 'migration-repair');                          // 4. back only through repair
        $this->assertSame(0, $exit, $output);
        $this->assertTrue($this->routes(self::A), 'routable again: identity and L-1 verified');
        $this->assertSame('platform_pg_org_a', $this->registry->find(TenantId::fromString(self::A))->locator()->database, 'the locator never changed');
        $this->assertSame(1, (int) DB::connection('platform_org_a')->selectOne('SELECT count(*) AS n FROM org_memberships')->n, "A's data is back");
        $this->assertTrue((bool) DB::connection('platform_org_a')->selectOne("SELECT to_regclass('public.org_membership_commands') IS NOT NULL AS t")->t);
        $this->assertEquals($bBefore, [$this->rowVersion(self::B), DB::connection('platform_org_b')->select('SELECT migration FROM migrations ORDER BY id')], 'B untouched');
        $this->assertTrue($this->routes(self::B));
    }

    public function test_t17_a_wrong_backup_restored_in_place_is_refused_and_a_stays_unroutable(): void
    {
        $wrong = $this->work . '/b.dump';
        $this->dump('platform_pg_org_b', $wrong);                                                // B's backup, by mistake
        $this->operate(self::A, 'take-out-for-restore');
        $this->restoreInPlace($wrong, 'platform_pg_org_a');

        [$exit, $output] = $this->operate(self::A, 'migration-repair');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('failed: repair-failed', $output);
        $this->assertFalse($this->routes(self::A), 'a database bound to another organisation never routes as A');
        $this->assertSame('platform_pg_org_a', $this->registry->find(TenantId::fromString(self::A))->locator()->database);
        $this->assertTrue($this->routes(self::B));
    }
}
