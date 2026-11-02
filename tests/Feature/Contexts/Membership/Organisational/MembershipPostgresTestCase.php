<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use Illuminate\Support\Facades\DB;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\TestCase;

/**
 * Base class of the Membership PostgreSQL tests (PBDIGIT-324, review finding F1).
 *
 * In a Membership run (phpunit.membership-pg.xml via scripts/testing/membership-pg.sh), before Tests\TestCase's
 * reset check and before RefreshDatabase runs migrate:fresh:
 * - it removes every unconfined PostgreSQL connection (e.g. the hard-coded `testing` → nrna_test);
 * - it checks that every remaining connection targets membership_pg_isolated;
 * - it confirms on the live Laravel connection that this is the cluster whose system_identifier was captured at
 *   container start.
 *
 * This is the same pattern as Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase, with the same guard.
 * Outside such a run (the default phpunit.xml, CI), nothing changes. Tests\TestCase's PBDIGIT-283 check applies
 * as before.
 *
 * Hooked in setUpTraits(), like Tests\TestCase, so that a test class repeating `use RefreshDatabase;` cannot skip it.
 */
abstract class MembershipPostgresTestCase extends TestCase
{
    protected function setUpTraits()
    {
        $kept = MembershipPgIsolatedDatabase::confinedConnections(
            getenv(),
            (array) config('database.connections'),
            (string) config('database.default'),
            fn () => DB::connection()->selectOne('SELECT current_database() AS name')->name,
            fn () => DB::connection()->selectOne('SELECT system_identifier::text AS id FROM pg_control_system()')->id,
        );
        if ($kept !== null) {
            config(['database.connections' => $kept]);
        }

        return parent::setUpTraits();
    }
}
