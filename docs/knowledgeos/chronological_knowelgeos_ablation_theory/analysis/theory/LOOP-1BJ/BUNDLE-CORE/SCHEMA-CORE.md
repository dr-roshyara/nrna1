# SCHEMA-CORE: coding schema

## 1. The unit

One record = one verbatim span together with its section heading. Code the span's words. If the span alone does not decide a field, you may read the surrounding section in the sources file. Do not use anything outside that section.

## 2. The three fields

Each field is YES, NO or UNKNOWN. Code each field independently.

**ACT_REPORTED**: the text reports that an act actually happened, or was attempted, refused, withdrawn or reversed, as a past or present event.
*Decision test:* Does the span say that someone did, tried, refused, withdrew or reversed something? If so, is that presented as an event that has happened or is happening, and not as a plan, a possibility or a rule?

**ACT_SELF_PERFORMED**: the span's own words perform an act. Simply by being written or issued, the text itself adopts, authorizes, withdraws, classifies, decides, etc.
*Decision test:* Would the act be done by the issuing of these very words ("hereby", "is decided", "is allocated")? Or do the words only describe an act done somewhere else?

**NORM_STATED**: the span states normative content, i.e. something is permitted, required, prohibited, authorized or eligible, or has a normative status.
*Decision test:* Does the span say what someone may, must or must not do, or what they are entitled or eligible to do? Or does it say that something has, or is stated to have, a normative status that carries such consequences?

## 3. Fixed rules

1. **Never infer an act from a norm.** "X is authorized to begin" is NORM_STATED = YES. It is not evidence that X began.
2. **UNKNOWN** when the span (and, where needed, its section) does not decide.
3. A record whose span reads **"NO SOURCE SPAN AVAILABLE"** is UNKNOWN on all three fields.
4. **One record may be YES on several fields.** The fields are coded independently.
5. **Performative convention.** A span that itself performs an act is ACT_SELF_PERFORMED = YES. It is ACT_REPORTED = YES **only** if the text **also** reports a separate act as having happened.

## 4. Boundary guidance

- **Plan or intention** ("plans to", "will", "intends"): not an act. ACT_REPORTED = NO, unless the span also reports that a decision to do it was actually taken.
- **Quotation or report of someone else's act:** ACT_REPORTED = YES. ACT_SELF_PERFORMED = NO, because the reporting words do not perform the act they quote.
- **Quoted performative:** a span of only the quoted words is coded as written, with "quoted" in `notes`; with its reporting frame, apply the quotation rule.
- **Heading or label alone:** decide it using the surrounding section where the sources provide it. If it is still undecided, UNKNOWN. Never NO by default.
- **Negated or refused act:** a refusal, rejection or withdrawal is an act, so ACT_REPORTED = YES. A mere absence ("nobody has done X") is not, so it is NO.
- **Deliberate non-action:** a refusal only if the text presents the not-doing as chosen; otherwise mere absence.
- **Attempted:** needs at least one reported step toward the act; intention alone is not an attempt.
- **Recommendation** ("should", "recommends"): not an adoption. NORM_STATED = YES only if the span states a permission, requirement or prohibition.
- **Prepared or not-yet-adopted decision:** record what the text states; do not decide its force.
- **Conditional ("if … then may"):** NORM_STATED = YES. ACT_REPORTED = NO. Never assume the condition was met.

## 5. Worked examples (village allotment-garden association)

AR = ACT_REPORTED, SP = ACT_SELF_PERFORMED, NS = NORM_STATED.

| # | Section / span | AR | SP | NS | Reason |
|---|---|---|---|---|---|
| 1 | *Minutes, 3 May*: "The committee voted to replace the water tap at the north gate." | YES | NO | NO | Reports a vote that took place; describes it, does not perform it. |
| 2 | *Rule book §4*: "Members may keep up to six hens on a full plot." | NO | NO | YES | States a permission; no act is reported. |
| 3 | *Minutes, 3 May*: "The committee hereby allocates plot 14 to Mrs Okafor from 1 June." | NO | YES | YES | The words themselves make the allocation, and the allocation gives an entitlement. |
| 4 | *Minutes, 7 June*: "The treasurer plans to order new hose reels next spring." | NO | NO | NO | An intention, not an act. |
| 5 | *Minutes, 7 June*: "Mr Brandt said the warden had locked the tool shed after the break-in." | YES | NO | NO | A report of someone else's act. |
| 6 | *Minutes, 7 June*: "The committee refused Mr Lutz's request to build a greenhouse on plot 7." | YES | NO | NO | A refusal is a reported act. The span does not itself state what plot holders may build. |
| 7 | *Rule book §9*: "If a plot is left uncultivated for a full season, the committee may reassign it." | NO | NO | YES | Conditional permission; nothing says any plot was reassigned. |
| 8 | *Plot work notes*: "Compost bins: the new arrangement." (the section says nothing more) | UNKNOWN | NO | UNKNOWN | Neither the span nor the section says whether an arrangement was made or what it requires. SP is NO because the label's own words perform nothing, not by default. |
| 9 | *Minutes, 5 July*: "The general meeting approved the revised rule that bonfires are prohibited from April to September." | YES | NO | YES | Reports an approval (act) and states a prohibition (norm). Both are YES, and neither is inferred from the other. |

## 6. Output format

**CODING.json**

```json
{
  "records": [
    {
      "record_id": "R001",
      "ACT_REPORTED": "YES",
      "ACT_SELF_PERFORMED": "NO",
      "NORM_STATED": "NO",
      "status": {
        "ACT_REPORTED": "SOURCE_FACT",
        "ACT_SELF_PERFORMED": "SOURCE_FACT",
        "NORM_STATED": "SOURCE_FACT"
      },
      "notes": ""
    }
  ]
}
```

- Field values are `"YES"`, `"NO"` or `"UNKNOWN"`.
- `status` gives one value per field:
  - `SOURCE_FACT`: the span's words (or its section's words) decide the answer directly.
  - `INFERENCE`: the answer depends on the coder's reading, e.g. tense or a performative force that is not stated outright.
  - `UNKNOWN`: the field is coded UNKNOWN.
  - Section context: `SOURCE_FACT` if its words decide the field outright, `INFERENCE` if they need interpretation.
- `notes`: a short free-text reason. Leave it empty when the case is plain.

**NOTES.md**: one line per hard record, meaning any record with an UNKNOWN or INFERENCE status, or one that needed boundary guidance. Format:
`R017 — ACT_REPORTED — "will be cut back": plan, not act; coded NO (INFERENCE).`
