<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\IdentityUnavailable;

/**
 * INV-ID-9 (D-197): failed redemption attempts are throttled per code and per client. Throttling slows; it never
 * disables a claim (no automatic lock, P-A4 deferred). The limits are configuration (values [TU]).
 */
interface RedemptionThrottle
{
    /** @throws IdentityUnavailable */
    public function isThrottled(string $codeKey, string $clientKey): bool;

    /** @throws IdentityUnavailable */
    public function recordFailure(string $codeKey, string $clientKey): void;
}
