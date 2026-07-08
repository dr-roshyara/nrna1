<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\Exception;

use DomainException;

/**
 * A delivered `DeterminationIssued` is historically valid but does not carry the
 * election scope (the `ContestedOutcome`) this consumer requires — a BUSINESS
 * INCOMPATIBILITY, not corruption and not a transient fault (ARB round-2 ruling).
 *
 * A schema-version-1 payload (which predates the additive election scope) is the
 * canonical case: valid when it was written, but insufficient for the Election
 * reaction. Infrastructure later maps this to a dead-letter/incident; it is NOT
 * retried and is NOT treated as a corrupt message.
 */
final class DeterminationLacksElectionScope extends DomainException
{
    public static function forEvent(string $eventId): self
    {
        return new self(sprintf(
            'DeterminationIssued "%s" carries no election scope (ContestedOutcome); '
            . 'it cannot drive an Election correction.',
            $eventId,
        ));
    }
}
