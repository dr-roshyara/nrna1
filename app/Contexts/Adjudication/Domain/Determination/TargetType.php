<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

/**
 * The kind of ContestedOutcome a Determination rules on (ADR-UL-01).
 *
 * CLOSED SET (closed by ADR-UL). Adding a case is a Published Language change —
 * do NOT add one without an ADR-UL evolution (ER-06). Mirror of Contestation's
 * TargetType, reconstructed locally per ADR-T16. No vote content (ADR-T11).
 */
enum TargetType: string
{
    case ElectionResult = 'election_result';
    case Determination = 'determination';
}
