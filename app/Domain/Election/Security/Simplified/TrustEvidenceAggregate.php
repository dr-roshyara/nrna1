<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * TrustEvidenceAggregate
 *
 * Single boundary containing all trust evidence.
 * Consolidates NetworkEvidence, DeviceEvidence, VerificationEvidence, SessionContinuity.
 */
readonly class TrustEvidenceAggregate
{
    public function __construct(
        public NetworkEvidence $network,
        public DeviceEvidence $device,
        public VerificationEvidence $attestation,
        public SessionContinuity $continuity,
    ) {}
}
