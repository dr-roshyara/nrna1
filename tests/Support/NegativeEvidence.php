<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * PBDIGIT-283 R6 negative evidence (G-RUN-NEG): the pure decisions of the harness
 * tests/Evidence/Pbdigit283/negative-evidence.php. Plan
 * docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md §6 R6 (D-283-4, F6, Option A).
 *
 * The run reproduces the 2026-10-13 incident's shape (`--no-configuration`, a non-test database configured) with
 * every reachable target replaced by a non-existent socket and a closed port, so even a broken guard meets nothing.
 * Pure: no framework, no database, no file system.
 */
final class NegativeEvidence
{
    /** A PostgreSQL/MySQL host starting with '/' is a socket directory; this one doesn't exist. */
    public const SOCKET = '/nonexistent-283-negative-evidence-socket-dir';

    public const CLOSED_PORT = '1';

    /** The incident's database: the canary is configured for it, so the R3 check must refuse. */
    public const UNSAFE_DATABASE = 'publicdigit';

    /** config/database.php's connections on 2026-10-14; anything else stops the run. */
    public const KNOWN_CONNECTIONS = ['sqlite', 'mysql', 'pgsql', 'pgsql_audit', 'pgsql_geo', 'sqlsrv', 'testing'];

    /**
     * Option A (Principal, 2026-10-14): the ONE hard-coded connection that environment variables can't neutralise,
     * accepted exactly as found: not the default, no URL, an approved test database. Its password is never read or
     * compared. Removal and credential review: backlog PBDIGIT-292.
     */
    public const OPTION_A_EXCEPTION = ['name' => 'testing', 'driver' => 'pgsql', 'host' => '127.0.0.1', 'port' => '5432', 'database' => 'nrna_test', 'username' => 'nrna'];

    /** The refusal R3 must produce, and the reason it must give. */
    public const REFUSAL = 'PBDIGIT-283: refusing to boot Laravel for a test before any service provider is registered';

    public const REFUSAL_REASON = "Connection 'pgsql' targets 'publicdigit', which is not approved.";

    public const CANARY = 'Tests\Evidence\Pbdigit283\ResetCanaryTest::test_the_canary_body_is_never_reached';

    /** @return array<string, string> every variable that selects a database target, unsafe database behind dead endpoints */
    public static function variables(): array
    {
        $vars = ['APP_ENV' => 'testing', 'DB_CONNECTION' => 'pgsql', 'DATABASE_URL' => ''];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $vars[$prefix.'HOST'] = self::SOCKET;
            $vars[$prefix.'PORT'] = self::CLOSED_PORT;
            $vars[$prefix.'DATABASE'] = self::UNSAFE_DATABASE;
            $vars[$prefix.'USERNAME'] = 'nobody';
            $vars[$prefix.'PASSWORD'] = '';
        }
        $vars['DB_READ_HOST'] = self::SOCKET;
        $vars['DB_WRITE_HOST'] = self::SOCKET;
        $vars['DB_SOCKET'] = self::SOCKET;

        return $vars;
    }

    /** The complete environment of the child processes: nothing is inherited except PATH and HOME. */
    public static function childEnvironment(string $path, string $home): array
    {
        return ['PATH' => $path, 'HOME' => $home] + self::variables();
    }

    /**
     * The pre-run check over the report printed by tests/Evidence/Pbdigit283/preflight.php.
     *
     * @return list<string> problems; empty = the canary may run
     */
    public static function preflightProblems(array $report): array
    {
        $problems = [];
        if (($report['cached'] ?? true) !== false) {
            $problems[] = 'the configuration is cached';
        }
        if (($report['env'] ?? null) !== 'testing') {
            $problems[] = 'APP_ENV is not testing';
        }
        if (($report['default'] ?? null) !== 'pgsql') {
            $problems[] = 'the default connection is not pgsql';
        }
        if (($report['bound_db'] ?? true) !== false) {
            $problems[] = 'the database manager is bound (providers were registered)';
        }
        if (($report['sqlite_target_exists'] ?? true) !== false) {
            $problems[] = 'the sqlite target file exists';
        }

        $connections = is_array($report['connections'] ?? null) ? $report['connections'] : [];
        $names = array_keys($connections);
        $known = self::KNOWN_CONNECTIONS;
        sort($names);
        sort($known);
        if ($names !== $known) {
            $problems[] = 'the configured connections are not exactly: '.implode(', ', self::KNOWN_CONNECTIONS);
        }
        $password = false;
        array_walk_recursive($connections, function ($value, $key) use (&$password): void {
            $password = $password || $key === 'password';
        });
        if ($password) {
            $problems[] = 'the report contains a password';
        }

        foreach ($connections as $name => $config) {
            if (is_array($config) && ! empty($config['url'])) {
                $problems[] = "connection '{$name}' has a URL";
            }
        }
        foreach (['pgsql', 'pgsql_audit', 'pgsql_geo', 'mysql', 'sqlsrv'] as $name) {
            $config = $connections[$name] ?? [];
            if (($config['host'] ?? null) !== self::SOCKET || (string) ($config['port'] ?? '') !== self::CLOSED_PORT) {
                $problems[] = "connection '{$name}' is not at the dead socket and closed port";
            }
        }
        if (($connections['mysql']['write']['host'] ?? null) !== self::SOCKET || ($connections['mysql']['unix_socket'] ?? null) !== self::SOCKET) {
            $problems[] = "connection 'mysql' write host or unix socket is not the dead socket";
        }
        if (($connections['sqlite']['driver'] ?? null) !== 'sqlite') {
            $problems[] = "connection 'sqlite' is not sqlite";
        }
        if (($connections['pgsql']['database'] ?? null) !== self::UNSAFE_DATABASE) {
            $problems[] = 'the default connection does not target the unsafe canary database';
        }

        $exception = $connections[self::OPTION_A_EXCEPTION['name']] ?? null;
        foreach (['driver', 'host', 'port', 'database', 'username'] as $key) {
            if (! is_array($exception) || (string) ($exception[$key] ?? '') !== self::OPTION_A_EXCEPTION[$key]) {
                $problems[] = "the Option A exception '".self::OPTION_A_EXCEPTION['name']."' does not match exactly ({$key}); stop (PBDIGIT-292)";
                break;
            }
        }

        return $problems;
    }

    /**
     * Whether the canary run is the evidence: exactly the canary ran, R3 refused it for the unsafe default database,
     * and no connection was attempted.
     *
     * @return list<string> problems; empty = evidence
     */
    public static function refusalProblems(int $exitCode, string $output): array
    {
        $problems = [];
        if ($exitCode === 0) {
            $problems[] = 'the canary run exited 0';
        }
        foreach ([self::REFUSAL, self::REFUSAL_REASON, self::CANARY, 'Tests: 1,'] as $expected) {
            if (! str_contains($output, $expected)) {
                $problems[] = "the output lacks: {$expected}";
            }
        }
        foreach (['SQLSTATE', 'connection to server', 'the canary body was reached'] as $forbidden) {
            if (str_contains($output, $forbidden)) {
                $problems[] = "the output contains: {$forbidden}";
            }
        }

        return $problems;
    }
}
