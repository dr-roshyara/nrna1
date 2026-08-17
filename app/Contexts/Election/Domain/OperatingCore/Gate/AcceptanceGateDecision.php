<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Gate;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteePositionExpressed;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyExpressedPosition;
use App\Contexts\Election\Domain\OperatingCore\Policy\GateIntervalClassification;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use InvalidArgumentException;

/**
 * AG-2 `AcceptanceGateDecision` — the decision record of ONE election-wide gate;
 * one per gate per election (EM-ARCH-001 §2c). The gate decides ACCEPTANCE and
 * never advances phases (B-1); its decision SUBJECT is abstract (D-3).
 *
 * Invariants owned:
 *  I-7  at most one position per seat per acceptance decision (EM-GOV-066)
 *  I-8  a validly cast Committee Vote STANDS — positions are append-only facts
 *       (EM-GOV-066): every state change flows through applying a recorded
 *       `CommitteePositionExpressed` fact, and the aggregate is reconstitutable
 *       FROM those facts (B-7 — the record is the source of truth; reviewer
 *       correction, 2026-08-17)
 *  I-9  a replacement occupant expresses only where the SEAT has not yet expressed
 *       (EM-GOV-066) — positions are keyed by seat, so this holds by construction
 *  I-10 evaluation applies the NAMED rule with round-up arithmetic against the
 *       constituted denominator (EM-GOV-035, 036, 038, 057)
 *  I-11 the interval state is DERIVED on recorded facts (EM-GOV-068; P-2) — and
 *       only against TRUSTED domain facts: the classification takes the
 *       `ElectionCommittee` collaborator, never an arbitrary caller-supplied
 *       vacancy list (reviewer correction, 2026-08-17)
 *  I-12 dissent is recorded even where the threshold is achieved (EM-GOV-031, 005)
 *
 * This aggregate has NO time input of any kind: nothing attaches to an
 * undecided gate or to inaction (EM-GOV-068; G-3; D-4).
 */
final class AcceptanceGateDecision
{
    /** @var array<string, AcceptancePosition> keyed by seat id — written only by apply() (I-8) */
    private array $positions = [];

    private function __construct(
        private readonly ElectionId $electionId,
        private readonly GateDesignation $gate,
        private readonly ThresholdRule $thresholdRule,
        private readonly int $constitutedSize,
    ) {
        if ($constitutedSize < 3) {
            throw new InvalidArgumentException('The constituted denominator is at least three (EM-GOV-033).');
        }
    }

    public static function establish(
        ElectionId $electionId,
        GateDesignation $gate,
        ThresholdRule $thresholdRule,
        int $constitutedSize,
    ): self {
        return new self($electionId, $gate, $thresholdRule, $constitutedSize);
    }

    /**
     * B-7: reconstitution from the recorded facts — `CommitteePositionExpressed`
     * events are the source of truth; state is always rebuildable from them and is
     * never richer than the record (P-2H).
     */
    public static function fromRecordedFacts(
        ElectionId $electionId,
        GateDesignation $gate,
        ThresholdRule $thresholdRule,
        int $constitutedSize,
        CommitteePositionExpressed ...$recordedFacts,
    ): self {
        $decision = new self($electionId, $gate, $thresholdRule, $constitutedSize);

        foreach ($recordedFacts as $fact) {
            if ($fact->electionId->toString() !== $electionId->toString() || $fact->gate !== $gate) {
                throw new InvalidArgumentException(
                    'A recorded fact of another gate or election cannot reconstitute this decision (B-7).'
                );
            }
            if (isset($decision->positions[$fact->seatId->toString()])) {
                throw SeatAlreadyExpressedPosition::withId($fact->seatId->toString());
            }
            $decision->apply($fact);
        }

        return $decision;
    }

    public function electionId(): ElectionId
    {
        return $this->electionId;
    }

    public function gate(): GateDesignation
    {
        return $this->gate;
    }

    public function thresholdRule(): ThresholdRule
    {
        return $this->thresholdRule;
    }

    /** I-3/I-10: the constituted denominator — vacancy does not reduce it (EM-GOV-057). */
    public function constitutedSize(): int
    {
        return $this->constitutedSize;
    }

    public function requiredVotes(): RequiredVotes
    {
        return $this->thresholdRule->requiredVotesFor($this->constitutedSize);
    }

    /**
     * I-7/I-9/I-12: one position per SEAT, append-only; dissent remains expressible
     * and recorded even after the threshold is achieved (EM-GOV-066, 031, 005).
     * The recorded fact is created FIRST and applied — the event, not the mutation,
     * is the truth (I-8).
     */
    public function expressPosition(
        CommitteeSeatId $seatId,
        AcceptancePosition $position,
        RecordedInstant $recordedAt,
    ): CommitteePositionExpressed {
        if (isset($this->positions[$seatId->toString()])) {
            throw SeatAlreadyExpressedPosition::withId($seatId->toString());
        }

        $fact = new CommitteePositionExpressed($this->electionId, $this->gate, $seatId, $position, $recordedAt);
        $this->apply($fact);

        return $fact;
    }

    /** The ONLY writer of position state: applying a recorded fact (I-8; B-7). */
    private function apply(CommitteePositionExpressed $fact): void
    {
        $this->positions[$fact->seatId->toString()] = $fact->position;
    }

    /** @return array<string, AcceptancePosition> keyed by seat id */
    public function positions(): array
    {
        return $this->positions;
    }

    public function acceptCount(): int
    {
        return count(array_filter($this->positions, static fn (AcceptancePosition $p) => $p === AcceptancePosition::Accept));
    }

    public function objectCount(): int
    {
        return count(array_filter($this->positions, static fn (AcceptancePosition $p) => $p === AcceptancePosition::Object));
    }

    /**
     * I-11: derived on recorded facts, never stored (EM-GOV-068; P-2; DD-1) —
     * against the TRUSTED committee record only, never an arbitrary vacancy list.
     * The committee must be THIS election's, with the same constituted denominator
     * the rule was bound against (EM-GOV-057).
     */
    public function intervalState(ElectionCommittee $committee): GateIntervalState
    {
        if ($committee->electionId()->toString() !== $this->electionId->toString()) {
            throw new InvalidArgumentException(
                'The gate derives its interval state from its own election\'s committee facts only.'
            );
        }
        if ($committee->constitutedSize() !== $this->constitutedSize) {
            throw new InvalidArgumentException(
                'The constituted denominator bound at establishment must match the committee record (EM-GOV-057).'
            );
        }

        return GateIntervalClassification::classify(
            $this->constitutedSize,
            $this->requiredVotes(),
            $this->positions,
            array_map(
                static fn (CommitteeSeatId $seatId) => $seatId->toString(),
                $committee->vacantSeatIds(),
            ),
        );
    }
}
