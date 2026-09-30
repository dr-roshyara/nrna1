# S5 canary: prompt for the FRESH orchestrator session (G-LOG-0109)

Source: the independent review `audit-p3b/impl/R-FRESH-SESSION-PROMPT-REVIEW.md` (verdict PROMPT-ACCEPTABLE-WITH-EDITS; accepted by the human, G-LOG-0109).

Usage: start a Claude Code session with its primary working directory in `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`, then give it the text below verbatim, or tell it: "Read and execute docs/knowledgeos/chronological-read/audit-p3b/20260930_S5-CANARY-FRESH-SESSION-PROMPT.md".

```
ROLE. You are the S-Series orchestrator for ONE bounded task: run the S5 canary restart exactly as written in
docs/knowledgeos/chronological-read/audit-p3b/20260930_S5-CANARY-OPERATOR-BRIEF.md (CR = .../nrna1/docs/knowledgeos/chronological-read),
under G-LOG-0108, contract v2.8.1 (7b68cbc9...; manifest 93b78526...), the R7 instrument, the freezes, the pre-registered retry policy
and the frozen stop rule. Read the brief, G-LOG-0106/0107/0108, audit-p3b/20260930_S5-CANARY-RESTART-DECISION.md and
audit-p3b/20260930_S5-CANARY-ATTEMPT1-FINDINGS.md first. The brief overrides everything else here. Fidelity to the brief, not
task completion or information gain, is the objective during the canary.

SCOPE. Approved: OB0012 attempt 2 (5 SINGLE runs), then OB0114 attempt 1 (3 SINGLE + 1 DECOMPOSED = 2 UNIT + 1 SYNTHESIS + 1 EMPTY label): 11 runs.
NOT approved and not to be started, planned or pre-computed: any other batch, full S5, s21 audit, H-06 acceptance, retire/new-attempt, reconstruction,
statistics, H1-H5 or any theory interpretation, models, ML, P4, H-19, verdicts, any freeze/frame/sample/estimand change, cleanup of the stray logs in the
ansible repository (R-5). Your work ends at the CANARY-EVALUATION GATE. Do not cross it. S-Series only; no sampled-content reading
(SLICE-VIEWs, run outputs, corpus, hold-out/H-19/quarantine, reviews/); use only tool output, counts, hashes, W-codes. Emitted prompts are pasted verbatim.

ATTEMPT 1. OB0012 attempt 1 is preserved historical evidence (class X environment failure; no valid measurement). Never reinterpret, estimate from,
combine, erase or normalize it. Its 5 runs are not among this restart's 11 and are not counted in the stop rule. Attempt 2 is a new execution.

PRECONDITIONS (verify, in this order; on ANY failure STOP, repair nothing, report, wait):
1 primary cwd is inside /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1; every command is `cd <CR> && ...`.
2 H-3: tool_sha256_frozen_for_canary in audit-p3b/20260930_S5-CANARY-PRECHECK-v281.json equals the current tool files.
3 G-LOG-0108 present; contract and manifest shas as above; H-19 untouched.
4 OB0012 PREPARED at attempt 2 (OB0012.A1 retired copy and archive exist); OB0114 PREPARED at attempt 1; P3B-STATE chain valid.
5 ~/knowledgeos-witness-archive/OB0012/ and .../OB0114/ empty or absent.
6 your served model id is claude-opus-5-5 (agents inherit it); otherwise STOP.
7 locate THIS session's transcript .jsonl (the path for `archive --session`); confirm it exists; otherwise STOP. Note it in your log.
8 no stale/conflicting state; git status clean for canary paths. Before EVERY dispatch (each run and the SYNTHESIS run): run `pwd -P` in a Bash call
  without `cd`; it must equal the --dispatch-cwd you passed to prepare, exactly; otherwise STOP.

PER BATCH (OB0012 first, then OB0114). Follow the brief's table exactly; in summary:
1 `python3 -B scripts/p3b_s5_r7_orchestrate.py prepare B --emit <scratchpad>/prompts-B --dispatch-cwd <primary cwd> --state P3B-STATE.json`
2 `python3 -B scripts/p3b_s5_state.py transition B DISPATCHED --reason "G-LOG-0108"`
3 dispatch every SINGLE and UNIT run: one Agent call each (general-purpose, background), prompt = the emitted file verbatim, binding line first.
4 DECOMPOSED label only (OB0114), after its UNIT runs complete: Bash `: S5-ORCH UNITS-VALIDATED batch=B label=L; sha256sum ledger-p3b-r2/<unit>/file-reading-records.jsonl ...`
  (from CR); `orchestrate archive B --session <this .jsonl>`; `orchestrate freeze-units B L`; re-run `prepare` into a FRESH emit dir for the SYNTHESIS prompt
  (with its line counts) and dispatch it.
5 when all runs complete: `: S5-ORCH ASSEMBLED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <YYYY-MM-DD> --model-id claude-opus-5-5`
6 `: S5-ORCH FINAL-VALIDATED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <same> --model-id claude-opus-5-5 --check`
7 `orchestrate archive B --session <this .jsonl>`, then `orchestrate freeze-final B`; 7a `orchestrate coverage B` (report-only);
  7b `state transition B PROPOSED --reason "assembly + freeze-final complete; WITNESS-DIGESTS.json sha256 <h>"`
8 `orchestrate verify B`
9 BATCH-PASS -> `transition B VERIFIED --evidence audit-p3b/S5-VERIFY-B-R7[.A<m>].json` (A2 file name for attempt 2). BATCH-FAIL -> `transition B FAILED
  --failure-class <mechanical class X/A1/A2/H/D/S/U as derived from the verifier tags, never your judgement> --failure-signature <sha256 of the sorted normalized failure set>`.
  BATCH-UNDETERMINED -> NO transition; restore the archive and re-verify (class U).
10 commit, per batch: that batch's run directories, assembly, freezes, verify report and P3B-STATE.json together (archive stays outside git). No force, no history rewrite.
Do not omit or reorder provenance steps. The markers in 4-6 are Bash commands beginning `: S5-ORCH ...;` and run the tool inside the marker.

STOP RULE (absolute; evaluate after every completed run): stop the canary if >= 6 of this restart's 11 runs have FAILED, or the same W-code occurs in >= 2 of
them. No automatic restart, no substituted threshold, no continuing because a failure looks harmless. Stopping = preserve everything, report, wait.
ON FAILURE: record class+signature as above; do not retire, start a new attempt, retry, or repair tools/contract/verifier/state in this session (EG-6 needs a fresh
session and a human act; repairs only at the canary gate). Execution failure is not a false hypothesis; success is not a true one. If any rule is ambiguous, STOP and ask.

AFTER THE CANARY (or after a stop): write the execution-integrity evaluation and STOP. Content: per run the W1 code, W8 violations, RI-1b coverage, reader pages vs plan,
elapsed time, tool-call count; per batch verify outcome and, for failures, mechanical class+signature; what the instrument demonstrated and did not; whether it met its spec.
State that NO label is measured by the canary (measured = batch ACCEPTED, which needs s21 and H-06, a later human-gated phase). No H1-H5, structure, models, effect sizes or
theory statements. Then stop: no other batch, no inferred authorization. The later phase (full S5 measurement, research loop) is governed by a separate document and is INERT
for this session.
```
