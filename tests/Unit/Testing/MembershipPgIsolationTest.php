<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;
use Tests\Support\MembershipPgIsolatedDatabase as Membership;

/**
 * PBDIGIT-324 steps 1 and 2 (plan docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md §3.1, §3.2):
 * the Membership PostgreSQL suite uses the PBDIGIT-274 identity guard with its own profile, and adds a check
 * that the connection reached the throwaway cluster (system_identifier). Database-free: no Laravel, no connection.
 */
final class MembershipPgIsolationTest extends TestCase
{
    private function assertRefused(string $reason, callable $call): IsolatedDatabaseRefused
    {
        try {
            $call();
        } catch (IsolatedDatabaseRefused $e) {
            $this->assertSame($reason, $e->reason());

            return $e;
        }
        $this->fail("expected IsolatedDatabaseRefused({$reason})");
    }

    // ── step 1: the Membership profile ────────────────────────────────────

    public function test_the_profile_names_its_own_variables_and_database(): void
    {
        $this->assertSame('membership_pg_isolated', Membership::APPROVED_NAME);
        $this->assertSame('MEMBERSHIP_PG_TEST_DB', Membership::ENV_NAME);
        $this->assertSame('MEMBERSHIP_PG_DB_', Membership::CREDENTIAL_PREFIX);
        $this->assertSame('MEMBERSHIP_PG_SYSTEM_ID', Membership::SYSTEM_ID_ENV);
        $this->assertNotSame(DatabaseIdentityGuard::APPROVED_NAME, Membership::APPROVED_NAME);
    }

    public function test_the_membership_name_passes_only_with_the_membership_approval(): void
    {
        $this->assertSame(Membership::APPROVED_NAME, DatabaseIdentityGuard::approvedName(Membership::APPROVED_NAME, Membership::APPROVED_NAME, Membership::ENV_NAME));
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName(Membership::APPROVED_NAME));
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName(DatabaseIdentityGuard::APPROVED_NAME, Membership::APPROVED_NAME, Membership::ENV_NAME));
    }

    public static function nearNames(): array
    {
        return [
            'suffix' => ['membership_pg_isolated_x'],
            'prefix only' => ['membership_pg'],
            'other case' => ['Membership_PG_isolated'],
        ];
    }

    #[DataProvider('nearNames')]
    public function test_names_near_the_membership_name_are_refused(string $raw): void
    {
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName($raw, Membership::APPROVED_NAME, Membership::ENV_NAME));
    }

    public function test_the_shared_test_database_stays_forbidden_under_the_membership_profile(): void
    {
        $this->assertRefused('forbidden', fn () => DatabaseIdentityGuard::approvedName('nrna_test', Membership::APPROVED_NAME, Membership::ENV_NAME));
    }

    public function test_refusal_messages_name_the_membership_variable(): void
    {
        $e = $this->assertRefused('missing', fn () => DatabaseIdentityGuard::wellFormedName(false, Membership::ENV_NAME));
        $this->assertStringContainsString('MEMBERSHIP_PG_TEST_DB', $e->getMessage());
        $this->assertStringNotContainsString('PBDIGIT274', $e->getMessage());
    }

    public function test_credentials_come_only_from_the_membership_variables(): void
    {
        $settings = DatabaseIdentityGuard::connectionSettings([
            'MEMBERSHIP_PG_DB_HOST' => '127.0.0.1', 'MEMBERSHIP_PG_DB_PORT' => '41234', 'MEMBERSHIP_PG_DB_USERNAME' => 'membership_pg',
            'PBDIGIT274_DB_HOST' => 'other', 'PBDIGIT274_DB_USERNAME' => 'other',
            'DB_HOST' => 'other', 'DB_USERNAME' => 'other', 'DB_PASSWORD' => 'other',
        ], Membership::CREDENTIAL_PREFIX);

        $this->assertSame(['host' => '127.0.0.1', 'port' => '41234', 'username' => 'membership_pg', 'password' => ''], $settings);
    }

    public function test_pbdigit274_credentials_do_not_satisfy_the_membership_profile(): void
    {
        $e = $this->assertRefused('credentials_missing', fn () => DatabaseIdentityGuard::connectionSettings(
            ['PBDIGIT274_DB_HOST' => 'h', 'PBDIGIT274_DB_USERNAME' => 'u'],
            Membership::CREDENTIAL_PREFIX,
        ));
        $this->assertStringContainsString('MEMBERSHIP_PG_DB_HOST', $e->getMessage());
    }

    // ── step 2: the server-identity check (system_identifier) ─────────────

    public static function missingSystemIds(): array
    {
        return ['not set' => [false], 'null' => [null], 'empty' => [''], 'blank' => ['  '], 'not digits' => ['abc'], 'signed' => ['-12'], 'padded' => [' 7432']];
    }

    #[DataProvider('missingSystemIds')]
    public function test_an_absent_or_malformed_expected_system_id_is_refused_before_asking(mixed $expected): void
    {
        $asked = false;
        $this->assertRefused('system_id_missing', fn () => DatabaseIdentityGuard::verifySystemIdentifier(
            $expected,
            function () use (&$asked) { $asked = true; return '7432'; },
        ));
        $this->assertFalse($asked, 'nothing is asked of the database without an expected identifier');
    }

    public function test_a_different_cluster_is_refused_and_both_ids_are_reported(): void
    {
        $e = $this->assertRefused('system_id_mismatch', fn () => DatabaseIdentityGuard::verifySystemIdentifier('7432001', fn () => '7432002'));
        $this->assertStringContainsString('7432001', $e->getMessage());
        $this->assertStringContainsString('7432002', $e->getMessage());
    }

    public function test_a_non_string_answer_is_refused(): void
    {
        $this->assertRefused('system_id_mismatch', fn () => DatabaseIdentityGuard::verifySystemIdentifier('7432001', fn () => 7432001));
        $this->assertRefused('system_id_mismatch', fn () => DatabaseIdentityGuard::verifySystemIdentifier('7432001', fn () => false));
    }

    public function test_a_failed_lookup_is_refused_without_leaking_its_message(): void
    {
        $e = $this->assertRefused('connection_failed', fn () => DatabaseIdentityGuard::verifySystemIdentifier(
            '7432001',
            fn () => throw new \PDOException('password authentication failed, password=s3cr3t-value'),
        ));
        $this->assertStringNotContainsString('s3cr3t-value', $e->getMessage());
    }

    public function test_the_same_cluster_passes(): void
    {
        DatabaseIdentityGuard::verifySystemIdentifier('7432001', fn () => '7432001');
        $this->addToAssertionCount(1);
    }

    // ── review finding F1 (2026-11-02): confinement inside the run, before any reset ──

    private const ACTIVE_ENV = ['MEMBERSHIP_PG_TEST_DB' => 'membership_pg_isolated', 'MEMBERSHIP_PG_SYSTEM_ID' => '7432001'];

    /** The booted connections of a Membership run: the three confined ones, plus config/database.php's hard-coded `testing`. */
    private function bootedConnections(array $override = []): array
    {
        $pg = ['driver' => 'pgsql', 'url' => null, 'database' => Membership::APPROVED_NAME];

        return array_replace_recursive([
            'sqlite' => ['driver' => 'sqlite', 'url' => '', 'database' => ':memory:'],
            'pgsql' => $pg, 'pgsql_audit' => $pg, 'pgsql_geo' => $pg,
            'testing' => ['driver' => 'pgsql', 'host' => '127.0.0.1', 'database' => 'nrna_test'],
        ], $override);
    }

    private function confined(array $env, array $connections, ?callable $currentDatabase = null, ?callable $systemId = null, array &$asked = []): ?array
    {
        return Membership::confinedConnections(
            $env,
            $connections,
            'pgsql',
            function () use ($currentDatabase, &$asked) { $asked[] = 'current_database'; return $currentDatabase ? $currentDatabase() : Membership::APPROVED_NAME; },
            function () use ($systemId, &$asked) { $asked[] = 'system_identifier'; return $systemId ? $systemId() : '7432001'; },
        );
    }

    public function test_outside_a_membership_run_nothing_changes_and_nothing_is_asked(): void
    {
        $asked = [];
        $this->assertNull($this->confined(['DB_DATABASE' => 'nrna_test'], $this->bootedConnections(), null, null, $asked));
        $this->assertSame([], $asked, 'the default and CI runs are untouched');
    }

    public function test_in_a_membership_run_the_unconfined_testing_connection_is_removed(): void
    {
        $kept = $this->confined(self::ACTIVE_ENV, $this->bootedConnections());

        $this->assertIsArray($kept);
        $this->assertArrayNotHasKey('testing', $kept, 'the hard-coded connection to the shared nrna_test must not stay reachable');
        $this->assertSame(['sqlite', 'pgsql', 'pgsql_audit', 'pgsql_geo'], array_keys($kept));
    }

    public function test_a_confined_connection_pointing_elsewhere_is_refused_before_the_database_is_asked(): void
    {
        $asked = [];
        $this->assertRefused('not_confined', fn () => $this->confined(
            self::ACTIVE_ENV, $this->bootedConnections(['pgsql_audit' => ['database' => 'nrna_test']]), null, null, $asked));
        $this->assertSame([], $asked);
    }

    public function test_a_url_override_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => $this->confined(self::ACTIVE_ENV, $this->bootedConnections(['pgsql' => ['url' => 'pgsql://u@h/publicdigit']])));
    }

    public function test_the_live_connection_must_reach_the_same_cluster(): void
    {
        $this->assertRefused('system_id_mismatch', fn () => $this->confined(self::ACTIVE_ENV, $this->bootedConnections(), null, fn () => '7432002'));
        $this->assertRefused('system_id_missing', fn () => $this->confined(['MEMBERSHIP_PG_TEST_DB' => 'membership_pg_isolated'], $this->bootedConnections()));
    }

    public function test_the_live_connection_must_report_the_approved_database(): void
    {
        $this->assertRefused('mismatch', fn () => $this->confined(self::ACTIVE_ENV, $this->bootedConnections(), fn () => 'nrna_test'));
    }

    public function test_a_wrong_profile_name_is_refused(): void
    {
        $this->assertRefused('not_approved', fn () => $this->confined(['MEMBERSHIP_PG_TEST_DB' => 'pbdigit274_isolated_test'] + self::ACTIVE_ENV, $this->bootedConnections()));
    }

    public function test_every_membership_postgres_test_uses_the_confining_base_class(): void
    {
        $files = glob(__DIR__.'/../../Feature/Contexts/Membership/Organisational/Postgres*Test.php');
        $this->assertCount(4, $files);
        foreach ($files as $file) {
            $this->assertMatchesRegularExpression('/\bextends\s+MembershipPostgresTestCase\b/', (string) file_get_contents($file), basename($file));
        }
    }
}
