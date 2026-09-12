Below is the draft for KR-EPISTEMIC-AGENCY-2026-09 Sub-Specification: Expected Utility Formalization for Epistemic Action Selection.
This specification defines the multi-attribute utility operator EU(a \mid K_t, Q_t, C_t, S_t, R_t) operating strictly over the candidate set \mathcal{A}_t^{\text{feasible}} established in Section 5.
1. General Operator Definition
The optimal next epistemic action a^* \in \mathcal{A}_t^{\text{feasible}} maximizes expected utility under current knowledge state K_t, active inquiry Q_t, domain schedule S_t, context C_t, and risk policy R_t:
2. Multi-Attribute Utility Decomposition
The total expected utility EU(a) is decomposed into four weighted components:
  ========================================================================================
  UTILITY DECOMPOSITION ATTRIBUTES
  ========================================================================================
  Term                   Name                         Core Function / Objective
  ----------------------------------------------------------------------------------------
  E[U_gap(a)]            Expected Epistemic Value     Measures predicted reduction in gap Δ_Q(K_t)
  C_resource(a)          Resource Expenditure Cost    Scalar cost derived from the Resource Vector R_t
  Ω_risk(a)              Operational & Policy Hazard  Non-linear penalty for physical, policy, or state risk
  U_task(a)              Pragmatic Task Utility      Direct task alignment or external milestone progress
  ========================================================================================

3. Mathematical Formulation of Utility Components
3.1 Expected Epistemic Value: \mathbb{E}\big[U_{\text{gap}}(a)\big]
Let \Delta_Q(K_t) denote the current typed gap, and \Delta_Q(K_{t+1}^a) denote the predicted gap after executing action a and acquiring observation O_{a}. The expected reduction is weighted by action reliability \rho(a):
Where \Vert{}\cdot\Vert{}_{\mathcal{G}} is a norm over the gap space, and w_g \in \mathbb{R}^+ is the epistemic priority weight.
3.2 Resource Expenditure Cost: C_{\text{resource}}(a)
Translates monetary, computational, and time/latency metrics from the Resource Vector \mathbf{R}_t(r) into a normalized cost scalar:
3.3 Risk & Hazard Penalty: \Omega_{\text{risk}}(a)
Evaluates potential state degradation, privacy violations, or physical hazards. Incorporates a risk-aversion scaling factor \beta(R_t) \ge 1 dictated by the active risk policy R_t:
3.4 Pragmatic Task Utility: U_{\text{task}}(a)
Captures domain-specific deadlines or task rewards that favor immediate action over extended investigation:
4. Trade-Off Scaling & Policy Calibration
The vector of preference weights \mathbf{W} = \langle w_g, w_m, w_c, w_{\lambda}, w_t \rangle is constrained by the policy contract \Pi:
 * High-Precision Epistemic Mode (e.g., Medical/Safety): Policy forces w_g \gg C_{\text{resource}}, heavily penalizing a_{\text{guess}} and rewarding multi-source confirmation actions.
 * Low-Latency Operational Mode (e.g., Real-time Navigation): Policy increases w_{\lambda} and w_t, favoring low-latency local actions over high-cost external queries.
5. Selection Invariant & Fallback
If two candidate actions yield equivalent expected utility (\vert{}EU(a_1) - EU(a_2)\vert{} < \epsilon), the selection operator applies a deterministic tie-breaking invariant:
Tie-breaking prioritizes the action with lower latency, minimizing state divergence during epistemic processing.
#
This is a good next sub-specification, but I would **not freeze it yet**. The expected-utility layer is appropriate for Epistemic Agency, but several mathematical choices currently turn what should be an experimental decision framework into implicit architectural assumptions.

## Senior verdict

**KR-EPISTEMIC-AGENCY — Expected Utility Sub-Spec**

**Status:** `[PROP][OPEN] — APPROVE FOR REFINEMENT, NOT FREEZE`

The central idea is sound:

$$
a^*
=
\arg\max_{a\in\mathcal A_t^{feasible}}
EU(a\mid K_t,Q_t,C_t,S_t,R_t)
$$

but I recommend treating the **utility decomposition, gap norm, weights, reliability multiplier, risk function, and tie-breaker as candidate mechanisms**, not part of KnowledgeOS theory.

The most important correction is:

> **Do not let the utility function define what "epistemically better" means before the experiment has established that.**

---

# 1. The strongest part: selection occurs after feasibility

This is architecturally correct:

$$
\mathcal A_t^{possible}
\rightarrow
\mathcal A_t^{feasible}
\rightarrow
EU
\rightarrow
a^*
$$

This preserves:

$$
\boxed{
Feasible\neq Preferred
}
$$

An action that is impossible, unauthorized, unsafe, or resource-incompatible must never enter the utility competition.

That is a strong architectural boundary.

---

# 2. But `EU` should not yet be called "optimal"

I would change:

> "The optimal next epistemic action..."

to:

> **"The utility-selected next epistemic action under the declared decision model..."**

Why?

Because:

$$
\arg\max EU
$$

is optimal **relative to the utility model**.

It does not establish objective optimality.

Formally:

$$
a^*_{U}
=
\arg\max_a EU_U(a)
$$

where \(U\) is the declared utility model.

Different legitimate utility models can produce different actions.

This is especially important because you already established that epistemic standards can vary.

---

# 3. The four components are useful, but don't assume additivity

You currently have:

$$
EU(a)
=
E[U_{gap}(a)]
-
C_{resource}(a)
-
\Omega_{risk}(a)
+
U_{task}(a).
$$

This is a perfectly reasonable **candidate model**.

But the additive form is not theoretically established.

For example, risk may interact with epistemic benefit:

$$
Risk(a,U_{gap})
$$

rather than simply subtracting an independent penalty.

Likewise, task utility and epistemic utility may be non-additive.

So record:

$$
\boxed{
EU = F(U_{gap},C,\Omega,U_{task})
}
$$

as the general specification, and treat the additive version as:

$$
[PROP]\quad
EU_{\text{additive}}.
$$

Then test whether the additive model is sufficient.

---

# 4. The biggest mathematical problem: a norm over the gap space

You write:

$$
\Vert\Delta_Q(K_t)\Vert_{\mathcal G}.
$$

This is currently underdefined.

Our gap is explicitly **typed**.

It may contain:

* missing value
* missing dimension
* contradiction
* insufficient evidence
* unobservable quantity
* underdetermination
* model insufficiency
* semantic ambiguity
* etc.

These don't naturally form a vector space.

Therefore a generic norm:

$$
\|\Delta\|_{\mathcal G}
$$

should **not** be assumed.

You need something more general:

$$
\boxed{
U_{gap}(a)
=
V\left(
\Delta_t,\Delta_{t+1}^{a};
Q,C,EC,S
\right)
}
$$

where \(V\) is a candidate **gap-value/reduction function**.

Only if experiments establish a numerical structure could you introduce:

$$
d(\Delta_t,\Delta_{t+1}).
$$

This is important because your Zero research has repeatedly shown that different gap classes are not interchangeable.

---

# 5. "Gap reduction" itself needs care

Suppose:

$$
|\Delta_{t+1}|<|\Delta_t|.
$$

That does not necessarily mean epistemic improvement.

Example:

```text
Before:
3 unresolved hypotheses

After:
1 hypothesis

But the surviving hypothesis is false.
```

Raw gap cardinality improved.

Knowledge did not.

We already discovered the same problem in the candidate-selection experiments:

$$
\boxed{
Reduction\neq Validation\neq Determination\neq Knowledge
}
$$

Therefore:

$$
U_{gap}
$$

must not silently become:

$$
KnowledgeGain.
$$

I recommend the term:

> **Expected Gap Resolution Value**

rather than Expected Epistemic Value, at least initially.

---

# 6. The reliability multiplier is dangerous

You propose weighting expected gap reduction by action reliability:

$$
\rho(a)\cdot E[\Delta Red].
$$

This risks double counting.

Suppose:

$$
\rho(a)
$$

already influences the probability distribution over observations:

$$
P(O\mid a,K).
$$

Then multiplying again by \(\rho(a)\) can count reliability twice.

The cleaner formulation is:

$$
\boxed{
E[U(a)]
=
\sum_o
P(o\mid a,K_t,\mathcal M)
U(a,o)
}
$$

where reliability belongs in the observation/evidence model if appropriate.

Then resource reliability becomes part of:

$$
P(O\mid a,\mathcal R_t,\ldots).
$$

This is much more statistically defensible.

---

# 7. Resource cost should remain vector-valued as long as possible

Your previous resource specification had:

$$
\gamma_t=(Monetary,Computational,HumanEffort).
$$

Now you're converting it into:

$$
C_{resource}(a).
$$

That is acceptable for a utility model, but don't lose the underlying vector.

Keep:

$$
C(a)=
(C_{\$},C_{cpu},C_{human},C_{time},\ldots)
$$

and define:

$$
C_U(a)=w_C^\top C(a)
$$

only inside a particular utility regime.

Why?

Because:

> 10 seconds of latency

and

> €10

are not intrinsically commensurable.

The weights are a **policy/decision-model choice**, not a mathematical truth.

---

# 8. Risk must be separated from policy

You currently write:

> risk policy \(R_t\)

and:

$$
\beta(R_t).
$$

As noted earlier, \(R_t\) is already a candidate **reasoning regime**.

Use:

$$
\mathsf{RiskPolicy}_t.
$$

Then distinguish:

$$
Risk(a)
$$

from:

$$
RiskTolerance(\mathsf{RiskPolicy}).
$$

For example:

$$
\mathsf{RiskPolicy}
\rightarrow
RiskConstraint
$$

is cleaner than hiding policy inside the risk function.

Even better, some risks should be **hard constraints**, not utility penalties.

For example:

$$
PrivacyViolation(a)=1
$$

may mean:

$$
a\notin\mathcal A^{feasible}
$$

rather than:

$$
EU(a)-1000.
$$

This preserves the important distinction:

$$
\boxed{
Constraint\neq Preference
}
$$

---

# 9. Your "high precision mode" should not simply change weights

This:

> medical/safety → \(w_g\gg C\)

is intuitive but potentially problematic.

Safety-critical requirements may be **constraints**, not merely high utility weights.

For example:

$$
P(\text{unsafe action})\le\epsilon
$$

could be mandatory.

Then:

$$
\mathcal A^{safe}
=
\{a:P(\text{harm}\mid a)\le\epsilon\}
$$

and only then:

$$
a^*=\arg\max_{a\in\mathcal A^{safe}}EU(a).
$$

This is architecturally superior.

---

# 10. Your tie-breaking rule should be removed for now

You propose:

> if utilities are equivalent, choose lower latency.

This is reasonable operationally, but it is **not an invariant**.

Why?

Imagine:

```text
Action A: 2 seconds, weak evidence
Action B: 10 seconds, strong evidence
```

If utilities are genuinely tied because the model says they are equivalent, automatically selecting latency may violate the epistemic objective.

More importantly:

$$
EU(a_1)\approx EU(a_2)
$$

does not mean:

$$
a_1\equiv_{sem}a_2.
$$

So I would call it:

> **candidate deterministic tie-break policy**

not:

> selection invariant.

And ideally:

$$
TieBreakPolicy
$$

should itself be configurable under the active decision regime.

---

# 11. Add an "epistemic sufficiency" stopping action

Your candidate action list should include:

$$
\boxed{Stop}
$$

or `NoFurtherInvestigation`.

Why?

Suppose:

$$
Adeq(K_t,Q,\ldots)=True.
$$

Continuing to investigate can have negative utility.

So:

$$
Stop\in\mathcal A^{possible}.
$$

Then:

$$
EU(Stop)
$$

can legitimately win.

This prevents KnowledgeOS from becoming an agent that **always searches**.

That is a very important property.

---

# 12. The metro example now becomes mathematically clean

Suppose:

$$
Q=\text{Which metro direction?}
$$

Current state:

$$
Adeq(K_t,Q,\ldots)=False.
$$

Candidate actions:

$$
\mathcal A=
\{
InspectSign,
ConsultMap,
AskHuman,
Guess,
Stop
\}.
$$

Feasibility removes prohibited actions:

$$
\mathcal A^{feasible}
=
\{
InspectSign,
ConsultMap,
AskHuman
\}.
$$

Then utility evaluates them.

For example:

$$
EU(InspectSign)=0.82
$$

$$
EU(ConsultMap)=0.71
$$

$$
EU(AskHuman)=0.39.
$$

Therefore:

$$
a^*=InspectSign.
$$

Then execution produces:

$$
O_{t+1}
\rightarrow
E_{t+1}
\rightarrow
Assessment
\rightarrow
Determination.
$$

That's a clean implementation of your human example.

---

# 13. But here's the crucial statistical experiment

Don't just test whether the system chooses:

> map vs sign vs human.

Test **counterfactual action quality**.

For every candidate action \(a\), estimate:

$$
P(
Adeq(K_{t+1}^a,Q)=1
\mid
K_t,Q,\mathcal R_t
).
$$

Then compare selected action with alternatives.

Define candidate:

$$
\boxed{
Regret(a)
=
U(a^*)-U(a)
}
$$

or, if utility isn't trusted yet, use outcome measures such as:

* determination probability
* answerability
* evidence quality
* cost
* risk
* time.

This lets you ask:

> Does the utility-selected action actually produce better epistemic outcomes?

That is much stronger than demonstrating that the formula generates a number.

---

# 14. We also need an adversarial test

Construct:

### Action A

High predicted utility because the resource has historically been reliable.

But today it is wrong.

### Action B

Lower historical reliability.

But it provides independent evidence.

Then see whether the system can recognize:

$$
Independence
$$

and:

$$
CurrentEvidenceQuality.
$$

This connects directly to our previous discovery:

$$
\boxed{
Agreement(Selection,Validation)
\not\Rightarrow Truth.
}
$$

---

# 15. Important knowledge-engineering addition: decision provenance

I strongly recommend adding this to the sub-spec.

For every selected action:

$$
\boxed{
ActionDecisionRecord_t
}
$$

containing at minimum:

```text
Inquiry
Current Knowledge State
Typed Gap
Candidate Actions
Resource States
Feasibility Results
Utility Model
Utility Parameters
Risk Policy
Selected Action
Rejected Alternatives
Decision Reason
Authorization
```

Then KnowledgeOS can answer:

> **"Why did you choose this action?"**

That is a major requirement for inspectability.

---

# 16. Revised formal structure

I would currently define the architecture as:

$$
\boxed{
\mathcal A_t^{possible}
=
Generate(K_t,Q_t,\Delta_t,\mathcal R_t,C_t)
}
$$

$$
\boxed{
\mathcal A_t^{feasible}
=
\{a\in\mathcal A_t^{possible}:
Feasible(a\mid
\mathcal R_t,C_t,\Pi_t,\mathsf{RiskPolicy}_t)
\}
}
$$

Then:

$$
\boxed{
a_t^*
=
Select_U(
\mathcal A_t^{feasible}
\mid
K_t,Q_t,\Delta_t,C_t,S_t,R_t,\mathsf{RiskPolicy}_t
)
}
$$

where:

$$
Select_U
=
\arg\max EU_U
$$

is only one candidate selection regime.

That last point is important:

> **Selection is the capability; expected utility is one possible implementation/regime for selection.**

This prevents EU itself from becoming a kernel primitive.

---

# 17. This directly answers the kernel question

Your architecture now has:

$$
Generate
\rightarrow
Feasible
\rightarrow
Evaluate
\rightarrow
Select
$$

and:

$$
EU
$$

is an evaluation mechanism.

Therefore the research question becomes beautifully sharp:

> Is `NextEpistemicAct` an irreducible semantic capability, or can it be represented as composition of existing capabilities plus a declared selection regime?

Candidate composition:

$$
\boxed{
NextEpistemicAct
=
Generate
+
Feasible
+
Evaluate
+
Select
+
Policy
}
$$

If that composition is semantically equivalent to the proposed agency operator:

$$
NextEpistemicAct
\equiv_{sem}
Composition
$$

then **no kernel expansion**.

That is exactly aligned with your frozen kernel-minimality programme.

---

# 18. One deeper conceptual separation

I would record this as a candidate principle:

$$
\boxed{
Epistemic\ Value
\neq
Task\ Utility
\neq
Operational\ Utility
}
$$

For example:

> Asking the user for their destination may have low immediate task utility but high epistemic value because it makes the inquiry well-formed.

That is your metro example again.

So the loop is actually:

$$
InquiryFormation
\rightarrow
GapResolution
\rightarrow
ActionSelection.
$$

Not everything is "gap reduction."

---

# 19. My recommended status changes

### General EU operator

`[PROP][OPEN]`

Good.

### Additive four-component model

`[PROP][OPEN]`

Candidate decision model, not theory.

### Gap norm

**OPEN — do not freeze.**

Prefer general value function.

### Reliability multiplier

**REMOVE from current formal core.**

Put reliability into the evidence/observation model unless an experiment demonstrates an independent role.

### Weight vector

`[PROP][REGIME-DEPENDENT]`

Not universal.

### Risk penalty

Separate hard constraints from soft preferences.

### Tie-break

`[PROP][POLICY]`, not invariant.

### Stop/NoOp

**ADD.**

### ActionDecisionRecord

**ADD.**

---

# 20. Final senior verdict

I would record:

> **KR-EPISTEMIC-AGENCY-2026-09 — Expected Utility Sub-Specification: ACCEPTED FOR RESEARCH, NOT FROZEN.**

The core architecture is now sound:

$$
\boxed{
Gap
\rightarrow
Candidate\ Acts
\rightarrow
Feasibility
\rightarrow
Utility/Decision\ Regime
\rightarrow
Selection
\rightarrow
Authorization
\rightarrow
Execution
\rightarrow
Observation
\rightarrow
Evidence
\rightarrow
Determination
\rightarrow
K_{t+1}
}
$$

But the research discipline should be:

$$
\boxed{
Selection\ Capability
\neq
Expected\ Utility\ Formula
}
$$

and:

$$
\boxed{
Gap\ Reduction
\neq
Knowledge\ Gain.
}
$$

The **most important next experiment** is therefore no longer merely "does the system choose the map when the map is available?"

It should be:

> **Can a composition of existing KnowledgeOS capabilities reproduce the observable action-selection behaviour of the proposed Epistemic Agency operator across changes in inquiry, resources, risk, standards and context?**

If yes:

$$
\boxed{
Epistemic\ Agency = Composite\ Capability
}
$$

and the kernel remains untouched.

If no, we investigate exactly **which capability is missing** before even considering a new primitive.

That is the mathematically and architecturally correct path.
