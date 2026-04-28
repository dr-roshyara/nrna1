# Architecture — Technical Implementation Details

## Overview

The Platform Admin Dashboard is built on a clean, layered architecture following Domain-Driven Design principles with test-first development.

---

## Architectural Layers

```
┌────────────────────────────────────────────────┐
│          Presentation Layer                    │ ← Vue 3 + Inertia
├────────────────────────────────────────────────┤
│          HTTP Layer                            │ ← Controllers
├────────────────────────────────────────────────┤
│       Application Layer                        │ ← Queries, Commands
├────────────────────────────────────────────────┤
│          Domain Layer                          │ ← Business Rules
├────────────────────────────────────────────────┤
│       Infrastructure Layer                     │ ← Database, Cache
└────────────────────────────────────────────────┘
```

### 1. **Presentation Layer** (Vue 3 + Inertia)

**Components:**
- `AdminLayout.vue` — Unified header + navigation
- `Dashboard.vue` — Stats dashboard
- `Elections/Pending.vue` — Approval queue
- `Elections/All.vue` — Transparency view with filters

**Characteristics:**
- **Options API** (not Composition API) following project convention
- Inertia 2.0 for seamless server-side routing
- Real-time form validation
- Flash message display
- i18n multi-language support (DE, EN, NP)

**Data Flow:**
```
User clicks [Approve] 
    ↓
Dialog modal appears 
    ↓
router.post('/platform/elections/{id}/approve', {...})
    ↓
Server processes request
    ↓
Inertia redirects (automatic)
    ↓
Flash message displays
```

### 2. **HTTP Layer** (Controllers)

**Controllers:**

#### `PlatformDashboardController@index()`
```php
public function index()
{
    return inertia('Admin/Dashboard', [
        'stats' => [
            'pending_elections' => Election::where('state', 'pending_approval')->count(),
            'platform_admins' => User::whereNotNull('platform_role')
                                    ->orWhere('is_super_admin', true)->count(),
            'organisations' => Organisation::count(),
            'total_elections' => Election::count(),
        ]
    ]);
}
```

**Route:** `GET /platform/dashboard`
**Middleware:** `['auth', 'verified', 'platform_admin']`

---

#### `AdminElectionController@pending()`
```php
public function pending()
{
    return inertia('Admin/Elections/Pending', [
        'elections' => Election::where('state', 'pending_approval')
                              ->withoutGlobalScopes()  // ← CRITICAL
                              ->paginate(25),
    ]);
}
```

**Route:** `GET /platform/elections/pending`
**Key Detail:** `withoutGlobalScopes()` ensures platform admin sees ALL pending elections, not just their org's

---

#### `AdminElectionController@approve()`
```php
public function approve(Request $request, string $election)
{
    $election = Election::findOrFail($election);
    
    // State guard: must be pending_approval
    if ($election->state !== 'pending_approval') {
        return back()->with('error', 'Election not in pending state');
    }
    
    // Trigger transition via domain
    $election->approve(
        new UserId(auth()->id()),
        $request->input('notes')
    );
    
    return redirect()->route('platform.elections.pending')
                    ->with('success', 'Election approved');
}
```

**Route:** `POST /platform/elections/{election}/approve`
**Key Detail:** State guard prevents approving elections already approved

---

#### `AdminElectionController@reject()`
```php
public function reject(Request $request, string $election)
{
    $election = Election::findOrFail($election);
    
    // State guard: must be pending_approval
    if ($election->state !== 'pending_approval') {
        return back()->with('error', 'Election not in pending state');
    }
    
    $request->validate([
        'reason' => 'required|string|min:10'
    ]);
    
    // Trigger transition with reason
    $election->reject(
        new UserId(auth()->id()),
        $request->input('reason')
    );
    
    return redirect()->route('platform.elections.pending')
                    ->with('success', 'Election rejected');
}
```

**Route:** `POST /platform/elections/{election}/reject`
**Validation:** Reason must be at least 10 characters

---

#### `AdminElectionController@all()`
```php
public function all(Request $request)
{
    $query = Election::withoutGlobalScopes();
    
    // Filter by subscription tier
    $filter = $request->query('filter', 'all');
    if ($filter === 'free') {
        $query->where('expected_voter_count', '<=', 40);
    } elseif ($filter === 'paid') {
        $query->where('expected_voter_count', '>', 40);
    }
    
    // Sort by field
    $sort = $request->query('sort', 'created_at');
    $direction = $request->query('direction', 'desc');
    $query->orderBy($sort, $direction);
    
    $elections = $query->paginate(25)
                      ->appends($request->query());
    
    return inertia('Admin/Elections/All', [
        'elections' => $elections,
        'filters' => [
            'filter' => $filter,
            'sort' => $sort,
            'direction' => $direction,
        ]
    ]);
}
```

**Route:** `GET /platform/elections/all`
**Query Parameters:**
- `filter`: 'all' | 'free' | 'paid'
- `sort`: 'name' | 'expected_voter_count' | 'created_at'
- `direction`: 'asc' | 'desc'

---

### 3. **Application Layer** (Queries, Commands)

Not explicitly separate in current implementation. Logic is in controllers and domain models.

**Could be extracted as:**
```php
// Query
class GetPendingElectionsQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 25,
    ) {}
}

// Handler
class GetPendingElectionsQueryHandler
{
    public function handle(GetPendingElectionsQuery $query)
    {
        return Election::where('state', 'pending_approval')
                      ->withoutGlobalScopes()
                      ->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```

This allows for better testing and separation of concerns, but is optional in current implementation.

---

### 4. **Domain Layer** (Business Rules)

**Election Model Methods:**

```php
class Election extends Model
{
    public function approve(UserId $actorId, string $notes = null): void
    {
        // Validate actor has permission
        $role = $this->resolveActorRole($actorId);
        if (!in_array($role, ['super_admin', 'platform_admin'])) {
            throw new UnauthorizedException(
                "Only platform admins can approve"
            );
        }
        
        // Validate state transition
        if ($this->state !== 'pending_approval') {
            throw new InvalidStateException(
                "Cannot approve election not in pending_approval state"
            );
        }
        
        // Execute transition
        $this->state = 'administration';
        $this->approved_at = now();
        $this->approved_by = $actorId;
        $this->approval_notes = $notes;
        $this->save();
        
        // Dispatch event for audit trail
        event(new ElectionApproved($this));
    }
    
    public function reject(UserId $actorId, string $reason): void
    {
        // Similar validation...
        if (!in_array($role, ['super_admin', 'platform_admin'])) {
            throw new UnauthorizedException("No permission");
        }
        
        if ($this->state !== 'pending_approval') {
            throw new InvalidStateException("Cannot reject");
        }
        
        // Execute transition
        $this->state = 'draft';
        $this->rejection_reason = $reason;
        $this->rejected_at = now();
        $this->rejected_by = $actorId;
        $this->save();
        
        // Dispatch event
        event(new ElectionRejected($this));
    }
    
    private function resolveActorRole(string $actorId): string
    {
        // Role resolution priority
        if ($actorId === 'system') {
            return 'system';
        }
        
        $user = User::find($actorId);
        if ($user && $user->isSuperAdmin()) {
            return 'super_admin';
        }
        
        if ($user && $user->isPlatformAdmin()) {
            return 'platform_admin';
        }
        
        // ... election-level and org-level checks
        
        return 'observer';
    }
}
```

**Role Resolution Priority:**
```
1. system              → Auto-submit bypass
2. super_admin         → Platform owner
3. platform_admin      → Platform staff
4. chief/deputy        → Election officer
5. owner/admin         → Organization staff
6. observer            → Default fallback
```

---

### 5. **Infrastructure Layer** (Database)

#### Database Schema

**users table:**
```sql
CREATE TABLE users (
    id UUID PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    is_super_admin BOOLEAN DEFAULT FALSE,
    platform_role VARCHAR(50) NULL,  -- 'platform_admin'
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
);

INDEX idx_is_super_admin (is_super_admin);
INDEX idx_platform_role (platform_role);
```

**elections table:**
```sql
CREATE TABLE elections (
    id UUID PRIMARY KEY,
    organisation_id UUID NULL,  -- NULL = platform-level
    name VARCHAR(255),
    expected_voter_count INTEGER NOT NULL,
    state VARCHAR(50),  -- draft, pending_approval, administration, etc.
    submitted_for_approval_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    approved_by UUID NULL,
    approval_notes TEXT NULL,
    rejected_at TIMESTAMP NULL,
    rejected_by UUID NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
);

INDEX idx_state (state);
INDEX idx_expected_voter_count (expected_voter_count);
INDEX idx_created_at (created_at);
```

---

## Key Design Decisions

### 1. **withoutGlobalScopes() for Platform Admin**

```php
// Platform admin must see ALL elections
Election::withoutGlobalScopes()
    ->where('state', 'pending_approval')
    ->get();

// Organization admin sees only their org's
Election::where('organisation_id', $org_id)
    ->where('state', 'pending_approval')
    ->get();
```

**Why:** Multi-tenant BelongsToTenant global scope would filter out elections from other organizations. Platform admin operates above tenant scope.

### 2. **State Guards on Approval/Rejection**

```php
// CORRECT: Only allow approval of pending elections
if ($election->state !== 'pending_approval') {
    throw new Exception("Cannot approve non-pending election");
}

// WRONG: Allowing approval from any state
$election->approve();  // Could approve draft, already approved, etc.
```

**Why:** Prevents duplicate approvals and state machine violations.

### 3. **is_super_admin vs platform_role Distinction**

```php
// Super Admin: Platform Owner
$user->is_super_admin = true;          // One flag
$user->canManagePlatformAdmins();     // TRUE (can manage staff)
$user->isPlatformAdmin();              // TRUE (can approve elections)

// Platform Admin: Platform Staff
$user->is_super_admin = false;
$user->platform_role = 'platform_admin';  // Specific role
$user->canManagePlatformAdmins();         // FALSE (cannot manage other staff)
$user->isPlatformAdmin();                 // TRUE (can approve elections)
```

**Why:** Allows separation of concerns (owner vs staff) while keeping the same approval permissions.

### 4. **Computed Property: is_free**

```php
// Frontend receives computed property
$election->is_free = $election->expected_voter_count <= 40;

// Frontend can use for styling
v-if="election.is_free" → Shows "✓ Free"
v-else → Shows "⭐ Paid"
```

**Why:** Avoids repeating the threshold logic in frontend code.

---

## Middleware

### EnsurePlatformAdmin

```php
namespace App\Http\Middleware;

class EnsurePlatformAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isPlatformAdmin()) {
            abort(403, 'Access restricted to platform administrators.');
        }
        
        return $next($request);
    }
}
```

**Registration:**
```php
// bootstrap/app.php
$middleware->alias([
    // ...
    'platform_admin' => \App\Http\Middleware\EnsurePlatformAdmin::class,
]);
```

**Usage:**
```php
// routes/platform.php
Route::middleware(['auth', 'verified', 'platform_admin'])->group(function () {
    // Routes here are protected
});
```

---

## Inertia Props Sharing

### HandleInertiaRequests Middleware

```php
class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'email' => $request->user()->email,
                    'is_super_admin' => $request->user()->isSuperAdmin(),
                    'is_platform_admin' => $request->user()->isPlatformAdmin(),
                ] : null,
            ],
            // Other shared props...
        ]);
    }
}
```

**Accessible in Vue as:**
```vue
<template>
  <div v-if="$page.props.auth.user?.is_platform_admin">
    Platform Admin Features
  </div>
</template>
```

---

## Routes Architecture

### Route Organization

```
routes/
├── web.php              ← Main routes
├── platform.php         ← Platform admin routes (new)
└── api.php              ← API routes
```

**routes/web.php (tail):**
```php
// Include platform admin routes
require __DIR__.'/platform.php';

// Tenant catch-all (must be last!)
Route::get('/{tenant}', ...);
```

**routes/platform.php:**
```php
<?php

use App\Http\Controllers\Admin\AdminElectionController;
use App\Http\Controllers\Admin\PlatformDashboardController;

Route::middleware(['auth', 'verified', 'platform_admin'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {
        
        Route::get('/dashboard', [PlatformDashboardController::class, 'index'])
            ->name('dashboard');
        
        Route::get('/elections/pending', [AdminElectionController::class, 'pending'])
            ->name('elections.pending');
        
        Route::post('/elections/{election}/approve', [AdminElectionController::class, 'approve'])
            ->name('elections.approve');
        
        Route::post('/elections/{election}/reject', [AdminElectionController::class, 'reject'])
            ->name('elections.reject');
        
        Route::get('/elections/all', [AdminElectionController::class, 'all'])
            ->name('elections.all');
    });
```

---

## Testing Strategy

### Test Classes

```
tests/Feature/Admin/
├── AdminElectionControllerTest.php
└── PlatformDashboardControllerTest.php

tests/Unit/Models/
└── ElectionApprovalTest.php
```

### Example Test

```php
public function test_super_admin_can_approve_pending_election()
{
    $super_admin = User::factory()->create(['is_super_admin' => true]);
    $election = Election::factory()->create(['state' => 'pending_approval']);
    
    $response = $this->actingAs($super_admin)
                    ->post(route('platform.elections.approve', $election), [
                        'notes' => 'Verified data quality'
                    ]);
    
    $response->assertRedirect(route('platform.elections.pending'));
    $this->assertTrue($election->fresh()->state === 'administration');
}

public function test_org_admin_cannot_approve_election()
{
    $org_admin = User::factory()->create();
    $org_admin->assignRole('org_admin');
    
    $election = Election::factory()->create(['state' => 'pending_approval']);
    
    $response = $this->actingAs($org_admin)
                    ->post(route('platform.elections.approve', $election));
    
    $response->assertStatus(403);
}
```

---

## Performance Considerations

### Database Queries

**Pending Elections Query:**
```php
// OPTIMIZED: Single query with eager load
Election::where('state', 'pending_approval')
    ->withoutGlobalScopes()
    ->with('organisation')  // Eager load org name
    ->paginate(25);
    
// NOT: N+1 problem
foreach ($elections as $election) {
    echo $election->organisation->name;  // Separate query per election!
}
```

### Pagination

```php
// Default: 25 elections per page
$elections->paginate(25);

// Why 25?
// - Fast initial page load
// - Reasonable data volume (JSON payload < 50KB)
// - Balance between UX and performance
```

### Caching

```php
// Could cache dashboard stats (5 minute TTL)
Cache::remember('admin_dashboard_stats', 300, function () {
    return [
        'pending_elections' => Election::where('state', 'pending_approval')->count(),
        // ... other stats
    ];
});
```

Currently not implemented (simple counts are fast enough), but documented for future optimization.

---

## Security Considerations

### 1. **Tenant Isolation**

Platform admin has elevated permissions but still respects some boundaries:
```php
// Platform admin sees ALL elections
Election::withoutGlobalScopes()->get();

// But still only their own org's elections in some contexts
// (Depends on implementation - current allows all)
```

### 2. **Authorization on Every Transition**

```php
// Validation inside domain, not just in controller
public function approve(UserId $actor)
{
    // Domain enforces permission
    if (!$this->canApprove($actor)) {
        throw new UnauthorizedException();
    }
    
    // Then execute
    $this->state = 'administration';
}
```

### 3. **State Machine Integrity**

```php
// State transitions validated
if ($this->state !== 'pending_approval') {
    throw new InvalidStateException();
}

// Prevents: Draft → Results (invalid transition)
//           Voting → Draft (invalid transition)
```

---

## Related Documentation

- [Getting Started](./GETTING_STARTED.md) — User guide
- [Election Approval Workflow](./ELECTION_APPROVAL_WORKFLOW.md) — Process details
- [Subscription Model](./SUBSCRIPTION_MODEL.md) — Free vs Paid explanation
- [Extending](./EXTENDING.md) — Adding new features

---

**Status:** Production Ready ✅  
**Last Updated:** April 28, 2026
