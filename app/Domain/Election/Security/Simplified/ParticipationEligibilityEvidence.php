<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * ParticipationEligibilityEvidence
 *
 * Frozen replay-addressable observational evidence about a voter's
 * constitutional participation eligibility at the time of evaluation.
 *
 * This is NOT an authority decision — it is observational evidence
 * that the Resolver uses to derive participation authority.
 *
 * The eligibility hash enables replay systems to detect when runtime
 * eligibility state diverged from the frozen snapshot.
 */
readonly class ParticipationEligibilityEvidence
{
    public function __construct(
        public bool $hasActiveMembership,
        public bool $hasValidAssignment,
        public bool $hasApproval,
        public bool $isSuspended,
        public ?\DateTimeImmutable $eligibilityEvaluatedAt,
        public string $eligibilitySourceVersion,
        public string $eligibilityHash,
    ) {}
}
