# R7 · E3 prerequisites: VP10, VP14, VP12, VP11 (Human ARB decision package, r4.1)

**Status:** decision-support artifact, 2026-10-11, after E2b (F-LOG-0261).

| review | verdict | applied in |
|---|---|---|
| r0 (F1–F15) | FAIL | r1 |
| r1 (G1–G14) | FAIL | r2 |
| r2 (H1–H16) | FAIL | r3 |
| r3 (I1–I12) | PASS_WITH_LIMITATIONS, nothing blocking | r4 (targeted fixes) |
| r4 (J1–J3) | PASS_WITH_LIMITATIONS, nothing blocking; all I-fixes verified in the body; no half-edits | r4.1 (three minor wording fixes) |

All findings are mapped in §8. **r4.1 is reviewed (fifth fresh review, of r4: PASS_WITH_LIMITATIONS); the r4.1 wording fixes J1–J3 were applied by the main analyst and are not separately re-reviewed. It decides nothing.**

It sets out the four variation points that F-LOG-0261 names as E3 prerequisites: **revisit VP10** (F-LOG-0258), **reopen VP14** (F-LOG-0259), and **decide VP12** and **VP11**. At the commission's request it gives one recommendation per VP, kept separate from the evidence. **For VP14 the recommendation is that, after even discounts, the neutral criteria give no ordering of the four cuts without weights, and the choice is the human's.** **Each VP is a separate act. No two are to be decided as one act.** Nothing here amends the adopted model or authorizes:
- a T3 re-derivation;
- the preparation of an O3 proposal;
- E3, E3b, E4 or E5.

**Inputs** (T = `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/`):
- `docs/knowledgeos/chronological_knowelgeos_ablation_theory/F-GOVERNANCE-LOG.md`, F-LOG-0253 to F-LOG-0261;
- `T/R7/VP-DECISION-REGISTER.md` r1.10 (**authoritative for every quote marked "register"**);
- `T/R7/E2b-ADOPTION-TEXT.md` r1.1 (the adopted content; §2d exclusions; §2f defaults; §6 reopen rows; D1–D4);
- `T/R7/T3-CANDIDATE-MODEL.md` r1.5 (§1a P-a to P-d, CB1–CB4; §1b CG1–CG6; the participant; §1e; §2 traces; §3a, §3b; §4 rows; §6);
- `T/R7/VP5-VP10-VP14-DECISION-PACKAGE.md` r1.1 ("the prior package");
- `T/R7/ARB-DECISION-PACKAGE.md` r4.3 (§C, §C1, §E3, §F);
- `T/R7/T2-RULE-CANDIDATES.md` r1.4 and `T/R7/T1-CONCEPT-CANDIDATES.md` r1.6, for ids.

**Label drift in the inputs (noted only; those files are not edited):**

| file | title or status label | actual revision |
|---|---|---|
| E2b text | title "(draft r1)"; status line still reads "Not yet independently reviewed" | r1.1; P6 MET (its §1, §8; F-LOG-0260) |
| prior package | title "(draft r1)" | r1.1 |
| register | status line reads "Draft r1.4" | r1.10 (title) |
| ARB package | inputs cite register r1.4 and T3 r1.4 | title r4.3 |

**Current state (F-LOG-0261):**
- **Decided:** VP1d-H (i), VP15 (i), VP5 (i), and VP14 "no boundary layer".
- **Recorded:** VP10 (iii), an open slot.
- **Adopted (E2b):** 25 elements: the participant, CG1–CG6, CV1–CV9, CV-text, CE1–CE6, CS1 and MW1.
  - There is no CB layer, and CB4 is an explicit open slot.
  - "adopting CV8, CV9 and CS1 does not decide VP10".
  - "all other variation points stay open with the defaults of `R7/E2b-ADOPTION-TEXT.md` §2f".
- **E3:** not authorized.

**Standing qualifier (applies to every row):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 0. Dependency note and decision order

**Every incoming edge to the four VPs (register §2 graph and §1 "after" cells):**

| edge | status |
|---|---|
| VP15 → VP14 | VP15 decided (i), F-LOG-0255 |
| VP5 → VP14 | VP5 decided (i), F-LOG-0257 |
| VP10 → VP14 | VP10 (step 1) |
| VP14 → VP12 | VP14 (step 2) |
| VP4 → VP12 | VP4 open, at its E2b §2f default (§6 item 6) |
| VP15 → VP12 | the VP12 row's "after: VP15 (Def 1.2)"; this is not drawn in the graph. VP15 is decided |
| VP14 → VP11, VP12 → VP11, VP10 → VP11 | steps 1–3 |
| VP8 → VP11 | VP8 open, at its E2b §2f default (§6 item 6) |
| VP4 → VP11 | the VP11 row's "after: … VP4" (not drawn in the graph); VP4 open, at its E2b §2f default (§6 item 6) |
| VP1b ⇢ VP11 ("interacts", dotted) | VP1b open (an inactive slot). VP11 is decided without reference to it (§4) |

| step | item | why at this position | source |
|---|---|---|---|
| 1 | **VP10** (revisit) | independent (no incoming edge); fixes VP14's boundary set and constrains VP11 | register §1b, §2; F-LOG-0258 |
| 2 | **VP14** (reopen) | fixes the sides that VP12 (ii) and VP11 refer to; must not reference VP1a–d (N1) | register §1c; F-LOG-0259 |
| 2a | **T3 re-derivation**, only if VP14 = P-b, P-c or P-d | the element tables exist only for P-a's layout (§2.9); its own authorization is needed (F-LOG-0259 C14). **Because it comes before VP12,** it is performed with the participant provisionally inside CG1, as T3 §1b does, and re-checked after VP12 | prior package §3.5; ARB §C1 |
| 3 | **VP12** | after VP14, VP4 and VP15; constrains VP11 (CG1) | register §1c |
| 4 | **VP11** | last | register §1c, §2 |

**After all four**, the register's O3 minimal set ("**VP15, VP5, VP14, VP10, VP12, VP11**") is met **only if** every one of these holds:
- VP12 = (i) or (ii). VP11 = (i) or (ii). For both, register §4 says "**Not open for O3**".
- VP14 = P-a, P-b, P-c or P-d. "No boundary layer" leaves "nothing to decompose" (F-LOG-0259), and "leave open" blocks.
- VP10 = (i) or (ii). Or VP10 is re-affirmed as (iii), **and** the separate new decision item of §1.8 is taken. The register is then amended by its own act, and ARB §E3 too if the human reads it as requiring a decision (§6 item 2; contested).
- Any authorized T3 re-derivation is completed and reviewed.
- Any consequential E2b amendment is made (§6 item 9).

**If the set is met, three steps remain, each by its own act:**
1. **Prepare the O3 proposal.** Register §3: "**Preparing such a proposal needs its own human act; architecture and R8 are not authorized**". This package does not give that act and does not imply it.
2. **Review the proposal independently.**
3. **E3 itself** (ARB §E3).

F-LOG-0261 lists "prepare an E3 proposal and have it reviewed" without the preparation act (§6 item 8).

---

## 1. VP10: evidential-limitation side (revisit)

### 1.1 Question and options (register §1b, verbatim)

| field | register text |
|---|---|
| question | "Evidential-limitation side (OQ7; R74)" |
| options | "(i) include CB4 (CV8, CV9, CS1); (ii) drop it; (iii) leave open as a slot" |
| categories | "A, C, E" |
| depends on / constrains | "independent. **Constrains:** VP14 (the boundary set), VP11" |
| consequences | "(i) carries epistemic information in the model, which is a step beyond R4 (X19, §3b); CB4 has no T-A row. (ii) removes the slot; its limitations stay in the qualifier only. (iii) open slot, as now" |
| assumptions | "(i): an epistemic-status representation; (ii) and (iii): none" |
| reversibility | "high" |
| required authority | "**human design decision**" |
| needed for | "O3 (whether the proposal includes CB4)" |

### 1.2 Exact relation to the record

| fact | source |
|---|---|
| VP10 = (iii) was **recorded** as "left open as a slot … This value must be revisited before any O3 proposal" | F-LOG-0258 |
| E2b adopted CV8, CV9 and CS1 **ungrouped**: "adopting CV8, CV9 and CS1 does not decide VP10, which stays recorded as (iii)" | F-LOG-0261; E2b §2a, §5 |
| the CB4 slot has "no element, no constraint and no boundary role" | E2b §2 |
| the "removed" reading of (ii) is an **unratified analyst item** (E2b §6, below). The F-LOG-0261 summary restates it without that label (§6 item 11) | E2b §6; F-LOG-0261 |

**E2b §6 rows, verbatim, cell by cell:**

| condition (E2b §6) | reopens (E2b §6) |
|---|---|
| "**any O3 / E3 proposal**" | "**VP14 must be reopened first** (no boundary to decompose into; F-LOG-0259) and **VP10 must be revisited** (F-LOG-0258); VP11, VP12 must be decided (register §3)" |
| "VP10 is later revisited as **(ii) drop CB4**" | "CV8, CV9 and CS1 are removed from the model (VP14 package §2.3, analyst reading [E]); **E2b is amended accordingly**" |
| "VP decided by implication or bundled" | "that VP; every act relying on it, including E2b" |
| "an adopted element found to encode H-D or H-B" | "VP14; this adopting act (N1)" |

**The adoption did not settle VP10.** The three elements are in the model because the VP14 act left "the CG, CV, CE, CS and MW elements and their traces … unaffected" (F-LOG-0259). F-LOG-0261 states that adopting them does not decide VP10. A human who reads it otherwise can use the E2b §6 row "VP decided by implication or bundled".

### 1.3 Evidence, and what is not established

| evidence-supported (ids, T3 §2) | unresolved, or an assumption |
|---|---|
| CB4 carries no T-A row (T3 §1a). **CS1** carries T-D rows only (R66–R69, R82). **CV8** carries T-D R70 and the excluded T-C row R62. **CV9** carries T-D R74 and R82, and R23 (T-A meta, via MW1) | whether the model should carry evidential-limitation information (OQ7; R74 is T-D: "design decision outside R4") |
| K5: two independent limitations (D16, F17) | whether an independent norm-state source exists (H14: MAY_NOT either way; R62 excluded) |
| "no: a design question; no route bears on it" (register §5a) | **What the register's step beyond R4 for (i) means ("X19, §3b") is a question for the human, alongside F-LOG-0261 (§6 item 1).** Two readings: (a) **[E]** the step is the model **gaining an evidential-limitation side**, so CV9's adoption does not engage it; (b) CV9's adoption **may already engage** the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists among the excluded extensions. T3 §3a gives X19's would-be hosts as "CB4, CV9". X19's status under (i) is part of the open question of §6 item 1; under (ii) and (iii) X19 stays excluded |
| — | whether a re-affirmed (iii) meets O3 (§1.7; §6 item 2) |

### 1.4 Consequences, compatibility with the adopted model, and implications

| option | change to the adopted model | E2b amendment? | O3 / E3 | R8 | implementation (E5, not authorized) |
|---|---|---|---|---|---|
| (i) include CB4 | CB4 becomes a side with no T-A row, holding CV8, CV9 and CS1; an extension beyond R4 that VP15 does not cover | **yes**: CB4 changes from slot to element, and the T3 §3b row (excluded under E2b §2d) is admitted. E2b §6 row "any O3 / E3 proposal" | an evidential-limitation side. **[E]** It excludes VP14 = "no boundary layer", since a side presupposes a layer (E2b's CB4 *slot* does not). It conflicts with F-LOG-0259 "carried without CB1–CB4". **The amendment must wait for VP14** (§6 item 9) | a CB4 label needs [E] justification | no instrument asserted (CS1 unrealized); R62 excluded |
| (ii) drop it | CV8, CV9 and CS1 are **removed** (analyst reading [E], ratified only by the act), leaving 22 elements. **Rows that lose their element host (D-type gaps):** T-D R66–R69, R82 (CS1), R70 (CV8), R74 (CV9); T-C R62 (CV8); X19 (CB4, CV9) and X21 (CV8, CS1). R23 stays on MW1. **The T1 concepts C25 (CV8), C26 (CS1) and C28 (CV9) lose their placement**, which breaks T3 §6 "Every C1–C28 placed". They stay carried as recorded rows (E2b §2d, §2e), as D1 and D2 were. The register says "qualifier only", while E2b and the act also keep §B (§6 item 10) | **yes**: E2b §6 row "VP10 … (ii)" | no evidential-limitation side; VP14's options unconstrained | three labels fewer | — |
| (iii) re-affirmed | none | no | CB4 stays an explicit open slot; VP14 unconstrained. **Admissibility for O3 is contested** (§1.7) | none | none |

### 1.5 Reversibility and reopen conditions

**Reversibility:** "high" (register); "additive either way" (register §5a).

**Reopen conditions:**
- E2b §6: "**any O3 / E3 proposal**"; "VP10 is later revisited as **(ii) drop CB4**"; "VP decided by implication or bundled". ARB §F has the variant "a VP is found to have been **decided by implication or bundled** with another act".
- ARB §F: a defect in an independent review of this package.

### 1.6 Required evidence or re-derivation

- **Evidence:** none; no route bears on VP10 (register §5a).
- **Re-derivation under (i):** CB4 changes status in T3 (a CB4 row already exists, §1a, §2). It also needs placing under the VP14 cut (§2.9).
- **Re-derivation under (ii):**
  - a D-type disclosure in the E2b amendment, listing the rows that lose their host (§1.4);
  - a T3 §2 / §3a sync for the CB4, CV8, CV9 and CS1 rows and the X19 / X21 host cells, by its own authorized amendment;
  - a disclosure that C25, C26 and C28 are unplaced (the T3 §6 "Every C1–C28 placed" row);
  - ratification in the act that CV8, CV9 and CS1 are "removed from the model, not merely ungrouped".
- **Under (iii):** none.

### 1.7 What the record says about O3, then the recommendation (kept separate; not conditioned on VP14)

**The record on whether (iii) meets O3 (evidence):**

| source | text |
|---|---|
| register §3, O2 vs O3 rows | O2 needs only "an explicit "left open" (or decided) record for **VP10**". For O3, VP10 is among the "minimal VPs to decide": "**VP15, VP5, VP14, VP10, VP12, VP11**" |
| register §5a, VP10 row | "**O2: an explicit "left open" record is required** (edge VP10 → VP14; r1.2). **O3: yes**" |
| ARB §C, row 4 | "**O2: the explicit "left open" record** (edge VP10 → VP14); O3: decision" |
| ARB §E3, prerequisites (**ambiguous**) | "E2 for VP10, VP12, VP11 (beyond E2b's set)". ARB §E2 lets the human fill in "the option from the register (§1) or "leave open"", so an E2 act re-affirming (iii) is itself an E2 decision |
| register §5a, reading note | "whichever option the human picks, including "leave open", is the human's" |
| prior package §2.3, (iii) row | "**O3 needs a decision** (register §3), so (iii) must be revisited at the O3 gate" |
| E2b §6, "any O3 / E3 proposal" | "**VP10 must be revisited** (F-LOG-0258); VP11, VP12 must be decided (register §3)". Here VP10 is to be "revisited" while VP11 and VP12 are to be "decided" |
| F-LOG-0261, E3 prerequisites | "**revisit VP10** (F-LOG-0258)" … "decide **VP12** and **VP11**", the same revisit-vs-decide contrast |

**Evidence that counts against (iii):** register §3; register §5a ("O3: yes"); ARB §C row 4; prior package §2.3.

**Ambiguous:** ARB §E3. Under ARB §E2, an act that re-affirms (iii) is itself an E2 decision.

**Evidence that may count for it:**
- (iii) is a register option;
- register §5a's reading note: "whichever option the human picks, including "leave open", is the human's";
- the revisit-vs-decide contrast in E2b §6 and in F-LOG-0261.

F-LOG-0258's "revisited" is silence on the question, not positive evidence. Register §4 is scoped to "O1 and O2" and does not bear on it.

**(iii)'s admissibility for O3 is contested.** On register §3 and §5a ("O3: yes"), O3 most plausibly needs (i) or (ii).

**Recommendation [analyst; separate from the evidence above]:** decide (i) or (ii) rather than re-affirm (iii); (i) vs (ii) is not ranked; the human chooses.
- **(i):** keeps all 25 adopted elements and adds a side with no T-A row. It is an extension beyond R4 that VP15 does not cover. On the [E] reading it excludes VP14 = "no boundary layer", and its E2b amendment waits for VP14.
- **(ii):** avoids the extension and leaves VP14 unconstrained. It removes three adopted elements, leaves the D-type gaps of §1.4, and rests on a reading that the act must ratify.

**Disclosure:** r1 recommended (iii), and that recommendation had a status-quo tilt. Its rationale that VP14 stays fully open [E] is equally true of (ii).

### 1.8 Draft Human ARB acts (one per option; choose one)

> **VP10 = (i):** "I, Human ARB, decide VP10 = (i): CB4 (CV8, CV9, CS1) is included as the evidential-limitation side of the model, as a human design decision and an extension beyond R4 (T3 §3b), carrying no T-A row. It asserts nothing about the domain; T-C R62 stays excluded and CS1 stays an unrealized slot; this act does not settle X19's status relative to CV9 and CB4, which remains the open question of `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` §6 item 1. I record the analyst reading [E] that this excludes VP14 = 'no boundary layer' (a side presupposes a boundary layer; E2b's CB4 slot does not), and that it conflicts with the F-LOG-0259 wording 'carried without CB1–CB4'. This revisits F-LOG-0258. It decides only VP10 (VP14, VP12 and VP11 are decided separately). Its effect on the adopted model takes hold only when the consequential E2b amendment is made by its own act, which cannot be made consistently before VP14 is decided. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP10 = (ii):** "I, Human ARB, decide VP10 = (ii): CB4 is dropped, and CV8, CV9 and CS1 are removed from the model, not merely ungrouped; I ratify this reading (E2b §6). The rows that thereby lose their element host (T-D R66–R69, R70, R74, R82; T-C R62; X19, X21) stay carried as recorded rows, and the T1 concepts C25, C26 and C28 lose their placement; both are to be disclosed in the E2b amendment. The evidential limitations remain stated in the standing qualifier and `R7/ARB-DECISION-PACKAGE.md` §B. The R7 rows C25, C26, C28, R70, R74 and R62 stay as recorded. This revisits F-LOG-0258. It decides only VP10. Its effect on the adopted model takes hold only when the consequential E2b amendment is made by its own act. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP10 = (iii), re-affirmed:** "I, Human ARB, revisit VP10 as required by F-LOG-0258 and re-affirm VP10 = (iii): CB4 stays an explicit open slot, and CV8, CV9 and CS1 stay adopted and ungrouped. It decides only VP10. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

**New decision item (outside the register's VP10 options; only with (iii); a separate act that may be withheld).** Ruling that (iii) counts for O3 is **not** one of the register's options. It runs against most of the record in §1.7. If taken, it would also need its own act amending register §3 / §5a, and ARB §E3 if the human reads §E3 as requiring a decision (§1.7: ambiguous).

> **Draft (new item):** "I, Human ARB, rule, as a decision item outside the register's VP10 options, that the re-affirmation of VP10 = (iii) counts as VP10's decision for the O3 minimal set, notwithstanding register §3 (VP10 among the 'minimal VPs to decide' for O3), register §5a ('O3: yes'), ARB §C row 4 ('O3: decision') and the prior package §2.3 ('O3 needs a decision'). The consequential amendment of the register, and of ARB §E3 ('E2 for VP10, VP12, VP11') if it is read as requiring a decision, needs its own act. This rules on that question only. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

---

## 2. VP14: boundary partition (reopening)

### 2.1 Question, options, consequences and assumptions (register §1c, verbatim)

| field | register text |
|---|---|
| question | "Boundary partition (§1a)" |
| options | "P-a / P-b / P-c / P-d; none preferred; or leave open (**[r1.7]** this is not a decision: it blocks O2/E2b, and the candidate merely carries no boundary layer *by default*. "No boundary layer" as an **explicit decision** is a separate option, counted as deciding VP14 (§3, package §E2b))" |
| categories | "B, C" |
| depends on / constrains | "**after:** VP15, VP10. **Must be decided without reference to** VP1a–d outcomes (N1). **Constrains:** VP12, VP11" |
| consequences | "**P-a:** H-D clauses internal, H-B clauses crossing; H-D-congenial. **P-b:** conformance and the work disjuncts internal, but the change clauses still cross acts↔σ; only partially H-B-congenial. **P-c:** the change and conformance clauses of both families internal; the existential record clauses cross symmetrically **under 𝐑₀; asymmetrically under R-i and R-i′** (R79, R80; T3 §1a, r1.3 onward); neutral under 𝐑₀. **P-d:** split by observability; the change and conformance clauses internal to the unobserved side; the existential record clauses cross symmetrically **under 𝐑₀; asymmetrically under R-i and R-i′**; it splits concepts by attribute (C13); neutral under 𝐑₀, follows R23. **[r1.5 sync] P-c, P-d:** Under R-h with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger); disclosed (F-LOG-0254 sync). **Leave open:** T3's P-a layout must not be carried into an adopted model, since that would adopt an H-D-congenial cut by implication" |
| assumptions | "P-a / P-b: a tilt toward one family is disclosed; P-c / P-d: none beyond the cut; leave open: none" |
| reversibility | "medium: re-layout re-derives the §1a crossings and VP11/VP12" |
| required authority | "**human design decision**; must not encode H-D or H-B (N1)" |
| needed for | "**O2** (decide, or explicitly adopt without a boundary layer), **O3**" |

**Current value:** "no boundary layer". F-LOG-0259: "VP14 must be reopened before any O3 proposal"; "there is nothing to decompose".

**Effect on E2b.** Reopening VP14 does not in itself amend E2b.
- Re-confirming "no boundary layer" leaves E2b's basis unchanged.
- Choosing P-a, P-b, P-c or P-d needs a consequential E2b amendment, by its own act, because E2b §5 adopts "**without CB1–CB4** (VP14 = no boundary layer, F-LOG-0259)".
- "Leave open" removes E2b prerequisite P5 (analyst reading [E]).

### 2.2 The options (T3 r1.5 §1a)

| option | cut | refinement order (H13) |
|---|---|---|
| P-a | acts + σ │ work │ records (T3's r0 working layout: CB2 │ CB3 │ CB1) | refines P-c |
| P-b | σ + work │ acts │ records | refines P-c; **not comparable with P-a** |
| P-c | one undivided norm-and-work side │ records | coarsest of P-a, P-b, P-c |
| P-d | observed (record ids, r, c) │ unobserved | not comparable with any of the others |
| no boundary layer (re-confirm) | none | — |
| leave open | "not a decision" | — |

### 2.3 Evidence, and what is not established

| evidence-supported | unresolved, or an assumption |
|---|---|
| T3 §6 item 8: "T1/T2 give no criterion for the CB cut" | which cut is adequate to the domain; "no route decides it" (register §5a) |
| clause placement per partition (T3 §1a; [F]-level clause reading) | that any boundary layer is needed, or that none is |
| P-d's line follows R23 (Lem 1.12, [F]) | that the checkability line is a domain boundary |
| F-LOG-0256 (preserved limitation): the theory does not establish aggregate or consistency boundaries as empirical facts | where CB4 sits under P-b, P-c, P-d (§6 item 3); where the participant sits (VP12-dependent) |

### 2.4 Neutral comparison (no ranking, no weights, no total)

**About the rows:**
- Rows 1–6 are H-D / H-B-neutral, taken from ARB §C1 and the prior package §3.4.
- Row 2a ("coherence") is an analyst criterion [E], added in r0. It displaced row 2, which r1 restored.
- Row 7 is disclosed only and is not used.
- **Numbering map to prior package §3.4:** this package's 1, 2, 3 (coupling), 4 (reversibility), 5, 6 and 7 are the prior package's 1, 2, **4**, **3**, 5, 6 and 7. Row 2a has no counterpart there.

| # | criterion | P-a | P-b | P-c | P-d | no boundary layer | leave open |
|---|---|---|---|---|---|---|---|
| 1 | traceability (element level) | tables exist (T3 §1a / §2 CB1–CB3), **because P-a is T3's r0 working layout** (path dependence) | re-derivation (§2.9) | re-derivation | re-derivation, the largest | retained for all 25 | as adopted; P5 withdrawn [E] |
| 2 | minimal unsupported assumptions [E] | a cut not derivable from T1 / T2 (T3 §6 item 8) | same | "same, plus one undivided side" (ARB §C1) | the line follows R23 ([F]); concepts split by attribute (C13) | none added | none |
| 2a | coherence [E]: concepts whole; E2b D1–D3 regain hosts | concepts whole; hosts **now** | concepts whole; hosts after re-derivation | concepts whole; hosts after re-derivation | **counted under row 2 only**: the attribute split is the same fact as row 2's C13 assumption | no hosts (D1–D3 stand) | — |
| 3 | coupling [I], qualitative | "H-B clauses and R18 cross; R2, R3 shared with records" (ARB §C1) | "both families' change clauses cross; F1 (R1) crosses acts↔σ [I]" (ARB §C1) | "fewest clause crossings among the cuts; largest single side" (ARB §C1) | "record-concept attributes cross the line" (ARB §C1) | none at boundary level | — |
| 4 | reversibility | medium | medium | medium | medium | high [E] | high; withdraws P5 [E] |
| 5 | future evidence [I] | V6 and V2 affect all cuts alike | as P-a | as P-a | **its line moves** with an E-A5 channel or a VP3 change (ARB §F) | unaffected | unaffected |
| 6 | decomposition value for O3 [E] | three sides | three sides | two sides | two sides, along R23 | none: E3 not preparable | none; blocks O2, O3 |
| 7 | H-D / H-B congeniality (**disclosed, not used**) | H-D-congenial | only partially H-B-congenial | neutral under 𝐑₀; asymmetric under R-i / R-i′; R-h / R83 note | as P-c | neutral by construction | — |

**On row 3, provenance:**
- ARB §C1's coupling row, and the prior package §3.4 row 4 that reuses it, are **qualitative**. They mix clause crossings (T-B slot clauses) with T-A rows (R18, R2, R3 for P-a; R1 for P-b) and give no count.
- A T-A count would need one criterion applied to every T2 row, with the participant placed. That depends on VP12, so any count is **indeterminate before VP12**, and no count is given.

**Granularity (H13): stated only for the refinement order P-a, P-b ⊐ P-c, and only for criteria that are monotone under refinement.**
- A refinement adds a cut. It therefore has at least as many crossings (row 3), more sides (row 6), and one more cut that T1 / T2 do not justify (row 2). T3 §6 item 8: "T1/T2 give no criterion for the CB cut".
- **Row 2, read symmetrically:**
  - ARB §C1 reads P-c as carrying "one undivided side";
  - the same fact reads equally as "**P-c makes one fewer unsupported cut**".
- So rows 2, 3 and 6 are **one granularity fact** for P-c vs P-a and P-c vs P-b, and none of them separates the two.
- **No granularity claim is made** between P-a and P-b, or between P-d and any other cut, because they are not comparable by refinement.

**R-h / R83 note (register §1c, for P-c and P-d):** "Under R-h with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger); disclosed (F-LOG-0254 sync)." R83 is inactive.

**Provenance disclosures (tilts in the source tables):**
- **ARB §C1:** "criteria 1–5 below favour 'no boundary layer' largely because it makes no decomposition at all; that advantage is the absence of a structure, not evidence for it".
- **Prior package §3.4:** "Criteria 1 and 6 favour P-a, which is the H-D-congenial cut". Its numbering is the same as rows 1 and 6 here. Under the discounts in §2.7, row 1 is path-dependent and row 6 is granularity (row 6 among P-a, P-b, P-c only; not against P-d).
- **Prior package §3.7:** it set P-a and P-b aside by a congeniality threshold (row 7: "no recommendation for an option with any disclosed tilt under 𝐑₀"). It then chose P-c on its criteria 4 and 5 (rows 3 and 5 here). Its revisit condition was VP1d decided for R-i, R-i′ or an active R83. This package does not take that route.

### 2.5 Consequences, compatibility with the adopted model, and implications

| option | change to the adopted model; E2b amendment | O3 / E3 | R8 | implementation |
|---|---|---|---|---|
| P-a | CB1–CB3 rows (plus CB4 under VP10 (i)) re-added from T3 r1.5 as they stand; D1–D3 resolved. **E2b amendment required** (E2b §5 "without CB1–CB4"). The tilt is carried in the act. The E2b §6 row with condition "an adopted element found to encode H-D or H-B" (reopens "VP14; this adopting act (N1)") stays live | three sides | working labels (N8) | nothing enforceable from records (R23) |
| P-b, P-c, P-d | new side rows after an **authorized re-derivation** (§2.9); E2b amendment after its review | E3 preparable only after the re-derivation | as P-a | as P-a |
| no boundary layer | none | **E3 not preparable** (F-LOG-0259) | — | — |
| leave open | E2b basis P5 withdrawn [E]; the adoption must be reopened by its own act | blocked | — | — |

### 2.6 Reversibility and reopen conditions

**Reversibility:** "medium" (register) for P-a to P-d. The cost rises once an E3 proposal rests on a cut.

**Reopen conditions:**
- ARB §F: E-A5 / Protocol C evidence on σ or f (P-d's line).
- ARB §F: "any adopted element is found to encode H-D or H-B, or a partition choice is found to be justified by VP1a–d outcomes".
- E2b §6 (cells in §1.2): "VP decided by implication or bundled"; "an adopted element found to encode H-D or H-B".

### 2.7 Recommendation: after even discounts the neutral criteria give no ordering of the four cuts without weights; the choice is the human's

**Basis:** rows 1–6 only. Row 7 and any VP1a–d outcome are **not** used.

**Scope.** "No boundary layer" (re-confirmed) and "leave open" are outside this comparison. Both fail O3, the purpose of the reopening: row 6 shows "none" for both, and F-LOG-0259 says "nothing to decompose". They remain legitimate choices if O3 is not intended (§2.8).

**Discounts (analyst judgements [E]):**
- **path [E]:** row 1, and the "now vs after re-derivation" timing in row 2a. Both rest only on the path dependence of T3 having been laid out as P-a first. Applies to all four cuts.
- **granularity [E]:** rows 2, 3 and 6. Applies **only among P-a, P-b and P-c**, which are related by refinement (§2.4). It does not apply to P-d.
- **double count [E]:** P-d's attribute split is counted once, under row 2.

| row | P-a | P-b | P-c | P-d | discount |
|---|---|---|---|---|---|
| 1 traceability | ahead | behind | behind | behind | **path**: discounted for all |
| 2 assumptions [E] | +1 cut vs P-c | +1 cut vs P-c | −1 cut (read as "one undivided side") | R23-founded line [F]; attribute split (C13) | **granularity** for P-a / P-b / P-c; P-d not comparable |
| 2a coherence [E] | whole; hosts now | whole; hosts later | whole; hosts later | (counted under row 2) | **path** (timing); **equal in substance** for P-a, P-b, P-c |
| 3 coupling [I] | more crossings than P-c | more crossings than P-c | fewest of P-a, P-b, P-c | not comparable | **granularity** |
| 4 reversibility | medium | medium | medium | medium | tie |
| 5 future evidence [I] | stable | stable | stable | **line moves** | none (a real difference for P-d) |
| 6 decomposition [E] | three sides | three sides | two sides | two sides | **granularity** among P-a, P-b, P-c only; **not discounted for P-d** |

**Result:**
- **P-a vs P-b:** not separated once row 1 is discounted.
- **P-c vs P-a / P-b:** every remaining difference is one granularity fact read from both ends, so they are not separated.
- **P-d:** its differences point both ways, and none is discounted:
  - row 2: a line founded on R23 [F], against a split of concepts;
  - row 5 [I]: a line that can move;
  - row 6 [E]: two sides against three for P-a and P-b.

  These do not net out without weights, and this package supplies none.

**After even discounts, the neutral criteria give no ordering of the four cuts without weights. The choice is the human's.**

**Disclosure:** whichever cut is chosen, the act carries that cut's row-7 disclosure (§2.8). If the choice is P-b, P-c or P-d, the re-derivation (§2.9) needs its own act.

### 2.8 Draft Human ARB acts (one per option; choose one)

**Common closing clause C14′** (adapted from F-LOG-0259 C14): "This partition is decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule. It decides only VP14 (VP10, VP12 and VP11 are decided separately). It supersedes the F-LOG-0259 value. Its effect on the adopted model takes hold only when the consequential E2b amendment of the 'no boundary layer' basis is made by its own act. It adopts no model element and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5, nor the re-derivation of T3's element tables, which needs its own authorization."

> **VP14 = P-a:** "I, Human ARB, reopen VP14 and decide VP14 = P-a (acts + σ │ work │ records), as a human design decision. I record its disclosed tilt: the H-D clauses are internal and the H-B clauses cross CB2↔CB3 (H-D-congenial), as the register's assumptions cell provides for P-a ('a tilt toward one family is disclosed'). T3 r1.5's CB1–CB3 rows serve as its element tables without re-derivation. [C14′]"

> **VP14 = P-b:** "I, Human ARB, reopen VP14 and decide VP14 = P-b (σ + work │ acts │ records), as a human design decision. I record its disclosed tilt: only partially H-B-congenial; the change clauses of both families cross acts↔σ, as the register's assumptions cell provides for P-b ('a tilt toward one family is disclosed'). Its element tables require a separately authorized re-derivation. [C14′]"

**Note (analyst reading [E]; not part of any act text; prior package §3.7).** The basis for admitting a congenial cut under N1 is the prior package's reading: "N1 forbids *encoding* H-D or H-B, and the register lets the human choose a tilted partition with its tilt disclosed".

> **VP14 = P-c:** "I, Human ARB, reopen VP14 and decide VP14 = P-c (one undivided norm-and-work side │ records), as a human design decision. I record that its neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), with the R-h / R83 crossing note. Its element tables require a separately authorized re-derivation. [C14′]"

> **VP14 = P-d:** "I, Human ARB, reopen VP14 and decide VP14 = P-d (observed │ unobserved, following R23), as a human design decision. I record that it splits concepts by attribute (C13), that its neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), the R-h / R83 crossing note, and that an E-A5 channel or a VP3 change would move its line. Its element tables require a separately authorized re-derivation. [C14′]"

> **VP14 = no boundary layer (re-confirm):** "I, Human ARB, reopen VP14 and re-confirm VP14 = no boundary layer (F-LOG-0259). E2b's basis is unchanged. I record that an O3 / E3 proposal is therefore not preparable, and the analyst reading [E] that this value is inconsistent with VP10 = (i), if VP10 (i) is decided (§1.4, §6 item 3). This is decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule. It decides only VP14 and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP14 = leave open:** "I, Human ARB, withdraw the VP14 decision and leave VP14 open. I record the analyst reading [E] that this removes E2b prerequisite P5, so that the E2b adoption must be reopened by its own act; the effect of this withdrawal on the adopted model takes hold only when that reopening act is made. This is decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule. It decides only VP14 and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

### 2.9 T3 re-derivation scope (P-b, P-c, P-d only; P-a needs none)

**Prerequisites:**
- VP10 decided first;
- the VP14 act;
- **participant placement:** because the re-derivation comes before VP12, it is performed with the participant provisionally inside CG1, as T3 §1b does. It is re-checked after VP12, and under VP12 (ii) partly re-derived, by its own authorization;
- **a separate human act authorizing a post-closure amendment of T3 r1.5** (F-LOG-0259 C14; the F-LOG-0254 pattern);
- the register and E2b synced afterwards.

**Tables and sections that change:**
- **§1a:** the CB table, "Relations between boundaries", the participant row ("shared across CB1–CB3"), the partition disclosure and the option table.
- **§2:** the CB1–CB4 trace rows.
- **§3a host column:** X1, X2, X4, X10, X25 (CB ids), and X19 (CB4, CV9).
- **§4 rows:** VP1d (CB1), VP2 (CB1↔CB2), VP3 (CB2), VP10 (CB4), VP11 (a side note if any CG straddles), VP12 (CB1–CB3), VP14.
- **§6:**
  - the neutrality row;
  - the rows "Every element traces to T1 and T2 ids" (it names CB4) and "No T-B, T-C or X item is active as an invariant" (it names R62 on CB4 and CV8);
  - insufficiency items 4 and 8;
  - the counts.

**Unchanged:** the CG, CV, CE, CS and MW rows. Under P-d they get a side-placement note for CG4 and CV3, which T3 marks "mixed".

**Review criteria:**
- every C1–C28 is on exactly one side, or is a **stated exception**:
  - C1 spans the sides under VP12 (i);
  - C22 has no place in T3's CB layout (it appears only under CV3), so its placement is stated;
  - any attribute split (P-d) is disclosed;
- one crossing criterion is applied to every T2 row, with the participant placed (VP12-dependent);
- the H-D / H-B placement matches T3 §1a (𝐑₀ / R-i / R-i′ / R-h);
- no T-B, T-C or X item is activated;
- the traces to T1 / T2 / R4 ids are unchanged in substance;
- the cut makes no reference to VP1a–d (N1);
- the standing qualifier is carried;
- an independent review is done.

| option | expected changes ([E]; outputs not fixed here) | relative size [E] |
|---|---|---|
| P-c | one norm-and-work side, the union of CB2 and CB3 (C2–C12, C19–C21); the records side reuses CB1; D1 and D2 gain hosts | smallest |
| P-b | CB2 re-cut into σ (C3, C5–C7) and acts (C8–C12); σ merged with CB3; **the placement of C2, C4 and C22 is an output** | medium |
| P-d | sides by attribute; C13 and others split; CG4 and CV3 straddle; record clauses cross symmetrically under 𝐑₀ | largest |

> **Draft re-derivation authorization (only after VP14 = P-b, P-c or P-d):** "I, Human ARB, authorize a post-closure amendment of `R7/T3-CANDIDATE-MODEL.md` r1.5, re-deriving §1a, the §2 boundary rows, the §3a host column (including X19), the §4 VP1d / VP2 / VP3 / VP10 / VP11 / VP12 / VP14 rows and the affected §6 rows under VP14 = [P-x], to be independently reviewed. It adopts nothing, does not amend E2b, decides no variation point, and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

---

## 3. VP12: participant

### 3.1 Question and options (register §1c, verbatim)

| field | register text |
|---|---|
| question | "Participant: one shared concept or boundary-local roles" |
| options | "(i) one shared participant (R4's 𝒜); (ii) boundary-local concepts (norm-maker / executor / keeper); (iii) leave open" |
| categories | "A, C, E" |
| depends on / constrains | "**after:** VP15 (Def 1.2), VP4, VP14. **Constrains:** VP11 (CG1)" |
| consequences | "(i) follows Def 1.2. (ii) splits one actor set across boundaries, and the identity link (R22) becomes a crossing constraint. R70 independence reads the roles relative to τ, not as concepts. (iii) one actor set held, as now" |
| assumptions | "(i): none beyond R4; (ii): a role-separation assumption (overlaps VP4); (iii): none" |
| reversibility | "medium" |
| required authority | "**human design decision**" |
| needed for | "**O3**" |

### 3.2 Evidence, and what is not established

| evidence-supported (ids) | unresolved, or an assumption |
|---|---|
| Def 1.2: one actor set 𝒜; VP15 (i) (F-LOG-0255) | whether boundary-local participant concepts fit the domain (register §5a) |
| R22: actor identity is identifier identity, unobserved (Lem 1.12) | **[analyst]** (ii)'s roles map onto P-a's sides. Under P-c, norm-maker and executor share a side; under P-d all three are unobserved; under "no boundary layer" there are no sides (§6 item 4) |
| R70 reads the roles relative to τ, not as concepts | **risk of an implied VP4 decision:** (ii) "overlaps VP4" (register). Introducing role concepts may decide VP4 (roles) by implication, which triggers the E2b §6 row "VP decided by implication or bundled" |
| A05, A06: role names in texts only. T3 §3b excludes "named roles / offices beyond Cap" (OQ10; R76), and E2b §2d carries it as an excluded extension. VP4 is open | — |

### 3.3 Consequences and compatibility with the adopted model

| option | adopted model | E2b amendment? | O3 / E3 | R8 | implementation |
|---|---|---|---|---|---|
| (i) | the participant stays as adopted; D3's "shared" refers to the reopened sides (none under "no boundary layer") | no element change. The §2f status changes from "provisional" to "decided"; whether this alone needs an E2b amendment is open, treated as for VP11 (ii) (§6 item 9) | one participant spanning the sides | one label | — |
| (ii) | the participant is replaced by three boundary-local concepts; R22 becomes a crossing constraint; a role-separation assumption is added | **yes**: the adopted element is replaced (D4), **and** the T3 §3b exclusion "named roles / offices beyond Cap" (OQ10, R76; E2b §2d) may be engaged | three role concepts, VP14-dependent | three labels needing [E] justification | identity across roles is unobservable (R22) |
| (iii) | none | no | **does not meet O3** (register §4: "**Not open for O3**") | — | — |

**H-D / H-B:** the register gives VP12 no category B.

### 3.4 Reversibility, reopen conditions, and required evidence

- **Reversibility:** "medium" (register).
- **Reopen conditions:**
  - V6 shows role structure ([I]);
  - VP4 is decided (i);
  - VP14 is re-cut;
  - **under (ii), the E2b §6 row "VP decided by implication or bundled", if VP4 is found to have been decided by implication**;
  - the generic ARB §F rows.
- **Evidence:** none in the corpus.
- **Under (ii):** the act names the partition the roles are defined against. The T3 participant row, the CG1 row and their §2 traces must be split.

### 3.5 Recommendation (separate from the evidence; analyst judgements marked [E])

**Recommended: (i).**

**Rationale:**
- it adds no assumption beyond R4 and is consistent with VP15 (i);
- it changes no adopted element;
- it holds under every VP14 option, including "no boundary layer" [E];
- it does not anticipate the open VP4, and it avoids the risk of an implied VP4 decision [E].

**Cost:** there is no role-level decomposition for E3. Roles belong to VP4 as an extension.

### 3.6 Draft Human ARB acts

> **VP12 = (i):** "I, Human ARB, decide VP12 = (i): one shared participant concept (R4's single actor set 𝒜, Def 1.2), spanning the sides of the partition decided under VP14, or, if VP14 is 'no boundary layer', held as R4's single actor set with no sides to span. This decides only VP12. It does not decide VP4 or VP11 and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP12 = (ii):** "I, Human ARB, decide VP12 = (ii): boundary-local participant concepts (norm-maker / executor / keeper), defined against the sides of VP14 = [P-x], as a human design decision carrying a role-separation assumption; the actor-identity link (R22) becomes a crossing constraint, unobservable from records. I record that this overlaps VP4, which this act does not decide; should it be found to decide VP4 by implication, the E2b §6 row 'VP decided by implication or bundled' applies. This decides only VP12. Its effect on the adopted model (replacement of the adopted participant element, and any engagement of the T3 §3b / E2b §2d exclusion 'named roles / offices beyond Cap') takes hold only when the consequential E2b amendment is made by its own act. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP12 = (iii):** "I, Human ARB, leave VP12 open; R4's single actor set is held. I record that the O3 minimal set is then not met. This decides only VP12 and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

---

## 4. VP11: grouping granularity (candidate aggregate boundaries)

### 4.1 Question and options (register §1c, verbatim)

| field | register text |
|---|---|
| question | "Grouping granularity (CG1–CG6)" |
| options | "(i) merge groupings (e.g. CG2+CG3); (ii) keep them separate with cross-grouping constraints; (iii) leave provisional (as now)" |
| categories | "A, B, C, E" |
| depends on / constrains | "**after:** VP14, VP12, VP8, VP4, VP10. **Interacts with:** VP1b, since CG3 is meaningful mainly under the H-D-side D-st / D-st⁺" |
| consequences | "(i) and (ii) need a consistency notion that R4 lacks (§3b), and no grouping is enforceable from records (R23). Ten T-A rows cross groupings (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22). A CG3-centred choice leans toward H-D-side clauses; a grouping justified only by H-B-side clauses would lean the other way. (iii) groupings stay conceptual" |
| assumptions | "(i) and (ii): a consistency / transaction notion (extension); (iii): none" |
| reversibility | "high while provisional" |
| required authority | "**human design decision**" |
| needed for | "**O3**" |

### 4.2 No derivation is possible: any choice is a design decision

- **R4 supplies no consistency or transaction criterion.**
  - T3 §4 VP11: "human design decision; R4 has no consistency notion".
  - T3 §1b: "R4 has no consistency, transaction or lifecycle notion … The grouping boundaries are therefore not derivable from T1/T2".
  - Register §3: "VP11 needs a design decision, not a derivation".
- **R23** (T3 §1b): "an aggregate-like 'enforcement unit' therefore has no operational content in this model". Any candidate aggregate boundary is conceptual only.
- **F-LOG-0256:** aggregate or consistency boundaries are not established as empirical facts.

### 4.3 Evidence, and what is not established

| evidence-supported | unresolved, or an assumption |
|---|---|
| ten crossing T-A rows, and guard rows R14–R17 (T3 §1b), computed with the participant inside CG1, i.e. under VP12 (i) | any consistency notion: "no: the gap is a missing notion, not missing data" (register §5a) |
| T3 §1b CG3 note: grouping an issue act with its adoptions "carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses)". The register's counterpart: "a grouping justified only by H-B-side clauses would lean the other way" | which merges (i) would mean; the register gives only "e.g. CG2+CG3" (§6 item 5) |
| VP4 and VP8 open at their E2b §2f defaults (F-LOG-0261) | whether that counts as "explicitly left open" for the edges VP4 → VP12, VP4 → VP11 and VP8 → VP11 (§6 item 6) |

### 4.4 Consequences and compatibility with the adopted model

| option | adopted model | E2b amendment? | O3 / E3 | R8 | implementation |
|---|---|---|---|---|---|
| (i) merge | the named CGs are replaced by a merged grouping; a consistency notion is added. Lean (register): "A CG3-centred choice leans toward H-D-side clauses; a grouping justified only by H-B-side clauses would lean the other way" | **yes**: CG ids change, and the consistency notion is admitted (note below) | fewer candidate aggregates | a new label | nothing enforceable from records (R23) |
| (ii) separate, with cross-grouping constraints | CG elements unchanged; the crossing T-A rows labelled as cross-grouping constraints; a consistency notion added. **Lean disclosed under (ii) as well:** CG3 stays a grouping that "carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses)" (T3 §1b); counterpart: "a grouping justified only by H-B-side clauses would lean the other way" (register) | **yes**: the consistency notion is admitted (note below). The §2f status also changes from "provisional" to "decided"; whether this alone needs an amendment is open, treated as for VP12 (i) (§6 item 9) | six candidate aggregates | none new | nothing enforceable from records (R23) |
| (iii) provisional | none | no | **does not meet O3** (register §4: "**Not open for O3**") | — | — |

**Why (i) and (ii) both need an E2b amendment.** E2b §2d carries the "10 rows" of T3 §3b as an **excluded** set: "extensions beyond R4 (unsupported)". One of those rows is "a consistency / transaction notion to fix grouping boundaries | not in R4 (VP11) | human design decision". Both (i) and (ii) introduce that notion; the register's assumptions cell reads "(i) and (ii): a consistency / transaction notion (extension)".

### 4.5 Reversibility, reopen conditions, and required evidence

- **Reversibility:** "high while provisional" (register). Once decided and cited by E3, medium [E].
- **Reopen conditions:**
  - VP14 or VP12 re-decided;
  - VP8 decided (i), which reshapes CG3;
  - VP4 decided (i);
  - VP10 changes;
  - the generic ARB §F rows.
- **Evidence:** none can decide VP11.
- **Under (i):** the act names the CGs and the rationale.
- **Under VP12 (ii):** the crossing list must be re-derived, which needs its own authorization.

### 4.6 Recommendation (separate from the evidence; analyst judgements marked [E])

**Recommended: (ii).**

**Rationale:**
- it changes no CG element. Like (i), it admits the excluded consistency notion and so needs an E2b amendment;
- it needs no merge criterion, and the register supplies none;
- the cross-grouping constraints already exist as T-A rows (T3 §1b), so the added notion can be stated minimally [E];
- of the two options that meet O3, it is the more reversible, because a merge can be added later [E].

**Analyst gloss (not register text):** state the consistency notion as conceptual, with no transaction semantics, and with nothing enforceable from records (R23).

**Neutrality:**
- no VP1a–d or VP1b outcome is used;
- the CG3 lean and its H-B counterpart are disclosed under (i) and (ii) alike.

### 4.7 Draft Human ARB acts

> **VP11 = (i):** "I, Human ARB, decide VP11 = (i): the groupings [CGx + CGy] are merged, as a human design decision introducing a consistency notion not supplied by R4 (§3b); no grouping is enforceable from records (R23). I record the register's disclosure: 'A CG3-centred choice leans toward H-D-side clauses; a grouping justified only by H-B-side clauses would lean the other way.' This is decided without reference to any VP1a–d (including VP1b) outcome. It decides only VP11. Its effect on the adopted model (change of adopted CG elements, and admission of the consistency notion that E2b §2d carries as an excluded §3b extension) takes hold only when the consequential E2b amendment is made by its own act. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

> **VP11 = (ii):** "I, Human ARB, decide VP11 = (ii): CG1–CG6 are kept separate, with the T-A rows that cross groupings as cross-grouping constraints, as a human design decision introducing a consistency notion not supplied by R4 (§3b); no grouping is enforceable from records (R23). Under VP12 (i) these are R1, R2, R3, R5, R6, R8, R9, R18, R19 and R22 (T3 §1b); under VP12 (ii) the crossing list is to be re-derived, which needs its own authorization. I record that CG3 'carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses)' (T3 §1b), and the register's counterpart that 'a grouping justified only by H-B-side clauses would lean the other way'. This is decided without reference to any VP1a–d (including VP1b) outcome. It decides only VP11. Its effect on the adopted model (admission of the consistency notion that E2b §2d carries as an excluded §3b extension) takes hold only when the consequential E2b amendment is made by its own act. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."
>
> *(Analyst gloss, not part of the act: the notion may be stated as conceptual, with no transaction semantics.)*

> **VP11 = (iii):** "I, Human ARB, leave VP11 provisional; the groupings stay conceptual. I record that the O3 minimal set is then not met. This is decided without reference to any VP1a–d (including VP1b) outcome. It decides only VP11 and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

---

## 5. Standing qualifier

"one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 6. Where the register or the record is insufficient or in tension

| # | point | effect |
|---|---|---|
| 1 | **What the register's VP10 (i) step means ("X19, §3b") is a question for the human, alongside F-LOG-0261.** (a) [E] It means gaining a side; or (b) CV9's adoption may already engage the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists as excluded. X19's hosts are "CB4, CV9", and CV9 also traces R23 via MW1. F-LOG-0261 says adopting CV9 does not decide VP10. The E2b §6 row "VP decided by implication or bundled" is available | for the human; no fix proposed |
| 2 | **VP10 at the O3 gate: contested.** Against (iii) sufficing: register §3 (for O2, an explicit "left open" (or decided) record; for O3, VP10 among the "minimal VPs to decide"), register §5a ("O3: yes"), ARB §C row 4, prior package §2.3. Ambiguous: ARB §E3, since under ARB §E2 a re-affirming act is itself an E2 decision. For it: (iii) is a register option; register §5a says "whichever option the human picks, including "leave open", is the human's"; and the revisit-vs-decide contrast in E2b §6 and the F-LOG-0261 prerequisites. F-LOG-0258's "revisited" is silence on the question. Register §4 covers O1 and O2 only. Bottom line: contested; on register §3 and §5a, most plausibly (i) or (ii) | (iii) is listed; a "counts for O3" ruling is a **new decision item** outside the register's options, and it needs a register §3 / §5a amendment by its own act, plus an ARB §E3 amendment if §E3 is read as requiring a decision (§1.8) |
| 3 | [E] VP10 (i) excludes VP14 = "no boundary layer" (E2b's CB4 slot coexists with no layer; a side does not). It conflicts with F-LOG-0259 "carried without CB1–CB4". T3 places CB4 only beside P-a | the (i) act records this; the re-derivation places CB4 |
| 4 | VP12 (ii) is defined against P-a's sides only | under (ii) the act names the partition |
| 5 | VP11 (i): only "e.g. CG2+CG3"; merges not enumerated | the act names the merge; none invented |
| 6 | edges VP4 → VP12, VP4 → VP11 and VP8 → VP11: F-LOG-0261 says "all other variation points stay open with the defaults of `R7/E2b-ADOPTION-TEXT.md` §2f"; whether that is "explicitly left open" is not stated | the human may confirm VP4 and VP8 open first |
| 7 | whether the E2b amendments precede the O3 proposal, and as one act or several | left to the human |
| 8 | register §3: "Preparing such a proposal needs its own human act". F-LOG-0261's prerequisites omit that act | §0 lists it separately |
| 9 | **Interim state:** between a VP act and its E2b amendment, the adopted model stays as in F-LOG-0261; the register records the new value. **The E2b amendment for VP10 (i) cannot be made consistently while VP14 = "no boundary layer" stands** (E2b §5 "without CB1–CB4"), so it waits for VP14. For VP12 (i) and VP11 (ii) alike, it is not stated whether the §2f status change ("provisional" to "decided") alone needs an amendment; for VP11 (ii) an amendment is needed anyway, for the consistency notion. The leave-open withdrawal of VP14 takes effect only when its reopening act is made | [E]: no O3 proposal should be prepared on a new value before its amendment |
| 10 | register VP10 (ii): "qualifier only"; E2b and this (ii) act also keep §B | the human may restrict it to the qualifier |
| 11 | the F-LOG-0261 summary states "VP10 revisited as (ii), which removes CV8, CV9 and CS1" without E2b §6's "analyst reading [E]" label | the (ii) act ratifies the reading |
| 12 | VP12 (ii) risks deciding VP4 by implication, and may engage the E2b §2d exclusion "named roles / offices beyond Cap" | disclosed in §3.2, §3.4 and the (ii) act |
| 13 | label drift in the inputs (header) | noted; not edited |

## 7. Self-check

| check | result |
|---|---|
| nothing decided, adopted or authorized | yes. Every act is a draft, decides only its own VP, and says it does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5 |
| no bundling | yes. Four sections; the re-derivation, the E2b amendments and the §1.8 new item are separate acts |
| quotes match register r1.10 | yes, for the VP10, VP11, VP12 and VP14 rows, §3 and §4. E2b §6 is quoted cell by cell; §2.5 quotes cells separately |
| no invented options | yes. Register options only. **One new decision item is labelled as such**: the §1.8 "counts for O3" ruling, outside the register's options and needing its own register / ARB amendment. Row 2a is an added criterion, not an option |
| VP10 | relation preserved (§1.2). The contrary record is cited in full; F-LOG-0258's "revisited" is treated as silence; (iii) is contested; the status-quo tilt is disclosed; (i) vs (ii) is unranked; the (i)/§3b reading is labelled [E] with an alternative; the D-type gaps under (ii) are listed, including C25, C26 and C28 losing their placement. **[r4]** ARB §E3 is classed as ambiguous; the "for" side lists (iii) as a register option and the §5a reading note; the ARB §E3 amendment in the new item is conditional; the (i) act no longer settles X19 (§6 item 1) |
| VP14 neutral | yes. One discount table covers all four cuts. All discounts are labelled [E]. Rows 1 and 2a (timing) are path-discounted for all, on path dependence alone. Rows 2, 3 and 6 are one granularity fact, applied only among P-a, P-b and P-c (P-a, P-b ⊐ P-c). P-d's split is counted once, and its row-6 difference stays in the Result. P-a and P-b are "not separated". The "Criteria 1 and 6" quote sits in the provenance disclosures. "No boundary layer" and "leave open" are outside the comparison, with the reason stated. Result: after even discounts the neutral criteria give no ordering of the four cuts without weights. Row 7 is not used. The N1 note is outside the acts, as [E] |
| [I] / [E] labels in recommendations | yes (§1.7, §2.7, §3.5, §4.6) |
| re-derivation scope | yes (§2.9): it includes X19, VP2, VP3, VP11, VP12 and the T3 §6 "Every element traces" and "No T-B, T-C or X item" rows; P-d straddlers are CG4 and CV3; C1 and C22 are stated exceptions |
| E2b effects | yes. Only a value other than "no boundary layer" amends E2b's basis. VP10 (i), VP10 (ii), VP11 (i), VP11 (ii) and **VP12 (ii)** need E2b amendments, and each act says its effect takes hold only on the amendment. The VP10 (i) amendment waits for VP14. The §2f status change ("provisional" to "decided") for VP12 (i) and VP11 (ii) is treated alike: whether it alone needs an amendment is open (§6 item 9). The leave-open act's effect waits for the act reopening E2b. The re-confirm act cross-refers to its [E] inconsistency with VP10 (i) |
| VP11 | design-decision status (§4.2); the CG3 lean and its H-B counterpart are disclosed under (i) and (ii); every act is decided without reference to VP1a–d, including VP1b |
| standing qualifier verbatim | yes (header, §5) |
| only this file written | yes |

## 8. Change rows

| rev | change | reason |
|---|---|---|
| r0 | first draft | commission (F-LOG-0261) |
| r1 | F1–F15 (map below) | review of r0: FAIL |
| r2 | G1–G14 (map below) | review of r1: FAIL |
| r3 | H1–H16 (map below); a full rewrite of the file | review of r2: FAIL |
| r4 | I1–I12 (map below); targeted edits only | review of r3: PASS_WITH_LIMITATIONS, nothing blocking |

**r1 map (F1–F15).**

| finding | fix |
|---|---|
| F1 | P-c recommendation withdrawn; row-3 provenance; row 2 restored; coarsening; prior-package route disclosed (refined further by G1 and H1) |
| F2 | the CV9 point restated as a possible tension (refined by G7 and H4) |
| F3 | §0: O3 preparation needs its own act; §6 item 8 |
| F4 | VP10 recommendation not conditioned on VP14; the (i) act records the exclusion |
| **F5** | **superseded by G2 and G9** (and H3): the (iii) recommendation was withdrawn, and the ruling split out as a new decision item |
| F6 | "takes hold only on amendment" clauses; interim state, §6 item 9 |
| F7 | §2.9 scope additions; P-d straddlers aligned |
| F8 | §6 items added |
| F9 | [I] labels in the recommendations |
| **F10** | **superseded by G4**: the N1 basis was moved out of the act text and labelled [E] |
| F11 | F-LOG-0261 "§2f defaults" cited (§0, §6 item 6) |
| F12 | only a value other than "no boundary layer" amends E2b |
| F13 | the register's VP14 consequences and assumptions cells quoted |
| F14 | VP12 (i) under "no boundary layer"; the VP11 (ii) rows assume VP12 (i) |
| F15 | label-drift table |

**r2 map (G1–G14).**

| finding | fix |
|---|---|
| G1 | row 2a: P-c not ahead; rows 3/6 discount; row 1 path-dependence; "Criteria 1 and 6" restored (all refined further by H1 and H2) |
| G2 | §1.7 contrary record; the §4 argument withdrawn; (iii) contested; status-quo tilt disclosed; recommendation: "decide (i) or (ii) rather than re-affirm (iii); (i) vs (ii) is not ranked; the human chooses" (wording identical to §1.7) |
| G3 | VP11 (ii) needs an E2b amendment (E2b §2d / T3 §3b consistency notion); act clause; rationale |
| G4 | the N1 reading moved out of the acts and labelled [E] (prior package §3.7) |
| G5 | row-3 provenance: qualitative, mixing clauses with R1, R2, R3 and R18 |
| G6 | numbering map; "decided by implication or bundled" attributed to E2b §6 |
| G7 | CV9's R23 trace; X19's hosts "CB4, CV9" |
| G8 | "excludes no boundary layer" labelled [E]; E2b's CB4 slot without a layer |
| G9 | the "counts for O3" ruling separated (now a labelled new item, H3) |
| G10 | §0 conditional O3 set |
| G11 | N1 clause in the re-confirm and leave-open acts |
| G12 | [E] labels in §1.7 and §4.6 |
| G13 | §2.9 stated exceptions (C1, C22) |
| G14 | E2b §6 quoted cell by cell |

**r3 map (H1–H16).**

| finding | fix |
|---|---|
| H1 (blocking) | §2.4 granularity: row 2 is the same fact as rows 3 and 6 (P-a = P-c + one cut; T3 §6 item 8); symmetric reading "P-c makes one fewer unsupported cut"; P-d's split counted once (rows 2 / 2a merged for P-d); P-b ties P-a once row 1 is discounted; the "Criteria 1 and 6" quote moved to the provenance disclosures; Result: "after even discounts, no neutral criterion separates the four cuts; the choice is the human's"; §7 corrected |
| H2 | §2.7: one discount table for all four cuts (path, granularity, double count); no-boundary and leave-open handled in the §2.4 table and, from r4, in the §2.7 "Scope" line (r3's "step 1" reference was stale; see I10) |
| H3 | §1.7: register §3 O2-vs-O3 contrast cited; F-LOG-0258 treated as silence; E2b §6 and F-LOG-0261 revisit-vs-decide contrast cited; §1.8 ruling labelled a **new decision item** outside the register options, needing a register §3 / §5a and ARB §E3 amendment; its "notwithstanding" list completed (register §3, prior package §2.3); §7 "no invented options" fixed |
| H4 | §1.3 and §6 item 1: the "gaining a side" reading labelled [E]; alternative reading stated (CV9 may already engage the §3b row excluded by E2b §2d); posed to the human alongside F-LOG-0261 |
| H5 | VP12 (ii): E2b §2d / T3 §3b "named roles / offices beyond Cap" (OQ10, R76) added; implied-VP4 risk flagged in §3.2, §3.4, the act and §6 item 12; §7 "E2b effects" includes VP12 (ii) |
| H6 | VP11: the CG3 lean disclosed under (ii) (T3 §1b quote); the H-B counterpart added wherever the lean appears; all VP11 acts are "decided without reference to any VP1a–d (including VP1b) outcome" |
| H7 | VP11 (ii) act: the analyst gloss moved out of the act; the crossing-list re-derivation made subject to its own authorization |
| H8 | §6 item 9 and the VP10 (i) act: the amendment waits for VP14 |
| H9 | VP10 (ii): D-type gaps listed (R66–R69, R70, R74, R82, R62; X19, X21) in §1.4, §1.6 and the act; §1.6 "Re-derivation: none" corrected |
| H10 | "removes E2b prerequisite P5" labelled [E] in §2.1, §2.4, §2.5 and the leave-open act |
| H11 | every act now says "does not authorize the preparation of an O3 proposal" |
| H12 | §0 lists all incoming edges, including VP15 → VP14 and VP5 → VP14 (decided), VP15 → VP12 (row text), and VP1b ⇢ VP11 |
| H13 | granularity claims restricted to the refinement order P-a, P-b ⊐ P-c and to monotone criteria; P-a / P-b and P-d marked not comparable (§2.2, §2.4) |
| H14 | §4.4: the amendment note placed below the table; the (iii) row restored inside the table |
| H15 | §3.5 [E] labels (listed in §7); the row-2 tag in §2.4 is now [E], matching §2.7; §4.6 quotation marks removed; the §1.7 and G2 wording made identical; §2.5 E2b §6 cells quoted separately; §1.3 CV8 / CS1 trace claim corrected |
| H16 | §2.9 adds the T3 §6 rows "Every element traces to T1 and T2 ids" (CB4) and "No T-B, T-C or X item is active as an invariant" (R62 on CB4 and CV8) |

**r4 map (I1–I12).**

| finding | fix (location) |
|---|---|
| I1 (major) | §1.7 table and text: ARB §E3 reclassed as **ambiguous** (ARB §E2: "the option from the register (§1) or "leave open""); "for" side adds (iii) as a register option and the register §5a note "whichever option the human picks, including "leave open", is the human's"; "only" dropped; bottom line kept (contested; most plausibly (i) or (ii) per register §3 and §5a "O3: yes"). §6 item 2 aligned; §1.8 new item: the ARB §E3 amendment made conditional; §0 VP10 condition aligned |
| I2 (major) | §1.8 VP10 (i) act: "X19 stay excluded" replaced; the act leaves X19's status relative to CV9 / CB4 to §6 item 1 |
| I3 | §0 step 2a and §2.9 prerequisites: the re-derivation is performed with the participant provisionally inside CG1 (as T3 §1b), re-checked after VP12 |
| I4 | §2.7 discounts and table: granularity limited to P-a, P-b, P-c; P-d's row-6 difference (two sides vs three) listed in the Result |
| I5 | header, §2.7 heading and Result: "after even discounts the neutral criteria give no ordering of the four cuts without weights"; P-a vs P-b "not separated" |
| I6 | §2.4 row 1 and §2.7: the path discount rests on path dependence alone (H-D-congenial wording removed there; it stays in row 7); discounts labelled [E] |
| I7 | §1.4 (ii) row, §1.6 and the (ii) act: C25, C26 and C28 lose their placement (T3 §6 "Every C1–C28 placed") |
| I8 | §0 edge table, §4.3 and §6 item 6: edge VP4 → VP11 added |
| I9 | §6 item 2: an explicit "left open" (or decided) record; §1.6: "removed from the model, not merely ungrouped" quoted exactly (prior package §2.6 act wording) |
| I10 | §8 H2 row: stale "step 1" reference corrected; §2.7 "Scope" line explains why "no boundary layer" and "leave open" are outside the comparison |
| I11 | §3.3 VP12 (i), §4.4 VP11 (ii) and §6 item 9: the §2f status change treated alike; the leave-open act's effect waits for the E2b reopening act (§2.8); §7 "E2b effects" updated |
| I12 | §2.8 re-confirm act: cross-reference to the [E] inconsistency with VP10 (i) (§1.4, §6 item 3) |

**r4.1 map (J1–J3; fifth fresh review of r4: PASS_WITH_LIMITATIONS).**

| finding | fix (location) |
|---|---|
| J1 (residue of I2) | §1.3 right cell: "X19 … stays excluded under every option" → X19's status under (i) is part of §6 item 1; excluded under (ii) and (iii) |
| J2 | §2.4 provenance: row-6 granularity restricted to P-a, P-b, P-c (not against P-d), per I4 |
| J3 | §1.8 VP10 (ii) act: "This revisits F-LOG-0258." added for symmetry with (i)/(iii); no decision content changed |
| note | header input path to F-GOVERNANCE-LOG.md made explicit |
