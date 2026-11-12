<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform\Harness;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 0: the test-only connections to the four platform databases.
 * - Each named connection reaches its own database on the cluster captured at container start.
 * - The databases are independent: what one holds, the others never see.
 * - A reset empties all four, repeatably, so every later test starts from nothing.
 * - Laravel's default connection is untouched (still the slice 1 database). Nothing here routes production code.
 * - Registration refuses anything it cannot prove, and then registers nothing.
 */
final class PlatformPgConnectionsTest extends MembershipPostgresTestCase
{
    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
    }

    public function test_each_platform_connection_reaches_its_own_database_on_the_captured_cluster(): void
    {
        foreach (PlatformPgIsolatedDatabases::CONNECTIONS as $connection => $database) {
            $c = DB::connection($connection);

            $this->assertSame($database, $c->selectOne('SELECT current_database() AS n')->n);
            $this->assertSame((string) getenv(MembershipPgIsolatedDatabase::SYSTEM_ID_ENV), $c->selectOne('SELECT system_identifier::text AS id FROM pg_control_system()')->id);
        }
    }

    public function test_a_table_created_in_organisation_a_is_invisible_in_b_c_and_central(): void
    {
        DB::connection('platform_org_a')->statement('CREATE TABLE harness_probe (id int)');
        DB::connection('platform_org_a')->insert('INSERT INTO harness_probe VALUES (1)');

        $this->assertSame(1, (int) DB::connection('platform_org_a')->selectOne('SELECT count(*) AS n FROM harness_probe')->n);
        foreach (['platform_org_b', 'platform_org_c', 'platform_central'] as $other) {
            $this->assertNull(DB::connection($other)->selectOne("SELECT to_regclass('public.harness_probe') AS t")->t, "{$other} must not see A's table");
        }
    }

    public function test_reset_empties_every_platform_database_and_can_run_twice(): void
    {
        foreach (array_keys(PlatformPgIsolatedDatabases::CONNECTIONS) as $connection) {
            DB::connection($connection)->statement('CREATE TABLE harness_probe (id int)');
        }

        PlatformPgIsolatedDatabases::reset();
        PlatformPgIsolatedDatabases::reset();

        foreach (array_keys(PlatformPgIsolatedDatabases::CONNECTIONS) as $connection) {
            $this->assertSame(0, (int) DB::connection($connection)->selectOne("SELECT count(*) AS n FROM pg_tables WHERE schemaname = 'public'")->n, "{$connection} must be empty after reset");
        }
    }

    public function test_the_default_connection_still_targets_the_slice_1_database(): void
    {
        $default = (string) config('database.default');

        $this->assertArrayNotHasKey($default, PlatformPgIsolatedDatabases::CONNECTIONS);
        $this->assertSame(MembershipPgIsolatedDatabase::APPROVED_NAME, DB::connection()->selectOne('SELECT current_database() AS n')->n);
    }

    public function test_registration_refuses_without_the_database_list_and_registers_nothing(): void
    {
        $this->forgetPlatformConnections();
        $env = getenv();
        unset($env['PLATFORM_PG_DATABASES']);

        $this->assertRefused(fn () => PlatformPgIsolatedDatabases::register($env));
    }

    public function test_registration_refuses_a_database_outside_the_approved_four(): void
    {
        $this->forgetPlatformConnections();
        $env = getenv();
        $env['PLATFORM_PG_DATABASES'] = 'platform_pg_central,nrna_test,platform_pg_org_b,platform_pg_org_c';

        $this->assertRefused(fn () => PlatformPgIsolatedDatabases::register($env));
    }

    public function test_registration_refuses_a_different_cluster_identity(): void
    {
        $this->forgetPlatformConnections();
        $env = getenv();
        $env[MembershipPgIsolatedDatabase::SYSTEM_ID_ENV] = '1';

        $this->assertRefused(fn () => PlatformPgIsolatedDatabases::register($env));
    }

    private function forgetPlatformConnections(): void
    {
        foreach (array_keys(PlatformPgIsolatedDatabases::CONNECTIONS) as $connection) {
            DB::purge($connection);
            config(["database.connections.{$connection}" => null]);
        }
    }

    private function assertRefused(callable $register): void
    {
        try {
            $register();
            $this->fail('registration must refuse');
        } catch (IsolatedDatabaseRefused) {
            // expected
        }
        foreach (array_keys(PlatformPgIsolatedDatabases::CONNECTIONS) as $connection) {
            $this->assertNull(config("database.connections.{$connection}"), "{$connection} must not stay registered after a refusal");
        }
    }
}
