<?php

namespace App\Domain\Election\Security;

readonly class VotingSessionTrustContinuity
{
    public function __construct(
        public string $sessionId,
        public string $ipHashAtStart,
        public string $ipHashCurrent,
        public bool   $deviceChanged,
        public string $continuityState,
    ) {}

    public function isPreserved(): bool
    {
        return $this->continuityState === 'continuous'
            && $this->ipHashAtStart === $this->ipHashCurrent
            && !$this->deviceChanged;
    }

    public function requiresReAttestation(): bool
    {
        return $this->ipHashAtStart !== $this->ipHashCurrent
            || $this->deviceChanged;
    }

    public function isInvalidated(): bool
    {
        return $this->continuityState === 'invalidated';
    }
}
