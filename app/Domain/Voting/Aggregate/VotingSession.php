<?php

declare(strict_types=1);

namespace App\Domain\Voting\Aggregate;

use App\Domain\Voting\ValueObject\EligibilitySnapshot;
use App\Domain\Voting\ValueObject\BallotCollection;
use App\Domain\Voting\ValueObject\VotingOutcome;

final readonly class VotingSession
{
    public function __construct(
        public EligibilitySnapshot $eligibilitySnapshot,
        public BallotCollection $ballots,
        public VotingOutcome $outcome,
        public bool $quorumMet
    ) {}

    public static function create(
        EligibilitySnapshot $eligibilitySnapshot,
        BallotCollection $ballots,
        VotingOutcome $outcome,
        bool $quorumResult
    ): self {
        return new self(
            $eligibilitySnapshot,
            $ballots,
            $outcome,
            $quorumResult
        );
    }

    /**
     * Deterministic fingerprint for replay validation
     * Canonical JSON with sorted fields ensures order-independence
     */
    public function fingerprint(): string
    {
        $data = [
            'eligible_voters' => $this->eligibilitySnapshot->eligibleVoters(),
            'valid_ballots' => $this->outcome->validBallots(),
            'results' => $this->outcome->results(),
            'quorum_met' => $this->quorumMet,
        ];

        return hash(
            'sha256',
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
