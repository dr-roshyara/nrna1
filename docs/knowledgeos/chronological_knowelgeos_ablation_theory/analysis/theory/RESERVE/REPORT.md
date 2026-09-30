# Reserve test: prospective within-register generalization, double-coded under manual r2

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Rows | the 13 reserve rows (R-33, R-42, R-48, R-54, R-57, R-59, R-60, R-69, R-70, R-71, R-74, R-76, R-95) of the seeded order, untouched until this test |
| Design | coding manual r2 frozen at `7be5f02e3` (coding rules only: record-only, act segmentation, applicability; **the theory is unchanged**) · main coding sealed at `629b31215` before the blind run · blind headless coder (same model family, **SECONDARY**) in a fresh bundle; transcript audit: 3 reads, 2 writes, 0 outside paths |
| Result | `AGREEMENT.json` · `BLIND-CODING.json` · `BLIND-NOTES.md` |
| Log | F-LOG-0138 |

## Results
**Coverage (T-min v1.1 vocabulary, r2 segmentation):**

| Coder | Covered / acts | Estimate [Wilson 95%] |
|---|---|---|
| main | 16/26 | 0.62 [0.43, 0.78] |
| blind | 14/23 | 0.61 [0.41, 0.78] |

- **P-COV (≥ 0.70) FAILS for both coders.** The held-out 0.708 (F-LOG-0136) does not replicate.
- With segmentation fixed, **the coders agree on coverage** (0.62 vs 0.61; in Gate 1 they differed by 0.14). **The best estimate of v1.1's coverage is about 0.6, and it is coder-robust.**
- **Remaining gaps**, named the same way by both coders: CORRECT (a plan or contract), RECORD (items, debt), DEFINE, AFFIRM, REFUTE, START/ACTIVATE.

**Agreement (Cohen's κ):**

| Item | κ |
|---|---|
| operations | Jaccard 0.846 |
| V6 | 1.00 |
| V3 | **0.84** (Gate 1: 0.23) |
| V7 | 0.63 |
| authority named | 0.63 |
| V1 | **0.49** (Gate 1: 0.19) |
| V2 | 0.38 |
| V4 | 0.35 |
| V5 | 0.00 (a prevalence effect; one disagreement) |

- r2 fixed segmentation and V3/V1 applicability.
- **V2 and V4 applicability are still unreproducible.** On acceptances, the blind coder codes V2 SUPPORTED where the main coder codes UNTESTABLE.

**Violations:**
- **main: 1** (R-76 V3). The headline "SCOPE CONFIRMED" maps to CONFIRM, which may change nothing, yet the row closes a scope ambiguity.
- **blind: 0.** It coded R-76 AMBIGUOUS.
- The frame violation is therefore **coder-dependent**. It exposes an **overload of "confirm"**: a no-change confirmation (R-97) vs a decisive scope confirmation (R-76, triple "Adoption").

## Consolidated validation picture (41 coded rows: 15 + 13 + 13)
- **Legality guards:** no violation by either coder in any sample.
- **Frames:** 1 contested case (R-76) caused by verb overload.
- **Evidence persistence, four-act separation, reopening, supersession:** no violations.
- **Coverage:** not stable across samples and coders (0.29 / 0.71 / 0.61). It is best treated as a **vocabulary diagnostic, not a model-quality metric**.
- **The vocabulary is where the theory is weakest.**
- **New source facts:**
  - "A recording note cannot open a work package" (R-60);
  - "acceptance criteria are not redefined retroactively after GREEN" (R-59);
  - "Engineering does not self-certify it" (R-71): **a second role-separation statement**, supporting the fitted conformance variable;
  - "R-51 granted permission, R-54 starts the clock" (R-54): pre-R-80 term use;
  - R-95, a Chief *planning* authorization with no PREPARED marker, against R-89, a Chief *implementation* authorization that was PREPARED. **Hypothesis: PREPARED attaches by act type** (implementation-authorizing rulings vs planning or confirmation acts).
