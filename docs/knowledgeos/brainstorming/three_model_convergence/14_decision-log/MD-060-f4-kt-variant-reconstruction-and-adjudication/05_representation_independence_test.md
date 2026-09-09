# MD-060 §05 — Representation-Independence Attack (Phase E)

## What is actually being tested

Since `Sat`'s own body is `NOT FOUND` for every variant (`03`), this phase cannot test whether a
concrete `Sat` semantics is invariant under representation change — there is no computable `Sat` to
test. What CAN be tested: whether the *census's own adjudication method* (§02) would remain stable
under the six listed transformations, applied to the census population itself. This is a narrower,
honest substitute for the originally-requested test, disclosed as such rather than silently
reinterpreted.

## The six required transformations, applied to the variant population

| Transformation | Corpus-defined as admissible? | Result if applied |
|---|---|---|
| **Component splitting** | 🔴 **NOT corpus-defined** — no source states which splits of a `K_t` component are semantics-preserving | dependency exposed, not invented: **admissibility itself is undefined** |
| **Component merging** | 🔴 same | same |
| **Reordering** | ⚠️ **Plausible by convention** (tuple position is presentational in every variant that uses positional notation) but never stated as a rule anywhere | **HYPOTHESIS**, not corpus-established |
| **Projection** | ⚠️ V6-meta (`01`) is the one variant that explicitly treats `K_t` as a projection of a richer object (`E_t`) — but this is stated for ONE variant only, not as a general admissible-transformation rule for the whole family | **PARTIAL** — one instance exists, not a general corpus rule |
| **Addition of semantically redundant structure** | 🔴 NOT corpus-defined | undefined |
| **Removal of non-observable structure** | 🔴 NOT corpus-defined | undefined; this is exactly V8's own `d_3=0` "neutralization, not non-existence" move (`01`), but again stated for one variant only |

## Finding

**Admissibility of representation-change is itself not corpus-defined for the `K_t` family as a
whole.** Two of the six transformations (projection, selective-zeroing/removal) have a **single,
variant-specific precedent** each (V6-meta; V8) — genuine corpus material, not invented by this
phase — but neither is stated as a general rule the whole 12-variant family is expected to obey.
**Per the authorizing prompt's own instruction ("if admissibility itself is not corpus-defined, mark
that dependency explicitly")**: this dependency is marked, not resolved. **No transformation is
invented as "admissible" to force a test through.**

## Consequence for the census's own adjudication (§02)

The `02` adjudication (V1↔V3 conditional equivalence; V2a↔V2b and V6a↔V6b structural correspondence/
refinement; the V1/V3↔V7 incompatibility) does **not** depend on any of the six transformations above
— it was built directly from each source's own stated formulas, not by transforming one
representation into another. **§02's own findings are therefore not put at risk by this section's
own negative result** — they stand independently of whether a general representation-change rule
exists.
