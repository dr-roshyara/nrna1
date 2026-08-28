# Step 25H — Formal Sārathi Algebra: Decision, Utility, Risk and Authorization

We have reached the next important boundary.

So far:

$$
\boxed{
Lord
=
\text{selects the next useful action}
}
$$

Now we need:

$$
\boxed{
Sārathi
=
\text{determines what decision should be made}
}
$$

This distinction is essential.

Lord may conclude:

> "The next best action is to perform a restore test."

Sārathi must eventually answer:

> "Given the resulting knowledge, should we migrate, postpone, change the plan, or stop?"

---

# 25H.1 — First distinction: action versus decision

Let:

$$
D=\{d_1,d_2,\ldots,d_m\}
$$

be possible decisions.

For Nexus:

$$
D=
\{
Migrate,
Postpone,
ChangePlan,
Reject
\}.
$$

Let:

$$
A
$$

be executable actions.

These are not necessarily the same.

For example:

$$
d=Migrate
$$

may require several actions:

$$
A=
\{
Backup,
RestoreTest,
FirewallValidation,
Approval,
Migration
\}.
$$

Therefore:

$$
\boxed{
Decision\neq Action.
}
$$

---

# 25H.2 — Lord and Sārathi

We can now state the distinction precisely:

### Lord

Chooses **what to do next to improve the state or advance the process**.

$$
Lord:
(K,Z,G,C)\rightarrow A
$$

### Sārathi

Chooses **which decision is justified given the current knowledge and decision model**.

$$
Sārathi:
(K,G,D,C,P)\rightarrow d
$$

where \(P\) is the decision policy/model.

---

# 25H.3 — Decision requires consequences

Suppose:

$$
d_1=Migrate.
$$

We need to know:

$$
Consequences(d_1).
$$

Possible outcomes:

$$
Success,
Failure,
DataLoss,
Downtime,
Rollback.
$$

So decision reasoning requires:

$$
\boxed{
OutcomeModel(d,K).
}
$$

---

# 25H.4 — Utility

If outcomes have different values, we need a utility function:

$$
U(o)
$$

where \(o\) is an outcome.

For example:

$$
U(Success)=+100
$$

$$
U(Failure)=-50
$$

$$
U(DataLoss)=-1000.
$$

These numbers are **illustrative only**.

They must never be invented by the AI and presented as organizational truth.

---

# 25H.5 — Expected utility

If probabilities are available:

$$
EU(d\mid K)
=
\sum_o
P(o\mid d,K)
U(o).
$$

Then:

$$
\boxed{
d^*
=
\arg\max_d EU(d\mid K)
}
$$

subject to governance and safety constraints.

This gives us a mathematically well-defined decision mechanism.

---

# 25H.6 — But probability is optional

Sometimes:

$$
P(o\mid d,K)
$$

cannot reasonably be established.

We must not invent it.

Instead we may have:

$$
Preference(d_1,d_2)
$$

or:

$$
Dominates(d_1,d_2).
$$

For example:

> If migration is unauthorized, postponement dominates migration.

No probability is necessary.

---

# 25H.7 — Decision feasibility

Before calculating utility, Sārathi must determine:

$$
Feasible(d,K,C).
$$

For example:

$$
d=Migrate
$$

requires:

$$
RollbackVerified=True.
$$

If:

$$
RollbackVerified=Unknown,
$$

then:

$$
Feasible(Migrate)=False.
$$

It should not matter how attractive migration appears economically.

---

# 25H.8 — Hard constraints versus preferences

This distinction is fundamental.

### Hard constraint

$$
C(d)=True
$$

must hold.

Example:

$$
ProductionChangeAuthorized.
$$

### Preference

Among permissible decisions:

$$
d_1\succ d_2.
$$

Example:

> Prefer lower expected downtime.

Therefore:

$$
\boxed{
Constraints\ filter;
Utility/preferences\ select.
}
$$

---

# 25H.9 — This prevents a dangerous mistake

Suppose:

$$
EU(Migrate)=95
$$

and:

$$
EU(Postpone)=60.
$$

But:

$$
Authorized(Migrate)=False.
$$

Then:

$$
Migrate
$$

is eliminated before utility optimization.

Therefore:

$$
\boxed{
Utility\ cannot\ override\ governance.
}
$$

---

# 25H.10 — Decision model

We can therefore define:

$$
\boxed{
DecisionModel=
(
Outcomes,
Constraints,
Preferences,
Utilities,
Uncertainty,
Policy
)
}
$$

and:

$$
\boxed{
Sārathi(K,D,DecisionModel)
\rightarrow DecisionResult.
}
$$

---

# 25H.11 — DecisionResult

It should not simply return:

```text
MIGRATE
```

It should return something like:

```text
Decision:
    POSTPONE

Reason:
    rollback capability not established

Blocking requirements:
    RollbackVerified

Alternatives:
    Migrate = infeasible
    Postpone = feasible

Evidence basis:
    E17, E22, E31

Decision model:
    ProductionMigrationPolicy v3

Authorization:
    not required for postponement
```

Now the decision is auditable.

---

# 25H.12 — Decision provenance

We need:

$$
DecisionProvenance(d)
$$

showing:

$$
K_t
+
Contract
+
Policy
+
Model
+
Evidence
\rightarrow
d.
$$

Therefore:

$$
\boxed{
Decision\ must\ be\ reproducible.
}
$$

---

# 25H.13 — The decision snapshot

Suppose today:

$$
d=Postpone.
$$

Tomorrow new evidence arrives:

$$
E_{new}.
$$

The decision may become:

$$
d'=Migrate.
$$

We must not rewrite the historical decision.

Instead:

$$
Decision_t
$$

remains associated with:

$$
K_t.
$$

Then:

$$
Decision_{t+1}
$$

is calculated from:

$$
K_{t+1}.
$$

Thus:

$$
\boxed{
Decision\ is\ time-indexed.
}
$$

---

# 25H.14 — Decision stability

Now a very interesting question arises.

Suppose small changes in evidence cause:

$$
Migrate
\leftrightarrow
Postpone.
$$

Then the decision is unstable.

We can define conceptually:

$$
Stability(d,K)
$$

as sensitivity to plausible changes in the knowledge state.

This can be extremely useful.

---

# 25H.15 — Example

Suppose:

$$
P(Success)=0.91.
$$

Migration threshold:

$$
P(Success)\ge0.90.
$$

Decision:

$$
Migrate.
$$

But if a small uncertainty could make:

$$
P(Success)=0.88,
$$

then the decision is fragile.

Sārathi should report:

$$
\boxed{
DecisionFragility=High.
}
$$

Not simply:

> "Migrate."

---

# 25H.16 — Threshold decisions

Many real decisions can be represented as:

$$
P(Success)\ge \theta.
$$

For example:

$$
\theta=0.95.
$$

Then:

$$
P=0.97
\Rightarrow Migrate
$$

while:

$$
P=0.72
\Rightarrow Postpone.
$$

But again:

$$
\theta
$$

must come from governance/domain policy.

The AI cannot invent it.

---

# 25H.17 — Unknown is not failure

Suppose:

$$
P(Success)
$$

cannot be estimated.

We should not substitute:

$$
P=0.5.
$$

That would be unjustified.

Instead:

$$
Probability=Unknown.
$$

Then the decision policy determines what to do with uncertainty.

For example:

$$
Unknown
+
CriticalRisk
\rightarrow
Postpone.
$$

---

# 25H.18 — This is an important KnowledgeOS principle

$$
\boxed{
Absence\ of\ evidence
\neq
evidence\ of\ failure.
}
$$

But:

$$
\boxed{
Required\ evidence\ absent
\Rightarrow
decision\ may\ be\ blocked.
}
$$

These are different.

---

# 25H.19 — Risk

We now need to distinguish:

$$
Risk
$$

from:

$$
Uncertainty.
$$

Uncertainty concerns what we do not know.

Risk concerns consequences under uncertainty.

A common formalization is:

$$
Risk(d)
=
P(Loss\mid d,K)\times Impact(Loss).
$$

But again, this is only appropriate where the probabilistic model is justified.

---

# 25H.20 — Risk without probabilities

Sometimes we can use:

$$
RiskLevel\in
\{
Low,Medium,High,Critical
\}.
$$

This is acceptable if the organization's risk framework defines those categories.

So:

$$
\boxed{
Risk\ can\ be\ quantitative\ or\ qualitative.
}
$$

---

# 25H.21 — Decision dominance

Suppose:

$$
d_1
$$

is:

* more expensive;
* more risky;
* no more beneficial;

than:

$$
d_2.
$$

Then:

$$
d_2
$$

dominates \(d_1\).

We don't need precise utility numbers.

Formally:

$$
\boxed{
d_2\succeq d_1.
}
$$

This is valuable when data is incomplete.

---

# 25H.22 — Multi-objective decisions

Real enterprise decisions often have multiple objectives:

$$
Objectives=
\{
Cost,
Risk,
Time,
Quality,
Compliance,
Architecture
\}.
$$

These cannot necessarily be reduced honestly to one scalar.

Therefore we may have a **Pareto set**:

$$
\mathcal P(D).
$$

Several decisions may be non-dominated.

Then Sārathi can report:

> "Three decisions are Pareto-optimal; organizational preference is required."

This is far better than arbitrary AI selection.

---

# 25H.23 — Human decision boundary

We can now define an important terminal state:

$$
\boxed{
HumanChoiceRequired.
}
$$

This occurs when:

* multiple decisions are equally defensible;
* utility weights are undefined;
* governance is unresolved;
* critical uncertainty cannot be reduced;
* authorized human judgment is explicitly required.

Again:

$$
HumanChoiceRequired
$$

is a **computed result**.

---

# 25H.24 — This is perhaps the most important finding in 25H

A computational system does not need to produce a decision in every situation.

It needs to determine:

$$
\boxed{
DecisionAvailable
}
$$

or:

$$
\boxed{
DecisionNotComputableFromCurrentKnowledge
}
$$

or:

$$
\boxed{
HumanDecisionRequired.
}
$$

That is much more rigorous than pretending every question has an AI answer.

---

# 25H.25 — Sārathi therefore has three modes

### Mode 1 — Deterministic decision

$$
K+Rules\rightarrow d.
$$

### Mode 2 — Model-based decision

$$
K+Probability+Utility\rightarrow d.
$$

### Mode 3 — Human escalation

$$
K+Constraints\rightarrow HumanChoiceRequired.
$$

---

# 25H.26 — Example

Suppose:

```text
Rollback = verified
Firewall = verified
Security = approved
Architecture = approved

Migration:
    expected benefit = high
    expected risk = acceptable
```

Then:

$$
Sārathi\rightarrow Migrate.
$$

---

Now:

```text
Rollback = unknown
```

and rollback is blocking.

Then:

$$
Sārathi\rightarrow Postpone.
$$

---

Now:

```text
Rollback = verified
Architecture policy = conflicting
No authority precedence
```

Then:

$$
Sārathi\rightarrow HumanDecisionRequired.
$$

Three different outcomes from three different epistemic conditions.

---

# 25H.27 — LLM role

Again, the LLM can help:

$$
LLM(K,D)
\rightarrow
CandidateDecisionReasoning.
$$

But Sārathi should validate:

$$
DecisionCandidate
$$

against:

$$
Contract,
Constraints,
Evidence,
Policy,
Authorization.
$$

The LLM cannot manufacture:

$$
Utility
$$

or:

$$
Authority.
$$

---

# 25H.28 — Sārathi versus Lord

The distinction is now quite clean:

|                         | Lord             | Sārathi                            |
| ----------------------- | ---------------- | ---------------------------------- |
| Main question           | What next?       | What decision?                     |
| Input                   | Zero + actions   | Knowledge + alternatives           |
| Main concern            | Gap reduction    | Consequences                       |
| Output                  | Action candidate | Decision                           |
| Information acquisition | Central          | Supporting                         |
| Utility                 | Secondary        | Central                            |
| Governance              | Constraint       | Constraint                         |
| Human escalation        | Possible         | Central when choice is irreducible |

This separation is architecturally valuable.

---

# 25H.29 — The complete agent loop

We now have:

$$
\boxed{
Knowledge
\rightarrow
Zero
\rightarrow
Lord
\rightarrow
Evidence/Action
\rightarrow
Knowledge
}
$$

until sufficient knowledge exists.

Then:

$$
\boxed{
Knowledge
\rightarrow
Sārathi
\rightarrow
Decision
\rightarrow
Authorization
\rightarrow
Execution.
}
$$

So Lord and Sārathi are not competing agents.

They form a sequence.

---

# 25H.30 — A subtle possibility

Sometimes Sārathi discovers that the decision cannot yet be made.

Then:

$$
Sārathi
\rightarrow
Lord.
$$

For example:

> "Migration decision cannot be made because rollback evidence is insufficient."

Therefore:

$$
Sārathi
\rightarrow
KnowledgeGap
\rightarrow
Lord.
$$

This gives us a feedback loop.

---

# 25H.31 — Full architecture

```text id="c6k4tc"
                 WORLD
                   │
             Observation
                   │
                   ▼
                EVIDENCE
                   │
                   ▼
             KNOWLEDGE STATE
                   │
          ┌────────┴─────────┐
          │                  │
          ▼                  ▼
   EPISTEMIC CONTRACT    DECISION MODEL
          │                  │
          ▼                  │
         ZERO                │
          │                  │
          ▼                  │
         LORD                │
          │                  │
          └──────┐           │
                 ▼           │
              KNOWLEDGE      │
                 │           │
                 └─────┬─────┘
                       ▼
                    SĀRATHI
                       │
                ┌──────┼──────┐
                ▼      ▼      ▼
             Decide  Escalate  More
                │              Knowledge
                ▼
           AUTHORIZATION
                │
                ▼
             EXECUTION
                │
                ▼
               WORLD
```

Now the loop is almost complete.

---

# 25H.32 — Computability on a normal PC

Again, the core Sārathi computation is completely feasible.

The computational primitives are:

* constraint satisfaction;
* rule evaluation;
* expected utility;
* preference ordering;
* Pareto analysis;
* graph traversal;
* threshold evaluation;
* provenance;
* versioned state.

All are ordinary computational operations.

Even moderately complex decision models can run on a normal workstation.

---

# 25H.33 — What may require more computation?

Potentially:

$$
LargeScaleMonteCarlo
$$

or:

$$
ComplexOptimization
$$

or:

$$
LargeCausalModels.
$$

But these are optional specialized engines.

The Sārathi abstraction does not require them.

---

# 25H.34 — What cannot be computed automatically?

There are cases where the problem is not computationally solvable from the available information.

For example:

> "Should the company accept a politically sensitive strategic risk?"

If the utility function itself depends on a human value judgment that has not been specified, there is no mathematically correct answer.

Sārathi should return:

$$
\boxed{
ValueModelUnderspecified.
}
$$

That is a correct result.

---

# 25H.35 — The deepest result so far

We can now distinguish four reasons why KnowledgeOS may not produce an automatic decision:

### 1. Missing knowledge

$$
Zero\neq\varnothing.
$$

### 2. Conflicting knowledge

$$
Conflict\neq\varnothing.
$$

### 3. Missing decision model

$$
Utility/PreferenceUndefined.
$$

### 4. Irreducible human authority

$$
HumanDecisionRequired.
$$

These are fundamentally different failure modes.

---

# 25H.36 — Therefore "AI uncertainty" is too vague

Instead of:

> "The AI is uncertain."

KnowledgeOS can say:

```text
Decision unavailable because:

1. Rollback evidence is insufficient.
2. Architecture policy conflict remains unresolved.
3. No authorized utility preference exists between
   options A and B.
```

That is vastly more useful.

---

# 25H.37 — 25H formal operator

I would provisionally define:

$$
\boxed{
S(K,G,D,M,C)
\rightarrow
DecisionResult
}
$$

where:

* \(K\) = KnowledgeState;
* \(G\) = Goal;
* \(D\) = decision alternatives;
* \(M\) = DecisionModel;
* \(C\) = constraints.

And:

$$
DecisionResult\in
\{
Decision,
HumanDecisionRequired,
InsufficientKnowledge,
GovernanceBlocked,
ModelUnderspecified
\}.
$$

This is computationally explicit.

---

# 25H.38 — Falsification

### Test A

All requirements satisfied and one decision dominates.

$$
S\rightarrow d.
$$

**PASS**

### Test B

Blocking knowledge missing.

$$
S\rightarrow InsufficientKnowledge.
$$

**PASS**

### Test C

Governance conflict unresolved.

$$
S\rightarrow GovernanceBlocked.
$$

**PASS**

### Test D

Two decisions equally valid, no preference.

$$
S\rightarrow HumanDecisionRequired.
$$

**PASS**

### Test E

Utility model absent where utility is necessary.

$$
S\rightarrow ModelUnderspecified.
$$

**PASS**

### Test F

LLM recommends unauthorized action.

$$
S\not\rightarrow unauthorized\ action.
$$

**PASS**

---

# 25H.39 — 25H verdict

$$
\boxed{
\textbf{25H — PASS}
}
$$

We now have a computable definition of Sārathi:

> **Sārathi is the governed decision function that evaluates feasible alternatives against current knowledge, constraints, consequences and an explicit decision model, and returns either a defensible decision or an explicit reason why automatic decision is not justified.**

---

# 25H.40 — We have now crossed a major boundary

The architecture is no longer merely:

$$
KnowledgeManagement.
$$

It is becoming:

$$
\boxed{
Epistemic\ Control\ System.
}
$$

It can:

1. acquire observations;
2. construct evidence;
3. maintain knowledge;
4. derive requirements;
5. calculate Zero;
6. choose knowledge-gathering actions;
7. evaluate decisions;
8. execute authorized actions;
9. observe the resulting world;
10. update knowledge.

---

# 25H.41 — And the normal-PC question has an even stronger answer

At this point I am comfortable saying:

$$
\boxed{
\textbf{Yes: the core mathematical architecture is computable on a normal PC.}
}
$$

Not merely theoretically.

The architecture maps to standard technologies:

| KnowledgeOS concept | Conventional implementation |
| ------------------- | --------------------------- |
| Evidence            | Records/objects             |
| Provenance          | Metadata + hashes           |
| KnowledgeState      | Versioned database          |
| Evidence Graph      | Graph/relational relations  |
| Contract            | Rules + structured data     |
| Zero                | Deterministic evaluation    |
| Lord                | Rule/planning engine        |
| Sārathi             | Decision engine             |
| Governance          | Policy/rule engine          |
| LLM                 | Optional inference service  |
| Internet            | External connector          |
| Human               | External authority          |
| Observation         | Event/input pipeline        |

So the architecture is **engineering-realizable**, not dependent on exotic mathematics or hardware.

---

# 25H.42 — But now we have reached the next true mathematical frontier

There is one issue we have intentionally not solved:

$$
\boxed{
\textbf{Atma / Knowledge Identity}
}
$$

We have talked about:

* Evidence identity;
* Knowledge state;
* version;
* provenance;
* observation;
* human knowledge;
* AI-generated knowledge.

But we have not yet formally answered:

> **What exactly makes two knowledge objects the "same knowledge", different knowledge, a refinement of the same knowledge, or merely two representations of the same underlying observation?**

This is the place where your earlier distinction between **Knowledge Atma** and **Knower Atma (human)** becomes mathematically important.

The next step should therefore be:

# **Step 25I — Knowledge Identity Algebra: Observation, Representation, Assertion, Knowledge Atma and Knower Atma**

We should attack questions such as:

$$
Document_1
\equiv
Document_2?
$$

$$
LLMOutput
\equiv
HumanAssertion?
$$

$$
Observation
\equiv
Evidence?
$$

$$
Evidence
\equiv
Knowledge?
$$

and most importantly:

$$
\boxed{
What is the identity of a Knowledge Atma?
}
$$

If we can make **25I** computable, we will have addressed one of the deepest remaining conceptual points in the entire KnowledgeOS theory.
