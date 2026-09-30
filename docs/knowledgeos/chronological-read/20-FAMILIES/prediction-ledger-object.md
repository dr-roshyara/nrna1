# prediction-ledger-object

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** ForecastCalibration, PredictionLedger · **Aliases:** Epistemic observability
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Proposed observability system recording prediction/probability/timestamp/model/assumptions/horizon/outcome/error/calibration/model_update for every prediction, enabling agent-level calibration profiles (distinguishing 'sounds intelligent' from 'empirically calibrated' agents); paired with 'Epistemic observability' (belief drift, prediction error, calibration, uncertainty, contradictions, model degradation, evidence quality) as a parallel to conventional system observability (CPU/memory/latency/errors)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0453 §"the Bayesian brain as continuously comparing Prediction vs. Observation and using the difference as prediction error ... connects this concept to Kalman filtering: estimate -> predict -> observe -> update -> predict again."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0453 §"the Bayesian brain as continuously comparing Prediction vs. Observation and using the difference as prediction error ... connects this concept to Kalman filtering: estimate -> predict -> observe -> update -> predict again."]
- CANDIDATE-FORMAL-BIRTH: [S0453 §"the Bayesian brain as continuously comparing Prediction vs. Observation and using the difference as prediction error ... connects this concept to Kalman filtering: estimate -> predict -> observe -> update -> predict again."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0453. Candidate lifecycle: DORMANT. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used — all three rows trace to a single source document (S0453), with no later row in this label's own family continuing or applying it — not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0453 (×3) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0453 (×3) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0453 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; rationale_truncated_count = 0, so no further rationale-bearing rows exist beyond what's captured). Note: the third row's own content ("a prediction is not just output. It is a future evidence contract") functions as a motivating rationale for the PredictionLedger design, but was not mechanically classified into the rationale_evidence bucket, so it is reported only under "All rows" below.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S0453] types=[FORMALIZATION, CONCEPT] scope=OBJECT — "Establishes Prediction Error as first-class via Prediction/PredictionOutcome/PredictionAssessment objects, driving a Model->Prediction->Observe->Prediction Error->Evidence->Belief Update->Model learning loop, contrasted with a naive Document->embedding->retrieval pipeline." (anchor: "the Bayesian brain as continuously comparing Prediction vs. Observation ...")
- [S0453] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "ForecastCalibration capability (Prediction->Outcome->Calibration->Model assessment) built on Tetlock/superforecaster Brier scoring, distinguishing 'well calibrated' from 'overconfident' agents (worked example: Agent A 80% predictions occur 82% of the time = well calibrated; Agent B 80% occur 45% of the time = overconfident); proposed for future AI agent qualification, distinguishing 'sounds intelligent' from 'empirically calibrated.'" (anchor: "Were the probabilities calibrated? ...")
- [S0453] types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Frames a prediction as a 'future evidence contract' (I expect X with probability P by time T under conditions C) that reality later evaluates; motivates a PredictionLedger recording prediction/probability/timestamp/model/assumptions/horizon/outcome/error/calibration/model_update, distinguished as 'Epistemic observability' parallel to but distinct from conventional system observability." (anchor: "Every prediction is an opportunity to generate future evidence about the model that produced it. ...")

## Notes for P3
- Single-source label (all 3 rows from S0453, one document, batch B0012) — thin in provenance, no independent corroboration found within this label's own capture.
- The first row is shared with another working_label (`belief-state-object`) in its `labels` list, suggesting the two concepts (belief state and prediction ledger) were captured together in the same document passage — no group_id currently links them, so this connection is visible only in the row-level co-labeling, not structurally. Flagging for P3 in case cross-label reconciliation should note this adjacency.
- The concept is proposed at a fairly mature design level for a single-source, DORMANT label (full field list for the ledger, a named calibration scoring method, a named agent-qualification use case) — worth checking during reconciliation whether any later batch operationalized or extended it under a different label.
