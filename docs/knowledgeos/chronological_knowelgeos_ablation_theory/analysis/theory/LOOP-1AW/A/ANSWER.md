# Anchoring 36 events to verbatim source spans

**How to read this.** I used only EVENTS.json and SOURCES.md. Spans are copied with the Markdown emphasis markers (`**`, `*`) removed; all other characters are exact. ANSWER.json has the full spans. Each claim carries one label:
- **SOURCE FACT**: the text says it.
- **INFERENCE**: my reading of the text.
- **UNKNOWN**: the sources can't decide it.

## Summary

| Status | Count | Events |
|---|---|---|
| ANCHORED | 31 | 1–5, 8–23, 25–27, 29–34, 36 |
| SHARED (DUAL-SPAN) | 2 | 6 ↔ 7 |
| UNANCHORED | 3 | 24, 28, 35 |

- **MERGE pairs:** 0.
- **Distinct records after merge:** 36. It would be 35 if you treat 8 and 9 as one motion; see ambiguity A2.

## Per-event anchors

| n | Section | Span (short form) | Status | What the description adds |
|---|---|---|---|---|
| 1 | R-81 (also word for word in R-83) | "The Chief declines to resolve an ambiguity about the Chief's own authority in the Chief's own favour…" | ANCHORED | INFERENCE: the target R-81..85 (the text says "these five"). Framing this as "ADOPT declined" is a reading, not the text's wording. |
| 2 | R-86 | "Decision Authority confirms and adopts R-81 through R-85 without amendment." | ANCHORED | nothing |
| 3 | R-91 | "HELD 2026-08-04 — NOT ADOPTED… The Decision Authority held it on the ground that…" | ANCHORED | nothing |
| 4 | R-70 | "WP-7B-R1 AUTHORIZED TO IMPLEMENT — Execution Governance · Authorization · ARB." | ANCHORED | nothing |
| 5 | R-89 | "WP-4C-1 AUTHORIZED TO PLAN AND IMPLEMENT — … ARB CHIEF (PREPARED)." | ANCHORED | the operation label drops "PLAN" |
| 6 | S0804-L576 | "Then R-90 itself withdrawn before adoption, because the Authority ruled it was operational acceptance rather than a constitutional decision — the register holds constitutional decisions." | SHARED with 7, DUAL-SPAN | the text says "filed/withdrawn", not "REGISTER"; the attempt being undone isn't in the event_id |
| 7 | S0804-L576 | same sentence | SHARED with 6, DUAL-SPAN | nothing |
| 8 | R-60 | "the issuing text placed this motion under a heading reading "Recording Notes -- Not Part of Ruling"" | ANCHORED | that a note opened the work. SOURCE FACT: "A recording note cannot open a work package". |
| 9 | R-60 | "so it is filed as a ruling in line with the operative text" | ANCHORED | "refiled": no earlier filing is stated |
| 10 | R-72 | "THAT PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN" | ANCHORED | nothing |
| 11 | git-6a67da5d7 | "Date: 2026-08-03 16:10:37 +0200 / Subject: test(adjudication): WP-4B RED -- …" | ANCHORED | an authorized governance START (none is stated); the time is truncated |
| 12 | R-79 | "WP-8 MAY BE PLANNED AND MUST NOT BE STARTED" | ANCHORED | "permission only" sits on START, but SOURCE FACT: the permission covers planning only |
| 13 | R-47 | "First authorized activity: RED at 7A" | ANCHORED | that the start was performed (only authorization is stated) |
| 14 | R-47 | "7B and 7C are NOT authorized and follow their own gates" | ANCHORED | the refused polarity is missing from the event_id |
| 15–18 | R-36 matrix rows 2, 3, 9, 10 | one table row each | ANCHORED | the outcome "promoted" comes from ## R-36, not from the row (the row only says PROMOTE); the raiser is unstated |
| 19–21 | R-36 matrix rows 5, 14, 17 | one table row each | ANCHORED | the target AST-013 isn't named in the rows; the raiser is unstated |
| 22 | S0815-L483 | "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one." | ANCHORED | the identifier "L493-B" (UNKNOWN); the refused outcome |
| 23 | R-41 | "Artifact Lifecycle Consistency adopted as a permanent documentation standard (Principal Architect instruction)." | ANCHORED | the label "P1"; "RAISE" understates "adopted" |
| 24 | none (register-numbering has no text) | null | UNANCHORED | the whole event. The nearest spans are R-90 "Issued as R-90…" and "The next ruling is R-91." |
| 25 | R-90 | "THE NUMBER R-90 IS RETIRED AND MUST NOT BE REUSED." | ANCHORED | the polarity (a prohibition; no attempt is recorded) |
| 26 | R-77 | "ADR-T14 is not superseded." | ANCHORED | nothing |
| 27 | R-83 | "it APPLIES §12 rather than superseding it" | ANCHORED | nothing |
| 28 | none (ADR-MP has no text; "D-12" appears nowhere) | null | UNANCHORED | the whole event |
| 29 | R-94 | "superseding would blur chronology" | ANCHORED | an explicit "declined" act (INFERENCE from "ANNOTATE is adopted") |
| 30 | R-94 | "ANNOTATE is adopted." | ANCHORED | nothing |
| 31 | R-56 | "Execution of 7B is a SEPARATE act and has NOT been issued" | ANCHORED | the event_id doesn't say this is a recording note, not part of the ruling |
| 32 | R-58 | "Engineering is authorized to begin Slice 7B RED on keystones K1–K10, with K9 inside 7B." | ANCHORED | a performed start (only authorization is stated) |
| 33 | R-58 | "Slice 7C remains unauthorized" | ANCHORED | nothing |
| 34 | R-65 | "Slice 7C AUTHORIZED; engineering may begin RED" | ANCHORED | a performed start (only permission is stated) |
| 35 | R-81 | null. The nearest span is "all other Batch-7 work still frozen". | UNANCHORED | the target "§12 reconcile" is never named in R-81 (INFERENCE via R-86) |
| 36 | R-86 | "engineering is AUTHORIZED to execute R-81's per-process isolation, R-84's §12 reconcile (…)" | ANCHORED | a performed start (only authorization is stated) |

### The one SHARED pair: 6 and 7 (DUAL-SPAN)

- **SOURCE FACT:** a single sentence in S0804-L576 carries both events:
  - **The act (event 6):** "R-90 itself withdrawn before adoption, because the Authority ruled it was operational acceptance". This is an attempted registration of operational acceptance, then undone.
  - **The norm (event 7):** "the register holds constitutional decisions".
- **SOURCE FACT:** R-90 has a parallel sentence that also merges the two: "The register holds constitutional decisions, not operational acceptance."
- **INFERENCE:** this is two events in one sentence (an act and a norm), not one event described twice. So the verdict is DUAL-SPAN, not MERGE.

## Authority pairs

**(a) Event 1 (Chief, declined) vs event 2 (DA, R-86):**
- **SOURCE FACT:** both are anchored to different spans, in R-81's annotation and in R-86.
- **They do not differ only in actor.** The outcomes are opposite (declined vs adopted), and the kinds of act differ: a provenance annotation vs a constitutional adoption act.
- **INFERENCE:** the Chief's span doesn't name R-81..R-85; it only says "these five".
- **Answer: no.**

**(b) Event 4 (R-70, ARB) vs event 5 (R-89, Chief, PREPARED):**
- **SOURCE FACT:** both are anchored to different spans.
- **They also differ in:**
  - target (WP-7B-R1 vs WP-4C-1)
  - scope ("TO IMPLEMENT" vs "TO PLAN AND IMPLEMENT")
  - effect (R-89 is "PREPARED, NOT ADOPTED"; R-70 is governing)
  - date
- **INFERENCE:** the "ARB" label on R-70 means the human authority. This rests on R-81's annotation: "Every prior ruling R-1..R-80 was issued by the human authority".
- **Answer: no.**

## Ambiguities

1. **A1, the "declined" framing (event 1).** The Chief "declines to resolve an ambiguity … in the Chief's own favour". Reading this as a declined ADOPT is an INFERENCE. The annotation also appears word for word in R-81 and R-83; I treat that as one text repeated, not two events.
2. **A2, events 8 and 9.** Each has its own span, but the source describes **one motion** that was misplaced under a note heading and then filed as a ruling. The source also says a note *cannot* open a work package. So event 8 may never have happened as an opening act. Whether 8 and 9 should merge is UNKNOWN; I kept them apart because the source text tells them apart.
3. **A3, sub-clause splitting for 6 and 7.** You could split the S0804 sentence at the dash and call both ANCHORED. I chose SHARED/DUAL-SPAN because it is a single sentence carrying an act and a norm, which is the example TASK.md gives.
4. **A4, "START" events (13, 32, 34, 36, 11).** The sources state authorizations, not performed starts. The one exception is event 11: the git commit shows RED work, but no authorization is stated for it. R-72 ("no RED begins") and R-77 ("WP-4B REMAINS BLOCKED", 08-03) seem to conflict with the 08-03 16:10 RED commit. Whether R-77 came before or after the commit is UNKNOWN.
5. **A5, the R-36 items (15–21).** The "#n" numbers exist only in the matrix, and the "promoted" outcome exists only in R-36, so no single span carries both. Some names also differ: matrix #3 is "Ownership Drives Reuse", while R-36 says "ownership-determines-architectural-reuse". SOURCE FACT: R-36 promoted "stop-at-architectural-uncertainty", which the matrix marks as #7 "already / No delta". This conflict affects no listed event.
6. **A6, event 22.** The id "L493-B" can't be checked because the excerpt has no line numbers (UNKNOWN). The anchoring rests on the only "single occurrence … methodology" sentence. A similar sentence in R-90 ("deliberately not promoted to methodology. ES-006.1 stands") sits under a different anchor.
7. **A7, events 24 and 28.** Both anchors are "NO SOURCE TEXT PROVIDED", so both are UNANCHORED (UNCLEAR per the source's own header). For event 24, the R-90 numbering sentences come close but state specific numbers, not a never-used-number rule.
8. **A8, event 35.** R-81's decision text lifts the freeze for the isolation repair only. Its annotation adds that "BATCH 7 IS RELEASED" does not hold at all until adoption. Either way, §12 reconcile is never named in R-81.
