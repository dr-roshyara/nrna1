# voting-election-outcome-lifecycle-model

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0005, scope OBJECT): Off-topic PublicDigit product content: a refined voting-opportunity outcome lifecycle (Scheduled/Available/Voting-started/Blocked/Expired/Cancelled/Superseded) with a schedule-version concept and an anti-reuse authorization invariant. Not a KnowledgeOS object; recorded for completeness per corpus policy.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0170] §"A scheduled voting opportunity should reach a definitive business outcome, but 'expired' and 'cancelled' should remain distinct outcomes."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0170. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0170 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0170 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0170]` types=[PRINCIPLE/DISTINCTION] scope=OBJECT — "Refines a proposed voting-opportunity outcome model into five distinct terminal/non-terminal states (Voting-started, Expired-unused, Cancelled-explicitly, Superseded, Blocked/awaiting-resolution) rather than collapsing them into one generic 'did not start' result." (anchor: "A scheduled voting opportunity should reach a definitive business outcome, but 'expired' and 'cancelled' should remain distinct outcomes.")
- `[S0170]` types=[INVARIANT/EXTENSION] scope=OBJECT — "Introduces a schedule-version concept and an anti-reuse invariant so that authorization for one voting opportunity never automatically carries over to a replacement schedule." (anchor: "Authorization(O1) ⇏ Authorization(O2) ... a schedule version or published schedule record")

## Notes for P3
- Thin evidentiary base (2 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
