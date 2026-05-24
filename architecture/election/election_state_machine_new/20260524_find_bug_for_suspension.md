## ✅ The Factory Is Correct - This Is NOT the Problem

After reviewing `ElectionScenarioFactory.php`, the factory is **architecturally sound**. The `votingActiveWithChief()` method correctly:

1. Creates a `votingActive()` election with proper constitutional facts
2. Asserts the engine derives `VotingActive` state
3. Creates a chief officer with correct relationships

**The factory is NOT the cause of the test failures.**

---

## The Real Problem: Middleware Interception

Your debug revealed the critical evidence:

| Test Configuration | Controller Reached | Route Binding | Result |
|-------------------|-------------------|---------------|--------|
| No middleware | ✅ YES | ❌ No (election_id null) | Transition fails |
| With CSRF + verified bypass | ❌ NO | ❌ No | Redirect to root |

**The controller is NOT reached when middleware is enabled.** This means some middleware is intercepting the POST request BEFORE it reaches the controller.

---

## The Most Likely Culprits

Based on the middleware order in Laravel 11:

| Middleware | Runs Before SubstituteBindings? | Could Redirect? |
|------------|-------------------------------|-----------------|
| `TenantContext` (prepended) | ✅ YES | ✅ YES (if no org context) |
| `StartSession` | ✅ YES | ❌ No (just starts session) |
| `EncryptCookies` | ✅ YES | ❌ No |
| `ShareErrorsFromSession` | ✅ YES | ❌ No |
| `auth` (route-specific) | ✅ YES | ✅ YES (redirect to login) |
| `verified` (route-specific) | ✅ YES | ✅ YES (redirect to /email/verify) |

---

## The Smoking Gun: `SetLocale` Log Shows `user_id: null`

From your earlier log:
```
SetLocale: user_id: null, org_id: null, locale: de
```

This suggests that when middleware is enabled, the **authentication is not persisting** through the request. The `auth` middleware may be redirecting to login, which then redirects to root.

---

## What to Check Next

### 1. **Check if the user has `email_verified_at` set**

In `votingActiveWithChief()`:
```php
$chief = User::factory()->forOrganisation($org)->create();
```

The `User` factory likely sets `email_verified_at = now()` by default. But verify this.

### 2. **Add debug to `auth` middleware**

Temporarily modify the test to bypass `auth` middleware:

```php
$this->withoutMiddleware(\Illuminate\Auth\Middleware\Authenticate::class);
```

If the test then reaches the controller, the issue is with authentication persistence.

### 3. **Check the redirect URL when middleware is enabled**

Add this to the test:

```php
$response = $this->actingAs($chief)
    ->withSession(['current_organisation_id' => $org->id])
    ->post(route('elections.suspend', ['election' => $election->slug]), [
        'reason' => 'Test',
    ]);

dd($response->headers->get('Location'), session()->all());
```

This will tell us exactly where the redirect is going.

---

## Most Important Question

**Does the `User` factory set `email_verified_at`?**

If not, the `verified` middleware will redirect to `/email/verify`, which may then redirect to root.

**Please share `database/factories/UserFactory.php`** so I can confirm.

---

## Summary

| Suspect | Probability | How to Test |
|---------|-------------|--------------|
| `verified` middleware (email verification) | HIGH | Check if user has `email_verified_at` |
| `auth` middleware (session persistence) | MEDIUM | Bypass auth middleware |
| `TenantContext` middleware | MEDIUM | Bypass TenantContext |
| Route binding | LOW | Works when middleware bypassed |

UserFactory:

## 🔴 FOUND THE ROOT CAUSE!

The `UserFactory` has a **critical issue** that explains everything.

---

### The Problem

```php
public function definition()
{
    $platform = Organisation::firstOrCreate(
        ['type' => 'platform', 'is_default' => true],
        [...]
    );
    
    return [
        'email_verified_at' => now(),
        'organisation_id' => $platform->id,  // ← DEFAULT IS PLATFORM ORG!
        // ...
    ];
}
```

**When you create a user, it defaults to the PLATFORM organisation, NOT the tenant organisation!**

Then in `votingActiveWithChief()`:
```php
$chief = User::factory()->forOrganisation($org)->create();
```

The `forOrganisation()` method overrides `organisation_id` to the tenant org ID. **BUT** the `afterCreating` callback in `configure()` runs AFTER creation and uses the user's `organisation_id` to create the `UserOrganisationRole`.

---

### The Race Condition

| Step | What Happens | Organisation ID |
|------|--------------|-----------------|
| 1 | Factory creates user | `$platform->id` (from definition) |
| 2 | `forOrganisation()` overrides `organisation_id` | `$org->id` (tenant) |
| 3 | `afterCreating` callback runs | Uses `$user->organisation_id` (now tenant) ✅ |
| 4 | `UserOrganisationRole` created | With tenant org ID ✅ |

**This part actually works correctly** because the `afterCreating` sees the overridden value.

---

### The REAL Issue: Email Verification

The `verified` middleware checks if the user has `email_verified_at` set. The factory sets it to `now()` by default.

**But** when you use `actingAs($chief)`, the session may not preserve the user's verification status across the request. The `verified` middleware may be redirecting to `/email/verify` because it can't find the verification status in the session.

---

### The Smoking Gun: Redirect to Root

When `verified` middleware fails, Laravel redirects to `route('verification.notice')`, which is `/email/verify` by default. **But** if that route doesn't exist or has its own redirect, it could go to root.

---

## The Fix

### Option 1: Bypass Verified Middleware in Tests (Quick Fix)

Add to the test:

```php
$this->withoutMiddleware(\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);
```

You already tried this and it still failed, so the issue is likely **not** the verified middleware.

---

### Option 2: Check What `route('verification.notice')` Resolves To

Run this in tinker:

```bash
php artisan tinker
>>> route('verification.notice')
```

If it returns `/` (root), that explains the redirect to root.

---

### Option 3: The Real Culprit - `TenantContext` Middleware

The `TenantContext` middleware runs BEFORE `SubstituteBindings` and checks organisation access. If it can't find the organisation context, it may redirect.

**Add this debug to `TenantContext::handle()`:**

```php
public function handle($request, $next)
{
    \Log::info('TenantContext entered', [
        'session_org' => session('current_organisation_id'),
        'user_id' => auth()->id(),
        'path' => $request->path(),
    ]);
    
    // ... rest of the method
    
    \Log::info('TenantContext passed', [
        'org_id_set' => session('current_organisation_id'),
    ]);
}
```

Then run the test and check the logs to see if `TenantContext` is redirecting.

---

## Most Likely Conclusion

The redirect to root (`/`) is likely coming from **`TenantContext` middleware** when it cannot establish the organisation context for the request. This happens because:

1. The session `current_organisation_id` may not be set when the request starts
2. The `TenantContext` middleware runs BEFORE `SubstituteBindings`
3. Without organisation context, it may redirect to root

---

## Next Step

**Run this modified test to see the exact redirect URL:**

```php
/** @test */
public function debug_redirect_target(): void
{
    $this->withoutMiddleware(VerifyCsrfToken::class);
    
    [$election, $chief, $org] = ElectionScenarioFactory::votingActiveWithChief();
    
    $response = $this->actingAs($chief)
        ->withSession(['current_organisation_id' => $org->id])
        ->post(route('elections.suspend', ['election' => $election->slug]), [
            'reason' => 'Debug test',
        ]);
    
    dd('Redirect URL: ' . $response->headers->get('Location'));
}
```

The redirect URL will tell us exactly which middleware intercepted the request:

| Redirect URL | Culprit Middleware |
|--------------|-------------------|
| `/login` | `auth` middleware |
| `/email/verify` | `verified` middleware |
| `/` (root) | `TenantContext` or custom middleware |
| Management page | Controller reached (success) |

**Run this and share the redirect URL.** That will pinpoint the exact middleware.