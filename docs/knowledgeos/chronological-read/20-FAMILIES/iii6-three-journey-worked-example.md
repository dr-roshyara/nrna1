# iii6-three-journey-worked-example

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Journey 1: Candidate->Supported (stalls on ActiveConflict)->Accepted after adjudication; volume of tally copies cannot move a rung`, `Journey 2: Committed(R,PublishCertification) crossing A6, blocked entirely if Auth unfilled`, `Journey 3: Contested->Rejected, preserved not deleted`
**Aliases:** `III.6's running-example illustration (Stage 5)`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope OBJECT: Book chapter III.6's illustrative (explicitly [IN]/never-evidence) three-journey worked example on the running 'certifying an election result' example: Journey 1 (the ladder cleanly) -- R enters Candidate on tally-file arrival, becomes Supported after independent comparison, stalls at Determination on ActiveConflict=True, then becomes Accepted after governed conflict adjudication, explicitly noting no volume of additional tally copies could move R a rung ('the ladder listens to the policy, not to volume'); Journey 2 (the boundary) -- the returning officer's authority act Committed(R,PublishCertification) crosses A6's line, and would remain blocked forever at Accepted if the officer's Auth were unfilled ('evidence cannot cross'); Journey 3 (the branches) -- a rival tally proposition R' becomes Supported, is Contested, fails Determination on the conflict condition, and is Rejected after adjudication finds its chain derivative, preserved with its reasoning, never deleted, and explicitly distinct from merely NotAccepted.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1385 §"no amount of additional tally copies (III.5's forty-one) would have moved R one rung — the ladder listens to the policy, not to volume. ... Had the officer lacked authority (III.7's Auth unfilled), R would remain Accepted forever — evidence cannot cross. ... is Rejected: preserved with its reasoning, never deleted (Article 7), and distinct from merely NotAccepted."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1409. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1409), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1385 |
| dependencies | PRESENT | S1385 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1409 |
| examples | PRESENT | S1385, S1409 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1385] types=['EXAMPLE'] scope=OBJECT — "Illustrates the admission ladder via three journeys on the running election-certification example: Journey 1 shows evidence volume cannot substitute for policy satisfaction ('the ladder listens to the policy, not to volume'); Journey 2 shows the A6 boundary is crossable only by authority, never by evidence, however strong ('evidence cannot cross'); Journey 3 shows a rejected proposition is preserved with its reasoning, never deleted, and is explicitly distinct from merely NotAccepted." (anchor: "no amount of additional tally copies (III.5's forty-one) would have moved R one rung — the ladder listens to the policy, not to volume. ... Had the officer lacked authority (III.7's Auth unfilled), R would remain Accepted forever — evidence cannot cross. ... is Rejected: preserved with its reasoning, never deleted (Article 7), and distinct from merely NotAccepted.")
- [S1409] types=['EXAMPLE', 'RESTATEMENT'] scope=OBJECT — "Stage 5 (chapter III.6) ledger row confirms the admission-ladder journeys already worked out for the election example: R progresses Candidate->Supported, stalls at r3's conflict, then reaches Accepted; a rival R' is Contested then Rejected on a separate wire/chain; the Committed(R,Publish) boundary crossing remains pending at this stage." (anchor: "| III.6 | 5 | ladder journeys | R: Candidate→Supported→(stall r₃)→Accepted · R′: Contested→Rejected (separate wire chain) · boundary: Committed(R, Publish) pending")

## Notes for P3
(none beyond what is noted above)
