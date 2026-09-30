# Review of R604.7 — Dependency as a Target-, Scope-, and Regime-Relative Relation

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment. I proceed from R604.7's baseline. No external research needed; if a genuine gap appears I will name the exact book and pause.

I will do seven things:

1. **Verdict** on R604.7.
2. **What 10/10 establishes**, honestly scoped.
3. **Where R604.7 remains imprecise** — five items with corrections.
4. **Full term definitions**, extended to the new objects.
5. **Worked examples**, including W1–W7 and the interaction-dependency case.
6. **ML positioning** for dependency candidates.
7. **Optimized architecture** and the concrete next slice (R604.8).

No new Kernel primitive. No new BC. No ML in the authority path.

---

# Part I — Verdict

R604.7 is a **genuine advance**, and it makes three substantive contributions:

$$\boxed{Dependency\ is\ target\text{-},\ scope\text{-},\ and\ regime\text{-}relative.}$$
$$\boxed{CommonModeDependency \neq DirectDependency.}$$
$$\boxed{DependencyDetection \neq DependencyExplanation.}$$

Each is correct, and the first is the strongest. It reframes dependency from a binary relation into a **four-place predicate**:

$$Dep(E_1, E_2 \mid Z, S, \Gamma)$$

That is a real architectural decision, and it rules out several naive formulations that earlier rounds had implicitly assumed.

R604.7 also performed the correct self-falsification cycle: the common-mode detector initially ignored scope and regime, was found wrong by the executable test, and was corrected. That is the **third consecutive** round with a self-correction. This pattern is now the project's most reliable quality mechanism.

**Five residual issues**, each requiring correction before R604.8:

1. The dependency kind taxonomy mixes **mechanism** (common source, common model) with **structure** (direct causal, common-mode). These are different axes.
2. The counterfactual test is stated but not formalized as an intervention.
3. The proposal in §22 to carry a **factor set** on the edge is correct but not yet typed.
4. `DependencyClosure` is graph reachability, but the document does not state whether it is transitive, reflexive, or materiality-aware.
5. The report discipline issue persists: no `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexamples.

---

# Part II — What 10/10 Establishes

| Test | Establishes | Does not establish |
|---|---|---|
| W1 Independent → `UNKNOWN` | The engine does not declare independence without proof. | All independences are `UNKNOWN`. |
| W2 Common Source | Common-mode dependency detected via shared source. | All common-mode dependencies are detected. |
| W3 Common Model | Common-model dependency detected. | All common-model dependencies are detected. |
| W4 Common Assumption | Common-assumption dependency detected. | All assumption dependencies are detected. |
| W5 Common Transformation | Common-transformation dependency detected. | All transformation dependencies are detected. |
| W6 Mixed | Multiple mechanisms retained, not collapsed. | All multi-mechanism cases are handled. |
| W7 Multi-Factor | The engine correctly abstains on partial detection. | The correct full-factor detection is implemented. |
| Scope-relative filter | A dependency valid under one scope was rejected under another. | All scope-relative rejections are correct. |
| Regime-relative filter | A dependency valid under one regime was rejected under another. | All regime-relative rejections are correct. |
| Dependency closure | Reachability computes a finite closure. | The closure carries causal or material truth. |

Honest summary:

$$\boxed{\text{Dependency assessment is executable, target/scope/regime-aware, and self-correcting.}}$$

And:

$$\boxed{\text{The engine found a real defect (scope/regime filtering) that the design had missed.}}$$

---

# Part III — Where R604.7 Remains Imprecise

## 3.1 The dependency kind taxonomy mixes two axes

R604.7 §4 lists six "kinds":

- Direct causal dependency.
- Common-mode dependency.
- Provenance dependency.
- Transformation dependency.
- Assumption dependency.
- Model dependency.

But these are not all on the same axis. Compare:

- **Structural axis:** direct, common-mode, provenance, transformation.
- **Mechanism axis:** source, model, assumption, transformation.

Two different classifications are being merged into one enum. That will cause trouble in W6, W7, and beyond.

**Corrected design.** Two orthogonal fields:

$$\text{Structure} \in \{\text{direct},\ \text{common-mode},\ \text{provenance},\ \text{transformation}\}$$

$$\text{Mechanism} \in \{\text{source},\ \text{model},\ \text{assumption},\ \text{transformation},\ \text{representation}\}$$

An edge carries **both**:

$$e = (a,\ b,\ Structure,\ Mechanism,\ Z,\ S,\ \Gamma,\ Provenance)$$

**Counterexample.** In W6, the edge $E_1 \leftarrow S \rightarrow E_2$ is `(common-mode, source)`. The edge $E_1 \leftarrow M \rightarrow E_2$ is `(common-mode, model)`. Under the current taxonomy they are two "kinds"; under the corrected taxonomy they share `Structure = common-mode` but differ in `Mechanism`. That distinction matters when we later ask "does changing the source change the dependency?"

## 3.2 The counterfactual test is not formalized as an intervention

R604.7 §16 says:

> If we intervene on $E_2$ while holding $C$ fixed and observe $Z(E_1^{do(E_2=a)}) \neq Z(E_1^{do(E_2=b)})$, then $E_1$ depends on $E_2$.

This is the right idea, but it uses `do()` without specifying the intervention algebra:

- Is the intervention **hard** (set to value) or **soft** (shift distribution)?
- Are interventions **atomic** (one node) or **joint** (multiple nodes)?
- Are there **backdoor** paths that must be blocked?

Without these, the counterfactual test is a schema, not an algorithm. And the reference calculus does not model interventions at all — it operates on finite observational states.

**Corrected statement for R604.7.**

$$\text{CounterfactualDep}(E_1, E_2 \mid Z, S, \Gamma) \iff \exists\ a, b : \text{ObservedFiniteTest}\left[\, E_1 \mid E_2 = a \,\right] \neq \text{ObservedFiniteTest}\left[\, E_1 \mid E_2 = b \,\right]$$

This is **observational conditional dependency**, not interventional. It is weaker than Pearl's `do()`. R604.8 must either:

- Extend the reference calculus with an intervention algebra (hard), or
- Explicitly downgrade to observational conditionals and label them as such (soft).

The latter is honest and sufficient for the finite W1–W7 worlds.

## 3.3 The "factor set" is proposed but not typed

R604.7 §22 proposes:

$$D = (Nodes, Factors, Target, Scope, Regime, Mechanism)$$

But `Factors` is not typed. Is it:

- A set of nodes?
- A set of edges?
- A set of mechanisms?
- A multiset?
- A lattice (support, interaction, supersession)?

For W7 (interaction dependency), the answer matters. If $f(S, A, T) = S \land A \land T$ in the sense that all three are required, then the factor set is $\{S, A, T\}$ with an *interaction* structure that no subset captures.

**Recommended type.**

$$Factors = (F_{direct},\ F_{interaction})$$

where:
- $F_{direct}$ — factors whose individual presence is necessary.
- $F_{interaction}$ — sets of factors whose *joint* presence is necessary.

Then W7 is characterized by $F_{interaction} \neq \emptyset$.

R604.7 gestures at this but does not type it. R604.8 should.

## 3.4 `DependencyClosure` is under-specified

R604.7 §18 defines:

$$Closure(A) = \{B : A \to^* B\}$$

This is graph reachability. But the document does not state:

- Is the relation reflexive? ($A \in Closure(A)$?)
- Is it transitive? (Yes, by definition of $\to^*$.)
- Does it require **materiality** at each step?
- Does it distinguish **necessary** from **sufficient** paths?

**Counterexample.** Suppose $A \to B$ is a *necessary* dependency (B cannot exist without A) and $B \to C$ is a *sufficient* dependency (B is enough to produce C, but C could also come from B'). Does $A \to^* C$ hold? Not necessarily — the path is necessary then sufficient, not necessarily composed.

**Recommendation.** Distinguish:

$$\text{StructuralClosure}(A) = \{B : A \to^* B\}$$ — pure reachability.

$$\text{MaterialClosure}(A) = \{B : \exists \text{ material path from } A \text{ to } B\}$$ — with materiality checks per edge.

R604.8 must decide which is the intended notion.

## 3.5 Report discipline still missing

The pattern persists. R604.7 reports only `10/10 passed`. No `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexample, no certificate.

This was frozen in R604.3 and has not been implemented. **R604.8 must fix this** — otherwise the "execution evidence" claim is weaker than the document suggests.

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
- **Real-world:** Bank ledger + audit log.

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

## Provenance terms (R604.6)

### Provenance
- **Type:** $(Source, Actor, Lineage, Time)$.

### Lineage
- **Type:** DAG of $(Node, Edge, Time)$.

### CausalHistory
- **Type:** Ordered sequence of recorded events.

### DerivationContract
- **Type:** $(Y, E, h, \text{proof obligation})$.

## Dependency terms (R604.7)

### DependencyNode
- **Definition:** Identifiable epistemic object that may participate in a dependency relation.
- **Type:** $n \in N$.
- **Real-world:** An evidence item, model output, assumption, transformation, determination, source, derived claim.
- **Invalid:** An unnamed or non-identifiable entity.

### DependencyEdge
- **Definition:** Declared or assessed relationship between two nodes, carrying structure, mechanism, target, scope, regime.
- **Type:** $e = (a, b, Structure, Mechanism, Z, S, \Gamma, Provenance)$.
- **Real-world:** "E₁ and E₂ share source S (common-mode, source)."
- **Invalid:** $e = (a, b)$ with no kind.
- **Invariant:** I-E-D01, I-E-D05.

### DependencyStructure (corrected — new axis)
- **Definition:** The relational shape of the dependency.
- **Values:** `direct`, `common-mode`, `provenance`, `transformation`.
- **Real-world:** Direct: A produces B. Common-mode: A and B share an ancestor.
- **Invalid:** Conflating with mechanism.

### DependencyMechanism (corrected — new axis)
- **Definition:** The causal or epistemic *reason* for the dependency.
- **Values:** `source`, `model`, `assumption`, `transformation`, `representation`.
- **Real-world:** Common source, common ML model, common assumption.
- **Invalid:** Conflating with structure.

### DependencyFactors (corrected — typed)
- **Definition:** Factors whose joint or individual presence creates dependency.
- **Type:** $(F_{direct}, F_{interaction})$.
- **Real-world:** W7 interaction: each factor alone insufficient; all three required.
- **Invalid:** Untyped set of "factors."
- **Invariant:** I-E-D07.

### DependencyGraph
- **Definition:** Structural model of nodes and edges.
- **Type:** $(N, E)$ with $E$ = set of DependencyEdges.

### DependencyAssessment
- **Definition:** Epistemic judgment about whether a dependency holds.
- **Type:** $(E_1, E_2, Z, S, \Gamma, Status, Evidence, Counterexample)$.
- **Real-world:** `EstablishedDependency` or `UNKNOWN`.
- **Invalid:** Confusing with graph structure.
- **Invariant:** I-E-D06.

### DependencyClosure (corrected — two notions)
- **StructuralClosure:** $\{B : A \to^* B\}$ — pure reachability.
- **MaterialClosure:** $\{B : \exists \text{ material path}\}$ — reachability with materiality per edge.
- **Real-world:** Structural: transitive closure. Material: transitive closure with side conditions.
- **Invalid:** Treating reachability as causal truth.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.
- **Rules:** $UNKNOWN \neq FAIL$, $UNDEFINED \neq FAIL$, $NOT\_APPLICABLE \neq PASS$.

### Counterexample
- **Type:** $(Input, Claim, Witness)$.

## New invariants (from R604.7)

### I-E-D01 — Target-relativity
$$Dep(E_1, E_2 \mid Z, S, \Gamma) \text{ is undefined without } Z.$$

### I-E-D02 — Scope-relativity
$$Dep(E_1, E_2 \mid S_1) \not\Rightarrow Dep(E_1, E_2 \mid S_2).$$

### I-E-D03 — Regime-relativity
$$Dep(E_1, E_2 \mid \Gamma_1) \not\Rightarrow Dep(E_1, E_2 \mid \Gamma_2).$$

### I-E-D04 — No observed edge ≠ proven independence
$$\neg ObservedDep(E_1, E_2) \not\Rightarrow ProvenIndependent(E_1, E_2).$$

### I-E-D05 — Common-mode ≠ direct
$$E_1 \leftarrow C \rightarrow E_2 \not\Rightarrow E_1 \rightarrow E_2.$$

### I-E-D06 — Candidate ≠ Established
$$CandidateDependency \neq EstablishedDependency.$$

### I-E-D07 — Single-factor detection is incomplete for multi-factor dependency
$$Detected(F_1) \not\Rightarrow Detected(AllMaterialFactors).$$

### I-E-D08 (new) — Structure ≠ Mechanism
$$(Structure = common\text{-}mode) \land (Mechanism = source) \neq (Structure = common\text{-}mode) \land (Mechanism = model).$$

---

# Part V — Worked Examples

## Example 1 — W2 Common Source

Structure:

$$E_1 \leftarrow S \rightarrow E_2$$

Dependency edge: `(common-mode, source)` with target $Z$, scope $S$, regime $\Gamma$.

**Distinction.** There is *no* direct edge $E_1 \to E_2$.

**Real-world.** Two news articles both citing the same wire service report. They appear independent but are not, because the wire service is a common ancestor.

## Example 2 — W3 Common Model

Structure:

$$E_1 \leftarrow M \rightarrow E_2$$

Dependency edge: `(common-mode, model)`.

**Real-world.** Two predictions both from the same trained LLM. Even with different prompts, they share the model's training data and biases.

**Interaction with I-E-D05.** Common-model dependency is common-mode, not direct. Changing prompt for $E_1$ does not change $E_2$'s text, but changing the *model* changes both.

## Example 3 — W4 Common Assumption

Structure:

$$E_1 \leftarrow A \rightarrow E_2$$

Dependency edge: `(common-mode, assumption)`.

**Real-world.** Two analyses both assuming "the sample is representative." If that assumption is false, both conclusions may be wrong together.

## Example 4 — W7 Multi-Factor Interaction

Structure:

$$E_1 = f(S, A, T), \quad E_2 = g(S, A, T)$$

with $f(S, A, T) = S \land A \land T$ (all three required).

**Correct dependency object.**

$$Factors = (F_{direct} = \emptyset, F_{interaction} = \{\{S, A, T\}\})$$

**Single-factor detection.** Detects $S$ alone → incomplete. Detects $A$ alone → incomplete. Detects $T$ alone → incomplete.

**Multi-factor detection.** Detects $\{S, A, T\}$ jointly.

**Conclusion.** $Detected(F_1) \not\Rightarrow Detected(AllMaterialFactors)$ (I-E-D07).

**Real-world.** A trading strategy relies on three independent-looking signals; only when all three align does the strategy's performance depend on them.

## Example 5 — Target-relative dependency

**Setup.** Two evidence items $E_1$ and $E_2$ share assumption $A$.

**Target $Z_1$:** "Is the sample size sufficient?" — $A$ is irrelevant.

**Target $Z_2$:** "Is the sample representative?" — $A$ is central.

**Conclusion.**

$$Dep(E_1, E_2 \mid Z_1) \neq Dep(E_1, E_2 \mid Z_2)$$

This is I-E-D01.

## Example 6 — Scope-relative dependency

**Setup.** A model dependency established for German voters.

**Query for Austrian voters.** Scope differs. Cannot transfer.

$$Dep(E_1, E_2 \mid S_{DE}) \not\Rightarrow Dep(E_1, E_2 \mid S_{AT})$$

This is I-E-D02, and it is exactly what R604.7's corrected common-mode detector caught.

## Example 7 — Structure vs mechanism

**Setup.**

- $e_1$: $E_1 \leftarrow S \rightarrow E_2$ (common-mode, source).
- $e_2$: $E_1 \leftarrow M \rightarrow E_2$ (common-mode, model).

Under the naive taxonomy, both are "common-mode." Under the corrected taxonomy, they share `Structure = common-mode` but differ in `Mechanism`. When we later ask "does intervening on the source change the dependency?", $e_1$ and $e_2$ behave differently.

This is I-E-D08.

---

# Part VI — ML Positioning for Dependency

## ML may do

- Propose `CandidateDependency(E₁, E₂, Structure, Mechanism, Factors, Score, Z, S, Γ, Provenance)`.
- Propose `CandidateFactorSet(E₁, E₂, Z, S, Γ)` — a candidate for $F_{direct}$ and $F_{interaction}$.
- Propose `CandidateAdversarialDependency` — near-miss pairs.

Each is a typed candidate.

## ML may not do

- Assert dependency.
- Assert independence.
- Write to X.
- Bypass L4.

## Firewall

```
ML candidate
  → TypeCheck (Structure, Mechanism)
  → ContractCheck
  → RegimeCheck
  → ScopeCheck
  → FactorCheck
  → CounterexampleSearch
  → L4 Verification (against W1–W7 reference)
  → Assessment
```

## Concrete technique for W1–W7

**Features per evidence pair:**
- source overlap (shared Source IDs)
- lineage overlap (shared nodes in Lineage DAG)
- model lineage (shared Model IDs)
- transformation lineage (shared Transformation IDs)
- assumption overlap (shared Assumption IDs)
- citation overlap
- temporal proximity
- graph distance
- semantic similarity (embeddings)

**Model:** Gradient-boosted trees. Justify: tabular, mixed types, interpretable.

**Labels:** W1–W7 ground truth.

**Metrics:**
- **Dependency Precision/Recall** — overall.
- **False Dependency Rate (FDR)** — declared dependency, actual independence.
- **False Independence Rate (FIR)** — the catastrophic error.
- **Common-Mode Recall** — W2.
- **Multi-Factor Recall** — W7.
- **Determination Flip Rate** — how often assessment flips when scope changes.
- **False Robustness Rate** — declaring dependency as stable when it is not.
- **Calibration Error** — reliability diagram, Brier score.
- **Abstention Quality** — does the model abstain when it should?

**Cardinal rules:**

$$\boxed{MLAccuracy \neq Dependency}$$
$$\boxed{PredictiveAssociation \neq LogicalDependency}$$

## Where ML earns its keep

- **Candidate generation** on large graphs.
- **Adversarial search** for near-miss pairs.
- **Calibration** of confidence.
- **OOD detection.**

---

# Part VII — Optimized Architecture (R604.7 baseline)

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
        Composition (8-tuple witness)
        PreservationTarget / Bridge
        Provenance / Lineage (DAG) / CausalHistory
                              │
                     L3 Assessment
       Dependency(Structure, Mechanism, Factors | Z, S, Γ)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     History integrity / Causal traceability
     Dependency verification
                              │
                ML Firewall (boundary contract)
                Type → Contract → Regime → Scope → Factor → Counterexample
                              │
                     L5 Intelligence
       CandidateDependency / CandidateFactorSet / OOD
                              │
                     L6 Governance
                              │
                            Action
```

No L4.5. No new BC. No new Kernel primitive.

## The eleven laws

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

## The methodological rule (now formal)

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

## The self-correction pattern

R604.4 → corrected R604.3's counterexample.
R604.6 → corrected R604.5's externality rule.
R604.7 → corrected its own common-mode detector's scope/regime filter.

This is now the project's **primary quality mechanism**. It should be formalized as a **methodological invariant** in the Theory Specification.

---

# Part VIII — R604.8 Specification

## What R604.8 must do

R604.7 §28 correctly identifies R604.8 as **Multi-Factor Dependency and Intervention Algebra**. But before R604.8 can be executed, four items must be fixed:

### Fixes carried from R604.7

**Fix 1.** Dependency edge carries `(Structure, Mechanism, Factors)` as separate typed fields.

**Fix 2.** `DependencyFactors` typed as $(F_{direct}, F_{interaction})$.

**Fix 3.** `DependencyClosure` split into `StructuralClosure` and `MaterialClosure`.

**Fix 4.** Report discipline: per-test `VerificationResult`, `ExecutionRunID`, printed counterexample, certificate.

### Deliverables

**Q1 — Factor type.** Formalize $(F_{direct}, F_{interaction})$ and its algebra (union, minimization, redundancy).

**Q2 — Minimal dependency set.** Define:

$$MinFactors(E_1, E_2 \mid Z, S, \Gamma)$$

as the set of factor subsets whose joint absence removes the dependency.

**Q3 — Interaction vs conjunction.**
- $F_1 \land F_2$ — conjunctive (each necessary, jointly sufficient).
- $F_1 \lor F_2$ — disjunctive (either sufficient).
- Interaction — no proper subset is sufficient.

Test each on W7.

**Q4 — Intervention algebra (or observational downgrade).**

Either:
- (a) extend the reference calculus with hard interventions $do(E_2 = e)$, or
- (b) explicitly downgrade to observational conditionals and label them as such.

R604.7's counterfactual test in §16 is closer to (b). R604.8 must choose.

**Q5 — Materiality per edge.** Formalize:

$$Material(a \to b \mid Z, S, \Gamma) \iff \text{changing } a \text{ changes } b \text{ for } Z$$

Then define `MaterialClosure` accordingly.

**Q6 — Counterexample certificates.** For each W1–W7 world, produce:
- Expected dependency structure.
- Detected dependency structure.
- Counterexample where detector fails.
- Certificate of assessment.

**Q7 — Full W1–W7 execution with report discipline.**

## What R604.8 must not do

- Do not conflate Structure, Mechanism, and Factors.
- Do not adopt the counterfactual test without clarifying intervention semantics.
- Do not introduce a new BC.
- Do not introduce a new Kernel primitive.
- Do not allow ML to enter the authority path.
- Do not claim universal theorems from W1–W7 results.

## Sequencing

- **R604.8** — Multi-factor dependency + intervention algebra + W1–W7 full execution with report.
- **R604.9** — Full invariant catalogue (~47 + new I-E-D01..D08).
- **R604.10** — Terminology freeze.
- **R604.11** — Theory Specification v1.0.

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
- **Dependency:** first executable formulation, target/scope/regime-relative.
- **W1–W7:** structurally represented.
- **ML firewall:** preserved.
- **Self-falsification:** three consecutive rounds.
- **Report discipline:** still pending.
- **Overall:** the theory is now a fully executable, self-correcting, falsifiable epistemic calculus.

## Remaining TODOs (short)

- **R604.8** — Multi-factor dependency + intervention algebra + W1–W7 full execution with report discipline.
- **R604.9** — Full invariant catalogue.
- **R604.10** — Terminology freeze.
- **R604.11** — Theory Specification v1.0.
- **Standing** — no new BC, no new Kernel primitive, no universal theorems from finite tests, use weakest sufficient method, `Structure ≠ Mechanism ≠ Factors`, each round must attempt to falsify the previous.

## The single most important thing to do next

**Fix the four items from R604.7 (structure/mechanism/factors typing, intervention clarification, closure split, report discipline) before executing W1–W7 fully.** The W7 world cannot be correctly handled without the typed factor set. And the report discipline must be enforced; otherwise the "execution evidence" claim remains weaker than the document suggests.

$$\boxed{\text{Type the factors. Fix the report. Then execute W7.}}$$

## Two open questions I flag

1. **Intervention semantics.** R604.8 must either build hard interventions or explicitly downgrade to observational conditionals. Both are legitimate, but the choice affects every subsequent dependency claim.

2. **Materiality per edge.** Does materiality depend on the regime? On the scope? On the target? If yes to all three, then `Material` is itself a 4-place predicate, and `MaterialClosure` becomes target/scope/regime-relative. This is probably the correct answer, but R604.8 must confirm it.

If you want to proceed, tell me:

- **(A)** R604.8 specification (fixes, factor algebra, intervention choice, W7 test, report format).
- **(B)** R604.8 code.
- **(C)** Both, in order.

My recommendation is **(A) then (B)**, with **Q1–Q3 first** — because the W7 world cannot be correctly handled without the typed factor set.