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
}
