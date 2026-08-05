## 🎯 **PROFESSIONAL PROMPT INSTRUCTIONS FOR CLAUDE CLI**
 
```yaml
CONTEXT:
  project: "NRNA Public Digit Voting Platform"
  current_state: "Incomplete implementation - claims production-ready but has critical flaws"
  architecture: "Laravel 11 + Vue 3 + Inertia + Multi-tenancy"
  
ISSUES_IDENTIFIED:
  - "Registration → auto-login instead of /email/verify"
  - "Email verification not enforced before dashboard access"
  - "Platform users (org_id=1) incorrectly redirected to /organisations/publicdigit"
  - "Missing proper first-time user flow to /dashboard/welcome"
  - "Test coverage incomplete - only 4 tests, 7 assertions"
  
ROLE: "Senior Laravel/Vue3 Developer & Solution Architect"

TASK: "Fix the complete user authentication and routing flow with proper TDD approach"

REQUIREMENTS:

  PHASE 1: EMAIL VERIFICATION FLOW
  ================================
  # RegisterController.php
  - MUST NOT auto-login after registration
  - MUST redirect to /email/verify (verification.notice)
  - MUST create user with organisation_id = 1 (platform)
  - MUST create pivot in user_organisation_roles with role='member'
  
  # EmailVerificationController.php
  - MUST set email_verified_at timestamp
  - MUST NOT auto-redirect to dashboard after verification
  - MUST show success message then redirect to login
  
  # LoginResponse.php / DashboardResolver.php
  - MUST check email_verified_at before ANY dashboard access
  - IF not verified → redirect to /email/verify with message
  - IF verified AND onboarded_at IS NULL → redirect to /dashboard/welcome
  - IF verified AND onboarded_at IS NOT NULL → proceed to role-based routing

  PHASE 2: FIRST-TIME USER WELCOME FLOW
  =====================================
  # WelcomeDashboardController.php
  - GET /dashboard/welcome → show welcome page with:
    * "Create Organisation" button
    * "Try Demo Election" button
    * Pending invitations list (if any)
  - Upon first visit, set onboarded_at = now()
  - Store onboarding_source in session for analytics
  
  # After Welcome
  - IF user clicks "Create Organisation":
    * Redirect to organisation.create
    * Set session flag 'onboarding_complete' = false until org created
  - IF user clicks "Try Demo Election":
    * Redirect to demo.vote
    * Store 'tried_demo' = true in session
    * After demo, return to welcome with completion message

  PHASE 3: PLATFORM VS ORGANISATION ROUTING
  =========================================
  # DashboardResolver.php - getDashboardRoles()
  - MUST check user_organisation_roles.role column, NOT just existence
  - MUST exclude platform organisation (id=1) from admin role detection
  - Platform members with role='member' → return [] (no roles)
  - Only users with role='admin' in non-platform orgs → return ['admin']
  
  # DashboardResolver.php - resolve() priority order:
    1. Active voting session → /v/{voter_slug}
    2. Email not verified → /email/verify
    3. Verified but not onboarded → /dashboard/welcome
    4. Multiple roles → /role/selection
    5. Single role → role-specific dashboard
    6. No roles (platform member) → /dashboard (main)
    7. Legacy fallback
  
  # organisation Access Middleware
  - MUST check if user has ANY role in organisation (pivot exists)
  - IF user tries /organisations/{slug} without role → 403
  - Platform users (org_id=1) have role='member' by default

  PHASE 4: COMPREHENSIVE TEST SUITE
  =================================
  Create tests/Feature/AuthenticationFlowTest.php with:

  TEST 1: Registration Flow
  - POST /register with valid data
  - Assert: User created, organisation_id=1
  - Assert: Pivot created with role='member'
  - Assert: Redirect to /email/verify
  - Assert: NOT logged in automatically
  - Assert: No session auth

  TEST 2: Email Verification Flow
  - Create unverified user
  - Visit verification URL from email
  - Assert: email_verified_at set
  - Assert: Redirect to login with success message
  - Assert: Cannot access dashboard

  TEST 3: First-Time Login (Verified, Not Onboarded)
  - Create verified user with onboarded_at = null
  - Login
  - Assert: Redirect to /dashboard/welcome
  - Assert: welcome page loads
  - Assert: onboarded_at set after visiting welcome

  TEST 4: Platform Member (No Admin Roles)
  - Create user with:
    * organisation_id=1
    * pivot role='member'
    * onboarded_at = now()
  - Login
  - Assert: Redirect to /dashboard (main dashboard)
  - Assert: NOT redirected to /organisations/publicdigit
  - Assert: Can access /dashboard without 403

  TEST 5: Real Organisation Admin
  - Create user with:
    * organisation_id=2
    * pivot role='admin'
    * onboarded_at = now()
  - Login
  - Assert: Redirect to /organisations/{slug}
  - Assert: Has access to organisation pages

  TEST 6: Multi-Role User
  - Create user with:
    * Org1: role='admin'
    * Org2: role='member'
  - Login
  - Assert: Redirect to /role/selection
  - Assert: Session has available_organisations

  TEST 7: Active Voting Priority
  - Create user with active voter_slug
  - Login
  - Assert: Redirect to /v/{voter_slug}
  - Assert: Voting takes priority over everything

  TEST 8: Email Not Verified Block
  - Create unverified user
  - Attempt to access /dashboard
  - Assert: Redirect to /email/verify
  - Assert: Cannot bypass

  TEST 9: Welcome Page Onboarding
  - Create verified user with onboarded_at = null
  - Visit /dashboard/welcome
  - Assert: onboarded_at set
  - Assert: Session has onboarding data
  - Second visit: stays on welcome

  TEST 10: Organisation Access Control
  - Create user with no role in org X
  - Attempt to access /organisations/X
  - Assert: 403 error
  - Create user with role in org X
  - Assert: 200 OK

  PHASE 5: IMPLEMENTATION FILES TO MODIFY
  =======================================

  # 1. app/Http/Controllers/Auth/RegisterController.php
  ```php
  protected function create(array $data)
  {
      DB::transaction(function () use ($data, &$user) {
          $user = User::create([
              'name' => $data['name'],
              'email' => $data['email'],
              'password' => Hash::make($data['password']),
              'organisation_id' => 1, // Platform
          ]);
          
          // Create pivot entry
          DB::table('user_organisation_roles')->insertOrIgnore([
              'user_id' => $user->id,
              'organisation_id' => 1,
              'role' => 'member',
              'created_at' => now(),
              'updated_at' => now(),
          ]);
      });
      
      return $user;
  }
  
  protected function registered(Request $request, $user)
  {
      // DO NOT log in automatically
      // DO NOT redirect to dashboard
      return redirect()->route('verification.notice')
          ->with('status', 'Registration successful! Please verify your email.');
  }
  ```

  # 2. app/Http/Responses/LoginResponse.php
  ```php
  public function toResponse($request)
  {
      $user = $request->user();
      
      // Priority 1: Email verification
      if (!$user->hasVerifiedEmail()) {
          return redirect()->route('verification.notice')
              ->with('warning', 'Please verify your email first.');
      }
      
      // Delegate to DashboardResolver
      return app(DashboardResolver::class)->resolve($user);
  }
  ```

  # 3. app/Services/DashboardResolver.php (UPDATED PRIORITY)
  ```php
  public function resolve(User $user): RedirectResponse
  {
      // PRIORITY 1: Active voting session
      if ($votingRedirect = $this->getActiveVotingRedirect($user)) {
          return $votingRedirect;
      }
      
      // PRIORITY 2: Email verification (already checked in LoginResponse)
      // But double-check for safety
      if (!$user->hasVerifiedEmail()) {
          return redirect()->route('verification.notice');
      }
      
      // PRIORITY 3: First-time welcome (verified but not onboarded)
      if ($user->hasVerifiedEmail() && $user->onboarded_at === null) {
          return redirect()->route('dashboard.welcome');
      }
      
      // PRIORITY 4: Get dashboard roles
      $roles = $this->getDashboardRoles($user);
      
      if (count($roles) > 1) {
          return redirect()->route('role.selection');
      }
      
      if (count($roles) === 1) {
          return $this->redirectByRole($user, $roles[0]);
      }
      
      // PRIORITY 5: No roles - main dashboard
      return redirect()->route('dashboard');
  }
  
  protected function getDashboardRoles(User $user): array
  {
      $roles = [];
      
      // Get actual roles from pivot table with role check
      $orgRoles = DB::table('user_organisation_roles')
          ->where('user_id', $user->id)
          ->get();
      
      foreach ($orgRoles as $pivot) {
          // Skip platform organisation (id=1) for admin routing
          if ($pivot->organisation_id == 1) {
              continue;
          }
          
          if ($pivot->role === 'admin') {
              $roles[] = 'admin';
          }
          // Add other role mappings as needed
      }
      
      // Check commission memberships
      if (Schema::hasTable('election_commission_members')) {
          $hasCommission = DB::table('election_commission_members')
              ->where('user_id', $user->id)
              ->exists();
          if ($hasCommission) {
              $roles[] = 'commission';
          }
      }
      
      // Check voter status
      if ($user->is_voter) {
          $roles[] = 'voter';
      }
      
      return array_unique($roles);
  }
  ```

  # 4. app/Http/Controllers/WelcomeDashboardController.php
  ```php
  public function index()
  {
      $user = auth()->user();
      
      // First visit to welcome page
      if ($user->onboarded_at === null) {
          $user->update([
              'onboarded_at' => now(),
              'last_activity_at' => now(),
          ]);
          
          session(['onboarding_completed_at' => now()]);
      }
      
      // Get pending invitations
      $invitations = DB::table('organisation_invitations')
          ->where('email', $user->email)
          ->where('accepted_at', null)
          ->get();
      
      return Inertia::render('Welcome/Dashboard', [
          'user' => [
              'name' => $user->name,
              'email' => $user->email,
              'onboarded_at' => $user->onboarded_at,
          ],
          'invitations' => $invitations,
          'actions' => [
              ['label' => 'Create Organisation', 'route' => 'organisation.create', 'icon' => 'BuildingOffice'],
              ['label' => 'Try Demo Election', 'route' => 'demo.index', 'icon' => 'Beaker'],
          ],
      ]);
  }
  ```

  # 5. app/Http/Middleware/EnsureEmailIsVerified.php (CUSTOM)
  ```php
  public function handle($request, $next)
  {
      if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
          return redirect()->route('verification.notice')
              ->with('error', 'You must verify your email to access this page.');
      }
      
      return $next($request);
  }
  ```

  # 6. Kernel.php - Add middleware to web group
  ```php
  protected $middlewareGroups = [
      'web' => [
          // ... existing
          \App\Http\Middleware\EnsureEmailIsVerified::class,
      ],
  ];
  ```

  PHASE 6: VALIDATION CHECKLIST
  =============================
  
  After implementation, verify:

  [ ] Register → /email/verify (not auto-login)
  [ ] Click verification link → verified, redirect to login
  [ ] First login after verification → /dashboard/welcome
  [ ] Welcome page sets onboarded_at
  [ ] Second login → /dashboard (not welcome again)
  [ ] Platform user → /dashboard (not /organisations/publicdigit)
  [ ] Real org admin → /organisations/{slug}
  [ ] Multi-org user → /role/selection
  [ ] Unverified user cannot access ANY dashboard
  [ ] Active voter → voting flow (highest priority)
  [ ] All 10 tests pass with assertions

  PHASE 7: ERROR HANDLING
  =======================
  
  Edge cases to handle:
  - User registers, verifies email, but never visits welcome
  - User clicks verification link after 24 hours (expired)
  - User has multiple roles across organisations
  - User is removed from organisation after login
  - Database transaction failures during registration
  - Concurrent registration with same email

OUTPUT_FORMAT:
  - Provide complete file replacements (not diffs)
  - Include all 10 tests with assertions
  - Explain each change with business rationale
  - Show before/after for critical sections
  - Include migration files if needed
  - Provide deployment instructions

VERIFICATION:
  Run: php artisan test --filter=AuthenticationFlowTest
  Expected: 10 tests, 30+ assertions, 100% passing

TIMELINE:
  - Phase 1-3: 2 hours
  - Phase 4 (tests): 1 hour
  - Phase 5 (implementation): 2 hours
  - Total: 5 hours for complete, production-ready solution
```

This prompt provides **clear, testable requirements** that will force Claude to deliver a **complete, working solution** rather than claiming it's "production-ready" without proof. The test-driven approach ensures every requirement is verifiable.
## 🎯 **COMPREHENSIVE EXPLANATION: LOGINRESPONSE & DASHBOARDRESOLVER ARCHITECTURE WITH TDD APPROACH**

```yaml
CONTEXT:
  project: "NRNA Public Digit Voting Platform"
  component: "Authentication & Routing Architecture"
  methodology: "Test-Driven Development (TDD)"
  current_status: "Implementation incomplete - fixing critical flows"
```

## 🏗️ **PART 1: LOGINRESPONSE & DASHBOARDRESOLVER ARCHITECTURE**

### **The Two-Component Design Pattern**

```
┌─────────────────────────────────────────────────────────────────┐
│                     LOGIN FLOW ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────┐     ┌─────────────────┐     ┌───────────┐ │
│  │   FORTIFY       │────▶│  LoginResponse  │────▶│Dashboard  │ │
│  │   Authenticates │     │  (Controller)   │     │Resolver   │ │
│  └─────────────────┘     └─────────────────┘     └───────────┘ │
│         │                      │                      │         │
│         │                      │                      │         │
│         ▼                      ▼                      ▼         │
│  "User logged in"      "Where should they go?"  "Business logic"│
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### **LoginResponse.php - The Traffic Director**

**Purpose:** Acts as the **first checkpoint** after Fortify authentication.

```php
// Current Implementation (simplified)
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();
        
        // Check 1: Email verified?
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        // Delegate business logic to DashboardResolver
        return app(DashboardResolver::class)->resolve($user);
    }
}
```

**Key Responsibilities:**
1. ✅ **Email verification gate** - Block unverified users
2. ✅ **3-level fallback chain** - Normal → Emergency → Static HTML
3. ✅ **Request ID tracking** - Unique ID for each login for debugging
4. ✅ **Cache management** - Store resolved destinations
5. ✅ **Performance monitoring** - Log slow resolutions (>2s warning, >5s critical)

### **DashboardResolver.php - The Business Logic Engine**

**Purpose:** Contains **ALL business rules** for where users should land.

```
┌─────────────────────────────────────────────────────────────────┐
│                    DASHBOARD RESOLVER                            │
│                    Priority Decision Tree                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  INPUT: User object with all relationships                       │
│  OUTPUT: RedirectResponse to correct dashboard                    │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    PRIORITY ORDER                            │ │
│  ├─────────────────────────────────────────────────────────────┤ │
│  │ 1. ACTIVE VOTING    │ "User is voting → voting booth"       │ │
│  │ 2. EMAIL VERIFY     │ "Email not verified → verify first"   │ │
│  │ 3. WELCOME          │ "Verified but new → welcome page"     │ │
│  │ 4. MULTI-ROLE       │ "Multiple orgs → choose one"          │ │
│  │ 5. SINGLE ROLE      │ "One org admin → org dashboard"       │ │
│  │ 6. PLATFORM USER    │ "No roles → main dashboard"           │ │
│  │ 7. LEGACY FALLBACK  │ "Backward compatibility"              │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  CACHING: Store result for 5 minutes (TTL)                       │
│  INVALIDATION: Observer clears cache on role changes             │
│  FRESHNESS: Check last_activity_at before using cache            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

**Current Implementation Gaps:**
```php
// PROBLEM 1: Platform users incorrectly get 'admin' role
$hasOrgRole = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->exists();  // ❌ Only checks existence, not actual role

if ($hasOrgRole) {
    $roles[] = 'admin';  // WRONG! Platform members aren't admins
}

// PROBLEM 2: Missing welcome page check for verified-but-not-onboarded
// No check for onboarded_at === null before routing

// PROBLEM 3: Email verification not enforced at multiple layers
// Only checked in LoginResponse, but direct URL access bypasses it
```

---

## 🧪 **PART 2: TEST-DRIVEN DEVELOPMENT APPROACH**

### **What is TDD? (Red-Green-Refactor)**

```
┌─────────────────────────────────────────────────────────────────┐
│                    TDD CYCLE                                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐                                                      │
│  │  RED    │  Write a failing test FIRST                         │
│  │  🔴     │  "User should go to welcome page"                   │
│  └────┬────┘  ↓ Test fails (as expected)                         │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────┐                                                      │
│  │  GREEN  │  Write MINIMAL code to make test pass               │
│  │  ✅     │  "Add if (!$user->onboarded_at) redirect"           │
│  └────┬────┘  ↓ Test passes                                      │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────┐                                                      │
│  │REFACTOR │  Clean up code, keep tests passing                  │
│  │  🔧     │  "Extract to method, add caching"                   │
│  └─────────┘                                                      │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### **Why TDD for This Problem?**

| Without TDD | With TDD |
|-------------|----------|
| "I think it works" | "I have 10 tests proving it works" |
| Fix one bug, create three more | Tests catch regressions immediately |
| Manual testing takes hours | Automated testing takes seconds |
| Production incidents | Confidence in deployment |
| "Claude said it's production-ready" | "Tests prove it's production-ready" |

### **Our TDD Test Suite (What We Need)**

```php
tests/Feature/AuthenticationFlowTest.php
├── 🔴 TEST 1: Registration → /email/verify (not auto-login)
├── 🔴 TEST 2: Email verification flow
├── 🔴 TEST 3: First login after verification → /dashboard/welcome
├── 🔴 TEST 4: Second login (onboarded) → /dashboard
├── 🔴 TEST 5: Platform member → /dashboard (NOT /organisations/publicdigit)
├── 🔴 TEST 6: Real org admin → /organisations/{slug}
├── 🔴 TEST 7: Multi-org user → /role/selection
├── 🔴 TEST 8: Active voting → /v/{voter_slug} (highest priority)
├── 🔴 TEST 9: Unverified user blocked from all dashboards
└── 🔴 TEST 10: Welcome page sets onboarded_at

Total: 10 tests, 30+ assertions
```

### **Example TDD Cycle for Welcome Page**

**STEP 1: Write Failing Test (RED)**
```php
/** @test */
public function verified_user_without_onboarding_goes_to_welcome_page()
{
    // Arrange
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => null,
    ]);
    
    // Act
    $response = $this->actingAs($user)->get('/login-success'); // Simulate post-login
    
    // Assert
    $response->assertRedirect('/dashboard/welcome');
}
```

**STEP 2: Run Test - It FAILS** ✅ (Good! Test is working)
```
FAIL: Expected redirect to '/dashboard/welcome', got '/dashboard'
```

**STEP 3: Write Minimal Code to Pass (GREEN)**
```php
// In DashboardResolver.php
public function resolve(User $user)
{
    // ... existing code ...
    
    // NEW: Check for verified but not onboarded
    if ($user->hasVerifiedEmail() && $user->onboarded_at === null) {
        return redirect()->route('dashboard.welcome');
    }
    
    // ... rest of logic ...
}
```

**STEP 4: Run Test - It PASSES** ✅
```
PASS: verified_user_without_onboarding_goes_to_welcome_page
```

**STEP 5: Refactor (if needed)**
```php
// Extract to method for clarity
protected function shouldGoToWelcome(User $user): bool
{
    return $user->hasVerifiedEmail() && $user->onboarded_at === null;
}
```

---

## 🔧 **PART 3: CURRENT ARCHITECTURE GAPS (Why It's NOT Production-Ready)**

### **Gap 1: Email Verification Not Enforced Everywhere**

```php
// Current: Only checked in LoginResponse
// Problem: User can directly access /dashboard without verification
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth']); // ❌ Missing verification check

// FIX: Add verification middleware to ALL protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/welcome', [WelcomeDashboardController::class, 'index']);
    // ... all other protected routes
});
```

### **Gap 2: Platform Users Treated as Admins**

```php
// Current broken logic
$hasOrgRole = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->exists();  // TRUE for platform users (org_id=1, role='member')

if ($hasOrgRole) {
    $roles[] = 'admin';  // ❌ Platform users become 'admin'
}

// FIX: Check actual role AND exclude platform org
$orgRoles = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->get();

foreach ($orgRoles as $pivot) {
    if ($pivot->organisation_id == 1) {
        continue; // Skip platform org
    }
    if ($pivot->role === 'admin') {
        $roles[] = 'admin';
    }
}
```

### **Gap 3: Missing Welcome Page Onboarding**

```php
// Current: No welcome page in flow
Register → Verify → Login → /dashboard ❌ (skips welcome)

// FIXED Flow:
Register → Verify → Login → /dashboard/welcome → Create Org → /organisations/{slug}
```

### **Gap 4: Insufficient Test Coverage**

```php
// Current: 4 tests, 7 assertions
tests/Unit/Services/DashboardResolverRoleTest.php
├── platform member does not get admin role ✓
├── non platform admin gets admin role ✓
├── platform admin does not auto redirect ✓
└── newly verified user sees welcome page ✓

// Need: 10+ tests, 30+ assertions covering ALL scenarios
```

---

## 📊 **PART 4: THE COMPLETE FIXED ARCHITECTURE**

### **LoginResponse.php (Final Version)**
```php
<?php

namespace App\Http\Responses;

use App\Services\DashboardResolver;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginResponse implements LoginResponseContract
{
    protected string $requestId;
    
    public function __construct(
        protected DashboardResolver $dashboardResolver
    ) {
        $this->requestId = Str::uuid()->toString();
    }
    
    public function toResponse($request)
    {
        $user = $request->user();
        
        // Track login start
        $this->trackLoginStart($user);
        
        // GATE 1: Email verification
        if (!$user->hasVerifiedEmail()) {
            return $this->redirectToVerification($user);
        }
        
        // Delegate to DashboardResolver for business logic
        try {
            return $this->dashboardResolver->resolve($user);
        } catch (\Throwable $e) {
            return $this->handleFailure($user, $e);
        }
    }
    
    protected function redirectToVerification($user)
    {
        Log::channel('login')->info('Unverified user redirected', [
            'request_id' => $this->requestId,
            'user_id' => $user->id,
        ]);
        
        return redirect()->route('verification.notice')
            ->with('warning', 'Please verify your email before continuing.');
    }
    
    protected function handleFailure($user, \Throwable $e)
    {
        Log::error('Login response failed', [
            'request_id' => $this->requestId,
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        
        // Fallback chain
        try {
            return redirect()->route('dashboard.emergency');
        } catch (\Throwable) {
            return response()->view('auth.login-success-fallback', [
                'request_id' => $this->requestId,
            ], 200);
        }
    }
}
```

### **DashboardResolver.php (Final Version with Priority)**
```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardResolver
{
    /**
     * Priority-based dashboard resolution
     * 
     * ORDER MATTERS - Higher priority first
     */
    public function resolve(User $user): RedirectResponse
    {
        // PRIORITY 1: Active voting session
        if ($votingRedirect = $this->getVotingRedirect($user)) {
            return $votingRedirect;
        }
        
        // PRIORITY 2: Email verification (safety check)
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        // PRIORITY 3: Welcome page for new users
        if ($this->needsWelcome($user)) {
            return $this->sendToWelcome($user);
        }
        
        // Get dashboard roles
        $roles = $this->getDashboardRoles($user);
        
        // PRIORITY 4: Multiple roles
        if (count($roles) > 1) {
            return $this->sendToRoleSelector($user, $roles);
        }
        
        // PRIORITY 5: Single role
        if (count($roles) === 1) {
            return $this->sendToRoleDashboard($user, $roles[0]);
        }
        
        // PRIORITY 6: Platform user (no roles)
        return redirect()->route('dashboard');
    }
    
    /**
     * Check if user needs welcome onboarding
     */
    protected function needsWelcome(User $user): bool
    {
        return $user->hasVerifiedEmail() 
            && $user->onboarded_at === null
            && !$this->hasAdminRoles($user); // Even if they have admin roles, they need onboarding first
    }
    
    /**
     * Get actual dashboard roles (not platform)
     */
    protected function getDashboardRoles(User $user): array
    {
        $roles = [];
        
        // Get REAL roles from pivot table
        $orgRoles = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->get();
        
        foreach ($orgRoles as $pivot) {
            // Skip platform organisation (id=1)
            if ($pivot->organisation_id == 1) {
                continue;
            }
            
            // Only add admin for actual admin role
            if ($pivot->role === 'admin') {
                $roles[] = 'admin';
            }
        }
        
        // Check commission memberships
        if (DB::table('election_commission_members')
            ->where('user_id', $user->id)
            ->exists()) {
            $roles[] = 'commission';
        }
        
        // Check voter status
        if ($user->is_voter) {
            $roles[] = 'voter';
        }
        
        return array_unique($roles);
    }
    
    /**
     * Send user to welcome page and set onboarded_at
     */
    protected function sendToWelcome(User $user): RedirectResponse
    {
        // Mark as onboarded WHEN THEY VISIT, not now
        // The WelcomeDashboardController will set it
        
        Log::info('New user redirected to welcome', [
            'user_id' => $user->id,
        ]);
        
        return redirect()->route('dashboard.welcome')
            ->with('first_login', true);
    }
}
```

### **WelcomeDashboardController.php**
```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class WelcomeDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // First visit to welcome - set onboarded_at
        if ($user->onboarded_at === null) {
            $user->update([
                'onboarded_at' => now(),
                'last_activity_at' => now(),
            ]);
            
            session(['onboarding_completed' => now()]);
        }
        
        // Get pending invitations
        $invitations = DB::table('organisation_invitations')
            ->where('email', $user->email)
            ->where('accepted_at', null)
            ->get();
        
        return Inertia::render('Welcome/Dashboard', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'verified' => $user->hasVerifiedEmail(),
            ],
            'invitations' => $invitations,
            'actions' => [
                [
                    'label' => 'Create Your Organisation',
                    'description' => 'Start your first real election',
                    'route' => route('organisation.create'),
                    'icon' => 'BuildingOffice',
                    'color' => 'primary',
                ],
                [
                    'label' => 'Try Demo Election',
                    'description' => 'Experience voting firsthand',
                    'route' => route('demo.index'),
                    'icon' => 'Beaker',
                    'color' => 'secondary',
                ],
            ],
            'stats' => [
                'total_votes' => DB::table('votes')->count(),
                'active_elections' => DB::table('elections')->where('status', 'active')->count(),
                'organisations' => DB::table('organisations')->count(),
            ],
        ]);
    }
}
```

---

## ✅ **PART 5: HOW TO VERIFY IT'S PRODUCTION-READY**

### **The 10-Test Suite**

```bash
# Run all authentication flow tests
php artisan test --filter=AuthenticationFlowTest

# Expected output:
PASS  Tests\Feature\AuthenticationFlowTest
✓ registration redirects to email verification
✓ email verification flow works
✓ first login after verification goes to welcome
✓ second login goes to dashboard
✓ platform member goes to dashboard not organisation
✓ organisation admin goes to org page
✓ multi-role user goes to role selector
✓ active voter goes to voting booth
✓ unverified user blocked from all dashboards
✓ welcome page sets onboarded_at

Tests: 10 passed (30 assertions)
```

### **Manual Verification Checklist**

- [ ] Register new user → See "Verify your email" page (not logged in)
- [ ] Click verification link in email → Email verified, redirect to login
- [ ] Login → Redirect to `/dashboard/welcome`
- [ ] Welcome page shows "Create Organisation" and "Try Demo"
- [ ] Create organisation → Redirect to `/organisations/{slug}`
- [ ] Logout and login again → Redirect to `/organisations/{slug}` (not welcome)
- [ ] Platform user (no org) → Login → Redirect to `/dashboard`
- [ ] Try accessing `/dashboard` without verification → Redirect to verification page
- [ ] Try accessing `/organisations/2` without permission → 403 error

---

## 📝 **PART 6: SUMMARY OF WHAT NEEDS FIXING**

| Component | Current | Fixed |
|-----------|---------|-------|
| **Registration** | Auto-login → dashboard | Verify email first |
| **Email Verification** | Only in LoginResponse | Middleware on ALL routes |
| **Welcome Page** | Missing | First login after verification |
| **Platform Users** | Redirected to org page | Redirected to main dashboard |
| **Role Detection** | Checks existence only | Checks actual role + excludes platform |
| **Test Coverage** | 4 tests, 7 assertions | 10+ tests, 30+ assertions |
| **Cache Invalidation** | Basic | Observer pattern |
| **Error Handling** | 500 errors | 3-level fallback |

---

## 🚀 **FINAL MESSAGE TO CLAUDE**

> "The architecture we built has the right components (LoginResponse + DashboardResolver) but **critical business logic is missing**. 
> 
> Using **TDD**, implement:
> 1. Email verification gate at ALL levels
> 2. Welcome page for verified-but-not-onboarded users
> 3. Correct role detection (exclude platform org, check actual role)
> 4. 10 comprehensive tests proving every flow works
> 
> Do NOT claim it's production-ready until ALL 10 tests pass with 30+ assertions."
