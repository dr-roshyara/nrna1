# Model Relationships Developer Guide

**Public Digit Multi-Tenant Voting Platform**

> A comprehensive guide to the Phase A model relationships implementation using TDD-first approach with UUID multi-tenancy and strict vote anonymity enforcement.

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Core Patterns](#core-patterns)
3. [Model Reference](#model-reference)
4. [Usage Examples](#usage-examples)
5. [Testing Patterns](#testing-patterns)
6. [Common Pitfalls](#common-pitfalls)
7. [Extending Models](#extending-models)
8. [Best Practices](#best-practices)

---

## Architecture Overview

### The Multi-Tenant Model Hierarchy

```
┌─────────────────────────────────────────────────────────────────┐
│                    ORGANISATION                                  │
│              (platform or tenant type)                           │
└─────────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┬──────────────┐
        ▼                     ▼                     ▼              ▼
    ELECTIONS            USERS               USER ORG ROLES    POSTS
    (id, org_id)    (id, org_id)         (user_id, org_id)  (id, org_id)
                                              │
                                              ▼
                                        CANDIDACIES
                                   (post_id, user_id)
                                              │
                                              ▼
                                        [VOTES & RESULTS]
                                      (Phase B - Anonymous)
```

### Key Design Principles

#### 1. **UUID Primary Keys**
All models use `char(36)` UUID string primary keys via `HasUuids` trait.

```php
// In every model
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Model extends Model {
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
}
```

#### 2. **Tenant Scoping**
Every model includes `organisation_id` and uses `BelongsToTenant` global scope for automatic filtering.

```php
// Session-based tenant context (set via TenantContext service)
session('current_organisation_id') // Automatically filters queries

// In tests, bypass with:
Model::withoutGlobalScopes()->get()
```

#### 3. **Global Scope Bypass Pattern**
Relationships must explicitly bypass `BelongsToTenant` scope to work correctly.

```php
// ✅ CORRECT - relationship with withoutGlobalScopes()
public function candidacies()
{
    return $this->hasMany(Candidacy::class, 'post_id', 'id')
                ->withoutGlobalScopes();
}

// ❌ WRONG - scope blocking queries
public function candidacies()
{
    return $this->hasMany(Candidacy::class, 'post_id', 'id');
}
```

#### 4. **Vote Anonymity (CRITICAL)**
Votes and Results have **NO user_id column**. Users cannot be linked to their votes.

```
// ✅ ALLOWED - VoterSlug is the bridge (one-way only)
VoterSlug::hasMany(Vote) ← VoterSlug to Vote
VoterSlug::hasMany(Result) ← VoterSlug to Result

// ❌ FORBIDDEN - These relationships MUST NOT exist
Vote::belongsTo(User) ← BREAKS ANONYMITY
Result::belongsTo(User) ← BREAKS ANONYMITY
User::hasMany(Vote) ← BREAKS ANONYMITY
Code::hasMany(Vote) ← BREAKS ANONYMITY
```

---

## Core Patterns

### Pattern 1: Organisation-Scoped Models

**Models affected:** `User`, `Election`, `Post`, `Candidacy`, `UserOrganisationRole`

Every model tracks which organisation it belongs to via `organisation_id`.

```php
// In Model
protected $fillable = [
    'organisation_id',  // ← REQUIRED
    'name',
    // ... other fields
];

public function organisation()
{
    return $this->belongsTo(Organisation::class)
                ->withoutGlobalScopes();
}
```

**Usage:**
```php
// Query by organisation
Post::forOrganisation($org_id)->get();

// All posts in a post automatically scoped to current org (via session)
$posts = Post::all();  // Only current org's posts
```

### Pattern 2: HAS-MANY Relationships

**Use when:** A model owns multiple records of another model.

```php
// Organisation HAS MANY Elections
public function elections()
{
    return $this->hasMany(Election::class);
}

// Post HAS MANY Candidacies
public function candidacies()
{
    return $this->hasMany(Candidacy::class, 'post_id', 'id')
                ->withoutGlobalScopes();
}
```

**Usage:**
```php
$org = Organisation::find($id);
$elections = $org->elections;  // Get all elections for this org

$post = Post::find($id);
$candidates = $post->candidacies()->approved()->get();
```

### Pattern 3: BELONGS-TO Relationships

**Use when:** A model belongs to another model (parent/child).

```php
// Election BELONGS TO Organisation
public function organisation()
{
    return $this->belongsTo(Organisation::class)
                ->withoutGlobalScopes();
}

// Candidacy BELONGS TO User (nullable)
public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id')
                ->withoutGlobalScopes();
}
```

**Usage:**
```php
$election = Election::find($id);
$org = $election->organisation;  // Get parent organisation

$candidacy = Candidacy::find($id);
if ($candidacy->user) {
    echo $candidacy->user->name;  // Get candidate's user
}
```

### Pattern 4: BELONGS-TO-MANY Relationships

**Use when:** Two models have a many-to-many relationship through a pivot table.

```php
// Organisation BELONGS-TO-MANY Users (through user_organisation_roles)
public function users()
{
    return $this->belongsToMany(User::class, 'user_organisation_roles')
                ->withPivot('role')
                ->withTimestamps();
}

// User BELONGS-TO-MANY Organisations (through user_organisation_roles)
public function organisations()
{
    return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                ->withPivot('role')
                ->withTimestamps();
}
```

**Usage:**
```php
$org = Organisation::find($id);
$users = $org->users;  // All users in this org

// With pivot data
foreach ($org->users as $user) {
    echo $user->pivot->role;  // 'admin', 'member', etc.
}

// Filter by role
$admins = $org->users()->wherePivot('role', 'admin')->get();
```

### Pattern 5: HAS-MANY-THROUGH Relationships

**Use when:** Access related records through an intermediate model.

```php
// Election HAS MANY Candidacies THROUGH Posts
// (Elections don't directly own Candidacies, only through Posts)
public function candidacies(): HasManyThrough
{
    return $this->hasManyThrough(
        Candidacy::class,      // Final model
        Post::class,           // Intermediate model
        'election_id',         // FK on posts table
        'post_id',             // FK on candidacies table
        'id',                  // Local key on elections
        'id'                   // Local key on posts
    )->withoutGlobalScopes();
}
```

**Usage:**
```php
$election = Election::find($id);
$all_candidates = $election->candidacies;  // All candidates in this election

// With constraints
$approved = $election->candidacies()
    ->where('status', 'approved')
    ->get();
```

### Pattern 6: Query Scopes

**Use to:** Create reusable query filters.

```php
// In Model
public function scopeForOrganisation($query, string $organisationId)
{
    return $query->withoutGlobalScopes()
                 ->where('organisation_id', $organisationId);
}

public function scopeApproved($query)
{
    return $query->where('status', 'approved');
}

public function scopeForElection($query, string $electionId)
{
    return $query->withoutGlobalScopes()
                 ->where('election_id', $electionId);
}
```

**Usage:**
```php
// Single scope
$posts = Post::forOrganisation($org_id)->get();

// Chained scopes
$approved_candidates = Candidacy::forOrganisation($org_id)
    ->approved()
    ->get();

// With additional constraints
$election_candidates = $election->candidacies()
    ->approved()
    ->orderBy('position_order')
    ->get();
```

---

## Model Reference

### 1. Organisation Model

**File:** `app/Models/Organisation.php`

**Type:** Root/Landlord model (platform or tenant)

**Attributes:**
```php
[
    'id'          => 'uuid',
    'name'        => 'string',
    'slug'        => 'string',
    'type'        => 'enum(platform|tenant)',  // Platform or Tenant
    'is_default'  => 'boolean',
    'email'       => 'string',
    'address'     => 'json',
    'representative' => 'json',
    'settings'    => 'json',
    'languages'   => 'json array',
    'timestamps'  => 'created_at, updated_at',
    'soft_delete' => 'deleted_at'
]
```

**Key Relationships:**
```php
$org->elections()                    // HasMany
$org->posts()                        // HasMany
$org->users()                        // BelongsToMany (via pivot)
$org->userOrganisationRoles()        // HasMany (pivot model)
```

**Type Helpers:**
```php
$org->isPlatform()   // Returns true if type === 'platform'
$org->isTenant()     // Returns true if type === 'tenant'

// Get default platform
$default = Organisation::getDefaultPlatform();
```

**Usage:**
```php
// Create organisation
$org = Organisation::factory()->tenant()->create();

// Access related data
$all_elections = $org->elections;
$all_posts = $org->posts;
$members = $org->users;

// Admin users only
$admins = $org->admins()->get();

// Commission members
$commission = $org->commissionMembers()->get();
```

---

### 2. User Model

**File:** `app/Models/User.php`

**Type:** Person in the system

**Attributes:**
```php
[
    'id'              => 'uuid',
    'organisation_id' => 'uuid fk',
    'name'            => 'string',
    'email'           => 'string unique',
    'password'        => 'string',
    'region'          => 'string nullable',  // For regional filtering
    'first_name'      => 'string nullable',
    'last_name'       => 'string nullable',
    'timestamps'      => 'created_at, updated_at',
    'soft_delete'     => 'deleted_at'
]
```

**Key Relationships:**
```php
$user->organisation()              // BelongsTo (current org)
$user->organisations()             // BelongsToMany (all orgs user is member of)
$user->organisationRoles()         // HasMany (UserOrganisationRole records)
$user->candidacies()               // HasMany (candidates that this user is)
```

**NOTE: NO Vote or Result Relationships!**
```php
// ❌ These relationships MUST NOT EXIST
$user->votes()     // FORBIDDEN - breaks anonymity
$user->results()   // FORBIDDEN - breaks anonymity
```

**Usage:**
```php
// Get user's primary organisation
$org = $user->organisation;

// Get all organisations this user is member of
$orgs = $user->organisations;

// Get user's candidacies
$my_candidacies = $user->candidacies;

// Check if user is admin in an org
$is_admin = $user->isOrganisationAdmin($org_id);

// Get user's role in an org
$role = $user->getRoleInOrganisation($org_id);  // 'admin', 'member', etc.
```

---

### 3. UserOrganisationRole Model (Pivot)

**File:** `app/Models/UserOrganisationRole.php`

**Type:** Explicit pivot model (bridges User and Organisation)

**Attributes:**
```php
[
    'id'               => 'uuid',
    'user_id'          => 'uuid fk',
    'organisation_id'  => 'uuid fk',
    'role'             => 'enum(admin|member|voter|commission)',
    'permissions'      => 'json nullable',
    'timestamps'       => 'created_at, updated_at'
]
```

**Unique Constraint:**
```php
unique(user_id, organisation_id)  // One user can have only one role per org
```

**Key Relationships:**
```php
$pivot->user()           // BelongsTo
$pivot->organisation()   // BelongsTo
```

**Usage:**
```php
// Create user-organisation relationship
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'admin'
]);

// Query by role
$admins = UserOrganisationRole::where('role', 'admin')->get();

// Update role
$pivot->update(['role' => 'member']);
```

---

### 4. Election Model

**File:** `app/Models/Election.php`

**Type:** Election event in an organisation

**Attributes:**
```php
[
    'id'                => 'uuid',
    'organisation_id'   => 'uuid fk',
    'name'              => 'string',
    'slug'              => 'string',
    'description'       => 'text nullable',
    'type'              => 'enum(demo|real)',
    'status'            => 'enum(pending|active|closed)',
    'start_date'        => 'datetime nullable',
    'end_date'          => 'datetime nullable',
    'is_active'         => 'boolean',
    'settings'          => 'json nullable',
    'timestamps'        => 'created_at, updated_at',
    'soft_delete'       => 'deleted_at'
]
```

**Key Relationships:**
```php
$election->organisation()           // BelongsTo
$election->posts()                  // HasMany
$election->candidacies()            // HasManyThrough (via posts)
$election->voterRegistrations()     // HasMany
$election->codes()                  // HasMany (verification codes)
```

**Type Helpers:**
```php
$election->isDemo()            // Returns true if type === 'demo'
$election->isReal()            // Returns true if type === 'real'
$election->isCurrentlyActive() // Checks is_active flag + date range
```

**Statistics Methods:**
```php
$election->pendingVoterCount()      // Count voters pending approval
$election->approvedVoterCount()     // Count approved voters
$election->votedCount()             // Count voters who have voted
$election->totalVotesCast()         // Total votes submitted
$election->voterTurnout()           // Turnout percentage (voted/approved)
$election->getStatistics()          // Complete statistics array
```

**Usage:**
```php
// Get all posts in an election
$posts = $election->posts;

// Get all candidates in an election
$candidates = $election->candidacies;

// Get approved candidates
$approved = $election->candidacies()
    ->where('status', 'approved')
    ->get();

// Check if election is still active
if ($election->isCurrentlyActive()) {
    // Allow voting
}

// Get election statistics
$stats = $election->getStatistics();
// Returns: [
//   'pending_voters' => 10,
//   'approved_voters' => 45,
//   'voted' => 32,
//   'turnout_percentage' => 71.1,
//   'election_type' => 'real',
//   'is_active' => true
// ]
```

---

### 5. Post Model

**File:** `app/Models/Post.php`

**Type:** Position/seat in an election

**Attributes:**
```php
[
    'id'                => 'uuid',
    'organisation_id'   => 'uuid fk',
    'election_id'       => 'uuid fk',
    'name'              => 'string',
    'nepali_name'       => 'string nullable',
    'is_national_wide'  => 'boolean',
    'state_name'        => 'string nullable',  // Region for regional posts
    'required_number'   => 'integer',          // How many to select
    'position_order'    => 'integer nullable',
    'timestamps'        => 'created_at, updated_at',
    'soft_delete'       => 'deleted_at'
]
```

**Key Relationships:**
```php
$post->organisation()              // BelongsTo
$post->election()                  // BelongsTo
$post->candidacies()               // HasMany
$post->approvedCandidacies()       // HasMany (filtered to approved)
```

**Query Scopes:**
```php
$posts = Post::forOrganisation($org_id)->get()
$posts = Post::forElection($election_id)->get()
```

**Type Information:**
```php
// National posts - visible to all voters
if ($post->is_national_wide) {
    // Show to all voters
}

// Regional posts - filtered by voter's region
if (!$post->is_national_wide) {
    // Show only to voters in $post->state_name
}
```

**Usage:**
```php
// Get all posts in an election
$posts = $election->posts;

// Get regional posts
$regional = $election->posts()
    ->where('is_national_wide', false)
    ->where('state_name', $voter_region)
    ->get();

// Get national posts
$national = $election->posts()
    ->where('is_national_wide', true)
    ->get();

// Get candidates for a post
$candidates = $post->candidacies()
    ->where('status', 'approved')
    ->orderBy('position_order')
    ->get();
```

---

### 6. Candidacy Model

**File:** `app/Models/Candidacy.php`

**Type:** Candidate for a post

**Attributes:**
```php
[
    'id'                => 'uuid',
    'organisation_id'   => 'uuid fk',
    'post_id'           => 'uuid fk',
    'user_id'           => 'uuid fk nullable',
    'name'              => 'string',
    'description'       => 'text nullable',
    'position_order'    => 'integer',
    'status'            => 'enum(pending|approved|rejected|withdrawn)',
    'timestamps'        => 'created_at, updated_at',
    'soft_delete'       => 'deleted_at'
]
```

**Status Constants:**
```php
Candidacy::STATUS_PENDING    // 'pending'
Candidacy::STATUS_APPROVED   // 'approved'
Candidacy::STATUS_REJECTED   // 'rejected'
Candidacy::STATUS_WITHDRAWN  // 'withdrawn'
```

**Key Relationships:**
```php
$candidacy->organisation()         // BelongsTo
$candidacy->post()                 // BelongsTo
$candidacy->user()                 // BelongsTo (nullable)

// Access election through post (NO DIRECT FK!)
$candidacy->post->election         // Via post relationship
```

**Query Scopes:**
```php
$approved = Candidacy::approved()->get()
$pending = Candidacy::pending()->get()
$org_candidates = Candidacy::forOrganisation($org_id)->get()
```

**Status Helpers:**
```php
$candidacy->isApproved()    // Status === 'approved'
$candidacy->isPending()     // Status === 'pending'
$candidacy->isRejected()    // Status === 'rejected'
$candidacy->isWithdrawn()   // Status === 'withdrawn'
```

**Status Mutators:**
```php
$candidacy->approve()       // Update to approved
$candidacy->reject()        // Update to rejected
$candidacy->withdraw()      // Update to withdrawn
```

**Usage:**
```php
// Get all candidates for a post
$candidates = $post->candidacies;

// Get approved candidates only
$approved = $post->candidacies()
    ->where('status', 'approved')
    ->orderBy('position_order')
    ->get();

// Get the candidate's user (if exists)
if ($candidacy->user) {
    echo $candidacy->user->name;
}

// Access the election (THROUGH POST, never directly)
$election = $candidacy->post->election;

// Update candidate status
$candidacy->approve();
$candidacy->reject();

// Check status
if ($candidacy->isApproved()) {
    // Show in voting UI
}
```

---

## Usage Examples

### Example 1: Creating an Election with Candidates

```php
// Create organisation
$org = Organisation::factory()->tenant()->create();

// Create election
$election = Election::factory()->forOrganisation($org)->create([
    'name' => 'Board Elections 2024',
    'type' => 'real'
]);

// Create posts
$president_post = Post::create([
    'organisation_id' => $org->id,
    'election_id' => $election->id,
    'name' => 'President',
    'is_national_wide' => true,
    'required_number' => 1,
]);

$treasurer_post = Post::create([
    'organisation_id' => $org->id,
    'election_id' => $election->id,
    'name' => 'Treasurer',
    'is_national_wide' => true,
    'required_number' => 1,
]);

// Add users as candidates
$user1 = User::factory()->forOrganisation($org)->create();
$user2 = User::factory()->forOrganisation($org)->create();

// Create candidacies
$candidacy1 = Candidacy::create([
    'organisation_id' => $org->id,
    'post_id' => $president_post->id,
    'user_id' => $user1->id,
    'name' => 'John Smith',
    'position_order' => 1,
    'status' => 'approved',
]);

$candidacy2 = Candidacy::create([
    'organisation_id' => $org->id,
    'post_id' => $president_post->id,
    'user_id' => $user2->id,
    'name' => 'Jane Doe',
    'position_order' => 2,
    'status' => 'approved',
]);

// Verify structure
assert($election->posts->count() === 2);
assert($election->candidacies->count() === 2);
assert($president_post->candidacies->count() === 2);
```

### Example 2: Querying by Organisation

```php
// Set current organisation context
session(['current_organisation_id' => $org->id]);

// Get organisation's data
$org = Organisation::find($org_id);

// These all automatically scope to current org
$elections = Election::all();           // Only this org's elections
$posts = Post::all();                   // Only this org's posts
$candidates = Candidacy::all();         // Only this org's candidates

// Without session context, use withoutGlobalScopes
$all_elections = Election::withoutGlobalScopes()
    ->where('organisation_id', $org_id)
    ->get();
```

### Example 3: Regional Voting

```php
$election = Election::find($election_id);
$voter_region = 'Bavaria';

// Get posts for this voter
$national_posts = $election->posts()
    ->where('is_national_wide', true)
    ->get();

$regional_posts = $election->posts()
    ->where('is_national_wide', false)
    ->where('state_name', $voter_region)
    ->get();

// Combine for voter's ballot
$ballot_posts = $national_posts->merge($regional_posts);

// For each post, get candidates
foreach ($ballot_posts as $post) {
    $candidates = $post->candidacies()
        ->where('status', 'approved')
        ->orderBy('position_order')
        ->get();

    echo $post->name . ": " . $candidates->count() . " candidates";
}
```

### Example 4: User Organisation Management

```php
// Create user and add to organisation
$user = User::factory()->forOrganisation($org)->create();

// Get user's primary organisation
$org = $user->organisation;

// Get all organisations this user belongs to
$orgs = $user->organisations;

// Get user's roles
$roles = $user->organisationRoles;

// Check if user is admin in org
$is_admin = $user->isOrganisationAdmin($org_id);

// Get user's specific role
$role = $user->getRoleInOrganisation($org_id);  // 'admin', 'member', etc.

// Add user to another organisation
$org2 = Organisation::factory()->tenant()->create();
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $org2->id,
    'role' => 'member'
]);

// Now user has two organisations
assert($user->organisations->count() === 2);
```

---

## Testing Patterns

### Test Structure (TDD-First)

```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\YourModel;
use App\Models\RelatedModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class YourModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function model_has_correct_relationship()
    {
        // Arrange - Create test data
        $org = Organisation::factory()->tenant()->create();
        $related = RelatedModel::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            // ... other fields
        ]);

        // Act - Use the relationship
        $model = YourModel::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'related_id' => $related->id,
        ]);

        // Assert - Verify the relationship works
        $this->assertEquals($related->id, $model->related->id);
        $this->assertIsIterable($model->related->models);
    }

    /** @test */
    public function scope_filters_correctly()
    {
        // Create two organisations' data
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $model1 = YourModel::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
        ]);

        $model2 = YourModel::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
        ]);

        // Test scope filtering
        $org1_models = YourModel::forOrganisation($org1->id)
            ->withoutGlobalScopes()
            ->get();

        $this->assertCount(1, $org1_models);
        $this->assertEquals($model1->id, $org1_models->first()->id);
    }
}
```

### Testing with Global Scopes

```php
// ❌ WRONG - Global scope filters nothing
$posts = Post::all();  // Empty! Session context not set

// ✅ CORRECT - Bypass for testing
$posts = Post::withoutGlobalScopes()
    ->where('organisation_id', $org_id)
    ->get();

// ✅ CORRECT - Use scope methods (they include withoutGlobalScopes())
$posts = Post::forOrganisation($org_id)->get();

// ✅ CORRECT - Set session context
session(['current_organisation_id' => $org_id]);
$posts = Post::all();  // Now works
```

### Testing Relationships

```php
/** @test */
public function election_has_many_candidacies_through_posts()
{
    $org = Organisation::factory()->tenant()->create();
    $election = Election::factory()->forOrganisation($org)->create();

    // Create post
    $post = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'name' => 'President',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Create candidacy
    $candidacy = Candidacy::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'post_id' => $post->id,
        'name' => 'John Doe',
        'status' => 'approved',
    ]);

    // Test hasManyThrough relationship
    $this->assertCount(1, $election->candidacies);
    $this->assertEquals(
        $candidacy->id,
        $election->candidacies->first()->id
    );
}
```

---

## Common Pitfalls

### ❌ Pitfall 1: Forgetting `withoutGlobalScopes()`

```php
// WRONG - BelongsToTenant scope blocks the query
public function posts()
{
    return $this->hasMany(Post::class);
}

// Calling this in a test will return empty because
// session('current_organisation_id') is not set

// CORRECT
public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();
}
```

**Fix:** Always add `->withoutGlobalScopes()` to relationship methods that load models with `BelongsToTenant` trait.

---

### ❌ Pitfall 2: Direct Election FK on Candidacy

```php
// WRONG - Candidacy doesn't have election_id column
$candidacy = Candidacy::where('election_id', $election_id)->get();

// CORRECT - Access through post
$candidacy = $election->candidacies;
$candidacy = Candidacy::whereHas('post', fn($q)
    => $q->where('election_id', $election_id)
)->get();
```

**Why:** Database normalization: `elections → posts → candidacies`. Never bypass it.

---

### ❌ Pitfall 3: Creating User<→Vote Relationships

```php
// ABSOLUTELY WRONG - BREAKS VOTE ANONYMITY
class User extends Model {
    public function votes()
    {
        return $this->hasMany(Vote::class);  // NO!
    }
}

class Vote extends Model {
    public function user()
    {
        return $this->belongsTo(User::class);  // NO!
    }
}

// This allows: $vote->user_id to be stored/queried
// which reveals who voted and for whom
```

**Fix:** Votes have NO user_id column. Ever. Period.

```php
// CORRECT - One-way from VoterSlug only
class VoterSlug extends Model {
    public function votes()
    {
        return $this->hasMany(Vote::class);  // VoterSlug → Vote (one-way)
    }
}
```

---

### ❌ Pitfall 4: Not Setting Organisation Context in Tests

```php
// WRONG - Session context not set
$posts = Post::all();  // Returns empty array

// CORRECT - Set session or use scope
session(['current_organisation_id' => $org->id]);
$posts = Post::all();  // Works!

// OR use scope (preferred for tests)
$posts = Post::forOrganisation($org->id)->get();
```

---

### ❌ Pitfall 5: Inconsistent Column Selection

```php
// WRONG - Selecting columns that don't exist in schema
public function candidacies()
{
    return $this->hasMany(Candidacy::class)
                ->select(['id', 'candidacy_id', 'user_id', ...]);
}

// candidacy_id doesn't exist in candidacies table!

// CORRECT - Let Laravel handle column selection
public function candidacies()
{
    return $this->hasMany(Candidacy::class)
                ->withoutGlobalScopes();
}
```

---

## Extending Models

### Adding a New Model Relationship

**Step 1: Create the model**
```php
php artisan make:model VoterSlug -m
```

**Step 2: Add migrations with organisation_id**
```php
// database/migrations/xxxx_xx_xx_create_voter_slugs_table.php
Schema::create('voter_slugs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');
    $table->uuid('election_id');
    $table->uuid('user_id');
    $table->string('slug')->unique();
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('organisation_id')->references('id')->on('organisations');
    $table->foreign('election_id')->references('id')->on('elections');
    $table->foreign('user_id')->references('id')->on('users');
});
```

**Step 3: Add traits and relationships**
```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class VoterSlug extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'election_id',
        'user_id',
        'slug',
    ];

    // Always include organisation relationship
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }

    public function election()
    {
        return $this->belongsTo(Election::class)
                    ->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class)
                    ->withoutGlobalScopes();
    }
}
```

**Step 4: Add inverse relationship to parent**
```php
// In Election model
public function voterSlugs()
{
    return $this->hasMany(VoterSlug::class)
                ->withoutGlobalScopes();
}
```

**Step 5: Write tests (TDD!)**
```php
// tests/Unit/Models/VoterSlugTest.php
class VoterSlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function voter_slug_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'slug' => 'voter-abc123',
        ]);

        $this->assertEquals($election->id, $slug->election->id);
    }
}
```

---

## Best Practices

### 1. **Always Use Explicit FKs**

```php
// ✅ GOOD - Explicit foreign key columns
$table->uuid('post_id')->references('id')->on('posts');

// ❌ BAD - Implicit Laravel conventions
// (Can hide relationships in complex schemas)
```

### 2. **Always Include `organisation_id`**

Every table MUST have `organisation_id` for multi-tenancy:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');  // ← REQUIRED
    $table->uuid('election_id');
    $table->string('name');
    // ...
});
```

### 3. **Bypass Global Scopes in Relationships**

All relationships that load scoped models need `withoutGlobalScopes()`:

```php
// ✅ CORRECT
public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();
}

// ❌ WRONG
public function posts()
{
    return $this->hasMany(Post::class);
}
```

### 4. **Use Query Scopes for Common Filters**

```php
// ✅ GOOD - Reusable scope
public function scopeForOrganisation($query, $org_id)
{
    return $query->withoutGlobalScopes()
                 ->where('organisation_id', $org_id);
}

// Usage
$posts = Post::forOrganisation($org_id)->get();

// ❌ BAD - Repetitive queries
$posts = Post::withoutGlobalScopes()
    ->where('organisation_id', $org_id)
    ->get();
```

### 5. **Never Store User IDs in Votes/Results**

```php
// ❌ CRITICAL VIOLATION - Vote anonymity broken
class Vote extends Model {
    protected $fillable = ['election_id', 'user_id', ...];
    // user_id in votes = voter can be identified!
}

// ✅ CORRECT - Vote anonymity preserved
class Vote extends Model {
    protected $fillable = ['election_id', 'voting_code', ...];
    // voting_code is anonymous hash, no user linkage
}
```

### 6. **Use Type Constants for Enum-like Fields**

```php
class Election extends Model {
    const TYPE_DEMO = 'demo';
    const TYPE_REAL = 'real';

    public function isDemo(): bool
    {
        return $this->type === self::TYPE_DEMO;
    }
}

// Usage
if ($election->isDemo()) {
    // Use demo tables
}
```

### 7. **Document Relationship Patterns**

```php
/**
 * Get all candidacies through posts relationship
 * This implements hasManyThrough pattern:
 * elections → posts → candidacies
 *
 * Why: Candidacies don't have direct election_id FK
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
 */
public function candidacies(): HasManyThrough
{
    return $this->hasManyThrough(
        Candidacy::class,      // Final model
        Post::class,           // Intermediate model
        'election_id',         // FK on posts
        'post_id',             // FK on candidacies
        'id',                  // Local key on elections
        'id'                   // Local key on posts
    )->withoutGlobalScopes();
}
```

### 8. **Test Tenant Isolation**

```php
/** @test */
public function cannot_access_other_organisation_data()
{
    $org1 = Organisation::factory()->tenant()->create();
    $org2 = Organisation::factory()->tenant()->create();

    $election1 = Election::factory()->forOrganisation($org1)->create();
    $election2 = Election::factory()->forOrganisation($org2)->create();

    // Set session to org1
    session(['current_organisation_id' => $org1->id]);

    // Should only see org1 elections (automatic via BelongsToTenant)
    $elections = Election::all();
    $this->assertCount(1, $elections);
    $this->assertEquals($election1->id, $elections->first()->id);
}
```

---

## Summary

The Phase A model relationships implementation provides:

✅ **UUID-based multi-tenancy** with explicit `organisation_id` scoping
✅ **TDD-first approach** with comprehensive test coverage
✅ **Vote anonymity enforcement** (no User→Vote relationships)
✅ **Correct data normalization** (Elections → Posts → Candidacies)
✅ **Reusable query scopes** for common filtering patterns
✅ **Global scope handling** for test isolation
✅ **Six core models** fully implemented with relationships

This foundation supports Phase B's voting models (Code, VoterSlug, Vote, Result) while maintaining strict architectural boundaries and security guarantees.

---

**Last Updated:** 2026-03-06
**Phase:** A (Core Model Relationships) - Complete ✅
**Tests:** 38 passing / 73 assertions
