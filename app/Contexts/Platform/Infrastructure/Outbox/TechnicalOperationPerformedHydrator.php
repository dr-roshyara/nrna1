<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Outbox;

use App\Contexts\Platform\Application\Published\TechnicalOperationPerformed;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;

/** PBDIGIT-345 task 11: hydrates the organisation's operator-channel report from its outbox payload. */
final class TechnicalOperationPerformedHydrator implements EventHydrator
{
    public const EVENT_TYPE = 'TechnicalOperationPerformed';

    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }

    public function hydrate(array $payload): object
    {
        return new TechnicalOperationPerformed($payload['operationId'], $payload['organisationId'], $payload['operation'], $payload['operator'],
            $payload['outcome'], $payload['reason'], $payload['requestedAt']);
    }
}
