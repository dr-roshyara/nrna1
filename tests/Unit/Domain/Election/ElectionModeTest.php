<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\Enum\ElectionMode;
use App\Models\Organisation;
use PHPUnit\Framework\TestCase;

/**
 * ElectionModeTest — Domain bifurcation point
 *
 * ElectionMode represents the two operational modes:
 * - FULL_MEMBERSHIP: requires member table + fees validation
 * - ELECTION_ONLY: only needs organisation_users table
 *
 * This is the gateway for Phase B policy routing.
 */
class ElectionModeTest extends TestCase
{
    /**
     * Test E.1.1: ElectionMode enum has FullMembership and ElectionOnly cases
     */
    public function test_election_mode_has_two_cases(): void
    {
        $cases = ElectionMode::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(ElectionMode::FullMembership, $cases);
        $this->assertContains(ElectionMode::ElectionOnly, $cases);
    }

    /**
     * Test E.1.2: FullMembership case exists
     */
    public function test_full_membership_case_exists(): void
    {
        $this->assertEquals('full_membership', ElectionMode::FullMembership->value);
    }

    /**
     * Test E.1.3: ElectionOnly case exists
     */
    public function test_election_only_case_exists(): void
    {
        $this->assertEquals('election_only', ElectionMode::ElectionOnly->value);
    }

    /**
     * Test E.1.4: fromOrganisation() reads uses_full_membership flag
     */
    public function test_from_organisation_maps_uses_full_membership(): void
    {
        $fullMembershipOrg = new Organisation(['uses_full_membership' => true]);
        $this->assertEquals(ElectionMode::FullMembership, ElectionMode::fromOrganisation($fullMembershipOrg));

        $electionOnlyOrg = new Organisation(['uses_full_membership' => false]);
        $this->assertEquals(ElectionMode::ElectionOnly, ElectionMode::fromOrganisation($electionOnlyOrg));
    }

    /**
     * Test E.1.5: isElectionOnly() helper
     */
    public function test_is_election_only_helper(): void
    {
        $this->assertTrue(ElectionMode::ElectionOnly->isElectionOnly());
        $this->assertFalse(ElectionMode::FullMembership->isElectionOnly());
    }

    /**
     * Test E.1.6: isFullMembership() helper
     */
    public function test_is_full_membership_helper(): void
    {
        $this->assertTrue(ElectionMode::FullMembership->isFullMembership());
        $this->assertFalse(ElectionMode::ElectionOnly->isFullMembership());
    }

    /**
     * Test E.1.7: label() returns human-readable string
     */
    public function test_label_returns_human_readable_string(): void
    {
        $this->assertEquals('Full Membership', ElectionMode::FullMembership->label());
        $this->assertEquals('Election-Only', ElectionMode::ElectionOnly->label());
    }

    /**
     * Test E.1.8: Can be used in switch statements
     */
    public function test_enum_works_in_switch(): void
    {
        $mode = ElectionMode::ElectionOnly;

        $result = match($mode) {
            ElectionMode::ElectionOnly => 'election_only_path',
            ElectionMode::FullMembership => 'full_membership_path',
        };

        $this->assertEquals('election_only_path', $result);
    }
}
