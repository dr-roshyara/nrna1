# Evidence Aggregate Discovery

**Phase:** P10 — Aggregate Discovery
**Date:** 2026-05-29
**Prerequisite:** DD.3b complete, P9 approved
**Status:** Initial — discovering consistency boundaries, not designing classes

## Purpose

Identify the consistency boundaries inside the Evidence bounded context (containing the Evaluation subdomain). Evidence answers: *What observable facts exist to support or challenge a voter's constitutional participation eligibility?*

---

## Current Type Inventory

### Evidence Domain Types

| Type | Role |
|------|------|
| `ConstitutionalEvidenceSnapshot` | Frozen evidence at evaluation time — root for replay verification |
| `ParticipationEligibilityEvidence` | Voter eligibility evidence |
| `EvidenceClassification` | Provenance classification (Initial, Attested, ContinuityProven, RegistrarConfirmed) |
| `NetworkTrustEvidence` | Network evidence (IP hashes, whitelist status, velocity) |
| `TrustEvidencePrivacyPolicy` | Hashing/minimization policy |

### Evaluation Subdomain Types

| Type | Role |
|------|------|
| `EvidenceEvaluationState` | Evidence quality state (SUFFICIENT, INSUFFICIENT, REVIEW_REQUIRED, INCONCLUSIVE) |
| `EvidenceEvaluationResult` | Evaluation result containing state + classification + reason + audit context |
| `EvaluationEnvelope` | Sealed transport bundling result + observations + snapshot |
| `EvaluationReasonCode` | Typed reason for evaluation outcome |
| `ConstitutionalFinding` | Policy evaluation output |
| `VotingTrustResult` | Consolidated trust evaluation result |

### Consumed from Observation BC

| Type | Source |
|------|--------|
| `OverlaySignal` | Observation BC — signal types (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, etc.) |
| `ConstitutionalObservationContext` | Observation BC — flat container for OverlaySignal[] |

---

## Invariant Review (from BusinessInvariantCatalog P7a)

| ID | Invariant | Aggregate Implication |
|----|-----------|----------------------|
| EVI-1 | Evidence frozen at evaluation time | The snapshot, once created, must never change |
| EVI-2 | Evidence hash integrity — same data → same hash | Hash must cover all snapshot fields |
| EVI-3 | No PII in evidence context | Applied before snapshot creation — structural |
| EVI-4 | Classification is categorical, not scalar | Classification must remain a typed enum, not a scalar |
| EVL-1 | Evaluation does not derive legitimacy | Evaluation produces evidence-quality result, not `LegitimacyOutcome` |
| EVL-2 | Typed reason codes | Reason is a value object, not free text |
| EVL-3 | Envelope sealed after creation | Envelope is immutable once created |

---

## Consistency Boundary Analysis

### Question 1: Must Snapshot Fields Change Together?

```
If network evidence changes, must device evidence also change?

Answer: NO — they are independent dimensions.
But ALL fields are frozen at evaluation time — the snapshot is
created atomically. After creation, NOTHING changes.
```

**Implication:** The snapshot is a single consistency boundary — created atomically, immutable after.

---

### Question 2: Must Evaluation Result and Classification Change Together?

```
If evaluation state is INSUFFICIENT_EVIDENCE,
must the classification also be consistent?

Answer: YES. Evaluation state and classification are part of
the same evaluation act. They describe the same evidence from
different perspectives and must be coherent.
```

**Implication:** `EvidenceEvaluationResult` (containing state, classification, reason) is a single consistency boundary.

---

### Question 3: Is the Envelope a Separate Consistency Boundary from the Snapshot?

```
Can the envelope be created without a new snapshot?

Answer: YES. The envelope references an existing snapshot.
The snapshot was created earlier (at freeze time).
The envelope is created later (at evaluation completion).

If one fails, the other still exists validly.
```

**Implication:** Snapshot and envelope are **separate** aggregates. The envelope references the snapshot by hash but does not contain it.

---

### Question 4: Can an Envelope Exist Without a Result?

```
Can the evaluation transport exist without evaluation content?

Answer: NO. The envelope seals the result + observations + snapshot.
An empty envelope is meaningless.
```

**Implication:** `EvaluationEnvelope` and `EvidenceEvaluationResult` are in the same consistency boundary. The envelope IS the seal around the result.

---

## Candidate Aggregates

### Aggregate 1: EvidenceSnapshot

**Root:** `ConstitutionalEvidenceSnapshot`

Created when evidence is frozen at evaluation entry. The primary input for replay.

```
EvidenceSnapshot (AGGREGATE ROOT)
│
├── identity: SnapshotId (value object — hash of contents)
├── networkEvidence: NetworkTrustEvidence (value object — hashed IP, whitelist, velocity)
├── deviceEvidence: DeviceEvidence (value object — hashed fingerprint, continuity)
├── verificationEvidence: VerificationEvidence (value object — attestation status)
├── participationEvidence: ParticipationEligibilityEvidence (value object — membership, role)
├── sessionContinuity: SessionContinuity (value object — prior session hash)
├── evaluatedAt: EvidenceTimestamp (value object — DateTimeImmutable)
├── classification: EvidenceClassification (value object — provenance category)
└── constitutionalHash: string (hash of ALL fields above — replay invariant)

IMMUTABLE after creation.
Created atomically by TrustSnapshotAssembler or equivalent.
Cannot reference an EvaluationEnvelope (not yet evaluated).
```

**Invariants protected:**
- EVI-1: Evidence frozen — no setters, all properties `readonly`
- EVI-2: Hash integrity — `constitutionalHash` covers all fields deterministically
- EVI-3: No PII — applied before snapshot assembly (hashing happens upstream)

---

### Aggregate 2: EvaluationEnvelope

**Root:** `EvaluationEnvelope`

Created when evidence evaluation completes. The published language to Legitimacy BC.

```
EvaluationEnvelope (AGGREGATE ROOT)
│
├── identity: EnvelopeId (value object — UUID)
├── evaluationResult: EvidenceEvaluationResult (value object)
│   ├── state: EvidenceEvaluationState
│   ├── classification: EvidenceClassification
│   ├── reason: EvaluationReasonCode
│   └── policyOutcomeSequence: ConstitutionalFinding[]
├── observationContext: ConstitutionalObservationContext (value object — flat signal set)
├── snapshotHash: string (reference to EvidenceSnapshot.constitutionalHash)
├── createdAt: EnvelopeTimestamp (value object — DateTimeImmutable)
└── envelopeHash: string (hash of ALL fields — tamper evidence)

IMMUTABLE after creation.
Created atomically by PolicySequence or equivalent.
References EvidenceSnapshot by hash — does not contain it.
```

**Invariants protected:**
- EVL-1: Must not derive legitimacy — envelope contains `EvidenceEvaluationResult`, not `LegitimacyOutcome`
- EVL-2: Typed reason codes — `EvaluationReasonCode` is a typed enum
- EVL-3: Envelope sealed — all properties `readonly`, no setters

---

## Why Two Aggregates, Not One

| Factor | One Aggregate | Two Aggregates |
|--------|--------------|----------------|
| **When created** | Both at once | Snapshot first, envelope later |
| **Consistency scope** | All-or-nothing | Each has its own transaction boundary |
| **Failure mode** | Failed evaluation = lost snapshot | Snapshot survives failed evaluation |
| **Replay granularity** | Must replay evaluation to verify snapshot | Can verify snapshot independently of evaluation |
| **Legitimacy dependency** | Couples evidence freeze to evaluation | Evaluation can be retried without re-freezing |

**Two aggregates is the correct model.** The snapshot and envelope have different creation times, different lifetimes, and different failure modes.

---

## What Is NOT an Aggregate

### ObservationCollection

```
ConstitutionalObservationContext is a value object within EvaluationEnvelope.
It holds a flat array of OverlaySignal[].
```

**Why not an aggregate:** It has no lifecycle, no identity, no consistency boundary separate from the envelope. Signals flow through Evidence but are owned by Observation BC.

### PolicySequence

```
PolicySequence is application-layer orchestration logic, not a domain aggregate.
Policies (NetworkBindingPolicy, DeviceBindingPolicy, etc.) are stateless evaluation rules.
```

**Why not an aggregate:** Policies have no state. They evaluate evidence but don't maintain state across evaluations.

---

## Aggregate Lifecycles

```
Evaluation entry:
  1. Freeze evidence → EvidenceSnapshot created
  2. EvidenceSnapshot is immutable from this point
  
Evaluation execution:
  3. Policies evaluate against frozen snapshot
  4. Policies produce ConstitutionalFinding[]
  
Evaluation completion:
  5. EvidenceEvaluationResult created with state, classification, reason
  6. EvaluationEnvelope seals result + observations + snapshot hash
  7. Envelope is immutable from this point
  
Consumption:
  8. EvaluationEnvelope crosses to Legitimacy BC as published language
  9. EvidenceSnapshot crosses to Replay BC for certification
```

---

## Current Code vs Aggregate Model

| Current Code | Aggregate Alignment | Gap |
|-------------|-------------------|-----|
| `ConstitutionalEvidenceSnapshot` | Aligns with EvidenceSnapshot aggregate | Already `readonly` — minimal changes needed |
| `EvidenceEvaluationResult` | Value object within EvaluationEnvelope aggregate | Currently separate from envelope — may need tighter bundling |
| `EvaluationEnvelope` | Aligns with EvaluationEnvelope aggregate | Already `readonly` — minimal changes needed |
| `VotingTrustResult` | Cross-cutting result object | Currently mixes evaluation and trust — may split to align with aggregate boundaries |

---

## References

| Artifact | Section |
|----------|---------|
| BusinessInvariantCatalog.md (P7a) | EVI-1 through EVL-3 |
| EvidenceContext.md (P8 update) | Boundaries, invariants, published language |
| EvaluationAutonomyAssessment.md | Evaluation ⊂ Evidence — both aggregates are within Evidence BC |
| PublishedLanguageMatrix.md (P6) | EvaluationEnvelope crosses to Legitimacy; EvidenceSnapshot crosses to Replay |
| ContextDependencyRules.md | ADR-1 (Evidence → Observation), ADR-2 (Legitimacy → Evidence), ADR-5 (Replay → Evidence) |
