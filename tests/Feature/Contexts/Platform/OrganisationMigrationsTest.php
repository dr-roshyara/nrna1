<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\OrganisationMigrationRefused;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 4 on the isolated cluster (tactical design §3.5, M-5; T-8; plan task 4):
 * - the ORGANISATION migration set runs on one organisation's own connection: its history (the `migrations` table)
 *   lives in that database; other organisations and the central database are untouched; a repeat applies nothing;
 * - it refuses the default (central) connection and the central database;
 * - T-8 (F-13): in an organisation database, `org_memberships.organisation_id` references the one-row
 *   `organisation_identity`, so a row for ANOTHER organisation is refused by the database itself;
 * - a failing migration leaves its own changes rolled back and the history exact (PostgreSQL DDL is transactional);
 * - parity: the organisation ledger schema equals the central one, except F-13's target.
 */
final class OrganisationMigrationsTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const C = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const LEDGER = ['org_memberships', 'org_membership_facts', 'org_membership_commands', 'org_membership_ledger_clocks'];

    private OrganisationConnectionFactory $factory;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private function context(string $id, string $database): OrganisationContext
    {
        return $this->factory->establish(new ResolvedOrganisation(TenantId::fromString($id), new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST')));
    }

    private static function migrations(): OrganisationMigrations
    {
        return app(OrganisationMigrations::class);   // the production wiring: both paths from organisation_routing.migration_paths
    }

    private static function hasTable(string $connection, string $table): bool
    {
        return DB::connection($connection)->selectOne('SELECT to_regclass(?) IS NOT NULL AS t', ["public.{$table}"])->t;
    }

    public function test_the_set_runs_on_one_organisation_database_only_and_a_repeat_applies_nothing(): void
    {
        $a = $this->context(self::A, 'platform_pg_org_a');
        $defaultBefore = DB::getDefaultConnection();

        $applied = self::migrations()->apply($a);

        $this->assertNotEmpty($applied);
        $this->assertSame($defaultBefore, DB::getDefaultConnection(), 'the default connection is restored');
        $this->assertSame($applied, array_map(static fn ($r) => $r->migration, $a->connection->select('SELECT migration FROM migrations ORDER BY id')), 'its history lives in its own database');
        foreach (['organisation_identity', ...self::LEDGER] as $t) {
            $this->assertTrue(self::hasTable('platform_org_a', $t), "A has {$t}");
            $this->assertFalse(self::hasTable('platform_org_b', $t), "B has no {$t}");
            $this->assertFalse(self::hasTable('platform_central', $t), "the central database has no {$t}");
        }
        $this->assertFalse(self::hasTable('platform_org_b', 'migrations'), 'B has no history');
        $this->assertSame([], array_values(array_intersect($applied, array_map(static fn ($r) => $r->migration, DB::select('SELECT migration FROM migrations')))), 'the default database recorded none of them');

        $this->assertSame([], self::migrations()->apply($a), 'a repeat applies nothing');
        $this->assertSame([], self::migrations()->pending($a));
        $this->assertSame($applied, self::migrations()->pending($this->context(self::B, 'platform_pg_org_b')), 'B: all pending, nothing applied');
    }

    public function test_the_default_connection_and_the_central_database_are_refused(): void
    {
        foreach ([new OrganisationContext(TenantId::fromString(self::A), DB::connection()), new OrganisationContext(TenantId::fromString(self::A), DB::connection('platform_central'))] as $i => $wrong) {
            try {
                self::migrations()->apply($wrong);
                $this->fail("case {$i}: migrated outside an organisation database");
            } catch (OrganisationMigrationRefused) {
                $this->addToAssertionCount(1);
            }
        }
        $this->assertFalse(self::hasTable('platform_central', 'organisation_identity'), 'nothing written');

        // mutant O2: a correctly NAMED organisation connection whose registry locator points at the central database
        // (a misconfigured registry entry) is refused by the database check, not by the name check
        $misrouted = $this->context(self::A, DB::connection()->getDatabaseName());
        try {
            self::migrations()->apply($misrouted);
            $this->fail('migrated the central database through a misrouted organisation connection');
        } catch (OrganisationMigrationRefused $e) {
            $this->assertStringContainsString('central database', $e->getMessage());
        }
        $this->assertFalse((bool) DB::selectOne("SELECT to_regclass('public.organisation_identity') IS NOT NULL AS t")->t, 'the central database is untouched');
    }

    public function test_t8_the_database_refuses_a_membership_of_another_organisation(): void
    {
        $a = $this->context(self::A, 'platform_pg_org_a');
        self::migrations()->apply($a);
        $a->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [self::A]);

        $a->connection->insert("INSERT INTO org_memberships (organisation_id, id, person_ref, version, created_at) VALUES (?, 'm1', 'p1', 0, now())", [self::A]);
        $refusals = [
            'a membership of another organisation' => ["INSERT INTO org_memberships (organisation_id, id, person_ref, version, created_at) VALUES ('" . self::B . "', 'm2', 'p2', 0, now())", 'org_memberships_organisation_identity_fk'],
            'a second identity row' => ["INSERT INTO organisation_identity (id) VALUES ('" . self::B . "')", 'organisation_identity_one_row'],
            'changing the identity' => ["UPDATE organisation_identity SET id = '" . self::B . "'", 'ORGANISATION_IDENTITY_IMMUTABLE'],
            'deleting the identity' => ['DELETE FROM organisation_identity', 'ORGANISATION_IDENTITY_IMMUTABLE'],
            'a ledger clock of another organisation' => ["INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES ('" . self::B . "')", 'org_membership_ledger_clocks_organisation_identity_fk'],
        ];
        foreach ($refusals as $why => [$sql, $reason]) {
            try {
                $a->connection->statement($sql);
                $this->fail("accepted: {$why}");
            } catch (QueryException $e) {
                $this->assertStringContainsString($reason, $e->getMessage(), "{$why}: refused, but not for the intended reason");
            }
        }
        $this->assertSame(1, (int) $a->connection->selectOne('SELECT count(*) AS n FROM org_memberships')->n);
    }

    public function test_a_failing_migration_rolls_back_its_own_changes_and_the_history_stays_exact(): void
    {
        $c = $this->context(self::C, 'platform_pg_org_c');
        $c->connection->statement('CREATE TABLE org_membership_commands (blocking int)');   // the ledger migration will collide here

        try {
            self::migrations()->apply($c);
            $this->fail('a colliding migration succeeded');
        } catch (QueryException $e) {
            $this->assertStringContainsString('org_membership_commands', $e->getMessage());
        }
        $recorded = array_map(static fn ($r) => $r->migration, $c->connection->select('SELECT migration FROM migrations ORDER BY id'));
        $this->assertNotEmpty($recorded, 'the identity migration before it is applied and recorded');
        $this->assertTrue(self::hasTable('platform_org_c', 'organisation_identity'));
        $this->assertFalse(self::hasTable('platform_org_c', 'org_memberships'), 'the failing migration left nothing behind');
        $this->assertSame(array_values(array_diff(self::migrations()->pending($c), $recorded)), self::migrations()->pending($c), 'the failing one is still pending');
    }

    /** The organisation ledger schema equals the central one (the default test database), except F-13's target. */
    public function test_the_organisation_ledger_schema_matches_the_central_one_except_f13(): void
    {
        $a = $this->context(self::A, 'platform_pg_org_a');
        self::migrations()->apply($a);
        $shape = static function ($connection): array {
            $out = [];
            foreach (self::LEDGER as $t) {
                $out[$t]['columns'] = array_map(static fn ($r) => (array) $r, $connection->select('SELECT column_name, data_type, is_nullable, datetime_precision, column_default
                    FROM information_schema.columns WHERE table_schema = current_schema() AND table_name = ? ORDER BY ordinal_position', [$t]));
                $out[$t]['constraints'] = array_map(static fn ($r) => (array) $r, $connection->select("SELECT conname, pg_get_constraintdef(oid) AS def FROM pg_constraint
                    WHERE conrelid = ?::regclass AND NOT (contype = 'f' AND confrelid IN (SELECT oid FROM pg_class WHERE relname IN ('organisations', 'organisation_identity')))
                    ORDER BY conname", [$t]));
            }

            return $out;
        };

        $this->assertSame($shape(DB::connection()), $shape($a->connection));
    }
}
