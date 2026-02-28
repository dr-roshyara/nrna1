<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class DashboardResolver
{
    /**
     * Resolve user's dashboard based on roles and system state
     *
     * Priority routing:
     * 1. First-time users (no roles/orgs) → welcome dashboard
     * 2. New system dashboard roles → role selection or specific dashboard
     * 3. Legacy system roles → backward compatible routing
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function resolve(User $user): RedirectResponse
    {
        \Log::info('DashboardResolver: Processing user', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // PRIORITY 1: First-time users → Welcome Dashboard
        if ($this->isFirstTimeUser($user)) {
            return $this->redirectToFirstTimeUser($user);
        }

        // PRIORITY 2: New system dashboard roles
        $dashboardRoles = $this->getDashboardRoles($user);

        \Log::info('DashboardResolver: Dashboard roles resolved', [
            'user_id' => $user->id,
            'dashboard_roles' => $dashboardRoles,
            'role_count' => count($dashboardRoles),
        ]);

        if (count($dashboardRoles) > 1) {
            return $this->redirectToRoleSelection($user, $dashboardRoles);
        }

        if (count($dashboardRoles) === 1) {
            $role = reset($dashboardRoles);
            return $this->redirectByRole($user, $role);
        }

        // PRIORITY 3: Legacy fallback
        return $this->legacyFallback($user);
    }

    /**
     * Check if user is first-time (no roles/organisations/commissions)
     *
     * @param User $user
     * @return bool
     */
    private function isFirstTimeUser(User $user): bool
    {
        // Check if user has organisation roles (new system)
        $hasOrgRoles = \DB::table('user_organisation_roles')
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

        // No roles/orgs/commissions = first-time user
        return true;
    }

    /**
     * Get dashboard roles for user (new system + legacy mapping)
     *
     * @param User $user
     * @return array
     */
    private function getDashboardRoles(User $user): array
    {
        $roles = [];

        // 1. organisation roles (new system)
        if (\DB::table('user_organisation_roles')->where('user_id', $user->id)->exists()) {
            $roles[] = 'admin';
        }

        // 2. Commission memberships (new system)
        if (\DB::table('election_commission_members')->where('user_id', $user->id)->exists()) {
            $roles[] = 'commission';
        }

        // 3. Voter status (new system)
        if ($user->is_voter) {
            $roles[] = 'voter';
        }

        // 4. Legacy Spatie roles mapping
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            if (!in_array('admin', $roles)) {
                $roles[] = 'admin';
            }
        }

        // 5. Legacy committee member mapping
        if ($user->is_committee_member ?? false) {
            if (!in_array('commission', $roles)) {
                $roles[] = 'commission';
            }
        }

        return array_unique($roles);
    }

    /**
     * Redirect first-time user to welcome dashboard
     *
     * @param User $user
     * @return RedirectResponse
     */
    private function redirectToFirstTimeUser(User $user): RedirectResponse
    {
        \Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'decision' => 'first_time_user',
            'destination' => 'dashboard.welcome',
            'reason' => 'No organisations, commissions, or existing roles detected',
        ]);

        return redirect()->route('dashboard.welcome');
    }

    /**
     * Redirect user with multiple roles to role selection page
     *
     * @param User $user
     * @param array $roles
     * @return RedirectResponse
     */
    private function redirectToRoleSelection(User $user, array $roles): RedirectResponse
    {
        \Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'decision' => 'multiple_roles',
            'destination' => 'role.selection',
            'roles' => $roles,
            'reason' => 'User has ' . count($roles) . ' dashboard roles',
        ]);

        return redirect()->route('role.selection');
    }

    /**
     * Redirect user by their single dashboard role
     *
     * @param User $user
     * @param string $role
     * @return RedirectResponse
     */
    private function redirectByRole(User $user, string $role): RedirectResponse
    {
        // Special handling for organisation admins
        if ($role === 'admin') {
            try {
                // Get first organisation where user is admin
                $orgRole = \DB::table('user_organisation_roles')
                    ->where('user_id', $user->id)
                    ->where('role', 'admin')
                    ->first();

                if ($orgRole) {
                    $organisation = \App\Models\Organisation::find($orgRole->organisation_id);

                    if ($organisation) {
                        \Log::info('DashboardResolver: organisation admin redirect', [
                            'user_id' => $user->id,
                            'organisation_id' => $organisation->id,
                            'organisation_slug' => $organisation->slug,
                            'destination' => route('organisations.show', $organisation->slug),
                        ]);

                        return redirect()->route('organisations.show', $organisation->slug);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('DashboardResolver: Error checking organisation admin', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Fallback: Platform admin, commission, voter, or role selection
        $destination = match($role) {
            'admin' => 'admin.dashboard',
            'commission' => 'commission.dashboard',
            'voter' => 'vote.dashboard',
            default => 'role.selection',
        };

        \Log::info('DashboardResolver: Fallback redirect', [
            'user_id' => $user->id,
            'role' => $role,
            'destination' => $destination,
        ]);

        return redirect()->route($destination);
    }

    /**
     * Legacy system fallback (backward compatibility)
     *
     * @param User $user
     * @return RedirectResponse
     */
    private function legacyFallback(User $user): RedirectResponse
    {
        // Check for old Spatie roles
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            \Log::info('DashboardResolver: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_admin',
                'destination' => 'admin.dashboard',
                'reason' => 'User has legacy Spatie admin or election_officer role',
            ]);
            return redirect()->route('admin.dashboard');
        }

        // Legacy voter
        if ($user->is_voter) {
            \Log::info('DashboardResolver: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_voter',
                'destination' => 'dashboard',
                'reason' => 'User is marked as voter (legacy)',
            ]);
            return redirect()->route('dashboard');
        }

        // Legacy committee member
        if ($user->is_committee_member ?? false) {
            \Log::info('DashboardResolver: Redirect decision', [
                'user_id' => $user->id,
                'decision' => 'legacy_committee_member',
                'destination' => 'commission.dashboard',
                'reason' => 'User is marked as committee member (legacy)',
            ]);
            return redirect()->route('commission.dashboard');
        }

        // Ultimate fallback
        \Log::warning('DashboardResolver: Redirect decision - Default fallback', [
            'user_id' => $user->id,
            'decision' => 'default_fallback',
            'destination' => 'dashboard',
            'reason' => 'No roles detected - using default fallback',
        ]);

        return redirect()->route('dashboard');
    }
}
