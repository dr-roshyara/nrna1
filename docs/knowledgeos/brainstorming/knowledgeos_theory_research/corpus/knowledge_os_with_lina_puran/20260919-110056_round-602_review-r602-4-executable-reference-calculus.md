# Review of R602.4 — Executable Reference Calculus

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

I will do seven things:

1. **Verdict** on R602.4.
2. **What it closed correctly.**
3. **Where it remains wrong or imprecise** — six specific items, each with a concrete example.
4. **Full term-by-term definitions**, one at a time, with real-world and invalid examples.
5. **Worked examples** that prove or refute the key claims.
6. **ML positioning** after R602.4, including a concrete technique plan.
7. **Optimized architecture** and the concrete next step.

No new Kernel primitive. No new BC. No new book. No ML in the oracle.

---

# Part I — Verdict

R602.4 is the first document in the series that **correctly refuses to expand the theory and demands execution instead.** That is the right move. But it still contains six residual issues, one of which is a recurrence of the very discipline problem the document claims to have closed.

**Strongest correct claim:** the transition from "design KnowledgeOS" to "execute and falsify KnowledgeOS" is the correct phase shift.

**Strongest incorrect claim:** "I verified this finite case computationally." This is the **fifth** recurrence of described-as-executed. The document explicitly warns against this and then commits it — in the same section.

**Strongest correct rejection:** `Loss(T₂∘T₁) ≠ Loss(T₁) ∪ Loss(T₂)`.

**Strongest under-specification:** the composition theorem's bridge condition is stated, but its quantifier structure is still pointwise in R602.4, not universal over the state space.

---

# Part II — What R602.4 Closed Correctly

| Claim | Verdict | Why it matters |
|---|---|---|
| Specification ≠ Execution ≠ Result ≠ Certificate | ✅ Correct | Enforced at code level. |
| `Pure ⇒ X' = X`, but not `K' = K` | ✅ Correct | Allows audit append. |
| `Admissible = TypeOK ∧ Pre ∧ ContractOK ∧ ScopeOK ∧ RegimeOK ∧ MutationOK` | ✅ Correct | Typed transition-system formulation. |
| Class × Mutation admissibility matrix is executable | ✅ Correct | Not documentation; enforced. |
| CompatibilityWitness is a partial conversion | ✅ Correct | `String ⇀ PositiveInteger`. |
| Composition requires witness: `T₂ ∘_w T₁` | ✅ Correct | Anti-corruption mechanism. |
| Projection ≠ Aggregation ≠ Deduplication | ✅ Correct | Frozen. |
| LossProfile ≠ PreservationAssessment | ✅ Correct | Structural vs evaluated. |
| Loss composition is derived, not union | ✅ Correct | Frozen. |
| Six-state status vocabulary | ✅ Correct | `UNKNOWN ≠ FAIL`, etc. |
| ML cannot mutate X, cannot bypass L4 | ✅ Correct | Firewall is a contract. |
| Deterministic oracle first, ML stress-tests it | ✅ Correct | Right ordering. |
| No new BC, no new Kernel primitive | ✅ Correct | The single most important architectural fact. |

This is substantial and correct.

---

# Part III — Six Residual Errors

## Error 1 — Described-as-executed: "I verified this finite case computationally"

R602.4 states, in §11 and §12:

> "I verified this finite case computationally; the result is a genuine finite-model execution, not a claim of a universal theorem."

But no runtime trace, no `ExecutionRunID`, no output log appears. This is the exact discipline violation that I-A10 was introduced to prevent.

**Fix.** The correct phrasing is:

> I hand-enumerated the finite case. The counterexample is hand-verifiable. No runtime evidence is claimed until R602.4b is executed.

This is the **fifth** recurrence. The pattern is not a moral failure; it is a **lexical collapse** between three distinct evidence types:

$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$

Each must be labeled. R602.4's own `Specification ≠ Run ≠ Result` chain requires this discipline; R602.4 should have applied it to itself.

## Error 2 — Preservation composition is stated pointwise, not over the state space

R602.4 §12 states:

$$Preserves(T_1) = True, \quad Preserves(T_2) = False \Rightarrow Preserves(T_2 \circ T_1) = False$$

But `Preserves` is a *universal* property:

$$Preserves(T, Z) \equiv \forall x, y \in W : T(x) = T(y) \Rightarrow Z(x) = Z(y)$$

The composition theorem R602.4 gives is therefore **pointwise in form and universal in claim.** The correct statement requires four conditions, as established in the previous review:

$$\boxed{Preserves(T_1, Z_X) \land Preserves(T_1, Z_Y \circ T_1) \land Preserves(T_2, Z_Y) \land (Z_X = Z_Z \circ T_2 \circ T_1 \text{ on } W) \Rightarrow Preserves(T_2 \circ T_1, Z_X)}$$

R602.4's condition "$Z_Y$ is the correct bridge preservation target" is a *name* for the missing conditions, not a statement of them.

## Error 3 — The compatibility witness is under-typed

R602.4 freezes:

$$w = (src, tgt, conv, pre, Z_w, Loss_w, \Gamma_w)$$

But `pre` is ambiguous between two distinct conditions:

- `pre_conv`: when the conversion is *defined*.
- `pre_comp`: when the *composition as a whole* is admissible.

**Counterexample.** Let `T₁ : DegreeCelsius → DegreeFahrenheit` and `T₂ : Kelvin → Joule`. The conversion `conv : Fahrenheit ⇀ Kelvin` is total on ℝ. But the composition is nonsense — the physical regime differs.

The `pre` field alone cannot decide this. The correct witness is an **8-tuple**:

$$w = (src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$$

R602.4 uses the 7-tuple form, which under-determines composition.

## Error 4 — `Assess` and `Create` cannot be unconditionally classified

R602.4 does not change the class of `Assess` and `Create`, both of which are contract-dependent:

- `Assess` with contract "return assessment, log in H" → `Pure`.
- `Assess` with contract "write assessment into X as `currentRisk`" → `Epistemic`.
- `Create` with contract "propose an artifact" → `Pure/Epistemic*`.
- `Create` with contract "install as authoritative evidence" → `Epistemic`.

**Fix.** Both must be marked `Pure/Epistemic*`, with the actual class determined by the contract, as R602.4 correctly did for `Reduce` and `Translate`.

## Error 5 — The verification result schema is missing `Expected`/`Actual`/`Method`

R602.4 §16 gives:

$$VR = (InvariantID, Operation, Scope, Preconditions, Method, Expected, Actual, Status, Counterexample, Provenance, Certificate)$$

Good. But §21's epistemic chain:

$$Specification \to Execution \to Observation \to Assessment \to Certificate$$

omits the fact that `Expected` and `Actual` must be **first-class** for `Status` to be meaningful. Without them, a `FAIL` could be a test bug, not a theory violation. This was flagged in previous reviews; R602.4 restores them in §16 but does not consistently use them in §21.

## Error 6 — The "single-file Python reference calculus" is not yet specified

R602.4 §23 says: "build a single-file Python Reference Calculus."

But the document does not specify:

- What the entry point is.
- What the test harness format is.
- How the certificate is emitted.
- How `ExecutionRunID` is generated.
- Whether the engine is deterministic (it should be).
- Whether the tests are asserted or reported.

This is precisely what R602.4b must specify before any code is written.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant touched.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact, invariant under representation change.
- **Type:** $\text{ID} : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invalid:** ISBN changes when the cover changes.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Definition:** Connection between identities carrying a declared type.
- **Type:** $r : ID \times ID \to \text{RelationType}$.
- **Real-world:** `derivedFrom(E456, E123)`.
- **Invalid:** `relatedTo` with no type.
- **Invariant:** I-E08.

### Semantics (Sem)
- **Definition:** Interpretation of identities/relations under declared context and regime.
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.
- **Real-world:** "bank" in financial vs geographic context.
- **Invalid:** Meaning declared without context.
- **Invariant:** I-S01, I-S03.

## Fabric terms

### State (K)
- **Definition:** Authoritative KnowledgeOS representation plus immutable history.
- **Type:** $K = (X, H)$.
- **Real-world:** Bank's current ledger plus audit log.
- **Invalid:** Silent mutation of X.

### Authoritative State (X)
- **Definition:** What KnowledgeOS currently treats as authoritative under its governance and contract rules.
- **Type:** $X = \{\text{facts, evidence, contexts, active contracts}\}$.
- **Real-world:** Accepted patient record.
- **Invalid:** Treating an ML score as authoritative X.
- **Invariant:** Invariant 1.

### History (H)
- **Definition:** Append-only event record.
- **Type:** $H = (e_1, \ldots, e_n)$.
- **Real-world:** Git commit log.
- **Invalid:** Rewriting a past event.
- **Invariant:** I-G05.

### Event
- **Definition:** Atomic append-only record.
- **Type:** $Event = (Type, Actor, Time, Cause, Input, Output, Contract, Provenance)$.
- **Real-world:** "Payment approved."
- **Invalid:** Event without time or actor.

### Evidence
- **Definition:** Artifact admissible under a declared contract as support for an inquiry.
- **Type:** $Evidence = (Artifact, Provenance, Scope, Admissibility)$.
- **Real-world:** Lab report.
- **Invalid:** LLM output admitted by default.
- **Invariant:** I-A01.

### Inquiry (Q)
- **Definition:** Explicit question with target and context.
- **Type:** $Q = (Target, Question, Context)$.
- **Real-world:** "Did transaction T occur before 14:00?"
- **Invalid:** "Analyse these documents."

### Context (Ctx)
- **Definition:** Versioned situational state.
- **Type:** $Ctx = (ID, Version, Attributes)$.
- **Real-world:** "Employment context v4."
- **Invalid:** Reading stale version.
- **Invariant:** I-S03.

### Contract
- **Definition:** Declared agreement specifying what an operation may and must do.
- **Type:** $Contract = (Pre, Post, Scope, Regime, Preservation, Authority)$.
- **Real-world:** GDPR processing agreement.
- **Invalid:** Execution without contract.
- **Invariant:** Invariant 2.

### Regime (Γ)
- **Definition:** Formal interpretive framework.
- **Type:** $\Gamma \in \{\Gamma^{Logic}, \Gamma^{Probability}, \Gamma^{Semantic}, \Gamma^{Governance}, \ldots\}$.
- **Real-world:** Frequentist vs Bayesian.
- **Invalid:** Evaluating without declared regime.
- **Invariant:** Regime Isolation.

### Scope
- **Definition:** Domain in which a claim is valid.
- **Type:** $Scope = (Population, StateSpace, Time, Context, Regime)$.
- **Real-world:** "TPP holds for sensor model 2 only."
- **Invalid:** "TPP holds" without scope.
- **Invariant:** I-A08.

## Derived terms

### Assessment (A)
- **Definition:** Derived evaluation; never automatically authoritative.
- **Type:** $A = f(K, Q, Ctx, Contract, \Gamma)$.
- **Real-world:** Credit score.
- **Invalid:** Score written as a transaction.
- **Invariant:** I-X02 (scoped).

### Determination (Det)
- **Definition:** Rule-based resolution among admissible alternatives.
- **Type:** $Det = (Q, \mathcal{A}, E, \Gamma, \rho, R)$, $R \subseteq \mathcal{A}$.
- **Real-world:** Jury verdict.
- **Invalid:** Verdict as authorization.
- **Invariant:** I-T02, I-G02.

### Decision
- **Definition:** Authorized governance choice.
- **Type:** $Decision = f(Det, Authority, Contract)$.
- **Real-world:** Board vote.
- **Invalid:** Decision as completed action.
- **Invariant:** I-T03.

### Action
- **Definition:** Operation producing change in external world.
- **Type:** $Action : State \to State'$ (operational).
- **Real-world:** Bank transfer.
- **Invalid:** Authorized but not executed.
- **Invariant:** I-T04.

### Candidate
- **Definition:** Unvalidated heuristic or ML output.
- **Type:** $Cand_X$ for X ∈ {Evidence, Meaning, Conflict, Regime, Transformation, Dependency}.
- **Real-world:** Spam score.
- **Invalid:** Candidate promoted to X.
- **Invariant:** I-A01, I-A02.

### Certificate
- **Definition:** Assurance artifact recording that a property passed a declared method under a declared scope.
- **Type:** $Cert = (Property, Scope, Method, Result, Provenance, Time)$.
- **Real-world:** Safety certification for a device.
- **Invalid:** "Verified" without scope, method, time.
- **Invariant:** I-A08, I-A09.

## Operation-level terms

### OperationClass
- **Definition:** What kind of semantic activity an operation performs.
- **Values:** `Pure`, `Epistemic`, `Governance`.
- **Real-world:** `Evaluate` → Pure; `Revise` → Epistemic; `Decide` → Governance.
- **Invalid:** Treating `Assess` as always Pure.

### MutationPolicy
- **Definition:** Whether and under what authority an operation may modify X.
- **Type:** Two-channel: $(Policy_X, Policy_H)$.
- **Values:** $Policy_X \in \{Forbidden, Contractual, Governed\}$, $Policy_H \in \{Always, Optional\}$.
- **Real-world:** `Evaluate` has $(Forbidden, Optional)$.

### Admissibility Rule
- **Definition:** Which class-policy pairs are legal.
- **Type:** $Admissible(c, p_X) \iff (c = Pure \Rightarrow p_X = Forbidden) \land (c = Governance \Rightarrow p_X = Governed) \land (c = Epistemic \Rightarrow p_X \in \{Contractual, Governed\})$.

### CompatibilityWitness (corrected to 8-tuple)
- **Definition:** Declared conversion with declared preconditions and preservation target.
- **Type:** $w = (src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.
- **Real-world:** Meter-to-centimeter with precondition "metric regime."
- **Invalid:** Treating witness as total function.

### WitnessEquivalence
- **Definition:** Two witnesses are equivalent iff all fields equal.
- **Type:** $w \sim w' \iff$ all fields equal.
- **Real-world:** Two declarations of the same metric conversion.
- **Invalid:** Assuming associativity without this definition.

### LossProfile
- **Definition:** What a transformation discards, structurally.
- **Type:** $(DiscardedDimensions, DeclaredLoss, PreservationTargets)$.
- **Real-world:** Dropping name and address.
- **Invalid:** Assuming union under composition.
- **Invariant:** I-X05, I-X07.

### PreservationTarget (Z)
- **Definition:** Property required to remain invariant.
- **Type:** $Z : W \to \text{TargetValue}$.
- **Real-world:** Total population count.
- **Invalid:** Claiming preservation without Z.

### PreservationAssessment
- **Definition:** Derived evaluation of whether a target survives.
- **Type:** $PA(T, Z, C, \Gamma) \to \text{Result}$.
- **Real-world:** Certificate "population preserved after aggregation."
- **Invalid:** Equating LossProfile with PreservationAssessment.

### Specification / VerificationRun / VerificationResult / Certificate
- **Definition:** Four distinct types forming the assurance chain.
- **Type:**
$$Spec = (Property, Scope, Method, Pre)$$
$$Run = (SpecID, StartTime, EndTime, InputK, Trace, ExecutionRunID)$$
$$Result = (RunID, Expected, Actual, Status, Counterexample)$$
$$Cert = (ResultID, Property, Scope, Method, Provenance, Signature, Time)$$
- **Real-world:** Test plan → test execution → test result → signed test report.
- **Invalid:** Claiming execution by citing the plan.
- **Invariant:** I-A10.

### Status
- **Definition:** Outcome classification.
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.
- **Rules:** $UNKNOWN \neq FAIL$, $UNDEFINED \neq FAIL$, $NOT\_APPLICABLE \neq PASS$.

### Counterexample
- **Definition:** Concrete admissible input where a universal claim fails.
- **Type:** $CE = (Input, Claim, Witness)$.
- **Real-world:** A single stock trade violating insider-trading policy.
- **Invariant:** I-A07.

### Projection / Aggregation / Deduplication
- **Definition:**
$$\pi : X \to Y \quad \text{(dimension change)}$$
$$Agg : X^n \to Y \quad \text{(many-to-one)}$$
$$Dedup : X^n \to X^m, m \le n \quad \text{(multiplicity removal)}$$
- **Invalid:** Conflating them.

### Reduce
- **Definition:** Operation-level spec that may internally call projection, aggregation, or deduplication.
- **Invalid:** Treating as a fourth mathematical primitive.

---

# Part V — Worked Examples

## Example 1 — Preservation composition, universal form

**Setup.** $W = \{1, 2, 3, 4\}$.

- $T_1(x) = x \bmod 2$.
- $Z_1(x) = x \bmod 2$.
- $T_2(y) = y$. $Z_2(y) = y$.

**Check $T_1$.** For all $x, y$: if $T_1(x) = T_1(y)$, then $x \bmod 2 = y \bmod 2$, so $Z_1(x) = Z_1(y)$. ✅.

**Check $T_2$.** Trivially preserves $Z_2$. ✅.

**Check composition.** $T_2 \circ T_1(x) = x \bmod 2 = Z_1(x)$. If $T_2 \circ T_1(x) = T_2 \circ T_1(y)$, then $Z_1(x) = Z_1(y)$. ✅.

**Conclusion.** With aligned bridge target, preservation composes.

## Example 2 — Preservation composition, failure due to bridge

**Setup.** $W = \{1, 2, 3, 4\}$.

- $T_1(x) = x \bmod 2$.
- $Z_1(x) = x$.
- $T_2(y) = 0$ (constant). $Z_2(y) = y$.

**Check $T_1$.** If $T_1(x) = T_1(y)$, then $x \bmod 2 = y \bmod 2$, but $Z_1(x) = x$ may differ. ❌. $T_1$ does not preserve $Z_1$.

**Adjust.** Let $Z_1(x) = x \bmod 2$ again.

**Check $T_1$.** ✅.

**Check $T_2$.** For all $y, y'$: $T_2(y) = T_2(y')$ always (constant). But $Z_2(y)$ may differ. ❌. $T_2$ does not preserve $Z_2$.

**Compose.** $T_2 \circ T_1(x) = 0$ always. Does it preserve $Z_1$? For all $x, y$: $T_2 \circ T_1(x) = T_2 \circ T_1(y)$, so we need $Z_1(x) = Z_1(y)$, which is false for $x=1, y=2$.

**Conclusion.** $T_2 \circ T_1$ does not preserve $Z_1$. The bridge condition fails because $T_2$ does not preserve $Z_2$. ✅. The four-condition theorem holds.

## Example 3 — Loss composition interaction

**Setup.** Let $X$ be records with fields `{name, zip}`.

- $T_1$: drop `name`. Output: `{zip}`. $L_1 = \{name\}$.
- $T_2$: map `{zip}` → `{region}`, collapsing zips `65185` and `65186` to the same region. $L_2 = \emptyset$ as a declared loss.

**Direct loss of composition.** $T_2 \circ T_1$: `{name, zip} → {region}`. Loses `name`, loses zip-distinction.

$$L(T_2 \circ T_1) = \{name, zip\text{-distinction}\}$$

**Union.** $L_1 \cup L_2 = \{name\}$.

**Conclusion.** $L(T_2 \circ T_1) \supsetneq L_1 \cup L_2$. The interaction term is `{zip-distinction}`, arising because $T_2$'s behavior depends on distinctions $T_1$ preserves.

## Example 4 — Why ML cannot bypass the ContractCheck

**Setup.** ML emits a `CandidateTransform` claiming to project `{name, age, zip}` → `{age, zip}`. It provides a `LossProfile` that omits `name`.

- Gate 1 (TypeCheck): passes.
- Gate 2 (ContractCheck): passes if the contract only requires "a LossProfile is present."

**Result:** false LossProfile enters assessment.

**Fix.** Firewall contract must require **declared** LossProfile and PreservationTarget. Inferred values are `NOT_APPLICABLE` for transformation.

## Example 5 — Why `Assess` cannot be unconditionally classified

**Contract A.** "Return assessment, log in H." → `Pure`.

**Contract B.** "Write assessment into X as `currentRisk`." → `Epistemic`.

**Conclusion.** Operation table cannot list `Assess` as unconditionally `Epistemic`.

---

# Part VI — ML Positioning and Techniques

## Three roles (frozen)

1. **Candidate generation** — proposing typed artifacts.
2. **Adversarial search** — proposing counterexamples.
3. **Calibration / OOD** — estimating own reliability.

## The firewall, closed against Q10′

Every ML candidate must carry **declared** (not inferred) `LossProfile` and `PreservationTarget`. Otherwise the firewall's ContractCheck has a hole.

## Concrete technique plan (after the deterministic oracle exists)

**Step 1 — Synthetic data generation.**
Construct synthetic worlds W1–W7 with known ground-truth dependency graphs. Sample from them.

**Step 2 — Feature engineering.**
Extract features per evidence pair: source identity, citation overlap, document lineage, model lineage, transformation lineage, semantic similarity, temporal proximity, graph distance.

**Step 3 — Model selection.**
Gradient-boosted trees (XGBoost/LightGBM) on engineered features. Justify: tabular data, mixed feature types, interpretable feature importance. Avoid deep learning unless sequence structure is inherent.

**Step 4 — Evaluation.**
Precision, recall, FDR, FIR, common-mode recall, multi-factor recall, calibration error, abstention quality.

**Step 5 — Adversarial testing.**
Use ML to search for inputs where the dependency detector's output is unstable. The deterministic oracle validates or refutes.

## The cardinal rule

$$\boxed{MLAccuracy \neq EpistemicValidity}$$

Accuracy is measured against synthetic ground truth. Epistemic validity is measured against the reference specification, which is different.

---

# Part VII — Optimized Architecture

## Frozen items

- `K = (X, H)`.
- `Class ∈ {Pure, Epistemic, Governance}`.
- `MutationPolicy = (Policy_X, Policy_H)`.
- Admissibility rule.
- `Pure ⇒ X' = X`, but not `K' = K`.
- CompatibilityWitness = 8-tuple.
- WitnessEquivalence defined.
- Preservation composition has four conditions.
- Loss composition has interaction term.
- Projection ≠ Aggregation ≠ Deduplication.
- Reduce is an operation spec.
- LossProfile ≠ PreservationAssessment.
- Spec ≠ Run ≠ Result ≠ Cert.
- Six-state status with rules.
- ReferenceOracle(Γ, W, C), not ReferenceOracle(World).
- ML firewall is a boundary contract.
- I-A10: `HandEnumerated ≠ Simulated ≠ ExecutedWithTrace`.

## Remaining open

- Does the witness set form a category? (Defer.)
- Is there a canonical minimal witness between types? (Open.)
- Can the loss interaction term be characterized uniformly? (Open.)
- Does the admissibility rule follow from a single deeper principle? (Open.)

## Optimized architecture (final for R602.4)

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
                              │
              CompatibilityWitness (8-tuple)
                              │
                     L3 Assessment
       Evidence / Assessment / Determination
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Specification → Run → Result → Certificate
                              │
                ML Firewall (boundary contract)
                Type → Contract → Assumption
                       → Evidence → Verify
                              │
                     L5 Intelligence
       Candidate / Adversarial Search / Calibration / OOD
                              │
                     L6 Governance
        Authority / Permission / Decision / Accountability
                              │
                            Action
```

**No L4.5. No new BC. No new Kernel primitive.**

## The five laws

$$\boxed{ML \not\to X}$$
$$\boxed{ML \not\to Assessment \text{ without L4}}$$
$$\boxed{Pure \Rightarrow X' = X}$$
$$\boxed{Spec \neq Run \neq Result \neq Cert}$$
$$\boxed{\text{Use the weakest sufficient method.}}$$

## The sixth law (new)

$$\boxed{HandEnumerated \neq Simulated \neq ExecutedWithTrace}$$

Each evidence type must be labeled. R602.4 should have applied this to its own §11 and §12.

---

# Part VIII — What R602.4b Must Actually Do

R602.4b is the code. It must be **actually executed by you**, and the output returned. Otherwise the described/executed confusion persists.

## Specification for the reference calculus

**File:** `knowledgeos_reference.py`. Single file, no dependencies.

**Core types:**
- `State(X, H)` with `X` frozen, `H` append-only.
- `Operation` with the 12-tuple from R602.4 §3.
- `Contract`, `Regime`, `Scope`.
- `CompatibilityWitness` (8-tuple).
- `LossProfile`, `PreservationTarget`.
- `Specification`, `VerificationRun`, `VerificationResult`, `Certificate`.
- `ExecutionRunID` — deterministic, derived from input hash.

**Core functions:**
- `admissible(T, K, C, Γ, S) → bool`
- `execute(T, K, C, Γ, S) → (K', Result, Run)`
- `compose(T₂, T₁, w) → T`
- `check_tpp(π, Z, W) → Result`
- `check_preservation(T, Z, W) → Result`
- `find_counterexample(claim, W) → CE | None`
- `verify(I, K, T, C, Γ, S) → VerificationResult`

**Test harness:**
- 18 deterministic tests (T01–T18 from R602.4 §23).
- 6 metamorphic tests (M01–M06).
- Each test emits a `VerificationResult` with `Method = "finite_exhaustive" | "hand_checked"`.

**Certificate emission:**
- Each `VerificationResult` with `Status = PASS` produces a `Certificate` with scope, method, signature (hash), time.

**The first test to run:**
T08 — TPP counterexample discovery. This is the hand-checkable case from Part V. It either confirms or refutes the hand result.

## What R602.4b must not do

- Must not add a new primitive.
- Must not use ML.
- Must not claim universal theorems.
- Must not conflate `HandEnumerated` with `ExecutedWithTrace`.
- Must not hide failures. If a test fails, that is a finding.

---

# Part IX — Current Status

| Area | Status |
|---|---|
| Kernel | Stable |
| L0–L6 architecture | Stable |
| Terminology | ~92% frozen |
| Class × MutationPolicy | Frozen |
| CompatibilityWitness | 8-tuple, frozen |
| Preservation composition | Four conditions, frozen |
| Loss composition | Interaction term, frozen |
| Projection/Aggregation/Dedup | Frozen |
| Spec/Run/Result/Cert | Frozen |
| Six-state status | Frozen |
| ML firewall | Frozen as boundary contract |
| Reference oracle scoping | Frozen |
| DDD | No new BC |
| **Executable reference calculus** | **Not yet built** |
| **Actual execution evidence** | **Not yet produced** |

## The single most important finding

R602.4 is correct that the phase shift is from *design* to *execution*. But it still commits the discipline violation it claims to be curing. The fix is I-A10, and its correct application is a **sixth law**: `HandEnumerated ≠ Simulated ≠ ExecutedWithTrace`.

## The single most important task

**Build R602.4b and actually execute it.** Everything else is commentary.

---

# Direct answer to your message

You asked me to review R602.4 as mathematician, statistician, DDD architect, computer-logic and ML expert; to define every term with real-world examples; to prove by example; to apply ML correctly; to optimize the architecture.

I have done all five. Findings:

1. **R602.4 is the correct phase shift** — from design to execution — and it closes several items correctly.
2. **Six residual errors**: described-as-executed (again), pointwise preservation composition, under-typed witness, contract-dependent class for `Assess`/`Create`, inconsistent use of `Expected`/`Actual`/`Method`, and unspecified test harness.
3. **The two theorems** (preservation, loss) are stated in their correct conditional form in this review, with worked examples.
4. **The ML firewall** is closed against Q10′ with the declared-LossProfile rule.
5. **The architecture** remains minimal: no L4.5, no new BC, no new Kernel primitive, six laws.

$$\boxed{\text{Build R602.4b, run it, return the output. Only then does R602.4 earn the word "executed."}}$$

Tell me which you want:

- **(A)** The frozen operation algebra specification (R602.4a).
- **(B)** The executable reference calculus code (R602.4b).
- **(C)** Both, in that order.

I recommend **(C)**, and I recommend that R602.4b's **first output** be T08 (TPP counterexample discovery), because it is the case with a hand-computed expected value. If the engine returns exactly `((2,2),(2,3))` with `Status = FAIL` for the full-world TPP and `Status = PASS` for the restricted-world TPP, the algebra has earned its first genuine runtime confirmation. If not, the engine or the specification is wrong, and we will have found something real.