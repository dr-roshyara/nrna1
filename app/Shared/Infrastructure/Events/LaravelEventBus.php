<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Events;

use App\Shared\Domain\Events\EventBus;

final class LaravelEventBus implements EventBus
{
    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
