<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrganisationSettingsController extends Controller
{
    public function index(Organisation $organisation)
    {
        $this->authorize('update', $organisation);

        $memberCount = Member::where('organisation_id', $organisation->id)->count();

        return Inertia::render('Organisations/Settings/Index', [
            'organisation' => $organisation,
            'memberCount' => $memberCount,
        ]);
    }

    public function updateMembershipMode(Request $request, Organisation $organisation)
    {
        $this->authorize('update', $organisation);

        $validated = $request->validate([
            'uses_full_membership' => 'required|boolean',
            'confirm_mode_change' => 'required_if:uses_full_membership,false|accepted',
        ]);

        $memberCount = Member::where('organisation_id', $organisation->id)->count();

        // If switching from full to election-only with existing members, require confirmation
        if ($organisation->uses_full_membership && !$validated['uses_full_membership'] && $memberCount > 0) {
            if (empty($validated['confirm_mode_change'])) {
                return back()->withErrors([
                    'confirm_mode_change' => 'You must confirm this change when members exist.',
                ]);
            }
        }

        Log::info('Organisation membership mode changed', [
            'organisation_id' => $organisation->id,
            'from' => $organisation->uses_full_membership ? 'full' : 'election_only',
            'to' => $validated['uses_full_membership'] ? 'full' : 'election_only',
            'user_id' => auth()->id(),
            'member_count' => $memberCount,
        ]);

        $organisation->update(['uses_full_membership' => $validated['uses_full_membership']]);

        return back()->with('success', 'Membership mode updated successfully.');
    }
}
