# R574.1 — Executable Regime Admission Calculus

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attached file. R574 is the object of review. R574 is conceptually correct but has not yet been executed; the current round must convert it into a running calculus, exactly as R602.4, R604.2, R604.4, R604.6–R604.9, and R548/R549 did for their respective subtheories. No external book needed; if a theorem demands it I will name it.

I will do six things:

1. **Verdict** on R574.
2. **What R574 established and what it did not.**
3. **Five precise defects** to fix in R574.1.
4. **Full term definitions** (extended).
5. **R574.1 specification** — the executable test suite and worked examples.
6. **Short bullet status.**

---

# Part I — Verdict on R574

R574 is the **correct next step** and its central distinction is right:

$$\boxed{Admission \neq Assessment \neq Determination}$$
$$\boxed{RegimeDifference \neq Conflict}$$
$$\boxed{Rejected \neq False}$$

It correctly rejects the R548 8-layer / 8-BC proposal and preserves the six-layer architecture. It also correctly identifies the deeper structure of transformation composition:

$$(X_0, \Gamma_0) \xrightarrow{T_1, w_1} (X_1, \Gamma_1) \xrightarrow{T_2, w_2} (X_2, \Gamma_2)$$

and requires the *regime* transition itself to be witnessed. That is a genuine advance over the earlier composition calculus, which typed only the state.

However, **R574 has not been executed**. Its Regime-Closure Proposition in §32 is a stated proposition, not a tested one. Its invariant set I-R01..I-R10 is stated, not enforced. Its §39 correctly lists "what we have not proven" — but that list must be closed by **execution**, not by another conceptual round.

R574.1 therefore must be the executable version, and it must obey the same discipline as R602.4 onward:

- report `ExecutionRunID` per test,
- print counterexamples,
- emit `certificate.json`,
- distinguish `hand_checked` from `finite_exhaustive` methods.

---

# Part II — What R574 Established and What It Did Not

## Established

- Admission is distinct from Assessment, Determination, Truth, and Utility (conceptual).
- Regime difference does not imply conflict (conceptual).
- Rejection does not imply falsehood (conceptual).
- Regime bridges can reuse the CompatibilityWitness mechanism without a new primitive (architectural).
- Architecture reduces to L0–L6 (architectural).
- Regime Closure requires witnessing each adjacent regime boundary (propositional).
- ML similarity does not establish semantic admission (architectural).

## Not established

- Any of the ten R574 benchmark worlds actually executed.
- The Regime-Closure Proposition tested on any composition.
- The invariant set I-R01..I-R10 enforced.
- The four-valued admission status `{Admitted, Rejected, Conditional, Unknown}` implemented.
- The ML firewall extended to regime candidates actually tested.

---

# Part III — Five Defects to Fix in R574.1

## Defect 1 — The four-valued admission status lacks a partial order

R574 §5 proposes:

$$Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$$

But no ordering is declared. This matters because composition must propagate admission: if $T_1$ is `Conditional` and $T_2$ is `Admitted`, what is $T_2 \circ T_1$?

**Recommended order (information lattice):**

$$\text{Rejected} \sqsubset \text{Unknown} \sqsubset \text{Conditional} \sqsubset \text{Admitted}$$

with the meet operation $\sqcap$ used at composition:

$$Adm(T_2 \circ T_1) = Adm(T_1) \sqcap Adm(T_2)$$

**Counterexample.** If $T_1$ is `Admitted` and $T_2$ is `Rejected`, the composition is `Rejected`. If $T_1$ is `Unknown` and $T_2$ is `Admitted`, the composition is `Unknown`. Without the lattice, composition is undefined.

**Real-world.** A Celsius-to-Fahrenheit conversion admitted in metric regime, composed with an unauthorized Fahrenheit-to-arbitrary mapping, yields a rejected composition — even though the first step was fine.

## Defect 2 — Regime Bridge is claimed to be a subset of CompatibilityWitness, but this is not proven

R574 §10 states:

$$RegimeBridge \subseteq CompatibilityWitness$$

This is correct *in direction* — the witness mechanism can carry regime information — but the claim needs a proof, or the subtyping must be made explicit.

**Recommended typing.**

Let the existing witness be:

$$w = (src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$$

Then:

$$RegimeBridge(\Gamma_1, \Gamma_2, Z, C) \equiv \exists w : w.\Gamma_w = (\Gamma_1 \to \Gamma_2) \land w.Z_w = Z \land pre_{comp}(w) \text{ holds}$$

The bridge is *not* a new type; it is a **predicate** on witnesses. This is the correct subtyping, and R574.1 must state it.

## Defect 3 — `Compatible(Γ₁, Γ₂, Z, C)` is under-specified

R574 §7 defines compatibility as "KnowledgeOS has a valid bridge." But "valid" is not defined. Is the bridge:

- Total on the declared domain?
- Loss-free for $Z$?
- Admitted under contract $C$?
- Preserving of all declared assumptions?

**Recommended definition.**

$$Compatible(\Gamma_1, \Gamma_2, Z, C) \iff \exists B : \text{Bridge}(B, \Gamma_1, \Gamma_2) \land \text{Adm}(B, \Gamma_2, C, S) \land TPP(\pi_B, Z \mid W_{\text{adm}})$$

That is: a bridge exists, the bridge is itself admitted, and the bridge preserves $Z$ on the admissible world.

**Counterexample where compatibility fails despite a bridge's existence.** A Celsius-to-Fahrenheit bridge exists, but if the target is *statistical distribution shape* rather than physical temperature, the bridge does not preserve $Z$: $N(\mu_C, \sigma_C^2)$ and $N(\mu_F, \sigma_F^2)$ have different shapes under the two scales.

## Defect 4 — R574's Regime-Closure Proposition is stated but not typed

R574 §32 states:

> If each adjacent pair is same-regime or bridged, then the sequence is regime-admissible.

This must be typed explicitly:

$$RegimeClosed(T_{1:n}, Z, C) \iff \forall i : [\Gamma_i = \Gamma_{i+1}] \lor Compatible(\Gamma_i, \Gamma_{i+1}, Z, C) \land TPP(T_{1:n}, Z)$$

And it must be tested against:

- a valid same-regime chain,
- a valid bridged chain,
- a chain with one missing bridge,
- a chain with a valid bridge but failed $TPP$,
- a chain with all bridges valid but one assumption violated.

Only after these tests can R574's Proposition be treated as established.

## Defect 5 — The ML experiment in §35 is described but not executed

R574 §35 correctly proposes that ML cannot distinguish admissible regime bridges from superficially similar invalid bridges. But this is a *claim about ML behavior*, and it must be tested empirically. The expectation:

$$\text{High Similarity} \not\Rightarrow Admission$$

must be shown by an actual benchmark, not asserted. R574.1 must include it.

---

# Part IV — Full Term Definitions

Each term: **Definition · Type · Real-world · Invalid · Invariant.**

## Kernel terms (unchanged)

### Identity (ID)
- **Definition:** Persistent unique name for an epistemic artifact.
- **Type:** $ID : \text{Artifact} \to \mathbb{N}$, injective.
- **Real-world:** ISBN.
- **Invalid:** ISBN changes with cover.
- **Invariant:** I-S01.

### Typed Relation (𝓡*)
- **Type:** $r : ID \times ID \to \text{RelationType}$.
- **Real-world:** `derivedFrom(E456, E123)`.
- **Invalid:** `relatedTo` without type.

### Semantics (Sem)
- **Type:** $Sem : ID \times \mathcal{R}^\star \times C \times \Gamma \to \text{Meaning}$.
- **Real-world:** "bank" in financial vs geographic context.

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
- **Real-world:** Meter-to-centimeter with metric-regime precondition.
- **Invalid:** Treating conversion as total.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules, Observation)$.
- **Real-world:** Bayesian probability with prior and likelihood; classical logic with material implication.
- **Invalid:** Mixing regimes without declaration.
- **Invariant:** Regime Isolation.

## Admission terms (new in R574)

### Admission
- **Definition:** The determination that an operation, evidence item, transformation, comparison, or inference is permitted to participate in a specified KnowledgeOS computation under a declared contract.
- **Type:** $Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$.
- **Real-world:** A Bayesian posterior is admitted only under Bayesian regime.
- **Invalid:** Treating admission as truth.
- **Invariant:** I-R04, I-R05.

### Admission Lattice
- **Definition:** Partial order on admission statuses.
- **Type:** $\text{Rejected} \sqsubset \text{Unknown} \sqsubset \text{Conditional} \sqsubset \text{Admitted}$.
- **Composition:** $Adm(T_2 \circ T_1) = Adm(T_1) \sqcap Adm(T_2)$.
- **Real-world:** A composition of a rejected and an admitted step is rejected.
- **Invalid:** Treating admission as Boolean.

### Regime Compatibility (corrected)
- **Definition:** Two regimes are compatible for target $Z$ under contract $C$ if there exists an admitted bridge preserving $Z$.
- **Type:**
$$Compatible(\Gamma_1, \Gamma_2, Z, C) \iff \exists B : \text{Bridge}(B, \Gamma_1, \Gamma_2) \land \text{Adm}(B, \Gamma_2, C, S) \land TPP(\pi_B, Z \mid W_{\text{adm}})$$
- **Real-world:** Celsius↔Fahrenheit for physical temperature.
- **Invalid:** Celsius↔Categorical scale for temperature (no such bridge).
- **Invariant:** I-R08.

### Regime Bridge
- **Definition:** A predicate on CompatibilityWitnesses asserting that the witness carries a regime transition.
- **Type:** $RegimeBridge(\Gamma_1, \Gamma_2, Z, C) \equiv \exists w : w.\Gamma_w = (\Gamma_1 \to \Gamma_2) \land w.Z_w = Z \land pre_{comp}(w)$.
- **Real-world:** Metric-to-imperial unit conversion.
- **Invalid:** Cross-domain composition without a bridge.
- **Invariant:** I-R02.

### Regime Closure
- **Definition:** A transformation chain is regime-closed if every adjacent pair is same-regime or bridged, and the composed chain preserves the target.
- **Type:**
$$RegimeClosed(T_{1:n}, Z, C) \iff \forall i : [\Gamma_i = \Gamma_{i+1}] \lor Compatible(\Gamma_i, \Gamma_{i+1}, Z, C) \land TPP(T_{1:n}, Z)$$
- **Real-world:** A pipeline that stays in one regime or uses documented translation steps.
- **Invalid:** Silent mixing of Bayesian and frequentist outputs.

### Conditional Admission
- **Definition:** Admission subject to declared preconditions not yet satisfied.
- **Type:** A status in the admission lattice.
- **Real-world:** "Admitted once provenance is verified."
- **Invalid:** Unconditional admission without evidence.

### Unknown Admission
- **Definition:** Insufficient information to determine admission.
- **Type:** A status in the admission lattice.
- **Real-world:** "Regime unknown; cannot admit or reject."
- **Invalid:** Treating unknown as rejected.

### Rejection
- **Definition:** Non-admission due to type, scope, regime, contract, assumption, provenance, or authority violation.
- **Type:** A status in the admission lattice.
- **Real-world:** A frequentist p-value offered as Bayesian evidence.
- **Invalid:** Rejection treated as truth-value.
- **Invariant:** I-R06.

## Assessment terms (recap)

### Assessment
- **Type:** $A = f(K, Q, C, \Gamma)$.

### Determination
- **Type:** $Det = (Q, \mathcal{A}, E, \Gamma, \rho, R)$.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.

### Certificate
- **Type:** $(Property, Scope, Method, Result, Provenance, Signature, Time)$.

### ExecutionRunID
- **Type:** Hash of (SpecID, CodeVersion, InputHash, Timestamp).

---

# Part V — R574.1 Specification

## V.1 — Data model

**Admission status as four-valued lattice:**

```python
class AdmissionStatus(Enum):
    REJECTED = 0
    UNKNOWN = 1
    CONDITIONAL = 2
    ADMITTED = 3

    def meet(self, other):
        return AdmissionStatus(min(self.value, other.value))
```

**Regime:**

```python
@dataclass(frozen=True)
class Regime:
    axioms: frozenset
    semantics: str
    rules: frozenset
    observation: str
```

**CompatibilityWitness (8-tuple):**

```python
@dataclass(frozen=True)
class CompatibilityWitness:
    src: str
    tgt: str
    conv: Callable
    pre_conv: Callable
    pre_comp: Callable
    Z_w: str
    Loss_w: frozenset
    Gamma_w: tuple[str, str]  # source regime, target regime
```

**Admission predicate:**

```python
def admit(x, K, C, Gamma, S) -> AdmissionStatus:
    checks = [
        type_check(x, C),
        scope_check(x, S),
        regime_check(x, Gamma, C),
        contract_check(x, C),
        assumption_check(x, C),
        provenance_check(x, C),
        authority_check(x, C),
    ]
    return meet_all(checks)  # lattice meet
```

## V.2 — Test suite (R1–R10)

| Test | Setup | Expected Status | Method |
|---|---|---|---|
| R1 | Same regime, matching scope | `ADMITTED` | finite_exhaustive |
| R2 | Celsius→Fahrenheit, $Z$=physical temp | `ADMITTED` | finite_exhaustive |
| R3 | Valid mathematical conversion | `ADMITTED` | finite_exhaustive |
| R4 | Incompatible regime (Celsius↔categorical) | `REJECTED` | finite_exhaustive |
| R5 | Missing bridge | `UNKNOWN` | finite_exhaustive |
| R6 | Scope mismatch | `REJECTED` | finite_exhaustive |
| R7 | ML high similarity, invalid mapping | `UNKNOWN` (candidate, not established) | finite_exhaustive |
| R8 | Same evidence, different logical regimes | `ADMITTED` with regime tag, not `CONFLICT` | finite_exhaustive |
| R9 | Valid bridge, target not preserved | `REJECTED` | finite_exhaustive |
| R10 | Valid bridge, target preserved | `ADMITTED` | finite_exhaustive |

**Plus:**

| Test | Setup | Expected | Method |
|---|---|---|---|
| R11 | Composition: same-regime × same-regime | `ADMITTED` | finite_exhaustive |
| R12 | Composition: admitted × rejected | `REJECTED` | finite_exhaustive |
| R13 | Composition: unknown × admitted | `UNKNOWN` | finite_exhaustive |
| R14 | Composition: conditional × admitted | `CONDITIONAL` | finite_exhaustive |
| R15 | Regime chain: all same regime | `RegimeClosed=True` | finite_exhaustive |
| R16 | Regime chain: one bridge, TPP holds | `RegimeClosed=True` | finite_exhaustive |
| R17 | Regime chain: missing bridge | `RegimeClosed=False` | finite_exhaustive |
| R18 | Regime chain: bridge exists but TPP fails | `RegimeClosed=False` | finite_exhaustive |
| R19 | ML regime-bridge candidates, similarity | `CANDIDATE` not `ESTABLISHED` | finite_exhaustive |
| R20 | Admission status reproducible (ExecutionRunID) | match | finite_exhaustive |

## V.3 — ML adversarial benchmark

For each of the following pairs, produce a candidate regime bridge and measure whether the ML score correctly predicts admission.

**Positives (Admissible):**
- Celsius → Fahrenheit (Z = physical temperature)
- Meters → Feet (Z = physical length)
- Kilograms → Pounds (Z = physical mass)
- Bayesian posterior → Bayesian posterior (same prior family, different parameterization)

**Negatives (Rejected):**
- Celsius → Population (Z = any)
- Meters → Political confidence (Z = any)
- Kilograms → Bayesian posterior (Z = statistical inference)
- Frequentist p-value → Bayesian posterior (Z = evidence)

**Adversarial (high similarity, but invalid):**
- Celsius → Fahrenheit but Z = "statistical distribution shape"
- Meters → Fahrenheit (both are "measurements," but dimensional mismatch)
- 95% CI → 95% credible interval (numerically identical, semantically different)

Expected result:

$$\boxed{High\ Similarity \not\Rightarrow Admission}$$

## V.4 — Report format (enforced)

Every test emits:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R574.1.R<k>",
  "Method": "finite_exhaustive | hand_checked | property_based",
  "Expected": "<status>",
  "Actual": "<status>",
  "Status": "PASS | FAIL | UNKNOWN | CONDITIONAL | UNDEFINED | NOT_APPLICABLE",
  "Counterexample": "<if applicable>",
  "Certificate": {
    "Property": "<I-Rk or R<k>>",
    "Scope": "<scope>",
    "Method": "<method>",
    "Provenance": "<chain>",
    "Signature": "<hash>",
    "Time": "<timestamp>"
  }
}
```

## V.5 — Worked examples (hand-verifiable, to be confirmed by the engine)

### Example 1 — Celsius/Fahrenheit admitted for physical temperature

$T_1$: Celsius → Fahrenheit, $Z$ = physical temperature.
Bridge: $F = \frac{9}{5}C + 32$.
TPP holds for $Z$ because the map is a bijection on $\mathbb{R}$.
Expected: `ADMITTED`.

### Example 2 — Celsius/Fahrenheit rejected for distribution shape

$T_1$: Celsius → Fahrenheit, $Z$ = shape of $N(\mu, \sigma^2)$.
The affine map changes both $\mu$ and $\sigma$, so the *shape* of the distribution is preserved only up to scaling, which the target does not declare admissible.
Expected: `REJECTED` (target not preserved).

### Example 3 — Composition of admitted and rejected

$T_1$: Celsius → Fahrenheit. `ADMITTED`.
$T_2$: Fahrenheit → Population. `REJECTED`.
Composition: `Admitted ⊓ Rejected = Rejected`.
Expected: `REJECTED`.

### Example 4 — Regime chain with missing bridge

$\Gamma_1 = \text{Celsius}$, $\Gamma_2 = \text{Fahrenheit}$, $\Gamma_3 = \text{categorical}$.
Bridge 1→2 exists; bridge 2→3 does not.
RegimeClosed = False.
Expected: `RegimeClosed = False`.

### Example 5 — ML adversarial: 95% CI vs 95% credible interval

Text similarity: high (both contain "95%").
Semantic similarity (embedding): high.
Semantic admission: rejected (they mean different things).
Expected: ML candidate is generated; L4 rejects it.
Expected: `CANDIDATE`.

---

# Part VI — Optimized Architecture (unchanged)

```
                KNOWLEDGEOS — Six-Layer Architecture
                              │
                     L0 Kernel (ID, R*, Sem)
                              │
                     L1 Semantic Fabric
             Context / Contract / Scope / Regime
             AdmissionContract / CompatibilityContract
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        CompatibilityWitness (with RegimeBridge predicate)
        Composition / PreservationTarget / Bridge
        Provenance / Lineage / CausalHistory
        DependencyFactor / FactorSet / Hyperedge
        Intervention / AcquisitionAction / AcquisitionPlan
                              │
                     L3 Epistemic Assessment
     Admission | Identifiability | InformationGain
     DeterminationGain | DecisionValue
     Dependency | Materiality | Minimality
     Zero (Unknown | Unobservable | Unidentifiable |
           Unacquired | Unavailable | Unvalidated)
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     RegimeClosureVerification / FirewallVerification
     CandidateQuarantine / Metamorphic / Bootstrap
                              │
                     L5 Intelligence
       Pairwise ML / Group ML / Embeddings
       RegimeBridgeCandidateGenerator
       AcquisitionPolicy P0–P3 / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       AdmissionAuthority / AcquisitionPermission
```

No L4.5. No L7. No L8. No new BC.

## The laws (R574 additions)

$$\boxed{Admission \neq Assessment \neq Determination}$$
$$\boxed{RegimeDifference \neq Conflict}$$
$$\boxed{Rejected \neq False}$$
$$\boxed{Unknown \neq Rejected}$$
$$\boxed{RegimeBridge \subseteq CompatibilityWitness}$$
$$\boxed{AdmissionStatus \text{ forms a lattice}}$$
$$\boxed{MLSimilarity \not\Rightarrow Admission}$$

Plus all previous laws (ML firewall, Pure ⇒ X' = X, Spec ≠ Run ≠ Result ≠ Cert, use weakest sufficient method, hand ≠ simulated ≠ executed, etc.).

---

# Part VII — Short Bullet Status

## Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency (multi-factor, target/scope/regime-relative):** executable.
- **No-information impossibility theorem:** established.
- **Information ≠ Identifiability:** established.
- **Greedy ≠ Optimal acquisition (XOR):** established.
- **Acquisition synergy ↔ multi-factor dependency:** unified.
- **Zero refined to six states:** established.
- **Admission ≠ Assessment ≠ Determination:** conceptual (R574).
- **Regime difference ≠ conflict:** conceptual (R574).
- **Rejection ≠ falsehood:** conceptual (R574).
- **Regime bridges ⊆ CompatibilityWitness:** proposed (R574).
- **Regime-Closure Proposition:** proposed (R574).
- **ML firewall:** preserved and extended to regime candidates (conceptual).

## Not yet done (executable)

- **R574.1** — executable Regime Admission Calculus (data model, R1–R20 tests, report discipline).
- **R574.2** — 10 regime/bridge/admission benchmark worlds executed.
- **R574.3** — adversarial ML regime-bridge benchmark executed.
- **R574.4** — regime-closure composition tests executed.
- **R575** — logical-regime boundary tests (classical, intuitionistic, paraconsistent).
- **R576+** — regime admission integrated with sequential acquisition.
- **R577** — VoI under noisy observations with synergy.
- **R578** — full invariant catalogue (I-S, I-T, I-E, I-X, I-A, I-G, I-R).
- **R579** — terminology freeze.
- **R580** — Theory Specification v1.0.

## Open (not "not proven")

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general; submodular case tractable.
- **Category-theoretic structure:** pending specific experiment.
- **Lattice for dependency types:** proposed ordering falsified; theory not falsified.

## Standing rules

- No new Kernel primitive.
- No new BC.
- No new layer.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- Report discipline: `ExecutionRunID` + `certificate.json` per test.
- ML cannot write to X.
- ML cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.
- `Greedy ≠ Optimal` under synergy.
- `AdmissionStatus` is a lattice, not a Boolean.

## The single most important next thing

$$\boxed{\text{Build R574.1. Execute R1–R20. Print certificates. Then attack with the ML adversarial regime-bridge benchmark.}}$$

Because R574 has articulated the correct theory of admission, and R574.1 is what converts "we claim X" into "the engine returned X, with counterexamples where applicable."

## Two open questions I flag

1. **Is the admission lattice the only reasonable order?** An alternative treats `Conditional` as incomparable with `Unknown`. R574.1 must test whether the four-valued lattice behaves correctly under composition; if not, the lattice must be revised.

2. **Does the ML adversarial regime-bridge benchmark behave as predicted?** The R574 §35 claim — that ML will assign high similarity to semantically invalid bridges — must be tested. If ML scores happen to be low for the invalid bridges, the prediction is wrong; if they are high, the firewall is required. Only the benchmark decides.

If you want to proceed, tell me:

- **(A)** R574.1 specification only (as above).
- **(B)** R574.1 code.
- **(C)** Both, in order.

My recommendation is **(C)**, with **report discipline first**, because R574.1's value depends on producing auditable certificates for every admission test.