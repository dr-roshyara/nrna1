# 📚 NRNA Constitutional Governance Platform - Developer Guide

## Version: Phase 1-2 Complete | Phase 3 Architecture Designed

---

## 🎯 What We Have Built

A **production-ready constitutional governance engine** for hierarchical organizations (NRNA, political parties, corporations, NGOs). The system manages committee lifecycles, constitutional decisions, authority delegation, and governance policies - all with immutable audit trails.

```yaml
Status:
  Phase 1: ✅ COMPLETE (Committee Aggregate + Lifecycle)
  Phase 2: ✅ COMPLETE (Constitutional Memory Layer)
  Phase 3: ⏳ DESIGNED (Authority Assignment - tests written)
  
Total Tests: 65+ passing | 170+ assertions
Architecture Fitness: 10/10 layers green
```

---

## 📁 Project Structure

```
app/Contexts/
├── Membership/Domain/Committee/     # Phase 1
│   ├── CommitteeAggregate.php       # Core aggregate (≤400 lines)
│   ├── ValueObjects/
│   │   ├── CommitteeId.php          # UUID identity
│   │   ├── MemberId.php             # Actor identity
│   │   ├── TermPeriod.php           # Bounded date range
│   │   ├── EffectivePeriod.php      # Open-ended validity
│   │   ├── CommitteeFacts.php       # Readonly snapshot
│   │   ├── StructuralOperationalState.php  # ACTIVE/SUSPENDED/DISSOLVED
│   │   ├── TemporalGovernanceState.php     # VALID/EXPIRING/EXPIRED/CARETAKER
│   │   └── ConstitutionalLegitimacy.php    # LEGITIMATE/REVOKED/DISPUTED
│   ├── Policies/
│   │   └── CommitteeHierarchyPolicy.php    # Pure cycle detection
│   └── Events/
│       ├── CommitteeCreated.php
│       ├── CommitteeParentAttached.php
│       ├── CommitteeLifecycleChanged.php   # Unified (suspend/restore/dissolve)
│       └── CommitteeTermUpdated.php        # Unified (start/extend)
│
├── Governance/Domain/               # Phase 2
│   ├── GovernanceDecision.php       # Immutable append-only record
│   ├── ValueObjects/
│   │   ├── GovernanceDecisionId.php # UUID identity
│   │   ├── ConstitutionalBasis.php  # Citation-only (article + reference)
│   │   ├── AuthorityChain.php       # Authority snapshot at decision time
│   │   ├── DecisionTrace.php        # Forensic audit record
│   │   └── Legitimacy.php           # LEGITIMATE/REVOKED/DISPUTED/UNAUTHORIZED
│   └── Events/
│       └── GovernanceDecisionRecorded.php  # Complete projection event
│
├── Governance/Domain/Authority/     # Phase 3 (DESIGNED - tests ready)
│   ├── AuthorityAssignment.php      # Aggregate (to implement)
│   ├── ValueObjects/
│   │   ├── DelegationStatus.php     # ACTIVE/REVOKED
│   │   ├── DelegationType.php       # AUTHORITY(80)/OVERRIDE(70)/TEMPORARY(60)
│   │   ├── DelegationScope.php      # Non-empty string scope
│   │   ├── AuthorityAssignmentId.php # UUID identity
│   │   └── AuthorityEffectivePeriod.php # Open-ended or bounded validity
│   ├── Policies/
│   │   └── DelegationLifecyclePolicy.php # State transition rules
│   ├── Events/
│   │   ├── AuthorityDelegated.php
│   │   └── AuthorityRevoked.php
│   └── AuthorityResolver.php        # Domain service (stateless)
│
├── Governance/Application/Authority/  # Phase 3
│   └── AuthorityGraphValidator.php  # Cycle detection (application service)
│
└── Shared/Domain/ValueObjects/
    └── TenantId.php                 # Multi-tenancy support

tests/
├── Unit/Contexts/Governance/Domain/
│   ├── Committee/                  # Phase 1 tests (34+ passing)
│   ├── ValueObjects/               # Phase 2 VO tests (31 passing)
│   ├── GovernanceDecisionTest.php  # Phase 2 aggregate tests
│   └── Authority/                  # Phase 3 tests (RED - ready to implement)
│       ├── ValueObjects/
│       ├── Events/
│       ├── AuthorityAssignmentTest.php
│       └── AuthorityResolverTest.php
├── Architecture/
│   └── GovernanceDomainPurityTest.php  # 10 fitness tests
└── Application/Authority/
    └── AuthorityGraphValidatorTest.php
```

---

## 🧱 Core Domain Models

### Phase 1: Committee Aggregate

```php
// Create a committee
$committeeId = CommitteeId::generate();
$committee = Committee::create(
    id: $committeeId,
    name: 'Japan Country Committee',
    levelIndex: 2,
    createdBy: MemberId::from('president-123'),
    now: new DateTimeImmutable()
);

// Lifecycle management
$committee->suspend(MemberId::from('admin'), 'Election audit required');
$committee->restore(MemberId::from('admin'));
$committee->dissolve(MemberId::from('icc-board'), 'Merged with regional body');

// Term management
$term = TermPeriod::from(
    start: new DateTimeImmutable('2025-01-01'),
    end: new DateTimeImmutable('2027-12-31')
);
$committee->startTerm($term);
$committee->extendTerm($newTerm);

// Hierarchy
$committee->attachToParent($parentId, $ancestors); // Cycle detection via policy

// Facts for policies
$facts = $committee->toFacts(); // Readonly snapshot
```

### Phase 2: Constitutional Memory

```php
// Record an immutable governance decision
$decision = GovernanceDecision::record(
    decisionId: GovernanceDecisionId::generate(),
    committeeId: $committeeId,
    capabilityType: 'COMMITTEE_FORMATION',
    legitimacy: Legitimacy::LEGITIMATE,
    constitutionalBasis: ConstitutionalBasis::from(
        article: 'Article 42.3',
        referenceText: 'Committee formation authority'
    ),
    trace: DecisionTrace::from(
        evaluatedRules: ['RULE_FORMATION_001'],
        matchedClauses: ['CLAUSE_5_2_A'],
        rejectedConstraints: [],
        authorityPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
        adjudicatedBy: MemberId::from('president-123'),
        adjudicatedAt: new DateTimeImmutable()
    ),
    effectiveFrom: new DateTimeImmutable('2025-01-01'),
    decidedAt: new DateTimeImmutable(),
    now: new DateTimeImmutable()  // Clock injection for replay safety
);

// Authority snapshot at decision time
$authorityChain = AuthorityChain::capture(
    actorId: MemberId::from('president-123'),
    role: GovernanceRole::COUNTRY_PRESIDENT,
    delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
    capturedAt: new DateTimeImmutable()
);

$authorityLevel = $authorityChain->authorityLevel(); // Derived from path length
```

### Phase 3: Authority Assignment (Design)

```php
// Delegate authority from one committee to another
$assignment = AuthorityAssignment::delegate(
    id: AuthorityAssignmentId::generate(),
    tenantId: $tenantId,
    fromCommitteeId: $iccCommitteeId,
    toCommitteeId: $continentCommitteeId,
    type: DelegationType::AUTHORITY,  // or OVERRIDE, TEMPORARY
    scope: DelegationScope::from('FINANCIAL_AUTHORITY'),
    validity: AuthorityEffectivePeriod::bounded($start, $end),
    delegatedBy: MemberId::from('icc-president'),
    delegatedAt: new DateTimeImmutable(),
    now: new DateTimeImmutable(),
    delegatorHasActiveAuthority: true  // For OVERRIDE validation only
);

// Revoke delegation (terminal - cannot be undone)
$assignment->revoke(
    revokedBy: MemberId::from('icc-board'),
    reason: 'Authority restructured',
    revokedAt: new DateTimeImmutable()
);

// Resolve active authority for a committee
$resolver = new AuthorityResolver();
$active = $resolver->resolveActiveAt(
    committeeId: $continentCommitteeId,
    at: new DateTimeImmutable(),
    repo: $authorityRepository
);

// Prevent cycles in delegation graph (application layer)
$validator = new AuthorityGraphValidator();
$validator->assertNoCycle($fromId, $toId, $repo); // Throws if cycle detected
```

---

## 🔒 Critical Invariants (The "Always True" Rules)

### Phase 1 - Committee Invariants

```yaml
INV-01: Committee cannot be restored if DISSOLVED (terminal state)
INV-02: Cannot suspend already DISSOLVED committee
INV-03: Term end must be after term start
INV-04: Extended term start must not precede current term start
INV-05: Committee cannot attach to itself
INV-06: Attachment must not create ancestor cycle
```

### Phase 2 - Governance Decision Invariants

```yaml
INV-07: GovernanceDecision is immutable once created (readonly)
INV-08: Integrity hash derived from canonical field order (future)
```

### Phase 3 - Authority Assignment Invariants

```yaml
INV-A01: Cannot delegate to self
INV-A02: REVOKED is terminal (cannot revoke twice)
INV-A03: Delegation must not create cycles (application layer)
INV-A04: OVERRIDE requires existing authority (delegatorHasActiveAuthority)
INV-A05: DelegatedAt must not exceed injected $now
INV-A06: If bounded validity, end > start
INV-A07: Delegation scope cannot be empty
```

---

## 🎨 Key Architectural Patterns

### 1. Private Constructor + Factory Methods

```php
// ❌ Forbidden
new CommitteeId($value);

// ✅ Required
CommitteeId::generate();      // UUID
CommitteeId::from($value);    // From string
```

### 2. Immutable Value Objects

```php
// All VOs are final readonly classes
final readonly class DelegationScope
{
    private function __construct(private string $value) {}
    public static function from(string $value): self { /* validation */ }
    public function value(): string { return $this->value; }
    public function equals(self $other): bool { return $this->value === $other->value; }
}
```

### 3. Pure Policies (No Constructor Injection)

```php
// ❌ Forbidden - policy cannot depend on repository
final class CommitteeHierarchyPolicy {
    public function __construct(CommitteeRepository $repo) {}
}

// ✅ Correct - pure domain service
final class CommitteeHierarchyPolicy {
    public function assertCanAttach(CommitteeId $childId, CommitteeId $parentId, array $ancestors): void {
        // Pure validation only - no external dependencies
    }
}
```

### 4. Clock Injection (No `new DateTimeImmutable()` in Domain)

```php
// ❌ Forbidden - domain creates its own clock
if ($decidedAt > new DateTimeImmutable()) { throw ... }

// ✅ Correct - clock injected from application layer
public static function record(..., DateTimeImmutable $now): self {
    if ($decidedAt > $now) { throw ... }
}
```

### 5. Event Dispatch AFTER Transaction Commit

```php
// Repository (Infrastructure)
DB::transaction(function () use ($aggregate) {
    $this->persist($aggregate);
});

// Application layer - AFTER commit
foreach ($aggregate->releaseEvents() as $event) {
    $this->eventBus->dispatch($event);
}
```

### 6. Domain Events as Historical Facts

```php
// Events contain NO logic - only data
final readonly class AuthorityDelegated
{
    private function __construct(
        private AuthorityAssignmentId $assignmentId,
        private CommitteeId $fromCommitteeId,
        private CommitteeId $toCommitteeId,
        // ... other fields
    ) {}
    
    public static function from(...): self { /* factory only */ }
    
    // Getters only - NO validate(), execute(), process(), compute()
}
```

### 7. Flat Query → Adjacency Map → Tree Assembly

```php
// Single query: SELECT * FROM committees WHERE tenant_id = ?
// Build parent_id → [children] map
// Iterative queue assembly (no recursion)
```

---

## 🧪 Testing Strategy

### Test Naming Convention

```php
// Test files follow: [ClassName]Test.php
// Methods follow: test_[invariant]_[scenario]

public function test_inv_a01_cannot_delegate_to_self(): void
public function test_priority_order_authority_overrides_override(): void
```

### RED-GREEN-REFACTOR Cycle

```yaml
1. Write ONE failing test (from invariant catalog)
2. Verify exact failure reason
3. Implement minimum code to pass
4. Run focused test suite
5. Refactor
6. Run regression suite

Forbidden: Batching tests, batching implementations, infrastructure before domain tests pass
```

### Test Coverage Requirements

```yaml
Value Objects:
  - Valid construction
  - Invalid construction (DomainException)
  - equals() contract
  - Immutability (readonly)

Aggregates:
  - Each invariant tested separately
  - Event emission verification
  - Terminal state transitions
  - Clock injection

Policies:
  - Stateless verification
  - Pure evaluation (no side effects)
  - No constructor injection

Events:
  - Private constructor
  - Readonly properties
  - No logic methods
  - No public setters
```

---

## 🚫 Forbidden Patterns (Stop-the-Line Rules)

```yaml
1. Domain depends on infrastructure:
   ❌ use Illuminate\Support\Str;
   ❌ use Carbon\Carbon;
   ❌ use Laravel\...

2. Public constructor on Value Objects:
   ❌ new CommitteeId($value);
   ✅ CommitteeId::from($value);

3. Behavior in Value Objects:
   ❌ $term->extend($newEnd);  // VO mutating state
   ✅ $newTerm = TermPeriod::from($start, $newEnd);

4. Policy with dependencies:
   ❌ public function __construct(CommitteeRepository $repo) {}
   ✅ Policy is pure, dependencies passed as method parameters

5. Clock in domain:
   ❌ new DateTimeImmutable() inside domain
   ✅ DateTimeImmutable $now injected

6. Event dispatch before commit:
   ❌ $this->events->dispatch() inside aggregate
   ✅ Release events, dispatch AFTER transaction

7. Cross-context dependency inversion:
   ❌ Phase 1 depends on Phase 2 (forbidden)
   ✅ Phase 2 depends on Phase 1 only
```

---

## 🏗️ Building the System

### Current State

```bash
# All Phase 1-2 tests pass
php artisan test tests/Unit/Contexts/Governance/ --no-coverage

# Architecture fitness tests pass
php artisan test tests/Architecture/ --no-coverage

# Phase 3 tests are RED (ready to implement)
php artisan test tests/Unit/Contexts/Governance/Domain/Authority/ --no-coverage
```

### Next Steps for Phase 3

```yaml
Order of implementation:
  1. Create Value Objects (5 files)
  2. Create DelegationLifecyclePolicy
  3. Create Events (2 files)
  4. Create AuthorityAssignment aggregate
  5. Create AuthorityResolver (domain service)
  6. Create AuthorityGraphValidator (application service)
  7. Run all tests → GREEN
  8. Run architecture fitness → confirm purity
```

### Running the Full Suite

```bash
# Phase 1 + Phase 2 + Phase 3 (after implementation)
php artisan test tests/Unit/Contexts/Governance/ tests/Architecture/ --no-coverage

# Expected: 120+ tests passing
```

---

## 📊 Key Metrics

```yaml
Current:
  - Phases Complete: 2 of 6
  - Tests Passing: 65+
  - Assertions: 170+
  - Value Objects: 12
  - Aggregates: 2 (Committee, GovernanceDecision)
  - Domain Events: 7
  - Policies: 2
  - Architecture Fitness Tests: 10

Phase 3 (when implemented):
  - Additional Tests: 50-60
  - Additional VOs: 5
  - Additional Aggregate: 1 (AuthorityAssignment)
  - Additional Events: 2
  - Additional Services: 2 (Resolver, Validator)
  
Total Projected (Phase 6):
  - Tests: 120-130
  - Assertions: 300+
  - Value Objects: 15+
  - Aggregates: 4
  - Events: 12+
```

---

## 🔗 Key Files to Reference

### For Value Object Patterns
- `CommitteeId.php` - UUID generation pattern
- `TermPeriod.php` - Bounded range validation
- `DelegationScope.php` - Non-empty string validation

### For Aggregate Patterns
- `CommitteeAggregate.php` - Lifecycle ownership, event recording
- `GovernanceDecision.php` - Immutable record, clock injection
- `AuthorityAssignment.php` (to implement) - Terminal state, INV-A02

### For Policy Patterns
- `CommitteeHierarchyPolicy.php` - Pure domain service
- `DelegationLifecyclePolicy.php` - State transition rules

### For Event Patterns
- `GovernanceDecisionRecorded.php` - Complete projection, historical fact
- `AuthorityDelegated.php` (to implement) - Immutable fact

### For Test Patterns
- `DelegationStatusTest.php` - Enum testing
- `AuthorityAssignmentTest.php` - Aggregate invariants
- `AuthorityGraphValidatorTest.php` - Application service with repository

---

## 🎓 Learning Resources Within This Codebase

```yaml
To understand Private Constructor + Factory:
  → app/Contexts/Governance/Domain/ValueObjects/GovernanceDecisionId.php

To understand Immutable VO with validation:
  → app/Contexts/Governance/Domain/Authority/ValueObjects/DelegationScope.php (to implement)

To understand Aggregate with invariants:
  → app/Contexts/Membership/Domain/Committee/CommitteeAggregate.php

To understand Clock Injection:
  → app/Contexts/Governance/Domain/GovernanceDecision.php (record method)

To understand Pure Policy:
  → app/Contexts/Membership/Domain/Committee/Policies/CommitteeHierarchyPolicy.php

To understand Event as Historical Fact:
  → app/Contexts/Governance/Domain/Events/GovernanceDecisionRecorded.php

To understand Repository Interface:
  → app/Contexts/Governance/Domain/Authority/AuthorityAssignmentRepository.php (to implement)

To understand Application Service with Repository:
  → app/Contexts/Governance/Application/Authority/AuthorityGraphValidator.php (to implement)
```

---

## ✅ Developer Checklist

Before submitting code:

```yaml
Domain Purity:
  ✅ No Laravel/Carbon imports in domain
  ✅ No public constructors on VOs
  ✅ All VOs are final readonly
  ✅ All aggregates have private constructor
  ✅ No public setters
  ✅ No `new DateTimeImmutable()` in domain (inject $now)
  ✅ No logic in events (getters only)

Invariants:
  ✅ Each invariant has a dedicated test
  ✅ DomainException thrown with meaningful message
  ✅ Terminal states enforced

Testing:
  ✅ RED-GREEN-REFACTOR followed
  ✅ One behavior per test
  ✅ Test names describe invariant (e.g., test_inv_a01_cannot_delegate_to_self)

Documentation:
  ✅ PHPDoc for public methods
  ✅ Type hints everywhere
  ✅ declare(strict_types=1)
```

---

## 🚀 Conclusion

You now have a **production-ready constitutional governance engine** with:
- Immutable audit trails (Phase 2)
- Committee lifecycle management (Phase 1)
- Authority delegation design (Phase 3 ready)
- Full test coverage (65+ tests)
- Architecture fitness enforcement (10 layers)
- No framework dependencies in domain

**The system is organization-agnostic** - works for NRNA, political parties, corporations, NGOs, and cooperatives with only naming changes.

**Proceed to implement Phase 3 (Authority Assignment)** following the test-first approach. 🚀