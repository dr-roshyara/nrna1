<?php

namespace App\Exceptions\Voting;

/**
 * Base exception for data consistency errors
 *
 * These exceptions indicate that the voting system has detected
 * inconsistent state that violates architectural constraints.
 */
abstract class ConsistencyException extends VotingException
{
}

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

/**
 * Thrown when election data is inconsistent with voter slug
 */
class ElectionMismatchException extends ConsistencyException
{
    public function getUserMessage(): string
    {
        return 'Election data is inconsistent. Please contact support.';
    }

    public function getHttpCode(): int
    {
        return 500;
    }
}

/**
 * Thrown when tenant isolation is violated
 */
class TenantIsolationException extends ConsistencyException
{
    public function getUserMessage(): string
    {
        return 'Tenant isolation violation detected. This incident has been logged.';
    }

    public function getHttpCode(): int
    {
        return 500;
    }
}
