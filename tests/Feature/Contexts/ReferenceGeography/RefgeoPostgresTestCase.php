<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * Base of the Reference Geography PostgreSQL tests (PBDIGIT-346). Same confinement as MembershipPostgresTestCase,
 * plus: every connection this test opened is closed when it ends. Without this, each test's application keeps its
 * connections until PHP collects it, and the full multi-suite run (Geography runs last) exhausted the cluster's
 * connections ("too many clients already", found in G-4f; evidence g4f/too-many-clients-diagnosis.txt).
 */
abstract class RefgeoPostgresTestCase extends MembershipPostgresTestCase
{
    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }
}
