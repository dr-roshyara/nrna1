# Coding manual (frozen). Code each record independently, from its own text only.

## A. Operations
For every performative act in a record (the headline and the Effect column), decide which operation it is **by the verb the record itself uses**, using this table.
- An act whose verb is not in the table is listed under `not_modelled`, using the record's own verb (e.g. "RENAME").
- If an act fits two rows of the table, use the headline's performative.

| Operation | Verbs in the record |
|---|---|
| ADOPT | "adopted" said of a ruling that was PREPARED (awaiting adoption) |
| ADOPT-DECISION | "adopted"/"adoption" said of an option, path, rename, review or decision (first instance) |
| APPROVE | "approved", "approval" (a plan, realization, strategy or rule) |
| DEFER | "deferred", "deferral" |
| OPEN-WORK | "opened" (a work package) |
| RATIFY | "ratified" |
| DECLARE-SCOPE | "declared" of a scope or boundary |
| CONFIRM | "confirmed" (no change) |
| SEAL | "sealed" |
| REFINE-CANDIDATE | "candidate … refined" |
| MODIFY-SEQUENCE | "sequence modified" |
| AUTHORIZE | "authorized" |
| ACCEPT | "accepted" (completed work or a completed assessment) |
| SUBDIVIDE | "subdivided", "split" |
| WITHDRAW | "withdrawn" |
| HOLD | "held" |
| ANNOTATE | "annotated", "annotation", "redirect", "pointer" |
| DETERMINE | "determined", "classified" |
| RECLASSIFY | "reclassified" |
| FREEZE / LIFT | "frozen"/"freeze" / "lifted", "released" |
| REJECT | "not adopted", "rejected", "declined" |
| SUPERSEDE | "supersedes", "superseded by" |
| CREATE-NORM | a rule or discipline established ("shall", "is the rule", "discipline") |
| RAISE | "promoted" (raised to a higher standing) |
| ALLOCATE | "allocated" |
| PERMIT | "permitted", "permission" |
| CONTRA | counter-evidence recorded |
| CORRECT-TEXT | a same-day correction of the record's own text |

## B. Each operation's frame (the only things it may change directly)

| Operation | May change |
|---|---|
| ADOPT | status, annotation-role |
| ADOPT-DECISION | decision-in-force |
| APPROVE | approval of the target |
| DEFER | deferral of the target |
| OPEN-WORK | work lifecycle |
| RATIFY | standing of the target |
| DECLARE-SCOPE | scope norm |
| CONFIRM | nothing |
| SEAL | the document's regime |
| REFINE-CANDIDATE | candidate content |
| MODIFY-SEQUENCE | sequence |
| AUTHORIZE | authorization (scoped) |
| ACCEPT | acceptance, work lifecycle (acceptance may close work) |
| SUBDIVIDE | work structure |
| WITHDRAW / HOLD | status |
| ANNOTATE | annotation |
| DETERMINE / RECLASSIFY | classification, evidence status |
| FREEZE / LIFT | regime (scoped) |
| REJECT | nothing |
| SUPERSEDE | authoritative location (the old record kept) |
| CREATE-NORM | norm |
| RAISE | standing |

**Consequences that follow automatically** (e.g. "X becomes acceptable", "queue item closed") are *derived*, not direct changes.

## C. Legality guards (what makes an act permitted)
- **ADOPT** a PREPARED ruling: only by the Decision Authority, never by its own issuer.
- **START / execution:** only with full authorization (any stated precondition discharged). Permission alone never allows execution.
- **Evidence bars:**
  - RAISE needs ≥ 2 supporting instances or an exception;
  - SUPERSEDE needs sufficient evidence or a structural rule;
  - creating new vocabulary, categories or a methodology extension needs demonstrated need;
  - authorizing a successor slice needs the predecessor accepted;
  - reopening needs materially new evidence.

## D. Code seven judgements per record
Values: SUPPORTED, VIOLATED, UNTESTABLE (does not apply), NOT-MODELLED (the relevant act is not in the table), AMBIGUOUS (the text allows two readings). For V6 on records numbered below R-43, use PRE-R43.

| # | Judgement | Code SUPPORTED / VIOLATED when |
|---|---|---|
| V1 | Legality | every act the record performs is permitted by §C, and every refusal that cites a rule concerns an act §C forbids. VIOLATED if a performed act breaks §C, with the relevant facts stated in the record |
| V2 | Separation of permission / authorization / commissioning / execution | the record keeps them apart. VIOLATED if it treats one as the other without a separate act |
| V3 | Frames | every explicitly stated direct change fits §B for its operation. VIOLATED if a stated direct change falls outside |
| V4 | Evidence persistence | nothing's standing or status changes by evidence alone. VIOLATED if the record says it did |
| V5 | Reopening | any reopening of an earlier ruling cites new evidence |
| V6 | Authority | the record names the deciding authority (in a "· Governance · X · AUTHORITY" triple or in its text) |
| V7 | Supersession | any supersession is explicit and keeps the old record |

## E. Rules
- Code from the record's text only.
- Silence is not evidence: if the record does not say it, the judgement is UNTESTABLE, not VIOLATED.
- Do not guess.
- Set `authority_named` to true or false.


## G. r3 rules (authorized by the human act of 2026-09-30; they override anything above that is looser)

- **G1.** V1 vacuous case: 'If no act of this record is subject to a §C guard and no refusal cites a rule, code V1 UNTESTABLE.'
- **G2.** V1 row value: code per act; VIOLATED > AMBIGUOUS > SUPPORTED (at least one guarded act satisfied, none UNTESTABLE) > UNTESTABLE; NOT-MODELLED only if every act is not modelled.
- **G3.** A refusal citing a rule outside §C does not bear on V1.
- **G4.** 'Demonstrated need' = the record states at least one instance of the problem having occurred (a purpose does not count); 'successor slice' = the record names the predecessor.
- **G5.** V4 actor rule: when a status change and evidence appear together, SUPPORTED if the record attributes the change to an act of an authority, AMBIGUOUS if it names no actor.
- **G6.** All judgements: code AMBIGUOUS only if the competing readings give different codes for that judgement.
- **G7.** V2 applies only if the record mentions permission, authorization, commissioning or execution; separating approval or acceptance from execution is outside V2.
