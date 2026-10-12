<?php

namespace Tests\Isolated\Pbdigit274\Guard;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;

/**
 * PBDIGIT-274 S2 step 1: the identity guard for the isolated test database (plan section 11.0, S2-3).
 *
 * Database-free: plain PHPUnit, no Laravel, no connection. The PostgreSQL identity lookup is replaced by a
 * closure; the real PDO lookup is exercised only after G4.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Isolated/Pbdigit274/Guard
 */
class DatabaseIdentityGuardTest extends TestCase
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

    // ── the configured name ───────────────────────────────────────────────

    public static function missingNames(): array
    {
        return ['getenv false' => [false], 'null' => [null]];
    }

    #[DataProvider('missingNames')]
    public function test_a_missing_name_is_refused(mixed $raw): void
    {
        $this->assertRefused('missing', fn () => DatabaseIdentityGuard::approvedName($raw));
    }

    public static function emptyNames(): array
    {
        return ['empty' => [''], 'spaces' => ['   '], 'tab and newline' => ["\t\n"]];
    }

    #[DataProvider('emptyNames')]
    public function test_an_empty_or_whitespace_name_is_refused(string $raw): void
    {
        $this->assertRefused('empty', fn () => DatabaseIdentityGuard::approvedName($raw));
    }

    public static function forbiddenNames(): array
    {
        return ['exact' => ['nrna_test'], 'upper' => ['NRNA_TEST'], 'mixed' => ['Nrna_Test']];
    }

    #[DataProvider('forbiddenNames')]
    public function test_the_shared_test_database_is_refused_case_insensitively(string $raw): void
    {
        $this->assertRefused('forbidden', fn () => DatabaseIdentityGuard::approvedName($raw));
    }

    public static function malformedNames(): array
    {
        return [
            'leading space'   => [' pbd_isolated'],
            'trailing space'  => ['pbd_isolated '],
            'hyphen'          => ['pbd-isolated'],
            'leading digit'   => ['1pbd'],
            'quote'           => ['pbd"x'],
            'semicolon'       => ['pbd;drop'],
            'longer than 63'  => [str_repeat('a', 64)],
            'not a string'    => [42],
        ];
    }

    #[DataProvider('malformedNames')]
    public function test_a_malformed_name_is_refused_rather_than_corrected(mixed $raw): void
    {
        $this->assertRefused('malformed', fn () => DatabaseIdentityGuard::approvedName($raw));
    }

    public function test_a_well_formed_name_is_returned_unchanged(): void
    {
        $this->assertSame('pbd_isolated_274', DatabaseIdentityGuard::approvedName('pbd_isolated_274'));
        $this->assertSame('PbdIsolated', DatabaseIdentityGuard::approvedName('PbdIsolated'));
        $this->assertSame(str_repeat('a', 63), DatabaseIdentityGuard::approvedName(str_repeat('a', 63)));
    }

    // ── the identity check against the connected database ─────────────────

    public function test_a_mismatched_database_is_refused_and_both_names_are_reported(): void
    {
        $e = $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity('pbd_isolated', fn () => 'nrna_test'));
        $this->assertStringContainsString('pbd_isolated', $e->getMessage());
        $this->assertStringContainsString('nrna_test', $e->getMessage());
    }

    public function test_the_comparison_is_exact(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity('pbd_isolated', fn () => 'PBD_ISOLATED'));
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity('pbd_isolated', fn () => 'pbd_isolated '));
    }

    public function test_a_non_string_answer_is_refused(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity('pbd_isolated', fn () => false));
    }

    public function test_a_matching_database_passes(): void
    {
        DatabaseIdentityGuard::verifyIdentity('pbd_isolated', fn () => 'pbd_isolated');
        $this->addToAssertionCount(1);
    }

    public function test_a_failed_lookup_is_refused_without_leaking_its_message(): void
    {
        $e = $this->assertRefused('connection_failed', fn () => DatabaseIdentityGuard::verifyIdentity(
            'pbd_isolated',
            fn () => throw new \PDOException('password authentication failed, password=s3cr3t-value'),
        ));
        $this->assertStringNotContainsString('s3cr3t-value', $e->getMessage());
        $this->assertStringContainsString('PDOException', $e->getMessage());
    }

    // ── credentials and the environment handed to Laravel ─────────────────

    public function test_host_and_username_are_required(): void
    {
        $this->assertRefused('credentials_missing', fn () => DatabaseIdentityGuard::connectionSettings(['PBDIGIT274_DB_USERNAME' => 'u']));
        $this->assertRefused('credentials_missing', fn () => DatabaseIdentityGuard::connectionSettings(['PBDIGIT274_DB_HOST' => 'h']));
        $this->assertRefused('credentials_missing', fn () => DatabaseIdentityGuard::connectionSettings(['PBDIGIT274_DB_HOST' => ' ', 'PBDIGIT274_DB_USERNAME' => 'u']));
    }

    public function test_a_malformed_port_is_refused(): void
    {
        $this->assertRefused('credentials_missing', fn () => DatabaseIdentityGuard::connectionSettings([
            'PBDIGIT274_DB_HOST' => 'h', 'PBDIGIT274_DB_USERNAME' => 'u', 'PBDIGIT274_DB_PORT' => '54x32',
        ]));
    }

    public function test_settings_come_only_from_the_dedicated_variables(): void
    {
        $settings = DatabaseIdentityGuard::connectionSettings([
            'PBDIGIT274_DB_HOST' => 'db.local', 'PBDIGIT274_DB_USERNAME' => 'iso_user',
            'DB_HOST' => 'other', 'DB_USERNAME' => 'other', 'DB_PASSWORD' => 'other',
        ]);
        $this->assertSame(['host' => 'db.local', 'port' => '5432', 'username' => 'iso_user', 'password' => ''], $settings);
    }

    public function test_every_postgres_connection_is_pinned_and_the_url_override_is_blanked(): void
    {
        $env = DatabaseIdentityGuard::environmentFor('pbd_isolated', ['host' => 'h', 'port' => '5433', 'username' => 'u', 'password' => 'p']);

        $this->assertSame('pgsql', $env['DB_CONNECTION']);
        $this->assertSame('', $env['DATABASE_URL']);
        $this->assertSame('testing', $env['APP_ENV']);
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $this->assertSame('pbd_isolated', $env[$prefix.'DATABASE'], $prefix);
            $this->assertSame('h', $env[$prefix.'HOST'], $prefix);
            $this->assertSame('5433', $env[$prefix.'PORT'], $prefix);
            $this->assertSame('u', $env[$prefix.'USERNAME'], $prefix);
            $this->assertSame('p', $env[$prefix.'PASSWORD'], $prefix);
        }
    }

    // ── the second check inside the tests, before the database refresh ────

    private function connections(array $override = []): array
    {
        $base = ['driver' => 'pgsql', 'url' => null, 'database' => 'pbd_isolated'];

        return array_replace_recursive([
            'pgsql' => $base, 'pgsql_audit' => $base, 'pgsql_geo' => $base,
            'testing' => ['driver' => 'pgsql', 'database' => 'nrna_test'],
        ], $override);
    }

    public function test_confined_connections_pass(): void
    {
        DatabaseIdentityGuard::assertConnectionsConfined($this->connections(), 'pbd_isolated', 'pgsql');
        $this->addToAssertionCount(1);
    }

    public function test_the_default_connection_must_be_one_of_the_confined_ones(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined($this->connections(), 'pbd_isolated', 'testing'));
    }

    public function test_a_connection_pointing_elsewhere_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['pgsql_audit' => ['database' => 'nrna_dev']]), 'pbd_isolated', 'pgsql'));
    }

    public function test_a_url_override_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['pgsql' => ['url' => 'pgsql://u@h/other']]), 'pbd_isolated', 'pgsql'));
    }

    public function test_confirm_before_refresh_checks_configuration_before_asking_the_database(): void
    {
        $asked = false;
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::confirmBeforeRefresh(
            $this->connections(['pgsql' => ['database' => 'nrna_test']]), 'pbd_isolated', 'pgsql',
            function () use (&$asked) { $asked = true; return 'pbd_isolated'; },
        ));
        $this->assertFalse($asked, 'the live identity lookup must not run when the configuration is already wrong');
    }

    public function test_confirm_before_refresh_refuses_a_live_mismatch(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::confirmBeforeRefresh(
            $this->connections(), 'pbd_isolated', 'pgsql', fn () => 'nrna_test'));
    }

    // ── a cached Laravel configuration would ignore every variable set here ──

    public function test_a_cached_configuration_is_refused(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'pbd274cfg');
        try {
            $this->assertRefused('config_cached', fn () => DatabaseIdentityGuard::refuseIfConfigCached($file));
        } finally {
            unlink($file);
        }
        DatabaseIdentityGuard::refuseIfConfigCached($file);   // gone → passes
    }
}
