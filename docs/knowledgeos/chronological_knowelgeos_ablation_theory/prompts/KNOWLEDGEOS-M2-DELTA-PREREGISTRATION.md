# M2 (a delta on M1): generalization-test pre-registration, frozen before any sampled row is read

| | |
|---|---|
| Status | **Frozen at commit before the reads.** Not canonical. Authority: none. M0 and M1 are unchanged; M2 = M1 plus the deltas in §1 |
| Regime | **pre-template, human-authority regime**: rows R-42…R-80 (2026-07-30…08-03). Per the R-81 annotation (source): "every prior ruling R-1..R-80 was issued by the human authority". Different template, issuer process and days from the 1s hold-out |
| Source | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` `7795c14b…` |

## 1. Deltas (from F-LOG-0123)
- Frame⁺(ADOPT) = {status, annotation-role}.
- New operation ALLOCATE with Frame⁺ {obligation}.
- New operation PERMIT-CONSIDERATION with Frame⁺ {consideration}.
- **Operation-category rule (anti-fitting).** "Approval", "Ratification", "Directive" and "Adjudication of a characterization" are **not** in the vocabulary.
  - A row's act may be mapped to an existing operation **only if the row's own text defines it as that operation**.
  - Otherwise the row's changes are **NOT-MODELLED**, never VIOLATED.

## 2. Sample (frozen rule, applied to the locate output)
- **Locate output:** ID, date and the headline triple "<X> Governance · <Operation> · <Authority>" only.
- **Rule:**
  - (a) for each operation category **absent from the 1s hold-out** (Approval, Ratification, Directive, Adjudication of a characterization), take the **earliest** row;
  - (b) add the row whose authority field carries a **qualifier**;
  - (c) exclude used rows (R-47, R-53, R-66).
- **Sample:**

  | Row | Line | Category |
  |---|---|---|
  | R-43 | L29 | Delivery · Approval |
  | R-44 | L30 | Architecture · Ratification |
  | R-72 | L58 | Execution · Authorization · "ARB — SUBJECT TO A PROVISO THAT IS NOT YET SATISFIED" |
  | R-77 | L63 | Architecture · Adjudication of a characterization |
  | R-79 | L65 | Execution · Directive |

- **Disclosure:** all five also contained ≥ 1 frame-phrase class in the F-LOG-0122 locate. 51 of the 71 rows do, so this is not a strong filter, but it is not zero.

## 3. Predictions (results: SUPPORTED · VIOLATED · UNTESTABLE · NOT-MODELLED · UNKNOWN)

| # | Prediction | VIOLATED iff |
|---|---|---|
| P1 | no row amends another ruling's decision text in place | the row states it edits or replaces another ruling's decision text in place |
| **P1-R43** (cross-row) | R-43's decision text does not contain R-53's note; R-43 carries a **forward-pointer annotation** to R-53, as R-53 states ("R-43 receives a minimal forward-pointer status annotation") | R-43 has no pointer to R-53, **or** the note's content is written into R-43's decision text |
| P2 | every authorization stated is scope-bounded | an unscoped authorization, or one extended by implication |
| **P3′** (regime) | **no PREPARED phase: the ruling is governing at issue** | the row states that the ruling itself awaits a decision act or is not yet governing. **A proviso on an authorization's EFFECT is not a status**; it is recorded as a GUARD observation |
| P4 | every stated change lies in Frame⁺ (M2) of the performing operation | a change outside Frame⁺ while all performing operations are in the vocabulary (otherwise NOT-MODELLED) |
| P5 | evidence changes no standing without a separate operation | the row states a standing/status changed because of evidence alone |
| **P6** (frame generality) | every sampled row states ≥ 1 change **and** ≥ 1 explicit preservation/non-effect | a row states changes but no preservation/non-effect |

## 4. Reporting
- Report "k/5 in the pre-template regime", together with effective evidence units (shared text counts once).
- Operations are coded **before** changes.
- NOT-RECORDED never becomes ABSENT.
- No amendment after the first read.
