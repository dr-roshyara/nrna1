# Evidence Context

**Status:** Emerging bounded context (DD.3b discovery)
**Stability:** EMERGING (per ContextStabilityAssessment.md — P7)
**Current namespace:** `App\Domain\Election\Security\Simplified` (historical — namespace frozen per P7: no migration for Emerging contexts)
**Phase discovered:** DD.3b — Tactical Semantic Alignment

---

## 1. Mission

The Evidence Context answers: **What observable facts exist to support or challenge a voter's constitutional participation eligibility?**

It owns the lifecycle of evidence — from raw observation through categorization and preservation — and produces evaluation results that the Legitimacy Context consumes. It has no authority over what those results mean or what action should follow.

---

## 2. Ubiquitous Language

| Term | Meaning | Example | Counter-example |
|------|---------|---------|-----------------|
| **Observation** | A non-authoritative detection of some constitutional fact by an overlay. Never decides, never ranks. | "Device fingerprint is consistent with prior sessions" | "This voter is denied" |
| **Signal** | The output of a single observation. Typed (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT). Carries evidence context. | `OverlaySignal::contextStable('device_anomaly', 'Fingerprint match', ['hash' => '...'])` | A scalar risk score |
| **Evidence** | Immutable observed constitutional fact frozen at evaluation time. Hash-verifiable. Replay-safe. | `ConstitutionalEvidenceSnapshot` containing network, device, verification, continuity data | A mutable DB column read at runtime |
| **Evidence Snapshot** | A frozen, replay-safe bundle of all evidence relevant to a single evaluation. "Same input → same output" contract. | `ConstitutionalEvidenceSnapshot` | A live query result |
| **Evidence Classification** | Factual classification of evidence provenance — what exists, not how much to trust it. | `EvidenceClassification::Attested` | `TrustLevel::Attested` (scalar) |
| **Evaluation** | The process of determining what the collected evidence means for participation eligibility. Evaluates evidence quality, does NOT decide authority. | `EvidenceEvaluationResult` | `LegitimacyOutcome::Denied` (authoritative) |
| **Evaluation State** | Descriptor of evidence quality after evaluation. | `SUFFICIENT_EVIDENCE`, `INSUFFICIENT_EVIDENCE`, `REVIEW_REQUIRED`, `INCONCLUSIVE` | A percentage or score |
| **Evaluation Envelope** | Sealed transport container bundling evaluation result, observations, and evidence snapshot. | `EvaluationEnvelope` | The resolver's decision output |
| **Reason Code** | Concrete, typed reason why evaluation reached its conclusion. | `EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT` | A free-text string |
| **Eligibility Evidence** | Frozen evidence about voter's constitutional eligibility at evaluation time. | `ParticipationEligibilityEvidence` | `$user->canVote()` (authoritative check) |

---

## 3. Authority Ownership

### Owns Authority

| Authority | Rationale |
|-----------|-----------|
| **Evidence preservation** | Evidence snapshots freeze evaluation inputs deterministically |
| **Evidence classification** | Provenance classification is factual, not evaluative |
| **Evaluation state** | Evidence quality is a factual descriptor, not an authority |
| **Evaluation result shape** | The result type defines what an evaluation output looks like |
| **Reason classification** | Reasons are typed enums, not authoritative judgments |
| **Evaluation transport** | The envelope seals result + observations + snapshot together |

### Must Never Own

| Prohibited Authority | Why |
|---------------------|-----|
| **Legitimacy derivation** | Only `ConstitutionalLegitimacyDecision` (Legitimacy Context) may derive `LegitimacyOutcome` |
| **Governance decisions** | This context has no authority over election governance rules |
| **Authorization** | It does not grant or deny participation |
| **Signal ranking/weighting** | Observations are a flat set with no ordering precedence |
| **Scalar trust scoring** | Evidence classification is categorical, not scalar |
| **Replay certification** | Replay is a separate context with its own certification authority |

---

## 4. Boundaries

### In Scope

- Collecting overlay observations as flat signal sets
- Freezing evidence at evaluation time for replay determinism
- Classifying evidence provenance (not trust level)
- Evaluating evidence quality (not legitimacy)
- Producing typed reason codes for evaluation outcomes
- Transporting evaluation results to the Legitimacy Context via sealed envelope
- Preserving eligibility evidence as frozen, hash-verifiable data

### Out of Scope

- Deriving `LegitimacyOutcome` (Denied, Allowed, Deferred, Investigate)
- Deciding whether a voter can participate
- Ranking or weighting observations
- Managing replay sessions or certifications
- Emitting sovereign domain events (those belong to Legitimacy context only — per P4, Governance is a Generic Domain, not a formal BC)
- Projecting constitutional state to UI
- Storing or managing election configurations

### Upstream Contexts

| Upstream | What flows into Evidence Context |
|----------|----------------------------------|
| **Observation Context** | `OverlaySignal[]` from overlay evaluation (independent BC per P3) |
| **Identity Infrastructure** | Device fingerprint, network identity, session continuity (infrastructure concern) |

### Downstream Contexts

| Downstream | What flows out of Evidence Context |
|------------|-------------------------------------|
| **Legitimacy Context** | `EvaluationEnvelope` → `ConstitutionalLegitimacyDecision::decide()` |
| **Replay Context** | `ConstitutionalEvidenceSnapshot` for replay certification |
| **Event system** | Evaluation state changes (telemetry, not sovereign) |

### Flow Diagram

```
Observation Context
    │
    │ OverlaySignal[]
    ▼
    ┌─────────────────────────────────────┐
    │    Evidence Context                 │
    │                                     │
    │  OverlaySignal[]         ┌────────┐ │
    │  + Evidence Snapshot ──▶ │Evaluate│ │
    │  + Eligibility Ev.       │Pipeline│ │
    │                          └────────┘ │
    │                              │      │
    │                     EvaluationEnvelope
    └──────────────────────────────│──────┘
                                  │
                                  ▼
                     ┌──────────────────────┐
                     │ Legitimacy Context    │
                     │                      │
                     │ Constitutional        │
                     │ LegitimacyDecision    │
                     │                      │
                     │ → LegitimacyOutcome   │
                     └──────────────────────┘
                                  │
                                  ▼
                     ┌──────────────────────────────┐
                     │ Governance (Generic Domain)   │
                     │                              │
                     │ → Enforcement action          │
                     │   (Laravel conventions)       │
                     └──────────────────────────────┘
```

---

## 5. Invariants

Cross-reference: Each invariant is mapped to its ID in the BusinessInvariantCatalog (P7a).

### I-1: Evidence is frozen at evaluation time → **EVI-1** (also LEG-3 at resolver boundary)

```
Every evidence snapshot is created once at evaluation entry.
No evidence is re-queried, re-fetched, or re-derived during evaluation.
```

**Enforcement:** `ConstitutionalEvidenceSnapshot` is `readonly`. Convention-based at resolver entry (LEG-3 gap identified).

### I-2: Observations are a flat set → **OBS-2**

```
Signals have no ordering, ranking, or weighting.
The set of observations is complete — no signal is filtered out.
```

**Enforcement:** F2 fitness function (envelope plurality).

### I-3: Evaluation state is not authority → **EVL-1**

```
EvidenceEvaluationState describes evidence quality, not legitimacy.
Only ConstitutionalLegitimacyDecision maps state → outcome.
```

**Enforcement:** F4, F10 fitness functions (resolver exclusivity).

### I-4: Reason codes are typed → **EVL-2**

```
Every evaluation result carries a typed EvaluationReasonCode.
Stringly-typed reasons are forbidden in the evaluation pipeline.
```

**Enforcement:** Constructor typehint on `EvidenceEvaluationResult`.

### I-5: Evidence snapshots are replay-safe → **REP-1** (also **EVI-2** for hash integrity)

```
Same frozen evidence → same evaluation result.
Evidence hash must be reproducible across processes, hosts, and runtimes.
```

**Enforcement:** F3 (replay hash stability), F9 (replay determinism).

### I-6: Classification is factual, not scalar → **EVI-4**

```
EvidenceClassification describes what provenance exists.
It must never become a scalar trust score or confidence level.
```

**Enforcement:** ⚠️ Convention-based — no structural enforcement (gap identified in P7a).

### I-7: No raw PII in observations → **EVI-3**

```
OverlaySignal evidence context contains only hashed or minimized data.
Raw IP addresses, device fingerprints, or identity values never appear.
```

**Enforcement:** `TrustEvidencePrivacyPolicy` hashes before domain processing.

---

## 6. Aggregates

> **⚠️ Aggregate discovery DEFERRED per Senior Architect directive (DD.3b, P9 gate).**
>
> DDD aggregates are consistency boundaries, not classification labels. They must be designed AFTER context boundaries are approved. The candidates below are speculative — they reflect likely aggregate boundaries but have not been validated through the DDD aggregate process.

### Candidate: Evidence Preservation Aggregate (Speculative)

**Suspected root:** `ConstitutionalEvidenceSnapshot`

```
ConstitutionalEvidenceSnapshot
├── ElectionConstitutionSnapshot (value object)
├── VerificationEvidence (value object)
├── NetworkEvidence (value object)
├── DeviceEvidence (value object)
├── SessionContinuity (value object)
├── ParticipationEligibilityEvidence (value object)
├── evaluatedAt: DateTimeImmutable
└── constitutionalHash: string (root signature for replay verification)
```

**Suspected invariant:** The hash covers all evidence fields. Any mutation invalidates the hash.

### Candidate: Evaluation Envelope Aggregate (Speculative)

**Suspected root:** `EvaluationEnvelope`

```
EvaluationEnvelope
├── EvidenceEvaluationResult (value object)
├── EvidenceClassification
├── EvaluationReasonCode
├── ConstitutionalObservationContext
└── ConstitutionalEvidenceSnapshot (reference)
```

**Suspected invariant:** The envelope is sealed once created. No post-creation mutation.

### Not an Aggregate: Observation Collection

`ConstitutionalObservationContext` is a flat value-object container for `OverlaySignal[]`. It has no lifecycle, no identity, and no consistency boundary separate from the Evaluation Envelope. Observations flow through Evidence but are owned by the Observation Context (P3).

**Next step:** Revisit aggregate design after P9 (Context Boundaries Approved). The BusinessInvariantCatalog (P7a) provides the invariant foundation for formal aggregate discovery.

---

## 7. Domain Events

The Evidence Context emits preservation-scoped events (non-sovereign):

| Event | When Emitted | Category | Owner (P5) |
|-------|-------------|----------|------------|
| `EvidenceFrozen` (proposed) | Evidence snapshot is sealed | Observational (non-authoritative) | **Evidence** |
| `EvidenceEvaluationCompleted` | Evaluation pipeline produces a result | Internal (context event) | **Evaluation** |

The `ObservationRecorded` event is emitted by the **Observation Context** (per DomainEventOwnershipMatrix.md — P5), not by Evidence. Observation owns signal creation; Evidence freezes, doesn't observe.

These are **non-sovereign events** — they inform but do not decide.

Sovereign events (like `LegitimacyEvaluated`, `ConstitutionalDenialIssued`) belong to the Legitimacy Context downstream.

---

## 8. Policies

### Overlay Observation Policy

Each overlay observes exactly one dimension of evidence:
- `DeviceAnomalyOverlay` — device fingerprint continuity
- `IpVelocityOverlay` — network velocity and IP binding
- `EmergencyConditionOverlay` — emergency/contingency status
- `RegistrarAttestationElevation` — registrar verification status
- `SuspiciousActivityOverlay` — suspicious behavior detection

**Rule:** Overlays return signals. They never interpret signals.

### Evidence Classification Policy

Evidence provenance is classified using `EvidenceClassification`:
- `Initial` — baseline, no additional evidence yet
- `Attested` — device or network attested
- `ContinuityProven` — session continuity established
- `RegistrarConfirmed` — registrar provided official verification

**Rule:** Classification is factual. It does not imply trustworthiness.

### Evaluation State Policy

Evidence quality is described using `EvidenceEvaluationState`:
- `SUFFICIENT_EVIDENCE` — all required evidence present and consistent
- `INSUFFICIENT_EVIDENCE` — required evidence missing or failed checks
- `REVIEW_REQUIRED` — evidence requires constitutional review
- `INCONCLUSIVE` — evidence cannot be definitively evaluated

**Rule:** State describes evidence, not legitimacy.

---

## 9. Relationships to Other Contexts

### Evidence Context → Legitimacy Context

```
Evidence Context                   Legitimacy Context
────────────────────────────       ────────────────────────────
EvaluationEnvelope          ──▶    ConstitutionalLegitimacyDecision::decide()
EvidenceEvaluationState            ↓
EvidenceClassification             LegitimacyOutcome
EvaluationReasonCode                (Allowed, Denied, Deferred, Investigate)
```

**Relationship:** Downstream consumer. Evidence produces evaluation results; Legitimacy interprets them. Strict one-way dependency.

**Constraint:** Evidence Context must never import from Legitimacy Context. The resolver sits BETWEEN the two, not inside either.

### Evidence Context → Replay Context

```
Evidence Context                   Replay Context
────────────────────────────       ────────────────────────────
ConstitutionalEvidenceSnapshot ──▶ ReplaySession
ParticipationEligibilityEvidence   ReplayCertification
                                  ReplayAssertion
```

**Relationship:** Evidence snapshots are the primary replay input. Replay Context certifies that the same evidence produces the same outcome.

**Constraint:** Evidence snapshots must carry a deterministic hash compatible with Replay Context's certification requirements.

### Evidence Context → Observation Context

**Assessment (P3):** Independent bounded context. Observation owns signal creation; Evidence freezes and preserves. The relationship is **Customer/Supplier** — Observation supplies `OverlaySignal[]`; Evidence consumes them.

**Current co-location:** Both share the historical `Security\Simplified` namespace. This is a legacy artifact, not an architectural coupling. Per P7 (ContextStabilityAssessment), no namespace migration should proceed for Emerging contexts. The co-location is acceptable until P10.

**Constraint:** Evidence must never create `OverlaySignal` directly. Signal creation is Observation's authority.

### Evidence Context → Governance

Governance is a **Generic Domain** (per P4 — GovernanceLegitimacyBoundaryAssessment.md). It does not warrant DDD bounded-context formalization. Enforcement actions (block, allow, redirect) are handled by Laravel conventions (controllers, middleware, policies). The constraint from GOV-2 applies: Governance may block what Legitimacy allows, but must never allow what Legitimacy denies.

### Evidence Context → Event System

Observational events (`EvidenceFrozen`, `EvidenceEvaluationCompleted`) flow from the Evidence/Evaluation contexts into the shared event system. Sovereign events (`LegitimacyEvaluated`, `ConstitutionalDenialIssued`) are emitted downstream by the Legitimacy Context (per P5).

---

## 10. Anti-Corruption Rules

### ACL-1: No Legitimacy Leakage

```php
// FORBIDDEN in Evidence Context:
use App\Domain\Election\Security\LegitimacyOutcome;

// FORBIDDEN:
if ($evaluationState === EvidenceEvaluationState::SUFFICIENT_EVIDENCE) {
    return LegitimacyOutcome::Allowed; // WRONG — not evidence's decision
}
```

### ACL-2: No Scalar Conversion

```php
// FORBIDDEN in Evidence Context:
$score = match ($classification) {
    EvidenceClassification::Initial => 0,
    EvidenceClassification::Attested => 50,
    EvidenceClassification::RegistrarConfirmed => 100,
}; // WRONG — categorical ≠ scalar
```

### ACL-3: No Observation Ordering

```php
// FORBIDDEN in Evidence Context:
usort($signals, fn($a, $b) => $a->signalType <=> $b->signalType);
// WRONG — observations are a flat set
```

### ACL-4: No Evidence Override

```php
// FORBIDDEN after snapshot creation:
$evidence->maxVotesPerIp = 10; // WRONG — evidence is frozen
```

### ACL-5: Namespace Stability (Pending P10)

```php
// The Evidence Context currently resides in Security\Simplified (historical).
// Per ContextStabilityAssessment.md (P7): Evidence is EMERGING — no namespace migration.
// Revisit at P10 (Namespace Decisions) after context boundaries are approved.
// The Security\Simplified namespace is acceptable until P10.
```

---

## 11. Published Language

Refer to PublishedLanguageMatrix.md (P6) for the full cross-context type map.

| Consumer | Published Language | Form | Contract |
|----------|-------------------|------|----------|
| **Evaluation** | `ConstitutionalEvidenceSnapshot` | Frozen data + hash | Same snapshot → same hash across runtimes |
| **Replay** | `ConstitutionalEvidenceSnapshot` | Wrapped in `ReplayEvidenceEnvelope` | Evidence integrity is hash-verifiable |

**Anti-corruption:** The snapshot must NOT embed overlay logic or signal interpretation. Evidence is frozen facts, not interpreted observations.

---

## 12. References

| Artifact | Relevance |
|----------|-----------|
| BusinessInvariantCatalog.md (P7a) | Invariant cross-reference for I-1 through I-7 |
| ContextStabilityAssessment.md (P7) | Evidence is EMERGING — no namespace migration |
| ObservationContextAssessment.md (P3) | Observation is an independent BC |
| GovernanceLegitimacyBoundaryAssessment.md (P4) | Governance is a Generic Domain |
| DomainEventOwnershipMatrix.md (P5) | Event ownership: ObservationRecorded → Observation |
| DomainCommandOwnershipMatrix.md (P5a) | Command ownership: FreezeEvidence → Evidence |
| PublishedLanguageMatrix.md (P6) | Full cross-boundary type map |
| AuthorityOwnershipMatrix.md (P1) | Evidence authority boundaries |
| BusinessCapabilityMap.md (P7b) | Evidence capabilities cross-referenced |

---

## Appendix: Current Type Inventory

### Observation Cluster (Belongs to Observation Context per P3)

| Type | Location | Target Context |
|------|----------|---------------|
| `OverlaySignal` | `Security\Simplified` | **Observation** (independent BC) |
| `ConstitutionalObservationContext` | `Security\Simplified` | **Observation** (independent BC) |

### Evidence Cluster

| Type | Location | Target Context |
|------|----------|---------------|
| `ConstitutionalEvidenceSnapshot` | `Security\Simplified` | Evidence |
| `ParticipationEligibilityEvidence` | `Security\Simplified` | Evidence |
| `EvidenceClassification` | `Security\Simplified` | Evidence |

### Evaluation Pipeline Cluster

| Type | Location | Target Context |
|------|----------|---------------|
| `EvidenceEvaluationState` | `Security\Simplified` | Evaluation |
| `EvidenceEvaluationResult` | `Security\Simplified` | Evaluation |
| `EvaluationEnvelope` | `Security\Simplified` | Evaluation |
| `EvaluationReasonCode` | `Security\Simplified` | Evaluation |

**Note:** All types remain in `Security\Simplified` pending P10 (Namespace Decisions). Per P7, no namespace migration for Emerging contexts.

### Stale Duplicates (Deleted)

| Type | Status |
|------|--------|
| `BallotAuthorizationProtocol` | Deleted — root copy canonical |
| `TrustEvaluationState` | Deleted — root copy canonical |
| `TrustLevel` | Deleted — root copy canonical |

### Diverged Types (Pending Namespace Decision)

| Type | Status |
|------|--------|
| `ElectionSecurityEvent` | Kept in Simplified — different interface from root |
| `VotingTrustResult` | Kept in Simplified — uses EvaluationReasonCode |
| `TrustEvaluationEnvelope` | Kept in Simplified — different architectural role |
