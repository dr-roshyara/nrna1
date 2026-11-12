<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform\Harness;

use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 0 (slice 2 plan §6; delta T-1 … T-25 need it): the isolated cluster provides one central and three
 * organisation databases. Plain PHPUnit and raw PDO only, so a failure means the harness lacks the capability, not
 * that Laravel is misconfigured.
 */
final class PlatformPgProvisioningTest extends TestCase
{
    private const EXPECTED = ['platform_pg_central', 'platform_pg_org_a', 'platform_pg_org_b', 'platform_pg_org_c'];

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
    }

    public function test_the_harness_provides_exactly_the_four_platform_databases(): void
    {
        $listed = getenv('PLATFORM_PG_DATABASES');

        $this->assertNotFalse($listed, 'the harness must export PLATFORM_PG_DATABASES');
        $this->assertSame(self::EXPECTED, explode(',', (string) $listed));
    }

    public function test_each_platform_database_exists_on_the_captured_throwaway_cluster(): void
    {
        $settings = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $clusterId = (string) getenv(MembershipPgIsolatedDatabase::SYSTEM_ID_ENV);

        foreach (self::EXPECTED as $name) {
            try {
                $current = (DatabaseIdentityGuard::pdoCurrentDatabase($name, $settings))();
                $system = (DatabaseIdentityGuard::pdoSystemIdentifier($name, $settings))();
            } catch (\PDOException $e) {
                // The driver's message may echo connection details; report the database name only.
                $this->fail("database '{$name}' is not reachable on the isolated cluster");
            }

            $this->assertSame($name, $current);
            $this->assertSame($clusterId, (string) $system, "'{$name}' must be on the cluster captured at container start");
        }
    }
}
