<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use Tests\Architecture\Support\MembershipSourceScan;

/**
 * R5 (PBDIGIT-271, Slice 2a step 7): the Election-Only boundary. No code outside the Membership context may
 * depend on the new Membership ledger, so Election-Only eligibility can never consult it. Static scan: no
 * Laravel boot, no database. The only allowlisted file is the migration that creates the ledger tables.
 */
final class MembershipLedgerIsolationTest extends TestCase
{
    private const SCANNED = ['app', 'routes', 'config', 'database'];
    private const EXCLUDED = ['app/Contexts/Membership/'];
    private const ALLOWLIST = ['database/migrations/2026_10_09_100000_create_org_membership_tables.php'];
    private const FORBIDDEN = [
        'App\\Contexts\\Membership\\Domain\\Organisational',
        'App\\Contexts\\Membership\\Application\\Organisational',
        'App\\Contexts\\Membership\\Infrastructure\\Organisational',
        'org_memberships',
        'org_membership_facts',
        'org_membership_commands',
    ];

    public function test_the_scanner_detects_a_planted_dependency_and_respects_the_allowlist(): void
    {
        $sources = [
            'app/Domain/Election/PlantedVoterSource.php' => "<?php\nuse App\\Contexts\\Membership\\Domain\\Organisational\\Member;\n",
            'app/Services/PlantedQuery.php' => "<?php\nDB::table('org_membership_facts')->get();\n",
            'app/Services/PlantedString.php' => "<?php\n\$c = 'App\\\\Contexts\\\\Membership\\\\Application\\\\Organisational\\\\AdmitMember';\n",
            self::ALLOWLIST[0] => "<?php\nSchema::create('org_memberships', fn () => null);\n",
            'app/Services/Clean.php' => "<?php\nuse App\\Contexts\\Membership\\Domain\\Member\\MemberStatus;\n",
        ];

        $violations = MembershipSourceScan::violations($sources, self::FORBIDDEN, self::ALLOWLIST);

        $this->assertSame([
            'app/Domain/Election/PlantedVoterSource.php: App\\Contexts\\Membership\\Domain\\Organisational',
            'app/Services/PlantedQuery.php: org_membership_facts',
            'app/Services/PlantedString.php: App\\Contexts\\Membership\\Application\\Organisational',
        ], $violations);
    }

    public function test_no_code_outside_membership_depends_on_the_new_ledger(): void
    {
        $sources = MembershipSourceScan::sources(dirname(__DIR__, 2), self::SCANNED, self::EXCLUDED);

        $this->assertNotEmpty($sources, 'the scan found no files');
        $this->assertSame([], MembershipSourceScan::violations($sources, self::FORBIDDEN, self::ALLOWLIST));
    }
}
