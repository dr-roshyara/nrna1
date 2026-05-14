<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\Immutability;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\CommitteeId;
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

        $episode = CommitteeAssociation::create(
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
        );

        $lineage = MembershipLineage::establish(
            lineageId: LineageId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            initialEpisode: $episode,
        );

        // Get initial episode count
        $initialCount = count($lineage->episodes());

        // Try to mutate episodes directly through reflection
        $this->expectException(\Error::class);

        $reflectionClass = new \ReflectionClass($lineage);
        $episodesProperty = $reflectionClass->getProperty('episodes');
        $episodesProperty->setAccessible(true);
        $episodes = $episodesProperty->getValue($lineage);

        // Try to add invalid episode directly
        $invalidEpisode = new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
            status: MembershipStatus::TERMINATED,
            actorId: null,  // Invalid - no actor
            transitionReason: 'Invalid reason',
            transitionedAt: new \DateTimeImmutable('2026-05-14 11:30:00'),
        );

        $episodes[] = $invalidEpisode;  // ← Should fail
    }

    /** @test */
    public function it_guarantees_lineage_snapshot_immutability(): void
    {
        // ATTACK VECTOR: Get reference to lineage, modify after accessing history
        // THREAT: Lineage state modified externally after reconstruction
        // CURRENT: Lineage could be mutated externally, breaking temporal consistency
        // CONSTITUTIONAL GUARANTEE: "Lineage state cannot be modified after reconstruction"

        $episode = CommitteeAssociation::create(
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
        );

        $lineage = MembershipLineage::establish(
            lineageId: LineageId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            initialEpisode: $episode,
        );

        // Capture current state
        $currentStatus1 = $lineage->currentStatus();
        $episodeCount1 = count($lineage->episodes());

        // Try to mutate lineage through reflection
        $this->expectException(\Error::class);

        $reflectionClass = new \ReflectionClass($lineage);
        $memberIdProperty = $reflectionClass->getProperty('memberId');
        $memberIdProperty->setValue($lineage, 'different-member-uuid');  // ← Should fail
    }
}
