# GEO-3.5 — Voting Context Architecture

**Status:** Implementation Complete (9 tests passing)  
**Date:** 2026-05-09  
**Phase:** GEO-3.5 Membership Governance — Voting Semantics Foundation  

---

## Executive Summary

**Voting Context** is a deterministic, order-independent orchestration layer that composes voting semantics without mutating the semantic canon layer (GEO-3.4D).

**Design Principle:** VotingEngine is NOT an algorithm owner. It is a thin deterministic coordinator delegating all complexity to domain services.

**Guarantees Delivered:**
- ✅ **Determinism:** Identical inputs → identical fingerprint (no time, random, or state)
- ✅ **Order Independence:** Vote insertion order does not affect outcome
- ✅ **Semantic Safety:** Read-only consumption of semantic canon (zero mutation)
- ✅ **Immutability:** VotingSession is readonly aggregate, no mutations possible
- ✅ **Replay Safety:** Fingerprint enables deterministic historical reconstruction

---

## Architectural Boundary (Critical)

### Semantic Layer Freeze Rule ✅

```
GEO-3.4D Semantic Canon (READ-ONLY)
        ↓ read-only reference
GEO-3.5 VotingEngine (evaluative only)
        ↓ immutable result
VotingSession (no side effects)
```

### What VotingEngine CAN Do:
- ✅ Reference semantic definitions
- ✅ Evaluate semantics against voting rules
- ✅ Compose semantics into governance outcomes
- ✅ Return immutable voting results

### What VotingEngine CANNOT Do:
- ❌ Mutate semantic registry
- ❌ Create new semantic categories
- ❌ Extend semantic meanings
- ❌ Generate feedback loops to semantic layer

---

## Domain Model

### Value Objects (Immutable)

#### 1. EligibilitySnapshot
**Purpose:** Canonical eligible voter list (deterministic ordering)

```php
final readonly class EligibilitySnapshot
{
    public function __construct(array $eligibleVoters)
    public function eligibleVoters(): array
    public function isEligible(string $voterId): bool
    public function count(): int
}
```

**Invariants:**
- Eligible voters are sorted lexicographically
- At least one voter required
- Immutable after construction

---

#### 2. BallotCollection
**Purpose:** Deterministically normalized ballot set

```php
final readonly class BallotCollection
{
    public function __construct(array $ballots)
    public function ballots(): array
    public function filterByEligible(EligibilitySnapshot $eligibility): BallotCollection
    public function count(): int
    public function isEmpty(): bool
}
```

**Invariants:**
- Ballots sorted by voter ID (deterministic ordering)
- Structure: `['voter' => string, 'selection' => string]`
- Filtering returns new instance (immutable)

---

#### 3. QuorumDefinition
**Purpose:** Quorum threshold specification

```php
final readonly class QuorumDefinition
{
    public function __construct(int $threshold)
    public function threshold(): int
    public function isMet(int $participationCount): bool
}
```

**Invariants:**
- Threshold must be positive
- Deterministic comparison (no subjective evaluation)

---

#### 4. VotingOutcome
**Purpose:** Election result computation (pure function result)

```php
final readonly class VotingOutcome
{
    public function __construct(array $validBallots)
    public function validBallots(): array
    public function results(): array
    public function countFor(string $selection): int
    public function winner(): ?string
    public function totalVotes(): int
}
```

**Invariants:**
- Results are deterministically computed
- Selections sorted lexicographically
- Results immutable after construction

---

### Services (Stateless Evaluators)

#### EligibilityEvaluator
**Purpose:** Extract eligible voters from snapshot (read-only)

```php
final class EligibilityEvaluator
{
    public function evaluate(EligibilitySnapshot $snapshot): array
}
```

**Contract:** Pure function, no state, no side effects.

---

#### VoteAggregator
**Purpose:** Normalize, deduplicate, and order-independent aggregation

**Steps:**
1. Filter by eligible voters
2. Deduplicate by voter (keep last vote, deterministically)
3. Normalize via lexicographic sort

```php
final class VoteAggregator
{
    public function aggregate(
        BallotCollection $ballots,
        EligibilitySnapshot $eligibility
    ): array
}
```

**Contract:** Pure function, deterministic ordering, idempotent.

---

#### QuorumCalculator
**Purpose:** Evaluate quorum met condition

```php
final class QuorumCalculator
{
    public function evaluate(array $validVotes, QuorumDefinition $quorum): bool
}
```

**Contract:** Pure function, deterministic comparison.

---

### Aggregate (Immutable Result)

#### VotingSession
**Purpose:** Immutable encapsulation of voting process output

```php
final readonly class VotingSession
{
    public function __construct(
        public EligibilitySnapshot $eligibilitySnapshot,
        public BallotCollection $ballots,
        public VotingOutcome $outcome,
        public bool $quorumMet
    )

    public static function create(...): self
    public function fingerprint(): string
}
```

**Invariants:**
- All properties are readonly
- Fingerprint is sha256 canonical hash
- No mutation possible after construction

**Fingerprint Contract:**
```
hash('sha256', json_encode({
    'eligible_voters': [...],      // sorted
    'valid_ballots': [...],         // normalized
    'results': {...},               // sorted
    'quorum_met': bool
}, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
```

---

### Orchestrator

#### VotingEngine
**Purpose:** Deterministic voting process coordinator

**Execution Flow:**
```
1. eligibilityEvaluator.evaluate(eligibility)
   ↓
2. voteAggregator.aggregate(ballots, eligibility)
   ↓
3. quorumCalculator.evaluate(validVotes, quorum)
   ↓
4. VotingOutcome::create(validVotes)
   ↓
5. VotingSession::create(...)
```

**Contract:** No hidden state, no time/random, deterministic delegation.

---

## Test Coverage

### 9 Tests — Determinism & Order Independence Verified

| Test | Guarantees |
|------|-----------|
| `test_engine_executes_and_returns_voting_session` | Basic orchestration works |
| `test_voting_engine_is_deterministic` | Same inputs → same fingerprint |
| `test_voting_engine_is_order_independent` | Ballot order doesn't matter |
| `test_voting_engine_evaluates_quorum_correctly` | Quorum calculation correct |
| `test_voting_engine_filters_ineligible_voters` | Eligibility enforced |
| `test_voting_session_is_immutable` | Readonly properties verified |
| `test_voting_engine_computes_outcome` | Results computed correctly |
| `test_voting_session_fingerprint_determinism` | 5 executions → identical fingerprints |
| `test_voting_engine_with_empty_ballots` | Edge case handled |

**Result:** 9 passed, 0 failures, 19 assertions.

---

## Semantic Safety Verification

### Boundary Enforcement

✅ **VotingEngine does NOT:**
- Call semantic registry
- Mutate semantic definitions
- Create new semantic categories
- Reference semantic meanings directly

✅ **VotingEngine DOES:**
- Accept pre-evaluated eligibility snapshot
- Apply pure voting logic
- Return immutable results

### Semantic Consumption Contract

VotingEngine respects:
1. **Read-Only:** Semantic canon is immutable reference
2. **No Feedback:** Voting outcomes do NOT influence semantics
3. **Compositional:** Voting logic composes existing semantics
4. **Evaluative:** Pure evaluation, never generative

---

## Architectural Positioning

```
Layer 1: Snapshot Integrity (GEO-3.4D)
        ↓
Layer 2: Replay Certification (GEO-3.4D)
        ↓
Layer 3: Doctrine Versioning (GEO-3.4D)
        ↓
Layer 4: Semantic Canon (GEO-3.4D) ← READ-ONLY boundary
        ↓
Layer 5: Voting Engine (GEO-3.5) ← THIS LAYER
        ↓
VotingSession (Immutable Result)
        ↓
GEO-3.6+: Membership Governance Expansion
```

---

## Design Decisions & Rationale

### Why VotingEngine is a Coordinator, Not an Algorithm

**Decision:** Delegate eligibility, aggregation, quorum, outcome computation to specialist services.

**Rationale:**
- Simplicity: VotingEngine is ~20 lines, not 200+
- Testability: Each service is independently testable
- Determinism: Easier to verify with thin orchestration
- Maintainability: Changes to voting rules stay local to services

---

### Why Fingerprint is Canonical Hash

**Decision:** SHA256 over canonical JSON with sorted fields.

**Rationale:**
- Order-independence: Sorted fields ensure equivalent results match
- Determinism: No timestamps, no random components
- Replay safety: Fingerprint is stable across time
- Auditability: Hash enables integrity verification

---

### Why VotingSession is Readonly

**Decision:** Final readonly class with all readonly properties.

**Rationale:**
- Immutability: No mutations possible after creation
- Architectural enforcement: Type system enforces boundaries
- Replay safety: Immutable results enable replayability
- Semantics preservation: No dynamic mutation of voting state

---

## Known Constraints (Intentional)

These are acceptable for Green phase, hardened in later refactors:

1. **EligibilitySnapshot is pre-canonical** — assumes upstream validation
2. **BallotCollection is pre-validated** — assumes voter/selection exist
3. **QuorumDefinition is numeric** — no semantic references (semantic-safe)
4. **VotingOutcome is pure data** — assumes valid selections

These constraints preserve semantic safety: voting layer never interprets semantics, only consumes pre-evaluated eligibility.

---

## Future Evolution Roadmap

### GEO-3.6 (Planned)
- Introduce `TermLimitValidator` (temporal composition)
- Add `DelegationEligibility` (authority composition)
- Expand to multi-round voting

### GEO-3.7 (Planned)
- Proxy voting semantics (deferred for architectural clarity)
- Recusal grounds evaluation

### GEO-3.8+ (Future)
- Constitutional amendment voting
- Doctrine ratification process
- Emergency governance activation

**All future extensions will respect:** Semantic canon is immutable, voting layer is evaluative-only.

---

## Sign-Off

**Voting Context:** ✅ Architecturally sound, deterministically correct, semantically safe.

**Boundary Enforcement:** ✅ Semantic canon remains read-only, no mutation possible.

**Test Coverage:** ✅ 9 tests verify determinism, order-independence, immutability.

**GEO-3.6+ Readiness:** ✅ Foundation is stable for membership governance expansion.

**Proceed to:** Quorum Validation Semantics (GEO-3.5 Phase 2)
