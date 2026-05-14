<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\Immutability;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

final class CommitteeAssociationImmutabilityTest extends TestCase
{
    /** @test */
    public function it_guarantees_suspend_returns_new_instance_not_original(): void
    {
        // ATTACK VECTOR: suspend() returns same object instead of new instance
        // THREAT: Original instance mutates, breaking immutability contract
        // CURRENT: suspend() could be implemented as mutation instead of new instance
        // CONSTITUTIONAL GUARANTEE: "Transitions create new instances, never mutate original"

        $original = CommitteeAssociation::create(
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
        );

        $suspended = $original->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // CRITICAL: Must be different object instances
        $this->assertNotSame($original, $suspended);
        // CRITICAL: Verify it's actually a new object
        $this->assertInstanceOf(CommitteeAssociation::class, $suspended);
        $this->assertNotEquals(\spl_object_id($original), \spl_object_id($suspended));
    }

    /** @test */
    public function it_preserves_immutability_original_unchanged_after_transition(): void
    {
        // ATTACK VECTOR: Call suspend(), then check original.status and see it changed
        // THREAT: Mutation violates readonly contract - original is modified after transition
        // CURRENT: If suspend() mutates instead of new instance, original becomes suspended
        // CONSTITUTIONAL GUARANTEE: "Original instance remains unchanged after transition"

        $now = new \DateTimeImmutable('2026-05-14 10:00:00');
        $original = CommitteeAssociation::create(
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
        );

        // Verify original is ACTIVE before transition
        $this->assertTrue($original->status->equals(MembershipStatus::ACTIVE));
        $this->assertNull($original->actorId);
        $this->assertNull($original->transitionReason);

        // Perform transition
        $suspended = $original->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // CRITICAL: Original must be unchanged
        $this->assertTrue($original->status->equals(MembershipStatus::ACTIVE));
        $this->assertNull($original->actorId);
        $this->assertNull($original->transitionReason);

        // New instance must have new state
        $this->assertTrue($suspended->status->equals(MembershipStatus::SUSPENDED));
        $this->assertEquals('actor-uuid-001', $suspended->actorId);
        $this->assertEquals('Disciplinary action', $suspended->transitionReason);
    }

    /** @test */
    public function it_prevents_external_modification_of_episode_reference(): void
    {
        // ATTACK VECTOR: Get reference to episode, modify it externally
        // THREAT: External code mutates episode state through reflection or property access
        // CURRENT: readonly only protects direct property assignment, not method-based mutation
        // CONSTITUTIONAL GUARANTEE: "Episodes cannot be modified through any reference"

        $episode = CommitteeAssociation::create(
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
        );

        // Verify initial state
        $this->assertTrue($episode->status->equals(MembershipStatus::ACTIVE));

        // Try to modify through reflection (this must fail)
        $this->expectException(\Error::class);

        $reflectionClass = new \ReflectionClass($episode);
        $statusProperty = $reflectionClass->getProperty('status');
        $statusProperty->setValue($episode, MembershipStatus::SUSPENDED);
    }

    /** @test */
    public function it_prevents_mutation_through_episodes_collection_reference(): void
    {
        // ATTACK VECTOR: Get lineage.episodes array, modify it directly
        // THREAT: External code adds/removes/modifies episodes after lineage creation
        // CURRENT: episodes() method returns array reference that can be modified
        // CONSTITUTIONAL GUARANTEE: "Episode history is immutable from external access"

        $episode = CommitteeAssociation::create(
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
        );

        $lineage = \App\Contexts\Membership\Domain\Membership\MembershipLineage::establish(
            lineageId: \App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId::generate(),
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            initialEpisode: $episode,
        );

        // Get the episodes
        $episodes1 = $lineage->episodes();
        $count1 = count($episodes1);

        // Try to mutate through the returned reference
        $this->expectException(\Error::class);
        $episodes1[] = CommitteeAssociation::create(
            memberId: 'member-uuid',
            committeeId: 'committee-uuid',
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );
    }
}
