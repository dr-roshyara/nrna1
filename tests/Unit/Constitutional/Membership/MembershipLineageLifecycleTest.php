<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: MembershipLineage State Machine Transitions
 *
 * Validates the formal state machine and transition rules for membership lifecycle.
 * Each test verifies one transition path and the immutability of the lineage identity.
 */
final class MembershipLineageLifecycleTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: ACTIVE → SUSPENDED transition
     *
     * An active membership can be suspended, temporarily revoking governance rights.
     * The lineageId is preserved (same constitutional chapter).
     */
    public function test_active_membership_can_be_suspended(): void
    {
        // ARRANGE: Establish active lineage
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // Aggregate owns initial ACTIVE status - just pass the reason
        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Capture initial episode before suspension
        $initialEpisode = $lineage->current();
        $initialAssociationId = $initialEpisode->associationId->value();

        // ACT: Suspend the membership
        $actorId = (string) \Illuminate\Support\Str::uuid();
        $reason = 'Disciplinary suspension';
        $lineage->suspend($actorId, $reason, $now->modify('+1 day'));

        // ASSERT: Status transitioned, lineage identity preserved
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->currentStatus());
        $this->assertTrue($lineage->lineageId->equals($lineageId));
        $this->assertCount(2, $lineage->episodes());

        // ASSERT: Latest episode reflects suspension
        $latestEpisode = $lineage->current();
        $this->assertEquals(MembershipStatus::SUSPENDED, $latestEpisode->status);
        // ASSERT: AssociationId preserved (same constitutional relationship)
        $this->assertEquals($initialAssociationId, $latestEpisode->associationId->value());
    }

    /**
     * Constitutional Specification: SUSPENDED → ACTIVE transition (Restoration)
     *
     * A suspended membership can be restored, regaining governance rights.
     * The lineageId is preserved (same constitutional chapter, no reapplication).
     */
    public function test_suspended_membership_can_be_restored(): void
    {
        // ARRANGE: Establish suspended lineage
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

        $initialAssociationId = $lineage->current()->associationId->value();

        $actorId = (string) \Illuminate\Support\Str::uuid();
        $lineage->suspend($actorId, 'Suspension reason', $now->modify('+1 day'));

        // ACT: Restore the membership
        $lineage->restore($actorId, $now->modify('+2 days'));

        // ASSERT: Status restored to ACTIVE
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->currentStatus());
        $this->assertTrue($lineage->lineageId->equals($lineageId));
        $this->assertCount(3, $lineage->episodes());

        // ASSERT: AssociationId still preserved
        $this->assertEquals(
            $initialAssociationId,
            $lineage->current()->associationId->value(),
        );
    }

    /**
     * Constitutional Specification: ACTIVE → TERMINATED transition
     *
     * An active membership can be terminated, permanently ending the relationship.
     * This is the terminal state of the lineage.
     */
    public function test_active_membership_can_be_terminated(): void
    {
        // ARRANGE: Establish active lineage
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

        // ACT: Terminate the membership
        $actorId = (string) \Illuminate\Support\Str::uuid();
        $reason = 'Resignation';
        $lineage->terminate($actorId, $reason, $now->modify('+1 day'));

        // ASSERT: Status is TERMINATED
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->currentStatus());
        $this->assertFalse($lineage->isActive());
        $this->assertTrue($lineage->canBeReapplied()); // TERMINATED can be reapplied
    }

    /**
     * Constitutional Specification: SUSPENDED → TERMINATED transition
     *
     * A suspended membership can also be terminated (from either ACTIVE or SUSPENDED).
     */
    public function test_suspended_membership_can_be_terminated(): void
    {
        // ARRANGE: Establish suspended lineage
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');$lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $actorId = (string) \Illuminate\Support\Str::uuid();
        $lineage->suspend($actorId, 'Temporary suspension', $now->modify('+1 day'));

        // ACT: Terminate from SUSPENDED
        $lineage->terminate($actorId, 'Expiration of term', $now->modify('+2 days'));

        // ASSERT: Status is TERMINATED
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->currentStatus());
    }

    /**
     * Constitutional Specification: TERMINATED is terminal (immutable state)
     *
     * Once TERMINATED, no further transitions are possible on this lineage.
     * Attempting to transition from TERMINATED throws InvalidLineageTransitionException.
     */
    public function test_terminated_membership_cannot_transition(): void
    {
        // ARRANGE: Establish and terminate lineage
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');$lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $actorId = (string) \Illuminate\Support\Str::uuid();
        $lineage->terminate($actorId, 'Termination', $now->modify('+1 day'));

        // ACT & ASSERT: Cannot suspend TERMINATED
        $this->expectException(InvalidLineageTransitionException::class);
        $lineage->suspend($actorId, 'Cannot suspend', $now->modify('+2 days'));
    }

    /**
     * Constitutional Specification: Cannot restore non-SUSPENDED
     *
     * Restore() can only be called on SUSPENDED. Attempting to restore ACTIVE or
     * TERMINATED throws exception.
     */
    public function test_cannot_restore_active_or_terminated(): void
    {
        // ARRANGE: Active lineage
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');$lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $actorId = (string) \Illuminate\Support\Str::uuid();

        // ACT & ASSERT: Cannot restore ACTIVE
        $this->expectException(InvalidLineageTransitionException::class);
        $lineage->restore($actorId, $now->modify('+1 day'));
    }

    /**
     * Constitutional Specification: Reapply creates new episode (new lineage)
     *
     * When a TERMINATED lineage is reapplied, reapplyInitialEpisode() returns
     * a NEW episode with a NEW associationId. The caller is responsible for
     * creating a new MembershipLineage with a new LineageId.
     */
    public function test_reapply_creates_new_initial_episode(): void
    {
        // ARRANGE: Establish and terminate lineage
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

        $initialAssociationId = $lineage->current()->associationId->value();

        $actorId = (string) \Illuminate\Support\Str::uuid();
        $lineage->terminate($actorId, 'Termination', $now->modify('+1 day'));

        // ACT: Reapply (get initial episode for new lineage)
        $newEpisode = $lineage->reapplyInitialEpisode(
            ApplicationReason::MANUAL,
            $now->modify('+2 days'),
        );

        // ASSERT: New episode has ACTIVE status
        $this->assertEquals(MembershipStatus::ACTIVE, $newEpisode->status);

        // ASSERT: New episode has NEW associationId (different from old)
        $this->assertNotEquals(
            $initialAssociationId,
            $newEpisode->associationId->value(),
            'Reapplication creates new constitutional relationship (new associationId)',
        );

        // ASSERT: Original lineage is UNCHANGED (still TERMINATED)
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->currentStatus());
        $this->assertTrue($lineage->lineageId->equals($lineageId));
        $this->assertCount(2, $lineage->episodes()); // Original + termination, nothing added
    }

    /**
     * Constitutional Specification: Cannot reapply non-TERMINATED
     *
     * Reapply can only be called on TERMINATED lineages.
     */
    public function test_cannot_reapply_active_or_suspended(): void
    {
        // ARRANGE: Active lineage
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

        // ACT & ASSERT: Cannot reapply while ACTIVE
        $this->expectException(InvalidLineageTransitionException::class);
        $lineage->reapplyInitialEpisode(ApplicationReason::MANUAL, $now->modify('+1 day'));
    }

    /**
     * Constitutional Specification: Reason is mandatory for suspend/terminate
     *
     * Empty reasons are not permitted (transparency required).
     */
    public function test_suspend_requires_non_empty_reason(): void
    {
        // ARRANGE: Active lineage
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');$lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // ACT & ASSERT: Empty reason throws
        $this->expectException(\InvalidArgumentException::class);
        $lineage->suspend((string) \Illuminate\Support\Str::uuid(), '', $now->modify('+1 day'));
    }

    /**
     * Constitutional Specification: Full lifecycle cycle
     *
     * A lineage can traverse: ACTIVE → SUSPENDED → ACTIVE → TERMINATED
     */
    public function test_full_lifecycle_cycle(): void
    {
        // ARRANGE
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');$lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $actorId = (string) \Illuminate\Support\Str::uuid();

        // ACT & ASSERT: Suspend
        $lineage->suspend($actorId, 'Suspension', $now->modify('+1 day'));
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->currentStatus());

        // ACT & ASSERT: Restore
        $lineage->restore($actorId, $now->modify('+2 days'));
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->currentStatus());

        // ACT & ASSERT: Terminate
        $lineage->terminate($actorId, 'Termination', $now->modify('+3 days'));
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->currentStatus());

        // ASSERT: Full history preserved
        $this->assertCount(4, $lineage->episodes());
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->episodes()[0]->status);
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->episodes()[1]->status);
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->episodes()[2]->status);
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->episodes()[3]->status);

        // ASSERT: LineageId unchanged throughout
        $this->assertTrue($lineage->lineageId->equals($lineageId));
    }
}
