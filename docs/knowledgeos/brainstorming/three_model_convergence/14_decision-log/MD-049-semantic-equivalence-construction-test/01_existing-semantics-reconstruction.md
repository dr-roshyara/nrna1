# MD-049 §1 — Existing Semantics, Reconstructed (Phase 1)

Reconstructed from the complete 10-file `KR-KERNEL-MINIMALITY-2026-09` chain (already fully read,
MD-044/045) — no new corpus content read for this step, since MD-044/045 already established this
exhaustively. Classified on the required 4-level scale.

| Object | Formula (as given by the source) | Classification |
|---|---|---|
| Candidate Kernel `K` | `K=(X_K,δ_K,Cap_K,Inv_K,Obs_K)` / `K=(Carrier,Transitions,Capabilities,Invariants,Observables)` | **SOURCE-DEFINED** (as a shape) |
| Transition system `ℳ_K` | `ℳ_K=(X_K,Σ_K,δ_K,λ_K,I_K)` | **SOURCE-DEFINED** (as a shape) |
| Trace | `Tr_K(s,n)=(o_0,...,o_n;i_0,...,i_n;δ_0,...,δ_{n-1})` | **SOURCE-DEFINED** (as a formula) |
| Observable vector `Obs` | `(State,Gap,Determination,Revision,History,InvariantStatus,Attribution,Transition)` | **SOURCE-DEFINED** (component names only) |
| Observational equivalence `≡_obs`/`≡_𝔠` | `τ_1≡_𝔠τ_2 ⟺ ∀ω∈Obs_𝔠, ω(τ_1)=ω(τ_2)` | **DERIVABLE** from Trace + Obs, once both are instantiated |
| Capability behavior `Beh_𝔠(K)` | Contract-relevant behaviors realizable by `K` | **SOURCE-DEFINED** (as a formula), **PARTIALLY SPECIFIED** in content |
| Simulation `⪯_cap`/`⊑_𝔠` | `K_1⪯_cap K_2 ⟺ Beh_𝔠(K_1)⊆Beh_𝔠(K_2)`, later strengthened to a mapping `Φ_{12}` with a forward-simulation condition | **SOURCE-DEFINED** (as a formula); the "losslessly simulated" primitive is explicitly flagged by the source itself as still informal |
| Semantic equivalence `≡_sem` | `K_1≡_sem K_2 ⟺ K_1⪯_cap K_2 ∧ K_2⪯_cap K_1` | **DERIVABLE** from `⪯_cap`, inheriting its own open status |
| Satisfaction `⊨`/`𝔠_KOS` | `K⊨𝔠_KOS` | **HYPOTHETICAL** — `𝔠_KOS` itself is never enumerated as a concrete requirement set anywhere in the chain |
| Admissible implementations `𝔎_adm` | Referenced throughout, cleanly separated from `𝔎_sat={K∈𝔎_adm:K⊨𝔠}` late in the chain | **HYPOTHETICAL** — no membership criterion ever given |
| `MinKer(𝔠_KOS)` | `Min_⪯sem{K∈𝔎_adm\|K⊨𝔠_KOS}` | **DERIVABLE**, but only as a formula over three `HYPOTHETICAL` inputs |
| `MinKer_/≡sem` | `{[K]_≡sem \| K∈MinKer(𝔠_KOS)}` | **DERIVABLE**, same caveat |
| Capability identity `c_1≡_𝔠 c_2` | Named as needed; a candidate representation `c=(I_c,O_c,Γ_c,ℐ_c)` proposed and explicitly NOT adopted | **HYPOTHETICAL** — the source's own final position (file 10) forbids instantiating this without exposing circularity |

## What this table means for the construction test

**Every object needed to run an actual semantic-equivalence test on a real candidate — `Tr_K`, `Obs`,
`Beh_𝔠`, `⪯_cap` — has a SOURCE-DEFINED *formula*, but every one of them requires a concrete
instantiation (an actual state space, an actual observable vector, an actual behavior set) that has
never been supplied for any candidate anywhere in the corpus.** The formulas are real; their inputs
are not. This is the precise, evidence-grounded starting condition for Phase 4's own attempted
construction.
