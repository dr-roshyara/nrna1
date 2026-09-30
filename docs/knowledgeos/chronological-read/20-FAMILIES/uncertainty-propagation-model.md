# uncertainty-propagation-model

**Scope(s):** THEORY-LEVEL · **Row count:** 60 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** U_D, U_K, U_M, U_O, U_P, Var(Z), sigma_Y · **Aliases:** Step 82 uncertainty model, Uncertainty Propagation
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0024`, scope `THEORY-LEVEL`: Step 82: variance/interval propagation of uncertainty through the Observation->Evidence->Knowledge->Model->Prediction->Decision pipeline; covers linear/nonlinear (delta method) propagation, correlation, Bayesian updating, confidence-vs-probability, calibration, decision sensitivity/robustness, aleatory-vs-epistemic and parameter-vs-model uncertainty, uncertainty budgets, and uncertainty-impact propagation graphs.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0982] §"We now test whether our mathematical model remains correct when uncertainty passes through multiple layers ... Observation→Evidence→Knowledge→Model→Prediction→Decision. Uncertainty must propagate through reasoning; it must never disappear merely because the representation changed."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0982] §"82.13 — Nonlinear transformations ... Y=f(X). Var(Y)≈[f'(E[X])]²Var(X). This is the delta method."
- CANDIDATE-FORMAL-BIRTH: [S0982] §"82.3 — Simple deterministic transformation ... Y=X+c, then σ_Y=σ_X. 100±5 → 110±5."
- CANDIDATE-OPERATIONAL-BIRTH: [S0982] §"82.2 — Experiment 1: uncertainty deletion ... X=100±5. Y=X+10. System stores Y=110. Expected: UncertaintyLossDetected. Result: PASS"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0982. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S0982), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982 |
| type_signature | PRESENT | S0982, S0982, S0982, S0982 |
| invariants | PRESENT | S0982 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982 |
| examples | PRESENT | S0982 |
| warnings | PRESENT | S0982, S0982, S0982 |
| experiments | PRESENT | S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982, S0982 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0982] (PRINCIPLE/ARGUMENT) States the step's central principle: uncertainty must propagate through the reasoning pipeline (Observation→Evidence→Knowledge→Model→Prediction→Decision) and must never disappear merely because representation changed.
- [S0982] (DEFINITION/ARGUMENT) Argues a measured quantity with known measurement uncertainty (X=100, σ_X=5) should be represented as X≈100±5 under an explicit uncertainty model, not as a bare point value.
- [S0982] (FORMALIZATION/ARGUMENT) Shows negative correlation can partially cancel uncertainty in a difference: Var(X-Y)=Var(X)+Var(Y)-2Cov(X,Y); correlation's effect on uncertainty is transformation-dependent, not uniformly increasing.
- [S0982] (ARGUMENT/LIMITATION) Argues that strongly nonlinear transforms (e.g. Y=e^X) turn symmetric input uncertainty into asymmetric output uncertainty, so mean±σ is not universally a sufficient representation.
- [S0982] (FORMALIZATION/ARGUMENT) Notes that although the decision rule D=A if P(H)>0.8 else B is itself deterministic given the model, small changes in P(H) near the 0.8 threshold can flip the decision.
- [S0982] (DEFINITION/ARGUMENT) Defines a decision boundary θ=0.8 and argues that near the boundary, small uncertainty in P(H) can have disproportionately large decision impact.
- [S0982] (ARGUMENT) Explains the practical stakes of the aleatory/epistemic distinction: epistemic uncertainty can be reduced by more evidence, while aleatory/intrinsic uncertainty may not be eliminated by more observation.
- [S0982] (WARNING/ARGUMENT) Warns against naively multiplying stage-wise confidence scores (e.g. 0.9^4 across four pipeline stages) without knowing what each confidence measure actually means semantically.
- [S0982] (WARNING/ARGUMENT) Illustrates false precision: a qualitative input ('roughly 80%') rendered as an overly precise numeric output (0.813742) misrepresents the underlying information's actual precision.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

All 60 rows come from a single source document (S0982, Step 82 of the phase_measure_theory
sequence). The document is itself organized as a sequence of ~30 numbered "Experiments,"
each pairing a formal/definitional claim with a PASS-verdict test. Rows are grouped below
into 9 content themes following that internal sequence; full verbatim text for every row
remains in `03-CONTRIBUTIONS.jsonl`. All rows cite [S0982].

### Theme 1 — Central principle and linear propagation rules (9 rows condensed)
States the step's central principle: uncertainty must propagate through the reasoning
pipeline (Observation to Evidence to Knowledge to Model to Prediction to Decision) and
must never disappear merely because a representation omits it. Argues a measured quantity
should be represented with its uncertainty explicitly (X≈100±5), not as a bare point
value (Experiment 1: dropping stored uncertainty triggers UncertaintyLossDetected, PASS).
Formalizes and numerically confirms three linear propagation rules: additive shift leaves
sigma unchanged (Experiment 2, PASS); multiplicative scaling by an exact scalar scales
sigma by the same factor (Experiment 3, PASS); and for independent variables, variances
add under summation (Experiment 4, PASS).

### Theme 2 — Correlation and covariance effects (4 rows condensed)
Warns that when Cov(X,Y)!=0, independence cannot be assumed automatically, since
Var(X+Y) then includes a covariance term (linked explicitly to prior evidence-independence
work). Experiment 5 confirms that assuming independence for two measurements from the
same sensor yields PotentialUnderestimatedUncertainty (PASS). Shows negative correlation
can partially cancel uncertainty in a difference, so correlation's effect is
transformation-dependent, not uniformly increasing. Experiment 6 confirms that assuming
independence for strongly correlated measurement errors produces IncorrectVariance
(PASS).

### Theme 3 — Nonlinear propagation and richer representations (7 rows condensed)
Introduces the delta method (Var(Y)≈[f'(E[X])]^2 Var(X)) for Y=f(X) under small
uncertainty, with a worked Y=X^2 example. Experiment 7 confirms that ignoring delta-method
scaling for a nonlinear transform is FalsePrecision (PASS). Argues strongly nonlinear
transforms (e.g. Y=e^X) turn symmetric input uncertainty into asymmetric output
uncertainty, so mean+/-sigma is not universally sufficient; Experiment 8 confirms
representing such a case as a symmetric interval is InvalidRepresentation (PASS).
Proposes moving to a full-distribution representation P(X) with P(Y)=integral
P(Y|X)P(X)dX; Experiment 9 shows two variables with identical mean/variance but different
shapes demonstrate that mean/variance alone may not preserve enough information (PASS).

### Theme 4 — Bayesian distinctions: prior/posterior, confidence, calibration (6 rows condensed)
States Bayes' rule and requires KnowledgeOS to distinguish Prior from Posterior
explicitly; Experiment 10 confirms overwriting a prior with a posterior while losing the
evidence relationship is ProvenanceLoss (PASS). States as essential that a confidence
score does not automatically equal a probability (Confidence != Probability); Experiment
12 confirms converting an LLM's self-reported confidence directly into P(H) is a
SemanticError unless the confidence measure is explicitly calibrated (PASS). Defines
calibration (a well-calibrated P(H)=0.8 prediction should be true in ~80% of comparable
cases empirically); Experiment 13 confirms an AI whose 0.9-confidence predictions are
correct only 65% of the time is flagged ConfidenceCalibrationProblem (PASS).

### Theme 5 — Decision sensitivity near thresholds (6 rows condensed)
Notes that although a threshold decision rule (D=A if P(H)>0.8 else B) is deterministic
given the model, small changes in P(H) near the threshold can flip the decision;
Experiment 14 confirms P(H) moving from 0.79 to 0.81 across theta=0.8 demonstrates
DecisionSensitivity=True (PASS). Defines a decision boundary theta=0.8 and argues that
near the boundary, small uncertainty in P(H) has disproportionate decision impact;
Experiment 15 confirms declaring DecisionCertain when an uncertainty band straddles the
boundary is FalseCertainty (PASS). Proposes representing P(H) as an interval, with
DecisionUndetermined as the correct status when the boundary falls inside that interval;
Experiment 16 confirms this DecisionStatus=Sensitive/Undetermined behavior (PASS).

### Theme 6 — Monte Carlo propagation, aleatory vs epistemic uncertainty, uncertainty budgets (7 rows condensed)
Proposes Monte Carlo simulation (sampling X_i, computing D_i=f(X_i), estimating P(D=A) by
frequency) to reveal how often a decision changes under plausible uncertainty; Experiment
17 confirms 10,000 simulations giving A:72%/B:28% should be represented as P(D=A)≈0.72,
not collapsed to a bare D=A (PASS). Distinguishes aleatory uncertainty (intrinsic
variability) from epistemic uncertainty (lack of knowledge), noting they behave
differently; Experiment 20 confirms mislabeling partly-epistemic demand-variation
uncertainty as pure aleatory "data noise" is an UncertaintyClassificationError (PASS).
Explains the practical stakes: epistemic uncertainty is reducible by more evidence,
aleatory uncertainty may not be; Experiment 21 confirms more data collection on inherently
stochastic demand cannot eliminate ResidualAleatoryUncertainty (PASS). Introduces the
notion of an uncertainty budget, decomposing a decision's uncertainty into per-component
contributions; Experiment 22 confirms an uncertainty budget attributing 80% of decision
uncertainty to one parameter implies ObservationPriority should focus there (PASS).

### Theme 7 — Uncertainty impact graph and downstream dependency tracking (4 rows condensed)
Proposes an uncertainty-propagation/impact graph (O_1 to K_1 to M to P to D) enabling
determination of which downstream decisions are affected when uncertainty enters at a
given observation, named UncertaintyImpact; Experiment 27 confirms that when an
observation is found unreliable, the system must be able to discover the full downstream
AffectedDecisionSet (PASS). Names a new capability Impact(O_i) -- determining which
knowledge, models, decisions, policies or actions materially depend on a given
observation -- described as much stronger than ordinary document search; Experiment 28
confirms that revoking a critical source and identifying 47 downstream claims / 12
affected decisions should trigger a RevalidationWorkflow (PASS).

### Theme 8 — Precision-vs-accuracy pitfalls (7 rows condensed)
Warns against naively multiplying stage-wise confidence scores across a pipeline without
knowing what each confidence measure semantically means; Experiment 29 confirms
multiplying undefined confidence scores is an UnsupportedProbabilityCalculation (PASS).
Illustrates false precision: rendering a qualitative input ("roughly 80%") as an overly
precise numeric output misrepresents the underlying information's actual precision;
Experiment 30 confirms converting a qualitative source into an uncalibrated precise number
is FalsePrecision (PASS). States Precision != Accuracy: a highly precise-looking number
can still be badly inaccurate; Experiment 31 confirms a model prediction with high
numerical precision but low historical calibration accuracy demonstrates
HighNumericalPrecision-but-LowPredictiveAccuracy (PASS). States the design principle that
representational precision must not exceed epistemically justified precision, proposed as
a formal KnowledgeOS design principle.

### Theme 9 — Decision robustness and closing synthesis (10 rows condensed)
Extends the earlier True/False/Unknown model with decision-level uncertainty states:
Determinate, Sensitive, Underdetermined, Robust; Experiment 32 confirms a decision winning
in 95% of plausible states should be labeled RobustlyA rather than an unqualified A
(PASS). Formally defines decision robustness (A is robust over uncertainty set U if
D(u)=A for all u in U), stronger than merely D(E[u])=A; Experiment 33 confirms an
expected-value analysis selecting A while some plausible values give D=B shows A is not
robust by this definition (PASS). Enriches Step 76's Decision=f(Knowledge,DecisionModel,
Policy) formula to Decision=f(Knowledge,Uncertainty,DecisionModel,Policy,Context), noting
Knowledge itself contains uncertainty. Restates the full pipeline with a parallel
uncertainty-propagation chain U_O to U_K to U_M to U_P to U_D, requiring not monotonic
increase but that every transformation preserve the semantics of uncertainty. States seven
new named invariants including I_UncertaintyPreservation (transformations must not
silently discard material uncertainty) and I_Dependence (dependencies between uncertain
variables must be represented). Records the step's own verdict as PASS, concluding
KnowledgeOS needs a mathematically meaningful uncertainty model preserved through
transformations, connecting Statistics, Bayesian inference, and Causal modeling. Presents
the cumulative KnowledgeOS pipeline as a closed loop (Reality to Observation to Evidence
to Knowledge to CausalModel to Prediction to DecisionModel to Decision to Authority to
Action to Outcome to Observation), cross-cut by the propagated-uncertainty and
provenance/precision concerns established in this step.

## Notes for P3
- Agent observation: all 60 rows come from a single source document (S0982); the "All rows" section above groups them into 9 content themes following that document's own internal sequence of numbered Experiments, per the P2b instruction for large labels, rather than listing all 60 individually.
- Agent observation: this label is Ungrouped (no P2a group id) despite dense conceptual overlap with decision-robustness, calibration, and provenance material that recurs elsewhere in the corpus (e.g. Confidence != Probability, calibration, FalsePrecision, decision-boundary sensitivity); P3 may want to check whether related labels exist for those sub-concepts that P2a's mechanical signals simply did not catch.

