# authoritative-observational-inferred-state-layers

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AUTHORITATIVE STATE / OBSERVATIONAL STATE / INFERRED STATE
**Aliases:** deterministic domain lifecycle vs probabilistic analytical inference
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "A three-layer state architecture: Authoritative State (deterministic domain/governance lifecycle, e.g. Decision=APPROVED) must never be silently overridden by Observational State (evidence actually seen) or Inferred State (probabilistic analytical model, e.g. P(GovernanceDecisionWasActuallyApproved|evidence)=0.97); disagreement between authoritative and inferred state triggers review rather than automatic state change -- invariant Observed != Inferred != Authoritative != True."

No single_candidate_flags recorded for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0466 §"DDD asks: What business/domain concept owns the state? ... The HSMM is then a cross-cutting analytical model, not necessarily the domain's source of truth. ... Governance: Decision = APPROVED is authoritative. But: Analytical inference: P(GovernanceDecisionWasActuallyApproved|evidence)=0.97 is an analytical assessment. The second cannot override the first."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0466. Candidate lifecycle: ACTIVE.
Evidence: no retracted_by, no superseded_by, not contested_by_own_contradiction_type — all empty. ACTIVE is a heuristic based on recency of source_id, not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0466 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0466 |
| dependencies | PRESENT | S0466 (x4) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0466 (x3) |
| examples | PRESENT | S0466 (x2) |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
One rationale-bearing row: the deepest result and final recommendation of the source document is Observed != Inferred != Authoritative != True as the core architectural invariant, with Temporal Epistemic Modelling added as a first-class research track (Model Registry -> Temporal Inference [filtering/smoothing/prediction] -> Assurance -> Wisdom -> Action) without changing the core DDD architecture yet [S0466]. `rationale_truncated_count` is 0.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S0466]` types=[DISTINCTION, CONSTRAINT] scope=OBJECT — "DDD discipline against making 'HiddenState' a universal aggregate: each bounded context (Architecture/Evidence/Governance/Knowledge) has its own state machine; the HSMM is a cross-cutting analytical model, not the domain source of truth -- a governance-approved decision (authoritative) cannot be overridden by an analytical inference about whether it was 'actually' approved, even at high probability." (anchor: "DDD asks: What business/domain concept owns the state? ... Governance: Decision = APPROVED is authoritative. But: Analytical inference: P(GovernanceDecisionWasActuallyApproved|evidence)=0.97 ... The second cannot override the first.")
- `[S0466]` types=[EXAMPLE, GOVERNANCE] scope=OBJECT — "Zero Lens requires preserving disagreement between authoritative and inferred state rather than silently reconciling them: a strong analytical signal of contestation triggers a review action, it does not overwrite the authoritative Assured status." (anchor: "AuthoritativeState=Assured but P(InferredState=Contested)=0.41. We should not automatically change the authoritative state. Instead: AUTHORITY: Assured / ANALYTICAL SIGNAL: Strong evidence of contestation / ACTION: Trigger review.")
- `[S0466]` types=[DISTINCTION, EXAMPLE] scope=OBJECT, label_confidence=UNCERTAIN — "Online model-parameter learning is permitted for the analytical model but canonical domain knowledge must still follow governance; a network-anomaly-detection analogy (hidden workload state/request rate/anomaly) is mapped onto KnowledgeOS (hidden epistemic state/evidence observations/unexpected epistemic behaviour), yielding an AnomalyScore = -log P(unexpected observation | expected state) that can trigger 'possible epistemic regime change', illustrated by a 14-month-stable Assured state suddenly showing failing tests and conflicting governance records." (anchor: "Analytical model can update automatically. Canonical domain knowledge must follow governance. ... a passing model to network anomaly detection: hidden workload state / requests-per-second / anomaly -> hidden epistemic state / evidence observations / unexpected epistemic behaviour.")
- `[S0466]` types=[PRINCIPLE, ANALYSIS] scope=THEORY-LEVEL — "Deepest result and final recommendation: Observed != Inferred != Authoritative != True as the core architectural invariant, with Temporal Epistemic Modelling added as a first-class research track (Model Registry -> Temporal Inference (filtering/smoothing/prediction) -> Assurance -> Wisdom -> Action) without changing the core DDD architecture yet." (anchor: "Observed != Inferred != Authoritative != True. That single distinction is enormously important. ... 'Temporal Epistemic Modelling / Hidden Epistemic State Modelling' as a first-class research track ... but keep HSMM as an analytical capability, not as the domain ontology itself.")

## Notes for P3
All four rows are drawn from a single source document (S0466, on Hidden Semi-Markov Models as a hidden epistemic-state layer). The four rows read as a tight, internally coherent progression (DDD-ownership principle -> worked disagreement example -> anomaly-detection analogy -> final invariant/recommendation), i.e. this looks like a genuinely well-evidenced single-source object rather than a synthesis across multiple documents. No group_ids connect it to anything else in this batch despite an obvious conceptual proximity to the "belief vs. established knowledge" and evidence-classification themes in other labels of this same batch (e.g. `step177-belief-and-ai-hallucination-prevention`) — noted only as an observation, not a merge candidate.
