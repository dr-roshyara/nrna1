<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\Member;
use App\Models\UserOrganisationRole;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Traits\ChecksElectionAccess;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    use ChecksElectionAccess;

    public function index(): Response
    {
        $user = auth()->user();

        $organisations = $user->organisationRoles()
            ->with('organisation')
            ->get()
            ->map(fn ($role) => [
                'id'        => $role->organisation->id,
                'name'      => $role->organisation->name,
                'slug'      => $role->organisation->slug,
                'role'      => $role->role,
                'joined_at' => $role->created_at?->format('Y-m-d'),
            ]);

        return Inertia::render('Organisations/Index', [
            'organisations' => $organisations,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Organisations/Create');
    }

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
            'members_count'          => Member::where('organisation_id', $organisation->id)->count(),
            'active_members_count'   => Member::where('organisation_id', $organisation->id)
                                            ->where('status', 'active')
                                            ->count(),
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
            'name'           => 'required|string|min:3|max:255',
            'email'          => 'nullable|email|max:255',
            'representative' => 'nullable|string|max:255',
            'languages'      => 'nullable|array',
            'languages.*'    => 'string|in:en,de,np',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('organisations/logos', 'public');
            }

            // Create new tenant organisation
            $org = Organisation::create([
                'name'           => $request->name,
                'slug'           => $slug,
                'type'           => 'tenant',
                'is_default'     => false,
                'email'          => $request->email,
                'representative' => $request->representative ? ['name' => $request->representative] : null,
                'languages'      => $request->languages ?? [],
                'logo'           => $logoPath,
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

        $isElectionOfficer = ElectionOfficer::where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        $isOfficer = in_array($role, ['owner', 'admin', 'commission']) || $isElectionOfficer;

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

        $publishedElections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->where('results_published', true)
            ->orderBy('start_date')
            ->get()
            ->map(fn ($e) => [
                'id'   => $e->id,
                'name' => $e->name,
                'slug' => $e->slug,
            ]);

        $electionIds = $activeElections->pluck('id');

        $voterMemberships = ElectionMembership::where('user_id', $user->id)
            ->whereIn('election_id', $electionIds)
            ->get()
            ->keyBy('election_id')
            ->map(fn ($m) => [
                'status'    => $m->status,
                'role'      => $m->role,
                'has_voted' => (bool) $m->has_voted,
            ]);

        $myApplications = \App\Models\CandidacyApplication::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->with(['election:id,name,status', 'post:id,name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($a) => [
                'id'             => $a->id,
                'election_id'    => $a->election_id,
                'election_name'  => $a->election?->name,
                'election_status'=> $a->election?->status,
                'post_name'      => $a->post?->name,
                'status'         => $a->status,
                'created_at'     => $a->created_at->format('Y-m-d'),
            ]);

        $appliedElectionIds = $myApplications->pluck('election_id')->unique()->values();

        return Inertia::render('Organisations/VoterHub', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'activeElections'    => $activeElections->values(),
            'publishedElections' => $publishedElections->values(),
            'voterMemberships'   => $voterMemberships,
            'myApplications'     => $myApplications->values(),
            'isOfficer'          => $isOfficer,
            'appliedElectionIds' => $appliedElectionIds->values(),
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

    public function voterPosts(Organisation $organisation, string $election): Response
    {
        $electionModel = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->firstOrFail();

        abort_unless(
            $this->canAccessElection($organisation, $electionModel->id),
            403,
            'You are not authorised to view this election.'
        );

        $posts = Post::withoutGlobalScopes()
            ->where('election_id', $electionModel->id)
            ->where('organisation_id', $organisation->id)
            ->orderBy('position_order')
            ->get()
            ->map(fn ($post) => [
                'id'               => $post->id,
                'name'             => $post->name,
                'nepali_name'      => $post->nepali_name,
                'is_national_wide' => (bool) $post->is_national_wide,
                'state_name'       => $post->state_name,
                'required_number'  => $post->required_number,
            ]);

        return Inertia::render('Organisations/Posts', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $electionModel->only('id', 'name', 'slug', 'status'),
            'posts'        => $posts->values(),
        ]);
    }

    /**
     * Show voter-facing candidates list with positions
     */
    public function voterCandidates(Organisation $organisation, string $election): Response
    {
        $electionModel = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->firstOrFail();

        abort_unless(
            $this->canAccessElection($organisation, $electionModel->id),
            403,
            'You are not authorised to view this election.'
        );

        $posts = Post::withoutGlobalScopes()
            ->where('election_id', $electionModel->id)
            ->where('organisation_id', $organisation->id)
            ->orderBy('position_order')
            ->with(['candidacies' => function ($q) {
                $q->withoutGlobalScopes()
                  ->where('status', 'approved')
                  ->with(['user' => fn ($u) => $u->withoutGlobalScopes()])
                  ->orderBy('position_order');
            }])
            ->get()
            ->map(fn ($post) => [
                'id'               => $post->id,
                'name'             => $post->name,
                'nepali_name'      => $post->nepali_name,
                'is_national_wide' => (bool) $post->is_national_wide,
                'state_name'       => $post->state_name,
                'required_number'  => $post->required_number,
                'candidacies'      => $post->candidacies->map(fn ($c) => [
                    'id'             => $c->id,
                    'name'           => $c->user?->name ?? $c->name ?? '—',
                    'description'    => $c->description,
                    'image_path_1'   => $c->image_path_1,
                    'image_path_2'   => $c->image_path_2,
                    'image_path_3'   => $c->image_path_3,
                    'position_order' => $c->position_order,
                ])->values(),
            ]);

        return Inertia::render('Organisations/Candidates', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $electionModel->only('id', 'name', 'slug', 'status'),
            'posts'        => $posts->values(),
        ]);
    }

    // READ-ONLY voter list for ALL election members (including officers).
    // Admin management remains at ElectionVoterController@index.
    public function voters(Organisation $organisation, string $election): Response
    {
        $electionModel = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->firstOrFail();

        abort_unless(
            $this->canAccessElection($organisation, $electionModel->id),
            403,
            'You are not authorised to view this election.'
        );

        $query = $electionModel->memberships()
            ->withoutGlobalScopes()
            ->with(['user' => fn ($q) => $q->withoutGlobalScopes()->select('id', 'name')])
            ->where('role', 'voter');

        $sort      = request('sort', 'assigned_at');
        $direction = in_array(request('direction'), ['asc', 'desc']) ? request('direction') : 'asc';
        $allowed   = ['name', 'status', 'assigned_at'];

        if ($sort === 'name') {
            $query->join('users', 'election_memberships.user_id', '=', 'users.id')
                  ->orderBy('users.name', $direction)
                  ->select('election_memberships.*');
        } elseif (in_array($sort, $allowed)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('assigned_at', 'asc');
        }

        if ($status = request('status')) {
            if ($status === 'pending_suspension') {
                $query->where('suspension_status', 'proposed');
            } elseif (in_array($status, ['active', 'invited', 'inactive', 'removed'])) {
                $query->where('status', $status);
            }
        }

        $voters = $query->paginate(50)
            ->through(fn ($m) => [
                'id'                => $m->id,
                'name'              => $m->user?->name ?? '—',
                'status'            => $m->status,
                'suspension_status' => $m->suspension_status,
                'has_voted'         => (bool) $m->has_voted,
            ]);

        return Inertia::render('Organisations/Voters', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $electionModel->only('id', 'name', 'slug', 'status'),
            'voters'       => $voters,
            'filters'      => ['sort' => $sort, 'direction' => $direction, 'status' => request('status')],
        ]);
    }

}
