# Constitutional Ubiquitous Language

**Status:** Domain-Algebra Specification  
**Phase:** DD.1 — Domain-Driven Design Hardening (parallel to D.0.3)  
**Authority:** Senior Architect Review + DDD Review (2026-05-28)  
**Supersedes:** Doctrine-only definitions in C.5a–C.5i audits  
**Relationship:** Implements concepts defined in `ConstitutionalLegitimacyDerivationDoctrine.md`, `ConstitutionalObservationDoctrine.md`, `CanonicalVocabularyRules.md`, `SemanticBoundaryMap.md`

---

## Purpose

This document defines the **constitutional domain language** with the precision required for DDD aggregate design. Every term has:

1. A precise definition (what it IS)
2. A counter-example (what it is NOT)
3. An aggregate boundary (where it lives)
4. A lifecycle state (how it changes over time)
5. A code artifact mapping (classes, interfaces, value objects)

This is the bridge from **doctrine-heavy governance** to **model-driven constitutional algebra**.

---

## Core Domain Concepts

### 1. Evidence

| Aspect | Definition |
|--------|-----------|
| **IS** | An immutable constitutional fact frozen at evaluation time. Evidence is observed, hashed, and sealed. |
| **IS NOT** | A mutable database column, a log entry, a cached value that can change between evaluations. |
| **Lifecycle** | Created at evaluation boundary → hashed → frozen in snapshot → preserved for replay → never mutated |
| **Code artifact** | `NetworkTrustEvidence`, `DeviceTrustContext`, `VerificationAttestationRecord`, `VotingSessionTrustContinuity`, `ParticipationEligibilityEvidence` |
| **Aggregate** | `EvidenceAggregate` — sealed container of frozen facts |

**Invariant:** Evidence frozen at time T must reproduce identical hash at time T+n for the same facts.

---

### 2. Observation

| Aspect | Definition |
|--------|-----------|
| **IS** | A non-sovereign contextual interpretation of evidence emitted by an overlay. Observations describe, they do not decide. |
| **IS NOT** | A policy decision, a legitimacy outcome, a capability grant, an authority directive. |
| **Lifecycle** | Overlay observes evidence → emits `OverlaySignal` with finding → collected in `ConstitutionalObservationContext` → preserved for resolver → never interpreted outside resolver |
| **Code artifact** | `OverlaySignal`, `ConstitutionalObservationContext`, `OverlayObservationSummary` |
| **Aggregate** | `ObservationAggregate` — flat collection, no ranking, no precedence |

**Invariant:** No observation may carry authority semantics (allow/deny/grant/block). Observations are semantically flat.

---

### 3. Legitimacy

| Aspect | Definition |
|--------|-----------|
| **IS** | Constitutional participation authorization derived exclusively by `ConstitutionalLegitimacyDecision` from frozen evidence via `PolicySequence`. |
| **IS NOT** | A controller's `canVote()` check, a middleware block, an inline DB query, a feature flag. |
| **Lifecycle** | Evidence frozen → PolicySequence evaluates → LegitimacyOutcome derived → enforced at gate → recorded for replay |
| **Code artifact** | `LegitimacyOutcome` (enum: Allowed, Denied, Deferred, Investigate), `ConstitutionalLegitimacyDecision`, `VotingTrustResult` |
| **Aggregate** | `LegitimacyAggregate` — outcome immutable after derivation |

**Invariant:** `LegitimacyOutcome` is constructable ONLY by `ConstitutionalLegitimacyDecision`. No other code path may instantiate it.

---

### 4. Sovereignty

| Aspect | Definition |
|--------|-----------|
| **IS** | The exclusive authority to derive legitimacy within a bounded constitutional context. Sovereignty is a property of the resolver, not the codebase. |
| **IS NOT** | Middleware blocking order, controller authority, observer-driven decision paths, cache-derived state, UI gating. |
| **Lifecycle** | Established by CFB-1 → verified by fitness functions → maintained through topology neutrality → retired via controlled D-phase sequencing |
| **Code artifact** | `PolicySequence` (sovereign resolver), `ConstitutionalLegitimacyDecision` (sovereignty gate), `SovereigntyConvergenceFitnessFunction` (verification) |
| **Aggregate** | `SovereigntyTransitionAggregate` — tracks phase state of sovereignty migration |

**Invariant:** Only one sovereignty source exists at any time for any participation decision. Dual sovereignty is a transitional state with telemetry.

---

### 5. Replay

| Aspect | Definition |
|--------|-----------|
| **IS** | Deterministic reconstruction of a sovereign outcome from frozen evidence. Replay verifies that the same evidence always produces the same legitimacy. |
| **IS NOT** | Log replay, event-sourcing replay, cache warmup, procedural reconstruction. |
| **Lifecycle** | Evidence frozen at time T → serialized to snapshot → deserialized at time T+n → re-evaluated → outcome compared |
| **Code artifact** | (Future) `ReplaySession`, `ReplayCertification`, `ReplayEvidenceEnvelope`, `ReplayAssertion` |
| **Aggregate** | `ReplayAggregate` — session-scoped, certification-sealed |

**Invariant:** ReplayCertification must fail if evidence envelope, policy sequence, or resolver mapping has changed since certification.

---

### 6. Divergence

| Aspect | Definition |
|--------|-----------|
| **IS** | An authority mismatch between the constitutional resolver and a procedural authority path. Evidence that two sovereignty sources produced different outcomes for the same voter. |
| **IS NOT** | A bug, a test failure, a configuration difference, expected behavioral variation. |
| **Lifecycle** | Detected at evaluation boundary → recorded as `SovereigntyDivergenceRecord` → categorized by type → tracked in `DivergenceObservationWindow` → resolved or escalated |
| **Code artifact** | `SovereigntyDivergenceRecord`, `ConstitutionalDivergenceType`, `DivergenceCategory`, `DivergenceSeverity` |
| **Aggregate** | `DivergenceAggregate` — immutable after creation, never mutated |

**Invariant:** Divergence records are immutable after creation. Mutation is forbidden — new observations create new records.

---

### 7. Projection

| Aspect | Definition |
|--------|-----------|
| **IS** | A non-authoritative representation of constitutional state for UI, reporting, or analytics. Projections interpret nothing — they display. |
| **IS NOT** | A legitimacy decision, a participation gate, a sovereign interpretation, an authority hint. |
| **Lifecycle** | Constitutional state emitted → projected into presentation format → rendered → never feeds back into sovereignty |
| **Code artifact** | Inertia props, Vue components, dashboard widgets, `ElectionCapabilityResolver` (projection-only path) |
| **Aggregate** | (No aggregate — projections are read-models, not domain entities) |

**Invariant:** Projection ordering must not imply constitutional precedence. UI layout is display topology, not sovereignty topology.

---

### 8. Topology Leakage

| Aspect | Definition |
|--------|-----------|
| **IS** | Authority implied by execution order rather than evidence. When middleware A runs before middleware B and A's output determines B's behavior, topology has leaked into sovereignty. |
| **IS NOT** | Intentional middleware ordering (logging before auth), documented processing pipelines, explicit orchestration. |
| **Detection** | Middleware position audit → controller ordering analysis → event listener chain audit → route group ordering review |
| **Remediation** | Centralize authority in resolver → make middleware evidence-observing not authority-enforcing → remove ordering dependencies |
| **Code artifact** | (No single artifact — detected via `C.5d` audit patterns) |

**Invariant:** Swapping any two middleware positions must not change the legitimacy outcome for any voter.

---

### 9. Constitutional Insufficiency

| Aspect | Definition |
|--------|-----------|
| **IS** | An evidence set that does not meet the sovereign threshold for participation legitimacy. Insufficiency is a property of the evidence relative to the constitution, not a "low score." |
| **IS NOT** | "Not enough trust points," "insufficient reputation," "below confidence threshold," "risk too high." |
| **Behavior** | `INSUFFICIENT_EVIDENCE` must survive additional `CONTEXT_STABLE` observations — monotonicity invariant |
| **Code artifact** | `TrustEvaluationState::InsufficientEvidence`, `VotingTrustResult::insufficientEvidence()` |
| **Algebra** | `evaluate(E ∪ {O}) = INSUFFICIENT_EVIDENCE` if `evaluate(E) = INSUFFICIENT_EVIDENCE` |

**Invariant:** Sovereignty monotonicity — constitutional insufficiency is NOT averaged away by additional observations.

---

## Aggregate Boundaries

### LegitimacyAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                    LegitimacyAggregate                        │
│                                                              │
│  Root Entity: LegitimacyEvaluation                           │
│  Value Objects: LegitimacyOutcome, VotingTrustResult         │
│                                                              │
│  Invariants:                                                 │
│  • Outcome derived EXCLUSIVELY by ConstitutionalLegitimacyDecision │
│  • Once finalized, Outcome is immutable                      │
│  • Evidence fingerprint must be part of derivation           │
│                                                              │
│  Lifecycle: Created → Decided → Enforced → Archived          │
│                                                              │
│  Creation: ONLY via ConstitutionalLegitimacyDecision::decide()│
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### EvidenceAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                     EvidenceAggregate                         │
│                                                              │
│  Root Entity: ConstitutionalEvidence                         │
│  Value Objects: NetworkTrustEvidence, DeviceTrustContext,     │
│                 VerificationAttestationRecord,                │
│                 VotingSessionTrustContinuity,                 │
│                 ParticipationEligibilityEvidence              │
│                                                              │
│  Invariants:                                                 │
│  • All evidence frozen at evaluation time (never re-queried) │
│  • Hash integrity always verifiable                          │
│  • Replay must reproduce identical set                       │
│  • Election-scoped hashing (same IP, different election →    │
│    different hash)                                           │
│                                                              │
│  Lifecycle: Collected → Hashed → Frozen → Sealed → Archived  │
│                                                              │
│  Creation: ONLY via TrustSnapshotAssembler::assemble()        │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### ObservationAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                   ObservationAggregate                        │
│                                                              │
│  Root Entity: ConstitutionalObservation                      │
│  Value Objects: OverlaySignal, OverlayObservationSummary     │
│  Collection: ConstitutionalObservationContext                 │
│                                                              │
│  Invariants:                                                 │
│  • Never authoritative (no allow/deny/grant/block)           │
│  • Never aggregated into scalar (no score/weight/rank)       │
│  • Never weighted (all observations are flat)                │
│  • Never reconciled (conflicting observations coexist)       │
│  • Order-independent (no implicit priority by position)      │
│  • Neutral secondary properties (no handling hints)          │
│                                                              │
│  Lifecycle: Observed → Emitted → Collected → Preserved       │
│                                                              │
│  Creation: Via OverlayCoordinator::aggregate()               │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### ReplayAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                      ReplayAggregate                          │
│                                                              │
│  Root Entity: ReplaySession                                  │
│  Value Objects: ReplayCertification, ReplayEvidenceEnvelope,  │
│                 ReplayAssertion, ReplayCompatibilityVersion   │
│                                                              │
│  Invariants:                                                 │
│  • Same input → identical output across runtimes             │
│  • Same input → identical output across serialization cycles │
│  • Same input → identical output across timezones            │
│  • Certification fails if schema/policy/resolver changed     │
│  • Evidence envelope sealed at session creation               │
│                                                              │
│  Lifecycle: Created → Evidence Sealed → Replayed → Certified │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### DivergenceAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                    DivergenceAggregate                        │
│                                                              │
│  Root Entity: SovereigntyDivergence                          │
│  Value Objects: DivergenceCategory, DivergenceSeverity,      │
│                 ConstitutionalDivergenceType                  │
│                                                              │
│  Invariants:                                                 │
│  • Immutable record of dual-authority mismatch               │
│  • No mutation after creation (new divergence = new record)  │
│  • Must carry both constitutional and procedural outcomes    │
│  • Must identify which sovereignty paths diverged            │
│                                                              │
│  Lifecycle: Detected → Recorded → Categorized → Resolved     │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### SovereigntyTransitionAggregate

```
┌─────────────────────────────────────────────────────────────┐
│                SovereigntyTransitionAggregate                 │
│                                                              │
│  Root Entity: DualSovereigntyTransition                      │
│  Value Objects: TransitionPhase, RollbackCondition,          │
│                 DriftTelemetrySnapshot                        │
│                                                              │
│  Invariants:                                                 │
│  • Irreversible per-phase (no skipping phases)               │
│  • Rollback always possible until D.5 complete               │
│  • Each phase has explicit entry and exit criteria           │
│  • Drift telemetry required during dual sovereignty          │
│                                                              │
│  Lifecycle: C.5e → D.0.1 → D.0.2 → D.0.3a → ... → D.5       │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## Ubiquitous Language Table

| Term | Precise Meaning | Counter-Example (What It Is NOT) | Primary Code Artifact | Aggregate |
|------|----------------|----------------------------------|----------------------|----------|
| Evidence | Immutable constitutional fact frozen at evaluation time | A mutable DB column like `User.voting_ip` | `NetworkTrustEvidence`, `DeviceTrustContext` | EvidenceAggregate |
| Observation | Non-sovereign contextual interpretation of evidence | A policy decision like "DENY this voter" | `OverlaySignal`, `ConstitutionalObservationContext` | ObservationAggregate |
| Legitimacy | Constitutional participation authorization derived by exclusive resolver | A controller's `canVote()` returning `bool` | `LegitimacyOutcome`, `ConstitutionalLegitimacyDecision` | LegitimacyAggregate |
| Sovereignty | Exclusive authority to derive legitimacy within bounded context | Middleware blocking in position 8 regardless of evidence | `PolicySequence::evaluate()` | SovereigntyTransitionAggregate |
| Replay | Deterministic reconstruction of sovereign outcome from frozen evidence | Event-sourcing replay that replays commands instead of evidence | `TrustEvaluationEnvelope` (current), `ReplaySession` (future) | ReplayAggregate |
| Divergence | Authority mismatch between constitutional and procedural systems | A bug in legacy code | `SovereigntyDivergenceRecord` | DivergenceAggregate |
| Projection | Non-authoritative representation of constitutional state | UI showing "You can vote" as an authority decision | Inertia page props, `ElectionCapabilityResolver` | (None — read model) |
| Topology Leakage | Authority implied by execution order rather than evidence | Middleware position 8 blocking before constitutional evaluation | Audit finding (C.5d), not a class | (None — architectural property) |
| Constitutional Insufficiency | Evidence set below sovereign threshold for participation | "Not enough trust score" or "risk too high" | `TrustEvaluationState::InsufficientEvidence` | LegitimacyAggregate |
| Freeze | Act of sealing evidence at evaluation boundary so it cannot mutate | Caching evidence with a TTL | `TrustSnapshotAssembler::assemble()` | EvidenceAggregate |
| Enforcement Gate | Runtime check that blocks participation when legitimacy is not Allowed | `if (!$user->canVote)` procedural check | D.0.3a gate in `VoteController::store()` | LegitimacyAggregate |
| Fitness Function | Automated invariant verification that constitutional rules still hold | A unit test that passes today but can rot | `SovereigntyConvergenceFitnessFunction` tests | (Verification, not domain) |
| Shadow Mode | Dual-running a new authority path while old path remains authoritative | Turning off the new path without telemetry | `config('voting_security.constitutional_mode')` | SovereigntyTransitionAggregate |
| Certification | Formal verification that a replay produced identical outcome | Running tests once and assuming they pass forever | F3 test (replay determinism), `ReplayCertification` (future) | ReplayAggregate |
| Telemetry | Observational data about sovereignty behavior, never authority itself | Logging a divergence and then using the log to make decisions | `SecurityEventRecorder::record()`, `SovereigntyDivergenceRecord` | DivergenceAggregate |

---

## Anti-Corruption Layer

Maps legacy procedural concepts to constitutional domain concepts during strangler fig migration.

### Legacy → Constitutional Translation

| Legacy (procedural) | Constitutional (domain) | Status |
|--------------------|------------------------|--------|
| `User.voting_ip` | `NetworkTrustEvidence.currentIpHash` | ✅ Migrated |
| `$user->canVote` | `ConstitutionalLegitimacyDecision::decide()` | ✅ Migrated (D.0.3a) |
| `validateVotingIpWithResponse()` | `EvidenceInconsistency` observation | ✅ Migrated (H.2) |
| Middleware blocking (ValidateVotingIp) | `ConstitutionalDenial` via resolver | ⏳ D.0.3a active, D.0.3e retirement pending |
| Helper status checks | `ObservationProjection::render()` | ✅ Migrated (H.2) |
| `Code.can_vote_now` | `AttestationRecord::isVerified()` | 🔍 Not yet migrated |
| `ElectionMembership` status checks | `ParticipationEligibilityEvidence` | ✅ Migrated |
| `VotingSecurityService::canVoteFromIp()` | `PolicySequence::evaluate()` → `LegitimacyOutcome` | ⏳ D.6 retirement |

### Translation Rules

| Rule | Description |
|------|-------------|
| **T1** | Legacy reads may inform constitutional evidence construction, but must be hashed/frozen at the boundary |
| **T2** | Constitutional outputs must never flow back into legacy authority paths (one-way translation) |
| **T3** | Dual sovereignty paths must have telemetry recording divergence until legacy path is retired |
| **T4** | Translation must be explicit at the anti-corruption boundary — no implicit conversions via shared types |

### Forbidden Translation Patterns

```php
// FORBIDDEN: Passing constitutional outcome back into legacy authority path
$legacyResult->setCanVote($constitutionalOutcome->isAllowed()); // ← VIOLATION

// FORBIDDEN: Using legacy DB column as constitutional evidence without freezing
$evidence = new NetworkTrustEvidence(
    currentIpHash: $user->voting_ip,  // ← VIOLATION: not hashed, not frozen
);

// CORRECT: Hash and freeze at boundary
$ipHash = $privacyPolicy->hashIp($request->ip(), $election->id);
$evidence = new NetworkTrustEvidence(
    currentIpHash: $ipHash,  // ✓ Hashed, frozen, election-scoped
);
```

---

## Constitutional Event Taxonomy

### Event Classification

```
Sovereign Events (immutable, authority-significant):
  ├── LegitimacyEvaluated     — outcome derived for a voter at a point in time
  ├── ConstitutionalDenial    — voter participation blocked by constitutional gate
  └── SovereigntyBoundaryCrossed — dual sovereignty path divergence detected

Observational Events (non-authoritative, record-only):
  ├── OverlaySignalRecorded   — overlay emitted an observation
  ├── EvidenceInconsistencyObserved — evidence inconsistency detected
  └── DriftTelemetryRecorded  — sovereignty divergence telemetry captured

Replay Events (certification-scoped):
  ├── ReplaySessionOpened     — replay session initialized
  ├── ReplayCertificationIssued — replay outcome matches original
  └── ReplayDivergenceDetected — replay outcome differs from original

Projection Events (presentation-scoped):
  ├── ConstitutionalStateProjected — state rendered for UI/display
  └── ObservationRendered      — observation displayed in admin context

Migration Events (retirement-scoped):
  ├── DualSovereigntyEntered  — transition to dual-authority mode
  ├── ConstitutionalFallbackActivated — rollback triggered
  └── LegacyGateRetired       — procedural authority path removed
```

### Event Constraints

| Constraint | Applies To | Rule |
|-----------|-----------|------|
| Immutability | Sovereign events | Must be serialized with schema version and replay hash |
| Order independence | All sovereign events | Order of sovereign events must not affect subsequent outcomes |
| Non-authority | Observational events | Must not derive or imply legitimacy |
| Non-escalation | Observational events | Must not trigger sovereign behavior |
| Certification scope | Replay events | Must not leak into production sovereignty |
| One-way | All events | Events must not feed back into the sovereignty loop |

---

## Aggregate Lifecycle States

### LegitimacyAggregate

```
    ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
    │ Pending  │────▶│ Decided  │────▶│Enforced  │────▶│ Archived │
    └──────────┘     └──────────┘     └──────────┘     └──────────┘
         │                │                │                │
    Evidence not      Outcome          Gate either     Snapshot
    yet evaluated   determined by     blocked or      persisted
                    LegitimacyDecision allowed vote    for replay
```

### EvidenceAggregate

```
    ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
    │Collecting│────▶│  Frozen  │────▶│  Sealed  │────▶│ Archived │
    └──────────┘     └──────────┘     └──────────┘     └──────────┘
         │                │                │                │
    Overlays         Snapshot         Evidence         Evidence
    observing        assembled        hashed and       persisted
                     at eval time     integrity        for replay
                                      verified
```

### SovereigntyTransitionAggregate

```
    ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
    │  Legacy  │────▶│  Dual    │────▶│Constit'l │────▶│Retired   │
    │Sovereign │     │Sovereign │     │Sovereign │     │          │
    └──────────┘     └──────────┘     └──────────┘     └──────────┘
         │                │                │                │
    Old blocking      ValidateVotingIp  Constitutional     Legacy
    middleware        shadows,          gate is primary    middleware
    is authority      constitutional                      removed
                      gate secondary
```

---

## Constitutional Algebra Axioms

These axioms define the **mathematical properties** of constitutional domain operations — they are the formal foundation that makes the constitutional runtime a verifiable algebra rather than a heuristic system.

### Axiom 1: Evidence Preservation (F2)

```
For any evidence set E and evaluation function evaluate():
  evaluate(E) produces exactly one outcome O
  O is a function of E only (no hidden state, no temporal drift)
```

### Axiom 2: Sovereignty Monotonicity (F5)

```
For any evidence set E and any observation O:
  If evaluate(E) = INSUFFICIENT_EVIDENCE
  Then evaluate(E ∪ {O}) = INSUFFICIENT_EVIDENCE
```

### Axiom 3: Topology Neutrality (F1)

```
For any evidence set E and any call topology T1 ≠ T2:
  evaluate_T1(E) = evaluate_T2(E)
  
The outcome depends on E, not on which code path delivered E.
```

### Axiom 4: Replay Determinism (F3)

```
For any evidence set E evaluated at time T and any time T+n:
  evaluate(E) at T = evaluate(E) at T+n

Reconstructing the same evidence produces the same outcome
regardless of when or where reconstruction occurs.
```

### Axiom 5: Resolver Exclusivity (F4)

```
For any participation decision D:
  D = ConstitutionalLegitimacyDecision::decide(E)
  
There is exactly one authority function, and it maps evidence to outcomes.
No other code path can produce D for any voter.
```

### Axiom 6: Observation Neutrality (F6)

```
For any observation O:
  O is a member of the set {CONTEXT_STABLE, EVIDENCE_INCONSISTENT}
  O carries no authority, no weight, no precedence
  The set of observations is unordered and flat
```

---

## Structural Enforcement Targets

These targets bridge from ubiquitous language to compiler-enforced boundaries.

| Target | Current State | Target State | Phase |
|--------|--------------|-------------|-------|
| `LegitimacyOutcome::fromTrustState()` | Public static method | Private to `ConstitutionalLegitimacyDecision` | DD.3 |
| `VotingTrustResult` construction | Multiple callers | Factory methods only | DD.3 |
| `OverlaySignal` severity ranking | Severity enum exists (EvidenceSeverity) | Descriptive only, no interpretation | DD.2 |
| `PolicySequence` vs resolver naming | Named "policy sequence" | Rename to reflect resolver role, or keep as evaluator with explicit resolver separation | DD.3 |
| Evidence freezing | Convention-based | Constructor-enforced: evidence must be pre-hashed | DD.2 |
| Replay as domain capability | Operational (tests only) | First-class domain objects: ReplaySession, ReplayCertification | DD.2 |

---

## Relationship to Existing Governance Documents

| Document | Relationship |
|----------|-------------|
| `ConstitutionalLegitimacyDerivationDoctrine.md` | Defines WHO may derive legitimacy (doctrine) — this document defines WHAT legitimacy IS (domain model) |
| `ConstitutionalObservationDoctrine.md` | Defines observation purity rules (doctrine) — this document defines observation aggregate boundaries (domain model) |
| `CanonicalVocabularyRules.md` | Defines forbidden vocabulary (enforcement) — this document defines permitted concepts (ontology) |
| `SemanticBoundaryMap.md` | Defines canonical vs compatibility zones (migration) — this document defines aggregate boundaries (structure) |

**Principle:** Doctrines say what is forbidden. Ubiquitous language says what exists. Together they define the constitutional domain completely.

---

## Amendment Process

This document evolves as the domain model is implemented:

1. **DD.1** (current) — Aggregate boundaries and ubiquitous language table
2. **DD.2** — Replay domain classes and event taxonomy implementation
3. **DD.3** — Structural enforcement and tactical separation

Amendments to the ubiquitous language require:
- Senior architectural review
- Corresponding update to affected aggregate invariants
- Verification that no aggregate boundary is violated
- Update to semantic regression tests

---

*This document formalizes the constitutional domain language. It transforms governance from doctrine-heavy enforcement to model-driven constitutional algebra. Every term defined here corresponds to a code artifact, an aggregate boundary, or an invariant enforced by the type system.*

*Engineers who introduce new terms without updating this document are introducing undiscovered sovereignty vectors.*
