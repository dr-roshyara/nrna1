<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    /**
     * Display an organisation's overview page
     *
     * @param  string  $slug
     * @return \Inertia\Response|\Illuminate\Routing\Redirector
     */
    public function show($slug)
    {
        // Get organisation by slug
        $organisation = Organisation::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Verify user is a member of this organisation
        $user = auth()->user();
        $isMember = $user->organisationRoles()
            ->where('organisation_id', $organisation->id)
            ->exists();

        if (!$isMember) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.access_denied')]);
        }

        // Set current organisation in session for multi-tenant context
        session(['current_organisation_id' => $organisation->id]);

        // Get user's role in this organisation
        $userRole = $user->organisationRoles()
            ->where('organisation_id', $organisation->id)
            ->value('role');

        // Check if user can manage this organisation (owner or admin role)
        $canManage = in_array($userRole, ['owner', 'admin']);

        // Get organisation stats
        $stats = [
            'members_count' => UserOrganisationRole::where('organisation_id', $organisation->id)->count(),
            'active_members_count' => 0, // TODO: Implement active members logic
            'elections_count' => 0, // TODO: Fetch from elections table
            'active_elections_count' => 0, // TODO: Fetch from elections table
            'completed_elections' => 0, // TODO: Fetch from elections table
            'new_members_30d' => 0, // TODO: Implement 30-day member logic
            'exited_members_30d' => 0, // TODO: Implement exited members logic
        ];

        // Get demo status
        $demoStatus = [
            'is_setup' => false, // TODO: Fetch from database
            'last_reset' => null, // TODO: Fetch from database
        ];

        // Return organisation overview page
        return inertia('Organisations/Show', [
            'organisation' => $organisation->only(['id', 'name', 'slug', 'type', 'email', 'address']),
            'stats' => $stats,
            'demoStatus' => $demoStatus,
            'canManage' => $canManage,
        ]);
    }

    /**
     * Create a new organisation
     *
     * User becomes OWNER, and switches to this new org
     * User retains membership of platform org for support access
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $org = DB::transaction(function () use ($request, $user) {
            // Create new tenant organisation
            $org = Organisation::create([
                'name' => $request->name,
                'type' => 'tenant',
                'is_default' => false,
            ]);

            // User becomes OWNER of new org
            UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $org->id,
                'role' => 'owner',
            ]);

            // Switch user to new org (they still belong to platform too)
            $user->update(['organisation_id' => $org->id]);

            return $org;
        });

        return redirect("/organisations/{$org->id}/dashboard");
    }
}
