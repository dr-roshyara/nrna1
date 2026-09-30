# Governance Finding — `admit.py --audit` crash (READ-RECEIPTS row 8)

| | |
|---|---|
| **Kind** | investigation only (read-only). ⛔ Not a gate verdict, not a certification of any research unit |
| **Investigator** | governance / control-plane session. ⚠️ Same model family as the research session |
| **Evaluated state** | HEAD `e6087b615` (retrofit batch 1), plus `git archive` snapshots of `cd845b6ab` and `d60efe05f` |
| **Trigger** | the research session reported `admit.py --audit` exit 1, `KeyError: 'sha256_at_read'`, after batch 1, and stopped the batch |
| **Modified** | nothing in the live workspace. All experiments ran on `git archive` copies in the governance scratchpad |

## Findings

| # | Finding | Evidence |
|---|---|---|
| **A-1** | **Both audit symptoms come from one row.** `evidence/READ-RECEIPTS.jsonl` row 8 is a **VOID annotation** (`"status": "VOID — ADMITTED BUT NOT READ"`, `correction_of: "F0047 receipt, unit U-0003-SEQ"`). It is not a receipt: it has no `manifest_hash` and no `sha256_at_read`. `cmd_audit` treats every row as a receipt, so the row is counted as *"bound to a DIFFERENT manifest hash"* (a false stale finding), and the drift loop then raises `KeyError: 'sha256_at_read'` | row key sets (rows 1–7 and 9–18 are receipts; row 8 has a different shape); `evidence/admit.py` `cmd_audit` (stale test on `manifest_hash`, drift test on `r["sha256_at_read"]`) |
| **A-2** | **The crash predates batch 1.** The VOID row entered in `cd845b6ab`, the first commit with 8 receipt rows. `--audit` exits 1 with the same `KeyError` at `cd845b6ab`, `d60efe05f` and `e6087b615`. ⛔ **No evidence-binding audit has completed since `cd845b6ab`**, a span that includes the F0032 controlled test, the F0026 retrofit, the F0027/F0001/F0010 pilot and batch 1 | `git archive` + `--audit` at each commit |
| **A-3** | **The 17 genuine receipts are sound, as far as this check reaches.** On a copy with row 8 removed, all 17 are bound to the current manifest hash (`54977c6e1213028d…`), and none reports a file changed since reading. That includes the five batch-1 receipts (rows 14–18, F0002–F0006, unit `U-0007-RETROFIT-B1`) | copy run: no stale line, no drift line |
| **A-4** | ⚠️ **Repairing the crash will NOT make the audit pass.** On the same copy the audit runs to completion and reports **`STATUS: BINDING_FAILURES_PRESENT`, exit 3 (STOP)**, because the research registry diverges from the canonical manifest on 9 file IDs: `F0031 F0032 F0033 F0034 F0035 F0036 F0037 F0038 F0040`. That is the known, unrepaired RC-H-04 incident; C-5 is held. ⭐ **"Audit completes" (it produces a binding verdict) and "audit passes" (`BOUND`) are different conditions.** Until C-5, the expected verdict of a completed audit is STOP, with exactly this divergence | copy run output |
| **A-5** | The VOID row itself records an instrument observation: `admit.py --receipt` performs **admission and writes a read receipt in one step**, so a receipt can exist for a file that was never opened (this is how the F0047 row came about). Recorded; not changed | row 8, field `instrument_defect_noted` |

## ⛔ What this record does NOT establish

- **The copy result is not a production verdict.** Removing row 8 was a diagnostic on a scratch copy. It shows what the audit *would* evaluate once it can pass that row. It does not certify any receipt, any research unit, or batch 1.
- It does not establish that any file was *read*. A receipt proves admission against a manifest hash (see A-5), not reading.
- It does not repair `admit.py`, and it does not touch research evidence or registry state.

## Consequences recorded

- **Batch 1 = research-completed evidence, not governance-certified.**
- Repair authority for `admit.py` did not exist at the time of this finding (L0-DEC-15 covered only runner, `gates.yaml` and the door). See **L0-DEC-18**, recorded after this finding.
- The release condition for batch 2 must separate instrument operability from the known divergence (A-4). See **L0-DEC-19**.

---

*Traceability:* receipt row shapes at `e6087b615` · `git archive` audits at `cd845b6ab` / `d60efe05f` / `e6087b615` (exit 1, `KeyError`) · a copy of `e6087b615` with row 8 removed (exit 3, `BINDING_FAILURES_PRESENT`, 9 divergent IDs) · `evidence/admit.py` `cmd_audit`.
