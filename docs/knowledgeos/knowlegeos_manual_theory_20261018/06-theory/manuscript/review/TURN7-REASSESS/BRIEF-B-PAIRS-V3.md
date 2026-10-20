# Turn 7, commission B: §9.14 v3 in full

*Reviewers B1 = Opus, B2 = Sonnet. Read `COMMON.md` first; its rules bind.*

## Object

`W/06-theory/manuscript/chapter_09/09.14-when-pairs-suffice.md` as it now stands (v3), **in full**. Turn 6 commission B reviewed v2 and settled v3 (`review/TURN6-TRUST/SETTLEMENT.md` §2).

Also review:
- `W/tmp/tools/helly_check.py` v3 and its run `helly_check-run-20261020-v3.txt` (checks 1–7);
- CON-0192 and EXT-081–083;
- the §2.3 (d) and §2.6 pointers.

## The changed theory, listed in full (v3)

1. **The row "threshold with forbidden value":**
   - n on n values (R4, reached by the complements alone);
   - unbounded when the order has finite intervals of every size (ℕ with one threshold, ℤ with two; m + 2);
   - 3 on a dense order with closed thresholds (proof), 2 with strict ones;
   - the column reads "only if n ≤ 2";
   - a neutral example; no book rule of this form found;
   - check 7.
2. R5 (c): with thresholds, one interior forbidden value already gives h = 3 (an endpoint gives 2); check 7.
3. R5 (d):
   - "ordered" means total and non-cyclic;
   - "infinite" replaces "unbounded";
   - the contradiction with h = 0 on infinite domains is removed;
   - MUST_NOT of an interval or a conjunction leaves the class;
   - the "h > 2" list adds partial and cyclic orders (check 7).
4. "Sound" is defined: it reproduces the set-wise report. The weaker point-flag property is distinguished, and the heading carries the realisability premise.
5. Convention: the coupling is part of X.
6. The tool:
   - check 7 computes n on n values;
   - check 3 adds classes with ∅, boxes, and required and forbidden values on a Boolean, and is called "sampled";
   - check 6's tautological line is marked.
7. Rivals added: Beeri, Fagin, Maier and Yannakakis 1983; Chepoi et al. 2010; van Beek and Dechter 1995. Dechter–van Beek is restricted to its abstract's words.
8. EXT-074's title spelling.
9. S1539's MCS sizes are relative to background logic; S0922's cardinality is h = 2 as written; its line range is L21–58.
10. h is a supremum, possibly ∞; R5 (a) computes regions.

**Turn 7:** no change. §9.16 R1 marks §9.14 "untouched" by the decisions. Check that claim: §9.14 should read no record, no CE* and no trust assumption.

## Resolved when

- Every claim in §9.14 v3 is sound as stated, or corrected: R1 (iff h ≤ 2), the one-base form (iff m ≤ 2), the product lemma (R2), R3–R5 and the new row with its proof.
- The v3 rows are each actually met.
- The rivals are described within their public abstracts.

## Questions in particular

1. Is the dense-order case (h = 3, closed thresholds) proved? Is the strict case 2?
2. Is "unbounded when the order has finite intervals of every size" the exact condition, or are there orders between?
3. Is "sound = reproduces the set-wise report" the definition R1 needs, in both directions?
4. Does check 7 compute what the text cites, with no tautology left?
5. Is the comparison with Beeri et al. (acyclic schemes) and Chepoi et al. (Helly and 2-SAT) fair, and does any of them already contain R1?

## Report

`W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-B1.md` (Opus) or `REPORT-B2.md` (Sonnet). Use finding IDs B1-nn or B2-nn.
