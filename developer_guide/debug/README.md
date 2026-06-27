# Debug Guide: Public Digit Multi-Tenant Platform

**Status:** Complete  
**Last Updated:** 2026-04-27  
**Coverage:** Routing, Authorization, Multi-Tenancy, Common Issues

---

## 📚 Documentation

### Quick Start
**New to debugging?** Start here.
- **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** — Decision trees, common issues, one-liners
  - Use when: Something breaks and you need a fast answer
  - Time: 2-5 minutes
  - Contains: Status code decisions, checklists, common fixes

### Deep Dives
**Need comprehensive understanding?** Pick your topic.

#### [MULTI_TENANT_ROUTING_DEBUG.md](MULTI_TENANT_ROUTING_DEBUG.md)
- **Focus:** 404 errors, route binding, multi-tenancy
- **Use when:** Routes aren't resolving, models not found, binding failures
- **Contains:** 
  - Request lifecycle diagram
  - 404 root causes (4 categories)
  - Route binding failures
  - Global scope filtering issues
  - Complete debugging workflow
  - Architecture concepts (scopeBindings, aggregate boundaries)

#### [AUTHORIZATION_POLICY_DEBUG.md](AUTHORIZATION_POLICY_DEBUG.md)
- **Focus:** 403 forbidden errors, policies, roles
- **Use when:** Access denied, policy returns wrong value, permission mismatches
- **Contains:**
  - Authorization stack diagram
  - 403 root causes (5 categories)
  - Session context issues
  - Role/permission verification
  - Policy testing in tinker
  - Permission matrix
  - Common authorization bugs

#### [CASE_STUDY_SCOPE_BINDING_404.md](CASE_STUDY_SCOPE_BINDING_404.md)
- **Focus:** The exact issue that was solved (2026-04-27)
- **Use when:** You hit the exact same "all routes 404" issue
- **Contains:**
  - Full incident timeline
  - Root cause analysis
  - Solution explanation
  - Before/after architecture
  - Prevention rules
  - DDD alignment discussion

---

## 🚨 Quick Diagnosis

### I'm seeing 404 errors

```bash
# Is the route in the registry?
php artisan route:list | grep your-route

# YES → Go to MULTI_TENANT_ROUTING_DEBUG.md section "404 Decision Tree"
# NO → Define the route first
```

### I'm seeing 403 forbidden

```bash
# Does the route have ->can()?
grep -B 2 "->can(" routes/organisations.php | grep your-route

# YES → Go to AUTHORIZATION_POLICY_DEBUG.md section "403 Decision Tree"
# NO → It's not a policy issue, check middleware
```

### I'm seeing 500 errors

```bash
# Check the logs
tail -100 storage/logs/laravel.log

# Look for exception class name
# Copy the error → Search in corresponding debug guide
```

### Everything works in tinker but fails in browser

→ See [AUTHORIZATION_POLICY_DEBUG.md - "True in Tinker, False in Browser"](AUTHORIZATION_POLICY_DEBUG.md#-issue-policy-returns-true-in-tinker-false-in-browser)

---

## 🎯 Common Issues & Quick Fixes

| Issue | Status Code | Root Cause | Fix |
|-------|------------|-----------|-----|
| All routes under `/organisations/{org}/elections/{election}/*` return 404 | 404 | Missing `->scopeBindings()` | Add `->scopeBindings()` to route group |
| Route works after restart, breaks after deployment | 404 | Route cache not reloaded | Run `php artisan serve` after `route:cache` |
| User can see some pages but not others | 403 | Missing ElectionOfficer record | Create record: `ElectionOfficer::create(...)` |
| Authorization fails in browser but works in tinker | 403 | Session context missing during policy check | Use relationships in policy instead of session |
| Policy method receives wrong model | 404/403 | No `->scopeBindings()` on parent routes | Add `->scopeBindings()` to enforce relationship |

---

## 📊 Architecture Overview

### Request Flow

```
Browser Request
    ↓
[1] Routes Matched
    ├─ Implicit binding via scopeBindings()
    └─ Parent model loaded first
    ↓
[2] Middleware Stack
    ├─ auth
    ├─ verified
    └─ ensure.organisation (sets session)
    ↓
[3] Model Binding (continued)
    └─ Child resolved through parent relationship
    ↓
[4] Authorization Gates
    ├─ ->can('action', model)
    └─ Policy method executes
    ↓
[5] Response
```

### Multi-Tenant Model

```
Organisation (Aggregate Root)
├─ id: UUID
├─ slug: "namaste-nepal-gmbh"
└─ hasMany: Elections
    └─ Election (Child Entity)
       ├─ id: UUID
       ├─ slug: "namaste-lk0dziy6"
       ├─ organisation_id: UUID (FK)
       └─ state: "draft|administration|nomination|voting|..."
           └─ Statemachine controls transitions
               └─ Policies enforce role requirements
```

### Route Pattern

```php
Route::prefix('/organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        
        Route::prefix('/elections/{election}')
            ->scopeBindings()  // ← CRITICAL: Enforces org ownership
            ->group(function () {
                
                Route::get('/positions', ...)              // No auth
                Route::post('/posts', ...)                 // ->can('manage')
                    ->middleware('election.state:manage_posts');
                
            });
    });
```

**Full URL:** `/organisations/namaste-nepal-gmbh/elections/namaste-lk0dziy6/positions`

---

## 🔧 Essential Tinker Tests

Run these to verify system health:

```bash
php artisan tinker

# 1. Does parent exist?
>>> Organisation::where('slug', 'namaste-nepal-gmbh')->first()
=> Organisation instance (not null)

# 2. Does child exist?
>>> Election::where('slug', 'namaste-lk0dziy6')->first()
=> Election instance (not null)

# 3. Do they relate?
>>> $org->elections()->where('slug', 'namaste-lk0dziy6')->first()
=> Election instance (not null)

# 4. Is user authenticated?
>>> auth()->check()
=> true

# 5. Is user member of org?
>>> auth()->user()->organisationRoles()
    ->where('organisation_id', $org->id)
    ->exists()
=> true

# 6. Can user perform action?
>>> auth()->user()->can('manageSettings', $election)
=> true (or false, depending on role)

# All ✅ = System is healthy
```

---

## 📝 Logging Strategy

### Enable Debug Logging

Add to controller/policy:
```php
\Log::debug('Debug point name', [
    'key1' => 'value1',
    'key2' => 'value2',
]);
```

### View Logs

```bash
# Real-time
tail -f storage/logs/laravel.log

# Last 100 lines
tail -100 storage/logs/laravel.log

# Search for specific term
tail -200 storage/logs/laravel.log | grep "manageSettings"

# View errors only
tail storage/logs/laravel.log | grep "ERROR\|Exception"
```

---

## 🛠️ Maintenance Commands

### After Modifying Routes

```bash
# Always do this sequence:
php artisan route:clear           # Clear old cache
php artisan route:cache           # Rebuild cache
php artisan config:cache          # Rebuild config
# THEN restart server:
php artisan serve                 # New terminal window
```

### Full Cache Clear

```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Verify System

```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getDatabaseName()

# List all routes
php artisan route:list

# Simulate request (useful for testing)
php artisan tinker
>>> $response = app('router')->dispatch(request()->create('GET', '/your-url'))
```

---

## 🚨 Error Checklist

### For Any Error

1. **Check the status code**
   - 404 → Route/binding issue
   - 403 → Authorization issue
   - 500 → Code/exception issue
   - 302 → Redirect (check headers)

2. **Check the logs**
   - `tail storage/logs/laravel.log`
   - Look for exception class and file/line

3. **Check tinker**
   - Can you reproduce the conditions?
   - Does the query return what you expect?

4. **Check the route**
   - Does route exist? `route:list`
   - Does it have the right middleware?
   - Does it have `->scopeBindings()`?

5. **Check the data**
   - Does the model exist in database?
   - Does it have the right foreign key?
   - Is it soft-deleted?

---

## 📚 Reference Files

### Code Location

| File | Purpose |
|------|---------|
| `routes/organisations.php` | Route definitions with middleware/policies |
| `routes/web.php` | Global routes, route model binding |
| `app/Providers/RouteServiceProvider.php` | Route binding registration |
| `app/Policies/ElectionPolicy.php` | Authorization policies |
| `app/Http/Middleware/EnsureOrganisationMember.php` | Org membership validation |
| `app/Http/Middleware/EnsureElectionState.php` | Election state validation |
| `app/Models/Election.php` | Election model with relationships |
| `app/Models/Organisation.php` | Organisation model with relationships |

### Configuration

| File | Purpose |
|------|---------|
| `.env` | Database credentials, debug mode |
| `config/app.php` | App configuration |
| `config/database.php` | Database setup |
| `config/logging.php` | Log configuration |

---

## 🔍 When to Escalate

### You can likely fix it:
- ❌ Missing `->scopeBindings()` 
- ❌ User not member of organisation
- ❌ ElectionOfficer record missing
- ❌ Wrong role/status in ElectionOfficer
- ❌ Route not defined
- ❌ Model doesn't exist in database

### Escalate to senior architect:
- ✅ Framework internals error
- ✅ Database schema corruption
- ✅ Multiple unrelated failures
- ✅ Intermittent race condition
- ✅ Performance degradation

---

## 📞 Getting Help

### Self-Service
1. Check QUICK_REFERENCE.md first
2. Read relevant deep-dive guide
3. Check logs for specific error
4. Test in tinker
5. Review case study if applicable

### Before Escalating
- [ ] Checked logs
- [ ] Tested in tinker
- [ ] Restarted dev server
- [ ] Cleared cache
- [ ] Verified data exists

---

## 📈 Document Index

```
developer_guide/debug/
├─ README.md (this file)
├─ QUICK_REFERENCE.md
│  └─ For: Fast diagnosis, decision trees
├─ MULTI_TENANT_ROUTING_DEBUG.md
│  └─ For: 404 errors, route binding
├─ AUTHORIZATION_POLICY_DEBUG.md
│  └─ For: 403 errors, policies
└─ CASE_STUDY_SCOPE_BINDING_404.md
   └─ For: Understanding the scope binding fix
```

---

## ✅ Verification Checklist

After fixing any issue, verify:

- [ ] Tests pass: `php artisan test`
- [ ] Routes work: `php artisan route:list`
- [ ] No warnings in logs
- [ ] Tinker tests pass (see above)
- [ ] Browser works (test in incognito to avoid cache)
- [ ] Related routes still work

---

## 🎓 Key Concepts

### scopeBindings()
- Tells Laravel to resolve child through parent relationship
- Enforces aggregate boundaries
- Automatic multi-tenant isolation
- Must be on child route group

### BelongsToTenant
- Global scope that filters by `session('current_organisation_id')`
- Applied automatically to Election, Post, etc.
- Requires middleware to set session
- Can bypass with `withoutGlobalScopes()` when needed

### Aggregate Root (DDD)
- Organisation is the aggregate root
- Elections, Posts, etc. are children
- Always access child through parent
- Enforced by routing with `scopeBindings()`

### Authorization Stack
- Authentication (user exists)
- Validation (middleware checks)
- Authorization (policy returns true/false)
- Response (403 or 200)

---

## 📊 Metrics

As of 2026-04-27:

| Metric | Value |
|--------|-------|
| Total debugging guides | 4 |
| Common issues covered | 15+ |
| Root causes documented | 20+ |
| Fixes provided | 50+ |
| Code examples | 100+ |

---

## 🚀 Next Steps

1. **Bookmark this folder** - You'll need it
2. **Read QUICK_REFERENCE.md** - For fast lookups
3. **Read MULTI_TENANT_ROUTING_DEBUG.md** - Understand the architecture
4. **Keep logs monitoring** - Tail logs while debugging

---

**Version:** 1.0  
**Maintained By:** Senior Architect  
**Last Updated:** 2026-04-27  
**Status:** Production Ready ✅

Remember: **Logs are your best friend.** Always check them first.
