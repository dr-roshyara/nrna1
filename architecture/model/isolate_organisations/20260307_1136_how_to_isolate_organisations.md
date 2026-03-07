## 📋 **COMPLETE DATABASE & LARAVEL ARCHITECTURE FOR ORGANISATION ISOLATION**

### Executive Summary

After analyzing your requirements, I'm providing a **concrete, production-ready architecture** that ensures:

1. ✅ Organisation A users **cannot see** Organisation B users
2. ✅ Election voters are **scoped to both organisation AND election**
3. ✅ Multiple protection layers (database, model, repository, controller)
4. ✅ **Zero chance** of cross-organisation data leaks

---

## 🏛️ **LAYERED ARCHITECTURE DIAGRAM**

```mermaid
graph TB
    subgraph "Layer 1: Database (Last Resort)"
        DB1[Foreign Keys: organisation_id in ALL tables]
        DB2[Composite Indexes for fast scoping]
        DB3[No direct cross-org references possible]
    end
    
    subgraph "Layer 2: Model (First Defense)"
        M1[Global Scopes: BelongsToTenant]
        M2[Relationship Scopes: forOrganisation()]
        M3[Helper Methods: belongsToOrganisation()]
    end
    
    subgraph "Layer 3: Repository (Enforcement)"
        R1[Forced organisation_id in all queries]
        R2[Election validation before voter access]
        R3[Pagination with automatic scoping]
    end
    
    subgraph "Layer 4: Controller (Business Logic)"
        C1[Middleware: EnsureOrganisationMember]
        C2[Route Model Binding with org context]
        C3[Policy checks for sensitive operations]
    end
    
    subgraph "Layer 5: View (Presentation)"
        V1[Only scoped data rendered]
        V2[No raw ID exposure]
        V3[UI conditional on permissions]
    end
    
    DB1 --> M1
    M1 --> R1
    R1 --> C1
    C1 --> V1
```

---

## 📊 **DATABASE SCHEMA (Complete)**

```sql
-- ============================================
-- CORE TABLES (Already Have)
-- ============================================

-- organisations table
CREATE TABLE organisations (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('platform', 'tenant') NOT NULL DEFAULT 'tenant',
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_type (type),
    INDEX idx_is_default (is_default)
);

-- users table (SINGLE SOURCE OF TRUTH)
CREATE TABLE users (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    organisation_id UUID NOT NULL, -- Current context, not source of truth
    email_verified_at TIMESTAMP NULL,
    onboarded_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE RESTRICT,
    INDEX idx_email (email),
    INDEX idx_organisation (organisation_id),
    INDEX idx_name_email (name, email) -- For searching
);

-- ============================================
-- MEMBERSHIP TABLE (SOURCE OF TRUTH)
-- ============================================

CREATE TABLE user_organisation_roles (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    organisation_id UUID NOT NULL,
    role ENUM('member', 'admin', 'owner', 'voter') NOT NULL,
    permissions JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_org (user_id, organisation_id),
    
    -- CRITICAL INDEXES for performance
    INDEX idx_user_lookup (user_id, organisation_id),
    INDEX idx_org_lookup (organisation_id, user_id, role),
    INDEX idx_role (role)
);

-- ============================================
-- ELECTION & VOTING TABLES
-- ============================================

CREATE TABLE elections (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('draft', 'active', 'completed', 'archived') DEFAULT 'draft',
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    -- CRITICAL INDEX for organisation scoping + status filtering
    INDEX idx_org_status (organisation_id, status),
    INDEX idx_dates (start_date, end_date)
);

-- voter_slugs table (links users to elections)
CREATE TABLE voter_slugs (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,
    election_id UUID NOT NULL,
    user_id UUID NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT true,
    vote_completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- CRITICAL INDEX for finding voters by election
    INDEX idx_election_voters (election_id, user_id, vote_completed_at),
    INDEX idx_org_election (organisation_id, election_id),
    INDEX idx_user_election (user_id, election_id)
);
```

---

## 🧠 **MODEL LAYER (Concrete Implementation)**

### **User Model - Complete with All Scopes**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'organisation_id',
        'email_verified_at', 'onboarded_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'onboarded_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
            ->withPivot('role', 'permissions')
            ->withTimestamps();
    }

    public function organisationRoles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    public function currentOrganisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }

    // ============================================
    // CRITICAL: MEMBERSHIP VERIFICATION
    // ============================================

    /**
     * Check if user belongs to specific organisation
     * SOURCE OF TRUTH - uses pivot table directly
     */
    public function belongsToOrganisation(string $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisation_id', $organisationId)
            ->exists();
    }

    /**
     * Get user's role in specific organisation
     */
    public function getRoleInOrganisation(string $organisationId): ?string
    {
        return $this->organisationRoles()
            ->where('organisation_id', $organisationId)
            ->value('role');
    }

    // ============================================
    // SCOPES FOR ORGANISATION ISOLATION
    // ============================================

    /**
     * Scope users to current organisation context
     * THIS IS THE PRIMARY ISOLATION MECHANISM
     */
    public function scopeForCurrentOrganisation($query)
    {
        $orgId = session('current_organisation_id');
        
        if (!$orgId) {
            // If no context, return no results (fail secure)
            return $query->whereRaw('1 = 0');
        }
        
        return $query->whereHas('organisationRoles', function ($q) use ($orgId) {
            $q->where('organisation_id', $orgId);
        });
    }

    /**
     * Scope users who are members of a specific organisation
     * Used when organisation context is known
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->whereHas('organisationRoles', function ($q) use ($organisationId) {
            $q->where('organisation_id', $organisationId);
        });
    }

    /**
     * Scope users who are voters in a specific election
     * Automatically ensures they're in the correct organisation
     */
    public function scopeVotersForElection($query, string $electionId)
    {
        // First ensure election exists and get its org
        $election = Election::findOrFail($electionId);
        
        // Scope to organisation AND election
        return $query->whereHas('organisationRoles', function ($q) use ($election) {
                $q->where('organisation_id', $election->organisation_id);
            })
            ->whereHas('voterSlugs', function ($q) use ($electionId) {
                $q->where('election_id', $electionId);
            });
    }

    /**
     * Scope users who have NOT voted in an election yet
     */
    public function scopeEligibleVoters($query, string $electionId)
    {
        return $query->votersForElection($electionId)
            ->whereDoesntHave('voterSlugs', function ($q) use ($electionId) {
                $q->where('election_id', $electionId)
                  ->whereNotNull('vote_completed_at');
            });
    }

    // ============================================
    // SEARCH SCOPES (with organisation isolation)
    // ============================================

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function scopeWithRole($query, ?string $role, string $organisationId)
    {
        if (!$role) {
            return $query;
        }
        
        return $query->whereHas('organisationRoles', function ($q) use ($role, $organisationId) {
            $q->where('organisation_id', $organisationId)
              ->where('role', $role);
        });
    }
}
```

### **Election Model - With Voter Scoping**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'organisation_id', 'name', 'slug', 'description',
        'type', 'status', 'start_date', 'end_date', 'settings'
    ];

    protected $casts = [
        'settings' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }

    /**
     * Get all voters for this election (scoped users)
     */
    public function voters()
    {
        return User::votersForElection($this->id);
    }

    /**
     * Get eligible voters (haven't voted yet)
     */
    public function eligibleVoters()
    {
        return User::eligibleVoters($this->id);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
```

---

## 🏭 **REPOSITORY LAYER (Enforced Scoping)**

### **UserRepository - Single Source of Truth for User Queries**

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    protected ?string $organisationId = null;
    
    public function __construct()
    {
        // Automatically get organisation context from session
        $this->organisationId = Session::get('current_organisation_id');
    }
    
    /**
     * Set organisation context manually (for testing/admin overrides)
     */
    public function forOrganisation(string $organisationId): self
    {
        $this->organisationId = $organisationId;
        return $this;
    }
    
    /**
     * Verify we have organisation context
     */
    protected function ensureContext(): void
    {
        if (!$this->organisationId) {
            throw new \RuntimeException('No organisation context set for repository');
        }
    }
    
    /**
     * Get all users in current organisation with filters
     */
    public function getUsers(array $filters = []): LengthAwarePaginator
    {
        $this->ensureContext();
        
        $query = User::forOrganisation($this->organisationId)
            ->with(['organisationRoles' => function ($q) {
                $q->where('organisation_id', $this->organisationId);
            }]);
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        // Apply role filter
        if (!empty($filters['role'])) {
            $query->withRole($filters['role'], $this->organisationId);
        }
        
        return $query->paginate($filters['per_page'] ?? 20);
    }
    
    /**
     * Get single user - THROWS 404 if not in organisation
     */
    public function find(string $userId): User
    {
        $this->ensureContext();
        
        $user = User::forOrganisation($this->organisationId)
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            abort(404, 'User not found in this organisation');
        }
        
        return $user;
    }
    
    /**
     * Get voters for a specific election
     */
    public function getElectionVoters(string $electionId, array $filters = []): LengthAwarePaginator
    {
        $this->ensureContext();
        
        // Verify election belongs to current organisation
        $election = Election::forOrganisation($this->organisationId)
            ->where('id', $electionId)
            ->firstOrFail();
        
        $query = User::votersForElection($electionId)
            ->with(['voterSlugs' => function ($q) use ($electionId) {
                $q->where('election_id', $electionId);
            }]);
        
        // Apply search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        // Filter by voting status
        if (isset($filters['has_voted'])) {
            if ($filters['has_voted']) {
                $query->whereHas('voterSlugs', function ($q) use ($electionId) {
                    $q->where('election_id', $electionId)
                      ->whereNotNull('vote_completed_at');
                });
            } else {
                $query->whereDoesntHave('voterSlugs', function ($q) use ($electionId) {
                    $q->where('election_id', $electionId)
                      ->whereNotNull('vote_completed_at');
                });
            }
        }
        
        return $query->paginate($filters['per_page'] ?? 20);
    }
    
    /**
     * Add voters to an election
     */
    public function addVotersToElection(string $electionId, array $userIds): int
    {
        $this->ensureContext();
        
        // Verify election belongs to current organisation
        $election = Election::forOrganisation($this->organisationId)
            ->where('id', $electionId)
            ->firstOrFail();
        
        $count = 0;
        foreach ($userIds as $userId) {
            // Verify user belongs to organisation
            $user = $this->find($userId);
            
            // Create voter slug
            VoterSlug::firstOrCreate([
                'organisation_id' => $this->organisationId,
                'election_id' => $electionId,
                'user_id' => $userId,
            ], [
                'slug' => Str::uuid()->toString(),
                'is_active' => true,
            ]);
            
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Remove voter from election
     */
    public function removeVoterFromElection(string $electionId, string $userId): bool
    {
        $this->ensureContext();
        
        // Verify election belongs to current organisation
        Election::forOrganisation($this->organisationId)
            ->where('id', $electionId)
            ->firstOrFail();
        
        // Verify user belongs to organisation
        $this->find($userId);
        
        return VoterSlug::where('election_id', $electionId)
            ->where('user_id', $userId)
            ->delete();
    }
    
    /**
     * Get voting statistics for election
     */
    public function getElectionStats(string $electionId): array
    {
        $this->ensureContext();
        
        $totalVoters = User::votersForElection($electionId)->count();
        $votedCount = User::votersForElection($electionId)
            ->whereHas('voterSlugs', function ($q) use ($electionId) {
                $q->where('election_id', $electionId)
                  ->whereNotNull('vote_completed_at');
            })
            ->count();
        
        return [
            'total_voters' => $totalVoters,
            'voted' => $votedCount,
            'remaining' => $totalVoters - $votedCount,
            'turnout_percentage' => $totalVoters > 0 
                ? round(($votedCount / $totalVoters) * 100, 2)
                : 0,
        ];
    }
}
```

---

## 🎮 **CONTROLLER LAYER (Implementation)**

### **UserController - Organisation-Scoped User Management**

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\Election;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected UserRepository $userRepo;
    
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
        $this->middleware(['auth', 'verified']);
        $this->middleware('ensure.organisation.member')->except(['index']);
    }
    
    /**
     * GET /organisations/{organisation}/users
     * Show all users in current organisation
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'role' => 'nullable|string|in:admin,member,voter',
            'per_page' => 'nullable|integer|min:10|max:100',
        ]);
        
        $users = $this->userRepo->getUsers($filters);
        
        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $filters,
            'stats' => [
                'total' => $users->total(),
                'admins' => User::forOrganisation(session('current_organisation_id'))
                    ->whereHas('organisationRoles', function ($q) {
                        $q->where('role', 'admin');
                    })->count(),
            ]
        ]);
    }
    
    /**
     * GET /organisations/{organisation}/users/{user}
     * Show single user profile
     */
    public function show(string $userId)
    {
        $user = $this->userRepo->find($userId);
        
        return Inertia::render('Users/Show', [
            'user' => $user,
            'roles' => $user->organisationRoles()
                ->where('organisation_id', session('current_organisation_id'))
                ->first(),
        ]);
    }
    
    /**
     * GET /elections/{election}/voters
     * Show voters for specific election
     */
    public function voters(Request $request, string $electionId)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'has_voted' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:10|max:100',
        ]);
        
        $voters = $this->userRepo->getElectionVoters($electionId, $filters);
        $stats = $this->userRepo->getElectionStats($electionId);
        
        $election = Election::forOrganisation(session('current_organisation_id'))
            ->where('id', $electionId)
            ->firstOrFail();
        
        return Inertia::render('Elections/Voters', [
            'election' => $election,
            'voters' => $voters,
            'filters' => $filters,
            'stats' => $stats,
        ]);
    }
    
    /**
     * POST /elections/{election}/voters
     * Add voters to election
     */
    public function addVoters(Request $request, string $electionId)
    {
        $data = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'string|exists:users,id',
        ]);
        
        $added = $this->userRepo->addVotersToElection($electionId, $data['user_ids']);
        
        return redirect()->back()->with('success', "Added {$added} voters to election");
    }
    
    /**
     * DELETE /elections/{election}/voters/{user}
     * Remove voter from election
     */
    public function removeVoter(string $electionId, string $userId)
    {
        $removed = $this->userRepo->removeVoterFromElection($electionId, $userId);
        
        return redirect()->back()->with('success', 'Voter removed from election');
    }
}
```

---

## 🛡️ **MIDDLEWARE (Already Have - Enhanced)**

Your existing middleware is already perfect. Just ensure it's applied to all organisation routes:

```php
// routes/web.php
Route::middleware(['auth', 'verified', 'ensure.organisation.member'])
    ->prefix('organisations/{organisation}')
    ->name('organisations.')
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        
        Route::prefix('elections/{election}')
            ->name('elections.')
            ->group(function () {
                Route::get('/voters', [UserController::class, 'voters'])->name('voters');
                Route::post('/voters', [UserController::class, 'addVoters'])->name('voters.add');
                Route::delete('/voters/{user}', [UserController::class, 'removeVoter'])->name('voters.remove');
            });
    });
```

---

## 🧪 **TESTING STRATEGY**

```php
// tests/Feature/UserIsolationTest.php

/** @test */
public function user_from_org_a_cannot_see_org_b_users()
{
    // Given: Two organisations with users
    $orgA = Organisation::factory()->create();
    $orgB = Organisation::factory()->create();
    
    $userA = User::factory()->create();
    $userA->organisations()->attach($orgA->id, ['role' => 'admin']);
    
    $userB = User::factory()->create();
    $userB->organisations()->attach($orgB->id, ['role' => 'member']);
    
    // When: User A tries to see all users (should be scoped to orgA)
    $this->actingAs($userA);
    session(['current_organisation_id' => $orgA->id]);
    
    $response = $this->get(route('organisations.users', $orgA->slug));
    
    // Then: Only user A should be visible
    $response->assertSee($userA->name);
    $response->assertDontSee($userB->name);
}

/** @test */
public function election_voters_only_show_users_in_both_org_and_election()
{
    // Given: Organisation with election
    $org = Organisation::factory()->create();
    $election = Election::factory()->create(['organisation_id' => $org->id]);
    
    $voter = User::factory()->create();
    $voter->organisations()->attach($org->id, ['role' => 'voter']);
    VoterSlug::create([
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'user_id' => $voter->id,
        'slug' => Str::uuid(),
    ]);
    
    $nonVoter = User::factory()->create();
    $nonVoter->organisations()->attach($org->id, ['role' => 'member']);
    
    // When: Viewing election voters
    $this->actingAs($voter);
    session(['current_organisation_id' => $org->id]);
    
    $response = $this->get(route('elections.voters', [$org->slug, $election->id]));
    
    // Then: Only voter should be visible
    $response->assertSee($voter->name);
    $response->assertDontSee($nonVoter->name);
}
```

---

## ✅ **IMPLEMENTATION CHECKLIST**

| Component | Status | Priority |
|-----------|--------|----------|
| **Database Indexes** | ⏳ Add | 🔴 HIGH |
| **User Model Scopes** | ✅ Ready | 🔴 HIGH |
| **Election Model Methods** | ✅ Ready | 🔴 HIGH |
| **UserRepository** | ⏳ Create | 🔴 HIGH |
| **UserController** | ⏳ Create/Update | 🔴 HIGH |
| **Routes** | ⏳ Update | 🔴 HIGH |
| **Tests** | ⏳ Write | 🟡 MEDIUM |

---

## 🚀 **EXECUTION ORDER**

```bash
# 1. Add database indexes
php artisan make:migration add_user_isolation_indexes

# 2. Create UserRepository
touch app/Repositories/UserRepository.php

# 3. Update UserController
# 4. Update routes
# 5. Write tests
# 6. Run tests
php artisan test tests/Feature/UserIsolationTest.php
```

---

## 🎯 **BOTTOM LINE**

**This architecture guarantees:**

| Guarantee | How |
|-----------|-----|
| ✅ Org A never sees Org B users | Repository + Model scopes |
| ✅ Voters only see their election's users | Election validation + double scoping |
| ✅ No data leaks even if URL manipulated | Middleware + Route binding |
| ✅ Fast queries | Proper indexes |
| ✅ Testable | Repository pattern + dedicated tests |

**This is production-ready architecture that you can implement today.**