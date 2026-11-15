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
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\PostgresTechnicalOperationLog;
use App\Contexts\Platform\Infrastructure\ProcessOrganisationProvisioner;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-345 task 11 on the isolated cluster: the operator channel E-4, technical only (tactical design §3.8;
 * D-166 (1), D-219 (3), ISO-8, ISO-11; T-16), through the REAL command `platform:operate` and a REAL repair child.
 * - T-16.1 migration repair under the joining grant restores A's schema; T-16.2 measured by PostgreSQL's per-table
 *   read counters: no read of a business table (with a positive control proving the counters see a read);
 * - T-16.3 no joining grant → refused; T-16.4 B is never touched; T-16.5 a content read → refused (case grant, not
 *   offered); T-16.6 a provisioning organisation stays provisioning, and a schema whose readiness is not restored
 *   stays migration-failed; T-16.7 recorded centrally and reported in A's own outbox, identifiers only; T-16.8 a
 *   failing child → failed, unroutable, recorded and reported.
 */
final class OperatorChannelTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const C = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const PENDING = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';
    private const BUSINESS_TABLES = "'org_memberships', 'org_membership_facts', 'org_membership_commands'";
    private const REPAIRED = '2026_11_14_300300_create_ledger_readiness_function';

    private OrganisationConnectionFactory $factory;
    private PostgresServedOrganisationRepository $registry;
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
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
            'database/migrations/2026_11_15_330000_create_platform_technical_operations.php',
        ]]);
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
        $this->registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b', self::C => 'platform_pg_org_c'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            $this->factory->release($context);
            $this->registry->add($id === self::C ? self::provisioningWith($id, $locator) : self::operational($id, $locator));
        }
        $this->registry->add(ServedOrganisation::register(TenantId::fromString(self::PENDING), self::application()));
        self::admitAMember(self::A);   // business content exists in A
        foreach ([self::A, self::C] as $id) {
            $this->breakSchema($id);
        }

        $this->childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql', 'QUEUE_CONNECTION' => 'sync',
            'APP_ROUTES_CACHE' => 'bootstrap/cache/membership-pg-never-a-route-cache.php',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $s['host'], 'port' => (int) $s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $s['username'], 'ORG_TEST_PASSWORD' => (string) $s['password']];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $this->childEnv += [$prefix . 'HOST' => (string) $s['host'], $prefix . 'PORT' => (string) $s['port'], $prefix . 'DATABASE' => 'platform_pg_central',
                $prefix . 'USERNAME' => (string) $s['username'], $prefix . 'PASSWORD' => (string) $s['password']];
        }
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory);
        $this->app->instance(ServedOrganisationRepository::class, $this->registry);
        $this->app->instance(TechnicalOperationLog::class, new PostgresTechnicalOperationLog(DB::connection('platform_central')));
        $this->app->instance(OrganisationSchemaRepair::class, new ProcessOrganisationProvisioner(base_path(), $this->childEnv));
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

    private static function provisioningWith(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator($locator);

        return $o;
    }

    private static function operational(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = self::provisioningWith($id, $locator);
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    private static function org(string $id): \Illuminate\Database\Connection
    {
        return DB::connection([self::A => 'platform_org_a', self::B => 'platform_org_b', self::C => 'platform_org_c'][$id]);
    }

    private static function admitAMember(string $id): void
    {
        $repo = new PostgresMemberRepository(self::org($id), new ScriptedLedgerClockReader(static fn (): int => 1_760_000_000_000_123));
        $t = TenantId::fromString($id);
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(),
            Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
    }

    /** a failed organisation migration: the readiness function and its history row are gone; the registry says so */
    private function breakSchema(string $id): void
    {
        self::org($id)->statement('DROP FUNCTION org_membership_ledger_readiness(uuid)');
        self::org($id)->delete('DELETE FROM migrations WHERE migration = ?', [self::REPAIRED]);
        $o = $this->registry->find(TenantId::fromString($id));
        $o->markMigrationFailed();
        $this->registry->save($o);
    }

    private function operate(string $organisation, string $operation, string $operator = 'ops:alice'): array
    {
        $exit = Artisan::call('platform:operate', ['organisation' => $organisation, 'operation' => $operation, '--operator' => $operator]);

        return [$exit, Artisan::output()];
    }

    /** reads (sequential + index scans) of A's business tables, from PostgreSQL's own counters, once they are stable */
    private static function businessReads(string $id): int
    {
        $reading = static function () use ($id): int {
            DB::disconnect($id === self::A ? 'platform_org_a' : ($id === self::B ? 'platform_org_b' : 'platform_org_c'));   // a fresh session sees flushed counters
            $db = self::org($id);

            return (int) $db->selectOne('SELECT coalesce(sum(coalesce(seq_scan, 0) + coalesce(idx_scan, 0)), 0) AS n FROM pg_stat_user_tables WHERE relname IN (' . self::BUSINESS_TABLES . ')')->n;
        };
        $previous = $reading();
        for ($i = 0; $i < 20; $i++) {   // other sessions flush their counters when they end: wait until two readings agree
            usleep(250_000);
            $now = $reading();
            if ($now === $previous) {
                return $now;
            }
            $previous = $now;
        }

        return $previous;
    }

    private static function hasReadinessFunction(string $id): bool
    {
        return (bool) self::org($id)->selectOne("SELECT to_regprocedure('org_membership_ledger_readiness(uuid)') IS NOT NULL AS f")->f;
    }

    /** @return list<object> */
    private static function centralRecords(): array
    {
        return DB::connection('platform_central')->select('SELECT organisation_id, operation, operator, status, reason, reported FROM platform_technical_operations ORDER BY started_at, id');
    }

    public function test_t16_2_the_counters_see_a_business_read_positive_control(): void
    {
        $before = self::businessReads(self::A);
        self::org(self::A)->selectOne('SELECT count(*) AS n FROM org_memberships');

        $this->assertGreaterThan($before, self::businessReads(self::A), 'the instrument detects a read of a business table');
    }

    public function test_t16_1_and_2_a_migration_repair_restores_the_schema_without_reading_business_tables(): void
    {
        $before = self::businessReads(self::A);
        $bMigrations = static fn (): array => array_map(static fn ($r): string => $r->migration, self::org(self::B)->select('SELECT migration FROM migrations ORDER BY id'));
        $bBefore = [$bMigrations(), self::businessReads(self::B)];

        [$exit, $output] = $this->operate(self::A, 'migration-repair');

        $this->assertSame(0, $exit, $output);
        $this->assertTrue(self::hasReadinessFunction(self::A), "A's schema is repaired");
        $a = $this->registry->find(TenantId::fromString(self::A));
        $this->assertSame([PlatformStatus::Operational, SchemaState::Current], [$a->status(), $a->schemaState()]);
        $this->assertSame($before, self::businessReads(self::A), 'T-16.2: not one read of a business table');
        $this->assertSame($bBefore, [$bMigrations(), self::businessReads(self::B)], 'T-16.4: B untouched');
        $this->assertTrue((new ResolutionService($this->registry))->resolve(self::A)->organisationId->equals(TenantId::fromString(self::A)), 'routable again: readiness re-verified');
    }

    public function test_t16_7_the_operation_is_recorded_centrally_and_reported_in_the_organisations_own_outbox(): void
    {
        $this->operate(self::A, 'migration-repair');

        $this->assertEquals([(object) ['organisation_id' => self::A, 'operation' => 'migration-repair', 'operator' => 'ops:alice', 'status' => 'succeeded', 'reason' => null, 'reported' => true]],
            self::centralRecords());
        $reports = self::org(self::A)->select("SELECT payload FROM outbox_events WHERE event_type = 'TechnicalOperationPerformed'");
        $this->assertCount(1, $reports, "reported to A, in A's own database");
        $payload = json_decode((string) $reports[0]->payload, true);
        $this->assertSame(['operationId', 'organisationId', 'operation', 'operator', 'outcome', 'reason', 'requestedAt'], array_keys($payload), 'identifiers only: no business content');
        $this->assertSame([self::A, 'migration-repair', 'ops:alice', 'succeeded'], [$payload['organisationId'], $payload['operation'], $payload['operator'], $payload['outcome']]);
        $this->assertSame([], self::org(self::B)->select("SELECT 1 FROM outbox_events WHERE event_type = 'TechnicalOperationPerformed'"), 'nothing reported to B');
    }

    public function test_t16_3_without_the_joining_grant_and_for_an_unknown_organisation_it_is_refused_and_recorded(): void
    {
        foreach ([self::PENDING => 'no-joining-grant', '9f9f9f9f-9f9f-4f9f-8f9f-9f9f9f9f9f9f' => 'unknown-organisation'] as $id => $reason) {
            [$exit, $output] = $this->operate($id, 'migration-repair');
            $this->assertSame(1, $exit);
            $this->assertStringContainsString("refused: {$reason}", $output);
        }
        $this->assertSame(['refused', 'refused'], array_map(static fn ($r) => $r->status, self::centralRecords()));
        $this->assertFalse(self::hasReadinessFunction(self::A), 'nothing executed anywhere');
    }

    public function test_t16_5_a_content_read_is_refused_without_a_case_grant_and_reads_nothing(): void
    {
        $before = self::businessReads(self::A);

        [$exit, $output] = $this->operate(self::A, 'read-members');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('refused: case-grant-required', $output);
        $this->assertSame($before, self::businessReads(self::A), 'not one read of a business table');
        $this->assertEquals([(object) ['organisation_id' => self::A, 'operation' => 'read-members', 'operator' => 'ops:alice', 'status' => 'refused', 'reason' => 'case-grant-required', 'reported' => false]],
            self::centralRecords());
    }

    public function test_t16_6_a_repaired_provisioning_organisation_stays_provisioning_and_unroutable(): void
    {
        [$exit, $output] = $this->operate(self::C, 'migration-repair');

        $this->assertSame(0, $exit, $output);
        $c = $this->registry->find(TenantId::fromString(self::C));
        $this->assertSame([PlatformStatus::Provisioning, SchemaState::Current], [$c->status(), $c->schemaState()]);
        try {
            (new ResolutionService($this->registry))->resolve(self::C);
            $this->fail('routable after a repair alone');
        } catch (OrganisationNotRoutable $e) {
            $this->assertSame('not-operational', $e->reason->value);
        }
    }

    public function test_t16_6_the_schema_stays_failed_when_readiness_is_not_restored(): void
    {
        self::org(self::A)->delete('DELETE FROM org_membership_ledger_clocks');   // a readiness fact (L-1) broken beyond the migration set

        [$exit, $output] = $this->operate(self::A, 'migration-repair');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('failed: readiness-not-restored', $output);
        $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(TenantId::fromString(self::A))->schemaState());
        $records = self::centralRecords();
        $this->assertCount(1, $records, 'recorded centrally');
        $this->assertSame(['failed', 'readiness-not-restored'], [$records[0]->status, $records[0]->reason]);
    }

    public function test_t16_8_a_failing_repair_leaves_the_organisation_unroutable_recorded_and_reported(): void
    {
        $this->app->instance(OrganisationSchemaRepair::class, new ProcessOrganisationProvisioner(base_path(), ['ORG_TEST_PASSWORD' => 'wrong-pass-zz9'] + $this->childEnv));

        [$exit, $output] = $this->operate(self::A, 'migration-repair');

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('failed: repair-failed', $output);
        $this->assertStringNotContainsString('wrong-pass-zz9', $output);
        $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(TenantId::fromString(self::A))->schemaState());
        $this->assertFalse(self::hasReadinessFunction(self::A));
        $this->assertEquals([(object) ['organisation_id' => self::A, 'operation' => 'migration-repair', 'operator' => 'ops:alice', 'status' => 'failed', 'reason' => 'repair-failed', 'reported' => true]],
            self::centralRecords());
    }
}
