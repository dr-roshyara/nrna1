# 4. Login Priority System (CRITICAL)

## What is the Login Priority System?

After successful authentication, `DashboardResolver` determines which dashboard/page the user sees. It uses an **8-level priority system** to make this decision.

```
User logs in → Auth successful → DashboardResolver::resolve()
    ↓
  Check Priority 1
    ↓ (if not met)
  Check Priority 2
    ↓ (if not met)
  Check Priority 3
    ... (repeat for 4-8)
    ↓ (eventually one matches)
  Redirect to dashboard
```

## The 8 Priorities (in order)

### Priority 1: Active Voting (HIGHEST)
```php
// If user has an active voting session in progress...
if ($activeVotingSession) {
    return redirect()->route('voting.show', $activeVotingSession);
}
```

**Condition:** User has created a voting code but hasn't completed voting yet
**Redirect:** `/organisations/{slug}/voting/{voting_id}`
**Example:** User started voting but closed browser

### Priority 2: Onboarded Platform User
```php
// If user is assigned to platform AND already onboarded...
if ($user->organisation_id == 1 && $user->onboarded_at !== null) {
    return redirect()->route('dashboard');
}
```

**Condition:** org_id=1 AND has seen welcome page
**Redirect:** `/dashboard` (main dashboard)
**Example:** Existing platform admin logging in again

### Priority 3: Non-Onboarded Platform User
```php
// If user is assigned to platform BUT not onboarded...
if ($user->organisation_id == 1 && $user->onboarded_at === null) {
    return redirect()->route('dashboard.welcome');
}
```

**Condition:** org_id=1 AND hasn't seen welcome yet
**Redirect:** `/dashboard/welcome` (onboarding page)
**Example:** Fresh registration, just verified email

### Priority 4: Tenant with Active Election
```php
// If user belongs to organisation with active election...
if ($user->organisation_id > 1) {
    $activeElection = Election::where('organisation_id', $user->organisation_id)
        ->where('is_active', true)
        ->first();

    if ($activeElection) {
        return redirect()->route('election.show', $activeElection);
    }
}
```

**Condition:** org_id > 1 AND organisation has active election
**Redirect:** `/organisations/{slug}/election/{election_id}`
**Example:** Committee member with live election running

### Priority 5: Tenant Organisation Dashboard
```php
// If user belongs to tenant organisation...
if ($user->organisation_id > 1) {
    return redirect()->route('organisation.dashboard', $organisation);
}
```

**Condition:** org_id > 1 (organisation assigned)
**Redirect:** `/organisations/{slug}/dashboard`
**Example:** Committee admin outside of active election

### Priority 6-8: Fallbacks
```php
// Priority 6: Check cache
if ($cached = Cache::get($cacheKey)) {
    return redirect($cached);
}

// Priority 7: Emergency dashboard
return redirect()->route('dashboard.emergency');

// Priority 8: Static HTML fallback
return response(view('login-success-fallback'), 200);
```

## Decision Tree (Simplified)

```
┌─────────────────────────────────┐
│ User successfully authenticated │
└────────────┬────────────────────┘
             │
             ▼
      ┌─────────────────┐
      │ Active voting?  │
      └─┬────────────┬──┘
        │ YES        │ NO
        │            │
        ▼            ▼
    VOTE PAGE   ┌─────────────────────┐
                │ organisation_id = 1? │
                └─┬─────────────────┬──┘
                  │ YES             │ NO
                  │                 │
                  ▼                 ▼
           ┌─────────────┐    TENANT ORG
           │ Onboarded?  │    DASHBOARD
           └─┬────────┬──┘
             │ YES    │ NO
             │        │
             ▼        ▼
          MAIN    WELCOME
        DASHBOARD  PAGE
```

## The getEffectiveOrganisationId() Hook

```php
// CRITICAL: Before checking priorities, resolve effective org_id
$effectiveOrgId = $user->getEffectiveOrganisationId();

// This method:
// 1. Checks if user.organisation_id > 1
// 2. Verifies a pivot exists for that organisation
// 3. If pivot missing → falls back to 1 (platform)
// 4. ALWAYS returns a valid organisation_id

// Result: All priorities can trust $user->organisation_id is valid
```

## Code Implementation

```php
// app/Services/DashboardResolver.php
class DashboardResolver
{
    public function resolve(User $user): RedirectResponse
    {
        try {
            // Get effective organisation (validates pivots)
            $effectiveOrgId = $user->getEffectiveOrganisationId();

            // Priority 1: Active Voting
            if ($activeVoting = $this->findActiveVoting($user)) {
                Log::info('🎯 PRIORITY 1: Active voting found', [
                    'user_id' => $user->id,
                    'voting_id' => $activeVoting->id,
                ]);
                return redirect()->route('voting.show', $activeVoting);
            }

            // Priority 2: Onboarded Platform User
            if ($effectiveOrgId == 1 && $user->onboarded_at !== null) {
                Log::info('🎯 PRIORITY 2: Onboarded platform user', [
                    'user_id' => $user->id,
                ]);
                return redirect()->route('dashboard');
            }

            // Priority 3: Non-Onboarded Platform User
            if ($effectiveOrgId == 1 && $user->onboarded_at === null) {
                Log::info('🎯 PRIORITY 3: Needs onboarding', [
                    'user_id' => $user->id,
                ]);
                return redirect()->route('dashboard.welcome');
            }

            // Priority 4-5: Tenant Organisation
            if ($effectiveOrgId > 1) {
                $organisation = Organisation::find($effectiveOrgId);

                if ($this->hasActiveElection($organisation)) {
                    Log::info('🎯 PRIORITY 4: Active election in org', [
                        'user_id' => $user->id,
                        'org_id' => $effectiveOrgId,
                    ]);
                    return redirect()->route('election.show', $election);
                }

                Log::info('🎯 PRIORITY 5: Tenant org dashboard', [
                    'user_id' => $user->id,
                    'org_id' => $effectiveOrgId,
                ]);
                return redirect()->route('organisation.dashboard', $organisation);
            }

        } catch (Exception $e) {
            // Priority 6: Cache fallback
            // Priority 7: Emergency dashboard
            // Priority 8: Static HTML
        }
    }
}
```

## Real-World Examples

### Example 1: Fresh Registration
```
User: alice@example.com (just verified email)
organisation_id: 1
onboarded_at: NULL

✅ Priority 1 check: No active voting
✅ Priority 2 check: org_id=1 but onboarded_at is NULL (skip)
✅ Priority 3 check: org_id=1 AND onboarded_at is NULL
   → MATCH!
   → Redirect to /dashboard/welcome
```

### Example 2: Returning Admin
```
User: admin@example.com (was onboarded before)
organisation_id: 1
onboarded_at: 2026-03-01 10:00:00

✅ Priority 1 check: No active voting
✅ Priority 2 check: org_id=1 AND onboarded_at is NOT NULL
   → MATCH!
   → Redirect to /dashboard
```

### Example 3: Committee Member Voting
```
User: voter@org.com (committee member)
organisation_id: 2
Has active voting session in progress

✅ Priority 1 check: Active voting found
   → MATCH!
   → Redirect to /organisations/orgname/voting/{voting_id}
   (Continues voting where they left off)
```

### Example 4: Committee Admin (No Active Election)
```
User: admin@org.com (org administrator)
organisation_id: 2
onboarded_at: 2026-02-01 (irrelevant for tenants)
No active elections

✅ Priority 1 check: No active voting
✅ Priority 2 check: org_id ≠ 1 (skip)
✅ Priority 3 check: org_id ≠ 1 (skip)
✅ Priority 4 check: organisation has election? NO
✅ Priority 5 check: organisation_id > 1
   → MATCH!
   → Redirect to /organisations/orgname/dashboard
```

## Debugging Priorities

### Check Logs
```bash
tail -f storage/logs/laravel.log | grep "🎯 PRIORITY"
```

You'll see:
```
[2026-03-04 10:30:45] laravel.INFO: 🎯 PRIORITY 3: Needs onboarding
[2026-03-04 10:31:02] laravel.INFO: 🎯 PRIORITY 2: Onboarded platform user
[2026-03-04 11:00:01] laravel.INFO: 🎯 PRIORITY 5: Tenant org dashboard
```

### Add Manual Debugging
```php
// In DashboardResolver::resolve()
Log::info('DEBUG: User state', [
    'user_id' => $user->id,
    'organisation_id' => $user->organisation_id,
    'effective_org_id' => $user->getEffectiveOrganisationId(),
    'onboarded_at' => $user->onboarded_at,
    'has_active_voting' => $this->findActiveVoting($user) ? true : false,
    'has_active_election' => $this->hasActiveElection($user->organisation) ? true : false,
]);
```

## Common Issues

### Issue 1: Wrong Redirect (Platform user → Org Dashboard)
**Symptom:** User sees `/organisations/publicdigit` instead of `/dashboard/welcome`

**Root Cause:**
- getEffectiveOrganisationId() returning wrong org
- organisation_id stale (pivot missing)

**Debug:**
```php
Log::info('User pivot check', [
    'user_id' => $user->id,
    'org_id' => $user->organisation_id,
    'pivot_exists' => $user->belongsToOrganisation($user->organisation_id),
]);
```

### Issue 2: Stuck on Welcome Page
**Symptom:** Every login shows `/dashboard/welcome`

**Root Cause:**
- onboarded_at never gets set
- WelcomeDashboardController not updating it

**Debug:**
```php
// Check if welcome page controller is setting onboarded_at
SELECT onboarded_at FROM users WHERE id = ?;
-- Should be NULL before visiting welcome, NOT NULL after
```

### Issue 3: 403 on Tenant Dashboard
**Symptom:** Redirect to org dashboard but get 403

**Root Cause:**
- EnsureOrganisationMember middleware checking pivot
- User has no pivot for organisation_id

**Debug:**
```sql
SELECT * FROM user_organisation_roles
WHERE user_id = ? AND organisation_id = ?;
-- Should return 1 row, got 0 rows
```

---

**Next:** [05-POST_LOGIN_ROUTING.md](05-POST_LOGIN_ROUTING.md)
