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
     * Supports both slug and UUID as route parameter:
     * - /organisations/publicdigit → lookup by slug
     * - /organisations/{uuid} → lookup by UUID
     *
     * @param  string  $slug UUID or slug
     * @return \Inertia\Response|\Illuminate\Routing\Redirector
     */
    public function show($slug)
    {
        \Log::info('Organisation show method called', ['slug' => $slug]);

        // Get organisation by slug OR UUID
        $organisation = Organisation::where('slug', $slug)
            ->orWhere('id', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        \Log::info('Organisation found', [
            'org_id' => $organisation->id,
            'org_slug' => $organisation->slug,
            'org_name' => $organisation->name,
        ]);

        // Verify user is a member of this organisation
        $user = auth()->user();

        \Log::info('Checking membership', [
            'user_id' => $user->id,
            'org_id' => $organisation->id,
        ]);

        $isMember = $user->organisationRoles()
            ->where('organisation_id', $organisation->id)
            ->exists();

        \Log::info('Membership check result', [
            'user_id' => $user->id,
            'org_id' => $organisation->id,
            'is_member' => $isMember,
        ]);

        if (!$isMember) {
            \Log::warning('Non-member access attempt', [
                'user_id' => $user->id,
                'org_id' => $organisation->id,
                'org_slug' => $organisation->slug,
            ]);
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
            // Generate unique slug from organisation name
            $slug = \Illuminate\Support\Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure slug is unique
            while (Organisation::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            // Create new tenant organisation
            $org = Organisation::create([
                'name' => $request->name,
                'slug' => $slug,
                'type' => 'tenant',
                'is_default' => false,
            ]);

            \Log::info('Organisation created', [
                'org_id' => $org->id,
                'org_slug' => $org->slug,
                'org_name' => $org->name,
            ]);

            // User becomes OWNER of new org
            $pivot = UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $org->id,
                'role' => 'owner',
            ]);

            \Log::info('Organisation membership created', [
                'pivot_id' => $pivot->id,
                'user_id' => $user->id,
                'org_id' => $org->id,
                'role' => 'owner',
            ]);

            // Switch user to new org (they still belong to platform too)
            $user->update(['organisation_id' => $org->id]);

            // Verify membership was created
            $isMember = $user->organisationRoles()
                ->where('organisation_id', $org->id)
                ->exists();

            \Log::info('Membership verification', [
                'user_id' => $user->id,
                'org_id' => $org->id,
                'is_member' => $isMember,
            ]);

            return $org;
        });

        \Log::info('Redirecting to organisation show page', [
            'org_slug' => $org->slug,
            'route' => 'organisations.show',
        ]);

        return redirect()->route('organisations.show', $org->slug);
    }

    /**
     * Display organisation's management dashboard
     *
     * Shows statistics and management options for:
     * - Members and active members
     * - Elections (active, completed)
     * - Demo mode status
     *
     * @param  string  $slug UUID or slug
     * @return \Inertia\Response
     */
    public function dashboard($slug)
    {
        // Get organisation by slug or UUID
        $organisation = Organisation::where('slug', $slug)
            ->orWhere('id', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Verify user is a member
        $user = auth()->user();
        $isMember = $user->organisationRoles()
            ->where('organisation_id', $organisation->id)
            ->exists();

        if (!$isMember) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.access_denied')]);
        }

        // Set organisation context
        session(['current_organisation_id' => $organisation->id]);

        // Get organisation statistics
        $stats = [
            'members' => $organisation->users()->count(),
            'active_members' => $organisation->users()
                ->where('email_verified_at', '!=', null)
                ->count(),
            'elections' => \App\Models\Election::where('organisation_id', $organisation->id)->count(),
            'active_elections' => \App\Models\Election::where('organisation_id', $organisation->id)
                ->where('status', 'active')
                ->count(),
            'completed_elections' => \App\Models\Election::where('organisation_id', $organisation->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return inertia('Organisations/Dashboard', [
            'organisation' => $organisation->only(['id', 'name', 'slug', 'type', 'email', 'address']),
            'stats' => $stats,
            'canManage' => in_array(
                $user->organisationRoles()
                    ->where('organisation_id', $organisation->id)
                    ->value('role'),
                ['owner', 'admin']
            ),
        ]);
    }
}
