# 03_model-b_mathematical — Phase 2: Independent Model B (Mathematics/Statistics) Reconstruction

**Status: IN PROGRESS.** This is the Phase 2 output of **MD-021**'s phased plan
(`14_decision-log/model-boundary-decisions.md`), executed under an explicit, separately-scoped
authorization from the user (2026-09-07, quoted in full in `.claude/plans/purring-tinkering-graham.md`
under "Plan: Phase 2 — Independent Model B (Mathematics/Statistics) Reconstruction"). **Phase 3+
(C1/C2 reconstruction, final classification, cross-model bridges, gap-analysis, formalization,
kernel, dynamics, computational theory, validation, canonical theory) are NOT authorized and are not
touched by this work.**

## Directory-name note

The authorization named `03_model-b_mathematics/`. The stage-gate directory that actually exists in
this repository (created when protocol.md's stage scaffold was first set up, before this
reconstruction began) is **`03_model-b_mathematical/`**. Per the authorization's own §5 instruction
("if the population differs from an earlier assumption, document the difference rather than
silently correcting history"), this discrepancy is recorded here rather than silently resolved: this
phase writes to the existing `03_model-b_mathematical/` directory, not a newly-created
`03_model-b_mathematics/`.

## Purpose

Independently reconstruct **Model B** — the mathematics/statistics lineage (protocol.md's
`MODEL-B = mathematics`) — as evidence, before any comparison with Model A (Gītā), Model C1
(Engineering KnowledgeOS), or Model C2 (Epistemic KnowledgeOS) is attempted. Per the authorization's
explicit independence requirement, **no Model-A conclusion, concept, contradiction, unresolved
equivalence, or boundary observation is used as evidence here**, and no cross-model comparison or
convergence claim is made anywhere in this document set.

## Scope: evidence population, independently derived from the governed records

The reconciled mathematical lane (`01_source-analysis/per-file-mathematical/*.yaml`, 401 files,
`resume_mathematical.py` → `CONSISTENT`) is the authoritative Phase-2 inventory. **Not all 401 files
are Model-B evidence** — membership was determined by each file's own `model.primary` classification
(a corpus-native tag, not a directory), independently recomputed for this phase directly from the
per-file records rather than copied from any earlier summary:

| `model.primary` | Count | Treatment |
|---|---|---|
| **`b` (mathematics)** | **162** | **Model-B evidence population** |
| `KR-SIM` | 199 | Boundary — this lane's own native category for general exploratory/dialogue-style research, distinct from `b` since the *original* 282-file pass |
| `x` (cross_model) | 16 | Boundary |
| `c1` (engineering_knowledgeos) | 13 | Boundary |
| `g` (gita) | 6 | Boundary — already Model-A's own evidence per Phase 1; not touched here |
| `c` (ambiguous, non-canonical shorthand) | 5 | Boundary — data-quality anomaly, see `04_boundary-observations.md` |
| **Total** | **401** | |

Within the 162 `b`-tagged rows, `source_role` was independently checked to separate genuinely
independent content from duplicates and process material:

| `source_role` | Count | Treatment |
|---|---|---|
| `PRIMARY` / `PRIMARY_RESEARCH` / `ADJUDICATION` / `SYNTHESIS` | **151** | **Independent Model-B evidence** (this document set's actual working population) |
| `DUPLICATE` | 10 | Named pointer records (see `01_evidence-base.md`), excluded from independent concept-counting |
| `CONTROL_SELF_REFERENCE` | 1 | A KR-SIM-lane process/audit instruction (M0003), not mathematical content itself — noted, not analyzed |

**Model-B primary evidence = 162 rows = 151 independent records + 10 duplicates + 1
control-self-reference row** — this precision matters because a naive "162 files" count would
silently include 11 rows carrying no independent content.

## Methodology

1. **Sequence order.** The 151 records were read in ascending sequence order from their existing
   `01_source-analysis/per-file-mathematical/NNNN.yaml` records (themselves already rich narrative
   syntheses of the underlying source documents, produced either by the original 282-file pass or by
   this session's own math-lane reconciliation for the later M0283+ tail).
2. **Raw-source verification where it matters.** Per the user's mid-task evidence-fidelity
   guardrail: every claim entering the concept register, the kernel-candidate table, or the
   contradictions register was checked against its governed per-file YAML/MD record; the underlying
   raw `.md` source was additionally inspected wherever a claim was mathematically consequential,
   the per-file record itself flagged ambiguity or competing formulations, a conclusion depended on
   a proof/counterexample, or a kernel-candidate's status could plausibly shift between the four
   required states.
3. **Vocabulary reused, not invented**: protocol.md §3's evidence-status tags, the Maturity scale,
   and MD-017's six-way relationship taxonomy — identical to Model A's own discipline.
4. **No silent normalization of competing mathematical formulations.** This corpus's own research
   programme produces many genuinely competing formalizations of the same construct (e.g. at least
   nine distinct `K_t` state-tuple proposals); each is recorded, compared, and where no corpus-
   internal act establishes equivalence, marked `unresolved_equivalence` — never merged because one
   formulation looks cleaner.
5. **Kernel-candidate discipline** (the authorization's §7, its own dedicated concern): every
   candidate is placed in exactly one of four states — **ESTABLISHED**, **TESTED → REJECTED**,
   **PROPOSED → UNTESTED**, **UNRESOLVED** — verified against this lane's own executed-experiment
   records, never against Model A's material. A candidate absent from an experiment is never
   recorded as rejected; only an experiment that actually ran and produced a negative result earns
   `TESTED → REJECTED`.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_evidence-base.md` | The 151 independent records (+10 duplicates, +1 control-reference) organized into nine evidentiary clusters, sequence-ordered, with a full traceability table. |
| `02_concept-register.md` | Named mathematical concepts/formalisms, each with source, terminology, evidence status, maturity, MD-017-typed relationships — including the dedicated kernel-candidate table. |
| `03_contradictions-and-open-questions.md` | Internal contradictions, unresolved interpretations, competing formulations, unresolved equivalences, and questions Model-B evidence alone cannot settle. |
| `04_boundary-observations.md` | The 239 non-`b` rows accounted for by category; the `c`-tag ambiguity; outward-pointing observations recorded as bounded only. |

## What this phase does NOT do

- Does not use any Model-A conclusion, concept, contradiction, unresolved equivalence, or boundary
  observation as evidence, and does not compare or converge Model B with Model A anywhere.
- Does not assign `final_primary`/`final_secondary`/`classification_change`/`reason_for_change` for
  any register row.
- Does not treat the 239 boundary rows as Model-B evidence, or reclassify any of them to inflate
  coverage.
- Does not modify `00_control/classification-register.tsv`, any Model-A/C1/C2 artifact, or any
  cross-model artifact.
- Does not begin Phase 3 or scope it in any way.

## Current phase status

**Complete, pending verification and completion report.** All five artifacts are written:
`00_index.md` (this file), `01_evidence-base.md` (151-row evidence base, nine evidentiary clusters,
full traceability table), `02_concept-register.md` (15 named concepts/formalisms plus a 15-row
kernel-candidate table in the four required states), `03_contradictions-and-open-questions.md` (5
contradictions, 3 unresolved equivalences, 10 open questions), `04_boundary-observations.md` (the
239 boundary rows accounted for by category, including one data-quality anomaly found and flagged,
not corrected). The verification suite (authorization §9) and the 12-point completion report follow
next.
