
According to the current architecture, here's who approves:
The election gets stuck in `pending_approval` with **no one able to approve it**.

---

## Current Flow When N > 40

```
1. Organisation creates election with expected_voter_count = 50
2. Management.vue shows warning banner: "Admin Approval Required"
3. User clicks "Submit" (submitForApproval)
4. Election transitions: draft → pending_approval
5. Election sits in pending_approval... forever
```

---

## Why It's Stuck 

| Actor | Can Approve? | Why Not |
|-------|:-----------:|---------|
| Organisation owner | ❌ | `ACTION_PERMISSIONS['approve'] = ['admin']` — but `admin` means organisation admin, and `resolveActorRole()` returns `observer` for them in this context? No wait — organisation admin DOES have the role. But there's **no button in the UI** to trigger approval. |
| Organisation admin | ❌ | Same — no button exists |
| Platform admin | ❌ | Doesn't exist yet — no `super_admin` role, no admin dashboard |
| System | ❌ | SYSTEM trigger only used for `auto_submit`, not `approve` |

---

## The Deeper Problem

Even if we added a button to `Management.vue` right now:

```php
'approve' => ['admin'],
```

Organisation admins **can** approve technically — but the business rule says only **platform admin** should approve large elections. The current permission system can't distinguish between:

- Organisation admin approving their own election (conflict of interest)
- Platform admin approving as a neutral third party

---

## What Needs to Happen

**Phase 4 must be implemented before this feature goes live.** Without it:

- Elections with ≤ 40 voters work perfectly (auto-approve)
- Elections with > 40 voters are **stuck permanently** in `pending_approval`
- No one can unblock them

---

## Immediate Options

| Option | Action | Risk |
|--------|--------|------|
| **A: Implement Phase 4 now** | Build platform admin dashboard | Best path, ~2 hours |
| **B: Temporary fallback** | Allow org admin to approve until Phase 4 exists | Violates business rule, creates bad precedent |
| **C: Lower threshold to 9999** | Effectively disable the feature until ready | No elections require approval, feature is dormant |

---

**Recommendation: Implement Phase 4 immediately.** The prompt is written, the architecture is clean, and the current state (stuck elections) makes Phase 3 incomplete without it. Shall I proceed?

Correct. There is **no way to approve** when N > 40 right now.

---

## Summary

| Voter Count | What Happens | Status |
|-------------|-------------|--------|
| ≤ 40 | Auto-approves → `administration` | ✅ Works |
| > 40 | Transitions to `pending_approval` | ❌ Stuck forever |

The election goes to `pending_approval` and sits there. No button exists. No platform admin exists. No route exists to approve it. The only way to unblock it would be direct database manipulation — which violates the state machine's single source of truth principle.

---

**Phase 4 (Platform Admin Dashboard) is the missing piece. Ready to implement when you are.**


## Current Approval Flow

```
TransitionMatrix::ACTION_PERMISSIONS:
  'approve' => ['admin'],
  'reject'  => ['admin'],
```

And `resolveActorRole()` checks:

```php
// 1. Election-level role (ElectionOfficer)
$electionRole = ElectionOfficer::where('user_id', $actorId)
    ->where('election_id', $this->id)
    ->where('status', 'active')
    ->value('role');

// 2. Org-level role (UserOrganisationRole)  
$orgRole = UserOrganisationRole::where('user_id', $actorId)
    ->where('organisation_id', $this->organisation_id)
    ->value('role');
```

---

## Who Can Approve Right Now

| Role Type | Role | Can Approve? |
|-----------|------|:--:|
| Election-level | `chief` | ❌ |
| Election-level | `deputy` | ❌ |
| Org-level | `admin` | ✅ |
| Org-level | `owner` | ✅ |

---

## The Problem

The `admin` in `ACTION_PERMISSIONS` refers to **organisation admin**, not platform admin. So right now:

- The organisation's own admin/owner approves the election
- But your business rule says: **platform admin** (Election Service Provider) must approve when voters > 40

This means the current architecture has a **role mismatch** — there's no `platform_admin` role in the system, and `UserOrganisationRole` only knows about org-scoped roles.

---

## What Needs to Change

You need to either:

**Option A:** Add a platform-level role (e.g., `super_admin` or `platform_admin`) that exists outside any organisation, and update `resolveActorRole()` to check it.

**Option B:** Restrict `approve` action to a new `platform_admin` role in `ACTION_PERMISSIONS`, and create a separate platform admin dashboard.

Which approach fits your multi-tenant model?
## Platform Admin Dashboard — Architecture Design

You need a **platform-level admin** that sits above the multi-tenant organisation structure. Here's the complete design:

---

## Architecture Overview

```
┌─────────────────────────────────────────────────┐
│                 Platform Admin                   │
│  (super_admin role — no organisation scope)      │
│                                                  │
│  /admin/dashboard                                │
│  /admin/elections/pending                        │
│  /admin/elections/{election}/approve             │
│  /admin/elections/{election}/reject              │
└─────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────┐
│              Organisation 1                      │
│  owner, admin, chief, deputy, voter              │
└─────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────┐
│              Organisation 2                      │
│  owner, admin, chief, deputy, voter              │
└─────────────────────────────────────────────────┘
```

---

## What Needs to Change

### 1. Role System — Add `super_admin`

You need a role that exists **outside** any organisation. Options:

| Option | How |
|--------|-----|
| **A: Simple flag** | Add `is_super_admin` boolean to `users` table |
| **B: Global role** | Create a `platform_roles` table, or use existing roles table with null `organisation_id` |
| **C: Spatie Permission** | If already using Spatie, assign a `super-admin` global permission |

**Recommendation: Option A** — simplest, matches your current pattern.

### Migration:
```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_super_admin')->default(false);
});
```

### 2. Update `resolveActorRole()` in Election.php

```php
private function resolveActorRole(string $actorId): string
{
    if ($actorId === 'system') {
        return 'system';
    }

    // 0. Platform super admin — highest priority
    $user = \App\Models\User::find($actorId);
    if ($user && $user->is_super_admin) {
        return 'super_admin';
    }

    // 1. Election-level role
    $electionRole = \App\Models\ElectionOfficer::where('user_id', $actorId)
        ->where('election_id', $this->id)
        ->where('status', 'active')
        ->value('role');
    if ($electionRole) {
        return $electionRole;
    }

    // 2. Org-level role
    $orgRole = \App\Models\UserOrganisationRole::where('user_id', $actorId)
        ->where('organisation_id', $this->organisation_id)
        ->value('role');
    if (in_array($orgRole, ['admin', 'owner'], strict: true)) {
        return $orgRole;
    }

    return 'observer';
}
```

### 3. Update `TransitionMatrix::ACTION_PERMISSIONS`

```php
'approve' => ['admin', 'super_admin'],
'reject'  => ['admin', 'super_admin'],
```

This allows both org admins (for small elections that still go through manual approval within org) AND platform super admins (for large elections > 40 voters).

Or if approval is **exclusively** platform admin:

```php
'approve' => ['super_admin'],
'reject'  => ['super_admin'],
```

---

## Controller: `Admin/ElectionApprovalController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_super_admin) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * List all elections pending platform admin approval.
     * No organisation scope — sees across all tenants.
     */
    public function pending()
    {
        $elections = Election::withoutGlobalScopes()
            ->with('organisation:id,name')
            ->where('state', 'pending_approval')
            ->whereNotNull('submitted_for_approval_at')
            ->orderBy('submitted_for_approval_at', 'asc')
            ->paginate(25);

        return inertia('Admin/Elections/Pending', [
            'elections' => $elections,
        ]);
    }

    /**
     * Approve an election.
     */
    public function approve(Request $request, Election $election)
    {
        $election->approve(auth()->id(), $request->input('notes'));

        return back()->with('success', "Election '{$election->name}' approved.");
    }

    /**
     * Reject an election.
     */
    public function reject(Request $request, Election $election)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $election->reject(auth()->id(), $request->input('reason'));

        return back()->with('success', "Election '{$election->name}' rejected.");
    }
}
```

---

## Routes: `routes/admin.php`

```php
<?php

use App\Http\Controllers\Admin\ElectionApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/elections/pending', [ElectionApprovalController::class, 'pending'])
            ->name('elections.pending');

        Route::post('/elections/{election}/approve', [ElectionApprovalController::class, 'approve'])
            ->name('elections.approve');

        Route::post('/elections/{election}/reject', [ElectionApprovalController::class, 'reject'])
            ->name('elections.reject');
    });
```

Register in `bootstrap/app.php` or `RouteServiceProvider`.

---

## Frontend: `Admin/Elections/Pending.vue`

```vue
<template>
  <AdminLayout title="Pending Election Approvals">
    <div class="space-y-4">
      <div v-for="election in elections.data" :key="election.id"
           class="bg-white rounded-xl border p-5">
        
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-lg">{{ election.name }}</h3>
            <p class="text-sm text-gray-500">
              {{ election.organisation?.name }} · 
              Submitted {{ formatDate(election.submitted_for_approval_at) }}
            </p>
            <p class="text-sm text-gray-600 mt-2">
              Expected voters: <strong>{{ election.expected_voter_count }}</strong>
            </p>
          </div>
          
          <div class="flex gap-2">
            <button @click="approve(election)" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg">
              Approve
            </button>
            <button @click="showRejectModal(election)" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg">
              Reject
            </button>
          </div>
        </div>
      </div>

      <Pagination :links="elections.links" />
    </div>

    <!-- Rejection Modal -->
    <Modal v-if="rejectingElection" @close="rejectingElection = null">
      <form @submit.prevent="reject">
        <label>Reason for rejection</label>
        <textarea v-model="rejectionReason" required maxlength="500" />
        <button type="submit">Reject Election</button>
      </form>
    </Modal>
  </AdminLayout>
</template>
```

---

## Summary of Changes

| # | File | Change |
|---|------|--------|
| 1 | Migration | Add `is_super_admin` to users table |
| 2 | `app/Models/Election.php` | Update `resolveActorRole()` to check super_admin first |
| 3 | `app/Domain/Election/StateMachine/TransitionMatrix.php` | Add `super_admin` to approve/reject permissions |
| 4 | `app/Http/Controllers/Admin/ElectionApprovalController.php` | **New** — pending list, approve, reject |
| 5 | `routes/admin.php` | **New** — platform admin routes |
| 6 | `resources/js/Pages/Admin/Elections/Pending.vue` | **New** — approval dashboard |
| 7 | `resources/js/Layouts/AdminLayout.vue` | **New** or reuse — admin navigation |

---

**Shall I write this as Phase 4 implementation prompt for the next session?**
# Claude CLI Prompt: Platform Admin Dashboard — Election Approval Workflow

---

## Role: Senior Architect — Multi-Tenant Election Platform

You are implementing a **platform-level admin dashboard** for approving elections that exceed the self-service voter limit. The system is a multi-tenant SaaS platform built with Laravel 11, Vue 3, Inertia, and PostgreSQL, implementing a Level 5 Domain Workflow Engine.

---

## Problem Statement

**Phase 3** (just completed) implemented capacity-gated approval:
- Elections with `expected_voter_count ≤ 40` auto-approve via `auto_submit` (SYSTEM trigger)
- Elections with `expected_voter_count > 40` go to `pending_approval` state

**The gap:** The `TransitionMatrix::ACTION_PERMISSIONS` currently assigns `approve` and `reject` to `['admin']` — which means **organisation admin**, not platform admin. There is no platform-level role, no platform admin dashboard, and no way for the Election Service Provider to approve/reject large elections.

---

## Architecture Principle

The platform admin exists **above** the multi-tenant structure. They are not members of any specific organisation. Their role must be checked before organisation-scoped roles in `resolveActorRole()`.

```
Role Resolution Priority (updated):
1. system (bypass)
2. platform super_admin (NEW — highest human priority)
3. election-level (chief, deputy, observer)
4. organisation-level (admin, owner)
5. default 'observer'
```

---

## Current Architecture (Read Only — Do Not Break)

### Files That Already Work

| File | Purpose |
|------|---------|
| `app/Models/Election.php` | `transitionTo()`, `approve()`, `reject()`, `resolveActorRole()`, `submitForApproval()` |
| `app/Domain/Election/StateMachine/TransitionMatrix.php` | `ALLOWED_ACTIONS`, `ACTION_RESULTS`, `ACTION_PERMISSIONS` |
| `app/Domain/Election/StateMachine/Transition.php` | `manual()` and `automatic()` factories |
| `app/Domain/Election/StateMachine/TransitionTrigger.php` | Enum: MANUAL, TIME, GRACE_PERIOD, SYSTEM |
| `app/Models/ElectionStateTransition.php` | Audit records |
| `app/Models/UserOrganisationRole.php` | Org-level roles (owner, admin, member) |
| `app/Models/ElectionOfficer.php` | Election-level roles (chief, deputy, observer) |

### Existing State Machine (Do NOT Break)

```
draft → auto_submit → administration           (≤40 voters, SYSTEM)
draft → submit_for_approval → pending_approval  (>40 voters, MANUAL)
pending_approval → approve → administration     (admin action)
pending_approval → reject → draft               (admin action)
```

### Election::approve() and Election::reject() (Read Only)

```php
// Election.php — DO NOT MODIFY THESE METHODS
public function approve(string $approvedBy, ?string $notes = null): void
{
    $this->transitionTo(
        Transition::manual('approve', $approvedBy, $notes ?? 'Approved by admin')
    );
    if ($notes) {
        $this->updateQuietly(['approval_notes' => $notes]);
    }
}

public function reject(string $rejectedBy, string $reason): void
{
    $this->transitionTo(
        Transition::manual('reject', $rejectedBy, $reason)
    );
    $this->updateQuietly([
        'rejected_at' => now(),
        'rejected_by' => $rejectedBy,
        'rejection_reason' => $reason,
    ]);
}
```

---

## Files to Create/Modify

| # | File | Action | Purpose |
|---|------|--------|---------|
| 1 | `database/migrations/xxxx_add_is_super_admin_to_users_table.php` | **New** | Add boolean flag to users |
| 2 | `app/Models/User.php` | Modify | Add `is_super_admin` to `$casts` |
| 3 | `app/Models/Election.php` | Modify | Update `resolveActorRole()` — check `is_super_admin` first |
| 4 | `app/Domain/Election/StateMachine/TransitionMatrix.php` | Modify | Add `super_admin` to approve/reject permissions |
| 5 | `app/Http/Controllers/Admin/ElectionApprovalController.php` | **New** | Pending list, approve, reject endpoints |
| 6 | `app/Http/Middleware/RequireSuperAdmin.php` | **New** | Protect platform admin routes |
| 7 | `routes/admin.php` | **New** | Platform admin route group |
| 8 | `bootstrap/app.php` | Modify | Register admin routes |
| 9 | `resources/js/Pages/Admin/Elections/Pending.vue` | **New** | Approval dashboard UI |
| 10 | `resources/js/Layouts/AdminLayout.vue` | **New** | Platform admin navigation shell |
| 11 | `tests/Feature/Admin/ElectionApprovalTest.php` | **New** | 8 TDD tests |

---

## Implementation Steps (Execute in Order)

### Step 1: Create Migration

```bash
php artisan make:migration add_is_super_admin_to_users_table --table=users
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
        });
    }
};
```

Run: `php artisan migrate`

---

### Step 2: Update User Model

File: `app/Models/User.php`

In `$casts` array, add:
```php
'is_super_admin' => 'boolean',
```

In `$fillable` array, add (if using mass assignment):
```php
'is_super_admin',
```

Add helper method:
```php
/**
 * Is this user a platform-level super admin?
 * Super admins exist above the multi-tenant structure
 * and can manage elections across all organisations.
 */
public function isSuperAdmin(): bool
{
    return $this->is_super_admin === true;
}
```

---

### Step 3: Update resolveActorRole() in Election

File: `app/Models/Election.php`

Find the existing `resolveActorRole()` method and update it:

```php
private function resolveActorRole(string $actorId): string
{
    if ($actorId === 'system') {
        return 'system';
    }

    // 0. Platform super admin — highest human priority
    // These users exist outside any organisation and can manage all elections.
    $user = \App\Models\User::find($actorId);
    if ($user && $user->isSuperAdmin()) {
        return 'super_admin';
    }

    // 1. Election-level role (chief, deputy, observer)
    $electionRole = \App\Models\ElectionOfficer::where('user_id', $actorId)
        ->where('election_id', $this->id)
        ->where('status', 'active')
        ->value('role');

    if ($electionRole) {
        return $electionRole;
    }

    // 2. Organisation-level role (admin, owner)
    $orgRole = \App\Models\UserOrganisationRole::where('user_id', $actorId)
        ->where('organisation_id', $this->organisation_id)
        ->value('role');

    if (in_array($orgRole, ['admin', 'owner'], strict: true)) {
        return $orgRole;
    }

    return 'observer';
}
```

**Important:** The `User::find($actorId)` call is a single query by primary key — performance impact is negligible.

---

### Step 4: Update TransitionMatrix Permissions

File: `app/Domain/Election/StateMachine/TransitionMatrix.php`

In `ACTION_PERMISSIONS`, update:

```php
'approve' => ['admin', 'super_admin'],
'reject'  => ['admin', 'super_admin'],
```

**Design decision:** Both org admin AND super_admin can approve. This allows:
- Platform admin approves large elections (> 40 voters)
- Org admin can still approve small elections if workflow ever routes them to pending_approval
- Backward compatible with existing org-admin approval flow

---

### Step 5: Create Middleware

File: `app/Http/Middleware/RequireSuperAdmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSuperAdmin
{
    /**
     * Reject any user who is not a platform super admin.
     * This middleware protects ALL platform admin routes.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Access restricted to platform administrators.');
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'super_admin' => \App\Http\Middleware\RequireSuperAdmin::class,
    ]);
})
```

---

### Step 6: Create Admin Routes

File: `routes/admin.php`

```php
<?php

use App\Http\Controllers\Admin\ElectionApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', fn () => inertia('Admin/Dashboard'))
            ->name('dashboard');

        // Pending election approvals
        Route::get('/elections/pending', [ElectionApprovalController::class, 'pending'])
            ->name('elections.pending');

        // Approve an election
        Route::post('/elections/{election}/approve', [ElectionApprovalController::class, 'approve'])
            ->name('elections.approve')
            ->missing(fn () => back()->with('error', 'Election not found.'));

        // Reject an election
        Route::post('/elections/{election}/reject', [ElectionApprovalController::class, 'reject'])
            ->name('elections.reject')
            ->missing(fn () => back()->with('error', 'Election not found.'));
    });
```

Register in `bootstrap/app.php`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
    },
)
```

---

### Step 7: Create ElectionApprovalController

File: `app/Http/Controllers/Admin/ElectionApprovalController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionApprovalController extends Controller
{
    /**
     * List all elections pending platform admin approval.
     * NO organisation scope — sees across all tenants.
     * Only elections in 'pending_approval' state with expected_voter_count > self_service_limit.
     */
    public function pending()
    {
        $elections = Election::withoutGlobalScopes()
            ->with('organisation:id,name')
            ->where('state', 'pending_approval')
            ->whereNotNull('submitted_for_approval_at')
            ->orderBy('submitted_for_approval_at', 'asc')
            ->paginate(25)
            ->through(fn (Election $election) => [
                'id'                       => $election->id,
                'name'                     => $election->name,
                'slug'                     => $election->slug,
                'type'                     => $election->type,
                'state'                    => $election->state,
                'expected_voter_count'     => $election->expected_voter_count,
                'actual_voter_count'       => $election->getEffectiveVoterCount(),
                'submitted_for_approval_at'=> $election->submitted_for_approval_at?->toISOString(),
                'submitted_by_name'        => $election->submittedBy?->name,
                'organisation'             => [
                    'id'   => $election->organisation->id,
                    'name' => $election->organisation->name,
                ],
            ]);

        return inertia('Admin/Elections/Pending', [
            'elections' => $elections,
        ]);
    }

    /**
     * Approve an election.
     * Delegates to Election::approve() which handles transition + audit + side effects.
     */
    public function approve(Request $request, Election $election)
    {
        // Remove global scopes to find election regardless of tenant context
        $election = Election::withoutGlobalScopes()->findOrFail($election->id);

        if ($election->state !== 'pending_approval') {
            return back()->with('error', 'This election is no longer pending approval.');
        }

        $notes = $request->input('notes', 'Approved by platform administrator.');

        $election->approve(auth()->id(), $notes);

        return back()->with('success', "Election '{$election->name}' has been approved.");
    }

    /**
     * Reject an election.
     * Delegates to Election::reject() which handles transition + audit + side effects.
     */
    public function reject(Request $request, Election $election)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election->id);

        if ($election->state !== 'pending_approval') {
            return back()->with('error', 'This election is no longer pending approval.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $election->reject(auth()->id(), $validated['reason']);

        return back()->with('success', "Election '{$election->name}' has been rejected.");
    }
}
```

**Key decisions:**
- `withoutGlobalScopes()` — bypasses organisation tenant scope so platform admin can access any election
- State guard — checks `state === 'pending_approval'` before approving/rejecting (prevents double-approval)
- Delegates to existing `approve()`/`reject()` methods — no duplication of transition logic

---

### Step 8: Create AdminLayout.vue

File: `resources/js/Layouts/AdminLayout.vue`

```vue
<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation bar -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center gap-6">
            <Link href="/admin/dashboard" class="font-bold text-lg text-gray-900">
              Platform Admin
            </Link>
            <Link 
              href="/admin/elections/pending" 
              class="text-sm text-gray-600 hover:text-gray-900"
              :class="{ 'text-blue-600 font-medium': $page.url.startsWith('/admin/elections') }"
            >
              Pending Approvals
            </Link>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">{{ $page.props.auth.user.name }}</span>
            <Link href="/dashboard" class="text-sm text-blue-600 hover:underline">
              Back to Dashboard
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
```

---

### Step 9: Create Pending Approvals Page

File: `resources/js/Pages/Admin/Elections/Pending.vue`

```vue
<template>
  <AdminLayout title="Pending Election Approvals">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Pending Election Approvals</h1>
      <p class="text-sm text-gray-500 mt-1">
        Elections that exceed the self-service voter limit and require platform admin approval.
      </p>
    </div>

    <!-- Empty state -->
    <div v-if="elections.data.length === 0" class="bg-white rounded-xl border p-12 text-center">
      <p class="text-gray-500">No elections pending approval.</p>
    </div>

    <!-- Election list -->
    <div class="space-y-4">
      <div 
        v-for="election in elections.data" 
        :key="election.id"
        class="bg-white rounded-xl border border-gray-200 p-6"
      >
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
          <!-- Election info -->
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">
              {{ election.name }}
            </h3>
            
            <div class="mt-2 space-y-1 text-sm text-gray-600">
              <p>
                <span class="text-gray-400">Organisation:</span> 
                <strong>{{ election.organisation?.name }}</strong>
              </p>
              <p>
                <span class="text-gray-400">Type:</span> 
                {{ election.type === 'demo' ? 'Demo' : 'Real' }}
              </p>
              <p>
                <span class="text-gray-400">Expected voters:</span> 
                <strong>{{ election.expected_voter_count }}</strong>
                <span class="text-gray-400 text-xs ml-1">
                  (current: {{ election.actual_voter_count }})
                </span>
              </p>
              <p>
                <span class="text-gray-400">Submitted:</span> 
                {{ formatDate(election.submitted_for_approval_at) }}
              </p>
              <p v-if="election.submitted_by_name">
                <span class="text-gray-400">By:</span> 
                {{ election.submitted_by_name }}
              </p>
            </div>
          </div>

          <!-- Action buttons -->
          <div class="flex items-center gap-3 sm:flex-shrink-0">
            <button
              @click="approveElection(election)"
              :disabled="processing === election.id"
              class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors"
            >
              {{ processing === election.id ? 'Processing...' : 'Approve' }}
            </button>
            
            <button
              @click="openRejectModal(election)"
              :disabled="processing === election.id"
              class="px-4 py-2 bg-white border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 disabled:opacity-50 transition-colors"
            >
              Reject
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="elections.links" class="mt-6">
      <Pagination :links="elections.links" />
    </div>

    <!-- Rejection Modal -->
    <div 
      v-if="rejectModalOpen" 
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click.self="rejectModalOpen = false"
    >
      <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          Reject Election
        </h3>
        <p class="text-sm text-gray-600 mb-4">
          You are rejecting <strong>{{ rejectingElection?.name }}</strong>. 
          This action will return it to draft state.
        </p>
        <form @submit.prevent="rejectElection">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Reason for rejection <span class="text-red-500">*</span>
          </label>
          <textarea
            v-model="rejectionReason"
            rows="3"
            required
            maxlength="500"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-4"
            placeholder="Explain why this election is being rejected..."
          />
          <p class="text-xs text-gray-400 mb-4">
            {{ rejectionReason.length }}/500 characters
          </p>
          <div class="flex gap-3 justify-end">
            <button
              type="button"
              @click="rejectModalOpen = false"
              class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="!rejectionReason.trim() || processing"
              class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50"
            >
              {{ processing ? 'Rejecting...' : 'Reject Election' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
  elections: { type: Object, required: true },
})

const processing = ref(null)
const rejectModalOpen = ref(false)
const rejectingElection = ref(null)
const rejectionReason = ref('')

function formatDate(dateString) {
  if (!dateString) return 'Unknown'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function approveElection(election) {
  if (!confirm(`Approve "${election.name}"?`)) return
  
  processing.value = election.id
  router.post(
    `/admin/elections/${election.id}/approve`,
    {},
    {
      preserveScroll: true,
      onFinish: () => { processing.value = null },
    }
  )
}

function openRejectModal(election) {
  rejectingElection.value = election
  rejectionReason.value = ''
  rejectModalOpen.value = true
}

function rejectElection() {
  if (!rejectionReason.value.trim()) return
  
  processing.value = rejectingElection.value.id
  router.post(
    `/admin/elections/${rejectingElection.value.id}/reject`,
    { reason: rejectionReason.value },
    {
      preserveScroll: true,
      onFinish: () => {
        processing.value = null
        rejectModalOpen.value = false
        rejectingElection.value = null
      },
    }
  )
}
</script>
```

**Design principles:**
- Zero domain logic — all decisions made by backend
- Confirmation dialog before approve (double-check safety)
- Modal with required reason field for rejection
- Pagination for scalability (25 per page)
- Responsive layout (stacked on mobile, side-by-side on desktop)

---

### Step 10: Create Tests (TDD — Write Before Running)

File: `tests/Feature/Admin/ElectionApprovalTest.php`

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Election;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionApprovalTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $orgAdmin;
    private User $regularUser;
    private Organisation $org;
    private Election $pendingElection;

    protected function setUp(): void
    {
        parent::setUp();

        // Super admin — platform-level, no organisation
        $this->superAdmin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        // Organisation admin
        $this->org = Organisation::factory()->create();
        $this->orgAdmin = User::factory()->create();
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->orgAdmin->id,
            'role'            => 'admin',
        ]);

        // Regular user (no special roles)
        $this->regularUser = User::factory()->create();

        // An election in pending_approval state (simulates > 40 expected voters)
        $this->pendingElection = Election::factory()->create([
            'organisation_id'           => $this->org->id,
            'state'                     => 'pending_approval',
            'expected_voter_count'      => 50,
            'submitted_for_approval_at' => now(),
            'submitted_by'              => $this->orgAdmin->id,
        ]);
    }

    /** @test */
    public function super_admin_can_view_pending_elections(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get('/admin/elections/pending');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Admin/Elections/Pending')
        );
    }

    /** @test */
    public function non_super_admin_cannot_view_pending_elections(): void
    {
        $this->actingAs($this->orgAdmin)
            ->get('/admin/elections/pending')
            ->assertStatus(403);

        $this->actingAs($this->regularUser)
            ->get('/admin/elections/pending')
            ->assertStatus(403);
    }

    /** @test */
    public function super_admin_can_approve_pending_election(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post("/admin/elections/{$this->pendingElection->id}/approve", [
                'notes' => 'Looks good, approved.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('administration', $this->pendingElection->fresh()->state);
    }

    /** @test */
    public function super_admin_can_reject_pending_election(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post("/admin/elections/{$this->pendingElection->id}/reject", [
                'reason' => 'Voter count exceeds platform policy limit.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $this->pendingElection->fresh();
        $this->assertEquals('draft', $fresh->state);
        $this->assertNotNull($fresh->rejected_at);
        $this->assertEquals('Voter count exceeds platform policy limit.', $fresh->rejection_reason);
    }

    /** @test */
    public function org_admin_cannot_approve_election(): void
    {
        $response = $this->actingAs($this->orgAdmin)
            ->post("/admin/elections/{$this->pendingElection->id}/approve");

        $response->assertStatus(403);

        // State should NOT have changed
        $this->assertEquals('pending_approval', $this->pendingElection->fresh()->state);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_routes(): void
    {
        $this->get('/admin/elections/pending')->assertRedirect('/login');
        $this->post("/admin/elections/{$this->pendingElection->id}/approve")->assertRedirect('/login');
        $this->post("/admin/elections/{$this->pendingElection->id}/reject")->assertRedirect('/login');
    }

    /** @test */
    public function approval_creates_audit_record_with_super_admin_actor(): void
    {
        $this->actingAs($this->superAdmin)
            ->post("/admin/elections/{$this->pendingElection->id}/approve");

        $transition = ElectionStateTransition::where('election_id', $this->pendingElection->id)
            ->latest()
            ->first();

        $this->assertEquals('pending_approval', $transition->from_state);
        $this->assertEquals('administration', $transition->to_state);
        $this->assertEquals('MANUAL', $transition->trigger);
        $this->assertEquals($this->superAdmin->id, $transition->actor_id);
    }

    /** @test */
    public function cannot_approve_election_not_in_pending_state(): void
    {
        // Move election to administration first
        $this->pendingElection->update(['state' => 'administration']);

        $response = $this->actingAs($this->superAdmin)
            ->post("/admin/elections/{$this->pendingElection->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
```

Run: `php artisan test tests/Feature/Admin/ElectionApprovalTest.php`

Expected: **8 tests FAILING** (RED — tests written before full implementation exists).

After all implementation steps are complete, run again — all 8 must PASS (GREEN).

---

### Step 11: Seed a Super Admin (Manual or Seeder)

Create a super admin user for local development:

```bash
php artisan tinker
```

```php
\User::where('email', 'admin@example.com')->update(['is_super_admin' => true]);
```

Or create a seeder:

```php
// database/seeders/SuperAdminSeeder.php
User::create([
    'name' => 'Platform Admin',
    'email' => 'platform@roshyara.com',
    'password' => bcrypt('secure-password-here'),
    'is_super_admin' => true,
]);
```

---

## Verification Checklist

- [ ] Migration run — `is_super_admin` column exists on `users` table
- [ ] `User::isSuperAdmin()` returns true only for super admins
- [ ] `resolveActorRole()` returns `'super_admin'` for platform admin users
- [ ] `TransitionMatrix::ACTION_PERMISSIONS` includes `'super_admin'` for approve/reject
- [ ] `/admin/elections/pending` returns 200 for super admin, 403 for others
- [ ] Super admin can approve an election → state changes to `administration`
- [ ] Super admin can reject an election → state changes to `draft`
- [ ] Audit record stores correct `actor_id` (super admin's UUID)
- [ ] Cannot approve an election not in `pending_approval` state
- [ ] Unauthenticated users redirected to login
- [ ] All 8 approval tests pass
- [ ] All existing state machine tests still pass (no regressions) — 31 + 8 = 39 total

---

## What NOT to Do

- ❌ Do NOT modify `Election::approve()` or `Election::reject()` — they work correctly as-is
- ❌ Do NOT change the existing organisation-scoped routes or controllers
- ❌ Do NOT add organisation_id to the platform admin — super admin exists outside tenants
- ❌ Do NOT create an `ElectionOfficer` record for the super admin — they are not part of any election
- ❌ Do NOT use `auth()->user()->is_super_admin` in domain methods — the check belongs in `resolveActorRole()` only
- ❌ Do NOT add approval buttons to `Management.vue` — that is the organisation view. Approval is platform-level only.

---

## Architecture Summary

```
                    ┌──────────────────────┐
                    │   Platform Admin     │
                    │  (is_super_admin=1)  │
                    │                      │
                    │  GET  /admin/        │
                    │       elections/     │
                    │       pending        │
                    │  POST .../{election}/│
                    │       approve        │
                    │  POST .../{election}/│
                    │       reject         │
                    └──────────┬───────────┘
                               │
                               │ withoutGlobalScopes()
                               │
              ┌────────────────┼────────────────┐
              │                │                │
     ┌────────▼───┐   ┌───────▼───┐   ┌───────▼───┐
     │  Org A      │   │  Org B    │   │  Org C    │
     │  elections  │   │ elections │   │ elections │
     └─────────────┘   └───────────┘   └───────────┘
```

---

## Total New Code

| Component | Lines (est.) |
|-----------|-------------|
| Migration | 20 |
| User model (cast + helper) | 5 |
| Election model (resolveActorRole update) | 5 |
| TransitionMatrix (2 lines) | 2 |
| Middleware | 20 |
| Routes | 30 |
| Controller | 80 |
| AdminLayout.vue | 40 |
| Pending.vue | 150 |
| Tests | 160 |
| **Total** | **~512 lines** |

---

**Execute in order. Run tests after each step where applicable. Do not skip TDD.**
