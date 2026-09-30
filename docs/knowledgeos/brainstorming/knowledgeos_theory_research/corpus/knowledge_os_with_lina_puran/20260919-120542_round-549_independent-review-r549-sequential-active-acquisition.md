# Independent Review — R549 (Sequential Active Acquisition)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment in this turn; R549 is the object of review. No external research needed; if a gap appears I name the book.

I will do seven things:

1. **Verdict** on R549.
2. **What is actually established**, honestly scoped.
3. **Where R549 remains imprecise or wrong** — five items with corrections.
4. **Full term definitions**, extended to new objects.
5. **Worked examples** proving or refuting the central claims.
6. **ML positioning** and what R550 must do.
7. **Optimized architecture** and short bullet status.

---

# Part I — Verdict

R549 is a **strong and correctly-scoped step**, and it makes three substantive contributions:

$$\boxed{Information > 0 \not\Rightarrow Identifiable}$$
$$\boxed{\text{One-step VoI-greedy} \neq \text{Optimal sequential acquisition}}$$
$$\boxed{AcquisitionSynergy \text{ exists; greedy may be strictly suboptimal}}$$

Each is correct. The second is the strongest: it is a *negative* result about a natural algorithm, and it is proven by a completely explicit finite world. That is a real theorem in the sequence-of-acquisitions problem, and it is not obvious.

The third is a genuine unification: **multi-factor dependency** (R604.8) and **multi-step information acquisition** (R549) turn out to be the same mathematical phenomenon — value from a set is not determined by values of individual elements. This is a good sign for the architecture: two apparently unrelated areas collapse into one.

**Five residual issues**, each requiring correction:

1. The XOR benchmark is correct, but the "optimal cost" claim is stated as a global optimum without proving no plan beats 0.40.
2. The Regret definition uses $\pi^*$ without stating the space over which $\pi^*$ is defined.
3. `AcquisitionPolicy` is typed but its dependence on hidden state, observations, and time is not stated.
4. The five-state distinction (Unknown, Unobservable, Unidentifiable, Unacquired, Unvalidated) is proposed for extension to six (adding Unavailable). This is correct but should be argued for, not just asserted.
5. The proposed architecture (L0–L6 retained) is correct, but R549's argument for it must be made explicit, not merely stated.

---

# Part II — What R549 Actually Establishes

## 2.1 The Information ≠ Identifiability correction

R549 §1 correctly distinguishes:

$$I(O; Z) > 0 \quad \text{(statistical signal)}$$
$$TPP(\pi_O, Z \mid W) \quad \text{(identifiability)}$$

These are genuinely different. A weak signal can carry information while still failing to distinguish alternatives at the required resolution.

**Proof.** Let $O$ be a coin flip that is biased by $Z$:

$$P(O = H \mid Z = 0) = 0.51, \quad P(O = H \mid Z = 1) = 0.49$$

Then $I(O; Z) > 0$ (they differ). But a single flip does not distinguish $Z = 0$ from $Z = 1$ at any meaningful confidence. Identifiability requires distinguishability at the target's resolution, not just nonzero mutual information.

**Real-world.** A medical test that returns slightly different distributions under disease present vs absent carries information but is not diagnostic at the individual level. Identifiability must be assessed at the resolution required by the target.

## 2.2 Greedy is suboptimal — the XOR benchmark

The benchmark is clean:

- $Z = X \oplus Y$, with $X, Y \in \{0, 1\}$ uniform.
- Actions A (observe $X$, cost 0.20), B (observe $Y$, cost 0.20), C (observe $Z$ directly, cost 0.45), D (irrelevant, cost 0.05).
- Initial Bayes error: 0.5.

**One-step values:**
- A alone: 0 information about $Z$ (since $X$ alone is independent of $Z$).
- B alone: 0.
- C alone: 0.5 (resolves $Z$).
- D alone: 0.

**Greedy**: picks C (highest immediate value). Cost 0.45.

**Optimal**: picks A and B. Cost 0.40. Resolves $Z$ exactly because $Z = X \oplus Y$.

**Conclusion.** Greedy is strictly worse. This is the XOR / synergy phenomenon, and it is real.

## 2.3 The unification with multi-factor dependency

This is R549's most important conceptual contribution. The XOR structure appears twice:

- **Multi-factor dependency (R604.8):** $Z = A \land B \land C$ has no material single factor, only a material joint factor.
- **Sequential acquisition (R549):** No single acquisition is informative, only a joint pair.

They are both instances of:

$$\boxed{\text{Value of a set } \neq \text{ sum of values of elements}}$$

This is a genuine structural unification. R549 correctly identifies it, and it justifies treating both as *synergy* phenomena rather than as unrelated anomalies.

## 2.4 The Regret metric

R549 §19 proposes:

$$Regret(\pi) = Cost(\pi) - Cost(\pi^*)$$

For the XOR benchmark:

$$Regret(Greedy) = 0.45 - 0.40 = 0.05$$

**Correct.**

But: the definition of $\pi^*$ is not stated precisely. It must be defined as the optimal policy over the class of admissible policies $\Pi$, and the class must be declared. Otherwise the metric is ill-posed.

---

# Part III — Where R549 Remains Imprecise or Wrong

## Error 1 — Optimality of $A + B$ is not proven

R549 §5 claims:

$$Optimal = 0.40 \quad (\text{plan } A + B)$$

But no argument is given that no other plan beats 0.40.

**Correct argument.** Enumerate all finite sequences over $\{A, B, C, D\}$:

- Single action $C$: cost 0.45, resolves.
- Pair $A, B$: cost 0.40, resolves.
- $D$ plus anything: worse than $A, B$ alone because $D$ is irrelevant.
- Any single action other than $C$: does not resolve.
- $A$ followed by $C$: cost 0.20 + 0.45 = 0.65. Worse.
- $B$ followed by $C$: worse.
- $A$ then $D$: cost 0.25, does not resolve.
- $A$ then $B$ then $C$: 0.85. Worse.
- $A$ then $B$: 0.40, resolves. **Optimal among resolving plans.**
- Any plan with $C$: cost $\geq 0.45$ > 0.40.
- Any plan without $C$ or $A+B$: does not resolve.

Therefore $A+B$ at cost 0.40 is optimal among resolving plans. And since any non-resolving plan is worse (by definition, $Cost(\pi^*) \leq Cost(\pi)$ for resolving plans), $A+B$ is the unique optimal plan.

**Status.** The claim is correct; the proof is missing.

## Error 2 — Regret's $\pi^*$ is under-defined

R549 §19:

$$Regret(\pi) = Cost(\pi) - Cost(\pi^*)$$

where "$\pi^*$ is the optimal policy within the finite benchmark."

But the space of admissible policies is not stated. Is it:

- All finite sequences of admissible actions?
- Only adaptive policies?
- Only deterministic policies?
- Only policies that resolve?
- Policies that resolve with error probability $\leq \epsilon$?

Each choice gives a different $\pi^*$ and thus a different Regret.

**Corrected definition.**

$$Regret_{\Pi}(\pi) = Cost(\pi) - \min_{\pi' \in \Pi} Cost(\pi')$$

with $\Pi$ declared. For R549, $\Pi$ = all finite sequences of admissible actions that reduce Bayes error to 0. Then $\pi^* = A, B$ and Regret(Greedy) = 0.05 as stated.

## Error 3 — `AcquisitionPolicy` is not fully typed

R549 §2:

$$\pi_A : K \to A$$

But an acquisition policy at time $t$ typically depends on:

- the initial state $K_0$,
- the history of actions and observations $(a_1, o_1, \ldots, a_{t-1}, o_{t-1})$,
- the current time budget or step count.

The correct type is:

$$\pi_A : (K_0, (a_1, o_1), \ldots, (a_{t-1}, o_{t-1}), t) \to A \cup \{\text{stop}\}$$

Without this, `AcquisitionPolicy` is a schema, not an object.

## Error 4 — The `Unavailable` addition should be argued

R549 §14 proposes adding `Unavailable` to the Zero classification. This is correct, but the argument is not made.

**Corrected argument.**

`Unobservable` and `Unavailable` are distinct:

- **Unobservable:** the target itself cannot be observed under any admissible action (the world does not permit it).
- **Unavailable:** the target is observable in principle, but no current action can access it (authorization, technical failure, expired source).

They have different remedies:

- **Unobservable:** stop. No acquisition can help.
- **Unavailable:** either remove the access barrier or defer acquisition.

The distinction matters operationally.

**Counterexample.** A hidden dependency exists in a system that has been decommissioned. The dependency itself is unobservable (system no longer exists). But if the system is merely offline, the dependency is available (it can be retrieved when the system is online).

## Error 5 — The architectural argument is understated

R549 §10–§11 rejects the eight-layer proposal and retains six layers. This is correct, but the argument should be explicit:

> Acquisition is a **capability**, not a **layer**, because:
> 1. It uses existing types (State, History, Operation, Contract, Regime, Scope).
> 2. It is invoked by the epistemic loop, not on top of it.
> 3. It does not introduce new primitives, aggregates, or BCs.
> 4. Its placement in L3 (Assessment) and L5 (Intelligence) preserves the layer's existing responsibilities.

Without this argument, the retention of six layers is a preference, not a decision.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Type:** $r : ID \times ID \to \text{RelationType}$.

### Semantics (Sem)
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.

## State and operation terms

### State (K)
- **Type:** $K = (X, H)$.

### Authoritative State (X)
- **Real-world:** Accepted patient record.

### History (H)
- **Type:** Append-only event sequence.

### Operation
- **Type:** $(ID, InputType, OutputType, Class, MutationPolicy, Pre, Post, Transform, Scope, Regime, PreservationTargets, LossProfile)$.

### Transformation
- **Type:** $f : X \to Y$.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules)$.

## Dependency and acquisition terms

### Information (I)
- **Definition:** Observable distinction that can reduce uncertainty about a target.
- **Type:** $I : \Omega \to \mathcal{O}$.
- **Real-world:** Pipeline identifier.

### Identifiability (recap)
- **Definition:** Different values of $Z$ produce distinguishable $O$.
- **Type:** $P(O \mid Z = z) \neq P(O \mid Z = z')$ for some $z \neq z'$ at the required resolution.
- **Real-world:** A diagnostic test that separates cases at the target's resolution.

### Information Gain (IG)
- **Type:** $IG(a) = H(Z) - H(Z \mid O_a)$.

### Determination Gain (DG)
- **Type:** $DG(a) \in \{0, 1\}$ or finer scale.

### Decision Value (DV)
- **Type:** $DV(a \mid U, \delta) = E[U \mid \text{with } a] - E[U \mid \text{without } a]$.

### Value of Information (VoI)
- **Type:** $VoI(a \mid U, \delta, C) = DV(a) - Cost(a)$.
- **Real-world:** Whether to order a test.
- **Invalid:** Universal scalar.

### Acquisition Action (extended)
- **Type:** $(Target, Source, Observation, Cost, Authority, Time, Contract, ActionProvenance, AuthorizationRecord)$.
- **Real-world:** Fetch pipeline metadata via authorized API.

### Acquisition Policy (corrected type)
- **Definition:** A rule that chooses the next action given state, history, and time.
- **Type:** $\pi_A : (K_0, (a_1, o_1), \ldots, (a_{t-1}, o_{t-1}), t) \to A \cup \{\text{stop}\}$.
- **Real-world:** "If policy is unclear, fetch manifest. If manifest still unclear, escalate to expert."
- **Invalid:** A static function $K \to A$ without history.

### Sequential Acquisition
- **Definition:** The result of one acquisition determines the next.
- **Type:** Adaptive planning; $A_{t+1}$ depends on $O_t$.
- **Real-world:** Medical diagnosis with staged tests.

### Complementary Information
- **Definition:** Two observations neither of which suffices alone but whose combination suffices.
- **Type:** $I(X; Z) = 0 \land I(Y; Z) = 0 \land I(X, Y; Z) > 0$.
- **Real-world:** XOR of two hidden variables.
- **Invalid:** Two independent weak signals (each helpful alone).

### Acquisition Synergy
- **Definition:** The value of a combination exceeds the sum of individual values.
- **Type:** $Value(A, B) > Value(A) + Value(B)$ under a declared value function.
- **Real-world:** The XOR case.
- **Invariant:** `LocalVoI ≠ GlobalVoI`.

### Acquisition Plan
- **Type:** $P = (A_1, A_2, \ldots, A_n)$, possibly adaptive.

### Acquisition Tree
- **Type:** A finite decision tree over acquisitions, conditional on observations.

### Acquisition Regret (corrected)
- **Definition:** Cost difference from optimal policy in a declared policy class.
- **Type:** $Regret_\Pi(\pi) = Cost(\pi) - \min_{\pi' \in \Pi} Cost(\pi')$.
- **Real-world:** The XOR case: 0.05.
- **Invalid:** Regret without a declared $\Pi$.

### Stopping Rule
- **Definition:** Condition under which acquisition stops.
- **Type:** Decision function over $K$, admissible actions, VoI, and governance constraints.
- **Real-world:** "Stop when no admissible action has positive VoI."

### Zero classification (extended to six)
- **Values:** `Unknown`, `Unobservable`, `Unidentifiable`, `Unacquired`, `Unavailable`, `Unvalidated`.
- **Real-world:**
  - Unknown: investigate.
  - Unobservable: stop (system is not accessible in any admissible world).
  - Unidentifiable: stop (no observation can distinguish).
  - Unacquired: acquire.
  - Unavailable: defer (access barrier).
  - Unvalidated: validate.

### Unavailable (new)
- **Definition:** Observation is theoretically possible but no current action can access it.
- **Type:** A classification in Zero.
- **Real-world:** A decommissioned system.
- **Invalid:** "I don't know" without diagnosing the barrier.

---

# Part V — Worked Examples

## Example 1 — XOR benchmark, hand-verified

**Setup.** $X, Y \in \{0, 1\}$ uniform, $Z = X \oplus Y$. Bayes error: 0.5.

**Enumerate plans:**

| Plan | Cost | Resolves? |
|---|---|---|
| A | 0.20 | No |
| B | 0.20 | No |
| C | 0.45 | Yes |
| D | 0.05 | No |
| A, B | 0.40 | Yes |
| A, C | 0.65 | Yes |
| A, D | 0.25 | No |
| B, C | 0.65 | Yes |
| A, B, C | 0.85 | Yes |
| any with 4+ actions | ≥ 0.45 | Yes (but worse) |

**Optimal resolving plan:** $A, B$ at 0.40.

**Greedy choice:** $C$ at 0.45 (highest immediate value).

**Regret:** 0.05.

**Conclusion.** Greedy is strictly suboptimal. Proven by finite enumeration.

## Example 2 — Complementary information

**Setup.** Two hidden bits $X$ and $Y$; target $Z = X \oplus Y$.

- Observation of $X$ alone: $I(X; Z) = 0$ (since $Z$ is $X$ XOR an independent uniform bit).
- Observation of $Y$ alone: $I(Y; Z) = 0$.
- Observation of both: $I(X, Y; Z) = 1$ bit.

**Consequence.** Two individually uninformative observations are jointly fully informative.

**Real-world.** Two independent survey questions, neither of which correlates with the outcome alone, but the XOR of which predicts it.

## Example 3 — Multi-factor dependency and acquisition synergy are the same phenomenon

**Multi-factor (R604.8).** $Z = A \land B \land C$; no single factor is material; all three jointly are.

**Sequential acquisition (R549).** No single action resolves; pair resolves.

**Common structure.**

$$Value(\{e_1, \ldots, e_k\}) \neq \sum Value(\{e_i\})$$

**Consequence.** Both require reasoning about **sets** of factors/actions, not individual elements. Same mathematical phenomenon.

## Example 4 — Unobservable vs Unavailable

**Setup A.** A system has been decommissioned for 5 years. A hidden dependency's log is gone forever.

**Classification:** Unobservable. **Remedy:** Stop.

**Setup B.** A system is offline for maintenance. The log exists but cannot be fetched now.

**Classification:** Unavailable. **Remedy:** Defer acquisition until system is online.

**Setup C.** The system is online but access requires higher authorization.

**Classification:** Unavailable. **Remedy:** Escalate authorization.

**Setup D.** The log is accessible but no one has looked at it.

**Classification:** Unacquired. **Remedy:** Acquire.

**Consequence.** Four different classifications with four different remedies.

---

# Part VI — ML Positioning and R550 Plan

## What ML may do

- **Candidate generation:** propose dependencies, groups, factor sets.
- **Acquisition ranking:** estimate which action is likely to reduce uncertainty.
- **Policy learning:** learn a policy $\pi_\theta(K) \to A$.
- **OOD detection.**
- **Calibration.**

## What ML may not do

- Assert identifiability.
- Assert dependency.
- Write to X.
- Bypass L4.
- Be evaluated by information gain without accounting for identifiability (R549 §1).

## Firewall

```
ML candidate
  → IdentifiabilityCheck
  → TypeCheck
  → ContractCheck
  → RegimeCheck
  → ScopeCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

## Concrete technique

**Features per pair or group:**
- source overlap
- lineage overlap
- model lineage
- transformation lineage
- assumption overlap
- citation overlap
- temporal proximity
- graph distance
- semantic similarity
- acquisition history

**Models:**
- Pairwise: gradient-boosted trees.
- Group: set-based classifiers (Deep Sets, GNNs).
- Policy: RL over the acquisition tree (P3).

**Policy classes:**
- P0 Random
- P1 Greedy (1-step VoI)
- P2 Sequential planner (finite-horizon DP)
- P3 ML policy

**Evaluation metrics:**
- Resolution Rate
- Determination Accuracy
- Acquisition Cost
- Waste Rate
- Abstention Rate
- Regret (w.r.t. optimal in the declared class)
- Robustness (performance under OOD)
- Calibration

**Cardinal rules:**

$$\boxed{MLAccuracy \neq Dependency}$$
$$\boxed{MLCapability \leq InformationAvailable}$$
$$\boxed{MLPolicy \text{ must be compared to P0, P1, P2, not assumed superior}}$$

## R550 — Acquisition Planning Under Uncertainty

R550 must extend R549 from deterministic to noisy observations.

**Q1 — Formalize noisy observations.**

$$P(O_i \mid W) \text{ non-deterministic}$$

**Q2 — Compare policy classes.**

- Random.
- Greedy Information Gain.
- Greedy Decision Value.
- Exact finite-horizon DP.
- Approximate planning.
- ML policy.

**Q3 — Characterize when greedy is optimal.** 
Test whether greedy is optimal iff the value function is submodular. This connects to classical results on submodular maximization.

**Submodularity condition:**

$$Value(A \cup B) + Value(A \cap B) \leq Value(A) + Value(B)$$

If the value function is submodular, greedy is $\frac{1}{e}$-optimal. If it is supermodular (as in XOR), greedy may fail badly.

**Q4 — Adversarial information.** 
Cases where:
- An acquisition changes the scope.
- An acquisition changes the regime.
- An acquisition introduces a new competing hypothesis.
- An acquisition is authorized but the source is compromised.

**Q5 — Report discipline.**
Every test emits `certificate.json` with `ExecutionRunID`.

**Q6 — Reproducibility.**
Generator parameters, seeds, hyperparameters.

## What R550 must not do

- Do not introduce a new Kernel primitive.
- Do not introduce a new BC.
- Do not conflate local and global VoI.
- Do not introduce RL until P0–P2 are characterized.
- Do not claim universal theorems from finite tests.
- Do not conflate the XOR case with submodularity; they are opposite.

---

# Part VII — Optimized Architecture

## Six layers (preserved)

```
                KNOWLEDGEOS — Six-Layer Architecture
                              │
                     L0 Kernel (ID, R*, Sem)
                              │
                     L1 Semantic Fabric
             Context / Contract / Scope / Regime
             IdentifiabilityContract / AcquisitionContract
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        Composition / PreservationTarget / Bridge
        Provenance / Lineage / CausalHistory
        DependencyFactor / FactorSet / Hyperedge
        Intervention / AcquisitionAction / AcquisitionPlan
                              │
                     L3 Epistemic Assessment
     Dependency | Materiality | Minimality
     Identifiability | InformationGain | DeterminationGain | DecisionValue
     Zero (Unknown | Unobservable | Unidentifiable |
           Unacquired | Unavailable | Unvalidated)
     AcquisitionAssessment
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     IdentifiabilityTesting / FirewallVerification
     CandidateQuarantine / Metamorphic / Bootstrap
                              │
                     L5 Intelligence
       Pairwise ML / Group ML / Embeddings
       AcquisitionPolicy P0–P3
       CandidateGenerator / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       AcquisitionPermission / ResourceAuthorization
```

No L4.5. No L7. No L8.

## The laws (updated)

$$\boxed{ML \not\to X}$$
$$\boxed{ML \not\to Assessment \text{ without L4}}$$
$$\boxed{MLCapability \leq InformationAvailable}$$
$$\boxed{Pure \Rightarrow X' = X}$$
$$\boxed{Spec \neq Run \neq Result \neq Cert}$$
$$\boxed{\text{Use the weakest sufficient method}}$$
$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$
$$\boxed{Information > 0 \not\Rightarrow Identifiable}$$
$$\boxed{Greedy \neq Optimal \text{ under synergy}}$$
$$\boxed{LocalVoI \neq GlobalVoI}$$
$$\boxed{AcquisitionSynergy \Leftrightarrow MultifactorSynergy}$$
$$\boxed{Unknown \neq Unobservable \neq Unidentifiable \neq Unacquired \neq Unavailable \neq Unvalidated}$$
$$\boxed{Acquisition \neq ML}$$
$$\boxed{Acquisition \neq Resolution}$$

## Methodological invariant

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

R549 is the seventh consecutive round with self-correction.

---

# Part VIII — Short Bullet Status

## Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency:** multi-factor, target/scope/regime-relative, materiality, minimality, interaction — executable.
- **No-information impossibility theorem:** established.
- **Information ≠ Identifiability:** established.
- **Greedy ≠ Optimal acquisition:** established by XOR counterexample.
- **Acquisition synergy:** unified with multi-factor dependency.
- **Zero:** refined to six states.
- **ML firewall:** preserved and extended.
- **Self-falsification:** seven consecutive rounds.

## Not yet done

- **R550:** noisy sequential acquisition, policy class comparison.
- **Report discipline:** `ExecutionRunID`, `certificate.json`.
- **Reproducibility:** generator parameters, seeds, hyperparameters.
- **Submodularity characterization:** when greedy is optimal.
- **Acquisition composition laws.**
- **Real-world benchmark.**
- **Full invariant catalogue.**
- **Terminology freeze.**
- **Theory Specification v1.0.**

## Open (not "not proven")

- **Optimal acquisition planning:** NP-hard in general; greedy may be adequate under submodularity.
- **Category theory application:** pending specific experiment.
- **Matroid universality:** falsified; other structures may apply.
- **Lattice for dependency types:** proposed ordering falsified; theory not falsified.

## Standing rules

- No new Kernel primitive.
- No new BC.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- ML cannot write to X.
- ML cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.
- `Greedy ≠ Optimal` under synergy.

## The single most important next thing

$$\boxed{\text{Build R550. Extend to noisy observations. Compare P0–P3. Characterize when greedy is optimal.}}$$

Because R549 has shown that the acquisition problem has real mathematics, and the natural next question — **when does greedy approximate optimal?** — has a classical answer (submodularity) that must be tested against the XOR case.

## Two open questions I flag

1. **Is the value function submodular in most realistic acquisition problems?** If yes, greedy is approximately optimal. If no, planning is required. R550 must test both regimes.

2. **Does the policy class P3 (ML) offer a real advantage over P2 (DP)?** R550 must not assume it does. The DP solution is exact for small worlds; ML is only justified when the world is too large for DP.

If you want to proceed, tell me:

- **(A)** R550 specification (noisy observations, P0–P3, submodularity test, adversarial acquisition, report format).
- **(B)** R550 code.
- **(C)** Both, in order.

My recommendation is **(C)**, with **report discipline fixed first** — because R550 will produce numbers that determine whether the acquisition theory holds, and without `ExecutionRunID` and `certificate.json` per test, those numbers are described, not auditable.