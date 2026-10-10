<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Capability of a Fact that carries a valid-time value: Admit and End (correction
 * contract §3; plan P-1). Implemented alongside Fact (no interface inheritance: the
 * kernel stays free of `extends`, Slice 0 fitness rule).
 *
 * Reversal facts (Annul, Void) have no valid time of their own and therefore do not
 * implement this interface; none is invented for them (D-COR-5 (A)).
 */
interface EffectiveDatedFact
{
    public function effectiveAt(): TimePoint;
}
