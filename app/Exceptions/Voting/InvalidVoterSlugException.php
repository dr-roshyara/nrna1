<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when voter slug is invalid or malformed
 */
class InvalidVoterSlugException extends VoterSlugException
{
    public function getUserMessage(): string
    {
        return 'Invalid voting session. Please request a new voting link.';
    }

    public function getHttpCode(): int
    {
        return 404;
    }
}
