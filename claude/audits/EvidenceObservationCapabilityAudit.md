# Evidence & Observation Capability Audit

**Phase:** DD.3b — Namespace Alignment (Step 4 per Senior Architect)
**Audit Date:** 2026-05-29
**Scope:** 9 active survivor types in `App\Domain\Election\Security\Simplified`
**Method:** Per-type capability classification, authority boundary analysis, bounded-context fit assessment
**Prerequisite:** NamespaceAlignmentAudit.md — established that Simplified is a partially-completed promotion with surviving evidence/observation types

---

## Core Finding

The 9 active survivors are **not** migration stragglers. They form a coherent **Evidence & Observation subdomain** that was architecturally separated from the root `Security` namespace's Legitimacy/Trust/Governance model. Four of the nine carry explicit doc comments stating they "replace" a root type in the simplified/constitutional runtime.

The correct architectural response is **not** promotion to root — it is **renaming the namespace** to reflect its actual purpose.

---

## Per-Type Analysis

### 1. OverlaySignal

| Question | Answer |
|----------|--------|
| Purpose | Creates typed observation signals (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT) |
| Owns authority | Observation signal creation — factual classification of what an overlay detected |
| Must never own | Legitimacy, authority decisions, signal ranking, signal weighting |
| Participates in replay? | Yes — signal type + evidence context frozen at observation time |
| Contains PII? | No — evidence context contains hashed/minimized data |
| Which BC? | **Observation** — signals describe what was observed, not what it means |
| Root equivalent? | No direct equivalent. `OverlaySignalCategory` (root) is a categorical enum of signal types; `OverlaySignal` (Simplified) is the actual signal data structure with evidence context |

**Assessment:** Observation type. Belongs in an Evidence/Observation subdomain, NOT in root Security (which governs legitimacy). The root already has `OverlaySignalCategory` as a categorical enum — `OverlaySignal` as a data structure belongs with its evidence peers.

### 2. ConstitutionalObservationContext

| Question | Answer |
|----------|--------|
| Purpose | Flat, unranked container for `OverlaySignal[]` observations |
| Owns authority | Observation aggregation — pure collection with no filtering or ranking |
| Must never own | Signal ranking, weighting, filtering, authority decisions |
| Participates in replay? | Yes — the complete observation set must be preserved for replay |
| Which BC? | **Observation** — container for observation signals |
| Root equivalent? | None |

**Assessment:** Observation type. Belongs with OverlaySignal.

### 3. ConstitutionalEvidenceSnapshot

| Question | Answer |
|----------|--------|
| Purpose | Frozen replay-safe evaluation input containing all evidence and constitution |
| Owns authority | Evidence preservation — immutable set of evidence at evaluation time |
| Must never own | Authority decisions, behavioral methods, recalculation |
| Participates in replay? | Yes — primary replay contract ("same input → same output") |
| Which BC? | **Evidence** — frozen evidence is the fundamental replay unit |
| Root equivalent? | None (note: root has `CapabilityParitySnapshot` which serves a different purpose — legacy parity, not constitutional evidence) |

**Assessment:** Evidence type. Core replay contract. Belongs with evidence types.

### 4. EvidenceEvaluationState

| Question | Answer |
|----------|--------|
| Purpose | Describes evidence quality state — SUFFICIENT, INSUFFICIENT, REVIEW_REQUIRED, INCONCLUSIVE |
| Owns authority | Evidence quality classification — factual description of what the evidence supports |
| Must never own | Legitimacy derivation — the Resolver (ConstitutionalLegitimacyDecision) maps evidence state → legitimacy outcome |
| Participates in replay? | Yes — evaluation state is the output of evidence evaluation, consumed by resolver |
| Which BC? | **Evaluation** — describes the outcome of evaluating evidence (parallel to TrustEvaluationState in root which describes trust evaluation) |
| Root equivalent? | `TrustEvaluationState` (root) — but `EvidenceEvaluationState` is a parallel concept for the evidence pipeline, not a duplicate. The doc says "Replaces `TrustEvaluationState` in the simplified/constitutional runtime" |

**Assessment:** Evaluation type. Parallel to but distinct from TrustEvaluationState. Belongs with evaluation pipeline types.

### 5. EvidenceClassification

| Question | Answer |
|----------|--------|
| Purpose | Classifies evidence provenance — Initial, Attested, ContinuityProven, RegistrarConfirmed |
| Owns authority | Evidence provenance classification — factual (what evidence exists, not how much to trust it) |
| Must never own | Trust levels, authority decisions, scalar trust scoring |
| Participates in replay? | Yes — evidence classification must be deterministic from same evidence set |
| Which BC? | **Evidence** — describes evidence provenance quality |
| Root equivalent? | Doc says "Replaces `TrustLevel` in the simplified/constitutional runtime" — but it's replacing a scalar trust concept with a factual provenance concept. Architecturally cleaner. |

**Assessment:** Evidence type. Cleaner replacement for TrustLevel's scalar approach — replaces subjective trust levels with factual provenance classifications.

### 6. ParticipationEligibilityEvidence

| Question | Answer |
|----------|--------|
| Purpose | Frozen observational evidence about voter eligibility at evaluation time |
| Owns authority | Eligibility evidence preservation — has active membership, valid assignment, approval, suspension status |
| Must never own | Eligibility decisions — the resolver uses this evidence to derive participation authority |
| Participates in replay? | Yes — eligibility hash enables divergence detection |
| Which BC? | **Evidence** — frozen evidence data |
| Root equivalent? | None |

**Assessment:** Evidence type. Pure evidence preservation — no authority.

### 7. EvaluationEnvelope

| Question | Answer |
|----------|--------|
| Purpose | Bundles evaluation result + observations + snapshot into single transport envelope |
| Owns authority | Evaluation transport — structural container only |
| Must never own | Authority decisions, behavioral methods |
| Participates in replay? | Yes — envelope is sealed for replay transport |
| Which BC? | **Evaluation** — evaluation output transport |
| Root equivalent? | Doc says "Replaces `TrustEvaluationEnvelope`" — but `TrustEvaluationEnvelope` is a root-level type that still exists. The Simplified version carries `EvidenceEvaluationResult` instead of the root's `VotingTrustResult`. Architecturally, both envelopes serve the same role but carry different result types. |

**Assessment:** Evaluation type. Transport layer for evaluation pipeline.

### 8. EvaluationReasonCode

| Question | Answer |
|----------|--------|
| Purpose | Enum of concrete reasons why evaluation reached its conclusion |
| Owns authority | Reason classification — descriptive only |
| Must never own | Evaluation logic, authority decisions |
| Participates in replay? | Yes — reason code must be deterministic from same evidence |
| Which BC? | **Evaluation** — evaluation outcome annotation |
| Root equivalent? | None — this is new in Simplified and has no root counterpart |

**Assessment:** Evaluation type. Pure classification enum.

### 9. EvidenceEvaluationResult

| Question | Answer |
|----------|--------|
| Purpose | Outcome of evidence evaluation — state + classification + reason + audit context |
| Owns authority | Evaluation result shape — bundles the complete evaluation output |
| Must never own | Legitimacy — this is INPUT to the Resolver, not the Resolver's output |
| Participates in replay? | Yes — evaluation result must be deterministically reproducible |
| Which BC? | **Evaluation** — evaluation output |
| Root equivalent? | Doc says "Replaces `VotingTrustResult` in the simplified/constitutional runtime" — architecturally cleaner separation: `EvidenceEvaluationResult` is what the evidence pipeline produces; the root `VotingTrustResult` is what the trust evaluation produces |

**Assessment:** Evaluation type. The output contract of the evidence evaluation pipeline.

---

## Cluster Analysis

The 9 types form **3 natural clusters**:

### Cluster 1: Observation
| Type | Role |
|------|------|
| `OverlaySignal` | Observation primitive — what an overlay detected |
| `ConstitutionalObservationContext` | Observation container — all signals in one evaluation |

**Character:** Passive, non-authoritative, pure telemetry.

### Cluster 2: Evidence
| Type | Role |
|------|------|
| `ConstitutionalEvidenceSnapshot` | Frozen evidence set — replay contract |
| `ParticipationEligibilityEvidence` | Eligibility evidence — frozen at evaluation time |
| `EvidenceClassification` | Evidence provenance classification — factual |

**Character:** Immutable, replay-safe, hash-verifiable.

### Cluster 3: Evaluation Pipeline
| Type | Role |
|------|------|
| `EvidenceEvaluationState` | Evaluation state enum — evidence quality |
| `EvidenceEvaluationResult` | Evaluation output — state + classification + reason |
| `EvaluationEnvelope` | Evaluation transport — bundles result + observations + snapshot |
| `EvaluationReasonCode` | Reason codes — concrete evaluation outcomes |

**Character:** Evaluation pipeline contract types. These are what the evidence pipeline produces and the resolver consumes.

---

## Assessment: Separate Subdomain Confirmed

The 9 survivors form a coherent **Evidence & Observation Evaluation subdomain**:

| What it is NOT | What it IS |
|---------------|------------|
| Not unfinished migration | A parallel evaluation model for evidence/observation |
| Not legacy compatibility | A deliberate replacement for root trust concepts |
| Not scattered types | Three coherent clusters (Observation, Evidence, Evaluation) |
| Not authoritative | Purely observational and evidentiary — deliberately separated from legitimacy |

**Promotion to root `Security\` would be architecturally wrong.**

The root `Security` namespace owns: Legitimacy, Trust, Governance, Sovereignty, Authorization
The Simplified survivors own: Evidence, Observation, Evaluation Pipeline

These are distinct concerns. Mixing them would reintroduce the scalar sovereignty and authority leakage that the constitutional model was designed to eliminate.

### Why the 6 Duplicates Were Promoted But These 9 Weren't

The 6 promoted types (`TrustEvaluationState`, `VotingTrustResult`, `TrustLevel`, etc.) are **governance/trust types** that belong in the root `Security` namespace. They were correctly promoted because they represent the **legitimacy layer**.

The 9 survivors are **evidence/observation types** that belong in a separate subdomain. They were correctly left in Simplified because they represent the **evidence layer** — the parallel pipeline that feeds into but is architecturally distinct from the legitimacy layer.

The boundary between the two layers is:

```
Evidence Layer (Simplified survivors)      →     Legitimacy Layer (Root Security)
────────────────────────────────────────────────────────────────────────────
EvidenceEvaluationResult                    →     ConstitutionalLegitimacyDecision::decide()
OverlaySignal                               →     Interpreted by resolver as context
ConstitutionalEvidenceSnapshot              →     Frozen input for deterministic evaluation
```

The resolver (`ConstitutionalLegitimacyDecision`) sits BETWEEN the two layers, converting evidence evaluation into legitimacy outcomes.

---

## Decision

| Option | Description | Recommendation |
|--------|-------------|---------------|
| Promote to root Security | Move all 9 to `App\Domain\Election\Security\` | ❌ Rejected — mixes evidence with legitimacy |
| Keep as Simplified | Leave in `App\Domain\Election\Security\Simplified\` | ❌ Rejected — namespace name is debt |
| **Rename to `Security\Evidence`** | Create `App\Domain\Election\Security\Evidence\` | ✅ Recommended |
| Rename to `Security\Observation` | Narrower scope — misses evaluation types | ⚠️ Less accurate |
| Split into 3 sub-namespaces | `Security\Evidence`, `Security\Observation`, `Security\Evaluation` | ⚠️ Over-engineered for 9 types |

### Recommended: `App\Domain\Election\Security\Evidence`

This name:
- Describes what the namespace contains (evidence lifecycle: observation → preservation → evaluation)
- Distinguishes from root Security (which governs legitimacy, trust, authorization)
- Eliminates the `Simplified` debt without promoting to root
- Groups all 3 clusters (Observation, Evidence, Evaluation) under the unifying concept of Evidence
- Does not collide with any existing namespace

### Alternative: `App\Domain\Election\Security\Observation\`

If the Senior Architect prefers narrower granularity, but this creates two namespaces for 9 types.

---

## Deletion Candidates (Not Affected by This Audit)

The 6 stale duplicates can be safely deleted regardless of the namespace decision:

| File | Reason |
|------|--------|
| `Simplified\BallotAuthorizationProtocol.php` | Root copy is canonical (27 lines with methods vs 9 bare) |
| `Simplified\ElectionSecurityEvent.php` | Root copy is canonical |
| `Simplified\TrustEvaluationEnvelope.php` | Replaced by Simplified's own `EvaluationEnvelope` |
| `Simplified\TrustEvaluationState.php` | Root copy is canonical (full constitutional doc) |
| `Simplified\TrustLevel.php` | Root copy is canonical; replaced by `EvidenceClassification` in Evidence BC |
| `Simplified\VotingTrustResult.php` | Root copy is canonical; replaced by `EvidenceEvaluationResult` in Evidence BC |

These 6 have 0 production references each and their root counterparts are active and canonical.

Additionally, ~15 Simplified-only types with 0 production references can be cleaned up after the namespace decision.
