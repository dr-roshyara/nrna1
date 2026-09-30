# S5 execution package under R7 (DRAFT, for human review; nothing executed)

| | |
|---|---|
| **Kind** | Execution-design draft. ⚠ authority: generated. **It dispatches nothing, changes no production state, and does not alter the frozen design** (Freeze 1: G-LOG-0094; Freeze 2: G-LOG-0103) |
| **Production state** | manifest `c7f3b257…` (rev 7, contract v2.6 `49169328…`); 396 batches PREPARED at R7; slices `_batch_input_r2/s5/rev7` (`36ba7f578`); plans `9239ccad…`; sample `e3f3ed76…`, anchor `8e0d9cb4…`; H-19 SEALED |
| **Bindings the agents and the witness use** | reader `…/scripts/p3b_read_source.py` · activation commit `66065984a3a234be1d8d9686f7676a2f5ccefd86` · archive `~/knowledgeos-witness-archive/` |

## 0. Two blockers before the canary (found while drafting; reported, not solved)

| Id | Finding | Evidence | Why it blocks | Proposed resolution (decision needed) |
|---|---|---|---|---|
| **EG-2 (RI-1 reopened, its condition 1: it threatens S5 execution)** | **Every slice is ONE line of canonical JSON.** Agents read their slice with the Read tool (I(run) category SLICE) | line lengths: median 29,013 characters, p90 104,761, max 3,189,120. **723 of 1,975** slices exceed 40,000 characters (the verified-fidelity range); **213** exceed 100,000 (in the RI-1 probe the harness silently **dropped** such a line). `offset`/`limit` cannot split one line | a large share of agents would **not see their input**. v2.6 accepts a partial display (RI-1), so the defect would be invisible: false completeness at scale | **SLICE-VIEW** (contract amendment v2.7, Universe + witness only): a deterministic, **lossless**, multi-line rendering of each frozen slice. JSON with indent 1; string values over 2,000 characters are emitted as numbered chunk lines. It is hash-bound as a new I(run) category, and the verifier checks `parse(view) = slice` byte-exactly. The slice itself and its hash stay unchanged. Rejected alternatives: paging slices through the corpus reader (it mixes input with evidence) and inlining the slice into the prompt (slices up to 3.2 MB) |
| **EG-1 (tooling gap)** | **No production orchestrator tool exists.** The artifacts the verifier requires are produced only by the test fixtures | `INPUT-MANIFESTS.json`; `WITNESS.jsonl`, `WITNESS-DIGESTS.json`, `WITNESS-UNIT.jsonl`, `WITNESS-UNIT-DIGESTS.json`; the archive layout `<archive>/<batch>/{main.jsonl, subagents/, tool-results/}`; the orchestrator markers | the canary cannot produce a verifiable batch without these | `scripts/p3b_s5_r7_orchestrate.py`, tests first, **reusing the production functions** (`U.input_manifest`, `W.witness`, `W.witness_bytes`, `W.digests`): **`prepare B`** (run directories; I(run) manifests; rendered dispatch prompts), **`archive B`** (copy the session transcripts into the archive), **`freeze-units B L`**, **`freeze-final B`**, **`verify B`** (`p3b_s5_verify`). No agent is dispatched by the tool; dispatch is the orchestrator session's Agent call |

**Also recommended (not a blocker):** the contract (99,924 characters, 1,358 lines) and the addendum (70,752 characters, 805 lines) are multi-line but exceed one Read display. The dispatch prompt below makes agents **page** them (`offset`/`limit` ≤ 300 lines). The orchestrator reports, not FAILS, whether each agent's displayed ranges cover each input file (RI-1b: completeness evidence without a contract change).

## 1. Runbook: the per-batch lifecycle (R7; every step witnessed)

| # | Step | Actor | Output | Stop condition |
|---|---|---|---|---|
| 1 | `orchestrate prepare B`: state PREPARED → check the plan hash; create the run directories `ledger-p3b-r2/<run>/`; write `INPUT-MANIFESTS.json` (U.input_manifest per run); render one dispatch prompt per run | tool | run directories, I(run) manifests, prompts | any R7-U from the plan/manifest checks |
| 2 | `state transition B DISPATCHED` | tool | state | — |
| 3 | **Dispatch** every SINGLE and UNIT run, one Agent call per run with the rendered prompt, **from a dedicated orchestrator session** (its transcript is the witness `main.jsonl`) | orchestrator | agent transcripts; hand-backs | a harness denial or a refused reader call → the run FAILED (W4) |
| 4 | Per DECOMPOSED label, after all its units: the marker `: S5-ORCH UNITS-VALIDATED batch=B label=L;` (prints `sha256  path` of each unit record); `orchestrate freeze-units B L` | orchestrator + tool | `WITNESS-UNIT*` | a unit record missing or changed |
| 5 | Dispatch the label's SYNTHESIS run (records only: inputs = the unit records; **no reader call**) | orchestrator | the final records | W8 |
| 6 | The marker `: S5-ORCH ASSEMBLED batch=B;` → assemble `ledger-p3b-r2/B-R7/` (objects, register, p1-gap, claim-evidence, S3-LINT) → the marker `: S5-ORCH FINAL-VALIDATED batch=B;` | orchestrator | the assembly | — |
| 7 | `orchestrate archive B` (the main + subagent transcripts, persisted tool results → the archive) → `orchestrate freeze-final B` | tool | `WITNESS.jsonl`, `WITNESS-DIGESTS.json` | an archive digest mismatch |
| 8 | `orchestrate verify B` → BATCH-PASS / BATCH-FAIL / BATCH-UNDETERMINED | tool | the verify report | — |
| 9 | `state transition B PROPOSED → VERIFIED` (evidence = the verify report) — or FAILED with its cause. **A failed R7 batch has no defined re-run naming** (`new_run` refuses on R7): that needs a human act | tool | state | — |

**Commit discipline:** per batch, the run directories, the assembly, the freeze files and the verify report are committed together. The archive stays outside git (FD-4′).

## 2. The dispatch prompt (template; rendered per run by `prepare`)

```
S5-RUN-BINDING run={RUN} batch={BATCH} label={LABEL} canary={sha256(66065984a3a234be1d8d9686f7676a2f5ccefd86 + RUN)[:16]}

You are one S5 reading run under contract revision 7 (the R7 addendum v2.6). You are a measurement instrument:
record what the sources say; never repair, infer across files you did not read, or invent.

YOUR INPUTS (read ONLY these, with the Read tool; page every file with offset/limit of at most 300 lines until
the whole file has been displayed):
{I(run) entries: CONTRACT prompts/20260925_1204_p3b-agent-contract-r2.md · ADDENDUM prompts/20260926_2400_…r7-addendum.md
 · PLAN _batch_input_r2/s5/rev7/{BATCH}.R7-PLAN.json · SLICE (or SLICE-VIEW, EG-2) _batch_input_r2/s5/rev7/{BATCH}/{LABEL}.json
 · for SYNTHESIS only: UNIT-RECORDS ledger-p3b-r2/{BATCH}-R7-L##U##/file-reading-records.jsonl}

CORPUS FILES: the ONLY way to read a source file is this exact Bash command, one page per call, nothing else on the line:
  python3 -B {READER_ABS} --run {RUN} --batch {BATCH} --label {LABEL} --step <1|7|10> --mode bytes --page <K> <S####>
Your files are exactly: {the run's files from the frozen plan}. Read every page of every file.
{if the plan lists binary_decisions: "Do NOT call the reader on: {S####: decision}. Record each as instructed in §6.1."}

WRITE ONLY these files (Write tool): {U.permitted_writes(run, role)}
NO other tool calls: no ls, find, cat, grep, mkdir, cd, pip, no other Bash, no web. Every other call FAILS the run (W8).

When finished, call SubagentHandback with a one-line summary (counts only). Do not paste source content.
```

The role-specific body (SINGLE / UNIT / SYNTHESIS) is taken verbatim from the contract sections the addendum names. The prompt restates **no** rule; it only points to them and adds the execution grammar.

## 3. The canary

| Batch | Composition (frozen plan) | Runs | Why |
|---|---|---:|---|
| **OB0012** | 5 SINGLE | 5 | the simplest non-hub batch; no decided binary |
| **OB0114** | 3 SINGLE + 1 DECOMPOSED (2 units + synthesis) + 1 EMPTY | 6 | exercises all three paths, the unit → synthesis handoff and EMPTY, with no binary |
| **Total** | | **11** | |

- **Stop rule (Freeze 2 / B1 v2):** if **≥ 6 of the 11 runs FAIL** (≥ 50%), or a systematic pattern shows (the same W-code in ≥ 2 runs), **stop S5**. Diagnose in this order: runbook → dispatch prompt → agent behaviour. Change the verifier **only** for a demonstrated verifier defect.
- **The canary is an instrument test, not evidence for any hypothesis.** A canary batch that reaches BATCH-PASS counts as a normal S5 batch; the canary is part of the population, not additional to it.
- **Reported per run:** the W1 code, the W8 violations, input-coverage completeness (RI-1b), the reader pages versus the plan, the elapsed time and the tool-call count.

## 4. The audit protocol (after S5 objects are accepted; the frozen Freeze 2 sample)

| Quantity | Instrument | Units | Blindness | Timing |
|---|---|---|---|---|
| **θ_D** | an independent re-analysis per sampled label under the same protocol; DECOMPOSED labels use an independent partition (EP-01 Q-S1) | 231 (33 HUB, 7 EMPTY, 4 MULTIROW, 87 DECOMPOSED, 100 SINGLE) | the re-analysis never sees the S5 object, its records or its read log | after the label's S5 object is accepted; complete before P4 |
| **θ_A** | a human adjudicator | the MULTIROW census (4) + 10 SINGLE (Freeze 2 subsample, seed 2) | sides labelled A/B by a separate seed; the adjudicator does not know which side is S5 and never sees the S5 read log | after θ_D |
| **Reference quality** | two independent humans | the 20-label double-adjudication subsample | as above | before θ_A may even be proposed as θ_E (B1 v2 §1) |

- **Estimation:** `estimate_v7` with the frozen record (anchor `8e0d9cb4…`). The exact upper bound is primary; the normal CI is reported only under its count condition.
- **Failed units:** AUDIT-FAILED and NOT-ASSESSABLE use the worst case in the bound.
- **Wording:** θ_D is disagreement, never "an error rate".

## 5. Decisions requested (in order)

1. **EG-2:** authorize the SLICE-VIEW amendment v2.7 (lossless, hash-bound, verifier-checked multi-line view), or choose another resolution. **The canary cannot run validly without a resolution.**
2. **EG-1:** authorize `p3b_s5_r7_orchestrate.py` (tests first, reusing the production functions).
3. **Approve** the runbook (§1), the prompt template (§2), the canary (§3) and the audit protocol (§4), with any amendments.
4. **Then** the separate **S5 execution authorization**: the canary first; if it passes the stop rule, the 396 batches.

**Traceability:** addendum v2.6 §5 (W1–W8), §5.8 (I(run)), §6.1 · RI-1 (`audit-p3b/20260927_R7-READ-INTEGRITY-CHARACTERIZATION.md`) · Freeze 2 (`audit-p3b/20260928_FREEZE-2-RECORD.json`) · B1 v2 · EP-01.

## 6. Runbook corrections under v2.8 (G-LOG-0106; appended 2026-09-29; §1 above stays as history)

| # | Step (corrected) | Source |
|---|---|---|
| 1 | `orchestrate prepare B --emit <dir outside the repo> --state P3B-STATE.json` (views, the reading runs' I(run), the prompts; hub prompts carry the §5.9a REQUIRED LINES and every prompt carries the RECORD IDS block) | EG-1, EG-3, EG-5b |
| 2 | `python3 -B scripts/p3b_s5_state.py transition B DISPATCHED` | unchanged |
| 3–5 | Dispatch as §1 steps 3–5. `freeze-units B L` freezes the SYNTHESIS I(run) after the UNITS-VALIDATED marker | unchanged |
| 6 | The marker command **runs the tool**: `: S5-ORCH ASSEMBLED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <YYYY-MM-DD> --model-id <served id>`, then `: S5-ORCH FINAL-VALIDATED batch=B; python3 -B scripts/p3b_s5_r7_orchestrate.py assemble B --date <same> --model-id <same> --check`. claim-evidence and S3-LINT are **not** assembled; they stay in the final runs' directories | EG-5 (§2.7) |
| 7 | `orchestrate archive B --session <session.jsonl>` → `orchestrate freeze-final B` (refuses if the assembly ≠ the ASSEMBLED printed hashes) | EG-1, EG-5 |
| 7a | `orchestrate coverage B` (RI-1b, §5.9a: per-run SLICE-VIEW coverage, **report-only**, never a failure; counts on stdout) | EG-3 |
| 7b | `python3 -B scripts/p3b_s5_state.py transition B PROPOSED --reason "assembly + freeze-final complete; WITNESS-DIGESTS.json sha256 <h>"`. On a freeze or digest failure: `transition B FAILED --reason "<cause>"` with its failure class. On an interrupted dispatch: `transition B INCOMPLETE` | **EG-10** |
| 8 | `orchestrate verify B` | unchanged |
| 9 | BATCH-PASS → `transition B VERIFIED --evidence audit-p3b/S5-VERIFY-<B>-R7.json` (attempt m ≥ 2: `S5-VERIFY-<B>-R7.A<m>.json`). BATCH-FAIL → `transition B FAILED --reason "<tags>"` with the mechanically derived class. BATCH-UNDETERMINED → **no transition**: restore the archive and re-verify (class U) | EG-7, EG-6, EG-10 |
| 10 | On FAILED or INCOMPLETE with class X, A1 or A2, below M = 4 and with no VERIFIED entry ever (FAILED/INCOMPLETE are written with `--failure-class C --failure-signature <sha256 of the failure-string set>`): `orchestrate retire B --cause-class C --reason <G-LOG or cause> [--staging <new dir outside the repo>]`, then `state new-attempt B --cause-class C --retirement ledger-p3b-r2/<B>-R7.A<m>/RETIREMENT-COMPLETE.json --retirement-sha256 <printed>`, then step 1 in a **fresh orchestrator session** (freeze-final refuses a retired transcript digest). Classes H, D, S → STOP for a human act | EG-6 (§2.8) |
| 11 | After every 20 completed post-canary attempts: `state wsys --record`. A stop is recorded durably (WSYS-STOP, class D) → no new dispatch; resumption only by a human act: `state wsys-resume --glog G-LOG-nnnn --reason R --actor human:<name> [--count-from all|resume]` (default cumulative) | EG-6 W-SYS |
| 12 | VERIFIED → AUDITED (§21: `p3b_s5_audit_record.py --batch <B> --run <B>-R7 --findings FILE --sample SAMPLE`; AUDIT-UPHELD → `P3B-CORRECTIONS.jsonl`, R-I; firewall rules) → ACCEPTED only through an H-06 tranche (`p3b_s5_accept.py --tranche …`); a §21 FAIL or H-06 rejection → FAILED, class H, no re-run | EG-9 (G-LOG-0106 (g)) |
