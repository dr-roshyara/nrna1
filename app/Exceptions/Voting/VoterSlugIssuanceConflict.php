<?php

namespace App\Exceptions\Voting;

use Throwable;

/**
 * VoterSlugIssuer could not settle a unique-key conflict (PBDIGIT-274, plan section 5): an unknown constraint,
 * or a conflict that persisted after the allowed retries. No link is returned. The kind is safe to log; the
 * driver error is kept as the cause for diagnostics and is never shown to the voter.
 */
class VoterSlugIssuanceConflict extends VotingException
{
    public function __construct(private readonly string $kind, private readonly Throwable $cause)
    {
        parent::__construct('voter slug issuance conflict', ['kind' => $kind]);
    }

    /** One of VoterSlugIssuer::CONFLICT_*. */
    public function kind(): string
    {
        return $this->kind;
    }

    public function cause(): Throwable
    {
        return $this->cause;
    }

    public function getUserMessage(): string
    {
        return 'Your voting link could not be prepared just now. Please try again.';
    }

    public function getHttpCode(): int
    {
        return 409;
    }
}
