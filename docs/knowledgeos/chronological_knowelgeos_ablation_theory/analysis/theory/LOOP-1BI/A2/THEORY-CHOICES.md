# THEORY-CHOICES (r3, repair r2)

SCHEMA-r3 gives every coding question an answer, or UNKNOWN. Six questions remain that are **theoretical, not coding, questions**. `[r2-fix-TC7]` r2 adds a seventh (TC-7). Answering any of them would decide part of what H-D and H-B disagree about. r3 does not answer them. Instead, it records the distinguishing information as tags, so that each alternative below can be computed from the coded data at analysis time. The analysis must state which alternative it uses, and should report results under each alternative.

r1 kept this tag-and-compute approach. It corrected TC-1 (the negative case was missing and the r0 description was inaccurate), extended TC-2 and TC-4, and added TC-5 and TC-6. TC-5 and TC-6 are the choices that r0 had built into D11 and D8 (ii) without listing them.

`[r2-fix-Na]` `[r2-fix-TC2c]` `[r2-fix-TC2b]` `[r2-fix-PT]` `[r2-fix-TC7]` r2 makes TC-1 1c computable for every gated candidate, corrects the TC-2 2c recipe, gives TC-2 2b a join tag, makes the `performative_token` definition behind TC-4 4c consistent, and adds TC-7 for the mechanism-refusal decision (N-d). Changes are marked `[r2-fix-<id>]`; ids are listed in CHANGELOG-r2.md.

---

## TC-1 (G1): Does an instrument's self-performance count as an act? `[r1-fix-B1]` `[r1-fix-N6]`

This choice is **inherited from r2, not made by r3**. r2's R-A3 already makes one choice: a register row that self-performs one of five listed norm-making operations is ACT_OCCURRED. That choice was flagged as building H-D's view into the observations. r3 restricts R-A3 to its literal text (closed list, register rows only, performative in the span) and does not extend it. It tags every act with `act.basis`.

**r1 correction: negative dispositions.** r0 coded a span's own refusal, withdrawal or reversal of **any** operation, in **any** section, as an act, while it limited positive self-performance to the five listed operations in register rows. That was alternative 1c applied to negative acts only, and it was not disclosed. r1 scopes negative and positive self-performance **identically** (SCHEMA R-A3 (b+)/(b−), R-A5 step 2):
- inside the scope (register row, listed operation, in the span), both are acts: ACT_OCCURRED / ACT_ATTEMPTED for positives, ACT_REFUSED / ACT_WITHDRAWN / ACT_REVERSED for negatives;
- outside the scope, both are `act.state = NONE`, `basis = SELF_PERFORMED_OUTSIDE_RA3`, with the would-be state in `act.state_if_extended`.

The TASK offered two symmetric options. r1 chose this one because it changes r2 least: r2's R-A3 first sentence (listed positive self-performance in register rows is ACT_OCCURRED) and r2's R-A2 (refusals are acts when a proposal is identified) both survive. The other option, coding all self-performance as NONE, would delete R-A3's first sentence. That option remains computable as alternative 1b.

`[r2-fix-Na]` **r2 correction: the would-be state is defined for both polarities.** In r1, rank 3 read "REPORTED, or R-A3 (a)–(c) hold". Applied to a gated *positive* act, that gave no allowed value, while a gated *negative* act got a rank-1 value. Read literally, 1c therefore counted extended negative acts but not extended positive ones. Rank 3 now reads "REPORTED, or a SELF-PERFORMANCE (D3)", and the scope gate alone enforces R-A3. Default `act.state` is unchanged, because only self-performances that pass the gate reach rank 3 in default coding. Every gated candidate, including those that lose at step 4, is now recorded with its would-be state in `act.gated_candidates`.

`[r2-fix-Na]` **Note on named-ruling withdrawals and reversals.** When the complement of a withdrawal or reversal is a noun ("R-90 … WITHDRAWN"), X is *withdraw* or *reverse* (SCHEMA R-A5 step 1), which is never a listed lemma. A register row's own withdrawal or reversal of a named ruling is therefore always gated under 1a, while a REPORTED withdrawal of the same ruling (e.g. R06/R07, in a session log) is an act. This follows from the closed list in both polarities alike; 1c restores the gated cases.

| Alternative | What counts as an act | Consequence for what the data can show |
|---|---|---|
| **1a. r2 as written (r3 default coding)** | `basis = REPORTED` (any operation, positive or negative, any section) + `basis = SELF_PERFORMED` (a register row's own performance **or own refusal / withdrawal / reversal** of a listed operation) | Norm-making acts are observed wherever a register row performs, or negatively disposes of, a listed operation. Every such act is a register act by construction, so "norms change through acts" is partly true by definition for register rows. That part cannot count as evidence for H-D over H-B. Positive and negative dispositions are treated alike: R-77's "ADR-T14 is not superseded" is an act (ACT_REFUSED supersede), like a row that supersedes. In R-36, "Promoted into …" and "Expressly NOT promoted: …" are **both** non-acts. |
| **1b. Reported acts only** | `basis = REPORTED` | The data show only acts that some text reports as having happened. The register's own rulings, positive and negative, stop being act observations, so norm-making acts become rare. This **under-counts** what both hypotheses agree exists. It weakens tests of H-D's "norms change only through acts", because many norm changes then have no observed act. `[r2-fix-PV]` Reported provenance or location mentions ("already ARB-adopted in Handover §9", "documented in …") are REPORTED acts and survive under 1b. |
| **1c. Extended self-performance** | 1a + ~~`basis = SELF_PERFORMED_OUTSIDE_RA3`, taking the state from `act.state_if_extended`~~ `[r2-fix-Na]` every entry of `act.gated_candidates`, at its `state_if_extended` | Classifications, recommendations, applications, approvals, retirements and matrix verdicts all become acts, **and so do their negative counterparts** ("Expressly NOT promoted" → ACT_REFUSED promote, if a proposal is stated; else UNKNOWN). R01 ("declines to resolve …") becomes UNKNOWN, because no proposal to resolve is stated (R-A2). Act counts rise most for governance-text operations. This extends the flagged H-D-favouring convention further, in both directions alike. |
| **1d. Reproduced rulings as reported** (modifier on 1a / 1c) | As 1a or 1c, but records with `act.attributed_to` filled are treated as REPORTED | Rows that reproduce an issuer's words ("RULING AS ISSUED, VERBATIM", "IN THE BOARD'S OWN WORDS", "(Principal Architect instruction)") count as reports of an act by that issuer, not as the row's own act. Under 1b + 1d these rows (R02, R23, R26) **survive** as act observations. Under 1b alone they do not. The default (D3) treats them as self-performances, because the reproduced text is the row's own ruling. |

All four are computable from `act.basis`, `act.state_if_extended`, `[r2-fix-Na]` `act.gated_candidates`, `act.attributed_to` and `act.operation`. r3's coded `act.state` follows 1a only because that is the unrevised r2 text, now applied symmetrically.

`[r2-fix-Na]` **Recipe for 1c.** For each record, restore every entry of `act.gated_candidates` at its `ext_rank`, with state `state_if_extended`, and re-apply SCHEMA R-A5 step 4 against the record's coded ungated candidate (if any), at the rank implied by its `act.state`: highest rank wins, then first in span (positions from `quote` within `span`). Gated candidates that lost at step 4 in default coding are included, so a record whose default act is a REPORTED rank-3 act (e.g. R18, R20) can be compared with its gated *classify* / *recommend* candidates.

---

## TC-2 (G2): Are behaviours named in an adopted instrument substantive norms the record carries? `[r1-fix-C3]`

Example: a promotion matrix adopted by a register ruling recommends that a named behaviour be promoted. Is the record then carrying a norm on that **behaviour** (e.g. REQUIRED: reuse before create), or only a verdict on the **meta-act** (ELIGIBLE: promote)?

r3 codes by literal attachment (R-N1). The verdict is coded on *promote*. The behaviour is MENTIONED (D11) and goes in `about_target`. A status label on the behaviour ("Remains Candidate", "Stays in ADR-MP-06", a category letter) is coded as a status label on the behaviour (`status: <label>`), not as a verdict on *promote* (D12, r1). r3 does **not** decide whether adoption of the instrument incorporates the mentioned behaviour as an in-force substantive norm.

| Alternative | Consequence |
|---|---|
| **2a. No incorporation.** Only stated norms count. | The data show the record carrying norms **about governance meta-acts** wherever a behaviour is handled by promotion or adoption. They show substantive norms only where a span states them. This looks closer to H-D's "the governed work is outside what the record needs to contain". The cause is partly that the substantive text lives in other documents, which this rule does not follow. |
| **2b. Incorporation by reference.** An adopted instrument's positive verdicts count as substantive norms on the mentioned behaviour. | The data show the record carrying norms on governed work, which looks closer to H-B. This requires an inferred deontic value that no span states (REQUIRED? PERMITTED?), so those observations would be INFERENCE throughout. |
| **2c. Status labels in a review read as verdicts** (r1; modifier on 2a / 2b) | Status labels that a review matrix assigns to a candidate ("Remains Candidate", category C / D) are read as INELIGIBLE *promote*. This is the reading r0 built into D12. It moves those records from the behaviour level to the meta-act level, toward H-D's framing. r1's default keeps the literal level. |

All are computable.
- `[r2-fix-TC2b]` **2b recipe.** Take records with `norm.deontic = ELIGIBLE`, `norm.basis = VERDICT` and a non-empty `norm.adopted_via`. Join each to the record(s) whose `section` is `## <adopted_via>` and whose `act.operation = adopt`. Count the incorporated substantive norm only if the joined act has `act.state = ACT_OCCURRED` (or, if the analysis so chooses, apply its TC-3 alternative). The substantive deontic value is an INFERENCE that the analysis must state once, for all such records. If no joined record exists in the coded set (in this dataset, the R-36 adoption span is not among the 36 records), the join fails. The analysis must then report the record as not computable under 2b rather than assume the adoption.
- `[r2-fix-TC2c]` ~~2c = records in a section carrying disposition verdicts, with `basis = STATUS_PREDICATE` (primary clause or in `norm.other_clauses`), recoded as INELIGIBLE.~~ **2c recipe.** Recode a record if and only if all four hold:
  1. its section contains at least one record with a VERDICT clause (primary or in `norm.other_clauses`);
  2. its **primary** clause has `basis = STATUS_PREDICATE`;
  3. it has **no** VERDICT clause, primary or in `norm.other_clauses`;
  4. its primary `status: <label>` is in a label set L that the analysis states before computing.

  The recoded value is set by the label's gloss in the source's own legend: a label whose gloss names the meta-operation positively becomes ELIGIBLE; every other label in L becomes INELIGIBLE. `about_operation` becomes the meta-operation, and `about_target` stays the behaviour. For the R-36 matrix the legend gives: A, B ("promote to …") → ELIGIBLE; C ("stays platform documentation"), D ("remains Candidate Pattern"), "already" ("permanent guidance today; re-adding = duplication"), and the continuative labels "Remains Candidate" / "Stays in …" → INELIGIBLE. Category A on R15–R18 is never recoded, because those records carry a VERDICT clause (condition 3). Labels outside L are not recoded.

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

**r1 addition.** An *authorize* performative is coded AUTHORIZED with `basis = OPERATOR` (R-N3 step 5). A bare "Slice 7C AUTHORIZED" is therefore as definitionally paired with its act as "ANNOTATE is adopted", but r0 gave no way to exclude it. r1 adds `norm.performative_token`. A **bare** authorize performative also has no governed operation stated in the span.

`[r2-fix-PT]` **r2 correction.** r1 defined `performative_token` as true when the normative word performs "the record's **R-A3** act", and also said it "covers every PERFORMATIVE_ONLY clause". The two disagreed for clauses on records with no R-A3 act: a gated row, a REPORTED adoption ("already ARB-adopted", R18), or "NOT ADOPTED" on a held ruling (R03). r2 defines it by the text alone. It is true whenever the clause's normative word is **itself an act expression in the span** (a performative, a negative disposition, or the verb or participle by which an act is reported), whether that act is coded on the record, gated, or REPORTED. Every PERFORMATIVE_ONLY clause and every authorize performative is then true, on any record. It is recorded on `other_clauses` entries too, so 4b / 4c can fall back to the next clause.

| Alternative | Consequence |
|---|---|
| **4a. Count them.** | Every such act generates a co-located norm. Act–norm co-occurrence is then partly definitional, and cannot be used as evidence for H-D's "norms change only through acts". |
| **4b. Exclude bare performatives.** Exclude `basis = PERFORMATIVE_ONLY` and bare authorize performatives. | Norm counts contain only norms with some textual content beyond the performative. "AUTHORIZED TO PLAN AND IMPLEMENT" stays, because it names the governed operations. |
| **4c. Exclude every performative-token norm.** Exclude every ~~record~~ `[r2-fix-PT]` clause with `performative_token = true`. | The strictest form: no norm that is stated by the same word that performs `[r2-fix-PT]` or reports an act is counted, even with a named governed operation. Authorizations, the most common operation in this register, then appear as norms only where a separate clause states them. |

Under 4b and 4c, a record whose primary clause is excluded takes, as its norm, the first entry of `norm.other_clauses` that is not excluded, if there is one. All three are computable from `norm.basis`, `norm.performative_token` (`[r2-fix-PT]` primary and per `other_clauses` entry), `about_operation` status and `norm.other_clauses`.

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

## TC-7 (new in r2): Is a mechanism's refusal of an attempt a disposition or a failed attempt? `[r2-fix-TC7]` `[r2-fix-Nd]`

In r1, an attempt that a mechanism rejected ("append --json='{"type":"CLOSE",…}' → refused: unknown transition type 'CLOSE' …, exit 65, record unchanged", S0815) matched rank 1 through the refusal verb. Rank 1 then required a *proposal*, so the record became UNKNOWN and dropped out of support counts. Actor refusals of stated proposals did not drop out. The loss fell on work-failure observations, which are H-B material (review N-d).

r2 removes the loss and discloses the choice it makes. **It codes these as ACT_ATTEMPTED with `attempt_kind = FAILED` where a stated attempt exists, and it also tags them**, so the other reading is computable:
- A MECHANISM RESPONSE (SCHEMA D6) is a non-effect marker of the attempt, never a rank-1 disposition. With a stated attempt it is ACT_ATTEMPTED / FAILED, `act.mechanism_refusal = true`.
- Without a stated attempt it is UNKNOWN (R-A4), still tagged `act.mechanism_refusal = true`, so it can be counted separately.
- A named actor's refusal expression stays a rank-1 disposition. With the rank-1 cell now reading "proposal **or attempt**" (R-A2), an actor's refusal of a stated attempt is ACT_REFUSED, not UNKNOWN.

*Why this default:* R-A2 and D5 describe a refusal as a disposition by a decider. A program's output is not a decision on a proposal. It is the source's statement that the attempt did not take effect, which is exactly D6's FAILED marker, and D6 already cites "refused: …" as its example.

| Alternative | Consequence |
|---|---|
| **7a. Failed attempt (r3 default coding)** | Mechanism rejections are work failures: the attempt is observed, and its non-effect is recorded. Work outcomes that H-B expects to feed back into norms ("failure") are visible as attempts. |
| **7b. Disposition** | Records with `act.mechanism_refusal = true` and `act.state = ACT_ATTEMPTED` are read as ACT_REFUSED of the same (operation, target). Mechanism rejections then count alongside governance refusals, among disposition acts. This treats a governed mechanism's rule enforcement as a norm-applying act. |

Both are computable from `act.mechanism_refusal`, `act.state` and `act.attempt_kind`. Under both, the tagged UNKNOWN records can be reported as a separate count.

---

No other gap required a theoretical choice. G3 (priority) and G5 (REQUIRED vs STATUS_CONSTRAINT) are closed on observational and lexical grounds; see RATIONALE.md. The tie-break rules added in r1 (first in span order, for act candidates and norm clauses) are mechanical. They lose no information, because the unchosen candidates are kept in `SPLIT_REQUIRED`, `norm.other_clauses` and `[r2-fix-Na]` `act.gated_candidates`.

`[r2-fix-GM]` `[r2-fix-PV]` `[r2-fix-DN]` `[r2-fix-PC]` The other r2 determinacy rules are not theory choices: the closed ground-marker list (D15), counting provenance and location mentions as candidates, reading an imperative of the verdict's own verb as a verdict in both polarities, and treating class labels as status predicates. Each is lexical, applies to both hypotheses' material alike, and is justified in RATIONALE.md. Where one of them changes which act a record shows (R18, R20), the alternative remains computable from `act.gated_candidates` (TC-1 1c recipe).
