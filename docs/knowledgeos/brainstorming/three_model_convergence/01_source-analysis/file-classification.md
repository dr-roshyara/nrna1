# 01_source-analysis — File Classification Matrix (§7)

**PROVISIONAL.** All classifications are pass-1 readings under MD-004. `primary_model_final` is
assigned only at global reclassification, after the entire corpus has been read.
**Taxonomy: MD-006** — A `gita` · B `mathematics` · C1 `engineering_knowledgeos` ·
C2 `epistemic_knowledgeos` · X `cross_model` · M `meta_research` · `foundational` · `experimental` ·
`ambiguous`. `kernel_ddd` is retired as a forward value and retained as historical metadata.

**Position: 2,376 of 2,376 processed — sequential pass 1 COMPLETE (MD-021, Phase 0, 2026-09-07).**
The row-by-row table below was hand-written for the first 10 files only and is kept as the
worked illustration of the schema (never rewritten, per its own footnote). It is **not** continued
row-by-row to the full corpus: doing so would duplicate, not aggregate, content that already lives
in full per-file (`01_source-analysis/per-file/NNNN.{md,yaml}`, 1,224 files) and in the
machine-readable `00_control/classification-register.tsv` (2,376 rows, one per manifest sequence).
The **Running summary** section below is instead extended to the full corpus — a mechanical
aggregate over those two artifacts, not a re-reading or re-interpretation of any file. See
`01_source-analysis/corpus-map.md` for the structural snapshot this summary is drawn from.

| Seq | File | Primary (provisional) | Lineage | Secondary | Tier 2 | Maturity | Importance |
|---|---|---|---|---|---|---|---|
| 0001 | `2026-08-02-knowledgeos-architecture-baseline.md` | `kernel_ddd`※ | **C1** | ddd, domain_model, governance, evidence, provenance, validation | ✅ | DEVELOPING | HIGH |
| 0002 | `KnowledgeOS_Architecture_Validation_Matrix.md` | `meta_research` | C1 | kernel, ddd, governance, evidence, provenance, validation | ✅ | DEVELOPING | HIGH |
| 0003 | `KnowledgeOS_Architecture_Synthesis_Matrix.md` | `meta_research` | C1 | governance, evidence, provenance, validation, contradiction, ddd | ✅ | DEVELOPING | HIGH |
| 0004 | `KnowledgeOS_Architecture_Health_Dashboard.md` | `meta_research` | C1 | evidence, validation, governance, provenance | ⛔ | DEVELOPING | MEDIUM |
| 0005 | `KnowledgeOS_Architecture_Fitness_Assessment.md` | `kernel_ddd`※ | **C1** | kernel, governance, evidence, validation, ddd, invariants | ✅ | DEVELOPING | HIGH |
| 0006 | `KnowledgeOS_ARB_Decision_Docket.md` | `meta_research` | C1 | governance, evidence, validation, contradiction, ddd, provenance | ✅ | DEVELOPING | MEDIUM |
| 0007 | `AI_Workflow_Observation_Log.md` | `meta_research` | n/a | evidence, governance, validation | ⛔ | HYPOTHETICAL | LOW |
| 0008 | `2026-08-02-mvk-bootstrap-validation-report.md` | `experimental` | **C1** | validation, kernel, evidence, provenance, governance, ddd, contradiction, invariants | ✅ | DEVELOPING | HIGH |
| 0009 | `2026-08-02-knowledgeos-strategic-domain-discovery.md` | `kernel_ddd`※ | **C1** | ddd, domain_model, governance, evidence, provenance, contradiction, meta_research | ✅ | DEVELOPING | HIGH |
| 0010 | `2026-08-02-knowledgeos-strategic-boundary-consolidation.md` | `engineering_knowledgeos` | **C1** | ddd, domain_model, governance, evidence, provenance, contradiction, validation | ✅ | DEVELOPING | HIGH |

※ = classified before MD-006; `kernel_ddd` retained as historical metadata, lineage recorded separately. **Not rewritten.**

## Running summary (0001–0010) — historical, first-10 illustration, not rewritten

| Measure | Value |
|---|---|
| Files processed | **10 / 2,320** (0.43%) |
| Primary `gita` (A) | **0** |
| Primary `mathematics` (B) | **0** |
| Primary C1 (incl. historical `kernel_ddd`) | **4** |
| Primary `epistemic_knowledgeos` (C2) | **0** |
| Primary `cross_model` (X) | **0** |
| Primary `meta_research` (M) | **5** |
| Primary `experimental` | **1** |
| Lineage C1 | **9** · not applicable **1** · C2 **0** |
| Tier 2 triggered | **8 / 10** |
| Contradictions recorded `[CT]` | **8** |
| Refutations recorded `[RF]` | **5** (kernel set-sufficiency · MVK sufficiency · F-3 · "0 of 11 enforcing" · the 15-file extraction boundary) |
| Bridges recorded | **1**, candidate only (`c1_to_c2` at 0010) |
| Files with mathematical content | **0** |
| Files with probabilistic content | **0** |
| Files with Gītā/philosophical content | **0** |
| Files defining an operator with a signature | **0** |

### Main contribution per file

| Seq | Main contribution |
|---|---|
| 0001 | Method/binding/evidence decomposition; the portability kernel-membership predicate; evidence-strength ordering |
| 0002 | External validation as a distinct operation; the corpus's first refutation; problem-convergence ≠ solution-convergence |
| 0003 | Five-bucket claim state machine; `Canonical` = human-adopted only; **the ladder collision** |
| 0004 | The projection update rule; instrumentation-vs-canon level for the evidence back-edge |
| 0005 | Three gap kinds (1/1/4); ENFORCEMENT as a dimension; **maturity ≠ enforcement**; first kernel-membership enumeration |
| 0006 | Define-the-metric-before-running-it; mandatory opposing evidence; authorization recursion |
| 0007 | Counts carry their enumerations; an instrument at n=0 |
| 0008 | The corpus's **first experiment**; `domain-free ≠ portable`; the genesis meta-finding; a repeatable falsifiable instrument |
| 0009 | The corpus's premise contradicts its own authority; **proposing-before-searching at n=5**; protocol-vs-records split |
| 0010 | **Two extraction blocker classes**; three bootstrap responsibilities; the closed, exclusive nine-criteria admissibility set |

## Full-corpus running summary (0001–2,376) — MD-021 Phase 0, mechanical aggregate, 2026-09-07

Computed from `00_control/classification-register.tsv` (post-back-fill), `00_control/progress.tsv`
(last occurrence per sequence), and every `01_source-analysis/per-file/*.yaml` (1,224 files; 2 —
seq 0002, 0009 — have malformed YAML and are excluded from the tier2/importance/content-flag counts
below, though their `initial_primary`/`initial_secondary` were hand-verified and are correctly
recorded in the register). No file was re-read or re-interpreted to produce these counts.

| Measure | Value |
|---|---|
| Sequences in manifest | **2,376** |
| DONE (has a per-file record) | **1,224** (primary 1,185 · adjacent/out-of-scope 39) |
| EXCLUDED (no per-file record, by design) | **1,152** |
| Primary `engineering_knowledgeos` (C1) | **742** |
| Primary `meta_research` (M) | **371** |
| Primary `gita` (A) | **84** |
| Primary `cross_model` (X) | **19** |
| Primary `kernel_ddd` (historical, retired forward value) | **3** |
| Primary `mathematics` (B) | **2** |
| Primary `epistemic_knowledgeos` (C2) | **1** |
| Primary `experimental` | **1** |
| Primary anomalous (`not_applicable_product_content`, seq 0056 — confirmed off-topic product content, passed through verbatim rather than force-mapped) | **1** |
| Tier 2 triggered | **1,012 / 1,222** parseable per-file records (≈ 82.8%) |
| Files with `mathematical_content: true` (new-schema field only) | **825** |
| Files with `gita_content: true` (new-schema field only) | **493** |
| Files with `kernel_content: true` (new-schema field only) | **1,033** |

**EXCLUDED breakdown** (progress.tsv category, last occurrence per sequence):

| Category | Count |
|---|---|
| `OUT_OF_SCOPE_ROOT` | 672 |
| `EXCLUDED_VERIFICATION` | 456 |
| `EXCLUDED_SYNTHESIS` | 12 |
| `EXCLUDED_FALSIFICATION` | 7 |
| `EXCLUDED_CORPUS` | 3 |
| `EXCLUDED_CLASSIFICATION` | 2 |

**Observation, not silently normalized:** protocol.md §"Importance" defines a controlled 4-value
scale (`CRITICAL`/`HIGH`/`MEDIUM`/`LOW`). The per-file `importance:` field as actually recorded
across the corpus carries at least 12 distinct free-text values (`critical` 802 · `high` 178 ·
`low` 89 · `medium` 44 · `minor` 35 · `normal` 24 · `major` 23 · `important` 8 · `none` 8 ·
`moderate` 6 · `standard` 3 · `skip` 2). This is reported here verbatim, not collapsed into the
4-value scale — doing so would be an interpretive act outside Phase 0's mechanical scope. Likewise,
the new (post-seq-~53) per-file YAML schema has no `maturity` field at all (only the 47 old-schema
files do), so no full-corpus `maturity` aggregate is given above; the schema-generation gap itself
is recorded as a finding in MD-021 and `corpus-map.md`, not resolved by inventing a mapping.

**"Contradictions", "Refutations", and "Bridges" recorded** (the three narrative-count rows in the
0001–0010 table above) are not aggregated to the full corpus here: they are free-text markers
(`[CT]`/`[RF]`/`bridges:`) inside per-file prose, not a fixed enum field, so a full-corpus count
would require re-reading every record's narrative content — an interpretive act reserved for the
gap-analysis stage (protocol.md stage 6), not this mechanical consolidation.
