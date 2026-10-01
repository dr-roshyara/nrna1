# Review of SCHEMA-r3 (with THEORY-CHOICES.md, RATIONALE.md)

Sources used: SCHEMA-r2.md, SCHEMA-r3.md, GAPS.md, THEORY-CHOICES.md, RATIONALE.md, TASK.md, HYPOTHESES.md, RECORDS.json, SOURCES.md.

## Verdict: **FAIL**

r3 is a careful revision. It closes most of the named gap cells with one answer (R15–R21, R27, R26, R29, R05, R10, R23, R02, R30, and where the R06/R07 norm goes). I found no rule written to make the data fit H-D or H-B. The verdict is FAIL because of two blocking issues:

- **B1. The R-A3 scope is not closed for negative dispositions.** The rules let a span's own text count as an act when it refuses, withdraws or reverses *any* operation, in *any* section. Positive self-performance is limited to five operations in register rows. This extends the flagged text-as-act convention by analogy, which R-A3 itself forbids. It contradicts R-A3's own "any other operation → NONE" sentence, it is mislabelled `SELF_PERFORMED`, and THEORY-CHOICES TC-1 does not disclose it.
- **B2. G3 is not closed for R01.** The span can still be coded either UNKNOWN or ACT_REFUSED, because §3 lets a PROPOSAL be identified by INFERENCE, and because it is not clear which operation X is.

Both fixes are small (see the end of this review).

---

## 1. Gap closure: rule applied to each named record

| Gap | Record | Answer r3 yields | Closed? |
|---|---|---|---|
| G1 (act) | R15, R16, R17, R18 | `act.state = NONE`, `basis = SELF_PERFORMED_OUTSIDE_RA3`, operation recommend/classify (A7). The section is not a register row under D1 ("…promotion matrix (adopted by R-36)…" is excluded by name). R-X1 forbids taking "adopted by" from the heading. Evidence-column acts are not candidates (R-A5). | **Yes** for state. The operation is a tie between two A7 candidates, *recommend* (PROMOTE) and *classify* (category letter). R-A5 gives no tie-break, and "highest step" is unclear. State is unaffected. |
| G1 (act) | R19, R20, R21 | NONE, `SELF_PERFORMED_OUTSIDE_RA3` (classify/recommend). "Stays in…" and "Remains Candidate" are continuative, never dispositions (D5). R21's "explicitly ruled" is evidence and excluded. | **Yes** (same operation tie) |
| G1 (act) | R27 "it APPLIES §12 rather than superseding it" | NONE, `SELF_PERFORMED_OUTSIDE_RA3`, operation *apply* (A7). "rather than superseding" is a contrastive, so it is never a disposition (D5). R-A3(b) fails because *apply* is not listed, and "superseding" is not a performative. | **Yes** (plus `SPLIT_REQUIRED: supersede` if the coder treats it as DISTINCT) |
| G2 (norm) | R15, R16, R17, R18 | **ELIGIBLE**, `basis = VERDICT`, `about_operation = promote`, `about_target` = the row's behaviour. Quoted rule lines ("reuse is justified…", "gaps and deferrals… never silently absorbed") and the evidence's "required" are MENTIONED (D11). In R18, "already ARB-adopted" is PERFORMATIVE_ONLY and loses to VERDICT. | **Yes** |
| G2 (norm) | R19, R20, R21 | **INELIGIBLE**, VERDICT, `about_operation = promote`. D12 names "stays in…" and "remains candidate" as negative verdicts. VERDICT > STATUS_PREDICATE. R-N3 step 3 points R19's second item (the "generalized form") to the row subject. R21's "replaced only on repeated evidence" loses on priority. | **Yes**, but see neutrality issue N2 |
| G3 (act) | R01 "The Chief declines to resolve an ambiguity…" | **Still ambiguous.** A2 applies ("declines"). Reading (i): X = resolve-in-own-favour. D4 finds no proposal in words, so the answer is **UNKNOWN**. Reading (ii): §3 allows the proposal to be an INFERENCE (premise: "an ambiguity… put to the decider"), giving **ACT_REFUSED** (resolve). Reading (iii): X = adopt, with the five PREPARED rulings as the D4 proposal ("issued PREPARED ruling"), giving **ACT_REFUSED** (adopt, R-81..R-85). R-A3 no longer competes, because the span has no listed performative. | **No** |
| G3 (act) | R26 "ADR-T14 is not superseded." | **ACT_REFUSED**, operation supersede, target ADR-T14 (A3). The span is operative text of the deciding row ("DECISION, IN THE BOARD'S OWN WORDS"). The proposal is "a supersession question" (D4, a question put to the decider), stated in words. A3 outranks A6. | **Yes** |
| G3 (act) | R29 "superseding would blur chronology" | **NONE**, `basis = NONE` (A8). This is a reason against an unchosen option, so it is not a refusal (R-A2). Grounds are not candidates (R-A5). Norm: NONE, because "would" is a modal, so it is not a prospective indicative. | **Yes** |
| G4 (act) | R05 "WP-4C-1 AUTHORIZED… ARB CHIEF (PREPARED)." | **ACT_ATTEMPTED**, `attempt_kind = PREPARED`, operation authorize, target WP-4C-1 (A4). The marker is in the span, and "ISSUED BY THE ARB CHIEF" in the section supplies the initiation. `later_disposition`: none in the section. Norm: AUTHORIZED (OPERATOR), plan + implement, `stated_by = ARB CHIEF (PREPARED)`. | **Yes** |
| G5 (norm) | R10 "…PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN" | **STATUS_CONSTRAINT**, `basis = STATUS_PREDICATE`, `about_operation = begin implementation`, target WP-4B (the referent comes from the section). "Proviso not met" is a fact (D8). Act: NONE (R-A1 / A3 without a proposal to begin). | **Yes** |
| G5 (norm) | R23 "…adopted as a permanent documentation standard…" | **STATUS_CONSTRAINT**, `basis = STATUS_PREDICATE` ("permanent… standard" beats the PERFORMATIVE_ONLY "adopted"). `about_operation = status: permanent documentation standard`. No obligation operator, so it is not REQUIRED. | **Yes** (act basis REPORTED vs SELF_PERFORMED is unclear; see nonblocking item 8) |
| G6 (norm) | R02 "Decision Authority confirms and adopts R-81 through R-85…" | Act: ACT_OCCURRED adopt (one event with "confirms", D10), A6 SELF_PERFORMED. Norm: mandatory. **STATUS_CONSTRAINT**, `basis = PERFORMATIVE_ONLY`, `about_operation = adopt`, target R-81..R-85. | **Yes** (basis: see nonblocking item 8) |
| G6 (norm) | R30 "ANNOTATE is adopted." | Act: ACT_OCCURRED adopt (A6). R-94 has no PREPARED marker in its own section, and R-A6 forbids importing R-86's standing rule. Norm: **STATUS_CONSTRAINT**, PERFORMATIVE_ONLY. | Value **yes**. `about_operation` is unclear: *adopt* (target ANNOTATE) or *annotate* (target PB-006 row), because D13 "the operation named in it" names two. |
| G6 (act) | R06 / R07 (shared span) | Two DISTINCT acts (D10 "withdrawn… because the Authority ruled…"). One record is **ACT_WITHDRAWN**, withdraw R-90, REPORTED (A1; the prior filing is identified in the section). The other is **ACT_OCCURRED**, rule/classify R-90, REPORTED (A5). | **Yes** (which record_id gets which operation follows lineage) |
| G6 (norm) | R06 / R07 | Placement is resolved: the same norm on both records, `shared_with` cross-listed, counted once. Value if present: **STATUS_CONSTRAINT** ("the register holds constitutional decisions", D8(i)). Presence is **still ambiguous**: the clause is the Authority's ground, cited inside a "because" clause. D11 makes cited grounds MENTIONED (giving NONE), but R-N1's own example (R22's ground clause "forbids promoting from one") treats a cited ground as a norm. | Placement **yes**. Presence **partly ambiguous** |

**Still ambiguous:**
- R01, act (UNKNOWN vs ACT_REFUSED; operation resolve vs adopt).
- R06/R07, norm presence (STATUS_CONSTRAINT vs NONE).
- R30, `about_operation`.
- R15–R21 and R27, `act.operation` ties (state is determinate).

## 2. Minimality

Every change carries an `[r3-Gn]` tag. Several of them reach beyond the cells that disagreed and change coding elsewhere:

1. **D7 is now a complete closed lexicon** for every deontic value (PERMITTED, AUTHORIZED, ELIGIBLE, DELEGATED, the "remains" rule). G5 needed only the REQUIRED / PROHIBITED / STATUS_CONSTRAINT boundary. Side effect: R01's "no self-issued act may adopt" matches only PERMITTED "may", because "no … may" is not listed.
2. **R-X1 limits section use for every norm and act**, not only R-A3 performatives and the G5 operator question.
3. **R-A5 codifies every act state.** This includes a new UNKNOWN rule for ACT_REVERSED (A1) and A5 for reported acts. G3 needed only REFUSED vs OCCURRED, and G4 needed only A4.
4. **D11 STATED/MENTIONED applies to all norms**, including any norm a span cites as evidence or grounds. It reaches session logs and commits, well beyond the G2 matrix rows.
5. **D8(ii) generalises R-A1's single example** to every present- or future-tense indicative about an operation.
6. **R-S1's counting clause** prescribes analysis, not coding. It is minor and tied to G6.

Items 1–5 can be defended as needed to make the procedures operational. They should be disclosed as scope extensions, because they change values outside the 36-record disagreement set.

## 3. Neutrality

**R-A3 scope.**
- The positive side is properly closed: a closed lemma list, register rows only, the performative must be in the span, no heading imports, and `basis` tags that make TC-1 computable.
- **The negative side is open (blocking, B1).**
  - D3's last bullet makes a self-performed refusal `SELF_PERFORMED`, governed by A2/A3 "not by R-A3's list". A1 has no basis or section restriction for withdrawals and reversals. Because A1–A3 come before A7, text that refuses or withdraws *any* operation, in *any* section, becomes an act.
  - R-A3's own sentence says "A span that self-performs any other operation … is coded `act.state = NONE`". The two directly conflict.
  - In this corpus: R-36's "**Expressly NOT promoted:** …" is D5(i) "expressly NOT [X]", with the matrix as the D4 proposal. That gives ACT_REFUSED *promote*. The same row's "Promoted into AST-013 … 6 behaviours" is A7 NONE. Refusals to promote are acts, promotions are not.
  - This is TC-1 alternative 1c (the extension TC-1 itself calls H-D-favouring), applied to negative dispositions only. It is undisclosed, and `SELF_PERFORMED` is defined in D3 as "R-A3 (a)–(c) hold", so the label is wrong.
  - It is computable only by combining `state` with `operation`, which TC-1 does not mention.

**"Governed act" level.**
- Literal attachment is a sound neutral principle, and it works for R15–R18 ("PROMOTE" is literally the verb).
- **N2:** for R19–R21 the literal text is a status label on the behaviour ("Remains Candidate", "Stays in ADR-MP-06"). Under D8(i) and R-N1 that is STATUS_CONSTRAINT (`status: candidate`) on the behaviour. D12 lists exactly these phrases as INELIGIBLE verdicts, and VERDICT > STATUS_PREDICATE then lifts them to the meta-act *promote*. That is the re-levelling R-N1 forbids, and RATIONALE's rejected option "always coding the meta-act" in miniature. Both values are governance standings, not substantive norms, so the H-D/H-B effect is small. But the label is lost from the coded fields, and the claimed symmetry does not hold. Not blocking.
- TC-2 is correctly left open. Its 2b computation needs a join key (the parsed "(adopted by R-36)" heading) and an unspecified inferred deontic value.

**Other neutrality notes (nonblocking):**
- **N3:** TC-4 is incomplete. R-N3's exception codes an *authorize* performative as OPERATOR, not PERFORMATIVE_ONLY. A bare "Slice 7C AUTHORIZED" is then as definitionally paired with its act as "ANNOTATE is adopted", but 4b cannot exclude it. Authorize is the most common operation in this register.
- **N4:** D8(ii) is too broad. It turns descriptive or predictive indicatives into norms: R27's "it APPLIES §12"; R-47's "no observable behaviour changes until 7C"; R-77's "Implementation continues under…". This raises counts of norms about work, which leans toward H-B's coupling. The rule is symmetric in form but is a theoretical choice and is not listed.
- **N5:** D11's "cites as evidence" and "asserts as its own" can drop every norm a session log or commit reports. That would leave norms only in governance instruments, which leans toward H-D. It is not listed.
- **N6:** the boundary between REPORTED and SELF_PERFORMED for register rows that reproduce another actor's ruling or instruction (R02 "RULING AS ISSUED, VERBATIM"; R23 "(Principal Architect instruction)") decides whether these acts survive TC-1 1b.

**THEORY-CHOICES assessment.**

| TC | Assessment |
|---|---|
| TC-1 | Correct to list and to treat as inherited. **Incomplete:** it omits self-performed negative dispositions (B1). Its 1a and 1c descriptions are therefore inaccurate. |
| TC-2 | Correctly a theory choice. 2b's computation is underspecified (no link field, deontic value open). |
| TC-3 | Appropriate. r3 codes no force, so this is an analysis choice. Listing it is harmless and useful. |
| TC-4 | Appropriate. Incomplete for bare authorize performatives (N3). |
| Missing | Self-performed refusals/withdrawals as acts (should be in TC-1). D8(ii) prospective indicatives as norms. D11's treatment of reported or cited norms. ("A standard is never REQUIRED" is a lexical choice, recoverable from `status: <label>`, and acceptably decided.) |
| Over-listed | None. No listed choice could have been decided neutrally in the schema. |

## 4. Consistency

1. **R-A3 bullet ("any other operation → NONE") vs D3 last bullet and A1–A3** (self-performed refusals and withdrawals of any operation are acts). This is B1.
2. **D3 is circular and self-contradicting.** R-A3(c) requires SELF_PERFORMED (D3). D3 defines SELF_PERFORMED as "R-A3 (a)–(c) hold", then also assigns it to refusals that fail (a) and (b).
3. **D11 vs R-N1 example vs D8(i).** D11 says cited grounds are MENTIONED. R-N1 codes R22's ground clause "forbids promoting from one". D8(i) names "the register holds X", which in R06/R07 is a ground clause.
4. **D12 vs R-N1 and D8(i).** "Remains candidate" and "stays in…" are both status labels and verdicts. Only the priority order decides, and the result re-levels (N2).
5. **R-A1 (retained) vs A3 / R-A2.** R-A1 says "not an ACT_REFUSED unless an *attempt* is recorded". A3 accepts a *proposal*.
6. **D4 vs D6.** A PREPARED ruling is a proposal (D4), and "a proposal is not ATTEMPTED" (D6), yet R05 is ATTEMPTED. This is consistent only if the proposal (to adopt) and the attempt (to authorize) are different operations. The schema should say so.
7. **The R-A5 merge rule interacts with R-A3's list.** "A deciding act whose content is X is coded as X." The R-36 "Promotion Review ADOPTED", whose content is promotion, can then be coded as *promote*, which gives A7 NONE instead of ACT_OCCURRED adopt. Merging can move an act on or off the closed list.
8. **Priority order:**
   - ACT: A1–A8 is a complete first-match list for state. Operation selection has no tie-break among candidates at the same step, and "highest step" is unclear.
   - NORM: priority applies only *between* classes on the *same* target. Two OPERATOR clauses on one target fall to step 7 UNKNOWN: R12 "MAY BE PLANNED AND MUST NOT BE STARTED"; R34 "AUTHORIZED; engineering may begin RED". So does an unclear target split. Those spans plainly decide, so this contradicts R-A4's meaning of UNKNOWN, and it removes exactly the norm-on-work records from support counts. **The NORM priority order is not complete.**

## 5. Codability

A fresh coder can apply most of r3 from §1–§5 without reading the rationale: the closed lexicons, the D1 heading test, the first-match tables and R-S1 are all mechanical. The obstacles are:
- the circular D3 / R-A3(c);
- inferred proposals (§3);
- same-class norm ties and same-step act ties;
- the D11 / R-N1 contradiction;
- multiple operations in D13 (R30; "PLAN AND IMPLEMENT");
- D1 depends on how the dataset formats section headings, not on the source text.

## Blocking issues

- **B1.** Self-performed refusals, withdrawals and reversals of any operation, in any section, count as acts (D3 last bullet; A1–A3 before A7). This contradicts R-A3's closed scope and its "any other operation → NONE" sentence. It is labelled with R-A3's own `SELF_PERFORMED` value and is not disclosed in TC-1. It builds the flagged text-as-act convention into the default observations for negative dispositions only (R-36: non-promotions are acts, promotions are not).
  - *Fix:* give self-performed negative dispositions their own basis value (e.g. `SELF_PERFORMED_DISPOSITION`), and either apply the R-A3 scope test to them or code them NONE by default. Add them to TC-1.
- **B2.** G3 is not closed on R01. §3 lets a PROPOSAL be an INFERENCE, which reopens coder judgement inside R-A2 and A2/A3. The merge rule also leaves X undecided (resolve vs adopt).
  - *Fix:* require the proposal to be stated in words (SOURCE_FACT), and code UNKNOWN otherwise. Fix X as the operation that the refusal verb's complement names.

## Nonblocking (should fix)

1. Add a NORM tie rule for same-class clauses and multi-target spans (R12, R34), or record a secondary clause. Do not send such spans to UNKNOWN.
2. Resolve the D11 / R-N1 contradiction. State whether a norm a span cites as a ground or reports from another actor is STATED (this decides R06/R07 and R22).
3. Restrict D12's negative-verdict list, or let status labels in a review keep `status: <label>` (N2).
4. Tag bare *authorize* performatives so that TC-4 4b can exclude them (N3).
5. Narrow D8(ii) to statements that assign a standing, and list the broader reading as a theory choice (N4).
6. Fix the D3 circularity. Add a tie-break to R-A5 operation selection and define "highest step".
7. Clarify the D4/D6 split (proposal to adopt vs attempt at the instrument's operation). Reconcile R-A1's "attempt" with R-A2/A3's "proposal or attempt".
8. Set the REPORTED vs SELF_PERFORMED test for register rows that reproduce another actor's ruling or instruction (R02, R23).
9. Make D7's negation handling general ("no … may", "not … may") so that R01's norm is not PERMITTED by literal match.
10. Disclose the non-minimal extensions (D7, R-X1, A1/A5, D11, D8(ii)) as scope extensions. Give D13 a rule for spans that name two operations (R30, R05).
