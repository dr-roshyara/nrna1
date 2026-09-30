# What makes a ruling in force: its issuer, or its status?

Sources used: `SOURCES.md` (R-70, R-81, R-86, R-89, R-95) and `TASK.md` only.

**Short answer:** the text rules out **issuer alone** as the cause of force, and the explicit markers are fully consistent with **status**. PREPARED is never in force and ADOPTED always is. But the model built on the status *marker as written* can't be confirmed, because two rulings with no marker (R-70, R-95) have unknown force. So M_status and M_chain come out **UNDETERMINED**, not surviving.

## 1. Ruling-states

| # | Ruling / state | Issuer | Status | Adopter | In force |
|---|---|---|---|---|---|
| 1 | R-70 at issue | "ARB" | none stated | none | **UNK** |
| 2 | R-81 before R-86 | "ARB CHIEF" | "PREPARED" | none | NOT-IN-FORCE (derived) |
| 3 | R-81 after R-86 | ARB Chief | "ADOPTED" | "DECISION AUTHORITY" | IN-FORCE (stated: "THIS RULING IS GOVERNING") |
| 4–5 | R-82 before / after R-86 | ARB Chief (inference, see A2) | PREPARED / ADOPTED | none / Decision Authority | NOT (derived) / IN (stated) |
| 6–7 | R-83 before / after | same | same | same | same |
| 8–9 | R-84 before / after | same | same | same | same |
| 10–11 | R-85 before / after | same | same | same | same |
| 12 | R-86 at issue | "DECISION AUTHORITY" | none stated | none | IN-FORCE (derived) |
| 13 | R-88 as cited by R-89 | UNK | UNK | UNK | IN-FORCE (derived) |
| 14 | R-89 at issue | "ARB CHIEF" | "PREPARED, NOT ADOPTED" | none | NOT-IN-FORCE (derived) |
| 15 | R-95 at issue | "ARB CHIEF" | none stated | none | **UNK** |

The full quotes for every value are in `ANSWER.json`.

### SOURCE FACT
- R-81 annotation: "ISSUED BY THE ARB CHIEF under the Session-1 mandate — NOT independently confirmed by the decision authority" and "THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION, AND “BATCH 7 IS RELEASED” DOES NOT HOLD."
- R-81 later: "✅ ADOPTED 2026-08-04 BY THE DECISION AUTHORITY, WITHOUT AMENDMENT (adoption act: R-86). THIS RULING IS GOVERNING."
- R-86: "Decision Authority confirms and adopts R-81 through R-85 without amendment. These become governing rulings effective immediately."
- R-86: "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it."
- R-89: "ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED … this ruling awaits the Decision Authority."
- R-95's header names "ARB CHIEF" and carries **no** PREPARED/ADOPTED marker.
- R-89: "R-88's adoption was a SINGLE ACT that expressly left the PREPARED→ADOPTED mechanism in force".

### INFERENCE
- **NOT-IN-FORCE for R-81..R-85 before R-86.** Derived from "become governing … effective immediately", which means they were not governing before, and from "“BATCH 7 IS RELEASED” DOES NOT HOLD".
- **NOT-IN-FORCE for R-89.** Derived from its PREPARED marker, combined with R-86's rule and the R-81 precedent that a PREPARED ruling's effect does not hold.
- **IN-FORCE for R-86.** Derived because R-81 records R-86's adoption as having taken effect.
- **IN-FORCE for R-88.** Derived because R-89 relies on it as settled authority.
- **Issuer of R-82..R-85 is the ARB Chief.** R-81's "these five were filed by the Chief" is read as referring to R-81..R-85, the "five" that R-86 adopts.

### UNKNOWN
- **R-70's force:** no row states it.
- **R-95's force:** the text doesn't state it (see A4).
- **R-88's issuer, status and adopter.**

## 2. Frozen test (FORMAL CONSEQUENCE)

| Model | Verdict | Deciding pair(s) |
|---|---|---|
| **M_issuer** | **FALSIFIED** | R-81 before R-86 vs R-81 after R-86: both "ARB Chief", NOT vs IN. Also R-89 (ARB Chief, NOT) vs R-82..85 after R-86 (ARB Chief, IN). |
| **M_status** | **UNDETERMINED** | No falsifying pair: every PREPARED state is NOT and every ADOPTED state is IN. The undetermined pairs are R-86 (none stated, IN) vs R-95 (none stated, UNK), R-86 vs R-70 (none stated, UNK), and R-88 (status UNK). |
| **M_adopter** | **FALSIFIED** | R-81 before R-86 (adopter none, NOT) vs R-86 (adopter none, IN, derived). Also R-89 (none, NOT) vs R-86. |
| **M_chain** | **UNDETERMINED** (fails the survival condition) | The attribution condition is met: all five coded status changes (R-81..R-85, PREPARED→ADOPTED) are acts of the **Decision Authority**, through R-86. The status condition fails because M_status is UNDETERMINED, not SURVIVES. |

**FORMAL CONSEQUENCE:**
- Issuer alone cannot determine force: the same ruling from the same issuer changes force when someone else acts on it.
- The text itself sets out a chain. R-86 fixes the default status by issuer (a ruling from the ARB Chief stays PREPARED). An adoption act by the Decision Authority changes the status. Status gates force.
- Under the frozen rules, that chain is still undetermined, because unmarked rulings exist whose force is unknown.

## 3. Ambiguities

- **A1 – Borderline inclusion.** R-70 and R-95 are included because the rows make them, even though their force is UNK. R-60, R-66, R-72, R-76 and R-79 are cited as operative but their force is only assumed, never stated, so they are excluded.
- **A2 – "These five."** The R-81 annotation never lists the five rulings. The identification with R-81..R-85 comes from R-86.
- **A3 – R-70's issuer conflict.** R-70's header says "ARB". R-81 says R-1..R-80 were "issued by the human authority".
- **A4 – R-95.** Applying R-86's rule by issuer would give PREPARED and NOT-IN-FORCE. The text, though, shows no marker; "RULING AS REFINED AT ISSUE" leaves open an act by an unnamed actor; and the register is only an excerpt.
  - If R-95 is derived as NOT-IN-FORCE, the pair R-86 vs R-95 (both "none stated", opposite force) **FALSIFIES M_status** when status means the marker as written.
  - M_status holds only if "status" means the status the rule assigns, not the marker.
- **A5 – R-86's adopter.** R-86 is coded as adopter "none". If it is coded UNK instead, M_adopter becomes UNDETERMINED rather than FALSIFIED.
- **A6 – R-88.** "R-88's adoption" could mean R-88 *is* an adoption act or that R-88 *was* adopted. Also, the content R-89 attributes to it ("created no standing delegation") matches R-86's text, which suggests a possible mis-citation.
- **A7 – Uncoded earlier state of R-81.** R-81 was first filed with no marker, and its summary claimed "isolation repair ACTIVE". The Chief's own annotation then reclassified it as PREPARED. That change is attributable to the ARB Chief but was not coded as a separate state.
- **A8 – Derived values and circularity.** Most NOT-IN-FORCE values are derived from the text's own PREPARED→not-governing link. For R-89 this makes the M_status test partly circular. R-81..R-85 rest on R-86's "become governing" wording instead.
- **A9 – Same date.** All the post-R-80 events are dated 2026-08-04. The order of states comes from the text ("adoption act: R-86"), not from the dates.
