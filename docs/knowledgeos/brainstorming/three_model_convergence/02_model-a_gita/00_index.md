# 02_model-a_gita — Phase 1: Independent Model A (Gītā) Reconstruction

**Status: IN PROGRESS.** This is the Phase 1 output of **MD-021**'s phased plan
(`14_decision-log/model-boundary-decisions.md`), executed under an explicit, separately-scoped
authorization from the user (2026-09-07, quoted in full in `.claude/plans/purring-tinkering-graham.md`
under "Plan: Phase 1 — Independent Model A (Gītā) Reconstruction"). **Phase 2 (Model B) and Phases
3–6+ (C1/C2 reconstruction, final classification, cross-model bridges, gap-analysis, formalization,
kernel, dynamics, computational theory, validation, canonical theory) are NOT authorized and are
not touched by this work.**

## Purpose

Independently reconstruct **Model A** — the Gītā/philosophical-epistemic lineage (protocol.md's
`MODEL-A = gita`) — as evidence, before any comparison with Model B (mathematics), Model C1
(Engineering KnowledgeOS), or Model C2 (Epistemic KnowledgeOS) is attempted. Per MD-004, model
boundaries are not decided before reconstruction; this document set is that reconstruction for
Model A alone.

## Scope

- **In scope:** the 84 sequences in `00_control/classification-register.tsv` whose
  `initial_primary == gita` — protocol.md's own provisional pass-1 classification, unchanged by
  this work. See `01_evidence-base.md` for the full ordered list.
- **Explicitly out of scope for this phase:**
  - The 511 sequences carrying a `gita`/`g`/`g1` **secondary** tag under a different primary
    (498 `engineering_knowledgeos`, 10 `cross_model`, 3 `meta_research`) — these are **not** Model-A
    evidence; they are recorded as an appendix in `04_boundary-observations.md` only.
  - Model B, C1, C2 reconstruction; cross-model bridges; gap analysis; final classification.
  - `mathematical_ideas_that_can_be_implemented/` (the separately-governed math lane, per MD-020 —
    its conclusions are not imported here).

## Methodology

1. **Evidence base = the 84 `initial_primary == gita` records**, taken directly from
  `01_source-analysis/per-file/NNNN.{yaml,md}` (the pass-1 per-file records already produced by the
  main sequential read — this is a *synthesis* of already-extracted evidence, not a re-extraction).
  All 84 were read in full (their per-file YAML records, which are themselves rich narrative
  syntheses of the underlying source documents, not raw extracts) in ascending sequence order.
2. **No raw-source re-reads were required.** All 84 records use the corpus's "new" per-file schema
  (rich `introduces`/`defines`/`refines`/`contradicts`/`repeats`/`connects_to`/`bridges` fields);
  none fell in the old-schema range (seq ≤ ~53) and none carry a `bytes:0` placeholder anomaly.
  Where a record's own content flagged an unresolved sequencing or provenance question (e.g. seq
  0407 vs 0405, or the two-way ambiguity at seq 0808's retracted formulas), that uncertainty is
  preserved in `03_contradictions-and-open-questions.md`, not resolved here.
3. **Vocabulary reused, not invented**, per the authorization's Boundary 5 and this repository's own
  discipline: the evidence-status tags `[SR]/[DR]/[DF]/[HP]/[CG]/[PR]/[TH]/[EX]/[AN]/[UN]/[OP]/[RF]/
  [CT]` (protocol.md §3), the Maturity scale (`ESTABLISHED/DEVELOPING/HYPOTHETICAL/SPECULATIVE/
  UNDEFINED/CONTRADICTORY`), and **MD-017's six-way relationship taxonomy** (`new_concept /
  new_representation / new_decomposition / refinement / genuine_contradiction /
  unresolved_equivalence` — `unresolved_equivalence` is the *default* for a plausible-but-unproven
  correspondence, never silently promoted to `new_representation`).
4. **Duplicates and near-duplicates preserved, not silently dropped**: several of the 84 are
  byte-identical or near-identical duplicates (self-labeled `-duplicate`/`-variant`, or confirmed via
  the per-file record's own diff note) — these are named in `01_evidence-base.md` and excluded from
  double-counting in the concept register, exactly as the corpus's own MD-009 duplicate discipline
  already treats them.
5. **Every claim in `02_concept-register.md` and `03_contradictions-and-open-questions.md` carries a
  source `seq` reference.** No concept is asserted without a traceable evidentiary anchor.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_evidence-base.md` | The 84 sequences, organized by evidentiary cluster, sequence-ordered, with a full traceability table. |
| `02_concept-register.md` | Named concepts/formalisms Model A's evidence produces, each with source, terminology, evidence status, maturity, and relationships (MD-017-typed). |
| `03_contradictions-and-open-questions.md` | Internal contradictions, unresolved interpretations, competing formulations, unresolved equivalences, and questions Model-A evidence alone cannot settle. |
| `04_boundary-observations.md` | The 511 secondary-tagged files; outward-pointing `connects_to`/`bridges` fields found inside the 84 primary records; schema-drift carryforward. |

## What this phase does NOT do

- Does not assign `final_primary`/`final_secondary`/`classification_change`/`reason_for_change` for
  any register row — every row remains `PENDING_GLOBAL_RECLASS`/`PENDING`.
- Does not compare Model A against Model B/C1/C2, establish an equivalence, or derive a unified
  theory.
- Does not treat the 511 secondary-tagged files as Model-A evidence, or promote any
  `bridge_candidate`/`connects_to` field into an established cross-model bridge.
- Does not touch any file outside `02_model-a_gita/` other than the governed decision-log/
  CONTEXT/session-log updates this project's own standing discipline requires at phase closure.

## Current phase status

**COMPLETE AND AUDITED (2026-09-07).** All five artifacts are written and independently
re-verified. Summary: 84 primary evidence records processed (76 independent + 8
duplicates/near-duplicates, organized into 7 evidentiary clusters), 14 named concepts/formalisms
registered, 4 genuine contradictions + 5 unresolved equivalences + 6 open questions recorded, 511
boundary-observation rows accounted for. **The Phase-1 audit found and corrected one
overstatement**: the "zero kernel candidates" principal finding, as first drafted, did not
sufficiently scope itself to the KR-SIM companion series' own narrow test — ten other files in this
evidence base self-flag `alternative_minimal_kernel: true` and were never cross-checked against that
series (see `02_concept-register.md` §N's scope correction and `03_contradictions-and-open-questions.md`
CT-4). See the Phase-1 completion report and audit report (session log / decision-log addenda,
2026-09-07) for full verification results. **Phase 2 remains unauthorized and unstarted.**
