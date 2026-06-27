<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * NetworkEvidence (Simplified Value Object)
 *
 * Immutable evidence about voter's IP/network participation.
 * Note: IP hash is permanent; raw IP never persists in this object.
 */
readonly class NetworkEvidence
{
    public function __construct(
        public string $currentIpHash,       // SHA-256 normalized hash
        public int $maxVotesPerIp,
        public int $votesFromThisIp,
        public bool $restrictionEnabled,
        public string $bindingStrategy,    // 'none'|'ip_count'|'ip_strict'|'whitelist_only'
    ) {}
}
