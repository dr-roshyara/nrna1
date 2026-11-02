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
}
