<?php

namespace App\Domain\Election\Events;

use App\Models\Election;
use Illuminate\Foundation\Events\Dispatchable;

class ElectionRejected
{
    use Dispatchable;

    public function __construct(
        public readonly Election $election,
        public readonly string $rejectedBy,
        public readonly string $reason
    ) {}
}
