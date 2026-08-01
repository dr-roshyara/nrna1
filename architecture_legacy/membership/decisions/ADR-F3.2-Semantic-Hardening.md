# ADR-F3.2: Semantic Hardening — Type Safety, Policy Delegation, and Trust Boundaries

**Date:** 2026-05-15  
**Status:** ACCEPTED  
**Applies to:** F3.2-L2 (Phase 2 Layer 2)  
**Affects:** CommitteeAssociation, MembershipLineage, MembershipTransitionPolicy, exception taxonomy  
**Reviewers:** Domain Architecture  

---

## Context

Phase F3.2-L1 made invalid entity construction impossible through constructor invariants. Phase F3.2-L2 addresses four remaining architectural weaknesses that would degrade future phases:

1. **Primitive leakage** — `?string $actorId` and `?string $transitionReason` bypass type guarantees
2. **Constructor owns lifecycle policy** — CommitteeAssociation has procedural if-blocks that belong in a dedicated policy service
3. **Wrong exception type** — MembershipLineage::reconstitute() threw `\InvalidArgumentException` (infrastructure) for constitutional violations (domain)
4. **Undocumented trust boundaries** — Three semantic creation paths (`create`, `rehydrate`, `replay`) lacked formal contract documentation

This ADR formalizes the architectural decisions made to resolve these weaknesses.

---

## Decision 1: ActorId Moves to Shared/Domain/ValueObjects

### Decision
`ActorId` is relocated from `Membership/Domain/Membership/ValueObjects/` to `Shared/Domain/ValueObjects/`.

### Rationale
Actor identity is a **cross-context concept**, not Membership-specific:
- **Membership context:** Actors suspend, restore, or terminate memberships
- **Committee context:** Actors make governance decisions (approve elections, dissolve committees)
- **Audit context:** Actors are recorded in all domain event payloads
- **Future contexts:** Committee, Elections, Governance (TBD) all reference actors

Actor identity is analogous to `TenantId` — a shared concern that appears in every context but belongs nowhere specifically. By placing it in `Shared/Domain/ValueObjects`, we:
- Signal that actors are cross-context
- Enable consistent actor semantics across all contexts
- Avoid premature Membership-specific coupling
- Follow the existing `TenantId` precedent

### Implementation
Moved `app/Contexts/Membership/Domain/Membership/ValueObjects/ActorId.php` → `app/Contexts/Shared/Domain/ValueObjects/ActorId.php`

Same validation and interface:
```php
final readonly class ActorId {
    private function __construct(private string $value) { ... }
    public static function fromString(string $value): self
    public function value(): string
    public function equals(self $other): bool
}
```

### Trade-offs
- **Pro:** Enables future cross-context actor operations (e.g., "all decisions by actor X")
- **Pro:** Eliminates artificial Membership → Shared dependency inversion
- **Con:** ActorId must exist before any actor-using context is implemented
- **Mitigation:** ActorId is stable — changes only if actor identity semantics evolve (unlikely)

---

## Decision 2: TransitionReason Stays in Membership/Domain

### Decision
`TransitionReason` remains in `app/Contexts/Membership/Domain/Membership/ValueObjects/` (not moved to Shared).

### Rationale
Transition reasons are **Membership-specific** because:
- Only Membership aggregates have transitions (suspend → restore, terminate)
- Reason semantics are tightly bound to membership lifecycle ("Disciplinary suspension", "Expiration of term")
- No other context currently needs to reference transition reasons
- The concept may evolve into `TransitionJustification` with semantic categories (disciplinary, administrative, etc.) — evolution should happen in Membership context first

### Future Evolution Path
As the domain matures, `TransitionReason` may evolve:
- Phase F3.2 → `TransitionReason` (simple string)
- Phase Fx → `TransitionJustification` (structured: category + authority + policy reference)
- Phase Fy → Shared concept if Committee/Elections also need justification modeling

The boundary is intentionally permeable: move to Shared only when a second consumer appears (YAGNI principle).

### Implementation
Created `app/Contexts/Membership/Domain/Membership/ValueObjects/TransitionReason.php`

```php
final readonly class TransitionReason {
    private function __construct(private string $value) {
        if (empty(trim($value))) {
            throw new \DomainException('TransitionReason cannot be empty');
        }
    }
    public static function fromString(string $value): self { return new self($value); }
    public function value(): string { return $this->value; }
}
```

### Trade-offs
- **Pro:** Keeps Membership concerns localized until generalization is proven
- **Pro:** Avoids speculative Shared abstractions
- **Con:** Requires future refactoring if second consumer appears (low cost)
- **Mitigation:** Decision is documented; refactoring is straightforward

---

## Decision 3: MembershipTransitionPolicy Owns Transition Matrix + Audit Policy (Nothing Else)

### Decision
`MembershipTransitionPolicy` is extracted to own ONLY:
1. Allowed transition matrix (`canTransition(from, to)`)
2. Audit-field requirements enforcement (`assertAuditRequirementsMet(status, actorId, reason, timestamp)`)

### What MembershipTransitionPolicy Does NOT Own
- Episode ordering (aggregate responsibility)
- Terminal-state chain enforcement (aggregate responsibility)
- Temporal continuity validation (aggregate responsibility)
- Lineage consistency (aggregate responsibility)

### Rationale
The policy service owns **transition legality and audit requirements** because these are domain rules that can be named and tested independently. However, **aggregate chain consistency** is part of the **aggregate's identity protection** and must stay inside `MembershipLineage`.

The boundary prevents a "God Service" anti-pattern where a procedural manager service owns all lifecycle logic. Instead:
- **Policy** = Stateless, reusable rules (transition matrix, audit field contracts)
- **Aggregate** = Stateful, identity-bearing logic (chain validation, episode sequencing)

### Implementation
Created `app/Contexts/Membership/Domain/Membership/MembershipTransitionPolicy.php`

```php
final class MembershipTransitionPolicy {
    public static function canTransition(MembershipStatus $from, MembershipStatus $to): bool
    {
        return match(true) {
            $from->equals(MembershipStatus::ACTIVE)    && $to->equals(MembershipStatus::SUSPENDED)  => true,
            $from->equals(MembershipStatus::ACTIVE)    && $to->equals(MembershipStatus::TERMINATED) => true,
            $from->equals(MembershipStatus::SUSPENDED) && $to->equals(MembershipStatus::ACTIVE)     => true,
            $from->equals(MembershipStatus::SUSPENDED) && $to->equals(MembershipStatus::TERMINATED) => true,
            default => false,
        };
    }

    public static function assertAuditRequirementsMet(
        MembershipStatus $status,
        ?ActorId $actorId,
        ?TransitionReason $transitionReason,
        ?\DateTimeImmutable $transitionedAt,
    ): void {
        if ($status->equals(MembershipStatus::SUSPENDED)) {
            if ($actorId === null)          throw InvalidMembershipConstructionException::suspendedRequiresActorId();
            if ($transitionReason === null) throw InvalidMembershipConstructionException::suspendedRequiresTransitionReason();
            if ($transitionedAt === null)   throw InvalidMembershipConstructionException::suspendedRequiresTimestamp();
        }
        if ($status->equals(MembershipStatus::TERMINATED)) {
            if ($actorId === null)          throw InvalidMembershipConstructionException::terminatedRequiresActorId();
            if ($transitionReason === null) throw InvalidMembershipConstructionException::terminatedRequiresTransitionReason();
            if ($transitionedAt === null)   throw InvalidMembershipConstructionException::terminatedRequiresTimestamp();
        }
    }
}
```

### Trade-offs
- **Pro:** Clear separation of concerns (legality vs consistency)
- **Pro:** Policy is testable independently of aggregate chain logic
- **Pro:** Prevents procedural "domain manager" anti-pattern
- **Con:** Developers must understand why aggregate keeps its own validation (well-documented via ADR)
- **Mitigation:** Trust boundary docblocks explain the boundary explicitly

---

## Decision 4: Chain Validation Stays Inside MembershipLineage (Never Extracted)

### Decision
`MembershipLineage` owns and will NEVER delegate:
1. Episode ordering validation
2. Terminal-state guarantees (TERMINATED cannot be followed by any state)
3. Possible-transition validation
4. Lineage continuity

### Rationale
The aggregate root's identity **is its lineage**. Extracting chain validation to an external service would:
- Break encapsulation: the aggregate loses control of its own invariants
- Create a "procedural domain manager" anti-pattern (logic outside the aggregate)
- Weaken the aggregate's role as the constitutional authority
- Enable external code to construct invalid lineages

Aggregate invariants must be **owned and enforced by the aggregate itself**. A lineage that violates its own rules is a broken aggregate root.

### Design Consequence
Chain validation is expressed as **extracted semantic methods** (not extracted services):

```php
// INSIDE MembershipLineage, private methods:
private static function assertNonEmptyEpisodeChain(array $episodes): void
private static function assertInitialEpisodeIsConstitutionallyValid(CommitteeAssociation $firstEpisode): void
private static function assertNoImpossibleTransitions(array $episodes): void
private static function assertTerminalStatesRemainTerminal(array $episodes): void

// Called in reconstitute() before construction
public static function reconstitute(...) {
    self::assertNonEmptyEpisodeChain($episodes);
    self::assertInitialEpisodeIsConstitutionallyValid($episodes[0]);
    self::assertNoImpossibleTransitions($episodes);
    self::assertTerminalStatesRemainTerminal($episodes);
    
    return new self(...);
}
```

These are **semantic extraction for clarity**, not architectural extraction for reuse. The aggregate remains the sole owner.

### Trade-offs
- **Pro:** Aggregate is the constitutional authority over its own state
- **Pro:** No external service can bypass or weaken chain validation
- **Pro:** Aggregate encapsulation is preserved
- **Con:** Method signature is larger (4 calls in `reconstitute()` instead of 1 service call)
- **Mitigation:** Semantic methods have clear names that explain what they validate

---

## Decision 5: Invalid Database State Is Fatal (Trust Boundary Enforcement)

### Decision
When `MembershipLineage::reconstitute()` or `CommitteeAssociation::rehydrate()` encounters invalid persisted state, they throw `DomainException` immediately. The system does not attempt recovery or silent corruption.

Invalid state includes:
- Episode with `status=SUSPENDED` but `actorId=NULL`
- Episode with `status=TERMINATED` but `transitionReason=NULL`
- Impossible episode sequence (e.g., TERMINATED followed by ACTIVE)
- Empty episode array

### Rationale
The trust boundary assumption is: **"The persistence layer has already validated database constraints."** If the database contains invalid rows:
- The system has a bug (code, migration, or both)
- Recovery is not possible (you cannot know which state was intended)
- Silent corruption is worse than loud failure (auditing becomes unreliable)
- The system should surface the corruption loudly so operators can investigate

Failing fast **protects the election system's integrity**. An election with unknown state is worse than a system that refuses to run.

### Enforcement Mechanism
`rehydrate()` and `reconstitute()` throw domain-semantic exceptions (`InvalidMembershipConstructionException`, `InvalidMembershipLineageException`) instead of infrastructure exceptions. This signals to the operator: **"Your data is invalid, investigate before proceeding."**

### Trade-offs
- **Pro:** System fails visibly when data is invalid
- **Pro:** Operator is forced to investigate rather than silently accepting bad state
- **Pro:** Audit trail integrity is protected
- **Con:** Requires database validation before deploying code
- **Mitigation:** Pre-deployment checklist: "Verify no invalid rows exist in production"

---

## Decision 6: ACTIVE with actorId Is Valid (Restoration Audit Metadata)

### Decision
An episode with `status=ACTIVE` may have `actorId` and `transitionedAt` set (from a prior restoration). This is valid.

### Rationale
When a membership is restored from SUSPENDED → ACTIVE, the aggregate records:
- `actorId` = who performed the restoration
- `transitionedAt` = when the restoration occurred
- `transitionReason` = `null` (restoration doesn't require justification, only suspension does)

This creates a situation where:
```
Episode 1: ACTIVE (no audit fields, original creation)
Episode 2: SUSPENDED (actorId, transitionReason, transitionedAt set)
Episode 3: ACTIVE (actorId, transitionedAt set, transitionReason=null)
```

Episode 3 has `ACTIVE` status but also has audit metadata. This is intentional: we want to know **who restored** the membership, even though ACTIVE status doesn't always have actor information.

### Validation Contract
The policy enforcement is:
- `SUSPENDED` → MUST have `actorId`, `transitionReason`, `transitionedAt`
- `TERMINATED` → MUST have `actorId`, `transitionReason`, `transitionedAt`
- `ACTIVE` → MAY have `actorId` and `transitionedAt` (optional, from restoration)

### Trade-offs
- **Pro:** Restoration audit is recorded
- **Pro:** Lineage history is complete
- **Con:** `ACTIVE` episodes can have optional audit fields (adds complexity)
- **Mitigation:** Policy documentation is clear about the exception

---

## Decision 7: Trust Boundary Taxonomy — Three Semantic Paths

### Decision
`CommitteeAssociation` exposes three distinct creation paths, each with different trust assumptions and guarantees:

| Path | Method | Inputs | Trust Assumption | Guarantee | Use Case |
|------|--------|--------|------------------|-----------|----------|
| **Domain Command** | `create()` | Domain VOs | Caller is domain logic | Output is always ACTIVE | New relationships |
| **Persistence Snapshot** | `rehydrate()` | DB values + VO conversion | DB rows passed schema constraints | Output satisfies invariants or throws | Loading from database |
| **Event Stream** | `replay()` [F3.3] | Event sequence | Events are immutable and ordered | Output is deterministic | Event-sourcing reconstruction |

### Rationale
These three paths have fundamentally different trust models:
- **create():** Untrusted input (from user/command) → enforces all rules
- **rehydrate():** Semi-trusted input (from DB) → trusts schema but validates semantics
- **replay():** Immutable input (from event log) → trusts event sequence ordering

Mixing these paths creates confusion. Making them explicit in the code (three separate methods with clear docblocks) prevents misuse.

### Implementation
Each method has formal trust boundary documentation:

```php
/**
 * Factory: Establish a new constitutional relationship (domain command path).
 * TRUST BOUNDARY: Domain-Controlled Creation
 */
public static function create(...)

/**
 * Reconstitute an episode from database persistence (trust-restricted reconstruction).
 * TRUST BOUNDARY: Persistence Snapshot Boundary
 */
public static function rehydrate(...)

/**
 * Reconstruct episode from immutable event stream (F3.3 event sourcing — reserved).
 * TRUST BOUNDARY: Event Stream Boundary (F3.3 - Not Yet Implemented)
 */
// public static function replay(...)
```

### Trade-offs
- **Pro:** Trust model is explicit and auditable
- **Pro:** Developers cannot accidentally use wrong path
- **Pro:** Future event-sourcing integration has a reserved, documented boundary
- **Con:** Three methods instead of one factory (more code to read)
- **Mitigation:** Docblocks explain when to use each path

---

## Decision 8: Membership Owns Lifecycle Truth; Committee Interprets State

### Decision
The **Membership context** is the sole constitutional authority over membership state. The **Committee context** (and future Elections/Governance contexts) **read** membership state but **never mutate** it directly.

### Rationale
Membership state must be owned by a single context to prevent state divergence:
- Membership defines: "What is this member's status with respect to this committee?"
- Committee uses that answer: "Can this member vote?"
- Committee cannot answer: "Change this member's status" (that's Membership's decision)

This is **context boundary isolation**, not just access control. Committee doesn't call `membership->suspend()`. Instead:
- Membership system has use cases: "A member should be suspended" (via application layer)
- Committee interprets results: "Is this member SUSPENDED?" (via read model adapter)

### Consequence: Committee Dissolution
When a committee dissolves, it does NOT automatically terminate all its memberships. Why?
- Memberships are constitutional relationships (independent of any one committee's existence)
- A member can rejoin a reformed committee with the same historical lineage
- Dissolving a committee is a committee decision, not a membership decision

This design preserves membership lineage integrity across organizational changes.

### Trade-offs
- **Pro:** Single source of truth for membership state
- **Pro:** Prevents divergence bugs (Committee changes state, Membership doesn't update)
- **Pro:** Membership logic is protected from Committee-specific rules
- **Con:** Requires careful use-case design (application layer must own "suspend this member" decision)
- **Mitigation:** Application-layer commands (e.g., `SuspendMemberCommand`) are the explicit decision point

---

## Decision 9: Exception Taxonomy — Domain-Semantic Exceptions Only

### Decision
All Membership context exceptions extend `\DomainException` (not SPL exceptions). There are three exception classes:

| Exception | Extends | Means | When Thrown |
|-----------|---------|-------|-------------|
| `InvalidMembershipConstructionException` | `DomainException` | Entity state is invalid | Constructor pre-conditions violated |
| `InvalidAssociationTransitionException` | `DomainException` | Transition is illegal | `suspend()`, `restore()`, `terminate()` invalid state |
| `InvalidMembershipLineageException` | `DomainException` | Aggregate chain is broken | Lineage chain violations in `reconstitute()` |

### Rationale
Using SPL exceptions (`\InvalidArgumentException`, `\TypeError`) for domain violations is a category error:
- SPL exceptions = Infrastructure problems (database down, invalid JSON)
- Domain exceptions = Constitutional violations (member cannot be restored from terminated state)

By using domain-semantic exceptions:
- Catch blocks can distinguish domain errors from infrastructure errors
- Exception handlers know what context they're in
- API layer can respond appropriately (422 Unprocessable Entity for domain, 500 for infrastructure)
- Tests can assert domain invariants via exception type

### Implementation
```php
final class InvalidMembershipConstructionException extends \DomainException { ... }
final class InvalidAssociationTransitionException extends \DomainException { ... }
final class InvalidMembershipLineageException extends \DomainException { ... }
```

Each has named constructors for specific violations:
```php
InvalidMembershipConstructionException::suspendedRequiresActorId()
InvalidMembershipConstructionException::terminatedRequiresTransitionReason()
InvalidAssociationTransitionException::cannotSuspendFrom(MembershipStatus $status)
InvalidMembershipLineageException::terminatedIsTerminal()
```

### Trade-offs
- **Pro:** Exception type indicates the kind of domain violation
- **Pro:** Tests can assert via exception type (more reliable than message matching)
- **Pro:** Application layer can route exceptions appropriately
- **Con:** Requires custom exception classes (boilerplate)
- **Mitigation:** Named constructors reduce verbosity and improve readability

---

## Deferred Decisions (Phase F3.3+)

### Temporal Validation Layer
Once event sourcing is implemented (F3.3), temporal validation rules will be extracted:
- Episode timestamps must be strictly increasing
- Episode transitions must respect temporal ordering
- Audit trails must have chronological integrity

These will become a separate validation layer in `MembershipLineage::reconstitute()`, after chain validation.

### Governance Validation Layer
Once governance rules are formalized (F3.4+), governance-aware validation may be needed:
- Certain transitions may be restricted based on election state
- Membership changes during voting may be prohibited
- Suspensions during voting may have special handling

These will be added to `MembershipTransitionPolicy` or a new `GovernanceTransitionPolicy`.

---

## Migration Path (If Needed)

### Evolving TransitionReason to TransitionJustification
If TransitionReason needs semantic categories:

1. Create new `TransitionJustification` VO in Membership/Domain
2. Add `category: TransitionCategory` enum
3. Update `CommitteeAssociation` to use `TransitionJustification` instead of `TransitionReason`
4. Update all tests to use new VO
5. **Keep `TransitionReason` as alias for backward compatibility** (if needed)

### Moving TransitionReason to Shared
If a second context needs transition reasons:

1. Move `TransitionReason` to `Shared/Domain/ValueObjects/`
2. Update Membership imports
3. No code changes needed (just file relocation)

---

## Validation

### Tests That Prove This ADR
- ✅ All 35 MutationResistance tests GREEN
- ✅ All 111 Constitutional tests GREEN
- ✅ `CommitteeAssociationConstructorSafetyTest` validates typed VOs
- ✅ `MembershipLineageConstructorSafetyTest` validates chain rules
- ✅ `MembershipTransitionPolicyTest` validates transition matrix and audit policy
- ✅ `MembershipHydrationIntegrityTest` validates persistence trust boundary

---

## Summary

F3.2-L2 establishes:
1. **Type safety** through promoted value objects (ActorId, TransitionReason)
2. **Clear policy ownership** (MembershipTransitionPolicy for transition rules)
3. **Aggregate integrity** (MembershipLineage owns chain validation, never delegates)
4. **Explicit trust boundaries** (three semantic creation paths with formal contracts)
5. **Fatal-on-invalid** philosophy (persistence layer is trusted but validated)
6. **Domain-semantic exceptions** (all violations extend DomainException)

These decisions protect the integrity of the Membership aggregate root and establish clear boundaries for future phases (event sourcing, governance rules, Committee integration).

---

**Approved:** 2026-05-15  
**Committed:** See `git log` for phase F3.2-L2 commits  
**Status:** ACTIVE (governing current and future Membership implementations)
