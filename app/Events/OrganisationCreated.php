<?php

namespace App\Events;

use App\Models\Organisation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrganisationCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Organisation $organisation,
        public readonly array $geographicConfig,
    ) {}
}
