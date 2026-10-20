# Theory turn 4 (critical-path chain, Rulebook v1.3 §12b, entry 59): the evaluator CE* — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer A = Opus, reviewer B = Sonnet; neither sees the other's report.*

## 1. Deliverable and "resolved when"
**Deliverable:** the evaluator of standing (Q1's reference definition): inputs, output, the authority it relies on, and when its answer is valid, unique or undefined (`W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md`, commit `2649b302c`).
**Resolved when:** the definition is total on a stated domain, with a proof that is a proof; T, F, U and "undefined" each have a stated meaning; §1.14.1's seven declared defects are each repaired, restricted or stated open; §9.7 result 2 is withdrawn with reason; Lemma D (§9.8) and T1 (§9.9) are re-checked; prior work is checked **corpus first** (revised practice 2); internal guarantees are kept apart from world assumptions.

## 2. What to review
- §9.13 in full; the model `W/tmp/tools/ce_model.py` and its run `ce_model-run-20261020.txt` (run it: `python3 -I ce_model.py`, about a minute); CON-0191; EXT-062–065; the changes in the same commit to §9.7 (result 2), §9.8 (Lemma D), §9.9 (T1), §9.10 (Q1, Q3, the vacuity table), the Ch. 9 index and BOOK-OUTLINE.
- Background: §1.14.1 (the formal model, its parameters and defects), §9.12 (the inputs, settled at turn 3), §9.10 (Req), §9.1 C6.
- **Challenge by type (practice 5):** R1 (totality, uniqueness) is mathematical: challenge it by a countermodel or an invalid step. R3 (WF over KK) is a definitional choice: challenge by boundary cases, coherence, consequences and a rival definition. R2a and the C1 link are interpretive.
- **Questions to answer in particular:**
  1. Is R1's proof correct? Is Φ really monotone (look for a clause that is not built from strong Kleene connectives over atoms and constants)? Does the n + 1 bound hold?
  2. Is the domain D stated precisely enough to decide membership? Is "undefined only outside D or Θ_m" true?
  3. Does the model test what the text says? Note: random records cite only earlier entries, so positive cycles arise only in the constructed case (check 7); say whether that weakens check 6.
  4. Is R3's argument for WF sound, and is the corpus correctly read (S1414 §196.47, S1761)?
  5. Are §9.13.5's repairs of defects 1–7 adequate, and is "argued, not modelled" (σ, F26 (b)) stated honestly?
  6. Prior work: is the corpus correctly read and cited (Src)? Are the outside works benchmarks only? Check outside works only through public bibliographic or publisher pages, in your own words, **no corpus or book text in any search**.

## 3. Verification marks (practice 6)
| Changed piece | Author | Read by the Professor | Reasoning checked by the Professor |
|---|---|---|---|
| §9.13 (all) | Professor | yes | yes |
| `tmp/tools/ce_model.py` | Professor | yes | yes (8 checks run) |
| §9.7 result 2 (withdrawal), §9.8 Lemma D, §9.9 T1, §9.10 Q1/Q3/vacuity table | Professor | yes | yes |
| CON-0191, EXT-062–065, index, outline | Professor | yes | yes |
No helper session wrote any part of this turn.

## 4. Corpus reading for this turn (practice 2, corpus first)
Six logged scans (read log rows "SCAN", 2026-10-20). Selection rule fixed before reading (patterns matched, an authority or fixed-point pattern required, index order, at most 8); relevance checked at the hit lines; read whole as topic-full: S0875, S1496, S1498, S1761; sections only: S1414 (§196.19–20, §196.47; topic-full at TT2), S1486 (L280–319; an 83 KB trace). Not read, with reasons, in §9.13.8. Reviewers may open these sources (allowed list, sha256) to check the Src claims.

## 5. Quote check (entry 60)
`tmp/tools/corpus_quote_check.py` on §9.13: 6 found, 0 not found. On §9.7–§9.12: no new failure. Baseline: `tmp/tools/QUOTE-BASELINE.txt`.

## 6. Access
- The working book, register, map, outline, review folders, tools and git history of W: allowed.
- Corpus sources: only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (sha256 matching).
- **No access to the Ch. 0–2 basis files** (GKC, PKSM, RS and the others in `archive/00-access/READ-LOG.tsv`). A check needing one is marked U.
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. Edit nothing except your report.

## 7. Method and report
For each material claim: scope, definitions, assumptions; the derivation step by step; the strongest counterexample (by type, practice 5); a rival; the falsifier where the claim should be testable; labels Src / Ded / Int / Hyp / Ext. Severity critical / high / normal (Rulebook §6; R5). A proof-step table for any derivation you judge adequate (R4). An observable "resolved when" for every finding.
`W/06-theory/manuscript/review/TURN4-EVALUATOR/REPORT-A.md` (Opus) or `REPORT-B.md` (Sonnet): verdict table · findings table (ID · finding · severity · evidence · correction · resolved when) · coverage. Final answer: a summary of at most 10 lines.
