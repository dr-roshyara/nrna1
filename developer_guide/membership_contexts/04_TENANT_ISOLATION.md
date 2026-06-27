# Tenant Isolation: Multi-Organization Safety

## 🎯 The Guarantee

**A user logged into Organization A can NEVER access Organization B's data.**

This guarantee is enforced at three layers:

1. **Database Layer** — `tenant_id` column in every table
2. **ORM Layer** — Eloquent GlobalScope automatic filtering
3. **Repository Layer** — Explicit `findForTenant()` method taking `TenantId`

---

## 📊 Architecture: Single Database, Isolated Organizations

```
┌────────────────────────────────────────────────────────┐
│                    POSTGRESQL DATABASE                  │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  organisations table (shared)                          │
│  ├─ id (UUID): 69b96a71-ced4-49af-9653-d449111e6e2d   │
│  ├─ id (UUID): a1a2d2a5-16ca-4af6-b5cb-8c4da625a9b8   │
│                                                         │
│  committees table (multi-tenant)                       │
│  ├─ id: 01KQNEV4GREY80JNE1EE5YDSVB                    │
│  ├─ name: Central Executive Committee                 │
│  ├─ tenant_id: 69b96a71-... ← Organisation A          │
│  │                                                     │
│  ├─ id: 01KQNFB6VRW1D3EEM0919ZZDHR                    │
│  ├─ name: Central Executive Committee                 │
│  ├─ tenant_id: a1a2d2a5-... ← Organisation B          │
│                                                         │
│  members table (multi-tenant)                          │
│  ├─ id: member-001, tenant_id: 69b96a71-...          │
│  ├─ id: member-002, tenant_id: a1a2d2a5-...          │
│                                                         │
│  committee_assignments table (multi-tenant)           │
│  ├─ committee_id: 01KQNEV4GREY80JNE1EE5YDSVB ← Org A  │
│  ├─ committee_id: 01KQNFB6VRW1D3EEM0919ZZDHR ← Org B  │
│                                                         │
└────────────────────────────────────────────────────────┘
```

---

## 🛡️ Three-Layer Isolation Mechanism

### Layer 1: Database Schema (Hard Guarantee)

Every multi-tenant table has a `tenant_id` column:

```sql
-- committees table
CREATE TABLE committees (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    tenant_id UUID NOT NULL,  -- ← Hard isolation point
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES organisations(id),
    UNIQUE KEY unique_code_per_org (code, tenant_id)
);

-- members table
CREATE TABLE members (
    id VARCHAR(26) PRIMARY KEY,
    email VARCHAR(255),
    tenant_id UUID NOT NULL,  -- ← Hard isolation point
    created_at TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES organisations(id)
);

-- committee_assignments table
CREATE TABLE committee_assignments (
    id VARCHAR(26) PRIMARY KEY,
    committee_id VARCHAR(26) NOT NULL,
    member_id VARCHAR(26) NOT NULL,
    tenant_id UUID NOT NULL,  -- ← Hard isolation point
    FOREIGN KEY (committee_id) REFERENCES committees(id),
    FOREIGN KEY (member_id) REFERENCES members(id),
    FOREIGN KEY (tenant_id) REFERENCES organisations(id)
);
```

**Benefit:** Even if code is buggy, database constraints prevent cross-tenant access.

---

### Layer 2: Eloquent GlobalScope (Automatic Filtering)

Every model uses `BelongsToTenant` trait, which adds a GlobalScope:

```php
// app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php

use App\Contexts\Shared\Traits\BelongsToTenant;

final class CommitteeModel extends Model
{
    use BelongsToTenant;  // ← Adds automatic GlobalScope
    
    protected $table = 'committees';
}

// Usage
CommitteeModel::all();  // Automatically filters to current tenant!
// Executes: SELECT * FROM committees WHERE tenant_id = session('current_organisation_id')
```

**How it works:**

```php
// In BelongsToTenant trait
protected static function booted(): void
{
    static::addGlobalScope('tenant', function (Builder $builder) {
        $tenantId = session('current_organisation_id');
        
        if ($tenantId) {
            $builder->where('tenant_id', $tenantId);
        }
    });
}
```

**Benefit:** Automatic protection; impossible to forget scoping.

### Layer 3: Repository Explicit Tenancy (Defense in Depth)

Even though GlobalScope protects, repositories require explicit `TenantId`:

```php
// Domain interface
interface CommitteeRepositoryInterface
{
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee;
    public function saveForTenant(Committee $committee): void;
}

// Implementation
final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        // Double-check: filter by both ID and TenantId
        $model = CommitteeModel::query()
            ->where('id', $id->value())
            ->where('tenant_id', $tenantId->value())  // ← Explicit check
            ->first();
        
        return $model ? $this->reconstitute($model) : null;
    }
}
```

**Benefit:** Defense in depth; catches bugs at application layer.

---

## 🔐 The Tenant Context Resolution

The **TenantId** (which organization you belong to) is resolved via **TenantContext**:

```php
// app/Services/TenantContext.php

final class TenantContext implements TenantContextInterface
{
    /**
     * Resolve current tenant ID with 3-tier strategy:
     * 1. Explicit setContext() call (for CLI, jobs, tests)
     * 2. Authenticated user's organisation_id
     * 3. Session's current_organisation_id (HTTP context)
     * 4. Throw if none available
     */
    public function currentTenantId(): TenantId
    {
        if ($this->currentTenantIdValue) {
            return TenantId::fromString($this->currentTenantIdValue);
        }

        if (auth()->check() && auth()->user()->organisation_id) {
            return TenantId::fromString(auth()->user()->organisation_id);
        }

        if ($sessionTenantId = session('current_organisation_id')) {
            return TenantId::fromString($sessionTenantId);
        }

        throw new RuntimeException('No tenant context available');
    }
    
    /**
     * Set explicit context (for testing, CLI, jobs)
     */
    public function setContext(string $tenantId): void
    {
        $this->currentTenantIdValue = $tenantId;
    }
}
```

### Usage

```php
// In HTTP controller (automatic via middleware)
final class CommitteeController
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext
    ) {}
    
    public function show(string $committeeId)
    {
        // Get current organization ID automatically
        $tenantId = $this->tenantContext->currentTenantId();
        
        // Pass explicitly to use case
        $view = $useCase->execute(
            CommitteeId::fromString($committeeId),
            $tenantId  // ← Forces conscious awareness
        );
    }
}

// In tests
public function test_cannot_access_other_tenants_committee()
{
    $committee = $this->createCommittee(tenantId: 'org-A');
    
    // Switch context to org-B
    $this->tenantContext->setContext('org-B');
    
    $result = $repo->findForTenant($committee->getId(), 'org-B');
    
    $this->assertNull($result);  // org-B cannot see org-A's committees
}
```

---

## ✅ Safety Checklist

### When Building a Feature

- [ ] **Add `tenant_id` to every new table**
```sql
CREATE TABLE new_feature (
    id VARCHAR(26) PRIMARY KEY,
    tenant_id UUID NOT NULL,
    FOREIGN KEY (tenant_id) REFERENCES organisations(id)
);
```

- [ ] **Add `BelongsToTenant` to every model**
```php
final class NewFeatureModel extends Model
{
    use BelongsToTenant;
}
```

- [ ] **Accept `TenantId` in repository methods**
```php
public function findForTenant(FeatureId $id, TenantId $tenantId): ?Feature
{
    return FeatureModel::where('id', $id->value())
        ->where('tenant_id', $tenantId->value())
        ->first();
}
```

- [ ] **Pass `TenantId` from controller to use case**
```php
$useCase->execute(new MyCommand(
    featureId: $id,
    tenantId: $this->tenantContext->currentTenantId(),  // ← Always
));
```

- [ ] **Test with multiple tenant IDs**
```php
public function test_isolation_between_tenants()
{
    $itemOrgA = $this->createItem(tenantId: 'org-a');
    $itemOrgB = $this->createItem(tenantId: 'org-b');
    
    $this->tenantContext->setContext('org-a');
    $found = $repo->findForTenant($itemOrgA->getId(), 'org-a');
    $this->assertNotNull($found);
    
    $found = $repo->findForTenant($itemOrgB->getId(), 'org-b');
    $this->assertNull($found);  // org-b not visible to org-a
}
```

---

## 🚨 Common Mistakes

### ❌ Mistake 1: Forgetting `tenant_id` in Query

```php
// WRONG: No tenant filter
$committee = CommitteeModel::find($id);

// RIGHT: Automatic via GlobalScope, but explicit is safer
$committee = CommitteeModel::where('tenant_id', $tenantId->value())
    ->where('id', $id->value())
    ->first();
```

### ❌ Mistake 2: Using `withoutGlobalScopes()`

```php
// WRONG: Bypasses GlobalScope protection
$allCommittees = CommitteeModel::withoutGlobalScopes()->get();

// RIGHT: Only use in migrations or admin tools, with explicit tenant filter
$committees = CommitteeModel::withoutGlobalScopes()
    ->where('tenant_id', $tenantId->value())
    ->get();
```

### ❌ Mistake 3: Accepting `$tenantId` as String, Not Value Object

```php
// WRONG: Accept string, forget to validate
public function find(string $id, string $tenantId): ?Committee
{
    return $repo->find(CommitteeId::fromString($id), $tenantId);
    //                                                    ↑ Plain string
}

// RIGHT: Use TenantId value object
public function find(CommitteeId $id, TenantId $tenantId): ?Committee
{
    return $repo->findForTenant($id, $tenantId);
}
```

### ❌ Mistake 4: Storing Tenant ID in Cache Key Without Scoping

```php
// WRONG: Cache key doesn't include tenant
Cache::remember('committee:' . $id, 3600, function () use ($id) {
    return Committee::find($id);
});

// RIGHT: Include tenant in cache key
Cache::remember('committee:' . $tenantId->value() . ':' . $id, 3600, function () use ($id, $tenantId) {
    return CommitteeModel::where('tenant_id', $tenantId->value())
        ->where('id', $id->value())
        ->first();
});
```

### ❌ Mistake 5: Joining Tables Without Tenant Filter

```php
// WRONG: No tenant constraint on join
$data = CommitteeModel::join('committee_assignments', ...)
    ->get();

// RIGHT: Filter both tables by tenant
$data = CommitteeModel::where('tenant_id', $tenantId->value())
    ->join('committee_assignments', function ($join) use ($tenantId) {
        $join->on('committees.id', '=', 'committee_assignments.committee_id')
             ->where('committee_assignments.tenant_id', $tenantId->value());
    })
    ->get();
```

---

## 🧪 Testing Multi-Tenancy

### Unit Test Template

```php
<?php

namespace Tests\Unit\Contexts\Membership\Infrastructure\Repositories;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

class EloquentCommitteeRepositoryTenantIsolationTest extends TestCase
{
    private $repo;
    private $tenantA;
    private $tenantB;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenantA = TenantId::fromString('tenant-a-uuid');
        $this->tenantB = TenantId::fromString('tenant-b-uuid');
        
        $this->repo = app(CommitteeRepositoryInterface::class);
    }

    public function test_find_returns_null_for_different_tenant()
    {
        // Create committee for org A
        $committee = CommitteeModel::create([
            'id' => 'committee-001',
            'name' => 'Committee A',
            'tenant_id' => $this->tenantA->value(),
        ]);

        // Try to find from org B
        $found = $this->repo->findForTenant(
            CommitteeId::fromString('committee-001'),
            $this->tenantB  // Different tenant
        );

        $this->assertNull($found);
    }

    public function test_find_returns_committee_for_same_tenant()
    {
        $committee = CommitteeModel::create([
            'id' => 'committee-001',
            'name' => 'Committee A',
            'tenant_id' => $this->tenantA->value(),
        ]);

        $found = $this->repo->findForTenant(
            CommitteeId::fromString('committee-001'),
            $this->tenantA  // Same tenant
        );

        $this->assertNotNull($found);
        $this->assertEquals('committee-001', $found->getId()->value());
    }

    public function test_save_with_wrong_tenant_fails()
    {
        $committee = Committee::form(..., tenantId: $this->tenantA);
        
        // Try to save with different tenant
        $this->expectException(TenantMismatchException::class);
        
        $this->repo->saveForTenant($committee);
        // Then switch context to tenantB... should fail or raise error
    }
}
```

### Feature Test Template

```php
<?php

namespace Tests\Feature\Committee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_organizations_committees()
    {
        // Create two organizations
        $orgA = Organisation::factory()->create();
        $orgB = Organisation::factory()->create();

        // Create committees in each org
        $committeeA = Committee::factory()->for($orgA)->create();
        $committeeB = Committee::factory()->for($orgB)->create();

        // Login as user in org A
        $userA = User::factory()->for($orgA)->create();
        $this->actingAs($userA);

        // Try to view committee from org B
        $response = $this->get("/committee/{$committeeB->id}/dashboard");

        // Should get 404, not org B's data
        $response->assertStatus(404);
    }

    public function test_user_can_only_see_own_organizations_data()
    {
        $orgA = Organisation::factory()->create();
        $committeeA = Committee::factory()->for($orgA)->create();

        $userA = User::factory()->for($orgA)->create();
        $this->actingAs($userA);

        $response = $this->get("/committee/{$committeeA->id}/dashboard");

        $response->assertStatus(200);
        $response->assertSee($committeeA->name);
    }
}
```

---

## 📈 Scaling: Multiple Databases (Future)

The current design uses one database with `tenant_id` scoping. To scale to per-tenant databases later:

1. Routing layer adds `X-Tenant-Id` header to connection selector
2. Repository `saveForTenant()` routes to tenant's database
3. GlobalScope still applies per database
4. Zero code changes needed in domain/application layers

The architecture is prepared for this; the inversion of control (repositories, interfaces) makes scaling seamless.

---

## 🎯 Key Principles

| Principle | Why | How |
|-----------|-----|-----|
| **Tenant everywhere** | Can't forget to scope | Add `tenant_id` to schema, always |
| **Explicit over implicit** | Catch bugs early | Pass `TenantId` explicitly to methods |
| **Three layers** | Defense in depth | Database + ORM + app all check |
| **Test isolation** | Know it works | Tests with multiple tenant IDs |
| **No magic** | Understand the code | No hidden scoping; explicit calls |

---

## 🔍 Debugging Tenant Issues

### Query Is Returning Wrong Data

1. **Check session**: `dd(session('current_organisation_id'))`
2. **Check auth**: `dd(auth()->user()->organisation_id)`
3. **Check query**: Add `.toSql()` to see actual query
4. **Check scope**: Is `BelongsToTenant` applied?

```php
// In tinker
session(['current_organisation_id' => 'org-a-uuid']);
CommitteeModel::toSql();  // Should have WHERE tenant_id = 'org-a-uuid'
CommitteeModel::get();    // Should only show org-a committees
```

### Can't Find Data That Should Exist

1. Confirm `tenant_id` in database matches context
2. Confirm model has `BelongsToTenant` trait
3. Try `.withoutGlobalScopes()` to see if scope is hiding data
4. Check if row is soft-deleted

```php
CommitteeModel::withoutGlobalScopes()
    ->where('id', 'committee-001')
    ->first();  // Should show even if different tenant
```

---

**Next:** Read [05_API_REFERENCE.md](./05_API_REFERENCE.md) for class lookups
