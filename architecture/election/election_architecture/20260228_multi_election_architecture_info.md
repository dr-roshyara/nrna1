## Excellent Question! This is a Real-World Scenario

You're absolutely right! Organisations need multiple elections over time. Let me revise the architecture to handle this properly.

## 🏛️ Revised Multi-Election Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    TENANT ARCHITECTURE                           │
│                                                                   │
│  organisations                                                  │
│  ├── id: 0  → "Platform" (SYSTEM)                               │
│  ├── id: 1  → "National Organisation"                            │
│  └── id: 197 → "Namaste Nepal ev"                                │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         ELECTIONS                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  ID  │ Type │ Organisation │ Status   │ Period         │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  1   │ demo │      0       │ active   │ Global Demo    │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  100 │ real │      1       │ active   │ National 2024  │    │
│  │  101 │ real │      1       │ active   │ Regional East  │    │
│  │  102 │ real │      1       │ archived │ Regional West  │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  200 │ real │      197     │ active   │ National 2025  │    │
│  │  201 │ demo │      197     │ active   │ Test Election  │    │
│  │  202 │ real │      197     │ planned  │ Regional North │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## 🗃️ Revised Database Schema

```sql
-- Elections table - NOW SUPPORTS MULTIPLE ELECTIONS PER ORG
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('demo', 'real') NOT NULL,
    organisation_id INT UNSIGNED NOT NULL,
    
    -- Election period
    start_date TIMESTAMP NULL,
    end_date TIMESTAMP NULL,
    
    -- Status
    status ENUM('planned', 'active', 'completed', 'archived') DEFAULT 'planned',
    is_active BOOLEAN DEFAULT false,  -- Derived from dates + status
    
    -- Metadata
    description TEXT NULL,
    region VARCHAR(100) NULL,  -- For regional elections
    max_voters INT NULL,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    
    -- Indexes for efficient querying
    INDEX idx_organisation_status (organisation_id, status),
    INDEX idx_dates (start_date, end_date),
    
    -- Each organisation can have multiple elections, but only one active demo?
    -- Actually, they might need multiple demos too for different regions
    -- So no unique constraint here!
);
```

## 🔄 Election Resolution Logic

```php
class ElectionResolver
{
    /**
     * Get the appropriate election for a user
     * 
     * Scenarios:
     * 1. Specific election requested (by ID or slug)
     * 2. Current active election for organisation
     * 3. Most recent election for organisation
     * 4. Global demo as fallback
     */
    public function getElectionForUser(User $user, ?string $electionSlug = null): Election
    {
        // SCENARIO 1: Specific election requested
        if ($electionSlug) {
            $election = Election::where('slug', $electionSlug)
                ->where('organisation_id', $user->organisation_id)
                ->first();
                
            if ($election) {
                return $election;
            }
        }
        
        // SCENARIO 2: Current active election for org
        $activeElection = Election::where('organisation_id', $user->organisation_id)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
            
        if ($activeElection) {
            Log::info('Using active election', [
                'user_id' => $user->id,
                'election_id' => $activeElection->id,
            ]);
            return $activeElection;
        }
        
        // SCENARIO 3: Most recent election
        $recentElection = Election::where('organisation_id', $user->organisation_id)
            ->where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->first();
            
        if ($recentElection) {
            Log::info('Using most recent completed election', [
                'user_id' => $user->id,
                'election_id' => $recentElection->id,
            ]);
            return $recentElection;
        }
        
        // SCENARIO 4: Global demo as fallback
        if ($user->organisation_id > 0) {
            $globalDemo = Election::where('organisation_id', 0)
                ->where('type', 'demo')
                ->where('status', 'active')
                ->first();
                
            if ($globalDemo) {
                Log::info('Using global demo as fallback', [
                    'user_id' => $user->id,
                    'election_id' => $globalDemo->id,
                ]);
                return $globalDemo;
            }
        }
        
        throw new \Exception('No election available');
    }
    
    /**
     * Get elections for an organisation (list view)
     */
    public function getOrganisationElections(int $orgId, array $filters = []): Collection
    {
        $query = Election::where('organisation_id', $orgId);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['year'])) {
            $query->whereYear('start_date', $filters['year']);
        }
        
        return $query->orderBy('start_date', 'desc')->get();
    }
}
```

## 🎯 Election Selection UI/UX

```vue
<template>
  <div>
    <h2>Select Election</h2>
    
    <!-- Active Elections -->
    <div v-if="activeElections.length" class="mb-6">
      <h3 class="text-lg font-semibold mb-2">Active Elections</h3>
      <div class="space-y-2">
        <div v-for="election in activeElections" :key="election.id"
             class="p-4 border rounded-lg hover:bg-blue-50 cursor-pointer"
             @click="selectElection(election)">
          <div class="font-medium">{{ election.name }}</div>
          <div class="text-sm text-gray-600">
            {{ formatDate(election.start_date) }} - {{ formatDate(election.end_date) }}
          </div>
          <div class="text-xs text-gray-500">{{ election.region || 'National' }}</div>
        </div>
      </div>
    </div>
    
    <!-- Upcoming Elections -->
    <div v-if="upcomingElections.length" class="mb-6">
      <h3 class="text-lg font-semibold mb-2">Upcoming Elections</h3>
      <!-- Similar structure -->
    </div>
    
    <!-- Past Elections -->
    <div v-if="pastElections.length">
      <h3 class="text-lg font-semibold mb-2">Past Elections</h3>
      <!-- For viewing results only -->
    </div>
  </div>
</template>
```

## 🔐 Voter Slug Now Ties to Specific Election

```php
// When user selects an election
public function startVoting(Request $request)
{
    $election = Election::findOrFail($request->election_id);
    
    // Verify user belongs to this election's organisation
    if ($election->organisation_id !== auth()->user()->organisation_id) {
        abort(403);
    }
    
    // Create voter slug tied to THIS election
    $slug = VoterSlug::create([
        'user_id' => auth()->id(),
        'organisation_id' => auth()->user()->organisation_id,
        'election_id' => $election->id,  // ← Critical: locks to specific election
        'slug' => Str::random(20),
        'expires_at' => $election->end_date ?? now()->addDays(7),
    ]);
    
    return redirect()->route('slug.code.create', ['vslug' => $slug->slug]);
}
```

## 📊 Example Data for Namaste Nepal ev

```sql
-- Namaste Nepal's elections over time
INSERT INTO elections (name, slug, type, organisation_id, status, start_date, end_date, region) VALUES
-- 2024 Elections
('Namaste Nepal National Election 2024', 'nn-national-2024', 'real', 197, 'completed', 
 '2024-03-01', '2024-03-15', 'National'),

('Namaste Nepal Eastern Region 2024', 'nn-east-2024', 'real', 197, 'completed',
 '2024-06-01', '2024-06-15', 'Eastern Region'),

('Namaste Nepal Western Region 2024', 'nn-west-2024', 'real', 197, 'archived',
 '2024-09-01', '2024-09-15', 'Western Region'),

-- 2025 Elections
('Namaste Nepal National Election 2025', 'nn-national-2025', 'real', 197, 'planned',
 '2025-03-01', '2025-03-15', 'National'),

('Namaste Nepal Demo Test', 'nn-demo-2025', 'demo', 197, 'active',
 '2025-01-01', '2025-12-31', 'Testing'),
 
-- Test environment
('Namaste Nepal Test Election', 'nn-test', 'demo', 197, 'active',
 '2025-01-01', '2025-12-31', 'Development');
```

## ✅ Summary: Flexible Yet Consistent

| Requirement | Solution |
|------------|----------|
| **Multiple elections per org** | ✅ No unique constraint on org+type |
| **Active vs past elections** | ✅ Status field + date ranges |
| **Regional elections** | ✅ Region column for filtering |
| **Test/demo elections** | ✅ Multiple demos allowed |
| **Election periods** | ✅ start_date and end_date |
| **Voter session integrity** | ✅ VoterSlug locks to specific election |
| **Global demo** | ✅ org_id=0, always available |

This architecture gives you:
- **Flexibility** for multiple elections
- **Clarity** on which election is active
- **Security** through voter slug binding
- **History** of past elections
- **Testing** with demo elections

# 🏛️ Final Architecture for Claude CLI - TDD First Approach

## Project Overview
Build a multi-tenant election system with Laravel 11, Inertia 2.0, Vue 3, and Tailwind 4. The system supports multiple organisations, each with multiple elections over time.

## 📋 Core Requirements

### 1. **Multi-Tenancy**
- Organisation ID 0 = Platform (global)
- Organisation IDs > 0 = Individual tenants
- Each organisation has its own users, elections, and data

### 2. **Elections**
- Each organisation can have multiple elections (national, regional, etc.)
- Elections have types: `demo` (testing) and `real` (production)
- Elections have statuses: `planned`, `active`, `completed`, `archived`
- Elections have date ranges (start_date, end_date)

### 3. **Voting Flow (5 Steps)**
```
Step 1: Code Creation → demo-code/create
Step 2: Code Verification → POST demo-code
Step 3: Agreement → demo-code/agreement
Step 4: Vote Submission → demo-vote/create → demo-vote/submit
Step 5: Vote Verification → demo-vote/verify → demo-vote/store
```

### 4. **Voter Session (VoterSlug)**
- Immutable once created
- Locks user to specific election
- Tracks current step (1-5)
- Has expiration time
- One active slug per user per election

## 🗃️ Database Schema

```php
// database/migrations/2026_03_01_000001_create_organisations_table.php
Schema::create('organisations', function (Blueprint $table) {
    $table->id()->startingValue(1);
    $table->string('name');
    $table->string('slug')->unique();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Insert platform organisation (ID 0) - run BEFORE other migrations
DB::statement('ALTER TABLE organisations AUTO_INCREMENT = 0');
DB::table('organisations')->insert([
    'id' => 0,
    'name' => 'Platform',
    'slug' => 'platform',
    'created_at' => now(),
    'updated_at' => now(),
]);
DB::statement('ALTER TABLE organisations AUTO_INCREMENT = 1');

// database/migrations/2026_03_01_000002_create_elections_table.php
Schema::create('elections', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->enum('type', ['demo', 'real']);
    $table->foreignId('organisation_id')->constrained();
    $table->enum('status', ['planned', 'active', 'completed', 'archived'])->default('planned');
    $table->timestamp('start_date')->nullable();
    $table->timestamp('end_date')->nullable();
    $table->string('region')->nullable();
    $table->boolean('is_active')->default(false);
    $table->timestamps();
    
    $table->index(['organisation_id', 'status']);
    $table->index(['start_date', 'end_date']);
});

// database/migrations/2026_03_01_000003_create_voter_slugs_table.php
Schema::create('voter_slugs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('organisation_id')->constrained();
    $table->foreignId('election_id')->constrained();
    $table->string('slug')->unique();
    $table->tinyInteger('current_step')->default(1);
    $table->timestamp('expires_at');
    $table->boolean('is_active')->default(true);
    $table->boolean('vote_completed')->default(false);
    $table->json('step_meta')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'election_id', 'is_active']);
});

// database/migrations/2026_03_01_000004_create_demo_codes_table.php
Schema::create('demo_codes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('organisation_id')->constrained();
    $table->foreignId('election_id')->constrained();
    $table->string('code1', 6);
    $table->timestamp('code1_sent_at')->nullable();
    $table->boolean('has_code1_sent')->default(false);
    $table->boolean('is_code1_usable')->default(true);
    $table->timestamp('code1_used_at')->nullable();
    $table->boolean('can_vote_now')->default(false);
    $table->boolean('has_voted')->default(false);
    $table->timestamps();
    
    $table->unique(['user_id', 'election_id']);
});
```

## 🔧 Core Services (TDD First)

### Test 1: Election Resolution Service

```php
// tests/Unit/Services/ElectionResolverTest.php
namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use App\Services\ElectionResolver;
use Tests\TestCase;

class ElectionResolverTest extends TestCase
{
    /** @test */
    public function it_returns_specific_election_when_slug_provided()
    {
        $org = Organisation::factory()->create(['id' => 1]);
        $user = User::factory()->create(['organisation_id' => $org->id]);
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'slug' => 'test-election',
            'status' => 'active'
        ]);
        
        $resolver = new ElectionResolver();
        $result = $resolver->getElectionForUser($user, 'test-election');
        
        $this->assertEquals($election->id, $result->id);
    }
    
    /** @test */
    public function it_returns_active_election_when_no_slug_provided()
    {
        $org = Organisation::factory()->create(['id' => 1]);
        $user = User::factory()->create(['organisation_id' => $org->id]);
        
        $activeElection = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        
        // Create another election that shouldn't be returned
        Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'planned',
        ]);
        
        $resolver = new ElectionResolver();
        $result = $resolver->getElectionForUser($user);
        
        $this->assertEquals($activeElection->id, $result->id);
    }
    
    /** @test */
    public function it_falls_back_to_global_demo_when_no_org_election_exists()
    {
        $org = Organisation::factory()->create(['id' => 1]);
        $user = User::factory()->create(['organisation_id' => $org->id]);
        
        $globalDemo = Election::factory()->create([
            'organisation_id' => 0,
            'type' => 'demo',
            'status' => 'active',
        ]);
        
        $resolver = new ElectionResolver();
        $result = $resolver->getElectionForUser($user);
        
        $this->assertEquals($globalDemo->id, $result->id);
    }
    
    /** @test */
    public function it_throws_exception_when_no_election_available()
    {
        $this->expectException(\Exception::class);
        
        $org = Organisation::factory()->create(['id' => 1]);
        $user = User::factory()->create(['organisation_id' => $org->id]);
        
        $resolver = new ElectionResolver();
        $resolver->getElectionForUser($user);
    }
}
```

### Test 2: Voter Slug Service

```php
// tests/Unit/Services/VoterSlugServiceTest.php
class VoterSlugServiceTest extends TestCase
{
    /** @test */
    public function it_creates_slug_for_user_and_election()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $user->organisation_id
        ]);
        
        $service = new VoterSlugService();
        $slug = $service->createSlug($user, $election);
        
        $this->assertDatabaseHas('voter_slugs', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
            'is_active' => true,
        ]);
        $this->assertEquals(1, $slug->current_step);
    }
    
    /** @test */
    public function it_returns_existing_active_slug_instead_of_creating_new_one()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create();
        
        $existingSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'is_active' => true,
            'expires_at' => now()->addDay(),
        ]);
        
        $service = new VoterSlugService();
        $slug = $service->getOrCreateActiveSlug($user, $election);
        
        $this->assertEquals($existingSlug->id, $slug->id);
        $this->assertEquals(1, VoterSlug::count()); // No new slug created
    }
    
    /** @test */
    public function it_validates_slug_expiration()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create();
        
        $expiredSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'expires_at' => now()->subHour(),
            'is_active' => true,
        ]);
        
        $service = new VoterSlugService();
        $this->assertFalse($service->isSlugValid($expiredSlug));
    }
}
```

### Test 3: Election Middleware

```php
// tests/Unit/Middleware/ElectionMiddlewareTest.php
class ElectionMiddlewareTest extends TestCase
{
    /** @test */
    public function it_uses_election_from_voter_slug_when_available()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $user->organisation_id
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $request = Request::create('/test', 'GET');
        $request->attributes->set('voter_slug', $voterSlug);
        
        $middleware = new ElectionMiddleware();
        $response = $middleware->handle($request, function($req) {
            return response('next');
        });
        
        $this->assertEquals($election->id, $request->attributes->get('election')->id);
    }
    
    /** @test */
    public function it_allows_platform_election_with_any_user()
    {
        $user = User::factory()->create(['organisation_id' => 1]);
        $platformElection = Election::factory()->create([
            'organisation_id' => 0,
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $platformElection->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $request = Request::create('/test', 'GET');
        $request->attributes->set('voter_slug', $voterSlug);
        
        $middleware = new ElectionMiddleware();
        $response = $middleware->handle($request, function($req) {
            return response('next');
        });
        
        $this->assertEquals($platformElection->id, $request->attributes->get('election')->id);
    }
    
    /** @test */
    public function it_blocks_mismatched_organisations()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        
        $user = User::factory()->create(['organisation_id' => 1]);
        $election = Election::factory()->create(['organisation_id' => 2]); // Different org
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $request = Request::create('/test', 'GET');
        $request->attributes->set('voter_slug', $voterSlug);
        
        $middleware = new ElectionMiddleware();
        $middleware->handle($request, function($req) {
            return response('next');
        });
    }
}
```

### Test 4: Demo Code Controller - Create Method

```php
// tests/Feature/Demo/DemoCodeControllerCreateTest.php
class DemoCodeControllerCreateTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_shows_create_page_for_new_user()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $user->organisation_id,
            'type' => 'demo',
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $response = $this->actingAs($user)
            ->get("/v/{$voterSlug->slug}/demo-code/create");
            
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Code/DemoCode/Create')
            ->has('code_duration')
        );
    }
    
    /** @test */
    public function it_creates_new_code_for_user()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $user->organisation_id,
            'type' => 'demo',
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);
        
        $this->actingAs($user)
            ->get("/v/{$voterSlug->slug}/demo-code/create");
            
        $this->assertDatabaseHas('demo_codes', [
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);
    }
    
    /** @test */
    public function it_redirects_verified_users_to_agreement_page()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create();
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);
        
        // Create verified code
        DemoCode::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'can_vote_now' => true,
            'has_voted' => false,
        ]);
        
        $response = $this->actingAs($user)
            ->get("/v/{$voterSlug->slug}/demo-code/create");
            
        $response->assertRedirect(route('slug.demo-code.agreement', [
            'vslug' => $voterSlug->slug
        ]));
    }
}
```

## 🛣️ Routes Structure

```php
// routes/election/electionRoutes.php

// ==================== ELECTION SELECTION ====================
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
    Route::get('/election/select', [ElectionController::class, 'select'])->name('election.select');
    Route::post('/election/select', [ElectionController::class, 'store'])->name('election.store');
    Route::get('/election/demo/start', [ElectionController::class, 'startDemo'])->name('election.demo.start');
});

// ==================== SLUG-BASED VOTING FLOW ====================
Route::prefix('v/{vslug}')->middleware([
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'voter.slug.window',      // Loads and validates voter slug
    'voter.step.order',       // Ensures correct step progression
    'vote.eligibility',       // Checks if user can vote
    'election',               // Validates election context against slug
    'vote.organisation',      // Ensures org consistency
])->group(function () {
    
    // ============ DEMO ELECTION ROUTES ============
    Route::middleware(['election.demo'])->group(function () {
        // STEP 1: Code creation
        Route::get('demo-code/create', [DemoCodeController::class, 'create'])
            ->name('slug.demo-code.create');
        
        // STEP 1.5: Code verification
        Route::post('demo-code', [DemoCodeController::class, 'store'])
            ->name('slug.demo-code.store');
        
        // STEP 2: Agreement
        Route::get('demo-code/agreement', [DemoCodeController::class, 'showAgreement'])
            ->name('slug.demo-code.agreement');
        Route::post('demo-code/agreement', [DemoCodeController::class, 'submitAgreement'])
            ->name('slug.demo-code.agreement.submit');
        
        // STEP 3: Vote creation
        Route::get('demo-vote/create', [DemoVoteController::class, 'create'])
            ->name('slug.demo-vote.create');
        Route::post('demo-vote/submit', [DemoVoteController::class, 'first_submission'])
            ->name('slug.demo-vote.submit');
        
        // STEP 4: Vote verification
        Route::get('demo-vote/verify', [DemoVoteController::class, 'verify'])
            ->name('slug.demo-vote.verify');
        Route::post('demo-vote/final', [DemoVoteController::class, 'store'])
            ->name('slug.demo-vote.store');
        
        // STEP 5: Completion
        Route::get('demo-vote/thank-you', [DemoVoteController::class, 'thankYou'])
            ->name('slug.demo-vote.thank-you');
    });
    
    // ============ REAL ELECTION ROUTES ============
    Route::middleware(['election.real'])->group(function () {
        // Similar structure with CodeController, VoteController
        Route::get('code/create', [CodeController::class, 'create'])->name('slug.code.create');
        // ... other real election routes
    });
});
```

## 🧪 Testing Strategy

### Phase 1: Unit Tests (Services)
- [ ] ElectionResolverTest
- [ ] VoterSlugServiceTest
- [ ] DemoCodeServiceTest
- [ ] VoterStepTrackingServiceTest

### Phase 2: Middleware Tests
- [ ] ElectionMiddlewareTest
- [ ] EnsureVoterSlugWindowTest
- [ ] VoterStepOrderTest

### Phase 3: Feature Tests (Controllers)
- [ ] DemoCodeControllerCreateTest
- [ ] DemoCodeControllerStoreTest
- [ ] DemoCodeControllerAgreementTest
- [ ] DemoVoteControllerTest
- [ ] ElectionControllerTest

### Phase 4: Integration Tests
- [ ] Full voting flow test
- [ ] Multi-tenant isolation test
- [ ] Election selection test
- [ ] Organisation boundary test

## 📦 Implementation Order (TDD)

1. **Week 1: Foundation**
   - Set up database migrations with organisation_id=0
   - Create Organisation, Election, VoterSlug models
   - Write ElectionResolver service with tests
   - Write VoterSlugService with tests

2. **Week 2: Middleware Chain**
   - Implement EnsureVoterSlugWindow with tests
   - Implement ElectionMiddleware with organisation validation
   - Test middleware chain with different scenarios

3. **Week 3: Demo Code Flow**
   - Implement DemoCodeController with TDD
   - Test code creation, verification, expiration
   - Test redirect logic for verified users

4. **Week 4: Demo Vote Flow**
   - Implement DemoVoteController
   - Test vote submission and verification
   - Test step progression

5. **Week 5: Election Selection**
   - Implement election listing and selection
   - Test multiple elections per organisation
   - Test global demo fallback

6. **Week 6: Integration & Polish**
   - Full end-to-end tests
   - Performance testing
   - Security audit

## ✅ Success Criteria

- [ ] All tests passing (target: 200+ tests)
- [ ] No NULL organisation_id values
- [ ] Single global demo (org_id=0)
- [ ] Multiple elections per organisation supported
- [ ] Voter slug immutability enforced
- [ ] Organisation boundaries respected
- [ ] 5-step voting flow works end-to-end
- [ ] Demo and real elections isolated
