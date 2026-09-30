# separation-of-duties-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "I_54", "Proposer != Approver" · **Aliases:** "SoD"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 196's invariant I_54: where separation-of-duties policy applies, the same actor must not hold mutually exclusive governance roles for the same transition; includes the Observer/Reviewer split and the ban on circular self-granted authority."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1414 §"Responsibility(Architect) \neq Authority(Architect,Approve). The architect can prepare and recommend. The Board determines."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1414. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1414), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1414 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1414, S1414 |
| examples | PRESENT | S1414 |
| warnings | PRESENT | S1414 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1414] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Separates Responsibility from Authority via a worked example: an Architect is responsible for architecture quality (can prepare/recommend) but the Architecture Board holds approval authority (determines) -- the two roles are not the same actor's powers." (anchor: "Responsibility(Architect) \neq Authority(Architect,Approve). The architect can prepare and recommend. The Board determines.")
- [S1414] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_54: where separation-of-duties policy applies (e.g. Proposer != Approver), the same actor must not simultaneously hold both mutually exclusive governance roles for one transition." (anchor: "I_54: Where separation of duties is required, the same actor must not satisfy mutually exclusive governance roles for the same transition.")
- [S1414] types=[PRINCIPLE] scope=OBJECT — "Argues for separating the Observer (who supplies evidence) from the Reviewer (who assesses it), reducing confirmation bias and circularity in the epistemic pipeline." (anchor: "The person who supplies evidence should not necessarily be the person who determines its validity. ... This reduces confirmation bias and circularity.")
- [S1414] types=[CONSTRAINT, WARNING] scope=OBJECT — "Warns against circular authority: an actor granting itself authority without external basis (Grant(A,A)) should normally be treated as invalid unless the domain explicitly permits self-constituting authority." (anchor: "Grant(A,A) should normally be invalid unless the domain explicitly permits self-constituting authority.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
