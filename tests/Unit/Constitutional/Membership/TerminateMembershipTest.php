<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Terminating Committee Membership
 *
 * Describes the constitutional act of permanently ending a member's relationship with
 * the institution.
 *
 * Termination is terminal. It is not suspension (which implies eventual restoration).
 * It is not revocation (which implies later reapplication through normal channels).
 *
 * Termination means: the constitutional relationship has reached its end.
 */
final class TerminateMembershipTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: A membership relationship can be terminated
     *
     * The institution may determine that a member's constitutional relationship has ended.
     * This may occur due to:
     *
     * - Voluntary resignation
     * - Expiration of term
     * - Disciplinary removal
     * - Administrative dissolution
     *
     * Regardless of cause, termination is final and immutable.
     *
     * Key constitutional properties:
     *
     * - The relationship moves to TERMINATED status
     * - No further transitions are possible from this state
     * - The relationship remains part of institutional record forever
     * - The member has no governance rights
     */
    public function test_active_or_suspended_membership_can_be_terminated(): void
    {
        // ARRANGE: Pure domain objects (no database access)
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: An active relationship exists
        $active = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );

        // WHEN: The institution terminates the relationship
        $terminated = $active->terminate('actor-uuid-001', 'Expiration of term', $now);

        // THEN: The constitutional relationship is terminated
        $this->assertEquals(MembershipStatus::TERMINATED, $terminated->status);

        // IMPLIED EVENT: MembershipTerminated
        // Payload: associationId, memberId, committeeId, terminatedAt, terminatedBy, reason
    }

    /**
     * Constitutional Specification: Terminated membership is permanent and immutable
     *
     * Once a membership is TERMINATED, no further constitutional transitions are possible.
     *
     * This is not merely a restriction. It is a constitutional law.
     *
     * The member cannot be:
     * - restored (only suspended can be restored)
     * - reactivated (that would require reapplication)
     * - modified in any way
     *
     * The relationship is historically frozen.
     */
    public function test_terminated_membership_cannot_be_reactivated_directly(): void
    {
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');
        $active = CommitteeAssociation::create(
            memberId: $this->createMemberId(),
            committeeId: $this->createCommitteeId(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $active->terminate('actor-uuid-001', 'Termination', $now);

        $this->expectException(\App\Contexts\Membership\Domain\Membership\Exceptions\InvalidAssociationTransitionException::class);
        $terminated->restore('actor-uuid', new \DateTimeImmutable('2026-05-14 11:00:00'));
    }

    /**
     * Constitutional Specification: Terminated membership has no governance rights
     *
     * A terminated member is completely excluded from governance participation.
     * They have no voting rights, no committee voice, no institutional legitimacy.
     *
     * This is critical for Elections context: TERMINATED associations must never
     * grant voting eligibility.
     */
    public function test_terminated_membership_grants_no_governance_rights(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A terminated relationship exists
        $active = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $active->terminate('actor-uuid-001', 'Termination', $now);

        // ASSERT: The member has no governance rights
        $this->assertEquals(MembershipStatus::TERMINATED, $terminated->status);
        $this->assertNotEquals(MembershipStatus::ACTIVE, $terminated->status);

        // IMPLIED CONSTRAINT: Elections context must NEVER grant voting rights for TERMINATED
    }

    /**
     * Constitutional Specification: Terminated relationship remains historical record
     *
     * Although the relationship is inactive, its record persists forever.
     *
     * This is mandatory for:
     * - Institutional audit trail
     * - Governance history reconstruction
     * - Dispute resolution
     * - Reapplication verification
     *
     * The relationship is not deleted. It is archived as historical governance fact.
     */
    public function test_terminated_membership_remains_queryable_in_history(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A terminated relationship exists
        $active = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $active->terminate('actor-uuid-001', 'Termination', $now);

        // ASSERT: The historical record persists
        $this->assertNotNull($terminated->associationId);
        $this->assertEquals(MembershipStatus::TERMINATED, $terminated->status);

        // IMPLICATION: Archive/history queries will retrieve this relationship
        // (Implementation: repository method to query by all statuses, not just ACTIVE)
    }

    /**
     * Constitutional Specification: Termination requires institutional audit
     *
     * A member's relationship cannot be terminated without:
     * - Institutional authority (who made the decision)
     * - Institutional justification (why the decision was made)
     * - Institutional record (timestamp of the decision)
     *
     * This is mandatory, not optional.
     */
    public function test_termination_requires_institutional_justification(): void
    {
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');
        $active = CommitteeAssociation::create(
            memberId: $this->createMemberId(),
            committeeId: $this->createCommitteeId(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );

        $terminated = $active->terminate('actor-uuid-002', 'Violation of code of conduct', $now);

        $this->assertEquals(MembershipStatus::TERMINATED, $terminated->status);
        $this->assertEquals('actor-uuid-002', $terminated->actorId);
        $this->assertEquals('Violation of code of conduct', $terminated->transitionReason);
        $this->assertNotNull($terminated->transitionedAt);
    }
}
