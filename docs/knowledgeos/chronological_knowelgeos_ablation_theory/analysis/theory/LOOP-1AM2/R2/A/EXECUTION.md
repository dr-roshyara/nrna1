# EXECUTION: what determines a standing-raising outcome?

Inputs: TASK.md and SOURCES.md only. Nothing else was opened and no commands were run. The full coding, with a quote for every non-UNK field, is in `EXECUTION.json`.

## 1. Events

- **OBSERVATION:** I coded 27 decision items in 5 clusters:
  - **C1 (S1+S2):** ES-004.3 / R-41. 1 item.
  - **C2 (S3):** O-CLOSURE-VOCAB. 1 item.
  - **C3 (S4+S5):** the R-36 AI Architecture Promotion Review. 21 items: the 19 matrix rows, with #5 split into its scoped and generalized forms, plus the ARB-added "implementation-evidence-outweighs-unverified-theory".
  - **C4 (S6):** the DDD implementation protocol. 3 items.
  - **C5 (S7):** R-39. 1 item.
- **SOURCE FACT:** E01, ES-004.3. The PA adopted it as a "permanent documentation standard" (S1, S2). Evidence count: UNK.
- **SOURCE FACT:** E02, the three-planes observation. It was "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one" (S3).
- **SOURCE FACT:** C3. R-36 promoted 6 behaviours into AST-013 (OPERATING_INSTRUCTIONS) and "expressly NOT promoted" the others (S5). S4 gives the per-item evidence in independent slices.
- **SOURCE FACT:** E24 and E25, the protocol. It was "ADOPTED AS THE OPERATING STANDARD … NOT self-promoted into a standards document". Evidence is "two slices". The stated reason for not promoting is that Phase 16 forbids promoting observations into standards without authorization (S6).
- **SOURCE FACT:** E27, R-39. The Decision Authority promoted the principles to methodology with evidence from "ONE context", "recorded as explicit exception"; the ES-006.1 multi-context bar is kept for future promotions (S7).

## 2. Falsification pool

- **FORMAL CONSEQUENCE:** P, meaning PROMOTED and not CHOICE-grounded: E01 (PA, STD, UNK), E10 (ARB, OI, 3), E23 (ARB, OI, UNK), E24 (PA, OTHER, 2) and E27 (DA, METH, 1).
- **FORMAL CONSEQUENCE:** Q, meaning NOT-PROMOTED on a RULE ground: E02 (UNK, METH, 1), E20 (ARB, OI, 1), E22 (ARB, STD, UNK) and E25 (session author, STD, 2).
- **FORMAL CONSEQUENCE:** E08 and E21 are NOT-PROMOTED but with ground UNK. They can only produce undetermined pairs.

## 3. Model verdicts

| Model | Verdict | Deciding pairs |
|---|---|---|
| M_E | **FALSIFIED** | (E02,E27), (E20,E27), (E25,E27), (E25,E24) |
| M_A | **UNDETERMINED** | none |
| M_T | **FALSIFIED** | (E02,E27): both METHODOLOGY; 1 ≥ 1 |
| M_AT | **UNDETERMINED** | none |
| M_C | **not established as needed** | M_A and M_AT are not falsified |

- **FORMAL CONSEQUENCE:** M_E fails even on a same-unit pair. The same protocol, with 2 slices, was PROMOTED to "operating standard" (E24) but NOT-PROMOTED on a RULE into a standards document (E25).
- **FORMAL CONSEQUENCE:** M_T fails on E02 vs E27. Both are methodology promotions under ES-006.1, both have evidence 1, and they have opposite outcomes. The pair compares units (occurrence vs context).
- **FORMAL CONSEQUENCE:** M_A and M_AT have no deciding pair. The main open pair is (E02,E27), which stays undetermined only because E02's actor is UNK. The two candidates the text offers (PO/ARB, or the log author) both differ from the Decision Authority, so neither reading would falsify these models.
- **FORMAL CONSEQUENCE:** The remaining undetermined pairs come from UNK evidence (E01, E22, E23) or UNK ground (E08, E21).
- **INFERENCE:** The pairs that separate outcomes are separated by who acted, together with whether an exception was recorded. The Decision Authority promoted at 1 by recorded exception. A non-authority actor (E25), or an actor not named (E02), did not promote at 1 or 2 because a rule forbade it.
- **HYPOTHESIS:** Taken literally, the text's own mechanism is "evidence bar (ES-006.1 / Phase 16) plus explicit authority exception". That is closer to M_A than to an evidence-only threshold. But an exception is not a threshold parameter, so a strict reading could call it M_C. The frozen models cannot tell these apart on this corpus.
- **UNKNOWN:** Whether M_A or M_AT survives. That depends on E02's actor and on the evidence counts for E01, E22 and E23.

## 4. Where the text was ambiguous, and how I coded it

1. **UNKNOWN / OBSERVATION: S7 is outside "S1…S6".** TASK.md names S1…S6, but SOURCES.md contains S7. I coded it because it is in SOURCES.md. Without S7 (E27), M_E would still be falsified via (E25,E24). M_T would have no deciding pair.
2. **OBSERVATION: E01 evidence.** The provenance names one pre-adoption instance, and a "second instance" was found after adoption. No count is stated as the basis, so I coded UNK. Under either reading (1 or 2), (E25,E01) would also falsify M_T.
3. **OBSERVATION: E01 target.** The text says "documentation standard, not a new standard document". I normalized it to STANDARD-DOC, since the task lists "a standard or a standards document".
4. **OBSERVATION: E02 actor.** The disposition is passive, so I coded UNK. The candidates are PO/ARB and the log author.
5. **INFERENCE: R-36 actor = ARB.** This rests on "ARB wording refinement at adoption" and "2 added by ARB at review". The text never states outright who adopted R-36.
6. **INFERENCE: which items R-36 means.** R-36 names some non-promoted items only by count (C 4, D 5, already 4). The mapping I used is consistent with every count:
   - C: #5, #12, #15, #16.
   - D: #5-generalized, #14, #17, #18, #19.
   - already: #1, #4, #8, #13.
   - ARB-added promotions: #7 and a new "implementation-evidence" line.

   #7 was "already" in S4 but promoted in R-36. It is uncertain whether "implementation-evidence" is a reworded #1. Either way its evidence is UNK.
7. **OBSERVATION: target of R-36 non-promotions.** I coded OPERATING-INSTRUCTIONS, because the review's only promotion home was AST-013 and "new documents 0". The exceptions are the B items (#6, #11 → PRINCIPLES-DOC) and #19 (STANDARD-DOC). For the D items, the home they were denied is not stated per item.
8. **OBSERVATION: evidence as ranges or vague counts.** #10 is "2–3" and #18 is "2-ish". I coded both UNK. I used #10's range as a bound: since 2 > 1, it cannot pair with the evidence-1 Q items.
9. **OBSERVATION: counts given as reference lists.** #1, #8, #13, #16 and #19 list references rather than numbers, so I coded UNK.
10. **OBSERVATION: mixed units.** Evidence is counted in occurrences, slices, contexts and demonstrations. I compared the integers as the frozen rule requires, and flagged every cross-unit pair in the JSON.
11. **OBSERVATION: grounds.**
    - S4 category-A promotions: CHOICE, as a selection among categories A–D on the evidence column.
    - "already" / B / C / #14: CHOICE.
    - #17: RULE. It cites the PB-005 Q1 ruling and ER-02 "replaced only on repeated evidence".
    - #19: RULE ("would violate Documentation Economy").
    - #5-generalized and #18: UNK. Only a count or a revisit trigger is given.
    - E01, E10, E23, E24 and E27: UNK. Each has reasons, but no requiring rule and no options.
    - R-39 was promoted *against* a cited rule, by exception. Coding it CHOICE instead would remove M_T's deciding pair.
12. **OBSERVATION: S6 splits into three items.** Adoption as an operating standard: PROMOTED, target OTHER. Not self-promoted into a standards document: NOT-PROMOTED, RULE. The DA's pending choice between options (a) and (b): outcome UNK.
13. **OBSERVATION: items I excluded as not standing-raising.** S2's ES-004.3 role-based refinement ("No new register row … refinement of the same rule"). The "validated unchanged" declaration. The O-2 watch item ("single instance, below the evidence bar"), which is not explicitly a promotion decision. F-2, which was referred out. S3's binding vocabulary ruling, which is a ruling but not stated as a promotion into a standard, instructions or methodology. R-36's AST-008 removal.
    - **HYPOTHESIS:** If O-2 were included, as NOT-PROMOTED with evidence 1, RULE-like and target STANDARD-DOC, it would only add a pair with E01 that stays UNDETERMINED.
14. **OBSERVATION: my verdict convention.** The task does not define a model-level verdict when a model has undetermined pairs but no deciding pair. I used UNDETERMINED for that case, and SURVIVES only when no pair could falsify the model.
