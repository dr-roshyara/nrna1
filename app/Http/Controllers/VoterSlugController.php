<?php

namespace App\Http\Controllers;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\User;
use App\Models\VoterSlug;
use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Services\VoterSlugService;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use App\Services\VotingSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling voter slug generation and initial voting flow
 */
class VoterSlugController extends Controller
{
    protected VoterSlugService $slugService;
    protected VotingSecurityService $securityService;

    public function __construct(VoterSlugService $slugService, VotingSecurityService $securityService)
    {
        $this->slugService = $slugService;
        $this->securityService = $securityService;
    }

    /**
     * Start the voting process by generating a secure voting slug
     *
     * This route is called from the Election Dashboard when user clicks "Vote Here"
     * It generates a new voting slug and redirects to the appropriate voting step
     */
    public function start(Request $request)
    {
        $user = Auth::user();

        // PBDIGIT-274 S4: a REAL election in the session goes through the issuer (no deactivation before
        // deciding, no legacy user flags). Demo elections and the no-session case keep the legacy path (D1).
        if ($realElection = $this->selectedRealElection()) {
            return $this->issueForRealElection($user, $realElection);
        }

        // Debug log to track infinite loops
        Log::info('=== VOTER START CALLED ===', [
            'user_id' => $user->id,
            'timestamp' => now(),
            'route' => $request->route()?->getName(), // no URL in logs (PBDIGIT-274 S9c)
        ]);

        // Check if user already has an active slug first
        $existingSlug = VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

            if ($existingSlug) {
            // Double-check expiration to handle timezone issues
            if ($existingSlug->expires_at->isPast()) {
                Log::warning('Found existing slug but it has expired, deactivating', [
                    'user_id' => $user->id,
                    'slug_id' => $existingSlug->id, // the id, never the link (PBDIGIT-274 S9c)
                    'expires_at' => $existingSlug->expires_at,
                    'current_time' => now(),
                ]);

                // Deactivate expired slug
                $existingSlug->update(['is_active' => false]);
                $existingSlug = null; // Continue to create new slug
            } else {
                // User already has an active slug
                // Check if vote is completed - if yes, they cannot restart
                if ($existingSlug->vote_completed) {
                    Log::info('User has completed vote, redirecting to verify page', [
                        'user_id' => $user->id,
                        'slug_id' => $existingSlug->id, // the id, never the link (PBDIGIT-274 S9c)
                    ]);
                    return redirect()->route('vote.verify_to_show');
                }

                // Vote not completed - redirect to appropriate step
                Log::info('Redirecting user to existing active slug', [
                    'user_id' => $user->id,
                    'slug_id' => $existingSlug->id, // the id, never the link (PBDIGIT-274 S9c)
                    'current_step' => $existingSlug->current_step,
                ]);

                return $this->redirectToSlugStep($existingSlug);
            }
        }

        // No existing slug - check if user can create a new one
        $eligibilityCheck = $this->securityService->canIssueVotingSlug($user);

        if (!$eligibilityCheck['can_issue']) {
            Log::warning('Voter start attempt denied', [
                'user_id' => $user->id,
                'reasons' => $eligibilityCheck['reasons'],
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('election.dashboard')->with('error',
                'Cannot start voting: ' . implode(', ', $eligibilityCheck['reasons'])
            );
        }

        // Get the selected election from session
        $selectedElectionId = session('selected_election_id');
        if (!$selectedElectionId) {
            Log::warning('No election selected in session', [
                'user_id' => $user->id,
            ]);
            return redirect()->route('election.dashboard')->with('error',
                'No election selected. Please select an election first.'
            );
        }

        $election = \App\Models\Election::withoutGlobalScopes()->find($selectedElectionId);
        if (!$election) {
            Log::warning('Selected election not found', [
                'user_id' => $user->id,
                'election_id' => $selectedElectionId,
            ]);
            return redirect()->route('election.dashboard')->with('error',
                'Selected election not found.'
            );
        }

        // Validate election lifecycle state (SSOT check)
        $lifecycle = ElectionLifecycle::of($election);
        if (!$lifecycle->canVote()) {
            Log::warning('Slug creation blocked by election lifecycle', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'election_state' => $lifecycle->state()->value,
                'blocked_reason' => $lifecycle->blockedReason(),
            ]);

            return redirect()->route('election.dashboard')
                ->with('error', 'Voting is not currently allowed: ' . $lifecycle->blockedReason());
        }

        // ✅ Use the new getOrCreateSlug method with proper election type handling
        try {
            $slug = $this->slugService->getOrCreateSlug($user, $election);
        } catch (\Exception $e) {
            Log::error('Failed to get or create voting slug', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('election.dashboard')->with('error',
                'Failed to start voting session. Please try again or contact support.'
            );
        }

        Log::info('New voting slug generated from dashboard', [
            'user_id' => $user->id,
            'slug_id' => $slug->id, // the id, never the link (PBDIGIT-274 S9c)
            'expires_at' => $slug->expires_at,
            'ip_address' => $request->ip(),
        ]);

        // Redirect to the slug-based code creation (step 1)
        return redirect()->route('slug.code.create', ['vslug' => $slug->slug]);
    }

    /**
     * Restart voting from the beginning
     *
     * This allows users to completely restart the voting process if they haven't
     * completed their vote yet. This deactivates their current session and creates
     * a new one, starting from step 1.
     */
    public function restart(Request $request)
    {
        $user = Auth::user();

        // PBDIGIT-274 S4: for a REAL election, "restart" is the issuer's decision too: a valid link is kept
        // (no replacement while valid, acceptance 7); an expired one is replaced by a new row, history kept.
        if ($realElection = $this->selectedRealElection()) {
            return $this->issueForRealElection($user, $realElection, 'Voting session restarted. You can start fresh from the beginning.');
        }

        Log::info('=== VOTER RESTART REQUESTED ===', [
            'user_id' => $user->id,
            'timestamp' => now(),
        ]);

        // Find the user's current active slug
        $existingSlug = VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($existingSlug) {
            // Check if vote is already completed
            if ($existingSlug->vote_completed) {
                Log::warning('Restart attempt on completed vote denied', [
                    'user_id' => $user->id,
                    'slug_id' => $existingSlug->id, // the id, never the link (PBDIGIT-274 S9c)
                ]);

                return redirect()->route('vote.verify_to_show')
                    ->with('error', 'Your vote has already been completed. You cannot restart.');
            }

            // Vote not completed - deactivate current session
            Log::info('Deactivating current slug for restart', [
                'user_id' => $user->id,
                'old_slug_id' => $existingSlug->id, // the id, never the link (PBDIGIT-274 S9c)
                'was_at_step' => $existingSlug->current_step,
            ]);

            $existingSlug->update(['is_active' => false]);

            // Clear any session data
            if ($user->code && $user->code->session_name) {
                $request->session()->forget($user->code->session_name);
            }
        }

        // Get the selected election from session
        $selectedElectionId = session('selected_election_id');
        if (!$selectedElectionId) {
            Log::warning('No election selected in session during restart', [
                'user_id' => $user->id,
            ]);
            return redirect()->route('election.dashboard')->with('error',
                'No election selected. Please select an election first.'
            );
        }

        $election = \App\Models\Election::withoutGlobalScopes()->find($selectedElectionId);
        if (!$election) {
            Log::warning('Selected election not found during restart', [
                'user_id' => $user->id,
                'election_id' => $selectedElectionId,
            ]);
            return redirect()->route('election.dashboard')->with('error',
                'Selected election not found.'
            );
        }

        // Validate election lifecycle state (SSOT check)
        $lifecycle = ElectionLifecycle::of($election);
        if (!$lifecycle->canVote()) {
            Log::warning('Slug restart blocked by election lifecycle', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'election_state' => $lifecycle->state()->value,
                'blocked_reason' => $lifecycle->blockedReason(),
            ]);

            return redirect()->route('election.dashboard')
                ->with('error', 'Voting is not currently allowed: ' . $lifecycle->blockedReason());
        }

        // Create a new voting session from scratch
        try {
            // ✅ Use the new getOrCreateSlug method with proper election type handling
            // For demo elections with restart, force creation of new slug
            $forceNew = ($election->type === 'demo');
            $newSlug = $this->slugService->getOrCreateSlug($user, $election, $forceNew);

            Log::info('New voting session created for restart', [
                'user_id' => $user->id,
                'new_slug_id' => $newSlug->id, // the id, never the link (PBDIGIT-274 S9c)
            ]);

            return redirect()->route('slug.code.create', ['vslug' => $newSlug->slug])
                ->with('success', 'Voting session restarted. You can start fresh from the beginning.');

        } catch (\Exception $e) {
            Log::error('Failed to create new slug during restart', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('election.dashboard')
                ->with('error', 'Failed to restart voting session. Please try again.');
        }
    }

    /**
     * Redirect to the appropriate step for an existing slug
     *
     * RESTART LOGIC: Allows voters to restart if they haven't completed voting.
     * This handles cases where users are interrupted during the voting process.
     */
    /** The election selected in the session, if it exists and is a real election (PBDIGIT-274 S4). */
    private function selectedRealElection(): ?\App\Models\Election
    {
        $id = session('selected_election_id');
        $election = $id ? \App\Models\Election::withoutGlobalScopes()->find($id) : null;

        return ($election && trim((string) $election->type) === 'real') ? $election : null;
    }

    /** Issue, reuse or replace the voter's link through VoterSlugIssuer (PBDIGIT-274 S4). */
    private function issueForRealElection(User $user, \App\Models\Election $election, ?string $success = null): \Illuminate\Http\RedirectResponse
    {
        try {
            $issuance = VoterSlugIssuer::fresh()->issue($user, $election);
        } catch (VoterSlugIssuanceConflict $e) {
            return redirect()->route('election.dashboard')->with('error', $e->getUserMessage());
        }

        if ($issuance->outcome === VoterSlugIssuance::ALREADY_VOTED) {
            return redirect()->route('election.dashboard')->with('info', 'You have already voted.');
        }
        if (! $issuance->hasLink()) {
            return redirect()->route('election.dashboard')->with('error', 'You are not eligible to vote in this election at this time.');
        }

        $redirect = redirect()->route('slug.code.create', ['vslug' => $issuance->slug->slug]);

        return $success !== null ? $redirect->with('success', $success) : $redirect;
    }

    private function redirectToSlugStep(VoterSlug $slug): \Illuminate\Http\RedirectResponse
    {
        // RESTART MECHANISM: Allow restart if vote not completed and in voting/verification phase
        // Steps 3-4 are the "voting in progress" phase where user can restart
        if (!$slug->vote_completed && $slug->current_step >= 3 && $slug->current_step <= 4) {
            Log::info('Allowing voter to restart voting session', [
                'user_id' => Auth::id(),
                'slug_id' => $slug->id, // the id, never the link (PBDIGIT-274 S9c)
                'current_step' => $slug->current_step,
                'vote_completed' => $slug->vote_completed,
                'reason' => 'Vote not yet completed - allowing restart from step 3'
            ]);

            // Reset to step 3 to allow fresh candidate selection
            $progressService = new \App\Services\VoterProgressService();
            $progressService->resetToStep($slug, 3);

            return redirect()->route('slug.vote.create', ['vslug' => $slug->slug])
                ->with('info', 'You can update your selections. Your previous choices were not saved.');
        }

        // NORMAL PROGRESSION: Follow step-by-step flow for early steps or completed votes
        switch ($slug->current_step) {
            case 1:
                return redirect()->route('slug.code.create', ['vslug' => $slug->slug]);

            case 2:
                return redirect()->route('slug.code.agreement', ['vslug' => $slug->slug]);

            case 3:
                return redirect()->route('slug.vote.create', ['vslug' => $slug->slug]);

            case 4:
                // BUGFIX: Step 4 is the verification page (GET route), not submit (POST route)
                return redirect()->route('slug.vote.verify', ['vslug' => $slug->slug]);

            case 5:
            default:
                // Voting complete (step 5) or unknown step - show verification page
                return redirect()->route('vote.verify_to_show');
        }
    }
}