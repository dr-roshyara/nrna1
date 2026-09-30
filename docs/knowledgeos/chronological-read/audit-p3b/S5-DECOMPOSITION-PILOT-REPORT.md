# S5 decomposition pilot — report (non-production)

**Authority:** human ruling "AUTHORIZE DECOMPOSITION PILOT", G-LOG-0052.

**Pre-registration (frozen before execution, commit `de6accb6c`):** `audit-p3b/20260925_1325_s5-decomposition-pilot-preregistration.md`, with plan `pilot-s5-decomp/PILOT-PLAN.json` (output `ac721f3b…`) and pilot contract `prompts/20260925_1325_…` (`468ec116…`).

**Isolation:** all pilot output and logs are under `pilot-s5-decomp/`. No production contract, manifest, state, batch or run was modified. OB0004 R2 / R2.2 / R2.3 are untouched. H-19 was not accessed; S5c was not started.

**Evidence files:**

| File | sha256 |
|---|---|
| `pilot-s5-decomp/PILOT-COVERAGE.json` | output `ead54f2f…` |
| `pilot-s5-decomp/PX0004-S01/objects.jsonl` | `5eec5000…` |
| `pilot-s5-decomp/PX0004-S02/objects.jsonl` | `7cf0e345…` |
| `pilot-s5-decomp/PX0004-A01/audit-report.json` | `cb899cf4…` |

Also: the unit records and read logs under `pilot-s5-decomp/PX0004-U0*/`.

## A. Mechanical coverage (Property A)

| | Control (constitution) | Target (step-verify-programme) |
|---|---|---|
| Required files / bytes (stage-2 ∪ all row sources) | 6 / 304,733 | 39 / 1,760,662 |
| Partitions (budget 600 KB; files never split) | 1 (U01) | 3 (U02 599,718 · U03 599,100 · U04 561,844) |
| Pages read and verified | 18 | 106 (33 + 33 + 40) |
| Bytes consumed by the units | 304,733 | 1,760,662 |
| Complete files | 6 / 6 | 39 / 39 |
| Missing coverage | 0 | 0 |
| Duplicate page reads | 0 | 0 |
| Pages read outside the unit | 0 | 0 |
| Hash failures | 0 | 0 |

- **Integrity:** the aggregate coverage is reconstructed by `p3b_s5_pilot.py check` from the persistent read logs alone.
- **Provenance:** 124 rows, each tracing S5 batch → label → required file → partition → page → reading event (utc, page hash, session).
- **Continuation (U02):** session 1 read 3 files (13 pages) and stopped. Session 2, a fresh context, resumed from `unit-state.json` and read the remaining 3 files (20 pages). **0 cross-session duplicate pages; complete after resume.**
- **No thinning:** the mandatory set is a superset of the frozen OMQ-14 set, and nothing was dropped.
- **Production verifier** (on a relabelled temporary copy with the union of read logs): **control PASS, 0 failures; target PASS, 0 failures.** READ-COVERAGE and G-04 both pass on the target, which a single agent failed in OB0004-R2.3. The only alerts are LOAD reader-call alerts: paging makes 2–3× more calls than the pre-paging prediction.

**Property A: satisfied on both labels, against the pre-registered criteria.**

## B. Control result: `knowledgeos-architecture-constitution-v01` (Property B)

**Baseline:** the OB0004-R2.3 single-agent object. **Decomposed:** PX0004-S01, built from the U01 records plus a 3-page confirmatory re-read (37,623 bytes).

**Pre-registered comparison:** 29 fields — **19 exact, 6 coarse, 4 different.** All four absence resolutions match exactly, as do semantic_status (with its rule), type_status, mathematical_status, primary_layer and tier.

**The auditor's dispositions of the 10 non-exact fields,** with its own whole-file reads:

| Disposition | Count | Fields |
|---|---|---|
| DECOMPOSED-UPHELD | 6 | four births of the same class (lexical, formal, operational, governance), the S1524 timeline class, escalations |
| JUDGMENT-CALL | 4 | births.conceptual (MOVED[S1020] vs ESCALATED[TIMESTAMP-ANOMALY]; both defensible under §11.2 / §14.2), secondary_roles, the two S1484 informal_meaning stage-2 keys |
| BASELINE-UPHELD | **0** | — |
| PROTOCOL-VIOLATION | **0** | — |

**Property B (control), under the frozen rule: SUCCESS.** There are 0 protocol violations and no BASELINE-UPHELD on a judgment field.

**Limits:**
- The control is small: 1 unit, 6 files. So it tests synthesis from records, not synthesis across several units.
- The baseline itself was never audited: R2.3 stopped at the verifier.
- Auditor and agents share one model family (claude-opus-5-5), so agreement is a lower bound (§21 item 8).

## C. Target result: `step-verify-programme`

- **Workload:** 39 files, 1,760,662 bytes, split into 3 partitions. Execution took 4 unit contexts (U02 ran as two sessions) plus 1 synthesis context.
- **Coverage:** complete (§A). Continuation behaved as pre-registered.
- **Synthesis:** PX0004-S02, built from records only, with 0 re-reads. It produced 1 object (33 stage-2 dispositions, 33 timeline points), 6 research records and 49 P1-gap records.
- **Production verifier:** PASS, 0 failures.
- **Independent audit** (seeded sample, cross-unit judgment fields, and one seeded file per unit re-derived by the auditor: S1531, S1518, S1860): 24 dispositions.
  - 14 ORIGINAL-UPHELD, 7 JUDGMENT-CALL, 3 AUDIT-UPHELD, **0 PROTOCOL-VIOLATION**.
  - The three AUDIT-UPHELD findings are minor and change no resolution, birth, status or whole-file claim:
    1. a miscount in the type_signature reason (25 / 8, not 27 / 6);
    2. a miscount in register record :2 (12 keys, not 10);
    3. an inconsistent date basis in the timeline placement of S1531 (it affects order only).
- **Target success criteria (pre-registration §4): met.** Property A holds, the verifier reports 0 failures, and the audit found 0 PROTOCOL-VIOLATION.
- **Unresolved issues:** the information-loss items in §E.

## D. Large-file probe: S2276 (reported separately)

- **Result: NOT completed.** 1 of 73 pages was read. Page 2 was delivered, and it appears in the read log, but only a preview reached the agent. The agent recorded the file NOT-CONSUMED instead of claiming it. This is **delivery ≠ reading** observed live. The page ledger alone cannot tell a logged page from a consumed one, and the agent's honest record is what exposed it.
- **Cause, by the agent's account and the log:**
  - S2276 is a **binary AI-generated PNG image** (C2PA manifest).
  - Decoded as text with replacement characters, a 20,000-character page becomes more than 30 KB of output. That exceeds the tool's display limit, so the harness substitutes a saved copy and a preview, and the contract correctly forbids reading saved copies.
  - At about 25k tokens per page, 73 pages would be about 1.8 million tokens, beyond a 1-million-token context even if displayed.
- **What it establishes:**
  - The largest "required" file in the population cannot be consumed whole in one context under current paging.
  - Paging by characters is unsafe for binary or multibyte content; pages need a **byte bound** under the display limit.
- **What it does not establish:**
  - whether text files of this size are consumable;
  - whether a stage-2 "hit" inside binary image data (here the term `b'` at a byte offset) should be a whole-file reading obligation at all. That is a protocol-interpretation question for the human (compare F2 / FALSE-HIT), not a capacity question.

## E. Epistemic assessment

**Does deterministic decomposition preserve the frozen S5 epistemic requirement?**

**Demonstrated:**
1. **Mechanical completeness (Property A).** A label that one context could not complete (1.76 MB) was covered completely, deterministically and verifiably through 3 whole-file units. There were no gaps and no duplicates, and the provenance chain is complete. The coverage union is provable from persistent logs.
2. **Continuation.** A unit resumed across a context boundary with 0 missing and 0 duplicate pages.
3. **Rule compliance.** The per-file whole-file basis of every FOUND, WHOLE-FILE and GENUINELY-UNDEFINED result stays that of an agent that read the file whole, and the production verifier passes both synthesized objects.
4. **Control comparability, on one small label.** The decomposed result matched the single-agent result on every absence resolution and status. No difference went against the decomposed object.

**Not demonstrated (uncertain):**
- **Cross-unit synthesis fidelity.** The auditor found **four losses caused by decomposition** on the target:
  1. change classes across unit boundaries, inferred from one quote per file;
  2. disposition-versus-absence-finding conflicts the synthesis cannot adjudicate without reading;
  3. **cross-unit calibration**: near-identical front matter was dispositioned UNSUPPLIED by one unit and FOUND by another;
  4. inconsistent dependency edges from co-labels.

  **Label-level resolutions were unaffected in this pilot. Per-key calibration and the edge set were affected.** A single analyst reading all files would have settled these.
- **Generality.** One control label (1 unit) and one target label (3 units). There is no multi-unit control with a single-analyst baseline, so Property B across units is untested. The labels above 1.76 MB (up to 8.59 MB, brief §A) were not tested.
- **Model independence.** Auditor and agents share one model family.
- **Binary and very large files.** See §D.

## F. Architecture implication (no production architecture recommended)

**Established:**
- Whole-file reading can be decomposed into deterministic units whose union is mechanically provable and resumable, with production-verifier compliance, for a label that exceeds one context.
- On a small control, the decomposed synthesis reproduced the single-analyst resolutions.

**Not established:**
- that synthesis across units is equivalent to single-analyst judgment for cross-file judgments. The measured losses are in calibration, change classes and edges;
- behaviour on the heaviest labels;
- behaviour with an independent-model auditor;
- how binary or very large files should be treated.

**Further human decisions required:**
1. Whether the measured cross-unit losses are acceptable, or require a mitigation that the pilot did not test, before C is adopted. Possible mitigations include a calibration pass across units, or a synthesis step that must re-read the files behind cross-unit judgments.
2. Whether a further test is wanted first: a multi-unit control with a single-analyst baseline, or the heaviest labels.
3. Byte-bounded paging, to fix the display limit found by the probe.
4. How required reading applies to binary files, i.e. a stage-2 "hit" inside image bytes.
5. Whether and how the schema-4 delta `NOT-CONSUMED-ESCALATED` enters a production contract revision.

## G. State

- **Production:** OB0004 FAILED (R2, R2.2, R2.3 unchanged); 395 batches PREPARED; **no batch authorized**.
- **Pilot:** complete and stopped.
- **H-19:** SEALED, never accessed. **S5c:** PROHIBITED.
- **The production S5 protocol is unchanged.** No manifest was regenerated and no batch dispatched.
