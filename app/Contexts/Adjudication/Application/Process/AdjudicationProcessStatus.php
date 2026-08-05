<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process;

/**
 * The six business states of an adjudication process (EPIC-004K §5).
 *
 * The set is CLOSED: no finer gradation is minted, because the recorded business
 * evidence names assembly, decision, conclusion and expiry — anything between
 * them would be invented, not discovered (ASP). Adding a seventh case is
 * therefore an architectural act, not a coding one.
 *
 * Traceability: EPIC-004K §5 (states) · §6 (terminality) · ASP.
 */
enum AdjudicationProcessStatus: string
{
    /** The request for adjudication of a routed challenge has been received. */
    case Opened = 'opened';

    /** Evidence admissions are being received; demands may be outstanding. */
    case Assembling = 'assembling';

    /** The assembled basis is before the constitutional authority; the process holds. */
    case AwaitingDecision = 'awaiting_decision';

    /** The authority decided; issuance was requested with the fixed considered set. */
    case ConcludedRulingRequested = 'concluded_ruling_requested';

    /** The authority found the evidence insufficient; the failure is declared. */
    case ConcludedFailureDeclared = 'concluded_failure_declared';

    /** The horizon elapsed without a conclusion — a distinct terminal fact. */
    case Expired = 'expired';

    /**
     * Terminal states admit no further conduct: exactly one conclusion of exactly
     * one kind, or an expiry — never more than one, never a mix (EPIC-004K §6).
     */
    public function isTerminal(): bool
    {
        return match ($this) {
            self::ConcludedRulingRequested,
            self::ConcludedFailureDeclared,
            self::Expired => true,
            default => false,
        };
    }
}
