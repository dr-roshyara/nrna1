<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when organisation IDs don't match (Golden Rule violation)
 *
 * The Golden Rule: VoterSlug.organisation_id MUST match Election.organisation_id
 * UNLESS Election.organisation_id = 1 (Platform) OR User.organisation_id = 1 (Platform)
 */
class OrganisationMismatchException extends ConsistencyException
{
    public function getUserMessage(): string
    {
        return 'Your organisation context does not match this election. Please contact support.';
    }

    public function getHttpCode(): int
    {
        return 500;
    }
}
