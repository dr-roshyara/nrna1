<?php

namespace Tests\Unit\Policies;

use App\Contexts\Elections\Domain\Policies\FullMembershipPolicy;
use App\Domain\Election\Enum\VoterSourceStrategy;
use PHPUnit\Framework\TestCase;
use Tests\Support\Fixtures\EligibilityFixtureBuilder;

/**
 * FullMembershipPolicyDecisionTest — Pure domain logic verification
 *
 * Responsibility: Test ONLY the decision logic of FullMembershipPolicy
 *
 * ✔ Allowed:
 * - Direct policy instantiation
 * - EligibilityContext (pure DTO)
 * - Fixture builders (domain intent, not ORM)
 * - Assert bool return value
 *
 * ❌ Forbidden:
 * - Database queries
 * - Eloquent models
 * - Laravel container
 * - Factory calls
 * - Side effects
 *
 * Mental Model: "Given minimal state, does policy decide correctly?"
 *
 * Full membership mode rules:
 *   - mode must be MembershipRegistry
 *   - membershipStatus must be 'active'
 *   - feesStatus must be 'paid' or 'exempt'
 *   - isActive must be true
 *   - isDeleted must be false
 */
class FullMembershipPolicyDecisionTest extends TestCase
{
    private FullMembershipPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new FullMembershipPolicy();
    }

    /**
     * Test D.2.1: Active member with paid fees qualifies
     */
    public function test_active_member_with_paid_fees_qualifies(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-1')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertTrue($result);
    }

    /**
     * Test D.2.2: Active member with exempt fees qualifies
     */
    public function test_active_member_with_exempt_fees_qualifies(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-2')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('active')
            ->feesStatus('exempt')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertTrue($result);
    }

    /**
     * Test D.2.3: Inactive member does not qualify
     */
    public function test_inactive_member_does_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-3')
            ->organisationId('org-1')
            ->inactive()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.2.4: Soft-deleted member does not qualify
     */
    public function test_soft_deleted_member_does_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-4')
            ->organisationId('org-1')
            ->active()
            ->deleted()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.2.5: Full membership policy rejects election-only mode
     */
    public function test_rejects_election_only_mode(): void
    {
        $context = EligibilityFixtureBuilder::electionOnly()
            ->userId('user-5')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.2.6: Member with unpaid fees does not qualify
     */
    public function test_unpaid_fees_do_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-6')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('active')
            ->feesStatus('unpaid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.2.7: Suspended membership does not qualify
     */
    public function test_suspended_membership_does_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-7')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('suspended')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.2.8: Both active AND not-deleted are required (conjunction)
     */
    public function test_requires_both_active_and_not_deleted(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->inactive()
            ->deleted()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $this->assertFalse($this->policy->decideForContext($context));
    }

    /**
     * Test D.2.9: Interface method is a placeholder — returns true regardless of mode
     *
     * Per Phase A design: infrastructure queries DB, builds EligibilityContext,
     * then calls decideForContext(). The isEligible() stub satisfies interface only.
     */
    public function test_interface_method_is_placeholder_returns_true(): void
    {
        $result = $this->policy->isEligible('user-1', 'org-1', VoterSourceStrategy::MembershipRegistry);

        $this->assertTrue($result);
    }
}
