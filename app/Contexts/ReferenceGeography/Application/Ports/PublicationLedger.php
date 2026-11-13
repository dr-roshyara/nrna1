<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;

/**
 * The Geography knowledge stream (v3.11 §6). ONE lock for publication and every roster write (the same lock the
 * roster store takes), held from the governance re-check to commit.
 */
interface PublicationLedger
{
    /**
     * Runs $work under the publication lock in one transaction; commits if it returns, rolls back if it throws.
     *
     * @template T
     * @param \Closure(LockedLedger): T $work
     * @return T
     */
    public function withLock(\Closure $work): mixed;

    /**
     * Closes through τ (or through now): afterwards every publication with recorded_at ≤ the returned W is committed
     * and none can be added. A τ at or below W is already confirmed.
     *
     * @throws BoundaryNotClosed if τ is in the future or the lock is busy
     */
    public function closeThrough(?string $tau): string;
}
