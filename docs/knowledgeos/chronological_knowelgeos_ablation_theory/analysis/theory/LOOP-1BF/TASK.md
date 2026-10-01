# Task (frozen): design Protocol C, a bounded, read-only host-side scoping

Use ONLY:
- `PREREG.md`
- `TARGET-FACTS.md`
- `ENVIRONMENT.md`
- `QUESTIONS.md` (the scoping questions and the definition of "independent")
- `R-SELF-RULE.md`

No commands, no web.

Write two files.

## PROTOCOL-C.md (at most ~2 pages)

### 1. Channel list, each with the exact GitHub REST endpoint (GET only)
Decide and justify the set. Candidates:
- repository metadata (visibility);
- the PRs associated with the commit;
- per PR: reviews, the review/merge/timeline events, merged_at / merged_by **relations**;
- check-runs and combined status for the commit;
- Actions workflow runs with head_sha = the commit;
- the PR base branch's protection and rulesets: **current state only; say plainly that historic state is not available**;
- repository activity / events for the window. **State the retention** as an assumption to be checked, never as fact.

### 2. Rules for each channel
For each channel give the rule for:
- existence (the PREREG outcomes);
- the **timestamp generator** (host-generated vs client-supplied, per field; e.g. `merged_at` and `submitted_at` are host-generated, commit dates are client-supplied);
- what the channel could say about **norm state** at t (most will say NOT_DETERMINABLE: a check-run says code was tested, not that work was permitted).

### 3. Identity policy
- Output **no logins, names, e-mails or avatars**.
- Only **equality relations** between roles, as booleans or counts. For example: "the PR approver equals the PR author"; "the merger equals the commit author's mapped login"; "number of distinct reviewers".
- If identity mapping between git e-mail and login is unavailable → NOT_DETERMINABLE.

### 4. Bound enforcement
- one commit;
- its associated PRs (cap 5);
- one base branch;
- the window;
- a call budget of ≤ 40;
- no pagination beyond what the bound needs.

### 5. Output
TSV/JSON per channel, a summary, and SHA256SUMS.

## scope_c.sh: a small bash script (under ~170 lines)
- **GET only**, through `gh api -X GET` (authenticated) or `curl -sS -X GET` (unauthenticated fallback, public repos only; declare the coverage loss).
- Deterministic sorting: LC_ALL=C, TZ=UTC.
- It resolves its own path before any `cd`, and refuses an OUT inside the work tree.
- No `set -e`; explicit exit codes.
- **Raw API responses are processed in a temp directory and never written to OUT** (they contain identities). Only derived, identity-free fields go to OUT.
- Removal is limited to that temp directory.
- A final identity guard fails closed: it greps OUT for `@`, `login`, `avatar_url`, `html_url` and `name"`.
- It records the HTTP status of every call and the call count. It stops at the budget.
