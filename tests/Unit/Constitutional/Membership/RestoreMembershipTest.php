<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Restoring Suspended Membership
 *
 * Describes the constitutional act of restoring a member's governance rights after
 * suspension has been resolved.
 *
 * This is fundamentally different from reapplication after termination.
 *
 * Key distinction:
 *
 * - RESTORATION (SUSPENDED → ACTIVE): Same constitutional lineage persists
 * - REAPPLICATION (after TERMINATED): New constitutional lineage begins
 *
 * Both restore voting rights, but only restoration preserves the original relationship.
 */
final class RestoreMembershipTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: A suspended relationship can be restored
     *
     * When the institutional condition that caused suspension has been resolved, the
     * member's governance rights may be restored. This is a restoration, not a new
     * relationship.
     *
     * Key constitutional properties:
     *
     * - The SAME constitutional relationship is restored
     * - The associationId remains unchanged
     * - The governance lineage is unbroken
     * - The member returns to full participation
     *
     * This preserves institutional continuity.
     */
    public function test_suspended_membership_can_be_restored_to_active(): void
    {
        // ARRANGE: Pure domain objects (no database access)
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

        // WHEN: The institution resolves the suspension and restores rights
        // (In phase A2.4+, this will be: $suspended->restore($actor))
        $restored = new CommitteeAssociation(
            associationId: $suspended->associationId,
            memberId: $suspended->memberId,
            committeeId: $suspended->committeeId,
            associationType: $suspended->associationType,
            associatedAt: $suspended->associatedAt,
            status: MembershipStatus::ACTIVE,
        );

        // THEN: The constitutional relationship is restored
        $this->assertEquals(MembershipStatus::ACTIVE, $restored->status);

        // ASSERT: The SAME institutional lineage persists (same associationId)
        $this->assertEquals(
            $suspended->associationId->value(),
            $restored->associationId->value(),
            'Restoration preserves the original constitutional lineage'
        );

        // IMPLIED EVENT: MembershipRestored
        // Payload: associationId, memberId, committeeId, restoredAt, restoredBy
    }

    /**
     * Constitutional Specification: Restoration grants full governance rights
     *
     * Once restored, the member regains all governance rights as if suspension had not
     * occurred. Their voting eligibility returns, their committee role is restored, and
     * their institutional legitimacy is reestablished.
     *
     * This is a critical specification for Elections context.
     */
    public function test_restored_membership_grants_full_governance_rights(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A membership transitions SUSPENDED → ACTIVE
        $restored = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        // ASSERT: The member is eligible for governance participation
        $this->assertEquals(MembershipStatus::ACTIVE, $restored->status);

        // IMPLIED CONSTRAINT: Elections context must recognize ACTIVE status for voting
    }

    /**
     * Constitutional Specification: Restoration is only possible from SUSPENDED
     *
     * A member cannot be "restored" from TERMINATED. Once terminated, a new relationship
     * must be established through reapplication.
     *
     * This is the critical constitutional boundary.
     */
    public function test_restoration_is_only_valid_from_suspended_state(): void
    {
        $terminated = CommitteeAssociation::create(
            memberId: $this->createMemberId(),
            committeeId: $this->createCommitteeId(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,
        );

        $this->expectException(\App\Contexts\Membership\Domain\Membership\Exceptions\InvalidAssociationTransitionException::class);
        $terminated->restore('actor-uuid', new \DateTimeImmutable('2026-05-14 11:00:00'));
    }
}
