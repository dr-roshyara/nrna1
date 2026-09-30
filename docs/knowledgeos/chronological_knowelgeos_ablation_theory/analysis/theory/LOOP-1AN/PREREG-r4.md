# 1an-r4: pre-registration (frozen before the first run)

- **Question:** which of the 1an guard requirements generalize beyond the decisions that force them?
- **Frozen instrument:** `minimize_1an_r4.py`: forced-pair recurrence · LOCO · disjoint support · operation test with shared-APPL comparability. The R-44 SUPERSEDE event is added from its frozen 1am-1 coding.

| Result | Consequence |
|---|---|
| guard variable GENERALIZING, with LOCO correct > 0 and wrong = 0 | the guard is **cross-cluster supported** (formal, model-relative); it is a candidate for the minimal theory |
| HALF-LOOKUP / LOOKUP, or LOCO abstains / wrong | the guard fits its training decisions only: **not a generalizing requirement** on current data |
| INSUFFICIENT | untested for generalization; it needs more clusters (an evidence need, recorded as a designed observation) |
| operation test power ≥ 1 | operation becomes testable; the verdict is as defined |

**Sealed main-analyst expectation (bias check):**
- START s GENERALIZING, with LOCO coverage > 0 and 0 wrong;
- RAISE e: LOCO abstains or is wrong (single cluster);
- ADOPT, REGISTER, OPEN-WORK, AUTHORIZE-IMPL, ASSIGN-ID: INSUFFICIENT;
- SUPERSEDE +R44: {k} is the unique guard, but INSUFFICIENT or LOOKUP;
- operation test power small but ≥ 1, with 0 decisive → MODEL-COND-REDUNDANT.

Low-moderate confidence on the last item.
