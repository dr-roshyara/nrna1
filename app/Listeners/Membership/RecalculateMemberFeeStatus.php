<?php

namespace App\Listeners\Membership;

use App\Events\MembershipFeePaid;
use App\Models\Member;

class RecalculateMemberFeeStatus
{
    /**
     * Recalculate member.fees_status after any fee payment event.
     *
     * Decision logic:
     *   - No fees at all          → unpaid
     *   - All fees paid/exempt    → paid (or exempt if ALL are waived)
     *   - Mix of paid + pending   → partial
     *   - All pending             → unpaid
     */
    public function handle(MembershipFeePaid $event): void
    {
        $member = $event->fee->member;

        if (! $member) {
            return;
        }

        $fees = $member->fees()->get(['status']);

        if ($fees->isEmpty()) {
            $member->fees_status = 'unpaid';
            $member->save();
            return;
        }

        $hasPending = $fees->contains('status', 'pending');
        $hasPaid    = $fees->contains('status', 'paid');
        $allWaived  = $fees->every(fn ($f) => in_array($f->status, ['waived', 'exempt']));

        $newStatus = match (true) {
            $allWaived              => 'exempt',
            $hasPaid && !$hasPending => 'paid',
            $hasPaid && $hasPending  => 'partial',
            default                  => 'unpaid',
        };

        $member->fees_status = $newStatus;
        $member->save();
    }
}
