<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Voting\ReasonCode;
use App\Contexts\Membership\Domain\Voting\VotingEligibilityPolicy;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Voting Eligibility Policy
 *
 * Verifies that VotingEligibilityPolicy is the ONLY place where voting eligibility
 * is determined. Policies evaluate MembershipLineage facts to produce eligibility decisions.
 *
 * CRITICAL: All voting eligibility rules live in ONE place (single source of truth).
 * Elections context ONLY calls this policy through the cross-context gateway.
 */
final class VotingEligibilityPolicyTest extends PureDomainTestCase
{
    private VotingEligibilityPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new VotingEligibilityPolicy();
    }

    /**
     * Constitutional Specification: Active members can vote
     *
     * CRITICAL: A lineage in ACTIVE status grants voting eligibility.
     */
    public function test_active_member_is_eligible_for_voting(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $result = $this->policy->evaluate($lineage);

        $this->assertTrue($result->canVote);
        $this->assertSame(ReasonCode::NONE, $result->reasonCode);
    }

    /**
     * Constitutional Specification: Suspended members cannot vote
     *
     * CRITICAL: A lineage in SUSPENDED status denies voting eligibility
     * with reason code SUSPENDED.
     */
    public function test_suspended_member_is_ineligible_with_suspended_reason(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension reason', $now->modify('+1 day'));

        $result = $this->policy->evaluate($lineage);

        $this->assertFalse($result->canVote);
        $this->assertSame(ReasonCode::SUSPENDED, $result->reasonCode);
    }

    /**
     * Constitutional Specification: Terminated members cannot vote
     *
     * CRITICAL: A lineage in TERMINATED status denies voting eligibility
     * with reason code TERMINATED.
     */
    public function test_terminated_member_is_ineligible_with_terminated_reason(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->terminate('actor-1', 'Termination reason', $now->modify('+1 day'));

        $result = $this->policy->evaluate($lineage);

        $this->assertFalse($result->canVote);
        $this->assertSame(ReasonCode::TERMINATED, $result->reasonCode);
    }

    /**
     * Constitutional Specification: No membership denies voting
     *
     * CRITICAL: When lineage is null (no membership relationship exists),
     * policy returns ineligible with reason code NO_MEMBERSHIP.
     */
    public function test_null_lineage_is_ineligible_with_no_membership_reason(): void
    {
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $result = $this->policy->evaluate(null);

        $this->assertFalse($result->canVote);
        $this->assertSame(ReasonCode::NO_MEMBERSHIP, $result->reasonCode);
    }

    /**
     * Constitutional Specification: Restored members regain voting eligibility
     *
     * CRITICAL: After SUSPENDED → ACTIVE transition,
     * member regains eligibility immediately.
     */
    public function test_restored_member_regains_voting_eligibility(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension', $now->modify('+1 day'));
        $lineage->restore('actor-1', $now->modify('+2 days'));

        $result = $this->policy->evaluate($lineage);

        $this->assertTrue($result->canVote);
        $this->assertSame(ReasonCode::NONE, $result->reasonCode);
    }

    /**
     * Constitutional Specification: Policy is deterministic across full lifecycle
     *
     * CRITICAL: The policy produces consistent decisions throughout the full
     * lifecycle: ACTIVE → SUSPENDED → ACTIVE → TERMINATED.
     * Each transition produces expected eligibility immediately.
     */
    public function test_policy_is_deterministic_full_lifecycle(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // PHASE 1: ACTIVE (establishment)
        $result1 = $this->policy->evaluate($lineage);
        $this->assertTrue($result1->canVote, 'ACTIVE: should be eligible');
        $this->assertSame(ReasonCode::NONE, $result1->reasonCode);

        // PHASE 2: SUSPENDED
        $lineage->suspend('actor-1', 'Suspension', $now->modify('+1 day'));
        $result2 = $this->policy->evaluate($lineage);
        $this->assertFalse($result2->canVote, 'SUSPENDED: should be ineligible');
        $this->assertSame(ReasonCode::SUSPENDED, $result2->reasonCode);

        // PHASE 3: ACTIVE (restored)
        $lineage->restore('actor-1', $now->modify('+2 days'));
        $result3 = $this->policy->evaluate($lineage);
        $this->assertTrue($result3->canVote, 'ACTIVE after restore: should be eligible');
        $this->assertSame(ReasonCode::NONE, $result3->reasonCode);

        // PHASE 4: TERMINATED
        $lineage->terminate('actor-1', 'Termination', $now->modify('+3 days'));
        $result4 = $this->policy->evaluate($lineage);
        $this->assertFalse($result4->canVote, 'TERMINATED: should be ineligible');
        $this->assertSame(ReasonCode::TERMINATED, $result4->reasonCode);
    }
}
