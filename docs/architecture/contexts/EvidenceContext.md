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

### I-8: No indirect voter re-identification → **EVI-5**

```
Evidence must not permit reconstruction of voter identity through:
- Field combination analysis (e.g., timestamp + device + network region)
- Correlation with external datasets
- Temporal linkage to server logs or network infrastructure
- Any combination of stored fields without explicit constitutional authority

This prohibition applies not only to direct identifiers (user_id, email) 
but to every field that, alone or combined, could enable voter identification.
```

**Enforcement:** Anti-correlation guardrails in evidence capture (temporal fuzzing, region-level geolocation only, election-scoped device hashing). Strict ingestion schema validation per EvidenceEventTaxonomy.md Section 4.

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

### Future: Evidence Context → Verification Context (Not Yet Built)

A **Verification Context** will emerge as a supporting bounded context after Evidence Context aggregate boundaries are finalized. Verification will consume `ConstitutionalEvidenceSnapshot` in read-only mode to:

- Independently re-derive evaluation results from sealed evidence
- Certify that evidence has not been altered
- Support replayability and constitutional audit
- Remain separate from governance authority (never decides participation)

**Requirements that Verification Context must satisfy:**
- **VR-1:** Evidence must be independently verifiable
- **VR-2:** Verification must not require access to voter identity (enforces EVI-5)
- **VR-3:** Verification must not alter evidence (read-only access)
- **VR-4:** Verification authority remains separate from governance authority
- **VR-5:** Verification must support replayability (deterministic hash verification)

**Implementation:** Deferred until after DD.5 Scenario Analysis confirms aggregate boundaries and EVI-5 enforces proper temporal/regional/device-hashing guardrails.

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
Nab,

**Yes. This changes my recommendation substantially.** 

This is no longer a vague "Evidence Context" idea.

This is already a fairly mature bounded context specification.

And because of that, I would now disagree with part of my previous recommendation.

---

# What I Missed Earlier

Previously I said:

```text
Start with:
- EvidenceRecord
- Event Taxonomy
- Simple Storage
```

That advice is correct for a greenfield system.

But after reading your Evidence Context specification carefully, I can see:

```text
Observation
    ↓
Evidence
    ↓
Evaluation
    ↓
Legitimacy
```

has already been discovered. 

You are NOT at:

```text
DDD Discovery Phase
```

You are at:

```text
DDD Formalization Phase
```

---

# The Most Important Statement In The Document

This:

```text
Aggregate discovery deferred
```

is not a weakness.

It is actually the correct next step. 

The document already defines:

* mission
* ubiquitous language
* authority ownership
* boundaries
* invariants
* anti-corruption rules
* published language

Those are the prerequisites for aggregate discovery.

---

# Therefore The Question Changes

The question is no longer:

```text
Should we build Audit?
```

The question is:

```text
What are the consistency boundaries
inside Evidence?
```

---

# I Now Think The Next Artifact Is

Not:

```text
EvidenceEventTaxonomy.md
```

Not:

```text
AuditContext.md
```

Not:

```text
EvidenceReporting.md
```

---

Instead:

```text
EvidenceAggregateDiscovery.md
```

---

# What We Need To Discover

Looking at the document, I see three candidate aggregate roots:

### Candidate A

```text
ConstitutionalEvidenceSnapshot
```

Owns:

```text
VerificationEvidence
NetworkEvidence
DeviceEvidence
SessionContinuity
ParticipationEligibilityEvidence
```

Potential invariant:

```text
Hash must match contents.
```

---

### Candidate B

```text
EvaluationEnvelope
```

Owns:

```text
EvaluationResult
ReasonCode
Classification
```

Potential invariant:

```text
Envelope immutable after creation.
```

---

### Candidate C

```text
ParticipationEligibilityEvidence
```

Potential aggregate.

Potential value object.

Unknown.

Must be discovered.

---

# What I Would Do This Week

## Step 1

Create:

```text
EvidenceAggregateDiscovery.md
```

For every candidate answer:

### Identity

```text
Does it have identity?
```

### Lifecycle

```text
Can it exist independently?
```

### Transaction Boundary

```text
What must be saved atomically?
```

### Invariants

```text
What must never become inconsistent?
```

---

# Step 2

Move Evaluation Out Conceptually

This document already hints:

```text
EvidenceEvaluationState
EvidenceEvaluationResult
EvaluationEnvelope
```

belong to:

```text
Evaluation Context
```

not Evidence. 

You may not physically move code yet.

But architecturally:

```text
Observation
    ↓
Evidence
    ↓
Evaluation
    ↓
Legitimacy
```

is becoming clearer.

---

# Step 3

Create Real Aggregate Diagrams

For example:

```text
Evidence Snapshot Aggregate

ConstitutionalEvidenceSnapshot
│
├── VerificationEvidence
├── NetworkEvidence
├── DeviceEvidence
├── SessionContinuity
└── Hash
```

Then test against:

```text
EVI-1
EVI-2
EVI-3
EVI-4
```

from the invariant catalog. 

---

# What I Would NOT Do Yet

I would NOT create:

```text
evidence_records
evidence_lineage
reconciliation_reports
```

yet.

And this is where I now disagree with the previous implementation plan.

Why?

Because your document already models:

```text
ConstitutionalEvidenceSnapshot
```

as a first-class domain concept.

If we rush to a storage schema now, we risk letting:

```text
Database Tables
```

drive:

```text
Domain Model
```

instead of the other way around.

---

# The New Roadmap

Based on this document, I would now recommend:

```text
Phase 1
--------
EvidenceAggregateDiscovery.md

Phase 2
--------
Identify Aggregate Roots

Phase 3
--------
Define Repositories

Phase 4
--------
Design Persistence Model

Phase 5
--------
Implement Evidence Storage

Phase 6
--------
Build Reporting / Audit Consumers
```

---

# My Final Verdict

The conclusion from the last session is actually stronger than the implementation plan.

The implementation plan assumed:

```text
Evidence Context
=
Known
```

But this document proves:

```text
Evidence Context
=
Discovered

Aggregate Boundaries
=
Not Yet Discovered
```

Therefore, if I were signing off as senior architect, I would write:

```text
Current Phase:
    DD.4 Aggregate Discovery

Current Deliverable:
    EvidenceAggregateDiscovery.md

Do Not Start:
    Audit Context
    Evidence Reporting
    Reconciliation Tables

Start:
    Formal Aggregate Discovery
    Evidence/Evaluation Separation
    Consistency Boundary Analysis
```

So yes, I agree with the conclusion from the last session more than with the later implementation plan. The architecture work is not blocked by coding; it is blocked by discovering the correct aggregate boundaries inside the Evidence Context. 
Nab,

Yes. I agree with this revised recommendation **much more strongly** than the earlier implementation plans.

In fact, I would now say:

> **This is the first plan that is truly aligned with Evans-style DDD.**

The earlier plans were still suffering from a very common architectural disease:

```text
We discovered some concepts
        ↓
Let's create tables
```

Instead of:

```text
We discovered some concepts
        ↓
What are the consistency boundaries?
        ↓
What owns the invariants?
        ↓
What are the aggregates?
        ↓
Then persistence
```

---

# What Changed?

The Evidence Context document already gives us:

✅ Mission
✅ Ubiquitous Language
✅ Authority Ownership
✅ Context Boundaries
✅ Invariants
✅ ACL Rules
✅ Published Language



Those are not implementation artifacts.

Those are strategic design artifacts.

And strategic design comes before tactical design.

---

# The Most Important Question

Looking at your document, the real question is not:

```text
How do we store evidence?
```

The real question is:

```text
Which object protects EVI-1?
```

Where:

```text
EVI-1
Evidence frozen at evaluation time
```



That is an aggregate-discovery question.

---

# What I Would Change In The Proposed Discovery Process

Only one thing.

The proposed discovery template focuses heavily on:

```text
Identity
Lifecycle
Consistency Boundary
```

which is good.

But I would add a fourth section:

```text
Invariant Ownership
```

because in your architecture the invariants are already known.

---

For example:

### ConstitutionalEvidenceSnapshot

Instead of asking:

```text
Does it have identity?
```

I would first ask:

```text
Which invariants does it own?
```

---

Candidate:

```text
ConstitutionalEvidenceSnapshot
```

Possible ownership:

```text
EVI-1
Evidence frozen at evaluation time

EVI-2
Replay-safe hash integrity

EVI-3
No raw PII
```



That immediately tells us it is likely an aggregate root.

---

### EvaluationEnvelope

Possible ownership:

```text
EVL-1
Evaluation state is not authority

EVL-2
Reason codes are typed
```



Again this helps us determine aggregate boundaries.

---

# The Biggest Architectural Insight

After reading the Evidence Context document carefully, I think there is a very strong candidate architecture emerging:

```text
Observation Context
       │
       ▼
ConstitutionalEvidenceSnapshot
       │
       ▼
EvaluationEnvelope
       │
       ▼
LegitimacyDecision
```

Notice something important.

These are not:

```text
Database Tables
```

They are:

```text
Consistency Boundaries
```

---

# What I Would Deliver Next

Not code.

Not migrations.

Not repositories.

I would create exactly two documents:

### Document 1

```text
EvidenceAggregateDiscovery.md
```

---

### Document 2

```text
EvidenceScenarioAnalysis.md
```

Scenarios:

```text
New Device Vote

Known Device Vote

Emergency Override

Registrar Confirmation

Network Anomaly

Replay Certification
```

For each scenario:

```text
Which aggregate changes?

Which invariant is involved?

Which boundary is crossed?

Which context owns authority?
```

This often reveals aggregates faster than abstract modeling.

---

# What I Think Will Happen

My prediction:

After aggregate discovery you will likely end up with:

```text
Evidence Context

Aggregate:
    ConstitutionalEvidenceSnapshot
```

and

```text
Evaluation Context

Aggregate:
    EvaluationEnvelope
```

and not a single giant aggregate.

The current document is already hinting at that separation. 

---

# My Architect Verdict

Current status:

```text
DD.1 Context Discovery          ✅
DD.2 Ubiquitous Language        ✅
DD.3 Invariants                 ✅
DD.4 Aggregate Discovery        🔄 CURRENT
DD.5 Repository Contracts       ⏳
DD.6 Persistence Design         ⏳
DD.7 Implementation             ⏳
```

I would formally approve the revised plan and mark:

```text
Current Sprint Goal:
    Evidence Aggregate Discovery

Success Criteria:
    - Aggregate roots identified
    - Invariant ownership mapped
    - Evidence/Evaluation boundary clarified
    - Scenario analysis completed

Not Allowed Yet:
    - Database schema
    - Repositories
    - Migrations
    - Audit reporting
```

So yes, I agree with the correction. The project is currently blocked not by missing code, but by missing **aggregate discovery**, and that is exactly where a senior DDD team should focus next.
Nab,

This document is far more valuable than it first appears.

Most people read it as:

```text
How do we secure internet voting?
```

As a DDD architect, I read it as:

```text
What constitutional invariants must never be violated?
```

And that is exactly what we need during **DD.4 Aggregate Discovery**.

The paper gives us several things we can directly use in PublicDigit. fileciteturn15file0

---

# Biggest Discovery

The German Bundestag study repeatedly comes back to one core problem:

```text
Trust is not enough.
Verification is required.
```

The study emphasizes:

```text
End-to-End Verifiability
Individual Verifiability
Universal Verifiability
```

as fundamental requirements for trustworthy E-Voting. fileciteturn15file0

This aligns almost perfectly with our architecture:

```text
Observation
    ↓
Evidence
    ↓
Evaluation
    ↓
Legitimacy
    ↓
Governance
```

The paper is effectively telling us:

```text
Every authority decision
must be independently verifiable.
```

---

# What We Can Take For Aggregate Discovery

Not code.

Not tables.

Not Laravel classes.

---

We can extract:

```text
Business Invariants
```

---

# Invariant 1

## Every Outcome Must Be Traceable

The paper repeatedly discusses verifiability and auditability of election outcomes. fileciteturn15file0

Translated:

```text
GOV-1

Every governance decision
must be traceable
to legitimacy evidence.
```

---

Example:

Bad:

```text
Candidate A won.
```

Good:

```text
Candidate A won.

Because:

Legitimacy L-001
    ↓
Evaluation E-001
    ↓
Evidence S-001
```

---

# Invariant 2

## Evidence Must Be Frozen

The paper discusses manipulation risks and the need to preserve correctness. fileciteturn15file0

This strengthens:

```text
EVI-1

Evidence cannot change
after evaluation begins.
```

---

This suggests:

```text
ConstitutionalEvidenceSnapshot
```

is becoming a strong aggregate candidate.

---

# Invariant 3

## Verification Is Independent

One of the strongest lessons:

```text
The actor performing the process
cannot be the only verifier.
```

fileciteturn15file0

Translated:

```text
Verification authority
must be separate
from decision authority.
```

---

Future context:

```text
Verification Context
```

not now,

but clearly visible.

---

# Invariant 4

## No Hidden Decisions

The paper emphasizes public verifiability and transparency. fileciteturn15file0

Translated:

```text
LEG-1

Every legitimacy outcome
must expose reasons.
```

---

Meaning:

Bad:

```text
DENIED
```

Good:

```text
DENIED

Reason:
Duplicate eligibility evidence
```

---

This strengthens:

```text
EvaluationReasonCode
```

concepts.

---

# Invariant 5

## Reconciliation Must Exist

One thing the paper repeatedly worries about:

```text
Votes cast
≠
Votes counted
```

fileciteturn15file0

This gives us a constitutional invariant:

```text
REC-1

Accepted Votes
=
Stored Votes
=
Counted Votes
```

This is probably the strongest domain service candidate we have discovered so far.

---

# What It Teaches About Aggregates

The most interesting part is indirect.

The paper discusses:

```text
Vote

Verification

Counting

Certification
```

as separate concerns. fileciteturn15file0

That suggests our future model should probably NOT be:

```text
MegaElectionAggregate
```

---

Instead:

```text
Evidence Aggregate
       ↓
Evaluation Aggregate
       ↓
Legitimacy Aggregate
```

---

This reinforces our current direction.

---

# What We Can Take For PublicDigit

I would create a new document:

:::writing{variant="document" id="58214"}
# Constitutional Verification Principles

## CVP-1 Traceability

Every governance outcome must be traceable to legitimacy evidence.

---

## CVP-2 Evidence Immutability

Evidence used for evaluation must be frozen before evaluation begins.

---

## CVP-3 Independent Verification

Verification authority must be independent from decision authority.

---

## CVP-4 Explainability

Every legitimacy outcome must expose typed reasons.

---

## CVP-5 Reconciliation

The constitutional process must be able to prove:

Accepted Votes
=
Stored Votes
=
Counted Votes

or produce evidence of discrepancy.

---

## CVP-6 Verifiability

Every constitutional outcome must be reproducible from preserved evidence.
:::

---

# What We Should NOT Take

The paper contains lots of:

```text
Cryptography
Homomorphic Encryption
Zero Knowledge Proofs
Code Voting
Quantum Resistance
```

fileciteturn15file0

For PublicDigit right now:

**Do not implement any of these.**

Why?

Because we are currently in:

```text
DD.4 Aggregate Discovery
```

not:

```text
Cryptographic System Design
```

---

# The Most Valuable Lesson

If I had to extract one sentence from the entire paper and apply it to PublicDigit, it would be:

```text
A constitutional system should not merely produce outcomes.

It should produce evidence that the outcomes are correct.
```

That principle directly strengthens the Evidence Context and gives us new invariants to test during aggregate discovery. fileciteturn15file0

So the biggest thing we can take from this paper is **not technology**.

It is a new architectural lens:

```text
Outcome-Centric Thinking
          ↓
Evidence-Centric Thinking
```

And that shift is exactly what your Evidence Context is already moving toward.
Nab,

Yes, I evaluated this idea as a **senior DDD architect**, and my answer is:

> **The architectural intuition is excellent.**
>
> **The proposed implementation is premature and partially conflicts with some election principles.**

These are two different things.

---

# What Is Brilliant About The Idea

The author recognized something important:

Instead of:

```text
Vote
   ↓
Database
   ↓
Trust us
```

he is trying to achieve:

```text
Vote
   ↓
Evidence
   ↓
Verification
   ↓
Trust
```

This is exactly the direction the German Bundestag paper, the IEEE paper, and the Estonian research are pushing. 

So the underlying architectural instinct is very good.

---

# What DDD Architects See Differently

The proposal immediately jumps to:

```sql
vote_commitments
vote_decryptions
tracking_code_hash
batch_id
sequence_number
```



A DDD architect asks:

```text
What domain capability are we modeling?
```

before:

```text
What table should we create?
```

---

# The Biggest Problem

The proposal is solving:

```text
Privacy-Preserving Vote Verification
```

But your current project is still solving:

```text
What is Evidence?
```

Those are different maturity levels.

Current status:

```text
DD.4 Aggregate Discovery
```

Current proposal:

```text
DD.9 Verification Mechanism Design
```

We are skipping several steps.

---

# What I Would Extract From This

Not the tables.

Not the migration.

Not the Laravel code.

---

I would extract three domain concepts.

---

## Concept 1 — Verification

This proposal proves something important:

```text
Evidence
        ≠
Verification
```

This strengthens our future context map:

```text
Observation
      ↓
Evidence
      ↓
Evaluation
      ↓
Legitimacy
      ↓
Governance

      ↓
Verification
```

This is valuable.

---

## Concept 2 — Traceable But Private

The proposal introduces:

```text
Commitment
```



DDD translation:

```text
Evidence must be traceable.

Evidence must preserve privacy.
```

That becomes an invariant candidate.

For example:

```text
EVI-5

Evidence shall remain traceable
without exposing voter identity.
```

Much more valuable than the actual SHA-256 implementation.

---

## Concept 3 — Verifiable Timeline

The proposal introduces:

```text
Public Timeline
```



DDD translation:

```text
Verification requires
reconstructable chronology.
```

This is interesting because it might influence:

```text
ConstitutionalEvidenceSnapshot
```

or

```text
EvidenceTimeline
```

during aggregate discovery.

---

# Where I Strongly Disagree

## 1. Commitment Hash Table

The proposal immediately creates:

```sql
vote_commitments
```



I would not.

Reason:

We don't yet know:

```text
Is Commitment:
    Entity?
    Value Object?
    Evidence Artifact?
    Verification Artifact?
```

Until aggregate discovery is finished, this is guesswork.

---

## 2. Tracking Codes

The proposal assumes:

```text
tracking_code
```

must exist.

I disagree.

That is one possible verification strategy.

The Bundestag study never says:

```text
Tracking Codes are required.
```

It says:

```text
Verifiability is required.
```

Those are different.

Tracking codes are a solution, not a requirement.

---

## 3. Vote Updating

The proposal implicitly adopts the Estonian model.

I strongly disagree with adopting this now.

Because:

```text
Vote Replacement
```

is governance policy.

Not evidence architecture.

---

# What I Think This Idea Really Gives Us

This is the important part.

The proposal accidentally discovered a future bounded context.

Not:

```text
Audit
```

Not:

```text
Evidence
```

But:

```text
Verification
```

---

Future architecture:

```text
Observation
      ↓
Evidence
      ↓
Evaluation
      ↓
Legitimacy
      ↓
Governance

Supporting Contexts
────────────────────

Replay

Verification

Certification

Reporting
```

This is the biggest architectural value in the proposal.

---

# What I Would Add To EvidenceAggregateDiscovery.md

A new section:

```markdown
## Future Verification Requirements

VR-1
Evidence must be independently verifiable.

VR-2
Verification must not require access to voter identity.

VR-3
Verification must not alter evidence.

VR-4
Verification authority must remain separate from governance authority.

VR-5
Verification must support replayability.
```

Notice:

No tables.

No hashes.

No migrations.

Only domain requirements.

---

# My Final Architect Verdict

### As Research

⭐⭐⭐⭐⭐ Excellent

The proposal correctly identifies:

* traceability
* privacy
* verifiability
* replayability

and connects them. 

---

### As DDD

⭐⭐⭐☆☆

It jumps too quickly into:

```text
tables
hashes
tracking codes
implementation
```

before aggregate discovery.

---

### As Future Architecture

⭐⭐⭐⭐⭐

It strongly suggests a future:

```text
Verification Context
```

that sits beside:

```text
Replay
Evidence
Certification
```

and consumes constitutional evidence.

---

So if I were your chief architect, I would write:

```text
Accept:
    Verification concepts
    Privacy-preserving traceability
    Verifiable timeline ideas

Reject:
    Immediate table design
    Immediate tracking code implementation
    Immediate cryptographic architecture

Action:
    Add Verification Requirements
    to EvidenceAggregateDiscovery.md

Continue:
    DD.4 Aggregate Discovery
```

That is the DDD-centered interpretation of this idea rather than the implementation-centered interpretation.
