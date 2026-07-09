<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Port;

use App\Contexts\Shared\Application\Messaging\EventProvenance;

/**
 * Application port through which the Contestation reactions publish the domain events they
 * decide (`ChallengeAdjudicated`, `ChallengeResolved`). Transport-agnostic; the concrete
 * adapter (5C) records them on the transactional outbox in the inbox transaction (ADR-T1).
 */
interface ChallengeEventOutbox
{
    /**
     * Provenance is supplied EXPLICITLY at publish time (ADR-MP-06 invariant): the
     * reaction propagates the consumed message's correlation and records it as the cause.
     */
    public function enqueue(EventProvenance $provenance, object ...$events): void;
}
