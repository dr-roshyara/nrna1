# Independent review of ANSWER.json / ANSWER.md

Evidence base for this review: `TASK.md`, `SOURCES.md` (R-71, R-81, R-86, R-91), `ANSWER.json`, `ANSWER.md`. Nothing else was used.

## 1. Summary

- **Evidence scope.** A used only permitted evidence. A mentions R-87, R-88, R-90 and the ARB Constitutional Review report only as referenced-but-unsupplied, and lists them as UNKNOWN. That is correct.
- **Quotes.** Every quote I checked is verbatim, allowing for markdown emphasis and marked ellipses. No quote is fabricated. Some quotes are attached to the wrong field or are weak for the value they carry (D3, D4). The outcome values are not quoted (D5).
- **Silent inference.** A did **not** infer P's conformance from the absence of a collapse statement. A cites positive statements and says openly that the evidence-submission role is unstated for P ("A strict reader may code UNK"). So the inference is disclosed, not silent. Kind equality rests on both objects being called "rulings" in the text. The text distinguishes the two objects only at a finer grain (register headers) that the task's example taxonomy does not use. A disclosed this as well (A2).
- **Category confusion.** A keeps object kind separate from act kind, held separate from refused/withdrawn, and the ground of the hold separate from the later remedy. There is one small slip: "prepared" is written into the `kind` value, which mixes kind with state_before (D3).
- **Classification.** Under my own corrected coding I get the **same** result as A: **conformance STRICT, kind NONE**. The STRICT result is fragile. It depends on reading "independent review completed" as stating that P's review was separated from evidence submission. The text neither confirms nor rules out that reading.

## 2. Per-field findings

### P — adoption of R-81..R-85 (R-86)

| Field | A | Reviewer | Finding |
|---|---|---|---|
| object | R-81..R-85 | same | Quote is verbatim and supports the value. |
| kind | "ruling (prepared ruling)" | **ruling** | All three quotes are verbatim. "FIVE NAMED RULINGS" and "PREPARED RULINGS AWAITING ADOPTION" state the kind for all five. The parenthetical "prepared" is a state, not a kind (D3). A correctly separates the object's kind (ruling) from the act's kind (R-86: "Programme Governance · Adoption", "a constitutional act"). |
| actor | Decision Authority | same | Verbatim; supports the value. |
| state_before | PREPARED | same | Both quotes are verbatim and support the value. |
| conformance | CONFORMANT | **CONFORMANT (partial; fragile)** | The quotes are verbatim, but their support is uneven (D1, D2). "delegated CHAIRING AND PREPARATION, not adoption" supports only preparer ≠ adopter. "The Chief declines to resolve an ambiguity about the Chief's own authority in the Chief's own favour" is the preparer's statement about itself, and it also concerns only preparation vs adoption. The quote that carries the value on Q's collapse axis (evidence submission vs review) is **"independent review completed"** in the Decision Authority's grounds. A lists it but does not single it out. Who submitted the evidence for P is not stated anywhere. Supporting quotes A missed are listed in §2.3. |
| outcome | PERFORMED | same | **Not quoted** (D5). Available quotes: "✅ ADOPTED 2026-08-04 BY THE DECISION AUTHORITY, WITHOUT AMENDMENT (adoption act: R-86)"; "R-81..R-85 GOVERNING". |
| ground | CHOICE | CHOICE (not decisive) | Verbatim. The adoption was chosen among Options A/B/C with reasons. Some grounds read like rule-type conditions: "Option B declined: it is appropriate only if a ruling is incorrect or insufficiently supported". The comparison does not use P's ground (D10). |

### Q — adoption of R-91 (held)

| Field | A | Reviewer | Finding |
|---|---|---|---|
| object | R-91 | same | Verbatim (header, with ellipsis). |
| kind | "ruling (prepared ruling)" | **ruling** | The quotes are verbatim. The header quote "Architecture Governance · Determination on submitted evidence · ARB CHIEF (PREPARED)" does not contain the word "ruling". It supports a finer-grain kind, not the coded value. "this ruling collapsed the two" uses the word, but it is a conformance statement. The best kind quote is "A PREPARED ruling was still engineering deciding the outcome", and even that one mainly supports conformance (D4). The value "ruling" is still stated: the hold calls R-91 "this ruling" and "A PREPARED ruling". |
| actor | Decision Authority | same | Verbatim: "The Decision Authority held it on the ground that …". |
| state_before | PREPARED | same | Verbatim: "PROVENANCE: ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED". |
| conformance | COLLAPSED | same | Verbatim. The text states the collapse explicitly. A further supporting quote is attached to kind instead: "A PREPARED ruling was still engineering deciding the outcome, in softer wording." |
| outcome | REFUSED/HELD | same (HELD) | Not separately quoted (D5), but A's ground quote "HELD 2026-08-04 — NOT ADOPTED" covers it. A correctly distinguishes held from withdrawn: "HELD, not withdrawn", with the explicit contrast with R-90. |
| ground | RULE | same | Verbatim. "NOT ELIGIBLE FOR ADOPTION AS ISSUED" and "Event D SEPARATES …" give a structural rule, not discretion. A correctly keeps the later remedy ("superseded in FORM only, by the ARB Constitutional Review") out of the ground. It also correctly keeps the routing-error correction out of the ground. |

### 2.3 Missed quotes

Missed quotes bearing on P's conformance:
- R-86: "the five rulings' own recorded evidence, unaltered by this act".
- R-86: "it **reopens no crash-model reasoning** and reinterprets no evidence".
  - Both state that the decider did not alter the evidence.
- R-81: "the ARB authorizes the slice, engineering selects the construct". This states a separation, though it is between decision and implementation, not evidence and review.
- R-81 annotation: "self-issued rulings must meet the identical test".

Missed quotes bearing on a COLLAPSED alternative for P:
- R-81: "these five were filed by the Chief acting on a delegated session mandate".
- R-81: "“BATCH 7 IS RELEASED” DOES NOT HOLD".
  - Together these show that P's object was first put forward in a self-issued form. The text then records the correction as done: "provenance corrected" (R-86); "The prepared/adopted distinction it carried is now RESOLVED; the condition it imposed is discharged" (R-81).

Other missed quotes:
- **Q, state_before and the link to R-86:** R-91: "(R-86/R-87 created no standing delegation; R-88 was a single act)".
- **Outcome, both events:** see the tables above.

### 2.4 ANSWER.md labelling

- **D6.** "This is why R-91 was in the PREPARED state" is labelled SOURCE FACT. It is an inference, although R-91's own parenthetical supports it.
- **D7.** The R-71 paragraph says R-71 "supports reading Q's ground as RULE rather than CHOICE by analogy". R-71 is cited by neither R-86 nor R-91. It concerns Delivery Governance acceptance of WP-7B-R1, and Q's RULE is fully grounded in R-91's own text. R-71 should be recorded as bearing on **no** coded value. At most it shows that the register records the same kind of principle elsewhere: "Engineering does not self-certify it."

## 3. Recomputed classification (reviewer coding)

| Compared field | P | Q | Known/equal? |
|---|---|---|---|
| kind | ruling | ruling | known, equal |
| actor | Decision Authority | Decision Authority | known, equal |
| state_before | PREPARED | PREPARED | known, equal |
| conformance | CONFORMANT (partial) | COLLAPSED | known, different |

The rule's preconditions hold: the outcomes are opposite (PERFORMED vs HELD), and Q's ground is RULE, so it is not CHOICE.

- **witness_conformance = STRICT.** Conformance is known and different, and every other compared field is known and equal.
- **witness_kind = NONE.** Kind is known and equal.
- **Reason the text gives for Q's outcome:** conformance, i.e. the collapse of evidence submission and constitutional review: "held it on the ground that Event D SEPARATES evidence submission from constitutional review, and this ruling collapsed the two". The routing-error passage is introduced as "AND A SUBSTANTIVE CORRECTION TO ITS OWN FINDING". The text does not give it as the ground.

Why I keep CONFORMANT for P rather than UNK: the field asks whether the separation is *stated*. For P, the text states:
- preparer ≠ adopter ("delegated CHAIRING AND PREPARATION, not adoption");
- an independent review step ("independent review completed");
- a decider that leaves the evidence unaltered ("reinterprets no evidence").

"Independent review" is the direct opposite of Q's defect, which was a review step occupied by the party that submitted the evidence. This is a positive statement, not a default drawn from silence. It is only *partial*, because the evidence submitter and the reviewer are never named.

## 4. Strongest alternatives

1. **P.conformance = UNK.** The evidence-submission role is unstated for P, and "independent" does not say independent *of whom*. Under this coding, conformance → **NONE** and kind → NONE.
   - **Not ruled out by the text.** This is the strongest alternative that would change the conformance classification.
2. **Kind at the register's own finer grain (header: domain · act type).**
   - R-81 is "Execution Governance · Authorization"; R-91 is "Architecture Governance · Determination on submitted evidence". The headers of R-82..R-85 are not supplied.
   - If P's finer kind is UNK: conformance → **POSSIBLE**, kind → NONE.
   - If R-81 stands for P: kind and conformance are both known and different, so both → **NONE**.
   - **Partly ruled out.** The task's kind taxonomy is coarse ("ruling / constitutional decision / evidence record / implementation evidence"), and the text explicitly calls both objects rulings. The finer grain is still the text's own typed label, so it is not excluded.
3. **Q.kind = engineering artefact / evidence record,** from "The engineering artefact must end at *evidence validated*". Kind and conformance would both differ, so both → NONE.
   - **Largely ruled out.** The sentence is prescriptive: it says what the artefact should have been. The same passage calls R-91 "this ruling" and "A PREPARED ruling". It is also ambiguous whether "the engineering artefact" refers to R-91 itself.
4. **P.conformance = COLLAPSED,** because the Chief originally filed R-81..R-85 as if they were issued.
   - **Ruled out.** The text records the correction before adoption: "provenance corrected"; "the condition it imposed is discharged".
5. **Q's ground includes the routing error,** which is a domain/kind-type defect. This would give a second stated reason.
   - **Mostly ruled out.** The "on the ground that" clause names only the collapse, and the routing error is framed as a correction to R-91's finding. "NOT ELIGIBLE FOR ADOPTION AS ISSUED" is broad enough that this is not fully excluded.

## 5. Disagreement register

| # | Field / item | Class | A | Reviewer | Changes classification? |
|---|---|---|---|---|---|
| D1 | P.conformance support | coding | CONFORMANT; quotes centre on preparation/adoption | CONFORMANT (partial). The load-bearing quote is "independent review completed". The evidence-submission axis is unstated. | No (fragile) |
| D2 | P.conformance quote "The Chief declines…" | independence | Used as support | This is the preparer speaking about itself. The Decision Authority's own grounds ("provenance corrected", "independent review completed") are the independent support. | No |
| D3 | P.kind, Q.kind value | coding | "ruling (prepared ruling)" | "ruling". "Prepared" belongs to state_before. | No |
| D4 | Q.kind quotes | coding | Header + "this ruling collapsed the two" + "A PREPARED ruling…" | The header does not state "ruling". "collapsed the two" and "engineering deciding the outcome" support conformance, not kind. | No |
| D5 | outcome (P, Q) | coding | Not quoted | Quote needed: "✅ ADOPTED 2026-08-04 BY THE DECISION AUTHORITY…"; "⛔ HELD 2026-08-04 — NOT ADOPTED" | No |
| D6 | ANSWER.md label on "This is why R-91 was in the PREPARED state" | wording | SOURCE FACT | INFERENCE, supported by R-91's "(R-86/R-87 created no standing delegation…)" | No |
| D7 | R-71 use | evidence scope | Supports Q ground = RULE by analogy | Bears on no coded value. Q's RULE rests on R-91 alone. | No |
| D8 | Missed quotes for P.conformance and the COLLAPSED alternative | evidence scope | Not cited | See §2.3 | No (they strengthen partial CONFORMANT and exclude COLLAPSED) |
| D9 | Kind grain | source interpretation | Coarse grain primary; finer grain as alternative | Agree on coarse primary. But the finer grain is the text's own typed header, and it decides between STRICT, POSSIBLE and NONE. It should be flagged as the second-strongest alternative, not treated as a side note. | No under primary coding |
| D10 | P.ground | source interpretation | CHOICE | CHOICE, though some grounds read as rule-type conditions (Option B "appropriate only if…") | No (P's ground is not used by the rule) |

There is no **formal reasoning** disagreement: A applied the §2 rule correctly under each of its codings, including all four alternatives. There is no **substantive** disagreement: the witness classifications match.

## 6. What can and cannot be established

**Can be established from the text:**
- Both objects are rulings in a PREPARED state, acted on by the Decision Authority.
- P was adopted; Q was held (not withdrawn) on a stated rule ground.
- Q's role separation is stated as collapsed.
- The text's own stated reason for the hold is the collapse of evidence submission and constitutional review, not the kind of the object.
- The routing-error correction and the later ARB Constitutional Review are not the ground of the hold.
- P's preparation/adoption separation, and an independent review step, are stated as kept.

**Cannot be established:**
- Who submitted the evidence for R-81..R-85, and whether that party was separated from the reviewer. This is exactly the axis of Q's collapse.
- The kinds of R-82..R-85 at the register-header grain.
- The contents of R-87, R-88, R-90 and the ARB Constitutional Review report.
- Whether the Chief (per R-91's header) or engineering (per the hold) authored R-91's Phase 4 determination.

**Net:** on the task's coarse kind taxonomy, and with "independent review completed" read as a positive separation statement, conformance is a STRICT witness and kind is not a witness. The text does not rule out that P's conformance is UNK on the evidence-submission axis, and under that coding no witness exists.
