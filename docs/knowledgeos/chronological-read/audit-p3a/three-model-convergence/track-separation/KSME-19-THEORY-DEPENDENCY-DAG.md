---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-19-MATHEMATICAL-THEORY-DERIVATION-LEDGER]
derived_from: [4 KSME-19 forks; the 174914 master document's own stated dependency structure, verified not assumed]
cross_track_dependency: none
---

# KSME-19 — Theory Dependency DAG

Edges below are **verified** — each was checked against the actual content of the cited sources by a fork,
not copied from `174914`'s own claimed structure. Where a fork could not verify an edge the source claims,
it is marked `CLAIMED-UNVERIFIED`, not silently included as if confirmed.

```
D1 (distinction ~_d) ──derives-from──> [KSME-08 R_eq/R_ord split]  (verified: DERIVED-BY-RECONSTRUCTION)
D1 ──feeds──> D2 (δ reflection/injectivity)                        (verified: D2 cites D1's sim_ρ⊆sim_d)
D2 ──feeds──> D3 (m* lower bound)                                  (verified: D3's polarity encoding uses D2's δ)
D3 ──answered-empirically-by──> KR-CONTR-FDE-2026-09                (verified: Fork 1, direct grep + read)

D4 (EVal sufficiency) ──prerequisite-for──> D6 (EVal aggregation)   (CLAIMED-UNVERIFIED — D6 not searched this pass)
D4 ──prerequisite-for──> D8 (EVal→Determination map)                (CLAIMED-UNVERIFIED — D8 not found)
D5 (Provenance vs Warrant) ──feeds──> D7 (Determination semantics)  (CLAIMED-UNVERIFIED — both tier-limited, secondhand)
D7 ──feeds──> D8                                                    (CLAIMED-UNVERIFIED)
D8 ──feeds──> D9 (Factivity)                                        (CLAIMED-UNVERIFIED)

D9 ──prerequisite-for──> D10 (Gap Δ(Q,Γ,K))                         (CLAIMED-UNVERIFIED — 174914's own §718 states this, not independently checked)
D10 ──feeds──> D11 (Determination ontology)                         (CLAIMED-UNVERIFIED)
D11 ──feeds──> D12 (Decision boundary chain)                        (CLAIMED-UNVERIFIED)

D13 (operation identity, O_core) ──partially-verified-in──> theory-part-04 §4.30–4.34   (verified: Fork 3, PARTIAL)
D13 ──prerequisite-for──> D14 (δ:K×O×Γ⇀K)                            (verified: same operation vocabulary)
D14 ──partially-verified-in──> theory-part-04 §4.7, §4.33            (verified: Fork 3 — notational variant, RETRACT only)
D14 ──prerequisite-for──> D15 (lifecycle states)                     (verified: RETRACT-vs-Retracted overlap)
D13 ──prerequisite-for──> D16 (Contr_Γ relational properties)        (CLAIMED-UNVERIFIED — D16 unchanged from KSME-12/16/17)
D16 ──prerequisite-for──> D17 (Scope(Contr,Γ), ISOLATE ternary)      (verified: symbol COLLISION found with theory-part-04's binary ISOLATE — not a dependency confirmation, a warning)
D13 ──prerequisite-for──> D18 (operation composition ∘)              (verified: Fork 3 — theory-part-04 §4.40–4.42 confirms non-commutativity directly)

D14+D16 ──prerequisite-for──> D19 (contextual observational equivalence)   (CLAIMED-UNVERIFIED — not found)
D19 ──prerequisite-for──> D20 (congruence F∘T̂=T∘F)                   (verified only in this investigation's own BSE construction, KSME-15/17 — NOT a corpus derivation of D20)
D20 ──prerequisite-for──> D21 (Adequate(K,Q,Γ))                       (verified: predecessor 20260902-004631's Adequate(K_t,EC_t) uses different notation, explicitly unproven)
D21 ──prerequisite-for──> D22 (executable adequacy)                   (verified: named prior test CLOSURE-5 FAILED — a real negative data point, not a proof of D22)
D22 ──prerequisite-for──> D23 (complexity)                            (verified only negatively: a prior invalid inference REJECTED, no proof supplied)

D1+D3+D13 ──prerequisite-for──> D25 (candidate architecture space 𝒜)  (verified: Fork 4 found source's own self-disclosed circularity, corroborating KSME-18's ABK-1 finding independently)
D25 ──prerequisite-for──> D26 (minimality, 7 notions)                 (verified: source's own admission the 4-candidate experiment is insufficient)
D26 ──prerequisite-for──> D27 (Kernel reduction K*=argmin)             (verified: source's own admission "should be a result, not an assumption")
D24 (determinism) ──independent-of-chain──> [no verified edge found to any other D-item]
```

## Structural findings from the verified DAG

- **The chain D1→D2→D3 is the only fully source-executed sub-chain** (three dedicated files exist:
  `175313`, `180019`, `180021`). Every other edge downstream of D4 is either `CLAIMED-UNVERIFIED` (the
  master document's own stated structure, not independently checked this pass) or verified only as a
  **partial, cross-directory match** (D13/D14/D18 via `theory-part-04`).
- **No cycle found.** The DAG is acyclic as far as verified; `174914`'s own claimed structure was not
  fully checked for cycles beyond the edges above (D4–D12 chain, D19–D20 chain not independently traced
  for cycles — recorded as `NOT-YET-TRAVERSED`, not asserted acyclic).
- **D16/D17 carry a warning, not a confirmation**: the `ISOLATE` symbol collision (ternary vs. binary)
  means any future work must NOT treat `theory-part-04`'s `ISOLATE` as a partial derivation of D17 — it is
  a different operation under the same name (Symbol Identity discipline applied).
- **D20's only "proof" is this investigation's own BSE construction** (KSME-15/17), which is
  `PROVED-IN-TESTED-SCOPE` for synthetic regimes only — this is methodology developed by the investigation
  itself, not a corpus-sourced derivation, and must never be cited as "the corpus proves D20."
