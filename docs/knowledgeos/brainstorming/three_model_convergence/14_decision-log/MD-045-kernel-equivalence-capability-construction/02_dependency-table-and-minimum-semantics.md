# MD-045 §2 — Updated Dependency Table (Phase A) and Minimum Additional Semantics (Phase B)

## Updated dependency table (supersedes MD-044's own table in precision, not in conclusion)

| Symbol / concept | Status | Classification |
|---|---|---|
| `𝔎_adm` (admissible implementations, raw) | Named as a set separate from `𝔎_sat`; own membership criterion still never given | **NECESSARY BUT UNSPECIFIED** — the K_adm/K_sat split (file 6) is a genuine improvement, but it fixes only a naming circularity, not the content gap |
| `𝔎_sat = {K∈𝔎_adm : K⊨𝔠}` | Formally defined in terms of `𝔎_adm` and `⊨` | **DERIVED**, but inherits both of `𝔎_adm`'s and `⊨`'s own unspecified status |
| `𝔠_KOS` / `⊨` | Still never enumerated as a concrete requirement set anywhere in the 10-file chain | **NECESSARY BUT UNSPECIFIED** |
| `ℳ_K = (X_K,Σ_K,δ_K,λ_K,I_K)` (transition system) | A real, more rigorous shape than the earlier bare `Tr_K` — proposed but never instantiated for any real candidate | **SOURCE-GROUNDED SHAPE, UNINSTANTIATED** |
| `Tr_K(s,n)`, `Obs_𝔠`, `≡_obs`, `≡_𝔠` | Formally defined as formulas over `ℳ_K`/contract-observable quantities | **DERIVED**, contingent on `𝔠` and `ℳ_K` themselves |
| `⊑_𝔠` (contract-preserving refinement/simulation) | Formally sketched (a simulation relation with the standard forward condition) — file 8 further requires it to route through `Φ_{12}`/a simulation mapping, not bare trace equality | **SOURCE-GROUNDED SHAPE, UNINSTANTIATED** — no concrete `Φ` or relation `R` constructed for any pair of implementations |
| `≡_sem`, `⪯_cap`/`≡_cap` | Formally defined, shown to form a preorder (not necessarily antisymmetric) | **DERIVED**, same caveat |
| `𝒞_sem` (semantic capability, as an object) / capability identity `c_1≡_𝔠 c_2` | **This is the chain's own central open question**, not merely unspecified — see `03_...md` | **NECESSARY BUT UNSPECIFIED, AND EXPLICITLY FLAGGED BY THE SOURCE AS NOT DERIVABLE WITHOUT A NEW MODELLING CHOICE** |
| `ℬ` (capability basis) | Explicitly deferred until capability identity is resolved (chain's own stated dependency order: identity → equivalence → basis) | **BLOCKED ON THE ABOVE** |
| `𝔎_adm^{-c}` (counterfactual removal) | Formally defined, addresses the "operator removal ≠ capability removal" problem | **SOURCE-GROUNDED**, but its own domain (`𝔎_adm`) is unspecified |
| `Irred_sem(c\|𝔠)` (universal irreducibility) | Formally defined (a genuine universal-quantifier statement, improved from the earlier bare witness form) | **DERIVED**, chained to every unresolved item above |
| `ρ:𝒞_KOS→ℬ_responsibility` (DDD responsibility projection) | Formally proposed, with an explicit conservation principle (`Cap_KOS` before/after a relocation stays fixed even as `Cap_Kernel` changes) | **SOURCE-GROUNDED SHAPE, UNINSTANTIATED** |
| `KR-KERNEL-EQUIVALENCE-CAPABILITY-PROOF-2026-09` (the named next artifact) | Named repeatedly across files 8–10 as the deliverable that would instantiate the above | **CONFIRMED NEVER PRODUCED** anywhere in the corpus (direct search) |

## Phase B — is anything derivable without a new modelling choice?

**Yes, three real improvements are logically forced by material already in the chain, and are used
as such**: (1) the `𝔎_adm`/`𝔎_sat` split (removes a circularity, no new assumption); (2)
`𝔎_adm^{-c}` counterfactual removal (a direct, forced consequence of the "operator removal ≠
capability removal" finding, itself evidenced by the chain's own worked example); (3) the `Cap_Kernel
≠ Cap_System` conservation principle (a direct, forced consequence of the DDD responsibility-mapping
argument already given).

**No, the one component this construction study would actually need to proceed — a granularity-
independent account of capability identity (`𝒞_sem`, `c_1≡_𝔠 c_2`) — is NOT derivable from anything
already in the chain.** The chain's own text is explicit that this requires a **new modelling
choice** (how to represent an individual capability, what counts as "the same" capability across
decompositions), not a logically forced consequence of existing definitions. Per file 9's own words:
introducing a specific representation "too early" risks "replacing the old '13 operators are
primitives' problem with a subtler version." **This is exactly the boundary the Hard Stop clause
describes** — see `03_...md`.
