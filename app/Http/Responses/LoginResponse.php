<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\Election;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * Post-login routing logic for single election system:
     * - Admins → admin.dashboard
     * - Voters during active real election → show dashboard/election page
     * - Non-voters → voter.dashboard
     *
     * Note: Voter eligibility (can_vote_now) is determined when entering the voting code,
     * stored in the Code model. We only check if user is registered as a voter here.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // ADMIN FLOW: Always go to admin dashboard
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            return redirect()->route('admin.dashboard');
        }

        // VOTER FLOW: If user is registered as a voter, show them the voting dashboard
        // The ElectionController dashboard will check:
        // - If real election is currently active
        // - If eligible to vote (Code model stores can_vote_now)
        // And route appropriately to ElectionPage or regular Dashboard
        if ($user->is_voter) {
            // Go to election dashboard which will display election or ask to wait
            return redirect()->route('dashboard');
        }

        // Non-voter → regular dashboard
        return redirect()->route('dashboard');
    }
}
