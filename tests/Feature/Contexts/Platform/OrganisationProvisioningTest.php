<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\FoundingRefused;
use App\Contexts\Platform\Application\FoundOrganisation;
use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Ports\ContactAccounts;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\Ports\OrganisationProvisioner;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Infrastructure\IdentityContactAccounts;
use App\Contexts\Platform\Infrastructure\PostgresOrganisationReadinessProbe;
use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationDatabasePreparer;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\OrganisationProvisioningRefused;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\ProcessOrganisationProvisioner;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 5b on the isolated cluster (tactical design §3.6 step 3; task 4 limit; T-9):
 * - step 3 runs in a CHILD PROCESS (`platform:prepare-organisation-database`), one organisation per process: the
 *   parent never opens the organisation's connection and its default connection is never switched;
 * - the preparer REFUSES to run in any other process (here: the test process itself);
 * - the child reads the locator from the REGISTRY, migrates the organisation's own database and binds it
 *   (`organisation_identity` = the registry id); Membership's trigger creates the ledger clock row;
 * - repeating step 3 changes nothing; a database already bound to ANOTHER organisation is refused and recorded as a
 *   failed migration; a failure never carries a password; the organisation is never routed before step 5.
 *
 * The child gets an EXPLICIT environment pointing every connection at the isolated cluster (never the development
 * database): PHPUnit's <server> values are not inherited by a child process.
 */
final class OrganisationProvisioningTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private OrganisationConnectionFactory $factory;
    /** @var array<string, string> */
    private array $childEnv;
    private string $password;

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
        ]]);
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $this->password = (string) $s['password'];
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
        $this->contactsActive(1);
        $this->childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $s['host'], 'port' => (int) $s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $s['username'], 'ORG_TEST_PASSWORD' => $this->password];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {   // every connection: the isolated central platform database
            $this->childEnv += [$prefix . 'HOST' => (string) $s['host'], $prefix . 'PORT' => (string) $s['port'], $prefix . 'DATABASE' => 'platform_pg_central',
                $prefix . 'USERNAME' => (string) $s['username'], $prefix . 'PASSWORD' => $this->password];
        }
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function registry(): PostgresServedOrganisationRepository
    {
        return new PostgresServedOrganisationRepository(DB::connection('platform_central'));
    }

    /** @param array<string, string> $envChanges */
    private function founding(array $envChanges = []): FoundOrganisation
    {
        $neverLegacy = new class implements LegacyOrganisationRegister {
            public function contains(TenantId $id): bool
            {
                return false;
            }
        };

        return new FoundOrganisation(self::registry(), $neverLegacy, new ProcessOrganisationProvisioner(base_path(), $envChanges + $this->childEnv),
            new PostgresOrganisationReadinessProbe(self::registry(), $this->factory, array_values((array) config('organisation_routing.migration_paths'))),
            $this->contacts);
    }

    /** 5c: Identity is outside this suite (its adapter has its own tests); the contacts' active-account count is set here */
    private ContactAccounts $contacts;

    private function contactsActive(int $n): void
    {
        $this->contacts = new class ($n) implements ContactAccounts {
            public function __construct(private readonly int $n)
            {
            }

            public function activeCount(array $accountIds): int
            {
                return $this->n;
            }
        };
    }

    public function test_5c_the_whole_founding_ends_operational_and_routed_to_its_own_database(): void
    {
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);
        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));

        $founding->markOperational(TenantId::fromString(self::A));

        $resolved = (new ResolutionService(self::registry()))->resolve(self::A);
        $this->assertTrue(self::locator('platform_pg_org_a')->equals($resolved->locator));
        $this->assertSame([], array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
            'the probe released the organisation connection');
    }

    /**
     * T-13 at the database: each L-1 fact broken alone, as far as the database allows. An identity row of ANOTHER
     * organisation cannot coexist with this organisation's clock row (the clock's foreign key), so that case names
     * both; the unit tests (FoundOrganisationTest) cover each fact alone.
     *
     * @return array<string, array{string, string}>
     */
    public static function brokenFacts(): array
    {
        return [
            'a migration of the set not recorded' => ["DELETE FROM migrations WHERE migration = '2026_11_14_300200_create_ledger_clock_on_organisation_identity'", 'ledger schema'],
            'a ledger table missing' => ['DROP TABLE org_membership_commands', 'ledger schema'],
            'the identity row of another organisation' => ['ALTER TABLE organisation_identity DISABLE TRIGGER organisation_identity_immutable; DELETE FROM org_membership_ledger_clocks;'
                . " UPDATE organisation_identity SET id = '" . self::B . "'; ALTER TABLE organisation_identity ENABLE TRIGGER organisation_identity_immutable", 'organisation identity, ledger clock row'],
            'the ledger clock row missing' => ['DELETE FROM org_membership_ledger_clocks', 'ledger clock row'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('brokenFacts')]
    public function test_5c_t13_operational_is_refused_when_a_fact_does_not_hold_in_the_organisation_database(string $break, string $missing): void
    {
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);
        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));
        DB::connection('platform_org_a')->unprepared($break);

        try {
            $founding->markOperational(TenantId::fromString(self::A));
            $this->fail('operational although missing: ' . $missing);
        } catch (FoundingRefused $e) {
            $this->assertStringEndsWith("missing: {$missing}.", $e->getMessage(), 'exactly the broken facts are named');
        }
        $this->assertSame(PlatformStatus::Provisioning, self::registry()->find(TenantId::fromString(self::A))->status());
    }

    public function test_5c_t13_operational_is_refused_without_a_contact_with_an_active_account(): void
    {
        $this->contactsActive(0);
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);
        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));

        $this->expectExceptionMessage('missing: a platform contact with an active account.');
        $founding->markOperational(TenantId::fromString(self::A));
    }

    public function test_5c_production_wiring_binds_the_probe_the_contacts_and_the_founding(): void
    {
        $this->assertInstanceOf(PostgresOrganisationReadinessProbe::class, app(OrganisationReadinessProbe::class));
        $this->assertInstanceOf(IdentityContactAccounts::class, app(ContactAccounts::class));
        $this->assertInstanceOf(ProcessOrganisationProvisioner::class, app(OrganisationProvisioner::class));
        $this->assertInstanceOf(FoundOrganisation::class, app(FoundOrganisation::class));
    }

    private static function locator(string $database): DatabaseLocator
    {
        return new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
    }

    private function upToProvisioning(FoundOrganisation $founding, string $id): void
    {
        $founding->register(TenantId::fromString($id), new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']));
        $founding->verifyCounterparty(TenantId::fromString($id), new CounterpartyVerification('agreement:' . $id));
        $founding->recordServiceOperationGrant(TenantId::fromString($id), new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $founding->startProvisioning(TenantId::fromString($id));
    }

    private static function hasTable(string $connection, string $table): bool
    {
        return DB::connection($connection)->selectOne('SELECT to_regclass(?) IS NOT NULL AS t', ["public.{$table}"])->t;
    }

    public function test_step_three_runs_in_a_child_process_and_prepares_only_that_database(): void
    {
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);
        $default = DB::getDefaultConnection();

        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));

        $this->assertSame($default, DB::getDefaultConnection(), 'the parent default connection was never switched');
        $this->assertSame([], array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
            'the parent never opened the organisation connection: the work ran in the child');
        $a = DB::connection('platform_org_a');
        $this->assertSame([self::A], array_map(static fn ($r) => $r->id, $a->select('SELECT id::text AS id FROM organisation_identity')), 'bound to the registry id');
        $this->assertSame([self::A], array_map(static fn ($r) => $r->id, $a->select('SELECT organisation_id::text AS id FROM org_membership_ledger_clocks')), 'the clock row, by Membership\'s trigger');
        foreach (['platform_org_b', 'platform_org_c'] as $other) {
            $this->assertFalse(self::hasTable($other, 'organisation_identity'), "{$other} untouched");
        }
        $this->assertFalse(self::hasTable('platform_central', 'organisation_identity'), 'the central database untouched');
        $this->assertSame(SchemaState::Current, self::registry()->find(TenantId::fromString(self::A))->schemaState());

        try {
            (new ResolutionService(self::registry()))->resolve(self::A);
            $this->fail('routed before step 5');
        } catch (OrganisationNotRoutable) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_repeating_step_three_changes_nothing(): void
    {
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);
        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));
        $a = DB::connection('platform_org_a');
        $before = [$a->select('SELECT migration FROM migrations ORDER BY id'), $a->select('SELECT id::text AS id, recorded_at::text AS at FROM organisation_identity')];

        $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));

        $this->assertEquals($before, [$a->select('SELECT migration FROM migrations ORDER BY id'), $a->select('SELECT id::text AS id, recorded_at::text AS at FROM organisation_identity')]);
    }

    public function test_the_preparer_refuses_to_run_outside_its_dedicated_process(): void
    {
        $this->upToProvisioning($this->founding(), self::A);
        $registry = self::registry();
        $o = $registry->find(TenantId::fromString(self::A));
        $o->assignLocator(self::locator('platform_pg_org_a'));
        $registry->save($o);
        $preparer = new OrganisationDatabasePreparer($registry, $this->factory, app(OrganisationMigrations::class));

        try {
            $preparer->prepare(TenantId::fromString(self::A));
            $this->fail('prepared inside the test process');
        } catch (OrganisationProvisioningRefused $e) {
            $this->assertStringContainsString('dedicated', $e->getMessage());
        }
        $this->assertFalse(self::hasTable('platform_org_a', 'migrations'), 'nothing migrated');
    }

    public function test_a_database_bound_to_another_organisation_is_refused_and_recorded_as_failed(): void
    {
        $b = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString(self::B), self::locator('platform_pg_org_b')));
        app(OrganisationMigrations::class)->apply($b);   // (test setup only) B's database, bound to B
        $b->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [self::B]);
        $founding = $this->founding();
        $this->upToProvisioning($founding, self::A);

        try {
            $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_b'));
            $this->fail('A was bound to B\'s database');
        } catch (ProvisioningFailed $e) {
            $this->assertStringContainsString('another organisation', $e->getMessage());
        }
        $this->assertSame(SchemaState::MigrationFailed, self::registry()->find(TenantId::fromString(self::A))->schemaState());
        $this->assertSame([self::B], array_map(static fn ($r) => $r->id, DB::connection('platform_org_b')->select('SELECT id::text AS id FROM organisation_identity')));
    }

    public function test_a_failing_child_fails_closed_and_never_reports_a_password(): void
    {
        $founding = $this->founding(['ORG_TEST_PASSWORD' => 'wrong-pass-zz9']);
        $this->upToProvisioning($founding, self::A);

        try {
            $founding->provision(TenantId::fromString(self::A), self::locator('platform_pg_org_a'));
            $this->fail('provisioned with a wrong password');
        } catch (ProvisioningFailed $e) {
            $this->assertStringNotContainsString('wrong-pass-zz9', $e->getMessage());
            $this->assertStringNotContainsString($this->password, $e->getMessage());
        }
        $this->assertSame(SchemaState::MigrationFailed, self::registry()->find(TenantId::fromString(self::A))->schemaState());
        $this->assertFalse(self::hasTable('platform_org_a', 'organisation_identity'));
    }
}
