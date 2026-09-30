# Review of R604.2 — Composition and Associativity

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

I will do seven things:

1. **Verdict** on R604.2.
2. **What the 10/10 execution actually establishes**, honestly scoped.
3. **Where R604.2 remains imprecise** — five items, each with a counterexample or correction.
4. **Full term-by-term definitions**, one at a time, with real-world and invalid examples.
5. **Worked examples** that prove or refute the key claims.
6. **ML positioning** for composition.
7. **Optimized architecture** and the concrete next slice (R604.3).

No new Kernel primitive. No new BC. No ML in the authority path.

---

# Part I — Verdict

R604.2 is a **correct and disciplined step**, and it makes one genuinely important architectural contribution:

$$\boxed{\text{Associativity is not one theorem. It is three: functional, transformation, specification.}}$$

Earlier rounds used "associativity" as though it were a single property. R604.2 correctly decomposes it into three levels and correctly introduces **observational equivalence** as the right notion of specification-level equality. This is the substantive contribution.

The **10/10 executable result** is real execution evidence. It is the second such round after R602.4. The described/executed gap remains closed for this document.

**But there are five residual issues**, one of which is significant: the mutation-class dominance rule `Governance > Epistemic > Pure` is used in code and *labeled* as unfrozen — which is honest — but the document also *relies on it* to justify some tests. That reliance is not yet warranted.

Also, and this is important: the document says "I created and actually executed" the code, and the output is a single summary line `10/10 passed`. As with R602.4, **no `ExecutionRunID`, no trace, no counterexample, no certificate** is surfaced. The infrastructure for these exists in R602.4b.1, but the R604.2 report does not include it. This must be fixed in R604.3.

---

# Part II — What the 10/10 Actually Establishes

| Test | Establishes | Does not establish |
|---|---|---|
| Function associativity | `h∘(g∘f) = (h∘g)∘f` for the specific `f, g, h` on `{-10,…,10}`. | Universal associativity of all functions. |
| Typed composition | One typed composition with witness executed. | All typed compositions succeed. |
| Specification composition | Two specification compositions produced outputs. | They agree with strict equality. |
| Rejection of incompatible witnesses | One witness was rejected. | All incompatible witnesses are rejected. |
| Rejection of regime mismatch | One regime mismatch was rejected. | All regime mismatches are rejected. |
| Rejection of scope mismatch | One scope mismatch was rejected. | All scope mismatches are rejected. |
| Pure → X immutability | One Pure operation preserved X. | All Pure operations preserve X. |
| Mutation-class behavior | The class of one composition was computed. | The dominance rule is correct. |
| Loss-profile propagation | Loss was treated as a declared summary. | Loss composition has any specific algebraic law. |
| Observational equivalence | Two differently grouped compositions were observationally equivalent under one declared contract. | They are observationally equivalent under all contracts. |

The honest summary:

$$\boxed{\text{The composition machinery is executable and internally consistent for the tested cases.}}$$

This is a real result. It is not a universal theorem.

---

# Part III — Where R604.2 Remains Imprecise

## 3.1 The mutation-class dominance rule is used but not justified

R604.2 §9 correctly states:

> "The prototype currently uses the conservative dominance: Governance > Epistemic > Pure for the resulting class. But this is currently an implementation rule, not yet a mathematical theorem of KnowledgeOS."

Good. But §17 then lists the rule as the "Candidate result" for all nine combinations. That does more work than its status permits.

**Correction.** Rename the column to `current implementation output` and add a column `expected by principle` to be filled in R604.3.

**Counterexample that matters.** Consider:

- $T_1$: `Epistemic (Declared)` — adds `X.new_fact = "e"`.
- $T_2$: `Governance (Governed)` — sets `X.rule = "r"` based on facts, but does *not* inspect `X.new_fact`.

Under dominance: $T_2 \circ T_1$ is `Governance`.

But $T_1$'s effect on X is *not consumed* by $T_2$. The composition does two things — adds a fact and applies a governance rule. Whether the composition is `Governance` or `Epistemic-with-Governance-overlay` depends on how "class" is interpreted. The dominance rule hides this ambiguity.

## 3.2 CompatibilityWitness is still 7-tuple

R604.2 §2.3 gives:

$$w = (src, tgt, conv, pre, Z_w, Loss_w, \Gamma_w)$$

This is the 7-tuple form. Earlier reviews established that `pre` conflates two conditions:

- $pre_{conv}$ — when the conversion function is defined.
- $pre_{comp}$ — when the composition as a whole is admissible.

**Counterexample.** Let $T_1 : \text{DegreeCelsius} \to \text{DegreeFahrenheit}$ and $T_2 : \text{Kelvin} \to \text{Joule}$. The conversion $conv : \text{Fahrenheit} \rightharpoonup \text{Kelvin}$ is total on ℝ. But the composition is nonsense because the *physical regime* is different. `pre_conv` alone cannot decide this.

The 8-tuple is required:

$$w = (src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$$

This must be corrected in R604.3.

## 3.3 Observational equivalence is defined but not typed

R604.2 §7 introduces:

$$T_L \equiv_\mathcal{O} T_R$$

with $\mathcal{O}$ an "observation contract." But the document does not state:

- Is $\mathcal{O}$ a subset of the fields of $T$?
- Is it a projection?
- Does $\equiv_\mathcal{O}$ form an equivalence relation?

**Recommendation.**

$$\mathcal{O} \subseteq \{\text{Output, Scope, Regime, Class, MutationPolicy, PreservationTarget, LossProfile, Provenance}\}$$

$$T_L \equiv_\mathcal{O} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$$

Then $\equiv_\mathcal{O}$ is automatically reflexive, symmetric, transitive.

**Counterexample showing $\mathcal{O}$ matters.** Let $\mathcal{O} = \{\text{Output}\}$. Then any two operations with the same output are equivalent. Too weak. Let $\mathcal{O} = \{\text{Output, Scope}\}$. Now scope mismatch breaks equivalence. The content of $\mathcal{O}$ matters; R604.2 acknowledges this but does not formalize it.

## 3.4 Non-commutativity is stated but not proven at KnowledgeOS level

R604.2 §17 says:

> "In general $T_2 \circ T_1 \neq T_1 \circ T_2$, and non-commutativity is not an error."

This is correct. But the document only *proves* it for raw functions. The KnowledgeOS-level non-commutativity is stronger and less obvious.

**Proof for KnowledgeOS.**

Let $T_1 : \text{Epistemic (Revise X)}$ — sets `X.approved = True`.
Let $T_2 : \text{Governance (Decide based on X)}$ — outputs `Decision = "permit"` iff `X.approved = True`.

- $T_2 \circ T_1$: Revise sets `approved = True`, Decide reads it and outputs `permit`.
- $T_1 \circ T_2$: Decide reads original `approved = False`, outputs `deny`; then Revise sets `approved = True`.

$$T_2 \circ T_1 \neq T_1 \circ T_2$$

This is KnowledgeOS-level non-commutativity from *semantic dependency*, not merely numerical non-commutativity. It should be stated as a theorem in R604.3.

## 3.5 "Loss composition is target- and provenance-dependent" is under-specified

R604.2 §11 correctly rejects:

$$Loss(T_2 \circ T_1) = Loss(T_1) \cup Loss(T_2)$$

but does not state when the union *does* hold.

**Correct refinement:**

$$L(T_2 \circ T_1) = L_1 \cup L_2 \cup L_{\text{interaction}}(T_1, T_2)$$

where $L_{\text{interaction}}$ is empty iff $T_2$'s behavior does not depend on distinctions $T_1$ has erased.

**Counterexample.** Let $T_1$ project $\{name, zip\} \to \{zip\}$, dropping `name`. Let $T_2$ map `{zip} → {region}` collapsing zips.

- $L_1 = \{name\}$
- $L_2 = \emptyset$ as declared
- $L_1 \cup L_2 = \{name\}$
- $L(T_2 \circ T_1) = \{name, zip\text{-distinction}\}$

Interaction term = $\{zip\text{-distinction}\}$. R604.2 should state this.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant touched.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact, invariant under representation change.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invalid:** ISBN changes when cover changes.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Definition:** Connection between identities carrying a declared type.
- **Type:** $r : ID \times ID \to \text{RelationType}$.
- **Real-world:** `derivedFrom(E456, E123)`.
- **Invalid:** `relatedTo` without type.

### Semantics (Sem)
- **Definition:** Interpretation under declared context and regime.
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.
- **Real-world:** "bank" in financial vs geographic context.
- **Invalid:** Meaning without context.

## State terms

### State (K)
- **Definition:** Authoritative representation plus immutable history.
- **Type:** $K = (X, H)$.
- **Real-world:** Bank ledger plus audit log.
- **Invalid:** Silent mutation of X.

### Authoritative State (X)
- **Definition:** What KnowledgeOS currently treats as authoritative.
- **Type:** Set of facts, evidence, contexts, contracts.
- **Real-world:** Accepted patient record.

### History (H)
- **Definition:** Append-only event record.
- **Type:** $(e_1, \ldots, e_n)$.
- **Real-world:** Git commit log.

## Operation terms

### Operation
- **Definition:** Contracted state transformation.
- **Type:** $(ID, InputType, OutputType, Class, MutationPolicy, Pre, Post, Transform, Scope, Regime, PreservationTargets, LossProfile)$.

### Transformation
- **Definition:** The mathematical function performed by an operation.
- **Type:** $f : X \to Y$.
- **Distinction:** $\text{Transformation} \neq \text{OperationSpecification}$.

### OperationClass
- **Definition:** What kind of semantic activity.
- **Values:** `Pure`, `Epistemic`, `Governance`.

### MutationPolicy
- **Definition:** Two-channel policy for X and H.
- **Type:** $(Policy_X, Policy_H)$.

### Admissibility Rule
$$Admissible(c, p_X) \iff (c = Pure \Rightarrow p_X = Forbidden) \land (c = Governance \Rightarrow p_X = Governed) \land (c = Epistemic \Rightarrow p_X \in \{Contractual, Governed\})$$

### CompatibilityWitness (8-tuple, corrected)
- **Definition:** Declared conversion with declared preconditions.
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.
- **Real-world:** Meter-to-centimeter with metric-regime precondition.
- **Invalid:** Treating witness as total function.

### Scope
- **Definition:** Domain in which a claim is valid.
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Definition:** Formal interpretive framework.
- **Type:** $(Axioms, Semantics, Rules)$.

### Provenance (P)
- **Definition:** Origin and lineage of an artifact.
- **Type:** $(Source, Lineage, Time, Actor)$.

### LossProfile
- **Definition:** What a transformation discards.
- **Type:** $(DiscardedDimensions, DeclaredLoss, PreservationTargets)$.

### PreservationTarget (Z)
- **Definition:** Property required to remain invariant.
- **Type:** $Z : W \to \text{TargetValue}$.

### PreservationAssessment
- **Definition:** Derived evaluation of whether a target survives.
- **Type:** $PA(T, Z, C, \Gamma) \to \text{Result}$.

### ObservationContract (𝒪)
- **Definition:** Declared finite subset of specification fields considered observable.
- **Type:** $\mathcal{O} \subseteq \{\text{Output, Scope, Regime, Class, MutationPolicy, PreservationTarget, LossProfile, Provenance}\}$.
- **Real-world:** "Two reports are the same if their conclusions and scopes match."
- **Invalid:** Claiming observational equivalence without declaring 𝒪.

### ObservationalEquivalence
- **Definition:** Two specifications agree on all 𝒪-fields.
- **Type:** $T_L \equiv_\mathcal{O} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$.
- **Properties:** Reflexive, symmetric, transitive.
- **Real-world:** Two versions of a report producing the same conclusion.
- **Invalid:** "Equivalence" without 𝒪.

### Specification
- **Definition:** Statement of what should happen.
- **Type:** $(Property, Scope, Method, Preconditions)$.

### VerificationRun
- **Definition:** Actual invocation.
- **Type:** $(SpecID, ExecutionRunID, StartTime, EndTime, InputK, Trace)$.

### VerificationResult
- **Definition:** Structured result.
- **Type:** $(RunID, Expected, Actual, Status, Counterexample, Method)$.

### Certificate
- **Definition:** Scoped, method-tagged, time-stamped assertion.
- **Type:** $(Property, Scope, Method, Result, Provenance, Signature, Time)$.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.
- **Rules:** $UNKNOWN \neq FAIL$, $UNDEFINED \neq FAIL$, $NOT\_APPLICABLE \neq PASS$.

### ExecutionRunID
- **Definition:** Deterministic identifier for a run.
- **Type:** Hash of (spec ID, code version, input hash, timestamp).

---

# Part V — Worked Examples

## Example 1 — Non-commutativity at KnowledgeOS level

**Setup.**

- $T_1$: `Revise(X)` sets `X.approved = True`.
- $T_2$: `Decide(X)` outputs `permit` iff `X.approved = True`.

**Computation.**

- $T_2 \circ T_1$: sets `approved = True`, outputs `permit`.
- $T_1 \circ T_2$: reads `approved = False`, outputs `deny`; then sets `approved = True`.

$$T_2 \circ T_1 \neq T_1 \circ T_2$$

**Conclusion.** KnowledgeOS-level non-commutativity arises from *semantic dependency*. This should be a theorem in R604.3.

## Example 2 — Preservation composition failure with unaligned bridge

**Setup.** $W = \{1, 2, 3, 4\}$.

- $T_1(x) = x \bmod 2$. $Z_1(x) = x \bmod 2$. ✅
- $T_2(y) = 0$ (constant). $Z_2(y) = y$. ❌ ($T_2$ does not preserve $Z_2$).

**Composition.** $T_2 \circ T_1(x) = 0$. For $x=1, y=2$: $T_2 \circ T_1(1) = T_2 \circ T_1(2)$, but $Z_1(1) = 1 \neq 0 = Z_1(2)$. ❌.

**Conclusion.** Bridge condition fails; composition does not preserve $Z_1$. The four-condition theorem holds.

## Example 3 — Scope mismatch prevents composition

- $T_1$: `Fit model on German voters 2026`. Scope: `{Population: German voters, Year: 2026}`.
- $T_2$: `Apply model to Austrian voters 2026`. Scope: `{Population: Austrian voters, Year: 2026}`.

Types match. Scopes differ. Silent composition would produce a prediction on a population the model was not fitted to. R604.2's conservative rejection is correct.

## Example 4 — Observational equivalence under 𝒪

- $T_L = T_3 \circ (T_2 \circ T_1)$
- $T_R = (T_3 \circ T_2) \circ T_1$

Under $\mathcal{O} = \{\text{Output, Scope, Regime}\}$: $T_L \equiv_\mathcal{O} T_R$ if they agree on these three. ✅.

Under $\mathcal{O}' = \{\text{Output, Scope, Regime, Provenance}\}$: they may differ in Provenance (grouping affects the provenance DAG). ❌.

**Conclusion.** Observational equivalence is observation-contract-relative.

## Example 5 — Loss composition with interaction term

- $T_1$: drop `name`. $L_1 = \{name\}$.
- $T_2$: map `{zip} → {region}` collapsing zips. $L_2 = \emptyset$ as declared.

$L(T_2 \circ T_1) = \{name, zip\text{-distinction}\}$. Union = $\{name\}$. Interaction term = $\{zip\text{-distinction}\}$.

## Example 6 — Why dominance is a hypothesis

- $T_1$: `Epistemic (Declared)` adds `X.new_fact = "e"`.
- $T_2$: `Governance (Governed)` sets `X.rule = "r"` but does not inspect `X.new_fact`.

Under dominance: $T_2 \circ T_1$ is `Governance`. But $T_1$'s effect is not consumed by $T_2$. Class assignment is ambiguous. R604.3 must test all nine combinations.

---

# Part VI — ML Positioning for Composition

## What ML may do

- Propose `CandidateComposition(A, B, score, S, Γ, P)`.
- Propose `CandidatePreservationTarget(A, B, Z)`.
- Propose `CandidateRegimeRelationship(Γ_A, Γ_B)`.
- Propose `CandidateLossRelationship(L_A, L_B)`.

Each is a typed candidate. Each goes through the firewall.

## What ML may not do

- Assert `Composable(A, B)` as a fact.
- Assert `Preserves(A ∘ B, Z)` as a fact.
- Write to X.

## The firewall

```
ML candidate
    ↓
TypeCheck
    ↓
ContractCheck
    ↓
RegimeCheck
    ↓
ScopeCheck
    ↓
AssumptionCheck
    ↓
L4 Verification
    ↓
Assessment
```

## Concrete technique

**Features:** operation IDs, type signatures, scope vectors, regime vectors, declared preservation targets, declared losses, provenance tags, embedding-based semantic similarity.

**Model:** Gradient-boosted trees (XGBoost/LightGBM) on tabular features.

**Labels:** Synthetic — positive/negative pairs constructed by the reference calculus (a composition is positive if it type-checks and preserves the declared target on a finite domain).

**Metrics:** Precision, recall, FDR, FIR, calibration error, abstention quality.

**Cardinal rule:**

$$\boxed{MLAccuracy \neq Compossibility}$$

## Where ML earns its keep

- **Adversarial search:** generate near-miss compositions where type signature matches but regime or scope differs.
- **Calibration:** reliability diagrams for the model's own confidence.
- **OOD detection:** detect when an operation pair is unlike training data.

---

# Part VII — Optimized Architecture

## Frozen items (R604.2 baseline)

- Three-level associativity decomposition.
- Observational equivalence as the right notion of specification equality.
- Scope and regime as first-class; silent composition is rejected.
- Loss composition is not union; it has an interaction term.
- Preservation composition requires a bridge target.
- Non-commutativity is not an error.
- ML outside the authority path.

## Items to freeze in R604.3

- CompatibilityWitness corrected to 8-tuple.
- ObservationContract as a first-class typed object.
- `ExecutionRunID` required on every run.
- Counterexamples printed in `VerificationResult`.
- Dominance rule renamed to "current implementation output" pending the R604.3 matrix.
- Non-commutativity as a stated theorem with a KnowledgeOS-level counterexample.

## Composition matrix (R604.3 deliverable)

```
       |  Pure       |  Epistemic  |  Governance
-------+-------------+-------------+-------------
Pure   |  ?          |  ?          |  ?
Epis.  |  ?          |  ?          |  ?
Gov.   |  ?          |  ?          |  ?
```

Each cell must be *tested*. For each pair: resulting class, mutation policy, scope, regime, preservation, loss, provenance.

## Optimized architecture

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
        Composition (witness, associative up
                    to observational equivalence)
                              │
                     L3 Assessment
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
                              │
                ML Firewall (boundary contract)
                              │
                     L5 Intelligence
       CandidateComposition / Adversarial / OOD
                              │
                     L6 Governance
                              │
                            Action
```

No L4.5. No new BC. No new Kernel primitive.

## The seven laws

$$\boxed{ML \not\to X}$$
$$\boxed{ML \not\to Assessment \text{ without L4}}$$
$$\boxed{Pure \Rightarrow X' = X}$$
$$\boxed{Spec \neq Run \neq Result \neq Cert}$$
$$\boxed{\text{Use the weakest sufficient method}}$$
$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$
$$\boxed{NonCommutativity \neq Error}$$

---

# Part VIII — The Next Slice (R604.3)

## What R604.3 must produce

**Matrix.** For all 9 class pairs: resulting class, mutation policy, scope, regime, preservation, loss, provenance, with tests.

**Theorems.**
- Non-commutativity theorem (with the Revise/Decide example).
- Preservation composition theorem (four conditions).
- Loss composition theorem (with interaction term).
- Specification-level associativity up to observational equivalence (with explicit 𝒪).

**Data model extensions.**
- CompatibilityWitness as 8-tuple.
- ObservationContract as a first-class object.
- `ExecutionRunID` emitted in every certificate.

**Report format.**
- Every test emits `certificate.json` with all fields.
- Counterexamples printed inline in the summary.

## What R604.3 must not do

- Do not freeze the dominance rule before testing all 9 pairs.
- Do not freeze `Loss = union`.
- Do not freeze `Preservation composes`.
- Do not add ML yet.
- Do not add a new BC.
- Do not claim universal theorems from finite tests.

## Sequencing

- **R604.3** — composition matrix + non-commutativity theorem + counterexample printing.
- **R604.4** — metamorphic testing on composition.
- **R604.5** — full invariant catalogue.
- **R604.6** — W1–W7 ML benchmark.
- **R604.7** — Theory Specification v1.0.

---

# Part IX — Current Status

| Area | Status |
|---|---|
| Kernel | Stable |
| L0–L6 architecture | Stable |
| Terminology | ~92% frozen |
| Class × MutationPolicy | Frozen |
| CompatibilityWitness | 7-tuple in code; 8-tuple correction pending |
| I-X02 | Executed and confirmed for tested cases |
| TPP | Executed with counterexample |
| Preservation | Executed for tested cases |
| Composition | Executed for 10 tests; matrix pending |
| Associativity | Three levels; functional proved |
| Observational equivalence | Introduced; typing pending |
| Non-commutativity | Stated; not yet proven at KnowledgeOS level |
| Loss composition | Correctly rejected as union; interaction term stated |
| Scope / Regime / Provenance | Executable |
| ML | Outside authority path |
| DDD | No new BC |
| **Reference calculus** | **Second execution: 10/10 PASS** |
| **Execution evidence** | **Produced, but no ExecutionRunID/certificate surfaced** |

## The single most important finding

R604.2's **three-level associativity decomposition** is the correct mathematical diagnosis. "Is KnowledgeOS associative?" is not a well-formed question; "at which level, under which observation contract, with which witnesses?" is.

## The single most important caveat

The dominance rule `Governance > Epistemic > Pure` is used in code but not proven. It must not be frozen until R604.3 tests all nine class pairs.

---

# Direct answer to your message

You asked me to review R604.2 as mathematician, statistician, DDD architect, computer-logic and ML expert; to define every term; to prove by example; to apply ML correctly; to optimize the architecture.

I have done all five. Findings:

1. **R604.2 is a correct step**, with the substantive contribution of decomposing associativity into three levels and introducing observational equivalence.

2. **The 10/10 execution is real evidence**, but scoped.

3. **Five residual issues:**
   - Dominance rule used but not justified.
   - CompatibilityWitness is 7-tuple in code; 8-tuple required.
   - Observational equivalence is untyped.
   - Non-commutativity stated but not proven at KnowledgeOS level.
   - Loss composition refined by interaction term but not stated.

4. **Theorems to state in R604.3:**
   - Non-commutativity (Revise/Decide counterexample).
   - Preservation composition (four conditions).
   - Loss composition (with interaction term).
   - Specification associativity up to observational equivalence (with explicit 𝒪).

5. **The architecture remains minimal:** no L4.5, no new BC, no new Kernel primitive, seven laws.

$$\boxed{\text{Test the composition matrix. State the theorems. Print counterexamples. Then attack.}}$$

Tell me which you want next:

- **(A)** The R604.3 specification (matrix, theorems, tests, data-model extensions) — before code.
- **(B)** The R604.3 code directly.
- **(C)** Both, in that order.

I recommend **(C)**, and I recommend that R604.3's **first output** be the composition matrix with all nine cells filled by *executed tests*, plus printed counterexamples for the non-commutativity and preservation-composition theorems. That is the point at which composition is genuinely earned.