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
        
        // ✅ Not authenticated: Show welcome page
        if (!$authUser) {
            return Inertia::render('Welcome', [
                'canLogin' => \Route::has('login'),
                'canRegister' => \Route::has('register'),
                'laravelVersion' => app()->version(),
                'phpVersion' => PHP_VERSION,
            ]);
        }
        
        // Make voting-related fields visible
        $authUser->makeVisible(['is_voter', 'can_vote', 'has_voted', 'can_vote_now']);
        
        $ballotAccess = $authUser->getBallotAccessStatus();
        
        \Log::info('Dashboard Debug for User ' . $authUser->id, [
            'user_fields' => [
                'is_voter' => $authUser->is_voter,
                'can_vote' => $authUser->can_vote,
                'has_voted' => $authUser->has_voted,
                'is_committee_member' => $authUser->is_committee_member,
            ],
            'ballot_access' => $ballotAccess,
            'ballot_access_type' => gettype($ballotAccess),
        ]);
        
        if (!is_array($ballotAccess)) {
            $ballotAccess = [
                'can_access' => false,
                'error_type' => 'system_error',
                'error_title' => 'System Error | प्रणाली त्रुटि',
                'error_message_nepali' => 'प्रणालीमा त्रुटि भएको छ।',
                'error_message_english' => 'A system error occurred.'
            ];
        }
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
            'is_active' => config('election.is_active', true), // ✅ Make sure this config exists
            'results_published' => config('election.results_published', false)
        ];
        
        return Inertia::render('Dashboard/ElectionDashboard', [
            'authUser' => $authUser,
            'ballotAccess' => $ballotAccess,        // ✅ Always an array
            'votingStatus' => $votingStatus,        // ✅ Array or null
            'electionStatus' => $electionStatus     // ✅ Always an array
        ]);
    }
}