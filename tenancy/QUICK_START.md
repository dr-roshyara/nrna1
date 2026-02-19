# Multi-Tenancy Quick Start (5 Minutes)

Get up and running with multi-tenancy in this application.

## What is Multi-Tenancy?

**Multi-tenancy** means one application instance serves multiple organizations (tenants), with complete data isolation between them.

In this application:
- 🏢 Each organization has its own elections, candidates, votes, etc.
- 🔒 Users from Org A cannot see data from Org B
- 📊 Queries automatically filter by organization
- ✅ All automatic - you don't need to manually scope queries

## How It Works (The Magic)

```php
// When you do this:
$elections = Election::all();

// It automatically becomes this:
$elections = Election::where('organisation_id', session('current_organisation_id'))->get();

// But you don't have to write it! The BelongsToTenant trait handles it.
```

## 5-Minute Setup

### 1. Add Trait to Your Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // ← Add this

class MyModel extends Model
{
    use BelongsToTenant; // ← Use the trait

    protected $fillable = [
        'organisation_id', // ← Add this to fillable
        'name',
        'description',
    ];
}
```

### 2. Create Migration

```bash
php artisan make:migration add_organisation_id_to_my_models_table --table=my_models
```

Then update the migration:

```php
public function up()
{
    Schema::table('my_models', function (Blueprint $table) {
        if (!Schema::hasColumn('my_models', 'organisation_id')) {
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();
        }
    });
}

public function down()
{
    Schema::table('my_models', function (Blueprint $table) {
        if (Schema::hasColumn('my_models', 'organisation_id')) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });
}
```

### 3. Run Migration

```bash
php artisan migrate
```

### 4. Done! ✅

Your model now:
- Automatically filters queries by tenant
- Auto-fills `organisation_id` when creating records
- Prevents cross-tenant access

## Using It

### Create Records (Auto-Filled)

```php
// Set tenant context
session(['current_organisation_id' => 1]);

// Create - organisation_id is auto-filled!
$election = Election::create([
    'name' => 'Presidential Election',
    // organisation_id is automatically set to 1
]);

echo $election->organisation_id; // Output: 1
```

### Query Records (Auto-Scoped)

```php
// Set tenant context
session(['current_organisation_id' => 1]);

// Gets only Org 1's elections
$elections = Election::all();

// Gets only Org 1's elections
$count = Election::count();

// Gets only Org 1's election (or null if not found)
$election = Election::find($id);
```

### Bypass Scoping (Admin Only)

```php
// When you need to see ALL records (admin operations)
$allElections = Election::withoutGlobalScopes()->get();

// Or use the helper
$allElections = Election::ignoreTenant()->get();
```

### Query Specific Tenant

```php
// Get records for a specific organization (not current context)
$org1Elections = Election::forOrganisation(1)->get();

// Get default platform records (organisation_id = null)
$platformElections = Election::forDefaultPlatform()->get();
```

## Testing

### In Your Tests

```php
use Tests\TestCase;

class MyTest extends TestCase
{
    public function test_tenant_isolation()
    {
        // Create users
        $org1User = User::factory()->create(['organisation_id' => 1]);
        $org2User = User::factory()->create(['organisation_id' => 2]);

        // Create data as org1
        $this->actingAs($org1User);
        session(['current_organisation_id' => 1]);
        Election::create(['name' => 'Org1 Election']);

        // Create data as org2
        $this->actingAs($org2User);
        session(['current_organisation_id' => 2]);
        Election::create(['name' => 'Org2 Election']);

        // Query as org1
        $this->actingAs($org1User);
        session(['current_organisation_id' => 1]);
        $elections = Election::all();

        // Should only see org1's election
        $this->assertCount(1, $elections);
    }
}
```

### Run Tests

```bash
php artisan test tests/Feature/TenantIsolationTest.php
```

Expected output:
```
Tests:  33 passed
```

## Common Patterns

### Pattern 1: Query as Specific Tenant

```php
// Temporarily switch context
session(['current_organisation_id' => 2]);
$org2Elections = Election::all();

// Switch back
session(['current_organisation_id' => 1]);
```

### Pattern 2: Batch Operations

```php
// Process all elections for current tenant
$elections = Election::all(); // Automatically filtered!

foreach ($elections as $election) {
    $election->update(['status' => 'completed']);
}
```

### Pattern 3: Check Tenant Ownership

```php
$election = Election::find($id);

// Check if election belongs to current tenant
if ($election->belongsToCurrentOrganisation()) {
    // Safe to show/edit
} else {
    // Access denied
}

// Or check specific tenant
if ($election->belongsToOrganisation(1)) {
    // Belongs to org 1
}
```

## Troubleshooting

### Issue: Getting zero results from queries

**Cause**: Session context is not set

**Solution**:
```php
// Make sure session is set
session(['current_organisation_id' => 1]);

// Then query
$elections = Election::all(); // Will now return results
```

### Issue: Can see other organization's data

**Cause**: Session context is null or wrong

**Check**:
```php
// In tinker
php artisan tinker
> session('current_organisation_id')
```

### Issue: Migration fails with foreign key error

**Solution**: Disable FK checks before migration:
```php
// In migration
public function up()
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    // ... your migration code
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
}
```

## Next Steps

1. ✅ Add trait to your model
2. ✅ Create and run migration
3. ✅ Test with the helper methods
4. 📖 Read [ADDING_TENANCY.md](./ADDING_TENANCY.md) for detailed guide
5. 📚 Read [API_REFERENCE.md](./API_REFERENCE.md) for all available methods

## Key Takeaways

| Concept | How It Works |
|---------|-------------|
| **Automatic Scoping** | Use BelongsToTenant trait → all queries automatically filtered |
| **Auto-Fill** | Create records without setting org_id → trait auto-fills it |
| **Session Context** | `session(['current_organisation_id' => X])` sets tenant |
| **Bypass** | Use `withoutGlobalScopes()` for admin operations |
| **Testing** | Set session + set context before querying |

## Questions?

- Check the other docs in this folder
- Search [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
- Review `tests/Feature/TenantIsolationTest.php` for examples
- Check `app/Traits/BelongsToTenant.php` for implementation details

---

**You're ready to go!** 🚀

Next: [ADDING_TENANCY.md](./ADDING_TENANCY.md) for detailed implementation guide.
