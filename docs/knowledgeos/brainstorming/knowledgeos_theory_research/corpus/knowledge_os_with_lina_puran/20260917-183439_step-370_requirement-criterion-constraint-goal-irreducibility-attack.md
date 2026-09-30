# Step 370 — Requirement / Criterion / Constraint / Goal Irreducibility Attack

We now attack the object that `Sat` operates on.

We have:

$$
Sat(K,r)
$$

but the symbol \(r\) has been overloaded. A requirement, a constraint, a criterion, a goal, and an objective are not obviously the same thing.

If we fail to separate them, the satisfaction theory becomes circular.

The central question is:

$$
\boxed{
Requirement
\stackrel{?}{=}
ID+\mathcal R^\star+\mathsf{Sem}
}
$$

and then:

$$
\boxed{
Requirement
\stackrel{?}{=}
Criterion
\stackrel{?}{=}
Constraint
\stackrel{?}{=}
Goal
\stackrel{?}{=}
Objective
}
$$

We should attack these independently.

---

## 370.1 First distinction: object versus evaluation role

Consider:

> “At least 50% of eligible members voted.”

This can appear as:

* a requirement for an election;
* a constraint on an admissible election state;
* a criterion for compliance;
* a condition for accepting a result.

The same semantic content can participate in different roles.

Therefore:

$$
\boxed{
Content\neq EvaluationRole.
}
$$

This is already a warning against making `Requirement` a universal ontological type.

---

# 370.2 Candidate structure

A useful provisional representation is:

$$
q=
\langle
ID_q,
Target_q,
Predicate_q,
Scope_q,
Regime_q,
Role_q
\rangle.
$$

But this is only a candidate representation.

We must test which components are actually necessary.

---

# 370.3 Requirement

Informally:

> A requirement specifies a condition that must be met for a particular purpose under a specified context.

Thus:

$$
Req(Q,C,EC)
$$

is already relative to:

$$
Q,C,EC.
$$

A requirement is therefore not simply a proposition.

Compare:

$$
p=\text{“A received 100 votes.”}
$$

with:

$$
r=\text{“A must receive at least 100 votes.”}
$$

They have different semantic roles.

Thus:

$$
\boxed{
Proposition\neq Requirement.
}
$$

---

# 370.4 Requirement versus proposition

A proposition may be evaluated for truth:

$$
Eval(p)\in\{T,F,U\}.
$$

A requirement is evaluated for satisfaction:

$$
Sat(K,r).
$$

Therefore:

$$
\boxed{
TruthEvaluation\neq RequirementSatisfaction.
}
$$

For example:

$$
p=(Votes(A)\ge100)
$$

can be true in the world.

But:

$$
r=(Votes(A)\ge100)
$$

becomes a requirement only when some contract says:

> This condition is required.

Thus requirement status is contextual.

---

# 370.5 Requirement versus constraint

A constraint usually restricts admissible states:

$$
C(K)=True.
$$

A requirement may be evaluated against a knowledge state:

$$
Sat(K,r).
$$

These can coincide:

$$
Sat(K,r)\equiv C_r(K)
$$

for a simple structural requirement.

But they need not.

Example:

> “There should be enough evidence to justify the conclusion.”

This is not necessarily a state-validity constraint.

It is an epistemic adequacy criterion.

Thus:

$$
\boxed{
Constraint\subsetneq? Requirement
}
$$

remains open, but equality is already not justified.

---

# 370.6 Hard constraint

Consider:

$$
x\ge0.
$$

A hard constraint may define:

$$
\mathcal K_{valid}
=
\{K:x(K)\ge0\}.
$$

Then:

$$
C(K)=True.
$$

Here satisfaction can be reduced to constraint checking:

$$
Sat(K,r)=C(K).
$$

This is the easiest case.

---

# 370.7 Soft constraint

Now consider:

$$
x\ge0
$$

as a preference rather than a prohibition.

Violation does not make the state invalid; it merely reduces desirability.

Therefore:

$$
Constraint_{soft}
$$

cannot simply be identified with:

$$
Constraint_{hard}.
$$

This introduces an important distinction:

$$
\boxed{
ValidityConstraint\neq PreferenceConstraint.
}
$$

---

# 370.8 Requirement versus goal

Consider:

> “The election should achieve at least 70% participation.”

This may be a goal.

It does not necessarily mean the election is invalid below 70%.

Thus:

$$
Goal
$$

can describe a desired future state without defining admissibility.

A requirement may instead impose:

$$
must.
$$

Therefore:

$$
\boxed{
Goal\neq HardRequirement.
}
$$

---

# 370.9 Goal and temporal direction

Goals frequently refer to a future state:

$$
Goal(K_{future}).
$$

Requirements can concern:

* current state;
* historical state;
* future state;
* transition;
* process;
* evidence.

Thus goal semantics often have an intentional/temporal component.

No new Kernel primitive follows.

---

# 370.10 Objective

An objective usually provides something to optimize:

$$
\max_d U(d)
$$

or:

$$
\min_d L(d).
$$

This is fundamentally different from a Boolean compliance condition.

For example:

$$
Cost(d)\le100
$$

may be a constraint, while:

$$
\min Cost(d)
$$

is an objective.

Therefore:

$$
\boxed{
Constraint\neq Objective.
}
$$

---

# 370.11 Criterion

A criterion specifies a basis for evaluation or comparison.

For example:

$$
C_1=\text{cost}
$$

$$
C_2=\text{quality}
$$

$$
C_3=\text{risk}.
$$

A criterion may produce:

$$
Score_C(d).
$$

It does not necessarily specify a hard threshold.

Therefore:

$$
\boxed{
Criterion\neq Constraint.
}
$$

---

# 370.12 Acceptance criterion

An acceptance criterion is more specific.

Example:

> “The software release is accepted only if all critical vulnerabilities are resolved.”

This is a criterion with a decision/acceptance role:

$$
Accept(Release)
$$

depending on:

$$
Sat(r_1)\land Sat(r_2)\land\cdots.
$$

Thus:

$$
AcceptanceCriterion
$$

can be modeled as a typed evaluative requirement.

It need not be a new primitive.

---

# 370.13 Decision criterion

A decision criterion can be:

$$
U(d)\ge\tau
$$

or:

$$
d\succ d'
$$

or:

$$
d\in ParetoSet.
$$

This is not necessarily a requirement.

It belongs naturally to the decision regime.

Again:

$$
\boxed{
DecisionCriterion
\neq
UniversalRequirement.
}
$$

---

# 370.14 Optimization objective

Consider:

$$
\min_d Cost(d).
$$

There may be no state that fully “satisfies” the objective.

Instead, the objective induces an ordering:

$$
d_1\preceq_U d_2.
$$

Therefore trying to force every objective into:

$$
Sat(d,r)\in\{T,F\}
$$

would be mathematically wrong.

This is an important result.

---

# 370.15 First major conclusion

There cannot be one universal scalar semantics:

$$
Requirement\rightarrow\{T,F\}
$$

covering all:

* constraints;
* goals;
* objectives;
* criteria.

They have different mathematical structures.

We need typed evaluation semantics.

---

# 370.16 Candidate requirement taxonomy

A useful provisional taxonomy is:

$$
\boxed{
RequirementType=
\{
Constraint,
Condition,
Criterion,
Objective,
Goal,
AcceptanceCondition
\}.
}
$$

But this is not yet an ontology.

It may simply be a semantic type system.

---

# 370.17 Test whether the types are primitive

Can:

$$
Constraint
$$

be represented as an identity-bearing relation?

Yes.

For example:

$$
Requires(Q,r)
$$

and:

$$
Constrains(r,x).
$$

The semantics specify:

$$
C_r.
$$

No primitive required.

---

# 370.18 Criterion representation

Represent:

$$
Criterion(r,d).
$$

For example:

$$
Measures(r,Cost).
$$

Then the semantic contract determines how the criterion evaluates alternatives.

Again:

$$
Criterion
$$

is a typed relation/object projection.

---

# 370.19 Objective representation

Represent:

$$
Objective(r,U).
$$

where \(U\) is an externally defined utility/loss function.

Then:

$$
OptimizeUnder(r,U).
$$

The optimization mathematics remains external.

Thus:

$$
Objective
$$

does not require a Kernel primitive.

---

# 370.20 Goal representation

Represent:

$$
Goal(r,g).
$$

and:

$$
Targets(r,g).
$$

The goal's semantics determine acceptable future states.

Again:

$$
Goal
$$

is reifiable using existing identity and relations.

---

# 370.21 Acceptance condition

Represent:

$$
AcceptanceCondition(r,c).
$$

Then:

$$
Sat(K,c)
$$

may determine whether the condition holds.

No new Kernel primitive appears.

---

# 370.22 Requirement identity

Two requirements may have identical content but different scopes:

$$
r_1:
Votes\ge50\%
$$

for election A, and:

$$
r_2:
Votes\ge50\%
$$

for election B.

Thus:

$$
r_1\neq r_2
$$

may be required.

Identity handles this.

---

# 370.23 Requirement versioning

Suppose:

$$
r_1:
Votes\ge50\%
$$

and later:

$$
r_2:
Votes\ge40\%.
$$

Represent:

$$
Supersedes(r_2,r_1).
$$

Therefore requirement evolution follows existing lifecycle semantics.

---

# 370.24 Requirement provenance

A requirement may originate from:

* constitution;
* law;
* contract;
* user;
* policy;
* scientific protocol.

Represent:

$$
DerivedFrom(r,source).
$$

Again:

$$
Provenance
$$

does not create a primitive.

---

# 370.25 Requirement authority

A requirement may be authoritative only if:

$$
IssuedBy(r,A)
$$

and:

$$
Authorized(A,r).
$$

Therefore:

$$
Requirement
$$

does not intrinsically imply authority.

This is crucial in governance.

---

# 370.26 Requirement conflict

Two requirements can conflict:

$$
r_1:x\ge10
$$

$$
r_2:x\le5.
$$

Represent:

$$
Conflict(r_1,r_2).
$$

The system should not silently choose one.

Thus:

$$
\boxed{
RequirementConflict\neq InvalidRequirement.
}
$$

---

# 370.27 Requirement priority

A governance regime may assign:

$$
Priority(r_1)>Priority(r_2).
$$

But priority is a semantic/governance relation.

It need not become a universal property of requirements.

---

# 370.28 Requirement hierarchy

We may have:

$$
r_{high}
$$

and:

$$
r_{low}.
$$

Represent:

$$
Refines(r_{low},r_{high}).
$$

For example:

$$
Requirement:
\text{“secure authentication”}
$$

refined into:

$$
MFARequired
$$

$$
PasswordPolicy
$$

etc.

This fits our refinement calculus.

---

# 370.29 Requirement versus specification

A specification may contain many requirements:

$$
Spec=\{r_1,\ldots,r_n\}.
$$

Thus:

$$
Requirement\in Specification
$$

is a common organizational relation.

But specification itself can be identity-bearing and versioned.

No primitive needed.

---

# 370.30 Requirement versus contract

A contract may include:

$$
Requirements+\Constraints+\Semantics+\Authority+Lifecycle.
$$

Therefore:

$$
Requirement\neq Contract.
$$

A requirement is a component of a larger normative structure.

---

# 370.31 Requirement versus semantic contract

This distinction is especially important for KnowledgeOS.

A semantic contract:

$$
\Lambda_\rho=(C,T,M)
$$

defines what a relation means and how it behaves.

A requirement:

$$
r
$$

states what must be established, achieved, or accepted.

Therefore:

$$
\boxed{
SemanticContract\neq Requirement.
}
$$

---

# 370.32 Constraint versus semantic contract

A constraint:

$$
C_r(K)
$$

can be part of a semantic contract.

But not every semantic contract component is a requirement.

For example:

$$
T_\rho
$$

describes transitions.

It does not itself say:

> “This transition must occur.”

Therefore:

$$
\boxed{
C\neq Requirement.
}
$$

---

# 370.33 Goal versus objective

A goal may be qualitative:

> Improve election accessibility.

An objective may be quantitative:

$$
\max AccessibilityScore.
$$

Thus:

$$
Goal
$$

can be transformed into one or more measurable objectives, but:

$$
Goal\neq Objective
$$

in general.

This distinction matters for formalization.

---

# 370.34 Criterion versus metric

A criterion may use:

$$
Metric(x).
$$

But:

$$
Criterion\neq Metric.
$$

For example:

> “Evaluate candidates by experience.”

The metric could be:

$$
YearsExperience.
$$

The criterion determines how that metric participates in evaluation.

Thus:

$$
Metric
$$

belongs to a mathematical/evaluation regime.

---

# 370.35 Criterion versus score

Similarly:

$$
Score(d,C)
$$

is an evaluation result.

The criterion specifies how the score is generated.

Therefore:

$$
Criterion\neq Score.
$$

---

# 370.36 Requirement versus score

A score:

$$
0.82
$$

does not itself imply satisfaction.

We need:

$$
Threshold(r)=0.8.
$$

Then:

$$
0.82\ge0.8
$$

may yield:

$$
Sat=T.
$$

Therefore:

$$
\boxed{
Score\neq Satisfaction.
}
$$

---

# 370.37 Requirement versus threshold

A threshold is a parameter:

$$
\tau.
$$

The requirement is something like:

$$
x\ge\tau.
$$

Thus:

$$
Threshold\neq Requirement.
$$

Threshold semantics belong to the evaluation regime.

---

# 370.38 Requirement versus policy

A policy can define requirements:

$$
Policy\rightarrow Requirements.
$$

But:

$$
Policy
$$

may also define:

* authority;
* exceptions;
* procedures;
* escalation;
* lifecycle.

Thus:

$$
Requirement\subseteq Policy
$$

may be useful in a domain, but not universal ontology.

---

# 370.39 Requirement versus law

A law can create requirements.

But legal semantics also involve:

* jurisdiction;
* authority;
* interpretation;
* enforcement;
* exceptions;
* temporal validity.

Therefore:

$$
LegalRequirement
$$

is a specialized projection.

---

# 370.40 Requirement semantics as typed contract

We can therefore define a generic abstraction:

$$
\boxed{
r=
\langle
ID_r,
Type_r,
Target_r,
Sem_r,
Scope_r,
Deps_r
\rangle
}
$$

where:

$$
Type_r\in
\{Constraint,Criterion,Objective,Goal,\ldots\}.
$$

Again, this is a **candidate semantic representation**, not a new Kernel structure.

---

# 370.41 Can \(Type_r\) be eliminated?

Possibly, if the semantic relation itself identifies the type.

For example:

$$
HasConstraint(r,c)
$$

versus:

$$
HasObjective(r,o).
$$

Thus:

$$
Type_r
$$

may be encoded by relation type.

This is another application of:

$$
\boxed{
SemanticType\text{-}NonPromotion.
}
$$

---

# 370.42 Could all requirements be propositions?

No.

An objective such as:

$$
\max U(d)
$$

is not naturally a proposition.

It induces an ordering or optimization problem.

A goal can describe a desired trajectory.

A constraint can define a feasible region.

Thus:

$$
\boxed{
Requirement\ space\ is\ heterogeneous.
}
$$

---

# 370.43 Could all requirements be predicates?

Closer, but still insufficient.

A constraint can be:

$$
C(K)\in\{T,F\}.
$$

But an objective is naturally:

$$
U(d)\in\mathbb R.
$$

A preference relation is:

$$
d_1\succ d_2.
$$

Therefore a universal predicate-only representation loses objective semantics.

---

# 370.44 Could all requirements be utility functions?

No.

A hard requirement:

$$
x\ge0
$$

does not naturally become a utility function without arbitrary encoding.

One could construct:

$$
U(x)=
\begin{cases}
0 & x\ge0\\
-\infty & x<0
\end{cases}
$$

but that is an encoding, not a genuine reduction.

This is another anti-absorption case.

---

# 370.45 Could all requirements be transition conditions?

No.

A requirement can concern a static state:

$$
x\ge0.
$$

No transition is necessary.

Therefore:

$$
Requirement\not\equiv T.
$$

---

# 370.46 Could all requirements be \(M\)?

Again, one could encode arbitrary evaluation into:

$$
M.
$$

But that would absorb the entire requirement/evaluation layer into interpretation.

It is not reduction.

Therefore:

$$
\boxed{
Requirement\neq M.
}
$$

---

# 370.47 The correct abstraction

The evidence suggests that `Requirement` should not be another Kernel primitive.

Instead:

$$
\boxed{
Requirement
=
typed\ evaluative\ structure
}
$$

whose semantics are supplied by a regime.

The Kernel provides the representation substrate.

The evaluation layer interprets the requirement.

---

# 370.48 Requirement evaluation signatures

We therefore need multiple possible signatures.

### Constraint:

$$
Eval_C(K,r)\rightarrow\{T,F,U\}.
$$

### Criterion:

$$
Eval_{Crit}(x,r)\rightarrow Score.
$$

### Objective:

$$
Eval_{Obj}(d,r)\rightarrow Value
$$

or an ordering:

$$
d_1\succ_r d_2.
$$

### Goal:

$$
Eval_{Goal}(K_t,r)\rightarrow Degree/Status.
$$

### Acceptance:

$$
Eval_{Acc}(K,r)\rightarrow\{Accept,Reject,Undetermined\}.
$$

These cannot honestly be collapsed into one universal Boolean `Sat`.

---

# 370.49 This changes the Sat model

The previous:

$$
Sat(K,r)
$$

should now be treated as a **specialized evaluation projection**, not the universal evaluator for every normative object.

We can define:

$$
\boxed{
Sat
}
$$

for satisfaction-bearing requirements where a satisfaction relation is meaningful.

But:

$$
Criterion,\ Objective,\ Goal
$$

may require:

$$
Score,\ Order,\ Degree,\ Feasibility,\ Utility.
$$

---

# 370.50 Proposed evaluation algebra

A candidate generic evaluator:

$$
\boxed{
Eval_\Gamma(K,r)\rightarrow V_\Gamma
}
$$

where:

$$
V_\Gamma
$$

is regime-specific.

Then:

$$
Sat_\Gamma(K,r)
$$

is a projection:

$$
Sat_\Gamma
=
\pi_{sat}\circ Eval_\Gamma.
$$

This is much more general.

---

# 370.51 But avoid over-formalizing too early

We should not freeze:

$$
V_\Gamma
$$

as one universal mathematical codomain.

The statistical regime may require:

$$
\mathbb R,
$$

the governance regime:

$$
\{Compliant,NonCompliant,Pending\},
$$

and decision analysis:

$$
\mathbb R^k
$$

or a Pareto ordering.

Thus the evaluator remains typed.

---

# 370.52 Requirement satisfaction remains possible

For requirements whose semantics define satisfaction:

$$
\boxed{
Sat_\Gamma(K,r)
}
$$

remains valid.

For example:

$$
r:
Votes\ge50\%.
$$

Then:

$$
Sat_\Gamma(K,r)=
\begin{cases}
T & Votes(K)\ge50\%\\
F & Votes(K)<50\%\\
U & Votes(K)\text{ not established}.
\end{cases}
$$

This is an actual candidate concrete satisfaction semantics.

But we still need a canonical \(K_t\) variant before Gate B can be closed.

---

# 370.53 Requirement completeness

A requirement should itself be sufficiently specified.

A candidate well-formedness condition:

$$
WF_{Req}(r)
$$

might require:

$$
ID_r,
Type_r,
Target_r,
Semantics_r,
Scope_r.
$$

But exact conditions are domain-dependent.

Thus:

$$
WF_{Req}
$$

is itself a semantic contract.

---

# 370.54 Requirement underspecification

Example:

> “The system should be secure.”

This is a goal/requirement-like statement, but:

* secure against what?
* for whom?
* under which threat model?
* at what time?
* according to which standard?

Therefore:

$$
Eval(r)
$$

may be:

$$
U.
$$

This is not an evaluator failure.

It is requirement underspecification.

---

# 370.55 Requirement refinement

We can refine:

$$
r_0=\text{“system secure”}
$$

into:

$$
r_1=\text{“MFA required”}
$$

$$
r_2=\text{“critical CVEs = 0”}
$$

$$
r_3=\text{“session timeout}\le15\text{ min”}.
$$

Then:

$$
r_1,r_2,r_3
$$

are more operationally evaluable.

This gives:

$$
Refines(r_i,r_0).
$$

---

# 370.56 Requirement decomposition

A requirement can be conjunctive:

$$
r=r_1\land r_2\land r_3.
$$

Then:

$$
Sat(r)
=
Sat(r_1)\land Sat(r_2)\land Sat(r_3)
$$

for a conjunctive hard-requirement regime.

But not every requirement decomposes this way.

For soft or optimization criteria, aggregation is different.

Therefore:

$$
\boxed{
RequirementComposition
\text{ is regime-specific.}
}
$$

---

# 370.57 Requirement aggregation

Possible aggregators include:

$$
\min_i Sat_i
$$

$$
\sum_i w_i Score_i
$$

$$
Pareto(Score_1,\ldots,Score_n)
$$

$$
LexicographicOrder.
$$

There is no universal aggregation law.

This is highly relevant to MCDA work.

---

# 370.58 Statistical perspective

In statistics, a hypothesis test criterion:

$$
p<0.05
$$

is a decision criterion.

It is not identical to:

$$
H_0=False.
$$

Likewise:

$$
RequirementSatisfied
$$

means the evaluation criterion was met, not that the underlying proposition is metaphysically true.

This preserves the epistemic architecture.

---

# 370.59 MCDA perspective

For PROMETHEE, TOPSIS, ELECTRE, WSM, SMAA, etc., a criterion can be:

$$
g_j(d).
$$

The criterion produces a value, preference relation, or ranking contribution.

It does not simply return:

$$
T/F.
$$

Thus the requirement framework must accommodate:

$$
Criterion\rightarrow Score/Preference.
$$

This strongly supports the typed evaluation model.

---

# 370.60 Governance perspective

For a constitutional rule:

$$
Quorum\ge50\%.
$$

we may have:

$$
Sat=T.
$$

For:

> “Candidate A is preferable to Candidate B”

we may instead have:

$$
Preference(A,B).
$$

These are different evaluation structures even though both can influence decisions.

---

# 370.61 DDD perspective

The domain should not have:

```text id="d4g6zx"
Requirement
    evaluate(): boolean
```

as a universal abstraction.

That would force:

* objectives;
* rankings;
* probabilities;
* soft constraints;
* governance statuses

into a Boolean model.

Instead:

```text id="h9x7as"
Requirement
Criterion
Constraint
Objective
Goal
```

can be domain-specific semantic types sharing a common referential substrate.

---

# 370.62 But should they share a common interface?

Possibly:

$$
Evaluable
$$

with:

$$
Eval(context)\rightarrow TypedResult.
$$

This is an architectural candidate.

But `Evaluable` should not be promoted to a Kernel primitive.

It is a service-level protocol.

---

# 370.63 Relation to semantic contracts

The evaluator itself can be governed by:

$$
\Lambda_{Eval}
=
(C_{Eval},T_{Eval},M_{Eval}).
$$

This preserves our three-layer law basis.

For example:

* \(C_{Eval}\): evaluator input requirements;
* \(T_{Eval}\): evaluation lifecycle;
* \(M_{Eval}\): meaning of evaluation.

Thus even the evaluation service does not force a fourth **law layer**.

---

# 370.64 New result: Requirement is not one mathematical kind

We can now state:

$$
\boxed{
Requirement
\not\cong
Predicate
}
$$

in general.

It can denote a family of normative/evaluative structures.

Therefore we should not attempt to give every requirement a single universal mathematical representation.

---

# 370.65 Primitive attack

Can requirement itself be reconstructed?

Yes, represent:

$$
Requires(Q,r)
$$

where \(r\) is an identity-bearing semantic object.

Its type and meaning are supplied by relations/contracts.

Therefore:

$$
\boxed{
Requirement\notin B_K.
}
$$

No new Kernel primitive.

---

# 370.66 Goal attack

Similarly:

$$
Goal
$$

is representable as a typed semantic object:

$$
HasGoal(A,g).
$$

No primitive.

---

# 370.67 Objective attack

Likewise:

$$
Objective
$$

can be:

$$
HasObjective(D,o).
$$

The optimization semantics are external.

No primitive.

---

# 370.68 Criterion attack

Likewise:

$$
UsesCriterion(D,c).
$$

No primitive.

---

# 370.69 Constraint attack

Likewise:

$$
HasConstraint(S,c).
$$

No primitive.

---

# 370.70 Acceptance condition attack

Likewise:

$$
RequiresAcceptance(S,a).
$$

No primitive.

---

# 370.71 Specification attack

A specification can be a collection:

$$
Spec=\{r_i\}.
$$

Its identity and lifecycle are representable.

No primitive.

---

# 370.72 Contract attack

A contract can combine:

$$
Requirements+
Semantics+
Authority+
Lifecycle.
$$

Again, it is a structured domain object.

No universal Kernel primitive.

---

# 370.73 Strong pairwise ablation

### Remove identity

Cannot distinguish two independently issued requirements.

### Remove relation structure

Cannot associate requirements with targets/scopes.

### Remove semantics

Cannot know what the requirement means.

### Remove evaluation regime

Cannot determine whether/how it is satisfied.

No additional universal primitive appears.

---

# 370.74 Anti-absorption test

Could we encode:

$$
Requirement
$$

entirely inside:

$$
M?
$$

Technically yes.

But then:

$$
M
$$

contains the entire normative/evaluative structure.

That is not reduction.

Likewise:

$$
Requirement\rightarrow C
$$

works only for hard structural constraints.

Therefore no universal collapse is valid.

---

# 370.75 Requirement as semantic role

The strongest current formulation is:

$$
\boxed{
Requirement
=
\text{a typed, identity-bearing normative/evaluative structure}
}
$$

whose semantics are supplied by a context/regime.

It is not a universal ontological primitive.

---

# 370.76 Proposed generic representation

A requirement-like object can be modeled as:

$$
\boxed{
r=
(ID_r,\rho_r,args_r)
}
$$

where:

$$
\rho_r
$$

is a relation type such as:

$$
Requires,
Constrains,
Optimizes,
Evaluates,
Accepts,
Targets.
$$

Its semantics come from:

$$
\Lambda_{\rho_r}
=
(C_{\rho_r},T_{\rho_r},M_{\rho_r}).
$$

This preserves the Kernel reduction.

---

# 370.77 Requirement evaluation pipeline

The architecture now becomes:

$$
\boxed{
K
\rightarrow
Requirement
\rightarrow
EvaluationRegime
\rightarrow
EvalResult
}
$$

and only for satisfaction-bearing requirements:

$$
\boxed{
EvalResult
\rightarrow
Sat.
}
$$

For objectives:

$$
EvalResult\rightarrow Score/Order.
$$

For criteria:

$$
EvalResult\rightarrow Preference/Score.
$$

For hard constraints:

$$
EvalResult\rightarrow T/F/U.
$$

---

# 370.78 This resolves an earlier ambiguity

Previously:

$$
Sat(K,r)
$$

looked like it might be the universal evaluator.

It is not.

The more precise architecture is:

$$
\boxed{
Eval_\Gamma(K,r)\rightarrow V_\Gamma
}
$$

with:

$$
Sat_\Gamma(K,r)
$$

as one typed projection.

This should prevent future mathematical confusion.

---

# 370.79 Gate B consequence

We are now closer to constructing the first concrete satisfaction evaluator.

But we still must not claim Gate B closed.

We need:

1. a concrete \(K_t\) variant;
2. a concrete requirement \(r\);
3. an explicit evaluation regime;
4. an explicit evaluator;
5. a reconstruction/validation test.

Until then:

$$
\boxed{
Gate\ B=\textbf{HARD STOP}.
}
$$

---

# 370.80 Step 370 verdict

## **PASS — Requirement / Criterion / Constraint / Goal Reduction**

The attack demonstrates:

$$
\boxed{
Requirement
\not\equiv
Constraint
\not\equiv
Criterion
\not\equiv
Goal
\not\equiv
Objective
}
$$

as universal mathematical concepts.

However, none requires an independent Kernel primitive.

They can be represented as:

$$
\boxed{
typed\ identity\text{-}bearing\ semantic\ structures
}
$$

with regime-specific evaluation.

---

# 370.81 New principle

## **Normative-Type Non-Promotion Principle**

> Normative or evaluative roles such as requirement, constraint, criterion, goal, objective, and acceptance condition must not be promoted to universal Kernel primitives merely because they are first-class concepts in a domain. Primitive status requires demonstrated non-reconstructibility.

Formally:

$$
NormativeRole(x)
\not\Rightarrow
x\in B_K.
$$

---

# 370.82 New principle

## **Typed Evaluation Principle**

> Evaluation results must preserve the mathematical type appropriate to the evaluation regime; a universal Boolean satisfaction value must not be imposed on objectives, rankings, utilities, preferences, or other non-Boolean evaluation structures.

Thus:

$$
\boxed{
Eval_\Gamma(K,r)\rightarrow V_\Gamma
}
$$

rather than universally:

$$
Eval(K,r)\rightarrow\{T,F\}.
$$

---

# 370.83 Updated evaluation architecture

The strongest current candidate is now:

$$
\boxed{
\begin{array}{ccccc}
Kernel &\rightarrow& Semantic\ Contracts
&\rightarrow& Regime\\
&&&&\downarrow\\
&&&&Evaluation\\
&&&&\downarrow\\
&&&&Typed\ Result
\end{array}}
$$

with:

$$
Sat
$$

being one evaluation projection rather than the entire evaluation theory.

---

# 370.84 Updated KnowledgeOS layer model

The working architecture now looks like:

$$
\boxed{
L_0:\ ID+\mathcal R^\star
}
$$

$$
\downarrow
$$

$$
\boxed{
L_1:\mathsf{Sem}=(C,T,M)
}
$$

$$
\downarrow
$$

$$
\boxed{
L_2: Evaluation
}
$$

$$
\downarrow
$$

$$
\boxed{
L_3: Epistemic\ Services
}
$$

where:

$$
L_2:
Eval,\ Verify,\ Sat,\ Score,\ Preference,\ Optimize,\ldots
$$

and:

$$
L_3:
Zero,\ EA,\ Det,\ Adeq,\ Decision,\ldots
$$

remain candidates for further refinement.

---

# 370.85 The next attack

The next problem is now unavoidable.

If:

$$
Eval_\Gamma(K,r)\rightarrow V_\Gamma,
$$

then what exactly is an **evaluation result**?

Is:

$$
Result
$$

just a value?

Or does a valid epistemic evaluation require:

$$
Value+
Provenance+
Regime+
Evidence+
Time+
Confidence+
Justification?
$$

This is not merely a data-model question.

It determines whether:

$$
EvaluationResult
$$

is a simple projection, a typed semantic object, or a new irreducible epistemic structure.

Therefore the next attack should be:

# **Step 371 — Evaluation Result / Judgment / Evidence / Justification Irreducibility Attack**

The decisive test is:

$$
\boxed{
EvaluationResult
\stackrel{?}{=}
Value+
Identity+
Provenance+
SemanticRegime+
Justification
}
$$

and, more fundamentally:

$$
\boxed{
Judgment
\stackrel{?}{=}
EvaluationResult
\stackrel{?}{=}
Determination
}
$$

We must avoid collapsing:

$$
Evaluation
\neq
Evidence
\neq
Justification
\neq
Determination
\neq
Knowledge
\neq
Decision.
$$

This is the next critical boundary before we attempt the first concrete \(Sat(K_t,r)\) construction.
