# Note for the reviewers of the consolidation S0278–S0302

The book is reviewed as a **frozen copy**, so that every reviewer reads the same version while the working files stay open for other work.

1. **The book is `06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/BOOK-UNDER-REVIEW/` only.** Read its sections, map and register there.
2. **Book search:** `python3 -I <copy>/tmp/tools/book_search.py …` (it searches the copy).
3. **Mechanical checks, run on the copy.** Pass `--root <copy>` from your **first** call of every tool that takes it (the tools' default root is the working book). `corpus_quote_check.py` takes the copy's section files as arguments.
   - `python3 -I tmp/tools/register_home_check.py --root <copy>` (R1);
   - `python3 -I tmp/tools/undefined_terms.py --root <copy> --scope <sections in your commission>` (R2): **judge every candidate it lists for your sections, one by one**; the whole-book list is background. *(Until the tool has `--scope`, run it without and keep the lines whose first use lies in your sections.)*
   - `python3 -I tmp/tools/inbound_citations.py --root <copy> "<claim>"` (R3) for every correction you propose;
   - `python3 -I tmp/tools/corpus_quote_check.py <section files of the copy>` (quote check).
4. **Do not open:** the working chapter files (`06-theory/manuscript/chapter_*`), the working `THEORY-MAP.md`, `CONCEPT-REGISTER.tsv` and `BOOK-OUTLINE.md`, `BASELINE-REVISIONS.md`, old texts in review folders, anything in `00-access/`, or git history of the manuscript. **Do not compare files.**
5. **Corpus sources** are read as usual, from the allowed list only; sealed and hold-out files never.
6. **A reference to a claim that does not exist** (an index row, table row or citation naming a claim the section lacks) is checked against the window inventory and the sources **before** it is called an index error: is content missing, or is the pointer stale? Say which, with the evidence.
7. **At the end of your report, list every file you opened and every search and command you ran.** If you reached any file in item 4, say so. Without the list, the review cannot be shown to rest on the frozen copy.
