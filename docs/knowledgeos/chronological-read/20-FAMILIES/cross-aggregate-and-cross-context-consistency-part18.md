# cross-aggregate-and-cross-context-consistency-part18

**Scope(s):** CROSS-OBJECT · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `LocalInvariant != CrossAggregatePolicy` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope CROSS-OBJECT): Cross-aggregate invariants require sagas/process-managers rather than atomic treatment; different bounded contexts may hold legitimately different models under shared vocabulary.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2781] §"LocalInvariant ≠ CrossAggregatePolicy ... Shared vocabulary does not imply shared semantics."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2781. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2781 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2781] types=[CONSTRAINT, DISTINCTION] scope=CROSS-OBJECT — "18.25-18.26: an invariant Inv(A,B) spanning two aggregates that cannot update atomically must be handled via domain policies/process managers/sagas/compensating actions/async validation/explicit intermediate states rather than treated as an ordinary atomic aggregate invariant (LocalInvariant≠CrossAggregatePolicy); separately, different bounded contexts may legitimately hold different models of the same vocabulary (PredictionContext's P(Y|X) vs CausalContext's P(Y|do(X))), so shared vocabulary does not imply shared semantics and the ACL must preserve the distinction." (anchor: "LocalInvariant ≠ CrossAggregatePolicy ... Shared vocabulary does not imply shared semantics.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
