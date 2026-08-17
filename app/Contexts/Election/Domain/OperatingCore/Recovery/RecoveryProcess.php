<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Recovery;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Policy\ClockAccrual;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use DomainException;
use InvalidArgumentException;

/**
 * AG-3 `RecoveryProcess` — one halted-recovery or Committee-restoration period
 * instance (EM-ARCH-001 §2c).
 *
 * Invariants owned:
 *  I-13 policy version and duration are bound at period start; a later policy change
 *       never retroactively alters them (EM-GOV-050(b))
 *  I-14 elapsed time accrues ONLY while the triggering condition is active
 *       (EM-GOV-062) — the clock is a computation over recorded condition intervals
 *       (DD-1), so pause-not-reset and non-retroactivity (EM-GOV-060) fall out of
 *       interval arithmetic
 *  I-15 the allowance is per ELECTION, never per failure; nothing renews, restarts
 *       or extends it (EM-GOV-061(b)) — no such API exists
 *  I-16 resumption resumes the REMAINING portion; no minimum is guaranteed, no time
 *       is created (EM-GOV-060, 061(a))
 *
 * Expiry is a QUESTION this aggregate answers (`isExpiredAt`), never an event it
 * produces — the consequence belongs to the Election rule alone (P-6; EM-GOV-063,
 * 058; EM-OPEN-047 resolution).
 */
final class RecoveryProcess
{
    /** @var list<array{int, int|null}> recorded condition intervals [activeFromEpoch, activeUntilEpoch|null] */
    private array $intervals;

    private bool $active = true;

    private function __construct(
        private readonly ElectionId $electionId,
        private readonly PeriodKind $kind,
        private readonly PolicyBinding $policyBinding,
        RecordedInstant $onset,
    ) {
        $this->intervals = [[$onset->epochSeconds, null]];
    }

    /** The period begins at the recorded onset fact, its policy bound at start (EM-GOV-050(b), 065). */
    public static function start(
        ElectionId $electionId,
        PeriodKind $kind,
        PolicyBinding $policyBinding,
        RecordedInstant $onset,
    ): self {
        return new self($electionId, $kind, $policyBinding, $onset);
    }

    public function electionId(): ElectionId
    {
        return $this->electionId;
    }

    public function kind(): PeriodKind
    {
        return $this->kind;
    }

    /** I-13: the binding captured at start — immutable, no rebinding API exists. */
    public function policyBinding(): PolicyBinding
    {
        return $this->policyBinding;
    }

    /**
     * The triggering condition became inactive at the recorded instant: the clock
     * pauses FROM THAT MOMENT, non-retroactively — a deadline that had already
     * legitimately expired is not undone (EM-GOV-060).
     */
    public function pause(RecordedInstant $at): void
    {
        if (! $this->active) {
            throw new DomainException('The period is not accruing; there is nothing to pause.');
        }

        $lastIndex = array_key_last($this->intervals);
        $from = $this->intervals[$lastIndex][0];
        if ($at->epochSeconds < $from) {
            throw new InvalidArgumentException('Recorded history runs forward: a pause cannot precede the interval it closes (EM-GOV-060).');
        }

        $this->intervals[$lastIndex][1] = $at->epochSeconds;
        $this->active = false;
    }

    /** I-16: resumes with the REMAINING portion only — interval arithmetic creates no time (EM-GOV-060, 061). */
    public function resume(RecordedInstant $at): void
    {
        if ($this->active) {
            throw new DomainException('The period is already accruing.');
        }

        $lastUntil = $this->intervals[array_key_last($this->intervals)][1];
        if ($lastUntil !== null && $at->epochSeconds < $lastUntil) {
            throw new InvalidArgumentException('Recorded history runs forward: a resumption cannot precede the pause it follows.');
        }

        $this->intervals[] = [$at->epochSeconds, null];
        $this->active = true;
    }

    /** DD-1: a derived measure over the recorded intervals — computed, never stored. */
    public function readingAt(RecordedInstant $at): ClockReading
    {
        $elapsed = ClockAccrual::elapsedSeconds($this->intervals, $at);

        return new ClockReading(
            $elapsed,
            max(0, $this->policyBinding->durationSeconds - $elapsed),
        );
    }

    /** Expiry as a question (EM-OPEN-047 resolution): the rule, not the clock, supplies any consequence. */
    public function isExpiredAt(RecordedInstant $at): bool
    {
        return ClockAccrual::elapsedSeconds($this->intervals, $at) >= $this->policyBinding->durationSeconds;
    }
}
