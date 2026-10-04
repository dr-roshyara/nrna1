<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

/**
 * Only the Chief or Deputy Chief of THIS election may create or revoke identity
 * evidence (ADR_20261004_1549 D2).
 */
final class VerifierNotAuthorised extends DomainException
{
    public static function forElection(string $userId, string $electionId): self
    {
        return new self("User [{$userId}] holds no leadership role in election [{$electionId}].");
    }
}
