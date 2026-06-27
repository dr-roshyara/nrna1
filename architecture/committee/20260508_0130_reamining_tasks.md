Here is a **senior architecture review of Phase B v2 plan**. I will focus on: correctness, bounded context purity, testability, DDD alignment, and risks before execution.

---

# 1. Executive Verdict

### 🟢 Overall assessment

The plan is **strong in intent and domain modeling**, but:

* ❗ It **violates one of its own architectural constraints**
* ❗ It introduces **hidden coupling to Laravel/DB inside domain policy path**
* ❗ It risks **duplicating Organisation as a second source of truth for governance state**
* ⚠️ It over-engineers early Phase B before stabilizing minimal enforcement slice

---

# 2. Key Strengths (Keep These)

## 2.1 Clear separation of concerns

Good layering:

* Domain → Actor / Geography / Context / Capability
* Application → Policy adapter
* Infrastructure → DI wiring

✔ This is correct DDD layering direction.

---

## 2.2 Capability-based model (not RBAC)

Strong decision:

* Moves from `role → permission`
* To `context → capability evaluation`

✔ This is the correct institutional modeling approach.

---

## 2.3 Stateless policy engine

Good design choice:

* `GovernanceCapabilityPolicyEngine` is pure logic
* Deterministic evaluation

✔ This is exactly what you want for testability.

---

## 2.4 Evaluation object design

`CapabilityEvaluation`:

* explicit failure reasons
* suggestion field
* structured denial

✔ This is production-grade authorization modeling.

---

# 3. Critical Issues (Must Fix Before Implementation)

## ❌ Issue 1 — Hidden contradiction: “Policy engine must be pure” is violated

### Problem

Plan says:

> “GovernanceCapabilityPolicyEngine must be pure (no repository dependencies)”

But later:

* Application layer builds context using:

  * `Organisation::find()`
  * `UserOrganisationRoleRepository`
  * DB-derived epoch state

This creates:

### ❗ Hidden coupling:

Even if engine is pure, **context builder is not isolated**, and policy behavior becomes DB-dependent indirectly.

---

### Fix (important)

Introduce explicit boundary:

```text
Policy Engine (pure)
    ↑
Policy Context Builder (application service)
    ↑
Infrastructure adapters (DB, auth, organisation)
```

👉 Rename missing layer:

### REQUIRED ADDITION:

```
GovernanceCapabilityContextFactory
```

This is missing but essential.

---

## ❌ Issue 2 — GovernanceEpochContext is incorrectly defined (semantic leak)

### Problem

Plan says:

> wraps governance state from Organisation (NOT CommitteeStructure)

But then:

* epoch is used to validate committee structure operations
* structure evolution depends on structure state

### Conflict:

You are mixing:

| Concept                        | Source                 |
| ------------------------------ | ---------------------- |
| Organisation governance status | coarse system state    |
| CommitteeStructure epoch       | domain evolution state |

---

### Fix

Split into 2 explicit contexts:

#### REQUIRED:

```
OrganisationGovernanceContext
CommitteeStructureEpochContext
```

Then compose:

```text
CapabilityContext
  ├── ActorContext
  ├── OrganisationGovernanceContext
  ├── CommitteeStructureEpochContext
  ├── LineageContext
  └── TargetGeography
```

✔ This prevents semantic collapse.

---

## ❌ Issue 3 — Actor model is under-specified (dangerous)

Current model:

* OWNER
* ADMIN
* STEWARD
* MEMBER

### Problem:

This ignores **institutional reality already encoded in your system**:

You already have:

```
owner (100)
admin (80)
commission (60)
voter (40)
member (20)
```

👉 Plan introduces a **parallel identity system**

---

### Fix (critical)

Do NOT redefine roles.

Instead:

```php
enum ActorPosition {
    case OWNER;
    case ADMIN;
    case COMMISSION;
    case VOTER;
    case MEMBER;
}
```

Then derive:

```php
public function hasGovernanceAuthority(): bool
{
    return in_array($this, [
        OWNER,
        ADMIN,
        COMMISSION
    ]);
}
```

✔ No dual role systems.

---

## ❌ Issue 4 — Geography model is oversimplified (risk for GEO-2 failure)

### Problem:

Current model:

* string prefix matching
* hierarchical level comparison

### Why this will break later:

You will need:

* overlapping jurisdictions
* non-tree geography (district belongs to multiple regions)
* exceptions (special zones)

---

### Fix (minimal correction)

Replace:

```php
str_starts_with()
```

with:

```php
GeographicScopeRelationService
```

Even if implementation is simple now:

```text
Domain rule interface, not string logic
```

✔ Prevents future rewrite in GEO-2.

---

## ❌ Issue 5 — LineageContext mixes hierarchy + identity

Problem:

```
CommitteeLineageContext
```

contains:

* committeeId
* parentCommitteeId
* depth

### Issue:

This is not “context”, this is **domain aggregate projection**

---

### Fix:

Rename:

```
CommitteeLineageContext → CommitteeLineageView
```

or:

```
CommitteeHierarchyState
```

✔ Avoids conceptual misuse of “context”

---

## ❌ Issue 6 — CapabilityContext is becoming a God Object

It contains:

* actor
* epoch
* lineage
* geography
* helper methods

### Risk:

This will become:

> “authorization dump object”

---

### Fix:

Make it **pure data carrier only**

Remove:

* `isGeographyValid()`
* `getOrganisationId()`

Move logic to:

```
CapabilityEvaluationService
```

✔ Keeps model clean and testable

---

## ❌ Issue 7 — Policy engine is too large (violates SRP)

Current methods:

* create committee
* activate structure
* modify committee
* deprecate structure

### Problem:

This is already a mini “authorization monolith”.

---

### Fix (recommended split)

Split into 4 policies:

```
CommitteeCreationPolicy
StructureActivationPolicy
CommitteeModificationPolicy
StructureDeprecationPolicy
```

Then compose:

```
GovernanceCapabilityPolicyEngine
   → delegates to domain policies
```

✔ Prevents Phase B from becoming unmaintainable

---

# 4. Missing Elements (Important Gaps)

## ❗ Missing: Context Factory Layer

You need:

```
GovernanceCapabilityContextFactory
```

Responsible for:

* loading Organisation
* loading roles
* mapping actor
* building geography
* building epoch context

👉 This is CRITICAL for testability.

---

## ❗ Missing: Authorization boundary contract test

You only defined unit tests.

You still need:

```
Integration boundary tests:

- HTTP controller blocked
- CLI blocked
- Queue context allowed
```

---

## ❗ Missing: explicit "system actor"

You mention:

> "when no authenticated user (console/queue context)"

But no explicit model exists.

Add:

```
ActorContext::system()
```

---

# 5. Architectural Risk Summary

| Risk                                  | Severity      | Status |
| ------------------------------------- | ------------- | ------ |
| Dual role system mismatch             | HIGH          | ❌      |
| Implicit DB dependency in policy path | HIGH          | ❌      |
| Geography logic too naive             | MEDIUM        | ⚠️     |
| CapabilityContext overreach           | MEDIUM        | ⚠️     |
| Policy monolith growth                | HIGH (future) | ⚠️     |
| Missing context factory               | CRITICAL      | ❌      |

---

# 6. Recommended Phase B v2 Adjustment Strategy

## Do NOT proceed as-is.

Instead apply:

### Step 1 (mandatory refactor)

* Add `GovernanceCapabilityContextFactory`
* Remove DB calls from policy layer path

### Step 2 (model correction)

* Align ActorPosition with existing role system
* Split epoch vs organisation governance context

### Step 3 (design simplification)

* Make CapabilityContext pure DTO
* Remove logic methods

### Step 4 (decompose policy engine)

* Split into 4 domain policies

---

# 7. Final Verdict

### ❗ Current plan status

| Category                  | Status                          |
| ------------------------- | ------------------------------- |
| Conceptual direction      | ✅ Strong                        |
| Domain modeling           | ⚠️ Overcomplex                  |
| Implementation readiness  | ❌ Not safe yet                  |
| Testability               | ⚠️ Partial                      |
| Long-term maintainability | ⚠️ Risk of monolith auth engine |

---

# 8. If you want the correct next step

Below is a **clean, implementation-safe Phase B v2.1 model**. It removes hidden coupling, fixes bounded-context violations, aligns with your existing system, and is structured so you can implement it **without architectural rewrites later**.

---

# Phase B v2.1 — Safe Governance Capability Model

## 🎯 Design Goal

Introduce **institutional capability enforcement** without:

* duplicating role systems
* leaking DB access into domain logic
* overloading context objects
* mixing Organisation vs Committee semantics

---

# 1. Correct Domain Boundaries

## 1.1 Domain Structure (Final)

```
Domain/
└── Committee/
    ├── Actor/
    │   └── ActorPosition.php
    │
    ├── Context/
    │   ├── ActorContext.php
    │   ├── GeographicScope.php
    │   ├── OrganisationGovernanceContext.php
    │   ├── CommitteeStructureEpochContext.php
    │   └── CommitteeLineageView.php
    │
    ├── Capability/
    │   ├── CapabilityContext.php
    │   ├── CapabilityEvaluation.php
    │   ├── InstitutionalCapabilityPolicy.php
    │   ├── CommitteeCreationPolicy.php
    │   ├── StructureActivationPolicy.php
    │   ├── CommitteeModificationPolicy.php
    │   └── StructureDeprecationPolicy.php
    │
    ├── Exceptions/
    │
    └── Services/
        └── GeographicScopeRelationService.php
```

---

# 2. Core Design Rules (Non-Negotiable)

## Rule A — Domain is DB-agnostic

❌ No repositories in domain
❌ No Organisation::find() in policies
✔ Only pure objects + services

---

## Rule B — Context is data only

❌ No logic inside CapabilityContext
✔ Only immutable composition object

---

## Rule C — All DB access happens BEFORE domain policy

✔ Application layer builds full context

---

## Rule D — Policy engine is a coordinator, not a god object

✔ Delegates to smaller policies

---

# 3. Actor Model (Aligned With Existing System)

## ActorPosition (NO duplication of system roles)

```php
enum ActorPosition: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case COMMISSION = 'commission';
    case VOTER = 'voter';
    case MEMBER = 'member';

    public function hasGovernanceAuthority(): bool
    {
        return in_array($this, [
            self::OWNER,
            self::ADMIN,
            self::COMMISSION,
        ]);
    }
}
```

---

# 4. Context Layer (Safe Version)

## 4.1 ActorContext

```php
final readonly class ActorContext
{
    public function __construct(
        public string $userId,
        public TenantId $tenantId,
        public ActorPosition $position,
        public GeographicScope $geographicScope,
        public ?string $committeeId = null,
        public bool $isSystemActor = false,
    ) {}

    public function isOwner(): bool
    {
        return $this->position === ActorPosition::OWNER;
    }

    public function hasGovernanceAuthority(): bool
    {
        return $this->position->hasGovernanceAuthority();
    }

    public function canOperateIn(GeographicScope $scope): bool
    {
        return GeographicScopeRelationService::canOperate(
            $this->geographicScope,
            $scope
        );
    }
}
```

---

## 4.2 OrganisationGovernanceContext (FIXED BOUNDARY)

```php
final readonly class OrganisationGovernanceContext
{
    public function __construct(
        public string $organisationId,
        public string $governanceStatus, // ACTIVE | PENDING | CONFIGURED | INACTIVE
    ) {}

    public function isActive(): bool
    {
        return $this->governanceStatus === 'ACTIVE';
    }
}
```

✔ This replaces direct Organisation dependency inside domain

---

## 4.3 CommitteeStructureEpochContext (separated from org)

```php
final readonly class CommitteeStructureEpochContext
{
    public function __construct(
        public int $version,
        public string $status, // ACTIVE | DEPRECATED | DRAFT
        public int $depth,
        public string $levelCode,
        public ?string $parentStructureId = null,
        public bool $isLatestActive = false,
    ) {}

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function canEvolve(): bool
    {
        return $this->isActive();
    }
}
```

---

## 4.4 CommitteeLineageView (renamed, correct semantics)

```php
final readonly class CommitteeLineageView
{
    public function __construct(
        public string $committeeId,
        public ?string $parentCommitteeId,
        public int $depth
    ) {}

    public static function root(string $committeeId): self
    {
        return new self($committeeId, null, 0);
    }

    public function isRoot(): bool
    {
        return $this->depth === 0;
    }
}
```

---

# 5. Capability Layer (Clean Split Design)

## 5.1 CapabilityContext (PURE DTO ONLY)

```php
final readonly class CapabilityContext
{
    public function __construct(
        public ActorContext $actor,
        public OrganisationGovernanceContext $organisation,
        public CommitteeStructureEpochContext $epoch,
        public CommitteeLineageView $lineage,
        public ?GeographicScope $targetScope = null,
    ) {}
}
```

❌ No helper methods
✔ Only composition

---

## 5.2 Evaluation Object

```php
final readonly class CapabilityEvaluation
{
    public function __construct(
        public bool $allowed,
        public string $reason,
        public array $failedChecks = []
    ) {}

    public static function allow(string $reason): self
    {
        return new self(true, $reason);
    }

    public static function deny(string $reason, array $checks = []): self
    {
        return new self(false, $reason, $checks);
    }

    public function assertAllowed(): void
    {
        if (!$this->allowed) {
            throw new CapabilityDeniedException($this->reason);
        }
    }
}
```

---

# 6. Policy Decomposition (IMPORTANT FIX)

Instead of one monolith engine:

---

## 6.1 Interface

```php
interface InstitutionalCapabilityPolicy
{
    public function canCreateCommittee(CapabilityContext $ctx): CapabilityEvaluation;
    public function canActivateStructure(CapabilityContext $ctx): CapabilityEvaluation;
    public function canModifyCommittee(CapabilityContext $ctx): CapabilityEvaluation;
    public function canDeprecateStructure(CapabilityContext $ctx): CapabilityEvaluation;
}
```

---

## 6.2 Orchestrator (lightweight only)

```php
final class GovernanceCapabilityPolicyEngine implements InstitutionalCapabilityPolicy
{
    public function __construct(
        private CommitteeCreationPolicy $creation,
        private StructureActivationPolicy $activation,
        private CommitteeModificationPolicy $modification,
        private StructureDeprecationPolicy $deprecation,
    ) {}

    public function canCreateCommittee(CapabilityContext $ctx): CapabilityEvaluation
    {
        return $this->creation->evaluate($ctx);
    }

    public function canActivateStructure(CapabilityContext $ctx): CapabilityEvaluation
    {
        return $this->activation->evaluate($ctx);
    }

    public function canModifyCommittee(CapabilityContext $ctx): CapabilityEvaluation
    {
        return $this->modification->evaluate($ctx);
    }

    public function canDeprecateStructure(CapabilityContext $ctx): CapabilityEvaluation
    {
        return $this->deprecation->evaluate($ctx);
    }
}
```

✔ This prevents “authorization monolith collapse”

---

# 7. Domain Policies (Example: Creation)

## 7.1 CommitteeCreationPolicy

```php
final class CommitteeCreationPolicy
{
    public function evaluate(CapabilityContext $ctx): CapabilityEvaluation
    {
        if (!$ctx->organisation->isActive()) {
            return CapabilityEvaluation::deny(
                'Organisation governance is not ACTIVE',
                ['org_inactive']
            );
        }

        if (!$ctx->actor->hasGovernanceAuthority()) {
            return CapabilityEvaluation::deny(
                'Actor lacks governance authority',
                ['no_authority']
            );
        }

        if ($ctx->targetScope &&
            !$ctx->actor->canOperateIn($ctx->targetScope)) {
            return CapabilityEvaluation::deny(
                'Geographic scope violation',
                ['geo_violation']
            );
        }

        if (!$ctx->epoch->isActive()) {
            return CapabilityEvaluation::deny(
                'Governance epoch is not active',
                ['epoch_inactive']
            );
        }

        return CapabilityEvaluation::allow('Committee creation permitted');
    }
}
```

---

# 8. Geographic Service (Fixes string logic issue)

```php
final class GeographicScopeRelationService
{
    public static function canOperate(
        GeographicScope $actor,
        GeographicScope $target
    ): bool {
        // Phase 1 simplified rule (safe)
        if ($actor->level === 'national') {
            return true;
        }

        return $actor->level === $target->level
            && $actor->code === $target->code;
    }
}
```

✔ No string prefix hacks anymore
✔ Ready for GEO-2 extension

---

# 9. Application Layer (Correct Responsibility Split)

## IMPORTANT: This is where DB belongs

### GovernanceCapabilityContextFactory

```php
final class GovernanceCapabilityContextFactory
{
    public function build(
        string $userId,
        TenantId $tenantId,
        ?GeographicScope $targetScope = null
    ): CapabilityContext {
```

### Responsibilities:

* Load Organisation
* Load User role
* Map ActorPosition
* Build epoch context from Organisation
* Build lineage view (root initially)
* Return CapabilityContext

❌ NO policy logic here
✔ ONLY data assembly

---

# 10. Application Policy Adapter

```php
final class GovernanceCapabilityPolicy implements GovernanceAccessPolicyInterface
{
    public function __construct(
        private GovernanceCapabilityContextFactory $factory,
        private InstitutionalCapabilityPolicy $policyEngine
    ) {}

    public function assertCanCreateCommittee(TenantId $tenantId): void
    {
        $ctx = $this->factory->build(
            auth()->id() ?? 'system',
            $tenantId
        );

        $this->policyEngine
            ->canCreateCommittee($ctx)
            ->assertAllowed();
    }
}
```

---

# 11. Key Fixes Compared to Original Plan

| Problem in old plan           | Fixed in v2.1      |
| ----------------------------- | ------------------ |
| Policy engine depended on DB  | ❌ removed          |
| CapabilityContext had logic   | ❌ removed          |
| Actor system duplicated roles | ❌ aligned          |
| Organisation vs Epoch mixed   | ❌ separated        |
| Single monolith policy engine | ❌ split policies   |
| Geography string logic        | ❌ replaced service |
| Missing context factory       | ✅ added            |
| DB access in domain layer     | ❌ eliminated       |

---

# 12. Implementation Safety Guarantees

This version ensures:

### ✔ No circular dependency risk

### ✔ Fully testable without DB (domain tests)

### ✔ No hidden repository leakage into domain

### ✔ Clear layering:

```
Controller
  → Application Policy Adapter
    → Context Factory (DB)
      → Pure Policy Engine
        → Domain Policies
```

---

# 13. What This Enables Next

Once implemented, you unlock:

* Phase B GEO-2 (regional enforcement)
* Phase M (membership governance rules)
* Committee hierarchy permissions
* Temporal + spatial governance consistency
* Event-driven governance decisions

---

# 14. Final Verdict

### This version is:

✔ architecturally consistent
✔ testable
✔ DDD-correct
✔ safe for production implementation
✔ extensible without rewrite

---

now implement it 