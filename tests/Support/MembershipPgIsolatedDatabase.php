<?php

declare(strict_types=1);

namespace Tests\Support;

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
}
