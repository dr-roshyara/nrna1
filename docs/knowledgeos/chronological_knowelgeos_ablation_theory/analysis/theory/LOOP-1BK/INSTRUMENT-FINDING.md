# 1bj reconciliation: the coarse binary core instrument (evidence; the pre-registered consequence is stated, not adopted)

## Verdict (pre-registered rule, applied literally)
**FAIL: 2 of 9 pair × field κ ≥ 0.67.** The independent review recomputed every value with its own code, and all match `COMPARISON.json`. No value is undefined.

| pair | ACT_REPORTED | ACT_SELF_PERFORMED | NORM_STATED |
|---|---|---|---|
| opus~sonnet | 1.000 P | **0.598 F** | 0.941 P |
| haiku~opus | 0.400 F | 0.583 F | 0.643 F |
| haiku~sonnet | 0.400 F | 0.637 F | 0.589 F |

**The FAIL does not depend on Haiku.** opus~sonnet fails ACT_SELF_PERFORMED. Any exclusion of a coder would be post hoc and forbidden; this is noted only to block that reading.

## Integrity (review)
- The seal holds: 15/15 files OK.
- Each coder received the identical bundle.
- The comparator was committed before the runs and is unchanged.
- Each coder made 5 reads of its own bundle and 2 writes, with no Bash.
- Model ids are confirmed; all exits were 0.
- **Haiku compliance defects (not invalidating):**
  - every status is SOURCE_FACT;
  - its notes are empty;
  - its summary contradicts its own CODING.json.
- An ancestor generic CLAUDE.md may have loaded, with no project content.
- The coder schema is 1,107 words by `wc`, against a ≤ 1,000 target.

## Disagreement anatomy (EXPLORATORY, one reviewer's judgement; it cannot change the verdict)

**opus~sonnet: 9 cells.**
- **ACT_SELF_PERFORMED: 8 cells, all class (c), schema admits both:** R05, R10, R13, R14, R23, R27, R34 and R36. **Each turns on whether a register/ruling row's words *perform* the ruling or *describe* it.**
- **NORM_STATED: 1 cell (R15, a Sonnet deviation, low confidence).**

**Haiku pairs: about 26 cells.**
- About **12 are class (a)**, Haiku deviating from the schema: R02 double-counts a performative; R11 is coded NO by default; the R15–R21 evidence columns are coded NO. That is a competence problem.
- About **14 are class (c)**, the schema admitting both answers.

**ACT_SELF_PERFORMED fails in the register and ruling rows, not in the R15–R21 matrix rows**: all coders give SP = NO on R15–R21.

## Sealed expectation vs result (bias check)

| field | expectation | result |
|---|---|---|
| NORM_STATED | PASS, κ ≈ 0.75–0.9 | opus~sonnet 0.94; Haiku pairs 0.59 / 0.64 → **partly confirmed** |
| ACT_REPORTED | borderline, 0.6–0.8 | 1.0 / 0.40 / 0.40 → **not confirmed**: bimodal, driven by coder competence |
| ACT_SELF_PERFORMED | weakest, 0.5–0.75 | 0.58–0.64 → **confirmed** |
| overall pass | < 50% likely | FAIL → **confirmed** |

## What it shows (bounded)
- **Within the Claude family, on these 36 records, with no ground truth:** whether a governance text *performs* an act or *reports* one is **not reliably codable, even as a binary distinction**.
- This is the same performative question that sat under R-A3 / CI1 (F-LOG-0172) and SCHEMA-r3's TC-1. It is the instrument-level counterpart of H5: the register entries are both the act and its record.
- **Exploratory only (not a reliability claim):** two capable coders reproduced ACT_REPORTED and NORM_STATED almost exactly.

## What it does not show
- anything cross-family, about human coders, or about other corpora;
- which coding is correct;
- that the act/norm distinction is conceptually invalid.

## Pre-registered consequence (PREREG: FAIL → option A), for human acknowledgement
**Option A:** the act/norm observation layer is not reliably codable on this corpus, at either grain.

The R2_DEPENDENT items in R3 stay NOT USABLE BY R4. The theory proceeds on the coding-independent base:
- D1 as a structural identifiability condition;
- the Protocol A/B results;
- scope and provenance;
- the methodological findings;
- the identifiability and measurement limits themselves.

**Candidate theoretical concept (L2 hypothesis, NOT established):** *performative ambiguity*. In a self-recording governance register, the record of an act and the act itself coincide. Whether an entry is "the act" or "a report of the act" is therefore not determined by the text. This would explain together:
- (i) the R-A3 convention problem;
- (ii) the non-identifiability of H-D vs H-B (H5);
- (iii) the failure of ACT_SELF_PERFORMED.

It requires formal treatment in R4 and independent testing; it is not an observation.
