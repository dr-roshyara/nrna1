<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

final class DomainEventsBuffer
{
    /** @var array<object> */
    private array $events = [];

    public function record(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array<object>
     */
    public function peek(): array
    {
        return $this->events;
    }

    /**
     * @return array<object>
     */
    public function release(): array
    {
        $released = $this->events;
        $this->events = [];

        return $released;
    }
}
