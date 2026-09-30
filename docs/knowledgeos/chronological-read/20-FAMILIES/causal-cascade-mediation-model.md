# causal-cascade-mediation-model

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** A→B→C→D, Failure=A∧B, Reach_C(A) · **Aliases:** Causal cascade / mediation / interaction model
**Candidate group membership (NOT an identity claim):**
- **G0197** [`causal-cascade-mediation-model` · `causal-reasoning-assurance-layer`] — explicit agent-stated uncertainty: 'causal-cascade-mediation-model' POSSIBLY relates to 'causal-reasoning-assurance-layer' (batch B0023). Note: Step 44's treatment of multi-step causal cascades: causal reachability Reach_C(A), direct-vs-indirect effects, path-multiplication attribution complexity, mediation, conjunctive/interaction causation, common-mode failure, and the DDD-boundary-as-risk-containment-boundary argument.
- **G1455** [`causal-cascade-mediation-model` · `dynamic-causal-system-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1456** [`causal-cascade-mediation-model` · `feedback-loop-stability-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0023, scope OBJECT (relation_to_existing: POSSIBLY:causal-reasoning-assurance-layer): Step 44's treatment of multi-step causal cascades: causal reachability Reach_C(A), direct-vs-indirect effects, path-multiplication attribution complexity, mediation, conjunctive/interaction causation, common-mode failure, and the DDD-boundary-as-risk-containment-boundary argument.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0939 §"Causal cascade, reachability Reach_C(A), and direct-versus-indirect effect distinction"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0939 §"Causal cascade, reachability Reach_C(A), and direct-versus-indirect effect distinction"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0976. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0939, S0976 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0976 |
| dependencies | PRESENT | S0939, S0976 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0939 |
| examples | PRESENT | S0939 |
| warnings | PRESENT | S0939 |
| experiments | PRESENT | S0939, S0976 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S0939] types=['FORMALIZATION', 'DISTINCTION'] scope=OBJECT — "Given A->B->C->D, A can indirectly influence D (a causal cascade); defines causal reachability Reach_C(A) as the set of states potentially affected through causal paths, with D in Reach_C(A) meaning D is causally reachable from A under the model. Distinguishes DirectCause from IndirectCause (e.g. A->C via A->B->C is indirect), and notes preserving the complete causal path A->B->C->D enables 'why did this happen' queries -- but causal paths can multiply (e.g. A affecting D via both B and C), creating attribution complexity." (anchor: "Causal cascade, reachability Reach_C(A), and direct-versus-indirect effect distinction")
- [S0939] types=['PRINCIPLE', 'FORMALIZATION', 'EXAMPLE'] scope=OBJECT — "The total effect of A on D may contain DirectEffect + IndirectEffects, which should not automatically be treated as additive without a valid causal model. If A->M->Y, M is a mediator, useful for root-cause analysis; but root cause is not always one node -- A1,A2,A3 may jointly produce Failure, so RootCause may be a set/causal structure. Conjunctive causation: Failure=A and B where neither alone is sufficient. Statistically, Y=b0+b1 A+b2 B+b3 AB with b3!=0 means the effect of A depends on B (causal interaction under identification assumptions); engineering example: a deployment configuration safe with Database=A alone or Network=B alone but unsafe when both hold together." (anchor: "Causal contribution is not automatically additive; mediation; root cause may be a set, not one node; conjunctive causation and interaction effects")
- [S0939] types=['FORMALIZATION', 'WARNING'] scope=OBJECT — "If A->B and B changes the probability of a future action A' (B->A'), then A->B->A' is a second-order effect; propagating further (A->B->C->D) generally increases model uncertainty, so CausalConfidence should not necessarily remain constant with causal distance. Probability chaining example: P(B|A)=0.9, P(C|B)=0.8 gives P(C|A)~=0.72 only under independence-like assumptions; if relationships are dependent or model assumptions differ, the multiplication is invalid -- 'propagation requires assumptions'. For a deterministic transformation Y=f(X), parameter uncertainty in X propagates to Y via Var(Y)~=(J_f) Sigma_X (J_f)^T under differentiability assumptions, but model uncertainty (competing f_1,f_2) must propagate separately from parameter uncertainty." (anchor: "Second- and third-order effects; causal confidence attenuates with causal distance and propagation requires assumptions")
- [S0939] types=['FORMALIZATION', 'WARNING', 'EXTENSION'] scope=OBJECT — "For a failure chain A->B->C->D with per-transition propagation probabilities p_i, P(D|A)=prod p_i under a simplifying independence assumption -- but correlated failures may make this substantially different. Common-mode failure: if A,B,C all depend on X, failure of X can affect all three simultaneously ('another form of hidden dependence'); KnowledgeOS should model CommonCause or it may underestimate systemic risk. Resilience is modeled via PropagationProbability and ContainmentBoundary; argues a good DDD bounded-context boundary reduces CausalPropagation, so a DDD Boundary is not merely organizational but also a RiskContainmentBoundary -- Conway/DDD boundaries influence Communication, Dependency, CausalPropagation and FailurePropagation together, so architectural boundaries can be partly evaluated through causal graphs." (anchor: "Cascading failure probability, common-mode failure, and DDD boundaries as risk-containment boundaries")
- [S0939] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Runs twelve falsification tests, all PASS: (1) an action changes state and future available actions => future action space recomputed; (2) a feedback loop amplifies errors => potential instability detected; (3) a feedback loop dampens errors => stabilizing behavior recognized; (4) an action has a delayed effect => causal model preserves the delay; (5) a causal cascade contains multiple paths => direct/indirect effects remain distinguishable; (6) two failures share a common cause => not treated as independent failures; (7) an action is safe immediately but unsafe over a longer horizon => safety evaluation depends on horizon; (8) an action creates an unsafe reachable state => decision gate blocks it under the relevant safety policy; (9) an intervention produces an unexpected second-order effect => outcome recorded and causal model updated, original history unchanged; (10) a hidden state is inferred from observations => state estimate retains uncertainty; (11) two causal models produce different long-term predictions => model uncertainty remains explicit; (12) a delayed feedback controller repeatedly overreacts => oscillation/instability detected as a system-level phenomenon rather than attributed to isolated decisions." (anchor: "Twelve falsification experiments for Step 44 dynamic-causal model (all PASS)")
- [S0939] types=['RESTATEMENT', 'PRINCIPLE'] scope=THEORY-LEVEL — "Declares STEP 44 -- PASS ('extended KnowledgeOS from static to dynamic causal reasoning') and restates seven core principles: a decision changes the future state space; feedback can amplify or dampen errors; causal cascades must preserve direct, indirect, and interacting effects; common causes destroy naive independence; safety is often horizon-dependent; delayed effects are part of causality; an observed outcome must feed back into knowledge, not rewrite the historical decision." (anchor: "Step 44 verdict and seven core principles")
- [S0976] types=['FORMALIZATION', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "States correlated risks can produce P(A∩B) >> P(A)P(B), so risk cannot always be summed independently. Experiment 14: assuming independent agent risks when they actually share infrastructure yields CorrelationRisk -- PASS. Restates common-mode failure at organizational scale (A1,A2,A3 all depending on Infrastructure_X); experiment 15: three seemingly independent agents sharing one database all failing together yields SystemicDependencyDetected -- PASS. Uses the existing dependency graph G_D for systemic-risk analysis: high node Centrality suggests high failure impact -- experiment 16 detects a hidden central dependency (twenty agents all depending on Service_X) as CriticalDependency -- PASS. But warns Centrality != Risk: a highly connected but redundant node need not be risky -- Risk requires Dependency + FailureProbability + Impact + Recoverability. Experiment 17: a high-centrality Service_X with three redundant replicas yields CentralityHigh but only moderate SystemicRisk -- PASS." (anchor: "Systemic risk under correlation; common-mode risk via dependency-graph centrality; Centrality != Risk")

## Notes for P3
None beyond what is recorded above.
