# Round 10 ARB Decision Record

**Date:** 2026-06-04  
**Decision Type:** Working Model Selection  
**Status:** Decision Recorded

---

## Context

Strategic DDD exploration completed:

- **Round 8:** Discovery (Authority patterns across 5 representative constitutional decisions)
- **Round 8 Synthesis:** Consolidated findings; identified H-B and H-C as equally viable hypotheses
- **Round 9A:** Candidate Responsibilities (both models could produce plausible responsibility boundaries)
- **Round 9B:** Candidate Invariants (H-C remained coherent across more Appeals interpretations than H-B)
- **Round 10:** Boundary Exploration (H-C produced plausible candidate boundaries; identified three unresolved tensions)

The ARB has reviewed all findings and evidence from these four rounds.

---

## Decision

**Selected Working Model:** H-C (Cross-Cutting Authority)

**Status:** Working Model (not final architecture, not implementation authorization)

**Reversibility:** This decision is reversible. Will reconsider if revision triggers are met.

---

## Rationale

The H-C model:

- **Explained more observed patterns** than H-B (uniform lifecycle across contexts; cross-boundary authority behavior; Appeals as natural consequence)
- **Remained plausible under explored uncertainty** (survived invariant testing under multiple Appeals interpretations)
- **Produced coherent candidate responsibilities** (without forcing Authority into context-local variants)
- **Produced coherent candidate boundaries** (with three documented tensions requiring further investigation)

H-B remains viable and captures important insights about context-specific decision ownership. However, under the explored evidence, H-C explains the system with fewer exceptional cases.

---

## Accepted Risks

The ARB acknowledges three major risks with this selection:

### Risk 1: Governance Subsumption

**Description:** Authority may ultimately prove to be Governance applied, not an independent concern.

**Evidence:** All authority traces to Governance rules. Authority lifecycle may be derivative of Governance, not a distinct pattern.

**Impact if Realized:** Authority Context becomes unnecessary; Governance expands to subsume authority responsibilities.

**Monitoring:** Round 11 Context Relationships exploration should reveal whether Authority operates independently or as a Governance extension.

---

### Risk 2: Verification Overlap

**Description:** Authority and Verification may substantially overlap, making Authority a redundant model.

**Evidence:** Both involve legitimacy, challenge, and proof. Boundary between them remains unclear.

**Impact if Realized:** Verification Context may be sufficient to explain observed patterns; Authority Context becomes unnecessary.

**Monitoring:** Round 11 may provide evidence regarding whether Authority and Verification can remain separate concerns.

---

### Risk 3: Wrong Abstraction

**Description:** Authority may not be the correct strategic abstraction at all.

**Evidence:** The observed lifecycle pattern (Claim → Origin → Exercise → Challenge → Revocation) may be documenting something else: Decision Lineage, Decision Legitimacy, or Decision Traceability rather than Authority itself.

**Impact if Realized:** Strategic model requires complete reconception. H-C may be correct structurally but operating on a misnamed concept.

**Monitoring:** Round 11 Context Relationships exploration should explicitly test whether Authority is the right abstraction or whether a stronger concept emerges.

---

## Revision Triggers

This decision will be reconsidered if any of the following occur:

### Trigger 1: Context Relationships Contradict H-C

If Round 11 boundary analysis reveals that contexts do not actually relate to Authority as H-C assumes (e.g., if authority is not truly cross-cutting), H-C model status becomes uncertain.

**Response:** Return to ARB for re-evaluation; consider revisiting H-B.

---

### Trigger 2: Verification Materially Conflicts with H-C

If Round 11 or later work reveals that Verification and Authority cannot be kept separate without significant operational complexity, H-C becomes problematic.

**Response:** Return to ARB for decision on whether to merge contexts or restructure model.

---

### Trigger 3: Boundary Ownership Becomes Incoherent

If further boundary exploration reveals ownership contradictions that H-C cannot resolve, the model loses foundational coherence.

**Response:** Return to ARB; may need to reconsider H-B or explore alternative models.

---

### Trigger 4: A Stronger Abstraction Emerges

If exploration reveals that Decision Lineage, Decision Legitimacy, or another concept better explains the observed patterns than Authority, the strategic model must shift.

**Response:** Return to ARB for model reframing; may require returning to discovery phase for that new concept.

---

## Round 11 Authorization

**Authorized:** Round 11 — Context Relationship Exploration

**Purpose:** Map how contexts relate to each other and how H-C working model functions in context relationships.

**Special Mandate:** 

Explicitly evaluate whether Authority is the correct strategic abstraction or whether a stronger concept emerges during relationship exploration. Proceed with H-C as working model, but remain alert for evidence that suggests:

- Authority is derivative (Governance subsumption)
- Authority overlaps with Verification
- A different concept (Decision Lineage, etc.) is the real pattern

If any of these emerge during Round 11, flag for ARB review immediately. Do not proceed to aggregate design assuming Authority is settled.

---

## Status

**Decision:** Recorded  
**Model:** H-C (Cross-Cutting Authority) — Working Model  
**Next Phase:** Round 11 Context Relationship Exploration  
**ARB Action:** Awaiting completion of Round 11

---

## Summary

The ARB has selected H-C as the working model for strategic design exploration, recognizing that it explains more observed patterns than H-B and survives explored uncertainty more robustly. However, the ARB acknowledges that Authority itself may not be the final abstraction and has mandated that Round 11 explicitly test whether Authority is the correct concept or whether a stronger framework emerges.

This decision is reversible. Revision triggers are in place. Work proceeds with both confidence in the direction and vigilance about the foundation.
