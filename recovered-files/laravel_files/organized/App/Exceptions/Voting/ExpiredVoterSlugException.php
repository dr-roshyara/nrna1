<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when voter slug has expired
 */
class ExpiredVoterSlugException extends VoterSlugException
{
    public function getUserMessage(): string
    {
        return 'Your voting session has expired. Please request a new voting link.';
    }

    public function getHttpCode(): int
    {
        return 403;
    }
}
