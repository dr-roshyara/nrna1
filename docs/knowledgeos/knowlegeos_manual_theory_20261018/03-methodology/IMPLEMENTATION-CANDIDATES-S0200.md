# Implementation candidates at S0200 (stage 0 of the implementation strategy)

| Field | Value |
|---|---|
| Status | **DRAFT FOR THE PROFESSOR'S CONFIRMATION**, then for the Principal's stage-1 decision. Nothing is authorised by this list |
| Prepared by | the Governance Engineer, 2026-10-29, under S0200 decision D8 (decision record, addendum 2026-10-29T02:08:48Z) |
| Basis | the working book at `0ae02e66b` (after stage 2 of the S0200 consolidation): Ch. 9 §9.10–§9.17, `CONCEPT-REGISTER.tsv`, Ch. 9 `TODO.md`, `BOOK-OUTLINE.md`; models in `tmp/tools/` |
| Levels | as defined in `IMPLEMENTATION-STRATEGY-PROPOSED.md` §3: **S0** unstable · **S1** experimental (reference model only) · **S2** reviewed core (kernel prototype) · **S3** established |

*Labels: **[V]** checked by the Governance Engineer in the book, register or a model run · **[R]** as the book states it, not reconstructed · **[P]** the level is the Governance Engineer's proposal, for the Professor to confirm or correct · **[O]** open.*

## 1. What the list is for

Stage 1 of the strategy builds an **executable reference model**: one library in which each kernel result becomes code and **each law becomes a property test**, with every assumption visible as a parameter or a stub. This list names the candidates, what each rests on, and how far each may be built. **A level is never raised by this list or by a passing test; only a reviewer's verdict raises it.**

## 2. The candidates

| # | Candidate | Book home · concept | Register status | What would be built in stage 1 | Rests on (assumptions) | Existing model | Proposed level | Open questions |
|---|---|---|---|---|---|---|---|---|
| 1 | **The record and its domain D** (which records the evaluator accepts; the D-check) | §9.13.2 · CON-0191 (part), CON-0149 | PROVISIONAL [V] | the record type; the D-check as a predicate with a reason for each refusal | K1 (what is detectable), K2, K5 [R] | `ce_model.py` v20 [V runs] | **S1** [P] | the present estate's record is **outside D** (§9.15.6) [R] — see §4; **G1** (strategic-design turn 2, `65e332c21`, T10-06): the record admits only acts of authority and has **no evidence entry type**, though §9.10 O2/Q4 require evidence entries and a grade; the promise (b) (T10-01) cannot yet be supported by the record [R] |
| 2 | **CE\*, the evaluator of current authority**: total and unique on D; answers T / F / U, each U with its reason | §9.13.3–§9.13.4 R1, R2 · CON-0191 | PROVISIONAL [V] | the evaluator as a pure function; property tests: exactly one answer on D (G1), T and F forced, every U names its reason, outside D a refusal with reason | K3 (inputs correct), K5, K6 (implementation correct, serialised), K7 (version recorded is the one that ran) [R] | `ce_model.py` v20 [V] | **S1** [P] | §9.13.10 open questions, incl. how a removal is recorded and by whom [R] |
| 3 | **No self-supporting authority**: an entry cites earlier entries only | §9.13.4 R3 (the Principal's decision; the lemma KK = WF on D) | under CON-0191 | a D-clause and a property test | the Principal's restriction [R] | `ce_model.py` [R] | **S1** [P] | — |
| 4 | **Entitlement of the named actor**: authorized(x) relative to the inputs (G2) | §9.13.3 (Φ) · CON-0191 | PROVISIONAL | Φ as code; tests relative to stubbed covers and identity link | K3 [R] | `ce_model.py` [R] | **S1** [P] | — |
| 5 | **The record's form** (G3): every change an entry; removal only by a recorded act (tombstone); two orders; pinned inputs | §9.15 R1 G3; §9.16 R5 · CON-0193, CON-0194 | PROVISIONAL | append-only record with tombstones; tests of H1′, H2, H3 | K1, K2 [R] | `ce_model.py`, `kernel_model.py` [R] | **S1** [P] | how a removal is recorded and by whom (§9.13.10) [O] |
| 6 | **No grant consumed twice** (G4; the single-use guard; F22 = (C + O), one owner per grant) | §9.16 R4a–R4d · CON-0194 | PROVISIONAL; **closed as a design sketch** (the Principal, 2026-10-21) | the append guard; tests: a second use refused within one serialised kernel | K4 (one serialization point per law), K6 [R] | `ce_model.py` [R] | **S1, as a sketch** [P] | T9-07: the protocol (use in flight, owner transfer, …) not worked out [O] |
| 7 | **Prevent or detect, per law, across applications**; records merged without a serialization point | §9.16 R2, R3, R3a · CON-0194 | PROVISIONAL | merge function; per-law tests: prevented laws refused, detected laws reported | K4 [R] | `ce_model.py` checks 18–18d [R] | **S1** [P] | — |
| 8 | **The designated record** (K2 restated: one record, its carrier designated) | §9.17 R1, R2 · CON-0261 | PROVISIONAL [V] | carrier and designation as record entries; K2-w, K2-r tests | L11 (root designation outside D) [R] | `ce_model.py` [R] | **S1** [P] | T9-08 (a) K2-r's refutation [O] |
| 9 | **Membership by acceptance and the designation rule** (cite every maximal entry of the old store; old store = carrier in force; a store is a carrier once; F/U designations refused) | §9.17 R3 (c), (c′); §9.13.2 · CON-0264 | PROVISIONAL; **closed as a design sketch** (decision record 2026-10-28) | the designation guard and the D-clauses; tests: fork, stale, reuse and bypass refused or outside D | K6; kernel attestation **assumed unforgeable**; one owner application per store [R] | `ce_model.py` v20 checks 30–31 [V: re-run 2026-10-29]; independent reconstruction [V] | **S1, as a sketch** [P] | T9-11: owner transfer and other protocol questions [O]; across merged applications only **detected** (T9-10) [R] |
| 10 | **The carrier-change gate**: survivor and casualty checked against an outside reference before the old carrier is retired | §9.17 R4 · CON-0265 | **VALIDATED** [V] | the gate as a check; tests from R4 C1–C5 | its stated premises (R4) [R] | `anchor_model.py` v2 [R] | **S1; first S2 candidate** [P] (VALIDATED, but its formal specification has had no separate specification review) | — |
| 11 | **Conflict among rules reduces to pairs exactly when the Helly number is ≤ 2** (G5) | §9.14 R1–R5 · CON-0192 | PROVISIONAL | a checker that computes h for a rule class and reports conflicts set-wise, reduced to pairs when h ≤ 2 | its own premises (product lemma, realisability for the converse) [R] | `helly_check.py` [R] | **S1** [P] | the converse rests on realisability (§9.14.4) [R] |
| 12 | **Refutation from the record alone** (G6): an integrity failure refused; a contradiction gives U with its reason | §9.13; §9.9 · CON-0193 | PROVISIONAL | integrity check as a stub (K5); tests on contradictions | K5 [R] | `ce_model.py` [R] | **S1** [P] | — |
| 13 | **The trust boundary**: K1–K7 inside, L1–L11 in the world | §9.15 · CON-0193 | PROVISIONAL | **not code:** every K and L becomes a named parameter or stub, so the model shows what it assumes | — | — | **S1** [P] | §9.15.10 [O] |
| 14 | **One standing per (item, scope, time, axis)** (K5 of §9.7) | §9.7 · CON-0143 | PROVISIONAL | an invariant test | §9.7's premises [R] | — | **S0–S1** [P] (no model) | — |
| 15 | **One current claim per (act, dimension)** | §9.4 · CON-0115 | PROVISIONAL | an invariant test | §9.4's premises [R] | — | **S0–S1** [P] (no model) | — |
| 16 | **Evaluator values need a common, versioned value** | §9.7 · CON-0173 | PROVISIONAL | the version field and a replay test (with K7) | K7 [R] | — | **S1** [P] | — |
| 17 | **The kernel type: laws on states, steps and histories** | §9.11 · CON-0183, CON-0189 | PROVISIONAL | each law classified in §9.11 as a property test of its class | §9.11's premises; the whole-history premises (R1) [R] | `kernel_type_model.py` [R] | **S1** [P] | T9-05: §9.6–§9.9 not yet derived from the kernel type [O] |

**Not candidates** (the book does not support building them): the required capabilities (Req, CON-0188, PROVISIONAL) as a fixed list; a **minimal primitive set** and laws **derived** from it (not yet in the book; T9-01–T9-04); whether a minimal kernel **exists and is unique** (T9-04). These are the gates of stages 3 and 4, not stage 1. INVALID concepts of Ch. 9 (CON-0100, 0110, 0114, 0116, 0118, 0135–0137, 0142, 0148, 0161, 0262, 0263) are excluded except as negative tests where the book states their counterexample [P].

## 3. Summary

- **17 candidates;** 16 PROVISIONAL, 1 VALIDATED (CON-0265) [V].
- **Proposed levels [P]:** 13 at S1; 2 at S1 **as design sketches** (6, 9); 2 at S0–S1 with no model yet (14, 15). **None at S2;** CON-0265 is the first S2 candidate.
- **Consequence [I]:** everything on this list may be built only as the **experimental reference model** (stage 1), marked experimental. Nothing qualifies for a kernel prototype.

## 4. Points for the stage-1 decision

1. **Ch. 9 is rotated in full at the S0225 consolidation** (`BOOK-OUTLINE.md`). That review gives every candidate above its consolidation verdict and would surface any open critical finding [V: rotation table]. **Proposed:** take the stage-1 decision **after S0225 is settled**, with this list updated by its verdicts. Before then, the "no open critical finding" condition of S1 is supported only by the window and turn reviews [P].
2. **Stage 2 needs a mapping.** The book states that the present estate's record is **outside D**, so CE\* refuses it and G2–G5 yield nothing (§9.15.6) [R]. Validating against real records therefore needs either records in D or a stated mapping into D. **Open question for the Professor** before stage 2 can be planned [O].
3. **Existing models** (`ce_model.py` v20 and others) are research instruments. Stage 1 builds a **clean library** from them; the models stay the research's evidence and are not edited by the engineering session [P].

## 5. For the Professor (confirmation)

For each row: confirm or correct the **register status**, the **assumptions**, the **open questions** and the **proposed level**; add any kernel result missing from the list; and answer §4 point 2. Mark each change with its source line. Report to the Principal.
