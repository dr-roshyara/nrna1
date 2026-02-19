# Multi-Tenancy Best Practices

Essential best practices for developing in a multi-tenant system.

## Golden Rules

### 1. Session Must Always Be Set First

```php
// ❌ WRONG - No session context
$election = Election::create(['name' => 'Test']);

// ✅ CORRECT - Session set before operation
session(['current_organisation_id' => 1]);
$election = Election::create(['name' => 'Test']);

// ✅ CORRECT IN CONTROLLERS - Middleware sets it
public function store(Request $request)
{
    // TenantContext middleware already set session
    $election = Election::create($request->validated());
}
```

**Why**: Without session context, global scope filters by NULL and queries return empty results.

---

### 2. Always Include organisation_id in $fillable

```php
// ❌ WRONG - Missing organisation_id
protected $fillable = ['name', 'description'];

// ✅ CORRECT - Include organisation_id
protected $fillable = ['organisation_id', 'name', 'description'];
```

**Why**: If organisation_id is not fillable, the auto-fill observer won't populate it on create().

---

### 3. Test Tenant Isolation Explicitly

```php
// ❌ WRONG - No tenant isolation test
public function test_create_election()
{
    $election = Election::create(['name' => 'Test']);
    $this->assertNotNull($election);
}

// ✅ CORRECT - Test with multiple tenants
public function test_election_isolation()
{
    // Create as org 1
    session(['current_organisation_id' => 1]);
    Election::create(['name' => 'Org1 Election']);

    // Create as org 2
    session(['current_organisation_id' => 2]);
    Election::create(['name' => 'Org2 Election']);

    // Query as org 1
    session(['current_organisation_id' => 1]);
    $elections = Election::all();

    // Should only see org 1's election
    $this->assertCount(1, $elections);
}
```

**Why**: This verifies data isolation actually works across tenants.

---

### 4. Never Query Across Tenants

```php
// ❌ WRONG - Queries multiple tenants
$allElections = Election::withoutGlobalScopes()->get();
$combined = collect();
foreach ($allElections as $election) {
    $combined->push($election);
}

// ✅ CORRECT - Query within single tenant
session(['current_organisation_id' => 1]);
$elections = Election::all();

// ✅ CORRECT FOR ADMIN - Use forOrganisation()
$org2Elections = Election::forOrganisation(2)->get();
```

**Why**: Cross-tenant queries can lead to data leaks or performance issues.

---

### 5. Use Helper Methods for Authorization

```php
// ❌ WRONG - Manual comparison
if ($election->organisation_id === auth()->user()->organisation_id) {
    // Allow
}

// ✅ CORRECT - Use provided method
if ($election->belongsToCurrentOrganisation()) {
    // Allow
}
```

**Why**: Helper method is self-documenting and handles null checks.

---

### 6. Log with Tenant Context

```php
// ❌ WRONG - Generic logging
\Log::info('Vote recorded');

// ✅ CORRECT - Use tenant logging
tenant_log('Vote recorded', 'info', [
    'election_id' => $election->id,
    'user_id' => auth()->id(),
]);
```

**Why**: Tenant-scoped logs help with debugging and security audits.

---

### 7. Migrate All Related Models

```php
// ❌ WRONG - Only parent model
class Election extends Model
{
    use BelongsToTenant;
}

// ❌ WRONG - Child model forgotten
class Post extends Model
{
    // BelongsToTenant missing!
}

// ✅ CORRECT - All related models
class Election extends Model
{
    use BelongsToTenant;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

class Post extends Model
{
    use BelongsToTenant;  // ← Must add here too!

    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}
```

**Why**: Child models must also be scoped to prevent orphan records.

---

### 8. Validate Tenant Context Before Operations

```php
// ❌ WRONG - No validation
public function updateElection(Election $election, Request $request)
{
    $election->update($request->validated());
    return $election;
}

// ✅ CORRECT - Validate ownership
public function updateElection(Election $election, Request $request)
{
    if (!$election->belongsToCurrentOrganisation()) {
        abort(403, 'Not authorized');
    }

    $election->update($request->validated());
    return $election;
}
```

**Why**: Prevents unauthorized access even if record somehow loads.

---

### 9. Use Transactions for Multi-Model Operations

```php
// ❌ WRONG - No transaction
$election = Election::create($data);
$post = Post::create(['election_id' => $election->id]);
$candidacy = Candidacy::create(['post_id' => $post->id]);

// ✅ CORRECT - Use transaction
DB::transaction(function () {
    $election = Election::create($data);
    $post = Post::create(['election_id' => $election->id]);
    $candidacy = Candidacy::create(['post_id' => $post->id]);
});
```

**Why**: Ensures consistency if any step fails.

---

### 10. Cache by Tenant

```php
// ❌ WRONG - Global cache (data leaks)
$elections = Cache::remember('elections', 60, function () {
    return Election::all();
});

// ✅ CORRECT - Tenant-scoped cache
$elections = Cache::remember('elections:' . current_organisation_id(), 60, function () {
    return Election::all();
});
```

**Why**: Prevents cached data from one tenant being served to another.

---

## Architecture Patterns

### Pattern 1: Service Layer

```php
// ✅ GOOD - Service encapsulates tenant logic
class ElectionService
{
    public function createElection(array $data): Election
    {
        return DB::transaction(function () use ($data) {
            // Service knows about tenant context
            $election = Election::create($data);

            // Create default posts
            foreach ($data['posts'] as $postData) {
                $election->posts()->create($postData);
            }

            tenant_log('Election created', 'info', [
                'election_id' => $election->id,
            ]);

            return $election;
        });
    }
}

// In Controller
public function store(Request $request)
{
    $election = app(ElectionService::class)->createElection(
        $request->validated()
    );

    return response()->json($election);
}
```

**Benefits**:
- Tenant logic isolated
- Easier to test
- Reusable across controllers
- Consistent error handling

---

### Pattern 2: Repository Pattern

```php
// ✅ GOOD - Repository abstracts data access
interface ElectionRepositoryInterface
{
    public function findForTenant(int $id, int $tenantId): ?Election;
    public function allForTenant(int $tenantId): Collection;
    public function saveForTenant(Election $election, int $tenantId): void;
}

class EloquentElectionRepository implements ElectionRepositoryInterface
{
    public function findForTenant(int $id, int $tenantId): ?Election
    {
        return Election::forOrganisation($tenantId)->find($id);
    }

    public function allForTenant(int $tenantId): Collection
    {
        return Election::forOrganisation($tenantId)->get();
    }

    public function saveForTenant(Election $election, int $tenantId): void
    {
        if (!$election->belongsToOrganisation($tenantId)) {
            throw new \Exception('Tenant mismatch');
        }
        $election->save();
    }
}

// In Service
class ElectionService
{
    public function __construct(
        private ElectionRepositoryInterface $repository
    ) {}

    public function getElection(int $id, int $tenantId): ?Election
    {
        return $this->repository->findForTenant($id, $tenantId);
    }
}
```

**Benefits**:
- Easy to swap implementations
- Centralized tenant validation
- Testable with mocks

---

### Pattern 3: Query Objects

```php
// ✅ GOOD - Query object encapsulates complex queries
class ElectionQuery
{
    private $query;

    public function __construct(private int $tenantId)
    {
        $this->query = Election::forOrganisation($tenantId);
    }

    public function active(): self
    {
        $this->query->where('status', 'active');
        return $this;
    }

    public function withCandidates(): self
    {
        $this->query->with('posts.candidacies');
        return $this;
    }

    public function get(): Collection
    {
        return $this->query->get();
    }
}

// Usage
$elections = (new ElectionQuery($tenantId))
    ->active()
    ->withCandidates()
    ->get();
```

**Benefits**:
- Readable, chainable queries
- Reusable across controllers
- Explicit tenant handling

---

## Error Handling

### 1. Graceful Null Checks

```php
// ❌ WRONG - Doesn't check for null
$election = Election::find($id);
$election->update(['status' => 'active']);  // Crashes if null

// ✅ CORRECT - Check before using
$election = Election::find($id);
if (!$election) {
    abort(404, 'Election not found');
}
$election->update(['status' => 'active']);
```

---

### 2. Explicit Tenant Validation

```php
// ✅ GOOD - Validate tenant before operating
public function updateElection(Request $request, int $id)
{
    $election = Election::find($id);

    if (!$election) {
        abort(404, 'Election not found');
    }

    if (!$election->belongsToCurrentOrganisation()) {
        abort(403, 'You do not have access to this election');
    }

    $election->update($request->validated());

    return response()->json($election);
}
```

---

### 3. Log Errors with Context

```php
// ✅ GOOD - Log includes tenant context
try {
    $election = Election::create($data);
} catch (\Exception $e) {
    tenant_log('Election creation failed', 'error', [
        'error' => $e->getMessage(),
        'data' => $data,
    ]);
    throw $e;
}
```

---

## Performance Tips

### 1. Eager Load Related Data

```php
// ❌ WRONG - N+1 query problem
$elections = Election::all();
foreach ($elections as $election) {
    $count = $election->posts()->count();  // Query per election!
}

// ✅ CORRECT - Eager load
$elections = Election::with('posts')->get();
foreach ($elections as $election) {
    $count = $election->posts->count();  // No extra queries
}
```

---

### 2. Use Indexes

```php
// ✅ Already done - organisation_id is indexed
$table->unsignedBigInteger('organisation_id')
      ->nullable()
      ->after('id')
      ->index();  // ← Ensures fast lookups
```

---

### 3. Paginate Large Result Sets

```php
// ❌ WRONG - Load all records
$elections = Election::all();

// ✅ CORRECT - Paginate
$elections = Election::paginate(15);
```

---

### 4. Cache Strategically

```php
// ✅ GOOD - Cache with tenant key
$elections = Cache::remember(
    'elections:' . current_organisation_id(),
    60,
    fn() => Election::all()
);

// Invalidate on update
public function updateElection(Election $election)
{
    $election->update([...]);

    Cache::forget('elections:' . current_organisation_id());
}
```

---

## Testing Best Practices

### 1. Use Test Helpers

```php
// ✅ GOOD - Helper sets both auth and session
$this->actAsUser($user);

// Instead of manually:
$this->actingAs($user);
session(['current_organisation_id' => $user->organisation_id]);
```

---

### 2. Test Both Paths

```php
// ✅ GOOD - Test allowed AND denied
public function test_can_update_own_election()
{
    $this->actAsUser($org1User);
    $election = Election::create(['name' => 'Test']);

    $response = $this->patch("/elections/{$election->id}", [...]);
    $response->assertStatus(200);
}

public function test_cannot_update_other_tenant_election()
{
    $this->actAsUser($org1User);
    $election = Election::create(['name' => 'Test']);

    $this->actAsUser($org2User);
    $response = $this->patch("/elections/{$election->id}", [...]);
    $response->assertStatus(403);
}
```

---

### 3. Verify Isolation

```php
// ✅ GOOD - Verify data separation
public function test_elections_isolated()
{
    $this->actAsUser($org1User);
    Election::create(['name' => 'Org1']);

    $this->actAsUser($org2User);
    Election::create(['name' => 'Org2']);

    $this->actAsUser($org1User);
    $elections = Election::all();

    $this->assertCount(1, $elections);
    $this->assertEquals('Org1', $elections->first()->name);
}
```

---

## Security Checklist

Before deploying, verify:

- [ ] All models that store tenant data have `BelongsToTenant` trait
- [ ] All models have `organisation_id` in `$fillable`
- [ ] All migrations include `organisation_id` column and index
- [ ] TenantContext middleware is registered
- [ ] All controllers validate tenant context before operations
- [ ] Sensitive operations log with tenant context
- [ ] Tests verify tenant isolation
- [ ] No cross-tenant queries in application code
- [ ] Cache is tenant-scoped
- [ ] Error messages don't leak tenant information

---

## Common Anti-Patterns (Avoid These)

### ❌ Anti-Pattern 1: Global Queries

```php
// DON'T DO THIS
$allElections = Election::withoutGlobalScopes()->get();
// Process across all tenants
```

**Why**: Can lead to data leaks and performance issues.

---

### ❌ Anti-Pattern 2: Relying on Middleware Alone

```php
// DON'T DO THIS
public function show(int $id)
{
    // Hope middleware set session correctly
    return Election::find($id);
}

// DO THIS INSTEAD
public function show(int $id)
{
    $election = Election::find($id);
    if (!$election->belongsToCurrentOrganisation()) {
        abort(403);
    }
    return $election;
}
```

**Why**: Middleware can fail; always validate explicitly.

---

### ❌ Anti-Pattern 3: Caching Without Tenant Key

```php
// DON'T DO THIS
Cache::put('elections', Election::all());

// DO THIS INSTEAD
Cache::put('elections:' . current_organisation_id(), Election::all());
```

**Why**: Data from one tenant can be served to another.

---

### ❌ Anti-Pattern 4: Logging Without Context

```php
// DON'T DO THIS
\Log::info('Action performed');

// DO THIS INSTEAD
tenant_log('Action performed', 'info', [
    'user_id' => auth()->id(),
    'election_id' => $election->id,
]);
```

**Why**: Without tenant context, logs are hard to audit and debug.

---

## Summary Checklist

Before merging code:

- [ ] ✅ Session is set before queries
- [ ] ✅ All models use BelongsToTenant trait
- [ ] ✅ organisation_id in $fillable
- [ ] ✅ Tenant context validated in controllers
- [ ] ✅ Tests verify isolation
- [ ] ✅ No cross-tenant queries
- [ ] ✅ Cache is tenant-scoped
- [ ] ✅ Logging includes tenant context
- [ ] ✅ Errors handled gracefully
- [ ] ✅ Performance acceptable (indexes used)

---

**Next**: See [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) for common issues and solutions.
