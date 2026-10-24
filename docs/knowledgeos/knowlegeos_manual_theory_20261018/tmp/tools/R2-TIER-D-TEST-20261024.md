# R2 tier D: test record (2026-10-24)

*The Principal's instruction: "undefined_terms.py also lists ordinary words used in a claim heading or a definition and defined nowhere in the book. Test it on "lane", "watermark" and "seal-line", report what it flags, and record the number of false positives on the working book." Rulebook v1.4 §2 step 8.*

## Rule implemented (`undefined_terms.py`, tier D)

- **Scope lines:**
  - a markdown heading;
  - a bold span of three or more words at line start (a claim or instance heading);
  - a bold term of one or two words at line start, with the rest of its line (a definition and its gloss).
- Spans led by an evidence or review label (Int, Src, Ded, Status, …) are skipped. Quotes, code, `[anchors]` and parentheses are removed first.
- **Word:** lower-case, at least 4 letters; hyphenated compounds count as one word.
- **Defined nowhere in the book:** none of the tool's existing definition forms, and not in a register term.
- **Kept if** the allowed corpus never uses the word (invented in the book), **or** the corpus marks it as a term (backticked or bold) at least twice. The threshold is the same as the tool's tier-C convention. *Without the corpus filter, the working book gives 1,094 candidates.*

## The three test words, each on the book it was found in

| Word | Test root | Flagged? | Row |
|---|---|---|---|
| seal-line | `review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW` (the S0150 copy) | **yes** | `D \| seal-line \| book 1 \| heads/defs 1 \| corpus files 0 marked 0 \| at 08.18 L180` |
| watermark | `review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW` (the S0100 copy) | **yes** | `D \| watermark \| book 2 \| heads/defs 1 \| corpus files 0 marked 0 \| at 09.08 L26` |
| lane | the book at the S0036–S0065 freeze (`adbe16c5c`, rebuilt with `git archive` in the scratchpad), with that review's three section copies laid over it | **yes** | `D \| lane \| book 24 \| heads/defs 3 \| corpus files 449 marked 3 \| at 01.14 L3; 02.00 L3; 08.06 L59` |

**Limit:** the filter was chosen with these three words in view, so catching them is not an independent validation. "lane" passes only through the marked-term branch (3 marks among 449 files), close to its threshold. The planted copies had tier D totals of 502 (S0150) and 455 (S0100); the S0036–S0065 rebuild had 420.

## False positives on the working book (base `6380afda0`)

- **Tier D candidates: 500.** Each was judged by the Professor on its word and the first heading or definition line it appears in.
- A candidate is a **true positive** when the word carries a technical meaning in that line that neither ordinary English nor the line supplies, and the book does not define it.

**True positives: 5.**

| Word | Location | Why |
|---|---|---|
| dependency-identifiable | §6.7 C5 head | a technical property, used undefined |
| evidence-to-the-leader | §8.20 C6 head | names CON-0254 (ii)'s placement rule without stating it |
| trust-weighted | §9.9 L5 (L61) | which acceptance is meant is not said |
| throw-away (rule) | §0.7 L13 | a named rule whose statement is not given under that name *(less certain)* |
| quiescence | §8.19 C2 head | a technical term; its body explains it by implication only *(less certain)* |

**False positives: 495 (99%).**
- About **15** are definitions the tool's forms miss: a term defined on the same line with the colon inside the bold (`**authority-body:**`, `**HP, hyperproperty:**`, `**once-only handling:**`), or defined in prose elsewhere (realisability §9.14, the trivial-solution test §5.7, must-never/must-do §9.1).
- The rest are ordinary words whose meaning in the line is the ordinary one ("despite", "ready", "clean", "connect").
- **Not counted as true:** compounds whose parts are book terms and whose meaning follows from them ("unhomed", "record-relative", "consumed-act", "high-grade").

**Load per section:** tier D rows appear in 98 sections, a median of 9 per section and a maximum of 46. For the newest sections: §8.18 14, §8.19 26, §8.20 42, §8.21 20.

## Assessment (facts and the Professor's judgement, labelled)

- *Fact:* the tool lists all three test words on their books.
- *Fact:* on the working book, 5 of 500 candidates are judged true; 495 are false.
- *Judgement:* tier D is a reading list, not a detector. Precision this low is useful only for the changed sections of a window (median about 9 rows each), where every row gets a disposition as v1.4 §2 step 8 requires. Book-wide it would not be read.
- *Not done:* a precision improvement (for example, treating `**term:**` as a definition form). It would change tiers A–C as well, so it is left for a decision. The five true positives are not corrected here; they go to their chapters' TODO at the next touch.
