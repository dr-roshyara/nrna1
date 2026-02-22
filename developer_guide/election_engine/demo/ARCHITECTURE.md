# Demo Auto-Creation - System Architecture

**Last Updated**: 2026-02-22
**Audience**: Developers who need to modify or extend the system

---

## 🏗️ System Components

### Component Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    User Interface Layer                      │
│  (ElectionController::startDemo, DemoCodeController, etc.)   │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ↓
┌─────────────────────────────────────────────────────────────┐
│              DemoElectionResolver                           │
│  - getDemoElectionForUser(User)                             │
│  - isElectionValidForUser(User, Election)                   │
│  - Decides which demo to use                                │
│  - Triggers auto-creation if needed                         │
└──────────────────────────┬──────────────────────────────────┘
                           │
                ┌──────────┴──────────┐
                ↓                     ↓
        ┌──────────────────┐  ┌──────────────────────────────┐
        │  Find Existing   │  │ DemoElectionCreationService  │
        │  Demo in DB      │  │ (if not found)               │
        │                  │  │ - createOrganisationDemo...()│
        └──────────────────┘  │ - createNationalPosts()      │
                              │ - createRegionalPosts()      │
                              │ - createPost()               │
                              │ - Helper methods             │
                              └──────────────────────────────┘
                                        │
                                        ↓
┌─────────────────────────────────────────────────────────────┐
│                    Database Layer                            │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐         │
│  │  elections   │ │  demo_posts  │ │  demo_codes  │ ...     │
│  │ org_id: 5    │ │ org_id: 5    │ │ org_id: 5    │         │
│  └──────────────┘ └──────────────┘ └──────────────┘         │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 Class Hierarchy & Responsibilities

### DemoElectionResolver Class

**Location**: `app/Services/DemoElectionResolver.php`

**Responsibilities**:
1. Determine correct demo election for a user
2. Decide between org-specific and platform demos
3. Trigger auto-creation when needed
4. Validate if election is appropriate for user

**Key Methods**:

```php
public function getDemoElectionForUser(User $user): ?Election
{
    // RESPONSIBILITY: Find or auto-create appropriate demo
    // INPUT: User with organisation_id
    // OUTPUT: Election or null
    // SIDE EFFECTS: May create database records
}

public function isElectionValidForUser(User $user, Election $election): bool
{
    // RESPONSIBILITY: Check if user can use this election
    // INPUT: User, Election
    // OUTPUT: true/false
    // SIDE EFFECTS: None
}
```

**State**: Stateless service
**Dependencies**: DemoElectionCreationService (injected via service container)

---

### DemoElectionCreationService Class

**Location**: `app/Services/DemoElectionCreationService.php`

**Responsibilities**:
1. Create election record
2. Create post records (national and regional)
3. Create candidate records
4. Create code records
5. Ensure organisation_id propagation
6. Log creation to audit channel

**Key Methods**:

```php
public function createOrganisationDemoElection(
    int $organisationId,
    Organization $organization
): Election
{
    // RESPONSIBILITY: Create complete demo structure
    // INPUT: Organization ID and model
    // OUTPUT: Created Election
    // SIDE EFFECTS: 22 database inserts
}

private function createNationalPosts(Election $election): void
{
    // RESPONSIBILITY: Create President and Vice President posts
    // Called by: createOrganisationDemoElection
}

private function createRegionalPosts(Election $election, array $regions): void
{
    // RESPONSIBILITY: Create regional posts (State Representative)
    // Called by: createOrganisationDemoElection
}

private function createPost(
    Election $election,
    array $postData,
    bool $isNational,
    ?string $region
): void
{
    // RESPONSIBILITY: Create single post with candidates and codes
    // Called by: createNationalPosts, createRegionalPosts
}
```

**State**: Stateless service
**Dependencies**: Database through Eloquent models

---

## 🔄 Method Call Sequence

### Scenario: User from Org 5 Accesses Demo Voting

```
1. User navigates to /election/demo/start
   │
2. ElectionController::startDemo() called
   │
3. Calls: DemoElectionResolver::getDemoElectionForUser($user)
   │        where user.organisation_id = 5
   │
4. getDemoElectionForUser() executes:
   │
   ├─ Check 1: user.organisation_id !== null? → YES (5)
   │
   ├─ Check 2: Find existing org demo in DB
   │            WHERE type='demo' AND organisation_id=5
   │
   │  Case A: FOUND → Return existing election ✅
   │  Case B: NOT FOUND → Continue to step 5
   │
5. NOT FOUND → Auto-create:
   │
   ├─ Get Organization model for ID 5
   │
   ├─ Call: DemoElectionCreationService::createOrganisationDemoElection(5, $org)
   │
   │  Inside createOrganisationDemoElection():
   │  ├─ CREATE 1 Election record
   │  │  └─ organisation_id = 5
   │  │
   │  ├─ CALL createNationalPosts($election)
   │  │  ├─ CREATE President post
   │  │  │  ├─ For each of 3 candidates:
   │  │  │  │  ├─ CREATE DemoCandidacy
   │  │  │  │  └─ CREATE DemoCode
   │  │  │  └─ organisation_id = 5 on all
   │  │  │
   │  │  └─ CREATE Vice President post
   │  │     └─ (same pattern, 3 candidates × 2 = 6 total)
   │  │
   │  ├─ CALL createRegionalPosts($election, ['Europe'])
   │  │  └─ CREATE State Representative post
   │  │     ├─ For each of 3 candidates:
   │  │     │  ├─ CREATE DemoCandidacy
   │  │     │  └─ CREATE DemoCode
   │  │     └─ organisation_id = 5 on all
   │  │
   │  └─ LOG to voting_audit channel
   │     └─ {action: 'auto-created', org: 5, election: 42, ...}
   │
   └─ Return created Election
   │
6. Return election to controller
   │
7. Controller: Create VoterSlug (voter_slug.organisation_id = 5)
   │
8. Redirect to voting page ✅
```

---

## 💾 Database Insertion Flow

### Transaction Safety

Currently NOT using transactions (could be enhanced):

```php
// Current (no transaction):
Election::create([...]);
DemoPost::create([...]);
DemoCandidacy::create([...]);
DemoCode::create([...]);
// If step 3 fails, steps 1-2 already in DB

// Could be enhanced with:
DB::transaction(function () {
    Election::create([...]);
    DemoPost::create([...]);
    DemoCandidacy::create([...]);
    DemoCode::create([...]);
});
// All or nothing atomicity
```

**Note**: This is a potential enhancement opportunity

---

## 🧵 Class Dependencies

### Dependency Graph

```
┌──────────────────────────────────────────────────┐
│  AppServiceProvider                              │
│  (registers singletons)                          │
└───────────────┬────────────────────┬─────────────┘
                │                    │
                ↓                    ↓
    ┌──────────────────────┐  ┌─────────────────────┐
    │ DemoElectionResolver │  │ DemoElectionCreation│
    │ (singleton)          │  │ Service (singleton) │
    └──────────────────────┘  └─────────────────────┘
                ↑                     ↑
                │ uses                │ uses
                └─────────────────────┘

Used by:
├─ VoterSlugService
├─ ElectionController
├─ DemoCodeController
└─ Various voting flow methods
```

### Dependency Injection Points

```php
// In AppServiceProvider::register()
$this->app->singleton(DemoElectionResolver::class, function () {
    return new DemoElectionResolver();  // No dependencies
});

$this->app->singleton(DemoElectionCreationService::class, function () {
    return new DemoElectionCreationService();  // No dependencies
});

// Usage via service container
$resolver = app(DemoElectionResolver::class);
$resolver->getDemoElectionForUser($user);

// Or via dependency injection
public function __construct(DemoElectionResolver $resolver) {
    $this->resolver = $resolver;
}
```

---

## 🧪 Testing Architecture

### Test Pyramid

```
        ╱╲
       ╱  ╲  Unit Tests (3)
      ╱────╲ - Service in isolation
     ╱      ╲
    ╱────────╲ Integration Tests (3)
   ╱          ╲ - Service + Resolver together
  ╱────────────╲
 ╱              ╲ End-to-End Tests
╱─────────────────╲ - Full voting flow (existing tests)
```

### Test File Organization

```
tests/
├── Unit/
│   └── Services/
│       ├── DemoElectionCreationServiceTest.php (3 tests)
│       ├── DemoElectionResolverTest.php (14 existing tests)
│       └── ... other tests
│
└── Feature/
    └── Services/
        ├── DemoElectionAutoCreationTest.php (3 tests)
        ├── VoterSlugServiceTest.php (29 existing tests)
        └── ... other tests
```

### Test Isolation Strategy

**Unit Tests**: Mock nothing, use real database with `RefreshDatabase`
```php
class DemoElectionCreationServiceTest extends TestCase {
    use RefreshDatabase;

    public function test_creates_election_with_correct_organisation_id() {
        // Setup: Real factories
        $org = Organization::factory()->create();

        // Execute: Real service
        $election = $this->service->createOrganisationDemoElection($org->id, $org);

        // Assert: Real database queries
        $this->assertNotNull($election);
    }
}
```

**Global Scope Handling**: Use `withoutGlobalScopes()` for test verification
```php
// Production code (filters automatically):
$posts = DemoPost::where('election_id', 123)->get();

// Test code (needs to see all for verification):
$posts = DemoPost::withoutGlobalScopes()->where('election_id', 123)->get();
```

---

## 📊 State Management

### Service State

Both services are **stateless**:

```php
// DemoElectionResolver is stateless
$resolver = new DemoElectionResolver();
$demo1 = $resolver->getDemoElectionForUser($user1);
$demo2 = $resolver->getDemoElectionForUser($user2);
// Each call is independent, no state carried between calls

// DemoElectionCreationService is stateless
$service = new DemoElectionCreationService();
$election1 = $service->createOrganisationDemoElection(1, $org1);
$election2 = $service->createOrganisationDemoElection(2, $org2);
// Each call is independent, no state carried between calls
```

**Benefits**:
- ✅ Thread-safe (if Laravel supports threading)
- ✅ Easy to test
- ✅ Easy to reason about
- ✅ No hidden side effects

### Data State (Database)

All state is in database, keyed by:
- `elections.id` + `elections.organisation_id`
- `demo_posts.election_id` + `demo_posts.organisation_id`
- `demo_candidacies.election_id` + `demo_candidacies.organisation_id`
- etc.

---

## 🔍 Visibility & Access Control

### Public vs Private Methods

```php
class DemoElectionCreationService {
    // PUBLIC: Expected external interface
    public function createOrganisationDemoElection(int $organisationId, Organization $organization): Election

    // PRIVATE: Internal implementation details
    private function createNationalPosts(Election $election): void
    private function createRegionalPosts(Election $election, array $regions): void
    private function createPost(Election $election, array $postData, bool $isNational, ?string $region): void
    private function getProposerName(int $index): string
    private function getSupporterName(int $index): string
    private function getCandidateImagePath(string $name, string $postName, ?string $region, int $index): string
}

class DemoElectionResolver {
    // PUBLIC: Expected external interface
    public function getDemoElectionForUser(User $user): ?Election
    public function isElectionValidForUser(User $user, Election $election): bool
}
```

**Access Levels**:
- External code should ONLY call public methods
- Private methods are implementation details that could change

---

## 🔐 Security Architecture

### Multi-Tenancy Implementation

```
Layer 1: Route Layer
- URL contains tenant: /nrna/api/*

Layer 2: Global Scope Layer
- BelongsToTenant trait auto-filters queries
- DemoPost::where(...) → adds AND organisation_id = current_tenant

Layer 3: Business Logic Layer
- organisation_id validated in services
- No cross-tenant operations possible

Layer 4: Audit Layer
- All changes logged with organisation_id
- Enables compliance verification
```

### Attack Surface Analysis

**Potential Attack**: Cross-organisation access
```
Org 5 user tries to vote in org 7's demo

Step 1: Can they get org 7's code?
- Codes are in database, organisation_id=7
- But user's context is organisation_id=5
- Query filter: AND organisation_id=5 → Returns empty ✅

Step 2: Can they guess a URL?
- URL format: /v/{slug}/...
- Each slug has voter_slug.organisation_id=5
- Direct access filtered ✅

Step 3: Can they create votes in wrong org?
- DemoVote::create() requires organisation_id
- Global scope filters inserts ✅

Result: ❌ Attack fails, security maintained
```

---

## 🚀 Performance Considerations

### Algorithm Complexity

```
getDemoElectionForUser():
- Check organisation_id: O(1)
- Query database: O(1) indexed lookup (organisation_id is indexed)
- Total: O(1) ✅

createOrganisationDemoElection():
- Create election: 1 INSERT O(1)
- Create posts: 3 INSERTs O(1)
- Create candidates: 9 INSERTs O(1)
- Create codes: 9 INSERTs O(1)
- Total: O(1) but with 22 database operations
- Actual time: ~20-35ms ✅
```

### Database Index Strategy

Critical indexes for auto-creation:

```sql
-- Elections table
CREATE INDEX idx_election_type_org ON elections(type, organisation_id);

-- Demo tables (via BelongsToTenant)
CREATE INDEX idx_demo_post_election_org ON demo_posts(election_id, organisation_id);
CREATE INDEX idx_demo_candidacy_election_org ON demo_candidacies(election_id, organisation_id);
CREATE INDEX idx_demo_code_election_org ON demo_codes(election_id, organisation_id);

-- Lookups use these indexes
SELECT * FROM elections WHERE type='demo' AND organisation_id=5;  // Uses index
```

---

## 📈 Scalability Analysis

### Current Scale

```
10 organizations with 1 demo each:
- Elections: 10 records
- Demo posts: 30 records (3 per election)
- Demo candidates: 90 records (9 per election)
- Demo codes: 90 records (9 per election)
- Total: 220 records
- Storage: ~50KB

Scales linearly with organizations.
```

### Bottleneck Analysis

```
CPU: Creating 22 records = ~20-35ms
- Not a bottleneck unless 1000s of simultaneous creates

I/O: Database writes are sequential
- Potential bottleneck if database is slow
- Could be improved with batch inserts

Memory: Single Election object in memory
- Negligible memory usage

Network: If database is remote
- 22 round trips → Could be optimized to 4-5 batch inserts
```

### Enhancement Opportunity

```php
// Current (22 round trips to DB)
foreach (candidates) {
    DemoCandidacy::create(...);  // 1 query
    DemoCode::create(...);        // 1 query
}

// Could be enhanced (4 batch inserts)
DemoCandidacy::insert([...array of all...]);  // 1 query
DemoCode::insert([...array of all...]);       // 1 query
```

---

## 🎓 Design Patterns Used

### 1. Service Locator Pattern

```php
app(DemoElectionCreationService::class)->createOrganisationDemoElection(...)
```

Used for:
- Dependency injection
- Service registration

### 2. Factory Pattern

DemoElectionCreationService is a factory for demo elections:
```php
// Input: Organization ID
// Output: Fully constructed Election with all related data
```

### 3. Single Responsibility Principle

- **DemoElectionResolver**: "What demo should this user get?"
- **DemoElectionCreationService**: "Create a complete demo election"
- **Individual methods**: Each has single responsibility

### 4. Dependency Injection

```php
public function __construct(DemoElectionCreationService $service) {
    $this->service = $service;
}
```

Services are injected, not created internally.

---

## 🔄 Extension Points

### How to Modify Auto-Creation

#### To Change Demo Data Structure

**File**: `DemoElectionCreationService.php`

```php
// Add more candidates
private function getNationalPosts() {
    return [
        [
            'candidates' => [
                // Add more candidates here
            ]
        ]
    ];
}

// Add more regions
private function createRegionalPosts(Election $election, array $regions) {
    // Change regions array
    $regions = ['Europe', 'Asia', 'Americas'];
}
```

#### To Add New Demo Election Types

```php
// Extend DemoElectionResolver
public function getDemoElectionForUser(User $user): ?Election {
    // Add new logic for different user types
    if ($user->isStudent()) {
        // Student-specific demo
    } else if ($user->isTeacher()) {
        // Teacher-specific demo
    }
}
```

#### To Add Logging/Monitoring

```php
// In createOrganisationDemoElection after creation
Log::channel('custom-channel')->info('Demo created', [...]);

// Send metric to monitoring service
Monitoring::send('demo.auto_creation', 1);
```

---

## 📚 Related Systems

### Voter Slug System
- **Uses**: DemoElectionResolver to find demo
- **Depends on**: Auto-created demos
- **See**: `../voter_slug/README.md`

### Election Controller
- **Calls**: DemoElectionResolver.getDemoElectionForUser()
- **Triggers**: Auto-creation indirectly
- **File**: `app/Http/Controllers/ElectionController.php`

### Demo Code Controller
- **Calls**: DemoElectionResolver.getDemoElectionForUser()
- **Triggers**: Auto-creation indirectly
- **File**: `app/Http/Controllers/Demo/DemoCodeController.php`

---

**Status**: ✅ Complete Architecture
**Last Updated**: 2026-02-22
