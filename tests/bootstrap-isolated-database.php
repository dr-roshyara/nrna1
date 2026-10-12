<?php

/**
 * PHPUnit bootstrap for the isolated PBDIGIT-274 database tests (phpunit.pbdigit274.xml only).
 *
 * Runs before Laravel exists. It aborts the whole run unless, in this order:
 *   1. PBDIGIT274_TEST_DB is well formed: not blank, a plain identifier, not a word Laravel's env()
 *      reinterprets, not a shared database;
 *   2. PBDIGIT274_DB_HOST / _USERNAME (and optional _PORT / _PASSWORD) are set;
 *   3. Laravel's configuration is not cached and APP_CONFIG_CACHE is not set;
 *   4. the name is exactly DatabaseIdentityGuard::APPROVED_NAME (empty until G4, so nothing passes yet);
 *   5. a raw PDO connection reports current_database() === that name.
 * Only then does it pin every PostgreSQL connection to that database. It never falls back to another
 * database and never prints a credential. Plan section 11.0 (S2-3), gates G4 / G5; step 1b.
 */

require __DIR__.'/../vendor/autoload.php';

use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;

try {
    $name = DatabaseIdentityGuard::wellFormedName(getenv(DatabaseIdentityGuard::ENV_NAME));
    $settings = DatabaseIdentityGuard::connectionSettings(getenv());
    DatabaseIdentityGuard::refuseIfConfigCached(__DIR__.'/../bootstrap/cache/config.php', getenv());
    $approved = DatabaseIdentityGuard::approvedName($name);
    DatabaseIdentityGuard::verifyIdentity($approved, DatabaseIdentityGuard::pdoCurrentDatabase($approved, $settings));
} catch (IsolatedDatabaseRefused $e) {
    fwrite(STDERR, 'PBDIGIT-274 isolated database guard: REFUSED '.$e->getMessage().PHP_EOL);
    exit(1);
}

DatabaseIdentityGuard::applyEnvironment(DatabaseIdentityGuard::environmentFor($approved, $settings));

fwrite(STDERR, "PBDIGIT-274 isolated database guard: current_database() = '{$approved}' confirmed.".PHP_EOL);
