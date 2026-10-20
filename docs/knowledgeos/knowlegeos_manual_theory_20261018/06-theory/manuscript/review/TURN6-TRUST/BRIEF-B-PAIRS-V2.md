# Turn 6, commission B: §9.14 v2 in full

*Reviewers B1 = Opus, B2 = Sonnet. Read `COMMON.md` first; its rules bind.*

## Object

`W/06-theory/manuscript/chapter_09/09.14-when-pairs-suffice.md` (v2), **in full**, including the new R3 row ("threshold with forbidden value") and its proof. The history: turn 5 commission B reviewed v1 and settled v2 (`review/TURN5-CONSISTENCY/SETTLEMENT.md` §3).

Also review:
- the pointers in §2.3 (d) and §2.6;
- CON-0192 and EXT-072–076;
- the check `W/tmp/tools/helly_check.py` v2 and its run `helly_check-run-20261020-v2.txt` (about 5 minutes; you may check parts in a scratch copy).

## Resolved when

Every claim in §9.14 v2 is sound as stated or corrected:
- **R1 (a)–(d):** language and base;
- **R2:** with its full-product premise;
- **R3:** both tables, every h, every placement and its representation;
- **R4;**
- **R5 (a)–(d):** in particular the list of h = 2 languages;
- **§9.14.4–§9.14.7;**
- **the turn-5 rows B1-01 … B2-13** are each actually met.

## Questions in particular

1. **The new row "threshold with forbidden value".** Is the proof of minimality right? Is "n on n values" right? Is "unbounded on an unbounded discrete domain" right, and does it need discreteness? What happens on a dense domain such as the rationals?
2. **R5 (d)'s list of h = 2 languages.** Is every listed union actually h = 2?
   - "required values with thresholds" (singletons are intervals);
   - "required and forbidden values on a Boolean or unbounded attribute" (singletons and cofinite sets);
   - products of these on a full product.

   Look for a minimal family of three or more inside any listed language.
3. **R1 (d) for one base** (m(ℛ) ≤ 2). Is it stated correctly and used consistently?
4. **The independent pairs analyser in check 3.** Is it truly independent of the set-wise report, and does it test what R1 (b) claims?
5. **The S1539 row** (the bridge as background logic; size 3 or 4) and **the S0922 cardinality row**: are they read correctly?
6. **Dechter and van Beek 1997** as the closest rival (§9.14.5). Is the description fair? Check only public bibliographic or publisher pages.

## Report

`W/06-theory/manuscript/review/TURN6-TRUST/REPORT-B1.md` (Opus) or `REPORT-B2.md` (Sonnet). Use finding IDs B1-nn or B2-nn.
