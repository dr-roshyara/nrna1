Below is the current, consolidated KnowledgeOS Research Ledger and Artifact Status Matrix, reflecting all active proposals, frozen theory baselines, and downstream downstream research targets as of September 2026.
KnowledgeOS System Core Baselines
| Core Component | ID / Version | Status | Governance Notes |
|---|---|---|---|
| System Theory Baseline | Theory v1.2 | [FROZEN] | Primary architecture reference; protected against upstream contamination. |
| Minimal Execution Kernel | Minimal Kernel v1.0 | [UNTOUCHED] | Stable operational kernel; strict isolation enforced. |
Active Research Ledger & Artifact Status Matrix
| ID | Title / Focus Domain | Status / Standing | Primary Focus & Dependencies |
|---|---|---|---|
| KR-ZOOM-FACTFINDING-01-2026-09 | Type A State Fact-Finding, Zoom-In Diagnostics | [PROP][ACTIVE] | Active Empirical Target. Evaluates diagnostic hypothesis testing (\text{ZF2}, \text{ZF3}, \text{ZF5}). Prerequisite for downstream action research. |
| KR-ACTION-FACTFINDING-2026-09 | Type B Action Fact-Finding, ActionRationale (AR_t), Action Warrant Protocol | [PROP][OPEN] | Downstream Target. Formulates 3-layer architecture, AR_t object schema, U(a) = F_U non-additive utility, and causal provenance. |
| KR-CH2-STRUCTURAL-2026 | Chapter 2 Structural Lens & Inquiry Reframing | [EXT][PROP] | Layer 1 Problem Discovery primitives; maps input observation (O_t) to refined inquiry (Q_t). |
| KR-EPISTEMIC-AGENCY-2026 | Epistemic Agency & Autonomous Goal Formulation | [PROP][OPEN] | Governs agent self-directed fact-checking and inquiry-generation boundaries under epistemic constraints. |
| KR-EXPECTED-UTILITY-2026 | Non-Additive Utility Modeling & Risk Regimes | [PROP][OPEN] | Provides general evaluation functions F_U(U_{\text{local}}, U_{\text{systemic}}, \text{Risk}, \dots) for candidate action evaluation. |
Core Theoretical & Invariant Invariants
 * Five-Stage Non-Equivalence Chain:
   
 * Action Non-Derivability:
   
 * Candidate Action Space (\mathcal{A}_Q): Includes explicit operational choices \{a_1, \dots, a_n, \text{NoOp}, \text{Wait}, \text{InvestigateFurther}, \text{Mitigate}\}.
#
This ledger is coherent with the research state we have established. I would make **one governance-level correction**: the ledger should distinguish **active execution** from **downstream research proposals**, because `KR-ZOOM-FACTFINDING-01` is the only experiment currently authorized to execute.

## Current adjudicated ledger

| Artifact                   | Status             | Interpretation                                          |
| -------------------------- | ------------------ | ------------------------------------------------------- |
| **Theory v1.2**            | **[FROZEN]**       | Protected theoretical baseline                          |
| **Minimal Kernel v1.0**    | **[UNTOUCHED]**    | No new primitive admitted                               |
| **KR-ZOOM-FACTFINDING-01** | **[PROP][ACTIVE]** | Current empirical experiment; ZF2/ZF3/ZF5 + ZF1 control |
| **KR-ACTION-FACTFINDING**  | **[PROP][OPEN]**   | Downstream; do not execute yet                          |
| **KR-CH2-STRUCTURAL**      | **[EXT][PROP]**    | Source-derived inquiry/reframing lens                   |
| **KR-EPISTEMIC-AGENCY**    | **[PROP][OPEN]**   | Downstream agency architecture                          |
| **KR-EXPECTED-UTILITY**    | **[PROP][OPEN]**   | Decision-evaluation regime                              |

That ordering is important:

$$
\boxed{
Theory\ v1.2
\;>\;
Active\ Experiment
\;>\;
Downstream\ Proposals
}
$$

A downstream proposal cannot silently become an upstream dependency.

---

# One correction to the terminology

You wrote:

> “Active Research Ledger & Artifact Status Matrix”

and:

> “downstream downstream research targets”

I would use simply:

$$
\boxed{\text{Downstream Research Targets}}
$$

The second “downstream” adds no semantic information.

More importantly, `KR-ACTION-FACTFINDING` should explicitly say:

> **Prerequisite:** result of KR-ZOOM-FACTFINDING-01

rather than merely “downstream.”

That creates a genuine research dependency.

---

# The invariant section should be completed

Your pasted ledger ends with:

> Five-Stage Non-Equivalence Chain:

but the equations are missing.

I recommend recording the current chain as:

$$
\boxed{
F_t
\neq
W_t
\neq
Decision_t
\neq
Authorization_t
\neq
Action_t
}
$$

where:

* \(F_t\) = State Determination
* \(W_t\) = Action Warrant
* \(Decision_t\) = selected action
* \(Authorization_t\) = governance/policy authorization
* \(Action_t\) = executed action

But this is a **candidate research separation**, not yet a proved invariant.

Therefore status:

**`[PROP][OPEN]`**

not `[INVARIANT]` or `[PROVED]`.

---

## Action non-derivability

The ledger should also make explicit what you currently mean by “Action Non-Derivability.”

The useful statement is:

$$
\boxed{
Determine(H)\not\Rightarrow Select(a)
}
$$

and:

$$
\boxed{
\neg Determine(H)\not\Rightarrow NoAction
}
$$

The first says that knowing the state/cause does not uniquely determine the action.

The second says that uncertainty does not necessarily permit inaction.

Both remain:

**`[PROP][OPEN]`**

until tested.

---

# Candidate Action Space

Your current formulation:

$$
\mathcal A_Q=
\{a_1,\ldots,a_n,
NoOp,
Wait,
InvestigateFurther,
Mitigate\}
$$

needs one small governance qualification.

Do **not** make `Mitigate` universally mandatory.

Better:

$$
\boxed{
\mathcal A_Q
=
\mathcal A_Q^{domain}
\cup
\mathcal A_Q^{epistemic}
}
$$

where:

$$
\mathcal A_Q^{epistemic}
\supseteq
\{NoOp,Wait,InvestigateFurther\}
$$

when applicable, and `Mitigate` is included when the domain supports it.

That keeps the research model domain-independent.

---

# The most important dependency chain now

I would add this explicitly to the ledger:

```text
Chapter 2 Structural Lens
          │
          ▼
Problem / Inquiry Discovery
          │
          ▼
KR-ZOOM-FACTFINDING-01
          │
          ├── ZF2 Dimension Expansion
          ├── ZF3 Cross-Boundary Discovery
          └── ZF5 Zoom-In ≠ Fact-Finding
          │
          ▼
State Fact-Finding
          │
          ▼
KR-ACTION-FACTFINDING
          │
          ├── ActionRationale
          ├── Action Warrant
          ├── Candidate Actions
          └── Causal / Utility Evaluation
          │
          ▼
KR-EPISTEMIC-AGENCY
          │
          ▼
Decision → Authorization → Action
```

With `KR-EXPECTED-UTILITY` functioning as a supporting evaluation regime rather than automatically becoming part of the kernel.

---

# What is actually being tested now?

There is a very important distinction between the ledger's **architectural vision** and the **current empirical question**.

### Current experiment

Only:

$$
\boxed{
Can\ inquiry-directed\ Zoom-In
discover\ dimensions
outside\ the\ initial\ active\ representation?
}
$$

with:

$$
ZF2,\;ZF3,\;ZF5
$$

and ZF1 preservation.

### Not currently being tested

Not yet:

* action warrant;
* utility;
* action selection;
* causal action rationale;
* systemic utility;
* authorization;
* Epistemic Agency;
* kernel necessity of Zoom-In;
* kernel necessity of ActionRationale.

This is exactly the isolation we want.

---

# One particularly useful research dependency

The future Action Fact-Finding experiment should **consume the output of State Fact-Finding**, not bypass it.

So:

$$
\boxed{
AR_t
=
f(F_t,\mathcal A_Q,K_t,Q_t,C_t,S_t,R_t)
}
$$

rather than:

$$
AR_t=f(K_t,\mathcal A_Q)
$$

alone.

Otherwise Type B could silently reconstruct Type A internally, destroying the very separation we are trying to test.

---

# Current research frontier

The ledger therefore has a very clean frontier:

$$
\boxed{
\textbf{NOW: Dimension Discovery}
}
$$

then:

$$
\boxed{
\textbf{NEXT: Action Fact-Finding}
}
$$

then:

$$
\boxed{
\textbf{LATER: Epistemic Agency / Action Selection}
}
$$

while the entire time:

$$
\boxed{
Theory\ v1.2 = FROZEN
}
$$

and:

$$
\boxed{
Minimal\ Kernel = UNTOUCHED.
}
$$

The most important discipline now is **not to let the increasingly rich architecture become evidence for itself**. `KR-ACTION-FACTFINDING`, Chapter 2, Expected Utility, and Epistemic Agency are excellent hypotheses and design structures—but the current Zoom experiment must be allowed to produce `[EXP]`, `[NEG]`, or `[OPEN]` results independently.

That keeps the research programme scientifically clean.
