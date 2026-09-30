# OB0018 decomposition-fidelity pilot — pre-registration (frozen before any execution)

**Authority:** G-LOG-0078 (human decisions D-1…D-4, 2026-09-26). The basis is `audit-p3b/20260926_OB0018-READINESS-REPORT.md` and the post-pilot brief §D (G-LOG-0054).

**Status:** NOT AUTHORIZED · NOT EXECUTED · no corpus content read in preparation. S-Series only; non-production.

**Revision (after the single independent audit, `audit-p3b/20260926_OB0018-PREREGISTRATION-AUDIT.md`):** the MATERIAL findings M-1…M-8 are repaired below; the minor findings are dispositioned in that record. The first freeze was `6f67392b3fcc77e712a00f143dbd63fdc6878da6`; this document supersedes it.

**Frozen artifacts (this commit):**

| Artifact | sha256 |
|---|---|
| tool `scripts/p3b_ob0018_pilot.py` | `0117d2acb3e200155fa360e532c7c7b26a7888fa041d1c4f73aa85c4aeb01166` |
| tests `scripts/tests/test_p3b_ob0018_pilot.py` (68 OK) | `4fd57d56a4d17901505424cb830ecae63c6a5e9747c101979b65d4a9054807ac` |
| pilot contract `prompts/20260926_1800_p3b-ob0018-pilot-contract.md` | `d076440fff3b0146038787ef54af34549ffbc55079b47c1eedad1947a1c2fb94` |
| plan `pilot-s5-decomp/ob0018/OB0018-PLAN.json` | `34c1b6353b820755d14fc248ae1ba41c2876c6f28c64b922f95066a1fd291f34` |
| control slice `pilot-s5-decomp/ob0018/slices/<label>.json` | file `13c871b8…a94bc`; content = frozen production manifest slice_sha256 `8aa7c6ec945750e16f2842a47f6a11d6b7d020eb1ca2edde203de756c84ff46d` |
| dispatch prompts `pilot-s5-decomp/ob0018/PROMPTS/*.txt` (8, concatenated) | `6936fe4e0fa00aa290262a4e6e19bf3725d9e938da419046212d7b1eb8413418` |

**Mandatory scope statements (verbatim in every report):**
- "OB0018 can provide evidence about decomposition fidelity on the selected few-unit control label. It does not by itself establish population-wide equivalence of decomposition and single-context analysis."
- "A successful OB0018 result does not eliminate the need for the S5 reconstruction floor." R19 is absolute.

---

## A. Research question

**Does the proposed S5 decomposition architecture show observable fidelity failures that would require a design change before production-scale reconstruction?**

**Operational form:** on one fixed 3-unit control label, do synthesis arms A, B and C reproduce a single-context baseline on the frozen comparison fields and failure-mode metrics (§G, §H)?

**It is not:** a universal decomposition theorem; a population error rate; a strategy or theory question.

## B. Hypotheses (brief §D)

- **H0:** decomposition plus synthesis from records only (arm A) is faithful.
- **H1:** a deterministic consistency pass is required (arm B faithful, A not).
- **H2:** bounded whole-file re-reads of cross-unit judgments are required (arm C faithful, A and B not).

## C. Unit of observation

- **Primary:** one **(arm, comparison field)** disposition, relative to the baseline, on one label.
- **Consequence:** the label-level decision is a **single observation (n_labels = 1)**; fields within the label are **not independent** observations of the population.

## D. Control-label selection (fixed before this document; brief §D)

- **Label:** `s1620-removal-test-provenance-base-case-and-user-decisions`, from S5 batch OB0018.
- **Candidate rule:** non-hub; required load 600–900 KB; ≥ 12 files; ≥ 4 row sources; ≥ 1 OMQ-14 source; not OB0004. That gives 16 candidates, and the pick was seeded with 20261100 (G-LOG-0054).
- **Production batch OB0018** (run OB0018-R2, PREPARED) is **not dispatched, modified or written**. Only this one label's slice is regenerated, byte-identical to the frozen manifest.

## E. Input population

**R(L) = stage-2 files ∪ all row sources** (no thinning):
- 34 files, 771,717 bytes;
- 31 stage-2 files and 8 row sources;
- no binary file; non-hub; tier U.

**Partition** (first-fit decreasing, 300 KB budget, files never split):

| Unit | Files | Bytes |
|---|---|---|
| PX0018-U01 | 4 | 298,362 |
| PX0018-U02 | 12 | 299,979 |
| PX0018-U03 | 18 | 173,376 |

**Cross-unit adjacencies:** 4. They are defined over the row sources in S-id order, a frozen proxy for historical order: (S1623, S1627), (S1627, S1629), (S1629, S1631), (S1631, S1633); 5 distinct files, 70,594 bytes.

**Population context** (readiness §4; deterministic; not a fidelity claim):
- 1,975 labels; 1,790 single-unit at 600 KB;
- 86 labels above the demonstrated 1.09 MB capacity, carrying 43.8% of required bytes;
- 358 unit boundaries at 600 KB; at most 13 units;
- this control: 3 units, percentile rank 0.93.

## F. Runs and arms (contract §1–§4)

| Run | Role | Inputs | Reading |
|---|---|---|---|
| PX0018-U01…U03 | reading units | slice, plan | every assigned file whole |
| PX0018-S00 | **single-context baseline**, production procedure | slice only (no unit records) | per production contract revision 3 |
| PX0018-S01 | **arm A**: records only | unit records | **none** (any page read is a violation) |
| PX0018-S02 | **arm B**: records + `INVARIANTS.json` | unit records, invariants | **none**; every flagged item resolved or escalated |
| PX0018-S03 | **arm C**: records + invariants + re-reads | unit records, invariants | **mandatory** whole re-reads of both files at every adjacency (**enforced: a skipped adjacency file makes arm C INVALID**, M-5), then conflict files in report order, **≤ 300,000 bytes**; remaining conflicts escalated with reason OTHER, detail "re-read budget exhausted" (LOAD is rejected on a non-hub label, M-3) |
| PX0018-A01 | **blind auditor** | audit package (letters P/Q/R), `COMPARE.json` | own re-reads ≤ 600,000 bytes |

**Deterministic invariant report** (`invariants`, for arms B and C):
- disposition-vs-finding conflicts (FOUND without DEFINES; DEFINES with FALSE-HIT or UNSUPPLIED-DIMENSION);
- calibration pairs (cross-unit absence evidence with **normalized-equal quotes**, the same dimension, different findings; exact normalized equality, **no similarity model**);
- invalid edge endpoints (target not a discovery label); edges without a quote; the adjacencies.

## G. Four outcome levels (never collapsed)

| Level | Name | Measured by | Pass |
|---|---|---|---|
| 1 | **engineering feasibility** | `check`: page ledger with hash re-computation | every unit file complete (0 missing pages, 0 hash failures, 0 NOT-CONSUMED, 0 read-log entries under a foreign run or batch); baseline complete per its verifier READ-COVERAGE (the production procedure may use Stage 2A/2B, so completeness of all of R(L) is not required of the baseline); 3 units; no transcript finding in units or baseline; **production ledger and state unchanged** (fingerprint taken at authorization; a change stops the pilot, M-6) |
| 2 | **extraction fidelity** | `validate`: production verifier + production quote checker on relabelled copies | per arm: verifier PASS (0 failures); 0 quote misses |
| 3 | **semantic fidelity** | blind audit per non-exact field | per arm: 0 PROTOCOL-VIOLATION (any field); **0 BASELINE-UPHELD on judgment fields** (births, absences.resolution, semantic_status, type_status, mathematical_status, primary_layer, stage2_dispositions) |
| 4 | **reconstruction fidelity** | `compare` metrics + audit | (i)–(iv) of §H |

**Evidence ladder (distinct names: E0 already denotes the closed V1.2.4 run):**
- **FL-0 engineering feasibility** = levels 1–2;
- **FL-1 local decomposition fidelity** = levels 3–4 on this label;
- **FL-2 population robustness** = future work only (§P).

**OB0018 addresses FL-0 and FL-1 only.**

## H. Exact numeric failure bounds (per arm; frozen)

| Metric | Definition | Faithful bound |
|---|---|---|
| (i) adjacency classes | `change_vs_previous` of the later file at each cross-unit adjacency, vs baseline; **every** unequal row is audited as `adjacency.<S-id>` and enters the level-3 rules with its actual disposition (a PROTOCOL-VIOLATION on it counts; M-4) | **0** BASELINE-UPHELD |
| (ii) residual conflicts | the arm object's disposition-vs-finding conflicts (against the unit records), **per distinct stage-1 hit key** (source, hit kind, hit key, term index, dimension; never collapsed per file, M-2), plus GENUINELY-UNDEFINED-AFTER-CENSUS for a dimension any unit found DEFINES. For arm C, a conflict on a file it completely re-read counts as resolved | **0** |
| (iii) residual calibration | calibration pairs whose final dispositions disagree on FOUND vs not-FOUND at file level (FOUND if any hit of the file is FOUND on that dimension) | **0** |
| (iv) edge endpoint validity | the share of the arm's edges whose target is a discovery label | **1.0** (100%) |
| (iv′) edge-set Jaccard vs baseline | reading R1 (all edges) and R2 (edges whose quote names the target: a deterministic proxy) | **reported only** (D-4: the co-label rule is undecided; not decision-relevant) |

## I. Outcome rules and failure handling (`outcome_of`, `decision`; tested exhaustively)

**Per arm, applied in order:**
1. **INVALID:** an arm-run protocol violation:
   - a transcript finding: reading outside the reader; a reader call with a foreign `--run` or `--batch`, or with a production run id (M-6); any reader call in arm A or B;
   - a read-log entry under a foreign run or batch;
   - a page read in arm A or B;
   - arm C over budget, or arm C skipping a mandatory adjacency re-read (M-5).
2. **UNDETERMINED:** level 1 failed (experiment-level); or any judgment-field difference, or any unequal adjacency row, UNADJUDICATED; or an invalid audit (auditor transcript finding or an off-scale disposition).
3. **FAITHFUL:** levels 2–4 all within bounds.
4. **NOT-FAITHFUL:** otherwise, with every reason listed.

**Decision over the arms (brief §D mapping):**
- A FAITHFUL → **EVIDENCE-FOR-ARCHITECTURE-A**;
- A NOT-FAITHFUL and B FAITHFUL → **…-B**;
- A and B NOT-FAITHFUL and C FAITHFUL → **…-C**;
- all NOT-FAITHFUL → **NO-ARM-SHOWN-FAITHFUL** (a valid negative result);
- any INVALID or UNDETERMINED arm before the first FAITHFUL arm → **UNDETERMINED**.

**Failure handling:**
- **A failed run** is recorded (never silently re-run). A re-run needs a new namespace and a human act.
- **A SEAL-BREACH** stops everything and escalates (P3B-ESC). No outcome is computed.
- **Repairs** are append-only: new files with diffs, never edits of frozen records.

## J. Provenance model

**Chain:** SOURCE (git object; content hash in the identity manifest) → PAGE (reader log: page index, span, sha256; re-verified) → UNIT RECORD (quote verbatim; S-id; anchor) → SYNTHESIS FIELD (per arm) → AUDIT DISPOSITION (letter → arm via the sealed key).

**Freeze stages** (`PROVENANCE.jsonl`, file hashes plus UTC):

| Stage | Contents |
|---|---|
| `authorization` | the authorization file |
| `plan` | plan, slice |
| `units` | unit coverage |
| `invariants` | the invariant report |
| `syntheses` | full coverage, validation, transcript scan, dispatch record |
| `blind` | sealed key (hash only), audit package, comparison |
| `audit` | audit, auditor scan |
| `unseal` | key |

Each stage is committed.
- **`freeze`** refuses when any listed file is missing (M-1). The auditor's file is frozen **from the auditor's own directory** `pilot-s5-decomp/PX0018-A01/AUDIT.jsonl`, the path the contract prescribes (M-1). `authorization` also freezes the production-ledger fingerprint.
- **`result`** refuses if a stage is missing, or if the key or the audit file differs from its frozen hash.

## K. Auditor procedure

- **Scope:** one auditor (PX0018-A01) dispositions **every non-exact field of every letter and every unequal adjacency row**: DECOMPOSED-UPHELD / BASELINE-UPHELD / JUDGMENT-CALL / PROTOCOL-VIOLATION / UNADJUDICATED.
- **Evidence:** each disposition cites a quote or a rule. The auditor may re-read files itself (≤ 600,000 bytes).

## L. Blindness

- **Mechanism:**
  - arm objects are presented under letters P/Q/R from a seeded permutation of a runtime secret;
  - run identity, model and contract fields are stripped;
  - the key is frozen by hash at `blind` and committed only at `unseal`, after the audit is frozen.
- **Enforced by detection, not by access control (M-7):**
  - the auditor has shell access, so no file on disk is unreachable;
  - the contract forbids opening or naming the arm-run directories, the key, `CHECK-ALL`, `VALIDATE`, `SCAN*` and `DISPATCH`;
  - the auditor's transcript scan flags any such name, and a finding **invalidates the audit** (every FAITHFUL or NOT-FAITHFUL arm becomes UNDETERMINED).
- **Residual:** a route that names none of these tokens (e.g. a wildcard listing followed by reading by position) is not detected.
- **Disclosed limitation:** blindness is also **partial by content**. Arm C's re-read evidence and arm B's resolution escalations may reveal the arm.

## M. Same-family limitation (D-3)

- **Selectable models:** all agents, including the auditor, are Claude-family, because only Claude aliases are demonstrated selectable (V1.2.x §3 observation).
- **What agreement means:** **audit agreement is not independent corroboration.** The blind audit is an error-detection mechanism.
- **Future:** a cross-family audit is a future enhancement, not a blocker.

## N. Statistical interpretation

- **The design:** a **minimum useful decomposition-fidelity pilot** (n_labels = 1, n_units = 3), not a smallest reliable experiment in any population sense.
- **What a FAITHFUL arm licenses:** "no decomposition-fidelity failure was observed for this 3-unit control label under the tested conditions."
- **What it does not license:** any rate. With zero failures in n independent labels, the exact one-sided 95% upper bound on a per-label failure probability is 1 − 0.05^(1/n): **0.95 for n = 1** (no population information). Bounding it at 0.20 needs **n ≥ 14** labels with zero failures (by the "rule of three", ≈ 3/n).
- **What it can establish:**
  - (1) engineering feasibility;
  - (2) local fidelity;
  - (3) concrete failure mechanisms;
  - (4) whether the arms differ materially on this label.
- **What it cannot establish:** population error rates, heavy-tail (10–13-unit) robustness, or population-wide equivalence.
- **Asymmetric value (why the pilot is decision-relevant):** one careful label **can expose** a fundamental failure mode (a NOT-FAITHFUL arm A would change the S5 load architecture), but **cannot certify** its absence.

## O. Explicit scope of inference

- **Licensed:** a statement about the tested arms on this label. Input to the S5 load-architecture ruling (brief §H options 1–3), as local evidence.
- **Not licensed:**
  - a population claim;
  - heavy-tail generalization;
  - model independence;
  - binary-file treatment;
  - the co-label rule;
  - any change to v3.5, P3b, S5 or Architecture v1.2;
  - skipping the S5 floor for any label (R19).

## P. Future FL-2 robustness pathway (prepared, not created; only if decision-relevant)

**Trigger:** OB0018 yields an architecture that survives FL-1, and the S5 ruling then needs tail assurance.

**Stratification dimensions** (and why each matters):

| Dimension | Why it matters |
|---|---|
| unit count / boundary count B | losses arise at boundaries (brief §C-1, §C-3) |
| required bytes | context pressure; a correlate of B |
| file count | more files means more per-file dispositions to calibrate |
| row-source count S | the timeline length, where adjacency classes live |
| dependency fan-out D | edge-evidence inconsistency (§C-4) |
| conflict density C | the §C-2 invariant-violation rate from unit records (measurable after units run) |

**Conceptual failure-risk model** (research model; **no coefficients are asserted**):

  P(error in field j of label i) = logit⁻¹(β₀ + β_B·B_i + β_D·D_i + β_S·S_i + β_C·C_i + β_R·R_i + u_i + γ_field(j)),

- u_i is a label random effect, and R_i the re-read coverage (arm C).
- **Estimation needs:** adjudicated field-level outcomes (the §K audit dispositions) for labels spanning the B range (for example 2–13 units), several labels per stratum, and the same frozen arms and audit procedure.
- **Minimum size:** under the zero-failure planning bound above, **≥ 14 labels per claim** for a 20% per-label bound. Estimating β_B needs variation in B across strata.

## Q. State machine

```
DISCOVERED → FROZEN (this commit) → AUTHORIZED (human act, G-LOG; authorization file)
→ DISPATCHED → EXTRACTED (unit records) → VALIDATED (check, invariants, validate, scan)
→ RECONSTRUCTED (baseline + arms) → AUDITED (blind; frozen) → SEALED (unseal; result; report)
```

**Forbidden transitions:**
- DISPATCHED without AUTHORIZED;
- RECONSTRUCTED from unvalidated units;
- FAILED → any success;
- modifying frozen evidence;
- a model output becoming evidence without the ledger, verifier and audit chain;
- **OB0018 substituting for the S5 floor**;
- any canonicalization, P4+ step or production write during the pilot.

**ML boundary:** no learned model; exact normalized matching and deterministic anchors only. `MODEL → PRIORITIZATION / CANDIDATE GENERATION`, never `MODEL → TRUTH`.

## R. Authorization requirements and runbook

**Before execution:**
1. **One independent read-only audit** of this pre-registration and its artifacts. Only MATERIAL findings are repaired; otherwise stop (hard stopping rule).
2. **A human act:** "AUTHORIZE OB0018 PILOT EXECUTION under pre-registration `<40-hex commit>`", recording:
   - the commit and this document's sha256;
   - namespace PX0018 / `pilot-s5-decomp/ob0018/`;
   - the scope statements (header);
   - the same-family limitation (§M);
   - that production batch OB0018 stays PREPARED;
   - independence from Decision A.

**Runbook after authorization** (orchestrator; each freeze committed with named-path staging):
1. Write and commit `OB0018-AUTHORIZATION.json`; run `fingerprint` (production-ledger fingerprint); then `freeze authorization` and `freeze plan`.
2. Dispatch U01–U03 **and** S00 in parallel, verbatim prompts; record `DISPATCH.json` (agent id, alias, transcript path).
3. `check`, then `freeze units`; then `invariants`, then `freeze invariants`.
4. Dispatch S01, S02, S03 in parallel (verbatim).
5. `check --all`, `validate`, `scan`, then `freeze syntheses`.
6. `blind` (writes the sealed key, package and comparison), then `freeze blind`; commit the package and comparison only.
7. Dispatch A01 (verbatim); `scan --audit`; `freeze audit`.
8. `freeze unseal`; commit the key; `result`; write the report; **stop**.

**Models:** the `opus` alias for all runs (the production model of S5, `claude-opus-5-5`).

**Agents write outputs only with the Write/Edit tool (M-8).** Shell inputs are scanned, and quote text in a shell command can name a corpus path.
