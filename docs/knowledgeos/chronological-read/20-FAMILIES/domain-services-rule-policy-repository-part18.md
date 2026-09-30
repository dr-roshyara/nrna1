# domain-services-rule-policy-repository-part18

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EvaluateEvidence(E,Gamma)`, `Repository<T>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Domain service definition, InferenceRule vs GovernancePolicy distinction, and Repository<T> semantics forbidding silent Retracted->Deleted / Unknown->False transformations.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2781] §"EvaluateEvidence(E,Gamma) -> Evaluation ... InferenceRule ≠ GovernancePolicy ... Repository<T> ... must not silently transform Retracted -> Deleted or Unknown -> False"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2781. Candidate lifecycle: ACTIVE. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2781 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2781 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2781] types=[DEFINITION, DISTINCTION, CONSTRAINT] scope=THEORY-LEVEL — "18.17-18.19: defines domain services for ownerless domain operations (EvaluateEvidence(E,Gamma)->Evaluation, Estimate(M,D,EC)->EstimateResult) that must preserve assumptions/model/contract/uncertainty/provenance; distinguishes an inference Rule tuple <Premises,Conclusion,Conditions,Exceptions,Logic,Authority,Version,Provenance> from a governance Policy (InferenceRule≠GovernancePolicy, e.g. 'Decision requires Authority A' vs 'If X then Y'); requires Repository<T> to preserve identity/lifecycle semantics and never silently transform Retracted->Deleted or Unknown->False because of persistence limitations -- 'the domain must not be rewritten to fit the database.'" (anchor: "EvaluateEvidence(E,Gamma) -> Evaluation ... InferenceRule ≠ GovernancePolicy ... Repository<T> ... must not silently transform Retracted -> Deleted or Unknown -> False")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
