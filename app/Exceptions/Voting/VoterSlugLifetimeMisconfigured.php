<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when voting.time_in_minutes is not a positive integer (PBDIGIT-274, O-4 / O-10 = B: fail closed,
 * no fallback). The reason code is safe to log; the configured value is never exposed to the client.
 */
class VoterSlugLifetimeMisconfigured extends VotingException
{
    public function __construct(private readonly string $reason, string $configuredType)
    {
        parent::__construct('voting.time_in_minutes is invalid', ['reason' => $reason, 'configured_type' => $configuredType]);
    }

    /** One of: missing, not_integer, non_positive. */
    public function reason(): string
    {
        return $this->reason;
    }

    public function getUserMessage(): string
    {
        return 'Voting is temporarily unavailable. Please try again later.';
    }

    public function getHttpCode(): int
    {
        return 503;
    }
}
