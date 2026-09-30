# step188-typed-relation-and-decision-replay

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `R(p_i,p_j) in {Change,Correction,Refinement,Supersession,Contradiction}; R(3.69,3.70)=Change vs R(3.69,3.68)=Correction`, `Replay(D,t0): reconstruct K_t0,R_t0,A_t0 and ask was D admissible then (not would we decide the same today)`, `historical replay vs counterfactual replay (do(X=x)); ActualHistory != CounterfactualAnalysis` · **Aliases:** `typed version relations and the decision-replay operation`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 188 formalizes a typed relation R(p_i,p_j) in {Change,Correction,Refinement,Supersession,Contradiction} between two versions of a proposition, worked with a Nexus-version example where R(3.69,3.70)=Change but R(3.69,3.68)=Correction if the original 3.69 assertion was itself erroneous -- 'far more valuable than simply storing versions.' Defines a decision-replay operation Replay(D,t0) that reconstructs K_t0, R_t0, A_t0 and asks whether D was admissible under those conditions, explicitly distinct from 'would we make the same decision today?'; distinguishes historical replay (uses only period-t0 data) from counterfactual replay (Replay_counterfactual(D,t0,K_t1), a Pearl-style do(X=x) causal question about what would have happened with later knowledge already available) -- ActualHistory != CounterfactualAnalysis, both representable in KnowledgeOS but must be typed differently."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1388 §"R(p_i,p_j) ∈ {Change,Correction,Refinement,Supersession,Contradiction}. Then: R(3.69,3.70)=Change while: R(3.69,3.68)=Correction if the original 3.69 assertion was erroneous. ... Replay(D,t_0). It reconstructs: K_{t_0} and: R_{t_0} and: A_{t_0}. ... Was D admissible under those conditions? ... not: …"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1388 §"R(p_i,p_j) ∈ {Change,Correction,Refinement,Supersession,Contradiction}. Then: R(3.69,3.70)=Change while: R(3.69,3.68)=Correction if the original 3.69 assertion was erroneous. ... Replay(D,t_0). It reconstructs: K_{t_0} and: R_{t_0} and: A_{t_0}. ... Was D admissible under those conditions? ... not: …"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1388. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1388 |
| type_signature | PRESENT | S1388 |
| invariants | PRESENT | S1388 |
| dependencies | PRESENT | S1388 |
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
- [S1388] types=[FORMALIZATION] scope=THEORY-LEVEL — "Formalizes a typed version relation R(p_i,p_j) in {Change,Correction,Refinement,Supersession,Contradiction} (worked example: R(3.69,3.70)=Change vs R(3.69,3.68)=Correction if the original assertion was erroneous). Defines Replay(D,t0) as reconstructing K_t0/R_t0/A_t0 and asking whether D was admissible then -- explicitly not 'would we decide the same today?' -- distinguishing historical replay from counterfactual replay (Replay_counterfactual(D,t0,K_t1), a Pearl-style do-calculus question about later-knowledge-already-available), with ActualHistory != CounterfactualAnalysis, both representable but never confused." (anchor: "R(p_i,p_j) ∈ {Change,Correction,Refinement,Supersession,Contradiction}. Then: R(3.69,3.70)=Change while: R(3.69,3.68)=Correction if the original 3.69 assertion was erroneous. ... Replay(D,t_0). It rec…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
