# S5 canary restart (G-LOG-0108): STOPPED. Execution-integrity evaluation

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Execution-integrity evidence only. **No evidence for or against H1–H5**; no structure, model, effect-size or theory statement |
| **Scope** | OB0012 attempt 2 (5 SINGLE) + OB0114 attempt 1 (3 SINGLE + 2 UNIT + 1 SYNTHESIS; 1 EMPTY label by rule) = the restart's 11 runs. OB0012 attempt 1 is preserved Legacy(B), not counted here |
| **Session** | fresh orchestrator session `0014bd63-7791-4c14-a19a-66c64495353a`, primary working directory nrna1, served model `claude-opus-5-5` |
| **Commits** | OB0012 `a4579b241` · OB0114 `f4b5d1baf` (transcript archives stay outside git: `~/knowledgeos-witness-archive/OB0012/`, `…/OB0114/`) |
| **Stop rule** | **triggered**: 11 of 11 runs failed (threshold ≥ 6). No W-code occurred in ≥ 2 runs. Evaluated at batch verify, when the run-level failure strings first exist |
| **Measured labels** | **none.** A label is measured only when its batch attempt is ACCEPTED (BATCH-PASS → VERIFIED → §21 AUDITED → H-06 ACCEPTED; AM-2), a later human-gated phase. Both batches are FAILED |

## 1. Per batch

| Batch | Attempt | Verify | Failures | State | Class (frozen EG-6 mapping) | Signature |
|---|---|---|---|---|---|---|
| OB0012 | 2 | BATCH-FAIL (`audit-p3b/S5-VERIFY-OB0012-R7.A2.json`) | 195 (149 distinct normalized) | FAILED | **A1**: the highest strings are `R7-W … call N (Edit): W8` and `R7-W W5 <run>: … changed after production` (both L03); the rest are A2 | `3357786973937f64bd5af6b7587eba59795849cb0737e70c105eb675bf125323` (≠ attempt 1 `cc4c64c2…`, so no RR-7) |
| OB0114 | 1 | BATCH-FAIL (`audit-p3b/S5-VERIFY-OB0114-R7.json`) | 145 (106 distinct normalized) | FAILED | **A2**: only `R7-E` (not W6), `R7-U <label>:`, `R7-R` (not EMPTY), `G-05`/`G-09`/`G-12`, `SCHEMA` | `f0ae8edd51bbae3e2cf63a674f4034439b36f0034149497a9cbdec63bdf4722c` |

No S, D, H or X string occurred in either batch. No string was unmatched by the mapping.

**Signature normalization.** The documents do not define "normalized". The attempt-1 convention is used: the sorted set of failure strings with `S\d{4}` → `S####` and `call \d+` → `call N`, joined by `\n`, then sha256. It reproduces attempt 1's recorded `cc4c64c2…` exactly.

**Failure families (counts only):**
- OB0012: R7-E 120 (claim evidence paths; acknowledgement tokens ≠ witnessed page tokens, 6 of them in L04); R7-R 58 (no pair-evidence check); SCHEMA 5 + G-05 5 + G-09 5 (register records without `output_layer`, L04); R7-W 2 (L03).
- OB0114: R7-E 97; R7-U 17 (SYNTHESIS `status_basis` sources outside the object's cited sources); R7-R 12; G-05 6, SCHEMA 5, G-09 5 (register records without `output_layer`, L02), G-12 3.
- LOAD alert in both batches: reader calls 41 vs 24 predicted (1.71×) and 58 vs 33 (1.76×). Stage-1 bytes equal the prediction (ratio 1.0). An alert, not a failure.

## 2. Per run

W1 = no W1 failure string for the run. W8 = witnessed unauthorized calls. Tools = witnessed tool uses / harness count. All 11 transcripts: chain intact, 0 parse errors. RI-1b coverage (report-only): 11/11 COVERED, 0 uncovered lines.

| Run | Role | W1 | W8 | RI-1b | Reader pages | Tools | Elapsed |
|---|---|---|---|---|---|---|---|
| OB0012-R7-L01 | SINGLE | OK | 0 | COVERED | 15 | 68/68 | 14.1 min |
| OB0012-R7-L02 | SINGLE | OK | 0 | COVERED | 6 | 46/46 | 8.8 min |
| OB0012-R7-L03 | SINGLE | OK | **1** (Edit) | COVERED | 2 | 43/43 | 9.2 min |
| OB0012-R7-L04 | SINGLE | OK | 0 | COVERED | 16 | 69/69 | 12.2 min |
| OB0012-R7-L05 | SINGLE | OK | 0 | COVERED | 2 | 41/41 | 7.6 min |
| OB0114-R7-L01 | SINGLE | OK | 0 | COVERED | 15 | 53/53 | 13.7 min |
| OB0114-R7-L02 | SINGLE | OK | 0 | COVERED | 3 | 39/39 | 6.8 min |
| OB0114-R7-L05 | SINGLE | OK | 0 | COVERED | 3 | 39/39 | 8.9 min |
| OB0114-R7-L03U01 | UNIT | OK | 0 | COVERED | 30 | 74/74 | 9.0 min |
| OB0114-R7-L03U02 | UNIT | OK | 0 | COVERED | 7 | 51/51 | 4.2 min |
| OB0114-R7-L03S | SYNTHESIS | OK | 0 | COVERED | 0 (no corpus reads, by role) | 51/51 | 11.3 min |

**Reader pages vs plan.** Only batch-level predictions exist (§1: 24 and 33 reader calls). Each SINGLE reads its files at step 1 and again at step 7, which accounts for the ratio (for example, L05 of OB0012: 1 file × 1 page × 2 steps = 2). This is stated as a fact of the call counts, not as a diagnosis of the prediction.

## 3. What the instrument demonstrated (FACT)
- **The F-1/F-5 repair (R-1) held.** The dispatch-cwd guard passed; `pwd -P` equalled `--dispatch-cwd` (nrna1). Every reader call resolved this repository, so there were 0 refusals and 0 W4 in all 11 runs, and 0 writes outside nrna1.
- **The F-3 repair (R-2) held.** The only W8 is an Edit call (L03 of OB0012). There was no past-end-of-file probe in any run.
- **The F-2 repair (R-3) held.** No run refused on the addendum's status text.
- **Dispatch, provenance and freezes worked.** Binding lines, canaries and prompt delivery worked. The UNITS-VALIDATED → freeze-units → SYNTHESIS ordering worked (no W5 string). The EMPTY object was made by rule with no R7-R EMPTY failure. The witness count matched the harness count for 11/11 runs. freeze-final gave 274 and 316 records.
- **Verifier and state machine.** They produced mechanical, reproducible failure records. The class followed from the frozen mapping with no residual judgement, and the state chain stayed valid.

## 4. What it did not demonstrate
- **No run produced an output that passes the content and consistency gates.** All 11 runs carry A2-type strings, and OB0012 L03 also carries A1 strings.
- **Agent-reported, not verified by the orchestrator** (it read no run output): three runs (OB0114 L03U02, L05, L03S) said their inputs did not give the schema of some output files. L03S said the U01 unit records carry no facts or stage-2 dispositions, so it escalated 112 dispositions. These are observations for the canary gate. They are not a diagnosis.
- **Met its spec?** The **execution instrument** (dispatch, reader, witness, freezes, verifier, state machine) met its specification in this canary. The **measurement** did not: 0 of 11 runs passed the gates. Whether that is an instrument or prompt defect (X, admissible to repair only at the canary gate, RR-6) or an agent-output defect (A1/A2) is **not decided here**. By default it is A1/A2 (EG-6 residual case 1).

## 5. Orchestrator deviations in this session (recorded, all visible in the transcript)
1. **Working directory.** The first command's `cd` moved the shell into CR. It was restored to nrna1 before any prepare or dispatch. After that, every command ran `cd <CR>` inside a subshell, so the directory the agents inherit stayed nrna1.
2. **Precondition 3 (manifest sha).** The prompt said `93b78526…`; the manifest on disk is `963ba6ad…`. The session STOPPED and asked. The human confirmed `963ba6ad…`, the manifest after the R-4 retirement (erratum `a120866b9`).
3. **OB0012 markers.** The first ASSEMBLED and FINAL-VALIDATED commands began `(cd … && : S5-ORCH …`, not `: S5-ORCH …;`, so the witness did not recognize them.
   - freeze-final refused and wrote nothing. Archive and coverage had already run once.
   - Steps 5 → 6 → 7 → 7a were re-issued in the correct form and order. `assemble` printed identical hashes.
   - The witness takes the last recognized marker, and no `W5 batch` string resulted.
4. **Verify report name.** The default `S5-VERIFY-OB0012-R7.json` belongs to attempt 1 (append-only), so step 9's A2 name was passed with `--out`.
5. **SYNTHESIS re-prepare (step 4d).** It ran without `--state`: the state gate refuses after DISPATCHED, and the brief's 4(d) names no `--state`. The 5 reading-run prompts re-emitted byte-identical.
6. **L03S dispatch.** Its `pwd -P` check was issued in the same parallel block as the dispatch, not strictly before it. It returned nrna1.
7. **Signature.** It uses the attempt-1 normalization (§1). The documents do not define it.

## 6. STOP
The canary stops here, at the **canary-evaluation gate (human)**.
- Not done, and not to be inferred: no retire, no new attempt, no retry, no repair of tools, contract, verifier or state, no other batch, no §21 or H-06, no R-5 cleanup.
- Any EG-6 re-run needs a human act and a fresh session. Any instrument repair (runbook or prompt) is admissible only at this gate (RR-6).
- The later phase (full S5 measurement, research loop) is governed separately and is inert here.
