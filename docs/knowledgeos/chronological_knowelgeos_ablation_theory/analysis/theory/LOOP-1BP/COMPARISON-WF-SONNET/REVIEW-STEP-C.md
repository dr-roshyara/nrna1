# Step-C review: bundle-02 within-family cross-check (Sonnet 5.5 vs the sealed Claude Opus side)

**Reviewer:** an independent read-only subagent (Claude family). It read the sealed values on both sides and re-ran the original program's predicates.

## Comparator output (frozen r11, `d8da904a…`)
- **BLIND:** 42/42 AGREEMENT.
- **Disagreements:** 40 (39 EXPOSED, 1 NONLICENSING).
- **INCOMPLETE:** 191 (format).
- **Unrecognized keys:** 1,425 (Sonnet's extra fields).
- **Licence:** not granted, by rule.

## Classification
| Group | Units | Class | To human |
|---|---|---|---|
| G1: K4b and exists(…H_d2) computed with the reading's classes (Sonnet NOTES 19) vs the base classes (original NOTES 13–14) | 34 | **(c) spec ambiguity.** V1B-SPEC Def P0.3 writes "H-D⁺ ∩ H-B" with no 𝐑, which leans literal toward the original, but P0.3's "per reading 𝐑" and Lem 3.7's ⁺ operator support the other reading. Sonnet's values equal the original's `K4b_with_reading_classes` 264/264 and its reading version of exists 24/24. | yes |
| G2: R5 witnesses K8.1, K8.4, K8.7, K9.1, K9.8 (original null, Sonnet a history) | 5 | **(c) witness semantics underspecified** for composite checks. Sonnet's histories were re-verified with the original program's own predicates as valid and least. The `result` values agree. | yes |
| G3: K10.5 witness | 1 | **(c) format plus a comparator extraction artifact.** The original reported the same least witness inside a nested `{h, slot}` entry. The comparator's role filter (`'sigma' in h`) skipped it and compared against the original's spec-example entry. The values are identical. | yes |
| INCOMPLETE and unrecognized | 191 / 1,425 | **Format.** Mapped by hand, the values agree: member, iS, iiO_c and iiiL 792/792; K8.1 per-o set equal; K12.1 table equal; K12.2 failing properties equal. Sonnet did not split A9 into con-only and d-only, so that finer finding is not cross-confirmed. | no (information) |

## The eight questions (all groups)
- Real disagreement on substance: **no**.
- Implementation error: **no**.
- Interpretation or underspecification: **yes**.
- Affects an R4 statement: **no**.
- Finite check only: **yes**.
- Affects an empirical conclusion: **no**.
- Reopen R4: **no**.

## Verdict on substance
Once interpretation and format are accounted for, there is **no discrepancy in any truth value, count or set** between two independently written Claude implementations (Opus 5.5 and Sonnet 5.5). **This is within-family evidence only** (F-LOG-0238).

## Comparator defect, recorded and not repaired
For the K10.5-type nested `{h, slot}` witness, the r11 role filter skips the entry. The effect here is one NONLICENSING unit compared against the wrong role. The comparator is frozen; a fix would be a new revision, made before the non-Claude return is compared, and only on a human act.
