<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\ConstructorSafety;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MembershipLineageConstructorSafetyTest extends TestCase
{
    /** @test */
    public function it_rejects_lineage_reconstitution_with_impossible_episode_sequence(): void
    {
        // ATTACK VECTOR: Inject invalid episodes directly into lineage via reconstitute()
        // THREAT: Historical integrity corruption - impossible transitions created
        // CURRENT: reconstitute() accepts any array of episodes without validation
        // CONSTITUTIONAL GUARANTEE: "Episode history must form a coherent constitutional chain"

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('SUSPENDED requires prior ACTIVE');

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        // Create an impossible sequence: SUSPENDED → ACTIVE → SUSPENDED (no re-suspension allowed)
        $ep1 = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-01-01 10:00:00'),
            status: MembershipStatus::SUSPENDED,
            actorId: 'actor-001',
            transitionReason: 'First suspension',
            transitionedAt: new \DateTimeImmutable('2026-01-01 11:00:00'),
        );

        $ep2 = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-01-01 10:00:00'),
            status: MembershipStatus::ACTIVE,
            actorId: null,
            transitionReason: null,
            transitionedAt: null,
        );

        $ep3 = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-01-01 10:00:00'),
            status: MembershipStatus::SUSPENDED,  // ← ATTACK: Cannot suspend again
            actorId: 'actor-002',
            transitionReason: 'Second suspension',
            transitionedAt: new \DateTimeImmutable('2026-01-01 12:00:00'),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2, $ep3],  // ← ATTACK: Impossible sequence
        );
    }

    /** @test */
    public function it_rejects_establishment_with_invalid_initial_episode(): void
    {
        // ATTACK VECTOR: Reconstitute lineage with non-ACTIVE first episode
        // THREAT: Lineage begins in invalid state - no valid foundation
        // CURRENT: reconstitute() doesn't validate first episode is ACTIVE
        // CONSTITUTIONAL GUARANTEE: "Initial episodes must have valid constitutional status"

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('SUSPENDED requires prior ACTIVE');

        // Try to reconstitute with SUSPENDED as first episode (invalid)
        $invalidInitial = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::SUSPENDED,  // ← ATTACK: Cannot start suspended
            actorId: 'actor-uuid',
            transitionReason: 'Invalid initial state',
            transitionedAt: new \DateTimeImmutable(),
        );

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$invalidInitial],
        );
    }

    /** @test */
    public function it_prevents_lineage_creation_with_empty_episodes(): void
    {
        // ATTACK VECTOR: Create lineage with zero episodes
        // THREAT: Lineage has no truth - currentStatus() undefined, audit trail empty
        // CURRENT: reconstitute() accepts empty episode arrays
        // CONSTITUTIONAL GUARANTEE: "Lineage must always have at least one episode"

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Lineage must have at least one episode');

        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [],  // ← ATTACK: Empty history
        );
    }
}
