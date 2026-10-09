<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * An immutable membership fact. Slice 0 has exactly two: Admit and End.
 * Facts carry no holder or organisation: those belong to the Member (INV-1).
 */
interface Fact
{
    public function id(): FactId;

    public function effectiveAt(): TimePoint;

    public function decidedAt(): ?Instant;

    public function provenance(): Provenance;
}
