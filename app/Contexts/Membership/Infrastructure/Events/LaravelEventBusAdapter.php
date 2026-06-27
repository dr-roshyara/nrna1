<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Events;

use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use Illuminate\Support\Facades\Event;

final class LaravelEventBusAdapter implements EventBusPort
{
    public function publish(object $event): void
    {
        Event::dispatch($event);
    }
}
