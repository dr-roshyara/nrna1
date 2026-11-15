<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure\Outbox;

use App\Contexts\Identity\Application\Published\ClaimRedeemed;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;

/** PBDIGIT-349 I-3: hydrates ClaimRedeemed from its central outbox payload. */
final class ClaimRedeemedHydrator implements EventHydrator
{
    public const EVENT_TYPE = 'ClaimRedeemed';

    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }

    public function hydrate(array $payload): object
    {
        return new ClaimRedeemed($payload['organisationId'], $payload['personRef'], $payload['claimId'], $payload['boundAt']);
    }
}
