# Legitimacy Aggregate Discovery

**Phase:** P10 — Aggregate Discovery
**Date:** 2026-05-29
**Prerequisite:** DD.3b complete, P9 approved
**Status:** Initial — discovering consistency boundaries, not designing classes

## Purpose

Identify the true consistency boundaries inside the Legitimacy bounded context. Aggregates are consistency boundaries, not classification labels. This document answers: *What must change together inside Legitimacy?*

---

## Current Type Inventory

### Legitimacy Domain Types

| Type | File | Role |
|------|------|------|
| `LegitimacyOutcome` | `Domain/Election/Security/LegitimacyOutcome.php` | Sovereign enum (Allowed, Denied, Deferred, Investigate) |
| `ConstitutionalLegitimacyDecision` | `Application/Election/Security/ConstitutionalLegitimacyDecision.php` | Sole resolver — maps `VotingTrustResult` → `LegitimacyOutcome` |

### Types Legitimacy Consumes (from Evidence/Evaluation)

| Type | Source Context | Role |
|------|---------------|------|
| `VotingTrustResult` | Evidence (Evaluation subdomain) | Trust evaluation result with state, reason, policy sequence |
| `TrustEvaluationState` | Evidence | Evaluation state (SUFFICIENT, INSUFFICIENT, REVIEW_REQUIRED, INCONCLUSIVE) |
| `EvaluationEnvelope` | Evidence | Sealed transport of evaluation result + observations + snapshot |

---

## Invariant Review (from BusinessInvariantCatalog P7a)

| ID | Invariant | Currently Enforced? | Aggregate Implication |
|----|-----------|-------------------|----------------------|
| LEG-1 | Only `ConstitutionalLegitimacyDecision` may derive `LegitimacyOutcome` | ✅ F4, F10 | Means the resolver is the sole factory — the aggregate root must be created through the resolver |
| LEG-2 | Each decision derives from exactly one policy sequence evaluation | ✅ Architectural | Means the decision references exactly one evaluation — a 1:1 consistency boundary |
| LEG-3 | Evidence frozen at decision time | ⚠️ Convention | Decision timestamp must be >= evidence snapshot timestamp — temporal invariant |
| LEG-4 | Sovereignty monotonicity — more observations never weaken insufficiency | ✅ F5 | Architectural invariant, not aggregate-internal |
| LEG-5 | Outcome is immutable after derivation | ✅ PHP enum | `LegitimacyOutcome` is a backed enum — structurally immutable |

---

## Consistency Boundary Analysis

### Question 1: Must Outcome and Evidence Reference Change Together?

```
If outcome changes from Denied to Allowed,
must the evidence reference also change?

Answer: YES. A different outcome implies a different evaluation.
If outcome and evidence reference could change independently,
a decision could claim "Allowed" but reference contradictory evidence.
```

**Implication:** Outcome and evidence reference are in the same consistency boundary.

---

### Question 2: Must Outcome and Timestamp Change Together?

```
If outcome changes, must the timestamp also update?

Answer: YES. A decision is a point-in-time fact.
The outcome and the "when" are inseparably bound.
```

**Implication:** Outcome and timestamp are in the same consistency boundary.

---

### Question 3: Must Outcome and Reason Change Together?

```
If outcome changes, must the reason also change?

Answer: YES. Every outcome exists because of a specific reason.
An "Allowed" with a "NETWORK_EVIDENCE_INSUFFICIENT" reason is contradictory.
```

**Implication:** Outcome and reason are in the same consistency boundary.

---

### Question 4: Can Decision Reason Exist Without a Decision?

```
Can we record "why someone would be denied" without recording a denial?

Answer: NO in the Legitimacy context.
Evaluation reasons exist in Evidence/Evaluation (EVL-2).
Legitimacy only has reasons as part of a decision.
```

**Implication:** Reason is a value object within the decision aggregate, not an independent entity.

---

### Question 5: Is a Decision Without an Outcome Meaningful?

```
Can we have a "pending" decision with no outcome?

Answer: NO. A legitimacy decision IS its outcome.
The resolver produces the outcome atomically.
```

**Implication:** The aggregate root is created atomically with the outcome. No partial-creation lifecycle.

---

## Candidate Aggregate

### LegitimacyDecision Aggregate

**Root:** `LegitimacyDecision`

This is the only aggregate needed inside the Legitimacy context. It captures the sovereign act of deciding.

```
LegitimacyDecision (AGGREGATE ROOT)
│
├── identity: DecisionId (value object — UUID)
├── outcome: LegitimacyOutcome (value object — Allowed|Denied|Deferred|Investigate)
├── decidedAt: DecisionTimestamp (value object — DateTimeImmutable)
├── evaluationHash: string (reference to the EvaluationEnvelope hash)
├── reasonCode: string (the EvaluationReasonCode that led to this outcome)
├── policySequenceId: string (which policy sequence produced the evaluation)
└── constitutionalHash: string (hash of all fields above — replay verification)

IMMUTABLE after creation.
Created exclusively by ConstitutionalLegitimacyDecision.
```

### Invariants Protected by This Aggregate

| Invariant | How Protected |
|-----------|--------------|
| LEG-1: Resolver exclusivity | `LegitimacyDecision` can only be created via `ConstitutionalLegitimacyDecision::decide()`. The `LegitimacyOutcome` enum is never created independently — it's embedded in the decision. |
| LEG-2: One policy sequence per decision | The `policySequenceId` field references exactly one sequence. No mechanism exists to merge multiple evaluations. |
| LEG-3: Evidence frozen at decision | `decidedAt >= evaluationTimestamp` enforced at construction. Constructor receives the evaluation timestamp and asserts the temporal invariant. |
| LEG-5: Outcome immutability | `LegitimacyDecision` is a `readonly` class. All properties are `private readonly`. No setters. |

### What Is NOT in This Aggregate

| Concept | Why Excluded | Where It Belongs |
|---------|-------------|------------------|
| `EvaluationEnvelope` | Crosses context boundary as published language; owned by Evidence | Evidence BC |
| `VotingTrustResult` | Evaluation output consumed by resolver; owned by Evidence subdomain | Evidence BC |
| `PolicySequence` | Orchestration logic, not decision data | Application layer (Evidence) |
| `OverlaySignal` | Non-sovereign observation; owned by Observation BC | Observation BC |
| `ConstitutionalEvidenceSnapshot` | Evidence preservation; owned by Evidence BC | Evidence BC |

### Aggregate Relationships (External)

```
Evidence BC                          Legitimacy BC
─────────────────                    ─────────────────
EvaluationEnvelope ──▶ consume ──▶  LegitimacyDecision (root)
(published language)                 │
                                     ├── outcome: LegitimacyOutcome
                                     ├── evaluationHash: references envelope
                                     └── constitutionalHash: replay verification
```

---

## Lifecycle

```
1. ConstitutionalLegitimacyDecision.decide(VotingTrustResult)
       │
       ▼
2. Create LegitimacyDecision {
     id: DecisionId::generate(),
     outcome: match(trustResult.evaluationState),
     decidedAt: now(),
     evaluationHash: trustResult.envelopeHash,
     reasonCode: trustResult.reasonCode,
     policySequenceId: trustResult.sequenceId,
     constitutionalHash: computeHash(...)
   }
       │
       ▼
3. LegitimacyDecision is published:
     - Persisted for audit trail
     - LegitimacyEvaluated event emitted (sovereign event, immutable)
     - Outcome flows to Governance for enforcement
       │
       ▼
4. LegitimacyDecision is NEVER modified after creation
```

### Key Design Points

- `LegitimacyDecision` is created in a valid state atomically — no setters, no stepwise construction
- The `constitutionalHash` covers all fields, enabling replay certification
- The resolver (`ConstitutionalLegitimacyDecision`) is the **sole factory** for the aggregate root
- After creation, the aggregate is read-only for all consumers

---

## What This Means for the Current Codebase

### Current State

```php
// ConstitutionalLegitimacyDecision::decide() currently returns just the enum:
public function decide(VotingTrustResult $trustResult): LegitimacyOutcome
{
    return match (...) {
        TrustEvaluationState::SUFFICIENT_EVIDENCE => LegitimacyOutcome::Allowed,
        // ...
    };
}
```

### Aggregate-Aware State (Future)

```php
// Would return the aggregate root, not just the enum:
public function decide(VotingTrustResult $trustResult): LegitimacyDecision
{
    return LegitimacyDecision::derive(
        id: DecisionId::generate(),
        outcome: match (...) { ... },
        decidedAt: new DecisionTimestamp(new \DateTimeImmutable()),
        evaluationHash: $trustResult->getEnvelopeHash(),
        reasonCode: $trustResult->reasonCode->value,
        policySequenceId: $trustResult->sequenceId,
    );
}
```

The caller then accesses both the outcome (for enforcement) and the full decision record (for audit/replay).

---

## Risk Assessment

| Risk | Severity | Mitigation |
|------|----------|-----------|
| Over-modeling — creating an aggregate where a simple value object suffices | Medium | `LegitimacyDecision` is the minimum consistency boundary that protects LEG-1 through LEG-5. A simpler model (just the enum) cannot protect LEG-2 or LEG-3. |
| Performance — creating a full decision object for every evaluation | Low | The decision object is lightweight (7 fields, no DB persistence required at this stage). |
| Coupling — Legitimacy BC depends on Evidence BC types | Already exists | This is by design (ADR-2: Legitimacy → Evidence via published language). No new coupling is introduced. |

---

## References

| Artifact | Section |
|----------|---------|
| BusinessInvariantCatalog.md (P7a) | LEG-1 through LEG-5 — invariants this aggregate must protect |
| ConstitutionalContextMap.md (P2) | Legitimacy BC mission and boundaries |
| PublishedLanguageMatrix.md (P6) | EvaluationEnvelope as cross-boundary type |
| CoreDomainProtection.md | P-CORE rules — maximum protection for Legitimacy |
| ContextDependencyRules.md | ADR-2: Legitimacy → Evidence (published language only) |
| EvaluationAutonomyAssessment.md | Evaluation ⊂ Evidence — Legitimacy consumes from Evidence, not from Evaluation directly |
