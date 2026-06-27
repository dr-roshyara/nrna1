<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\AggregateConsistency;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipLineageException;
use PHPUnit\Framework\TestCase;

final class MembershipLineageConsistencyTest extends TestCase
{
    /** @test */
    public function it_prevents_illegal_status_transition_sequences(): void
    {
        // ATTACK VECTOR: Create lineage with ACTIVE → TERMINATED → ACTIVE sequence
        // THREAT: State machine logic violated - impossible sequences stored as fact
        // CURRENT: reconstitute() accepts any episode sequence
        // CONSTITUTIONAL GUARANTEE: "Only valid state transitions can exist historically"

        $this->expectException(InvalidMembershipLineageException::class);
        $this->expectExceptionMessage('Invalid state transition');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        // Create impossible sequence
        $ep1 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        $ep2 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,
            actorId: 'actor-uuid-001',
            transitionReason: 'Terminated',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $ep3 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,  // ← ATTACK: Cannot return to ACTIVE from TERMINATED
            actorId: 'actor-uuid-002',
            transitionReason: 'Restored',
            transitionedAt: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2, $ep3],  // ← ATTACK: Illegal sequence
        );
    }

    /** @test */
    public function it_prevents_duplicate_terminal_status_in_episode_chain(): void
    {
        // ATTACK VECTOR: Two TERMINATED episodes in history
        // THREAT: Terminal state violated - membership cannot be terminated twice
        // CURRENT: reconstitute() doesn't check for duplicate TERMINATED episodes
        // CONSTITUTIONAL GUARANTEE: "TERMINATED status can appear exactly once"

        $this->expectException(InvalidMembershipLineageException::class);
        $this->expectExceptionMessage('Invalid state transition');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        // Create sequence with two terminations
        $ep1 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        $ep2 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,
            actorId: 'actor-uuid-001',
            transitionReason: 'First termination',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $ep3 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,  // ← ATTACK: Second termination
            actorId: 'actor-uuid-002',
            transitionReason: 'Second termination',
            transitionedAt: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2, $ep3],  // ← ATTACK: Two terminals
        );
    }

    /** @test */
    public function it_requires_active_status_before_suspension(): void
    {
        // ATTACK VECTOR: Episode sequence SUSPENDED without prior ACTIVE
        // THREAT: Violation of lifecycle - suspension requires antecedent membership
        // CURRENT: Episode chain validation missing
        // CONSTITUTIONAL GUARANTEE: "Suspension requires prior ACTIVE status"

        $this->expectException(InvalidMembershipLineageException::class);
        $this->expectExceptionMessage('SUSPENDED requires prior ACTIVE');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        // Invalid: Start with SUSPENDED directly
        $ep1 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::SUSPENDED,  // ← ATTACK: No ACTIVE before this
            actorId: 'actor-uuid-001',
            transitionReason: 'Suspended without being active',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1],  // ← ATTACK: Violates lifecycle
        );
    }

    /** @test */
    public function it_prevents_restoration_from_terminated_status(): void
    {
        // ATTACK VECTOR: Episode sequence TERMINATED → ACTIVE (restoration from terminated)
        // THREAT: Terminal state violated - terminated members cannot be reactivated
        // CURRENT: reconstitute() doesn't validate restoration rules
        // CONSTITUTIONAL GUARANTEE: "Only SUSPENDED can be restored to ACTIVE"

        $this->expectException(InvalidMembershipLineageException::class);
        $this->expectExceptionMessage('Invalid state transition');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        $ep1 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        $ep2 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,
            actorId: 'actor-uuid-001',
            transitionReason: 'Terminated',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $ep3 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,  // ← ATTACK: Cannot restore from TERMINATED
            actorId: 'actor-uuid-002',
            transitionReason: null,
            transitionedAt: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2, $ep3],  // ← ATTACK: Impossible restoration
        );
    }

    /** @test */
    public function it_detects_impossible_episode_chain_gaps(): void
    {
        // ATTACK VECTOR: Episode chain with duplicate or skipped statuses
        // THREAT: Discontinuous state progression - logical path violated
        // CURRENT: No validation of continuous logical progression
        // CONSTITUTIONAL GUARANTEE: "Episode chain represents valid state machine path"

        $this->expectException(InvalidMembershipLineageException::class);
        $this->expectExceptionMessage('Invalid state transition');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        // Create sequence: ACTIVE → ACTIVE (duplicate status)
        $ep1 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        $ep2 = CommitteeAssociation::rehydrate(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,  // ← ATTACK: Duplicate status, no transition
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2],  // ← ATTACK: Gap in state machine progression
        );
    }
}
