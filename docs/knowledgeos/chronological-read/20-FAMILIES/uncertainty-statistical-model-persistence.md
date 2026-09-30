# uncertainty-statistical-model-persistence

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Prediction=<Input,Output,ModelId,...>`, `U=<Type,Quantity,Scale,...>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Persistence tuples for uncertainty, statistical estimates and model predictions, with the EqualOutput != EqualModelMeaning distinction.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782] §"U = <Type,Quantity,Scale,Model,Procedure,Assumptions,Population,Time,Calibration,Provenance> ... Uncertainty != SingleNumber ... EqualOutput != EqualModelMeaning"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782] §"U = <Type,Quantity,Scale,Model,Procedure,Assumptions,Population,Time,Calibration,Provenance> ... Uncertainty != SingleNumber ... EqualOutput != EqualModelMeaning"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2782 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2782 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2782] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "19.36-19.40: uncertainty must not be stored as a bare confidence number; proposes U=<Type,Quantity,Scale,Model,Procedure,Assumptions,Population,Time,Calibration,Provenance> across types (probability, interval, variance, SE, posterior, prediction interval, qualitative, ambiguity, incomplete knowledge) -- Uncertainty≠SingleNumber; a statistical estimate theta-hat needs <Estimand,Estimator,Estimate,Population,Sample,SamplingDesign,Model,Assumptions,Uncertainty,Procedure,Version,Provenance> since a bare number like 4.72 does not specify mean/median/coefficient/odds-ratio/hazard-ratio/effect/probability (NumericValue≠StatisticalMeaning); a model prediction y=f-hat(x) needs Prediction=<Input,Output,ModelId,ModelVersion,ParameterSet,Assumptions,Time,Contract,Uncertainty,Provenance>; two models M1≠M2 giving numerically equal outputs (0.73=0.73) need not share assumptions/training/calibration/causal-interpretation/uncertainty/validity-scope, so EqualOutput≠EqualModelMeaning." (anchor: "U = <Type,Quantity,Scale,Model,Procedure,Assumptions,Population,Time,Calibration,Provenance> ... Uncertainty != SingleNumber ... EqualOutput != EqualModelMeaning")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
