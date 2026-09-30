# aggregate-derivation-criteria

**Scope(s):** METHODOLOGICAL · **Row count:** 16 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Agg=(I,O,B)`; `invariant ownership + atomicity + lifecycle + concurrency`
**Aliases:** "four derivation criteria"
**Candidate group membership (NOT an identity claim):**
- G0728: co-occurs with `aggregate-boundary-criterion`, `assurance-claim-aggregate-boundary` — an UNKNOWN-OBJECT-CANDIDATE row (batch B0067, source S2781) named these as alternative candidates for one piece of evidence. why_uncertain: This re-derives an aggregate formalism and candidate-aggregate list independently within the architecture chapter; may be a restatement/continuation of the DDD aggregate-derivation work from B0034 (aggregate-boundary-criterion, aggregate-derivation-criteria) using the same underlying invariant-ownership logic but a different symbolic notation (Agg=<Root,Members,Inv,Cmd,Ev> vs Agg=(I,O,B)), and the concrete Evidence/Epistemic-Case/Model/Decision aggregate candidates are not explicitly tied back to the earlier ten-candidate derivation.  ## EXACT-STRING-REUSE (18 groups)  _STRONG — the identical label string was independently coined by different batches_
- G0891: co-occurs with `aggregate-derivation-program` — working_label token overlap Jaccard=0.50 (shared tokens: ['aggregate', 'derivation'])

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope METHODOLOGICAL: "Step 205's four rigorous DDD aggregate-derivation criteria (invariant ownership, atomicity required by an invariant, lifecycle containment, concurrency cost) and their application across ten candidate concepts, replacing noun-based aggregate grouping."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1424 §"We do not derive Aggregates from nouns. We derive them from invariants, transactional atomicity, identity, lifecycle and concurrency."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1424 §"We do not derive Aggregates from nouns. We derive them from invariants, transactional atomicity, identity, lifecycle and concurrency."]

## Lifecycle

last_seen: S1424. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1424), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1424 (×3) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1424 (×6) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1424 (×3) |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1424 (×6) |
| examples | PRESENT | S1424 (×2) |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

First derivation criterion (invariant ownership): for each invariant, ask which object has enough information and authority to guarantee it; if none can, the invariant is cross-context, the model is missing a concept, or the proposed boundary is wrong -- far more rigorous than grouping objects by similarity [S1424]. Additionally, Walks Proposition vs Assessment through the criteria: a proposition can exist before and receive multiple assessments over time, so its lifecycle is not contained by any one assessment's -- concluding PropositionAggregate is a candidate with assessments as separate objects/references, possibly owning only a CurrentAssessmentReference if a 'one active assessment per model/version' invariant exists [S1424]. Further, Walks Evidence vs Observation: evidence can exist independently and originate from non-observation sources (documents, system records, external datasets, calculations), arguing against a universal ObservationWithEvidence aggregate; instead an EvidenceAggregate can own the verification invariant (Verified requires Provenance and Integrity) [S1424].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1424] types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — "States the governing methodological rule for the whole step: aggregates must be derived from invariants/atomicity/identity/lifecycle/concurrency, deliberately resisting the temptation to create one aggregate per existing noun (EvidenceAggregate, AssessmentAggregate, DecisionAggregate, ...)." (anchor: "We do not derive Aggregates from nouns. We derive them from invariants, transactional atomicity, identity, lifecycle and concurrency.")
- [S1424] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Defines an Aggregate as (Identity, OwnedState, InvariantBoundary), with the boundary B defining what must remain atomically consistent, explicitly distinct from a database table, an entity, or a bounded context." (anchor: "Agg=(I,O,B) ... B defines what must remain consistent atomically. Therefore: Aggregate\neq DatabaseTable. And: Aggregate\neq Entity. And: Aggregate\neq BoundedContext.")
- [S1424] types=[EXPLANATION, DEFINITION] scope=METHODOLOGICAL — "First derivation criterion (invariant ownership): for each invariant, ask which object has enough information and authority to guarantee it; if none can, the invariant is cross-context, the model is missing a concept, or the proposed boundary is wrong -- far more rigorous than grouping objects by similarity." (anchor: "Owner(I_i)=Agg_j. If no aggregate can enforce the invariant locally, then one of three things is true: 1. the invariant is actually cross-context; 2. the model is missing a concept; 3. the proposed aggregate boundary is wrong.")
- [S1424] types=[CONSTRAINT] scope=METHODOLOGICAL — "Second criterion (atomicity): two objects belong in one aggregate only if an actual invariant requires their atomic co-change, not merely that they usually change together." (anchor: "Atomicity must be required by an invariant. ... 'usually change together' is insufficient.")
- [S1424] types=[CONSTRAINT] scope=METHODOLOGICAL — "Third criterion (lifecycle): if B cannot meaningfully exist without A, that is evidence for containment, but lifecycle dependency alone is insufficient if B has its own independent consistency boundary." (anchor: "Lifecycle(B)\subseteq Lifecycle(A). This is evidence for containment. But lifecycle dependency alone is not enough.")
- [S1424] types=[PRINCIPLE, EXTENSION] scope=METHODOLOGICAL — "Fourth criterion (concurrency): frequently-independently-modified objects placed in one aggregate create contention costs; states the optimization principle of minimizing aggregate size subject to invariant preservation." (anchor: "ConcurrencyCost(A,B). An aggregate should be as small as possible while still protecting its invariants. Minimize aggregate size subject to Invariant preservation.")
- [S1424] types=[ANALYSIS, EXAMPLE] scope=OBJECT — "Walks Proposition vs Assessment through the criteria: a proposition can exist before and receive multiple assessments over time, so its lifecycle is not contained by any one assessment's -- concluding PropositionAggregate is a candidate with assessments as separate objects/references, possibly owning only a CurrentAssessmentReference if a 'one active assessment per model/version' invariant exists." (anchor: "Lifecycle(Proposition) \not\subseteq Lifecycle(Assessment). ... PropositionAggregate with assessments represented as separate knowledge objects or references.")
- [S1424] types=[ANALYSIS, DEFINITION] scope=OBJECT — "Walks Evidence vs Observation: evidence can exist independently and originate from non-observation sources (documents, system records, external datasets, calculations), arguing against a universal ObservationWithEvidence aggregate; instead an EvidenceAggregate can own the verification invariant (Verified requires Provenance and Integrity)." (anchor: "Evidence\neq Observation. ... EvidenceAggregate with: EvidenceSource and provenance information internally managed where the invariant requires it. ... I_E: Verified\Rightarrow Provenance\land Integrity.")
- [S1424] types=[PRINCIPLE] scope=METHODOLOGICAL — "States that Observation need not be owned by the Evidence Aggregate as a child entity, since Observation can have its own independent lifecycle (Captured->Corrected->Superseded) -- reference is often preferable to containment." (anchor: "Reference is often preferable to containment.")
- [S1424] types=[DEFINITION] scope=OBJECT — "Assessment is a strong aggregate candidate owning a TraceableBasis invariant (Assessment must reference Proposition, Evidence, Model, Uncertainty, Lineage), but must hold Evidence references, not embedded Evidence ownership, preserving aggregate independence." (anchor: "I_A: Assessment \rightarrow (P,E,M,U,L). ... AssessmentAggregate may contain: EvidenceRef_1,...,EvidenceRef_n. This preserves aggregate independence.")
- [S1424] types=[INVARIANT, EXAMPLE] scope=OBJECT — "Model deserves its own independent aggregate/registry (ModelRegistry/ModelContext) rather than being embedded in Assessment, since multiple assessments can reference the same model and a referenced model version must never be silently mutated -- VersionedModelIdentity is required for reproducibility, especially for AI models." (anchor: "M_v\neq M_{v+1}. Thus: VersionedModelIdentity is required for reproducibility. This is especially important for AI models.")
- [S1424] types=[RESTATEMENT, CONSTRAINT] scope=OBJECT — "Concludes Uncertainty should not automatically become its own aggregate; at present it is a value/semantic component of Assessment, unless it independently develops its own lifecycle, calibration, or governance justifying separation." (anchor: "Uncertainty is a value/semantic component of Assessment, not automatically an Aggregate.")
- [S1424] types=[DEFINITION] scope=OBJECT — "Policy is an independent aggregate candidate (its own versioned lifecycle, multiple decisions can depend on one version); a governed action's validity requires an applicable policy version found at that time, not a copied mutable policy." (anchor: "Policy should have identity/versioning independent from: Decision. A Decision should reference: PolicyVersion. It should not copy the whole mutable policy. ... ActionValid\Rightarrow ApplicablePolicyFound.")
- [S1424] types=[INVARIANT] scope=OBJECT — "Authority has its own independent lifecycle (an actor's authority can change over time), so a decision must preserve the authority basis at the time it was made -- PresentAuthority != HistoricalAuthority, a conditional aggregate candidate." (anchor: "Authority_t\neq Authority_{t+1}. Therefore the decision record must preserve the authority basis at decision time. ... PresentAuthority\neq HistoricalAuthority.")
- [S1424] types=[INVARIANT, CONSTRAINT] scope=OBJECT — "Decision is a strong aggregate candidate with invariant FinalDecision requires ValidDecisionBasis; explicitly references (never owns) Assessment, or Decision would become responsible for epistemic lifecycle, violating bounded responsibility." (anchor: "I_D: FinalDecision \Rightarrow ValidDecisionBasis. ... Decision references Assessment; it does not own Assessment.")
- [S1424] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Action is a strong aggregate candidate (identity, target, authorization, status, execution info) that references rather than owns its originating Decision; Outcome is a low-confidence aggregate candidate since it may be produced by an external system, reinforcing ExecutionState != Outcome." (anchor: "Action \xrightarrow{derivedFrom} DecisionRef. This preserves the distinction: Decision\neq Action. ... Action \rightarrow OutcomeObservation. ExecutionState\neq Outcome.")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
