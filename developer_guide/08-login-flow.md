# Login Flow & Post-Authentication Routing

## Overview

The login flow in Public Digit is carefully orchestrated to route authenticated users to the correct dashboard based on their roles and permissions. This guide explains how the login process works, the circular dependency issue that was fixed, and how role-based routing is implemented.

---

## The Complete Login Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER SUBMITS LOGIN                          │
│                    POST /login (email/password)                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                  LARAVEL FORTIFY VALIDATES                      │
│              Checks credentials in users table                  │
│              Creates authenticated session                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                 LoginResponse::toResponse()                     │
│      Delegates routing decision to DashboardResolver            │
│      (This is the KEY ORCHESTRATION POINT)                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│            DashboardResolver::resolve($user)                    │
│  1. Check if first-time user → /dashboard/welcome              │
│  2. Check if multiple roles → /dashboard/roles                 │
│  3. Check if single role → redirect by role                    │
│  4. Fallback legacy roles → backward compatibility             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              REDIRECT RESPONSE SENT TO BROWSER                  │
│         Browser follows redirect to appropriate dashboard       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│          CheckUserRole Middleware Validates Access             │
│  Uses SAME role detection logic as DashboardResolver           │
│  Ensures user has required role for that dashboard             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│               DASHBOARD CONTROLLER RENDERS PAGE                 │
│           User sees their role-appropriate dashboard            │
└─────────────────────────────────────────────────────────────────┘
```

---

## LoginResponse: The Orchestrator

### File: `app/Http/Responses/LoginResponse.php`

The `LoginResponse` class implements Laravel Fortify's `LoginResponse` contract. It's called immediately after successful authentication and is responsible for **deciding where to send the user**.

```php
<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\Election;
use App\Services\DashboardResolver;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Log the login event
        \Log::info('🔐 LoginResponse::toResponse() CALLED', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

        try {
            // Delegate routing decision to DashboardResolver
            $redirect = app(DashboardResolver::class)->resolve($user);

            $targetUrl = $redirect->getTargetUrl();
            \Log::info('✅ LoginResponse: Will redirect to', [
                'user_id' => $user->id,
                'target_url' => $targetUrl,
            ]);

            return $redirect;
        } catch (\Exception $e) {
            \Log::error('❌ LoginResponse: DashboardResolver failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }
}
```

### Why LoginResponse is Important

1. **Single Responsibility**: Only handles the redirect decision after authentication
2. **Delegation Pattern**: Uses DashboardResolver for the actual logic (separation of concerns)
3. **Logging**: Provides insight into where users are being routed
4. **Extensibility**: Can be modified without affecting DashboardResolver

---

## DashboardResolver: The Decision Engine

### File: `app/Services/DashboardResolver.php`

The `DashboardResolver` service determines the correct dashboard for a user based on their roles and permissions.

### Role Detection Priority

```
PRIORITY 1: First-Time Users
├─ No organisation roles
├─ No commission memberships
├─ No voter registrations
└─ → Redirect to /dashboard/welcome

PRIORITY 2: Multi-Role Users
├─ Multiple dashboard roles detected
└─ → Redirect to /dashboard/roles (role selection page)

PRIORITY 3: Single-Role Users
├─ User has exactly one role
└─ → Redirect to role-specific dashboard

PRIORITY 4: Legacy Fallback
├─ Check Spatie permission roles
├─ Check is_committee_member flag
├─ Check is_voter flag
└─ → Redirect to appropriate dashboard
```

### Role Sources (Checked in Order)

The `getDashboardRoles()` method checks these sources to build the user's role list:

```php
private function getDashboardRoles(User $user): array
{
    $roles = [];

    // SOURCE 1: Organisation Roles (NEW SYSTEM)
    // Users who are admins for organisations
    if (DB::table('user_organisation_roles')
        ->where('user_id', $user->id)->exists()) {
        $roles[] = 'admin';
    }

    // SOURCE 2: Commission Memberships (NEW SYSTEM)
    // Users who are part of election commissions
    if (DB::table('election_commission_members')
        ->where('user_id', $user->id)->exists()) {
        $roles[] = 'commission';
    }

    // SOURCE 3: Voter Status (NEW SYSTEM)
    // Users registered to vote
    if ($user->is_voter) {
        $roles[] = 'voter';
    }

    // SOURCE 4: Legacy Spatie Roles (BACKWARD COMPATIBILITY)
    if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
        if (!in_array('admin', $roles)) {
            $roles[] = 'admin';
        }
    }

    // SOURCE 5: Legacy Committee Member (BACKWARD COMPATIBILITY)
    if ($user->is_committee_member ?? false) {
        if (!in_array('commission', $roles)) {
            $roles[] = 'commission';
        }
    }

    return array_unique(array_filter($roles));
}
```

### Routing Examples

**Example 1: Organisation Admin**
```
User has: user_organisation_roles record
getDashboardRoles() returns: ['admin']
Decision: Single role → redirect to route('organisations.show', org)
Result: /organisations/{slug}
```

**Example 2: Commission Member + Voter**
```
User has: election_commission_members record AND is_voter = 1
getDashboardRoles() returns: ['commission', 'voter']
Decision: Multiple roles → redirect to role.selection
Result: /dashboard/roles (user picks which role to use)
```

**Example 3: First-Time User**
```
User has: No roles, no memberships, is_voter = 0
getDashboardRoles() returns: []
Decision: First-time user → redirect to welcome
Result: /dashboard/welcome (onboarding page)
```

---

## The Circular Dependency Problem (FIXED)

### What Was Wrong

Before the fix, there was a **middleware mismatch** that created an infinite redirect loop:

```
❌ BROKEN FLOW:

1. LoginResponse uses DashboardResolver (custom role detection)
   └─ Checks: user_organisation_roles, election_commission_members, is_voter

2. DashboardResolver redirects to: /dashboard/admin
   └─ Says user is an 'admin'

3. Route middleware 'role:admin' is Spatie Permission's middleware
   └─ Checks: Does user have Spatie role 'admin'?
   └─ Looks in: roles table + role_has_permissions

4. User might NOT have Spatie 'admin' role
   └─ Even though they have user_organisation_roles record

5. Spatie middleware rejects request
   └─ Redirects back to /login (or role.selection)

6. Browser is stuck in loop: /login → /dashboard/admin → /login → ...
```

### Why It Happened

The system evolved to have **two different role systems**:

1. **Old System**: Spatie Permission roles in the `roles` table
2. **New System**: Custom tables (`user_organisation_roles`, `election_commission_members`)

The LoginResponse/DashboardResolver used the **new system**, but the routes still used the **old system's middleware**. This mismatch was the source of the infinite loop.

### The Fix

Changed the route middleware from Spatie's generic `'role'` to our custom `'dashboard.role'`:

```php
// BEFORE (BROKEN):
Route::prefix('dashboard/admin')->middleware(['role:admin'])->group(function () {
    // Uses Spatie Permission middleware
    // Checks: Does $user->hasRole('admin')?
});

// AFTER (FIXED):
Route::prefix('dashboard/admin')->middleware(['dashboard.role:admin'])->group(function () {
    // Uses our custom CheckUserRole middleware
    // Checks: Is 'admin' in getDashboardRoles()?
});
```

### Key Files Changed

1. **routes/web.php**
   - Changed 3 route groups (admin, commission, voter)
   - From `middleware(['role:...])` to `middleware(['dashboard.role:...])`

2. **app/Http/Middleware/CheckUserRole.php**
   - Added comprehensive logging
   - Validates roles using DashboardResolver's logic
   - Ensures consistency between decision and validation

3. **app/Services/DashboardResolver.php**
   - Added detailed debug logging
   - Shows which role sources are detected
   - Helps diagnose role detection issues

---

## CheckUserRole Middleware

### File: `app/Http/Middleware/CheckUserRole.php`

This middleware validates that a user has the required role before accessing a dashboard. **Critically, it uses the exact same role detection logic as DashboardResolver.**

```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    $user = $request->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Get roles using SAME logic as DashboardResolver
    $userRoles = $user->getDashboardRoles();
    $hasAccess = false;

    // Check if user has ANY of the required roles
    foreach ($roles as $requiredRole) {
        if (in_array($requiredRole, $userRoles)) {
            $hasAccess = true;
            break;
        }
    }

    // Special case: Legacy committee members
    if (!$hasAccess && in_array('commission', $roles) && $user->is_committee_member) {
        $hasAccess = true;
    }

    if (!$hasAccess) {
        // Redirect to role selection, not login
        return redirect()->route('role.selection')
            ->with('error', 'You do not have access to this dashboard.');
    }

    $request->attributes->set('current_role', $currentRole);
    return $next($request);
}
```

### Why It Works

The middleware validates using `$user->getDashboardRoles()` (from User model), which implements the **same role detection logic** as `DashboardResolver->getDashboardRoles()`. This ensures:

✅ **Consistency**: Decision and validation use the same logic
✅ **No Mismatches**: User won't be rejected after being redirected
✅ **Backward Compatibility**: Supports legacy Spatie roles and flags
✅ **Clear Feedback**: Redirects to role selection instead of login

---

## The User Model's Role Method

### File: `app/Models/User.php`

The `User` model has a `getDashboardRoles()` method that caches the role detection:

```php
public function getDashboardRoles(): array
{
    return Cache::remember(
        "user_{$this->id}_dashboard_roles",
        3600,  // Cache for 1 hour
        function () {
            $roles = [];

            // Check all 5 role sources (same as DashboardResolver)
            $orgRoles = DB::table('user_organisation_roles')
                ->where('user_id', $this->id)
                ->pluck('role')
                ->toArray();
            $roles = array_merge($roles, $orgRoles);

            if ($this->is_committee_member) {
                $roles[] = 'commission';
            }

            if ($this->wantsToVoteInDemo() || $this->wantsToVoteInReal()) {
                $roles[] = 'voter';
            }

            // Legacy Spatie roles...
            return array_unique(array_filter($roles));
        }
    );
}
```

### Caching Strategy

- **TTL**: 1 hour
- **Key**: `user_{user_id}_dashboard_roles`
- **Purpose**: Avoid repeated database queries for role detection
- **Invalidation**: Automatically expires after 1 hour

---

## Logging & Diagnostics

### What Gets Logged

The updated login flow logs events at every step for easy troubleshooting:

**1. LoginResponse logs when toResponse() is called:**
```
[2026-03-01 15:45:30] 🔐 LoginResponse::toResponse() CALLED
user_id: 42
email: user@example.com
timestamp: 2026-03-01 15:45:30
```

**2. DashboardResolver logs role detection:**
```
[2026-03-01 15:45:30] DashboardResolver: Dashboard roles resolved
user_id: 42
dashboard_roles: ["admin", "commission"]
role_count: 2
```

**3. DashboardResolver logs redirect decision:**
```
[2026-03-01 15:45:30] DashboardResolver: Redirect decision
user_id: 42
decision: multiple_roles
destination: role.selection
roles: ["admin", "commission"]
reason: User has 2 dashboard roles
```

**4. CheckUserRole logs validation:**
```
[2026-03-01 15:45:31] 🔐 CheckUserRole Middleware: Processing request
user_id: 42
required_roles: ["admin"]
route: /dashboard/admin

[2026-03-01 15:45:31] 🔐 CheckUserRole: Access GRANTED
user_id: 42
required_roles: ["admin"]
assigned_role: admin
```

### Troubleshooting with Logs

**Problem**: User stuck in redirect loop
**Solution**: Check logs for:
1. What role did DashboardResolver detect?
2. What role did CheckUserRole require?
3. Did they match?

**Problem**: User redirected to wrong dashboard
**Solution**: Check logs for:
1. What was the first-time user check result?
2. What roles were detected?
3. What decision was made?

---

## Testing the Login Flow

### Manual Test Scenario 1: First-Time User

```bash
# Setup
1. Create new user with email: test@example.com
2. User has no organisation roles, no commission memberships
3. is_voter = 0

# Test
1. Login with test@example.com
2. Check logs for: "first_time_user" decision
3. Should see: Redirect to /dashboard/welcome

# Verify
✓ Logs show "Dashboard role resolution: first_time_user"
✓ User sees welcome/onboarding page
```

### Manual Test Scenario 2: Organisation Admin

```bash
# Setup
1. Create user: admin@example.com
2. Add record to user_organisation_roles (org_id=1, role='admin')
3. is_voter = 0

# Test
1. Login with admin@example.com
2. Check logs for: getDashboardRoles returns ['admin']
3. Should see: Redirect to /organisations/{slug}

# Verify
✓ Logs show "Dashboard roles resolved: admin"
✓ User sees organisation dashboard
✓ CheckUserRole middleware logs "Access GRANTED"
```

### Manual Test Scenario 3: Multiple Roles

```bash
# Setup
1. Create user: multi@example.com
2. Add to user_organisation_roles (role='admin')
3. Add to election_commission_members
4. is_voter = 1

# Test
1. Login with multi@example.com
2. Check logs for: getDashboardRoles returns ['admin', 'commission', 'voter']
3. Should see: Redirect to /dashboard/roles

# Verify
✓ Logs show "Dashboard roles resolved: 3 roles"
✓ Logs show "multiple_roles" decision
✓ User sees role selection page
✓ User can click to choose a role
✓ Subsequent redirect uses CheckUserRole to validate
```

---

## Summary

The login flow is now properly orchestrated with:

✅ **Clear Separation**: LoginResponse delegates to DashboardResolver
✅ **Single Role Source**: All systems use `getDashboardRoles()`
✅ **Consistent Validation**: Middleware uses the same logic as DashboardResolver
✅ **Backward Compatible**: Supports legacy roles and attributes
✅ **Well-Logged**: Every step is logged for diagnostics
✅ **No Circular Dependencies**: Middleware and routing aligned

When a user logs in, the flow is:
1. **Fortify** authenticates credentials
2. **LoginResponse** asks DashboardResolver where to send them
3. **DashboardResolver** analyzes their roles and decides
4. **Browser** follows redirect to dashboard
5. **CheckUserRole** validates they have the required role
6. **Controller** renders their dashboard

All using the same role detection logic throughout.
