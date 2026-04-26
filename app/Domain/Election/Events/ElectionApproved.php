<?php

namespace App\Domain\Election\Events;

use App\Models\Election;
use Illuminate\Foundation\Events\Dispatchable;

class ElectionApproved
{
    use Dispatchable;

    public function __construct(
        public readonly Election $election,
        public readonly string $approvedBy,
        public readonly ?string $notes = null
    ) {}
}
