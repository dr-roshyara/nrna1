# entities-vs-value-objects-candidates-part18

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope OBJECT: "Candidate entity list (EvidenceRecord, Proposition, Assertion, KnowledgeCase, Model, ModelVersion, Decision, Action, LearningEvent) vs value-object list (Context, Quantity, Unit, Dimension, TimeInterval, UncertaintySpecification, AssumptionSet, Requirement, Contract, IdentitySpecification)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2781 §"Candidate entities: EvidenceRecord, Proposition, Assertion, KnowledgeCase, Model, ModelVersion, Decision, Action, LearningEvent. Candidate value objects: Context, Quantity, Unit, Dimension, TimeInterval, UncertaintySpecification, AssumptionSet, Requirement, Contract, IdentitySpecification."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S2781 §"Candidate entities: EvidenceRecord, Proposition, Assertion, KnowledgeCase, Model, ModelVersion, Decision, Action, LearningEvent. Candidate value objects: Context, Quantity, Unit, Dimension, TimeInterval, UncertaintySpecification, AssumptionSet, Requirement, Contract, IdentitySpecification."] (same anchor as lexical birth)
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2781 (single row/single source). Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a recency heuristic only.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2781] types=[CONCEPT] scope=OBJECT — "18.13: lists candidate DDD entities (require identity: EvidenceRecord, Proposition, Assertion, KnowledgeCase, Model, ModelVersion, Decision, Action, LearningEvent) versus candidate value objects (defined by value-equivalence: Context, Quantity, Unit, Dimension, TimeInterval, UncertaintySpecification, AssumptionSet, Requirement, Contract, IdentitySpecification); notes the classification is domain-dependent and a VO can become an entity if lifecycle/identity become domain-significant." (anchor: "Candidate entities: EvidenceRecord, Proposition, Assertion, KnowledgeCase, Model, ModelVersion, Decision, Action, LearningEvent. Candidate value objects: Context, Quantity, Unit, Dimension, TimeInterval, UncertaintySpecification, AssumptionSet, Requirement, Contract, IdentitySpecification.")

## Notes for P3

- This candidate entity list overlaps substantially with objects captured elsewhere in this batch (e.g. `decision-as-distinct-aggregate`'s Decision/Determination distinction, `authority-knowledge-gap`'s Decision/Assurance formalization) — P3 should check whether `Decision` and `Action` as listed here (Step-18.13's candidate entity list) are meant to be the same entities discussed under those other labels, or independently proposed.
- The row's own caveat — "a VO can become an entity if lifecycle/identity become domain-significant" — is a load-bearing qualifier: this list should be read as provisional DDD classification work, not a settled model, consistent with its ACTIVE-by-recency (not ACTIVE-by-ratification) lifecycle status here.
