Your refinement brings exceptional mathematical and operational clarity to the downstream research boundary. By resolving the notation collision (AR_t vs. R_t), establishing the non-equivalence chain (\text{State Determination} \neq \text{Action Warrant} \neq \text{Decision} \neq \text{Authorization}), and embedding non-additive utility and explicit causal provenance, you have constructed a fully rigorous foundation for Type B Fact-Finding.
Below is the consolidated, updated specification for KR-ACTION-FACTFINDING-2026-09, incorporating all 11 corrections while maintaining strict isolation from active experiments.
  ========================================================================================
  RESEARCH ARTIFACT SPECIFICATION
  ID: KR-ACTION-FACTFINDING-2026-09
  Title: Action Fact-Finding, ActionRationale (AR_t), and Action Warrant Protocol
  ========================================================================================
  Governance Standing: [PROP][OPEN]
  Theory v1.2: FROZEN
  Minimal Kernel: UNTOUCHED
  Active Empirical Target: KR-ZOOM-FACTFINDING-01-2026-09 (UNTOUCHED / PREREQUISITE)
  ========================================================================================

1. Three-Layer Epistemic & Operational Architecture
  ====================================================================================================
  THREE-LAYER ARCHITECTURE
  ====================================================================================================

  [ LAYER 1: PROBLEM DISCOVERY ]
  Observation (O_t) ──► Inquiry (Q_t) ──► Inquiry Diagnosis & Reframing
  Question: "What is the actual question / inquiry space?"

                                       │
                                       ▼

  [ LAYER 2: STATE FACT-FINDING (Type A) ]
  ZoomIn(K_t, O_t, Q_t) ──► Candidate Dimensions (\mathcal{D}^{cand}_{t+1})
                        ──► Assessment over Hypothesis Space (\mathcal{H}_Q)
                        ──► State Determination (F_t)
  Question: "What is actually the case?"
  F_t \in {Determined, Underdetermined, Contradictory, Insufficient}

                                       │
                                       ▼

  [ LAYER 3: ACTION FACT-FINDING (Type B) ]
  F_t ──► Challenge ──► Candidate Action Space (\mathcal{A}_Q)
      ──► Construct ActionRationale (AR_t)
      ──► Action Evaluation ──► Decision_t ──► Authorization_t ──► Execution (Action_t)
  Question: "Given what is known or uncertain, why act, and which action is warranted?"

                                       │
                                       ▼

  [ FEEDBACK LOOP ]
  Action_t ──► Environment Transition ──► O_{t+1} ──► Evidence_{t+1} ──► K_{t+1}
  ====================================================================================================

2. Rigorous Semantic Separation & Non-Equivalence Invariants
To prevent premature automated execution or conceptual leakage, the architecture strictly enforces a five-stage non-equivalence chain:
  ========================================================================================
  NON-EQUIVALENCE INVARIANTS
  ========================================================================================
  Relation                            Semantic Meaning & Operational Guardrail
  ----------------------------------------------------------------------------------------
  Determine(H) != Select(a)           Knowing the root cause does not dictate a unique action.
  ~Determine(H) != NoAction           An operational system may be forced to act (e.g. Mitigate)
                                      under severe epistemic uncertainty.
  State Determination != Action Warrant Knowing what is true (F_t) is an epistemically distinct
  (F_t != W_t)                        problem from establishing an action warrant (W_t).
  ActionRationale != Decision_t       AR_t is a candidate justification under evaluation; it does
                                      not imply the action has been selected.
  Decision_t != Authorization_t       Selection by epistemic preference != Policy authorization.
  ========================================================================================

3. Updated Structural Object: ActionRationale (AR_t)
Notation Correction: Renamed from R_t to AR_t to preserve R_t \equiv \text{ReasoningRegime}.
{
  "$schema": "https://knowledgeos.org/schemas/v1/action-rationale.json",
  "title": "ActionRationale",
  "type": "object",
  "properties": {
    "rationale_id": { "type": "string" },
    "inquiry_ref": { "type": "string", "description": "Originating Inquiry (Q_t)" },
    "determination_ref": { 
      "type": "object",
      "properties": {
        "determination_id": { "type": "string" },
        "standing": { "type": "string", "enum": ["Determined", "Underdetermined", "Contradictory", "Insufficient"] }
      },
      "required": ["determination_id", "standing"]
    },
    "candidate_action_space": {
      "type": "array",
      "items": { "type": "string" },
      "description": "Must include explicit operational choices: {a_1, ..., NoOp, Wait, InvestigateFurther, Mitigate}"
    },
    "proposed_action": { "type": "string" },
    "causal_model": {
      "type": "object",
      "properties": {
        "expected_mechanism": { "type": "string" },
        "assumptions": { "type": "array", "items": { "type": "string" } },
        "evidence_references": { "type": "array", "items": { "type": "string" } },
        "alternative_hypotheses": { "type": "array", "items": { "type": "string" } },
        "counterfactual_status": { "type": "string" }
      },
      "required": ["expected_mechanism", "assumptions", "evidence_references"]
    },
    "utility_evaluation": {
      "type": "object",
      "properties": {
        "u_local": { "type": "number", "description": "Local impact on immediate target" },
        "u_systemic": { "type": "number", "description": "Impact on wider system topology" },
        "composition_function": { 
          "type": "string", 
          "default": "F_U(U_local, U_systemic, Risk, Cost, Context)",
          "description": "Non-additive utility integration function"
        },
        "calculated_eu": { "type": "number" }
      },
      "required": ["u_local", "u_systemic", "composition_function", "calculated_eu"]
    },
    "uncertainty_profile": {
      "type": "object",
      "properties": {
        "unobserved_variables": { "type": "array", "items": { "type": "string" } },
        "residual_risk": { "type": "string" }
      }
    },
    "warrant_evaluation": {
      "type": "string",
      "enum": ["Warranted", "NotWarranted", "Underdetermined", "Blocked"],
      "default": "Underdetermined"
    }
  },
  "required": [
    "rationale_id",
    "inquiry_ref",
    "determination_ref",
    "candidate_action_space",
    "proposed_action",
    "causal_model",
    "utility_evaluation",
    "warrant_evaluation"
  ]
}

4. Registered Governance & Pipeline Sequence
  ========================================================================================
  RESEARCH GOVERNANCE MATRIX
  ========================================================================================
  Component / Primitive               Status        Governance Category
  ----------------------------------------------------------------------------------------
  Chapter 3 Structural Extraction     [EXT][PROP]   External Analogy
  State vs. Action Fact-Finding       [PROP][OPEN]  Type A vs. Type B Separation
  State Determination != Action Warrant [PROP][OPEN] Semantic Separation Invariant
  Notation AR_t := ActionRationale   [PROP]        Resolved Notation Collision
  Causal Model Provenance             [PROP][OPEN]  Replaces Unsubstantiated Causal Mechanism
  Non-Additive Utility Composition    [PROP][OPEN]  U(a) = F_U(U_local, U_systemic, Risk, ...)
  Explicit Inaction Space (\mathcal{A}_Q) [PROP]    Includes NoOp, Wait, Investigate, Mitigate
  Theory v1.2                         [FROZEN]      System Core Reference
  Minimal Kernel                      [UNTOUCHED]   Kernel Standard
  ========================================================================================
#
This consolidated specification is substantially stronger. I would **accept the research architecture**, but I would **not freeze the artifact quite yet**. There are a few remaining semantic/type issues worth correcting now, because they could otherwise contaminate the later experiment.

## 1. The architecture itself is sound

The strongest result is the separation:

$$
\boxed{
Problem\ Discovery
\rightarrow
State\ Fact\!-\!Finding
\rightarrow
Action\ Fact\!-\!Finding
\rightarrow
Authorization
\rightarrow
Action
}
$$

with the feedback:

$$
Action_t\rightarrow O_{t+1}\rightarrow Evidence_{t+1}\rightarrow K_{t+1}.
$$

This is consistent with the distinctions established earlier.

In particular:

$$
\boxed{
F_t\neq W_t\neq Decision_t\neq Authorization_t\neq Action_t
}
$$

is an excellent research boundary.

---

# 2. Correct the `~Determine(H)` notation

You currently have:

> `~Determine(H) != NoAction`

Use:

$$
\boxed{
\neg Determine(H)\not\Rightarrow NoAction
}
$$

The current `~Determine(H) != NoAction` could be interpreted as saying that *non-determination is unequal to NoAction*, rather than expressing the intended implication.

The actual principle is:

$$
\boxed{
\neg Determine(H)\not\Rightarrow NoAction
}
$$

because action may be required under uncertainty.

And the converse also matters:

$$
\boxed{
NoAction\not\Rightarrow \neg Determine(H)
}
$$

A system can determine the situation and rationally choose NoOp.

---

# 3. Do not require `Mitigate` in every action space

The schema currently says:

> Must include `{a_1,...,NoOp,Wait,InvestigateFurther,Mitigate}`.

That is too strong.

`Mitigate` is domain-dependent.

The universal requirement should be:

$$
\boxed{
NoOp,\ Wait,\ InvestigateFurther
}
$$

as explicit candidates where semantically applicable, while:

$$
Mitigate\in\mathcal A_Q
$$

only when mitigation is a legitimate action in the domain.

More generally:

$$
\mathcal A_Q=
\mathcal A_Q^{domain}
\cup
\mathcal A_Q^{epistemic}
$$

where the epistemic candidates can include `InvestigateFurther`, `Wait`, or `NoOp`.

This avoids turning a research abstraction into an implementation requirement.

---

# 4. `ActionRationale` should not require a `proposed_action`

This is subtle.

You correctly define:

$$
ActionRationale\neq Decision.
$$

But if `proposed_action` is mandatory, the object is already biased toward a selected/proposed intervention.

I would allow:

```text
candidate_action_space
evaluated_actions
preferred_action: optional
```

or simply:

$$
AR_t=
Rationale(
F_t,\mathcal A_Q,\mathcal M,\mathcal U,\mathcal R,\ldots
)
$$

and let:

$$
Evaluate(AR_t)\rightarrow W_t
$$

then:

$$
Select(\mathcal A_Q,AR_t)\rightarrow Decision_t.
$$

This preserves the distinction:

$$
\boxed{
Rationale\ construction
\neq
Action\ selection
}
$$

---

# 5. The `calculated_eu` field needs the biggest correction

You have correctly rejected automatic additive utility:

$$
U=U_{local}+U_{systemic}.
$$

But the schema now has:

```json
"calculated_eu": { "type": "number" }
```

That silently assumes that a scalar expected utility has already been constructed.

Your earlier Expected Utility work deliberately left the composition function open.

Therefore:

$$
\boxed{
EU(a)
=
\mathbb E[
F_U(
U_{local},
U_{systemic},
Risk,
Cost,
Context,\ldots
)
]
}
$$

is a **candidate evaluation regime**, not a universal property of `ActionRationale`.

I recommend making `calculated_eu` optional and explicitly recording:

```text
utility_regime
utility_components
outcome_model
probability_model
composition_function
evaluation_result
```

Then an action can be evaluated under:

* expected utility;
* lexicographic safety;
* constraint satisfaction;
* minimax;
* threshold policy;
* another declared decision regime.

This also preserves the existing distinction:

$$
\boxed{
Reasoning\ Regime\neq Epistemic\ Standard\neq Governance\ Policy
}
$$

---

# 6. “Action Warrant” needs an explicit definition

This is now the most important conceptual open point.

You have:

$$
F_t\rightarrow AR_t\rightarrow W_t.
$$

But what exactly makes an action **warranted**?

It cannot simply be:

$$
W_t = EU(a)>0.
$$

That would make warrant entirely dependent on one utility model.

A better candidate is:

$$
\boxed{
Warrant(a\mid K,Q,C,S,R)
}
$$

as a contract-relative evaluation satisfying the declared requirements for:

* epistemic standing;
* causal assumptions;
* expected consequences;
* risk constraints;
* action alternatives;
* resource feasibility;
* policy requirements.

Then:

$$
W_t\in
\{
Warranted,
NotWarranted,
Underdetermined,
Blocked
\}.
$$

This makes the existing `warrant_evaluation` field meaningful without prematurely defining its mathematics.

---

# 7. Causal provenance is correctly placed

This part is excellent:

```text
expected_mechanism
assumptions
evidence_references
alternative_hypotheses
counterfactual_status
```

I would add one distinction:

$$
\boxed{
CausalModel
\neq
CausalDetermination
}
$$

The ActionRationale can contain an explicitly uncertain causal model.

For example:

```text
Causal status:
    supported-under-assumptions
```

is epistemically different from:

```text
Causal status:
    determined
```

This is especially important given the earlier Freedman/Pearl research.

---

# 8. Local and systemic utility should not necessarily be the only consequence levels

I like:

$$
U_{local}
$$

and:

$$
U_{systemic}.
$$

But don't freeze these as exhaustive.

A more general structure is:

$$
U_{impact}:
\mathcal A_Q
\rightarrow
\mathcal Y
$$

where the evaluation may have dimensions such as:

$$
\{
local,\ systemic,\ temporal,\ safety,\ resource,\ epistemic
\}.
$$

Then `u_local` and `u_systemic` are one concrete realization.

This keeps the architecture open without adding another kernel primitive.

---

# 9. Add an explicit “evidence for the action” distinction

There are now two different evidential questions:

### State evidence

$$
E^{state}
\rightarrow
F_t
$$

“What is happening?”

### Action evidence

$$
E^{action}
\rightarrow
W_t
$$

“Why is this intervention appropriate?”

They can overlap, but they are not necessarily identical.

For example:

```text
State finding:
    GitLab Runner is responsible for 70 GB/day.

Action question:
    Should we stop the Runner?
```

Additional evidence may be required:

* Is the Runner currently needed?
* What jobs depend on it?
* What happens if it is stopped?
* Is there a safer throttling action?
* Is the traffic legitimate?
* What is the operational cost?

Therefore:

$$
\boxed{
E^{state}\neq E^{action}
}
$$

should be a **candidate research distinction**, not yet a theory invariant.

---

# 10. The complete architecture becomes particularly clean

I would represent the downstream structure as:

$$
\boxed{
O_t
\rightarrow
Q_t
\rightarrow
InquiryDiagnosis
\rightarrow
ZoomIn
\rightarrow
\mathcal D^{cand}
\rightarrow
FactFind
\rightarrow
F_t
}
$$

then:

$$
\boxed{
F_t
\rightarrow
Challenge
\rightarrow
\mathcal A_Q
\rightarrow
AR_t
\rightarrow
W_t
\rightarrow
Decision_t
\rightarrow
Authorization_t
\rightarrow
Action_t
}
$$

with:

$$
\boxed{
Action_t
\rightarrow
O_{t+1}
\rightarrow
E_{t+1}
\rightarrow
K_{t+1}.
}
$$

That is a very strong separation.

---

# 11. Chapter 2 and Chapter 3 now have distinct architectural roles

I would preserve this explicitly in the research record.

### Chapter 2 structural lens

$$
\boxed{
Problem\ discovery / inquiry\ reframing
}
$$

It asks:

> **What is the actual question?**

### Chapter 3 structural lens

$$
\boxed{
Action\ rationale / action\ fact-finding
}
$$

It asks:

> **Given the situation and uncertainty, why act?**

Then KnowledgeOS adds the missing engineering/governance distinctions:

$$
\boxed{
Why\ act
\neq
Which\ action
\neq
Authorization
\neq
Execution
}
$$

That is where the external philosophical source becomes a useful **research inspiration** rather than being mistaken for an architectural specification.

---

# 12. Final status

With the corrections above, I would freeze:

### `KR-ACTION-FACTFINDING-2026-09`

as:

**`[PROP][OPEN] — FROZEN RESEARCH PROPOSAL`**

with:

| Element                        | Status         |
| ------------------------------ | -------------- |
| Problem Discovery              | `[PROP][OPEN]` |
| State Fact-Finding             | `[PROP][OPEN]` |
| Action Fact-Finding            | `[PROP][OPEN]` |
| \(F_t\neq W_t\)                | `[PROP][OPEN]` |
| \(W_t\neq Decision_t\)         | `[PROP][OPEN]` |
| Decision ≠ Authorization       | `[PROP]`       |
| ActionRationale \(AR_t\)       | `[PROP][OPEN]` |
| Causal provenance              | `[PROP][OPEN]` |
| Non-additive utility           | `[PROP][OPEN]` |
| Explicit NoOp/Wait/Investigate | `[PROP]`       |
| Theory v1.2                    | **FROZEN**     |
| Minimal Kernel                 | **UNTOUCHED**  |
| KR-ZOOM-FACTFINDING-01         | **UNTOUCHED**  |

And one especially important governance rule:

$$
\boxed{
\text{Chapter 3 motivates the research question; it does not validate the ActionRationale architecture.}
}
$$

The next empirical question should therefore be **whether ActionRationale actually adds a necessary, observable capability beyond**:

$$
GenerateActions
+
Feasibility
+
Evaluate
+
Select
+
PolicyCheck.
$$

That is the correct kernel-minimality question. If `ActionRationale` is semantically reducible to those existing capabilities, it is a valuable **audit representation**, but not a new kernel capability. If it exposes an irreducible capability, then the kernel research can legitimately reopen that question.

**No Theory v1.3 and no kernel expansion at this stage.**

