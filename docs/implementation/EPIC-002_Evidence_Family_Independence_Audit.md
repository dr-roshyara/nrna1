# EPIC-002 Evidence Family Independence Audit

**Kind:** methodology quality-assurance audit — not a research iteration. **Authority:** generated; never authoritative without ARB review.
**Scope (as authorized by ARB, 2026-07-25):** verify whether the "independent evidence family" counts recorded in iterations 4 and 5 satisfy the project's own previously established independence criterion (`EPIC-002_Concept_Register.md`: *"genuinely different mechanisms — different assumptions, methodologies, communities, terminology — not discipline labels. Two disciplines sharing one mechanism count as ONE family."*). **No new literature searched. No findings reinterpreted. No phenomenon strengthened or weakened.** The only authorized outputs were: (1) confirm counts unchanged, (2) revise counts with justification, or (3) conclude independence cannot be determined and replace the numeric claim with descriptive language.

---

## Trigger

Across iterations 3a v2 → 4 → 5, P1 climbed 5 → 7 → 8 families and P2 climbed 4 → 10 → 11 families. Iteration 4 alone added six "new families" to P2 in a single count-bump. The independence criterion was applied visibly and explicitly through iteration 3a v2 (e.g., the chain-of-custody row was flagged: *"CAUTION: both legs partly share the procedural-custody mechanism; family independence weaker than the count suggests"*), but no comparable check is visible for iteration 4's six-family jump.

## Method

Re-read the six claimed new P2 families from `EPIC-002_Literature_Review.md`'s iteration 4 section against the independence criterion, asking of each: is this a genuinely distinct **mechanism** (different assumptions/methodology/community/terminology), or the same mechanism recurring in a different institutional label?

## Findings

| Claimed family (iteration 4) | Mechanism | Independence assessment |
|---|---|---|
| Corporate/regulatory audit remediation (PCAOB AS 2201, ICFR deficiency remediation) | A named institutional body (audit committee) exercises professional judgment, documented after the fact; does not self-correct mechanically | Shares mechanism with the three rows below |
| Crisis post-mortem review (OECD) | Institutional, multi-level, judgment-based review after a crisis event | Shares mechanism with the row above |
| Nonprofit board accountability | Boards "struggle to enact" broader accountability; proposed reforms exist because current mechanisms don't reliably self-correct | Shares mechanism with the rows above |
| Constitutional sunset-review practice | Parliamentary renewal review, conditional on affirmative act, empirically "poorly attended, rushed, based on incomplete or misleading information" | Shares mechanism with the rows above |
| ICSID revision/annulment | A narrow, fact/grounds-limited, exceptional **legal remedy** within international arbitration, explicitly not a route to relitigate merits | **Genuinely distinct** — formal legal-procedural mechanism, structurally unlike institutional deliberative review |
| AI-attestation-as-input-not-decision | A technical/cryptographic **design principle**: automated output is architected to feed a human decision, not replace it | **Genuinely distinct** — technical-design mechanism, not an institutional review process |

**The first four rows are the same underlying mechanism — "a named institutional deliberative body performs non-automatic, empirically imperfect oversight/correction" — recurring across four institutional labels (corporate audit, crisis management, nonprofit governance, legislative sunset review).** Per the established criterion, these collapse to **ONE family**, not four.

Iteration 5 added one further family for P2: DDD's "knowledge crunching"/EventStorming Hot Spots/human-consultation mechanism (Evans, Brandolini, arXiv 2309.03796). This is collaborative knowledge-modeling among domain practitioners in a software-design context — structurally distinct from institutional legal/audit/governance review. **Assessed as genuinely independent.**

**P1's iteration-4/5 additions were also checked** (PCAOB AS 2201 procedural-method plurality; OECD data-source-type plurality; DDD source-kind plurality). These three are each a different kind of plurality mechanism (methodological-procedure, evidentiary-data-type, domain-knowledge-source) in different communities — **assessed as genuinely independent; no revision needed.**

## Corrected counts

| Phenomenon | Recorded count | Audited count | Disposition |
|---|---|---|---|
| P1 | 8 | **8 — confirmed unchanged** | Option 1: no revision needed |
| P2 | 11 | **8 — revised** | Option 2: 4 of iteration 4's 6 claimed new families (audit remediation, crisis post-mortem, nonprofit board, sunset-review) collapse into 1 ("institutional deliberative oversight, non-automatic and imperfect"); ICSID and AI-attestation remain distinct; iteration 5's DDD family remains distinct. Corrected: 4 (baseline) + 3 (iteration 4, corrected) + 1 (iteration 5) = **8**. |

**Consequence for downstream claims:** every prior document describing P2 as "the strongest phenomenon" (implicitly by having the highest family count, 11 vs. P1's 8) no longer has that basis. **P1 and P2 are now tied at 8 independent families each.** Neither phenomenon gained or lost a counterexample as a result of this audit — the *substance* of every finding (zero confirmed counterexamples for either phenomenon, the two open P2 categorization questions) is unaffected. Only the **family-count arithmetic** changes.

## Corrections applied (this commit)

- `EPIC-002_Concept_Register.md` — P2 phenomenon-table row and "Reading this table" paragraph corrected (11 → 8; movement-log entry added recording this audit).
- `EPIC-002_Literature_Review.md` — Evidence Sufficiency Review (Iteration 5) corrected ("10 independent evidence families" → "8, tied with P1" is not literally what was written there but the same order-of-magnitude fix applies where P2's count is cited).
- `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md` — both P2 citations corrected; "second-strongest" language corrected to "tied with P1."
- `EPIC-002_Strategic_Domain_Discovery.md` — ubiquitous-language table and Cluster B description corrected.
- `EPIC-002_Bounded_Context_Discovery.md` — CB-3's evidence citations (three places) corrected; "strongest, most robustly cohesive" language retained where still true (CB-3 remains the strongest *decision-owning* candidate — it owns D1, which no P1-tied capability does — but the family-count claim itself is corrected).
- `EPIC-002_Domain_Decomposition_Evaluation.md` — CB-3 strengths entry corrected.

## What this audit does NOT change

- No new counterexample was found or lost for any phenomenon.
- No phenomenon's CONTESTED/unresolved status changed (P3 still ambiguous, P4 still thin, P5 still contested).
- CB-3 (Adjudication) remains the strongest *decision-owning* candidate in the Bounded Context Discovery / Domain Decomposition Evaluation reports — it is the only candidate (besides the contested CB-2/Alt pair) that owns a clear decision (D1) — but its evidence-breadth claim relative to CB-1 (Collection, tied at 8) is corrected from "clearly ahead" to "tied."

---
*Charter: `EPIC-002_Problem_Statement.md` · Audited: `EPIC-002_Literature_Review.md` iterations 4-5, `EPIC-002_Concept_Register.md` phenomenon table · No new sources consulted.*
