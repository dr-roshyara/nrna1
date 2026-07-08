<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

/**
 * Contestation's LOCAL view of the binding ruling on a challenge (ADR-T16: reconstructed
 * from the `DeterminationIssued` wire string; not Adjudication's enum). Drives the
 * Dismissed short-circuit in the adjudication reaction.
 */
enum DeterminationOutcome: string
{
    case Upheld = 'upheld';
    case Dismissed = 'dismissed';
}
