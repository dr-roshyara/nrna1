<?php

/**
 * PHPUnit bootstrap for the isolated PBDIGIT-274 database tests (phpunit.pbdigit274.xml only).
 *
 * Runs before Laravel exists. It aborts the whole run unless:
 *   - PBDIGIT274_TEST_DB is a well-formed name and not a shared database;
 *   - PBDIGIT274_DB_HOST / _USERNAME (and optional _PORT / _PASSWORD) are set;
 *   - Laravel's configuration is not cached;
 *   - a raw PDO connection reports current_database() === PBDIGIT274_TEST_DB.
 * Only then does it pin every PostgreSQL connection to that database. It never falls back to another
 * database and never prints a credential. Plan section 11.0 (S2-3), gates G4 / G5.
 */

require __DIR__.'/../vendor/autoload.php';

use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;

try {
    $approved = DatabaseIdentityGuard::approvedName(getenv(DatabaseIdentityGuard::ENV_NAME));
    $settings = DatabaseIdentityGuard::connectionSettings(getenv());
    DatabaseIdentityGuard::refuseIfConfigCached(__DIR__.'/../bootstrap/cache/config.php');
    DatabaseIdentityGuard::verifyIdentity($approved, DatabaseIdentityGuard::pdoCurrentDatabase($approved, $settings));
} catch (IsolatedDatabaseRefused $e) {
    fwrite(STDERR, 'PBDIGIT-274 isolated database guard: REFUSED '.$e->getMessage().PHP_EOL);
    exit(1);
}

foreach (DatabaseIdentityGuard::environmentFor($approved, $settings) as $key => $value) {
    $_SERVER[$key] = $value;
    $_ENV[$key] = $value;
    putenv("{$key}={$value}");
}

fwrite(STDERR, "PBDIGIT-274 isolated database guard: current_database() = '{$approved}' confirmed.".PHP_EOL);
