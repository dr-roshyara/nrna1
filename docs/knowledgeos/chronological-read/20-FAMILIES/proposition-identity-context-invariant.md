# proposition-identity-context-invariant

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Context(P), ID(P)=constant · **Aliases:** context invariant, identity invariant
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0034 · scope OBJECT: Paired invariants from Step 189: a proposition's identity must stay constant unless a new proposition is explicitly created, and its semantic context must be preserved or explicitly changed; grounds the formal Conflict predicate.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1390 §"ID(P)=constant unless we explicitly establish that it is a different proposition."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1390. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1390 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1390 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1390 |
| examples | PRESENT | S1390 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1390] types=[INVARIANT, DEFINITION] scope=OBJECT — "Identity invariant: if P_t and P_(t+1) are asserted to be the same proposition then ID(P_t)=ID(P_(t+1)); if identity changes, the system must represent a new proposition P' -- protects against semantic identity drift. Paired with a Context invariant (Context(P) must be preserved or explicitly changed) and listed alongside provenance-witness-per-transition, temporal-history-cannot-disappear, and correction-must-not-silently-become-change as five stronger invariant candidates than the state names themselves." (anchor: "ID(P)=constant unless we explicitly establish that it is a different proposition.")
- [S1390] types=[EXAMPLE, DISTINCTION] scope=OBJECT — "Worked example of the context invariant: 'Nexus is running version 3.69' asserted in Context1=Production and the same version asserted in Context2=Test are not contradictory, because P(C1)!=P(C2) -- classic DDD bounded-context reasoning expressed mathematically." (anchor: "P(C_1)\neq P(C_2).")

## Notes for P3
(none beyond what is captured above)
