# M2: source fidelity and evidence register

**Kinds of evidence:**
- **[SRC-V]**: a source line verified by the author this session;
- **[SRC-R]**: reported by a reviewer, not re-read by the author;
- **[BOOK]**: baseline v1;
- **[DER]**, **[ASM]**: as in the model;
- **[TEST]**: encoding observation;
- **[REV1]**: the first independent review.

**Status of validation:** internally reproduced = checked by the author; independently validated = **nothing yet**; the second review is pending.

## 1. M2 elements and their support

| M2 element | Support | Kind |
|---|---|---|
| act time, effective time, total order (*t*, *s*), half-open intervals | v1 L85–89 | [BOOK]. "Half-open" is the book's **convention**, not a source rule (→ C14) |
| one totally ordered world-time domain | never stated in v1 | [ASM] (review 1 noted it) |
| Ax1 τ ≥ *t*; Ax2 τ monotone in *s* | — | [ASM]. The live records carry `seq` only per file and explicit dates on 2 of 274 transitions ([REV1] §6), so neither axiom can be checked on today's data |
| bitemporal question (*t*, *k*) | safeguard 3, v1 L60: "what the records showed at the time … or what they show now" | [DER] from [BOOK] |
| question instant inclusive | v1 case 12, L177 | [DER] from [BOOK] |
| grants as products of issuing acts | AM-3 "every grant must reference an immutable human act" (AMS L286 via §1.13.3); "not transferable — a new act is required" (AMS L189) | [SRC-V] L189 · [BOOK] AM-3 |
| delegation distinct from independent authority | AM-2 (AMS L285) | [SRC-V] |
| scope and activity narrowing for delegation | AMS L285 "within a narrower boundary"; L92–94 "re-granting with a narrower scope" | [SRC-V] |
| right to delegate | issue-grant is an activity (v1 L93) plus safeguard 1 | [DER] |
| period containment of a delegated grant | §1.13.3(a) | [ASM] (the book's derivation; no source) → C15 |
| cascade δ | §1.13.3(d): "the sources do not rule" | open → RD 1 |
| consumption as the effect of an authorized use | the CONSUMED record: "recorded, not newly authorized" (§1.13.2) | [BOOK] observation + [DER] from safeguard 6 → C13 |
| expiry boundary | IM L131 (`validFrom < validUntil`), IM L211–219 (the asking instant), AMS L284 | [SRC-V]: **no source decides** → C14 |
| κ_g per C1 | AMS L49–51 (C1-a) vs IA L99–101 (C1-b), via §1.13.1 | [BOOK] summary of [SRC] |
| holder unrecorded today | `session-resolve.php` L268–269: "not evaluable from the record" | [SRC-V] (code, verified earlier) |
| headline by progress | v1 L155: the causal-order rationale | [DER] from [BOOK] |

## 2. The two authority definitions (investigated; **not** assumed equivalent)

| Definition | Where | What it is about | Kind |
|---|---|---|---|
| **authority = act × scope × time** | book §1.6 (authority axiom); §1.13.1 (AMS: "a recorded permission for a named activity within a stated scope …") | a **permission to act**: who may do what, where and when. Its bearer is an actor or a grant | [BOOK] / [SRC-V] AMS L49 |
| **authority = immutable PROVENANCE × act-changed STANDING** | S0003 L56 and S0002 L37, both **citing** the "Engineering Progression Model", which the author has not read | a property **of a knowledge item**: where it came from × what acts have made of it. Its bearer is a claim | [SRC-V] (S0002, S0003 as secondary syntheses) |

**Analysis [DER]:**
1. **Different bearers.** One applies to actors or grants (who may act); the other to claims (what standing a piece of knowledge has). Statements about one cannot be substituted into the other, so they are **not equivalent definitions of one concept**.
2. **Different arity.** act × scope × time has no provenance component. provenance × standing has no scope or time component; time enters it only through acts.
3. **A possible relation (Hyp, not established):**
   - the book already separates provenance and standing as two dimensions of a **claim** (§1.2; EP01: provenance ⟂ standing);
   - standing changes only by acts authorized under act × scope × time (§1.6).

   So "provenance × standing" may be what the book calls **a claim's position on two of its dimensions**, and act × scope × time is what **authorizes the acts** that change one of them. Under this hypothesis the corpus uses "authority" for the first and the book for the second: **term drift, not contradiction.**
4. **A third notion in the book:** §1.14.1's "current authority **of a claim**" (derived from standing, grants and time). This is closer to provenance × standing than to act × scope × time, which supports the hypothesis.
5. **What would test it:**
   - read the Engineering Progression Model (if it is in the S-series index; not yet reached);
   - check whether the corpus ever applies "authority" in the P × S sense **to actors**, which would refute the hypothesis;
   - check whether it applies act × scope × time anywhere, which would show coexistence.
6. **Status: UNRESOLVED.** Proposed concept-validation entries KC-AUTH-1 / KC-AUTH-2, with the relation as an open contradiction or term drift.

## 3. Evidence limits that travel with M2

- **No validation on live data.** The live records lack holder, activities, period, issuer link, global position and recording time ([REV1] §6). Every live claim evaluates to U under M2, as under v1.
- **Same-model-family reviews.** Both reviews are same-model-family sessions, so they are internally independent at best: a fresh context, no access to the author's conclusions. They are not external validation.
- **Reported line numbers.** AMS L104–112 (scope prose) and L61–72 (one holder) come from [REV1]/[BOOK] and were not re-read in this round.
