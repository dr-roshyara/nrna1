# Step 33 — Uncertainty Propagation, Dependence, Correlation, Error Propagation and Epistemic Risk

We now continue from Step 32.

Step 32 established the **epistemic algebra**:

$$
Evidence
\rightarrow
Assertion
\rightarrow
Model
\rightarrow
Prediction
\rightarrow
Decision
$$

with explicit types, partial operations, provenance, conflict and revision.

Step 33 addresses the next mathematical weakness:

> **If every stage contains uncertainty, how does uncertainty propagate through the knowledge system?**

This is where we must be particularly rigorous.

The naïve approach would be:

$$
Confidence_{total}
=
Confidence_1
\times
Confidence_2
\times
Confidence_3.
$$

That is generally **wrong**.

The correct answer depends on what the uncertainty actually represents and on the dependencies between the quantities.

---

# 33.1 — First principle: uncertainty is not one thing

We need to distinguish at least:

$$
\boxed{
Measurement\ uncertainty
}
$$

$$
\boxed{
Sampling\ uncertainty
}
$$

$$
\boxed{
Parameter\ uncertainty
}
$$

$$
\boxed{
Model\ uncertainty
}
$$

$$
\boxed{
Prediction\ uncertainty
}
$$

$$
\boxed{
Epistemic\ uncertainty
}
$$

$$
\boxed{
Aleatory\ variability
}
$$

These must not automatically be collapsed into one number.

---

# 33.2 — Aleatory versus epistemic uncertainty

A useful distinction is:

### Aleatory variability

Variation inherent in the phenomenon.

Example:

$$
X\sim Distribution.
$$

### Epistemic uncertainty

Uncertainty caused by incomplete knowledge.

Example:

$$
\theta\in[2.0,3.0].
$$

These have different mathematical meanings.

---

# 33.3 — Example

Suppose:

$$
TravelTime
$$

varies because of traffic.

That is aleatory variability.

But suppose we do not know the actual traffic distribution.

That is epistemic uncertainty.

Therefore:

$$
\boxed{
Randomness\neq LackOfKnowledge.
}
$$

This is foundational.

---

# 33.4 — Measurement uncertainty

Suppose the actual value is:

$$
X
$$

but the sensor produces:

$$
Y=X+\epsilon.
$$

Then:

$$
\epsilon
$$

represents measurement error.

We may model:

$$
E[\epsilon]=0
$$

and:

$$
Var(\epsilon)=\sigma^2.
$$

But those assumptions themselves require justification.

---

# 33.5 — Measurement model

More generally:

$$
Y=h(X,\theta,\epsilon).
$$

KnowledgeOS should preserve the measurement model where available.

Otherwise downstream models may treat measured values as exact.

---

# 33.6 — Error propagation

Suppose:

$$
Y=f(X_1,\ldots,X_n).
$$

For small uncertainties, first-order propagation gives approximately:

$$
Var(Y)
\approx
J\Sigma J^T
$$

where:

$$
J=
\left[
\frac{\partial f}{\partial X_1},
\ldots,
\frac{\partial f}{\partial X_n}
\right]
$$

is the Jacobian and:

$$
\Sigma
$$

is the covariance matrix.

This immediately exposes why independent multiplication of "confidence" values is inadequate.

---

# 33.7 — Covariance matters

If:

$$
Cov(X_i,X_j)\neq0,
$$

then uncertainty propagation depends on their relationship.

For two variables:

$$
Var(X+Y)
=
Var(X)+Var(Y)+2Cov(X,Y).
$$

Therefore:

$$
\boxed{
Dependence\ matters.
}
$$

---

# 33.8 — The evidence duplication problem

Suppose:

$$
E_1
$$

and:

$$
E_2
$$

both come from the same underlying report.

Treating them as independent could artificially increase certainty.

This is a major risk in AI systems.

---

# 33.9 — Correlated evidence

Suppose:

$$
E_1=f(S)
$$

and:

$$
E_2=g(S)
$$

where both depend on the same source \(S\).

Then:

$$
E_1\not\perp E_2.
$$

Therefore combining them as independent evidence is invalid.

---

# 33.10 — Provenance becomes mathematically important

This gives a deeper justification for our provenance graph.

Provenance is not merely for audit.

It allows us to estimate:

$$
Dependency(E_i,E_j).
$$

Therefore:

$$
\boxed{
Provenance
\rightarrow
DependenceStructure
\rightarrow
CorrectUncertaintyPropagation.
}
$$

This is a major result.

---

# 33.11 — Bayesian updating

When a suitable probabilistic model exists:

$$
P(H\mid E)
=
\frac{P(E\mid H)P(H)}
{P(E)}.
$$

KnowledgeOS can update beliefs.

But the prior:

$$
P(H)
$$

and likelihood:

$$
P(E\mid H)
$$

must have explicit provenance and assumptions.

---

# 33.12 — Bayesian updating is not automatically appropriate

Not every knowledge problem has:

* a meaningful prior;
* a well-defined likelihood;
* sufficient data.

Therefore:

$$
BayesianUpdate
$$

must be a typed operation requiring a valid probabilistic model.

It should not be the universal inference engine.

---

# 33.13 — Frequentist uncertainty

Other models may use:

* confidence intervals;
* sampling distributions;
* hypothesis tests;
* bootstrap distributions;
* asymptotic approximations.

For example:

$$
\hat\theta
\pm
z_{\alpha/2}SE(\hat\theta).
$$

This represents a different inferential framework.

KnowledgeOS should preserve the framework.

---

# 33.14 — Confidence interval is not probability of the parameter

A standard confidence interval:

$$
[L,U]
$$

should not automatically be interpreted as:

$$
P(L\le\theta\le U)=0.95.
$$

That interpretation is generally Bayesian, not classical frequentist.

This is exactly the kind of semantic precision KnowledgeOS must preserve.

---

# 33.15 — Interval uncertainty

Some knowledge is naturally represented as:

$$
X\in[L,U].
$$

For example:

$$
Latency\in[100ms,150ms].
$$

No probability distribution is required.

---

# 33.16 — Set-valued uncertainty

More generally:

$$
X\in S
$$

where \(S\) is a feasible set.

This is useful when evidence constrains the possibilities without assigning probabilities.

---

# 33.17 — Unknown distribution

We may know:

$$
X\in[0,100]
$$

but have no justified distribution.

The system should not invent:

$$
X\sim Uniform(0,100).
$$

That would be an unsupported assumption.

Therefore:

$$
\boxed{
Bounded\neqProbabilistically\Specified.
}
$$

---

# 33.18 — Partial identification

This leads to an important statistical concept.

Suppose the available evidence does not identify:

$$
\theta
$$

exactly.

Instead:

$$
\theta\in\Theta_I.
$$

Then the correct result is:

$$
\boxed{
PartiallyIdentified.
}
$$

This is stronger than simply saying "uncertain."

---

# 33.19 — Example

Suppose:

$$
10\le X\le20
$$

and:

$$
Y=2X.
$$

Then:

$$
20\le Y\le40.
$$

We have propagated a set-valued constraint without inventing a probability distribution.

---

# 33.20 — Dependency graph for uncertainty

Suppose:

$$
X_1,X_2
\rightarrow
Y
\rightarrow
Z.
$$

KnowledgeOS should preserve:

$$
DependencyGraph.
$$

Then uncertainty can be propagated through the graph.

---

# 33.21 — Correlation matrix

For quantitative models:

$$
\Sigma=
\begin{bmatrix}
\sigma_1^2 & \sigma_{12}\\
\sigma_{12} & \sigma_2^2
\end{bmatrix}.
$$

The off-diagonal terms matter.

Ignoring them can substantially underestimate or overestimate uncertainty.

---

# 33.22 — Common-source uncertainty

Suppose two measurements share calibration error:

$$
X_1=X_1^*+\epsilon_c+\epsilon_1
$$

$$
X_2=X_2^*+\epsilon_c+\epsilon_2.
$$

Then:

$$
Cov(X_1,X_2)>0.
$$

KnowledgeOS needs provenance capable of identifying this common source.

---

# 33.23 — Model uncertainty

Suppose:

$$
M_1
$$

and:

$$
M_2
$$

are both plausible models.

Then uncertainty exists not merely in parameters:

$$
\theta
$$

but in:

$$
M.
$$

Therefore:

$$
P(Y\mid X)
$$

may depend on model selection.

---

# 33.24 — Model averaging

If justified, we may have:

$$
P(Y\mid X)
=
\sum_m
P(Y\mid X,M_m)P(M_m\mid D).
$$

But again, this requires a probabilistic model over models.

We should not automatically average incompatible models.

---

# 33.25 — Structural uncertainty

Some uncertainty arises because we do not know whether a relationship exists.

For example:

$$
X\rightarrow Y?
$$

versus:

$$
X\not\rightarrow Y.
$$

This is structural/model uncertainty.

It is distinct from uncertainty in:

$$
\theta.
$$

---

# 33.26 — Causal uncertainty

Suppose we observe:

$$
X\leftrightarrow Y.
$$

That does not establish:

$$
X\rightarrow Y.
$$

KnowledgeOS must distinguish:

$$
Association
$$

from:

$$
Causation.
$$

This prevents another major AI reasoning error.

---

# 33.27 — Causal model uncertainty

Possible causal graphs:

$$
X\rightarrow Y
$$

or:

$$
Y\rightarrow X
$$

or:

$$
Z\rightarrow X,\quad Z\rightarrow Y.
$$

Observational evidence may not distinguish them.

Therefore:

$$
CausalIdentifiability
$$

must be explicitly represented.

---

# 33.28 — Propagation through a deterministic function

Suppose:

$$
Y=2X+3.
$$

If:

$$
X\in[1,2],
$$

then:

$$
Y\in[5,7].
$$

This is deterministic interval propagation.

No probability is necessary.

---

# 33.29 — Propagation through a nonlinear function

Suppose:

$$
Y=X^2
$$

and:

$$
X\in[-2,3].
$$

Then:

$$
Y\in[0,9].
$$

Naïve endpoint transformation:

$$
[-2^2,3^2]=[4,9]
$$

would be wrong.

This demonstrates that mathematical transformations must respect the structure of uncertainty.

---

# 33.30 — Monte Carlo propagation

For sufficiently specified probabilistic models:

$$
X\sim P_X
$$

and:

$$
Y=f(X),
$$

we can sample:

$$
X^{(1)},\ldots,X^{(N)}
$$

and compute:

$$
Y^{(i)}=f(X^{(i)}).
$$

Then estimate:

$$
P_Y.
$$

This is computationally feasible for many practical problems.

---

# 33.31 — But Monte Carlo cannot create missing information

If:

$$
P_X
$$

is unjustified, Monte Carlo simply produces precise-looking results from an unjustified distribution.

Therefore:

$$
\boxed{
Computational\ precision
\neq
Epistemic\ precision.
}
$$

This must become another KnowledgeOS principle.

---

# 33.32 — Confidence explosion

AI systems often make a dangerous mistake:

$$
Evidence
\rightarrow
0.8
$$

then:

$$
Inference
\rightarrow
0.9
$$

then:

$$
Decision
\rightarrow
0.95.
$$

The final number can become more confident than the underlying evidence warrants.

This is:

$$
\boxed{
Confidence\ Inflation.
}
$$

---

# 33.33 — Anti-inflation rule

Any transformation that increases epistemic certainty must provide a mathematical reason.

For example:

$$
NewEvidence
$$

may justify posterior concentration.

But:

$$
LLMReasoning
$$

alone does not justify:

$$
0.8\rightarrow0.95.
$$

Therefore:

$$
\boxed{
CertaintyIncrease
requires
EvidenceOrValidInference.
}
$$

---

# 33.34 — Uncertainty propagation through a reasoning chain

Suppose:

$$
E
\rightarrow
A
\rightarrow
M
\rightarrow
P
\rightarrow
D.
$$

We should not represent this as:

$$
u_E,u_A,u_M,u_P,u_D
$$

without defining what each \(u\) means.

Instead each stage should declare its uncertainty semantics.

For example:

```text id="u33"
Evidence:
    measurement uncertainty

Assertion:
    interval uncertainty

Model:
    parameter uncertainty

Prediction:
    predictive distribution

Decision:
    expected-loss distribution
```

---

# 33.35 — Typed uncertainty

This suggests:

$$
\boxed{
UncertaintyType
}
$$

should be part of the epistemic type system.

Examples:

$$
U_{prob}
$$

$$
U_{interval}
$$

$$
U_{set}
$$

$$
U_{qualitative}
$$

$$
U_{unknown}.
$$

---

# 33.36 — Illegal uncertainty conversion

Suppose:

$$
X\in[0,10].
$$

It is invalid to silently convert this to:

$$
X\sim Uniform(0,10).
$$

Therefore:

$$
Interval\not\rightarrow Probability
$$

without an explicit modeling assumption.

---

# 33.37 — This is epistemic type safety again

The conversion:

$$
U_{interval}
\rightarrow
U_{prob}
$$

must require:

$$
DistributionAssumption.
$$

That assumption becomes provenance.

---

# 33.38 — Assumption objects

We therefore need:

$$
Assumption
$$

as a first-class object.

For example:

$$
A_1:
X\sim Uniform(0,10).
$$

Then:

$$
DerivedProbability
$$

depends on:

$$
A_1.
$$

If \(A_1\) is challenged, downstream conclusions are affected.

---

# 33.39 — Assumption dependency graph

We therefore extend:

$$
G=(V,E)
$$

with:

$$
Assumption
$$

nodes.

Now:

$$
Assumption
\rightarrow
Model
\rightarrow
Prediction
\rightarrow
Decision.
$$

This is extremely important for impact analysis.

---

# 33.40 — Sensitivity to assumptions

Suppose:

$$
D=f(A_1,A_2).
$$

If small changes in \(A_1\) dramatically alter \(D\), then:

$$
Sensitivity(D,A_1)
$$

is high.

This gives us a mathematical basis for prioritizing validation.

---

# 33.41 — Local sensitivity

For differentiable models:

$$
Sensitivity_i
=
\left|
\frac{\partial D}{\partial A_i}
\right|.
$$

More generally:

$$
S_i
$$

can be defined using finite perturbations.

---

# 33.42 — Global sensitivity

For nonlinear systems, local derivatives may be insufficient.

Methods such as variance decomposition can be used.

Conceptually:

$$
Var(Y)
=
\sum_i Contribution_i
+
Interactions.
$$

This identifies which uncertainties dominate the output.

---

# 33.43 — Epistemic risk

Now we can define:

$$
Risk(D)
=
E[Loss(D,Y)].
$$

But there may be uncertainty over the probability model itself.

Then:

$$
\boxed{
EpistemicRisk
}
$$

includes uncertainty about the model/knowledge used to calculate the risk.

---

# 33.44 — Risk versus uncertainty

They are not identical.

Uncertainty asks:

> What do we not know?

Risk asks:

> What is the consequence of uncertain outcomes?

Therefore:

$$
\boxed{
Risk=f(Uncertainty,Consequences).
}
$$

---

# 33.45 — Same uncertainty, different risk

Suppose:

$$
P(Failure)=0.01.
$$

For a harmless experiment:

$$
Risk
$$

may be negligible.

For a catastrophic system:

$$
Risk
$$

may be unacceptable.

Therefore uncertainty alone does not determine action.

---

# 33.46 — Decision-theoretic formulation

Let actions be:

$$
a\in\mathcal A_d.
$$

Outcomes:

$$
y\in\mathcal Y.
$$

Loss:

$$
L(a,y).
$$

Then expected loss:

$$
EL(a)
=
E[L(a,Y)].
$$

Optimal decision:

$$
a^*
=
\arg\min_a EL(a).
$$

This will become important in later steps.

---

# 33.47 — But uncertainty about the model matters

Suppose:

$$
M_1
$$

and:

$$
M_2
$$

produce different expected losses.

Then:

$$
EL(a\mid M_1)
\neq
EL(a\mid M_2).
$$

Therefore model uncertainty affects the decision.

---

# 33.48 — Robust decision-making

If probability models are not sufficiently justified, we may instead optimize against a set:

$$
\mathcal P
$$

of plausible distributions.

For example:

$$
a^*
=
\arg\min_a
\sup_{P\in\mathcal P}
E_P[L(a,Y)].
$$

This is a robust decision approach.

KnowledgeOS should be able to represent such cases rather than forcing one probability distribution.

---

# 33.49 — Partial knowledge can still support action

This is important.

We do not need:

$$
PerfectKnowledge
$$

before every action.

We need:

$$
SufficientKnowledge
$$

for the decision's risk and constraints.

Thus:

$$
\boxed{
DecisionQuality
depends\ on\ required\ epistemic\ resolution.
}
$$

---

# 33.50 — Resolution must be proportional to consequence

For a trivial decision:

$$
LowRisk
$$

a rough estimate may be enough.

For:

$$
CriticalAction
$$

we may require:

* stronger evidence;
* independent validation;
* narrower uncertainty;
* deterministic verification.

Therefore:

$$
RequiredEvidence
=
f(DecisionCriticality).
$$

---

# 33.51 — This connects DDD and statistics

The domain determines:

$$
DecisionCriticality.
$$

Statistics determines:

$$
Uncertainty.
$$

Governance determines:

$$
RequiredAssurance.
$$

KnowledgeOS orchestrates these but should not own their domain semantics.

---

# 33.52 — Uncertainty budget

We can introduce an:

$$
UncertaintyBudget.
$$

For a critical decision:

$$
U(D)\le U_{max}.
$$

If:

$$
U(D)>U_{max},
$$

the decision requires:

$$
MoreEvidence
$$

or:

$$
HumanReview.
$$

---

# 33.53 — But an uncertainty budget is domain-specific

We should not define a universal:

$$
U_{max}=0.1.
$$

A 10% uncertainty may be acceptable in one context and catastrophic in another.

Thus:

$$
U_{max}(Context,Decision).
$$

---

# 33.54 — Evidence quality versus uncertainty

High-quality evidence does not necessarily eliminate uncertainty.

Suppose the phenomenon itself is stochastic.

Then even perfect measurements leave:

$$
AleatoryVariance.
$$

Therefore:

$$
EvidenceQuality
\neq
ZeroUncertainty.
$$

---

# 33.55 — Perfect evidence can still yield uncertain outcomes

For example:

$$
P(Y=1\mid X)=0.7.
$$

Even with perfect knowledge of the model, the outcome remains stochastic.

Thus:

$$
\boxed{
Uncertainty\ can\ be\ irreducible.
}
$$

---

# 33.56 — Epistemic uncertainty can sometimes be reduced

Additional evidence may reduce:

$$
Var(\theta\mid E).
$$

Or shrink:

$$
\Theta_I.
$$

Therefore:

$$
InformationAcquisition
\rightarrow
UncertaintyReduction.
$$

This is the bridge toward the later Value-of-Information problem.

---

# 33.57 — But more evidence can increase uncertainty

Suppose new evidence reveals that our previous model was oversimplified.

Then:

$$
Uncertainty_{new}
>
Uncertainty_{old}.
$$

This is not necessarily bad.

It may represent **better calibrated knowledge**.

Therefore:

$$
\boxed{
LowerReportedUncertainty
is\ not\ always\ evidence\ of\ better\ knowledge.
}
$$

---

# 33.58 — Epistemic honesty

KnowledgeOS should prefer:

$$
AccurateUncertainty
$$

over:

$$
ArtificialCertainty.
$$

A newly discovered uncertainty is often an epistemic improvement.

---

# 33.59 — Falsification experiment A

Two evidence records originate from the same source.

Naïve system treats them as independent.

Expected KnowledgeOS behavior:

$$
DependenceDetected
$$

or:

$$
IndependenceUnknown.
$$

**PASS.**

---

# 33.60 — Falsification experiment B

Interval:

$$
X\in[0,10].
$$

System attempts:

$$
X\sim Uniform(0,10)
$$

without an assumption.

Expected:

$$
InvalidConversion.
$$

**PASS.**

---

# 33.61 — Falsification experiment C

Unknown distribution is fed into Monte Carlo.

Expected:

$$
NoSimulation
$$

unless a distribution is explicitly specified.

**PASS.**

---

# 33.62 — Falsification experiment D

Two highly correlated inputs are treated as independent.

Expected:

$$
DependenceWarning
$$

or deterministic rejection where required.

**PASS.**

---

# 33.63 — Falsification experiment E

An LLM increases confidence from 0.7 to 0.95 without new evidence.

Expected:

$$
UnsupportedCertaintyIncrease.
$$

**PASS.**

---

# 33.64 — Falsification experiment F

New evidence reveals a previously unknown source of uncertainty.

Expected:

$$
ReportedUncertainty
$$

may increase.

System should not classify this as automatically worse knowledge.

**PASS.**

---

# 33.65 — Falsification experiment G

A decision has low probability of failure but catastrophic loss.

Expected:

Risk analysis identifies potentially unacceptable expected/robust loss.

**PASS.**

---

# 33.66 — Falsification experiment H

Two models produce materially different decisions.

Expected:

$$
ModelUncertainty
\rightarrow
DecisionSensitivity.
$$

**PASS.**

---

# 33.67 — Falsification experiment I

A conclusion depends on assumption \(A_1\).

\(A_1\) is invalidated.

Expected:

$$
Closure(A_1)
$$

identifies affected conclusions.

**PASS.**

---

# 33.68 — Falsification experiment J

A deterministic transformation has exact input.

Expected output uncertainty:

$$
0
$$

only if the transformation itself and inputs are exact.

Otherwise uncertainty must propagate.

**PASS.**

---

# 33.69 — Step 33 verdict

$$
\boxed{
\textbf{STEP 33 — PASS}
}
$$

But this step produced several important architectural rules.

---

# 33.70 — Principle 1: Uncertainty is typed

$$
\boxed{
UncertaintyType
must\ be\ explicit.
}
$$

Probability, interval, set-valued uncertainty, unknown, and qualitative uncertainty are not interchangeable.

---

# 33.71 — Principle 2: Dependence is first-class

$$
\boxed{
Evidence\ independence
must\ never\ be\ assumed\ merely\ from\ separate\ records.
}
$$

Provenance provides part of the dependency structure.

---

# 33.72 — Principle 3: Precision is not knowledge

$$
\boxed{
ComputationalPrecision
\neq
EpistemicPrecision.
}
$$

A simulation with millions of samples does not rescue an unjustified model.

---

# 33.73 — Principle 4: Certainty cannot be manufactured

$$
\boxed{
CertaintyIncrease
requires
Evidence,
ValidInference,
or
ExplicitModelAssumption.
}
$$

---

# 33.74 — Principle 5: Unknown distributions remain unknown

$$
\boxed{
UnknownDistribution
\neq
UniformDistribution.
}
$$

---

# 33.75 — Principle 6: Model uncertainty is real uncertainty

$$
\boxed{
ParameterUncertainty
\neq
ModelUncertainty.
}
$$

Both must be represented.

---

# 33.76 — Principle 7: Risk is consequence-weighted uncertainty

$$
\boxed{
Risk
=
Uncertainty
\times
Consequence
}
$$

is only a conceptual shorthand; formally:

$$
Risk(a)=E[L(a,Y)].
$$

---

# 33.77 — Principle 8: Evidence can reduce or reveal uncertainty

Therefore:

$$
\boxed{
KnowledgeImprovement
\neq
AlwaysLowerUncertainty.
}
$$

Sometimes better knowledge means discovering that we were previously overconfident.

---

# 33.78 — Principle 9: Uncertainty must propagate through dependencies

If:

$$
A\rightarrow B\rightarrow C,
$$

then uncertainty in \(A\) can propagate to \(C\).

But the propagation mechanism depends on:

$$
Model(A,B,C).
$$

There is no universal multiplication rule.

---

# 33.79 — Principle 10: Critical decisions require epistemic thresholds

For a critical action:

$$
U(D)>U_{max}
$$

should lead to:

$$
NoAutomaticExecution.
$$

Possible responses:

$$
AcquireEvidence
$$

$$
RunValidation
$$

$$
HumanReview
$$

or:

$$
ChooseRobustAction.
$$

---

# 33.80 — Updated mathematical architecture

The architecture now becomes:

```text id="arch33"
                         REALITY
                            │
                            ▼
                       OBSERVATION
                            │
                            ▼
                         EVIDENCE
                      ┌─────┴─────┐
                      │           │
                 Provenance   Dependence
                      │           │
                      └─────┬─────┘
                            ▼
                     EPISTEMIC CLAIM
                      ┌─────┼─────┐
                      │     │     │
                    Time  Context  Uncertainty
                      │     │     │
                      └─────┼─────┘
                            ▼
                          MODEL
                     ┌──────┼──────┐
                     │      │      │
                  Params  Structure  Assumptions
                     │      │      │
                     └──────┼──────┘
                            ▼
                        PREDICTION
                            │
                      Uncertainty
                            │
                            ▼
                         DECISION
                       ┌────┴────┐
                       │         │
                    Utility     Risk
                       │         │
                       └────┬────┘
                            ▼
                    ASSURANCE GATE
                            │
                     Constraints
                     Preconditions
                     Epistemic threshold
                            │
                            ▼
                          ACTION
                            │
                            ▼
                         OUTCOME
                            │
                            ▼
                       VALIDATION
                            │
                            ▼
                     KNOWLEDGE REVISION
```

---

# 33.81 — A particularly important mathematical insight

We can now distinguish four different reasons why a decision may be uncertain:

$$
\boxed{
U_D=
U_{measurement}
+
U_{sampling}
+
U_{model}
+
U_{state}
}
$$

This is only schematic; these components cannot always simply be added.

But conceptually the decomposition is powerful.

---

# 33.82 — Four uncertainty questions

For every important conclusion we should ask:

### 1. Did we measure the world accurately?

$$
MeasurementUncertainty
$$

### 2. Did we observe enough?

$$
SamplingUncertainty
$$

### 3. Did we choose the correct model?

$$
ModelUncertainty
$$

### 4. Do we correctly understand the current state/context?

$$
State/SemanticUncertainty
$$

These are different failure modes.

---

# 33.83 — The KnowledgeOS epistemic contract

I would now formulate a strong architectural contract:

> **KnowledgeOS must preserve the semantics, source, dependencies and type of uncertainty whenever knowledge is transformed.**

Formally, for transformation:

$$
f:X\rightarrow Y,
$$

we require:

$$
UncertaintySemantics(Y)
=
Transform(
UncertaintySemantics(X),
Assumptions(f),
Model(f)
).
$$

It may change.

But it must not change **silently**.

---

# 33.84 — Step 33 completion

At this point our mathematical progression is becoming quite disciplined:

$$
\boxed{
Step\ 29:
Consistency
}
$$

$$
\boxed{
Step\ 30:
External\ Validation
}
$$

$$
\boxed{
Step\ 31:
Formalization
}
$$

$$
\boxed{
Step\ 32:
Epistemic\ Algebra
}
$$

$$
\boxed{
Step\ 33:
Uncertainty\ Propagation
}
$$

This is a coherent progression rather than a collection of disconnected ideas.

---

# Step 34 — Information Acquisition and Value of Information

We have now reached the natural next question.

Suppose KnowledgeOS knows:

$$
Decision=D
$$

but uncertainty is too high.

There may be several possible investigations:

$$
I_1,I_2,\ldots,I_n.
$$

Each investigation has:

* cost;
* time;
* uncertainty reduction;
* potential decision impact.

The question becomes:

$$
\boxed{
Which information should KnowledgeOS acquire next?
}
$$

This leads to:

$$
\boxed{
Value\ of\ Information\ (VOI)
}
$$

and ultimately:

$$
\boxed{
NextBestEpistemicAction.
}
$$

The key equation will be conceptually:

$$
VOI(I)
=
ExpectedLoss_{without\ I}
-
ExpectedLoss_{with\ I}
-
Cost(I).
$$

But we must be careful: information has value **only insofar as it can change or improve a decision**.

That will be the mathematical foundation of **Step 34**.
