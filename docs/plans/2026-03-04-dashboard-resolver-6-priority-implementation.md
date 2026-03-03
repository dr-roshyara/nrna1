# DashboardResolver 6-Priority Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task.

**Goal:** Implement complete DashboardResolver 6-priority routing system with comprehensive test coverage.

**Architecture:** Priority-based routing system that determines user dashboard based on voting state, roles, and organisation membership. Uses TDD with RED-GREEN-REFACTOR cycle.

**Tech Stack:** Laravel 11, PHPUnit, MySQL, Vue 3, Inertia.js

---

## Task 1: Create Comprehensive Test Suite (RED Phase)

**Status:** ✅ COMPLETED

**Files:**
- Create: `tests/Feature/Auth/DashboardResolverPriorityTest.php`

**Implementation Summary:**

Created 16 comprehensive tests covering all 6 priority levels:

- PRIORITY 1 (Active Voting): 2 tests
- PRIORITY 2 (Active Election): 3 tests
- PRIORITY 3 (New User Welcome): 3 tests
- PRIORITY 4 (Multiple Roles): 2 tests
- PRIORITY 5 (Single Role): 2 tests
- PRIORITY 6 (Fallback): 1 test
- Precedence Tests: 3 tests

All tests are currently FAILING (RED phase), which confirms they test business logic that doesn't exist yet. This is correct TDD behavior.

**Key Test Data Fixes Applied:**
- Updated test data to use correct `voter_slugs` table columns
- Changed from non-existent columns (`code1_used_at`, `vote_completed_at`) to actual columns (`is_active`, `current_step`, `expires_at`)
- Updated `DashboardResolver.php` methods to use correct column names

**Commands to Verify:**
```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest
# Expected: 16 FAILED tests (RED phase)
```

---

## Task 2: Implement getActiveElectionForUser() Method

**Status:** ⏳ PENDING

**Files:**
- Modify: `app/Services/DashboardResolver.php`

**Step 1: Write the method signature and documentation**

Add to DashboardResolver class:

```php
/**
 * Check if user has an active election they can vote in
 *
 * Priority 2: Active election available
 * Returns first active election where user can vote
 *
 * @param User $user
 * @return Election|null
 */
private function getActiveElectionForUser(User $user): ?Election
{
    // Implementation to follow
}
```

**Step 2: Implement the logic**

```php
private function getActiveElectionForUser(User $user): ?Election
{
    try {
        // Get user's organisation
        $orgId = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('organisation_id');

        if (!$orgId) {
            return null;  // No organisation
        }

        // Find active election in user's organisation
        $election = Election::where('organisation_id', $orgId)
            ->where('status', 'active')
            ->whereBetween('start_date', [now()->subHour(), now()])
            ->whereBetween('end_date', [now(), now()->addYear()])
            ->whereNotExists(function ($query) use ($user) {
                // Exclude elections where user already voted
                $query->table('voter_slugs')
                    ->where('user_id', $user->id)
                    ->where('is_active', false)
                    ->where('current_step', 5);  // Completed
            })
            ->orderBy('start_date', 'asc')
            ->first();

        if ($election) {
            Log::debug('DashboardResolver: Found active election', [
                'user_id' => $user->id,
                'election_id' => $election->id,
            ]);
        }

        return $election;
    } catch (\Exception $e) {
        Log::warning('DashboardResolver: Error checking active elections', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return null;
    }
}
```

**Step 3: Verify tests still failing**

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest::priority_2_active_election_redirects_to_election_dashboard
# Expected: Still FAILING (method exists but resolve() not calling it yet)
```

---

## Task 3: Implement isNewUserWithoutOrganisation() Method

**Status:** ⏳ PENDING

**Files:**
- Modify: `app/Services/DashboardResolver.php`

**Implementation:**

```php
/**
 * Check if user is new and hasn't been onboarded
 *
 * Priority 3: New user welcome
 * Checks if user is verified but has no organisation assignment
 *
 * @param User $user
 * @return bool
 */
private function isNewUserWithoutOrganisation(User $user): bool
{
    try {
        // Must be email verified
        if (!$user->email_verified_at) {
            return true;  // Unverified is always "new"
        }

        // Must not be onboarded yet
        if ($user->onboarded_at === null) {
            return true;
        }

        // Check if has organisation roles (excluding platform org id=1)
        $hasOrgRole = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', '!=', 1)  // Exclude platform org
            ->exists();

        if (!$hasOrgRole) {
            Log::debug('DashboardResolver: User identified as new', [
                'user_id' => $user->id,
                'email_verified_at' => $user->email_verified_at,
                'onboarded_at' => $user->onboarded_at,
            ]);
            return true;
        }

        return false;
    } catch (\Exception $e) {
        Log::warning('DashboardResolver: Error checking new user status', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

---

## Task 4: Implement getDashboardRoles() Method

**Status:** ⏳ PENDING

**Files:**
- Modify: `app/Services/DashboardResolver.php` (already exists, update if needed)

**Implementation (if not already present):**

```php
/**
 * Get all dashboard roles for user
 *
 * Checks:
 * 1. Organisation roles (admin, member)
 * 2. Commission membership
 * 3. Voter status
 *
 * @param User $user
 * @return array
 */
private function getDashboardRoles(User $user): array
{
    $roles = [];

    try {
        // 1. Check organisation roles
        if (Schema::hasTable('user_organisation_roles')) {
            $orgRoles = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->distinct()
                ->pluck('role')
                ->toArray();

            if (in_array('admin', $orgRoles)) {
                $roles[] = 'admin';
            }
        }

        // 2. Check commission membership
        if (Schema::hasTable('election_commission_members')) {
            $isCommission = DB::table('election_commission_members')
                ->where('user_id', $user->id)
                ->exists();

            if ($isCommission) {
                $roles[] = 'commission';
            }
        }

        // 3. Check voter status
        if ($user->is_voter) {
            $roles[] = 'voter';
        }

        // 4. Legacy Spatie roles
        if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
            if (!in_array('admin', $roles)) {
                $roles[] = 'admin';
            }
        }

        // 5. Legacy committee member
        if ($user->is_committee_member ?? false) {
            if (!in_array('commission', $roles)) {
                $roles[] = 'commission';
            }
        }

        $uniqueRoles = array_unique(array_filter($roles));

        Log::info('DashboardResolver: Dashboard roles determined', [
            'user_id' => $user->id,
            'roles' => $uniqueRoles,
            'count' => count($uniqueRoles),
        ]);

        return $uniqueRoles;
    } catch (\Exception $e) {
        Log::warning('DashboardResolver: Error determining roles', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return [];
    }
}
```

---

## Task 5: Update resolve() Method with Complete Priority Order

**Status:** ⏳ PENDING

**Files:**
- Modify: `app/Services/DashboardResolver.php`

**Implementation:**

Replace the existing `resolve()` method with the complete 6-priority system:

```php
/**
 * Resolve user's dashboard based on 6-priority system
 *
 * Priority Order:
 * 1. Active voting session → /vote/{slug}
 * 2. Active election available → /election/dashboard
 * 3. New user welcome → /dashboard/welcome
 * 4. Multiple roles → /dashboard/roles
 * 5. Single role → role-specific dashboard
 * 6. Platform user fallback → /dashboard
 *
 * @param User $user
 * @return RedirectResponse
 */
public function resolve(User $user): RedirectResponse
{
    Log::info('DashboardResolver: Processing user', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

    // PRIORITY 1: Active voting session
    $activeVoting = $this->checkActiveVotingSession($user);
    if ($activeVoting) {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'priority' => 1,
            'decision' => 'active_voting',
            'destination' => 'vote.start',
        ]);
        return redirect()->route('vote.start', ['slug' => $activeVoting->slug]);
    }

    // PRIORITY 2: Active election available
    $activeElection = $this->getActiveElectionForUser($user);
    if ($activeElection) {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'priority' => 2,
            'decision' => 'active_election',
            'destination' => 'election.dashboard',
        ]);
        return redirect()->route('election.dashboard', $activeElection->slug);
    }

    // PRIORITY 3: New user welcome
    if ($this->isNewUserWithoutOrganisation($user)) {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'priority' => 3,
            'decision' => 'new_user',
            'destination' => 'dashboard.welcome',
        ]);
        return redirect()->route('dashboard.welcome');
    }

    // PRIORITY 4 & 5: Check dashboard roles
    $dashboardRoles = $this->getDashboardRoles($user);

    if (count($dashboardRoles) > 1) {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'priority' => 4,
            'decision' => 'multiple_roles',
            'roles' => $dashboardRoles,
            'destination' => 'role.selection',
        ]);
        return redirect()->route('role.selection');
    }

    if (count($dashboardRoles) === 1) {
        $role = reset($dashboardRoles);
        return $this->redirectByRole($user, $role);
    }

    // PRIORITY 6: Fallback
    Log::info('DashboardResolver: Redirect decision', [
        'user_id' => $user->id,
        'priority' => 6,
        'decision' => 'fallback',
        'destination' => 'dashboard',
    ]);
    return redirect()->route('dashboard');
}
```

---

## Task 6: Implement Helper Methods

**Status:** ⏳ PENDING

**Files:**
- Modify: `app/Services/DashboardResolver.php`

**Implementation:**

Add these helper methods:

```php
/**
 * Check if user has active voting session
 *
 * @param User $user
 * @return VoterSlug|null
 */
private function checkActiveVotingSession(User $user): ?VoterSlug
{
    try {
        $voterSlug = DB::table('voter_slugs')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->where('current_step', '<', 5)  // Not completed
            ->first();

        return $voterSlug ? VoterSlug::fromStdClass($voterSlug) : null;
    } catch (\Exception $e) {
        Log::warning('DashboardResolver: Error checking voting session', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return null;
    }
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
    // Special handling for organisation admin
    if ($role === 'admin') {
        try {
            $orgRole = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('role', 'admin')
                ->first();

            if ($orgRole) {
                $organisation = Organisation::find($orgRole->organisation_id);
                if ($organisation) {
                    Log::info('DashboardResolver: Redirect decision', [
                        'user_id' => $user->id,
                        'priority' => 5,
                        'decision' => 'single_admin_role',
                        'organisation_id' => $organisation->id,
                        'destination' => 'organisations.show',
                    ]);
                    return redirect()->route('organisations.show', $organisation->slug);
                }
            }
        } catch (\Exception $e) {
            Log::error('DashboardResolver: Error handling admin redirect', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Fallback redirect by role
    $destination = match($role) {
        'admin' => 'admin.dashboard',
        'commission' => 'commission.dashboard',
        'voter' => 'vote.dashboard',
        default => 'role.selection',
    };

    Log::info('DashboardResolver: Redirect decision', [
        'user_id' => $user->id,
        'priority' => 5,
        'decision' => 'single_role',
        'role' => $role,
        'destination' => $destination,
    ]);

    return redirect()->route($destination);
}
```

---

## Task 7: Run All Tests and Verify GREEN Phase

**Status:** ⏳ PENDING

**Files:**
- Test: `tests/Feature/Auth/DashboardResolverPriorityTest.php`

**Step 1: Run all tests**

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest
```

**Expected Output:**
```
PASS  Tests\Feature\Auth\DashboardResolverPriorityTest
  ✓ priority 1 active voting session redirects to voting portal
  ✓ priority 1 takes precedence over multiple active elections
  ✓ priority 2 active election redirects to election dashboard
  ✓ priority 2 skips elections where user already voted
  ✓ priority 2 ignores elections outside voting window
  ✓ priority 3 new user verified but no org goes to welcome
  ✓ priority 3 new user with platform org only goes to welcome
  ✓ priority 3 skips if user already onboarded
  ✓ priority 4 user with multiple roles goes to role selection
  ✓ priority 4 user with admin and commission roles goes to role selection
  ✓ priority 5 single admin role redirects to organisation page
  ✓ priority 5 single commission role redirects to commission dashboard
  ✓ priority 6 user with no roles goes to default dashboard
  ✓ active voting takes precedence over new user welcome
  ✓ active election takes precedence over new user welcome
  ✓ roles take precedence over welcome when onboarded

Tests:  16 passed (48 assertions)
Time:   2.34s
```

**Step 2: Verify no regressions**

```bash
php artisan test tests/
```

All tests should pass.

**Step 3: Check code coverage**

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest --coverage
```

Target: ≥ 80% coverage for DashboardResolver class.

---

## Task 8: Commit Implementation

**Status:** ⏳ PENDING

**Files Modified:**
- `app/Services/DashboardResolver.php` (complete rewrite)
- `tests/Feature/Auth/DashboardResolverPriorityTest.php` (created)

**Commit Message:**

```
feat: implement DashboardResolver 6-priority routing system

Implement complete dashboard routing logic with these priorities:
1. Active voting session → /vote/{slug}
2. Active election available → /election/dashboard
3. New user welcome → /dashboard/welcome
4. Multiple roles → /dashboard/roles
5. Single role → role-specific dashboard
6. Platform user fallback → /dashboard

Changes:
- Add getActiveElectionForUser() method
- Add isNewUserWithoutOrganisation() method
- Rewrite resolve() with 6-priority decision tree
- Add helper methods for routing logic
- Create comprehensive test suite (16 tests)
- All tests passing with 100% green phase

Co-Authored-By: Claude Haiku 4.5 <noreply@anthropic.com>
```

---

## Verification Checklist

- [ ] All 16 tests passing (GREEN phase)
- [ ] No test failures or warnings
- [ ] Code follows Laravel conventions
- [ ] All logging properly implemented
- [ ] No untested edge cases
- [ ] Error handling in place
- [ ] Database queries optimized
- [ ] Commit created with proper message
- [ ] No regressions in other tests

---

## Success Criteria

✅ All 16 tests pass
✅ DashboardResolver implements complete 6-priority system
✅ Helper methods are well-documented
✅ Logging allows debugging of routing decisions
✅ Code is clean and maintainable
✅ No breaking changes to existing functionality

---

**Document Version:** 1.0
**Created:** March 4, 2026
**Status:** Implementation Plan Ready
