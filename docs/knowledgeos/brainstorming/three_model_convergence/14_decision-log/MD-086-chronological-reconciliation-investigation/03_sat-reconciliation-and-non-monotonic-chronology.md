# MD-086 §03 — `Sat` Reconciliation and the Non-Monotonic Chronology

## The three scopes, tested for a source-stated relationship

**Verified directly this phase**: `T5`'s own canonical source (`[DEF-19]`/`[DEF-20]`/`[DEF-21]`, the
same file) introduces `Sat(K,EC_t)` (contract-level) and `Sat(K_t,r)` (requirement-level) in immediate
succession, **without stating any relationship between them** — no universal quantification, no
"`Sat(K,EC)` means `∀r: Sat(K,r)`," nothing. Both are used as independent, undefined primitives. **This
closes the question the mission posed in §6 directly: the hypothesized relationship
`Sat(K,EC)⟺∀r∈Req(EC):Sat(K,r)` is not source-stated anywhere this reconstruction has read, at birth or
later.** Recorded as `UNRESOLVED`, not invented.

## The one genuine, source-stated refinement claim in the whole `Sat` family

Part V §5.6 (T21): *"Part III introduced generalized satisfaction. We now formalize it."* — this is a
direct, explicit, first-person claim of continuity between Part III's own 2-arg
`Sat:𝕂×Req→𝒮_sat` (4-value) and Part V's own 3-arg `Sat(K,r,Γ)→𝕊_sat` (5-value). **Historical: explicit.
Structural: fails** — the claimed "formalization" actually changes arity (2→3) and value-count (4→5)
without acknowledging either change (already flagged, MD-078). **Verdict: `SAME OBJECT, REFINED
(SOURCE-CLAIMED), WITH UNDISCLOSED STRUCTURAL DRIFT`** — the corpus's own explicit refinement claim is
honored as evidence of intended continuity, while the actual mathematical content is reported precisely
as *not* a clean refinement. This is the single strongest historical link in the entire `Sat` family,
and it is also the clearest instance of a claimed relationship not holding up structurally.

## Part V ↔ Part VI

Part VI's own `[Def 6.18]` reuses Part V's own 3-arg `Sat(K,r,Γ)` symbol and its own `𝕊_sat` codomain
name, with **no explicit citation of Part V**. Given no rival definition of `𝕊_sat` exists anywhere in
the corpus, this is the strongest *unwitnessed* same-object case in the family (already established,
MD-078/082). **Verdict: `RELATED OBJECT, RECONSTRUCTED` (plausibly `SAME OBJECT`, not proven by
citation)** — unchanged from prior phases, restated here for completeness of the graph.

## The non-monotonic reversion — investigated for cause, not assumed

The mission's own §6 asks whether T22's and Part 21's own reversion to the 2-arg `Sat(K,r_i)=Satisfied`
form (*after* the 3-arg form was introduced, same rewrite) is deliberate abstraction, regression,
context shift, simplification, or unresolved notational drift — and explicitly forbids deciding without
evidence.

**Checked directly**: neither T22's own worked-example text nor Part 21's own Definition 21.4 contains
any comment, aside, or justification for using the 2-arg form rather than the 3-arg form introduced
earlier in the same rewrite. No sentence anywhere says "for simplicity we use the 2-arg form here," or
"in this context Γ is fixed," or any equivalent. **The honest classification is `UNRESOLVED NOTATIONAL
DRIFT`** — none of the four offered explanations (deliberate abstraction / regression / context shift /
simplification) is textually supported over the others. This is explicitly *not* the same kind of
finding as the acceptance-policy's own `TERMINAL D` (MD-084), which rested on direct, explicit textual
disclosure of intent — here, no such disclosure exists in either direction. The corpus is silent, not
explanatory.

## Consolidated `Sat` Definition Evolution Graph

```
Sat(K,r_i) [step-023, BIRTH, gloss only]
 ├─[fiat-consumed, unexplained]──> Sat(K_t,r)∈{0,1} [T5 [00-51]]
 │                                   │
 │                                   └─[UNRESOLVED — no stated relation]──> Sat(K,EC_t) [T5 [DEF-19/20], contract-level, same file]
 │
 └─[SAME OBJECT, REFINED (source-claimed), structural drift undisclosed]──> Sat:𝕂×Req→𝒮_sat [Part III, 4-value]
                                                                               │
                                                                               └─[claimed refinement, arity+value-count both drift]──> Sat(K,r,Γ)→𝕊_sat [Part V, 5-value, 3-arg]
                                                                                                                                          │
                                                                                                                                          ├─[RELATED OBJECT, RECONSTRUCTED, unwitnessed]──> Sat(K,r,Γ)=Det_r(EvalReq(...),EC) [Part VI [Def 6.18]]
                                                                                                                                          │                                                    │
                                                                                                                                          │                                                    └─[wired via same-symbol reuse, MD-082]──> Det(K,p,EC,Γ)→Δ_p→Zero_p [Part VI §6.27-74]
                                                                                                                                          │
                                                                                                                                          └─[UNRESOLVED NOTATIONAL DRIFT — no stated cause]──> Sat(K,r_i)=Satisfied [T22, same day, REVERTS to 2-arg]
                                                                                                                                          └─[UNRESOLVED NOTATIONAL DRIFT — no stated cause]──> Sat(K,r)=Satisfied [Part 21 Def 21.4, REVERTS to 2-arg]

Sat(P,r) [Part 19 — K→P argument-TYPE change, isolated to the persistence context, no relation stated to any other form]
Sat_c(K,r;Γ) [kos12, RELATED CONSTRUCTION, no relation stated to any T21/T5 form]
Sat(K,r,E) [kos/inquiry.py, RELATED CONSTRUCTION, no relation stated to any T21/T5 form]
```

**No single canonical `Sat` is established or should be inferred.** What is newly established this
phase: the contract-level and requirement-level scopes are genuinely, confirmedly unrelated by any
source statement (not merely unexamined); the Part III→Part V link is the one place the corpus makes
an explicit refinement claim, and that claim's own structural content does not hold up cleanly; the
non-monotonic reversions are genuinely unexplained by the corpus, not merely under-investigated by this
reconstruction.
