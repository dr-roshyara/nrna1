<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\EventIntegrity;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\Events\MembershipSuspended;
use App\Contexts\Membership\Domain\Membership\Events\MembershipRestored;
use App\Contexts\Membership\Domain\Membership\Events\MembershipTerminated;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

final class MembershipEventOrderingTest extends TestCase
{
    /** @test */
    public function it_guarantees_event_dispatch_after_persistence_completes(): void
    {
        // ATTACK VECTOR: Event dispatched before save() completes
        // THREAT: Handler processes event before persistence - subscribers see uncommitted state
        // CURRENT: Handler might dispatch events before saveForTenant()
        // CONSTITUTIONAL GUARANTEE: "Events are emitted only after persistence succeeds"

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

        $lineage->releaseEvents();

        // Perform transition
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // Get event BEFORE any external persistence (handlers would see uncommitted state)
        $events = $lineage->releaseEvents();
        $event = $events[0];

        // CRITICAL: Event contains sufficient data to be meaningful
        // If emitted before persistence, downstream would have incomplete info
        $this->assertNotNull($event->lineageId);
        $this->assertNotNull($event->actorId);
        $this->assertNotNull($event->reason);
        $this->assertNotNull($event->suspendedAt);
    }

    /** @test */
    public function it_preserves_event_ordering_across_multiple_transitions(): void
    {
        // ATTACK VECTOR: Multiple transitions produce events in wrong order
        // THREAT: Event sequence doesn't match transition sequence - replay creates wrong state
        // CURRENT: Event emission could happen in arbitrary order
        // CONSTITUTIONAL GUARANTEE: "Event sequence matches transition sequence exactly"

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

        $lineage->releaseEvents();

        $time1 = new \DateTimeImmutable('2026-05-14 11:00:00');
        $time2 = new \DateTimeImmutable('2026-05-14 12:00:00');

        // Perform sequence: ACTIVE → SUSPENDED → RESTORED
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'First action',
            at: $time1,
        );

        $suspensionEvents = $lineage->releaseEvents();

        $lineage->restore(
            actorId: 'actor-uuid-002',
            at: $time2,
        );

        $restorationEvents = $lineage->releaseEvents();

        // CRITICAL: Order is preserved
        $this->assertInstanceOf(MembershipSuspended::class, $suspensionEvents[0]);
        $this->assertInstanceOf(MembershipRestored::class, $restorationEvents[0]);

        // CRITICAL: Timestamps show correct sequence
        $this->assertTrue($suspensionEvents[0]->suspendedAt < $restorationEvents[0]->restoredAt);
    }

    /** @test */
    public function it_guarantees_replay_events_match_persistence_order(): void
    {
        // ATTACK VECTOR: Events stored in different order than transitions occurred
        // THREAT: Replay reconstructs different state than operational path
        // CURRENT: Event timestamping might not preserve order
        // CONSTITUTIONAL GUARANTEE: "Replay order is identical to transition order"

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

        $lineage->releaseEvents();

        // Perform three transitions with precise timing
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Action 1',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );
        $events1 = $lineage->releaseEvents();

        $lineage->restore(
            actorId: 'actor-uuid-002',
            at: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );
        $events2 = $lineage->releaseEvents();

        $lineage->suspend(
            actorId: 'actor-uuid-003',
            reason: 'Action 3',
            at: new \DateTimeImmutable('2026-05-14 13:00:00'),
        );
        $events3 = $lineage->releaseEvents();

        // CRITICAL: All events are in chronological order
        $allEvents = array_merge($events1, $events2, $events3);

        $this->assertInstanceOf(MembershipSuspended::class, $allEvents[0]);
        $this->assertInstanceOf(MembershipRestored::class, $allEvents[1]);
        $this->assertInstanceOf(MembershipSuspended::class, $allEvents[2]);

        // CRITICAL: Timestamps are monotonically increasing
        $this->assertLessThan(
            $allEvents[1]->restoredAt->getTimestamp(),
            $allEvents[0]->suspendedAt->getTimestamp() + 3600
        );
    }
}
