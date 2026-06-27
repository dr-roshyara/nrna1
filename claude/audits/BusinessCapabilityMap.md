# Business Capability Map

**Phase:** DD.3b — Strategic DDD Discovery (P7b)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

Cross-reference authority ownership with business capability ownership. Prevents constitutional governance vocabulary from overwhelming the ubiquitous language — a risk identified by the Senior Architect (9.3/10 review).

While `AuthorityOwnershipMatrix.md` (P1) describes **what constitutional authority each concept owns**, this document describes **what business capability each concept serves**. The distinction:

| Perspective | Question | Risk of Over-Emphasis |
|-------------|----------|----------------------|
| **Authority ownership** | What constitutional power does this concept hold? | Everything becomes governance; domain language disappears |
| **Business capability** | What business problem does this concept solve? | Everything becomes features; constitutional rigor disappears |

Both perspectives are necessary. Neither dominates.

---

## Capability Categories

| Category | Definition | Examples |
|----------|-----------|----------|
| **Participation** | Determines who may participate and under what conditions | Voter eligibility, verification, attestation |
| **Observation** | Gathers contextual information about participation events | Device continuity, IP velocity, anomaly detection |
| **Preservation** | Freezes constitutional facts for audit and replay | Evidence snapshots, hashing, integrity verification |
| **Evaluation** | Assesses evidence quality against constitutional rules | Policy evaluation, evidence classification |
| **Decision** | Derives sovereign legitimacy outcomes | ConstitutionalLegitimacyDecision |
| **Enforcement** | Applies legitimacy decisions in operational context | Blocking votes, allowing participation, redirecting |
| **Reconstruction** | Replays evaluations to verify determinism and detect divergence | Replay sessions, certification, assertion recording |
| **Presentation** | Renders constitutional state for human understanding | UI components, dashboards, audit views |
| **Retirement** | Manages sovereignty transitions from legacy to constitutional | Divergence detection, gate sequencing, fallback |

---

## Capability-to-Authority Cross-Reference

### Participation Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Determine voter eligibility | `PolicySequence` | Evaluation | Evaluate evidence quality | Must not derive legitimacy |
| Verify voter membership | `ElectionLifecycle` | Governance (Generic) | Enforce membership rules | Operational, not constitutional |
| Check voting code validity | `Code` (model) | Governance (Generic) | Ensure code not exhausted | Infrastructure concern |
| Validate voter identity | `User` (model) | Governance (Generic) | Authenticate user | Infrastructure concern |

### Observation Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Observe device continuity | `DeviceAnomalyOverlay` | Observation | Produce OverlaySignal | Signals are non-sovereign |
| Observe IP velocity | `IpVelocityOverlay` | Observation | Produce OverlaySignal | Signals are non-sovereign |
| Observe attestation presence | `RegistrarAttestationElevation` | Observation | Produce OverlaySignal | Signals are non-sovereign |
| Observe suspicious activity | `SuspiciousActivityOverlay` | Observation | Produce OverlaySignal | Signals are non-sovereign |
| Observe emergency conditions | `EmergencyConditionOverlay` | Observation | Produce OverlaySignal | Signals are non-sovereign |
| Aggregate observations | `OverlayCoordinator` | Observation | Collect into `ConstitutionalObservationContext` | No filtering or ranking |

### Preservation Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Freeze evidence snapshot | `ConstitutionalEvidenceSnapshot` | Evidence | Immutable evidence at evaluation time | `readonly` class |
| Hash evidence for integrity | `ConstitutionalEvidenceSnapshot` | Evidence | Deterministic hash across runtimes | F3 enforced |
| Minimize PII in evidence | `TrustEvidencePrivacyPolicy` | Evidence | Hash before domain processing | Structural enforcement |
| Classify evidence provenance | `EvidenceClassification` | Evidence | Categorical, not scalar | EVI-4 convention-only |

### Evaluation Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Evaluate network binding | `NetworkBindingPolicy` | Evaluation | Produce `ConstitutionalFinding` | Policy evaluates, doesn't observe |
| Evaluate device binding | `DeviceBindingPolicy` | Evaluation | Produce `ConstitutionalFinding` | Policy evaluates, doesn't observe |
| Evaluate verification attestation | `VerificationAttestationPolicy` | Evaluation | Produce `ConstitutionalFinding` | Policy evaluates, doesn't observe |
| Orchestrate policy sequence | `PolicySequence` | Evaluation | Coordinate evaluation chain | Single orchestrator per decision |
| Produce evaluation envelope | `EvaluationEnvelope` | Evaluation | Seal evidence + result for resolver | Immutable after creation |

### Decision Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Derive legitimacy outcome | `ConstitutionalLegitimacyDecision` | Legitimacy | Map `EvaluationEnvelope` → `LegitimacyOutcome` | F4, F10 protected |
| Assert constitutional denial | `ConstitutionalLegitimacyDecision` | Legitimacy | Produce Denied outcome | Immutable once derived |
| Assert constitutional allowance | `ConstitutionalLegitimacyDecision` | Legitimacy | Produce Allowed outcome | Immutable once derived |
| Defer for investigation | `ConstitutionalLegitimacyDecision` | Legitimacy | Produce Deferred or Investigate | Requires human review path |

### Enforcement Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Block constitutionally denied voter | Controllers/Middleware | Governance (Generic) | Enforce `LegitimacyOutcome::Denied` | GOV-2: may block, must not allow what legitimacy denies |
| Allow constitutionally approved voter | Controllers/Middleware | Governance (Generic) | Enforce `LegitimacyOutcome::Allowed` | Subject to operational rules (election closed) |
| Redirect for verification | Controllers/Middleware | Governance (Generic) | Enforce `LegitimacyOutcome::Deferred` | UX concern |
| Flag for manual review | Controllers/Middleware | Governance (Generic) | Enforce `LegitimacyOutcome::Investigate` | Admin workflow |

### Reconstruction Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Open replay session | `ReplaySession` | Replay | Initialize replay lifecycle | Sealed → replayed → certified/diverged |
| Record replay assertion | `ReplaySession` | Replay | Bind expected outcome | Pre-certification contract |
| Certify replay result | `ReplaySession` | Replay | Produce `ReplayCertification` | Matched or diverged |
| Compute replay hash | `ReplayCertification` | Replay | Deterministic hash across runtimes | F9 enforced |

### Presentation Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Render participation status | Inertia/Vue components | Projection (Generic) | Present non-authoritative state | F8: no LegitimacyOutcome in UI |
| Display observation signals | Inertia/Vue components | Projection (Generic) | Flat, unranked set | PRJ-2: no implied precedence |
| Show denial reason | Inertia/Vue components | Projection (Generic) | Present constitutional evidence | After H.2: no cleartext IP |

### Retirement Capabilities

| Business Capability | Authority Owner | Context | Constitutional Authority | Notes |
|--------------------|----------------|---------|------------------------|-------|
| Detect sovereignty divergence | `SovereigntyDivergenceRecorder` | Migration | Compare constitutional vs procedural | Temporary (D.0-D.5) |
| Record divergence telemetry | `DivergenceObserver` | Migration | Log mismatch evidence | Read-only for Legitimacy |
| Sequence gate retirement | Config + feature flags | Migration | Control cutover sequencing | D.0.1-D.0.3 sequencing |

---

## Capability Clusters by Business Value

### Core Differentiating Capabilities

These capabilities generate business value and differentiate the platform:

| Capability | Why Core | Authority Owner | Protected By |
|-----------|----------|----------------|--------------|
| Derive legitimacy outcome | Constitutional participation authorization — the fundamental governance decision | `ConstitutionalLegitimacyDecision` | F4, F10 |
| Evaluate evidence quality | Determines what evidence means for participation | `PolicySequence` + Policies | F4 (must not derive legitimacy) |
| Freeze evidence for audit | Ensures replay-deterministic evaluation | `ConstitutionalEvidenceSnapshot` | F3, F9 |

These three capabilities form the **sovereign core** of the system. They are the reason the platform exists.

### Supporting Capabilities

These are necessary but not differentiating:

| Capability | Why Supporting | Authority Owner |
|-----------|---------------|----------------|
| Observe device/network activity | Required input for evaluation, but observation mechanisms are standard | Overlays |
| Certify replay | Required for audit, but the cert format is internal | `ReplaySession` |
| Hash evidence for replay | Supporting infrastructure for F3 determinism | `TrustEvidencePrivacyPolicy` |

### Generic Capabilities

These could be off-the-shelf or outsourced:

| Capability | Why Generic | Best Handled By |
|-----------|------------|-----------------|
| Authenticate user | Standard Laravel auth | Laravel Fortify |
| Check voting code validity | Simple DB query | Eloquent model |
| Render UI | Standard Inertia + Vue | Generic frontend patterns |
| Block/allow HTTP requests | Standard middleware pattern | Laravel middleware |
| Log audit trail | Standard logging | Laravel logging |
| Detect operational divergence | Telemetry comparison | Feature flags + logger |

---

## Capability Flow (Business View)

```
                        ┌─────────────────────────────┐
                        │     OBSERVATION DOMAIN       │
                        │  (Supporting — Infrastructure)│
                        │                              │
                        │  Observe device continuity   │
                        │  Observe IP velocity         │
                        │  Observe attestation         │
                        │  Observe anomalies           │
                        └──────────────┬──────────────┘
                                       │ OverlaySignal[]
                                       ▼
                        ┌─────────────────────────────┐
                        │     PRESERVATION DOMAIN      │
                        │  (Supporting — Integrity)    │
                        │                              │
                        │  Freeze evidence snapshot    │
                        │  Hash for integrity          │
                        │  Minimize PII                │
                        │  Classify provenance         │
                        └──────────────┬──────────────┘
                                       │ ConstitutionalEvidenceSnapshot
                                       ▼
                        ┌─────────────────────────────┐
                        │     EVALUATION DOMAIN        │
                        │  (Supporting — Assessment)   │
                        │                              │
                        │  Evaluate network binding    │
                        │  Evaluate device binding     │
                        │  Evaluate attestation        │
                        │  Orchestrate policy sequence │
                        │  Seal evaluation envelope    │
                        └──────────────┬──────────────┘
                                       │ EvaluationEnvelope
                                       ▼
             ┌─────────────────────────────────────────────┐
             │           LEGITIMACY DOMAIN                 │
             │         (CORE — Business Differentiator)     │
             │                                               │
             │  Derive legitimacy outcome ← PRIMARY DECISION │
             │  Assert constitutional denial                 │
             │  Assert constitutional allowance              │
             │                                               │
             │         ▲ THIS IS THE BUSINESS VALUE ▲        │
             └──────────────┬──────────────────────────────┘
                            │ LegitimacyOutcome
                            ▼
             ┌─────────────────────────────────────────────┐
             │         ENFORCEMENT DOMAIN                   │
             │  (Generic — Laravel conventions)             │
             │                                               │
             │  Block denied voters                         │
             │  Allow approved voters                       │
             │  Redirect for verification                   │
             └─────────────────────────────────────────────┘

    ┌─────────────────────────────────────────────────────────┐
    │  CROSS-CUTTING: REPLAY DOMAIN                           │
    │  (Supporting — Audit & Verification)                    │
    │                                                         │
    │  Open replay session → Record assertion → Certify       │
    │  Feed: Evidence snapshots from Preservation             │
    └─────────────────────────────────────────────────────────┘
```

---

## Capability vs Authority — Key Divergences

These are the places where authority ownership and business capability ownership **do not align**. Each divergence is a potential vocabulary conflict point.

| Concept | Authority Owner Says | Business Capability Says | Resolution |
|---------|--------------------|------------------------|------------|
| `PolicySequence` | "I am an evaluation authority" | "I solve the problem of evidence quality assessment" | The business capability is **evidence quality assessment**. The authority it holds is evaluation. "Policy" is the implementation mechanism, not the capability. |
| `OverlayCoordinator` | "I am an observation authority" | "I solve the problem of collecting contextual signals" | The business capability is **signal collection**. The authority it holds is observation. "Coordinator" is infrastructure, not the capability. |
| `ConstitutionalLegitimacyDecision` | "I am the sole legitimacy authority" | "I solve the problem of constitutional participation authorization" | These converge cleanly — the business capability IS the constitutional authority. This is the core. |
| `ReplaySession` | "I own reconstruction authority" | "I solve the problem of audit verification" | The business capability is **audit verification**. The authority it holds is reconstruction. "Replay" is the method, not the goal. |
| `ConstitutionalEvidenceSnapshot` | "I am an evidence preservation authority" | "I solve the problem of frozen evaluation inputs" | The business capability is **deterministic evaluation input**. The authority is preservation. "Snapshot" is the mechanism. |
| `DivergenceObserver` | "I own migration authority" | "I solve the problem of detecting cutover readiness" | The business capability is **cutover readiness verification**. The authority is temporary (D.0-D.5). "Divergence" is the signal, not the capability. |

### Why This Matters

If the architecture is described only in authority terms:
- Everything becomes sovereignty, legitimacy, authority — opaque governance language
- Engineers struggle to understand what business problem each component solves
- New features are modeled as "authority boundaries" rather than "capability extensions"

If the architecture is described only in capability terms:
- Constitutional rigor erodes — capabilities are implemented without authority protection
- `PolicySequence` becomes "just a policy runner" — not the protected evaluation orchestrator
- `LegitimacyOutcome` becomes "just a status" — not the sovereign constitutional decision

Both descriptions must coexist. The ubiquitous language uses **capability terms** for everyday communication and **authority terms** for constitutional governance.

---

## Boundary Rules

| Rule | Capability Perspective | Authority Perspective |
|------|----------------------|---------------------|
| B1 | Evaluation capabilities must not produce legitimacy decisions | F4: Evaluation must not derive `LegitimacyOutcome` |
| B2 | Observation capabilities must not assess evidence quality | OBS-1: Signals are non-sovereign, non-evaluative |
| B3 | Presentation capabilities must not imply constitutional precedence | F8: No `LegitimacyOutcome` in UI; PRJ-2: flat set |
| B4 | Enforcement capabilities must not re-derive legitimacy | GOV-1: Governance receives outcome, does not derive |
| B5 | Preservation capabilities must not interpret evidence | EVI-4: Classification is categorical, not scalar |
| B6 | Retirement capabilities must not affect current legitimacy | Migration reads outcomes, never derives them |

---

## Capability Gap Analysis

| Missing Capability | Impact | Priority | Notes |
|--------------------|--------|----------|-------|
| Evidence provenance query | No way to ask "what evidence supported this decision" for audit | Medium | Relies on replay certification + logs |
| Human review workflow for Investigate | `LegitimacyOutcome::Investigate` has no defined UI path | Medium | Admin panel gap |
| Divergence dashboard | No unified view of D.0.1 telemetry | Low | Temporary (D.0-D.5) |

---

## Capability-to-Fitness-Function Map

| Fitness Function | Capability Protected | Business Value |
|-----------------|---------------------|---------------|
| F1 (no singular authority) | Observation — signals must not carry authority | Prevents implicit sovereignty |
| F2 (envelope plurality) | Observation — signals are flat, unranked | Prevents ordering-based precedence |
| F3 (replay hash stability) | Preservation — deterministic hashing | Enables replay audit |
| F4 (resolver exclusivity) | Decision — only `ConstitutionalLegitimacyDecision` derives outcome | Protects the core capability |
| F5 (observation preservation) | Evaluation — more observations cannot weaken insufficiency | Prevents compensating legitimacy |
| F6 (sovereignty convergence) | Migration — dual sovereignty equivalence | Ensures safe retirement |
| F7 (no controller trust) | Enforcement — controllers must not derive legitimacy | Prevents procedural sovereignty |
| F8 (no projection legitimacy) | Presentation — UI must not reference `LegitimacyOutcome` | Prevents implied UI authority |
| F9 (replay determinism) | Reconstruction — same input → same outcome | Enables cross-runtime audit |
| F10 (resolver constructor) | Decision — `LegitimacyOutcome` has restricted construction | Compile-time resolver protection |
