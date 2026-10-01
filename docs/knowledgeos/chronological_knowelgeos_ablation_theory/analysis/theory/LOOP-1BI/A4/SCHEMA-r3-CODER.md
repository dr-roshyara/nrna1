# Coding schema: acts and norms (coder edition, r3c-1)

How to read this file:
- Read it as plain text. Every rule sentence stands on its own line and begins with its rule id (`U-`, `F-`, `ST-`, `R-A…`, `R-N…`, `R-S1`, `R-X1`, `D…`).
- The rule lines are the rule sentences of SCHEMA-r3.md, in its order and with its section numbers (§1–§5). Revision tags, struck text, purpose clauses and pointers to other project files are left out. References such as "§3", "D10" or "R-A5 step 1" point to this file.
- Where SCHEMA-r3.md illustrates a rule with an item from the coded domain, this file uses an item from one fictional domain instead: the Elmbrook allotment-garden association (its committee minutes, its rule book and its members' plot logs). Word lists that decide a value are kept exactly as SCHEMA-r3.md gives them.
- NOT IN SCHEMA marks something SCHEMA-r3.md refers to but does not contain. Do not supply it yourself.
- Where this file and SCHEMA-r3.md differ, SCHEMA-r3.md wins.
- §6 gives worked examples, one decision path each. §7 gives the output layout.

---

## 1. Unit: the individuated span record

U-1: One record = one verbatim source span, the smallest sufficient span.
U-2: Fields: `record_id` · `v1_event_ids` (lineage) · `source` (file + blob id) · `section` · `span` (verbatim; markdown emphasis markers may be stripped, and that must be disclosed).
U-2a: How lineage is established for `v1_event_ids`: NOT IN SCHEMA.
U-3: Uniqueness: (span, operation, target) must be unique.
U-4: Events resolving to one span are one record.
U-5: A span carrying an act clause and a norm clause stays one record with both components.
U-6: "Events resolving to one span are one record" applies to SAME-EVENT candidates (D10).
U-7: A span that records two or more DISTINCT acts (D10) that differ in operation or target yields one record per (operation, target).
U-8: Those records share the span and are coded under R-S1.
U-9: This holds also when one of the DISTINCT acts stands in a ground-marked segment (D15).
U-10: An act given as the ground of another is DISTINCT (D10), so on a shared span it is the act of its own record (R-A5 step 1, shared-span exception).

## 2. Two independent components per record

### ACT

F-ACT: ACT: does the span record an act that actually happened?
F-act.state: `act.state` ∈ {ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED, NONE, UNKNOWN}.
F-act.operation.1: `act.operation`: what act (open vocabulary; the v1 operation list is preferred).
F-act.operation.2: The v1 operation list: NOT IN SCHEMA.
F-act.actor: `act.actor` · `act.target` · `act.quote`.
F-act.basis.1: `act.basis` ∈ {REPORTED, SELF_PERFORMED, SELF_PERFORMED_OUTSIDE_RA3, NONE} (D3).
F-act.basis.2: Required on every record.
F-act.basis.3: When `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.state` is NONE, but `act.operation` and `act.state_if_extended` are still filled in (R-A3, R-A5 step 2).
F-act.state_if_extended.1: `act.state_if_extended`: required if and only if `act.basis = SELF_PERFORMED_OUTSIDE_RA3`.
F-act.state_if_extended.2: It is the state R-A5 step 3 would assign if the R-A3 scope gate (R-A5 step 2) were not applied: ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED or UNKNOWN.
F-act.state_if_extended.3: It is not `act.state`.
F-act.state_if_extended.4: Because rank 3 matches any SELF-PERFORMANCE (D3), this value is defined for positive and negative gated acts alike.
F-act.state_if_extended.5: It is never NONE.
F-act.gated_candidates.1: `act.gated_candidates`: required on every record.
F-act.gated_candidates.2: A list with one entry for every candidate that R-A5 step 2 removed, including the candidate coded on the record and every candidate that loses at step 4.
F-act.gated_candidates.3: Each entry has `operation`, `target`, `quote`, `state_if_extended`, `attempt_kind` (if and only if `state_if_extended = ACT_ATTEMPTED`), `ext_rank` (the step-3 rank, 1, 2 or 3, that produced `state_if_extended`) and `coded` (true for the candidate coded in `act.operation`, otherwise false).
F-act.gated_candidates.4: It is empty if step 2 removed no candidate.
F-act.ground_candidates.1: `act.ground_candidates`: required on every record.
F-act.ground_candidates.2: A list with one entry for every act that R-A5 step 1 excluded from this record's candidates because it stands in a ground-marked segment (D15).
F-act.ground_candidates.3: Each entry has `operation`, `actor`, `target`, `quote` and `marker` (the D15 kind, (i), (ii) or (iii), with the marker word or the column header).
F-act.ground_candidates.4: A ground-marked act that is coded on another record sharing the span (R-S1) is not entered.
F-act.ground_candidates.5: The list is empty if step 1 excluded no act.
F-act.ground_candidates.6: It records no state and changes no other field.
F-act.attributed_to.1: `act.attributed_to` (optional): the actor that the span or its row names as the issuer of reproduced operative text ("TEXT AS MOVED, UNCHANGED", "IN THE COMMITTEE'S OWN WORDS", "(as dictated by the treasurer)").
F-act.attributed_to.2: It does not change `act.basis` (D3).
F-act.attempt_kind.1: `act.attempt_kind` ∈ {PREPARED, FAILED} (D6).
F-act.attempt_kind.2: Required if and only if `act.state = ACT_ATTEMPTED` or `act.state_if_extended = ACT_ATTEMPTED`.
F-act.mechanism_refusal.1: `act.mechanism_refusal` ∈ {true, false}.
F-act.mechanism_refusal.2: Required whenever the span contains a refusal expression (D5 (i)) or a FAILED marker (D6) concerning a candidate act.
F-act.mechanism_refusal.3: It is true when that expression is a MECHANISM RESPONSE (D6), otherwise false.
F-act.later_disposition.1: `act.later_disposition` (optional): if the span or its section records a later act that gave the attempted act effect or denied it effect (adopted / held / withdrawn by …), give that act's identifier and a quote.
F-act.later_disposition.2: This does not change `act.state` (R-A6).

### NORM

F-NORM: NORM: what normative / deontic content does the span state?
F-norm.deontic.1: `norm.deontic` ∈ {PERMITTED, REQUIRED, PROHIBITED, AUTHORIZED, UNAUTHORIZED, ELIGIBLE, INELIGIBLE, DELEGATED, STATUS_CONSTRAINT, NONE, UNKNOWN}.
F-norm.deontic.2: REQUIRED, PROHIBITED and STATUS_CONSTRAINT are defined in D7–D9.
F-norm.deontic.3: ELIGIBLE / INELIGIBLE are defined in D12.
F-norm.deontic.4: The other values keep their r2 meaning.
F-norm.deontic.5: r3 gives no lexicon for them.
F-norm.deontic.6: The r2 meaning of PERMITTED, AUTHORIZED, UNAUTHORIZED and DELEGATED: NOT IN SCHEMA.
F-norm.about.1: `norm.about_operation` · `norm.about_actor` · `norm.about_target` (the governed act).
F-norm.about.2: `about_operation` is fixed by literal attachment (D13, R-N1).
F-norm.about.3: For STATUS_CONSTRAINT with no governed operation named, write `status: <verbatim status label>`.
F-norm.stated_by.1: `norm.stated_by`: the act or actor stating it, if identifiable.
F-norm.stated_by.2: If the span or its section marks the stating instrument PREPARED (D6), `stated_by` carries the marker verbatim, e.g. `TREASURER (PREPARED)`.
F-norm.quote: `norm.quote`.
F-norm.basis.1: `norm.basis` ∈ {OPERATOR, VERDICT, STATUS_PREDICATE, PERFORMATIVE_ONLY} (D7, D8, D12, R-N3).
F-norm.basis.2: Required whenever `norm.deontic` is not NONE / UNKNOWN.
F-norm.performative_token.1: `norm.performative_token` ∈ {true, false}.
F-norm.performative_token.2: Required whenever `norm.basis` is filled, and on every entry of `norm.other_clauses`.
F-norm.performative_token.3: It is true when the clause's normative word is itself an act expression in the span: a PERFORMATIVE (D2), a negative disposition expression (D5; a withdrawal or reversal expression), or the verb or participle by which the span reports an act.
F-norm.performative_token.4: This holds whether or not that act is coded on this record, is removed by the scope gate, or is REPORTED.
F-norm.performative_token.5: Every PERFORMATIVE_ONLY clause therefore has `performative_token = true`, and so has an authorize performative coded OPERATOR (R-N3 step 5).
F-norm.performative_token.6: Otherwise it is false.
F-norm.performative_token.7: A bare authorize performative is a clause with `performative_token = true`, `deontic = AUTHORIZED`, and no governed operation stated in the span (`about_operation` with status NOT_EVIDENCED), e.g. "Plot 2 bonfire request AUTHORIZED".
F-norm.performative_token.8: Normative word of a class label. For a class-label clause (D8 (i)), the normative word is the label noun phrase itself, not the copula, preposition or colon that predicates it of the item ("is / was", "as", ":").
F-norm.performative_token.9: The clause has `performative_token = true` only if that noun phrase is itself an act expression in the span in the sense above.
F-norm.cited_as_ground.1: `norm.cited_as_ground` ∈ {true, false}.
F-norm.cited_as_ground.2: Required whenever `norm.basis` is filled.
F-norm.cited_as_ground.3: It is true when the chosen clause stands in a segment that the span marks as the ground of, or evidence for, another statement ("because …", "since …", "on the ground that …", "GROUNDS:", "Evidence:", an evidence column).
F-norm.cited_as_ground.4: Otherwise it is false.
F-norm.other_clauses.1: `norm.other_clauses`: a list of the norm clauses that R-N3 step 1 collected but did not choose.
F-norm.other_clauses.2: Each entry has `deontic`, `about_operation`, `about_target`, `basis`, `performative_token` and `quote`.
F-norm.other_clauses.3: It is empty otherwise.
F-norm.other_clauses.4: The primary NORM fields above stay single-valued.
F-norm.indicative.1: `norm.indicative`: a list of {`quote`, `operation`} for each present- or future-tense indicative in the span that has no modal, states whether an operation takes place, and is not a status predicate under D8 (ii).
F-norm.indicative.2: These are not coded as norms.
F-norm.indicative.3: It is empty otherwise.
F-norm.shared_with: `norm.shared_with`: list of the other `record_id`s that share this span (R-S1); empty otherwise.
F-norm.adopted_via.1: `norm.adopted_via`: the identifier of the instrument that the record's section heading states adopted the section's content ("## R-12 plot rota table (adopted by R-12): …" → `R-12`), with status SOURCE_FACT.
F-norm.adopted_via.2: If the heading states none, it is empty, with status NOT_EVIDENCED.
F-norm.adopted_via.3: It supplies no act, no performative and no norm clause (R-X1).

### Both components

F-BOTH.1: Both components may be non-NONE for the same record.
F-BOTH.2: They are coded independently.
F-BOTH.3: The NORM component records stated content, whether or not the act that stated it took effect.
F-BOTH.4: It does not code the norm's force.
F-BOTH.5: The act's effectiveness is recorded only in the ACT component (`ACT_ATTEMPTED` / `attempt_kind`).

## 3. Epistemic status per coded field

ST-1: Each coded field carries a status ∈ {SOURCE_FACT, INFERENCE, UNKNOWN, NOT_EVIDENCED}.
ST-2: An INFERENCE states its premise.
ST-3: No silent defaults.
ST-4: A PROPOSAL or attempt (D4, R-A2) is identified only as SOURCE_FACT: the span or its section states it in words.
ST-5: A proposal or attempt that could only be inferred counts as not identified (R-A2, R-A5 step 3).
ST-6: Judging SAME EVENT vs DISTINCT (D10) is SOURCE_FACT when the span or section states it in words.
ST-7: Otherwise it is INFERENCE, with the premise quoted.

## 4. Critical rules

### R-A1

R-A1.1: Never infer an ACT from a NORM.
R-A1.2: "X is authorized to begin" is a NORM (AUTHORIZED); it is not evidence that X began.
R-A1.3: "Digging does not begin" is a NORM: STATUS_CONSTRAINT (D8 (ii)), because it has no deontic operator (R-N2).
R-A1.4: It would be PROHIBITED only if it read "must not / shall not begin".
R-A1.5: It is not an ACT_REFUSED unless a proposal or attempt is identified (R-A2, §3) and the span is the disposition of it (D5 (ii)).

### R-A2

R-A2.1: ACT_REFUSED / ACT_WITHDRAWN require an identified proposal or attempt in the span or its section.
R-A2.2: "Proposal" is defined in D4.
R-A2.3: ACT_REFUSED also requires a refusal expression that disposes of that proposal (D5).
R-A2.4: A negation that only reports non-occurrence so far ("has not been issued", "remains unauthorized", "not yet") is never a refusal.
R-A2.5: A reason given against an option that was not chosen, with no refusal expression ("X would shade the next plot"), is not a refusal.
R-A2.6: Its act is coded NONE.
R-A2.7: "Identified" means stated in words in the span or section (SOURCE_FACT, §3).
R-A2.8: An inferred proposal is not identified.
R-A2.9: The refused operation X is the operation named by the complement of the refusal expression (R-A5 step 1).
R-A2.10: It is never taken from the section or from what the refused item would have done.
R-A2.11: A MECHANISM RESPONSE (D6) is not a refusal expression for R-A2, even when it contains a refusal verb ("refused: …").
R-A2.12: It is a non-effect marker of the attempt it answers (D6 FAILED, R-A5 rank 2).

### R-A3

R-A3.1: A register row that itself performs a norm-making act (adopts, authorizes, registers, supersedes, annotates) is an ACT_OCCURRED of that operation.
R-A3.2: Its stated content is additionally a NORM.
R-A3.3: Scope (closed, and the same for positive and negative acts). R-A3 applies only when all of the following hold:
R-A3.4: (a) the section is a REGISTER ROW (D1);
R-A3.5: (b) the span itself contains either (b+) a PERFORMATIVE (D2) of one of the five listed operations, matched by lemma: adopt, authorize, register, supersede, annotate; or
R-A3.6: (b−) a negative disposition expression (a refusal expression, D5, or a withdrawal or reversal expression) whose operation X (R-A5 step 1) is one of the same five lemmas: "the shed rule is not superseded", "NOT ADOPTED", "declines to adopt …".
R-A3.7: Inflections and nominalizations count.
R-A3.8: Synonyms do not ("approve", "confirm" alone, "classify", "recommend", "apply", "file", "retire", "hold", "direct", "defer", "promote", "resolve" are not listed).
R-A3.9: The negative verb itself (decline, reject, refuse, withdraw, reverse) is never the listed operation;
R-A3.10: (c) the act is a SELF-PERFORMANCE (D3), not REPORTED.
R-A3.11: It is not applied by analogy.
R-A3.12: A performative or disposition found only in the section, row heading or annotation label, and not in the span, does not satisfy (b).
R-A3.13: Symmetry. A span that self-performs any other operation, positively or negatively, or that self-performs outside a register row, is coded `act.state = NONE` with `act.basis = SELF_PERFORMED_OUTSIDE_RA3`.
R-A3.14: The operation and `act.state_if_extended` are recorded (R-A5 step 2).
R-A3.15: Example: in a section headed `## R-30`, "Transferred to Mrs Hale: plot 6." and "Expressly NOT transferred: plot 7." are both NONE / SELF_PERFORMED_OUTSIDE_RA3, because transfer is not listed.
R-A3.16: Adding operations to the list is a schema revision, not a coder judgement.
R-A3.17: "Its stated content is additionally a NORM" is mandatory whenever R-A3 applies, under (b+) or (b−).
R-A3.18: Code the NORM by R-N3.
R-A3.19: Because a listed performative, or a negative disposition of a listed operation, always yields at least a norm clause (D8 (iii)), an R-A3 record never has `norm.deontic = NONE`.
R-A3.20: If the only basis is the performative itself, `norm.basis = PERFORMATIVE_ONLY`.

### R-A4

R-A4.1: UNKNOWN when the span does not decide.
R-A4.2: It is counted, and excluded from support counts.

### R-A5. ACT priority order

R-A5.0.1: What this rule decides. R-A5 decides only the competitions that G3 and G4 raise: a negative disposition vs an occurrence vs an attempt on the same (operation, target), plus the R-A3 scope gate.
R-A5.0.2: The gap list that defines G3 and G4: NOT IN SCHEMA; the competitions are the ones named after the colon in R-A5.0.1.
R-A5.0.3: It adds no condition to any state.
R-A5.0.4: ACT_OCCURRED for reported acts and ACT_REVERSED keep their r2 meaning (§2).
R-A5.0.5: The r2 meaning of ACT_OCCURRED for reported acts and of ACT_REVERSED: NOT IN SCHEMA.
R-A5.0.6: ACT_REFUSED and ACT_WITHDRAWN keep R-A2.

Step 1. Candidates.

R-A5.1.1: Collect the candidate acts the span supports.
R-A5.1.2: An act that stands in a ground-marked segment (D15) is not a candidate for this record, except under the shared-span exception below.
R-A5.1.3: It is entered in `act.ground_candidates`.
R-A5.1.4: The list of ground markers in D15 is closed: no other wording, punctuation or position makes an act a ground.
R-A5.1.5: Shared-span exception. When records share the span (D14, R-S1), a ground-marked act is DISTINCT from the act it grounds (D10), so it is the act of its own record among them (§1).
R-A5.1.6: It is a candidate for that one record only.
R-A5.1.7: On the other sharing records it is excluded and is not entered in `act.ground_candidates`.
R-A5.1.8: If every sharing record is already taken by an act outside the ground-marked segments, the ground-marked act is a candidate for none of them and is entered in `act.ground_candidates` on each.
R-A5.1.9: On a span that no other record shares, a ground-marked act is never a candidate.
R-A5.1.10: Provenance and location mentions are candidates.
R-A5.1.11: An act that the span mentions only as the provenance or location of an item ("documented in the autumn minutes §3", "already adopted in the 2023 rule book §9", "(adoption act: minute 12)") is a candidate like any other act the span supports, unless it stands in a ground-marked segment (D15).
R-A5.1.12: Its syntactic form does not matter: main predicate, modifier, apposition or verbless fragment.
R-A5.1.13: Naming another instrument, document or section as where the act was done is a REPORTED sign (D3 (ii)), so such a mention is a REPORTED candidate.
R-A5.1.14: It is never gated (step 2).
R-A5.1.15: A label headed PROVENANCE is not a ground label (D15 (ii)), so an act stated after it is a candidate under R-A5.1.10–R-A5.1.14.
R-A5.1.16: Merge SAME-EVENT candidates (D10).
R-A5.1.17: DISTINCT candidates belong to separate records (§1, R-S1).
R-A5.1.18: Merge rule. When the deciding verb is not a listed R-A3 lemma (e.g. rule, decide, hold, determine, confirm), and its stated content is the performance, refusal, withdrawal or reversal of operation X, the act is coded as X ("rules that X is not superseded" → supersede; "confirms and adopts" → adopt).
R-A5.1.19: A listed lemma is never replaced by merging.
R-A5.1.20: If the deciding verb is itself a listed lemma, the operation is that lemma and the decided content is its target ("Hedge Report ADOPTED" → adopt, target = the Hedge Report).
R-A5.1.21: Merging can therefore never move an act off the R-A3 list.
R-A5.1.22: X of a negative disposition. X is the operation named by the complement of the refusal, withdrawal or reversal expression: "declines to extend a tenancy …" → extend; "is not superseded" → supersede; "NOT ADOPTED" → adopt.
R-A5.1.23: If the complement is a noun that names no operation ("the bonfire motion … withdrawn"), X is the expression's own lemma (withdraw, reverse, refuse) and the noun is the target.
R-A5.1.24: X of a mechanism response. For a MECHANISM RESPONSE (D6), X is the operation of the attempt it answers, as the span states that attempt ("booked the rotavator for 3 May → refused: …" → book, target = the 3 May slot).

Step 2. Scope gate.

R-A5.2.1: A candidate that is a SELF-PERFORMANCE (D3), positive or negative, and fails R-A3 (a) or (b), is removed from step 3.
R-A5.2.2: If no ungated candidate is coded at step 4, the record is coded `act.state = NONE`, `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.operation = X`, where X is the gated candidate that step 4 chooses among the gated candidates, and `act.state_if_extended` is the result step 3 would give that candidate.
R-A5.2.3: If an ungated candidate is coded at step 4, the record takes that candidate's state, basis and operation, and the gated candidates are recorded only in `act.gated_candidates` (all with `coded = false`) and as `SPLIT_REQUIRED` notes.
R-A5.2.4: REPORTED candidates are never gated, whatever their operation, polarity or section.
R-A5.2.5: Every candidate removed here is entered in `act.gated_candidates`, with the state and rank that step 3 would give it, whether or not it is the candidate finally coded at step 4.

Step 3. Ranks.

R-A5.3.1: Apply the ranks to each remaining candidate.
R-A5.3.2: The first rank that matches decides.
R-A5.3.3: The rank table has the columns Rank, Condition (span, plus section only where stated) and Result; its rows are R-A5.3.4 to R-A5.3.13.
R-A5.3.4: Rank 1, condition: Negative disposition of X: a refusal expression (D5), a withdrawal expression or a reversal expression.
R-A5.3.5: Rank 1, condition: A MECHANISM RESPONSE (D6) is never a rank-1 condition.
R-A5.3.6: Rank 1, result, Refusal: ACT_REFUSED if a PROPOSAL or attempt for X is identified (R-A2, §3).
R-A5.3.7: Rank 1, result, Refusal: An explicit refusal verb (D5 (i)) without either: UNKNOWN.
R-A5.3.8: Rank 1, result, Refusal: A negated X without either is not a disposition (D5 (ii), R-A1) and is not a rank-1 candidate.
R-A5.3.9: Rank 1, result, Withdrawal: ACT_WITHDRAWN if the withdrawn proposal or attempt is identified (R-A2); otherwise R-A4 applies.
R-A5.3.10: Rank 1, result, Reversal: ACT_REVERSED, as in r2 (the r2 conditions: NOT IN SCHEMA).
R-A5.3.11: Rank 2, condition: Initiation of X, plus a non-effect marker (D6), including a MECHANISM RESPONSE to it; result: ACT_ATTEMPTED, with `attempt_kind` (FAILED for a mechanism response).
R-A5.3.12: Rank 3, condition: X occurred: REPORTED (D3), or a SELF-PERFORMANCE (D3); result: ACT_OCCURRED.
R-A5.3.13: Rank 4, condition: No candidate remains; result: NONE. `basis = NONE`, or `SELF_PERFORMED_OUTSIDE_RA3` if step 2 removed a candidate.
R-A5.3.14: Rank 3 and the gate. In default coding, every self-performance that reaches step 3 has already passed the gate, so it satisfies R-A3 (a)–(c).
R-A5.3.15: The gate alone enforces R-A3; rank 3 adds no scope condition.
R-A5.3.16: When step 3 is applied to a gated candidate to compute `state_if_extended`, rank 3 gives ACT_OCCURRED for a positive candidate, just as rank 1 gives a value for a negative one.

Step 4. Choosing among candidates on a single record.

R-A5.4.1: If candidates for different (operation, target) remain and the record is the only record on the span:
R-A5.4.2: code the candidate of the highest rank.
R-A5.4.3: Rank 1 is the highest and rank 3 the lowest.
R-A5.4.4: A candidate removed by the scope gate ranks below rank 3.
R-A5.4.5: Among gated candidates, the one with the highest `ext_rank` (rank 1 highest) is coded;
R-A5.4.6: between candidates of the same rank (or, among gated candidates, the same `ext_rank`), code the one whose expression occurs first in the span;
R-A5.4.7: add `SPLIT_REQUIRED: <operation>` for each other candidate to the premise of `act.operation`.
R-A5.4.8: A gated candidate that loses here keeps its full entry in `act.gated_candidates`.

Consequences of the order.

R-A5.C1: A refusal (rank 1) outranks a register row's own performance (rank 3).
R-A5.C2: A register row that negatively disposes of a listed X is coded ACT_REFUSED (or ACT_WITHDRAWN / ACT_REVERSED) on X.
R-A5.C3: A row that negatively disposes of an unlisted X is NONE / SELF_PERFORMED_OUTSIDE_RA3, exactly like a row that performs an unlisted X (R-A3 Symmetry).
R-A5.C4: An ATTEMPTED act (rank 2) outranks ACT_OCCURRED (rank 3).
R-A5.C5: A PREPARED instrument is never coded ACT_OCCURRED of its own operation.

### R-A6. PREPARED and other non-effective acts

R-A6.1: An act is ACT_ATTEMPTED only on a non-effect marker in the source (D6).
R-A6.2: The coder never judges, on their own account, whether an actor had the capability or authority to act.
R-A6.3: Without a marker, an act by any actor is coded at rank 3.
R-A6.4: A conditional or provisoed authorization ("authorized … provided …") is not ATTEMPTED.
R-A6.5: The act is coded at rank 3.
R-A6.6: The condition belongs to the NORM.
R-A6.7: A later adoption, hold or withdrawal recorded in the section is a separate act.
R-A6.8: It is recorded in `act.later_disposition` and does not convert the attempted act to ACT_OCCURRED.

### R-N1. Level of the governed act

R-N1.1: `norm.about_operation` is the operation that the span's deontic operator, verdict or status predicate attaches to grammatically (D13).
R-N1.2: It is never raised to a meta-act or lowered to a substantive behaviour.
R-N1.3: Rule content that the span only MENTIONS (D11) supplies `about_target`.
R-N1.4: It never supplies a separate norm, and its internal modals are not coded.
R-N1.5: Example: "ALLOCATE" applied to a named applicant attaches to allocate, with target = that applicant.
R-N1.6: Example: "may begin sowing" attaches to begin sowing.
R-N1.7: Example: "forbids subletting a plot" attaches to sublet.
R-N1.8: Example: "Remains waiting" in a waiting-list review row is a status label on the row's applicant (`status: waiting`). It is not lifted to allocate (D12).

### R-N2. REQUIRED vs PROHIBITED vs STATUS_CONSTRAINT

R-N2.1: The deontic value is read from the span.
R-N2.2: The section may not supply the operator or the status predicate (R-X1).
R-N2.3: If an obligation operator (D9) or a prohibition operator (D7) attaches to an operation, the value is REQUIRED or PROHIBITED.
R-N2.4: If there is no deontic operator, and a STATUS PREDICATE (D8) is predicated of an item or operation, the value is STATUS_CONSTRAINT.
R-N2.5: A status noun or adjective ("standard", "permanent", "candidate", "in force", "binding", "frozen") is a status predicate even if it implies conformity.
R-N2.6: It is never REQUIRED unless an obligation operator attaches to an operation.

### R-N3. NORM decision procedure and priority order

R-N3.1: Step 1. Collect the norm clauses in the span: deontic operators (D7 for PROHIBITED, D9 for REQUIRED, and the other r2 deontic values in their r2 sense), disposition verdicts (D12), status predicates (D8), and listed performatives (D8 (iii)).
R-N3.2: In a span that carries a disposition verdict, skip MENTIONED content (D11).
R-N3.3: Class labels are status predicates (D8 (i)) and are collected like any other status label.
R-N3.4: Step 2. If there are none, the value is NONE.
R-N3.5: Step 3. For each clause, set `about_operation` by literal attachment (R-N1, D13), and `about_target` = the item the clause is predicated of.
R-N3.6: In a table or matrix row, that is the row's subject item.
R-N3.7: Step 4. Choose one clause. Over all clauses of the span, whatever their targets, take the highest class: OPERATOR > VERDICT > STATUS_PREDICATE > PERFORMATIVE_ONLY.
R-N3.8: Between clauses of the same class, take the clause that occurs first in the span.
R-N3.9: Step 5. Map the chosen clause: operator → the value it expresses (D7, D9, or the r2 value); verdict → ELIGIBLE / INELIGIBLE; status predicate or performative-only → STATUS_CONSTRAINT.
R-N3.10: Exception: a performative of authorize is a deontic operator (AUTHORIZED, `basis = OPERATOR`), because "authorized" is in the deontic vocabulary.
R-N3.11: It gets `performative_token = true`.
R-N3.12: Step 6. Record `norm.basis`, `norm.performative_token` and `norm.cited_as_ground`.
R-N3.13: Step 7. Record every other clause from step 1 in `norm.other_clauses`.
R-N3.14: The value is UNKNOWN only under R-A4: when the span does not decide whether a clause is present or what it states.
R-N3.15: Several clauses never make the value UNKNOWN.

### R-S1. Shared-span rule

R-S1.1: When two or more records share one verbatim span:
R-S1.2: ACT: each record codes only the act for its own (operation, target).
R-S1.3: An act is never coded on more than one record.
R-S1.4: This includes an act in a ground-marked segment (R-A5 step 1, shared-span exception).
R-S1.5: NORM: the norm clause(s) of the span, selected by R-N3, are coded identically on every record sharing the span.
R-S1.6: Each record lists the others in `norm.shared_with`.
R-S1.7: The coder does not choose which record "owns" the norm.

### R-X1. What the section may not supply

R-X1.1: The section (heading and surrounding source text of the same section) may not supply:
R-X1.2: (i) the performative or negative disposition expression for R-A3 (b);
R-X1.3: (ii) the deontic operator or status predicate that decides REQUIRED vs PROHIBITED vs STATUS_CONSTRAINT (R-N2).
R-X1.4: Every other use of the section follows r2 (e.g. R-A2's "in the span or its section").
R-X1.5: The r2 rules on other uses of the section, beyond the example in R-X1.4: NOT IN SCHEMA.

## 5. Definitions

### D1. REGISTER ROW

D1.1: A span whose `section` heading is exactly a register ruling identifier (`## R-<number>`, with nothing else in the heading).
D1.2: These are not register rows: a section about a ruling's attachment ("## R-12 plot rota table (adopted by R-12): …"), session logs, commits, plans, or any null section.

### D2. PERFORMATIVE

D2.1: A verb, participle or nominalization in the span by which the span's own instrument does the act it names.
D2.2: Examples: present-tense ruling text ("X adopts …", "… is adopted"), a headline participle ("HEDGE WORK AUTHORIZED"), or "is authorized to …" in the instrument's operative text.

### D3. SELF-PERFORMANCE; act.basis

D3.1: SELF-PERFORMANCE. The span's own text constitutes the act.
D3.2: It contains a PERFORMATIVE (D2), or a negative disposition expression (D5; a withdrawal or reversal expression) in the present tense, in headline form, or in the instrument's operative text, and no REPORTED sign holds.
D3.3: This definition does not refer to R-A3.
D3.4: REPORTED signs. Any one of these makes the act REPORTED:
D3.5: (i) a finite past or perfect verb for the act ("was adopted", "ruled", "has been issued"), or a participle used as a narrative clause ("Then the bonfire motion itself withdrawn").
D3.6: A headline participle or present passive ("HEDGE WORK AUTHORIZED", "X is adopted") is not a sign;
D3.7: (ii) another instrument, record or document, or an earlier date, named as where the act was constituted ("(adoption act: minute 12)", "per minute 4", "in the treasurer's own ledger");
D3.8: (iii) conduct verbs ("ran", "appended", "probed").
D3.9: Reproduced operative text. A span that reproduces operative text and names its issuer ("TEXT AS MOVED, UNCHANGED", "IN THE COMMITTEE'S OWN WORDS", "(as dictated by the treasurer)") is:
D3.10: a SELF-PERFORMANCE if the reproduced text is the ruling of the register row it stands in, i.e. no other ruling or instrument is named as its source;
D3.11: REPORTED if another ruling or instrument is named as its source.
D3.12: Naming the issuing actor is never by itself a REPORTED sign.
D3.13: The issuer is recorded in `act.attributed_to`.
D3.14: Values: REPORTED: a REPORTED sign holds.
D3.15: Values: SELF_PERFORMED: a SELF-PERFORMANCE for which R-A3 (a) and (b) hold.
D3.16: Values: SELF_PERFORMED_OUTSIDE_RA3: a SELF-PERFORMANCE for which R-A3 (a) or (b) fails, positive or negative.
D3.17: Examples: a register row that classifies, recommends, applies, approves, retires, or declines to extend; a review-table cell that recommends or classifies; a row that "expressly does NOT transfer".
D3.18: Values: NONE: no act.

### D4. PROPOSAL

D4.1: Text in the span or section stating in words that performing X on the target was put forward for decision.
D4.2: This includes: a motion, request, offered option or option set; an issued PREPARED ruling (a proposal for its adoption); a recommendation; a question put to the decider of whether to do X.
D4.3: A mere possibility, a statement that X has not happened, or a proposal the coder can only infer from context, is not a proposal.
D4.4: Proposal vs attempt. One instrument can be both, for different operations.
D4.5: An issued PREPARED ruling is: (i) an ACT_ATTEMPTED of the operation it performs (e.g. authorize, target the plot 12 beehive permit; D6); (ii) a proposal for adopt, target = the ruling itself, which the adopting authority may adopt, hold or refuse.
D4.6: (i) and (ii) never share an (operation, target).
D4.7: D6's "a proposal is not ATTEMPTED" means that putting X forward for decision is not an attempt at X.

### D5. REFUSAL EXPRESSION / DISPOSITION

D5.1: (i) Explicit refusal verb: decline(s), reject(s/ed), refuse(s/d), "will not", "is/are NOT ADOPTED", "expressly NOT [X]".
D5.2: (ii) Negated X as a disposition: a negation of X ("X is not superseded") counts only when the span is the decision on the proposal.
D5.3: Either the span states a deciding act ("ruled", "held", "decided") whose content is the negation, or the span is the operative or headline text of the register row that the section identifies as deciding that proposal.
D5.4: The proposal must be identified as SOURCE_FACT (§3).
D5.5: Never dispositions: continuative or non-perfective negations ("has not been", "not yet", "remains / still not", "un-"), contrastives ("rather than X"), and reasons against an unchosen option.
D5.6: "NOT ADOPTED" on a PREPARED instrument is both a D6 non-effect marker for the instrument's own operation and, where the adoption proposal is identified, a refusal of adopt (target = the instrument).
D5.7: These are different (operation, target) pairs (D4).
D5.8: A refusal verb inside a MECHANISM RESPONSE (D6) is not a refusal expression in the sense of (i).

### D6. ACT_ATTEMPTED; PREPARED; FAILED

D6.1: ACT_ATTEMPTED: the span or section records that an actor initiated X (issued, filed, submitted, tried, ran) and a source statement says the initiation did not by itself take effect.
D6.2: There are two kinds:
D6.3: PREPARED: the instrument is marked "PREPARED", "NOT ADOPTED", "awaiting adoption", or as needing a further act to take effect.
D6.4: FAILED: the attempt is recorded as rejected or errored by a mechanism or an actor (e.g. "refused: …", "record unchanged").
D6.5: MECHANISM RESPONSE. A refusal, rejection or error expression that the span presents as the output of a program, tool, machine, test or automated check (a quoted output, an exit code, "→ refused: …"), not as the act of a named actor or body, and that states that the attempt it answers did not take effect.
D6.6: A refusal, rejection or error of the attempt ("refused: …", "rejected: …", "error: …", "record unchanged", a non-zero exit reported as the attempt's failure) is such a statement.
D6.7: These are not mechanism responses:
D6.8: an output that reports the result of an act that took effect, such as an empty result ("returns nothing", "0 matches") or a report of what a test or check found, where no attempt is stated to have been refused, rejected or errored;
D6.9: a test failure that the span or section marks as intended ("RED", "expected to fail", "fails as intended").
D6.10: The run of the test is coded like any other act (rank 3 if it occurred).
D6.11: The intended failure is not a non-effect marker, and it does not by itself make `act.mechanism_refusal` required.
D6.12: A mechanism response to an attempt stated in the span or section is a FAILED marker.
D6.13: The attempt is coded at rank 2: ACT_ATTEMPTED, `attempt_kind = FAILED`, `act.mechanism_refusal = true`.
D6.14: This holds even when the response contains a refusal verb.
D6.15: A mechanism response with no stated attempt is UNKNOWN (R-A4), with `act.mechanism_refusal = true`, so it stays countable.
D6.16: A rejection by a named actor or body that uses a refusal expression (D5) is a disposition and is decided at rank 1 (ACT_REFUSED, if the proposal or attempt is identified).
D6.17: FAILED by an actor therefore applies only where the non-effect is stated without a refusal expression ("did not take effect", "record unchanged").
D6.18: Not ATTEMPTED: a proposal (offering X for decision is not an attempt at X; D4); an intended or planned act; a conditional or provisoed act (R-A6).

### D7. PROHIBITION OPERATOR

D7.1: Matching is case-insensitive.
D7.2: must not, shall not, may not, no … shall, is/are forbidden / prohibited, forbids …-ing, NOT PERMITTED; imperative never … / do not ….
D7.3: Exception. An imperative never … / do not … whose verb is the meta-operation of a disposition verdict (D12) in the same span ("do not allocate") is not a prohibition operator. It is the negated verdict form (D12, INELIGIBLE).
D7.4: Any other imperative never … / do not … ("do not duplicate it") is a prohibition operator.
D7.5: The permission modal may, when a preceding negative quantifier or adverb takes it in its scope (no <noun> may …, nothing may …, never may …), is a prohibition operator.
D7.6: Example: "no plot holder may sublet" is PROHIBITED, `about_operation = sublet`, `about_actor` = a plot holder.
D7.7: D7 is not a lexicon for PERMITTED, AUTHORIZED, UNAUTHORIZED or DELEGATED.
D7.8: Those keep their r2 meaning (NOT IN SCHEMA).
D7.9: ELIGIBLE / INELIGIBLE: see D12. REQUIRED: see D9.

### D8. STATUS PREDICATE; STATUS_CONSTRAINT

D8.1: A STATUS PREDICATE is a predicate, with no deontic operator, that assigns an item or operation a standing in the governance order. There are three kinds:
D8.2: (i) Status label. A lifecycle, standing or class label: open, closed, frozen, deferred, retired, held, in force, governing, binding, permanent, standard, candidate; a category letter in a classification; "remains / stays <label>"; scope statements such as "the rule book holds X".
D8.3: Class labels. A noun phrase predicated of an item ("<item> is / was <NP>", "<item> … as <NP>", "<item>: <NP>") is a class label, and so a status label, when it names a kind of act, decision, ruling, instrument or document ("routine maintenance", "a members' decision", "a SEPARATE job", "a minute", "a courtesy notice"), or a category of a classification scheme that the source states (a table legend category).
D8.4: It is a status predicate whether it is affirmed or reported ("the committee found it was routine maintenance").
D8.5: A contrast attached to the same predication ("X rather than Y", "X, not Y") belongs to that clause and is quoted with it.
D8.6: It is not a separate clause.
D8.7: A class label's `about_operation` is `status: <label>`, even when the label is a nominalization of an operation ("routine maintenance" → `status: routine maintenance`, not maintain; D13 (iii)).
D8.8: Its `about_target` is the item it is predicated of.
D8.9: Its normative word, for `norm.performative_token`, is the label noun phrase (§2).
D8.10: (ii) Non-commencement statement. The case R-A1 names: a present-tense indicative, with no modal, stating that an operation does not begin (does not begin / start / commence / proceed; "no RED begins").
D8.11: Other present- or future-tense indicatives about whether an operation takes place ("the rota APPLIES from May", "no watering changes until June", "Pruning continues under …") are not status predicates.
D8.12: They are listed in `norm.indicative`.
D8.13: (iii) Listed performative. A performative or participle of a listed R-A3 operation other than authorize (adopted, superseded, annotated, registered), or a negative disposition of one (not superseded, NOT ADOPTED, declines to adopt), predicated of a named object.
D8.14: Its `basis = PERFORMATIVE_ONLY`.
D8.15: Statements that a condition, guard or proviso is or is not met are facts, not status predicates.
D8.16: STATUS_CONSTRAINT is the value for (i)–(iii).

### D9. REQUIRED

D9.1: An obligation operator attached to an operation: must, shall, is/are required to, is obliged to, has to, MUST BE …-ed, requires <actor> to …, mandatory.

### D10. SAME EVENT / DISTINCT

D10.1: SAME EVENT: two candidate acts in one span are the same event if one is the deciding or performing act whose stated content is the performance, refusal, withdrawal or reversal of the other ("rules that X is not superseded"; "held it — not adopted").
D10.2: Coordinated verbs with one actor and one object ("confirms and adopts") are also one event.
D10.3: The coded operation follows the merge rule (R-A5 step 1).
D10.4: DISTINCT: otherwise. This covers different actors, different times, or an act given as the ground of another ("withdrawn … because the committee found …").

### D11. STATED vs MENTIONED

D11.1: Scope. D11 applies only to a span that carries a disposition verdict (D12), such as a waiting-list review row.
D11.2: MENTIONED (in such a span): content the span names, quotes or proposes as the object of the verdict or recommendation (a candidate behaviour's name, a proposed one-line rule text, a quoted principle), and content cited as evidence for the verdict (an evidence column).
D11.3: STATED: all other content of the span.
D11.4: In every other span the r2 reading holds: each normative clause the span states is collected, including clauses cited as grounds and clauses reported from another actor.
D11.5: `norm.cited_as_ground` tags the former.
D11.6: So R-N1's example "forbids subletting a plot" (when it is a ground clause in committee minutes), and D8 (i)'s "the rule book holds X" (when it is a ground clause), are coded norms.

### D12. DISPOSITION VERDICT; ELIGIBLE / INELIGIBLE

D12.1: Disposition verdict: a classification or recommendation that assigns an item an outcome of a meta-operation under decision in the section, without performing that operation, and that contains, in the span, the meta-operation's verb (positive, imperative or negated: "ALLOCATE", "allocate to …", "NOT allocated", "do not allocate") or an eligibility operator (eligible, not eligible, qualifies, does not qualify).
D12.2: An imperative of the meta-operation's verb is a verdict form in both polarities: "ALLOCATE" is ELIGIBLE and "do not allocate" is INELIGIBLE.
D12.3: D7's imperative do not … does not apply to it (D7 Exception).
D12.4: A phrase that only predicates a status label of the item ("Remains waiting", "Stays on list B", a category letter) is a STATUS PREDICATE (D8 (i)) on the item, not a verdict.
D12.5: Its `about_operation` is `status: <label>` (R-N1).
D12.6: ELIGIBLE: a positive verdict or a positive eligibility operator: the item qualifies for, or is recommended for, operation X.
D12.7: INELIGIBLE: a negated verdict or a negative eligibility operator.
D12.8: `about_operation` = X.
D12.9: A verdict is never evidence that X occurred (R-A1).

### D13. LITERAL ATTACHMENT

D13.1: The operation that a norm clause governs is: the verb the operator modifies; for a verdict, the meta-operation the verdict concerns; for a status predicate, the operation named in it, or else `status: <label>`.
D13.2: "Named" means named by a verb or verbal predicate ("does not begin").
D13.3: A label that is a noun, including a nominalization, names no operation here (D8 (i) class labels; (iii) below).
D13.4: Two operations named. (i) One operator, verdict or predicate governing coordinated verbs is one clause.
D13.5: `about_operation` lists the verbs in span order, joined by " + " ("authorized to dig and plant" → `dig + plant`).
D13.6: (ii) Separate operators governing separate verbs are separate clauses ("may be measured and must not be fenced" → PERMITTED measure and PROHIBITED fence; R-N3 step 4).
D13.7: (iii) An operation named only by a noun or a name (a subject, an object or an option label) supplies `about_target`, never `about_operation`.
D13.8: `about_operation` is the operation of the predicate word: "FENCING is adopted" → `about_operation = adopt`, `about_target = FENCING` (the option).

### D14. SHARED SPAN

D14.1: Two or more records whose `span` fields are verbatim-identical within the same source and section.

### D15. GROUND-MARKED SEGMENT

D15.1: Used by R-A5 step 1. The list is closed, and matching is case-insensitive. A part of the span is ground-marked if and only if it is one of the following:
D15.2: (i) Ground conjunction. A clause introduced by because, since (when not followed by a time expression), on the ground that, on the grounds that, on the ground of or on the grounds of.
D15.3: The segment runs from the marker to the end of the sentence.
D15.4: (ii) Ground label. Text following a label whose head word is GROUNDS, EVIDENCE, REASON, RATIONALE or BASIS, where the label ends in a colon or a dash.
D15.5: The label may carry qualifiers before the colon ("GROUNDS AS ISSUED:", "GROUNDS RECORDED BY THE SECRETARY:", "Basis —").
D15.6: The segment runs to the next label that ends in a colon, or to the end of the span.
D15.7: The label must be in the span.
D15.8: A label whose first word is PROVENANCE ("PROVENANCE:", "PROVENANCE ANNOTATION …:") is not a ground label, whatever words follow.
D15.9: Acts stated after it are candidates under R-A5 step 1 (provenance and location mentions).
D15.10: Such a label still ends a preceding ground-label segment, like any label that ends in a colon.
D15.11: (iii) Evidence column. A table cell in a column whose header contains the word Evidence (the header is taken from the table the row belongs to).
D15.12: Nothing else marks a ground: not a dash, semicolon, colon without a listed head word, parenthesis, apposition, so, as, per, already, given, or the position of a clause.

---

## 6. Worked examples (fictional: the Elmbrook allotment-garden association)

The association keeps committee minutes (sections such as `## Minutes, 9 June`), a rule book in which each committee resolution is a section headed `## R-<number>` and nothing else, and plot logs (sections such as `## Plot log, plot 3`). Every example below is invented. Each one shows a single decision path. Unless stated otherwise, the span has one record. Only the fields the path decides are shown. Each result follows from the rule lines cited; no example adds a rule.

### 6.1 ACT

E-A01: Path: no candidate. Section `## Minutes, 3 March`. Span: "The pond has not been cleared yet." Result: `act.state` NONE, `act.basis` NONE. Rules: D5.5, R-A2.4, R-A5.3.13.

E-A02: Path: reported occurrence. Section `## Plot log, plot 14`. Span: "Mrs Hale dug over the north bed on Saturday." Result: ACT_OCCURRED, operation dig, basis REPORTED. Rules: D3.5, D3.8, R-A5.3.12.

E-A03: Path: in scope, positive (R-A3 (b+)). Section `## R-14`. Span: "The hedge-height rule is adopted." Result: ACT_OCCURRED, operation adopt, target the hedge-height rule, basis SELF_PERFORMED. Rules: R-A3.1–R-A3.10, D2.2, D3.15.

E-A04: Path: reproduced operative text naming its issuer. Section `## R-15`. Span: "Text as moved by the treasurer, unchanged: the bonfire rule is adopted." Result: ACT_OCCURRED, operation adopt, basis SELF_PERFORMED, `attributed_to` the treasurer. Rules: D3.9, D3.10, D3.12, D3.13.

E-A05: Path: conditional authorization. Section `## R-16`. Span: "Plot 9 is authorized to keep hens, provided the coop is fox-proof." Result: ACT_OCCURRED, operation authorize, basis SELF_PERFORMED (rank 3, not ATTEMPTED); the condition goes to NORM. Rules: R-A6.4–R-A6.6.

E-A06: Path: gated, positive, unlisted operation. Section `## R-17`. Span: "The committee hereby approves the new compost bay." Result: NONE, basis SELF_PERFORMED_OUTSIDE_RA3, operation approve, `state_if_extended` ACT_OCCURRED; `gated_candidates` one entry, `ext_rank` 3, `coded` true. Rules: R-A3.8, R-A5.2.1, R-A5.2.2, R-A5.3.16.

E-A07: Path: gated, listed operation outside a D1 section. Section `## Minutes, 5 May`. Span: "The meeting adopts the new watering rota." Result: NONE, basis SELF_PERFORMED_OUTSIDE_RA3, operation adopt, `state_if_extended` ACT_OCCURRED, `ext_rank` 3. Rules: D1.1, R-A3.4, R-A3.13, R-A5.2.2.

E-A08: Path: gated, negative, unlisted operation, proposal identified. Section `## R-18`; the section states "Request from plot 3: extend the tenancy to a second plot." Span: "The committee will not extend the tenancy of plot 3." Result: NONE, basis SELF_PERFORMED_OUTSIDE_RA3, operation extend, `state_if_extended` ACT_REFUSED, `ext_rank` 1; `mechanism_refusal` false. Rules: D5.1, R-A5.1.22, R-A5.2.1, R-A5.3.6, R-A5.C3, F-act.mechanism_refusal.2.

E-A09: Path: refusal of a listed operation, proposal for X on the target identified. Section `## R-19`; the section states "Motion from plot 7: adopt a ban on rotavators before 9 a.m." Span: "The committee declines to adopt the rotavator ban." Result: ACT_REFUSED, operation adopt, target the rotavator ban, basis SELF_PERFORMED; `mechanism_refusal` false. Rules: D4.1, R-A2.3, R-A5.3.6, R-A5.C2.

E-A10: Path: refusal verb, but no proposal for X on this target. Section `## R-20`; the section states "Motion from plot 7: adopt a ban on rotavators before 9 a.m." Span: "The committee declines to adopt the strimmer ban." Result: UNKNOWN, operation adopt, target the strimmer ban, basis SELF_PERFORMED; `mechanism_refusal` false. The only proposal is for adopt on another target (the rotavator ban). Rules: D4.1, R-A2.3, R-A5.3.7.

E-A11: Path: merge rule (unlisted deciding verb, negated listed X). Section `## R-21`; the section states "Motion: supersede the 2019 shed rule." Span: "The committee decides that the 2019 shed rule is not superseded." Result: ACT_REFUSED, operation supersede, target the 2019 shed rule, basis SELF_PERFORMED. Rules: D10.1, R-A5.1.18, D5.2, D5.3, R-A5.3.6.

E-A12: Path: reported refusal. Section `## Minutes, 9 June`; the minutes state "Mr Patel asked the committee to annotate rule 6 with the new pond depth." Span: "The committee refused to annotate rule 6." Result: ACT_REFUSED, operation annotate, target rule 6, basis REPORTED; `mechanism_refusal` false. Rules: D3.5, R-A5.2.4, R-A5.3.6.

E-A13: Path: withdrawal, complement names no operation. Section `## Minutes, 9 June`. Span: "Mrs Hale's bonfire motion was withdrawn." Result: ACT_WITHDRAWN, operation withdraw, target Mrs Hale's bonfire motion, basis REPORTED. Rules: R-A5.1.23, R-A5.3.9.

E-A14: Path: reversal. Section `## Minutes, 9 June`. Span: "The committee reversed its March decision on hosepipes." Result: ACT_REVERSED, operation reverse, target the March decision on hosepipes, basis REPORTED. Rules: R-A5.3.10.

E-A15: Path: PREPARED attempt with a later disposition. Section `## R-22`; later in the section: "Adopted at the July meeting, minute 4." Span: "Hedge works on the east boundary authorized; prepared by the treasurer, awaiting adoption." Result: ACT_ATTEMPTED, operation authorize, `attempt_kind` PREPARED, basis SELF_PERFORMED; `later_disposition` minute 4 with its quote. Rules: D6.1, D6.3, R-A5.3.11, R-A5.C5, R-A6.7, R-A6.8.

E-A16: Path: FAILED by a mechanism response to a stated attempt. Section `## Plot log, plot 3`. Span: "Booked the rotavator for 3 May on the hire terminal → refused: slot already taken." Result: ACT_ATTEMPTED, operation book, target the 3 May slot, `attempt_kind` FAILED, `mechanism_refusal` true. Rules: D6.5, D6.12, D6.13, R-A5.1.24.

E-A17: Path: mechanism response with no stated attempt. Section `## Plot log, plot 3`. Span: "Hire terminal: → error: card not accepted." Result: UNKNOWN, `mechanism_refusal` true. Rules: D6.15.

E-A18: Path: output reporting the result of an act that took effect. Section `## Plot log, plot 5`. Span: "Ran the soil test kit on bed 2: 0 samples above the lead limit." Result: ACT_OCCURRED, operation run, basis REPORTED; not a mechanism response; `mechanism_refusal` not required. Rules: D6.8, R-A5.3.12.

E-A19: Path: test failure marked as intended. Section `## Plot log, plot 5`. Span: "Ran the germination check on last year's seed; every tray fails as intended." Result: ACT_OCCURRED, operation run, basis REPORTED (rank 3); the intended failure is not a non-effect marker; `mechanism_refusal` not required. Rules: D6.9–D6.11.

E-A20: Path: ground-marked act on an unshared span. Section `## Minutes, 9 June`; one record on this span. Span: "Plot 11 was re-let to Mr Osei. Reason: the previous tenant gave notice in April." Result: ACT_OCCURRED, operation re-let, basis REPORTED; `ground_candidates` one entry: operation give notice, actor the previous tenant, marker (ii) "Reason:". Rules: D15.4, R-A5.1.2, R-A5.1.3, R-A5.1.9, F-act.ground_candidates.2–.3.

E-A21: Path: shared span, no ground marker. Section `## Minutes, 2 July`; records M1 and M2 share this span (D14). Span: "Plot 4 was re-let after the committee had fenced the boundary." Result: the span records two DISTINCT acts, so there is one record per (operation, target) (U-7). M1 is the record for (re-let, plot 4) and codes ACT_OCCURRED re-let, REPORTED. M2 is the record for (fence, the boundary) and codes ACT_OCCURRED fence, REPORTED. NORM is identical on both (NONE); `shared_with` lists the other. Rules: U-7, U-8, R-S1.2, R-S1.3, R-S1.5, R-S1.6.

E-A22: Path: shared span with a ground-marked act. Section `## Minutes, 9 June`; records M3 and M4 share the span of E-A20. Result: M3 is the record for (re-let, plot 11) and codes ACT_OCCURRED re-let. M4 is the record for (give notice, the plot 11 tenancy) and codes ACT_OCCURRED give notice, REPORTED: on a shared span the ground-marked act is the act of its own record. `ground_candidates` is empty on both. Rules: U-9, U-10, R-A5.1.5–R-A5.1.7, R-S1.4.

E-A23: Path: step 4, rank 1 beats rank 3 on a single record. Section `## R-23`; the section states "Motion: adopt the water-butt rule and the bonfire rule." One record on this span. Span: "The committee adopts the water-butt rule and declines to adopt the bonfire rule." Result: ACT_REFUSED, operation adopt, target the bonfire rule; premise of `act.operation` carries `SPLIT_REQUIRED: adopt`. Rules: R-A5.4.1–R-A5.4.7, R-A5.C1.

E-A24: Path: step 2 and step 4, an ungated reported candidate beats a gated one. Section `## R-24`. One record on this span. Span: "The committee hereby approves the pond fence, for which the members voted at the spring meeting." Result: ACT_OCCURRED, operation vote, basis REPORTED; `gated_candidates` one entry: approve, `state_if_extended` ACT_OCCURRED, `ext_rank` 3, `coded` false; premise carries `SPLIT_REQUIRED: approve`. Rules: R-A5.2.3, R-A5.2.4, R-A5.4.4, R-A5.4.8.

E-A25: Path: provenance mention as a reported candidate. Section `## Minutes, 2 July`. Span: "The new hedge rule (adoption act: minute 5 of the general meeting) applies from August." Result: ACT_OCCURRED, operation adopt, target the new hedge rule, basis REPORTED; never gated. Rules: R-A5.1.10–R-A5.1.14, D3.7.

E-A26: Path: a PROVENANCE label ends a ground segment. Section `## Minutes, 2 July`. Span: "Hedges, 1.5 m at most. Reason: light for the next plot. Provenance: adopted at the spring meeting." Result: the "Reason:" segment ends at "Provenance:"; the act after it is a candidate: ACT_OCCURRED, operation adopt, basis REPORTED; `ground_candidates` empty (no act in the Reason segment). Rules: D15.6, D15.8–D15.10, R-A5.1.15.

### 6.2 NORM

E-N01: Path: no clause. Span: "Mrs Hale dug over the north bed on Saturday." Result: `norm.deontic` NONE. Rules: R-N3.4.

E-N02: Path: indicative, not a norm. Span: "Watering continues under the summer rota." Result: NONE; `norm.indicative` one entry, operation water. Rules: D8.11, D8.12, F-norm.indicative.1.

E-N03: Path: prohibition operator. Span: "Members must not burn plastic on site." Result: PROHIBITED, `about_operation` burn, basis OPERATOR. Rules: D7.2, R-N2.3.

E-N04: Path: may scoped by a preceding negative. Span: "Nothing may be planted within a metre of the path." Result: PROHIBITED, `about_operation` plant, basis OPERATOR. Rules: D7.5.

E-N05: Path: obligation operator. Span: "Every tenant is required to keep the path clear." Result: REQUIRED, `about_operation` keep clear, basis OPERATOR. Rules: D9.1, R-N2.3.

E-N06: Path: coordinated verbs under one operator. Span: "Plot holders are required to weed and mulch their borders." Result: REQUIRED, `about_operation` `weed + mulch`. Rules: D13.4, D13.5.

E-N07: Path: two operators, same class, first in span wins. Span: "The shed may be repainted, but it must not be enlarged." Result: PERMITTED, `about_operation` repaint; `other_clauses`: PROHIBITED enlarge. Rules: D13.6, R-N3.7, R-N3.8, R-N3.13.

E-N08: Path: class order, operator over listed performative. Section `## R-25`. Span: "The pond rule is adopted and must be reviewed every spring." Result: REQUIRED, `about_operation` review, basis OPERATOR; `other_clauses`: STATUS_CONSTRAINT, adopt, PERFORMATIVE_ONLY, `performative_token` true. Rules: R-N3.7, D8.13, D8.14.

E-N09: Path: authorize performative with a governed operation. Span: "Mr Osei is authorized to prune the orchard." Result: AUTHORIZED, `about_operation` prune, basis OPERATOR, `performative_token` true. Rules: R-N3.10, R-N3.11.

E-N10: Path: bare authorize performative. Span: "Plot 2 bonfire request AUTHORIZED." Result: AUTHORIZED, `about_operation` NOT_EVIDENCED, basis OPERATOR, `performative_token` true. Rules: F-norm.performative_token.7.

E-N11: Path: positive verdict. Section `## Waiting-list review, spring` (the section decides which applicants get a free plot). Span: "| Ms Okafor | allocate |". Result: ELIGIBLE, `about_operation` allocate, `about_target` Ms Okafor, basis VERDICT. Rules: D12.1, D12.6, D12.8.

E-N12: Path: imperative negative verdict (not a prohibition). Same section. Span: "| Mr Lund | do not allocate |". Result: INELIGIBLE, `about_operation` allocate, basis VERDICT. Rules: D7.3, D12.2.

E-N13: Path: status label in a verdict table, not a verdict. Same section. Span: "| Mr Brandt | remains waiting |". Result: STATUS_CONSTRAINT, `about_operation` `status: waiting`, basis STATUS_PREDICATE. Rules: D12.4, D12.5, R-N1.8.

E-N14: Path: non-commencement statement. Span: "Digging on the new field does not begin." Result: STATUS_CONSTRAINT, `about_operation` begin digging, basis STATUS_PREDICATE. Rules: D8.10, D13.2, R-A1.3.

E-N15: Path: class label with a contrast. Span: "The committee found that the gate repair was routine maintenance, not a members' decision." Result: STATUS_CONSTRAINT, `about_operation` `status: routine maintenance`, `about_target` the gate repair, quote includes "not a members' decision", `performative_token` false. Rules: D8.3–D8.9, F-norm.performative_token.8–.9.

E-N16: Path: listed performative only. Section `## R-14`. Span: "The hedge-height rule is adopted." Result: STATUS_CONSTRAINT, `about_operation` adopt, basis PERFORMATIVE_ONLY, `performative_token` true. Rules: D8.13, D8.14, R-A3.17–R-A3.20.

E-N17: Path: clause cited as a ground. Span: "The bonfire motion was dropped, since rule 3 forbids lighting fires in summer." Result: PROHIBITED, `about_operation` light, `about_target` fires, basis OPERATOR, `cited_as_ground` true. Rules: D7.2, D11.4, D11.5, F-norm.cited_as_ground.3.

## 7. Output layout

This section is layout only; it adds no rule and no field beyond §1–§3.
One JSON object per record. Each coded field is `{"value", "status", "premise"}`; the premise is required for INFERENCE (ST-2) and for `SPLIT_REQUIRED` notes (R-A5.4.7). Lists hold objects with the sub-fields given in §2. The disclosure of stripped emphasis markers (U-2) is written in `span_note`.

```json
{"record_id":"","v1_event_ids":[],"source":"","section":"","span":"","span_note":null,
 "act":{"state":{},"operation":{},"actor":{},"target":{},"quote":{},"basis":{},
  "state_if_extended":null,"attempt_kind":null,"mechanism_refusal":null,
  "attributed_to":null,"later_disposition":null,"gated_candidates":[],"ground_candidates":[]},
 "norm":{"deontic":{},"about_operation":{},"about_actor":{},"about_target":{},"stated_by":{},"quote":{},
  "basis":null,"performative_token":null,"cited_as_ground":null,
  "other_clauses":[],"indicative":[],"shared_with":[],"adopted_via":{}}}
```
