# uncertainty-propagation-dependence-epistemic-risk-algebra

**Scope(s):** OBJECT · **Row count:** 68 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Risk(a)=E[L(a,Y)]`, `U_D=U_measurement+U_sampling+U_model+U_state`, `UncertaintySemantics(Y)=Transform(UncertaintySemantics(X),Assumptions(f),Model(f))`, `Var(Y)~=J Sigma J^T` · **Aliases:** `Uncertainty Propagation, Dependence, Correlation, Error Propagation and Epistemic Risk`
**Candidate group membership (NOT an identity claim):**
- G0179: [`decision-theory-value-of-information-algebra` · `uncertainty-propagation-dependence-epistemic-risk-algebra`] — explicit agent-stated uncertainty: 'uncertainty-propagation-dependence-epistemic-risk-algebra' POSSIBLY relates to 'decision-theory-value-of-information-algebra' (batch B0022). Note: S0928's Step 33: formalizes typed uncertainty (measurement/sampling/parameter/model/prediction/epistemic/aleatory), dependence/covariance-aware propagation, provenance-as-dependence-structure, Bayesian/frequentist/interval/set-valued representations, model and causal-structural uncertainty, Monte Carlo limits, confidence inflation, assumption-sensitivity graphs, and epistemic risk as consequence-weighted uncertainty. Its own opening places it immediately after Step 32 (S0926), but it was processed after Step 34 (S0927) in this batch's file order, and its closing section previews Step 34 content already extracted.
- G1438: [`causal-counterfactual-rootcause-algebra` · `uncertainty-propagation-dependence-epistemic-risk-algebra`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1439: [`epistemic-algebra-type-closure-composition-algebra` · `uncertainty-propagation-dependence-epistemic-risk-algebra`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0022, scope OBJECT): S0928's Step 33: formalizes typed uncertainty (measurement/sampling/parameter/model/prediction/epistemic/aleatory), dependence/covariance-aware propagation, provenance-as-dependence-structure, Bayesian/frequentist/interval/set-valued representations, model and causal-structural uncertainty, Monte Carlo limits, confidence inflation, assumption-sensitivity graphs, and epistemic risk as consequence-weighted uncertainty. Its own opening places it immediately after Step 32 (S0926), but it was processed after Step 34 (S0927) in this batch's file order, and its closing section previews Step 34 content already extracted.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0928] §"Confidence_total = Confidence_1 x Confidence_2 x Confidence_3 ... That is generally wrong"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0928] §"Y=X+epsilon ... E[epsilon]=0 and Var(epsilon)=sigma^2 ... those assumptions themselves require justification"
- CANDIDATE-OPERATIONAL-BIRTH: [S0928] §"Experiments A-E: shared-source evidence naively independent->DependenceDetected/IndependenceUnknown PASS; interval->uniform conversion without assumption->InvalidConversion PASS; unknown distribution fed to Monte Carlo->NoSimulation PASS; correlated inputs treated independent->DependenceWarning PASS"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0928. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0928 |
| informal_meaning | PRESENT | S0928 |
| formal_definition | PRESENT | S0928 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0928 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0928 |
| examples | PRESENT | S0928 |
| warnings | PRESENT | S0928 |
| experiments | PRESENT | S0928 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Formalizes a minimax robust-decision alternative for insufficiently justified probability models [S0928].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
This label has 68 rows — too many to list individually while keeping the file readable. Rows are grouped into content-based themes below; each theme names the rows condensed into it (by position in the ledger-order row list for this label) and its representative source_id(s). Full text for every row is in `03-CONTRIBUTIONS.jsonl`.

**Rejecting naive confidence multiplication; the aleatory/epistemic split** (3 rows condensed into this theme; source(s): S0928)
Rows 0-2. Rejects naive multiplication of stage confidences as generally wrong; distinguishes at least seven kinds of uncertainty that must not be collapsed into one number; formalizes the aleatory-vs-epistemic distinction (Randomness≠LackOfKnowledge) with a travel-time/traffic example.

**Measurement-error models and first-order error propagation** (4 rows condensed into this theme; source(s): S0928)
Rows 3-6. Formalizes a basic measurement-error model (flagging that its moment assumptions need justification), generalizes to a preserved measurement model, and formalizes first-order error propagation via the Jacobian and covariance matrix, noting dependence matters.

**Evidence duplication, correlated evidence, and provenance's role** (3 rows condensed into this theme; source(s): S0928)
Rows 7-9. States evidence duplication as a major AI-system risk; formalizes correlated evidence sharing a common source as invalid to treat as independent; states provenance's mathematical role in estimating dependency for correct propagation, called a major result.

**Bayesian updating as a typed operation; frequentist representations and CI misreading** (4 rows condensed into this theme; source(s): S0928)
Rows 10-13. Restates Bayesian updating as requiring explicit provenance/assumptions for prior and likelihood and constrains it to a typed operation needing a valid model, not a universal inference engine; lists frequentist uncertainty representations and warns against reading a confidence interval as a Bayesian probability statement.

**Interval and set-valued uncertainty; forbidding invented distributions; partial identification** (5 rows condensed into this theme; source(s): S0928)
Rows 14-18. Defines interval uncertainty (no distribution required) and generalizes it to set-valued uncertainty over a feasible set; forbids inventing a uniform distribution over a bounded-but-unjustified range (Bounded≠Probabilistically Specified); introduces partial identification and a worked set-valued propagation example.

**Dependency graphs, correlation structure, and the model/parameter/structural distinctions** (6 rows condensed into this theme; source(s): S0928)
Rows 19-24. Defines a dependency-graph structure for propagating uncertainty through a network; formalizes the correlation/covariance matrix (warning that ignoring off-diagonal terms mis-estimates uncertainty) and common-source uncertainty; distinguishes model uncertainty from parameter uncertainty (constraining model averaging to require justification) and from structural/relational uncertainty.

**Causation vs. association, causal identifiability, and interval-propagation examples** (4 rows condensed into this theme; source(s): S0928)
Rows 25-28. Requires distinguishing association from causation as prevention of a major AI reasoning error; requires explicit representation of causal identifiability given observationally indistinguishable structures; works a deterministic linear interval-propagation example and a nonlinear counterexample showing naive endpoint propagation is wrong.

**Monte Carlo limits and the Confidence Inflation failure mode** (4 rows condensed into this theme; source(s): S0928)
Rows 29-32. Defines Monte Carlo propagation for sufficiently specified models but states it cannot manufacture missing information (Computational precision≠Epistemic precision); names 'Confidence Inflation' as a failure mode where a reasoning chain manufactures unwarranted certainty, and states the anti-inflation rule that certainty increase requires evidence or valid inference, not reasoning alone.

**The epistemic type system, explicit per-stage semantics, and Assumption as a first-class object** (9 rows condensed into this theme; source(s): S0928)
Rows 33-41. Requires each reasoning-chain stage to declare explicit, distinct uncertainty semantics; defines UncertaintyType with five example kinds; forbids silent interval-to-probability conversion without an explicit tracked assumption; defines Assumption as a first-class object whose invalidation affects downstream conclusions, extends the dependency graph with assumption nodes, and defines local/global sensitivity analysis to prioritize validation.

**Epistemic risk, risk vs. uncertainty, and decision-theoretic integration** (8 rows condensed into this theme; source(s): S0928)
Rows 42-49. Defines epistemic risk (risk including uncertainty about its own model) and distinguishes risk from uncertainty as consequence-weighted; works an example where identical failure probability yields different risk by consequence severity; formalizes expected-loss decision theory and a minimax robust-decision alternative; states decision quality depends on required (not perfect) epistemic resolution, formalized as evidence-required-by-criticality.

**Ownership roles, uncertainty budgets, and irreducible aleatory variance** (5 rows condensed into this theme; source(s): S0928)
Rows 50-54. Distinguishes domain-owned, statistics-owned, and governance-owned roles KnowledgeOS orchestrates without owning; introduces an uncertainty budget with escalation for critical decisions, rejecting a universal threshold in favor of a context-specific one; distinguishes evidence quality from zero uncertainty given irreducible aleatory variance, with a worked example.

**Evidence-driven epistemic reduction, the more-evidence-more-uncertainty counterexample, honesty** (3 rows condensed into this theme; source(s): S0928)
Rows 55-57. States epistemic uncertainty can be reduced by evidence (bridging to Value-of-Information, Step 34); gives a counterexample where more evidence increases reported uncertainty while representing an epistemic improvement; states an epistemic-honesty principle preferring accurate over artificial certainty.

**Falsification tests A-J and the Step 33 self-verdict** (3 rows condensed into this theme; source(s): S0928)
Rows 58-60. Runs falsification tests A-E and F-J (all PASS) covering evidence-duplication flagging, illegal interval-to-uniform conversion, and revealed-uncertainty-source classification; records the Step 33 self-verdict as PASS.

**Ten boxed principles, the updated architecture diagram, and the Step 29-33 progression** (7 rows condensed into this theme; source(s): S0928)
Rows 61-67. States all ten boxed architectural principles from Step 33 (uncertainty must be explicitly typed; evidence independence never assumed; parameter≠model uncertainty; etc.); presents an updated end-to-end architecture diagram incorporating provenance/dependence and epistemic-claim typing; gives a four-component decomposition of decision uncertainty and four diagnosis questions; formulates a contract that uncertainty semantics must transform explicitly, never silently; restates the Step 29-33 progression as a coherent mathematical development.


## Notes for P3
Carries 3 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
