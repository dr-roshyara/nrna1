# Independent Review — R577 (Target-Preserving Composition Theorem)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R577. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R577.
2. **What R577 actually establishes** (with the mathematical proof scoped correctly).
3. **Seven precise defects** to fix in R577.1 / R578.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R577 is the **strongest constructive step in the entire series**, and it makes five substantive contributions:

$$\boxed{TPP \neq \text{Target-Preserving Bridge}}$$
$$\boxed{\text{Conditional Composition Theorem: } Preserve(T_1,Z_X) \land Bridge(T_1,Z_X,Z_Y) \land Preserve(T_2,Z_Y) \Rightarrow Preserve(T_2 \circ T_1, Z_X)}$$
$$\boxed{LocalTargetPreservation \not\Rightarrow GlobalChainPreservation}$$
$$\boxed{Loss(T_2 \circ T_1) \neq Loss(T_1) \cup Loss(T_2) \text{ as semantic law}}$$
$$\boxed{\text{Contracts compose} \neq \text{steps compose}}$$

Each is correct. The second is the substantive result: it is a **theorem**, not an example, and it is proven by substitution. The fourth is the sharpest correction: it fixes the naive loss-union claim made multiple times in earlier rounds.

R577 also makes a decisive architectural point: **no new BC, no new layer, no new Kernel primitive, no new aggregate**. Everything needed for composition preservation is already in the existing L0–L6 stack. That is the correct result of a mature theory.

**Seven residual issues**, each requiring correction before R578:

1. The theorem's proof is correct, but the **quantification over $W$** is not stated. Is it "for all admissible $x$" or "for all $x$ in the declared state space"?
2. The distinction between TPP and Preservation Bridge is introduced but its **relationship is not typed**. Are they independent? Does one imply the other?
3. §8's loss-composition correction is correct, but the correct formula (interaction term) is not stated — only "loss must remain target-relative."
4. §11 claims "specification associativity up to observational equivalence" but does not type what $\sim$ means concretely.
5. §19's `TargetPreservationBridge` is proposed as a new object but immediately says "not a new primitive." This is correct but needs to be typed as a predicate on existing witnesses, not left ambiguous.
6. §20's proposed invariants I-X11..I-X15 mix theorems, definitions, and derived observations in one list.
7. Report discipline: again no `ExecutionRunID`, no `certificate.json`, no printed counterexamples. This is the same discipline debt since R604.3 and now spans 15+ rounds.

---

# Part II — What R577 Actually Establishes

## 2.1 The Conditional Composition Theorem — proof and scope

R577 §4–§5 states and proves:

**Theorem.** Let $T_1 : X \rightharpoonup Y$ and $T_2 : Y \rightharpoonup Z$. Let $Z_X : X \to V$, $Z_Y : Y \to V$, $Z_Z : Z \to V$. Suppose:

- (Typing) $T_1$'s output is compatible with $T_2$'s input under a witness.
- (Domain) For all $x$ in the declared admissible domain: $x \in Dom(T_1)$ and $T_1(x) \in Dom(T_2)$.
- (Bridge 1) $Z_X(x) = Z_Y(T_1(x))$ for all admissible $x$.
- (Bridge 2) $Z_Y(y) = Z_Z(T_2(y))$ for all $y = T_1(x)$, $x$ admissible.

Then for all admissible $x$:

$$Z_X(x) = Z_Z((T_2 \circ T_1)(x))$$

**Proof.** Substitution. Correct as stated.

**Scope caveat.** The theorem is **universal over the declared admissible domain**, but it is **not universal over all possible domains, regimes, and contracts**. If any of the four conditions fails for some $x$, the theorem does not apply to that $x$. This is a *conditional* theorem, and its power comes from the conditions being checkable.

**Real-world.** Currency conversion: EUR → USD with target "monetary value at 10:00", then USD → GBP with the same target. If both conversions use the rate at 10:00 and the target is valued at that timestamp, preservation holds. If either uses a different rate time, the theorem does not apply — and composition may fail.

## 2.2 The local-vs-global preservation lemma

R577 §19:

$$Preserve(T_1, Z_1) \land Preserve(T_2, Z_2) \not\Rightarrow Preserve(T_2 \circ T_1, Z)$$

unless an intermediate target bridge $Z_1 \leftrightarrow Z_2$ exists.

**Correct.** This is the negative counterpart to the positive theorem. Without $Z_1 = Z_2$ on the intermediate domain (or a bridge), preservation of the composition is not implied.

**Example.** $T_1$ preserves "amount", $T_2$ preserves "amount at timestamp". A composition may preserve "amount" but not "amount at timestamp" because $T_1$ dropped the timestamp.

## 2.3 The loss non-union correction

R577 §8:

$$Loss(T_2 \circ T_1) \neq Loss(T_1) \cup Loss(T_2)$$

as a universal semantic law.

**Correct.** The correct formula (from R604.4 Theorem 1) is:

$$Loss(T_2 \circ T_1) = Loss_1 \cup Loss_2 \cup Loss_{\text{interaction}}(T_1, T_2)$$

where the interaction term captures losses arising only from the pair (e.g., $T_2$ collapsing distinctions that $T_1$ preserved).

R577 restates this in the composition-preservation context and correctly notes the loss is **target-relative**. That is accurate.

## 2.4 The three-stage composition

R577 §10 extends the theorem to three stages:

$$Z_X(x) = Z_W(T_3(T_2(T_1(x))))$$

for all admissible $x$, given compatible bridges $Z_X \leftrightarrow Z_Y \leftrightarrow Z_Z \leftrightarrow Z_W$.

**Correct.** This generalizes by induction.

## 2.5 The strictness of the three-level hierarchy (inherited from R576)

R577 implicitly preserves R576's:

$$\text{Function} \subsetneq \text{Typed} \subsetneq \text{Epistemic}$$

but does not reprove strictness. R577.1 or R578 should give the two counterexamples:

- Level 1-only: a function composition where regimes differ and no bridge exists.
- Level 2-only: a typed composition that does not preserve the target (R576's $B_3$ corrected).

## 2.6 The architecture result

R577 §17 states:

> Existing Transformation + Compatibility Witness + Target + Scope + Regime + Contract + Assurance is sufficient.

**Correct.** No new layer, no new BC, no new primitive. The theorem is a *rule over existing objects*, not a new ontology. This is the strongest architecture-compression result since R601.

---

# Part III — Seven Defects to Fix in R577.1 / R578

## Defect 1 — Quantification over $W$ is implicit

The theorem is stated for "every admissible $x$", but "admissible" is not defined. Is it:

- The declared state space $W$?
- The admissible model state space $W_{M,O,A,C}$ from R601?
- A contract-specific admissible set?
- The domain of $T_1 \circ T_2$?

**Corrected statement.**

$$Preserve(T, Z, W) \iff \forall x, y \in W : T(x) = T(y) \Rightarrow Z(x) = Z(y)$$

and:

$$\text{Theorem: } \forall W, \forall T_1, T_2, \forall Z_X, Z_Y, Z_Z : \left[\text{four conditions hold on } W\right] \Rightarrow \forall x \in W : Z_X(x) = Z_Z(T_2(T_1(x)))$$

Without quantifying $W$, "preservation" is undefined.

## Defect 2 — TPP vs Preservation Bridge relationship is not typed

R577 §2 correctly distinguishes:

- **TPP:** $\pi(x_1) = \pi(x_2) \Rightarrow Z(x_1) = Z(x_2)$ (target is a function of the projection).
- **Preservation Bridge:** $Z_X(x) = Z_Y(T(x))$ (target interpreted before equals target interpreted after).

But it does not state how they relate.

**Corrected typing.**

$$\text{PreservationBridge}(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}}(T) : Z_X(x) = Z_Y(T(x))$$

TPP is a special case when:

- $T = \pi$ (a projection), and
- $Z_Y \circ \pi = Z_X$.

More generally:

$$TPP(\pi, Z) \iff \text{PreservationBridge}(\pi, Z, Z \circ \pi^{-1})$$

for a suitable inverse.

So: **Preservation Bridge generalizes TPP**. TPP is one form of preservation. R577 should state this.

## Defect 3 — The correct loss formula is stated informally

R577 §8 says loss is target-relative and loss union fails, but does not restate the correct formula:

$$Loss(T_2 \circ T_1) = Loss_1 \cup Loss_2 \cup Loss_{\text{interaction}}$$

R577.1 should restate this with the interaction term explicit.

**Real-world.** $T_1$ projects out `name` from a patient record. $T_2$ buckets `zip` into `region`. Individually, $Loss_1 = \{name\}$ and $Loss_2 = \emptyset$ (as a declared loss on $T_2$'s input). But the composition loses $name$ and $zip$-distinction. The interaction term is $\{zip$-distinction$\}$.

## Defect 4 — `~` for specification associativity is untyped

R577 §11:

$$((T_3 \circ T_2) \circ T_1) \sim (T_3 \circ (T_2 \circ T_1))$$

where $\sim$ is "observational/specification equivalence". But $\sim$ is not typed.

**Corrected typing (from R604.2).**

$$T_L \equiv_{\mathcal{O}} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$$

where $\mathcal{O}$ is a declared observation contract (subset of specification fields). With $\mathcal{O} = \{\text{Output}, \text{Scope}, \text{Regime}, \text{PreservationTarget}\}$, the two parenthesizations are observationally equivalent. With $\mathcal{O} = \{\text{Provenance}\}$, they may not be (the provenance DAG may differ).

## Defect 5 — `TargetPreservationBridge` is not typed as a predicate

R577 §19 says:

> The missing object is TargetPreservationBridge but not as a new primitive. It is a specialization/contractual role of the existing compatibility/preservation witness.

**Correct**, but needs to be typed:

$$TargetPreservationBridge(T, Z_X, Z_Y) \iff \exists w : CompatibilityWitness(w, T) \land w.Z_w = (Z_X, Z_Y)$$

The `TargetPreservationBridge` is a **predicate** on existing witnesses, not a new type.

## Defect 6 — Invariants mix theorems and definitions

R577 §20 lists I-X11..I-X15. But:

- I-X11 is a **theorem** (conditional composition).
- I-X12 is a **negative result** (local preservation not sufficient).
- I-X13 is a **definition** (partiality).
- I-X14 is a **proposition** (specification associativity).
- I-X15 is a **negative result** (loss not implying target failure).

**Recommendation.** Split into:

**Theorems:** I-X11 (conditional composition), I-X14 (specification associativity up to $\mathcal{O}$).
**Definitions:** I-X13 (partiality domain).
**Counterexample theorems:** I-X12 (local ≠ global).
**Derived observations:** I-X15 (loss ≠ target failure).

## Defect 7 — Report discipline

Same pattern. R577 reports:

```
R577 target-preserving composition tests: 10/10 passed
```

but does not surface:

- `ExecutionRunID` per test.
- `certificate.json`.
- Printed counterexamples.
- `Method` field.

The report does list the case names:

```
valid preservation bridge: ADMITTED
invalid preservation bridge: REJECTED; witness=1
scope mismatch: REJECTED
regime mismatch: REJECTED
temporal mismatch: REJECTED
loss accumulation: ADMITTED
partial-domain composition: UNDEFINED; witness=0
three-stage composition: ADMITTED
function associativity: PASS
specification associativity up to observational equivalence: PASS
```

That is better than prior rounds (counterexamples for the two failing cases are shown). But `ExecutionRunID` and per-test certificates are still missing.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms (unchanged)

### Identity (ID)
- **Definition:** Persistent unique name.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.

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

### Partial Transformation
- **Definition:** Defined only on a subset of $X$.
- **Type:** $Dom(T) \subseteq X$.
- **Real-world:** Division by nonzero denominator.
- **Invalid:** Assuming totality.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Temporal Scope
- **Type:** Time interval within which a scope or claim is valid.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules, Observation)$.

## R577 terms

### Source Representation
- **Type:** $X$.
- **Real-world:** EUR amount at 10:00.

### Target Representation
- **Type:** $Y$.
- **Real-world:** USD amount at 10:00.
- **Distinction:** Not to be confused with the epistemic target.

### Epistemic Target
- **Type:** $Z_X : X \to V$.
- **Real-world:** Monetary value at 10:00.

### Target Interpretation
- **Type:** $Z_Y : Y \to V$.
- **Real-world:** Monetary value represented in USD.

### Preservation Bridge (corrected)
- **Definition:** A declared correspondence between two target interpretations that holds on the admissible domain.
- **Type:** $B(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}}(T) : Z_X(x) = Z_Y(T(x))$.
- **Real-world:** EUR-value ↔ USD-value via exchange rate at the same timestamp.
- **Invalid:** Declaring a bridge without quantifying over the admissible domain.
- **Invariant:** I-X11.

### TPP (recap)
- **Definition:** If two states become indistinguishable after projection, the target must have the same value.
- **Type:** $TPP(\pi, Z) \iff \forall x_1, x_2 : \pi(x_1) = \pi(x_2) \Rightarrow Z(x_1) = Z(x_2)$.
- **Relationship:** PreservationBridge generalizes TPP.

### Admissible Composition (corrected)
- **Definition:** A composition that satisfies Typing, Compatibility, Preconditions, and TargetPreservation.
- **Type:** $Admissible(T_2 \circ T_1) \iff \text{Typing} \land \text{Compatibility} \land \text{Preconditions} \land \text{TargetPreservation}$.

### Composition Status
- **Values:** `{ADMITTED, REJECTED, UNDEFINED, UNKNOWN}`.
- **Distinction:** `UNDEFINED` when a precondition fails; `REJECTED` when a contract is violated; `UNKNOWN` when evidence is insufficient.

### Specification Equivalence (corrected)
- **Definition:** Two specifications are observationally equivalent under a declared observation contract.
- **Type:** $T_L \equiv_{\mathcal{O}} T_R \iff \forall f \in \mathcal{O} : f(T_L) = f(T_R)$.
- **Real-world:** Two different parenthesizations of a chain agree on Output, Scope, Regime, and PreservationTarget.

### Target Preservation Bridge (predicate, not new type)
- **Definition:** A predicate asserting that a CompatibilityWitness carries the target-preservation obligation.
- **Type:** $TPB(T, Z_X, Z_Y) \iff \exists w : CompatibilityWitness(w, T) \land w.Z_w = (Z_X, Z_Y)$.

### Local vs Global Preservation
- **Definition:** Local preservation concerns a single transformation; global preservation concerns a chain.
- **Type:** $Preserve(T_i, Z_i)$ vs $Preserve(T_1 \circ \cdots \circ T_n, Z)$.
- **Counterexample:** Local preservation does not imply global without bridge alignment.

## Term distinctions from R574/R575

### Admission
- **Type:** $\{Admitted, Rejected, Conditional, Unknown\}$.

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### Conflict vs Contradiction
- **Distinction:** Conflict requires a comparison contract; contradiction requires a logical regime.

### Comparison Contract
- **Type:** $(Subject, Time, Definition, Regime, Window, Scope, Granularity)$.

---

# Part V — Worked Examples

## Example 1 — Valid preservation bridge (physical temperature)

**Setup.**

- $T_1 : \text{Celsius} \to \text{Fahrenheit}$.
- $T_2 : \text{Fahrenheit} \to \text{Kelvin}$.
- $Z_X(x) = x$ (physical temperature in Celsius).
- $Z_Y(y) = \frac{5}{9}(y - 32)$ (back-converts to Celsius).
- $Z_Z(z) = z - 273.15$ (converts Kelvin to Celsius).

**Check.**

- Bridge 1: $Z_X(x) = Z_Y(F(x))$ where $F = \frac{9}{5}x + 32$. ✅.
- Bridge 2: $Z_Y(y) = Z_Z(K(y))$ where $K = \frac{5}{9}(y - 32) + 273.15$. ✅.

**Result.** Composition preserves $Z_X$.

## Example 2 — Invalid preservation bridge

**Setup.**

- $T_1(x) = x + 10$.
- $T_2(x) = 0$.
- $Z(x) = x$.

**Check.**

- Bridge 1: $Z(x) = ?$ No target change from $T_1$; trivially $Z_X(x) = Z_Y(T_1(x))$ if $Z_Y = Z_X$ applied to $T_1(x)$ interpreted as $x + 10$. Actually, for this to be a bridge, we need $Z_Y = Z_X$ and $Z_X(T_1(x)) = Z_X(x)$, i.e., $T_1$ preserves $Z$. Here $T_1$ does not preserve $Z$.
- Bridge 2: $Z_Y(0) = 0$ for all $y$, so $Z_Y(T_2(y)) = 0$ for all $y$. This does not equal $Z_Y(y) = y$ unless $y = 0$.

**Result.** $REJECTED$; witness $= 1$ ($Z(1) = 1$, $Z(0) = 0$).

## Example 3 — Scope mismatch

**Setup.**

- $T_1$: normalization trained on German customers, scope $S_1$.
- $T_2$: classification for EU-wide, scope $S_2$.
- $S_1 \subsetneq S_2$.

**Check.** Type matches. But $T_2$'s contract requires $S_1 = S_2$ or a scope-bridge.

**Result.** $REJECTED$.

## Example 4 — Regime mismatch

**Setup.**

- $T_1$ operates in $\Gamma_{\text{Celsius}}$.
- $T_2$ operates in $\Gamma_{\text{Kelvin}}$.
- No bridge exists.

**Result.** $REJECTED$.

## Example 5 — Temporal mismatch (currency)

**Setup.**

- $T_1 : \text{EUR} \to \text{USD}$ at 10:00.
- $T_2 : \text{USD} \to \text{GBP}$ at 14:00.
- Target: value at 10:00.

**Check.** Both transformations are individually valid at their own times. But Bridge 2 requires $\text{Time}(T_2) = \text{Time}(T_1)$ for the target "value at 10:00". Mismatch.

**Result.** $REJECTED$.

## Example 6 — Loss accumulation with target preservation

**Setup.**

- $Z(x) = x \bmod 2$.
- $T_1(x) = x + 10$.
- $T_2(x) = x - 10$.

**Check.**

- $Z(T_1(x)) = (x + 10) \bmod 2 = x \bmod 2$. ✅.
- $Z(T_2(T_1(x))) = (x + 10 - 10) \bmod 2 = x \bmod 2$. ✅.

**Result.** $ADMITTED$.

Even though $T_1$ and $T_2$ may declare losses on other dimensions, the target is preserved.

## Example 7 — Partial-domain composition

**Setup.**

- $T_1(x) = x + 10$.
- $Dom(T_2) = \{y : y \le 5\}$.

**Check.** $x = 0 \Rightarrow T_1(0) = 10 \notin Dom(T_2)$.

**Result.** $UNDEFINED$ with witness $x = 0$.

## Example 8 — Three-stage composition

**Setup.** $X \xrightarrow{T_1} Y \xrightarrow{T_2} Z \xrightarrow{T_3} W$ with bridges $Z_X \leftrightarrow Z_Y \leftrightarrow Z_Z \leftrightarrow Z_W$.

**Result.** By induction, $Z_X(x) = Z_W(T_3(T_2(T_1(x))))$ for all admissible $x$. $ADMITTED$.

## Example 9 — Function associativity

**Setup.** $f(x) = x + 1$, $g(x) = 2x$, $h(x) = x^2$.

**Check.** $(h \circ g) \circ f = h \circ (g \circ f)$ by definition of function composition.

**Result.** $PASS$.

## Example 10 — Specification associativity up to $\mathcal{O}$

**Setup.** Same functions, but different scope/regime metadata in the two parenthesizations.

**Check.** Under $\mathcal{O} = \{\text{Output}, \text{Scope}, \text{Regime}\}$, the two parenthesizations agree.

**Result.** $PASS$ (up to $\mathcal{O}$).

Under $\mathcal{O} \ni \text{Provenance}$: they may differ, because the provenance DAGs differ.

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
        Types / Operations / Transformations (partial)
        CompatibilityWitness / RegimeBridge / PreservationBridge
        CompositionWitness / TargetPreservationBridge (predicate)
        TPP / Composition / Preservation / Loss
        Three-level composition: Function ⊊ Typed ⊊ Epistemic
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     CompositionStatus {Admitted, Rejected, Undefined, Unknown}
     Zero (Unknown | Unobservable | Unidentifiable |
           Unacquired | Unavailable | Unvalidated)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     RegimeClosure / FirewallVerification
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

- Propose `CandidateBridge(Γ₁, Γ₂, score, Z, S)`.
- Propose `CandidateComposition(B₁, B₂, score, Z, S)`.
- Propose adversarial near-miss bridges (e.g., Celsius→Fahrenheit under $Z = $ distribution shape).
- Propose `CandidateRegime` for unlabeled sources.

**ML may not do:**

- Assert bridge validity.
- Assert composition validity.
- Assert regime identity.
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
  → ScopeCheck
  → PreconditionCheck
  → ProvenanceCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

**Concrete technique for adversarial benchmarks:**

Features: source regime signature, target regime signature, target $Z$, textual similarity, embedding similarity, dimensional-analysis features (for physical units), known-bridge corpus.

Model: gradient-boosted trees + embeddings. Justify: mixed types, small positive class.

Labels: synthetic admissible bridges (positives) and invalid bridges (negatives with high similarity).

Metrics: Bridge Precision, Recall, FDR, FIR, False Composition Rate, Calibration Error, Abstention Quality.

Cardinal rule:

$$\boxed{MLSimilarity \not\Rightarrow BridgeValidity}$$

## VI.3 — Short bullet status

### Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency:** executable, multi-factor, target/scope/regime-relative.
- **Admission, regime compatibility, regime bridge:** executable.
- **Logical regime boundaries:** R575 passed 10/10.
- **Cross-regime composition:** R576 passed 10/10.
- **Target-preserving composition:** R577 passed 10/10.
- **Conditional Composition Theorem:** proven by substitution.
- **TPP ≠ PreservationBridge:** distinguished.
- **Local preservation ⇏ global preservation:** established.
- **Loss composition has interaction term:** established.
- **Three-level strictness:** from R576; R577 preserves it.
- **ML firewall:** preserved.
- **Report discipline:** partially (counterexamples shown, ExecutionRunID/certificate missing).

### Not yet done

- **R577.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R577.2** — typed quantification over $W$ in the theorem.
- **R577.3** — TPP vs PreservationBridge typed relationship stated.
- **R577.4** — loss interaction term restated.
- **R577.5** — `~` typed as observational equivalence under $\mathcal{O}$.
- **R578** — composition under interacting scope + regime + time + target.
- **R579** — terminology freeze.
- **R580** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.

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
- `Valid(B₁) ∧ Valid(B₂) ⇏ Valid(B₂ ∘ B₁)` without additional conditions.
- `Function ⊊ Typed ⊊ Epistemic` composition.
- `Undefined ≠ Rejected ≠ Unknown`.
- `LocalTargetPreservation ⇏ GlobalChainPreservation`.
- `Loss(T₂ ∘ T₁) ≠ Loss(T₁) ∪ Loss(T₂)` in general.

### The single most important next thing

$$\boxed{\text{Build R577.1 (report discipline) and R578 (interacting scope/regime/time/target composition).}}$$

Because R577's theorem is the first universal theorem of the composition calculus, and its practical value depends on producing auditable certificates. R578 must test whether the theorem survives interaction effects that R577 treated separately.

### Two open questions I flag

1. **Does the Composition Theorem hold when scope, regime, time, and target all change simultaneously?** R577 tested them separately. R578 must construct a case where all four change and verify whether the theorem still applies, and if not, state the additional conditions.

2. **Is specification associativity under $\mathcal{O}$ = {Output, Scope, Regime, PreservationTarget} always achievable, or does the choice of $\mathcal{O}$ depend on the contract?** If $\mathcal{O}$ depends on the contract, associativity is contract-relative; if not, it is universal. R578 must decide.