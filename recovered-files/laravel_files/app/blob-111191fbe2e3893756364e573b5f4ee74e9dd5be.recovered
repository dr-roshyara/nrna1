<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when election is not found
 */
class ElectionNotFoundException extends ElectionException
{
    public function getUserMessage(): string
    {
        return 'The requested election was not found.';
    }

    public function getHttpCode(): int
    {
        return 404;
    }
}
