<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when user has already voted
 */
class AlreadyVotedException extends VoteException
{
    public function getUserMessage(): string
    {
        return 'You have already submitted your vote in this election. Duplicate votes are not allowed.';
    }

    public function getHttpCode(): int
    {
        return 403;
    }
}
