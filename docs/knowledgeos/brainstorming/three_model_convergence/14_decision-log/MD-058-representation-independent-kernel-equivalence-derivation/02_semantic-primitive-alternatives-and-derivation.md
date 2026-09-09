# MD-058 §02 — Semantic Primitive Alternatives and Derivation of the Weakest Relation

## Candidate semantic primitives (§4 of the authorizing prompt)

| Primitive | Type | Source justification | R1 (repr.-indep.)? | R2 (decomp.-indep.)? | Status |
|---|---|---|---|---|---|
| **Behaviour `Beh(K)`** | subset of an outcome/atom universe | F3's own `Reach(Ops(K))` construction (MD-050) — the only source-grounded instance anywhere in this reconstruction | PARTIAL — depends on the atom universe itself being representation-neutral (tested §03) | satisfied *for F3 specifically*; unavailable for any candidate without an equivalent construction | **CORPUS-DERIVED, F3-only** |
| **Observation `Obs_{Q,𝒪}(K)`** | function `(Q×𝒪) → Value`, or its induced kernel | MD-057 Cluster 1's CLOSURE-4 formula (`K₁≡_sem^{Q,Γ,𝒪}K₂` iff determinations match) | potentially strong, IF `Q,𝒪` are fixed representation-neutrally — **not currently guaranteed** (R10 open) | satisfied by construction — `Q,𝒪` are external, not internal decomposition | **CORPUS-DERIVED, parameter-open** |
| **Trace `Tr(K,n)`** | finite behaviour trace | `MinKer`'s own stated primitive (`Tr_K(s,n)`) | unknown — never instantiated | unknown | **SOURCE-DEFINED IN FORMULA ONLY** — zero instantiations corpus-wide (MD-049) |
| **Satisfaction `Sat(K,C)`** | boolean/graded, over a constraint registry `C` | `MinKer`'s own `⊨` | inherits `C`'s own status | inherits `C`'s own status | **NOT SPECIFIED** (R8) — `C_KOS` itself unresolved |
| **Capability set `{c₁,…,cₙ}`** | a labeled set | the corpus's own most common informal shorthand | **VIOLATES R1/R2 as primary basis** | **VIOLATES R2 directly** | **DISQUALIFIED as PRIMARY basis** — may remain a representation-level artifact, never the semantic ground |

## Derivation

Given **R1** (no representation-dependence) and **R2** (no decomposition-dependence), the capability-
set primitive is disqualified as the semantic ground (it fails R2 by construction — comparing sets of
labeled capabilities is exactly the "arbitrary decomposition" the corpus's own rule forbids). Of the
remaining four:

- `Sat(K,C)` inherits whatever is wrong with `C` (R8: unspecified) — cannot ground anything until `C`
  exists. **Excluded pending R8.**
- `Tr(K,n)` has a stated type but zero instances anywhere in the corpus for any candidate — using it
  as the primitive basis would require inventing the first instantiation from nothing, which this
  phase's own hard boundary forbids (§00 §7). **Excluded — DEFINABLE IN FORMULA ONLY.**
- `Beh(K)` is available for exactly one candidate (F3). As a general primitive it is not currently
  available corpus-wide.
- `Obs_{Q,𝒪}(K)` is the only primitive that is (a) formally stated with an actual interpretation, (b)
  execution-tested at least once (CLOSURE-4's own tester), and (c) satisfies R2 by its very shape —
  querying external observations, never internal structure.

**Derived choice — `Sem(K) := Obs_{Q,𝒪}(K)`, and `K₁ ≈_{Q,𝒪} K₂ :⟺ Obs_{Q,𝒪}(K₁) = Obs_{Q,𝒪}(K₂)`.**

This is a **NECESSARY CONSEQUENCE**, not a preference: given R1/R2/R4 and the corpus's own current
inventory of primitives, `Obs_{Q,𝒪}`-equality is the only candidate ground satisfying every stated
requirement simultaneously. It also gives R4 "for free" — equality of a function is automatically an
equivalence relation, so `≈_{Q,𝒪}` is reflexive/symmetric/transitive by construction, without further
proof.

**What this derivation does NOT do**: it does not adopt CLOSURE-4. CLOSURE-4 is one *instance* of this
now-derived general shape, with a *specific*, self-labeled-candidate, unratified choice of `Q,𝒪`. The
derivation here justifies the *shape* `Obs_{Q,𝒪}`-equality mathematically, from the stated
requirements — it leaves `Q,𝒪` themselves exactly as open as R10 already found them, and it explicitly
does **not** claim this recovers `≡_sem` (R5/R6: `≡` is corpus-intended to be *stronger* than any
`≈_{Q,𝒪}`).

**Alternatives A–E, tested (§5 of the authorizing prompt) — see `03` for the representation-
independence test proper, `06` for the full A–E comparison table.**
