# C09 — Statistical / ML analysis · v1.2 (delta)

**Base:** `contracts-v1.1/C09-statistical-ml-analysis.md`, sha256 `a9fe614af0a07f897ed726f75e86c365e3d54fcab1e70ae61d7ccc58ec153e9c`. Unchanged: per-file STATISTICAL-ML is deferred; the EXPLORATORY/CONFIRMATORY modes; the admitted methods in §3.

## Changes
1. **Populations (RN-08).** Every F-ID stays in provenance. Duplicate content is collapsed only when a statistical unit is defined. Each checkpoint snapshot (`checkpoints/CP-##/POPULATIONS.json`, `f_checkpoint.populations`) carries:
   - `F-ID-population`: every AUDITED F-ID with its disposition (provenance);
   - `text-audited`;
   - `unique-content`: exact sha256 duplicates collapsed to the first F-copy, with `exact-duplicates-collapsed` mapping each copy to its first;
   - `F-specific-content` and `content-identical-to-S`: both are subsets of unique-content, and CONTENT-IDENTICAL-TO-S is a provenance flag, never an automatic exclusion;
   - `near-duplicate-signal`: 5-word shingles, Jaccard ≥ 0.8, labelled **SIGNAL — not an equivalence claim**;
   - `historical-time-evidence`: list mtime, file mtime, explicit dates and order evidence, for HDR-2.

   An analysis names the population it uses, and treats near-duplicate pairs as dependence to be reviewed, not as independent recurrence (R9).
2. **Similarity is never proof (RN-09).** Jaccard, cosine, clustering and embeddings are **candidate similarity signals**. They are never an epistemic determination of identity, equivalence, causality or conceptual relationship, and they never set a C05 disposition by themselves.
3. **Exploratory → confirmatory (F-15).** An exploratory result may *suggest* a hypothesis registered at a checkpoint. That hypothesis's confirmatory test must then use data the exploratory analysis did not see; its population rule must exclude the exploratory population.
4. **Multiple testing (RN-10).** Whenever more than one confirmatory comparison is performed, the handling must be specified in the pre-registration (`family`, `multiplicity_rule`). The admitted rules are **HDR-3**.
5. **Monitoring-only checkpoints.** Until HDR-2 and HDR-3 are decided, a checkpoint is MONITORING-ONLY: it snapshots and reports, and nothing at it counts as confirmation.
