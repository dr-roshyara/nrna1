<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge\Exception;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use DomainException;

/**
 * Domain invariant: a Challenge is bound by exactly ONE determination. A *different*
 * determination arriving for an already-adjudicated Challenge violates that invariant —
 * a business condition (never a messaging concept). The Application layer translates it
 * to a permanent messaging outcome; the Domain only states the business fact.
 */
final class ConflictingDetermination extends DomainException
{
    public static function on(ChallengeId $challenge, DeterminationId $existing, DeterminationId $incoming): self
    {
        return new self(sprintf(
            'Challenge "%s" is already bound by determination "%s"; a conflicting determination "%s" cannot be applied.',
            $challenge->toString(),
            $existing->toString(),
            $incoming->toString(),
        ));
    }
}
