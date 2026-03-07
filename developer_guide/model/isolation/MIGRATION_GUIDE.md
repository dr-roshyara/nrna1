# 🔄 Adding Isolation to Existing Models

## Overview

If you have a model that needs organisation isolation but doesn't have it yet, follow this step-by-step guide.

---

## Pre-Checklist

Before starting, verify:
- [ ] Model exists: `app/Models/MyModel.php`
- [ ] Table exists: `my_models` (plural)
- [ ] Column exists: `organisation_id` UUID in migration
- [ ] Foreign key exists: `organisation_id → organisations.id`
- [ ] Test file exists or create: `tests/Feature/MyModelIsolationTest.php`

---

## Step 1: Add Trait to Model

**File:** `app/Models/MyModel.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;  // ← ADD THIS IMPORT

class MyModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;  // ← ADD TRAIT HERE

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',  // ← ENSURE THIS IS FILLABLE
        'name',
        'description',
        // ... other fields
    ];

    // Define relationships with withoutGlobalScopes to avoid double-filtering:
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();  // ← IMPORTANT
    }
}
```

---

## Step 2: Verify Migration Has organisation_id

**File:** `database/migrations/YYYY_MM_DD_create_my_models_table.php`

Check that the migration includes:

```php
Schema::create('my_models', function (Blueprint $table) {
    $table->uuid('id')->primary();

    // ✅ REQUIRED: UUID foreign key
    $table->uuid('organisation_id');

    // Other columns...
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();  // If using soft deletes

    // ✅ REQUIRED: Foreign key constraint
    $table->foreign('organisation_id')
        ->references('id')
        ->on('organisations')
        ->onDelete('cascade');

    // ✅ RECOMMENDED: Composite index for performance
    $table->index(['organisation_id', 'status']);  // if status column exists
    // OR:
    $table->index(['organisation_id', 'created_at']);  // common pattern
});
```

### If `organisation_id` Column Missing

Create a new migration to add it:

```php
// database/migrations/YYYY_MM_DD_add_organisation_id_to_my_models.php

Schema::table('my_models', function (Blueprint $table) {
    // Add the UUID column
    $table->uuid('organisation_id')->after('id')->nullable();

    // Add foreign key
    $table->foreign('organisation_id')
        ->references('id')
        ->on('organisations')
        ->onDelete('cascade');

    // Add index
    $table->index(['organisation_id']);
});

// Then BACKFILL existing data:
// UPDATE my_models SET organisation_id = (SELECT id FROM organisations WHERE type = 'platform' LIMIT 1)
// WHERE organisation_id IS NULL;

// Then make it non-nullable:
// ALTER TABLE my_models MODIFY organisation_id VARCHAR(36) NOT NULL;
```

---

## Step 3: Update Relationships

**File:** `app/Models/MyModel.php`

For all relationships that point to organisational data, add `withoutGlobalScopes()`:

```php
// ✅ CORRECT - prevents double-filtering
public function organisation()
{
    return $this->belongsTo(Organisation::class)
                ->withoutGlobalScopes();
}

public function election()
{
    return $this->belongsTo(Election::class)
                ->withoutGlobalScopes();  // Election also has BelongsToTenant
}

public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();  // Post also has BelongsToTenant
}

// ✅ But for relationships to unscoped models (like User), don't add it:
public function user()
{
    return $this->belongsTo(User::class);  // User doesn't have BelongsToTenant by design
}
```

---

## Step 4: Write Tests

**File:** `tests/Feature/MyModelIsolationTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MyModel;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyModelIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $orgA;
    protected Organisation $orgB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgA = Organisation::factory()->create(['type' => 'tenant']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant']);
    }

    /** @test */
    public function test_all_queries_return_only_current_org_records()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $itemA = MyModel::factory()->create(['organisation_id' => $this->orgA->id]);
        $itemB = MyModel::factory()->create(['organisation_id' => $this->orgB->id]);

        $items = MyModel::all();

        $this->assertCount(1, $items);
        $this->assertEquals($itemA->id, $items->first()->id);
    }

    /** @test */
    public function test_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $itemB = MyModel::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = MyModel::find($itemB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_create_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $item = MyModel::create(['name' => 'Test']);

        $this->assertEquals($this->orgA->id, $item->organisation_id);
    }

    /** @test */
    public function test_count_is_scoped()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        MyModel::factory()->count(3)->create(['organisation_id' => $this->orgA->id]);
        MyModel::factory()->count(2)->create(['organisation_id' => $this->orgB->id]);

        $count = MyModel::count();

        $this->assertEquals(3, $count);
    }
}
```

---

## Step 5: Run Tests

```bash
php artisan test tests/Feature/MyModelIsolationTest.php
```

Expected output:
```
PASS  Tests\Feature\MyModelIsolationTest
✓ all queries return only current org records
✓ find returns null for other org
✓ create auto fills organisation id
✓ count is scoped
```

---

## Step 6: Update Controllers/Services

Find any places where you manually filter by `organisation_id` and remove them:

**Before (❌ Manual filtering):**
```php
public function getItems()
{
    $items = MyModel::where('organisation_id', session('current_organisation_id'))
        ->where('status', 'active')
        ->get();

    return $items;
}
```

**After (✅ Let trait handle it):**
```php
public function getItems()
{
    // organisation_id filtering is automatic
    $items = MyModel::where('status', 'active')->get();

    return $items;
}
```

---

## Step 7: Update Existing Data (If Needed)

If you're adding `organisation_id` to an existing table with data, you need to backfill:

```php
// Create migration for backfilling
// database/migrations/YYYY_MM_DD_backfill_organisation_id_to_my_models.php

Schema::table('my_models', function (Blueprint $table) {
    // Backfill with platform org for existing records
    DB::statement(
        'UPDATE my_models SET organisation_id = ? WHERE organisation_id IS NULL',
        [Organisation::where('slug', 'platform')->value('id')]
    );
});
```

---

## Verification Checklist

After completing all steps:

- [ ] Trait added to model: `use BelongsToTenant;`
- [ ] `organisation_id` in `$fillable` array
- [ ] Migration has `organisation_id` UUID column
- [ ] Migration has foreign key constraint
- [ ] Migration has composite index
- [ ] Relationships use `withoutGlobalScopes()` where appropriate
- [ ] Tests written and passing (4+ tests minimum)
- [ ] Manual `where('organisation_id', ...)` removed from code
- [ ] Existing data backfilled (if applicable)
- [ ] Full test suite passes: `php artisan test`

---

## Troubleshooting

### Error: "Unknown column 'organisation_id'"

The migration needs to be run:
```bash
php artisan migrate
```

### Error: "Column not found: organisation_id"

The model's database table doesn't have the column. Check migration and run it.

### Records from other orgs showing up

The `BelongsToTenant` trait isn't being used. Verify:
1. Trait is imported: `use App\Traits\BelongsToTenant;`
2. Trait is in use statement: `use ... BelongsToTenant;`
3. No `withoutGlobalScopes()` accidentally applied to base query

### Test passes but production fails

Usually means the middleware isn't setting the session in your controller/service. Verify:
```php
// Check that middleware ran
if (session('current_organisation_id')) {
    // Good, middleware set the context
} else {
    // Bad, session not set - middleware issue
}
```

---

## Common Pitfalls

### ❌ Pitfall 1: Forgetting withoutGlobalScopes() in relationships

```php
// ❌ WRONG - causes double-filtering
public function organisation()
{
    return $this->belongsTo(Organisation::class);
}

// ✅ RIGHT
public function organisation()
{
    return $this->belongsTo(Organisation::class)
                ->withoutGlobalScopes();
}
```

### ❌ Pitfall 2: Manual filtering still in code

```php
// ❌ WRONG - redundant
MyModel::where('organisation_id', session('current_organisation_id'))->get();

// ✅ RIGHT - trait handles it
MyModel::all();
```

### ❌ Pitfall 3: Not setting session in tests

```php
// ❌ WRONG - no session context
$items = MyModel::all();

// ✅ RIGHT - set context first
session(['current_organisation_id' => $org->id]);
$items = MyModel::all();
```

---

## Performance Tips

### Use Composite Indexes

```php
// Migration should have:
$table->index(['organisation_id', 'status']);
// OR
$table->index(['organisation_id', 'created_at']);
```

### Avoid N+1 Queries

Use eager loading:
```php
// ❌ N+1 - bad
foreach ($items as $item) {
    echo $item->organisation->name;
}

// ✅ Eager load - good
$items = MyModel::with('organisation')->get();
foreach ($items as $item) {
    echo $item->organisation->name;
}
```

---

## Questions?

Refer to:
- **Main Guide:** `developer_guide/model/isolation/README.md`
- **Quick Reference:** `developer_guide/model/isolation/QUICK_REFERENCE.md`
- **Architecture:** `architecture/model/isolate_organisations/`
