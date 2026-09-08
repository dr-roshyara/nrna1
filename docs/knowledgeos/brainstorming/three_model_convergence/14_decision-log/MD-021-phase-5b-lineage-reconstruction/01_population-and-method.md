# Phase 5B — Population and Method

## Population definitions and denominators (restated with full detail from `00_index.md`)

1. **Main-corpus `PRIMARY`-tier rows: 1,185.** The complete governed population any lineage claim in
   this phase can be checked against, joined from `classification-register.tsv` ×
   `reading-manifest.tsv`'s `corpus_tier` field.
2. **Math-lane rows: 401.** Complete population, `01_source-analysis/per-file-mathematical/`.
3. **`meta_research` ∩ `kernel_content=true`: 306** (main corpus only). Established in Phase 5A
   Census A; re-used, not re-derived, here.
4. **`phase_measure_theory/knowledgeos_kernel/` on-disk files: 237.** Re-confirmed this phase via
   direct `find` query (mechanical, Level 2).
5. **Kernel-definition text-pattern census hits: 116** (main corpus). Established in Phase 5A Part 2;
   re-used here as the starting point for §`02_kernel-lineage.md`'s corpus-wide discovery — **this
   phase does not start from Phase 4's "eight," per the authorization's explicit instruction.**
6. **Literal-string `K-1` hits: 22** (main corpus). Newly run this phase: `grep -lE "\bK-1\b" *.yaml`
   across all 1,185 `PRIMARY`-tier per-file records — a mechanical, complete-population search
   (Level 2), not a sample.
7. **Transition-language marker hits, per marker** (main corpus, complete population, mechanical
   `grep` count): "derived from" 153 · "based on" 66 · "extends" 563 · "reformulates" 52 · "replaces"
   46 · "supersedes" 161 · "the previous Kernel" 0 · "from EKS" 2 · "what actually is knowledge" 0 ·
   "motivated by" 62. **Methodological caveat, disclosed**: several of these markers (notably
   "extends") substantially overlap with the per-file YAML schema's own **structured field names**
   (`extends:`, `refines:`) — meaning a large share of these hits reflect the classifier's own
   cross-reference bookkeeping (a Level-2 machine-observable fact about how the per-file record
   labels a relationship), not necessarily free-prose argumentation. This is stated explicitly so the
   counts are not misread as counts of narrative transition-claims in raw source prose.
8. **C2-defining vocabulary corpus-wide hits** (main corpus, complete population, mechanical `grep`
   count, case-insensitive): "Knowledge Space" 125 · "Buddhi" 102 · "purification" 68 · "Moksha" 52 ·
   "K_t" 396 · "admissibility" 65. **These are corpus-wide counts, not filtered by classification** —
   directly testing whether protocol.md's own C2-defining vocabulary is confined to the single
   `epistemic_knowledgeos`-tagged file (it is not, by a very large margin).

## Method, by artifact

- **§`02_kernel-lineage.md`** starts from population 5 (the 116-hit marker census), re-examines its
  classification breakdown (already known from Phase 5A: 92 inside C1, 14 `meta_research`, 5
  `cross_model`, 4 `gita`, 1 C2), and builds a provenance-graph table for a **disclosed, non-
  exhaustive, prioritized subset** — the objects with the richest available evidence, not all 116.
  This is stated as a scope limitation, consistent with the "document a full graph for every
  possible object" instruction being interpreted proportionately: a complete per-object graph for
  116+ objects (several hundred, once Level-B research objects rather than Level-A documents are
  counted) is not attempted; the subset chosen is named and justified in that artifact.
- **§`03_c1-c2-lineage-evidence.md`** uses the C1 lineage scaffold already established by Phase 4's
  own cluster narrative (re-examined and re-classified per the A–E transition-evidence scale here,
  not merely re-asserted), and a corpus-wide C2-vocabulary first-appearance analysis on population 8,
  using a **defined sample** (disclosed in that artifact) since reading all up to 396 `K_t`-bearing
  files is disproportionate to this phase's own scope.
- **§`04_provenance-graph-and-transitions.md`** uses population 6 (K-1) read in full (22 files is
  small enough for a census-complete read of title + targeted raw-source spot-checks) and population
  7 (transition markers) on a **defined sample** per marker, classified A–E.
- **§`05_non-lineages-and-unresolved-relationships.md`** draws negative findings directly from the
  positive findings in `02`–`04` — no new census or sample is run for this artifact; it restates,
  under the "negative evidence is first-class evidence" instruction, what `02`–`04` already found
  and explicitly could not connect.

## Confirmed methodological limitations of this phase, disclosed up front

- The 116-hit and 22-hit censuses are **text-pattern** searches over per-file YAML text (titles,
  `introduces`/`defines`/free narrative fields) — they find files *mentioning* the pattern, not a
  verified count of *distinct* objects. Distinctness is judged only for the disclosed subset actually
  examined in `02_kernel-lineage.md`.
- No claim in this phase extrapolates a sample finding (Phase 5A's own 10-file sample, or any sample
  drawn in this phase) to the size of an unread population.
- This phase does not attempt to read all 237 files in `phase_measure_theory/knowledgeos_kernel/`, all
  306 `meta_research`∩`kernel_content` rows, or all up to 396 `K_t`-bearing files — each of those
  remains a **larger population than this phase's own bounded scope can exhaustively cover**; where a
  finding is drawn from less than the full population, the actual files read are named.
