<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use App\Models\User;

readonly class TrustCapabilityContext
{
    public function __construct(
        public ?Election                     $election,
        public ?User                         $user,
        public NetworkTrustEvidence          $network,
        public DeviceTrustContext            $device,
        public VerificationAttestationRecord $attestation,
        public VotingSessionTrustContinuity  $sessionContinuity,
    ) {}

    // Evidence context only (derives facts, never authority)

    public function currentTrustLevel(): TrustLevel
    {
        return $this->attestation->trustLevel();
    }

    public function continuityPreserved(): bool
    {
        return $this->sessionContinuity->isPreserved();
    }
}
