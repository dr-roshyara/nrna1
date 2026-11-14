<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;

/** TEST ONLY (PBDIGIT-345 task 9): hydrates T9FollowUpRequested from its outbox payload. */
final class T9FollowUpRequestedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'T9FollowUpRequested';
    }

    public function hydrate(array $payload): object
    {
        return new T9FollowUpRequested($payload['organisationId'], $payload['label']);
    }
}
