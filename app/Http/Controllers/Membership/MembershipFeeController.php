<?php

namespace App\Http\Controllers\Membership;

use App\Domain\Shared\ValueObjects\Money;
use App\Exceptions\FeeAlreadyPaidException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use App\Services\MembershipPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MembershipFeeController extends Controller
{
    public function create(Organisation $organisation, Member $member): Response
    {
        $this->authorize('recordFeePayment', $organisation);
        abort_if($member->organisation_id !== $organisation->id, 404);

        $types = MembershipType::where('organisation_id', $organisation->id)
            ->active()
            ->get(['id', 'name', 'fee_amount', 'fee_currency', 'duration_months', 'grants_voting_rights']);

        return Inertia::render('Organisations/Membership/Member/FeeCreate', [
            'organisation'    => $organisation->only('id', 'name', 'slug'),
            'member'          => $member->load('organisationUser.user'),
            'membershipTypes' => $types,
        ]);
    }

    public function store(Request $request, Organisation $organisation, Member $member): RedirectResponse
    {
        $this->authorize('recordFeePayment', $organisation);
        abort_if($member->organisation_id !== $organisation->id, 404);

        $validated = $request->validate([
            'membership_type_id' => [
                'required',
                'uuid',
                Rule::exists('membership_types', 'id')
                    ->where('organisation_id', $organisation->id),
            ],
            'due_date'    => 'required|date|after_or_equal:today',
            'period_label' => 'nullable|string|max:100',
            'notes'       => 'nullable|string|max:500',
        ]);

        $type = MembershipType::findOrFail($validated['membership_type_id']);

        MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $organisation->id,
            'member_id'          => $member->id,
            'membership_type_id' => $type->id,
            'amount'             => $type->fee_amount,
            'currency'           => $type->fee_currency,
            'fee_amount_at_time' => $type->fee_amount,
            'currency_at_time'   => $type->fee_currency,
            'period_label'       => $validated['period_label'] ?? null,
            'due_date'           => $validated['due_date'],
            'status'             => 'pending',
            'recorded_by'        => auth()->id(),
            'notes'              => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('organisations.members.fees.index', [$organisation->slug, $member->id])
            ->with('success', 'Fee assigned successfully.');
    }

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
