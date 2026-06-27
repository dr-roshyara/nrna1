# Evidence Context Review

**Phase:** DD.3b — Strategic DDD Discovery (P8)
**Date:** 2026-05-29
**Prerequisite:** P1-P7b complete
**Status:** Review — findings for EvidenceContext.md update

## Purpose

Apply findings from P1-P7b to EvidenceContext.md. The original document was created during DD.3b Tactical Semantic Alignment, before context boundaries were finalized. Per Senior Architect directive (9.3/10 review), it contains premature conclusions that must be corrected.

---

## Finding 1: Observation is an Independent BC (Not a Subdomain of Evidence)

**Source:** P3 — ObservationContextAssessment.md

**Original text (Section 9):**
> "Currently merged within the same namespace. The Observation types (OverlaySignal, ConstitutionalObservationContext) could be extracted into a separate context if the architecture requires finer granularity. Currently they are co-located because the observation lifecycle is tightly coupled to evidence collection."

**Problem:** This frames Observation as a potential extraction candidate from Evidence, when the assessment determined Observation is an **independent bounded context** with:
- Different change drivers (overlay logic vs evidence schema)
- Authority handoff at `OverlaySignal[]` boundary
- Different lifetimes (observations mutating per-request vs evidence frozen at evaluation)

**Fix:** Revise to reflect independent BC status. The co-location in `Simplified` is a historical artifact, not an architectural coupling.

---

## Finding 2: Aggregate Definitions Are Premature

**Source:** Senior Architect Directive (9.3/10 review), P7a — BusinessInvariantCatalog.md

**Original text (Section 6):**
> Three aggregates defined with root entities, value objects, and invariants:
> - Evidence Aggregate (`ConstitutionalEvidenceSnapshot`)
> - Observation Aggregate (`ConstitutionalObservationContext`)
> - Evaluation Aggregate (`EvaluationEnvelope`)

**Problem:** DDD aggregate design occurs AFTER context boundaries are approved (P9). These definitions were created before:
- Authority ownership was finalized (P1) ✅ now complete
- Context map was approved (P2) ✅ now complete
- Observation–Evidence boundary was assessed (P3) ✅ now complete
- Published language was formalized (P6) ✅ now complete
- Invariants were catalogued (P7a) ✅ now complete

**Fix:** Replace with a deferred-aggregate placeholder. Review the candidates once P9 is approved. The aggregate definitions are speculative — they may be correct but the process requires boundary approval first.

---

## Finding 3: Governance is a Generic Domain, Not a Formal Downstream BC

**Source:** P4 — GovernanceLegitimacyBoundaryAssessment.md

**Original text (Figure in Section 4):**
> Shows `Governance Context` as a formal downstream BC consuming from Legitimacy Context.

**Problem:** Governance is a Generic Domain best handled by Laravel conventions (controllers, middleware, policies). It does not warrant DDD bounded-context formalization.

**Fix:** Replace "Governance Context" with "Governance (Generic Domain)" and add a note that enforcement is handled by Laravel conventions, not a formal BC.

---

## Finding 4: Namespace Migration is Premature

**Source:** P7 — ContextStabilityAssessment.md

**Original text (Section 1):**
> "Proposed namespace: `App\Domain\Election\Security\Evidence`"

**Problem:** Evidence is classified as **Emerging** stability. Per the Stability Assessment:
> "NO namespace migration" for Emerging contexts.

The namespace proposal violates the stability rule.

**Fix:** Remove the proposed namespace. Keep `Security\Simplified` as-is. Revisit after P10 (Namespace Decisions).

---

## Finding 5: Invariant Cross-Reference to BusinessInvariantCatalog

**Source:** P7a — BusinessInvariantCatalog.md

**Current invariants in Section 5 map to P7a IDs:**

| Current I-# | Description | P7a ID | Status |
|------------|-------------|--------|--------|
| I-1 | Evidence frozen at evaluation | EVI-1 | ✅ Aligned |
| I-2 | Observations are a flat set | OBS-2 | ✅ Aligned |
| I-3 | Evaluation state is not authority | EVL-1 | ✅ Aligned |
| I-4 | Reason codes are typed | EVL-2 | ✅ Aligned |
| I-5 | Evidence snapshots are replay-safe | REP-1 (+ EVI-2) | ✅ Aligned |
| I-6 | Classification is factual, not scalar | EVI-4 | ⚠️ Aligned but convention-only |
| I-7 | No raw PII in observations | EVI-3 | ✅ Aligned |

**Missing invariants from P7a not represented in EvidenceContext.md:**

| P7a ID | Description | Gap |
|--------|------------|-----|
| LEG-3 | Evidence frozen at decision (resolver entry) | Not covered — this is at the Legitimacy boundary, not Evidence boundary |
| OBS-1 | Observations are non-sovereign | Not explicitly stated |
| OBS-3 | Observations never flow directly to Legitimacy | Not covered — Observation→Legitimacy path block |
| EVL-3 | Envelope is sealed after creation | Covered implicitly by Aggregate section |

**Fix:** Add P7a IDs to each invariant and add the missing cross-references.

---

## Finding 6: Observation Creation Authority Belongs to Observation, Not Evidence

**Source:** P1 — AuthorityOwnershipMatrix.md, P3 — ObservationContextAssessment.md

**Original text (Section 3):**
> "Owns Authority: Observation creation — Overlays observe facts; signals are the output of observation"

**Problem:** Observation creation is the authority of the **Observation Context**, not Evidence. The document conflates the two: Evidence receives `OverlaySignal[]` but does not own their creation.

**Fix:** Remove "Observation creation" from Evidence's authority list. Evidence owns preservation, classification, and evaluation — not observation.

---

## Finding 7: Missing Published Language Reference

**Source:** P6 — PublishedLanguageMatrix.md

The published language from Evidence Context to its consumers is:

| Consumer | Published Language | Form |
|----------|-------------------|------|
| Evaluation | `ConstitutionalEvidenceSnapshot` | Frozen data + hash |
| Replay | `ConstitutionalEvidenceSnapshot` | Wrapped in `ReplayEvidenceEnvelope` |

**Fix:** Add explicit published language section to EvidenceContext.md, referencing PublishedLanguageMatrix.md.

---

## Finding 8: Event Ownership Mismatch — ObservationRecorded

**Source:** P5 — DomainEventOwnershipMatrix.md

**Original text (Section 7):**
> Lists `ObservationRecorded` as an Evidence Context event.

**Problem:** P5 assigns `ObservationRecorded` to the **Observation Context**, not Evidence. Observation owns signal creation; Evidence freezes, doesn't observe.

**Fix:** Move `ObservationRecorded` to Observation Context ownership. Evidence emits `EvidenceFrozen` (proposed) instead.

---

## Finding 9: Command Ownership — FreezeEvidence vs EvaluateEvidence

**Source:** P5a — DomainCommandOwnershipMatrix.md

Evidence Context owns `FreezeEvidence`. It does NOT own `EvaluateEvidence` (that belongs to Evaluation Context).

The document currently does not reference command ownership. Add a reference to P5a for clarity.

---

## Finding 10: Anti-Corruption Rule ACL-5 — Namespace Rename

**Original text (Section 10):**
> "The Evidence Context's canonical namespace must NOT be Security\Simplified. Current location is a historical artifact. Rename to Security\Evidence."

**Problem:** Per Finding 4, namespace migration is premature for an Emerging context. This ACL rule contradicts the stability assessment.

**Fix:** Change ACL-5 to acknowledge the current namespace is acceptable until P10. Remove the imperative "must NOT be" language.

---

## Summary of Required Changes

| # | Section | Change | Priority |
|---|---------|--------|----------|
| 1 | Section 1 (Mission) | Remove proposed namespace; add stability note | High |
| 2 | Section 3 (Authority) | Remove "Observation creation" from Owns Authority | High |
| 3 | Section 4 (Boundaries) | Replace "Governance Context" with "Governance (Generic)" | Medium |
| 4 | Section 4 (Boundaries) | Restructure upstream/downstream — Observation is independent | High |
| 5 | Section 5 (Invariants) | Add P7a cross-reference IDs | Medium |
| 6 | Section 5 (Invariants) | Add missing invariants (OBS-1, OBS-3) | Medium |
| 7 | Section 6 (Aggregates) | Replace with deferred placeholder | **Critical** |
| 8 | Section 7 (Events) | Move ObservationRecorded to Observation ownership | High |
| 9 | Section 9 (Relationships) | Update Observation relationship to independent BC | High |
| 10 | Section 9 (Relationships) | Update Governance to Generic Domain | Medium |
| 11 | Section 10 (ACL) | Remove ACL-5 namespace imperative; soften to pending P10 | High |
| 12 | New section | Add Published Language reference (from P6) | Medium |
| 13 | Appendix | Keep type inventory but remove proposed moves | Medium |
