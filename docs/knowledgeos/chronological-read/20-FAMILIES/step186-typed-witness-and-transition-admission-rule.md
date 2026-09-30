# step186-typed-witness-and-transition-admission-rule

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Req(r) subseteq Witness(r) <=> TransitionAllowed(r), W = (W_E,W_T,W_A,W_G); different transition types require different witness subsets · **Aliases:** typed, transition-specific witness requirement; deterministic admission rule
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Refines the witness invariant: KS_i --(E,S,T,A,G)--> KS_j (evidence, semantic relationship, temporal qualification, authority, governance rule), with the witness itself typed W=(W_E,W_T,W_A,W_G), noting not every transition needs every component (Observation needs W_E+W_T; Correction needs W_E+W_T+Identity; Determination needs W_E+W_A+Rule; Governance Decision needs W_A+W_G+DecisionRecord; Recommendation needs W_E+InferenceModel) -- avoiding an unnecessarily rigid universal witness rule. States the candidate formal admission rule: TransitionAllowed(r) iff Req(r) subseteq Witness(r), meaning the system need not understand the whole domain, only enforce transition preconditions -- explaining why the Kernel can be small.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1381] §"W = (W_E,W_T,W_A,W_G). ... Not every transition necessarily needs every component. ... Req(r). Then: TransitionAllowed(r) ⟺ Req(r) ⊆ Witness(r). ... the system does not need to understand the entire domain. It needs to enforce the preconditions of transitions. This begins to explain why the Kernel could be small."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1381] §"W = (W_E,W_T,W_A,W_G). ... Not every transition necessarily needs every component. ... Req(r). Then: TransitionAllowed(r) ⟺ Req(r) ⊆ Witness(r). ... the system does not need to understand the entire domain. It needs to enforce the preconditions of transitions. This begins to explain why the Kernel could be small."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1381. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1381 |
| type_signature | PRESENT | S1381 |
| invariants | PRESENT | S1381 |
| dependencies | PRESENT | S1381 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1381]` types=[FORMALIZATION] scope=THEORY-LEVEL — "Refines the witness invariant into typed, transition-specific components W=(W_E,W_T,W_A,W_G) with different transition types requiring different subsets (Observation: W_E+W_T; Correction: W_E+W_T+Identity; Determination: W_E+W_A+Rule; Governance Decision: W_A+W_G+DecisionRecord; Recommendation: W_E+InferenceModel), avoiding an unnecessarily rigid universal requirement. States the candidate formal admission rule TransitionAllowed(r) iff Req(r) subseteq Witness(r), meaning the system need only enforce transition preconditions rather than understand the whole domain, explaining why the Kernel can remain small." (anchor: "W = (W_E,W_T,W_A,W_G). ... Not every transition necessarily needs every component. ... Req(r). Then: TransitionAllowed(r) ⟺ Req(r) ⊆ Witness(r). ... the system does not need to understand the entire domain. It needs to enforce the preconditions of transitions. This begins to explain why the Kernel could be small.")

## Notes for P3
- Thin evidentiary base (1 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
