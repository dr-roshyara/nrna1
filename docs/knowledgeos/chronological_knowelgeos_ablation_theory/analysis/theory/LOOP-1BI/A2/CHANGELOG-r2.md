# CHANGELOG-r2: targeted repair of SCHEMA-r3 (r1 → r2)

Inputs used: r1 `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`, `CHANGELOG-r1.md`; `REVIEW-r1.md` / `.json` (verdict PASS_WITH_LIMITATIONS); `SCHEMA-r2.md`, `GAPS.md`, `RECORDS.json`, `SOURCES.md`, `HYPOTHESES.md`. No commands, no web.

Scope: only the seven items in TASK.md. Every change is tagged `[r2-fix-<id>]` in the files. Text that r1 added and r2 keeps still carries its `[r1-fix-…]` or `[r3-Gn]` tag.

**Rule-selection statement.** No rule was chosen to reproduce an existing coding. No coder output was consulted. Each rule follows from text already in r2 / r1, or from the review's stated defect. Where a rule changes a value that CHANGELOG-r1 reported (R18, R20, R06/R07 primary clause), the new value is a consequence of the rule and is reported in §3. In two cases (PV, Nd) the TASK left a choice open. The choice and the rejected option are disclosed below and in RATIONALE.md.

---

## 1. Changes by TASK item

| TASK item | Id | Where | Change | Replaced r1 text (verbatim) |
|---|---|---|---|---|
| 1. N-a | **Na** | R-A5 rank 3 | "R-A3 (a)–(c) hold" → "a SELF-PERFORMANCE (D3)". The gate alone enforces R-A3. New "Rank 3 and the gate" note: default coding is unchanged. | "X occurred: REPORTED (D3), or R-A3 (a)–(c) hold" |
| | | §2 `act.state_if_extended` | Added: defined for positive and negative gated acts alike; never NONE. | — |
| | | §2 new `act.gated_candidates` | One entry per gated candidate, **including the coded one and step-4 losers**, with `state_if_extended`, `attempt_kind`, `ext_rank`, `coded`. | — |
| | | R-A5 step 2 | Every gated candidate is entered in `act.gated_candidates`. | — |
| | | R-A5 step 4 | Gated candidates are ordered by `ext_rank`, then span order. A losing gated candidate keeps its entry. | "A candidate removed by the scope gate ranks below rank 3, and is ranked among other gated candidates by its `state_if_extended`;" |
| | | THEORY-CHOICES TC-1 | r2 correction paragraph; 1c column now names `act.gated_candidates`; 1c recipe; note that withdrawals and reversals of named rulings are never in scope (review nonblocking note). | 1c column: "1a + `basis = SELF_PERFORMED_OUTSIDE_RA3`, taking the state from `act.state_if_extended`" |
| 2. N-d | **Nd** | §2 new `act.mechanism_refusal`; R-A2 new bullet; R-A5 step 1 "X of a mechanism response"; rank 1 and rank 2 cells; D5 last bullet; D6 MECHANISM RESPONSE | A mechanism response is never a rank-1 refusal. It is the FAILED marker of the attempt it answers: ACT_ATTEMPTED / FAILED where the attempt is stated, UNKNOWN otherwise, always tagged. An actor's refusal expression stays at rank 1, so actor FAILED applies only without a refusal expression. | (none removed; rank-2 cell extended) |
| | | THEORY-CHOICES new **TC-7** | Disposition reading (7b) made computable. | — |
| | | **Disclosure** | r2 does **both**: it codes stated attempts as ACT_ATTEMPTED / FAILED, **and** it tags all mechanism responses (`act.mechanism_refusal`), including those with no stated attempt. | |
| 3. R18 / R20 | **GM** | R-A5 step 1; new **D15** | Ground exclusion now uses the closed D15 list (ground conjunctions, ground labels, evidence column). | "Acts cited only as **evidence or grounds** for the span's principal content (e.g. in an evidence column or a "because …" clause) are not candidates for this record. They may be their own records." |
| | **PV** | R-A5 step 1 new bullet | **Stated:** REPORTED acts mentioned only as provenance or location **are** candidates (unless ground-marked by D15). They are REPORTED by D3 (ii), and never gated. | — |
| 4. Rank-1 cell | **RF** | R-A5 rank 1 | "PROPOSAL" → "PROPOSAL **or attempt**"; "without one" → "without either" (twice). | "ACT_REFUSED if a PROPOSAL for X is identified (R-A2, §3). An explicit refusal verb (D5 (i)) without one: UNKNOWN. A negated X without one is not a disposition …" |
| 5. D7 vs D12 | **DN** | D7 Exception; D12 new bullet | An imperative of the verdict's own meta-operation verb is a verdict in both polarities ("do not promote" → INELIGIBLE). Every other imperative *do not / never* stays a prohibition operator. | (none removed) |
| 6. Primary clause | **PC** | D8 (i) three new bullets; D13 status-predicate bullet; R-N3 step 1 | **Stated:** class labels ("operational acceptance") are status predicates. Contrasts in the same predication belong to the clause. `about_operation = status: <label>`, including nominalizations. No new primary-clause rule: R-N3 step 4 decides. | (none removed) |
| 7a. TC-2 2c | **TC2c** | THEORY-CHOICES TC-2 | Recipe: primary STATUS_PREDICATE only, records with no VERDICT clause, a stated label set L, polarity from the source legend. | "2c = records in a section carrying disposition verdicts, with `basis = STATUS_PREDICATE` (primary clause or in `norm.other_clauses`), recoded as INELIGIBLE." |
| 7b. TC-2 2b | **TC2b** | §2 new `norm.adopted_via`; THEORY-CHOICES TC-2 | Join tag from the section heading's "(adopted by R-n)"; 2b recipe; join failure must be reported. RATIONALE "Not repaired in r1" bullet updated. | RATIONALE: "**Not repaired here** (outside the TASK's list): D1's dependence on dataset heading format; TC-2 2b's missing join key; and an overlap that r1 noticed between D6 FAILED … See CHANGELOG-r1.md." |
| 7c. `performative_token` | **PT** | §2 `norm.performative_token`; THEORY-CHOICES TC-4 (r2 correction, 4c row) | Defined by the text: true when the clause's normative word is itself an act expression in the span, whether that act is coded, gated or REPORTED. Also recorded per `other_clauses` entry. 4c excludes clauses, not records. | §2: "It is true when the chosen clause's normative word is the **same token** by which the record's R-A3 act is performed. This covers every PERFORMATIVE_ONLY clause, and an *authorize* performative coded OPERATOR (R-N3 step 5). Otherwise it is false." TC-4 4c: "Exclude every record with `performative_token = true`." |
| 7d. Missing choices | **TC7** | THEORY-CHOICES | The review marks no further theory choice as missing. Its non-computability findings (TC-1 1c, TC-2 2b, 2c) are fixed above. TC-7 is added because Nd makes a choice that bears on H-B's "failure" material. | — |

## 2. What was deliberately not changed

These are review items outside the TASK's list. They are recorded in KNOWN-AMBIGUITIES.md where they leave more than one answer:
- D11 / R22 (C2 residual: whether a log's "NOT promoted to methodology" is a D12 verdict span);
- `norm.cited_as_ground`'s marker list. D15 closes the list for R-A5 step 1 only. The `cited_as_ground` wording is unchanged, so R01's tag stays open;
- D2 vs the D3 examples (category letters and imperatives as performatives of *classify* / *recommend*);
- the headline vs narrative participle boundary;
- the R-36 "Expressly NOT promoted" `state_if_extended`;
- N-b (D11 skipping evidence cells only on verdict rows);
- D1's dependence on heading format;
- the minor minimality residue in D12 / NEG;
- the r1 CHANGELOG re-run slips (§3 below gives corrected values for the records that r2 touches).

## 3. Records re-run under r2 (only those r2 changes)

| Record | r2 answer | Change vs r1 | Driven by |
|---|---|---|---|
| R15, R16, R17 | Act NONE, `SELF_PERFORMED_OUTSIDE_RA3`, *classify* (category A, first in span), `SPLIT_REQUIRED: recommend`. `gated_candidates`: classify (ext ACT_OCCURRED, rank 3, coded), recommend (ext ACT_OCCURRED, rank 3). `state_if_extended = ACT_OCCURRED`, now by the literal rules. Norm unchanged (ELIGIBLE, VERDICT, *promote*). | `state_if_extended` now literal; `gated_candidates` new | Na |
| R18 | Act **ACT_OCCURRED *adopt***, REPORTED (D3 (ii): Handover §9 named), actor ARB, target Epistemic labeling, quote "already ARB-adopted in Handover §9" (recommendation cell, not ground-marked under D15). `SPLIT_REQUIRED: classify, recommend`. `gated_candidates`: classify, recommend (both ext ACT_OCCURRED, rank 3; `coded = false`). Norm: ELIGIBLE, VERDICT, *promote*. `other_clauses`: category A (STATUS_PREDICATE), "already ARB-adopted" (PERFORMATIVE_ONLY, `performative_token = true`). The evidence column's "ARB-adopted" is ground-marked (D15 (iii)) and is not an act candidate. | was NONE / *classify* | PV, GM, Na, PT |
| R19 | Act NONE, OUTSIDE, *classify* (category C). `gated_candidates` include *recommend* (recommendation cell). `SPLIT_REQUIRED: recommend`. Norm: STATUS_CONSTRAINT, `status: category C`. `other_clauses` include "→ D", "Stays in ADR-MP-06", "→ Candidate Pattern", and the evidence cell's "permanent design principle" (`cited_as_ground = true`). | SPLIT note and `other_clauses` completed; `gated_candidates` new | Na (r1 slip corrected) |
| R20 | Act **ACT_OCCURRED *document***, REPORTED (D3 (ii): PB-004 retro §9 + Handover §8 named), target Strangler reconstitution, actor NOT_EVIDENCED, quote "documented in PB-004 retro §9 + Handover §8". `SPLIT_REQUIRED: classify, recommend`. `gated_candidates`: classify (D), recommend ("Remains Candidate" cell), both ext ACT_OCCURRED, rank 3. Norm unchanged: STATUS_CONSTRAINT, `status: category D`; "Remains Candidate" in `other_clauses`. | was NONE / *classify* | PV, GM, Na |
| R21 | Act NONE, OUTSIDE, *classify* (category D), `SPLIT_REQUIRED: recommend`. `gated_candidates`: classify, recommend. "explicitly ruled" stands in the evidence column (D15 (iii)): not a candidate. Norm: STATUS_CONSTRAINT `status: category D`. `other_clauses`: "Remains candidate"; the evidence cell's class label "temporary carrier, not a pattern" (`cited_as_ground = true`; see KNOWN-AMBIGUITIES). | SPLIT note; `gated_candidates` new | Na, PC |
| R27 | As r1. `gated_candidates`: apply §12 (ext ACT_ATTEMPTED / PREPARED, rank 2, coded). | `gated_candidates` new | Na |
| R01 | As r1. `gated_candidates`: resolve (ext UNKNOWN, rank 1, coded). `mechanism_refusal = false` (the refuser is the Chief). | tags new | Na, Nd |
| R26 | As r1. `mechanism_refusal = false`. | tag new | Nd |
| R03 | `performative_token = true` on the "NOT ADOPTED" clause, wherever it falls (primary or `other_clauses`). Other fields: see KNOWN-AMBIGUITIES. | definition consistent | PT |
| R06 / R07 | Acts as r1 (ACT_WITHDRAWN *withdraw* R-90, REPORTED; ACT_OCCURRED *rule*, REPORTED, a DISTINCT act in the "because" segment, so it is its own record). Norm on both: STATUS_CONSTRAINT, STATUS_PREDICATE, **`status: operational acceptance`** (quote "it was operational acceptance rather than a constitutional decision"), `about_target` = R-90, `cited_as_ground = true`, `stated_by` = the Authority. `other_clauses`: "the register holds constitutional decisions" (STATUS_PREDICATE, scope statement). `shared_with` cross-listed. | primary clause decided: was "the register holds …" in CHANGELOG-r1 | PC |
| R15–R21 sections | `norm.adopted_via = R-36` (heading "(adopted by R-36)"). | tag new | TC2b |
| all other records | `act.gated_candidates` empty unless step 2 removed a candidate (R09 *file*, R25 *retire*: one entry each, ext ACT_OCCURRED, rank 3). `norm.adopted_via` empty. No value changes. | tags only | Na, TC2b |

Rule test, not in RECORDS: S0815 "append --json='{"type":"CLOSE",…}' → refused: unknown transition type 'CLOSE' … exit 65, record unchanged" → **ACT_ATTEMPTED / FAILED**, *append* (target: the CLOSE transition), REPORTED ("Probed"), `mechanism_refusal = true`. It was UNKNOWN in r1. Matrix row 7, "do not duplicate it": PROHIBITED *duplicate* (not the meta-operation verb, so D7 applies). It is unchanged.

## 4. Neutrality check of the r2 changes

- **Na:** default `act.state` is unchanged on every record. The would-be state is now defined for both polarities.
- **Nd:** stated work failures enter support counts by default. The disposition reading is computable (TC-7).
- **GM:** the list is lexical and does not depend on whether the act concerns work or norm-making.
- **PV:** it adds REPORTED acts of any kind (adopt in R18, document in R20). The row's own gated classifications stay recorded and are comparable under TC-1 1c.
- **DN:** it removes a polarity asymmetry.
- **PC / PT:** they are definitional, and the same for every record.
