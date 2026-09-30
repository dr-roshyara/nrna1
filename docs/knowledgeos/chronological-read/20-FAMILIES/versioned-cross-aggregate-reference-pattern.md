# versioned-cross-aggregate-reference-pattern

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Ref=(Id,Version)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope OBJECT: "Step 205's preferred cross-aggregate reference pattern -- ID-only references by default, extended to (Id,Version) pairs where historical reproducibility requires pinning a specific semantic version."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1424 §"Aggregate_A \rightarrow ID_B rather than: Aggregate_A \rightarrow MutableObject_B. ... Decision.AssessmentId. not: Decision.Assessment. This limits accidental coupling."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1424. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1424 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1424 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1424] types=[CONSTRAINT, EXAMPLE] scope=OBJECT — "Establishes the preferred cross-aggregate reference pattern: reference by ID (Decision.AssessmentId) rather than by mutable object (Decision.Assessment), limiting accidental coupling." (anchor: "Aggregate_A \rightarrow ID_B rather than: Aggregate_A \rightarrow MutableObject_B. ... Decision.AssessmentId. not: Decision.Assessment. This limits accidental coupling.")
- [S1424] types=[DEFINITION, EXTENSION] scope=OBJECT — "Refines the ID-reference pattern to include a semantic version where required (AssessmentRef, PolicyRef, ModelRef all as (Id,Version) pairs), essential for historical reproducibility." (anchor: "AssessmentRef=(AssessmentId,Version). ... PolicyRef=(PolicyId,Version). And: ModelRef=(ModelId,Version). This is essential for historical reproducibility.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
