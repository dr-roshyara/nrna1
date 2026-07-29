# PKS Brainstorming Documents — Assessment (pre-M3 input review)

| | |
|---|---|
| **Kind** | Assessment of supporting material — the PA's brainstorming corpus in `docs/knowledge_tranfer/` evaluated against the accepted Phase-I baseline and the M0–M2 model state. **Advisory; adopts nothing; changes no plan.** |
| **Authority** | Generated — never authoritative without human review. |
| **Status** | **PRODUCED — PA commission 2026-07-28 ("read the brainstorming documents and build your opinion… think if we need to improve anything in architecture or in our plan"), pre-M3.** |
| **Inputs** | `docs/knowledge_tranfer/what_is_pks_v0.md` (31 KB, two-part: catalog view + self-correction) · `20260728_1710_what_is_pks_v1.md` (18 KB, consolidated definition) · `PKS_Bootstrapping_and_Location.md` (34 KB, incl. an embedded external review) · `Constitutional Governance Platform.md` (empty, 0 B — nothing to assess). |
| **Placement** | `docs/implementation/`, beside the Phase-II artifacts it cross-checks. |

---

## Verdict up front

**I agree with roughly 90% of this material, and the 90% is the part that matters.** The core definition, the bootstrap separation, and the embedded review's epistemic corrections are all sound — and, importantly, most of what is right in these documents is right because it *re-derives what the accepted record already established*, which is itself evidence the model is stable. The 10% I push back on consists of specific claims that contradict measured evidence or quietly assume answers to questions the ARB still owns.

**Neither the architecture nor the M0–M8 plan needs changing.** Everything genuinely new in these documents lands in Phase II.B (Architecture Definition), which the plan already sequences after M8 — the documents themselves say so repeatedly. What is needed is smaller: three factual flags on the documents, one routing note, and one gated-input capture.

---

## 1. Where I agree — and why it counts

**1.1 The corrected definition is the accepted baseline, restated well.** *"The PKS is a knowledge system that models engineering knowledge, governance, evidence, and their relationships; documentation guidance is a derived capability, not the definition"* — this is item 1's central finding ("the document is a serialization; the knowledge concepts are the domain") in product language. v0's own arc — first defining PKS as a documentation instruction manual, then correcting itself — recapitulates the exact discovery the evidence forced on us. Notably, v0's *first half alone* would have rebuilt the AKB/EKP failure mode (templates and rules whose instances don't follow); the self-correction caught it. I agree with the correction emphatically.

**1.2 The three-layer structure (Knowledge / Representation / Presentation) is a good articulation** — consistent with artifacts-as-projections, and it gives Phase II.B a vocabulary before Phase II.B exists. Properly labeled in v1 as "one plausible architectural interpretation… subject to refinement." Keep it exactly that provisional.

**1.3 The bootstrap separation is correct and is existing constitution, applied.** Methodology (cross-product, → `engineering/` after qualification) ≠ the PKS (product-specific, → `docs/`) resolves the circularity ("AI needs a PKS to build the PKS") and is precisely EM-001 + ES-005 + DR-4's methodology handling. The strongest kind of agreement: the brainstorming reached the same place the constitution already stood.

**1.4 The embedded review's corrections are right, and M2 already behaves accordingly.** Promotion evidence-driven, not phase-driven (matches ES-006/R-36/DR-4) · M0–M8 outputs are *Strategic-Modeling artifacts, some of which become PKS content, some of which remain records* (matches the four-role classification, capture #11) · Living-PKS *mechanisms* belong to Architecture Definition while the strategic model only recognizes that concepts evolve (matches the scope guard — and M2's two-tier evolution canon is exactly that recognition without mechanism) · portability revisited *only where a resolution materially changes a concept boundary* (M2 did precisely this: it re-touched portability only for Contract-narrowing and Policy, nowhere else).

**1.5 The "what the PKS tells AI" scenario is a legitimate purpose grounding.** It concretizes discovered purposes P-4 (AI-collaborator onboarding), P-5 (session continuity), and P-7 (task-scoped context assembly) into a usable narrative. As motivation, it is accurate.

## 2. Where I disagree — specific, checkable

**2.1 The single-lifecycle assumption recurs in both v0 and v1 — against the record.** v0's YAML sketches (`LC-ADR: PROPOSED → UNDER_REVIEW → APPROVED…`) and v1's Layer-1 line (`Lifecycle: Draft → Proposed → Approved → Deprecated → Archived`) present one clean lifecycle. The evidence found **eight** lifecycle vocabularies plus ~60 ad-hoc tokens; the candidate model's single-lifecycle proposal was **not adopted** (M1); and the kind→vocabulary mapping is **OQ-9 — surfaced, ARB-owned**. If these documents circulate as knowledge transfer, this assumption propagates as if settled. *Flag: mark those lines "illustrative — actual lifecycle vocabulary is OQ-9, pending" on the documents' next revision.*

**2.2 v0's relationship cardinalities contradict measured evidence.** `Decision → ADR, one-to-one` is falsified by the decisive Phase-I measurement: 23 decisions in one register file, 14 in one log — the concept/file ratio *was the argument* for concepts-over-documents. One-to-one would quietly re-document-ify the model. Same class of issue: `required: true` fields in the illustrative catalog are governance decisions no one has made.

**2.3 The location tables overlap a pending DA decision.** `docs/implementation/pks/` is proposed (and in one table marked ✅ as if current — it is not; no such folder exists, and M0–M2 artifacts live in `docs/implementation/` directly). Two constraints apply: EM-001 — *create folders only when the first artifact arrives, no speculative namespaces* — and, platform-side, the **Placement Rule decision paper (D-1..D-5) is still awaiting the DA**, whose entire subject is deriving knowledge-artifact locations from kind/type/lifecycle/authority. *Route: the location tables are input to that pending decision, not a decision. No folder until the first Phase II.B artifact needs one and the placement rule allows it.*

**2.4 Minor: the AI-consumer emphasis under-represents human consumers.** The discovered purposes include P-6 (teaching contributors) and P-9 (dispute resolution/audit) — human-facing. The definition's "guidance for AI" is one primary capability, not the consumer model. Worth one sentence in a future revision.

**2.5 Staleness, harmless:** v1's status table shows M1 "in progress"; M1 and M2 are execution-complete (M2 awaiting checkpoint disposition). The documents were written in parallel with the work.

## 3. Do we need to improve the architecture or the plan?

**The plan (M0–M8): no change.** Checked deliverable-by-deliverable: everything these documents add — representation formats, `concepts.yaml`, document catalog, AI guidance services, evolution mechanisms — is Phase II.B material, and the documents say so themselves ("exact structure to be determined during Architecture Definition"). The plan's scope guard already excludes it from II.A. Amending the WBS to reach into II.B would be exactly the premature-detailing the whole day's governance has prevented.

**The architecture: no change now — one genuine addition for later.** The **Document Catalog / AI Guidance Services** described here is the first concrete articulation of what Phase II.B's product might be: a *derived projection layer over the validated knowledge model* (what to produce · when · where · how structured · how verified). That is a real, valuable target — and it is captured as **gated input to Phase II.B planning** (the same treatment the KnowledgeOS charter seeds received), not as a plan item. Its correct grounding will be the M1 register + M4 identity/lifecycle model + M7 relationships — i.e., it becomes buildable exactly when the plan says II.B starts.

**One forward-looking risk worth naming (not acting on):** the brainstorming's catalog-with-templates vision is operationally the EKP's shape — and the EKP's measured failure was rules-written/instances-don't-follow with **no conformance observation**. The M3 Conformance-kind decision (next milestone) is therefore not an abstract exercise: whichever kind (A/B/C) is chosen becomes the mechanism that keeps the future Document Catalog from repeating the EKP's fate. The brainstorming unknowingly strengthens the case that M3 matters.

## 4. Dispositions

| Item | Disposition |
|---|---|
| Core definition (v1 §1.1/§9) | **Agree** — consistent with accepted baseline; fit for knowledge transfer |
| Three layers | **Agree as provisional interpretation** — Phase II.B validates |
| Bootstrap separation + promotion sequence (evidence-driven) | **Agree** — existing constitution, applied |
| Embedded review's five corrections | **Agree with all five** — M2 already conforms to the portability one |
| Single-lifecycle lines (v0, v1) | **Flag** — contradicts OQ-9 state; mark illustrative on next revision |
| v0 cardinalities + `required:` fields | **Flag** — contradicts measured evidence / undecided governance |
| `docs/implementation/pks/` location tables | **Route** as input to pending Placement Rule D-1..D-5; no speculative folder |
| Document Catalog / AI Guidance Services | **Captured as gated Phase II.B input** |
| "Current Facts / Expected Evolution / Future Architecture" separation | **Agree** — good documentation pattern; candidate for future artifact headers (observed once; not promoted) |
| Empty `Constitutional Governance Platform.md` | Nothing to assess; note for housekeeping |

---

*Traceability: PA commission 2026-07-28 (pre-M3 brainstorming review) · inputs: the three documents in `docs/knowledge_tranfer/` · cross-checked against: item 1 (§1.3 ratio, Q5 vocabularies, P-1..P-9), M1 register (single-lifecycle non-adoption, Contract narrowing), M2 report (evolution canon, portability touch-points), OQ-9/OQ-3 surfaced state, EM-001 namespace rule, Placement Rule decision paper (D-1..D-5, pending), DR-4 · resolves the earlier "phantom referent" flag: the reviewed document is `PKS_Bootstrapping_and_Location.md`'s embedded review, now in the record. **STOP — assessment only; M3 opens on explicit confirmation after the M2 checkpoint disposition.***
