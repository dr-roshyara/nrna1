<?php

namespace App\Domain\Election\Security\VoterVerification;

/**
 * VerificationSession (Value Object)
 *
 * Immutable snapshot of voter evidence verification state.
 * The officer is evidence attestation authority — NOT voting authority.
 *
 * This subdomain tracks whether officer evidence capture is complete.
 * It does NOT authorize participation — that belongs to the Resolver.
 */
readonly class VerificationSession
{
    public function __construct(
        public string $sessionId,
        public bool $isRequired,         // Does this election require officer verification?
        public bool $isComplete,         // Has officer completed evidence capture?
        public ?string $officerId,       // Which officer performed verification
        public ?\DateTimeImmutable $completedAt,  // When verification was completed
    ) {}
}
