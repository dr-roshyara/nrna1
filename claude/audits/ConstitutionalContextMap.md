# Constitutional Context Map

**Phase:** DD.3b — Strategic DDD Discovery (P2)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, CoreDomainIdentification.md
**Status:** Initial — open for Senior Architect review

## Purpose

Full bounded-context map of the constitutional governance architecture. Every context gets mission, ubiquitous language, upstream/downstream relationships, published language, anti-corruption boundaries, and current code locations.

---

## Context Overview

```
┌──────────────────────────────────────────────────────────────────────┐
│                        CONSTITUTIONAL GOVERNANCE                      │
│                                                                      │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────────┐   │
│  │Observation│───▶│ Evidence │───▶│Evaluation│───▶│  Legitimacy  │   │
│  │ (Collect) │    │ (Freeze) │    │ (Assess) │    │  (Derive)    │   │
│  └──────────┘    └──────────┘    └──────────┘    └──────┬───────┘   │
│       │                                                  │           │
│       │                                                  ▼           │
│       │                                          ┌──────────────┐   │
│       │                                          │  Governance  │   │
│       │                                          │  (Enforce)   │   │
│       │                                          └──────────────┘   │
│       │                                                  │           │
│       │                                                  ▼           │
│       │                                          ┌──────────────┐   │
│       │                                          │  Projection  │   │
│       │                                          │  (Present)   │   │
│       │                                          └──────────────┘   │
│       │                                                  │           │
│       │                                          ┌──────────────┐   │
│       └────────────────── Replay ─────────────────▶│  Replay     │   │
│                                                     │ (Certify)   │   │
│                                                     └──────────────┘   │
│                                                                      │
│  ┌──────────────┐    ┌────────────────┐                              │
│  │   Migration  │───▶│  Retirement    │  (Temporary — D.0-D.5)       │
│  │  (Transition)│    │  (Remove)      │                              │
│  └──────────────┘    └────────────────┘                              │
└──────────────────────────────────────────────────────────────────────┘
```

**Pipeline flow (left to right):** Observation → Evidence → Evaluation → Legitimacy → Governance/Projection
**Cross-cutting:** Replay certifies the Evidence→Evaluation→Legitimacy pipeline
**Temporary:** Migration and Retirement manage the D.0-D.5 sovereignty transition

---

## Context 1: Observation

| Aspect | Description |
|--------|-------------|
| **Mission** | What non-authoritative constitutional facts have overlays detected? |
| **Classification** | Supporting Domain |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Observation** | A non-authoritative detection of constitutional fact by an overlay |
| **Signal** | The typed atomic output of a single observation (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT) |
| **Observation Context** | Flat, unranked collection of all signals from one evaluation cycle |
| **Overlay** | Application-layer component that observes one dimension of evidence |
| **Evidence Context** | The hashed/minimized payload carried by a signal |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Signal creation (factual classification) | Legitimacy derivation |
| Observation collection (flat, unranked) | Signal ranking, weighting, ordering |
| Evidence context (hashed/minimized) | Policy evaluation |
| | Scalar scores or trust levels |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Downstream** | Evidence | Customer/Supplier — Observation supplies signals; Evidence freezes them | `OverlaySignal[]` via `ConstitutionalObservationContext` |
| **Upstream** | (Application overlays) | Overlays produce raw signals | `Overlay::observe()` |

### Anti-Corruption Boundaries

- Signals must never carry `LegitimacyOutcome`, `TrustLevel`, or `EvidenceEvaluationState`
- `ConstitutionalObservationContext` must remain a flat collection (no ordering, ranking, or filtering)
- Overlays must never import from Legitimacy Context

### Current Code Locations

```
app/Domain/Election/Security/Simplified/
  ├── OverlaySignal.php
  └── ConstitutionalObservationContext.php

app/Application/Election/Security/
  ├── Overlays/ (7 overlays — produce signals)
  ├── OverlayCoordinator.php (collects signals)
  └── OverlayAggregator.php (aggregates into ConstitutionalObservationContext)
```

---

## Context 2: Evidence

| Aspect | Description |
|--------|-------------|
| **Mission** | What observable facts exist to support or challenge a voter's constitutional participation eligibility? |
| **Classification** | Supporting Domain |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Evidence** | Immutable constitutional fact frozen at evaluation time |
| **Evidence Snapshot** | Frozen, replay-safe bundle of all evidence for one evaluation |
| **Evidence Classification** | Factual classification of evidence provenance (Initial, Attested, ContinuityProven, RegistrarConfirmed) |
| **Eligibility Evidence** | Frozen evidence about voter's constitutional eligibility |
| **Evidence Context** | Hashed/minimized payload (not PII) |
| **Privacy Policy** | Rule set for hashing and minimizing raw evidence |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Evidence preservation (immutable snapshots) | Legitimacy derivation |
| Evidence provenance classification (factual) | Evaluation quality assessment |
| Evidence hashing for replay integrity | Signal interpretation |
| Privacy policy application | Outcome production |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Observation | Customer/Supplier | `OverlaySignal[]` |
| **Downstream** | Evaluation | Customer/Supplier — Evidence provides frozen snapshots | `ConstitutionalEvidenceSnapshot` |

### Anti-Corruption Boundaries

- Evidence must never be re-queried or re-derived during evaluation
- Evidence provenance is categorical, not scalar (must not become a trust score)
- Raw PII must never appear in evidence context (ACL-7 from EvidenceContext.md)
- Evidence snapshots are immutable after creation

### Current Code Locations

```
app/Domain/Election/Security/Simplified/
  ├── ConstitutionalEvidenceSnapshot.php
  ├── ParticipationEligibilityEvidence.php
  └── EvidenceClassification.php

app/Domain/Election/Security/               (root — evidence sources)
  ├── NetworkTrustEvidence.php
  ├── DeviceTrustContext.php
  ├── VerificationAttestationRecord.php
  ├── VotingSessionTrustContinuity.php
  └── TrustEvidencePrivacyPolicy.php
```

---

## Context 3: Evaluation

| Aspect | Description |
|--------|-------------|
| **Mission** | What does the evidence mean for participation readiness? |
| **Classification** | Supporting Domain |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Evaluation State** | Descriptor of evidence quality (SUFFICIENT, INSUFFICIENT, REVIEW_REQUIRED, INCONCLUSIVE) |
| **Evaluation Result** | Bundled output: state + classification + reason + audit context |
| **Evaluation Envelope** | Sealed transport container bundling result + observations + snapshot |
| **Reason Code** | Typed, concrete reason for evaluation conclusion |
| **Evidence Quality** | Descriptive assessment of whether evidence meets threshold |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Evidence quality assessment | Legitimacy outcome derivation |
| Evaluation state classification (SUFFICIENT/INSUFFICIENT/etc.) | Signal interpretation or weighting |
| Reason code assignment | Observation ranking |
| Result envelope sealing | Scalar trust scoring |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Evidence | Customer/Supplier | `ConstitutionalEvidenceSnapshot` |
| **Downstream** | Legitimacy | Published Language — sealed envelope is the sole input | `EvaluationEnvelope` |

### Anti-Corruption Boundaries

- Evaluation must NOT produce `LegitimacyOutcome` (must not import from Legitimacy Context)
- `EvidenceEvaluationState` is a quality descriptor, not an authority level
- `EvaluationResult` is input to the resolver, not the resolver's output
- (ACL-1 from EvidenceContext.md: No Legitimacy Leakage)
- (ACL-2: No Scalar Conversion of categorical classifications)

### Current Code Locations

```
app/Domain/Election/Security/Simplified/
  ├── EvidenceEvaluationState.php
  ├── EvidenceEvaluationResult.php
  ├── EvaluationEnvelope.php
  └── EvaluationReasonCode.php

app/Application/Election/Security/
  ├── SimplifiedPolicySequence.php
  └── Simplified/
      ├── EvidenceCapabilityPolicy.php
      └── ParticipationEligibilityPolicy.php
```

---

## Context 4: Legitimacy

| Aspect | Description |
|--------|-------------|
| **Mission** | Is participation legitimate? |
| **Classification** | **Core Domain** |
| **Stability** | Maturing |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Legitimacy** | Constitutional authority to participate in an election |
| **Legitimacy Outcome** | Sovereign decision — Allowed, Denied, Deferred, Investigate |
| **Resolver** | Exclusive derivation path: `ConstitutionalLegitimacyDecision` |
| **Decision** | The act of mapping evaluation state → legitimacy outcome |
| **Exclusivity** | Only the resolver may produce LegitimacyOutcome |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Legitimacy derivation (EXCLUSIVE) | Evidence collection, observation, policy evaluation |
| LegitimacyOutcome production | Signal interpretation, evidence preservation |
| Participation authority decision | Projection or presentation of state |
| | Governance enforcement actions |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Evaluation | Published Language — sealed envelope is sole input | `EvaluationEnvelope` |
| **Downstream** | Governance | Customer/Supplier | `LegitimacyOutcome` |
| **Downstream** | Projection | ACL — projection must not display outcome | (none — blocked by ACL) |

### Anti-Corruption Boundaries

- F4: Only `ConstitutionalLegitimacyDecision` may derive `LegitimacyOutcome`
- F7: Controllers must not derive or create `LegitimacyOutcome`
- F10: Only the resolver may map `TrustEvaluationState` → `LegitimacyOutcome`
- F8: Projection must not reference `LegitimacyOutcome`
- Legitimacy Context must not import from Observation, Evidence, or Evaluation
- `LegitimacyOutcome` construction must be restricted

### Current Code Locations

```
app/Domain/Election/Security/
  ├── LegitimacyOutcome.php
  ├── TrustEvaluationState.php
  ├── VotingTrustResult.php
  └── TrustEvaluationEnvelope.php

app/Application/Election/Security/
  └── ConstitutionalLegitimacyDecision.php
  └── TrustCapabilityContext.php
  └── TrustPolicyEvaluator.php
  └── PolicySequence.php
  └── Policies/
      ├── DeviceBindingPolicy.php
      ├── NetworkBindingPolicy.php
      └── VerificationAttestationPolicy.php
```

---

## Context 5: Governance

| Aspect | Description |
|--------|-------------|
| **Mission** | What action should the organization take based on legitimacy outcomes? |
| **Classification** | Generic Domain |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Governance** | Administrative enforcement of constitutional outcomes |
| **Enforcement** | The action taken in response to a legitimacy outcome |
| **Election Constitution** | Rules governing election participation |
| **Lifecycle** | Election state progression (setup → active → closed) |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Enforcement actions based on outcomes | Legitimacy derivation |
| Election lifecycle management | Evidence or observation collection |
| Voter roll administration | Signal interpretation |
| | Policy evaluation |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Legitimacy | Customer/Supplier | `LegitimacyOutcome` |
| **Downstream** | (Application) | Controller enforcement | (none — Laravel conventions) |

### Anti-Corruption Boundaries

- Governance must not re-derive legitimacy (must trust the outcome it receives)
- Governance enforcement must not bypass the constitutional path
- Feature flags must be temporary, not permanent governance mechanisms
- (CFB-1 from stabilization certificate)

### Current Code Locations

```
app/Models/Election.php                  (election lifecycle)
app/Http/Controllers/                   (enforcement actions)
app/Http/Middleware/                     (request filtering)
app/Http/Middleware/ValidateVotingIp.php (D.0.1 shadow mode)
config/voting_security.php              (constitutional_mode flag)
```

---

## Context 6: Projection

| Aspect | Description |
|--------|-------------|
| **Mission** | How is constitutional state presented to users and administrators? |
| **Classification** | Generic Domain |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Projection** | Read-only representation of constitutional state |
| **Snapshot** | Frozen presentation data (no behavioral methods) |
| **Observation Summary** | Aggregated overlay findings for UI display |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Read-only state presentation | Legitimacy outcome display |
| Observation rendering (flat, unranked) | Any derivation or recalculation |
| Dashboard display | Precedence or priority ordering |
| Admin panel state | Authority implication through UI |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Observation | ACL — projection renders observations | (none — read-only) |
| **Upstream** | Legitimacy | BLOCKED by F8 — must not reference outcomes | (none) |

### Anti-Corruption Boundaries

- F8: UI/projection must not reference `LegitimacyOutcome`
- No "high risk", "critical", "urgent", "escalated" labels (from C.5i)
- No ranked legitimacy indicators or red-first sovereignty grouping
- Observations rendered as flat set — ordering must not imply precedence
- Table fields exposing sovereign authority are forbidden (H.2 cleanup complete)

### Current Code Locations

```
app/Domain/Election/Security/OverlayObservationSummary.php
app/Domain/Election/Security/Simplified/ConstitutionalTrustSnapshot.php
resources/js/Pages/Vote/                  (Vue components)
resources/js/Pages/Elections/             (admin panels)
```

---

## Context 7: Replay

| Aspect | Description |
|--------|-------------|
| **Mission** | Can sovereign outcomes be deterministically reconstructed from frozen evidence? |
| **Classification** | Supporting Domain |
| **Stability** | Maturing |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Replay** | Deterministic reconstruction of a sovereign outcome |
| **Session** | Bounded lifecycle for one replay certification cycle |
| **Certification** | Immutable record of whether replay matched original |
| **Assertion** | Contract binding evidence envelope + expected outcome |
| **Evidence Envelope** | Sealed container with evidence integrity verification |
| **Compatibility Version** | Schema version marker for cross-runtime compatibility |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Deterministic reconstruction lifecycle | Legitimacy outcome interpretation |
| Certification of match/divergence | Policy evaluation |
| Evidence envelope integrity | Evidence dimension |
| Schema version compatibility | Outcome production |
| Session state management | |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Evidence | Open Host Service | `ReplayEvidenceEnvelope` (wraps snapshot) |
| **Upstream** | Evaluation | Open Host Service | Policy sequence hash |
| **Downstream** | (All) | Open Host Service — certification result for audit | `ReplayCertification` |

### Anti-Corruption Boundaries

- Replay must not re-evaluate evidence (it certifies, not evaluates)
- Session state transitions are guarded (sealed → replayed → certified/diverged)
- Certification is immutable after creation
- F3: Same snapshot → same hash across runtimes
- F9: Same envelope → same certification across runtimes, serialization cycles, timezones

### Current Code Locations

```
app/Domain/Election/Replay/
  ├── ReplaySession.php
  ├── ReplayCertification.php
  ├── ReplayAssertion.php
  ├── ReplayEvidenceEnvelope.php
  ├── ReplayCompatibilityVersion.php
  └── Event/
      ├── ReplaySessionOpened.php
      ├── ReplayCertificationIssued.php
      └── ReplayDivergenceDetected.php
```

---

## Context 8: Migration (Temporary)

| Aspect | Description |
|--------|-------------|
| **Mission** | How do we transition from procedural to constitutional enforcement safely? |
| **Classification** | Generic Domain (Temporary: D.0-D.5) |
| **Stability** | Emerging |

### Ubiquitous Language

| Term | Meaning |
|------|---------|
| **Migration** | Controlled transition from legacy to constitutional enforcement |
| **Divergence** | Authority mismatch between constitutional and procedural paths |
| **Retirement** | Engineering removal of legacy sovereignty paths |
| **Shadow Mode** | Constitutional enforcement observes without blocking |
| **Rollback** | Reversion to dual sovereignty when divergence detected |

### Authority Ownership

| Owns | Must Never Own |
|------|---------------|
| Divergence detection and classification | Legitimacy derivation |
| Transition sequencing (D.0.1 → D.0.3a → D.0.3e) | Policy evaluation |
| Rollback control | Evidence collection |
| | Any permanent authority |

### Relationships

| Direction | Context | Relationship | Published Language |
|-----------|---------|-------------|-------------------|
| **Upstream** | Legitimacy | Divergence comparison | `SovereigntyDivergenceRecord` |
| **Upstream** | (Legacy middleware) | Procedural outcomes for comparison | (none — instrumented) |
| **Downstream** | (Operations) | Rollback signals | Feature flag state |

### Current Code Locations

```
app/Domain/Election/Security/
  ├── SovereigntyDivergenceRecord.php
  ├── DivergenceType.php
  ├── DivergenceSeverity.php
  └── DivergenceCategory.php

config/voting_security.php
app/Http/Middleware/ValidateVotingIp.php
```

---

## Context Relationship Summary

| Upstream | Downstream | Relationship Type | Published Language |
|----------|-----------|-------------------|--------------------|
| Observation | Evidence | **Customer/Supplier** | `OverlaySignal[]` via `ConstitutionalObservationContext` |
| Evidence | Evaluation | **Customer/Supplier** | `ConstitutionalEvidenceSnapshot` |
| Evaluation | Legitimacy | **Published Language** | `EvaluationEnvelope` |
| Legitimacy | Governance | **Conformist** | `LegitimacyOutcome` |
| Legitimacy | Projection | **ACL (blocked)** | None (F8 forbids) |
| Observation | Projection | **ACL (filtered)** | Observation summaries only |
| Replay | (All) | **Open Host Service** | `ReplayCertification` |
| Migration | Legitimacy | **Partnership** (temporary) | `SovereigntyDivergenceRecord` |

---

## Context Count and Maturity

| # | Context | Classification | Stability | Namespace-Ready? |
|---|---------|---------------|-----------|-----------------|
| 1 | Observation | Supporting | Emerging | No |
| 2 | Evidence | Supporting | Emerging | No |
| 3 | Evaluation | Supporting | Emerging | No |
| 4 | Legitimacy | **Core** | Maturing | Already correct |
| 5 | Governance | Generic | Emerging | No |
| 6 | Projection | Generic | Emerging | No |
| 7 | Replay | Supporting | Maturing | Yes (already in `Replay/`) |
| 8 | Migration | Generic (temporary) | Emerging | No |

**Namespace migration candidates:** Only contexts classified as Maturing or Stable and Core or Supporting. Currently: Legitimacy (already correct), Replay (already correct). Evidence, Evaluation, and Observation require further stability analysis before any namespace decision.
