# reversibility-compensation-idempotency-taxonomy

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `(T,prec)`, `Compensation(tau)`, `Idempotent(tau)`, `tau^-1` · **Aliases:** `Rollback != Reversal`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 204's formal treatment of transition reversibility, compensation (Payment != Refund^-1), idempotency, commutativity, and a partial (not total) causal order over transitions, defining concurrency as absence of causal dependency."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1423 §"Rollback\neq Reversal. ... Payment \rightarrow Refund. The refund does not erase the payment. ... Payment\neq Refund^{-1}."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1423 §"(\mathcal T,\prec). ... Concurrency = absence of causal dependency."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1423. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1423 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1423 |
| examples | PRESENT | S1423 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1423] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Distinguishes true reversibility (tau^-1 exists such that tau^-1(tau(s))=s) from compensation for irreversible actions (e.g. MessageSent, PaymentExecuted): a Refund does not erase a Payment, it creates a new domain event/state -- Rollback != Reversal, and Payment != Refund^-1." (anchor: "Rollback\neq Reversal. ... Payment \rightarrow Refund. The refund does not erase the payment. ... Payment\neq Refund^{-1}.")
- [S1423] types=[DEFINITION, EXAMPLE] scope=OBJECT — "Defines transition idempotency, requiring every transition to explicitly declare Idempotent(tau) in {True,False}; a critical implementation consequence for distributed systems where messages can be delivered more than once (e.g. MarkEvidenceVerified may be idempotent while CreateDecision may not be)." (anchor: "\tau(\tau(s))=\tau(s). ... Idempotent(\tau)\in\{True,False\}. ... If e is delivered twice, we need: Process(e,e) to have a predictable result.")
- [S1423] types=[DEFINITION, EXAMPLE] scope=OBJECT — "Defines transition commutativity (independent observations typically commute, e.g. Add(O1) o Add(O2) = Add(O2) o Add(O1)), while competing decisions typically do not commute (Approve o Reject != Reject o Approve), making ordering semantically meaningful for concurrent processing." (anchor: "\tau_a\circ\tau_b = \tau_b\circ\tau_a. ... Approve\circ Reject \neq Reject\circ Approve. Therefore ordering is semantically meaningful.")
- [S1423] types=[FORMALIZATION] scope=OBJECT — "Proposes a partial order over transitions (tau_a prec tau_b only where a genuine dependency exists) rather than forcing a total order, defining concurrency as the absence of causal dependency -- a better foundation for distributed KnowledgeOS processes." (anchor: "(\mathcal T,\prec). ... Concurrency = absence of causal dependency.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
