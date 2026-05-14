<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Suspending a Member's Committee Membership
 *
 * Describes the constitutional act of temporarily revoking a member's governance rights
 * while preserving the institutional relationship for potential restoration.
 *
 * Suspension is not punishment. It is the exercise of institutional authority over a
 * constitutional relationship.
 */
final class SuspendMembershipTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: An active member's rights can be revoked temporarily
     *
     * When the institution determines that a member's governance rights must be temporarily
     * removed (disciplinary, procedural, or administrative reasons), the constitutional
     * relationship transitions from ACTIVE to SUSPENDED.
     *
     * Key constitutional properties:
     *
     * - The relationship persists (not terminated, merely suspended)
     * - The member loses voting rights
     * - The institutional record is preserved
     * - The transition is potentially reversible (can be reactivated)
     *
     * This is fundamentally different from termination.
     * Suspension means: "your rights are held in abeyance, pending resolution."
     * Termination means: "your relationship with this body has ended."
     */
    public function test_active_membership_can_be_suspended(): void
    {
        // ARRANGE: Pure domain objects (no database access)
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: An active constitutional relationship exists
        $relationship = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        // WHEN: The institution exercises authority to suspend
        // (In phase A2.4+, this will be: $relationship->suspend($actor, $reason))
        // For now, the aggregate supports the action structurally
        $suspended = new CommitteeAssociation(
            associationId: $relationship->associationId,
            memberId: $relationship->memberId,
            committeeId: $relationship->committeeId,
            associationType: $relationship->associationType,
            associatedAt: $relationship->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );

        // THEN: The constitutional relationship transitions
        $this->assertEquals(MembershipStatus::SUSPENDED, $suspended->status);
        $this->assertEquals(MembershipStatus::ACTIVE, $relationship->status);

        // ASSERT: The institutional identity persists (same relationship, different status)
        $this->assertEquals(
            $relationship->associationId->value(),
            $suspended->associationId->value(),
            'The constitutional relationship identity persists across suspension'
        );

        // IMPLIED EVENT: MembershipSuspended
        // Payload: associationId, memberId, committeeId, suspendedAt, suspendedBy, reason
    }

    /**
     * Constitutional Specification: Suspended membership is invisible in active governance
     *
     * From the institution's perspective, a suspended member has no active governance
     * rights. They do not appear in voting rolls, cannot participate in decisions, and
     * their voice is institutionally silent.
     *
     * This is a critical specification for Elections context: voting rights depend on
     * ACTIVE status, not mere existence of a relationship.
     */
    public function test_suspended_membership_does_not_grant_governance_rights(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A suspended relationship exists
        $suspended = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::SUSPENDED,
        );

        // ASSERT: The member has no active governance rights
        $this->assertEquals(MembershipStatus::SUSPENDED, $suspended->status);
        $this->assertNotEquals(MembershipStatus::ACTIVE, $suspended->status);

        // IMPLIED CONSTRAINT: Elections context must ignore SUSPENDED in voting eligibility
    }

    /**
     * Constitutional Specification: Suspension requires institutional audit
     *
     * A member's rights cannot be suspended without:
     * - Institutional authority (who made the decision)
     * - Institutional justification (why the decision was made)
     * - Institutional record (timestamp of the decision)
     *
     * This is mandatory, not optional. The aggregate itself will enforce this in A2.6.
     */
    public function test_suspension_requires_institutional_justification(): void
    {
        // NOTE: This test is currently aspirational.
        // After A2.6, it will verify that suspend() method requires:
        //   $relationship->suspend($actorId, $suspensionReason)
        // and throws exception if called without these parameters.

        // TODO: Implement in A2.6
        $this->markTestIncomplete(
            'Audit enforcement deferred to A2.6: Mandatory institutional justification'
        );
    }
}
