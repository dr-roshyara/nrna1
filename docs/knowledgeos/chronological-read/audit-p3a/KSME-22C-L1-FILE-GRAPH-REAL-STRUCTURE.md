---
task: KSME-22C step 3 (import P3a edges with reliability metadata) + step 11 (graph closure numbers)
scope: corpus-wide chronological-read pipeline; FILE-level graph, not label-touch coverage
supersedes_in_part: KSME-22B-L1-GRAPH-COVERAGE.md's "80% coverage" framing -- flagged there as
  possibly over-readable, now measured precisely and shown to be a materially different, smaller number
derived_from: [31-RECONCILIATION-PAIRS.jsonl, 02-FILES.jsonl, direct computation this pass]
---

# KSME-22C — Real File-Level Graph Structure (corrects the "80% coverage" framing)

## The correction, precisely

`KSME-22B-L1-GRAPH-COVERAGE.md` reported "2,222/2,779 files (80.0%) touched by ≥1 P3a chain-type pair."
That number measures **label co-membership** (a file counts as "touched" if it contains any contribution
carrying a label that appears on either side of a judged pair) — not an actual file-to-file relationship.
A label can span dozens of files; none of that structure was in the 80% figure. The user's objection is
correct: `∃ e ∈ E_chain : file ∈ e` (touched by an edge) is not the same claim as "connected into a
validated historical path."

## Method for a real file-level graph

For each of the 1,793 P3a pairs, extracted the specific `Sxxxx` source_id citations embedded in the pair's
own evidence text (`what_says_this`/`notes_for_p3b` — e.g. "A (S1474) frames... B (S1475)..."). These are
the actual files the reconciling agent anchored its verdict to, not every file that happens to share the
label. Mapped source_ids to file paths (`02-FILES.jsonl`), took the first and last distinct cited file per
pair as a representative edge (a disclosed simplification — some pairs cite more than two files; this likely
undercounts true connectivity but does not change the order of magnitude), and computed real undirected
connected components.

## Results

| Metric | Count | % of 2,779 |
|---|--:|--:|
| Pairs with 0 extractable source_id citations | 296 | -- |
| Pairs with only 1 distinct cited file (no edge possible) | 364 | -- |
| **Pairs yielding a real file-to-file edge** | **1,133** | 63.2% of 1,793 pairs |
| Files with ≥1 real file-level edge | 1,003 | **36.1%** |
| **Isolated files (no file-level edge at all)** | **1,776** | **63.9%** |

Edge reliability tags: 803 `P3A_VALIDATED`, 204 `P3A_TRUNCATED` (text-mention heuristic, not a structured
flag -- the pipeline has no clean truncation field, confirmed by direct schema check), 126
`P3A_AFFECTED_REQUIRES_REVIEW` (the KSME-21 414-pair set).

**Component structure** (the actual answer to "how much of the historical lineage is reconstructed"):
- 186 connected components of size ≥2.
- **One genuine giant component: 406 files (14.6% of the corpus)** — real, substantial reconstructed
  structure, not a touch-count artifact.
- Sharp drop-off after that: next-largest components are 38, 28, 24, 11, 10, 10, 9, 9, 8 files.
- **Median component size is 2** — most "connections" found are simple pairs, not chains.
- **Only 62 components (size ≥3) are genuine multi-file chains.**
- Total files inside any component: 1,003 (36.1%) — this, not 80%, is the honest "some file-level
  lineage structure exists" number.
- Chain-type-relationship-only subgraph (EXTENSION/CONTINUATION/REFINEMENT/DERIVED-FROM/SPECIALIZATION):
  761 files (27.4%) connected, 207 components, largest 50, 72 chains of size ≥3.

## What this changes

1,776 files, not the previously-estimated 557, currently have zero file-level edge in the graph built from
P3a citation evidence alone. This is the honest gap-set — but it is measured **before** merging in the two
evidence sources not yet incorporated into this graph: the 55-65+ named multi-file chains in
`09-ORCHESTRATOR-FLAGS.md` (narrative, not yet structured into file-node/edge records) and the prose-target
`lineage_claims` (93.6% of 2,138, requiring a real resolution pass, not yet done). Both are likely to
connect some fraction of the 1,776 before any new whole-file (L2) reading is needed. Per KSME-22C's own
specified order, those two merges come next, not L2 reading yet.

## Limitations of this pass, disclosed

- The first/last-cited-file simplification may miss real multi-file structure within pairs citing 3+ files
  — a fuller pass would build a clique or a more careful representative-edge choice per pair.
- `P3A_TRUNCATED` is a heuristic (text mentions "truncat" anywhere), not the pipeline's own structured flag
  — no such field exists in `31-RECONCILIATION-PAIRS.jsonl` (confirmed by direct schema enumeration this
  pass: only `pair_id, a, b, relationship, basis, type_compatibility, what_says_this,
  what_would_make_this_wrong, notes_for_p3b, _batch_id, homonym_positive_evidence, negative_verdict_label,
  corpus_wide_search_note` exist as keys across all 1,793 rows).
- This graph does not yet include the ORCHESTRATOR-FLAGS narrative chains or resolved lineage_claims —
  both pending, per the execution order above.
