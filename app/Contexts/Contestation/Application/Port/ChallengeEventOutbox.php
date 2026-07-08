<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Port;

/**
 * Application port through which the Contestation reactions publish the domain events they
 * decide (`ChallengeAdjudicated`, `ChallengeResolved`). Transport-agnostic; the concrete
 * adapter (5C) records them on the transactional outbox in the inbox transaction (ADR-T1).
 */
interface ChallengeEventOutbox
{
    public function enqueue(object ...$events): void;
}
