<?php

namespace Tests\Isolated\Pbdigit274\Support;

use PDO;
use Throwable;

/**
 * Identity guard for the isolated PBDIGIT-274 test database (plan section 11.0, S2-3; gates G4 / G5).
 *
 * Plain PHP, no Laravel: tests/bootstrap-isolated-database.php uses it before the framework exists, and
 * IsolatedDatabaseTestCase uses it again before the database refresh. Every failure is an
 * IsolatedDatabaseRefused; nothing falls back to another database, and no message contains a credential.
 */
final class DatabaseIdentityGuard
{
    public const ENV_NAME = 'PBDIGIT274_TEST_DB';

    /** Never usable for this work, compared case-insensitively. */
    public const FORBIDDEN_NAMES = ['nrna_test'];

    /** Every PostgreSQL connection in config/database.php whose target comes from the environment. */
    public const CONFINED_CONNECTIONS = ['pgsql', 'pgsql_audit', 'pgsql_geo'];

    /** Environment prefixes of those connections (config/database.php). */
    private const PREFIXES = ['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'];

    /** A plain PostgreSQL identifier; anything else is refused, never corrected. */
    private const NAME_PATTERN = '/^[A-Za-z_][A-Za-z0-9_]{0,62}$/';

    public static function approvedName(mixed $raw): string
    {
        if ($raw === null || $raw === false) {
            throw new IsolatedDatabaseRefused('missing', self::ENV_NAME.' is not set.');
        }
        if (is_string($raw) && trim($raw) === '') {
            throw new IsolatedDatabaseRefused('empty', self::ENV_NAME.' is empty.');
        }
        if (!is_string($raw) || preg_match(self::NAME_PATTERN, $raw) !== 1) {
            throw new IsolatedDatabaseRefused('malformed', self::ENV_NAME.' must be a plain PostgreSQL identifier (letters, digits, underscore; at most 63).');
        }
        if (in_array(strtolower($raw), self::FORBIDDEN_NAMES, true)) {
            throw new IsolatedDatabaseRefused('forbidden', "'{$raw}' is a shared database and is never used for this work.");
        }

        return $raw;
    }

    /** A cached Laravel configuration would ignore every environment variable this guard sets. */
    public static function refuseIfConfigCached(string $configCachePath): void
    {
        if (file_exists($configCachePath)) {
            throw new IsolatedDatabaseRefused('config_cached', 'Laravel configuration is cached; run without a config cache.');
        }
    }

    /**
     * Credentials come only from the dedicated PBDIGIT274_DB_* variables, never from DB_* or .env.
     *
     * @return array{host: string, port: string, username: string, password: string}
     */
    public static function connectionSettings(array $env): array
    {
        $host = trim((string) ($env['PBDIGIT274_DB_HOST'] ?? ''));
        $username = trim((string) ($env['PBDIGIT274_DB_USERNAME'] ?? ''));
        $port = (string) ($env['PBDIGIT274_DB_PORT'] ?? '5432');
        if ($host === '' || $username === '' || preg_match('/^[0-9]{1,5}$/', $port) !== 1) {
            throw new IsolatedDatabaseRefused('credentials_missing', 'PBDIGIT274_DB_HOST and PBDIGIT274_DB_USERNAME are required; PBDIGIT274_DB_PORT must be numeric.');
        }

        return ['host' => $host, 'port' => $port, 'username' => $username, 'password' => (string) ($env['PBDIGIT274_DB_PASSWORD'] ?? '')];
    }

    /** @param callable(): mixed $currentDatabase returns the result of SELECT current_database() */
    public static function verifyIdentity(string $approved, callable $currentDatabase): void
    {
        try {
            $actual = $currentDatabase();
        } catch (Throwable $e) {
            // The driver's message may echo connection details, so only the exception class is reported.
            throw new IsolatedDatabaseRefused('connection_failed', 'Could not read current_database() ('.$e::class.').');
        }
        if (!is_string($actual) || $actual !== $approved) {
            $shown = is_string($actual) ? "'{$actual}'" : get_debug_type($actual);
            throw new IsolatedDatabaseRefused('mismatch', "Connected to {$shown}, expected '{$approved}'.");
        }
    }

    /** @return callable(): mixed a raw PDO lookup, made before Laravel boots */
    public static function pdoCurrentDatabase(string $approved, array $settings): callable
    {
        return static function () use ($approved, $settings) {
            $pdo = new PDO(
                "pgsql:host={$settings['host']};port={$settings['port']};dbname={$approved}",
                $settings['username'],
                $settings['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5],
            );

            return $pdo->query('SELECT current_database()')->fetchColumn();
        };
    }

    /**
     * The variables handed to Laravel: every confined connection points at the approved database and the
     * DATABASE_URL override is blanked, so values from .env can't redirect any of them (Laravel's dotenv
     * loader never overwrites a variable that is already set).
     *
     * @return array<string, string>
     */
    public static function environmentFor(string $approved, array $settings): array
    {
        $env = ['APP_ENV' => 'testing', 'DB_CONNECTION' => 'pgsql', 'DATABASE_URL' => ''];
        foreach (self::PREFIXES as $prefix) {
            $env[$prefix.'HOST'] = $settings['host'];
            $env[$prefix.'PORT'] = $settings['port'];
            $env[$prefix.'DATABASE'] = $approved;
            $env[$prefix.'USERNAME'] = $settings['username'];
            $env[$prefix.'PASSWORD'] = $settings['password'];
        }

        return $env;
    }

    /** Configuration-level check (no connection): the default and every confined connection target the approved database. */
    public static function assertConnectionsConfined(array $connections, string $approved, string $default): void
    {
        if (!in_array($default, self::CONFINED_CONNECTIONS, true)) {
            throw new IsolatedDatabaseRefused('not_confined', "Default connection '{$default}' is not a confined connection.");
        }
        foreach (self::CONFINED_CONNECTIONS as $name) {
            $config = $connections[$name] ?? null;
            if (!is_array($config) || ($config['driver'] ?? null) !== 'pgsql'
                || !empty($config['url']) || ($config['database'] ?? null) !== $approved) {
                throw new IsolatedDatabaseRefused('not_confined', "Connection '{$name}' does not target '{$approved}'.");
            }
        }
    }

    /** The in-test check: configuration first, then the live identity, before any destructive setup. */
    public static function confirmBeforeRefresh(array $connections, string $approved, string $default, callable $currentDatabase): void
    {
        self::assertConnectionsConfined($connections, $approved, $default);
        self::verifyIdentity($approved, $currentDatabase);
    }
}
