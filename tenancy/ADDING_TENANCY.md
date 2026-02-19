# Adding Multi-Tenancy to New Models

Step-by-step guide to add multi-tenancy to any new or existing model.

## Overview

To make a model tenant-aware, you need to:
1. ✅ Add `BelongsToTenant` trait
2. ✅ Update $fillable array
3. ✅ Create migration adding `organisation_id` column
4. ✅ Test the implementation
5. ✅ Update related models if needed

**Time Required**: 5-10 minutes per model

## Step-by-Step Guide

### Step 1: Add Trait to Model

**File**: `app/Models/YourModel.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;  // ← ADD THIS LINE

class YourModel extends Model
{
    use HasFactory;
    use BelongsToTenant;  // ← ADD THIS LINE

    protected $fillable = [
        'organisation_id',  // ← ADD THIS
        'name',
        'description',
        // ... other fields
    ];

    // ... rest of model
}
```

### Step 2: Create Migration

Run the migration command:

```bash
php artisan make:migration add_organisation_id_to_your_models_table --table=your_models
```

This creates a file like:
```
database/migrations/2026_02_19_XXXXXX_add_organisation_id_to_your_models_table.php
```

### Step 3: Implement Migration

**File**: The migration created above

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToYourModelsTable extends Migration
{
    public function up()
    {
        Schema::table('your_models', function (Blueprint $table) {
            // Check to prevent duplicate columns
            if (!Schema::hasColumn('your_models', 'organisation_id')) {
                // Add column with these properties:
                // - unsignedBigInteger: matches organisation foreign key
                // - nullable: allows default platform records (org_id = null)
                // - after('id'): position in table
                // - index(): fast lookups
                $table->unsignedBigInteger('organisation_id')
                      ->nullable()
                      ->after('id')
                      ->index();
            }
        });
    }

    public function down()
    {
        Schema::table('your_models', function (Blueprint $table) {
            if (Schema::hasColumn('your_models', 'organisation_id')) {
                $table->dropIndex(['organisation_id']);
                $table->dropColumn('organisation_id');
            }
        });
    }
}
```

### Step 4: Run Migration

```bash
php artisan migrate
```

Expected output:
```
Migrating: 2026_02_19_XXXXXX_add_organisation_id_to_your_models_table
Migrated: 2026_02_19_XXXXXX_add_organisation_id_to_your_models_table
```

### Step 5: Test It Works

#### Test 5a: Auto-Fill

In `php artisan tinker`:

```php
// Set tenant context
session(['current_organisation_id' => 1]);

// Create record without setting organisation_id
$record = App\Models\YourModel::create([
    'name' => 'Test',
]);

// Check if organisation_id was auto-filled
echo $record->organisation_id;  // Should output: 1
```

Expected output:
```
1
```

#### Test 5b: Automatic Scoping

Still in `php artisan tinker`:

```php
// Create records for different orgs
session(['current_organisation_id' => 1]);
App\Models\YourModel::create(['name' => 'Org1 Record']);

session(['current_organisation_id' => 2]);
App\Models\YourModel::create(['name' => 'Org2 Record']);

// Query as org 1
session(['current_organisation_id' => 1]);
$records = App\Models\YourModel::all();

echo $records->count();  // Should output: 1 (only org1's record)
```

Expected output:
```
1
```

### Step 6: Add Tests (Optional but Recommended)

**File**: `tests/Feature/TenantIsolationTest.php`

Add a test method:

```php
/**
 * @test
 * Test that YourModels are scoped by organisation
 */
public function test_your_models_are_scoped_by_organisation()
{
    // Arrange: Create records for different orgs
    $this->actAsUser($this->org1User);
    \App\Models\YourModel::create(['name' => 'Org1 Record']);

    $this->actAsUser($this->org2User);
    \App\Models\YourModel::create(['name' => 'Org2 Record']);

    // Act: Query as org1
    $this->actAsUser($this->org1User);
    $records = \App\Models\YourModel::all();

    // Assert: Should only see org1's record
    $this->assertCount(1, $records);
}

/**
 * @test
 * Test that new YourModels auto-fill organisation_id
 */
public function test_new_your_model_auto_fills_organisation_id()
{
    // Arrange
    $this->actAsUser($this->org1User);

    // Act: Create without setting org_id
    $record = \App\Models\YourModel::create([
        'name' => 'New Record',
    ]);

    // Assert: org_id should be auto-filled
    $this->assertEquals(1, $record->organisation_id);
}
```

Run tests:
```bash
php artisan test tests/Feature/TenantIsolationTest.php
```

## Common Scenarios

### Scenario 1: Model with Relationships

If your model has relationships, also add tenancy to related models:

```php
// app/Models/YourModel.php
class YourModel extends Model
{
    use BelongsToTenant;

    public function relatedModels()
    {
        return $this->hasMany(RelatedModel::class);
    }
}

// app/Models/RelatedModel.php
class RelatedModel extends Model
{
    use BelongsToTenant;  // ← Add here too!

    public function yourModel()
    {
        return $this->belongsTo(YourModel::class);
    }
}
```

### Scenario 2: Model Inheriting from Base Class

If your model extends a base class, add tenancy to the base:

```php
// app/Models/BaseModel.php
abstract class BaseModel extends Model
{
    use BelongsToTenant;  // ← Add here
}

// app/Models/ChildModel.php
class ChildModel extends BaseModel
{
    // Automatically has tenancy from parent
}
```

### Scenario 3: Model with Pivot Table

For many-to-many relationships:

```php
// Create pivot table with organisation_id
php artisan make:migration create_model_related_table

// In the migration:
Schema::create('model_related', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('model_id');
    $table->unsignedBigInteger('related_id');
    $table->unsignedBigInteger('organisation_id')  // ← Add this
          ->nullable()
          ->index();
    $table->timestamps();

    $table->foreign('model_id')->references('id')->on('models');
    $table->foreign('related_id')->references('id')->on('relateds');
});
```

### Scenario 4: Existing Model Without Tenancy

To add tenancy to an existing model with data:

```bash
# 1. Create migration
php artisan make:migration add_organisation_id_to_existing_models_table

# 2. In migration up():
# Set all existing records to a default org_id
DB::table('existing_models')->update(['organisation_id' => 1]);

# 3. Make column not nullable if all records should have an org
$table->unsignedBigInteger('organisation_id')
      ->after('id')
      ->index();
      // Remove ->nullable() if you want to enforce tenant
```

## Verification Checklist

After adding tenancy to a model, verify:

### Code Changes
- [ ] ✅ Trait imported: `use App\Traits\BelongsToTenant;`
- [ ] ✅ Trait used: `use BelongsToTenant;`
- [ ] ✅ Fillable updated: `'organisation_id'` added
- [ ] ✅ Related models also updated if needed
- [ ] [ ] Migration created with correct table name
- [ ] [ ] Migration has up() and down() methods
- [ ] [ ] Migration checks for existing column
- [ ] [ ] Migration runs without errors

### Testing
- [ ] Auto-fill test passes
- [ ] Scoping test passes
- [ ] Tests cover both create and read operations
- [ ] No test failures introduced

### Database
- [ ] Column appears in database
- [ ] Column is indexed
- [ ] Column allows nulls (for platform users)
- [ ] No data corruption during migration

## Troubleshooting

### Issue: "Unknown column 'organisation_id' in 'field list'"

**Cause**: Migration hasn't been run yet

**Solution**:
```bash
php artisan migrate
```

### Issue: "Column 'organisation_id' doesn't have a default value"

**Cause**: Trying to insert without setting organisation_id and session context not set

**Solution**:
```php
// Make sure session is set before creating records
session(['current_organisation_id' => 1]);

// Then create
$record = Model::create(['name' => 'Test']);
```

### Issue: "SQLSTATE[42S02]: Base table or view not found"

**Cause**: Migration failed or table doesn't exist

**Solution**:
```bash
# Check migration status
php artisan migrate:status

# Re-run failed migrations
php artisan migrate
```

### Issue: Getting empty results from queries

**Cause**: Session context not set

**Solution**:
```php
// Always set session before querying
session(['current_organisation_id' => 1]);

// Then query
$records = Model::all();
```

### Issue: Duplicate migration files

**Cause**: Running migration command multiple times

**Solution**:
```bash
# Delete the duplicate migrations
rm database/migrations/duplicate_migration.php

# Keep only one version
```

## Migration Template

Use this template for adding tenancy to new models:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToXxxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xxx', function (Blueprint $table) {
            // Idempotent: check before adding
            if (!Schema::hasColumn('xxx', 'organisation_id')) {
                // Add with standard multi-tenancy properties
                $table->unsignedBigInteger('organisation_id')
                      ->nullable()
                      ->after('id')
                      ->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xxx', function (Blueprint $table) {
            // Safe removal
            if (Schema::hasColumn('xxx', 'organisation_id')) {
                $table->dropIndex(['organisation_id']);
                $table->dropColumn('organisation_id');
            }
        });
    }
}
```

## Model Template

Use this template for adding tenancy to new models:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

/**
 * App\Models\YourModel
 *
 * @property int $id
 * @property int $organisation_id - Tenant identifier
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class YourModel extends Model
{
    use HasFactory;
    use BelongsToTenant; // Provides automatic tenant scoping

    protected $fillable = [
        'organisation_id', // Required for auto-fill to work
        'name',
        'description',
    ];

    /**
     * Relationships
     */
    // Add your relationships here
}
```

## Best Practices

### 1. Always Set fillable

```php
// ❌ Wrong - forget 'organisation_id'
protected $fillable = ['name', 'description'];

// ✅ Correct - include 'organisation_id'
protected $fillable = ['organisation_id', 'name', 'description'];
```

### 2. Set Session Before Operating

```php
// ❌ Wrong - session not set
$record = Model::create(['name' => 'Test']);

// ✅ Correct - session set first
session(['current_organisation_id' => 1]);
$record = Model::create(['name' => 'Test']);
```

### 3. Test Tenant Isolation

```php
// ❌ Wrong - no cross-tenant test
public function test_create_record()
{
    $record = Model::create(['name' => 'Test']);
    $this->assertNotNull($record);
}

// ✅ Correct - verify isolation
public function test_tenant_isolation()
{
    session(['current_organisation_id' => 1]);
    Model::create(['name' => 'Org1']);

    session(['current_organisation_id' => 2]);
    Model::create(['name' => 'Org2']);

    session(['current_organisation_id' => 1]);
    $this->assertCount(1, Model::all());
}
```

### 4. Update Related Models

```php
// ❌ Wrong - only update parent
class Post extends Model
{
    use BelongsToTenant;
}

// ✅ Correct - update all related models
class Post extends Model
{
    use BelongsToTenant;
}

class Comment extends Model
{
    use BelongsToTenant;  // Add here too!
}
```

## Summary Checklist

When adding tenancy to a model:

1. [ ] Add trait to model
2. [ ] Update $fillable array
3. [ ] Create migration
4. [ ] Implement migration with proper structure
5. [ ] Run migration
6. [ ] Test auto-fill
7. [ ] Test scoping
8. [ ] Add unit tests
9. [ ] Update related models
10. [ ] Review and verify

---

**Total Time**: 5-10 minutes per model

**Complexity**: Low (copy-paste from template)

**Risk**: Very Low (scoped to single model)

**Benefit**: Complete tenant isolation for new model

Next: See [TESTING.md](./TESTING.md) for comprehensive testing guide.
