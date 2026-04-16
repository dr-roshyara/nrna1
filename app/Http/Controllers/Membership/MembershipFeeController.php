<?php

namespace App\Http\Controllers\Membership;

use App\Domain\Shared\ValueObjects\Money;
use App\Exceptions\FeeAlreadyPaidException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use App\Services\MembershipPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembershipFeeController extends Controller
{
    public function index(Request $request, Organisation $organisation, Member $member): Response
    {
        $this->authorizeRecordPayment($request->user(), $organisation);
        abort_if($member->organisation_id !== $organisation->id, 404);

        $fees = MembershipFee::where('member_id', $member->id)
            ->with('membershipType')
            ->orderByDesc('created_at')
            ->paginate(20);

        $canManage = (new MembershipPolicy())->recordFeePayment($request->user(), $organisation);

        return Inertia::render('Organisations/Membership/Member/Fees', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'member'       => $member->load('organisationUser.user'),
            'fees'         => $fees,
            'canManage'    => $canManage,
        ]);
    }

    public function pay(
        Request $request,
        Organisation $organisation,
        Member $member,
        MembershipFee $fee,
        MembershipPaymentService $service
    ): RedirectResponse {
        // Authorization & tenant isolation
        $this->authorize('recordFeePayment', $organisation);
        abort_if($member->organisation_id !== $organisation->id, 404);
        abort_if($fee->member_id !== $member->id, 404);

        // Full membership mode only
        abort_if(!$organisation->uses_full_membership, 403, 'Not in full membership mode');

        // Validation
        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,cash,card,cheque,online',
            'payment_reference' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        try {
            // Use service to record payment atomically
            // If no amount provided, use the fee's original amount
            $paymentAmount = $validated['amount'] ?? $fee->amount;

            $service->recordPayment(
                $member,
                $fee,
                new Money($paymentAmount, 'EUR'),
                $validated['payment_method'],
                $validated['payment_reference'] ?? null
            );

            return back()->with('success', 'Payment recorded successfully.');
        } catch (FeeAlreadyPaidException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function waive(Request $request, Organisation $organisation, Member $member, MembershipFee $fee): RedirectResponse
    {
        $this->authorizeRecordPayment($request->user(), $organisation);
        abort_if($fee->member_id !== $member->id, 404);

        if ($fee->status !== 'pending') {
            return back()->withErrors(['error' => 'This fee has already been processed.']);
        }

        $fee->update([
            'status'      => 'waived',
            'recorded_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Fee waived successfully.');
    }

    private function authorizeRecordPayment($user, Organisation $organisation): void
    {
        abort_if(!(new MembershipPolicy())->recordFeePayment($user, $organisation), 403);
    }
}
