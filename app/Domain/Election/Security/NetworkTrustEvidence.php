<?php

namespace App\Domain\Election\Security;

readonly class NetworkTrustEvidence
{
    public function __construct(
        public string  $currentIpHash,
        public ?string $registeredIpHash,
        public ?array  $whitelist,
        public int     $maxVotesPerIp,
        public int     $votesFromThisIp,
        public bool    $restrictionEnabled,
        public string  $bindingStrategy,
    ) {}

    public function isWhitelisted(): bool
    {
        if ($this->whitelist === null || empty($this->whitelist)) {
            return false;
        }

        return in_array($this->currentIpHash, $this->whitelist, strict: true);
    }

    public function exceedsLimit(TrustLevel $trustLevel): bool
    {
        $effectiveLimit = $this->effectiveLimit($trustLevel);
        return $this->votesFromThisIp > $effectiveLimit;
    }

    public function satisfiesNetworkAttestation(): bool
    {
        if (!$this->restrictionEnabled) {
            return true;
        }

        return $this->currentIpHash === $this->registeredIpHash;
    }

    public function remainingVotes(TrustLevel $trustLevel): int
    {
        $effectiveLimit = $this->effectiveLimit($trustLevel);
        $remaining = $effectiveLimit - $this->votesFromThisIp;

        return max(0, $remaining);
    }

    private function effectiveLimit(TrustLevel $trustLevel): int
    {
        return match ($trustLevel) {
            TrustLevel::RegistrarAttested => (int) floor($this->maxVotesPerIp * 2),
            TrustLevel::ContinuityVerified => (int) floor($this->maxVotesPerIp * 1.5),
            TrustLevel::Attested => $this->maxVotesPerIp,
            TrustLevel::Unverified => max(1, (int) floor($this->maxVotesPerIp / 2)),
        };
    }
}
