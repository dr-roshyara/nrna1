# S5 decomposition pilot — pre-registration (frozen before execution)

**Authority:** human ruling "S-SERIES — HUMAN RULING: AUTHORIZE DECOMPOSITION PILOT" (2026-09-25), recorded as G-LOG-0052. S-Series only; non-production.

**Frozen artifacts:**

| Artifact | sha256 |
|---|---|
| plan `pilot-s5-decomp/PILOT-PLAN.json` | file `4ccc14f0bfeb3b3bdb0e48f7a0e8f257aec23d898b75b93f1dd257b4bab53a36`, output `ac721f3ba18f7e157a810757e7ff08904f5afc3eb34c4e09c572a2f13358e01b` |
| pilot contract `prompts/20260925_1325_p3b-s5-decomposition-pilot-contract.md` | `468ec116829c09c326c1e13a8901ff1f6697ac92a70540d5c762da6f48f38536` |

Tooling: `scripts/p3b_s5_pilot.py` (commit `52fbe3c3f`).

**This document is committed before any pilot agent runs. The criteria below are not changed after execution.**

## 1. Isolation

- **Namespace:** batch ids `PX0004` and `PX0027`; run ids `PX0004-U01…U04` (units), `PX0004-S01` (control synthesis), `PX0004-S02` (target synthesis), `PX0004-A01` (audit), `PX0027-U01` (probe).
- **All pilot output and read logs go to `pilot-s5-decomp/`.** No production manifest, slice, state, ledger, batch, contract, pass plan or pass contract is modified. OB0004 R2 / R2.2 / R2.3 stay immutable.
- **Unchanged:** population, hubs, K = 64, A'1, H-17. H-19 is not accessed; S5c is not started.
- **Inputs:** the production revision-3 slices of OB0004 (read-only) and the frozen discovery search (probe hit keys only).

## 2. Mandatory reading and partition (no thinning)

- **Mandatory set per label:** R(L) = the label's `stage2_files` ∪ every row source of the label. That is a superset of the frozen OMQ-14 set (stage-2 files plus sources carrying births, changes or contradictions).
  - Control: 6 files, 304,733 bytes.
  - Target: 39 files, 1,760,662 bytes.
- **Partition:** first-fit decreasing by size, ties by S-id; budget 600,000 bytes per unit; files never split.

  | Unit | Label | Files | Bytes |
  |---|---|---|---|
  | U01 | control | 6 | 304,733 |
  | U02 | target | 6 | 599,718 |
  | U03 | target | 6 | 599,100 |
  | U04 | target | 27 | 561,844 |
- **Continuation:** U02 runs in two sessions (session 1: the first 3 files in S-id order; session 2: the rest).
- **Probe:** PX0027-U01 reads S2276 (1,516,994 bytes, the largest required file in the population) whole, under label `b-prime-relocation-decision` (hit key: raw offset 1099330, term index 1). It is included because it adds one agent and changes nothing else. It answers only the single-file capacity question.

## 3. Property A — mechanical completeness (success = all of the following)

| Criterion | Measured by | Success |
|---|---|---|
| Coverage | `p3b_s5_pilot.py check` | every file of R(control) and R(target) complete: pages 1..N logged in its unit, hashes re-verified; 0 missing pages; 0 hash failures |
| No unproven whole-file claim | `check` plus `validate` | every WHOLE-FILE disposition, FOUND, birth and change-type timeline point rests on a complete, verified file (READ-COVERAGE on the union: 0 failures) |
| Integrity | `check` | the aggregate coverage is reconstructed from the persistent read logs alone |
| Provenance | `check` provenance rows | every page read maps S5 batch → label → required file → partition → page → reading event (utc, page hash, session) |
| Continuation | `check` continuation block for U02 | complete after resume; **0 cross-session duplicate pages**; sessions 1 and 2 both present |
| Partition determinism | tests plus plan | re-computing the partition yields the frozen plan |
| No thinning | plan | R(L) ⊇ the frozen OMQ-14 set, and no required file is dropped |

**Duplicates:** a page read more than once within a unit is counted and reported. It does not by itself fail coverage, but cross-session duplicates in U02 fail the continuation criterion.

## 4. Target completion (step-verify-programme)

**Success:**
- Property A holds for R(target).
- The synthesized object passes the **production verifier** (`p3b_s5_pilot.py validate`: a relabelled temporary copy with the schema-4 header and the union of read logs) with **0 failures**.
- The independent audit (§6) finds **no PROTOCOL-VIOLATION**.

**Failure:** any unit cannot complete its files; coverage has a gap; the verifier reports failures; or the audit finds a PROTOCOL-VIOLATION.

## 5. Property B — control comparability (knowledgeos-architecture-constitution-v01)

**Baseline:** the OB0004-R2.3 single-agent object for this label, `ledger-p3b-r2/OB0004-R2.3/objects.jsonl`. All 3 of its required files were completely read in R2.3; S1023 was not, as it was not required under revision 3's rule.

**Compared object:** `pilot-s5-decomp/PX0004-S01/objects.jsonl`.

**Fields and agreement classes (fixed now):**

| Field | Exact | Coarse | Different |
|---|---|---|---|
| births.{5 kinds} | same value | same class (ESTABLISHED / MOVED / BIRTH-UNRESOLVED / ESCALATED), different S-id | different class |
| absences.{dimension}.resolution | same resolution | — | different |
| absences FOUND supplied_by source | same source | FOUND on both, different source | — |
| semantic_status, semantic_status_rule, type_status, mathematical_status, primary_layer, tier | equal | — | not equal |
| secondary_roles | equal sets | overlap ≥ 1 | disjoint |
| dependency_edges (target labels) | equal sets | Jaccard ≥ 0.5 | Jaccard < 0.5 |
| timeline (source_id → change class) | equal map | same source set, some classes differ | different source set |
| escalations (reasons, multiset) | equal | same set of reasons | different set |
| stage2_dispositions (per hit key: by_dimension) | equal | — | any value differs (reported per key) |

**Disposition of every non-exact field** by the independent auditor, with reading evidence:
- DECOMPOSED-UPHELD;
- BASELINE-UPHELD (the decomposed result is wrong);
- JUDGMENT-CALL (both defensible);
- PROTOCOL-VIOLATION.

**Property B success (control):**
- 0 PROTOCOL-VIOLATION in the decomposed object;
- **no BASELINE-UPHELD on a judgment field** (births, absences.resolution, semantic_status, type_status, mathematical_status, primary_layer, stage2_dispositions).

**Reported in all cases:** the counts of exact / coarse / different fields. Differences dispositioned JUDGMENT-CALL or DECOMPOSED-UPHELD do not fail Property B.

**Not claimed:** Property B on the control does not by itself establish comparability for the target. For the target there is no complete single-agent baseline; only the audit of §6 applies.

## 6. Independent audit (PX0004-A01)

- **(a) Control:** the §5 comparison and a disposition of every non-exact field. The auditor reads the relevant files itself through the paged reader.
- **(b) Target:** a §21-style audit of the synthesized object on a seeded sample, drawn by the frozen audit-sample script over `pilot-s5-decomp/PX0004-S02`. It covers every judgment field whose basis crosses unit boundaries (births, timeline, D4 and semantic status), plus the stage-2 dispositions of one seeded file per unit.
- **(c) Cross-unit information-loss check:** for the target, the auditor lists any label-level judgment it could not verify from the file-reading records alone and needed to re-read a file for. That list is the measured information loss of synthesis from records.
- **Dispositions:** ORIGINAL-UPHELD, AUDIT-UPHELD, JUDGMENT-CALL, PROTOCOL-VIOLATION (§21).

## 7. Probe (reported separately)

- **Success:** all pages of S2276 are read and verified in one context, and a file-reading record is written for the label's hit key.
- **Failure:** incomplete. That indicates a single-file context limit, which decomposition at file granularity cannot solve.

## 8. Stop

After the report `audit-p3b/S5-DECOMPOSITION-PILOT-REPORT.md`: stop. No production change and no batch; H-19 SEALED; S5c PROHIBITED.
