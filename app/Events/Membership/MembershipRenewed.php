<?php

namespace App\Events\Membership;

use App\Models\MembershipRenewal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipRenewed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipRenewal $renewal,
    ) {}
}
