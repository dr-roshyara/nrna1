# Protocol C: bounded, read-only host-side scoping (1bf)

**Target.** Repository `REPO` = the `origin` of TARGET-FACTS; commit `e4724a44…3093`; t = 2026-08-04T00:30:25Z; window W = [2026-08-03T00:00Z, 2026-08-06T00:00Z). Scoping only: existence and metadata. No interpretation, and no evidence acquisition beyond derived counts. Script: `scope_c.sh OUT_DIR WORKTREE_DIR`.

## 1. Channels (GET only; `{r}` = `repos/REPO`)

| # | Channel | Endpoint | Why it is in the set |
|---|---|---|---|
| 0 | mode probe | `rate_limit` | Chooses gh or curl mode. Does not count against the host quota. |
| 1 | repository | `{r}` | Visibility decides which mode can reach anything else. Also gives owner type and the owner relation. |
| 2 | commit | `{r}/commits/{sha}` | Source of the host's mapping from git e-mail to account. Without it there are no role relations. |
| 3 | prs | `{r}/commits/{sha}/pulls?per_page=5`, then `{r}/pulls/{n}` | GitHub's own commit→PR association, plus `merged_at` and `merged_by` |
| 4 | reviews | `{r}/pulls/{n}/reviews?per_page=100` | Host-timestamped approvals, the nearest host analogue of an "authorization act" |
| 5 | timeline | `{r}/issues/{n}/timeline?per_page=100` | Review, merge, close, force-push and ready-for-review events |
| 6 | checks | `{r}/commits/{sha}/check-runs?per_page=100` | Records of CI execution on the commit |
| 7 | status | `{r}/commits/{sha}/status` | Combined legacy statuses |
| 8 | runs | `{r}/actions/runs?head_sha={sha}&per_page=100` | Actions runs; the host generates their timestamps |
| 9 | protection | `{r}/branches/{base}/protection` | Branch rules on the base branch |
| 10 | branch_rules | `{r}/rules/branches/{base}` | Active ruleset rules on the base branch |
| 11 | rulesets | `{r}/rulesets?includes_parents=true` | Ruleset `created_at`/`updated_at` |
| 12 | events | `{r}/events?per_page=100&page=1..3` | Repository activity in W |

**Left out:** search, other commits, logs and artifacts (that would be evidence acquisition, not scoping), audit log (organisation-only, needs admin), and everything under `docs/knowledgeos/` (R-SELF P-SELF; never a candidate channel). **Channels 9–11 return current state only. Historic state is not available from these endpoints**, so they cannot show what was in force at t. (Assumption to check, not called: a ruleset-history endpoint may exist. It is outside this frozen scope.) **Events retention (assumption to check, not fact):** the Events API is assumed to keep ≤ 90 days and ≤ 300 events. At about 58 days the window may be inside the 90 days but beyond the 300-event cap.

## 2. Rules per channel

**Existence**, the same for every channel:
- HTTP 200 with ≥ 1 relevant item → `EXISTS`.
- HTTP 200 with 0 items → `NOT_EVIDENCED_ON_HOST`.
- 401, 403, 404, other codes, transport failure, or not called → `NOT_ACCESSIBLE`, with the reason in `note`.

There are three special cases:
- **protection:** a 404 with message `Branch not protected` → `NOT_EVIDENCED_ON_HOST`.
- **No associated PR:** reviews, timeline and the base-branch channels → `NOT_EVIDENCED_ON_HOST`, with the note "not called".
- **events:** `NOT_RETAINED` when the returned list does not reach back before W's start (oldest `created_at` ≥ 2026-08-03) and nothing in W was returned. If items in W were returned but coverage is incomplete → `EXISTS`, with `window_covered=false`.

**Timestamp generator, per field:**

| Channel | HOST_GENERATED | CLIENT_SUPPLIED | NOT_DETERMINABLE |
|---|---|---|---|
| repository | `created_at` | | |
| commit | | author/committer dates | |
| prs | `created_at`, `merged_at`, `closed_at` | | |
| reviews | `submitted_at` | | |
| timeline | event `created_at` | `committed` events (git dates) | |
| checks | `started_at`/`completed_at` only if every run belongs to the `github-actions` app | | otherwise (app-written through the Checks API) |
| status | `created_at` (assigned when the status is posted) | | |
| runs | `created_at`, `run_started_at` | `head_commit.timestamp` | |
| rulesets | `created_at`, `updated_at` | | |
| protection, branch_rules | | | no timestamps at all |
| events | `created_at` | | |

**Norm state at t: `NOT_DETERMINABLE` for every channel.**
- A check-run or workflow run says that code was tested, not that the work was permitted.
- A review approves a merge of a diff, not the WP-4C-1 work. The derived `n_approvals_le_t` is only a pointer for the human.
- Protection, branch rules and rulesets are current-state only. Rules that govern merging are not the norm that permits the work.
- Rulesets `updated_le_t=true` would at most suggest the ruleset body is unchanged since before t. It is recorded, not interpreted.

**Independence:** `INDEPENDENCE_NOT_DETERMINABLE` by default, never a forced negative. A channel's writers (the host, apps, reviewers) are not classed as executors by construction. A host timestamp alone does not make a record independent.

## 3. Identity policy

- OUT contains no logins, names, e-mails, avatars, profile URLs, numeric user ids or the owner slug. The repository appears as `REPO` in endpoints, and as `repo_slug_sha256` in the summary.
- Roles are held only in shell memory and in the temp dir:
  - commit author's mapped account (`commit.author`);
  - PR author;
  - merger;
  - reviewers;
  - run actor and triggering actor;
  - status creator;
  - event actor;
  - repository owner.
- Output is equality relations only, as `true`/`false` or counts:
  - `pr_author_eq_commit_author`, `merger_eq_pr_author`, `merger_eq_commit_author`;
  - `n_distinct_reviewers`, `n_approvals_by_pr_author`, `n_approvals_by_commit_author`;
  - `author_eq_committer`, `owner_eq_commit_author`;
  - `n_actor_eq_commit_author`, `n_triggering_actor_eq_commit_author`, `n_creator_eq_commit_author`;
  - `n_in_window_actor_eq_commit_author`.
- If GitHub has no e-mail→login mapping for the commit (`author` is null), every relation involving the commit author is `NOT_DETERMINABLE` and `identity_mapping_available=false`.

## 4. Bound enforcement

- **Repository and commit:** hard-coded, so neither can be overridden.
- **PRs:** at most 5, the lowest numbers first. `cap_reached` is recorded.
- **Base branch:** one, taken from the lowest-numbered PR. `n_distinct_bases` is recorded.
- **Window:** a fixed constant, applied as a filter. Nothing outside it is interpreted.
- **Call budget:** ≤ 40, counted in `get()`. Each call is refused once N = 40, and the script exits 4 with partial output; skipped channels are marked "not called: call budget reached". The planned maximum is 29 calls: 2 probes, 1 repo, 1 commit, 1 PR list, 15 per-PR, 3 commit-CI, 3 base, 3 events.
- **Pagination:** one page everywhere, with `*_truncated` flags when 100 items come back. The only exception is events: up to 3 pages, and it stops as soon as a page is short or the window start is reached.
- **Methods:** GET only, via `gh api -X GET` or `curl -sS -X GET`. No mutating `gh` subcommand is used.

**Unauthenticated fallback:** this mode is used if `gh` is missing or its token is invalid, as it is now per ENVIRONMENT. It works only if the repository is public. Its declared coverage loss:
- the rate limit is 60/h;
- protection needs admin, so expect `NOT_ACCESSIBLE`;
- rulesets may be partly hidden.

The `summary.json` field `coverage` declares the mode. If the repository GET is not 200, no further calls are made and every channel is marked `NOT_ACCESSIBLE`.

**Paths:**
- The script resolves its own path before any `cd`.
- It refuses an OUT that already exists, that lies under WORKTREE_DIR, or that lies in any git work tree. It applies the same refusal to its temp dir.
- No `set -e`. Exit codes: 0 ok, 2 precondition, 3 refused path, 4 budget hit, 5 identity guard failed, 6 write failure.
- `LC_ALL=C`, `TZ=UTC`, deterministic sort.
- Raw responses live only in `mktemp -d …/scope_c.XXXXXXXX`. Removal (`rm -rf`) is limited to that directory, and its basename is checked first.

## 5. Output (OUT)

| File | Contents |
|---|---|
| `calls.tsv` | seq, channel, endpoint (with `REPO`), HTTP status, for every call |
| `channels.tsv` | One row per channel: http, existence, ts_generator, norm_state_at_t, independence, note |
| `ch_<channel>.tsv` | Derived key/value pairs |
| `prs.tsv`, `timeline.tsv`, `rulesets.tsv` | Derived rows, one per PR, event type or ruleset |
| `summary.json` | Mode, coverage, call count, budget, `budget_hit`, window, t, `script_sha256`, `repo_slug_sha256`, and `channels.tsv` as objects |
| `SHA256SUMS` | Hashes of all the files above |

**Identity guard (fail closed).** OUT is first built in the temp dir. Before anything is copied, that staged copy is grepped for:
- `@`, `login`, `avatar_url`, `html_url` and `name"`;
- every login, git author name and git committer name seen in the raw responses, plus the owner slug, matched as whole words.

Any hit → exit 5 and nothing is written to OUT. The guard runs again on OUT after the copy, followed by `sha256sum -c`.

**Next steps per PREREG:** review → FAIL repair loop → main-analyst safety check → human re-authenticates `gh` (or accepts the declared unauthenticated loss) → run → output review → seal → information-gain review → human gate.
