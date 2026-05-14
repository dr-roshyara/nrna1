# Constitutional Mutation Resistance Tests (F3.2)

**Status:** Architectural anchoring phase (pre-implementation)  
**Phase:** F3.2 — Structural Mutation Resistance Layer  
**Date:** 2026-05-14  

---

## Core Principle

This system assumes:

> **The domain will be attacked by incorrect usage.**

Not maliciously.  
Not intentionally.  
But inevitably.

Therefore:

- correctness is not sufficient
- invariants must be **structurally enforced** (not conventionally enforced)
- invalid state must be **impossible to construct** (not just difficult)
- every loophole must be closed before implementation begins

---

## What This Suite Is

This is NOT a functional test suite.

It is a:

> **Formal constraint system over the domain state space**

It answers three questions:

1. **What MUST NEVER be possible?**
2. **What MUST ALWAYS hold true?**
3. **What MUST FAIL even if the code compiles?**

---

## Architectural Position

This test suite sits between:

```
F3.1 (Constitutional Behavior)
  ↓
F3.2 (Mutation Resistance) ← YOU ARE HERE
  ↓
F3.3 (Event Replay)
  ↓
F3.4 (Governance Policy)
  ↓
F3.5 (Read Projections)
```

**F3.2's job:** Guarantee that all downstream systems operate on **valid truth only**.

Downstream systems (F3.3, F3.4, F3.5) can assume the domain is structurally sound.

If F3.2 fails, everything downstream fails.

---

## Threat Model: Attack Vectors

We explicitly identify the following **mutation attack classes**:

### 1. Construction Attacks
Invalid objects created directly via constructors.

**Examples:**
- `new CommitteeAssociation(status: SUSPENDED, actorId: null)`
- `new CommitteeAssociation(status: TERMINATED, transitionReason: null)`
- Invalid timestamp combinations
- Status sequences that violate lifecycle rules

**Defense:** Constructor validation + factory methods

---

### 2. Mutation Attacks
State changed after creation through unintended side effects.

**Examples:**
- Attempt to modify `$episode->status` after creation
- Collection mutation: `$lineage->episodes[] = corrupted`
- Immutability bypass through reflection
- Hidden property setters

**Defense:** Readonly classes + deep immutability enforcement

---

### 3. Persistence Corruption
Invalid state reconstructed from database.

**Examples:**
- Load SUSPENDED without `suspension_reason`
- Load TERMINATED without `terminated_by_actor_id`
- Episode without required audit trail
- Invalid state combination in database

**Defense:** Hydration validation on load

---

### 4. Replay Corruption
Event stream produces invalid historical sequences.

**Examples:**
- Replay produces impossible state transitions
- State evolves differently from operational path
- Episode chain violates logical progression
- Determinism violation (same events → different states)

**Defense:** Replay reconstruction verification + sequencing validation

---

### 5. Event Integrity Violations
Events are incomplete, duplicated, or malformed.

**Examples:**
- Two `MembershipSuspended` events for one transition
- Missing event for state change
- Incomplete event payload (missing actorId, reason, timestamp)
- Event ordering violation

**Defense:** Event counting + payload verification + ordering assertions

---

### 6. Temporal Inconsistency
Episode chains violate logical lifecycle progression.

**Examples:**
- SUSPENDED → TERMINATED → ACTIVE (impossible)
- ACTIVE → ACTIVE (duplicate status)
- Orphaned episodes with no lineage
- Circular episode references

**Defense:** Lifecycle state machine validation + episode chain coherence checks

---

## Category Definitions & Coverage

### ConstructorSafety/
**Prevents invalid object creation at the source.**

Ensures:
- No SUSPENDED without actorId
- No TERMINATED without termination_reason
- No ACTIVE with unexpected audit fields
- No invalid timestamp combinations
- Constructor rejects invalid combinations loudly

Tests organized by entity:
- `CommitteeAssociationConstructorSafetyTest.php` — episode constructor validity
- `MembershipLineageConstructorSafetyTest.php` — lineage constructor validity

---

### Immutability/
**Ensures transitions never mutate historical state.**

Ensures:
- Transition methods return new instances
- Original instance remains unchanged
- Collections are not mutated
- Readonly semantics enforced across lifecycle
- Reflection-based property override is impossible

Tests:
- `CommitteeAssociationImmutabilityTest.php` — entity immutability
- `MembershipLineageImmutabilityTest.php` — aggregate immutability

---

### EventIntegrity/
**Ensures events are complete, singular, and correct.**

Ensures:
- Exactly one event per valid transition
- No duplicate events
- Event payloads are complete (all required fields)
- Event ordering is preserved
- Events are emitted before state is persisted

Tests:
- `MembershipEventEmissionTest.php` — one-event-per-transition enforcement
- `MembershipEventOrderingTest.php` — temporal event sequencing

---

### AggregateConsistency/
**Ensures lifecycle chains remain logically valid.**

Ensures:
- Episode history is coherent (valid transitions only)
- Status sequences obey state machine rules
- No impossible episode chains
- Historical continuity is maintained
- Replay reconstructs identical state

Tests:
- `MembershipLineageConsistencyTest.php` — episode chain validity
- `MembershipReplayConsistencyTest.php` — replay determinism

---

### HydrationIntegrity/
**Ensures persistence layer cannot inject invalid truth.**

Ensures:
- Invalid database state is rejected on load
- Hydration validates against domain rules
- Partial corruption is detected
- Replay from persisted events is deterministic
- Impossible state combinations fail loudly

Tests:
- `MembershipHydrationIntegrityTest.php` — database state validation
- `EventReplayIntegrityTest.php` — replay reconstruction safety

---

## Load-Bearing Guarantees

These tests enforce permanent guarantees:

1. **Events are canonical historical facts** (not notifications)
   - Consequence: F3.3 replay is deterministic
   - Consequence: F3.5 projections are correct

2. **Transitions are irreversible operations**
   - Consequence: domain state is never partially corrupted
   - Consequence: audit trail is complete

3. **State is reconstructible and deterministic**
   - Consequence: replay produces identical results
   - Consequence: temporal queries are reliable

4. **Invalid state is structurally impossible**
   - Consequence: downstream systems trust domain validity
   - Consequence: governance layer can focus on policy, not data validity

5. **Domain truth is never partially corrupted**
   - Consequence: constitutional auditing is reliable
   - Consequence: governance decision logs are trustworthy

---

## Relationship to F3 Roadmap

| Phase | Depends On | Is Depended On By |
|-------|-----------|------------------|
| **F3.1** (Constitutional Behavior) | F1/F2 | F3.2, F3.3 |
| **F3.2** (This: Mutation Resistance) | F3.1 | F3.3, F3.4, F3.5 |
| **F3.3** (Event Replay) | F3.2 | F3.4, F3.5 |
| **F3.4** (Governance Policy) | F3.2, F3.3 | F3.5 |
| **F3.5** (Read Projections) | F3.3, F3.4 | Downstream systems |

---

## Anti-Patterns This Suite Prevents

✗ **Silent invalid state construction**  
→ Test prevents: constructing SUSPENDED without actor

✗ **Accidental immutability violation**  
→ Test prevents: mutating episodes after creation

✗ **Event duplication or loss**  
→ Test prevents: emitting multiple events per transition

✗ **Replay inconsistency**  
→ Test prevents: state divergence during reconstruction

✗ **Invalid historical chains**  
→ Test prevents: impossible episode sequences

✗ **Audit trail corruption**  
→ Test prevents: loading state without audit metadata

✗ **Constitutional bypass**  
→ Test prevents: creating invalid state through any path

---

## Test Naming Convention

Every test name must use **constitutional threat language**, not technical language.

### CORRECT (Threat Language)

```php
// Rejects / Prevents / Detects
it_rejects_construction_of_suspended_without_actor_id()
it_prevents_immutability_violation_through_reflection()
it_detects_duplicate_event_emission()
it_guarantees_single_event_per_transition()
it_preserves_episode_chain_coherence_across_replay()
```

### WRONG (Technical/CRUD Language)

```php
// Constructor / Update / Method
test_suspended_constructor()
test_update_status()
test_event_service_logic()
test_database_query()
```

The language itself is a declaration of what we protect.

---

## F3.2 Definition of Done

F3.2 is complete when:

- [x] Directory structure created (boundaries declared)
- [x] README established (threat model explicit)
- [ ] Attack surface enumerated (all vectors documented)
- [ ] RED test suite designed (all tests fail initially)
- [ ] All tests fail for correct reasons
- [ ] All failures are resolved structurally
- [ ] No business logic leaks into governance layer (F3.4)
- [ ] No hidden mutation paths exist
- [ ] Downstream systems (F3.3, F3.4, F3.5) can assume valid domain

---

## Running This Test Suite

### Run all F3.2 tests
```bash
php artisan test tests/Unit/Constitutional/Membership/MutationResistance/ --no-coverage
```

### Run single category
```bash
php artisan test tests/Unit/Constitutional/Membership/MutationResistance/ConstructorSafety/ --no-coverage
```

### Run with verbose output
```bash
php artisan test tests/Unit/Constitutional/Membership/MutationResistance/ -v --no-coverage
```

---

## Adding Tests to This Suite

When adding new tests:

1. **Identify the attack vector** — What invalid state are we preventing?
2. **Name reflects the guarantee** — Use threat language (rejects, prevents, detects, guarantees)
3. **Place in appropriate category** — Which mutation class does this address?
4. **Make it fail first** — RED test must fail before implementation
5. **Document the loophole** — Explain why this attack is possible without the protection
6. **Update this README** — Keep threat model current

---

## Example: Adding a Constructor Safety Test

```php
// Location: tests/Unit/Constitutional/Membership/MutationResistance/ConstructorSafety/CommitteeAssociationConstructorSafetyTest.php

/** @test */
public function it_rejects_construction_of_suspended_without_actor_id(): void
{
    // This test documents a specific attack vector:
    // "Can someone create SUSPENDED status without actorId?"
    
    // Initially this test FAILS (RED phase)
    // Because constructor currently allows this invalid combination
    
    $this->expectException(\InvalidArgumentException::class);
    
    new CommitteeAssociation(
        associationId: AssociationId::generate(),
        memberId: $this->createMemberId(),
        committeeId: $this->createCommitteeId(),
        associationType: ApplicationReason::RESIDENCE,
        associatedAt: now(),
        status: MembershipStatus::SUSPENDED,
        actorId: null,  // ← ATTACK: Missing required audit field
        transitionReason: 'Some reason',
        transitionedAt: now(),
    );
}
```

---

## Foundation Laid

This README and structure declaration commits the project to:

1. **Explicit threat model** — We've named the attacks
2. **Structural defense** — We're not avoiding invalid state, making it impossible
3. **Constitutional semantics** — This is governance, not CRUD
4. **Downstream reliability** — F3.3/F3.4/F3.5 can trust domain validity
5. **Architectural clarity** — Structure mirrors intent

---

## Next Phase (After This is Committed)

**F3.2 Attack Surface Enumeration**

We will systematically document:

- Every possible way to create invalid state
- Every loophole that needs closing
- Every invariant that needs protection
- Complete coverage of mutation attack vectors

Only THEN write RED tests.

---

## Final Statement

This suite ensures:

> **The domain cannot lie, even if misused.**

That is the constitutional guarantee.

---

**Status:** ARCHITECTURAL ANCHORING COMPLETE  
**Next:** Attack surface enumeration and RED test design  
**Responsibility:** All F3.2 tests must maintain threat language and category discipline
