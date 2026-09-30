# calibration-separate-gated-executable

**Scope(s):** METHODOLOGICAL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** calibrate.py, calibration.json · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0651** [`calibration-separate-gated-executable` · `reusable-experimental-governance-pipeline`] — explicit agent-stated uncertainty: 'calibration-separate-gated-executable' POSSIBLY relates to 'reusable-experimental-governance-pipeline' (batch B0067). Note: The concrete formalization of calibration as a separate, gated executable step, repairing KR-ZOOM-OUT-01's process defect; part of the reusable-experimental-governance-pipeline extracted in S2810.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0067, scope METHODOLOGICAL): The concrete formalization of calibration as a separate, gated executable step, repairing KR-ZOOM-OUT-01's process defect; part of the reusable-experimental-governance-pipeline extracted in S2810. [relation_to_existing: POSSIBLY:reusable-experimental-governance-pipeline]

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2811 §"calibrate.py runs alone, writes data/calibration.json, and exits. Its output is inspected and the gate decided before analyse.py exists in a runnable state. KR-ZOOM-OUT-01's gate failure ... was invisible until every outcome had been seen, because calibration and analysis ran in one pass. That is a "]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2811. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
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
- **[S2811]** types=[EXTENSION] scope=METHODOLOGICAL — "Formalizes the fix for KR-ZOOM-OUT-01's calibration-and-analysis-in-one-pass process defect: calibration must run as a wholly separate executable that writes its gate result and exits, with the gate decision made and inspected before the analysis script even exists in runnable form -- directly repairing the defect that made the earlier gate failure invisible until every outcome had already been seen." (anchor: "calibrate.py runs alone, writes data/calibration.json, and exits. Its output is inspected and the gate decided before analyse.py exists in a runnable state. KR-ZOOM-OUT-01's gate failure ... was invisible until every outcome had been seen, because calibration and analysis ran in one pass. That is a ")

## Notes for P3
- Own observation: only 1 row(s) touch this label — a thin evidentiary base; treat any generalization from it with caution.
- Own observation: completeness is thin — only no dimension is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
