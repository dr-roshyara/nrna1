<?php

declare(strict_types=1);

namespace App\Domain\Voting\Service;

use App\Domain\Voting\Aggregate\VotingSession;
use App\Domain\Voting\ValueObject\BallotCollection;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;
use App\Domain\Voting\ValueObject\QuorumDefinition;
use App\Domain\Voting\ValueObject\VotingOutcome;

final class VotingEngine
{
    public function __construct(
        private EligibilityEvaluator $eligibilityEvaluator,
        private VoteAggregator $voteAggregator,
        private QuorumCalculator $quorumCalculator
    ) {}

    /**
     * Execute voting process deterministically
     *
     * Orchestrates:
     * 1. Eligibility evaluation (read-only)
     * 2. Vote aggregation (normalize, deduplicate)
     * 3. Quorum calculation (deterministic comparison)
     * 4. Outcome computation (pure function)
     * 5. Session assembly (immutable result)
     *
     * @return VotingSession immutable result
     */
    public function execute(
        EligibilitySnapshot $eligibility,
        BallotCollection $ballots,
        QuorumDefinition $quorum
    ): VotingSession {
        // 1. Determine eligible voters (deterministic read-only)
        $eligibleVoters = $this->eligibilityEvaluator->evaluate($eligibility);

        // 2. Aggregate votes: filter + normalize + deduplicate
        $validVotes = $this->voteAggregator->aggregate($ballots, $eligibility);

        // 3. Quorum calculation (deterministic comparison)
        $quorumMet = $this->quorumCalculator->evaluate($validVotes, $quorum);

        // 4. Outcome computation (pure function result)
        $outcome = new VotingOutcome($validVotes);

        // 5. Session assembly (NO side effects, NO timestamps, immutable)
        return VotingSession::create(
            eligibilitySnapshot: $eligibility,
            ballots: $ballots,
            outcome: $outcome,
            quorumResult: $quorumMet
        );
    }
}
