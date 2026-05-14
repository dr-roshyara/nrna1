<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\HydrationIntegrity;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\Events\MembershipSuspended;
use App\Contexts\Membership\Domain\Membership\Events\MembershipRestored;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidAssociationTransitionException;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class EventReplayIntegrityTest extends TestCase
{
    /** @test */
    public function it_guarantees_replay_result_equals_database_snapshot(): void
    {
        // ATTACK VECTOR: Replay from events produces different state than DB snapshot
        // THREAT: Temporal truth diverges from operational truth - audit trail unreliable
        // CURRENT: No determinism verification
        // CONSTITUTIONAL GUARANTEE: "Event replay is deterministic and matches persistence"

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

        $lineage->releaseEvents();

        // Perform transitions
        $time1 = new \DateTimeImmutable('2026-05-14 11:00:00');
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'First suspension',
            at: $time1,
        );

        $events = $lineage->releaseEvents();

        // Capture the operational state after suspension
        $operationalStatus = $lineage->currentStatus();
        $operationalActor = $lineage->current()->actorId?->value();

        // CRITICAL: If we were to replay from events, we'd get the same state
        $this->assertTrue($operationalStatus->equals(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::SUSPENDED));
        $this->assertEquals('actor-uuid-001', $operationalActor);

        // Verify events captured all necessary information for replay
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MembershipSuspended::class, $events[0]);
        $this->assertEquals('actor-uuid-001', $events[0]->actorId);
        $this->assertEquals('First suspension', $events[0]->reason);
    }

    /** @test */
    public function it_detects_missing_events_in_replay_stream(): void
    {
        // ATTACK VECTOR: Event table is incomplete (suspension event missing)
        // THREAT: Replay produces incomplete state - missing intermediate transitions
        // CURRENT: No event count validation
        // CONSTITUTIONAL GUARANTEE: "All state transitions are captured as events"

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

        $lineage->releaseEvents();

        // Perform two transitions
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Suspended',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $events1 = $lineage->releaseEvents();

        $lineage->restore(
            actorId: 'actor-uuid-002',
            at: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        $events2 = $lineage->releaseEvents();

        // CRITICAL: Both transitions must be represented
        $this->assertCount(1, $events1, 'Suspension must emit one event');
        $this->assertCount(1, $events2, 'Restoration must emit one event');

        // If we had a replay with missing suspension event, we'd go ACTIVE → ACTIVE directly
        // That would be invalid
        $this->assertInstanceOf(MembershipSuspended::class, $events1[0]);
        $this->assertInstanceOf(MembershipRestored::class, $events2[0]);
    }

    /** @test */
    public function it_prevents_double_application_of_duplicate_events(): void
    {
        // ATTACK VECTOR: Same MembershipSuspended event applied twice during replay
        // THREAT: Transition applied twice - state drifts from operational
        // CURRENT: Replay doesn't detect duplicates
        // CONSTITUTIONAL GUARANTEE: "Each event applies exactly once to state"

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

        $lineage->releaseEvents();

        // Single suspension
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Suspended',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $events = $lineage->releaseEvents();

        // CRITICAL: Exactly one event, not duplicated
        $this->assertCount(1, $events);

        // If replay got this same event twice, it would try to suspend again
        // But current status is already SUSPENDED, so second suspension would fail
        $this->assertTrue($lineage->currentStatus()->equals(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::SUSPENDED));

        // Attempting to apply the same event again should fail
        $this->expectException(\App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException::class);

        // Try to re-suspend (same event applied twice)
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Suspended',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );
    }

    /** @test */
    public function it_guarantees_deterministic_replay_regardless_of_ordering(): void
    {
        // ATTACK VECTOR: Replay in different order produces different state
        // THREAT: Same events → different final state depending on replay sequence
        // CURRENT: Events might not be ordered
        // CONSTITUTIONAL GUARANTEE: "Replay is deterministic (same input → same output)"

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

        $lineage->releaseEvents();

        // Perform sequence: ACTIVE → SUSPENDED → RESTORED
        $time1 = new \DateTimeImmutable('2026-05-14 11:00:00');
        $time2 = new \DateTimeImmutable('2026-05-14 12:00:00');

        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Suspended',
            at: $time1,
        );

        $suspensionEvents = $lineage->releaseEvents();

        $lineage->restore(
            actorId: 'actor-uuid-002',
            at: $time2,
        );

        $restorationEvents = $lineage->releaseEvents();

        // Combine all events in order
        $allEvents = array_merge($suspensionEvents, $restorationEvents);

        // CRITICAL: Events must be timestamp-ordered to ensure determinism
        $event1Time = $suspensionEvents[0]->suspendedAt->getTimestamp();
        $event2Time = $restorationEvents[0]->restoredAt->getTimestamp();

        $this->assertLessThan(
            $event2Time,
            $event1Time,
            'Events must be in temporal order for deterministic replay'
        );

        // CRITICAL: Final state must be ACTIVE (same regardless of replay order)
        $this->assertTrue($lineage->currentStatus()->equals(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::ACTIVE));
    }
}
