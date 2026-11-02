<?php

/**
 * PHPUnit bootstrap for the Membership PostgreSQL suite (phpunit.membership-pg.xml only). PBDIGIT-324, roadmap W2,
 * decision W2-1 = C; plan docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md §3.
 *
 * The PBDIGIT-274 identity guard with the Membership profile (Tests\Support\MembershipPgIsolatedDatabase),
 * plus the cluster check of §3.2. It runs before Laravel exists and aborts the whole run unless, in this order:
 *   1. MEMBERSHIP_PG_TEST_DB is well formed (not blank, a plain identifier, not reserved, not a shared database);
 *   2. MEMBERSHIP_PG_DB_HOST / _USERNAME (and optional _PORT / _PASSWORD) are set; never DB_* or .env;
 *   3. Laravel's configuration is not cached and APP_CONFIG_CACHE is not set;
 *   4. the name is exactly membership_pg_isolated;
 *   5. MEMBERSHIP_PG_SYSTEM_ID is present and plain digits, and a raw PDO connection reports the same
 *      system_identifier (the throwaway cluster started by scripts/testing/membership-pg.sh);
 *   6. the same connection reports current_database() === membership_pg_isolated.
 * Only then does it pin every PostgreSQL connection to that database. It never falls back to another database
 * and never prints a credential.
 */

require __DIR__.'/../vendor/autoload.php';

use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;
use Tests\Support\MembershipPgIsolatedDatabase as Profile;

try {
    $name = DatabaseIdentityGuard::wellFormedName(getenv(Profile::ENV_NAME), Profile::ENV_NAME);
    $settings = DatabaseIdentityGuard::connectionSettings(getenv(), Profile::CREDENTIAL_PREFIX);
    DatabaseIdentityGuard::refuseIfConfigCached(__DIR__.'/../bootstrap/cache/config.php', getenv());
    $approved = DatabaseIdentityGuard::approvedName($name, Profile::APPROVED_NAME, Profile::ENV_NAME);
    DatabaseIdentityGuard::verifySystemIdentifier(getenv(Profile::SYSTEM_ID_ENV), DatabaseIdentityGuard::pdoSystemIdentifier($approved, $settings));
    DatabaseIdentityGuard::verifyIdentity($approved, DatabaseIdentityGuard::pdoCurrentDatabase($approved, $settings));
} catch (IsolatedDatabaseRefused $e) {
    fwrite(STDERR, 'PBDIGIT-324 Membership PostgreSQL guard: REFUSED '.$e->getMessage().PHP_EOL);
    exit(1);
}

DatabaseIdentityGuard::applyEnvironment(DatabaseIdentityGuard::environmentFor($approved, $settings));

fwrite(STDERR, "PBDIGIT-324 Membership PostgreSQL guard: cluster system_identifier and current_database() = '{$approved}' confirmed.".PHP_EOL);
