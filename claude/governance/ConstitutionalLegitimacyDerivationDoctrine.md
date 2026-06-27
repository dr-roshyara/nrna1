# Constitutional Legitimacy Derivation Doctrine

**Status:** AUTHORITATIVE
**Date:** 2026-05-28
**Phase:** Pre-D.0.3a Requirement (mandated by C.5e F-4 + Senior Architect Review)
**Supersedes:** Diffuse principles across C.5a–C.5i audits

---

## Purpose

This doctrine consolidates all principles governing **who may derive legitimacy, where it
may be derived, what evidence may participate, and what topology may NOT participate.**

It is the authoritative reference for:
- `ConstitutionalLegitimacyDecision` implementation (D.0.3a prerequisite)
- Future resolver modifications
- Constitutional dispute resolution
- Sovereign migration design

---

## 1. Legitimacy Derivation Exclusivity

### Who May Derive Legitimacy

```
ONLY: PolicySequence → TrustPolicyEvaluator → VotingTrustResult
```

**Forbidden derivation authorities:**
- Controllers (inline derivation)
- Middleware (blocking without PolicySequence)
- Observers (event-driven derivation)
- Jobs / queued workers (async derivation)
- UI components (projection-layer derivation)
- Feature flags (bypass-derived legitimacy)
- Caches (stale-authority replay)

### Why This Matters

Any component that derives legitimacy outside PolicySequence creates a
**parallel sovereignty path** — an authority that can diverge from the
constitutional resolver, survive migration, and resist retirement.

Parallel sovereignty paths are **constitutionally invisible** because:
- They pass standard tests (they are tests of a different resolver)
- They survive C.4 equivalence proofs (equivalence only proved for PolicySequence)
- They persist after D-phase deletion (they are not in the deletion target)

---

## 2. Where Legitimacy May Be Derived

### Permitted Location

```
app/Application/Election/Security/PolicySequence.php
  └─ evaluate(TrustCapabilityContext $ctx): VotingTrustResult
```

This is the ONLY location where `VotingTrustResult` with sovereign implications
may be produced.

### Permitted Callers

| Caller | Context | Status |
|--------|---------|--------|
| `TrustPolicyEvaluator::evaluate()` | Runtime evaluation | ✅ Permitted |
| `ElectionCapabilityResolver::resolve()` | Capability projection | ✅ Permitted (projection-only) |
| `ConstitutionalLegitimacyDecision::decide()` | D.0.3a enforcement | ✅ Permitted (future) |
| Tests (unit + feature) | Verification | ✅ Permitted |

### Forbidden Callers

| Caller | Reason |
|--------|--------|
| `VoteController` (inline) | Controller-layer derivation — violates CFB-1 |
| `ValidateVotingIp` middleware | Middleware sovereignty — documented F-1 |
| Any `Job` class | Async temporal drift — violates evidence freeze |
| Any `Listener` class | Event-topology sovereignty — violates observation doctrine |
| Admin commands | Unguarded authority bypass |

---

## 3. What Evidence May Participate

### Permitted Evidence Sources

All evidence must be assembled by `TrustSnapshotAssembler` before PolicySequence
receives it. Evidence is frozen at assembly time.

| Evidence Type | Class | Role |
|---------------|-------|------|
| Network trust | `NetworkTrustEvidence` | IP continuity + density |
| Device trust | `DeviceTrustContext` | Fingerprint binding |
| Attestation | `VerificationAttestationRecord` | Registrar verification |
| Session continuity | `VotingSessionTrustContinuity` | Session binding |
| Election context | `Election` model | Configuration |

### Evidence Immutability Rule

```
Evidence assembled by TrustSnapshotAssembler is frozen.
PolicySequence MUST NOT re-query database, cache, or external state.
```

This is the **temporal sovereignty isolation invariant** — legitimacy derived at
time T must be reproducible at time T+n from the same evidence snapshot.

### Forbidden Evidence Patterns

| Pattern | Violation |
|---------|-----------|
| Re-querying DB inside PolicySequence | Temporal drift |
| Reading cache inside PolicySequence | Stale authority |
| Accepting mutable array/object | Non-frozen evidence |
| Time-dependent derivation | Replay unsafety |
| Cross-election evidence sharing | Scope contamination |

---

## 4. What Topology May NOT Participate

### Forbidden Topology Patterns

| Pattern | Why Forbidden |
|---------|--------------|
| Middleware authority blocking | Topology-derived legitimacy — position-8 problem |
| Controller early returns | Hidden sovereignty branch |
| Event escalation | Listener-derived authority |
| Queued legitimacy decisions | Async temporal drift |
| Cache-derived authority | Stale sovereignty replay |
| UI legitimacy gating | Projection-layer sovereignty — P-1/P-2 findings |
| Feature-flag bypass | Temporary becomes permanent |
| Admin shortcut routes | Authority bypass without constitutional guard |

### The Topology Neutrality Principle

```
Sovereign legitimacy must be topology-neutral:
The SAME evidence must produce the SAME outcome
regardless of which code path reached PolicySequence.
```

This means: changing middleware order, controller refactoring, or
event restructuring MUST NOT change legitimacy outcomes.

---

## 5. Replay Guarantees

### Required Properties

| Property | Invariant |
|----------|-----------|
| Determinism | Same evidence → same VotingTrustResult |
| Time independence | Hash function must not depend on current time |
| Election scoping | Hash(IP, election_A) ≠ Hash(IP, election_B) |
| Whitespace normalization | Hash(" 1.2.3.4 ") = Hash("1.2.3.4") |
| Node independence | Result identical across hosts, timezones, workers |

### Replay Protocol

```
1. Freeze evidence snapshot (TrustSnapshotAssembler)
2. Derive legitimacy (PolicySequence)
3. Record result + evidence fingerprint
4. Replay: reconstruct snapshot → re-evaluate → compare fingerprints
```

A legitimacy outcome is **replay-certified** if re-evaluation of the same
evidence snapshot produces the same `VotingTrustResult.evaluationState`.

---

## 6. Exclusivity Guarantees

### The Resolver Exclusivity Invariant

```
For any voter action requiring participation legitimacy:

canVote = PolicySequence.evaluate(evidence).isSufficient()

and ONLY canVote governs enforcement.
```

### What "Exclusivity" Prevents

Without exclusivity, multiple sovereignty paths can produce different answers
for the same voter:

- `ValidateVotingIp` says BLOCK
- `PolicySequence` says ALLOW
- Controller inline logic says BLOCK
- Feature flag says ALLOW

This **sovereignty divergence** is undetectable without structured telemetry (D.1).

Exclusivity collapses all paths to one: PolicySequence.

### Enforcement Through ConstitutionalLegitimacyDecision (D.0.3a)

The missing class (F-4) that makes exclusivity enforceable:

```php
final class ConstitutionalLegitimacyDecision
{
    public function __construct(
        private readonly PolicySequence $policySequence,
        private readonly TrustSnapshotAssembler $assembler,
    ) {}

    public function decide(Election $election, User $user): LegitimacyOutcome
    {
        $context = $this->assembler->assemble($election, $user);
        $result  = $this->policySequence->evaluate($context);

        return LegitimacyOutcome::fromTrustResult($result);
    }
}
```

This class:
1. Is the ONLY permitted caller of `LegitimacyOutcome::fromTrustState()`
2. Is the single verifiable target for F4 resolver exclusivity invariant
3. Replaces all inline derivation at VoteController:1552 and similar

---

## 7. Rollback Guarantees

### Rollback Triggers (Constitutional)

| Trigger | Detection | Action |
|---------|-----------|--------|
| Sovereign mismatch | PolicySequence ≠ enforcement | Block D.0.3b, dual sovereignty mode |
| Replay divergence | Snapshot hash mismatch | F3 test failure → full rollback |
| Topology reorder | Middleware position change | Detect + alert |
| Nondeterministic hash | Time-dependent output | Fail F3 → rollback |

### Rollback Action

```
Automatic return to dual sovereignty:
  VOTING_CONSTITUTIONAL_MODE = false
  ValidateVotingIp restored to active blocking
  D.0 phase paused
  D1_DualSovereigntyDriftReport generated
```

---

## 8. Observation vs. Sovereignty Distinction (Critical)

This is the most frequently violated boundary.

### Observation (Permitted for Overlays)

```
Overlays observe evidence.
They emit OverlayObservationSummary with signalType.
They do NOT produce legitimacy decisions.
They do NOT call isSufficient() or deny() on their own signal.
```

### Sovereignty (Permitted Only for PolicySequence)

```
PolicySequence interprets observations.
It produces VotingTrustResult with evaluationState.
It is the ONLY component allowed to decide legitimacy.
```

### Violation Pattern

```php
// FORBIDDEN — overlay deriving sovereignty
class MyOverlay {
    public function observe(...): ObservationSummary {
        if ($inconsistent) {
            return ObservationSummary::deny(); // ← SOVEREIGNTY VIOLATION
        }
        return ObservationSummary::stable();   // ← OBSERVATION: correct
    }
}
```

The correct form is always: emit a signal (CONTEXT_STABLE / EVIDENCE_INCONSISTENT),
never emit a decision (ALLOW / DENY / BLOCK).

---

## 9. Sovereignty Monotonicity Rule

Formal invariant (from C.5 plan):

```
For any evidence set E and any observation O:
  If evaluate(E) = INSUFFICIENT_EVIDENCE
  Then evaluate(E ∪ {O}) = INSUFFICIENT_EVIDENCE
```

**Additional CONTEXT_STABLE signals NEVER cancel an EVIDENCE_INCONSISTENT finding.**

This prevents:
- Compensating evidence scoring
- Heuristic reconciliation
- Probabilistic legitimacy
- Reputation accumulation

---

## 10. Constitutional Algebra Boundary

Observations are NOT arithmetic operands.

**Forbidden:**
- Weighted averaging
- Signal balancing
- Confidence accumulation
- Evidence cancellation
- Observation subtraction

**Allowed:**
- Preservation (observations maintain state)
- Classification (observations categorized)
- Deterministic interpretation (evidence → legitimacy through PolicySequence only)
- Explicit precedence (only through resolver)

```
Legitimacy derives from constitutional interpretation,
not observational arithmetic.
```

---

## Doctrine Summary

| Rule | Statement |
|------|-----------|
| **Exclusivity** | Only PolicySequence may derive legitimacy |
| **Location** | Only `PolicySequence::evaluate()` produces sovereign VotingTrustResult |
| **Evidence freeze** | Evidence is frozen at TrustSnapshotAssembler; never re-queried |
| **Topology neutrality** | Legitimacy outcome must not depend on call topology |
| **Replay safety** | Same evidence always produces same outcome |
| **Monotonicity** | INSUFFICIENT_EVIDENCE survives additional evidence |
| **Algebra boundary** | Observations are not arithmetic operands |
| **Observation separation** | Overlays observe; PolicySequence decides |

---

## Relationship to D.0.3a

`ConstitutionalLegitimacyDecision` (F-4) is the **enforcement artifact** of this doctrine.

It implements exclusivity, encapsulates the derivation site, and provides a
verifiable target for F4 fitness function testing.

Without this class:
- VoteController retains inline derivation (distributed authority)
- F4 has no single enforcement target
- D.0.3a cannot achieve resolver exclusivity

With this class:
- All derivation converges to one class
- F4 can verify with precision
- D.0.3a enforcement is constitutionally complete

---

*This doctrine consolidates principles from C.5a–C.5i audits into a single authoritative
reference. Future engineers should treat any deviation from these rules as a constitutional
sovereignty violation requiring audit, not merely a refactoring concern.*
