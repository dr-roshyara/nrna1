# CHANGELOG — Protocol C r0 → r1

Scope: the repairs listed in TASK.md, and nothing else. Basis: REVIEW-r0.md/.json (verdict FAIL). Nothing was run.
Unchanged: the bound, GET-only, the call budget (≤ 40, planned max 29), the identity policy (§3), M-TIME, neutrality, the norm-state/independence constants, and the endpoint set.

## B1–B3 (blocking): one status-gating rule for every channel

PROTOCOL-C §2 (rewritten "Existence" and special cases), §4, §5; scope_c.sh `get`, `lab`, `why`, `dep`, `crow`, `kv`, `agg`, `scan`.

- **Derived fields only from a 200.** `kv CH STATUS` writes `http\t<status>` and turns every derived value into `NA` unless STATUS is 200; `scope` (constant) and `n_distinct_bases` (computed from the PR rows) are left as they are. `prs.tsv` now has `pr_http`, `reviews_http` and `timeline_http` columns, and each field is gated on its own source status via jq `g()` (PR fields on `pulls/{n}`, review fields on `reviews`, `timeline_truncated` on `timeline`). Commit-author relations (`eqc`, `cnt`) are `NA` unless the commit GET is 200. `timeline.tsv` rows for a non-200 are `NA`. `rulesets.tsv` rows only come from a 200; the counts go to a new `ch_rulesets.tsv`. Fixes B3: for example, `ch_protection.tsv` can no longer show `false` values after a 401, 403 or 404.
- **Existence follows the PREREG mapping** (`lab`): 200 → EXISTS / NOT_EVIDENCED_ON_HOST; 401/403/404, plus 422 for `commit` (DOC422, assumption to check) → NOT_ACCESSIBLE; 000 or any other code → `NOT_ACCESSIBLE[transport]`; budget refusal (`get` now writes status `budget` and makes no call) → `NOT_ACCESSIBLE[not_called_budget]`. NOT_RETAINED stays limited to the events 200-coverage rule. A 200 with an unparsable body becomes `200_unparsable`, so it cannot be read as an empty 200. `agg ""` now returns `000`, not `200`.
- **No-PR branch only on a 200 with an empty list.** In that case reviews, timeline, protection and branch_rules are reported `NOT_APPLICABLE[no_associated_pr]`. §2 states that this does not show the commit's branches are unprotected. Fixes B1.
- **Failed association → dependency_failed.** If pulls is not 200, reviews, timeline, protection and branch_rules are `NOT_ACCESSIBLE[dependency_failed]` (or `[not_called_budget]` when pulls was refused by the budget). The same applies when the lowest-numbered PR GET is not 200 or gives no base, for protection and branch_rules, and when the repository GET is not 200, for every channel. Fixes B1.
- **Never called ≠ NOT_EVIDENCED_ON_HOST.** No code path writes NOT_EVIDENCED_ON_HOST without a 200. Fixes B2.
- **rulesets is repository-scoped and always called**, independent of the PR outcome. It moved out of the base-branch block. Fixes B2.
- **The protection special case is removed.** A 404 "Branch not protected" used to give NOT_EVIDENCED_ON_HOST; it now gives NOT_ACCESSIBLE like every other 404. This follows from "one rule for every channel" and the PREREG mapping (404 → NOT_ACCESSIBLE).
- The early `return`s on budget are removed. Every later `get` is refused by the budget and its channel is labelled from status `budget`, so no channel falls through to a generic note.

## N1: observation time

`get` records the `Date` response header (gh: from the `-i` headers; curl: `-D` into TMP) and the client UTC clock at each call. `summary.json.observation_time` carries `host_date_header.{first,last}` labelled `HOST_GENERATED` and `client_clock_utc.{first,last}` labelled `CLIENT_SUPPLIED`, with `NA` if a value is unavailable. PROTOCOL-C §5. `date` has been added to the required tools.

## N3: WHY note reflects the actual cause

`why` produces `not called: call budget reached` / `transport failure (000)` / `http <code>`. `crow` puts it at the start of every non-200 note, including the events row and aggregated rows. The final fallback note now names the real cause (repository GET status, or the budget) and no longer always says "call budget reached".

## N4: truncation and page scope

- New flags: `checks_truncated` and `runs_truncated` (100 returned, or `total_count` > returned), and `rulesets_truncated` and `branch_rules_truncated` (100 returned).
- status now passes `per_page=100`.
- The checks HOST_GENERATED decision is limited to the returned page: the `ts_decision_scope=returned_page` key, the ts_generator label "(returned page only)", and the wording in §2 and its table.

## N5: guard not tripped by the script's own notes

- The checks note "…by GitHub Actions" is now "…by the host CI app".
- The coverage strings no longer name a tool or product ("authenticated" / "UNAUTHENTICATED: …"). `mode` still records gh/curl.
- No other note contains a product or proper name.
- The guard itself is unchanged and still fails closed.

## N6: timeline events without a timestamp

Each event type reads its own field: `committed` → `author.date` (CLIENT_SUPPLIED), `reviewed` → `submitted_at`, everything else → `created_at`. Events without that field are counted in `n_no_timestamp` (NO_TIMESTAMP). The window counts use only `n_timestamped` events. A group with no timestamps is labelled `NO_TIMESTAMP` and never `HOST_GENERATED`. The `timeline.tsv` columns are now pr, http, event, n, n_timestamped, n_in_window, n_no_timestamp, ts_generator.

## N7: path safety

- `git` has been added to the required tools, so the script refuses to run without it, and `inside()` no longer skips the work-tree test silently.
- OUT is created with plain `mkdir` (no `-p`) after the existence check and the staged guard. If that fails, the script exits 3.
- Disclosed consequence: OUT's parent must already exist.

## N2, N8, N9, N10

These are not repaired. Each is disclosed in one line in PROTOCOL-C §6.
