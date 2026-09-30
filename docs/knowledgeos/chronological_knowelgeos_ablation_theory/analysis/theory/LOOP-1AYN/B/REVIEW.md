# Review of MAPPING.json

**Items:** 180, all values in VALUES.json. Every value was mapped exactly once. No value is missing and no extra value was added.
**Disagreements:** 16. The other 164 mappings are accepted.
**Usable for pairing without manufacturing contrasts:** **Yes, once corrected and only with conditions** (see the last section).

Sources: TASK.md, VALUES.json, RECORDS.json and MAPPING.json only.

## What the worker got right

- **Kept distinct actors apart.** ARB and ARB-CHIEF stay separate (R04 vs R05, R26 vs R27). ENGINEERING, OTHER:agent and OTHER:AI stay separate.
- **Kept distinct operations apart.** PLAN and START-EXECUTION are not merged (R05 is UNMAPPABLE; R12 codes only the main operation). HOLD and ADOPT stay separate (R03). PREPARE-ISSUE is used for a ruling that was never adopted (R05).
- **Mapped by meaning, not by shared words.** "Reuse a capability" is not coded REUSE-ID (R15). "Adopt a reading" is not coded ADOPT (R01).
- **Handled negated supersession correctly.** R26 and R27 are not coded SUPERSEDE.
- **Used UNMAPPABLE honestly.** It was never used to dodge a clear case.
- **Invented no identifier.** Every target code appears in its value. The only exception is the conjunction "R-81 and R-84", where both identifiers are in the value but the joined string is not.

## Corrections

| # | Record · field | Worker | Reviewer | Class | Why |
|---|---|---|---|---|---|
| 1 | R08 act.target | WP-7B-R1 | UNMAPPABLE | coding | The value is about *the motion* (R-60), not the work package it opens. R-60 is not in the value. WP-7B-R1 is R04's authorization target. |
| 2 | R08 norm.about_target | WP-7B-R1 | UNMAPPABLE | coding | Same referent as #1. The worker's code would pair it with R04's norm on implementing WP-7B-R1. |
| 3 | R09 norm.about_target | WP-7B-R1 | UNMAPPABLE | coding | In the same record, the motion is coded R-60 (act) and WP-7B-R1 (norm). |
| 4 | R22 act.target | O-CLOSURE-VOCAB | UNMAPPABLE | coding | The PO's *framing* of O-CLOSURE-VOCAB is not O-CLOSURE-VOCAB. This follows the worker's own R01 rule ("the ambiguity over … R-81..R-85" → UNMAPPABLE). |
| 5 | R22 act.operation | RAISE | UNMAPPABLE | formal reasoning | The promotion "(did not occur)". Coding RAISE records an act that never happened. The norm field keeps RAISE. |
| 6 | R29 act.operation | SUPERSEDE | UNMAPPABLE | formal reasoning | The span gives the reason supersession was *rejected*. The worker refused SUPERSEDE in R26 because it "would reverse the meaning", and that applies here too. As coded, R29 and R30 say the Chief both superseded and annotated PB-006. |
| 7 | R31 act.operation | AUTHORIZE-IMPL | UNMAPPABLE | formal reasoning | "(not occurred)" / "has NOT been issued". As coded, R31 looks the same as R32's real authorization of Slice 7B. |
| 8 | R30 act.operation | ADOPT | ANNOTATE | source interpretation | "ANNOTATE is adopted" means the Chief chose a disposition. It is not the adoption of a ruling. ADOPT creates an "ARB-CHIEF ADOPT" act that conflicts with R01 and R02 (only the Decision Authority adopts rulings), and that conflict is manufactured. |
| 9 | R30 norm.about_target | IMPLEMENTATION_PROGRESS.md:58 | PB-006 | coding | This is the same row that R29 and R30 act.target code as PB-006. A file:line location is not a canonical identifier. |
| 10–16 | R15, R16, R17, R18, R19, R20, R21 norm.stated_by | ARB | UNMAPPABLE | independence | The Promotion Review matrix stated these norms; ARB only *adopted* it via R-36. In R27 the worker coded the issuer rather than the adopter, and in R22 a document as stater became UNMAPPABLE. The matrix's author is unnamed. Crediting ARB adds seven norms to ARB's own voice. |

Classes used: formal reasoning ×3, coding ×5, source interpretation ×1, independence ×7. None of the disagreements fall under wording, evidence scope or substantive.

## Over-merges

1. **The motion and the work package.** The motion opening WP-7B-R1 was coded WP-7B-R1 (R08, R09). That pairs R-60's non-ruling classification with ARB's authorization to implement WP-7B-R1 (R04).
2. **A framing and its object.** R22's framing was coded as O-CLOSURE-VOCAB.
3. **Acts that happened and acts that did not.** R22 (RAISE), R29 (SUPERSEDE) and R31 (AUTHORIZE-IMPL) share codes with real acts. For example, R31 and R32 both read as "AUTHORIZE-IMPL Slice 7B".
4. **Choosing a disposition and adopting a ruling.** R30 was coded ADOPT, the same as R02 (Decision Authority) and R23 (PA). This is the "the Chief vs the adopting authority" hazard.
5. **The matrix's author and ARB.** R15–R21 norm.stated_by were credited to ARB.

## Under-merges

1. **The PB-006 reporting row** is coded two ways: PB-006 and IMPLEMENTATION_PROGRESS.md:58.
2. **The R-60 motion** is coded WP-7B-R1 in R08 but R-60 in R09. After correction, R08 is UNMAPPABLE because R-60 is not in its value. So the real R08 vs R09 contrast (the issuer's heading vs the recorder's filing) has to be paired through the shared section (## R-60), not the target code.
3. **Composite targets (not corrected).** "R-81 through R-85" (R02), "Slices 7B and 7C" (R14) and "R-81 and R-84" (R36) cannot match their member identifiers. This loses matches but does not create false ones.

## Can the corrected mapping be used for pairing?

**Yes, with conditions.** After the 16 corrections, none of the codes create a false match or contrast. Three conditions apply:

- **Carry norm modality separately.** norm.about_operation deliberately leaves out whether an operation is permitted, forbidden or not authorized (R12, R14, R20–R22, R26, R27, R31, R33). Pairing on the operation alone would turn real time sequences into apparent contradictions. Examples: R14 or R31 "not authorized" vs R32 or R34 "authorized"; R10 "implementation does not begin" vs R11's WP-4B RED commit.
- **Expand composite targets** into their member identifiers before matching.
- **Order records in time** before reading a pair as a contradiction.

## Other caveats

- **R05** norm.about_operation is correctly UNMAPPABLE, but R05 therefore drops out of plan/execute pairings.
- **R06:** the justification says R-90 "was never entered in the register". No span supports this, and R25 suggests R-90 was numbered. The code (PREPARE-ISSUE) is unaffected.
- **R21:** the target drops "carrier". This is accepted.
- **R15–R21:** candidate names are used as targets. Row numbers appear only in the spans, so the names are correct under the literal-from-value rule.
- **§12** (R27, R36) is only unique in context. Confirm it is the same section before pairing.
- **R-90:** the number (R25) and the ruling (R06, R07) share a string. Pairing them links one lineage and does not create a contrast.
- **R24, R28 and R35** have no spans and no values. Nothing was mapped for them, which is correct.
