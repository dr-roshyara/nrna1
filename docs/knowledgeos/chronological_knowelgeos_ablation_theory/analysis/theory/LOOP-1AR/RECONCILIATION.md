# 1ar: reconciliation. Act-attestation audit of the 36 strict legality events

| | |
|---|---|
| Frozen | `PREREG.md` + bundle (`34c685c85`) |
| Result | A: ACT-PERFORMED 10 · ACT-REFUSED 9 · PERMISSION-STATEMENT 13 · GENERIC-PRACTICE 1 · UNCLEAR 3. The reviewer agrees on the pattern, with corrections D2 (R-89 is ACT-PERFORMED: issued as PREPARED, never refused), D3, D9 and D10 |
| Task defect (disclosed; mine) | TASK.md was self-conflicting: "you do not need to recompute" vs a witness definition. A recomputed from EVENTS.json and did not read WITNESSES.md (D1, evidence scope). The reviewer used the recorded list. The reconciliation uses the **recorded** witness pairs |
| Bias check | the sealed expectation held for a, s, h and c; **failed for k** (expected WEAK; the result is 0) |

## Surviving strict support on **attested acts** (reconciled)

| Variable | Pairs that survive | Status |
|---|---|---|
| **a (authority)** | 2: ADOPT (the Chief's declined adoption vs the DA's R-86); AUTHORIZE-IMPL (R-70 vs R-89) | **SUPPORTED**, the only dimension with ≥ 2 on attested acts. Note: per F-LOG-0161, a acts through ruling status for force |
| k (kind) | 0: REGISTER = a generic practice; OPEN-WORK "by a recording note" = a permission statement | **NOT DEMONSTRATED on attested acts** (was SUPPORTED) |
| s (target-indexed state) | 0: START events are permission statements; the START outcome is a function of s by construction (D9) | **NOT DEMONSTRATED on attested acts** (was SUPPORTED; F-LOG-0162) |
| e (evidence) | 1 cluster: R-36; A's "12" are item pairs of one row (D3, independence). D7: e = 2+ is not attested for promoted items in the provided row | **WEAK** (unchanged) |
| c (conformance) | 1 coarse, 0 strict (D4) | **WEAK, coarse** (unchanged) |
| h (history) | 0 | NOT DEMONSTRATED. **New candidate (D10, substantive):** R-90 records a *refused* number assignment ("put forward as R-89. R-89 WAS ALREADY TAKEN" → issued as R-90). That is an attested ASSIGN-ID contrast on *occupancy*, not on retirement |

## Pattern: H-DEONTIC SUPPORTED (same-family)
- **Norm-making operations are attested acts:** ADOPT, AUTHORIZE-IMPL, RAISE, ANNOTATE and (mostly) SUPERSEDE. In a norm register **the row is the act**.
- **Execution and register-meta operations are not:** START, REGISTER-rule, OPEN-WORK-by-note, ASSIGN-retired. There the register can only **state norms**, and the strict coding turned those norms into REFUSED acts. That is an **artifact of the coding** (reviewer: `artifact_assessment`).

## Model impact (major)
- **The empirical core shrinks from {authority, kind, state} to {authority}** on attested acts.
- Kind and state remain meaningful **as properties of norms**: what a ruling permits, per kind and per target. They are **not demonstrated as guards of observed acts**.
- **Reframing (HYPOTHESIS, not promoted):** the corpus supports a theory of **norm-making**:
  - who may perform which norm-making act (authority);
  - through which status transitions (PREPARED → ADOPTED; F-LOG-0161);
  - producing which permissions.

  It does not support a theory of the governed actions, which the register does not observe.
- **Instrument consequence:** the semantic-observation schema must distinguish **norm statements** from **act observations** before coding legality. That is a schema change and needs human authorization.

## Next (dynamic queue)
1. The r3-effect review (running) → reconcile.
2. **A decision brief for the human:** the empirical core has shrunk and the theory target is reframed as norm-making (H-DEONTIC). Should the schema separate norm statements from act observations (schema r2)? That is a protocol change.
3. Machine-runnable meanwhile: the D10 ASSIGN-ID occupancy contrast (small); an evidence recheck given D7.
