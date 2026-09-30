# investigation-terminal-states

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** InvestigationStatus · **Aliases:** four terminal investigation states
**Candidate group membership (NOT an identity claim):**
- G0113: [`decision-readiness-stop-condition-model` · `investigation-terminal-states`] — explicit agent-stated uncertainty: 'investigation-terminal-states' POSSIBLY relates to 'decision-readiness-stop-condition-model' (batch B0020). Note: Replacement for boolean ShouldStop: Ready | Continue | ForcedStop | Unresolvable, correcting the conflation of forced stopping with decision-readiness.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0020, scope OBJECT): Replacement for boolean ShouldStop: Ready | Continue | ForcedStop | Unresolvable, correcting the conflation of forced stopping with decision-readiness. _[relation_to_existing: POSSIBLY:decision-readiness-stop-condition-model]_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0804] §"Status = ForcedStopNotReady"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0804] §"InvestigationStatus \in \{Ready,\ Continue,\ ForcedStop,\ Unresolvable\}"
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0804. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S0804 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0804 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S0804] types=['CORRECTION', 'DISTINCTION'] scope=OBJECT — "Urgency must not produce false readiness: 'Not sufficient + Urgent = STOP' is operationally fine but must produce Status = ForcedStopNotReady, not DecisionReady = True." (anchor: "Status = ForcedStopNotReady")
- [S0804] types=['CONCEPT', 'EXTENSION'] scope=OBJECT — "Recommends replacing the boolean ShouldStop with four terminal InvestigationStatus values: Decision Ready (evidence/understanding satisfy decision criteria), Continue Investigation (more investigation warranted), Forced Stop — Not Ready (must stop but readiness not achieved), Unresolvable (required knowledge cannot currently be obtained/resolved)." (anchor: "InvestigationStatus \in \{Ready,\ Continue,\ ForcedStop,\ Unresolvable\}")

## Notes for P3
- Very thin evidentiary base (row_count=2) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
