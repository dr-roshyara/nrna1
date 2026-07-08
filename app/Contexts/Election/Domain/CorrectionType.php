<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

/**
 * The kind of correction the Election applies in reaction to a binding determination.
 *
 * Constitutional bound (ADR-T8/T11): the correction loop is FORWARD-ONLY. Because
 * anonymity forbids ever un-casting or re-attributing votes, the only correction the
 * Election can apply is `ContainedOnly` — it records that a correction occurred and
 * moves the election forward; it never rolls history back. The set is closed here on
 * purpose; a non-forward-only member would require an ADR that revisits ADR-T8.
 */
enum CorrectionType: string
{
    case ContainedOnly = 'contained_only';

    public function isForwardOnly(): bool
    {
        return match ($this) {
            self::ContainedOnly => true,
        };
    }
}
