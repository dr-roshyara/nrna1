<?php

namespace App\Domain\Election\Security\Simplified;

readonly class DeviceEvidence
{
    public function __construct(
        public ?string $fingerprintHash,    // SHA-256 hash or null
        public string $matchType,           // 'exact_match'|'no_match'|'not_required'
        public string $captureMethod,       // 'browser_api'|'canvas'|'none'
        public string $volatility,          // 'stable'|'semi_stable'|'volatile'
    ) {}
}
