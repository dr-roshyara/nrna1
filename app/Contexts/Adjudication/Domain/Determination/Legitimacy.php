<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

/**
 * Legitimacy verdict (50-05 `legitimacy`) — the constitutional legitimacy the
 * adjudicating authority assigns. Computed by the LegitimacyDecision policy
 * (50-06) and supplied to the aggregate at issue(); the aggregate records it.
 */
enum Legitimacy: string
{
    case Legitimate = 'legitimate';
    case Illegitimate = 'illegitimate';
}
