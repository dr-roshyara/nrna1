# Phase 5J — K-2 Authority, Version, Provenance and Supersession Adjudication

**Status: COMPLETE.** Executed under the user's explicit, separate authorization of Phase 5J only
(2026-09-08), following the completed Phase 5I integrity audit. **Phase 5K, global reclassification,
four-model convergence, unified/canonical Kernel construction, DDD context-map adoption, and
implementation remain unauthorized and untouched.**

## Central question, answered up front (full evidence in `15`)

*Can the corpus establish a legitimate authority, version, provenance, or supersession relationship
among the competing K-2 Assertion definitions, or are they genuinely unresolved competing
definitions?*

**Answer: No legitimate authority/version/supersession relationship is established. The competing
definitions remain genuinely unresolved (Outcome E — "Multiple definitions are competing but neither
has authority" — combined with Outcome G, "provenance/authority is unresolved," for the specific
question of temporal/creation order).**

## Two decisive negative findings this phase

1. **Git history cannot establish creation or modification order between any of the D285/exec
   artifacts.** All of D285-1, D285-6, D285-7, and the three executable scripts were added to version
   control in a **single bulk commit** (`70fee73c`, 2026-09-06, "check in the brainstorming corpus...
   as untracked-until-now research material"). This commit reflects when the corpus was imported into
   this git repository, not when the underlying research was written. **Repository history yields
   `NOT EVIDENCED` for every ordering question the authorization's §7 asks**, precisely as the
   authorization itself anticipated as a valid outcome.
2. **Step 272A and Step 272B — read in full this phase for the first time (3,556 lines combined) —
   do not define `Assertion`'s own field structure at all.** They are the corpus's own cited source
   for K-2's operation set (`𝒪_sem`) and its epistemic-status structure, and they are genuinely
   substantive on those two subjects (`02`, `03`) — but neither ever writes `Assertion = (...)`. They
   are **silent** on the exact conflict Phase 5I found, not resolving it. This is itself informative:
   the two documents this reconstruction most needed to check for a reconciling definition turn out
   not to contain one.

## A third `Qualify` variant found, and a resolution lead for `Π`

Step 272A supplies a **third arity**: $Qualify(o,c,\pi) \rightarrow e$ (3 arguments: observation,
context, policy). Step 272B's own `Assess: (A,E,\Pi,C,\pi) \rightarrow AssessmentResult` uses **both**
capital $\Pi$ and lowercase $\pi$ in the same formula, with only $\pi$ (lowercase) ever elaborated in
surrounding prose as "the policy" — capital $\Pi$ appears exactly once, undefined. Both symbols, where
elaborated, denote Policy — **this is new evidence for Phase 5I's own unresolved "Π=Policy vs.
Π=Provenance" question, weighing toward Policy (2 of 3 sources now agree), though not conclusively
(the third source, `e_equality.py`, is executable code with a concrete worked example, arguably
stronger evidence than either prose source alone)**.

## Frozen Phase-5I baseline

All 12 of Phase 5I's own findings (`01`) are preserved, neither silently strengthened nor weakened.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_k2-definition-version-register.md` | Every materially distinct K-2/Assertion formulation, extended with Step 272A/B. |
| `02_step-272a-analysis.md` | Full analysis of Step 272A (seq 0911). |
| `03_step-272b-analysis.md` | Full analysis of Step 272B (seq 0912). |
| `04_chronology-audit.md` | Filename-date ordering, with the mandatory chronology≠authority discipline. |
| `05_repository-history-audit.md` | The git bulk-commit finding. |
| `06_provenance-graph.md` | Typed edges, extended from Phase 5I. |
| `07_authority-and-governance-audit.md` | Explicit authority-marker search across all sources. |
| `08_assertion-version-adjudication.md` | Per-definition ontology/representation/semantics/provenance/authority/temporal/supersession/compatibility. |
| `09_pi-denotation-adjudication.md` | The Π/π collision, now with 3 sources instead of 2. |
| `10_id-semantics-adjudication.md` | Field-existence vs. field-derivability. |
| `11_qualify-version-adjudication.md` | Now 4 variants (1-arg, 2-arg, 3-arg, differently-named), tested. |
| `12_mathematical-compatibility.md` | Bijection/injection/surjection/embedding tests between the competing Assertion definitions. |
| `13_ddd-implications-only.md` | Updated architectural implications, no pattern declared. |
| `14_k2-source-of-truth-matrix.md` | Per the authorization's §18 — "NO DEMONSTRATED SOURCE OF TRUTH" tested explicitly. |
| `15_final-authority-and-status-matrix.md` | The required source-of-truth matrix and 12 explicit answers. |
| `16_open-questions-and-source-gaps.md` | Reconstruction gap vs. source-research gap vs. source contradiction vs. governance gap, kept distinct. |
| `17_verification-and-completion-report.md` | Verification suite; ≥20 raw-source checks; final classification. |

## What this phase does NOT do

Does not modify any frozen artifact, D285-1/D285-6/D285-7, Step 272A/272B, or any executable script.
Does not declare a DDD context mapping. Does not perform four-model convergence. Does not construct a
unified Kernel. Does not implement anything. Does not begin Phase 5K.
