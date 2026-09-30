# S5 operations specification (O-8, O-9, O-11, O-18, O-21)

| Field | Value |
|---|---|
| Status | operational consolidation of the **approved** S5 plan v2.3.2 (`audit-p3b/20260925_0106_s5-plan-v2.3.md`, sha256 `f89a1682…c7de1`; §P approved, G-LOG-0041). **Adds no parameter and changes none.** Where this text and the plan differ, the plan governs |
| Authorization | infrastructure construction and review only (G-LOG-0041). **This document authorizes no S5 execution** |

## 1. Batch lifecycle and acceptance (O-11; plan §B.5–§B.8)

States live in `P3B-STATE.json` (`scripts/p3b_s5_state.py`):

```
PREPARED → DISPATCHED → PROPOSED → VERIFIED → AUDITED → ACCEPTED
             │              │          │          └→ FAILED → re-run <batch>-R2.2 (same frozen slices)
             └→ INCOMPLETE → new run (output kept)
```

1. **Prepare.** `scripts/p3b_s5_prepare.py --materialize OB####` rebuilds the batch's slices, verifies each against the manifest hash, quarantine-checks them, and writes the slices plus `P3B-BATCH-SNAPSHOT.json` once. The first dispatch freezes `_batch_manifest_p3b_r2.jsonl`.
2. **Dispatch.** One agent per batch, under the S5 contract (`prompts/<stamp>_p3b-agent-contract-r2.md`). Private scratch `/tmp/p3b-s5-scratch/<batch>/`; no helpers; model per the model rule.
3. **Verify.** `scripts/p3b_s5_verify.py` runs on 100% of batches: schema, G-01…G-12, the G-08 join, hub checks, F1–F3, and load and research-read alerts. **Load and alerts never fail a batch** (§20 unchanged).
4. **Audit** (§21 and OMQ-09).
   - `scripts/p3b_s5_audit_sample.py` draws the per-batch sample:
     - every CONTESTED / HOMONYM-SPLIT label;
     - seeded records up to 10% (minimum 5, at least one object record);
     - every HYPOTHESIS / STRUCTURE-CANDIDATE / SCHEMA-LIMITATION / METHODOLOGICAL-DEFICIENCY record, plus at least 2 others;
     - F3: at least 30 Stage-2B occurrences for labels with more than 100.
   - Seeds are streams from root 20261100 by manifest index.
   - One independent audit agent covers each group of 5 consecutive batches, including the §21 item 3 cross-batch audit.
   - Reports go to `audit-p3b/` (append-only).
5. **Accept.** `scripts/p3b_s5_accept.py` records a per-batch H-06 decision (human decision text, reference, `decided-by`) in `audit-p3b/S5-ACCEPTANCE.jsonl`.
   - It refuses unless the batch is VERIFIED and AUDITED.
   - It never auto-accepts.
   - Any PROTOCOL-VIOLATION found by the audit fails the batch.
6. **Recovery.**
   - A failed batch keeps its outputs and is re-run as `<batch>-R2.2` from the same frozen slices.
   - An interrupted batch is marked INCOMPLETE and re-dispatched as a new run.
   - Resumption uses `P3B-STATE.json` and the manifest only.

## 2. Test pass (O-8; plan §C.2)

**Order:** after S5a, over the frozen frame of primary non-CORPUS TEST-DEFINED records, with COMPARISON records excluded (`scripts/p3b_s5_testpass.py`).
1. The frame is sorted by record id and hashed.
2. `random.Random(20260927).shuffle`; the permutation is hashed.
3. `test_plan_sha256` values go into `P3B-INPUT-MANIFEST.json`.
4. Run strictly in order.
5. **Fixed 20% prefix**, with no lowering.

**Load and caps:**
- The load unit is the distinct hit keys of the union of the B and D lexical searches over **discovery** files.
- A non-CORPUS test with load > 4,081 is skipped as `LOAD`. It keeps its place and is not replaced.
- Reads are capped at 8,633,127 bytes per test; a test that reaches the cap is `LOAD-PARTIAL`, and no STATUS is assigned from it.
- **CORPUS tests always run in full.** An over-load CORPUS test raises `P3B-ESC` (load) to the human **before** it runs. The human authorizes a budget covering the complete test, or invokes stop-the-line.

**Other rules:**
- Test-pass runs use run ids `OT####-R2`, and read only through `p3b_read_source.py` → `discovery_resolver()`.
- Test-pass verification and the H-16 reporting fields (`independence_basis`, `authoring_session_independence: NOT-ASSESSED`) go on every SUPPORTED-IN-CORPUS or ≥ RECURRENT record.

## 3. Repeated-run stability (O-9, O-21; plan §K)

All of this runs in `scripts/p3b_s5_stability.py`.

- **Object rerun:**
  - 97 labels, a seeded (20260928) proportional 5% over the 24 strata plus the purposive group; group size max(1, round-half-to-even(0.05·n)).
  - Fresh agents, same contract and slices, same model. Run ids `OB####-R2S`, blind to the primary outputs.
  - Rerun records are **COMPARISON** records: verified and audited like batches, never ACCEPTED as P3b results, never used in S5a, the test pass or the S5c baseline.
- **Metrics:**
  - exact agreement, Cohen's κ and PABAK, design-weighted;
  - bootstrap within the 25 groups, singleton groups pooled, root 20260930;
  - `METHOD-INSTABILITY` if the κ 95% CI upper bound is < 0.40 (a floor only).
- **S5a same-model re-analysis:** 20% of all blinded units (root 20260929), blind to role. It never feeds any cell.

## 4. Cross-scale audit for S5a/S5b (O-18; §21 item 6)

1. An independent agent re-examines **every CORPUS-scale record** and a seeded 20% of CROSS-OBJECT findings. That seed is a stream from root 20261100, after the batch streams.
2. It checks, for form not truth:
   - the §9E mandatory content;
   - independence per A.8 (an occurrence's sources and labels are disjoint; no SELF-DERIVED, DESCRIPTIVE or control occurrences);
   - that the control-comparison citation (`cell_id`, `results_sha256`) matches `audit-p3b/S5A-CELL-RESULTS.json`, with the §9E.2 item 8 derivation for CORPUS records;
   - that no finding rests on a hub's absence resolution;
   - that ISC routing is applied (sameness between members recorded as EQUIVALENCE-CLASS, never ISC);
   - that claims stay within claim scope A/B.
3. The G-13 script (`scripts/p3b_s5a_g13.py`) runs on 100%. The agent audit supplements it.

## 5. H-19 protection during operations (plan §M)

Required before every batch and pass:
- `scripts/p3b_s5_h19_guard.py seal`;
- `scripts/p3b_s5_h19_guard.py grep` over S5 tooling;
- `inputs` on every §19.5 artifact produced;
- the quarantine scanner over the batch's agent inputs.

After every batch: `refusals` over its read log.

**H-19 ruling A (G-LOG-0045; P3B-ESC-0001):** H-19 stays SEALED. There is no unseal, no S5c scoring, no use of H-19 predictions and no hold-out material anywhere in the production path. Reporting on H-19 is counts and booleans only. S5c requires a separate human governance decision, and authorizing S5 production does not authorize it. The two lanes are separate: the escalation quarantines H-19/S5c only.

**Input rules (G-LOG-0045 items 1–3):**
- Family `.md` files of S5-population labels are readable only for the purpose `s5-slice-construction`, never as a research source.
- Quarantine is recorded as counts only.
- An S-id range whose interval spans a hold-out S-id counts as a hold-out citation. Affected slice input records are withheld and counted in the slice's `quarantine` field and in the manifest header. Agents never write S-id ranges.

Before any unseal request: `scripts/p3b_s5_freeze.py` writes `audit-p3b/S5-DISCOVERY-RECORDS-FREEZE.json`, covering primary records and the prediction set, with COMPARISON records and re-analysis dispositions excluded.

## 6. Dispatch runbook (per batch; applies only after a governance entry authorizes execution)

Steps run in this order; any failure stops the batch and is reported. `<B>` = batch id (e.g. OB0004), `<R>` = `<B>-R2`.

1. **Gate check.** A governance entry authorizes execution of `<B>` (G-LOG-0041 does **not**). The pass plan (O-12) is human-approved. `_batch_manifest_p3b_r2.jsonl` and the batch contract hashes are recorded in the governance log. The manifest is frozen from the first dispatch onward.
2. **Protection.** Run `p3b_s5_h19_guard.py seal`, then `grep`, then `inputs` on the manifest. Each must exit 0.
3. **State.** `p3b_s5_state.py init` (once, before the first batch). It sets every batch to PREPARED, so no transition is made here.
4. **Materialize.** `p3b_s5_prepare.py --materialize <B>` (a one-time write into `_batch_input_r2/s5/<B>/`, with slice hashes verified against the manifest). Then run `p3b_s5_quarantine_scan.py scan _batch_input_r2/s5/<B>/`, which must exit 0.
5. **Dispatch.** Transition to DISPATCHED. Start one agent, with no helpers, under the batch contract. Its inputs are the contract plus `_batch_input_r2/s5/<B>/`; its scratch is `/tmp/p3b-s5-scratch/<B>/`. The model rule applies.
6. **Proposed.** When the agent's outputs exist in `ledger-p3b-r2/<R>/`, transition to PROPOSED. If the agent was interrupted, transition to INCOMPLETE and re-dispatch as a new run.
7. **Verify.** Run `p3b_s5_verify.py --batch <B>` (a re-run adds `--run <B>-R2.<n>`) and `p3b_s5_quotes.py --batch <B>`, then `p3b_s5_h19_guard.py refusals` on the batch read log. Transition to VERIFIED with `--evidence audit-p3b/S5-VERIFY-<B>.json` (for a re-run, `S5-VERIFY-<run>.json`), which must be PASS and produced by the verifier. A FAIL leads to FAILED and a re-run as `<B>-R2.2`.
8. **Audit.** Run `p3b_s5_audit_sample.py`. The independent audit agent for the audit group checks the sample (§21, OMQ-09) and writes a findings file. `p3b_s5_audit_record.py --batch <B> --run <R> --findings <file>` turns it into `audit-p3b/S5-AUDIT-<R>.json`. Transition to AUDITED with that report as `--evidence`; it must be PASS (no PROTOCOL-VIOLATION) and produced by the audit-record script.
9. **Accept.** The human records the H-06 decision with `p3b_s5_accept.py --batch <B> --outcome ACCEPTED|NOT-ACCEPTED --decision "…" --decided-by "<human>" --reference <G-LOG entry>`.
10. **Record.** Add a session-log entry and, per audit group, a governance entry.
