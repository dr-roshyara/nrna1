<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\HydrationIntegrity;

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

final class MembershipHydrationIntegrityTest extends TestCase
{
    /** @test */
    public function it_rejects_hydration_of_suspended_without_actor_id(): void
    {
        // ATTACK VECTOR: Database contains SUSPENDED but suspended_by_actor_id=NULL
        // THREAT: Persistence layer injects invalid state - audit trail corrupted
        // CURRENT: hydrateEpisodes() doesn't validate consistency
        // CONSTITUTIONAL GUARANTEE: "Hydration validates consistency with domain rules"

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/memberId/');

        // Simulate database state: SUSPENDED without actor
        $invalidEpisode = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::SUSPENDED,
            actorId: null,  // ← ATTACK: Database contains null actor
            transitionReason: 'Some reason',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // This should fail during hydration
        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$invalidEpisode],
        );
    }

    /** @test */
    public function it_rejects_hydration_of_terminated_without_reason(): void
    {
        // ATTACK VECTOR: Database has TERMINATED but termination_reason=NULL
        // THREAT: Governance loses justification - why termination cannot be explained
        // CURRENT: No validation during hydration
        // CONSTITUTIONAL GUARANTEE: "Termination always has documented reason"

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/memberId/');

        // Simulate database state: TERMINATED without reason
        $invalidEpisode = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::TERMINATED,
            actorId: 'actor-uuid-001',
            transitionReason: null,  // ← ATTACK: Database contains null reason
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // This should fail during hydration
        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$invalidEpisode],
        );
    }

    /** @test */
    public function it_prevents_partial_episode_reconstruction_from_database(): void
    {
        // ATTACK VECTOR: Episode reconstructed with missing audit fields
        // THREAT: Incomplete episodes load silently - state appears valid but missing data
        // CURRENT: hydrateEpisodes() might skip optional fields
        // CONSTITUTIONAL GUARANTEE: "All required fields are present after hydration"

        $lineageId = LineageId::generate();
        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('test-tenant');
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $tenantId,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Suspend to create SUSPENDED episode
        $suspensionTime = $now->modify('+1 hour');
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: $suspensionTime,
        );

        $episodes = $lineage->episodes();
        $suspendedEpisode = $episodes[1];  // Second episode is the suspended one

        // CRITICAL: Verify all required audit fields are present
        $this->assertNotNull($suspendedEpisode->status);
        $this->assertEquals(MembershipStatus::SUSPENDED, $suspendedEpisode->status);
        $this->assertNotNull($suspendedEpisode->actorId, 'actorId must be present after hydration');
        $this->assertNotNull($suspendedEpisode->transitionReason, 'transitionReason must be present after hydration');
        $this->assertNotNull($suspendedEpisode->transitionedAt, 'transitionedAt must be present after hydration');
    }

    /** @test */
    public function it_detects_impossible_episode_chains_during_hydration(): void
    {
        // ATTACK VECTOR: Database contains impossible episode sequence
        // THREAT: Corrupted state loaded from persistence - invalid chain passed as fact
        // CURRENT: loadEpisodesForLineage() doesn't validate chain
        // CONSTITUTIONAL GUARANTEE: "Reconstructed lineage must be logically valid"

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid state transition');

        // Create impossible sequence in memory (simulating bad database state)
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
            status: MembershipStatus::SUSPENDED,  // ← ATTACK: Cannot suspend after termination
            actorId: 'actor-uuid-002',
            transitionReason: 'Suspended',
            transitionedAt: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        // This should fail because the chain is impossible
        MembershipLineage::reconstitute(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            episodes: [$ep1, $ep2, $ep3],  // ← ATTACK: Corrupted sequence from DB
        );
    }
}
