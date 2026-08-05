<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Process;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Application\Process\Exception\IllegalProcessTransition;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * WP-2 — the Adjudication Process Manager's state, with its business guards.
 *
 * The APM is the loop's HEAD and only the head (ADR-T8): it conducts one routed
 * challenge's adjudication to exactly one conclusion. It is orchestration, NOT an
 * aggregate (Candidate-2 ruling) — hence Application-seated, immutable, no domain
 * repository (EPIC-004K §11).
 *
 * States/transitions are business-derived and closed (EPIC-004K §5/§6): no finer
 * gradation is minted (ASP). The authority DECIDES; this process RECEIVES —
 * nothing here computes legitimacy or sufficiency (K1 / Q-1 / ADR-T23).
 *
 * Traceability: EPIC-004K §§3,5,6,11 · roadmap §WP-2 keystones · EPIC-004E INV-B1
 * (mirrored at the process side) · Policy 4 (no timer→conclusion path) · ADR-T11.
 */
final class AdjudicationProcessStateTest extends TestCase
{
    private function at(string $t = '2026-07-30T10:00:00+00:00'): DateTimeImmutable
    {
        return new DateTimeImmutable($t);
    }

    private function opened(): AdjudicationProcessState
    {
        return AdjudicationProcessState::open(
            AdjudicationProcessId::fromString('apm-1'),
            ChallengeRef::fromString('ch-1'),
            $this->at(),
        );
    }

    private function awaitingDecision(): AdjudicationProcessState
    {
        return $this->opened()
            ->admitEvidence('envelope-sha256-abc', $this->at())
            ->submitToAuthority($this->at());
    }

    private function authority(): IssuedByAuthority
    {
        return IssuedByAuthority::fromString('authority-cab-01');
    }

    private function considered(): EvidenceSet
    {
        return EvidenceSet::fromRefs('envelope-sha256-abc');
    }

    // ── §6: the lawful path, in business language ───────────────────────────

    public function test_opening_a_process_starts_it_opened(): void
    {
        $process = $this->opened();

        $this->assertSame(AdjudicationProcessStatus::Opened, $process->status());
        $this->assertSame('ch-1', $process->challengeRef()->toString());
        $this->assertFalse($process->status()->isTerminal());
    }

    public function test_first_admission_moves_the_process_to_assembling(): void
    {
        $process = $this->opened()->admitEvidence('envelope-sha256-abc', $this->at());

        $this->assertSame(AdjudicationProcessStatus::Assembling, $process->status());
        $this->assertSame(['envelope-sha256-abc'], $process->admittedEvidence());
    }

    public function test_submitting_the_basis_moves_to_awaiting_decision(): void
    {
        $this->assertSame(AdjudicationProcessStatus::AwaitingDecision, $this->awaitingDecision()->status());
    }

    public function test_authority_may_demand_more_evidence_returning_to_assembling(): void
    {
        // A decision to NOT-YET-decide is the authority's prerogative (§6) — it is
        // not a conclusion and must not be recorded as one.
        $process = $this->awaitingDecision()->returnForMoreEvidence($this->at());

        $this->assertSame(AdjudicationProcessStatus::Assembling, $process->status());
    }

    public function test_ruling_decision_concludes_the_process_as_ruling_requested(): void
    {
        $process = $this->awaitingDecision()->concludeRulingRequested(
            $this->considered(),
            $this->authority(),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Tally dispute upheld on the considered record.'),
            $this->at(),
        );

        $this->assertSame(AdjudicationProcessStatus::ConcludedRulingRequested, $process->status());
        $this->assertTrue($process->status()->isTerminal());
    }

    public function test_insufficiency_decision_concludes_the_process_as_failure_declared(): void
    {
        $process = $this->awaitingDecision()->concludeFailureDeclared(
            $this->considered(),
            $this->authority(),
            Reason::fromString('The assembled evidence is insufficient to rule.'),
            $this->at(),
        );

        $this->assertSame(AdjudicationProcessStatus::ConcludedFailureDeclared, $process->status());
        $this->assertTrue($process->status()->isTerminal());
    }

    // ── KEYSTONE: exactly one conclusion, of exactly one kind ───────────────

    public function test_a_concluded_process_cannot_conclude_again(): void
    {
        $concluded = $this->awaitingDecision()->concludeRulingRequested(
            $this->considered(), $this->authority(), DeterminationOutcome::Upheld,
            Legitimacy::Legitimate, Reason::fromString('first'), $this->at(),
        );

        $this->expectException(IllegalProcessTransition::class);

        $concluded->concludeRulingRequested(
            $this->considered(), $this->authority(), DeterminationOutcome::Dismissed,
            Legitimacy::Illegitimate, Reason::fromString('second'), $this->at(),
        );
    }

    public function test_the_two_conclusion_kinds_can_never_be_mixed(): void
    {
        $concluded = $this->awaitingDecision()->concludeRulingRequested(
            $this->considered(), $this->authority(), DeterminationOutcome::Upheld,
            Legitimacy::Legitimate, Reason::fromString('ruling'), $this->at(),
        );

        $this->expectException(IllegalProcessTransition::class);

        $concluded->concludeFailureDeclared(
            $this->considered(), $this->authority(), Reason::fromString('failure too'), $this->at(),
        );
    }

    // ── KEYSTONE: no admission after conclusion ─────────────────────────────

    public function test_no_evidence_may_be_admitted_after_conclusion(): void
    {
        $concluded = $this->awaitingDecision()->concludeFailureDeclared(
            $this->considered(), $this->authority(), Reason::fromString('insufficient'), $this->at(),
        );

        $this->expectException(IllegalProcessTransition::class);

        $concluded->admitEvidence('envelope-sha256-late', $this->at());
    }

    public function test_an_illegal_transition_leaves_the_process_exactly_as_it_was(): void
    {
        $awaiting = $this->awaitingDecision();

        try {
            $awaiting->admitEvidence('envelope-sha256-out-of-turn', $this->at());
            $this->fail('Expected IllegalProcessTransition');
        } catch (IllegalProcessTransition) {
            // State is immutable, so this is structural — asserted so the guarantee is pinned.
            $this->assertSame(AdjudicationProcessStatus::AwaitingDecision, $awaiting->status());
            $this->assertSame(['envelope-sha256-abc'], $awaiting->admittedEvidence());
        }
    }

    // ── PM-5: conclude-time atomic fixation ─────────────────────────────────

    public function test_conclusion_fixes_the_considered_set_and_the_authority_together(): void
    {
        // PM-5: the conclusion, the evidence-set-as-considered and the authority
        // reference are one fact — a partially concluded process is unrepresentable.
        // (The PERMANENT fixation is the aggregate's, at issuance — ADR-T22/INV-4.)
        // The lawful path to a second admission runs through the authority's
        // decision to NOT-YET-decide (§6) — evidence cannot be slipped in while
        // the basis is already before the authority. The guard proved this when an
        // earlier draft of this test tried the shortcut.
        $process = $this->awaitingDecision()
            ->returnForMoreEvidence($this->at())
            ->admitEvidence('envelope-sha256-def', $this->at())
            ->submitToAuthority($this->at())
            ->concludeRulingRequested(
                EvidenceSet::fromRefs('envelope-sha256-abc', 'envelope-sha256-def'),
                $this->authority(),
                DeterminationOutcome::Upheld,
                Legitimacy::Legitimate,
                Reason::fromString('Both envelopes considered.'),
                $this->at(),
            );

        $this->assertNotNull($process->consideredEvidence());
        $this->assertSame(
            ['envelope-sha256-abc', 'envelope-sha256-def'],
            $process->consideredEvidence()->toArray(),
        );
        $this->assertSame('authority-cab-01', $process->concludedByAuthority()->toString());
        $this->assertSame(AdjudicationProcessStatus::ConcludedRulingRequested, $process->status());
    }

    // ── PM-8 + Policy 4: the horizon bounds the conduct; a timer never rules ─

    public function test_horizon_expiry_yields_expired_a_distinct_terminal_fact(): void
    {
        $expired = $this->awaitingDecision()->expire($this->at('2026-09-28T10:00:00+00:00'));

        $this->assertSame(AdjudicationProcessStatus::Expired, $expired->status());
        $this->assertTrue($expired->status()->isTerminal());
    }

    public function test_expiry_never_produces_a_conclusion(): void
    {
        // Policy 4: automated verification never determines significance. The
        // horizon firing is not an adjudication — it must not conclude anything.
        $expired = $this->awaitingDecision()->expire($this->at('2026-09-28T10:00:00+00:00'));

        $this->assertNull($expired->consideredEvidence());
        $this->assertNull($expired->concludedByAuthority());
        $this->assertNotSame(AdjudicationProcessStatus::ConcludedRulingRequested, $expired->status());
        $this->assertNotSame(AdjudicationProcessStatus::ConcludedFailureDeclared, $expired->status());
    }

    public function test_an_expired_process_cannot_be_concluded_afterwards(): void
    {
        // The ARB's horizon ruling: late decisions are never honoured.
        $expired = $this->awaitingDecision()->expire($this->at('2026-09-28T10:00:00+00:00'));

        $this->expectException(IllegalProcessTransition::class);

        $expired->concludeRulingRequested(
            $this->considered(), $this->authority(), DeterminationOutcome::Upheld,
            Legitimacy::Legitimate, Reason::fromString('too late'), $this->at(),
        );
    }

    public function test_a_concluded_process_cannot_expire(): void
    {
        $concluded = $this->awaitingDecision()->concludeRulingRequested(
            $this->considered(), $this->authority(), DeterminationOutcome::Upheld,
            Legitimacy::Legitimate, Reason::fromString('ruled'), $this->at(),
        );

        $this->expectException(IllegalProcessTransition::class);

        $concluded->expire($this->at('2026-09-28T10:00:00+00:00'));
    }

    // ── ASP: the state set is closed ────────────────────────────────────────

    public function test_the_state_set_is_exactly_the_six_business_states(): void
    {
        // EPIC-004K §5 — a seventh state would be invented, not discovered.
        $this->assertCount(6, AdjudicationProcessStatus::cases());
    }
}
