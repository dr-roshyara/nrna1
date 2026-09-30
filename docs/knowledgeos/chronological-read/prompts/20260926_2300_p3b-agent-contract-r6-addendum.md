# P3b S5 AGENT CONTRACT — REVISION 6 ADDENDUM (§26 change control; G-LOG-0083)

**Status: DRAFTED, IMPLEMENTED BEHIND THE REVISION-6 GATE, NOT ACTIVATED.** S5 execution, dispatch and corpus reading: **NOT AUTHORIZED**.

**Supersedes:**
- revision 5 (`prompts/20260926_2100_p3b-agent-contract-r5-addendum.md`), which was never activated and is **withdrawn**. The verifier rejects any revision-5 manifest and any `-R5` run.
- The revision-5 addendum and runbook stay as audited evidence, and every item of revision 5 carries over **except where changed below**.
- Revision 3 governs everything neither addendum changes.

**Why:** the ONE independent adversarial audit (`audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md`) demonstrated false PASS (8 MATERIAL). The causal analysis is in `audit-p3b/20260926_REVISION-6-CAUSAL-ANALYSIS.md`.

**Principle:**
- The verifier **derives** every critical verification state from authoritative inputs. Agent and orchestrator declarations are assertions checked against that derivation.
- **PASS ⇒ P ∧ E ∧ R ∧ T ∧ L ∧ S** (provenance, execution/run, reading state, temporal, load/plan, synthesis). Violating any one gives NOT-PASS (tested for each).

**Machinery:**
- `scripts/p3b_s5_r6.py` (rules);
- `scripts/p3b_s5_r6_verify.py` (batch verification);
- the `p3b_s5_verify.verify` gate (revision ≥ 6);
- `p3b_read_source.py` (revision-6 run grammar; plan check).

## Changes from revision 5

| Area | Revision 6 rule |
|---|---|
| **Run ids** | `OB####-R6-L##` (SINGLE), `OB####-R6-L##U##` (unit), `OB####-R6-L##S` (synthesis); the assembled run is `OB####-R6`. **`L##` = the label's 1-based position in the manifest entry.** Byte mode is mandatory |
| **L: frozen plan** | `<slice_root>/<batch>.R6-PLAN.json`, canonical JSON; its sha256 sits in the manifest entry `r6_plan_sha256`. **The plan must equal `plan_derive`**: from the manifest label order, the hash-verified slices, the resolver's bytes (sizes = byte lengths, every blob type) and 02-FILES metadata (packing key). The plan cannot define the evidence it is checked against. **The packing order has no chronological meaning** and never enters a record |
| **E: execution** | every `ledger-p3b-r2/<batch>-R6-*` directory is a planned run (undeclared → FAIL; any `-R5` directory → FAIL). **Each planned run's own `READ-LOG.jsonl` is verified:** run = its directory; batch = batch; `working_label` = the plan owner; byte-mode page present; reads ⊆ permitted files. Synthesis runs have **zero** entries. **Any refused entry fails** (stop condition 16). The reader refuses unplanned run/file pairs, synthesis reads and a `--label` ≠ owner (defence in depth) |
| **R: reading state** | one file-reading record per permitted file of each reading run. WHOLE-FILE requires **complete byte coverage in the owning run**. Acknowledgement tokens equal the page tokens as a multiset. **All timeline classes except {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE} need a WHOLE-FILE source** (default-deny; unknown classes included). Every non-WHOLE-FILE required file carries a CONTRACT-DEVIATION escalation naming it |
| **P: provenance** (new record schema) | each file-reading record carries `facts: [{fact_id, kind, quote, …}]`. The kinds and their required fields are:<br>– TIMELINE (`change_candidate`)<br>– BIRTH (`birth_kind`)<br>– ABSENCE (`dimension`, `finding` ∈ DEFINES/MENTIONS/NONE)<br>– DEPENDENCY (`target_label`)<br>– OMQ14 (`type`)<br>**Every fact quote must occur in the file's authoritative bytes.** The final run (SINGLE or synthesis) writes `claim-evidence.json`: `{claim_id: [{run, source_id, fact_id}]}`. **The verifier enumerates the claims itself:** timeline points; FOUND absences; FOUND stage-2 values; S-id-bearing births; R2 edges. Each must resolve to a fact of the right kind and fields, in a reading run of that label, with a WHOLE-FILE record where whole-file evidence is required. GENUINELY-UNDEFINED needs a complete census and no DEFINES fact. **Facts exist only in reading runs**, so a claim cannot be its own evidence. *(The verifier certifies SOURCE-SUPPORTED linkage only. RECONSTRUCTION-VALID, THEORY-CONSISTENT and INDEPENDENTLY-CORROBORATED remain downstream statuses and are never collapsed into it)* |
| **T: temporal** | every time is **strict RFC 3339 with a zone**, parsed to aware UTC; anything else fails (no string compares). The ordering is: max(unit read `utc` from the reader-written logs) ≤ `units-validated` < `synthesis-dispatched` ≤ `produced_utc`. The stages come from `ledger-p3b-r2/<batch>-R6/R6-PROVENANCE.jsonl`, and `produced_utc` from the final run's `RUN-MANIFEST.json`. The units-validated stage binds the unit record files' hashes, and the final run's manifest binds the exact current record-file hashes |
| **S: synthesis** | S1 with **exact** pair tokens: YES/NO quotes are verified in the file's bytes; a NO needs a register record (topic VERDICT-EVIDENCE-CONFLICT, exact pair token) **and** an escalation with reason OTHER and detail `VERDICT-EVIDENCE-CONFLICT: <pair_id>`. S3 on the object, register **and P1-gap** records, with the range variants broadened and pointers matched case-insensitively. `S3-LINT.json` is bound to the hash of exactly those records. **EMPTY gets every check** (S3, edges, claim enumeration) and must carry no timeline point and no edge |

**Stated deviation from the audit:** a slash between S-ids (`S0957/S2361`) is an **enumeration, not a range** (it implies no ids in between), so it is not a range connector. This is recorded for any re-audit to judge.

## Frozen statistical specification (R5-16)

| Element | Specification |
|---|---|
| **Population** | all S5 labels of the activated plan (1,975) |
| **Sampling unit** | one label. Field-level differences within a label are **not** independent observations |
| **Strata** | mutually exclusive and collectively exhaustive, by precedence HUB → EMPTY → MULTIROW (DECOMPOSED with row sources over > 1 unit) → DECOMPOSED → SINGLE (`assign_strata`, validated by `validate_strata`). **TIER-X** is a **reporting domain** (a nested attribute), not a stratum |
| **Estimand** | D_i = 1 if adjudication finds a qualifying reconstruction discordance on label i (a judgment field BASELINE-UPHELD against an independent blind single-context re-analysis, or a PROTOCOL-VIOLATION), else 0. Parameters: τ = Σ D_i and p = τ / N. **Called "adjudicated discordance"**, never a fidelity rate: same-family agreement ≠ correctness |
| **Design** | stratified SRSWOR with a frozen seed (`draw_sample_v6`). Rates are fixed in the activation act: CENSUS for MULTIROW and EMPTY; a stated fraction elsewhere. A rate of 0 means n = 0, stated as "no information" for that stratum |
| **Inclusion probability** | π_h = n_h / N_h, known before any S5 output exists |
| **Estimator** | stratified Horvitz–Thompson τ̂ = Σ_h (N_h / n_h) d_h (`ht_with_variance`) |
| **Variance / CI** | Var̂(τ̂) = Σ_h N_h² (1 − n_h/N_h) s_h² / n_h (census strata contribute 0; n_h ≤ 1 is reported as unestimable). A normal 95% CI on p with the FPC. Per stratum with d_h = 0: the exact one-sided bound |
| **Zero-discordance bound** | α = 0.05, one-sided. **Finite population:** the hypergeometric bound (largest D with P(0 \| D) ≥ α). **Census:** 0. **n = 0:** 1.0 (no information). The binomial 1 − α^(1/n) applies only without a finite N |
| **Freezing** | the seed, the drawn label lists, the specification sha256 and the Python version (`freeze_sample_record`), written at activation **before any S5 output exists** |
| **ML exclusion** | the estimate refuses discordant labels outside the probability sample and a sample that is not the frozen one. No ML may alter inclusion probabilities, membership, strata, the discordance definition or the weighting. An ML review queue is a separate, non-estimating queue |

## ML boundary (design only; no ML in revision 6)

- **Permitted later:** adjudicated S5 data → ML candidate retrieval / anomaly detection / uncertainty estimation → human review prioritization.
- **Forbidden:** SOURCE → ML → EVIDENCE; SOURCE → ML → THEORY; SOURCE → similarity → CANONICAL.
- **ML never establishes** identity, equivalence, births, edges, canonical identity, truth or theory.
- **Random probability sampling stays independent** of ML prioritization.

## Runbook changes (supersedes the corresponding steps of the revision-5 runbook)

| Step(s) | Change |
|---|---|
| 1 | the plan is written at activation and its hash is bound in the manifest entry |
| 3, 6 | records carry facts |
| 7 | `units-validated` records the unit record-file hashes in `R6-PROVENANCE.jsonl` |
| 10 | the lint covers object + register + P1-gap |
| 11 | synthesis writes `claim-evidence.json`, `S3-LINT.json` and `RUN-MANIFEST.json`; `synthesis-dispatched` is recorded |
| 13 | assembly writes objects, register, P1-gap and `R6-PROVENANCE.jsonl` (the per-run logs stay in place and are what the verifier reads) |
| 14 | `p3b_s5_verify.py` on `OB####-R6` |

## Activation checklist (human acts; NOT performed)

1. A human decision on whether a narrow independent re-audit of revision 6 is required.
2. Binary pre-classification (authorized separately) and the 12 per-file decisions.
3. The audit-sample rates and seed; freezing.
4. Manifest regeneration with the revision-6 contract hash and `r6_plan_sha256`; the production plan and slices; `prepare --check` against it.
5. The human **S5 EXECUTION AUTHORIZATION**.
