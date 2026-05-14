<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: MembershipLineage Aggregate Root
 *
 * Describes the constitutional lifecycle container that owns all episodes
 * (CommitteeAssociation state snapshots) and will eventually own transition rules.
 *
 * PHASE A2.2 (Current):
 * - Lineage aggregates episodes
 * - Lineage preserves identity across lifecycle
 * - Lineage queries current status
 *
 * PHASE A2.3+ (Next):
 * - Lineage will own suspend(), restore(), terminate() methods
 * - Lineage will enforce state machine rules
 */
final class MembershipLineageTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: A lineage is established with initial episode
     *
     * When a member's application is approved, a new lineage is created with
     * a single initial episode (the ACTIVE membership).
     */
    public function test_lineage_is_established_with_initial_episode(): void
    {
        // ARRANGE: Pure domain objects
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // ACT: Establish lineage (aggregate creates initial ACTIVE episode)
        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // ASSERT: Lineage has identity
        $this->assertTrue($lineage->lineageId->equals($lineageId));
        $this->assertTrue($lineage->memberId->equals($member));
        $this->assertTrue($lineage->committeeId->equals($committee));
        $this->assertTrue($lineage->tenantId->equals($tenant));

        // ASSERT: Lineage contains initial episode
        $this->assertCount(1, $lineage->episodes());
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->currentStatus());
    }

    /**
     * Constitutional Specification: Lineage identity persists across lifecycle
     *
     * The lineageId remains constant even as the member transitions through
     * ACTIVE → SUSPENDED → ACTIVE → TERMINATED states.
     *
     * This is the constitutional continuity anchor.
     */
    public function test_lineage_identity_persists_across_lifecycle(): void
    {
        // ARRANGE: Pure domain objects
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // Establish lineage (aggregate creates initial ACTIVE episode)
        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Capture initial episode to reuse associationId
        $initialEpisode = $lineage->current();

        // Add suspension episode
        $suspended = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );
        $lineage->addEpisode($suspended);

        // Add restoration episode
        $restored = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::ACTIVE,
        );
        $lineage->addEpisode($restored);

        // Add termination episode
        $terminated = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::TERMINATED,
        );
        $lineage->addEpisode($terminated);

        // ASSERT: Lineage identity unchanged throughout lifecycle
        $this->assertTrue($lineage->lineageId->equals($lineageId));

        // ASSERT: All episodes recorded in order
        $this->assertCount(4, $lineage->episodes());
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->episodes()[0]->status);
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->episodes()[1]->status);
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->episodes()[2]->status);
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->episodes()[3]->status);

        // ASSERT: Current status reflects latest episode
        $this->assertEquals(MembershipStatus::TERMINATED, $lineage->currentStatus());
    }

    /**
     * Constitutional Specification: Lineage can be reconstituted from persistence
     *
     * When loading from database, lineage is fully restored with all episodes,
     * preserving complete constitutional history.
     */
    public function test_lineage_can_be_reconstituted_from_episodes(): void
    {
        // ARRANGE: Create episodes (as would be loaded from database)
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $active = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        $suspended = new CommitteeAssociation(
            associationId: $active->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $active->associationType,
            associatedAt: $active->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );

        $episodes = [$active, $suspended];

        // ACT: Reconstitute from persistence
        $lineage = MembershipLineage::reconstitute(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            episodes: $episodes,
        );

        // ASSERT: Lineage fully reconstituted
        $this->assertTrue($lineage->lineageId->equals($lineageId));
        $this->assertCount(2, $lineage->episodes());
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->currentStatus());
    }

    /**
     * Constitutional Specification: Lineage tracks current status
     *
     * The current status always reflects the most recent episode's status.
     */
    public function test_lineage_current_status_reflects_latest_episode(): void
    {
        // ARRANGE
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

        $initialEpisode = $lineage->current();

        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->currentStatus());

        // Add suspension
        $suspended = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );
        $lineage->addEpisode($suspended);

        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->currentStatus());
    }

    /**
     * Constitutional Specification: Only terminated lineages can be reapplied
     *
     * Lineages in ACTIVE or SUSPENDED status cannot be reapplied. Only TERMINATED
     * lineages open the possibility of a new application (which creates a NEW lineage).
     */
    public function test_only_terminated_lineage_can_be_reapplied(): void
    {
        // ARRANGE
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // Active lineage
        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $initialEpisode = $lineage->current();

        // ASSERT: Cannot reapply while ACTIVE
        $this->assertFalse($lineage->canBeReapplied());
        $this->assertTrue($lineage->isActive());

        // Add suspension
        $suspended = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );
        $lineage->addEpisode($suspended);

        // ASSERT: Cannot reapply while SUSPENDED
        $this->assertFalse($lineage->canBeReapplied());
        $this->assertFalse($lineage->isActive());
        $this->assertTrue($lineage->isSuspended());

        // Add termination
        $terminated = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::TERMINATED,
        );
        $lineage->addEpisode($terminated);

        // ASSERT: CAN reapply only after TERMINATED
        $this->assertTrue($lineage->canBeReapplied());
        $this->assertFalse($lineage->isActive());
    }

    /**
     * Constitutional Specification: Episodes are immutable historical record
     *
     * All episodes in a lineage form an immutable, queryable history.
     * No episode can be modified or removed.
     */
    public function test_lineage_episodes_form_immutable_history(): void
    {
        // ARRANGE
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

        $initialEpisode = $lineage->current();

        // Get episodes reference
        $episodesSnapshot = $lineage->episodes();
        $originalCount = count($episodesSnapshot);

        // Add new episode
        $suspended = new CommitteeAssociation(
            associationId: $initialEpisode->associationId,
            memberId: $member,
            committeeId: $committee,
            associationType: $initialEpisode->associationType,
            associatedAt: $initialEpisode->associatedAt,
            status: MembershipStatus::SUSPENDED,
        );
        $lineage->addEpisode($suspended);

        // ASSERT: Snapshot unchanged, lineage updated
        $this->assertCount($originalCount, $episodesSnapshot);
        $this->assertCount($originalCount + 1, $lineage->episodes());

        // ASSERT: Episodes are queryable in order
        $this->assertEquals(MembershipStatus::ACTIVE, $lineage->episodes()[0]->status);
        $this->assertEquals(MembershipStatus::SUSPENDED, $lineage->episodes()[1]->status);
    }
}
