<?php

namespace App\Domain\Election\Security\BallotAuthorization;

/**
 * BallotSession (Value Object)
 *
 * Immutable snapshot of the ballot authorization state for a voting session.
 * Describes which codes are available and whether protocol steps are complete.
 *
 * This is NOT a trust concept. Ballot authorization is an independent
 * sovereignty dimension orthogonal to trust evaluation.
 */
readonly class BallotSession
{
    public function __construct(
        public string $sessionId,
        public string $protocol,       // 'single_code' | 'dual_code'
        public array $codes,           // AuthorizationCode[]
        public bool $viewCompleted,    // dual_code: view step completed?
    ) {}
}
