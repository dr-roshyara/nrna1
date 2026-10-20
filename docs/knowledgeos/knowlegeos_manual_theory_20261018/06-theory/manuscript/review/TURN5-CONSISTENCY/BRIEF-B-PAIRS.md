# Theory turn 5, commission B: global consistency — when checking pairs suffices — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer B1 = Opus, reviewer B2 = Sonnet; neither sees the other's report, nor commission A's (`BRIEF-A-CE-CHANGES.md`). Both commissions are settled before turn 6.*

## 1. Deliverable and "resolved when"

**Deliverable (the Principal's instruction):** "check that the set-wise conflict test holds for the book's rule classes, or state the class for which checking pairs suffices." Text: `W/06-theory/manuscript/chapter_09/09.14-when-pairs-suffice.md` (new), with pointers added to §2.3 (d) and §2.6.

**Resolved when:**
- the class for which pairs suffice is stated precisely, with a proof that is a proof and a converse with its premise stated;
- each of the book's rule classes is placed, with its Helly number derived and its place in the book cited correctly;
- the consequences for §2.3 (d) (cost) and §2.6 (partitioning) are correct;
- prior work is checked **corpus first** and the status of the idea is no stronger than both searches support;
- internal guarantees are kept apart from world assumptions.

## 2. What to review

- §9.14 in full; the pointer bullets added to §2.3 (d) and §2.6.
- `W/tmp/tools/helly_check.py` and its run `helly_check-run-20261020.txt`. Run it with `python3 -I helly_check.py`; it takes about 5 minutes (exhaustive search).
- CON-0192 (register); EXT-072–075.
- Background: §2.3 (whole: the set-wise definition (d), pointwise minimality, the counterexamples), §2.6, §2.8 (three-valued applicability; §2.8.4a placement and precedence), §2.9 (thresholds, target 0), §2.0 and §2.2 (the rule as a formal object).

**Challenge by type (practice 5):** R1, R2 and R4, and each h-value in R3, are mathematical (countermodel or invalid step). The placement of each book example in R3's table is interpretive: check it against the cited section. R5 is a set of consequences: check them by counterexample.

**Questions to answer in particular:**
1. **R1:** is step 1 right, given §2.3 (d)'s pointwise minimality and effective applicability? Is "applicability plays no part" true? Is the UNKNOWN extension (three-valued applicability) sound? Does the converse need anything beyond realisability?
2. **R2:** is the product lemma correct as stated? Does it need factors to be non-empty, or the attributes independent?
3. **R3:**
   - Is each h correct: required value 2; forbidden value k (2 if Boolean or infinite); both max(2, k); allowed set k; interval 2 (check the proof); forbidden range n; linear d + 1 over the reals, 2^d over the integers; disjunctions up to |X|?
   - Is each book example placed in the right class, and cited to the right section? The OAuth2 pair; TLS; the target-0 design checks; the trace links (marked Int); §2.3 (a)'s linear arithmetic.
4. **R4:** is the proof of h ≤ |X| valid, and the tightness example right?
5. **R5:**
   - (a) the cost bound;
   - (b) the §2.6 claim that pair comparison suffices for h = 2 classes, with tests point by point;
   - (c) "h is a property of the admitted rule language, attribute by attribute", and the placement example (independent obligations versus precedence);
   - (d) the test an analyser can run.
6. **§9.14.4:** is this really the strongest counterexample? Can you find a stronger one, for example where R1 (a) fails?
7. **§9.14.5:** is the distinction from k-consistency stated correctly?
8. **The model:** does `helly_check.py` compute what the text says it computes? In particular, the Helly-number search (minimal families drawn from distinct members, with a cap) and check 3's report comparison.
9. **Prior work:**
   - Is the corpus read correctly: S1539 (MCS-2 as a three-member minimal set; the other four pairs), S0922, S0287?
   - Are the outside works used as benchmarks only, and is "established; the application is the book's own" a fair status?
   - Check outside works only through public bibliographic or publisher pages, in your own words. **No corpus or book text in any search.**

## 3. Verification marks (practice 6)

| Changed piece | Author | Read by the Professor | Reasoning checked by the Professor |
|---|---|---|---|
| §9.14 (all) | Professor | yes | yes |
| pointers in §2.3 (d), §2.6 | Professor | yes | yes |
| `tmp/tools/helly_check.py` | Professor | yes | yes (5 checks run) |
| CON-0192, EXT-072–075, index, outline | Professor | yes | yes |

No helper session wrote any part.

## 4. Corpus reading for this turn (practice 2, corpus first)

Five logged scans (read log rows "SCAN", 2026-10-20, "THEORY TURN 5"). Hit lines were checked for relevance. Read whole as topic-full: S1539, S0922, S0287 (logged with `close_source.py`). Sources not read are listed in §9.14.7, with reasons. Reviewers may open allowed corpus sources (sha256) to check the Src claims.

## 5. Quote check (entry 60)

`corpus_quote_check.py`: §9.14 2 found, 0 not found; §2.3, §2.6 unchanged against the baseline (no checked-form quotes).

## 6. Access

As in commission A:
- the working book, tools and history: allowed;
- the corpus: allowed list only, sha256 matching;
- **no Ch. 0–2 basis files** (a check needing one is marked U);
- never `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, or `docs/knowledgeos/chronological-read/*`;
- no corpus or book text in any search;
- edit nothing except your report.

## 7. Method and report

For each material claim:
- scope, definitions, assumptions;
- the derivation;
- the strongest counterexample (by type);
- a rival;
- the falsifier where testable;
- labels Src / Ded / Int / Hyp / Ext.

Severity is critical, high or normal (Rulebook §6; R5). Give a proof-step table for any derivation you judge adequate (R4), and an observable "resolved when" for every finding.

Write the report to `W/06-theory/manuscript/review/TURN5-CONSISTENCY/REPORT-B1.md` (Opus) or `REPORT-B2.md` (Sonnet). It contains a verdict table, a findings table (ID · finding · severity · evidence · correction · resolved when) and a coverage section. Final answer: a summary of at most 10 lines.
