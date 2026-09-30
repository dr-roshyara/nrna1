# C13 — Research state machine and audit · v1.1

**Treatment:** F-SPECIFIC state; audit ADAPTED from v3.5 A8 and P3B §21. **Code:** `f_common.ALLOWED_FROM`,
`f_transition.py`, `f_audit.py`, `f_status.py`.

## 1. Per-file state machine
As protocol v1.1 §F-6. State transitions are evidence-producing events appended to `F-SERIES-STATE.jsonl`; the current
state of an F-ID is its last event; history is never edited. `f_status.py` answers "the last contiguous AUDITED F-ID
is …; the next F-ID is …" from the ledger alone.

## 2. Mechanical audit (every file)
`f_audit.py F#### --run RUN` re-derives: state-ledger legality · identity · read coverage + integrity record ·
content inventory (C02) · Level-1 records (C04 §2) · Level-2 analysis + lens checklist + cross-file dispositions
(C04–C08, C05) · Level-3 records (C10) · independent audit when due · outside-lane changes recorded (attribution
UNKNOWN). Output `AUDIT.json` (latest) + `AUDIT-LOG.jsonl` (history).

## 3. Independent audit (first text F-ID, every fifth, every checkpoint)
A fresh agent without access to `ledger/F####/` reads the file through the reader (run `FR-F####-9NN`), builds its own
inventory into the scratchpad, then compares with the stored records: missed units or definitions, category counts,
definition fidelity, contributions, lens checklist, overstated analysis or research. It writes
`INDEPENDENT-AUDIT.json {run_id, auditor_run, verdict: CONFIRMED|DISCREPANCY, discrepancies[], utc, model_id}`.
DISCREPANCY is recorded, not fixed by the auditor; the F-ID goes AUDIT-FAILED and re-enters at the failing stage.

## 4. Checkpoints
`checkpoints/CP-##/STATE.jsonl`: `PLANNED → OPEN → RUN → AUDITED`. OPEN requires N new AUDITED text F-IDs since the
last checkpoint (FD-11). RUN performs C05 §2, C09 §2, C11. AUDITED requires a checkpoint audit (mechanical +
independent). Checkpoint findings are reported to the human before the next file after the checkpoint is opened.
