<?php

namespace App\Contexts\Elections\Application\DTO;

use App\Contexts\Elections\Domain\ValueObjects\CastDecisionReason;

/**
 * CastDecision — the result of VotingAuthorization::forCast(). Derived, never stored.
 *
 * `Allowed` alone does not commit a vote: the commit also needs the compare-and-set on
 * codes.has_voted to change exactly one row, the insert to succeed and the transaction
 * to commit (ADR_20261005_0950 I1).
 */
final readonly class CastDecision
{
    public function __construct(
        public CastDecisionReason $reason,
    ) {}

    public function isAllowed(): bool
    {
        return $this->reason->isAllowed();
    }
}
