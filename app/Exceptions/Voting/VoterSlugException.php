<?php

namespace App\Exceptions\Voting;

/**
 * Base exception for voter slug-related errors
 */
abstract class VoterSlugException extends VotingException
{
}

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
        return 400;
    }
}

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
