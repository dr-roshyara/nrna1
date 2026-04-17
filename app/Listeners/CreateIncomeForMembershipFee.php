<?php

namespace App\Listeners;

use App\Models\Income;
use App\Events\MembershipFeePaid;

class CreateIncomeForMembershipFee
{
    /**
     * Handle the MembershipFeePaid event by creating an Income record.
     *
     * This listener decouples the Membership context from the Finance context.
     * When a membership fee is paid, we automatically create a corresponding
     * Income record for financial reporting without tight coupling.
     */
    public function handle(MembershipFeePaid $event): void
    {
        $income = Income::create([
            'organisation_id' => $event->organisation->id,
            'user_id' => $event->payment->recorded_by,
            'membership_fee' => $event->payment->amount,
            'source_type' => 'membership_fee',
            'source_id' => $event->fee->id,
            'country' => $event->organisation->country ?? 'DE',
            'committee_name' => 'Membership',
            'period_from' => now()->startOfMonth(),
            'period_to' => now()->endOfMonth(),
        ]);

        // Link income back to payment for audit trail
        $event->payment->update(['income_id' => $income->id]);
    }
}
