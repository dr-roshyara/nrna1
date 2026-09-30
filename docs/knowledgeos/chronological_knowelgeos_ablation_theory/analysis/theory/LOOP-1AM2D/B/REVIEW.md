# Independent review of Researcher A's answer

Files used: `REVIEW-TASK.md`, `TASK.md`, `SOURCES.md`, `ANSWER.json`, `ANSWER.md`. Nothing else.

## Summary

- **Verdicts: I agree with all of A's verdicts.** M_A, M_T, M_K and M_AT are all NOT FALSIFIED under A's coding and under mine. The confound is **not broken** under either coding.
- **Permitted evidence:** A kept to the permitted files. A was right to report that the quotes given for E02 and E20 do not appear in `SOURCES.md`. A was also right to use those events as given, since `TASK.md` §3 says to check them but not re-code them.
- **Quotes:** every quote that A attributes to `SOURCES.md` is verbatim, apart from dropped bold markup and marked ellipses. A also correctly found that the E01 quote is stitched together in reverse order. Three quotes are verbatim but do not support the value they are attached to, all on `raise_kind` (R38-1, R90-1, R90-2). Of these, only R90-2 changes the coded value: it should be UNK.
- **Category confusions:** there are three.
  - R40-A4 is coded NOT-RAISED, but it is a **deferred candidate** ("descriptive until explicitly adopted — do not adopt; submit for review"). It should not count as a non-raise.
  - R40-A1 is coded EXTENSION on the strength of a **"Parsimony note (DA guidance …)"** about an "expected end-state". That is a recommendation about a candidate, not a decision.
  - R38-2 codes an **"ARB preference"** as a RAISED decision.

  None of the three changes a verdict, because every event involved has evidence UNK.
- **Missed event (the most important finding):** R-41 explicitly does *not* raise the rule as a new standard: "hosted ONCE as ES-004.3 (documentation standard, **not a new standard document**)", justified by "Parsimony honored". Coded on its own, this is a PA · NEW · 1 instance · NOT-RAISED event.
  - The text does not establish its ground. Parsimony is cited by name only, and elsewhere in the register it is called an "ARB preference" and "DA guidance". So I code the ground UNK, and the event is not a Q.
  - If it were read as a RULE ground, it would pair with E01 (same actor PA, 1 ≥ 1). That would **falsify M_A alone** and break the confound.
  - This is the strongest alternative reading, and the text does not rule it out.
- **Formal reasoning:** A's model logic is sound. In particular, "M_AT not falsified follows from M_A not falsified" is valid. Two small points:
  - A's extra claim that a model with no parameter is falsified depends on E01's *given* evidence of 1. A itself calls R-41's evidence ambiguous (1 or 2), so the claim should be stated as conditional on that.
  - Ambiguity 3 in `ANSWER.md` is garbled ("Either way it is ≤ 1 only under the first reading").

## Per-event findings

| Event | A's coding | Finding | My coding (changes only) |
|---|---|---|---|
| R38-1 | ARB · NEW · UNK evidence · exception NO · NOT-RAISED · ground UNK | The quotes are verbatim. The NEW quote ("This ruling introduces NO new governance") is a negation. It shows that the rejected home would be *new* governance, so it supports NEW only indirectly. A was right to leave the ground UNK: "Option B" is a choice, and parsimony is elsewhere called a "preference". | Agree. The quote is weak but acceptable. |
| R38-2 | ARB · UNK kind · RAISED · CHOICE | This is a **decision vs recommendation** problem. The text says "Precedent going forward (**ARB preference**, Option A)", and the same correction also says "This ruling introduces NO new governance". The text does not present the preference as gaining standing. | Not a standing-raising decision. Excluded. If it were kept as A codes it, it would still decide nothing (evidence UNK). |
| R40-A1 | DA · EXTENSION · ES-005 (candidate ES-005.5) · RAISED · UNK | The actor is named, so it is not inferred from the register's host. The EXTENSION value rests on "*Parsimony note (DA guidance + E-4 precedent)*: the placement rule's **expected** end-state is a clarification hosted in ES-005 (**candidate** ES-005.5)". That is guidance about a candidate, not an adopted clause. What A1 actually approves is that "the platform shall introduce a governed placement rule". | raise_kind **UNK**. The target is "a governed placement rule", with ES-005.5 as a candidate only. The outcome stays RAISED (approved), and evidence stays UNK. |
| R40-A1b (missed) | — | "…not a new standard" is an explicit non-raise into a new standard. It is "DA guidance" about an expected end-state. | DA · NEW · "a new standard" · UNK evidence · NOT-RAISED · ground UNK (guidance). Decides nothing. |
| R40-A2 | DA · NEW · RAISED | The quotes are verbatim. It is arguable whether a "knowledge type" counts as a higher-standing home. The "no folder is created" clause is a deferral, not a non-raise, and A correctly did not code it as one. | Agree. |
| R40-A3 | not coded | "A3 DEFERRED" concerns file moves. It is deferred, not a non-raise, and it is not a standing event. A was right to leave it out, but did not say why. | Not an event. |
| R40-A4 | DA · NEW · NOT-RAISED · UNK | This confuses **not-raised with deferred** and **a candidate with an adopted clause**. "descriptive until explicitly adopted — do not adopt; submit for review" puts adoption off until review. It does not refuse to raise the item. | Deferred candidate, excluded. It would never have been a Q anyway (ground not RULE). |
| R41-1 (=E01) | PA · EXTENSION · ES-004.3 · 1 instance · exception UNK · RAISED · UNK | Actor, kind, target and outcome are well quoted. **Evidence:** the "second instance" sits inside the "Provenance:" sentence. A's reading that it came after adoption is an INFERENCE from "first checklist execution the same day". A labels it as such, which is fair. **Exception:** "Parsimony honored" is the text applying a normal rule, so NO is at least as defensible as UNK. | Evidence: 1 instance (certain ≥ 1; 2 is possible). Exception: **NO**. Either way it is not YES, so the event stays in the test. |
| R41-2 (missed) | — | "(documentation standard, **not a new standard document**)" together with "Parsimony honored" is an explicit non-raise into a new standard. It has the same actor and the same evidence base as R41-1. | PA · NEW · "a new standard document" · 1 instance · exception NO · NOT-RAISED · ground **UNK**. No rule ID is cited, and parsimony is elsewhere a "preference" or "guidance". |
| R90-1 | ARB CHIEF · NEW · methodology · 1 work package · NO · NOT-RAISED · RULE | The quotes are verbatim. **Decision vs recommendation:** the row is "PREPARED, NOT ADOPTED" and was "WITHDRAWN BEFORE ADOPTION", so the ARB CHIEF's non-promotion is a proposal, not an adopted decision. The factual outcome (nothing was promoted) still holds, and the ground cites an existing rule ("ES-006.1 stands"). The NEW quote ("NOT a new governance principle") is a negation, like R38-1. The evidence count of 1 comes from context ("THIS commission"). That is well supported, but it is still read from context. | Kept as a Q, with a caveat. The actor, "ARB CHIEF", is the proposer, not the decider. |
| R90-2 | DA · NEW · register · UNK · NO · NOT-RAISED · RULE | The quote attached to raise_kind ("this ruling was put forward") does not establish NEW. Everything else is supported. | raise_kind **UNK**. |

**R-41 compared with E01.** I agree with A's points 2 to 4 (evidence value, stitched quote, the fields that match). On `recorded_exception`, E01 has "none recorded", A has UNK, and I have NO. All three are not-YES, so the difference has no effect. E01's evidence of 1 instance is kept as given, and is not re-coded.

## Recomputed verdicts (my coding, including E01, E02, E20 as given)

- **P (RAISED, not excluded):**
  - E01: PA · EXT · ES-004 · 1 instance
  - R40-A1: DA · UNK · evidence UNK
  - R40-A2: DA · NEW · evidence UNK
- **Q (NOT-RAISED, RULE ground, exception not YES):**
  - E02: UNK · NEW · methodology · 1 occurrence
  - E20: ARB · NEW · operating instructions · 1 slice
  - R90-1: ARB CHIEF · NEW · methodology · 1 work package
  - R90-2: DA · UNK · register · evidence UNK

| Model | Verdict | Reason |
|---|---|---|
| M_A | NOT FALSIFIED | E01's actor (PA) matches no Q's actor, and E01 vs E02 is blocked because E02's actor is UNK. The DA pairs (R40-A1 and R40-A2 vs R90-2) have evidence UNK. |
| M_T | NOT FALSIFIED | E01 vs E02 and E01 vs R90-1 depend on whether "documentation standard (ES clause)" and "methodology" are the same class. That is not established, so these pairs are UNDETERMINED. |
| M_K | NOT FALSIFIED | There is no EXTENSION Q. The only NEW P (R40-A2) has evidence UNK. |
| M_AT | NOT FALSIFIED | Any pair that falsifies M_AT would also falsify M_A, and M_A is not falsified. |

**Confound broken: none.**

## Alternatives that would change a verdict

1. **R41-2 ground read as RULE** ("Parsimony honored" treated as a governing rule). The pair E01 (P) vs R41-2 (Q) has the same actor (PA) and evidence 1 ≥ 1, which **falsifies M_A**. The kinds differ (EXT vs NEW) and so do the strict targets (an existing ES clause vs a new standard document), so M_K, M_T and M_AT survive. That would be a real break of the confound. The result holds even if R-41's evidence is 2, because both events share the same evidence base.
   - *Does the text rule it out?* No. It weighs against it: parsimony is called an "ARB preference" in R-38 and "DA guidance" in R-40, and no rule ID is cited.
   - A second objection is that R41-2 may simply be the other face of R41-1's raise_kind rather than a separate event, which would mean double-coding. Neither point settles the question.
   - Verdict impact: PLAUSIBLE, not established.
2. **Methodology treated as the same target class as a standard** (R-90 answers "not promoted to methodology" with "one work package is not a standard"). E01 vs E02 and E01 vs R90-1 would then decide the question and **falsify M_T**. A identified this correctly.
   - The text does not rule it out. Against it: `TASK.md` §1 lists "a standard, a methodology, canon, an ES clause" as separate homes, and E01's home is a clause *hosted in an existing* standard ("not a new standard document").
3. **"ARB CHIEF" treated as the same actor as "ARB".** No verdict changes, because the only ARB P (R38-2, which I exclude) has evidence UNK.
4. **R-41's evidence read as 2 instances.** This removes the certainty from every E01 pair against a Q at 1. No model verdict changes. A's pooled "no parameter" falsification would fail.

## Disagreement register

| # | Item | A | Reviewer | Class | Verdict impact |
|---|---|---|---|---|---|
| D1 | Missed event R41-2 ("not a new standard document") | not coded | NOT-RAISED, PA, NEW, 1 instance, ground UNK | coding | none under my coding; falsifies M_A if ground = RULE |
| D2 | R40-A4 outcome | NOT-RAISED | deferred candidate, excluded | source interpretation | none |
| D3 | R40-A1 raise_kind | EXTENSION ("expected end-state") | UNK (DA guidance about a candidate) | source interpretation | none |
| D4 | R38-2 status | RAISED (CHOICE) | preference, not a standing decision; excluded | source interpretation | none |
| D5 | R90-1 standing | decided non-raise by the ARB CHIEF | proposal in a withdrawn row; kept as Q with a caveat | source interpretation | none |
| D6 | R41-1 recorded_exception | UNK | NO ("Parsimony honored") | coding | none (not YES either way) |
| D7 | R90-2 raise_kind | NEW ("this ruling was put forward") | UNK: the quote does not support NEW | coding | none |
| D8 | Missed event R40-A1b ("not a new standard") | folded into A1 | separate NOT-RAISED, ground UNK | coding | none |
| D9 | R-41 evidence timing | 1 (after-adoption inference) | 1 as given; 2 not excluded, because the second instance sits under "Provenance" | evidence scope | none on the models; affects A's pooled claim |
| D10 | Pooled "no parameter" falsification | stated as FALSIFIED | holds only if E01's given evidence of 1 is correct; not certain from R-41's text | formal reasoning | outside the frozen test |
| D11 | ANSWER.md ambiguity 3 | "Either way it is ≤ 1 only under the first reading" | garbled; the intended meaning is "=1 only under the first reading" | wording | none |

## What can and cannot be established

**Can be established:**
- None of the four models is falsified by any pair that is certain under the frozen rule.
- The confound (actor × kind × target) is not broken by the coded events.
- The E02 and E20 quotes cannot be verified from `SOURCES.md`.
- R-41's rule was hosted as a clause (EXTENSION) and explicitly not as a new standard document.
- R-90's non-promotion sits in a row that was withdrawn and never adopted.

**Cannot be established:**
- Whether "Parsimony honored" is a RULE ground. This single point decides whether M_A is falsified and whether the confound is broken.
- Whether methodology and ES clauses belong to the same target class.
- The evidence counts for every R-38, R-40 and R90-2 event.
- E02's actor.
- Whether ES-006.1 covers clause extensions (R-41 raised from 1 or 2 instances without recording an exception).
- The given events' quotes.
