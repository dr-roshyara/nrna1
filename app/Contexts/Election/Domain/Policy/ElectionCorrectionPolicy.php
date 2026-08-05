<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\Policy;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\RulingOutcome;

/**
 * Business policy owned by the Election context (OQ-3): given the binding ruling on a
 * challenge, decide whether — and how — the election must be corrected.
 *
 *  - Dismissed → no correction is required (D-02): returns null.
 *  - Upheld    → a forward-only, anonymity-bounded correction (ADR-T8): ContainedOnly.
 *
 * Pure domain behavior — no framework, no persistence, no transport.
 */
final class ElectionCorrectionPolicy
{
    public function decide(RulingOutcome $outcome): ?CorrectionType
    {
        return match ($outcome) {
            RulingOutcome::Upheld => CorrectionType::ContainedOnly,
            RulingOutcome::Dismissed => null,
        };
    }
}
