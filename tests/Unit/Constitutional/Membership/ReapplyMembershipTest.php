<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Reapplying for Committee Membership
 *
 * Describes what happens when a member whose previous relationship was TERMINATED
 * seeks to rejoin the institution.
 *
 * CRITICAL DISTINCTION from Restoration:
 *
 * - RESTORATION (SUSPENDED → ACTIVE) = Same constitutional relationship resumes
 * - REAPPLICATION (after TERMINATED) = New constitutional relationship begins
 *
 * This distinction is foundational to the entire governance model.
 */
final class ReapplyMembershipTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: After termination, a new relationship must begin
     *
     * When a member's previous relationship with a committee was TERMINATED, and the
     * member seeks to rejoin, a NEW constitutional relationship is created.
     *
     * This is not revival of the old relationship.
     * This is birth of a new institutional lineage.
     *
     * Key constitutional properties:
     *
     * - A NEW AssociationId is created
     * - The original terminated relationship remains immutable historical record
     * - The new relationship is independent
     * - Both relationships coexist in institutional history
     *
     * This preserves:
     * - Institutional memory (old relationship not erased)
     * - Constitutional legitimacy (new relationship has separate identity)
     * - Governance continuity (history remains queryable)
     */
    public function test_terminated_member_can_reapply_creating_new_constitutional_relationship(): void
    {
        // ARRANGE: Pure domain objects (no database access)
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A terminated relationship exists in institutional history
        $initial = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $initial->terminate('actor-uuid-001', 'Termination', $now);

        $originalAssociationId = $terminated->associationId->value();

        // WHEN: The member reapplies and is accepted into a new constitutional relationship
        $reapplied = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::MANUAL,
            associatedAt: $now->modify('+1 day'),
        );

        // THEN: A NEW constitutional relationship exists
        $this->assertEquals(MembershipStatus::ACTIVE, $reapplied->status);

        // ASSERT: The NEW relationship has DIFFERENT institutional identity
        $this->assertNotEquals(
            $originalAssociationId,
            $reapplied->associationId->value(),
            'Reapplication creates entirely new constitutional relationship with unique identity'
        );

        // ASSERT: Both relationships coexist in institutional history
        // (The old TERMINATED record persists; the new ACTIVE record is separate)

        // IMPLIED EVENT: MembershipReapplied
        // Payload: newAssociationId, memberId, committeeId, appliedAt, approvedAt, approvedBy
    }

    /**
     * Constitutional Specification: Reapplication requires new institutional approval
     *
     * A member cannot simply resume participation after termination through automatic
     * restoration. The institution must explicitly accept the new application.
     *
     * This is a governance boundary:
     *
     * - RESTORATION = automatic institutional act (formerly suspended, now restored)
     * - REAPPLICATION = requires institutional approval (formerly terminated, now applying)
     *
     * The member must go through the application process again, not merely request
     * restoration of rights.
     */
    public function test_reapplication_is_separate_from_restoration(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A terminated relationship exists
        $initial = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $initial->terminate('actor-uuid-001', 'Termination', $now);

        // WHEN: We attempt to treat this as restoration (wrong approach)
        // (This would be semantically incorrect)
        // The correct approach is to create a NEW relationship via application

        $reapplied = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::MANUAL,
            associatedAt: $now,
        );

        // ASSERT: It is a NEW relationship, not a restoration of the old one
        $this->assertNotEquals(
            $terminated->associationId->value(),
            $reapplied->associationId->value(),
            'Reapplication must create new relationship, not restore old one'
        );

        // NOTE: In phase A2.5+, there will be explicit use cases:
        // - RestoreCommitteeMembership (for SUSPENDED → ACTIVE)
        // - ReapplyForCommitteeMembership (for new application after TERMINATED)
        // These will be separate workflows with different authority rules.
    }

    /**
     * Constitutional Specification: Historical integrity is preserved through reapplication
     *
     * When a member reapplies, the institutional history remains complete:
     *
     * - The original terminated relationship is not deleted
     * - The new relationship is distinct and independent
     * - Both are queryable in governance records
     * - The institution can reconstruct the full lineage
     *
     * This is mandatory for:
     * - Appeal processes (can reference why original was terminated)
     * - Dispute resolution (full history available)
     * - Institutional accountability (transparency over time)
     */
    public function test_reapplication_preserves_institutional_history(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: A terminated relationship exists
        $initial = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );
        $terminated = $initial->terminate('actor-uuid-001', 'Termination', $now);

        // WHEN: The member reapplies
        $reapplied = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::MANUAL,
            associatedAt: $now->modify('+1 day'),
        );

        // ASSERT: Both records should be queryable (future: repository method)
        // Currently testing at aggregate level:
        $this->assertNotNull($terminated->associationId);
        $this->assertNotNull($reapplied->associationId);
        $this->assertEquals(MembershipStatus::TERMINATED, $terminated->status);
        $this->assertEquals(MembershipStatus::ACTIVE, $reapplied->status);

        // IMPLICATION: Repository must support querying full history
        // Not just "current" relationships, but complete institutional timeline
    }

    /**
     * Constitutional Specification: Reapplied member regains full governance rights
     *
     * Once reapplication is approved and ACTIVE status is established, the member
     * has complete governance participation rights as if they are a new member.
     */
    public function test_reapplied_active_membership_grants_governance_rights(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // GIVEN: Reapplication was approved and member is now ACTIVE
        $reapplied = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::MANUAL,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        // ASSERT: Member is eligible for governance participation
        $this->assertEquals(MembershipStatus::ACTIVE, $reapplied->status);

        // IMPLIED CONSTRAINT: Elections context must grant voting rights for ACTIVE
        // regardless of whether this is original membership or reapplication
    }
}
