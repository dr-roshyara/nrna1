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

        // Check if user already has an active slug first
        $existingSlug = VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingSlug) {
            // User already has an active slug, redirect to appropriate step
            Log::info('Redirecting user to existing active slug', [
                'user_id' => $user->id,
                'slug' => $existingSlug->slug,
                'current_step' => $existingSlug->current_step,
            ]);

            return $this->redirectToSlugStep($existingSlug);
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

        // Generate secure voting slug
        $result = $this->securityService->secureSlugGeneration($user, 'dashboard_voting_start');

        if (!$result['success']) {
            Log::error('Failed to generate voting slug', [
                'user_id' => $user->id,
                'reasons' => $result['reasons'] ?? ['unknown_error'],
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('election.dashboard')->with('error',
                'Failed to start voting session. Please try again or contact support.'
            );
        }

        $slug = $result['slug'];

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
     * Redirect to the appropriate step for an existing slug
     */
    private function redirectToSlugStep(VoterSlug $slug): \Illuminate\Http\RedirectResponse
    {
        switch ($slug->current_step) {
            case 1:
                return redirect()->route('slug.code.create', ['vslug' => $slug->slug]);
            case 2:
                return redirect()->route('slug.code.agreement', ['vslug' => $slug->slug]);
            case 3:
                return redirect()->route('slug.vote.create', ['vslug' => $slug->slug]);
            case 4:
                return redirect()->route('slug.vote.submit', ['vslug' => $slug->slug]);
            case 5:
            default:
                // Voting complete or unknown step - go to verification
                return redirect()->route('vote.verify_to_show');
        }
    }
}