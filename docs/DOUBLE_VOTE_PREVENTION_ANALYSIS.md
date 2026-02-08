# Double Vote Prevention Analysis

**Date:** 2026-02-08
**Status:** ✅ **LOGIC WORKING** but ❌ **ROUTE MISSING**

---

## Executive Summary

**Good News:** The `has_voted` flag logic IS working correctly in VoteController::first_submission()

**Bad News:** The redirect route it tries to use doesn't exist, causing a 500 error instead of graceful redirect

---

## What We Discovered

### ✅ The Logic IS Working

When a user with `has_voted = true` attempts to vote in a real election:

1. **Line 410-424 in VoteController::first_submission()** correctly checks:
   ```php
   if ($election->type === 'real' && $code && $code->has_voted) {
       // BLOCK THE VOTE
       return redirect()->route($route, $routeParams)
           ->withErrors(['vote' => 'You have already voted...']);
   }
   ```

2. **Debug Test Confirms:**
   - has_voted flag is correctly set: ✅
   - Election type check works: ✅
   - Condition is properly evaluated: ✅
   - The code ATTEMPTS to redirect: ✅

### ❌ The Route Doesn't Exist

**The Problem:**
- VoteController tries to redirect to: `route('slug.dashboard')`
- This route **does NOT exist** in electionRoutes.php
- Laravel throws `RouteNotFoundException`
- Test receives 500 error instead of 302 redirect

**Line 419 in VoteController:**
```php
$route = $voterSlug ? 'slug.dashboard' : 'dashboard';
```

**Available Routes in electionRoutes.php:**
- ✅ `election.dashboard` - exists
- ✅ `dashboard` - exists (legacy)
- ❌ `slug.dashboard` - **DOES NOT EXIST**

---

## Test Results

### Debug Test Output

```
=== BEFORE SETTING has_voted ===
has_voted: 0

=== AFTER SETTING has_voted = true ===
has_voted (fresh): 1 ✅

=== ATTEMPTING SECOND VOTE ===
Status: 500 ❌
Exception: RouteNotFoundException
Message: Route [slug.dashboard] not defined.

=== CODE STATE IN FIRST_SUBMISSION ===
type='real', has_voted=1
✅ SHOULD REDIRECT (conditions met for double vote check)
```

---

## The Issue in Detail

### VoteController::first_submission() Line 410-424

```php
// ⛔ REAL ELECTIONS: Block voting if already voted
$code = $auth_user->code;
if ($election->type === 'real' && $code && $code->has_voted) {
    \Log::warning('⛔ Real election - blocking vote submission...', [
        'user_id' => $auth_user->id,
        'election_id' => $election->id,
    ]);

    $voterSlug = $request->attributes->get('voter_slug');
    $route = $voterSlug ? 'slug.dashboard' : 'dashboard';  // ← PROBLEM HERE
    $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

    return redirect()->route($route, $routeParams)           // ← CRASHES HERE
        ->withErrors(['vote' => 'You have already voted...']);
}
```

### What Should Happen

When user with `has_voted=true` tries to vote in real election:

1. ✅ Code correctly identifies double vote attempt
2. ✅ Code correctly determines it's a real election
3. ✅ Code correctly builds redirect parameters
4. ❌ Code tries to use non-existent `slug.dashboard` route
5. ❌ Laravel throws RouteNotFoundException
6. ❌ User gets 500 error instead of redirect

---

## Root Cause

The slug-based voting workflow uses routes like:
- `/v/{vslug}/code/create` → `slug.code.create`
- `/v/{vslug}/vote/submit` → `slug.vote.submit`
- etc.

But there is **NO slug-based dashboard route defined**.

### Available Routes for Redirects

In electionRoutes.php, only these dashboard routes exist:

1. **`election.dashboard`** - GET /election
2. **`dashboard`** - Legacy fallback (undefined destination)

Neither of these are in the `/v/{vslug}/...` namespace.

---

## How to Fix

### Option 1: Create slug-based dashboard route (RECOMMENDED)

Add to electionRoutes.php (within slug route group):

```php
Route::prefix('v/{vslug}')->middleware(['voter.slug.window'])->group(function () {
    // ... existing routes ...

    // Dashboard for slug-based voter
    Route::get('dashboard', function (\Illuminate\Http\Request $request) {
        $voter = $request->attributes->get('voter');
        $voterSlug = $request->attributes->get('voter_slug');

        return redirect()->route('election.dashboard')
            ->with('info', 'Your voting session has ended.');
    })->name('slug.dashboard');
});
```

### Option 2: Use existing dashboard route (QUICK FIX)

Change VoteController line 419:

```php
// OLD:
$route = $voterSlug ? 'slug.dashboard' : 'dashboard';

// NEW:
$route = 'election.dashboard';
$routeParams = []; // Don't pass slug to non-slug route
```

### Option 3: Create separate voting completion page

Create a new route specifically for voting completion/rejection:

```php
Route::prefix('v/{vslug}')->group(function () {
    Route::get('voting-complete', function () {
        return Inertia::render('Voting/Complete', [
            'message' => 'Your voting session is complete.'
        ]);
    })->name('slug.voting.complete');
});
```

---

## Verification

### Test Scenarios to Verify Fix

**Scenario 1: Double vote blocked in real election**
- [ ] has_voted = true
- [ ] election type = 'real'
- [ ] Second vote attempt
- Expected: 302 redirect (not 500)
- Expected: User sees error message "You have already voted"

**Scenario 2: Demo election allows revoting**
- [ ] has_voted = true
- [ ] election type = 'demo'
- [ ] Second vote attempt
- Expected: 302 redirect to vote/verify
- Expected: Vote is accepted

**Scenario 3: First vote works normally**
- [ ] has_voted = false
- [ ] First vote attempt
- Expected: 302 redirect to vote/verify
- Expected: Vote is accepted

---

## Code Locations

### Files Affected

1. **VoteController.php**
   - Line 410-424: Double vote check (WORKING ✅)
   - Line 419: Route name (BROKEN ❌)
   - Also appears at: 574, 643, 1259

2. **electionRoutes.php**
   - Line 354-382: Slug route group
   - Missing: `slug.dashboard` route

### The Fix Required

Update VoteController.php line 419:
```php
// Change from:
$route = $voterSlug ? 'slug.dashboard' : 'dashboard';

// To:
$route = $voterSlug ? 'slug.vote.complete' : 'election.dashboard';
```

And add the route to electionRoutes.php.

---

## Conclusion

**The double vote prevention logic is CORRECT and WORKING.**

The only issue is:
- ✅ `has_voted` flag is correctly preventing double votes
- ✅ Real election check is working
- ❌ The redirect route doesn't exist
- ❌ Causes 500 error instead of graceful 302 redirect

**Fix Status:** Straightforward - just needs route definition and route name update

---

## Recommendations

1. **PRIORITY 1:** Define the missing `slug.dashboard` route OR use existing dashboard route
2. **PRIORITY 2:** Test all double vote scenarios after fix
3. **PRIORITY 3:** Add logging to confirm redirect works

---

## Related Issues

- Missing `/v/{vslug}/dashboard` endpoint
- No feedback to user when double vote is detected (500 error instead of error page)
- Inconsistency: other slug routes work, but dashboard doesn't

**Status:** FIXABLE IN PRODUCTION (simple route addition)
