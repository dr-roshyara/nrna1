<?php

namespace App\Domain\Election\Security\BallotAuthorization;

/**
 * AuthorizationCode (Value Object)
 *
 * Immutable representation of a single ballot authorization code state.
 */
readonly class AuthorizationCode
{
    public function __construct(
        public string $code,
        public bool $isUsed,
        public ?\DateTimeImmutable $usedAt,
    ) {}
}
