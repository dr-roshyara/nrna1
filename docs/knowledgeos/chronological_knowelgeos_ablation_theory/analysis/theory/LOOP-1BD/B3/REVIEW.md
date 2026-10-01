# Targeted re-review — Protocol B r3 (PROTOCOL-B.md + scope_b.sh, frozen)

**Verdict: PASS_WITH_LIMITATIONS.** No blocking issue was found.
- **BLK-2 is fixed.** The leading boundary `(^|[/._-])` is now in all three token regexes. The E-exclusion globs were widened to the matching infix forms, and the two sets are equivalent at the pattern level. The rulings ADR is now K-NORMREG.
- **NB-11 is fixed.**
- **NB-12 is declared** in §6.
- **No regression** was found in B1–B5, BLK-1, read-only safety, determinism, config isolation, self-path, OUT refusal, the identity guard, boundedness, the knowledgeos exclusion or neutrality.
- **One new non-blocking precision item** (NB-13, part of the NB-12 family) is raised below.

Sources: REVIEW-TASK.md, REVIEW-r2.md, CHANGELOG-B-r3.md, PROTOCOL-B.md, and scope_b.sh (181 lines, read line by line). Nothing was executed.

---

## 1. BLK-2 path tests

q is the lower-cased path.
- **Register regex** (l.77): `(^|[/._-])(rulings?|register|registry|decisions?|norm|inforce|authoriz(ation|ations)?)([._/-]|$)`, plus a doc extension.
- **Status regex** (l.78): the `else` branch, plus a doc extension.
- **Deploy regex** (l.79): no extension restriction, or `tfstate`.
- **E roots** (l.85): `app routes database resources config bootstrap lang public tests`.

| # | Path | Match | Channel | Correct | E-consistent |
|---|---|---|---|---|---|
| 1 | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` | register: `-rulings.`, ext md. No deploy token. | **K-NORMREG** | yes (the BLK-2 target) | yes. Glob `**/*[._-]rulings.md` matches. `engineering/` is not an E root anyway. |
| 2 | `docs/implementation/PROGRAM_STATUS.md` | register: no token. status: `/program_status.` (also `_status.`). | **K-STATUS** | yes | yes. Globs `**/program_status.md` and `**/*[._-]status.md`. Not an E root. |
| 3 | `docs/approvals/x.md` | status: `/approvals/` | **K-STATUS** | yes | yes. Glob `**/approvals/**/*.md`. Not an E root. |
| 4 | `register.md` | register: `^register.` | **K-NORMREG** | yes | yes. In git glob magic, a leading `**/` also matches top level, so `**/register.md` matches. |
| 5 | `docs/decision-log.md` | register: `/decision-` | **K-NORMREG** | yes (a decision log is a register by vocabulary). It was already K-NORMREG in r2. | yes. Glob `**/decision[._-]*.md`. Not an E root. |
| 6 | `app/Models/Registration.php` | `.php` is not doc-like, so neither register nor status applies. No deploy token. | **none** (executor path) | yes | yes. No doc glob matches (they all end in a doc ext), and no deploy glob matches. It stays in E. |
| 7 | `config/statuses.php` | not doc-like. No deploy token. (`statuses` would also fail the trailing boundary.) | **none** (executor path) | yes | yes. It stays in E. |
| 8 | `resources/js/normalize.js` | not doc-like. `norm` is followed by `a`, so the trailing boundary fails anyway. No deploy token. | **none** (executor path) | yes | yes. It stays in E. |
| 9 | `docs/release-notes.md` | deploy: `/release-` | **K-DEPLOY** | yes by the declared vocabulary. This is semantically loose (NB-12 lists `pre-release.md`). It was already K-DEPLOY in r2. | yes. Glob `**/release[._-]*`. Not an E root. |
| 10 | `deploy/prod.yml` | deploy: `^deploy/`. No doc token. | **K-DEPLOY** | yes | yes. Glob `**/deploy/**`. Not an E root. |

### Regex ↔ glob equivalence, checked case by case (l.77–79 against l.86–93)
- **Leading boundary.**
  - `^` or `/` maps to `**/`. Git's leading `**/` and inner `/**/` match zero or more directories.
  - `.`, `_` or `-` maps to `**/*[._-]`. `*` does not cross `/`, so the boundary character is in the same segment as the token.
- **Trailing boundary for doc tokens.** There are four cases:
  - the extension dot: `$t.$x`;
  - an inner `.`, `_` or `-` in the basename: `$t[._-]*.$x`. This also covers `status.old.md`.
  - `/`: `$t/**/*.$x`;
  - an inner `.`, `_` or `-` in a directory: `$t[._-]*/**/*.$x`.
- **Path-end case.** For doc tokens, `$` cannot occur because no token is a doc extension. For K-DEPLOY, the bare `**/$t` and `**/*[._-]$t` forms cover it.
  - Example: `.env` matches `**/*[._-]env` with `*` empty. The regex matches the same path via `[.]env$`.
- **Doc extension.** The awk `e` is the basename's last extension. Every doc glob ends in `.$x` at the end of the basename, so the two agree. An edge case such as basename `.md` matches on both sides.
- **Token list.** The E token list (l.88–89) is the union of the register and status tokens: 20 tokens, with `authoriz`, `authorization` and `authorizations` written out. The deploy list (l.92) equals the regex list. `**/*.tfstate` matches `e=="tfstate"`.
- **Case folding.** `icase` matches `tolower` under `LC_ALL=C`.
- **Bash quoting.** `"**/$t[._-]*.$x"` is quoted, and `$t[` without braces is not an array subscript, so the glob reaches git literally.
- **Counts.** 20 × 10 × 8 = 1600 doc globs and 7 × 8 = 56 deploy globs, as CHANGELOG states. About 70 KB of argv is far below ARG_MAX.
- **Result.** I found no tip path that is classified as a candidate but is not excluded from E, and none that is excluded but not classified. The remaining asymmetry is by design and declared: the regex runs on tip paths and the globs on historic paths.

### Repository-specific paths: **none added.**
- No ADR, `engineering/` or file-specific pattern appears in the script or in §1. `ADR-0007-Rulings.md` in §1 is a generic example.
- The ROOTS list (Protocol A r1) and `docs/knowledgeos/` were there before and are declared.

---

## 2. NB-11 and NB-12

| Item | Status | Evidence |
|---|---|---|
| NB-11 | **FIXED** | See below. |
| NB-12 | **DECLARED** | §6 NB-12 gives examples, and I re-traced each one: `config/decisions.yml` → K-NORMREG; `lang/*/status.json` → K-STATUS; `http_status.json` → K-STATUS; `x-norm.md` → K-NORMREG; `.env` and `pre-release.md` → K-DEPLOY. It also says that the BLK-2 infix widens the imprecision. |

NB-11 evidence:
- l.13–14 unset the four `GIT_*_PATHSPECS` variables and nine named `GIT_TRACE*` variables.
- l.15 runs `compgen -v GIT_TRACE` and unsets every remaining prefixed variable. With no match, it produces no output, and there is no `set -e` to trip.
- All of this happens before the `git()` wrapper (l.23) and the first git call (l.29).
- Trace2 config keys are honoured only from system/global config, and both are disabled (l.9). §5 documents the change.

---

## 3. Reachability

The Q1–Q8 logic (l.139–163) is byte-for-byte unchanged in substance from r2, so the REVIEW-r2 §3 table still holds:

| Q | Reachable outcomes |
|---|---|
| Q1 | `YES_IN_CAPTURE`, `NOT_EVIDENCED_AT_TIPS` (path channels, K-SIG); `YES_LOCAL_ONLY`, `NOT_EVIDENCED_LOCALLY` (K-LOCALHOOK); `NOT_CHECKABLE_LOCALLY` |
| Q2 | `CONFIG_RULE`, `NORM_REGISTER_RECORD`, `STATUS_RECORD`, `STATE_RECORD_BY_TYPE`, `IDENTITY_ATTESTATION`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q3 | `CONTROLLED_BY_WRITERS[n=k]`; `ND(keyring not consulted)`; `LOCAL_MACHINE`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q4 | `NO_CLIENT_SET(…)`; `NONE` (K-LOCALHOOK, N13); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q5 | `ND`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` (single substantive outcome by design) |
| Q6 | `ND[COMPLETE_VS_HOST=NOT_SHOWN]`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` (single outcome by design) |
| Q7 | **K-STATUS and K-DEPLOY:** `IND[no_norm_source…]`, `IND[empty_set…]`, `NOT_INDEPENDENT[overlap_norm=…]`, `NOT_INDEPENDENT[overlap_exec=…]`, `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`. **K-NORMREG:** `SELF_ATTESTED[…]` (definitional, declared). **K-CI, K-HOOK, K-CODEOWNERS, K-SIG, K-LOCALHOOK:** `IND`. Also `NOT_APPLICABLE(Q1)` and `NOT_CHECKABLE_LOCALLY`. |
| Q8 | `YES_LOCAL(…)`; `NO_LOCAL_ONLY`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |

**BLK-2 effect on reachability.**
- The rulings ADR is now in `npaths` (l.99), so for K-STATUS `nnp ≥ 1`.
- `IND[no_norm_source_distinct_from_candidate]` is therefore no longer forced by the file name. On this repository the K-STATUS positive (rules 4–6) can be reached.
- For K-DEPLOY, `comm -23` (l.112) removes only K-NORMREG paths that are also K-DEPLOY paths. The ADR path is not one of them.
- The false Q1 `NOT_EVIDENCED_AT_TIPS` for K-NORMREG no longer follows from the file name.

**Headline: computed from the data.**
- `q7n` (l.167–168) reads column 8 of the local rows. Host rows never match.
- The order is I → D → X → A → none (l.170–172), and all five tokens can be reached, as in REVIEW-r2.
- `SELF_ATTESTED` stays in its own bucket.

**Forced negatives: none.**
- `NOT_INDEPENDENT` needs an observed shared author (`on>0` or `oe>0`).
- `SELF_ATTESTED` is definitional and is excluded from the negative bucket.
- The BLK-2 construction-driven `NOT_EVIDENCED_AT_TIPS` is gone.

---

## 4. Regression check

| Item | Result |
|---|---|
| Read-only safety | **OK.** The only new commands are the builtins `unset` and `compgen`. Every other command is unchanged: `realpath`, `sha256sum`, `cut`, `ls -A`, `mktemp -d`, `rm -rf $TMP` (l.38 and the trap), `mkdir -p $OUT`, one `cd`, `mv` within TMP, text tools, and `find`/`xargs sha256sum` inside OUT. The git calls are `rev-parse`, `config --get`, `--version`, `for-each-ref`, `ls-tree`, `log`, `rev-list` and `cat-file`, all read-only. Loop git calls have stdin redirected. |
| Determinism | **OK.** `LC_ALL=C TZ=UTC`, one C-sorted tip file, sorted sets, a fixed channel order and sorted SHA256SUMS. The NB-11 residual is removed. |
| Config isolation | **OK.** System/global config is off. The injected-config variables are unset, plus `GIT_COMMON_DIR` and `GIT_GRAFT_FILE=/dev/null`. The eight `-c` overrides are on every call. Repo-local config is readable, and this is declared. |
| B1 | **Intact.** The tip set (l.55–60) is unchanged. |
| B2 | **Intact.** `comm -23 npaths cp` (l.112). |
| B3/B4 | **Intact.** E uses the roots, `%ae`, `--no-merges --full-history`, every channel glob excluded, and `docs/knowledgeos/**` excluded (l.94–96). |
| B5 | **Intact.** Q5 = `ND` on every evidenced row. |
| BLK-1 | **Intact.** The `if`/`else` keeps K-NORMREG and K-STATUS disjoint (l.77–78). `npaths` comes from K-NORMREG only (l.99). `SELF_ATTESTED` is decided by definition (l.145) and has its own 4th bucket (l.171). |
| Self path before `cd` | **OK.** l.19–21 resolve and hash it. The only `cd` is at l.41. If the path does not resolve, the script fails closed with exit 2. |
| OUT refusal | **OK** (unchanged, l.27–40). |
| No identities / guard fails closed | **OK.** Output holds counts, 16-hex ids, labels and the git version. git stderr goes to `$TMP/e`. The guard (l.177–180) maps rc 1 → PASS, rc 0 → FAIL(n) and rc ≥ 2 → FAIL(grep_rc). Both FAILs exit 1. |
| Boundedness | **OK.** Only commit/tag headers are read, never blobs. The E pathspec list doubled in size, which is a runtime cost only, as disclosed (NB-8 family). |
| `docs/knowledgeos/` exclusion | **OK.** l.71 (`next`), the E pathspec on l.95, and through `cand` also `npaths` and W. |
| Neutrality | **OK.** No new labels. The definitional outcome is declared. |

---

## Blocking

None.

## Non-blocking (new)

- **NB-13 (precision; NB-12 family; widened by the r3 infix): non-register data files in K-NORMREG feed N_c, so overlap_norm can attribute developer authors to the norm-maker role.**
  - Since BLK-1/BLK-2, every K-NORMREG path is a norm source for K-STATUS and K-DEPLOY. Examples are `config/decisions.yml`, `database/seeders/x-norm.json` and `lang/en/authorization.json`.
  - When such a file is a developer-written data file, its authors enter N_c. K-STATUS or K-DEPLOY can then show `NOT_INDEPENDENT[overlap_norm=…]`. The overlap is observed, so this is not a forced negative, but the role label "norm" is wrong.
  - For the same reason, those code-root data files are excluded from E, so pure editors of them are not counted as executors.
  - A single such data file inside a channel also merges into the whole channel's W_c.
  - §6 NB-12 states the classification imprecision and the `SELF_ATTESTED` label consequence, but not the N_c / E consequence.
  - *Suggested:* add a half-sentence to §6 NB-12.
- The r1/r2 limitations (N7, N8, N10, N11, N13, N15, NB-4 to NB-9) are still disclosed in §6. They were not re-examined, as that is out of scope.
