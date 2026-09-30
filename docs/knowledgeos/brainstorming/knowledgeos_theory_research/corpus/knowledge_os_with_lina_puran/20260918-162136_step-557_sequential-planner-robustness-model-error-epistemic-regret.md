# Step 557 — Sequential Planner Robustness, Model Error and Epistemic Regret

I have read the attached Step 556 review in full. Its central proposal is now sufficiently mature to be challenged by the next experiment: **what happens when the sequential planner's model of the world is wrong?** The attached review explicitly identifies this as the next unresolved danger and defines the Sequential Oracle as exact only for the finite model supplied to it. 

I therefore do **not** add another abstraction first. I attack the existing architecture.

The result is important:

$$
\boxed{
\text{An optimal policy under a wrong epistemic model can be worse than a simple policy under the true model.}
}
$$

This means KnowledgeOS needs a **Model Adequacy / Model Validation boundary before trusting sequential optimization**.

---

# 1. Step 557 research question

The exact question is:

> If \(M\) is the model used by the sequential planner and \(M^\*\) is the actual generating process, when is the planner allowed to trust its policy?

We have:

$$
\pi_M^*
=
\arg\max_\pi V(\pi\mid M,Q,C,\Gamma)
$$

but execution occurs in:

$$
M^\*.
$$

There is no mathematical reason that:

$$
\boxed{
\pi_M^*=\pi_{M^\*}^*
}
$$

must hold.

That equality needs evidence.

---

# 2. First new term: Epistemic Model

An **Epistemic Model** is a formal representation of how the current KnowledgeOS system expects hypotheses, actions, observations and updates to behave.

For example:

$$
M=
(H,A,O,P,Update,Det,Stop).
$$

Where:

* \(H\) = hypotheses;
* \(A\) = actions;
* \(O\) = possible observations;
* \(P\) = observation model;
* \(Update\) = epistemic update rule;
* \(Det\) = determination function;
* \(Stop\) = stopping rule.

It is important that:

$$
\boxed{
Model\neq Reality.
}
$$

A model is an object used for reasoning.

---

# 3. Model Adequacy

Define:

$$
\boxed{
ModelAdequacy(M,E,Q,C,\Gamma)
}
$$

as the degree to which the model's assumptions and predictions are sufficiently supported for the intended inquiry.

This is **not** the same as:

$$
ModelAccuracy.
$$

A model can be accurate for one purpose and inadequate for another.

Example:

```text
Model:
    "Veeam backup configuration is stable for 24 hours."

Adequate for:
    current operational check

Not necessarily adequate for:
    proving six-month backup continuity
```

So:

$$
\boxed{
ModelAdequacy\ is\ inquiry\ relative.
}
$$

---

# 4. Model Misspecification

A **Model Misspecification** exists when the model used by the planner differs materially from the process relevant to the inquiry.

Formally:

$$
M\not\equiv_Q M^\*
$$

where \(\equiv_Q\) means equivalence for the current inquiry contract.

This is stronger and more useful than saying merely:

> The model is wrong.

---

# 5. The first computational experiment

I constructed a finite world:

$$
H=\{0,1,2,3\}.
$$

The determination is:

$$
D(h)=
\begin{cases}
A,&h<2\\
B,&h\ge2.
\end{cases}
$$

The system initially has four possible hypotheses.

We provide three acquisition actions:

### Cheap probe

Moderately informative:

$$
P(o=1|h)
=
(0.1,0.2,0.8,0.9).
$$

### Direct probe

The **true world** is only moderately informative:

$$
P(o=1|h)
=
(0.35,0.35,0.65,0.65).
$$

### Sequential gate

The gate reveals a variable which permits a subsequent inexpensive perfect determination probe.

So the sequential structure is:

$$
a_G\rightarrow a_{perfect}.
$$

---

# 6. Deliberately wrong planner model

Now introduce the planner's model.

The planner incorrectly believes that the direct probe is almost perfect:

$$
\widehat P(o=1|h)
=
(0.001,0.001,0.999,0.999).
$$

Thus:

$$
\widehat M\neq M^\*.
$$

This is deliberate **model misspecification**.

---

# 7. Exact Oracle under the true model

I computed the exact finite-horizon oracle using the true observation model.

For horizon \(T=3\):

$$
\boxed{
\pi^\*_{M^\*}=probe
}
$$

with value:

$$
\boxed{
V^\*_{M^\*}=-0.23.
}
$$

The policy discovers the gate and then uses the cheap perfect continuation.

---

# 8. What does the wrong planner choose?

The planner optimizes against \(\widehat M\).

For horizon \(T=1\):

$$
\boxed{
\pi^*_{\widehat M}=direct.
}
$$

Why?

Because the planner believes:

$$
P(\text{correct determination}\mid direct)
\approx 0.999.
$$

But that belief is false.

---

# 9. Execute the wrong policy in the true world

When the supposedly excellent direct action is executed against \(M^\*\), its actual reliability is much lower.

The computed value becomes:

$$
V_{M^\*}(\pi_{\widehat M})
=
-0.47.
$$

The true optimal value was:

$$
V_{M^\*}(\pi^*)
=
-0.25.
$$

Therefore regret is:

$$
\boxed{
Regret=0.22.
}
$$

For horizon 2, the divergence becomes even larger:

$$
\boxed{
Regret=0.36.
}
$$

This is a decisive synthetic counterexample.

---

# 10. Important theorem

We can now formulate:

### Model-Conditional Optimality

For a finite model \(M\):

$$
\pi_M^*
=
\arg\max_\pi V_M(\pi)
$$

is optimal **with respect to \(M\)**.

It does not imply:

$$
\pi_M^*
=
\pi_{M^\*}^*.
$$

Therefore:

$$
\boxed{
Optimality\ under\ a\ model
\neq
Optimality\ under\ the\ generating\ process.
}
$$

This distinction should become fundamental in KnowledgeOS.

---

# 11. Define Planner Regret

We already had contract-relative regret:

$$
Regret(\pi|Q,C,\Gamma).
$$

Step 557 requires a second axis:

$$
\boxed{
Regret(\pi;M,M^\*|Q,C,\Gamma)
}
$$

defined as:

$$
V_{M^\*}(\pi_{M^\*}^*)
-
V_{M^\*}(\pi_M).
$$

This measures the cost of planning with \(M\) when the relevant generating process is \(M^\*\).

For our experiment:

$$
Regret(\pi_{\widehat M};M^\*,Q)
=
0.22
$$

for the one-step case.

---

# 12. New distinction: Epistemic Regret vs Model Regret

This gives us:

### Policy Regret

A policy is inferior to another policy under the same model.

$$
Regret_{policy}
$$

### Model Regret

A policy is generated from an inadequate model.

$$
Regret_{model}
$$

Therefore:

$$
\boxed{
Regret_{total}
\neq
Regret_{policy}\ only.
}
$$

The decomposition is conceptually:

$$
\boxed{
PerformanceLoss
=
PolicyError
+
ModelError
+
ExecutionError
}
$$

The exact additive decomposition is **not universal** because interaction terms may exist. This is a useful accounting decomposition, not yet a theorem.

---

# 13. Why this matters for ML

This is exactly where ML becomes dangerous.

Suppose ML estimates:

$$
\widehat P(O|E,a).
$$

The sequential planner then calculates:

$$
\widehat V^*(E).
$$

The system might report:

> optimal acquisition action.

But mathematically the actual statement is only:

> optimal under the estimated model.

Thus:

$$
\boxed{
\widehat V^*
\neq
V^*
}
$$

unless model adequacy is established sufficiently for the contract.

---

# 14. New ML concept: Model Uncertainty

Previously we distinguished uncertainty in the world.

Now we need uncertainty **about the model itself**.

Let:

$$
\mathcal M=\{M_1,\ldots,M_k\}
$$

be admissible candidate models.

Then the system has:

$$
\boxed{
ModelUncertainty
}
$$

when more than one materially different model remains plausible.

Example:

```text
M1:
    backup inspection succeeds with 95%

M2:
    backup inspection succeeds with 70%

M3:
    inspection result is strongly dependent on operator
```

The planner should not silently select M1.

---

# 15. Model uncertainty is different from state uncertainty

We now have:

$$
\boxed{
StateUncertainty\neq ModelUncertainty.
}
$$

Example:

```text
State uncertainty:
    Is Veeam configured?

Model uncertainty:
    How reliable is a configuration inspection
    as evidence of actual backup operation?
```

This is a major distinction.

---

# 16. New concept: Model Identifiability

A model property is **identifiable** if available observations can distinguish it from competing model properties.

Let:

$$
M_1\sim_O M_2
$$

if they produce the same observable distribution under the available observations.

If:

$$
M_1\sim_O M_2
$$

but:

$$
\pi^*_{M_1}\neq\pi^*_{M_2},
$$

then the optimal policy itself is not identifiable from current evidence.

This is extremely important.

---

# 17. Model Non-Identifiability

We therefore have the counterexample:

$$
\boxed{
M_1\sim_O M_2
\land
\pi^*_{M_1}\neq\pi^*_{M_2}
}
$$

Then no algorithm using only the current observations can know which policy is actually optimal.

This extends our earlier identifiability theorem.

Previously:

$$
K_1\sim_O K_2
$$

could imply inability to identify a target predicate.

Now:

$$
M_1\sim_O M_2
$$

can imply inability to identify the optimal policy.

---

# 18. This creates a new Zero

Suppose:

```text
Determination:
    sufficient

Stability:
    sufficient

Model:
    uncertain
```

Then ordinary Zero may be empty for the determination itself.

But the planner has a new unresolved predicate:

$$
\boxed{
Zero_{planning}
=
\{ModelAdequacy\}
}
$$

Therefore:

$$
\boxed{
Stop_{determination}
\neq
Stop_{planning}.
}
$$

This is an important architectural refinement.

---

# 19. Planning Zero

I recommend defining:

### Planning Zero

A Planning Zero is an unresolved predicate whose uncertainty materially affects whether a proposed acquisition policy is admissible or reliable.

Formally:

$$
Z_P\in Zero
$$

if variation in \(Z_P\) can change:

$$
\pi^*
$$

or materially change its contract value.

Example:

```text
Zero:
    Is the direct backup inspection model reliable?

Why material?
    If yes → inspect directly.
    If no  → obtain independent evidence first.
```

This is much more useful than generic model uncertainty.

---

# 20. Model-sensitive acquisition

Now an important new possibility appears.

We might not need another observation about the **world**.

We may need an observation about the **model**.

Example:

```text
World question:
    Is backup active?

Model question:
    Does this inspection method reliably establish backup activity?
```

So acquisition can target:

$$
Z_{world}
$$

or:

$$
Z_{model}.
$$

Therefore:

$$
\boxed{
TargetSet
=
Target_{world}
\cup
Target_{model}
}
$$

provided the inquiry contract requires it.

---

# 21. Model Validation Acquisition

Define:

**Model Validation Acquisition**:

> An acquisition whose primary purpose is to reduce uncertainty about whether a model is adequate for the intended inquiry.

Example:

```text
Review 100 historical backup inspections
and compare inspection result against independently
verified backup execution.
```

This does not directly determine today's backup state.

It validates:

$$
P(O|H,a).
$$

That distinction is critical.

---

# 22. New architecture: two feedback loops

KnowledgeOS should therefore have two related loops.

### World loop

$$
Observation
\rightarrow
Evidence
\rightarrow
Determination
$$

### Model loop

$$
Prediction
\rightarrow
Observation
\rightarrow
ModelValidation
\rightarrow
ModelRevision.
$$

Combined:

```text id="v8yqj7"
                    KNOWLEDGE
                       │
                 ┌─────┴─────┐
                 ▼           ▼
            WORLD MODEL   MODEL MODEL
                 │           │
                 ▼           ▼
            Acquisition   Validation
                 │           │
                 ▼           ▼
              Evidence   Model Evidence
                 │           │
                 └─────┬─────┘
                       ▼
                 Model Update
                       │
                       ▼
                 Policy Update
```

The second loop is currently missing from the simplified architecture.

---

# 23. ML architecture must change

The ML layer should no longer be represented simply as:

```text
ML → candidate ranking
```

It should be:

```text id="jknz6t"
                 ML
                  │
        ┌─────────┼─────────┐
        ▼         ▼         ▼
   Outcome     Value      Feature
    Model      Model     Discovery
        │         │         │
        └─────────┼─────────┘
                  ▼
             Predictions
                  │
                  ▼
          Model Validation
                  │
          ┌───────┴────────┐
          ▼                ▼
       Accepted         Rejected/
       Model            Uncertain
          │                │
          ▼                ▼
      Planner          Planning Zero
```

This is much safer.

---

# 24. ML should output uncertainty about its own model

Instead of:

$$
\widehat P(O|E,a)=0.93
$$

the richer representation should eventually be:

$$
\boxed{
(\widehat P,\ Calibration,\ OOD,\ ModelScope,\ Provenance)
}
$$

For example:

```text
Predicted success: 0.93
Calibration status: validated
Training domain: Nexus production configurations
OOD status: unknown
Temporal scope: 2025–2026
Evidence basis: 4,800 historical inspections
```

The number alone is not sufficient.

---

# 25. Distribution shift becomes model adequacy

This connects directly to our Step 547 work.

We already distinguished:

$$
IID\neq OOD
$$

and:

$$
DataDrift\neq ConceptDrift.
$$

Step 557 adds:

$$
\boxed{
OOD\rightarrow ModelAdequacyQuestion
}
$$

but **not automatically**:

$$
OOD\rightarrow ModelInvalid.
$$

OOD means:

> the new case differs from the model's demonstrated operating distribution.

Whether that invalidates the model requires validation.

---

# 26. New term: Model Scope

A **Model Scope** defines where a model has been validated or justified.

For example:

$$
Scope(M)=
\{
Environment=Production,
Product=Nexus,
Version\in[3.69,3.70],
ObservationType=BackupConfig
\}.
$$

A prediction outside that scope is not automatically false.

It is:

$$
\boxed{
OutsideValidatedScope.
}
$$

That should generate a planning warning.

---

# 27. New term: Model Confidence is not enough

We must avoid:

$$
Confidence=0.95
\Rightarrow
ModelAdequate.
$$

That implication is invalid.

A model can be highly confident and wrong because of:

* distribution shift;
* leakage;
* biased training;
* hidden confounding;
* model misspecification;
* temporal change.

Therefore:

$$
\boxed{
Confidence\neq ModelAdequacy.
}
$$

---

# 28. A very important new principle

I recommend this as a **Strong Candidate**:

$$
\boxed{
\textbf{A sequential policy must be evaluated against uncertainty in the model used to generate it.}
}
$$

This is more precise than simply saying:

> validate the model.

It directly connects model assurance with decision planning.

---

# 29. Robust policy rather than single-model policy

If several models remain plausible:

$$
\mathcal M=\{M_1,\ldots,M_k\},
$$

we should not necessarily choose:

$$
\pi^*_{M_1}.
$$

Instead possible policies include:

### Expected-model policy

$$
\max_\pi E_M[V_M(\pi)].
$$

### Worst-case policy

$$
\max_\pi \min_{M\in\mathcal M}V_M(\pi).
$$

### Robust constraint policy

$$
V_M(\pi)\ge\tau
\quad
\forall M\in\mathcal M.
$$

These are **different decision contracts**.

None should become the universal KnowledgeOS rule.

---

# 30. New term: Robust Policy

A **Robust Policy** is a policy whose performance remains acceptable across a declared set of plausible models.

For example:

$$
\boxed{
\forall M\in\mathcal M:
V_M(\pi)\ge\tau.
}
$$

This is much more defensible than saying:

> the policy is optimal.

---

# 31. New term: Policy Fragility

Define:

$$
\boxed{
PolicyFragility(\pi,\mathcal M)
}
$$

as sensitivity of the selected policy or its contract value to admissible model variation.

A simple benchmark metric can be:

$$
PF=
\frac{
|\{M:\pi_M^*\neq\pi\}|
}{
|\mathcal M|
}.
$$

This is a benchmark metric, not a universal definition.

A more useful profile is:

$$
PF(\pi)=
(
PolicyChange,
ValueRange,
StopChange,
TargetFailure,
CostRange
).
$$

---

# 32. Policy stability versus determination stability

This creates another important separation:

$$
\boxed{
DeterminationStability
\neq
PolicyStability.
}
$$

The determination may remain:

$$
D=A
$$

under multiple models while the recommended acquisition changes dramatically.

Example:

```text
Model 1:
    direct inspection reliable
    → inspect directly

Model 2:
    direct inspection unreliable
    → obtain independent evidence
```

Same current determination.

Different optimal next action.

---

# 33. Therefore the KnowledgeOS stability profile expands carefully

We already had:

$$
SP=
(S_D,S_A,S_C,S_\Gamma,\ldots).
$$

We should **not immediately add Policy Stability as a new universal dimension**.

Instead define it as a derived property:

$$
\boxed{
PS(\pi|\mathcal M,Q,C,\Gamma)
}
$$

only when the inquiry contract asks whether policy choice is robust to model variation.

This avoids unnecessary ontology growth.

---

# 34. DDD implications

The new concepts belong primarily in the existing **Epistemic Intelligence + Assurance** architecture.

I would not create a Model Governance Bounded Context yet.

### L3 — Epistemic Intelligence

Add:

```text
Model Hypothesis
Model Comparison
Model Sensitivity
Policy Sensitivity
Robust Policy Analysis
Planning Zero
```

### L4 — Assurance

Add:

```text
Model Adequacy
Model Scope Validation
Model Misspecification Tests
Policy Robustness Test
Model Leakage Audit
OOD Model Check
Policy Regret Evaluation
```

### L5 — Intelligence

Keep:

```text
Outcome Model
Value Model
Feature Discovery
Policy Approximation
```

This is clean DDD placement.

---

# 35. Optimized architecture after Step 557

```text id="q6q0sf"
L0  KNOWLEDGEOS KERNEL
    ├── Identity
    ├── Typed Relations
    └── Semantic Interpretation

L1  CONTRACT FABRIC
    ├── Inquiry Contract
    ├── Target Set
    ├── Acquisition Contract
    ├── Evidence Contract
    ├── Stability Contract
    ├── Model Scope Contract
    ├── Planning Contract
    └── Stopping Contract

L2  STRUCTURAL / MATHEMATICAL FABRIC
    ├── Relations
    ├── Graphs
    ├── Partitions
    ├── Refinements
    ├── Probability
    ├── Statistics
    └── Optimization

L3  EPISTEMIC ENGINE
    ├── Hypothesis Space
    ├── Identifiability
    ├── Determination
    ├── Stability
    ├── Zero
    ├── Evidence
    ├── Dependency
    ├── Acquisition Discovery
    ├── Sequential Planning
    ├── Model Comparison
    ├── Model Sensitivity
    ├── Policy Sensitivity
    ├── Robust Policy Analysis
    └── Planning Zero

L4  ASSURANCE
    ├── Evidence Validation
    ├── Ground Truth Comparison
    ├── Identifiability Tests
    ├── Leakage Audit
    ├── Calibration
    ├── OOD Detection
    ├── Model Scope Validation
    ├── Model Adequacy
    ├── Model Misspecification
    ├── Sequential Oracle
    ├── Policy Regret
    ├── Model Regret
    ├── False Stop
    └── Policy Robustness

L5  INTELLIGENCE
    ├── Candidate Discovery
    ├── Outcome Model Estimation
    ├── Value Approximation
    ├── Feature Discovery
    ├── Candidate Ranking
    ├── Policy Approximation
    └── ML-Assisted Planning

L6  GOVERNANCE
    ├── Authority
    ├── Policy
    ├── Responsibility
    ├── Decision
    ├── Authorization
    └── Accountability
```

---

# 36. One particularly important architectural correction

The previous architecture effectively had:

$$
Zero\rightarrow Acquisition\rightarrow Evidence.
$$

It should now become:

$$
\boxed{
Zero
\rightarrow
TargetSet
\rightarrow
Identifiability
\rightarrow
ModelAdequacy
\rightarrow
AcquisitionPlanning
\rightarrow
Evidence
}
$$

But only when model uncertainty is decision-material.

Otherwise we create unnecessary work.

So:

$$
\boxed{
ModelValidation\ is\ conditional,\ not\ mandatory\ for\ every\ acquisition.
}
$$

---

# 37. The new decision gate

Before accepting a sequential policy:

$$
\boxed{
PolicyGate =
TargetAdequacy
\land
ModelAdequacy
\land
ActionFeasibility
\land
EvidenceAdequacy
\land
GovernancePermission
}
$$

If:

$$
ModelAdequacy=Unknown
$$

and model uncertainty can change the selected policy, then:

$$
\boxed{
PlanningZero\rightarrow ModelValidationAcquisition.
}
$$

This is the critical control.

---

# 38. Example in the Nexus domain

Suppose the system asks:

> Is the current Nexus repository adequately protected by backup?

Current evidence:

```text
Backup configuration:
    found

Determination:
    provisionally sufficient

Stability:
    unknown
```

ML says:

```text
Probability backup is operational = 0.94
```

But its training data consisted mostly of:

```text
Nexus installations from 2024–2025
```

while the current system has:

```text
different infrastructure
different backup tooling
different deployment architecture
```

The correct KnowledgeOS reaction is **not**:

> confidence = 94%, therefore continue.

Instead:

```text
OOD / Scope warning
        ↓
Planning Zero:
    Is the outcome model applicable here?
        ↓
Model validation acquisition
        ↓
Independent evidence
        ↓
Re-evaluate policy
```

This is exactly the kind of behavior we want from KnowledgeOS.

---

# 39. Step 557 evidence ledger

| Proposition                                                   | Status                             |
| ------------------------------------------------------------- | ---------------------------------- |
| Optimality is model-relative                                  | **FORMALLY PROVEN**                |
| Optimal policy under \(M\) need not be optimal under \(M^\*\) | **COMPUTATIONALLY DEMONSTRATED**   |
| Model misspecification can create acquisition regret          | **COMPUTATIONALLY DEMONSTRATED**   |
| State uncertainty ≠ model uncertainty                         | **FORMALLY DISTINGUISHED**         |
| Model identifiability is separate from state identifiability  | **STRONG CANDIDATE**               |
| Model non-identifiability can prevent policy identification   | **FORMALLY DERIVED**               |
| Policy stability ≠ determination stability                    | **DEMONSTRATED BY COUNTEREXAMPLE** |
| Model validation may itself require acquisition               | **STRONG CANDIDATE**               |
| Robust policy is contract-relative                            | **FORMALLY MOTIVATED**             |
| Policy fragility is measurable                                | **BENCHMARK CONSTRUCT**            |
| ML can estimate outcome/value models                          | **STRONG CANDIDATE**               |
| ML-estimated model may be misspecified                        | **DEMONSTRATED**                   |
| ML confidence establishes model adequacy                      | **REJECTED**                       |
| New Model Governance BC required                              | **NOT ESTABLISHED**                |
| New Kernel primitive required                                 | **NO**                             |

---

# 40. The deeper KnowledgeOS control loop

After Step 557, the architecture is no longer simply an epistemic acquisition loop.

It is:

$$
\boxed{
\begin{aligned}
Observation
&\rightarrow Hypothesis\\
&\rightarrow Determination\\
&\rightarrow Zero\\
&\rightarrow TargetSet\\
&\rightarrow Identifiability\\
&\rightarrow ModelAdequacy\\
&\rightarrow AcquisitionPlanning\\
&\rightarrow Execution\\
&\rightarrow Evidence\\
&\rightarrow Validation\\
&\rightarrow Update\\
&\rightarrow Determination\\
&\rightarrow Stability\\
&\rightarrow PolicyRobustness\\
&\rightarrow Stop/Continue.
\end{aligned}
}
$$

ML operates alongside this loop:

$$
\boxed{
ML
\rightarrow
Candidate/Model/Value
\rightarrow
Validation
\rightarrow
Policy.
}
$$

Never:

$$
ML\rightarrow Truth.
$$

---

# 41. The most important new principle

I recommend recording this as a **Strong Candidate Principle**, not yet a frozen universal law:

$$
\boxed{
\textbf{Optimal sequential reasoning is conditional on the adequacy of the model used to predict future acquisition outcomes.}
}
$$

And the corresponding architectural rule:

$$
\boxed{
\textbf{Before trusting a sequential policy, test whether model uncertainty can change the policy or its contract-level outcome.}
}
$$

If it can:

$$
\boxed{
PlanningZero\rightarrow ModelValidation.
}
$$

If it cannot, we do not waste resources validating an irrelevant model property.

That last condition is important: **KnowledgeOS should validate models according to their material effect on the inquiry, not pursue model certainty for its own sake.**

---

# 42. Step 558 — next decisive challenge

We have now uncovered the next harder problem.

Suppose several models are plausible:

$$
\mathcal M=\{M_1,M_2,\ldots,M_k\}.
$$

They recommend different acquisition policies:

$$
\pi^*_{M_1}\neq\pi^*_{M_2}.
$$

What should KnowledgeOS do?

The next experiment should therefore be:

$$
\boxed{
\textbf{Step 558 — Model-Uncertainty-Aware Sequential Planning}
}
$$

with controlled worlds containing:

1. correct model known;
2. wrong model confidently believed;
3. multiple plausible models;
4. models producing different optimal actions;
5. an acquisition that distinguishes the models;
6. an acquisition that improves the world determination but not model knowledge;
7. an acquisition that improves model knowledge but not current determination.

Then compare:

$$
\boxed{
Single\text{-}Model\ Oracle
}
$$

vs.

$$
\boxed{
Model\text{-}Averaged\ Policy
}
$$

vs.

$$
\boxed{
Worst\text{-}Case\ Robust\ Policy
}
$$

vs.

$$
\boxed{
Model\text{-}Validation\ First
}
$$

vs.

$$
\boxed{
ML\text{-}Assisted\ Model\ Selection.
}
$$

The decisive question will be whether **model uncertainty itself becomes a legitimate acquisition target** and, if so, whether we can incorporate it without introducing another Kernel primitive or another unnecessary Bounded Context.

At this point the Kernel still remains exactly:

$$
\boxed{
\mathfrak K_{\min}=(ID,\mathcal R^\star,Sem)
}
$$

and the architecture is becoming more capable **without becoming ontologically larger**.
