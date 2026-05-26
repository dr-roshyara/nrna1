<?php

namespace App\Domain\Election\Security;

readonly class VerificationAttestationRecord
{
    public function __construct(
        public bool               $required,
        public bool               $attested,
        public ?string            $registrarId,
        public ?\DateTimeImmutable $attestationTimestamp,
        public string             $protocol,
        public ?string            $networkEvidenceHash,
        public ?string            $deviceEvidenceHash,
        public bool               $revoked,
        public TrustValidityScope $validityScope,
    ) {}

    public function isSatisfied(): bool
    {
        if ($this->revoked) {
            return false;
        }

        if (!$this->required) {
            return true;
        }

        return $this->attested;
    }

    public function networkMismatch(NetworkTrustEvidence $current): bool
    {
        if ($this->networkEvidenceHash === null) {
            return false;
        }

        return $this->networkEvidenceHash !== $current->currentIpHash;
    }

    public function deviceMismatch(DeviceTrustContext $current): bool
    {
        if ($this->deviceEvidenceHash === null) {
            return false;
        }

        return $this->deviceEvidenceHash !== $current->fingerprintHash;
    }

    public function trustLevel(): TrustLevel
    {
        if (!$this->attested) {
            return TrustLevel::Unverified;
        }

        if ($this->registrarId !== null) {
            return TrustLevel::RegistrarAttested;
        }

        return TrustLevel::Attested;
    }

    public function isExpired(\DateTimeImmutable $validUntil): bool
    {
        if ($this->attestationTimestamp === null) {
            return true;
        }

        return $this->attestationTimestamp > $validUntil;
    }
}
