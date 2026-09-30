# What separates an adoption that was performed (P) from one that was held (Q)?

Sources: `SOURCES.md` (R-71, R-81, R-86, R-91) and `TASK.md` only.

## 1. Coded events

| Field | P — adoption of R-81..R-85 (R-86) | Q — adoption of R-91 (held) |
|---|---|---|
| object | R-81..R-85 — "Decision Authority confirms and adopts R-81 through R-85" | R-91 — "EVENT D REVIEW — THE DECISION IS SOUND; …" |
| kind | ruling — "PREPARED RULINGS AWAITING ADOPTION"; "These become governing rulings" | ruling — "A PREPARED ruling was still engineering deciding the outcome"; "this ruling collapsed the two" |
| actor | Decision Authority — "Programme Governance · Adoption · DECISION AUTHORITY" | Decision Authority — "The Decision Authority held it" |
| state_before | PREPARED — "THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION"; "explicitly marked PREPARED" | PREPARED — "ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED" |
| conformance | CONFORMANT (partly stated, see ambiguity A1) — "delegated CHAIRING AND PREPARATION, not adoption"; "independent review completed" | COLLAPSED — "Event D SEPARATES evidence submission from constitutional review, and this ruling collapsed the two" |
| outcome | PERFORMED | REFUSED/HELD |
| ground | CHOICE — Option A chosen with reasons; "Option B declined …", "Option C declined …" | RULE — "NOT ELIGIBLE FOR ADOPTION AS ISSUED"; "on the ground that Event D SEPARATES evidence submission from constitutional review" |

## 2. Labelled findings

**SOURCE FACT.** R-86 records the Decision Authority adopting R-81..R-85 "without amendment". Its grounds include "independent review completed" and "the rulings explicitly marked PREPARED rather than implicitly adopted".

**SOURCE FACT.** R-81's annotation says the Session-1 mandate "delegated CHAIRING AND PREPARATION, not adoption". The Chief also "declines to resolve an ambiguity about the Chief's own authority in the Chief's own favour".

**SOURCE FACT.** R-86 says: "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it." This is why R-91 was in the PREPARED state.

**SOURCE FACT.** R-91 was "HELD … NOT ELIGIBLE FOR ADOPTION AS ISSUED". The ground was that "Event D SEPARATES evidence submission from constitutional review, and this ruling collapsed the two: engineering submitted evidence and then wrote the governance outcome".

**SOURCE FACT.** The hold does not dispute the substance of Phases 1–3: they are "superseded in FORM only". The "option-set gap" is corrected to a "ROUTING ERROR". The text introduces this as "AND A SUBSTANTIVE CORRECTION", not as the ground of the hold.

**INFERENCE.** Kind, actor and state_before are the same in P and Q: both are PREPARED rulings acted on by the Decision Authority. Under the primary coding, the only compared field that differs is conformance.

**FORMAL CONSEQUENCE (primary coding).**
- **Conformance: STRICT witness.** The outcomes are opposite. Q's ground is RULE, not CHOICE. Conformance is known and different (CONFORMANT vs COLLAPSED). Kind, actor and state_before are known and equal.
- **Kind: NONE.** Kind is known and equal, so it cannot be a witness.
- Decisive field: `conformance`.

**SOURCE FACT: the reason the text gives for Q's outcome.** The text itself gives the conformance reading: evidence submission and constitutional review were collapsed. It does not give kind as the reason.

**INFERENCE: how R-71 bears.** R-71 is about WP-7B-R1 and is not cited by R-86 or R-91. It states the same principle as Q's hold: "engineering PRODUCES THE EVIDENCE … THE QUALIFICATION ITSELF BELONGS TO THE ACCEPTANCE PACKAGE. Engineering does not self-certify it." It is an earlier instance of the evidence-producer ≠ qualifier rule, so it supports reading Q's ground as RULE rather than CHOICE by analogy only. It sets no value for any coded field of P or Q.

**UNKNOWN.**
- Who submitted the evidence for R-81..R-85, and whether that role was stated as separate from review.
- The governance domains of R-82..R-85, whose texts were not supplied.
- The contents of R-87, R-88 and R-90, which are referenced but not supplied.
- The findings of the ARB Constitutional Review report.

## 3. Ambiguities and alternatives

- **A1 — P conformance.** The separation is stated for preparation, review and decision. It is not stated for evidence submission, which is exactly the axis on which Q collapsed. If P is coded `UNK`, conformance is not known in both events, so the conformance witness drops to **NONE**. The kind witness stays NONE.
- **A2 — Kind granularity.**
  - At the finer grain, R-81 is "Execution Governance · Authorization" and R-91 is "Architecture Governance · Determination on submitted evidence". P's finer kind is UNK because R-82..R-85 were not supplied. Under this coding, conformance becomes **POSSIBLE** and kind stays **NONE**.
  - If only R-81 stands in for P, kind is known and different, and so is conformance. Neither is then an isolated witness, so both are **NONE**.
- **A3 — Q's kind.** The hold calls R-91 "The engineering artefact [that] must end at *evidence validated*". Kind could therefore be read as an engineering artefact or evidence record rather than a ruling. That reading is closer to what R-91 *should* have been than to what it is labelled as. Under it, kind and conformance both differ, so both witnesses are NONE.
- **A4 — Who authored R-91.** Its header says "ISSUED BY THE ARB CHIEF", but the hold says "engineering submitted evidence and then wrote the governance outcome". Authorship is not a compared field, but it affects how the collapse is described.
- **A5 — P's ground.** It could be coded RULE, since adoption was an act the authority was constitutionally required to take, rather than CHOICE among options A/B/C. The comparison rule only uses Q's ground, so this does not change the result.
- **A6 — Two reasons in Q.** The routing-error correction could be read as a second, substantive reason for the hold. The text frames it as a correction, not as the ground.
