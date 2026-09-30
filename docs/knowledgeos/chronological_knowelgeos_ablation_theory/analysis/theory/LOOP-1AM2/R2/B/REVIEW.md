# REVIEW of A's execution (EXECUTION.json / EXECUTION.md)

Inputs used: REVIEW-TASK.md, TASK.md, SOURCES.md, EXECUTION.json, EXECUTION.md. Nothing else.

## 1. Summary verdict

- **OBSERVATION:** A used only SOURCES.md. I found no fact from outside it. Every quote I checked is verbatim, apart from formatting and four quote fields where A put its own gloss inside the quote (see D-10).
- **FORMAL CONSEQUENCE:** A's four model verdicts follow from A's own coding. I recomputed every deciding pair and none is wrong. The deciding-pair list for M_E is complete under A's coding.
- **FORMAL CONSEQUENCE:** My corrected coding gives the **same four verdicts**: M_E FALSIFIED · M_A UNDETERMINED · M_T FALSIFIED · M_AT UNDETERMINED · M_C not established as needed.
- **OBSERVATION:** The verdicts do not change, but I disagree with A on 11 points (§5). The ones that matter most:
  1. **E01 evidence.** A coded UNK. The text names exactly one provenance instance, so I code 1 (D-1).
  2. **(E25, E01) under M_T.** A called this pair undetermined, but by A's own reasoning every textual reading makes it deciding. A used a range as a bound to rule pairs *out* (E13), but did not use a bound to rule this pair *in* (D-2).
  3. **Target home vs the item.** E22's "item" is itself a home (the Engineering Standards document), so E22 should be excluded (D-4).
  4. **B-category items (E09, E14).** A coded these with the home they were *routed to*, but coded the D items with the home they were *denied*. That is inconsistent (D-3).
  5. **Grounds.** A applied its own ground criterion inconsistently to E17 (D-5).
- **INFERENCE:** My corrections make M_T's falsification *more* robust. Under A's coding it rests on one pair, (E02, E27), which fails if R-39 were read as CHOICE. Under my coding it also rests on (E25, E01), which fails only if ES-004.3 is not normalized as STANDARD-DOC. **Both** readings would have to hold at once to rescue M_T.

## 2. Per-event findings

Codes: ✔ = agree · ✎ = disagree (item in §5) · ⚠ = agree with the value, but note a weakness or an alternative.

| Event | A's coding (actor · target · evidence · exc · outcome · ground) | Finding | Label |
|---|---|---|---|
| E01 ES-004.3 / R-41 | PA · STANDARD-DOC · UNK instances · UNK · PROMOTED · UNK | ✎ **evidence.** "Provenance: the WP-1 closure inconsistency" (S1) is one instance, and S2 says "the rule generalizes the WP-1 status-line correction". So evidence = **1 instance**. The "second instance" came *after* adoption. Even counting it, the total is ≤ 2, and both instances are within one slice (WP-1). ⚠ **target:** STANDARD-DOC is right, but not for A's reason ("the task lists 'a standard'"). The right reason is that it is hosted *inside* an existing standards document: "ES-004.3 hosted in `engineering/governance/ES-004-Documentation.md`" (S2). A's reason would equally make E24 STANDARD-DOC (D-9). Actor, outcome and cluster ✔. | SOURCE FACT / INFERENCE |
| E02 three state planes | UNK · METHODOLOGY · 1 occurrence · UNK · NOT-PROMOTED · RULE | ✔ All fields. The actor is correctly UNK: the disposition is passive. The ground "ES-006.1 forbids promoting from one" is verbatim and is a rule that forbids. ⚠ A says the candidate actors "differ from Decision Authority". That holds only as a comparison of the names. The text never says whether the PO is or is not the DA. | SOURCE FACT |
| E03 #1 Evidence Before Governance | ARB · OI · UNK · UNK · NP · CHOICE | ⚠ Value accepted. The outcome depends on A's count-reconciliation of R-36. That reconciliation is correct (see E23), but it is an inference, and A labels it SOURCE. The actor basis is inference, not SOURCE (D-6). The ground quote contains A's gloss (D-10). | INFERENCE |
| E04 #2 Reuse Before Create | ARB · OI · 3 slices · UNK · PROMOTED · CHOICE | ✔ Values. The ground quote contains a gloss (D-10). The ground rests on S4's *recommendation* text; R-36 gives no per-item reason. | OBSERVATION |
| E05 #3 Ownership Drives Reuse | ARB · OI · 3 · UNK · P · CHOICE | ✔ Values. ⚠ The ground quote "PROMOTE — one line; the strongest new principle" is a recommendation, not the decision's ground. CHOICE is accepted under the matrix-selection frame. | OBSERVATION |
| E06 #4 Test Behaviour | ARB · OI · 3 · UNK · NP · CHOICE | ✔ | SOURCE FACT |
| E07 #5 Registration≠Delivery (scoped) | ARB · OI · 1 slice · UNK · NP · CHOICE | ✔ R-36: "Expressly NOT promoted: Registration ≠ Delivery stays Messaging-scoped in ADR-MP-06 (1 slice)". The quote contains a gloss (D-10). | SOURCE FACT |
| E08 #5 generalized | ARB · OI · 1 · UNK · NP · UNK | ✔ The ground is UNK, consistent with A's criterion. The target is OI, read from the structure of R-36 ("Promoted into AST-013 … Expressly NOT promoted: …"). A's own ambiguity note 7 says the D-item home "is not stated per item". I think A is too cautious there: the R-36 list structure supplies the home. | INFERENCE |
| E09 #6 Domain≠Integration Event | ARB · PRINCIPLES-DOC · 2 · UNK · NP · CHOICE | ✎ **target and outcome confused.** The outcome quote is R-36's "Expressly NOT promoted" (relative to AST-013). But the target A coded is the principles doc, and for that home the item is a "Category-B follow-up … separate slices", which is pending, not refused. Consistent coding: target OI, NP, with the principles-doc promotion recorded as pending (UNK). There is no verdict effect, because the item is CHOICE. | SOURCE FACT |
| E10 #7 Stop at uncertainty | ARB · OI · 3 · UNK · PROMOTED · UNK | ✔ A correctly coded the *decision* (R-36 promoted it) rather than S4's *recommendation* ("already … do not duplicate it"). This is a clear case of recommendation ≠ decision, and A handled it correctly. | SOURCE FACT |
| E11 #8 Freeze architecture | ARB · OI · UNK · UNK · NP · CHOICE | ✎ **evidence.** "IDD-FROZEN practice in all three tickets" states 3 (the three tickets are PB-004/005/006, which are the column's slices). No verdict effect. | SOURCE FACT |
| E12 #9 Deferred≠skipped | ARB · OI · 3 · UNK · P · CHOICE | ✔ The quote contains a gloss (D-10). | — |
| E13 #10 Epistemic labels | ARB · OI · UNK (2–3) · UNK · P · CHOICE | ✔ UNK is correct for a range. Using it as a bound is valid. | — |
| E14 #11 Discovery→…→Completion chain | ARB · PRINCIPLES-DOC · 3 · UNK · NP · CHOICE | ✎ Same confusion as E09. In addition, the routed-to home (`Implementation_Process_v1.1_Draft.md`, a process document) is not a principles document. No verdict effect. | SOURCE FACT |
| E15 #12, E16 #13, E18 #15, E19 #16 | ARB · OI · … · NP · CHOICE | ✔ | — |
| E17 #14 Strangler | ARB · OI · 1 context · UNK · NP · CHOICE | ✎ **ground.** The only reason given is evidential: "1 context only … evidence it does not generalize by default". A itself coded E08 (a count as the only reason) and E01/E10/E24/E27 (reasons, but no options) as UNK. By that criterion, E17 is UNK. Effect: E17 becomes a potential Q, which adds only undetermined pairs. | FORMAL CONSEQUENCE |
| E20 #17 carrier | ARB · OI · 1 slice · UNK · NP · RULE | ⚠ Accepted: "explicitly ruled 'temporary carrier, not a pattern' (PB-005 Q1)" is a prior ruling that forbids pattern status. Alternative: "replaced only on repeated evidence (ER-02)" governs *replacement*, not promotion. On that reading the ground rests only on the Q1 classification, and could be read as UNK. | SOURCE FACT / INFERENCE |
| E21 #18 PGP-03 hoist | ARB · OI · UNK ("2-ish") · UNK · NP · UNK | ✔ | — |
| E22 #19 Engineering Standards document | ARB · STANDARD-DOC · UNK · UNK · NP · RULE | ✎ **target home vs item.** The "item" is the standards document itself, a home that would be *created*. No item is being raised into it. This is the confusion REVIEW-TASK §4 names. I exclude it as not a standing-raising event. Effect: only undetermined pairs are removed. | SOURCE FACT |
| E23 implementation-evidence line | ARB · OI · UNK · UNK · PROMOTED · UNK | ✔ The count reconciliation holds: S4 has 4 A-rows, so the "2 added by ARB" must be #7 and this new line. If this line were #1 renamed, R-36's counts (C 4, already 4) could not both be met. A's mapping is the only one consistent with the counts. | FORMAL CONSEQUENCE |
| E24 protocol → operating standard | PA · OTHER · 2 slices · UNK · PROMOTED · UNK | ⚠ Accepted: there is no document home ("must reach the next session through the runtime path — the WP-3 work plan will carry it"). A's E01 rationale would make this STANDARD-DOC (D-9). | INFERENCE |
| E25 protocol → standards doc (not self-promoted) | session author · STANDARD-DOC · 2 slices · UNK · NP · RULE | ✔ TASK.md's actor definition ("who decides **or acts**", with "the session author / engineering" as an example) supports keeping this as an event separate from the DA's pending decision. ⚠ The rule (Phase 16) forbids promotion *without authorization*. It is an authority rule, not an evidence rule. | SOURCE FACT |
| E26 DA disposition | DA · STANDARD-DOC · 2 · UNK · UNK · UNK | ✔ The outcome is UNK. "Recommendation: (b)" is a recommendation, not a decision, and A did not confuse the two. | SOURCE FACT |
| E27 R-39 | DA · METHODOLOGY · 1 context · YES · PROMOTED · UNK | ✔ ⚠ The ground UNK is defensible, but CHOICE is a live alternative (§4). | SOURCE FACT |
| Exclusions (ES-004.3 refinement, validation, O-2, F-2, S3 vocabulary ruling, AST-008) | — | ✔ The exclusions are defensible. ⚠ The S3 vocabulary ruling is the most borderline: it is "BINDING … applies to ALL future documentation", but it names no higher-standing home. ⚠ A's hypothesis that including O-2 "stays UNDETERMINED" depends on E01 = UNK. With E01 = 1, and O-2 coded RULE, it would be deciding. O-2's ground ("below the evidence bar" cites no named rule) is better coded UNK, and then the pair is undetermined. | INFERENCE |

**Independence (check 5).**
- **OBSERVATION:** The clusters are right. S1+S2 are one decision (C1). The 21 R-36 items are one decision (C3). S6's three items share C4. S3 and S7 are separate decisions.
- **OBSERVATION:** No independent decisions are merged, and no single decision is counted as independent events.
- **OBSERVATION:** E01 and E24 come from the same session log day (2026-07-30), but they are separate PA instructions.
- **OBSERVATION:** E24 and E25 are the same item with different targets, inside one cluster. The frozen rule does not require P and Q to be independent, so the pair is formally admissible.

## 3. Recomputed model verdicts

**FORMAL CONSEQUENCE: pools under my corrected coding.**
- **P** (PROMOTED, not CHOICE): E01 (PA, STD, 1 inst) · E10 (ARB, OI, 3) · E23 (ARB, OI, UNK) · E24 (PA, OTHER, 2) · E27 (DA, METH, 1 ctx).
- **Q** (NOT-PROMOTED on a RULE ground): E02 (UNK, METH, 1 occ) · E20 (ARB, OI, 1 slice) · E25 (author, STD, 2 slices).
- **Q candidates with ground UNK** (can only give undetermined pairs): E08 (ARB, OI, 1) · E17 (ARB, OI, 1 ctx) · E21 (ARB, OI, UNK).
- E22 is excluded.

| Model | A's coding: verdict (deciding pairs) | Corrected coding: verdict (deciding pairs) |
|---|---|---|
| M_E | FALSIFIED: (E02,E27) (E20,E27) (E25,E27) (E25,E24) | **FALSIFIED**: the same four pairs, plus (E25,E01), (E02,E01), (E20,E01) |
| M_A | UNDETERMINED: none | **UNDETERMINED**: none. Open pairs: (E02, E01/E27/E23) because actor(E02) is UNK; (E20,E23), (E08,E23), (E17,E23), (E21,E10/E23). (E20,E10) is ruled out, since 1 < 3 |
| M_T | FALSIFIED: (E02,E27) | **FALSIFIED**: (E02,E27) METH 1 ≥ 1, and (E25,E01) STD 2 ≥ 1 |
| M_AT | UNDETERMINED: none | **UNDETERMINED**: none. Open pairs: (E02,E27) because of the actor; (E20,E23), (E08,E23), (E17,E23), (E21,E10/E23) |
| M_C | not established as needed | **not established as needed**: M_A and M_AT are not falsified, and not shown to survive |

- **FORMAL CONSEQUENCE:** A's deciding pairs recompute correctly.
  - (E25,E24): 2 slices ≥ 2 slices, same unit. M_E has no parameters.
  - (E02,E27): both METH; 1 ≥ 1.
  - (E20,E27) and (E25,E27): 1 ≥ 1 and 2 ≥ 1, across units.
- **FORMAL CONSEQUENCE:** The cross-unit pairs survive conversion to the coarsest unit, bounded contexts.
  - One occurrence (E02) lies in at most one context, and so does one slice (E20). Both therefore equal 1 context.
  - Two slices (E25) cover at least one context. The comparison with E27's 1 context therefore holds.
  - They do **not** survive conversion to slices. E27's single context spans "EPIC-004D..K", and its slice count is UNK. On a slice count, (E20,E27) and (E25,E27) become undetermined. (E25,E24) is unaffected.
- **FORMAL CONSEQUENCE:** A's verdict convention is disclosed and conservative: UNDETERMINED means no deciding pair, but at least one open pair. I accept it. Under the looser reading, "SURVIVES if no deciding pair", M_A and M_AT would SURVIVE and M_C would be formally not needed.
- **FORMAL CONSEQUENCE:** The difference in my M_T column comes from D-1 and D-2. A itself wrote that "under either textual reading of E01's evidence (1 … or 2 …), 2 ≥ it, so the pair would become deciding". Given that, and given that A bounded E13 from its text, the pair should have been classed deciding, not undetermined.

## 4. Alternative interpretations (per deciding pair)

| Pair (model) | Strongest alternative reading | Does the text rule it out? | Effect |
|---|---|---|---|
| (E25,E24) M_E | E25 is not a decision. The author says "one decision belongs to the DA, not to me", so the real Q is E26, whose outcome is UNK. Drop E25. | **No, but disfavoured.** TASK defines actor as "who decides **or acts**" and names "the session author / engineering". The text states the act: "I have **not** created one". | M_E is still falsified by (E02,E27), (E20,E27), (E02,E01), (E20,E01). |
| (E02,E27) M_E, M_T | E27 is not "otherwise-equal", because it carries a recorded exception ("Early promotion recorded as explicit exception"). | **Yes, formally.** The frozen rule compares only the model parameters and evidence. Exception is not a parameter. | None under the frozen rule. It is substantive for interpretation (§6). |
| (E02,E27) M_T | E27's ground is CHOICE: the DA chose early promotion on the stated principle "generalized methodology by construction", over holding. S6 frames the same kind of DA decision as options (a)/(b), citing R-39 as the precedent. | **No.** S7 lists no options, which is why UNK is defensible, but CHOICE is not excluded. | Under A's coding, M_T → UNDETERMINED. Under mine, M_T stays FALSIFIED via (E25,E01). |
| (E25,E01) M_T, M_E | ES-004.3 is "documentation standard, **not a new standard document**", the same structure as E24's "operating standard; NOT … a standards document". So E01 should be OTHER, like E24. | **No.** The text supports STD only through hosting inside ES-004-Documentation.md, which is itself inferred to be a standards document. | M_T: this pair disappears. M_T then rests on (E02,E27) alone. |
| (E02,E01), (E20,E01) M_E | E01's evidence is 2: "caught a **second** instance". | **No.** The second instance is post-adoption, but TASK does not say "at decision time". | These two pairs drop (1 < 2). (E25,E01) survives, since 2 ≥ 2. M_E is unaffected. |
| (E20,E27) M_E | E20's ground is not RULE, because ER-02 concerns replacement. And E27 in slices is more than 1. | **No.** | The pair drops. M_E is unaffected. |
| (E25,E27) M_E | Evidence counted in slices: E27's slice count is UNK (EPIC-004D..K). | **No.** The text gives only "ONE context". | The pair becomes undetermined. M_E is unaffected. |
| M_A / M_AT open pair (E02, E27) | E02's actor is the PO, and the PO is the Decision Authority. | **Not ruled out, but not supported.** The text never identifies the PO with the DA, and S3 says "Nothing commissioned". | If it held, M_AT would be FALSIFIED (METH, 1 ≥ 1), and so would M_A. That would make M_C formally needed. |
| Omitted S3 vocabulary ruling | Treat it as a PROMOTED binding rule by PO/ARB on one occurrence. With E02's actor = PO/ARB, M_A would have a pair at 1 ≥ 1. | **Largely yes.** No higher-standing home is named, and its evidence count and E02's actor are both UNK. | At most more undetermined pairs. |

- **FORMAL CONSEQUENCE:** Only two alternatives could change a verdict:
  1. E02's actor = DA. This would falsify M_A and M_AT, and M_C would then be needed.
  2. E27 = CHOICE **and** E01 ≠ STANDARD-DOC, both at once. This would move M_T to UNDETERMINED.
- **OBSERVATION:** The text supports neither.

## 5. Disagreement register

| # | Event · field | A | Reviewer | Class |
|---|---|---|---|---|
| D-1 | E01 · evidence | UNK (instances) | 1 instance (≤ 2 counting the post-adoption one; 1 slice, WP-1) | coding |
| D-2 | (E25,E01) under M_T, M_E | undetermined | deciding: every reading of E01 the text allows is ≤ 2 | formal reasoning |
| D-3 | E09, E14 · target_type | PRINCIPLES-DOC (the home routed to) | OPERATING-INSTRUCTIONS (the home expressly not promoted into). The B-home promotion is pending (UNK) | coding |
| D-4 | E22 · inclusion / target_type | event; target STANDARD-DOC | excluded: the item *is* the home (target vs item confusion) | coding |
| D-5 | E17 · ground | CHOICE | UNK (a reason is given, but no options, by A's own criterion) | coding |
| D-6 | E03–E22 · actor basis | SOURCE | the value ARB is acceptable, but it is INFERENCE: R-36 never names the adopter. A's EXECUTION.md says so itself; its JSON contradicts it | source interpretation |
| D-7 | E11 · evidence | UNK | 3 ("all three tickets") | coding |
| D-8 | O-2 hypothesis | pair "stays UNDETERMINED" (it relied on E01 = UNK) | undetermined only because O-2's ground is UNK. With E01 = 1 and a RULE ground, it would be deciding | formal reasoning |
| D-9 | E01 · target rationale | STANDARD-DOC "since the task lists 'a standard'" | STANDARD-DOC because it is hosted in ES-004-Documentation.md. A's rationale would force E24 to STANDARD-DOC too | source interpretation |
| D-10 | E03, E04, E07, E12 · quotes | the quote fields contain A's glosses (e.g. "(selection among categories …)") | the quotes should be verbatim only | wording |
| D-11 | E02 · M_A/M_AT notes | the candidate actors "differ from Decision Authority" | true only as a comparison of names. The text does not settle PO vs DA identity | source interpretation |

- **OBSERVATION:** None of these is **substantive**. None changes a verdict, and none shows A using evidence from outside SOURCES.md.
- **OBSERVATION:** A is right on:
  - all four verdicts;
  - the complete M_E pair list;
  - the R-36 count reconciliation;
  - E10 (decision over recommendation);
  - E26 (outcome UNK);
  - the UNK actor for E02;
  - the S7 scope note;
  - the clustering.

## 6. What this evidence can and cannot establish

- **FORMAL CONSEQUENCE:** Established:
  - M_E is falsified. The strongest pair is (E25,E24): the same item, the same unit, the same day, promoted to one kind of home and refused on a rule for another.
  - M_T is falsified. It fails at METH (E02 vs E27, both under ES-006.1), and, on my coding, at STD (E25 vs E01).
- **UNKNOWN:** Whether M_A or M_AT survives. Almost every actor appears on only one side of the P/Q divide: the DA and PA only in P; the session author and E02's unnamed actor only in Q. The one actor on both sides, ARB, gives (E20, E10) at 1 < 3. That is consistent with a threshold, but it does not test one. These models are **not testable** on this corpus, so they are not supported by it.
- **INFERENCE:** The text states its own mechanism, and the frozen models do not represent it. There is an evidence bar (ES-006.1: "more than one bounded context"; ES-006.1 "forbids promoting from one"). On top of it sits an authority gate (Phase 16: not "without authorization"; "a decision is an explicit act by an authority"). Only an authority can grant an exception, and it is recorded as one (R-39; S6 option (a), "recorded as an exception so the normal bar stays intact"). The exception is not a threshold that varies by actor, because R-39 insists "the multi-context bar continues to apply". So the text describes a fixed bar plus an authorized-exception channel. That is closest to M_C, but the frozen rule cannot show this: it has no parameter for exceptions.
- **OBSERVATION:** Limits on any stronger conclusion:
  - The evidence units are mixed (occurrences, instances, slices, contexts, tickets).
  - Grounds are rarely rule-based (3 RULE-grounded Q items).
  - Most R-36 items are CHOICE, so they are excluded.
  - The per-item grounds in R-36 are drawn from S4's *recommendation* text, on the reading that adopting "the review" adopts the matrix's reasons.
- **UNKNOWN:** The DA's disposition of the protocol (E26). The identity of E02's actor. E23's evidence. Whether the PO is the Decision Authority.
