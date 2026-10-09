<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Ends a membership period at effectiveAt (contract §18). It only shrinks the
 * period's base claim. The kind is opaque (BD-3).
 */
final readonly class End implements Fact
{
    public function __construct(
        private FactId $id,
        private PeriodId $period,
        private TimePoint $effectiveAt,
        private EndKind $kind,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
    ) {
        if ($effectiveAt->isInfinite()) {
            throw new \InvalidArgumentException('An end takes effect at a finite time.');
        }
    }

    public function id(): FactId
    {
        return $this->id;
    }

    public function period(): PeriodId
    {
        return $this->period;
    }

    public function effectiveAt(): TimePoint
    {
        return $this->effectiveAt;
    }

    public function kind(): EndKind
    {
        return $this->kind;
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
