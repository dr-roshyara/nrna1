# REVIEW — verification of the "same kind: ruling / role conformance" analysis

Sources used: `SOURCES.md` and `TASK.md` only.
Labels: **[SOURCE FACT]** a verbatim quote or direct reading · **[INFERENCE]** my interpretation · **[FORMAL CONSEQUENCE]** follows mechanically from the frozen witness rule · **[UNKNOWN]** the text does not establish it.

---

## 0. Preliminary issues with the premise

- **[SOURCE FACT]** `SOURCES.md` has the header segments of R-81..R-85 and the full R-91 row. **It contains no R-86 row.** The only mention of R-86 is inside R-91: *"(R-86/R-87 created no standing delegation; R-88 was a single act)"*.
- **[UNKNOWN]** All of R-86's fields (header, X, Y, authority, kind, status) are UNK.
- **[INFERENCE]** Q4 asks about "the R-86/R-91 pair", but the prior analysis compared R-81..R-85 with R-91. I answer Q4 for the pair as written, and separately for R-8x/R-91 (x = 1..5), so the answer holds on either reading.
- **[UNKNOWN]** The segments do not say that R-81, R-83, R-84 or R-85 were "adopted, performed". Only R-82 has adoption wording: *"ACKNOWLEDGED CONSEQUENCE, ADOPTED WITH THE RULING"*. The prior analysis's status coding is therefore **not verified** from these sources, except for R-82, where it is partly supported.
- **[SOURCE FACT]** Rows that call themselves "ruling": R-81 (*"which is why this ruling is reachable before Q1"*), R-82 (*"ADOPTED WITH THE RULING"*), R-85 (*"this ruling does not endorse amending a test"*) and R-91 (*"this ruling collapsed the two"*, *"this ruling is not a reason to accelerate it"*). **[UNKNOWN]** R-83 and R-84 do not call themselves "ruling" in the quoted segments. For those two rows the coarse kind is the prior analysis's coding, not something the text states.

---

## 1. Header extraction — `TITLE — <X> Governance · <Y> · <authority>`

All values are verbatim **[SOURCE FACT]**.

| Row | X | Y | authority |
|---|---|---|---|
| R-81 | `Execution` | `Authorization` | `ARB` |
| R-82 | `Architecture` | `Adoption` | `ARB` |
| R-83 | `Architecture` | `Adoption` | `ARB` |
| R-84 | `Execution` | `Adoption` | `ARB` |
| R-85 | `Delivery` | `Adjudication` | `ARB` |
| R-91 | `Architecture` | `Determination on submitted evidence` | `ARB CHIEF (PREPARED)` |
| R-86 | UNK | UNK | UNK (row not in sources) |

Verbatim header tails:
- R-81: *"— Execution Governance · Authorization · ARB."*
- R-82: *"— Architecture Governance · Adoption · ARB."*
- R-83: *"— Architecture Governance · Adoption · ARB."*
- R-84: *"— Execution Governance · Adoption · ARB."*
- R-85: *"— Delivery Governance · Adjudication · ARB."*
- R-91: *"— Architecture Governance · Determination on submitted evidence · ARB CHIEF (PREPARED)."*

**[SOURCE FACT]** R-91 also differs from all of R-81..R-85 in the **authority** field: `ARB CHIEF (PREPARED)` vs `ARB`. The row's provenance note supports this: *"ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED"*.

---

## 2. Is R-91's Y equal to any R-81..R-85 Y?

**NO.** **[SOURCE FACT]** R-91's Y is `Determination on submitted evidence`. The Y values for R-81..R-85 are {`Authorization`, `Adoption`, `Adjudication`}. None of them matches R-91's Y.

**[INFERENCE]** The prior analysis coded all six rows as one kind, "ruling". That coding is coarser than the headers, and it hides a difference the headers record in two fields: Y and authority.

---

## 3. Scope of the role-separation requirement

**Answer: UNK.** The text scopes the requirement to **Event D**. It does not tie it to R-91's Y type, and it does not state it as a general rule.

Quotes **[SOURCE FACT]**:
- *"The Decision Authority held it on the ground that **Event D SEPARATES evidence submission from constitutional review**, and this ruling collapsed the two"*
- *"engineering submitted evidence and then wrote the governance outcome — 'no architecture-governance action required' — which belongs to the **ARB review step**."*
- *"A PREPARED ruling was still engineering deciding the outcome, in softer wording."*
- *"The engineering artefact must end at *evidence validated*."*
- *"the Phase 4 determination belongs to the ARB."*

Analysis:
- **[SOURCE FACT]** The requirement is stated as a property of **Event D**. "Event D" appears in R-91's **TITLE** (*"EVENT D REVIEW"*), not in its Y field.
- **[UNKNOWN]** The text never says that "Determination on submitted evidence" as a type requires the separation. So TYPE-SPECIFIC is not established.
- **[UNKNOWN]** The text never states the separation as a requirement for all rulings or all governance acts. The sentence *"A PREPARED ruling was still engineering deciding the outcome"* generalises only over PREPARED/softer wording **within this case**. It does not generalise across row types. So GENERAL is not established.
- **[INFERENCE]** The Y label itself names both steps of the collapse: "submitted evidence" (engineering's step) and "Determination" (the ARB's step). This fits the hold ground, but the text never draws that link. The link is my observation, not a stated rule.

---

## 4. Consequence under the frozen witness rule

The rule is STRICT. It requires every other compared field, including kind, to be **known and equal**.

### (a) Coarse grain "ruling": is the R-86/R-91 pair STRICT for conformance?

**NO — not STRICT.**
- **[UNKNOWN]** R-86's kind and every other R-86 field are UNK, because the row is absent from the sources. Under STRICT, "known" fails.
- **[FORMAL CONSEQUENCE]** Not STRICT.
- **[FORMAL CONSEQUENCE]** The same holds for the prior analysis's actual pairs (R-8x/R-91, x = 1..5). Even if "ruling" is granted as equal kind, the **authority** field is known and **unequal** (`ARB` vs `ARB CHIEF (PREPARED)`). So no R-81..R-85 row is a STRICT witness against R-91 at the coarse grain either. For R-83 and R-84, even the "ruling" kind is only the prior analysis's coding, not text (§0).

### (b) Header-type grain Y

- **R-86/R-91: POSSIBLE.** **[FORMAL CONSEQUENCE]** R-86's Y and authority are UNK. Nothing is known to differ, but nothing is known to be equal. That is not STRICT, and it is not refuted.
- **R-8x/R-91 (x = 1..5): NONE.** **[FORMAL CONSEQUENCE]** Y is known and unequal for every pair (§2), and authority is also known and unequal. At this grain these rows are not the same kind, so none can witness R-91's conformance difference.
- The frozen rule defines only STRICT. I read POSSIBLE as "not STRICT because a field is UNK, and no known field differs". I read NONE as "a known compared field differs". **[INFERENCE]**

**Net [INFERENCE]:** The "role conformance" finding depends on treating R-81..R-85 and R-91 as the same kind. At the grain the headers record, they are not the same kind. The authority field differs as well. So the contrast rests on more than role conformance, and the witness rule gives it no STRICT support. The hold ground itself (Event D's separation was collapsed) is **text-attested** as the reason R-91 was held. What is not supported is presenting it as *the* difference within a same-kind comparison.

---

## 5. Strongest alternative reading of Y

**Alternative:** Y names the **act** the row performs, not the kind of object adopted:
- R-81 *authorizes*
- R-82, R-83 and R-84 *adopt*
- R-85 *adjudicates*
- R-91 *determines on submitted evidence*

On this reading, X names the governance **branch** performing the act.

Support **[SOURCE FACT]**:
- R-91 uses "authorization" as the name of an **act** and "Execution/Architecture governance" as **branches**: *"a separate EXECUTION-governance authorization is required for any repair — **a different act, by a different branch of the same authority**"*.
- R-81 (Y = `Authorization`) performs an authorizing act: *"one engineering slice is authorized inside the seam"*.
- R-85 (Y = `Adjudication`) rules between two conflicting keystones: *"K2 and K3 cannot both pass under any correct design"* … *"K2's RECORDED INTENT IS UPHELD; ITS MECHANISM IS NOT."*
- R-91 (Y = `Determination on submitted evidence`) describes its own act as *"The determination is therefore: …"*.
- R-91's correction contrasts domains by function: *"Architecture governance answers 'is the decision still correct?'; execution governance answers 'who is authorized to repair realization?'"*

**Is it ruled out? NO.** **[UNKNOWN]** No sentence in the sources defines the header fields or says that Y classifies the object adopted. **[INFERENCE]** The quotes above fit the act reading at least as well as the object-kind reading.

**Does it change the answers? [INFERENCE]** No. On either reading, R-91's Y differs from every R-81..R-85 Y, so §2 and §4 stand. On the act reading, the "act" of R-91 combines two steps, *submitted evidence* and *determination*, and that combination is the collapse the Decision Authority objected to.

---

## Summary of classifications

| Claim | Class |
|---|---|
| Headers X/Y/authority as tabled | SOURCE FACT |
| R-86 fields | UNKNOWN |
| R-91 Y ≠ any R-81..R-85 Y | SOURCE FACT |
| R-91 authority ≠ R-81..R-85 authority | SOURCE FACT |
| Separation requirement attributed to Event D | SOURCE FACT |
| Separation specific to Y type, or general | UNKNOWN |
| Y label names both collapsed steps | INFERENCE |
| Coarse grain R-86/R-91 not STRICT | FORMAL CONSEQUENCE |
| Coarse grain R-8x/R-91 not STRICT (authority differs) | FORMAL CONSEQUENCE |
| Y grain R-86/R-91 POSSIBLE; R-8x/R-91 NONE | FORMAL CONSEQUENCE |
| "Adopted, performed" status of R-81, R-83, R-84, R-85 | UNKNOWN |
| Y = act reading is not ruled out | UNKNOWN (no definition) / INFERENCE (fits the text) |
