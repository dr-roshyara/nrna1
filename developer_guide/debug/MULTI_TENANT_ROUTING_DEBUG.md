# Multi-Tenant Routing Debug Guide

**Project:** Public Digit (Multi-tenant Election Platform)  
**Last Updated:** 2026-04-27  
**Architecture:** Laravel 11 + DDD + Scoped Route Binding

---

## 📋 Quick Reference

### Common Issues & Fixes

| Error | Root Cause | Fix |
|-------|-----------|-----|
| **404** (clean route) | Binding not scoped to parent | Add `->scopeBindings()` |
| **404** (after route:clear) | Route cache not reloaded | Restart dev server after `route:cache` |
| **403** (policy denies) | Session tenant mismatch | Verify `session('current_organisation_id')` is set |
| **500** (ModelNotFoundException) | Parent model missing | Check organisation/election actually exists |
| **Silent 404** | Custom binding conflicts with implicit binding | Remove redundant `Route::bind()` |

---

## 🏗️ Architecture Overview

### Request Lifecycle (Correct Order)

```
HTTP Request
    ↓
[1] Router matches route pattern
    ↓
[2] Route Model Binding (implicit via relationship)
    ├─ Resolves {organisation:slug}
    └─ Resolves {election} via Organisation::elections() relationship
    ↓
[3] Middleware stack executes
    ├─ auth
    ├─ verified
    └─ ensure.organisation ← Sets session('current_organisation_id')
    ↓
[4] Authorization policies (->can())
    ├─ Checks: can user perform action?
    └─ Uses session context + model instance
    ↓
[5] Controller handles request
    ├─ Models already resolved & authorized
    └─ Session context available
    ↓
[6] Response
```

### Multi-Tenant Model Structure

```
Organisation (aggregate root)
├─ id: UUID
├─ slug: "namaste-nepal-gmbh"
└─ elections: HasMany relationship
    └─ Election (child entity)
        ├─ id: UUID
        ├─ slug: "namaste-lk0dziy6"
        ├─ organisation_id: UUID (foreign key)
        └─ state: "administration"
```

### Route Pattern

```php
Route::prefix('/organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        
        Route::prefix('/elections/{election}')
            ->scopeBindings()  // ← CRITICAL: Enforces org ownership
            ->group(function () {
                Route::get('/positions', ...)
                Route::post('/posts', ...)
                // ... more routes
            });
            
    });
```

**Full URL Example:** `/organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/positions`

---

## 🔴 Issue: 404 on Valid Routes

### Symptoms
- Route exists in `php artisan route:list` ✓
- Data exists in database ✓
- Browser returns: **404 Not Found**

### Root Causes (In Order of Likelihood)

#### 1. Missing `->scopeBindings()` (MOST COMMON)

**Problem:**
```php
// ❌ WRONG
Route::prefix('/elections/{election}')
    ->group(function () { ... })
```

Without `scopeBindings()`, Laravel doesn't know to resolve Election through Organisation relationship.

**Diagnosis:**
```bash
# Run this
php artisan route:list | grep elections.positions

# You'll see the route, but accessing it returns 404
curl http://localhost:8000/organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/positions
# → 404
```

**Fix:**
```php
// ✅ CORRECT
Route::prefix('/elections/{election}')
    ->scopeBindings()  // Add this line
    ->group(function () { ... })
```

**Why this matters:**
- `scopeBindings()` tells Laravel: "Resolve child models through parent relationships"
- Enforces aggregate boundary at routing layer (DDD-compliant)
- Automatic multi-tenant isolation

---

#### 2. Custom Binding Conflicts with Implicit Binding

**Problem:**
```php
// In routes:
Route::prefix('/elections/{election:slug}')

// In RouteServiceProvider:
Route::bind('election', function($value) {
    return Election::where('slug', $value)->firstOrFail();
})
```

**Why it fails:**
- `:slug` suffix = implicit scoped binding (via relationship)
- Custom `Route::bind()` = explicit global binding
- They conflict → binding resolution fails → **404**

**Diagnosis:**
Check `app/Providers/RouteServiceProvider.php`:
```bash
grep -A 10 "registerElectionBinding" app/Providers/RouteServiceProvider.php
```

If this method exists AND your routes use `{election:slug}`, you have a conflict.

**Fix:**
- **Option A (Recommended):** Remove custom binding entirely, use `->scopeBindings()`
- **Option B:** Remove `:slug` suffix from route, keep binding

```php
// ✅ BEST (Option A)
// DELETE registerElectionBinding() method
// ADD ->scopeBindings() to routes

// ⚠️ OK (Option B)
Route::prefix('/elections/{election}')  // Remove :slug
    ->group(...)
// Keep custom binding
```

---

#### 3. Route Cache Not Reloaded After `route:cache`

**Problem:**
```bash
php artisan route:cache
# Server still returns 404
```

**Why it fails:**
- `route:cache` writes to `bootstrap/cache/routes-v7.php`
- Dev server must **restart** to load new cache
- Running server continues using old route definitions

**Diagnosis:**
```bash
# Check if cache is active
php artisan route:list | head -1

# Or hit this endpoint:
curl http://localhost:8000/debug-route-cache
# true = cache active
# false = using old routes
```

**Fix:**
```bash
php artisan route:cache
# IMPORTANT: Kill and restart server
# Ctrl+C
php artisan serve
```

---

#### 4. Global Scope Filtering (Session Context Missing)

**Problem:**
Election has `BelongsToTenant` global scope:
```php
class Election extends Model {
    use BelongsToTenant;  // Filters by session('current_organisation_id')
}
```

When custom binding tries to query **before middleware runs**:
```php
// Routes
Route::prefix('/elections/{election:slug}')  // Binding happens HERE
    ->middleware('ensure.organisation')       // But middleware runs HERE
```

Session is still empty during binding → scope filters out election → **404**

**Diagnosis:**
Check logs for binding errors:
```bash
tail -50 storage/logs/laravel.log | grep -i "election\|404"
```

Look for: `"Election binding FAILED"` or `ModelNotFoundException`

**Fix:**
Use `->scopeBindings()` + remove custom binding:
```php
Route::prefix('/elections/{election}')
    ->scopeBindings()  // Resolves via relationship, not session
    ->middleware('ensure.organisation')
```

---

### Debug Checklist for 404

- [ ] Route exists: `php artisan route:list --name=organisations.elections.positions`
- [ ] Data exists: Query database for election and organisation
- [ ] `->scopeBindings()` is present in route group
- [ ] No conflicting `Route::bind('election', ...)` in RouteServiceProvider
- [ ] Dev server restarted after route changes: `php artisan serve`
- [ ] Parent model (organisation) matches child (election) organisation_id
- [ ] No soft-deleted parent: `Organisation::find(...)->trashed()`

---

## 🟠 Issue: 403 Forbidden (Authorization Fails)

### Symptoms
- Route resolves (no 404) ✓
- Model binding works ✓
- Browser returns: **403 Forbidden**
- OR: Policy returns `true` in Tinker, but `false` in browser

### Root Causes

#### 1. Session Tenant Context Not Set

**Problem:**
Policy checks use session:
```php
// In ElectionPolicy
public function manageSettings(User $user, Election $election): bool {
    // Checks if user belongs to same org as election
    $org = session('current_organisation_id');
    
    if ($election->organisation_id !== $org) {
        return false;  // ← 403
    }
}
```

But `ensure.organisation` middleware hasn't run yet:
```php
// Policy runs BEFORE this line
return $next($request);  // ← Sets session here
```

**Diagnosis:**
Add logging to policy:
```php
public function manageSettings(User $user, Election $election): bool {
    \Log::debug('POLICY CHECK', [
        'user_id' => $user->id,
        'session_org' => session('current_organisation_id'),
        'election_org' => $election->organisation_id,
        'match' => session('current_organisation_id') === $election->organisation_id,
    ]);
    
    return session('current_organisation_id') === $election->organisation_id;
}
```

Check logs:
```bash
tail storage/logs/laravel.log | grep "POLICY CHECK"
```

**Fix:**
Ensure middleware runs before policy:
```php
Route::prefix('/organisations/{organisation}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])  // ← Must be here
    ->group(function () {
        Route::post('/elections/{election}/complete', ...)
            ->can('manageSettings', 'election');  // Now session is set
    });
```

---

#### 2. Wrong Model Instance

**Problem:**
```php
// In controller
public function update(Election $election) {
    // $election might be from a DIFFERENT organisation
    // if binding is global, not scoped
}
```

Without `scopeBindings()`, Laravel finds first matching election regardless of organisation.

**Diagnosis:**
Add to controller:
```php
\Log::debug('Controller received', [
    'election_id' => $election->id,
    'election_org_id' => $election->organisation_id,
    'session_org_id' => session('current_organisation_id'),
]);
```

**Fix:**
Use `->scopeBindings()` to ensure correct election is passed.

---

#### 3. User Not Member of Organisation

**Problem:**
```php
// In ensure.organisation middleware
$isMember = $user->organisationRoles()
    ->where('organisation_id', $organisation->id)
    ->exists();

if (!$isMember) {
    return redirect(...);  // User bounced before reaching policy
}
```

**Diagnosis:**
Check logs for middleware rejection:
```bash
grep -i "non-member\|access denied" storage/logs/laravel.log
```

**Fix:**
User must have role in organisation:
```bash
# In tinker:
$user->organisationRoles()->attach($organisation->id, ['role' => 'owner']);
```

---

### Debug Checklist for 403

- [ ] User is authenticated: `auth()->check()`
- [ ] User is member of organisation: `$user->organisationRoles()->where('organisation_id', $org->id)->exists()`
- [ ] Session is set: `session('current_organisation_id')`
- [ ] Policy receives correct election instance: Log in policy
- [ ] Election actually belongs to that organisation: `$election->organisation_id === $org->id`
- [ ] No policy returns false accidentally (check logic)

---

## 🟢 Issue: 500 Error (ModelNotFoundException)

### Symptoms
- Browser returns: **500 Internal Server Error**
- Logs contain: `ModelNotFoundException`

### Root Causes

#### 1. Parent Model (Organisation) Not Found

**Problem:**
```
/organisations/invalid-slug/elections/...
```

Organisation `invalid-slug` doesn't exist.

**Diagnosis:**
```bash
php artisan tinker
>>> Organisation::where('slug', 'invalid-slug')->exists()
false
```

**Fix:**
Use valid slug:
```
/organisations/namaste-nepal-gmbh/elections/...
```

---

#### 2. Child Model (Election) Not Found

**Problem:**
```
/organisations/namaste-nepal-gmbh/elections/invalid-slug
```

Election `invalid-slug` doesn't exist for that organisation.

**Diagnosis:**
```bash
php artisan tinker
>>> $org = Organisation::where('slug', 'namaste-nepal-gmbh')->first()
>>> $org->elections()->where('slug', 'invalid-slug')->exists()
false
```

**Fix:**
Use valid election slug:
```
/organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6
```

---

### Debug Checklist for 500

- [ ] Check logs: `tail storage/logs/laravel.log`
- [ ] Look for: `ModelNotFoundException`
- [ ] Verify organisation exists: `Organisation::where('slug', ...)->exists()`
- [ ] Verify election exists: `Election::where('slug', ...)->exists()`
- [ ] Verify election belongs to org: `$election->organisation_id === $org->id`

---

## 🔧 Debugging Workflow

### Step 1: Identify Error Type

```bash
# Test the URL
curl -i http://localhost:8000/organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/positions

# Check response code:
# 404 → Go to "404 Debug Checklist"
# 403 → Go to "403 Debug Checklist"
# 500 → Go to "500 Debug Checklist"
```

### Step 2: Check Route Definition

```bash
# List all routes
php artisan route:list --name=organisations.elections.positions

# Should show route with proper middleware
```

### Step 3: Verify Route Cache (if applicable)

```bash
# Check if cache exists
ls -la bootstrap/cache/routes-v7.php

# If changed, restart server:
php artisan route:cache
# Kill server (Ctrl+C)
php artisan serve
```

### Step 4: Test Binding in Isolation

```bash
php artisan tinker

# Can we resolve the parent?
$org = Organisation::where('slug', 'namaste-nepal-gmbh')->first()
# Should return Organisation instance

# Can we resolve the child through parent?
$org->elections()->where('slug', 'namaste-lk0dziy6')->first()
# Should return Election instance

# Do they match?
$org->id === $election->organisation_id
# Should be true
```

### Step 5: Add Logging to Narrow Down

Add to relevant location (binding, middleware, policy):

```php
\Log::debug('DEBUG POINT', [
    'step' => 'binding_resolution',
    'organisation_slug' => 'namaste-nepal-gmbh',
    'organisation_found' => $org ? true : false,
    'organisation_id' => $org?->id,
    'election_slug' => 'namaste-lk0dziy6',
    'election_found' => $election ? true : false,
    'election_organisation_id' => $election?->organisation_id,
]);
```

Then check logs:
```bash
tail -100 storage/logs/laravel.log | grep "DEBUG POINT"
```

### Step 6: Isolate with Minimal Route

Create temporary debug route to test binding:

```php
// In web.php (temporary)
Route::get('/test-binding/{organisation:slug}', function(Organisation $org) {
    return response()->json([
        'organisation_id' => $org->id,
        'organisation_slug' => $org->slug,
    ]);
});
```

Test:
```bash
curl http://localhost:8000/test-binding/namaste-nepal-gmbh
# Should return JSON with organisation data
```

---

## 📚 Key Concepts

### scopeBindings()

**What it does:**
```php
Route::prefix('/organisations/{organisation:slug}')
    ->group(function () {
        
        Route::prefix('/elections/{election}')
            ->scopeBindings()  // ← Here
            ->group(function () {
                // All routes in this group...
            });
    });
```

**Laravel translates to:**
```php
// Instead of:
Election::where('slug', $value)->first()

// It becomes:
Organisation::where('slug', $org_slug)
    ->elections()  // Uses relationship
    ->where('slug', $election_slug)
    ->first()
```

**Benefits:**
- ✅ Automatic multi-tenant isolation
- ✅ Enforces aggregate boundaries
- ✅ No manual session/context needed during binding
- ✅ DDD-compliant architecture

---

### BelongsToTenant Global Scope

**What it does:**
```php
class Election extends Model {
    use BelongsToTenant;  // Auto-filters by tenant
}
```

**Automatic filtering:**
```php
// Any query becomes:
Election::where('organisation_id', session('current_organisation_id'))
    ->where(...)
```

**Risk:**
- If session is empty (before middleware runs), all elections filtered out → 404

**Safe usage:**
```php
// During route binding, use withoutGlobalScopes():
Election::withoutGlobalScopes()->where('slug', $value)->first()

// In controllers (after middleware), scope is safe:
Election::where('id', $id)->first()  // Auto-scoped by session
```

---

### Aggregate Boundaries

**Design:**
```
Organisation (Aggregate Root)
  └─ Elections (Child entities)
      └─ Posts (Grandchild entities)
```

**Routing reflects design:**
```
/organisations/{organisation}        ← Load aggregate root
  /elections/{election}              ← Load through relationship
    /posts/{post}                    ← Load through nested relationship
```

**Why this matters:**
- Elections don't exist independently
- Every operation must go through Organisation
- Enforced by `scopeBindings()`
- DDD principle: "Always access child through parent"

---

## 📊 Common Patterns

### Pattern 1: Read-Only Nested Resource

```php
Route::prefix('/organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        
        Route::prefix('/elections/{election}')
            ->scopeBindings()
            ->group(function () {
                
                // Read-only: accessible in any phase
                Route::get('/positions', [Controller::class, 'getPositions']);
                
            });
    });
```

**Characteristics:**
- No authorization checks (anyone in org can view)
- Works in all election states

---

### Pattern 2: State-Gated Action

```php
Route::prefix('/organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        
        Route::prefix('/elections/{election}')
            ->scopeBindings()
            ->group(function () {
                
                // Only in administration phase
                Route::middleware('election.state:manage_posts')
                    ->post('/posts', [Controller::class, 'createPost'])
                    ->can('managePosts', 'election');
                
            });
    });
```

**Characteristics:**
- State middleware checks election phase
- Policy checks user role
- Only accessible when both conditions met

---

## 🚨 Common Mistakes

### ❌ Mistake 1: Global Election Query in Controller

```php
// BAD
public function show(Election $election) {
    // $election might be from different organisation!
    return view('election.show', ['election' => $election]);
}
```

**Why it fails:**
- Route binding is unscoped
- Controller receives wrong organisation's election

**Fix:**
```php
// GOOD
public function show(Organisation $organisation, Election $election) {
    // $election is guaranteed to belong to $organisation (via scopeBindings)
    return view('election.show', ['election' => $election]);
}
```

---

### ❌ Mistake 2: Using Global Scope During Binding

```php
// BAD
Route::bind('election', function($value) {
    return Election::where('slug', $value)->first();
    // Global scope filters → returns null if session empty
});
```

**Fix:**
```php
// GOOD
Route::bind('election', function($value) {
    return Election::withoutGlobalScopes()
        ->where('slug', $value)
        ->first();
});
```

Or better yet, **remove the binding entirely** and use `scopeBindings()`.

---

### ❌ Mistake 3: Forgetting Server Restart

```bash
php artisan route:cache
# Still getting 404

# ❌ WRONG: Running same server process
# ✅ CORRECT:
php artisan serve  # Restart server
```

---

## 📝 Logging Strategy

### Location 1: Route Binding

```php
// RouteServiceProvider.php
Route::bind('election', function($value) {
    $election = Election::where('slug', $value)->first();
    
    if (!$election) {
        \Log::error('Election binding failed', ['slug' => $value]);
        abort(404);
    }
    
    \Log::info('Election bound', ['slug' => $value, 'id' => $election->id]);
    return $election;
});
```

### Location 2: Middleware

```php
// EnsureOrganisationMember.php
\Log::channel('voting_audit')->info('organisation context validated', [
    'user_id' => $user->id,
    'organisation_id' => $organisation->id,
    'path' => $request->path(),
]);
```

### Location 3: Policy

```php
// ElectionPolicy.php
public function manageSettings(User $user, Election $election): bool {
    $allowed = /* ... check logic ... */;
    
    \Log::debug('POLICY: manageSettings', [
        'user_id' => $user->id,
        'election_id' => $election->id,
        'allowed' => $allowed,
    ]);
    
    return $allowed;
}
```

### Review Logs

```bash
# All logs
tail -100 storage/logs/laravel.log

# Specific component
tail -100 storage/logs/laravel.log | grep "manageSettings"

# Real-time
tail -f storage/logs/laravel.log
```

---

## ✅ Health Check

Run this to verify system is healthy:

```bash
php artisan tinker

# 1. Routes exist
>> Route::has('organisations.elections.positions')
true

# 2. Data exists
>> Organisation::where('slug', 'namaste-nepal-gmbh')->exists()
true
>> Election::where('slug', 'namaste-lk0dziy6')->exists()
true

# 3. Relationship works
>> $org = Organisation::where('slug', 'namaste-nepal-gmbh')->first()
>> $org->elections()->where('slug', 'namaste-lk0dziy6')->exists()
true

# 4. Scoped binding works
>> $org->elections()->where('slug', 'namaste-lk0dziy6')->first()
# Returns Election instance

# 5. User is member
>> $user = User::first()
>> $user->organisationRoles()->where('organisation_id', $org->id)->exists()
true

# 6. Policy passes
>> $user->can('manageSettings', $election)
true (or false, depending on permissions)
```

All `true`? ✅ System is healthy.

---

## 📞 When to Escalate

### Escalate if:
1. ✅ All debug checks pass but issue persists
2. ✅ Error is in framework code (Laravel internals)
3. ✅ Multiple unrelated routes failing
4. ✅ Issue reproduces inconsistently (race condition?)

### Don't escalate for:
- ❌ Missing `scopeBindings()` (too common, easily fixed)
- ❌ Server not restarted (user error)
- ❌ Data doesn't exist (check database)

---

## 🔍 Related Documentation

- [Laravel Route Model Binding](https://laravel.com/docs/11.x/routing#implicit-model-binding)
- [Laravel Scoped Bindings](https://laravel.com/docs/11.x/routing#implicit-binding-scoping)
- [Multi-Tenant Architecture](../ARCHITECTURE.md)
- [DDD in Laravel](../DDD_PATTERNS.md)

---

**Last Updated:** 2026-04-27  
**Maintainer:** Senior Architect  
**Questions?** Check the logs first. Logs are the source of truth.
