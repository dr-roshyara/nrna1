<?php

declare(strict_types=1);

namespace Tests\Support;

use RuntimeException;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

/**
 * Which databases a test may reset (RefreshDatabase runs migrate:fresh on PostgreSQL, which drops every table).
 *
 * Incident 2026-10-13: a test run with `--no-configuration` skipped phpunit.xml's pinning, used the .env database
 * and wiped the development database `publicdigit`. Since then Tests\TestCase asks this class, with the ACTUAL
 * database (current_database()), before any schema change. Pure: no framework, no database.
 */
final class TestDatabaseSafety
{
    /** The only databases a test may reset: phpunit.xml / phpunit-pgsql.xml, and the PBDIGIT-274 isolated database. */
    public const RESETTABLE = ['nrna_test', DatabaseIdentityGuard::APPROVED_NAME];

    public static function assertResettable(string $driver, ?string $database): void
    {
        if ($driver === 'pgsql' && in_array($database, self::RESETTABLE, true)) {
            return;
        }

        throw new RuntimeException(sprintf(
            'Refusing to reset database "%s" (driver %s): tests may only reset %s. Nothing was changed. '
            .'Run tests through phpunit.xml (vendor/bin/phpunit) or phpunit.pbdigit274.xml, never with --no-configuration '
            .'for tests that extend Tests\TestCase.',
            $database ?? '(unknown)',
            $driver,
            implode(' or ', self::RESETTABLE),
        ));
    }

    /**
     * PBDIGIT-283 R3 (D-283-2): the LOADED configuration, checked by the hook in Tests\CreatesApplication after
     * LoadConfiguration and before any provider is registered. Uses the shared rules with this suite's approved
     * set, and refuses a cached configuration. Every problem is reported; no URL is echoed.
     */
    public static function refuseUnsafeConfiguration(string $appEnv, string $default, array $connections, string $cachedConfigPath, array $env): void
    {
        $problems = DatabaseTargetRules::targetProblems($appEnv, $default, $connections, self::RESETTABLE);
        if (DatabaseTargetRules::configCacheProblem($cachedConfigPath, $env) !== null) {
            $problems[] = 'The configuration is cached (bootstrap/cache/config.php or APP_CONFIG_CACHE); run without a config cache.';
        }
        if ($problems === []) {
            return;
        }

        throw new RuntimeException('PBDIGIT-283: refusing to boot Laravel for a test before any service provider is registered; '
            .'nothing was connected. '.implode(' ', $problems).' Run through phpunit.xml, or mark a database-free test with '
            .'Tests\Support\DatabaseFreeTest.');
    }
}
