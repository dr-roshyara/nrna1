# Theory turn 1 (critical-path chain, Rulebook v1.3 §12b, entry 59): Req — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer A = Opus, reviewer B = Sonnet; neither sees the other's report.*

## 1. Deliverable and "resolved when"
**Deliverable:** Req, the questions and operations the kernel must answer and accept, each with purpose, inputs, scope, identity, observable consequences and assumptions (`W/06-theory/manuscript/chapter_09/09.10-required-capabilities.md`, commit `c0ab6daab`).
**Resolved when:** every item has the six fields; every material claim has its grounds labelled; the falsifiers (F-ins, F-min, F-vac) are tested or the obstacle is stated; internal guarantees are kept apart from world assumptions; prior work is checked where a claim could be new.

## 2. What to review
- §9.10 in full (the research question; the definition of Req as (Q, O); Q1–Q7; O1–O4; the candidates not made items; the derivation; the tests; the table of guarantees vs assumptions; prior work; dependants).
- Register row CON-0188; `EXTERNAL-SOURCES.md` rows EXT-038 to EXT-042; the edits to BOOK-OUTLINE, TARGET-TOC, the Ch. 9 index and T9-01 in the same commit.
- **Questions to answer in particular:**
  1. Is each item grounded as labelled (act, Src, Ded, Hyp)? Is any item invented without grounds?
  2. Is the derivation from the central question valid? Which premises are missing?
  3. Is there a trivial system that meets Req (F-vac), beyond the constant-U evaluator the text names?
  4. Is any item redundant (F-min), or is a needed item missing (F-ins)? Search the book (`W/06-theory/manuscript/chapter_0*`) for must-do statements the list omits.
  5. Are the prior-work comparisons accurate (definitions, not names)? Check the cited works only through public bibliographic or publisher pages; put **no corpus or book text into any search**.
  6. Are the dependants (§9.10.8) mapped correctly?

## 3. Inputs the author read and did not write
Everything §9.10 builds on was read by the author for this turn: BOOK-OUTLINE §3a (written by a forked session at stage 2; read now), §5.4 C9, the Ch. 5 owner table, §9.5, §9.6, §9.7, §9.8, §5.5 C3, §6.5 C1, §9.4 C7. Text of stage 2 not read by the author is listed in `review/TURN0-STAGE2/SETTLEMENT.md` §6; none of it is used by §9.10 except as cited above.

## 4. Quote check (entry 60)
`tmp/tools/corpus_quote_check.py` on §9.10: 0 quotes in the checked form (the section quotes no corpus line in `"…" [Snnnn Lx]` form; S0129, S0067 and S0048 are cited in meaning, through the sections that quote them). Baseline: `tmp/tools/QUOTE-BASELINE.txt`.

## 5. Access
- The working book, register, map, outline, review folders and git history of W: allowed.
- Corpus sources: only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (sha256 matching), at the anchors you check.
- **No access to the Ch. 0–2 basis files** (GKC, PKSM, RS and the others in `archive/00-access/READ-LOG.tsv`): the Principal's grant covers the Professor only. A check that needs one is marked U.
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. Edit nothing except your report. Web searches only with your own words, never corpus or book text.

## 6. Method
For each material claim: scope, definitions and assumptions; the derivation step by step; the strongest counterexample; a materially different rival; the falsifier; labels Src / Ded / Int / Hyp / Ext. Severity critical / high / normal (Rulebook §6; R5 for a missing premise, confirm each cited dependency, name uncited ones). A proof-step table for any derivation you judge adequate (R4). An observable "resolved when" for every finding.

## 7. Report
`W/06-theory/manuscript/review/TURN1-REQ/REPORT-A.md` (Opus) or `REPORT-B.md` (Sonnet): verdict table (per item and per question of §2) · findings table (ID · finding · severity · evidence · correction · resolved when) · coverage (what you did not check). Final answer: a summary of at most 10 lines.
