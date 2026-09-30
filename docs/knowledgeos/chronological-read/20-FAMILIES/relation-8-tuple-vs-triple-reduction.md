# relation-8-tuple-vs-triple-reduction

**Scope(s):** OBJECT · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `r=(E1,E2,T,R,Q,E,Σ,τ)`, `ℛ⊆𝒜×𝒜×RelationType`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0042, scope OBJECT: The corpus's own relation model is an 8-field n-ary relation (4 independent corpus files, band A pre-programme); the ratified/claimed theory reduces it to a bare binary triple, discarding 5 fields (E,Q,R,Σ,τ) and the n-arity with no recorded justification (C-03). Consequence: a contradiction claim ("A1 contradicts A2") cannot itself be evidenced, dated, superseded or contested under the triple.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1718 §"UL-7 (`DERIVED`, HIGH) — `ℛ` edges are not modelled as anything."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1748. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1748), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1718, S1733, S1736, S1745 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1736 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1724, S1736, S1743, S1748 |
| open_questions | PRESENT | S1745 |

## Rationale
- [S1718] (LIMITATION, ARGUMENT): UL-7: ℛ edges are bare triples with no identity, provenance, status, or time in DDD terms — neither Entities nor Value Objects with a lifecycle — yet the exp_congruence experiment shows ℛ_der is exactly the component that makes the state sufficient, making the most load-bearing element of the model the one with no DDD classification at all.
- [S1733] (ARGUMENT): Divergence 2: four independent corpus files define ℛ as an 8-tuple r=(E1,E2,T,R,Q,E,Σ,τ), explicitly n-ary ('a semantic connection between two or more entities'); the claimed theory reduces this to a binary triple, discarding R, Q, E, Σ, τ with no recorded justification, so a contradiction claim cannot be evidenced, dated, superseded or contested under the claimed model.
- [S1736] (ARGUMENT, COUNTEREXAMPLE): Constructed circularity test: encoding 'A1 contradicts A2' as P=(E,D,V) with E=A1,D='contradicts',V=A2 requires V_D('contradicts') to be the set of ALL assertions, producing V_D→𝒫→Assertion→𝒜→V_D, a genuine circularity; ℛ is therefore a primitive that cannot be reduced to 𝒜 — three independent routes (this construction, Q14's own text, and Step 262.15's open sub-test) reach the same conclusion.
- [S1745] (ANALYSIS): Three measurable reductions are named as having done the actual damage to the theory, each with material sitting in the corpus while code implementing much of it already passes: ℛ reduced from an 8-tuple to a bare triple (5 fields + n-arity discarded); Status reduced from ten executable values to (dir,str) (6 values become inexpressible while all ten execute and pass in the same repository); DC(d) reduced from 7 components to a ratified 6 (Q, epistemic sufficiency, removed and then rediscovered as an 'open theoretical problem'). In every case the corpus was richer than the model built from it — the deficit is substantially a transcription/ratification deficit, not a discovery deficit.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1718] types=['LIMITATION', 'ARGUMENT'] scope=THEORY-LEVEL — "UL-7: ℛ edges are bare triples with no identity, provenance, status, or time in DDD terms — neither Entities nor Value Objects with a lifecycle — yet the exp_congruence experiment shows ℛ_der is exactly the component that makes the state sufficient, making the most load-bearing element of the model the one with no DDD classification at all." (anchor: "UL-7 (`DERIVED`, HIGH) — `ℛ` edges are not modelled as anything.")
- [S1724] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=OBJECT — "EXP-2: two states agreeing on every proposition and every proposition's in-assertion provenance still differ under withdraw(), because the RELATION set R (not Π's placement) carries the load-bearing information (R_der); this shows Step 265's provenance-placement verdict is orthogonal to the sufficiency question it is often cited for." (anchor: "=> OBSERVED: the two states agree on every proposition AND on every      proposition's provenance, yet withdraw() distinguishes them.")
- [S1733] types=['ARGUMENT'] scope=THEORY-LEVEL — "Divergence 2: four independent corpus files define ℛ as an 8-tuple r=(E1,E2,T,R,Q,E,Σ,τ), explicitly n-ary ('a semantic connection between two or more entities'); the claimed theory reduces this to a binary triple, discarding R, Q, E, Σ, τ with no recorded justification, so a contradiction claim cannot be evidenced, dated, superseded or contested under the claimed model." (anchor: "### 2.2 `ℛ` — the corpus's relation is an **8-tuple**, the claimed theory's is a **bare triple**")
- [S1736] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Constructed test: under the corpus's own 8-tuple r, the claim 'A1 contradicts A2' is itself evidenced/dated/contestable (E,Σ,τ are fields of r); under the canonical binary triple it is none of these, refuting ℛ-as-triple on capability, not on score — a theory of governed knowledge that cannot evidence its own contradiction claims is not adequate to its own subject." (anchor: "**Test:** *is the claim "A₁ contradicts A₂" itself evidenced, dated, contestable?*")
- [S1736] types=['ARGUMENT', 'COUNTEREXAMPLE'] scope=THEORY-LEVEL — "Constructed circularity test: encoding 'A1 contradicts A2' as P=(E,D,V) with E=A1,D='contradicts',V=A2 requires V_D('contradicts') to be the set of ALL assertions, producing V_D→𝒫→Assertion→𝒜→V_D, a genuine circularity; ℛ is therefore a primitive that cannot be reduced to 𝒜 — three independent routes (this construction, Q14's own text, and Step 262.15's open sub-test) reach the same conclusion." (anchor: "=> CIRCULAR.  **`ℛ` is a genuine primitive and cannot be folded into `𝒜`.**")
- [S1739] types=['LIMITATION'] scope=THEORY-LEVEL — "One inherited defect: Lineage=Π∘ℛ_der* is a correct composition, implemented as GovernanceLineageGraph with 47 tests, but ℛ_der is a bare triple, so lineage edges carry no evidence, no time and no epistemic status — 'A was derived from B' is an unevidenced, undated, uncontestable claim in the claimed model though the corpus's own r=(E1,E2,T,R,Q,E,Σ,τ) would carry all three; the defect is in ℛ, not in the provenance model itself." (anchor: "> **Lineage edges carry no evidence, no time and no epistemic status.**")
- [S1743] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "RUN-5 (constructed attacks, attack.py): confirms the relation-field-loss (5 of 8 fields discarded), the relation-as-assertion circularity, Σ's policy-relativity, the 3-cycle dependency graph, assertion identity instability under withdrawal, Σ's blindness to ℛ (two assertions in an explicit contradicts edge both evaluate to ('Supporting','Weak')), and that the commit operation δ is a no-op (K1 is K0 == True after an authorized, executed, historied commit)." (anchor: "**B · relation-as-assertion reduction → CIRCULAR**")
- [S1745] types=['ANALYSIS'] scope=THEORY-LEVEL — "Three measurable reductions are named as having done the actual damage to the theory, each with material sitting in the corpus while code implementing much of it already passes: ℛ reduced from an 8-tuple to a bare triple (5 fields + n-arity discarded); Status reduced from ten executable values to (dir,str) (6 values become inexpressible while all ten execute and pass in the same repository); DC(d) reduced from 7 components to a ratified 6 (Q, epistemic sufficiency, removed and then rediscovered as an 'open theoretical problem'). In every case the corpus was richer than the model built from it — the deficit is substantially a transcription/ratification deficit, not a discovery deficit." (anchor: "1. **`ℛ` reduced from an 8-tuple to a bare triple**")
- [S1745] types=['FUTURE-RESEARCH'] scope=THEORY-LEVEL — "The six smallest missing things are ranked in dependency order: (1) restore ℛ to its 8-tuple form — closes 4 gaps, invents nothing, the smallest/cheapest/highest-yield fix; (2) add D_t to K; (3) give AuthorityAct identity (genuinely 0 corpus occurrences, absent, not merely lost); (4) supply Qualify's body; (5) restore (W,Ω); (6) fix Σ's value set and str rule (exactly what Step 272 was commissioned to do). Only #3 and #4 require genuine invention; the rest require only restoration or adoption of material the corpus already established." (anchor: "| **1** | **`ℛ` restored to `(E₁,E₂,T,R,Q,E,Σ,τ)`, n-ary** | nothing | **CORPUS ESTABLISHES ×4** |")
- [S1748] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Raw execution confirms: the corpus's 8-field relation loses 5 fields (E,Q,R,Sigma,tau) under the canonical triple reduction, and 'A1 contradicts A2' is evidenced/dated/contestable under the corpus's r but not under the canonical triple." (anchor: "FIELDS DISCARDED         : ['E', 'Q', 'R', 'Sigma', 'tau']   (5 of 8)")

## Notes for P3
(none beyond what is noted above)
