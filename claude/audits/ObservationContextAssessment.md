# Observation Context Assessment

**Phase:** DD.3b — Strategic DDD Discovery (P3)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

Determine whether **Observation** is a subdomain of **Evidence Context** or an **independent bounded context**. This is the most critical boundary decision because:

- If Observation ⊆ Evidence: `OverlaySignal` and `ConstitutionalObservationContext` stay co-located with evidence types
- If Observation is independent: Formal ACL between Observation and Evidence, with published language and explicit upstream/downstream flow

---

## The Question

> Are "what was observed" and "what evidence exists" the same concept at different stages, or are they fundamentally different concerns?

---

## Analysis Framework

### Dimension 1: Definition Comparison

| Aspect | Observation | Evidence |
|--------|-------------|----------|
| **Core question** | What did overlays detect? | What facts exist at evaluation time? |
| **Nature** | Active — produced by overlays in real-time | Static — frozen at evaluation boundary |
| **Lifecycle** | Created per-evaluation, discarded after | Preserved for replay, hash-verifiable |
| **Authority** | Signal creation + flat collection | Preservation + classification |
| **Change driver** | New overlays, new signal types | New evidence sources, schema changes |
| **Replay role** | Input to evaluation (must be preserved for replay determinism) | The frozen input itself |
| **PII concern** | Evidence context is hashed/minimized | Same — but covers more dimensions |

**Finding:** Definitions overlap but are not identical. Observation is about **detection**; Evidence is about **preservation**. These are different temporal stages of the same pipeline.

---

### Dimension 2: Lifecycle Analysis

```
Observation Lifecycle:
  Overlay runs → detects fact → creates OverlaySignal
  → signals collected in ConstitutionalObservationContext
  → passed to evaluation → END (signals consumed)

Evidence Lifecycle:
  Raw evidence collected → hashed/minimized
  → frozen in ConstitutionalEvidenceSnapshot
  → preserved for replay → hash-verifiable across time
```

**Key difference:** Observations are **ephemeral** (created and consumed per evaluation cycle). Evidence is **persistent** (frozen for replay). A signal is created, used once, and never referenced again. An evidence snapshot is created and then preserved indefinitely.

**However:** In practice, the observation context IS part of the evidence snapshot in the current architecture. `ConstitutionalEvidenceSnapshot` includes verification, network, device, and session continuity data — the very data that overlays observed.

**Finding:** Lifecycles are different but the data flows through in a single evaluation cycle. Observations feed evidence; they don't have a separate lifecycle afterward.

---

### Dimension 3: Authority Boundary Analysis

From the AuthorityOwnershipMatrix:

| Authority | Observation | Evidence |
|-----------|-------------|----------|
| Signal creation | OWNS | MUST NOT |
| Flat observation collection | OWNS | N/A |
| Evidence preservation | MUST NOT | OWNS |
| Provenance classification | MUST NOT | OWNS |
| Hashing for integrity | MUST NOT | OWNS |
| Privacy policy | MUST NOT | OWNS |

**Key finding:** The authority boundaries are clean and non-overlapping. Observation's authority (signal creation, collection) ends where Evidence's authority (preservation, classification, hashing) begins. There is no authority overlap — the boundary is a clear handoff.

**Finding:** Authority boundaries support separation. Observation's authority is complete before Evidence's authority begins. They don't share authority — they hand off.

---

### Dimension 4: Dependency Analysis

**Can Observation exist without Evidence?**
- Yes. Overlays can produce signals independently of evidence snapshots. The observation pipeline can function standalone — it just collects and passes signals.

**Can Evidence exist without Observation?**
- In theory, yes — evidence can be collected directly from raw sources (network evidence from DB, device evidence from session). But in the constitutional architecture, evidence is collected THROUGH observation (overlays observe raw sources and produce signals that freeze into evidence).

**Current dependency direction:**
```
Signals → EvidenceSnapshot
(Observation produces what Evidence freezes)
```

**Finding:** Observation is **upstream** of Evidence with a clear dependency direction. Observation can exist independently; Evidence cannot easily exist without Observation in the current architecture.

---

### Dimension 5: Replay Role Analysis

| Aspect | Observation in Replay | Evidence in Replay |
|--------|----------------------|-------------------|
| **What is preserved** | Signal set (flat, unranked) | Evidence snapshot (frozen facts) |
| **How preserved** | Part of evaluation context | Sealed in ReplayEvidenceEnvelope |
| **Replay verification** | Same signals → same evaluation input | Same snapshot → same hash |
| **Certification scope** | Implicit (within evaluation) | Explicit (ReplayCertification) |

**Finding:** Observations and evidence play DIFFERENT roles in replay. Observations are the INPUT that must be deterministically reproducible. Evidence is the INPUT's FROZEN FORM that Replay certifies. They are the same data at different stages of formality.

---

### Dimension 6: Change Coupling Analysis

| Change type | Observation impact | Evidence impact |
|-------------|-------------------|-----------------|
| New overlay added | HIGH — new signal type | LOW — new evidence source |
| Signal type changed | HIGH — affects all overlays | MEDIUM — snapshot schema change |
| Evidence schema change | NONE | HIGH — replay compatibility |
| New evidence source | NONE | HIGH — new snapshot field |
| Privacy policy change | MEDIUM — hashing rules | HIGH — re-hashing snapshots |
| Replay contract change | NONE | HIGH — envelope format |

**Key finding:** Observation and Evidence change for **different reasons**. An overlay change affects Observation but may not affect Evidence. A snapshot schema change affects Evidence but not Observation. This is the strongest indicator of separate contexts.

**Finding:** Change coupling analysis supports SEPARATE contexts. Observation and Evidence have different change drivers and different stability requirements.

---

## Assessment Summary

| Dimension | Verdict |
|-----------|---------|
| Definition comparison | **Related but distinct** — detection vs preservation |
| Lifecycle analysis | **Different** — ephemeral vs persistent |
| Authority boundary | **Clear handoff** — no overlap, no conflict |
| Dependency direction | **Upstream/Downstream** — Observation supplies Evidence |
| Replay roles | **Different stages** — same data, different formality |
| Change coupling | **DIFFERENT DRIVERS** — strongest evidence for separation |

---

## Recommendation: Independent Bounded Contexts

**Observation should be an independent bounded context, NOT a subdomain of Evidence.**

### Rationale

1. **Different change rates and drivers** are the strongest DDD signal for separate contexts. Observation changes when overlays change; Evidence changes when schema or replay contracts change. These happen independently.

2. **Authority boundaries are non-overlapping** with a clean handoff. Observation owns signal creation and collection; Evidence owns preservation and classification. No shared authority.

3. **Different lifetimes** — observations are ephemeral (per-evaluation-cycle), evidence is persistent (preserved for replay across time).

4. **The handoff is already a natural boundary** — `ConstitutionalObservationContext` is produced once, and then evidence freezing begins. This is a natural transactional boundary.

5. **Change coupling is the decisive factor.** The matrix above shows that Observation and Evidence change for completely different reasons. Co-locating them would mean a snapshot schema change touches the same namespace as a signal type change — coupling unrelated concerns.

### What This Means

```
Observation BC (upstream)              Evidence BC (downstream)
──────────────────────────────         ──────────────────────────────
OverlaySignal                          ConstitutionalEvidenceSnapshot
ConstitutionalObservationContext       ParticipationEligibilityEvidence
                                       EvidenceClassification
                                                                     
Published Language: OverlaySignal[]    Published Language: EvidenceSnapshot
                                                                     
ACL: Signals must NOT carry            ACL: Snapshot must NOT refer
     authority semantics                    back to overlays
```

### What Stays Co-located

The **Evaluation** cluster (`EvidenceEvaluationState`, `EvidenceEvaluationResult`, `EvaluationEnvelope`, `EvaluationReasonCode`) should remain with **Evidence**, not Observation. Evaluation assesses the quality of preserved evidence — it is downstream of Evidence, not Observation.

### What This Unlocks

- Observation types (`OverlaySignal`, `ConstitutionalObservationContext`) can evolve independently of evidence schema
- Evidence schema can change without affecting overlay logic
- Replay contracts concern only Evidence, not Observation
- ACL between Observation and Evidence prevents signal semantics from leaking into evidence classification

### What This Blocks

- Namespace rename `Simplified → Evidence` alone would be WRONG — it conflates Observation with Evidence
- The `Simplified` namespace must be split: Observation types into `Security\Observation`, Evidence types into `Security\Evidence`
- Evaluation types would stay with `Security\Evidence`

---

## Caveat: 9-Type Practicality

For 9 types total, splitting into two namespaces (Observation: 2 types, Evidence+Evaluation: 7 types) adds complexity. The practical question:

> Is the architectural clarity of the split worth the namespace overhead of maintaining two namespaces for 9 classes?

**Answer:** Yes, because:
1. The change coupling evidence is strong — these types WILL evolve independently
2. The authority boundaries are clean — mixing them would blur the handoff
3. The ACL value (preventing signal semantics from leaking into evidence classification) is real
4. The namespace overhead is minimal — one extra directory, two use statement changes

However, if the Senior Architect prefers pragmatism over purity, co-locating Observation within Evidence (with clear doc boundaries) is an acceptable compromise given the 9-type scale.

---

## Final Verdict

| Position | Evidence | Recommendation |
|----------|----------|---------------|
| Observation ⊆ Evidence | Co-located today, simple 9-type namespace, single rename | Acceptable compromise |
| Observation independent BC | Different change drivers, authority handoff, different lifetimes | **Preferred for architectural clarity** |

**Recommended:** Treat Observation and Evidence as separate bounded contexts, even if they share the `Simplified` namespace temporarily. The namespace migration should split them into `Security\Observation` and `Security\Evidence` when stability allows.

**Minimum:** At minimum, document the boundary clearly with doc headers in co-located files and a note that the split is deferred until context stability assessment passes.

---

## Appendix: Current Code Mapping

| Type | Proposed Context |
|------|-----------------|
| `OverlaySignal` | **Observation** |
| `ConstitutionalObservationContext` | **Observation** |
| `ConstitutionalEvidenceSnapshot` | **Evidence** |
| `ParticipationEligibilityEvidence` | **Evidence** |
| `EvidenceClassification` | **Evidence** |
| `EvidenceEvaluationState` | **Evidence** (Evaluation subdomain) |
| `EvidenceEvaluationResult` | **Evidence** (Evaluation subdomain) |
| `EvaluationEnvelope` | **Evidence** (Evaluation subdomain) |
| `EvaluationReasonCode` | **Evidence** (Evaluation subdomain) |
