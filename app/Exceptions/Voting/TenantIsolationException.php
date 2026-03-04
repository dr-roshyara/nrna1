<?php

namespace App\Exceptions\Voting;

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
