<?php

namespace App\Http\Controllers\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Code;
use App\Models\Election;
use App\Http\Controllers\Controller;
use App\Models\ElectionMembership;
use App\Services\ElectionService;
use App\Services\DemoElectionResolver;
use App\Services\VoterSlugService;
use App\Services\DashboardResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Response;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Notifications\ElectionReadyForActivation;
use Illuminate\Support\Facades\Notification;

class ElectionManagementController extends Controller
{
    protected DemoElectionResolver $demoResolver;
    protected VoterSlugService $slugService;

    public function __construct(DemoElectionResolver $demoResolver, VoterSlugService $slugService)
    {
        $this->demoResolver = $demoResolver;
        $this->slugService = $slugService;
    }

    // =========================================================================
    // Election Creation
    // =========================================================================

    /**
     * Show the form to create a new real election.
     *
     * GET /organisations/{organisation:slug}/elections/create
     */
    public function listForOrganisation(Organisation $organisation, \Illuminate\Http\Request $request): Response
    {
        $query = Election::where('organisation_id', $organisation->id)
            ->where('type', '!=', 'demo')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $elections = $query->get()
            ->map(fn ($e) => [
                'id'         => $e->id,
                'name'       => $e->name,
                'slug'       => $e->slug,
                'status'     => $e->status,
                'start_date' => $e->start_date,
                'end_date'   => $e->end_date,
                'created_at' => $e->created_at,
            ]);

        $userRole = \App\Models\UserOrganisationRole::where('organisation_id', $organisation->id)
            ->where('user_id', auth()->id())
            ->value('role');

        return Inertia::render('Organisations/Elections/Index', [
            'organisation'   => $organisation->only('id', 'name', 'slug'),
            'elections'      => $elections,
            'canManage'      => in_array($userRole, ['owner', 'admin', 'commission']),
            'statusFilter'   => $request->input('status'),
        ]);
    }

    public function create(Organisation $organisation): Response
    {
        $this->authorize('create', [Election::class, $organisation]);

        return Inertia::render('Organisations/Elections/Create', [
            'organisation' => $organisation,
        ]);
    }

    /**
     * Store a newly created real election.
     *
     * POST /organisations/{organisation:slug}/elections
     */
    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorize('create', [Election::class, $organisation]);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255',
                              Rule::unique('elections')->where('organisation_id', $organisation->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date'  => ['required', 'date', 'after_or_equal:today'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'type'        => ['sometimes', 'in:real'],
        ]);

        $election = Election::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $organisation->id,
            'name'            => $validated['name'],
            'slug'            => $this->generateUniqueSlug($validated['name']),
            'description'     => $validated['description'] ?? null,
            'type'            => 'real',
            'status'          => 'planned',
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
        ]);

        // Notify all active chiefs of this organisation
        $activeChiefs = ElectionOfficer::with('user')
            ->where('organisation_id', $organisation->id)
            ->where('role', 'chief')
            ->where('status', 'active')
            ->get()
            ->pluck('user')
            ->filter();

        if ($activeChiefs->isNotEmpty()) {
            Notification::send($activeChiefs, new ElectionReadyForActivation($election));
        }

        return redirect()->route('organisations.show', $organisation->slug)
            ->with('success', 'Election created successfully. Now add positions and voters.');
    }

    /**
     * Generate a globally unique slug for the election name.
     */
    private function generateUniqueSlug(string $name): string
    {
        // Use a UUID suffix so we never need to loop — guaranteed unique.
        $base = Str::slug($name) ?: 'election';
        return $base . '-' . Str::lower(Str::random(8));
    }

    /**
     * Activate a planned election (chief or deputy only).
     *
     * POST /elections/{election}/activate
     */
    public function activate(Election $election): RedirectResponse
    {
        $this->authorize('manageSettings', $election);

        if ($election->status === 'active') {
            return back()->with('error', 'Cannot activate an election that is already active.');
        }

        if ($election->status === 'completed') {
            return back()->with('error', 'Cannot activate an election that is already completed.');
        }

        Election::withoutGlobalScopes()
            ->where('id', $election->id)
            ->update([
                'status'            => 'active',
                'results_published' => false,
            ]);

        return back()->with('success', 'Election activated successfully! Voting period is now open.');
    }

    /**
     * ✅ Dashboard method - Route to appropriate page
     *
     * This method handles direct access to "/" and delegates to DashboardResolver
     * to ensure consistent routing with LoginResponse.
     *
     * Flow:
     * - Unauthenticated → Show Welcome page
     * - Authenticated → Delegate to DashboardResolver for smart routing:
     *   - Active voting session → Resume voting
     *   - Active election → Go to voting
     *   - Has organisation → Show organisation page
     *   - etc. (see DashboardResolver for full priority chain)
     */
    public function dashboard(DashboardResolver $dashboardResolver)
    {
        $authUser = Auth::user();
        $ipAddress = $this->getUserIpAddr();

        // Not authenticated: Show welcome page
        if (!$authUser) {
            return Inertia::render('Welcome', [
                'canLogin' => \Route::has('login'),
                'canRegister' => \Route::has('register'),
                'user_ip' => $ipAddress
            ]);
        }

        // Real election multi-tenant flow:
        // When the user has an org context and there is an active real election,
        // render ElectionPage with is_eligible from ElectionMembership.
        $orgId = session('current_organisation_id');
        \Illuminate\Support\Facades\Log::info('DASHBOARD_DEBUG', ['orgId' => $orgId, 'user_id' => $authUser->id]);
        if ($orgId) {
            $activeElection = Election::withoutGlobalScopes()
                ->where('organisation_id', $orgId)
                ->where('type', 'real')
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $allElectionsInDB = \Illuminate\Support\Facades\DB::table('elections')->get(['id', 'organisation_id', 'type', 'status'])->toArray();
            \Illuminate\Support\Facades\Log::info('DASHBOARD_DEBUG2', ['session_orgId' => $orgId, 'activeElection' => $activeElection?->id, 'allElectionsInDB' => $allElectionsInDB]);

            if ($activeElection) {
                $redirect = redirect()->route('elections.show', $activeElection->slug);
                // Carry any flash messages through this intermediate redirect
                if ($error = session('error')) {
                    $redirect = $redirect->with('error', $error);
                }
                if ($success = session('success')) {
                    $redirect = $redirect->with('success', $success);
                }
                return $redirect;
            }
        }

        // Authenticated user: Use DashboardResolver for consistent routing
        // This ensures the same logic is used whether user logs in or visits "/" directly
        return $dashboardResolver->resolve($authUser);
    }

    /**
     * ✅ NEW: Proper ballot access determination logic
     */
    private function determineBallotAccess($user)
    {
        // Check if election is active
        if (!config('election.is_active', true)) {
            return [
                'can_access' => false,
                'error_type' => 'election_inactive',
                'error_message_nepali' => 'निर्वाचन अहिले सक्रिय छैन।',
                'error_message_english' => 'Election is not currently active.'
            ];
        }

        // Check if user is registered as voter
        if (!$user->is_voter) {
            return [
                'can_access' => false,
                'error_type' => 'not_voter',
                'error_message_nepali' => 'तपाईं मतदाताको रूपमा दर्ता हुनुभएको छैन।',
                'error_message_english' => 'You are not registered as a voter.'
            ];
        }

        // Check if user is approved to vote
        if (!$user->can_vote) {
            return [
                'can_access' => false,
                'error_type' => 'vote_not_approved',
                'error_message_nepali' => 'तपाईंको मतदान अनुमति अझै स्वीकृत भएको छैन।',
                'error_message_english' => 'Your voting permission has not been approved yet.'
            ];
        }

        // Check if user has already voted (from user table)
        if ($user->has_voted) {
            return [
                'can_access' => true, // Allow access to view their vote
                'access_type' => 'view_vote',
                'message_nepali' => 'तपाईंले पहिले नै मतदान गरिसक्नुभएको छ।',
                'message_english' => 'You have already voted.'
            ];
        }

        // Check if voting period is active (new users can only vote if voting period is active)
        if (!ElectionService::isVotingPeriodActive()) {
            return [
                'can_access' => false,
                'error_type' => 'voting_period_inactive',
                'error_message_nepali' => 'मतदान अवधि सक्रिय छैन। मतदान सुरु भएपछि फेरि प्रयास गर्नुहोस्।',
                'error_message_english' => 'Voting period is not active. Please try again when voting has started.'
            ];
        }

        // All checks passed - user can vote
        return [
            'can_access' => true,
            'access_type' => 'can_vote',
            'message_nepali' => 'तपाईं मतदान गर्न सक्नुहुन्छ।',
            'message_english' => 'You can vote.'
        ];
    }
   
    /**
     * ⏳ FUTURE USE: Election selection page for multiple simultaneous elections
     *
     * Currently not used (single real election system).
     * Kept for future when multiple real elections might run simultaneously.
     *
     * @deprecated Use single election flow (LoginResponse → ElectionPage)
     */
    public function selectElection()
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return redirect()->route('login');
        }

        // Get all active real elections that are currently active
        $activeElections = Election::where('type', 'real')
            ->where('is_active', true)
            ->get()
            ->filter(fn ($election) => $election->isCurrentlyActive())
            ->values();

        // If no elections, redirect to dashboard
        if ($activeElections->isEmpty()) {
            return redirect()->route('dashboard');
        }

        // If only one election, redirect directly to voting
        if ($activeElections->count() === 1) {
            return redirect()->route('code.create', ['vslug' => $activeElections->first()->slug]);
        }

        // Multiple elections - show selection page (future use)
        return Inertia::render('Election/SelectElection', [
            'activeElections' => $activeElections,
            'authUser' => $authUser,
        ]);
    }

    /**
     * ✅ Demo election start - Bypass voter eligibility checks
     *
     * Demo elections are for testing:
     * - All authenticated users can vote (no can_vote_now check)
     * - Votes stored in demo_votes table (separate from real elections)
     * - Can be reset for testing
     *
     * Uses DemoElectionResolver for priority-based election selection:
     * 1️⃣ Org-specific demo (if user has organisation_id)
     * 2️⃣ Platform-wide demo (fallback)
     */
    public function startDemo()
    {
        $authUser = Auth::user();
        Log::info('🎬 Demo election start requested', [
            'user_id' => $authUser?->id,
            'user_org_id' => $authUser?->organisation_id,
        ]);

        if (!$authUser) {
            return redirect()->route('login');
        }

        try {
            // Use DemoElectionResolver to get the correct demo election
            // Priority: org-specific demo → platform-wide demo
            $demoElection = $this->demoResolver->getDemoElectionForUser($authUser);

            if (!$demoElection) {
                Log::error('❌ No demo election found', [
                    'user_id' => $authUser->id,
                    'user_org_id' => $authUser->organisation_id,
                ]);
                return redirect()->route('dashboard')
                    ->with('error', 'Demo election not available');
            }

            Log::info('✅ Demo election found', [
                'user_id' => $authUser->id,
                'election_id' => $demoElection->id,
                'election_org_id' => $demoElection->organisation_id,
            ]);

            // Store demo election in session
            session([
                'selected_election_id' => $demoElection->id,
                'selected_election_type' => 'demo',
            ]);

            Log::info('📝 Session updated', [
                'user_id' => $authUser->id,
                'session_election_id' => session('selected_election_id'),
            ]);

            // ✅ FIX: For demo elections, ALWAYS create a FRESH slug (forceNew = true)
            // This allows users to vote in demo unlimited times with new slugs each time
            $slug = $this->slugService->getOrCreateSlug($authUser, $demoElection, true);

            Log::info('✅ Voter slug created', [
                'user_id' => $authUser->id,
                'voter_slug' => $slug->slug,
                'election_id' => $slug->election_id,
            ]);

            // 🔥 DIGITAL OCEAN FIX 1: Refresh slug from database to ensure it's saved
            $slug->refresh();

            // 🔥 DIGITAL OCEAN FIX 2: Force session save for database driver
            session()->save();

            // ✅ Database-specific slug verification
            $driver = \DB::getDriverName();
            $verified = false;

            if ($driver === 'mysql') {
                // MySQL with Digital Ocean replicas may have read lag - retry verification
                $maxAttempts = 3;
                $attempts = 0;

                while (!$verified && $attempts < $maxAttempts) {
                    if ($attempts > 0) {
                        Log::debug('Retrying slug verification on MySQL', [
                            'attempt' => $attempts + 1,
                            'slug_id' => $slug->id
                        ]);
                        sleep(1);
                        \DB::reconnect($driver);
                    }

                    $verified = \DB::table('demo_voter_slugs')
                        ->where('id', $slug->id)
                        ->exists();

                    $attempts++;
                }

                if (!$verified) {
                    Log::error('Slug verification failed after retries on MySQL', [
                        'slug_id' => $slug->id,
                        'attempts' => $maxAttempts
                    ]);
                }
            } else {
                // PostgreSQL has strong consistency - no verification delay needed
                $verified = true;
                Log::debug('Slug created on ' . ucfirst($driver) . ' (strong consistency)');
            } else {
                Log::info('✅ Slug verified successfully', [
                    'attempts' => $attempts,
                    'slug_id' => $slug->id
                ]);
            }

            // Store slug in session for fallback if route binding fails
            session(['last_created_voter_slug' => $slug->slug]);
            session()->save(); // Force save again

            Log::info('📍 Redirecting to demo code entry', [
                'slug' => $slug->slug,
                'session_slug' => session('last_created_voter_slug'),
            ]);

            // Redirect to DEMO voting flow with voter slug (NOT election slug)
            return redirect()->route('slug.demo-code.create', ['vslug' => $slug->slug])
                ->with('success', '🎮 Demo election selected. Test the voting system!');

        } catch (\Exception $e) {
            Log::error('❌ Failed to start demo election', [
                'user_id' => $authUser->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Unable to start demo election. Please try again.');
        }
    }

    /**
     * List all available demo elections for the user
     *
     * Supports multiple demo elections per organisation
     * Shows:
     * - Organisation-specific demo elections
     * - Platform-wide demo elections (PublicDigit)
     */
    public function listDemoElections()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $elections = collect();

        // 1️⃣ Get organisation-specific demo elections
        if ($user->organisation_id) {
            $orgElections = Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $user->organisation_id)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('created_at', 'desc')
                ->get();

            $elections = $elections->concat($orgElections);

            Log::info('📋 Found organisation demo elections', [
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id,
                'count' => $orgElections->count(),
            ]);
        }

        // 2️⃣ Get platform-wide demo elections (PublicDigit)
        $publicElections = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->whereNull('organisation_id')
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        $elections = $elections->concat($publicElections);

        Log::info('📋 Demo elections list', [
            'user_id' => $user->id,
            'total_count' => $elections->count(),
            'org_elections' => $elections->where('organisation_id', $user->organisation_id)->count(),
            'public_elections' => $elections->whereNull('organisation_id')->count(),
        ]);

        // Group elections by organisation
        $groupedElections = $elections->groupBy(function ($election) {
            return $election->organisation_id
                ? $election->organisation?->name ?? 'Unknown Organisation'
                : 'PublicDigit';
        });

        return Inertia::render('Election/SelectDemoElection', [
            'elections' => $elections->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'description' => $e->description,
                'organisation_name' => $e->organisation?->name ?? 'PublicDigit',
                'start_date' => $e->start_date->format('d.m.Y'),
                'end_date' => $e->end_date->format('d.m.Y'),
                'posts_count' => $e->posts()->count(),
                'candidates_count' => $e->posts()->withCount('candidacies')->get()->sum('candidacies_count'),
            ]),
            'groupedByOrganisation' => $groupedElections->map(function ($orgs, $orgName) {
                return [
                    'name' => $orgName,
                    'elections' => $orgs->map(fn($e) => [
                        'id' => $e->id,
                        'name' => $e->name,
                        'description' => $e->description,
                    ]),
                ];
            })->values(),
        ]);
    }

    /**
     * Start a specific demo election (user selects from list)
     *
     * Validates user has access to the election and starts voting
     */
    public function startSpecificDemo(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id'
        ]);

        try {
            $election = Election::withoutGlobalScopes()
                ->find($validated['election_id']);

            if (!$election) {
                Log::warning('Election not found', [
                    'user_id' => $user->id,
                    'election_id' => $validated['election_id'],
                ]);
                return redirect()->route('election.demo.list')
                    ->with('error', 'Election not found');
            }

            // Check access permission
            if ($election->organisation_id && $election->organisation_id !== $user->organisation_id) {
                Log::warning('⚠️ User tried to access election from another organisation', [
                    'user_id' => $user->id,
                    'user_org_id' => $user->organisation_id,
                    'election_org_id' => $election->organisation_id,
                    'election_id' => $election->id,
                ]);
                abort(403, 'You do not have access to this election');
            }

            Log::info('✅ Starting specific demo election', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'election_name' => $election->name,
            ]);

            // Store election in session
            session([
                'selected_election_id' => $election->id,
                'selected_election_type' => 'demo',
            ]);

            // Create fresh voter slug for this election
            $slug = $this->slugService->getOrCreateSlug($user, $election, true);

            Log::info('✅ Voter slug created for specific election', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'voter_slug' => $slug->slug,
            ]);

            return redirect()->route('slug.demo-code.create', ['vslug' => $slug->slug])
                ->with('success', '🎮 Demo election selected. Test the voting system!');

        } catch (\Exception $e) {
            Log::error('❌ Failed to start specific demo election', [
                'user_id' => $user->id,
                'election_id' => $validated['election_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('election.demo.list')
                ->with('error', 'Unable to start this demo election. Please try again.');
        }
    }

    // ─── Election Management Methods ──────────────────────────────────────────

    /**
     * Management dashboard — chief or deputy only.
     * Authorization enforced by route ->can('manageSettings', 'election').
     */
    public function index(Election $election): Response
    {
        $election->load(['organisation']);

        $postsCount = \App\Models\Post::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();

        $candidatesCount = \App\Models\Candidacy::withoutGlobalScopes()
            ->whereHas('post', fn ($q) => $q->withoutGlobalScopes()->where('election_id', $election->id))
            ->where('status', \App\Models\Candidacy::STATUS_APPROVED)
            ->count();

        $organisation = $election->organisation;

        return Inertia::render('Election/Management', [
            'election'        => $election,
            'organisation'    => $organisation ? [
                'id'   => $organisation->id,
                'slug' => $organisation->slug,
                'name' => $organisation->name,
                'logo' => $organisation->logo ? asset($organisation->logo) : null,
            ] : null,
            'stats'           => $election->voter_stats,
            'canPublish'      => auth()->user()->can('publishResults', $election) && $election->status === 'completed',
            'postsCount'      => $postsCount,
            'candidatesCount' => $candidatesCount,
        ]);
    }

    /**
     * Upload organisation logo from the election management page.
     *
     * POST /elections/{election}/upload-logo
     */
    public function uploadLogo(Request $request, Election $election): RedirectResponse
    {
        $this->authorize('manageSettings', $election);

        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp,svg', 'max:2048'],
        ]);

        $organisation = $election->organisation;

        if (! $organisation) {
            return back()->withErrors(['logo' => 'No organisation linked to this election.']);
        }

        // Delete old logo
        if ($organisation->logo) {
            \Storage::delete($organisation->logo);
        }

        $path = $request->file('logo')->store(
            "organisations/{$organisation->id}/logo",
            'public'
        );

        $organisation->update(['logo' => $path]);

        return back()->with('success', 'Logo uploaded successfully.');
    }

    /**
     * Update the election's start/end dates.
     *
     * PATCH /elections/{election}/update-dates
     */
    public function updateDates(Request $request, Election $election): RedirectResponse
    {
        $this->authorize('manageSettings', $election);

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        Election::withoutGlobalScopes()
            ->where('id', $election->id)
            ->update([
                'start_date' => $validated['start_date'],
                'end_date'   => $validated['end_date'],
            ]);

        return back()->with('success', 'Election dates updated successfully.');
    }

    /**
     * Election status JSON — chief or deputy only.
     */
    public function status(Election $election): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'election' => $election->only(['id', 'name', 'status', 'is_active', 'results_published']),
            'stats'    => $election->voter_stats,
        ]);
    }

    /**
     * Viewboard — any active officer.
     */
    public function viewboard(Election $election): Response
    {
        $election->load(['organisation']);
        return Inertia::render('Election/Viewboard', [
            'election' => $election,
            'stats'    => $election->voter_stats,
            'readonly' => true,
        ]);
    }

    /**
     * Publish results — chief only.
     */
    public function publish(Election $election): \Illuminate\Http\RedirectResponse
    {
        if ($election->status !== 'completed') {
            return back()->with('error', 'Results can only be published after voting is closed.');
        }
        $election->update(['results_published' => true]);
        return back()->with('success', 'Results published.');
    }

    /**
     * Unpublish results — chief only.
     */
    public function unpublish(Election $election): \Illuminate\Http\RedirectResponse
    {
        $election->update(['results_published' => false]);
        return back()->with('success', 'Results unpublished.');
    }

    /**
     * Open voting period — chief or deputy.
     */
    public function openVoting(Election $election): \Illuminate\Http\RedirectResponse
    {
        $election->update([
            'status'            => 'active',
            'is_active'         => true,
            'results_published' => false,
        ]);
        return back()->with('success', 'Voting period opened.');
    }

    /**
     * Close voting period — chief or deputy.
     */
    public function closeVoting(Election $election): \Illuminate\Http\RedirectResponse
    {
        $election->update(['status' => 'completed', 'is_active' => false]);
        return back()->with('success', 'Voting period closed.');
    }

    /**
     * Bulk approve voters — chief or deputy.
     */
    public function bulkApproveVoters(Request $request, Election $election): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'voter_ids'   => 'required|array|min:1|max:1000',
            'voter_ids.*' => [
                'uuid',
                Rule::exists('election_memberships', 'id')
                    ->where('election_id', $election->id),
            ],
        ]);

        DB::transaction(function () use ($validated) {
            ElectionMembership::whereIn('id', $validated['voter_ids'])
                ->update(['status' => 'active']);
        });

        return back()->with('success', count($validated['voter_ids']) . ' voters approved.');
    }

    /**
     * Bulk disapprove voters — chief or deputy.
     */
    public function bulkDisapproveVoters(Request $request, Election $election): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'voter_ids'   => 'required|array|min:1|max:1000',
            'voter_ids.*' => [
                'uuid',
                Rule::exists('election_memberships', 'id')
                    ->where('election_id', $election->id),
            ],
        ]);

        DB::transaction(function () use ($validated) {
            ElectionMembership::whereIn('id', $validated['voter_ids'])
                ->update(['status' => 'inactive']);
        });

        return back()->with('success', count($validated['voter_ids']) . ' voters disapproved.');
    }

    public function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
    }
}