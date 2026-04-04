<?php

namespace App\Events\Membership;

use App\Models\MembershipFee;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipFeePaid
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipFee $fee,
    ) {}
}
