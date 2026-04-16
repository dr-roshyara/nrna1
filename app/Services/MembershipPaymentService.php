<?php

namespace App\Services;

use App\Domain\Shared\ValueObjects\Money;
use App\Events\MembershipFeePaid as MembershipFeePaidEvent;
use App\Exceptions\FeeAlreadyPaidException;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MembershipPaymentService
{
    /**
     * Record a membership fee payment atomically.
     *
     * This method:
     * 1. Locks the fee row to prevent concurrent duplicate payments
     * 2. Validates the fee is not already paid (idempotency guard)
     * 3. Creates a MembershipPayment audit record
     * 4. Marks the fee as paid
     * 5. Updates member fees_status if all fees are cleared
     * 6. Fires MembershipFeePaid event (listener creates Income record)
     *
     * @param Member $member The member making the payment
     * @param MembershipFee $fee The fee being paid
     * @param Money $amount The payment amount with currency
     * @param string $method Payment method (default: bank_transfer)
     * @param string|null $reference Payment reference number (optional)
     * @return MembershipPayment The created payment record
     * @throws FeeAlreadyPaidException If fee is already paid
     */
    public function recordPayment(
        Member $member,
        MembershipFee $fee,
        Money $amount,
        string $method = 'bank_transfer',
        ?string $reference = null
    ): MembershipPayment {
        return DB::transaction(function () use ($member, $fee, $amount, $method, $reference) {
            // CRITICAL: Lock fee row to prevent concurrent duplicate payments
            $fee = MembershipFee::where('id', $fee->id)->lockForUpdate()->first();

            // IDEMPOTENCY GUARD: Prevent duplicate payments
            if ($fee->status === 'paid') {
                throw new FeeAlreadyPaidException("Fee {$fee->id} is already paid.");
            }

            // 1. Create audit record
            $payment = MembershipPayment::create([
                'member_id' => $member->id,
                'fee_id' => $fee->id,
                'organisation_id' => $member->organisation_id,
                'amount' => $amount->getAmount(),
                'currency' => $amount->getCurrency(),
                'payment_method' => $method,
                'payment_reference' => $reference,
                'status' => 'completed',
                'recorded_by' => auth()->id() ?? $member->organisation_id,
                'paid_at' => now(),
            ]);

            // 2. Mark fee paid
            $fee->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // 3. Update member fees_status if no pending/overdue remain
            $hasOutstanding = $member->fees()
                ->whereIn('status', ['pending', 'overdue'])
                ->exists();

            if (!$hasOutstanding) {
                $member->update(['fees_status' => 'paid']);
            }

            // 4. Fire domain event → Listener creates Income record (decoupled)
            event(new MembershipFeePaidEvent($fee, $payment, $member->organisation));

            return $payment;
        });
    }

    /**
     * Get outstanding (pending and overdue) fees for a member.
     *
     * @param Member $member
     * @return Collection
     */
    public function getOutstandingFees(Member $member): Collection
    {
        return $member->fees()
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get payment history for a member (last 20 payments).
     *
     * @param Member $member
     * @return Collection
     */
    public function getPaymentHistory(Member $member): Collection
    {
        return $member->payments()
            ->with('fee')
            ->latest('paid_at')
            ->limit(20)
            ->get();
    }

    /**
     * Get financial dashboard statistics for an organisation.
     *
     * @param Member|null $member If provided, stats are for this member only
     * @return array
     */
    public function getDashboardStats(?Member $member = null): array
    {
        if ($member) {
            return [
                'outstanding_total' => $member->fees()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->sum('amount'),
                'paid_total' => $member->payments()->sum('amount'),
                'overdue_count' => $member->fees()
                    ->where('status', 'overdue')
                    ->count(),
            ];
        }

        return [];
    }
}
