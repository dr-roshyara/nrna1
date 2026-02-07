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
     * Post-login routing logic for multi-role dashboard system:
     * - Users with multiple dashboard roles → role.selection (choose dashboard)
     * - Users with single dashboard role → redirect to that role's dashboard
     * - Users with legacy roles only → backward compatibility routing
     *
     * Dashboard roles: admin, commission, voter
     * Legacy roles: hasRole('admin'), hasRole('election_officer'), is_voter, is_committee_member
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // PRIORITY 1: First-time users → Welcome Dashboard (onboarding flow)
        if ($this->isFirstTimeUser($user)) {
            return redirect()->route('dashboard.welcome');
        }

        // PRIORITY 2: New system dashboard roles
        // Get dashboard roles (new system + legacy mapping)
        $dashboardRoles = $user->getDashboardRoles();

        // If user has multiple dashboard roles, show role selection
        if (count($dashboardRoles) > 1) {
            return redirect()->route('role.selection');
        }

        // If user has exactly one dashboard role, redirect directly to that dashboard
        if (count($dashboardRoles) === 1) {
            $role = reset($dashboardRoles);
            return match($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'commission' => redirect()->route('commission.dashboard'),
                'voter' => redirect()->route('vote.dashboard'),
                default => redirect()->route('role.selection'),
            };
        }

        // PRIORITY 3: LEGACY FALLBACK: User has no dashboard roles
        // Check for old Spatie roles (backward compatibility)
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->is_voter) {
            return redirect()->route('dashboard'); // Existing voter dashboard
        }

        if ($user->is_committee_member) {
            return redirect()->route('commission.dashboard');
        }

        // Default fallback: existing voter dashboard (backward compatible)
        return redirect()->route('dashboard');
    }

    /**
     * Detect first-time users (new diaspora communities with no existing roles/organizations)
     *
     * First-time user criteria:
     * - No organizations (via user_organization_roles)
     * - No commission memberships (no election_commission_members)
     * - Not a voter (is_voter = false)
     * - No admin/election_officer Spatie roles
     * - Account created within 7 days
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isFirstTimeUser($user): bool
    {
        // Account must be recent (within 7 days)
        if ($user->created_at->diffInDays(now()) > 7) {
            return false;
        }

        // Check if user has organization roles (new system)
        $hasOrgRoles = \DB::table('user_organization_roles')
            ->where('user_id', $user->id)
            ->exists();

        if ($hasOrgRoles) {
            return false;
        }

        // Check if user is commission member (new system)
        $hasCommissionMembership = \DB::table('election_commission_members')
            ->where('user_id', $user->id)
            ->exists();

        if ($hasCommissionMembership) {
            return false;
        }

        // Check legacy roles
        if ($user->is_voter || $user->hasRole('admin') || $user->hasRole('election_officer')) {
            return false;
        }

        // If user has no roles/organizations/commission membership, they're a first-time user
        return true;
    }
}
