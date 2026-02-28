<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RoleSelectionController extends Controller
{
    /**
     * Show role selection dashboard
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get available roles for this user
        $availableRoles = $user->getDashboardRoles();

        // If only one role, redirect directly to that dashboard
        if (count($availableRoles) === 1) {
            return $this->redirectToRole(reset($availableRoles));
        }

        // Gather data for each role
        $data = [
            'userName' => $user->name ?? $user->email,
            'userEmail' => $user->email,
            'availableRoles' => $availableRoles,
            'adminStats' => $this->getAdminStats($user),
            'commissionStats' => $this->getCommissionStats($user),
            'voterStats' => $this->getVoterStats($user),
            'userOrganizations' => $user->organisationRoles()
                ->withPivot('role')
                ->get()
                ->map(function ($org) {
                    return [
                        'id' => $org->id,
                        'name' => $org->name,
                        'role' => $org->pivot->role,
                    ];
                })
                ->toArray(),
        ];

        return Inertia::render('RoleSelection/Index', $data);
    }

    /**
     * Switch to different role
     *
     * @param Request $request
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchRole(Request $request, $role)
    {
        $user = $request->user();
        $availableRoles = $user->getDashboardRoles();

        // Validate user has this role
        if (!in_array($role, $availableRoles)) {
            return redirect()->route('role.selection')
                ->with('error', 'You do not have access to this role');
        }

        // Store role in session for current request
        session(['current_role' => $role]);

        return $this->redirectToRole($role);
    }

    /**
     * Redirect to appropriate dashboard based on role
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToRole($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'commission' => redirect()->route('commission.dashboard'),
            'voter' => redirect()->route('vote.dashboard'),
            default => redirect()->route('role.selection'),
        };
    }

    /**
     * Get admin statistics for this user
     *
     * @param $user
     * @return array
     */
    private function getAdminStats($user)
    {
        $adminOrgs = $user->organisationRoles()
            ->wherePivot('role', 'admin')
            ->count();

        return [
            'organisations' => $adminOrgs,
            'activeElections' => 0, // Will be populated later
            'totalMembers' => 0, // Will be populated later
        ];
    }

    /**
     * Get commission statistics for this user
     *
     * @param $user
     * @return array
     */
    private function getCommissionStats($user)
    {
        $electionCount = $user->electionCommissionRoles()->count();

        return [
            'elections' => $electionCount,
            'votesCast' => 0, // Will be populated later
            'participationRate' => 0, // Will be populated later
        ];
    }

    /**
     * Get voter statistics for this user
     *
     * @param $user
     * @return array
     */
    private function getVoterStats($user)
    {
        $pendingDemoVotes = 0;
        $pendingRealVotes = 0;
        $castVotes = 0;

        // Get pending and cast votes from VoterRegistration
        if (method_exists($user, 'voterRegistrations')) {
            $pendingDemoVotes = $user->voterRegistrations()
                ->where('election_type', 'demo')
                ->where('status', 'approved')
                ->count();

            $pendingRealVotes = $user->voterRegistrations()
                ->where('election_type', 'real')
                ->where('status', 'approved')
                ->count();

            $castVotes = $user->voterRegistrations()
                ->where('status', 'voted')
                ->count();
        }

        return [
            'pending' => $pendingDemoVotes + $pendingRealVotes,
            'cast' => $castVotes,
        ];
    }
}
