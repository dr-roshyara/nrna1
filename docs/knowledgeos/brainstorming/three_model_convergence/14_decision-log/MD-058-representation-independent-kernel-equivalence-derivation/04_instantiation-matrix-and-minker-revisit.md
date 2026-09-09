# MD-058 §04 — F1–F6/K0 Instantiation Matrix, and the MinKer Revisit

## Instantiation matrix (§7–8 of the authorizing prompt)

For each candidate: is `Sem(K) = Obs_{Q,𝒪}(K)` (or any of B–E from `02`/`06`) available without
inventing semantics?

| Candidate | Source-defined? | Derivable without new modelling choice? | Status |
|---|---|---|---|
| **F3** (kernel-reduction C0/C0_plus) | ✅ `Reach(Ops(K))` (MD-050) | ✅ reuses existing construction | **ESTABLISHED** |
| **F1** (frozen governance K-1, 8-primitive) | 🔴 no | 🔴 requires inventing an atom/carrier table — never attempted anywhere in the corpus (MD-049: zero hits) | **UNAVAILABLE — requires modelling choice** |
| **F4** (Model B `K_t`/`Δ_t`) | 🔴 no | 🔴 same reason | **UNAVAILABLE — requires modelling choice** |
| **F5** (C1 DDD aggregate "K-1") | 🔴 no | 🔴 same reason | **UNAVAILABLE — requires modelling choice** |
| **F6** (Model C2, single seq-2330 file) | 🔴 no | 🔴 population of one file — insufficient to reconstruct any construction, a data-scarcity obstruction distinct from F1/F4/F5's modelling-choice obstruction | **UNAVAILABLE — data-scarcity, not modelling** |
| **K0** (MD-052) | ⚠️ K0 proposes its own representation-independence test methodology, unexecuted | 🔴 running it would be new scope for this phase | **UNAVAILABLE THIS PHASE — a named, executable next step, not attempted here** |

**Pairwise comparison (§7)**: every pair involving a non-F3 candidate defaults to **UNRESOLVED** per
the ladder's own default (IDENTITY ESTABLISHED / FORMAL EQUIVALENCE ESTABLISHED / STRUCTURAL
CORRESPONDENCE / FUNCTIONAL ANALOGY / PARTIAL CORRESPONDENCE / INCOMPATIBLE / UNRESOLVED) — **15 of
15 pairs among {F1,F3,F4,F5,F6,K0}: UNRESOLVED.** The only test actually performed is F3-against-
itself under two representations (`03`), not a cross-candidate comparison. **No candidate selected;
no comparison forced.**

## MinKer revisit (§9)

1. **Is `≡_sem` (as derived here, `Obs_{Q,𝒪}`-equality) actually an equivalence relation?**
   ✅ **NECESSARY CONSEQUENCE** — equality of a function is reflexive/symmetric/transitive by
   construction (`02`).
2. **Is `⪯_cap` a preorder?** ⚠️ **OPEN/HYPOTHETICAL** — if defined via `Obs`-inclusion it would be a
   preorder by construction, but no corpus source actually defines it this way; this is a **DESIGN
   CHOICE** not yet made.
3. **Does it induce equivalence classes?** ✅ **NECESSARY CONSEQUENCE** of (1) — any equivalence
   relation partitions its domain.
4. **Is minimality defined over candidates or equivalence classes?** **MATHEMATICALLY DERIVED —
   must be over equivalence classes.** If minimality were computed over raw candidates `K∈𝔎_adm`
   directly, two `≈_{Q,𝒪}`-equivalent candidates with different representation-dependent "sizes"
   could receive different minimality verdicts for the same semantic content — directly violating
   **R1**. `MinKer` must therefore be restated as quotienting first: `MinKer(𝔠_KOS) :=
   Min_⪯sem{[K] ∈ 𝔎_adm/≈_{Q,𝒪} \| K⊨𝔠_KOS}` — a genuine, disclosed refinement of the corpus's own
   formula, not a corpus fact.
5. **Does every admissible candidate have a semantic representative?** 🔴 **NO** — per the
   instantiation matrix above, 5 of 6 do not. `MinKer` over the induced quotient is currently
   **ill-posed for the actual candidate population** — not because the formula is wrong, but because
   its inputs are missing for 5/6 candidates.
6. **Does a minimal element exist?** **UNDETERMINED** — cannot be tested with 1/6 candidates
   instantiated.
7. **Is it unique?** **UNDETERMINED**, same reason.
8. **If not unique, what does MinKer return?** **HYPOTHESIS**: a *set* of minimal equivalence
   classes, not a single `K` — consistent in shape (not merged, not treated as corroborating
   evidence — a structural analogy only, per MD-057's own provenance discipline) with the
   operation-registry commission's own independently-reached finding of six minimal sufficient
   registries, none selectable (MD-056) — a different object, same shape of non-uniqueness.
9. **Can canonicalization be justified mathematically rather than by naming/decomposition?**
   🔴 **NOT FROM WHAT IS DERIVED HERE.** Two ways out exist and neither is forced: (a) a
   mathematically-forced tie-breaking rule — none derived; (b) accept non-uniqueness and redefine
   `MinKer` as set-valued. **This is an open DESIGN CHOICE**, and it is the same DESIGN CHOICE
   GA-038 already names as missing — corroborated, not resolved, here.
