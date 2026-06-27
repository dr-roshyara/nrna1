<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

/**
 * The ruling outcome (50-05 `outcome`). Whether the challenge succeeds; drives
 * the downstream ElectionCorrectionApplied correction type (eventually).
 */
enum DeterminationOutcome: string
{
    case Upheld = 'upheld';     // challenge succeeds → correction follows
    case Dismissed = 'dismissed'; // challenge fails → no correction
}
