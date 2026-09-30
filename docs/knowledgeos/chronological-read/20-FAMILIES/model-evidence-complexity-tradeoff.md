# model-evidence-complexity-tradeoff

**Scope(s):** `OBJECT` · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Bayesian Model Averaging`, `p(M_i|D) prop p(M_i)p(D|M_i)` · **Aliases:** `model evidence balances fit against complexity`
**Candidate group membership (NOT an identity claim):**
- **G0084**: [`model-evidence-complexity-tradeoff` · `model-identifiability-invariant`] — explicit agent-stated uncertainty: 'model-evidence-complexity-tradeoff' POSSIBLY relates to 'model-identifiability-invariant' (batch B0012). Note: Bayesian model-selection/model-evidence framework used to refine HSMM-activation from a manual duration-mismatch threshold into a function of duration mismatch, predictive performance, model evidence, risk, and compute; introduces Bayesian Model Averaging p(z|D)=sum_m p(z|D,M_m)p(M_m|D) as an alternative to picking one winning model, distinguishing state uncertainty p(Z|D,M) from model uncertainty p(M|D) as two separate uncertainty layers that must not be conflated; proposes maintaining a Pareto frontier of models by accuracy/cost/assurance rather than always choosing the most accurate.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0012, scope OBJECT): Bayesian model-selection/model-evidence framework used to refine HSMM-activation from a manual duration-mismatch threshold into a function of duration mismatch, predictive performance, model evidence, risk, and compute; introduces Bayesian Model Averaging p(z|D)=sum_m p(z|D,M_m)p(M_m|D) as an alternative to picking one winning model, distinguishing state uncertainty p(Z|D,M) from model uncertainty p(M|D) as two separate uncertainty layers that must not be conflated; proposes maintaining a Pareto frontier of models by accuracy/cost/assurance rather than always choosing the most accurate.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0471] §"p(M_i|D) prop p(M_i)p(D|M_i) ... model evidence balances goodness of fit against model complexity ... Does the evidence justify the additional model complexity? ... p(z|D) = sum_m p(z|D,M_m)p(M_m|D). ... State uncertainty + Model uncertainty."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0471] §"p(M_i|D) prop p(M_i)p(D|M_i) ... model evidence balances goodness of fit against model complexity ... Does the evidence justify the additional model complexity? ... p(z|D) = sum_m p(z|D,M_m)p(M_m|D). ... State uncertainty + Model uncertainty."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0471`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0471 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0471 |
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
- `[S0471]` types=[FORMALIZATION] scope=OBJECT — "Bayesian model evidence and model averaging refine the HSMM-activation criterion into a function of duration mismatch, predictive performance, model evidence, risk and compute; introduces two distinct uncertainty layers -- state uncertainty p(Z|D,M) and model uncertainty p(M|D) -- that must not be conflated into one total." (anchor: "p(M_i|D) prop p(M_i)p(D|M_i) ... model evidence balances goodness of fit against model complexity ... Does the evidence justify the additional model complexity? ... p(z|D) = sum_m p(z|D,M_m)p(M_m|D...")

## Notes for P3
- Single-row label — thin evidentiary base by construction; any relationship claims beyond this one row would be unsupported.
