# C11 — Hypothesis testing · v1.1

**Treatment:** ADAPTED from P3B v1.7 §13.10 (searches A–D, each with population, method ∈ LEXICAL |
STRUCTURAL-ENUMERATION | WHOLE-FILE-READING, completeness ∈ EXHAUSTIVE | SAMPLED, termination bound, result, negative
label) and §13.10a (a frozen test is never changed after its outcome is seen). Tests run **only at checkpoints**
(human choice). **Code:** specified here; implemented and tested before CP-01.

## 1. Procedure at a checkpoint
For each HYPOTHESIS / STRUCTURE-CANDIDATE registered before the checkpoint:
1. Load its pre-registration unchanged (its record hash is verified against the RESEARCHED event of its F-ID).
2. Build the population by its `population_rule` from AUDITED F-IDs only. Record which part is in-sample
   (≤ `registered_after_f`) and which is out-of-sample.
3. Run A (support), B (contradiction), C (discrimination against `competing_hypotheses` / controls), D (counterexample).
   Whole-file reading at a checkpoint uses the C01 reader under checkpoint run ids `FR-CP##-NNN`.
4. Decide by the pre-registered `comparison_rule` and `stopping_rule` only.

## 2. Outcome
`SUPPORTED` · `UNSUPPORTED` · `UNDETERMINED` — with the evidence for each search, the in/out-of-sample split and the
remaining uncertainty. Written to `checkpoints/CP-##/TESTS.jsonl`. **A SUPPORTED hypothesis is an evidence-backed
finding about the F-corpus, not a theory element** (C14).

## 3. Forbidden
Changing a population, rule or prediction after seeing data · testing on files not yet AUDITED · counting duplicates as
independent recurrence · reporting in-sample support as out-of-sample.
