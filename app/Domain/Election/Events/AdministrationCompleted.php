<?php

namespace App\Domain\Election\Events;

use App\Models\Election;
use Illuminate\Foundation\Events\Dispatchable;

class AdministrationCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly Election $election,
        public readonly string $completedBy,
        public readonly ?string $reason = null
    ) {}
}
