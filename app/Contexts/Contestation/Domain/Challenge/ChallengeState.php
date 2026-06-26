<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

/**
 * Challenge lifecycle states (Round 50-07 v1.2).
 * Terminal: Resolved, Dismissed, Lapsed.
 */
enum ChallengeState: string
{
    case Raised = 'raised';
    case Admitted = 'admitted';
    case Routed = 'routed';
    case Resolved = 'resolved';
    case Dismissed = 'dismissed';
    case Lapsed = 'lapsed';

    public function isTerminal(): bool
    {
        return in_array($this, [self::Resolved, self::Dismissed, self::Lapsed], true);
    }
}
