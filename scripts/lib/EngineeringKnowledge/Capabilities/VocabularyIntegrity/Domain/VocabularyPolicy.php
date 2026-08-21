<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

/**
 * DP-3 — the domain policy this capability executes, stated once.
 *
 *   "A governed term shall carry one meaning per context; where overloaded, it shall
 *    be qualified."
 *
 * Restates the SD-3 · F-BCP-4 obligation that the catalogue attributes to CAP-003 —
 * the same vocabulary-integrity discipline the plan's §6 findings (DI-2, DI-7) expose.
 * ⛔ It CREATES no authority and OWNS no rule: the governed rule lives in the
 * catalogue; this is its engineering expression
 * (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 *
 * The S3 discriminators are DP-3's OWN escape hatch — "shall be qualified" — applied
 * to the two overload shapes the corpus actually exhibits:
 *   a retired term used LIVE       → an UNQUALIFIED overload (DI-2: AMD4 `Phase 2b`)
 *   a retired term CITED           → qualified (quoted / `§N Phase 2b` / `Traceability (`)
 *   a confusable glyph pair        → an UNQUALIFIED overload (DI-7: AMD5 `CASE β`/`CASE B`)
 *     whose collision is undeclared
 *   the same pair, collision
 *     DECLARED by the document     → qualified (AMD6's `DI-7` finding)
 *
 * Separated from the service per the PA's structural direction: the POLICY states the
 * obligation; the SERVICE applies it. A future policy change need not touch the service.
 */
final readonly class VocabularyPolicy
{
    public const STATEMENT =
        'A governed term shall carry one meaning per context; where overloaded, it shall '
        .'be qualified.';

    public const GOVERNING_RULE = 'SD-3 · F-BCP-4';
}
