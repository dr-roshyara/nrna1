# model-registry-object

**Scope(s):** `OBJECT` · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KnowledgeOS Model Registry`, `Model-of-Knowledge`, `ModelApplicability` · **Aliases:** `Model vs Knowledge Object distinction`
**Candidate group membership (NOT an identity claim):**
- **G0199**: [`champion-challenger-model-promotion-lifecycle` · `model-registry-object`] — explicit agent-stated uncertainty: 'champion-challenger-model-promotion-lifecycle' POSSIBLY relates to 'model-registry-object' (batch B0023). Note: Step 45's governed model-promotion machinery: the Candidate/Validated/Approved/Active/Deprecated lifecycle, shadow-mode safety testing, champion/challenger comparison, and multi-objective constrained model selection -- an operational-governance extension of model-registry-object rather than the registry schema itself.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Proposed first-class Model Registry (ModelId, ModelType, SemanticDomain, Version, Parameters, Assumptions, ApplicabilityDomain, InputSchema, OutputSchema, InferenceMethod, Calibration, ValidationEvidence, ErrorBounds, Uncertainty, KnownFailures, Owner, LifecycleStatus) called 'probably one of the biggest architectural additions from this book'; every model carries an explicit ApplicabilityDomain (the falling-body-model-only-applies-until-landing example) as core architecture, not documentation; distinguishes a Knowledge Object (e.g. Claim X) from a Knowledge Model (how X is generated/supported/updated/validated/predicted/used), making KnowledgeOS ultimately a 'Model-of-Models.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0465] §"model omission must become explicit KnowledgeOS metadata. ... ModelBoundary, ModelScope, ModelAssumption, ModelExclusion, ModelApproximation, ModelApplicability."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0465] §"model omission must become explicit KnowledgeOS metadata. ... ModelBoundary, ModelScope, ModelAssumption, ModelExclusion, ModelApproximation, ModelApplicability."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0940`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0465, S0465, S0465, S0467, S0467, S0940 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0465, S0465, S0465, S0467, S0467, S0940 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0465, S0940 |
| examples | PRESENT | S0465, S0467, S0940 |
| warnings | PRESENT | S0465 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0465]` types=[FORMALIZATION, WARNING] scope=OBJECT — "Zero Lens applied to mathematical modelling: since models are selective representations, what a model deliberately omits must become explicit metadata (ModelBoundary/Scope/Assumption/Exclusion/Approximation/Applicability), not left implicit." (anchor: "model omission must become explicit KnowledgeOS metadata. ... ModelBoundary, ModelScope, ModelAssumption, ModelExclusion, ModelApproximation, ModelApplicability.")
- `[S0465]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "ModelApplicability elevated to core architecture (not documentation), illustrated by an agent reporting an ArchitectureComplianceModel v2.1 result with explicit applicability scope, evidence count, inference method, posterior, applicability status, model uncertainty, known exclusions, and partial assurance -- described as 'that is KnowledgeOS.'" (anchor: "a model only applies inside certain parameter ranges ... the falling-body model only applies until landing. ... ModelApplicability: domain, parameter constraints, temporal bounds, environmental con...")
- `[S0465]` types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Distinguishes Knowledge Object (a specific epistemic item, e.g. Claim X) from Knowledge Model (the model governing how that kind of claim is generated/supported/updated/validated/predicted/used), leading to a top-level Model Registry containing Domain/Epistemic/Decision models each further split into Causal/Statistical/Optimization model families -- KnowledgeOS becomes 'a Model-of-Models', called a major architectural evolution." (anchor: "A Knowledge Object might be: Claim X. A Knowledge Model explains: how Claim X is generated, supported, updated, validated, predicted, and used. ... KnowledgeOS therefore becomes a Model-of-Models.")
- `[S0467]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Contextual model selection formalized as a function of context/domain/evidence/temporal-structure, not one global choice; a worked model_registry YAML assigns different baseline/escalation model pairs per KnowledgeOS concern (architecture assessment: HMM->HSMM; governance lifecycle: deterministic only; operational health: HSMM; semantic identity: deterministic SNF; evidence admissibility: rule engine; long-term knowledge evolution: HSMM->Bayesian)." (anchor: "we should not use HSMM everywhere...contextual model selection...M* = f(Context, Domain, Evidence, TemporalStructure), not M* = HSMM globally. ... model_registry: architecture_assessment: baseline ...")
- `[S0467]` types=[FORMALIZATION, CONSTRAINT] scope=OBJECT — "Moksha-derived model-drift detection via KL divergence between current and historical observation distributions triggers model reassessment; the resulting model hierarchy is explicitly 'self-optimizing, not self-certifying' -- external governance remains outside the inference loop." (anchor: "Don't preserve the previous model merely because it exists. ... periodically test P_current(O) against P_historical(O). If KL(P_new||P_old) > tau, trigger model reassessment. ... self-optimizing mo...")
- `[S0940]` types=[FORMALIZATION] scope=OBJECT — "Model revision M_t -> M_{t+1} requires each model to carry a ModelVersion so a prediction identifies Prediction(ModelVersion=v) -- 'otherwise we cannot reconstruct why the prediction was made'. Defines prediction lineage as Prediction=(ModelVersion, InputState, EvidenceState, Timestamp), enabling later outcomes to be compared against the correct model." (anchor: "Model versioning and prediction lineage: Prediction=(ModelVersion,InputState,EvidenceState,Timestamp)")
- `[S0940]` types=[PRINCIPLE, EXAMPLE] scope=OBJECT — "If Model_1 relied on assumption A and new evidence establishes not-A, Model_1 may become Invalid -- but predictions it generated remain historical artifacts, marked ModelLaterInvalidated=True rather than erased. This is essential for auditability: 'why did KnowledgeOS make this recommendation in March' is answered using K_March and M_March even if the model is obsolete today." (anchor: "Model invalidation does not erase predictions; historical auditability using K_March/M_March")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
