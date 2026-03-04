<?php

namespace App\Services;

use App\Models\User;
use App\Enums\VotingStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardResolver
{
    /**
     * Resolve user's dashboard based on roles and system state
     *
     * Resolution Priority (in order):
     * 1. Check for active voting session → redirect to voting dashboard
     * 2. First-time users (no roles/orgs) → welcome dashboard
     * 3. Multiple roles → role selection page
     * 4. Single role → role-specific dashboard
     * 5. Legacy fallback → backward compatibility
     *
     * Cache Strategy:
     * - Caches resolved dashboard for performance
     * - Invalidated when user roles/organisations change (via Observer)
     * - Checks session freshness to prevent stale routing
     *
     * @param User $user
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function resolve(User $user): RedirectResponse
    {
        Log::info('DashboardResolver: Starting resolution', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Try to get cached resolution if session is fresh
        if ($this->shouldUseCachedResolution($user)) {
            $cached = $this->getCachedResolution($user);
            if ($cached) {
                Log::info('DashboardResolver: Using cached resolution', [
                    'user_id' => $user->id,
                    'target' => $cached,
                ]);
                return redirect($cached);
            }
        }

        // PRIORITY 1: Check for active voting session
        $votingDashboard = $this->checkActiveVotingSession($user);
        if ($votingDashboard) {
            $this->cacheResolution($user, $votingDashboard);
            return $this->redirectToVoting($user, $votingDashboard);
        }

        // PRIORITY 1.5: Check if user needs onboarding (email verified but not yet welcomed)
        // After user verifies their email for the first time, show welcome page
        if ($user->email_verified_at !== null && $user->onboarded_at === null) {
            Log::info('DashboardResolver: User needs onboarding (just verified email)', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return redirect()->route('dashboard.welcome');
        }

        // PRIORITY 2: First-time users → Welcome Dashboard
        if ($this->isFirstTimeUser($user)) {
            $response = $this->redirectToFirstTimeUser($user);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        // PRIORITY 3: New system dashboard roles
        $dashboardRoles = $this->getDashboardRoles($user);

        Log::info('DashboardResolver: Dashboard roles resolved', [
            'user_id' => $user->id,
            'dashboard_roles' => $dashboardRoles,
            'role_count' => count($dashboardRoles),
        ]);

        if (count($dashboardRoles) > 1) {
            $response = $this->redirectToRoleSelection($user, $dashboardRoles);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        if (count($dashboardRoles) === 1) {
            $role = reset($dashboardRoles);
            $response = $this->redirectByRole($user, $role);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        // PRIORITY 4: Legacy fallback
        $response = $this->legacyFallback($user);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    /**
     * Check if cached resolution should be used
     *
     * Uses cache only if:
     * 1. Cache hit exists
     * 2. Session is fresh (within freshness threshold)
     *
     * @param User $user
     * @return bool
     */
    protected function shouldUseCachedResolution(User $user): bool
    {
        if (!config('login-routing.cache.dashboard_resolution_ttl', 300)) {
            return false; // Caching disabled
        }

        // Check if cache exists
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        if (!Cache::has($cacheKey)) {
            return false;
        }

        // Validate session freshness
        if (!config('login-routing.session.validate_freshness', true)) {
            return true;
        }

        return $this->isSessionFresh($user);
    }

    /**
     * Check if user's session is fresh enough to trust cached routing
     *
     * Session freshness is determined by comparing:
     * - User's last activity timestamp
     * - Configured freshness threshold
     *
     * This prevents stale routing after role/org changes during an active session
     *
     * @param User $user
     * @return bool
     */
    protected function isSessionFresh(User $user): bool
    {
        if (!Schema::hasColumn('users', 'last_activity_at')) {
            return true; // Column doesn't exist yet, assume fresh
        }

        $lastActivity = $user->last_activity_at;
        if (!$lastActivity) {
            return true; // No recorded activity, assume fresh
        }

        $threshold = config('login-routing.session.freshness_threshold', 60);
        $isWithinThreshold = $lastActivity->addSeconds($threshold)->isFuture();

        if (!$isWithinThreshold && config('login-routing.debug.log_cache', false)) {
            Log::debug('Session not fresh, cache invalidated', [
                'user_id' => $user->id,
                'last_activity' => $lastActivity->toIso8601String(),
                'threshold_seconds' => $threshold,
            ]);
        }

        return $isWithinThreshold;
    }

    /**
     * Get cached resolution for user
     *
     * @param User $user
     * @return string|null
     */
    protected function getCachedResolution(User $user): ?string
    {
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        return Cache::get($cacheKey);
    }

    /**
     * Cache the resolved dashboard URL
     *
     * @param User $user
     * @param string $targetUrl
     * @return void
     */
    protected function cacheResolution(User $user, string $targetUrl): void
    {
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $ttl = config('login-routing.cache.dashboard_resolution_ttl', 300);

        Cache::put($cacheKey, $targetUrl, $ttl);

        if (config('login-routing.debug.log_cache', false)) {
            Log::debug('Dashboard resolution cached', [
                'user_id' => $user->id,
                'target_url' => $targetUrl,
                'cache_ttl' => $ttl,
            ]);
        }
    }

    /**
     * Check if user has an active voting session
     *
     * Returns the voting dashboard route if user is currently voting
     * An active session means:
     * - voter_slug exists and is not expired
     * - is_active = true
     * - current_step is between 1 and 4 (not yet completed)
     *
     * @param User $user
     * @return string|null
     */
    protected function checkActiveVotingSession(User $user): ?string
    {
        try {
            // Check if voter_slugs table exists
            if (!Schema::hasTable('voter_slugs')) {
                return null;
            }

            // Find active voting session for this user
            // Must be: active, not expired, and not yet completed (step < 5)
            $activeVote = DB::table('voter_slugs')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->where('current_step', '<', 5) // Not completed (5 = completed)
                ->first();

            if (!$activeVote) {
                return null;
            }

            // User has active voting, route to voting dashboard
            $currentStep = $this->getCurrentVotingStep($activeVote);

            Log::info('DashboardResolver: Active voting session detected', [
                'user_id' => $user->id,
                'voter_slug_id' => $activeVote->id,
                'current_step' => $currentStep->label(),
                'step_number' => $currentStep->value,
            ]);

            // Route based on current voting step
            return match($currentStep) {
                VotingStep::WAITING => route('vote.start'),
                VotingStep::CODE_VERIFIED => route('vote.agreement'),
                VotingStep::AGREEMENT_ACCEPTED => route('vote.select'),
                VotingStep::VOTE_CAST => route('vote.verify'),
                VotingStep::VERIFIED => route('vote.complete'),
            };

        } catch (\Throwable $e) {
            Log::warning('DashboardResolver: Error checking active voting session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get current voting step for a voter slug
     *
     * Maps voter_slug.current_step to VotingStep enum for proper routing
     *
     * @param object $voterSlug
     * @return VotingStep
     */
    protected function getCurrentVotingStep($voterSlug): VotingStep
    {
        // Map current_step column (1-5) to VotingStep enum
        // current_step values: 1=waiting, 2=code verified, 3=agreement, 4=vote cast, 5=verified
        return match((int)($voterSlug->current_step ?? 1)) {
            1 => VotingStep::WAITING,
            2 => VotingStep::CODE_VERIFIED,
            3 => VotingStep::AGREEMENT_ACCEPTED,
            4 => VotingStep::VOTE_CAST,
            5 => VotingStep::VERIFIED,
            default => VotingStep::WAITING,
        };
    }

    /**
     * Redirect user to voting dashboard
     *
     * @param User $user
     * @param string $votingDashboard
     * @return RedirectResponse
     */
    protected function redirectToVoting(User $user, string $votingDashboard): RedirectResponse
    {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'decision' => 'active_voting_session',
            'destination' => $votingDashboard,
            'reason' => 'User has active voting in progress',
        ]);

        return redirect($votingDashboard);
    }

    /**
     * Check if user is first-time (no roles/organisations/commissions)
     *
     * Defensive checks for table existence to support migrations
     * and partial deployments.
     *
     * @param User $user
     * @return bool
     */
    private function isFirstTimeUser(User $user): bool
    {
        try {
            // Check if user has organisation roles (new system)
            if (Schema::hasTable('user_organisation_roles')) {
                $hasOrgRoles = DB::table('user_organisation_roles')
                    ->where('user_id', $user->id)
                    ->exists();

                if ($hasOrgRoles) {
                    Log::debug('DashboardResolver: User has organisation roles', [
                        'user_id' => $user->id,
                    ]);
                    return false;
                }
            }

            // Check if user is commission member (new system)
            if (Schema::hasTable('election_commission_members')) {
                $hasCommissionMembership = DB::table('election_commission_members')
                    ->where('user_id', $user->id)
                    ->exists();

                if ($hasCommissionMembership) {
                    Log::debug('DashboardResolver: User has commission membership', [
                        'user_id' => $user->id,
                    ]);
                    return false;
                }
            }

            // Check legacy roles (with defensive check for column existence)
            if ($user->is_voter ?? false) {
                Log::debug('DashboardResolver: User is marked as voter', [
                    'user_id' => $user->id,
                ]);
                return false;
            }

            try {
                if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
                    Log::debug('DashboardResolver: User has legacy Spatie roles', [
                        'user_id' => $user->id,
                    ]);
                    return false;
                }
            } catch (\Throwable $e) {
                Log::debug('DashboardResolver: Could not check Spatie roles (may not be set up)', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // No roles/orgs/commissions = first-time user
            Log::info('DashboardResolver: User identified as first-time', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return true;

        } catch (\Exception $e) {
            // If tables don't exist yet (during migration), treat as first-time user
            Log::warning('DashboardResolver: Error checking first-time user status', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'defaulting_to' => 'first_time_user',
                'exception' => get_class($e),
            ]);

            return true;
        }
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

        try {
            // 1. organisation roles (new system)
            // ✅ FIX: Only add 'admin' role for actual admins in NON-PLATFORM organisations
            // Don't add 'admin' role for 'member' role in platform organisation
            if (\Schema::hasTable('user_organisation_roles')) {
                // Check for actual admin roles in non-platform organisations
                $adminRoleExists = \DB::table('user_organisation_roles')
                    ->where('user_id', $user->id)
                    ->where('role', 'admin')
                    ->whereNot(function ($query) {
                        // Exclude platform organisation (id=1)
                        $query->where('organisation_id', 1);
                    })
                    ->exists();

                if ($adminRoleExists) {
                    $roles[] = 'admin';
                    \Log::debug('DashboardResolver: User is admin in non-platform organisation', [
                        'user_id' => $user->id,
                        'org_roles' => \DB::table('user_organisation_roles')
                            ->where('user_id', $user->id)
                            ->where('role', 'admin')
                            ->get(['organisation_id', 'role'])
                            ->toArray(),
                    ]);
                } else {
                    \Log::debug('DashboardResolver: User has NO admin roles in non-platform organisations', [
                        'user_id' => $user->id,
                        'org_roles' => \DB::table('user_organisation_roles')->where('user_id', $user->id)->get(['organisation_id', 'role'])->toArray(),
                    ]);
                }
            }

            // 2. Commission memberships (new system)
            if (\Schema::hasTable('election_commission_members')) {
                $commissionExists = \DB::table('election_commission_members')->where('user_id', $user->id)->exists();
                if ($commissionExists) {
                    $roles[] = 'commission';
                    \Log::debug('DashboardResolver: User has commission membership', [
                        'user_id' => $user->id,
                        'commissions' => \DB::table('election_commission_members')->where('user_id', $user->id)->get(['election_id'])->toArray(),
                    ]);
                } else {
                    \Log::debug('DashboardResolver: User has NO commission memberships', ['user_id' => $user->id]);
                }
            }

            // 3. Voter status (new system)
            if ($user->is_voter) {
                $roles[] = 'voter';
                \Log::debug('DashboardResolver: User is marked as voter', ['user_id' => $user->id]);
            }

            // 4. Legacy Spatie roles mapping
            $hasSpatieAdmin = $user->hasRole('admin');
            $hasSpatieElectionOfficer = $user->hasRole('election_officer');
            if ($hasSpatieAdmin || $hasSpatieElectionOfficer) {
                if (!in_array('admin', $roles)) {
                    $roles[] = 'admin';
                }
                \Log::debug('DashboardResolver: User has legacy Spatie admin role', [
                    'user_id' => $user->id,
                    'spatie_roles' => $user->roles()->pluck('name')->toArray(),
                ]);
            }

            // 5. Legacy committee member mapping
            if ($user->is_committee_member ?? false) {
                if (!in_array('commission', $roles)) {
                    $roles[] = 'commission';
                }
                \Log::debug('DashboardResolver: User is legacy committee member', ['user_id' => $user->id]);
            }

            $uniqueRoles = array_unique(array_filter($roles));
            \Log::info('DashboardResolver: Final roles determined', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_voter' => $user->is_voter,
                'is_committee_member' => $user->is_committee_member ?? false,
                'final_roles' => $uniqueRoles,
                'role_count' => count($uniqueRoles),
            ]);

            return $uniqueRoles;
        } catch (\Exception $e) {
            // If there's an error checking roles (tables don't exist, etc.)
            // Default to no roles and log the issue
            \Log::warning('DashboardResolver: Error determining dashboard roles', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'defaulting_to' => 'no_roles',
            ]);

            // Return empty roles array - user will be treated as first-time
            return [];
        }
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
