# authority-knowledge-gap

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DecisionRisk=f(AuthorityScope,KnowledgeQuality,EvidenceCompleteness,Uncertainty)`; `Gap(a)=AuthorityScope(a)-KnowledgeCoverage(a)`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 196's concept of a dangerous mismatch where an actor's decision authority exceeds its available knowledge, connected to a decision-risk formalization and a deterministic escalation policy triggered by uncertainty exceeding a threshold."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1414 §"Authority_{new} > Knowledge_{new}. The architecture should make this visible. ... Gap(a)=AuthorityScope(a)-KnowledgeCoverage(a)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1414 §"DecisionRisk = f(AuthorityScope,KnowledgeQuality,EvidenceCompleteness,Uncertainty). An actor with enormous authority but poor information can be more dangerous than a highly informed actor with limited authority."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1414. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a recency heuristic — all four rows come from a single source document (S1414), so "last seen" here just means the label was never revisited elsewhere in the captured corpus, not that it was retired.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1414 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1414 (×4) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1414 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1414 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

S1414 formalizes `DecisionRisk` as a function of AuthorityScope, KnowledgeQuality, EvidenceCompleteness, and Uncertainty, arguing "an actor with enormous authority but poor information can be more dangerous than a highly informed actor with limited authority" [S1414] — this is the sole rationale-bearing row for the label. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1414] types=[DEFINITION, WARNING] scope=THEORY-LEVEL — "Defines a conceptual Authority-Knowledge Gap: an actor can hold decision authority over a domain larger than the knowledge actually available to it (Authority_new > Knowledge_new), a dangerous situation the architecture should make visible and potentially trigger Review or Escalation." (anchor: "Authority_{new} > Knowledge_{new}. The architecture should make this visible. ... Gap(a)=AuthorityScope(a)-KnowledgeCoverage(a).")
- [S1414] types=[FORMALIZATION, ARGUMENT] scope=OBJECT — "Formalizes DecisionRisk as a function of AuthorityScope, KnowledgeQuality, EvidenceCompleteness, and Uncertainty, arguing that high authority combined with poor information is more dangerous than the reverse." (anchor: "DecisionRisk = f(AuthorityScope,KnowledgeQuality,EvidenceCompleteness,Uncertainty). An actor with enormous authority but poor information can be more dangerous than a highly informed actor with limited authority.")
- [S1414] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Connects statistical uncertainty U(P|E) to deterministic governance policy: a decision authority may require the uncertainty to stay below a threshold U_max, above which an escalation policy is deterministically triggered." (anchor: "U(P|E)<=U_max. If U(P)>U_max, the governance policy might require: Escalate. This connects statistics directly to deterministic governance.")
- [S1414] types=[PRINCIPLE, FORMALIZATION] scope=THEORY-LEVEL — "States the deterministic-assurance principle precisely: rather than asking an AI whether a decision is 'okay', the system should deterministically verify Assurance(d) = AuthorityValid AND EvidencePresent AND RequiredReviewCompleted AND PolicySatisfied." (anchor: "The system deterministically verifies that the decision process satisfies the required rules. Assurance(d)= AuthorityValid \\land EvidencePresent \\land RequiredReviewCompleted \\land PolicySatisfied.")

## Notes for P3

- This label's evidence base is a single source document (S1414, Step 196) with no cross-corpus reinforcement or contradiction captured in this batch. Its final row's `Assurance(d)` formula overlaps conceptually with the `deterministic-assurance-track2` label elsewhere in this batch (LB0007) — both concern deterministic, rule-conjunction verification of a decision/assurance predicate — but no mechanical group_id links the two, so P3 may want to check whether they are the same underlying architectural device surfacing under two names.
