# Targeted re-review of Protocol C r1 (`PROTOCOL-C.md`, `scope_c.sh`)

Basis: `REVIEW-TASK.md`, `REVIEW-r0.md`, `CHANGELOG-C-r1.md`, `PROTOCOL-C.md` and `scope_c.sh` (all 224 lines, read line by line). Nothing was run. Line numbers refer to `scope_c.sh` r1.

## Verdict: **PASS_WITH_LIMITATIONS**

B1, B2 and B3 are fixed. In every traced scenario, no non-200 response and no uncalled endpoint becomes absence (`NOT_EVIDENCED_ON_HOST`), and no derived field becomes `false` or `0`. rulesets is always called once the repository GET is 200. N1 and N3–N6 are fixed. N7 is fixed for the two items the changelog names. Its other two items are still open. There are no regressions in GET-only, the bound, the identity policy, determinism or M-TIME. I found five non-blocking issues. The most important is that the identity guard fails **open** if `grep` returns an error. This was already true in r0, which reported it wrongly as fail-closed.

---

## 1. B1–B3: scenario traces

**Assumptions.** Unless a scenario says otherwise:
- the repository GET and the commit GET are 200;
- every call not named in the scenario returns its own status;
- "own" below means that call's status goes through `lab`: 200 with ≥ 1 item gives `EXISTS`, 200 with 0 items gives `NOT_EVIDENCED_ON_HOST`, and a non-200 gives `NOT_ACCESSIBLE[…]`.

checks, status and runs are single calls, independent of the PR outcome, so they are "own" in every scenario. rulesets is called at L147 with no condition inside `scan`, so it is "own" in every scenario as well.

### (a) pulls association returns 403
- `ps=403`. `PRS` stays empty because `mapfile` runs only when `ps` is 200 (L87). `cap=NA`. The PR loop does not run. `prs.tsv` and `timeline.tsv` have headers only.
- **prs**: `crow prs 403 0`, so `NOT_ACCESSIBLE`, http `403`. The note is `http 403; n=NA; cap_reached=NA; pr_http=none`.
- **reviews** and **timeline**: the `ps != 200` branch (L119–121) gives `NOT_ACCESSIBLE[dependency_failed]`, http `-`. The note is `not called: PR association lookup http 403`.
- **protection** and **branch_rules**: L158 sets `s0=403` and `base=""`, so L160 gives `NOT_ACCESSIBLE[dependency_failed]`. The note is `not called: base branch not derivable (PR association lookup http 403)`. No `ch_protection.tsv` or `ch_branch_rules.tsv` is written, so there can be no `false` values.
- **rulesets**: called anyway, own.
- **checks, status, runs, events**: own.
- **Result: OK.**

### (b) pulls returns 200 `[]`
- `npr=0` and `cap=false`.
- **prs**: `crow prs 200 0`, so `NOT_EVIDENCED_ON_HOST`. This is correct: it is a 200 with 0 items. The note is `n=0; cap_reached=false; pr_http=none`.
- **reviews** and **timeline**: `NOT_APPLICABLE[no_associated_pr]` (L118). The note is `not called: host lists no associated PR`.
- **protection** and **branch_rules**: `NOT_APPLICABLE[no_associated_pr]` (L155). The note adds `does not show that the commit's branches are unprotected`.
- **rulesets**: called, own.
- Everything else: own.
- **Result: OK.** This is the only path to the no-PR branch, and it requires a 200 with an empty list.

### (c) pulls returns 200 with one PR; the PR GET returns 404
- `PRS=(n)`. `pr$n` returns 404. `rv$n` and `tl$n` are still called. `PA=""`.
- **prs.tsv row**: `pr_http=404`. Every PR-sourced field is `NA` via `g($ps; …)`: state, merged, base_ref, created_at, merged_at, merged_le_t, merged_in_window, the three relations, and `n_approvals_by_pr_author`, which is gated on both statuses. The review fields follow `reviews_http`, and `timeline_truncated` follows `timeline_http`.
- **prs**: `EXISTS`, http `200`, because the association list is a 200 with 1 item. The note is `n=1; cap_reached=false; pr_http=404`. This is not a negative. See NB-3 for a small wording gap.
- **reviews** and **timeline**: own, from `rv$n` and `tl$n`. If they are also 404, the result is `NOT_ACCESSIBLE`, the note leads with `http 404`, and the `timeline.tsv` row is all `NA`.
- **protection** and **branch_rules**: `s0=404` (L157) and `base` is `NA`, so L159–160 give `NOT_ACCESSIBLE[dependency_failed]`. The note is `not called: base branch not derivable (lowest PR GET http 404)`.
- **rulesets**: called, own.
- **Result: OK.**

### (d) protection returns 404 (curl mode)
This needs the pulls list and the lowest PR GET to be 200, with a non-empty `base.ref`.
- **protection**:
  - `kv protection 404` writes `http 404`.
  - `scope=CURRENT_STATE_ONLY` and `n_distinct_bases=<nb>` are kept. Both are constants or come from the PR rows.
  - These six keys are all `NA`: `required_pull_request_reviews`, `required_approving_review_count`, `enforce_admins`, `required_status_checks`, `required_signatures`, `push_restrictions`, `allow_force_pushes`.
  - Existence is `NOT_ACCESSIBLE`, because no protection special case is left (L50). The note is `http 404; current state only; …`. ts_generator is `NOT_DETERMINABLE`.
- **branch_rules**: called independently, own.
- **rulesets**: own.
- The rest: own. Coverage reads `UNAUTHENTICATED: …`.
- **Result: OK.** This fixes the r0 B3 example.

### (e) budget runs out before the events call
This cannot happen as configured: the most calls possible is 29 and `BUDGET=40`. The trace below sets that aside and assumes N = 40 when `ev1` is reached.
- `get ev1` makes no call. It writes status `budget` and `{}`, sets `HIT=1`, and the loop breaks. `es=budget` and `e1=budget`. `ev_all.json` is `[]`.
- `kv events budget` sets every derived value to `NA`: `n_returned`, `oldest_created_at`, `window_covered`, `n_in_window`, and the rest.
- **events**: `NOT_ACCESSIBLE[not_called_budget]`, http `budget`. The note is `not called: call budget reached; retention assumed …`. calls.tsv has no `ev` row.
- All earlier channels: own, since they were all called.
- `budget_hit=true`, and the script exits 4 with partial output.
- **Result: OK.** For a latent issue on the per-PR loop, see NB-2.

### (f) transport failure (000) on reviews
- curl's `%{http_code}` is `000`. In gh mode, an empty `.http` gives `s=""`, which becomes `000`. An absent or empty body is replaced by `{}`.
- **prs.tsv**: `reviews_http=000`. These fields are `NA`: n_reviews, n_distinct_reviewers, n_approvals, n_approvals_le_t, n_approvals_in_window, n_approvals_by_pr_author, n_approvals_by_commit_author and reviews_truncated. The PR fields follow `pr_http`.
- **reviews**: `agg 000`, so `NOT_ACCESSIBLE[transport]`, http `000`. The note is `transport failure (000); see prs.tsv`, with no `n=`.
- **timeline**, **protection** and **branch_rules**: own, because they depend only on the pulls list and the PR GET.
- **rulesets** and the rest: own.
- **Result: OK.**

### Answers to the three questions in item 1
- **Is any non-200 mapped to absence?** No:
  - `lab` produces `NOT_EVIDENCED_ON_HOST` only for status 200 (L49).
  - `NOT_APPLICABLE` is produced only for 200 `[]` on pulls.
  - `NOT_RETAINED` is produced only when `e1=200` (L186–187).
  - `200_unparsable` is treated as a non-200 (L42).
  - `agg ""` returns `000` (L47).
- **Is any derived field `false` or `0` instead of `NA`?** No:
  - every `ch_*` file goes through `kv` (L60–62);
  - `prs.tsv` gates each field on its own source (`g`);
  - commit-author relations are gated on the commit status (`eqc` and `cnt`);
  - `timeline.tsv` writes an `NA` row for a non-200 (L112);
  - `rulesets.tsv` rows are written only from a 200 (L150).
- **Is rulesets always called?** Yes: L147 has no condition inside `scan`. It is skipped, and labelled `[dependency_failed]`, only when the repository GET is not 200 and nothing is called. §2 and §4 say this.

## 2. N1, N3–N7

| Item | Status | Evidence |
|---|---|---|
| N1 | **Fixed** | L36 and L43–44 record the client clock and the `Date` header. L207–208 write `observation_time`, labelled `HOST_GENERATED` and `CLIENT_SUPPLIED`, with `NA` when a value is missing. `date` is a required tool. |
| N3 | **Fixed** | `why` (L53). `crow` puts it first in every non-200 note (L59), including the events row (L188). The fallback note names the cause: the repository GET status, or the budget (L199–200). |
| N4 | **Fixed** | L128 and L142 add `checks_truncated` and `runs_truncated`. L148 adds `rulesets_truncated` and L170 `branch_rules_truncated`. L128 also adds `ts_decision_scope=returned_page`, and the checks label reads "(returned page only)". status passes `per_page=100` (L135). |
| N5 | **Fixed** | The checks note is "by the host CI app", and the coverage strings name no product. I found no proper or product name among the fixed OUT strings. See NB-4 for the residual risk. |
| N6 | **Fixed** | L106–111 use the timestamp field of each event type. `committed` is labelled `CLIENT_SUPPLIED`. `n_no_timestamp` is counted separately. A group with no timestamps gets `NO_TIMESTAMP`, never `HOST_GENERATED`. |
| N7 | **Partly fixed** | `git` is required (L15), and OUT is created with a plain `mkdir` that exits 3 on failure (L218). This covers what the changelog lists. Two r0 N7 items remain: `grep -I` skips binary files, and stage writes other than `summary.json` are not checked. Neither is repaired, and neither is disclosed in §6. |

## 3. Regression checks

**GET only: OK.** The network is reached only through `get()`: `gh api -X GET -i` (L38) and `curl -sS -X GET` (L41). There is no `-f`/`-F`, no `--paginate` and no other `gh` subcommand. Every call:

| Key | Endpoint | When |
|---|---|---|
| probe_gh | `rate_limit` | gh present |
| probe_curl | `rate_limit` | curl mode |
| repo | `repos/REPO` | always |
| commit | `repos/REPO/commits/{sha}` | repo 200 |
| pulls | `repos/REPO/commits/{sha}/pulls?per_page=5` | repo 200 |
| pr{n} | `repos/REPO/pulls/{n}` | per PR, ≤ 5 |
| rv{n} | `repos/REPO/pulls/{n}/reviews?per_page=100` | per PR, ≤ 5 |
| tl{n} | `repos/REPO/issues/{n}/timeline?per_page=100` | per PR, ≤ 5 |
| checks | `repos/REPO/commits/{sha}/check-runs?per_page=100` | repo 200 |
| status | `repos/REPO/commits/{sha}/status?per_page=100` | repo 200 |
| runs | `repos/REPO/actions/runs?head_sha={sha}&per_page=100` | repo 200 |
| rsets | `repos/REPO/rulesets?per_page=100&includes_parents=true` | repo 200, always |
| prot | `repos/REPO/branches/{base}/protection` | base derivable |
| brules | `repos/REPO/rules/branches/{base}?per_page=100` | base derivable |
| ev{1..3} | `repos/REPO/events?per_page=100&page={p}` | repo 200; stops on a short page, a non-200 or reaching W's start |

**Bound: OK.**
- The commit is hard-coded (L8).
- PRs are capped twice, by `per_page=5` and `.[:5]`.
- There is one base branch, from `PRS[0]`, the lowest number.
- The window is a constant, used only as a filter.
- The budget check comes before `N++` (L35).
- The most calls possible is 2 + 1 + 1 + 1 + 15 + 3 + 1 + 2 + 3 = 29, which is ≤ 40.

**Identity policy: OK, with NB-1.**
- OUT gets only derived fields, and endpoints have `REPO` substituted.
- Raw `.json`, `.hdr` and `.http` files and `ids.txt` stay under `$TMP/raw`.
- Roles are held only in `CA` and `PA` and passed through jq `--arg` (N8, disclosed).
- The guard is unchanged:
  - On a match it fails closed: staged first, exit 5, nothing copied.
  - On a `grep` error it does **not** fail closed (NB-1).
- I found no fixed OUT string that would trip it. The JSON keys contain no `name`, the notes no `login` or `@`, and the checks key `n_app_github_actions` is not a whole-word or case match for `GitHub`.

**Determinism and local safety: OK, with NB-5.**
- `LC_ALL=C` and `TZ=UTC` are set.
- Every jq output is sorted (`sort`, `group_by`, `unique`, `sort_by(.id)`). The `sha256sum` glob runs under the C locale.
- Both `cd`s run in subshells.
- OUT and TMP get the work-tree refusal. OUT is refused if it already exists, and `mkdir` without `-p` closes the TOCTOU gap.
- `rm -rf` runs only after the basename check.
- `observation_time` is not deterministic, by design.

**M-TIME labels: OK.** Every timestamp field matches the §2 table:
- commit, `committed` events and `head_commit.timestamp` are `CLIENT_SUPPLIED`;
- checks are `HOST_GENERATED` only when every run on the returned page is by `github-actions`;
- protection and branch_rules are `NOT_DETERMINABLE`;
- the observation time is labelled.

**Neutrality: OK.**
- Norm state and independence are constants in `row()`.
- `*_le_t` and `updated_le_t` are recorded but not interpreted.
- Retention is "assumed (unchecked)".

## Blocking issues

None.

## Non-blocking issues

- **NB-1. A grep error makes the guard fail open (L216).**
  - **Defect:** `! grep …` turns grep's exit 2 (error) into success.
  - **Effect:** if `ids.txt` is missing (its write at L215 is unchecked) or `grep` fails on I/O, the ids check passes silently. With an error, grep returns 2 even when it also found a match.
  - **Likelihood:** it needs a local I/O failure. OUT is identity-free by construction, so the guard is only a backstop.
  - **Records:** §5 and the changelog say "fails closed", and r0 §3 claimed exit 2 counts as a failure. Neither is accurate.
  - **Fix:** test for exit status 1 explicitly, e.g. `grep …; [ $? -eq 1 ]`, and require `[ -s "$R/ids.txt" ]`.
- **NB-2. A budget hit in the per-PR loop can label reviews from a partial PR set (L113, latent).**
  - **Defect:** suppose `tl$k` is refused but `rv$k` succeeded. Then L113 breaks out of the loop. PRs after k are never called, but `rv` holds only 200s.
  - **Effect:** reviews can read `NOT_EVIDENCED_ON_HOST`, and `prs.tsv` drops rows with no note. This would be "never called → absence" again.
  - **Likelihood:** it cannot happen at 29 ≤ 40.
  - **Fix:** when `HIT=1`, append `budget` to `pst`/`rv`/`tl` for each skipped PR.
- **NB-3. The prs label shows only the pulls list status.** In scenario (c), prs is `EXISTS` with http `200`, and the PR GET's 404 appears only as `pr_http=` in the note and in `prs.tsv`. This is not a negative. But §2's aggregation sentence lists reviews, timeline and events, not prs, so the treatment should be stated.
- **NB-4. "Cannot be tripped" is overstated (§5, changelog N5).**
  - **Defect:** any raw login or git name of 3 or more characters that equals a whole word in OUT trips the guard. Examples:
    - a fixed note word such as `host` or `page`;
    - the base branch name;
    - a branch named `<login>-patch-1`, which matches because `-` is a word boundary.
  - **Effect:** none on safety. The guard fails safe, with exit 5.
  - **Fix:** reword to "contain no product or proper names".
- **NB-5. The work-tree test can pass a work tree when git errors (L23).**
  - **Defect:** `git rev-parse` exits 128 for a work tree it distrusts (`safe.directory`, "dubious ownership") or one hidden by `GIT_CEILING_DIRECTORIES`. `inside()` then treats the path as outside.
  - **Effect:** an OUT inside such a work tree would be accepted. The WORKTREE_DIR check still applies.
- Also open: the two remaining N7 items (`grep -I` and unchecked stage writes), which are not disclosed in §6. status has `per_page=100` and `total_count` but no `status_truncated` flag (minor).
