# MD-058 §03 — Representation-Independence Test (the central test, §6 of the authorizing prompt)

## Construction

Only F3 has an actual, source-grounded semantics to test against (MD-050's `Beh_𝔠(K) := Reach(Ops(K))`,
computed via atom/carrier reachability over F3's own C0 operator set — `Observe, Relate, Infer,
Qualify, Validate, Revise, Determine, Discriminate, …`, 13/14 operators). This is used as the test
object rather than inventing a fresh one, per §00 §7's own prohibition on inventing candidate
behaviour.

- **`Rep₁(K)`**: F3's C0 operator set exactly as computed in MD-050.
- **`Rep₂(K)`**: a hypothetical re-decomposition merging two operators (`Observe`, `Relate`) into one
  compound operator `ObserveRelate`, whose net atom production is defined to equal the sequential
  composition `Relate ∘ Observe` — i.e. a legitimate different decomposition of the same underlying
  transformation, differing only in naming/count/internal structure, not in net effect.

## Proposition P1 (MATHEMATICALLY DERIVED)

> If `K'` is obtained from `K` by merging two operators into one compound operator whose net atom
> production equals the sequential composition of the originals, then `Reach(Ops(K)) = Reach(Ops(K'))`,
> hence `Obs_A(K) = Obs_A(K')` for any observation set `𝒪 ⊆ A` (the atom universe).

**Proof (informal, grounded in MD-050's own `Reach` definition)**: `Reach` is defined as the atom-
closure reachable by iteratively applying the operator set to the carrier/atom table. The closure
operation depends only on which atoms become reachable, not on how many operator-steps or what names
produce them. A merge that preserves net atom production by construction leaves every reachable atom
reachable and produces no new one — the closure is therefore identical under `Rep₁` and `Rep₂`. Since
`Obs_A` is a projection of `Reach` onto `𝒪 ⊆ A`, equality of the full closure implies equality of any
such projection. ∎ (conditional on the stated premise — an explicit, disclosed assumption, not a free
result).

**This is a genuine, non-circular representation-independence result**: it does not use "these are
equivalent because they contain the same capabilities" (the forbidden circular move, §6) — it uses
only the *atoms reached*, which is external to naming/decomposition.

## Counterexample C1 (COUNTEREXAMPLE, MATHEMATICALLY DERIVED)

Construct `Rep₃(K)`: a *split* of one F3 operator into two, such that an *intermediate* atom `a*` —
produced internally by `Rep₁`'s compound step but never itself exposed as a terminal reachable atom
under `Rep₁`'s own closure — becomes externally reachable/observable under `Rep₃`'s split (the split
exposes a genuine internal state `Rep₁` never externalizes).

If `𝒪` happens to include `a*` (nothing in the corpus currently forbids this — `𝒪_K` is not closed,
R10), then `Obs_𝒪(Rep₁) ≠ Obs_𝒪(Rep₃)` even though both compute the identical *net external*
transformation. **`≈_{Q,𝒪}` is therefore NOT automatically representation-independent** — it inherits
representation-independence only when `𝒪` itself is chosen to exclude representation-specific
intermediate atoms.

## Consequence — a new, explicit sub-requirement surfaced by the derivation itself

**`R1a` (MATHEMATICALLY DERIVED, added to the ledger)**: *For `≈_{Q,𝒪}` to satisfy R1
(representation-independence) as originally intended, `𝒪` must itself be chosen representation-
neutrally — i.e. `𝒪` must not admit intermediate/internal atoms that differ solely because of how a
candidate happens to be decomposed.* This was **not** stated anywhere in the corpus prior to this
derivation — it is a genuine new mathematical finding, produced by adversarial testing exactly as
prompted, and it explains *why* R10 (the `𝒪`/`𝒪_K` closure requirement) is not a peripheral blocker
but load-bearing for the representation-independence claim itself: without `𝒪`'s own closure/
neutrality, R1 cannot actually be verified for any candidate relation of this shape, `≈_{Q,𝒪}`
included.

**Status discipline**: P1 and C1 are both `MATHEMATICALLY DERIVED`, grounded in F3's own already-
established construction (MD-050) — no new candidate behaviour was invented; `R1a` is a
`NECESSARY CONSEQUENCE` of testing R1 against the derived relation, not a corpus fact and not
retroactively inserted into `01`'s ledger as if pre-existing.
