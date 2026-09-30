# Review of R604.8 — Multi-Factor Dependency and Intervention Algebra

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment. I proceed from R604.8's baseline. No external research needed; if a genuine gap appears I will name the exact book and pause.

I will do seven things:

1. **Verdict** on R604.8.
2. **What 10/10 establishes**, honestly scoped.
3. **Where R604.8 remains imprecise** — five items with corrections.
4. **Full term definitions**, extended to the new objects.
5. **Worked examples**, including the interaction, alternative-minimal-set, and sequential-vs-joint cases.
6. **ML positioning** for factor-set candidates.
7. **Optimized architecture** and the concrete next slice (R604.9).

No new Kernel primitive. No new BC. No ML in the authority path.

---

# Part I — Verdict

R604.8 is a **significant advance**, and it makes three substantive contributions:

$$\boxed{MinimalFactors(Z) = \min_{\subseteq}\{F : Material(F, Z)\}}$$
$$\boxed{NoSingleFactorEffect \neq NoDependency}$$
$$\boxed{InteractionDependency = JointMateriality + Minimality}$$

Each is correct. The first is the strongest: it turns "multi-factor dependency" from a qualitative worry into a decidable minimization problem on a finite lattice.

The third is also important: it defines "interaction dependency" as a *joint materiality that has no smaller material subset*, which is a precise mathematical statement, not a slogan.

**The self-falsification pattern continues.** R604.8 corrected R604.7's implicit assumption that a graph with single-node edges can represent multi-factor dependency. That is the **fourth consecutive** round with a self-correction. The methodology is now stable.

**Five residual issues**, each requiring correction before R604.9:

1. The definition of `Material(F, Z, w)` is **baseline-relative**, which is correct, but the document does not state how baselines are declared or quantified over.
2. `MinimalFactors(Z)` returns a *family of sets*, not a single set, but R604.8 mostly reasons as if it returns one.
3. The intervention model is described but not typed. What is the type of `do(F = f')`? Over what domain does $f'$ range?
4. `SequentialIntervention` vs `JointIntervention` is stated as distinct but not formalized. When exactly are they equivalent?
5. Report discipline still missing: no `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexamples.

---

# Part II — What 10/10 Establishes

| Test | Establishes | Does not establish |
|---|---|---|
| Empty intervention | `Material(∅, Z) = False` (no-op does nothing). | All no-ops are non-material. |
| Single-factor local detection | `Material({S}, Z)` computed correctly. | All single factors are computed correctly. |
| Joint materiality | `Material({S, A, T}, Z) = True` for the W7 world. | All joint materiality is detected. |
| Minimality | `{S, A, T}` is minimal (no proper subset is material). | Minimality detection is complete. |
| Target-specific minimality | Minimal factors vary with target. | All targets are handled. |
| Target/scope/regime retention | Minimal factor sets respect Z, S, Γ. | All such settings are handled. |
| Single-factor detector incompleteness | A single-factor detector fails on W7. | All single-factor detectors fail on W7. |
| Proper-subset minimality | Some non-minimal supersets are correctly identified. | All non-minimal supersets are identified. |
| Sequential vs joint | These produce different results on a specific finite case. | They always differ. |
| Contextual dependency assessment | Dependency depends on the baseline in a specific case. | All dependencies are baseline-relative (though likely true). |

Honest summary:

$$\boxed{\text{Multi-factor dependency is executable and falsifiable for the tested finite worlds.}}$$

And:

$$\boxed{\text{R604.8 found that a simple graph is insufficient to represent W7.}}$$

That last point is the real result: the graph representation is **provably incomplete** for multi-factor cases.

---

# Part III — Where R604.8 Remains Imprecise

## 3.1 Materiality is baseline-relative, but baselines are not typed

R604.8 §5 defines:

$$Material(F, Z, w) \iff Z(w) \neq Z(do(F = w')w)$$

This is correct and important. But R604.8 does not define:

- What is a baseline $w$?
- How many baselines exist in a given scope?
- Is `Material` universally quantified over baselines, existentially quantified, or evaluated at a specific baseline?

The document uses a specific baseline ("$S=0, A=0, T=0$") in examples, but the general definition is silent.

**Corrected statement.** Three distinct notions:

$$\text{BaselineSpecificMaterial}(F, Z, w) \iff Z(w) \neq Z(do(F)w)$$
$$\text{ExistentiallyMaterial}(F, Z) \iff \exists w \in W_{\text{adm}}(S, \Gamma) : \text{BaselineSpecificMaterial}(F, Z, w)$$
$$\text{UniversallyMaterial}(F, Z) \iff \forall w \in W_{\text{adm}}(S, \Gamma) : \text{BaselineSpecificMaterial}(F, Z, w)$$

The W7 example is *existentially material* at one baseline, not universally. This distinction matters for what "the dependency exists" means.

**Real-world.** A trading strategy: some factors matter only when the market is in a specific regime. `ExistentiallyMaterial` is the right notion for "the strategy has a vulnerability"; `UniversallyMaterial` is the right notion for "the vulnerability exists in every state."

## 3.2 `MinimalFactors(Z)` returns a family, but the document reasons as if it returns one

R604.8 §14 correctly notes that `MinimalFactors(Z)` may be:

$$\{\{A, B\},\ \{C, D\}\}$$

for $Z = (A \land B) \lor (C \land D)$. But most of §5–§13 reason as if the result is a single set.

This is a subtle type error: `MinimalFactors(Z)` has type **set of sets**, not **set**.

**Consequence.** A dependency assessment must report a *family* of explanations, not a single factor set:

$$\text{DependencyAssessment} = (E_1, E_2, Z, S, \Gamma, \mathcal{F}, \text{Status})$$

where $\mathcal{F}$ is the family of minimal factor sets.

**Real-world.** A diagnosis system that reports "the vulnerability comes from X" is wrong if there are multiple independent routes to the same vulnerability. It should report all minimal routes.

## 3.3 The intervention model is not typed

R604.8 §3 uses:

$$do(F = f')$$

But $F$ is a set of factors, and $f'$ is a value assignment. The document does not state:

- The domain of $f'$.
- Whether interventions are **hard** (set to a fixed value) or **soft** (shift distribution).
- Whether factors are **atomic** (one variable) or **composite** (a set).
- What happens when an intervention is applied to a factor that is already at $f'$.

**Recommended type.**

$$\text{Intervention} = (F, \text{assignment}, \text{hard}|\text{soft}, \text{domain})$$

with the convention:
- Hard intervention: replaces $F$'s values with the assigned values.
- Soft intervention: modifies the conditional distribution of $F$ given its causes.
- Domain: the declared admissible set over which interventions range.

**Consequence.** R604.9 must decide whether the reference calculus supports hard, soft, or both. For finite W1–W7 worlds, hard interventions are sufficient. For real systems, soft interventions may be needed.

## 3.4 Sequential vs joint intervention — under-specified

R604.8 §18 says:

$$\text{JointIntervention} \neq \text{SequentialIntervention}$$

This is correct in general. But the document does not state when they **are** equivalent, which is just as important.

**Recommended condition.**

$$\text{Joint}(F) = \text{Sequential}(F) \iff \forall i, j : \text{no intervention } do(F_i = f_i) \text{ changes the preconditions for } do(F_j = f_j)$$

That is: joint and sequential coincide iff the interventions are **order-independent** — each intervention's effect is independent of the others' ordering.

**Real-world.** A database pipeline: setting `schema = v2` then `migrate` may differ from `migrate` then setting `schema = v2`. Order matters. Joint interventions (`do(schema = v2, migrate = done)`) may be undefined or may define a new state that neither sequential path reaches.

## 3.5 Report discipline still missing

The pattern persists. R604.8 reports only `10/10 passed`. No `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexample, no certificate.

This was frozen in R604.3, was flagged again in R604.6 and R604.7, and has not been implemented. **R604.9 must fix this.** The document's central claim — "we built and executed" — is currently weaker than it could be.

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

## State terms

### State (K)
- **Type:** $K = (X, H)$.

### Authoritative State (X)
- **Real-world:** Accepted patient record.

### History (H)
- **Type:** Append-only event sequence.

### Event
- **Type:** $(ID, Operation, Kind, Inputs, Outputs, Provenance, Time)$.

## Operation terms

### Operation
- **Type:** $(ID, InputType, OutputType, Class, MutationPolicy, Pre, Post, Transform, Scope, Regime, PreservationTargets, LossProfile)$.

### Transformation
- **Type:** $f : X \to Y$.

### OperationClass
- **Values:** `Pure`, `Epistemic`, `Governance`.

### MutationPolicy
- **Type:** $(Policy_X, Policy_H)$.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules)$.

## Provenance terms

### Provenance
- **Type:** $(Source, Actor, Lineage, Time)$.

### Lineage
- **Type:** DAG of $(Node, Edge, Time)$.

### CausalHistory
- **Type:** Ordered sequence of events.

### DerivationContract
- **Type:** $(Y, E, h, \text{proof obligation})$.

## Dependency terms

### DependencyNode
- **Type:** $n \in N$.
- **Real-world:** Evidence item, model output, assumption, transformation.

### DependencyEdge
- **Type:** $e = (a, b, Structure, Mechanism, Factors, Z, S, \Gamma, Provenance)$.
- **Real-world:** "E₁ and E₂ share source S."

### DependencyStructure
- **Values:** `direct`, `common-mode`, `provenance`, `transformation`.

### DependencyMechanism
- **Values:** `source`, `model`, `assumption`, `transformation`, `representation`.

### DependencyFactor
- **Definition:** A variable, condition, assumption, transformation, model, source, or element whose intervention may affect a declared target.
- **Type:** $F_i \in \mathcal{F}_{\text{adm}}(S, \Gamma)$.
- **Real-world:** Source ID, model ID, assumption ID.
- **Invalid:** An observation that isn't intervention-eligible.

### DependencyFactorSet
- **Definition:** A finite subset of admissible factors.
- **Type:** $F \subseteq \mathcal{F}_{\text{adm}}(S, \Gamma)$.
- **Real-world:** $\{S, A, T\}$.

### Intervention (corrected)
- **Definition:** A controlled modification of one or more declared factors while holding others fixed.
- **Type:** $(F, \text{assignment}, \text{hard}|\text{soft}, \text{domain})$.
- **Real-world:** `do(model = M2)` — replace the model.
- **Invalid:** Any modification without declaring hardness and domain.

### JointIntervention
- **Definition:** An intervention applied simultaneously to multiple factors.
- **Type:** $(F = \{F_1, \ldots, F_k\}, \text{assignment})$.
- **Real-world:** `do(S=1, A=1, T=1)`.

### SequentialIntervention
- **Definition:** A composition of single-factor interventions.
- **Type:** $do(F_1 = f_1) \circ \cdots \circ do(F_k = f_k)$.
- **Distinction:** $\text{Joint} \neq \text{Sequential}$ in general (I-E-D10).

### Baseline (corrected — typed)
- **Definition:** A specific admissible state at which materiality is evaluated.
- **Type:** $w \in W_{\text{adm}}(S, \Gamma)$.

### Materiality (corrected — three notions)
- **BaselineSpecificMaterial:** $Z(w) \neq Z(do(F = w')w)$ at a specific $w$.
- **ExistentiallyMaterial:** $\exists w : \text{BaselineSpecificMaterial}(F, Z, w)$.
- **UniversallyMaterial:** $\forall w : \text{BaselineSpecificMaterial}(F, Z, w)$.
- **Real-world:** Trading strategy vulnerability is existentially material; foundational assumptions are universally material.
- **Invalid:** Conflating existential and universal.

### MinimalJointlyMaterialSet
- **Definition:** $F$ is minimal iff $Material(F, Z)$ and no proper subset is material.
- **Type:** $F \in \min_{\subseteq}\{F' : Material(F', Z)\}$.
- **Real-world:** $\{S, A, T\}$ for $Z = S \land A \land T$.
- **Invalid:** Declaring minimality without subset checks.

### MinimalFactors (corrected — family)
- **Definition:** The family of all minimal jointly material sets for $Z$.
- **Type:** $\mathcal{F}^*(Z) \subseteq 2^{\mathcal{F}_{\text{adm}}}$.
- **Real-world:** $\{\{A, B\}, \{C, D\}\}$ for $Z = (A \land B) \lor (C \land D)$.
- **Invalid:** Treating it as a single set.

### InteractionDependency
- **Definition:** Dependency characterized by joint materiality with no proper material subset.
- **Type:** $InteractionDep(F, Z) \iff Material(F, Z) \land \forall F' \subsetneq F : \neg Material(F', Z)$.
- **Real-world:** W7 world.

### DependencyGraph
- **Type:** $(N, E)$ with $E$ = set of DependencyEdges.

### DependencyAssessment
- **Type:** $(E_1, E_2, Z, S, \Gamma, \mathcal{F}^*, Status, Evidence, Counterexample)$.

### DependencyClosure (split)
- **StructuralClosure:** $\{B : A \to^* B\}$ — reachability.
- **MaterialClosure:** $\{B : \exists \text{ material path}\}$.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.

### Counterexample
- **Type:** $(Input, Claim, Witness)$.

## New invariants (R604.8)

### I-E-D08 — No single factor ≠ no dependency
$$NoSingleFactorEffect \neq NoDependency.$$

### I-E-D09 — Materiality ≠ minimality
$$Material(F) \neq Minimal(F).$$

### I-E-D10 — Joint ≠ sequential
$$JointIntervention \neq SequentialIntervention \text{ (unless order-independent)}.$$

### I-E-D11 — Simple graph ≠ complete multi-factor representation
$$SimpleGraphRepresentation \neq CompleteMultiFactorRepresentation.$$

### I-E-D12 — Statistical interaction ≠ logical interaction
$$StatisticalInteraction \neq LogicalInteractionDependency.$$

### I-E-D13 (new) — Minimal factor sets form a family
$$MinimalFactors(Z) \in 2^{2^{\mathcal{F}_{\text{adm}}}} \text{, not } 2^{\mathcal{F}_{\text{adm}}}.$$

### I-E-D14 (new) — Materiality is baseline-scoped
$$\text{Materiality is one of } \{\text{baseline-specific}, \text{existential}, \text{universal}\} \text{ — never unqualified.}$$

---

# Part V — Worked Examples

## Example 1 — W7 Interaction Dependency

**Setup.** Three factors $S, A, T$; target $Z = S \land A \land T$.

**Baseline** $w_0 = (0, 0, 0)$, so $Z(w_0) = 0$.

| Intervention | Target | Material at $w_0$? |
|---|---|---|
| $do(S=1)$ | 0 | No |
| $do(A=1)$ | 0 | No |
| $do(T=1)$ | 0 | No |
| $do(S=1, A=1)$ | 0 | No |
| $do(S=1, T=1)$ | 0 | No |
| $do(A=1, T=1)$ | 0 | No |
| $do(S=1, A=1, T=1)$ | 1 | Yes |

**Minimal factor set:** $\{S, A, T\}$.

**Conclusion.** No proper subset is material at $w_0$; only the joint is. This is the canonical interaction dependency.

**Real-world.** A fraud-detection rule that requires three independent conditions to fire. Each condition alone triggers nothing; only all three together.

## Example 2 — Alternative minimal factor sets

**Setup.** $Z = (A \land B) \lor (C \land D)$.

At baseline where all are 0:

- $do(A=1, B=1)$: $Z = 1$. Material.
- $do(C=1, D=1)$: $Z = 1$. Material.
- $do(A=1)$: $Z = 0$. Not material.
- $do(C=1)$: $Z = 0$. Not material.

**Minimal factor sets:** $\{\{A, B\}, \{C, D\}\}$.

**Conclusion.** There are two independent routes to the target. The dependency assessment must report both.

**Real-world.** Two independent pipelines both producing a fraud signal. Investigating only one misses the other.

## Example 3 — Baseline-relative materiality

**Setup.** $Z = S \land A$.

| Baseline | $do(S=1)$ | Material? |
|---|---|---|
| $(0, 0)$ | $(1, 0) \to 0$ | No |
| $(0, 1)$ | $(1, 1) \to 1$ | **Yes** |
| $(1, 0)$ | $(1, 0) \to 0$ | No |
| $(1, 1)$ | $(1, 1) \to 1$ | No |

**Conclusion.** $S$ is materially relevant at $(0, 1)$ only. `ExistentiallyMaterial({S}, Z) = True`. `UniversallyMaterial({S}, Z) = False`.

**Real-world.** A hypothesis that only matters when another condition holds. Ignoring baselines misses it.

## Example 4 — Joint vs sequential intervention

**Setup.** Three factors with a conditional dependency: `do(A=1, B=1, C=1)` is one operation; sequential is `do(A=1); do(B=1); do(C=1)`.

**Case where they agree.** If A, B, C are independent variables, joint and sequential produce the same state.

**Case where they differ.** If `do(A=1)` changes the admissible domain of `do(B=1)`, then sequential reaches a different state.

**Real-world.** Database migrations: order matters. A schema change followed by a data migration may work; the reverse may fail.

**Condition for equivalence.** $\text{Joint}(F) = \text{Sequential}(F) \iff \text{no intervention changes another's preconditions}$.

## Example 5 — Simple graph cannot represent W7

**Setup.** $Z = S \land A \land T$.

**Graph representation.** Naive graph would add edges $S \to Z$, $A \to Z$, $T \to Z$. But this says "each of $S$, $A$, $T$ alone affects $Z$," which is **false**.

**Correct representation.** A factor-set object:

$$\text{Dependency} = (\text{Nodes} = \{S, A, T\}, \text{Structure} = \text{joint}, \text{Minimality} = \text{yes})$$

**Conclusion.** Simple graphs are insufficient for multi-factor dependency (I-E-D11).

## Example 6 — Why `MinimalFactors(Z)` is a family

**Setup.** $Z = (A \land B) \lor (C \land D) \lor (E \land F)$.

**Minimal sets:** $\{\{A, B\}, \{C, D\}, \{E, F\}\}$.

**Real-world.** Three independent routes to a target. A single-answer diagnosis is wrong; all three must be reported.

---

# Part VI — ML Positioning for Factor Sets

## ML may do

- Propose `CandidateFactorSet(E₁, E₂, F, Score, Z, S, Γ)`.
- Propose `CandidateMinimalFactorSet(E₁, E₂, Z, S, Γ)` — a candidate for $\mathcal{F}^*$.
- Propose `CandidateInteractionFactorSet` — sets where joint materiality is suspected.
- Propose `CandidateAdversarialFactorSet` — near-miss sets.

Each is a typed candidate.

## ML may not do

- Assert materiality.
- Assert minimality.
- Assert dependency.
- Write to X.

## Firewall

```
ML candidate
  → TypeCheck (Factor, FactorSet)
  → ContractCheck
  → RegimeCheck
  → ScopeCheck
  → InterventionCheck (is do(F) admissible?)
  → MaterialityCheck (via reference calculus)
  → MinimalityCheck (via subset search)
  → L4 Verification
  → Assessment
```

## Concrete technique for W1–W7

**Features per pair (E₁, E₂):**

- source overlap
- lineage overlap
- model lineage
- transformation lineage
- assumption overlap
- citation overlap
- temporal proximity
- graph distance
- semantic similarity (embeddings)

**Candidate factor-set generation.** For small factor spaces (< 20), enumerate all subsets. For larger, use beam search over subsets scored by ML.

**Model.** Gradient-boosted trees on the pair features, producing a score per candidate subset.

**Labels.** W1–W7 ground truth: for each world, the true minimal factor family.

**Metrics:**

- **Factor-set Precision:** fraction of declared minimal sets that are truly minimal.
- **Factor-set Recall:** fraction of true minimal sets that are detected.
- **Minimality Error:** fraction of declared minimal sets that contain a proper material subset.
- **Completeness Error:** fraction of true minimal sets that are missed.
- **Interaction Recall (W7):** does the model detect joint materiality?
- **Calibration Error.**
- **Abstention Quality.**

**Cardinal rules:**

$$\boxed{MLAccuracy \neq Dependency}$$
$$\boxed{MLScore \neq Materiality}$$
$$\boxed{MLProposedFactorSet \neq MinimalFactorSet}$$

## Where ML earns its keep

- **Candidate generation** on large factor spaces.
- **Adversarial search** for near-miss factor sets.
- **Calibration** of confidence.
- **OOD detection** for unseen factor combinations.

---

# Part VII — Optimized Architecture (R604.8 baseline)

```
                         KNOWLEDGEOS
                              │
                  ┌───────────┴───────────┐
                  │                       │
              X Authoritative         H History
                  │                       │
                  └───────────┬───────────┘
                              │
                     L0 Kernel (ID, R*, Sem)
                              │
                     L1 Semantic Fabric
             Context / Contract / Scope / Regime
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        Composition / PreservationTarget / Bridge
        Provenance / Lineage / CausalHistory
        DependencyFactor / FactorSet
        Intervention / Joint vs Sequential
                              │
                     L3 Assessment
       Materiality | Minimality | Family of minimal factor sets
       Dependency(Structure, Mechanism, Factors | Z, S, Γ)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     Materiality / Minimality checks
                              │
                ML Firewall (boundary contract)
                Type → Contract → Regime → Scope → Intervention → Materiality → Minimality
                              │
                     L5 Intelligence
       CandidateFactorSet / CandidateMinimalFactorSet / OOD
                              │
                     L6 Governance
                              │
                            Action
```

No L4.5. No new BC. No new Kernel primitive.

## The fourteen laws

$$\boxed{ML \not\to X}$$
$$\boxed{ML \not\to Assessment \text{ without L4}}$$
$$\boxed{Pure \Rightarrow X' = X}$$
$$\boxed{Spec \neq Run \neq Result \neq Cert}$$
$$\boxed{\text{Use the weakest sufficient method}}$$
$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$
$$\boxed{NonCommutativity \neq Error}$$
$$\boxed{Associativity\ is\ }\mathcal{O}\text{-relative}$$
$$\boxed{Target \neq Bridge \neq Assessment}$$
$$\boxed{Provenance \neq CausalHistory \neq Dependency}$$
$$\boxed{Structure \neq Mechanism \neq Factors}$$
$$\boxed{NoSingleFactorEffect \neq NoDependency}$$
$$\boxed{Materiality \neq Minimality}$$
$$\boxed{JointIntervention \neq SequentialIntervention}$$

## The methodological rule (formal)

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

## The self-correction pattern

Four consecutive rounds (R604.4, R604.6, R604.7, R604.8) have self-corrected. This is now the project's **primary quality mechanism** and should be formalized in the Theory Specification.

---

# Part VIII — R604.9 Specification

## What R604.9 must do

R604.8 §28 correctly identifies R604.9 as **Dependency Counterexample and Minimality Stress Test**. But before R604.9 can be executed, four items must be fixed:

### Fixes carried from R604.8

**Fix 1.** Materiality typed as one of `{baseline-specific, existential, universal}`.

**Fix 2.** `MinimalFactors(Z)` typed as a family of sets.

**Fix 3.** `Intervention` typed as `(F, assignment, hard|soft, domain)`.

**Fix 4.** `Joint vs Sequential` condition stated: equivalent iff no intervention changes another's preconditions.

**Fix 5.** Report discipline: per-test `VerificationResult`, `ExecutionRunID`, printed counterexample, certificate.

### Deliverables

**Q1 — Structured counterexample suite (Cases A–J from §28).**
For each case, produce:
- The expected dependency structure.
- A candidate detector's output.
- A counterexample if the detector fails.
- A certificate of the assessment.

**Q2 — Alternative minimal factor sets.**
Test $Z = (A \land B) \lor (C \land D)$ and confirm that the family of minimal sets is $\{\{A, B\}, \{C, D\}\}$ and not $\{A, B, C, D\}$.

**Q3 — Redundant factor detection.**
Given $Z = A \land B$ and a factor $C$ correlated with $A$ but not needed, verify that $C$ is not part of the minimal set.

**Q4 — Suppressor / intervention effects.**
Construct a case where a factor $X$ reduces the materiality of $Y$ when $X$ is intervened upon. Verify the reference calculus handles this.

**Q5 — Scope-dependent dependency.**
Verify that a dependency established under one scope does not transfer without a scope-bridge.

**Q6 — Regime-dependent dependency.**
Verify that a dependency established under one regime does not transfer without a regime-translation witness.

**Q7 — Temporal dependency.**
Introduce time-indexed factors and verify that materiality at $t_1$ does not imply materiality at $t_2$.

**Q8 — Post-transformation dependency.**
Construct a case where a dependency appears only after a transformation. Verify it is detected.

**Q9 — Adversarial ML-generated false dependency.**
Generate an ML candidate that appears plausible but is not material. Verify the firewall rejects it.

**Q10 — Report discipline.**
Every test emits `certificate.json` with all fields.

## What R604.9 must not do

- Do not conflate materiality notions.
- Do not treat `MinimalFactors` as a single set.
- Do not conflate joint and sequential interventions.
- Do not introduce a new BC.
- Do not introduce a new Kernel primitive.
- Do not allow ML to enter the authority path.
- Do not claim universal theorems from finite tests.

## Sequencing

- **R604.9** — Counterexample stress test + report discipline.
- **R604.10** — Full invariant catalogue.
- **R604.11** — Terminology freeze.
- **R604.12** — Theory Specification v1.0.

---

# Part IX — Where We Are and What Remains

## How far we are

- **Kernel:** stable across 604+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC.
- **Operation algebra:** frozen.
- **Composition, non-commutativity, associativity:** executed and proven.
- **Preservation:** executable with bridges.
- **Loss composition:** interaction term frozen.
- **TPP:** executable with counterexamples.
- **Recovery vs augmentation:** separated.
- **Provenance / Lineage / CausalHistory:** executable.
- **Tampered history:** detected.
- **Dependency:** target-, scope-, regime-relative; multi-factor aware.
- **Materiality / Minimality:** executable.
- **W1–W7:** structurally represented, W7 handled.
- **ML firewall:** preserved.
- **Self-falsification:** four consecutive rounds.
- **Report discipline:** still pending.
- **Overall:** the theory is now a fully executable, self-correcting, falsifiable epistemic calculus with multi-factor dependency.

## Remaining TODOs (short)

- **R604.9** — Counterexample stress test (Cases A–J) + report discipline.
- **R604.10** — Full invariant catalogue.
- **R604.11** — Terminology freeze.
- **R604.12** — Theory Specification v1.0.
- **Standing** — no new BC, no new Kernel primitive, no universal theorems from finite tests, use weakest sufficient method, `Structure ≠ Mechanism ≠ Factors`, `Materiality ≠ Minimality`, `Joint ≠ Sequential`, each round must attempt to falsify the previous.

## The single most important thing to do next

**Fix the five items carried from R604.8 and execute the counterexample suite (Cases A–J).** The W7 world is now handled; R604.9 must ensure W1–W7 (and beyond) all survive adversarial stress.

$$\boxed{\text{Type the materiality. Family the minimal sets. Print the counterexamples. Then attack.}}$$

## Two open questions I flag

1. **Hard vs soft interventions.** R604.9 must decide whether the reference calculus supports hard interventions only, or also soft ones. Hard is sufficient for W1–W7; soft is required for real systems. The choice is architectural.

2. **Baseline quantification.** Is the W7 dependency existential (some baseline exhibits materiality) or universal (every baseline exhibits materiality)? The current example is existential. R604.9 must make the convention explicit, because "the dependency exists" is ambiguous without it.

If you want to proceed, tell me:

- **(A)** R604.9 specification (fixes, counterexample suite A–J, report format).
- **(B)** R604.9 code.
- **(C)** Both, in order.

My recommendation is **(A) then (B)**, with **the report discipline fix first** — because R604.9 must produce actual certificates for its stress tests, and the current report format does not surface them.