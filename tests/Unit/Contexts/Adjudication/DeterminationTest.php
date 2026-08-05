<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\DeterminationState;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\Exception\IllegalDeterminationTransition;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DeterminationTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-06-27T10:00:00+00:00');
    }

    private function draft(): Determination
    {
        return Determination::prepare(
            DeterminationId::fromString('det-1'),
            ChallengeRef::fromString('ch-1'),
            IssuedByAuthority::fromString('ARB'),
            Jurisdiction::fromString('National'),
            EvidenceEnvelopeRef::fromString('ev-1'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('election-1'),
                TargetType::ElectionResult,
                TargetId::fromString('result-1'),
            ),
        );
    }

    public function test_prepare_creates_draft_without_event(): void
    {
        $d = $this->draft();
        $this->assertSame(DeterminationState::Draft, $d->state());
        $this->assertSame([], $d->pullEvents());
    }

    public function test_issue_transitions_to_issued_and_records_event(): void
    {
        $d = $this->draft();
        $d->issue(DeterminationOutcome::Upheld, Legitimacy::Legitimate, Reason::fromString('Tally dispute upheld.'), EvidenceSet::fromRefs('ev-1'), $this->at());

        $this->assertSame(DeterminationState::Issued, $d->state());
        $events = $d->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DeterminationIssued::class, $events[0]);
        $this->assertSame('det-1', $events[0]->determinationId->toString());
        $this->assertSame('ch-1', $events[0]->challengeRef->toString());
        $this->assertSame(DeterminationOutcome::Upheld, $events[0]->outcome);
    }

    // ADR-UL-01 / ADR-PL-01: the Determination carries the ContestedOutcomeRef it
    // rules on, and emits it on DeterminationIssued (payload schema version 2).
    public function test_issue_carries_the_contested_outcome(): void
    {
        $determination = Determination::prepare(
            DeterminationId::fromString('det-2'),
            ChallengeRef::fromString('ch-2'),
            IssuedByAuthority::fromString('ARB'),
            Jurisdiction::fromString('National'),
            EvidenceEnvelopeRef::fromString('ev-2'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('election-1'),
                TargetType::ElectionResult,
                TargetId::fromString('result-1'),
            ),
        );

        $determination->issue(DeterminationOutcome::Upheld, Legitimacy::Legitimate, Reason::fromString('Upheld.'), EvidenceSet::fromRefs('ev-1'), $this->at());

        $events = $determination->pullEvents();
        $this->assertInstanceOf(DeterminationIssued::class, $events[0]);
        $this->assertNotNull($events[0]->contestedOutcome);
        $this->assertSame('election-1', $events[0]->contestedOutcome->electionId->toString());
        $this->assertSame(TargetType::ElectionResult, $events[0]->contestedOutcome->type);
        $this->assertSame('result-1', $events[0]->contestedOutcome->targetId->toString());
    }

    // ── KEYSTONE (WP-1, ADR-T22): the considered set is fixed at issuance and
    // carried immutably in the event — R-4-expanded lands at the aggregate's
    // issuance (INV-4 rider: record and announcement can never diverge). ──
    public function test_issue_fixes_the_considered_evidence_set_in_the_event(): void
    {
        $d = $this->draft();
        $set = EvidenceSet::fromRefs('ev-1', 'ev-supplementary-2');

        $d->issue(
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Upheld on the considered record.'),
            $set,
            $this->at(),
        );

        $events = $d->pullEvents();
        $this->assertInstanceOf(DeterminationIssued::class, $events[0]);
        $this->assertNotNull($events[0]->evidenceSet);
        $this->assertSame(['ev-1', 'ev-supplementary-2'], $events[0]->evidenceSet->toArray());

        // Immutability: exporting and tampering with the export cannot alter
        // what the event fixed.
        $exported = $events[0]->evidenceSet->toArray();
        $exported[] = 'injected-after-issuance';
        $this->assertSame(['ev-1', 'ev-supplementary-2'], $events[0]->evidenceSet->toArray());
    }

    public function test_finalize_transitions_issued_to_final_without_event(): void
    {
        $d = $this->draft();
        $d->issue(DeterminationOutcome::Dismissed, Legitimacy::Legitimate, Reason::fromString('No merit.'), EvidenceSet::fromRefs('ev-1'), $this->at());
        $d->pullEvents();

        $d->finalize($this->at());

        $this->assertSame(DeterminationState::Final, $d->state());
        $this->assertSame([], $d->pullEvents(), 'finalize emits no event');
    }

    public function test_cannot_issue_twice(): void
    {
        $d = $this->draft();
        $d->issue(DeterminationOutcome::Upheld, Legitimacy::Legitimate, Reason::fromString('first'), EvidenceSet::fromRefs('ev-1'), $this->at());

        $this->expectException(IllegalDeterminationTransition::class);
        $d->issue(DeterminationOutcome::Dismissed, Legitimacy::Illegitimate, Reason::fromString('second'), EvidenceSet::fromRefs('ev-1'), $this->at());
    }

    public function test_cannot_finalize_a_draft(): void
    {
        $d = $this->draft();
        try {
            $d->finalize($this->at());
            $this->fail('Expected IllegalDeterminationTransition');
        } catch (IllegalDeterminationTransition) {
            $this->assertSame(DeterminationState::Draft, $d->state(), 'state unchanged after forbidden transition');
        }
    }

    public function test_cannot_issue_a_final(): void
    {
        $d = $this->draft();
        $d->issue(DeterminationOutcome::Upheld, Legitimacy::Legitimate, Reason::fromString('r'), EvidenceSet::fromRefs('ev-1'), $this->at());
        $d->finalize($this->at());

        $this->expectException(IllegalDeterminationTransition::class);
        $d->issue(DeterminationOutcome::Dismissed, Legitimacy::Illegitimate, Reason::fromString('after final'), EvidenceSet::fromRefs('ev-1'), $this->at());
    }

    public function test_no_event_emitted_after_final(): void
    {
        $d = $this->draft();
        $d->issue(DeterminationOutcome::Upheld, Legitimacy::Legitimate, Reason::fromString('r'), EvidenceSet::fromRefs('ev-1'), $this->at());
        $d->finalize($this->at());
        $d->pullEvents();

        // Any further (forbidden) command must not emit an event.
        try {
            $d->issue(DeterminationOutcome::Dismissed, Legitimacy::Illegitimate, Reason::fromString('x'), EvidenceSet::fromRefs('ev-1'), $this->at());
        } catch (IllegalDeterminationTransition) {
        }
        $this->assertSame([], $d->pullEvents(), 'no event may be emitted after Final');
    }
}
