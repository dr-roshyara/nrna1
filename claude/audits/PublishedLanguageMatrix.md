# Published Language Matrix

**Phase:** DD.3b — Strategic DDD Discovery (P6)
**Date:** 2026-05-29
**Prerequisite:** ConstitutionalContextMap.md, ContextRelationshipMatrix.md
**Status:** Initial — open for Senior Architect review

## Purpose

Formal anti-corruption boundary map. Defines **what types cross which context boundaries** and in what form. This is the contract that each context exposes to its consumers.

---

## Published Language by Context

### Observation → Published Language: `OverlaySignal[]`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `OverlaySignal[]` via `ConstitutionalObservationContext` |
| **Consumer** | Evidence Context |
| **Form** | Flat array of typed signal objects |
| **Contract** | Signals are non-authoritative, unranked, unordered |
| **Anti-corruption** | Signals must NOT carry `LegitimacyOutcome`, `TrustLevel`, or scalar scores |

### Evidence → Published Language: `ConstitutionalEvidenceSnapshot`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `ConstitutionalEvidenceSnapshot` |
| **Consumer** | Evaluation Context |
| **Form** | Immutable frozen data + hash |
| **Contract** | Same snapshot → same hash across runtimes |
| **Anti-corruption** | Snapshot must NOT embed overlay logic or signal interpretation |

### Evidence → Published Language (Replay): `ConstitutionalEvidenceSnapshot`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `ConstitutionalEvidenceSnapshot` → wrapped in `ReplayEvidenceEnvelope` |
| **Consumer** | Replay Context |
| **Form** | Sealed envelope with compatibility version |
| **Contract** | Evidence integrity is hash-verifiable |
| **Anti-corruption** | Replay adapts the snapshot via OHS; Evidence does not know about Replay |

### Evaluation → Published Language: `EvaluationEnvelope`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `EvaluationEnvelope` (containing `EvidenceEvaluationResult` + observations + snapshot) |
| **Consumer** | Legitimacy Context |
| **Form** | Sealed envelope — no post-creation mutation |
| **Contract** | Exclusive input to legitimacy derivation |
| **Anti-corruption** | Envelope must NOT contain `LegitimacyOutcome`; Evaluation must not import Legitimacy types |

### Legitimacy → Published Language: `LegitimacyOutcome`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `LegitimacyOutcome` (Allowed, Denied, Deferred, Investigate) |
| **Consumer** | Governance Context, Application layer |
| **Form** | String enum |
| **Contract** | Sole derivation path is `ConstitutionalLegitimacyDecision` |
| **Anti-corruption** | F8 blocks projection from referencing; F4 blocks off-path derivation |

### Legitimacy → Published Language (Migration): `LegitimacyOutcome`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `LegitimacyOutcome` for divergence comparison |
| **Consumer** | Migration Context (temporary: D.0-D.5) |
| **Form** | Same enum, compared against procedural outcome |
| **Contract** | Migration reads outcomes; never derives them |
| **Anti-corruption** | Migration must not create, modify, or intercept the outcome derivation |

### Replay → Published Language: `ReplayCertification`

| Aspect | Detail |
|--------|--------|
| **What crosses** | `ReplayCertification` (matched/diverged + envelope hash + version) |
| **Consumer** | All contexts (via Open Host Service) |
| **Form** | Immutable certification record |
| **Contract** | Same envelope → same certification across runtimes |
| **Anti-corruption** | Certification is read-only for all consumers; no post-creation mutation |

---

## Full Cross-Boundary Type Map

| Producer Context | Consumer Context | Type Crossing | Form | ACL |
|-----------------|-----------------|---------------|------|-----|
| **Observation** | Evidence | `OverlaySignal[]` | `ConstitutionalObservationContext` | None needed — natural upstream |
| **Evidence** | Evaluation | `ConstitutionalEvidenceSnapshot` | Frozen data + hash | Immutability contract |
| **Evidence** | Replay | `ConstitutionalEvidenceSnapshot` | Wrapped in `ReplayEvidenceEnvelope` | OHS adaptation |
| **Evaluation** | Legitimacy | `EvaluationEnvelope` | Sealed envelope | Published Language |
| **Legitimacy** | Governance | `LegitimacyOutcome` | String enum | Conformist |
| **Legitimacy** | Migration | `LegitimacyOutcome` | Same enum | Read-only (temporary) |
| **Legitimacy** | Projection | **(BLOCKED)** | None | F8 ACL |
| **Replay** | (All) | `ReplayCertification` | Immutable record | Open Host Service |

---

## Types That MUST NOT Cross Boundaries

| Type | Source Context | Must Not Reach | Reason |
|------|---------------|----------------|--------|
| `OverlaySignal` (individual) | Observation | Legitimacy, Projection | F1/F2 — singular signals carry no authority |
| `EvidenceEvaluationState` | Evaluation | Legitimacy, Projection | Evaluation state is input, not outcome |
| `EvaluationReasonCode` | Evaluation | Legitimacy, Projection | Reason is evaluation detail, not legitimacy basis |
| `LegitimacyOutcome` | Legitimacy | Projection | F8 — UI must not display outcomes |
| `TrustLevel` | (Legacy root) | Any constitutional context | Scalar trust is legacy concept |
| `SovereigntyDivergenceRecord` | Migration | Legitimacy | Migration concerns must not affect derivation |
| Raw evidence (IP, fingerprint) | (Infrastructure) | Domain layer | Privacy policy requires hashing before domain processing |

---

## Published Language Invariants

| Invariant | Enforced By |
|-----------|-------------|
| Every type crossing a boundary has exactly one producer | This matrix |
| Every type crossing a boundary has exactly one consumer (per instance) | This matrix |
| No type may be both producer's and consumer's domain object | ACL pattern |
| Published language types are simple value objects (no behavior) | Design convention |
| Published language types must be serializable for replay | F3/F9 fitness functions |
