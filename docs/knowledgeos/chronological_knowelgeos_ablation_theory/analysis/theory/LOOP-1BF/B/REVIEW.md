# Review of Protocol C (`PROTOCOL-C.md`, `scope_c.sh`)

Basis: `REVIEW-TASK.md`, `TASK.md`, `PROTOCOL-C.md` and `scope_c.sh` (read line by line). Nothing was run.

## Verdict: **FAIL**

There are three blocking issues, all under item 5 (forced negatives). Each one turns a non-200 response, or a call that was never made, into a negative-looking result in OUT. The most likely trigger is the expected run mode: `gh` is unauthenticated per ENVIRONMENT, so the script falls back to curl at 60 calls/h. In that mode, protection is *expected* to be NOT_ACCESSIBLE, and running twice in an hour returns 403s. Items 1–4, 6 and 7 pass, with some non-blocking limitations.

---

## 1. Read-only on the host: OK

There are two transport lines, and both are inside `get()`:
- L38: `gh api -X GET -i`
- L40: `curl -sS -X GET`

`get()` is the only path to the network. Every call goes through it:

| Line | Key | Method | Endpoint |
|---|---|---|---|
| 173 | probe_gh | GET (gh) | `rate_limit` |
| 174 | probe_curl | GET (curl) | `rate_limit` (only if curl mode) |
| 177 | repo | GET | `repos/REPO` |
| 62 | commit | GET | `repos/REPO/commits/{sha}` |
| 71 | pulls | GET | `repos/REPO/commits/{sha}/pulls?per_page=5` |
| 78 | pr{n} | GET | `repos/REPO/pulls/{n}` (≤5×) |
| 79 | rv{n} | GET | `repos/REPO/pulls/{n}/reviews?per_page=100` (≤5×) |
| 80 | tl{n} | GET | `repos/REPO/issues/{n}/timeline?per_page=100` (≤5×) |
| 105 | checks | GET | `repos/REPO/commits/{sha}/check-runs?per_page=100` |
| 115 | status | GET | `repos/REPO/commits/{sha}/status` |
| 121 | runs | GET | `repos/REPO/actions/runs?head_sha={sha}&per_page=100` |
| 135 | prot | GET | `repos/REPO/branches/{base}/protection` |
| 144 | brules | GET | `repos/REPO/rules/branches/{base}?per_page=100` |
| 147 | rsets | GET | `repos/REPO/rulesets?per_page=100&includes_parents=true` |
| 156 | ev{p} | GET | `repos/REPO/events?per_page=100&page={1..3}` |

- No method other than GET is used.
- No `-f`/`-F` body is sent.
- No `--paginate` is used.
- No other `gh` subcommand is called.
- No token is read, printed or written:
  - curl sends no auth header.
  - `gh api -i` captures only *response* headers, and those go to `$TMP/raw`.
  - stderr of both tools goes to `/dev/null`.

## 2. Bound: OK

- Repository and commit are hard-coded (L8).
- PRs are capped twice: by `per_page=5` and by `.[:5]` (L71–72).
- There is one base branch (L129).
- The window is a constant, used only as a filter.
- Reviews and timeline fetch one page each.
- Events fetch at most 3 pages, with three stop rules (L155–159).
- There is no search.

The worst case is 2 + 1 + 1 + 1 + 15 + 3 + 3 + 3 = **29** calls, under BUDGET = 40. The budget check at L35 is a hard refusal before `N++`. No path can exceed the bound. The budget-hit path (exit 4) cannot be reached in practice.

## 3. Identity: OK

- **What reaches OUT.** Only derived fields: counts, booleans, `NOT_DETERMINABLE`, host and git timestamps, PR numbers, `base_ref`, event and type tallies, ruleset ids, target and enforcement, and endpoints with `REPO` substituted (L43). Logins, names, e-mails, avatars and URLs are never selected into the staged files. Role logins (`CA`, `PA`) live only in shell variables and jq `--arg`.
- **Raw responses** are written only under `$TMP/raw` (L29, L38–42), inside a `mktemp -d` directory with umask 077. `ids.txt` is also kept in TMP.
- **The guard fails closed.**
  - `! grep …`: a grep error (exit 2) also counts as a guard failure.
  - The fixed patterns, and every login, plus every name found in any object that has an `email` key, plus the owner slug, are matched as whole words against the *staged* directory before any copy (L193–196). The guard runs again on OUT (L198), followed by `sha256sum -c` (L199).
  - A guard hit exits 5 and nothing is written.

## 4. Determinism and local safety: OK

- `LC_ALL=C` and `TZ=UTC` are exported first (L7).
- Sorting is explicit: jq `sort`/`group_by`/`unique`/`sort_by(.id)`, and `sort -u`.
- `SELF` is resolved at L6. The script never `cd`s in the main shell; both `cd`s are in subshells (L190, L199).
- OUT is refused if it exists, lies under WORKTREE_DIR, or lies in any git work tree (L23–24). TMP gets the same check (L28).
- `rm -rf` runs only on `$TMP`, and only after its basename matches `scope_c.????????` (L26).
- There is no `set -e`. Exit codes are 2, 3, 4, 5, 6 and 0, all explicit.

## 5. Reachability / no forced negative: **FAIL**

How each existence value can be produced, per channel, as the script stands:

| Channel | EXISTS | NOT_EVIDENCED_ON_HOST | NOT_ACCESSIBLE | NOT_RETAINED |
|---|---|---|---|---|
| repository | yes | no (by design: a 200 means it exists) | yes | n/a |
| commit | yes | no (non-200 → NA, conservative) | yes | n/a |
| prs | yes | yes | yes | n/a |
| reviews | yes | yes, **also wrongly** (B1) | yes | n/a |
| timeline | yes | yes, **also wrongly** (B1) | yes | n/a |
| checks | yes | yes | yes | n/a |
| status | yes | yes | yes | n/a |
| runs | yes | yes | yes | n/a |
| protection | yes | yes (404 "Branch not protected"); **also wrongly** (B1, B2) | yes | n/a |
| branch_rules | yes | yes; **also wrongly** (B1, B2) | yes | n/a |
| rulesets | yes | yes; **also wrongly** (B1, B2) | yes | n/a |
| events | yes | yes | yes | yes |

**Norm state and independence** are hard-coded to `NOT_DETERMINABLE` and `INDEPENDENCE_NOT_DETERMINABLE` in `row()` (L49). Neither is ever inferred from a check, a merge or a review. **Timestamp-generator labels** match the protocol table field by field. See N6 for one small gap.

### Blocking issues

**B1: A failed PR association is reported as "no associated PR". (L96–98, L129–132)**

`npr` is 0 whenever `pulls` is not a usable 200. That covers a 403 (rate limit), a 404, a 422, `000` (transport failure), and a parse failure that gets replaced by `{}`. `base` is also empty whenever the lowest-numbered PR's `pulls/{n}` GET failed, because that row's `base_ref` is then `""`.

In those cases, five channels are written as `NOT_EVIDENCED_ON_HOST` with the note "not called: no associated PR": reviews, timeline, protection, branch_rules and rulesets.

Example: in curl mode, a second run within the hour gets a 403 on `commits/{sha}/pulls`. The result is `prs=NOT_ACCESSIBLE`, but reviews, timeline, protection, branch_rules and rulesets all show `NOT_EVIDENCED_ON_HOST`. That is a 403 mislabelled as absence.

*Fix:*
- Take the "no PR" branch only when `st pulls` is 200 and `npr` is 0.
- Take the "no base" branch only when the lowest PR's GET is 200 and `base_ref` is empty.
- In every other case write `NOT_ACCESSIBLE` with the upstream status in `note`.

**B2: Channels that were never called are labelled `NOT_EVIDENCED_ON_HOST`. (L132; PROTOCOL-C §2, special case 2)**

The protocol contradicts itself. Its existence rule says "not called → NOT_ACCESSIBLE". Its special case says base-branch channels get `NOT_EVIDENCED_ON_HOST` when there is no PR.

- **rulesets.** `repos/REPO/rulesets` is repository-scoped and does not depend on a base branch at all. Skipping it and asserting `NOT_EVIDENCED_ON_HOST` is a negative by construction.
- **protection and branch_rules.** Having no associated PR does not show that the branch holding the commit is unprotected.

*Fix:*
- Always call rulesets. It stays inside the 29-call plan.
- When there is no base, label protection and branch_rules `NOT_ACCESSIBLE`, with the note "not called: no base branch derivable". Do not use `NOT_EVIDENCED_ON_HOST`.
- Amend PROTOCOL-C §2 so that the special case applies only to the PR-scoped channels (reviews and timeline), and only after a 200 with an empty list.

**B3: Derived fields are written as negatives when the response was not 200. (L106–110, L116–118, L122–125, L136–140, L145, L82–89)**

The `kv` and `prs.tsv` writers run whatever the HTTP status was, and they default missing values to `false` or 0:
- `ch_protection.tsv`: `required_pull_request_reviews false`, `enforce_admins false`, `required_status_checks false`, `required_signatures false`, `push_restrictions false`, `allow_force_pushes false`.
- `ch_checks`, `ch_status`, `ch_runs`: `total_count 0` and `state none`.
- `prs.tsv`, for a failed `pulls/{n}`: `merged false`, `merged_le_t false`, `merged_in_window false`, `n_reviews 0`, `n_approvals 0`.

None of these files carries an http column. In curl mode, protection is *expected* to return 401/403/404. So the planned run will publish a `ch_protection.tsv` that reads as "no review requirement, no signatures, force-push allowed=false" for a branch whose protection was never visible. That is a 403/404 mislabelled as absence, in OUT.

*Fix:* write a `ch_<channel>.tsv` (and PR-row fields) only when the source status is 200. Otherwise write `http\t<status>`, and set every derived value to `NOT_DETERMINABLE`.

## 6. M-TIME: OK, with a limitation

The three kinds of time are kept apart:
- **Decision time** `t` and the window are constants (L9).
- **Host time.** Every host-generated field is labelled `HOST_GENERATED`.
- **Client time.** Git dates and `head_commit.timestamp` are labelled `CLIENT_SUPPLIED`.

Comparisons with `t` are emitted as separate `*_le_t` fields and are not interpreted.

Limitation (N1): the *observation* time is not recorded. That would be either the host `Date` response header or the client clock. The current-state channels (protection, branch_rules, rulesets) are therefore "current" as of an unrecorded instant.

## 7. Neutrality: OK

- There is no hypothesis, conformance test or score.
- Retention is stated as "assumed (unchecked)".
- `n_approvals_le_t` and `updated_le_t` are recorded without interpretation.

---

## Non-blocking issues

- **N1. Observation time is not recorded.** Add the `Date` header (gh: from `.http`; curl: `-D` into TMP) and the client UTC time, each labelled, to `summary.json`.
- **N2. "Lowest numbers first" is not guaranteed.** `per_page=5` returns the *host's* first 5 PRs, and those are then sorted. `cap_reached=true` signals the gap, but the protocol wording overstates what the script does.
- **N3. A wrong note after transport failure.** `WHY` is always "call budget reached" once the repo GET is 200 (L181). A transport failure on `ev1` (`000`, which L161 treats as not called) therefore gets that note. The existence value (`NOT_ACCESSIBLE`) is still correct.
- **N4. Truncation flags are missing for some channels.** checks, runs, rulesets and branch_rules have none. checks and runs at least expose `total_count` against `n_returned`. The checks `HOST_GENERATED` decision (L112) is made on the returned page only, so unreturned runs from other apps would not be seen. The status endpoint is called without `per_page`.
- **N5. The guard can block its own output.** The note on L113 contains "GitHub Actions". If any raw git user object has the name `GitHub` (the web-flow committer on web edits or merges, found in `runs[].head_commit.committer` or in timeline `committed` events), the whole-word match on `GitHub` fails the guard. The run then exits 5 with no output. This fails safe, but it could waste a run. Rephrase the note (e.g. "github-actions app").
- **N6. One timestamp label is slightly loose.** Timeline events with no `created_at` (e.g. `line-commented`) are counted as outside the window, yet labelled `created_at=HOST_GENERATED`. Harmless, but imprecise.
- **N7. Some failure paths are only partly covered.**
  - If git is absent, the "any git work tree" refusal is silently skipped (L22). The WORKTREE_DIR check still holds.
  - OUT is created with `mkdir -p` after the existence check (L197). Plain `mkdir` would close that TOCTOU gap.
  - `grep -I` skips files it judges binary (they would need a NUL byte), which is a theoretical fail-open.
  - Writes to stage files other than `summary.json` are not checked, so a failed write does not trigger exit 6.
- **N8. Logins can leak locally, outside OUT.**
  - Role logins are passed as jq command-line `--arg`, so other local users can see them in `/proc/*/cmdline`.
  - jq error messages on stderr can quote raw values.
  - Neither reaches OUT, but the protocol's claim "only in shell memory and temp dir" is not strictly true.
- **N9. The repository hash is easy to reverse.** `repo_slug_sha256` is an unsalted hash of a known slug. It is fine as an integrity tag, but it is not anonymisation.
- **N10. Some outcomes depend on visibility in curl mode.** Rulesets can come back as a 200 with `[]` because of hidden entries. That would read as `NOT_EVIDENCED_ON_HOST`. The declared coverage loss covers it, but the rulesets row note should say so.

## Required before running

1. Fix B1, B2 and B3 in the script.
2. Make the matching amendment to PROTOCOL-C §2 (special cases) and §5.
3. Run a fresh review. Per PREREG, this goes into the FAIL repair loop.
