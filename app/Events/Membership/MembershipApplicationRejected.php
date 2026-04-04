<?php

namespace App\Events\Membership;

use App\Models\MembershipApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipApplicationRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application,
    ) {}
}
