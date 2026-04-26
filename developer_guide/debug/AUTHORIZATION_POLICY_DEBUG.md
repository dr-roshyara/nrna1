# Authorization & Policy Debug Guide

**Project:** Public Digit  
**Focus:** 403 Forbidden errors, policy failures, authorization mismatches  
**Related File:** `app/Policies/ElectionPolicy.php`

---

## 🎯 Quick Diagnosis

When you hit a 403 error:

```bash
# 1. Check what policy is being called
grep -r "->can(" routes/organisations.php | head -5

# 2. Check what the policy returns
php artisan tinker
>>> $user = User::first()
>>> $election = Election::first()
>>> $user->can('manageSettings', $election)
true / false

# 3. If true in tinker but false in browser = context mismatch
```

---

## 🏗️ The Authorization Stack

```
HTTP Request
    ↓
[1] Middleware stack
    ├─ auth (verify user exists)
    ├─ verified (verify email)
    └─ ensure.organisation (set session tenant)
    ↓
[2] Route model binding
    ├─ Organisation resolved
    └─ Election resolved
    ↓
[3] Authorization gate
    ├─ ->can('action', $model)
    └─ Calls policy method
    ↓
[4] Policy method executes
    ├─ Checks user role
    ├─ Checks organisation ownership
    └─ Returns true/false
    ↓
[5] 403 if false, continue if true
```

---

## 🔴 Issue: 403 Forbidden on Valid Action

### Symptoms

```bash
# Route exists and resolves
GET /organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/management
# → 200 OK (no policy required)

# But this fails
POST /organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/complete-administration
# → 403 Forbidden

# And in tinker, the same user/election returns true
>>> auth()->user()->can('manageSettings', $election)
true
```

### Root Causes

#### 1. Session Tenant Context Missing

**The Problem:**

Policy checks:
```php
// ElectionPolicy.php
public function manageSettings(User $user, Election $election): bool
{
    $current_org = session('current_organisation_id');
    
    // ← If session is empty, this check fails
    return $election->organisation_id === $current_org;
}
```

But middleware sets session **AFTER** policy check in some cases.

**Why it happens:**
```php
Route::middleware('ensure.organisation')        // Sets session
    ->post('/action', Controller@action)
    ->can('manageSettings', 'election');        // Policy runs HERE
    // Depending on order, session might not be set yet
```

**Diagnosis:**

Add logging to policy:
```php
public function manageSettings(User $user, Election $election): bool
{
    $session_org = session('current_organisation_id');
    
    \Log::debug('POLICY: manageSettings', [
        'user_id' => $user->id,
        'election_id' => $election->id,
        'session_org_id' => $session_org,
        'election_org_id' => $election->organisation_id,
        'match' => $session_org === $election->organisation_id,
    ]);
    
    return $session_org === $election->organisation_id;
}
```

Check logs:
```bash
tail storage/logs/laravel.log | grep "POLICY: manageSettings"

# Look for: session_org_id = null
```

**Fix:**

Option A: Don't rely on session in policy:
```php
// ✅ BETTER
public function manageSettings(User $user, Election $election): bool
{
    // Check through user relationship instead
    return $user->organisationRoles()
        ->where('organisation_id', $election->organisation_id)
        ->exists();
}
```

Option B: Ensure middleware runs before policy:
```php
// ✅ CORRECT ORDER
Route::prefix('/organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])  // ← Before policy
    ->group(function () {
        Route::post('/elections/{election}/action', ...)
            ->can('action', 'election');  // ← After middleware
    });
```

---

#### 2. User Not Member of Organisation

**The Problem:**

Policy checks:
```php
public function manageSettings(User $user, Election $election): bool
{
    // Are they in the same org?
    $isMember = $user->organisationRoles()
        ->where('organisation_id', $election->organisation_id)
        ->exists();
    
    return $isMember;
}
```

But user has no role in this organisation.

**Diagnosis:**

```bash
php artisan tinker

>>> $user = User::find('user-id')
>>> $org = Organisation::find('org-id')
>>> $user->organisationRoles()->where('organisation_id', $org->id)->exists()
false  ← ❌ User not a member!
```

**Fix:**

Add user to organisation:
```bash
php artisan tinker

>>> $user = User::first()
>>> $org = Organisation::first()
>>> $user->organisationRoles()->attach($org->id, ['role' => 'owner'])

# Verify
>>> $user->organisationRoles()->where('organisation_id', $org->id)->exists()
true ✅
```

---

#### 3. User Has Wrong Role

**The Problem:**

Policy checks:
```php
public function manageSettings(User $user, Election $election): bool
{
    // Only chiefs can manage
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->whereIn('role', ['chief', 'deputy'])
        ->where('status', 'active')
        ->exists();
}
```

But user is a `commissioner` (not chief/deputy).

**Diagnosis:**

```bash
php artisan tinker

>>> $user = User::first()
>>> $election = Election::first()
>>> ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->get(['role', 'status'])

# Returns: [{ role: 'commissioner', status: 'active' }]
# ← Not in ['chief', 'deputy'], so denied
```

**Fix:**

Assign correct role:
```bash
php artisan tinker

>>> $user = User::first()
>>> $election = Election::first()
>>> ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->update(['role' => 'chief'])

# Verify
>>> ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('role', 'chief')
    ->exists()
true ✅
```

---

#### 4. Election Officer Record Missing

**The Problem:**

Policy requires:
```php
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->exists();  // ← No record found
}
```

But user was never assigned as election officer.

**Diagnosis:**

```bash
php artisan tinker

>>> ElectionOfficer::where('user_id', 'user-id')
    ->where('election_id', 'election-id')
    ->exists()
false  ← ❌ Record doesn't exist
```

**Fix:**

Create election officer record:
```bash
php artisan tinker

>>> ElectionOfficer::create([
    'user_id' => 'user-id',
    'election_id' => 'election-id',
    'organisation_id' => 'org-id',
    'role' => 'chief',
    'status' => 'active'
])

# Verify
>>> ElectionOfficer::where('user_id', 'user-id')
    ->where('election_id', 'election-id')
    ->exists()
true ✅
```

---

#### 5. Status is Not "Active"

**The Problem:**

Policy checks:
```php
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('status', 'active')  // ← Must be active
        ->exists();
}
```

But election officer status is `inactive` or `pending`.

**Diagnosis:**

```bash
php artisan tinker

>>> ElectionOfficer::where('user_id', 'user-id')
    ->where('election_id', 'election-id')
    ->first()
    ->status
"inactive"  ← ❌ Not active
```

**Fix:**

Set status to active:
```bash
php artisan tinker

>>> $officer = ElectionOfficer::where('user_id', 'user-id')
    ->where('election_id', 'election-id')
    ->first()
>>> $officer->update(['status' => 'active'])

# Verify
>>> $officer->refresh()
>>> $officer->status
"active" ✅
```

---

### Debug Checklist for 403

- [ ] User is authenticated: `auth()->check()`
- [ ] User is member of organisation: `$user->organisationRoles()->where('organisation_id', $org->id)->exists()`
- [ ] Session tenant is set: `session('current_organisation_id')`
- [ ] Election officer record exists: `ElectionOfficer::where('user_id', $user->id)->where('election_id', $election->id)->exists()`
- [ ] Election officer role is correct: `['chief', 'deputy', ...]`
- [ ] Election officer status is active: `status === 'active'`
- [ ] Policy is called (add logging and check logs)
- [ ] Policy receives correct models (add logging)

---

## 🟠 Issue: Policy Returns True in Tinker, False in Browser

### Root Causes

This is the most confusing error. Everything works in `tinker` but fails in the browser.

**Why it happens:**

Tinker has no:
- HTTP request context
- Middleware execution
- Session data
- Authorization gates
- Proper timing of checks

#### 1. Session Data Missing in Browser

**In Tinker:**
```php
>>> session('current_organisation_id')
null  ← Tinker has no session
```

**But policy works in tinker:**
```php
>>> $user->can('manageSettings', $election)
true

// Why? Because policy doesn't rely on session when testing like this
```

**In Browser:**
```php
// Middleware hasn't set session yet
// Policy fails because it checks session
```

**Fix:**
Don't use session in policy. Use relationships instead:

```php
// ❌ BAD: Relies on session
public function manageSettings(User $user, Election $election): bool
{
    return session('current_organisation_id') === $election->organisation_id;
}

// ✅ GOOD: Relationship-based
public function manageSettings(User $user, Election $election): bool
{
    return $user->organisationRoles()
        ->where('organisation_id', $election->organisation_id)
        ->exists();
}
```

---

#### 2. Timing Issues (Middleware Order)

**In Tinker:**
```php
>>> session()->put('current_organisation_id', 'org-id')
>>> $user->can('manageSettings', $election)
true
```

You manually set session, so it works.

**In Browser:**
```
Request arrives
    ↓
Route matches
    ↓
Policy gate runs  ← Session might not be set yet
    ↓
Middleware runs  ← Session gets set here
    ↓
Controller runs
```

**Fix:**

Ensure middleware runs **before** policy:

```php
// ✅ CORRECT
Route::middleware('ensure.organisation')  // Sets session
    ->post('/action', ...)
    ->can('action', 'model');           // Checks session

// ❌ WRONG
Route::post('/action', ...)
    ->can('action', 'model')            // Checks session (not set yet)
    ->middleware('ensure.organisation'); // Sets session
```

---

#### 3. Different User/Election Context

**In Tinker (you manually pick):**
```php
>>> $user = User::find('my-user-id')
>>> $election = Election::find('my-election-id')
>>> $user->can('manageSettings', $election)
true
```

**In Browser (automatic from binding):**
```
Route binding injects $election automatically
But it might be a DIFFERENT election
→ Policy checks that election
→ Different permissions → false
```

**Diagnosis:**

Add logging to policy:
```php
public function manageSettings(User $user, Election $election): bool
{
    \Log::debug('POLICY DEBUG', [
        'tinker_user_id' => 'my-user-id',
        'actual_user_id' => $user->id,
        'tinker_election_id' => 'my-election-id',
        'actual_election_id' => $election->id,
    ]);
}
```

Check if IDs match.

**Fix:**

Verify you're testing with the same user/election from the browser request.

---

### Debug Checklist for "True in Tinker, False in Browser"

- [ ] Manually set session in tinker: `session()->put('current_organisation_id', $org->id)`
- [ ] Use exact user/election from browser: Don't guess, check request data
- [ ] Check middleware order: Middleware before policy
- [ ] Check policy logic: Log every condition
- [ ] Don't rely on session: Use relationships instead
- [ ] Use roles/relationships: More reliable than session in policies

---

## 📚 ElectionPolicy Reference

### All Policy Methods

```php
class ElectionPolicy
{
    /**
     * Any active officer for this organisation may view
     */
    public function view(User $user, Election $election): bool
    
    /**
     * Org owner/admin or any active officer may view results
     */
    public function viewResults(User $user, Election $election): bool
    
    /**
     * Chief, deputy, or org owner/admin may manage settings
     */
    public function manageSettings(User $user, Election $election): bool
    
    /**
     * Chief only may publish results
     */
    public function publishResults(User $user, Election $election): bool
    
    /**
     * Chief or deputy may manage voters
     */
    public function manageVoters(User $user, Election $election): bool
    
    /**
     * Chief or deputy may manage posts
     */
    public function managePosts(User $user, Election $election): bool
    
    /**
     * Generic manage for state machine
     */
    public function manage(User $user, Election $election): bool
    
    /**
     * Chief or deputy may create election
     */
    public function create(User $user, Organisation $organisation): bool
}
```

### Testing Each Method

```bash
php artisan tinker

$user = User::first()
$election = Election::first()

# Test each method
$user->can('view', $election)
$user->can('viewResults', $election)
$user->can('manageSettings', $election)
$user->can('publishResults', $election)
$user->can('manageVoters', $election)
$user->can('managePosts', $election)
$user->can('manage', $election)
```

---

## 🔍 Role & Permission Model

### Hierarchy

```
User
├─ UserOrganisationRole
│  ├─ organisation_id
│  └─ role: 'owner' | 'admin' | 'staff' | 'guest'
│
└─ ElectionOfficer
   ├─ election_id
   ├─ role: 'chief' | 'deputy' | 'commissioner'
   └─ status: 'active' | 'inactive' | 'pending'
```

### Permission Matrix

| Action              | Owner | Admin | Chief | Deputy | Commissioner |
|-------------------|-------|-------|-------|--------|--------------|
| view               | ✅    | ✅    | ✅    | ✅     | ✅           |
| viewResults        | ✅    | ✅    | ✅    | ✅     | ✅           |
| manageSettings     | ✅    | ✅    | ✅    | ✅     | ❌           |
| publishResults     | ❌    | ❌    | ✅    | ❌     | ❌           |
| manageVoters       | ❌    | ❌    | ✅    | ✅     | ❌           |
| managePosts        | ❌    | ❌    | ✅    | ✅     | ❌           |
| manage             | ❌    | ❌    | ✅    | ✅     | ❌           |

---

## 🛡️ Correct Authorization Pattern

### Pattern 1: Read-Only (Everyone in Org)

```php
// Route - no ->can() needed
Route::get('/elections/{election}/results', [Controller::class, 'showResults']);

// Controller
public function showResults(Election $election)
{
    // User is already member (checked by ensure.organisation)
    // No additional authorization needed
    return view('results', ['election' => $election]);
}
```

---

### Pattern 2: Chief Only

```php
// Route
Route::post('/elections/{election}/publish-results', [Controller::class, 'publish'])
    ->can('publishResults', 'election');

// Policy
public function publishResults(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('role', 'chief')
        ->where('status', 'active')
        ->exists();
}
```

---

### Pattern 3: Chief or Deputy

```php
// Route
Route::post('/elections/{election}/manage-voters', [Controller::class, 'manageVoters'])
    ->can('manageVoters', 'election');

// Policy
public function manageVoters(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->whereIn('role', ['chief', 'deputy'])
        ->where('status', 'active')
        ->exists();
}
```

---

## 🐛 Common Authorization Bugs

### Bug 1: Checking Wrong Table

```php
// ❌ WRONG: Checking election officers
public function view(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->exists();
    // But not all viewers are officers!
}

// ✅ CORRECT: Check organisation membership
public function view(User $user, Election $election): bool
{
    return $user->organisationRoles()
        ->where('organisation_id', $election->organisation_id)
        ->exists();
    // All organisation members can view
}
```

---

### Bug 2: Forgetting Status Check

```php
// ❌ WRONG: Doesn't check status
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('role', 'chief')
        ->exists();
}

// ✅ CORRECT: Include status
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('role', 'chief')
        ->where('status', 'active')  // ← Add this
        ->exists();
}
```

---

### Bug 3: Hardcoded IDs

```php
// ❌ WRONG: Hardcoded IDs (why are you testing like this?)
public function manageSettings(User $user, Election $election): bool
{
    // This only works for specific users/elections!
    return $user->id === 'hardcoded-id';
}

// ✅ CORRECT: Use model relationships
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->exists();
}
```

---

## 📊 Authorization Debugging Workflow

### Step 1: Confirm 403 is from Policy

```bash
# Check route has ->can()
grep -B 2 -A 2 "->can()" routes/organisations.php | grep -A 5 "your-route-name"

# If no ->can(), then 403 comes from somewhere else
# Check middleware instead
```

---

### Step 2: Identify Which Policy Method

```bash
# From route, find the policy action
# Example: ->can('manageSettings', 'election')
#          ↓ Find this method
grep -A 10 "function manageSettings" app/Policies/ElectionPolicy.php
```

---

### Step 3: Add Logging to Policy

```php
public function manageSettings(User $user, Election $election): bool
{
    \Log::debug('POLICY: manageSettings', [
        'step' => 1,
        'user_id' => $user->id,
        'election_id' => $election->id,
        'election_org_id' => $election->organisation_id,
    ]);
    
    $has_role = ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('status', 'active')
        ->exists();
    
    \Log::debug('POLICY: manageSettings', [
        'step' => 2,
        'has_role' => $has_role,
    ]);
    
    return $has_role;
}
```

---

### Step 4: Check Logs

```bash
tail -50 storage/logs/laravel.log | grep "POLICY"

# Look for:
# - Did it log step 1? (Policy was called)
# - What was user_id? (Is it the right user?)
# - What was election_id? (Is it the right election?)
# - What was has_role? (Why did it fail?)
```

---

### Step 5: Test in Tinker

```bash
php artisan tinker

# Manually check the same conditions the policy checks
$user = User::find('user-from-logs')
$election = Election::find('election-from-logs')

ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('status', 'active')
    ->exists()

# Should match what policy returned
```

---

### Step 6: Fix Based on Finding

| Finding | Action |
|---------|--------|
| ElectionOfficer record missing | Create it |
| Status is not 'active' | Update status |
| Role is wrong | Update role |
| Wrong election bound | Check route binding |
| Wrong user | Check auth context |

---

## ✅ Health Check

Run this to verify authorization system:

```bash
php artisan tinker

# 1. User exists and is authenticated
$user = User::first()
$user->exists()
# true

# 2. User is member of organisation
$org = Organisation::first()
$user->organisationRoles()
    ->where('organisation_id', $org->id)
    ->exists()
# true

# 3. Election officer record exists
$election = $org->elections()->first()
ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->exists()
# true

# 4. Election officer has correct role
ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->first()
    ->role
# 'chief' or 'deputy'

# 5. Election officer is active
ElectionOfficer::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->first()
    ->status
# 'active'

# 6. Policy returns correct value
$user->can('manageSettings', $election)
# true (if user is chief/deputy)
# false (if user is commissioner)

# 7. Policy works in request context
# Test in browser - should match tinker
```

All checks pass? ✅ Authorization is healthy.

---

## 📝 Logging Best Practices

### What to Log

```php
public function manageSettings(User $user, Election $election): bool
{
    \Log::debug('Authorization check', [
        'policy' => 'ElectionPolicy::manageSettings',
        'user_id' => $user->id,
        'user_email' => $user->email,
        'election_id' => $election->id,
        'election_org_id' => $election->organisation_id,
    ]);
    
    $allowed = /* ... check ... */;
    
    \Log::debug('Authorization result', [
        'allowed' => $allowed,
        'reason' => $allowed ? 'User has correct role' : 'User lacks required role',
    ]);
    
    return $allowed;
}
```

### Where to Look

```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Specific policy
tail storage/logs/laravel.log | grep "manageSettings"

# All authorization
tail storage/logs/laravel.log | grep "Authorization"

# Last 100 lines
tail -100 storage/logs/laravel.log
```

---

## 🔗 Related Files

- `app/Policies/ElectionPolicy.php` — All authorization logic
- `app/Models/ElectionOfficer.php` — Officer records
- `app/Models/User.php` — User relations
- `app/Http/Middleware/EnsureOrganisationMember.php` — Membership check
- `routes/organisations.php` — Route definitions with policies

---

**Last Updated:** 2026-04-27  
**Remember:** Logs are your best friend. If in doubt, add logging.
