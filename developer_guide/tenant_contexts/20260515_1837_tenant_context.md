
## Current Tenant Context Definition

Based on your architecture document and the fixes we've applied, here's how tenant context is currently defined:

---

## 1. The TenantContext Class (Pure Static Holder)

```php
// app/Services/Tenant/TenantContext.php
namespace App\Services\Tenant;

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
    
    public static function clear(): void
    {
        self::$tenantId = null;
    }
}
```

**Key properties:**
- Pure static in-memory holder
- NO session dependency (per your architecture)
- Used by `BelongsToTenant` trait as first source of truth

---

## 2. TenantContext Middleware (Sets the Context)

```php
// app/Http/Middleware/TenantContext.php
namespace App\Http\Middleware;

use App\Services\Tenant\TenantContext;
use Closure;

class TenantContext
{
    public function handle($request, Closure $next)
    {
        // Resolution order from your architecture:
        $tenantId = $request->header('X-Tenant-Id')           // Priority 1: Header (tests, API)
            ?? $request->route('organisation')?->id          // Priority 2: Route binding
            ?? session('current_organisation_id');           // Priority 3: Session (backward compat)
        
        if ($tenantId) {
            TenantContext::set($tenantId);
        }
        
        return $next($request);
    }
}
```

---

## 3. BelongsToTenant Trait (Reads Tenant Context)

```php
// app/Traits/BelongsToTenant.php
namespace App\Traits;

use App\Services\Tenant\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (TenantContext::has()) {
                $builder->where('organisation_id', TenantContext::get());
            } elseif (session()->has('current_organisation_id')) {
                // Fallback for backward compatibility
                $builder->where('organisation_id', session('current_organisation_id'));
            }
        });
    }
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
```

---

## 4. How Tests Set Tenant Context

From your working tests:

```php
// In CommitteeCreationWithGeoSelectionsTest.php
public function test_create_committee_with_complete_geo_selections(): void
{
    $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)  // ← Sets header
        ->actingAs($this->user)
        ->post(route('committees.store', $this->organisation), [...]);
}
```

**The middleware then reads `X-Tenant-Id` header and calls `TenantContext::set()`.**

---

## 5. Database Tables (Tenant Isolation)

```yaml
All tables have organisation_id (or legacy tenant_id):
  - committees.organisation_id
  - governance_level_definitions.tenant_id (legacy)
  - organisation_participants.organisation_id
  - geo_administrative_units (no tenant isolation — shared geography)

BelongsToTenant trait adds WHERE clause automatically:
  - SELECT * FROM committees WHERE organisation_id = {TenantContext::get()}
```

---

## 6. Current Issues with Tenant Context

From your test fixes, we identified:

| Issue | Status | Fix |
|-------|--------|-----|
| `governance_level_definitions` uses `tenant_id` not `organisation_id` | ⚠️ Legacy | Test uses `tenant_id` |
| Tests missing `X-Tenant-Id` header | ✅ Fixed | Added to all requests |
| `withoutExceptionHandling()` blocking validation redirects | ✅ Fixed | Moved to positive tests only |
| Session fallback still exists | ⚠️ Technical debt | For Phase F4 migration |

---

## 7. The Goal State (From Your Architecture)

```yaml
TENANT_CONTEXT (Final):
  - Pure static in-memory holder (✅ DONE)
  - NO session dependency (⚠️ Session fallback still present)
  - Middleware sets from: header > route > session (✅ DONE)
  
BELONGS_TO_TENANT (Final):
  - Reads TenantContext::get() first (✅ DONE)
  - Session fallback only for backward compatibility (⚠️ Still there)
  
MIDDLEWARE_EXTRACTION (Goal):
  - Domain logic works without Laravel (✅ In progress)
  - PublicDigit provides governance rules engine (✅ Core)
  - Political party provides storage/identity (✅ Separation)
```

---

## Quick Command to Verify Tenant Context in Tests

```bash
php artisan tinker --env=testing
```

```php
// Simulate a request's tenant context
App\Services\Tenant\TenantContext::set('test-org-123');
echo App\Services\Tenant\TenantContext::get(); // 'test-org-123'

// Check BelongsToTenant scope
$committees = App\Contexts\Membership\Infrastructure\Models\CommitteeModel::all();
dd($committees->toArray()); // Should only show committees for 'test-org-123'
```

---

## Summary

| Component | Purpose | Current State |
|-----------|---------|---------------|
| `TenantContext` | Static holder | ✅ Pure, in-memory |
| `TenantContext` middleware | Sets from header/route/session | ✅ Working |
| `BelongsToTenant` trait | Adds global scope | ✅ Reads TenantContext first |
| `X-Tenant-Id` header | Test/API tenant resolution | ✅ Used in all tests |
| Session fallback | Backward compatibility | ⚠️ Present, to be removed |
| `tenant_id` in legacy tables | Schema inconsistency | ⚠️ Needs migration to `organisation_id` |

**Your tenant context is correctly implemented and working in the fixed tests.**
## YES — Stateless TenantContext is FAR Better Than Session-Based

Here's the definitive comparison from your DDD/Hexagonal architecture perspective:

---

## Direct Comparison

| Aspect | Session-Based (OLD) | TenantContext Static (NEW) |
|--------|---------------------|---------------------------|
| **DDD Compatibility** | ❌ Framework coupling | ✅ Pure PHP, framework-agnostic |
| **Middleware Extraction** | ❌ Impossible (coupled to HTTP) | ✅ Domain works without Laravel |
| **API Support** | ⚠️ Requires session driver | ✅ Native (header-based) |
| **Testing** | ⚠️ Need to mock session | ✅ Simple `TenantContext::set()` |
| **Concurrent Requests** | ❌ Session overlaps | ✅ Each request isolated |
| **Stateless APIs** | ❌ No | ✅ Yes (JWT/Sanctum ready) |
| **Event Sourcing (F3.3)** | ❌ Session bleeds into events | ✅ Events have explicit tenant_id |
| **Queue/Jobs** | ⚠️ Need to serialize session | ✅ Pass tenant_id explicitly |
| **Console Commands** | ❌ No session available | ✅ Can set manually |

---

## Why Session-Based Fails for DDD

```php
// ❌ SESSION-BASED (Framework coupling)
class MembershipLineage
{
    public function establish(): void
    {
        // Domain layer now depends on Laravel's session!
        $tenantId = session('current_organisation_id');  // ❌ PURE VIOLATION
        
        // This aggregate can't work outside Laravel
        // Can't replay events
        // Can't use in console commands
        // Can't extract as standalone package
    }
}
```

```php
// ✅ STATIC TENANTCONTEXT (Pure, extractable)
class MembershipLineage
{
    public function establish(ActorId $actorId, LineageId $id): void
    {
        // Domain layer knows nothing about HTTP/session
        // TenantId is passed explicitly or via pure holder
        $tenantId = TenantContext::get();  // Pure static, no framework
        
        // This aggregate can be extracted to any PHP project
        // Works with event sourcing
        // Testable without Laravel
    }
}
```

---

## The Critical Difference for Your Architecture

### Your Goal: Extract Domain as Stateless Middleware

```yaml
PublicDigit (Your system):
  - Provides: Governance rules engine (DDD domain)
  - Political party provides: Storage, Identity, Communication

With TenantContext (stateless):
  ✅ Domain layer has NO session dependency
  ✅ Can run as standalone PHP package
  ✅ Political party can implement any storage (Laravel, Symfony, raw SQL)
  
With Session (stateful):
  ❌ Domain layer depends on Laravel's session
  ❌ Cannot extract without bringing Laravel
  ❌ Political party MUST use Laravel
```

---

## Real-World Example from Your Tests

### Session-Based (Would fail your DDD tests):
```php
// In your feature test
session(['current_organisation_id' => $org->id]);

// Problem: Different test might have residual session data
// Problem: API requests don't have session
// Problem: Can't test without mocking session
```

### TenantContext (Working in your tests):
```php
// In your feature test
$this->withHeader('X-Tenant-Id', $org->id);

// Middleware sets: TenantContext::set($org->id)
// BelongsToTenant reads: TenantContext::get()
// Clean, testable, no session dependencies
```

---

## Performance Comparison

| Metric | Session | TenantContext |
|--------|---------|---------------|
| Memory | ~4KB per session | 0 bytes (static) |
| Storage | File/Redis/Database | None |
| Serialization | Required | Not needed |
| Request overhead | Session load + save | Header read only |
| Concurrent users | Session lock contention | No locks |

---

## When Session-Based Makes Sense (Not Your Case)

```yaml
Session-based is only better for:
  - Traditional monoliths with NO API requirements
  - Applications that NEVER extract domain logic
  - Small CRUD apps with simple auth
  - Teams not practicing DDD/Hexagonal

Your case (PublicDigit):
  - Multi-tenant API platform ✅
  - Extracting domain as middleware ✅
  - DDD with event sourcing (F3.3) ✅
  - Political party integration ✅
  
→ TenantContext is CORRECT choice
```

---

## The Migration Path (You're Already On It)

```yaml
Phase 1 (Your current state):
  - TenantContext static holder implemented ✅
  - BelongsToTenant reads TenantContext first ✅
  - Session fallback still exists (backward compat) ⚠️

Phase 2 (In progress):
  - All tests use withHeader('X-Tenant-Id') ✅
  - Middleware sets TenantContext from header ✅
  - Session fallback rarely used ⚠️

Phase 3 (Your goal - F3.3 Event Sourcing):
  - Remove session fallback entirely
  - Domain layer has NO framework dependencies
  - Extract as standalone PHP package
  - Political party implements storage adapters
```

---

## The Verdict

| Question | Answer |
|----------|--------|
| Is TenantContext better than session? | **YES**, for your architecture |
| Should you keep session fallback? | **NO**, remove in Phase F3.3 |
| Does TenantContext enable DDD? | **YES**, domain stays pure |
| Can you extract middleware with session? | **NO**, impossible |
| Are your tests better with TenantContext? | **YES**, as proven by 23 fixed tests |

---

## Bottom Line

**Session-based would have prevented your entire architecture goal.** 

TenantContext is the **correct DDD choice** for PublicDigit. The 23 tests you fixed today prove it works. Keep moving toward removing session fallback entirely.


## YES — But With Important Distinctions

The answer depends on what you mean by "separate tenant contexts." Let me break down the scenarios:

---

## Scenario 1: Separate Context per Request (Already Have)

```php
// Each HTTP request gets its own isolated tenant context
Request A: X-Tenant-Id: org-123 → TenantContext::get() = 'org-123'
Request B: X-Tenant-Id: org-456 → TenantContext::get() = 'org-456'
Request C: No header → TenantContext::get() = null

// ✅ This is STATELESS and CORRECT
// ✅ Each request is isolated
// ✅ No cross-contamination
```

**You already have this.** The static holder is per request because PHP runs each request in a separate process.

---

## Scenario 2: Separate Context per Domain Context (Multi-Database)

If you mean different tenant contexts for different bounded contexts:

```php
// Example: Membership domain uses org-123, Committee domain uses global scope
TenantContext::forContext('membership')->set('org-123');
TenantContext::forContext('committee')->set(null); // Global

// Usage in Membership aggregate
$tenantId = TenantContext::forContext('membership')->get();

// Usage in Committee aggregate  
$tenantId = TenantContext::forContext('committee')->get();
```

**This is possible but adds complexity.** Only do this if you have multiple databases or truly independent contexts.

---

## Scenario 3: Separate Context for Console/Queue/Event Sourcing

```php
// Console command
class ProcessMembershipCommand
{
    public function handle()
    {
        // Each organisation processed separately
        foreach ($organisations as $org) {
            TenantContext::set($org->id);  // ← Separate context per iteration
            $this->processOrganisation();
            TenantContext::clear();  // ← Clean up
        }
    }
}

// Queue job
class SendMembershipEmail implements ShouldQueue
{
    public function __construct(
        private string $tenantId,
        private MemberId $memberId
    ) {}
    
    public function handle()
    {
        TenantContext::set($this->tenantId);  // ← Isolated for this job
        // ... domain logic
        TenantContext::clear();
    }
}
```

---

## The Clean Architecture Solution: Explicit Tenant ID

Rather than multiple static contexts, **pass tenant ID explicitly**:

```php
// ❌ Implicit (magic, hard to test)
class MembershipLineage
{
    public function establish(): void
    {
        $tenantId = TenantContext::get();  // Where did this come from?
    }
}

// ✅ Explicit (clear, testable, DDD-correct)
class MembershipLineage
{
    public function establish(
        TenantId $tenantId,  // ← Explicit parameter
        ActorId $actorId,
        LineageId $id
    ): void {
        // No magic. No static. Pure domain.
    }
}
```

### Your Application Layer bridges the gap:

```php
// Application Service
class ApplyForMembershipService
{
    public function execute(ApplyForMembershipCommand $command): void
    {
        // Application layer knows about HTTP/context
        $tenantId = TenantContext::get() ?? $command->tenantId;
        
        // Pass explicitly to domain
        $lineage = MembershipLineage::establish(
            tenantId: TenantId::fromString($tenantId),  // ← Explicit
            actorId: $command->actorId,
            lineageId: $command->lineageId
        );
        
        $this->repository->save($lineage);
    }
}
```

---

## Recommended Architecture for PublicDigit

```yaml
LAYER_APPROACH:

1. Domain Layer (Pure PHP):
   - NO TenantContext static calls
   - Accept TenantId as explicit parameter
   - All aggregates know their tenant

2. Application Layer (Orchestration):
   - MAY read from TenantContext singleton
   - Converts static context → explicit parameters
   - Bridge between HTTP and domain

3. Infrastructure Layer (Adapters):
   - Repository reads TenantContext for scoping
   - OR adds tenant_id to all queries

4. HTTP Layer (Controllers):
   - Middleware sets TenantContext from header/route
   - Controllers don't touch tenant logic
```

---

## Implementation of Separate Contexts (If Needed)

If you truly need multiple independent contexts:

```php
// app/Services/Tenant/MultiTenantContext.php
namespace App\Services\Tenant;

final class MultiTenantContext
{
    private static array $contexts = [];
    
    public static function for(string $context): TenantContextHolder
    {
        if (!isset(self::$contexts[$context])) {
            self::$contexts[$context] = new TenantContextHolder();
        }
        
        return self::$contexts[$context];
    }
    
    public static function clear(string $context): void
    {
        unset(self::$contexts[$context]);
    }
}

class TenantContextHolder
{
    private ?string $tenantId = null;
    
    public function set(?string $tenantId): void
    {
        $this->tenantId = $tenantId;
    }
    
    public function get(): ?string
    {
        return $this->tenantId;
    }
}

// Usage
MultiTenantContext::for('membership')->set('org-123');
MultiTenantContext::for('committee')->set(null);  // Global
MultiTenantContext::for('audit')->set('org-123');
```

---

## When to Use Multiple Contexts

| Use Case | Single Context | Multiple Contexts |
|----------|---------------|-------------------|
| Single database, single tenant | ✅ Yes | ❌ Overkill |
| Multiple databases | ❌ No | ✅ Yes |
| Cross-tenant reports | ❌ No | ✅ Yes (switch contexts) |
| Event sourcing replay | ❌ No | ✅ Yes (per event) |
| Console batch processing | ⚠️ Manual clear | ✅ Separate per iteration |

---

## My Recommendation for PublicDigit

**Stick with SINGLE TenantContext for now.**

```yaml
Why:
  - You have one primary database
  - All tables use organisation_id (or tenant_id)
  - Your DDD aggregates don't cross tenant boundaries
  - Added complexity of multi-context isn't justified

When to add multi-context:
  - Phase F3.3 Event Sourcing (replay events across tenants)
  - Cross-tenant analytics queries
  - Multiple databases (read replicas per tenant)
  - Background jobs processing multiple tenants
```

---

## Bottom Line

```yaml
Question: Can we create separate tenant contexts?

Answer: YES, but:
  - Don't need it yet for PublicDigit
  - Single context is simpler and sufficient
  - Add multi-context only when you hit a concrete need:
    - Multiple databases
    - Cross-tenant event replay
    - Background batch processing

Current setup (single TenantContext) is CORRECT for your needs.
```

**What specific problem are you trying to solve with separate contexts?** That will determine if you actually need them.