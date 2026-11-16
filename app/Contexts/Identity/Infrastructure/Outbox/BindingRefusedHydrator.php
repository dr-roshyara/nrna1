<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure\Outbox;

use App\Contexts\Identity\Application\Published\BindingRefused;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;

/** PBDIGIT-349 I-3: hydrates BindingRefused from its payload in Identity's outbox (identity_outbox_events). */
final class BindingRefusedHydrator implements EventHydrator
{
    public const EVENT_TYPE = 'BindingRefused';

    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }

    public function hydrate(array $payload): object
    {
        return new BindingRefused($payload['organisationId'], $payload['personRef']);
    }
}
