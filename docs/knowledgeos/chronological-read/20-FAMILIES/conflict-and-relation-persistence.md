# conflict-and-relation-persistence

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Conflict=<Claims,Evidence,Context,Time,ResolutionStatus,Provenance>`, `r=<Type,Source,Target,Context,Time,Provenance,Status>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Formal persistence tuples for epistemic conflict and typed relations, with the DatabaseConflict != EpistemicConflict distinction.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782] §"Conflict = <Claims,Evidence,Context,Time,ResolutionStatus,Provenance> ... DatabaseConflict != EpistemicConflict ... r = <Type,Source,Target,Context,Time,Provenance,Status>"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782] §"Conflict = <Claims,Evidence,Context,Time,ResolutionStatus,Provenance> ... DatabaseConflict != EpistemicConflict ... r = <Type,Source,Target,Context,Time,Provenance,Status>"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

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
- [S2782] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "19.24-19.26: epistemic conflicts (p supported by e1, ¬p supported by e2) must be preserved via a Conflict=<Claims,Evidence,Context,Time,ResolutionStatus,Provenance> tuple with a legitimate Status(p)=Conflicted state, rather than resolved by picking one row; two contradictory database rows are not automatically an epistemic conflict (could be historical change, multiple observations/contexts, corruption, or concurrent updates) -- DatabaseConflict≠EpistemicConflict; relations are first-class semantic objects r=<Type,Source,Target,Context,Time,Provenance,Status>, since a bare source_id/target_id edge cannot specify whether A->B means supports/contradicts/depends-on/derives-from/describes/causes/supersedes." (anchor: "Conflict = <Claims,Evidence,Context,Time,ResolutionStatus,Provenance> ... DatabaseConflict != EpistemicConflict ... r = <Type,Source,Target,Context,Time,Provenance,Status>")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
