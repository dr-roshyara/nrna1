# Phase 5B — Independent Lineage Reconstruction and Provenance Analysis

**Status: IN PROGRESS.** Executed under the user's explicit authorization of Phase 5B only
(2026-09-07), issued after Phase 5A's completion. **Phase 5C, global reclassification, four-model
convergence, Kernel equivalence, canonical Kernel selection, unified theory, and implementation
remain unauthorized and untouched.**

## Purpose

Determine **which historical relationships the corpus itself demonstrably documents**, independent
of the current C1/C2 classification labels — reconstructing documentary lineage first, never
assuming `C1 → C2`, `C1 ∥ C2`, or `C1 → limitation → C2` as a starting point. This is a provenance
and lineage reconstruction phase, **not** a model-adjudication phase.

## Governing methodological principle

`document → explicit provenance/derivation evidence → research-object lineage → branch/merge/
refinement structure → documented transitions → only then a possible C1/C2 relationship hypothesis`.
A chronological sequence is evidence of sequence, not causation. A shared term is evidence of lexical
overlap, not identity. A classification label is evidence of historical classification, not
independently sufficient evidence of underlying lineage.

## Frozen inputs (read-only)

Model A (`02_model-a_gita/`), Model B (`03_model-b_mathematical/`), Phase 3
(`05_cross-model/`), Phase 4 (`04_model-c_kernel-ddd/`), Phase 5A
(`14_decision-log/MD-021-phase-5a-classification-boundary-audit/`), `classification-register.tsv`,
and every existing per-file record. Phase 5A's own findings are used here as **research leads**, not
as conclusions to be propagated without independent re-verification.

## Populations, with denominators kept separate (per §4 of the authorization — never treated as
## equivalent)

| Population | Size | Source |
|---|---:|---|
| Main-corpus `PRIMARY`-tier rows | 1,185 | `reading-manifest.tsv` `corpus_tier` |
| Math-lane rows | 401 | `01_source-analysis/per-file-mathematical/` |
| `meta_research` ∩ `kernel_content=true` (main corpus) | 306 | Phase 5A Census A |
| `phase_measure_theory/knowledgeos_kernel/` files on disk | 237 | Phase 5A, re-confirmed |
| Files matching the Kernel-definition text-pattern census (main corpus) | 116 | Phase 5A Part 2, re-used |
| Files containing the literal string `K-1` (main corpus) | 22 | newly run this phase, mechanical `grep` |
| Files matching transition-language markers (main corpus, 10 markers) | 46–563 per marker, see `04_provenance-graph-and-transitions.md` | newly run this phase |
| Files containing C2-defining vocabulary corpus-wide (`Knowledge Space`/`Buddhi`/`purification`/`Moksha`/`K_t`/`admissibility`) | 125 / 102 / 68 / 52 / 396 / 65 | newly run this phase |

**These populations are not equivalent and are never merged into one count.** Each artifact below
states exactly which population a given finding is drawn from.

## Unit of analysis — two levels, never silently converted

- **Level A — Document**: a source file, identified by `seq`/`M####` and path.
- **Level B — Research object**: a named or formally distinguishable construct (a Kernel candidate,
  a Knowledge-state tuple, an invariant, an operator set, an aggregate, etc.). **One document may
  contain multiple Level-B objects; multiple documents may describe one Level-B object.** No count in
  this phase's artifacts converts a document count into an object count, or vice versa, without saying
  so explicitly.

## Provenance relationship taxonomy (used only where evidenced; never inferred from chronology alone)

`ORIGINATES` · `REFINES` · `REDEFINES` · `REJECTS` · `FALSIFIES` · `REPRODUCES` · `VERIFIES` ·
`SUPERSEDES` · `DERIVES_FROM` · `EXPLICITLY_REFERENCES` · `EXPLICITLY_DISTINGUISHES_FROM` ·
`POSSIBLE_RELATIONSHIP` · `UNRESOLVED`. Default for any candidate correspondence: `UNRESOLVED` (or,
where MD-017's own vocabulary is the more precise fit for a same-model concept pairing,
`unresolved_equivalence`) — never promoted without a demonstrated mapping.

## Five-level evidence typing (applied to every consequential claim in this phase)

**Level 1** — direct source evidence (the raw `.md` source says it). **Level 2** — machine-observable
corpus fact (repository structure, metadata, hash, sequence, classification, established
mechanically). **Level 3** — reconstructed provenance (multiple Level-1/2 facts jointly support a
lineage relation). **Level 4** — research interpretation (this investigation's own higher-level
reading). **Level 5** — hypothesis (a proposition for future testing). **Level 3–5 claims are never
presented as Level 1.**

## Statistical discipline

No p-values, effect sizes, significance tests, convergence scores, or prevalence/probability estimates
are produced anywhere in this phase. Every count carries its own population definition,
selection mechanism, and unit. The Phase-5A 10-file systematic sample is **not** used here to estimate
corpus-wide prevalence of anything. Qualitative words ("most," "many," "systematic," "rare") are
avoided unless a denominator is stated alongside them or the statement is explicitly labeled
qualitative.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_population-and-method.md` | Full population/denominator tables; the 13-relationship taxonomy applied; methodology detail for each census/search performed. |
| `02_kernel-lineage.md` | Corpus-wide Kernel-object discovery (not starting from the 8 candidates); the provenance graph for a disclosed, prioritized subset of objects; the K-1 investigation. |
| `03_c1-c2-lineage-evidence.md` | The C1 lineage scaffold, every arrow classified A–E; the C2 lineage reconstruction from corpus-wide vocabulary census, independent of the sole classified C2 file. |
| `04_provenance-graph-and-transitions.md` | Transition-language census and classification (A–E); the full K-1 provenance writeup; negative findings on literal-phrase searches. |
| `05_non-lineages-and-unresolved-relationships.md` | Mandatory negative findings; objects with no demonstrated predecessor/successor; homonyms; chronological-only successions. |
| `06_verification-and-completion-report.md` | Verification suite; 10 raw-source spot-checks; the required final outputs A–G. |

## What this phase does NOT do

Does not reclassify any file. Does not modify `classification-register.tsv` or any Model A/B/Phase-3/
Phase-4/Phase-5A artifact. Does not adjudicate the C1↔C2 relationship as a model question (only as a
documentary-lineage question, per §18.G of the authorization — a lineage finding, not a model
adjudication). Does not establish Kernel equivalence. Does not perform cross-model work against Model
A/B. Does not create a unified theory or canonical Kernel. Does not implement anything. Does not begin
Phase 5C.

## Current phase status

**COMPLETE.** All 7 artifacts written; verification suite passed; 13 raw-source spot-checks
performed (requirement ≥10); 3 evidence-preserving corrections applied and disclosed in
`06_verification-and-completion-report.md`. See that file for the full completion report.
