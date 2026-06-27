<?php

declare(strict_types=1);

namespace App\Shared\Domain\Events;

use Illuminate\Support\Str;

abstract class AbstractDomainEvent
{
    public readonly string $eventId;

    public readonly \DateTimeImmutable $occurredAt;

    public function __construct()
    {
        $this->eventId = Str::uuid()->toString();
        $this->occurredAt = new \DateTimeImmutable();
    }
}
