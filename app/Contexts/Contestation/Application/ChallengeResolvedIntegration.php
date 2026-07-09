<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

use App\Contexts\Contestation\Domain\Events\ChallengeResolved;

/**
 * The Application's explicit integration enrichment for a resolved challenge (F-2 seam,
 * emergent from RED): it pairs the **unchanged minimal domain event** with the
 * `Resolution` the Application supplies. The outbox adapter maps this to the published
 * `ChallengeResolved` payload (which carries `resolution`); the domain event itself is
 * never modified. This is the single, minimal carrier the RED forced — no envelope, no DTO
 * hierarchy. @immutable
 */
final readonly class ChallengeResolvedIntegration
{
    public function __construct(
        public ChallengeResolved $event,
        public Resolution $resolution,
    ) {
    }
}
