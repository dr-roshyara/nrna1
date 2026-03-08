<?php

namespace App\Http\Controllers\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Code;
use App\Models\Election;
use App\Http\Controllers\Controller;
use App\Services\ElectionService;
use App\Services\DemoElectionResolver;
use App\Services\VoterSlugService;
use App\Services\DashboardResolver;
use Illuminate\Support\Facades\Log;

class ElectionManagementController extends Controller
{
    protected DemoElectionResolver $demoResolver;
    protected VoterSlugService $slugService;

    public function __construct(DemoElectionResolver $demoResolver, VoterSlugService $slugService)
    {
        $this->demoResolver = $demoResolver;
        $this->slugService = $slugService;
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