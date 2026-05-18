<?php

namespace Tests\Boundary;

use App\Contexts\Elections\Domain\Policies\ElectionOnlyPolicy;
use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\ElectionMode;
use PHPUnit\Framework\TestCase;

/**
 * ModeIsolationBoundaryTest — DDD firewall for mode boundaries
 *
 * Responsibility: Prevent architectural regression where logic from one mode
 * accidentally affects another mode.
 *
 * ✔ Purpose:
 * - Guard: ElectionOnly policy ONLY uses election-only logic
 * - Guard: FullMembership policy ONLY uses full-membership logic
 * - Prevent: Logic leakage between modes
 * - Prevent: Fallback/default behavior that crosses boundaries
 *
 * ❌ Forbidden:
 * - Implementation details
 * - Database testing
 * - Infrastructure specifics
 *
 * Mental Model: "Are architectural boundaries holding?"
 *
 * This is the strongest regression guard for the Strangler Fig migration.
 * If this fails, a future developer has accidentally mixed mode logic.
 */
class ModeIsolationBoundaryTest extends TestCase
{
    /**
     * Test B.1.1: ElectionOnlyPolicy rejects full-membership mode context
     *
     * Firewall: ElectionOnly should never make decisions for FullMembership mode
     */
    public function test_election_only_policy_rejects_full_membership_mode(): void
    {
        $policy = new ElectionOnlyPolicy();

        $fullMembershipContext = new EligibilityContext(
            userId: 'user-1',
            organisationId: 'org-1',
            mode: ElectionMode::FullMembership,
            isActive: true,
            isDeleted: false,
            membershipStatus: 'active',
            feesStatus: 'paid',
        );

        $result = $policy->decideForContext($fullMembershipContext);

        // ElectionOnly policy should NEVER return true for FullMembership mode
        $this->assertFalse($result, 'ElectionOnlyPolicy must reject FullMembership mode context');
    }

    /**
     * Test B.1.2: ElectionOnlyPolicy ignores membership fields
     *
     * Firewall: ElectionOnly should not inspect or validate membership-specific fields.
     * If these fields accidentally affect decision, boundary is breached.
     */
    public function test_election_only_policy_ignores_membership_fields(): void
    {
        $policy = new ElectionOnlyPolicy();

        // Context with FullMembership mode data, ElectionOnly mode
        $contextWithMembershipData = new EligibilityContext(
            userId: 'user-1',
            organisationId: 'org-1',
            mode: ElectionMode::ElectionOnly,
            isActive: true,
            isDeleted: false,
            membershipStatus: 'inactive', // Should be ignored
            feesStatus: 'unpaid', // Should be ignored
        );

        $result = $policy->decideForContext($contextWithMembershipData);

        // ElectionOnly should return true (active + not deleted)
        // despite membership fields being invalid
        $this->assertTrue(
            $result,
            'ElectionOnlyPolicy must ignore membershipStatus and feesStatus fields'
        );
    }

    /**
     * Test B.1.3: ElectionOnlyPolicy does not use full-membership decision logic
     *
     * Firewall: Verify policy doesn't contain "or" fallback to simple checks
     * that would accidentally allow FullMembership users
     */
    public function test_election_only_policy_strict_mode_check(): void
    {
        $policy = new ElectionOnlyPolicy();

        // Correct mode, but both membership fields invalid
        $contextElectionOnlyValid = new EligibilityContext(
            userId: 'user-1',
            organisationId: 'org-1',
            mode: ElectionMode::ElectionOnly,
            isActive: true,
            isDeleted: false,
            membershipStatus: null,
            feesStatus: null,
        );

        // Wrong mode, but all membership fields valid (FullMembership would accept)
        $contextFullMembershipValid = new EligibilityContext(
            userId: 'user-2',
            organisationId: 'org-1',
            mode: ElectionMode::FullMembership,
            isActive: true,
            isDeleted: false,
            membershipStatus: 'active',
            feesStatus: 'paid',
        );

        $resultsElectionOnly = $policy->decideForContext($contextElectionOnlyValid);
        $resultsFullMembership = $policy->decideForContext($contextFullMembershipValid);

        $this->assertTrue($resultsElectionOnly, 'ElectionOnly with correct mode should pass');
        $this->assertFalse($resultsFullMembership, 'FullMembership mode should always fail in ElectionOnlyPolicy');
    }

    /**
     * Test B.1.4: Interface method enforces mode boundary at API level
     *
     * Firewall: Even through the interface, mode boundary must hold
     */
    public function test_interface_method_enforces_mode_boundary(): void
    {
        $policy = new ElectionOnlyPolicy();

        $electionOnlyResult = $policy->isEligible('user-1', 'org-1', ElectionMode::ElectionOnly);
        $fullMembershipResult = $policy->isEligible('user-1', 'org-1', ElectionMode::FullMembership);

        // Both should behave consistently with mode boundary
        $this->assertNotEquals(
            $electionOnlyResult,
            $fullMembershipResult,
            'Interface must differentiate between modes'
        );

        // Specifically: FullMembership should be rejected
        $this->assertFalse($fullMembershipResult);
    }
}
