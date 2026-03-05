# UUID Multi-Tenancy Implementation Design

## Document Information
- **Date**: 2026-03-05
- **Status**: Approved ✅
- **Author**: Claude Code with Senior Laravel Architect Role
- **Project**: Public Digit Voting Platform
- **Context**: Complete migration from integer-based to UUID-based multi-tenancy

## Executive Summary

This document outlines the complete migration of the Public Digit platform from integer-based multi-tenancy to UUID-based multi-tenancy. Since the project is not in production and has "almost full structure" but no live data, we are implementing Option 1: **Full Migration - Convert All Existing Integer IDs to UUIDs**.

## Background

### Current State
- ✅ Multi-tenancy foundation complete (integer-based)
- ✅ BelongsToTenant trait and TenantContext middleware
- ✅ 6-case routing active
- ❌ Integer IDs throughout schema
- ❌ Assumption of platform organisation ID=1
- ❌ Mixed patterns for tenant isolation

### Target State
- ✅ UUID primary keys throughout
- ✅ Explicit TenantContext service
- ✅ Repository pattern for tenant scoping
- ✅ Platform organisation resolved via `type='platform', is_default=true`
- ✅ Comprehensive tenant isolation tests

## Architecture Decisions

### 1. UUID Strategy
**Decision**: Use Laravel's native `HasUuids` trait (no external package)
**Rationale**:
- Built-in Laravel 11 support
- Zero external dependencies
- Perfect fit for "explicit over magic" architecture principle
- Works immediately with minimal configuration

### 2. Tenant Context Management
**Decision**: Replace `BelongsToTenant` trait with explicit `TenantContext` service
**Rationale**:
- Removes implicit magic scoping
- Makes tenant context visible in code flow
- Enables explicit validation and error handling
- Supports session persistence for web requests

### 3. Query Scoping Pattern
**Decision**: Repository pattern with implicit tenant scoping
**Rationale**:
- Centralized tenant logic
- Consistent query patterns
- Easy to test and mock
- Clear separation of concerns

## Database Schema

### Core Tables Migration

| Table | Changes |
|-------|---------|
| `organisations` | `id` → UUID, add `type` enum, add `is_default` boolean |
| `users` | `id` → UUID, `organisation_id` → UUID FK |
| `user_organisation_roles` | All IDs → UUID |
| `elections` | `id` → UUID, `organisation_id` → UUID FK |
| *All other tenant tables* | Convert integer IDs to UUIDs |

### Key Constraints
```sql
-- Ensure single default platform organisation
ALTER TABLE organisations ADD CONSTRAINT organisations_unique_default_platform
UNIQUE (type, is_default);

-- Prevent duplicate organisation memberships
ALTER TABLE user_organisation_roles ADD CONSTRAINT user_org_unique
UNIQUE (user_id, organisation_id);
```

### Indexing Strategy
- All UUID foreign keys indexed
- Composite indexes on `(organisation_id, status)` patterns
- Maintain query performance with UUID storage

## Models Layer

### Base Configuration
All tenant-aware models implement:
```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;

protected $keyType = 'string';
public $incrementing = false;
```

### Key Models

#### Organisation Model
```php
class Organisation extends Model
{
    // Relationships
    public function users() // belongsToMany via pivot
    public function elections() // hasMany

    // Business Logic
    public function isPlatform(): bool
    public function isTenant(): bool
    public static function getDefaultPlatform(): ?self
}
```

#### User Model
```php
class User extends Authenticatable
{
    // Relationships
    public function currentOrganisation() // belongsTo
    public function organisations() // belongsToMany via pivot

    // Membership Logic
    public function belongsToOrganisation(string $orgId): bool
    public function getRoleInOrganisation(string $orgId): ?string
}
```

#### UserOrganisationRole Model (Pivot)
```php
class UserOrganisationRole extends Model
{
    // Relationships
    public function user() // belongsTo
    public function organisation() // belongsTo
}
```

## Tenant Context Service

### Core Interface
```php
class TenantContext
{
    // Context Management
    public function setContext(User $user, Organisation $org): void
    public function getCurrentOrganisation(): Organisation
    public function getCurrentOrganisationId(): string

    // Context State
    public function isPlatformContext(): bool
    public function isTenantContext(): bool
    public function clear(): void
}
```

### Features
- ✅ Session persistence for web requests
- ✅ Automatic resolution from session
- ✅ Validation of user membership
- ✅ Type-safe UUID handling
- ✅ Runtime safety checks

## Middleware

### TenantMiddleware
```php
class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Get authenticated user
        // 2. Resolve organisation from route/parameter/input
        // 3. Validate user membership in organisation
        // 4. Set TenantContext
        // 5. Update user's current organisation if needed
    }
}
```

### Registration
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [\App\Http\Middleware\TenantMiddleware::class],
    'api' => [\App\Http\Middleware\TenantMiddleware::class],
];

protected $routeMiddleware = [
    'tenant' => \App\Http\Middleware\TenantMiddleware::class,
];
```

## Repository Pattern

### Example: ElectionRepository
```php
class ElectionRepository
{
    // All methods implicitly scope to current organisation
    public function find(string $id): ?Election
    public function getAll(array $filters = []): Collection
    public function create(array $data): Election
    public function update(string $id, array $data): Election
    public function delete(string $id): bool

    // Business Logic
    public function getActiveElections(): Collection
}
```

### Key Principles
- Repository constructor injects `TenantContext`
- All queries use `$this->tenantContext->getCurrentOrganisationId()`
- No raw queries bypass tenant scoping
- Consistent error handling

## Registration Flow

### New User Registration
```php
public function store(Request $request)
{
    // 1. Get default platform organisation
    $platformOrg = Organisation::getDefaultPlatform();

    // 2. Create user with platform organisation
    $user = User::create([
        ...,
        'organisation_id' => $platformOrg->id
    ]);

    // 3. Create pivot record for platform membership
    UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $platformOrg->id,
        'role' => 'member'
    ]);

    // 4. Set tenant context to platform
    $tenantContext->setContext($user, $platformOrg);
}
```

## Testing Strategy

### Factory Updates
```php
// All factories generate UUIDs automatically
OrganisationFactory::platform()->default()
UserFactory::forOrganisation($organisation)
ElectionFactory::forOrganisation($organisation)
```

### Key Test Suite
```php
class TenantIsolationTest extends TestCase
{
    test_user_cannot_access_other_tenant_data()
    test_user_can_access_own_tenant_data()
    test_platform_user_can_access_all_tenants()
    test_query_without_tenant_context_fails_safely()
    test_user_cannot_switch_to_unauthorised_organisation()
    test_user_can_switch_to_authorised_organisation()
}
```

### Test Principles
- Each test creates isolated tenant data
- Verify cross-tenant queries return empty/404
- Test platform vs tenant context differences
- Validate UUID format in all responses

## Database Seeder

### Initial Data Structure
```php
DatabaseSeeder::run()
{
    // 1. Platform Organisation
    $platform = Organisation::create([
        'name' => 'Public Digit Platform',
        'slug' => 'platform',
        'type' => 'platform',
        'is_default' => true,
        'settings' => [...]
    ]);

    // 2. Platform Admin
    $admin = User::create([...]);
    UserOrganisationRole::create([
        'user_id' => $admin->id,
        'organisation_id' => $platform->id,
        'role' => 'super-admin'
    ]);

    // 3. Demo Tenant
    $demo = Organisation::create([
        'name' => 'Demo Organisation',
        'slug' => 'demo',
        'type' => 'tenant',
        'settings' => ['is_demo' => true]
    ]);

    // 4. Demo User with cross-membership
    $demoUser = User::create([...]);
    UserOrganisationRole::create([...]); // Demo admin
    UserOrganisationRole::create([...]); // Platform member
}
```

## Implementation Timeline

| Phase | Hours | Deliverables | Dependencies |
|-------|-------|--------------|--------------|
| 1. Database Migrations | 4 | All UUID migration files | None |
| 2. Models & Relationships | 3 | Updated models with HasUuids | Phase 1 |
| 3. TenantContext Service | 2 | Service class + unit tests | Phase 2 |
| 4. Middleware | 1 | TenantMiddleware + tests | Phase 3 |
| 5. Repositories | 3 | ElectionRepository + tests | Phase 3 |
| 6. Registration Flow | 2 | Updated controllers | Phase 2-4 |
| 7. Testing | 4 | Factories + isolation tests | All phases |
| 8. Seeding | 1 | Updated seeders | Phase 1-2 |
| **Total** | **20** | **Complete Implementation** | |

## Migration Strategy

### Step 1: Prepare Development Environment
```bash
# 1. Backup current migrations (optional)
cp -r database/migrations database/migrations_backup_$(date +%Y%m%d)

# 2. Rollback all migrations (clean slate)
php artisan migrate:rollback

# 3. Delete existing migration files
rm database/migrations/*.php
```

### Step 2: Implement New Migrations
1. Create all migration files with UUID schema
2. Test migrations locally
3. Verify foreign key relationships

### Step 3: Update Codebase
1. Update models with `HasUuids`
2. Implement `TenantContext` service
3. Update controllers and repositories
4. Update tests and factories

### Step 4: Test Thoroughly
1. Run all existing tests
2. Add new tenant isolation tests
3. Test registration flow
4. Test platform/tenant switching

## Risk Assessment

| Risk | Probability | Impact | Mitigation |
|------|------------|--------|------------|
| UUID performance issues | Low | Medium | Proper indexing, test with realistic data |
| Migration complexity | Medium | High | Thorough testing, rollback procedures |
| Broken relationships | Medium | High | Foreign key constraints, validation tests |
| Session compatibility | Low | Low | Session serialization handles strings |

## Success Criteria

### Technical Success
- ✅ All tables use UUID primary keys
- ✅ All foreign keys reference UUIDs
- ✅ TenantContext service fully functional
- ✅ All existing tests pass
- ✅ New tenant isolation tests pass

### Architectural Success
- ✅ No integer ID assumptions in code
- ✅ Platform organisation resolved via business logic
- ✅ Explicit tenant context over implicit scoping
- ✅ Repository pattern for tenant queries

### Operational Success
- ✅ Registration flow works end-to-end
- ✅ Users can switch between organisations
- ✅ Platform admin can access all tenants
- ✅ Demo mode functions correctly

## Next Steps

1. **Approve this design document** ✅ (Already approved via section review)
2. **Create implementation plan** (Invoke writing-plans skill)
3. **Execute implementation** (Follow plan phases)
4. **Validate implementation** (Run comprehensive tests)

---

## Revision History

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2026-03-05 | 1.0 | Initial design based on architecture document | Claude Code |
| 2026-03-05 | 1.1 | Approved via section-by-section review | User |

---

**Status**: ✅ **APPROVED FOR IMPLEMENTATION**