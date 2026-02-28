# Three-Role Dashboard System - Implementation Guide

## Code Locations Quick Reference

### Backend Controllers

| Controller | File | Purpose |
|-----------|------|---------|
| LoginResponse | `app/Http/Responses/LoginResponse.php` | Post-login routing |
| RoleSelectionController | `app/Http/Controllers/RoleSelectionController.php` | Multi-role selection |
| AdminDashboardController | `app/Http/Controllers/AdminDashboardController.php` | Admin dashboard |
| CommissionDashboardController | `app/Http/Controllers/CommissionDashboardController.php` | Commission dashboard |
| VoterDashboardController | `app/Http/Controllers/VoterDashboardController.php` | Voter dashboard |
| WelcomeDashboardController | `app/Http/Controllers/WelcomeDashboardController.php` | Welcome/onboarding |

### Models

| Model | File | Key Methods |
|-------|------|-------------|
| User | `app/Models/User.php` | `getDashboardRoles()`, `hasDashboardRole($role)`, `isOrganizationAdmin($orgId)` |
| organisation | `app/Models/organisation.php` | `hasAdmin($userId)`, `members()`, `elections()` |

### Middleware

| Middleware | File | Purpose |
|-----------|------|---------|
| CheckUserRole | `app/Http/Middleware/CheckUserRole.php` | Role-based route protection |

### Frontend Components

| Component | File | Purpose |
|-----------|------|---------|
| Welcome Dashboard | `resources/js/Pages/Welcome/Dashboard.vue` | First-time user onboarding |
| Role Selection | `resources/js/Pages/RoleSelection/Index.vue` | Multi-role selection UI |
| Admin Dashboard | `resources/js/Pages/Admin/Dashboard.vue` | Admin interface |
| Commission Dashboard | `resources/js/Pages/Commission/Dashboard.vue` | Commission interface |
| Voter Dashboard | `resources/js/Pages/Vote/Dashboard.vue` | Voter interface |

### Translations

| Location | Scope | Languages |
|----------|-------|-----------|
| `resources/js/locales/pages/Welcome/Dashboard/` | Welcome dashboard | EN/DE/NP |
| `resources/js/locales/pages/RoleSelection/` | Role selection | EN/DE/NP |
| `resources/js/locales/pages/Admin/` | Admin dashboard | EN/DE/NP |
| `resources/js/locales/pages/Commission/` | Commission dashboard | EN/DE/NP |
| `resources/js/locales/pages/Vote/Dashboard/` | Voter dashboard | EN/DE/NP |

### Routes

| File | Key Routes |
|------|-----------|
| `routes/web.php` | All three-role dashboard routes (lines 222-250) |

### Database Migration

| File | Purpose |
|------|---------|
| `database/migrations/2026_02_07_131712_create_role_system_tables.php` | Creates organisation, user_organization_roles, election_commission_members tables |

## Key Implementation Details

### 1. First-Time User Detection

**File:** `app/Http/Responses/LoginResponse.php:123-168`

```php
private function isFirstTimeUser($user): bool
{
    // Account must be recent (within 7 days)
    if ($user->created_at->diffInDays(now()) > 7) {
        return false;
    }

    // Check if user has organisation roles (new system)
    $hasOrgRoles = \DB::table('user_organization_roles')
        ->where('user_id', $user->id)
        ->exists();

    if ($hasOrgRoles) {
        return false;
    }

    // Check if user is commission member (new system)
    $hasCommissionMembership = \DB::table('election_commission_members')
        ->where('user_id', $user->id)
        ->exists();

    if ($hasCommissionMembership) {
        return false;
    }

    // Check legacy roles
    if ($user->is_voter || $user->hasRole('admin') || $user->hasRole('election_officer')) {
        return false;
    }

    // If user has no roles/organizations/commission membership, they're a first-time user
    return true;
}
```

**Criteria for First-Time User:**
- Account created within 7 days
- No organisation roles (new system)
- No commission membership (new system)
- No voter status or legacy roles

### 2. Dashboard Role Detection

**File:** `app/Models/User.php` (approximately 150-200 lines from top)

```php
public function getDashboardRoles()
{
    // Cache with 60-minute TTL
    return Cache::remember(
        "user_{$this->id}_dashboard_roles",
        3600,
        function () {
            // Get roles from new system
            $orgRoles = \DB::table('user_organization_roles')
                ->where('user_id', $this->id)
                ->distinct()
                ->pluck('role')
                ->toArray();

            // Get legacy Spatie roles
            $legacyRoles = $this->roles->pluck('name')->toArray();

            // Merge and unique
            return array_unique(array_merge($orgRoles, $legacyRoles));
        }
    );
}

public function hasDashboardRole($role): bool
{
    return in_array($role, $this->getDashboardRoles());
}
```

**Why Direct Query?**
- `pluck('pivot.role')` doesn't work properly in Cache::remember closure
- Direct query ensures correct data retrieval
- 60-minute cache prevents excessive queries

### 3. Role-Based Routing

**File:** `routes/web.php` (lines 222-250)

```php
Route::middleware(['auth'])->group(function () {
    // Welcome dashboard (first-time users / onboarding)
    Route::get('/dashboard/welcome', [WelcomeDashboardController::class, 'index'])
         ->name('dashboard.welcome');

    // Role selection dashboard (entry point for multi-role users)
    Route::get('/dashboard/roles', [RoleSelectionController::class, 'index'])
         ->name('role.selection');

    // Role switching
    Route::post('/switch-role/{role}', [RoleSelectionController::class, 'switchRole'])
         ->name('role.switch');

    // Admin dashboard (requires admin role)
    Route::prefix('dashboard/admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Commission dashboard (requires commission role)
    Route::prefix('dashboard/commission')->middleware(['role:commission'])->group(function () {
        Route::get('/', [CommissionDashboardController::class, 'index'])->name('commission.dashboard');
    });

    // Voter dashboard (requires voter role)
    Route::prefix('vote')->middleware(['role:voter'])->group(function () {
        Route::get('/', [VoterDashboardController::class, 'index'])->name('vote.dashboard');
    });
});
```

**Key Points:**
- All routes require `auth` middleware
- Role-specific routes use `role:{role}` middleware
- `role.selection` is NOT role-protected (for multi-role users to access)
- `role:admin`, `role:commission`, `role:voter` are custom middleware

### 4. Custom Role Middleware

**File:** `app/Http/Middleware/CheckUserRole.php`

```php
public function handle($request, Closure $next, $role)
{
    $user = Auth::user();

    if (!$user || !$user->hasDashboardRole($role)) {
        return redirect()->route('role.selection');
    }

    // Store current role for later use
    $request->attributes->set('current_role', $role);

    return $next($request);
}
```

**Registration in `app/Http/Kernel.php`:**

```php
protected $routeMiddleware = [
    // ... other middleware
    'role' => \App\Http\Middleware\CheckUserRole::class,
];
```

### 5. Welcome Dashboard Controller

**File:** `app/Http/Controllers/WelcomeDashboardController.php`

```php
public function index()
{
    $user = Auth::user();

    return Inertia::render('Welcome/Dashboard', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'userCreatedAt' => $user->created_at,
    ]);
}
```

**Props Passed to Vue:**
- `userName` - For personalized greeting
- `userEmail` - For user identification
- `userCreatedAt` - For age validation

### 6. Role Selection Controller

**File:** `app/Http/Controllers/RoleSelectionController.php`

```php
public function index()
{
    $user = Auth::user();
    $roles = $user->getDashboardRoles();

    return Inertia::render('RoleSelection/Index', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'availableRoles' => $roles,
        'adminStats' => $this->getAdminStats($user),
        'commissionStats' => $this->getCommissionStats($user),
        'voterStats' => $this->getVoterStats($user),
    ]);
}

public function switchRole($role)
{
    $user = Auth::user();

    if (!$user->hasDashboardRole($role)) {
        return back()->with('error', 'Unauthorized role');
    }

    session(['current_role' => $role]);

    return match($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'commission' => redirect()->route('commission.dashboard'),
        'voter' => redirect()->route('vote.dashboard'),
        default => redirect()->route('role.selection'),
    };
}
```

### 7. organisation Model

**File:** `app/Models/organisation.php`

```php
class organisation extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'languages'
    ];

    protected $casts = [
        'languages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function members()
    {
        return $this->belongsToMany(User::class, 'user_organization_roles')
            ->withPivot('role');
    }

    public function admins()
    {
        return $this->members()->wherePivot('role', 'admin');
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }
}
```

## Step-by-Step: Adding a User to a Role

### Step 1: Create organisation (if needed)

```php
$org = organisation::create([
    'name' => 'Example organisation',
    'slug' => 'example-org',
    'languages' => ['en', 'de', 'np']
]);
```

### Step 2: Assign User to organisation with Role

```php
DB::table('user_organization_roles')->insert([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'admin',  // or 'commission', 'voter'
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Step 3: Clear User's Role Cache

```php
Cache::forget("user_{$user->id}_dashboard_roles");
```

### Step 4: Verify Role Assignment

```php
$roles = $user->getDashboardRoles();  // Should include 'admin'
```

## API Endpoints (To Be Implemented)

### POST /api/organizations

Create new organisation

```php
// Request
{
    "name": "organisation Name",
    "slug": "org-slug",
    "languages": ["en", "de", "np"]
}

// Response
{
    "id": 1,
    "name": "organisation Name",
    "slug": "org-slug",
    "created_at": "2026-02-07..."
}
```

### POST /api/organizations/{org}/members

Add member to organisation

```php
// Request
{
    "user_id": 5,
    "role": "admin"  // or 'commission', 'voter'
}

// Response
{
    "user_id": 5,
    "organisation_id": 1,
    "role": "admin"
}
```

### GET /api/organizations/{org}/members

List organisation members

```php
// Response
[
    {
        "id": 5,
        "name": "User Name",
        "email": "user@example.com",
        "role": "admin",
        "joined_at": "2026-02-07..."
    }
]
```

## Debugging Tips

### Check User's Roles

```php
// In tinker or command
$user = User::find(5);
$roles = $user->getDashboardRoles();
dd($roles);  // Should show array of roles
```

### Clear Cache

```php
// For specific user
Cache::forget("user_{$user->id}_dashboard_roles");

// For all users
Cache::flush();
```

### Test First-Time User Detection

```php
// Create test user
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),  // Will be < 7 days
]);

// In LoginResponse
$isFirstTime = $this->isFirstTimeUser($user);  // Should be true
```

### Verify Route Protection

```bash
# Try accessing admin dashboard as voter
curl -b "LARAVEL_SESSION=..." http://localhost:8000/dashboard/admin

# Should redirect to /dashboard/roles
```

## Performance Optimization

### Current Optimizations

✅ Dashboard roles cached for 60 minutes
✅ Database indexes on user_id, organisation_id
✅ Unique constraint on user_org_role combo
✅ Middleware runs before controller

### Future Optimizations

- Add Redis for distributed caching
- Implement user_organization_roles index on role
- Cache election commission members
- Async role verification for admin bulk operations
- Database connection pooling for high-concurrency

## Security Implementation

### Current Security

✅ CSRF protection on forms
✅ Session-based authentication
✅ Role verification at middleware level
✅ Database constraints for data integrity
✅ No direct role assignment from user input

### Future Security Enhancements

- Rate limiting on role.switch endpoint
- Audit logging for role changes
- Two-factor authentication for admin users
- Session expiration on role change
- IP whitelisting for organisation admins
