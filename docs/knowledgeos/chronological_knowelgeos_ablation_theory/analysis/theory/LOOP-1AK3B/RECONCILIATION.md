# 1ak-3b: reconciliation. Compact task report

| | |
|---|---|
| Question | **U:** is evidence counted in contexts, with instances not substituting? **X:** does H-X (a below-threshold raise requires a recorded exception) survive? |
| Frozen scope | `PREREG.md` (`d0cdaa44a`); session log 2026-08-01, L440–487 and L662–685 |
| Execution | blind subagent A and reviewer B (Claude; SECONDARY); tool paths audited |

## Results

| | A | Reviewer | Reconciled |
|---|---|---|---|
| **U** | none | [E1] | **UNRESOLVED.** The substantive disagreement (D4) rests on three points: (i) whether "many instances" satisfies ≥ 2 (coding); (ii) whether E1's threshold unit is contexts (coding: A accepted the same sentence as E3's threshold); (iii) the reviewer's own alternative, that E1's outcome is **PENDING** ("Referred"; "the ARB's"), not HELD (source interpretation), which removes E1. The frozen rule resolves none of these. **Both readings are preserved; not averaged.** The reviewer's result matches the sealed expectation, so I do not side with it (bias control) |
| **X** | E4 (R-39) X-consistent | E4 X-consistent | **H-X survives, but with no new information.** E4 is the cited R-39 precedent, i.e. the same cluster (per the pre-registration). **No X-counter** |

- Other disagreements: D1 / D10 (formal reasoning); D2, D3, D7, D8, D9 (coding); D5, D6 (wording); D11 (source interpretation). None changes X.
- The reviewer adds a missed event (AP-1 / AP-2 rejected; G-1 accepted), with target, counts and threshold UNK.

## Observation that survives both codings: the unit problem

- Across the coded material of 1am-2, 1am-2b, 1am-2c and 1ak-3b, the stated promotion thresholds use **at least three different units**:

| Unit | Source |
|---|---|
| slices | R-36: "2 slices is the floor"; the PA criterion "WP-1 + a few more slices" |
| occurrences | ES-006.1 as applied 2026-08-15: "single occurrence … forbids promoting from one" |
| contexts | R-39: "evidence from more than one bounded context"; 2026-08-01 E3: "no insight from a single-context programme can ever be promoted" |

- **INFERENCE (model integrity: an *overloaded* existing dimension, not a new one):** the single variable `e` conflates at least two orthogonal counts, *instances / slices / occurrences* (repetition) and *contexts* (breadth). The 2026-08-01 E1 text itself separates them: "many instances, one context".
- **HYPOTHESIS H-E2:** e = (repetition, breadth), and the promotion guard is monotone in each.

  Not frozen; post hoc from coded material.

  What it would explain: in 1am-2, the M_E / M_T falsifications hold in contexts but not in slices (the review's finding). That is the signature of comparing a vector by one coordinate.

## Surviving / eliminated
- **Surviving:** M_A · M_AT · H-X (1 cluster) · H-E2 (post hoc).
- **Eliminated:** none new.
- **Evidence** stays **WEAK** as a single variable. Its *decomposition* is now the higher-information question.

## Next (by information gain)
1. **1ak-3c (formal, no new reading):** re-run the reconciled 1am-2b event set with e coded as a vector (repetition, breadth; UNK where not stated). Test whether M_E is falsified on every component, and whether any pair is monotone-inconsistent on breadth alone. A deterministic script, frozen before running.
2. **1am-3:** the ADOPT role-conformance pair.
