# Consolidation S0201–S0225: freeze record (Professor only)

*2026-10-29. The Professor. Not a test consolidation (S0182–S0200 was); reviewers may start once the briefs are committed; the Governance Engineer checks the briefs afterwards (template §1.2 step 4).*

- **The object** is `06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/BOOK-UNDER-REVIEW/`, copied by the Professor from the working book at the commit that adds this file: `06-theory/` top files (register, external sources, testimony, purpose, map), `06-theory/manuscript/` without `review/`, `BASELINE-REVISIONS.md` and `TODO-TRIAGE-S0100.md` (as in the previous copy's file set), and `tmp/tools/book_search.py` with its synonyms. Hashes of its files: `frozen-table.txt`.
- **The copy's files are never edited.** No comparison of the copy with the working book is made by any reviewer.
- **The working book stays open** for work outside the scope (the Principal's T9-17 decision, if it arrives, is applied to the working book and recorded here as a change after the freeze).
- **Before copying:** the map's window lines (C38–C45) and position, and the rotation table, were brought up to date (`cc9007904` and this commit). Two phrases naming the previous consolidation as a test were removed from the map and the outline (G2).
- **Not in scope, recorded:** the Ch. 10 lines changed by the Principal's T9-16 (b) (§10.2 refuted(k, t); §10.5.3 W row) are not window-settlement changes; Ch. 10 is not rotated. §9.18's T9-16 text is covered by the Ch. 9 rotation.
- **Reviewers:** A (Opus), B (Sonnet), C (Opus), then the integration reviewer (Opus); all `kos-lane-reviewer`.

## Access notes on the reviewers' runs (Professor only)
- **Reviewer A** disclosed that its first tool call ran `undefined_terms.py`, `register_home_check.py` and `inbound_citations.py` without `--root`, so the tools scanned the working lane (book files read by the tools, not opened by A); A states it discarded that output and used only runs on the copy. A deviation from REVIEWER-NOTE item 4 by tool default, disclosed; passed to the integration reviewer. *Template note for the Governance Engineer: the tools' default root is the working lane; a brief could require `--root` in the first call.*
- **Reviewer B** listed the file names of `review/` and `review/C38-S0201-S0204/` (names only; no file opened). Passed to the integration reviewer.
