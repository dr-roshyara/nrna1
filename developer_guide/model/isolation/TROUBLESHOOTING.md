# 🔧 Troubleshooting & FAQ

## Common Issues

---

## Issue 1: Models from Other Organisations Showing Up

### Symptoms
```php
session(['current_organisation_id' => $orgA->id]);
$elections = Election::all();

// Result: Getting elections from OTHER organisations too!
```

### Diagnosis

**Check 1: Does the model have BelongsToTenant trait?**
```php
class Election extends Model
{
    // ❌ WRONG - trait missing
    use HasFactory, HasUuids, SoftDeletes;

    // ✅ CORRECT
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;
}
```

**Check 2: Is the trait imported?**
```php
// ❌ Missing import
use App\Models\Election;

// ✅ Has import
use App\Traits\BelongsToTenant;
```

**Check 3: Is organisation_id in fillable array?**
```php
protected $fillable = [
    // ❌ Missing
    'name',
    'status',

    // ✅ Must include
    'organisation_id',
    'name',
    'status',
];
```

### Solution

Add the trait properly:
```php
use App\Traits\BelongsToTenant;

class Election extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'organisation_id',  // ← Add this
        'name',
        'status',
    ];
}
```

---

## Issue 2: "No Organisations in Database" or Empty Results

### Symptoms
```php
$elections = Election::all();  // Returns empty even after creating records
```

### Diagnosis

**Check: Is session context set?**
```php
// ❌ Missing session context
$elections = Election::all();  // Returns empty!

// ✅ With context
session(['current_organisation_id' => $org->id]);
$elections = Election::all();  // Returns records
```

**Why?** The BelongsToTenant trait filters by session context. Without session set, it defaults to a context that doesn't match any records.

### Solution

Always set session before querying:
```php
// In controllers:
public function index(Request $request)
{
    // EnsureOrganisationMember middleware sets this automatically
    session(['current_organisation_id' => $request->route('organisation')->id]);

    return Election::all();  // Now scoped correctly
}

// In tests:
public function test_elections()
{
    session(['current_organisation_id' => $this->org->id]);

    $elections = Election::all();  // Returns records
}

// In console commands:
public function handle()
{
    // Manually set context for console
    session(['current_organisation_id' => Organisation::firstWhere('slug', 'platform')->id]);

    $elections = Election::all();
}
```

---

## Issue 3: Find Returns Null for Valid Record

### Symptoms
```php
$election = Election::factory()->create(['organisation_id' => $org->id]);
session(['current_organisation_id' => $org->id]);

$found = Election::find($election->id);
// Result: null (but should be the election!)
```

### Diagnosis

**Likely cause:** The record belongs to a different organisation than the session context.

```php
// Debug: What org does the record belong to?
$election = Election::factory()->create(['organisation_id' => $orgA->id]);
echo $election->organisation_id;  // orgA-uuid

// Debug: What's the session context?
session(['current_organisation_id' => $orgB->id]);
echo session('current_organisation_id');  // orgB-uuid

// They don't match! So find() returns null
```

### Solution

Verify organisation matches:
```php
public function test_find_with_correct_org()
{
    $org = Organisation::factory()->create();

    // ✅ Correct: Create record in same org as session
    session(['current_organisation_id' => $org->id]);
    $election = Election::factory()->create(['organisation_id' => $org->id]);

    // Now find works
    $found = Election::find($election->id);
    $this->assertNotNull($found);
}
```

---

## Issue 4: N+1 Query Warnings

### Symptoms
```
Laravel Debugbar shows 50+ queries for a simple page load
Most are: SELECT * FROM organisations WHERE slug = 'platform'
```

### Diagnosis

This is the **platform org lookup query being run repeatedly**.

The BelongsToTenant trait caches it, but if you see many, it might indicate:
1. Queries running in a loop
2. Cache not working (session context repeatedly becoming null)

### Solution

The trait already includes a static cache. If you still see many queries:

```php
// Debug: Check query log
DB::enableQueryLog();

Election::count();
Post::count();
Vote::count();

$queries = DB::getQueryLog();

// Should see only ONE query for "SELECT * FROM organisations WHERE slug = 'platform'"
// All other models use the cached value
```

If you're still seeing N+1:
1. Verify BelongsToTenant includes the cache (it should)
2. Check that session context isn't constantly being cleared
3. Use eager loading for relationships

---

## Issue 5: Database Constraint Violation

### Symptoms
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails
```

### Diagnosis

You're trying to create a record with an invalid `organisation_id`.

```php
// ❌ Invalid org_id
Election::create([
    'organisation_id' => 'invalid-uuid',  // Doesn't exist
    'name' => 'Test',
]);

// ✅ Valid org_id
Election::create([
    'organisation_id' => $org->id,  // Exists in organisations table
    'name' => 'Test',
]);
```

### Solution

Verify the organisation exists:
```php
public function storeElection(Request $request)
{
    $org = Organisation::findOrFail($request->input('organisation_id'));

    Election::create([
        'organisation_id' => $org->id,
        'name' => $request->input('name'),
    ]);
}
```

Or let the trait auto-fill:
```php
// Let BelongsToTenant auto-fill from session
Election::create(['name' => 'Test']);
// organisation_id automatically set to session value
```

---

## Issue 6: Tests Pass Individually But Fail Together

### Symptoms
```bash
# Works
php artisan test tests/Feature/ElectionTest.php --filter=test_one

# Fails
php artisan test tests/Feature/ElectionTest.php
```

### Diagnosis

**Likely cause:** Shared database state between tests. The `RefreshDatabase` trait should prevent this, but:

1. Tests running in different order
2. Session not being reset between tests
3. Cache not being cleared

### Solution

```php
class ElectionTest extends TestCase
{
    use RefreshDatabase;  // ← Must have this

    protected function setUp(): void
    {
        parent::setUp();

        // Clear session before each test
        session()->flush();

        // Clear any caches
        Cache::flush();
    }

    protected function tearDown(): void
    {
        session()->flush();
        parent::tearDown();
    }
}
```

---

## Issue 7: withoutGlobalScopes() Returns Nothing

### Symptoms
```php
$all = Election::withoutGlobalScopes()->get();
// Result: empty, expected: all elections
```

### Diagnosis

Two possibilities:

1. **No records in database** - Nothing to return
2. **Other scopes applied** - Something else is filtering

### Solution

Debug step by step:
```php
// Step 1: Check if records exist
$count = DB::table('elections')->count();
echo $count;  // Should be > 0

// Step 2: Check if bypassScope works
$all = Election::withoutGlobalScopes()->get();
echo count($all);  // Should match step 1

// Step 3: Check if normal scoping works
session(['current_organisation_id' => $org->id]);
$scoped = Election::all();
echo count($scoped);  // Should be <= step 1
```

---

## Issue 8: Relationship Not Scoped Correctly

### Symptoms
```php
$election->posts()->get();  // Returns posts from OTHER organisations!
```

### Diagnosis

The relationship definition is missing `withoutGlobalScopes()`:

```php
// ❌ WRONG - causes double-filtering
public function posts()
{
    return $this->hasMany(Post::class);
    // Post model has BelongsToTenant, so both scopes apply
    // This can cause unexpected filtering
}

// ✅ CORRECT
public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();
    // Let the global scope handle filtering
}
```

### Solution

Add `withoutGlobalScopes()` to related model relationships:
```php
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

public function election()
{
    return $this->belongsTo(Election::class)
                ->withoutGlobalScopes();
}
```

---

## Issue 9: Controller Can't Find Record

### Symptoms
```php
// Route: /organisations/org-a/elections/123

public function show(Request $request, Election $election)
{
    // $election is null, but record exists!
}
```

### Diagnosis

The implicit route model binding is scoped by the middleware. If `organisation_id` doesn't match current context, Laravel returns null.

### Solution

```php
// Option 1: Use explicit binding
public function show(Request $request, $id)
{
    $election = Election::findOrFail($id);  // Will auto-scope
    return view('elections.show', compact('election'));
}

// Option 2: The middleware should have set context already
public function show(Request $request, Election $election)
{
    // Verify middleware ran and set context:
    dd(session('current_organisation_id'));

    // If null, middleware failed
}
```

---

## Issue 10: Can't Create Record with Explicit Org ID

### Symptoms
```php
// Want to create in org B, but session has org A
session(['current_organisation_id' => $orgA->id]);

Election::create([
    'organisation_id' => $orgB->id,
    'name' => 'Test',
]);

// Result: organisation_id set to $orgA->id (session value), not $orgB->id!
```

### Diagnosis

The `BelongsToTenant` trait auto-fills `organisation_id` from session, overriding any explicit value.

This is by design for security - prevents accidental cross-organisation record creation.

### Solution

If you need to create in a different org, temporarily change session:
```php
// Save current context
$original = session('current_organisation_id');

// Switch to org B
session(['current_organisation_id' => $orgB->id]);

// Create in org B
Election::create(['name' => 'Test']);  // Uses org B

// Restore original context
session(['current_organisation_id' => $original]);
```

Or use `withoutGlobalScopes()` with manual setting:
```php
$record = new Election();
$record->organisation_id = $orgB->id;
$record->name = 'Test';
$record->saveQuietly();  // Bypass the creating observer
```

---

## FAQ (Frequently Asked Questions)

### Q: Do I need to manually add WHERE organisation_id?

**A:** No! The `BelongsToTenant` trait adds it automatically via global scope.

```php
// ❌ Don't do this
Election::where('organisation_id', session('current_organisation_id'))->get();

// ✅ Do this
Election::all();
```

### Q: What if session context isn't set?

**A:** Queries return empty. The middleware should set context, but in console commands or background jobs, you need to set it manually.

```php
session(['current_organisation_id' => $org->id]);
```

### Q: Can users see other organisations' data?

**A:** No. The middleware prevents access to other organisations, and the global scope prevents data leaks if somehow accessed.

### Q: Does this work with soft deletes?

**A:** Yes. Soft deletes and organisation scoping work together fine.

### Q: Do relationships need the trait too?

**A:** The related model needs the trait, but relationships should use `withoutGlobalScopes()` to avoid double-filtering.

### Q: Can I query across organisations?

**A:** Yes, but only for admin operations:

```php
$all = Election::withoutGlobalScopes()->get();
```

### Q: Does this affect performance?

**A:** The global scope is typically a single WHERE clause - minimal overhead. The static cache prevents N+1 queries for platform org lookup.

### Q: How do I test this?

**A:** Set session context and create data in multiple organisations:

```php
session(['current_organisation_id' => $orgA->id]);
$itemA = Model::factory()->create(['organisation_id' => $orgA->id]);
$itemB = Model::factory()->create(['organisation_id' => $orgB->id]);

$items = Model::all();
$this->assertCount(1, $items);  // Only org A
```

### Q: What if I find a bug in the isolation?

**A:** Check:
1. Trait is added to model
2. `organisation_id` is in fillable
3. Session context is set
4. Database migration has the column

If all OK, file an issue with the specific scenario.

---

## Still Stuck?

1. **Check the main guide:** `developer_guide/model/isolation/README.md`
2. **Read test examples:** `tests/Feature/OrganisationIsolationTest.php`
3. **Review the architecture:** `architecture/model/isolate_organisations/`
4. **Enable query logging:**
   ```php
   DB::enableQueryLog();
   // Your code
   dd(DB::getQueryLog());
   ```

---

## Contact / Debugging Help

When reporting an issue, include:
1. The exact model class
2. The query or code causing the issue
3. Expected vs actual results
4. Whether other models have the same issue
5. Test case if possible
