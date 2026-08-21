<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

/**
 * DP-4 — the domain policy this capability executes, stated once.
 *
 *   "A reference shall resolve to an existing target, or be classified as evidence —
 *    never repaired on a guess."
 *
 * Restates the ≥99 confidence bar (GOVERNED) that the catalogue attributes to CAP-004,
 * the same reference-integrity discipline `link-check.php` and `doc-placement.php`
 * already realize. ⛔ It CREATES no authority and OWNS no rule: the governed rule lives
 * in the catalogue; this is its engineering expression
 * (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 *
 * The S2 discriminator is DP-4's OWN escape hatch, not a new policy:
 *   a reference whose target exists exactly once   → RESOLVED
 *   a reference whose target exists more than once → AMBIGUOUS (FAIL)
 *   a reference with no local target               → CLASSIFIED AS EVIDENCE (NOT-CHECKED,
 *                                                    never a defect — it may be
 *                                                    cross-document, e.g. `its §13`)
 *
 * Separated from the service per the PA's structural direction: the POLICY states the
 * obligation; the SERVICE applies it. A future policy change need not touch the service.
 */
final readonly class ReferencePolicy
{
    public const STATEMENT =
        'A reference shall resolve to an existing target, or be classified as evidence — '
        .'never repaired on a guess.';

    public const GOVERNING_RULE = 'the ≥99 confidence bar';
}
