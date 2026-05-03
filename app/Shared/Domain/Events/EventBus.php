<?php

declare(strict_types=1);

namespace App\Shared\Domain\Events;

interface EventBus
{
    /** Dispatch all domain events collected from an aggregate. */
    public function dispatchAll(array $events): void;
}
