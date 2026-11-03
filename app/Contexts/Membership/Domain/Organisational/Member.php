<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The Member aggregate (EM-VOC-001): one per (Person, Organisation), holding its
 * fact log. Its periods and status are derived by Resolution, never stored.
 *
 * Decisions are pure. They return Accepted(fact, basedOnVersion) or
 * Refused(reason), and never change this object. Appending is Slice 1a.
 */
final readonly class Member
{
    /** @param list<RecordedFact> $log */
    private function __construct(
        private MemberId $id,
        private PersonRef $holder,
        private TenantId $organisation,
        private array $log,
    ) {
        $previous = null;
        foreach ($log as $k => $recorded) {
            if ($recorded->seq() !== $k + 1) {
                throw new \InvalidArgumentException('The log must be in seq order 1..n.');
            }
            if ($previous !== null && $previous->isAfter($recorded->recordedAt())) {
                throw new \InvalidArgumentException('recordedAt must not decrease along seq.');
            }
            $previous = $recorded->recordedAt();
        }
        self::requireStructurallyValid(array_map(static fn (RecordedFact $r): Fact => $r->fact(), $log));
    }

    /**
     * COR-4 load validation (PBDIGIT-329, Q-3; roadmap V-1, V-14, V-15): the log must be one the kernel could have
     * decided, structurally. Every reference points to an earlier fact of this log and has the right kind; N-3 and
     * R-7 hold; a Correct's consequences name earlier admissions, one each. G* and the guards that depend on time
     * or authority are not re-run: a stored history is the record (R-12).
     *
     * @param list<Fact> $facts in seq order
     * @throws \InvalidArgumentException when the log is structurally invalid
     */
    private static function requireStructurallyValid(array $facts): void
    {
        /** @var array<string, Fact> $earlier */
        $earlier = [];
        foreach ($facts as $fact) {
            $id = $fact->id()->value();
            if (isset($earlier[$id])) {
                throw new \InvalidArgumentException("Stored log: fact id {$id} is used twice.");
            }
            if ($fact instanceof End) {
                self::requireEarlier($earlier, $fact->period()->admitFactId(), $id, Admit::class);
            }
            if ($fact instanceof ReversalFact) {
                $target = self::requireEarlier($earlier, $fact->target(), $id, Fact::class);
                if ($fact instanceof Annul && $target instanceof VoidFact) {
                    throw new \InvalidArgumentException("Stored log: Annul {$id} targets a Void (N-3).");
                }
                if ($fact instanceof Annul && $fact->provenance()->kind() !== ProvenanceKind::Decision) {
                    throw new \InvalidArgumentException("Stored log: Annul {$id} has no decision provenance (R-7).");
                }
                // Review F-3 (PO 2026-11-03): an Annul carries its decision instant (P-2) and sets aside only a decision (R-2).
                if ($fact instanceof Annul && $fact->decidedAt() === null) {
                    throw new \InvalidArgumentException("Stored log: Annul {$id} has no decidedAt.");
                }
                if ($fact instanceof Annul && $target->provenance()->kind() !== ProvenanceKind::Decision) {
                    throw new \InvalidArgumentException("Stored log: Annul {$id} targets a fact without decision provenance (R-2).");
                }
            }
            if ($fact instanceof Correct) {
                $target = self::requireEarlier($earlier, $fact->target(), $id, Fact::class);
                if ($target instanceof ReversalFact) {
                    throw new \InvalidArgumentException("Stored log: Correct {$id} targets an Annul or a Void (N-3).");
                }
                // Review F-3: g2 never trims the correction's own period (the period of its chain root).
                $root = $target;
                while ($root instanceof Correct) {
                    $root = $earlier[$root->target()->value()];
                }
                $ownPeriod = $root instanceof End ? $root->period()->admitFactId() : $root->id();
                $trimmed = [];
                foreach ($fact->consequences() as $consequence) {
                    $period = $consequence->period()->admitFactId();
                    if (isset($trimmed[$period->value()])) {
                        throw new \InvalidArgumentException("Stored log: Correct {$id} trims period {$period->value()} twice.");
                    }
                    $trimmed[$period->value()] = true;
                    self::requireEarlier($earlier, $period, $id, Admit::class);
                    if ($period->equals($ownPeriod)) {
                        throw new \InvalidArgumentException("Stored log: Correct {$id} trims its own period {$period->value()}.");
                    }
                }
            }
            $earlier[$id] = $fact;
        }
    }

    /**
     * @param array<string, Fact> $earlier
     * @param class-string<Fact> $kind
     */
    private static function requireEarlier(array $earlier, FactId $reference, string $by, string $kind): Fact
    {
        $fact = $earlier[$reference->value()] ?? null;
        if (!$fact instanceof $kind) {
            throw new \InvalidArgumentException("Stored log: {$by} refers to {$reference->value()}, which is not an earlier fact of the required kind.");
        }

        return $fact;
    }

    public static function fromLog(MemberId $id, PersonRef $holder, TenantId $organisation, RecordedFact ...$log): self
    {
        return new self($id, $holder, $organisation, array_values($log));
    }

    public function id(): MemberId
    {
        return $this->id;
    }

    public function holder(): PersonRef
    {
        return $this->holder;
    }

    public function organisation(): TenantId
    {
        return $this->organisation;
    }

    /** The version is the number of recorded facts; the next fact's seq is version + 1. */
    public function version(): int
    {
        return count($this->log);
    }

    /** @return list<RecordedFact> */
    public function log(): array
    {
        return $this->log;
    }

    public function resolve(): ResolvedMember
    {
        return Resolution::of($this->facts());
    }

    /**
     * What was known at instant τ: Resolution over the facts recorded up to τ.
     * A prefix of the log, because recordedAt does not decrease along seq (INV-14).
     */
    public function knownAt(Instant $tau): ResolvedMember
    {
        $known = array_filter($this->log, static fn (RecordedFact $r): bool => !$r->recordedAt()->isAfter($tau));

        return Resolution::of(array_values(array_map(static fn (RecordedFact $r): Fact => $r->fact(), $known)));
    }

    /**
     * @param TimePoint $decisionDate the caller-supplied calendar date of the decision (D-5(a) Z1); the domain
     *                                reads no clock and no time zone
     * @throws \InvalidArgumentException when effectiveAt or decisionDate is ∞ (invalid input, not a refusal)
     */
    public function decideAdmit(FactId $id, TimePoint $effectiveAt, TimePoint $decisionDate, ?TimePoint $initialTermUntil, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        self::requireFiniteDates($effectiveAt, $decisionDate);
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        if ($initialTermUntil !== null && !$effectiveAt->isBefore($initialTermUntil)) {
            return Decision::refused(RefusalReason::InvalidInitialTerm);
        }
        // D-1 = C1: exactly one recognised basis. A Provenance has exactly one kind; rule and attested are
        // bases in themselves, a decision needs its declaration reference.
        if ($provenance->kind() === ProvenanceKind::Decision && $provenance->declarationRef() === null) {
            return Decision::refused(RefusalReason::AdmissionBasisMissing);
        }
        if ($effectiveAt->isAfter($decisionDate)) {
            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);
        }

        return $this->accept(new Admit($id, $effectiveAt, $initialTermUntil, $provenance, $decidedAt));
    }

    /**
     * @param TimePoint $decisionDate as in decideAdmit (D-5(a) Z1)
     * @throws \InvalidArgumentException as in decideAdmit
     */
    public function decideEnd(FactId $id, PeriodId $period, TimePoint $effectiveAt, TimePoint $decisionDate, EndKind $kind, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        self::requireFiniteDates($effectiveAt, $decisionDate);
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        $target = $this->resolve()->period($period);
        if ($target === null) {
            return Decision::refused(RefusalReason::PeriodNotFound);
        }
        if (!$target->baseClaim()->isOpenEnded()) {
            return Decision::refused(RefusalReason::PeriodAlreadyEnded);
        }
        if (!$target->baseClaim()->start()->isBefore($effectiveAt)) {
            return Decision::refused(RefusalReason::EndNotAfterPeriodStart);
        }
        if ($kind->isLegacy() && $provenance->kind() !== ProvenanceKind::Attested) {
            return Decision::refused(RefusalReason::LegacyEndRequiresAttestedHistory);
        }
        if ($effectiveAt->isAfter($decisionDate)) {
            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);
        }

        return $this->accept(new End($id, $period, $effectiveAt, $kind, $provenance, $decidedAt));
    }

    /** Correction contract §3.1 (C-1): set aside an earlier decision fact; liveness only (D-COR-5 (A)). */
    public function decideAnnul(FactId $id, FactId $target, Provenance $provenance, ?Instant $decidedAt): Decision
    {
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        $targetFact = $this->fact($target);
        if ($targetFact === null) {
            return Decision::refused(RefusalReason::TargetNotFound);
        }
        if ($decidedAt === null) {
            return Decision::refused(RefusalReason::DecidedAtRequired);
        }
        // V-2 (CC §3.1, BR-9a): the Annul is itself a decision; rule or attested provenance cannot set a decision aside.
        if ($provenance->kind() !== ProvenanceKind::Decision) {
            return Decision::refused(RefusalReason::AnnulRequiresDecisionProvenance);
        }
        if ($targetFact instanceof VoidFact) {
            return Decision::refused(RefusalReason::AnnulOfVoidRefused);
        }
        if ($targetFact->provenance()->kind() !== ProvenanceKind::Decision) {
            return Decision::refused(RefusalReason::TargetHasNoDecisionProvenance);
        }
        if (!Resolution::isLive($this->facts(), $target)) {
            return Decision::refused(RefusalReason::TargetNotLive);
        }

        return $this->accept(new Annul($id, $target, $provenance, $decidedAt));
    }

    /** Correction contract §3.2 (C-1): record that an earlier fact never reflected reality; liveness only (D-COR-5 (A)). */
    public function decideVoid(FactId $id, FactId $target, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        if ($this->fact($target) === null) {
            return Decision::refused(RefusalReason::TargetNotFound);
        }
        if (!Resolution::isLive($this->facts(), $target)) {
            return Decision::refused(RefusalReason::TargetNotLive);
        }

        return $this->accept(new VoidFact($id, $target, $provenance, $decidedAt));
    }

    /**
     * Correction contract §3.3 (C-2, PBDIGIT-327): replace the date of an earlier Admit, End or Correct (D-COR-3:
     * dates only). An annulled target is accepted and stays inert until revived (§10a Correct (5); P-6).
     *
     * @param TimePoint $decisionDate as in decideAdmit (D-5(a) Z1); P-5 applies the dating rule to the corrected date
     * @throws \InvalidArgumentException when the corrected date or the decision date is ∞ (invalid input, not a refusal)
     */
    public function decideCorrect(FactId $id, FactId $target, TimePoint $value, TimePoint $decisionDate, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        self::requireFiniteDates($value, $decisionDate);
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        $targetFact = $this->fact($target);
        if ($targetFact === null) {
            return Decision::refused(RefusalReason::TargetNotFound);
        }
        if ($targetFact instanceof ReversalFact) {
            return Decision::refused(RefusalReason::CorrectionOfAnnulOrVoidRefused);
        }
        $facts = $this->facts();
        $root = Resolution::rootOf($facts, $target);
        // §10a Correct (4) and P-1: a voided target, or a chain whose root is voided, never reflected reality.
        if (Resolution::isVoided($facts, $target) || Resolution::isVoided($facts, $root->id())) {
            return Decision::refused(RefusalReason::CorrectOfVoidedFact);
        }
        if ($value->equals(Resolution::valueOf($facts, $target))) {
            return Decision::refused(RefusalReason::ValueUnchanged);
        }
        if (!$this->keepsPeriodOrdered($facts, $root, $value)) {
            return Decision::refused(RefusalReason::EndNotAfterPeriodStart);
        }
        if ($value->isAfter($decisionDate)) {
            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);
        }

        $candidate = new Correct($id, $target, $value, $provenance, $decidedAt);

        return $this->accept(new Correct($id, $target, $value, $provenance, $decidedAt, $this->trimConsequences($facts, $targetFact, $candidate)));
    }

    /**
     * g2, the trim policy (BD-REV = trim; contract §10a Correct (8)), frozen on the Correct (FP §12.4 option C):
     * - Δ: what the correction adds to its period's BaseClaim, less the cuts already on that period, is removed from
     *   every other period's current lifetime;
     * - inherited: a Correct of a Correct takes over its target's trims, clipped to the new BaseClaim.
     * At most one consequence per trimmed period (the union of both parts).
     *
     * @param list<Fact> $facts the log before the decision
     * @return list<TrimConsequence>
     */
    private function trimConsequences(array $facts, Fact $target, Correct $candidate): array
    {
        $after = [...$facts, $candidate];
        $period = Resolution::periodOfChain($after, $candidate);
        $claimAfter = Resolution::baseClaimOf($after, $period);
        $delta = $claimAfter
            ->without(Resolution::baseClaimOf($facts, $period))
            ->without(Resolution::liveCutOf($facts, $period));

        /** @var array<string, array{PeriodId, IntervalSet}> $removed */
        $removed = [];
        foreach (Resolution::of($facts)->periods() as $other) {
            if ($other->id()->equals($period)) {
                continue;
            }
            $removed[$other->id()->value()] = [$other->id(), $delta->intersectedWith($other->lifetime())];
        }
        if ($target instanceof Correct) {
            foreach ($target->consequences() as $inherited) {
                $key = $inherited->period()->value();
                $removed[$key] = [$inherited->period(), ($removed[$key][1] ?? IntervalSet::empty())->union($inherited->removed()->intersectedWith($claimAfter))];
            }
        }

        $consequences = [];
        foreach ($removed as [$trimmed, $interval]) {
            if (!$interval->isEmpty()) {
                $consequences[] = new TrimConsequence($trimmed, $interval);
            }
        }

        return $consequences;
    }

    /**
     * P-4: a corrected End stays after its period's current start (C-S0-1 carried over); a corrected Admit stays
     * before its period's live End, if it has one (the latest by seq; current value).
     *
     * @param list<Fact> $facts
     */
    private function keepsPeriodOrdered(array $facts, Fact $root, TimePoint $value): bool
    {
        if ($root instanceof End) {
            return Resolution::valueOf($facts, $this->admissionOf($root->period())->id())->isBefore($value);
        }
        if (!$root instanceof Admit) {
            throw new \LogicException('The root of a correction chain is an Admit or an End.');
        }
        $liveEnd = null;
        foreach ($facts as $fact) {
            if ($fact instanceof End && $fact->period()->equals($root->periodId()) && Resolution::isLive($facts, $fact->id())) {
                $liveEnd = $fact;
            }
        }

        return $liveEnd === null || $value->isBefore(Resolution::valueOf($facts, $liveEnd->id()));
    }

    private function admissionOf(PeriodId $period): Admit
    {
        foreach ($this->facts() as $fact) {
            if ($fact instanceof Admit && $fact->periodId()->equals($period)) {
                return $fact;
            }
        }
        throw new \LogicException('An End names the period of an Admit in the same log.');
    }

    /** G*: resolve the candidate F·f and accept iff no invariant is violated (contract §15). */
    private function accept(Fact $fact): Decision
    {
        $after = [...$this->facts(), $fact];
        $violations = InvariantSet::violations(Resolution::of($after));

        return $violations === []
            ? Decision::accepted($fact, $this->version(), $this->revivedTrims($after, $fact))
            : Decision::refused($violations[0]);
    }

    /**
     * CFC+ (contract §10a Correct (10)): the trims in force after the fact that were not in force before it, other than
     * the fact's own trims (those are PeriodTrimmed, not revivals). Identity is (cause, period).
     *
     * @param list<Fact> $after
     * @return list<TrimRevival>
     */
    private function revivedTrims(array $after, Fact $fact): array
    {
        $key = static fn (TrimRevival $t): string => $t->cause()->value() . "\0" . $t->period()->value();
        $before = array_flip(array_map($key, Resolution::trimsInForce($this->facts())));

        return array_values(array_filter(
            Resolution::trimsInForce($after),
            static fn (TrimRevival $t): bool => !isset($before[$key($t)]) && !$t->cause()->equals($fact->id()),
        ));
    }

    /** @return list<Fact> */
    private function facts(): array
    {
        return array_map(static fn (RecordedFact $r): Fact => $r->fact(), $this->log);
    }

    /**
     * Hardening H1-a / H1-b (PBDIGIT-271): a decision date is a calendar date and an Admit / End takes effect at
     * a finite time. ∞ stays legitimate for open-ended terms, so this is a precondition here, not a TimePoint rule.
     */
    private static function requireFiniteDates(TimePoint $effectiveAt, TimePoint $decisionDate): void
    {
        if ($effectiveAt->isInfinite() || $decisionDate->isInfinite()) {
            throw new \InvalidArgumentException('The effective date and the decision date must be finite.');
        }
    }

    private function usesFactId(FactId $id): bool
    {
        foreach ($this->log as $recorded) {
            if ($recorded->fact()->id()->equals($id)) {
                return true;
            }
        }

        return false;
    }

    private function fact(FactId $id): ?Fact
    {
        foreach ($this->log as $recorded) {
            if ($recorded->fact()->id()->equals($id)) {
                return $recorded->fact();
            }
        }

        return null;
    }
}
