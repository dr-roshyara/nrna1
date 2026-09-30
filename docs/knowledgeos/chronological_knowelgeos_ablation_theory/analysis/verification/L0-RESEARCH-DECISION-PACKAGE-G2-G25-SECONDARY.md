# L0 research-decision package — Gate 2 and Gate 2.5 (SECONDARY verification)

| | |
|---|---|
| Kind | research-decision package. ⚠ authority: generated. **Advisory; decides nothing** |
| Verification class | **SECONDARY_REVIEW**: blind headless Claude verifiers, the same family as the reference. The L0 **non-Claude** requirement is **still OPEN** for both gates, so nothing here may be used for a theory decision until it is met |
| Records | F-LOG-0071…0075 · adapter `adapt_blind.py` `5cd8d170…` (committed before comparison) · frozen comparator `compare_mech.py` `2c286c93…` |

## 1. Reproduction status

| Gate | Reference | Secondary verifier | Status |
|---|---|---|---|
| 2 | `results_ref_r2.json` `2782d765…` | r2 `071cec0a…` (F-LOG-0064) and r2b `1e466068…` (this package) | two Claude replicates |
| 2.5 | `results_ref.json` `1771c79a…` | r1 `52b6ab6a…` | one Claude replicate |

## 2. Mechanical comparison (frozen comparator)

| Gate | EXACT | non-EXACT (by cause) |
|---|---|---|
| 2 (r2b) | C-1 66 · C-2 300 · C-3 51 · C-4 4,158 · C-5 209 · C-6 117 | 21 applicability token (`RUN` vs `APPLICABLE`); 45 C-2 EXTRA (the verifier reports per-property N/A on antichain2 where the reference reports the instance N/A); 15 C-2 + 132 C-4 HOLDS↔VACUOUS (`both_true`, the same sites as F-LOG-0064); 4 C-3 EXTRA (`S4-T` = `NOT_RUN` for models without the variant); 27 C-5 EXTRA (minimal sets for properties that are VACUOUS in the reference); 198 C-6 MISSING (both sides have no witness); 10 unmapped auxiliaries |
| 2.5 (r1) | C-1 45 · C-2 270 · C-3 305 (including every t_a/t_p field) · C-4 4,320 · C-5 126 · C-6 123 | 240 C-4 EXTRA (the verifier also ablates REP, which the reference reports as a scenario); 126 C-6 MISSING (both sides have no witness); **21 C-6 NUMERIC, all REVAL-a/b**; 17 unmapped auxiliaries |

DIFF sha256: G2 `e179e618…` · G25 `366533b5…`.

## 3. r2-7 classification

- **Gate 2: REPRODUCED UNDER A DIFFERENT READING** (secondary). No truth-value difference. Every non-EXACT entry is a token, a class-reading (HOLDS vs VACUOUS) or a presence reading. The same pattern as F-LOG-0064 (15 / 132 / 27).
- **Gate 2.5: REPRODUCED UNDER A DIFFERENT READING** (secondary).
  - C-1 to C-5 are exact, including every scenario's t_a/t_p (e, u, E0, EB).
  - The 21 C-6 differences are all REVAL witnesses. The verifier (its METHOD reading 8) reports the full trajectory from a fresh state through the start state y; the reference reports the path from y. Same classes; a witness-origin reading.
- C-7 readings: reference R1–R8 (`analysis/gate25/METHOD.md`); verifier readings 1–14 (`BLIND-HEADLESS-r1/METHOD.md`).

## 4. Reproduced formal facts (model properties under SPEC-G25-r1; identical on chain3, V and diamond)

These are statements about the **models**, not about the corpus or the theory.

| Property | MT0 | MT1-P0 | MT1-P1 | MT2-P0 | MT2-P1 |
|---|---|---|---|---|---|
| AUTH-SAFETY (EF) | F | H | H | H | H |
| AUTH-/EVENT-EVIDENCE-SAFETY (unscoped) | F | F | F | F | F |
| AUTH-EVIDENCE-SAFETY-scoped | F | H | H | H | H |
| **AUTH-ELIGIBILITY-SAFETY** | F | F | F | F | F |
| EVENT-FLOOR-SAFETY = EVENT-EVIDENCE-SAFETY-scoped | F | H | **F** | H | H |
| **EVENT-ELIGIBILITY-SAFETY** | F | F | F | F | F |
| TOCTOU (spec pattern) | Y | Y | Y | Y | Y |
| PERSIST (ad ∧ ¬EF) | Y | N | Y | N | Y |
| AUTO-INVAL / REVOC-EXPLICIT | F / F | H / F | F / H | H / F | F / H |
| REVAL-a / REVAL-b | Y / Y | N / Y | Y / Y | N / Y | N / Y |
| P-GUARD | F | H | H | H | H |
| D1, D2, D6 | H | H | H | H | H |
| REP, T1–T6 | all reachable in every model |

Facts worth separating:

1. **Below-bar authorization and promotion are expressible in every model.** AUTH- and EVENT-ELIGIBILITY-SAFETY fail everywhere, and every T4 witness has EB = false at t_a. No primary model forces eligibility (EB) at promotion. So the R-39 shape (evidence present, authorized, below the bar, promoted) is **representable in all four primary models**. That is a property of the model family, not a finding about R-39.
2. **The genuine time-of-check gap exists only in MT1-P1.** The spec's TOCTOU/T4 pattern (authorization → a ¬EF state → promotion) is reachable in **every** model. But only in MT1-P1 does the promotion's source state have ¬EF: T4 t_p has E0 = false. In MT1-P0, MT2-P0 and MT2-P1 the shortest witness **restores evidence** (EVIDREF to n0, then EVID back to n1) before promoting, so t_p has E0 = true. The floor-at-event question is answered by EVENT-FLOOR-SAFETY, which fails only in MT1-P1.
3. **P0 and P1 are complementary by construction.** AUTO-INVAL holds exactly in P0, and REVOC-EXPLICIT exactly in P1. No model has both. PERSIST is reachable exactly in P1.
4. **The event guard T-EVENT is sufficient for the floor at the event**, and T-P0 (with T-G1) is an alternative route. In MT2-P1, T-EVENT is the **only** route.
5. **T-AM (authorization monotone) is redundant** for every holding property in every model. T-G1 is redundant in the P1 models. Redundancy is relative to the property list, not a statement about design value.
6. **Unscoped E0 safety fails trivially** (the u = E bar gives EB without E0). Only the scoped forms are informative (disclosed in the addendum).

## 5. Prediction check (the pre-registered r1-7; after the classification)

- Of the 18 predicted rows, **16 match exactly**, in all five models and all three instances.
- Two rows (TOCTOU, T4) mismatch in 3 models each, which is 6 cells.
- **Mismatch:** TOCTOU and T4 were predicted *not reachable* in MT1-P0, MT2-P0 and MT2-P1, and they are reachable.
- **Cause:** a gap between the formalization and the prediction. The predictions assumed "promotion **while** ¬EF". The spec's pattern only requires a ¬EF state **between** authorization and promotion. This is recorded as a finding; the frozen spec is not changed.
- The intended reading is already measured by EVENT-FLOOR-SAFETY, which matches the prediction in every model.
- A future strict form (TOCTOU-strict: the PromEvent source has ¬EF) is FRN-5, not applied.

## 6. Unresolved formal alternatives (no selection)

- **Authorization-only vs event guard (MT1 vs MT2).** Differs only in whether the floor is re-checked at promotion; MT1-P1 alone has the gap.
- **Evidence-coupled vs explicit revocation (P0 vs P1).** Automatic invalidation vs traceable revocation with persistence.
- **Floor definition.** EF = EB ∨ E0 is a modelling choice. An EB-only guard would make the R-39 shape **unrepresentable** at the guarded point. Not modelled (FRN-2 / EQ-13).
- **Eligibility persistence.** A PERSIST-EB form (ad = 1 ∧ ¬EB reachable) is a future property (FRN-2).
- **Witness dependence on free components** (FRN-1): not yet reviewed. That review is needed before any witness is used as an argument.

## 7. Evidence gaps (the corpus has not been read for any of these)

- Whether authorization is a snapshot entitlement or re-checked at execution (OQ-T1).
- Whether adoption survives deterioration (OQ-T2); whether bar changes affect pending or adopted items (OQ-T3); whether validation is a condition or an expectation (OQ-T4).
- Whether any source supports evidence **below** the bar being sufficient for an exception (EQ-13).

## 8. Proposed targeted corpus questions (template `prompts/KNOWLEDGEOS-TEMPORAL-EVIDENCE-MATRIX-TEMPLATE.md`)

- EQ-1…EQ-13, with priority EQ-2 (check time), EQ-3/4 (persistence and revocation), EQ-13 (floor), EQ-6/7/8 (bar changes; pending and adopted items).
- Sources in order: CAP-001 §9 → EPIC-004 → others, **each under its own L0 release**. Not read.

## 9. Decisions requested from L0 (none taken here)

1. Accept the secondary classification as recorded, or require the non-Claude verification before any further step.
2. Commission the non-Claude verifiers (the bundles and `~/VERIFIER-COMMISSIONING-PROMPT.txt` are ready). Codex was cancelled on the human's instruction.
3. Release CAP-001 §9 for the EQ matrix, or defer.
4. Whether FRN-5 (TOCTOU-strict) and PERSIST-EB enter a later, separately pre-registered experiment.
