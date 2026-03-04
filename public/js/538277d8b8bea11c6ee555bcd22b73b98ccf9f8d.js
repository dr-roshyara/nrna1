# DashboardResolver 6-Priority Business Logic Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement the 6-priority dashboard routing system in DashboardResolver following the architecture document, enabling proper user routing based on voting state, elections, and roles.

**Architecture:** The DashboardResolver uses a strict priority-based decision tree to route authenticated users:
1. **Active voting session** (in-progress voting) → voting portal
2. **Active election available** (can vote but hasn't started) → election dashboard
3. **New user welcome** (verified but not onboarded) → welcome page
4. **Multiple roles** (admin in multiple orgs/commissions) → role selection
5. **Single role** (one admin org or commission) → role-specific dashboard
6. **Platform user fallback** (no roles) → default dashboard

This requires fixing test data (missing voter_slugs columns), implementing the election check logic, and reordering priorities in the resolve() method.

**Tech Stack:** Laravel 11, PHPUnit, MySQL, DDD patterns with TDD methodology

---

## Task 1: Fix Test Data - Add Missing voter_slugs Columns

**Files:**
- Modify: `tests/Feature/Auth/DashboardResolverPriorityTest.php` (all test methods inserting voter_slugs)

**Step 1: Identify actual voter_slugs columns**

Check the voter_slugs migration to see what columns actually exist:

```bash
grep -A 50 "Schema::create('voter_slugs'" database/migrations/2026_03_01_000008_create_voter_slug_steps_table.php | head -60
```

Expected output will show actual column names like `is_active`, `completed_at`, `voting_step`, etc.

**Step 2: Update test data in all voter_slugs insertions**

Replace all test insertions like:
```php
// OLD (wrong):
DB::table('voter_slugs')->insert([
    'code1_used_at' => now(),
    'vote_completed_at' => null,
]);

// NEW (correct - use actual columns):
DB::table('voter_slugs')->insert([
    'is_active' => true,
    'completed_at' => null,
]);
```

Find and replace in test file:
- `code1_used_at` → `is_active` (or correct actual column)
- `vote_completed_at` → `completed_at` (or correct actual column)
- `vote_cast_at` → `voting_step` or correct column for tracking vote submission

**Step 3: Run tests to see new failure types**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php::DashboardResolverPriorityTest::priority_1_active_voting_session_redirects_to_voting_portal --no-coverage 2>&1 | tail -50
```

Expected: Tests fail because business logic not implemented, NOT because of SQL errors.

**Step 4: Commit**

```bash
git add tests/Feature/Auth/DashboardResolverPriorityTest.php
git commit -m "test: fix voter_slugs test data to use correct column names"
```

---

## Task 2: Implement getActiveElectionForUser() Helper Method

**Files:**
- Modify: `app/Services/DashboardResolver.php` (add new protected method)

**Step 1: Add the method to DashboardResolver**

Add this method **after the checkActiveVotingSession() method** (after line 263):

```php
/**
 * Check if user has an active election they can vote in
 *
 * Returns the first election where:
 * 1. User is a voter (is_voter OR has voter_slugs)
 * 2. Election is active (status='active')
 * 3. Current date between start_date and end_date
 * 4. User hasn't already voted in this election
 *
 * @param User $user
 * @return object|null Election object or null
 */
protected function getActiveElectionForUser(User $user): ?object
{
    try {
        // Quick check - if not a voter, skip this priority
        if (!$user->is_voter && !$this->hasAnyVoterSlugs($user)) {
            Log::debug('DashboardResolver: User is not a voter, skipping active election check', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        // Find active elections for user's organisations
        $activeElections = DB::table('elections')
            ->join('user_organisation_roles', 'elections.organisation_id', '=', 'user_organisation_roles.organisation_id')
            ->where('user_organisation_roles.user_id', $user->id)
            ->where('elections.status', 'active')
            ->where('elections.start_date', '<=', now())
            ->where('elections.end_date', '>=', now())
            ->select('elections.*')
            ->distinct()
            ->get();

        if ($activeElections->isEmpty()) {
            Log::debug('DashboardResolver: No active elections found for user', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        Log::info('DashboardResolver: Found active elections for user', [
            'user_id' => $user->id,
            'election_count' => $activeElections->count(),
        ]);

        // Filter out elections where user already voted
        foreach ($activeElections as $election) {
            $hasVoted = DB::table('voter_slugs')
                ->where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->whereNotNull('completed_at') // Check if voting is complete
                ->exists();

            if (!$hasVoted) {
                Log::info('DashboardResolver: Found available election for voting', [
                    'user_id' => $user->id,
                    'election_id' => $election->id,
                    'election_slug' => $election->slug,
                ]);
                return $election;
            }
        }

        Log::info('DashboardResolver: User has voted in all active elections', [
            'user_id' => $user->id,
        ]);
        return null;

    } catch (\Throwable $e) {
        Log::warning('DashboardResolver: Error checking active elections', [
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
protected function hasAnyVoterSlugs(User $user): bool
{
    try {
        if (!Schema::hasTable('voter_slugs')) {
            return false;
        }

        return DB::table('voter_slugs')
            ->where('user_id', $user->id)
            ->exists();
    } catch (\Throwable $e) {
        Log::warning('DashboardResolver: Error checking voter slugs', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

**Step 2: Run a test to verify the new method exists**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php::DashboardResolverPriorityTest::priority_2_active_election_redirects_to_election_dashboard --no-coverage 2>&1 | tail -30
```

Expected: Test should still fail (because priority not implemented yet), but NO PHP errors about method not existing.

**Step 3: Commit**

```bash
git add app/Services/DashboardResolver.php
git commit -m "feat: add getActiveElectionForUser() method to check available elections"
```

---

## Task 3: Add isNewUserWithoutOrganisation() Helper Method

**Files:**
- Modify: `app/Services/DashboardResolver.php` (add new protected method)

**Step 1: Add method after hasAnyVoterSlugs()**

Add this method after the hasAnyVoterSlugs() method:

```php
/**
 * Check if user is new and has no organisation (except platform)
 *
 * Returns true when:
 * 1. Email is verified
 * 2. NOT yet onboarded (onboarded_at = null)
 * 3. Has NO non-platform organisations
 * 4. Has NO voter slugs in any election
 *
 * @param User $user
 * @return bool
 */
protected function isNewUserWithoutOrganisation(User $user): bool
{
    try {
        // Must be verified first
        if ($user->email_verified_at === null) {
            return false;
        }

        // Already onboarded? Then not "new"
        if ($user->onboarded_at !== null) {
            return false;
        }

        // Check if user has non-platform organisations
        if (Schema::hasTable('user_organisation_roles')) {
            $hasNonPlatformOrgs = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', '!=', 1) // Exclude platform (id=1)
                ->exists();

            if ($hasNonPlatformOrgs) {
                Log::debug('DashboardResolver: User has non-platform organisations', [
                    'user_id' => $user->id,
                ]);
                return false;
            }
        }

        // Check if they're a voter in any election
        if (Schema::hasTable('voter_slugs')) {
            $isVoterInElection = DB::table('voter_slugs')
                ->where('user_id', $user->id)
                ->exists();

            if ($isVoterInElection) {
                Log::debug('DashboardResolver: User is a voter in an election', [
                    'user_id' => $user->id,
                ]);
                return false;
            }
        }

        // Check legacy is_voter flag
        if ($user->is_voter) {
            Log::debug('DashboardResolver: User is marked as voter (legacy)', [
                'user_id' => $user->id,
            ]);
            return false;
        }

        // All checks passed - this is a new user
        Log::info('DashboardResolver: New user detected - needs welcome page', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return true;

    } catch (\Throwable $e) {
        Log::warning('DashboardResolver: Error checking if new user', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

**Step 2: Run test**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php::DashboardResolverPriorityTest::priority_3_new_user_verified_but_no_org_goes_to_welcome --no-coverage 2>&1 | tail -30
```

Expected: Test still fails (priority not implemented), NO PHP errors.

**Step 3: Commit**

```bash
git add app/Services/DashboardResolver.php
git commit -m "feat: add isNewUserWithoutOrganisation() helper method"
```

---

## Task 4: Reorder Priorities in resolve() Method

**Files:**
- Modify: `app/Services/DashboardResolver.php` (lines 34-104, the resolve() method)

**Step 1: Update resolve() method with correct 6-priority order**

Replace the entire resolve() method with:

```php
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
    // User is in middle of voting → redirect to voting portal
    // =============================================
    $votingDashboard = $this->checkActiveVotingSession($user);
    if ($votingDashboard) {
        $this->cacheResolution($user, $votingDashboard);
        return $this->redirectToVoting($user, $votingDashboard);
    }

    // =============================================
    // PRIORITY 2: ACTIVE ELECTION AVAILABLE
    // User can vote but hasn't started → redirect to election dashboard
    // =============================================
    $activeElection = $this->getActiveElectionForUser($user);
    if ($activeElection) {
        $electionRoute = route('election.dashboard', $activeElection->slug);
        $this->cacheResolution($user, $electionRoute);

        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'decision' => 'active_election_available',
            'destination' => $electionRoute,
            'election_id' => $activeElection->id,
            'reason' => 'User has active election to vote in',
        ]);

        return redirect($electionRoute);
    }

    // =============================================
    // PRIORITY 3: NEW USER WELCOME
    // Verified but no org and not onboarded → welcome page
    // =============================================
    if ($this->isNewUserWithoutOrganisation($user)) {
        Log::info('DashboardResolver: Redirect decision', [
            'user_id' => $user->id,
            'decision' => 'new_user_welcome',
            'destination' => 'dashboard.welcome',
            'reason' => 'User verified but not onboarded and has no organisations',
        ]);
        return redirect()->route('dashboard.welcome');
    }

    // =============================================
    // PRIORITY 4 & 5: ROLES-BASED ROUTING
    // Get dashboard roles and decide based on count
    // =============================================
    $dashboardRoles = $this->getDashboardRoles($user);

    Log::info('DashboardResolver: Dashboard roles resolved', [
        'user_id' => $user->id,
        'dashboard_roles' => $dashboardRoles,
        'role_count' => count($dashboardRoles),
    ]);

    // PRIORITY 4: Multiple roles → role selection
    if (count($dashboardRoles) > 1) {
        $response = $this->redirectToRoleSelection($user, $dashboardRoles);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    // PRIORITY 5: Single role → role-specific dashboard
    if (count($dashboardRoles) === 1) {
        $role = reset($dashboardRoles);
        $response = $this->redirectByRole($user, $role);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    // =============================================
    // PRIORITY 6: PLATFORM USER FALLBACK
    // No roles → default dashboard
    // =============================================
    $response = $this->legacyFallback($user);
    $this->cacheResolution($user, $response->getTargetUrl());
    return $response;
}
```

**Step 2: Run one test to verify change is correct**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php::DashboardResolverPriorityTest::priority_1_active_voting_session_redirects_to_voting_portal --no-coverage 2>&1 | tail -50
```

Expected: This test might still fail due to test data issues, but the resolve() method should execute without errors.

**Step 3: Commit**

```bash
git add app/Services/DashboardResolver.php
git commit -m "feat: implement 6-priority routing order in DashboardResolver.resolve()"
```

---

## Task 5: Run All Tests and Verify They Pass

**Files:**
- Test: `tests/Feature/Auth/DashboardResolverPriorityTest.php`

**Step 1: Run all new tests**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php --no-coverage 2>&1 | tail -100
```

Expected: All 16 tests PASS ✅

Test summary should show:
- 16 tests
- All PASS
- No failures or errors
- Total time < 2 minutes

**Step 2: If any tests fail**

For each failing test:
1. Read the assertion error carefully
2. Identify the root cause (usually related to)
   - Wrong column names in voter_slugs
   - Wrong route names
   - Wrong database state
3. Make minimal fix
4. Re-run that test only
5. Commit with `git commit -m "fix: correct test data or assertion"`

**Step 3: Final verification**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/DashboardResolverPriorityTest.php --no-coverage 2>&1 | grep -E "(PASS|FAIL|ERROR)" | tail -20
```

Expected: All tests PASS

**Step 4: Commit**

```bash
git add .
git commit -m "test: all 16 DashboardResolver priority tests passing"
```

---

## Task 6: Verify No Regressions - Run Full Auth Test Suite

**Files:**
- Test: `tests/Feature/Auth/` (all auth tests)

**Step 1: Run existing auth tests to ensure no regressions**

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && php artisan test tests/Feature/Auth/ --no-coverage 2>&1 | tail -50
```

Expected:
- All previous tests still pass (VerifiedMiddlewareTest, LogoutTest, etc.)
- New DashboardResolverPriorityTest tests also pass
- Total: 26+ tests passing

**Step 2: If any tests fail**

The failures will be regressions. Fix by:
1. Checking if changes to DashboardResolver broke existing behavior
2. Review the error message carefully
3. Make minimal targeted fix
4. Re-run tests

**Step 3: Final commit**

```bash
git add .
git commit -m "test: verify no regressions in auth test suite - all tests passing"
```

---

## Success Criteria Checklist

- [ ] All 16 DashboardResolverPriorityTest tests pass
- [ ] All existing auth tests still pass (no regressions)
- [ ] DashboardResolver implements all 6 priorities in correct order
- [ ] New methods have proper logging
- [ ] New methods handle edge cases (table not exists, null values, etc.)
- [ ] Code follows existing DashboardResolver patterns
- [ ] All git commits are clean and descriptive
- [ ] No SQL errors due to column names

---

## Files Modified Summary

| File | Changes |
|------|---------|
| `tests/Feature/Auth/DashboardResolverPriorityTest.php` | 16 new test cases covering all 6 priorities |
| `app/Services/DashboardResolver.php` | Added 3 new methods + reordered resolve() logic |

---

## Reference Documentation

- **Architecture:** `architecture/dashboard/20260303_2357_business_login_login_response.md`
- **Existing Tests:** `tests/Feature/Auth/VerifiedMiddlewareTest.php` (reference for test patterns)
- **DashboardResolver Current:** `app/Services/DashboardResolver.php` (understand existing code)

---

## Estimated Timeline

- Task 1 (Fix Test Data): 5 minutes
- Task 2 (getActiveElectionForUser): 10 minutes
- Task 3 (isNewUserWithoutOrganisation): 8 minutes
- Task 4 (Reorder Priorities): 10 minutes
- Task 5 (Run Tests): 15 minutes (includes fixes if needed)
- Task 6 (Regression Tests): 5 minutes

**Total: ~50 minutes**

---
