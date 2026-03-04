<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when voter slug doesn't belong to the current user
 */
class SlugOwnershipException extends VoterSlugException
{
    public function getUserMessage(): string
    {
        return 'This voting session does not belong to you.';
    }

    public function getHttpCode(): int
    {
        return 403;
    }
}
