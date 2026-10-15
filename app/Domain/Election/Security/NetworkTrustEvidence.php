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
        // PBDIGIT-247 IP Binding: null = binding not required; true / false = the request IP is / is not the
        // voter's captured IP in this election. Only the fact enters the evidence — never an address (as Q3-D5).
        public ?bool   $ipBindingSatisfied = null,
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
        // PBDIGIT-286 D7: votesFromThisIp counts completed votes from this network (the current voter not yet
        // included), so the next voter is refused once L completed votes exist. `>` admitted an (L + 1)th vote.
        $effectiveLimit = $this->effectiveLimit($trustLevel);
        return $this->votesFromThisIp >= $effectiveLimit;
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
        // Priority 4: Integer-Only Threshold Math (Deterministic Governance)
        // All calculations use integer-only operations, never floating point
        // Same input always produces same threshold (replay-deterministic)
        //
        // Thresholds (for maxVotesPerIp = N):
        // - RegistrarAttested: N * 2 = 2N (double the base)
        // - ContinuityVerified: N * 1.5 = N*3/2 (intdiv for reproducibility)
        // - Attested: N (baseline)
        // - Unverified: N / 2 = intdiv(N, 2) (half, minimum 1)

        return match ($trustLevel) {
            TrustLevel::RegistrarAttested => $this->maxVotesPerIp * 2,
            TrustLevel::ContinuityVerified => intdiv($this->maxVotesPerIp * 3, 2),
            TrustLevel::Attested => $this->maxVotesPerIp,
            TrustLevel::Unverified => max(1, intdiv($this->maxVotesPerIp, 2)),
        };
    }
}
