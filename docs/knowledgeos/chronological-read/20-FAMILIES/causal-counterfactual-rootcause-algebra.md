# causal-counterfactual-rootcause-algebra

**Scope(s):** OBJECT · **Row count:** 47 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `CK=(Cause,Effect,Model,Evidence,Identification,Assumptions,Estimate,Uncertainty,Validity)`, `P(Y|do(X=x))`, `do(X=x)`, `tau=Y(1)-Y(0)` · **Aliases:** `Causality, Counterfactuals, Interventions and Root-Cause Knowledge`
**Candidate group membership (NOT an identity claim):**
- G0164: [`causal-counterfactual-rootcause-algebra` · `causal-dependency-counterfactual-reasoning`] — explicit agent-stated uncertainty: 'causal-counterfactual-rootcause-algebra' POSSIBLY relates to 'causal-dependency-counterfactual-reasoning' (batch B0022). Note: S0908's Step 25P: Pearl-style causal machinery (do-calculus, ATE, potential outcomes, identifiability) fully worked with falsification tests and DDD placement; extends B0021's causal-dependency-counterfactual-reasoning (S0870/Step 14) with root-cause analysis, necessary/sufficient causation, and integration into Sarathi's decision theory.
- G0864: [`causal-counterfactual-rootcause-algebra` · `causal-dependency-counterfactual-reasoning`] — labels share the notation 'do(X=x)'
- G1438: [`causal-counterfactual-rootcause-algebra` · `uncertainty-propagation-dependence-epistemic-risk-algebra`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0022, scope OBJECT): S0908's Step 25P: Pearl-style causal machinery (do-calculus, ATE, potential outcomes, identifiability) fully worked with falsification tests and DDD placement; extends B0021's causal-dependency-counterfactual-reasoning (S0870/Step 14) with root-cause analysis, necessary/sufficient causation, and integration into Sarathi's decision theory.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0908] §"TemporalPrecedence(A,B)\not\Rightarrow Causation(A,B) ... temporal ordering alone is insufficient"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0908] §"CausalHypothesis, CausalAssessment, IncidentCauseAnalysis, Experiment ... Incident Management BC ... The generic KnowledgeOS layer provides epistemic infrastructure, while the bounded context defines what cause means operationally"
- CANDIDATE-FORMAL-BIRTH: [S0908] §"X_i=f_i(PA_i,U_i) ... Outage=f(Upgrade,FirewallChange,Load,UnknownFactors) ... genuine causal modeling rather than merely temporal reasoning"
- CANDIDATE-OPERATIONAL-BIRTH: [S0908] §"Falsification tests A-G: temporal ordering->Sequence not Causal; correlation->Association not Causation; controlled intervention->CausalEvidence; confounding structure->PotentialConfounding; unidentifiable effect->Unidentifiable; multiple sufficient causes->MultipleCauses; counterfactual question->C"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0930. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0908 |
| informal_meaning | PRESENT | S0908, S0928, S0930 |
| formal_definition | PRESENT | S0908 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0908, S0928, S0930 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0908, S0928 |
| examples | PRESENT | S0908, S0928, S0930 |
| warnings | PRESENT | S0908, S0928 |
| experiments | PRESENT | S0908 |
| open_questions | PRESENT | S0908, S0917 |

## Rationale
Argues Sarathi's decision-relevant quantity is the interventional P(Outage|do(Upgrade)), not the observational P(Outage|Upgrade), since Sarathi evaluates an action/intervention [S0908]. Lists causal-graph operations (graph traversal, d-separation, causal graph analysis, regression, Bayesian inference, simulation, counterfactual calculations for specified models) as normal-PC computable; large Bayesian networks/Monte Carlo/large causal models may need more compute but 'no theoretical hardware barrier.' [S0908].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
This label has 47 rows — too many to list individually while keeping the file readable. Rows are grouped into content-based themes below; each theme names the rows condensed into it (by position in the ledger-order row list for this label) and its representative source_id(s). Full text for every row is in `03-CONTRIBUTIONS.jsonl`.

**Causal-knowledge hierarchy foundations** (5 rows condensed into this theme; source(s): S0908)
Rows 0-4. States temporal precedence does not imply causation (upgrade-then-outage example); defines a four-level causal-knowledge hierarchy of increasing epistemic strength (Sequence, Association, CausalHypothesis, CausalAssessment); warns an LLM's narrative causal claim must be typed as CausalHypothesis, not silently upgraded; introduces the structural causal model X_i=f_i(PA_i,U_i); defines the causal graph G=(V,E) as a hypothesis/model relation, not automatically empirical truth.

**Confounding and the root-cause 'earliest event' fallacy** (3 rows condensed into this theme; source(s): S0908)
Rows 5-7. Works the classic ice-cream/drowning/temperature confounding example; formalizes confounding (C→A, C→B producing apparent A-B association); rejects the naive 'earliest preceding event is the root cause' algorithm using a multi-step deployment/drift/firewall-change/outage timeline.

**Pearl's do-calculus and a taxonomy of causal-evidence types** (4 rows condensed into this theme; source(s): S0908)
Rows 8-11. Introduces Pearl's intervention operator do(X=x) and P(Y|do(X=x)) vs. P(Y|X=x); works an example where observational and interventional probabilities diverge because upgrades are non-randomly timed; lists non-intervention causal-evidence sources (observational, controlled experiment, natural experiment, etc.) as a six-value CausalEvidenceType taxonomy with unequal evidential status.

**Treatment effects, potential outcomes, and typed counterfactuals** (5 rows condensed into this theme; source(s): S0908)
Rows 12-16. Defines the average treatment effect via random assignment; defines individual causal effect tau=Y(1)-Y(0) via potential outcomes (the fundamental problem of causal inference — both cannot usually be jointly observed); defines a structured CausalEstimate record; defines the counterfactual query Y_do(X=0) as generally unobservable; states ObservedFact≠Counterfactual, requiring counterfactual statements be typed CounterfactualClaim.

**Structured causal assessment and identification** (4 rows condensed into this theme; source(s): S0908)
Rows 17-20. Rejects a bare causal_confidence score in favor of a structured CausalAssessment=(Model,Assumptions,Data,Estimand,Estimate,Uncertainty); introduces identification (computing P(Y|do(X)) from observational data via adjustment); states non-identifiability should feed Zero=NeedCausalEvidence rather than a manufactured answer; notes multiple plausible causal graphs can yield model-dependent conclusions.

**Root-cause modeling as multi-candidate scoring, not single-answer declaration** (5 rows condensed into this theme; source(s): S0908)
Rows 21-25. Reformulates root-cause analysis as comparing a candidate set by Support(Ci→Failure), forbidding declaring the highest-scoring candidate as definitively THE cause; defines a six-value RootCauseStatus enum; shows that when two causes jointly produce an effect, demanding a single root cause is itself a modeling error (RootCause may be a set); distinguishes necessary from sufficient causation with a worked joint-cause outage example (HighLoad AND ConfigurationError).

**DDD mapping, causal provenance chain, and simulation-vs-empirical evidence** (4 rows condensed into this theme; source(s): S0908)
Rows 26-29. Maps causal concepts onto bounded-context-specific concepts rather than a generic 'Knowledge' aggregate; defines the causal provenance chain Observations→Variables→Causal Model→Identification→Analysis→Causal Assessment; defines SimulatedOutcome as distinct from real-world observation (SimulationEvidence≠EmpiricalEvidence); warns a predicted outcome must not be treated as observed fact.

**Decision-theoretic integration of causal inference** (7 rows condensed into this theme; source(s): S0908)
Rows 30-36. Argues Sarathi's decision-relevant quantity is the interventional P(Outage|do(Upgrade)), not the observational quantity; formally integrates causal inference with decision theory via EU(do(X=x))=Σ_y P(y|do(X=x))U(y); states CausalIdentifiability as a prerequisite for reliable decision computation; works a model-disagreement example (0.05 vs. 0.30) motivating explicit ModelUncertainty handling and interval-valued causal uncertainty; defines a consolidated nine-field CausalKnowledge tuple and a six-value CausalAssertionType taxonomy.

**Step 25P validation, closure, and transition** (7 rows condensed into this theme; source(s): S0908, S0917)
Rows 37-43. Runs seven falsification tests A-G (all PASS); lists causal-graph operations (traversal, d-separation, regression, Bayesian inference, simulation, counterfactual calculation); states the limitation CausalComputation≠CausalTruth since assumptions cannot be self-validated; records the Step 25P self-verdict PASS; states the major architectural result Evidence→CausalModel→OutcomePrediction→Decision; poses the model-fallibility open question transitioning to Step 25Q, then closes with a further open question transitioning to Step 25Z (Action, Intervention, Control, Risk, Feedback).

**Later reinforcement from Step 33** (2 rows condensed into this theme; source(s): S0928)
Rows 44-45. Restates association≠causation as prevention of a major AI reasoning error and requires explicit representation of causal identifiability given observationally indistinguishable structures.

**Root epistemic cause** (1 rows condensed into this theme; source(s): S0930)
Row 46. Defines root epistemic cause with three worked example root-cause categories.


## Notes for P3
Carries 3 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
