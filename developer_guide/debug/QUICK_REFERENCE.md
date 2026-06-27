# Quick Reference: Debugging Decision Tree

**Use this when:** Something is broken and you need a fast diagnosis.

---

## 🚨 Step 1: What's the HTTP Status Code?

```
Browser → Right-click → Inspect → Network tab

Or: curl -i http://localhost:8000/your-url
```

### If 404

→ Go to [**404 Decision Tree**](#-404-decision-tree)

### If 403

→ Go to [**403 Decision Tree**](#-403-decision-tree)

### If 500

→ Go to [**500 Decision Tree**](#-500-decision-tree)

### If 302 Redirect

→ Check where it redirects:
```bash
curl -i http://localhost:8000/your-url | grep Location
# /login → User not authenticated
# /organisations → Route guard redirect
```

---

## 🔴 404 Decision Tree

**Is the route in `php artisan route:list`?**

```bash
php artisan route:list | grep positions
```

### YES → Route exists in registry

**Did you recently change routes?**

- Yes → Did you restart the dev server?
  - No → `php artisan serve` (restart it)
  - Yes → Go to **"Route Binding Failure"**

- No → Go to **"Route Binding Failure"**

### NO → Route doesn't exist

**Check routes file:**
```bash
grep -n "organisations.elections.positions" routes/organisations.php
```

- Found → Route cache corrupted:
  ```bash
  php artisan route:clear
  php artisan route:cache
  php artisan serve
  ```

- Not found → Route is not defined:
  ```bash
  # Define the route in routes/organisations.php
  Route::get('/positions', [Controller::class, 'positions'])
      ->name('organisations.elections.positions');
  ```

---

## 🟠 Route Binding Failure (404)

**Test route binding in isolation:**

```bash
php artisan tinker

# 1. Does parent exist?
>>> $org = Organisation::where('slug', 'namaste-nepal-gmbh')->first()
>>> $org
# Should return Organisation instance, not null

# 2. Does child exist?
>>> $election = Election::where('slug', 'namaste-lk0dziy6')->first()
>>> $election
# Should return Election instance, not null

# 3. Do they match?
>>> $org->id === $election->organisation_id
true  # Should be true

# 4. Can we access through relationship?
>>> $org->elections()->where('slug', 'namaste-lk0dziy6')->first()
# Should return Election instance
```

**All pass?** → Issue is elsewhere (check middleware, session, policy)

**Some fail?** → Data doesn't exist or is wrong:
- Create missing records
- Fix organisation_id foreign key
- Verify slugs are correct (no typos, case-sensitive)

---

## 🟡 403 Decision Tree

**Is there a `->can()` on this route?**

```bash
grep -B 5 "the-route-name" routes/organisations.php
# Look for: ->can('action', 'election')
```

### YES → Authorization check is happening

**What does the policy return in tinker?**

```bash
php artisan tinker

>>> $user = User::first()
>>> $election = Election::first()
>>> $user->can('manageSettings', $election)
true   # Yes → Session/context mismatch
false  # No  → Real authorization failure
```

**If `true` in tinker but `false` in browser:**

→ Go to [Authorization Guide](AUTHORIZATION_POLICY_DEBUG.md#-issue-policy-returns-true-in-tinker-false-in-browser)

**If `false` in both:**

→ Go to [Authorization Guide](AUTHORIZATION_POLICY_DEBUG.md#-issue-403-forbidden-on-valid-action)

### NO → It's not a policy issue

**Check what middleware is blocking:**

```bash
grep -B 10 "the-route-name" routes/organisations.php
# Look for: ->middleware('something')
```

Common middleware:
- `auth` → User not logged in
- `verified` → Email not verified
- `election.state:*` → Election in wrong state
- Custom middleware → Check its logic

---

## 🔴 500 Decision Tree

**Check the error message:**

```bash
# In browser: Look at error page
# In logs: 
tail -100 storage/logs/laravel.log
```

### Common 500 Errors

**ModelNotFoundException**
→ Parent or child model doesn't exist
→ Check [404 Decision Tree - Route Binding Failure](#-route-binding-failure-404)

**Call to undefined method**
→ Typo in method name or relationship
→ Check spelling in models and controllers

**Undefined variable**
→ Variable not initialized before use
→ Check controller code

**SQLSTATE error**
→ Database issue
```bash
# Verify database connection
php artisan tinker
>>> \DB::connection()->getDatabaseName()
'your_db_name'

# Verify table exists
>>> \DB::table('elections')->count()
```

---

## ⚡ Common One-Liners

```bash
# Clear everything
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# Rebuild cache
php artisan route:cache && php artisan config:cache

# Restart server
php artisan serve

# Check route exists
php artisan route:list | grep "positions"

# Test binding
php artisan tinker
>>> Organisation::where('slug', 'namaste-nepal-gmbh')->first()
>>> Election::where('slug', 'namaste-lk0dziy6')->first()

# Test policy
>>> User::first()->can('manageSettings', Election::first())

# Watch logs
tail -f storage/logs/laravel.log

# Search logs
tail -100 storage/logs/laravel.log | grep "ERROR\|404\|403"
```

---

## 📊 Status Code Meanings

| Code | Meaning | Common Cause |
|------|---------|--------------|
| 200  | OK | Everything works |
| 302  | Redirect | Middleware redirect (to login, etc.) |
| 403  | Forbidden | Policy denies access |
| 404  | Not Found | Route or model not found |
| 500  | Server Error | Code error, exception |
| 503  | Unavailable | Database down, config error |

---

## 🔍 Debugging Checklists

### Before Starting

- [ ] `php artisan serve` is running
- [ ] Browser is hitting correct URL
- [ ] Database is accessible
- [ ] Laravel logs are being written

### For 404

- [ ] Route exists in code
- [ ] Route is in registry: `route:list`
- [ ] Parent model exists in database
- [ ] Child model exists in database
- [ ] Child belongs to parent: `organisation_id` match
- [ ] `->scopeBindings()` is in route group
- [ ] No conflicting `Route::bind()` in provider
- [ ] Dev server was restarted after route changes

### For 403

- [ ] User is authenticated
- [ ] User is member of organisation
- [ ] ElectionOfficer record exists
- [ ] ElectionOfficer role is correct
- [ ] ElectionOfficer status is 'active'
- [ ] Policy is being called (check logs)
- [ ] Policy receives correct models
- [ ] Policy logic is correct
- [ ] Middleware ran before policy

### For 500

- [ ] Check `storage/logs/laravel.log`
- [ ] Look for exception class
- [ ] Look for file and line number
- [ ] Check that file exists
- [ ] Check for obvious typos or logic errors

---

## 📞 When to Escalate

### Safe to fix yourself
- Missing `->scopeBindings()`
- Route not defined
- User not a member of organisation
- Missing ElectionOfficer record
- Wrong ElectionOfficer role/status

### Safe to escalate
- Framework exceptions
- Database schema issues
- Multiple routes failing
- Intermittent failures
- Framework version compatibility

---

## 📚 Full Guides

For deep dives, see:
- [Multi-Tenant Routing Debug](MULTI_TENANT_ROUTING_DEBUG.md) — Route binding, 404s, scoped bindings
- [Authorization & Policy Debug](AUTHORIZATION_POLICY_DEBUG.md) — 403s, policies, roles

---

**Last Updated:** 2026-04-27  
**Pro Tip:** Always check the logs first. They tell you exactly what went wrong.
