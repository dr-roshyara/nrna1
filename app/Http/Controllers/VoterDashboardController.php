<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class VoterDashboardController extends Controller
{
    /**
     * Show voter dashboard with available elections
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get active elections user can vote in
        $activeElections = [];
        $pendingVotes = 0;
        $castVotes = 0;

        // Get user's voter registrations
        if (method_exists($user, 'voterRegistrations')) {
            // Get approved elections
            $approved = $user->voterRegistrations()
                ->where('status', 'approved')
                ->with('election')
                ->get();

            $activeElections = $approved->map(function ($registration) {
                return [
                    'id' => $registration->election_id,
                    'title' => $registration->election?->title ?? 'Election ' . $registration->election_id,
                    'type' => $registration->election_type,
                    'status' => $registration->status,
                    'can_vote' => !$registration->hasVoted(),
                ];
            })->toArray();

            $pendingVotes = $approved->where('status', '!=', 'voted')->count();
            $castVotes = $user->voterRegistrations()->where('status', 'voted')->count();
        }

        return Inertia::render('Vote/Dashboard', [
            'activeElections' => $activeElections,
            'pendingVotes' => $pendingVotes,
            'votingHistory' => [],
            'quickStats' => [
                'pending' => $pendingVotes,
                'cast' => $castVotes,
                'completed' => false,
            ],
        ]);
    }
}
