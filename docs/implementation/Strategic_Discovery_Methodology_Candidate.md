# Strategic Discovery Methodology — Candidate Capture

| | |
|---|---|
| **Kind** | Methodology capture — **learning input** (ES-006.3 harvest discipline). Records reusable engineering knowledge revealed by the PKS Phase-1 discovery. **Adopts nothing; creates no governance** (ES-001.2). |
| **Authority** | Generated — never authoritative without human review. |
| **Status** | **RECORDED — candidate; standing CONFIRMED (ARB, 2026-07-28: DR-4 "Confirm as recorded" — `PKS_Phase_I_ARB_Rulings.md`).** One operational instance (the PKS discovery, 2026-07-27). The multi-context evidence bar for methodology-module candidacy (R-39's normal bar) is **not yet met** — a second independent discovery run is the natural qualifying evidence. Routed to the retrospective inbox, not to adoption; no adoption occurred at confirmation. |
| **Provenance** | Emerged unplanned across the PKS Phase-1 review rounds (Principal Architect + assistant, 2026-07-27); the PA's closing instruction: preserve it in the engineering methodology, **not** in the PKS discovery report — it is method, not product domain. |
| **Placement** | Project-side per ES-006.2/ES-005.3 (research/candidate artifacts live in `docs/implementation/` until promoted); promotion, if ever, would move it beside `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` via the ES-006.1 ladder. |

---

## 1. The promotion pipeline (as operated, once)

```text
Observation → Measured Evidence → Derived Claim → Confidence Assessment
→ Portability Filter → Capabilities Classification → Strategic Modeling → ARB Decision
```

Every step is a gate an idea must pass to become architecture; no step may be skipped; the final act is always a human decision.

## 2. The three orthogonal tests

| Test | Question | Failure mode it blocks |
|---|---|---|
| **Confidence assessment** | How certain are we? | false certainty — inference borrowing an observation's authority |
| **Portability filter** | Could another product instantiate this concept without this repository's conventions? | false generality — a local convention promoted to domain concept |
| **Capabilities classification** | Is this operational / specified-but-unperformed / absent / hypothetical? | false operationality — a specified mechanism assumed to be running |

They are independent: a claim can be high-confidence, portable, and describe something absent (e.g. "whole-system conformance assessment is absent" — High · portable · Absent).

## 3. Claim classes (evidence discipline)

| Class | Meaning | Confidence behavior |
|---|---|---|
| **Observed** | quoted verbatim from an artifact | high by construction (re-read, same answer) |
| **Measured** | counted/executed this run | high by construction (re-run, same answer) |
| **Derived** | follows from observations in one inference step | confidence rates the inference |
| **Synthesized** *(new distinction, this capture)* | integrates multiple observations/contexts into a higher-level explanatory concept | confidence rates the abstraction; reviewers should see how much abstraction occurred |
| **Interpreted** | grouping/judgment | lowest default confidence |

Example: *"documents are not the fundamental unit"* is **Derived** (one step from the concept/file counts); *"Knowledge-System Conformance is a reusable domain phenomenon"* is **Synthesized** (integrates AKB + EKP + platform enforcement-asymmetry evidence across two domains).

Lineage: this extends, and never replaces, the operating label discipline (R-36 epistemic labels: Observed/Measured/Derived/Interpreted; the "single observation ≠ candidacy" rule).

## 4. Confidence attaches to claims, never to concepts

`Decision` has no confidence; *"the document is not the fundamental unit"* does. Confidence is **discovery evidence**, not domain-model content — it must not leak into the model. Compound statements are split before rating so a weak inference cannot borrow a strong observation's authority (the merge example: "merge has no repository evidence" = Measured/High; "therefore merge is unnecessary to the domain" = Interpreted/Low).

In-corpus precedent for the vocabulary: EPIC-004C candidate confidence (High/Medium, ARB decision input) · Round 32B ("DISCOVERED — HIGH confidence / CANDIDATE — MEDIUM-LOW").

## 5. Identify the modeling decision; do not make it in discovery

The discipline's terminal move: when several model shapes explain the same evidence, discovery records the options and stops. Worked example (open at capture time): Knowledge-System Conformance may be **(A)** a first-class concept, **(B)** a derived assessment producing a Verdict, or **(C)** an emergent per-object property aggregating to system level — all three explain the evidence; the choice belongs to Strategic Modeling.

## 6. What this methodology is assembled from

Nothing here is invented ex nihilo; the composition is the novelty. Constituents and their earned homes: evidence-first discovery + "Evidence not found" (EPIC-002 charter discipline, KnowledgeOS Uncertainty Rule) · epistemic labels (R-36) · confidence vocabulary (EPIC-004C) · the portability litmus (ES-005.3, generalized from placement to concepts) · promotion ladder with human gates (ES-006.1; ES-001.2) · capabilities-as-operational-maturity (this run) · claim/concept confidence separation (this run) · Derived/Synthesized split (this run).

## 7. Qualifying evidence required before candidacy

One instance operates (PKS Phase 1). To meet the normal methodology-module bar: **a second, independent strategic discovery executed with this pipeline**, with its reviewer assessing whether the three orthogonal tests discriminated (per the Methodological Fitness Rule: a criterion that never rejects anything is presumed ceremonial). Until then this document is a record, not a proposal.

---

*Traceability: PKS Phase-1 discovery (`Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md`) · review rounds recorded in `.claude/sessions/2026-07-27.md` (PKS entries) · precedents: R-36 · EPIC-004C · ES-005.3 · ES-006.1/.3/.4 · R-39 (single-context exception precedent, NOT invoked here) · module precedent: DDD Tactical Governance Principles. **STOP — recorded for the retrospective inbox; no adoption requested.***
