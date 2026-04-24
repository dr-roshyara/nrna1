<?php

namespace App\Events;

use App\Models\Election;
use Illuminate\Foundation\Events\Dispatchable;

class ElectionStateChangedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly Election $election,
        public readonly string $fromState,
        public readonly string $toState,
        public readonly string $trigger,
        public readonly ?string $actorId
    ) {}
}
