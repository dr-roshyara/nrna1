# REVIEW — Protocol A (`PROTOCOL-A.md`, `capture_a.sh`)

Sources used: REVIEW-TASK.md, TASK.md, PROTOCOL-A.md, capture_a.sh, ENVIRONMENT.md, TARGET-FACTS.md, REFERENCE-r2-rank0-script.sh. I did not run anything. I read the script line by line. No hypothesis is scored here.

## Verdict: **FAIL**

The script is read-only, deterministic and neutral. Its matching, timestamps, absence handling and hashing are sound. The blocking problem is the path classes (§2, `pclass`, `capture_a.sh:48-56`). They decide which commit counts as EXECUTION-TRACE, and so they decide the headline result and the earliest trace. For a Laravel / Vue repository they are wrong in both directions:

- real application code can be classed as documentation, or never classed as code;
- governance and agent tooling can be classed as code.

The protocol is frozen in advance, so running it as written would fix a misleading result. **The script must not run until B1–B3 are fixed.**

---

## 1. Read-only safety — OK (one non-blocking hardening point)

Every git invocation:

| Line | Command |
|---|---|
| 19 | `git --no-pager for-each-ref --count=1` (repository preflight) |
| 61 | `git --version` |
| 65 | `git for-each-ref --format=…` |
| 73 | `git rev-list --stdin` |
| 78 | `git log --stdin --format=…%B…%(trailers:only,unfold)` |
| 85 | `git log --stdin … --name-only` |
| 92 | `git log --stdin … -p -U0` |
| 109 | `git log --no-walk=unsorted --stdin --date=format-local:…` |

- **Git subcommands used:** `for-each-ref`, `rev-list`, `log`, and `--version`.
- **Not used:** `fetch`, `config`, `update-ref`, `gc`, `show`, `rev-parse` or `cat-file`.
- `GIT_OPTIONAL_LOCKS=0`, `GIT_NO_LAZY_FETCH=1` and `GIT_TERMINAL_PROMPT=0` prevent index refreshes and promisor fetches.
- External diff and textconv are off (`--no-ext-diff --no-textconv`).
- Non-git writes are `mkdir -p OUT`, files inside OUT, and `rm -f OUT/.tmp_*`. Nothing else.
- The one gap: the script does not check that OUT lies outside the work tree or `.git`. The usage text only says it "should" (N1).

## 2. Determinism and reproducibility — OK

- `LC_ALL=C` and `TZ=UTC` are set.
- Every output is sorted with `sort` / `sort -u` under C collation. The order of the awk `for (h in np)` loop does not matter, because its output is re-sorted (line 139). The paths in `matched_paths` come from a sorted file. The order of `matched_in` is fixed by the `i, j` loop.
- **Config isolation:** `GIT_CONFIG_NOSYSTEM=1`, `GIT_CONFIG_GLOBAL=/dev/null` and `GIT_NO_REPLACE_OBJECTS=1` are set. The relevant keys are overridden with `-c`: `core.quotepath`, the `diff.*` prefix and relative settings, `diff.renames`, `log.showRoot`, `log.showSignature` and `color.ui`. Local repository config is still read (N2).
- **Self-path:** `SELF` is resolved with `realpath -e` at line 10, before anything else. The main shell never runs `cd`; the only `cd` is inside the `finish` subshell. The r2 defect is fixed.
- **Root vs subdirectory:** there is no pathspec, and `diff.relative=false` plus `--no-relative` are set. As a result, `--name-only` paths and `+++ b/` paths are relative to the repository root wherever the script is run from. `00_meta.tsv` holds no paths. The output from the root and from a subdirectory is identical.

## 3. Exact target matching — correct for the specified rules

After hyphen normalisation and space padding, `hit()` (lines 40-47) implements T1 and T2 exactly as PROTOCOL-A §1 states. The loop advances by one character after each rejected candidate, so a later valid occurrence on the same line is still found.

- **Over-matching:** WP-4C-10, WP-4C-1.2, WP-4C-2, WP-4C-1-2, WP-4C-1a, WP-4C and xWP-4C-1 are all rejected.
- **Under-matching:**
  - all 11 listed Unicode hyphens are normalised, including the soft hyphen;
  - the `wp4c1-` alias is matched case-insensitively with a left boundary;
  - paths are tested (`--name-only`, so deleted paths are included);
  - trailers are tested (`%(trailers:only,unfold)`, recorded separately and also as `message`);
  - in added lines, CRLF `\r` counts as a boundary.
- **Minor asymmetries:** T2 has no right boundary, so `wp4c1-2…` matches while `WP-4C-1-2` does not (N4). T1 accepts range or list forms such as `WP-4C-1..3` and `WP-4C-1/2` (N5).

See `matching_tests` in REVIEW.json for the full table.

## 4. Classification

**Determinism — OK.** C1 to C5 are mechanical first-match rules over recorded facts (`own()`, lines 115-119). The script matches the protocol table: C4 requires a message hit, no path or line hit, at least one changed path and no K path.

**Merges — OK.** A merge's paths and lines come from `--diff-merges=combined`. Only lines with `+` in every parent column count (`w` = number of parents; lines 93-99). Every merge is classed MERGE. `C1>Cn` is recorded for information only. Side-branch commits are scanned as ordinary commits, so they are never promoted through the merge.

**Path patterns — BLOCKING (B1–B3).** The class depends only on the file extension and on generic segment and basename words. It does not depend on where the file sits in the Laravel / Vue tree.

| Path (Laravel / Vue) | `pclass` | Should be |
|---|---|---|
| `app/Http/Controllers/X.php`, `routes/web.php`, `database/migrations/…php` | CODE | CODE ✓ |
| `tests/Feature/XTest.php`, `resources/js/x.spec.ts` | TEST | TEST ✓ |
| `resources/views/x.blade.php` | CODE | CODE ✓ |
| `resources/js/components/Foo.vue` | **OTHER** | CODE ✗ (B1) |
| `resources/js/Pages/Auth/Register.vue` | **DOC** (basename `register.`) | CODE ✗ (B1, B2) |
| `resources/views/auth/register.blade.php` | **DOC** | CODE ✗ (B2) |
| `app/Models/Plan.php`, `app/Models/Decision.php` | **DOC** | CODE ✗ (B2) |
| `app/Http/Controllers/Reports/…php`, `tests/Feature/Plans/…Test.php` | **DOC** (segment) | CODE / TEST ✗ (B2) |
| `docs/x.md`, `engineering/x.md`, `developer_guide/x.md`, `.claude/agents/x.md` | DOC | DOC ✓ (only because of the extension) |
| `.claude/hooks/x.sh` / `.py` / `.js`, `engineering/tools/x.py`, `developer_guide/examples/x.php` | **CODE** | DOC / governance ✗ (B3) |
| `.claude/settings.json`, `engineering/*.yaml` | OTHER | DOC / governance (B3) |

## 5. Timestamps — OK

- Author date (`%ad`) and committer date (`%cd`) go in separate columns, formatted as `format-local:%Y-%m-%dT%H:%M:%SZ` under `TZ=UTC`.
- Each row carries `time_label=RECORDING_ORDER`.
- The summary gives the earliest EXECUTION-TRACE by committer date and, separately, by author date. Each is labelled RECORDING_ORDER and ties are broken by sha (lines 148-149, 159-161).
- The M-TIME wording is present.

## 6. Absence semantics — OK

- No errors and no EXECUTION-TRACE gives `NOT-EVIDENCED-IN-CAPTURE`.
- Any unexpected exit code gives `UNDETERMINED (CAPTURE_ERROR)` and exit 1.
- Coverage is stated: `capture LOCAL_REF_SET`, `completeness_vs_host NOT SHOWN`, the ref counts by namespace and `commits_scanned`.
- The script never claims that no execution occurred.
- Limitation: some pipeline stages are not checked (N7), and a shallow clone is not detected (N8).

## 7. Provenance and hashing — OK

- `SHA256SUMS` covers every file in OUT except itself, including the error and stderr logs. The temporary files are removed first.
- `find | sort | xargs sha256sum` runs under `pipefail`.
- The script hash is taken from `SELF`, which was resolved before any `cd`, so it is the hash of the file actually invoked. It goes into `00_meta.tsv` with the git version and no paths.

## 8. Neutrality — OK

There is no hypothesis, falsifier, conformance judgement or score in either file. The result tokens are descriptive.

## 9. Identities — OK

- No `%an`, `%ae`, `%cn` or `%ce` fields are used, and no message text or line text is stored.
- Residual risk (N10): ref names and paths are stored verbatim and may contain personal handles. `00_stderr.log` could contain absolute paths.

---

## Blocking issues

- **B1 — `.vue` (and frontend code generally) is not P-CODE** (`capture_a.sh:54`, PROTOCOL-A §2).
  - In a Vue repository, single-file components are application code, but they are classed P-OTHER.
  - Vue work whose message names WP-4C-1 is classed MENTION-ONLY, which by definition means "changed files unrelated". If the lines themselves contain the target, it is classed UNCLASSIFIABLE.
  - It is never EXECUTION-TRACE. This can give a false `NOT-EVIDENCED-IN-CAPTURE` or a wrong earliest trace.
- **B2 — the generic P-DOC words override application and test code** (lines 50 and 52; P-DOC takes precedence over TEST and CODE).
  - The segments `plans`, `reports`, `notes`, `decisions`, `registers` and `evidence`, and the basenames `plan.*`, `register.*`, `decision.*` and `acceptance.*`, match ordinary Laravel files.
  - Examples: `app/Models/Plan.php`, `resources/views/auth/register.blade.php`, Breeze's `resources/js/Pages/Auth/Register.vue`, `app/Http/Controllers/Reports/…` and `tests/Feature/Plans/…`.
  - These become DOC, so real implementation commits are classed DOC-OR-GOVERNANCE.
  - The DOC rules need to be anchored to documentation roots (`docs/`, governance or register directories) rather than to any segment or basename anywhere in the tree.
- **B3 — governance and agent directories are not P-DOC, and code roots are not positively defined** (lines 50-56).
  - `.claude/`, `engineering/` and `developer_guide/` are recognised as documentation only through the `.md` extension.
  - A hook or script in them (for example `.claude/hooks/*.sh|py|js`, `engineering/tools/*.py`, `developer_guide/examples/*.php`) that names WP-4C-1 is classed P-CODE. With a message hit, that makes it EXECUTION-TRACE.
  - TARGET-FACTS says that rulings, plans and acceptance material are not execution traces, and the review task names these directories as documentation or governance.
  - The fix is to add them to P-DOC (or a governance class), and to restrict P-CODE / P-TEST to the application roots (`app/`, `routes/`, `database/`, `resources/`, `config/`, `tests/`, and so on). Anything outside those roots should be P-OTHER.

## Non-blocking issues

- **N1** — The script does not check that OUT lies outside the work tree and `.git`. The reference script checked this.
  - If OUT were under `.git/refs/…`, the script would create files there before `for-each-ref` (line 65) runs. That would corrupt the ref set it captures.
- **N2** — Local `.git/config`, including any `include.path`, is still read. `GIT_CONFIG_PARAMETERS` / `GIT_CONFIG_COUNT` and `GIT_CEILING_DIRECTORIES` are not unset.
  - `diff.algorithm` can change which lines `-U0` reports as added. `i18n.logOutputEncoding` can change the bytes of the message.
  - Fix: pin `--diff-algorithm=myers` and `-c i18n.logOutputEncoding=UTF-8`.
- **N3** — For a path that contains a space, git appends a TAB to the `+++ b/…` header. As a result, `p` (line 96) gets a trailing tab:
  - `pclass` returns OTHER;
  - `12_line_hits.tsv` gains an extra column, which shifts the class and rule fields that `classify` reads (lines 123-124).
  - Paths with quote or control characters stay C-quoted.
  - Fix: strip the trailing `\t`, or take the path from `--name-only` / `-z`.
- **N4** — T2 has no right boundary. `wp4c1-2…` matches, while the T1 equivalent `WP-4C-1-2` is excluded.
- **N5** — T1 accepts `WP-4C-1..3`, `WP-4C-1/2` and `WP-4C-1,2`. These are range or list forms. Only `-<digit>` and `.<digit>` are excluded.
- **N6** — EXECUTION-TRACE does not separate adding from removing:
  - a commit that only deletes code but names the target in its message counts, because deleted paths are listed;
  - so does `Revert "WP-4C-1 …"`;
  - so does a message like "WP-4C-2, follows WP-4C-1".
  - The reference script had a FIRST_WP_NOT_TARGET flag; this script has nothing equivalent.
- **N7** — Several stages do not record failures in `00_errors.tsv`:
  - the tips pipeline (line 68), the sort at line 74, and the path-hit awk (line 89);
  - lines 104-106 and 138-158;
  - the final `sort` in the pipelines at lines 78, 85 and 92 (`PIPESTATUS[2]`).
  - If one of these fails silently, absence could be asserted on incomplete data.
- **N8** — Shallow and partial clones, and grafts, are not detected or recorded. A missing blob fails safe as CAPTURE_ERROR, but a shallow boundary silently truncates the history.
- **N9** — `refs/stash`, if present, is walked. A stash index commit carries the HEAD subject (`index on X: <sha> <subject>`) and the staged diff, so it could become EXECUTION-TRACE with the stash's date. ENVIRONMENT lists no "other" refs, so this is probably inert here.
- **N10** — Ref names and paths are stored verbatim and may contain handles. `00_stderr.log` may contain absolute paths.
- **N11** — The JSON output is fragile:
  - `js()` does not escape control characters or non-UTF-8 bytes;
  - a `;` inside a path corrupts the `matched_paths` array.
- **N12** — In PROTOCOL-A, the C5 example "a P-DOC/P-OTHER mix" only holds when there is a path or line hit. A message-only hit with that mix is C4. The wording should be clarified.
- **N13** — The hyphen list leaves out U+2043, U+058A, U+1806 and U+2E3A/B. This is acceptable because the list is fixed in advance, but the omission should be stated.
- **N14** — Ref names are not searched, for example a branch `…/wp4c1-x`. This is consistent with the four places TASK specifies; merge messages still catch most such branches.
