<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Which databases a test may target: the shared, pure rules (PBDIGIT-283 R1, plan
 * docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md, F3, F7).
 *
 * Parameterised by the caller's approved set, so the default suite (TestDatabaseSafety::RESETTABLE) and the
 * PBDIGIT-274 isolated guard (its own approved name) share one set of rules without sharing a policy.
 * Pure: no framework, no database. Problems name the connection, never a URL (it may carry a password).
 */
final class DatabaseTargetRules
{
    /** Every PostgreSQL connection in config/database.php whose target comes from the environment. */
    public const CONNECTIONS = ['pgsql', 'pgsql_audit', 'pgsql_geo'];

    /** Words Laravel's env() turns into true / false / '' / null (Illuminate\Support\Env::get). */
    public const RESERVED_WORDS = ['true', 'false', 'empty', 'null'];

    /** A plain PostgreSQL identifier; anything else is refused, never corrected. */
    private const NAME_PATTERN = '/^[A-Za-z_][A-Za-z0-9_]{0,62}$/';

    /** @return 'missing'|'empty'|'malformed'|'reserved'|null null when $raw is a plain, unreserved identifier */
    public static function identifierProblem(mixed $raw): ?string
    {
        if ($raw === null || $raw === false) {
            return 'missing';
        }
        if (is_string($raw) && trim($raw) === '') {
            return 'empty';
        }
        if (!is_string($raw) || preg_match(self::NAME_PATTERN, $raw) !== 1) {
            return 'malformed';
        }
        if (in_array(strtolower($raw), self::RESERVED_WORDS, true)) {
            return 'reserved';
        }

        return null;
    }

    /**
     * A cached Laravel configuration ignores every environment variable: the default cache file, or any
     * APP_CONFIG_CACHE override (set at all, even empty), is a problem.
     *
     * @return 'config_cached'|null
     */
    public static function configCacheProblem(string $configCachePath, array $env): ?string
    {
        return array_key_exists('APP_CONFIG_CACHE', $env) || file_exists($configCachePath) ? 'config_cached' : null;
    }

    /**
     * Configuration-level check (no connection is opened). Every problem is reported, not only the first:
     * - APP_ENV is 'testing', and the default connection is 'pgsql';
     * - each confined connection is PostgreSQL on a database in $approved (exact, case-sensitive);
     * - no connection, of any driver, carries a URL override.
     *
     * @param list<string> $approved an empty set refuses everything
     * @return list<string>
     */
    public static function targetProblems(string $appEnv, string $default, array $connections, array $approved): array
    {
        $problems = [];
        if ($appEnv !== 'testing') {
            $problems[] = "APP_ENV is '{$appEnv}', not 'testing'.";
        }
        if ($default !== 'pgsql') {
            $problems[] = "The default connection is '{$default}', not 'pgsql'.";
        }
        foreach (self::CONNECTIONS as $name) {
            $config = $connections[$name] ?? null;
            if (!is_array($config) || ($config['driver'] ?? null) !== 'pgsql') {
                $problems[] = "Connection '{$name}' is missing or not PostgreSQL.";
            } elseif (!in_array($config['database'] ?? null, $approved, true)) {
                $database = is_string($config['database'] ?? null) ? "'{$config['database']}'" : 'no database';
                $problems[] = "Connection '{$name}' targets {$database}, which is not approved.";
            }
        }
        foreach ($connections as $name => $config) {
            if (is_array($config) && !empty($config['url'])) {
                $problems[] = "Connection '{$name}' has a URL override.";
            }
        }

        return $problems;
    }
}
