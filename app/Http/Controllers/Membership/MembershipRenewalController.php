<?php

namespace App\Http\Controllers\Membership;

use App\Events\Membership\MembershipRenewed;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipRenewal;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembershipRenewalController extends Controller
{
    public function store(Request $request, Organisation $organisation, Member $member): RedirectResponse
    {
        abort_if($member->organisation_id !== $organisation->id, 404);

        $user   = $request->user();
        $policy = new MembershipPolicy();

        // Determine if this is a self-renewal or an admin renewal
        $isSelf = $user->id === $member->organisationUser->user_id;

        if (!$policy->initiateRenewal($user, $organisation, $isSelf)) {
            abort(403);
        }

        // Self-renewal window check
        if ($isSelf && !$member->canSelfRenew()) {
            return back()->withErrors(['error' => 'You are not eligible to self-renew at this time.']);
        }

        // Admin renewing a lifetime member (no expiry) is not allowed
        if ($member->membership_expires_at === null) {
            return back()->withErrors(['error' => 'Lifetime members cannot be renewed.']);
        }

        $validated = $request->validate([
            'membership_type_id' => ['required', 'uuid'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ]);

        $type = MembershipType::where('id', $validated['membership_type_id'])
            ->where('organisation_id', $organisation->id)
            ->where('is_active', true)
            ->firstOrFail();

        DB::transaction(function () use ($member, $type, $user, $validated) {
            $oldExpiry = $member->membership_expires_at;

            // Extend from old expiry or from now — whichever is later
            $base      = $oldExpiry->isFuture() ? $oldExpiry : now();
            $newExpiry = $base->addMonths($type->duration_months);

            // Create fee for the renewal
            $fee = MembershipFee::create([
                'id'                 => (string) Str::uuid(),
                'organisation_id'    => $member->organisation_id,
                'member_id'          => $member->id,
                'membership_type_id' => $type->id,
                'amount'             => $type->fee_amount,
                'currency'           => $type->fee_currency,
                'fee_amount_at_time' => $type->fee_amount,
                'currency_at_time'   => $type->fee_currency,
                'status'             => 'pending',
                'recorded_by'        => $user->id,
            ]);

            // Create renewal record
            $renewal = MembershipRenewal::create([
                'id'                 => (string) Str::uuid(),
                'organisation_id'    => $member->organisation_id,
                'member_id'          => $member->id,
                'membership_type_id' => $type->id,
                'renewed_by'         => $user->id,
                'old_expires_at'     => $oldExpiry,
                'new_expires_at'     => $newExpiry,
                'fee_id'             => $fee->id,
                'notes'              => $validated['notes'] ?? null,
            ]);

            // Update member expiry
            $member->update([
                'membership_expires_at' => $newExpiry,
                'last_renewed_at'       => now(),
            ]);

            event(new MembershipRenewed($renewal));
        });

        return back()->with('success', 'Membership renewed successfully.');
    }
}
