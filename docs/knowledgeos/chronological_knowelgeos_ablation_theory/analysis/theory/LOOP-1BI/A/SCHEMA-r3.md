# SCHEMA-r3

Change key: unmarked text is SCHEMA-r2 verbatim. Every addition or modification is marked `[r3-Gn]` (n = the gap in `GAPS.md` it closes). Where an r2 sentence is modified, the r2 wording is shown struck through as `~~…~~` and the r3 wording follows.

---

## 1. Unit: the individuated span record
- One record = one **verbatim source span**, the smallest sufficient span (1aw).
- **Fields:** `record_id` · `v1_event_ids` (lineage) · `source` (file + blob id) · `section` · `span` (verbatim; markdown emphasis markers may be stripped, and that must be disclosed).
- **Uniqueness:** (span, operation, target) must be unique. Events resolving to one span are **one record**. A span carrying an act clause and a norm clause stays **one record with both components**.
  - `[r3-G6]` "Events resolving to one span are one record" applies to **SAME-EVENT** candidates (§5 D10). A span that records two or more **DISTINCT** acts (D10) that differ in operation or target yields one record per (operation, target); those records **share** the span and are coded under **R-S1**.

## 2. Two independent components per record

**ACT: does the span record an act that actually happened?**
- `act.state` ∈ {ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED, NONE, UNKNOWN}.
- `act.operation`: what act (open vocabulary; the v1 operation list is preferred).
- `act.actor` · `act.target` · `act.quote`.
- `[r3-G1]` `act.basis` ∈ {REPORTED, SELF_PERFORMED, SELF_PERFORMED_OUTSIDE_RA3, NONE} (D3). Required on every record. When `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.state` is NONE but `act.operation` is still filled in (R-A3, step A7 of R-A5).
- `[r3-G4]` `act.attempt_kind` ∈ {PREPARED, FAILED} (D6). Required if and only if `act.state = ACT_ATTEMPTED`.
- `[r3-G4]` `act.later_disposition` (optional): if the span **or its section** records a later act that gave the attempted act effect or denied it effect (adopted / held / withdrawn by …), give that act's identifier and a quote. This does **not** change `act.state` (R-A6).

**NORM: what normative / deontic content does the span state?**
- `norm.deontic` ∈ {PERMITTED, REQUIRED, PROHIBITED, AUTHORIZED, UNAUTHORIZED, ELIGIBLE, INELIGIBLE, DELEGATED, STATUS_CONSTRAINT, NONE, UNKNOWN}.
  - `[r3-G5]` REQUIRED, PROHIBITED and STATUS_CONSTRAINT are defined in D7–D9. `[r3-G2]` ELIGIBLE / INELIGIBLE are defined in D12.
- `norm.about_operation` · `norm.about_actor` · `norm.about_target` (the *governed* act).
  - `[r3-G2]` `about_operation` is fixed by **literal attachment** (D13, R-N1). For STATUS_CONSTRAINT with no governed operation named, write `status: <verbatim status label>`.
- `norm.stated_by`: the act or actor stating it, if identifiable.
  - `[r3-G4]` If the span or its section marks the stating instrument PREPARED (D6), `stated_by` carries the marker verbatim, e.g. `ARB CHIEF (PREPARED)`.
- `norm.quote`.
- `[r3-G6]` `norm.basis` ∈ {OPERATOR, VERDICT, STATUS_PREDICATE, PERFORMATIVE_ONLY} (D7, D8, D12, R-N3). Required whenever `norm.deontic` is not NONE / UNKNOWN.
- `[r3-G6]` `norm.shared_with`: list of the other `record_id`s that share this span (R-S1); empty otherwise.

**Both components may be non-NONE for the same record.** They are coded **independently**.
- `[r3-G4]` The NORM component records **stated content**, whether or not the act that stated it took effect. It does not code the norm's force. The act's effectiveness is recorded only in the ACT component (`ACT_ATTEMPTED` / `attempt_kind`). See THEORY-CHOICES TC-3.

## 3. Epistemic status per coded field
Each coded field carries a status ∈ {SOURCE_FACT, INFERENCE, UNKNOWN, NOT_EVIDENCED}. An INFERENCE states its premise. No silent defaults.
- `[r3-G3]` Identifying a PROPOSAL (D4) and judging SAME EVENT vs DISTINCT (D10) are SOURCE_FACT when the span or section states them in words. Otherwise they are INFERENCE, with the premise quoted.

## 4. Critical rules
- **R-A1.** **Never infer an ACT from a NORM.** "X is authorized to begin" is a NORM (AUTHORIZED); it is not evidence that X began. ~~"Implementation does not begin" is a NORM (PROHIBITED / a status constraint)~~ `[r3-G5]` "Implementation does not begin" is a NORM: **STATUS_CONSTRAINT**, because it has no deontic operator (R-N2). It would be PROHIBITED only if it read "must not / shall not begin". It is **not** an ACT_REFUSED unless an attempt is recorded.
- **R-A2.** ACT_REFUSED / ACT_WITHDRAWN require an **identified proposal or attempt** in the span or its section.
  - `[r3-G3]` "Proposal" is defined in D4. ACT_REFUSED also requires a **refusal expression that disposes of that proposal** (D5). A negation that only reports non-occurrence so far ("has not been issued", "remains unauthorized", "not yet") is **never** a refusal. A reason given against an option that was not chosen, with no refusal expression ("X would blur chronology"), is **not** a refusal. Its act is coded NONE.
- **R-A3.** A register row **that itself performs** a norm-making act (adopts, authorizes, registers, supersedes, annotates) is an ACT_OCCURRED of that operation. Its stated content is additionally a NORM.
  - `[r3-G1]` **Scope (closed).** R-A3 applies only when **all** of the following hold:
    - (a) the section is a REGISTER ROW (D1);
    - (b) the **span itself** contains a PERFORMATIVE (D2) of one of the five listed operations, matched by lemma: *adopt, authorize, register, supersede, annotate*. Inflections and nominalizations count; synonyms do not ("approve", "confirm" alone, "classify", "recommend", "apply", "file", "retire", "hold", "direct", "defer", "promote" are **not** listed);
    - (c) the act is SELF_PERFORMED (D3), not reported.
    
    It is **not** applied by analogy. A performative found only in the section, row heading or annotation label, and not in the span, does not satisfy (b). A span that self-performs any other operation, or that self-performs outside a register row, is coded `act.state = NONE` with `act.basis = SELF_PERFORMED_OUTSIDE_RA3` and the operation recorded (R-A5 step A7). Adding operations to the list is a schema revision, not a coder judgement (THEORY-CHOICES TC-1).
  - `[r3-G6]` **"Its stated content is additionally a NORM"** is mandatory whenever R-A3 applies. Code the NORM by R-N3. Because a listed performative always yields at least a norm clause (D8 (iii)), an R-A3 record never has `norm.deontic = NONE`. If the only basis is the performative itself, `norm.basis = PERFORMATIVE_ONLY`.
- **R-A4.** UNKNOWN when the span does not decide. It is counted, and excluded from support counts.

- **R-A5. `[r3-G3]` ACT decision procedure and priority order.**
  - **Selecting the operation.** Collect the candidate acts the span supports. Merge SAME-EVENT candidates (D10): a deciding act whose stated content is the performance, refusal, withdrawal or reversal of operation X is coded **as X**, not as a generic "rule / decide". DISTINCT candidates belong to separate records (§1, R-S1). If the record is the only record on the span, code the candidate with the highest step below, and add `SPLIT_REQUIRED: <other operation>` to the premise of `act.operation`.
  - **Steps.** Apply the steps to the record's (operation, target). **The first step that matches decides.**

| Step | Condition (span, plus section only where stated) | Result |
|---|---|---|
| A1 | Withdrawal or reversal expression about X, and the prior proposal or act is identified (R-A2) | ACT_WITHDRAWN / ACT_REVERSED. If the prior item is not identified: UNKNOWN |
| A2 | Explicit refusal verb (D5 (i)) about X | ACT_REFUSED if a PROPOSAL for X is identified (R-A2). Otherwise UNKNOWN |
| A3 | Negated X stated as a disposition (D5 (ii)) | ACT_REFUSED if a PROPOSAL is identified. Otherwise NONE (R-A1) |
| A4 | Initiation of X, plus a non-effect marker (D6) | ACT_ATTEMPTED, with `attempt_kind` |
| A5 | X reported as performed (D3 REPORTED) | ACT_OCCURRED, `basis = REPORTED` |
| A6 | R-A3 scope (a)–(c) satisfied | ACT_OCCURRED, `basis = SELF_PERFORMED` |
| A7 | Self-performance of any other operation | NONE, `basis = SELF_PERFORMED_OUTSIDE_RA3`, operation recorded |
| A8 | No act | NONE, `basis = NONE` |

  - **Consequences of the order.**
    - A refusal (A2/A3) outranks a register row's own performance (A6). A row that declines or disposes negatively of X is coded on X, not as an ACT_OCCURRED of the row.
    - An ATTEMPTED act (A4) outranks ACT_OCCURRED (A5/A6). A PREPARED instrument is never coded ACT_OCCURRED of its own operation.
  - Acts cited only as **evidence or grounds** for the span's principal content (e.g. in an evidence column or a "because …" clause) are not candidates for this record. They may be their own records.

- **R-A6. `[r3-G4]` PREPARED and other non-effective acts.**
  - An act is ACT_ATTEMPTED only on a **non-effect marker in the source** (D6). The coder never judges, on their own account, whether an actor had the capability or authority to act. Without a marker, an act by any actor is coded A5/A6.
  - A conditional or provisoed authorization ("authorized … provided …") is **not** ATTEMPTED. The act is coded A5/A6. The condition belongs to the NORM.
  - A later adoption, hold or withdrawal recorded in the section is a **separate act**. It is recorded in `act.later_disposition` and does not convert the attempted act to ACT_OCCURRED.

- **R-N1. `[r3-G2]` Level of the governed act.**
  - `norm.about_operation` is the operation that the span's deontic operator, verdict or status predicate **attaches to grammatically** (D13). It is never raised to a meta-act or lowered to a substantive behaviour.
  - Rule content that the span only MENTIONS (D11) supplies `about_target`. It never supplies a separate norm, and its internal modals are not coded.
  - **Examples:**
    - "PROMOTE" applied to a named candidate behaviour attaches to *promote*, with target = that behaviour.
    - "may begin RED" attaches to *begin RED*.
    - "forbids promoting from one" attaches to *promote*.

- **R-N2. `[r3-G5]` REQUIRED vs PROHIBITED vs STATUS_CONSTRAINT.**
  - The deontic value is read from the **span**. The section may resolve referents only (R-X1).
  - If an obligation operator (D9) or a prohibition operator (D7) attaches to an operation, the value is REQUIRED or PROHIBITED.
  - If there is no deontic operator, and a STATUS PREDICATE (D8) is predicated of an item or operation, the value is STATUS_CONSTRAINT.
  - A status noun or adjective ("standard", "permanent", "candidate", "in force", "binding", "frozen") is a status predicate even if it implies conformity. It is never REQUIRED unless an obligation operator attaches to an operation.

- **R-N3. `[r3-G2]` `[r3-G5]` `[r3-G6]` NORM decision procedure and priority order.**
  1. Collect the norm clauses in the span's **STATED** content (D11): deontic operators (D7), disposition verdicts (D12), status predicates (D8), and listed performatives (D8 (iii)).
  2. If there are none, the value is NONE.
  3. For each clause, set `about_operation` by literal attachment (R-N1), and `about_target` = the item the clause is predicated of. In a table or matrix row, that is the row's subject item.
  4. If several clauses concern the **same target**, choose by this priority: **OPERATOR > VERDICT > STATUS_PREDICATE > PERFORMATIVE_ONLY.**
  5. Map the chosen clause:
     - operator → per D7;
     - verdict → ELIGIBLE / INELIGIBLE;
     - status predicate or performative-only → STATUS_CONSTRAINT.
     
     Exception: a performative of *authorize* is a deontic operator (AUTHORIZED, `basis = OPERATOR`), because "authorized" is in the deontic vocabulary.
  6. Record `norm.basis`.
  7. If the span cannot be resolved into one clause by steps 1–5, the value is UNKNOWN.

- **R-S1. `[r3-G6]` Shared-span rule.** When two or more records share one verbatim span:
  - **ACT:** each record codes **only** the act for its own (operation, target). An act is never coded on more than one record.
  - **NORM:** the norm clause(s) of the span, selected by R-N3, are coded **identically on every record sharing the span**. Each record lists the others in `norm.shared_with`. The coder does not choose which record "owns" the norm.
  - **Counting:** a norm coded on records that share a span is **one** norm observation in every support count.

- **R-X1. `[r3-G1]` `[r3-G5]` What the section may supply.** The section (heading and surrounding source text of the same section) may be used **only** to:
  - (i) resolve referents (who "the Chief" is, what "it" or "this proviso" denotes, what a category label or legend means);
  - (ii) identify a PROPOSAL or attempt (R-A2, D4);
  - (iii) identify a non-effect marker (D6) or a later disposition (`act.later_disposition`);
  - (iv) identify the meta-operation a verdict concerns (D12).
  
  The section may **not** supply a performative for R-A3, a deontic operator, or a status predicate for the NORM.

## 5. Definitions `[r3]`

- **D1. REGISTER ROW** `[r3-G1]`. A span whose `section` heading is exactly a register ruling identifier (`## R-<number>`, with nothing else in the heading). These are not register rows: a section about a ruling's attachment ("## R-36 promotion matrix (adopted by R-36): …"), session logs, commits, plans, or any null section.

- **D2. PERFORMATIVE** `[r3-G1]`. A verb, participle or nominalization in the span by which the span's own instrument does the act it names. Examples: present-tense ruling text ("X adopts …", "… is adopted"), a headline participle ("SLICE X AUTHORIZED"), or "is authorized to …" in the instrument's operative text.

- **D3. act.basis** `[r3-G1]`.
  - **REPORTED:** the span describes an act constituted elsewhere or by conduct. Typical signs: past tense or narrative; another instrument, date or actor named as where the act happened ("(adoption act: R-86)", "per R-44"); conduct verbs ("ran", "appended", "probed").
  - **SELF_PERFORMED:** R-A3 (a)–(c) hold.
  - **SELF_PERFORMED_OUTSIDE_RA3:** the span's own text constitutes an act, but R-A3 (a) or (b) fails. Examples: a register row that classifies, recommends, applies, approves or retires; a matrix cell that recommends.
  - **NONE:** no act.
  - A self-performed refusal ("the Board declines that") gets `basis = SELF_PERFORMED`. It is governed by R-A2 and steps A2/A3, not by R-A3's list.

- **D4. PROPOSAL** `[r3-G3]`. Text in the span or section stating that performing X on the target was **put forward for decision**. This includes:
  - a motion, request, offered option or option set;
  - an issued PREPARED ruling (a proposal for its adoption);
  - a recommendation;
  - a question put to the decider of whether to do X.
  
  A mere possibility, or a statement that X has not happened, is not a proposal.

- **D5. REFUSAL EXPRESSION / DISPOSITION** `[r3-G3]`.
  - (i) **Explicit refusal verb:** *decline(s), reject(s/ed), refuse(s/d)*, "will not", "is/are NOT ADOPTED", "expressly NOT [X]".
  - (ii) **Negated X as a disposition:** a negation of X ("X is not superseded") counts only when the span is the decision on the proposal. Either the span states a deciding act ("ruled", "held", "decided") whose content is the negation, or the span is the operative or headline text of the register row that the section identifies as deciding that proposal.
  - **Never dispositions:** continuative or non-perfective negations ("has not been", "not yet", "remains / still not", "un-"), contrastives ("rather than X"), and reasons against an unchosen option.

- **D6. ACT_ATTEMPTED; PREPARED; FAILED** `[r3-G4]`.
  - **ACT_ATTEMPTED:** the span or section records that an actor **initiated** X (issued, filed, submitted, tried, ran) **and** a source statement says the initiation **did not by itself take effect**. There are two kinds:
    - **PREPARED:** the instrument is marked "PREPARED", "NOT ADOPTED", "awaiting adoption", or as needing a further act to take effect.
    - **FAILED:** the attempt is recorded as rejected or errored by a mechanism or an actor (e.g. "refused: …", "record unchanged").
  - **Not ATTEMPTED:**
    - a proposal (offering X for decision is not an attempt at X);
    - an intended or planned act;
    - a conditional or provisoed act (R-A6).

- **D7. DEONTIC OPERATOR (lexicon)** `[r3-G5]`. Matching is case-insensitive.

| Value | Operators |
|---|---|
| REQUIRED | See D9 |
| PROHIBITED | *must not, shall not, may not, no … shall, is/are forbidden / prohibited, forbids …-ing, NOT PERMITTED*; imperative *never …* / *do not …* |
| PERMITTED | *may, is permitted to* |
| AUTHORIZED / UNAUTHORIZED | *authorized (to), not authorized, unauthorized* |
| ELIGIBLE / INELIGIBLE | *eligible, not eligible, qualifies, does not qualify* |
| DELEGATED | *delegated, delegates* |

  - A deontic word stays an operator after *remains / stays* ("remains unauthorized" = UNAUTHORIZED).

- **D8. STATUS PREDICATE; STATUS_CONSTRAINT** `[r3-G5]` `[r3-G6]`. A STATUS PREDICATE is a predicate, with no deontic operator, that assigns an item or operation a **standing in the governance order**. There are three kinds:
  - (i) **Status label.** A lifecycle, standing or class label: open, closed, frozen, deferred, retired, held, in force, governing, binding, permanent, standard, candidate; "remains / stays <label>"; scope statements such as "the register holds X".
  - (ii) **Prospective indicative.** A present- or future-tense indicative statement, with no modal, that an operation does / does not / does only-under-a-condition take place ("implementation does not begin"). Retrospective reports ("nothing was implemented") are not status predicates.
  - (iii) **Listed performative.** A performative or participle of a listed R-A3 operation other than *authorize* (adopted, superseded, not superseded, annotated, registered), predicated of a named object. Its `basis = PERFORMATIVE_ONLY`.
  
  Statements that a condition, guard or proviso is or is not met are **facts, not status predicates**. STATUS_CONSTRAINT is the value for (i)–(iii).

- **D9. REQUIRED** `[r3-G5]`. An obligation operator attached to an operation: *must, shall, is/are required to, is obliged to, has to, MUST BE …-ed, requires <actor> to …, mandatory*.

- **D10. SAME EVENT / DISTINCT** `[r3-G3]` `[r3-G6]`.
  - **SAME EVENT:** two candidate acts in one span are the same event if one is the deciding or performing act whose **stated content** is the performance, refusal, withdrawal or reversal of the other ("rules that X is not superseded"; "held it — not adopted"). Coordinated verbs with one actor and one object ("confirms and adopts") are also one event.
  - **DISTINCT:** otherwise. This covers different actors, different times, or an act given as the **ground** of another ("withdrawn … because the Authority ruled …").

- **D11. STATED vs MENTIONED** `[r3-G2]`.
  - **MENTIONED:** content the span names, quotes or proposes **as the object** of a meta-operation, verdict or recommendation, or cites as evidence. Examples: a candidate behaviour's name, or a proposed one-line rule text.
  - **STATED:** content the span's instrument asserts as its own normative content, including verbatim reproduction of the row's own ruling ("ruling as issued", "in the Board's own words").

- **D12. DISPOSITION VERDICT; ELIGIBLE / INELIGIBLE** `[r3-G2]`.
  - **Disposition verdict:** a classification, category assignment or recommendation (including imperative forms such as "PROMOTE") that assigns an item an outcome of a meta-operation under decision in the section (identified per R-X1 (iv)), without performing that operation.
  - **ELIGIBLE:** a positive verdict, or an explicit eligibility operator: the item qualifies for, or is recommended for, operation X.
  - **INELIGIBLE:** a negative verdict ("not promoted", "remains candidate" as the outcome of the review, "stays in …" instead of X), or an explicit ineligibility operator.
  - `about_operation` = X. A verdict is never evidence that X occurred (R-A1).

- **D13. LITERAL ATTACHMENT** `[r3-G2]`. The operation that a norm clause governs is:
  - the verb the operator modifies;
  - for a verdict, the meta-operation the verdict concerns;
  - for a status predicate, the operation named in it, or else `status: <label>`.

- **D14. SHARED SPAN** `[r3-G6]`. Two or more records whose `span` fields are verbatim-identical within the same source and section.
