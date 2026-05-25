<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\Organisation;
use PHPUnit\Framework\TestCase;

/**
 * VoterSourceStrategyTest — Constitutional voter participation authority
 *
 * VoterSourceStrategy represents the two voter registry mechanisms:
 * - MembershipRegistry: formal membership governance (member table + fees validation)
 * - ImportedVoterRegistry: direct org enrollment (organisation_users table)
 *
 * Phase 3 transitional semantic bridge vocabulary.
 */
class VoterSourceStrategyTest extends TestCase
{
    /**
     * Test E.1.1: VoterSourceStrategy enum has MembershipRegistry and ImportedVoterRegistry cases
     */
    public function test_voter_source_strategy_has_two_cases(): void
    {
        $cases = VoterSourceStrategy::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(VoterSourceStrategy::MembershipRegistry, $cases);
        $this->assertContains(VoterSourceStrategy::ImportedVoterRegistry, $cases);
    }

    /**
     * Test E.1.2: MembershipRegistry case exists
     */
    public function test_membership_registry_case_exists(): void
    {
        $this->assertEquals('full_membership', VoterSourceStrategy::MembershipRegistry->value);
    }

    /**
     * Test E.1.3: ImportedVoterRegistry case exists
     */
    public function test_imported_voter_registry_case_exists(): void
    {
        $this->assertEquals('election_only', VoterSourceStrategy::ImportedVoterRegistry->value);
    }

    /**
     * Test E.1.4: fromOrganisation() reads uses_full_membership flag
     */
    public function test_from_organisation_maps_uses_full_membership(): void
    {
        $fullMembershipOrg = new Organisation(['uses_full_membership' => true]);
        $this->assertEquals(VoterSourceStrategy::MembershipRegistry, VoterSourceStrategy::fromOrganisation($fullMembershipOrg));

        $electionOnlyOrg = new Organisation(['uses_full_membership' => false]);
        $this->assertEquals(VoterSourceStrategy::ImportedVoterRegistry, VoterSourceStrategy::fromOrganisation($electionOnlyOrg));
    }

    /**
     * Test E.1.5: isImportedVoterRegistry() helper
     */
    public function test_is_imported_voter_registry_helper(): void
    {
        $this->assertTrue(VoterSourceStrategy::ImportedVoterRegistry->isImportedVoterRegistry());
        $this->assertFalse(VoterSourceStrategy::MembershipRegistry->isImportedVoterRegistry());
    }

    /**
     * Test E.1.6: isMembershipRegistry() helper
     */
    public function test_is_membership_registry_helper(): void
    {
        $this->assertTrue(VoterSourceStrategy::MembershipRegistry->isMembershipRegistry());
        $this->assertFalse(VoterSourceStrategy::ImportedVoterRegistry->isMembershipRegistry());
    }

    /**
     * Test E.1.7: label() returns human-readable string
     */
    public function test_label_returns_human_readable_string(): void
    {
        $this->assertEquals('Full Membership', VoterSourceStrategy::MembershipRegistry->label());
        $this->assertEquals('Election-Only', VoterSourceStrategy::ImportedVoterRegistry->label());
    }

    /**
     * Test E.1.8: Can be used in switch statements
     */
    public function test_enum_works_in_switch(): void
    {
        $mode = VoterSourceStrategy::ImportedVoterRegistry;

        $result = match($mode) {
            VoterSourceStrategy::ImportedVoterRegistry => 'imported_voter_registry_path',
            VoterSourceStrategy::MembershipRegistry => 'membership_registry_path',
        };

        $this->assertEquals('imported_voter_registry_path', $result);
    }
}
