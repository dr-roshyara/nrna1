# Independent review of Researcher A (ANSWER.json / ANSWER.md)

Inputs used: TASK.md, EVENTS.json, SOURCES.md, ANSWER.json, ANSWER.md. Nothing else.
Limitation: I read A's output before checking the sources, so this review is not blind to A's framing.

## 1. Verbatim

I checked all 33 non-null spans against SOURCES.md by hand.

- **Under A's declared convention** (strip `**` and `*`, keep every other character): **0 character failures.** Every span is present exactly as given.
- **Under a strict character-for-character reading**, 15 spans are not literally present, because emphasis markers sit *inside* them: events 3, 6, 7, 12, 14, 15–21, 22, 27, 36. Examples: `MAY BE *PLANNED* AND MUST NOT BE *STARTED*` (12); `**engineering is AUTHORIZED to execute** R-81's…` (36). Each one becomes exact once the markers are stripped. A disclosed the convention, so these are **wording**, not fabrication. Leading emoji (⚠️ ⛔ ✅) fall outside every span.
- **Heading placement.** Some spans do not sit directly under the heading A names:
  - **Events 15–21** sit under the in-excerpt heading `## Deliverable 1 — Promotion Matrix`.
  - **Event 22** sits under `## Problems` › `### Ninth — …`.
  - **Events 6/7** sit under `## WP-4C-2 — commission closed…`. A disclosed this one as "(within ## S0804-L576)" but did not do the same for 15–22. The treatment is inconsistent, but no span is misattributed to the wrong excerpt.
- **Line arithmetic A missed.** Each excerpt's line count matches its stated range: S0804 = 13 lines for 570–582; S0815 = 46 lines for 455–500. That lets us resolve line references:
  - S0804-L576 is the `## WP-4C-2` heading.
  - S0815-L483 is the `### Ninth` heading.
  - L493 is SOURCES.md line 149, which is **exactly the line holding event 22's span** ("NOT promoted to methodology — single occurrence…").
  - So A's "L493-B can't be checked (UNKNOWN)" is wrong. The anchor checks out: the line number is confirmed, and the "-B" suffix is still unexplained.

## 2. Status

I agree with A's status on 34 of 36 events, and I dispute none of the counts. My qualifications:

| Event(s) | A | Reviewer | Class |
|---|---|---|---|
| 1 | ANCHORED | ANCHORED **only on inference**. The span is a refusal to *resolve an authority ambiguity in the Chief's favour*, not a refusal to *adopt*. A span closer to the adoption sense exists in the same annotation: "THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION". A's own description_adds concede that the framing is a reading, which sits uneasily with plain ANCHORED. On a strict reading → UNANCHORED as an ADOPT act. | source interpretation |
| 6 / 7 | SHARED, DUAL-SPAN | DUAL-SPAN is correct *if* SHARED. But TASK rule 1 asks for the **smallest** span, and the sentence splits cleanly at the dash: the act is "R-90 itself withdrawn before adoption, because the Authority ruled it was operational acceptance rather than a constitutional decision", and the norm is "the register holds constitutional decisions". That gives two disjoint spans, which would make both ANCHORED. A raised this (A3) and chose otherwise. | coding |
| 15–21 | ANCHORED to matrix rows | ANCHORED stands, but A left the events' stated anchor (R-36). R-36 has item-specific spans that carry the *outcome* (e.g. "reuse-before-create" in the promoted list; "Registration ≠ Delivery stays Messaging-scoped in ADR-MP-06"). For #14 and #17, however, R-36 has only one shared clause ("Strangler reconstitution, `ChallengeResolvedIntegration` carrier, PGP-03 hoist, Engineering Standards (R-28/CMP-009) remain Candidates"). Anchored to R-36, events 20 and 21 would be SHARED / DUAL-SPAN. A's choice is defensible under "most specific". | coding / evidence scope |
| 24 | UNANCHORED | I agree, but an alternative is live. "Issued as R-90; the collision is recorded rather than silently renumbered." (## R-90) is an attested act of assigning a number that had not been used, with R-89 "ALREADY TAKEN". "Never-used" is inferred, not stated. | source interpretation |
| 29 | ANCHORED ("superseding would blur chronology") | This span is a *ground*, not an act. The act-bearing sentence is "Of the three engineering-presented outcomes (retain · annotate · supersede), ANNOTATE is adopted.", which also carries event 30, so it would be DUAL-SPAN. A flagged the gap in description_adds. | coding |
| 31 | ANCHORED to the recording note | The ruling proper (outcome column) has a better span: "execution still NOT authorized". The recording note is expressly "not part of this ruling". | coding |

- **MERGE vs DUAL-SPAN:** I agree there are no MERGEs. Keeping 8 and 9 apart is right. They are one motion, but the source itself contrasts the two vehicles: note versus ruling.

## 3. Description additions

A's flags are mostly complete. It missed three things:

- **26, 27:** "SUPERSEDE ADR-T14" and "SUPERSEDE §12" do not state the refused polarity. The spans are "ADR-T14 is not superseded." and "it APPLIES §12 rather than superseding it". A flagged exactly this gap for events 14 and 25 but left 26 and 27 blank. (**coding**)
- **17, 18:** the target AST-013 is absent from rows 9 and 10. A flagged the target only for the not-promoted rows. For 15 and 16, the rows say "OPERATING_INSTRUCTIONS", which equals AST-013 only via R-36. (**coding**)
- **1:** correctly flagged.

## 4. Authority pairs

### (a) Event 1 "ADOPT R-81..85 by the Chief (declined)" vs event 2 "ADOPT R-81..85 by the DA (R-86)": **FAILS**

Spans (## R-81, repeated word for word in ## R-83; ## R-86):
- 1: "The Chief declines to resolve an ambiguity about the Chief's own authority in the Chief's own favour — that is the one reading no self-issued act may adopt."
- 1, context: "DETERMINATION ON THE EVIDENCE: THE DECISION AUTHORITY IS THE HUMAN AUTHORITY, NOT THE CHIEF." / "the Session-1 mandate delegated CHAIRING AND PREPARATION, not adoption." / "THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION"
- 2: "Decision Authority confirms and adopts R-81 through R-85 without amendment."
- R-86 rule: "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it"

Checking each criterion:
- **Distinct spans:** yes.
- **Differ only in actor:** no. The verbs differ ("declines to resolve an ambiguity" vs "confirms and adopts"), the objects differ (an authority reading vs five named rulings), the polarity differs, and only span 2 names R-81..R-85 ("these five" vs "R-81 through R-85").
- **Attested acts:** member 2 is an attested, performed adoption. Member 1 is an attested *refusal*, but a refusal of a self-favouring interpretation, not of adoption.
- **Is the "declined" adoption stated as a refused act?** No. Non-adoption by the Chief appears only as a **rule about who may adopt**: the mandate "delegated CHAIRING AND PREPARATION, not adoption", later generalised by R-86. On top of that rule sits a **determination** of who the authority is. No sentence says the Chief declined to adopt R-81..85.

A's "no" is right. But A also records `both_anchored: true`, which overstates member 1.

### (b) Event 4 "AUTHORIZE-IMPL R-70 (ARB)" vs event 5 "AUTHORIZE-IMPL R-89 (Chief, PREPARED)": **WEAK**

Spans:
- 4 (## R-70): "WP-7B-R1 AUTHORIZED TO IMPLEMENT — Execution Governance · Authorization · ARB."
- 5 (## R-89): "WP-4C-1 AUTHORIZED TO PLAN AND IMPLEMENT — Execution Governance · Authorization · ARB CHIEF (PREPARED)." and "PROVENANCE: ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED."

Checking each criterion:
- **Distinct spans:** yes.
- **Both attested acts:** yes. Both are issued authorizations in the same register template.
- **The actor contrast is in the spans:** "ARB" vs "ARB CHIEF (PREPARED)". That "ARB" at R-70 means the human authority rests on R-81's "Every prior ruling R-1..R-80 was issued by the human authority".
- **Not only the actor differs.** The target also differs (WP-7B-R1 vs WP-4C-1; EVENTS.json hides this behind "own-slice"), and so does the scope ("PLAN AND").
- **Real contrast, but confounded:** the authority contrast is genuine and attested, but mixed with target and scope differences. So WEAK, not STRICT.

## 5. Counts

- **Distinct records after merges:** **36** (agree; 0 MERGE).
- **UNANCHORED:** **3** (events 24, 28, 35; agree). A strict reading of event 1 would make it 4; the alternative reading of event 24 would make it 2.

## 6. Strongest alternative readings

- **(a) → WEAK.** The annotation shows the Chief retracting an implied adoption. The row's outcome column had already claimed "Batch 7 partially released", and the annotation says "“BATCH 7 IS RELEASED” DOES NOT HOLD". Read that way, "declines… in the Chief's own favour" is functionally the Chief refusing to let its own issuance count as adoption: a performed refusal on the same five rulings, contrasted with the DA's performed adoption. The actor and polarity would both vary, but the object would be fixed.
- **(b) → FAILS.** R-89 is "PREPARED, NOT ADOPTED", and no adoption of it appears in the sources. So member 5 may be only a *prepared* authorization, not an effective one. With the target and scope also differing, three factors vary, not one.

## Disagreement classification

| # | Disagreement | Class |
|---|---|---|
| 1 | Pair (a): A says "no" and `both_anchored: true`; reviewer says FAILS, because member 1 is a rule/determination, not a refused ADOPT | source interpretation |
| 2 | Pair (b): A gives an ungraded "no"; reviewer says WEAK, since the actor contrast is attested but confounded by target and scope | formal reasoning |
| 3 | Event 22: "L493-B uncheckable" is wrong; line arithmetic puts L493 on the span line | evidence scope |
| 4 | Heading nesting disclosed for 6/7 but not for 15–22 | wording |
| 5 | Strict verbatim: 15 spans contain stripped markers (convention disclosed) | wording |
| 6 | description_adds omit the refused polarity for 26 and 27 | coding |
| 7 | description_adds omit the AST-013 target for 17 and 18 | coding |
| 8 | 15–21 anchored to matrix rows rather than the stated anchor R-36; R-36 would make 20/21 DUAL-SPAN | coding |
| 9 | 6/7: the smallest-span rule allows disjoint spans, so both could be ANCHORED | coding |
| 10 | 29: the span is a ground, not an act; the act sentence is shared with 30 | coding |
| 11 | 31: recording-note span preferred over the ruling's own outcome column | coding |
| 12 | 24: "Issued as R-90" is a candidate anchor (unused number assigned) | source interpretation |
| 13 | 1: ANCHORED holds only on inference | source interpretation |
