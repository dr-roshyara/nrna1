<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class CommissionDashboardController extends Controller
{
    /**
     * Show commission dashboard
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentRole = $request->attributes->get('current_role', 'commission');

        // Get elections where user is commission member
        $elections = $user->electionCommissionRoles()
            ->get()
            ->map(function ($election) {
                return [
                    'id' => $election->id,
                    'title' => $election->title ?? 'Unnamed Election',
                    'status' => $election->status ?? 'active',
                    'organization_id' => $election->organization_id,
                ];
            });

        // Also include elections where user is legacy committee member
        if ($user->is_committee_member) {
            // This is a fallback for legacy committee members
            $elections = $elections->push([
                'id' => 'legacy',
                'title' => 'Current Election (Legacy)',
                'status' => 'active',
                'organization_id' => null,
            ]);
        }

        return Inertia::render('Commission/Dashboard', [
            'currentRole' => $currentRole,
            'elections' => $elections,
            'quickStats' => [
                'activeElections' => count($elections),
                'votesCast' => 0,
                'pendingVoters' => 0,
                'issues' => 0,
            ],
        ]);
    }
}
