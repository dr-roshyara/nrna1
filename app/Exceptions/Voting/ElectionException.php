<?php

namespace App\Exceptions\Voting;

/**
 * Base exception for election-related errors
 */
abstract class ElectionException extends VotingException
{
}

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
