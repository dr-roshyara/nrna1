# Protocol C: bounded, read-only host-side scoping (1bf), r2

**Target.** Repository `REPO` = the `origin` of TARGET-FACTS; commit `e4724a44…3093`; t = 2026-08-04T00:30:25Z; window W = [2026-08-03T00:00Z, 2026-08-06T00:00Z). Scoping only: existence and metadata. No interpretation, and no evidence acquisition beyond derived counts. Script: `scope_c.sh OUT_DIR WORKTREE_DIR`. r1 repaired the r0 review findings B1–B3, N1 and N3–N7 (see CHANGELOG-C-r1.md). r2 repairs SCOPE-1 (human-authorization compliance), NB-1 and NB-5 (see CHANGELOG-C-r2.md).

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
| 7 | status | `{r}/commits/{sha}/status?per_page=100` | Combined legacy statuses |
| 8 | runs | `{r}/actions/runs?head_sha={sha}&per_page=100` | Actions runs; the host generates their timestamps |
| 9 | protection | `{r}/branches/{base}/protection` | Branch rules on the base branch |
| 10 | branch_rules | `{r}/rules/branches/{base}?per_page=100` | Active ruleset rules on the base branch |
| 11 | rulesets | `{r}/rulesets?per_page=100&includes_parents=true` | Ruleset `created_at`/`updated_at`. **Repository-scoped: always called**, independent of the PR outcome. |
| 12 | events | `{r}/events?per_page=100&page=1..3` | Repository activity in W |

**Left out:** search, other commits, logs and artifacts (that would be evidence acquisition, not scoping), audit log (organisation-only, needs admin), and everything under `docs/knowledgeos/` (R-SELF P-SELF; never a candidate channel). **Channels 9–11 return current state only. Historic state is not available from these endpoints**, so they cannot show what was in force at t. (Assumption to check, not called: a ruleset-history endpoint may exist. It is outside this frozen scope.) **Events retention (assumption to check, not fact):** the Events API is assumed to keep ≤ 90 days and ≤ 300 events. At about 58 days the window may be inside the 90 days but beyond the 300-event cap.

**Signature and verification fields: out of scope by human decision.** The human declined signer verification (H2 = NO) and excluded signer investigation from H1 (HUMAN-SCOPE.md). No `verification`, `signature` or `gpg` field is read or output:
- not `commit.verification` (verified, reason, signature, payload) on the commit or on timeline `committed` events; `signature_verified` and `verification_reason` are removed from `ch_commit.tsv`;
- not protection's `required_signatures`;
- branch_rules: rule types matching `signature`, `verif` or `gpg` are left out of the `types` tally. They still count in `n_active_rules`, so existence is not understated.

The host still returns such fields inside the commit and timeline bodies. They stay unread in the temp dir and are removed with it. The identity guard's harvest (§5) walks every raw body but extracts only `login` and a `name` that sits next to an `email`.

## 2. Rules per channel

**One status-gating rule, the same for every channel.**

*Derived fields.* Every derived field (each `ch_<channel>.tsv` value, each `prs.tsv`/`timeline.tsv`/`rulesets.tsv` field, every count or relation) is computed **only from an HTTP 200 response**. For any non-200, every derived field of that channel is **`NA`**, never `false` or `0`. Each derived file carries the source status (`http` key in `ch_*.tsv`; `pr_http`, `reviews_http`, `timeline_http` in `prs.tsv`; `http` in `timeline.tsv`). A 200 whose body is not valid JSON is recorded as `200_unparsable` and treated as a non-200.

*Existence label* (PREREG mapping):
- HTTP 200 with ≥ 1 relevant item → `EXISTS`.
- HTTP 200 with 0 items → `NOT_EVIDENCED_ON_HOST`.
- 401, 403, 404, and 422 where the endpoint documents it (`commit` only; assumption to check) → `NOT_ACCESSIBLE`, or `NOT_RETAINED` where retention applies (events only; see below).
- Transport failure (`000`) or any other code → `NOT_ACCESSIBLE[transport]`.
- Call refused by the budget → `NOT_ACCESSIBLE[not_called_budget]`.
- Where a channel aggregates several calls (reviews and timeline over PRs, events over pages), the first non-200 status decides the label.
- The `note` of every non-200 row leads with the actual cause: `call budget reached`, `transport failure (000)`, or `http <code>`.

*Dependencies.*
- **A channel that was never called is never `NOT_EVIDENCED_ON_HOST`.**
- The no-PR branch is taken **only** on a 200 from `commits/{sha}/pulls` with an empty list. Then reviews, timeline, protection and branch_rules are not called and are reported `NOT_APPLICABLE[no_associated_pr]`. **This does not show that the commit's branches are unprotected**: the commit sits on branches (TARGET-FACTS) whose protection is simply not queried without a PR-derived base.
- A failed association lookup (pulls not 200) makes every dependent channel (reviews, timeline, protection, branch_rules) `NOT_ACCESSIBLE[dependency_failed]`; a budget refusal there gives `NOT_ACCESSIBLE[not_called_budget]`.
- The base branch comes only from a 200 on the lowest-numbered PR. If that GET is not 200 (or yields no `base.ref`), protection and branch_rules are `NOT_ACCESSIBLE[dependency_failed]`.
- If the repository GET is not 200, nothing further is called and every other channel is `NOT_ACCESSIBLE[dependency_failed]`.
- rulesets does not depend on any of these and is always called.
- protection has no special case: a 404 (whatever its message) is `NOT_ACCESSIBLE`, like every other 404.

*Events retention:* `NOT_RETAINED` only on a 200 for every page called, when the returned list does not reach back before W's start (oldest `created_at` ≥ 2026-08-03) and nothing in W was returned. If items in W were returned but coverage is incomplete → `EXISTS`, with `window_covered=false`.

**Timestamp generator, per field:**

| Channel | HOST_GENERATED | CLIENT_SUPPLIED | NOT_DETERMINABLE |
|---|---|---|---|
| repository | `created_at` | | |
| commit | | author/committer dates | |
| prs | `created_at`, `merged_at`, `closed_at` | | |
| reviews | `submitted_at` | | |
| timeline | event `created_at` (`submitted_at` for `reviewed`) | `committed` events (git dates) | |
| checks | `started_at`/`completed_at` only if every run **on the returned page** belongs to the host's own CI app (`app.slug` `github-actions`) | | otherwise (app-written through the Checks API) |
| status | `created_at` (assigned when the status is posted) | | |
| runs | `created_at`, `run_started_at` | `head_commit.timestamp` | |
| rulesets | `created_at`, `updated_at` | | |
| protection, branch_rules | | | no timestamps at all |
| events | `created_at` | | |

- The checks decision covers **the returned page only** (`ts_decision_scope=returned_page`); runs beyond it are not seen, which `checks_truncated` flags.
- Timeline events with no timestamp field (e.g. `line-commented`) are counted separately as `NO_TIMESTAMP` (`n_no_timestamp`) and are never labelled `HOST_GENERATED`.

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
- **Call budget:** ≤ 40, counted in `get()`. Each call is refused once N = 40, and the script exits 4 with partial output; refused channels are labelled `NOT_ACCESSIBLE[not_called_budget]` with the note "not called: call budget reached". The planned maximum is 29 calls: 2 probes, 1 repo, 1 commit, 1 PR list, 15 per-PR, 3 commit-CI, 3 base (rulesets included, always called), 3 events.
- **Pagination:** one page everywhere, with `*_truncated` flags: `reviews_truncated`, `timeline_truncated`, `checks_truncated`, `runs_truncated` (100 items returned, or `total_count` above the returned count), `rulesets_truncated`, `branch_rules_truncated` (100 items returned). status passes `per_page=100`. The only exception is events: up to 3 pages, and it stops as soon as a page is short, not 200, or the window start is reached.
- **Methods:** GET only, via `gh api -X GET` or `curl -sS -X GET`. No mutating `gh` subcommand is used.

**Unauthenticated fallback:** this mode is used if `gh` is missing or its token is invalid, as it is now per ENVIRONMENT. It works only if the repository is public. Its declared coverage loss:
- the rate limit is 60/h;
- protection needs admin, so expect `NOT_ACCESSIBLE`;
- rulesets may be partly hidden.

The `summary.json` field `coverage` declares the loss and `mode` the transport. If the repository GET is not 200, no further calls are made (see §2, dependencies).

**Paths:**
- The script resolves its own path before any `cd`.
- It refuses to run if `git` (or any other required tool) is absent, so the work-tree refusal can never be skipped.
- It refuses an OUT that already exists, that lies under WORKTREE_DIR, or that lies in any git work tree. It applies the same refusal to its temp dir.
- The work-tree test fails closed: `git rev-parse` exit 0 means inside; a failure whose message is "not a git repository" means outside; **any other `git rev-parse` failure** (e.g. a distrusted `safe.directory` work tree, an unreadable directory) **is treated as inside**, so the path is refused (exit 3). A work tree that git itself reports as "not a git repository" (hidden by `GIT_CEILING_DIRECTORIES` or a filesystem boundary) still reads as outside; the WORKTREE_DIR check applies regardless.
- OUT is created with plain `mkdir` (no `-p`) after that check and after the staged guard; if `mkdir` fails (OUT appeared meanwhile, or its parent is missing) the script exits 3.
- No `set -e`. Exit codes: 0 ok, 2 precondition, 3 refused path, 4 budget hit, 5 identity guard failed, 6 write failure.
- `LC_ALL=C`, `TZ=UTC`, deterministic sort.
- Raw responses and headers live only in `mktemp -d …/scope_c.XXXXXXXX`. Removal (`rm -rf`) is limited to that directory, and its basename is checked first.

## 5. Output (OUT)

| File | Contents |
|---|---|
| `calls.tsv` | seq, channel, endpoint (with `REPO`), HTTP status, for every call made |
| `channels.tsv` | One row per channel: http, existence, ts_generator, norm_state_at_t, independence, note |
| `ch_<channel>.tsv` | `http` plus derived key/value pairs (`NA` unless 200) |
| `prs.tsv`, `timeline.tsv`, `rulesets.tsv` | Derived rows, one per PR, PR × event type or ruleset; PR and timeline rows carry their source statuses; `timeline.tsv` has `n`, `n_timestamped`, `n_in_window`, `n_no_timestamp`, `ts_generator` |
| `summary.json` | Mode, coverage, call count, budget, `budget_hit`, window, t, `script_sha256`, `repo_slug_sha256`, `observation_time`, and `channels.tsv` as objects |
| `SHA256SUMS` | Hashes of all the files above |

**Observation time** (`summary.json` → `observation_time`), so that the current-state channels are anchored:
- `host_date_header.first/last`: the `Date` response header of the first and last response that carried one, labelled `HOST_GENERATED`;
- `client_clock_utc.first/last`: the local clock (UTC) at the first and last call, labelled `CLIENT_SUPPLIED`.
- Either is `NA` if unavailable.

**Identity guard (fail closed).** OUT is first built in the temp dir. Before anything is copied, that staged copy is grepped for:
- `@`, `login`, `avatar_url`, `html_url` and `name"`;
- every login, git author name and git committer name seen in the raw responses, plus the owner slug, matched as whole words (the list `ids.txt`, kept in the temp dir).

The guard passes **only** if `ids.txt` exists and is non-empty (it always holds at least the owner slug) and **both** greps exit with status 1 (no match). Explicit exit-status handling replaces the r1 `! grep` idiom:
- a hit (status 0) → exit 5;
- any grep error (status ≥ 2, even if a match was also found) → exit 5;
- `ids.txt` not written (its write status is checked), missing or empty → exit 5.

On exit 5 nothing is written to OUT. The guard runs again on OUT after the copy, followed by `sha256sum -c`. The script's own fixed notes and coverage text contain no product or proper names, so the guard cannot be tripped by them (e.g. by a web-flow committer name); see NB-4 in §6.

## 6. Disclosed limitations

- **N2:** `per_page=5` returns the host's first 5 associated PRs, which are then sorted, so "lowest numbers first" holds only within that page; `cap_reached=true` flags a possible gap.
- **N8:** role logins pass through jq `--arg` (visible locally in `/proc/*/cmdline`) and jq stderr may quote raw values; neither reaches OUT, so "only in shell memory and temp dir" is not strictly true locally.
- **N9:** `repo_slug_sha256` is an unsalted hash of a known slug: an integrity tag, not anonymisation.
- **N10:** in unauthenticated mode a rulesets 200 `[]` may reflect hidden entries and would read `NOT_EVIDENCED_ON_HOST`; this is covered only by the declared coverage loss.
- **NB-2:** a budget hit inside the per-PR loop leaves later PRs uncalled without adding `budget` to the aggregated statuses, so reviews could be labelled from a partial PR set; latent, since the maximum of 29 calls is below the budget of 40.
- **NB-3:** the prs label and http reflect only the association list (`commits/{sha}/pulls`); a failed `pulls/{n}` GET shows only as `pr_http` in the note and in `prs.tsv`, not in the prs label.
- **NB-4:** "cannot be tripped" (§5) is overstated: any raw login or git name of ≥ 3 characters that equals a whole word in OUT (a note word, the base branch name, `<login>-patch-1`) trips the guard, which then fails safe with exit 5.
- **NB-6:** `grep -I` skips binary files, and stage writes other than `summary.json` are not status-checked (r0 N7 remainder).
- **NB-7:** status passes `per_page=100` and records `total_count` but has no `status_truncated` flag.

**Next steps per PREREG:** review → FAIL repair loop → main-analyst safety check → human re-authenticates `gh` (or accepts the declared unauthenticated loss) → run → output review → seal → information-gain review → human gate.
