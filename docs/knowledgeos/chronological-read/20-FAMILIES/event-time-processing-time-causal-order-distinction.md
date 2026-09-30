# event-time-processing-time-causal-order-distinction

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ArrivalOrder != CausalOrder`, `EventTime`, `ProcessingTime` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 193's distributed-systems-derived distinction between event time, processing/ingestion time, and causal order, plus the role of EventID in detecting duplicate arrivals and the requirement for explicit CausalRelation(E_i,E_j) beyond timestamp comparison."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1396 §"EventTime=08:45 while: ProcessingTime=09:00. ... ArrivalOrder\neq CausalOrder."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1396. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1396 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1396 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1396 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1396 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
A stable EventID lets the system detect duplicate event arrivals, connecting the temporal model back to the earlier identity invariant as part of transition integrity. [S1396]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1396] types=[DISTINCTION, WARNING] scope=OBJECT — "Distinguishes event time (when something happened) from processing time (when the pipeline ingested it); in distributed systems, events can arrive late, duplicated, out of order, or corrected, so ArrivalOrder must never be assumed equal to CausalOrder/EventOrder." (anchor: "EventTime=08:45 while: ProcessingTime=09:00. ... ArrivalOrder\neq CausalOrder.")
- [S1396] types=[ARGUMENT] scope=OBJECT — "A stable EventID lets the system detect duplicate event arrivals, connecting the temporal model back to the earlier identity invariant as part of transition integrity." (anchor: "EventID becomes part of transition integrity. This connects our temporal model back to the identity invariant.")
- [S1396] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "A simple timestamp ordering (T_i<T_j) is insufficient to establish causality; an explicit CausalRelation(E_i,E_j) (e.g. Observation->Assessment->Decision) is stronger and must be separately represented -- TemporalOrder != CausalOrder." (anchor: "CausalRelation(E_i,E_j). ... TemporalOrder\neq CausalOrder. Both should be represented where necessary.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
