<?php

namespace Tests\Isolated\Pbdigit274;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

/**
 * Base class for the PBDIGIT-274 database tests (S2 onwards). Run only through phpunit.pbdigit274.xml, whose
 * bootstrap has already confirmed the database identity before Laravel booted.
 *
 * Second, independent check (plan section 11.0, S2-3): before RefreshDatabase may run migrate:fresh, the
 * booted configuration must confine every PostgreSQL connection to PBDIGIT274_TEST_DB and the live
 * connection must report that name. It deliberately does not extend Tests\TestCase, whose setup targets the
 * shared test database.
 *
 * Not yet exercised against PostgreSQL: that needs G4 (an isolated database named by the PO).
 */
abstract class IsolatedDatabaseTestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase {
        refreshDatabase as private refreshDatabaseAfterIdentityCheck;
    }

    public function refreshDatabase(): void
    {
        // Step 1b (L1): unconfined PostgreSQL connections (e.g. the hard-coded `testing` → nrna_test) are
        // removed, then EVERY remaining connection is checked before the live identity is asked.
        config(['database.connections' => DatabaseIdentityGuard::withoutUnconfinedPostgresConnections(config('database.connections'))]);

        DatabaseIdentityGuard::confirmBeforeRefresh(
            config('database.connections'),
            DatabaseIdentityGuard::approvedName(getenv(DatabaseIdentityGuard::ENV_NAME)),
            (string) config('database.default'),
            fn () => DB::connection()->selectOne('SELECT current_database() AS name')->name,
        );

        $this->refreshDatabaseAfterIdentityCheck();
    }
}
