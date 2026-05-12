# organisation Creation — Debug Guide

**Status:** Complete
**Last Updated:** 2026-05-12
**Coverage:** Organisation creation flow, caching, tenant context, role assignment

---

## 📚 Quick Links

| Issue | Guide |
|-------|-------|
| "No verification code found" | [Cache Staleness](#-scenario-no-verification-code-found) |
| 403 after org creation | [Missing UserOrganisationRole](#-scenario-missing-userorganisationrole) |
| Wrong organisation context | [Stale TenantContext Cache](#-scenario-stale-tenantcontext-cache) |
| 500 on store | [Validation or DB constraint](#-500-errors-on-store) |

---

## 🚨 Quick Diagnosis

### User created organisation but gets 403 everywhere

```bash
# 1. Does the role pivot exist?
php artisan tinker
>>> $user = User::find($userId);
>>> $user->organisationRoles()->where('organisation_id', $orgId)->exists()
=> false   # ← PROBLEM: Missing UserOrganisationRole
```

**Fix:** `UserOrganisationRole::create(['user_id' => $userId, 'organisation_id' => $orgId, 'role' => 'voter'])`

---

### User switched orgs but middleware uses old org

```bash
# 2. Is the cache stale?
>>> Cache::get("user.{$userId}.organisation_id")
=> "old-org-id"   # ← PROBLEM: Should be the new org ID

# Verify current assignment:
>>> $user->organisation_id
=> "new-org-id"
```

**Fix:** Add `Cache::forget("user.{$userId}.organisation_id")` after org creation/switch.

---

### 500 on POST /organisations

```bash
# 3. Check the logs
tail -100 storage/logs/laravel.log | grep -A 5 "ERROR\|Exception"

# 4. Validate the request manually
php artisan tinker
>>> app(\App\Http\Requests\StoreOrganizationRequest::class)->rules()
# Check each field against the rules
```

---

## 🎯 Root Causes

### 1. Cache Staleness (Most Common)

**Symptom:** User creates organisation, gets redirected, sees "No verification code found" or wrong org data.

**Root Cause:** `Cache::put()` in middleware stores the user's `organisation_id` after login. When `OrganisationController::store()` updates `user.organisation_id`, the cache still holds the old value.

```text
Login → Cache::put("user.{id}.organisation_id", oldOrgId)
                                                ↓
User creates new organisation → DB updated to newOrgId
                                                ↓
Next request → Cache::get("user.{id}.organisation_id") → returns oldOrgId ❌
```

**Fix location:** `OrganisationController::store()`:

```php
// After successful creation:
$user->organisation_id = $organisation->id;
$user->save();

// ✅ CRITICAL: Clear the cached org ID
Cache::forget("user.{$user->id}.organisation_id");
```

---

### 2. Missing UserOrganisationRole

**Symptom:** 403 Forbidden on pages that check organisation membership middleware.

**Root Cause:** The `StoreOrganizationRequest` and `OrganisationController::store()` create the Organisation and update `user.organisation_id` but may not create the required `UserOrganisationRole` pivot record. The tenant middleware checks `UserOrganisationRole::where('user_id', $user->id)->where('organisation_id', $orgId)->exists()` and returns false.

```text
User registered → no UserOrganisationRole for new org
                                                ↓
Middleware: "UserOrganisationRole exists?"
  → false ❌ → 403 Forbidden
```

**Fix location:** `OrganisationController::store()`:

```php
// ✅ CRITICAL: Create the role pivot
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $organisation->id,
    'role' => 'admin',  // or 'voter' depending on flow
]);
```

---

### 3. TenantContext Middleware Cache Pattern

**Symptom:** Intermittent 403s — user sometimes sees correct org, sometimes wrong.

**Root Cause:** The `TenantContext` middleware uses a cache-aside pattern:

```php
$orgId = Cache::remember("user.{$userId}.organisation_id", 3600, function () use ($userId) {
    return User::find($userId)?->organisation_id;
});
```

The 3600-second TTL (1 hour) means stale data persists. If the user creates an organisation, the DB updates but the cache doesn't expire.

**Fix matrix:**

| Scenario | Fix |
|----------|-----|
| Organisation created | `Cache::forget("user.{$userId}.organisation_id")` in controller |
| Organisation switched | `Cache::forget("user.{$userId}.organisation_id")` on switch |
| Organisation deleted | `Cache::forget("user.{$userId}.organisation_id")` on delete |
| User impersonation | `Cache::forget("user.{$targetId}.organisation_id")` after impersonation |

---

### 4. UserFactory Missing Auto-Creation

**Symptom:** Tests fail with 403 when creating organisations.

**Root Cause:** `UserFactory` doesn't auto-create the `UserOrganisationRole` after user creation. Tests create users without roles, then organisation creation doesn't add the role.

**Fix location:** `database/factories/UserFactory.php`:

```php
public function configure(): static
{
    return $this->afterCreating(function (User $user) {
        UserOrganisationRole::firstOrCreate(
            [
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id,
            ],
            ['role' => 'voter']
        );
    });
}
```

---

## 🔧 Essential Tinker Tests

Run these after org creation to verify system health:

```php
// 1. Organisation exists
$org = Organisation::find($orgId);
dump($org?->name);
// => "My Organisation"

// 2. User's org_id matches
$user = User::find($userId);
dump($user->organisation_id === $orgId);
// => true

// 3. Role pivot exists
$hasRole = UserOrganisationRole::where('user_id', $userId)
    ->where('organisation_id', $orgId)
    ->exists();
dump($hasRole);
// => true

// 4. Cache is fresh
dump(Cache::get("user.{$userId}.organisation_id"));
// => $orgId (not a stale value)

// 5. Can access org
\Log::debug('Org creation debug', [
    'user_id' => $userId,
    'org_id' => $orgId,
    'cache_value' => Cache::get("user.{$userId}.organisation_id"),
    'has_role' => $hasRole,
]);
```

All five pass ✅ = System healthy

---

## 🔍 Decision Tree

```
User gets error after creating organisation
│
├─ Is it a 403?
│  ├─ YES → Check UserOrganisationRole exists
│  │        ├─ Missing → Create the role pivot
│  │        └─ Exists  → Check middleware cache
│  │
├─ Is it "No verification code found"?
│  ├─ YES → Cache staleness issue
│  │        └─ Add Cache::forget() after org creation
│  │
├─ Is it a 500?
│  ├─ YES → Check validation rules
│  │        ├─ Field too long? → Check DB column size
│  │        ├─ Missing field? → Check FormRequest rules
│  │        └─ DB constraint? → Check unique indexes
│  │
└─ Is it a 302 redirect loop?
     └─ Check middleware conditions
```

---

## 📝 Logging Strategy

### Enable Debug Logging During Org Creation

Add to `OrganisationController::store()`:

```php
\Log::debug('Organisation creation flow', [
    'user_id' => auth()->id(),
    'org_name' => $request->get('name'),
    'org_id' => $organisation->id,
    'cache_before' => Cache::get("user." . auth()->id() . ".organisation_id"),
]);

// After cache clear:
Cache::forget("user." . auth()->id() . ".organisation_id");

\Log::debug('Cache cleared for user', [
    'user_id' => auth()->id(),
    'cache_after' => Cache::get("user." . auth()->id() . ".organisation_id"),
    'db_value' => auth()->user()->organisation_id,
]);
```

### View Logs

```bash
# Real-time monitoring during org creation
tail -f storage/logs/laravel.log | grep "Organisation creation"

# Find all org creation attempts
grep "Organisation creation" storage/logs/laravel.log

# Last 50 lines
tail -50 storage/logs/laravel.log
```

---

## 🛠️ Commands

### Clear All Caches

```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Verify Database State

```bash
php artisan tinker
>>> DB::table('organisations')->where('id', $orgId)->first();
>>> DB::table('user_organisation_roles')->where('user_id', $userId)->get();
```

### Reset Organisation for Testing

```bash
php artisan tinker
>>> $user = User::find($userId);
>>> $org = Organisation::find($orgId);
>>> $user->organisation_id = null;
>>> $user->save();
>>> Cache::forget("user.{$userId}.organisation_id");
# User is now org-less — can re-test creation flow
```

---

## ✅ Verification Checklist

After fixing any org creation issue:

- [ ] `POST /organisations` returns 201/302
- [ ] User redirected to org dashboard
- [ ] No 403 on subsequent requests
- [ ] `UserOrganisationRole` record exists: `php artisan tinker → ...exists() → true`
- [ ] Cache fresh: `Cache::get("user.{id}.organisation_id")` matches DB
- [ ] Can access org resources (elections, settings, etc.)
- [ ] Tests pass: `php artisan test --filter Organisation`
- [ ] Logs show no errors

---

## 📂 Reference Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/OrganisationController.php` | Org creation endpoint |
| `app/Http/Requests/StoreOrganizationRequest.php` | Validation rules |
| `app/Http/Middleware/TenantContext.php` | Cache-aside middleware |
| `app/Models/UserOrganisationRole.php` | Role pivot model |
| `database/factories/UserFactory.php` | Test factory |
| `routes/organisations.php` | Route definitions |
| `storage/logs/laravel.log` | Application logs |

---

## 📈 Common Error Patterns

| Error | Pattern | Fix |
|-------|---------|-----|
| 403 after org creation | Missing role pivot | Create `UserOrganisationRole` |
| "No verification code" | Stale org_id cache | `Cache::forget()` after update |
| 500 on store() | DB constraint violation | Check unique indexes, field lengths |
| Wrong org displayed | 3600s cache TTL | `Cache::forget()` on org switch |
| Tests fail intermittently | Factory misses role | Add `afterCreating` to UserFactory |
| Route model binding 404 | Cached route with old data | `route:clear && route:cache` |

---

## 🚀 Prevention Rules

1. **Cache clear after every org_id mutation** — Create, update, delete, switch
2. **Role pivot on every org creation** — Don't assume it exists from registration
3. **Factory creates roles for tests** — Otherwise tests pass in isolation but fail in integration
4. **Log the full flow** — user_id, org_id, cache state, role state at every step

---

**Version:** 1.0
**Maintained By:** Senior Architect
**Last Updated:** 2026-05-12
**Status:** Production Ready ✅

Remember: **80% of org creation bugs are stale cache + missing role pivot.** Check those first.
