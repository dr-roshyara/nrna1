<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Gate;

/**
 * Which of the TWO election-wide acceptance gates a decision belongs to
 * (EM-GOV-016 fixes the number and election-wide scope — and nothing more).
 * The gate's decision SUBJECT is deliberately abstract (D-3; EM-OPEN-054 Q3, 055):
 * these values carry order only, never a subject. Binding a concrete first gate
 * is a configuration/deployment-side act, separately not authorized.
 */
enum GateDesignation: string
{
    case First = 'first';
    case Second = 'second';
}
