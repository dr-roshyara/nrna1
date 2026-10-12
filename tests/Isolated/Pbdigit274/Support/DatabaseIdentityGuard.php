<?php

namespace Tests\Isolated\Pbdigit274\Support;

use PDO;
use Throwable;

/**
 * Identity guard for the isolated PBDIGIT-274 test database (plan section 11.0, S2-3; gates G4 / G5;
 * step 1b: independent review findings H1, M1, M2, L1, L4).
 *
 * Plain PHP, no Laravel: tests/bootstrap-isolated-database.php uses it before the framework exists, and
 * IsolatedDatabaseTestCase uses it again before the database refresh. Every failure is an
 * IsolatedDatabaseRefused; nothing falls back to another database, and no message contains a credential.
 */
final class DatabaseIdentityGuard
{
    /**
     * The one database the Human PO approved under G4 (2026-10-12; evidence
     * docs/publicdigit/election/evidence/2026-10-12-pbdigit274-g4-isolation-fix/). Every other name is refused.
     * Changing it is a separate, reviewable change made only on a new G4 approval; never guess it from
     * repository defaults or the environment. An empty value refuses every name.
     */
    public const APPROVED_NAME = 'pbdigit274_isolated_test';

    public const ENV_NAME = 'PBDIGIT274_TEST_DB';

    /** Never usable for this work, compared case-insensitively. */
    public const FORBIDDEN_NAMES = ['nrna_test'];

    /** Words Laravel's env() turns into true / false / '' / null (Illuminate\Support\Env::get). */
    public const RESERVED_WORDS = ['true', 'false', 'empty', 'null'];

    /** Every PostgreSQL connection in config/database.php whose target comes from the environment. */
    public const CONFINED_CONNECTIONS = ['pgsql', 'pgsql_audit', 'pgsql_geo'];

    /** Environment prefixes of those connections (config/database.php). */
    private const PREFIXES = ['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'];

    /** A plain PostgreSQL identifier; anything else is refused, never corrected. */
    private const NAME_PATTERN = '/^[A-Za-z_][A-Za-z0-9_]{0,62}$/';

    /** Syntax only: present, not blank, a plain identifier, not a reserved word, not a forbidden database. */
    public static function wellFormedName(mixed $raw): string
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
        if (in_array(strtolower($raw), self::RESERVED_WORDS, true)) {
            throw new IsolatedDatabaseRefused('reserved', "'{$raw}' is a word Laravel's env() reinterprets; it is never a database name here.");
        }
        if (in_array(strtolower($raw), self::FORBIDDEN_NAMES, true)) {
            throw new IsolatedDatabaseRefused('forbidden', "'{$raw}' is a shared database and is never used for this work.");
        }

        return $raw;
    }

    /** Well formed AND exactly the approved name. With nothing approved (before G4) every name is refused. */
    public static function approvedName(mixed $raw, string $approvedName = self::APPROVED_NAME): string
    {
        $name = self::wellFormedName($raw);
        if ($approvedName === '') {
            throw new IsolatedDatabaseRefused('not_approved', 'No database is approved yet (gate G4); every name is refused.');
        }
        if ($name !== $approvedName) {
            throw new IsolatedDatabaseRefused('not_approved', "'{$name}' is not the approved database.");
        }

        return $name;
    }

    /**
     * A cached Laravel configuration would ignore every environment variable this guard sets: refuse the
     * default cache file, and any APP_CONFIG_CACHE override (set at all, even empty).
     */
    public static function refuseIfConfigCached(string $configCachePath, array $env): void
    {
        if (array_key_exists('APP_CONFIG_CACHE', $env)) {
            throw new IsolatedDatabaseRefused('config_cached', 'APP_CONFIG_CACHE is set; run without a config-cache override.');
        }
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

    /** Writes the variables where Laravel's environment repository reads them: $_SERVER, $_ENV and putenv. */
    public static function applyEnvironment(array $variables): void
    {
        foreach ($variables as $key => $value) {
            $_SERVER[$key] = $value;
            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }

    /**
     * Drops every PostgreSQL connection that is not confined (e.g. the hard-coded `testing` → nrna_test), so
     * a test can't reach it: using a dropped connection fails with "not configured". Others are kept.
     */
    public static function withoutUnconfinedPostgresConnections(array $connections): array
    {
        return array_filter(
            $connections,
            fn ($config, $name) => !is_array($config) || ($config['driver'] ?? null) !== 'pgsql'
                || in_array($name, self::CONFINED_CONNECTIONS, true),
            ARRAY_FILTER_USE_BOTH,
        );
    }

    /**
     * Configuration-level check over EVERY configured connection (no connection is opened):
     * - the default is a confined connection, and each confined connection targets the approved database;
     * - no connection carries a URL override;
     * - no PostgreSQL connection targets anything but the approved database;
     * - no connection of any driver names a forbidden database.
     */
    public static function assertConnectionsConfined(array $connections, string $approved, string $default): void
    {
        if (!in_array($default, self::CONFINED_CONNECTIONS, true)) {
            throw new IsolatedDatabaseRefused('not_confined', "Default connection '{$default}' is not a confined connection.");
        }
        foreach (self::CONFINED_CONNECTIONS as $name) {
            $config = $connections[$name] ?? null;
            if (!is_array($config) || ($config['driver'] ?? null) !== 'pgsql' || ($config['database'] ?? null) !== $approved) {
                throw new IsolatedDatabaseRefused('not_confined', "Connection '{$name}' does not target '{$approved}'.");
            }
        }
        foreach ($connections as $name => $config) {
            if (!is_array($config)) {
                continue;
            }
            $database = $config['database'] ?? null;
            if (!empty($config['url'])) {
                throw new IsolatedDatabaseRefused('not_confined', "Connection '{$name}' has a URL override.");
            }
            if (($config['driver'] ?? null) === 'pgsql' && $database !== $approved) {
                throw new IsolatedDatabaseRefused('not_confined', "PostgreSQL connection '{$name}' does not target '{$approved}'.");
            }
            if (is_string($database) && in_array(strtolower($database), self::FORBIDDEN_NAMES, true)) {
                throw new IsolatedDatabaseRefused('not_confined', "Connection '{$name}' names a forbidden database.");
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
