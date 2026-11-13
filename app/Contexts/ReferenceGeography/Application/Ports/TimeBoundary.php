<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;

/**
 * The completeness boundary in time, W (v3.11 §6), on its own (G-5a): the read side may close through τ, and can never
 * reach the publication lock's write path (withLock), which stays with PublishRevision.
 */
interface TimeBoundary
{
    /**
     * Closes through τ (or through now): afterwards every publication with recorded_at ≤ the returned W is committed
     * and none can be added. A τ at or below W is already confirmed.
     *
     * @throws BoundaryNotClosed if τ is in the future or the lock is busy
     */
    public function closeThrough(?string $tau): string;
}
