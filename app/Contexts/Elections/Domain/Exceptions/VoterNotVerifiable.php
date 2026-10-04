<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

/**
 * Only a registered voter of this election — voter role, not deleted, not removed —
 * can be identity-verified (ADR_20261004_1549 D3, PO decision C3-D2).
 */
final class VoterNotVerifiable extends DomainException
{
    public static function inElection(string $voterId, string $electionId): self
    {
        return new self("User [{$voterId}] is not a registered voter of election [{$electionId}] and cannot be verified.");
    }
}
