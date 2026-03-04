<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when no demo election is available
 */
class NoDemoElectionException extends ElectionException
{
    public function getUserMessage(): string
    {
        return 'No demo election is currently available. Please contact your administrator.';
    }

    public function getHttpCode(): int
    {
        return 404;
    }
}
