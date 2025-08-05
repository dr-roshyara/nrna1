<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteEligibility
{
    /**
     * ✅ FIXED: Handle an incoming request with proper type checking
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        // ✅ Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access voting.');
        }
        
        // ✅ FIXED: Use the improved eligibility check
        if (!$user->isEligibleToVote()) {
            // ✅ Get detailed status for better error message
            $ballotAccess = $user->getBallotAccessStatus();
            
            $errorMessage = 'You are not eligible to vote.';
            if (isset($ballotAccess['error_message_english'])) {
                $errorMessage = $ballotAccess['error_message_english'];
            }
            
            // ✅ FIXED: Use the correct route name
            return redirect()->route('dashboard') // or 'electiondashboard' if that's your route name
                ->with('error', $errorMessage)
                ->with('ballot_access_error', $ballotAccess);
        }
        
        return $next($request);
    }
}