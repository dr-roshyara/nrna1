# C10 — Hypothesis generation (Level 3) · v1.2 (delta)

**Base:** `contracts-v1.1/C10-hypothesis-generation.md`, sha256 `208726f368564550b3caf37963ae232eaad7326c781118d3486c1e2c50225d8c`.

## Changes
1. **Registration is blocked (F-12, F-15).** A HYPOTHESIS or STRUCTURE-CANDIDATE is refused (**HUMAN DECISION REQUIRED**) while `F-DECISIONS.json` has `temporal_semantics` or `multiplicity_rules` null. Per-file SUGGESTION-RESEARCH / SUGGESTION-METHOD records remain allowed.
2. **Binding (F-10).**
   - Every record carries ≥ 1 `analysis_refs` (this file's Level-2 ids) or `inventory_refs`.
   - Same-file evidence quotes must be recorded in this file's Level-1 ledger.
   - Epistemic class is RESEARCH-SUGGESTION or HYPOTHESIS; THEORY-CANDIDATE exists only at checkpoints.
   - Scale is OBJECT or CROSS-OBJECT; CORPUS exists only at checkpoints.
3. **No outcome (F-10).** No outcome-like key (`outcome`, `result`, `test_outcome`, `test_result`, `verdict`, `finding_status`) at any depth. No SUPPORTED / UNSUPPORTED / UNDETERMINED value outside `preregistration.outcome_vocabulary`.
4. **Disconfirmation is planned, not performed, per file (F-11).**
   - `disconfirmation_plan = {population, method ∈ LEXICAL | STRUCTURAL-ENUMERATION | WHOLE-FILE-READING, completeness, termination_bound, where}`, with no result field (P3B §13.10, "at TEST-DEFINED, B–D are planned").
   - `contradicting_evidence[] = [{f_id: this file, quote from its L1 ledger}]` records contrary evidence already in the file, as evidence, not as an outcome.
5. **Pre-registration fields (F-12, F-15):** `population_rule`, `temporal_scope`, `selection_rule`, `comparison_rule`, `stopping_rule`, `prediction`, `registered_after_f`.
   - `holdout_basis` must be one of the decided bases (HDR-2).
   - `confirmatory_checkpoint` is the single confirmatory look.
   - `family` and `multiplicity_rule` must be one of the decided rules (HDR-3).
   - `outcome_vocabulary` is `["SUPPORTED","UNSUPPORTED","UNDETERMINED"]`.
6. **Temporal language (F-12).** While HDR-2 is undecided, "out-of-sample", "held-out", "hold-out" and "prospective" are refused anywhere in a record.
7. **Immutability (F-01).** The RESEARCHED event freezes `research.jsonl`. Any later change fails every later gate and the audit, and the checkpoint snapshot records `frozen_research_sha256` against `current_sha256` for every hypothesis. A changed hypothesis is a new record in a new run, never an edit.
8. **Validation (F-19).** `research_time` is ISO-8601 UTC. `contract_sha256` is the sha256 of the runbook in force.
