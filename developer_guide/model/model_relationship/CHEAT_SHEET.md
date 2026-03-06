# Model Relationships Cheat Sheet

Quick reference for developers working with Phase A models.

---

## Model Hierarchy at a Glance

```
Organisation (platform or tenant)
├── hasMany Elections
│   ├── hasMany Posts
│   │   └── hasMany Candidacies
│   └── hasManyThrough Candidacies (via Posts)
├── hasMany Posts
├── hasMany UserOrganisationRoles
└── belongsToMany Users (via pivot)
```

---

## The Six Core Models

| Model | Primary Role | Key FK | Traits |
|-------|------------|--------|--------|
| **Organisation** | Root (platform/tenant) | None | HasFactory, HasUuids, SoftDeletes |
| **User** | Person in system | organisation_id | HasFactory, HasUuids, SoftDeletes, BelongsToTenant |
| **UserOrganisationRole** | User-Org bridge | user_id, org_id | HasFactory, HasUuids, SoftDeletes |
| **Election** | Election event | organisation_id | HasFactory, HasUuids, SoftDeletes, BelongsToTenant |
| **Post** | Position/seat | org_id, election_id | HasFactory, HasUuids, SoftDeletes, BelongsToTenant |
| **Candidacy** | Candidate | org_id, post_id, user_id(nullable) | HasFactory, HasUuids, SoftDeletes, BelongsToTenant |

---

## Creating Models

### Organisation
```php
$org = Organisation::factory()->tenant()->create();
$org = Organisation::factory()->platform()->state(['is_default' => true])->create();
```

### Election
```php
$election = Election::factory()->forOrganisation($org)->create();
$election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);
```

### Post
```php
$post = Post::create([
    'id' => Str::uuid()->toString(),
    'organisation_id' => $org->id,
    'election_id' => $election->id,
    'name' => 'President',
    'is_national_wide' => true,
    'required_number' => 1,
]);
```

### User (via DB insert)
```php
DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
    Str::uuid()->toString(),
    $org->id,
    'John Doe',
    'john@example.com',
    bcrypt('password'),
    now(),
    now(),
]);
$user = User::find($user_id);
```

### Candidacy
```php
$candidacy = Candidacy::create([
    'id' => Str::uuid()->toString(),
    'organisation_id' => $org->id,
    'post_id' => $post->id,
    'user_id' => $user->id,  // Optional
    'name' => 'John Smith',
    'description' => 'Bio here',
    'position_order' => 1,
    'status' => 'approved',
]);
```

### UserOrganisationRole
```php
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'admin',  // or 'member'
]);
```

---

## Accessing Relationships

### BelongsTo (Child → Parent)
```php
$post->organisation;      // Get parent org
$post->election;          // Get parent election
$candidacy->user;         // Get user (may be null)
$candidacy->post;         // Get post
```

### HasMany (Parent → Children)
```php
$org->elections;          // Get all elections in org
$election->posts;         // Get all posts in election
$post->candidacies;       // Get all candidacies for post
```

### HasManyThrough (Parent → Grandchildren)
```php
$election->candidacies;   // All candidates in election (via posts)
```

### BelongsToMany (Many-to-Many via Pivot)
```php
$org->users;              // Get all users in org
$user->organisations;     // Get all orgs user belongs to

// With pivot data
foreach ($org->users as $user) {
    echo $user->pivot->role;  // 'admin' or 'member'
}
```

---

## Querying with Scopes

```php
// By organisation
Post::forOrganisation($org->id)->get();
Election::forOrganisation($org->id)->get();
Candidacy::forOrganisation($org->id)->get();

// By election
Post::forElection($election->id)->get();
Candidacy::forElectionId($election->id)->get();

// By post
Candidacy::forPost($post->id)->get();

// By status
Candidacy::approved()->get();
Candidacy::pending()->get();

// Chained
Candidacy::forOrganisation($org->id)
    ->approved()
    ->where('post_id', $post->id)
    ->get();
```

---

## Filtering with Additional Constraints

```php
// National posts only
$national = $election->posts()
    ->where('is_national_wide', true)
    ->get();

// Regional posts for a state
$regional = $election->posts()
    ->where('is_national_wide', false)
    ->where('state_name', 'Bavaria')
    ->get();

// Approved candidates
$candidates = $post->approvedCandidacies;

// Admin users only
$admins = $org->users()
    ->wherePivot('role', 'admin')
    ->get();

// Or use helper
$admins = $org->admins();
```

---

## Handling Global Scopes

### In Tests (No Session Context)

```python
# ❌ Wrong - Returns empty array
elections = Election.all()

# ✅ Correct - Use scope method
elections = Election.forOrganisation(org_id).get()

# ✅ Correct - Explicit bypass
elections = Election.withoutGlobalScopes()
    .where('organisation_id', org_id)
    .get()

# ✅ Correct - Via relationship
elections = org.elections
```

### In Production (With Session Context)

```php
// Set context once at start of request
session(['current_organisation_id' => $org->id]);

// Auto-filtered queries
$elections = Election::all();
$posts = Post::all();

// Still works (auto-filtered)
$elections = $org->elections;
```

---

## Common Queries

### Get all data for an election
```php
$election = Election::find($id);

$posts = $election->posts;           // Direct
$candidates = $election->candidacies; // Via posts
$voters = $election->voterRegistrations;
$codes = $election->codes;
```

### Get ballot for a voter
```php
$national_posts = $election->posts()
    ->where('is_national_wide', true)
    ->get();

$regional_posts = $election->posts()
    ->where('is_national_wide', false)
    ->where('state_name', $user->region)
    ->get();

$ballot = $national_posts->merge($regional_posts);
```

### Check user's role
```php
$role = $user->getRoleInOrganisation($org_id);

if ($user->isOrganisationAdmin($org_id)) {
    // User is admin
}
```

### Add user to organisation
```php
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'member',
]);
```

---

## Type Checking

### Election Types
```php
if ($election->isDemo()) {
    // Demo election
}

if ($election->isReal()) {
    // Real election
}
```

### Election Activity
```php
if ($election->isCurrentlyActive()) {
    // Can vote now
}
```

### Organisation Types
```php
if ($org->isPlatform()) {
    // Platform organisation
}

if ($org->isTenant()) {
    // Tenant organisation
}
```

### Candidacy Status
```php
if ($candidacy->isApproved()) { ... }
if ($candidacy->isPending()) { ... }
if ($candidacy->isRejected()) { ... }
if ($candidacy->isWithdrawn()) { ... }
```

---

## Status Transitions

### Candidacy
```php
$candidacy->approve();    // → approved
$candidacy->reject();     // → rejected
$candidacy->withdraw();   // → withdrawn
```

---

## Testing Helpers

### Create Test Data
```php
// Create organisation
$org = Organisation::factory()->tenant()->create();

// Create election
$election = Election::factory()->forOrganisation($org)->create();

// Create post
$post = Post::create([...]);

// Create user (via DB due to trait issues)
DB::insert('insert into users...', [...]);
$user = User::find($user_id);

// Create candidacy
$candidacy = Candidacy::create([...]);

// Create pivot
UserOrganisationRole::create([...]);
```

### Test Relationships
```php
// Test belongs-to
$this->assertEquals($org->id, $post->organisation->id);

// Test has-many count
$this->assertCount(2, $election->posts);

// Test has-many-through
$this->assertCount(1, $election->candidacies);

// Test scope
$org1Posts = Post::forOrganisation($org1->id)->get();
$this->assertCount(1, $org1Posts);
```

### Bypass Global Scope
```php
Election::withoutGlobalScopes()->get();
Post::withoutGlobalScopes()->where('...');
```

---

## Performance Tips

### Eager Load Relations
```php
// ❌ N+1 queries
foreach ($elections as $election) {
    echo $election->organisation->name;
}

// ✅ Single query
$elections = Election::with('organisation')->get();
```

### Eager Load Nested Relations
```php
$elections = Election::with([
    'posts' => function($q) {
        $q->with('candidacies');
    }
])->get();
```

### Count Without Loading
```php
// ❌ Loads all records
$count = $election->posts->count();

// ✅ Single count query
$count = $election->posts()->count();
```

---

## Common Mistakes

| ❌ Wrong | ✅ Correct | Why |
|---------|-----------|-----|
| `Post::all()` (no session) | `Post::forOrganisation($id)->get()` | Global scope filters |
| `$candidacy->election` | `$candidacy->post->election` | No direct FK |
| `$vote->user()` | Cannot exist | Breaks anonymity |
| `$relationship` without check | `if ($relationship) { ... }` | Nullable relationships |
| `Election::all()` in test | `Election::withoutGlobalScopes()->where(...)` | No session context |

---

## File Locations

```
app/Models/
  ├── Organisation.php
  ├── User.php
  ├── UserOrganisationRole.php
  ├── Election.php
  ├── Post.php
  └── Candidacy.php

tests/Unit/Models/
  ├── OrganisationTest.php
  ├── UserTest.php
  ├── UserOrganisationRoleTest.php
  ├── ElectionTest.php
  ├── PostTest.php
  └── CandidacyTest.php

developer_guide/model_relationship/
  ├── README.md                        (main guide)
  ├── RELATIONSHIP_PATTERNS.md         (patterns reference)
  ├── TESTING_GUIDE.md                 (testing patterns)
  ├── ARCHITECTURE_DECISIONS.md        (design decisions)
  └── CHEAT_SHEET.md                   (this file)
```

---

## Running Tests

```bash
# All model tests
php artisan test tests/Unit/Models/

# Single test class
php artisan test tests/Unit/Models/ElectionTest.php

# Single test method
php artisan test tests/Unit/Models/ElectionTest.php --filter="election_has_many_posts"

# With coverage
php artisan test tests/Unit/Models/ --coverage
```

---

## Key Rules

1. ✅ **Always include `organisation_id`** in tenant-scoped models
2. ✅ **Always add `withoutGlobalScopes()`** to relationships that load tenant-scoped models
3. ✅ **Never create User→Vote relationships** (breaks vote anonymity)
4. ✅ **Access Election from Candidacy via Post** (`$candidacy->post->election`)
5. ✅ **Use query scopes** for common filters
6. ✅ **Set session context** in production: `session(['current_organisation_id' => $id])`
7. ✅ **Bypass scopes in tests** with `withoutGlobalScopes()` or scope methods

---

## Getting Help

- **Architecture Questions:** See ARCHITECTURE_DECISIONS.md
- **Pattern Examples:** See RELATIONSHIP_PATTERNS.md
- **Testing Help:** See TESTING_GUIDE.md
- **Full Documentation:** See README.md

---

**Last Updated:** 2026-03-06
**Phase:** A (Complete)
**Tests:** 38 passing / 73 assertions ✅
