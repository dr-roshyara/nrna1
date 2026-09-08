# 01_source-analysis — Corpus Map

**Status:** written after the full pass, per `00_control/protocol.md`'s artifact contract
("Corpus map | `01_source-analysis/corpus-map.md` | after the full pass"). Produced as Phase 0 of
**MD-021** (`14_decision-log/model-boundary-decisions.md`), 2026-09-07. This is a **structural
snapshot of what exists** — sequence ranges, exclusion-category counts, per-lineage provisional
counts — not a classification decision. No file's `final_primary` is assigned here; that remains
`PENDING_GLOBAL_RECLASS` for every row until MD-004's global reclassification (Phases 1–5 of
MD-021, none yet authorized).

## Scope

This map covers the **main corpus** only: 2,376 sequences tracked in `00_control/progress.tsv` /
`00_control/reading-manifest.tsv` / `00_control/classification-register.tsv`. Two directories are
tracked **separately** and are not part of this map, per MD-020:

- `three_model_convergence/` itself — excluded as self-reference.
- `mathematical_ideas_that_can_be_implemented/` — a separately-governed research lane ("KR-SIM"),
  with its own manifest (`00_control/mathematical-manifest.tsv`, 282 entries),
  its own progress ledger (`00_control/mathematical-progress.tsv`), and its own per-file records
  (`01_source-analysis/per-file-mathematical/*.yaml`, 282 files — sequential pass complete,
  confirmed via `00_control/resume_mathematical.py` → `MATHEMATICAL-PART SEQUENTIAL PASS COMPLETE`).
  **Per MD-020, this lane's conclusions are not imported into the classification below** — it
  remains independently-unverified material, exactly like any other external source, subject to
  MD-002/MD-012's independent-verification discipline if and when it is ever drawn on.

## Sequence range and processing state

| Measure | Value | Source |
|---|---|---|
| Total sequences | **2,376** (0001–2376) | `resume.py` / `classification-register.tsv` |
| DONE (has a per-file record) | **1,224** — primary 1,185 · adjacent/out-of-scope 39 | `progress.tsv`, last occurrence per seq |
| EXCLUDED (no per-file record, by protocol design) | **1,152** | `progress.tsv` |
| Per-file YAML records on disk | **1,224** (matches DONE exactly) | `01_source-analysis/per-file/*.yaml` |
| Register rows carrying a transcribed `initial_primary` | **1,224** | `classification-register.tsv` (this Phase 0 back-fill) |

`00_control/resume.py` confirms `CONSISTENT` and `SEQUENTIAL PASS COMPLETE — global reclassification
may now open (MD-004)` as of this writing.

## EXCLUDED breakdown

| Category | progress.tsv (authoritative — last occurrence per seq) | reading-manifest.tsv (a-priori snapshot at manifest build time) |
|---|---|---|
| `OUT_OF_SCOPE_ROOT` | 672 | 711 |
| `EXCLUDED_VERIFICATION` | 456 | 456 |
| `EXCLUDED_SYNTHESIS` | 12 | 8 |
| `EXCLUDED_FALSIFICATION` | 7 | 4 |
| `EXCLUDED_CORPUS` | 3 | 2 |
| `EXCLUDED_CLASSIFICATION` | 2 | 2 |
| **Total** | **1,152** | **1,183** |

**Discrepancy noted, not resolved.** `reading-manifest.tsv`'s `corpus_tier` column is the a-priori
classification made when the manifest was generated/refreshed (MD-020); `progress.tsv` records what
actually happened file-by-file during the sequential pass, which can differ (e.g. the manifest also
shows 1,193 `PRIMARY` vs. progress.tsv's 1,224 DONE + differences distributed across the exclusion
categories above). `progress.tsv` is treated as authoritative here because `resume.py` validates
against it directly and protocol.md names it "the resume point." This gap is recorded as an honest
accounting finding for whoever next touches manifest-regeneration tooling — it is not silently
reconciled by this document.

## Primary classification distribution (provisional, pass 1 — see `file-classification.md` for the
full aggregate and caveats)

| Primary (provisional) | Count |
|---|---|
| `engineering_knowledgeos` (C1) | 742 |
| `meta_research` (M) | 371 |
| `gita` (A) | 84 |
| `cross_model` (X) | 19 |
| `kernel_ddd` (historical, retired forward value) | 3 |
| `mathematics` (B) | 2 |
| `epistemic_knowledgeos` (C2) | 1 |
| `experimental` | 1 |
| `not_applicable_product_content` (anomaly — seq 0056, confirmed off-topic product content, passed through verbatim rather than force-mapped into the six-way scheme) | 1 |
| — (EXCLUDED, no per-file record) | 1,152 |

**Reading this table:** these are **pass-1 provisional** readings (`initial_primary`), not final
classifications. MD-004 explicitly forbids assigning `final_primary` before the three canonical
models (A/B/C1/C2) are independently reconstructed as evidence. The heavy skew toward
`engineering_knowledgeos` and `meta_research` reflects the corpus's own compositional history
(a KnowledgeOS engineering-governance research programme that later grew Gītā/mathematics threads),
not a pre-judgment of what the final model boundaries will be.

## Per-file schema note (discovered during Phase 0, recorded in MD-021)

Per-file YAML records were written under **two schema generations**:

- **Old schema** (47 files, roughly seq 1–53): `primary_model_initial:` (full canonical name) +
  `secondary_models:` (list) + `maturity:` + `status:` + `lineage_provisional:`. This is the schema
  documented in `01_source-analysis/machine-record-schema.md` (MD-005).
- **New schema** (1,177 files, seq ~60 onward): `model: {primary: <shorthand code>, secondary:
  <list|str|"none">}` + `importance:` + `tier2_triggered:` + `mathematical_content:` /
  `gita_content:` / `kernel_content:` (boolean flags). **No `maturity:` or `lineage_provisional:`
  field exists in this generation.** The shorthand codes (`g`/`g1`→gita, `c1`→engineering_knowledgeos,
  `c2`→epistemic_knowledgeos, `b`→mathematics, `x`→cross_model, `m`/`m1`/`meta`→meta_research) are
  not documented anywhere prior to MD-021 — this drift was undocumented until this pass.

This means a literal full-corpus `maturity` column (as used in the 0001–0010 illustrative table)
cannot be mechanically produced for the 1,177 new-schema files — the field simply is not there.
`file-classification.md`'s full-corpus summary omits it rather than inventing values.

## Pointers

- Full per-row classification data (machine-readable): `00_control/classification-register.tsv`.
- Full-corpus aggregate statistics and caveats: `01_source-analysis/file-classification.md` §"Full-corpus running summary".
- Individual file records: `01_source-analysis/per-file/NNNN.{md,yaml}`.
- Math lane (separate, not imported): `01_source-analysis/per-file-mathematical/`, `00_control/mathematical-*.tsv`.
- Readiness finding and phased plan for what comes next: `14_decision-log/model-boundary-decisions.md` → **MD-021**.
