# Theory turn 2 (critical-path chain, Rulebook v1.3 §12b, entry 59): the kernel type, derived from Req — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer A = Opus, reviewer B = Sonnet; neither sees the other's report.*

## 1. Deliverable and "resolved when"
**Deliverable:** whether the corrected kernel type (§9.1 C6, CON-0183, with result 7 added at turn 1) is enough for Req (§9.10, settled at turn 1) and for the laws the book tests, and which laws need steps or whole histories (`W/06-theory/manuscript/chapter_09/09.11-kernel-type-from-req.md`, commit `5ebf1659a`).
**Resolved when:** every law the book states for kernel candidates is classified with a reason; each "C6 cannot express" claim has a counterexample; each "C6 expresses" claim is derivable from the type's definition; the whole-history result (R1) is proved; prior work is checked; internal guarantees are kept apart from world assumptions.

## 2. What to review
- §9.11 in full; §9.1 C6 (including result 7, added at turn 1); §9.10 (Req, settled at turn 1: `review/TURN1-REQ/SETTLEMENT.md`); CON-0189; the F36 extension in THEORY-MAP; EXT-050.
- **Questions to answer in particular:**
  1. Is each of the 25 laws in the right class? Give a counterexample run (or pair of runs) for any misclassification.
  2. Does R1 hold: are all past-referring laws expressible once the record is in the state? Is the record a valid history variable under C6 result 5's three conditions?
  3. Are R2's four counterexamples correct, and is the list of inexpressible laws complete? Search the book (`W/06-theory/manuscript/chapter_0*`) for laws of the form "eventually", "always able to", "across applications", "whoever asks".
  4. Is R4 (C3's reach) correct?
  5. Prior work: check the cited works only through public bibliographic or publisher pages, with your own words, **no corpus or book text in any search**. Is a classification of this kind established elsewhere?

## 3. Inputs the author read and did not write
§9.11 uses the laws as stated in §0.5, §1.6, §1.7, §1.14.2, §1.16, §5.6, §8.10 C10, §9.1, §9.7, §9.8, §9.9 and §9.10. The author read each of these for this or an earlier turn of the chain. §5.6 C3's must-do sentence was written at stage 2 by a forked session; the author read it for this turn.

## 4. Quote check (entry 60)
`tmp/tools/corpus_quote_check.py` on §9.11: 0 quotes in the checked form (no corpus line is quoted). Baseline: `tmp/tools/QUOTE-BASELINE.txt`.

## 5. Access
- The working book, register, map, outline, review folders and git history of W: allowed.
- Corpus sources: only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (sha256 matching), at the anchors you check.
- **No access to the Ch. 0–2 basis files** (GKC, PKSM, RS and the others in `archive/00-access/READ-LOG.tsv`): the Principal's grant covers the Professor only. A check that needs one is marked U.
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. Edit nothing except your report. Web searches only with your own words, never corpus or book text.

## 6. Method
For each material claim: scope, definitions and assumptions; the derivation step by step; the strongest counterexample; a materially different rival; the falsifier; labels Src / Ded / Int / Hyp / Ext. Severity critical / high / normal (Rulebook §6; R5 for a missing premise, confirm each cited dependency, name uncited ones). A proof-step table for any derivation you judge adequate (R4). An observable "resolved when" for every finding.

## 7. Report
`W/06-theory/manuscript/review/TURN2-TYPE/REPORT-A.md` (Opus) or `REPORT-B.md` (Sonnet): verdict table (per item and per question of §2) · findings table (ID · finding · severity · evidence · correction · resolved when) · coverage (what you did not check). Final answer: a summary of at most 10 lines.
