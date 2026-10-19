# Amendment 32a: hybrid research method (topic branches), APPROVED

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-19 ("I approve the five fixes and the operating limits in principle"), with the instruction that the script and records **enforce** the rules, not merely document them |
| Builds on | register entry 32 (the BRANCH-01 trial, kept as the historical record) · Rulebook v1.3 |

## The rules

1. **Logging.**
   - Every branch consultation is a read-log row with depth `branch-consult` and the exact passages consulted.
   - Every content-wide search is a row with depth `scan`, giving its pattern, scope and limits.
   - **A branch consultation never advances the chronological reading position.**
2. **Branch review.**
   - Each branch closes with an independent transition review: the consulted passages, new or revised concepts, and changes to the theory map.
   - Branch-derived concepts stay **PROVISIONAL** until that review is recorded.
   - A directly evidenced defect may be recorded as INVALID before the review, with its evidence.
3. **Precision.** Claims are stated at the strength of their proof. (Applied first to Ch. 5 §5.4 C2.)
4. **Predictions before comparison.**
   - Before chronological reading reaches a branch-consulted source, the branch's predictions about the unread parts are recorded, fingerprinted and demonstrably earlier.
   - At comparison they are scored as supported, incorrect or undecided, and anything not predicted is scored as an omission.
   - A later synthesis does not count as independent confirmation of an earlier source.
5. **Search coverage.**
   - Branch search patterns are built from the register's and the book's terms (including E1–E10).
   - Each search states its terms, scope and limits.
   - Results are findings within that scope, not proof of absence.

## Operating limits

- A branch is opened only when an open TODO or question blocks understanding of the current chapter.
- One branch at a time.
- One bounded turn and about ten sources per branch.
- A closing note in `review/BRANCH-nn-…/`.
- The theory map shows two counts: sources read in order, and sources consulted in branches.

## Enforcement (in `tmp/tools/close_source.py` and `tmp/tools/branch_search.py`)

- `full` and `skim` are accepted **only** for the next source in index order after the reading position. The reading position counts only `full` and `skim` rows.
- `branch-consult` requires a passages argument and never moves the position.
- `scan` is written only by `branch_search.py --log`, with its pattern, scope (the allowed list), result count and limits.
- A concept whose origin is a branch cannot be VALIDATED while its branch has no recorded review. The tag check fails, and no read-log row is written.

## §32b (2026-10-19T09:41:23Z): snapshot of the theory map before a branch — APPROVED

**Principal (verbatim):** "yes" (to the proposal: "before a branch starts, the theory map is copied and fingerprinted, and the reviewer receives that copy as the 'before'; the script could enforce it by refusing a branch-consult row when no snapshot exists").

- **Opening a branch** is done with `tmp/tools/close_source.py --open-branch BRANCH-nn <slug>`. It creates `06-theory/manuscript/review/BRANCH-nn-<slug>/` and copies `06-theory/THEORY-MAP.md` to `THEORY-MAP-BEFORE.md` there, with its sha256 written in `THEORY-MAP-BEFORE.sha256`. It refuses if the branch already has a snapshot.
- **A `branch-consult` row is refused** when the branch has no snapshot, or when the snapshot no longer matches its recorded fingerprint.
- **The branch review's "before"** is that snapshot, not the current map.
- **BRANCH-01 is exempt:** it predates this rule and has no faithful snapshot, and none is reconstructed afterwards. Its review used the current map as "before", recorded as a process finding.
