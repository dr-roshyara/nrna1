<?php

namespace App\Http\Controllers\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Code;
use App\Models\Election;
use App\Http\Controllers\Controller;
use App\Services\ElectionService;

class ElectionController extends Controller
{
    /**
     * ✅ Dashboard method - Simplified for single election system
     *
     * Logic:
     * - Unauthenticated → Welcome page
     * - Authenticated voter + real election active + eligible → ElectionPage (direct voting)
     * - Otherwise → ElectionDashboard (dashboard view)
     */
    public function dashboard()
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

        // Update user IP
        $authUser->update(['user_ip' => $ipAddress]);

        // Check if the ONE real election is active
        $realElection = Election::where('type', 'real')
            ->where('is_active', true)
            ->first();

        // Check if user has an active code with voting eligibility
        $userCode = Code::where('user_id', $authUser->id)
            ->where('can_vote_now', 1)
            ->first();

        // Election day: Real election active AND user is voter AND user is eligible
        if ($realElection && $realElection->isCurrentlyActive() &&
            $authUser->is_voter && $userCode) {
            // Show voting page (skip dashboard, go directly to ballot)
            return Inertia::render('Election/ElectionPage', [
                'activeElection' => $realElection,
                'authUser' => $authUser,
                'ipAddress' => $ipAddress,
            ]);
        }

        // Non-election day OR not eligible → Show dashboard
        $authUser->makeVisible(['is_voter', 'can_vote', 'has_voted', 'can_vote_now']);
        $ballotAccess = $this->determineBallotAccess($authUser);

        $votingStatus = null;
        if ($ballotAccess['can_access']) {
            $code = Code::where('user_id', $authUser->id)->first();

            $votingStatus = [
                'has_code' => $code !== null,
                'can_vote_now' => $code ? (bool) $code->can_vote_now : false,
                'has_voted' => $code ? (bool) $code->has_voted : false,
                'voting_started_at' => $code ? $code->voting_started_at : null,
                'voting_time_remaining' => $code && $code->voting_started_at ?
                    max(0, ($code->voting_time_in_minutes ?? 20) - now()->diffInMinutes($code->voting_started_at)) : 0,
                'has_agreed_to_vote' => $code ? (bool) ($code->has_agreed_to_vote ?? false) : false
            ];
        }

        $electionStatus = ElectionService::getElectionStatus();

        return Inertia::render('Dashboard/ElectionDashboard', [
            'authUser' => $authUser,
            'ballotAccess' => $ballotAccess,
            'votingStatus' => $votingStatus,
            'electionStatus' => $electionStatus,
            'ipAddress' => $ipAddress,
            'useSlugPath' => config('election.use_slug_path', false),
            'realElectionSlug' => $realElection ? $realElection->slug : null
        ]);
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
     */
    public function startDemo()
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return redirect()->route('login');
        }

        // Get the ONE demo election
        $demoElection = Election::where('type', 'demo')
            ->where('is_active', true)
            ->first();

        if (!$demoElection) {
            return redirect()->route('dashboard')
                ->with('error', 'Demo election not available');
        }

        // Bypass all voter checks for demo - direct to code entry
        return redirect()->route('code.create', ['vslug' => $demoElection->slug]);
    }

    public function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}