# Multi-Tenancy in Membership Context

## Global Scope Pattern

### How It Works

All membership tables use `organisation_id` + `BelongsToTenant` trait:

```php
// Model
class ApplicationContextModel extends Model
{
    use BelongsToTenant;  // Automatically adds organisation_id scoping
    protected $table = 'membership_applications';
}

// Query
$applications = ApplicationContextModel::all();
// Automatically translates to:
// SELECT * FROM membership_applications WHERE organisation_id = current_organisation_id
```

### In Repositories

Repositories must explicitly remove the global scope when loading:

```php
public function find(ApplicationId $id, TenantId $tenantId): ?Application
{
    // MUST use withoutGlobalScopes()
    $record = $this->model
        ->withoutGlobalScopes()  // ← CRITICAL
        ->where('id', $id->value())
        ->where('organisation_id', $tenantId->value())  // ← Explicit check
        ->first();

    if (!$record) {
        return null;
    }

    return $this->reconstitute($record);
}
```

**Why both?**
1. `withoutGlobalScopes()` - Removes automatic filtering
2. `where('organisation_id', ...)` - Adds explicit check for safety

**Defense in depth:**
- If global scope fails to apply, repository still filters
- If where() clause fails, global scope still filters
- Never rely on just one mechanism

## Tenant Isolation Testing

### Test 1: Basic Isolation

```php
public function test_member_not_visible_across_organisations(): void
{
    $org1 = Organisation::factory()->create();
    $org2 = Organisation::factory()->create();

    $member = Member::factory()
        ->for($org1, 'organisation')
        ->create();

    $repository = app(MemberRepositoryInterface::class);

    // Try to load from org1
    $found1 = $repository->find(
        MemberId::fromString($member->id),
        TenantId::fromOrganisationId($org1->id)
    );
    $this->assertNotNull($found1);  // Should find it

    // Try to load from org2
    $found2 = $repository->find(
        MemberId::fromString($member->id),
        TenantId::fromOrganisationId($org2->id)
    );
    $this->assertNull($found2);  // Should NOT find it
}
```

### Test 2: Status Queries Per Tenant

```php
public function test_finds_only_applications_for_current_tenant(): void
{
    $org1 = Organisation::factory()->create();
    $org2 = Organisation::factory()->create();

    // Create applications in org1
    $app1 = MembershipApplication::factory()
        ->for($org1, 'organisation')
        ->submitted()
        ->create();

    // Create applications in org2
    $app2 = MembershipApplication::factory()
        ->for($org2, 'organisation')
        ->submitted()
        ->create();

    $repository = app(ApplicationRepositoryInterface::class);

    // Query org1 - should only see app1
    $results1 = $repository->findByStatusForTenant(
        ApplicationStatus::submitted(),
        TenantId::fromOrganisationId($org1->id)
    );

    $this->assertCount(1, $results1);
    $this->assertEquals($app1->id, $results1[0]->getId()->value());

    // Query org2 - should only see app2
    $results2 = $repository->findByStatusForTenant(
        ApplicationStatus::submitted(),
        TenantId::fromOrganisationId($org2->id)
    );

    $this->assertCount(1, $results2);
    $this->assertEquals($app2->id, $results2[0]->getId()->value());
}
```

### Test 3: Concurrent Tenants

```php
public function test_concurrent_saves_dont_cross_tenants(): void
{
    $org1 = Organisation::factory()->create();
    $org2 = Organisation::factory()->create();

    // Org1 approves an application
    $app1 = MembershipApplication::factory()
        ->for($org1, 'organisation')
        ->create();

    $useCase1 = app(ApproveApplication::class);
    $useCase1->execute(new ApproveMembershipApplicationCommand(
        applicationId: ApplicationId::fromString($app1->id),
        tenantId: TenantId::fromOrganisationId($org1->id),
        // ... other fields
    ));

    // Org2 approves an application
    $app2 = MembershipApplication::factory()
        ->for($org2, 'organisation')
        ->create();

    $useCase2 = app(ApproveApplication::class);
    $useCase2->execute(new ApproveMembershipApplicationCommand(
        applicationId: ApplicationId::fromString($app2->id),
        tenantId: TenantId::fromOrganisationId($org2->id),
        // ... other fields
    ));

    // Verify isolation
    $repo = app(ApplicationRepositoryInterface::class);

    $status1 = $repo->find(
        ApplicationId::fromString($app1->id),
        TenantId::fromOrganisationId($org1->id)
    );
    $this->assertTrue($status1->getStatus()->isApproved());

    $status2 = $repo->find(
        ApplicationId::fromString($app2->id),
        TenantId::fromOrganisationId($org2->id)
    );
    $this->assertTrue($status2->getStatus()->isApproved());

    // Cross-check: app1 not visible to org2
    $notFound = $repo->find(
        ApplicationId::fromString($app1->id),
        TenantId::fromOrganisationId($org2->id)
    );
    $this->assertNull($notFound);
}
```

## Common Tenant-Related Bugs

### Bug 1: Forgetting withoutGlobalScopes()

```php
// ❌ WRONG
$model = $this->model
    ->where('id', $id)
    ->where('organisation_id', $tenantId)
    ->first();
// Global scope may interfere with explicit where clause
```

**Why it's wrong:**
- Global scope filters first
- Then your where() clause filters
- If current tenant != specified tenant, you get NULL

**Fix:**
```php
// ✅ RIGHT
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $id)
    ->where('organisation_id', $tenantId)
    ->first();
```

### Bug 2: Not Passing TenantId to Save

```php
// ❌ WRONG
$this->repository->save($application);  // No tenantId!

// Repository tries to set organisation_id, but from where?
```

**Fix:**
```php
// ✅ RIGHT
$this->repository->save(
    $application,
    TenantId::fromOrganisationId($org->id)
);
```

### Bug 3: SQL Injection Risk

```php
// ❌ DANGEROUS
$tenantId = request()->input('organisation_id');
$model = $this->model
    ->where('organisation_id', $tenantId)
    ->first();
```

**Why it's dangerous:**
- User can pass any organisation_id
- No validation

**Fix:**
```php
// ✅ SAFE
$tenantId = TenantId::fromOrganisationId(auth()->user()->organisation_id);
$model = $this->model
    ->withoutGlobalScopes()
    ->where('organisation_id', $tenantId->value())
    ->first();
```

### Bug 4: Updating Wrong Tenant's Data

```php
// ❌ WRONG
$application = $this->model
    ->where('id', $appId)
    ->first();  // No organisation_id check!

$application->status = 'approved';
$application->save();
```

**What happens:**
- Global scope filters by current tenant
- But user could pass appId from different tenant
- Your WHERE clause has no organisation_id
- You might accidentally update different tenant's app

**Fix:**
```php
// ✅ RIGHT
$application = $this->model
    ->withoutGlobalScopes()
    ->where('id', $appId)
    ->where('organisation_id', $tenantId->value())
    ->first();

if (!$application) {
    throw new RuntimeException('Application not found for this tenant');
}

$application->status = 'approved';
$application->save();
```

## Controller Layer

### Passing Tenant to Use Cases

```php
final class MembershipApplicationController
{
    public function approve(Organisation $org, MembershipApplication $app): Response
    {
        // Get current tenant from route model binding
        $tenantId = TenantId::fromOrganisationId($org->id);

        $command = new ApproveMembershipApplicationCommand(
            applicationId: ApplicationId::fromString($app->id),
            tenantId: $tenantId,  // ← Pass explicitly
            // ... other fields
        );

        DB::transaction(function () use ($command) {
            $this->approveApplication->execute($command);
        });

        return redirect()->back()->with('success', 'Approved');
    }
}
```

### Middleware Check

```php
// app/Http/Middleware/EnsureTenantIsolation.php
public function handle(Request $request, Closure $next): Response
{
    if ($request->route('organisation')) {
        $organisation = $request->route('organisation');
        $userOrg = auth()->user()->organisation_users
            ->first()
            ->organisation_id;

        if ($organisation->id !== $userOrg) {
            abort(403, 'Unauthorized organisation');
        }
    }

    return $next($request);
}
```

## Database Layer

### Schema with Global Scope

```php
// In migration
Schema::create('membership_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');  // ← Required
    $table->string('user_id');
    $table->string('status');
    // ...

    // Global scope uses this to filter
    $table->foreign('organisation_id')
        ->references('id')
        ->on('organisations');

    // For efficient global scoping
    $table->index('organisation_id');
});

// Model trait applies global scope
class ApplicationContextModel extends Model
{
    use BelongsToTenant;  // Automatically filters by organisation_id
}
```

### Tenant Isolation Guarantee

```
Layer 1: Database         → Foreign key constraint on organisation_id
Layer 2: Model Global     → BelongsToTenant automatically scopes queries
         Scope
Layer 3: Repository       → Explicit where('organisation_id', ...)
Layer 4: Use Case         → Receives and validates TenantId
Layer 5: Controller       → Gets tenant from route model binding
```

Each layer is defensive. Never rely on just one.

---

**Testing Checklist:**
- [ ] Unit: Value objects handle tenant validation
- [ ] Unit: Aggregates never leak tenant data
- [ ] Integration: Repositories enforce tenant boundaries
- [ ] Integration: Global scope works with explicit where
- [ ] Integration: Multiple tenants stay isolated
- [ ] Feature: Controllers pass tenant to use cases
- [ ] Feature: Concurrent tenant requests don't cross

**Last Updated:** May 3, 2026
