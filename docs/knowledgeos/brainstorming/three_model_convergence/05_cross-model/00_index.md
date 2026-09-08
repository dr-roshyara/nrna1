# 05_cross-model — Phase 3: Cross-Model Adjudication and Controlled Convergence

**Status: IN PROGRESS.** This is the Phase 3 output of **MD-021**'s phased plan
(`14_decision-log/model-boundary-decisions.md`), executed under an explicit, separately-scoped
19-section authorization from the user (2026-09-07, quoted in full in
`.claude/plans/purring-tinkering-graham.md` under "Plan: Phase 3 — Cross-Model Adjudication and
Controlled Convergence"), issued only after Model A (Phase 1) and Model B (Phase 2) were each
independently completed, audited, and frozen. **Phases 4–6+ (C1/C2 reconstruction, final
classification, gap-analysis, formalization, kernel, dynamics, computational theory, validation,
canonical theory) are NOT authorized and are not touched by this work.**

## Directory-numbering clarification (recorded, not silently resolved)

MD-021's own phase numbers (Phase 1/2/3/4/5/6+) do **not** map 1:1 onto
`three_model_convergence/`'s directory numbers (`02_model-a_gita/` / `03_model-b_mathematical/` /
`04_model-c_kernel-ddd/` / `05_cross-model/` / …). MD-021's Phase 3 — cross-model adjudication — is
what `protocol.md`'s own stage-gate sequence names `05_cross-model/`. `04_model-c_kernel-ddd/`
remains reserved for a *later*, still-unauthorized MD-021 phase (Model C1/C2 reconstruction), not for
this phase. This document set is written to `05_cross-model/` (confirmed empty before this phase
began), the directory-numbering-correct target.

## Purpose

Determine, from the two already-frozen independent reconstructions, **which structures, concepts,
relations, and candidate mechanisms are genuinely comparable, potentially corresponding, merely
analogous, incompatible, or currently unresolved.** This is an **adjudication phase, not a
theory-merging phase.** The default status for any apparent correspondence is **UNRESOLVED until
equivalence is demonstrated** (authorization §2).

## Non-negotiable governing principle

**SIMILARITY ≠ IDENTITY.** Shared terminology, shared diagrammatic form, shared mathematical shape,
shared functional role, or shared philosophical description is never, by itself, evidence of
identity. Every proposed correspondence in `02_correspondence-matrix.md` is leveled across six
distinct evidentiary strengths, weakest to strongest:

1. **Lexical similarity** — the same or similar word is used.
2. **Conceptual similarity** — the two objects address a similar concern.
3. **Functional similarity** — the two objects play a similar role in their respective systems.
4. **Structural correspondence** — a demonstrable structural parallel exists (not merely asserted).
5. **Formal equivalence** — a structure-preserving map is exhibited and checked.
6. **Demonstrated identity** — the two objects are shown to be the same object.

No row in the matrix is assigned a relationship state stronger than what its own evidence
demonstrates. Most rows are expected to land at levels 1–3 or at **UNRESOLVED**; that is a correct
outcome of adjudication, not a failure of it (authorization §12).

**A second governing instruction, given mid-execution by the user and binding throughout this phase**:
*the correspondence-candidate list below is a starting point, never a hidden completeness assumption.*
If the evidence reveals a correspondence outside the initial list, it is added. Conversely, if an
initially proposed candidate has no defensible counterpart, that is recorded explicitly as **"no
demonstrated counterpart"** rather than forced into a weak relationship. Both directions are honored
throughout `01_cross-model-evidence.md` and `02_correspondence-matrix.md`.

## Authoritative inputs

- **Model A**: `02_model-a_gita/` (all five artifacts — 84 evidence rows, 14 concepts §A–§N, 4
  contradictions, 5 unresolved equivalences, 6 open questions), read as governed, not re-derived.
- **Model B**: `03_model-b_mathematical/` (all five artifacts — 151 independent evidence rows, 15
  concepts §A–§O, a 15-row kernel-candidate table §P, 5 contradictions, 3 unresolved equivalences, 10
  open questions), read as governed, not re-derived.
- Underlying raw source (`01_source-analysis/per-file/*.{yaml,md}` for Model A;
  `01_source-analysis/per-file-mathematical/*.{yaml,md}` for Model B) consulted per the
  authorization's §11 source-verification rule wherever a cross-model claim is consequential,
  ambiguous, disputed, or foundational — not for every row.
- The math lane remains the reconciled 401-file inventory; Model B's evidence population (162 `b`
  = 151 independent + 10 duplicate + 1 control-reference; 239 boundary) is unchanged from Phase 2 and
  is neither re-derived nor re-opened here.

## Methodology

1. **`01_cross-model-evidence.md` first** — both registers organized against the authorization's
   sixteen named investigation targets (kernel, minimality, representation, structure, state,
   transition, operator, invariant, equivalence, reduction, closure, history/audit, composition,
   event, observation, knowledge/epistemic status), recording for each target what object (if any)
   each model actually contains — an explicit "Model X has no counterpart for this target" finding is
   itself evidence, recorded as such, not silently skipped.
2. **`02_correspondence-matrix.md` next** — one row per candidate correspondence (from the sixteen
   targets, from any further candidate the evidence itself surfaces, or from a target found to have
   no counterpart), each carrying: Model-A concept/structure · Model-B concept/structure · source
   references into both frozen registers · proposed relationship type · evidence for · evidence
   against · preserved differences · required assumptions · adjudication status · confidence ·
   unresolved questions. Relationship-state vocabulary (authorization §5, all seven, none forced):
   IDENTITY ESTABLISHED / FORMAL EQUIVALENCE ESTABLISHED / STRUCTURAL CORRESPONDENCE / FUNCTIONAL
   ANALOGY / PARTIAL CORRESPONDENCE / INCOMPATIBLE / UNRESOLVED.
3. **Kernel adjudication treated with the authorization's own required extra strictness** (§7):
   Model A's kernel-related material is never collapsed into one "kernel," Model B's differentiated
   kernel-candidate states (§P) are preserved exactly, and the representation-dependence hard
   constraint (§8) is explicitly checked for any kernel-adjacent row reaching STRUCTURAL
   CORRESPONDENCE or above.
4. **`03_adjudications-and-contradictions.md`** — the dedicated cross-model disagreement register
   (reconcilable / conditionally reconcilable / irreconcilable-under-current-definitions /
   unresolved, per §9), never resolved by choosing the more convenient formulation; explicitly never
   imports Model-B's negative results into Model A or vice versa (§10).
5. **`04_non-convergences-and-open-questions.md`** — the mandatory "what does not converge" section
   (§12): model-specific structures with no counterpart, rejected correspondence proposals,
   insufficient-evidence items. Any suggested new abstraction is labeled **PROPOSED CROSS-MODEL
   HYPOTHESIS** (§14) and is never treated as part of either original model.
6. **Neither Model A nor Model B is rewritten.** Both are frozen independent witnesses (§4). Any
   reinterpretation required to state a correspondence is marked explicitly as inference/hypothesis,
   never as source evidence.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_cross-model-evidence.md` | Both registers organized against the sixteen investigation targets, with explicit "no counterpart" findings recorded where applicable. |
| `02_correspondence-matrix.md` | The per-row adjudication matrix, seven-state vocabulary, six-level evidentiary ladder applied to each row. |
| `03_adjudications-and-contradictions.md` | Cross-model disagreements, the strict kernel adjudication, the representation-dependence check. |
| `04_non-convergences-and-open-questions.md` | What remains separate; rejected correspondence proposals; any PROPOSED CROSS-MODEL HYPOTHESIS, explicitly labeled. |

## What this phase does NOT do

- Does not modify `02_model-a_gita/`, `03_model-b_mathematical/`, `classification-register.tsv`, any
  per-file source record, or the mathematical manifests.
- Does not construct a unified/final model, perform global reclassification, or begin Phase 4 (Model
  C1/C2 reconstruction).
- Does not resolve any Model-A-internal or Model-B-internal contradiction, unresolved equivalence, or
  open question — those remain exactly as Phase 1/Phase 2 left them; this phase may reference them
  but never closes them.
- Does not promote any correspondence past what its own evidence supports, and does not treat
  "convergence" as a success metric — a correctly-adjudicated UNRESOLVED or INCOMPATIBLE row is as
  valid an outcome as a correctly-adjudicated STRUCTURAL CORRESPONDENCE.

## Current phase status

**Complete, pending verification and completion report.** All five artifacts are written:
`00_index.md` (this file), `01_cross-model-evidence.md` (both registers organized against the 16
investigation targets, with 8 explicit "no counterpart" findings), `02_correspondence-matrix.md` (10
adjudicated rows, none reaching STRUCTURAL CORRESPONDENCE or above), `03_adjudications-and-
contradictions.md` (the strict kernel adjudication, the representation-dependence check recorded
inapplicable-by-vacuity, 2 apparent-contradictions resolved as term-collisions, 0 genuine
contradictions found), `04_non-convergences-and-open-questions.md` (the mandatory non-convergence
section, 1 PROPOSED CROSS-MODEL HYPOTHESIS). The verification suite (authorization §17) and the
15-point completion report follow next.
