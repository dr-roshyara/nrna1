# Replay Aggregate Discovery

**Phase:** P10 — Aggregate Discovery
**Date:** 2026-05-29
**Prerequisite:** DD.3b complete, P9 approved
**Status:** Initial — discovering consistency boundaries, not designing classes

## Purpose

Identify the consistency boundaries inside the Replay bounded context. Replay answers: *Can we prove that the same evidence produces the same sovereign outcome across runtimes?*

---

## Current Type Inventory

### Replay Domain Types

| Type | File | Role |
|------|------|------|
| `ReplaySession` | `Domain/Election/Replay/ReplaySession.php` | Session lifecycle — sealed → replayed → certified/diverged |
| `ReplayCertification` | `Domain/Election/Replay/ReplayCertification.php` | Certification result — matched or diverged |
| `ReplayEvidenceEnvelope` | `Domain/Election/Replay/ReplayEvidenceEnvelope.php` | Sealed evidence container with compatibility version |
| `ReplayCompatibilityVersion` | `Domain/Election/Replay/ReplayCompatibilityVersion.php` | Schema version marker |
| `ReplayAssertion` | `Domain/Election/Replay/ReplayAssertion.php` | Expected outcome binding |

### Consumed from Evidence BC

| Type | Source |
|------|--------|
| `ConstitutionalEvidenceSnapshot` | Evidence BC — frozen evidence to certify |

---

## Invariant Review (from BusinessInvariantCatalog P7a)

| ID | Invariant | Currently Enforced? | Aggregate Implication |
|----|-----------|-------------------|----------------------|
| REP-1 | Replay determinism — same evidence → same outcome | ✅ Test (F9) | The certification result must be bound to the evidence hash |
| REP-2 | Session state transitions: sealed → replayed → certified/diverged | ✅ Runtime exception | The session lifecycle must be a state machine — cannot certify without replaying |
| REP-3 | Certification immutability | ✅ `readonly` class | Once created, certification is never modified |

---

## Consistency Boundary Analysis

### Question 1: Must Session State and Certification Change Together?

```
If certification is issued, must session state also update?

Answer: YES. certify() transitions the session to certified.
These are the same transactional operation — you can't have
a certification without a session state transition.
```

**Implication:** Session and certification are in the same consistency boundary. `certify()` atomically creates the certification AND transitions the session.

---

### Question 2: Must Assertion and Certification Change Together?

```
Must recording an assertion happen in the same transaction as certification?

Answer: NO. Assertion is recorded BEFORE certification (during replay).
Certification happens AFTER replay is complete.
They are different lifecycle phases.
```

**Implication:** Assertion is a separate consistency boundary from certification. Assertion is recorded when replay begins; certification is issued when replay completes.

---

### Question 3: Can Evidence Envelope Change Independently of Session?

```
Can the evidence envelope be sealed without affecting the session?

Answer: YES. The envelope wraps evidence BEFORE the session opens.
The session references the envelope but does not own it.
```

**Implication:** `ReplayEvidenceEnvelope` is a value object wrapping evidence from Evidence BC. It's a container, not an aggregate root.

---

### Question 4: Must the Compatibility Version Change with Certification?

```
If schema version changes, must certification be re-issued?

Answer: The compatibility version is part of the evidence envelope.
It's fixed when evidence is received. Certification references it
for verification but does not own it.
```

**Implication:** `ReplayCompatibilityVersion` is a value object within the evidence envelope, not an independent entity.

---

## Candidate Aggregate

### ReplaySession Aggregate

**Root:** `ReplaySession`

This is the single aggregate in the Replay context. It owns the full replay lifecycle from session open through certification.

```
ReplaySession (AGGREGATE ROOT)
│
├── identity: SessionId (value object — UUID)
├── state: SessionState (sealed → replayed → certified | diverged)
├── evidenceEnvelope: ReplayEvidenceEnvelope (value object — sealed evidence container)
├── assertion: ReplayAssertion | null (value object — expected outcome, set during replay)
├── certification: ReplayCertification | null (value object — result, set on completion)
├── openedAt: SessionTimestamp (value object — when session was created)
└── completedAt: SessionTimestamp | null (value object — when certification/divergence occurred)
│
STATE MACHINE (guarded by REP-2):
  sealed ──▶ recordAssertion() ──▶ replayed ──▶ certify() ──▶ certified
                                                ──▶ recordDivergence() ──▶ diverged
│
IMMUTABLE after terminal state (certified or diverged).
```

### Value Objects Within the Aggregate

| Value Object | Role | State-Dependent? |
|-------------|------|-----------------|
| `ReplayEvidenceEnvelope` | Frozen evidence wrapper | Created at session open, never changes |
| `ReplayAssertion` | Expected outcome binding | Set during replay phase (nullable before) |
| `ReplayCertification` | Verification result | Set at terminal state (nullable before) |
| `ReplayCompatibilityVersion` | Schema marker | Fixed for the session lifetime |

### Invariants Protected

| Invariant | How Protected |
|-----------|--------------|
| REP-1: Replay determinism | `ReplayCertification` compares assertion against actual outcome deterministically. Same evidence envelope → same certification result. |
| REP-2: State transitions | `recordAssertion()` throws if state !== sealed. `certify()` throws if state !== replayed. `certify()` throws if already certified. |
| REP-3: Certification immutability | `ReplayCertification` is a `readonly` value object. Once created, never modified. |

---

## Why One Aggregate, Not Multiple

| Factor | One Aggregate | Multiple Aggregates |
|--------|--------------|---------------------|
| **State machine** | Session IS the state machine — natural root | Splitting would require distributed transaction across aggregates |
| **Assertion and certification** | Both are lifecycle phases of the same session | They're temporally ordered — separate aggregates could allow asserting without certifying |
| **Lifetime** | Replay session has a bounded lifetime (open → terminal) | Splitting creates orphaned certifications or assertions |
| **Consistency** | `certify()` transitions session AND creates certification atomically | Separate aggregates could get out of sync |

**One aggregate is the correct model.** The session lifecycle IS the consistency boundary.

---

## Current Code Alignment

The existing implementation already follows this model closely:

```php
// Current ReplaySession — already aligned with aggregate model:
final class ReplaySession
{
    public function recordAssertion(ReplayAssertion $assertion): void
    {
        // Guards: only when state === sealed
        // Transitions: sealed → replayed
    }
    
    public function certify(): ReplayCertification
    {
        // Guards: only when state === replayed
        // Transitions: replayed → certified
        // Creates: ReplayCertification atomically
    }
}
```

**Gap:** `ReplayCertification` is currently a standalone `readonly` class created inside `certify()`. This is correct behavior but the coupling to session state is convention-based rather than structural. The aggregate model formalizes what the code already does.

---

## Lifecycle

```
Sealed state:
  ReplaySession created with evidence envelope
  No assertion recorded yet
  No certification issued yet
       │
       ▼ recordAssertion(ReplayAssertion)
Replayed state:
  Expected outcome bound
  Ready for verification
  Certification not yet issued
       │
       ├── certify()
       │     ▼
       │   Certified (terminal):
       │     ReplayCertification::matched() — assertion matches outcome
       │     Session is immutable
       │
       └── recordDivergence()
             ▼
           Diverged (terminal):
             ReplayCertification::diverged() — assertion differs from outcome
             Session is immutable
```

---

## What This Means for the Current Codebase

### Minimal Changes Required

The existing `ReplaySession` class (5 methods, 32 tests) is already close to the aggregate model. Key refinements:

| Current | Aggregate-Aware |
|---------|----------------|
| `ReplaySession` as standalone class | Same — it IS the aggregate root |
| `ReplayCertification` as standalone value object | Same — value object within the aggregate |
| State guards via `RuntimeException` | Same — structural enforcement |
| No session identity | Add `SessionId` value object |

### Replay Certification Is NOT an Aggregate

The Senior Architect correctly rejected Certification as an independent BC. The aggregate analysis confirms: `ReplayCertification` is a value object within `ReplaySession`, created atomically during `certify()`. It has no independent lifecycle.

---

## References

| Artifact | Section |
|----------|---------|
| BusinessInvariantCatalog.md (P7a) | REP-1, REP-2, REP-3 |
| ConstitutionalContextMap.md (P2) | Replay BC — Open Host Service |
| PublishedLanguageMatrix.md (P6) | `ReplayCertification` as published language |
| ContextDependencyRules.md | ADR-5: Replay → Evidence (published language, read-only) |
| CoreDomainProtection.md | Replay classified as Core-Supporting |
