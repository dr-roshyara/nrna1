# Independent Review — R584 (Completeness Basis & Negative-Impact Assurance)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R584. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R584.
2. **What R584 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R584.1 / R585.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R584 is a **correct and important step**, and it makes five contributions:

$$\boxed{CompletenessClaim \neq EstablishedCompleteness}$$
$$\boxed{CompletenessStatus \in \{ESTABLISHED, CONDITIONAL, UNKNOWN\} \text{ — not FALSE}}$$
$$\boxed{ClosedWorldClaim \neq EstablishedClosedWorld}$$
$$\boxed{MLConfidence \neq CompletenessProof}$$
$$\boxed{\text{FalseNegativeImpact is the most dangerous failure mode}}$$

Each is correct. The first is the strongest: it refuses to let "we believe the graph is complete" become an epistemic fact without evidence. This is the same discipline that produced the Admission lattice (R574), the Belnap four-valued logic (R575), and the three-valued ImpactAssessment (R582). It is now the *fifth* time the project has introduced a scoped status vocabulary to prevent silent collapse of distinct states.

The second is the operational consequence: R584 deliberately avoids `FALSE` in the CompletenessStatus enum, because "not established" is genuinely different from "definitely incomplete." This preserves the invariant from R604.6:

$$Unknown \neq False$$

The fourth is the ML firewall restated at the completeness layer. A model producing `P(Dependency) < 0.01` does not produce completeness.

The fifth is the safety-critical operational shift. R584 correctly identifies that the most dangerous failure is no longer `FalseDependency` but `FalseNegativeImpact`, because it produces stale certificates that are silently assumed to be `CURRENT`. This is a new class of failure and it must be measured separately from ordinary classifier accuracy.

R584 also correctly preserves the L0–L6 architecture, refuses to add `COMPLETENESS_REQUIRED` as a lifecycle state, and refuses to add a `Completeness` Bounded Context or Kernel primitive.

**Seven residual issues**, each requiring correction before R585:

1. The `CompletenessBasis` tuple is introduced but not fully typed. The `Verification` field is the whole point of R584 and it is left as a placeholder.
2. `ESTABLISHED`, `CONDITIONAL`, `UNKNOWN` are introduced but the **lattice** is not stated. What is the meet/join behavior?
3. §13's `CONDITIONAL` is correct but the **type of the condition** is not stated. Is it a predicate, an assertion, a prior assessment?
4. §14 correctly refuses to add lifecycle states, but the **derivation** of `UseDecision` from the three axes is not typed.
5. §18's adversarial benchmark classes are listed but not formally constructed as test cases.
6. §19's `Negative Proof Validity` metric is proposed but its ground truth is not defined.
7. Report discipline: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`. This is 25+ rounds of accumulated debt.

---

# Part II — What R584 Actually Establishes

## 2.1 The completeness-claim distinction

R584 §1:

$$\text{CompletenessClaim} \neq \text{EstablishedCompleteness}$$

**Correct.** A claim is an assertion; established completeness is an assessed fact. The distinction parallels:

- `Certificate ≠ TruthCertificate` (R601)
- `Assessment ≠ Determination` (R601)
- `Admission ≠ Truth` (R574)
- `ImpactAssessment = UNKNOWN ≠ NOT_AFFECTED` (R582)
- `Candidate ≠ Established` (throughout)

The pattern is consistent across the series.

## 2.2 Three completeness bases

R584 §3–§6 examines three sources of completeness evidence:

1. **Closed-world** — the contract declares the universe is finite and enumerable.
2. **Exhaustive finite model** — a finite domain is exhaustively checked.
3. **Verified generator** — a generator is proven to cover the universe.

Each has a distinct evidence type:

- Closed-world: contractual assertion + independent verification.
- Exhaustive: finite enumeration proof.
- Generator: coverage proof.

**Correct.** These are genuinely different mechanisms, and each requires its own evidence.

## 2.3 ML cannot establish completeness

R584 §7:

$$MLConfidence \neq CompletenessProof$$

**Correct.** A model producing `P(Dependency) < 0.01` provides evidence that the dependency is unlikely, not that the dependency universe is complete.

**Real-world.** A fraud detector's low score on a transaction does not prove the transaction is not fraudulent; it says the model thinks it's unlikely. Only an exhaustive audit of the transaction's provenance would establish completeness.

## 2.4 Completeness is indexed

R584 §9:

$$Complete(D \mid Z, \Gamma, \Sigma)$$

**Correct.** There is no global completeness statement. Every completeness claim is relative to target, contract, and scope.

**Example.** A dependency graph may be complete for "voter eligibility in the 2026 German election" but not for "voter eligibility in EU elections."

## 2.5 The three-branch outcome

R584 §21:

$$\begin{array}{ccc} AFFECTED &\to& REVALIDATION\_REQUIRED \\ UNKNOWN &\to& INVESTIGATE/ACQUIRE \\ NOT\_AFFECTED &\to& NO\ REVALIDATION \end{array}$$

**Correct.** The third branch is only permitted when the completeness basis is established.

## 2.6 The FalseNegativeImpact concern

R584 §20:

$$HiddenDependency \to FalseNOT\_AFFECTED \to MissedRevalidation \to StaleCertificate$$

**Correct.** This is the most dangerous failure mode in the entire selective-revalidation machinery, and it is testable.

## 2.7 The acquisition connection

R584 §22:

$$Impact = UNKNOWN \to AcquisitionFrontier \to Observation \to CompletenessAssessment$$

**Correct.** This connects R584 to R549's acquisition machinery. When completeness cannot be established, acquisition can attempt to establish it.

## 2.8 Architecture discipline

R584 §15 states:

> No Completeness BC, no Dependency BC, no Impact BC, no Revalidation BC, no Completeness Kernel primitive.

**Correct.** Completeness is L3 (assessment) and L4 (verification), not a new layer.

---

# Part III — Seven Defects to Fix in R584.1 / R585

## Defect 1 — `CompletenessBasis.Verification` is not typed

R584 §2 defines:

$$CB = (Mode, Scope, Target, Contract, Coverage, Verification)$$

The `Verification` field is the whole point of R584, but it is left as a placeholder.

**Corrected typing.** `Verification` is itself a witness:

$$Verification = (Method, Evidence, Provenance, Signature, Time)$$

with:

$$Method \in \{\text{closed-world}, \text{exhaustive-finite}, \text{verified-generator}, \text{formal}, \text{adversarial}, \text{hybrid}\}$$

**Consequence.** A completeness basis is a *verification artifact*, not a Boolean flag. It carries provenance and can be re-audited.

## Defect 2 — The CompletenessStatus lattice is not stated

R584 §2 introduces:

$$\{ESTABLISHED, CONDITIONAL, UNKNOWN\}$$

but does not state the ordering or the operations on this set.

**Recommended lattice.**

$$UNKNOWN \sqsubset CONDITIONAL \sqsubset ESTABLISHED$$

with:

- **Meet** (used when combining multiple bases): the weakest link.
- **Join** (used when combining independent evidence): the strongest evidence.

**Rules:**

- `ESTABLISHED ∧ UNKNOWN = UNKNOWN` (weakest link governs).
- `CONDITIONAL ∧ UNKNOWN = UNKNOWN`.
- `CONDITIONAL ∧ ESTABLISHED = CONDITIONAL`.
- `ESTABLISHED ∧ ESTABLISHED = ESTABLISHED`.

**Note on FALSE.** R584 deliberately omits `FALSE`. The rationale (stated in §2) is that "not established" is different from "definitely incomplete." If a counterexample is found, the status becomes `UNKNOWN` (or better, a distinct `REFUTED` state, which R585 may add).

## Defect 3 — `CONDITIONAL` condition type is not stated

R584 §13 correctly notes that a conditional completeness claim requires verification of the condition. But the type of the condition is not stated.

**Corrected typing.**

$$CONDITIONAL(CB, \phi) \iff CB \text{ establishes completeness under condition } \phi$$

where:

- $\phi$ is a **predicate** on the world: e.g., "registry $R$ contains every admissible source."
- $\phi$ is a **declared assumption**: it enters the assumption validation machinery of L4.
- $\phi$ is evaluated on the current state: if $\phi$ holds, the status may promote to `ESTABLISHED`; if $\phi$ fails, status becomes `UNKNOWN`.

**Real-world.** "The dependency universe is complete assuming the source registry is up to date." If the registry's currency can be verified, the conditional promotes to established; otherwise it remains conditional.

## Defect 4 — `UseDecision` derivation is not typed

R584 §14 says:

$$UseDecision = f(ImpactAssessment, CompletenessAssessment, CertificateLifecycle, Contract)$$

but does not state $f$.

**Corrected definition.**

$$UseDecision = \begin{cases} \text{USABLE} & \text{if } I = NOT\_AFFECTED \land L = CURRENT \land CB = ESTABLISHED \\ \text{CONDITIONALLY\_USABLE} & \text{if } I = NOT\_AFFECTED \land L = CURRENT \land CB = CONDITIONAL \\ \text{NOT\_USABLE} & \text{if } I = AFFECTED \text{ or } L \in \{REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\} \\ \text{INVESTIGATE} & \text{if } I = UNKNOWN \end{cases}$$

**Consequence.** `UseDecision` is a *derived* function, not a persisted state. It recomputes whenever any of the four inputs changes.

## Defect 5 — Adversarial benchmark classes are not constructed

R584 §18 lists classes but does not construct them.

**Required in R584.1.** For each class, a concrete test:

- **Hidden direct dependency:** $E_1 \to C_5$ exists in ground truth, absent from $D$.
- **Common model (W3):** $E_1 \leftarrow M \to E_2$; the model link is hidden.
- **Common assumption (W4):** $E_1 \leftarrow A \to E_2$; the assumption link is hidden.
- **Common transformation (W5):** $E_1 = T(X_1)$, $E_2 = T(X_2)$; the transformation link is hidden.
- **Multi-factor (W7):** $Z = f(A, B, C)$; no single factor is individual evidence.
- **TPP-induced blindness:** $\pi$ loses a factor $F$ that $Z$ depends on.

Each must be built and tested for whether the system produces `FalseNOT_AFFECTED`.

## Defect 6 — NegativeProofValidity ground truth is not defined

R584 §19 proposes:

$$NegativeProofValidity = \frac{|\text{correct } NOT\_AFFECTED|}{|\text{all } NOT\_AFFECTED|}$$

But "correct" requires ground truth.

**Corrected definition.**

$$\text{Correct}(NOT\_AFFECTED(C)) \iff \text{no material impact path exists in the true graph } D^*$$

The true graph is available only for synthetic benchmarks. In production, NegativeProofValidity is measurable only against declared completeness bases — i.e., against their claimed scope.

**Consequence.** NegativeProofValidity is a *benchmark* metric; in production, the analogous signal is the *certificate of completeness* and its verification chain.

## Defect 7 — Report discipline

Same pattern. 25+ rounds of debt.

**Recommendation.** R584.1 must produce, for each test:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R584.1.<k>",
  "Method": "finite_exhaustive | hand_checked | property_based",
  "Expected": "<status>",
  "Actual": "<status>",
  "Status": "PASS | FAIL | UNKNOWN | CONDITIONAL | UNDEFINED | NOT_APPLICABLE",
  "Counterexample": "<if applicable>",
  "Certificate": { ... }
}
```

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
- **Type:** $T : X \rightharpoonup Y$.

### CompatibilityWitness (8-tuple)
- **Type:** $(src, tgt, conv, pre_{conv}, pre_{comp}, Z_w, Loss_w, \Gamma_w)$.

### Scope
- **Type:** $(Domain, Population, TimeRange, Regime)$.

### Regime (Γ)
- **Type:** $(Axioms, Semantics, Rules, Observation)$.

## R574–R583 (recap)

### Admission
- **Type:** $\{Admitted, Rejected, Conditional, Unknown\}$.

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### PreservationBridge
- **Type:** $B(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}} : Z_X(x) = Z_Y(T(x))$.

### GlobalPreserve
- **Type:** $\forall x \in W : Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$.

### EpistemicState
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.

### CertificateLifecycle
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

### AssessmentStatus
- **Type:** $\{PASS, FAIL, UNKNOWN, CONDITIONAL\}$.

### Transition Function
- **Type:** $\delta : (A, L, E, C) \rightharpoonup (A', L')$.

### ImpactAssessment
- **Type:** $\{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$.

### DependencyClosure
- **Type:** $Closure_D(E) = \{x : E \to^* x \text{ through material established edges}\}$.

### Complete_D, Complete_M, Complete_P
- **Types:** Three independent completeness predicates.

### ImpactPropagation (from R583)
- **Type:** $PropagatesImpact(e, C, \Delta, \Sigma) \iff \exists s' \in \Delta : Z_C(s') \neq Z_C(s)$.

## R584 new terms

### CompletenessClaim
- **Definition:** A statement that the dependency graph contains every relevant dependency for a target and scope.
- **Type:** An assertion, not yet a fact.
- **Real-world:** "The registry lists every source."
- **Invalid:** Treating a claim as established.
- **Invariant:** $CompletenessClaim \neq EstablishedCompleteness$.

### CompletenessBasis (corrected)
- **Definition:** The evidence or formal mechanism supporting a completeness claim.
- **Type:** $CB = (Mode, Scope, Target, Contract, Coverage, Verification)$ where $Verification$ is a witness tuple.
- **Real-world:** A closed-world contractual declaration with independent audit.

### CompletenessStatus (corrected lattice)
- **Definition:** The assessed status of a completeness basis.
- **Type:** $\{ESTABLISHED, CONDITIONAL, UNKNOWN\}$ with $UNKNOWN \sqsubset CONDITIONAL \sqsubset ESTABLISHED$.
- **Note:** No `FALSE`; incomplete claims produce `UNKNOWN`.

### CompletenessStatus Lattice Operations
- **Meet (weakest link):** $ESTABLISHED \sqcap UNKNOWN = UNKNOWN$; $ESTABLISHED \sqcap CONDITIONAL = CONDITIONAL$.
- **Join (strongest):** $ESTABLISHED \sqcup UNKNOWN = ESTABLISHED$.

### Verification (of CompletenessBasis)
- **Definition:** The witness that a completeness basis is valid.
- **Type:** $(Method, Evidence, Provenance, Signature, Time)$.
- **Method values:** `closed-world`, `exhaustive-finite`, `verified-generator`, `formal`, `adversarial`, `hybrid`.

### ClosedWorldClaim vs EstablishedClosedWorld
- **Invariant:** A closed-world claim requires verification of the universe's declaration.

### FiniteCompleteness vs UniversalCompleteness
- **Invariant:** Completeness established for a finite model does not extend to the universal domain.

### ConditionalCompleteness
- **Definition:** Completeness that holds only under a declared condition.
- **Type:** $CONDITIONAL(CB, \phi)$ where $\phi$ is a predicate on the world.
- **Invariant:** Promotes to `ESTABLISHED` if $\phi$ verified; remains `CONDITIONAL` otherwise.

### UseDecision (derived)
- **Definition:** Whether a certificate is usable for a specific purpose.
- **Type:** $f(ImpactAssessment, CompletenessAssessment, CertificateLifecycle, Contract)$.
- **Values:** $\{USABLE, CONDITIONALLY\_USABLE, NOT\_USABLE, INVESTIGATE\}$.
- **Invariant:** Derived, not persisted.

### NegativeImpactProof
- **Definition:** Proof that no impact path exists.
- **Type:** $NOT\_AFFECTED(C) \iff C \notin ImpactClosure_D(E) \land Complete(D, M, P \mid \Gamma, \Sigma, Z_C)$ with an established completeness basis.

### NegativeProofValidity (metric)
- **Definition:** The fraction of correct `NOT_AFFECTED` conclusions.
- **Type:** $\frac{|\text{correct } NOT\_AFFECTED|}{|\text{all } NOT\_AFFECTED|}$.
- **Ground truth:** Only against synthetic benchmarks.

### FalseNegativeImpact (metric)
- **Definition:** A `NOT_AFFECTED` conclusion when a material impact exists.
- **Type:** An erroneous $NOT\_AFFECTED$ conclusion.
- **Real-world:** A stale certificate incorrectly marked `CURRENT`.
- **Invariant:** The most dangerous failure mode in the selective revalidation pipeline.

### CompletenessAssessment
- **Definition:** An L3/L4 assessment that a completeness basis holds.
- **Type:** An assessment, not a new certificate type.
- **Rationale:** Uses existing machinery; no new primitive.

---

# Part V — Worked Examples

## Example 1 — No completeness basis

**Setup.**

- $D$ contains some edges.
- No completeness basis declared.

**Change.** $E_1$ revised.

**Result for $C$ not in closure.**

$ImpactAssessment(C) = UNKNOWN$, not `NOT_AFFECTED`.

**Consequence.** Conservative response: `C` may require revalidation under contract-conservative policy.

## Example 2 — Verified closed-world basis

**Setup.**

- Contract declares: "Only sources in registry $R$ can provide dependencies for this certificate."
- Registry $R$ is independently audited; audit certificate attached.

**Change.** $E_1$ revised.

**Result for $C$ not in closure.**

$ImpactAssessment(C) = NOT\_AFFECTED$. `CompletenessBasis = ESTABLISHED`.

## Example 3 — Unverified closed-world claim

**Setup.**

- Contract declares: "Only sources in registry $R$ can provide dependencies."
- No audit of $R$ exists.

**Change.** $E_1$ revised.

**Result for $C$ not in closure.**

$ImpactAssessment(C) = UNKNOWN$.

**Reason.** `ClosedWorldClaim ≠ EstablishedClosedWorld`.

## Example 4 — Exhaustive finite model

**Setup.**

- Universe $U = \{C_1, C_2, C_3\}$.
- Exhaustive enumeration of all $\binom{3}{2} = 3$ possible edges: none leads to $C_2$.

**Change.** $E_1$ revised.

**Result for $C_2$.**

$ImpactAssessment(C_2) = NOT\_AFFECTED$ (within the finite model).

**Caveat.** This does not extend beyond $U$.

## Example 5 — ML score does not produce completeness

**Setup.**

- ML emits `P(Dependency) < 0.01`.

**Incorrect inference.** `Complete_D = ESTABLISHED`.

**Correct handling.** The score is a prioritization signal; it does not produce completeness.

**Result.** `CompletenessBasis = UNKNOWN`.

## Example 6 — Conditional completeness

**Setup.**

- Basis: "Complete assuming registry $R$ is current."
- Registry $R$ not currently verified.

**Result.** `CompletenessStatus = CONDITIONAL`.

**Consequence.** For a `NOT_AFFECTED` claim, the status is `CONDITIONAL`; whether this is acceptable depends on the contract.

## Example 7 — Counterexample destroys completeness

**Setup.**

- Completeness declared for scope $\Sigma$.
- A hidden dependency $E_1 \to C_5$ (with $C_5 \in \Sigma$) is discovered.

**Result.** Completeness claim refuted. Status becomes `UNKNOWN` (or a distinct `REFUTED`).

**Consequence.** All previously asserted `NOT_AFFECTED` claims within $\Sigma$ require review.

## Example 8 — FalseNegativeImpact

**Setup.**

- $D$ declared complete for scope.
- Reality: hidden dependency $E_1 \to C_5$.
- $C_5$ asserted `NOT_AFFECTED`.

**Result.** A `FalseNegativeImpact` occurred. `C_5` may be stale.

**Metric.** This contributes to `FalseNegativeImpactRate`.

## Example 9 — UseDecision derivation

**Setup A.** $I = NOT\_AFFECTED$, $L = CURRENT$, $CB = ESTABLISHED$.

**Result.** `USABLE`.

**Setup B.** $I = NOT\_AFFECTED$, $L = CURRENT$, $CB = CONDITIONAL$.

**Result.** `CONDITIONALLY_USABLE`.

**Setup C.** $I = AFFECTED$, $L = REVALIDATION\_REQUIRED$.

**Result.** `NOT_USABLE`.

**Setup D.** $I = UNKNOWN$.

**Result.** `INVESTIGATE`.

## Example 10 — TPP-induced blind spot

**Setup.**

- $X = (F, G)$, $Z_C(F, G) = F$.
- $\pi(F, G) = G$.
- Two states $(0, 1)$ and $(1, 1)$ collapse under $\pi$ but differ in $Z_C$.

**Result.** $\neg TPP(\pi, Z_C)$. The transformed representation cannot establish `NOT_AFFECTED` about $F$-dependencies.

**ImpactAssessment.** `UNKNOWN`.

---

# Part VI — Architecture, ML Positioning, and Short Bullet Status

## VI.1 — Architecture (unchanged)

```
                KNOWLEDGEOS — Six-Layer Architecture
                              │
                     L0 Kernel (ID, R*, Sem)
                     Immutable epistemic history
                              │
                     L1 Semantic Fabric
             Meaning / Context / Scope / Contract / Regime
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        CompatibilityWitness / RegimeBridge / PreservationBridge
        CompositionWitness / TPP / Composition / Preservation / Loss
        DimensionRelevance
        DependencyGraph (typed edges: Established, Material, PropagatesImpact)
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality | ImpactPropagation
     CompositionStatus | EpistemicState | Revision | Acquisition
     ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}
     CompletenessAssessment ∈ {ESTABLISHED, CONDITIONAL, UNKNOWN}
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     ImpactAnalysis / Revalidation
     CompletenessVerification
     CompletenessCertificate (a certificate type)
     AssessmentStatus × ImpactAssessment × CertificateLifecycle
     × CompletenessStatus (4 × 3 × 4 × 3 = 144 states)
     GovernanceAuthority
                              │
                     L5 Intelligence
       CandidateImpact / CandidateDependency / CandidateAcquisition
       CandidateCompletenessSignal / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       UseDecision (derived)
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

**ML may do:**

- Propose `CandidateDependency(E, C, score)`.
- Propose `CandidateImpact(E, C, score)`.
- Propose `CandidateCompletenessSignal(D, Z, C, Σ)`.
- Propose `CandidateGap(D)` — a missing edge.

**ML may not do:**

- Assert dependency, materiality, propagation, or impact.
- Declare completeness.
- Produce `NOT_AFFECTED` or `AFFECTED` directly.
- Change lifecycle.
- Write to X.

**Firewall:**

```
ML candidate
  → TypeCheck
  → DependencyCheck
  → MaterialityCheck
  → PropagationCheck
  → CompletenessCheck (only if supporting an existing basis)
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Dependency}$$
$$\boxed{MLScore \not\Rightarrow ImpactAssessment}$$
$$\boxed{MLSignal \not\Rightarrow Completeness}$$
$$\boxed{LowMLScore \not\Rightarrow NOT\_AFFECTED}$$

**Adversarial benchmark classes for R585+:**

- Hidden direct dependency.
- Common model / source / assumption / transformation (W3–W5).
- Multi-factor hidden (W7).
- TPP-induced blind spot.
- Adversarial similarity (high score, no true dependency).
- Adversarial miss (low score, true dependency).

**Metrics:**

- Dependency Precision/Recall/FDR/FIR.
- Impact Recall/FNR.
- Missed Revalidation Rate.
- Negative Proof Validity.
- False Negative Impact Rate.
- Completeness Recall.
- Calibration Error.
- Abstention Quality.

**Cost-sensitive objective:**

$$Loss = C_{miss} \cdot FN + C_{false} \cdot FP$$

with $C_{miss} \gg C_{false}$ — declared by governance, not by ML.

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
- **Global compositional closure:** R578 passed finite tests.
- **Dependency + Acquisition + Revision + Composition:** R579 passed 10/10.
- **Certificate lifecycle + selective revalidation:** R580 passed 10/10.
- **Assessment × Lifecycle state machine:** R581 passed finite tests.
- **Selective revalidation impact closure:** R582 passed finite tests.
- **Negative impact proof conditions:** R583 passed finite tests.
- **Completeness basis formalized:** R584 passed finite tests.
- **CompletenessClaim ≠ EstablishedCompleteness:** established.
- **MLConfidence ≠ CompletenessProof:** established.
- **FalseNegativeImpact identified as most dangerous failure mode:** established.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R584.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R584.2** — `CompletenessBasis.Verification` typed.
- **R584.3** — CompletenessStatus lattice operations.
- **R584.4** — `CONDITIONAL` condition type.
- **R584.5** — `UseDecision` derivation.
- **R584.6** — Adversarial benchmark classes constructed.
- **R585** — Completeness Verification and Counterexample Calculus.
- **R586** — Step 545 full benchmark execution (W1–W7).
- **R587** — ML revalidation-priority benchmark.
- **R588** — full invariant engine.
- **R589** — terminology freeze.
- **R590** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Completeness composition:** open.
- **Verification of completeness bases (R585):** open and critical.

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
- `Revision ⇒ HistoryPreservation ∧ SelectiveRevalidation`.
- `Affected ≠ Invalid`.
- `EpistemicState ≠ AssuranceLifecycleState ≠ GovernanceAuthority`.
- `Acquisition ≠ Transformation ≠ Evidence`.
- `CurrentValidity ≠ HistoricalValidity`.
- `UNKNOWN → FAIL ⇒ ExplicitFailAssessment`.
- `Terminal lifecycle states cannot resurrect`.
- `Closure_D(E) ≠ TrueImpact(E)` when $D$ incomplete.
- `Dependency ≠ Impact`.
- `No-edge ≠ proven-unaffected` without completeness.
- `ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}`.
- `Complete_D ∧ Complete_M ∧ Complete_P` required for `NOT_AFFECTED`.
- `PositiveDetection is existential; NegativeProof is universal`.
- `CompletenessClaim ≠ EstablishedCompleteness`.
- `ClosedWorldClaim ≠ EstablishedClosedWorld`.
- `MLConfidence ≠ CompletenessProof`.
- `UNKNOWN ≠ FALSE` (also in completeness status).
- `FalseNegativeImpact is the most dangerous failure`.

### The single most important next thing

$$\boxed{\text{Build R584.1 (report discipline) and R585 (Completeness Verification and Counterexample Calculus).}}$$

Because R584 has correctly established that completeness claims are refutable, and R585 is where the *evidence standard* for completeness becomes precise. The four basis types (closed-world, exhaustive, generator, adversarial) require distinct verification mechanisms, and the counterexample search must be integrated with the falsification discipline that has served the project since R604.3.

The report discipline fix is now 25+ rounds overdue.

### Two open questions I flag

1. **Does a refuted completeness claim make previously asserted `NOT_AFFECTED` conclusions retroactively wrong, or does it only invalidate the *current* certificate?** The correct answer should preserve the historical record (the certificate was valid at the time given the evidence) while flagging that current use requires review. R585 must construct the test.

2. **What is the operational behavior when completeness is `CONDITIONAL` and the condition fails?** Does the certificate immediately become `REVALIDATION_REQUIRED`, or does it enter a distinct `CONDITION_FAILED` state? My recommendation: it triggers `REVALIDATION_REQUIRED` with an audit trail entry recording the condition failure. R585 must decide.