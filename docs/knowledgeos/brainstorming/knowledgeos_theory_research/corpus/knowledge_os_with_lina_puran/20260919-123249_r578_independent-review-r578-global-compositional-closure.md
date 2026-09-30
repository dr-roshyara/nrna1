# Independent Review — R578 (Global Compositional Closure)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R578. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R578.
2. **What R578 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R578.1 / R579.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R578 is the **correct constructive successor to R577**, and it makes four substantive contributions:

$$\boxed{\text{Global Composition Theorem (by induction)}}$$
$$\boxed{LocalPreservation \not\Rightarrow GlobalPreservation}$$
$$\boxed{Executable \neq StructurallyAdmissible \neq EpistemicallyValid}$$
$$\boxed{\text{A dimension can be locally irrelevant but globally material}}$$

Each is correct. The fourth is the sharpest: it names a phenomenon (dimension materiality shifts across the chain) that earlier rounds had gestured at but not stated. R578 correctly attributes the failure to a *missing target alignment* between stages, and it correctly refuses to introduce `TargetAlignment` as a new primitive — it is a role of the existing preservation witness.

R578 also preserves the architecture at L0–L6 and adds no new Bounded Context, layer, or Kernel primitive. That is now a consistent pattern across R574–R578.

**Seven residual issues**, each requiring correction before R579:

1. The Global Composition Theorem is stated for **arbitrary $n$** but only proven for $n=1$ and $n=k+1$; the base case is fine, but the *admissible domain* is not restated at each step, so the induction silently changes the domain.
2. §10's "dimension locally irrelevant but globally material" is correct but not typed. It requires a **DimensionRelevance** predicate.
3. §16's loss result is stated qualitatively. The interaction term from R604.4 must be restated explicitly.
4. §17's test report lists seven cases but only five counterexamples/witnesses. The mapping is inconsistent.
5. §19's proposed invariants again mix theorems, definitions, and derived observations.
6. The identity-insertion invariance in §19 (I-X20) uses `~` without typing what $\sim$ means (observational equivalence under a declared $\mathcal{O}$).
7. Report discipline: still no `ExecutionRunID`, no `certificate.json`, no per-test `Method` field. This is the same discipline debt flagged since R604.3; it now spans 20+ rounds and must be closed in R578.1.

---

# Part II — What R578 Actually Establishes

## 2.1 The Global Composition Theorem

R578 §6–§7 states and proves:

**Theorem.** Let $T_i : X_{i-1} \rightharpoonup X_i$ for $i = 1, \ldots, n$. Suppose:

- (A) Typed composition holds between adjacent pairs.
- (B) Domain compatibility holds along the chain for each admissible input.
- (C) Contract compatibility (scope, regime, temporal, other dimensions) holds.
- (D) Local target preservation holds: $Z_{i-1}(x) = Z_i(T_i(x))$ for all admissible $x$ at each stage.
- (E) Target alignment holds: each $Z_i$ is explicitly declared as the representation of the same target as $Z_{i-1}$ under the chain's declared contract.

Then:

$$Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$$

for all admissible $x$.

**Proof.** Induction on $n$. Base: $n = 1$ follows from (D). Step: assume for $n = k$; apply (D) at $i = k+1$; substitute $y = T_k \circ \cdots \circ T_1(x)$. Correct.

**Scope caveat.** The induction assumes the *admissible domain* is preserved at each step. If $T_{k+1}$ is only partially defined on the image of $T_k \circ \cdots \circ T_1$, the theorem holds only on the restricted domain:

$$W_n = \{x \in W_0 : T_i \circ \cdots \circ T_1(x) \in W_i \text{ for all } i\}$$

R578 does not state this restricted domain in the induction step. R578.1 must.

## 2.2 The Local ≠ Global failure

R578 §2 constructs the counterexample:

- $T_1(x) = x$, with target $Z_1(x) = x \bmod 2$.
- $T_2$ uses target $Z_2(y) = \lfloor y / 2 \rfloor$.

Both are individually preserving. But the original target $x \bmod 2$ is not preserved by the final chain, because the intermediate target was changed without a declared alignment.

**Correct.** This is a genuine counterexample to naive global preservation.

**Sharper statement.** If target alignment is missing at stage $k+1$, the chain fails at that stage. The failure is local (at stage $k+1$), but the effect is global (the whole chain loses the original target).

## 2.3 The dimension-materiality shift

R578 §10's timestamp-loss example is correct and important:

- Stage 1: $Z_1 = $ numeric value (timestamp irrelevant).
- Stage 2: $Z_2 = $ value at time $t$ (timestamp material).

The timestamp was **locally** irrelevant at Stage 1 but **globally** material when the chain's target is considered.

**This requires a new predicate:**

$$DimensionRelevant(d, Z, W) \iff \exists x_1, x_2 \in W : d(x_1) \neq d(x_2) \land Z(x_1) \neq Z(x_2)$$

Dimension $d$ is relevant to $Z$ over $W$ iff there exist two states differing in $d$ that also differ in $Z$.

The timestamp-loss failure is then:

$$DimensionRelevant(timestamp, Z_0, W_0) = \text{True} \quad \text{but} \quad DimensionRelevant(timestamp, Z_1, W_1) = \text{False}$$

so a loss that appears harmless at Stage 1 is catastrophic when the composed target is evaluated.

## 2.4 The three-level separation

R578 §8:

$$\text{Executable} \neq \text{StructurallyAdmissible} \neq \text{EpistemicallyValid}$$

This is the same strictness claim as R576's three-level composition, restated for chains. R578 does not reprove strictness; R578.1 or R579 should supply the counterexamples. But the claim is correct.

## 2.5 The partiality result

R578 §15:

$$Dom(T_2 \circ T_1) = \{x \in Dom(T_1) : T_1(x) \in Dom(T_2)\}$$

**Correct.** Composition of partial functions. This composes iteratively for longer chains. R578 states it for two stages; the general form is:

$$Dom(T_n \circ \cdots \circ T_1) = \{x \in W_0 : T_i \circ \cdots \circ T_1(x) \in W_i\ \forall i \in [1, n]\}$$

## 2.6 The architecture result

R578 §22 states:

> No new BC, no new layer, no new Kernel primitive.

**Correct.** The theorem is a rule over existing objects. R578 is the fourth consecutive round without architectural inflation.

---

# Part III — Seven Defects to Fix in R578.1 / R579

## Defect 1 — The induction silently changes the admissible domain

R578 §7 proves the theorem by induction but does not state that $W_{k+1} = \{y : y = T_k \circ \cdots \circ T_1(x), x \in W_k\}$ at each step. Without this, the induction is not well-defined: it is not clear that the base case ($n=1$) and the step case ($n=k+1$) quantify over the same domain.

**Corrected statement.** Let:

$$W_i = \{T_i \circ \cdots \circ T_1(x) : x \in W_{i-1} \cap Dom(T_i \circ \cdots \circ T_1)\}$$

Then the theorem is:

$$\forall x \in W_0^* : Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$$

where $W_0^* = \{x \in W_0 : x \in Dom(T_n \circ \cdots \circ T_1)\}$.

## Defect 2 — Dimension relevance is not typed

R578 §10 gestures at "locally irrelevant but globally material" but does not type it. See §II.3 for the corrected predicate. R578.1 must state:

$$DimensionRelevant(d, Z, W) \iff \exists x_1, x_2 \in W : d(x_1) \neq d(x_2) \land Z(x_1) \neq Z(x_2)$$

And the failure mode becomes:

$$Loss(T_1) \ni d \land DimensionRelevant(d, Z_0, W_0) \Rightarrow GlobalPreservation \text{ may fail}$$

## Defect 3 — The loss interaction term is not restated

R578 §16 correctly says loss is target-relative but does not restate the interaction term from R604.4:

$$Loss(T_2 \circ T_1) = Loss_1 \cup Loss_2 \cup Loss_{\text{interaction}}(T_1, T_2)$$

This must be restated in R578.1 so the composition of losses over a chain of $n$ stages is derivable:

$$Loss(T_n \circ \cdots \circ T_1) = \bigcup_i Loss_i \cup \bigcup_{i<j} Loss_{\text{interaction}}(T_i, T_j) \cup \ldots$$

The higher-order interactions are real; a three-stage chain can have interactions only visible when all three stages are composed.

## Defect 4 — Test report/counterexample mapping is inconsistent

R578 §17 reports:

```
valid 3-stage chain: ADMITTED
scope mismatch: REJECTED
temporal mismatch: REJECTED
regime mismatch: REJECTED
target failure: REJECTED; witness=1
partial composition: UNDEFINED; witness=0
```

Six cases, two witnesses. But §2 and §10 discuss the target-drift counterexample and the timestamp-loss counterexample. These should each produce a witness or an explicit "no witness" (i.e., the case passes). The mapping between the report and the narrative is unclear.

**Recommendation.** R578.1 must list all cases, each with:

- setup reference,
- expected status,
- actual status,
- witness (if applicable),
- method (`finite_exhaustive`, `hand_checked`, or `property_based`),
- `ExecutionRunID`.

## Defect 5 — Invariants mix theorems, definitions, and derived observations

R578 §19 lists I-X16..I-X20. But:

- I-X16 (`GlobalPreserve`): **definition**.
- I-X17 (`Local ⇏ Global`): **theorem** (via counterexample).
- I-X18 (`Dom` composition): **definition**.
- I-X19 (`Loss ⇏ ¬Preserve`): **derived observation**.
- I-X20 (identity insertion): **theorem**.

**Recommendation.** Split into:

- **Definitions:** I-X16, I-X18.
- **Theorems:** I-X11 (conditional composition), I-X14 (spec associativity), I-X20 (identity insertion).
- **Counterexample theorems:** I-X12, I-X17.
- **Derived observations:** I-X15, I-X19.

## Defect 6 — Identity-insertion `~` is untyped

R578 §19 (I-X20):

$$T_2 \circ I \circ T_1 \sim T_2 \circ T_1$$

where $I$ is an admitted identity. But $\sim$ is not typed. From R577 §11, $\sim$ should be observational equivalence under a declared $\mathcal{O}$:

$$T_L \equiv_{\mathcal{O}} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$$

R578.1 must restate this.

## Defect 7 — Report discipline

Same pattern. R578 reports a summary, not a per-test certificate. This must be fixed.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms (unchanged)

### Identity (ID)
- **Definition:** Persistent unique name.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.

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
- **Type:** $T : X \rightharpoonup Y$ (partial).

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules, Observation)$.

## R577 terms

### Preservation Bridge
- **Type:** $B(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}}(T) : Z_X(x) = Z_Y(T(x))$.

### TargetPreservationBridge (predicate)
- **Type:** Predicate on CompatibilityWitnesses.

### Composition Status
- **Values:** `{ADMITTED, REJECTED, UNDEFINED, UNKNOWN}`.

### Specification Equivalence
- **Type:** $T_L \equiv_{\mathcal{O}} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$.

## R578 new terms

### Global Target Preservation
- **Definition:** The whole chain preserves the original target.
- **Type:** $GlobalPreserve(T_1, \ldots, T_n, Z_0, Z_n, W) \iff \forall x \in W : Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$.
- **Real-world:** A pipeline of unit conversions preserving physical length.
- **Invalid:** Assuming global preservation from local preservation alone.

### Local Target Preservation
- **Definition:** A single transformation preserves its own local target.
- **Type:** $LocalPreserve(T_i, Z_{i-1}, Z_i) \iff \forall x \in W_{i-1} : Z_{i-1}(x) = Z_i(T_i(x))$.

### Target Alignment
- **Definition:** The intermediate target is explicitly the representation of the same relevant target as the previous stage.
- **Type:** A role of the existing preservation witness: $TargetAlignment(T_i, Z_{i-1}, Z_i)$.
- **Real-world:** A Celsius target and a Fahrenheit target both being representations of physical temperature.
- **Invalid:** Implicitly assuming alignment without declaration.

### Dimension Relevance (new)
- **Definition:** A dimension $d$ is relevant to a target $Z$ over a domain $W$ iff there exist two states that differ in $d$ and differ in $Z$.
- **Type:** $DimensionRelevant(d, Z, W) \iff \exists x_1, x_2 \in W : d(x_1) \neq d(x_2) \land Z(x_1) \neq Z(x_2)$.
- **Real-world:** Timestamp is relevant to "value at time $t$".
- **Invalid:** Assuming a dimension is globally relevant because it exists in the domain.

### Admissible Domain of a Chain
- **Definition:** The set of starting states for which the whole chain is defined.
- **Type:** $W_n^* = \{x \in W_0 : T_i \circ \cdots \circ T_1(x) \in W_i\ \forall i \in [1, n]\}$.
- **Real-world:** Inputs for which every intermediate step is defined.

### Loss Interaction Term (recap from R604.4)
- **Definition:** Losses arising only from the composition, not from either stage alone.
- **Type:** $L_{\text{interaction}}(T_1, T_2)$.
- **Real-world:** A pipeline that drops zip-distinctions only because a later stage uses the wrong granularity.

### Executable / StructurallyAdmissible / EpistemicallyValid (three levels)
- **Definition:** Three increasing requirements on a chain.
- **Type:**
  - Executable: $Dom(T_n \circ \cdots \circ T_1) \neq \emptyset$ for some admissible input.
  - StructurallyAdmissible: typing, contract compatibility, and scope/regime/temporal checks pass.
  - EpistemicallyValid: global target preservation holds.
- **Real-world:** A pipeline that runs but loses the target is Executable + StructurallyAdmissible but not EpistemicallyValid.

### Identity Insertion Invariance
- **Definition:** Inserting an admitted identity transformation does not change the chain's observational behavior.
- **Type:** $T_2 \circ I \circ T_1 \equiv_{\mathcal{O}} T_2 \circ T_1$ under a declared $\mathcal{O}$.
- **Real-world:** Adding a no-op logging step does not change the result.

---

# Part V — Worked Examples

## Example 1 — Valid three-stage chain

**Setup.**

- $T_1$: meters → centimeters.
- $T_2$: centimeters → millimeters.
- $T_3$: millimeters → micrometers.
- $Z_i$ = physical length expressed in the corresponding unit.

**Check.**

- Typing: ✅.
- Domains: all of $\mathbb{R}^+$.
- Local preservation: ✅ at each stage.
- Target alignment: all $Z_i$ are physical length.

**Result.** $GlobalPreserve = \text{True}$.

## Example 2 — Local preservation but global failure

**Setup.**

- $T_1(x) = x$, $Z_1(x) = x \bmod 2$.
- $T_2(y) = \lfloor y/2 \rfloor$, $Z_2(y) = \lfloor y/2 \rfloor$.

**Check.**

- Local preservation at Stage 1: $Z_1(x) = x \bmod 2 = Z_1(T_1(x))$. ✅.
- Local preservation at Stage 2: $Z_2(y) = \lfloor y/2 \rfloor = Z_2(T_2(y))$. ✅.
- Target alignment from $Z_1$ to $Z_2$: **False**. $x \bmod 2$ and $\lfloor y/2 \rfloor$ are different targets.

**Result.** $GlobalPreserve = \text{False}$ despite local preservation at both stages.

## Example 3 — Timestamp-loss failure

**Setup.**

- Stage 1: measurement → numeric value; drops timestamp.
- Stage 2: value → value-at-time; requires timestamp.

**Check.**

- $Z_1$ = "numeric value". Timestamp irrelevant.
- $Z_2$ = "value at time $t$". Timestamp material.
- Chain's original target $Z_0$ = $Z_2$.

**Result.** $GlobalPreserve = \text{False}$. The dimension (timestamp) was locally irrelevant but globally material.

## Example 4 — Regime mismatch

**Setup.**

- $T_1$: Celsius → Fahrenheit.
- $T_2$: operating in Kelvin regime.
- No bridge between Fahrenheit and Kelvin.

**Result.** $REJECTED$.

## Example 5 — Scope mismatch

**Setup.**

- $T_1$: valid for German customers.
- $T_2$: valid for EU customers.
- No scope-translation contract.

**Result.** $REJECTED$.

## Example 6 — Temporal mismatch

**Setup.**

- $T_1$: EUR → USD at 10:00.
- $T_2$: USD → GBP at 14:00.

**Result.** $REJECTED$ (target at 10:00 cannot be preserved across a chain using different timestamps).

## Example 7 — Partial domain

**Setup.**

- $T_1(x) = x + 10$.
- $Dom(T_2) = \{y : y \le 5\}$.

**Check.** $x = 0 \Rightarrow T_1(0) = 10 \notin Dom(T_2)$.

**Result.** $UNDEFINED$ with witness $x = 0$.

## Example 8 — Loss accumulating harmlessly

**Setup.**

- $Z(x) = x \bmod 2$.
- $T_1(x) = x + 10$.
- $T_2(x) = x - 10$.

**Check.** $Z(T_2(T_1(x))) = (x + 10 - 10) \bmod 2 = x \bmod 2$. ✅.

**Result.** $ADMITTED$. Loss is declared but does not affect the target.

## Example 9 — Identity insertion

**Setup.** $I(x) = x$ with no mutation, admitted.

**Check.** $T_2 \circ I \circ T_1(x) = T_2(T_1(x))$. Under $\mathcal{O} = \{\text{Output}, \text{Scope}, \text{Regime}\}$, equivalent.

**Result.** $PASS$ (observational equivalence).

Under $\mathcal{O} \ni \text{Provenance}$, may differ because the provenance DAG has an extra node.

---

# Part VI — Architecture, ML Positioning, and Short Bullet Status

## VI.1 — Architecture (unchanged)

```
                KNOWLEDGEOS — Six-Layer Architecture
                              │
                     L0 Kernel (ID, R*, Sem)
                              │
                     L1 Semantic Fabric
             Meaning / Context / Scope / Contract / Regime
             ComparisonContract
                              │
                     L2 Formal Fabric
        Types / Operations / Partial Transformations
        CompatibilityWitness / RegimeBridge / PreservationBridge
        CompositionWitness / TargetPreservationBridge (predicate)
        TPP / Composition / Preservation / Loss
        DimensionRelevance
        Three-level composition: Function ⊊ Typed ⊊ Epistemic
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     CompositionStatus {Admitted, Rejected, Undefined, Unknown}
     GlobalPreservationAssessment
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     RegimeClosure / FirewallVerification / GlobalPreservationVerification
                              │
                     L5 Intelligence
       CandidateBridgeGenerator / Pairwise ML
       Group ML / Embeddings / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       BridgeAuthorization
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

**ML may do:**

- Propose `CandidateBridge(Γ_i, Γ_{i+1}, score, Z)`.
- Propose `CandidateComposition(T_i, T_{i+1}, score)`.
- Propose `CandidateTargetAlignment(Z_{i-1}, Z_i, score)`.
- Propose adversarial near-miss chains.
- Propose `CandidateRegime` for unlabeled sources.

**ML may not do:**

- Assert bridge validity.
- Assert composition validity.
- Assert target alignment.
- Write to X.
- Bypass L4.

**Firewall:**

```
ML candidate
  → TypeCheck
  → RegimeCheck
  → CompatibilityCheck
  → TPPCheck
  → PreservationBridgeCheck
  → TargetAlignmentCheck
  → ScopeCheck
  → TemporalCheck
  → PreconditionCheck
  → ProvenanceCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

**Adversarial benchmark for R579+:**

- High-similarity, low-alignment chains.
- Dimension-loss chains where the loss is locally irrelevant.
- Temporal-leakage chains.
- Scope-leakage chains.
- Regime-mismatched chains.
- ML-generated transformations with subtly wrong targets.

**Metrics:** Bridge Precision, Recall, FDR, FIR, False Composition Rate, Calibration Error, Abstention Quality.

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow BridgeValidity}$$
$$\boxed{MLAlignment \not\Rightarrow TargetAlignment}$$

## VI.3 — Short bullet status

### Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency (multi-factor, target/scope/regime-relative):** executable.
- **Admission, regime compatibility, regime bridge:** executable.
- **Logical regime boundaries:** R575 passed 10/10.
- **Cross-regime composition:** R576 passed 10/10.
- **Target-preserving composition:** R577 passed 10/10.
- **Global compositional closure:** R578 passed finite executable tests.
- **Global Composition Theorem:** proven by induction (with domain caveats).
- **Local ⇏ Global preservation:** established.
- **Dimension relevance shift:** introduced as a named failure mode.
- **Executable ≠ StructurallyAdmissible ≠ EpistemicallyValid:** established.
- **Partiality and domain composition:** stated.
- **ML firewall:** preserved.
- **Report discipline:** partial (witnesses shown; ExecutionRunID/certificate pending).

### Not yet done

- **R578.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R578.2** — dimension relevance predicate formalized.
- **R578.3** — loss interaction restated for $n$-stage chains.
- **R578.4** — invariants split into theorems/definitions/observations.
- **R578.5** — `~` typed as $\mathcal{O}$-relative observational equivalence.
- **R579** — composition involving **revision + acquisition + dependency**, not just transformations.
- **R580** — terminology freeze.
- **R581** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.

### Standing rules

- No new Kernel primitive.
- No new BC, no new layer.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- Report discipline: `ExecutionRunID` + `certificate.json` per test.
- ML cannot write to X.
- ML cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.
- `Greedy ≠ Optimal` under synergy.
- `AdmissionStatus` is a lattice.
- `BelnapValue` is a lattice.
- `Conflict ≠ Contradiction`.
- `RegimeDifference ≠ Conflict`.
- `Valid(B₁) ∧ Valid(B₂) ⇏ Valid(B₂ ∘ B₁)`.
- `Function ⊊ Typed ⊊ Epistemic` composition.
- `Undefined ≠ Rejected ≠ Unknown`.
- `LocalTargetPreservation ⇏ GlobalChainPreservation`.
- `Loss(T₂ ∘ T₁) ≠ Loss(T₁) ∪ Loss(T₂)`.
- `DimensionRelevant(d, Z, W)` is required for dimension-loss analysis.

### The single most important next thing

$$\boxed{\text{Build R578.1 (report discipline) and R579 (composition with revision + acquisition + dependency).}}$$

Because R578's theorem is the strongest structural result since R577, and its value depends on producing auditable certificates. R579 must test whether the theorem survives *non-transformation* operations — revision, acquisition, and dependency — that were not part of the R577/R578 framework.

### Two open questions I flag

1. **Can a revision invalidate a composition certificate without corrupting history?** Revision is not a transformation in the R577 sense. If a revision changes the target interpretation of a previous stage, does the composition certificate become stale? The correct answer should preserve the certificate as a historical artifact while flagging it as no longer valid for the current state. R579 must construct the test.

2. **Does the Global Composition Theorem hold when acquisition inserts new transformations?** If acquisition adds a stage between $T_i$ and $T_{i+1}$, does the theorem still apply? The answer likely depends on whether the inserted stage preserves the chain's target alignment. R579 must characterize the exact condition.