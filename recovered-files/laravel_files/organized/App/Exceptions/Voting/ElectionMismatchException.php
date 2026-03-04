<?php

namespace App\Exceptions\Voting;

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
