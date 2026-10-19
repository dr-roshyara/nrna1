# B-06 — The basic idea: summary of the basis

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Built from | B-00…B-05: **35 files read and extracted** (engineering ADRs, baseline, C4, reference, developer guide; `docs/pks/`) |
| Not yet built from | the KnowledgeOS corpus itself. Corpus reading awaits authorization (`00-access/ACCESS-RECORD.md`) |
| Labels | **[EST]** established by the read sources · **[I]** interpretation · **[OPEN]** recorded as open, in the record or here |

---

## 1. The basic idea in one paragraph

**[EST]** KnowledgeOS belongs to a programme whose central concern is **governed knowledge**: making sure that what an organization (first, its own engineering) *knows*, *claims*, *decides* and *records* stays honest, traceable and correctable. The founding experience was negative. An AI framework whose "governance" was faked (hard-coded scores, an undefined "truth score" with power to act, success messages for no-ops) was examined and rejected (7 July 2026). From that came the demand that **nothing may be asserted as knowledge, verdict or authority unless it is derived from evidence and, where authority is involved, bound by an explicit human decision.**

## 2. The core principles (established in the read sources)

| # | Principle | Where it is stated |
|---|---|---|
| 1 | **Evidence, never authority.** Knowledge and evidence *feed* authority and never *hold* it; a verdict is an output, never a decision | Phase-02 §1; PKS AP-1 |
| 2 | **Only an explicit human decision creates authority.** AI output enters as `generated`; "approved/certified" cannot even be represented by the system | Phase-02 §6; PD-01…12 |
| 3 | **Separate the kinds.** Observation · evidence · interpretation · recommendation · decision are never conflated; every statement carries its epistemic label | R-34; R-36; Theory Ref §6 |
| 4 | **Independent dimensions.** Authority ⊥ status ⊥ maturity ⊥ adoption; illegal combinations are rejected | Metamodel §6 |
| 5 | **Five knowledge types with a conflict order.** Normative > Evidence > Descriptive > Runtime; Learning is quarantined until promoted | Metamodel §3 |
| 6 | **Derived, never asserted.** Progress, verdicts and status are computed from checks; no free-standing scores | Phase-01 §10; PD-07 |
| 7 | **Assertion integrity.** No artifact may claim an event that has not happened | AIP-10 |
| 8 | **Append-only history.** Nothing is rewritten; everything is superseded or annotated; the denotation of an identifier never changes | AIP-11; identifier discovery I-3 |
| 9 | **Source governs projection.** A register, index or summary is a projection; it is never evidence for a decision and is corrected toward its source | PKS DR-1/DR-2 |
| 10 | **"Unknown" and "no" are legitimate results.** UNDETERMINED, INCONCLUSIVE, "no X needed", "nothing general learned" are success outcomes, not failures | R-50/R-53; ES-006.4; distillation |
| 11 | **Knowledge grows only by evidence.** Observation → repeated observation → practice → candidate → human decision; "one corpus is one observation" | ES-006.1; PKS candidates |
| 12 | **Governance precedes automation.** Automation may implement governance, never define it | Reference Architecture §1 |

## 3. What "KnowledgeOS" is: three recorded meanings, one open question

| Meaning | Statement | Source |
|---|---|---|
| **KOS-M1** | the **engineering platform** (reusable methodology) | ADR_20260801_1740 (2026-08-01) |
| **KOS-M2** | a **prospective product for organizations**, built *on* the platform (never forking it); core domain: engineering governance/knowledge; vision frozen for discovery; problem hypothesis, charter, stage gates | EKS-RM (2026-07-27); DA clarification |
| **KOS-M3** | the **graduation layer** for proven cross-product engineering knowledge: *"KnowledgeOS never invents engineering practices. It records engineering practices that have already demonstrated value"* | distillation candidate (2026-08-05, not adopted) |

- **[OPEN] OQ-5 (raised 2026-08-01, unresolved, owner ARB):** *"Is KnowledgeOS the same thing as the Engineering Platform?"* **The identity of KnowledgeOS is therefore formally undecided in the record.** This is the source of the earlier "confusion". It is a recorded fact, not a reading gap.
- **[OPEN] KOS-M4:** the **theory in the corpus** (≈2,779 registered files) is the object this reconstruction targets. Second-hand reports say it concerns the kernel's **minimal sufficient state K**, **Zero/Gap** against an epistemic contract, invariants, operations, an epistemic pipeline (observation → evidence → knowledge) and philosophical lenses (Gita-derived and others). **Which of M1–M3 it serves, or whether it is a fourth thing, is unknown until the corpus is read.**

## 4. Related but distinct: PKS

**[EST]** **PKS, the Product Knowledge System**, is a separate product domain of *knowledge specifications* (YAML + Markdown).
- It has two accepted contexts: Knowledge Assessment and Knowledge Projection.
- It has a candidate Normative Governance seam and an unpartitioned core of expressed knowledge: Decision · Term · Model element · Contract.
- Its rules are the most precise knowledge-governance statements found so far (principles 1, 9 and the closed verdict vocabulary).
- **[OPEN] OQ-PKS-7:** do the three roots form one corpus or three?

## 5. What has actually been built and proven

**[EST] Built:**
- the engineering platform as a governance architecture: constitution written but PROPOSED; 7 hooks; 1 of 7 blocking gates; 0 of 17 fitness functions;
- one knowledge capability, CAP-001 Identifier Integrity, architecturally verified, *"its value has not"* been shown (zero real uses).

**[EST] Most promising piece of knowledge theory found in real work:**
- the invariant *"an identifier denotes exactly one subject"*;
- with **commands vs projections** and **annotation vs denotation** (identifier discovery, frozen).

**[EST] Theory work so far:**
- the S-Series design (pre-data);
- manuscript v1 (new mathematics, not the original theory);
- Session A's corpus map (unverified).

## 6. Working hypotheses (not findings)

- **H-B1:** the corpus theory generalizes the platform's governance discipline. It **holds by definition for KOS-M3** (n = 1, unadopted); for the corpus theory it is untested.
- **H-B1′:** the corpus theory generalizes specifically the *distinctive* epistemic mechanisms: authority as unconstructible state, epistemic labels, documents as records of decisions, append-only history.
- **H-B2:** KnowledgeOS-as-product (M2) aims to give organizations the evidence/authority model that the platform specified but never fully built.
- **What would refute them:** early corpus documents that develop the core distinctions independently, for example from the philosophical (Gita) or measure-theory strands, or from a knowledge-representation problem without a governance motive.

## 7. What must be settled next

1. **[OPEN, Authority] OQ-5:** which "KnowledgeOS" is the theory about? The reconstruction can proceed without the answer, but must not assume one.
2. **[OPEN, gate] Corpus authorization**, so that KOS-M4 can be read first-hand: vision, charter, the first theory documents, and S0011's date.
3. **[OPEN, method] Reconstruction rule:** engineering and PKS concepts enter the reconstructed theory **only where the corpus itself states or uses them**. Everything above is background and hypothesis for that work.

---

## Addendum 1 (2026-10-18, after reading the 12 founding files, B-08). Sections 1–7 above are kept unchanged as the earlier state

**The basic idea, restated with the founding documents:**

- **[EST] What KnowledgeOS was proposed as** (charter, 2026-07-12, PROPOSED): an **AI-native Knowledge Operating System for software engineering** for organizations.
  - It is meant to **preserve architectural knowledge, enforce engineering governance, and let AI agents reason consistently**.
  - The problem it addresses is **architectural-knowledge loss**: departing seniors, stale ADRs, AI ignoring standards, drifting docs, inconsistent reviews.
  - Later framing (Development Constitution, 2026-07-27): an **"Evidence-Governed Engineering Knowledge Operating System"**: *"AI is a capability inside the system. It is not the system"*; it *"governs engineering knowledge, not engineering truth."*
- **[EST] Its theory before the corpus** (RQ-002, 2026-07-11, research-frozen as "complete enough to be falsified"): the **General Knowledge Constitution**.
  - Knowledge Space = **Nature × Representation × Governance-Status (× Authority-Scope)**.
  - The atomic unit is the **Knowledge Claim** (content + coordinate + provenance + status + scent).
  - **11 invariants**, ending with the telos *"knowledge exists only to satisfy Knowledge Needs."*
  - A cyclic lifecycle; a flow algebra; specializations as weights.
  - The Engineering Platform is stated to be *"the first implemented bounded context"* of this model.
  - Project Knowledge adds **Knowledge Need → Context Assembly → Working Context**, with unmet needs recorded as gap evidence.
- **[EST] Order of origin, by git dates:** platform practice (07-07/08) → literature-based knowledge research and constitution (07-11) → product charter (07-12) → product constitution (07-27) → corpus (Aug–Sep).
- **[OPEN] Unchanged:** OQ-5; whether the charter was approved; how the corpus theory (kernel K, Zero/Gap, lenses) relates to the General Knowledge Constitution, whether it continues, replaces or diverges from it. **This is now the central question for the corpus reading.**

## Addendum 2 (2026-10-18, after `docs/ideas/`, B-09)

- **[EST, vision-only]** The idea files (2026-08-03/04) add **KOS-M5**: KnowledgeOS as *"the system that integrates engineering observations into governed engineering knowledge"*.
  - It is a **consumer, never a producer**, of observations; *"rules are data, the platform is the engine."*
  - Its own events are **EngineeringEvent → ObservationProduced → RecommendationIssued → DeveloperDecisionRecorded → OutcomeRecorded → AssessmentRecorded**.
- **[OPEN]** These are explicitly visions without authority. An "ARB ratified" note inside them has no located ruling record.

## Addendum 3 (2026-10-18, after `docs/knowledge_tranfer/`, B-10 KT-1…KT-8)

- **[EST] Identity of KnowledgeOS, as ruled on 2026-08-16 (ARB rulings recorded in the folder, KT-5):**
  - **KnowledgeOS = T1**, the reusable engineering platform;
  - **PKS = T3**, a per-product knowledge space;
  - EKP = PD-3; "EKS" is retired.
  - This answers OQ-5 *in substance*. **[OPEN]** I have not found the ruling entered in the OQ register or the ADR, so OQ-5 still shows as open there.
- **[EST] The governance core is restated at every layer** (strategic, rule model, tactical, events):
  - knowledge feeds authority and never holds it;
  - a report of an act is not the act (INV-A1);
  - absence is never permission (INV-A3);
  - a mechanism records authority and never grants it;
  - finding ≠ disposition.
- **[EST] Conflict has a precise definition:** joint unsatisfiability of normative effects over the intersection of applicability, scope and time. UNKNOWN is a first-class result, and *"absence of a detected conflict is not evidence of consistency."*
- **[EST] Several "empty" results are kept distinct and none counts as a pass:** UNKNOWN · NOT_APPLICABLE · TIMEOUT · "evidence-free and provisional". SC-2 adds: unspecified ≠ all.
- **[EST] Pinning:** a claim stays checkable later only if it records the versions of what it read (`reconstructable_as_of`).
- **[EST] Honest status:** all of this is PROPOSED and AI-generated. The two root questions, **OQ-1** (is the PKS software?) and **OQ-8** (what is a Rule?), were still open when the folder ends.
- **[I] Lead for the corpus reading.** The corpus's two "Zeros" may correspond to the distinction between proven-empty and unknown/unspecified that these documents enforce. This is to be tested first-hand once corpus reading is authorized.

## Addendum 4 (2026-10-18, after developer guide 01, B-11)

- **[EST] KnowledgeOS also exists as running software.** It is the **observation runtime** in `scripts/observations/` (v1.0, closed 2026-08-04):
  - pipeline: engineering event → observation → advisory recommendation → human decision → outcome → deterministic assessment;
  - evidence is append-only JSONL.
- **[EST] Measured use:** one completed cycle (1 outcome, 1 assessment). The streams are idle after 2026-08-04.
- **[I] Section 5 ("what has been built") is therefore incomplete.** The built assets are:
  1. the governance platform (ES/PD/AIP, hooks);
  2. CAP-001 Identifier Integrity;
  3. **the KnowledgeOS observation runtime**.

  Specified but not built: the PKS specifications and the 08-16 tactical model.

## Addendum 5 (2026-10-18, after developer guides 02–07 and s5_r7, B-12)

- **[EST] Built assets, complete list as of the developer guides:**
  1. **Governance platform** (ES/PD/AIP, hooks).
  2. **KnowledgeOS observation runtime** (B-11).
  3. **EngineeringKnowledge capability library:** CAP-001 Identifier, CAP-003 Vocabulary and CAP-004 Reference Integrity, as structural checks S1–S5 plus the author-side handoff report. All warn-only.
  4. **AI-session governance mechanisms AST-015…019:** the workflow interpreter, resolver, bootstrap, next-actor commission and fresh-session activation. Mostly not yet verified or adopted.
  5. **Research instruments for the theory reconstruction:** the gate runner, `admit.py`, and the S5 r7 verifier. Implemented; not activated or not independently audited.
- **[EST] Common logic in code:** three-valued verdicts (PASS / FAIL / UNDETERMINED, Strong Kleene, U never PASS); *mechanical assurance proves declared structure only*; *a precondition is not the act*; *completes ≠ passes*.
- **[OPEN] Unchanged:** none of this is the corpus theory itself. Whether the theory (K, Zero/Gap, lenses) is a generalization of this logic remains to be tested first-hand.
