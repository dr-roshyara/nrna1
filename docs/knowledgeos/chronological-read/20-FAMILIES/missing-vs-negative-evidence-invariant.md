# missing-vs-negative-evidence-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** MissingEvidence != ContradictoryEvidence · **Aliases:** absence of evidence is not evidence of absence, temporal version
**Candidate group membership (NOT an identity claim):**
- **G0082** [`missing-vs-negative-evidence-invariant` · `observed-unobserved-unknown-state-model`] — explicit agent-stated uncertainty: 'missing-vs-negative-evidence-invariant' POSSIBLY relates to 'observed-unobserved-unknown-state-model' (batch B0012). Note: HSMM missing-observation handling motivates the explicit invariant that O_t=empty must not imply P(H|O_t=empty)=0; called 'one of the most important consequences of the HSMM perspective', illustrated by runtime/repository/documentation/test/log unavailability that should not be read as negative evidence.


## Sources (how this label entered the ledger)
- **PROPOSAL**, batch `B0012`, scope `THEORY-LEVEL` (relation_to_existing: POSSIBLY:observed-unobserved-unknown-state-model): HSMM missing-observation handling motivates the explicit invariant that O_t=empty must not imply P(H|O_t=empty)=0; called 'one of the most important consequences of the HSMM perspective', illustrated by runtime/repository/documentation/test/log unavailability that should not be read as negative evidence.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0466] §"MissingEvidence != ContradictoryEvidence ... O_t=empty does not imply P(H|O_t=empty)=0. This is one of the most important consequences of the HSMM perspective."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0466. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S0466), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0466 |
| dependencies | PRESENT | S0466 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0466 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0466] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "Explicit invariant that missing observations (runtime unavailable, repository incomplete, tests missing, reviewer unavailable) must never be interpreted as negative/contradictory evidence, grounded in the book's event-sequence models for missed observations." (anchor: "MissingEvidence != ContradictoryEvidence ... O_t=empty does not imply P(H|O_t=empty)=0. This is one of the most important consequences of the HSMM perspective.")

## Notes for P3
- Agent observation: this label has only 1 recorded row(s); evidence base is thin and the classification above should be read as provisional pending further capture.
