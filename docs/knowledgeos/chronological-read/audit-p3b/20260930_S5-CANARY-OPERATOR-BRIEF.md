# S5 canary: operator brief for a FRESH orchestrator session

| | |
|---|---|
| **Kind** | ⚠ authority: generated. The exact runbook for the canary restart. Valid only after the human act approving `audit-p3b/20260930_S5-CANARY-RESTART-DECISION.md` |
| **Runbook** | execution package §6 (v2.8), contract v2.8.1 once rebound; stop rule of G-LOG-0107 |

## 0. Preconditions (check all; stop if any fails)
- **Working directory.** The session's primary working directory is inside `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`.
  - Every command is run as `cd <CR> && …`, with CR = `…/nrna1/docs/knowledgeos/chronological-read`.
  - Every `prepare` passes `--dispatch-cwd <the session's primary working directory>`.
  - Before every dispatch, run `pwd -P` in a Bash call **without** `cd`. Its output is the directory the agents inherit, and it must equal `--dispatch-cwd` exactly; otherwise STOP. This closes the residual in R-C1: the tool can check only the directory it is given.
- **Scope.** Work only on the S-Series; ignore other lanes.
- **Governance.** G-LOG shows the restart approval; the contract rebind (v2.8.1) is committed; the latest canary pre-check record's `tool_sha256_frozen_for_canary` equals the current tool files (H-3).
- **State.**
  - OB0012 is PREPARED at attempt 2: attempt 1 was retired into `ledger-p3b-r2/OB0012-R7.A1/`, and `new-attempt` was recorded.
  - OB0114 is PREPARED at attempt 1.
  - The archive paths `~/knowledgeos-witness-archive/OB0012/` and `…/OB0114/` are empty or absent.
- **Model.** The served model id is `claude-opus-5-5`; agents inherit it.

## 1. Per batch (OB0012 first, then OB0114)

| Step | Command / act |
|---|---|
| 1 | `python3 -B scripts/p3b_s5_r7_orchestrate.py prepare B --emit <scratch>/prompts-B --dispatch-cwd <session cwd> --state P3B-STATE.json` |
| 2 | `python3 -B scripts/p3b_s5_state.py transition B DISPATCHED --reason "<G-LOG>"` |
| 3 | Dispatch every SINGLE and UNIT run: one Agent call each (general-purpose, background). The `prompt` is the emitted file **verbatim**; the binding line is first |
| 4 | Only for a DECOMPOSED label, once its UNIT runs have completed:<br>(a) `: S5-ORCH UNITS-VALIDATED batch=B label=L; sha256sum ledger-p3b-r2/<unit>/file-reading-records.jsonl …` (run from CR, so the relative paths are printed);<br>(b) `orchestrate archive B --session <this session's .jsonl>`;<br>(c) `orchestrate freeze-units B L`;<br>(d) re-run `prepare` into a fresh emit dir to obtain the SYNTHESIS prompt with its line counts, then dispatch it |
| 5 | Once every run has completed: `: S5-ORCH ASSEMBLED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <YYYY-MM-DD> --model-id claude-opus-5-5` |
| 6 | `: S5-ORCH FINAL-VALIDATED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <same> --model-id claude-opus-5-5 --check` |
| 7 | `orchestrate archive B --session <this session's .jsonl>`, then `orchestrate freeze-final B` |
| 7a | `orchestrate coverage B` (report-only) |
| 7b | `state transition B PROPOSED --reason "assembly + freeze-final complete; WITNESS-DIGESTS.json sha256 <h>"` |
| 8 | `orchestrate verify B` |
| 9 | BATCH-PASS → `transition B VERIFIED --evidence audit-p3b/S5-VERIFY-B-R7[.A<m>].json`. BATCH-FAIL → `transition B FAILED --failure-class <mechanical class> --failure-signature <sha256 of the sorted normalized failure set>`. BATCH-UNDETERMINED → no transition; restore the archive and re-verify |

**Stop rule** (evaluate after every completed run): stop the canary if ≥ 6 of the 11 runs have FAILED, or if the same W-code occurs in ≥ 2 runs. No automatic restart.

## 2. After the canary
- Commit, per batch, the run directories, the assembly, the freezes and the verify report together (the archive stays outside git).
- Write the canary evaluation: execution integrity only, with no H1–H5 interpretation.
- **STOP** at the canary-evaluation gate.
