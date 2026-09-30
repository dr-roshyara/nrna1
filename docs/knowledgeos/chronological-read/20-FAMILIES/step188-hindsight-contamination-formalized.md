# step188-hindsight-contamination-formalized

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Evaluate(D_t0|K_t0), never Evaluate(D_t0|K_t1)`, `HC(D,t0,t1)>0 when info acquired after t0 is used as though available at t0`, `Justification_then != Knowledge_now` · **Aliases:** `hindsight contamination HC formalized`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 188 formalizes the series' recurring ex-ante evaluation principle into a named quantity: hindsight contamination HC(D,t0,t1)>0 occurs when information first acquired after t0 is incorrectly used as though available at t0 (the forbidden operation e_t1 -> DecisionEvaluation_t0 without acknowledging AcquisitionTime(e)>t0). States the correct evaluation is always Evaluate(D_t0|K_t0), never Evaluate(D_t0|K_t1) -- a category error otherwise -- distinguishing Question A (was the decision justified then? J_t0=J(D_t0,K_t0,R_t0)) from Question B (what do we know now? K_t1), giving Justification_then != Knowledge_now.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1388] §"Evaluate(D_{t_0}\mid K_{t_1}). That is a category error. The correct evaluation is: Evaluate(D_{t_0}\mid K_{t_0}) ... HC(D,t_0,t_1) as hindsight contamination. ... e_{t_1} → DecisionEvaluation_{t_0} without acknowledging: AcquisitionTime(e)>t_0. ... Justification_{then} ≠ Knowledge_{now}."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1388] §"Evaluate(D_{t_0}\mid K_{t_1}). That is a category error. The correct evaluation is: Evaluate(D_{t_0}\mid K_{t_0}) ... HC(D,t_0,t_1) as hindsight contamination. ... e_{t_1} → DecisionEvaluation_{t_0} without acknowledging: AcquisitionTime(e)>t_0. ... Justification_{then} ≠ Knowledge_{now}."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1388. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1388 |
| Type signature | PRESENT | S1388 |
| Invariants | PRESENT | S1388 |
| Dependencies | PRESENT | S1388 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1388] types=[FORMALIZATION, INVARIANT] scope=THEORY-LEVEL — "Names and formalizes hindsight contamination HC(D,t0,t1)>0, occurring when evidence acquired after t0 is used as though available at t0; states the correct evaluation is always Evaluate(D_t0|K_t0), never Evaluate(D_t0|K_t1) (a category error otherwise). Distinguishes Question A (was the decision justified then? J_t0=J(D_t0,K_t0,R_t0)) from Question B (what do we know now? K_t1) -- Justification_then != Knowledge_now." (anchor: "Evaluate(D_{t_0}\mid K_{t_1}). That is a category error. The correct evaluation is: Evaluate(D_{t_0}\mid K_{t_0}) ... HC(D,t_0,t_1) as hindsight contamination. ... e_{t_1} → DecisionEvaluation_{t_0} without acknowledging: AcquisitionTime(e)>t_0. ... Justification_{then} ≠ Knowledge_{now}.")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. Single-row label: the evidentiary base is thin by construction (one contribution) — treat every dimension marked NOT-EVIDENCED-IN-CAPTURE above as simply unobserved in this capture, not as absent from the underlying idea.
