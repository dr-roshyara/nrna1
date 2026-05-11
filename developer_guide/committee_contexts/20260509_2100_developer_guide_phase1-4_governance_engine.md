# NRNA Constitutional Governance Engine — Developer Guide
## Phases 1–4 Complete | 229 Tests | 445 Assertions | Architecture Fitness 10/10

---

## Table of Contents

1. [What This Is](#1-what-this-is)
2. [Architecture Overview](#2-architecture-overview)
3. [Bounded Context Map](#3-bounded-context-map)
4. [Phase 1 — Committee Aggregate](#4-phase-1--committee-aggregate)
5. [Phase 2 — Governance Decision](#5-phase-2--governance-decision)
6. [Phase 3 — Authority Assignment](#6-phase-3--authority-assignment)
7. [Phase 4 — Approval Workflow Saga](#7-phase-4--approval-workflow-saga)
8. [Shared Kernel](#8-shared-kernel)
9. [Architecture Fitness Rules](#9-architecture-fitness-rules)
10. [Testing Strategy](#10-testing-strategy)
11. [Running Tests](#11-running-tests)
12. [Adding New Capabilities](#12-adding-new-capabilities)
13. [Formal Invariant Catalog](#13-formal-invariant-catalog)
14. [Architectural Debt Register](#14-architectural-debt-register)

---

## 1. What This Is

This is a **constitutional governance engine** — not a committee management CRUD system. Every governance decision is:

- **Replayable** — decisions carry their full authority chain at the time of adjudication
- **Auditable** — every state transition emits an immutable domain event
- **Constitutionally-grounded** — each decision references the article that authorized it
- **Termination-aware** — lifecycle states are explicitly terminal (cannot be undone by code)

The system is built using **Event-Driven DDD** (not Event-Sourced). Domain events are emitted and may be dispatched to listeners, but there is no event store and no aggregate replay from events.

```
Current status (2026-05-09):
  Phase 1 ✅  Committee Aggregate + Lifecycle + Terms
  Phase 2 ✅  GovernanceDecision (immutable append-only record)
  Phase 3 ✅  AuthorityAssignment + Graph cycle detection
  Phase 4 ✅  Approval Workflow Saga (Process Manager pattern)

  Total tests:      229 passing
  Total assertions: 445
  Architecture fitness: 10/10 layers green
```

---

## 2. Architecture Overview

### Execution order (non-negotiable)

```
DOMAIN ENTITY
  → DOMAIN TESTS (RED first)
  → POLICIES (pure, no infra)
  → DOMAIN SERVICES (stateless, repo as method param)
  → APPLICATION LAYER (via repository ports)
  → INFRASTRUCTURE ADAPTERS (Eloquent, DB)
  → PROJECTIONS (read-only, denormalized)
  → UI (renders projections only)
```

### Layer rules

| Layer | Laravel | Eloquent | Facades | DTOs |
|-------|---------|----------|---------|------|
| Domain | ❌ Forbidden | ❌ Forbidden | ❌ Forbidden | Pure PHP |
| Application | ⚠️ Limited | ❌ Forbidden | ❌ Forbidden | Required |
| Infrastructure | ✅ Allowed | ✅ Allowed | ✅ Allowed | Optional |

### Folder structure

```
app/
├── Contexts/
│   ├── Shared/
│   │   └── Domain/ValueObjects/
│   │       └── TenantId.php             # Shared kernel VO (org isolation boundary)
│   │
│   ├── Membership/
│   │   └── Domain/Committee/            # Phase 1
│   │       ├── CommitteeAggregate.php
│   │       ├── ValueObjects/            # CommitteeId, MemberId, TermPeriod, ...
│   │       ├── Policies/                # CommitteeHierarchyPolicy
│   │       └── Events/                  # CommitteeCreated, CommitteeLifecycleChanged, ...
│   │
│   └── Governance/
│       ├── Domain/
│       │   ├── GovernanceDecision.php   # Phase 2
│       │   ├── ValueObjects/            # GovernanceDecisionId, ConstitutionalBasis, ...
│       │   ├── Events/                  # GovernanceDecisionRecorded
│       │   ├── Authority/               # Phase 3
│       │   │   ├── AuthorityAssignment.php
│       │   │   ├── AuthorityResolver.php
│       │   │   ├── AuthorityAssignmentRepository.php
│       │   │   ├── ValueObjects/        # DelegationType, DelegationStatus, ...
│       │   │   ├── Events/              # AuthorityDelegated, AuthorityRevoked
│       │   │   └── Policies/            # DelegationLifecyclePolicy
│       │   ├── Approval/                # Phase 4
│       │   │   ├── ValueObjects/        # ApprovalStatus, ApprovalId, IdempotencyKey
│       │   │   ├── Events/              # ApprovalRequested, ApprovalGranted, ApprovalRejected
│       │   │   └── Policies/            # ApprovalRequirementPolicy
│       │   └── Replay/
│       │       └── GovernanceStateReconstructionService.php  # Deferred (Phase 6)
│       │
│       └── Application/
│           ├── Authority/
│           │   └── AuthorityGraphValidator.php   # Phase 3 (app service)
│           ├── Approval/                          # Phase 4
│           │   ├── ApprovalProcessState.php
│           │   ├── ApprovalProcessRepository.php
│           │   ├── GovernanceApprovalProcessManager.php
│           │   └── Commands/
│           │       ├── StartApprovalProcess.php
│           │       ├── FinalizeDecision.php
│           │       └── AbortDecision.php
│           └── Contracts/
│               └── CommandBusInterface.php

tests/Unit/Contexts/Governance/
├── Domain/
│   ├── ValueObjects/          # AuthorityChain, ConstitutionalBasis, DecisionTrace
│   ├── Events/                # GovernanceDecisionRecorded
│   ├── GovernanceDecisionTest.php
│   ├── Authority/             # ValueObjects, Events, Policies, Aggregate tests
│   └── Approval/              # ValueObjects, Events, Policies tests
└── Application/
    ├── Authority/             # AuthorityGraphValidator tests
    └── Approval/              # ApprovalProcessState, ProcessManager, Commands tests
```

---

## 3. Bounded Context Map

```
┌─────────────────────────────────────────────────────────────────┐
│  Membership Context (upstream)                                   │
│  ├── CommitteeAggregate (lifecycle, term, hierarchy)             │
│  ├── CommitteeId, MemberId (shared reference VOs)               │
│  └── CommitteeHierarchyPolicy (structural parentage)            │
└─────────────────────────────────────────────────────────────────┘
         ↑ consumed by
┌─────────────────────────────────────────────────────────────────┐
│  Governance Context (downstream)                                 │
│  ├── GovernanceDecision (constitutional memory)                  │
│  ├── AuthorityAssignment (delegation graph)                      │
│  └── ApprovalWorkflow (process manager / Saga)                   │
└─────────────────────────────────────────────────────────────────┘
         ↑ anti-corruption layer (ACL)
┌─────────────────────────────────────────────────────────────────┐
│  Geography Context (upstream)                                    │
│  └── GeoAdministrativeUnit, Country                              │
└─────────────────────────────────────────────────────────────────┘
```

**Critical distinction:**

| Concept | Meaning | Implementation |
|---------|---------|----------------|
| Structural hierarchy | Organizational containment | `parent_committee_id` on Committee |
| Authority hierarchy | Constitutional delegation | `AuthorityAssignment` aggregate |

These are **independent**. Never use `parent_committee_id` to infer authority.

---

## 4. Phase 1 — Committee Aggregate

**Location:** `app/Contexts/Membership/Domain/Committee/`

### What it is

The `CommitteeAggregate` (also referred to as `Committee`) is the core aggregate capturing the lifecycle of an organizational committee. It is **non-anemic** — all state transitions go through behavior methods that enforce invariants and emit domain events.

### State taxonomy

There are **three independent enums** — they never overlap:

```php
// Operational state (structural)
enum StructuralOperationalState: string
{
    case ACTIVE    = 'ACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case DISSOLVED = 'DISSOLVED';  // TERMINAL — cannot be restored
}

// Term state (temporal)
enum TemporalGovernanceState: string
{
    case VALID     = 'VALID';
    case EXPIRING  = 'EXPIRING';   // < 30 days until term end
    case EXPIRED   = 'EXPIRED';
    case CARETAKER = 'CARETAKER';  // post-expiry, no renewal
    case NO_TERM   = 'NO_TERM';
}

// Constitutional legitimacy
enum ConstitutionalLegitimacy: string
{
    case LEGITIMATE   = 'LEGITIMATE';
    case REVOKED      = 'REVOKED';
    case DISPUTED     = 'DISPUTED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
}
```

### Key value objects

| Class | Location | Purpose |
|-------|----------|---------|
| `CommitteeId` | `ValueObjects/CommitteeId.php` | UUID identity — `generate()`, `fromString()` |
| `MemberId` | `ValueObjects/MemberId.php` | Actor identity — `from(string)` |
| `TermPeriod` | `ValueObjects/TermPeriod.php` | Bounded start+end date range |
| `EffectivePeriod` | `ValueObjects/EffectivePeriod.php` | Open-ended OR bounded validity |
| `CommitteeFacts` | `ValueObjects/CommitteeFacts.php` | Readonly data snapshot passed to policies |

### Lifecycle behaviors

```php
// All methods enforce invariants and emit domain events

$committee->attachToParent(CommitteeId $parentId): void
// INV-05: cannot attach to self
// INV-06: cannot create a hierarchy cycle
// Emits: CommitteeParentAttached

$committee->startTerm(TermPeriod $period): void
// INV-03: end must be after start (validated in TermPeriod constructor)
// Emits: CommitteeTermUpdated

$committee->extendTerm(TermPeriod $newPeriod): void
// INV-04: new start must not precede current term start
// Emits: CommitteeTermUpdated

$committee->suspend(MemberId $actor, string $reason): void
// INV-02: cannot suspend a DISSOLVED committee
// Emits: CommitteeLifecycleChanged

$committee->restore(MemberId $actor): void
// INV-01: cannot restore a DISSOLVED committee (terminal state)
// Emits: CommitteeLifecycleChanged

$committee->dissolve(MemberId $actor, string $reason): void
// No additional guard — dissolution is always allowed from ACTIVE or SUSPENDED
// Emits: CommitteeLifecycleChanged
// NOTE: DISSOLVED is terminal. Reinstatement requires a NEW committee aggregate.
```

### Hierarchy validation

Cycle detection is handled by `CommitteeHierarchyPolicy` (domain service), NOT by the aggregate itself:

```php
// Application layer loads ancestors first, then passes them to the policy
$ancestors = $committeeRepository->findAncestors($committeeId);
$policy = new CommitteeHierarchyPolicy();
$policy->assertCanAttach($childId, $parentId, $ancestors);  // throws DomainException on cycle

// After policy passes, call the aggregate
$committee->attachToParent($parentId);
```

The aggregate does NOT query the repository. The application layer resolves the facts, the policy enforces the invariant, the aggregate records the event.

### Domain events emitted

| Event | When |
|-------|------|
| `CommitteeCreated` | On creation |
| `CommitteeParentAttached` | On `attachToParent()` |
| `CommitteeLifecycleChanged` | On suspend / restore / dissolve |
| `CommitteeTermUpdated` | On startTerm / extendTerm |

---

## 5. Phase 2 — Governance Decision

**Location:** `app/Contexts/Governance/Domain/GovernanceDecision.php`

### What it is

`GovernanceDecision` is an **immutable, append-only** record. Once created, it cannot be modified. It is the constitutional memory of the system — every governance action that was authorized leaves a `GovernanceDecision` record.

```php
final readonly class GovernanceDecision
{
    // Constructor is private — use GovernanceDecision::record()
    public static function record(
        GovernanceDecisionId $id,
        CommitteeId $committeeId,
        string $capabilityType,          // e.g. 'COMMITTEE_FORMATION'
        Legitimacy $legitimacy,
        AuthorityChain $authorityChain,  // snapshot of who authorized this
        ConstitutionalBasis $basis,      // which article authorized this
        DecisionTrace $trace,            // forensic audit record
        DateTimeImmutable $effectiveFrom,
        DateTimeImmutable $decidedAt,
        DateTimeImmutable $now,
    ): self
}
```

**Critical:** `effectiveFrom` must not be before `decidedAt`. The record is immutable from the moment of creation (INV-07).

### Supporting value objects

| Class | Purpose | Key rules |
|-------|---------|-----------|
| `GovernanceDecisionId` | UUID identity — `generate()`, `from()` | |
| `ConstitutionalBasis` | Article + optional reference text | Article cannot be empty |
| `AuthorityChain` | Snapshot: actor, role, delegation path, captured timestamp | Path must start with 'ICC' |
| `AuthorityPath` | Ordered delegation chain as string array | Non-empty; first element = 'ICC' |
| `GovernanceRole` | `ICC_PRESIDENT(4)`, `CONTINENT_PRESIDENT(3)`, `COUNTRY_PRESIDENT(2)`, `CHAPTER_PRESIDENT(1)` | `level()` returns numeric hierarchy |
| `DecisionTrace` | Full audit: rules evaluated, clauses matched, rejected constraints, authority path, adjudicator | |
| `Legitimacy` | `LEGITIMATE`, `REVOKED`, `DISPUTED`, `UNAUTHORIZED` | |

### Building an AuthorityChain

```php
$chain = AuthorityChain::capture(
    actorId: MemberId::from('icc-president-uuid'),
    role: GovernanceRole::ICC_PRESIDENT,
    delegationPath: AuthorityPath::fromArray(['ICC']),
    capturedAt: new DateTimeImmutable(),
);
```

### Repository contract

```php
// Append-only — no update method
interface GovernanceDecisionRepository
{
    public function append(GovernanceDecision $decision): void;
    public function findById(GovernanceDecisionId $id): ?GovernanceDecision;
    public function findByCommittee(CommitteeId $id): array;
}
```

### Domain event

`GovernanceDecisionRecorded` is emitted whenever a decision is recorded. It carries the full decision details and is the primary event consumed by downstream projections.

---

## 6. Phase 3 — Authority Assignment

**Location:** `app/Contexts/Governance/Domain/Authority/`

### What it is

`AuthorityAssignment` is the aggregate modeling constitutional authority delegation between committees. This is **not** a parent-child structural relationship — it is an explicit constitutional act transferring governing power.

**Key distinction:**

```
parent_committee_id  →  structural containment (where you are in the org chart)
AuthorityAssignment  →  constitutional authority (who has the power to act)
```

A committee with no parent can still have authority. A committee with a parent may have its authority delegated away.

### Lifecycle

```
ACTIVE ──────────────────► REVOKED (terminal)
```

REVOKED is terminal. A revoked authority assignment cannot be reinstated. A new assignment must be created.

### Delegation types (priority ordering)

| Type | Value | Priority | Use case |
|------|-------|----------|---------|
| `AUTHORITY` | `'AUTHORITY'` | 80 | Standard full authority grant |
| `OVERRIDE` | `'OVERRIDE'` | 70 | Temporary override of existing authority |
| `TEMPORARY` | `'TEMPORARY'` | 60 | Time-bounded delegation |

When multiple active assignments exist for the same committee, `AuthorityResolver` selects the highest-priority one. Equal priorities resolve lexicographically by `AuthorityAssignmentId`.

### Creating a delegation

```php
$assignment = AuthorityAssignment::delegate(
    id: AuthorityAssignmentId::generate(),
    tenantId: TenantId::fromOrganisationId($organisationId),  // AD-09: tenancy boundary
    fromCommitteeId: CommitteeId::fromString('icc-committee-uuid'),
    toCommitteeId: CommitteeId::fromString('europe-committee-uuid'),
    type: DelegationType::AUTHORITY,
    scope: DelegationScope::from('COMMITTEE_FORMATION'),
    validity: AuthorityEffectivePeriod::bounded(
        new DateTimeImmutable('2026-01-01'),
        new DateTimeImmutable('2026-12-31'),
    ),
    delegatedBy: MemberId::from('actor-uuid'),
    delegatedAt: new DateTimeImmutable('2026-01-01'),
    now: new DateTimeImmutable(),           // injected clock — never call now() inside domain
    delegatorHasActiveAuthority: true,      // app layer resolves this, aggregate enforces INV-A04
);

// Retrieve and dispatch domain events
foreach ($assignment->releaseEvents() as $event) {
    $eventBus->dispatch($event);  // dispatch AFTER transaction commit, never before
}
```

### Formal invariants enforced on delegate()

| ID | Rule |
|----|------|
| INV-A01 | `fromCommitteeId` ≠ `toCommitteeId` (no self-delegation) |
| INV-A04 | `OVERRIDE` type requires `$delegatorHasActiveAuthority === true` |
| INV-A05 | `delegatedAt` must not exceed injected `$now` (no future-dated delegations) |
| INV-A07 | `DelegationScope` cannot be empty or whitespace |

### Revoking

```php
$assignment->revoke(
    revokedBy: MemberId::from('actor-uuid'),
    reason: 'Constitutional amendment 2026-A',
    revokedAt: new DateTimeImmutable(),
);

// DelegationLifecyclePolicy enforces INV-A02: REVOKED → anything throws
```

### Effective period types

```php
// Open-ended: starts on $date, never expires
$period = AuthorityEffectivePeriod::openEnded(new DateTimeImmutable('2026-01-01'));

// Bounded: INV-A06 enforced — end must be strictly after start
$period = AuthorityEffectivePeriod::bounded(
    new DateTimeImmutable('2026-01-01'),
    new DateTimeImmutable('2026-12-31'),
);

// Check if active at a specific point in time
$isActive = $period->isActive(new DateTimeImmutable('2026-06-15'));  // true
```

### Resolving active authority

`AuthorityResolver` is a **stateless domain service**. The repository is injected as a method parameter (not constructor) to keep the service pure:

```php
$resolver = new AuthorityResolver();
$active = $resolver->resolveActiveAt(
    committeeId: $committeeId,
    at: new DateTimeImmutable(),
    repo: $authorityAssignmentRepository,
);

if ($active === null) {
    // No active authority for this committee at this time
}
```

### Cycle detection (critical — read carefully)

Circular authority graphs are prevented by `AuthorityGraphValidator` (application service). This check **must** run inside a `SERIALIZABLE` transaction:

```php
// In application layer / use case:
DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
DB::transaction(function () use ($fromId, $toId, $repo, $assignment) {

    // 1. Check for cycles INSIDE the transaction
    $validator = new AuthorityGraphValidator();
    $validator->assertNoCycle($fromId, $toId, $repo);  // throws DomainException on cycle

    // 2. Persist INSIDE the same transaction
    $repo->append($assignment);
});

// 3. Dispatch events AFTER commit
foreach ($assignment->releaseEvents() as $event) {
    $eventBus->dispatch($event);
}
```

**Why SERIALIZABLE?** Without this isolation level, two concurrent delegations (A→B and B→A) can both pass the cycle check independently and both be committed — creating a cycle that neither detected. `SERIALIZABLE` prevents this by serializing the two transactions.

### DelegationLifecyclePolicy

Lifecycle transition rules live in a **policy**, not on the enum:

```php
$policy = new DelegationLifecyclePolicy();
$canTransition = $policy->canTransition(DelegationStatus::ACTIVE, DelegationStatus::REVOKED); // true
$canTransition = $policy->canTransition(DelegationStatus::REVOKED, DelegationStatus::REVOKED); // false (terminal)
```

The enum itself is pure data — no behavior beyond PHP enum identity comparison.

---

## 7. Phase 4 — Approval Workflow Saga

**Location:** `app/Contexts/Governance/Application/Approval/` + `Domain/Approval/`

### What it is

The approval workflow is implemented as a **Process Manager (Saga)** — not an aggregate. This is the correct pattern for multi-step, multi-committee approval coordination because:

- The workflow spans multiple events across time (not a single transaction)
- Multiple committees may approve independently in any order
- Timeout/expiry handling requires async scheduling
- Idempotency is required (events may be replayed)

### The three-layer separation

```
Domain layer:    Value Objects + Events + Pure Policy (no workflow state)
Application:     ApprovalProcessState + ProcessManager + Commands
Infrastructure:  EloquentApprovalProcessRepository (to be implemented in Phase 5/6)
```

### ApprovalStatus enum

```php
enum ApprovalStatus: string
{
    case PENDING  = 'PENDING';   // isTerminal() → false
    case APPROVED = 'APPROVED';  // isTerminal() → true
    case REJECTED = 'REJECTED';  // isTerminal() → true
    case EXPIRED  = 'EXPIRED';   // isTerminal() → true
}
```

`isTerminal()` is **classification metadata** on the enum — it describes the nature of the state, not a transition rule. This is different from `DelegationLifecyclePolicy`, which encodes transition logic as a separate class.

### ApprovalRequirementPolicy

A pure domain class (no constructor dependencies, no repo access) that maps capability types to required approver strategy types:

```php
$policy = new ApprovalRequirementPolicy();

$policy->requiresApproval('COMMITTEE_FORMATION');      // true
$policy->requiredApproverTypes('COMMITTEE_FORMATION'); // ['PARENT_COMMITTEE']

$policy->requiresApproval('CONSTITUTIONAL_AMENDMENT');      // true
$policy->requiredApproverTypes('CONSTITUTIONAL_AMENDMENT'); // ['ICC_BOARD']

$policy->requiresApproval('AUTHORITY_DELEGATION');          // true
$policy->requiredApproverTypes('AUTHORITY_DELEGATION');     // ['SOURCE_COMMITTEE']

$policy->requiresApproval('ROUTINE_DECISION');   // false
$policy->requiredApproverTypes('ROUTINE_DECISION'); // []
```

The policy returns **strategy type strings** — not actual `CommitteeId` values. Resolving strategy types to real CommitteeIds is the job of `ApprovalRequirementResolver` (application service, has repo access), which is implemented in Phase 5.

### ApprovalProcessState

Immutable snapshot with versioned transitions. Every method returns a **new instance**:

```php
// Start a new process
$state = ApprovalProcessState::start(
    approvalId: ApprovalId::generate(),
    decisionId: GovernanceDecisionId::from('decision-uuid'),
    requiredApprovals: [$committeeA, $committeeB],
    now: new DateTimeImmutable(),
);
// $state->status() → PENDING
// $state->version() → 1

// Record an approval from committee A
$state = $state->addApproval($committeeA, $memberId, new DateTimeImmutable());
// $state->version() → 2
// $state->status() → PENDING (only 1 of 2 received)

// Record an approval from committee B (all received → auto-APPROVED)
$state = $state->addApproval($committeeB, $memberId, new DateTimeImmutable());
// $state->status() → APPROVED
// $state->isFullyApproved() → true

// Reject (terminal — any further transition throws DomainException)
$state = $state->reject($memberId, 'Quorum not met', new DateTimeImmutable());

// Expire (terminal)
$state = $state->expire(new DateTimeImmutable());
```

**Invariants enforced:**

| ID | Rule |
|----|------|
| INV-P01 | Terminal state cannot transition further (throws `DomainException`) |
| INV-P02 | Same committee cannot approve twice (throws `DomainException`) |
| INV-P03 | Rejection reason cannot be empty or whitespace |
| INV-P05 | `APPROVED` only when ALL required committees have approved |

### GovernanceApprovalProcessManager (the Saga)

The Process Manager is **stateless** — it holds no workflow state itself. All state lives in `ApprovalProcessState` persisted by `ApprovalProcessRepository`.

```php
$manager = new GovernanceApprovalProcessManager(
    repository: $approvalProcessRepository,
    commandBus: $commandBus,
    policy: new ApprovalRequirementPolicy(),
);

// Dispatch any domain event to the manager
$manager->handle($event, new DateTimeImmutable());

// Schedule this to run periodically (e.g., Laravel scheduler)
$manager->expireOldProcesses(new DateTimeImmutable(), $cutoffDate);
```

**Event handling logic:**

```
GovernanceDecisionRecorded received:
  → policy->requiresApproval($capabilityType)?
      → NO:  return (ignore)
      → YES: check idempotency (already started for this decisionId?)
          → YES: return (ignore — duplicate event)
          → NO:  create ApprovalProcessState, save, dispatch StartApprovalProcess command

ApprovalGranted received:
  → load state by approvalId
  → state == null OR state->status->isTerminal()? → return (ignore)
  → state->addApproval() → save updated state
  → state->isFullyApproved()? → dispatch FinalizeDecision command

ApprovalRejected received:
  → load state by approvalId
  → state == null OR state->status->isTerminal()? → return (ignore)
  → state->reject() → save updated state
  → dispatch AbortDecision command
```

### Commands (immutable DTOs)

Commands are intent — they instruct application handlers what to do next. They carry no logic.

```php
// Initiate the approval process externally
new StartApprovalProcess(
    approvalId: $approvalId,
    decisionId: $decisionId,
    requiredApprovals: $committeeIds,
    idempotencyKey: $key,
);

// Finalize the governance decision (all approvals received)
new FinalizeDecision(decisionId: $decisionId, approvalId: $approvalId);

// Abort the governance decision (rejection or expiry)
new AbortDecision(decisionId: $decisionId, approvalId: $approvalId, reason: 'Quorum not met');
```

### Idempotency

**INV-P04:** The same event must not be processed twice. The Process Manager checks for existing state by `decisionId` before creating a new process:

```php
if ($this->repository->findByDecisionId($event->decisionId()) !== null) {
    return;  // already started — idempotent
}
```

For production deployment, also add a `processed_events` table (idempotency store):

```sql
CREATE TABLE processed_events (
    event_id UUID PRIMARY KEY,
    saga_id  UUID NOT NULL,
    event_type VARCHAR(255),
    processed_at TIMESTAMP
);
```

### Optimistic locking

`ApprovalProcessState` carries a `version` field. Infrastructure repositories must use optimistic locking when saving:

```sql
UPDATE approval_processes
SET status = ?, version = ? + 1, ...
WHERE id = ? AND version = ?  -- fails if another transaction modified first
```

### Database tables required

```sql
-- Core saga state
CREATE TABLE approval_processes (
    id UUID PRIMARY KEY,
    governance_decision_id UUID NOT NULL,
    status VARCHAR(20) NOT NULL,
    required_approvals JSON NOT NULL,
    received_approvals JSON NOT NULL,
    rejected_by UUID NULL,
    rejection_reason TEXT NULL,
    version INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (governance_decision_id),
    INDEX (status)
);
```

---

## 8. Shared Kernel

### TenantId

`App\Contexts\Shared\Domain\ValueObjects\TenantId` is the **organisation isolation boundary**. Every aggregate in the Governance context carries a `TenantId`.

```php
// From an organisation ID (most common in this codebase)
$tenantId = TenantId::fromOrganisationId($organisationId);

// From a raw string value
$tenantId = TenantId::fromString('uuid-string');

// Comparison
$tenantId->equals($other);  // true/false
$tenantId->value();          // string
```

`TenantId` is a Shared Kernel type. Its import (`use App\Contexts\Shared\Domain\ValueObjects\TenantId`) is **not** forbidden by architecture fitness tests.

### Clock injection rule

**Never call `now()`, `Carbon::now()`, or `new DateTimeImmutable()` inside domain or policy classes.** Time must always be injected from the application boundary:

```php
// ✅ Correct — inject time at the application boundary
$now = new DateTimeImmutable();
$assignment = AuthorityAssignment::delegate(..., delegatedAt: $now, now: $now, ...);

// ❌ Wrong — hidden clock inside domain
public function delegate(...): self {
    $now = new DateTimeImmutable();  // DO NOT DO THIS
}
```

This rule exists to make historical replay and testing deterministic.

---

## 9. Architecture Fitness Rules

These are enforced by `tests/Architecture/GovernanceDomainPurityTest.php`:

| Test | What it verifies |
|------|-----------------|
| `governance_domain_has_no_framework_dependencies` | No `Illuminate\` or `Laravel\` imports inside `Domain/` |
| `governance_domain_has_no_infrastructure_imports` | No Eloquent, DB, or queue imports in domain |
| `governance_value_objects_are_readonly` | All VO classes are `final readonly` |
| `governance_domain_events_are_readonly_historical_facts` | All event classes are `final readonly` |
| `governance_domain_has_no_public_setters` | No `public function set*()` in domain |
| `governance_decision_aggregate_is_final` | Aggregate is `final` |
| `governance_events_have_no_logic_methods` | Events only have getters (no `calculate*`, `validate*`, etc.) |
| `phase1_committee_domain_does_not_depend_on_governance_legitimacy` | No cross-context domain coupling |
| `governance_domain_classes_are_final` | All domain classes are `final` |
| `governance_domain_uses_no_carbon` | No Carbon in domain |

Run fitness tests:
```bash
php artisan test tests/Architecture/ --no-coverage
```

---

## 10. Testing Strategy

| Layer | Approach | Never do |
|-------|----------|---------|
| Domain aggregates | Unit test per invariant — no infrastructure | Mock Eloquent models |
| Value objects | Valid construction, invalid construction, equality | Use database |
| Domain policies | Inject `DateTimeImmutable $now` — no real clock | Call `Carbon::now()` |
| Domain services | Repository as test double (interface-based) | Inject Eloquent models |
| Application (use cases) | Repository doubles implementing domain interfaces | Use `createMock(EloquentModel::class)` |
| Process Manager | Mock `ApprovalProcessRepository` + `CommandBusInterface` | Use real DB |
| Architecture fitness | File-scan assertions | Skip them "just this once" |

### Repository test doubles

All test doubles must implement domain repository interfaces. The correct pattern:

```php
// In a test:
$repository = $this->createMock(AuthorityAssignmentRepository::class);
$repository->method('findActiveByCommittee')->willReturn([$assignment]);

// NEVER:
$repository = $this->createMock(EloquentAuthorityAssignmentRepository::class); // ❌
```

### Invariant-per-test

One test per invariant, named after the invariant:

```php
public function test_inv_a01_cannot_delegate_to_self(): void { ... }
public function test_inv_a02_double_revoke_throws(): void { ... }
public function test_inv_p01_cannot_approve_after_rejection(): void { ... }
```

---

## 11. Running Tests

```bash
# Phase 3 domain tests only
php artisan test tests/Unit/Contexts/Governance/Domain/Authority/ --no-coverage --stop-on-failure

# Phase 4 domain + application tests
php artisan test tests/Unit/Contexts/Governance/Domain/Approval/ tests/Unit/Contexts/Governance/Application/Approval/ --no-coverage --stop-on-failure

# Architecture fitness (must always be green)
php artisan test tests/Architecture/ --no-coverage

# Full Governance regression
php artisan test tests/Unit/Contexts/Governance/ tests/Architecture/ --no-coverage

# Full suite with Membership
php artisan test tests/Unit/Contexts/Governance/ tests/Unit/Contexts/Membership/ tests/Architecture/ --no-coverage
```

Expected: **229 tests, 445 assertions, all green.**

---

## 12. Adding New Capabilities

### Adding a new capability type (e.g., `ELECTION_AUTHORIZATION`)

**Step 1 — Domain policy** (pure, no infra):

```php
// app/Contexts/Governance/Domain/Approval/Policies/ApprovalRequirementPolicy.php
public function requiredApproverTypes(string $capabilityType): array
{
    return match($capabilityType) {
        'COMMITTEE_FORMATION'      => ['PARENT_COMMITTEE'],
        'CONSTITUTIONAL_AMENDMENT' => ['ICC_BOARD'],
        'AUTHORITY_DELEGATION'     => ['SOURCE_COMMITTEE'],
        'ELECTION_AUTHORIZATION'   => ['ICC_BOARD', 'CONTINENT_PRESIDENT'],  // ← add here
        default                    => [],
    };
}
```

**Step 2 — Test first (RED)**:

```php
public function test_election_authorization_requires_icc_board_and_continent(): void
{
    $policy = new ApprovalRequirementPolicy();
    $this->assertTrue($policy->requiresApproval('ELECTION_AUTHORIZATION'));
    $this->assertSame(
        ['ICC_BOARD', 'CONTINENT_PRESIDENT'],
        $policy->requiredApproverTypes('ELECTION_AUTHORIZATION')
    );
}
```

**Step 3 — No changes needed in Process Manager.** It reads the policy dynamically.

### Adding a new delegation type

1. Add case to `DelegationType` enum with a unique `priority()` value
2. Write test asserting the priority ordering relative to existing types
3. `AuthorityResolver` automatically handles new types via priority sorting

### Adding a new committee lifecycle state

1. Add case to `StructuralOperationalState` enum
2. Add behavior method on `CommitteeAggregate`
3. Write invariant test first (RED), then implement (GREEN)
4. Run architecture fitness tests to confirm no coupling violations

---

## 13. Formal Invariant Catalog

### Committee aggregate (Phase 1)

| ID | Location | Rule |
|----|----------|------|
| INV-01 | `CommitteeAggregate::restore()` | Cannot restore a DISSOLVED committee |
| INV-02 | `CommitteeAggregate::suspend()` | Cannot suspend a DISSOLVED committee |
| INV-03 | `TermPeriod` constructor | Term end must be after term start |
| INV-04 | `CommitteeAggregate::extendTerm()` | Extended term start must not precede current term start |
| INV-05 | `CommitteeHierarchyPolicy` | Committee cannot attach itself as parent |
| INV-06 | `CommitteeHierarchyPolicy` | Attachment must not create a structural cycle |

### Governance Decision (Phase 2)

| ID | Location | Rule |
|----|----------|------|
| INV-07 | `GovernanceDecision` (readonly) | Decision is immutable once created |
| INV-08 | `GovernanceDecision` | Integrity hash derived from canonical field order |

### Authority Assignment (Phase 3)

| ID | Location | Rule |
|----|----------|------|
| INV-A01 | `AuthorityAssignment::delegate()` | `fromCommitteeId` ≠ `toCommitteeId` (no self-delegation) |
| INV-A02 | `DelegationLifecyclePolicy` + aggregate | REVOKED is terminal; double-revoke throws |
| INV-A03 | `AuthorityGraphValidator` + SERIALIZABLE tx | Delegation must not create a cycle |
| INV-A04 | `AuthorityAssignment::delegate()` | OVERRIDE type requires delegator to hold active authority |
| INV-A05 | `AuthorityAssignment::delegate()` | `delegatedAt` must not exceed injected `$now` |
| INV-A06 | `AuthorityEffectivePeriod` constructor | If bounded, `end > start` |
| INV-A07 | `DelegationScope::from()` | Scope cannot be empty or whitespace |

### Approval Workflow (Phase 4)

| ID | Location | Rule |
|----|----------|------|
| INV-P01 | `ApprovalProcessState` | Terminal state cannot transition further |
| INV-P02 | `ApprovalProcessState::addApproval()` | Same committee cannot approve twice |
| INV-P03 | `ApprovalProcessState::reject()` | Rejection reason cannot be empty |
| INV-P04 | `GovernanceApprovalProcessManager` | Each event processed exactly once (idempotency) |
| INV-P05 | `ApprovalProcessState::addApproval()` | APPROVED only when ALL required committees approved |
| INV-P06 | `IdempotencyKey::from()` | Key cannot be empty or whitespace |

---

## 14. Architectural Debt Register

Items raised by external architectural review. Status updated as of 2026-05-09.

| # | Issue | Severity | Status |
|---|-------|----------|--------|
| AD-01 | Committee aggregate approaching 400-line limit — consider extracting `CommitteeLifecyclePolicy`, `CommitteeTermPolicy` as collaborators | High | Open (Phase 1 follow-up) |
| AD-02 | `AuthorityGraphValidator` race condition — requires SERIALIZABLE isolation. Documented in validator PHPDoc and application guide. | Critical | ✅ Addressed in Phase 3 |
| AD-03 | `GovernanceDecision` missing `DecisionCause` VO — WHY was the decision necessary | High | Open (Phase 2 follow-up) |
| AD-04 | Policies will grow in rule complexity — introduce `GovernanceRule` interface before Phase 5 | Medium | Open (Phase 5 prep) |
| AD-05 | `ConstitutionalReplayService` used ES terminology. Renamed to `GovernanceStateReconstructionService` | Medium | ✅ Resolved in Phase 4 |
| AD-06 | Projection versioning semantics undefined — rebuild triggers, invalidation, staleness | Medium | Open (Phase 6) |
| AD-07 | Domain events are CRUD-like — long-term should be semantically richer | Low | Open (future) |
| AD-08 | Approval workflow must not couple to committee services — Saga + CommandBus enforces this | Medium | ✅ Resolved in Phase 4 |
| AD-09 | Multi-tenancy missing from Governance — `TenantId` added to `AuthorityAssignment` and events | Critical | ✅ Resolved in Phase 3 |
| AD-10 | Commands/Queries not structurally separated in Application layer | Low | Open (Phase 5) |

---

## Quick Reference Card

```
Create committee         CommitteeAggregate::create(...)
Suspend committee        $committee->suspend($actor, $reason)
Dissolve committee       $committee->dissolve($actor, $reason)      [TERMINAL]
Record decision          GovernanceDecision::record(...)             [IMMUTABLE]
Delegate authority       AuthorityAssignment::delegate(...)         + SERIALIZABLE tx
Revoke authority         $assignment->revoke($actor, $reason, $now) [TERMINAL]
Resolve authority        $resolver->resolveActiveAt($id, $at, $repo)
Check for cycles         $validator->assertNoCycle($from, $to, $repo)
Start approval process   $manager->handle($decisionRecordedEvent, $now)
Expire old approvals     $manager->expireOldProcesses($now, $cutoff)

Events are released      $aggregate->releaseEvents()
Events are dispatched    AFTER DB::transaction commit — never before
Time is always injected  DateTimeImmutable $now — never Carbon::now() in domain
```
