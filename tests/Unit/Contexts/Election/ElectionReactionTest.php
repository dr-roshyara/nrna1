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
 * PB-004 Step 3 (RED) — the Election aggregate reacts to a binding determination
 * by applying (or not) a correction, and records the ElectionCorrectionApplied
 * domain event. Behavior-first (ER-07): asserts the emitted DOMAIN EVENT, not any
 * wire/JSON shape. Election reconstructs its OWN local model (ADR-T16).
 *
 * Invariants: Dismissed ⇒ no correction/event (D-02) · forward-only ContainedOnly
 * (ADR-T8) · idempotent at the aggregate level (same determination ⇒ one correction)
 * · anonymity — the event carries no voter↔vote linkage (ADR-T11).
 */
final class ElectionReactionTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:00:00+00:00');
    }

    private function election(): Election
    {
        return Election::identifiedBy(ElectionId::fromString('election-1'));
    }

    public function test_upheld_determination_records_a_contained_only_correction_event(): void
    {
        $election = $this->election();

        $election->reactToDetermination(DeterminationId::fromString('det-1'), RulingOutcome::Upheld, $this->at());

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

        $election->reactToDetermination(DeterminationId::fromString('det-2'), RulingOutcome::Dismissed, $this->at());

        $this->assertSame([], $election->pullEvents(), 'Dismissed ⇒ Election stays silent (D-02)');
    }

    public function test_applying_the_same_determination_twice_is_idempotent(): void
    {
        // Aggregate-level idempotency: the same binding determination can never
        // produce two corrections, even if delivered/redriven more than once.
        $election = $this->election();

        $election->reactToDetermination(DeterminationId::fromString('det-3'), RulingOutcome::Upheld, $this->at());
        $election->reactToDetermination(DeterminationId::fromString('det-3'), RulingOutcome::Upheld, $this->at());

        $this->assertCount(1, $election->pullEvents(), 'exactly one correction per determination');
    }

    public function test_correction_event_carries_no_voter_vote_linkage(): void
    {
        $election = $this->election();
        $election->reactToDetermination(DeterminationId::fromString('det-4'), RulingOutcome::Upheld, $this->at());

        $event = $election->pullEvents()[0];
        // Anonymity (ADR-T11): only election/determination/correction identity + time.
        $encoded = json_encode([
            'electionId' => $event->electionId->toString(),
            'determinationId' => $event->determinationId->toString(),
            'correctionType' => $event->correctionType->value,
        ]);
        foreach (['user_id', 'voter_id', 'voterId', 'voting_code', 'votingCode'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, (string) $encoded);
        }
    }
}
