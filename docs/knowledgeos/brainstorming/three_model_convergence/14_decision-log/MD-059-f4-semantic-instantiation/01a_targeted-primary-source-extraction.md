# MD-059 §01a — Targeted F4 Primary-Source Extraction (prerequisite check, performed before
finalizing MD-059's own artifacts)

Per the user's own request: before relying on Model B's Phase-2 *register* (a secondary synthesis),
the primary sources underlying its strongest `K_t` entries were opened directly: **M0043**
(`20260902-004631_...definitions-axioms-theorems-corollaries.md`, 3302 lines), **M0132**
(`20260902-175303_...gap-theory-v1-critical-assessment-and-integration-strategy.md`, 387 lines), and
**M0125** (`20260902-175304_...knowledge-transfer-for-next-session.md`, partial). M0047/M0126 were
not separately opened — the material actually load-bearing for this question (the `K_t`/`Δ_t`/`Sat`
apparatus) is concentrated in M0043 and M0132, and M0125/M0126 are confirmed near-duplicates of each
other (register §G, `03_contradictions-and-open-questions.md` UE-2) — reading one plus M0132's own
restatement of the same tuple is sufficient without re-reading its duplicate.

## Seven questions, answered

| # | Question | Answer | Status |
|---|---|---|---|
| 1 | Can one or more complete `K_t` formulations be reconstructed from primary sources? | **PARTIALLY FOUND** — M0043's `[DEF-11]` deliberately declines to fix a tuple (`𝕂` = "space of admissible semantic epistemic states," type left abstract "as the principal unresolved mathematical question"); M0132/M0125 supply one concrete 11-component candidate, `K_t=(A_t,R_t,E_t,Σ_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t)`, of which **one component, `Σ_t = (A,S,R,V,C)`, has a real, enumerated, typed field definition** (Acquisition/Support/Resolution/Validity/Conflict); the other ten components are named but not separately typed in the files opened | CORPUS FACT |
| 2 | What fields/components are actually defined for each? | Only `Σ_t`'s own five sub-fields are typed with enumerated value domains (table, M0125). The remaining ten top-level components (`A_t,R_t,E_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t`) are named, not separately defined, in the files opened | PARTIALLY FOUND |
| 3 | Is `Δ_t` semantically connected to those components? | **NOT directly** — `Δ_t = {r∈R_t : Sat(K_t,r)=0}` treats `K_t` as a single argument to `Sat`, not as its own decomposed components; M0132 states directly: *"the semantics of each component are still open. Therefore `Sat(K_t,r)` cannot be fully defined yet"* — i.e. the corpus's own text explicitly ties `Sat`'s eventual computability to the components' own resolution, without yet using them | CORPUS FACT (both the definition and its own stated limitation) |
| 4 | Is `Sat(K_t,r)` defined anywhere in the primary F4 evidence? | **FOUND as a SHAPE, NOT FOUND as a body** — `Adequate(K_t,EC_t) ⟺ Sat(K_t,EC_t)` (`[DEF-20]`, M0043) and `Δ_t=Gap(K_t,EC_t)={r∈Req(EC_t):¬Sat(K_t,r)}` (`[DEF-21]`) are real, primary, formally-typed definitions — but M0132 explicitly and directly poses *"How should `Sat(K_t,r)` be formally defined for each class of epistemic requirement?"* as its own open question, twice (Part 4 header and closing questions), and freezes only the *equation shape*, marking the underlying predicate `[FROZEN]`-as-form while its computation remains `[OPEN]` | CORPUS FACT |
| 5 | Is there an F4 transition semantics in the primary sources? | **FOUND** — `K_{t+1}=δ(K_t,e_t)` (M0125, event-indexed, matching MD-057's own catalogued form A for `δ`) and `K_t=Replay(K_0,H_t)` (M0125) both appear as stated formulas, not merely named | CORPUS FACT |
| 6 | Is there an F4 observation/output/consequence semantics the register-level extraction missed? | **YES — a significant find**: `[DEF-15]` (M0043) states, as a named, boxed, primary definition: *"two representations `r₁,r₂` are semantically equivalent... iff `⟦r₁(x)⟧=⟦r₂(y)⟧`... more generally, for observable behavior `B`: `r₁≡_sem r₂ ⟺ B_{r₁}=B_{r₂}` over the admissible test domain."* This is a **corpus-native, primary-source precedent for exactly the shape** MD-058 derived independently (`Obs_{Q,𝒪}`-equality) — not found by the Phase-2 register's own summary, which cited only the *later*, same-day, rejected "CLOSURE-4" reformulation (already characterized in MD-057) | CORPUS FACT — **not previously surfaced in this reconstruction's own MD-057/058 record** |
| 7 | Are `Obs`/`Beh`/`Trace` genuinely absent, or merely not yet reconstructed? | **Mixed, precisely**: the *shape* of an observational-equivalence relation is genuinely present and primary-sourced (`[DEF-15]`), so "absent" is too strong; but its *instantiation for F4's own `K_t`* is genuinely blocked, not merely unreconstructed — by the corpus's own admission (M0132), because `Sat`'s computation is stated to require component semantics that remain open for 10 of 11 named components, across a family that itself has 9+ mutually unreconciled variants (UE-1/UE-2) | CORPUS FACT (the blocker itself is source-stated, not inferred) |

## Decision gate

**B — F4 still cannot be fully instantiated. Smallest exact missing primitive, now precisely
sharpened by primary-source evidence**: not "closure of `R_t`" (the earlier, register-level framing)
but **a decomposition-independent body for `Sat(K_t,r)`** — the corpus's own text (M0132) ties this
directly to resolving `K_t`'s own component semantics, and only one component (`Σ_t`) has ever been
given a concrete typed definition, within only one of 9+ mutually unreconciled `K_t` variants that
have never been shown consistent with each other (UE-1/UE-2). **This does not authorize inventing
that body — it sharpens, not removes, the obstruction MD-059's own construction attempt (`02`) must
respect.**

## Correction, disclosed (MD-058's own text NOT edited)

MD-058 characterized `Obs_{Q,𝒪}`-equality as a **NECESSARY CONSEQUENCE** derived from abstract
requirements (R1/R2/R4), described as arising independently of any specific corpus citation for its
own shape. This primary-source check finds that shape **already stated as a named, primary corpus
definition** (`[DEF-15]`, M0043, 2026-09-02) — predating MD-058 by this reconstruction's own
chronology, and never opened during MD-058's own requirement-ledger construction (which drew on
Model B's register-level synthesis, not this specific primary file). **This does not invalidate
MD-058's mathematics** (P1/C1/R1a remain genuine, independently-checkable derivations) — it corrects
the *provenance claim*: `Obs_{Q,𝒪}`-equality is better classified as **CORPUS-DERIVED, convergent
with a primary corpus definition (`[DEF-15]`)**, not as a relation this reconstruction arrived at
with no corpus precedent for its shape. Recorded here, in the new phase, per this reconstruction's
own standing discipline of never silently repairing a completed artifact.
