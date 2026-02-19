# Multi-Tenancy Troubleshooting Guide

Solutions for common multi-tenancy issues and errors.

## Common Issues

### Issue 1: Queries Return Empty Results

**Symptoms**:
```
Expected 5 records, got 0
User can't see any data
All queries return empty collections
```

**Root Cause**:
Session context is not set before queries.

**Diagnosis**:
```php
php artisan tinker
> session('current_organisation_id')
=> null  // ← Problem!
```

**Solution**:

```php
// Always set session first
session(['current_organisation_id' => 1]);

// Then query
$elections = Election::all();  // Now returns results
```

**In Controllers**:
```php
public function index()
{
    // TenantContext middleware already set session
    $elections = Election::all();  // Session already set!
    return view('elections.index', compact('elections'));
}
```

**In Tests**:
```php
// Use actAsUser helper
$this->actAsUser($this->org1User);  // Sets both auth + session
$elections = Election::all();  // Now works!
```

**In Tinker**:
```php
php artisan tinker
> session(['current_organisation_id' => 1])
> Election::all()
=> Collection(5 items) [...]
```

---

### Issue 2: Unknown Column 'organisation_id' in 'field list'

**Symptoms**:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'organisation_id'
Migration hasn't been run yet
```

**Root Cause**:
Migration file exists but hasn't been executed.

**Diagnosis**:
```bash
php artisan migrate:status
# Shows pending migrations
```

**Solution**:

```bash
# Run all pending migrations
php artisan migrate

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

**Verification**:
```bash
# Check column exists
php artisan tinker
> \DB::getSchemaBuilder()->hasColumn('elections', 'organisation_id')
=> true
```

---

### Issue 3: Can See Other Organization's Data

**Symptoms**:
```
User from Org A can see Org B's elections
Data is leaking between tenants
Global scope not working
```

**Root Cause**:
Global scope bypassed with `withoutGlobalScopes()` in wrong place.

**Diagnosis**:
```php
// Check where withoutGlobalScopes is used
grep -r "withoutGlobalScopes" app/
```

**Solution**:

```php
// ❌ WRONG - Using in controller
public function index()
{
    $elections = Election::withoutGlobalScopes()->get();  // Data leak!
    return view('elections.index', compact('elections'));
}

// ✅ CORRECT - Use without scopes in admin only
if (auth()->user()->isAdmin()) {
    $elections = Election::withoutGlobalScopes()->get();  // Admin operation
} else {
    $elections = Election::all();  // Normal operation
}
```

**Prevention**:
- Use `ignoreTenant()` and `forOrganisation()` explicitly
- Reserve `withoutGlobalScopes()` for admin-only operations
- Add authorization gates

---

### Issue 4: Foreign Key Constraint Errors

**Symptoms**:
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update
a child row: foreign key constraint fails
```

**Root Cause**:
Related records don't exist or belong to different tenants.

**Diagnosis**:
```php
// Parent doesn't exist
$post = Post::create(['election_id' => 999]);  // election_id=999 doesn't exist

// Tenant mismatch
session(['current_organisation_id' => 1]);
$election = Election::create(['name' => 'E1']);

session(['current_organisation_id' => 2]);
$post = Post::create([  // election_id from org1 but org2 context
    'election_id' => $election->id,
]);
```

**Solution**:

```php
// Ensure parent exists in same tenant context
session(['current_organisation_id' => 1]);

$election = Election::create(['name' => 'E1']);
$post = Post::create([
    'election_id' => $election->id,  // Parent exists in same org
]);

// In tests, disable FK checks if needed
public function setUp(): void
{
    parent::setUp();
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
}

public function tearDown(): void
{
    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    parent::tearDown();
}
```

---

### Issue 5: Tests Failing with "Route Not Found"

**Symptoms**:
```
Route [elections.show] not defined
Tests fail when accessing HTTP endpoints
```

**Root Cause**:
Tests are calling routes that don't exist.

**Diagnosis**:
```php
// Check what routes exist
php artisan route:list | grep election
```

**Solution**:

```php
// ❌ WRONG - Assumes route exists
public function test_get_election()
{
    $response = $this->get('/elections/1');
    $response->assertStatus(200);
}

// ✅ CORRECT - Test model directly
public function test_get_election()
{
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'Test']);

    $this->assertNotNull($election);
    $this->assertEquals(1, $election->organisation_id);
}

// ✅ CORRECT - If route exists, test full flow
public function test_election_api()
{
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'Test']);

    $response = $this->getJson("/api/elections/{$election->id}");
    $response->assertStatus(200);
}
```

---

### Issue 6: Auto-Fill Not Working

**Symptoms**:
```
organisation_id is NULL after create()
Auto-fill didn't populate value
```

**Root Cause**:
- Session not set before create
- organisation_id not in $fillable array
- Explicitly set to different value

**Diagnosis**:
```php
// Check if organisation_id in fillable
$model = new Election();
var_dump($model->getFillable());  // Should include 'organisation_id'

// Check if session set
echo session('current_organisation_id');  // Should have value
```

**Solution**:

```php
// 1. Add to $fillable
protected $fillable = ['organisation_id', 'name', ...];

// 2. Set session first
session(['current_organisation_id' => 1]);

// 3. Create (organisation_id will auto-fill)
$election = Election::create(['name' => 'Test']);
echo $election->organisation_id;  // Outputs: 1
```

**Verification**:
```php
php artisan tinker
> session(['current_organisation_id' => 1])
> $e = Election::create(['name' => 'Test'])
> $e->organisation_id
=> 1
```

---

### Issue 7: Duplicate Migration Files

**Symptoms**:
```
Multiple migration files with same timestamp
Confusing which migration to use
```

**Root Cause**:
Running migration generation command multiple times.

**Diagnosis**:
```bash
ls database/migrations/ | grep organisation_id | wc -l
# If > 1, you have duplicates
```

**Solution**:

```bash
# Delete duplicate files
rm database/migrations/2026_02_19_185532_add_organisation_id_to_elections_table_OLD.php

# Keep only one copy
# Run fresh migration
php artisan migrate:refresh
php artisan migrate
```

---

### Issue 8: Tests Fail After Adding Trait

**Symptoms**:
```
Tests that worked before now fail
New tests fail completely
Global scope preventing test data access
```

**Root Cause**:
- Trait added but tests not updated
- Session context not set in tests
- Test isolation broken

**Solution**:

```php
// Update your test base class
abstract class TenantTestCase extends TestCase
{
    protected function actAsUser(User $user): void
    {
        $this->actingAs($user);
        session(['current_organisation_id' => $user->organisation_id]);
    }
}

// All tests extend TenantTestCase
class ElectionTest extends TenantTestCase
{
    public function test_election_respects_tenant()
    {
        $this->actAsUser($this->org1User);  // Both auth + session
        $election = Election::create(['name' => 'Test']);

        $this->assertEquals($this->org1->id, $election->organisation_id);
    }
}
```

---

### Issue 9: Migration Rollback Fails

**Symptoms**:
```
Migration rollback doesn't work
Down method has errors
```

**Root Cause**:
- Incorrect down() method implementation
- Conditions not checked before dropping
- Foreign key constraints preventing rollback

**Solution**:

```php
// ❌ WRONG - No checking
public function down()
{
    Schema::dropIfExists('elections');  // Might fail
}

// ✅ CORRECT - Check before dropping
public function down()
{
    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            // Drop index first
            $table->dropIndex(['organisation_id']);
            // Then drop column
            $table->dropColumn('organisation_id');
        }
    });
}

// ✅ IF FK ISSUES - Disable checks
public function down()
{
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');

    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });

    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
}
```

---

### Issue 10: Logging Shows No Context

**Symptoms**:
```
Logs don't include tenant information
Hard to debug tenant-specific issues
```

**Root Cause**:
Not using `tenant_log()` helper function.

**Solution**:

```php
// ❌ WRONG - No tenant context
\Log::info('Election created');

// ✅ CORRECT - Use tenant logging
tenant_log('Election created', 'info', [
    'election_id' => $election->id,
    'created_by' => auth()->id(),
]);

// Logs go to:
// storage/logs/tenant_1.log (for org 1)
// storage/logs/tenant_2.log (for org 2)
```

---

## Performance Issues

### Issue: Slow Queries

**Symptoms**:
```
Queries taking > 1 second
High database CPU usage
```

**Diagnosis**:
```php
// Enable query logging
\DB::enableQueryLog();

$elections = Election::all();

// Check if organisation_id column is in WHERE
dump(\DB::getQueryLog());
```

**Solution**:

```php
// 1. Ensure column is indexed (already done)
$table->unsignedBigInteger('organisation_id')
      ->nullable()
      ->after('id')
      ->index();  // ← Index is critical

// 2. Verify index exists
SELECT * FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME='elections' AND COLUMN_NAME='organisation_id';

// 3. Use EXPLAIN to verify query plan
EXPLAIN SELECT * FROM elections WHERE organisation_id=1;
```

---

### Issue: N+1 Queries

**Symptoms**:
```
Many repeated queries for related data
"SELECT * FROM posts WHERE election_id = 1" repeated 100 times
```

**Diagnosis**:
```php
\DB::enableQueryLog();

$elections = Election::all();
foreach ($elections as $election) {
    $posts = $election->posts;  // N+1 problem!
}

echo count(\DB::getQueryLog());  // Should be 2, but is 101
```

**Solution**:

```php
// ❌ WRONG - N+1 queries
$elections = Election::all();
foreach ($elections as $election) {
    $posts = $election->posts;  // Query per election
}

// ✅ CORRECT - Eager load
$elections = Election::with('posts')->get();
foreach ($elections as $election) {
    $posts = $election->posts;  // No extra queries
}
```

---

## Session Issues

### Issue: Session Lost Between Requests

**Symptoms**:
```
First request works, second request fails
Session value becomes null
```

**Root Cause**:
- Session configuration issue
- Not using session correctly
- CORS/authentication issues

**Diagnosis**:
```php
// In controller
dd(session('current_organisation_id'));  // Check if present
dd(auth()->user());  // Check if authenticated
```

**Solution**:

```php
// Ensure TenantContext middleware is registered
// In app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\TenantContext::class,
    ],
];

// Middleware sets session
public function handle($request, $next)
{
    if (auth()->check()) {
        session(['current_organisation_id' => auth()->user()->organisation_id]);
    }
    return $next($request);
}
```

---

## Authorization Issues

### Issue: User Can Access Other Tenant's Data

**Symptoms**:
```
User from org 1 can modify org 2's data
Authorization check not working
```

**Solution**:

```php
// ✅ CORRECT - Validate tenant in controller
public function update(Request $request, Election $election)
{
    // This automatically filters by current tenant (global scope)
    // But if record is accessed by ID, could be from other tenant

    if (!$election->belongsToCurrentOrganisation()) {
        abort(403, 'You do not have access to this election');
    }

    $election->update($request->validated());
    return $election;
}

// Even better - use policy
class ElectionPolicy
{
    public function update(User $user, Election $election)
    {
        return $election->organisation_id === $user->organisation_id;
    }
}

// In controller
$this->authorize('update', $election);  // Checks policy
$election->update($request->validated());
```

---

## Debug Commands

### Check Migration Status
```bash
php artisan migrate:status
```

### View All Indexes
```bash
php artisan tinker
> \DB::select("SHOW INDEX FROM elections")
```

### Test Tenant Isolation
```bash
php artisan tinker
> session(['current_organisation_id' => 1])
> Election::create(['name' => 'Test'])
> session(['current_organisation_id' => 2])
> Election::all()->count()
=> 0  // Correct - only org 1's data
```

### View Log Files
```bash
# View current tenant log
cat storage/logs/tenant_1.log

# Watch logs in real-time
tail -f storage/logs/tenant_1.log
```

---

## Getting Help

1. **Check the logs first**:
   ```bash
   cat storage/logs/tenant_{organisation_id}.log
   ```

2. **Verify session context**:
   ```php
   dd(session('current_organisation_id'));
   ```

3. **Test in tinker**:
   ```bash
   php artisan tinker
   > session(['current_organisation_id' => 1])
   > Model::all()
   ```

4. **Enable query logging**:
   ```php
   \DB::enableQueryLog();
   // ... your code
   dd(\DB::getQueryLog());
   ```

5. **Check migrations status**:
   ```bash
   php artisan migrate:status
   ```

---

## Summary Checklist

When troubleshooting:

- [ ] ✅ Is session set? `session('current_organisation_id')`
- [ ] ✅ Has migration run? `php artisan migrate:status`
- [ ] ✅ Is trait added? `use BelongsToTenant;`
- [ ] ✅ Is organisation_id fillable? Check `$fillable`
- [ ] ✅ Is user authenticated? `auth()->check()`
- [ ] ✅ Check logs: `storage/logs/tenant_X.log`
- [ ] ✅ Run tests: `php artisan test`
- [ ] ✅ Query log enabled: `\DB::enableQueryLog()`

---

**Next**: See [MIGRATIONS.md](./MIGRATIONS.md) for migration patterns and strategies.
