# Independent review of Researcher A's answer

**Files used:** `TASK.md`, `SOURCES.md`, `ANSWER.json`, `ANSWER.md`. Nothing else.

**Bottom line:** A's verdicts survive once the coding is corrected, but some of the reasons change.
- **R-89:** its in_force value is circular and must be recoded UNK. That removes one deciding pair each from M_issuer and M_adopter. Both models are still decided by other pairs.
- **R-88:** its IN-FORCE value is unsupported and is recoded UNK.
- **M_adopter:** its falsification is fragile. It depends on coding R-86's adopter as "none".
- **M_status:** it is still UNDETERMINED. Its strongest threat is an uncoded "as filed" state of R-81.

| Model | A | Reviewer |
|---|---|---|
| M_issuer | FALSIFIED | **FALSIFIED** (R-81 before vs after R-86; R-82..R-85 before vs after) |
| M_status | UNDETERMINED | **UNDETERMINED** |
| M_adopter | FALSIFIED | **FALSIFIED, fragile** (R-81..R-85 before R-86 vs R-86 only) |
| M_chain | "UNDETERMINED" | **DOES NOT SURVIVE**, because M_status is not SURVIVES. The attribution condition is met. Same substance as A, different label. |

## 1. Evidence and verbatim quotes

**Passes.** I checked every quote in `ANSWER.json` and `ANSWER.md` against `SOURCES.md`.
- All quotes are verbatim, apart from dropped markdown bold, one marked ellipsis, and one bracketed edit ("become[s] governing").
- No outside evidence is used.
- "The register excerpt is partial" follows from TASK's "five register rows".

## 2. Status coding

| State | A | Reviewer |
|---|---|---|
| R-70 | none stated | Agree. But the quote A gives, "WP-7B-R1 **authorized to implement**", shows a slice being authorized, not a status marker (D7). |
| R-81..R-85 before R-86 | PREPARED | Agree. There is extra support A did not cite: R-86 "the rulings explicitly marked PREPARED rather than implicitly adopted" (D8). |
| R-81..R-85 after R-86 | ADOPTED | Agree. |
| R-86 | none stated | Agree. |
| R-88 | UNK | Agree. "R-88's adoption" is ambiguous. Reading it as ADOPTED would be an inferred marker, not a stated one. |
| R-89 | PREPARED | Agree. The header says "ARB CHIEF (PREPARED)". |
| R-95 | none stated | Agree. R-86's rule would give it an **inferred** marker (PREPARED). That marker comes from the issuer, so it cannot be used as R-95's status. The quote A gives, "EP-01 plans may be prepared", is about plans, not PREPARED status (D7). |
| R-81 as filed | not coded | **none stated** (D5). The decision text carries no marker, and the summary claims "isolation repair ACTIVE". |

## 3. in_force: stated, derived, or circular

| State | A | Reviewer |
|---|---|---|
| R-81 after R-86 | IN, stated | Agree: "THIS RULING IS GOVERNING." |
| R-82..R-85 after R-86 | IN, stated | Agree: "These become governing rulings effective immediately." |
| R-81..R-85 before R-86 | NOT, derived | **Derived, not circular.** It comes from force language, not from the marker: "become governing … effective immediately" means they were not governing before, and R-81 says "“BATCH 7 IS RELEASED” DOES NOT HOLD". The text itself reasons "THEREFORE" from PREPARED status, but that is the text's statement, not A's derivation. |
| R-86 | IN, derived | **Derived, not circular.** R-86 has no marker, and the value is not taken from its issuer. R-81 records that R-86's act operated: "(adoption act: R-86). THIS RULING IS GOVERNING." TASK counts "operative" as force. |
| R-88 | IN, derived | **Unsupported, recoded UNK** (D3). In "left the PREPARED→ADOPTED mechanism in force", "in force" describes the mechanism, not R-88. The ruling that cites R-88 (R-89) is itself PREPARED. A excludes R-60/66/72/76/79 because their force is "presupposed, not stated", and R-88 is in the same position. Reading it as "R-88 was adopted, so it is in force" would make the value status-derived. |
| **R-89** | NOT, derived | **CIRCULAR, recoded UNK** (D1). The chain is: PREPARED marker plus R-86's issuer rule gives NOT-IN-FORCE. That is issuer → status → force, and A then uses the result to test M_status, M_issuer and M_adopter. A's notes admit it is "partly circular" but keep it in the deciding pairs (D2). No row states R-89's force. "WP-4C-1 … is AUTHORIZED" is the authorization of a slice, not the ruling's force. |
| R-95 | UNK | Agree. The only possible derivation, through R-86's rule, is circular. A's added reason about an "unnamed actor" in "RULING AS REFINED AT ISSUE" is speculative (D9). |
| R-70 | UNK | Agree. Deriving force from "issued by the human authority" would be issuer-based, and so circular for M_issuer. |
| R-81 as filed | – | **UNK.** The self-claim "isolation repair ACTIVE" conflicts with the later, present-tense "DOES NOT HOLD". |

## 4. Category confusion

- **Issuer, adopter, and the ruling that records an adoption:** handled correctly.
  - R-81..R-85 were issued by the ARB Chief and adopted by the Decision Authority.
  - R-86 is the adoption act. Its issuer is the Decision Authority and its adopter is none.
  - M_chain's actor is correctly given as "Decision Authority (act: R-86)".
- **Exception: R-70's issuer (D4).** A codes the header label "ARB" as the issuer. R-81 states "Every prior ruling R-1..R-80 was issued by the human authority" and "THE DECISION AUTHORITY IS THE HUMAN AUTHORITY, NOT THE CHIEF".
  - R-81's own header also says "ARB", yet R-81 was issued by the Chief. So "ARB" in a header names a governance track, not an issuer.
  - R-70's issuer is therefore the human authority, i.e. the Decision Authority.
- **PREPARED vs HELD vs WITHDRAWN:** HELD and WITHDRAWN do not appear. PREPARED is never confused with plans being "prepared".
- **Authorization of a slice vs force of the ruling:** A kept these apart in the coding. It blurred them only in the quotes chosen for "none stated" (D7).

## 5. Missed ruling-states

- **R-95's status:** coded correctly as none stated.
- **R-81..R-85 before and after R-86:** both states are coded.
- **Missed: R-81 as filed** (D5). The provenance annotation moved R-81 from an unmarked filing to PREPARED. A describes this change in a note but leaves it out of `M_chain.status_changes`, even though M_chain requires *every* status change to be attributed to an actor. The change is attributable to the ARB Chief, so the attribution condition still holds.

## 6. Verdicts recomputed (circular values set to UNK)

**Values used for testing:**
- R-81..R-85 before R-86: NOT (non-circular).
- R-81..R-85 after R-86: IN (stated).
- R-86: IN (non-circular).
- UNK: R-70, R-88, R-89, R-95, R-81 as filed.

**Verdicts:**
- **M_issuer: FALSIFIED.** R-81 is issued by the ARB Chief both before and after R-86, and its force flips from NOT to IN. R-82..R-85 show the same flip. This is robust.
- **M_status: UNDETERMINED.**
  - Every PREPARED state is NOT and every ADOPTED state is IN, so there is no falsifying pair.
  - Undetermined pairs:
    - R-86 (none stated, IN) vs each of R-70, R-95 and R-81 as filed (none stated, UNK);
    - R-89 (PREPARED, UNK);
    - R-88 (status UNK).
- **M_adopter: FALSIFIED.**
  - Deciding pair: R-81..R-85 before R-86 (adopter none, "AWAITING ADOPTION", NOT) vs R-86 (adopter none, IN).
  - A's second pair, R-89 vs R-86, is dropped because R-89 is now UNK.
- **M_chain: DOES NOT SURVIVE**, because M_status is UNDETERMINED.
  - Attribution is met. R-81..R-85 went from PREPARED to ADOPTED by the Decision Authority, through R-86.
  - R-81 went from none stated to PREPARED by the ARB Chief, through its provenance annotation.

## 7. Strongest alternative readings

| # | Model | Reading | Does the text rule it out? |
|---|---|---|---|
| A-1 | M_status → FALSIFIED | R-81 as filed (none stated) was NOT-IN-FORCE from the start, because the Chief never had adoption authority. Paired with R-86 (none stated, IN), this would falsify M_status. | **Partly.** R-81 says it "passed through a PREPARED state", a single pre-adoption state, and R-86 lists "provenance corrected". Both treat the unmarked filing as a provenance error, not a separate state. "DOES NOT HOLD" is present tense and does not state the as-filed force. So the reading is not excluded beyond doubt. |
| A-2 | M_adopter → UNDETERMINED | When the Decision Authority issues a ruling, that issuance is itself the adoption. R-81 says the mandate delegated "CHAIRING AND PREPARATION, not adoption". On this reading R-86's adopter is the Decision Authority (or UNK), and every known pair is consistent. | **No.** The register records adoptions in the adopted ruling's own row (as with R-81), and R-86's row records none, which supports "none". But nothing excludes the merged reading. This is the most fragile verdict. |
| A-3 | M_adopter → UNDETERMINED | An adoption act has no "force" of its own, so R-86's in_force is UNK. | **Largely.** TASK counts "operative" as force, and R-81 records that R-86's act took effect. |
| A-4 | M_status → FALSIFIED | Give R-95 the rule-assigned status PREPARED and derive NOT-IN-FORCE. | **Yes, procedurally.** Both values would come from the issuer, which is circular, so the reading cannot be used for testing. |

## Disagreements (one class each, not averaged)

| ID | Class | Issue | Verdict impact |
|---|---|---|---|
| D1 | coding | R-89's in_force is circular; recoded UNK | none (one deciding pair each dropped) |
| D2 | formal reasoning | A flags the circular value but still uses it in deciding pairs and in "PREPARED is never in force" | none |
| D3 | source interpretation | R-88 IN-FORCE: "in force" describes the mechanism; the citing ruling is PREPARED; recoded UNK | none |
| D4 | source interpretation | R-70's issuer is the human authority (Decision Authority), not "ARB" | none (adds an undetermined pair) |
| D5 | coding | R-81 as-filed state not coded; its none→PREPARED change missing from status_changes | none under UNK; see A-1 |
| D6 | formal reasoning | M_chain defined by "survives iff" is binary: DOES NOT SURVIVE, not "UNDETERMINED" | label only |
| D7 | wording | Quotes given for "none stated" show slice authorizations (and "plans may be prepared"), not the absence of a marker | none |
| D8 | evidence scope | Unused supporting quote for R-82..R-85 PREPARED: "the rulings explicitly marked PREPARED rather than implicitly adopted" | none (strengthens A) |
| D9 | source interpretation | R-95 UNK partly rests on a speculative "unnamed actor" reading; the circularity alone justifies UNK | none |

No independence or substantive disagreements were found.
