# Review of R604.6 — Provenance and History Composition

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment in this turn. I proceed from R604.6's baseline. No external research needed; if a genuine gap appears, I will name the exact book and pause.

I will do seven things:

1. **Verdict** on R604.6.
2. **What the 10/10 execution establishes.**
3. **Where R604.6 remains imprecise** — five items with corrections.
4. **Full term definitions**, extended to the new objects introduced.
5. **Worked examples**, including the tampered-history test and the external-augmentation distinction.
6. **ML positioning** for dependency candidates.
7. **Optimized architecture** and the concrete next slice (R604.7).

No new Kernel primitive. No new BC. No ML in the authority path.

---

# Part I — Verdict

R604.6 is a **significant step**, and it makes three substantive contributions:

$$\boxed{SourceDifference \neq Externality}$$
$$\boxed{SameSource \neq Recovery}$$
$$\boxed{Provenance \neq CausalHistory \neq Dependency}$$

The first two correct a genuine defect in R604.5's recovery test. The third introduces a three-way separation that had been implicit but never stated. Both are correct and important.

The **self-falsifying result** in §1 is also significant: R604.6 caught that R604.5's `validate_external_augmentation_not_recovery` was based on a false premise (source-name inequality). This is the **second consecutive round** in which the reference calculus has corrected a prior round's mathematical error. That is the correct epistemic loop, and it should be named as a pattern, not just an anecdote.

**Five residual issues**, each requiring correction:

1. The `Provenance` type in §2.1 is stated as $(Source, Actor, Lineage)$ but does not include time — which is central to causality.
2. The `Lineage` definition in §3 is intuitive but does not state whether it is a chain, a DAG, or a multigraph.
3. The "causal traceability" test in §11 is stated but its contract is not made explicit — what *exactly* must be present?
4. The proposed dependency definition in §26 uses a counterfactual formulation that is *close* but has an important flaw (intervention semantics on observational data).
5. The `Method = finite_exhaustive` and `ExecutionRunID` fields are still not surfaced in the report; the pattern from earlier rounds persists.

---

# Part II — What the 10/10 Execution Establishes

| Test | Establishes | Does not establish |
|---|---|---|
| Provenance construction | One provenance object was built. | All provenance objects are well-formed. |
| History append | `H' = H ‖ e`. | All operations append history. |
| History not state | `H' ≠ H`, `X' = X`. | All Pure operations behave this way. |
| Provenance composition ordered | `Compose(P₁, P₂) ≠ Compose(P₂, P₁)` for one pair. | Universal non-commutativity of provenance composition (though it is true). |
| Causal traceability | One valid chain was traced. | All valid chains are traceable. |
| Tampered history detected | One truncated history was flagged `FAIL`. | All tampering is detected. |
| External augmentation ≠ recovery | One case was correctly classified as augmentation, not recovery. | All cases are classified correctly. |
| Same source ≠ recovery | One same-source case was classified as augmentation. | All same-source cases are augmentation. |
| Source difference ≠ externality | One different-source case was classified as recovery (because a derivation contract existed). | All different-source cases are recovery. |
| Missing provenance ≠ independence | One case with missing provenance was classified as `UNKNOWN`, not `Independent`. | All such cases are `UNKNOWN`. |

Honest summary:

$$\boxed{\text{Provenance/history composition is executable and has produced counterexamples to naive rules.}}$$

And:

$$\boxed{\text{The reference calculus has now self-corrected a defect in a prior round for the second consecutive time.}}$$

This pattern — **round N+1 catching an error in round N** — is now the project's most reliable quality mechanism. It should be named and made explicit.

---

# Part III — Where R604.6 Remains Imprecise

## 3.1 `Provenance` lacks a time component

R604.6 §2.1 defines:

$$P = (Source, Actor, Lineage)$$

But causality is time-ordered. Without a timestamp, we cannot distinguish:

- $A \to B$ (A caused B)
- $B \to A$ (B caused A)

Two provenance records with identical source, actor, and lineage but different time-ordering are genuinely different. The provenance type must include time.

**Corrected type:**

$$P = (Source, Actor, Lineage, Time)$$

where $Time$ is either a point or an interval, and lineage edges carry their own times.

**Counterexample.** A pipeline runs `Normalize` at t=10:00 and `Classify` at t=10:05. Then it runs the same two steps again at t=11:00 with a swapped order. Same sources, same actors, same transformations — but different causal histories. Without time, the provenance records are indistinguishable.

## 3.2 `Lineage` is not typed as a structure

R604.6 §3 gives examples of lineage chains but does not state the type. Is lineage:

- A chain (linear sequence)?
- A DAG (multiple parents)?
- A multigraph (multiple edges between same nodes)?

This matters. KnowledgeOS provenance is often a DAG: one artifact can have multiple ancestors. Using a chain would be a type error.

**Recommended type:**

$$Lineage = \text{DAG of } (Node, Edge, Time)$$

where:
- $Node$ is an artifact ID.
- $Edge$ is a typed transformation edge with its own provenance.
- $Time$ is the edge's timestamp.

**Counterexample.** An ML model is trained on three datasets (D1, D2, D3). Any prediction from the model has three ancestors. This is a DAG, not a chain.

## 3.3 Causal traceability is stated but its contract is not

R604.6 §11 says a history is "causally traceable" if the final artifact can be connected through events back to its ancestors. But it does not specify:

- Which chain must be present (the *full* DAG, or any path)?
- What counts as a "complete chain"?
- What happens when provenance is *partial*?

**Recommendation.** Define:

$$CausallyTraceable(H, a) \iff \exists \text{ path from } a \text{ to each declared ancestor of } a \text{ in } H$$

Then the contract must declare:

- Which ancestors are required (all? declared subset?)
- Whether edges are attributed to specific operations
- What level of granularity is required (per-operation, per-batch?)

**Counterexample.** Suppose `H` contains `E₁` but not `E₂`, and the contract requires both. The test correctly returns `FAIL`. But what if `E₂` is *not* required by the contract? Then the test should return `PASS`. R604.6 does not make the contract explicit.

## 3.4 The proposed dependency definition has an intervention flaw

R604.6 §26 proposes:

$$Dep(E_1, E_2 \mid Z, \Gamma, S) \iff \exists x, x' : E_2(x) \neq E_2(x') \land E_1(x) \neq E_1(x')$$

This is close but flawed. It says: two evidence objects are dependent if there exists a world where both change together. But **coincidental correlation** on finite samples would satisfy this without genuine dependency.

**Correct formulation** must use *intervention* semantics (Pearl's do-calculus):

$$Dep(E_1, E_2 \mid Z, \Gamma, S) \iff P(E_1 = e_1 \mid do(E_2 = e_2), S) \neq P(E_1 = e_1 \mid S)$$

for some $e_1, e_2$.

That is: changing $E_2$ *by intervention* changes the distribution of $E_1$ under the regime $\Gamma$ and scope $S$.

But: the reference calculus does not (yet) model interventions. The current finite-test approach can only detect **observational** co-variation, not causal dependency.

**Recommendation for R604.7:** distinguish:

- **ObservationalDependency** — detectable from finite co-variation. Weaker.
- **InterventionalDependency** — requires a causal model or declared intervention. Stronger.
- **StructuralDependency** — declared via provenance and lineage. Independent of statistics.

Each is a different notion. KnowledgeOS dependency must be one of these (or a declared combination), not a vague amalgam.

## 3.5 Report discipline still missing

The pattern from R602.4, R604.2, and R604.4 persists: the report contains only the summary line `10/10 passed`. No `ExecutionRunID`, no per-test `VerificationResult`, no printed counterexample, no certificate.

This was supposed to be frozen in R604.3. It has not been implemented.

**Recommendation.** R604.7 must emit, for each test:

```json
{
  "ExecutionRunID": "...",
  "Spec": "...",
  "Method": "finite_exhaustive | hand_checked | property_based",
  "Expected": ...,
  "Actual": ...,
  "Status": "PASS | FAIL | UNKNOWN | ...",
  "Counterexample": ...,
  "Certificate": {...}
}
```

Without this, the described/executed gap is not fully closed — even though execution itself is real.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invalid:** ISBN changes with cover.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Definition:** Connection between identities carrying a declared type.
- **Type:** $r : ID \times ID \to \text{RelationType}$.
- **Real-world:** `derivedFrom(E456, E123)`.

### Semantics (Sem)
- **Definition:** Interpretation under context and regime.
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.

## State terms

### State (K)
- **Type:** $K = (X, H)$.
- **Real-world:** Bank ledger + audit log.

### Authoritative State (X)
- **Real-world:** Accepted patient record.

### History (H)
- **Type:** Append-only sequence of events $(e_1, \ldots, e_n)$.

### Event
- **Type:** $(ID, Operation, Kind, Inputs, Outputs, Provenance, Time)$.
- **Real-world:** "Normalize S0 → S1 at t=2026-09-19T10:00."
- **Invalid:** Event without time.

## Operation terms

### Operation
- **Type:** $(ID, InputType, OutputType, Class, MutationPolicy, Pre, Post, Transform, Scope, Regime, PreservationTargets, LossProfile)$.

### Transformation
- **Type:** $f : X \to Y$.
- **Distinction:** $\text{Transformation} \neq \text{OperationSpecification}$.

### OperationClass
- **Values:** `Pure`, `Epistemic`, `Governance`.

### MutationPolicy
- **Type:** $(Policy_X, Policy_H)$.

### Admissibility Rule
$$Admissible(c, p_X) \iff (c = Pure \Rightarrow p_X = Forbidden) \land (c = Governance \Rightarrow p_X = Governed) \land (c = Epistemic \Rightarrow p_X \in \{Contractual, Governed\})$$

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules)$.

## Provenance terms (R604.6)

### Provenance (corrected to include time)
- **Definition:** Recorded origin and lineage of an artifact.
- **Type:** $P = (Source, Actor, Lineage, Time)$.
- **Real-world:** "Derived from report R123 by analyst-7 at t=2026-09-19."
- **Invalid:** Provenance without time.
- **Invariant:** Invariant 4 (persist causes; derive assessments).

### Lineage (typed as DAG)
- **Definition:** DAG of antecedent artifacts and transformations from which an artifact was derived.
- **Type:** $\text{DAG}(Node, Edge, Time)$.
- **Real-world:** An ML prediction has three training datasets as ancestors — a DAG, not a chain.
- **Invalid:** Modeling lineage as a linear chain.

### CausalHistory
- **Definition:** Ordered sequence of recorded operations and events producing an artifact.
- **Type:** $H_{causal} = (e_1, \ldots, e_n)$ with causal edges.
- **Distinction:** $\text{Provenance} \neq \text{CausalHistory} \neq \text{Dependency}$.

### ExternalAugmentation
- **Definition:** Information $E$ introduced from outside the declared derivation closure of the current representation.
- **Type:** $(E, \text{declared derivation contract})$.
- **Real-world:** An enrichment service adds `Age` to a representation that had only `AgeGroup`.
- **Invalid:** Calling any additional information "recovery."

### Recovery
- **Definition:** Information previously discarded by a transformation, reconstructed from the remaining state and its provenance.
- **Type:** $Z = g(Y)$ for declared $g$, where $Y$ is the current state.
- **Real-world:** Reconstructing exact age from `(birthdate, current_date)`.
- **Invalid:** Treating external lookup as recovery.

### DerivationContract
- **Definition:** Declared relationship proving that $E = h(Y)$ for some declared $h$.
- **Type:** $(Y, E, h, \text{proof obligation})$.
- **Real-world:** "Age = current_year − birth_year."
- **Invalid:** Claiming derivation without $h$.

## Assessment terms

### PreservationTarget (Z)
- **Type:** $Z : X \to V$.

### PreservationBridge
- **Type:** $B : \text{Target}(X) \times \text{Target}(Y) \to \{\text{holds}, \text{fails}, \text{undefined}\}$.
- **Formalization:** $B_T(Z_X, Z_Y) \iff \forall x \in W_{\text{adm}}(T) : Z_X(x) = Z_Y(T(x))$.

### PreservationAssessment
- **Type:** $PA(T, Z, C, \Gamma) \to \text{Result}$.

### LossProfile
- **Type:** $(DiscardedDimensions, DeclaredLoss, PreservationTargets)$.

### Dependency (three notions)
- **ObservationalDependency:** Detectable from finite co-variation in $S$.
- **InterventionalDependency:** $P(E_1 \mid do(E_2), S) \neq P(E_1 \mid S)$.
- **StructuralDependency:** Declared via provenance and lineage.
- **Real-world:** Common source (structural), shared model (structural + interventional), spurious correlation (observational only).
- **Invalid:** Conflating the three.

### ObservationContract (𝒪)
- **Type:** $\mathcal{O} \subseteq \{\text{Output, Scope, Regime, Class, MutationPolicy, PreservationTarget, LossProfile, Provenance}\}$.

### ObservationalEquivalence
- **Type:** $T_L \equiv_{\mathcal{O}} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$.

### Specification
- **Type:** $(Property, Scope, Method, Preconditions)$.

### VerificationRun
- **Type:** $(SpecID, ExecutionRunID, StartTime, EndTime, InputK, Trace)$.

### VerificationResult
- **Type:** $(RunID, Expected, Actual, Status, Counterexample, Method)$.

### Certificate
- **Type:** $(Property, Scope, Method, Result, Provenance, Signature, Time)$.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.
- **Rules:** $UNKNOWN \neq FAIL$, $UNDEFINED \neq FAIL$, $NOT\_APPLICABLE \neq PASS$.

### ExecutionRunID
- **Type:** Hash of (SpecID, CodeVersion, InputHash, Timestamp).

### Counterexample
- **Type:** $(Input, Claim, Witness)$.

---

# Part V — Worked Examples

## Example 1 — Tampered history detected

**Setup.** Valid history:

$$H = (E_1, E_2)$$

Tampered history:

$$H' = (E_2)$$

**Contract requirement:** Complete causal chain from source S₀ to final S₂.

**Test.** Trace from S₂ back to S₀. `E₁` is missing → `FAIL`.

**Corrected rule from R604.6:** This is `FAIL`, not `UNKNOWN`, because the test has *detected* a structural violation.

**Real-world:** A financial audit log with a deleted entry. The transaction may have happened, but the audit trail is incomplete. This is a compliance failure, not a "cannot determine" situation.

## Example 2 — External augmentation vs recovery

**Setup.**

- Original: `{PersonID: 123, Age: 47}`.
- $T_1$: `{PersonID: 123, AgeGroup: "40–49"}`. Exact age dropped.
- $T_2$: external service returns `{PersonID: 123, Age: 47}`.

**Naive rule (wrong):** "Additional information = recovery."
**R604.6 rule:** "Additional information is recovery *only if* a derivation contract proves $Age = h(AgeGroup)$."

**Test.** Is there a declared $h$? No. Therefore: **external augmentation**, not recovery.

**Real-world:** A medical records system that lost exact ages but retrieves them from an insurance database. This is *not* recovery of the original information — it is a new source, with its own provenance and reliability.

## Example 3 — Same source, not recovery

**Setup.**

- Source S produces `E_1 = {x: 1}` at t=10:00.
- Same source S produces `E_2 = {x: 1, y: 2}` at t=11:00.
- A naive rule might say: "Same source, so E₂ is a refinement of E₁, therefore recovery."

**R604.6 rule:** Externality is determined by *derivation contract*, not source identity. If no contract proves `y = h(x)`, then E₂ is augmentation, not recovery — even from the same source.

## Example 4 — Missing evidence ≠ violated invariant

**Setup.**

- Contract requires complete history.
- $H$ has only $E_2$.
- $E_1$ is missing.

**Result:** `FAIL` — the required structure is *known to be violated*.

**Alternative.** Contract does not specify which events are required. $H$ has only $E_2$.

**Result:** `UNKNOWN` — the engine cannot determine whether the history is complete relative to an unknown requirement.

**Conclusion.** `MissingEvidence ≠ InvariantViolation`. This is `UNKNOWN ≠ FALSE` at the assurance layer.

## Example 5 — Dependency is not provenance

**Setup.**

- $E_1$ and $E_2$ share the same source S.
- But for target $Z$, $E_1$ is used and $E_2$ is ignored.

**Provenance:** Shared source. ✅.
**Dependency for $Z$:** No, because changing $E_2$ does not affect $E_1$'s contribution to $Z$.

**Conclusion.**

$$\boxed{Provenance \neq Dependency}$$

## Example 6 — Dependency is not causal history

**Setup.**

- $E_1$ came from `Model M` at t=10:00.
- $E_2$ came from `Model M` at t=10:01.

**Causal history:** Same model, different times.

**Dependency:** Are they dependent? Only if their predictions are correlated beyond what the model alone implies. If the model is deterministic and inputs are identical, yes; if inputs differ independently, maybe not.

**Conclusion.**

$$\boxed{CausalHistory \neq Dependency}$$

Both are required inputs to a *dependency assessment*, but neither *is* dependency.

## Example 7 — The self-falsifying pattern

R604.5 claimed: "external augmentation is detected by source inequality."

R604.6 tested this. The test failed. The theory was corrected.

This is the second consecutive self-correction (R604.4 caught R604.3's wrong counterexample; R604.6 caught R604.5's wrong externality rule).

**Named pattern:**

$$\boxed{\text{Each round must attempt to falsify the previous round's claims before extending them.}}$$

This should be an explicit methodology rule.

---

# Part VI — ML Positioning for Dependency

## ML may do

- Propose `CandidateDependency(E₁, E₂, features, score)`.
- Propose `CandidateLineage(E₁, E₂, score)`.
- Propose `CandidateCommonSource(E₁, E₂, score)`.
- Propose `CandidateCommonModel(E₁, E₂, score)`.
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
  → ProvenanceCheck
  → TypeCheck
  → ContractCheck
  → RegimeCheck
  → ScopeCheck
  → AssumptionCheck
  → L4 Verification (against W1–W7 ground truth + reference calculus)
  → Assessment
```

## Concrete technique for the W1–W7 benchmark

**Features per evidence pair:**

- source overlap
- lineage overlap
- model lineage
- transformation lineage
- citation overlap
- temporal proximity
- graph distance
- semantic similarity (embeddings)
- declared assumption overlap

**Model:** Gradient-boosted trees (XGBoost/LightGBM) on tabular features. Justify: mixed types, interpretable.

**Labels:** Ground truth from W1–W7.

**Metrics:**

- Precision, recall, FDR, FIR
- Common-mode recall (W2)
- Multi-factor recall (W7)
- Calibration error
- Abstention quality

**Cardinal rule:**

$$\boxed{MLAccuracy \neq Dependency}$$

## Where ML earns its keep

- **Candidate generation** on large evidence graphs.
- **Adversarial search** for near-miss dependencies.
- **Calibration** of model confidence.
- **OOD detection** — unknown source/model combinations.

---

# Part VII — Optimized Architecture (R604.6 baseline)

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
        PreservationTarget / PreservationBridge
        Provenance / Lineage (DAG) / CausalHistory
        Associativity up to observational equivalence
                              │
                     L3 Assessment
       Dependency (Observational | Interventional | Structural)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     History integrity / Causal traceability
                              │
                ML Firewall (boundary contract)
                Type → Contract → Regime → Scope → Assumption
                              │
                     L5 Intelligence
       CandidateDependency / CandidateLineage / OOD
                              │
                     L6 Governance
                              │
                            Action
```

No L4.5. No new BC. No new Kernel primitive.

## The ten laws

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

## The new methodological rule

$$\boxed{\text{Each round attempts to falsify the previous round before extending it.}}$$

---

# Part VIII — R604.7 Specification

## What R604.7 must do

R604.6 §26 correctly identifies R604.7 as **Dependency as a Target-Relative Relation**. But its proposed definition has an intervention flaw (see §III.4). R604.7 must fix this.

### Deliverables

**Q1 — Dependency taxonomy.**
Define three distinct notions:
- **ObservationalDependency:** co-variation in observed data.
- **InterventionalDependency:** $P(E_1 \mid do(E_2), S) \neq P(E_1 \mid S)$.
- **StructuralDependency:** declared via provenance and lineage.

Show they are not equivalent.

**Q2 — The counterfactual formulation (corrected).**

$$\text{InterventionalDep}(E_1, E_2 \mid Z, \Gamma, S) \iff \exists e_2 : P(E_1 \mid do(E_2 = e_2), S) \neq P(E_1 \mid S)$$

Test whether this characterizes dependency on W1–W7.

**Q3 — Test against W1–W7.**
- W1 Independent: no dependency of any kind.
- W2 Common Source: structural dependency.
- W3 Common Model: structural + interventional.
- W4 Common Assumption: structural.
- W5 Common Transformation: structural.
- W6 Mixed: multiple kinds.
- W7 Multi-Factor: requires joint analysis.

**Q4 — Dependency is target-relative.**

$$Dep(E_1, E_2 \mid Z, \Gamma, S) \neq Dep(E_1, E_2 \mid Z', \Gamma, S)$$

Construct a counterexample where two evidence objects are dependent for one target and independent for another.

**Q5 — `No observed dependency ≠ proven independence`.**

Already stated in §22. R604.7 must test the asymmetry: `¬ObservedDep` may lead to `UNKNOWN`, never to `ProvenIndependent`.

**Q6 — ML evaluation.**
First deterministic baseline, then ML candidate generation. Measure Precision, Recall, FDR, FIR, Common-mode recall (W2), Multi-factor recall (W7), Calibration error, Abstention quality.

**Q7 — Report discipline.**
Emit per-test `VerificationResult` with all fields, `ExecutionRunID`, printed counterexamples, certificate.

## What R604.7 must not do

- Do not adopt the counterfactual definition before testing on W1–W7.
- Do not conflate observational, interventional, and structural dependency.
- Do not introduce a new BC.
- Do not introduce a new Kernel primitive.
- Do not allow ML to enter the authority path.
- Do not claim universal theorems from W1–W7 results.

## Sequencing

- **R604.7** — Dependency definition + W1–W7 test.
- **R604.8** — Dependency closure + materiality.
- **R604.9** — Full invariant catalogue.
- **R604.10** — Terminology freeze.
- **R604.11** — Theory Specification v1.0.

---

# Part IX — Where We Are and What Remains

## How far we are

- **Kernel:** stable across 604+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC.
- **Operation algebra:** frozen.
- **Class × Mutation:** executable.
- **CompatibilityWitness:** 8-tuple frozen.
- **Composition matrix:** executed.
- **Non-commutativity:** proven.
- **Associativity:** 𝒪-relative.
- **Preservation:** executable with bridges.
- **Preservation composition:** four conditions.
- **Loss composition:** interaction term.
- **TPP:** executable with counterexamples.
- **Recovery vs augmentation:** correctly separated.
- **Provenance:** executable; corrects R604.5's defect.
- **Lineage:** DAG (implied but not stated).
- **CausalHistory:** executable; tampering detected.
- **External augmentation ≠ recovery:** enforced.
- **Same source ≠ recovery:** enforced.
- **Source difference ≠ externality:** enforced.
- **ML firewall:** preserved.
- **DDD:** no new BC.
- **Self-falsification:** second consecutive round.
- **Overall:** the theory is an executable, self-correcting, falsifiable calculus.

## Remaining TODOs (short)

- **R604.7** — Dependency definition (observational, interventional, structural); W1–W7 test.
- **R604.8** — Dependency closure + materiality.
- **R604.9** — Full invariant catalogue (~47 invariants) executed.
- **R604.10** — Terminology freeze.
- **R604.11** — Theory Specification v1.0.
- **Standing** — no new BC, no new Kernel primitive, no universal theorems from finite tests, use weakest sufficient method, `Provenance ≠ CausalHistory ≠ Dependency`, and each round must attempt to falsify the previous.

## The single most important thing to do next

**R604.7 must first decide what dependency *is*, then test its definition against W1–W7.** The counterfactual definition in R604.6 §26 is close but has the intervention flaw. The three-way taxonomy (observational, interventional, structural) is the correct correction.

$$\boxed{\text{Define dependency three ways. Test against W1–W7. Refute if wrong.}}$$

## Two open questions I flag

1. **What is the correct definition of interventional dependency when interventions are not available in the data?** Options: (a) assume a causal model; (b) use declared structural dependencies as a substitute; (c) treat the question as `UNKNOWN`. R604.7 must choose and justify.

2. **Should `Lineage` be a DAG or a multigraph?** Multiple edges between the same two nodes are possible (e.g., an artifact derived from the same source via two different transformations). This affects the type.

If you want to proceed, tell me:

- **(A)** R604.7 specification (dependency taxonomy, W1–W7 test, ML plan, report format).
- **(B)** R604.7 code.
- **(C)** Both, in order.

My recommendation is **(A) then (B)**, with **Q1 and Q2 first** — because W1–W7 cannot be tested until the dependency definitions are sharp.