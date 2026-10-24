# Note for the reviewers of the consolidation S0131–S0150

The book is reviewed as a **frozen copy**, so that every reviewer reads the same version while the working files stay open for other work.

1. **The book is `06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW/` only.** Read its sections, map and register there.
2. **Book search:** `python3 -I 06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW/tmp/tools/book_search.py …` (it searches the copy).
3. **Mechanical checks, run on the copy:**
   - `python3 -I tmp/tools/register_home_check.py --root 06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW` (R1);
   - `python3 -I tmp/tools/undefined_terms.py --root …/BOOK-UNDER-REVIEW` (R2; candidates, judge each);
   - `python3 -I tmp/tools/inbound_citations.py --root …/BOOK-UNDER-REVIEW "<claim>"` (R3) for every correction you propose;
   - `python3 -I tmp/tools/corpus_quote_check.py <section files of the copy>` (quote check).
4. **Do not open:** the working chapter files (`06-theory/manuscript/chapter_*`), the working `THEORY-MAP.md`, `CONCEPT-REGISTER.tsv` and `BOOK-OUTLINE.md`, `BASELINE-REVISIONS.md`, old texts in review folders, or git history of the manuscript. **Do not compare files.**
5. **Corpus sources** are read as usual, from the allowed list only; sealed and hold-out files never.
6. **At the end of your report, list every file you opened and every search you ran.** If you reached any file in item 4, say so. Without the list, the review cannot be shown to rest on the frozen copy.
