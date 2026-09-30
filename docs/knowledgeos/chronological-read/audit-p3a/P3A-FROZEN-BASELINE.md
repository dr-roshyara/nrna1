---
task: P3A closure -- freeze P3A as an auditable, noncanonical baseline before starting file-level
  historical reconstruction as a separate phase
status: FROZEN 2026-09-22
frozen_commit: 125cfe8371efa4b0520c260636fab79cbd8ebddf
derived_from: [KSME-18 through KSME-22D, all documents under docs/knowledgeos/chronological-read/audit-p3a/]
---

# P3A Frozen Baseline

This document closes the P3A investigation line (KSME-18 through KSME-22D). P3A is **paused and frozen**,
not abandoned and not canonicalized. No further P3A expansion or repair work should begin without a new,
explicit commission — the next phase (file-level historical reconstruction) is a separate, differently-
scoped effort that treats P3A as one evidence source among several, never as ground truth.

## Established

Facts directly demonstrated by execution or audit, not inference:

- The chronological-read pipeline (P1 extraction, P2 label/family normalization, P3a pairwise
  reconciliation) is real, executed, and substantially complete: **70/70 batches DONE, 2,779 files read,
  27,906 typed contributions extracted, 1,895 canonical object-index labels + 1,348 unresolved candidates,
  2,591 family dossiers, 1,793 reconciliation pairs judged.**
- `scripts/derive_reconciliation.py`'s `row_brief()` function has **no schema key at all** for
  `dependencies`, `lineage_claims`, `invariants`, or `assumptions` — confirmed by direct code inspection,
  not inference. **7,407 of 27,906 contributions (26.5%) carry ≥1 non-empty value in these fields**, all
  dropped by `row_brief()` for 100% of them.
- `audit-p3a/v2/scripts/evidence_bundle_v2.py`'s `row_bundle_v2()` **exactly preserves all four fields for
  100% of the 7,407 affected rows, 0 mismatches, 0 file-metadata-join failures** — verified against the
  real, complete dataset (not a fixture sample).
- The P3a quality-gate self-audit's headline finding is reproduced exactly: on the 158-pair high-stakes
  slice (SAME 45 + REPLACEMENT 19 + DERIVED-FROM 94), **20.9% exact agreement (33/158), 75.3% coarse
  agreement (119/158)** against independent re-derivation.
- Of 684 UNWITNESSED verdicts (38.1% of all 1,793 pairs), **0 carry the protocol's own
  `negative_verdict_label` field** (NEGATIVE-BOUNDED/NEGATIVE-CENSUS), though the schema slot exists and
  4 correctly-labeled INDEPENDENT verdicts elsewhere show the mechanism works when used.
- **257 already-judged P3a pairs are evidence-loss-affected** (cross-referenced against
  `P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl`'s 1,467 candidate label-pairs with unused signal evidence);
  only 24 of these overlap the 158 high-stakes slice — 233 sit entirely outside it.
- **Final bounded re-adjudication scope = 414 pairs (23.1% of 1,793)** = union(257 evidence-loss-affected,
  158 high-stakes, 28 NEGATIVE-CENSUS-anomaly-flagged).
- Of 2,138 `lineage_claims`: **126 (5.9%) resolved to an exact, unique target file; 624 (29.2%) are
  ambiguous** (the cited short code appears in >1 file); 224 (10.5%) extracted a token found nowhere else;
  **1,164 (54.4%) contain no extractable ID token at all** (genuine free prose).
- The unified L1 file-level graph (P3a-cited source_ids + 126 resolved lineage edges + 58 narrative-chain
  edges from both halves of `09-ORCHESTRATOR-FLAGS.md`): **1,124 of 2,779 files connected (40.4%), 1,655
  files L1-unconnected (59.6%)**, largest connected component 516 files, 70 components of size ≥3.
- The KSME-22D L2 pilot (15 files, drawn from the 233-file ambiguous-clue pool): **15/15 found a real
  connection via actual whole-file reading, 0/15 found nothing; 8/15 had the cited token genuinely match,
  6/15 had the cited token be a false cognate** with the real relationship found only by reading. Merged
  result: 1,140 connected (41.0%), 1,639 L1/L2-unconnected (59.0%), largest component 528.
- `audit-p3a/v2/` (candidate-generation/evidence-bundle redesign) is a real, working, git-untracked,
  never-adopted engine, validated against 3,089 real corpus-derived candidate rows (99.93% recall of
  previously-invisible signal pairs) — but its own validation report explicitly states it does not
  determine relationship verdicts, only candidate visibility.

## Validated mechanisms

Mechanisms whose implementation or extraction was actually tested, not merely proposed:

- The evidence-integrity test (source row → `row_brief()` vs. `row_bundle_v2()` → assert field
  preservation) — a real, deterministic, corpus-wide test, reproducible.
- The mechanical UNWITNESSED triage (`unwitnessed_mechanical.json`): zero-content-side detection (25 pairs)
  and non-NONE-basis detection (10 pairs), both rule-based, both auditable.
- The `negative_verdict_label` backfill script (656 NEGATIVE-BOUNDED, 28 NEGATIVE-CENSUS-flagged) — a
  keyword-screen, explicitly disclosed as a screen, not an adjudication.
- The track-provenance classifier (directory-prefix rules) — mechanical, reproducible, disclosed as
  incomplete for the ~51% of objects whose dominant evidence sits outside this session's Track-A/B/Lane-B
  taxonomy.
- The file-level graph-construction method (extracting cited `Sxxxx` source_ids from pair evidence text,
  building undirected connected components) — real, reproducible, disclosed limitation (first/last-cited-
  file simplification, not full multi-file clique construction).

## Experimental findings

Useful, real, but not canonical — do not treat as established facts about KnowledgeOS theory:

- The corpus's own terminal self-audit (`09-ORCHESTRATOR-FLAGS.md` B0070) concluding that apparent
  gaps/contradictions are almost always already-resolved-elsewhere, a declared scope exclusion, or
  deliberately open — a corpus-internal finding, not independently re-verified by this investigation
  beyond citing it.
- The 55-65+ named multi-file chains found in the orchestrator log (Zero Lens self-correction, the
  v1.0→v1.2→v2.0 theory-version lineage, the FR-001 freeze lineage, etc.) — real narrative findings from a
  prior extraction pass, not independently re-verified by this investigation except for the 16 explicitly
  structured into graph edges.
- The specific relationship types assigned by P3a to any individual pair outside the 414-pair bounded
  re-adjudication scope — presumed more reliable than the flagged subset, but not separately audited.

## Unresolved

Questions or populations not fully adjudicated:

- The 624 RESOLVED_AMBIGUOUS lineage claims — require per-claim judgment, deliberately not forced into
  edges.
- ~30+ named narrative chains referencing only step/GN numbers (not literal source_ids) — need a separate
  step-to-source_id resolution pass.
- The two confirmed ontology-coverage gaps in the 11-value relationship enum ("operationally-connected-
  but-architecturally-independent," "symmetric/co-integrative sibling") — named, not resolved; closing them
  is a governance decision (new enum value vs. sub-typed status field), never made.
- Whether `audit-p3a/v2/`'s candidate-generation layer should be adopted into production — named, not
  decided.
- The ~218 remaining files in the same ambiguous-clue pool the L2 pilot drew from, and the yield of a
  random (non-clue-selected) sample of the ~1,400+ files with no existing clue at all — neither processed.

## Deferred

Work deliberately stopped because the project is moving to the file-level reconstruction phase, not
because it is finished:

- Completing the 414-pair bounded re-adjudication.
- Resolving the 624 ambiguous lineage claims.
- Any further P3a repair, ontology extension, or v2 adoption decision.
- Scaling the L2 pilot beyond the 15-file proof of concept.

## Quantitative baseline (every number traceable to a cited source document)

| Metric | Value | Source |
|---|--:|---|
| P3a reconciliation pairs | 1,793 | `31-RECONCILIATION-PAIRS.jsonl`; KSME-20 |
| Corpus contribution rows | 27,906 | `03-CONTRIBUTIONS.jsonl`; KSME-20 |
| Corpus files | 2,779 | `02-FILES.jsonl`; KSME-20 |
| Rows with fields `row_brief()` drops | 7,407 (26.5%) | KSME-21 Phase 1 |
| v2 field-preservation result | 100% of 7,407, 0 mismatches | KSME-21 Phase 1 |
| High-stakes slice / exact / coarse agreement | 158 / 20.9% / 75.3% | KSME-20-P3A-QUALITY-REPRODUCTION |
| Evidence-loss-affected already-judged pairs | 257 (24 overlap high-stakes) | KSME-21 Phase 3 |
| Final bounded re-adjudication scope | 414 (23.1%) | KSME-21 Phase 3 |
| Lineage claims total | 2,138 | KSME-22C Step 5 |
| — resolved exact | 126 (5.9%) | KSME-22C Step 5 |
| — ambiguous | 624 (29.2%) | KSME-22C Step 5 |
| — unresolved (token found nowhere else) | 224 (10.5%) | KSME-22C Step 5 |
| — no extractable token (prose) | 1,164 (54.4%) | KSME-22C Step 5 |
| UNWITNESSED pairs / with `negative_verdict_label` | 684 / 0 | KSME-20-P3A-ERROR-TAXONOMY |
| L1 unified graph: edges | 1,317 | KSME-22C Step 6 |
| L1 unified graph: files connected / unconnected | 1,124 (40.4%) / 1,655 (59.6%) | KSME-22C Step 6 |
| L1 largest component / components ≥3 | 516 / 70 | KSME-22C Step 6 |
| L2 pilot: files examined / real connections / found-nothing | 15 / 15 / 0 | KSME-22D |
| L2 pilot: token genuinely matched / false cognate | 8 / 6 | KSME-22D |
| Post-pilot: files connected / unconnected | 1,140 (41.0%) / 1,639 (59.0%) | KSME-22D |
| Post-pilot: largest component | 528 (19.0%) | KSME-22D |

## What P3A does NOT prove

Stated explicitly, per the closure requirement:

1. **P3A is not the canonical historical derivation graph.** It is a pairwise, label-level reconciliation
   product with known, quantified evidence-visibility defects.
2. **P3A relationship adjudications are not all revalidated.** Only the 158-pair high-stakes slice and the
   257-pair evidence-loss-affected set have been independently checked; the remaining ~1,379 pairs are
   presumed, not confirmed, more reliable.
3. **The 414 affected relationships have not been re-adjudicated.** Their exact affected-set size (23.1%
   of 1,793) is established; their corrected verdicts are not.
4. **The 624 ambiguous lineage claims remain unresolved and deferred**, not resolved by any process in
   this baseline.
5. **The 1,639 currently L1/L2-unconnected files are not proven unrelated to the connected graph.** Absence
   of a discovered edge is not evidence of independence — this is the single most important discipline
   established across KSME-22B/C/D, and it must carry forward unchanged into the next phase.
6. **The KSME-22D 15/15 pilot result must not be extrapolated to the full corpus.** The sample was
   deliberately drawn from a favorable, clue-bearing subset (233 of 1,655 files); the yield on the
   remaining, clue-free population (~1,400+ files) is unmeasured.
7. **P3A is therefore a candidate/evidence-navigation baseline, not canonical historical truth.**

## Preserved role of P3A going forward

$$P3A = \text{candidate/evidence-navigation layer}$$

Permitted future uses: candidate relationship discovery, navigation aid, prior hypothesis source, evidence
lookup, comparison against newly reconstructed file-level relationships, measuring recall of the new
method, identifying disagreements between `G_reconciliation` (P3a) and `G_path` (the file-level
reconstruction). **P3A must never automatically establish a new historical edge in the next phase.** Any
edge P3A suggests must be re-verified by the same whole-file-reading standard applied to everything else.

## Freeze record

- **Commit**: `125cfe8371efa4b0520c260636fab79cbd8ebddf`
- **Date**: 2026-09-22 02:52:49 +0200
- **Branch**: `knowelegeos-modelling`
- **Scope**: 3,052 files, 4,739,980 insertions, 559 deletions — `docs/knowledgeos/chronological-read/**`
  (the full P1-P3a pipeline: `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `31-RECONCILIATION-PAIRS.jsonl`,
  `11-OBJECT-INDEX.jsonl`, `11-UNRESOLVED-CANDIDATES.jsonl`, `20-FAMILIES/` [2,591 files], `ledger/`,
  `ledger-p3a/`, `ledger-p3b/`, `scripts/`, `audit-p3a/` [163 files, includes this document's own
  predecessors KSME-18 through KSME-22D]) plus `.claude/sessions/2026-09-22.md`.
- **Explicitly excluded from this commit, confirmed by pre-commit checks**: the 142-file relocation/
  retimestamping operation touching `knowledgeos_theory_research/` and
  `mathematical_ideas_that_can_be_implemented/` (pre-existing, unrelated, unfinished); `.claude/CONTEXT.md`
  (diff not cleanly isolated to this session's content); all unrelated `app/`, election-admin, and
  `.claude/{MEMORY,plans,settings}` changes; anything under `docs/knowledgeos/theory-extraction/
  reconstruction/`; `list_of_files_to_read.log`.
- **Checks executed before commit**: `git diff --cached --name-status` reviewed in full; six explicit
  confirmation greps (no relocation-set files, no `theory-extraction/reconstruction/` files, nothing
  outside the two approved roots, `CONTEXT.md` absent, no unrelated `app`/election/`.claude` files, zero
  deletions) all passed. Post-commit `git status` on `docs/knowledgeos/chronological-read/` confirmed
  clean (0 remaining changes).
- **Resulting repository state**: `docs/knowledgeos/chronological-read/` fully committed and clean; all
  other pre-existing working-tree state (85 untracked, 142 deleted, 8 modified paths, none belonging to
  this work) left untouched exactly as found.
