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

        return $this->accept(new End($id, $period, $effectiveAt, $kind, $provenance, $decidedAt));
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
}
