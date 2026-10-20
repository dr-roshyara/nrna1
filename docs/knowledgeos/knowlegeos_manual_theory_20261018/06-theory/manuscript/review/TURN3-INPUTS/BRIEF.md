# Theory turn 3 (critical-path chain, Rulebook v1.3 §12b, entry 59): the evaluator's inputs — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer A = Opus, reviewer B = Sonnet; neither sees the other's report.*

## 1. Deliverable and "resolved when"
**Deliverable:** what the evaluator of current authority (CE, Q1's reference definition) takes as input under each reading of fork F37 and each reading of A1, and what its answer then means (`W/06-theory/manuscript/chapter_09/09.12-evaluator-inputs.md`, commit `dc07032fd`; and the DB-1 sentence added to §1.6).
**Resolved when:** each reading combination has its inputs, its meaning and its falsifier; each "same inputs" claim is derived from §1.14.1's definition; each "extra input" claim has a record on which a correct evaluator must consult it; prior work is checked; internal guarantees are kept apart from world assumptions.

## 2. What to review
- §9.12 in full; §1.14.1 (the formal model: definitions, open parameters, declared defects); §9.7 (input table); §3.1 (F37, after turn 0); §1.6 (A1's readings; the DB-1 decision); §9.9 T2; CON-0190; EXT-057.
- **Questions to answer in particular:**
  1. Is B1 right: do (A) and (B) need the inputs stated, and only those? Is the carry relation with pinned versions needed under (B), and is an aggregation rule needed under (A)?
  2. Is B2 right: do de jure, recognized and as form leave CE's inputs unchanged? Look for a record on which two of them must answer differently from the same inputs.
  3. Is B3 right: does A1 as fact need refutation entries as input? Is the counterexample record correct?
  4. Does the DB-1 consequence follow (the kernel's evaluator is CE under A1 recognized)?
  5. Prior work: check the cited works only through public bibliographic or publisher pages, with your own words, **no corpus or book text in any search**.

## 3. Inputs the author read and did not write
§9.12 uses §1.14.1, §9.7, §3.1, §1.6, §5.6 C3, §9.9 and §0.3. §1.6's named readings and §3.1's bearer section were written at stage 2 (§1.6 by a forked session; §3.1 by the author) and corrected at turn 0; the author read both for this turn.

## 4. Quote check (entry 60)
`tmp/tools/corpus_quote_check.py` on §9.12: 0 quotes in the checked form. §1.6: two inherited failures, listed in `tmp/tools/QUOTE-BASELINE.txt`; none new. Baseline: `tmp/tools/QUOTE-BASELINE.txt`.

## 5. Access
- The working book, register, map, outline, review folders and git history of W: allowed.
- Corpus sources: only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (sha256 matching), at the anchors you check.
- **No access to the Ch. 0–2 basis files** (GKC, PKSM, RS and the others in `archive/00-access/READ-LOG.tsv`): the Principal's grant covers the Professor only. A check that needs one is marked U.
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. Edit nothing except your report. Web searches only with your own words, never corpus or book text.

## 6. Method
For each material claim: scope, definitions and assumptions; the derivation step by step; the strongest counterexample; a materially different rival; the falsifier; labels Src / Ded / Int / Hyp / Ext. Severity critical / high / normal (Rulebook §6; R5 for a missing premise, confirm each cited dependency, name uncited ones). A proof-step table for any derivation you judge adequate (R4). An observable "resolved when" for every finding.

## 7. Report
`W/06-theory/manuscript/review/TURN3-INPUTS/REPORT-A.md` (Opus) or `REPORT-B.md` (Sonnet): verdict table (per item and per question of §2) · findings table (ID · finding · severity · evidence · correction · resolved when) · coverage (what you did not check). Final answer: a summary of at most 10 lines.
