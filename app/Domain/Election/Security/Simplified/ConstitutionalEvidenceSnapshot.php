<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * ConstitutionalEvidenceSnapshot
 *
 * Frozen replay-safe evaluation input containing all evidence and constitution.
 * Same input → same output determinism.
 * NEVER carries authority decisions. NEVER has behavioral methods.
 */
readonly class ConstitutionalEvidenceSnapshot
{
    public function __construct(
        public ElectionConstitutionSnapshot $constitution,
        public VerificationEvidence $verification,
        public NetworkEvidence $network,
        public DeviceEvidence $device,
        public SessionContinuity $continuity,
        public ParticipationEligibilityEvidence $eligibility,
        public \DateTimeImmutable $evaluatedAt,
        public string $constitutionalHash,
    ) {}
}
