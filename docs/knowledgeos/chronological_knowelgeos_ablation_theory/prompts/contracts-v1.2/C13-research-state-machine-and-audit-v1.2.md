# C13 — Research state machine and audit · v1.2 (full text; supersedes the v1.1 C13)

**Superseded:** `contracts-v1.1/C13-research-state-machine-and-audit.md`, sha256 `3bacf6844615a3bea40395cb71a4a9e2c0b67adc99fc4df053b4e4c5ca8aba2d` (kept unchanged).
**Code:** `f_common.ALLOWED_FROM`, `f_common.record_event`, `f_integrity`, `f_transition.py`, `f_audit.py`, `f_compare_inventory.py`, `f_checkpoint.py`, `f_status.py`.

## 1. State ledger (F-07)
- `F-SERIES-STATE.jsonl` is append-only.
- Every line written under v1.2 carries `prev_hash`, the sha256 of the previous line's bytes, and `hash`, the sha256 of its own canonical JSON without `hash`.
- The 4,556 pre-v1.2 lines are sealed by `F-STATE-ANCHOR.json` (line count, sha256, last line hash).
- `verify_chain()` checks, for every line: the anchor, the hash links, the per-F `seq` continuity, that `from` equals the F-ID's previous state, and legality under `ALLOWED_FROM`.
- `record_event()` refuses to append to a broken chain. `f_status.py` refuses to report a restart point from a broken chain. Every gate refuses on a broken chain.
- **Run rule R-COMMIT:** the lane is committed after each successful gate on a real file. Git is the second record.

## 2. Transitions (F-01, F-08, F-17, F-20)
Order of checks in `f_transition.py`:
1. usage;
2. **legality**;
3. **approval** (every governing document's sha256 has an `APPROVED-FOR-EXECUTION` line);
4. **chain**;
5. **run**: the run of the previous event, except that READING starts one;
6. **human reference** for exceptions;
7. **processing order and checkpoint due** for READING and EXACT-DUPLICATE;
8. **freezes** of every stage before the target;
9. the stage's own evidence checks;
10. the **freeze** of the stage's artifacts, recorded in the event.

Re-entry after AUDIT-FAILED is allowed at READING, CONTENT-EXTRACTED, RECONSTRUCTED, ANALYZED or RESEARCHED. The run is kept; the stage re-freezes, and freezes of later stages are invalidated. **AUDIT-FAILED → AUDITED does not exist.** Re-extraction (CONTENT-EXTRACTED → CONTENT-EXTRACTED) needs `--human-ref`.

## 3. Independent L1 audit (F-06)
- **When:** at CONTENT-EXTRACTED, for the first text F-ID and every fifth (`f_compare_inventory.is_due`).
- **Auditor:** a fresh agent under C15, run `FR-F####-9NN`, in its own scratch directory.
- **Auditor artifacts:** it writes `AUDITOR-PAGE-DIGESTS.jsonl` (its own complete read, checked with the C01 coverage rules), `AUDITOR-INVENTORY.jsonl` (its own inventory, C02 schema), `AUDITOR-ATTESTATION.json` (role AUDITOR; it never reads the extractor's L1 artifacts), and optionally `AUDITOR-DISCREPANCIES.jsonl`.
- **Computation:** `f_compare_inventory.py` computes the comparison:
  - units where the auditor has a DEFINITIONAL (DEFINITION/TERM) or MATHEMATICAL (FORMULA/NOTATION/THEOREM) item and the extractor has none of that group;
  - units the auditor extracted but the extractor did not cover;
  - DEFINITION terms the extractor lacks;
  - invalid auditor items;
  - the category distributions of both sides.
- **Record:** it writes `INDEPENDENT-AUDIT-L1.json` and appends to `INDEPENDENT-AUDIT-L1-LOG.jsonl`, holding the verdict, the auditor coverage, the extractor's CONTENT-EXTRACTED freeze hashes, the auditor artifact hashes, the discrepancies and the human reference.
- **Verdicts:**
  - CONFIRMED requires `--human-ref`: the human accepts L1, and any listed discrepancy, in an F-LOG entry naming the F-ID.
  - DISCREPANCY blocks RECONSTRUCTED and leads to re-extraction with a human reference.
- **Verification:** RECONSTRUCTED and the audit **recompute** the comparison and compare it with the stored artifact. A forged, stale or two-field file is refused.
- **Independence** is procedural (a fresh context, a separate scratch location, no access to the extractor's L1 by rule), not statistical (same model family).

## 4. Mechanical audit (every file)
`f_audit.run_audit()` re-derives: approval, chain, freezes, run consistency, identity, and the stage checks for the state reached (read coverage + integrity record, isolation attestation, inventory, independent L1 audit when due, L1 records, L2, L3). For an exception state it checks the human reference. It records outside-lane changes with attribution UNKNOWN. **AUDITED and AUDIT-FAILED call it themselves**; AUDIT.json is written by the gate and is never an input.

## 5. Checkpoints (F-16)
- Cadence N = 50 AUDITED text F-IDs: this is the initial cadence for CP-01, reviewed empirically at CP-01 (RN-01).
- READING is refused while a checkpoint is due and not opened.
- `f_checkpoint.py open` writes `checkpoints/CP-##/{STATE.jsonl, POPULATIONS.json, HYPOTHESES.json}`, recording the data boundary, the mode and the snapshot hashes.
- The mode is MONITORING-ONLY until HDR-2 and HDR-3 are decided; `run` stays refused until then.
