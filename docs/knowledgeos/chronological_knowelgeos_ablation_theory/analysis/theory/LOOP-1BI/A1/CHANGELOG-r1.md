# CHANGELOG-r1: targeted repair of SCHEMA-r3 (r0 → r1)

Inputs used: r0 `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`; `REVIEW-r0.md` / `.json` (verdict FAIL); `SCHEMA-r2.md`, `GAPS.md`, `RECORDS.json`, `SOURCES.md`, `HYPOTHESES.md`. No commands, no web.

Scope: only the items in TASK.md. Every change is tagged `[r1-fix-<id>]` in the schema. Text that r0 added and r1 keeps still carries its `[r3-Gn]` tag.

**Rule-selection statement.** No rule was chosen to reproduce an existing coding. The repair had no coder output to consult. Each rule follows from a principle already in r2 / r0: the closed R-A3 list, literal attachment, the source-marking requirement, or the r2 text. Where r1 changes a value that r0 produced (R19–R21, R27, R01), the change is a consequence of the rule, and it is reported below.

---

## 1. Blocking items

| Id | Where | Change | Removed r0 text |
|---|---|---|---|
| **B1** | §2 `act.basis`, new `act.state_if_extended`, `attempt_kind` condition; R-A3 scope (b+)/(b−), Symmetry paragraph, second-sentence bullet; R-A5 step 2 (scope gate); D3 values; D8 (iii); THEORY-CHOICES TC-1 | Self-performed refusals, withdrawals and reversals are now scoped **exactly like** positive self-performance. They are acts only in a register row, only when X is one of the five listed lemmas, and only when the expression is in the span. Otherwise they are `act.state = NONE`, `basis = SELF_PERFORMED_OUTSIDE_RA3`, and the would-be state goes in `act.state_if_extended`. The gate applies before ranking. REPORTED acts are never gated. TC-1's 1a/1b/1c now describe the negative case, and 1d is added. | D3: "A self-performed refusal ('the Board declines that') gets `basis = SELF_PERFORMED`. It is governed by R-A2 and steps A2/A3, not by R-A3's list." |
| | | **Choice disclosed.** Of the TASK's two symmetric options, r1 takes **"both are acts only for R-A3's closed operation list in register rows"**. It changes r2 least: r2's R-A3 first sentence and r2's R-A2 both survive. The other option, all self-performance NONE, would delete R-A3's first sentence. It stays computable as TC-1 1b. | |
| **B2** | §3; R-A2 new bullet; R-A5 step 1 ("X of a negative disposition"), rank 1; D4; D5 (ii) | A proposal or attempt is identified **only** when stated in words in the span or section (SOURCE_FACT). An inferred proposal counts as not identified. With an explicit refusal verb (D5 (i)), that gives UNKNOWN. With a bare negation, it gives no disposition (R-A1, NONE). X is the operation named by the **complement** of the refusal expression. | §3: "Identifying a PROPOSAL (D4) … are SOURCE_FACT when the span or section states them in words. Otherwise they are INFERENCE, with the premise quoted." (now only SAME/DISTINCT may be INFERENCE) |

## 2. Minimality reverts (`REVIEW-r0.json` `non_minimal_changes`)

| Id | r0 extension | r1 (r2 behaviour restored) | Kept because G1–G6 need it | Neutrality follow-up |
|---|---|---|---|---|
| **M1** | D7 was a closed lexicon for every deontic value, plus the "remains / stays" rule | D7 lists **prohibition operators only**. D9 (obligation) is unchanged. PERMITTED, AUTHORIZED, UNAUTHORIZED and DELEGATED keep their r2 meaning. ELIGIBLE / INELIGIBLE words moved into D12 (G2). | The REQUIRED / PROHIBITED / STATUS_CONSTRAINT boundary (G5) | none |
| **M2** | R-X1 limited section use for all acts and norms, with a four-item whitelist | R-X1 now only **forbids** two section uses: a performative or disposition for R-A3 (b), and an operator or status predicate for the G5 boundary. All other section use follows r2. | G1 (no performative from headings), G5 (operator in the span) | none |
| **M3** | R-A5 codified all act states. It added an UNKNOWN rule for ACT_REVERSED (A1) and a separate A5 for reported acts. | R-A5 now decides only the G3/G4 competitions: negative disposition vs attempted vs occurred, plus the B1 gate. ACT_REVERSED keeps its r2 meaning with no added condition. ACT_WITHDRAWN without an identified proposal falls to R-A4 (r2). Reported ACT_OCCURRED is r2's meaning. | G3 priority, G4 ATTEMPTED > OCCURRED, B1 gate | none |
| **M4** | D11 STATED/MENTIONED applied to all norms, including norms cited as grounds or reported in logs and commits | D11 applies **only to spans carrying a disposition verdict** (the G2 matrix case). Elsewhere every stated norm clause is collected, as in r2. | G2 (R15–R21) | Listed as **TC-5**. New tag `norm.cited_as_ground`. |
| **M5** | D8 (ii) made every present/future indicative about an operation a status predicate | D8 (ii) is narrowed to R-A1's own case, a non-commencement statement ("does not begin"). Other indicatives are listed in `norm.indicative` and not coded as norms. | G5 (R10, the R-A1 example) | Listed as **TC-6** (review N4). |
| **M6** | R-S1 counting clause ("one norm observation in every support count") | Removed. `shared_with` keeps both counting options open. | — | none |

## 3. Conflicts (`REVIEW-r0.json` `conflicts`)

| Id | Conflict | Resolution |
|---|---|---|
| **C1** | D3 circular: R-A3 (c) required SELF_PERFORMED, and D3 defined it as "R-A3 (a)–(c) hold" | D3 now defines **SELF-PERFORMANCE** by textual form only. The basis value is that property plus the R-A3 (a)/(b) test. R-A3 (c) refers to self-performance. (The other review conflict, R-A3 "any other operation → NONE" vs D3's last bullet, is removed by B1.) |
| **C2** | D11 (cited grounds MENTIONED) vs R-N1's example "forbids promoting from one" vs D8 (i)'s "the register holds X" | With M4, cited grounds outside verdict spans are STATED, so all three agree. R06/R07's ground clause is a coded norm (STATUS_CONSTRAINT, `cited_as_ground = true`). |
| **C3** | D12's negative-verdict phrases ("remains candidate", "stays in …") vs D8 (i) and R-N1's no-re-levelling rule (N2) | A verdict must contain the meta-operation's verb or an eligibility operator **in the span**. Status labels and category letters are status predicates on the item. A new R-N1 example and a D8 (i) entry for category letters were added. The lifted reading is TC-2 2c. |
| **C4** | R-A1's "unless an *attempt* is recorded" vs R-A2 / A3's "proposal or attempt" | R-A1 now reads "unless a proposal or attempt is identified (R-A2, §3) and the span is the disposition of it (D5 (ii))". r2 wording struck. |
| **C5** | D4 (PREPARED ruling = proposal) vs D6 (a proposal is not ATTEMPTED) | D4 "Proposal vs attempt": a PREPARED ruling is an attempt at its **own** operation and a proposal for **adopt** of itself. Those are never the same (operation, target). D5 notes the same for "NOT ADOPTED". |
| **C6** | Merge rule ("coded as X") vs the closed list: an adoption whose content is promotion could be recoded *promote* | Merging replaces only an **unlisted** deciding verb. A listed lemma is never replaced, so "Promotion Review ADOPTED" stays *adopt*. D10 points to the merge rule. |
| **C7** | NORM priority incomplete: same-class clauses (R12, R34) and different targets went to step 7 UNKNOWN, contradicting R-A4 | R-N3 step 4: class priority over all clauses, then **first in span order**. The unchosen clauses go to the new `norm.other_clauses`. UNKNOWN only under R-A4. r0 step 7 removed. |
| **C8** | ACT operation: no same-step tie-break; "highest step" undefined | R-A5 step 4: rank 1 is highest. Gated candidates rank below rank 3. Same rank: first in span order. The others are recorded as `SPLIT_REQUIRED`. |

## 4. Selected nonblocking items

| Id | Item | Change |
|---|---|---|
| **N3** | Tag bare authorize performatives | New `norm.performative_token`. "Bare" is defined in §2. TC-4 now has 4b (exclude bare) and 4c (exclude all performative-token norms). |
| **N6** | REPORTED vs SELF_PERFORMED for rows reproducing another actor's ruling | D3 "Reproduced operative text": a row's own ruling, reproduced or attributed, is a self-performance. A named other ruling or instrument as the source makes it REPORTED. Naming the actor is never a REPORTED sign. New `act.attributed_to`. TC-1 1d. The REPORTED signs are also made explicit (headline participles and present passives are not signs). |
| **D13** | Two-operation rule | D13 (i) coordinated verbs under one predicate form one clause (`plan + implement`). (ii) Separate operators form separate clauses. (iii) An operation named by a noun or option label is the target ("ANNOTATE is adopted" → adopt / ANNOTATE). |
| **NEG** | Negation, only as needed for R01 | D7: *may* within the scope of a preceding negative quantifier (*no <noun> may …*) is a prohibition operator. Nothing else on negation was changed. |

## 5. THEORY-CHOICES

- TC-1 rewritten for the negative case, plus 1d (B1, N6).
- TC-2: 2c added (C3).
- TC-3 unchanged.
- TC-4: performative_token, 4b/4c (N3).
- **TC-5 new** (M4 / N5).
- **TC-6 new** (M5 / N4).

Tag-and-compute is kept throughout.

## 6. Gap records re-run under r1

| Gap | Record | r1 answer | Change vs r0 |
|---|---|---|---|
| G1 | R15–R18 | act NONE, `SELF_PERFORMED_OUTSIDE_RA3`, operation *classify* (the category letter comes first in the span; step 4), `SPLIT_REQUIRED: recommend`, `state_if_extended = ACT_OCCURRED` | operation tie now decided |
| G1 | R19–R21 | act NONE, `SELF_PERFORMED_OUTSIDE_RA3`, *classify*, `state_if_extended = ACT_OCCURRED` | tie decided |
| G1 | R27 | act NONE, `SELF_PERFORMED_OUTSIDE_RA3`, *apply* §12. `state_if_extended = ACT_ATTEMPTED` / PREPARED (the R-83 section marks the ruling PREPARED), `later_disposition`: adopted, R-86. "rather than superseding" is a contrastive. Norm NONE; "it APPLIES §12" in `norm.indicative`. | `state_if_extended` new; norm no longer a D8 (ii) status predicate (M5) |
| G2 | R15–R18 | ELIGIBLE, VERDICT, *promote*, target = the row's behaviour. Category letter and (R18) "already ARB-adopted" in `other_clauses`. | none |
| G2 | R19 | **STATUS_CONSTRAINT**, STATUS_PREDICATE, `status: category C`, target Registration ≠ Delivery. "Stays in ADR-MP-06", "→ Candidate Pattern" in `other_clauses`. | was INELIGIBLE (C3) |
| G2 | R20, R21 | **STATUS_CONSTRAINT**, STATUS_PREDICATE, `status: category D`. "Remains Candidate" in `other_clauses`. R21: "replaced only on repeated evidence" in `norm.indicative`. | was INELIGIBLE (C3) |
| G3 | R01 | act **NONE**, `SELF_PERFORMED_OUTSIDE_RA3`, operation *resolve* (complement of "declines"), `state_if_extended = UNKNOWN` (no proposal to resolve is stated). Norm **PROHIBITED**, OPERATOR, *adopt*, actor = a self-issued act ("no self-issued act may adopt", NEG). | was ambiguous (B2). Now determinate. |
| G3 | R26 | **ACT_REFUSED** *supersede* ADR-T14, `SELF_PERFORMED` (R-77 is a register row; *supersede* is listed; the proposal "a supersession question" is stated), `attributed_to`: the Board. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, `performative_token = true`. | basis now within R-A3 (b−) |
| G3 | R29 | act NONE, `basis = NONE`. Norm NONE ("would" is a modal). | none |
| G4 | R05 | ACT_ATTEMPTED / PREPARED, *authorize* WP-4C-1, `SELF_PERFORMED`. Norm AUTHORIZED, OPERATOR, `plan + implement` (D13 (i)), `performative_token = true` (not bare), `stated_by = ARB CHIEF (PREPARED)`. | D13 explicit |
| G5 | R10 | act NONE, `basis = NONE`. Norm STATUS_CONSTRAINT, STATUS_PREDICATE (narrow D8 (ii)), *begin implementation*, WP-4B. | none |
| G5 | R23 | ACT_OCCURRED *adopt*, `SELF_PERFORMED`, `attributed_to`: Principal Architect. Norm STATUS_CONSTRAINT, `status: permanent documentation standard`. "adopted" (PERFORMATIVE_ONLY) in `other_clauses`. | basis rule now explicit (N6) |
| G6 | R02 | ACT_OCCURRED *adopt* R-81..R-85, `SELF_PERFORMED`, `attributed_to`: Decision Authority. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, *adopt*, `performative_token = true`. | basis rule explicit |
| G6 | R30 | ACT_OCCURRED *adopt*, target ANNOTATE. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, `about_operation = adopt`, `about_target = ANNOTATE` (D13 (iii)). | about_operation decided |
| G6 | R06 / R07 | One record: ACT_WITHDRAWN *withdraw* R-90, REPORTED (the proposal is stated: "a ruling was put forward … filed as R-90"). The other: ACT_OCCURRED *rule* (R-90 is operational acceptance), REPORTED. Norm on both: STATUS_CONSTRAINT, STATUS_PREDICATE ("the register holds constitutional decisions", D8 (i)), `cited_as_ground = true`, `shared_with` cross-listed. | norm presence decided (C2) |
| conflict test | R12 | Norm PERMITTED *plan* WP-8 (first in span). PROHIBITED *start* in `other_clauses`. | was UNKNOWN |
| conflict test | R34 | Norm AUTHORIZED (bare, `performative_token = true`), first in span. PERMITTED *begin RED* in `other_clauses`. | was UNKNOWN |

Symmetry check (R-36 row, not in RECORDS): "Promoted into AST-013 …" and "Expressly NOT promoted: …" are both NONE / `SELF_PERFORMED_OUTSIDE_RA3` (*promote* unlisted), with `state_if_extended` ACT_OCCURRED and ACT_REFUSED / UNKNOWN (per R-A2) respectively. "Promotion Review ADOPTED" stays ACT_OCCURRED *adopt* (C6).

## 7. Not repaired (outside the TASK's list), recorded for the next round

- D1 depends on how the dataset formats section headings (review nonblocking item 12).
- TC-2 2b still needs a join key to the adopting act and an inferred deontic value (review TC-2 assessment).
- **Newly noticed, not repaired.** The D6 FAILED example "refused: …" (an attempt rejected by a mechanism) also matches a D5 (i) refusal verb with an identified attempt. Rank 1 then outranks rank 2, so D6's FAILED kind is reachable only for errors with no refusal verb. This is outside r1's scope and is left for review.
