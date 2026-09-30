# assertion-single-temporal-field-gap

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A=(id,P,e,c,t,Pi)`, `T_valid != T_known` · **Aliases:** `CS-2`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): The terminal Assertion type carries a single temporal field t, but the corpus separately establishes bitemporality (T_valid != T_known, Steps 185 and 025w); Step 187's own worked example (a board-chair role with a validity interval) needs a validity interval the type cannot hold, so either t is assertion-time (validity unrepresentable) or validity-time (replay loses its ordering key).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1701 §"A carries one t while the corpus establishes T_valid != T_known; Step 187's own example needs a validity interval the type cannot hold. Either t is assertion time (and validity is unrepresentable) or it is validity time (and replay loses its ordering key). The corpus establishes bitemporality and then defines a unitemporal assertion."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1701. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1701 |
| dependencies | PRESENT | S1701 |
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

- `[S1701]` types=[CONTRADICTION, LIMITATION] scope=OBJECT — "Finding CS-2: the terminal Assertion type A=(id,P,e,c,t,Pi) carries a single t field, but Steps 185 and 025w both establish bitemporality (T_valid != T_known); Step 187 §187.13's own worked example (a board-chair role valid 2026-01-01 to 2026-12-31) requires a validity interval the type structurally cannot carry, forcing an unresolved choice between t-as-assertion-time (losing validity representability) or t-as-validity-time (losing replay's ordering key)." (anchor: "A carries one t while the corpus establishes T_valid != T_known; Step 187's own example needs a validity interval the type cannot hold. Either t is assertion time (and validity is unrepresentable) or it is validity time (and replay loses its ordering key). The corpus establishes bitemporality and then defines a unitemporal assertion.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
