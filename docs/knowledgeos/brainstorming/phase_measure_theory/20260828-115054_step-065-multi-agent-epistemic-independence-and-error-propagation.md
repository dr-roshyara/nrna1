# Step 65 — Multi-Agent Epistemic Independence and Error Propagation

We now test one of the most important assumptions in the entire KnowledgeOS architecture:

$$
\boxed{
\text{Does agreement between multiple AI agents actually increase knowledge quality?}
}
$$

At first glance, it seems obvious:

$$
AI_A\rightarrow p
$$

$$
AI_B\rightarrow p
$$

$$
AI_C\rightarrow p
$$

Three agents agree.

One might be tempted to conclude:

$$
Confidence(p)\uparrow.
$$

But mathematically that conclusion is only justified under specific assumptions.

The agents may share:

* the same training data;
* the same foundation model;
* the same retrieval sources;
* the same prompt;
* the same erroneous source;
* the same causal assumption;
* the same model family;
* the same generated intermediate knowledge.

Then their errors may be highly correlated.

So:

$$
\boxed{
Agreement\neq IndependentEvidence.
}
$$

---

# 65.1 — The independence problem

Suppose three agents produce:

$$
A_1,A_2,A_3.
$$

If their errors are independent, then agreement can provide additional evidence.

But if:

$$
A_1\not\perp A_2
$$

and:

$$
A_2\not\perp A_3,
$$

then naïvely counting them as three independent confirmations is invalid.

---

# 65.2 — Simple example

Suppose one external source contains a false statement:

$$
E_{bad}.
$$

All three agents retrieve the same source:

$$
E_{bad}
\rightarrow
A_1
$$

$$
E_{bad}
\rightarrow
A_2
$$

$$
E_{bad}
\rightarrow
A_3.
$$

All three say:

$$
p=True.
$$

Agreement is:

$$
3/3.
$$

But the effective independent evidence may still be:

$$
\boxed{1}.
$$

---

# 65.3 — Experiment 1: three agents, one source

Construct:

$$
A_1,A_2,A_3
$$

with identical retrieval source \(E\).

All produce:

$$
p.
$$

Naïve system:

$$
Support(p)=3.
$$

Correct provenance-aware interpretation:

$$
IndependentEvidence(p)=1.
$$

### Result

$$
\boxed{\text{PASS}}
$$

if source dependency is preserved.

---

# 65.4 — Agent provenance

Every AI-generated artifact should therefore reference:

$$
AgentId
$$

$$
ModelVersion
$$

$$
InputEvidence
$$

$$
RetrievalContext
$$

and, where relevant:

$$
Prompt/Configuration.
$$

Then we can construct an agent dependency graph.

---

# 65.5 — Agent dependency graph

Define:

$$
G_A=(V_A,E_A).
$$

Where:

$$
V_A=
\{
Agents,Models,Evidence,Artifacts
\}.
$$

An edge:

$$
A_i\rightarrow A_j
$$

means \(A_j\) depends on \(A_i\)'s output.

---

# 65.6 — Experiment 2: cascading AI generation

Suppose:

$$
A_1\rightarrow C_1
$$

then:

$$
A_2(C_1)\rightarrow C_2
$$

then:

$$
A_3(C_2)\rightarrow C_3.
$$

Finally:

$$
A_4(C_3)\rightarrow C_4.
$$

If \(C_1\) is wrong, the error may propagate through the entire chain.

### Result

$$
\boxed{\text{PASS}}
$$

provided lineage is preserved.

---

# 65.7 — Error propagation

Let:

$$
P(E_i)
$$

be the probability of error at stage \(i\).

In a simple independent chain, downstream error probabilities can sometimes be analyzed.

But real AI pipelines often have correlated errors.

Therefore:

$$
P(E_{downstream})
$$

cannot safely be computed by assuming independence.

---

# 65.8 — Common-mode failure

Suppose all agents share:

$$
M_{same}.
$$

Then an error in \(M_{same}\) can affect every agent.

This is:

$$
\boxed{
CommonModeFailure.
}
$$

Examples:

* shared foundation model;
* shared corrupted knowledge base;
* shared prompt;
* shared parser;
* shared ontology error.

---

# 65.9 — Experiment 3: common model error

Agents:

$$
A_1,A_2,A_3,A_4
$$

all use:

$$
M_1.
$$

A systematic model error causes all four to produce the same incorrect result.

Expected:

$$
Agreement=High
$$

but:

$$
IndependentSupport\not\text{High}.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.10 — Therefore

$$
\boxed{
AgentCount
\neq
EvidenceCount.
}
$$

This is one of the most important results of Step 65.

---

# 65.11 — Independence dimensions

"Independent agent" is not binary.

We can define a dependency vector:

$$
Dep(A_i,A_j)
=
(
D_{model},
D_{data},
D_{source},
D_{prompt},
D_{pipeline},
D_{reasoning}
).
$$

Two agents may be independent in one dimension and dependent in another.

---

# 65.12 — Example

Agent A and B use different models:

$$
D_{model}=0.
$$

But both retrieve the same database:

$$
D_{data}=1.
$$

They are not fully independent.

---

# 65.13 — Experiment 4: different models, same evidence

$$
M_A\neq M_B.
$$

But:

$$
Evidence_A=Evidence_B.
$$

Both produce \(p\).

Expected:

$$
SupportStrength
$$

increases less than it would under fully independent evidence.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.14 — Agreement is nevertheless useful

We should not overcorrect.

Agreement between independently designed systems can be informative.

For example:

$$
A_1\rightarrow p
$$

and:

$$
A_2\rightarrow p
$$

using genuinely independent evidence.

Then agreement may strengthen the proposition.

But the strengthening must be justified.

---

# 65.15 — Bayesian view

Suppose hypotheses are:

$$
H_1:p=True
$$

and:

$$
H_0:p=False.
$$

Two conditionally independent observations:

$$
E_1,E_2.
$$

Then:

$$
\frac{P(H_1|E_1,E_2)}
{P(H_0|E_1,E_2)}
=
\frac{P(H_1)}{P(H_0)}
\times
LR_1
\times
LR_2.
$$

But this multiplication requires conditional independence.

---

# 65.16 — Correlated evidence

If:

$$
E_1,E_2
$$

are correlated, then:

$$
LR_1LR_2
$$

can overstate evidence.

The correct joint likelihood is:

$$
P(E_1,E_2|H).
$$

Not necessarily:

$$
P(E_1|H)P(E_2|H).
$$

---

# 65.17 — Experiment 5: correlated evidence

Construct:

$$
Corr(E_1,E_2)>0.
$$

Compare:

$$
P(H|E_1,E_2)
$$

with the naïve independent estimate.

Expected:

$$
NaiveConfidence
>
CorrectConfidence
$$

in the relevant correlated case.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.18 — AI consensus should therefore be evidence-weighted

Instead of:

$$
Consensus=3/3,
$$

we should preserve:

$$
Consensus=
(
Agents,
Dependencies,
EvidenceSources,
Models,
AgreementPattern
).
$$

Then a downstream context can decide what the agreement means.

---

# 65.19 — Experiment 6: circular citation

Now create:

$$
A_1\rightarrow C_1
$$

then:

$$
A_2(C_1)\rightarrow C_2.
$$

Then:

$$
A_1(C_2)\rightarrow C_3.
$$

The system could eventually appear to have many supporting artifacts.

But the entire chain originates from the same initial assertion.

This creates:

$$
\boxed{
EpistemicCircularity.
}
$$

---

# 65.20 — Circularity detection

The provenance graph:

$$
G_P
$$

should be inspected for cycles.

If:

$$
C_1\leadsto C_1,
$$

the system should identify the cycle.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.21 — But not every cycle is invalid

A feedback cycle:

$$
Prediction
\rightarrow
Outcome
\rightarrow
Learning
\rightarrow
NewPrediction
$$

is legitimate because time advances.

The problematic case is:

$$
Claim
\rightarrow
DerivedClaim
\rightarrow
Support
\rightarrow
OriginalClaim
$$

where the supposed evidence ultimately depends on the claim itself.

---

# 65.22 — Temporal distinction

Thus:

$$
Cycle
$$

is not automatically an error.

We need:

$$
TemporalProgress.
$$

A static epistemic justification cycle is problematic.

A historical learning cycle may be valid.

---

# 65.23 — Experiment 7: self-reinforcing knowledge

Suppose AI generates:

$$
p.
$$

KnowledgeOS stores \(p\).

Later AI retrieves \(p\) and generates:

$$
p'
$$

which cites the stored \(p\).

Then another process treats \(p'\) as independent support for \(p\).

This creates artificial evidence amplification.

### Result

$$
\boxed{\text{FAILURE DETECTED}}
$$

This is an important failure.

---

# 65.24 — Architectural correction

Generated knowledge must preserve:

$$
DerivedFrom.
$$

Then:

$$
p'
$$

is recognized as dependent on:

$$
p.
$$

It does not automatically count as independent evidence.

---

# 65.25 — New invariant

$$
\boxed{
I_{EpistemicIndependence}:
Derived\ artifacts\ cannot\ be\ counted\ as\
independent\ evidence\ of\ their\ own\ ancestry.
}
$$

This is a very strong KnowledgeOS invariant.

---

# 65.26 — Experiment 8: knowledge poisoning through repetition

Suppose false claim:

$$
p
$$

enters the system once.

Ten agents subsequently repeat it.

The system observes:

$$
11\ agreements.
$$

But provenance reveals:

$$
10\leftarrow1.
$$

Therefore effective independent source count remains approximately:

$$
1.
$$

### Result

$$
\boxed{\text{PASS}}
$$

after provenance-aware correction.

---

# 65.27 — This changes how we think about AI consensus

The correct question is not:

> How many agents agree?

It is:

> **How many sufficiently independent evidence paths support the proposition?**

That is a much stronger formulation.

---

# 65.28 — Evidence path diversity

We can define:

$$
PathDiversity(p).
$$

For example, support may come from:

$$
Database
$$

$$
Sensor
$$

$$
HumanObservation
$$

$$
IndependentModel
$$

$$
ExternalExperiment.
$$

Diversity does not guarantee truth, but it reduces some common-mode risks.

---

# 65.29 — Experiment 9: diverse evidence

Five agents agree.

But they use:

* three independent data sources;
* two independent models;
* separate retrieval pipelines.

The system records the dependency structure.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.30 — Independence is not the same as diversity

Different sources can still share a hidden common dependency.

For example:

$$
Source_A
$$

and:

$$
Source_B
$$

may both copy the same original source.

Therefore:

$$
SurfaceDiversity
\neq
CausalIndependence.
$$

---

# 65.31 — Common-source graph

We can represent:

$$
S_0\rightarrow S_1
$$

and:

$$
S_0\rightarrow S_2.
$$

Then:

$$
S_1,S_2
$$

are not independent despite being separate documents.

---

# 65.32 — Experiment 10: copied sources

Two apparently independent documents trace to the same original report.

Expected:

$$
IndependentEvidenceCount=1
$$

or appropriately discounted according to the evidence model.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.33 — AI-generated evidence

Now an even harder case.

Suppose:

$$
AI_A
$$

generates an explanation from:

$$
E.
$$

That explanation is stored.

Later:

$$
AI_B
$$

uses the explanation as evidence.

We must label it:

$$
AI\ GeneratedArtifact.
$$

Not:

$$
IndependentObservation.
$$

---

# 65.34 — Experiment 11: generated artifact treated as fact

AI explanation enters KnowledgeOS.

A later process promotes it to:

$$
ObservedFact.
$$

Expected:

$$
Rejected.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.35 — This gives us a provenance taxonomy

Evidence may originate from:

$$
Human
$$

$$
Instrument
$$

$$
ExternalSystem
$$

$$
AI
$$

$$
Simulation
$$

$$
Inference.
$$

These origins have different epistemic semantics.

---

# 65.36 — Simulation evidence

Suppose simulation predicts:

$$
Y=10.
$$

This is not an observation.

It is:

$$
SimulatedOutcome.
$$

It can support a claim, but its evidentiary semantics depend on model validity.

---

# 65.37 — Experiment 12: simulation treated as observation

Simulation produces:

$$
Y=10.
$$

System stores:

$$
Observed(Y=10).
$$

Expected:

$$
Rejected.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.38 — Synthetic data

Similarly:

$$
SyntheticData
\neq
ObservedData.
$$

Synthetic data may be valuable, but the provenance must remain explicit.

---

# 65.39 — Model-generated evidence

This creates a hierarchy:

$$
Observation
\rightarrow
Inference
\rightarrow
Prediction
\rightarrow
Decision.
$$

The direction must not be reversed accidentally:

$$
Prediction
\not\Rightarrow
Observation.
$$

---

# 65.40 — Experiment 13: prediction becomes evidence

Model predicts:

$$
SystemFailureTomorrow=True.
$$

Tomorrow does not occur yet.

The prediction cannot be stored as:

$$
FailureOccurred.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.41 — Outcome closes the loop

Only when actual observation arrives:

$$
ObservedFailure=True
$$

can we evaluate the prediction.

Thus:

$$
Prediction
\rightarrow
Outcome
\rightarrow
Evaluation.
$$

---

# 65.42 — AI agent performance

For agent \(A_i\), we can accumulate:

$$
Predictions(A_i)
$$

and:

$$
Outcomes(A_i).
$$

Then estimate:

$$
Accuracy(A_i)
$$

$$
Calibration(A_i)
$$

$$
BrierScore(A_i)
$$

or domain-specific metrics.

---

# 65.43 — But agent quality is contextual

An agent may be excellent for:

$$
CodeReview
$$

and poor for:

$$
CausalInference.
$$

Therefore:

$$
Quality(A_i,Task)
$$

is more meaningful than:

$$
Quality(A_i).
$$

---

# 65.44 — Experiment 14: task transfer

Agent has:

$$
Accuracy=95\%
$$

in task domain \(D_1\).

It is automatically trusted in:

$$
D_2.
$$

Expected:

$$
UnsupportedTransfer.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.45 — Agent capability profile

We can therefore define:

$$
Capability(A)=
\{
Domain,
Task,
Model,
Performance,
Calibration,
ValidityPeriod
\}.
$$

This becomes another governed artifact.

---

# 65.46 — Agent authorization

Even if an agent is highly accurate, that does not automatically give it authority to perform consequential actions.

Therefore:

$$
AgentCapability
\neq
AgentAuthority.
$$

This mirrors:

$$
Decision
\neq
Authorization.
$$

---

# 65.47 — Experiment 15: capable but unauthorized agent

Agent demonstrates:

$$
Accuracy=99\%.
$$

It attempts a restricted action.

Governance denies authorization.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.48 — Agent trust should therefore be scoped

Instead of:

$$
TrustedAgent=True,
$$

we want something closer to:

$$
TrustProfile(A,Domain,Task,Time).
$$

---

# 65.49 — Model lineage and agent lineage

We now have:

$$
Agent
\rightarrow
Model
\rightarrow
Data
$$

and:

$$
Agent
\rightarrow
Output
\rightarrow
Decision.
$$

This makes AI behavior auditable.

---

# 65.50 — Experiment 16: hidden dependency

Agent B appears independent.

But inspection reveals:

$$
Agent_B
\rightarrow
KnowledgeOS
\rightarrow
Agent_AOutput.
$$

Therefore B's conclusion is dependent on A.

### Result

$$
\boxed{\text{PASS}}
$$

if lineage exposes the dependency.

---

# 65.51 — Epistemic independence becomes a graph property

We can ask:

$$
Independent(A,B|p)?
$$

by examining their dependency ancestors.

If:

$$
Ancestors(A)\cap Ancestors(B)
$$

contains important common sources, independence is reduced or cannot be assumed.

---

# 65.52 — Formal dependency set

Define:

$$
Anc(A)
$$

as the set of upstream artifacts on which \(A\)'s output depends.

Then:

$$
Common(A,B)
=
Anc(A)\cap Anc(B).
$$

Large common dependency does not mathematically prove correlation, but it is evidence against assuming independence.

---

# 65.53 — Experiment 17: common ancestor

$$
Common(A,B)=\{E_0\}.
$$

Both agents rely on \(E_0\).

Expected:

$$
IndependentSupport
$$

is not automatically doubled.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.54 — This is a powerful architectural idea

KnowledgeOS can potentially compute:

$$
EvidenceDependencyGraph
$$

and use it to prevent artificial confidence inflation.

That is substantially more rigorous than ordinary multi-agent consensus.

---

# 65.55 — Multi-agent epistemic state

We can now represent:

$$
M(p)=
(
Claims,
Evidence,
Agents,
Models,
Dependencies,
Agreement,
Conflict
).
$$

This becomes the epistemic state of a proposition under multi-agent reasoning.

---

# 65.56 — Consensus is a derived property

We should calculate:

$$
Consensus(p)
$$

from underlying artifacts.

Consensus itself should not become primary evidence.

---

# 65.57 — Experiment 18: consensus without provenance

Suppose database stores:

```text id="e1w1y7"
p = true
votes = 17
```

but no evidence lineage.

The system cannot determine whether the 17 votes are independent.

Expected:

$$
ConsensusEvidenceQuality=Unknown.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.58 — Another important principle

$$
\boxed{
Untraceable\ agreement
is\ not\ equivalent\ to\ independent\ confirmation.
}
$$

---

# 65.59 — Error amplification

Suppose a false claim has probability:

$$
q
$$

of entering the system.

If every downstream agent blindly trusts upstream AI outputs, the false claim may become increasingly entrenched.

This is a positive feedback loop:

$$
FalseClaim
\rightarrow
AI
\rightarrow
Knowledge
\rightarrow
AI
\rightarrow
MoreKnowledge.
$$

---

# 65.60 — Error amplification coefficient

We can conceptually define:

$$
\lambda
=
\frac{ExpectedDownstreamFalseSupport}
{InitialFalseSupport}.
$$

If:

$$
\lambda>1,
$$

the system amplifies unsupported claims.

The exact metric requires a concrete stochastic model, but the architectural phenomenon is clear.

---

# 65.61 — Experiment 19: amplification

Inject one false claim.

Allow ten agents to consume and reproduce it.

Measure independent provenance.

Expected:

$$
IndependentSupport
$$

does not increase proportionally to the number of reproductions.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.62 — How to stop amplification

The system needs:

$$
SourceLineage
$$

and:

$$
DerivedFrom
$$

so that repeated derivations do not become independent evidence.

Potentially also:

$$
EvidenceFreshness
$$

and:

$$
IndependentSourceRequirement.
$$

---

# 65.63 — Independent-source policy

For high-consequence claims, governance may require:

$$
N_{independent}\ge k.
$$

For example:

$$
N_{independent}\ge2.
$$

The value \(k\) belongs to policy.

---

# 65.64 — Experiment 20: insufficient independent evidence

Policy:

$$
N_{independent}\ge2.
$$

Current:

$$
N_{independent}=1.
$$

Even though:

$$
AgentAgreement=12/12,
$$

claim cannot be promoted to the required status.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 65.65 — This is a powerful result

We have mathematically separated:

$$
AgentConsensus
$$

from:

$$
EvidenceIndependence.
$$

That is precisely what a governed AI knowledge architecture needs.

---

# 65.66 — New invariants

We can now add several important invariants.

### AI provenance

$$
\boxed{
I_{AI-Provenance}:
Every\ AI-generated\ artifact\ retains\
its\ model,\ input,\ and\ dependency\ lineage.
}
$$

### No artificial evidence multiplication

$$
\boxed{
I_{EvidenceNonAmplification}:
Derived\ copies\ of\ the\ same\ epistemic\ source\
cannot\ automatically\ increase\ independent\ evidence.
}
$$

### No circular justification

$$
\boxed{
I_{NoCircularJustification}:
A\ claim\ cannot\ serve\ as\ independent\ justification\
for\ an\ ancestor\ claim.
}
$$

### Scoped agent competence

$$
\boxed{
I_{AgentScope}:
Agent\ competence\ is\ task/domain/context\ specific.
}
$$

---

# 65.67 — Step 65 verdict

$$
\boxed{
\textbf{STEP 65 — PASS WITH ONE CRITICAL CORRECTION}
}
$$

The architecture successfully handles multi-agent reasoning **only if provenance and dependency are first-class**.

We discovered one important failure mode:

$$
\boxed{
AI\text{-generated knowledge can recursively amplify itself.}
}
$$

The correction is:

$$
\boxed{
DerivedFrom
+
DependencyGraph
+
IndependentEvidenceAccounting.
}
$$

---

# 65.68 — The architecture now has a deeper epistemic firewall

We can formulate:

$$
\boxed{
AI\ Output
\neq
Evidence
\neq
Fact
\neq
Truth.
}
$$

Instead:

$$
AIOutput
\rightarrow
CandidateArtifact
\rightarrow
Evaluation
\rightarrow
KnowledgeStatus.
$$

That distinction should be fundamental to KnowledgeOS.

---

# 65.69 — The complete multi-agent flow

```text id="i0m5dw"
              External World
                    │
                    ▼
               Observations
                    │
                    ▼
                Evidence
                    │
          ┌─────────┼─────────┐
          ▼         ▼         ▼
       Agent A   Agent B   Agent C
          │         │         │
          ▼         ▼         ▼
      Proposal   Proposal   Proposal
          │         │         │
          └─────────┼─────────┘
                    ▼
             Dependency Graph
                    │
                    ▼
          Independent Evidence
              Assessment
                    │
          ┌─────────┼─────────┐
          ▼         ▼         ▼
        Agree     Conflict   Unknown
          │         │         │
          └─────────┼─────────┘
                    ▼
               Evaluation
                    │
                    ▼
               Knowledge
```

The important component is:

$$
\boxed{IndependentEvidenceAssessment}
$$

between agent outputs and knowledge promotion.

---

# 65.70 — Current mathematical correctness model

We now have:

$$
\boxed{
C_{KOS}
=
S
\land
L
\land
Comp
\land
Conc
\land
Epi
\land
Dec
\land
Causal
\land
Model
\land
MultiAgent
}
$$

where:

* \(S\) = safety;
* \(L\) = liveness;
* \(Comp\) = compositionality;
* \(Conc\) = concurrency;
* \(Epi\) = epistemic integrity;
* \(Dec\) = decision integrity;
* \(Causal\) = causal integrity;
* \(Model\) = model validity;
* \(MultiAgent\) = multi-agent epistemic integrity.

---

# 65.71 — Where we stand scientifically

At this point I would **not yet say**:

> "We have mathematically proven that KnowledgeOS is correct."

That would be too strong.

What we can say is considerably more useful:

$$
\boxed{
The architecture has survived a growing family of explicit
mathematical counterexample tests.
}
$$

And when failures appeared, we converted them into architectural invariants.

That is exactly how a serious architecture experiment should proceed.

---

# 65.72 — The next boundary

There is now a very fundamental remaining question.

All of our reasoning assumes that the **external world can be represented adequately by the system's observations**.

But the world contains things we cannot observe.

Therefore:

$$
\boxed{
What\ can\ KnowledgeOS\ legitimately\ know?
}
$$

This leads to the problem of:

$$
Observability
$$

$$
Identifiability
$$

$$
PartialObservability
$$

$$
HiddenState
$$

$$
MeasurementError
$$

$$
MissingData
$$

$$
SelectionBias
$$

$$
SamplingBias
$$

and:

$$
\boxed{
The\ boundary\ between\ the\ world\ and\ the\ KnowledgeOS\ model.
}
$$

---

# Step 66 — Partial Observability and the Epistemic Boundary

The next step will formalize something even more fundamental:

$$
\boxed{
KnowledgeOS\ never\ directly\ owns\ the\ entire\ world.
}
$$

It receives observations:

$$
O_t
$$

from an underlying state:

$$
X_t.
$$

But generally:

$$
O_t\neq X_t.
$$

We therefore need to investigate:

$$
X_t
\rightarrow
Observation
\rightarrow
Measurement
\rightarrow
Evidence
\rightarrow
Knowledge.
$$

This will let us mathematically distinguish:

$$
Reality
$$

from:

$$
Observation
$$

from:

$$
Measurement
$$

from:

$$
Inference
$$

from:

$$
Decision.
$$

That may become the deepest architectural boundary we've encountered so far.
