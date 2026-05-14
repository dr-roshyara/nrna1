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

final class MembershipEventEmissionTest extends TestCase
{
    /** @test */
    public function it_guarantees_exactly_one_membership_suspended_event_per_suspension(): void
    {
        // ATTACK VECTOR: Call suspend(), no MembershipSuspended event emitted
        // THREAT: Event stream becomes incomplete - audit trail broken, F3.3 replay fails
        // CURRENT: recordEvent() might not be called in suspend() method
        // CONSTITUTIONAL GUARANTEE: "Every suspension generates exactly one MembershipSuspended event"

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

        // Clear any establishment events
        $lineage->releaseEvents();

        // Perform suspension
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        // Get emitted events
        $events = $lineage->releaseEvents();

        // CRITICAL: Exactly one event emitted
        $this->assertCount(1, $events, 'Suspension must emit exactly one event');
        $this->assertInstanceOf(MembershipSuspended::class, $events[0]);
        $this->assertEquals('Disciplinary action', $events[0]->reason);
    }

    /** @test */
    public function it_prevents_duplicate_event_emission_on_single_transition(): void
    {
        // ATTACK VECTOR: suspend() emits MembershipSuspended twice
        // THREAT: Event duplication creates inconsistent replay - same transition applied twice
        // CURRENT: recordEvent() could be called multiple times
        // CONSTITUTIONAL GUARANTEE: "Each transition produces exactly one event"

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

        // Perform suspension
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $events = $lineage->releaseEvents();

        // CRITICAL: Count suspension events specifically
        $suspensionEvents = array_filter(
            $events,
            fn($e) => $e instanceof MembershipSuspended
        );

        $this->assertCount(1, $suspensionEvents, 'Must emit exactly one suspension event');
    }

    /** @test */
    public function it_guarantees_exactly_one_membership_restored_event_per_restoration(): void
    {
        // ATTACK VECTOR: Call restore(), no MembershipRestored event
        // THREAT: Event stream missing restoration transaction - replay incomplete
        // CURRENT: restore() might not call recordEvent()
        // CONSTITUTIONAL GUARANTEE: "Every restoration generates exactly one MembershipRestored event"

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

        // Suspend first
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Temporary suspension',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $lineage->releaseEvents();

        // Restore
        $lineage->restore(
            actorId: 'actor-uuid-002',
            at: new \DateTimeImmutable('2026-05-14 12:00:00'),
        );

        $events = $lineage->releaseEvents();

        // CRITICAL: Exactly one restoration event
        $this->assertCount(1, $events, 'Restoration must emit exactly one event');
        $this->assertInstanceOf(MembershipRestored::class, $events[0]);
    }

    /** @test */
    public function it_guarantees_exactly_one_membership_terminated_event_per_termination(): void
    {
        // ATTACK VECTOR: Call terminate(), no MembershipTerminated event
        // THREAT: Event stream missing termination - irreversible action without audit
        // CURRENT: terminate() might not call recordEvent()
        // CONSTITUTIONAL GUARANTEE: "Every termination generates exactly one MembershipTerminated event"

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

        // Terminate
        $lineage->terminate(
            actorId: 'actor-uuid-001',
            reason: 'Violation of code of conduct',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $events = $lineage->releaseEvents();

        // CRITICAL: Exactly one termination event
        $this->assertCount(1, $events, 'Termination must emit exactly one event');
        $this->assertInstanceOf(MembershipTerminated::class, $events[0]);
        $this->assertEquals('Violation of code of conduct', $events[0]->reason);
    }

    /** @test */
    public function it_requires_complete_event_payload_on_suspension(): void
    {
        // ATTACK VECTOR: MembershipSuspended event emitted without actorId or reason
        // THREAT: Governance loses accountability - who suspended and why is missing
        // CURRENT: Event constructor doesn't validate required fields
        // CONSTITUTIONAL GUARANTEE: "All events must have complete required payloads"

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

        // Suspend
        $lineage->suspend(
            actorId: 'actor-uuid-001',
            reason: 'Disciplinary action',
            at: new \DateTimeImmutable('2026-05-14 11:00:00'),
        );

        $events = $lineage->releaseEvents();
        $event = $events[0];

        // CRITICAL: All required fields present and non-null
        $this->assertNotNull($event->lineageId);
        $this->assertNotNull($event->memberId);
        $this->assertNotNull($event->committeeId);
        $this->assertNotNull($event->actorId);
        $this->assertNotNull($event->reason);
        $this->assertNotNull($event->suspendedAt);
        $this->assertNotEmpty($event->actorId, 'Actor ID must not be empty');
        $this->assertNotEmpty($event->reason, 'Reason must not be empty');
    }
}
