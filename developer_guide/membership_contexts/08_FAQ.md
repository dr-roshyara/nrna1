# FAQ & Troubleshooting

Common questions and solutions.

---

## Architecture & Design

### Q: Why is Committee an aggregate root instead of a simple entity?

**A:** Committee needs to enforce business rules across multiple related entities:
- Role limits per committee type
- Member assignments with geographic constraints
- Structural strategies

If these were in separate entities, invariants could break. As an aggregate, Committee owns the whole picture and guarantees consistency.

### Q: Why use Strategy pattern for committee types?

**A:** Each committee type has completely different rules:
- Central: exactly 1 chairperson, no geography
- District: max 2 vice chairs, geography required
- Youth: age 18-35, optional geography

With Strategy, adding a new type is a new class, not a giant if/else. Each strategy encapsulates its rules.

### Q: Can a committee belong to multiple organizations?

**A:** No. Every committee has exactly one `tenant_id`. Multi-tenancy is achieved through tenant ID scoping, not shared ownership.

### Q: Why isn't CommitteeAssignment a separate aggregate?

**A:** Assignments only exist in the context of a Committee. They can't be queried independently, and their validity depends on the Committee's rules (role limits, geography). They're owned entities, not aggregates.

---

## Domain Model Questions

### Q: What's the difference between RolePath and Role?

**A:**
- **Role** = Human-readable label ("Chairperson")
- **RolePath** = Machine-readable path ("1.0.0")

RolePath is sortable, translatable, and type-safe.

### Q: Why do you use value objects for everything?

**A:** Type safety. You can't accidentally pass a string where a CommitteeId is expected. IDEs autocomplete properly. Validation happens in the value object constructor.

```php
// Without value objects (bad)
public function find($id, $tenantId) { }  // What type is $id? String? UUID?

// With value objects (good)
public function findForTenant(CommitteeId $id, TenantId $tenantId) { }  // Clear!
```

### Q: What's inside a GeoReference?

**A:** A geographic path string like `"np.3.15.234.1"`:
- `np` = Country (Nepal)
- `3` = Province ID
- `15` = District ID (within province 3)
- `234` = Local body ID (within district 15)
- `1` = Ward ID (within local body 234)

Used for:
- Filtering committees by region
- Validating that members are within committee boundaries
- Creating sub-committee hierarchies

---

## Multi-Tenancy & Isolation

### Q: Can tenant A see tenant B's committees?

**A:** No. Isolation happens at three levels:
1. Database: Every query filtered by `tenant_id`
2. ORM: `BelongsToTenant` GlobalScope auto-scopes queries
3. Repository: Methods require explicit `TenantId` parameter

### Q: What if I forget to pass `TenantId` to a repository method?

**A:** The method signature requires it:
```php
// Won't compile without TenantId
$repo->findForTenant($id, $tenantId);  // Required
```

The type system catches it.

### Q: How do I test tenant isolation?

**A:** Create items for two tenants and verify they don't leak:
```php
$itemA = create_item($tenantA);
$itemB = create_item($tenantB);

session(['current_organisation_id' => $tenantA->value()]);
$found = $repo->find($itemB->id);

$this->assertNull($found);  // Tenant A can't see item B
```

### Q: What if the database constraint fails?

**A:** That's actually good! It means your code tried to violate an invariant, and the database caught it. This is the third layer of defense.

---

## Development & Implementation

### Q: Where do I put business logic?

**A:**
- **Domain layer** — Pure business rules, no framework
- **Application layer** — Orchestration, transactions, event dispatch
- **Infrastructure layer** — Database, HTTP, technical details

Not in controllers, not in Eloquent models, not in services.

### Q: I need to access a committee from an external context. What do I do?

**A:** Use the repository interface:
```php
// In any layer
$repo = app(CommitteeRepositoryInterface::class);
$committee = $repo->findForTenant($id, $tenantId);
```

The repository abstracts away Eloquent details.

### Q: Can I call domain methods directly in a controller?

**A:** No. The controller should call a use case, which calls domain methods:
```php
// WRONG: Direct domain call
$committee = Committee::find($id);  // Where's TenantId?
$committee->assignMember(...);

// RIGHT: Use case owns transaction
$useCase->execute(new AssignMemberDto(...));
```

### Q: Do I have to use a use case for simple reads?

**A:** For reads that don't need transactions/events, you can use a query use case, but it's still a use case:
```php
$view = app(GetCommitteeDashboard::class)->execute($id, $tenantId);
```

This keeps the pattern consistent.

### Q: How do I handle errors?

**A:** Throw domain exceptions from domain layer:
```php
// Domain
if ($role is not valid) {
    throw new InvalidMemberAssignmentException(...);
}

// Application catches and decides what to do
try {
    $useCase->execute(...);
} catch (InvalidMemberAssignmentException) {
    return back()->withErrors([...]);
}
```

### Q: Where do I validate form input?

**A:**
- **FormRequest** — Validates request structure (required fields, types)
- **Domain** — Validates business rules (role limits, geography constraints)

```php
// FormRequest: Is email valid format?
$rules = ['email' => 'email'];

// Domain: Is this email already registered?
if ($repo->findByEmail($email)) {
    throw new EmailAlreadyRegisteredException();
}
```

---

## Testing

### Q: My unit test is failing because it needs the database.

**A:** You're testing at the wrong level. Unit tests should mock repositories:
```php
// WRONG: Needs database
$this->repo->findForTenant($id, $tenantId);

// RIGHT: Mock the repository
$this->repo = $this->createMock(CommitteeRepositoryInterface::class);
$this->repo->expects(...)->willReturn($committee);
```

### Q: Should I test private domain methods?

**A:** No. Test public methods (the aggregate interface). Private methods are tested indirectly.

### Q: How many tests do I need?

**A:**
- Unit: 1 per business rule
- Integration: 1 per repository method
- Feature: 1 per user story

Example for "assign member to committee":
- Unit: test invariant (role limit), test geography check
- Integration: test persistence, test tenant isolation
- Feature: test HTTP endpoint, test response

### Q: What should feature tests cover?

**A:**
- Happy path (success)
- Unhappy path (errors)
- Tenant isolation (can't access other org's data)
- Authentication (unauthenticated users redirected)

---

## Common Errors

### Error: "Class 'CommitteeRepositoryInterface' not found"

**Solution:** Import the interface:
```php
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
```

Or check that it's registered in the service provider:
```php
// MembershipServiceProvider.php
$this->app->bind(
    CommitteeRepositoryInterface::class,
    EloquentCommitteeRepository::class
);
```

### Error: "No tenant context in session"

**Solution:** The `TenantContext` couldn't resolve the current organization. Check:
1. Is user logged in? `auth()->check()`
2. Does user have `organisation_id`? `auth()->user()->organisation_id`
3. Is session key set? `session('current_organisation_id')`

For tests, explicitly set:
```php
$tenantContext->setContext('org-uuid');
```

### Error: "Committee with ID X not found" (but you know it exists)

**Solution:** Tenant isolation. The committee exists, but belongs to a different organization.

Check:
```php
// In tinker
$committee = CommitteeModel::find('committee-id');
dd($committee->tenant_id);  // Whose tenant is it?
```

Then query with the right tenant:
```php
$repo->findForTenant($id, $correctTenantId);
```

### Error: "SQLSTATE[HY000]: no such table: pg_indexes"

**Solution:** You're running PostgreSQL-specific code in SQLite (tests). Use cross-database methods:
```php
// WRONG
DB::select("SELECT * FROM pg_indexes");

// RIGHT
Schema::getIndexes('table_name');
```

### Error: "Class does not exist: App\Contexts\..."

**Solution:** The class exists but isn't loaded. Check:
1. Namespace matches file path
2. No typos in `use` statement
3. File was created (check `git status`)

### Error: "Call to undefined method AssignMemberDto::..."

**Solution:** The DTO is read-only. You can't call setters. Create a new instance:
```php
// WRONG
$dto->rolePath = $newRole;

// RIGHT
$dto = new AssignMemberDto(..., rolePath: $newRole);
```

### Error: "The value does not exist in the enum"

**Solution:** You're using an invalid enum value:
```php
// WRONG
CommitteeType::invalid_type();  // This enum value doesn't exist

// RIGHT
CommitteeType::central();  // Valid
CommitteeType::district();  // Valid
```

Check `CommitteeType.php` for valid values.

---

## Performance

### Q: How do I optimize committee queries?

**A:** Use the repository's specialized methods:
```php
// Slow: Fetch all, filter in PHP
$all = $repo->findAllForTenant($tenantId);
$districts = array_filter($all, fn($c) => $c->type()->value() === 'district');

// Fast: Filter in SQL
$districts = $repo->findByTypeForTenant(CommitteeType::district(), $tenantId);
```

### Q: Should I cache committees?

**A:** Yes, but include tenant in the key:
```php
// WRONG: Cache key doesn't separate tenants
Cache::remember('committee:' . $id, 3600, fn() => $repo->find($id));

// RIGHT: Tenant-aware cache key
$key = "committee:{$tenantId->value()}:{$id->value()}";
Cache::remember($key, 3600, fn() => $repo->findForTenant($id, $tenantId));
```

---

## Going Forward

### Q: I want to add a new feature. Where do I start?

**A:** Follow this order:
1. Write tests (understand requirements)
2. Add domain logic (Committee aggregate method)
3. Create DTO (input shape)
4. Create use case (orchestration)
5. Add controller (HTTP entry point)
6. Add route (URL mapping)
7. Create Vue component (UI)

### Q: How do I know if I'm breaking the architecture?

**A:** Ask these questions:
- [ ] Does domain layer have any `use Illuminate\*` imports?
- [ ] Does application layer own transactions?
- [ ] Does application layer dispatch events?
- [ ] Does repository accept `TenantId` parameter?
- [ ] Are value objects used instead of plain strings?
- [ ] Are all classes marked `final`?

If all yes, you're good.

### Q: How do I extend the system?

**A:** Everything is extensible:
- **New committee type?** Create new Strategy class
- **New role?** Add to RolePath enum
- **New domain event?** Create event class and listener
- **New query?** Create new repository method + use case

The architecture supports adding features without changing existing code.

---

## Getting Help

1. **Check the README** → `developer_guide/membership_contexts/README.md`
2. **Search API Reference** → `05_API_REFERENCE.md`
3. **Find example** → `07_WORKFLOWS.md`
4. **Read architecture** → `01_ARCHITECTURE.md`
5. **Understand domain** → `02_DOMAIN_MODEL.md`
6. **Study tests** → `tests/Unit/Contexts/Membership/`

---

**Last Updated:** May 3, 2026  
**Author:** Claude  
**Status:** Phase 2 Complete

For more help, refer to the specific documentation file listed above, or check the implementation files directly — they're well-commented.
