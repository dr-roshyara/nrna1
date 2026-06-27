<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;

final class FakeEventBus implements EventBusPort
{
    /** @var array<object> */
    public array $publishedEvents = [];

    public function publish(object $event): void
    {
        $this->publishedEvents[] = $event;
    }

    public function hasPublished(string $eventClassName): bool
    {
        foreach ($this->publishedEvents as $event) {
            if (class_basename($event) === $eventClassName) {
                return true;
            }
        }
        return false;
    }

    public function reset(): void
    {
        $this->publishedEvents = [];
    }
}
