<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

/**
 * The binding ruling an adjudication reached on a challenge, as Election understands
 * it (OQ-3). Election reacts only to these outcomes; the string values match the
 * `outcome` carried on `DeterminationIssued` (published language).
 */
enum RulingOutcome: string
{
    case Upheld = 'upheld';
    case Dismissed = 'dismissed';
}
