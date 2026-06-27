# Authority Ownership Matrix

**Phase:** DD.3b — Strategic DDD Discovery (P1)
**Date:** 2026-05-29
**Prerequisite:** NamespaceAlignmentAudit.md, EvidenceObservationCapabilityAudit.md, EvidenceContext.md
**Status:** Initial — open for Senior Architect review

## Purpose

For every concept in the constitutional governance architecture, document **what authority it owns** and **what authority it must never own**. This is the foundational layer — before context boundaries can be drawn, authority must be assigned.

---

## 1. Observation

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Emitting non-authoritative factual signals about observed constitutional state. Aggregating signals into a flat, unranked collection. |
| **Authority prohibited** | Legitimacy derivation, signal ranking/weighting, evidence preservation, policy evaluation, outcome production. |
| **Upstream dependencies** | Overlay output (Application layer produces `OverlaySignal`) |
| **Downstream dependencies** | Evidence Context consumes `OverlaySignal[]` via `ConstitutionalObservationContext` |
| **Current code location** | `App\Domain\Election\Security\Simplified\` — `OverlaySignal`, `ConstitutionalObservationContext` |
| **Has formal BC?** | No — currently co-located with Evidence in `Simplified` namespace |
| **Fitness function guard** | F1 (no singular authority fields), F2 (envelope plurality), F5 (observation preservation) |

**Key question:** Does Observation own the authority to observe, or just the output of observation?
**Answer:** Observation owns the **output** (signal creation and collection). The act of observing is an Application-layer concern (overlays). Observation is the domain concept that gives structure to what overlays produce.

---

## 2. Signal

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Typed classification of observation output (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT). Carrying evidence context (hashed/minimized data). |
| **Authority prohibited** | Signal ranking, weighting, aggregation into scalar scores, implication of legitimacy, ordering precedence. |
| **Upstream dependencies** | Overlay produces raw observation → Signal types it |
| **Downstream dependencies** | `ConstitutionalObservationContext` aggregates signals; Evaluation consumes them |
| **Current code location** | `App\Domain\Election\Security\Simplified\OverlaySignal` |
| **Has formal BC?** | No — same location as Observation |
| **Fitness function guard** | F1 (no singular authority fields) |

**Key question:** Is Signal distinct from Observation, or the same concept at different granularity?
**Answer:** Signal is the **unit of observation** — the atomic output. Observation is the **collection context**. Signal is the leaf type; Observation is the container. They share a concept boundary.

---

## 3. Evidence

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Freezing constitutional facts at evaluation time. Preserving evidence in immutable, hash-verifiable snapshots. Classifying evidence provenance (factual, not scalar). Maintaining replay-safe evidence integrity. |
| **Authority prohibited** | Legitimacy derivation, policy evaluation, outcome production, signal interpretation. Evidence describes what exists — it never judges or decides. |
| **Upstream dependencies** | Raw evidence sources (network, device, verification, continuity, eligibility); hashing infrastructure |
| **Downstream dependencies** | Evaluation consumes evidence snapshots; Replay certifies evidence integrity |
| **Current code location** | `App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot`, `ParticipationEligibilityEvidence`, `EvidenceClassification`; also root `NetworkTrustEvidence`, `DeviceTrustContext`, `VerificationAttestationRecord`, `VotingSessionTrustContinuity` |
| **Has formal BC?** | Partially — evidence types exist in both root and Simplified; no formal BC boundary |
| **Fitness function guard** | F3 (replay hash stability), F6 (deterministic convergence) |

**Key question:** Does Evidence own preservation authority, or is that infrastructure?
**Answer:** Evidence owns the **immutability contract** — the guarantee that once frozen, evidence is never re-queried or mutated. The technical act of hashing is infrastructure; the *invariant* that evidence is frozen is Evidence's authority.

---

## 4. Evaluation

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Assessing evidence quality against constitutional rules. Producing `EvidenceEvaluationState` (SUFFICIENT/INSUFFICIENT/REVIEW_REQUIRED/INCONCLUSIVE). Classifying evaluation reasons via typed `EvaluationReasonCode`. Sealing results in `EvaluationEnvelope`. |
| **Authority prohibited** | Legitimacy derivation — `EvidenceEvaluationResult` is INPUT to the resolver, not the resolver's output. Must not produce `LegitimacyOutcome`. Must not rank or weight observations. |
| **Upstream dependencies** | Evidence snapshots, observation context, policy definitions |
| **Downstream dependencies** | Legitimacy Context consumes `EvaluationEnvelope` |
| **Current code location** | `App\Domain\Election\Security\Simplified\EvidenceEvaluationState`, `EvidenceEvaluationResult`, `EvaluationEnvelope`, `EvaluationReasonCode` |
| **Has formal BC?** | No — co-located with Observation and Evidence in `Simplified` |
| **Fitness function guard** | F4 (resolver exclusivity — evaluation must NOT derive legitimacy) |

**Key question:** Does Evaluation own the authority to classify quality, or is that part of Evidence?
**Answer:** Evaluation owns **quality classification** — determining what the evidence means for participation eligibility *in terms of evidence quality*. This is distinct from Evidence (which only preserves and classifies provenance). The boundary: Evidence = "what exists"; Evaluation = "what the evidence means for readiness assessment."

---

## 5. Legitimacy

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Sovereign derivation of participation legitimacy. Mapping evaluation state to `LegitimacyOutcome` (Allowed, Denied, Deferred, Investigate). EXCLUSIVE authority — no other code path may produce `LegitimacyOutcome`. |
| **Authority prohibited** | Evidence collection, observation, policy evaluation, signal interpretation, projection. Legitimacy derives — it does not observe, collect, or present. |
| **Upstream dependencies** | `EvaluationEnvelope` (from Evaluation Context) |
| **Downstream dependencies** | Governance Context consumes `LegitimacyOutcome` for enforcement action |
| **Current code location** | `App\Domain\Election\Security\LegitimacyOutcome`, `App\Application\Election\Security\ConstitutionalLegitimacyDecision` |
| **Has formal BC?** | Partially — `LegitimacyOutcome` is a domain enum; `ConstitutionalLegitimacyDecision` is in Application. The resolver is the concept boundary. |
| **Fitness function guard** | F4 (resolver exclusivity), F7 (no controller legitimacy), F10 (legitimacy derivation only) |

**Key question:** What are the exact bounds of Legitimacy authority?
**Answer:** Legitimacy authority begins at the **resolver boundary** — it receives an `EvaluationEnvelope` and produces a `LegitimacyOutcome`. It has no authority over HOW evidence was collected, evaluated, or classified. Its sole authority is the **derivation mapping**.

---

## 6. Governance

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Defining election constitution rules, participation policies, enforcement actions based on legitimacy outcomes. Administrative decisions (election lifecycle, voter rolls). |
| **Authority prohibited** | Legitimacy derivation, evidence evaluation, observation collection. Governance acts ON legitimacy outcomes — it does not produce them. |
| **Upstream dependencies** | `LegitimacyOutcome` from Legitimacy Context |
| **Downstream dependencies** | Enforcement layer, UI projection |
| **Current code location** | No dedicated Governance context — currently conflated with application-layer controllers, middleware, and `Election` model |
| **Has formal BC?** | No — governance concepts are scattered across Application layer |
| **Fitness function guard** | None currently — Governance is outside the constitutional fitness function scope |

**Key question:** Is Governance inside Legitimacy or independent?
**Answer:** Governance is **downstream of Legitimacy** — it acts on outcomes, not derives them. It warrants separation because its concerns (election rules, enforcement policies, administrative actions) are substantively different from Legitimacy's concern (derivation). However, for this system's current maturity, Governance may be a subdomain of Legitimacy rather than a full independent context.

---

## 7. Projection

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Read-only presentation of constitutional state. Rendering observations as flat, unranked UI elements. Projecting trust snapshots for dashboard display. |
| **Authority prohibited** | Legitimacy outcome presentation (F8 — UI must not reference `LegitimacyOutcome`). Observation ordering that implies precedence (F1/F2). Any behavioral logic that recalculates state. |
| **Upstream dependencies** | Observation snapshots, enrollment history, constitution state |
| **Downstream dependencies** | Vue components, Inertia pages, admin dashboards |
| **Current code location** | `App\Domain\Election\Security\OverlayObservationSummary`, `ConstitutionalTrustSnapshot`, Vue pages in `resources/js/Pages/` |
| **Has formal BC?** | No — projection is an architectural concept, not a formal context |
| **Fitness function guard** | F8 (no projection legitimacy) |

**Key question:** What counts as "projection" vs "presentation"?
**Answer:** Projection = any read-facing representation of constitutional state. This includes API responses, Inertia props, Vue component state, and dashboard widgets. The key invariant: projections must never derive, imply, or display legitimacy outcomes. They show what was observed, not what was decided.

---

## 8. Replay

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Deterministic reconstruction of sovereign outcomes from frozen evidence. Session lifecycle management (sealed → replayed → certified/diverged). Evidence envelope integrity and sealing. Replay assertion contracts binding envelope + expected outcome. |
| **Authority prohibited** | Legitimacy outcome interpretation, policy evaluation, evidence dimension, any post-creation mutation of evidence or outcomes. |
| **Upstream dependencies** | `ConstitutionalEvidenceSnapshot` → `ReplayEvidenceEnvelope`; policy sequence hash |
| **Downstream dependencies** | Certification result consumed by telemetry, audit trails |
| **Current code location** | `App\Domain\Election\Replay\ReplaySession`, `ReplayCertification`, `ReplayAssertion`, `ReplayEvidenceEnvelope`, `ReplayCompatibilityVersion` |
| **Has formal BC?** | Yes — dedicated `App\Domain\Election\Replay` namespace with 5 classes and 32 tests |
| **Fitness function guard** | F3 (replay hash stability), F9 (replay determinism across runtimes) |

**Key question:** Does Replay own certification, or is Certification separate?
**Answer:** Replay **includes** certification as a lifecycle state. `ReplaySession::certify()` produces `ReplayCertification`. Certification is a value object within the Replay aggregate, not an independent context. The distinction matters: Certification is the OUTPUT of replay, not the controller of it.

---

## 9. Certification

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Immutable result recording whether replay matched or diverged. Carrying compatibility version, envelope hash, and timestamps for audit. |
| **Authority prohibited** | Session lifecycle, evidence sealing, outcome derivation. Certification records — it does not control or decide. |
| **Upstream dependencies** | ReplaySession produces certification |
| **Downstream dependencies** | Audit trail, divergence telemetry |
| **Current code location** | `App\Domain\Election\Replay\ReplayCertification` (inside Replay BC) |
| **Has formal BC?** | No — certification is a value object in Replay Context, not its own context |
| **Fitness function guard** | F3 (replay hash stability), F9 (replay determinism) |

**Key question:** Is Certification a sub-authority of Replay, or independent?
**Answer:** Sub-authority. Certification is the output of Replay's lifecycle. It has no independent behavior or lifecycle — it is created by ReplaySession::certify() and is immutable thereafter. Making it an independent context would over-engineer a value object.

---

## 10. Migration

| Attribute | Value |
|-----------|-------|
| **Authority owned** | Sovereignty transition sequencing (D.0 phase progression). Divergence detection between constitutional and procedural enforcement paths. Retirement engineering of legacy sovereignty paths. Rollback control when divergence exceeds threshold. |
| **Authority prohibited** | Legitimacy derivation, policy evaluation, evidence collection. Migration manages the RETIREMENT of old authority — it does not create new authority. |
| **Upstream dependencies** | Divergence telemetry, constitutional mode flags, comparison infrastructure |
| **Downstream dependencies** | Feature flags, retirement sequencing, rollback decisions |
| **Current code location** | `App\Domain\Election\Security\SovereigntyDivergenceRecord`, `DivergenceType`, `DivergenceSeverity`, `DivergenceCategory`, config (`constitutional_mode`), middleware shadow mode |
| **Has formal BC?** | No — migration concepts are scattered across config, Application layer, and domain divergence types |
| **Fitness function guard** | F4, F5, F6, F10 (those that blockTransfer) |

**Key question:** Does Migration own divergence detection?
**Answer:** Yes — divergence detection IS Migration's primary responsibility. It owns the telemetry, classification, and comparison between constitutional and procedural outcomes. However, divergence detection is TEMPORARY (D.0-D.5 only). After retirement is complete, divergence detection becomes unnecessary. Migration is a temporary context.

---

## Authority Summary

| Concept | Primary Authority | Key Constraint | BC Status |
|---------|------------------|----------------|-----------|
| **Observation** | Signal creation + flat collection | Must not rank, weight, or imply precedence | Emerging |
| **Signal** | Atomic observation unit, typed | Must not carry scalar or authority semantics | Emerging |
| **Evidence** | Freeze + preserve + classify facts | Must never decide; evidence is descriptive | Emerging |
| **Evaluation** | Assess evidence quality + produce state | Must not derive LegitimacyOutcome | Emerging |
| **Legitimacy** | Derive participation authority (EXCLUSIVE) | Only ConstitutionalLegitimacyDecision | Maturing |
| **Governance** | Define rules + act on outcomes | Must not derive legitimacy | Emerging |
| **Projection** | Present constitutional state readonly | Must not imply precedence or display outcomes | Emerging |
| **Replay** | Deterministic reconstruction + certify | Must not interpret outcomes | Maturing |
| **Certification** | Record replay match/divergence | Immutable value object; sub-authority of Replay | Subsumed |
| **Migration** | Sequence retirement + detect divergence | Temporary — D.0-D.5 only | Emerging |

## Context Assignment

Based on authority patterns, contexts cluster as:

| Bounded Context | Owned Authorities |
|----------------|-------------------|
| **Observation** | Signal creation, observation collection |
| **Evidence** | Evidence preservation, provenance classification, evidence snapshots |
| **Evaluation** | Evidence quality assessment, reason classification, result envelope |
| **Legitimacy** | Outcome derivation (exclusive resolver) |
| **Governance** | Rule definition, enforcement action |
| **Projection** | Read-only state presentation |
| **Replay** | Deterministic reconstruction, session lifecycle, certification |

## Cross-Reference: Fitness Functions to Authority

| Fitness Function | Guards | Authority |
|-----------------|--------|-----------|
| F1 | No singular authority fields | Observation |
| F2 | Envelope plurality | Observation |
| F3 | Replay hash stability | Evidence, Replay |
| F4 | Resolver exclusivity | Legitimacy |
| F5 | Observation preservation | Observation |
| F6 | Deterministic convergence | Legitimacy, Evaluation |
| F7 | No controller legitimacy | Legitimacy, Projection |
| F8 | No projection legitimacy | Projection |
| F9 | Replay determinism | Replay |
| F10 | Legitimacy derivation only | Legitimacy |
