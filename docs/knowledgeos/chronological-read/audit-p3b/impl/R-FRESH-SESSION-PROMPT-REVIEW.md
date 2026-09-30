# Independent review: fresh-session S5 canary prompt + AM-1/AM-2

Verdict: PROMPT-ACCEPTABLE-WITH-EDITS (edits below are required; none changes the approved scope).

## Facts checked (all correct)
Attempt numbers (OB0012 attempt 2 PREPARED, OB0114 attempt 1 PREPARED), contract v2.8.1 (7b68cbc9...), manifest 93b78526..., 11 runs (5 + 6), stop rule wording (>=6 of 11 FAILED, or same W-code in >=2 runs, no automatic restart), model id claude-opus-5-5, 396 batches / 1,975 labels, attempt 1 = class X, W4 in 5/5 runs, never estimated: all match G-LOG-0106/7/8, the brief and the findings. Attempt 1 demonstrated only execution integrity of binding, canaries, I(run), prompt delivery, witness/verifier/state chain; the prompt says no valid measurement, which is right.

## Required edits (RE)
RE-1 (factual/contradiction) Prompt s6 names a taxonomy "environment/instrument, contract, agent execution, verifier, state machine, scientific/data". That is NOT the frozen one. The frozen classes are X, A1, A2, H, D, S, U, derived MECHANICALLY from verifier failure tags (EG-6; precedence S>D>H>X>A1>A2; unrecognized -> D; BATCH-UNDETERMINED -> U, no transition). Brief step 9 requires `transition B FAILED --failure-class <mechanical class> --failure-signature <sha256 of sorted normalized failure set>`. A judgement-based class would weaken the pre-registered policy. Replace s6.
RE-2 (omission, brief step 4) Prompt s4 compresses the DECOMPOSED path and drops: the `: S5-ORCH UNITS-VALIDATED batch=B label=L; sha256sum ledger-p3b-r2/<unit>/file-reading-records.jsonl ...` marker (run from CR), the mid-batch `archive B --session`, `freeze-units B L`, and re-running `prepare` into a FRESH emit dir to get the SYNTHESIS prompt with line counts. Restore exactly, and state that the two markers (ASSEMBLED, FINAL-VALIDATED) are Bash commands that begin with `: S5-ORCH ...;` and RUN the tool inside the marker (not a separate call).
RE-3 (omission) Give assemble arguments: `--date <YYYY-MM-DD>` (same date in the --check call) and `--model-id claude-opus-5-5`; the `archive --session` path (this session's own .jsonl under the Claude Code project dir for nrna1; determine it at start, confirm it exists and is growing; if it cannot be determined, STOP). Emit dir must be outside the repo (use the session scratchpad); `<scratch>/prompts-B`. DISPATCHED reason cites G-LOG-0108. Every command as `cd <CR> && ...`; dispatch prompt is the emitted file verbatim, binding line first, general-purpose background Agent.
RE-4 (commit discipline, brief s2 and repo state) Prompt s8 says "commit at the end". The brief says commit PER BATCH: run directories, assembly, freezes and verify report together; archive stays outside git. P3B-STATE.json is tracked and is mutated by every transition: include it in the batch commit (brief is silent; treat it as part of the same evidence, else it is left dirty and later batches mix). Commit only nrna1 canary evidence; do not touch the stray R-5 logs in the ansible repo; never force/rewrite history.
RE-5 (gate safety, s8) "whether any measurement is usable" invites a measurement claim. Under the approved package no label is measured until batch ACCEPTED (VERIFIED -> s21 AUDITED -> H-06), which this session cannot and must not do. Reword: report verify outcome per batch; state that no label is measured by the canary; take no s21/H-06/ACCEPTED action, no retire/new-attempt.
RE-6 (failure handling gap) The prompt lacks what to do on FAILED. Add: record FAILED with class+signature, evaluate the stop rule, do NOT retire/new-attempt/retry in this session (EG-6 requires a fresh session and a human act; RR-6 repairs only at the canary gate), do not repair tools/contract/verifier, preserve everything, then continue to OB0114 only if the brief and stop rule allow, else stop and report. BATCH-UNDETERMINED: no transition, restore the archive, re-verify (class U).
RE-7 (stop-rule tally, flag to human) Attempt 1's 5 W4 failures are retired legacy; the 11 runs are this restart's. Say explicitly: count only this restart's 11 runs; attempt 1 stays preserved and outside the tally. This is the orchestrator/decision-package reading (G-LOG-0107 defines the canary as 11 runs); if the human disagrees, change this line. Also add: "If any rule is ambiguous, STOP and ask; ambiguity is never resolved in favour of continuing."
RE-8 (gate-crossing risk) Sections 9-23 (full S5 Phase A, research loop A-E, theory attack, Model 0, ML) are in the same prompt as the canary. A fresh session optimizing "scientific information gain, not task completion" (s0) could start tasks, spawn workers or draw interpretations. Also s10.B ("exactly one worker ... one task at a time") collides with brief step 3 (dispatch every SINGLE/UNIT run, several background Agents). Required: delete s9-23 from the fresh-session prompt (keep them in a separate document for the later phase) or mark them "INERT: not authorized; do not act, plan or pre-compute for them", and state that during the canary the brief overrides s0/s10. Add "Do not read sampled content, SLICE-VIEWs, run outputs, hold-out/H-19/quarantine/reviews; read only tool outputs, counts, hashes, W-codes; the emitted prompts are pasted verbatim."
RE-9 (preconditions fidelity) Name the checks: H-3 = `tool_sha256_frozen_for_canary` in `audit-p3b/20260930_S5-CANARY-PRECHECK-v281.json` equals the current tool files (do first); contract sha 7b68cbc9... and manifest 93b78526...; both archive paths `~/knowledgeos-witness-archive/OB0012/` and `.../OB0114/` empty/absent (`OB0012.A1/` is the legitimate retired archive); `pwd -P` without `cd` equals --dispatch-cwd before EVERY dispatch (also each re-dispatch of the SYNTHESIS run); served model id is claude-opus-5-5, verified by the session, else STOP. Add pre-check result header note: the pre-check shows may_start_attempt true for both batches.
RE-10 (evaluation content) Per the execution package s3 the canary reports per run: the W1 code, W8 violations, RI-1b coverage, reader pages vs plan, elapsed time, tool-call count; plus what the instrument demonstrated/did not, whether it met its spec, all failures by mechanical class + signature. Add these to s8. No H1-H5, no content interpretation. Note: a canary batch that reaches BATCH-PASS is part of the normal S5 population (package s3) but remains VERIFIED only.

## Gate-crossing assessment
As written: s8 "measurement usable", s9-23, s0 objective and s10.B are the risks; with RE-5/6/8 applied the session cannot cross the canary-evaluation gate. The stop at s8 is correct and must also forbid any batch after OB0114, s21, H-06, freeze changes, R-5 cleanup.

## AM-1 (parallel on disjoint artifacts): ACCEPT WITH EDITS, later phase only, human decision
Not necessary for the canary and not relevant to it. Necessary-ish later (programme already used parallel builders on disjoint files). Risks in the draft wording: "disjoint artifacts" is undefined; S5 batch execution is governed by frozen batch_order, sequential attempts, W-SYS counting and one orchestrator transcript per batch (retired transcript digests are refused), none of which may be parallelized by this amendment; the firewall rulings (s21 auditor never the S5 reader or a theta_D/theta_A auditor) must survive parallelism. Recommended wording:
"One worker per task; a task has one accountable worker and an independent reviewer who is never its builder. Tasks may run in parallel only if their write sets are disjoint and neither consumes the other's unreviewed output. Mutation of shared or production state (manifest, P3B-STATE, ledgers, contract, governance log, freezes) is serialized through one writer. This amendment does not alter the S5 runbook, batch_order, retry policy, W-SYS, or any firewall; S5 batches run only as the runbook and a human act authorize."
Orchestrator may not adopt it itself; it is a change to the human's instruction and takes effect only by human act, after the canary gate.

## AM-2 ("measured" := batch ACCEPTED): ACCEPT WITH EDITS
Correct and consistent: RR-3 (estimation object = first attempt that passes and is accepted), the EG-9 chain VERIFIED -> AUDITED -> ACCEPTED (H-06 tranche), PROGRAM-ACCEPTED, and class H for s21 FAIL/H-06 rejection. Necessary: the canary will produce VERIFIED batches and package s3 makes them part of the population. Wording defects: "estimated only by RR-3" conflates measured vs estimation object (RR-3 selects which attempt is the estimation object; it does not estimate), and "first attempt that reaches BATCH-PASS and is accepted" hides that RR-1 (PASS never re-run) means at most one PASS exists. Also say EMPTY/DECOMPOSED labels follow their batch, and that NOT-ASSESSABLE labels are worst-cased (EP-01 s7), not measured. Recommended:
"A label is measured iff its batch attempt reached BATCH-PASS and was ACCEPTED (VERIFIED, then s21 AUDITED, then H-06 ACCEPTED). VERIFIED, PROPOSED, or passed-but-unaccepted is not measured. By RR-3 the estimation object of a batch is its first attempt that passes and is accepted; failed or retired attempts are never measured, estimated, or shown to auditors, and are counted in E-3. Labels of unaccepted batches are NOT-ASSESSABLE (worst-cased per EP-01 s7). Today: 0 labels measured."

## Final recommended prompt (paste as-is)
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
