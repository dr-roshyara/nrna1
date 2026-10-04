# Cross-family cross-check of R5 and V1b: pre-registered comparison (fixed before any return exists)

**Purpose:** a non-Claude implementer reimplements the R5 and V1b enumerations from the stripped specs. Its results are compared with the sealed Claude results (`LOOP-1BL/RETURN/RESULTS.json`; `LOOP-1BN/V1B-RETURN/V1B-RESULTS.json` + `SUPP-RESULTS.json`).

**Bundle:** `~/F-XCHECK-BUNDLE-01.tar`, sha256 in `BUNDLE-TAR.sha256`. Contents: README, TASK, R5-SPEC, V1B-SPEC, V1B-SUPP-SPEC. No proofs, no expected outcomes, no Claude results.

## Comparison units (each scored AGREE / DISAGREE / NOT_REPORTED)
1. **R5:** `n_frame_histories` (665,920); for each of the 33 checks, the boolean result; for checks with a reported least witness or first counterexample, coordinate equality; K8.1's difference set; the K12.1 and K12.2 sets.
2. **V1b:** for each of the 12 readings × 2 frames × 5 check forms, the `holds` boolean and the least counterexample (coordinate equality); each named-history field.

## Interpretation rules (fixed now)
- **Agreement on every reported unit** → **cross-family reproduction of the finite results.** It upgrades R5/V1b from "Claude-family" to "reproduced across model families" **for the finite claims only**. The generic statements still rest on the proofs.
- **Any DISAGREE** → classify it, before any judgement about the theory, as one of:
  - (a) the non-Claude side's implementation error;
  - (b) the Claude side's implementation error;
  - (c) a spec ambiguity;
  - (d) a genuine theory-level discrepancy.

  The classification is done by a reviewer **with both programs**. **No result is corrected silently. A (b) or (d) → human gate** (R4/R5/V1b status).
- **A reading choice disclosed in NOTES** that explains a DISAGREE counts as (c), and is reported.
- **NOT_REPORTED units** are listed. They are not counted as agreement.

## Not claimed
Cross-family agreement on finite checks is not proof of the generic statements, not empirical validation, and not evidence about the corpus.
