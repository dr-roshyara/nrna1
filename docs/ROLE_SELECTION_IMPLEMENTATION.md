# 🎯 Role Selection Dashboard — Implementation Guide

**Complete step-by-step guide for implementing the three-role system**

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Database Setup](#database-setup)
3. [Backend Implementation](#backend-implementation)
4. [Frontend Implementation](#frontend-implementation)
5. [Route Configuration](#route-configuration)
6. [Testing & Validation](#testing--validation)
7. [Deployment Checklist](#deployment-checklist)

---

## Architecture Overview

### Current Flow
```
Login → /dashboard (Voter Portal)
```

### New Flow
```
Login → /dashboard (Role Selection)
        ├─ Admin Role → /dashboard/admin
        ├─ Commission Role → /dashboard/commission/{election_id}
        └─ Voter Role → /vote
```

---

## Database Setup

### Step 1: Create Role System Migration

```bash
php artisan make:migration create_user_roles_table
```

**File:** `database/migrations/[date]_create_user_roles_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role columns to users table if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'primary_role')) {
                $table->enum('primary_role', ['admin', 'commission', 'voter'])
                    ->default('voter')
                    ->after('email');
            }
        });

        // Create user_roles pivot table for many-to-many relationships
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['admin', 'commission', 'voter']);
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Ensure user can't have duplicate roles per organization
            $table->unique(['user_id', 'role', 'organization_id']);
        });

        // Add commission members to elections table
        Schema::table('elections', function (Blueprint $table) {
            if (!Schema::hasColumn('elections', 'commission_members')) {
                $table->json('commission_members')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('primary_role');
        });

        Schema::dropIfExists('user_roles');

        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('commission_members');
        });
    }
};
```

### Step 2: Create Organizations Table (if not exists)

```bash
php artisan make:migration create_organizations_table
```

**File:** `database/migrations/[date]_create_organizations_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('type', ['ngo', 'diaspora', 'cultural', 'professional']);
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

---

## Backend Implementation

### Step 1: Create Models

**File:** `app/Models/UserRole.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRole extends Model
{
    protected $fillable = ['user_id', 'role', 'organization_id', 'metadata'];
    protected $casts = ['metadata' => 'json'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
```

**File:** `app/Models/Organization.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'description', 'type', 'settings'];
    protected $casts = ['settings' => 'json'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'metadata')
            ->withTimestamps();
    }

    public function elections(): HasMany
    {
        return $this->hasMany(Election::class);
    }
}
```

**File:** Update `app/Models/User.php`

```php
<?php

namespace App\Models;

// ... existing imports ...
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    // ... existing code ...

    public function roles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)
            ->withPivot('role', 'metadata')
            ->withTimestamps();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role, ?Organization $organization = null): bool
    {
        return $this->roles()
            ->where('role', $role)
            ->when($organization, function ($query) use ($organization) {
                return $query->where('organization_id', $organization->id);
            })
            ->exists();
    }

    /**
     * Get all available roles for user
     */
    public function getAvailableRoles(): array
    {
        return $this->roles()
            ->distinct('role')
            ->pluck('role')
            ->toArray();
    }
}
```

### Step 2: Create Middleware

**File:** `app/Http/Middleware/CheckUserRole.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has any of the required roles
        $hasRequiredRole = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                $hasRequiredRole = true;
                break;
            }
        }

        if (!$hasRequiredRole) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
```

Register middleware in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... existing middleware ...
    'role' => \App\Http\Middleware\CheckUserRole::class,
];
```

### Step 3: Create Controllers

**File:** `app/Http/Controllers/RoleSelectionController.php`

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RoleSelectionController extends Controller
{
    /**
     * Show role selection dashboard
     */
    public function index(Request $request): Response | RedirectResponse
    {
        $user = $request->user();
        $availableRoles = $user->getAvailableRoles();

        // If user has only one role, redirect directly
        if (count($availableRoles) === 1) {
            return $this->redirectToRole($availableRoles[0]);
        }

        // Get statistics for each role
        $adminStats = [
            'organizations' => $user->organizations()->count(),
            'activeElections' => $user->organizations()
                ->whereHas('elections', function ($q) {
                    $q->where('status', 'active');
                })->count(),
            'totalMembers' => 0, // Calculate from organization members
        ];

        $commissionStats = [
            'elections' => $user->roles()
                ->where('role', 'commission')
                ->count(),
            'votesCast' => 0, // Calculate from elections
            'participationRate' => 0,
        ];

        $voterStats = [
            'pending' => 0, // Calculate from open elections
            'cast' => 0,    // Calculate from votes table
        ];

        return Inertia::render('RoleSelection/Index', [
            'userName' => $user->name,
            'userHasAdminRole' => $user->hasRole('admin'),
            'userHasCommissionRole' => $user->hasRole('commission'),
            'userHasVoterRole' => $user->hasRole('voter'),
            'adminStats' => $adminStats,
            'commissionStats' => $commissionStats,
            'voterStats' => $voterStats,
            'userOrgs' => $user->organizations()
                ->with('pivot')
                ->get()
                ->map(fn($org) => [
                    'id' => $org->id,
                    'name' => $org->name,
                    'role' => ucfirst($org->pivot->role),
                ])
                ->toArray(),
            'commissionElections' => [], // Load elections for commission
            'voterElections' => [], // Load elections for voter
            'recentActivities' => [], // Load activity log
        ]);
    }

    /**
     * Switch to a different role
     */
    public function switchRole(Request $request, string $role): RedirectResponse
    {
        $user = $request->user();

        if (!$user->hasRole($role)) {
            return back()->with('error', 'You do not have access to this role.');
        }

        session(['current_role' => $role]);

        return $this->redirectToRole($role);
    }

    /**
     * Redirect to role-specific dashboard
     */
    private function redirectToRole(string $role): RedirectResponse
    {
        session(['current_role' => $role]);

        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'commission' => redirect()->route('commission.dashboard'),
            'voter' => redirect()->route('vote.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}
```

**File:** `app/Http/Controllers/AdminDashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Admin/Dashboard', [
            'organizations' => $user->organizations()
                ->where('pivot.role', 'admin')
                ->get(),
            'activeElections' => [], // Load admin's elections
            'statistics' => [], // Load admin stats
        ]);
    }

    /**
     * List elections for admin
     */
    public function elections(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Admin/Elections', [
            'elections' => $user->organizations()
                ->whereHas('elections', function ($q) {
                    $q->where('status', 'active');
                })
                ->with('elections')
                ->get(),
        ]);
    }
}
```

**File:** `app/Http/Controllers/CommissionDashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class CommissionDashboardController extends Controller
{
    /**
     * Show commission dashboard
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Commission/Dashboard', [
            'assignedElections' => [], // Load elections where user is commission member
            'liveStats' => [], // Real-time voting stats
        ]);
    }
}
```

---

## Frontend Implementation

### Step 1: Create Role Selection Component

**File:** `resources/js/Pages/RoleSelection/Index.vue`

```vue
<template>
  <div class="role-selection-page">
    <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />

    <main class="role-selection-container">
      <!-- Welcome Section -->
      <section class="welcome-section">
        <h1>Welcome back, {{ userName }}!</h1>
        <p>Choose your role to access the appropriate tools:</p>
      </section>

      <!-- Role Cards Grid -->
      <section class="role-cards-section">
        <div class="role-cards-grid">
          <!-- Admin Card -->
          <div
            v-if="userHasAdminRole"
            class="role-card admin-card"
            @click="selectRole('admin')"
            role="radio"
            :aria-checked="selectedRole === 'admin'"
            tabindex="0"
            @keydown.enter="selectRole('admin')"
            @keydown.space="selectRole('admin')"
          >
            <div class="role-icon">👑</div>
            <h2>Organization Administrator</h2>
            <p class="role-description">
              Create elections, manage your organization
            </p>
            <div class="role-stats">
              <div class="stat">
                <span class="stat-label">Organizations:</span>
                <span class="stat-value">{{ adminStats.organizations }}</span>
              </div>
              <div class="stat">
                <span class="stat-label">Active Elections:</span>
                <span class="stat-value">{{ adminStats.activeElections }}</span>
              </div>
            </div>
            <button
              @click.stop="goToAdminDashboard"
              class="action-btn primary"
            >
              Enter Admin Dashboard
            </button>
          </div>

          <!-- Commission Card -->
          <div
            v-if="userHasCommissionRole"
            class="role-card commission-card"
            @click="selectRole('commission')"
            role="radio"
            :aria-checked="selectedRole === 'commission'"
            tabindex="0"
            @keydown.enter="selectRole('commission')"
            @keydown.space="selectRole('commission')"
          >
            <div class="role-icon">⚖️</div>
            <h2>Election Commission</h2>
            <p class="role-description">
              Monitor elections, ensure fairness
            </p>
            <div class="role-stats">
              <div class="stat">
                <span class="stat-label">Elections:</span>
                <span class="stat-value">{{ commissionStats.elections }}</span>
              </div>
              <div class="stat">
                <span class="stat-label">Votes Cast:</span>
                <span class="stat-value">{{ commissionStats.votesCast }}</span>
              </div>
            </div>
            <button
              @click.stop="goToCommissionDashboard"
              class="action-btn primary"
            >
              Enter Commission Dashboard
            </button>
          </div>

          <!-- Voter Card -->
          <div
            v-if="userHasVoterRole"
            class="role-card voter-card"
            @click="selectRole('voter')"
            role="radio"
            :aria-checked="selectedRole === 'voter'"
            tabindex="0"
            @keydown.enter="selectRole('voter')"
            @keydown.space="selectRole('voter')"
          >
            <div class="role-icon">👤</div>
            <h2>Voter / Member</h2>
            <p class="role-description">
              Cast votes, verify your choice
            </p>
            <div class="role-stats">
              <div class="stat">
                <span class="stat-label">Pending:</span>
                <span class="stat-value">{{ voterStats.pending }}</span>
              </div>
              <div class="stat">
                <span class="stat-label">Votes Cast:</span>
                <span class="stat-value">{{ voterStats.cast }}</span>
              </div>
            </div>
            <button
              @click.stop="goToVotingPortal"
              class="action-btn primary"
            >
              Vote Now
            </button>
          </div>
        </div>
      </section>
    </main>

    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3-vue3';
import ElectionHeader from '@/Components/Header/ElectionHeader.vue';
import PublicDigitFooter from '@/Jetstream/PublicDigitFooter.vue';

const props = defineProps({
  userName: String,
  userHasAdminRole: Boolean,
  userHasCommissionRole: Boolean,
  userHasVoterRole: Boolean,
  adminStats: Object,
  commissionStats: Object,
  voterStats: Object,
});

const page = usePage();
const selectedRole = ref(null);

const selectRole = (role) => {
  selectedRole.value = role;
};

const goToAdminDashboard = () => {
  window.location.href = route('admin.dashboard');
};

const goToCommissionDashboard = () => {
  window.location.href = route('commission.dashboard');
};

const goToVotingPortal = () => {
  window.location.href = route('vote.dashboard');
};
</script>

<style scoped>
.role-selection-page {
  min-height: 100vh;
  background: #f8fafc;
}

.role-selection-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 4rem 2rem;
}

.welcome-section {
  text-align: center;
  margin-bottom: 4rem;
}

.welcome-section h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 1rem;
}

.welcome-section p {
  font-size: 1.125rem;
  color: #64748b;
}

.role-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.role-card {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.role-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.role-card:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.role-icon {
  font-size: 3rem;
  text-align: center;
  margin-bottom: 1rem;
}

.role-card h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.role-description {
  color: #64748b;
  margin-bottom: 1.5rem;
}

.role-stats {
  background: #f8fafc;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

.stat {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  font-size: 0.875rem;
}

.stat-label {
  color: #64748b;
}

.stat-value {
  font-weight: 600;
  color: #1e293b;
}

.action-btn {
  width: 100%;
  padding: 0.75rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-btn.primary {
  background: #3b82f6;
  color: white;
}

.action-btn.primary:hover {
  background: #2563eb;
}

.action-btn.primary:active {
  transform: scale(0.98);
}

@media (max-width: 768px) {
  .role-selection-container {
    padding: 2rem 1rem;
  }

  .welcome-section h1 {
    font-size: 1.875rem;
  }

  .role-cards-grid {
    grid-template-columns: 1fr;
  }
}
</style>
```

---

## Route Configuration

**File:** `routes/web.php` - Update authentication routes

```php
<?php

use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommissionDashboardController;
use App\Http\Controllers\VoterDashboardController;
use Illuminate\Support\Facades\Route;

// Existing public routes...

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // ROLE SELECTION (Default dashboard)
    Route::get('/dashboard', [RoleSelectionController::class, 'index'])
        ->name('dashboard');

    Route::post('/switch-role/{role}', [RoleSelectionController::class, 'switchRole'])
        ->name('role.switch');

    // ADMIN ROUTES
    Route::prefix('dashboard/admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/elections', [AdminDashboardController::class, 'elections'])->name('elections');
        Route::get('/voters', [AdminDashboardController::class, 'voters'])->name('voters');
    });

    // COMMISSION ROUTES
    Route::prefix('dashboard/commission')->middleware(['role:commission'])->name('commission.')->group(function () {
        Route::get('/', [CommissionDashboardController::class, 'index'])->name('dashboard');
        Route::get('/{election}', [CommissionDashboardController::class, 'election'])->name('election');
    });

    // VOTER ROUTES (moved from /dashboard to /vote)
    Route::prefix('vote')->middleware(['role:voter'])->name('vote.')->group(function () {
        Route::get('/', [VoterDashboardController::class, 'index'])->name('dashboard');
        Route::get('/election/{id}', [VoterDashboardController::class, 'election'])->name('election');
        Route::post('/election/{id}', [VoterDashboardController::class, 'castVote'])->name('cast');
    });

    // Redirect old /dashboard/voter to /vote
    Route::redirect('/dashboard/voter', '/vote');
});
```

---

## Testing & Validation

### Test Checklist

```bash
# 1. Database migrations
php artisan migrate
✓ Check user_roles table created
✓ Check organizations table created
✓ Check primary_role added to users

# 2. Middleware
php artisan tinker
>>> $user = User::first();
>>> $user->hasRole('admin');  // Should test role checking

# 3. Routes
php artisan route:list | grep dashboard
php artisan route:list | grep admin
php artisan route:list | grep commission
php artisan route:list | grep vote

# 4. Frontend
npm run dev
# Test at: http://localhost:8000/dashboard
# Should see role selection page

# 5. Role access
# Test logging in as different users with different roles
# Test switching between roles
# Test unauthorized access
```

---

## Deployment Checklist

```markdown
- [ ] Backup production database
- [ ] Run migrations on staging
- [ ] Test all role flows on staging
- [ ] Create admin users in production
- [ ] Run migrations in production
- [ ] Test role selection dashboard
- [ ] Test admin dashboard
- [ ] Test commission dashboard
- [ ] Test voter portal
- [ ] Verify old /dashboard links redirect to /vote
- [ ] Monitor error logs
- [ ] Gather user feedback
```

---

**Implementation Status:** Ready to begin
**Priority:** High (Phase 1 for user experience)
**Estimated Time:** 5-7 days for complete implementation

