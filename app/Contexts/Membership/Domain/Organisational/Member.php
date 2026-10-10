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

    public function decideAdmit(FactId $id, TimePoint $effectiveAt, ?TimePoint $initialTermUntil, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        if ($this->usesFactId($id)) {
            return Decision::refused(RefusalReason::DuplicateFactId);
        }
        if ($initialTermUntil !== null && !$effectiveAt->isBefore($initialTermUntil)) {
            return Decision::refused(RefusalReason::InvalidInitialTerm);
        }

        return $this->accept(new Admit($id, $effectiveAt, $initialTermUntil, $provenance, $decidedAt));
    }

    public function decideEnd(FactId $id, PeriodId $period, TimePoint $effectiveAt, EndKind $kind, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
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

        return $this->accept(new End($id, $period, $effectiveAt, $kind, $provenance, $decidedAt));
    }

    /** Correction contract §3.1 (C-1): set aside an earlier decision fact; liveness only (D-COR-5 (A)). */
    public function decideAnnul(FactId $id, FactId $target, Provenance $provenance, ?Instant $decidedAt): Decision
    {
        $targetFact = $this->fact($target);
        if ($targetFact !== null && $targetFact->provenance()->kind() !== ProvenanceKind::Decision) {
            return Decision::refused(RefusalReason::TargetHasNoDecisionProvenance);
        }

        return $this->accept(new Annul($id, $target, $provenance, $decidedAt));
    }

    /** Correction contract §3.2 (C-1): record that an earlier fact never reflected reality; liveness only (D-COR-5 (A)). */
    public function decideVoid(FactId $id, FactId $target, Provenance $provenance, ?Instant $decidedAt = null): Decision
    {
        return $this->accept(new VoidFact($id, $target, $provenance, $decidedAt));
    }

    /** G*: resolve the candidate F·f and accept iff no invariant is violated (contract §15). */
    private function accept(Fact $fact): Decision
    {
        $candidate = Resolution::of([...$this->facts(), $fact]);
        $violations = InvariantSet::violations($candidate);

        return $violations === []
            ? Decision::accepted($fact, $this->version())
            : Decision::refused($violations[0]);
    }

    /** @return list<Fact> */
    private function facts(): array
    {
        return array_map(static fn (RecordedFact $r): Fact => $r->fact(), $this->log);
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
