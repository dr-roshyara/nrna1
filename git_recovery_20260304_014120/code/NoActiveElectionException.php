<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when no active election is available
 */
class NoActiveElectionException extends ElectionException
{
    public function getUserMessage(): string
    {
        return 'No active elections are available at this time. Please try again later.';
    }

    public function getHttpCode(): int
    {
        return 404;
    }
}
