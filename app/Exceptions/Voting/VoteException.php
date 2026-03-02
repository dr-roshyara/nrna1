<?php

namespace App\Exceptions\Voting;

/**
 * Base exception for vote-related errors
 */
abstract class VoteException extends VotingException
{
}

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
