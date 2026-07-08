<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Domain\RulingOutcome;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 (RED) — the Election AGGREGATE decides how a binding determination
 * corrects the election and records `ElectionCorrectionApplied`. Language: the
 * aggregate `applyDetermination()` (it DECIDES); the application handler *reacts*.
 * Behavior-first (ER-07): assert the emitted DOMAIN EVENT, not any wire shape.
 *
 * Invariants: Dismissed ⇒ no correction/event (D-02) · **forward-only** — the
 * election is never rolled back and historical votes are never reversed/exposed
 * (ADR-T8/T11) · aggregate-level idempotency (same determination ⇒ one correction)
 * · the event carries only Election-owned identity (no Contestation `ChallengeId`).
 */
final class ElectionTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:00:00+00:00');
    }

    private function election(): Election
    {
        return Election::identifiedBy(ElectionId::fromString('election-1'));
    }

    public function test_upheld_determination_applies_a_correction_and_records_the_event(): void
    {
        $election = $this->election();

        $election->applyDetermination(DeterminationId::fromString('det-1'), RulingOutcome::Upheld, $this->at());

        $events = $election->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ElectionCorrectionApplied::class, $events[0]);
        $this->assertSame('election-1', $events[0]->electionId->toString());
        $this->assertSame('det-1', $events[0]->determinationId->toString());
        $this->assertSame(CorrectionType::ContainedOnly, $events[0]->correctionType);
    }

    public function test_dismissed_determination_applies_no_correction_and_emits_no_event(): void
    {
        $election = $this->election();

        $election->applyDetermination(DeterminationId::fromString('det-2'), RulingOutcome::Dismissed, $this->at());

        $this->assertSame([], $election->pullEvents(), 'Dismissed ⇒ Election stays silent (D-02)');
    }

    // Constitutional invariant: correction is FORWARD-ONLY — the Election is never
    // reversed and historical votes are never un-cast or exposed (ADR-T8/T11).
    public function test_applied_correction_is_forward_only_and_never_reverses_history(): void
    {
        $election = $this->election();
        $election->applyDetermination(DeterminationId::fromString('det-3'), RulingOutcome::Upheld, $this->at());

        $event = $election->pullEvents()[0];
        $this->assertTrue(
            $event->correctionType->isForwardOnly(),
            'Election corrections are forward-only — votes are never un-cast or reversed',
        );
        // The aggregate exposes no operation to reverse/rescind a correction.
        $this->assertFalse(method_exists($election, 'reverseCorrection'), 'no reversal operation may exist');
        $this->assertFalse(method_exists($election, 'rescindDetermination'), 'no rescind operation may exist');
    }

    public function test_applying_the_same_determination_twice_is_idempotent(): void
    {
        // Aggregate-level idempotency: the same binding determination can never
        // produce two corrections, even if delivered/redriven more than once.
        $election = $this->election();

        $election->applyDetermination(DeterminationId::fromString('det-4'), RulingOutcome::Upheld, $this->at());
        $election->applyDetermination(DeterminationId::fromString('det-4'), RulingOutcome::Upheld, $this->at());

        $this->assertCount(1, $election->pullEvents(), 'exactly one correction per determination');
    }

    // Event boundary: ElectionCorrectionApplied carries only Election-owned identity.
    // It must NOT leak Contestation concepts (e.g. ChallengeId) — Election never
    // knows the Challenge; it reacts to the Determination.
    public function test_correction_event_carries_only_election_owned_identity(): void
    {
        $election = $this->election();
        $election->applyDetermination(DeterminationId::fromString('det-5'), RulingOutcome::Upheld, $this->at());
        $event = $election->pullEvents()[0];

        $this->assertFalse(property_exists($event, 'challengeId'), 'ElectionCorrectionApplied must not carry ChallengeId (Contestation concept)');
        $this->assertFalse(property_exists($event, 'challengeRef'), 'ElectionCorrectionApplied must not carry a Challenge reference');

        // Anonymity (ADR-T11): no voter↔vote linkage either.
        $encoded = (string) json_encode([
            'electionId' => $event->electionId->toString(),
            'determinationId' => $event->determinationId->toString(),
            'correctionType' => $event->correctionType->value,
        ]);
        foreach (['user_id', 'voter_id', 'voterId', 'voting_code', 'votingCode'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $encoded);
        }
    }
}
