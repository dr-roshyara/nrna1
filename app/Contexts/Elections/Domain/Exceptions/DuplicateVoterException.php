<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

final class DuplicateVoterException extends DomainException
{
    public static function forUserInElection(
        string $userId,
        string $electionId
    ): self {
        return new self(
            "User [{$userId}] is already an active voter in election [{$electionId}]"
        );
    }
}
