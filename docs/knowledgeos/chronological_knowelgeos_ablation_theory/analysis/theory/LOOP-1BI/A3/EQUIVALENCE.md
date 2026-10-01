# EQUIVALENCE: SCHEMA-r3.md (r3c) → SCHEMA-r3-CODER.md

This table maps every rule in `SCHEMA-r3.md` (consolidation r3c) to where it appears in `SCHEMA-r3-CODER.md`. "CODER" locations use the coder text's own labels: §1.n (unit), §2 field names, D1–D15 (definitions), G1–G3 (general rules), A1–A5 (ACT steps), N1–N7 (NORM steps), §4.3 (shared spans), §5 (examples), §6 (output).

**Kinds of correspondence**
- **Same**: the rule is stated with the same content, possibly in shorter words.
- **Split** / **Merged**: one SCHEMA-r3 rule is stated in more than one CODER place, or several in one place. The content is unchanged.
- **Example replaced**: an illustration taken from a source record or a source document is replaced by an invented one, or dropped. The rule it illustrates is stated in full. The TASK requires this, to avoid anchoring.
- **Non-rule removed**: revision tags, struck text, history ("r2 meaning", "as in r2"), rationale, purpose clauses ("exists so that TC-… can be computed"), and references to THEORY-CHOICES, gaps or records. None of these decides a coded value.

## 1. Unit and components

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 1 | §1 One record = one verbatim span, smallest sufficient span | §1.1 | Same ("(1aw)" label removed) |
| 2 | §1 Fields `record_id`, `v1_event_ids`, `source`, `section`, `span` | §2 Identification; §6 | Same |
| 3 | §1 Emphasis markers may be stripped; must be disclosed | §1.1; §6 `span_note` | Same; §6 names the place of the disclosure |
| 4 | §1 (span, operation, target) unique | §1.2 | Same |
| 5 | §1 Events resolving to one span are one record; applies to SAME-EVENT candidates (G6 bullet) | §1.2 | Merged |
| 6 | §1 Act clause + norm clause = one record with both components | §1.2 | Same |
| 7 | §1 DISTINCT acts differing in operation or target → one record per (operation, target), sharing the span, coded under R-S1 | §1.3 | Same |
| 8 | §1 [r3c-SS] This holds also for a DISTINCT act in a ground-marked segment | §1.3 | Same |
| 9 | §2 Both components may be non-NONE; coded independently | §1.4 | Same |
| 10 | §2 NORM records stated content whether or not the stating act took effect; does not code force; effectiveness only in ACT | §1.4 | Same ("See TC-3" removed) |

## 2. Status (§3)

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 11 | Each coded field carries SOURCE_FACT / INFERENCE / UNKNOWN / NOT_EVIDENCED; INFERENCE states premise; no silent defaults | §2 Status; §6 | Same; §6 shows the triple layout |
| 12 | Proposal or attempt identified only as SOURCE_FACT; inferable-only = not identified | §2 Status | Same |
| 13 | SAME EVENT vs DISTINCT: SOURCE_FACT if stated in words, else INFERENCE with premise quoted | §2 Status | Same |

## 3. ACT fields (§2)

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 14 | `act.state` values | §2 ACT `state` | Same |
| 15 | `act.operation`: open vocabulary, v1 list preferred | §2 ACT `operation` | Same |
| 16 | `act.actor`, `act.target`, `act.quote` | §2 ACT | Same |
| 17 | `act.basis` values; required on every record; with OUTSIDE_RA3 `state` NONE, `operation` and `state_if_extended` filled | §2 ACT `basis` | Same |
| 18 | `act.state_if_extended`: iff OUTSIDE_RA3; the step-3 state without the gate; six allowed values; not `act.state` | §2 ACT `state_if_extended` | Same ("any state but NONE" = the six listed values); purpose clause removed |
| 19 | [r2-Na] defined for positive and negative alike; never NONE | §2 ACT `state_if_extended` | Same |
| 20 | `act.gated_candidates`: required; every gated candidate incl. coded one and step-4 losers; sub-fields `operation`, `target`, `quote`, `state_if_extended`, `attempt_kind` (iff ATTEMPTED), `ext_rank`, `coded`; empty if none | §2 ACT `gated_candidates`; A2 Gate; A4 | Same |
| 21 | [r3c-GC] `act.ground_candidates`: required; one entry per act excluded at step 1 as ground-marked; sub-fields `operation`, `actor`, `target`, `quote`, `marker`; not for an act coded on another sharing record; empty if none; no state; changes no other field | §2 ACT `ground_candidates`; A1.1 | Split (the "not for an act coded on another sharing record" clause is in A1.1); purpose clause removed |
| 22 | `act.attributed_to` (optional), issuer of reproduced operative text; does not change basis | §2 ACT `attributed_to` | Same; one of three source example phrases replaced, purpose clause removed |
| 23 | `act.attempt_kind` ∈ PREPARED, FAILED; iff state or `state_if_extended` = ATTEMPTED | §2 ACT `attempt_kind` | Same |
| 24 | `act.mechanism_refusal`: required whenever a D5 (i) refusal expression or a D6 FAILED marker concerning a candidate is present; true iff MECHANISM RESPONSE | §2 ACT `mechanism_refusal` | Same; purpose clause removed |
| 25 | `act.later_disposition` (optional): later act in span or section giving or denying effect; id + quote; does not change state | §2 ACT `later_disposition`; A3 note | Same |

## 4. NORM fields (§2)

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 26 | `norm.deontic` values | §2 NORM `deontic` | Same |
| 27 | REQUIRED, PROHIBITED, STATUS_CONSTRAINT defined in D7–D9; ELIGIBLE / INELIGIBLE in D12; others keep r2 meaning, no lexicon | §2 NORM `deontic` | Same. "r2 meaning" → "plain meaning": SCHEMA-r2 lists these values without defining them, so their r2 meaning is their plain meaning |
| 28 | `about_operation`, `about_actor`, `about_target` = governed act | §2 NORM | Same |
| 29 | `about_operation` by literal attachment; STATUS_CONSTRAINT with no operation → `status: <verbatim label>` | §2 NORM; D13 | Same |
| 30 | `norm.stated_by`; PREPARED marker carried verbatim | §2 NORM `stated_by` | Same; example "ARB CHIEF (PREPARED)" replaced |
| 31 | `norm.quote` | §2 NORM | Same |
| 32 | `norm.basis` values; required unless deontic NONE / UNKNOWN | §2 NORM `basis` | Same |
| 33 | `norm.performative_token`: required when basis filled and on every `other_clauses` entry; true iff normative word is itself an act expression (PERFORMATIVE, negative disposition, reporting verb or participle), whether coded, gated or REPORTED; every PERFORMATIVE_ONLY clause and *authorize* performative true; otherwise false | §2 NORM `performative_token`; N5 | Same ("otherwise false" is the complement of "true when") |
| 34 | Bare authorize performative definition | §2 NORM `performative_token` | Same; example "Slice 7C AUTHORIZED" replaced by "RELEASE 4 AUTHORIZED" (§5) |
| 35 | [r3c-NW] Normative word of a class label = the label noun phrase, not copula / preposition / colon; true only if that phrase is an act expression | §2 NORM `performative_token` | Same ("true only if …" follows from the general definition once the normative word is fixed) |
| 36 | `norm.cited_as_ground`: required when basis filled; marker examples; otherwise false | §2 NORM `cited_as_ground` | Same ("TC-5" removed) |
| 37 | `norm.other_clauses`: unchosen clauses, six sub-fields; primary fields single-valued | §2 NORM `other_clauses`; N6 | Same |
| 38 | `norm.indicative`: present/future indicatives without modal about whether an operation takes place, not D8 (ii); not norms | §2 NORM `indicative`; D8 (ii); N6 | Same ("TC-6" removed) |
| 39 | `norm.shared_with` | §2 NORM; §4.3 | Same |
| 40 | `norm.adopted_via`: from section heading, SOURCE_FACT; else empty, NOT_EVIDENCED; supplies no act, performative or norm clause | §2 NORM `adopted_via` | Same; example replaced; "join key for TC-2 2b only" removed (purpose) |

## 5. Critical rules (§4)

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 41 | R-A1 Never infer an ACT from a NORM; "X is authorized to begin" is not evidence X began | G1 | Same |
| 42 | R-A1 "Implementation does not begin" is STATUS_CONSTRAINT (D8 (ii)); PROHIBITED only with "must not / shall not"; not ACT_REFUSED unless proposal/attempt identified and the span disposes of it | G1; D8 (ii); D7; D5 (ii) | Split. G1 keeps the ACT half; the NORM value follows from D8 (ii) (non-commencement → STATUS_CONSTRAINT) and D7 (prohibition only with an operator). Example "Implementation" → "Rollout" |
| 43 | R-A2 ACT_REFUSED / WITHDRAWN require identified proposal or attempt in span or section | A3 rank 1; D4; §2 Status | Same |
| 44 | R-A2 ACT_REFUSED also requires a refusal expression disposing of the proposal | A3 rank 1; D5 | Same |
| 45 | R-A2 Non-occurrence-so-far negations ("has not been issued", "remains unauthorized", "not yet") are never refusals | D5 "Never" ("has not been", "not yet", "remains / still not", "un-") | Same |
| 46 | R-A2 A reason against an unchosen option, without refusal expression, is not a refusal; act NONE | D5 "Never"; A3 note ("is NONE") | Same; source example "X would blur chronology" removed |
| 47 | R-A2 "Identified" = stated in words; inferred proposal not identified | §2 Status; D4 | Same |
| 48 | R-A2 X = operation named by the complement of the refusal expression; never from section or from what the refused item would have done | A1.5 | Same |
| 49 | R-A2 [Nd] A mechanism response is not a refusal expression; it is a non-effect marker | D5 last sentence; D6; A3 rank 1 ("never a mechanism response") | Same |
| 50 | R-A3 A register row that itself performs a listed act is ACT_OCCURRED of it; its stated content is also a NORM | A2 first sentence; A5 | Same. "(subject to A3)" makes explicit that R-A5's ranks apply, as SCHEMA-r3 R-A5 "Consequences" states (a PREPARED instrument is never ACT_OCCURRED) |
| 51 | R-A3 scope (a) REGISTER ROW | A2 (a); D1 | Same |
| 52 | R-A3 (b+) PERFORMATIVE of the five lemmas in the span | A2 (b) | Same |
| 53 | R-A3 (b−) negative disposition whose X is one of the five | A2 (b) | Same; example "ADR-T14 is not superseded" removed (other two kept) |
| 54 | R-A3 Inflections and nominalizations count; synonyms do not (list of twelve) | A2 (b) | Same (all twelve listed) |
| 55 | R-A3 The negative verb is never the listed operation | A2 (b) | Same |
| 56 | R-A3 (c) SELF-PERFORMANCE, not REPORTED | A2 (c) | Same |
| 57 | R-A3 Not by analogy; section / row heading / annotation label do not satisfy (b) | A2 ("No analogy"; "section, row-heading or annotation-label wording does not count"); G3 | Same. "Adding operations is a schema revision, not a coder judgement" is expressed by "only if", the closed list and "No analogy" |
| 58 | R-A3 Symmetry: any other self-performance, either polarity, or outside a register row → NONE / OUTSIDE_RA3 with operation and `state_if_extended` | A2 Gate; A4; D3 values | Same; R-36 example and "THEORY-CHOICES TC-1" removed |
| 59 | R-A3 NORM mandatory whenever R-A3 applies ((b+) or (b−)); code by R-N3; never NONE (D8 (iii)); PERFORMATIVE_ONLY if the performative is the only basis | A5 | Same |
| 60 | R-A4 UNKNOWN when the span does not decide; counted; excluded from support counts | G2 | Same. "Excluded from support counts" → "counted, apart from decided values": the coder-facing content (UNKNOWN is a value that is counted but not pooled with decided values) is kept without naming the analysis |
| 61 | R-A5 decides only disposition vs occurrence vs attempt on one (operation, target), plus the gate; adds no condition to any state; reported ACT_OCCURRED and ACT_REVERSED keep r2 meaning; REFUSED / WITHDRAWN keep R-A2 | §4.1 intro | Same; "G3 / G4" and "r2 meaning" removed (history). The states have no other definition in either text |
| 62 | Step 1: collect candidates the span supports | A1 heading | Same |
| 63 | Step 1 [r3c-SS/GC]: ground-marked act not a candidate; entered in `ground_candidates`; D15 list closed | A1.1; D15 | Same |
| 64 | Step 1 [r3c-SS] shared-span exception (own record only; not entered on others; entered on each if all records taken; unshared → never a candidate) | A1.1; §4.3 | Same |
| 65 | Step 1 [PV] provenance / location mentions are candidates in any syntactic form unless ground-marked; REPORTED by D3 (ii); never gated | A1.2; D3 (ii) | Same; source examples replaced |
| 66 | Step 1 [r3c-PR] a label headed PROVENANCE is not a ground label | D15 (ii) | Same |
| 67 | Step 1 merge SAME-EVENT candidates; DISTINCT to separate records | A1.3; §1.3 | Same |
| 68 | Step 1 merge rule (unlisted deciding verb coded as X; listed lemma never replaced; listed deciding verb is the operation, content its target) | A1.4 | Same; "Promotion Review ADOPTED" → "Review Report ADOPTED"; "confirms and adopts" example moved to D10 and replaced. "Merging can never move an act off the list" is a consequence of the two sentences kept |
| 69 | Step 1 X of a negative disposition (complement; noun naming no operation → own lemma, noun as target) | A1.5 | Same; "declines to resolve" → "declines to rename"; "R-90 … withdrawn" → "Motion M-4 … withdrawn" |
| 70 | Step 1 X of a mechanism response = operation of the answered attempt, as stated | A1.5 | Same; source example removed |
| 71 | Step 2 gate: self-performance, either polarity, failing R-A3 (a) or (b), removed from step 3 | A2 Gate | Same |
| 72 | Step 2 [r3c-S2] if no ungated candidate is coded at step 4: NONE / OUTSIDE_RA3 / X (chosen gated candidate), `state_if_extended` its step-3 result; if an ungated one is coded, record takes its values, gated ones only in `gated_candidates` (`coded = false`) and SPLIT notes | A4 ("Ungated winner … Otherwise …") | Same |
| 73 | Step 2 REPORTED candidates never gated | A2 Gate; A1.2 | Same |
| 74 | Step 2 [Na] every gated candidate entered with step-3 state and rank, whether or not finally coded | A2 Gate; §2 `gated_candidates` | Same |
| 75 | Step 3 first matching rank decides | A3 | Same |
| 76 | Rank 1 condition; mechanism response never rank 1 | A3 table | Same |
| 77 | Rank 1 results (refusal / withdrawal / reversal) | A3 table | Same. "otherwise R-A4 applies" → "else G2"; "as in r2" removed |
| 78 | Rank 2 initiation + non-effect marker incl. mechanism response → ATTEMPTED + `attempt_kind` (FAILED for mechanism) | A3 table | Same |
| 79 | Rank 3 REPORTED or SELF-PERFORMANCE → ACT_OCCURRED | A3 table | Same |
| 80 | Rank 4 no candidate → NONE; basis NONE or OUTSIDE_RA3 | A3 table | Same |
| 81 | "Rank 3 and the gate": gate alone enforces R-A3; for `state_if_extended` rank 3 gives a positive gated candidate ACT_OCCURRED, rank 1 a value for a negative one | A3 last sentence ("rank gated candidates as if ungated") | Same; the explanatory sentence on default coding is removed (it states a consequence, not a rule) |
| 82 | Step 4 applies when candidates for different (operation, target) remain and the record is the only one on the span | A4 | Same |
| 83 | Step 4 highest rank; gated below rank 3, ordered by `ext_rank` | A4 | Same |
| 84 | Step 4 tie → first in span (also among gated, same `ext_rank`) | A4 | Same |
| 85 | Step 4 `SPLIT_REQUIRED: <operation>` in premise of `act.operation`; gated losers keep entries | A4; §2 `operation` | Same |
| 86 | Consequences: rank 1 outranks a row's own performance; listed X → REFUSED / WITHDRAWN / REVERSED; unlisted X → NONE / OUTSIDE_RA3 | A4 last sentences | Same |
| 87 | Consequences: ATTEMPTED outranks OCCURRED; PREPARED instrument never ACT_OCCURRED of its own operation | A3 table order; A4 | Same |
| 88 | R-A6 ATTEMPTED only on a source non-effect marker; coder never judges capability or authority; without marker, rank 3 | A3 note | Same |
| 89 | R-A6 conditional / provisoed authorization not ATTEMPTED; rank 3; condition to NORM | A3 note; D6 "Not ATTEMPTED" | Same |
| 90 | R-A6 later adoption / hold / withdrawal in section is a separate act, in `later_disposition`, no conversion to OCCURRED | A3 note; §2 `later_disposition` | Same |
| 91 | R-N1 `about_operation` = operation the operator / verdict / status predicate attaches to grammatically; never raised or lowered | D13; N3 | Same |
| 92 | R-N1 MENTIONED content supplies `about_target` only; no separate norm; internal modals not coded | N1 | Same |
| 93 | R-N1 examples ("PROMOTE", "may begin RED", "forbids promoting from one", "Remains Candidate") | D13 last sentence | Example replaced ("GRADUATE", "may begin testing", "Remains Draft"); "forbids promoting from one" dropped (source span) |
| 94 | R-N2 deontic value read from the span; section may not supply operator or status predicate | N5 ("from the span only"); G3 | Same |
| 95 | R-N2 obligation / prohibition operator on an operation → REQUIRED / PROHIBITED | N5 | Same |
| 96 | R-N2 no deontic operator + status predicate → STATUS_CONSTRAINT | N4 (operators outrank status predicates); N5 | Merged |
| 97 | R-N2 status noun or adjective is a status predicate even if it implies conformity; never REQUIRED without an obligation operator | N5 | Same; example list shortened (D8 (i) lists the rest) |
| 98 | R-N3 step 1 collect operators (D7, D9, other values in plain sense), verdicts, status predicates, listed performatives; skip MENTIONED in verdict spans; class labels collected | N1 | Same |
| 99 | R-N3 step 2 none → NONE | N2 | Same |
| 100 | R-N3 step 3 `about_operation` by D13; `about_target` = item predicated of; row's subject item in a table | N3 | Same |
| 101 | R-N3 step 4 one clause over all clauses by class OPERATOR > VERDICT > STATUS_PREDICATE > PERFORMATIVE_ONLY; tie → first in span | N4 | Same |
| 102 | R-N3 step 5 mapping; *authorize* performative is OPERATOR / AUTHORIZED, token true | N5 | Same |
| 103 | R-N3 step 6 record basis, token, `cited_as_ground` | N6 | Same |
| 104 | R-N3 step 7 others to `other_clauses` | N6 | Same |
| 105 | R-N3 UNKNOWN only under R-A4; several clauses never UNKNOWN | N7 | Same |
| 106 | R-S1 ACT: each record codes only its own act; never on two records; [r3c-SS] includes ground-marked acts | §4.3; A1.1 | Same |
| 107 | R-S1 NORM: identical on all sharing records; cross-listed in `shared_with`; coder does not choose an owner | §4.3 | Same |
| 108 | R-X1 (i) section may not supply R-A3 (b)'s performative or disposition | G3; A2 (b) | Same |
| 109 | R-X1 (ii) section may not supply the operator or status predicate deciding REQUIRED / PROHIBITED / STATUS_CONSTRAINT | G3; N5 | Same |
| 110 | R-X1 every other section use follows r2 (R-A2's "span or section") | G3 ("elsewhere 'span or section' allows it") | Same. SCHEMA-r2's only section use is R-A2's; every r3 rule that admits the section says "span or section" in both texts |

## 6. Definitions (§5)

| # | SCHEMA-r3 rule | CODER location | Correspondence |
|---|---|---|---|
| 111 | D1 REGISTER ROW; non-register sections | D1 | Same; example heading replaced |
| 112 | D2 PERFORMATIVE with three example forms | D2 | Same; "SLICE X AUTHORIZED" → "RELEASE 4 AUTHORIZED" |
| 113 | D3 SELF-PERFORMANCE definition | D3 | Same ("does not refer to R-A3" removed: a remark about the definition) |
| 114 | D3 REPORTED signs (i)–(iii), incl. headline participle / present passive not a sign | D3 | Same; examples replaced where taken from sources |
| 115 | D3 Reproduced operative text: self-performance if own row's ruling, REPORTED if another source named; issuer naming never itself a sign; issuer in `attributed_to` | D3; §2 `attributed_to` | Split |
| 116 | D3 values REPORTED / SELF_PERFORMED / OUTSIDE_RA3 / NONE, with OUTSIDE examples | D3 | Same; "declines to resolve" → "declines to rename"; "expressly does NOT promote" dropped (example) |
| 117 | D4 PROPOSAL (stated in words; four kinds; three exclusions) | D4 | Same |
| 118 | D4 Proposal vs attempt for a PREPARED ruling; never same (operation, target); putting X forward is not attempting X | D4 | Same; "which the adopting authority may adopt, hold or refuse" removed (descriptive) |
| 119 | D5 (i) explicit refusal verbs | D5 (i) | Same |
| 120 | D5 (ii) negated X as disposition (deciding act or register-row operative / headline text deciding an identified proposal) | D5 (ii) | Same |
| 121 | D5 never-dispositions | D5 | Same |
| 122 | D5 "NOT ADOPTED" on PREPARED instrument: non-effect marker and, if proposal identified, refusal of *adopt*; different pairs | D5 | Same |
| 123 | D5 [Nd] refusal verb inside mechanism response not (i) | D5 | Same |
| 124 | D6 ACT_ATTEMPTED (initiation + source statement of no effect) | D6 | Same |
| 125 | D6 PREPARED markers; FAILED by mechanism or actor | D6 | Same |
| 126 | D6 [r3c-MR] MECHANISM RESPONSE requires stated non-effect; examples of such statements | D6 | Same |
| 127 | D6 [r3c-MR] not mechanism responses: results of effective acts (empty results, test/check findings with no refused/rejected/errored attempt); intended test failures (run coded like any act, rank 3 if it occurred; does not by itself require `mechanism_refusal`) | D6; §5 "Intended failure" | Same |
| 128 | D6 mechanism response with stated attempt → rank 2, FAILED, `mechanism_refusal` true, even with refusal verb | D6; A3 rank 2 | Same |
| 129 | D6 mechanism response with no stated attempt → UNKNOWN, `mechanism_refusal` true | D6 | Same ("so it stays countable" removed: purpose) |
| 130 | D6 named actor's refusal expression is rank 1; actor FAILED only without refusal expression | D6 | Same |
| 131 | D6 Not ATTEMPTED: proposal, intended / planned act, conditional / provisoed act | D6 | Same ("conditional acts" covers "conditional or provisoed"; A3 note names "provided …") |
| 132 | D7 prohibition operators (case-insensitive) | D7 | Same |
| 133 | D7 [DN] exception for the verdict's own meta-operation verb; other imperatives stay prohibition | D7 | Same ("do not duplicate it" → none needed: "Any other imperative" is the residue of "Except") |
| 134 | D7 negated *may* | D7; §5 "Negated may" | Same; example replaced |
| 135 | D7 not a lexicon for PERMITTED / AUTHORIZED / UNAUTHORIZED / DELEGATED | §2 NORM `deontic` | Same |
| 136 | D8 STATUS PREDICATE definition; value STATUS_CONSTRAINT for (i)–(iii) | D8 | Same |
| 137 | D8 (i) status labels, category letter, remains / stays, scope statements | D8 (i) | Same |
| 138 | D8 (i) [PC] class labels (forms, kinds, legend categories; affirmed or reported) | D8 (i) | Same; the five source example labels removed, invented example in §5 |
| 139 | D8 (i) [PC] contrast belongs to the clause, quoted with it | D8 (i) | Same |
| 140 | D8 (i) [PC] class label `about_operation = status: <label>` even for nominalizations; target the item; [r3c-NW] normative word = the label NP | D8 (i); §2 `performative_token` | Split; "operational acceptance" → "routine maintenance" |
| 141 | D8 (ii) non-commencement statement; other indicatives to `norm.indicative` | D8 (ii) | Same; source examples removed |
| 142 | D8 (iii) listed performative other than *authorize*, or negative disposition of one; PERFORMATIVE_ONLY | D8 (iii) | Same |
| 143 | D8 conditions / guards / provisos met or unmet are facts | D8 last bullet | Same |
| 144 | D9 REQUIRED operators | D9 | Same |
| 145 | D10 SAME EVENT (stated content; coordinated verbs, one actor, one object); merge rule applies | D10 | Same; "held it — not adopted" → "decided: not adopted"; "confirms and adopts" → "reviews and adopts" |
| 146 | D10 DISTINCT (different actors, times, ground of another) | D10; §1.3 | Same |
| 147 | D11 scope: only spans with a disposition verdict | D11 | Same |
| 148 | D11 MENTIONED; STATED | D11 | Same |
| 149 | D11 elsewhere every stated clause collected, incl. grounds and reports | D11 | Same; R06/R07 and session-log illustrations removed |
| 150 | D12 disposition verdict definition (meta-operation verb or eligibility operator in span; not performing it) | D12 | Same; "PROMOTE" forms replaced by "GRADUATE" forms (§5) |
| 151 | D12 [DN] imperative verdict in both polarities | D12; D7 | Same |
| 152 | D12 status label only on the item is D8 (i), not a verdict; `status: <label>` | D12; D13 | Same; "Remains Candidate" → "Remains Draft" |
| 153 | D12 ELIGIBLE / INELIGIBLE; `about_operation` = X; never evidence X occurred | D12 | Same |
| 154 | D13 literal attachment (operator verb; verdict meta-operation; status predicate's named operation else `status:`); "named" = by verb or verbal predicate | D13 | Same |
| 155 | D13 (i) coordinated verbs one clause, " + ", span order | D13 | Same; example replaced |
| 156 | D13 (ii) separate operators separate clauses | D13; §5 "Two operators" | Same; example replaced |
| 157 | D13 (iii) operation named only by noun or name → `about_target`; operation of the predicate word | D13 | Same; "ANNOTATE is adopted" → "MERGE is adopted" |
| 158 | D14 SHARED SPAN | D14 | Same |
| 159 | D15 closed list, case-insensitive | D15 | Same |
| 160 | D15 (i) ground conjunctions; segment to end of sentence | D15 (i) | Same |
| 161 | D15 (ii) ground labels (five head words, colon or dash, qualifiers, segment end, label in span) | D15 (ii) | Same |
| 162 | D15 (ii) [r3c-PR] PROVENANCE-first label not a ground label; acts after it are candidates; still ends a preceding segment | D15 (ii); A1.2 | Split |
| 163 | D15 (iii) evidence column (header from the row's table) | D15 (iii) | Same |
| 164 | D15 nothing else marks a ground | D15 | Same |

## 7. Coder-only material

| CODER item | Status |
|---|---|
| §5 worked examples | Illustrations only. Every span is invented. Each result follows from the rules mapped above; none adds a rule. There is one row per ACT path (no candidate, reported, in-scope, reproduced, conditional, gated positive, outside register row, gated negative, refusal, merge, reported refusal, refusal with nothing identified, withdrawal, reversal, PREPARED, FAILED, mechanism response without attempt, non-mechanism result, ground-marked, shared span, step-4 ranks, step-4 reported vs gated) and per NORM path (none, indicative, prohibition, negated *may*, obligation, two operators, class order, authorize, bare authorize, verdict, imperative verdict, status label, non-commencement, class label, performative only, ground clause). |
| §6 JSON layout | Presentation of SCHEMA-r3 §1–§3: one object per record, each coded field with value, status and premise. `span_note` is the place where SCHEMA-r3 §1's required disclosure of stripped emphasis is written. No field is added or dropped. |

## 8. Confirmation

- **No rule was added.** Every normative sentence in SCHEMA-r3-CODER.md maps to a row above. The coder text has no rule without a SCHEMA-r3 source.
- **No rule was dropped.** Every rule in SCHEMA-r3.md §1–§5, including the r3c fixes SS, PR, MR, S2, GC and NW, has a row. What the coder text omits is only non-rule material: the change key, revision tags, struck r2 wording, history ("r2 meaning", "as in r2"), rationale, purpose clauses, and references to THEORY-CHOICES, gaps or records.
- **No rule was changed.** Rewordings that are not literal are listed in the Correspondence column, with the reason they are equivalent: rows 27, 42, 50, 57, 60, 61, 77, 81, 96 and 110. Examples were replaced or dropped only where they came from source records or source documents, and the rule each one illustrated is stated in full.
- **Limit of this check.** The check was done by reading, without tools (TASK: no commands). The coder text's length (target ≤ 3,500 words) was estimated by hand at about 3,400 words, not machine-counted.
