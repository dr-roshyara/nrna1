<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Opens a membership period at effectiveAt (contract §17).
 *
 * The optional initial term ends at initialTermUntil (may be ∞). Without it, the
 * period's term is empty and the member is Lapsed (C-S0-2). There is no default term.
 */
final readonly class Admit implements Fact, EffectiveDatedFact
{
    public function __construct(
        private FactId $id,
        private TimePoint $effectiveAt,
        private ?TimePoint $initialTermUntil,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
    ) {
        if ($effectiveAt->isInfinite()) {
            throw new \InvalidArgumentException('An admission takes effect at a finite time.');
        }
    }

    public function id(): FactId
    {
        return $this->id;
    }

    public function periodId(): PeriodId
    {
        return PeriodId::ofAdmit($this->id);
    }

    public function effectiveAt(): TimePoint
    {
        return $this->effectiveAt;
    }

    public function initialTermUntil(): ?TimePoint
    {
        return $this->initialTermUntil;
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
