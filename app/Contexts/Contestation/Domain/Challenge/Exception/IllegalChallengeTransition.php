<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge\Exception;

use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use DomainException;

/**
 * Thrown when a forbidden state transition is attempted (Round 50-07 v1.2
 * illegal-transition policy): DomainException + NO state mutation. The
 * application layer records the attempt to Audit as a security event.
 */
final class IllegalChallengeTransition extends DomainException
{
    public static function from(ChallengeState $current, string $command): self
    {
        return new self(sprintf(
            'Illegal challenge transition: cannot "%s" from state "%s".',
            $command,
            $current->value
        ));
    }
}
