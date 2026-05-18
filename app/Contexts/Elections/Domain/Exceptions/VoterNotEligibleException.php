<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

final class VoterNotEligibleException extends DomainException
{
    public static function forUserInOrganisation(
        string $userId,
        string $organisationId,
        string $mode
    ): self {
        return new self(
            "User [{$userId}] is not eligible to vote in organisation [{$organisationId}] in mode [{$mode}]"
        );
    }
}
