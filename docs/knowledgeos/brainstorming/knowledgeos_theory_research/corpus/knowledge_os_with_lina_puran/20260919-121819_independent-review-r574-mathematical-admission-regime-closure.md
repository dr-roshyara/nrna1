
# Independent Review — R574 (Mathematical Admission and Regime Closure)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment; R574 is the object of review. No external research needed; if a gap appears I name the book.

I will do seven things:

1. **Verdict** on R574.
2. **What is actually established**, honestly scoped.
3. **Where R574 remains imprecise or wrong** — six items with corrections.
4. **Full term definitions**, extended to new objects.
5. **Worked examples** proving or refuting the central claims.
6. **ML positioning** and what R574.1 must do.
7. **Optimized architecture** and short bullet status.

---

# Part I — Verdict

R574 is a **correct and important step**, and it makes four substantive contributions:

$$\boxed{Admission \neq Assessment \neq Determination}$$
$$\boxed{RegimeDifference \neq EvidenceConflict}$$
$$\boxed{Rejected \neq False}$$
$$\boxed{Computational\ Composability \neq Semantic\ Composability}$$

Each is correct. The fourth is the most consequential: it identifies a gap that had persisted since R604, and it correctly rules out a class of invalid compositions that naive type systems would accept.

R574 also makes a **strong architectural decision** that should be named:

$$\boxed{\text{RegimeBridge} \subseteq \text{CompatibilityWitness}}$$

This avoids introducing a new primitive while capturing the new content. That is exactly the theory-compression discipline the project has been enforcing.

**Six residual issues**, each requiring correction before R574.1:

1. The four-valued admission status `{Admitted, Rejected, Conditional, Unknown}` is proposed but its **combining rules** are not stated. What is `Admitted ∧ Unknown`?
2. `Regime Compatibility` is proposed as $Comp_\Gamma(\Gamma_1, \Gamma_2, Z, C)$ but its relationship to TPP is not made explicit. It should be a special case.
3. §32's "Regime-Closure Proposition" is stated as a proposition but lacks an explicit proof.
4. §33's counterexample for meters → feet → temperature is correct but the argument that no bridge can exist is not given.
5. §39 lists what is proven vs not proven but does not distinguish "not proven" from "out of scope."
6. Report discipline still not fixed across the sequence — R574 inherits the pattern from prior rounds.

---

# Part II — What R574 Actually Establishes

## 2.1 The Admission distinction

R574 §5 defines:

$$Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$$

with:

$$Admission \neq Truth$$

This is correct and important. It parallels the same distinction already established for:

- `Certificate ≠ TruthCertificate` (R601)
- `ML Candidate ≠ Epistemic Fact` (R601)
- `Assessment ≠ Determination` (R601)
- `Rejected ≠ False` (R574)

The pattern is consistent. Admission is a *type-check on the epistemically permitted*, not a truth judgment.

## 2.2 The regime compatibility lemma

The Celsius-to-Fahrenheit example is clean:

$$\Gamma_C = (\text{Celsius}), \quad \Gamma_F = (\text{Fahrenheit})$$
$$F = \frac{9}{5} C + 32$$
$$Comp_\Gamma(\Gamma_C, \Gamma_F, \text{Temperature}, C) = \text{True}$$

The same-number-different-semantics example is also correct:

$$CI_{95} \neq CredibleInterval_{95}$$

even though both surface as `95%`.

These demonstrate that numerical equality is not semantic equality.

## 2.3 The composability counterexample

§33 states:

$$T_1 : \text{meters} \to \text{feet}, \quad T_2 : \text{feet} \to \text{temperature}$$

Even as raw functions, the composition is:

$$\text{meters} \to \text{temperature}$$

which is physically meaningless for any target $Z$ expressing a physical quantity. The document correctly notes:

$$FunctionCompositionDefined \not\Rightarrow EpistemicAdmission$$

**But** the argument that *no bridge can exist* is not given. The correct argument:

Suppose a bridge $B$ existed: $B : \text{feet} \to \text{temperature}$. Then $B$ would be a *dimensional transformation* from [L] (length) to [Θ] (temperature). No such physical transformation exists for any target $Z$ declaring physical meaning, because dimensional analysis forbids it (Buckingham π theorem). Therefore:

$$Comp_\Gamma(\text{length}, \text{temperature}, Z, C) = \text{False} \quad \forall Z \in \text{physical targets}$$

This is the correct proof. R574 should include it.

## 2.4 The regime-closure proposition

§32's proposition is stated:

$$\forall i : [\Gamma_i = \Gamma_{i+1} \lor Comp_\Gamma(\Gamma_i, \Gamma_{i+1}, Z, C)] \land TPP(T_{1:n}, Z) \Rightarrow Admitted(T_{1:n}, Z, C) = \text{True}$$

This is correct but requires proof. I will give it in §V.1.

---

# Part III — Where R574 Remains Imprecise or Wrong

## Error 1 — The four-valued admission status lacks combining rules

R574 §21 proposes:

$$\{Admitted, Rejected, Conditional, Unknown\}$$

But the operations on this set are not given. Specifically:

- What is `Admitted ∧ Unknown`?
- What is `Rejected ∧ Unknown`?
- What is `Admitted ∨ Rejected`?

Consider a chain of admission checks:

```
TypeOK = Admitted
ScopeOK = Admitted
RegimeOK = Unknown
ContractOK = Admitted
AssumptionOK = Rejected
```

The overall admission is not determined by any single status.

**Corrected rule.** Use a **lattice** with:

- $\text{Rejected} \sqsubseteq \text{Unknown} \sqsubseteq \text{Conditional} \sqsubseteq \text{Admitted}$ in some sense? No — this is wrong.

**Actual lattice.** The join (weakest link) and meet (strongest promotion) are:

- `Rejected` dominates: `Rejected ∧ x = Rejected` for all `x`.
- `Unknown` dominates over `Admitted` in the "conservative" reading: `Admitted ∧ Unknown = Unknown`.
- `Conditional` is between: `Admitted ∧ Conditional = Conditional`.
- `Admitted` is neutral.

**Join table** (for conjunction of conditions):

| ∧ | Admitted | Conditional | Unknown | Rejected |
|---|---|---|---|---|
| Admitted | Admitted | Conditional | Unknown | Rejected |
| Conditional | Conditional | Conditional | Unknown | Rejected |
| Unknown | Unknown | Unknown | Unknown | Rejected |
| Rejected | Rejected | Rejected | Rejected | Rejected |

This is the correct semilattice for admission combination. It must be stated.

## Error 2 — `Comp_Γ` should be defined as a special case of TPP

R574 §7 introduces:

$$Comp_\Gamma(\Gamma_1, \Gamma_2, Z, C)$$

But it is not related to TPP. This is a missed unification.

**Corrected definition.** The regimes $\Gamma_1, \Gamma_2$ are compatible for target $Z$ under contract $C$ iff there exists a bridge $B : \Gamma_1 \to \Gamma_2$ such that:

$$TPP(\pi_B, Z \mid W)$$

where $\pi_B$ is the projection induced by $B$ on the joint admissible world.

That is: the bridge preserves the target. Compatibility is a *special case* of target preservation via a specific projection.

**Consequence.** $Comp_\Gamma$ inherits all the TPP machinery: existence proofs, counterexamples, identifiability conditions.

## Error 3 — Regime-closure proposition needs proof

See §V.1.

## Error 4 — The "no bridge" argument for meters-to-temperature is missing

See §II.3.

## Error 5 — "Not yet proven" vs "out of scope" is not distinguished

R574 §39 lists:

> We have not proven: universal regime inference; universal semantic compatibility; automatic discovery of correct regime bridges; general identifiability from arbitrary observations; optimal acquisition for arbitrary environments; universally calibrated ML regime detection; universal validity of any particular logical regime.

But these are of two different kinds:

**Not proven** (there is a specific claim pending):
- Regime-closure proposition (stated, proof missing).
- Target-relative compatibility for specific bridges (testable).

**Out of scope** (no specific claim has been made):
- Universal regime inference — no one has proposed an algorithm that would infer any regime in any domain.
- Universally calibrated ML regime detection — impossible in general; calibration is population-relative.
- Universal validity of any logical regime — not a claim the project makes.

**Corrected presentation.** Split into:

```
Not yet proven (specific claims pending):
- Regime-closure proposition
- Bridge existence for specific target classes
- Regime compatibility of TPP-preserving bridges

Out of scope (no claim made):
- Universal regime inference
- Universally calibrated ML
- Universal logic validity

Open questions:
- Optimal regime-bridge discovery
- Complexity of admission for general contracts
- Composition of regime bridges
```

This is the same status discipline the project applies elsewhere.

## Error 6 — Report discipline still not fixed

The pattern persists across 10+ rounds. R574 reports conceptual claims and passes without `ExecutionRunID`, `certificate.json`, or per-test evidence.

**R574.1 must fix this**, or the executable claims remain described, not audited.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact.
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

### Regime (Γ) — refined
- **Definition:** Formal and semantic conditions under which an operation, inference, comparison, or assessment is interpreted.
- **Type:** $(Semantics, Logic, Mathematics, Observation, Assumptions, Scope, ValidityRules)$.
- **Real-world:** Bayesian regime, classical logic regime.
- **Invalid:** Equating with "which algorithm was used."

### Context — distinct from regime
- **Definition:** The situation in which an epistemic object exists.
- **Type:** $(Location, Date, Actor, Source, Event, Domain)$.
- **Real-world:** "Restaurant open at 20:00 on 18 Sep."
- **Distinction:** Same context can have different regimes.

## New terms (R574)

### Admission
- **Definition:** Determination that an operation, evidence item, transformation, comparison, or inference is permitted to participate in a specific computation.
- **Type:** $Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$.
- **Real-world:** A Bayesian posterior probability admitted into a decision; a frequentist p-value not admitted until translated.
- **Invalid:** Admitting ML candidates directly.
- **Invariant:** $Admission \neq Truth$; $Rejected \neq False$.

### Regime Compatibility (corrected)
- **Definition:** Two regimes are compatible for target $Z$ under contract $C$ iff a bridge exists that preserves $Z$.
- **Type:** $Comp_\Gamma(\Gamma_1, \Gamma_2, Z, C) \iff \exists B : \Gamma_1 \to \Gamma_2, TPP(\pi_B, Z \mid W)$.
- **Real-world:** Celsius-Fahrenheit for temperature.
- **Invalid:** Comparing CI and credible interval for the same target without translation.
- **Invariant:** I-R03, I-R08.

### Regime Bridge (corrected — subset of CompatibilityWitness)
- **Definition:** A formally justified mapping permitting an object in one regime to be interpreted in another.
-