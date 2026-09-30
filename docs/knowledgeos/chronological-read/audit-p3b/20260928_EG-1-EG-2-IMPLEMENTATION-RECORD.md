# EG-2 SLICE-VIEW (v2.7) and EG-1 orchestrator tool: implementation record (G-LOG-0104)

| | |
|---|---|
| **Kind** | Engineering evidence. ⚠ authority: generated. Engineering supplies evidence and never accepts its own work |
| **Authority** | G-LOG-0104: "EG-2 SLICE-VIEW v2.7 and EG-1 orchestrator authorized (tests first); package approved." |
| **Not done** | no dispatch, no canary, no production manifest or state change, no corpus content read |

## 1. EG-2: contract amendment v2.7 (SLICE-VIEW)

**Contract:**
- addendum v2.7 sha256 `9523c712efd8c0e67d701b7e0003b85bf3f683e457d2b15aa2d6e1a3b854b367`;
- it adds the §5.8 category `SLICE-VIEW`, §5.9, T119–T121 and DR-21;
- the static consistency checks give 73/73 PASS (the registry has 39 rows, 121 tests).

**Code:**
- `p3b_s5_r7_universe.py`: `slice_view`, `slice_view_parse`, `slice_view_violations`, the I(run) entry, and `ADDENDUM_SHA256`;
- `p3b_s5_r7_verify.py`: the Universe view check for every planned label.

**Tests:**
- `test_p3b_s5_slice_view.py`: 6 tests;
- `test_p3b_s5_r7_full.SliceView`: T119–T121.

**Production losslessness (read-only, rev7 slices, no corpus):**
- all 1,975 views parse back to their slice byte-exactly (0 violations);
- the longest line is 1,173 characters;
- one Read page of 25 lines is at most 25,961 characters;
- Reads per view: median 19, p90 36, max 5,950.

## 2. EG-1: `scripts/p3b_s5_r7_orchestrate.py`

**Subcommands:** `prepare`, `archive`, `freeze-units`, `freeze-final`, `verify`.

**Reuse:** only production functions (`U.input_manifest`, `U.slice_view`, `W.witness`, `W.witness_bytes`, `W.digests`, `p3b_s5_verify`). The tool dispatches nothing.

**Validation (`test_p3b_s5_r7_orchestrate.py`, 19 tests):** on the full synthetic batch, the tool replays the runbook (prepare → freeze-units → freeze-final).
- Its `INPUT-MANIFESTS.json`, `WITNESS*.jsonl`/`-DIGESTS.json` and views are **byte-identical** to the fixture's independently produced artifacts.
- The production verifier returns **BATCH-PASS** on the tool's artifacts.
- Refusals are tested: emit or archive inside the repository; a plan-hash mismatch; a differing view (never overwritten); a differing frozen I(run); state not PREPARED; a non-DECOMPOSED label; a missing unit record; no UNITS-VALIDATED marker; a hold-out token in a prompt; an archive rewrite that is not an append.

**Design notes:**
- The SYNTHESIS I(run) is frozen by `freeze-units`, because it hashes the unit records, which exist only then.
- The unit freeze is cumulative and may only extend the previous one (W7a).
- The final freeze is written once.

**Full suite:** 1,091 tests OK (1 skipped) before the orchestrator tests were added, plus the orchestrator tests 19/19.

## 3. New findings (reported, not solved)

| Id | Classification | Finding | Consequence |
|---|---|---|---|
| **EG-4** | Operational, **blocks the canary** | The production manifest header binds contract v2.6 + AF-1 (`49169328…`). The frozen addendum is now v2.7, so `U.revision_violations` refuses every production batch. Probe: `orchestrate._context(OB0012)` → REFUSED | A human act must authorize a narrow production rebind: manifest header `contract.sha256` → `9523c712…`, plus the P3B-STATE manifest rebind (like AG-2, only the contract sha changes). The views are written by `prepare` per batch |
| **EG-3** | Protocol, does not block the canary | 23 slices exceed 500,000 characters; 22 of them are hub labels, dominated by `search_records`. Their views need up to 5,950 Reads | Before any of the 14 hub batches: decide what a hub agent must read (a protocol question, not a tooling one) |

**Next:** a human act on EG-4 (the v2.7 production binding). After that, the separate S5 execution authorization: the canary OB0012 + OB0114, with the stop rule of ≥ 6 of 11 runs failed.
