<?php

namespace Tests\Isolated\Pbdigit274\Guard;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;

/**
 * PBDIGIT-274 S2 steps 1 and 1b: the identity guard for the isolated test database (plan section 11.0,
 * S2-3; independent review findings H1, M1, M2, L1, L4).
 *
 * Database-free: plain PHPUnit, no Laravel, no connection. The PostgreSQL identity lookup is replaced by a
 * closure; the real PDO lookup is exercised only after G4.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Isolated/Pbdigit274/Guard
 */
class DatabaseIdentityGuardTest extends TestCase
{
    /** A stand-in for the name the PO will approve under G4; used only as an explicit argument. */
    private const APPROVED = 'pbd_isolated';

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

    // ── H1: nothing is approved before G4 ─────────────────────────────────

    public function test_no_database_is_approved_before_g4(): void
    {
        // Changing this constant is the reviewable act that follows the PO's G4 approval.
        $this->assertSame('', DatabaseIdentityGuard::APPROVED_NAME);
    }

    public static function wellFormedButUnapprovedNames(): array
    {
        return [
            'repository default for pgsql_geo' => ['publicdigit'],
            'another documented name'          => ['nrna'],
            'a plausible isolated name'        => ['pbd_isolated_274'],
        ];
    }

    #[DataProvider('wellFormedButUnapprovedNames')]
    public function test_every_name_is_refused_while_nothing_is_approved(string $raw): void
    {
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName($raw));
    }

    public function test_only_the_exact_approved_name_passes(): void
    {
        $this->assertSame(self::APPROVED, DatabaseIdentityGuard::approvedName(self::APPROVED, self::APPROVED));
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName('PBD_ISOLATED', self::APPROVED));
        $this->assertRefused('not_approved', fn () => DatabaseIdentityGuard::approvedName('publicdigit', self::APPROVED));
    }

    public function test_an_approved_name_cannot_unlock_a_forbidden_database(): void
    {
        $this->assertRefused('forbidden', fn () => DatabaseIdentityGuard::approvedName('nrna_test', 'nrna_test'));
    }

    // ── syntax: missing, empty, malformed, reserved, forbidden ────────────

    public static function missingNames(): array
    {
        return ['getenv false' => [false], 'null' => [null]];
    }

    #[DataProvider('missingNames')]
    public function test_a_missing_name_is_refused(mixed $raw): void
    {
        $this->assertRefused('missing', fn () => DatabaseIdentityGuard::wellFormedName($raw));
    }

    public static function emptyNames(): array
    {
        return ['empty' => [''], 'spaces' => ['   '], 'tab and newline' => ["\t\n"]];
    }

    #[DataProvider('emptyNames')]
    public function test_an_empty_or_whitespace_name_is_refused(string $raw): void
    {
        $this->assertRefused('empty', fn () => DatabaseIdentityGuard::wellFormedName($raw));
    }

    public static function reservedWords(): array
    {
        // Laravel's env() turns these into true / false / '' / null (Illuminate\Support\Env::get).
        return [
            'null' => ['null'], 'NULL' => ['NULL'], 'Null' => ['Null'],
            'true' => ['true'], 'TRUE' => ['TRUE'],
            'false' => ['false'], 'False' => ['False'],
            'empty' => ['empty'], 'EMPTY' => ['EMPTY'],
        ];
    }

    #[DataProvider('reservedWords')]
    public function test_words_laravel_reinterprets_are_refused(string $raw): void
    {
        $this->assertRefused('reserved', fn () => DatabaseIdentityGuard::wellFormedName($raw));
    }

    public static function forbiddenNames(): array
    {
        return ['exact' => ['nrna_test'], 'upper' => ['NRNA_TEST'], 'mixed' => ['Nrna_Test']];
    }

    #[DataProvider('forbiddenNames')]
    public function test_the_shared_test_database_is_refused_case_insensitively(string $raw): void
    {
        $this->assertRefused('forbidden', fn () => DatabaseIdentityGuard::wellFormedName($raw));
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
        $this->assertRefused('malformed', fn () => DatabaseIdentityGuard::wellFormedName($raw));
    }

    public function test_a_well_formed_name_is_returned_unchanged(): void
    {
        $this->assertSame('pbd_isolated_274', DatabaseIdentityGuard::wellFormedName('pbd_isolated_274'));
        $this->assertSame('PbdIsolated', DatabaseIdentityGuard::wellFormedName('PbdIsolated'));
        $this->assertSame(str_repeat('a', 63), DatabaseIdentityGuard::wellFormedName(str_repeat('a', 63)));
    }

    // ── the identity check against the connected database ─────────────────

    public function test_a_mismatched_database_is_refused_and_both_names_are_reported(): void
    {
        $e = $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity(self::APPROVED, fn () => 'nrna_test'));
        $this->assertStringContainsString(self::APPROVED, $e->getMessage());
        $this->assertStringContainsString('nrna_test', $e->getMessage());
    }

    public function test_the_comparison_is_exact(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity(self::APPROVED, fn () => 'PBD_ISOLATED'));
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity(self::APPROVED, fn () => 'pbd_isolated '));
    }

    public function test_a_non_string_answer_is_refused(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::verifyIdentity(self::APPROVED, fn () => false));
    }

    public function test_a_matching_database_passes(): void
    {
        DatabaseIdentityGuard::verifyIdentity(self::APPROVED, fn () => self::APPROVED);
        $this->addToAssertionCount(1);
    }

    public function test_a_failed_lookup_is_refused_without_leaking_its_message(): void
    {
        $e = $this->assertRefused('connection_failed', fn () => DatabaseIdentityGuard::verifyIdentity(
            self::APPROVED,
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
        $env = DatabaseIdentityGuard::environmentFor(self::APPROVED, ['host' => 'h', 'port' => '5433', 'username' => 'u', 'password' => 'p']);

        $this->assertSame('pgsql', $env['DB_CONNECTION']);
        $this->assertSame('', $env['DATABASE_URL']);
        $this->assertSame('testing', $env['APP_ENV']);
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $this->assertSame(self::APPROVED, $env[$prefix.'DATABASE'], $prefix);
            $this->assertSame('h', $env[$prefix.'HOST'], $prefix);
            $this->assertSame('5433', $env[$prefix.'PORT'], $prefix);
            $this->assertSame('u', $env[$prefix.'USERNAME'], $prefix);
            $this->assertSame('p', $env[$prefix.'PASSWORD'], $prefix);
        }
    }

    // ── L4: the environment is really written where Laravel reads it ──────

    public function test_apply_environment_writes_server_env_and_putenv(): void
    {
        $key = 'PBDIGIT274_GUARD_SELFTEST_'.bin2hex(random_bytes(4));
        try {
            DatabaseIdentityGuard::applyEnvironment([$key => 'value-1']);
            $this->assertSame('value-1', $_SERVER[$key]);
            $this->assertSame('value-1', $_ENV[$key]);
            $this->assertSame('value-1', getenv($key));
        } finally {
            unset($_SERVER[$key], $_ENV[$key]);
            putenv($key);
        }
    }

    public function test_apply_environment_writes_empty_values_too(): void
    {
        // DATABASE_URL = '' must exist (set, empty) so .env cannot fill it in.
        $key = 'PBDIGIT274_GUARD_SELFTEST_'.bin2hex(random_bytes(4));
        try {
            DatabaseIdentityGuard::applyEnvironment([$key => '']);
            $this->assertArrayHasKey($key, $_SERVER);
            $this->assertSame('', $_SERVER[$key]);
            $this->assertSame('', getenv($key));
        } finally {
            unset($_SERVER[$key], $_ENV[$key]);
            putenv($key);
        }
    }

    // ── M2: a cached Laravel configuration would ignore every variable set here ──

    public function test_a_cached_configuration_file_is_refused(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'pbd274cfg');
        try {
            $this->assertRefused('config_cached', fn () => DatabaseIdentityGuard::refuseIfConfigCached($file, []));
        } finally {
            unlink($file);
        }
        DatabaseIdentityGuard::refuseIfConfigCached($file, []);   // gone, no override → passes
        $this->addToAssertionCount(1);
    }

    public static function configCacheOverrides(): array
    {
        return ['a path' => ['/tmp/elsewhere/config.php'], 'empty but set' => ['']];
    }

    #[DataProvider('configCacheOverrides')]
    public function test_an_app_config_cache_override_is_refused(string $value): void
    {
        $missing = sys_get_temp_dir().'/pbd274-no-such-cache-'.bin2hex(random_bytes(4)).'.php';
        $this->assertRefused('config_cached', fn () => DatabaseIdentityGuard::refuseIfConfigCached($missing, ['APP_CONFIG_CACHE' => $value]));
    }

    // ── L1: the in-test check covers every configured connection ──────────

    private function connections(array $override = []): array
    {
        $pg = ['driver' => 'pgsql', 'url' => null, 'database' => self::APPROVED];

        return array_replace_recursive([
            'sqlite' => ['driver' => 'sqlite', 'url' => '', 'database' => self::APPROVED],
            'mysql'  => ['driver' => 'mysql', 'url' => '', 'database' => self::APPROVED],
            'pgsql' => $pg, 'pgsql_audit' => $pg, 'pgsql_geo' => $pg,
        ], $override);
    }

    public function test_confined_connections_pass(): void
    {
        DatabaseIdentityGuard::assertConnectionsConfined($this->connections(), self::APPROVED, 'pgsql');
        $this->addToAssertionCount(1);
    }

    public function test_the_default_connection_must_be_one_of_the_confined_ones(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined($this->connections(), self::APPROVED, 'mysql'));
    }

    public function test_a_confined_connection_pointing_elsewhere_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['pgsql_audit' => ['database' => 'nrna_dev']]), self::APPROVED, 'pgsql'));
    }

    public function test_a_url_override_on_any_connection_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['pgsql' => ['url' => 'pgsql://u@h/other']]), self::APPROVED, 'pgsql'));
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['mysql' => ['url' => 'mysql://u@h/other']]), self::APPROVED, 'pgsql'));
    }

    public function test_an_extra_postgres_connection_to_the_shared_database_is_refused(): void
    {
        // config/database.php hard-codes a `testing` connection to nrna_test.
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['testing' => ['driver' => 'pgsql', 'database' => 'nrna_test']]), self::APPROVED, 'pgsql'));
    }

    public function test_an_extra_postgres_connection_to_any_unapproved_database_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['reporting' => ['driver' => 'pgsql', 'database' => 'publicdigit']]), self::APPROVED, 'pgsql'));
    }

    public function test_a_non_postgres_connection_naming_a_forbidden_database_is_refused(): void
    {
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::assertConnectionsConfined(
            $this->connections(['mysql' => ['database' => 'NRNA_TEST']]), self::APPROVED, 'pgsql'));
    }

    public function test_unconfined_postgres_connections_are_removed_and_others_kept(): void
    {
        $kept = DatabaseIdentityGuard::withoutUnconfinedPostgresConnections(
            $this->connections(['testing' => ['driver' => 'pgsql', 'database' => 'nrna_test'], 'reporting' => ['driver' => 'pgsql', 'database' => 'x']]),
        );
        $this->assertSame(['sqlite', 'mysql', 'pgsql', 'pgsql_audit', 'pgsql_geo'], array_keys($kept));
        DatabaseIdentityGuard::assertConnectionsConfined($kept, self::APPROVED, 'pgsql');
    }

    public function test_confirm_before_refresh_checks_configuration_before_asking_the_database(): void
    {
        $asked = false;
        $this->assertRefused('not_confined', fn () => DatabaseIdentityGuard::confirmBeforeRefresh(
            $this->connections(['pgsql' => ['database' => 'nrna_test']]), self::APPROVED, 'pgsql',
            function () use (&$asked) { $asked = true; return self::APPROVED; },
        ));
        $this->assertFalse($asked, 'the live identity lookup must not run when the configuration is already wrong');
    }

    public function test_confirm_before_refresh_refuses_a_live_mismatch(): void
    {
        $this->assertRefused('mismatch', fn () => DatabaseIdentityGuard::confirmBeforeRefresh(
            $this->connections(), self::APPROVED, 'pgsql', fn () => 'nrna_test'));
    }
}
