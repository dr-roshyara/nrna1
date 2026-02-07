# Three-Role Dashboard System - Developer Guide

## Table of Contents
1. [Architecture Overview](#architecture-overview)
2. [Current Implementation Status](#current-implementation-status)
3. [System Flow](#system-flow)
4. [Database Schema](#database-schema)
5. [File Structure](#file-structure)
6. [Authentication & Authorization](#authentication--authorization)
7. [Role System](#role-system)
8. [Frontend Components](#frontend-components)
9. [Translation System](#translation-system)
10. [What's Missing](#whats-missing)
11. [How to Complete Setup](#how-to-complete-setup)
12. [Testing the System](#testing-the-system)

---

## Architecture Overview

The Public Digit platform implements a **three-role dashboard system** that supports multi-language, multi-tenant voting operations. Users can have multiple roles and select which dashboard to access.

### Three Dashboard Roles

| Role | Purpose | Access Path | Data Scope |
|------|---------|-------------|-----------|
| **Admin** | Platform & organization management | `/dashboard/admin` | Organizations, users, system config |
| **Commission** | Election committee oversight | `/dashboard/commission` | Elections, votes, audit logs |
| **Voter** | Member voting interface | `/vote` | Active elections, voting history |

### Key Design Principles

- **Option C: Legacy-Aware Hybrid** - Respects existing `is_committee_member` logic while adding new role system
- **Multi-tenant**: Each tenant has isolated dashboards and data
- **Translation-First**: All Vue components use i18n (German, English, Nepali)
- **Role-Based Middleware**: Access controlled via `CheckUserRole` middleware
- **Session-Based Selection**: Users select their active role in session
- **DDD Architecture**: Following Domain-Driven Design patterns

---

## Current Implementation Status

### ✅ COMPLETED

#### Backend
- [x] Migration: `2026_02_07_131712_create_role_system_tables.php`
  - `organizations` table with multi-language support
  - `user_organization_roles` pivot table (admin/commission/voter roles)
  - `election_commission_members` pivot table (election-specific roles)
  - Safe up() and down() methods

- [x] Models
  - `Organization` model with relationships to users and elections
  - `User` model extended with:
    - `getDashboardRoles()` - Fetches all dashboard roles with caching
    - `hasDashboardRole($role)` - Checks for specific role
    - `isCommissionMemberForElection($electionId)` - Election-specific checks
    - `isOrganizationAdmin($organizationId)` - Organization admin verification

- [x] Middleware
  - `CheckUserRole` middleware validates role access
  - Supports role parameters: `'role:admin'`, `'role:commission'`, `'role:voter'`
  - Redirects to role selection if access denied

- [x] Controllers
  - `RoleSelectionController` - Entry point, role selection logic
  - `AdminDashboardController` - Organization and system statistics
  - `CommissionDashboardController` - Election-specific commission view
  - `VoterDashboardController` - Active elections and voting history

- [x] Routes (in `routes/web.php`)
  ```
  /dashboard/roles           → Role selection (role.selection)
  /switch-role/{role}        → Role switching (role.switch)
  /dashboard/admin           → Admin dashboard (admin.dashboard)
  /dashboard/commission      → Commission dashboard (commission.dashboard)
  /vote                      → Voter dashboard (vote.dashboard)
  ```

#### Frontend
- [x] Translation Files (all 3 languages: en, de, np)
  - `resources/js/locales/pages/RoleSelection/` - Role selection translations
  - `resources/js/locales/pages/Admin/` - Admin dashboard translations
  - `resources/js/locales/pages/Commission/` - Commission dashboard translations
  - `resources/js/locales/pages/Vote/Dashboard/` - Voter dashboard translations

- [x] Vue Components (all using `useI18n()` and `$t()`)
  - `resources/js/Pages/RoleSelection/Index.vue` - Role card selection
  - `resources/js/Pages/Admin/Dashboard.vue` - Organizations and stats
  - `resources/js/Pages/Commission/Dashboard.vue` - Elections and monitoring
  - `resources/js/Pages/Vote/Dashboard.vue` - Voting interface

- [x] i18n Configuration
  - Updated `resources/js/i18n.js` with all new translation imports
  - Registered under `pages.role-selection`, `pages.admin`, `pages.commission`, `pages.vote-dashboard`

### ❌ NOT YET COMPLETED

1. **Login Flow Integration**
   - No automatic redirect from login to `/dashboard/roles`
   - Currently redirects to `/election` (ElectionController::dashboard)
   - Need: Modify `RouteServiceProvider::HOME` or Fortify response handler

2. **RoleSelectionController Implementation**
   - `index()` method needs to fetch user's dashboard roles
   - Display available roles with statistics
   - Handle role-not-found scenario

3. **Session-Based Role Validation**
   - Need middleware to check if role is selected in session
   - Auto-redirect to role selection if missing
   - Validate role matches requested URL

4. **Database Seeding**
   - `RoleSystemSeeder` for creating test organizations and roles
   - Test data for demonstration

5. **Feature Tests**
   - Test role selection flow
   - Test access control (403 for unauthorized roles)
   - Test role switching
   - Test election-specific commission roles

6. **Frontend Build**
   - `npm run dev` or `npm run build` to compile assets

---

## System Flow

### User Login → Role Selection → Dashboard Access

```
┌─────────────────────────────────────────────────────────────┐
│                    USER LOGIN FLOW                          │
└─────────────────────────────────────────────────────────────┘

   1. User visits /login
      ↓
   2. Submits credentials (email, password, remember)
      ↓
   3. Fortify validates & authenticates
      ↓
   4. ⚠️ CURRENTLY REDIRECTS TO: /election (ElectionController::dashboard)
      SHOULD REDIRECT TO: /dashboard/roles (RoleSelectionController::index)
      ↓
   5. RoleSelectionController fetches user's dashboard roles
      - Calls: User->getDashboardRoles() [cached]
      - Returns: Array of roles user has access to
      ↓
   6. RoleSelection/Index.vue displays role cards
      - Admin: Platform & organization management
      - Commission: Election committee oversight
      - Voter: Member voting interface
      ↓
   7. User clicks role card (e.g., "Commission")
      ↓
   8. Submits to /switch-role/commission (POST)
      - Stores selected role in session: session('dashboard_role')
      - Redirects to /dashboard/commission
      ↓
   9. CommissionDashboardController@index
      - CheckUserRole middleware validates role
      - Verifies session['dashboard_role'] === 'commission'
      - Fetches user's elections & commission data
      ↓
  10. Commission/Dashboard.vue displays elections
      - Quick stats (active elections, votes cast, pending voters)
      - Elections with action buttons
      - All text translated (de, en, np)
```

### What Happens if User Tries Direct Access?

```
User tries: /dashboard/admin (without role selected in session)
   ↓
CheckUserRole middleware checks:
   1. User authenticated? ✓
   2. User has role 'admin' in database? ✓
   3. Session['dashboard_role'] === 'admin'? ✗
   ↓
Middleware options:
   - Option A: Redirect to /dashboard/roles (force re-selection)
   - Option B: Auto-select first available role (auto-redirect)

⚠️ CURRENT: Needs implementation in middleware logic
```

---

## Database Schema

### Table: `organizations`
```sql
CREATE TABLE organizations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('diaspora', 'ngo', 'professional', 'other') NOT NULL,
    settings JSON,                    -- Organization-specific settings
    languages JSON,                   -- Supported languages: ["en", "de", "np"]
    timestamps
);
```

### Table: `user_organization_roles`
```sql
CREATE TABLE user_organization_roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    organization_id BIGINT UNSIGNED NOT NULL,
    role ENUM('admin', 'commission', 'voter') NOT NULL,
    permissions JSON,
    timestamps

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    UNIQUE (user_id, organization_id, role)
);
```

### Table: `election_commission_members`
```sql
CREATE TABLE election_commission_members (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    permissions JSON,
    timestamps

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    UNIQUE (user_id, election_id)
);
```

### Extended: `elections` Table
```sql
ALTER TABLE elections ADD organization_id BIGINT UNSIGNED;
ALTER TABLE elections ADD FOREIGN KEY (organization_id) REFERENCES organizations(id);
```

---

## File Structure

### Backend Files Created

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── RoleSelectionController.php       [NEW] Entry point for role selection
│   │   ├── AdminDashboardController.php      [NEW] Admin dashboard logic
│   │   ├── CommissionDashboardController.php [NEW] Commission dashboard logic
│   │   └── VoterDashboardController.php      [NEW] Voter dashboard logic
│   ├── Middleware/
│   │   └── CheckUserRole.php                 [NEW] Role-based access control
│   └── Kernel.php                            [MODIFIED] Register 'role:*' middleware
├── Models/
│   ├── Organization.php                      [NEW] Organization model
│   ├── User.php                              [MODIFIED] Added role methods
│   └── ... (existing models)
└── ...

database/
└── migrations/
    └── 2026_02_07_131712_create_role_system_tables.php [NEW]

routes/
└── web.php                                   [MODIFIED] Added role-based routes
```

### Frontend Files Created

```
resources/js/
├── Pages/
│   ├── RoleSelection/
│   │   └── Index.vue                         [NEW] Role card selection
│   ├── Admin/
│   │   └── Dashboard.vue                     [NEW] Admin dashboard
│   ├── Commission/
│   │   └── Dashboard.vue                     [NEW] Commission dashboard
│   └── Vote/
│       └── Dashboard.vue                     [NEW] Voter dashboard
├── locales/pages/
│   ├── RoleSelection/
│   │   ├── en.json                           [NEW] English translations
│   │   ├── de.json                           [NEW] German translations
│   │   └── np.json                           [NEW] Nepali translations
│   ├── Admin/
│   │   ├── en.json                           [NEW]
│   │   ├── de.json                           [NEW]
│   │   └── np.json                           [NEW]
│   ├── Commission/
│   │   ├── en.json                           [NEW]
│   │   ├── de.json                           [NEW]
│   │   └── np.json                           [NEW]
│   └── Vote/Dashboard/
│       ├── en.json                           [NEW]
│       ├── de.json                           [NEW]
│       └── np.json                           [NEW]
└── i18n.js                                   [MODIFIED] Register new translations
```

---

## Authentication & Authorization

### User Model Methods

```php
// Get all dashboard roles for user (cached 60 min)
public function getDashboardRoles(): array
{
    return Cache::remember(
        "user_{$this->id}_dashboard_roles",
        now()->addMinutes(60),
        function () {
            // Returns: ['admin', 'commission', 'voter']
        }
    );
}

// Check if user has specific dashboard role
public function hasDashboardRole($role): bool
{
    return in_array($role, $this->getDashboardRoles());
}

// Check if user is organization admin
public function isOrganizationAdmin($organizationId): bool
{
    return $this->organizationRoles()
        ->where('organization_id', $organizationId)
        ->where('role', 'admin')
        ->exists();
}

// Check if user is commission member for election
public function isCommissionMemberForElection($electionId): bool
{
    return $this->electionCommissionRoles()
        ->where('election_id', $electionId)
        ->exists();
}
```

### Middleware: CheckUserRole

```php
// Location: app/Http/Middleware/CheckUserRole.php

// Usage in routes:
Route::get('/dashboard/admin', ...)
    ->middleware('role:admin');

Route::get('/dashboard/commission', ...)
    ->middleware('role:commission');

Route::get('/vote', ...)
    ->middleware('role:voter');

// The middleware:
// 1. Verifies user is authenticated
// 2. Checks user has the required role in database
// 3. Stores current_role in request for controller access
// 4. Redirects to role.selection if unauthorized (403)
```

### Special Case: Legacy Committee Members

From `is_committee_member` field in users table:

```php
// If user has is_committee_member = true, they automatically get
// commission dashboard access (backward compatibility)

// This is handled in User->getDashboardRoles():
if ($this->is_committee_member) {
    $roles[] = 'commission';
}
```

---

## Role System

### Role Hierarchy

```
┌────────────────────────────────────────┐
│ ADMIN (Highest Privilege)              │
├────────────────────────────────────────┤
│ • Manage organizations                 │
│ • Create/delete elections              │
│ • Manage users and roles               │
│ • View system analytics                │
│ • Manage committee assignments         │
└────────────────────────────────────────┘
           ↓
┌────────────────────────────────────────┐
│ COMMISSION (Election Management)       │
├────────────────────────────────────────┤
│ • Monitor elections                    │
│ • View vote counts                     │
│ • Manage pending voters                │
│ • View audit logs                      │
│ • Cannot modify election setup         │
└────────────────────────────────────────┘
           ↓
┌────────────────────────────────────────┐
│ VOTER (Member Participation)           │
├────────────────────────────────────────┤
│ • View assigned elections              │
│ • Cast votes                           │
│ • Verify votes                         │
│ • View voting history                  │
│ • Cannot manage anything               │
└────────────────────────────────────────┘
```

### Role Assignment

#### Method 1: Organization Admin Role
```php
// User assigned admin role for organization
$user->organizationRoles()->attach($organizationId, [
    'role' => 'admin',
    'permissions' => json_encode(['create_election', 'manage_users'])
]);
```

#### Method 2: Organization Commission Role
```php
// User assigned commission role for organization
$user->organizationRoles()->attach($organizationId, [
    'role' => 'commission',
    'permissions' => json_encode(['monitor_elections', 'view_votes'])
]);
```

#### Method 3: Election-Specific Commission
```php
// User assigned to election's commission
$user->electionCommissionRoles()->attach($electionId, [
    'permissions' => json_encode(['monitor', 'audit_log'])
]);
```

#### Method 4: Legacy Committee Member (Backward Compatible)
```php
// Existing users with is_committee_member = true auto-get commission access
$user->update(['is_committee_member' => true]);
// Now user->hasDashboardRole('commission') === true
```

---

## Frontend Components

### 1. RoleSelection/Index.vue
- **Purpose**: Let user select which dashboard to access
- **Data Props**:
  ```javascript
  {
    currentRole: String,
    organizations: Array,
    quickStats: Object
  }
  ```
- **Translation Keys**: `pages.role-selection.*`
- **Features**:
  - 3 role cards with emoji icons
  - Statistics for each role
  - Keyboard navigation support
  - Responsive grid layout

### 2. Admin/Dashboard.vue
- **Purpose**: Platform and organization management
- **Data Props**:
  ```javascript
  {
    currentRole: String,
    organizations: Array,      // { id, name, type }
    quickStats: Object         // { totalElections, activeElections, totalVoters, participationRate }
  }
  ```
- **Translation Keys**: `pages.admin.adminDashboard.*`
- **Features**:
  - Quick stats grid (4 cards)
  - Organizations grid display
  - Manage organization button

### 3. Commission/Dashboard.vue
- **Purpose**: Election committee oversight
- **Data Props**:
  ```javascript
  {
    currentRole: String,
    elections: Array,          // { id, title, status }
    quickStats: Object         // { activeElections, votesCast, pendingVoters, issues }
  }
  ```
- **Translation Keys**: `pages.commission.commissionDashboard.*`
- **Features**:
  - Quick stats grid (4 cards)
  - Elections list with status
  - Action buttons: Monitor, Manage, Audit Log

### 4. Vote/Dashboard.vue
- **Purpose**: Member voting interface
- **Data Props**:
  ```javascript
  {
    activeElections: Array,    // { id, title, type, status, can_vote }
    votingHistory: Array,      // { id, date, election, type }
    quickStats: Object         // { pending, cast, completed }
  }
  ```
- **Translation Keys**: `pages.vote-dashboard.voterDashboard.*`
- **Features**:
  - Quick stats grid (3 cards)
  - Active elections list with voting buttons
  - Voting history table

---

## Translation System

### Language Support
- **German (de)** - Default language, primary diaspora audience
- **English (en)** - International audience
- **Nepali (np)** - Local audience in Nepal

### Translation Architecture

All Vue components use Vue i18n:

```javascript
// In component script setup:
import { useI18n } from 'vue-i18n'
const { t: $t } = useI18n()

// In template:
{{ $t('pages.role-selection.welcome') }}
{{ $t('pages.admin.adminDashboard.title') }}
```

### Translation File Structure

```
resources/js/locales/pages/
├── RoleSelection/
│   ├── en.json → { welcome, selectRole, roles.*, stats.*, buttons.*, ... }
│   ├── de.json
│   └── np.json
├── Admin/
│   ├── en.json → { adminDashboard: { title, subtitle, stats, organizations, ... } }
│   ├── de.json
│   └── np.json
├── Commission/
│   ├── en.json → { commissionDashboard: { title, elections, buttons, ... } }
│   ├── de.json
│   └── np.json
└── Vote/Dashboard/
    ├── en.json → { voterDashboard: { title, elections, votingHistory, ... } }
    ├── de.json
    └── np.json
```

### i18n Registration

In `resources/js/i18n.js`:

```javascript
import voteDashboardDe from './locales/pages/Vote/Dashboard/de.json';
import voteDashboardEn from './locales/pages/Vote/Dashboard/en.json';
import voteDashboardNp from './locales/pages/Vote/Dashboard/np.json';

const messages = {
  de: {
    ...de,
    pages: {
      'vote-dashboard': voteDashboardDe,
      // ... other pages
    }
  },
  // en, np...
};
```

---

## What's Missing

### 🔴 CRITICAL - MUST IMPLEMENT

#### 1. Login Redirect Integration
**File**: `app/Providers/RouteServiceProvider.php`

Currently:
```php
public const HOME = '/election';  // Goes to ElectionController::dashboard
```

Should be:
```php
public const HOME = '/dashboard/roles';  // Goes to RoleSelectionController::index
```

**Why**: Users need to select their dashboard role after login.

#### 2. RoleSelectionController Implementation
**File**: `app/Http/Controllers/RoleSelectionController.php`

Current pseudo-code needs actual implementation:
```php
public function index(Request $request)
{
    $user = $request->user();

    // TODO: Fetch user's dashboard roles
    $dashboardRoles = $user->getDashboardRoles();

    // TODO: Calculate statistics for each role
    // admin: org count, election count
    // commission: active elections, pending voters
    // voter: pending votes, cast votes

    return Inertia::render('RoleSelection/Index', [
        'currentRole' => $request->session()->get('dashboard_role'),
        'organizations' => $user->organizations,
        'quickStats' => [...],
    ]);
}
```

#### 3. Role Session Persistence Middleware
**File**: Need new middleware or update `CheckUserRole`

Must:
1. Check if role selected in session: `session('dashboard_role')`
2. If missing, redirect to `/dashboard/roles`
3. If present, validate it matches route's required role
4. Store `$request->attributes->put('current_role', $role)`

#### 4. Switch Role Implementation
**File**: `app/Http/Controllers/RoleSelectionController.php`

```php
public function switchRole(Request $request, $role)
{
    $user = $request->user();

    // Verify user has this role
    if (!$user->hasDashboardRole($role)) {
        return redirect('role.selection')
            ->withErrors('Unauthorized role access');
    }

    // Store in session
    session(['dashboard_role' => $role]);

    // Redirect to appropriate dashboard
    return match($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'commission' => redirect()->route('commission.dashboard'),
        'voter' => redirect()->route('vote.dashboard'),
    };
}
```

### 🟡 IMPORTANT - SHOULD IMPLEMENT

#### 5. Database Seeder
**File**: `database/seeders/RoleSystemSeeder.php`

Should create:
- 3-5 test organizations
- 10-15 test users
- Assign users to organizations with different roles
- Create test elections
- Assign users to election commissions

#### 6. Feature Tests
**File**: `tests/Feature/RoleSystemTest.php`

Should test:
- User can see only roles they have
- User can switch roles
- Middleware blocks unauthorized access
- Session persists role selection
- Role selection displays correct statistics
- Each dashboard displays correct data

### 🟢 NICE TO HAVE - CAN IMPLEMENT LATER

#### 7. Role Management UI
Admin interface to:
- Assign roles to users
- Manage organization membership
- Assign users to election commissions

#### 8. Permission System
Fine-grained permissions:
- Admin: `create_election`, `manage_users`, `view_analytics`
- Commission: `monitor_votes`, `audit_log`, `manage_voters`
- Voter: `cast_vote`, `verify_vote`, `view_history`

---

## How to Complete Setup

### Step 1: Fix Login Redirect

**File**: `app/Providers/RouteServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path where users are redirected after login.
     */
    // CHANGED: From '/election' to '/dashboard/roles'
    public const HOME = '/dashboard/roles';

    // ... rest of service provider
}
```

### Step 2: Implement RoleSelectionController

**File**: `app/Http/Controllers/RoleSelectionController.php`

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RoleSelectionController extends Controller
{
    /**
     * Show role selection dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dashboardRoles = $user->getDashboardRoles();

        // Get statistics for each role
        $stats = [];
        if (in_array('admin', $dashboardRoles)) {
            $stats['admin'] = $this->getAdminStats($user);
        }
        if (in_array('commission', $dashboardRoles)) {
            $stats['commission'] = $this->getCommissionStats($user);
        }
        if (in_array('voter', $dashboardRoles)) {
            $stats['voter'] = $this->getVoterStats($user);
        }

        return Inertia::render('RoleSelection/Index', [
            'currentRole' => $request->session()->get('dashboard_role'),
            'organizations' => $user->organizations,
            'availableRoles' => $dashboardRoles,
            'quickStats' => $stats,
        ]);
    }

    /**
     * Switch user's active role
     */
    public function switchRole(Request $request, $role)
    {
        $user = $request->user();

        // Validate user has this role
        if (!$user->hasDashboardRole($role)) {
            return redirect()->route('role.selection')
                ->with('error', 'You do not have access to this role');
        }

        // Store role in session
        $request->session()->put('dashboard_role', $role);

        // Redirect to appropriate dashboard
        return $this->redirectToRole($role);
    }

    /**
     * Redirect to the appropriate dashboard
     */
    private function redirectToRole($role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'commission' => redirect()->route('commission.dashboard'),
            'voter' => redirect()->route('vote.dashboard'),
            default => redirect()->route('role.selection'),
        };
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats($user)
    {
        return [
            'organizations' => $user->organizations->count(),
            'elections' => $user->organizations
                ->flatMap(fn($org) => $org->elections)
                ->count(),
            'activeElections' => $user->organizations
                ->flatMap(fn($org) => $org->elections->where('status', 'active'))
                ->count(),
        ];
    }

    /**
     * Get commission statistics
     */
    private function getCommissionStats($user)
    {
        $elections = $user->electionCommissionRoles()
            ->with('election')
            ->get()
            ->pluck('election');

        return [
            'activeElections' => $elections->where('status', 'active')->count(),
            'pendingVoters' => $elections->sum('pending_voters_count'),
            'votesCast' => $elections->sum('votes_count'),
        ];
    }

    /**
     * Get voter statistics
     */
    private function getVoterStats($user)
    {
        $pending = $user->voterRegistrations()
            ->where('status', '!=', 'voted')
            ->count();

        $cast = $user->voterRegistrations()
            ->where('status', 'voted')
            ->count();

        return [
            'pendingVotes' => $pending,
            'castVotes' => $cast,
            'completed' => false,
        ];
    }
}
```

### Step 3: Create RoleSystemSeeder

**File**: `database/seeders/RoleSystemSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test organizations
        $orgs = Organization::factory(3)->create();

        // Assign users to organizations with different roles
        $users = User::limit(10)->get();

        foreach ($users as $index => $user) {
            $orgs->each(function ($org) use ($user, $index) {
                $roles = match($index % 3) {
                    0 => ['admin'],
                    1 => ['commission'],
                    default => ['voter'],
                };

                foreach ($roles as $role) {
                    $user->organizationRoles()->attach($org->id, [
                        'role' => $role,
                        'permissions' => json_encode([]),
                    ]);
                }
            });
        }
    }
}
```

### Step 4: Run Migration & Seeder

```bash
# Run migration
php artisan migrate

# Run seeder
php artisan db:seed --class=RoleSystemSeeder

# Or in one command
php artisan migrate --seed
```

### Step 5: Build Frontend Assets

```bash
# Development build with hot reload
npm run dev

# Production build
npm run build
```

### Step 6: Test the System

```bash
# Start development server
php artisan serve

# In another terminal
npm run dev
```

Visit: `http://localhost:8000/login`
- Login with test credentials
- Should redirect to `/dashboard/roles`
- See role selection cards
- Click a role → redirects to that dashboard
- All text should be translated

---

## Testing the System

### Manual Testing Checklist

- [ ] User can log in
- [ ] After login, redirected to `/dashboard/roles`
- [ ] Role selection displays available roles
- [ ] Can click role card and switch roles
- [ ] Admin role shows organizations and elections
- [ ] Commission role shows commission-specific elections
- [ ] Voter role shows available elections to vote in
- [ ] All text displays in correct language (de, en, np)
- [ ] Middleware blocks access to unauthorized roles
- [ ] Role persists in session when navigating
- [ ] Can switch roles by clicking different card
- [ ] Legacy `is_committee_member = true` users can access commission

### Feature Test Template

```php
// tests/Feature/RoleSystemTest.php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;

class RoleSystemTest extends TestCase
{
    public function test_user_can_access_role_selection_after_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard/roles');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('RoleSelection/Index')
        );
    }

    public function test_user_can_switch_roles()
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();

        $user->organizationRoles()->attach($org->id, ['role' => 'admin']);

        $response = $this->actingAs($user)
            ->post('/switch-role/admin');

        $this->assertEquals('admin', session('dashboard_role'));
        $response->assertRedirect('/dashboard/admin');
    }

    public function test_user_cannot_access_unauthorized_role()
    {
        $user = User::factory()->create();
        // Don't assign any roles

        $response = $this->actingAs($user)
            ->get('/dashboard/admin');

        $response->assertStatus(403);
    }
}
```

---

## Architecture Decision Records (ADR)

### ADR-001: Option C - Legacy-Aware Hybrid Role System

**Status**: Accepted

**Context**:
- System has existing `is_committee_member` field
- Need new role system for multiple dashboards
- Must maintain backward compatibility

**Decision**:
Implement Option C (Hybrid) which:
1. Respects existing `is_committee_member` logic
2. Adds new `user_organization_roles` pivot table
3. Maps both to getDashboardRoles()
4. No data migration required

**Consequences**:
- ✅ Backward compatible
- ✅ No data loss
- ❌ Dual role systems (temporary)
- ❌ Migration plan needed for future

---

## Glossary

| Term | Definition |
|------|-----------|
| **Dashboard Role** | High-level access role: admin, commission, or voter |
| **Organization Role** | Granular role within an organization (can be extended) |
| **Committee Member** | Legacy term for users with election oversight (commission equivalent) |
| **Role Selection** | Initial page after login where user chooses which dashboard |
| **Session Role** | Currently selected dashboard role stored in session |
| **Voter Registration** | Record of user's eligibility to vote in specific election |

---

## References

- Laravel Inertia.js: https://inertiajs.com/
- Vue i18n: https://vue-i18n.intlify.dev/
- Laravel Fortify: https://laravel.com/docs/fortify
- Tailwind CSS: https://tailwindcss.com/

---

**Last Updated**: 2026-02-07
**Status**: Development in Progress
**Next Phase**: Complete missing components and create comprehensive tests
