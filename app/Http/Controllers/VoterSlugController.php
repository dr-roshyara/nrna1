<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
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

        // Debug log to track infinite loops
        Log::info('=== VOTER START CALLED ===', [
            'user_id' => $user->id,
            'timestamp' => now(),
            'request_url' => $request->fullUrl(),
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
                    'slug' => $existingSlug->slug,
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
                        'slug' => $existingSlug->slug,
                    ]);
                    return redirect()->route('vote.verify_to_show');
                }

                // Vote not completed - redirect to appropriate step
                Log::info('Redirecting user to existing active slug', [
                    'user_id' => $user->id,
                    'slug' => $existingSlug->slug,
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

        // Use the enhanced getOrCreateActiveSlug method for better user experience
        try {
            $slug = $this->slugService->getOrCreateActiveSlug($user);
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
            'slug' => $slug->slug,
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
                    'slug' => $existingSlug->slug,
                ]);

                return redirect()->route('vote.verify_to_show')
                    ->with('error', 'Your vote has already been completed. You cannot restart.');
            }

            // Vote not completed - deactivate current session
            Log::info('Deactivating current slug for restart', [
                'user_id' => $user->id,
                'old_slug' => $existingSlug->slug,
                'was_at_step' => $existingSlug->current_step,
            ]);

            $existingSlug->update(['is_active' => false]);

            // Clear any session data
            if ($user->code && $user->code->session_name) {
                $request->session()->forget($user->code->session_name);
            }
        }

        // Create a new voting session from scratch
        try {
            $newSlug = $this->slugService->getOrCreateActiveSlug($user);

            Log::info('New voting session created for restart', [
                'user_id' => $user->id,
                'new_slug' => $newSlug->slug,
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
    private function redirectToSlugStep(VoterSlug $slug): \Illuminate\Http\RedirectResponse
    {
        // RESTART MECHANISM: Allow restart if vote not completed and in voting/verification phase
        // Steps 3-4 are the "voting in progress" phase where user can restart
        if (!$slug->vote_completed && $slug->current_step >= 3 && $slug->current_step <= 4) {
            Log::info('Allowing voter to restart voting session', [
                'user_id' => Auth::id(),
                'slug' => $slug->slug,
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