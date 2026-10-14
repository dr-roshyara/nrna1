<?php

/**
 * Bootstrap Test Database Configuration
 *
 * CRITICAL: This file MUST run before Laravel loads the config.
 * It ensures tests ALWAYS use the test database (nrna_test).
 *
 * This prevents accidental data corruption in development/production databases.
 */

// Load composer autoloader FIRST
require __DIR__ . '/../vendor/autoload.php';

// Force testing environment
$_SERVER['APP_ENV'] = 'testing';
$_ENV['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');

// Force test database TARGETING (not credentials).
//
// PBDIGIT-00: the password is deliberately NOT set here. It resolves from
// .env.testing (or the shell). Reasons:
//   1. A plaintext database password must not live in a tracked file.
//   2. Forcing it here overrode every other source — $_SERVER, $_ENV *and*
//      putenv are all set, and Laravel's env repository is immutable, so a
//      hardcoded value here silently beat .env.testing and phpunit.xml. The
//      same stale credential existed in all three places at once, and fixing
//      either of the other two changed nothing.
//
// Which values stay forced, and why: DB_DATABASE is the SAFETY mechanism.
// tests/TestCase.php skips transaction isolation for PostgreSQL and relies on
// migrate:fresh instead, which DROPS EVERY TABLE. Pinning the database here —
// before Laravel reads any config — is what prevents a stray .env from
// pointing that at the development database.
$dbUsername = 'publicdigit_user';

$_SERVER['DB_CONNECTION'] = 'pgsql';
$_SERVER['DB_HOST'] = '127.0.0.1';
$_SERVER['DB_PORT'] = '5432';
$_SERVER['DB_DATABASE'] = 'nrna_test';
$_SERVER['DB_USERNAME'] = $dbUsername;

// Also set $_ENV
$_ENV['DB_CONNECTION'] = 'pgsql';
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_PORT'] = '5432';
$_ENV['DB_DATABASE'] = 'nrna_test';
$_ENV['DB_USERNAME'] = $dbUsername;

// And putenv
putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=5432');
putenv('DB_DATABASE=nrna_test');
putenv('DB_USERNAME=' . $dbUsername);

// PBDIGIT-283 R2 (D-283-1): pin the OTHER PostgreSQL targets too. pgsql_audit and pgsql_geo read their own
// DB_AUDIT_* / DB_PGSQL_GEO_* variables (the geo default is `publicdigit`), and DATABASE_URL overrides a
// connection's target, so a stray .env could point them elsewhere. DATABASE_URL is set and EMPTY so .env cannot
// fill it in. Targeting only; passwords stay unpinned (PBDIGIT-00 above).
$otherTargets = ['DATABASE_URL' => ''];
foreach (['DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
    $otherTargets[$prefix . 'HOST'] = '127.0.0.1';
    $otherTargets[$prefix . 'PORT'] = '5432';
    $otherTargets[$prefix . 'DATABASE'] = 'nrna_test';
    $otherTargets[$prefix . 'USERNAME'] = $dbUsername;
}
foreach ($otherTargets as $key => $value) {
    $_SERVER[$key] = $value;
    $_ENV[$key] = $value;
    putenv($key . '=' . $value);
}
