# S5 canary restart: ONE decision package (after attempt 1, G-LOG-0107)

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Decision package for a human act. Nothing in it is applied |
| **Decision** | **APPROVE the canary restart as specified**, or REJECT / REVISE it (name the item) |
| **Evidence** | `audit-p3b/20260930_S5-CANARY-ATTEMPT1-FINDINGS.md` (F-1…F-5); `audit-p3b/S5-VERIFY-OB0012-R7.json` (BATCH-FAIL, class X) |

## Why a human is required
1. **Restart after a stop.** The frozen canary rule says no automatic restart after a stop.
2. **A contract text change (F-2).** The addendum sha is production-bound, so the change needs a rebind.
3. **A fresh orchestrator session.** EG-6 W2 and finding F-1 require one, with its primary working directory inside nrna1. Only the human can start it.

## The bounded change
| # | Item | Kind | State |
|---|---|---|---|
| R-1 | **F-1/F-5 guard.** `orchestrate prepare … --dispatch-cwd DIR` is required. It refuses unless the reader, run from the session's primary working directory, resolves this repository and finds the batch activated | orchestrator tool | implemented tests-first; review R-C1 IMPL-ACCEPTABLE; committed; live-tested: refuses from the ansible working directory, nothing written |
| R-2 | **F-3 line counts.** Every input line in a prompt states its harness line count (N = bytes.count("\n") + 1, as measured on the harness), plus "Stop paging at the stated line count" | orchestrator tool (prompt facts, no rule) | implemented tests-first; the count rule was verified independently on the harness (a 4-line and a 3-line file); committed |
| R-3 | **F-2 addendum status text (v2.8.1).** Replace the stale line "**Not frozen, not implemented, not activated.** S5 execution, dispatch and corpus reading are **NOT AUTHORIZED**." and the stale "NOT ACTIVATED" in the status sentence with: "**Activated (G-LOG-0102); bound as v2.8 (G-LOG-0106).** Execution is governed only by the recorded human acts (G-LOG-0107 onward); this status line authorizes nothing by itself." No rule text changes | contract text → one rebind (EG-4 procedure) | proposed |
| R-4 | **OB0012 attempt 2.** `retire OB0012 --cause-class X`, then `state new-attempt`, then the unchanged runbook in the fresh session. It is within the pre-registered policy (class X, attempt 2 of M = 4, repairs admissible at the canary gate, RR-6). After that, OB0114 attempt 1 | runbook | ready |
| R-5 | **The 5 stray READ-LOG files** that the misrouted reader wrote into the ansible repository (`docs/knowledgeos/…/READ-LOG.jsonl`, untracked; copies preserved in the witness archive): remove them, or authorize the orchestrator to remove them | housekeeping in another repository | human choice |

## Considered and not proposed now
- **Making the reader resolve its root from its own file location.** That would change the audited reader. R-1 closes the failure operationally without touching it. Proposed only if R-1 proves insufficient.
- **A witness tolerance for past-end-of-file reads (F-3).** That would change the verifier. R-2 removes the cause. Otherwise a past-end-of-file probe stays a W8 failure: conservative and visible.

## Scientific invariants
- Unchanged: frame, sample, seed, strata, estimands, H1–H5, Model 0, the freezes, and the retry policy (the restart uses it as pre-registered).
- The failed attempt 1 stays preserved as Legacy(B), counted in E-3 (class X), and never estimated.

## After approval (bounded, then STOP at the next gate)
1. The orchestrator applies R-3 and rebinds, tests first.
2. It commits R-1/R-2 after review, then re-runs the pre-check (the H-3 re-freeze).
3. It retires OB0012 attempt 1 and records the new attempt.
4. **The human starts a fresh Claude Code session in** `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`, with the instruction "execute audit-p3b/20260930_S5-CANARY-OPERATOR-BRIEF.md".
5. That session runs OB0012 attempt 2 and OB0114 attempt 1 under the frozen stop rule, and stops at canary evaluation.
