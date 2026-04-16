<?php

namespace App\Events;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use App\Models\Organisation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipFeePaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly MembershipFee $fee,
        public readonly MembershipPayment $payment,
        public readonly Organisation $organisation
    ) {
    }
}
