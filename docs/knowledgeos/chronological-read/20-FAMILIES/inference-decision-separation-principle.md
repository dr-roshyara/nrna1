# inference-decision-separation-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Posterior != Action`; `Reject Option`
**Aliases:** "P1-P8 Bishop principles"
**Candidate group membership (NOT an identity claim):**
- G0083: links this to `epistemic-vs-practical-decision-separation` — explicit agent-stated uncertainty: 'inference-decision-separation-principle' POSSIBLY relates to 'epistemic-vs-practical-decision-separation' (batch B0012). Note: Bishop's inference-vs-decision-theory separation (posterior estimation vs loss-driven action choice) reframed as eight architectural principles: P1 Inference!=Decision; P2 Reject is a valid outcome (formalized as Decision=argmin_a E[L(a,theta)|evidence] with a=REJECT as an available action, giving KnowledgeOS a first-class ABSTAIN/ESCALATE/ACCEPT_WITH_UNCERTAINTY/INSUFFICIENT_EVIDENCE vocabulary instead of forced TRUE/FALSE); P3 structure before sophistication; P4 complexity must earn its cost via model evidence; P5 approximation must be observable (bound+convergence+validation); P6 model uncertainty is first-class and distinct from state uncertainty; P7 preserve posterior distributions until decision time rather than premature point estimates; P8 use heterogeneous experts (mixture-of-experts) where domains differ, aligning BoundedContext ~= InferenceExpertBoundary.

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0012, scope THEORY-LEVEL: "Bishop's inference-vs-decision-theory separation (posterior estimation vs loss-driven action choice) reframed as eight architectural principles: P1 Inference!=Decision; P2 Reject is a valid outcome (formalized as Decision=argmin_a E[L(a,theta)|evidence] with a=REJECT as an available action, giving KnowledgeOS a first-class ABSTAIN/ESCALATE/ACCEPT_WITH_UNCERTAINTY/INSUFFICIENT_EVIDENCE vocabulary instead of forced TRUE/FALSE); P3 structure before sophistication; P4 complexity must earn its cost via model evidence; P5 approximation must be observable (bound+convergence+validation); P6 model uncertainty is first-class and distinct from state uncertainty; P7 preserve posterior distributions until decision time rather than premature point estimates; P8 use heterogeneous experts (mixture-of-experts) where domains differ, aligning BoundedContext ~= InferenceExpertBoundary."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0471 §"inference — estimate the posterior distribution ... decision — use that posterior together with a loss function to choose an action. ... Evidence -> Inference -> Assessment -> Decision, and never collapse them."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0471 §"a reject option: when posterior probabilities are insufficiently decisive, the system should refuse to make the classification and hand the difficult case to a human expert. ... Decision = argmin_a E[L(a,theta)|evidence] where one possible action is a = REJECT."]
- CANDIDATE-FORMAL-BIRTH: [S0471 §"a reject option: when posterior probabilities are insufficiently decisive, the system should refuse to make the classification and hand the difficult case to a human expert. ... Decision = argmin_a E[L(a,theta)|evidence] where one possible action is a = REJECT."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0471. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S0471), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0471 (×2) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0471 |
| dependencies | PRESENT | S0471 (×4) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0471 |
| examples | PRESENT | S0471 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0471] types=[PRINCIPLE, DISTINCTION] scope=THEORY-LEVEL — "Called 'the single most important extraction': Bishop's inference-vs-decision-theory separation directly reinforces KnowledgeOS's existing Evidence->Inference->Assessment->Decision chain and strengthens the Godel invariant into a computational form: posterior != decision." (anchor: "inference — estimate the posterior distribution ... decision — use that posterior together with a loss function to choose an action. ... Evidence -> Inference -> Assessment -> Decision, and never collapse them.")
- [S0471] types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Bishop's reject option formalizes the platform's independently-derived 'escalate when uncertain' mechanism into a principled decision-theoretic form, giving KnowledgeOS a first-class ABSTAIN/ESCALATE/ACCEPT_WITH_UNCERTAINTY/INSUFFICIENT_EVIDENCE vocabulary instead of forcing TRUE/FALSE or STATE_A/STATE_B." (anchor: "a reject option: when posterior probabilities are insufficiently decisive, the system should refuse to make the classification and hand the difficult case to a human expert. ... Decision = argmin_a E[L(a,theta)|evidence] where one possible action is a = REJECT.")
- [S0471] types=[FORMALIZATION] scope=OBJECT — "Refines the prior Compute+ExpectedError routing formula into ExpectedTotalCost = ComputeCost + ExpectedDecisionLoss, incorporating Bishop's asymmetric false-positive/false-negative cost example, so the inference ladder path can vary by problem risk (a low-risk problem stops at a deterministic rule; a high-risk one may jump directly to an expensive method)." (anchor: "ExpectedTotalCost(model) = ComputeCost(model) + ExpectedDecisionLoss(model). ... choose m* = argmin_m ExpectedTotalCost(m) subject to assurance constraints. ... the inference ladder should therefore be risk-aware.") — lineage claim: SOURCE-CLAIMED-REFINEMENT of the earlier Compute(a)+ExpectedError(a) formula from S0467
- [S0471] types=[CONCEPT, EXAMPLE] scope=OBJECT — "Mixture-of-experts (p(t|x)=sum_k pi_k(x)p(t|x,k)) mapped onto DDD: an 'epistemic experts' architecture where a Context Gate (a function of Context/Domain/EvidenceType/TemporalStructure/Risk, not merely an ML classifier) routes to a Temporal Expert (HSMM), Semantic Expert (SNF), or Governance Expert (Rules/DDD) -- BoundedContext approximately equals InferenceExpertBoundary." (anchor: "mixture of experts, where different experts model different regions of the input space and the gate determines which expert controls the prediction. ... BoundedContext ~= InferenceExpertBoundary.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
