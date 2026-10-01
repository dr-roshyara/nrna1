# REVIEW of SCHEMA-CORE (frozen)

**Verdict: PASS_WITH_LIMITATIONS.** Nothing blocks it: there is no theory decision, no neutrality leak in any definition, and no example is built from a record. There are several codability gaps and one anchoring overlap that came from the spec. These are listed below so the analysis can account for them.

## 1. Fidelity

The three field definitions and the four fixed rules match CORE-SPEC almost word for word. Additions: the unit rule (§1), the decision tests, the boundary guidance (§4), the examples (§5), the `status` layer and NOTES.md (§6).

- **Self-performance as an act: not decided.** SP only records whether the span's words perform something. Example 3 codes a performative AR = NO. That is a convention about *reporting* ("reports that an act happened"), not a claim that performance is or is not an act. Both facts stay recoverable from the data, so the theory question is left to the analyst. **Limitation:** this convention appears only in an example. The text never states it, and the spec's "present event" wording could be read the other way for present-tense performatives ("is adopted", "confirms and adopts", "IS RETIRED"). It should be written as an explicit rule.
- **Heading rule departs from the spec's UNKNOWN rule.** §4 makes a bare label NO on all three fields and forbids using the section. That clashes with (a) §1, which allows section reading when the span does not decide, (b) spec rule 2, UNKNOWN when undecided, and (c) Example 8, which codes a label UNKNOWN/NO/UNKNOWN. The schema contradicts itself here (AUTHOR-NOTES 5).
- **NS decision test drifts from the spec.** The spec says something "has a normative status". The schema adds "does it *give* something a status", and Example 3 says an allocation creates an entitlement. This turns a state into the outcome of an act (AUTHOR-NOTES 3). See §3.
- **`status` values (SOURCE_FACT / INFERENCE / UNKNOWN)** are not in the spec, and the author defined them. They do not change the field values, so this is non-blocking. The SOURCE_FACT/INFERENCE line for context taken from the section is still undefined.
- **"Absence vs refusal" leaves out deliberate non-action** ("deliberately did NOT reuse", "NOT promoted", "is not superseded"). The lower bound of "attempted" is also open (AUTHOR-NOTES 7).
- **Missing guidance:** recommendations ("PROMOTE", "should"); rulings marked PREPARED / not adopted; quoted performatives (a verbatim ruling quoted in the register).

## 2. Anchoring

The examples come from a different domain (allotment gardens). No example copies a record's wording together with its structure and answer.

- **Fixed rule 1 ("X is authorized to begin")** matches R32 ("Engineering is authorized to begin …") and pre-codes it on AR and NS. CORE-SPEC requires this wording, so the schema cannot drop it while staying faithful. Those two R32 fields would be determinate anyway. It is still flagged so the spec owner knows.
- **Example 6 ("declined … request")** shares the refusal verb with R01 ("declines to resolve"). R01 differs: it uses the present tense, refers to itself and has a normative second clause, so following the example blindly would mis-steer a coder. Low risk. Use a different refusal verb.
- **The SP cue phrases "is decided", "is allocated"** match the pattern "X is …-ed" in R25, R30 and R32. This is a structural cue, not wording taken from a record. Low risk.
- **Example 8 ("Topic: the new arrangement")** has a label-colon shape like R11. But its premise (a silent section) does not hold for R11. Low risk.

## 3. Neutrality

- AR's test is generic ("did, tried, refused, withdrew, reversed"). Governance acts and work acts both qualify (Examples 1 and 5). It does not depend on governance vocabulary.
- NS never depends on an act being reported. Rule 1 and Example 9 keep them apart.
- **Risk (non-blocking):** the "give something a status" test plus Example 3 push adoptions and classifications toward NS = YES. That raises act–norm co-occurrence, which fits H-D's "norms change through acts". The qualifier "carries such consequences" limits it, and it stays close to the spec's "has a normative status". Fix: "*has*, or is stated to have, a status …".
- **Risk (non-blocking):** the heading rule applied to R11 (a git commit subject, the only work-event record) would code a work act NO without reading the section. That undercounts work evidence, which matters to H-B. The rule itself is neutral but its effect is not.
- PREPARED vs ADOPTED force: the schema says nothing about it. That is correct for neutrality (it is an H-D claim), but it costs codability (R05).

## 4. Dry run (R01–R36, SCHEMA-CORE only; codes withheld)

| Field | Determinate | Debatable |
|---|---|---|
| ACT_REPORTED | 23 | 13 |
| ACT_SELF_PERFORMED | 18 | 18 |
| NORM_STATED | 20 | 16 |

**ACT_REPORTED:**
- R01: present-tense refusal; is it a report or a performance?
- R02: the span is a performative, but the section presents it as a verbatim quotation.
- R09: "is filed": a present event or a performative classification?
- R11: the heading rule, the section-reading rule and Example 8 conflict.
- R16: only deliberate non-transfer and states; refusal or absence?
- R19: "recorded there as": is a location note an act?
- R20: "deliberately did NOT reuse": refusal or absence?
- R22: "NOT promoted": a deliberate decision or an absence?
- R23: the past participle in a headline could be a report or a performative.
- R25: "IS RETIRED": a present event or a performative?
- R26: "is not superseded": a state, or the Board's refusal?
- R27: "it APPLIES": the ruling describing its own act.
- R30: "is adopted": a present report or a performative?

**ACT_SELF_PERFORMED:**
- R01 and R03: annotation voice; does the recorder perform the act or describe it?
- R02 and R26: quoted performatives (the quotation rule conflicts with the span read alone).
- R04, R05, R23: ruling headlines; is the headline the operative act? (R05 is also PREPARED.)
- R09, R25, R27, R30: present/passive "is …-ed" forms.
- R10, R12, R13, R14, R32, R34, R36: norm sentences inside rulings; does stating the norm perform the authorization? The schema gives no rule.

**NORM_STATED:**
- R02, R30: does adoption give a normative status?
- R05: the authorization is PREPARED, not adopted.
- R06, R07: "the register holds constitutional decisions": a rule or a description?
- R09: "filed as a ruling" confers a status.
- R10: "DOES NOT BEGIN": a directive or a description?
- R11: label: the heading rule and Example 8 disagree.
- R15–R19: recommended principles; no guidance on "PROMOTE".
- R20: is "Candidate" a normative status?
- R26, R27: an existing norm's continued status is implied.

R06 and R07 are identical records, so they will always be coded the same. Check whether a different clause was meant.

## 5. Length

About 880 words, counted by hand (±5%). That is within the ≤ 1,000 limit.

## Required follow-ups (non-blocking)

1. State the AR convention for performatives as a rule.
2. Make the heading rule, §1 and Example 8 agree.
3. Change "give a status" in the NS test to "has a status".
4. Add guidance for deliberate non-action, recommendations, PREPARED rulings and quoted performatives.
5. Replace "declined" in Example 6.
6. Flag the R32 overlap in rule 1 to the CORE-SPEC owner.
