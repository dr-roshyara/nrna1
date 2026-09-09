# MD-068 §02 — Theory Object Registry

Compact identity ledger. Purpose: prevent accidental conflation of same-spelled, different objects.
One row per **distinct object** (not per symbol) — several symbols below denote more than one object;
each gets its own row.

| Object ID | Symbol(s) used | Owning branch/lineage | Distinguishing feature | Related-but-distinct objects |
|---|---|---|---|---|
| `K_t`-abstract | `K_t`, `K` | Canonical (`[00-47]`) and Theory-00-21 (`[05-36]`ff) | deliberately untyped, `K_t∈𝕂` | `K_t`-Bayesian (`[00-01]`, unrelated), `A_t` (governance rename, propagation unwitnessed) |
| `A_t` | `A_t` | Zero-Lens/Structure-First branch (`[01-56]`) | governance-renamed `K_t`, non-factive attribution | `K_t`-abstract (rename target, never shown executed into canonical/Theory-00-21 text) |
| `EC_t`-v3 (4-field) | `EC_t`, `EC` | Canonical (`[00-47]`) | `EC(S_t,G_t,Q_t,C_t)` | `EC`-v4 (6-field), distinct fields, no reconciliation |
| `EC_t`-v4 (6-field) | `EC`, `EC_t` | Theory-00-21 (`[05-36]`) | `⟨Req,Rules,Scope,EvidenceRequirements,TemporalRequirements,AuthorityRequirements⟩` | `EC_t`-v3, unreconciled (GAP-002) |
| `Req(EC_t)` | `Req(EC_t)`, `I_t`, `ℛ_I`, `ℛ_t` | Canonical + Theory-00-21 | the tracked requirement-generation object | `ℛ_req(Q,Γ)` (unrelated homonym), bare `ℛ` (unrelated homonym, representation functions) |
| `ℛ_req(Q,Γ)` | `ℛ_req`, `R_req` | Contr/FDE/ABK-1 branch (`[02-45]`ff) | "Required Distinction Universe" — a set of preserved equivalence-relations | `Req(EC_t)` — **UNRELATED_HOMONYM**, tracked as `EKS-41` |
| `ℛ` (bare, representation functions) | `ℛ` | `phase_measure_theory/` step_276 (outside this traversal's own scope, found by Lane T, recorded in `EKS-41` Appendix A) | `{Serialize,Deserialize,Save,Load}` | `Req(EC_t)`, `ℛ_req` — **UNRELATED_HOMONYM**, oldest of the three (2026-08-30) |
| `r`-v1 (structured) | `r` | Canonical extension (`[00-51]`) | 7-field tuple incl. `standard` | `r`-v2, field structure not carried forward (GAP-001) |
| `r`-v2 (opaque) | `r` | Theory-00-21 (`[05-37]`) | `r∈Req`, no field structure given | `r`-v1 — bridge UNWITNESSED but functionally reconciled (see GAP-001 closure) |
| `Sat`-family (base predicate) | `Sat(K,r,...)` | spans canonical → Theory-00-21 | see Definition Registry, 7 versions | `Sat_c` (distinct), `Sat*` (distinct, this reconstruction's own artifact) |
| `Sat_c` | `Sat_c` | Sep-2 KR-SIM branch (`[00-55]`–`[00-59]`) | class-indexed, three-valued, demoted to derived projection of `Eval_c` | `Sat` base predicate — **no corpus-native bridge found**, `Sat_c` never re-engaged after `[00-59]` |
| `Sat*` | `Sat*` | This reconstruction's own artifact (MD-061, not a primary source) | never consumes `EC_t` (MD-062 finding) | `Sat`/`Sat_c` — not a corpus object at all, recorded for completeness only |
| `Eval_c` (Zero-Lens sense) | `Eval_c`, `EVal_c=(v,ρ,π)` | Zero-Lens branch (`[01-01]`) | 3-tuple (value/reason/provenance) | `Eval` (Theory-00-21 sense) — SAME_LINEAGE_AS by shared role, field-mapping UNWITNESSED |
| `Eval` (Theory-00-21 sense) | `Eval`, `Eval_c` | Theory-00-21 (`[05-38]`, `[05-41]`) | 5-tuple then 7-field vector, feeds `EvalReq`/`Sat` directly | `Eval_c` (Zero-Lens sense) — see above |
| `EvalReq` | `EvalReq` | Theory-00-21 only (`[05-41]`) | requirement-specialized `Eval` output | `App` — functionally overlapping (applicability role), no explicit bridge (GAP-003) |
| `App` | `App` | Sep-2 KR-SIM branch (`[00-55]`) | applicability gate between `Req` and `Sat` | `Req(EC_t,Γ_t)`-v4/`EvalReq` — functional role architecturally absorbed, no explicit bridge (GAP-003) |
| `Δ_t` (Sat-gap sense) | `Δ_t`, `Δ` | Canonical + Theory-00-21 | `{r∈Req(EC_t):¬Sat(K_t,r)}`, 4 versions, PROVED | `Δ_t` (transition-residue sense) — **UNRELATED_HOMONYM**, permanent |
| `Δ_t` (transition-residue sense) | `Δ_t` | Zero-taxonomy branch (`[04-27]`ff) | `Diff(K_t,K_{t+1})`, later `(Δ_t^-,Δ_t^○,Δ_t^+)` | `Δ_t` (Sat-gap sense) — **UNRELATED_HOMONYM**, permanent |
| `Zero` (canonical, `EC_t`-relative) | `Zero(K_t,EC_t)` | Canonical + Theory-00-21 | `⟺Δ_t=∅⟺K_t⊨EC_t`, bottom of Gap lattice | `ZeroLens` and `Zero_{T,Π}` below — both unrelated homonyms |
| `ZeroLens` | `ZL(K,Γ,L)`, `Zero` | Zero-Lens branch (`[01-01]`) | `→Boundary_t`, retires the `Δ=∅` reading WITHIN its own branch only | `Zero` (canonical) — **UNRELATED_HOMONYM** for practical purposes |
| `Zero_{T,Π}` | `Zero`, `Zero_{T,Π}(S;D)` | Zero-Algebra branch (`[03-16]`ff) | counterfactual elimination predicate, extensively tested, no causal link to "Adequate" (KR-BRIDGE-01) | `Zero` (canonical) — **UNRELATED_HOMONYM** |
| `Determination`/`Det` | `Det`, `Determine` | Canonical + Theory-00-21 | 5 versions, culminating in PROVED theorems (`[THM 3.1]`/`[THM 6.1]`/`[THM 6.2]`/`[THM 21.8]`) | — |
| `Decision` | `Decision` | Theory-00-21 (`[05-51]`) | never independently structured; `[THM 16.38]` proves it is NOT determined by `Determination` alone | — |

## Reading note

This registry is the authoritative disambiguation reference for MD-068 and any later phase. Any future
document that writes a bare `Sat`, `Δ_t`, `Zero`, `Eval`, `ℛ`/`ℛ_req`, or `App` without specifying
which row above it means should be treated as **ambiguous until disambiguated**, not silently resolved
to "the most recent" sense.
