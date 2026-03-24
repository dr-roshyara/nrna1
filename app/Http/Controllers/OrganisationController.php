<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Inertia\Inertia;
use Inertia\Response;
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
        $canCreateElection = in_array($userRole, ['owner', 'admin']);

        // Load ALL active officer records for this user in this org (one per election they manage)
        $userOfficerRecords = ElectionOfficer::with('election:id,name')
            ->where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->active()
            ->get();

        $isOfficer      = $userOfficerRecords->isNotEmpty();
        $isChief        = $userOfficerRecords->contains('role', 'chief');
        $isDeputy       = $userOfficerRecords->contains('role', 'deputy');
        $isCommissioner = $userOfficerRecords->contains('role', 'commissioner') && !$isChief && !$isDeputy;

        // Build a human-readable list of which elections the officer manages
        $officerElectionNames = $userOfficerRecords
            ->filter(fn ($o) => $o->election_id !== null)
            ->map(fn ($o) => $o->election?->name)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $canActivateElection = $isChief || $isDeputy;
        $canManageVoters     = $isChief || $isDeputy;
        $canPublishResults   = $isChief;

        // Real elections for this organisation
        $realElections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'slug', 'status', 'start_date', 'end_date', 'results_published']);

        // Voter membership context for the current user across these elections
        $electionIds = $realElections->pluck('id')->toArray();
        $voterMemberships = [];
        if (!empty($electionIds)) {
            \App\Models\ElectionMembership::where('user_id', $user->id)
                ->whereIn('election_id', $electionIds)
                ->get(['election_id', 'role', 'status', 'has_voted'])
                ->each(function ($m) use (&$voterMemberships) {
                    $voterMemberships[$m->election_id] = [
                        'role'      => $m->role,
                        'status'    => $m->status,
                        'has_voted' => (bool) $m->has_voted,
                    ];
                });
        }

        // Get organisation stats
        $stats = [
            'members_count'          => UserOrganisationRole::where('organisation_id', $organisation->id)->count(),
            'active_members_count'   => 0,
            'elections_count'        => $realElections->count(),
            'active_elections_count' => $realElections->where('status', 'active')->count(),
            'completed_elections'    => $realElections->where('status', 'completed')->count(),
            'new_members_30d'        => 0,
            'exited_members_30d'     => 0,
        ];

        // Get demo status - check if organisation already has a demo election
        $demoExists = DB::table('elections')
            ->where('organisation_id', $organisation->id)
            ->where('type', 'demo')
            ->exists();

        $demoStatus = [
            'exists' => $demoExists,
            'is_setup' => $demoExists,
            'last_reset' => null,
        ];

        // Active officers with user details
        $officers = \App\Models\ElectionOfficer::with('user:id,name,email')
            ->where('organisation_id', $organisation->id)
            ->active()
            ->orderBy('role')
            ->get()
            ->map(fn ($o) => [
                'id'           => $o->id,
                'user_id'      => $o->user_id,
                'user_name'    => $o->user->name  ?? '',
                'user_email'   => $o->user->email ?? '',
                'role'         => $o->role,
                'status'       => $o->status,
                'appointed_at' => $o->appointed_at?->toDateString(),
            ]);

        // Members for the appointment modal dropdown
        $orgMembers = DB::table('user_organisation_roles as ur')
            ->join('users as u', 'u.id', '=', 'ur.user_id')
            ->where('ur.organisation_id', $organisation->id)
            ->select('u.id', 'u.name', 'u.email')
            ->orderBy('u.name')
            ->get();

        // Return organisation overview page
        return inertia('Organisations/Show', [
            'organisation'       => $organisation->only(['id', 'name', 'slug', 'type', 'email', 'address']),
            'stats'              => $stats,
            'demoStatus'         => $demoStatus,
            'canManage'          => $canManage,
            'canCreateElection'  => $canCreateElection,
            'canActivateElection'=> $canActivateElection,
            'canManageVoters'    => $canManageVoters,
            'canPublishResults'  => $canPublishResults,
            'userRole'           => $userRole,
            'isOfficer'           => $isOfficer,
            'isChief'             => $isChief,
            'isDeputy'            => $isDeputy,
            'isCommissioner'      => $isCommissioner,
            'officerElectionNames'=> $officerElectionNames,
            'officers'           => $officers,
            'orgMembers'         => $orgMembers,
            'elections'          => $realElections->values(),
            'voterMemberships'   => $voterMemberships,
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

    // =========================================================================
    // voterHub — member-facing: active elections + voter status
    // =========================================================================

    public function voterHub(Organisation $organisation): Response
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

        $activeElections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->where('status', 'active')
            ->with(['posts' => fn ($q) => $q->withoutGlobalScopes()->orderBy('position_order')])
            ->orderBy('start_date')
            ->get()
            ->map(fn ($e) => [
                'id'          => $e->id,
                'name'        => $e->name,
                'slug'        => $e->slug,
                'start_date'  => $e->start_date,
                'end_date'    => $e->end_date,
                'description' => $e->description,
                'posts'       => $e->posts->map(fn ($p) => [
                    'id'               => $p->id,
                    'name'             => $p->name,
                    'is_national_wide' => (bool) $p->is_national_wide,
                    'state_name'       => $p->state_name,
                    'required_number'  => $p->required_number,
                    'position_order'   => $p->position_order,
                ])->values(),
            ]);

        $voterMemberships = ElectionMembership::where('user_id', $user->id)
            ->whereIn('election_id', $activeElections->pluck('id'))
            ->get()
            ->keyBy('election_id')
            ->map(fn ($m) => [
                'status'    => $m->status,
                'role'      => $m->role,
                'has_voted' => (bool) $m->has_voted,
            ]);

        return Inertia::render('Organisations/VoterHub', [
            'organisation'     => $organisation->only('id', 'name', 'slug'),
            'activeElections'  => $activeElections->values(),
            'voterMemberships' => $voterMemberships,
        ]);
    }

    // =========================================================================
    // electionCommission — officer/admin-facing: per-election management links
    // =========================================================================

    public function electionCommission(Organisation $organisation): Response
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

        $isOfficer = ElectionOfficer::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('status', 'active')
            ->exists();

        abort_if(! in_array($role, ['owner', 'admin']) && ! $isOfficer, 403);

        $elections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($e) => [
                'id'     => $e->id,
                'name'   => $e->name,
                'slug'   => $e->slug,
                'status' => $e->status,
            ]);

        $stats = [
            'elections_count'  => $elections->count(),
            'active_elections' => $elections->where('status', 'active')->count(),
            'total_voters'     => ElectionMembership::where('organisation_id', $organisation->id)
                ->where('status', 'active')->count(),
            'officers_count'   => ElectionOfficer::where('organisation_id', $organisation->id)
                ->where('status', 'active')->count(),
        ];

        return Inertia::render('Organisations/ElectionCommission', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'elections'    => $elections->values(),
            'stats'        => $stats,
            'canManage'    => in_array($role, ['owner', 'admin']),
            'isChief'      => ElectionOfficer::where('user_id', $user->id)
                ->where('organisation_id', $organisation->id)
                ->where('role', 'chief')->where('status', 'active')->exists(),
            'isDeputy'     => ElectionOfficer::where('user_id', $user->id)
                ->where('organisation_id', $organisation->id)
                ->where('role', 'deputy')->where('status', 'active')->exists(),
        ]);
    }
}
