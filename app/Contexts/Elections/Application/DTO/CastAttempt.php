<?php

namespace App\Contexts\Elections\Application\DTO;

use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;

/**
 * CastAttempt — the facts of one final-cast attempt, read inside the cast transaction.
 *
 * Input of VotingAuthorization::forCast(). Process facts are read, never written:
 * `codeStepCompleted` is Code.can_vote_now and `alreadyVotedPreCheck` is Code.has_voted
 * (ADR_20261005_0950 I5). Agreement is not a fact here (Q5 = A).
 */
final readonly class CastAttempt
{
    public function __construct(
        public string $voterId,
        public string $electionId,
        public SecurityVerdict $security,
        public bool $codeExists,
        public bool $submittedCodeMatches,
        public bool $codeStepCompleted,
        public bool $alreadyVotedPreCheck,
        public bool $ballotPresent,
    ) {}
}
