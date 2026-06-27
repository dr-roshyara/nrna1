# Business Invariant Catalog

**Phase:** DD.3b — Strategic DDD Discovery (P7a)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

DDD aggregates are built around invariants. Before aggregate discovery can proceed, the business invariants that those aggregates must protect must be catalogued. This document catalogs every known constitutional invariant.

**Relationship to fitness functions:** Fitness functions (F1-F10) are ARCHITECTURAL invariants (protection against regression). Business invariants are DOMAIN invariants (protection against incorrect constitutional outcomes). They overlap but are not identical.

---

## Invariant Template

Each invariant is catalogued with:
- **ID** — unique identifier
- **Context** — which bounded context owns it
- **Description** — what must always be true
- **Forbidden** — what would violate it
- **Enforcement** — how it is enforced today
- **Fitness function** — which F# protects it (if any)

---

## Legitimacy Invariants

### LEG-1: Resolver Exclusivity

| Aspect | Detail |
|--------|--------|
| **Description** | Only `ConstitutionalLegitimacyDecision` may derive `LegitimacyOutcome` |
| **Forbidden** | Controllers, middleware, services, queue workers, or UI creating `LegitimacyOutcome` |
| **Enforcement** | F4, F10 fitness functions; `fromTrustState()` removed (inlined in resolver) |
| **Current status** | ✅ Enforced |

### LEG-2: One Policy Sequence Per Decision

| Aspect | Detail |
|--------|--------|
| **Description** | Each legitimacy decision derives from exactly one policy sequence evaluation |
| **Forbidden** | Multiple independent evaluations merged into one outcome |
| **Enforcement** | `PolicySequence` is the single orchestrator |
| **Current status** | ✅ Enforced by architecture |

### LEG-3: Evidence Frozen at Decision

| Aspect | Detail |
|--------|--------|
| **Description** | Evidence snapshot is created before legitimacy derivation; no evidence is re-queried during derivation |
| **Forbidden** | Live DB queries during `ConstitutionalLegitimacyDecision::decide()` |
| **Enforcement** | Convention — evaluation chain freezes evidence before resolver |
| **Current status** | ⚠️ Convention-based — no structural enforcement |

### LEG-4: Sovereignty Monotonicity

| Aspect | Detail |
|--------|--------|
| **Description** | Additional observation abundance must never weaken constitutional insufficiency |
| **Forbidden** | Scoring, weighting, averaging, or compensating reconciliation that allows more signals to override an insufficiency |
| **Enforcement** | F5 (observation preservation); Constitutional Algebra Boundary doctrine |
| **Current status** | ✅ Enforced by F5 |

### LEG-5: Outcome Immutability

| Aspect | Detail |
|--------|--------|
| **Description** | Once derived, `LegitimacyOutcome` is immutable for that evaluation |
| **Forbidden** | Post-derivation mutation of an outcome |
| **Enforcement** | `LegitimacyOutcome` is a backed enum — immutable by PHP design |
| **Current status** | ✅ Structurally enforced |

---

## Evidence Invariants

### EVI-1: Evidence Frozen at Evaluation Time

| Aspect | Detail |
|--------|--------|
| **Description** | All evidence for an evaluation is collected, frozen, and hashed before evaluation begins |
| **Forbidden** | Re-querying evidence mid-evaluation; mutating an evidence snapshot after creation |
| **Enforcement** | `ConstitutionalEvidenceSnapshot` is `readonly` |
| **Current status** | ✅ Structurally enforced |

### EVI-2: Evidence Hash Integrity

| Aspect | Detail |
|--------|--------|
| **Description** | Same evidence data must produce identical hash across processes, hosts, and runtimes |
| **Forbidden** | Timezone-dependent, locale-dependent, or platform-dependent hashing |
| **Enforcement** | F3 (replay hash stability) |
| **Current status** | ✅ Test-enforced |

### EVI-3: No PII in Evidence Context

| Aspect | Detail |
|--------|--------|
| **Description** | Evidence context contains only hashed or minimized data. Raw IPs, fingerprints, or identity values never appear. |
| **Forbidden** | Storing cleartext IP, device fingerprint, or identity values in domain objects |
| **Enforcement** | `TrustEvidencePrivacyPolicy::canRetainRawIp()` returns false; hashing before domain processing |
| **Current status** | ✅ Convention and structural |

### EVI-4: Classification Is Categorical, Not Scalar

| Aspect | Detail |
|--------|--------|
| **Description** | `EvidenceClassification` describes what provenance exists — it must never become a scalar trust score |
| **Forbidden** | Numeric scoring of classifications; weighted provenance averages |
| **Enforcement** | ACL-2 from EvidenceContext.md; no scalar conversion pattern exists |
| **Current status** | ⚠️ Convention-based — no structural enforcement |

---

## Evaluation Invariants

### EVL-1: Evaluation Does Not Derive Legitimacy

| Aspect | Detail |
|--------|--------|
| **Description** | `EvidenceEvaluationResult` describes evidence quality; it does not produce `LegitimacyOutcome` |
| **Forbidden** | Evaluation producing a legitimacy-like outcome; mapping evaluation state to legitimacy inside Evaluation |
| **Enforcement** | F4, F10 (resolver exclusivity — also covers evaluation boundary) |
| **Current status** | ✅ Test-enforced |

### EVL-2: Evaluation Uses Typed Reason Codes

| Aspect | Detail |
|--------|--------|
| **Description** | Every evaluation result carries a typed `EvaluationReasonCode` |
| **Forbidden** | Stringly-typed reasons; free-text reason fields |
| **Enforcement** | `EvidenceEvaluationResult` constructor requires `EvaluationReasonCode` |
| **Current status** | ✅ Structurally enforced |

### EVL-3: Envelope Is Sealed After Creation

| Aspect | Detail |
|--------|--------|
| **Description** | `EvaluationEnvelope` is immutable after construction |
| **Forbidden** | Post-creation mutation of envelope contents |
| **Enforcement** | `readonly` class |
| **Current status** | ✅ Structurally enforced |

---

## Observation Invariants

### OBS-1: Observations Are Non-Sovereign

| Aspect | Detail |
|--------|--------|
| **Description** | `OverlaySignal` describes what was observed; it never implies legitimacy or authority |
| **Forbidden** | Signal types that imply authority outcomes; signals carrying scalar trust scores |
| **Enforcement** | F1 (no singular authority fields); signal types are categorical (CONTEXT_STABLE, etc.) |
| **Current status** | ✅ Structurally enforced |

### OBS-2: Observations Are a Flat, Unranked Set

| Aspect | Detail |
|--------|--------|
| **Description** | `ConstitutionalObservationContext` holds signals as a flat collection with no ordering, ranking, or filtering |
| **Forbidden** | Sorting signals, filtering by type, weighting signals, ordering by precedence |
| **Enforcement** | F2 (envelope plurality); container is a simple array |
| **Current status** | ✅ Structurally enforced |

### OBS-3: Observations Never Flow Directly to Legitimacy

| Aspect | Detail |
|--------|--------|
| **Description** | Observation data must pass through Evidence → Evaluation before reaching Legitimacy |
| **Forbidden** | Direct Observation → Legitimacy import; resolver consuming OverlaySignal directly |
| **Enforcement** | F1, F4, F10 — multi-layer enforcement |
| **Current status** | ✅ Enforced |

---

## Replay Invariants

### REP-1: Replay Determinism

| Aspect | Detail |
|--------|--------|
| **Description** | Same evidence envelope → identical certification outcome across runtimes, serialization cycles, timezones |
| **Forbidden** | Timezone-dependent comparison; platform-specific serialization |
| **Enforcement** | F9 (replay determinism); deterministic `certificationHash` |
| **Current status** | ✅ Test-enforced |

### REP-2: Session State Transitions

| Aspect | Detail |
|--------|--------|
| **Description** | ReplaySession transitions are guarded: sealed → replayed → certified/diverged |
| **Forbidden** | Recording assertion after certification; certifying without assertion |
| **Enforcement** | State machine checks in `recordAssertion()` and `certify()` throw `RuntimeException` on invalid transition |
| **Current status** | ✅ Structurally enforced |

### REP-3: Certification Immutability

| Aspect | Detail |
|--------|--------|
| **Description** | Once created, `ReplayCertification` is immutable |
| **Forbidden** | Post-creation mutation of any certification field |
| **Enforcement** | `readonly` class |
| **Current status** | ✅ Structurally enforced |

---

## Governance Invariants

### GOV-1: Must Not Re-Derive Legitimacy

| Aspect | Detail |
|--------|--------|
| **Description** | Governance receives `LegitimacyOutcome` and enforces it; it does not derive its own |
| **Forbidden** | Governance code creating or mapping to `LegitimacyOutcome` |
| **Enforcement** | F4, F10 — same fitness functions protect this boundary |
| **Current status** | ✅ Test-enforced |

### GOV-2: May Block, Must Never Allow What Legitimacy Denies

| Aspect | Detail |
|--------|--------|
| **Description** | Governance may block an Allowed outcome (election closed). Governance must NEVER allow a Denied outcome. |
| **Forbidden** | A voter with `LegitimacyOutcome::Denied` being allowed to vote by governance logic |
| **Enforcement** | Convention — no test currently enforces this |
| **Current status** | ⚠️ Convention-only — gap identified |

---

## Projection Invariants

### PRJ-1: No Legitimacy Outcome in Projection

| Aspect | Detail |
|--------|--------|
| **Description** | UI/projection layer must not reference `LegitimacyOutcome` |
| **Forbidden** | Any import or reference to `LegitimacyOutcome` in Vue components, Inertia pages, or dashboard code |
| **Enforcement** | F8 (no projection legitimacy) — scans for `LegitimacyOutcome` in UI code |
| **Current status** | ✅ Test-enforced |

### PRJ-2: Observations Rendered as Flat Set

| Aspect | Detail |
|--------|--------|
| **Description** | UI ordering must not imply constitutional precedence |
| **Forbidden** | Sorting observations by "criticality"; red-first grouping; ranked legitimacy indicators |
| **Enforcement** | Convention (C.5i audit findings) |
| **Current status** | ⚠️ Convention-based |

---

## Invariant Summary

| ID | Context | Severity | Enforced? | Enforcement Type |
|----|---------|----------|-----------|-----------------|
| LEG-1 | Legitimacy | Critical | ✅ | Structural (F4, F10) |
| LEG-2 | Legitimacy | Critical | ✅ | Architectural |
| LEG-3 | Legitimacy | High | ⚠️ | Convention |
| LEG-4 | Legitimacy | Critical | ✅ | Structural (F5) |
| LEG-5 | Legitimacy | High | ✅ | PHP language |
| EVI-1 | Evidence | Critical | ✅ | PHP readonly |
| EVI-2 | Evidence | Critical | ✅ | Test (F3) |
| EVI-3 | Evidence | High | ✅ | Convention + structural |
| EVI-4 | Evidence | Medium | ⚠️ | Convention |
| EVL-1 | Evaluation | Critical | ✅ | Test (F4, F10) |
| EVL-2 | Evaluation | Medium | ✅ | Constructor typehint |
| EVL-3 | Evaluation | Medium | ✅ | PHP readonly |
| OBS-1 | Observation | Critical | ✅ | Structural (F1) |
| OBS-2 | Observation | High | ✅ | Structural (F2) |
| OBS-3 | Observation | Critical | ✅ | Multi-layer (F1, F4, F10) |
| REP-1 | Replay | Critical | ✅ | Test (F9) |
| REP-2 | Replay | High | ✅ | Runtime exception |
| REP-3 | Replay | Medium | ✅ | PHP readonly |
| GOV-1 | Governance | Critical | ✅ | Test (F4, F10) |
| GOV-2 | Governance | High | ⚠️ | Convention |
| PRJ-1 | Projection | High | ✅ | Test (F8) |
| PRJ-2 | Projection | Low | ⚠️ | Convention |

### Enforcement Gap Analysis

| Gap | Invariant | Risk | Recommended Action |
|-----|-----------|------|-------------------|
| LEG-3 convention-only | Evidence frozen at decision | Medium | Add assertion at resolver entry; verify snapshot timestamp is before derivation start |
| EVI-4 convention-only | Categorical classification | Low | Low priority — no scalar conversion pattern exists |
| GOV-2 convention-only | May not allow what legitimacy denies | **High** | Add integration test: submit vote with Denied outcome + governance override → verify block |
| PRJ-2 convention-only | Flat set projection | Low | Low priority — C.5i audit already cleaned up known violations |
