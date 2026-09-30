# 1am-2d: reconciliation. Compact task report. **The RAISE branch STOPS here**

| | |
|---|---|
| Question | Does any no-exception raise break the actor / target / raise-kind confound among M_A+X, M_T+X, M_K+X and M_AT+X? |
| Frozen | `PREREG.md` (`3ff922f74`); sources: register rows R-38, R-40, R-41, R-90; given priors E01, E02, E20 |
| Result | A: all four **NOT FALSIFIED**; confound **NONE**. Reviewer: **the same** |
| Key reviewer finding (D1, coding) | a missed event **R41-2**: the PA did **not** raise the rule as a *new standard document* ("hosted ONCE as ES-004.3 … not a new standard document"; "Parsimony honored") at the same 1 instance at which it raised it as an *extension*. Same actor, same evidence, the outcome split by raise_kind. **Ground UNK**: no rule ID is cited, and R-38 frames parsimony as an "ARB preference". If RULE: M_A is falsified (and only M_A). If CHOICE: not a legality witness |
| Other disagreements | source interpretation: D2 (R40-A4 is deferred, not refused), D3 (R40-A1 raise_kind UNK: an "expected" end-state), D4 (R38-2 is a precedent, not a raise), D5 (R90-1's actor is a proposer in a PREPARED / WITHDRAWN row) · coding: D6, D7, D8 · evidence scope: D9 (R-41 = 2 not excluded) · formal reasoning: D10 · wording: D11 |
| Bias check | the sealed expectation (confound not broken) matched |

## Why the branch stops (stop condition: the evidence cannot discriminate the remaining models)

- Across the already-read material and three targeted locates (1am-2c, 1am-2d, and the register-wide PA / ES-extension locate):
  - the only no-exception RAISED event with known evidence is **E01 / R-41**;
  - every known-evidence refusal differs from it in actor, target *and* raise-kind at once.
- Further reading of this slice has lower expected information than one designed observation.

## Result classification

| Proposition | Status |
|---|---|
| M_E (evidence only, scalar or vector) | **FALSIFIED**, conditional on E01 = 1 (inference strength; F-LOG-0151) |
| G_RAISE (single threshold ∨ recorded exception) | **FALSIFIED**, same condition |
| H-X (a below-threshold raise needs a recorded exception) | **not needed to fail** where the actor is PA and the kind is EXTENSION; 1 cluster (R-39) |
| M_A+X · M_T+X · M_K+X · M_AT+X | **observationally equivalent on this corpus slice** |
| **H-K-choice** (post hoc): raise_kind is a **CHOICE** dimension (placement parsimony), not a legality guard | HYPOTHESIS; the R41-2 ground is the discriminator |

## Designed observations (the smallest; for a future release or a targeted locate)

1. **Ground of the R-41 "Parsimony honored"** (a rule vs a preference). RULE falsifies M_A; CHOICE supports H-K-choice.
2. **A PA raise of a NEW item at evidence 1, with no exception** (M_A predicts raised; M_K predicts refused).
3. **An ARB / DA raise EXTENDING an existing standard at evidence 1, with no exception** (M_K predicts raised; M_A predicts refused).
