# four-graphs-temporal-epistemic-governance-causal

**Scope(s):** THEORY-LEVEL · **Row count:** 9 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** TemporalGraph, EpistemicGraph, GovernanceGraph, CausalGraph
**Aliases:** semantic structures, not four services
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 194's deepest result: KnowledgeOS must preserve four distinct interacting semantic graphs (Temporal, Epistemic, Governance, Causal), explicitly not a mandate for four separate implementations; includes the typed-edge graph extension, six candidate bounded contexts, and the restated four+one Gita-chapter architectural questions."

No single_candidate_flags recorded for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1397 §"A --supports--> B / A --precedes--> B / A --causes--> B / A --justifies--> B / A --authorizes--> B. The edge itself carries semantics."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1412. Candidate lifecycle: ACTIVE.
Evidence: no retracted_by, no superseded_by, not contested_by_own_contradiction_type — all empty. ACTIVE is a heuristic based on recency of source_id, not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1397 (x4) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1397 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1397 (x5), S1412 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1397 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Four rationale-bearing rows, all from S1397 (Step 194): (1) extends the causal lineage graph (Observation->Evidence->Statistical Analysis->Causal Claim, branching to Epistemic Assessment and Governance Determination->Decision->Action) into a typed graph where each edge carries domain semantics, connecting to DDD — these deserve distinct ubiquitous language, invariants, policies, and validation rules rather than one generic Relationship{type:string} entity; (2) identifies six candidate bounded-context boundaries (Observation, Evidence, Epistemic, Governance, Decision, Execution) as strong semantic boundaries, explicitly warning against prematurely turning them into six microservices; (3) applies the atomic-consistency DDD question to the causal/governance split — EvidenceRegistration needs atomic EvidenceIdentity+Provenance+Integrity but should not atomically create a GovernanceDecision, which should be a separate transition; (4) restates the Chapter 1 lens that Arjuna's problem is conflicting values/constraints, not missing information, so the same evidence can legitimately support several different decisions under different objectives and Evidence must not be assumed to imply a UniqueDecision [S1397]. `rationale_truncated_count` is 0.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S1397]` types=[EXTENSION, ARGUMENT] scope=OBJECT — "Extends the causal lineage graph into a typed graph where the edge itself carries domain semantics, connecting to DDD: these deserve distinct ubiquitous language, invariants, policies, and validation rules rather than one generic Relationship{type:string} entity." (anchor: "A --supports--> B / A --precedes--> B / A --causes--> B / A --justifies--> B / A --authorizes--> B. The edge itself carries semantics.")
- `[S1397]` types=[EXTENSION, WARNING] scope=THEORY-LEVEL — "Identifies six candidate bounded-context boundaries (Observation, Evidence, Epistemic, Governance, Decision, Execution) as strong semantic boundaries, explicitly warning against prematurely turning them into six microservices." (anchor: "Observation Context ... Governance Context ... Decision Context ... Execution Context. These should not automatically become six microservices.")
- `[S1397]` types=[ARGUMENT] scope=OBJECT — "Applies the atomic-consistency DDD question to the causal/governance split: EvidenceRegistration needs atomic EvidenceIdentity+Provenance+Integrity but should not atomically create a GovernanceDecision, which should be a separate transition." (anchor: "EvidenceRegistration may need to atomically establish: EvidenceIdentity+Provenance+Integrity. But it probably does not need to atomically create: GovernanceDecision.")
- `[S1397]` types=[ARGUMENT, RESTATEMENT] scope=THEORY-LEVEL — "Chapter 1 lens restated: Arjuna's problem is conflicting values/constraints, not missing information; the same evidence can legitimately support several different decisions under different objectives, so Evidence must not be assumed to imply a UniqueDecision." (anchor: "MultipleConstraints + ConflictingValues \\rightarrow DecisionProblem. The architecture should therefore not assume: Evidence \\rightarrow UniqueDecision.")
- `[S1397]` types=[DISTINCTION] scope=OBJECT — "Chapter 2 lens restated for AI-agent evaluation: DecisionQuality (was the choice correct given available knowledge) must be distinguished from OutcomeQuality (was the eventual result favorable), since a correct decision can still yield an unfavorable outcome." (anchor: "DecisionQuality from: OutcomeQuality.")
- `[S1397]` types=[INVARIANT, RESTATEMENT] scope=OBJECT — "Chapter 4 lens (Transmission+Lineage+Renewal): knowledge passed between actors/systems may transmit only a projection, not the complete history, so Transmission != CompleteReconstruction -- reinforcing provenance requirements." (anchor: "Transmission \\neq CompleteReconstruction.")
- `[S1397]` types=[RESTATEMENT, ANALYSIS] scope=THEORY-LEVEL — "Summarizes the four Gita-chapter architectural questions (conflict/competing obligations; identity/continuity through change; separating knowledge from action; transmission/lineage/renewal) and adds Step 194's fifth question about distinguishing sequence/evidence/correlation/causation." (anchor: "How do we distinguish temporal sequence, evidence, correlation, and causation?")
- `[S1397]` types=[RESTATEMENT, CONSTRAINT] scope=THEORY-LEVEL — "Deepest result of Step 194: the architecture must preserve (at least) four distinct semantic graphs -- Temporal, Epistemic, Governance, and (newly) Causal -- interacting through typed transitions, explicitly warning these are semantic structures, not a mandate for four separate databases/services." (anchor: "TemporalGraph ... EpistemicGraph ... GovernanceGraph ... CausalGraph. These should not automatically be implemented as four databases or four services.")
- `[S1412]` types=[EXTENSION, RESTATEMENT] scope=THEORY-LEVEL — "Extends the corpus's growing multi-graph architecture to five named graphs (adding IdentityGraph to Temporal/Epistemic/Causal/Governance), reframing KnowledgeOS as 'a federation of semantic graphs over a common lineage model' rather than one undifferentiated graph -- explicitly warning this means five conceptual models, not necessarily five separate databases; DDD determines boundaries first, technology comes later." (anchor: "G_I = IdentityGraph, G_T = TemporalGraph, G_E = EpistemicGraph, G_C = CausalGraph, G_G = GovernanceGraph. ... a federation of semantic graphs over a common lineage model")

## Notes for P3
Eight of nine rows come from a single source (S1397, Step 194); the ninth (S1412, Step 195) is the very next step in the same file series and extends the four-graph result to five graphs by adding IdentityGraph — this reads as a direct, immediate continuation rather than an independent corroboration, so P3 should weigh this label's evidentiary base as "one extended argument across two adjacent steps" rather than "two independent sources." No group_ids connect this label to anything else, despite an obvious thematic connection to `knowledgeos-causal-inference-and-dynamical-system` (also in this batch, also about causal graphs) and to `authoritative-observational-inferred-state-layers` (also in this batch, also about layered state/graph distinctions) — flagged only as an observation for P3's cross-label reconciliation, not asserted as identity or dependency.
