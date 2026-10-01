# REVIEW — Protocol A r1 (targeted re-review)

Sources used: REVIEW-TASK.md, REVIEW-r0.md, CHANGELOG-A-r1.md, PROTOCOL-A.md (r1), capture_a.sh (r1). I did not run anything. I read the script line by line. The r0 script is not in this directory, so I could not diff it byte for byte. To check for regressions I compared the r1 script against the line-level description in REVIEW-r0. Line offsets match the changelog's stated edits: +1 for the two-line `unset`, then the N1 guard block, then +1 for the `g()` continuation line, then +1 for the N3 comment before pass 3. After that, every r0 line reference (`own()`, classify, summary, `finish`) lands on the same code at the expected offset.

## Verdict: **PASS_WITH_LIMITATIONS**

B1–B3 are fixed. `pclass()` (`capture_a.sh:80-87`) now checks the root first and the extension second, and it no longer consults any segment or basename word. N1–N3 are fixed. I found no regression in the areas I was asked to re-check. The only blocking issues in r0 were B1–B3, and nothing new is blocking. N4–N14 from r0 are still open, as the changelog says, and I note a few small new limitations below.

---

## 1. B1–B3: path classes (`capture_a.sh:79-87`, PROTOCOL-A §2)

The script does exactly what §2 says. It lower-cases the path and takes `rt` as the text before the first `/` (empty for a top-level file) and `b` as the basename. Then:
- R1: `rt` is one of `docs|.claude|engineering|developer_guide|claude` → DOC.
- R2: `rt == "tests"` → TEST if `src(b)`, else OTHER.
- R3: `rt` is one of `app|routes|database|resources|config|bootstrap|lang|public` → CODE if `src(b)`, else OTHER.
- R4: `b` ends in `.md` → DOC, else OTHER.

`src()` tests the final extension against S, so `.blade.php` is covered by `.php`. The rules use no other words.

| Path | root | Step | Class | Correct? |
|---|---|---|---|---|
| `app/Models/Plan.php` | app | R3 | CODE | ✓ (B2: `plan` no longer overrides) |
| `resources/views/auth/register.blade.php` | resources | R3 | CODE | ✓ (B2) |
| `resources/js/Pages/Auth/Register.vue` | resources | R3 | CODE | ✓ (B1 `.vue` ∈ S; B2) |
| `app/Http/Controllers/Reports/X.php` | app | R3 | CODE | ✓ (B2: `reports` segment ignored) |
| `tests/Feature/Plans/XTest.php` | tests | R2 | TEST | ✓ (B2) |
| `.claude/hooks/x.sh` | .claude | R1 | DOC | ✓ (B3) |
| `engineering/tools/x.py` | engineering | R1 | DOC | ✓ (B3) |
| `developer_guide/examples/x.php` | developer_guide | R1 | DOC | ✓ (B3: root beats `.php`) |
| `docs/plans/x.md` | docs | R1 | DOC | ✓ |
| `README.md` | (none) | R4 | DOC | ✓ (top-level documentation) |
| `composer.json` | (none) | R4 | OTHER | ✓ (by design: a manifest, not application logic; never EXECUTION-TRACE) |
| `package.json` | (none) | R4 | OTHER | ✓ (same) |
| `routes/web.php` | routes | R3 | CODE | ✓ |
| `database/migrations/x.php` | database | R3 | CODE | ✓ |

All 14 paths get the right class, and the protocol's examples table agrees with the script (I also checked `resources/js/README.md` → OTHER, `public/favicon.ico` → OTHER, `scripts/notes.md` → DOC, `.github/workflows/ci.yml` → OTHER).

The class tokens (`DOC`, `TEST`, `CODE`, `OTHER`) are the same ones the classify step reads (`capture_a.sh:153,155`), so C1–C5 still see the right K, all-DOC and kref facts.

- **B1 fixed:** `.vue` and the JS/TS/CSS family are in S, and they are CODE under `resources/`.
- **B2 fixed:** no generic segment or basename word appears anywhere in `pclass`.
- **B3 fixed:** the governance and agent roots are DOC whatever the extension, and CODE/TEST are now limited to positively listed roots. Anything else is OTHER, apart from `.md`.

## 2. N1–N3

- **N1 — fixed** (`capture_a.sh:22-46`).
  - The guard runs after the repository preflight and before `mkdir` (line 48), so a refused run creates nothing.
  - It walks up from `pwd -P` and checks:
    - every enclosing directory that has a `.git` entry (`under` also matches OUT equal to that directory);
    - the git directory that entry names: a directory or symlink via `realpath -m`, or the `gitdir:` target of a `.git` file, resolved against the directory that holds the file;
    - that git directory's `commondir`, resolved against GD, as git does.
  - OUT is resolved with `realpath -m`, so a symlinked OUT path is compared physically.
  - The guard adds no git subcommand; it uses only `realpath`, `read`, `dirname` and `pwd`.
  - The usage comment now says "must … enforced, exit 2".
  - Residual limitations are listed as nonblocking below.
- **N2 — fixed as scoped.**
  - `GIT_CONFIG_PARAMETERS`, `GIT_CONFIG_COUNT` and `GIT_CEILING_DIRECTORIES` are unset (lines 13-14).
  - `g()` pins `diff.algorithm=myers` and `i18n.logOutputEncoding=UTF-8` (line 57). `git log` honours `diff.algorithm`, and `-c` overrides local config.
  - The fact that local `.git/config` stays readable is disclosed in §7.
- **N3 — fixed** (line 128).
  - `sub(/\t$/, "", p)` runs before the `/dev/null` test, the `b/` strip and `pclass`. So paths with spaces get their real class, and `12_line_hits.tsv` is back to 4 columns.
  - The strip does nothing when no TAB is present, for example on combined-diff headers.
  - C-quoted paths are disclosed under R4.

## 3. Regression check

| Property | Status | Evidence |
|---|---|---|
| Read-only | OK | git subcommands are still only `--version`, `for-each-ref`, `rev-list` and `log`. Writes go only to OUT, plus `rm -f OUT/.tmp_*`. The new guard is read-only and makes OUT safer. |
| Deterministic | OK | `LC_ALL=C` and `TZ=UTC` are still set, every output is still sorted, and more config keys are now pinned. The guard affects only whether the run starts, never the content. |
| Target matching | Unchanged | `hit()` (lines 71-78), the hyphen list (line 70), the four places and the pass-3 `+`-column logic are the same as r0. The N3 strip touches only `p`, never the matched text. |
| M-TIME | Unchanged | Line 141 uses `format-local:%Y-%m-%dT%H:%M:%SZ`. Author and committer dates are in separate columns, each labelled RECORDING_ORDER (lines 166, 191-193). |
| Absence semantics | Unchanged | Lines 100-103 and 182-185: NOT-EVIDENCED-IN-CAPTURE, or UNDETERMINED (CAPTURE_ERROR) with exit 1. The coverage statement is still present. |
| Hashing | Unchanged | `finish` (lines 60-65) and the `SELF` resolution before any `cd` (line 10). |
| Neutrality | OK | Neither file has a hypothesis, falsifier, score or conformance judgement. |
| No identities | OK | No `%an/%ae/%cn/%ce` fields, and no message or line text is stored. |

Intended behaviour changes, all of them disclosed in CHANGELOG/§2, with none blocking:
- `*.spec.*` and `test_*` outside `tests/` are now CODE or OTHER rather than TEST. CODE and TEST are both K, so C2 is unaffected.
- `.sh`/`.py` and non-`.md` doc extensions outside the listed roots are now OTHER.

**No regressions found.**

## Blocking issues

None.

## Non-blocking issues

- **R1-N1 — `GIT_COMMON_DIR` is not unset.** Git honours it even without `GIT_DIR`, so a hostile environment could redirect refs and objects, and the N1 guard would not see it. §7's phrase "the other repository-location variables" overstates what is unset. The same applies to the legacy `GIT_GRAFT_FILE`. Fix: add both to the `unset` line.
- **R1-N2 — the N1 guard is not "exactly" git discovery.**
  - It only recognises `.git` entries. If the cwd is inside a bare repository, or inside a git directory with no enclosing `.git` entry, OUT is not protected.
  - Running from a linked worktree lets OUT sit inside the *main* work tree, though not inside `.git` or the commondir. That location is harmless for the capture.
  - This is out of scope for an ordinary working clone, but §7's wording should be softened.
- **R1-N3 — local config keys that are still unpinned can change the added-line set.** For example, `diff.submodule=diff` adds submodule content lines prefixed with `+`, and `.git/info/attributes` or `core.attributesFile` can mark files binary. This is covered by the general §7 disclosure; pinning `-c diff.submodule=short` would close the first case.
- **R1-N4 — consequences of the root-first rule worth stating.**
  - Top-level build configuration (`vite.config.js`, `tailwind.config.js`, `webpack.mix.js`) is OTHER.
  - Compiled assets committed under `public/` (`.js`/`.css`) are CODE.
  - `composer.json`/`package.json`-only commits with a message hit become MENTION-ONLY.
  - All of these are defensible, but they should be mentioned in §2.
- **N4–N14 from REVIEW-r0 are still open**, as the changelog says: T2 has no right boundary, T1 accepts range/list forms, removals and reverts can count as EXECUTION-TRACE, some pipeline stages are unchecked, shallow clones are not detected, `refs/stash`, verbatim ref names and paths, JSON escaping, the C5 wording, the hyphen-list omissions, and ref names are not searched.
