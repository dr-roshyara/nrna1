# M2: proofs, tests, v1 → M2 behaviour, record-view comparison

**Labels:**
- **PROVED**: a proof given here; not yet independently reviewed;
- **CONJECTURE**;
- **TESTED**: an encoding observation only;
- **COUNTEREXAMPLE**.

All claims refer to `M2-MODEL.md`. "Finite record" means K_k is finite.

## A. Proofs

**Lemma 0 (connectives are monotone).** Strong Kleene ¬, ∧, ∨ and the threshold ATLEAST(*n*, ·) are monotone in the information order ⊑, coordinate-wise.
- For ATLEAST: raising one argument from U to T can only move the result from U to T (#T grows), or keep it. Raising it from U to F can only move the result from U to F (#T + #U shrinks), or keep it. A definite result never changes.
- The others are standard.

**PROVED.**

**T1 (existence and uniqueness of the least fixed point; every option; every solve).**
- *Premises:* a finite record; a fixed question (*c*, *t*, *k*); a fixed suppression set (S, S_use).
- *Statement:* the key set *D* is finite, the operator Φ is monotone on (V₃^D, ⊑), and Φ has a unique **least** fixed point, reached by iteration from all-U in at most |*D*| strict steps.
- *Proof:*
  1. **Keys are finite.** Acts are finite. Points are drawn from {*p*(*x*)} ∪ {q(*t*)}. Views are drawn from {now} ∪ positions ∪ {(*t*ₓ, *s*ₓ)}. Judges are acts. Claims are *c* plus finitely many successors. So *D* is finite.
  2. **Every coordinate is a Kleene term.** Each coordinate of Φ is a term built from:
     - constants: record data, comparisons of data, and option values λ, κ, κ_g, π, ε, covers' data part;
     - other coordinates, combined with ¬, ∧, ∨ and ATLEAST.

     No coordinate applies a map such as h(U) = F (v1's non-monotone υ), because υ = does-not-count acts **only** by fixing S **between** solves. So Φ is monotone, by Lemma 0.
  3. **The iteration climbs and stops.** (V₃^D, ⊑) is finite with bottom ⊥ = all-U, so ⊥ ⊑ Φ(⊥) ⊑ … is a chain. Each strict step fixes at least one coordinate from U, and coordinates never revert, so the chain stabilizes after at most |*D*| steps.
  4. **The limit is least.** Induction shows Φⁿ(⊥) ⊑ *q* for every fixed point *q*, so the limit is the **least** fixed point (Kleene's theorem for a monotone map on a finite pointed poset).
  5. **The cited theorem was wrong.** Knaster–Tarski does not apply: there is no top element.

  ∎
- *Scope:* "unique" means the unique **least** fixed point; other fixed points may exist (review F-17).
- *Encoding check (TESTED):* the solver asserts ⊑-growth at every step; there were 0 violations in 700 random evaluations across all options.

**T2 (υ = does-not-count is well defined; termination; determinism).**
- The greedy loop visits each ending or use at most once and adds it to S at most once. So it performs at most |endings ∪ uses| + 1 solves, each defined by T1. **Termination: PROVED.**
- The result is determined by the record and the record order. **Determinism: PROVED.**
- *Order dependence:* under ρ = now it is a **definition**, not a theorem of agreement. For mutual annulment, the earlier-recorded annulment is suppressed and the later one counts. This is a **policy property** of the greedy rule (reserved decision 6 / C10).

**T2′ (agreement with v1's stratified semantics).**
- *Premises:* ρ ∈ {at-recording, at-act}; consume_view = view; Ax1; Ax2.
- *Statement:* the greedy result equals the direct stratified evaluation in record order, with e(U) = F (v1's υ = "does not count until evidenced", defined by stratification).
- *Proof:*
  1. By T3, A(*x*) depends only on entries with *s* < *s*ₓ.
  2. When the greedy loop reaches *y*, every suppression decision for entries before *y* has been made, and later suppressions cannot change A(*y*).
  3. So A(*y*) at its turn equals the stratified value, and *y* is suppressed iff that value is U. That is exactly the stratified rule.
  4. Induction on *s*. ∎
- *Not claimed under* ρ = now or consume_view = now.
- *Evidence:* the review's F-3 witness. M1 changed v1's answer F → T; M2 returns v1's F. TESTED, regression `F-3`.

**T3 (stratification: no dependency cycles between authorizations).**
- *Premises:* ρ ∈ {at-recording, at-act}; consume_view = view; Ax1; Ax2.
- *Statement:* every A-key read, directly or through W/V keys, by A(*x*) concerns an entry with *s* < *s*ₓ.
- *Proof:*
  1. **at-recording:** visibility is *s* < *s*ₓ by definition.
  2. **at-act:** suppose (τ_y, *s_y*) < (*t*ₓ, *s*ₓ).
     - If τ_y < *t*ₓ ≤ τₓ (Ax1), then *s_y* ≥ *s*ₓ would give τ_y ≥ τₓ (Ax2), a contradiction. So *s_y* < *s*ₓ.
     - If τ_y = *t*ₓ, the tie-break gives *s_y* < *s*ₓ.
  3. W keys in view v(*x*) read issuers, endings, act annulments and uses, all visible, hence earlier.
  4. Parent W keys stay in the same view.

  ∎
- *Consequences:*
  - (i) A has a unique solution by recursion on *s*, independent of fixed-point machinery.
  - (ii) **U never arises from mutual annulment, mutual revocation, or the revoked-annuller freeze** (review F-13, C10).
  - (iii) U can still arise from:
    - unknown values (κ, κ_g, π, λ, unrecorded holders);
    - **cycles among W keys of one view**, i.e. cyclic issuance. Here W1 forces *p*(i(*h*)) ≤ *p*(i(*g*)) and the converse, so at least one issuing act is not before the other and its W is F. U survives only when both issuing acts sit at the same point, which is impossible for distinct records (review F-16);
    - **cycles among V keys**: two act annulments annulling each other, both visible. These are U, and they affect only the effects of the two annulments.
- **Fails under consume_view = now:** a use recorded later but dated earlier can debit *x*'s grant, while *x* is visible to that use. **COUNTEREXAMPLE class.** T1 still guarantees a least fixed point; cycles then give U.

**T4 (the headline is total and agrees with the value).**
- *Statement:* for every finite record, every option value, and every (*c*, *t*, *k*), H is defined. When the value is F, the reported reason is a failing conjunct of a disjunct that is F.
- *Proof:*
  1. Value T or U: the headline is fixed.
  2. Value F with no adoption by *t*: only ε exists, and it is F only under C1-a. It carries "not adopted by *t*".
  3. Value F with adoptions: every disjunct is F, so each has a conjunct that is F. Map the conjuncts:
     - c1 = F → "not validly made";
     - ¬ann = F ⇔ ann = T → "annulled (void)";
     - c2 = F → "not yet valid" or "no longer valid";
     - c3 = F ⇔ some revocation, supersession or override is T → its reason;
     - c4 = F ⇔ some ending is T → "authority ended" or "suspended".

     So every disjunct's reason set is non-empty, and "progress" selects one of them.

  ∎ **PROVED.**
- *TESTED:* 0 headless F results in 700 random evaluations.
- Under ω = intersection (outside M2) the theorem would become per scope atom (review F-19).

**P2a (adding an adoption act preserves T).**
- *Premises:* ρ ∈ {at-recording, at-act}; consume_view = view; the new adoption *x′* of *c* is recorded **last** (max *s*, τ ≥ all).
- *Statement:* if C(*c*, *t*) = T before, it is T after.
- *Proof* (the review's, re-derived):
  1. *x′* is invisible in every existing view, so every existing A, V and W key keeps its equation.
  2. C(*c*) gains one disjunct.
  3. By induction on the Kleene chains, V′ₖ ⊒ Vₖ, so a T stays T.

  ∎ **PROVED.**
- Under ρ = now or consume_view = now it **fails**: the M1-era witness (`prop2_check.py`) and quota debits. Under C6 = later-prevails, adding an adoption of a **conflicting** claim *d* can turn *c* from T to F, by design.

**P2b (adding a basis preserves T).** **FALSE** even under the premises of P2a (review F-14; regression `F-14`: T → F). It is not claimed.

**P4 (the record views agree).**
- *Premises:* a **static** dependency set D(*x*): the issuers, endings, act annulments and uses of every grant in *x*'s citations and their ancestors, plus the annulments of those, transitively. For every *x* and every *y* ∈ D(*x*): *s_y* < *s*ₓ and τ_y ≤ *t*ₓ.
- *Statement:* A(*x*) is the same under now, at-recording and at-act, and C(*c*, *t*, *k*) is the same under all three.
- *Proof:*
  1. Under the premise every relevant entry is in every view of *x*. Induction on *s* gives equal A.
  2. C reads K_k in every view. The only view-dependent term, c4's "unseen" clause, is vacuous under the premise.

  ∎ **PROVED.**
- The premise is **static** (record structure, not computed values), which removes review F-15's circularity. F-15's witness (a revocation recorded at τ = 6 with act time 5) now gives "revoked" in both views (TESTED).

**Annulment chains.** V(*y*) = A(*y*) ∧ ¬⋁ V(*z*): a finite chain is evaluated inside the fixed point, and cycles give U (T1). Regression `F-2`: annulment of an annulment of a revocation gives "revoked". **PROVED by the structure, TESTED.**

**Root issuance under cascade.** The parents of an independent grant are {∅}, ⋀∅ = T, so W4 = T. **PROVED**; regression `F-1`.

**Question instant.**
- v1 case 12: "an adoption and a revocation share the same act time; record position decides the order. Revocation after adoption: **revoked**", asked at that same time.
- Under q = (*t*, −∞), a revocation at (*t*, *s*) is not ≤ q, so the claim would be current. That contradicts case 12.
- So **inclusive is DERIVED from v1**. Exclusive is an option only. TESTED (`F-5`).

**Expiry boundary.** By the definition of w2:
- half-open: *p* < (to, −∞), so an act at exactly `to` is **outside**;
- closed: *p* ≤ (to, +∞), so it is **inside**.

No authorized source decides which applies (IM L131, L211–219; AMS L284 [SRC, re-read earlier]). **C14.** TESTED both ways.

## B. Tests

`tests_m2.py`: **49/49** expectations met. **TESTED, not proof.**
- Each expectation names its source: a v1 case, a safeguard, a review finding, or an option's documented behaviour.
- **Coverage:** the five reproduced counterexamples, the six v1 defects, every remaining review finding, and both values of every testable option.

**Mutation check (do the tests detect regressions?).** Reverting each of four repairs makes its own test fail:

| Repair reverted | Test that fails |
|---|---|
| root parents {∅} | F-1 |
| recursive annulment | F-2 |
| greedy suppression | F-3, F-4 |
| progress headline | F-13 |

**Random probe:** 700 evaluations across all options: 0 information-order violations, 0 headless F results, 0 errors.

**Known limits of the evidence:**
- The tests' **test-helper defaults** (recording time = act time unless given; grant period 100) are explicit in `tests_m2.py`, but they are still defaults. Every test that depends on recording time sets it.
- Only the encoding's author wrote the expectations. The independent review (§9 of the package) is the check on them.

## C. v1 → M2 behaviour on every known change

| # | Case | v1 result (and when it is defined) | M2 result | Why | Correction or policy? |
|---|---|---|---|---|---|
| 1 | act after the grant's recorded end | literally authorized (the window never closes; v1 case 3 says otherwise) | not validly made | the period enters the window | **mathematical correction**: v1 contradicts its own case 3 |
| 2 | no adoption on record, C1-b | value F, headline "cannot determine" (inconsistent) | U, "cannot determine" | ε inside the formula | **correction** |
| 3 | adoption annulled with effect | F, no headline | F, "annulled (void)" | new reason | **correction** |
| 4 | act under a grant issued by an unauthorized act | authorized | not validly made | grants are products of checked acts | **correction** (safeguard 2) |
| 5 | delegate's act after the delegator's grant ended | authorized for every P | δ = no: authorized (= v1) · δ = yes: not validly made | the cascade is explicit | **policy**: reserved decision 1 (δ) |
| 6 | two acts at the same act time using a single-use grant | U, or no fixed point | first recorded wins | intervals in points | **correction** (v1 states the total order) |
| 7 | revocation dated before *x*, recorded after *x*, at-the-time | current for ever, no note | late = reach: current until known, then "authority ended" · late = never: = v1 | v1's timing note is silent here | **policy**: reserved decision 2 |
| 8 | late-recorded act *x* (case 10 variant) | v1 judges by position (at-recording) | at-recording = v1 · at-act: judged by the records at its act time | v1's "at the time" is ambiguous | **policy**: reserved decision 2 |
| 9 | mutual annulment, ρ = now, υ = does-not-count | undefined (no least fixed point) | defined; record-order greedy | stratified υ | **correction (existence)** + **policy (order rule)**: reserved decision 6 |
| 10 | review F-3 witness | F (stratified) | F | — | none (M1 had changed it) |
| 11 | annulment of an annulment | undefined effect | the effect is restored | recursive V | **correction** |
| 12 | x1 invalid, x2 valid, later revocation | headline "not validly made" | "revoked" (progress); union reproduces v1 | v1's own rationale for the causal order | **correction by v1's stated rationale**; union kept as C8 |
| 13 | grant record without an issuing act | grants "as recorded": usable | C1-a: usable (= v1) · C1-b: U | κ_g tied to C1 | **policy**: C1. *Data note:* every live grant carries a human-act reference. If the reference counts as the issuing (root) act, today's behaviour equals v1. **Open question Q1** |
| 14 | single-use or quota consumption under at-the-time | consumption as an ending; over-consumption possible | consume_view = view: like v1 · = now: first by point wins | explicit | **policy**: C13 |
| 15 | any act with a non-root citation | covers undefined, so formally undefined | defined (U today: no holder field) | covers defined | **correction (definition)** |
| 16 | question at the instant of a same-time revocation | "revoked" (case 12) | inclusive: "revoked" (= v1) | — | none |

**No change of meaning is hidden.** Every policy-dependent difference has an option whose value reproduces v1 where v1 was defined: δ = no; late = never; ρ = at-recording; headline = union; consume_view = view; κ_g with the reference treated as the act.

## D. Record views compared (reserved decision 2: comparison, **no selection**)

**In business language:**
- **at-recording** judges an approval by what was on file when the approval itself was **filed**. A late-filed approval is judged by later information.
- **at-act** judges it by what was on file when the approval was **made**.
- **now** judges everything by today's file.

| Criterion | at-recording (own position) | at-act (record state at act time) | now (later evaluation) |
|---|---|---|---|
| Determinacy and cycles | stratified: no cycles between authorizations (T3) | stratified (T3, with Ax1, Ax2) | cycles possible (mutual annulment, revoked annuller): U, or order-dependent under υ = does-not-count |
| Late-recorded or backdated revocation (of a grant) | the act stands (good faith); its product ends once the revocation is known (late = reach) | same | the act was never validly made, retroactively |
| Effect of the act's **own** recording delay | **yes**: a late-filed act can see later entries (F-6) | **no** | n/a (everything is visible) |
| Late-recorded adoptions | bitemporal: counted from *k* ≥ τ (symmetric with endings) | same | same |
| Reproducibility of a past judgement | fixed once the act is filed | fixed once every entry with τ < *t*ₓ is filed. A record backfilled later with τ < *t*ₓ is impossible: τ is when it was filed, so later filings have τ > now ≥ *t*ₓ | changes whenever late entries arrive |
| Single-use and quota | over-consumption possible (F-8, F-21) unless consume_view = now (which breaks T3) | same | first by point wins |
| Source fit | v1's formula (positions) | v1's business wording: safeguard 3 "what the records showed **at the time**"; case 10 "on 5 March … not yet on record" | v1's ρ = now |
| Remaining policy questions | late; consume_view; λ | late; consume_view; λ | υ order rule; C10 cycles; λ |

**Recommendation (AI; not a selection):** **at-act** is the simplest defensible view.
- It is stratified, so there are no cycles and v1's υ semantics agree (T2′).
- It is independent of the act's own filing delay.
- Its past judgements are reproducible.
- It matches the business wording.

The residual policy questions are **late** (recommend reach), **λ** and **consume_view**. The trade-off: consume_view = now guarantees "at most *n* uses" but breaks stratification (T3); view keeps stratification but allows over-use, detectable afterwards. **Reserved decision 2 remains the Principal's.**
