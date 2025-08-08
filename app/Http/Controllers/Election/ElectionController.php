<?php

namespace App\Http\Controllers\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Code;
use App\Http\Controllers\Controller;

class ElectionController extends Controller
{
    /**
     * ✅ FIXED: Dashboard method with proper data handling
     */
    public function dashboard()
    {
        $authUser = Auth::user();
        $ipAddress = $this->getUserIpAddr();
         
 
        // ✅ Not authenticated: Show welcome page
        if ($authUser) {
            $authUser->update([
                'user_ip' => $ipAddress
            ]);
        } else {
            return Inertia::render('Welcome', [
                'canLogin' => \Route::has('login'),
                'canRegister' => \Route::has('register'),
                'user_ip' => $ipAddress
            ]);
        }

        // Make voting-related fields visible
        $authUser->makeVisible(['is_voter', 'can_vote', 'has_voted', 'can_vote_now']);
        
        // ✅ FIX: Implement proper ballot access logic here instead of relying on a potentially missing method
        $ballotAccess = $this->determineBallotAccess($authUser);
        
        \Log::info('Dashboard Debug for User ' . $authUser->id, [
            'user_fields' => [
                'is_voter' => $authUser->is_voter,
                'can_vote' => $authUser->can_vote,
                'has_voted' => $authUser->has_voted,
                'is_committee_member' => $authUser->is_committee_member ?? false,
            ],
            'ballot_access' => $ballotAccess,
            'ballot_access_type' => gettype($ballotAccess),
        ]);
        
        $votingStatus = null;
        if ($ballotAccess['can_access']) {
            $code = Code::where('user_id', $authUser->id)->first();
            
            // ✅ Determine voting status based on Code model only
            $hasVoted = $code ? (bool) $code->has_voted : false;
            $canVoteNow = $code ? (bool) $code->can_vote_now : false;
            $hasAgreed = $code ? (bool) ($code->has_agreed_to_vote ?? false) : false;
            
            $votingStatus = [
                'has_code' => $code !== null,
                'can_vote_now' => $canVoteNow,
                'has_voted' => $hasVoted,  // ✅ Only from Code model
                'voting_started_at' => $code ? $code->voting_started_at : null,
                'voting_time_remaining' => $code && $code->voting_started_at ? 
                    max(0, ($code->voting_time_in_minutes ?? 20) - now()->diffInMinutes($code->voting_started_at)) : 0,
                'has_agreed_to_vote' => $hasAgreed
            ];
        }
        
        // ✅ Election system status
        $electionStatus = [
            'is_active' => config('election.is_active', true),
            'results_published' => config('election.results_published', false)
        ];
        
        return Inertia::render('Dashboard/ElectionDashboard', [
            'authUser' => $authUser,
            'ballotAccess' => $ballotAccess,
            'votingStatus' => $votingStatus,
            'electionStatus' => $electionStatus,
            'ipAddress' => $ipAddress
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
                'error_title' => 'निर्वाचन सक्रिय छैन | Election Inactive',
                'error_message_nepali' => 'निर्वाचन अहिले सक्रिय छैन।',
                'error_message_english' => 'Election is not currently active.'
            ];
        }

        // Check if user is registered as voter
        if (!$user->is_voter) {
            return [
                'can_access' => false,
                'error_type' => 'not_voter',
                'error_title' => 'मतदाता दर्ता नभएको | Not Registered as Voter',
                'error_message_nepali' => 'तपाईं मतदाताको रूपमा दर्ता हुनुभएको छैन।',
                'error_message_english' => 'You are not registered as a voter.'
            ];
        }

        // Check if user is approved to vote
        if (!$user->can_vote) {
            return [
                'can_access' => false,
                'error_type' => 'vote_not_approved',
                'error_title' => 'मतदान अनुमति नभएको | Voting Not Approved',
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

        // All checks passed - user can vote
        return [
            'can_access' => true,
            'access_type' => 'can_vote',
            'message_nepali' => 'तपाईं मतदान गर्न सक्नुहुन्छ।',
            'message_english' => 'You can vote.'
        ];
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