# model-hypothesis-prediction-revision-algebra

**Scope(s):** OBJECT · **Row count:** 43 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `H->M->P->O->E->Assessment(M)->Revision(M)`, `M=(Variables,Relations,Assumptions,Parameters,Scope,Predictions,Version)`, `P(Mi|D) propto P(D|Mi)P(Mi)`, `Predict(M,X)->Y` · **Aliases:** `Models, Hypotheses, Prediction, Model Selection and Scientific Revision`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0022, scope OBJECT): S0909's Step 25Q: the scientific-method layer treating models as first-class, non-destructively-revisable, versioned artifacts distinct from assertions; extends causal-counterfactual-rootcause-algebra (S0908) and mirrors epistemic-revision-error-taxonomy-algebra's (S0905) revision-as-event-addition architecture at the model level.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0909] §"Observation\rightarrow Hypothesis\rightarrow Model\rightarrow Prediction\rightarrow Test\rightarrow Revision ... the beginnings of a scientific method for engineering knowledge"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0909] §"Observation\rightarrow Hypothesis\rightarrow Model\rightarrow Prediction\rightarrow Test\rightarrow Revision ... the beginnings of a scientific method for engineering knowledge"
- CANDIDATE-FORMAL-BIRTH: [S0909] §"Observation\rightarrow Hypothesis\rightarrow Model\rightarrow Prediction\rightarrow Test\rightarrow Revision ... the beginnings of a scientific method for engineering knowledge"
- CANDIDATE-OPERATIONAL-BIRTH: [S0909] §"Test A model versus assertion PASS. Test B prediction failure should not automatically refute a probabilistic model PASS. Test C assumption violation classify AssumptionViolation not ModelFalse PASS. Test D competing models -> Undetermined PASS. Test E model revision does not erase old model PASS. T"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0940. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0909 |
| Informal meaning | PRESENT | S0909 |
| Formal definition | PRESENT | S0909, S0940 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0940 |
| Dependencies | PRESENT | S0909 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0909, S0940 |
| Examples | PRESENT | S0909 |
| Warnings | PRESENT | S0909, S0940 |
| Experiments | PRESENT | S0909 |
| Open questions | PRESENT | S0909 |

## Rationale
Argues KnowledgeOS 'learning' need not mean LLM retraining — it can mean model revision based on accumulated evidence, improved rules, better evidence, corrected ontology, better causal models, or calibrated predictions — 'a much broader concept of learning.' [S0909].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
### The scientific-method chain, the model lifecycle, the Model tuple, and assumption discipline

7 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Establishes the scientific-method chain Observation->Hypothesis->Model->Prediction->Test->Revision, giving KnowledgeOS 'the beginnings of a scientific method for engineering knowledge'; a causal model is itself a hypothesis and must not silently become Truth [S0909]. A worked example shows a prediction failure does not automatically falsify the model -- six alternative explanations are listed -- so PredictionError≠ModelFalse without further analysis; a five-state model lifecycle (Candidate->Proposed->Testable->Supported->Established, with Refuted/Weakened/Revised branches) is defined, and an assertion (a single proposition) is distinguished from a model (relationships between variables) [S0909]. A seven-field Model tuple M=(Variables,Relations,Assumptions,Parameters,Scope,Predictions,Version) is defined, with ModelAssumptions required to be explicit and first-class -- an unstated assumption C=True means the model's real prediction is A∧C->B, so C being false can explain failure without refuting the model, illustrated by a capacity-scaling model whose CPU-bound assumption is violated by an actual database bottleneck [S0909].

### Prediction as a first-class object, error measures, and calibration

4 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Formalizes Predict(M,X)->Y for both probabilistic (P(Y|X,M)) and deterministic (Y=f_M(X)) models with recorded ModelVersion, defines Prediction as a first-class six-field object (Model,Input,Time,ExpectedOutcome,Uncertainty,Assumptions) worked with a full auditable example record, and defines prediction-error measures (numeric residual, classification indicator, Brier score) enabling calibration evaluation [S0909]. Calibration (repeated 0.8 predictions should be right ~80% of the time) is defined as distinct from accuracy and flagged as mattering for Sarathi's decisions [S0909].

### Model assessment and selection: overfitting, AIC/BIC, and preserving competing models

7 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Rejects a bare scalar model_quality, requiring a six-dimension ModelAssessment (PredictiveAccuracy,Calibration,Robustness,Applicability,Complexity,Stability) since different domains emphasize different dimensions, and defines overfitting (near-zero training error, large new-data error), requiring KnowledgeOS to distinguish HistoricalFit from PredictiveValidity [S0909]. It rejects automatically preferring a more complex model that equally fits historical data, listing five model-selection principles, gives AIC/BIC formulas as relative comparison criteria while warning ModelScore≠Truth, and works a competing-causal-models example requiring both to be preserved rather than deleting the weaker one [S0909]. It defines Compare(M1,M2,D,C)->Assessment with an important third outcome, Indistinguishable, and defines underdetermination: when both competing models explain all observations, EvidenceInsufficientForModelSelection should become another form of Zero [S0909].

### Model revision and falsification: lineage, likelihood-based refutation, and Bayesian comparison

6 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

States model revision, like knowledge revision, is a NewModelEvent, never deletion of the prior model -- explicitly mirroring the 25M revision architecture -- and defines model lineage M1->M2->M3, each version recording predecessor, changed assumptions/parameters/structure, revision reason, and supporting evidence, worked with a firewall-then-workload-interaction example [S0909]. It states model falsification strength depends on the model's own predicted likelihood of the observation P(O|M) -- 'ModelRefutation requires model likelihood, not merely prediction failure' -- defines Bayesian model comparison P(Mi|D)∝P(D|Mi)P(Mi) while warning priors must be explicit and never secretly invented, works an example where nearly-equal posteriors should be reported as ModelsCompeting rather than a declared winner, and defines ensemble/model-averaging as useful under genuine model uncertainty but requiring an explicit probabilistic framework [S0909].

### A dedicated ModelRegistry bounded context, model contracts, and bounded-context-relative meaning

4 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Proposes a dedicated ModelRegistry bounded-context concept containing eight domain object types, with generic KnowledgeOS supplying provenance/epistemic infrastructure while the domain owns model semantics, and defines a six-field ModelContract (InputSchema,OutputSchema,Assumptions,ValidityDomain,Version,EvaluationMethod) analogous to an API contract, required for safe model reuse [S0909]. It reinforces bounded-context-relative model meaning (a security risk model and a hotel revenue model can coexist without a forced universal semantic model), and requires an explicit, documented, versioned ModelOutputContract for cross-bounded-context model-result exchange, worked with a Security->Architecture RiskScore mapping example [S0909].

### The prediction lifecycle, the learning loop, the Zero/Lord connection, and model-failure taxonomy

6 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Defines a six-stage prediction lifecycle producing a feedback loop into model assessment, presents the scientific-learning loop diagram consolidating it into a closed cycle back to the model, and argues KnowledgeOS 'learning' need not mean LLM retraining -- it can mean model revision based on accumulated evidence, improved rules, better evidence, corrected ontology, better causal models, or calibrated predictions -- 'a much broader concept of learning' [S0909]. A worked example connects model failure to the Zero/Lord loop: a repeatedly-failing model missing an important variable produces Zero=MissingVariable, letting Lord request the missing observation, called 'an elegant connection between scientific reasoning and the Zero/Lord/Sarathi loop' [S0909]. It defines a seven-value ModelFailureType taxonomy (more useful than a bare boolean flag) and defines DistributionShift and temporal model drift, requiring a ModelValidityInterval and restating ExpiredModel≠FalseModel -- a model may simply no longer be applicable [S0909].

### Governance versus technical validity, provenance/reproducibility, and the falsification test suite

6 rows condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Restates GovernanceTruth-vs-technical-truth for models: ModelApproval is a governance fact separate from mathematical validity -- TechnicalValidity≠GovernanceApproval, 'exactly the same principle established earlier' -- and requires a full model/prediction provenance chain (PredictionID->ModelVersion->InputEvidence, PredictionResult->Observation) enabling retrospective evaluation, plus prediction reproducibility Prediction=f(ModelVersion,InputSnapshot,Parameters) with RandomSeed recording for stochastic models [S0909]. It runs seven falsification tests (A-G, all PASS) against the model/prediction/revision machinery (Model≠Assertion; a single failed probabilistic prediction should not automatically refute the model; a violated assumption is AssumptionViolation not ModelFalse; indistinguishable competing models yield ModelSelection=Undetermined; revision does not erase the old model; identical ModelVersion+InputSnapshot reproduces the same prediction; model drift invalidates applicability without proving universal falsity), presents the complete scientific inference chain, and delivers the Step 25Q self-verdict PASS: models are first-class, versioned epistemic artifacts, comparable against observations and revisable without destroying historical model states -- 'a major step toward a genuine self-correcting knowledge system' [S0909].

### Closing open question: from prediction to decision theory (value of information)

1 row condensed into this theme (source_ids: S0909; full text in `03-CONTRIBUTIONS.jsonl`).

Closes by posing the next problem -- probability alone cannot determine whether a risk is acceptable without knowing cost/utility ('probability alone does not determine rational action') -- transitioning to Step 25R (Decision Theory, Utility, Risk, Value of Information) and introducing VOI(E)=ExpectedUtility(with E)-ExpectedUtility(now) as potentially explaining why Zero->Lord->Sarathi is 'a principled decision-making system,' not merely an AI workflow [S0909].

### S0940 -- calibration versus causal validity, and catastrophic forgetting versus immutable evidence

2 rows condensed into this theme (source_ids: S0940; full text in `03-CONTRIBUTIONS.jsonl`).

Given predictions and actual outcomes, repeated structured prediction error may trigger ModelReview; if predicted probabilities are systematically too high the model is overconfident and Calibration should be updated, but calibration does not prove causal correctness (Calibration≠CausalValidity, explicitly said to 'preserve Step 43') [S0940]. It warns that a continually learning system M_{t+1}=Learn(M_t,E_{t+1}) risks CatastrophicForgetting (new learning erasing previously valid knowledge), so KnowledgeOS should distinguish ModelLearning from KnowledgeHistory, keeping historical knowledge outside the mutable model -- stating the strong architectural principle that evidence should be immutable while interpretations may evolve (Evidence_E does not change but Interpretation_t(E) can), preventing 'the model changed, therefore the historical evidence changed' [S0940].


## Notes for P3
Almost entirely a single-source document (S0909, 41 of 43 rows), with S0940 as a later, separate two-row addendum on calibration-vs-causal-validity and catastrophic forgetting; the theming above is this agent's content-based grouping of S0909's internal structure, not a P2a-derived signal. My own observation: this label ends on an explicit forward pointer to a Decision Theory / Value-of-Information step (S0909's closing row) that P3 may want to check has its own working_label elsewhere in the corpus.
