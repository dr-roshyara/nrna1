## 🏛️ Solution: Standardize on `organisation_id` (British English)

You're right - having both spellings is a **recipe for disaster**. Here's a comprehensive plan to standardize:

## 🔍 First, Identify All Variations

```bash
# Find all occurrences of "organisation" in codebase
grep -r "organisation" --include="*.php" --include="*.vue" app/ database/ resources/ routes/ tests/

# Find all occurrences of "organisation" 
grep -r "organisation" --include="*.php" --include="*.vue" app/ database/ resources/ routes/ tests/

# Check database columns
php artisan tinker --execute="
\$tables = DB::select('SHOW TABLES');
foreach (\$tables as \$table) {
    \$tableName = array_values((array)\$table)[0];
    \$columns = DB::select(\"SHOW COLUMNS FROM \$tableName\");
    foreach (\$columns as \$column) {
        if (str_contains(\$column->Field, 'organisation')) {
            echo \"❌ \$tableName.\$column->Field (uses 'z' spelling)\n\";
        }
        if (str_contains(\$column->Field, 'organisation')) {
            echo \"✅ \$tableName.\$column->Field (correct 's' spelling)\n\";
        }
    }
}"
```

## 📝 Migration to Standardize

```php
<?php
// database/migrations/2026_03_01_000003_standardize_organisation_spelling.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Standardize on British English spelling: "organisation"
     * This migration renames all "organisation" columns to "organisation"
     * and drops any duplicate columns
     */
    public function up(): void
    {
        // ============ STEP 1: Handle elections table first (has both columns) ============
        if (Schema::hasTable('elections')) {
            // Check if both columns exist
            $hasOrganisation = Schema::hasColumn('elections', 'organisation_id');
            $hasOrganization = Schema::hasColumn('elections', 'organisation_id');
            
            if ($hasOrganisation && $hasOrganization) {
                // Both exist - need to merge data
                echo "Merging data in elections table...\n";
                
                // Update NULL organisation_id from organisation_id where needed
                DB::statement("
                    UPDATE elections 
                    SET organisation_id = organisation_id 
                    WHERE organisation_id IS NULL AND organisation_id IS NOT NULL
                ");
                
                // Drop the duplicate column
                Schema::table('elections', function (Blueprint $table) {
                    $table->dropColumn('organisation_id');
                });
                
                echo "✅ Dropped duplicate organisation_id from elections\n";
            } elseif ($hasOrganization && !$hasOrganisation) {
                // Only the wrong spelling exists - rename it
                Schema::table('elections', function (Blueprint $table) {
                    $table->renameColumn('organisation_id', 'organisation_id');
                });
                echo "✅ Renamed organisation_id to organisation_id in elections\n";
            }
        }
        
        // ============ STEP 2: Check all other tables ============
        $tables = [
            'users', 'voter_slugs', 'codes', 'demo_codes', 'posts', 
            'candidates', 'candidacies', 'votes', 'results', 'voters',
            'committees', 'members', 'nominations', 'ballots'
        ];
        
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) continue;
            
            $hasOrganisation = Schema::hasColumn($table, 'organisation_id');
            $hasOrganization = Schema::hasColumn($table, 'organisation_id');
            
            if ($hasOrganization && !$hasOrganisation) {
                // Only wrong spelling exists - rename it
                Schema::table($table, function (Blueprint $table) use ($table) {
                    $table->renameColumn('organisation_id', 'organisation_id');
                });
                echo "✅ Renamed organisation_id to organisation_id in {$table}\n";
                
            } elseif ($hasOrganization && $hasOrganisation) {
                // Both exist - need to merge and drop duplicate
                echo "Merging data in {$table} table...\n";
                
                DB::statement("
                    UPDATE {$table} 
                    SET organisation_id = organisation_id 
                    WHERE organisation_id IS NULL AND organisation_id IS NOT NULL
                ");
                
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('organisation_id');
                });
                echo "✅ Dropped duplicate organisation_id from {$table}\n";
            }
        }
        
        // ============ STEP 3: Fix any foreign key constraints ============
        // Drop and recreate foreign keys that reference the wrong column names
        $this->fixForeignKeys();
    }
    
    private function fixForeignKeys(): void
    {
        // Get all foreign keys that might reference organisation_id
        $tables = DB::select("
            SELECT 
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
            AND REFERENCED_COLUMN_NAME = 'organisation_id'
        ");
        
        foreach ($tables as $fk) {
            // Drop the old foreign key
            DB::statement("ALTER TABLE {$fk->TABLE_NAME} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            
            // Recreate with correct column name
            DB::statement("
                ALTER TABLE {$fk->TABLE_NAME}
                ADD CONSTRAINT {$fk->CONSTRAINT_NAME}
                FOREIGN KEY (organisation_id)
                REFERENCES {$fk->REFERENCED_TABLE_NAME}(id)
                ON DELETE CASCADE
            ");
            
            echo "✅ Fixed foreign key {$fk->CONSTRAINT_NAME} in {$fk->TABLE_NAME}\n";
        }
    }
    
    public function down(): void
    {
        // Revert if needed (not recommended)
        // This would rename back to organisation_id
        $tables = [
            'users', 'elections', 'voter_slugs', 'codes', 'demo_codes',
            'posts', 'candidates', 'candidacies', 'votes', 'results'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'organisation_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->renameColumn('organisation_id', 'organisation_id');
                });
            }
        }
    }
};
```

## 🔧 Model Standardization

### Update Base Model or Trait

```php
// app/Traits/HasOrganisation.php
<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

trait HasOrganisation
{
    /**
     * Boot the trait - ensure we're using the correct column name
     */
    protected static function bootHasOrganisation()
    {
        static::creating(function (Model $model) {
            // Always use 'organisation_id' (British spelling)
            if (!isset($model->organisation_id) && !isset($model->organisation_id)) {
                $model->organisation_id = session('current_organisation_id', 0);
            }
            
            // If someone tried to set organisation_id, move it to organisation_id
            if (isset($model->attributes['organisation_id'])) {
                $model->organisation_id = $model->attributes['organisation_id'];
                unset($model->attributes['organisation_id']);
            }
        });
        
        static::updating(function (Model $model) {
            // Clean up any rogue organisation_id attributes
            if (isset($model->attributes['organisation_id'])) {
                $model->organisation_id = $model->attributes['organisation_id'];
                unset($model->attributes['organisation_id']);
            }
        });
    }
    
    /**
     * Get the organisation that owns this model
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
    
    /**
     * Scope a query to only include records from a specific organisation
     */
    public function scopeForOrganisation($query, $orgId)
    {
        return $query->where('organisation_id', $orgId);
    }
    
    /**
     * Scope to include platform records (organisation_id = 0)
     */
    public function scopeIncludePlatform($query)
    {
        return $query->where('organisation_id', 0);
    }
}
```

### Update All Models to Use the Trait

```php
// app/Models/Election.php
class Election extends Model
{
    use HasOrganisation;
    
    // Remove any old organisation methods
    // Remove any old organisation_id references in $fillable
    protected $fillable = [
        'name', 'slug', 'type', 'organisation_id', // ✅ Standardized
        'status', 'start_date', 'end_date'
    ];
}
```

## 🧪 Test the Standardization

```php
// tests/Unit/OrganisationStandardizationTest.php
<?php

namespace Tests\Unit;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationStandardizationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function all_tables_use_correct_column_name()
    {
        $tables = ['users', 'elections', 'voter_slugs', 'codes', 'posts'];
        
        foreach ($tables as $table) {
            $columns = \DB::getSchemaBuilder()->getColumnListing($table);
            
            // Should NOT have 'organisation_id'
            $this->assertNotContains('organisation_id', $columns, 
                "Table {$table} still has 'organisation_id' column");
            
            // Should HAVE 'organisation_id'
            $this->assertContains('organisation_id', $columns,
                "Table {$table} missing 'organisation_id' column");
        }
    }
    
    /** @test */
    public function models_can_access_organisation_relationship()
    {
        $user = User::factory()->create(['organisation_id' => 0]);
        
        // This should not throw an error
        $relation = $user->organisation();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
    }
    
    /** @test */
    public function creating_model_with_organization_id_gets_normalized()
    {
        // Try to create with wrong spelling
        $data = [
            'name' => 'Test',
            'type' => 'demo',
            'organisation_id' => 5, // Wrong spelling
        ];
        
        $election = Election::create($data);
        
        // Should have saved with correct spelling
        $this->assertEquals(5, $election->organisation_id);
        $this->assertFalse(property_exists($election, 'organisation_id'));
    }
}
```

## 📋 Implementation Steps

### Step 1: Backup Database
```bash
mysqldump -u root -p nrna_eu > backup_before_spelling_fix.sql
```

### Step 2: Run the Migration
```bash
php artisan migrate --path=database/migrations/2026_03_01_000003_standardize_organisation_spelling.php
```

### Step 3: Update Models
```bash
# Add HasOrganisation trait to all relevant models
php artisan make:trait HasOrganisation
# Then manually add to each model
```

### Step 4: Run Tests
```bash
php artisan test --filter=OrganisationStandardizationTest
php artisan test # Full test suite
```

### Step 5: Clean Up Codebase
```bash
# Find and replace any remaining "organisation" in code
find app -type f -name "*.php" -exec sed -i 's/organisation_id/organisation_id/g' {} \;
find resources -type f -name "*.php" -exec sed -i 's/organisation_id/organisation_id/g' {} \;
find tests -type f -name "*.php" -exec sed -i 's/organisation_id/organisation_id/g' {} \;

# But be careful - only replace column names, not class names!
```

## ✅ Benefits

| Before | After |
|--------|-------|
| ❌ Inconsistent spelling | ✅ Single standard |
| ❌ Confusing for developers | ✅ Clear, predictable |
| ❌ Potential bugs | ✅ No ambiguity |
| ❌ Hard to maintain | ✅ Easy to refactor |
| ❌ Duplicate columns | ✅ Clean schema |

This approach ensures **100% consistency** across the entire codebase and database! 🎯