# Task (frozen): a targeted repair of Protocol B (r3)

Use ONLY the files here:
- `PROTOCOL-B.md`, `scope_b.sh`, `CHANGELOG-B-r2.md`: the r2 version to repair;
- `REVIEW-r2.md` / `.json`: its review, verdict FAIL with one blocking issue (BLK-2);
- `QUESTIONS.md`, `ENVIRONMENT.md`, `ROOTS.txt`.

No commands, no web. Repair **only** the following; change nothing else.

1. **BLK-2 (blocking): token boundaries on both sides, for every vocabulary token.**
   - In both the K-NORMREG and the K-STATUS regex (and the K-DEPLOY regex, for consistency), replace the leading `(^|\/)` with `(^|[/._-])`. A token may then start a path segment **or** follow `.`, `_` or `-` inside a name (e.g. `ADR-AIP-LOG-Platform-Rulings.md`, `PROGRAM_STATUS.md`). Keep the trailing boundary `([._/-]|$)`.
   - **Do not add any repository-specific path or file name.** The rule must stay general.
   - Widen the E exclusion globs to match exactly, adding the infix forms per token t and extension x: `**/*[._-]$t.$x`, `**/*[._-]$t[._-]*.$x`, `**/*[._-]$t/**/*.$x`, `**/*[._-]$t[._-]*/**/*.$x`; the K-DEPLOY tokens likewise.
   - State in PROTOCOL-B §1 that the glob set and the regex are equivalent by construction. If any residual mismatch remains, name it as a disclosed limitation.
2. **NB-11:** unset GIT_LITERAL_PATHSPECS, GIT_ICASE_PATHSPECS, GIT_GLOB_PATHSPECS and GIT_NOGLOB_PATHSPECS, and every GIT_TRACE* / GIT_TRACE2* variable, including GIT_TRACE, GIT_TRACE2, GIT_TRACE2_EVENT, GIT_TRACE2_PERF, GIT_TRACE_PACKET, GIT_TRACE_PERFORMANCE, GIT_TRACE_SETUP, GIT_TRACE_CURL and GIT_TRACE_PACK_ACCESS.
3. **NB-12:** declare it as a one-line disclosed limitation in §6. The broader boundary of item 1 widens this imprecision; say so.

Keep everything else unchanged.

Write the repaired **PROTOCOL-B.md** and **scope_b.sh** (≤ ~185 lines), plus **CHANGELOG-B-r3.md** listing each change by defect id.
