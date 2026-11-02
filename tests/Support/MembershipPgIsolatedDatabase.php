<?php

declare(strict_types=1);

namespace Tests\Support;

use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

/**
 * PBDIGIT-324 (roadmap W2, decision W2-1 = C): the profile of the throwaway PostgreSQL cluster that runs the
 * Membership PostgreSQL suite (tests/Feature/Contexts/Membership/Organisational) through phpunit.membership-pg.xml.
 *
 * It is a profile of the existing PBDIGIT-274 identity guard (Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard),
 * not a second guard. The values are fixed by the approved plan
 * (docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md §3); changing any of them is a reviewable change.
 */
final class MembershipPgIsolatedDatabase
{
    /** The only database name this suite accepts; created by scripts/testing/membership-pg.sh inside the container. */
    public const APPROVED_NAME = 'membership_pg_isolated';

    /** The variable naming the database for this run. */
    public const ENV_NAME = 'MEMBERSHIP_PG_TEST_DB';

    /** Credentials come only from MEMBERSHIP_PG_DB_HOST / _PORT / _USERNAME / _PASSWORD, never from DB_* or .env. */
    public const CREDENTIAL_PREFIX = 'MEMBERSHIP_PG_DB_';

    /** The cluster's system_identifier, captured by the script right after the container started. */
    public const SYSTEM_ID_ENV = 'MEMBERSHIP_PG_SYSTEM_ID';

    /**
     * In-run confinement (review finding F1, 2026-11-02), used by MembershipPostgresTestCase before any reset.
     *
     * - Outside a Membership run (MEMBERSHIP_PG_TEST_DB not set) it returns null and asks nothing. The default and
     *   CI runs of these test files are unchanged.
     * - Inside a run, configuration first: unconfined PostgreSQL connections (config/database.php's hard-coded
     *   `testing` → nrna_test) are removed, and every remaining connection must target the approved database with
     *   no URL override. Only then is the live Laravel connection asked for the cluster's system_identifier (it must
     *   equal MEMBERSHIP_PG_SYSTEM_ID) and for current_database().
     *
     * @param array<string, string> $env getenv()
     * @param callable(): mixed $currentDatabase SELECT current_database() on the default connection
     * @param callable(): mixed $systemIdentifier SELECT system_identifier::text FROM pg_control_system() on the default connection
     * @return array<string, mixed>|null the connections to install, or null when no Membership run is active
     */
    public static function confinedConnections(array $env, array $connections, string $default, callable $currentDatabase, callable $systemIdentifier): ?array
    {
        if (!array_key_exists(self::ENV_NAME, $env)) {
            return null;
        }
        $approved = DatabaseIdentityGuard::approvedName($env[self::ENV_NAME], self::APPROVED_NAME, self::ENV_NAME);
        $kept = DatabaseIdentityGuard::withoutUnconfinedPostgresConnections($connections);
        DatabaseIdentityGuard::assertConnectionsConfined($kept, $approved, $default);
        DatabaseIdentityGuard::verifySystemIdentifier($env[self::SYSTEM_ID_ENV] ?? false, $systemIdentifier);
        DatabaseIdentityGuard::verifyIdentity($approved, $currentDatabase);

        return $kept;
    }
}
