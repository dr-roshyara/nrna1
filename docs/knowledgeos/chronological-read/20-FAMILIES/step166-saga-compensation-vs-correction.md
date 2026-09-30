# step166-saga-compensation-vs-correction

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ActionExecuted and ExecutionOutcomeObserved(Failure) both true`, `Decision->AuthorizationDenied as legitimate terminal branch (not rollback)`, `compensation != correction` · **Aliases:** `Saga applicability test`, `historical facts are not rolled back`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 166's test for whether a Saga pattern actually applies: not 'can we use a Saga' but 'do we actually have a distributed business transaction whose intermediate states are legitimate?' -- e.g. a DecisionMade followed by AuthorizationDenied is not a rollback of the decision (the decision remains historically valid) but a legitimate terminal branch, so this is not necessarily a traditional transactional Saga. Distinguishes compensation (undo/offset a previous business action) from correction (the previous state/fact was incomplete or wrong, so establish a new state) -- not the same mechanism. Reinforces the Step-164 historical-immutability principle with a worked example: an ActionExecuted followed by ExecutionOutcomeObserved(Failure) keeps both facts true simultaneously (the command was executed; its outcome was unsuccessful) rather than collapsing to one mutable status field like action.status=FAILED, which 'loses semantic distinctions'; a failure may instead spawn a CorrectiveActionRequested."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1359 §"Do we actually have a distributed business transaction whose intermediate states are legitimate? ... Decision → AuthorizationDenied may simply be a legitimate terminal branch. ... A compensation says: Undo or compensate a previous business action. A correction says: The previous state/fact was incom…"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1359. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1359 |
| dependencies | PRESENT | S1359 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1359 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1359 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1359] types=[DISTINCTION, WARNING] scope=THEORY-LEVEL — "Gives the correct test for adopting a Saga: not 'can we use a Saga' but 'do we actually have a distributed business transaction whose intermediate states are legitimate?' -- a DecisionMade followed by AuthorizationDenied is a legitimate terminal branch, not a rollback (the decision remains historically valid), so not necessarily a traditional transactional Saga. Distinguishes compensation (undo/offset a prior business action) from correction (establish new state because the prior fact was incomplete/wrong) -- these are not the same mechanism; a failed execution (ActionExecuted true, ExecutionOutcomeObserved(Failure)) does not erase the execution but may spawn a CorrectiveActionRequested." (anchor: "Do we actually have a distributed business transaction whose intermediate states are legitimate? ... Decision → AuthorizationDenied may simply be a legitimate terminal branch. ... A compensation says:…")
- [S1359] types=[INVARIANT, RESTATEMENT] scope=THEORY-LEVEL — "Reinforces the Step-164 historical-immutability principle in the operational context: historical facts are not rolled back merely because their consequences are undesirable; ActionExecuted and ExecutionOutcomeObserved(Failure) are both simultaneously true, which is more accurate than collapsing to a single mutable status field like action.status=FAILED, which loses semantic distinctions." (anchor: "Historical facts are not rolled back merely because their consequences are undesirable. If an action happened: ActionExecuted remains true. A later action can compensate it. ... action.status = FAILED…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
