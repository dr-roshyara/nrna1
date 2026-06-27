# Evaluation Autonomy Assessment

**Phase:** DD.3b — Strategic DDD Discovery (P9 gate prerequisite)
**Date:** 2026-05-29
**Status:** Final — recommendation for P9 boundary decision

## Purpose

Determine whether Evaluation is an independent bounded context or a subdomain/capability within Evidence. This is the single most important unresolved boundary question identified by the Senior Architect before P9 approval.

---

## Assessment Framework

A bounded context is autonomous if it can independently:

| Criterion | Question |
|-----------|----------|
| **Business decisions** | Does it own unique business decisions? |
| **Language** | Does it maintain its own ubiquitous language? |
| **Invariants** | Does it protect unique business invariants? |
| **Events** | Does it emit events that belong to it alone? |
| **Lifecycle** | Does it have a lifecycle independent of Evidence? |
| **Evolution** | Can its types evolve without requiring Evidence changes? |

---

## Criterion 1: Unique Business Decisions

### What Evaluation Decides

Evaluation produces `EvidenceEvaluationResult`, which describes evidence quality:

| Decision | Input | Output | Autonomous? |
|----------|-------|--------|-------------|
| Is the evidence sufficient? | `ConstitutionalEvidenceSnapshot` | `EvidenceEvaluationState` | **Borderline** — decision is about evidence |
| Which reason code applies? | Evidence state + policy evaluation | `EvaluationReasonCode` | **Borderline** — reason is evidence-specific |
| What is the evidence classification? | Evidence provenance | `EvidenceClassification` | **No** — classification is evidence property |
| Should the voter be allowed? | *None* | `LegitimacyOutcome` | **No** — EVL-1 explicitly forbids this |

### Key Insight

Every decision Evaluation makes is **about evidence**. There is no Evaluation-specific business outcome that exists independently of evidence processing.

| Evaluation Decision | Without Evidence, Does This Exist? |
|--------------------|-----------------------------------|
| "Evidence is sufficient" | ❌ — cannot evaluate what isn't observed |
| "Network evidence insufficient" | ❌ — reason code references evidence |
| "Review required" | ❌ — classification of evidence quality |

**Verdict: Evaluation does not own unique business decisions.** Its decisions are entirely about the evidence it processes.

---

## Criterion 2: Unique Language

### Evaluation's Language

| Term | Shared with Evidence? | Analysis |
|------|---------------------|----------|
| `EvidenceEvaluationState` | Prefix "Evidence" | Named after Evidence |
| `EvidenceEvaluationResult` | Prefix "Evidence" | Named after Evidence |
| `EvaluationReasonCode` | Unique | But describes evidence quality |
| `EvaluationEnvelope` | Unique | But transports evidence |
| `ConstitutionalFinding` | Unique | Policy output, not evidence-general |

### How Much Is Unique?

Of Evaluation's 5 core types:

- **2** share the `Evidence` prefix — naming suggests subdomain relationship
- **3** are Evaluation-specific — but their semantics reference evidence

### Vocabulary Coupling

```
Evaluation cannot describe its results
without referencing Evidence concepts:

  NETWORK_EVIDENCE_INSUFFICIENT
  DEVICE_EVIDENCE_INSUFFICIENT
  VERIFICATION_ATTESTATION_INSUFFICIENT
  ATTESTATION_PRESENT
```

Every reason code references the evidence dimension being evaluated. Evaluation's language is Evidence's language with an evaluative verb attached.

**Verdict: Evaluation's language is Evidence's language + evaluation verbs.** No independently meaningful vocabulary exists.

---

## Criterion 3: Unique Invariants

### Evaluation's Invariants (from P7a)

| ID | Invariant | Shared With Evidence? |
|----|-----------|---------------------|
| EVL-1 | Evaluation does not derive legitimacy | Shared — Evidence must not either |
| EVL-2 | Typed reason codes | Structural — applies within Evidence |
| EVL-3 | Envelope sealed after creation | Same pattern as EVI-1 (evidence frozen) |

### Invariant Analysis

**EVL-1 (must not derive legitimacy):** This is the same constraint that applies to Evidence (I-3 in EvidenceContext.md). Not unique to Evaluation.

**EVL-2 (typed reason codes):** This is a design quality constraint, not a business invariant unique to a BC. Reason codes are typed because they describe evidence categories — if evidence schema changes, reason codes change too.

**EVL-3 (envelope sealed):** This is `readonly` enforcement — the same structural pattern as `ConstitutionalEvidenceSnapshot`. The invariant "freeze at creation" is not unique to Evaluation.

### Comparison

| Domain | Invariant Count | Unique to Domain? |
|--------|----------------|-------------------|
| **Legitimacy** | 5 (LEG-1 through LEG-5) | 5/5 unique |
| **Evidence** | 7 (EVI-1 through EVI-4, plus 3 shared-invariant) | 7/7 unique |
| **Evaluation** | 3 (EVL-1 through EVL-3) | **0/3 unique** — all are Evidence concerns |

**Verdict: Evaluation has no invariants that are not also Evidence invariants.** Its invariants are Evidence invariants viewed through an evaluation lens.

---

## Criterion 4: Unique Events

### Evaluation's Events (from P5)

| Event | Category | Unique to Evaluation? |
|-------|----------|----------------------|
| `EvidenceEvaluationCompleted` | Observational | **No** — named after Evidence |

### Comparison

| Context | Events | All Unique? |
|---------|--------|-------------|
| **Observation** | `ObservationRecorded` | ✅ |
| **Evidence** | `EvidenceFrozen` (proposed) | ✅ |
| **Evaluation** | `EvidenceEvaluationCompleted` | ❌ — prefixed "Evidence" |
| **Legitimacy** | `LegitimacyEvaluated`, `LegitimacyGranted`, `ConstitutionalDenialIssued` | ✅ All |
| **Replay** | `ReplaySessionOpened`, `ReplayCertificationIssued`, `ReplayDivergenceDetected` | ✅ All |
| **Migration** | 3 events | ✅ All |

Evaluation's single event is named `EvidenceEvaluationCompleted` — the event name itself suggests Evidence is the subject, Evaluation is the action. If Evaluation were an independent BC, the event would be named something like `EvaluationConcluded` or `QualityAssessmentCompleted`.

**Verdict: Evaluation's events do not demonstrate autonomous identity.** The event naming convention (`Evidence*`) implies subdomain relationship.

---

## Criterion 5: Independent Lifecycle

### Does Evaluation Have a Lifecycle Separate from Evidence?

```
Evidence Lifecycle:
  Observe → Freeze → [Evaluate] → Preserve → Replay

Evaluation Lifecycle:
  Receive snapshot → Apply policies → Produce result → Seal envelope
```

The evaluation step occurs **within** the evidence lifecycle. Evidence is frozen, then evaluated. The evaluation cannot begin until evidence exists, and once evaluation completes, the result is attached to the evidence for preservation.

```
Evaluation cannot exist without Evidence.
Evidence can exist without Evaluation.
```

**Verdict: Evaluation has no lifecycle independent of Evidence.** Evaluation is a phase within evidence processing.

---

## Criterion 6: Independent Evolution

### Could Evaluation Types Change Without Evidence Changes?

| Evaluation Change | Would Evidence Need to Change? |
|------------------|-------------------------------|
| Add new `EvaluationReasonCode` | **No** — reason codes can be extended independently |
| Change `EvaluationEnvelope` format | **Yes** — contains `ConstitutionalEvidenceSnapshot` |
| Change `EvidenceEvaluationResult` | **Yes** — references `EvidenceClassification` |
| Change policy evaluation logic | **No** — policy logic is internal to evaluation |
| Add new evaluation state | **Possibly** — states must be mappable to legitimacy |

**Verdict: Partial.** Reason codes and policy logic can evolve independently. But the core data types (`EvidenceEvaluationResult`, `EvaluationEnvelope`) reference Evidence types and would require coordinated changes.

---

## Summary Assessment

| Criterion | Autonomous? | Evidence |
|-----------|-------------|----------|
| Unique business decisions | ❌ No | Every decision evaluates evidence |
| Unique language | ❌ Mostly no | Shared prefixes, evidence-referencing semantics |
| Unique invariants | ❌ No | All are shared with or derived from Evidence |
| Unique events | ❌ No | Named `EvidenceEvaluationCompleted` |
| Independent lifecycle | ❌ No | Evaluation is a phase within evidence processing |
| Independent evolution | ⚠️ Partial | Reason codes can evolve; data types cannot |

---

## Recommendation

### Conclusion: Evaluation is a Capability Within Evidence, Not an Independent BC

```
Evaluation
⊂
Evidence
```

**Rationale:** Evaluation fails 5 of 6 autonomy criteria. Its only partial autonomy (reason codes evolving independently) is characteristic of a well-structured subdomain, not an independent bounded context.

### Architectural Implications

| Aspect | Decision |
|--------|----------|
| **Namespace** | Co-located with Evidence in `Security\Simplified` — no separate namespace needed |
| **Published Language** | `EvaluationEnvelope` is Evidence's published language to Legitimacy, not a separate BC's |
| **Events** | `EvidenceEvaluationCompleted` is an Evidence-scoped event |
| **Commands** | `EvaluateEvidence` is an Evidence internal command |
| **Policies** | Policy chain remains as-is — it's the evaluation mechanism within Evidence |
| **Aggregates** | The Evaluation Envelope is an Evidence aggregate (speculative), not an independent aggregate |

### Revised Pipeline

```
Observation (BC)         Evidence (BC)              Legitimacy (BC)
─────────────────        ──────────────────         ──────────────────
OverlaySignal[]          Evidence                    LegitimacyOutcome
  │                        ├── Freeze                  Allowed
  │                        ├── Evaluate  ← Evaluation  Denied
  │                        ├── Classify   is here      Deferred
  │                        ├── Preserve                Investigate
  │                        └── Publish Envelope
  ▼                              │
[Observation]                   EvaluationEnvelope
                                 │
                                 ▼
                             [Legitimacy]
```

**Three bounded contexts, not four:**

```
Observation → Evidence → Legitimacy
```

Evaluation is a capability within Evidence, analogous to how "credit card charging" is a capability within a Payment context — important, structured, but not independently bounded.

### Risk of This Decision

| Risk | Mitigation |
|------|------------|
| Evaluation logic becomes "second-class" | Structure it as a well-defined subdomain with clear responsibility boundary within Evidence |
| Evaluation complexity explodes | Can extract to independent BC later if change coupling proves problematic |
| Reason code proliferation | Manage as Evidence's evaluation vocabulary, not a separate BC's language |

### Risk of the Alternative (Evaluation as Independent BC)

| Risk | Severity |
|------|----------|
| Four-context pipeline instead of three | Added complexity for no proven benefit |
| Evaluation ↔ Evidence boundary churn | Two contexts with near-identical change patterns |
| Published language duplication | Both would publish to Legitimacy — boundary ambiguity |
| Aggregate ambiguity | Is `EvaluationEnvelope` in Evidence or Evaluation? |

The risks of separation outweigh the risks of co-location.

---

## References

| Artifact | Section |
|----------|---------|
| AuthorityOwnershipMatrix.md (P1) | Evaluation authority — quality assessment only |
| ConstitutionalContextMap.md (P2) | Evaluation as subdomain of Evidence |
| ObservationContextAssessment.md (P3) | Methodology for BC autonomy assessment |
| DomainEventOwnershipMatrix.md (P5) | EvidenceEvaluationCompleted → Evaluation (internal) |
| DomainCommandOwnershipMatrix.md (P5a) | EvaluateEvidence → Evaluation (internal) |
| PublishedLanguageMatrix.md (P6) | EvaluationEnvelope as Evidence's published language |
| BusinessInvariantCatalog.md (P7a) | EVL-1, EVL-2, EVL-3 — all Evidence-relative |
| ContextStabilityAssessment.md (P7) | Evaluation stability = Evidence stability |
