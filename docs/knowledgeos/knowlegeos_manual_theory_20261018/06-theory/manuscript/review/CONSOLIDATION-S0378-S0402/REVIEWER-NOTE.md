# Note for the reviewers of the consolidation S0378–S0402

The book is reviewed as a **frozen copy**, so that every reviewer reads the same version while the working files stay open for other work.

1. **The book is `06-theory/manuscript/review/CONSOLIDATION-S0378-S0402/BOOK-UNDER-REVIEW/` only.** Read its sections, map, register and summaries there.
2. **Working instructions in force:** `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md` (the index of the working instructions in force under Rulebook v1.4) and `03-methodology/REVIEWER-INSTRUCTIONS.md` may be read. Where the index differs from the Principal's decision record, the record wins; report any difference you notice, and do not resolve it.
3. **Book search:** `python3 -I <copy>/tmp/tools/book_search.py …` (it searches the copy).
4. **Mechanical checks, run on the copy.** Run the check tools from the lane's `tmp/tools/` (the copy holds only `book_search.py`), and pass `--root <copy>` from your **first** call of every tool that takes it (the tools' default root is the working book; a `--help` call without `--root` counts as a call on the working book).
   - `python3 -I tmp/tools/register_home_check.py --root <copy>` (R1);
   - `python3 -I tmp/tools/undefined_terms.py --root <copy> --no-corpus --scope <sections in your commission>` (R2): **judge every candidate it lists for your sections, one by one**;
   - `python3 -I tmp/tools/inbound_citations.py --root <copy> "<claim>"` (R3) for every correction you propose;
   - `python3 -I tmp/tools/corpus_quote_check.py <section files of the copy>` (quotation check). Reviewers do not open `02-FILES.jsonl`, but the tool may read it (index A5).
5. **Do not open:** the working chapter files (`06-theory/manuscript/chapter_*`), the working `THEORY-MAP.md`, `CONCEPT-REGISTER.tsv`, `BOOK-OUTLINE.md` and `SUMMARIES/`, `BASELINE-REVISIONS.md`, `02-FILES.jsonl`, old texts in review folders other than the inventories SCOPE §2.3 names, or anything in `00-access/`. **No git history, diffs, blame, historical versions, or commit listings of any file** (Principal ruling 2026-11-02: reading a file permits its approved content only; repository output naming files outside your list is not used to inspect, search or infer them). Do not run `diff`, `cmp` or similar tools on any files.
6. **Corpus sources** are read as usual, from the allowed list only (`/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`); sealed, hold-out and quarantine files never.
7. **A reference to a claim that does not exist** (an index row, table row or citation naming a claim the section lacks) is checked against the window inventory and the sources **before** it is called an index error: is content missing, or is the pointer stale? Say which, with the evidence.
8. **At the end of your report, list every file you opened and every search and command you ran.** If you reached any file in item 5, say so. Without the list, the review cannot be shown to rest on the frozen copy.
