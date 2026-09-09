# MD-059 §02 — Attempted Semantic Construction (§4/§5 of the authorizing prompt)

## Anti-invention gate, applied to each alternative in order

### A. Observation semantics `Obs_F4 : Q × 𝒪 → V`

**Can it be constructed directly from corpus-established F4 definitions? YES, in form — and this
form is itself primary-sourced, not this reconstruction's own invention.**

Define `Obs_F4(K_t) := (r ↦ Sat(K_t,r))_{r∈Req(EC_t)}` — the function mapping each requirement
`r∈Req(EC_t)` to whether `K_t` satisfies it, for a *given, stated* epistemic contract `EC_t`. This
reuses `[DEF-19]`–`[DEF-21]` (M0043, primary) directly — `EC_t`'s own parameterization is a
**deliberate design choice** (purpose-relativity), corrected from this file's own earlier "R_t is an
unclosed registry" framing (see `01`'s own correction note) — **no internal decomposition of `K_t`
is invented**; the anti-invention gate is satisfied for the *definition*.

**Dependency exposed, not silently assumed away, and now sharper than the first pass found**: this
definition is only *computable* once `Sat(K_t,r)` itself has a body. **The corpus's own primary text
(M0132) states directly that this requires `K_t`'s own component semantics** — and only one of eleven
named components (`Σ_t`) has ever been given a concrete typed definition (M0125), within only one of
9+ mutually unreconciled `K_t` variants (UE-1/UE-2). **Status: `Obs_F4` — MATHEMATICALLY DERIVED IN
FORM, CORPUS-CONVERGENT with `[DEF-15]`'s own observational-equivalence shape; NOT COMPUTABLE —
blocked on a decomposition-independent body for `Sat`, a corpus-stated dependency, not an invented
one.**

### B. Behaviour semantics `Beh_F4(K_t)`

**Can it be constructed? NO, not without an arbitrary choice.**

The only corpus-native candidate transition, `Orgasm_t: K_t→K_{t+1}`, is a single named transition,
not a closure operator over a *registry* of admissible transitions the way F3's `Reach` is defined
over F3's *registered* operator set. The corpus's own candidate registry for *transition/merge*
operators is the composition-rule set (§N) — but two of its five tested candidates ("majority",
"intraframe-only") survive without being shown necessary or sufficient (`P-12`/`P-13`), and the
relationship between the two survivors is itself unaddressed (UE-3). **Choosing either survivor to
define `Beh_F4` would be exactly the forbidden arbitrary modelling choice.**

**Status: `Beh_F4` — UNAVAILABLE. Smallest missing input: a corpus-native decision (not yet made)
selecting among, or reconciling, the surviving composition-rule candidates.**

### C. Trace semantics `Tr_F4(K,n)`

**Can it be constructed? NO**, for the same reason as B — a trace of length `n` requires composing
`n` applications of *some* admissible transition, and no single admissible transition is corpus-
established as canonical (`Orgasm_t` is one instance, confirmed executed once, but its own
irreversibility status relative to the kernel is itself `UNRESOLVED`, P-6/OQ-3).

**Status: `Tr_F4` — UNAVAILABLE, same root cause as B.**

### D. Satisfaction semantics `Sat_F4(K,C)`

**Already corpus-native**, per §01 — `Sat(K_t,r)` is the frozen-as-shape apparatus itself (M0132
ratifying M0043/M0047), not something this phase constructs. This is the one case among the four
where the corpus supplies more than MD-058's general apparatus could assume (R8, from MD-058, found
`C_KOS`/`⊨` unspecified *in general*; F4 specifically supplies a real, governance-frozen shape — a
genuine, disclosed gain). **The corpus's own text is explicit that this gain is partial**: M0132
poses, as its own open question (twice), *"How should `Sat(K_t,r)` be formally defined for each class
of epistemic requirement?"* — the shape is frozen, the body is not.

**Status: `Sat_F4` — AVAILABLE as a frozen shape (CORPUS FACT), NOT AVAILABLE as a computable body —
blocked on `K_t`'s own component semantics (same dependency as A, now stated by the corpus itself,
not inferred by this reconstruction).**

## Summary of this section

Two of four primitives (`Obs_F4`, `Sat_F4`) are genuinely, non-invented-ly constructible **in form**,
grounded in the same frozen-as-shape corpus apparatus (`Δ_t`/`Sat`) — a real result, distinct from
F1/F5's flat "zero hits" (MD-049), and now corroborated by a genuine primary-source precedent for the
relation's own shape (`[DEF-15]`, M0043 — see `01a`). Both share one, single, precisely-named open
dependency, sharper than this section's own first pass found: **not `R_t`'s closure, but a
decomposition-independent body for `Sat`**, which the corpus's own text (M0132) ties directly to the
unresolved `K_t`-variant family (UE-1/UE-2). Two primitives (`Beh_F4`, `Trace_F4`) are genuinely
UNAVAILABLE, blocked by a *different*, also precisely-named obstruction: unresolved choice among
composition-rule candidates. **No semantics were invented to force a comparison.**
