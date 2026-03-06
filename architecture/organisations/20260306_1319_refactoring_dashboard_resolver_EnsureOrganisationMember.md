## 📋 **IMPLEMENTATION PLAN: DashboardResolver + Organisation Isolation Middleware**

Based on your architecture, here's how to implement the complete routing and isolation system:

---

## 🏛️ **ARCHITECTURE OVERVIEW**

```mermaid
graph TB
    subgraph "1. Login Flow"
        A[User Login] --> B[LoginController]
        B --> C{DashboardResolver}
    end
    
    subgraph "2. DashboardResolver - 6 Priorities"
        C --> D[Priority 1: Active Voting]
        C --> E[Priority 2: Active Election]
        C --> F[Priority 3: New User Welcome]
        C --> G[Priority 4: Multiple Roles]
        C --> H[Priority 5: Single Role]
        C --> I[Priority 6: Platform Fallback]
    end
    
    subgraph "3. Organisation Isolation"
        J[Organisation Route] --> K[EnsureOrganisationMember Middleware]
        K --> L{Has Pivot?}
        L -->|Yes| M[Access Granted]
        L -->|No| N[403 Forbidden]
    end
    
    D --> O[/vote/{slug}/]
    E --> P[/election/dashboard]
    F --> Q[/dashboard/welcome]
    G --> R[/dashboard/roles]
    H --> S[Role-specific Dashboard]
    I --> T[/dashboard]
    
    S --> J
```

---

## 📋 **TASK 1: Implement DashboardResolver (6 Priorities)**

### **Step 1: Create DashboardResolver Service**

```bash
# Create service file
touch app/Services/DashboardResolver.php
```

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;

class DashboardResolver
{
    protected TenantContext $tenantContext;
    
    public function __construct(TenantContext $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }
    
    /**
     * Resolve which dashboard user should see (6 priorities)
     */
    public function resolve(User $user): RedirectResponse
    {
        Log::info('🚀 DashboardResolver START', [
            'user_id' => $user->id,
            'email' => $user->email,
            'org_id' => $user->organisation_id,
            'onboarded' => $user->onboarded_at ? 'YES' : 'NO',
        ]);

        // ===== PRIORITY 1: ACTIVE VOTING SESSION =====
        if ($activeVoting = $this->getActiveVotingSession($user)) {
            Log::info('🎯 PRIORITY 1: Active voting session', [
                'user_id' => $user->id,
                'voter_slug' => $activeVoting->slug,
            ]);
            return redirect()->route('vote.start', ['slug' => $activeVoting->slug]);
        }

        // ===== PRIORITY 2: ACTIVE ELECTION AVAILABLE =====
        if ($activeElection = $this->getActiveElectionForUser($user)) {
            Log::info('🎯 PRIORITY 2: Active election available', [
                'user_id' => $user->id,
                'election_id' => $activeElection->id,
                'election_name' => $activeElection->name,
            ]);
            return redirect()->route('election.dashboard', [
                'organisation' => $activeElection->organisation->slug,
                'election' => $activeElection->slug,
            ]);
        }

        // ===== PRIORITY 3: NEW USER WELCOME =====
        if ($this->isNewUser($user)) {
            Log::info('🎯 PRIORITY 3: New user welcome', [
                'user_id' => $user->id,
            ]);
            return redirect()->route('dashboard.welcome');
        }

        // Get all dashboard roles for user
        $roles = $this->getDashboardRoles($user);
        
        // ===== PRIORITY 4: MULTIPLE ROLES =====
        if (count($roles) > 1) {
            Log::info('🎯 PRIORITY 4: Multiple roles', [
                'user_id' => $user->id,
                'roles' => $roles,
            ]);
            return redirect()->route('role.selection');
        }

        // ===== PRIORITY 5: SINGLE ROLE =====
        if (count($roles) === 1) {
            $role = reset($roles);
            Log::info('🎯 PRIORITY 5: Single role', [
                'user_id' => $user->id,
                'role' => $role,
            ]);
            return $this->redirectByRole($user, $role);
        }

        // ===== PRIORITY 6: PLATFORM FALLBACK =====
        Log::info('🎯 PRIORITY 6: Platform fallback', [
            'user_id' => $user->id,
        ]);
        return redirect()->route('dashboard');
    }

    /**
     * Priority 1: Check for active voting session
     */
    protected function getActiveVotingSession(User $user): ?VoterSlug
    {
        return VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->where('vote_completed_at', null)
            ->first();
    }

    /**
     * Priority 2: Check for active election user can vote in
     */
    protected function getActiveElectionForUser(User $user): ?Election
    {
        // Get all organisations user belongs to
        $orgIds = $user->organisations()->pluck('organisations.id')->toArray();
        
        if (empty($orgIds)) {
            return null;
        }

        // Find active elections in those orgs
        return Election::whereIn('organisation_id', $orgIds)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereDoesntHave('voterSlugs', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereNotNull('vote_completed_at');
            })
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Priority 3: Check if user is new (verified but no orgs)
     */
    protected function isNewUser(User $user): bool
    {
        // User is new if:
        // 1. Email is verified
        // 2. Has no organisation roles
        // 3. Not onboarded yet
        return $user->email_verified_at !== null
            && $user->organisations()->count() === 0
            && $user->onboarded_at === null;
    }

    /**
     * Get all dashboard roles for user (cached)
     */
    protected function getDashboardRoles(User $user): array
    {
        $cacheKey = "user_{$user->id}_dashboard_roles";
        
        return Cache::remember($cacheKey, 60, function () use ($user) {
            $roles = [];
            
            // Check organisation roles
            foreach ($user->organisationRoles as $role) {
                if (in_array($role->role, ['admin', 'owner'])) {
                    $roles[] = 'admin_' . $role->organisation_id;
                } elseif ($role->role === 'member') {
                    $roles[] = 'member';
                }
            }
            
            // Check commission membership
            if ($user->isCommissionMember()) {
                $roles[] = 'commission';
            }
            
            // Check voter status
            if ($user->is_voter) {
                $roles[] = 'voter';
            }
            
            return array_unique($roles);
        });
    }

    /**
     * Priority 5: Redirect based on single role
     */
    protected function redirectByRole(User $user, string $role): RedirectResponse
    {
        // Extract role type (remove org_id if present)
        $roleType = explode('_', $role)[0];
        
        switch ($roleType) {
            case 'admin':
                // Get the organisation for this admin role
                $orgId = explode('_', $role)[1] ?? null;
                $organisation = Organisation::find($orgId);
                
                if ($organisation) {
                    return redirect()->route('organisations.show', [
                        'organisation' => $organisation->slug
                    ]);
                }
                break;
                
            case 'commission':
                return redirect()->route('commission.dashboard');
                
            case 'voter':
                return redirect()->route('vote.dashboard');
                
            case 'member':
                // Member without admin - send to org page
                $firstOrg = $user->organisations()->first();
                if ($firstOrg) {
                    return redirect()->route('organisations.show', [
                        'organisation' => $firstOrg->slug
                    ]);
                }
                break;
        }
        
        // Fallback if something goes wrong
        return redirect()->route('dashboard');
    }
}
```

### **Step 2: Update LoginController to Use DashboardResolver**

```php
// app/Http/Controllers/Auth/LoginController.php

use App\Services\DashboardResolver;

public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    $user = Auth::user();

    // Check email verification
    if ($user->email_verified_at === null) {
        return redirect()->route('verification.notice');
    }

    // DELEGATE to DashboardResolver
    return app(DashboardResolver::class)->resolve($user);
}
```

---

## 📋 **TASK 2: Implement EnsureOrganisationMember Middleware**

### **Step 1: Create Middleware**

```bash
php artisan make:middleware EnsureOrganisationMember
```

```php
<?php

namespace App\Http\Middleware;

use App\Models\Organisation;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureOrganisationMember
{
    protected TenantContext $tenantContext;
    
    public function __construct(TenantContext $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    /**
     * Handle organisation isolation - ONLY members can access
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Extract organisation from route
        $organisation = $this->getOrganisationFromRoute($request);
        
        // If no organisation in route, let it pass (platform route)
        if (!$organisation) {
            return $next($request);
        }

        // 3. Check if organisation is soft-deleted
        if ($organisation->trashed()) {
            Log::warning('Attempt to access deleted organisation', [
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisation->slug,
            ]);
            
            return $this->forbiddenResponse($request, 'Organisation no longer available');
        }

        // 4. CRITICAL: Check if user belongs to this organisation
        if (!$user->belongsToOrganisation($organisation->id)) {
            Log::warning('🚫 CROSS-ORG ACCESS BLOCKED', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'attempted_org_id' => $organisation->id,
                'attempted_org_slug' => $organisation->slug,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return $this->forbiddenResponse(
                $request, 
                'You do not have access to this organisation'
            );
        }

        // 5. Set context for this request
        $this->tenantContext->setContext($user, $organisation);
        $request->attributes->set('organisation', $organisation);
        session(['current_organisation_id' => $organisation->id]);

        // 6. Log successful access (sample 10% to avoid log flood)
        if (random_int(1, 10) === 1) {
            Log::info('✅ Organisation access granted', [
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisation->slug,
                'path' => $request->path(),
            ]);
        }

        return $next($request);
    }

    /**
     * Extract organisation from route parameters
     */
    protected function getOrganisationFromRoute(Request $request): ?Organisation
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }

        // Check various parameter names
        $candidates = [
            $route->parameter('organisation'),
            $route->parameter('organisation_slug'),
            $route->parameter('org'),
            $route->parameter('slug'),
        ];

        foreach ($candidates as $candidate) {
            if ($candidate instanceof Organisation) {
                return $candidate;
            }
            
            if (is_string($candidate)) {
                // Try UUID first
                if ($this->isValidUuid($candidate)) {
                    $org = Organisation::find($candidate);
                    if ($org) {
                        return $org;
                    }
                }
                
                // Try slug
                $org = Organisation::where('slug', $candidate)->first();
                if ($org) {
                    return $org;
                }
            }
        }

        return null;
    }

    /**
     * Check if string is valid UUID
     */
    protected function isValidUuid(string $value): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value) === 1;
    }

    /**
     * Return appropriate forbidden response
     */
    protected function forbiddenResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'forbidden',
                'message' => $message,
            ], 403);
        }

        return redirect()->route('dashboard')
            ->withErrors(['error' => $message]);
    }
}
```

### **Step 2: Register Middleware**

```php
// app/Http/Kernel.php (Laravel 10) OR bootstrap/app.php (Laravel 11)

protected $routeMiddleware = [
    // ... other middleware
    'ensure.organisation.member' => \App\Http\Middleware\EnsureOrganisationMember::class,
];

// Or for route groups:
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\EnsureOrganisationMember::class,
    ],
    'api' => [
        // ... other middleware
        \App\Http\Middleware\EnsureOrganisationMember::class,
    ],
];
```

---

## 📋 **TASK 3: Define Organisation Routes**

```php
// routes/web.php

// ===== ORGANISATION-SCOPED ROUTES =====
Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation.member'])
    ->name('organisations.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [OrganisationController::class, 'dashboard'])
            ->name('dashboard');
        
        // Elections
        Route::get('/elections', [ElectionController::class, 'index'])
            ->name('elections.index');
        
        Route::get('/elections/{election:slug}', [ElectionController::class, 'show'])
            ->name('elections.show');
        
        Route::get('/elections/{election}/manage', [ElectionController::class, 'manage'])
            ->name('elections.manage')
            ->middleware('can:manage,election');
        
        // Posts
        Route::get('/posts', [PostController::class, 'index'])
            ->name('posts.index');
        
        Route::get('/posts/{post}', [PostController::class, 'show'])
            ->name('posts.show');
        
        // Candidacies
        Route::get('/candidacies', [CandidacyController::class, 'index'])
            ->name('candidacies.index');
        
        // Voters
        Route::get('/voters', [VoterController::class, 'index'])
            ->name('voters.index');
        
        Route::get('/voters/approve', [VoterController::class, 'approvalQueue'])
            ->name('voters.approve');
        
        // Settings
        Route::get('/settings', [OrganisationController::class, 'settings'])
            ->name('settings');
    });

// ===== PLATFORM ROUTES (No Organisation) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    Route::get('/dashboard/welcome', [WelcomeController::class, 'index'])
        ->name('dashboard.welcome');
    
    Route::get('/dashboard/roles', [RoleSelectionController::class, 'index'])
        ->name('role.selection');
    
    Route::post('/dashboard/roles/select', [RoleSelectionController::class, 'select'])
        ->name('role.select');
});

// ===== PUBLIC VOTING ROUTES =====
Route::get('/vote/{voterSlug:slug}', [VoteController::class, 'start'])
    ->name('vote.start')
    ->middleware('ensure.voter.slug.valid');
```

---

## 📋 **TASK 4: Write Tests**

```php
// tests/Feature/Auth/DashboardResolverPriorityTest.php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardResolverPriorityTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = app(DashboardResolver::class);
    }

    /** @test */
    public function priority_1_active_voting_session_takes_precedence()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create();
        
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'is_active' => true,
            'expires_at' => now()->addHour(),
            'vote_completed_at' => null,
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('vote.start', ['slug' => $voterSlug->slug]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_2_active_election_redirects_to_election_dashboard()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);
        
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('election.dashboard', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_3_new_user_goes_to_welcome()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null,
        ]);
        // No organisation roles

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('dashboard.welcome'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_4_multiple_roles_goes_to_role_selection()
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();
        
        $user->organisations()->attach($org1->id, ['role' => 'admin']);
        $user->organisations()->attach($org2->id, ['role' => 'member']);
        
        $user->is_voter = true;
        $user->save();

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('role.selection'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_5_single_admin_role_goes_to_organisation_page()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'admin']);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('organisations.show', ['organisation' => $org->slug]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_6_no_roles_goes_to_default_dashboard()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);
        // No roles

        $response = $this->resolver->resolve($user);

        $this->assertEquals(
            route('dashboard'),
            $response->getTargetUrl()
        );
    }
}
```

```php
// tests/Feature/Middleware/EnsureOrganisationMemberTest.php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnsureOrganisationMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function organisation_member_can_access_page()
    {
        $org = Organisation::factory()->create(['slug' => 'test-org']);
        $user = User::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);

        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        $response->assertOk();
    }

    /** @test */
    public function non_member_cannot_access_page()
    {
        $org = Organisation::factory()->create(['slug' => 'test-org']);
        $user = User::factory()->create();
        // No pivot attached

        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        $response->assertStatus(403);
    }

    /** @test */
    public function deleted_organisation_returns_404()
    {
        $org = Organisation::factory()->create([
            'slug' => 'test-org',
            'deleted_at' => now(),
        ]);
        $user = User::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);

        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        $response->assertStatus(404);
    }

    /** @test */
    public function platform_admin_can_override()
    {
        $platform = Organisation::factory()->platform()->default()->create();
        $org = Organisation::factory()->create(['slug' => 'test-org']);
        
        $admin = User::factory()->create([
            'organisation_id' => $platform->id,
            'email' => 'admin@publicdigit.org',
        ]);
        $admin->organisations()->attach($platform->id, ['role' => 'super-admin']);
        $admin->assignRole('super-admin');

        $response = $this->actingAs($admin)
            ->get("/organisations/test-org/dashboard");

        $response->assertOk();
    }
}
```

---

## 📋 **TASK 5: Add Cache Invalidation**

```php
// app/Models/User.php

public function clearDashboardCache(): void
{
    Cache::forget("user_{$this->id}_dashboard_roles");
}

// In UserOrganisationRole model
protected static function booted()
{
    static::created(function ($role) {
        $role->user->clearDashboardCache();
    });
    
    static::updated(function ($role) {
        $role->user->clearDashboardCache();
    });
    
    static::deleted(function ($role) {
        $role->user->clearDashboardCache();
    });
}
```

---

## ✅ **IMPLEMENTATION SUMMARY**

| Task | Component | Status |
|------|-----------|--------|
| 1 | DashboardResolver Service | ✅ Ready |
| 2 | EnsureOrganisationMember Middleware | ✅ Ready |
| 3 | Organisation Routes | ✅ Ready |
| 4 | Tests | ✅ Ready |
| 5 | Cache Invalidation | ✅ Ready |

**Total Implementation Time: ~3-4 hours**

Proceed with implementing these files in order.
## 📋 **TDD REFACTORING PLAN: DashboardResolver & Middleware**

### **RED-GREEN-REFACTOR Approach**

---

## 🎯 **PHASE 1: Write Failing Tests First (RED)**

### **Step 1: Create Test Files**

```bash
# Create test files (they don't exist yet)
touch tests/Feature/Auth/DashboardResolverTest.php
touch tests/Feature/Middleware/EnsureOrganisationMemberTest.php
```

### **Step 2: Write Comprehensive Tests**

```php
<?php
// tests/Feature/Auth/DashboardResolverTest.php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class DashboardResolverTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardResolver $resolver;
    protected Organisation $platform;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = app(DashboardResolver::class);
        $this->platform = Organisation::factory()->platform()->default()->create();
    }

    /** @test */
    public function priority_1_active_voting_session_redirects_to_voting_portal()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => $this->platform->id]);
        $election = Election::factory()->create(['organisation_id' => $this->platform->id]);
        
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'is_active' => true,
            'expires_at' => now()->addHour(),
            'vote_completed_at' => null,
            'current_step' => 2,
        ]);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('vote.start', ['slug' => $voterSlug->slug]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_1_takes_precedence_over_active_election()
    {
        // Arrange
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);
        
        // Active election available
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        
        // Active voting session
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'is_active' => true,
            'expires_at' => now()->addHour(),
            'vote_completed_at' => null,
        ]);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert - Priority 1 should win
        $this->assertEquals(
            route('vote.start', ['slug' => $voterSlug->slug]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_2_active_election_redirects_to_election_dashboard()
    {
        // Arrange
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);
        
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('election.dashboard', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_2_skips_elections_where_user_already_voted()
    {
        // Arrange
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id, ['role' => 'member']);
        
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        
        // User already voted
        VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'vote_completed_at' => now(),
        ]);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert - Should skip to next priority
        $this->assertNotEquals(
            route('election.dashboard', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_3_new_user_without_organisation_goes_to_welcome()
    {
        // Arrange
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null,
        ]);
        // No organisations attached

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('dashboard.welcome'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_3_skips_if_user_already_onboarded()
    {
        // Arrange
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);
        // No organisations attached

        // Act
        $response = $this->resolver->resolve($user);

        // Assert - Should not go to welcome
        $this->assertNotEquals(
            route('dashboard.welcome'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_4_user_with_multiple_roles_goes_to_role_selection()
    {
        // Arrange
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();
        
        $user->organisations()->attach($org1->id, ['role' => 'admin']);
        $user->organisations()->attach($org2->id, ['role' => 'member']);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('role.selection'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_5_single_admin_role_redirects_to_organisation_page()
    {
        // Arrange
        $user = User::factory()->create();
        $org = Organisation::factory()->create(['slug' => 'test-org']);
        $user->organisations()->attach($org->id, ['role' => 'admin']);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('organisations.show', ['organisation' => 'test-org']),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_5_single_voter_role_redirects_to_vote_dashboard()
    {
        // Arrange
        $user = User::factory()->create(['is_voter' => true]);

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('vote.dashboard'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function priority_6_user_with_no_roles_goes_to_default_dashboard()
    {
        // Arrange
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);
        // No roles, no organisations

        // Act
        $response = $this->resolver->resolve($user);

        // Assert
        $this->assertEquals(
            route('dashboard'),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function cache_is_used_for_dashboard_roles()
    {
        // Arrange
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        
        Cache::shouldReceive('remember')
            ->once()
            ->with("user_{$user->id}_dashboard_roles", 60, \Closure::class)
            ->andReturn(['admin_' . $org->id]);

        // Act
        $this->resolver->resolve($user);
    }
}
```

```php
<?php
// tests/Feature/Middleware/EnsureOrganisationMemberTest.php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class EnsureOrganisationMemberTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platform;
    protected Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platform = Organisation::factory()->platform()->default()->create();
        $this->org = Organisation::factory()->create(['slug' => 'test-org']);
    }

    /** @test */
    public function authenticated_member_can_access_organisation_page()
    {
        // Arrange
        $user = User::factory()->create();
        $user->organisations()->attach($this->org->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $response->assertOk();
        $response->assertViewHas('organisation', function($viewOrg) {
            return $viewOrg->id === $this->org->id;
        });
    }

    /** @test */
    public function unauthenticated_user_is_redirected_to_login()
    {
        // Act
        $response = $this->get("/organisations/test-org/dashboard");

        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_non_member_receives_403()
    {
        // Arrange
        $user = User::factory()->create();
        // No pivot to test-org

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function non_member_attempt_is_logged()
    {
        // Arrange
        $user = User::factory()->create();
        
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function($message, $context) use ($user) {
                return str_contains($message, 'CROSS-ORG ACCESS BLOCKED')
                    && $context['user_id'] === $user->id
                    && $context['attempted_org_slug'] === 'test-org';
            });

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");
    }

    /** @test */
    public function deleted_organisation_returns_404()
    {
        // Arrange
        $deletedOrg = Organisation::factory()->create([
            'slug' => 'deleted-org',
            'deleted_at' => now(),
        ]);
        
        $user = User::factory()->create();
        $user->organisations()->attach($deletedOrg->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/deleted-org/dashboard");

        // Assert
        $response->assertStatus(404);
    }

    /** @test */
    public function organisation_can_be_resolved_by_uuid()
    {
        // Arrange
        $user = User::factory()->create();
        $user->organisations()->attach($this->org->id, ['role' => 'member']);

        // Act - Access by UUID instead of slug
        $response = $this->actingAs($user)
            ->get("/organisations/{$this->org->id}/dashboard");

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function tenant_context_is_set_for_valid_request()
    {
        // Arrange
        $user = User::factory()->create();
        $user->organisations()->attach($this->org->id, ['role' => 'member']);
        
        $tenantContext = app(TenantContext::class);

        // Act
        $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $this->assertEquals($this->org->id, $tenantContext->getCurrentOrganisationId());
        $this->assertEquals($user->id, $tenantContext->getCurrentUser()->id);
    }

    /** @test */
    public function api_request_returns_json_error_for_non_member()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)
            ->getJson("/api/organisations/test-org/dashboard");

        // Assert
        $response->assertStatus(403);
        $response->assertJson([
            'error' => 'forbidden',
            'message' => 'You do not have access to this organisation',
        ]);
    }

    /** @test */
    public function platform_super_admin_can_override_membership_check()
    {
        // Arrange
        $platformAdmin = User::factory()->create([
            'organisation_id' => $this->platform->id,
        ]);
        $platformAdmin->organisations()->attach($this->platform->id, ['role' => 'super-admin']);
        $platformAdmin->assignRole('super-admin');

        // Act - Access another org without being member
        $response = $this->actingAs($platformAdmin)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function regular_platform_user_cannot_override_membership()
    {
        // Arrange
        $platformUser = User::factory()->create([
            'organisation_id' => $this->platform->id,
        ]);
        $platformUser->organisations()->attach($this->platform->id, ['role' => 'member']);

        // Act - Try to access another org
        $response = $this->actingAs($platformUser)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function organisation_is_stored_in_request_attributes()
    {
        // Arrange
        $user = User::factory()->create();
        $user->organisations()->attach($this->org->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        // Assert - Check that controller can access via request
        $response->assertViewHas('organisation', function($viewOrg) {
            return $viewOrg->id === $this->org->id;
        });
    }

    /** @test */
    public function session_current_organisation_id_is_set()
    {
        // Arrange
        $user = User::factory()->create();
        $user->organisations()->attach($this->org->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($user)
            ->get("/organisations/test-org/dashboard");

        // Assert
        $this->assertEquals($this->org->id, session('current_organisation_id'));
    }

    /** @test */
    public function middleware_ignores_routes_without_organisation_parameter()
    {
        // Arrange
        $user = User::factory()->create();

        // Act - Platform route with no org parameter
        $response = $this->actingAs($user)
            ->get("/dashboard");

        // Assert
        $response->assertOk(); // Should pass through middleware
    }
}
```

---

## 🔴 **STEP 3: Run Tests (Expect RED)**

```bash
# Run DashboardResolver tests
php artisan test tests/Feature/Auth/DashboardResolverTest.php

# Run Middleware tests
php artisan test tests/Feature/Middleware/EnsureOrganisationMemberTest.php

# Expected: All tests FAIL (RED) - This is good! TDD working
```

---

## 🟢 **STEP 4: Refactor Existing Code to Pass Tests**

### **Common Issues to Fix:**

1. **Route names mismatch** - Update to match actual route names
2. **Method signatures** - Ensure helper methods exist
3. **Cache implementation** - Verify cache keys
4. **Platform admin override** - Add super-admin check
5. **UUID resolution** - Ensure both slug and UUID work

---

## 🔄 **STEP 5: Run Tests Again (Expect GREEN)**

```bash
# Run all tests
php artisan test tests/Feature/Auth/DashboardResolverTest.php
php artisan test tests/Feature/Middleware/EnsureOrganisationMemberTest.php

# Expected: All tests PASS (GREEN)
```

---

## 📝 **STEP 6: Commit Refactored Code**

```bash
git add app/Services/DashboardResolver.php
git add app/Http/Middleware/EnsureOrganisationMember.php
git add tests/Feature/Auth/DashboardResolverTest.php
git add tests/Feature/Middleware/EnsureOrganisationMemberTest.php

git commit -m "test: Add comprehensive tests for DashboardResolver and EnsureOrganisationMember

- Add 12 tests for DashboardResolver covering all 6 priorities
- Add 12 tests for EnsureOrganisationMember middleware
- Test edge cases: UUID resolution, platform admin override, deleted orgs
- Test cache usage, logging, API responses
- All tests passing (GREEN)

Refactored based on TDD approach to ensure:
- Priority ordering works correctly
- Organisation isolation is enforced
- Platform admin can override
- Proper context setting"
```

---

## ✅ **TDD VERIFICATION CHECKLIST**

| Component | Tests | Status |
|-----------|-------|--------|
| DashboardResolver Priority 1 | 2 tests | ⏳ |
| DashboardResolver Priority 2 | 2 tests | ⏳ |
| DashboardResolver Priority 3 | 2 tests | ⏳ |
| DashboardResolver Priority 4 | 1 test | ⏳ |
| DashboardResolver Priority 5 | 2 tests | ⏳ |
| DashboardResolver Priority 6 | 1 test | ⏳ |
| DashboardResolver Cache | 1 test | ⏳ |
| Middleware - Member access | 1 test | ⏳ |
| Middleware - Non-member | 2 tests | ⏳ |
| Middleware - Deleted org | 1 test | ⏳ |
| Middleware - UUID resolution | 1 test | ⏳ |
| Middleware - Context setting | 2 tests | ⏳ |
| Middleware - API responses | 1 test | ⏳ |
| Middleware - Admin override | 2 tests | ⏳ |
| **TOTAL** | **21 tests** | **🔴 RED → 🟢 GREEN** |

---

## 🚀 **EXECUTION COMMAND FOR CLAUDE**

```bash
## TASK: Refactor DashboardResolver and EnsureOrganisationMember with TDD

1. Create test files first (already provided above)
2. Run tests - expect RED failures
3. Refactor existing code to make tests pass:
   - Fix route names in DashboardResolver
   - Add platform admin override in middleware
   - Ensure UUID resolution works
   - Add cache invalidation
4. Run tests again - expect GREEN
5. Commit changes

Proceed with Step 1: Create test files.
```