# **CLAUDE CLI PROMPT: SYSTEM ARCHITECTURE REORGANIZATION**

## **TARGET STATE:**
```
Current: /dashboard → Voter interface
Target: /dashboard → Role Selection Dashboard
        ├── /dashboard/admin → Admin Dashboard
        ├── /dashboard/commission → Commission Dashboard  
        └── /vote → Voter Dashboard (moved from /dashboard)
```

---

## **STEP 1: BACKEND PREPARATION**

```bash
# 1. Create new database migrations for role system
php artisan make:migration create_role_system_tables --create=role_system

# Expected Migration Structure:
# users table: add 'user_type' enum('admin','commission','voter','multi')
# organizations table (if not exists)
# user_organization_roles: pivot table with user_id, org_id, role_type
# elections table: add commission_members JSON column

# 2. Run seeders for testing
php artisan db:seed --class=RoleSystemSeeder

# 3. Create Role middleware
php artisan make:middleware CheckUserRole

# 4. Create controllers for each role
php artisan make:controller RoleSelectionController
php artisan make:controller AdminDashboardController
php artisan make:controller CommissionDashboardController
php artisan make:controller VoterDashboardController
```

---

## **STEP 2: ROUTE REORGANIZATION**

```php
// routes/web.php - NEW STRUCTURE

// Public routes (no auth)
Route::get('/', [LandingController::class, 'index']);
Route::get('/demo', [DemoController::class, 'index']);

// Authentication routes (keep existing)
Auth::routes();

// Authenticated users - START at role selection
Route::middleware(['auth'])->group(function () {
    
    // DEFAULT: Role selection dashboard
    Route::get('/dashboard', [RoleSelectionController::class, 'index'])
         ->name('role.selection');
    
    // ADMIN routes (protected by admin middleware)
    Route::prefix('dashboard/admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/elections', [AdminDashboardController::class, 'elections'])->name('admin.elections');
        Route::get('/voters', [AdminDashboardController::class, 'voters'])->name('admin.voters');
        // ... more admin routes
    });
    
    // COMMISSION routes (protected by commission middleware)  
    Route::prefix('dashboard/commission')->middleware(['role:commission'])->group(function () {
        Route::get('/', [CommissionDashboardController::class, 'index'])->name('commission.dashboard');
        Route::get('/election/{id}', [CommissionDashboardController::class, 'election'])->name('commission.election');
        // ... more commission routes
    });
    
    // VOTER routes (moved from /dashboard to /vote)
    Route::prefix('vote')->middleware(['role:voter'])->group(function () {
        Route::get('/', [VoterDashboardController::class, 'index'])->name('vote.dashboard');
        Route::get('/election/{id}', [VoterDashboardController::class, 'election'])->name('vote.election');
        Route::post('/vote/{id}', [VoterDashboardController::class, 'castVote'])->name('vote.cast');
        // ... existing voter routes moved here
    });
    
    // Role switching
    Route::post('/switch-role/{role}', [RoleSelectionController::class, 'switchRole'])
         ->name('role.switch');
});
```

---

## **STEP 3: FILE STRUCTURE REORGANIZATION**

```bash
# CURRENT STRUCTURE:
# resources/views/dashboard.blade.php ← Voter dashboard

# NEW STRUCTURE:
mkdir -p resources/views/role-selection
mkdir -p resources/views/admin
mkdir -p resources/views/commission  
mkdir -p resources/views/vote

# Move existing voter dashboard
mv resources/views/dashboard.blade.php resources/views/vote/dashboard.blade.php

# Create new role selection dashboard
touch resources/views/role-selection/index.blade.php

# Create admin dashboard
touch resources/views/admin/dashboard.blade.php

# Create commission dashboard  
touch resources/views/commission/dashboard.blade.php

# Update layouts
mkdir -p resources/views/layouts
touch resources/views/layouts/role-selection.blade.php
touch resources/views/layouts/admin.blade.php
touch resources/views/layouts/commission.blade.php
touch resources/views/layouts/vote.blade.php
```

---

## **STEP 4: VUE COMPONENT REORGANIZATION**

```bash
# Current: resources/js/Pages/Dashboard.vue ← Voter dashboard

# New structure:
mkdir -p resources/js/Pages/RoleSelection
mkdir -p resources/js/Pages/Admin
mkdir -p resources/js/Pages/Commission
mkdir -p resources/js/Pages/Vote

# Move existing voter dashboard
mv resources/js/Pages/Dashboard.vue resources/js/Pages/Vote/Dashboard.vue

# Create role selection component
touch resources/js/Pages/RoleSelection/Index.vue

# Create admin dashboard component  
touch resources/js/Pages/Admin/Dashboard.vue

# Create commission dashboard component
touch resources/js/Pages/Commission/Dashboard.vue

# Update Inertia routes in app.js
```

---

## **STEP 5: UPDATE INERTIA ROUTES**

```javascript
// resources/js/app.js - Update Inertia routes

import RoleSelection from './Pages/RoleSelection/Index.vue';
import AdminDashboard from './Pages/Admin/Dashboard.vue';
import CommissionDashboard from './Pages/Commission/Dashboard.vue';
import VoteDashboard from './Pages/Vote/Dashboard.vue'; // renamed from Dashboard

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    
    // Route mapping
    const routeMap = {
      'RoleSelection/Index': RoleSelection,
      'Admin/Dashboard': AdminDashboard,
      'Commission/Dashboard': CommissionDashboard,
      'Vote/Dashboard': VoteDashboard,
      // Keep existing mappings...
    };
    
    return routeMap[name] || pages[`./Pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    // ... existing setup
  },
});
```

---

## **STEP 6: MIDDLEWARE IMPLEMENTATION**

```php
<?php
// app/Http/Middleware/CheckUserRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user has ANY of the required roles
        $hasRole = false;
        foreach ($roles as $role) {
            if ($this->userHasRole($user, $role)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            // Redirect to role selection with error
            return redirect()->route('role.selection')
                ->with('error', 'You do not have access to this area');
        }
        
        return $next($request);
    }
    
    private function userHasRole($user, $role)
    {
        // Implementation depends on your role system
        // Could be: $user->user_type, $user->roles(), etc.
        return $user->hasRole($role);
    }
}
```

---

## **STEP 7: ROLE SELECTION CONTROLLER**

```php
<?php
// app/Http/Controllers/RoleSelectionController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RoleSelectionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user's available roles
        $availableRoles = $this->getAvailableRoles($user);
        
        // If only one role available, redirect directly
        if (count($availableRoles) === 1) {
            return $this->redirectToRole($availableRoles[0]);
        }
        
        // Get role-specific data for dashboard
        $data = [
            'userName' => $user->name,
            'availableRoles' => $availableRoles,
            'adminStats' => $this->getAdminStats($user),
            'commissionStats' => $this->getCommissionStats($user),
            'voterStats' => $this->getVoterStats($user),
            'recentActivities' => $this->getRecentActivities($user),
        ];
        
        return Inertia::render('RoleSelection/Index', $data);
    }
    
    public function switchRole(Request $request, $role)
    {
        // Validate role access
        $user = $request->user();
        $availableRoles = $this->getAvailableRoles($user);
        
        if (!in_array($role, $availableRoles)) {
            return back()->with('error', 'Invalid role selection');
        }
        
        // Store selected role in session
        session(['current_role' => $role]);
        
        return $this->redirectToRole($role);
    }
    
    private function redirectToRole($role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'commission' => redirect()->route('commission.dashboard'),
            'voter' => redirect()->route('vote.dashboard'),
            default => redirect()->route('role.selection'),
        };
    }
    
    // ... helper methods for stats and roles
}
```

---

## **STEP 8: UPDATE NAVIGATION LINKS**

```blade
{{-- Update all navigation links in your app --}}

{{-- Old: --}}
<a href="{{ route('dashboard') }}">Dashboard</a>

{{-- New: --}}
@auth
    <a href="{{ route('role.selection') }}">Dashboard</a>
@else
    <a href="{{ route('login') }}">Login</a>
@endauth
```

---

## **STEP 9: TESTING COMMANDS**

```bash
# Test the new flow
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Create test user with multiple roles
php artisan tinker
>>> $user = User::first();
>>> $user->assignRole('admin');
>>> $user->assignRole('voter');

# Test routes
curl -I http://localhost/dashboard
curl -I http://localhost/dashboard/admin
curl -I http://localhost/vote

# Run tests
php artisan test --testsuite=Feature --filter=RoleSystemTest
```

---

## **STEP 10: DEPLOYMENT CHECKLIST**

```markdown
# Deployment Checklist

## Database
- [ ] Run migrations for role system
- [ ] Back up existing data
- [ ] Test role assignments

## Code
- [ ] Move voter dashboard to /vote
- [ ] Create role selection dashboard
- [ ] Update all route references
- [ ] Test navigation flows

## Testing
- [ ] Test admin user flow
- [ ] Test commission user flow  
- [ ] Test voter user flow
- [ ] Test role switching
- [ ] Test unauthorized access

## Rollback Plan
- [ ] Keep backup of current /dashboard
- [ ] Document rollback SQL
- [ ] Test rollback procedure
```

---

## **EXECUTION ORDER:**

1. **Database First** (non-breaking): Add role columns, keep old data
2. **Create New Routes** (additive): New routes don't break old ones
3. **Move Voter Dashboard** (breaking): Update links after moving
4. **Test Thoroughly**: Each role flow
5. **Deploy**: During low-traffic period

---

## **CRITICAL NOTES:**

- **Preserve existing voter URLs**: Use 301 redirects from old /dashboard to new /vote
- **Maintain session data**: User's selected role should persist
- **Backward compatibility**: Existing users should still work
- **Error handling**: Clear messages for role access errors
- **Mobile responsive**: All new dashboards must work on mobile

**Execute commands in order. Test after each step. Backup before breaking changes.**