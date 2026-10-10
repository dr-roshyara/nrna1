<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * An immutable membership fact. Facts carry no holder or organisation: those belong
 * to the Member (INV-1). Facts with a valid-time value implement EffectiveDatedFact.
 */
interface Fact
{
    public function id(): FactId;

    public function decidedAt(): ?Instant;

    public function provenance(): Provenance;
}
