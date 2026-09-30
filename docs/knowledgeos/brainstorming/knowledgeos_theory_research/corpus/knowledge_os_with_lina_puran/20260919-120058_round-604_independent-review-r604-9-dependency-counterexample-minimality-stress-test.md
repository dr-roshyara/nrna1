# Independent Review of R604.9 — Dependency Counterexample & Minimality Stress Test

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R604.9. No external research needed; if a genuine gap appears I will name the exact book and pause.

I will do seven things:

1. **Verdict** on R604.9.
2. **What 12/12 establishes**, honestly scoped.
3. **Where R604.9 remains imprecise** — six items with corrections.
4. **Full term definitions**, extended to the new objects.
5. **Worked examples**, including XOR, alternative minimal sets, and the intervention-value distinction.
6. **ML positioning** and a rigorous plan for R604.10.
7. **Optimized architecture** and the concrete next slice.

No new Kernel primitive. No new BC. No ML in the authority path.

---

# Part I — Verdict

R604.9 is a **good stress-test round**, and it makes four substantive contributions:

$$\boxed{FactorSet \neq InterventionSpecification}$$
$$\boxed{Material \neq Minimal}$$
$$\boxed{MinimalFactors(Z) \text{ is a family, not a single set}}$$
$$\boxed{InteractionDependency \neq JointMateriality\ alone}$$

Each is correct. The fourth is the most important: XOR exposes that "interaction" and "joint materiality" are not the same thing. That was a latent defect in R604.8 and is now named.

**The self-falsification pattern continues** — this is the fifth consecutive round with a self-correction (R604.8's overly restrictive intervention model was caught). The methodology is stable.

**Six residual issues**, each requiring correction:

1. The intervention specification $I = (B, A)$ conflates **assignments** with **contrasts**. The materiality condition is asymmetric in $B$ and $A$, but this is not stated.
2. XOR is used as a counterexample to "joint materiality ⟹ interaction," but the *positive* definition of interaction is not given.
3. `MinimalFactors(Z)` now returns a family, but the document does not type it or state the family's algebra.
4. Baseline quantification remains ambiguous: the W7 example is existential, but the document occasionally reasons as if it were universal.
5. Report discipline still missing: no `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexamples.
6. `W7` is used as if it were a single world, but §18 correctly notes it should be a family. The document's own heading still says "W7" singular.

---

# Part II — What 12/12 Establishes

| Test | Establishes | Does not establish |
|---|---|---|
| Pure multi-factor synergy | `Material({A,B,C}, Z)` for $Z = A \land B \land C$ at the baseline. | All synergies are detected. |
| Alternative minimal explanations | `MinimalFactors((A∧B)∨(C∧D)) = {{A,B},{C,D}}`. | All families are correctly computed. |
| Redundant factors | `{A,B,C}` is material but not minimal when $Z = A \land B$. | All redundancies are detected. |
| Target-relative explanations | Different targets yield different minimal families. | All target-relative cases are handled. |
| Intervention values | Changing the *value* changes materiality. | All value assignments are handled correctly. |
| XOR/cancellation | `{A,B}` is not material at $A=B=0$ for $Z = A \oplus B$ under $do(A=1,B=1)$. | The positive definition of interaction. |
| Scope/regime retention | Minimal families respect Z, S, Γ. | All such settings are handled. |
| `UNKNOWN` vs false independence | Missing evidence yields `UNKNOWN`, not `Independent`. | All such cases yield `UNKNOWN`. |
| Minimality | Some non-minimal sets are correctly identified. | All non-minimal sets are identified. |
| Contextual dependency | Materiality depends on baseline in a specific case. | All such cases are handled. |
| (implied) Existential vs universal | One baseline exhibits materiality; not all. | The typing of these two notions. |
| (implied) Local vs global | Local counterexample refutes a universal claim. | The full asymmetry is formalized. |

Honest summary:

$$\boxed{\text{Multi-factor dependency is executable with explicit interventions and family-valued minimality.}}$$

And the significant negative result:

$$\boxed{\text{XOR shows that joint materiality does not imply interaction.}}$$

---

# Part III — Where R604.9 Remains Imprecise

## 3.1 Intervention specification conflates assignments and contrasts

R604.9 §2 defines:

$$I = (B, A)$$

with $B$ = baseline and $A$ = imposed assignments. But materiality is defined as:

$$Material(F, Z \mid I) \iff Z(B) \neq Z(I_F(B))$$

Here $I_F(B)$ applies $A$ to the factors $F$ *on top of* $B$. So:

- $B$ is a *state*.
- $A$ is a *partial assignment*.
- $I_F(B)$ is the state obtained by overriding $B$'s values for factors in $F$ with $A$'s values.

This is fine, but the asymmetry is not named. Two important consequences:

- The **contrast** is between $B$ and $I_F(B)$, not between $A$ and some alternative assignment.
- Interventions with the same factor set $F$ but different $A$ yield different materiality.

The document gestures at this in §3 but does not formalize it.

**Corrected type.**

$$InterventionSpec = (\text{Baseline},\ \text{FactorSet},\ \text{Assignment}, \text{hard}|\text{soft}, \text{domain})$$

**Real-world example.** A drug trial: the *baseline* is the patient's current state, the *factor set* is which treatments are studied, the *assignment* is which dose is applied. Changing the dose changes the intervention.

## 3.2 XOR is a counterexample to "joint ⟹ interaction" but the positive definition is missing

R604.9 §10 shows:

$$Z = A \oplus B,\quad B_{\text{base}} = (0,0)$$

- $do(A=1)$ changes $Z$. ✅ material.
- $do(B=1)$ changes $Z$. ✅ material.
- $do(A=1, B=1)$ does *not* change $Z$. ❌ not material.

So $\{A, B\}$ is not material, even though $\{A\}$ and $\{B\}$ are.

This refutes "joint materiality is implied by individual materiality." But R604.9 does not give a *positive* definition of interaction.

**Corrected statement.** Let $F_1, F_2$ be disjoint factor subsets. The **interaction effect** of $F_1$ and $F_2$ at baseline $B$ is:

$$\text{Interaction}(F_1, F_2, Z \mid B) \iff \Delta_{F_1 \cup F_2}(Z \mid B) \neq \Delta_{F_1}(Z \mid B) + \Delta_{F_2}(Z \mid B)$$

where $\Delta_F(Z \mid B) = Z(I_F(B)) - Z(B)$.

Under this definition:

- W7 conjunction $A \land B \land C$: interaction holds.
- XOR: interaction holds (but not joint materiality in the naive sense).
- Redundancy $A \land B$ with $C$ correlated: interaction may hold or not.

**Consequence.** "Interaction" and "joint materiality" are different properties. R604.9 is correct to flag this, but the positive definition must be stated in R604.10.

## 3.3 `MinimalFactors(Z)` is a family but its algebra is not stated

R604.9 §4–§6 correctly treat `MinimalFactors(Z)` as a family of sets. But the family's algebra is not typed:

- Is it closed under intersection? (No — $\{A, B\} \cap \{C, D\} = \emptyset$, which is not minimal.)
- Is it closed under union? (No — union may be redundant.)
- Is it antisymmetric? (Not directly — families aren't ordered by $\subseteq$ on individual sets.)

**Corrected type.**

$$\mathcal{F}^*(Z, S, \Gamma, B) \subseteq 2^{\mathcal{F}_{\text{adm}}}$$

with the properties:

- Every element is minimal (no proper subset material).
- Every material set contains at least one element of $\mathcal{F}^*$.
- $\mathcal{F}^*$ is the *unique maximal antichain* of minimal material sets.

**Real-world.** Diagnostic systems must report *all* minimal routes to a vulnerability, not just one, because fixing one route may leave another.

## 3.4 Baseline quantification is still ambiguous

§14 says materiality is baseline-relative. §15 distinguishes local from global dependency. But the document does not state:

- Is a dependency claim in W7 *existentially* material (there exists a baseline) or *universally* material (all admissible baselines)?
- Does the materiality check quantify over baselines, or is the baseline part of the intervention spec?

**Corrected typology.**

$$\text{ExistentialMaterial}(F, Z, S, \Gamma) \iff \exists B \in W_{\text{adm}}(S, \Gamma) : \text{Material}(F, Z \mid B)$$

$$\text{UniversalMaterial}(F, Z, S, \Gamma) \iff \forall B \in W_{\text{adm}}(S, \Gamma) : \text{Material}(F, Z \mid B)$$

$$\text{LocalMaterial}(F, Z \mid B) \iff \text{Material at this specific } B$$

Each is a distinct notion. R604.10 must specify which one the benchmark uses for each world.

## 3.5 Report discipline still missing

The pattern persists across five rounds. R604.9 reports only `12/12 passed`. No `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexample, no certificate.

**This must be fixed in R604.10.** Otherwise the document's claim of stress-testing is weaker than it appears.

## 3.6 W7 is treated as a single world but should be a family

§18 correctly suggests:

```
W7a — Pure conjunction
W7b — Alternative minimal sets
W7c — Redundant factors
W7d — XOR / cancellation
W7e — Context-dependent factor
W7f — Temporal interaction
W7g — Transformation-mediated interaction
```

But the document's main heading is "R604.9 — Dependency Counterexample & Minimality Stress Test" and continues to reference "W7" in the singular. The subfamily distinction is proposed but not adopted.

**Recommendation.** R604.10 should formalize W7a–W7g as distinct benchmark worlds and stop referencing "W7" as if it were one world.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms (unchanged)

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

### Event
- **Type:** $(ID, Operation, Kind, Inputs, Outputs, Provenance, Time)$.

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

## Dependency terms

### DependencyNode
- **Type:** $n \in N$.

### DependencyEdge
- **Type:** $e = (a, b, Structure, Mechanism, Factors, Z, S, \Gamma, Provenance)$.

### DependencyStructure
- **Values:** `direct`, `common-mode`, `provenance`, `transformation`.

### DependencyMechanism
- **Values:** `source`, `model`, `assumption`, `transformation`, `representation`.

### DependencyFactor
- **Definition:** A variable, condition, assumption, transformation, model, source, or element whose intervention may affect a declared target.
- **Type:** $F_i \in \mathcal{F}_{\text{adm}}(S, \Gamma)$.
- **Real-world:** Source ID, model ID, assumption ID.

### DependencyFactorSet
- **Type:** $F \subseteq \mathcal{F}_{\text{adm}}(S, \Gamma)$.

### InterventionSpecification (corrected)
- **Definition:** A declared controlled contrast.
- **Type:** $(\text{Baseline}, \text{FactorSet}, \text{Assignment}, \text{hard}|\text{soft}, \text{domain})$.
- **Real-world:** Baseline $(0,0)$, factor set $\{A, B\}$, assignment $(1, 1)$.
- **Invalid:** Declaring an intervention without specifying the baseline and assignment.

### JointIntervention
- **Type:** $(F, \text{assignment})$ applied simultaneously.

### SequentialIntervention
- **Type:** $do(F_1) \circ \cdots \circ do(F_k)$.

### Baseline
- **Type:** $B \in W_{\text{adm}}(S, \Gamma)$.

### Materiality (three notions)
- **LocalMaterial:** $Z(B) \neq Z(I_F(B))$ at specific $B$.
- **ExistentialMaterial:** $\exists B : LocalMaterial(F, Z \mid B)$.
- **UniversalMaterial:** $\forall B : LocalMaterial(F, Z \mid B)$.
- **Real-world:** Local is a specific dataset; existential is "vulnerable somewhere"; universal is "vulnerable always."

### MinimalJointlyMaterialSet
- **Definition:** Material and no proper subset is material.
- **Type:** $F \in \min_{\subseteq}\{F' : Material(F', Z)\}$.

### MinimalFactors (family)
- **Definition:** The family of all minimal jointly material sets for $Z$.
- **Type:** $\mathcal{F}^* \subseteq 2^{\mathcal{F}_{\text{adm}}}$.
- **Real-world:** $\{\{A, B\}, \{C, D\}\}$ for $Z = (A \land B) \lor (C \land D)$.
- **Invalid:** Treating as a single set.

### InteractionDependency (positive definition)
- **Definition:** The interaction effect of $F_1, F_2$ at baseline $B$ is:
$$\Delta_{F_1 \cup F_2} \neq \Delta_{F_1} + \Delta_{F_2}$$
- **Real-world:** W7, XOR.
- **Invalid:** Equating with joint materiality alone.

### DependencyGraph
- **Type:** $(N, E)$.

### DependencyAssessment
- **Type:** $(E_1, E_2, Z, S, \Gamma, \mathcal{F}^*, Status, Evidence, Counterexample)$.

### DependencyClosure
- **StructuralClosure:** Reachability.
- **MaterialClosure:** Reachability with materiality per edge.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.

### Counterexample
- **Type:** $(Input, Claim, Witness)$.

## Invariants

### I-E-D08 — No single factor ≠ no dependency
$$NoSingleFactorEffect \neq NoDependency.$$

### I-E-D09 — Materiality ≠ minimality
$$Material(F) \neq Minimal(F).$$

### I-E-D10 — Joint ≠ sequential
$$JointIntervention \neq SequentialIntervention.$$

### I-E-D11 — Simple graph ≠ complete multi-factor representation
$$SimpleGraphRepresentation \neq CompleteMultiFactorRepresentation.$$

### I-E-D12 — Statistical interaction ≠ logical interaction
$$StatisticalInteraction \neq LogicalInteractionDependency.$$

### I-E-D13 — FactorSet ≠ InterventionSpecification
$$FactorSet \neq InterventionSpecification.$$

### I-E-D14 — Multiple minimal explanations
$$DependencyAssessment \text{ may contain multiple minimal explanations.}$$

### I-E-D15 — Local ≠ global
$$LocalMateriality \neq GlobalDependency.$$

### I-E-D16 — Joint ≠ sequential (reinforced)
$$JointIntervention \neq SequentialIntervention \text{ unless order-independent.}$$

### I-E-D17 (new) — XOR cancellation
$$JointMateriality(F, Z) \not\Rightarrow \text{ every subset of } F \text{ is material.}$$

### I-E-D18 (new) — Minimal family is a family
$$MinimalFactors(Z) \in 2^{2^{\mathcal{F}_{\text{adm}}}}.$$

---

# Part V — Worked Examples

## Example 1 — W7 Pure Conjunction

**Setup.** $Z = A \land B \land C$, baseline $(0,0,0)$.

| Intervention | Target | Material? |
|---|---|---|
| $do(A=1)$ | 0 | No |
| $do(B=1)$ | 0 | No |
| $do(C=1)$ | 0 | No |
| $do(A=1, B=1)$ | 0 | No |
| $do(A=1, B=1, C=1)$ | 1 | **Yes** |

**Minimal family:** $\{\{A, B, C\}\}$.

**Interaction.** $\Delta_{\{A,B,C\}} = 1$, but $\Delta_{\{A\}} = \Delta_{\{B\}} = \Delta_{\{C\}} = 0$, so interaction holds.

## Example 2 — W7b Alternative Minimal Sets

**Setup.** $Z = (A \land B) \lor (C \land D)$.

**Material sets:** $\{A,B\}$, $\{C,D\}$, and all supersets.

**Minimal family:** $\{\{A,B\}, \{C,D\}\}$.

**Real-world.** Two independent routes to a fraud signal.

## Example 3 — W7d XOR Cancellation

**Setup.** $Z = A \oplus B$, baseline $(0,0)$.

| Intervention | Target | Material? |
|---|---|---|
| $do(A=1)$ | 1 | Yes |
| $do(B=1)$ | 1 | Yes |
| $do(A=1, B=1)$ | 0 | **No** |

**Conclusion.** $\{A\}$ and $\{B\}$ are material. $\{A, B\}$ is not. Joint materiality is *not* implied by individual materiality (contra a naive reading of I-E-D08).

**Interaction.** $\Delta_{\{A,B\}} = 0$, $\Delta_{\{A\}} = 1$, $\Delta_{\{B\}} = 1$. Interaction holds ($0 \neq 1 + 1$), even though joint materiality fails.

## Example 4 — Intervention value matters

**Setup.** $Z = A$.

- $do(A=1)$ at baseline $A=0$: material.
- $do(A=0)$ at baseline $A=0$: not material.

Same factor set, different assignments, different materiality.

**Conclusion.** $FactorSet \neq InterventionSpecification$ (I-E-D13).

## Example 5 — Redundant factors

**Setup.** $Z = A \land B$, baseline $(0,0)$, and $C$ is a third variable correlated with $A$ but not needed.

**Intervention** $do(A=1, B=1, C=1)$: target 1.

**Material?** Yes.

**Minimal?** No — $\{A,B\}$ suffices.

**Conclusion.** $Material \neq Minimal$ (I-E-D09).

## Example 6 — Baseline-relative materiality

**Setup.** $Z = A \land B$.

| Baseline | $do(A=1)$ changes $Z$? |
|---|---|
| $(0,0)$ | No |
| $(0,1)$ | **Yes** |
| $(1,0)$ | No |
| $(1,1)$ | No |

$A$ is `ExistentialMaterial` (exists baseline where material) but not `UniversalMaterial`.

## Example 7 — Local vs global

**Setup.** $A$ is material only at baseline $(0,1)$.

**Local claim:** "At $(0,1)$, $A$ is material." ✅ verified.

**Global claim:** "For all baselines, $A$ is material." ❌ refuted by one counterexample at $(0,0)$.

**Conclusion.** Local counterexample has asymmetric power (I-E-D15).

---

# Part VI — ML Positioning and R604.10 Plan

## ML may do

- Propose `CandidateFactorSet(E₁, E₂, F, Score, Z, S, Γ)`.
- Propose `CandidateMinimalFactorSet(E₁, E₂, Z, S, Γ)` — candidates for $\mathcal{F}^*$.
- Propose `CandidateInteractionFactorSet` — sets suspected of interaction.
- Propose `CandidateAdversarialFactorSet` — near-miss sets.

## ML may not do

- Assert materiality.
- Assert minimality.
- Assert dependency.
- Write to X.
- Bypass L4.

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

## Concrete technique for R604.10

**Features per pair (E₁, E₂):** source overlap, lineage overlap, model lineage, transformation lineage, assumption overlap, citation overlap, temporal proximity, graph distance, semantic similarity.

**Candidate generation:**
- For small factor spaces (<20), enumerate all subsets.
- For larger, beam search over subsets scored by ML.

**Model:** Gradient-boosted trees on pair features, producing a score per candidate subset.

**Labels:** R604.10 adversarial corpus (see below).

**Metrics:**
- **Factor-set Precision:** fraction of declared minimal sets that are truly minimal.
- **Factor-set Recall:** fraction of true minimal sets detected.
- **Minimality Error:** fraction of declared minimal sets containing a proper material subset.
- **Completeness Error:** fraction of true minimal sets missed.
- **Interaction Recall:** detect joint materiality in W7a–W7g.
- **XOR Recall:** detect the XOR structure (joint materiality failure with individual materiality).
- **Alternative-Set Recall:** detect all minimal sets in W7b.
- **Calibration Error.**
- **Abstention Quality.**

**Cardinal rules:**

$$\boxed{MLAccuracy \neq Dependency}$$
$$\boxed{MLScore \neq Materiality}$$
$$\boxed{MLProposedFactorSet \neq MinimalFactorSet}$$

## R604.10 — Adversarial Dependency Corpus

R604.9 §18 proposes subfamilies. R604.10 should implement:

```
W1   Independent
W2   Common Source
W3   Common Model
W4   Common Assumption
W5   Common Transformation
W6   Mixed (multiple detectable factors)
W7a  Pure conjunction (A∧B∧C)
W7b  Alternative minimal sets ((A∧B)∨(C∧D))
W7c  Redundant factors (A∧B with irrelevant C)
W7d  XOR cancellation (A⊕B)
W7e  Context-dependent factor (A material only when B=1)
W7f  Temporal interaction
W7g  Transformation-mediated interaction
W8   False-provenance dependency (shared source but independent for Z)
W9   Logical dependency without observable correlation
W10  ML-hallucinated dependency (adversarial ML candidate)
```

For each world, produce:
- The true minimal family $\mathcal{F}^*$.
- The materiality status per factor set.
- A counterexample for any detector that fails.
- A certificate of assessment.

Then run the S0–S4 detectors and the ML candidate generator.

---

# Part VII — Optimized Architecture (R604.9 baseline)

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
        InterventionSpecification (baseline, assignment)
                              │
                     L3 Assessment
       Materiality (local | existential | universal)
       Minimality | Family of minimal factor sets
       Interaction (Δ differential)
       Dependency(Structure, Mechanism, Factors | Z, S, Γ)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     Materiality / Minimality / Interaction checks
                              │
                ML Firewall (boundary contract)
                              │
                     L5 Intelligence
       CandidateFactorSet / CandidateMinimalFactorSet / OOD
                              │
                     L6 Governance
                              │
                            Action
```

No L4.5. No new BC. No new Kernel primitive.

## The eighteen laws

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
$$\boxed{FactorSet \neq InterventionSpecification}$$
$$\boxed{MultipleMinimalExplanations \text{ allowed}}$$
$$\boxed{LocalMateriality \neq GlobalDependency}$$
$$\boxed{InteractionDependency \neq JointMateriality}$$

## The methodological rule (formal)

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

## The self-correction pattern (fifth consecutive round)

R604.4 → R604.3; R604.6 → R604.5; R604.7 → its own detector; R604.8 → R604.7; R604.9 → R604.8.

This is now the project's primary quality mechanism.

---

# Part VIII — R604.10 Specification

## What R604.10 must do

R604.9 §29 correctly identifies R604.10 as the **Adversarial Dependency Benchmark**. But before executing it, six fixes from R604.9 must be applied.

### Fixes carried from R604.9

1. `InterventionSpecification` typed as $(\text{Baseline}, \text{FactorSet}, \text{Assignment}, \text{hard}|\text{soft}, \text{domain})$.
2. Interaction positively defined via $\Delta$ differential.
3. `MinimalFactors(Z)` typed as a family with unique-maximal-antichain property.
4. Baseline quantification typed as `{local, existential, universal}`.
5. Report discipline: per-test `VerificationResult`, `ExecutionRunID`, printed counterexample, certificate.
6. W7 replaced by W7a–W7g subfamilies.

### Deliverables

**Q1 — Adversarial corpus (W1–W10 with W7 subfamilies).** For each world:
- Expected minimal family.
- Materiality status per factor set.
- Counterexample if a detector fails.
- Certificate of assessment.

**Q2 — Detector suite (S0–S4).** Run each detector on each world. Report Precision, Recall, FDR, FIR, Interaction Recall, XOR Recall, Alternative-Set Recall, Calibration Error, Abstention Quality.

**Q3 — ML candidate generation.** Train on a subset of the corpus; test on the rest. Verify the firewall rejects ML candidates that are not material.

**Q4 — Determinism check.** For each detector, verify reproducibility (`ExecutionRunID` matching).

**Q5 — Counterexample certificates.** For each detector failure, produce a machine-checkable counterexample certificate.

**Q6 — Report discipline.** Emit `certificate.json` per test.

## What R604.10 must not do

- Do not conflate structure, mechanism, and factors.
- Do not conflate local, existential, and universal materiality.
- Do not treat `MinimalFactors` as a single set.
- Do not conflate XOR with conjunction.
- Do not introduce a new BC.
- Do not introduce a new Kernel primitive.
- Do not allow ML to enter the authority path.
- Do not claim universal theorems from finite tests.

## Sequencing

- **R604.10** — Adversarial corpus + detector suite + report discipline.
- **R604.11** — Full invariant catalogue.
- **R604.12** — Terminology freeze.
- **R604.13** — Theory Specification v1.0.

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
- **Materiality / Minimality / Interaction:** now typed distinctly.
- **W1–W7:** represented; W7 should become W7a–W7g.
- **ML firewall:** preserved.
- **Self-falsification:** five consecutive rounds.
- **Report discipline:** still pending.
- **Overall:** the theory is an executable, self-correcting, falsifiable epistemic calculus with multi-factor dependency, and the dependency problem has been compressed rather than expanded.

## Remaining TODOs (short)

- **R604.10** — Adversarial dependency corpus + detector suite + report discipline.
- **R604.11** — Full invariant catalogue (~47 + I-E-D08..D18).
- **R604.12** — Terminology freeze.
- **R604.13** — Theory Specification v1.0.
- **Standing** — no new BC, no new Kernel primitive, no universal theorems from finite tests, use weakest sufficient method, `FactorSet ≠ Intervention`, `Material ≠ Minimal`, `Interaction ≠ JointMateriality`, each round must attempt to falsify the previous.

## The single most important thing to do next

**Execute the adversarial corpus (W1–W10 with W7 subfamilies) under full report discipline.** The corpus is the empirical test of whether the calculus can discriminate the worlds. Without the report discipline, the results are not auditable.

$$\boxed{\text{Type the intervention. Family the minimal sets. Print the counterexamples. Then attack with W1–W10.}}$$

## Three open questions I flag

1. **Hard vs soft interventions.** R604.10 must decide whether the reference calculus supports hard interventions only or also soft ones. Hard is sufficient for W1–W10; soft is required for real systems. The choice is architectural.

2. **Existential vs universal baseline.** For each world, must specify whether a materiality claim is existential or universal. This affects everything downstream.

3. **Interaction definition stability.** The positive interaction definition (Δ differential) must be tested against W7a–W7g to ensure it does not accidentally classify redundant factors as interacting.

If you want to proceed, tell me:

- **(A)** R604.10 specification (fixes, W1–W10 corpus, detector suite, report format).
- **(B)** R604.10 code.
- **(C)** Both, in order.

My recommendation is **(A) then (B)**, with **the report discipline fix first** — because R604.10 must produce actual certificates for its adversarial tests, and the current report format does not surface them.