# Independent Review — R576 (Cross-Regime Translation Composition)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R576. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R576.
2. **What R576 actually establishes**, honestly scoped.
3. **Seven precise defects** to fix in R576.1 / R577.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R576 is the **strongest conceptual step since R602.4**, and it makes four substantive contributions:

$$\boxed{Valid(B_1) \land Valid(B_2) \not\Rightarrow Valid(B_2 \circ B_1)}$$
$$\boxed{Computational \subsetneq Typed \subsetneq Epistemic \text{ composition}}$$
$$\boxed{Undefined \neq Rejected \neq Unknown}$$
$$\boxed{Composition: \mathcal{S}_1 \times \mathcal{S}_2 \rightharpoonup \mathcal{S}_{12}}$$

Each is correct. The second is the strongest: it names three distinct levels of composition that had been conflated in earlier rounds, and it orders them correctly by increasing admissibility requirements. The first is the negative result — the counterexample to the naive theorem — and it is the one that must survive R577.

**Seven residual issues**, each requiring correction before R577:

1. The three-level composition claim uses `⊊` (strict subset) without proving strictness. Are there cases in each level that are *not* in the next?
2. The counterexample in §4 uses $B_3(x) = 0$ and treats this as "computationally valid but epistemically invalid." This is correct, but the argument that $B_3$ is a *bridge* at all needs to be stated.
3. The composition condition in §13 uses $Bridge_Z(T_1, Z_X, Z_Y)$ but the bridge's own type is not restated. It should be the 8-tuple from R602.3A/R574.
4. §20 reports "Case 1: ADMITTED, Case 2: REJECTED, Case 3: UNDEFINED, Case 4: REJECTED, Case 5: REJECTED" — but only 5 cases are named, and the test count says 10/10. The mapping is unclear.
5. `ExecutionRunID`, `certificate.json`, and printed counterexamples are still not surfaced in the report. This is the same discipline debt flagged since R604.3.
6. The §17 ML claim ("ML confidence cannot substitute for compositional validity") is correct but asserted; it should be tested in R577's adversarial benchmark.
7. §23 claims "no new aggregate" — good — but the round has not actually proven that composition fits within existing aggregates. It has only avoided introducing new ones by fiat.

---

# Part II — What R576 Actually Establishes

## 2.1 The negative theorem

R576 §4 constructs:

$$B_3(x) = 0$$

which is defined on all of $\mathbb{R}$ but maps everything to 0. For target $Z(x) = x$:

$$Z(1) = 1 \quad\text{but}\quad Z(B_3(1)) = 0$$

So:

$$TPP(B_3, Z) = \text{False}$$

and therefore $B_3$ is not target-preserving. A composition $B_3 \circ B_1$ where $B_1$ is valid would fail the target-preservation check at the composition level.

**Correct.** This is a legitimate counterexample to:

$$Valid(B_1) \land Valid(B_2) \Rightarrow Valid(B_2 \circ B_1)$$

where "Valid" is interpreted as merely admissible-at-that-step without composition conditions.

## 2.2 The undefined-composition counterexample

R576 §7 constructs:

$$Pre_{B_4}(x) \iff x \le 5$$

and $B_1(0) = 10$. Then:

$$Pre_{B_4}(10) = \text{False}$$

So $B_4(B_1(0))$ is undefined. This is correctly distinguished from `Rejected`.

**Correct.** The three-way distinction `Rejected ≠ Undefined ≠ Unknown` is important, and R576 establishes it with a concrete example.

## 2.3 The three-level composition order

R576 §11 claims:

$$\text{Function} \subsetneq \text{Typed Transformation} \subsetneq \text{Epistemic Composition}$$

**Interpretation.** Each level is *more restrictive* than the previous — a composition valid at level $n+1$ is also valid at level $n$, but not vice versa.

**Does the document prove strictness?** No. It shows examples but does not give a case that is *strictly* at one level and not at the next. R577 must supply:

- A composition that is valid at Level 1 but not at Level 2 (e.g., $f: \mathbb{R} \to \mathbb{R}$, $g: \mathbb{R} \to \mathbb{R}$ where the regimes differ).
- A composition valid at Level 2 but not at Level 3 (e.g., $B_3$ above: type-checks but does not preserve the target).

Without these, the strict-subset claim is a slogan, not a theorem.

## 2.4 The composition preservation condition

R576 §13 proposes:

$$Preserve(T_1, Z_X) \land Bridge_Z(T_1, Z_X, Z_Y) \land Preserve(T_2, Z_Y) \Rightarrow Preserve(T_2 \circ T_1, Z_X)$$

This is *the same* preservation-composition theorem stated in earlier rounds (R604.4's four-condition version). R576 reproduces it in the regime-bridge context without extending it. That is fine — the point is that regime bridges fit into the existing theorem — but R576 should say so explicitly rather than presenting it as new.

**Correct statement.** This is the R604.4 Theorem 2, applied to regime-bridged transformations. It is not a new theorem; it is a **specialization** of an existing one.

## 2.5 The currency example

R576 §14 uses:

$$T_1 : \text{EUR} \to \text{USD}, \quad T_2 : \text{USD} \to \text{GBP}$$

with target $Z = $ "monetary value at declared timestamp". If the exchange rates are used at different times (10:00 and 14:00), the composition fails to preserve $Z$.

**Correct.** This is a clean example of a **temporal scope mismatch** that breaks preservation even though both transformations are individually valid. It reinforces the need for scope as a first-class object and adds temporal scope to the list of composition conditions.

---

# Part III — Seven Defects to Fix in R576.1 / R577

## Defect 1 — The three-level strictness is not proven

R576 §11 uses `⊊` without showing strictness.

**Corrected demonstration.**

- **Level 1 \ Level 2 example.** Let $f(x) = x$, $g(y) = y$ on $\mathbb{R}$, but with $\Gamma_f = $ classical logic and $\Gamma_g = $ paraconsistent logic. Function composition $g \circ f$ is well-defined. But no bridge exists between the regimes for the target $Z = $ "boolean truth value". So Level 1 holds; Level 2 fails.

- **Level 2 \ Level 3 example.** R576's own $B_3(x) = 0$: type-checks and is defined on all inputs; but does not preserve the target. So Level 2 holds; Level 3 fails.

R577 must include both.

## Defect 2 — The $B_3$ counterexample needs to establish that $B_3$ is a bridge

R576 §4 uses $B_3$ as if it were a bridge, but a bridge carries a regime transition:

$$B_3 : \Gamma_i \to \Gamma_j$$

If $\Gamma_i = \Gamma_j$ (i.e., $B_3$ is not a regime transition at all), it is not a bridge in the R574/R575 sense.

**Corrected setup.** $B_3$ must be defined with $\Gamma_i \neq \Gamma_j$ but the transformation fails target preservation. E.g.:

- $\Gamma_i$: "signed numeric regime"
- $\Gamma_j$: "absolute-value regime"
- $B_3(x) = |x|$ (a valid regime transition)
- $Z(x) = x$ (the sign matters)
- $B_3(1) = 1$, $B_3(-1) = 1 \neq -1$. So $TPP(B_3, Z) = \text{False}$.

This makes the counterexample structurally sound as a bridge-level failure.

## Defect 3 — The composition condition omits the witness type

R576 §13 writes $Bridge_Z(T_1, Z_X, Z_Y)$, but the bridge is not typed. The correct reference is the 8-tuple:

$$w = (src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$$

The condition should be:

$$Preserve(T_1, Z_X) \land \exists w : Bridge(w, Z_X, Z_Y, T_1) \land Preserve(T_2, Z_Y) \land \text{regimes compatible} \land \text{scopes compatible} \land \text{pre_comp holds} \Rightarrow Preserve(T_2 \circ T_1, Z_X)$$

This makes explicit what is being assumed.

## Defect 4 — The test count and case mapping are inconsistent

R576 §20 reports:

```
R576 cross-regime composition tests: 10/10 passed
Case 1: ADMITTED
Case 2: REJECTED
Case 3: UNDEFINED
Case 4: REJECTED
Case 5: REJECTED
```

Ten tests are reported but only five cases are printed. Either:

- There are ten cases (and five are omitted), or
- The report is inconsistent.

**Correction.** R576.1 must list all ten cases with their statuses, expected values, and counterexamples.

## Defect 5 — Report discipline

The pattern persists across R602.4, R604.3, R604.6–R604.9, R548, R549, R574, R575, R576. The report contains a summary line but not:

- `ExecutionRunID` per test.
- `certificate.json`.
- Printed counterexamples.
- `Method` field (`finite_exhaustive` / `hand_checked` / `property_based`).

**Recommendation.** R577 must enforce:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R577.<k>",
  "Method": "finite_exhaustive",
  "Expected": "<status>",
  "Actual": "<status>",
  "Status": "PASS | FAIL | UNKNOWN | CONDITIONAL | UNDEFINED | NOT_APPLICABLE",
  "Counterexample": "<if applicable>",
  "Certificate": { ... }
}
```

## Defect 6 — The ML claim is asserted, not tested

R576 §17 says:

> ML confidence cannot substitute for compositional validity.

This is a claim about ML behavior; it must be tested. The adversarial benchmark in §18 is the right test, but it is deferred to R577. R576 should state that its ML claim is *pending* R577.

## Defect 7 — "No new aggregate" is asserted, not demonstrated

R576 §23 states that no new aggregate is needed. But this is a claim about DDD modeling, and it requires showing:

- Where composition lives (which aggregate, which value object).
- How composition witnesses are stored.
- How composition certificates are attached.

**Recommendation.** R576.1 should specify:

- Composition lives in L2's Formal Fabric, in a `CompositionWitness` value object that extends `CompatibilityWitness`.
- Composition certificates attach to the existing `Certificate` entity.
- Composition assessments are `Assessment` entities in L3.

This is a concrete DDD placement, not merely a claim.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms (unchanged)

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact.
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
- **Type:** $f : X \to Y$.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules, Observation)$.

## R574 admission terms

### Admission
- **Type:** $Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$.

### Admission Lattice
- **Type:** $\text{Rejected} \sqsubset \text{Unknown} \sqsubset \text{Conditional} \sqsubset \text{Admitted}$.

### Regime Compatibility
- **Type:** $Compatible(\Gamma_1, \Gamma_2, Z, C) \iff \exists B : \text{Bridge}(B, \Gamma_1, \Gamma_2) \land \text{Adm}(B) \land TPP(\pi_B, Z \mid W)$.

## R575 logical-regime terms

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### Conflict
- **Type:** $Conflict(E_1, E_2, CC)$ where $CC$ is a comparison contract.

### Contradiction
- **Type:** $Contradiction_\Gamma(P, \neg P)$.

### Comparison Contract
- **Type:** $CC = (\text{Subject}, \text{Time}, \text{Definition}, \text{Regime}, \text{Window}, \text{Scope}, \text{Granularity})$.

## R576 new terms

### Bridge
- **Definition:** An admitted transformation allowing an artifact or assessment to move between two regimes.
- **Type:** $B : \Gamma_i \to \Gamma_j$.
- **Real-world:** Celsius → Fahrenheit.
- **Invalid:** A transformation without regime transition labeled as a bridge.

### Translation
- **Definition:** A bridge that maps expressions/representations/assessments across regimes while attempting to preserve a declared target.
- **Type:** $T_{\Gamma_i \to \Gamma_j}$.
- **Real-world:** EUR → USD with target "monetary value at declared timestamp."

### Target Preservation (recap)
- **Definition:** A transformation is target-preserving iff $Z(x) = Z(T(x))$ for all admissible $x$.
- **Type:** $Preserve(T, Z)$.

### Composition (three levels)
- **Level 1 — Function Composition:** $f \circ g$ is defined and computable.
- **Level 2 — Typed Transformation Composition:** $T_2 \circ_w T_1$ with a compatibility witness and matching regimes.
- **Level 3 — Epistemic Composition:** $T_2 \circ_w T_1$ preserves the declared target under the contract.
- **Type:** $\text{Function} \subsetneq \text{Typed} \subsetneq \text{Epistemic}$.
- **Real-world:** Executable composition (Level 1); type-correct composition (Level 2); target-preserving composition (Level 3).
- **Invariant:** Each level is more restrictive than the previous.

### CompositionWitness (new)
- **Definition:** A value object in L2 that extends CompatibilityWitness to carry composition-specific metadata.
- **Type:** $(w, \text{composition info})$ where $w$ is the base CompatibilityWitness.
- **Real-world:** Two bridges plus the declared target preservation obligation.
- **Invalid:** Composition without a witness.

### Composition Status
- **Type:** $\{ADMITTED, REJECTED, UNDEFINED, UNKNOWN\}$.
- **Distinction:** `UNDEFINED` when a precondition of an intermediate step is violated; `REJECTED` when a contract is violated; `UNKNOWN` when evidence is insufficient.
- **Real-world:** A chain that hits an undefined domain is `UNDEFINED`, not `REJECTED`.

### Scope Mismatch
- **Definition:** Two transformations with different declared scopes; composition may fail even if types match.
- **Type:** A specific failure mode of composition.
- **Real-world:** Currency conversions at different times.

### Temporal Scope
- **Definition:** The time interval within which a scope or claim is valid.
- **Type:** A component of Scope.
- **Real-world:** Exchange rate at 10:00 vs at 14:00.

---

# Part V — Worked Examples

## Example 1 — Valid bridge chain

**Setup.**

- $B_1 : \Gamma_1 \to \Gamma_2$ with $conv = \text{identity}$ and $Z_w = Z$.
- $B_2 : \Gamma_2 \to \Gamma_3$ with $conv = \text{identity}$ and $Z_w = Z$.
- Target $Z(x) = x$.

**Check.**

- $Preserve(B_1, Z) = \text{True}$.
- $Bridge_Z(B_1, Z, Z) = \text{True}$.
- $Preserve(B_2, Z) = \text{True}$.
- Regimes compatible.
- Scopes match.

**Result.** `ADMITTED`.

## Example 2 — Target loss counterexample (fixed $B_3$)

**Setup.**

- $\Gamma_1$ = signed numeric regime.
- $\Gamma_2$ = absolute-value regime.
- $B_3 : \Gamma_1 \to \Gamma_2$, $conv(x) = |x|$.
- Target $Z(x) = x$ (sign matters).

**Check.**

- $Z(1) = 1$, $Z(-1) = -1$.
- $B_3(1) = 1$, $B_3(-1) = 1$.
- $Z(B_3(-1)) = Z(1) = 1 \neq -1 = Z(-1)$.
- $TPP(B_3, Z) = \text{False}$.

**Result.** `REJECTED` at Level 3.

**Note.** This is the corrected $B_3$ that actually constitutes a regime bridge.

## Example 3 — Undefined composition

**Setup.**

- $B_1(x) = x + 10$.
- $B_4$ defined only for $x \le 5$.
- Input $x = 0$: $B_1(0) = 10$.

**Check.**

- $Pre_{B_4}(10) = \text{False}$.
- Therefore $B_4(B_1(0))$ is undefined.

**Result.** `UNDEFINED` with witness $x = 0$.

**Distinction from `REJECTED`.** No contract was violated; the composition simply is not defined on this input.

## Example 4 — Regime endpoint mismatch

**Setup.**

- $B_1 : R_1 \to R_2$.
- $B_5 : R_{99} \to R_4$.

**Check.**

- $Cod(B_1) = R_2 \neq R_{99} = Dom(B_5)$.
- No witness exists between $R_2$ and $R_{99}$.

**Result.** `REJECTED`. Composition is not well-typed.

## Example 5 — Currency with temporal mismatch

**Setup.**

- $T_1 : \text{EUR} \to \text{USD}$ using rate at 10:00.
- $T_2 : \text{USD} \to \text{GBP}$ using rate at 14:00.
- Target: monetary value at declared timestamp $t^*$.

**Check.**

- $T_1$ valid at 10:00 for $t^* = 10:00$.
- $T_2$ valid at 14:00 for $t^* = 14:00$.
- Composition requires both at $t^*$; the times disagree.

**Result.** `REJECTED` due to scope mismatch.

**Real-world.** This is exactly the class of error that the scope-as-first-class treatment prevents.

## Example 6 — Three-level composition hierarchy

**Level 1 only.** $f: \mathbb{R} \to \mathbb{R}$, $g: \mathbb{R} \to \mathbb{R}$ with different regimes and no bridge. Composition is computable but has no regime semantics. `Level 1 ✓, Level 2 ✗`.

**Levels 1–2 only.** $B_3(x) = |x|$ with regimes compatible. Composition is type-correct. But $TPP(B_3, Z) = \text{False}$. `Level 2 ✓, Level 3 ✗`.

**Levels 1–3.** Celsius → Fahrenheit → Kelvin with target physical temperature. `Level 3 ✓`.

This establishes strictness of the hierarchy.

## Example 7 — Embedding similarity ≠ legal equivalence

**Setup.**

- $T_1 : \text{Text} \to \text{Embedding}$.
- $T_2 : \text{Embedding} \to \text{SimilarityScore}$.
- Target: legal equivalence.

**Check.** Two legally different texts may have high similarity score. So $TPP(T_2 \circ T_1, \text{legal equivalence})$ can fail.

**Result.** `REJECTED` at Level 3.

**Reinforces.** `EmbeddingSimilarity ≠ SemanticIdentity`.

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
        Types / Operations / Transformations
        CompatibilityWitness / RegimeBridge
        CompositionWitness (new)
        TPP / Composition / Preservation / Loss
        Three-level composition:
          Function ⊊ Typed ⊊ Epistemic
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     CompositionStatus: {Admitted, Rejected, Undefined, Unknown}
     Zero
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
  → ScopeCheck
  → AssumptionCheck
  → ProvenanceCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

## VI.3 — R577 specification (short)

R577 should be **Composition Preserves Target — Finite Theorems and Counterexamples**.

Required tests:

- R577.1 Valid bridge × valid bridge → valid composite.
- R577.2 Valid × valid → composite loses target.
- R577.3 Valid × valid → composite undefined.
- R577.4 Non-associative witness composition.
- R577.5 Three-stage preservation.
- R577.6 Temporal scope mismatch.
- R577.7 Function associativity (algebraic).
- R577.8 Witness/specification associativity (up to equivalence).
- R577.9 Adversarial ML bridge chain.
- R577.10 Report discipline: full `certificate.json` per test.

Required theorems to state:

- Preservation-composition theorem (recap from R604.4, applied to bridges).
- Regime-closure proposition (recap from R574, applied to bridge chains).
- Strictness of three-level hierarchy (proof by two counterexamples: Level 1 \ Level 2, Level 2 \ Level 3).

## VI.4 — Short bullet status

### Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency:** executable, multi-factor, target/scope/regime-relative.
- **Admission, regime compatibility, regime bridge:** executable.
- **Logical regime boundaries:** R575 passed 10/10.
- **Cross-regime composition:** R576 passed 10/10.
- **Three-level composition hierarchy:** introduced; strictness not yet proven.
- **Target-preservation composition condition:** stated; equals R604.4 Theorem 2 specialized.
- **`Undefined ≠ Rejected ≠ Unknown`:** established by example.
- **ML firewall:** preserved across all steps.

### Not yet done

- **R576.1** — report discipline: `ExecutionRunID` + `certificate.json` per test; full test mapping.
- **R576.2** — strictness of three-level hierarchy proven by two counterexamples.
- **R576.3** — composition condition restated with typed witness.
- **R577** — composition preserves target; finite theorems and counterexamples.
- **R577.9** — adversarial ML bridge chain tested.
- **R578** — full invariant catalogue.
- **R579** — terminology freeze.
- **R580** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general; submodular tractable.
- **Belnap order choice (information vs truth):** R575.1 must decide.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified; specific cases may apply.

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

### The single most important next thing

$$\boxed{\text{Build R576.1 (report discipline) and R577 (composition preservation theorems and counterexamples).}}$$

Because R576's conceptual contribution is correct and its negative result is genuine, and the next decisive question — does the composition theorem actually hold under the corrected conditions? — can only be settled by an executable test with the corrected $B_3$ and typed witnesses.

### Two open questions I flag

1. **Is the strict-subset hierarchy claim `Function ⊊ Typed ⊊ Epistemic` universal, or does it depend on the declaration of "typed" and "epistemic"?** R576.2 must construct the exact counterexamples and state the level definitions in a way that makes the strictness provable.

2. **Does composition preserve the target under all admissible regimes, or only under specific ones?** R577 must characterize the regime classes under which preservation composes. If the answer is "only under declared-and-compatible regimes," then composition itself must be typed by regime.