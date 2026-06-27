# Extending — Adding New Platform Features

## Overview

This guide explains how to extend the Platform Admin Dashboard with new features, following the established architecture and patterns.

---

## Architecture Review

Before extending, understand the current structure:

```
Platform Admin Layer
├── Routes (routes/platform.php)
├── Controllers (app/Http/Controllers/Admin/)
├── Domain Logic (app/Models/Election.php)
├── Middleware (app/Http/Middleware/EnsurePlatformAdmin.php)
├── Vue Pages (resources/js/Pages/Admin/)
└── Layouts (resources/js/Layouts/AdminLayout.vue)
```

---

## Common Extension Scenarios

### Scenario 1: Add a New Dashboard Metric

**Example:** "Average Election Duration"

#### Step 1: Add to Controller

**File:** `app/Http/Controllers/Admin/PlatformDashboardController.php`

```php
public function index()
{
    $stats = [
        'pending_elections' => Election::where('state', 'pending_approval')->count(),
        'platform_admins' => User::whereNotNull('platform_role')
                                ->orWhere('is_super_admin', true)->count(),
        'organisations' => Organisation::count(),
        'total_elections' => Election::count(),
        
        // NEW: Average duration
        'avg_duration_days' => Election::where('state', 'results')
                                      ->select(DB::raw('AVG(DATEDIFF(completed_at, started_at)) as avg_days'))
                                      ->value('avg_days') ?? 0,
    ];
    
    return inertia('Admin/Dashboard', ['stats' => $stats]);
}
```

#### Step 2: Update Vue Component

**File:** `resources/js/Pages/Admin/Dashboard.vue`

Add to stats grid (line ~49):
```vue
<div class="rounded-lg bg-white p-6 shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">Avg Duration</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.avg_duration_days }} days</p>
        </div>
        <div class="text-4xl text-indigo-500">⏱️</div>
    </div>
</div>
```

#### Step 3: Test

```php
public function test_dashboard_shows_average_duration()
{
    $admin = User::factory()->create(['is_super_admin' => true]);
    
    // Create completed elections with durations
    Election::factory()->create([
        'state' => 'results',
        'started_at' => now()->subDays(7),
        'completed_at' => now(),
    ]);
    
    $response = $this->actingAs($admin)
                    ->get(route('platform.dashboard'));
    
    $response->assertInertia(fn (Assert $page) => $page
        ->has('stats.avg_duration_days')
    );
}
```

---

### Scenario 2: Add Filter to All Elections Page

**Example:** Filter by state (Draft, Pending, Administration, etc.)

#### Step 1: Update Controller

**File:** `app/Http/Controllers/Admin/AdminElectionController.php`

```php
public function all(Request $request)
{
    $query = Election::withoutGlobalScopes();
    
    // Existing filters...
    
    // NEW: Filter by state
    $state = $request->query('state', null);
    if ($state && in_array($state, ['draft', 'pending_approval', 'administration'])) {
        $query->where('state', $state);
    }
    
    $sort = $request->query('sort', 'created_at');
    $direction = $request->query('direction', 'desc');
    $query->orderBy($sort, $direction);
    
    return inertia('Admin/Elections/All', [
        'elections' => $query->paginate(25)->appends($request->query()),
        'filters' => [
            'state' => $state,
            'sort' => $sort,
            'direction' => $direction,
        ]
    ]);
}
```

#### Step 2: Update Vue Component

**File:** `resources/js/Pages/Admin/Elections/All.vue`

Add state filter tabs (after line ~50):
```vue
<!-- State Filter Tabs -->
<div class="flex gap-2 border-b border-gray-200 pb-2 mt-6">
    <Link
        :href="route('platform.elections.all', { state: null, sort: filters.sort, direction: filters.direction })"
        :class="!filters.state ? 'px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600' : 'px-4 py-2 text-sm text-gray-500 hover:text-gray-700'"
    >
        All States
    </Link>
    <Link
        :href="route('platform.elections.all', { state: 'draft', sort: filters.sort, direction: filters.direction })"
        :class="filters.state === 'draft' ? 'px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600' : 'px-4 py-2 text-sm text-gray-500 hover:text-gray-700'"
    >
        Draft
    </Link>
    <Link
        :href="route('platform.elections.all', { state: 'pending_approval', sort: filters.sort, direction: filters.direction })"
        :class="filters.state === 'pending_approval' ? 'px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600' : 'px-4 py-2 text-sm text-gray-500 hover:text-gray-700'"
    >
        Pending
    </Link>
    <Link
        :href="route('platform.elections.all', { state: 'administration', sort: filters.sort, direction: filters.direction })"
        :class="filters.state === 'administration' ? 'px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600' : 'px-4 py-2 text-sm text-gray-500 hover:text-gray-700'"
    >
        Administration
    </Link>
</div>
```

---

### Scenario 3: Add New Action (e.g., "Defer" Election)

**Example:** Platform admin can defer approval decision to later date

#### Step 1: Add Domain Method

**File:** `app/Models/Election.php`

```php
public function defer(UserId $actorId, \DateTime $deferUntil): void
{
    // Permission check
    $role = $this->resolveActorRole($actorId);
    if (!in_array($role, ['super_admin', 'platform_admin'])) {
        throw new UnauthorizedException("Only platform admins can defer");
    }
    
    // State guard
    if ($this->state !== 'pending_approval') {
        throw new InvalidStateException("Can only defer pending elections");
    }
    
    // Defer to specific date
    $this->deferred_until = $deferUntil;
    $this->deferred_by = $actorId;
    $this->save();
    
    event(new ElectionDeferred($this, $deferUntil));
}
```

#### Step 2: Add Controller Method

**File:** `app/Http/Controllers/Admin/AdminElectionController.php`

```php
public function defer(Request $request, string $election)
{
    $election = Election::findOrFail($election);
    
    // Validate state
    if ($election->state !== 'pending_approval') {
        return back()->with('error', 'Election not in pending state');
    }
    
    // Validate deferral date
    $request->validate([
        'defer_until' => 'required|date|after:today',
    ]);
    
    $election->defer(
        new UserId(auth()->id()),
        \Carbon\Carbon::parse($request->input('defer_until'))
    );
    
    return back()->with('success', 'Election deferred');
}
```

#### Step 3: Add Route

**File:** `routes/platform.php`

```php
Route::post('/elections/{election}/defer', [AdminElectionController::class, 'defer'])
    ->name('elections.defer');
```

#### Step 4: Update Vue Component

**File:** `resources/js/Pages/Admin/Elections/Pending.vue`

Add defer button to table:
```vue
<button
    @click="deferElection(election)"
    class="text-sm text-indigo-600 hover:text-indigo-900 mr-2"
>
    Defer
</button>
```

Add defer dialog:
```vue
<div v-if="showDeferDialog" class="fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md">
        <h3 class="text-lg font-bold mb-4">Defer Election</h3>
        <input
            v-model="deferUntilDate"
            type="date"
            class="w-full px-3 py-2 border rounded-lg"
        />
        <div class="mt-4 flex gap-2">
            <button @click="showDeferDialog = false" class="px-4 py-2 border rounded">Cancel</button>
            <button @click="confirmDefer" class="px-4 py-2 bg-indigo-600 text-white rounded">Defer</button>
        </div>
    </div>
</div>
```

---

### Scenario 4: Add New Page to Dashboard

**Example:** "Manage Platform Admins" page (currently marked "Coming Soon")

#### Step 1: Create Controller

**File:** `app/Http/Controllers/Admin/PlatformAdminController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class PlatformAdminController
{
    public function index()
    {
        return inertia('Admin/PlatformAdmins/Index', [
            'admins' => User::where('is_super_admin', true)
                           ->orWhere('platform_role', 'platform_admin')
                           ->get(),
        ]);
    }
    
    public function promote(Request $request, User $user)
    {
        // Only super_admin can promote
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        $user->update(['platform_role' => 'platform_admin']);
        
        return back()->with('success', 'User promoted to platform admin');
    }
    
    public function demote(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        $user->update(['platform_role' => null]);
        
        return back()->with('success', 'User demoted');
    }
}
```

#### Step 2: Add Routes

**File:** `routes/platform.php`

```php
Route::prefix('admins')->name('admins.')->group(function () {
    Route::get('/', [PlatformAdminController::class, 'index'])
        ->name('index');
    Route::post('/{user}/promote', [PlatformAdminController::class, 'promote'])
        ->name('promote');
    Route::post('/{user}/demote', [PlatformAdminController::class, 'demote'])
        ->name('demote');
});
```

#### Step 3: Create Vue Page

**File:** `resources/js/Pages/Admin/PlatformAdmins/Index.vue`

```vue
<template>
    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Platform Admins</h1>
                <p class="mt-2 text-gray-600">Manage platform staff members</p>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="admin in admins" :key="admin.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ admin.email }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ admin.is_super_admin ? 'Super Admin' : 'Platform Admin' }}
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <button
                                    v-if="!admin.is_super_admin"
                                    @click="demote(admin)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Demote
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

export default {
    components: { AdminLayout },
    props: {
        admins: Array,
    },
    methods: {
        demote(admin) {
            if (!confirm(`Demote ${admin.email}?`)) return
            
            router.post(route('platform.admins.demote', admin.id), {}, {
                onSuccess: () => this.$page.props.flash.success && alert('Demoted'),
            })
        },
    },
}
</script>
```

#### Step 4: Update Dashboard Navigation

**File:** `resources/js/Pages/Admin/Dashboard.vue`

Change "Coming Soon" section (line ~127):
```vue
<!-- Manage Platform Admins -->
<Link
    :href="route('platform.admins.index')"
    class="rounded-lg bg-purple-50 p-4 border border-purple-200 hover:bg-purple-100 transition"
>
    <p class="font-medium text-purple-900">Manage Platform Admins</p>
    <p class="text-sm text-purple-700 mt-1">Add/remove platform staff members</p>
</Link>
```

---

## Testing Patterns

### Test Structure

```php
class PlatformAdminControllerTest extends TestCase
{
    use RefreshDatabase;
    
    private User $super_admin;
    private User $platform_admin;
    private User $org_admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->super_admin = User::factory()->create([
            'is_super_admin' => true
        ]);
        
        $this->platform_admin = User::factory()->create([
            'platform_role' => 'platform_admin'
        ]);
        
        $this->org_admin = User::factory()
            ->withOrganisation()
            ->create();
    }
    
    public function test_super_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->super_admin)
                        ->get(route('platform.dashboard'));
        
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) =>
            $page->component('Admin/Dashboard')
        );
    }
    
    public function test_org_admin_cannot_access_dashboard()
    {
        $response = $this->actingAs($this->org_admin)
                        ->get(route('platform.dashboard'));
        
        $response->assertStatus(403);
    }
    
    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get(route('platform.dashboard'));
        
        $response->assertRedirect(route('login'));
    }
}
```

---

## Best Practices

### ✅ DO

- **Test first:** Write tests before implementing features
- **Follow established patterns:** Use existing controllers/models as templates
- **Validate permissions:** Always check `isPlatformAdmin()` or similar
- **Respect state guards:** Validate election state before transitions
- **Document changes:** Update this guide with new extension patterns
- **Use `withoutGlobalScopes()`:** Platform features need full visibility
- **Provide audit trail:** Log all administrative actions
- **Use flash messages:** Feedback to admin after actions

### ❌ DON'T

- **Bypass authorization:** Don't assume user is admin
- **Skip state validation:** Don't allow invalid state transitions
- **Create cross-tenant queries:** Don't mix org data with platform data
- **Modify core schemas:** Don't add columns without migration
- **Forget error handling:** Don't leave exceptions unhandled
- **Skip tests:** Don't deploy untested features
- **Hard-code values:** Use environment variables and config
- **Create tight coupling:** Keep controllers, models, and views separate

---

## Debugging Tips

### Enable Query Logging

```php
// In controller
\Illuminate\Support\Facades\DB::enableQueryLog();

// After query
dd(\Illuminate\Support\Facades\DB::getQueryLog());
```

### Check Middleware Chain

```php
// routes/platform.php routes go through:
// 1. auth
// 2. verified
// 3. platform_admin ← Check this

// If 403, verify:
auth()->user()->isPlatformAdmin();  // Must be true
```

### Inspect Inertia Props

```javascript
// In Vue console
console.log(this.$page.props);  // See all server props
```

### Database Inspection

```bash
# Check user roles
php artisan tinker

User::where('email', 'admin@example.com')->first()->isPlatformAdmin();

# Check election states
Election::groupBy('state')->selectRaw('state, count(*) as total')->get();
```

---

## Performance Considerations

### N+1 Queries

```php
// BAD: N+1 queries
$elections = Election::all();
foreach ($elections as $e) {
    echo $e->organisation->name;  // Query per election!
}

// GOOD: Eager load
$elections = Election::with('organisation')->get();
foreach ($elections as $e) {
    echo $e->organisation->name;  // Single query
}
```

### Pagination

```php
// DON'T: Load all elections
$elections = Election::withoutGlobalScopes()->get();

// DO: Paginate
$elections = Election::withoutGlobalScopes()->paginate(25);
```

### Caching

```php
// Cache stats that don't change frequently
Cache::remember('pending_elections_count', 300, function () {
    return Election::where('state', 'pending_approval')->count();
});
```

---

## Related Documentation

- [Getting Started](./GETTING_STARTED.md) — User guide
- [Election Approval Workflow](./ELECTION_APPROVAL_WORKFLOW.md) — Approval process
- [Subscription Model](./SUBSCRIPTION_MODEL.md) — Free vs Paid
- [Architecture](./ARCHITECTURE.md) — Technical details

---

**Status:** Production Ready ✅  
**Last Updated:** April 28, 2026
