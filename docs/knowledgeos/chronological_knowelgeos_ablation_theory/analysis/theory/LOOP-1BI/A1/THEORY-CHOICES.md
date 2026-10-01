# THEORY-CHOICES (r3, repair r1)

SCHEMA-r3 gives every coding question an answer, or UNKNOWN. Six questions remain that are **theoretical, not coding, questions**. Answering any of them would decide part of what H-D and H-B disagree about. r3 does not answer them. Instead, it records the distinguishing information as tags, so that each alternative below can be computed from the coded data at analysis time. The analysis must state which alternative it uses, and should report results under each alternative.

r1 keeps this tag-and-compute approach. It corrects TC-1 (the negative case was missing and the r0 description was inaccurate), extends TC-2 and TC-4, and adds TC-5 and TC-6. TC-5 and TC-6 are the choices that r0 had built into D11 and D8 (ii) without listing them.

---

## TC-1 (G1): Does an instrument's self-performance count as an act? `[r1-fix-B1]` `[r1-fix-N6]`

This choice is **inherited from r2, not made by r3**. r2's R-A3 already makes one choice: a register row that self-performs one of five listed norm-making operations is ACT_OCCURRED. That choice was flagged as building H-D's view into the observations. r3 restricts R-A3 to its literal text (closed list, register rows only, performative in the span) and does not extend it. It tags every act with `act.basis`.

**r1 correction: negative dispositions.** r0 coded a span's own refusal, withdrawal or reversal of **any** operation, in **any** section, as an act, while it limited positive self-performance to the five listed operations in register rows. That was alternative 1c applied to negative acts only, and it was not disclosed. r1 scopes negative and positive self-performance **identically** (SCHEMA R-A3 (b+)/(b−), R-A5 step 2):
- inside the scope (register row, listed operation, in the span), both are acts: ACT_OCCURRED / ACT_ATTEMPTED for positives, ACT_REFUSED / ACT_WITHDRAWN / ACT_REVERSED for negatives;
- outside the scope, both are `act.state = NONE`, `basis = SELF_PERFORMED_OUTSIDE_RA3`, with the would-be state in `act.state_if_extended`.

The TASK offered two symmetric options. r1 chose this one because it changes r2 least: r2's R-A3 first sentence (listed positive self-performance in register rows is ACT_OCCURRED) and r2's R-A2 (refusals are acts when a proposal is identified) both survive. The other option, coding all self-performance as NONE, would delete R-A3's first sentence. That option remains computable as alternative 1b.

| Alternative | What counts as an act | Consequence for what the data can show |
|---|---|---|
| **1a. r2 as written (r3 default coding)** | `basis = REPORTED` (any operation, positive or negative, any section) + `basis = SELF_PERFORMED` (a register row's own performance **or own refusal / withdrawal / reversal** of a listed operation) | Norm-making acts are observed wherever a register row performs, or negatively disposes of, a listed operation. Every such act is a register act by construction, so "norms change through acts" is partly true by definition for register rows. That part cannot count as evidence for H-D over H-B. Positive and negative dispositions are treated alike: R-77's "ADR-T14 is not superseded" is an act (ACT_REFUSED supersede), like a row that supersedes. In R-36, "Promoted into …" and "Expressly NOT promoted: …" are **both** non-acts. |
| **1b. Reported acts only** | `basis = REPORTED` | The data show only acts that some text reports as having happened. The register's own rulings, positive and negative, stop being act observations, so norm-making acts become rare. This **under-counts** what both hypotheses agree exists. It weakens tests of H-D's "norms change only through acts", because many norm changes then have no observed act. |
| **1c. Extended self-performance** | 1a + `basis = SELF_PERFORMED_OUTSIDE_RA3`, taking the state from `act.state_if_extended` | Classifications, recommendations, applications, approvals, retirements and matrix verdicts all become acts, **and so do their negative counterparts** ("Expressly NOT promoted" → ACT_REFUSED promote, if a proposal is stated; else UNKNOWN). R01 ("declines to resolve …") becomes UNKNOWN, because no proposal to resolve is stated (R-A2). Act counts rise most for governance-text operations. This extends the flagged H-D-favouring convention further, in both directions alike. |
| **1d. Reproduced rulings as reported** (modifier on 1a / 1c) | As 1a or 1c, but records with `act.attributed_to` filled are treated as REPORTED | Rows that reproduce an issuer's words ("RULING AS ISSUED, VERBATIM", "IN THE BOARD'S OWN WORDS", "(Principal Architect instruction)") count as reports of an act by that issuer, not as the row's own act. Under 1b + 1d these rows (R02, R23, R26) **survive** as act observations. Under 1b alone they do not. The default (D3) treats them as self-performances, because the reproduced text is the row's own ruling. |

All four are computable from `act.basis`, `act.state_if_extended`, `act.attributed_to` and `act.operation`. r3's coded `act.state` follows 1a only because that is the unrevised r2 text, now applied symmetrically.

---

## TC-2 (G2): Are behaviours named in an adopted instrument substantive norms the record carries? `[r1-fix-C3]`

Example: a promotion matrix adopted by a register ruling recommends that a named behaviour be promoted. Is the record then carrying a norm on that **behaviour** (e.g. REQUIRED: reuse before create), or only a verdict on the **meta-act** (ELIGIBLE: promote)?

r3 codes by literal attachment (R-N1). The verdict is coded on *promote*. The behaviour is MENTIONED (D11) and goes in `about_target`. A status label on the behaviour ("Remains Candidate", "Stays in ADR-MP-06", a category letter) is coded as a status label on the behaviour (`status: <label>`), not as a verdict on *promote* (D12, r1). r3 does **not** decide whether adoption of the instrument incorporates the mentioned behaviour as an in-force substantive norm.

| Alternative | Consequence |
|---|---|
| **2a. No incorporation.** Only stated norms count. | The data show the record carrying norms **about governance meta-acts** wherever a behaviour is handled by promotion or adoption. They show substantive norms only where a span states them. This looks closer to H-D's "the governed work is outside what the record needs to contain". The cause is partly that the substantive text lives in other documents, which this rule does not follow. |
| **2b. Incorporation by reference.** An adopted instrument's positive verdicts count as substantive norms on the mentioned behaviour. | The data show the record carrying norms on governed work, which looks closer to H-B. This requires an inferred deontic value that no span states (REQUIRED? PERMITTED?), so those observations would be INFERENCE throughout. |
| **2c. Status labels in a review read as verdicts** (r1; modifier on 2a / 2b) | Status labels that a review matrix assigns to a candidate ("Remains Candidate", category C / D) are read as INELIGIBLE *promote*. This is the reading r0 built into D12. It moves those records from the behaviour level to the meta-act level, toward H-D's framing. r1's default keeps the literal level. |

All are computable. 2b = records with `norm.deontic = ELIGIBLE` and `basis = VERDICT`, joined to the adopting act's record. 2c = records in a section carrying disposition verdicts, with `basis = STATUS_PREDICATE` (primary clause or in `norm.other_clauses`), recoded as INELIGIBLE.

---

## TC-3 (G4): Does a norm stated by a non-effective (PREPARED) act have force?

r3 codes the act as ACT_ATTEMPTED / PREPARED. It codes the stated content (e.g. AUTHORIZED) as a NORM, with `stated_by` carrying the PREPARED marker. It does not code **force**.

| Alternative | Consequence |
|---|---|
| **3a. Force follows the making act's status.** PREPARED norms are not in force until a later act gives them effect. | The data can test whether work conforms to *effective* norms only. A prepared authorization followed by work would count as non-conformity. |
| **3b. Force follows stated content.** | Prepared and adopted norms are treated alike. Any difference between them, the thing H-D predicts matters, cannot appear in the analysis. |
| **3c. Force undecided; report separately.** | The cost is smaller cells. The difference between prepared and adopted norms is shown, not assumed. |

Choosing 3a in the schema would build H-D's second bullet ("force depends on the status of the act that made it") into the observations. Choosing 3b would rule it out by definition.

---

## TC-4 (G6): Do performative-only norms count as norm observations? `[r1-fix-N3]`

r2's R-A3 second sentence requires a NORM on every self-performing row. When a row's only normative words are the performative itself ("ANNOTATE is adopted", "adopts R-81 through R-85", "ADR-T14 is not superseded"), r3 codes STATUS_CONSTRAINT with `norm.basis = PERFORMATIVE_ONLY`.

**r1 addition.** An *authorize* performative is coded AUTHORIZED with `basis = OPERATOR` (R-N3 step 5). A bare "Slice 7C AUTHORIZED" is therefore as definitionally paired with its act as "ANNOTATE is adopted", but r0 gave no way to exclude it. r1 adds `norm.performative_token`, which is true for every clause whose normative word is the act's own performative token, including authorize performatives. A **bare** authorize performative also has no governed operation stated in the span.

| Alternative | Consequence |
|---|---|
| **4a. Count them.** | Every such act generates a co-located norm. Act–norm co-occurrence is then partly definitional, and cannot be used as evidence for H-D's "norms change only through acts". |
| **4b. Exclude bare performatives.** Exclude `basis = PERFORMATIVE_ONLY` and bare authorize performatives. | Norm counts contain only norms with some textual content beyond the performative. "AUTHORIZED TO PLAN AND IMPLEMENT" stays, because it names the governed operations. |
| **4c. Exclude every performative-token norm.** Exclude every record with `performative_token = true`. | The strictest form: no norm that is stated by the same word that performs the act is counted, even with a named governed operation. Authorizations, the most common operation in this register, then appear as norms only where a separate clause states them. |

Under 4b and 4c, a record whose primary clause is excluded takes, as its norm, the first entry of `norm.other_clauses` that is not excluded, if there is one. All three are computable from `norm.basis`, `norm.performative_token`, `about_operation` status and `norm.other_clauses`.

---

## TC-5 (new in r1): Do norms cited as grounds, or reported from another actor, count? `[r1-fix-M4]`

r0's D11 treated content "cited as evidence" as MENTIONED in **every** span, and STATED as content the instrument "asserts as its own". That could drop every norm a session log or commit reports, and every norm given as a ground, leaving norms mainly in governance instruments (review item N5). r1 restricts D11 to spans carrying a disposition verdict (the G2 case). Everywhere else, every norm clause the span states is coded, as under r2, and ground clauses are tagged `norm.cited_as_ground = true`.

| Alternative | Consequence |
|---|---|
| **5a. Count all stated norms (r3 default coding)** | Norms in session logs, commits and ground clauses are observed ("ES-006.1 forbids promoting from one"; "the register holds constitutional decisions"). Norms then appear also in the narration of work, which gives H-B's "the record carries or references both" more material. |
| **5b. Exclude ground clauses** (`cited_as_ground = true`) | Norms invoked only as reasons drop out. The record's norms are then those it asserts directly. |
| **5c. Exclude norms in non-register sections** (by `section`: session logs, commits, plans) | Norms appear only in governance instruments. This looks closer to H-D's picture by construction, which is why r1 does not code it as the default. |

All three are computable from `norm.cited_as_ground` and `section`.

---

## TC-6 (new in r1): Do prospective indicatives about work count as norms? `[r1-fix-M5]`

r0's D8 (ii) made every present- or future-tense indicative about whether an operation takes place a status predicate: "it APPLIES §12", "no observable behaviour changes until 7C", "Implementation continues under …" (review item N4). r1 narrows D8 (ii) to the single case r2 itself names in R-A1, a non-commencement statement ("implementation does not begin"). The other indicatives are not coded as norms. They are listed, with their operation, in `norm.indicative`.

| Alternative | Consequence |
|---|---|
| **6a. Narrow (r3 default coding)** | Only non-commencement statements are norms (STATUS_CONSTRAINT). Descriptive and predictive statements about work are not. Norms about work may be under-counted where the record states them descriptively. |
| **6b. Broad** | Every `norm.indicative` entry counts as STATUS_CONSTRAINT on its operation. Norm counts on governed work rise, so norm–work coupling, which H-B predicts, appears more often. Some of these statements are predictions or descriptions, not norms. |

Both are computable from `norm.indicative`.

---

No other gap required a theoretical choice. G3 (priority) and G5 (REQUIRED vs STATUS_CONSTRAINT) are closed on observational and lexical grounds; see RATIONALE.md. The tie-break rules added in r1 (first in span order, for act candidates and norm clauses) are mechanical. They lose no information, because the unchosen candidates are kept in `SPLIT_REQUIRED` and `norm.other_clauses`.
