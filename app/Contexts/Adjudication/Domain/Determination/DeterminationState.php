<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

/**
 * Determination lifecycle (Round 50-07 v1.2): Draft → Issued → Final.
 * Terminal: Final (immutable; override only via constitutional amendment).
 */
enum DeterminationState: string
{
    case Draft = 'draft';
    case Issued = 'issued';
    case Final = 'final';

    public function isTerminal(): bool
    {
        return $this === self::Final;
    }
}
