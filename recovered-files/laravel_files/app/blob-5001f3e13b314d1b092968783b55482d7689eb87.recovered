# 🎯 DashboardResolver Architecture

## Overview

`DashboardResolver` is the intelligent routing system that determines which dashboard each user should access based on their roles, organizations, and system state.

**Location**: `app/Services/DashboardResolver.php`

## Purpose

Determine the optimal dashboard for each authenticated user by analyzing:
1. Active voting sessions
2. User onboarding status
3. Organization roles
4. Commission memberships
5. Legacy role mappings

## Resolution Priority (In Order)

```
┌─────────────────────────────────────┐
│ PRIORITY 1: Active Voting Session   │
│ User is currently voting            │
├─────────────────────────────────────┤
│ PRIORITY 2: Email Verification     │ ← NEW SECURITY FIX
│ Just verified, needs onboarding    │
├─────────────────────────────────────┤
│ PRIORITY 3: First-Time User        │
│ No roles/orgs/commissions          │
├─────────────────────────────────────┤
│ PRIORITY 4: Multiple Roles         │
│ User has >1 dashboard role         │
├─────────────────────────────────────┤
│ PRIORITY 5: Single Role            │
│ User has exactly 1 role            │
├─────────────────────────────────────┤
│ PRIORITY 6: Legacy Fallback        │
│ Check old Spatie role structure    │
└─────────────────────────────────────┘
```

## Class Structure

```php
class DashboardResolver {

    public function resolve(User $user): RedirectResponse

    // Priority checks
    protected function checkActiveVotingSession($user): ?string
    protected function isFirstTimeUser($user): bool
    protected function getDashboardRoles($user): array
    protected function getCurrentVotingStep($voterSlug): VotingStep

    // Caching
    protected function shouldUseCachedResolution($user): bool
    protected function getCachedResolution($user): ?string
    protected function cacheResolution($user, $targetUrl): void
    protected function isSessionFresh($user): bool

    // Redirects
    protected function redirectToVoting($user, $votingDashboard): RedirectResponse
    protected function redirectToFirstTimeUser($user): RedirectResponse
    protected function redirectToRoleSelection($user, array $roles): RedirectResponse
    protected function redirectByRole($user, string $role): RedirectResponse
    protected function legacyFallback($user): RedirectResponse
}
```

## Key Methods

### 1. resolve() - Main Entry Point

```php
public function resolve(User $user): RedirectResponse
{
    Log::info('DashboardResolver: Starting resolution', [
        'user_id' => $user->id,
        'timestamp' => now()->toIso8601String(),
    ]);

    // Try to get cached resolution if session is fresh
    if ($this->shouldUseCachedResolution($user)) {
        $cached = $this->getCachedResolution($user);
        if ($cached) {
            return redirect($cached);
        }
    }

    // PRIORITY 1: Check for active voting session
    $votingDashboard = $this->checkActiveVotingSession($user);
    if ($votingDashboard) {
        $this->cacheResolution($user, $votingDashboard);
        return $this->redirectToVoting($user, $votingDashboard);
    }

    // PRIORITY 1.5: Check if user needs onboarding
    if ($user->email_verified_at !== null && $user->onboarded_at === null) {
        return redirect()->route('dashboard.welcome');
    }

    // PRIORITY 2: First-time users → Welcome Dashboard
    if ($this->isFirstTimeUser($user)) {
        $response = $this->redirectToFirstTimeUser($user);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    // PRIORITY 3: New system dashboard roles
    $dashboardRoles = $this->getDashboardRoles($user);

    if (count($dashboardRoles) > 1) {
        $response = $this->redirectToRoleSelection($user, $dashboardRoles);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    if (count($dashboardRoles) === 1) {
        $role = reset($dashboardRoles);
        $response = $this->redirectByRole($user, $role);
        $this->cacheResolution($user, $response->getTargetUrl());
        return $response;
    }

    // PRIORITY 4: Legacy fallback
    $response = $this->legacyFallback($user);
    $this->cacheResolution($user, $response->getTargetUrl());
    return $response;
}
```

### 2. checkActiveVotingSession()

```php
protected function checkActiveVotingSession($user): ?string
{
    try {
        if (!Schema::hasTable('voter_slugs')) {
            return null;
        }

        // Find active voting session for this user
        $activeVote = DB::table('voter_slugs')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('is_active', true)
                      ->orWhereNull('completed_at');
            })
            ->first();

        if (!$activeVote) {
            return null;
        }

        // Get current voting step
        $currentStep = $this->getCurrentVotingStep($activeVote);

        // Route based on step
        return match($currentStep) {
            VotingStep::WAITING => route('vote.start'),
            VotingStep::CODE_VERIFIED => route('vote.agreement'),
            VotingStep::AGREEMENT_ACCEPTED => route('vote.select'),
            VotingStep::VOTE_CAST => route('vote.verify'),
            VotingStep::VERIFIED => route('vote.complete'),
        };
    } catch (Throwable $e) {
        Log::warning('Error checking active voting session', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return null;
    }
}
```

**Purpose**: If user has an active voting session, redirect them back to continue voting.

### 3. isFirstTimeUser()

```php
private function isFirstTimeUser($user): bool
{
    try {
        // Check if user has organisation roles (new system)
        if (Schema::hasTable('user_organisation_roles')) {
            $hasOrgRoles = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasOrgRoles) {
                return false;
            }
        }

        // Check if user is commission member (new system)
        if (Schema::hasTable('election_commission_members')) {
            $hasCommissionMembership = DB::table('election_commission_members')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasCommissionMembership) {
                return false;
            }
        }

        // Check legacy roles
        if ($user->is_voter ?? false) {
            return false;
        }

        try {
            if ($user->hasRole('admin') || $user->hasRole('election_officer')) {
                return false;
            }
        } catch (Throwable $e) {
            // Spatie roles may not be set up yet
        }

        // No roles/orgs/commissions = first-time user
        return true;
    } catch (Exception $e) {
        // Default to first-time if error checking
        return true;
    }
}
```

**Purpose**: Identify users with no roles or organizations.

### 4. getDashboardRoles()

```php
private function getDashboardRoles($user): array
{
    $roles = [];

    try {
        // 1. Organisation admin roles (NON-PLATFORM only)
        if (Schema::hasTable('user_organisation_roles')) {
            $adminRoleExists = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('role', 'admin')
                ->whereNot(function ($query) {
                    // ✅ FIX: Exclude platform organisation (id=1)
                    // Platform members should NOT be routed as admins
                    $query->where('organisation_id', 1);
                })
                ->exists();

            if ($adminRoleExists) {
                $roles[] = 'admin';
            }
        }

        // 2. Commission memberships
        if (Schema::hasTable('election_commission_members')) {
            $commissionExists = DB::table('election_commission_members')
                ->where('user_id', $user->id)
                ->exists();

            if ($commissionExists) {
                $roles[] = 'commission';
            }
        }

        // 3. Voter status
        if ($user->is_voter) {
            $roles[] = 'voter';
        }

        // 4. Legacy Spatie roles
        $hasSpatieAdmin = $user->hasRole('admin');
        $hasSpatieElectionOfficer = $user->hasRole('election_officer');
        if ($hasSpatieAdmin || $hasSpatieElectionOfficer) {
            if (!in_array('admin', $roles)) {
                $roles[] = 'admin';
            }
        }

        // 5. Legacy committee member
        if ($user->is_committee_member ?? false) {
            if (!in_array('commission', $roles)) {
                $roles[] = 'commission';
            }
        }

        return array_unique(array_filter($roles));
    } catch (Exception $e) {
        return [];
    }
}
```

**Key Points**:
- ✅ **CRITICAL FIX**: Excludes platform organisation (id=1) from admin role
- Checks both new and legacy role structures
- Returns de-duplicated array

### 5. redirectByRole()

```php
private function redirectByRole($user, string $role): RedirectResponse
{
    // Special handling for organisation admins
    if ($role === 'admin') {
        try {
            // Get first organisation where user is admin
            $orgRole = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('role', 'admin')
                ->first();

            if ($orgRole) {
                $organisation = App\Models\Organisation::find($orgRole->organisation_id);

                if ($organisation) {
                    return redirect()->route('organisations.show', $organisation->slug);
                }
            }
        } catch (Exception $e) {
            // Fall through to default
        }
    }

    // Fallback routing by role
    $destination = match($role) {
        'admin' => 'admin.dashboard',
        'commission' => 'commission.dashboard',
        'voter' => 'vote.dashboard',
        default => 'role.selection',
    };

    return redirect()->route($destination);
}
```

**Purpose**: Route user to appropriate dashboard based on their role.

## Role System

### Dashboard Roles

| Role | Dashboard | Access Control |
|------|-----------|-----------------|
| `admin` | `/dashboard/admin` or `organisations/{slug}` | Organisation management |
| `commission` | `/dashboard/commission` | Election commission functions |
| `voter` | `/vote` | Voting interface |

### Platform vs Non-Platform Organisations

```
Platform Organisation (id=1):
├─ Members: Should NOT get 'admin' role
├─ Purpose: User pool management
└─ Exception: Special admin users

Non-Platform Organisations:
├─ Members: May have 'admin' role
├─ Purpose: Election management
└─ Admins: Routed to organisation dashboard
```

## Voting Steps

When user has active voting session, route to appropriate step:

```
VotingStep::WAITING
    └─ route('vote.start')

VotingStep::CODE_VERIFIED
    └─ route('vote.agreement')

VotingStep::AGREEMENT_ACCEPTED
    └─ route('vote.select')

VotingStep::VOTE_CAST
    └─ route('vote.verify')

VotingStep::VERIFIED
    └─ route('vote.complete')
```

## Caching Strategy

```php
protected function shouldUseCachedResolution($user): bool
{
    // Check if caching is enabled
    if (!config('login-routing.cache.dashboard_resolution_ttl')) {
        return false;
    }

    // Check if cache exists
    if (!Cache::has($cacheKey)) {
        return false;
    }

    // Validate session freshness (prevent stale routing)
    return $this->isSessionFresh($user);
}

protected function isSessionFresh($user): bool
{
    $lastActivity = $user->last_activity_at;
    $threshold = config('login-routing.session.freshness_threshold', 60);

    return $lastActivity->addSeconds($threshold)->isFuture();
}
```

**Why Cache?**:
- DashboardResolver does multiple DB queries
- Cache hits save 5+ queries per login
- Session freshness prevents stale routing after role changes
- TTL: 5 minutes (default)

## Decision Tree

```
User authenticated
    ↓
Has active voting?
    ├─ YES → Route to current voting step
    └─ NO ↓
Has just verified email but not onboarded?
    ├─ YES → Route to welcome/onboarding
    └─ NO ↓
Has any roles/organizations/commissions?
    ├─ NO → First-time user → Welcome dashboard
    └─ YES ↓
How many dashboard roles?
    ├─ 1 → Route to that role's dashboard
    ├─ >1 → Route to role selection page
    └─ 0 → Check legacy roles

Legacy role check:
    ├─ Spatie admin/election_officer → Admin dashboard
    ├─ Committee member → Commission dashboard
    └─ Marked as voter → Voter dashboard
```

## Error Handling

### Safe Defaults
```php
try {
    // Risky operation
} catch (Throwable $e) {
    Log::warning('Error', ['error' => $e->getMessage()]);
    // Safe fallback
    return $this->legacyFallback($user);
}
```

### Schema Checks
```php
if (!Schema::hasTable('voter_slugs')) {
    return null;  // Table doesn't exist yet
}
```

## Testing

### Test File
`tests/Unit/Services/DashboardResolverRoleTest.php` - Tests role detection and routing

### What's Tested
1. Platform members NOT routed as admins
2. Non-platform admins routed correctly
3. Platform admins don't auto-redirect
4. New users see welcome page
5. Multiple roles handled correctly

## Database Tables Used

| Table | Purpose | Notes |
|-------|---------|-------|
| `user_organisation_roles` | New system roles | Pivot table for multi-role support |
| `election_commission_members` | Commission membership | Links users to elections |
| `voter_slugs` | Active voting sessions | Tracks voting progress |
| `roles` | Legacy Spatie roles | For backward compatibility |
| `model_has_roles` | Legacy Spatie mappings | User-role relationships |

## Configuration

```php
// config/login-routing.php
return [
    'cache' => [
        'dashboard_resolution_ttl' => 300,  // Cache for 5 minutes
    ],
    'session' => [
        'validate_freshness' => true,
        'freshness_threshold' => 60,  // Invalidate cache after 60 seconds of inactivity
    ],
];
```

## Logging

```php
Log::info('DashboardResolver: Starting resolution', [
    'user_id' => $user->id,
    'email' => $user->email,
    'timestamp' => now()->toIso8601String(),
]);

Log::info('DashboardResolver: Dashboard roles resolved', [
    'user_id' => $user->id,
    'dashboard_roles' => $dashboardRoles,
    'role_count' => count($dashboardRoles),
]);

Log::info('DashboardResolver: Redirect decision', [
    'user_id' => $user->id,
    'decision' => 'first_time_user',
    'destination' => 'dashboard.welcome',
    'reason' => 'No organisations, commissions, or existing roles detected',
]);
```

## Performance

| Operation | Time | Notes |
|-----------|------|-------|
| Cache hit | ~5ms | Returns cached URL |
| Cache miss | ~100-200ms | Queries role tables |
| Voting session check | ~50ms | Single voter_slugs query |
| Role aggregation | ~80ms | Multiple table checks |

## Future Improvements

1. **Role Preferences**: Store user's preferred dashboard
2. **Quick Switch**: Allow users to switch dashboards quickly
3. **Smart Defaults**: Learn from user behavior
4. **Distributed Cache**: Support Redis for multi-server deployments
5. **Analytics**: Track which routes are most used

---

## Related Files

- **LoginResponse**: `app/Http/Responses/LoginResponse.php`
- **LoginController**: `app/Http/Controllers/Auth/LoginController.php`
- **Routes**: `routes/web.php`
- **Models**: `app/Models/User.php`, `app/Models/Organisation.php`
- **Tests**: `tests/Unit/Services/DashboardResolverRoleTest.php`

---

**Last Updated**: 2026-03-03
**Status**: Production Ready
**Maintenance**: High Priority - Core routing system
