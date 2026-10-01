# Coding schema: acts and norms (coder edition)

Use this document alone. Code ACT first (§4.1), then NORM (§4.2).

## 1. Unit

1.1 A record is one verbatim source span, the smallest sufficient span. You may strip markdown emphasis markers; say so if you do.
1.2 (span, operation, target) is unique. Events resolving to one span that are the SAME EVENT (D10) are one record. A span with an act clause and a norm clause is one record with both.
1.3 A span recording DISTINCT acts (D10) that differ in operation or target gives one record per (operation, target), sharing the span (§4.3). This includes an act in a ground-marked segment (D15), which is DISTINCT from the act it grounds.
1.4 ACT (did an act actually happen?) and NORM (what normative content does the span state?) are coded independently; both may be non-NONE. NORM records stated content whether or not its act took effect, never force. Effect is coded only in ACT.

## 2. Fields

**Status.** Every coded field carries SOURCE_FACT, INFERENCE, UNKNOWN or NOT_EVIDENCED. An INFERENCE states its premise. No silent defaults. A proposal or attempt is identified only as SOURCE_FACT (stated in words in span or section). SAME EVENT vs DISTINCT is SOURCE_FACT if so stated, else INFERENCE with the premise quoted.

**Identification:** `record_id`, `v1_event_ids` (lineage), `source` (file + blob id), `section`, `span`.

**ACT**
- `state` ∈ ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED, NONE, UNKNOWN.
- `operation`: open vocabulary, v1 list preferred; its premise carries `SPLIT_REQUIRED` notes. `actor`, `target`, `quote`.
- `basis` ∈ REPORTED, SELF_PERFORMED, SELF_PERFORMED_OUTSIDE_RA3, NONE (D3); every record. "RA3" is the register-act scope (A2). With OUTSIDE_RA3, `state` is NONE but `operation` and `state_if_extended` are filled.
- `state_if_extended`: iff basis is OUTSIDE_RA3. The A3 result without the gate: any state but NONE, for positive and negative candidates alike. Not `state`.
- `attempt_kind` ∈ PREPARED, FAILED: iff `state` or `state_if_extended` is ACT_ATTEMPTED.
- `gated_candidates`: every record, possibly empty. One entry per gated candidate, including the coded one and A4 losers: `operation`, `target`, `quote`, `state_if_extended`, `attempt_kind` (iff ATTEMPTED), `ext_rank` (rank 1–3 producing it), `coded` (true for the one in `operation`).
- `ground_candidates`: every record, possibly empty. Acts excluded at A1.1: `operation`, `actor`, `target`, `quote`, `marker` (D15 kind plus marker word or column header). No state; changes no other field.
- `mechanism_refusal` (true/false): whenever the span has an explicit refusal verb (D5 (i)) or a FAILED marker (D6) concerning a candidate; true iff it is a MECHANISM RESPONSE.
- `attributed_to` (optional): the issuer that the span or row names for reproduced operative text ("RULING AS ISSUED, VERBATIM", "(Chief Engineer instruction)"). Does not change basis.
- `later_disposition` (optional): a later act in span or section giving or denying the attempted act effect (adopted / held / withdrawn by …): identifier and quote. Does not change `state`.

**NORM**
- `deontic` ∈ PERMITTED, REQUIRED (D9), PROHIBITED (D7), AUTHORIZED, UNAUTHORIZED, ELIGIBLE / INELIGIBLE (D12), DELEGATED, STATUS_CONSTRAINT (D8), NONE, UNKNOWN. Values not defined here take their plain meaning; no word list is given for them.
- `about_operation`, `about_actor`, `about_target`: the governed act; operation by D13. STATUS_CONSTRAINT with no operation named: `status: <verbatim label>`.
- `stated_by`: the stating act or actor, if identifiable, with any PREPARED marker verbatim ("CHIEF STEWARD (PREPARED)"). `quote`.
- `basis` ∈ OPERATOR, VERDICT, STATUS_PREDICATE, PERFORMATIVE_ONLY: whenever `deontic` is not NONE/UNKNOWN.
- `performative_token` (true/false): whenever basis is filled, and on each `other_clauses` entry. True when the clause's normative word is itself an act expression in the span (a PERFORMATIVE, a negative disposition, or the verb or participle reporting an act), whether that act is coded here, gated or REPORTED. All PERFORMATIVE_ONLY clauses and *authorize* performatives are true. For a class label, the normative word is the label noun phrase, not the copula, preposition or colon. A **bare authorize performative**: token true, AUTHORIZED, no governed operation in the span (`about_operation` NOT_EVIDENCED).
- `cited_as_ground` (true/false): whenever basis is filled; true when the chosen clause stands in a segment the span marks as ground or evidence ("because …", "since …", "on the ground that …", "GROUNDS:", "Evidence:", an evidence column).
- `other_clauses`: each collected, unchosen clause: `deontic`, `about_operation`, `about_target`, `basis`, `performative_token`, `quote`. Primary fields stay single-valued.
- `indicative`: {`quote`, `operation`} for each present/future indicative without modal stating whether an operation takes place, other than D8 (ii). Not norms.
- `shared_with`: other `record_id`s on the span.
- `adopted_via`: the instrument the section heading says adopted the section ("(adopted by R-12)" → `R-12`), SOURCE_FACT; else empty, NOT_EVIDENCED. Supplies no act, performative or clause.

## 3. Definitions

**D1 REGISTER ROW.** Section heading exactly a ruling identifier (`## R-<number>`, nothing else). Not: a section about a ruling's attachment ("## R-12 review table (adopted by R-12)"), logs, commits, plans, null sections.

**D2 PERFORMATIVE.** A verb, participle or nominalization by which the span's own instrument does the act it names: present-tense ruling text ("X adopts …", "… is adopted"), a headline participle ("RELEASE 4 AUTHORIZED"), operative "is authorized to …".

**D3 SELF-PERFORMANCE; basis.** Self-performance: the span's text constitutes the act: a PERFORMATIVE, or a negative disposition (D5, withdrawal, reversal) in present tense, headline form or operative text, and no REPORTED sign. REPORTED signs: (i) a finite past or perfect verb ("was adopted", "ruled", "has been issued") or a narrative participle clause ("Then the motion itself withdrawn"), but not a headline participle or present passive; (ii) another instrument, record, document or earlier date named as where the act was constituted ("(adoption act: R-12)", "per R-30"); (iii) conduct verbs ("ran", "appended", "probed"). Reproduced operative text naming its issuer is a self-performance if it is its own register row's ruling (no other source named), REPORTED if another ruling or instrument is named as source; naming the issuer alone is never a sign. Values: REPORTED; SELF_PERFORMED (A2 (a), (b) hold); SELF_PERFORMED_OUTSIDE_RA3 (either polarity, (a) or (b) fails: e.g. a row that classifies, recommends, applies, approves, retires or declines to rename; a matrix cell that recommends or classifies); NONE.

**D4 PROPOSAL.** Span or section text stating in words that doing X was put forward for decision: motion, request, offered option(s), issued PREPARED ruling (for its adoption), recommendation, question to the decider. Not: a possibility, a statement that X has not happened, an inferred proposal. A PREPARED ruling is an attempt at its own operation and a proposal to *adopt* itself; these never share (operation, target). Proposing X is not attempting X.

**D5 REFUSAL / DISPOSITION.** (i) Refusal verb: *decline, reject, refuse*, "will not", "is/are NOT ADOPTED", "expressly NOT [X]". (ii) A negation of X counts only when the span decides an identified proposal: it states a deciding act ("ruled", "held", "decided") with the negation as content, or is the operative or headline text of the register row the section identifies as deciding it. Never: continuative negations ("has not been", "not yet", "remains / still not", "un-"), contrastives ("rather than X"), reasons against an unchosen option. "NOT ADOPTED" on a PREPARED instrument is a non-effect marker for its own operation and, if the adoption proposal is identified, a refusal of *adopt* on it. A refusal verb inside a mechanism response is not (i).

**D6 ATTEMPT; MECHANISM RESPONSE.** ACT_ATTEMPTED: span or section records that an actor initiated X (issued, filed, submitted, tried, ran) and a source statement says it did not by itself take effect. PREPARED: marked "PREPARED", "NOT ADOPTED", "awaiting adoption", or needing a further act. FAILED: rejected or errored by a mechanism or actor ("refused: …", "record unchanged"). MECHANISM RESPONSE: a refusal, rejection or error presented as output of a program, tool, machine, test or automated check (quoted output, exit code, "→ refused: …"), not as a named actor's act, that states the answered attempt did not take effect ("refused: …", "error: …", "record unchanged", a non-zero exit reported as failure). Not mechanism responses: a result of an act that took effect ("returns nothing", "0 matches", what a test found) with no attempt stated as refused, rejected or errored; a test failure marked intended in span or section ("RED", "expected to fail"), whose run is coded like any act and which does not by itself require `mechanism_refusal`. Answering an attempt stated in span or section, a mechanism response is FAILED (rank 2), even with a refusal verb; with no stated attempt, UNKNOWN; both `mechanism_refusal` true. A named actor's refusal expression is a disposition (rank 1); actor FAILED needs non-effect stated without one ("did not take effect"). Not ATTEMPTED: proposals, intended or planned acts, conditional acts.

**D7 PROHIBITION OPERATOR** (case-insensitive): *must not, shall not, may not, no … shall, is/are forbidden / prohibited, forbids …-ing, NOT PERMITTED*, imperative *never / do not*. Except: *never / do not* + the meta-operation verb of a verdict in the span ("do not graduate") is a negated verdict (INELIGIBLE). *May* scoped by a preceding negative (*no <noun> may, nothing may, never may*) is prohibition.

**D8 STATUS PREDICATE** (value STATUS_CONSTRAINT): a predicate without deontic operator giving an item or operation a standing in the governance order.
- (i) Status label: open, closed, frozen, deferred, retired, held, in force, governing, binding, permanent, standard, candidate; a category letter; "remains / stays <label>"; scope statements ("the register holds X"). Class label: a noun phrase predicated of an item ("is / was <NP>", "as <NP>", "<item>: <NP>") naming a kind of act, decision, ruling, instrument or document, or a source-stated classification category; affirmed or reported alike. A contrast in the same predication ("X, not Y") belongs to the clause and is quoted with it. `about_operation = status: <label>`, also for nominalizations ("routine maintenance", not *maintain*); target the item.
- (ii) Non-commencement: present indicative, no modal, that an operation does not begin / start / commence / proceed. Other indicatives about whether an operation takes place go to `indicative`.
- (iii) Listed performative: performative or participle of *adopt, supersede, annotate, register* (not *authorize*), or a negative disposition of one, predicated of a named object; basis PERFORMATIVE_ONLY.
- That a condition or proviso is (not) met is a fact.

**D9 REQUIRED** operator on an operation: *must, shall, is/are required to, is obliged to, has to, MUST BE …-ed, requires <actor> to, mandatory*.

**D10 SAME EVENT / DISTINCT.** Same event: one candidate's stated content is the performance, refusal, withdrawal or reversal of the other ("rules that X is not superseded", "decided: not adopted"), or coordinated verbs with one actor and object ("reviews and adopts"); code by A1.4. DISTINCT otherwise: different actors or times, or a ground of another act.

**D11 STATED / MENTIONED.** Only in verdict spans. MENTIONED: what is named, quoted or proposed as the verdict's object (behaviour name, proposed rule text, quoted principle) and its evidence (evidence column). Elsewhere every stated clause counts, including grounds and reports of others.

**D12 DISPOSITION VERDICT.** Classification or recommendation assigning an item an outcome of a meta-operation under decision in the section, without performing it, containing in the span the meta-operation verb (positive, imperative or negated) or an eligibility operator (*eligible, not eligible, qualifies, does not qualify*). Imperatives count in both polarities. A mere status label on the item ("Remains Draft", "Stays in <doc>", a letter) is D8. ELIGIBLE: positive; INELIGIBLE: negated. `about_operation` = the meta-operation. Never evidence it occurred.

**D13 LITERAL ATTACHMENT.** Governed operation: the verb the operator modifies; a verdict's meta-operation; for a status predicate, the operation it names by a verb or verbal predicate, else `status: <label>` (nouns name no operation). One operator over coordinated verbs is one clause (`build + ship`, span order); separate operators are separate clauses. An operation named only by a noun or name supplies `about_target` ("MERGE is adopted" → *adopt*, target MERGE). Never raise to a meta-act or lower to a behaviour: "GRADUATE" on a behaviour → *graduate*; "may begin testing" → *begin testing*; "Remains Draft" → `status: Draft`.

**D14 SHARED SPAN.** Records with verbatim-identical spans in the same source and section.

**D15 GROUND-MARKED SEGMENT** (closed, case-insensitive): (i) from *because*, *since* (not before a time expression), *on the ground(s) that / of*, to sentence end; (ii) after an in-span label headed *GROUNDS, EVIDENCE, REASON, RATIONALE* or *BASIS* ending in colon or dash, qualifiers allowed ("GROUNDS AS ISSUED:"), to the next colon-label or span end; a label whose first word is *PROVENANCE* is not one but ends a segment; (iii) a cell in a column whose header (in the row's table) contains *Evidence*. Nothing else: no dash, semicolon, other colon, parenthesis, apposition, *so, as, per, already, given*, or position.

## 4. Decision procedures

**General.** G1: never infer an ACT from a NORM ("X is authorized to begin" is not evidence X began; "Rollout does not begin" is a NORM, not ACT_REFUSED, unless an identified proposal or attempt is disposed of). G2: UNKNOWN when the span does not decide; it is counted, apart from decided values. G3: the section may not supply A2 (b)'s performative or disposition, nor the operator or predicate deciding REQUIRED / PROHIBITED / STATUS_CONSTRAINT; elsewhere "span or section" allows it.

### 4.1 ACT

These steps settle only disposition vs attempt vs occurrence and the register-act scope; they add no condition to any state.

**A1 Collect candidates.**
1. Exclude acts in ground-marked segments; enter them in `ground_candidates`. Shared span: such an act is the candidate of its own sharing record only, excluded and not entered elsewhere; if all sharing records are taken by non-ground acts, it is entered on each. Unshared span: never a candidate.
2. Provenance and location mentions ("already adopted in Charter §4", "(adoption act: R-12)") are REPORTED candidates in any syntactic form, unless ground-marked; never gated.
3. Merge SAME EVENT candidates; DISTINCT ones go to separate records.
4. Merge rule: a deciding verb not on the A2 list (*rule, decide, hold, determine, confirm*) whose content performs or disposes of X is coded X. A listed deciding verb is kept, its content the target ("Review Report ADOPTED" → *adopt*).
5. X of a disposition: the operation named by the expression's complement ("declines to rename" → *rename*; "NOT ADOPTED" → *adopt*); for a noun naming no operation, the expression's lemma (*withdraw, reverse, refuse*), noun as target. Never from the section or the item's effect. X of a mechanism response: the answered attempt's operation, as stated.

**A2 Register-act scope; gate.** A register row performing a listed operation is ACT_OCCURRED of it (subject to A3), and its content is also a NORM, only if: (a) REGISTER ROW; (b) the span contains (b+) a PERFORMATIVE of *adopt, authorize, register, supersede, annotate* or (b−) a negative disposition whose X is one ("NOT ADOPTED", "declines to adopt"); inflections and nominalizations count, synonyms do not (*approve, confirm* alone, *classify, recommend, apply, file, retire, hold, direct, defer, promote, resolve*); the negative verb is never the operation; section, row-heading or annotation-label wording does not count; (c) SELF-PERFORMANCE. No analogy. **Gate:** remove every self-performance, either polarity, failing (a) or (b); list it in `gated_candidates` with its A3 state and `ext_rank`. REPORTED candidates are never gated.

**A3 Rank** remaining candidates; first match decides.

| Rank | Condition | Result |
|---|---|---|
| 1 | Refusal (D5), withdrawal or reversal of X; never a mechanism response | Refusal: ACT_REFUSED if a proposal or attempt is identified; refusal verb without: UNKNOWN; negation without: not rank 1. Withdrawal: ACT_WITHDRAWN if identified, else G2. Reversal: ACT_REVERSED |
| 2 | Initiation of X + non-effect marker, incl. mechanism response | ACT_ATTEMPTED + `attempt_kind` (FAILED for mechanism) |
| 3 | X occurred: REPORTED or self-performance | ACT_OCCURRED |
| 4 | None remains | NONE; basis NONE, or OUTSIDE_RA3 if gated |

A reason against an unchosen option is NONE. ATTEMPTED needs a source non-effect marker; never judge capability or authority yourself; without a marker, rank 3. A conditional authorization ("provided …") is rank 3; the condition goes to NORM. A later adoption, hold or withdrawal is `later_disposition`, no recoding. For `state_if_extended`, rank gated candidates as if ungated.

**A4 Choose and code.** For different (operation, target) candidates on the span's only record: highest rank wins (1 highest; gated below 3, ordered by `ext_rank`); tie: first in span; others go to `SPLIT_REQUIRED: <operation>`, gated losers keep entries. Ungated winner: its state, basis, operation; gated entries `coded = false`. Otherwise: NONE, OUTSIDE_RA3, operation and `state_if_extended` of the chosen gated candidate. So a row disposing of a listed X is REFUSED / WITHDRAWN / REVERSED; of an unlisted X, NONE / OUTSIDE_RA3. A PREPARED instrument is never ACT_OCCURRED of its own operation.

**A5 Tags.** Fill all ACT tags. When A2 applies, NORM is mandatory and never NONE (D8 (iii)); performative alone → PERFORMATIVE_ONLY.

### 4.2 NORM

1. **N1** Collect clauses: operators (D7, D9, others plainly), verdicts (D12), status predicates (D8, class labels included), listed performatives. In verdict spans skip MENTIONED content: it supplies `about_target` only; its modals are not coded.
2. **N2** None: NONE.
3. **N3** Per clause: `about_operation` by D13; `about_target` = the item predicated of (in a table row, its subject).
4. **N4** Choose over all clauses: **OPERATOR > VERDICT > STATUS_PREDICATE > PERFORMATIVE_ONLY**; tie: first in span.
5. **N5** Map from the span only: operator → its value (obligation REQUIRED, prohibition PROHIBITED); verdict → ELIGIBLE / INELIGIBLE; status predicate or performative → STATUS_CONSTRAINT. Status words ("standard", "binding") are status predicates even if implying conformity, never REQUIRED without an obligation operator. An *authorize* performative is OPERATOR, AUTHORIZED, token true.
6. **N6** Record basis, token, `cited_as_ground`; other clauses to `other_clauses`; indicatives to `indicative`.
7. **N7** UNKNOWN only when the span does not decide whether a clause exists or what it says; several clauses never mean UNKNOWN.

### 4.3 Shared spans

Each record codes only its own (operation, target), ground-marked acts included; no act on two records. The NORM result is identical on all and cross-listed in `shared_with`; no record owns it.

## 5. Worked examples (invented)

**ACT** ([section])

| Path | Span | Result |
|---|---|---|
| No candidate | "The index has not been rebuilt yet." | NONE / NONE |
| Reported | [## Log 14] "The team ran the migration on staging." | OCCURRED *run*, REPORTED |
| In scope | [## R-412] "Policy P-7 is adopted." | OCCURRED *adopt*, SELF_PERFORMED |
| Reproduced | [## R-490] "RULING AS ISSUED, VERBATIM: P-9 is adopted (Chief Engineer instruction)." | OCCURRED *adopt*, SELF_PERFORMED, `attributed_to` |
| Conditional | [## R-495] "Orca is authorized to migrate, provided the audit passes." | OCCURRED *authorize* |
| Gated | [## R-420] "Dataset Q is classified as restricted." | NONE, OUTSIDE_RA3, *classify*; extended OCCURRED, rank 3 |
| Not register row | [## Charter draft] "We adopt the naming rule." | NONE, OUTSIDE_RA3, *adopt* |
| Gated negative | [## R-431, no proposal] "The Board declines to rename the module." | NONE, OUTSIDE_RA3, *rename*; extended UNKNOWN, rank 1 |
| Refusal | [## R-430; "Motion: supersede ADR-Q2"] "The Board declines to supersede ADR-Q2." | REFUSED *supersede*, SELF_PERFORMED |
| Merge | [## R-460; proposal stated] "The Board rules that ADR-Q2 is not superseded." | REFUSED *supersede* |
| Reported refusal | [## Log 33; "Kite asked to register F-2"] "The registrar refused to register F-2." | REFUSED *register*, REPORTED |
| Nothing identified | [## Log 20] "The chair refused to reopen the budget." | UNKNOWN *reopen* |
| Withdrawal | [## Log 22] "Motion M-4 was withdrawn." | WITHDRAWN *withdraw*, target M-4 |
| Reversal | [## Log 23] "The Board reversed its decision on M-9." | REVERSED *reverse* |
| PREPARED | [## R-440; later "adopted by R-441"] "RELEASE 4 AUTHORIZED — Chief Steward (PREPARED)." | ATTEMPTED *authorize*, PREPARED; `later_disposition` R-441 |
| FAILED | [## Log 31] "Ran deploy → error: permission denied, exit 1, nothing deployed." | ATTEMPTED *deploy*, FAILED; `mechanism_refusal` true |
| No attempt | [## Log 31] "→ rejected: schema mismatch" | UNKNOWN; `mechanism_refusal` true |
| Intended failure | [## Log 32] "Ran the RED tests: 6 failed, as intended." | OCCURRED *run* |
| Ground | [## Log 40] "Wren was archived because the board had retired its sponsor." | OCCURRED *archive*; `ground_candidates` *retire*, (i) |
| Shared | [## Log 41; Ra, Rb] "T-5 was closed because the owner had merged the fix." | Ra *close*, Rb *merge* (by lineage) |
| Ranks | [## R-470; motion offers Rules 12, 13; one record] "The Board adopts Rule 12 and declines to adopt Rule 13." | REFUSED *adopt* Rule 13; `SPLIT_REQUIRED: adopt` |
| Reported vs gated | [## R-480] "Rule 8 is retired, as already recorded in Charter §4." | OCCURRED *record*, REPORTED; gated *retire*, coded false |

**NORM**

| Path | Span | Result |
|---|---|---|
| None | "The team ran the migration." | NONE |
| Indicative | "The service now uses the new queue." | NONE; `indicative` *use* |
| Prohibition | "Kestrel must not call the ledger." | PROHIBITED *call* |
| Negated *may* | "No contractor may approve invoices." | PROHIBITED *approve* |
| Obligation | "Every release shall carry a manifest." | REQUIRED *carry* |
| Two operators | "D MAY BE REVIEWED AND MUST NOT BE SENT." | PERMITTED *review*; other: PROHIBITED *send* |
| Class order | "P-7 is adopted and must be reviewed yearly." | REQUIRED *review*; other: *adopt*, PERFORMATIVE_ONLY |
| Authorize | "ORCA AUTHORIZED TO MIGRATE AND DECOMMISSION." | AUTHORIZED `migrate + decommission`, token true |
| Bare | "RELEASE 4 AUTHORIZED — Chief Steward (PREPARED)." | AUTHORIZED, operation NOT_EVIDENCED; `stated_by` with marker |
| Verdict | [graduation review] "\| Retry with backoff \| GRADUATE \|" | ELIGIBLE *graduate* |
| Imperative | "\| Shadow tables \| do not graduate \|" | INELIGIBLE *graduate* |
| Label | "\| Feature flags \| Stays experimental \|" | STATUS_CONSTRAINT `status: experimental` |
| Non-commencement | "Rollout to region B does not begin." | STATUS_CONSTRAINT *begin rollout* |
| Class label | "The panel ruled the memo was a planning act, not a ruling." | STATUS_CONSTRAINT `status: a planning act`, token false |
| Performative only | "P-7 is adopted." | STATUS_CONSTRAINT *adopt*, PERFORMATIVE_ONLY |
| Ground clause | "Withdrawn because P-2 forbids approving one's own request." | PROHIBITED *approve*, `cited_as_ground` true |

## 6. Output

One JSON object per record. Each coded field is `{"value", "status", "premise"}` (premise required for INFERENCE and for `SPLIT_REQUIRED`). Lists hold objects with the §2 sub-fields. Disclose stripped emphasis in `span_note`.

```json
{"record_id":"","v1_event_ids":[],"source":"","section":"","span":"","span_note":null,
 "act":{"state":{},"operation":{},"actor":{},"target":{},"quote":{},"basis":{},
  "state_if_extended":null,"attempt_kind":null,"mechanism_refusal":null,
  "attributed_to":null,"later_disposition":null,"gated_candidates":[],"ground_candidates":[]},
 "norm":{"deontic":{},"about_operation":{},"about_actor":{},"about_target":{},"stated_by":{},"quote":{},
  "basis":null,"performative_token":null,"cited_as_ground":null,
  "other_clauses":[],"indicative":[],"shared_with":[],"adopted_via":{}}}
```
