<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

/**
 * The kind of ContestedOutcome a Challenge contests (ADR-UL-01). Closed set;
 * extensible only by an ADR-UL evolution. No vote content.
 */
enum TargetType: string
{
    case ElectionResult = 'election_result';
    case Determination = 'determination';
}
