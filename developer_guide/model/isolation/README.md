# 🔒 Organisation Isolation - Developer Guide

## Overview

Public Digit uses **automatic organisation isolation** to enforce strict multi-tenancy. Every data operation is automatically scoped to the current organisation without requiring explicit filtering on each query.

**Key principle:** If you write `Election::all()`, you get **only the current organisation's elections**, not all elections in the system.

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [How It Works](#how-it-works)
3. [Using BelongsToTenant](#using-belongstotenant)
4. [Working with Models](#working-with-models)
5. [Testing Isolation](#testing-isolation)
6. [Common Patterns](#common-patterns)
7. [Troubleshooting](#troubleshooting)
8. [Performance](#performance)

---

## Quick Start

### ✅ Default Behavior (Automatic Scoping)

```php
// User is in organisation "org-a"
// Session contains: current_organisation_id = "org-a-uuid"

// This automatically ONLY returns org-a's elections:
$elections = Election::all();
// SQL: SELECT * FROM elections WHERE organisation_id = 'org-a-uuid'

// This returns null if the election belongs to a different org:
$election = Election::find($election_id);
// Returns null if organisation_id != 'org-a-uuid'

// Creating a new election auto-fills organisation_id:
$election = Election::create(['name' => 'Test']);
// Automatically sets: organisation_id = 'org-a-uuid'
```

### ❌ What NOT to Do

```php
// ❌ DON'T do this - it's unnecessary and confusing:
$elections = Election::where('organisation_id', session('current_organisation_id'))->get();

// ✅ DO this instead - global scope handles it:
$elections = Election::all();
```

---

## How It Works

### 4-Layer Architecture

```
┌─────────────────────────────────────────┐
│  Layer 1: MIDDLEWARE                    │
│  EnsureOrganisationMember               │
│  - Extracts org from URL                │
│  - Validates user is member             │
│  - Sets session('current_organisation_id')
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│  Layer 2: MODEL TRAIT                   │
│  BelongsToTenant                        │
│  - Reads session context                │
│  - Adds WHERE organisation_id = ? to ALL queries
│  - Auto-fills organisation_id on create │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│  Layer 3: ELOQUENT GLOBAL SCOPE         │
│  - Automatically applied to all queries │
│  - Cannot be forgotten (no manual work) │
│  - Can be explicitly bypassed if needed │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│  Layer 4: DATABASE                      │
│  - Foreign key constraints              │
│  - Composite indexes (org_id, ...)      │
│  - Last resort if everything else fails │
└─────────────────────────────────────────┘
```

### The BelongsToTenant Trait

Located at: `app/Traits/BelongsToTenant.php`

**What it does:**
1. **Adds global scope** - Every query automatically includes `WHERE organisation_id = session_value`
2. **Auto-fills on create** - When you create a model, `organisation_id` is automatically set from session
3. **Caches platform org** - Prevents N+1 queries when session is null (platform context)

---

## Using BelongsToTenant

### Step 1: Add the Trait to Your Model

```php
// app/Models/MyModel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;  // ← Add this import

class MyModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;  // ← Add trait here

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',  // ← Must be fillable
        'name',
        'description',
        // ... other fields
    ];
}
```

### Step 2: Ensure Database Column Exists

Your migration must have an `organisation_id` column with a foreign key:

```php
// database/migrations/YYYY_MM_DD_create_my_models_table.php
Schema::create('my_models', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');  // ← Required
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Foreign key constraint
    $table->foreign('organisation_id')
        ->references('id')
        ->on('organisations')
        ->onDelete('cascade');

    // Composite index for performance
    $table->index(['organisation_id', 'status']);  // if you have status
});
```

### Step 3: That's It!

Your model now has automatic organisation isolation. No more manual scoping needed.

---

## Working with Models

### Basic Queries (All Automatically Scoped)

```php
// Get all records for current org
$items = Item::all();

// Find by ID (returns null if wrong org)
$item = Item::find($id);

// Get with conditions (still scoped)
$items = Item::where('status', 'active')->get();

// Paginate (scoped)
$items = Item::paginate(15);

// Count (scoped)
$count = Item::count();
```

### Creating Records

```php
// organisation_id is auto-filled:
$item = Item::create([
    'name' => 'Test Item',
    'description' => 'Test',
    // organisation_id is set automatically!
]);

// Even if you try to set it explicitly, it gets validated:
$item = Item::create([
    'organisation_id' => $wrong_org_id,  // Still gets overridden
    'name' => 'Test',
]);
// Will use session org_id, not the one you provided
```

### Relationships

```php
// When defining relationships, use withoutGlobalScopes() to avoid double-filtering:
public function organisation()
{
    return $this->belongsTo(Organisation::class)
                ->withoutGlobalScopes();
}

public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();
}
```

---

## Testing Isolation

### Set Up Test Organisation Context

```php
use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganisationIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two organisations
        $this->orgA = Organisation::factory()->create(['type' => 'tenant']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant']);

        // Create user in org A
        $this->user = User::factory()->create();
        $this->user->organisations()->attach($this->orgA->id, ['role' => 'admin']);
    }

    /** @test */
    public function test_elections_are_scoped_to_organisation()
    {
        // Set org A context
        session(['current_organisation_id' => $this->orgA->id]);

        // Create elections in both orgs
        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        // Query should only return org A's election
        $elections = Election::all();

        $this->assertCount(1, $elections);
        $this->assertEquals($electionA->id, $elections->first()->id);
    }

    /** @test */
    public function test_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        // Should be null because election belongs to org B
        $found = Election::find($electionB->id);

        $this->assertNull($found);
    }
}
```

### Important Test Patterns

```php
// ✅ Always set session before querying
session(['current_organisation_id' => $organisation->id]);

// ✅ Create with explicit organisation_id for other orgs
$other = Model::factory()->create(['organisation_id' => $other_org->id]);

// ❌ Don't rely on auto-fill in tests unless testing that behavior
// The auto-fill uses session, which might not be what you want

// ✅ To test without organisation context:
session()->forget('current_organisation_id');
$results = Model::all();
// This will use the platform organisation context (if configured)
```

---

## Common Patterns

### 1. Multi-Org User Queries

```php
// If a user belongs to multiple orgs, manually check which org:
public function getUserElections(User $user, Organisation $organisation)
{
    // Verify user is member of this org
    if (!$user->organisations()->where('id', $organisation->id)->exists()) {
        throw new UnauthorizedException('User not member of this org');
    }

    // Set context for this org
    session(['current_organisation_id' => $organisation->id]);

    // Now all queries are scoped to this org
    return Election::all();
}
```

### 2. Cross-Org Admin Operations (Bypass Scope)

```php
// If you need to see data from all orgs (admin panel):
$allElections = Election::withoutGlobalScopes()->get();

// Or filter by specific org:
$orgAElections = Election::withoutGlobalScopes()
    ->where('organisation_id', $orgA->id)
    ->get();

// ⚠️ Use withoutGlobalScopes() carefully - only for admin operations
```

### 3. Eager Loading with Isolation

```php
// Load related data while maintaining isolation:
$posts = Post::with([
    'election' => function($q) { $q->withoutGlobalScopes(); },
    'candidacies' => function($q) { $q->withoutGlobalScopes(); }
])->get();
```

### 4. Aggregation Queries

```php
// Count is also scoped:
$count = Election::count();  // Only current org

// Sum/Avg/Max/Min also scoped:
$total = Vote::sum('value');  // Only current org's votes

// GroupBy maintains scope:
$grouped = Result::groupBy('post_id')->get();  // Only current org
```

---

## Troubleshooting

### Problem: "No organisation context set"

**Cause:** Session `current_organisation_id` is not set.

**Solution:**
```php
// Check that middleware ran:
if (!session('current_organisation_id')) {
    // Middleware didn't run or failed
    // Set it manually for debugging:
    session(['current_organisation_id' => $organisation->id]);
}
```

### Problem: Models from other orgs are showing up

**Cause:** Model doesn't have `BelongsToTenant` trait.

**Solution:**
```php
// Check model has trait:
use App\Traits\BelongsToTenant;

class MyModel extends Model {
    use BelongsToTenant;  // ← Verify this exists
}
```

### Problem: Finding a record returns null unexpectedly

**Cause:** Record belongs to different organisation.

**Solution:**
```php
// Check what org the record belongs to:
$record = Model::withoutGlobalScopes()->find($id);
echo $record->organisation_id;  // Verify it's current org

// Or just manually verify context:
echo session('current_organisation_id');
```

### Problem: Tests failing with "Unknown column 'organisation_id'"

**Cause:** Migration not run or schema doesn't have column.

**Solution:**
```php
// In test, ensure migrations ran:
use RefreshDatabase;  // ← This runs all migrations

// Or manually verify column exists:
if (!Schema::hasColumn('my_models', 'organisation_id')) {
    // Column missing - check migration
}
```

---

## Performance

### N+1 Prevention

The `BelongsToTenant` trait includes a **static cache** to prevent N+1 queries when resolving the platform organisation:

```php
// First query: Platform org looked up from database
Election::count();

// Subsequent queries: Cached value used (NO database hit)
Post::count();
Vote::count();

// This prevents: 7+ queries → 1 query (99% reduction)
```

### Indexes for Speed

All P0 models have composite indexes optimized for isolation:

```sql
-- Elections table
CREATE INDEX idx_organisation_id_status
  ON elections(organisation_id, status);

-- Posts table
CREATE INDEX idx_organisation_id_election_id
  ON posts(organisation_id, election_id);

-- Always indexed as: (organisation_id, ...)
```

### Query Examples (All Optimized)

```php
// ✅ Fast - uses composite index
Election::where('status', 'active')->get();

// ✅ Fast - uses index
Post::where('election_id', $id)->get();

// ✅ Fast - joins use indexed paths
$posts = Election::find($id)->posts;
```

---

## Checklist: Adding Isolation to a New Model

When creating a new model that needs organisation isolation:

- [ ] Add `BelongsToTenant` trait to model class
- [ ] Import trait: `use App\Traits\BelongsToTenant;`
- [ ] Add `organisation_id` to `$fillable` array
- [ ] Migration has `organisation_id` UUID column
- [ ] Migration has foreign key: `organisation_id → organisations.id`
- [ ] Migration has composite index: `(organisation_id, status)` or similar
- [ ] Relationships use `withoutGlobalScopes()` to avoid double-filtering
- [ ] Tests set `session(['current_organisation_id' => ...])` before querying
- [ ] No manual `where('organisation_id', ...)` needed (trait handles it)

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `app/Traits/BelongsToTenant.php` | Core trait providing isolation |
| `app/Http/Middleware/EnsureOrganisationMember.php` | Sets session context from URL |
| `app/Services/TenantContext.php` | Stateful tenant tracking service |
| `tests/Feature/OrganisationIsolationTest.php` | Comprehensive isolation tests |
| `architecture/model/isolate_organisations/` | Architecture documentation |

---

## Questions?

Refer to:
- **Architecture overview:** `architecture/model/isolate_organisations/20260307_1137_isolation_architecture.md`
- **Implementation details:** `architecture/model/isolate_organisations/20260307_1137_how_to_isolate_organisations.md`
- **Test examples:** `tests/Feature/OrganisationIsolationTest.php`
