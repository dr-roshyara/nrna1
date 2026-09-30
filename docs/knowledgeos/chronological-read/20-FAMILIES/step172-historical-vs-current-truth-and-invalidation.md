# step172-historical-vs-current-truth-and-invalidation

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** D1--basedOn-->K1; K1--invalidatedBy-->E2, HistoricalTruth != CurrentTruth, Justified(D1,t1)=true while CurrentValidity(K1,t2)=false, Superseded vs Invalidated · **Aliases:** Experiment F consequences, system must not rewrite history
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 172's Experiment F consequences: a decision D1 based on K1 at t1 remains Justified(D1,t1)=true even after K1 is superseded by K2 at t2 (CurrentValidity(K1,t2)=false) -- CurrentKnowledge != HistoricalKnowledge and Supersession != Deletion, 'one of the strongest architectural consequences obtained from the Chapter 4 lens'. Distinguishes two cases: Case 1 new evidence changes the conclusion (K1->K2, K1 was reasonable but incomplete) vs Case 2 evidence shows K1 was actually invalid (Invalidate(K1)) -- these are not identical and a knowledge lifecycle needs the conceptual ability to express both Superseded and Invalidated. States the system must not rewrite history: if D1 was historically based on K1, it must never be silently re-recorded as based on K2 after K1 is invalidated ('that would create a false history') -- instead the true relationships D1--basedOn-->K1 and K1--invalidatedBy-->E2 are both preserved, separating HistoricalTruth (what was actually recorded/decided at that time) from CurrentTruth (what we believe now given current evidence), both of which a mature knowledge system must support.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1365] §"Justified(D_1,t_1) may remain: true while: CurrentValidity(K_1,t_2)=false. ... CurrentKnowledge ≠ HistoricalKnowledge. And: Supersession ≠ Deletion. This is one of the strongest architectural consequences we have obtained from the Chapter 4 lens."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1365. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1365 |
| invariants | PRESENT | S1365 |
| dependencies | PRESENT | S1365 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1365 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1365 |
| experiments | PRESENT | S1365 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1365] types=['EXPERIMENTAL-RESULT', 'INVARIANT'] scope=THEORY-LEVEL — "Experiment F (later evidence E2 contradicts earlier knowledge K1 used for Decision_1) passes and is judged the most important experiment: a decision D1 justified at t1 (Justified(D1,t1)=true) remains justified even after K1's current validity becomes false at t2 (CurrentValidity(K1,t2)=false) -- CurrentKnowledge != HistoricalKnowledge and Supersession != Deletion, described as one of the strongest architectural consequences obtained from the Chapter 4 lens." (anchor: "Justified(D_1,t_1) may remain: true while: CurrentValidity(K_1,t_2)=false. ... CurrentKnowledge ≠ HistoricalKnowledge. And: Supersession ≠ Deletion. This is one of the strongest architectural consequences we have obtained from the Chapter 4 lens.")
- [S1365] types=['DISTINCTION', 'WARNING'] scope=THEORY-LEVEL — "Distinguishes two distinct knowledge-transition cases: Case 1, new evidence refines an incomplete-but-reasonable conclusion (K1->K2, Superseded); Case 2, evidence shows K1 was actually invalid (Invalidate(K1)) -- not identical, requiring a knowledge lifecycle to express both Superseded and Invalidated. States the system must never rewrite history: a decision historically based on K1 must never be silently re-recorded as based on K2 after K1 is invalidated ('that would create a false history'); instead both true relationships (D1--basedOn-->K1; K1--invalidatedBy-->E2) are preserved, separating HistoricalTruth (what was actually recorded/decided at the time) from CurrentTruth (what we believe now given current evidence) -- both of which a mature knowledge system must support." (anchor: "Case 1 — New evidence changes the conclusion ... Case 2 — Evidence shows K1 was invalid: Invalidate(K1). These are not identical. ... we must not rewrite: Decision 1 basis = K2 if historically it was based on K1. That would create a false history. Instead: D1 --basedOn--> K1 and K1 --invalidatedBy--> E2. ... HistoricalTruth from: CurrentTruth.")

## Notes for P3
- Very thin evidentiary base (row_count=2) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
