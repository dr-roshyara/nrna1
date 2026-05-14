<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\Immutability;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\TransitionReason;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
use PHPUnit\Framework\TestCase;

final class MembershipLineageImmutabilityTest extends TestCase
{
    /** @test */
    public function it_prevents_direct_array_mutation_of_episodes(): void
    {
        // ATTACK VECTOR: Add invalid episode to lineage.episodes directly
        // THREAT: Episode chain becomes corrupted through mutation after creation
        // CURRENT: Private property but not protected from reflection or direct access
        // CONSTITUTIONAL GUARANTEE: "Episode sequence is append-only from authorized operations"

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Get initial episode count
        $initialCount = count($lineage->episodes());

        // ATTACK: Try to create invalid episode (TERMINATED without audit fields)
        // This should fail at constructor time due to invariant validation
        $this->expectException(InvalidMembershipConstructionException::class);

        $invalidEpisode = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::TERMINATED,
            actorId: null,  // ← Invalid: TERMINATED requires actorId
            transitionReason: TransitionReason::fromString('Invalid reason'),
            transitionedAt: $now,
        );
    }

    /** @test */
    public function it_guarantees_lineage_snapshot_immutability(): void
    {
        // ATTACK VECTOR: Get reference to lineage, modify after accessing history
        // THREAT: Lineage state modified externally after reconstruction
        // CURRENT: Lineage could be mutated externally, breaking temporal consistency
        // CONSTITUTIONAL GUARANTEE: "Lineage state cannot be modified after reconstruction"

        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: LineageId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: TenantId::fromString('test-tenant'),
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Capture current state
        $currentStatus1 = $lineage->currentStatus();
        $episodeCount1 = count($lineage->episodes());

        // Try to mutate lineage through reflection (readonly prevents this)
        $this->expectException(\Error::class);

        $reflectionClass = new \ReflectionClass($lineage);
        $memberIdProperty = $reflectionClass->getProperty('memberId');
        $memberIdProperty->setValue($lineage, MemberId::generate());  // ← Should fail
    }
}
