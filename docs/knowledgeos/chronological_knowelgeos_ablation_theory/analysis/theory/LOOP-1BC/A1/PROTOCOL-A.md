# PROTOCOL A — evidence capture for WP-4C-1 (fixed in advance; implemented by `capture_a.sh`) — r1

**Purpose (descriptive only).** Find out whether the local git history of the ordinary working clone contains WP-4C-1 execution work, and find the earliest reproducible trace that the system recorded. This protocol **captures evidence only**. It does not judge whether any work conformed to any norm, and it contains no hypothesis, falsifier or score.

## 1. Target
**Normalisation (before any test).** Each Unicode hyphen or dash is replaced by an ASCII `-`. The replaced characters are U+2010, U+2011, U+2012, U+2013, U+2014, U+2015, U+2212, U+FE58, U+FE63, U+FF0D and U+00AD (soft hyphen). The text is padded with one space on each side. Tests run byte-wise under `LC_ALL=C`.

| Rule | Matches (case-insensitive letters) | Left boundary | Right boundary |
|---|---|---|---|
| **T1** | `WP-4C-1` | previous char not `[0-9A-Za-z]` | next char not `[0-9A-Za-z]`, **and** the next two chars are not `-<digit>` or `.<digit>` |
| **T2** (alias) | `wp4c1-` | previous char not `[0-9A-Za-z]` | none (the hyphen is the boundary) |

`_`, `/`, `.`, spaces and punctuation count as boundaries. So `WP-4C-1.`, `(WP-4C-1)`, `x_wp4c1-evidence.md` and `WP-4C-1-acceptance` all match.

**Excluded near-matches:**
- `WP-4C-2`, `WP-4B`, `WP-4D`: these are sibling items, not the target.
- `WP-4C` with no `-1` after it: this is the parent item. It names a scope, not this item.
- `WP-4C-10`, `WP-4C-1a`: the identifier continues with an alphanumeric character, so it names a different item.
- `WP-4C-1.2`, `WP-4C-1-2`: these read as a sub-item or a range. They are a longer identifier, so the protocol does not treat them as WP-4C-1.
- `xWP-4C-1`, `swp4c1-`: there is no left boundary.
- Unattested spellings (`WP4C1`, `WP-4C1`, `wp_4c_1`, `wp4c1_`, `WP 4C 1`): neither the register nor the facts give these spellings. Matching them would add false positives without any stated basis.

**Where the protocol looks.** It searches every commit reachable from the captured refs, with no date or ancestry filter, in four places:
- `message`: the full raw message (`%B`).
- `trailer`: the parsed trailers (`%(trailers:only,unfold)`). A trailer hit is also a `message` hit.
- `path`: changed file paths (`--name-only`).
- `added_line`: added diff lines only (`-p -U0`; lines starting with `+`). The path of an added line is taken from the `+++ b/…` header. Git appends a TAB to that header when the path contains a space; **the trailing TAB is stripped before the path is classified** (r1, N3).

Renames are not detected (`--no-renames`), and external diff and textconv are off. A **merge's own changes** come from the combined diff (`--diff-merges=combined`): only paths that differ from *every* parent, and only lines added relative to *every* parent (all `+` columns). The side branch's changes are therefore never attributed to the merge. Side-branch commits are scanned separately, as ordinary commits.

## 2. Path classes and commit classification
Each changed path (repository-relative, as git prints it) is lower-cased. Its **root** is the text before the first `/`; a top-level file (no `/`) has no root.

**Precedence: ROOT first, THEN extension.** The root alone decides which step applies; the extension is consulted only inside that step. No directory-segment word and no basename word (plan, register, report, decision, acceptance, evidence, notes, …) is consulted anywhere, so a generic word can never override a CODE or TEST root (B2). The first step whose root matches assigns the class:

| Step | Root | Class |
|---|---|---|
| R1 | `docs`, `.claude`, `engineering`, `developer_guide`, `claude` (DOC-OR-GOVERNANCE roots) | **P-DOC**, whatever the extension (B3) |
| R2 | `tests` (TEST root) | **P-TEST** if the extension is in S; otherwise P-OTHER |
| R3 | `app`, `routes`, `database`, `resources`, `config`, `bootstrap`, `lang`, `public` (CODE roots) | **P-CODE** if the extension is in S; otherwise P-OTHER |
| R4 | any other root, or no root | **P-DOC** if the extension is `.md`; otherwise **P-OTHER** |

**Source extensions S:** `.php .vue .js .mjs .cjs .ts .tsx .jsx .css .scss .sass .less .json .sql`. `.blade.php` is covered by `.php` (the test is on the final extension).

Justification of S (Laravel / Vue repository):
- `.php`, `.blade.php`: controllers, models, routes, migrations, seeders, factories, config, translations and views are all PHP; Blade views are templates with logic.
- `.vue`, `.js`, `.mjs`, `.cjs`, `.ts`, `.tsx`, `.jsx`: Vue single-file components and front-end modules under `resources/` (B1). The module and JSX variants cost nothing and avoid a gap if the build uses them.
- `.css`, `.scss`, `.sass`, `.less`: application styles under `resources/` (and compiled into `public/`); changing them is implementation work on the UI.
- `.json`: translation files (`lang/*.json`) and front-end data or config inside the CODE roots. Top-level `package.json` / `composer.json` have no root and so stay P-OTHER.
- `.sql`: schema dumps and raw migrations under `database/`.
- Excluded on purpose: images, fonts, `.svg`, `.map`, `.txt`, `.yml`/`.yaml`, `.xml`, `.env*`, `.htaccess`, lockfiles, `.md` inside CODE/TEST roots, and `.sh`/`.py`. These carry no application logic in this stack, or (shell, Python) belong to tooling that lives outside the CODE roots. Such files under a CODE or TEST root are P-OTHER, which never counts as EXECUTION-TRACE.

Notes on the roots:
- `ROOTS.txt` lists `app bootstrap .claude claude config database developer_guide docs engineering public resources routes scripts storage tests`. `public/` is present, so it is a CODE root. `lang/` is absent from `ROOTS.txt`; the rule is kept as specified and is inert unless the directory appears in history.
- `scripts/`, `storage/`, `.github/` and every other root fall under R4: P-OTHER except `*.md`, which is P-DOC. The protocol does not interpret CI content.
- A path that git C-quotes (it contains `"`, `\` or a control character) starts with `"`, so it has no recognised root and falls under R4. This is disclosed, not repaired.

Examples:

| Path | Step | Class |
|---|---|---|
| `app/Models/Plan.php`, `app/Http/Controllers/Reports/X.php` | R3 | P-CODE |
| `resources/js/Pages/Auth/Register.vue`, `resources/views/auth/register.blade.php` | R3 | P-CODE |
| `tests/Feature/Plans/XTest.php` | R2 | P-TEST |
| `resources/js/README.md`, `public/favicon.ico` | R3 | P-OTHER |
| `.claude/hooks/x.sh`, `.claude/settings.json`, `engineering/tools/x.py`, `developer_guide/examples/x.php`, `docs/x.md` | R1 | P-DOC |
| `README.md`, `scripts/notes.md` | R4 | P-DOC |
| `scripts/x.sh`, `composer.json`, `.github/workflows/ci.yml` | R4 | P-OTHER |

"Code/test path" means P-TEST ∪ P-CODE.

**A matching commit** has at least one T1/T2 hit in at least one of the four places. Notation: K = its code/test paths; *msg* = a message or trailer hit; *kref* = a hit in a K path name or in a line added to a K path. The first rule that fires assigns the class:

| Rule | Condition | Class |
|---|---|---|
| C1 | ≥ 2 parents | **MERGE** |
| C2 | K ≠ ∅ and (*msg* or *kref*) | **EXECUTION-TRACE** |
| C3 | ≥ 1 changed path and all changed paths are P-DOC | **DOC-OR-GOVERNANCE** |
| C4 | *msg*, no path or added-line hit, ≥ 1 changed path (none of them K) | **MENTION-ONLY** |
| C5 | anything else (for example an empty commit, or hits only in P-OTHER, or a P-DOC/P-OTHER mix) | **UNCLASSIFIABLE** |

For a merge, C2–C5 are also applied to the merge's own changes and recorded as `rule_id = C1>Cn`. This note is for information only. A merge never counts as EXECUTION-TRACE.

## 3. Timestamps (rule M-TIME)
The protocol records the author date and the committer date **separately**, in UTC (`--date=format-local:%Y-%m-%dT%H:%M:%SZ`, `TZ=UTC`). Each is labelled **RECORDING_ORDER** only, because recording ≠ decision ≠ entry into force. No other timestamp source is available (no CI, no host data). Dates are copied as written by the client. They are not validated.

## 4. Coverage
- **Captured:** every ref printed by `git for-each-ref` (`01_refs.tsv`). Annotated tags are peeled to their commits; refs that point to non-commit objects are listed but not walked.
- **Scanned:** every commit reachable from those tips (`02_commits_scanned.txt`, with the count in the summary).
- **Out of scope:** hosted PR refs, server audit logs, push times and CI history.
- **Fixed statement:** "completeness vs the host NOT shown".

## 5. Absence
If no commit is classed EXECUTION-TRACE and the capture had no errors, the result is **`NOT-EVIDENCED-IN-CAPTURE`**. The protocol never concludes that no execution occurred. If any command exits unexpectedly (`00_errors.tsv` is non-empty), the result is `UNDETERMINED (CAPTURE_ERROR)` and absence is not asserted.

## 6. Outputs (in the directory passed as the argument)
- `00_meta.tsv`: script SHA-256 and git version.
- `00_errors.tsv`, `00_stderr.log`.
- `01_refs.tsv`, `01_tips.txt`, `02_commits_scanned.txt`.
- `10_message_hits.tsv`, `11_path_hits.tsv`, `12_line_hits.tsv`, `13_hitset.txt`, `14_changed_paths.tsv`: the raw hits and the path class of each changed path of the matching commits. No line text is stored.
- **`20_commits.tsv` / `20_commits.jsonl`**: one row per matching commit. Fields:
  - `sha`, `author_date_utc`, `committer_date_utc`, `class`;
  - `matched_in`: `source:rule` tokens, for example `message:T1`;
  - `matched_paths`: joined with `;`;
  - `rule_id`, `time_label=RECORDING_ORDER`.
- **`30_summary.tsv`**:
  - ref counts and commits scanned;
  - counts per class, all five shown, including zeros;
  - the earliest EXECUTION-TRACE by committer date and, separately, by author date (ties broken by sha), each labelled RECORDING_ORDER;
  - the result from §5 and the coverage statement.
- **`SHA256SUMS`**: the SHA-256 of every other output file.

The outputs contain no author or committer names and no email addresses.

## 7. Run guards and configuration isolation (r1)
- **Output location (N1).** The script refuses to start (exit 2, nothing created) if the output directory lies inside the work tree or inside `.git`. Without `rev-parse`, it walks up from the physical working directory as git discovery does, and refuses if OUT is inside any enclosing directory that holds a `.git` entry, inside the git directory that entry names (a `.git` directory or symlink, or the target of a `gitdir:` file), or inside that git directory's `commondir`. Because every enclosing work tree is checked, the guard is slightly stricter than "the" work tree; it never allows a location inside the captured repository.
- **Environment (N2).** `GIT_CONFIG_PARAMETERS`, `GIT_CONFIG_COUNT` and `GIT_CEILING_DIRECTORIES` are unset (as are `GIT_DIR`, `GIT_WORK_TREE` and the other repository-location variables). System and global config stay disabled (`GIT_CONFIG_NOSYSTEM=1`, `GIT_CONFIG_GLOBAL=/dev/null`).
- **Pinned keys (N2).** Every capture command runs with `-c diff.algorithm=myers` (fixes which lines `-U0` reports as added) and `-c i18n.logOutputEncoding=UTF-8` (fixes the bytes of messages), in addition to the keys already pinned (`core.quotepath`, the `diff.*` prefix and relative settings, `diff.renames`, `log.showRoot`, `log.showSignature`, `color.ui`).
- **Disclosed limitation (N2).** The repository-local `.git/config` (and any file it pulls in via `include.path`/`includeIf`, and `config.worktree`) **remains readable**. Keys not pinned above could still influence the output. The protocol does not inspect or record that file.
