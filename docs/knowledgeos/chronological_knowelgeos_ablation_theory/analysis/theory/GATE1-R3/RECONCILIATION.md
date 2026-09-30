# Manual r3 effect: reconciliation (SECONDARY, same-family; not Gate 1 proper)

| | |
|---|---|
| Frozen | adoption + pre-registration `34d874221` · verification `ce0410fc3` · codings sealed `3efdd68d0` · scores + review task `fc42a7305` |
| Review | C1–C2 recomputation **matches**. B1–B2 was not recomputed (D10: the file names in the bundle were not found by the reviewer; mine, disclosed). B1–B2 was already recomputed in F-LOG-0159 |

## Pre-registered outcome: **TRADE-OFF** (row 3)
Gate 1 V1 0.393 → 0.857 and V4 0.750 → 0.929; **V2 −0.143, V3 −0.107**. The derived prediction (a larger gain on RESERVE) is **FALSIFIED** (D9: it was posed without a metric and against a V1 ceiling). The sealed expectation was right on V1 and V4 and wrong on "others stable" (D8).

## Causes (row-level, reviewer)

| Change | Cause | Class |
|---|---|---|
| V1 gain | **genuine** for the 17 vacuous-case splits (G1). **Convergent error** on R-31 (both coders applied G2 over G1's letter; D6). Possibly convergent on R-45 and R-35 | mostly true |
| V4 gain | genuine (R-52, R-55, R-67, R-93), conditional on what "status change" covers | true |
| **V2 drop** | **G7**: "mentions" undefined; the scope of the approval/acceptance exclusion unclear (R-35, R-49, R-56, R-63, R-68) | wording (D1) |
| **V3 drop** | **not a G-rule effect**: G6 lets unconstrained competing readings through, and V3 has no rule for changes made by not-modelled acts (R-36, R-40, R-61, R-97) | coding (D2) |

## Independence (D7)
Ops Jaccard is 1.0 on both bundles. On RESERVE it rose from 0.821 to 1.0 **although section G contains no operations rule**, which is **shared priors**. The claim "r3 improves reliability" is restated as **"r3 improves within-family reproducibility on V1/V4 at a cost on V2/V3."** Same-family agreement is an **upper bound**; a non-Claude coder is the real test.

## Recommendations for r4 (reviewer; RECOMMENDATION ONLY; human authorization required)

| Rule | Proposed fix | Type |
|---|---|---|
| G7 | define "mentions" as the four terms, in any word form | clarification |
| G7 | V2 is UNTESTABLE unless ≥ 2 of the terms are mentioned; the exclusion removes only the approval/acceptance pair | **substantive** |
| D-V3 | a direct change attributed to a not-modelled act is outside V3 | clarification |
| G6 | a competing reading counts only if it is consistent with the listed ops / not_modelled **and** changes this judgement's code | clarification |

## Model impact
- The coding instrument is **improvable but not converged**; each fix exposes the next ambiguity.
- The deeper issue sits **upstream**. F-LOG-0163 shows the schema conflates norm statements with act observations. **Refining V1–V7 wording before fixing that distinction optimizes the wrong layer.**
