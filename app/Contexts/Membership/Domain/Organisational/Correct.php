<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Correction contract §3.3 (C-2, PBDIGIT-327): replaces the date of an earlier Admit, End or Correct of the same
 * Member. D-COR-3: the date is the only attribute a correction can carry. The target stays in the log; the latest
 * live Correct of a fact gives its value (value precedence, contract §10a Correct (2)).
 *
 * Not an EffectiveDatedFact yet: the storable-range check (R-1a-6) for the corrected value belongs to persistence
 * (COR-4), which must extend StorableValidTime when it stores this fact.
 */
final readonly class Correct implements Fact
{
    /** @param list<TrimConsequence> $consequences frozen at decision time (g2, FP §12.4 option C) */
    public function __construct(
        private FactId $id,
        private FactId $target,
        private TimePoint $value,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
        private array $consequences = [],
    ) {
        if ($value->isInfinite()) {
            throw new \InvalidArgumentException('A corrected date is a finite time.');
        }
        foreach ($consequences as $consequence) {
            if (!$consequence instanceof TrimConsequence) {
                throw new \InvalidArgumentException('A consequence is a TrimConsequence.');
            }
        }
    }

    /** @return list<TrimConsequence> at most one per trimmed period; each is one PeriodTrimmed (§10a Correct (10)) */
    public function consequences(): array
    {
        return $this->consequences;
    }

    public function id(): FactId
    {
        return $this->id;
    }

    /** The Admit, End or Correct whose date this fact replaces. */
    public function target(): FactId
    {
        return $this->target;
    }

    /** The corrected valid-time date. */
    public function value(): TimePoint
    {
        return $this->value;
    }

    public function decidedAt(): ?Instant
    {
        return $this->decidedAt;
    }

    public function provenance(): Provenance
    {
        return $this->provenance;
    }
}
