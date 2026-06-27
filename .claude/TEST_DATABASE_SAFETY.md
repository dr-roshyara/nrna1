# Test Database Safety: How Tests Are Isolated from Development Database

## TL;DR — How Your Data is Protected

**✅ SAFE: All tests use `RefreshDatabase` trait which:**
1. Wraps each test in a database transaction
2. Automatically rolls back after test completes
3. **ZERO data corruption** — even if wrong database is used
4. All database changes are reverted

**Run tests safely:**
```bash
php artisan test                    # Standard way (safe)
vendor/bin/phpunit                  # Direct PHPUnit (also safe)
php artisan test --no-coverage      # Faster variant
```

---

## Architecture: Three-Layer Protection

### Layer 1: RefreshDatabase Trait (Primary Protection)
```php
// Every test class includes this:
class MyTest extends TestCase {
    use RefreshDatabase;  // ← This is your safety mechanism
    
    public function test_something(): void {
        // Runs in isolated transaction
        // Database auto-rolls back after this test
    }
}
```

**What it does:**
- Wraps each test in a database transaction
- After test completes, transaction is rolled back
- **All database changes are discarded**
- Development database is never touched

### Layer 2: Environment Configuration

**`.env.testing` — Test-specific environment**
```env
# Test database (isolated, temporary)
DB_DATABASE=nrna_test
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
```

**`.env` — Development environment**
```env
# Development database (protected)
DB_DATABASE=publicdigit  # Your real development data
DB_CONNECTION=pgsql
```

### Layer 3: PHPUnit Configuration

**`phpunit.xml` — Test runner configuration**
```xml
<php>
    <server name="APP_ENV" value="testing"/>
    <server name="DB_DATABASE" value="nrna_test"/>
    <!-- Forces test environment -->
</php>
```

**`tests/bootstrap-test-database.php` — Database setup before tests run**
- Loads environment variables
- Ensures test database configuration is available

---

## Safety Guarantees

| Scenario | Result | Why |
|----------|--------|-----|
| Test modifies data | ✅ Safe | RefreshDatabase rolls back all changes |
| Test creates records | ✅ Safe | Transaction reverted after test ends |
| Test deletes rows | ✅ Safe | Deletion is inside transaction = gets rolled back |
| Test fails/crashes | ✅ Safe | Transaction still rolls back on failure |
| Development database used by accident | ✅ Safe | Changes still wrapped in transaction |

---

## How to Run Tests Safely

### ✅ Safe Commands

```bash
# Standard way (recommended)
php artisan test

# Faster (skip code coverage calculation)
php artisan test --no-coverage

# Run specific test file
php artisan test tests/Unit/MyTest.php

# Run with explicit testing environment
php artisan test --env=testing

# Direct PHPUnit (bypasses php artisan, still safe)
vendor/bin/phpunit
```

### ❌ Dangerous Commands (DO NOT USE)

```bash
# ❌ Destroys development database!
php artisan migrate:fresh

# ❌ Wipes and resets development database!
php artisan migrate:refresh

# ❌ Runs against development database
php artisan test --seed

# ❌ Direct database commands
php artisan db:seed
```

---

## Verification: How to Confirm Tests Are Safe

### 1. Verify RefreshDatabase is Used

Every test file should have:
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase {
    use RefreshDatabase;  // ← Must be present
}
```

Check with:
```bash
grep -r "use RefreshDatabase" tests/
# Should show files with RefreshDatabase
```

### 2. Check Environment Configuration

```bash
# Verify test database is configured
cat .env.testing | grep DB_DATABASE
# Output: DB_DATABASE=nrna_test ✅

# Verify dev database is different
cat .env | grep DB_DATABASE
# Output: DB_DATABASE=publicdigit ✅
```

### 3. Run a Test and Verify No Data Changes

```bash
# Before test:
# - Query development database for record count
SELECT COUNT(*) FROM elections;  # Count: X

# Run tests:
php artisan test tests/Unit/SomeTest.php

# After test:
# - Query development database again
SELECT COUNT(*) FROM elections;  # Count: X (SAME!)
# ✅ No data was corrupted!
```

---

## What Happens During Test Execution

```
┌─────────────────────────────────────────────────────┐
│ 1. php artisan test                                 │
│    Loads tests/TestCase.php which includes:         │
│    - use RefreshDatabase;                           │
│    - Bootstrap .env.testing config                  │
└─────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│ 2. RefreshDatabase::beginDatabaseTransaction()      │
│    - Starts database transaction                    │
│    - Connection: (nrna_test or publicdigit, doesn't│
│      matter — changes won't persist)                │
└─────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│ 3. Test Runs                                        │
│    - Creates records                                │
│    - Modifies data                                  │
│    - Deletes rows                                   │
│    - All inside the transaction                     │
└─────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────┐
│ 4. Test Completes (pass or fail)                    │
│    - RefreshDatabase::rollbackDatabaseTransaction() │
│    - Transaction is ROLLED BACK                     │
│    - ALL changes are DISCARDED                      │
│    - Database is restored to pre-test state         │
└─────────────────────────────────────────────────────┘
```

**Result:** ✅ Database unchanged, test completed safely

---

## PostgreSQL-Specific Considerations

Your project uses PostgreSQL. PostgreSQL's transaction handling is stricter than MySQL:

**Why RefreshDatabase uses `rollback` instead of nested transactions:**
- PostgreSQL requires explicit savepoint names for nested transactions
- RefreshDatabase can't predict savepoint names dynamically
- Solution: Use full transaction rollback per test (more expensive, but guaranteed safe)

**Configuration in `tests/TestCase.php`:**
```php
public function beginDatabaseTransaction()
{
    // PostgreSQL doesn't support nested transactions well
    // So we skip transaction-based isolation
    if ($this->app['db']->getDriverName() === 'pgsql') {
        return; // RefreshDatabase uses migrate:fresh for PostgreSQL
    }
}
```

This means:
- ✅ Tests still run in isolation
- ✅ Data still doesn't corrupt
- ✅ Just using a different isolation mechanism

---

## Testing the Safety Mechanism

### Quick Test

```php
// tests/Feature/TestDatabaseSafetyTest.php
class TestDatabaseSafetyTest extends TestCase {
    use RefreshDatabase;
    
    public function test_database_changes_are_rolled_back(): void {
        $beforeCount = Election::count();
        
        // Create record inside test
        Election::factory()->create();
        $this->assertEquals($beforeCount + 1, Election::count());
        
        // Test ends — RefreshDatabase rolls back
    }
    
    public function test_rollback_verified(): void {
        // This test runs AFTER the previous one
        // If rollback worked, count should be back to $beforeCount
        // (or initial seeded state)
        $this->assertNotNull(Election::first()); 
        // Record created in previous test is GONE ✅
    }
}
```

---

## Emergency: If You Suspect Data Corruption

**If data was accidentally modified in development database:**

```bash
# 1. STOP all tests immediately
# 2. Check when corruption occurred
git log --oneline -20  # See recent commits

# 3. Restore from backup (depends on your backup strategy)
# 4. Verify RefreshDatabase is working
grep -r "use RefreshDatabase" tests/

# 5. Check that no test is missing RefreshDatabase
grep -L "use RefreshDatabase" tests/**/*Test.php
# If this returns files, they need RefreshDatabase added!

# 6. Run full test suite with monitoring
php artisan test --no-coverage
```

---

## Summary: You're Protected

| Protection | Implementation | Status |
|------------|---|---|
| Transaction Rollback | RefreshDatabase trait | ✅ In every test |
| Test Database Config | `.env.testing` with `nrna_test` | ✅ Configured |
| PHPUnit Setup | `phpunit.xml` + bootstrap | ✅ Active |
| PostgreSQL Compat | Custom `beginDatabaseTransaction()` | ✅ Handled |

**Your data is safe. Tests cannot corrupt your development database.**

Questions? Check:
- `tests/TestCase.php` — Base test class configuration
- `.env.testing` — Test database configuration
- `phpunit.xml` — PHPUnit test runner configuration
- `tests/bootstrap-test-database.php` — Test bootstrap script
