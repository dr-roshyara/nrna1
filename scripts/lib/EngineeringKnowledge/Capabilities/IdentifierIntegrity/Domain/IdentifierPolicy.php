<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

/**
 * DP-1 — the domain policy this capability executes, stated once.
 *
 *   "Every identifier shall be unique within its register(ns), and checked before minting."
 *
 * Restates PMR-10 (GOVERNED) + AP-4. ⛔ It CREATES no authority and OWNS no rule:
 * the governed rule lives in the PMR register; this is its engineering expression
 * (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 *
 * Separated from the service per the PA's structural direction: the POLICY states the
 * obligation; the SERVICE applies it. A future policy change need not touch the service.
 */
final readonly class IdentifierPolicy
{
    public const STATEMENT =
        'Every identifier shall be unique within its register(ns), and checked before minting.';

    public const GOVERNING_RULE = 'PMR-10';

    public const GOVERNING_PRINCIPLE = 'AP-4';

    /** An identifier may only be evaluated against the series it belongs to (M4: register(ns) is the unit). */
    public function appliesTo(Identifier $identifier, SeriesContents $contents): bool
    {
        return $identifier->series()->equals($contents->series());
    }

    /** Fail-closed: without a governed register there is no criterion, so no PASS is available. */
    public function isEvaluable(SeriesContents $contents): bool
    {
        return $contents->isGoverned();
    }

    public function isViolatedBy(Identifier $identifier, SeriesContents $contents): bool
    {
        return $contents->containsMinted($identifier);
    }

    /** Not yet a collision — a collision HAZARD. The R-65..R-71 case. */
    public function isAtRiskOfViolation(Identifier $identifier, SeriesContents $contents): bool
    {
        return $contents->containsCitedButUnminted($identifier);
    }
}
