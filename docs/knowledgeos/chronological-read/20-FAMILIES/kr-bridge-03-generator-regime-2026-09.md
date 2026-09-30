# kr-bridge-03-generator-regime-2026-09

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KR-BRIDGE-03-GENERATOR-REGIME-2026-09`, `informative strata`, `n_rec` · **Aliases:** `KR-BRIDGE-03`
**Candidate group membership (NOT an identity claim):**
- **G1880** [`kr-bridge-03-generator-regime-2026-09` · `theory-doc-series-00-14-information-transformation-2026-09`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0066, scope THEORY-LEVEL): A follow-on bridge experiment varying the synthetic generator's redundancy-distribution regime (9 regimes calibrated, 2 run) to test whether increasing informative strata changes the Zero/preservation bridge verdict; finds flattening the redundancy distribution does not raise informative strata (mass moves into deterministic extremes) while record count does, and reproduces H1-not-refuted at 13x power with only 1 surviving cell (down from 14) and 0 substantive on both splits. Continues the existing kr-bridge-01/kr-bridge-02 object family as a distinct, separately-registered third experiment.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2742] §"Power is a property of the informative region, not of n."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2754] §"KR-BRIDGE-03-GENERATOR-REGIME-2026-09 ... 9 regimes calibrated, 2 run → H1 NOT REFUTED at 13× power"

## Lifecycle
last_seen: S2754. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S2748 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2754 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S2742, S2748 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
KR-BRIDGE-03 found flattening the redundancy distribution moved informative strata only 73->75 because flattening moves mass into deterministic extremes (r=0: Zero never fires; r=3: Zero always fires) that cannot be informative by construction; the effective lever instead is record count (73->233 at n_rec 4->6), while population size is not a lever (73/74/70 at n=1500/4000/8000) [S2748].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2742] types=[EXPERIMENTAL-RESULT] scope=METHODOLOGICAL — "Measured across four candidate power levers: population size (no effect, 73/74/70 across n=1500/4000/8000) and flattening the confounder distribution (73->75, no effect) both do nothing, while record count (73->233) and finer stratification variable (~30->388) are the effective levers; when power stops responding to sampling, the constraint has moved into the design (the 67 dead cells are invariant across nine generator regimes because the adequacy definition itself saturates)." (anchor: "Power is a property of the informative region, not of n.")
- [S2748] types=[EXPERIMENTAL-RESULT, EXPLANATION] scope=OBJECT — "KR-BRIDGE-03 found flattening the redundancy distribution moved informative strata only 73->75 because flattening moves mass into deterministic extremes (r=0: Zero never fires; r=3: Zero always fires) that cannot be informative by construction; the effective lever instead is record count (73->233 at n_rec 4->6), while population size is not a lever (73/74/70 at n=1500/4000/8000)." (anchor: "Flattening the redundancy distribution does not increase informative strata")
- [S2748] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Cause identified as adequacy saturation (most (T,Q) pairs fully preserve or fully destroy Q, 25-31 of 35 combinations sitting at 0.00 or 1.00), a property of the adequacy definition rather than the generator, evidenced across nine regimes; the remaining lever is (T,Q) designs that partially preserve each Q." (anchor: "67 dead cells, in every one of the nine regimes. The count did not move by one.")
- [S2754] types=[GOVERNANCE, RESTATEMENT] scope=OBJECT — "Canonical-ID registration for the generator-regime bridge experiment, cross-referencing its result (H1 not refuted at 13x power) already detailed in theory doc 07." (anchor: "KR-BRIDGE-03-GENERATOR-REGIME-2026-09 ... 9 regimes calibrated, 2 run → H1 NOT REFUTED at 13× power")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
