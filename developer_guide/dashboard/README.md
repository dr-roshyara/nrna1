# Three-Role Dashboard System - Developer Guide

## Overview

The **Three-Role Dashboard System** is a comprehensive role-based access control architecture for the Public Digit platform. It supports three distinct user types:

1. **Admin** - Organization managers and administrators
2. **Commission** - Election monitors and supervisors
3. **Voter** - Regular participants casting votes

Additionally, the system maintains **backward compatibility** with legacy roles for existing deployments.

## Quick Reference

### Dashboard Routes

| Role | URL | Purpose |
|------|-----|---------|
| New User | `/dashboard/welcome` | Onboarding & organization setup |
| Multi-Role | `/dashboard/roles` | Select which role to use |
| Admin | `/dashboard/admin` | Manage organizations & elections |
| Commission | `/dashboard/commission` | Monitor elections & votes |
| Voter | `/vote` | Cast votes & view results |
| Legacy | `/dashboard` | Backward compatibility |

### Entry Point: Login Response

**File:** `app/Http/Responses/LoginResponse.php`

The `LoginResponse` class determines where users are redirected after successful login using this priority:

```
Priority 1: First-time users → /dashboard/welcome
Priority 2: Multi-role users → /dashboard/roles
Priority 3: Single-role users → Direct dashboard
Priority 4: Legacy role users → Backward compatible routes
Priority 5: Everyone else → /dashboard
```

## Key Components

### Backend (Laravel)

- **LoginResponse** - Post-login routing logic
- **RoleSelectionController** - Multi-role selection interface
- **AdminDashboardController** - Admin dashboard
- **CommissionDashboardController** - Commission dashboard
- **VoterDashboardController** - Voter dashboard (aliased as `/vote`)
- **CheckUserRole Middleware** - Role-based access control
- **User Model** - Dashboard roles and role detection logic

### Frontend (Vue 3)

- **Welcome/Dashboard.vue** - Onboarding page for new users
- **RoleSelection/Index.vue** - Multi-role selection UI
- **Admin/Dashboard.vue** - Admin interface
- **Commission/Dashboard.vue** - Commission interface
- **Vote/Dashboard.vue** - Voter interface

### Database

- **users** - User accounts with legacy flags (`is_voter`, `is_committee_member`)
- **organizations** - Organization records
- **user_organization_roles** - Mapping users to organizations with roles (NEW)
- **election_commission_members** - Commission member assignments (NEW)
- **elections** - Election records (linked to organizations)

## How It Works

### 1. User Logs In

```php
// app/Http/Responses/LoginResponse.php
public function toResponse($request)
{
    $user = $request->user();

    // Step 1: Check if first-time user
    if ($this->isFirstTimeUser($user)) {
        return redirect()->route('dashboard.welcome');
    }

    // Step 2: Get all dashboard roles
    $dashboardRoles = $user->getDashboardRoles();

    // Step 3: Route based on role count
    if (count($dashboardRoles) > 1) {
        return redirect()->route('role.selection');
    }

    if (count($dashboardRoles) === 1) {
        return redirect to appropriate dashboard based on role;
    }

    // Step 4-5: Legacy fallback
}
```

### 2. Role Detection

The system detects roles from multiple sources:

```php
// app/Models/User.php
public function getDashboardRoles()
{
    // Check new system: user_organization_roles table
    $orgRoles = DB::table('user_organization_roles')
        ->where('user_id', $this->id)
        ->pluck('role');

    // Check legacy: Spatie roles
    $spaiteRoles = $this->roles->pluck('name');

    // Merge and cache for 60 minutes
    return Cache::remember(
        "user_{$this->id}_dashboard_roles",
        3600,
        function() use ($orgRoles, $spaiteRoles) { ... }
    );
}
```

### 3. Middleware Protection

Routes are protected with role-based middleware:

```php
// routes/web.php
Route::prefix('dashboard/admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });
```

The middleware checks:
1. User is authenticated
2. User has the required role
3. If not, redirects to role selection

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── RoleSelectionController.php
│   │   ├── AdminDashboardController.php
│   │   ├── CommissionDashboardController.php
│   │   ├── VoterDashboardController.php
│   │   └── WelcomeDashboardController.php
│   ├── Middleware/
│   │   └── CheckUserRole.php
│   └── Responses/
│       └── LoginResponse.php
├── Models/
│   ├── User.php (extended with getDashboardRoles)
│   ├── Organization.php (new)
│   └── ...
└── ...

resources/
├── js/
│   ├── Pages/
│   │   ├── Welcome/
│   │   │   └── Dashboard.vue
│   │   ├── RoleSelection/
│   │   │   └── Index.vue
│   │   ├── Admin/
│   │   │   └── Dashboard.vue
│   │   ├── Commission/
│   │   │   └── Dashboard.vue
│   │   └── Vote/
│   │       └── Dashboard.vue
│   ├── Components/
│   │   └── Header/
│   │       └── ElectionHeader.vue
│   └── locales/
│       └── pages/
│           ├── Welcome/Dashboard/ (EN/DE/NP)
│           ├── RoleSelection/ (EN/DE/NP)
│           ├── Admin/ (EN/DE/NP)
│           ├── Commission/ (EN/DE/NP)
│           └── Vote/Dashboard/ (EN/DE/NP)

routes/
└── web.php (role-based dashboard routes)

database/
└── migrations/
    └── 2026_02_07_131712_create_role_system_tables.php
```

## Next Steps

1. **User Management** - Assign roles to users via admin interface
2. **Organization Creation** - Implement wizard for new organizations
3. **Election Setup** - Create election management interface
4. **Voting Interface** - Implement secure voting mechanisms
5. **Analytics** - Add dashboards with election metrics

## Documentation Files

- **ARCHITECTURE.md** - Detailed architecture and flow diagrams
- **IMPLEMENTATION.md** - Code locations and implementation details
- **USER_JOURNEYS.md** - Real-world user journey examples
- **DATABASE_SCHEMA.md** - Database table structure
- **EXTENDING.md** - How to add new dashboards or roles
- **TESTING.md** - Testing strategies and test scenarios

## Key Design Principles

✅ **Priority-Based Routing** - Clear order of decision logic
✅ **Backward Compatibility** - Legacy users continue working
✅ **Explicit Role Sources** - Roles from database, not magic methods
✅ **Translation-First** - All UI text supports EN/DE/NP
✅ **Middleware Protection** - Routes enforced at HTTP layer
✅ **Caching** - Dashboard roles cached for performance
✅ **German GDPR** - Privacy-first design, German servers

## Support & Questions

For questions about:
- **Architecture** - See `ARCHITECTURE.md`
- **Code locations** - See `IMPLEMENTATION.md`
- **User flows** - See `USER_JOURNEYS.md`
- **Database** - See `DATABASE_SCHEMA.md`
- **Adding features** - See `EXTENDING.md`
