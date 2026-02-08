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

        \Log::info('LoginResponse: Processing login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => $user->created_at->toDateString(),
        ]);

        // Role detection logging
        \Log::info('LoginResponse: User role detection starting', [
            'user_id' => $user->id,
            'has_org_roles' => \DB::table('user_organization_roles')->where('user_id', $user->id)->exists(),
            'has_commission' => \DB::table('election_commission_members')->where('user_id', $user->id)->exists(),
            'is_voter' => (bool)$user->is_voter,
            'is_committee_member' => (bool)($user->is_committee_member ?? false),
            'spatie_roles' => $user->roles->pluck('name')->toArray() ?? [],
        ]);

        // PRIORITY 1: First-time users → Welcome Dashboard (onboarding flow)
        if ($this->isFirstTimeUser($user)) {
            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'first_time_user',
                'destination' => 'dashboard.welcome',
                'reason' => 'No organizations, commissions, or existing roles detected',
            ]);
            return redirect()->route('dashboard.welcome');
        }

        // PRIORITY 2: New system dashboard roles
        // Get dashboard roles (new system + legacy mapping)
        $dashboardRoles = $user->getDashboardRoles();

        \Log::info('LoginResponse: Dashboard roles resolved', [
            'user_id' => $user->id,
            'dashboard_roles' => $dashboardRoles,
            'role_count' => count($dashboardRoles),
        ]);

        // If user has multiple dashboard roles, show role selection
        if (count($dashboardRoles) > 1) {
            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'multiple_roles',
                'destination' => 'role.selection',
                'roles' => $dashboardRoles,
                'reason' => 'User has ' . count($dashboardRoles) . ' dashboard roles',
            ]);
            return redirect()->route('role.selection');
        }

        // If user has exactly one dashboard role, redirect directly to that dashboard
        if (count($dashboardRoles) === 1) {
            $role = reset($dashboardRoles);
            $destination = match($role) {
                'admin' => 'admin.dashboard',
                'commission' => 'commission.dashboard',
                'voter' => 'vote.dashboard',
                default => 'role.selection',
            };

            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'single_role',
                'role' => $role,
                'destination' => $destination,
                'reason' => 'User has exactly one dashboard role: ' . $role,
            ]);

            return redirect()->route($destination);
        }

        // PRIORITY 3: LEGACY FALLBACK: User has no dashboard roles
        // Check for old Spatie roles (backward compatibility)
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_admin',
                'destination' => 'admin.dashboard',
                'reason' => 'User has legacy Spatie admin or election_officer role',
            ]);
            return redirect()->route('admin.dashboard');
        }

        if ($user->is_voter) {
            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_voter',
                'destination' => 'dashboard',
                'reason' => 'User is marked as voter (legacy)',
            ]);
            return redirect()->route('dashboard'); // Existing voter dashboard
        }

        if ($user->is_committee_member) {
            \Log::info('LoginResponse: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_committee_member',
                'destination' => 'commission.dashboard',
                'reason' => 'User is marked as committee member (legacy)',
            ]);
            return redirect()->route('commission.dashboard');
        }

        // Default fallback: existing voter dashboard (backward compatible)
        \Log::warning('LoginResponse: Redirect decision - Default fallback', [
            'user_id' => $user->id,
            'decision' => 'default_fallback',
            'destination' => 'dashboard',
            'reason' => 'No roles detected - using default fallback',
        ]);
        return redirect()->route('dashboard');
    }

    /**
     * Detect first-time users (new diaspora communities with no existing roles/organizations)
     *
     * First-time user criteria (all must be true):
     * - No organizations assigned (via user_organization_roles)
     * - No commission memberships (no election_commission_members)
     * - Not a voter (is_voter = false)
     * - No admin/election_officer Spatie roles
     *
     * Note: Time-based detection removed - users can return at any time
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isFirstTimeUser($user): bool
    {
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
