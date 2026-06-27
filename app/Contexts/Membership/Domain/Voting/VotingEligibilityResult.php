<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Voting;

/**
 * Voting Eligibility Result
 *
 * Cross-context contract DTO that communicates voting eligibility decisions.
 * Returned by VotingEligibilityPolicy to Elections context.
 *
 * This separates policy logic (Membership context) from policy consumption (Elections context).
 */
final readonly class VotingEligibilityResult
{
    private function __construct(
        public bool $canVote,
        public ReasonCode $reasonCode,
    ) {}

    /**
     * Member is eligible for voting.
     */
    public static function eligible(): self
    {
        return new self(
            canVote: true,
            reasonCode: ReasonCode::NONE,
        );
    }

    /**
     * Member is ineligible for voting.
     */
    public static function ineligible(ReasonCode $reason): self
    {
        return new self(
            canVote: false,
            reasonCode: $reason,
        );
    }
}
