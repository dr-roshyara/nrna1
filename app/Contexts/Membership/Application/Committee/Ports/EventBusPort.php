<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Ports;

interface EventBusPort
{
    public function publish(object $event): void;
}
