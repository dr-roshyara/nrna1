<?php

namespace App\Domain\Election\Security;

readonly class DeviceTrustContext
{
    public function __construct(
        public ?string              $fingerprintHash,
        public ?string              $registeredFingerprintHash,
        public FingerprintMatchType $matchType,
        public string               $captureMethod,
        public string               $volatility,
    ) {}

    public function satisfiesDeviceAttestation(): bool
    {
        return $this->matchType === FingerprintMatchType::ExactMatch
            || $this->matchType === FingerprintMatchType::NotRequired;
    }

    public function isVolatile(): bool
    {
        return $this->volatility === 'volatile';
    }
}
