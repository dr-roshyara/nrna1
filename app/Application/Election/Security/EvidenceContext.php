<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use App\Models\User;

/**
 * EvidenceContext
 *
 * Evidence facts container for the OLD pipeline evaluation.
 * Carries hashed evidence only — no authority derivation methods.
 *
 * Renamed from TrustCapabilityContext per C10.
 * This class will be removed alongside the OLD pipeline in Phase E.3 cutover.
 *
 * Evidence context only (derives facts, never authority)
 */
readonly class EvidenceContext
{
    public function __construct(
        public ?Election                     $election,
        public ?User                         $user,
        public NetworkTrustEvidence          $network,
        public DeviceTrustContext            $device,
        public VerificationAttestationRecord $attestation,
        public VotingSessionTrustContinuity  $sessionContinuity,
    ) {}

    public function currentTrustLevel(): TrustLevel
    {
        return $this->attestation->trustLevel();
    }

    public function continuityPreserved(): bool
    {
        return $this->sessionContinuity->isPreserved();
    }
}
