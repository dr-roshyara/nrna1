<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when vote verification fails
 */
class VoteVerificationException extends VoteException
{
    public function getUserMessage(): string
    {
        return 'Your vote could not be verified. Please try again or contact support.';
    }

    public function getHttpCode(): int
    {
        return 400;
    }
}
