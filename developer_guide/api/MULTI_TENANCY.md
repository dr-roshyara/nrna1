# Multi-Tenancy Implementation Guide

Complete documentation on how multi-tenancy is implemented in this API.

---

## Overview

The system uses **header-based, request-scoped tenant isolation** where:
- Each organization (tenant) has a unique ID
- Tenant ID is passed via HTTP header on every API request
- Tenant context is extracted and validated by middleware
- All queries automatically scoped to current tenant

---

## Architecture

```
API Request
    ↓
IdentifyTenantFromHeader Middleware
    ├─ Extracts X-Organisation-ID from headers
    ├─ Validates user has access to this organisation
    └─ Sets TenantContext::set($organisationId)
    ↓
Controller
    ├─ Gets tenant ID from TenantContext::get()
    ├─ Passes to application layer
    └─ Application layer passes to domain
    ↓
Domain/Repository
    └─ All queries include: WHERE organisation_id = $tenantId
    ↓
Response
    └─ Tenant-scoped data only
```

---

## Key Components

### 1. TenantContext Service

**File:** `app/Services/TenantContext.php`

```php
final class TenantContext
{
    private static ?string $tenantId = null;

    public static function set(?string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    public static function get(): ?string
    {
        return self::$tenantId;
    }

    public static function has(): bool
    {
        return self::$tenantId !== null;
    }

    public static function require(): string
    {
        return self::$tenantId
            ?? throw new \RuntimeException('Tenant context not set');
    }

    public static function clear(): void
    {
        self::$tenantId = null;
    }
}
```

**Why static?**
- Per-request singleton (request-scoped)
- Available throughout request lifecycle
- Easy to inject or access anywhere
- No circular dependencies

**Why not session?**
- Session is user-scoped, not request-scoped
- Can't switch tenants in same session
- Not stateless (violates REST)

---

### 2. IdentifyTenantFromHeader Middleware

**File:** `app/Http/Middleware/IdentifyTenantFromHeader.php`

```php
final class IdentifyTenantFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Extract header (case-insensitive with fallbacks)
        $organisationId = $request->header('X-Organisation-ID')
                       ?? $request->header('x-organisation-id')
                       ?? $request->header('X-Tenant-Id')
                       ?? $request->header('x-tenant-id');

        if (!$organisationId) {
            return response()->json([
                'error' => 'X-Organisation-ID header is required for API requests.',
                'code' => 'MISSING_TENANT_CONTEXT',
            ], 400);
        }

        // 2. Verify organisation exists
        $organisation = Organisation::find($organisationId);
        if (!$organisation) {
            return response()->json([
                'error' => 'Organisation context not found.',
                'code' => 'INVALID_ORGANISATION',
            ], 404);
        }

        // 3. Verify user has access to this organisation
        if (!$request->user()->organisations()->where('organisation_id', $organisationId)->exists()) {
            return response()->json([
                'error' => 'You do not have access to this organisation.',
                'code' => 'UNAUTHORIZED_TENANT_ACCESS',
            ], 403);
        }

        // 4. Set tenant context globally
        TenantContext::set($organisationId);

        // 5. Also store in session for compatibility
        session(['current_organisation_id' => $organisationId]);

        return $next($request);
    }
}
```

**Execution Order:**
```php
// bootstrap/app.php
->middleware(['web', 'json.api', 'tenant.api', 'auth', 'verified'])

// 1. web: Session setup
// 2. json.api: Force JSON responses
// 3. tenant.api: Extract tenant from header ← WE ARE HERE
// 4. auth: Verify user is authenticated
// 5. verified: Verify email is verified
```

**Important:** `tenant.api` must run BEFORE `auth` so if user needs tenant context in their checks, it's available.

---

### 3. BelongsToTenant Global Scope

**File:** `app/Traits/BelongsToTenant.php`

Automatically scopes queries to current tenant:

```php
trait BelongsToTenant
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }
}

// Usage in models
class Committee extends Model
{
    use BelongsToTenant;
    // Automatically scoped to current tenant
}
```

**How it works:**
```php
// This:
$committee = Committee::find($id);

// Becomes:
SELECT * FROM committees 
WHERE id = $id 
  AND organisation_id = <current tenant>  ← Automatic!
```

**Important:** Must use `withoutGlobalScopes()` to bypass:
```php
// When you need cross-tenant data (rare)
$allCommittees = Committee::withoutGlobalScopes()
    ->where('organisation_id', $specificTenant)
    ->get();
```

---

## Usage Examples

### In Controllers

```php
final class CommitteeMemberController extends Controller
{
    public function index(string $committeeId): JsonResponse
    {
        // 1. Get tenant from middleware
        $tenantId = TenantContext::get();
        if (!$tenantId) {
            return response()->json(['error' => 'Missing tenant'], 400);
        }

        // 2. Pass to application layer
        $members = $this->queryService->getMembersForCommittee(
            CommitteeId::fromString($committeeId),
            TenantId::fromString($tenantId)
        );

        return response()->json(['members' => $members]);
    }
}
```

### In Application Layer

```php
final class AddCommitteeMemberHandler
{
    public function handle(AddCommitteeMemberCommand $command): void
    {
        // Tenant ID comes from command (set by controller)
        $tenantId = TenantId::fromString($command->tenantId);

        // Load aggregate (automatically scoped to tenant)
        $committee = $this->repository->findById(
            CommitteeId::fromString($command->committeeId),
            $tenantId  // Explicit tenant scoping
        );

        if (!$committee) {
            throw new \DomainException('Committee not found', 404);
        }

        // ...
    }
}
```

### In Repository

```php
final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        // Query automatically scoped to tenant by global scope
        $model = CommitteeModel::where('organisation_id', $tenantId->value())
            ->find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function save(Committee $aggregate, TenantId $tenantId): void
    {
        $model = CommitteeModel::firstOrCreate(
            ['id' => $aggregate->id()->value()],
            ['organisation_id' => $tenantId->value()]  // Always set tenant
        );

        $model->update([...]);
    }
}
```

### In Models

```php
class Committee extends Model
{
    use BelongsToTenant;

    // Queries are automatically tenant-scoped
    public static function forOrganisation(string $orgId)
    {
        return static::withoutGlobalScopes()
            ->where('organisation_id', $orgId)
            ->get();
    }
}

// Usage
$committees = Committee::all();  // Only current tenant's committees
$allCommittees = Committee::withoutGlobalScopes()->get();  // All committees
```

---

## Testing Multi-Tenancy

### Setup Test Tenants

```php
final class CommitteeMemberTest extends TestCase
{
    private string $tenant1Id = 'org-1';
    private string $tenant2Id = 'org-2';

    protected function setUp(): void
    {
        parent::setUp();

        // Create organizations
        Organisation::create(['id' => $this->tenant1Id, 'name' => 'Org 1']);
        Organisation::create(['id' => $this->tenant2Id, 'name' => 'Org 2']);

        // Create users
        $this->user = User::create([...]);
        OrganisationUser::create(['user_id' => $this->user->id, 'organisation_id' => $this->tenant1Id]);
    }
}
```

### Test Tenant Isolation

```php
#[Test]
public function userCannotAccessOtherTenantData(): void
{
    $committee = Committee::create([
        'id' => 'cmte-1',
        'organisation_id' => $this->tenant2Id,  // Different tenant!
    ]);

    // Try to access as tenant1 user
    $response = $this->actingAs($this->user)
        ->postJson('/api/v1/governance/committees', [...], [
            'X-Organisation-ID' => $this->tenant2Id,  // Wrong tenant
        ]);

    $response->assertStatus(403);  // Forbidden
}
```

### Test Tenant Context

```php
#[Test]
public function tenantContextSetByMiddleware(): void
{
    $this->actingAs($this->user);

    $response = $this->postJson('/api/v1/test-endpoint', [], [
        'X-Organisation-ID' => $this->tenant1Id,
    ]);

    // TenantContext should be set
    $this->assertEquals($this->tenant1Id, TenantContext::get());
}
```

---

## Common Mistakes

### ❌ Mistake #1: Forgetting Tenant Scoping

```php
// ❌ WRONG: No tenant check
$committee = Committee::find($id);
// Might return another tenant's committee!

// ✅ CORRECT: Explicit tenant scoping
$committee = Committee::where('organisation_id', TenantContext::get())
    ->find($id);
```

### ❌ Mistake #2: Forgetting to Pass Tenant to Repository

```php
// ❌ WRONG: Tenant not passed
$committee = $this->repository->findById($committeeId);

// ✅ CORRECT: Tenant passed explicitly
$committee = $this->repository->findById(
    $committeeId,
    TenantId::fromString($tenantId)
);
```

### ❌ Mistake #3: Not Validating Tenant Access

```php
// ❌ WRONG: Middleware doesn't validate
public function handle(Request $request, Closure $next): Response
{
    $tenantId = $request->header('X-Organisation-ID');
    TenantContext::set($tenantId);  // Set without validation!
    return $next($request);
}

// ✅ CORRECT: Validate user has access
public function handle(Request $request, Closure $next): Response
{
    $tenantId = $request->header('X-Organisation-ID');
    
    // Verify user has access
    if (!$request->user()->organisations()->where('organisation_id', $tenantId)->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    TenantContext::set($tenantId);
    return $next($request);
}
```

### ❌ Mistake #4: Using Tenant ID from Request Instead of TenantContext

```php
// ❌ WRONG: Can be manipulated
$tenantId = $request->input('organisation_id');

// ✅ CORRECT: From validated middleware
$tenantId = TenantContext::get();
```

---

## Scaling Considerations

### Current Approach (Request-Scoped)
- ✅ Simple, easy to test
- ✅ Per-request isolation
- ✅ Stateless (REST-friendly)
- ❌ Tenant ID passed on every request

### Future: Session-Based (if needed)
```php
// Store tenant in session
session(['current_organisation_id' => $tenantId]);

// Retrieve from session
$tenantId = session('current_organisation_id');
```

### Future: Domain-Based (if needed)
```php
// Tenant determined by domain
// example.com → tenant-1
// tenant2.example.com → tenant-2

$tenantId = TenantResolver::fromDomain($request->getHost());
```

---

## Debugging Tenant Issues

### Check Current Tenant Context
```bash
php artisan tinker
>>> TenantContext::get()
=> "a1ca231c-59aa-4950-8b23-75b16d5c176a"

>>> TenantContext::has()
=> true
```

### Check User's Organisations
```bash
>>> auth()->user()->organisations()->pluck('id')
=> ["a1ca231c-...", "b2db342d-..."]
```

### Check Model Scoping
```bash
# Without tenant context
>>> TenantContext::clear()
>>> Committee::count()  // Should be 0 if scope works

# With tenant context
>>> TenantContext::set('org-id')
>>> Committee::count()  // Should show org's committees

# Bypass scope
>>> Committee::withoutGlobalScopes()->count()  // All committees
```

### Check Database
```sql
-- Verify organisation_id is set
SELECT organisation_id, COUNT(*) FROM committees GROUP BY organisation_id;

-- Find committees by tenant
SELECT * FROM committees WHERE organisation_id = 'org-id';

-- Verify no NULL organisation_id values (data integrity)
SELECT COUNT(*) FROM committees WHERE organisation_id IS NULL;
```

---

## API Headers Reference

### Required Header
```
X-Organisation-ID: a1ca231c-59aa-4950-8b23-75b16d5c176a
```

### Fallback Headers (if primary fails)
```
x-organisation-id: (lowercase)
X-Tenant-Id: (alternative name)
x-tenant-id: (alternative lowercase)
```

### Example Request
```bash
curl -X POST http://localhost:8000/api/v1/governance/committees/123/members \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "X-Organisation-ID: a1ca231c-59aa-4950-8b23-75b16d5c176a" \
  -H "Content-Type: application/json" \
  -d '{"memberId":"john-id","role":"member"}' \
  -b "PHPSESSID=session-cookie"
```

---

## Security Checklist

- [ ] TenantContext cleared after request (automatic in Laravel)
- [ ] User verified to have access to requested tenant
- [ ] All queries include `organisation_id` filtering
- [ ] No raw queries that bypass scoping
- [ ] Tenant ID validated is UUID format
- [ ] Tests verify cross-tenant isolation
- [ ] Logs include tenant ID for audit
- [ ] Admin endpoints bypass tenant check (if needed, use super admin role)

---

**Next:** Read [DEBUGGING_GUIDE.md](./DEBUGGING_GUIDE.md) for troubleshooting common issues.
