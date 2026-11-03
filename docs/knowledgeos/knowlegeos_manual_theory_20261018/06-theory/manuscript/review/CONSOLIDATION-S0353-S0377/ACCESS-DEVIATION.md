# Consolidation S0353–S0377: reviewer access deviations (minor; recorded without a stop)

*2026-11-03. Under the Principal's rule of 2026-11-03 (item 6): a disclosed deviation that touches only lane book files or allowed corpus files already read, is not relied on by any finding, and whose checks were rerun or are not needed, is recorded here and the review continues; it is listed at the next consolidation and in the access record.*

| Reviewer | Deviation (the reviewer's own disclosure) | Touches | Relied on? | Rerun / needed? |
|---|---|---|---|---|
| B | `ls` on the copy's `chapter_09/` folder (output cut to three names) and on `TODO.md`, to learn file names | the frozen copy (lane book files) | no | not needed |
| E | (1) `ls` with glob patterns on wrong paths ("No such file"; nothing read); (2) `ls` of the copy's `tmp/tools` and `chapter_01/`; (3) `undefined_terms.py --help` run without `--root`, so the tool ran unscoped on the working book and printed its whole-book background list, not used; rerun with `--root <copy> --scope 08.82-08.89`; (4) `corpus_quote_check.py` reads `02-FILES.jsonl` internally (not opened by E); E used its own scratch quote script instead | the frozen copy; the working book's files (lane book files) through a tool's default root, read by the tool, output not used | no | R2 rerun on the copy |
| D | `undefined_terms.py --help` and `inbound_citations.py --help` run without `--root`: both tools ran on their default root, the working book, and printed whole-book lines; not used; every later call used `--root <copy>` | the working book's files (lane book files) through a tool's default root | no | not needed (later runs on the copy) |
