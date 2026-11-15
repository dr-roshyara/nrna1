<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A resignation declared for a LATER date (PBDIGIT-354 S2-4a; D-201, D-204, D-205; M-TDD-01 §5.2).
 *
 * - **Held outside the ledger**, per period, like an application (D-2, D-204): it is not a Fact, changes no coverage,
 *   and C1 / C2 never show it (MI-4). Only its execution records a ledger fact (`Member::decideScheduledResignationEnd`).
 * - **One scheduled declaration per period.** Withdrawn, executed and superseded ones do not count.
 * - Carries **the authority answer given when it was declared** (`provenance`, `decidedAt`): execution uses it
 *   (M-TDD-01 §5.1 "the answer stored with the declaration"); nobody is asked again at execution.
 * - Immutable: every transition returns a new value (or a RefusalReason). Final states never change.
 */
final readonly class ScheduledResignation
{
    private function __construct(
        private DeclarationId $id,
        private PeriodId $period,
        private TimePoint $declarationDate,
        private TimePoint $effectiveDate,
        private Provenance $provenance,
        private ?Instant $decidedAt,
        private ScheduledResignationState $state,
        private ?TimePoint $withdrawnOn,
        private ?Provenance $withdrawnBy,
        private ?FactId $executedAs,
        private ?string $supersededBecause,
    ) {
        if ($declarationDate->isInfinite() || $effectiveDate->isInfinite()) {
            throw new \InvalidArgumentException('A scheduled resignation has finite dates.');
        }
        if (!$declarationDate->isBefore($effectiveDate)) {
            throw new \InvalidArgumentException('A scheduled resignation takes effect after its declaration.');
        }
        $consistent = match ($state) {
            ScheduledResignationState::Scheduled => $withdrawnOn === null && $withdrawnBy === null && $executedAs === null && $supersededBecause === null,
            ScheduledResignationState::Withdrawn => $withdrawnOn !== null && $withdrawnBy !== null && $withdrawnOn->isBefore($effectiveDate) && $executedAs === null && $supersededBecause === null,
            ScheduledResignationState::Executed => $executedAs !== null && $withdrawnOn === null && $withdrawnBy === null && $supersededBecause === null,
            ScheduledResignationState::Superseded => $supersededBecause !== null && trim($supersededBecause) !== '' && $withdrawnOn === null && $withdrawnBy === null && $executedAs === null,
        };
        if (!$consistent) {
            throw new \InvalidArgumentException("An inconsistent {$state->value} scheduled resignation.");
        }
    }

    /**
     * Rebuild a stored declaration (S2-4b). Every state's fields are checked, so an inconsistent row cannot be read as
     * a valid declaration.
     *
     * @throws \InvalidArgumentException when the stored fields do not form a valid declaration
     */
    public static function fromStorage(DeclarationId $id, PeriodId $period, TimePoint $declarationDate, TimePoint $effectiveDate, Provenance $provenance, ?Instant $decidedAt,
        ScheduledResignationState $state, ?TimePoint $withdrawnOn, ?Provenance $withdrawnBy, ?FactId $executedAs, ?string $supersededBecause): self
    {
        return new self($id, $period, $declarationDate, $effectiveDate, $provenance, $decidedAt, $state, $withdrawnOn, $withdrawnBy, $executedAs, $supersededBecause);
    }

    /**
     * Declare a resignation from $effectiveDate (D-201). The period is the member's current one at $declarationDate;
     * the caller never names it.
     *
     * @param list<ScheduledResignation> $existing the member's declarations so far (any state)
     * @throws \InvalidArgumentException when a date is ∞ (invalid input, not a refusal)
     */
    public static function schedule(DeclarationId $id, ResolvedMember $member, array $existing, TimePoint $declarationDate, TimePoint $effectiveDate, Provenance $provenance, ?Instant $decidedAt): self|RefusalReason
    {
        if ($declarationDate->isInfinite() || $effectiveDate->isInfinite()) {
            throw new \InvalidArgumentException('A scheduled resignation has finite dates.');
        }
        foreach ($existing as $other) {
            if ($other->id->equals($id)) {
                return RefusalReason::DuplicateDeclarationId;
            }
        }
        if (!$declarationDate->isBefore($effectiveDate)) {
            return RefusalReason::ScheduledDateNotAfterDeclaration;   // the same day is "resign now" (S2-3)
        }
        $periodId = $member->currentPeriod($declarationDate);
        if ($periodId === null) {
            return RefusalReason::PeriodNotFound;
        }
        if (!$effectiveDate->isBefore($member->period($periodId)->baseClaim()->until())) {
            return RefusalReason::PeriodEnded;   // it already ends by the scheduled date: nothing to schedule
        }
        foreach ($existing as $other) {
            if ($other->period->equals($periodId) && $other->state === ScheduledResignationState::Scheduled) {
                return RefusalReason::ScheduledResignationAlreadyPending;
            }
        }

        return new self($id, $periodId, $declarationDate, $effectiveDate, $provenance, $decidedAt, ScheduledResignationState::Scheduled, null, null, null, null);
    }

    /**
     * D-201: withdrawn before the effective date; once that date has started, refused (D-223). `$by` is who withdrew
     * it and with what authority (the member, or a recorder: GC-12), so the history is complete (S2-4b).
     *
     * `$organisationToday` (S2-4d; M-TDD-01 §5.2 "withdrawal is refused once the date has started (organisation-local),
     * even if not yet executed"): the organisation's own date at the ledger reading taken under the ledger-clock lock.
     * The store passes it; a stated `$on` before the date does not override it. Null only for the use case's early
     * check, before the store decides.
     */
    public function withdraw(TimePoint $on, Provenance $by, ?TimePoint $organisationToday = null): self|RefusalReason
    {
        if ($this->state !== ScheduledResignationState::Scheduled) {
            return RefusalReason::DeclarationNotScheduled;
        }
        if (!$on->isBefore($this->effectiveDate) || ($organisationToday !== null && !$organisationToday->isBefore($this->effectiveDate))) {
            return RefusalReason::WithdrawalAfterEffectiveDate;
        }

        return $this->with(ScheduledResignationState::Withdrawn, withdrawnOn: $on, withdrawnBy: $by);
    }

    /** MI-3: the period ended earlier (another End, or an Annul / Void of the period); the reason is recorded. */
    public function supersede(string $because): self|RefusalReason
    {
        if ($this->state !== ScheduledResignationState::Scheduled) {
            return RefusalReason::DeclarationNotScheduled;
        }
        if (trim($because) === '') {
            throw new \InvalidArgumentException('A superseded declaration records why.');
        }

        return $this->with(ScheduledResignationState::Superseded, supersededBecause: $because);
    }

    /** After its End is recorded (in the same transaction). */
    public function markExecuted(FactId $end): self|RefusalReason
    {
        if ($this->state !== ScheduledResignationState::Scheduled) {
            return RefusalReason::DeclarationNotScheduled;
        }

        return $this->with(ScheduledResignationState::Executed, executedAs: $end);
    }

    private function with(ScheduledResignationState $state, ?TimePoint $withdrawnOn = null, ?Provenance $withdrawnBy = null, ?FactId $executedAs = null, ?string $supersededBecause = null): self
    {
        return new self($this->id, $this->period, $this->declarationDate, $this->effectiveDate, $this->provenance, $this->decidedAt, $state,
            $withdrawnOn ?? $this->withdrawnOn, $withdrawnBy ?? $this->withdrawnBy, $executedAs ?? $this->executedAs, $supersededBecause ?? $this->supersededBecause);
    }

    public function id(): DeclarationId
    {
        return $this->id;
    }

    public function period(): PeriodId
    {
        return $this->period;
    }

    public function declarationDate(): TimePoint
    {
        return $this->declarationDate;
    }

    public function effectiveDate(): TimePoint
    {
        return $this->effectiveDate;
    }

    public function provenance(): Provenance
    {
        return $this->provenance;
    }

    public function decidedAt(): ?Instant
    {
        return $this->decidedAt;
    }

    public function state(): ScheduledResignationState
    {
        return $this->state;
    }

    public function withdrawnOn(): ?TimePoint
    {
        return $this->withdrawnOn;
    }

    public function withdrawnBy(): ?Provenance
    {
        return $this->withdrawnBy;
    }

    public function executedAs(): ?FactId
    {
        return $this->executedAs;
    }

    public function supersededBecause(): ?string
    {
        return $this->supersededBecause;
    }
}
