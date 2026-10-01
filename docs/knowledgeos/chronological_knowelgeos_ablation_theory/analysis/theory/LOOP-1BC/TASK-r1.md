# Task (frozen): a targeted repair of Protocol A (r1)

Use ONLY the files here:
- `PROTOCOL-A.md`, `capture_a.sh`: the version to repair;
- `REVIEW-r0.md` / `.json`: its review, verdict FAIL;
- `ENVIRONMENT.md`, `TARGET-FACTS.md`;
- `ROOTS.txt`: the top-level directories that exist in the repository; metadata only.

No commands, no web.

Repair **only** the following, and change nothing else.

1. **B1, B2, B3 (blocking): classify paths by repository ROOT first, then by extension.**
   - **CODE roots:** app/, routes/, database/, resources/, config/, bootstrap/, lang/, public/ (if present), using source extensions **including .vue, .blade.php, .php, .js, .ts, .css, .scss, .json** (decide the exact set and justify it).
   - **TEST root:** tests/.
   - **DOC-OR-GOVERNANCE roots:** docs/, .claude/, engineering/, developer_guide/, claude/, plus any *.md outside the CODE / TEST roots. **Everything under the DOC-OR-GOVERNANCE roots is DOC-OR-GOVERNANCE regardless of extension** (B3).
   - **Generic words (plan, register, report, decision, acceptance, evidence …) must NOT override a CODE or TEST root** (B2).
   - Any other path is OTHER, which never counts as EXECUTION-TRACE.
   - State the precedence order explicitly: root, then extension.
2. **N1:** refuse to run (exit with an error) if the output directory lies inside the work tree or inside .git.
3. **N2:** isolate configuration:
   - unset GIT_CONFIG_PARAMETERS, GIT_CONFIG_COUNT and GIT_CEILING_DIRECTORIES;
   - pin diff.algorithm and i18n.logOutputEncoding via `-c`;
   - note that the repository-local .git/config remains readable (a disclosed limitation).
4. **N3:** strip the trailing TAB that git appends to `+++` header paths containing spaces, before classifying them.

Keep everything else (target matching, timestamps, absence semantics, hashing, neutrality, no identities) **unchanged**.

Write the repaired **PROTOCOL-A.md** and **capture_a.sh**, plus **CHANGELOG-A-r1.md** listing each change by defect id.
