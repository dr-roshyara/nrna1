# REVIEW — independent review of A's EXECUTION against SOURCES.md

Files used: REVIEW-TASK.md, TASK.md, SOURCES.md, EXECUTION.json and EXECUTION.md. Nothing else was used. Event ids follow A's (E01–E30). I add one event, **E31**. The full corrected coding is in `REVIEW.json`.

## 1. Summary verdict

- **OBSERVATION:** A used only SOURCES.md. I found no fact from outside it. The quotes I checked are verbatim, apart from dropped markdown and marked ellipses.
- **OBSERVATION:** A's arithmetic is correct *given A's coding*. I rebuilt every deciding and undetermined pair list for M_E, M_A, M_T and M_AT from A's own values and got A's counts (25 / 13 / 8 / 3). The only formal slip is small: A counts pairs with E08 as undetermined even though E08's stated range ("2–3") rules them out.
- **INFERENCE (main disagreement):** A's one deciding pair is (E26, E27). It depends on coding E27 as a *decided* NOT-PROMOTED on a RULE ground by "the session author". But the text shows the author as someone who **recommends and refrains**, not someone who **decides**: *"GOVERNANCE FLAG — one decision belongs to the DA, not to me"*, *"Options for the DA … Recommendation: (b)"*.
  - Whether the protocol goes into a standards document is **one** decision item, and it is still pending with the DA. A has already coded it as E28 (outcome UNK, ground CHOICE).
  - A records that one item twice (E27 and E28), with conflicting outcomes.
  - Once E27 is merged into E28, no deciding pair is left.
- **INFERENCE:** Two further coding changes:
  - E18 (the "Engineering Standards document" itself) is a *home*, not an item promoted into one, so I remove it as an event.
  - A missed a standing-raising event: E31, the governing principle "generalised by the PA" and carried at the top of the protocol.
- **FORMAL CONSEQUENCE:** On my corrected coding **all four models are UNDETERMINED**, with 8 / 5 / 5 / 3 undetermined pairs and no deciding pair. M_C is not needed, but it cannot be excluded.
- **FORMAL CONSEQUENCE:** The only verdict that changes is **M_E: FALSIFIED (A) → UNDETERMINED (reviewer)**. The M_A, M_T, M_AT and M_C verdicts are the same as A's.
- **INFERENCE:** The M_E verdict is fragile both ways. It flips back to FALSIFIED under three readings the text does not rule out:
  - A's reading of E27;
  - E01's evidence read as 1 instance;
  - E31's evidence read as 1 slice.

  See §4.

## 2. Per-event findings

"Agree" means that, after re-reading the quote, the value is supported. The Class column is filled only where I disagree.

| Event | A's key coding (actor · target · evidence · outcome · ground) | Finding | Class |
|---|---|---|---|
| E01 ES-004.3 | PA · STANDARD-DOC · UNK · PROMOTED · UNK | Agree. **SOURCE FACT:** "(Principal Architect instruction)"; the text is hosted in `ES-004-Documentation.md`, which is an existing standards document ("not a new standard document"), so STANDARD-DOC is justified. **SOURCE FACT:** the provenance line names the WP-1 inconsistency, and then "a second instance" caught by the first checklist execution, which ran after hosting. **INFERENCE:** evidence is 1 or 2 instances, so UNK is correct under "never guess". | — |
| E02 O-2 watch item | UNK · UNK · 1 instance · NOT-PROMOTED · UNK | Agree, as a borderline case. "below the evidence bar" names a bar but no rule, so ground UNK is defensible. RULE is the alternative (§4). | — |
| E03 vocabulary ruling | PO/ARB · OTHER · UNK · PROMOTED · UNK | **Target:** no home is named. "applies to ALL future documentation" gives the ruling's *scope*, not a home, so it should be UNK. **Outcome quote:** "Applied immediately to …" records *application*, not promotion; the PROMOTED reading rests on "BINDING … applies to ALL future documentation … registered". **Cluster:** see E04. | coding (target) · wording (quote) |
| E04 three planes | UNK · METHODOLOGY · 1 occurrence · NOT-PROMOTED · RULE | Agree on every field. **SOURCE FACT:** "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one." **Cluster:** A merges E04 with E03, but the ruling was made by PO/ARB, while the disposition of E04 has an UNK actor (A's own coding). Sharing a log entry is not the same as being decided together, so I give them separate clusters. No effect on verdicts. | independence |
| E05, E06, E07, E09 | ARB · OI · 3 slices · PROMOTED · UNK | Agree. **OBSERVATION:** I re-checked the S4→S5 join. The category counts match exactly (C=4, D=5, B=2, already=4 after #7 moves, promoted 6 = 4 A-items + #7 + one item not in S4). The actor ARB is best supported by "ARB wording refinement at adoption", rather than by the category counts. | — |
| E08 epistemic labels | ARB · OI · UNK ("2–3") · PROMOTED · UNK | Value agreed. **FORMAL CONSEQUENCE:** the stated range means evidence ≥ 2, so pairs with a Q at evidence 1 (E04, E16) cannot falsify. A still lists (E08,E04) and (E08,E16) as undetermined. No verdict changes. | formal reasoning |
| E10 | ARB · OI · UNK · PROMOTED · UNK | Agree. | — |
| E11 Registration ≠ Delivery | ARB · OI · 1 slice · NOT-PROMOTED · UNK | Agree. S5's "Expressly NOT promoted" is set against "Promoted into AST-013", which supports the target convention. The actor quote (a stitched fragment) is weak. | wording (quote) |
| E12 "configuration is not capability" | ARB · OI · 1 · NOT-PROMOTED · UNK | This is a **candidate-pattern registration** ("→ Candidate Pattern"), not a promotion decision. S5 does not name it. Its target is better coded CANDIDATE-PATTERN. It plays no role in falsification. The actor quote ("candidates (D) 5") is a count, not a name. | coding |
| E13 Domain ≠ Integration Event | ARB · PRINCIPLES-DOC · 2 · NOT-PROMOTED · UNK | **Target/outcome mismatch.** What was expressly not promoted in R-36 was promotion into AST-013. The PRINCIPLES-DOC home is a pending "Category-B follow-up", and A itself notes that "the principles-doc outcome itself is not decided". So the coding should be OI + NOT-PROMOTED (the R-36 decision), with the PRINCIPLES-DOC outcome UNK. The actor quote "ARB may prefer waiting for a 3rd slice" is S4 speculation, not evidence of who decided. No effect on verdicts (ground UNK). | coding · wording |
| E14 process chain | ARB · METHODOLOGY · 3 · NOT-PROMOTED · UNK | Same mismatch as E13: v1.1 ratification is a follow-up, so the METHODOLOGY outcome is UNK. No effect on verdicts. | coding |
| E15 Strangler | ARB · OI · 1 context · NOT-PROMOTED · UNK | Agree. | — |
| E16 carrier | ARB · OI · 1 slice · NOT-PROMOTED · RULE | Values agreed. RULE: "explicitly ruled 'temporary carrier, not a pattern' (PB-005 Q1)" plus ER-02 "replaced only on repeated evidence". The target *value* is supported by S5's "Promoted into AST-013 … Expressly NOT promoted: … carrier". The quote A attaches to the target does not state a home. | wording (quote) |
| E17 PGP-03 hoist | ARB · OTHER · UNK ("2-ish") · NOT-PROMOTED · UNK | Agree. | — |
| E18 Engineering Standards document | ARB · STANDARD-DOC · UNK · NOT-PROMOTED · RULE | **Home vs item confusion.** The candidate *is* the standards document ("building it now would violate Documentation Economy"). No item is being promoted into a home, so under TASK §1 this is not a standing-raising event and it is removed as Q. No change to A's verdicts; it removes undetermined pairs. | evidence scope |
| E19–E22, E24, E25 | ARB · OI · (various) · NOT-PROMOTED · UNK | Agree. They play no role in falsification. The target quotes for E21 and E22 describe category C / "already", not OI; the value follows A's convention. | — |
| E23 Test Behaviour | ARB · OI · 3 · NOT-PROMOTED · UNK | Agree as a non-promotion into `.claude`. Note: its "single designated home" is the v1.1 draft. | — |
| E26 protocol as operating standard | PA · OTHER · 2 slices · PROMOTED · UNK | Agree. **SOURCE FACT:** "Received from the PA as the going-forward protocol. In force from the next work package."; "ADOPTED AS THE OPERATING STANDARD … NOT self-promoted into a standards document"; "following the protocol meanwhile without a standards doc". **INFERENCE:** OTHER (rather than STANDARD-DOC) is consistent with E01, because E01 sits inside a standards document and E26 sits in none. | — |
| E27 protocol not self-promoted | session author · STANDARD-DOC · 2 · **NOT-PROMOTED · RULE** | **Disagree.** The author explicitly disclaims the decision ("one decision belongs to the DA, not to me") and only recommends ("Recommendation: (b)"). Phase 16 limits *who* may promote ("without authorization"); it does not decide *whether* the item is promoted. The item's decision is E28, which is pending. So E27 is not a separate decision record: it merges into E28, with actor DA, outcome UNK and ground CHOICE, and is excluded from falsification. | source interpretation (outcome) · coding (actor, ground) · independence (record) |
| E28 DA decision | DA · STANDARD-DOC · 2 · UNK · CHOICE | Agree. It absorbs E27. | — |
| E29 parked candidate | PA · UNK · UNK · NOT-PROMOTED · UNK | Agree. Alternative: ground RULE, since the PA set "an explicit promotion criterion" (§4). | — |
| E30 R-39 | DA · UNK · UNK · PROMOTED · UNK; exception YES | Agree. The phrase "recorded as an exception" sits in the option-(a) parenthesis, but it most naturally describes how DA-authorized early promotion (R-39) is recorded. | — |
| **E31 governing principle (new)** | *(not coded by A)* | **Missing event.** **SOURCE FACT:** "Governing principle carried at the top of the protocol (originating in this slice's mapper self-correction, now generalised by the PA)" and "Applied twice in WP-2". Reviewer coding: PA · OTHER (the protocol / operating standard) · 2 occurrences · PROMOTED · ground UNK · exception UNK · cluster C-S6-PROTOCOL. It is not independent of E26. | evidence scope |

## 3. Recomputed model verdicts

**FORMAL CONSEQUENCE:** In the table below, "Undetermined" counts only pairs that some admissible value of an UNK field could make falsifying (A's convention). The figure in brackets applies E08's stated range.

| Model | A's coding (recomputed by reviewer) | Reviewer's corrected coding |
|---|---|---|
| M_E | **FALSIFIED** by (E26, E27) · 25 undetermined (23) | **UNDETERMINED** · 0 deciding · 8 undetermined: (E01,E04) (E01,E16) (E03,E04) (E03,E16) (E10,E04) (E10,E16) (E30,E04) (E30,E16) |
| M_A | UNDETERMINED · 13 (11) | **UNDETERMINED** · 5: (E01,E04) (E03,E04) (E10,E04) (E30,E04) (E10,E16) |
| M_T | UNDETERMINED · 8 (7) | **UNDETERMINED** · 5: (E03,E04) (E30,E04) (E03,E16) (E10,E16) (E30,E16) |
| M_AT | UNDETERMINED · 3 (2) | **UNDETERMINED** · 3: (E03,E04) (E30,E04) (E10,E16) |
| M_C | not needed; not excludable | not needed; not excludable |

- **FORMAL CONSEQUENCE (corrected coding):**
  - The eligible P are E01, E03, E05–E10, E26, E30 and E31.
  - The eligible Q are E04 (UNK, METHODOLOGY, 1) and E16 (ARB, OI, 1).
  - Every P with known evidence has 2 or more, and every Q has 1. So no pair is decided on known values, and every open pair runs through a P whose evidence is UNK (E01, E03, E10, E30).
- **FORMAL CONSEQUENCE (A's coding):**
  - A's own sensitivity note is correct: E01 at 1 or 2 falsifies M_T via (E01, E27).
  - E01's stated evidence can only be 1 or 2, so under A's coding the pair falsifies M_T on *every* value the text allows. By the frozen letter ("UNK on a needed field → UNDETERMINED"), A's UNDETERMINED is still formally correct.
- **FORMAL CONSEQUENCE:** In A's convention, a PROMOTED item with ground UNK counts as P. Under the stricter reading, where ground is a "needed field" because CHOICE excludes an item, every P in both codings has ground UNK. Then no model can be FALSIFIED on either coding. A's sensitivity section does not test this, and does not test E26 in particular.

## 4. Alternative interpretations

This covers A's one deciding pair and every near-deciding pair on the corrected coding.

| # | Alternative reading | Effect | Does the text rule it out? |
|---|---|---|---|
| 1 | E27 is a real, decided RULE-grounded non-promotion by the author, as A reads it: "so I have **not** created one". | M_E becomes FALSIFIED again via (E26, E27). | **No.** The non-promotion is stated explicitly. The text only shows that the author is not the one who decides. |
| 2 | E26's actor is the session author, since the heading "ADOPTED AS THE OPERATING STANDARD" is passive. | Combined with A's E27, M_A is FALSIFIED (same actor, 2 ≥ 2). | **Largely.** "Received from the PA as the going-forward protocol. In force from the next work package." |
| 3 | E26's target is normalized to STANDARD-DOC, because TASK §1 lists "a standard or a standards document". | Combined with A's E27, M_T is FALSIFIED. | **Largely.** The heading contrasts "OPERATING STANDARD" with "standards document", and the text says "without a standards doc". |
| 4 | E26 is not a standing-raising event. | Under A's coding, M_E becomes UNDETERMINED (A tested this). | No. |
| 5 | Strict falsification rule: a P with ground UNK is undetermined. | Every model is UNDETERMINED on both codings. | No. The TASK phrase "RULE-grounded-or-PROMOTED" favours A's reading, but does not settle it. |
| 6 | E01's evidence is 1 instance: only the WP-1 inconsistency came before adoption. | Corrected M_E is FALSIFIED via (E01,E04) and (E01,E16). M_A (E01,E04) stays undetermined, because E04's actor is UNK. | **No.** The provenance line lists both instances, but the second was "caught" by the rule's own first execution. |
| 7 | E31's evidence is 1 slice ("this slice", "in WP-2") rather than 2 occurrences ("Applied twice"). | Corrected M_E is FALSIFIED via (E31,E16), same unit, and via (E31,E04). | **No.** Both counts are in the text. |
| 8 | E02's ground is RULE ("below the evidence bar"). | Adds Q E02 (1 instance). It decides nothing unless E01 = 1. | No. |
| 9 | E29's ground is RULE (the PA's "explicit promotion criterion"). | Adds Q E29 with evidence UNK, which creates M_A undetermined pairs with the PA items E01, E26 and E31. No verdict changes. | No. |
| 10 | E04's actor is PO/ARB. | M_A (E03,E04) becomes same-actor, but stays undetermined because E03's evidence is UNK. | No. |

## 5. Disagreement register

| # | Event · field | A | Reviewer | Class |
|---|---|---|---|---|
| 1 | E27 · outcome | NOT-PROMOTED | UNK (pending DA decision) | source interpretation |
| 2 | E27 · actor | the session author / engineering | Decision Authority (the author only recommends) | coding |
| 3 | E27 · ground | RULE (Phase 16) | CHOICE (the DA's options (a)/(b)); Phase 16 governs who may promote | coding |
| 4 | E27/E28 · record | two decision records | one decision item | independence |
| 5 | M_E · verdict | FALSIFIED | UNDETERMINED | substantive |
| 6 | E18 · inclusion | Q event (RULE) | not a standing-raising event (the home, not an item) | evidence scope |
| 7 | E31 · inclusion | absent | PROMOTED, PA, OTHER, 2 occurrences | evidence scope |
| 8 | E03 · target_type | OTHER | UNK | coding |
| 9 | E03 · outcome quote | "Applied immediately …" | "BINDING … applies to ALL future documentation" | wording |
| 10 | E03/E04 · cluster | C-S3-NINTH (shared) | separate clusters | independence |
| 11 | E13 · target/outcome | PRINCIPLES-DOC + NOT-PROMOTED | OI + NOT-PROMOTED; PRINCIPLES-DOC outcome UNK | coding |
| 12 | E14 · target/outcome | METHODOLOGY + NOT-PROMOTED | OI + NOT-PROMOTED; METHODOLOGY outcome UNK | coding |
| 13 | E12 · target_type | OPERATING-INSTRUCTIONS | CANDIDATE-PATTERN (a registration) | coding |
| 14 | E13 · actor quote | "ARB may prefer waiting for a 3rd slice" | "ARB wording refinement at adoption" | wording |
| 15 | E16 · target quote | "…carrier … remain Candidates" | "Promoted into AST-013 … Expressly NOT promoted: …" | wording |
| 16 | E11/E12/E15/E19–E25 · actor quotes | category counts | "ARB wording refinement at adoption" | wording |
| 17 | E08 pairs | undetermined with E04/E16 | not falsifiable (evidence ≥ 2 stated) | formal reasoning |
| 18 | Sensitivity | P with ground UNK always eligible; E26-as-CHOICE not tested | strict reading makes every pair undetermined; should be reported | formal reasoning |

**OBSERVATION:** A is right, and I have no disagreement, on:
- E01, E02, E04 (values), E05–E11, E15–E17 and E19–E30 (values);
- the S4→S5 join;
- E26's target;
- E01's evidence as UNK;
- every pair count on A's own coding, apart from item 17;
- the M_A, M_T, M_AT and M_C verdicts.

## 6. What this evidence can and cannot establish

- **SOURCE FACT:** The sources state thresholds in several places: ES-006.1 "forbids promoting from one"; "2 slices is the floor"; ER-02 "repeated evidence"; the PA's "WP-1 plus a few more slices"; the "evidence bar"; and "early promotion … recorded as an exception".
- **OBSERVATION:** On either coding, every PROMOTED item with known evidence has 2 or more, and every RULE-grounded refusal with known evidence has 1 (or 2, under A's E27).
- **INFERENCE:** The sources can show that evidence thresholds and authorization rules (Phase 16, DA exceptions) are both cited. They cannot separate the two mechanisms: the only case where an authorization rule does the work (E27/E28) is still pending.
- **FORMAL CONSEQUENCE:** On the corrected coding, no model is falsified and none survives. Each verdict depends on UNK evidence (E01, E03, E10, E30) and, for M_E, on how E01 and E31 are counted.
- **UNKNOWN:**
  - how the DA decides E28;
  - what R-39 promoted, into which home, and at what evidence;
  - who made the E04 disposition;
  - the evidence behind E03 and E10;
  - whether counts in different units (instances, occurrences, slices, contexts) are meant to be compared. The frozen rule compares bare integers.
- **UNKNOWN:** Whether a non-evidence mechanism (M_C) is at work. The sources contain too few determinate Q events: two on the corrected coding, both at evidence 1.
