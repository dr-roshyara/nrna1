# Main-analyst safety check of `A2/scope_c.sh` (r2), sha256 `b1202b618f1965d3b6d83216160c8778bd3d2db788f69f6cf6b09afa79d41c5f`

## History
- **r1 check: BLOCKED.** The script read `commit.verification` (signer verification), which the human declined (H2 = NO). This led to r2 (F-LOG-0193).
- **r2 review:** PASS_WITH_LIMITATIONS (`B2/REVIEW.json`).

## Checklist (from the human's post-H1 prompt)

| Check | Result |
|---|---|
| scope == frozen | ✅ one repo, `e4724a44`, PRs ≤ 5, base from the lowest PR, window 2026-08-03..06 |
| method == read-only | ✅ only `get()` touches the network: `gh api -X GET -i` or `curl -sS -X GET` |
| calls ≤ 40 | ✅ budget checked before each call; maximum path 29 |
| target == e4724a44 | ✅ constant `SHA` (l.8) |
| window == 2026-08-03 … 2026-08-06 | ✅ constants `WF` / `WE` |
| no identity interpretation | ✅ equality relations only; raw responses stay in a mktemp TMP; the guard fails closed |
| no hypothesis scoring | ✅ norm_state and independence are constant NOT_DETERMINABLE |
| no signer investigation | ✅ (r2) |
| no destructive command | ✅ removal only of TMP, by pattern-guarded `rm -rf`; OUT created by plain `mkdir` |

## Reviewer non-blocking items: disposition
- **NB-r2-1** (a signature-rule count is derivable from branch-rule totals): this is branch configuration, not signer data. **Accepted as within H2 = NO; flagged for the human.**
- **NB-r2-2** (a failed harvest could weaken the guard): **mitigated at run time.** The main analyst greps OUT for `@`, `login` and every account handle **before anything is committed**.
- **NB-r2-3** (jq regex): **not triggered**. jq-1.8.1 has regex support (verified locally).
- **NB-r2-5** (events pages fetched repo-wide before the window filter): within the PREREG bound; raw data stays in TMP only.

## Verdict
**SAFE TO RUN, authenticated only.**

**Execution is BLOCKED at the authentication gate:**
- the `gh` keyring token is invalid (re-checked 2026-10-01);
- the script would fall back to unauthenticated curl;
- **the human instructed: do not bypass authentication.**

**Run command, after the human runs `gh auth login -h github.com`:**
```
bash <scratchpad>/LOOP-1BF/A2/scope_c.sh <scratchpad>/LOOP-1BF/RUN1 <repo-root>
bash <scratchpad>/LOOP-1BF/A2/scope_c.sh <scratchpad>/LOOP-1BF/RUN2 <repo-root>
```
The second run is a reproducibility check. Host data may differ only in the observation time.

**Precondition:** before running, confirm `gh auth status` shows a valid token and `mode=gh`.
