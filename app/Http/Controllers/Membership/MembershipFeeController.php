<?php

namespace App\Http\Controllers\Membership;

use App\Events\Membership\MembershipFeePaid;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function pay(Request $request, Organisation $organisation, Member $member, MembershipFee $fee): RedirectResponse
    {
        $this->authorizeRecordPayment($request->user(), $organisation);
        abort_if($fee->member_id !== $member->id, 404);

        if ($fee->status !== 'pending') {
            return back()->withErrors(['error' => 'This fee has already been processed.']);
        }

        $validated = $request->validate([
            'payment_method'    => ['required', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:200'],
            'idempotency_key'   => ['nullable', 'string', 'max:100'],
        ]);

        // Idempotency check: if key supplied and already used on a DIFFERENT fee, reject
        if (!empty($validated['idempotency_key'])) {
            $duplicate = MembershipFee::where('idempotency_key', $validated['idempotency_key'])
                ->where('id', '!=', $fee->id)
                ->exists();

            if ($duplicate) {
                return back()->withErrors(['error' => 'Duplicate payment detected (idempotency key already used).']);
            }
        }

        DB::transaction(function () use ($fee, $validated, $request) {
            $fee->update([
                'status'             => 'paid',
                'paid_at'            => now(),
                'payment_method'     => $validated['payment_method'],
                'payment_reference'  => $validated['payment_reference'] ?? null,
                'idempotency_key'    => $validated['idempotency_key'] ?? null,
                'recorded_by'        => $request->user()->id,
            ]);

            event(new MembershipFeePaid($fee->fresh()));
        });

        return back()->with('success', 'Payment recorded successfully.');
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
