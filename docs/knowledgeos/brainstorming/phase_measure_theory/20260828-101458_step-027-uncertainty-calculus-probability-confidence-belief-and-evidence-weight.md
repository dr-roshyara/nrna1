# Step 27 — Uncertainty Calculus, Probability, Confidence, Belief, Evidence Weighting and the Mathematics of “Unknown”

We continue from Step 26.

This step is foundational because KnowledgeOS will constantly encounter statements of the form:

* “probably true”
* “high confidence”
* “evidence is weak”
* “we don't know”
* “there are two plausible explanations”
* “the probability is 70%”

These expressions are **not interchangeable**.

As a senior statistician, I would strongly recommend that we **do not introduce one universal `confidence` number** into KnowledgeOS.

Instead, we first define the mathematical objects precisely.

---

# 27.1 — The fundamental distinction

For a proposition \(H\), we can distinguish:

$$
\boxed{Truth}
$$

$$
\boxed{Evidence}
$$

$$
\boxed{Probability}
$$

$$
\boxed{Belief}
$$

$$
\boxed{Confidence}
$$

$$
\boxed{Uncertainty}
$$

These describe different things.

For example:

> “There is a 70% probability that the migration will succeed.”

is a statement about a probability model.

Whereas:

> “The evidence is weak.”

is a statement about the evidential basis.

And:

> “We are not authorized to act.”

is a governance statement.

They should not collapse into one scalar.

---

# 27.2 — Truth is not probability

For a proposition:

$$
H=\text{“Nexus migration will succeed.”}
$$

the actual future outcome will eventually be:

$$
H=True
$$

or:

$$
H=False.
$$

Before the event occurs, however, we may represent uncertainty with:

$$
P(H)=0.7.
$$

The probability describes our uncertainty, not a fractional truth value.

Therefore:

$$
\boxed{
P(H)=0.7
\neq
Truth(H)=0.7.
}
$$

---

# 27.3 — Epistemic uncertainty

Suppose:

$$
H\in\{True,False\}
$$

but we lack sufficient evidence.

Then:

$$
P(H)
$$

may represent uncertainty about \(H\).

This is **epistemic uncertainty**:

> uncertainty because we do not know enough.

More evidence may reduce it.

---

# 27.4 — Aleatoric uncertainty

Some uncertainty is inherent in the process.

For example:

> What will tomorrow's network traffic be?

Even with perfect knowledge of the current state, future traffic may contain genuine variability.

This is often called:

$$
\boxed{
Aleatoric\ uncertainty.
}
$$

More observations may improve the model but cannot necessarily eliminate randomness.

---

# 27.5 — The distinction matters

Suppose:

$$
P(Failure)=0.1.
$$

There are at least two possible interpretations:

### Epistemic

We don't know enough to determine failure.

### Aleatoric

The process itself has approximately 10% failure probability even under perfect information.

Those require different responses.

---

# 27.6 — Measurement uncertainty

Suppose CPU utilization is reported as:

$$
40\%.
$$

The actual value may be:

$$
40\pm 1\%.
$$

That is measurement uncertainty.

It is different from:

$$
P(ServiceFailure).
$$

Therefore:

$$
\boxed{
MeasurementUncertainty
\neq
OutcomeUncertainty.
}
$$

---

# 27.7 — Model uncertainty

Suppose we have:

$$
M_1,M_2,M_3.
$$

All are plausible.

Then uncertainty may arise from:

$$
ModelChoice.
$$

This is **model uncertainty**.

It should not automatically be treated as ordinary sampling uncertainty.

---

# 27.8 — Parameter uncertainty

Suppose model:

$$
Y=\beta_0+\beta_1X+\epsilon.
$$

We don't know \(\beta_1\) exactly.

We estimate:

$$
\hat{\beta}_1.
$$

with uncertainty:

$$
SE(\hat{\beta}_1).
$$

This is parameter uncertainty.

Again, distinct from model uncertainty.

---

# 27.9 — Semantic uncertainty

Suppose a document says:

> “Approved.”

But we don't know whether this means:

* technically approved;
* architecturally approved;
* management-approved;
* conditionally approved.

Then uncertainty exists in:

$$
Meaning(Approved).
$$

This is **semantic uncertainty**.

A probability over the technical outcome does not solve it.

---

# 27.10 — Identity uncertainty

Suppose two systems have:

```text
nexus-prod
```

and:

```text
nexus3
```

and we don't know whether they are the same entity.

Then:

$$
P(Entity_A=Entity_B)
$$

could represent identity uncertainty.

Again, this is not the same as outcome probability.

---

# 27.11 — Temporal uncertainty

Suppose an event occurred:

$$
t\in[10:00,11:00].
$$

We should not arbitrarily select:

$$
t=10:30.
$$

The uncertainty is about time itself.

---

# 27.12 — Causal uncertainty

Suppose:

$$
Deployment
$$

and:

$$
Failure
$$

occur close together.

We may know:

$$
P(Failure\mid Deployment).
$$

But the causal question is:

$$
P(Failure\mid do(Deployment)).
$$

Uncertainty about causal structure is different from uncertainty about the probability distribution.

---

# 27.13 — Therefore uncertainty is a vector

A better conceptual representation is:

$$
\boxed{
U(H)=
(
U_{measurement},
U_{epistemic},
U_{aleatoric},
U_{model},
U_{semantic},
U_{identity},
U_{temporal},
U_{causal}
)
}
$$

Not every proposition needs every component.

---

# 27.14 — Why one confidence score fails

Suppose:

$$
Confidence=0.85.
$$

What does that mean?

Possibilities:

* source is highly reliable;
* model predicts with 85% probability;
* evidence is strong;
* identity is 85% likely;
* human reviewer is confident.

These are completely different.

Therefore:

$$
\boxed{
UniversalConfidenceScore
=
ArchitecturalAntiPattern.
}
$$

---

# 27.15 — Probability

Probability is appropriate when we have a well-defined random quantity or uncertainty model.

For event \(H\):

$$
P(H)\in[0,1].
$$

And:

$$
P(H)+P(\neg H)=1.
$$

But we should only assign probabilities when the semantics are clear.

---

# 27.16 — Probability requires a reference class or model

A statement:

$$
P(Failure)=0.73
$$

is meaningless without knowing:

* population;
* conditions;
* time horizon;
* model;
* data;
* assumptions.

Therefore a probability should carry context.

Conceptually:

$$
P(H\mid C,M,E,t).
$$

---

# 27.17 — Conditional probability

Most KnowledgeOS probabilities should be conditional.

For example:

$$
P(Failure
\mid
Version=3.69,
Load>80\%).
$$

This is much more meaningful than:

$$
P(Failure)=0.1.
$$

---

# 27.18 — Base rates

A classic statistical error is ignoring base rates.

Suppose:

$$
P(SecurityIncident)=0.01.
$$

A detector has:

$$
Sensitivity=0.95
$$

and:

$$
FalsePositiveRate=0.05.
$$

A positive signal does not imply:

$$
P(Incident\mid Positive)=0.95.
$$

We must apply Bayes' theorem.

---

# 27.19 — Bayes' theorem

$$
P(H\mid E)
=
\frac{
P(E\mid H)P(H)
}{
P(E)
}.
$$

This gives us a principled update:

$$
Prior
+
Evidence
\rightarrow
Posterior.
$$

This fits KnowledgeOS naturally.

---

# 27.20 — KnowledgeOS Bayesian update

Conceptually:

$$
Belief_{new}(H)
\propto
Likelihood(E\mid H)
\times
Belief_{old}(H).
$$

But we should not require every KnowledgeOS inference to be Bayesian.

Bayesian reasoning is one mathematical tool among several.

---

# 27.21 — Evidence is not probability

Suppose:

$$
EvidenceStrength=High.
$$

This does not automatically mean:

$$
P(H)=0.95.
$$

Evidence strength depends on:

* relevance;
* independence;
* reliability;
* specificity;
* authority;
* temporal validity.

Probability depends on a model.

Therefore:

$$
\boxed{
EvidenceAssessment
\neq
Probability.
}
$$

---

# 27.22 — Likelihood

Likelihood:

$$
L(H;E)=P(E\mid H)
$$

measures how compatible evidence is with a hypothesis.

It is not itself:

$$
P(H\mid E).
$$

This distinction is essential.

---

# 27.23 — Example

Suppose:

$$
P(E\mid H_1)=0.9
$$

and:

$$
P(E\mid H_2)=0.8.
$$

The evidence slightly favors \(H_1\).

But without priors:

$$
P(H_1),P(H_2),
$$

we cannot calculate the posterior probabilities.

---

# 27.24 — Bayes factors

For comparing hypotheses:

$$
BF_{12}
=
\frac{P(E\mid H_1)}
{P(E\mid H_2)}.
$$

If:

$$
BF_{12}=9,
$$

the evidence is nine times as likely under \(H_1\) than \(H_2\).

This can be useful for evidence comparison.

---

# 27.25 — But evidence sources may not be independent

Suppose:

```text
CMDB
Monitoring
Dashboard
Report
```

all derive from the same underlying database.

Treating them as four independent observations would artificially inflate evidence.

Thus:

$$
\boxed{
EvidenceCount\neq IndependentEvidenceCount.
}
$$

This directly connects to 25X.

---

# 27.26 — Independence graph

We should therefore preserve source lineage.

For evidence:

$$
E_1,E_2,E_3,
$$

we need to know whether:

$$
E_1\perp E_2
$$

is plausible.

If:

$$
Origin(E_1)=Origin(E_2),
$$

they may not constitute independent corroboration.

---

# 27.27 — Confidence versus credibility

In statistical terminology, “confidence” has specific meanings.

For example, a 95% confidence interval is not:

> “There is a 95% probability that the fixed parameter lies inside this interval.”

That is not the classical frequentist interpretation.

Therefore KnowledgeOS should avoid casual use of:

$$
95\%\ Confidence
$$

unless the statistical semantics are explicitly defined.

---

# 27.28 — Confidence interval

For an estimator:

$$
\hat{\theta},
$$

we may construct:

$$
CI_{95\%}
=
[L,U].
$$

This represents a procedure with a long-run coverage property under assumptions.

It is not simply a subjective probability distribution.

---

# 27.29 — Bayesian credible interval

A Bayesian model may instead produce:

$$
P(\theta\in[L,U]\mid E)=0.95.
$$

That is a different statement.

Therefore:

$$
\boxed{
ConfidenceInterval
\neq
CredibleInterval.
}
$$

KnowledgeOS must preserve the distinction.

---

# 27.30 — Interval estimates are often better than false precision

Suppose:

$$
FailureRate=0.073842.
$$

If data are sparse, this may be misleading.

A more honest representation may be:

$$
FailureRate\in[0.04,0.11].
$$

This preserves uncertainty.

---

# 27.31 — Bounds instead of probabilities

Sometimes we cannot justify a probability distribution.

But we can establish:

$$
L\le P(H)\le U.
$$

For example:

$$
0.4\le P(H)\le0.7.
$$

This is useful when evidence is insufficient to specify one exact probability.

---

# 27.32 — Imprecise probability

This leads to:

$$
\boxed{
P(H)\in\mathcal P
}
$$

where \(\mathcal P\) is a set of plausible probability distributions.

This can represent uncertainty about the probability model itself.

It may be particularly appropriate for KnowledgeOS where evidence is heterogeneous.

---

# 27.33 — Don't overformalize prematurely

However, I would **not** make imprecise probability the universal representation.

It introduces significant complexity.

The architecture should support it where needed, while simpler bounded uncertainty may be sufficient elsewhere.

---

# 27.34 — DDD principle

Uncertainty semantics belong to the bounded context.

For example:

### Infrastructure

May use:

$$
AvailabilityProbability.
$$

### Governance

May use:

$$
EvidenceStatus.
$$

### Identity

May use:

$$
MatchProbability.
$$

### Architecture

May use:

$$
ApplicabilityStatus.
$$

They should not all be forced into:

$$
ConfidenceScore.
$$

---

# 27.35 — Evidence status

For governance, a categorical model may be more appropriate:

$$
\{
Unverified,
PartiallyVerified,
Verified,
Contradicted,
Superseded
\}.
$$

This is often more useful than:

$$
Confidence=0.83.
$$

---

# 27.36 — Epistemic status

Likewise:

$$
\boxed{
EpistemicStatus
}
$$

could include:

$$
Observed
$$

$$
Supported
$$

$$
Inferred
$$

$$
Hypothesized
$$

$$
Disputed
$$

$$
Unknown
$$

$$
Rejected
$$

$$
Superseded.
$$

These are semantic states, not probabilities.

---

# 27.37 — Probability and epistemic status can coexist

For example:

```text id="ep1"
Assertion:
    Migration succeeds

Epistemic status:
    Forecast

Probability:
    0.82

Model:
    RiskModel v4

Horizon:
    30 days
```

This is much richer than:

```text
confidence = 82%
```

---

# 27.38 — Unknown

Now we reach the most important concept.

Suppose:

$$
P(H)
$$

cannot reasonably be estimated.

The correct state may be:

$$
\boxed{
Unknown.
}
$$

Not:

$$
P(H)=0.5.
$$

Why?

Because:

$$
0.5
$$

is a substantive probabilistic claim.

It means:

> Under the specified probability model, the proposition has probability 0.5.

Unknown means:

> We do not possess a justified probability model.

These are fundamentally different.

---

# 27.39 — Unknown ≠ 50%

This must become a KnowledgeOS invariant:

$$
\boxed{
Unknown\neq 0.5.
}
$$

This is one of the strongest conclusions of Step 27.

---

# 27.40 — Missing data

Suppose:

```text id="miss01"
Database version:
    unknown
```

It would be wrong to encode:

$$
Version=0.
$$

Likewise:

$$
Probability=0.5
$$

is not a valid generic encoding of missing knowledge.

---

# 27.41 — Null semantics

The technical representation should distinguish:

$$
Missing
$$

$$
Unknown
$$

$$
NotApplicable
$$

$$
NotObserved
$$

$$
Withheld

$$

$$
Contradictory.
$$

A SQL `NULL` alone cannot express all of these semantics.

---

# 27.42 — Missingness mechanism

Statistics also distinguishes missing-data mechanisms such as:

$$
MCAR
$$

$$
MAR
$$

$$
MNAR.
$$

The reason data are missing can itself affect inference.

This may become relevant to KnowledgeOS analytics.

---

# 27.43 — Example

Suppose failed deployments are less likely to be logged.

Then missing logs are not random.

The observed sample is biased.

Therefore:

$$
P(Failure\mid Logged)
$$

may differ from:

$$
P(Failure).
$$

KnowledgeOS must not assume missing evidence is neutral.

---

# 27.44 — Selection bias

Suppose users report only serious incidents.

Then:

$$
ObservedIncidentRate
>
TrueIncidentRate
$$

may occur.

This is selection bias.

Evidence quality therefore depends not only on:

$$
what\ was\ observed,
$$

but:

$$
how\ the\ observation\ process\ was\ generated.
$$

---

# 27.45 — Observation mechanism

We should model:

$$
ObservationProcess.
$$

Conceptually:

$$
World
\rightarrow
ObservationMechanism
\rightarrow
ObservedEvidence.
$$

The observation mechanism itself can introduce bias.

---

# 27.46 — Missing-not-at-random evidence

This is especially important for AI-generated knowledge.

Suppose humans document unusual failures more often than normal successes.

Then the KnowledgeOS corpus may overrepresent failure cases.

The system could incorrectly infer:

$$
FailureRate
$$

that is much too high.

---

# 27.47 — Evidence weighting

We can therefore define evidence relevance/quality as multidimensional:

$$
Q(E,q)=
(
Reliability,
Relevance,
Independence,
Completeness,
Freshness,
Provenance
).
$$

But again:

$$
Q
$$

is not necessarily a probability.

---

# 27.48 — Evidence aggregation

If several pieces of evidence support \(H\):

$$
E_1,\ldots,E_n,
$$

we need an aggregation method.

Possible approaches include:

* Bayesian updating;
* likelihood ratios;
* deterministic rules;
* expert weighting;
* Dempster-Shafer-style evidence structures;
* interval probabilities.

The bounded context determines the appropriate method.

---

# 27.49 — Dempster-Shafer perspective

In some situations we want to represent:

> evidence supports a set of possibilities, but does not distinguish among them.

For hypotheses:

$$
\{H_1,H_2\},
$$

evidence may support:

$$
\{H_1,H_2\}
$$

without assigning separate probabilities.

This represents ignorance more explicitly than:

$$
P(H_1)=0.5.
$$

---

# 27.50 — Why this is interesting for KnowledgeOS

Suppose we know:

$$
Cause\in\{Network,Database\}
$$

but have no evidence distinguishing them.

Dempster-Shafer-like representation can preserve:

$$
Support(\{Network,Database\})
$$

without pretending:

$$
P(Network)=0.5.
$$

That is conceptually aligned with our **Unknown ≠ 50%** principle.

---

# 27.51 — But avoid belief-function complexity everywhere

Again:

$$
\boxed{
Mathematical\ sophistication
\neq
architectural\ quality.
}
$$

We should introduce advanced uncertainty formalisms only where they solve a demonstrated problem.

---

# 27.52 — Uncertainty decomposition

For a decision \(D\), we can conceptually decompose uncertainty:

$$
U_D=
U_E+
U_M+
U_P+
U_C+
U_S
$$

where:

* \(U_E\) = evidence uncertainty;
* \(U_M\) = model uncertainty;
* \(U_P\) = parameter uncertainty;
* \(U_C\) = causal uncertainty;
* \(U_S\) = semantic uncertainty.

The decomposition need not be numerically additive in every implementation.

The important point is diagnostic separation.

---

# 27.53 — Why this matters operationally

Suppose decision uncertainty is high.

We need to know why.

If:

$$
U_E
$$

is high:

> acquire better evidence.

If:

$$
U_M
$$

is high:

> evaluate competing models.

If:

$$
U_S
$$

is high:

> clarify terminology.

If:

$$
U_C
$$

is high:

> conduct causal analysis.

Different uncertainty requires different action.

---

# 27.54 — Uncertainty reduction as a planning problem

Therefore:

$$
\boxed{
NextBestInformation
=
argmax_O\ VOI(O).
}
$$

The best next step is the observation that most improves the decision relative to its cost and risk.

This connects Step 27 directly to 25Z and 26.

---

# 27.55 — Decision under uncertainty

Let actions be:

$$
A=\{a_1,\ldots,a_n\}.
$$

States:

$$
S=\{s_1,\ldots,s_m\}.
$$

Utilities:

$$
U(a,s).
$$

Then:

$$
EU(a)=
\sum_sP(s\mid E)U(a,s).
$$

Choose:

$$
a^*=\arg\max_a EU(a)
$$

subject to governance constraints.

---

# 27.56 — But expected utility is not enough

Suppose:

$$
EU(a_1)>EU(a_2)
$$

but \(a_1\) has catastrophic downside.

Risk constraints may override expected utility.

Therefore:

$$
\boxed{
Decision
=
Optimization
+
Constraints
+
Governance.
}
$$

Not pure mathematics.

---

# 27.57 — Robust decision making

When probabilities themselves are uncertain, we may choose:

$$
a^*=
\arg\max_a
\min_{P\in\mathcal P}EU_P(a).
$$

This is a robust decision criterion.

Again, it is not universally appropriate, but it becomes useful where probability estimates are unreliable.

---

# 27.58 — Sensitivity analysis

Suppose the preferred action depends strongly on:

$$
P(Failure).
$$

We should determine the threshold:

$$
p^*
$$

at which the decision changes.

For example:

$$
p<0.15
\Rightarrow
Upgrade.
$$

$$
p\ge0.15
\Rightarrow
StageFirst.
$$

This makes the decision transparent.

---

# 27.59 — Sensitivity is highly valuable for KnowledgeOS

Instead of saying:

> “Upgrade is recommended.”

KnowledgeOS can say:

> “Upgrade is recommended if failure probability remains below 15%; above that threshold, staged migration dominates.”

This is far more useful to an architect.

---

# 27.60 — Falsification experiment A

Assertion has:

$$
EpistemicStatus=Unknown.
$$

Expected:

$$
Probability
$$

is not automatically assigned.

**PASS.**

---

# 27.61 — Falsification experiment B

Evidence quality is high, but evidence is about a different entity.

Expected:

$$
Relevance=False.
$$

Therefore evidence cannot support the assertion.

**PASS.**

---

# 27.62 — Falsification experiment C

Two reports derive from the same source.

Expected:

$$
IndependentEvidenceCount=1.
$$

Not 2.

**PASS.**

---

# 27.63 — Falsification experiment D

Model probability says:

$$
P(H)=0.8.
$$

But model applicability conditions are violated.

Expected:

$$
ProbabilityStatus=NotApplicable.
$$

The system must not blindly use 0.8.

**PASS.**

---

# 27.64 — Falsification experiment E

Measurement uncertainty:

$$
X=40\pm1.
$$

Expected:

This is not automatically interpreted as:

$$
P(Failure)=?
$$

**PASS.**

---

# 27.65 — Falsification experiment F

Semantic ambiguity exists.

Expected:

$$
SemanticUncertainty
$$

rather than:

$$
NumericalConfidence.
$$

**PASS.**

---

# 27.66 — Falsification experiment G

Two hypotheses remain plausible but evidence does not distinguish them.

Expected:

$$
Underdetermined
$$

or a set-valued belief representation.

Not automatically:

$$
P(H_1)=P(H_2)=0.5.
$$

**PASS.**

---

# 27.67 — Falsification experiment H

A high-value observation can significantly distinguish competing hypotheses.

Expected:

$$
AcquireInformation
$$

may dominate immediate action.

**PASS.**

---

# 27.68 — Falsification experiment I

Historical probability:

$$
P_{2026-08-15}=0.7.
$$

New evidence changes it to:

$$
P_{2026-08-27}=0.3.
$$

Expected:

Both remain historically accessible.

**PASS.**

---

# 27.69 — Step 27 verdict

$$
\boxed{
\textbf{STEP 27 — PASS}
}
$$

The central mathematical principles are:

$$
\boxed{
Unknown\neq0.5
}
$$

$$
\boxed{
EvidenceStrength\neq Probability
}
$$

$$
\boxed{
Confidence\neq Truth
}
$$

$$
\boxed{
ModelUncertainty\neq ParameterUncertainty
}
$$

$$
\boxed{
ObservationUncertainty\neq OutcomeUncertainty
}
$$

$$
\boxed{
Correlation\neq Causation
}
$$

and:

$$
\boxed{
Different\ uncertainties\ require\ different\ remedies.
}
$$

---

# 27.70 — The stronger KnowledgeOS epistemic state

We can now define an assertion more rigorously.

Instead of:

```text
Assertion:
    Nexus migration is safe.
Confidence:
    87%
```

we want something closer to:

```text
Assertion:
    Nexus migration is safe under defined conditions.

Epistemic status:
    Supported forecast

Evidence:
    E17, E21, E42

Evidence provenance:
    verified

Identity:
    Nexus-Production-01

Semantic version:
    ArchitectureRisk-v3

Temporal scope:
    2026-08-27

Model:
    MigrationRisk-v4

Model applicability:
    valid

Probability:
    P(success | context) = 0.87

Model uncertainty:
    moderate

Known unknowns:
    downstream consumer compatibility

Decision threshold:
    ≥ 0.80

Authorization:
    pending
```

That is vastly more expressive than:

$$
Confidence=87\%.
$$

---

# 27.71 — The mathematical architecture is converging

We now have a useful decomposition:

$$
\boxed{
KnowledgeState
=
(
Assertions,
Evidence,
Uncertainty,
TemporalState,
SemanticState,
Provenance,
Models,
Rules
)
}
$$

and:

$$
DecisionState
=
(
KnowledgeState,
Goals,
Utilities,
Risks,
Constraints,
Authorization
).
$$

Then:

$$
ActionState
=
(
Decision,
Preconditions,
Execution,
Observation,
Outcome
).
$$

---

# 27.72 — One major consequence

We can now state:

$$
\boxed{
KnowledgeOS\ should\ never\ expose\ a\ naked\ number\ when\ the\ semantics\ of\ that\ number\ are\ unclear.
}
$$

A number must have:

$$
Meaning
+
ReferenceContext
+
Method
+
Time
+
Uncertainty.
$$

This is a very strong statistical design principle.

---

# 27.73 — Next step

We have now formalized uncertainty.

But another difficult issue remains.

Suppose KnowledgeOS has:

$$
P(H)=0.8.
$$

Another agent has:

$$
P(H)=0.6.
$$

A human expert has:

> “I strongly disagree.”

And a deterministic rule says:

$$
H=Forbidden.
$$

Now we have **multiple epistemic authorities producing potentially conflicting claims**.

Who wins?

And more fundamentally:

> **How should KnowledgeOS combine, rank, reconcile, or deliberately preserve conflicting knowledge from humans, agents, statistical models, rules, and authoritative systems?**

That leads naturally to:

# **Step 28 — Epistemic Conflict, Belief Revision, Multiple Authorities, Contradiction Management and Knowledge Reconciliation**

The central question will be:

$$
\boxed{
When two legitimate sources disagree, should KnowledgeOS choose one truth, preserve both, or escalate the conflict?
}
$$

This is where **belief revision, non-monotonic reasoning, defeasible knowledge, authority hierarchies, argumentation, contradiction sets, and DDD bounded-context ownership** become relevant.

And I expect this step to produce another important architectural invariant:

$$
\boxed{
Conflict\ is\ information.
}
$$

We should not automatically destroy it through premature reconciliation.
