# ADR: Semantic Canon Architecture — Immutable Constitutional Vocabulary

**Status:** Approved  
**Date:** 2026-05-08  
**Phase:** GEO-3.4D  
**Architect:** Constitutional Governance Engine Team  

---

## Executive Summary

The platform transitions from **replay-safe data structures** to **replay-safe semantic governance** by introducing an immutable semantic canon layer that protects constitutional meaning from retroactive corruption.

**Core Decision:** Semantics must remain immutable after publication to preserve historical constitutional truth across replay operations.

---

## Problem Statement

Most governance systems preserve data through snapshots and replay. Very few preserve **meaning**.

### Historical Context

In constitutional systems, meaning is as critical as data:
- A vote under "MANDATE_ACTIVE = Elected authority" (2026) means something different if interpreted as "Elected or delegated authority" (2028)
- A quorum requirement of "≥50% participation" must not retroactively mean "≥50% present" in a future doctrine
- A delegation semantic cannot be reinterpreted to mean something incompatible with its original constitutional context

### Risk if Unsolved

Without immutable semantics:
- Historical governance decisions become legally ambiguous
- Replay certification cannot prove constitutional consistency
- Doctrine evolution corrupts the meaning of past decisions
- Constitutional archaeology becomes unreliable
- Audit trails lose institutional weight

---

## Solution: Semantic Canon Layer

Introduce an **immutable semantic canon** — a central registry of governance meanings that:
1. Registers constitutional vocabulary exactly once
2. Prevents retroactive mutations to meaning, implications, or categories
3. Enforces deterministic ordering for replay consistency
4. Validates semantic evolution through strict policy
5. Preserves historical constitutional truth across time

### Core Principle

**Semantic immutability > Feature velocity**

When a semantic meaning is published (registered), it is **final and unchangeable**. New semantics can be added, but existing semantics cannot be modified.

---

## Architecture

### Layer Stack (4 Protection Layers)

```
Layer 4: Semantic Canon              ← Prevents meaning corruption
         ImmutableSemanticEvolutionPolicy
         GovernanceSemanticRegistry
         Deterministic ordering

Layer 3: Doctrine Versioning         ← Prevents doctrine corruption  
         SnapshotMetadata
         DoctrineVersion tracking

Layer 2: Replay Certification        ← Prevents replay inconsistency
         ConstitutionalReplayFingerprint
         5-dimensional equivalence

Layer 1: Snapshot Integrity          ← Prevents data tampering
         SHA256 hashing
         Immutable snapshots
```

### Semantic Canon Components

#### 1. GovernanceSemanticCategory (Enum)

**Purpose:** Canonical governance semantic vocabulary

**Categories:**
- AUTHORITY — Power and permission
- LEGITIMACY — Constitutional validity
- DELEGATION — Authority transfer
- PARTICIPATION — Quorum and attendance
- CERTIFICATION — Proof and validation
- TEMPORALITY — Time-based conditions
- JURISDICTION — Scope and boundaries
- MANDATE — Election and authority
- CONFLICT — Resolution mechanisms
- PROVENANCE — Origin and attribution

**Why immutable:** These categories represent the constitutional universe. Adding is allowed. Changing categories is not.

#### 2. GovernanceSemanticCode (Value Object)

**Purpose:** Canonical semantic identifier

**Properties:**
- Code: Lowercase, normalized string (e.g., `quorum_minimum_met`)
- Immutable after creation
- Replay-safe equality via lowercase normalization

**Why immutable:** Semantics are referenced by code in historical snapshots. Changing codes breaks replay equivalence.

#### 3. GovernanceSemanticDefinition (Value Object)

**Fields:**
- Code
- Category
- CanonicalMeaning (Constitutional interpretation)
- ReplayImplications (Impact on historical decisions)
- LegitimacyImplications (Constitutional validity rules)
- IntroducedInDoctrineVersion (When registered)

**Invariants (enforced by VO constructor):**
- No field is empty
- Meaning is non-trivial
- Implications are specified
- Doctrine version is present

**Why immutable:** Historical replay depends on frozen semantic definitions.

#### 4. ImmutableSemanticEvolutionPolicy

**Purpose:** Enforce semantic immutability

**Guarantees:**
- ❌ Cannot change `canonicalMeaning` after publication
- ❌ Cannot change `replayImplications` after publication
- ❌ Cannot change `legitimacyImplications` after publication
- ❌ Cannot change `category` after publication
- ❌ Cannot remove semantics (null modification blocked)
- ✅ Can register new semantics
- ✅ Can deprecate (add lifecycle state in GEO-3.4E)

**Method signature:**
```php
public function validateEvolution(
    GovernanceSemanticDefinition $original,
    ?GovernanceSemanticDefinition $modified,
): void
```

Throws `SemanticEvolutionViolationException` on any violation.

#### 5. GovernanceSemanticRegistry

**Purpose:** Central semantic lookup and serialization

**Operations:**
- Register new semantic definitions
- Retrieve by code
- Detect duplicates
- Canonical serialization (deterministic JSON)

**Deterministic Ordering:** Lexicographic sort by code ensures:
- Identical registrations produce identical hashes
- Replay validation is order-independent
- Cross-machine consistency is guaranteed

**Serialization Format:**
```json
[
  {
    "code": "quorum_minimum",
    "category": "participation",
    "meaning": "Minimum participants required",
    "replay_implications": "Immutable in snapshot",
    "legitimacy_implications": "Decision invalid if not met",
    "introduced": "1.0"
  }
]
```

Uses: `JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES`

#### 6. GovernanceSemanticCollection (Immutable Collection)

**Purpose:** Version-safe semantic grouping

**Operations:**
- Add (returns new instance, never mutates original)
- Retrieve by code
- Count
- Canonical serialization
- Deterministic ordering

**Immutability guarantee:** `add()` returns a new collection; original is never modified.

---

## Why These Decisions

### Why Immutable?

Semantic mutations retroactively change constitutional meaning:

```
2026: MANDATE_ACTIVE = "Elected authority holding office"
↓
2028: MANDATE_ACTIVE = "Elected or delegated authority"  ← CORRUPTION
↓
Replay 2026 decision: Is it valid under new meaning? ← AMBIGUOUS
```

**Solution:** Once published, semantics are frozen. New semantics added as doctrine evolves.

### Why Deterministic Ordering?

Replay must be order-independent:

```
Registry A: Register [alpha, beta, gamma]
Registry B: Register [gamma, alpha, beta]

Hash(A) must equal Hash(B)
```

**Solution:** Always sort lexicographically before serialization.

### Why Tight Evolution Policy?

Categories, implications, and meanings are constitutional anchors. If they change:
- Historical decisions become ambiguous
- Replay certification fails
- Audit trails lose credibility
- Constitutional law becomes uncertain

**Solution:** Explicit policy that throws exceptions on dangerous mutations.

### Why Separate Registry from Snapshot?

Snapshots store **historical semantics at decision time**.
Registry contains **live semantic vocabulary**.

If they were merged:
- Changing registry would corrupt historical snapshots
- Replay would depend on current state
- Constitutional truth would become relative

**Solution:** Registry is append-only. Snapshots reference frozen semantics.

---

## Architectural Boundaries

### What Semantics Do (Semantic Canon Layer)

✅ Define constitutional vocabulary
✅ Register meanings exactly once  
✅ Prevent retroactive changes
✅ Support deterministic replay
✅ Provide immutable audit trail

### What Semantics Don't Do (Deferred to Later Phases)

❌ Manage doctrine lifecycle (GEO-3.4E: add semantic deprecation)
❌ Track semantic provenance (GEO-3.4E: add approval attribution)
❌ Certify semantic equivalence (GEO-3.4F: semantic groups)
❌ Evaluate semantic severity (GEO-3.4G: drift severity)
❌ Handle semantic versioning (GEO-3.5+: doctrine migration)

---

## Test Coverage

### Unit Tests (69 total across GEO-3.4D)

**Phase D — Semantic Foundation (35 tests)**
- Enum stability
- VO immutability
- Registry duplicate detection
- Deterministic ordering

**Phase E — Evolution Policy (7 tests)**
- Cannot change meaning
- Cannot change implications
- Cannot remove semantics
- Cannot mutate categories
- Correct exception messages

**Phase F — Collection (10 tests)**
- Immutable add operations
- Deterministic ordering
- Duplicate prevention
- Canonical serialization

**Phase G — Architecture Fitness (9 tests)**
- All classes final
- No Laravel imports
- No setters present
- Deterministic hashing
- Policy enforcement

**Phase H — Semantic Invariance (8 tests)**
- Same definition → identical serialization
- Ordering-independent hashes
- Evolution prevents drift
- Historical semantics unchanged
- Collection immutability preserves invariance

### Integration Tests (GEO-3.4E)

- **Taxonomy Review:** Verify 10 categories cover all governance concepts
- **Vocabulary Normalization:** Ensure naming consistency across semantics
- **Replay Drift Simulation:** Verify historical semantics survive doctrine evolution
- **Doctrine Mutation Threat Model:** Prove dangerous mutations are blocked

---

## Failure Scenarios Prevented

### Scenario 1: Meaning Drift

**Danger:**
```
2026 Snapshot: QUORUM = "≥50% attendance"
2028 Doctrine: QUORUM = "≥50% votes cast"
2026 Replay: Which interpretation is correct?
```

**Prevention:**
ImmutableSemanticEvolutionPolicy throws exception on meaning change.

### Scenario 2: Category Shift

**Danger:**
```
2026: DELEGATION (category: DELEGATION)
2028: DELEGATION (category: AUTHORITY)  ← Recategorized
Replay: Is delegation authority or delegation?
```

**Prevention:**
Policy blocks category mutations. New semantic created instead.

### Scenario 3: Implication Corruption

**Danger:**
```
2026: MANDATE_ACTIVE impacts legitimacy
2028: MANDATE_ACTIVE has no legitimacy impact
Replay: Was 2026 decision legitimate?
```

**Prevention:**
Policy blocks legitimacy implication changes.

### Scenario 4: Semantic Removal

**Danger:**
```
2026: QUORUM_MINIMUM registered
2028: QUORUM_MINIMUM deleted
Replay 2026: Quorum semantic undefined
```

**Prevention:**
Policy forbids null modifications (removal). Deprecation only (GEO-3.4E).

### Scenario 5: Non-Deterministic Ordering

**Danger:**
```
Registry A serialization: [alpha, beta, gamma] → Hash1
Registry B serialization: [gamma, alpha, beta] → Hash2
Hash1 ≠ Hash2  ← Same semantics, different hashes!
```

**Prevention:**
Lexicographic sort on all serializations guarantees Hash1 = Hash2.

---

## Future Evolution (Planned Phases)

### GEO-3.4E: Semantic Governance Stabilization

Add:
- `GovernanceSemanticLifecycle` (ACTIVE, DEPRECATED, SUPERSEDED, ARCHIVED, REVOKED)
- `SemanticProvenance` (introducedBy, approvedAt, authority)
- Semantic taxonomy normalization
- Replay drift severity classification

### GEO-3.4F: Semantic Equivalence Groups

Add:
- `SemanticEquivalenceGroup` (abstract type, no implementation yet)
- Future support for certified semantic equivalence
- Enables future semantic merging (not deletion)

### GEO-3.5+: Voting Semantics

Add domain-specific semantics:
- `VOTING_ELIGIBILITY`
- `QUORUM_REQUIREMENT`
- `DELEGATION_ALLOWED`
- `PROXY_AUTHORITY`
- `RECUSAL_GROUNDS`

All protected by same immutability policy.

---

## Governance and Change Control

### How to Add a Semantic

1. Define `GovernanceSemanticDefinition` with all fields
2. Register via `GovernanceSemanticRegistry.register()`
3. On commit: Include rationale in PR title/description
4. Testing: Add to semantic taxonomy review

### How to Deprecate a Semantic

1. (GEO-3.4E) Mark with lifecycle state `DEPRECATED`
2. Include `deprecatedAt` and `replacementSemanticCode`
3. Replay still uses original meaning (no corruption)
4. New code can use replacement semantic

### How to NOT Modify a Semantic

❌ You cannot change meaning
❌ You cannot change implications  
❌ You cannot change category
❌ You cannot remove semantics

If you discover a mistake in a published semantic:
- Create a new corrected semantic
- Deprecate the original
- Migrate forward (GEO-3.5+ migration strategy)

---

## Architectural Significance

**Before GEO-3.4D:**
```
Replay correctness depends on disciplined developers
```

**After GEO-3.4D:**
```
Replay correctness is enforced structurally
```

This is a **maturity boundary** — the platform moves from *relying on discipline* to *enforcing immutability at the type level*.

---

## References

- `GovernanceSemanticCategory.php` — Canonical vocabulary enum
- `GovernanceSemanticCode.php` — Semantic identifier VO
- `GovernanceSemanticDefinition.php` — Definition VO with field invariants
- `GovernanceSemanticRegistry.php` — Central registry with deterministic ordering
- `ImmutableSemanticEvolutionPolicy.php` — Policy enforcement
- `GovernanceSemanticCollection.php` — Immutable collection
- Test suite: `tests/Unit/Domain/Committee/Constitutional/Semantics/`

---

## Sign-Off

**Approved by:** Constitutional Governance Engine Architecture Review  
**Date:** 2026-05-08  
**Rationale:** Immutable semantic canon is the critical foundation for replay-safe semantic governance. Without this layer, semantic drift corrupts constitutional meaning. With it, historical governance truth is preserved across time and doctrine evolution.

**Next Phase:** GEO-3.4E Semantic Governance Stabilization Review
