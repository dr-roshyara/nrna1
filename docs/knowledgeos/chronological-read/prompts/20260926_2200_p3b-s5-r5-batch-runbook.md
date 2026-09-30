# P3b S5 revision-5 batch runbook (SPECIFICATION ONLY; G-LOG-0082)

**Status:** specification of the deterministic orchestration of ONE revision-5 batch.
- **It executes nothing.** It is valid only after revision 5 is **activated** and S5 execution is **authorized** by separate human acts.
- **Until then, no step of this runbook may run against the corpus.**

**Governing documents:**
- `prompts/20260926_2100_p3b-agent-contract-r5-addendum.md` (revision 5);
- `prompts/20260925_1204_p3b-agent-contract-r2.md` (revision 3, governing everything the addendum does not change).

**Machinery:**
- `scripts/p3b_s5_r5.py`;
- `scripts/p3b_s5_r5_verify.py`;
- `scripts/p3b_s5_verify.py` (the revision-5 gate);
- `scripts/p3b_read_source.py --mode bytes`.

**Invariants at every step:**
- R19: no step runs before its predecessor has validated.
- Records are append-only; frozen evidence is never modified.
- Corpus bytes pass only through the seal-aware resolver.
- **No ML** in any step.
- The packing order is an engineering order with **no chronological meaning**.

## Steps

| # | Step | Deterministic rule | Gate to pass before the next step |
|---|---|---|---|
| 1 | **Batch preparation** | the batch's slices (revision-5 manifest, activated) are hash-verified against the manifest. The production plan (written at activation) gives, per label, `path`, `files`, `row_sources`, `sizes` (all blob types), and for DECOMPOSED `packing_order` and `units` | slice hashes = manifest; plan hash recorded |
| 2 | **Label-path classification** | `dispatch_path(n_files, R(L) bytes)`: EMPTY (0 files) · SINGLE (≤ 600,000 B) · DECOMPOSED (> 600,000 B). This must equal the plan | recomputed = plan (the verifier re-checks) |
| 3 | **SINGLE dispatch** | one agent, run `OB####-R5-L##`, prompt = the revision-3 contract + the addendum. It reads R(L) with `--mode bytes` only. It writes the object, register, P1-gap records and one **file-reading record per required file** (`reading_state`, `ack_tokens`, `pair_evidence_checks`) | agent completes, or FAILED (recorded, never re-run in place) |
| 4 | **DECOMPOSED partition** | `partition(files, rows, sizes, packing_order)`: row-first next-fit + FFD fill, 600 KB, label-local, files never split. It must equal the plan's `units` | recomputed = plan |
| 5 | **Unit dispatch** | one agent per unit, run `OB####-R5-L##U##`. It reads only its unit's files, with `--mode bytes` | all units complete, or FAILED |
| 6 | **Evidence-record generation** | each unit writes one file-reading record per assigned file, as in step 3. **No label-level judgments** | exactly one record per assigned file |
| 7 | **Unit validation** | byte coverage per unit (`byte_page_coverage`); `reading_state` WHOLE-FILE only with complete coverage; `ack_violations` empty; read log levels 3 only (no level 4); the orchestrator's transcript scan (`scan_level`) shows no violation. Record `units_validated_utc` | every unit valid; otherwise the label is FAILED and synthesis is **not** dispatched |
| 8 | **S1** | `required_pair_checks(slice pairs, label, R(L))` ⊆ the checks recorded in the records; each NO ⇒ a planned VERDICT-EVIDENCE-CONFLICT register record + escalation (§11.5) | no missing check |
| 9 | **S2** | births may cite only row sources (`s2_violations`); non-row candidates remain P1-gap records | applied in synthesis and re-checked in step 12 |
| 10 | **S3** | `s3_lint` on the object + register **before submission**: S-id ranges, layer-A register pointers, quarantine hits. **Failure blocks submission.** Write `{result, records_sha256}` | PASS with the hash of exactly the submitted records |
| 11 | **Records-only synthesis** (DECOMPOSED only) | run `OB####-R5-L##S`, dispatched **after** `units_validated_utc` (record `synthesis_dispatched_utc`). Inputs: the revision-3 contract, the addendum, the slice, all unit records, the plan. **Zero reader calls.** It writes the object, register and P1-gap records under S1–S3 | synthesis completes; step 10 PASS |
| 12 | **Synthesis validation** | `r5_checks` (reading state, edge classes, S1, S2, S3, EMPTY) on the final object | no R5 failure |
| 13 | **Provenance freezing + batch assembly** | freeze (hash + UTC) the plan, records, logs, lint reports and objects. Assemble `ledger-p3b-r2/OB####-R5/`: each label's final object, register and P1-gap records; the union of the batch's revision-5 read logs (entries keep their run ids); `R5-BATCH.json` (format in `p3b_s5_r5_verify.py`) | frozen hashes recorded; assembly deterministic |
| 14 | **Final verification** | `p3b_s5_verify.py --batch OB####` on the assembled run `OB####-R5` (the revision-5 gate), plus the production quote checker | verifier PASS (all historical gates + gate R5) |
| 15 | **Failure handling** | a failed unit, label or check is **recorded, never repaired in place**. A re-run needs a new run id and a human act. A NOT-CONSUMED file follows item 10 (NOT-CONSUMED-ESCALATED); a binary file follows its per-file decision record | failure recorded with its class |
| 16 | **Stop conditions** | **stop the batch immediately** on: a hold-out refusal or SEAL-BREACH-ATTEMPT (scan level 2); a level-4 exposure; a production-ledger or state change outside the batch's own runs; a character-mode read in a revision-5 run; a failed guard | stop; escalate (P3B-ESC); no further dispatch |

## Path summary

```
R(L) = ∅          → EMPTY      → object: NOT-EVIDENCED-IN-CAPTURE births, no FOUND / GENUINELY-UNDEFINED,
                                   escalation EMPTY-REQUIRED-SET (R17); no runs
R(L) ≤ 600 KB     → SINGLE     → L## agent (byte mode) → records + object → S3 → validation
R(L) > 600 KB     → DECOMPOSED → partition → U## units (byte mode) → records → unit validation
                                   → S1 → records-only synthesis L##S → S2/S3 → synthesis validation
```

**No hierarchical synthesis. No ML.** The pre-registered probability audit (addendum item 17) runs on the frozen sample, blind to these outputs, and completes before the P4 hand-off. It is not part of the batch.
