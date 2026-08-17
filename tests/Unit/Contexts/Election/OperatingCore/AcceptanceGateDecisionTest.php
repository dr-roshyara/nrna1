<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteePositionExpressed;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyExpressedPosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Gate\ThresholdRule;
use App\Contexts\Election\Domain\OperatingCore\Policy\GateIntervalClassification;
use App\Contexts\Election\Domain\OperatingCore\Policy\ThresholdEvaluation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * AG-2 `AcceptanceGateDecision` — invariants I-7…I-12, P-1 threshold arithmetic and
 * P-2 derived interval classification (EM-ARCH-001 §2c/§2e; EM-GOV-016, 031, 035,
 * 036, 038, 057, 066, 068; R-F2). RED tests 7–14 of the readiness report §E.
 */
final class AcceptanceGateDecisionTest extends TestCase
{
    private function gate(int $constitutedSize = 3): AcceptanceGateDecision
    {
        return AcceptanceGateDecision::establish(
            ElectionId::fromString('election-1'),
            GateDesignation::First,
            ThresholdRule::twoThirdsOfCommitteeVotes(),
            $constitutedSize,
        );
    }

    private function seat(string $id): CommitteeSeatId
    {
        return CommitteeSeatId::fromString($id);
    }

    /** The TRUSTED domain collaborator the gate derives against (reviewer correction, 2026-08-17). */
    private function committee(): ElectionCommittee
    {
        return ElectionCommittee::constitute(
            ElectionId::fromString('election-1'),
            CommitteeSeatId::fromString('seat-a'),
            CommitteeSeatId::fromString('seat-b'),
            CommitteeSeatId::fromString('seat-c'),
        );
    }

    private function at(int $epoch = 1_000): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epoch);
    }

    /** Test 7 — P-1: required = ceil(2n/3), round-up, against the constituted denominator (EM-GOV-036/038/057). */
    #[DataProvider('roundUpArithmetic')]
    public function test_required_votes_are_two_thirds_rounded_up(int $constituted, int $required): void
    {
        $this->assertSame($required, RequiredVotes::forConstitutedSize($constituted)->count());
        $this->assertSame(
            $required,
            ThresholdEvaluation::requiredVotes(ThresholdRule::twoThirdsOfCommitteeVotes(), $constituted)->count()
        );
    }

    /** @return array<string, array{int, int}> The sizes EM-GOV-033/036 verified: 3→2 · 4→3 · 5→4 · 6→4 · 7→5. */
    public static function roundUpArithmetic(): array
    {
        return [
            '3 seats' => [3, 2],
            '4 seats' => [4, 3],
            '5 seats' => [5, 4],
            '6 seats' => [6, 4],
            '7 seats' => [7, 5],
        ];
    }

    /** Test 8 — I-10: evaluation applies the NAMED rule token, never a numeric percentage (EM-GOV-035). */
    public function test_threshold_rule_is_a_named_token_not_a_percentage(): void
    {
        $rule = ThresholdRule::twoThirdsOfCommitteeVotes();
        $this->assertSame('TWO_THIRDS_OF_COMMITTEE_VOTES', $rule->name());

        // The rule set this core consumes is adopted EM-GOV-036 directly — no menu (D-6):
        // an unknown rule name is refused, and no constructor accepts a numeric value.
        $this->expectException(\InvalidArgumentException::class);
        ThresholdRule::fromName('SIXTY_SIX_PERCENT');
    }

    /** Test 9 — I-7: a seat expresses at most ONE position per acceptance decision (EM-GOV-066). */
    public function test_a_seat_expresses_at_most_one_position_per_decision(): void
    {
        $gate = $this->gate();

        $event = $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at());
        $this->assertInstanceOf(CommitteePositionExpressed::class, $event);

        $this->expectException(SeatAlreadyExpressedPosition::class);
        $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Object, $this->at(1_001));
    }

    /** Test 10 — I-8: a validly cast Committee Vote STANDS; positions are append-only facts (EM-GOV-066). */
    public function test_a_cast_committee_vote_stands_and_cannot_be_removed(): void
    {
        $gate = $this->gate();
        $fact = $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at());

        // No API removes or rewrites a position (append-only facts).
        foreach (get_class_methods($gate) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/remove|withdraw|revoke|erase|reset|clear/i',
                $method,
                'A validly cast Committee Vote stands (EM-GOV-066); positions are append-only.'
            );
        }

        $this->assertSame(1, $gate->acceptCount());
        $this->assertArrayHasKey('seat-a', $gate->positions());

        // B-7: the recorded fact is the source of truth — the aggregate is
        // reconstitutable FROM CommitteePositionExpressed and never richer than it.
        $rebuilt = AcceptanceGateDecision::fromRecordedFacts(
            ElectionId::fromString('election-1'),
            GateDesignation::First,
            ThresholdRule::twoThirdsOfCommitteeVotes(),
            3,
            $fact,
        );
        $this->assertSame(AcceptancePosition::Accept, $rebuilt->positions()['seat-a']);
        $this->assertSame($gate->positions(), $rebuilt->positions());
    }

    /** Test 11 — I-9: a replacement occupant expresses only where the SEAT has not yet expressed (EM-GOV-066). */
    public function test_a_replacement_may_express_only_where_the_seat_has_not_yet_expressed(): void
    {
        $gate = $this->gate();

        // seat-a expressed before its occupant left; the vote stands and binds the SEAT.
        $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at());

        // seat-b never expressed: a replacement occupant participates fully there.
        $event = $gate->expressPosition($this->seat('seat-b'), AcceptancePosition::Object, $this->at(1_001));
        $this->assertInstanceOf(CommitteePositionExpressed::class, $event);

        // But the replacement on seat-a cannot express again — the seat already has.
        $this->expectException(SeatAlreadyExpressedPosition::class);
        $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at(1_002));
    }

    /**
     * Test 12 — I-11 / P-2: the interval state is DERIVED on recorded facts:
     * OPEN iff undecided ∧ achievable · decided-pass iff accepts ≥ required ·
     * decided-failure iff satisfaction impossible by decision (EM-GOV-068).
     */
    public function test_interval_state_is_derived_on_recorded_facts(): void
    {
        // 3 constituted, required 2 — derived against the TRUSTED committee record.
        $committee = $this->committee();
        $gate = $this->gate();
        $this->assertSame(GateIntervalState::Open, $gate->intervalState($committee));

        // One accept: still undecided, still achievable → OPEN.
        $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at());
        $this->assertSame(GateIntervalState::Open, $gate->intervalState($committee));

        // Second accept: accepts ≥ required → decided pass.
        $gate->expressPosition($this->seat('seat-b'), AcceptancePosition::Accept, $this->at(1_001));
        $this->assertSame(GateIntervalState::DecidedPass, $gate->intervalState($committee));

        // Decided failure by decision: at 3/2, two objections (R-F2).
        $failed = $this->gate();
        $failed->expressPosition($this->seat('seat-a'), AcceptancePosition::Object, $this->at());
        $this->assertSame(GateIntervalState::Open, $failed->intervalState($committee));
        $failed->expressPosition($this->seat('seat-b'), AcceptancePosition::Object, $this->at(1_001));
        $this->assertSame(GateIntervalState::DecidedFailure, $failed->intervalState($committee));
    }

    /**
     * Test 13 — S-06 (R-F2): temporary unavailability with non-vacant ≥ required ⇒ gate OPEN;
     * a single remaining member can neither pass nor decidedly fail; no third exit.
     * The gate model has no clock to run — OPEN has no time input at all (EM-GOV-068, 062; G-3).
     */
    public function test_s06_temporary_unavailability_keeps_the_gate_open_and_no_clock_exists(): void
    {
        // S-06: members temporarily unavailable are NOT vacancies — no recorded fact changes.
        $gate = $this->gate();
        $this->assertSame(GateIntervalState::Open, $gate->intervalState($this->committee()));

        // Single remaining member (two seats vacant by recorded events): the remaining member
        // can neither pass (1 < 2) nor decidedly fail (objections cannot reach impossibility);
        // achievability on recorded facts is lost → not OPEN, and NOT a decided failure.
        $state = GateIntervalClassification::classify(
            3,
            RequiredVotes::forConstitutedSize(3),
            [],
            ['seat-a', 'seat-b'],
        );
        $this->assertSame(GateIntervalState::Unachievable, $state);
        $this->assertNotSame(GateIntervalState::DecidedFailure, $state, 'Vacancy is never a decision (R-F2).');
        $this->assertNotSame(GateIntervalState::DecidedPass, $state);

        // No clock, no deadline, no expiry hook anywhere on the gate (G-3, D-4).
        foreach (get_class_methods(AcceptanceGateDecision::class) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/clock|expir|timeout|deadline|schedul|elaps/i',
                $method,
                'OPEN has no clock, no deadline and no expiry hook to hang one on (EM-GOV-068; G-3).'
            );
        }
    }

    /**
     * Semantics pin (review dispute, 2026-08-17) — DecidedFailure vs Unachievable:
     * a DECIDED failure is a pure function of objections (R-F2: at 3/2, two
     * objections — permanent, no future filling undoes it); UNACHIEVABLE is
     * vacancy arithmetic on recorded facts (recoverable: filling returns the gate
     * to OPEN, EM-GOV-059(c)). The two are never conflated in either direction
     * (EM-GOV-068; I-11).
     */
    public function test_decided_failure_is_a_pure_function_of_objections_never_of_vacancies(): void
    {
        $required = RequiredVotes::forConstitutedSize(3); // 2

        // Canonical R-F2, NO vacancies: two objections ⇒ DECIDED failure — never Unachievable.
        $this->assertSame(
            GateIntervalState::DecidedFailure,
            GateIntervalClassification::classify(3, $required, [
                'seat-a' => AcceptancePosition::Object,
                'seat-b' => AcceptancePosition::Object,
            ], []),
            'Two objections at 3/2 are the decided failure (R-F2); reordering that makes this Unachievable breaks EM-GOV-068.'
        );

        // One objection + two vacancies: impossibility is VACANCY arithmetic ⇒ Unachievable, not a decision.
        $this->assertSame(
            GateIntervalState::Unachievable,
            GateIntervalClassification::classify(3, $required, [
                'seat-a' => AcceptancePosition::Object,
            ], ['seat-b', 'seat-c']),
        );

        // Two objections + one vacancy: still DECIDED — occupy the vacant seat and it fails anyway
        // (even its accept gives 1 < 2). The objections alone decided it; the vacancy adds nothing.
        $withVacancy = GateIntervalClassification::classify(3, $required, [
            'seat-a' => AcceptancePosition::Object,
            'seat-b' => AcceptancePosition::Object,
        ], ['seat-c']);
        $withoutVacancy = GateIntervalClassification::classify(3, $required, [
            'seat-a' => AcceptancePosition::Object,
            'seat-b' => AcceptancePosition::Object,
            'seat-c' => AcceptancePosition::Accept,
        ], []);
        $this->assertSame(GateIntervalState::DecidedFailure, $withVacancy);
        $this->assertSame(GateIntervalState::DecidedFailure, $withoutVacancy, 'Same positions, no vacancy: identical verdict — proof the vacancy was not the cause.');

        // Vacancy-only impossibility (no objections at all) is NEVER a decided failure.
        $this->assertSame(
            GateIntervalState::Unachievable,
            GateIntervalClassification::classify(3, $required, [], ['seat-a', 'seat-b']),
            'Vacancy is never a decision (R-F2); only objections can decide a failure.'
        );
    }

    /** Test 14 — I-12: dissent is recorded even where the threshold is achieved (EM-GOV-031, 005). */
    public function test_dissent_is_recorded_even_where_the_threshold_is_achieved(): void
    {
        $committee = $this->committee();
        $gate = $this->gate();
        $gate->expressPosition($this->seat('seat-a'), AcceptancePosition::Accept, $this->at());
        $gate->expressPosition($this->seat('seat-b'), AcceptancePosition::Accept, $this->at(1_001));
        $this->assertSame(GateIntervalState::DecidedPass, $gate->intervalState($committee));

        // The third seat's dissent is still expressible and remains on the record.
        $event = $gate->expressPosition($this->seat('seat-c'), AcceptancePosition::Object, $this->at(1_002));
        $this->assertInstanceOf(CommitteePositionExpressed::class, $event);
        $this->assertSame(AcceptancePosition::Object, $event->position);

        $this->assertSame(GateIntervalState::DecidedPass, $gate->intervalState($committee), 'Dissent changes no achieved threshold.');
        $this->assertSame(AcceptancePosition::Object, $gate->positions()['seat-c'], 'Dissent remains recorded.');
    }
}
