# Do the sources support the coded evidence values? (R-36)

The only sources used are `SOURCES.md` (R-36 plus matrix lines 23-50) and `CODED.json`.

## Per-event analysis

### RAISE promoted #2 → matrix #2 "Reuse Before Create" (A)
- **SOURCE FACT:** "3: PB-004 (...) · PB-005 (...) · PB-006 (...)". The column is headed "Evidence (independent slices)".
- **INFERENCE:** the coded `e`="2+" is **DERIVED**. The source value is 3 slices, and 3 ≥ 2. The string "2+" appears nowhere in the sources.
- **SOURCE FACT (outcome):** the matrix says "**PROMOTE** — one line in OPERATING_INSTRUCTIONS". R-36 says "Promoted into AST-013 ... reuse-before-create".
- **INFERENCE:** R-36 decides (see "Which source decides?" below).

### RAISE promoted #3 → matrix #3 "Ownership Drives Reuse" (A)
- **SOURCE FACT:** "3: PB-004 (ownership held stable ...) · PB-005 (Strangler/ACL NOT transferred because Contestation *owns* the Challenge ...) · PB-006 (delivery owned by Messaging ...)".
- **INFERENCE:** the coded `e` is **DERIVED** from 3 slices.
- **SOURCE FACT (outcome):** R-36 says "ownership-determines-architectural-reuse (never precedent)". The matrix says "**PROMOTE**".
- **INFERENCE:** R-36 wording differs from the matrix title, so the mapping is an inference.

### RAISE promoted #9 → matrix #9 "Surface gaps / Deferred ≠ skipped" (A)
- **SOURCE FACT:** "3: PB-004 (...) · PB-005 (... Deptrac "deferred, NOT skipped") · PB-006 (F-PB006-1 surfaced ...)".
- **INFERENCE:** the coded `e` is **DERIVED** from 3 slices.
- **SOURCE FACT (outcome):** R-36 says "deferred ≠ skipped". The matrix says "**PROMOTE**".

### RAISE promoted #10 → matrix #10 "Epistemic labeling" (A)
- **SOURCE FACT:** "2–3: Handover §9 (ARB-adopted Completion-Review discipline) · PB-006 Discovery (...) · PB-004/005 EP-02 reviews".
- **INFERENCE:** the coded `e` is **DERIVED**, and only weakly. The source gives a range of 2–3, and only the lower bound (2) puts it in "2+".
- **UNKNOWN:** whether Handover §9 counts as a "slice". It is a document, not a ticket. It is also unknown whether "PB-004/005" is one entry or two.
- **SOURCE FACT (outcome):** R-36 says "epistemic labels (...— broadened to ALL architectural recommendations/reviews)". The matrix says "**PROMOTE (relocation, not new governance)**".

### RAISE not promoted #5 → matrix #5 "Registration ≠ Delivery" (C, + generalization → D)
- **SOURCE FACT:** "1: PB-006 / ADR-MP-06 (recorded there as "permanent design principle")". R-36 says "(1 slice)".
- **INFERENCE:** the coded `e`="1" is **STATED**, in slices.
- **SOURCE FACT (outcome):** R-36 says "Expressly NOT promoted: Registration ≠ Delivery stays Messaging-scoped in ADR-MP-06 (1 slice)". The matrix says "Stays in ADR-MP-06 (Messaging-scoped)".
- **Reason type:** primarily **scope** ("Messaging-scoped"; Category C = platform documentation). The count is attached as a parenthetical. The matrix gives an evidence reason only for the *generalized* form: "has ONE demonstration → Candidate Pattern".
- **SOURCE FACT:** no bar for Category A is stated anywhere. The only stated bar is "2 slices is the floor", and it appears under #6 (Category B).

### RAISE not promoted #14 → matrix #14 "Strangler reconstitution" (D)
- **SOURCE FACT:** "1 context only (Election). PB-005 deliberately did NOT reuse it — evidence it does not generalize by default".
- **INFERENCE:** "1" is **STATED**, but the unit is **contexts**, not slices. The coded `e`=1 is therefore in a different unit from the other coded values.
- **SOURCE FACT (outcome):** R-36 says "... Strangler reconstitution ... remain Candidates", with no reason given. The matrix says "Remains Candidate".
- **Reason type:** **domain-specificity / non-generalization**. It is not a count below a stated bar.

### RAISE not promoted #17 → matrix #17 "`ChallengeResolvedIntegration` carrier" (D)
- **SOURCE FACT:** "1 — explicitly ruled "temporary carrier, not a pattern" (PB-005 Q1)".
- **INFERENCE:** "1" is **STATED**.
- **UNKNOWN:** the unit. It is a bare 1 under a "slices" header.
- **SOURCE FACT (outcome):** R-36 says "... `ChallengeResolvedIntegration` carrier ... remain Candidates". The matrix says "Remains candidate; replaced only on repeated evidence (ER-02)".
- **Reason type:** a **prior ruling** (PB-005 Q1). Evidence appears only as the condition for revisiting it.

## Which source decides?
- **SOURCE FACT:** R-36 is headed "ADOPTED" and says "promoted 6 (2 added by ARB at review)". Its list includes "stop-at-architectural-uncertainty", which the matrix (#7) marks "**already** ... No delta ... do not duplicate it". It also includes "implementation-evidence-outweighs-unverified-theory", which is absent from the matrix.
- **INFERENCE:** R-36 overrides the matrix, so R-36 is the deciding source. For the 7 coded events the two sources agree on the outcome.

## Evidence pairs
**n = 0.** The unit is independent slices.
- **INFERENCE:** there are 12 (promoted, not-promoted) pairs. All of them differ in count, and all of them also differ in other stated properties:
  - category: A vs C or D
  - destination: AST-013 vs ADR-MP-06 or the candidate backlog
  - stated reason: scope, non-generalization or a prior ruling
- **INFERENCE:** only #5 has a count in slices at all, so a unit-matched comparison is possible only for #5 against the promoted items. Even those pairs differ in category and scope.

## Ambiguities
1. The coded "2+" is a bucket; the sources give exact counts (3, 3, 3, 2–3).
2. #10 gives a range (2–3), and its entries mix a document (Handover §9) with slices.
3. The units of the "1" values differ: slices (#5), contexts (#14), and unspecified (#17).
4. No promotion bar for Category A is stated. The "2 slices is the floor" bar is stated only for #6 (Category B).
5. #5 mixes a scope reason with an evidence note, so the reason type is not purely one or the other.
6. The coded `t`=AST-013, `k`=engineering-behaviour and `ground`=RULE for the refused items are not stated by the sources. Only #17 rests on a ruling.
7. R-36's behaviour names do not match the matrix titles verbatim, so the item mapping (#3, #9, #10) is an inference.
8. R-36 promotes #7, and an item not in the matrix, beyond the matrix's recommendations. So the matrix is not the full basis for the outcomes.
9. Under a reading that uses only the coded fields, all 12 pairs share a, k, t and cluster. Those fields are coder-assigned, not stated by the sources, so they are excluded.
