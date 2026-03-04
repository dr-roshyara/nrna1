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
     * 1. ACTIVE VOTING SESSION → resume voting if in progress
     * 2. ACTIVE ELECTION AVAILABLE → election dashboard if eligible
     * 3. MISSING ORGANISATION → handle default vs custom org routing
     * 4. EMAIL VERIFICATION → ensure email verified
     * 5. NEW USER WELCOME → first-time users without roles/orgs
     * 6. MULTIPLE ROLES → role selection page
     * 7. SINGLE ROLE → role-specific dashboard
     * 8. PLATFORM FALLBACK → no roles (backward compatibility)
     *
     * Eligibility Checks for Active Elections:
     * - Organisation must exist in database
     * - User must have active membership (user_organisation_roles)
     * - Election must be active (status='active')
     * - Current date within election date range
     * - User must not have already voted
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

        // =============================================
        // PRIORITY 1: ACTIVE VOTING SESSION
        // User is in middle of voting → resume voting session first
        // =============================================
        if ($activeVoterSlug = $this->getActiveVoterSlug($user)) {
            Log::info('DashboardResolver: Active voting session found - resuming', [
                'user_id' => $user->id,
                'voter_slug_id' => $activeVoterSlug->id,
            ]);
            $this->cacheResolution($user, route('voting.portal', ['voter_slug' => $activeVoterSlug->slug]));
            return redirect()->route('voting.portal', ['voter_slug' => $activeVoterSlug->slug]);
        }

        // =============================================
        // PRIORITY 2: ACTIVE ELECTION AVAILABLE
        // User has eligible organisation AND active election exists
        // =============================================
        if ($activeElection = $this->getActiveElectionForUser($user)) {
            Log::info('DashboardResolver: Active election found - user can vote', [
                'user_id' => $user->id,
                'election_id' => $activeElection->id,
            ]);
            $this->cacheResolution($user, route('election.dashboard', $activeElection->slug));
            return redirect()->route('election.dashboard', $activeElection->slug);
        }

        // =============================================
        // PRIORITY 3: HANDLE MISSING ORGANISATION/ELECTIONS
        // Called when no active election found
        // Routes based on organisation_id (default=1 or custom)
        // =============================================
        if (!$this->hasActiveOrganisations($user)) {
            $response = $this->handleMissingOrganisation($user);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        // =============================================
        // PRIORITY 4: EMAIL VERIFICATION
        // Safety check - ensure email verified
        // =============================================
        if ($user->email_verified_at === null) {
            Log::info('DashboardResolver: User email not verified', [
                'user_id' => $user->id,
            ]);
            return redirect()->route('verification.notice');
        }

        // =============================================
        // PRIORITY 5: NEW USER WELCOME
        // Verified but no roles/commissions → welcome page
        // =============================================
        if ($this->isFirstTimeUser($user)) {
            Log::info('DashboardResolver: First-time user - directing to welcome', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            $response = $this->redirectToFirstTimeUser($user);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        // =============================================
        // PRIORITY 6: MULTIPLE ROLES
        // User has multiple dashboard roles - must choose
        // =============================================
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

        // =============================================
        // PRIORITY 7: SINGLE ROLE
        // User has exactly one role - route to role-specific dashboard
        // =============================================
        if (count($dashboardRoles) === 1) {
            $role = reset($dashboardRoles);
            $response = $this->redirectByRole($user, $role);
            $this->cacheResolution($user, $response->getTargetUrl());
            return $response;
        }

        // =============================================
        // PRIORITY 8: PLATFORM USER FALLBACK
        // No roles - direct to platform dashboard
        // =============================================
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

    /**
     * Check if user has an active election they can vote in
     *
     * Conditions (ALL must be true):
     * 1. Organisation exists in database
     * 2. User has active membership (user_organisation_roles)
     * 3. Election is active (status='active')
     * 4. Current date between start_date and end_date
     * 5. User hasn't already voted in this election
     *
     * @param User $user
     * @return ?object Election object or null
     */
    private function getActiveElectionForUser(User $user): ?object
    {
        try {
            // =============================================
            // STEP 1: Verify organisation exists AND user has active membership
            // Exclude platform organisation (id=1)
            // =============================================
            $activeOrgs = DB::table('user_organisation_roles')
                ->join('organisations', 'user_organisation_roles.organisation_id', '=', 'organisations.id')
                ->where('user_organisation_roles.user_id', $user->id)
                ->where('organisations.id', '!=', 1) // Exclude platform org
                ->where('user_organisation_roles.role', 'member') // Active membership
                ->select('organisations.*')
                ->get();

            if ($activeOrgs->isEmpty()) {
                Log::debug('DashboardResolver: No active organisations found for user', [
                    'user_id' => $user->id,
                ]);
                return null;
            }

            // =============================================
            // STEP 2: Find active elections in these organisations
            // =============================================
            $orgIds = $activeOrgs->pluck('id')->toArray();

            $activeElections = DB::table('elections')
                ->whereIn('organisation_id', $orgIds)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->select('elections.*')
                ->get();

            if ($activeElections->isEmpty()) {
                Log::debug('DashboardResolver: No active elections in user organisations', [
                    'user_id' => $user->id,
                    'organisation_ids' => $orgIds,
                ]);
                return null;
            }

            // =============================================
            // STEP 3: Filter out elections where user already voted
            // =============================================
            foreach ($activeElections as $election) {
                $hasVoted = DB::table('voter_slugs')
                    ->where('user_id', $user->id)
                    ->where('election_id', $election->id)
                    ->whereNotNull('vote_completed_at')
                    ->exists();

                if (!$hasVoted) {
                    Log::info('DashboardResolver: Found available election', [
                        'user_id' => $user->id,
                        'organisation_id' => $election->organisation_id,
                        'election_id' => $election->id,
                        'election_slug' => $election->slug,
                    ]);
                    return $election; // Return first election user hasn't voted in
                }
            }

            Log::info('DashboardResolver: User voted in all active elections', [
                'user_id' => $user->id,
            ]);
            return null; // Voted in all active elections

        } catch (\Throwable $e) {
            Log::warning('DashboardResolver: Error in getActiveElectionForUser', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Check if user has an active voting session in progress
     *
     * An active session means:
     * - Voter slug exists for user
     * - Not expired (expires_at > now)
     * - Vote not completed yet (vote_completed_at IS NULL)
     * - User is in middle of steps 1-4 (code1_used_at OR has_agreed OR vote_submitted)
     *
     * @param User $user
     * @return ?object VoterSlug object or null
     */
    private function getActiveVoterSlug(User $user): ?object
    {
        try {
            return DB::table('voter_slugs')
                ->where('user_id', $user->id)
                ->where('expires_at', '>', now())
                ->whereNull('vote_completed_at')  // Not finished voting
                ->where(function($query) {
                    $query->whereNotNull('code1_used_at')  // Started voting
                          ->orWhereNotNull('has_agreed_to_vote_at')
                          ->orWhereNotNull('vote_submitted_at');
                })
                ->orderBy('updated_at', 'desc')
                ->first();
        } catch (\Exception $e) {
            Log::error('DashboardResolver: Error checking active voter slug', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Helper to check if user has any voter slugs at all
     *
     * @param User $user
     * @return bool
     */
    private function hasAnyVoterSlugs(User $user): bool
    {
        try {
            return DB::table('voter_slugs')
                ->where('user_id', $user->id)
                ->exists();
        } catch (\Exception $e) {
            Log::error('DashboardResolver: Error checking voter slugs existence', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check if user has any active organisations (excluding platform)
     *
     * @param User $user
     * @return bool
     */
    private function hasActiveOrganisations(User $user): bool
    {
        try {
            return DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', '!=', 1) // Exclude platform
                ->where('role', 'member')
                ->exists();
        } catch (\Exception $e) {
            Log::error('DashboardResolver: Error checking active organisations', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Handle case when user has no active organisations or elections
     *
     * Routes based on organisation_id:
     * - org_id = 1 (platform/publicdigit) → welcome dashboard
     * - org_id > 1 (custom organisation) → organisation page
     *
     * @param User $user
     * @return RedirectResponse
     */
    private function handleMissingOrganisation(User $user): RedirectResponse
    {
        try {
            // Check if user has any organisation role at all
            $userOrgRole = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->first();

            if (!$userOrgRole) {
                // This should not happen - every user should have platform membership
                Log::error('DashboardResolver: User has no organisation roles at all', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                return redirect()->route('dashboard.welcome');
            }

            // Get organisation details
            $organisation = DB::table('organisations')
                ->where('id', $userOrgRole->organisation_id)
                ->first();

            if (!$organisation) {
                Log::error('DashboardResolver: Organisation not found', [
                    'user_id' => $user->id,
                    'organisation_id' => $userOrgRole->organisation_id,
                ]);
                return redirect()->route('dashboard.welcome');
            }

            // Route based on organisation type
            if ($organisation->id == 1) {
                Log::info('DashboardResolver: User belongs to platform org only', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'destination' => 'dashboard.welcome',
                ]);
                return redirect()->route('dashboard.welcome');
            }

            // Custom organisation - send to their organisation page
            Log::info('DashboardResolver: User has custom org but no active elections', [
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisation->slug,
                'destination' => 'organisations.show',
            ]);

            return redirect()->route('organisations.show', $organisation->slug);

        } catch (\Throwable $e) {
            Log::error('DashboardResolver: Error in handleMissingOrganisation', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('dashboard.welcome');
        }
    }
}
