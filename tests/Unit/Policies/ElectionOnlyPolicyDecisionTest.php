<?php

namespace Tests\Unit\Policies;

use App\Contexts\Elections\Domain\Policies\ElectionOnlyPolicy;
use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\ElectionMode;
use PHPUnit\Framework\TestCase;
use Tests\Support\Fixtures\EligibilityFixtureBuilder;

/**
 * ElectionOnlyPolicyDecisionTest — Pure domain logic verification
 *
 * Responsibility: Test ONLY the decision logic of ElectionOnlyPolicy
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
 * This test layer has ZERO dependencies on persistence.
 */
class ElectionOnlyPolicyDecisionTest extends TestCase
{
    private ElectionOnlyPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ElectionOnlyPolicy();
    }

    /**
     * Test D.1.1: Active voter in election-only mode qualifies
     */
    public function test_active_voter_qualifies(): void
    {
        $context = EligibilityFixtureBuilder::electionOnly()
            ->userId('user-1')
            ->organisationId('org-1')
            ->active()
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertTrue($result);
    }

    /**
     * Test D.1.2: Inactive voter does not qualify
     */
    public function test_inactive_voter_does_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::electionOnly()
            ->userId('user-2')
            ->organisationId('org-1')
            ->inactive()
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.1.3: Soft-deleted voter does not qualify
     */
    public function test_soft_deleted_voter_does_not_qualify(): void
    {
        $context = EligibilityFixtureBuilder::electionOnly()
            ->userId('user-3')
            ->organisationId('org-1')
            ->active()
            ->deleted()
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.1.4: Election-only policy rejects full-membership mode
     */
    public function test_rejects_full_membership_mode(): void
    {
        $context = EligibilityFixtureBuilder::fullMembership()
            ->userId('user-4')
            ->organisationId('org-1')
            ->active()
            ->membershipStatus('active')
            ->feesStatus('paid')
            ->build();

        $result = $this->policy->decideForContext($context);

        $this->assertFalse($result);
    }

    /**
     * Test D.1.5: Both active AND not-deleted required
     */
    public function test_requires_both_active_and_not_deleted(): void
    {
        $inactive = EligibilityFixtureBuilder::electionOnly()
            ->inactive()
            ->deleted()
            ->build();

        $this->assertFalse($this->policy->decideForContext($inactive));
    }

    /**
     * Test D.1.6: Interface method respects mode boundary
     */
    public function test_interface_method_rejects_wrong_mode(): void
    {
        $result = $this->policy->isEligible('user-1', 'org-1', ElectionMode::FullMembership);

        $this->assertFalse($result);
    }

    /**
     * Test D.1.7: Interface method accepts election-only mode (placeholder behavior)
     */
    public function test_interface_method_accepts_election_only_mode(): void
    {
        $result = $this->policy->isEligible('user-1', 'org-1', ElectionMode::ElectionOnly);

        $this->assertTrue($result);
    }
}
