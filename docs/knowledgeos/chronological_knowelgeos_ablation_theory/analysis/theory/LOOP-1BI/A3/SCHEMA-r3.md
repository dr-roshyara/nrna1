# SCHEMA-r3 (consolidation r3c of repair r2)

Change key:
- Unmarked text is SCHEMA-r2 verbatim.
- `[r3-Gn]` marks text added or modified by the first r3 draft (r0) to close gap Gn in `GAPS.md`.
- `[r1-fix-<id>]` marks text changed by the first repair (r1) of r0. The ids are listed in `CHANGELOG-r1.md`.
- `[r2-fix-<id>]` marks text changed by the second repair (r2) of r1. The ids are listed in `CHANGELOG-r2.md`: Na = neutrality item N-a, Nd = neutrality item N-d, GM = ground-marker list, PV = provenance / location mentions, RF = rank-1 refusal cell, DN = D7 vs D12 "do not …", PC = primary clause / class labels, PT = `performative_token`, TC2b = join tag for TC-2 2b. r1 text that r2 removes or replaces is not shown here. CHANGELOG-r2 records it verbatim.
- `[r3c-fix-<id>]` marks text changed by this consolidation (r3c) of r2. The ids are listed in `CHANGELOG-r3c.md`: SS = shared-span ground acts, PR = PROVENANCE label, MR = mechanism response needs a stated non-effect, S2 = conditional step 2, GC = `act.ground_candidates`, NW = normative word of a class label. r3c makes drafting repairs only. It changes no value that CHANGELOG-r2 or REVIEW-r2 reports for any record in RECORDS.json. It removes one literal reading of r2 (R06/R07, see SS) that disagreed with the reported value.
- Where an r2 sentence is modified (by r2 or r3c), the replaced wording is struck through as `~~…~~` and the new wording follows.
- A markup-free, coder-facing text of the same rules is `SCHEMA-r3-CODER.md`; `EQUIVALENCE.md` maps every rule here to its place there.

---

## 1. Unit: the individuated span record
- One record = one **verbatim source span**, the smallest sufficient span (1aw).
- **Fields:** `record_id` · `v1_event_ids` (lineage) · `source` (file + blob id) · `section` · `span` (verbatim; markdown emphasis markers may be stripped, and that must be disclosed).
- **Uniqueness:** (span, operation, target) must be unique. Events resolving to one span are **one record**. A span carrying an act clause and a norm clause stays **one record with both components**.
  - `[r3-G6]` "Events resolving to one span are one record" applies to **SAME-EVENT** candidates (§5 D10). A span that records two or more **DISTINCT** acts (D10) that differ in operation or target yields one record per (operation, target). Those records **share** the span and are coded under **R-S1**.
  - `[r3c-fix-SS]` This holds also when one of the DISTINCT acts stands in a ground-marked segment (D15). An act given as the ground of another is DISTINCT (D10), so on a shared span it is the act of its own record (R-A5 step 1, shared-span exception).

## 2. Two independent components per record

**ACT: does the span record an act that actually happened?**
- `act.state` ∈ {ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED, NONE, UNKNOWN}.
- `act.operation`: what act (open vocabulary; the v1 operation list is preferred).
- `act.actor` · `act.target` · `act.quote`.
- `[r3-G1]` `[r1-fix-C1]` `act.basis` ∈ {REPORTED, SELF_PERFORMED, SELF_PERFORMED_OUTSIDE_RA3, NONE} (D3). Required on every record. When `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.state` is NONE, but `act.operation` and `act.state_if_extended` are still filled in (R-A3, R-A5 step 2).
- `[r1-fix-B1]` `act.state_if_extended`: required if and only if `act.basis = SELF_PERFORMED_OUTSIDE_RA3`. It is the state R-A5 step 3 would assign if the R-A3 scope gate (R-A5 step 2) were not applied: ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED or UNKNOWN. It is **not** `act.state`. It exists so that THEORY-CHOICES TC-1 alternative 1c can be computed.
  - `[r2-fix-Na]` Because rank 3 matches any SELF-PERFORMANCE (D3), this value is defined **for positive and negative gated acts alike**. It is never NONE.
- `[r2-fix-Na]` `act.gated_candidates`: required on every record. A list with one entry for **every** candidate that R-A5 step 2 removed, **including the candidate coded on the record and every candidate that loses at step 4**. Each entry has `operation`, `target`, `quote`, `state_if_extended`, `attempt_kind` (if and only if `state_if_extended = ACT_ATTEMPTED`), `ext_rank` (the step-3 rank, 1, 2 or 3, that produced `state_if_extended`) and `coded` (true for the candidate coded in `act.operation`, otherwise false). It is empty if step 2 removed no candidate.
- `[r3c-fix-GC]` `act.ground_candidates`: required on every record. A list with one entry for every act that R-A5 step 1 excluded from this record's candidates because it stands in a ground-marked segment (D15). Each entry has `operation`, `actor`, `target`, `quote` and `marker` (the D15 kind, (i), (ii) or (iii), with the marker word or the column header). A ground-marked act that is coded on another record sharing the span (R-S1) is not entered. The list is empty if step 1 excluded no act. It records no state and changes no other field. It exists so that acts in ground and evidence segments stay computable.
- `[r1-fix-N6]` `act.attributed_to` (optional): the actor that the span or its row names as the issuer of reproduced operative text ("RULING AS ISSUED, VERBATIM", "IN THE BOARD'S OWN WORDS", "(Principal Architect instruction)"). It does not change `act.basis` (D3). It exists for TC-1 alternative 1d.
- `[r3-G4]` `act.attempt_kind` ∈ {PREPARED, FAILED} (D6). Required if and only if `act.state = ACT_ATTEMPTED` `[r1-fix-B1]` or `act.state_if_extended = ACT_ATTEMPTED`.
- `[r2-fix-Nd]` `act.mechanism_refusal` ∈ {true, false}. Required whenever the span contains a refusal expression (D5 (i)) or a FAILED marker (D6) concerning a candidate act. It is true when that expression is a MECHANISM RESPONSE (D6), otherwise false. It exists so that THEORY-CHOICES TC-7 can be computed.
- `[r3-G4]` `act.later_disposition` (optional): if the span **or its section** records a later act that gave the attempted act effect or denied it effect (adopted / held / withdrawn by …), give that act's identifier and a quote. This does **not** change `act.state` (R-A6).

**NORM: what normative / deontic content does the span state?**
- `norm.deontic` ∈ {PERMITTED, REQUIRED, PROHIBITED, AUTHORIZED, UNAUTHORIZED, ELIGIBLE, INELIGIBLE, DELEGATED, STATUS_CONSTRAINT, NONE, UNKNOWN}.
  - `[r3-G5]` REQUIRED, PROHIBITED and STATUS_CONSTRAINT are defined in D7–D9. `[r3-G2]` ELIGIBLE / INELIGIBLE are defined in D12. `[r1-fix-M1]` The other values keep their r2 meaning. r3 gives no lexicon for them.
- `norm.about_operation` · `norm.about_actor` · `norm.about_target` (the *governed* act).
  - `[r3-G2]` `about_operation` is fixed by **literal attachment** (D13, R-N1). For STATUS_CONSTRAINT with no governed operation named, write `status: <verbatim status label>`.
- `norm.stated_by`: the act or actor stating it, if identifiable.
  - `[r3-G4]` If the span or its section marks the stating instrument PREPARED (D6), `stated_by` carries the marker verbatim, e.g. `ARB CHIEF (PREPARED)`.
- `norm.quote`.
- `[r3-G6]` `norm.basis` ∈ {OPERATOR, VERDICT, STATUS_PREDICATE, PERFORMATIVE_ONLY} (D7, D8, D12, R-N3). Required whenever `norm.deontic` is not NONE / UNKNOWN.
- `[r1-fix-N3]` `[r2-fix-PT]` `norm.performative_token` ∈ {true, false}. Required whenever `norm.basis` is filled, and on every entry of `norm.other_clauses`. It is true when the clause's normative word is **itself an act expression in the span**: a PERFORMATIVE (D2), a negative disposition expression (D5; a withdrawal or reversal expression), or the verb or participle by which the span reports an act. This holds **whether or not** that act is coded on this record, is removed by the scope gate, or is REPORTED. Every PERFORMATIVE_ONLY clause therefore has `performative_token = true`, and so has an *authorize* performative coded OPERATOR (R-N3 step 5). Otherwise it is false. A **bare authorize performative** is a clause with `performative_token = true`, `deontic = AUTHORIZED`, and no governed operation stated in the span (`about_operation` with status NOT_EVIDENCED), e.g. "Slice 7C AUTHORIZED".
  - `[r3c-fix-NW]` **Normative word of a class label.** For a class-label clause (D8 (i)), the normative word is the label noun phrase itself, not the copula, preposition or colon that predicates it of the item ("is / was", "as", ":"). The clause has `performative_token = true` only if that noun phrase is itself an act expression in the span in the sense above.
- `[r1-fix-M4]` `norm.cited_as_ground` ∈ {true, false}. Required whenever `norm.basis` is filled. It is true when the chosen clause stands in a segment that the span marks as the ground of, or evidence for, another statement ("because …", "since …", "on the ground that …", "GROUNDS:", "Evidence:", an evidence column). Otherwise it is false (TC-5).
- `[r1-fix-C7]` `norm.other_clauses`: a list of the norm clauses that R-N3 step 1 collected but did not choose. Each entry has `deontic`, `about_operation`, `about_target`, `basis`, `performative_token` and `quote`. It is empty otherwise. The primary NORM fields above stay single-valued.
- `[r1-fix-M5]` `norm.indicative`: a list of {`quote`, `operation`} for each present- or future-tense indicative in the span that has no modal, states whether an operation takes place, and is **not** a status predicate under D8 (ii). These are not coded as norms (TC-6). It is empty otherwise.
- `[r3-G6]` `norm.shared_with`: list of the other `record_id`s that share this span (R-S1); empty otherwise.
- `[r2-fix-TC2b]` `norm.adopted_via`: the identifier of the instrument that the record's **section heading** states adopted the section's content ("## R-36 promotion matrix (adopted by R-36): …" → `R-36`), with status SOURCE_FACT. If the heading states none, it is empty, with status NOT_EVIDENCED. It is a join key for THEORY-CHOICES TC-2 2b only. It supplies no act, no performative and no norm clause (R-X1).

**Both components may be non-NONE for the same record.** They are coded **independently**.
- `[r3-G4]` The NORM component records **stated content**, whether or not the act that stated it took effect. It does not code the norm's force. The act's effectiveness is recorded only in the ACT component (`ACT_ATTEMPTED` / `attempt_kind`). See THEORY-CHOICES TC-3.

## 3. Epistemic status per coded field
Each coded field carries a status ∈ {SOURCE_FACT, INFERENCE, UNKNOWN, NOT_EVIDENCED}. An INFERENCE states its premise. No silent defaults.
- `[r3-G3]` `[r1-fix-B2]` A PROPOSAL or attempt (D4, R-A2) is identified **only as SOURCE_FACT**: the span or its section states it in words. A proposal or attempt that could only be inferred counts as **not identified** (R-A2, R-A5 step 3). Judging SAME EVENT vs DISTINCT (D10) is SOURCE_FACT when the span or section states it in words. Otherwise it is INFERENCE, with the premise quoted.

## 4. Critical rules
- **R-A1.** **Never infer an ACT from a NORM.** "X is authorized to begin" is a NORM (AUTHORIZED); it is not evidence that X began. ~~"Implementation does not begin" is a NORM (PROHIBITED / a status constraint); it is **not** an ACT_REFUSED unless an attempt is recorded.~~ `[r3-G5]` `[r1-fix-C4]` "Implementation does not begin" is a NORM: **STATUS_CONSTRAINT** (D8 (ii)), because it has no deontic operator (R-N2). It would be PROHIBITED only if it read "must not / shall not begin". It is **not** an ACT_REFUSED unless a **proposal or attempt** is identified (R-A2, §3) and the span is the disposition of it (D5 (ii)).
- **R-A2.** ACT_REFUSED / ACT_WITHDRAWN require an **identified proposal or attempt** in the span or its section.
  - `[r3-G3]` "Proposal" is defined in D4. ACT_REFUSED also requires a **refusal expression that disposes of that proposal** (D5). A negation that only reports non-occurrence so far ("has not been issued", "remains unauthorized", "not yet") is **never** a refusal. A reason given against an option that was not chosen, with no refusal expression ("X would blur chronology"), is **not** a refusal. Its act is coded NONE.
  - `[r1-fix-B2]` "Identified" means **stated in words** in the span or section (SOURCE_FACT, §3). An inferred proposal is not identified. The refused operation X is the operation named by the **complement of the refusal expression** (R-A5 step 1). It is never taken from the section or from what the refused item would have done.
  - `[r2-fix-Nd]` A MECHANISM RESPONSE (D6) is not a refusal expression for R-A2, even when it contains a refusal verb ("refused: …"). It is a non-effect marker of the attempt it answers (D6 FAILED, R-A5 rank 2).
- **R-A3.** A register row **that itself performs** a norm-making act (adopts, authorizes, registers, supersedes, annotates) is an ACT_OCCURRED of that operation. Its stated content is additionally a NORM.
  - `[r3-G1]` `[r1-fix-B1]` **Scope (closed, and the same for positive and negative acts).** R-A3 applies only when **all** of the following hold:
    - (a) the section is a REGISTER ROW (D1);
    - (b) the **span itself** contains either
      - (b+) a PERFORMATIVE (D2) of one of the five listed operations, matched by lemma: *adopt, authorize, register, supersede, annotate*; or
      - (b−) a **negative disposition expression** (a refusal expression, D5, or a withdrawal or reversal expression) whose operation X (R-A5 step 1) is one of the same five lemmas: "ADR-T14 is not superseded", "NOT ADOPTED", "declines to adopt …".

      Inflections and nominalizations count. Synonyms do not ("approve", "confirm" alone, "classify", "recommend", "apply", "file", "retire", "hold", "direct", "defer", "promote", "resolve" are **not** listed). The negative verb itself (*decline, reject, refuse, withdraw, reverse*) is never the listed operation;
    - (c) the act is a SELF-PERFORMANCE (D3), not REPORTED.

    It is **not** applied by analogy. A performative or disposition found only in the section, row heading or annotation label, and not in the span, does not satisfy (b).

    **Symmetry.** A span that self-performs any other operation, **positively or negatively**, or that self-performs outside a register row, is coded `act.state = NONE` with `act.basis = SELF_PERFORMED_OUTSIDE_RA3`. The operation and `act.state_if_extended` are recorded (R-A5 step 2). Example: in the R-36 row, "Promoted into AST-013 …" and "Expressly NOT promoted: …" are **both** NONE / SELF_PERFORMED_OUTSIDE_RA3, because *promote* is not listed. Adding operations to the list is a schema revision, not a coder judgement (THEORY-CHOICES TC-1).
  - `[r3-G6]` `[r1-fix-B1]` **"Its stated content is additionally a NORM"** is mandatory whenever R-A3 applies, under (b+) or (b−). Code the NORM by R-N3. Because a listed performative, or a negative disposition of a listed operation, always yields at least a norm clause (D8 (iii)), an R-A3 record never has `norm.deontic = NONE`. If the only basis is the performative itself, `norm.basis = PERFORMATIVE_ONLY`.
- **R-A4.** UNKNOWN when the span does not decide. It is counted, and excluded from support counts.

- **R-A5. `[r3-G3]` `[r3-G4]` ACT priority order.**
  - `[r1-fix-M3]` **What this rule decides.** R-A5 decides only the competitions that G3 and G4 raise: a negative disposition vs an occurrence vs an attempt on the same (operation, target), plus the R-A3 scope gate. It adds **no condition** to any state. ACT_OCCURRED for reported acts and ACT_REVERSED keep their r2 meaning (§2). ACT_REFUSED and ACT_WITHDRAWN keep R-A2.
  - **Step 1. Candidates.**
    - Collect the candidate acts the span supports. `[r2-fix-GM]` ~~An act that stands in a **ground-marked segment** (D15) is not a candidate for this record. It may be its own record.~~ `[r3c-fix-SS]` `[r3c-fix-GC]` An act that stands in a **ground-marked segment** (D15) is not a candidate for this record, except under the shared-span exception below. It is entered in `act.ground_candidates`. The list of ground markers in D15 is **closed**: no other wording, punctuation or position makes an act a ground.
    - `[r3c-fix-SS]` **Shared-span exception.** When records share the span (D14, R-S1), a ground-marked act is DISTINCT from the act it grounds (D10), so it is the act of its own record among them (§1). It is a candidate for that one record only. On the other sharing records it is excluded and is **not** entered in `act.ground_candidates`. If every sharing record is already taken by an act outside the ground-marked segments, the ground-marked act is a candidate for none of them and is entered in `act.ground_candidates` on each. On a span that no other record shares, a ground-marked act is never a candidate.
    - `[r2-fix-PV]` **Provenance and location mentions are candidates.** An act that the span mentions only as the provenance or location of an item ("documented in PB-004 retro §9", "already ARB-adopted in Handover §9", "(adoption act: R-86)") is a candidate like any other act the span supports, unless it stands in a ground-marked segment (D15). Its syntactic form does not matter: main predicate, modifier, apposition or verbless fragment. Naming another instrument, document or section as where the act was done is a REPORTED sign (D3 (ii)), so such a mention is a REPORTED candidate. It is never gated (step 2). `[r3c-fix-PR]` A label headed *PROVENANCE* is not a ground label (D15 (ii)), so an act stated after it is a candidate under this bullet.
    - Merge SAME-EVENT candidates (D10). DISTINCT candidates belong to separate records (§1, R-S1).
    - `[r1-fix-C6]` **Merge rule.** When the deciding verb is **not** a listed R-A3 lemma (e.g. *rule, decide, hold, determine, confirm*), and its stated content is the performance, refusal, withdrawal or reversal of operation X, the act is coded **as X** ("rules that X is not superseded" → *supersede*; "confirms and adopts" → *adopt*). A listed lemma is **never** replaced by merging. If the deciding verb is itself a listed lemma, the operation is that lemma and the decided content is its target ("Promotion Review ADOPTED" → *adopt*, target = the Promotion Review). Merging can therefore never move an act off the R-A3 list.
    - `[r1-fix-B2]` **X of a negative disposition.** X is the operation named by the **complement** of the refusal, withdrawal or reversal expression: "declines to resolve an ambiguity …" → *resolve*; "is not superseded" → *supersede*; "NOT ADOPTED" → *adopt*. If the complement is a noun that names no operation ("R-90 … withdrawn"), X is the expression's own lemma (*withdraw*, *reverse*, *refuse*) and the noun is the target.
    - `[r2-fix-Nd]` **X of a mechanism response.** For a MECHANISM RESPONSE (D6), X is the operation of the attempt it answers, as the span states that attempt ("append --json='{"type":"CLOSE",…}' → refused: …" → *append*, target = the CLOSE transition).
  - **Step 2. `[r1-fix-B1]` Scope gate.** A candidate that is a SELF-PERFORMANCE (D3), positive or negative, and fails R-A3 (a) or (b), is removed from step 3. ~~The record is coded `act.state = NONE`, `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.operation = X`. `act.state_if_extended` is the result step 3 would give it.~~ `[r3c-fix-S2]` If no ungated candidate is coded at step 4, the record is coded `act.state = NONE`, `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.operation = X`, where X is the gated candidate that step 4 chooses among the gated candidates, and `act.state_if_extended` is the result step 3 would give that candidate. If an ungated candidate is coded at step 4, the record takes that candidate's state, basis and operation, and the gated candidates are recorded only in `act.gated_candidates` (all with `coded = false`) and as `SPLIT_REQUIRED` notes. REPORTED candidates are never gated, whatever their operation, polarity or section.
    - `[r2-fix-Na]` Every candidate removed here is entered in `act.gated_candidates`, with the state and rank that step 3 would give it, **whether or not** it is the candidate finally coded at step 4.
  - **Step 3. Ranks.** Apply the ranks to each remaining candidate. **The first rank that matches decides.**

| Rank | Condition (span, plus section only where stated) | Result |
|---|---|---|
| 1 | **Negative disposition** of X: a refusal expression (D5), a withdrawal expression or a reversal expression. `[r2-fix-Nd]` A MECHANISM RESPONSE (D6) is never a rank-1 condition | **Refusal:** `[r2-fix-RF]` ACT_REFUSED if a PROPOSAL **or attempt** for X is identified (R-A2, §3). An explicit refusal verb (D5 (i)) without either: UNKNOWN. A negated X without either is not a disposition (D5 (ii), R-A1) and is not a rank-1 candidate. **Withdrawal:** ACT_WITHDRAWN if the withdrawn proposal or attempt is identified (R-A2); otherwise R-A4 applies. **Reversal:** ACT_REVERSED, as in r2 |
| 2 | Initiation of X, plus a non-effect marker (D6) `[r2-fix-Nd]`, including a MECHANISM RESPONSE to it | ACT_ATTEMPTED, with `attempt_kind` (FAILED for a mechanism response) |
| 3 | `[r2-fix-Na]` X occurred: REPORTED (D3), or a SELF-PERFORMANCE (D3) | ACT_OCCURRED |
| 4 | No candidate remains | NONE. `basis = NONE`, or `SELF_PERFORMED_OUTSIDE_RA3` if step 2 removed a candidate |

  - `[r2-fix-Na]` **Rank 3 and the gate.** In default coding, every self-performance that reaches step 3 has already passed the gate, so it satisfies R-A3 (a)–(c). The gate alone enforces R-A3; rank 3 adds no scope condition. When step 3 is applied to a gated candidate to compute `state_if_extended`, rank 3 gives ACT_OCCURRED for a positive candidate, just as rank 1 gives a value for a negative one.
  - **Step 4. `[r1-fix-C8]` Choosing among candidates on a single record.** If candidates for different (operation, target) remain and the record is the only record on the span:
    - code the candidate of the **highest rank**. Rank 1 is the highest and rank 3 the lowest. A candidate removed by the scope gate ranks below rank 3. `[r2-fix-Na]` Among gated candidates, the one with the highest `ext_rank` (rank 1 highest) is coded;
    - between candidates of the same rank `[r2-fix-Na]` (or, among gated candidates, the same `ext_rank`), code the one whose expression occurs **first in the span**;
    - add `SPLIT_REQUIRED: <operation>` for each other candidate to the premise of `act.operation`. `[r2-fix-Na]` A gated candidate that loses here keeps its full entry in `act.gated_candidates`.
  - **Consequences of the order.**
    - A refusal (rank 1) outranks a register row's own performance (rank 3). A register row that negatively disposes of a **listed** X is coded ACT_REFUSED (or ACT_WITHDRAWN / ACT_REVERSED) on X. A row that negatively disposes of an **unlisted** X is NONE / SELF_PERFORMED_OUTSIDE_RA3, exactly like a row that performs an unlisted X (R-A3 Symmetry).
    - An ATTEMPTED act (rank 2) outranks ACT_OCCURRED (rank 3). A PREPARED instrument is never coded ACT_OCCURRED of its own operation.

- **R-A6. `[r3-G4]` PREPARED and other non-effective acts.**
  - An act is ACT_ATTEMPTED only on a **non-effect marker in the source** (D6). The coder never judges, on their own account, whether an actor had the capability or authority to act. Without a marker, an act by any actor is coded at `[r1-fix-M3]` rank 3.
  - A conditional or provisoed authorization ("authorized … provided …") is **not** ATTEMPTED. The act is coded at rank 3. The condition belongs to the NORM.
  - A later adoption, hold or withdrawal recorded in the section is a **separate act**. It is recorded in `act.later_disposition` and does not convert the attempted act to ACT_OCCURRED.

- **R-N1. `[r3-G2]` Level of the governed act.**
  - `norm.about_operation` is the operation that the span's deontic operator, verdict or status predicate **attaches to grammatically** (D13). It is never raised to a meta-act or lowered to a substantive behaviour.
  - Rule content that the span only MENTIONS (D11) supplies `about_target`. It never supplies a separate norm, and its internal modals are not coded.
  - **Examples:**
    - "PROMOTE" applied to a named candidate behaviour attaches to *promote*, with target = that behaviour.
    - "may begin RED" attaches to *begin RED*.
    - "forbids promoting from one" attaches to *promote*.
    - `[r1-fix-C3]` "Remains Candidate" in a promotion matrix row is a status label on the row's behaviour (`status: Candidate`). It is not lifted to *promote* (D12).

- **R-N2. `[r3-G5]` REQUIRED vs PROHIBITED vs STATUS_CONSTRAINT.**
  - The deontic value is read from the **span**. `[r1-fix-M2]` The section may not supply the operator or the status predicate (R-X1).
  - If an obligation operator (D9) or a prohibition operator (D7) attaches to an operation, the value is REQUIRED or PROHIBITED.
  - If there is no deontic operator, and a STATUS PREDICATE (D8) is predicated of an item or operation, the value is STATUS_CONSTRAINT.
  - A status noun or adjective ("standard", "permanent", "candidate", "in force", "binding", "frozen") is a status predicate even if it implies conformity. It is never REQUIRED unless an obligation operator attaches to an operation.

- **R-N3. `[r3-G2]` `[r3-G5]` `[r3-G6]` NORM decision procedure and priority order.**
  1. Collect the norm clauses in the span: deontic operators (`[r1-fix-M1]` D7 for PROHIBITED, D9 for REQUIRED, and the other r2 deontic values in their r2 sense), disposition verdicts (D12), status predicates (D8), and listed performatives (D8 (iii)). `[r1-fix-M4]` In a span that carries a disposition verdict, skip MENTIONED content (D11). `[r2-fix-PC]` Class labels are status predicates (D8 (i)) and are collected like any other status label.
  2. If there are none, the value is NONE.
  3. For each clause, set `about_operation` by literal attachment (R-N1, D13), and `about_target` = the item the clause is predicated of. In a table or matrix row, that is the row's subject item.
  4. `[r1-fix-C7]` **Choose one clause.** Over all clauses of the span, whatever their targets, take the highest class: **OPERATOR > VERDICT > STATUS_PREDICATE > PERFORMATIVE_ONLY.** Between clauses of the same class, take the clause that occurs **first in the span**.
  5. Map the chosen clause:
     - operator → the value it expresses (D7, D9, or the r2 value);
     - verdict → ELIGIBLE / INELIGIBLE;
     - status predicate or performative-only → STATUS_CONSTRAINT.

     Exception: a performative of *authorize* is a deontic operator (AUTHORIZED, `basis = OPERATOR`), because "authorized" is in the deontic vocabulary. `[r1-fix-N3]` It gets `performative_token = true`.
  6. Record `norm.basis`, `norm.performative_token` and `norm.cited_as_ground`.
  7. `[r1-fix-C7]` Record every other clause from step 1 in `norm.other_clauses`.

  `[r1-fix-C7]` The value is UNKNOWN only under R-A4: when the span does not decide whether a clause is present or what it states. Several clauses never make the value UNKNOWN.

- **R-S1. `[r3-G6]` Shared-span rule.** When two or more records share one verbatim span:
  - **ACT:** each record codes **only** the act for its own (operation, target). An act is never coded on more than one record. `[r3c-fix-SS]` This includes an act in a ground-marked segment (R-A5 step 1, shared-span exception).
  - **NORM:** the norm clause(s) of the span, selected by R-N3, are coded **identically on every record sharing the span**. Each record lists the others in `norm.shared_with`. The coder does not choose which record "owns" the norm.

- **R-X1. `[r3-G1]` `[r3-G5]` `[r1-fix-M2]` What the section may not supply.** The section (heading and surrounding source text of the same section) may **not** supply:
  - (i) the performative or negative disposition expression for R-A3 (b);
  - (ii) the deontic operator or status predicate that decides REQUIRED vs PROHIBITED vs STATUS_CONSTRAINT (R-N2).

  Every other use of the section follows r2 (e.g. R-A2's "in the span or its section").

## 5. Definitions `[r3]`

- **D1. REGISTER ROW** `[r3-G1]`. A span whose `section` heading is exactly a register ruling identifier (`## R-<number>`, with nothing else in the heading). These are not register rows: a section about a ruling's attachment ("## R-36 promotion matrix (adopted by R-36): …"), session logs, commits, plans, or any null section.

- **D2. PERFORMATIVE** `[r3-G1]`. A verb, participle or nominalization in the span by which the span's own instrument does the act it names. Examples: present-tense ruling text ("X adopts …", "… is adopted"), a headline participle ("SLICE X AUTHORIZED"), or "is authorized to …" in the instrument's operative text.

- **D3. SELF-PERFORMANCE; act.basis** `[r3-G1]` `[r1-fix-C1]` `[r1-fix-N6]`.
  - **SELF-PERFORMANCE.** The span's own text constitutes the act. It contains a PERFORMATIVE (D2), or a negative disposition expression (D5; a withdrawal or reversal expression) in the present tense, in headline form, or in the instrument's operative text, **and** no REPORTED sign holds. This definition does not refer to R-A3.
  - **REPORTED signs.** Any one of these makes the act REPORTED:
    - (i) a finite past or perfect verb for the act ("was adopted", "ruled", "has been issued"), or a participle used as a narrative clause ("Then R-90 itself withdrawn"). A headline participle or present passive ("SLICE X AUTHORIZED", "X is adopted") is **not** a sign;
    - (ii) another instrument, record or document, or an earlier date, named as where the act was constituted ("(adoption act: R-86)", "per R-44", "in the commission's own artefacts");
    - (iii) conduct verbs ("ran", "appended", "probed").
  - **Reproduced operative text** `[r1-fix-N6]`. A span that reproduces operative text and names its issuer ("RULING AS ISSUED, VERBATIM", "IN THE BOARD'S OWN WORDS", "(Principal Architect instruction)") is:
    - a SELF-PERFORMANCE if the reproduced text is the ruling of the register row it stands in, i.e. no other ruling or instrument is named as its source;
    - REPORTED if another ruling or instrument is named as its source.

    Naming the issuing actor is never by itself a REPORTED sign. The issuer is recorded in `act.attributed_to`.
  - **Values:**
    - **REPORTED:** a REPORTED sign holds.
    - **SELF_PERFORMED:** a SELF-PERFORMANCE for which R-A3 (a) and (b) hold.
    - **SELF_PERFORMED_OUTSIDE_RA3:** a SELF-PERFORMANCE for which R-A3 (a) or (b) fails, positive or negative. Examples: a register row that classifies, recommends, applies, approves, retires, or declines to resolve; a matrix cell that recommends or classifies; a row that "expressly does NOT promote".
    - **NONE:** no act.

- **D4. PROPOSAL** `[r3-G3]` `[r1-fix-B2]` `[r1-fix-C5]`. Text in the span or section stating **in words** that performing X on the target was **put forward for decision**. This includes:
  - a motion, request, offered option or option set;
  - an issued PREPARED ruling (a proposal for its adoption);
  - a recommendation;
  - a question put to the decider of whether to do X.

  A mere possibility, a statement that X has not happened, or a proposal the coder can only infer from context, is not a proposal.
  - **Proposal vs attempt** `[r1-fix-C5]`. One instrument can be both, **for different operations**. An issued PREPARED ruling is:
    - (i) an ACT_ATTEMPTED of the operation it performs (e.g. *authorize*, target WP-4C-1; D6);
    - (ii) a proposal for *adopt*, target = the ruling itself, which the adopting authority may adopt, hold or refuse.

    (i) and (ii) never share an (operation, target). D6's "a proposal is not ATTEMPTED" means that putting X forward for decision is not an attempt at X.

- **D5. REFUSAL EXPRESSION / DISPOSITION** `[r3-G3]`.
  - (i) **Explicit refusal verb:** *decline(s), reject(s/ed), refuse(s/d)*, "will not", "is/are NOT ADOPTED", "expressly NOT [X]".
  - (ii) **Negated X as a disposition:** a negation of X ("X is not superseded") counts only when the span is the decision on the proposal. Either the span states a deciding act ("ruled", "held", "decided") whose content is the negation, or the span is the operative or headline text of the register row that the section identifies as deciding that proposal. `[r1-fix-B2]` The proposal must be identified as SOURCE_FACT (§3).
  - **Never dispositions:** continuative or non-perfective negations ("has not been", "not yet", "remains / still not", "un-"), contrastives ("rather than X"), and reasons against an unchosen option.
  - `[r1-fix-C5]` "NOT ADOPTED" on a PREPARED instrument is both a D6 non-effect marker for the instrument's own operation and, where the adoption proposal is identified, a refusal of *adopt* (target = the instrument). These are different (operation, target) pairs (D4).
  - `[r2-fix-Nd]` A refusal verb inside a MECHANISM RESPONSE (D6) is not a refusal expression in the sense of (i).

- **D6. ACT_ATTEMPTED; PREPARED; FAILED** `[r3-G4]`.
  - **ACT_ATTEMPTED:** the span or section records that an actor **initiated** X (issued, filed, submitted, tried, ran) **and** a source statement says the initiation **did not by itself take effect**. There are two kinds:
    - **PREPARED:** the instrument is marked "PREPARED", "NOT ADOPTED", "awaiting adoption", or as needing a further act to take effect.
    - **FAILED:** the attempt is recorded as rejected or errored by a mechanism or an actor (e.g. "refused: …", "record unchanged").
  - `[r2-fix-Nd]` **MECHANISM RESPONSE.** ~~A refusal, rejection or error expression that the span presents as the **output of a program, tool, machine, test or automated check** (a quoted output, an exit code, "→ refused: …", "returns nothing"), not as the act of a named actor or body.~~ `[r3c-fix-MR]` A refusal, rejection or error expression that the span presents as the **output of a program, tool, machine, test or automated check** (a quoted output, an exit code, "→ refused: …"), not as the act of a named actor or body, **and** that states that the attempt it answers **did not take effect**. A refusal, rejection or error of the attempt ("refused: …", "rejected: …", "error: …", "record unchanged", a non-zero exit reported as the attempt's failure) is such a statement. These are **not** mechanism responses:
    - an output that reports the **result** of an act that took effect, such as an empty result ("returns nothing", "0 matches") or a report of what a test or check found, where no attempt is stated to have been refused, rejected or errored;
    - a test failure that the span or section marks as **intended** ("RED", "expected to fail", "fails as intended"). The run of the test is coded like any other act (rank 3 if it occurred). The intended failure is not a non-effect marker, and it does not by itself make `act.mechanism_refusal` required.
    - A mechanism response to an attempt stated in the span or section is a FAILED marker. The attempt is coded at rank 2: ACT_ATTEMPTED, `attempt_kind = FAILED`, `act.mechanism_refusal = true`. This holds even when the response contains a refusal verb.
    - A mechanism response with no stated attempt is UNKNOWN (R-A4), with `act.mechanism_refusal = true`, so it stays countable.
    - A rejection by a **named actor or body** that uses a refusal expression (D5) is a disposition and is decided at rank 1 (ACT_REFUSED, if the proposal or attempt is identified). FAILED by an actor therefore applies only where the non-effect is stated **without** a refusal expression ("did not take effect", "record unchanged").
  - **Not ATTEMPTED:**
    - a proposal (offering X for decision is not an attempt at X; D4);
    - an intended or planned act;
    - a conditional or provisoed act (R-A6).

- **D7. PROHIBITION OPERATOR** `[r3-G5]` `[r1-fix-M1]` `[r1-fix-NEG]`. Matching is case-insensitive.
  - *must not, shall not, may not, no … shall, is/are forbidden / prohibited, forbids …-ing, NOT PERMITTED*; imperative *never …* / *do not …*.
    - `[r2-fix-DN]` **Exception.** An imperative *never …* / *do not …* whose verb is the meta-operation of a disposition verdict (D12) in the same span ("do not promote") is **not** a prohibition operator. It is the negated verdict form (D12, INELIGIBLE). Any other imperative *never …* / *do not …* ("do not duplicate it") is a prohibition operator.
  - `[r1-fix-NEG]` The permission modal *may*, when a preceding negative quantifier or adverb takes it in its scope (*no <noun> may …*, *nothing may …*, *never may …*), is a prohibition operator. Example: "no self-issued act may adopt" is PROHIBITED, `about_operation = adopt`, `about_actor` = a self-issued act.
  - `[r1-fix-M1]` D7 is not a lexicon for PERMITTED, AUTHORIZED, UNAUTHORIZED or DELEGATED. Those keep their r2 meaning. ELIGIBLE / INELIGIBLE: see D12. REQUIRED: see D9.

- **D8. STATUS PREDICATE; STATUS_CONSTRAINT** `[r3-G5]` `[r3-G6]`. A STATUS PREDICATE is a predicate, with no deontic operator, that assigns an item or operation a **standing in the governance order**. There are three kinds:
  - (i) **Status label.** A lifecycle, standing or class label: open, closed, frozen, deferred, retired, held, in force, governing, binding, permanent, standard, candidate; `[r1-fix-C3]` a category letter in a classification; "remains / stays <label>"; scope statements such as "the register holds X".
    - `[r2-fix-PC]` **Class labels.** A noun phrase predicated of an item ("<item> is / was <NP>", "<item> … as <NP>", "<item>: <NP>") is a class label, and so a status label, when it names a **kind of act, decision, ruling, instrument or document** ("operational acceptance", "a constitutional decision", "a SEPARATE act", "a ruling", "a PLANNING act"), or a **category of a classification scheme that the source states** (a matrix legend category). It is a status predicate whether it is affirmed or reported ("the Authority ruled it was operational acceptance").
    - `[r2-fix-PC]` A contrast attached to the same predication ("X rather than Y", "X, not Y") belongs to that clause and is quoted with it. It is not a separate clause.
    - `[r2-fix-PC]` A class label's `about_operation` is `status: <label>`, even when the label is a nominalization of an operation ("operational acceptance" → `status: operational acceptance`, not *accept*; D13 (iii)). Its `about_target` is the item it is predicated of. `[r3c-fix-NW]` Its normative word, for `norm.performative_token`, is the label noun phrase (§2).
  - (ii) `[r1-fix-M5]` **Non-commencement statement.** The case R-A1 names: a present-tense indicative, with no modal, stating that an operation does not begin (*does not begin / start / commence / proceed*; "no RED begins"). Other present- or future-tense indicatives about whether an operation takes place ("it APPLIES §12", "no observable behaviour changes until 7C", "Implementation continues under …") are **not** status predicates. They are listed in `norm.indicative` (TC-6).
  - (iii) **Listed performative.** A performative or participle of a listed R-A3 operation other than *authorize* (adopted, superseded, annotated, registered), `[r1-fix-B1]` or a negative disposition of one (not superseded, NOT ADOPTED, declines to adopt), predicated of a named object. Its `basis = PERFORMATIVE_ONLY`.

  Statements that a condition, guard or proviso is or is not met are **facts, not status predicates**. STATUS_CONSTRAINT is the value for (i)–(iii).

- **D9. REQUIRED** `[r3-G5]`. An obligation operator attached to an operation: *must, shall, is/are required to, is obliged to, has to, MUST BE …-ed, requires <actor> to …, mandatory*.

- **D10. SAME EVENT / DISTINCT** `[r3-G3]` `[r3-G6]`.
  - **SAME EVENT:** two candidate acts in one span are the same event if one is the deciding or performing act whose **stated content** is the performance, refusal, withdrawal or reversal of the other ("rules that X is not superseded"; "held it — not adopted"). Coordinated verbs with one actor and one object ("confirms and adopts") are also one event. `[r1-fix-C6]` The coded operation follows the merge rule (R-A5 step 1).
  - **DISTINCT:** otherwise. This covers different actors, different times, or an act given as the **ground** of another ("withdrawn … because the Authority ruled …").

- **D11. STATED vs MENTIONED** `[r3-G2]` `[r1-fix-M4]` `[r1-fix-C2]`.
  - **Scope.** D11 applies **only to a span that carries a disposition verdict** (D12), such as a promotion-matrix row.
  - **MENTIONED** (in such a span): content the span names, quotes or proposes **as the object** of the verdict or recommendation (a candidate behaviour's name, a proposed one-line rule text, a quoted principle), and content cited as evidence for the verdict (an evidence column).
  - **STATED:** all other content of the span.
  - In every other span the r2 reading holds: each normative clause the span states is collected, including clauses cited as grounds and clauses reported from another actor. `norm.cited_as_ground` tags the former (TC-5). So R-N1's example "forbids promoting from one" (a ground clause in a session log), and D8 (i)'s "the register holds X" (a ground clause in R06/R07), are coded norms.

- **D12. DISPOSITION VERDICT; ELIGIBLE / INELIGIBLE** `[r3-G2]` `[r1-fix-C3]`.
  - **Disposition verdict:** a classification or recommendation that assigns an item an outcome of a meta-operation under decision in the section, without performing that operation, **and** that contains, in the span, the meta-operation's verb (positive, imperative or negated: "PROMOTE", "promote to …", "NOT promoted", "do not promote") or an eligibility operator (*eligible, not eligible, qualifies, does not qualify*).
    - `[r2-fix-DN]` An imperative of the meta-operation's verb is a verdict form in **both** polarities: "PROMOTE" is ELIGIBLE and "do not promote" is INELIGIBLE. D7's imperative *do not …* does not apply to it (D7 Exception).
  - A phrase that only predicates a status label of the item ("Remains Candidate", "Stays in ADR-MP-06", a category letter) is a STATUS PREDICATE (D8 (i)) on the item, not a verdict. Its `about_operation` is `status: <label>` (R-N1).
  - **ELIGIBLE:** a positive verdict or a positive eligibility operator: the item qualifies for, or is recommended for, operation X.
  - **INELIGIBLE:** a negated verdict or a negative eligibility operator.
  - `about_operation` = X. A verdict is never evidence that X occurred (R-A1).

- **D13. LITERAL ATTACHMENT** `[r3-G2]`. The operation that a norm clause governs is:
  - the verb the operator modifies;
  - for a verdict, the meta-operation the verdict concerns;
  - for a status predicate, the operation named in it, or else `status: <label>`. `[r2-fix-PC]` "Named" means named by a verb or verbal predicate ("does not begin"). A label that is a noun, including a nominalization, names no operation here (D8 (i) class labels; (iii) below).
  - `[r1-fix-D13]` **Two operations named.**
    - (i) One operator, verdict or predicate governing coordinated verbs is **one** clause. `about_operation` lists the verbs in span order, joined by " + " ("AUTHORIZED TO PLAN AND IMPLEMENT" → `plan + implement`).
    - (ii) Separate operators governing separate verbs are **separate** clauses ("MAY BE PLANNED AND MUST NOT BE STARTED" → PERMITTED *plan* and PROHIBITED *start*; R-N3 step 4).
    - (iii) An operation named only by a noun or a name (a subject, an object or an option label) supplies `about_target`, never `about_operation`. `about_operation` is the operation of the predicate word: "ANNOTATE is adopted" → `about_operation = adopt`, `about_target = ANNOTATE` (the option).

- **D14. SHARED SPAN** `[r3-G6]`. Two or more records whose `span` fields are verbatim-identical within the same source and section.

- **D15. GROUND-MARKED SEGMENT** `[r2-fix-GM]`. Used by R-A5 step 1. The list is **closed**, and matching is case-insensitive. A part of the span is ground-marked if and only if it is one of the following:
  - (i) **Ground conjunction.** A clause introduced by *because*, *since* (when not followed by a time expression), *on the ground that*, *on the grounds that*, *on the ground of* or *on the grounds of*. The segment runs from the marker to the end of the sentence.
  - (ii) **Ground label.** Text following a label whose head word is ~~*GROUNDS*, *EVIDENCE*, *REASON*, *RATIONALE*, *BASIS* or *PROVENANCE*~~ `[r3c-fix-PR]` *GROUNDS*, *EVIDENCE*, *REASON*, *RATIONALE* or *BASIS*, where the label ends in a colon or a dash. The label may carry qualifiers before the colon ("GROUNDS AS ISSUED:", "GROUNDS RECORDED BY THE AUTHORITY:", "Basis —"). The segment runs to the next label that ends in a colon, or to the end of the span. The label must be in the span.
    - `[r3c-fix-PR]` A label whose first word is *PROVENANCE* ("PROVENANCE:", "PROVENANCE ANNOTATION …:") is **not** a ground label, whatever words follow. Acts stated after it are candidates under R-A5 step 1 (provenance and location mentions). Such a label still ends a preceding ground-label segment, like any label that ends in a colon.
  - (iii) **Evidence column.** A table cell in a column whose header contains the word *Evidence* (the header is taken from the table the row belongs to).

  Nothing else marks a ground: not a dash, semicolon, colon without a listed head word, parenthesis, apposition, *so*, *as*, *per*, *already*, *given*, or the position of a clause.
