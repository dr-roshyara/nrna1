# uncertainty-statistical-model-based-reasoning-part21

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** M,P |- q, Uncertainty(q) = f(...) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Explicit uncertainty-propagation function, statistical-reasoning preservation tuple, and model-relative (plural-model) reasoning within Part XXI's reasoning theory.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2785 §"Uncertainty(q) = f(Uncertainty(Premises), Model, Dependencies, Rule, Assumptions). ... 0.8 x 0.7 is meaningful only under appropriate probabilistic semantics. ... Estimate != Truth. ... StatisticalInference != DeductiveProof. ... M1,P |- q [but] M2,P not|- q [is] model-relative reasoning."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2785 §"Uncertainty(q) = f(Uncertainty(Premises), Model, Dependencies, Rule, Assumptions). ... 0.8 x 0.7 is meaningful only under appropriate probabilistic semantics. ... Estimate != Truth. ... StatisticalInference != DeductiveProof. ... M1,P |- q [but] M2,P not|- q [is] model-relative reasoning."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2785. Candidate lifecycle: ACTIVE.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2785 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2785 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2785] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "21.35-21.38: forbids naive multiplication of confidence numbers -- Uncertainty(q)=f(Uncertainty(Premises),Model,Dependencies,Rule,Assumptions) must be explicitly defined, so 0.8x0.7 is meaningful only under an appropriate probabilistic semantics; statistical reasoning must preserve <Estimand,Population,SamplingDesign,Estimator,Model,Assumptions,Procedure,Estimate,Uncertainty>, never reduced to a bare number, since Estimate≠Truth and StatisticalInference≠DeductiveProof; model-based reasoning M,P⊢q must preserve model M=<Variables,Structure,Parameters,Assumptions,Semantics,Scope,Version>, so M1≠M2 can legitimately give M1,P⊢q but M2,P⊬q (model-relative, not contradictory); model plurality M={M1..Mn} can yield ModelConflict(q,M) when M1⊢q and M2⊢¬q, and the system must not auto-select the model that produced the preferred conclusion -- model selection requires its own contract." (anchor: "Uncertainty(q) = f(Uncertainty(Premises), Model, Dependencies, Rule, Assumptions). ... 0.8 x 0.7 is meaningful only under appropriate probabilistic semantics. ... Estimate != Truth. ... StatisticalInference != DeductiveProof. ... M1,P |- q [but] M2,P not|- q [is] model-relative reasoning.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
