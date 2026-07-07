<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

/**
 * The kind of ContestedOutcome a Challenge contests (ADR-UL-01).
 *
 * CLOSED SET (closed by ADR-UL). Adding a case (e.g. Membership) is a
 * **Published Language change** — it flows into `DeterminationIssued v2`
 * (ADR-PL-01) and every consumer. Do NOT add a case without an **ADR-UL**
 * evolution (ER-06). No vote content (anonymity, ADR-T11).
 */
enum TargetType: string
{
    case ElectionResult = 'election_result';
    case Determination = 'determination';
}
