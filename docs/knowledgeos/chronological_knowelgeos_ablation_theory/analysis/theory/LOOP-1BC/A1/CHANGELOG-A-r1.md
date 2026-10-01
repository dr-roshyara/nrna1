# CHANGELOG — Protocol A, r0 → r1 (targeted repair)

Scope: only B1, B2, B3, N1, N2 and N3 from REVIEW-r0. Target matching (T1/T2, normalisation), timestamps (M-TIME), coverage, absence semantics, C1–C5, outputs, hashing, neutrality and the no-identities rule are **unchanged**. N4–N14 are **not** addressed.

## B1, B2, B3 — path classes: root first, then extension
- **PROTOCOL-A §2** rewritten for path classes; `capture_a.sh` `pclass()` replaced, new helper `src()`.
- **Precedence stated:** root (first path segment) first, then extension. Steps R1–R4:
  - R1 DOC-OR-GOVERNANCE roots `docs .claude engineering developer_guide claude` → P-DOC regardless of extension (**B3**: `.claude/hooks/*.sh`, `engineering/tools/*.py`, `developer_guide/examples/*.php`, `.claude/settings.json` are now P-DOC, not P-CODE / P-OTHER).
  - R2 TEST root `tests` → P-TEST if the extension is in S, else P-OTHER.
  - R3 CODE roots `app routes database resources config bootstrap lang public` → P-CODE if the extension is in S, else P-OTHER.
  - R4 anything else → P-DOC if `*.md`, else P-OTHER (never EXECUTION-TRACE).
- **S** = `.php .vue .js .mjs .cjs .ts .tsx .jsx .css .scss .sass .less .json .sql` (`.blade.php` via `.php`); justification and deliberate exclusions written in §2. **B1:** `.vue` is now code.
- **B2:** all generic segment words (`plan(s)`, `report(s)`, `register(s)`, `decision(s)`, `evidence`, `notes`, `minutes`, `governance`, `ruling(s)`, `adr(s)`, `doc`) and basename prefixes (`register`, `plan`, `decision`, `acceptance`, `ruling`, `adr`) are removed. `app/Models/Plan.php`, `resources/js/Pages/Auth/Register.vue`, `resources/views/auth/register.blade.php`, `app/Http/Controllers/Reports/*.php` → P-CODE; `tests/Feature/Plans/*Test.php` → P-TEST.
- Consequences (from following the TASK rule set): the old extension-only P-CODE/P-TEST rules (e.g. `*.sh`, `*.py`, `test_*`, `*.spec.*` anywhere) and the old P-DOC extensions other than `.md` (`.rst .txt .adoc .org .pdf .doc(x) .odt .markdown`) no longer apply outside the listed roots; such files are P-OTHER. `.md` inside a CODE/TEST root is P-OTHER. `scripts/` and `storage/` are not CODE roots and so are R4. `lang/` is not in ROOTS.txt; the rule is kept and is inert.
- Disclosed (not repaired): C-quoted paths begin with `"` and so fall under R4.
- Added an examples table to §2.

## N1 — refuse an output directory inside the work tree or .git
- `capture_a.sh`: new guard after the repository preflight and **before `mkdir`**. Walks up from `pwd -P` (no `rev-parse`; git subcommands used are still only `--version`, `for-each-ref`, `rev-list`, `log`) and exits 2 if OUT is inside any enclosing directory holding `.git`, inside the git directory it names (directory, symlink, or `gitdir:` file), or inside that git directory's `commondir`.
- Usage comment updated from "should lie outside" to "must lie outside … enforced, exit 2".
- PROTOCOL-A new §7, first bullet.

## N2 — configuration isolation
- `capture_a.sh`: `GIT_CONFIG_PARAMETERS`, `GIT_CONFIG_COUNT`, `GIT_CEILING_DIRECTORIES` added to the `unset` line.
- `g()`: added `-c diff.algorithm=myers -c i18n.logOutputEncoding=UTF-8`.
- PROTOCOL-A §7: documents both, and discloses that the repository-local `.git/config` (with its includes and `config.worktree`) remains readable.

## N3 — trailing TAB on `+++` header paths
- `capture_a.sh` pass 3: `sub(/\t$/, "", p)` immediately after reading the `+++ ` header, before the `/dev/null` test, the `b/` strip and `pclass()`. Fixes the P-OTHER misclass and the extra column in `12_line_hits.tsv`.
- PROTOCOL-A §1, `added_line` bullet: states the stripping.

## Other edits
- Title marked `— r1`; one header comment in the awk prelude stating the precedence. No other textual or behavioural change.
