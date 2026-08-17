<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

/**
 * D-7 marker: this event's TYPE NAME is a NON-CANONICAL PLACEHOLDER. The canonical
 * protocol-event vocabulary is expressly open (EM-OPEN-045: "an illustration is not
 * a rule"); technical type naming is Architecture's mapping (registered DDD
 * separation, EM-GOV-069 registration §4) and creates no business vocabulary.
 * The recorded FACT each event carries is fixed by adopted text (design §5f);
 * business-facing rendering awaits EM-OPEN-045's resolution as a rendering concern,
 * never a rewrite of recorded facts (EM-GOV-005 — history is never reset).
 */
interface NonCanonicalEventName
{
}
