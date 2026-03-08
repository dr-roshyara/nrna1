<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Services\VoterSlugService;
use App\Services\DemoElectionResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

/**
 * ElectionController
 *
 * Handles election selection and context management.
 * Users must select an election (demo or real) before entering the voting flow.
 */
class ElectionController extends Controller
{
    protected VoterSlugService $slugService;

    /**
     * Inject VoterSlugService via constructor
     * The service is registered as singleton in AppServiceProvider
     */
    public function __construct(VoterSlugService $slugService)
    {
        $this->slugService = $slugService;
    }
    /**
     * Show election selection page
     * Users choose between demo (testing) and real (official) elections
     *
     * Route: GET /election/select
     */
    public function selectElection(Request $request)
    {
        Log::info('Election selection page accessed', [
            'user_id' => auth()->user()?->id,
        ]);

        // Get all active elections
        $elections = Election::where('is_active', true)
            ->orWhere('is_active', 1)
            ->get();

        if ($elections->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('error', 'No active elections available. Please try again later.');
        }

        // For API requests
        if ($request->wantsJson()) {
            return response()->json([
                'elections' => $elections->map(fn($e) => [
                    'id' => $e->id,
                    'slug' => $e->slug,
                    'name' => $e->name,
                    'type' => $e->type,
                    'description' => $e->description,
                    'is_demo' => $e->isDemo(),
                    'is_real' => $e->isReal(),
                    'is_active' => $e->isCurrentlyActive(),
                ]),
            ]);
        }

        return Inertia::render('Election/SelectElection', [
            'elections' => $elections->map(fn($e) => [
                'id' => $e->id,
                'slug' => $e->slug,
                'name' => $e->name,
                'type' => $e->type,
                'description' => $e->description,
                'is_demo' => $e->isDemo(),
                'is_real' => $e->isReal(),
                'badge' => $e->isDemo() ? 'DEMO' : 'OFFICIAL',
                'color' => $e->isDemo() ? 'blue' : 'green',
            ]),
        ]);
    }

    /**
     * Store election selection
     * User selects an election and is redirected to voting flow
     *
     * Route: POST /election/select
     */
    public function storeElection(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
        ]);

        $election = Election::findOrFail($validated['election_id']);

        // Verify election is active
        if (!$election->isCurrentlyActive()) {
            return redirect()->route('election.select')
                ->with('error', 'This election is not currently active.');
        }

        Log::info('Election selected', [
            'user_id' => auth()->user()?->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        // Store in session
        session([
            'selected_election_id' => $election->id,
            'selected_election_type' => $election->type,
        ]);

        try {
            // Generate voter slug for the user using injected service
            $slug = $this->slugService->getOrCreateActiveSlug(auth()->user());

            // For API requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Election selected',
                    'redirect' => route('slug.code.create', ['vslug' => $slug->slug]),
                    'election_id' => $election->id,
                    'election_type' => $election->type,
                ]);
            }

            // Redirect to voting flow with slug parameter
            return redirect()->route('slug.code.create', ['vslug' => $slug->slug])
                ->with('success', 'Election selected. You may now start voting.');
        } catch (\Exception $e) {
            Log::error('Failed to create voter slug', [
                'user_id' => auth()->user()?->id,
                'election_id' => $election->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('election.select')
                ->with('error', 'Unable to start voting. Please try again.');
        }
    }

    /**
     * Get currently selected election
     * Returns election details for the voting flow
     *
     * @return \App\Models\Election|null
     */
    public static function getSelectedElection(?Request $request = null): ?Election
    {
        $electionId = session('selected_election_id');

        if (!$electionId) {
            return null;
        }

        return Election::find($electionId);
    }

    /**
     * Start demo election voting flow
     * Explicitly selects demo election and redirects to voting
     *
     * Route: GET /election/demo/start
     */
    public function startDemo(Request $request)
    {
        $user = auth()->user();
        Log::info('🎬 Demo election start requested', [
            'user_id' => $user?->id,
            'user_org_id' => $user?->organisation_id,
            'request_path' => $request->path(),
        ]);

        // Get first demo election
        // CRITICAL: Use withoutGlobalScopes() because demo elections are accessible
        // to ALL users regardless of organisation context
        // Demo elections always have organisation_id = NULL but should be universally accessible
        $demoElection = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('is_active', true)
            ->first();

        if (!$demoElection) {
            Log::error('❌ No active demo election found', [
                'user_id' => $user?->id,
            ]);
            return redirect()->route('dashboard')
                ->with('error', 'Demo election is not available. Please try again later.');
        }

        Log::info('✅ Found demo election', [
            'user_id' => $user?->id,
            'election_id' => $demoElection->id,
            'election_org_id' => $demoElection->organisation_id,
        ]);

        // Set demo election in session
        session([
            'selected_election_id' => $demoElection->id,
            'selected_election_type' => 'demo',
        ]);

        Log::info('📝 Session updated with election', [
            'user_id' => $user?->id,
            'session_election_id' => session('selected_election_id'),
        ]);

        try {
            // Generate voter slug for the user using injected service
            Log::info('🔑 Generating voter slug...', [
                'user_id' => $user?->id,
            ]);

            // ✅ FIX: For DEMO elections, ALWAYS create a FRESH slug (forceNew = true)
            // This allows users to vote in demo unlimited times with new slugs each time
            $slug = $this->slugService->getOrCreateSlug($user, $demoElection, true);

            Log::info('✅ Voter slug generated successfully', [
                'user_id' => $user?->id,
                'slug' => $slug->slug,
                'election_id' => $slug->election_id,
            ]);

            // For API requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Demo election selected',
                    'redirect' => route('slug.demo-code.create', ['vslug' => $slug->slug]),
                ]);
            }

            // Redirect to DEMO voting flow with slug parameter
            // Uses demo-code.create (not real code.create)
            Log::info('🔄 Redirecting to demo code create page', [
                'user_id' => $user?->id,
                'slug' => $slug->slug,
                'route' => route('slug.demo-code.create', ['vslug' => $slug->slug]),
            ]);

            return redirect()->route('slug.demo-code.create', ['vslug' => $slug->slug])
                ->with('success', '🎮 Demo election selected. Test the voting system!');
        } catch (\Exception $e) {
            Log::error('❌ Failed to start demo election', [
                'user_id' => $user?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Unable to start demo election. Please try again.');
        }
    }

    /**
     * Clear selected election from session
     * Called when voting is complete or user cancels
     */
    public static function clearSelectedElection(): void
    {
        session()->forget(['selected_election_id', 'selected_election_type']);
    }
}
