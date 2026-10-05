<?php

namespace App\Contexts\Elections\Application\Services;

use App\Contexts\Elections\Application\DTO\CastAttempt;
use App\Contexts\Elections\Application\DTO\CastDecision;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\CastDecisionReason;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;

/**
 * VotingAuthorization — pure composition of the existing decisions; holds no state.
 *
 * forCast(): fresh authorization + process validation for one final-cast attempt
 * (ADR_20261005_0950 §3.2). First failure wins, in a fixed order:
 *   1. entitlement — VotingEntitlementQuery::decide() (standing ∧ lifecycle), exactly once (E2-D2);
 *   2. security    — the existing Trust → Legitimacy chain's verdict, passed in as a fact;
 *   3. process     — CodeMissing → AlreadyVotedPreCheck → CodeMismatch → CodeStepIncomplete
 *                    → BallotMissing (E2-D1; changing it needs an architectural decision).
 * It never decides "already voted" authoritatively — only the compare-and-set does (E4).
 * forEntry() is Phase 5.
 */
final class VotingAuthorization
{
    public function __construct(
        private readonly VotingEntitlementQuery $entitlement,
    ) {}

    public function forCast(CastAttempt $attempt): CastDecision
    {
        return new CastDecision($this->firstFailure($attempt) ?? CastDecisionReason::Allowed);
    }

    private function firstFailure(CastAttempt $attempt): ?CastDecisionReason
    {
        $entitlement = $this->entitlement->decide($attempt->voterId, $attempt->electionId);
        if (! $entitlement->isEntitled()) {
            return CastDecisionReason::fromEntitlement($entitlement);
        }

        return match ($attempt->security) {
            SecurityVerdict::Denied         => CastDecisionReason::SecurityDenied,
            SecurityVerdict::ReviewRequired => CastDecisionReason::SecurityReviewRequired,
            SecurityVerdict::Allowed        => $this->processFailure($attempt),
        };
    }

    private function processFailure(CastAttempt $attempt): ?CastDecisionReason
    {
        return match (true) {
            ! $attempt->codeExists           => CastDecisionReason::CodeMissing,
            $attempt->alreadyVotedPreCheck   => CastDecisionReason::AlreadyVotedPreCheck,
            ! $attempt->submittedCodeMatches => CastDecisionReason::CodeMismatch,
            ! $attempt->codeStepCompleted    => CastDecisionReason::CodeStepIncomplete,
            ! $attempt->ballotPresent        => CastDecisionReason::BallotMissing,
            default                          => null,
        };
    }
}
