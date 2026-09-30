# Independent Review — R582 (Selective Revalidation Impact Closure)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R582. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R582.
2. **What R582 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R582.1 / R583.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R582 is a **correct and important step**, and it makes four substantive contributions:

$$\boxed{Closure_D(E) \neq TrueImpact(E) \text{ when } D \text{ is incomplete}}$$
$$\boxed{C \notin Closure_D(E) \not\Rightarrow ProvenUnaffected(C)}$$
$$\boxed{ImpactAssessment \in \{AFFECTED, NOT\_AFFECTED, UNKNOWN\}}$$
$$\boxed{Completeness(D \mid Z, C, \Sigma) \text{ is a scoped property, not a global one}}$$

Each is correct. The third is the strongest: it names a *three-valued* impact status that had been implicit (two-valued: affected / not affected), and it correctly refuses to collapse `NoPathFound` into `NOT_AFFECTED`. This is the same discipline that produced the four-valued admission lattice (R574) and the Belnap four-valued logic (R575) — the theory consistently distinguishes "unknown" from "false."

The first and second together are the operational consequence: **an incomplete dependency graph cannot certify unaffectedness**. This is a genuinely useful negative result, and it directly connects R582 to W1–W7 from Step 545.

**Seven residual issues**, each requiring correction before R583:

1. The `NOT_AFFECTED` predicate is introduced but its **proof obligation** is not typed. When is a certificate proven unaffected?
2. `Complete(D \mid Z, C, \Sigma)` is defined informally; its **witness structure** (what evidence justifies a completeness claim?) is not stated.
3. §19's Missed Revalidation Rate (MRR) is a good metric, but the **ground truth** for "materially affected" is not defined.
4. §22 correctly proposes using existing certificate types rather than new ones, but the **mapping** from (target, existing certificate type) to completeness claim is not stated.
5. §23's four approaches to completeness (structural, formal, empirical, adversarial, hybrid) are listed but the **hybrid** is not defined.
6. §28's `ImpactAssessment` is proposed but the **relationship to R581's lifecycle** is not stated. Is `AFFECTED` a lifecycle trigger or an assessment?
7. Report discipline: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`. This is 23+ rounds of accumulated debt.

---

# Part II — What R582 Actually Establishes

## 2.1 The closure =/= impact result

R582 §10 and §12:

$$Closure_D(E) \neq TrueImpact(E)$$

when the dependency graph $D$ is incomplete.

**Correct.** This is the key negative result. Graph traversal finds what's in the graph; it cannot find what isn't in the graph.

**Example.** W5 (common transformation) creates a hidden dependency between two certificates. If the transformation lineage is not in $D$, traversal misses it. The certificate is materially affected but is not flagged.

## 2.2 No-edge ≠ proven-unaffected

R582 §7:

$$C \notin Closure_D(E) \not\Rightarrow ProvenUnaffected(C)$$

unless $D$ is complete for the relevant scope.

**Correct.** This parallels the invariant from R604.7:

$$\neg ObservedDependency \neq ProvenIndependent$$

The application to revalidation is natural and correct.

## 2.3 Three-valued impact status

R582 §28:

$$ImpactAssessment \in \{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$$

with the rule:

$$\text{NoPathFound} \Rightarrow UNKNOWN \text{ (unless completeness holds)}$$

**Correct.** This is the right status vocabulary.

**Why it matters.** A two-valued `{AFFECTED, NOT_AFFECTED}` would force the system to incorrectly assert `NOT_AFFECTED` when the graph is silent — producing a false certificate of safety.

## 2.4 The centrality of materiality

R582 §4:

$$Material(e, Z) \text{ is target-relative}$$

and:

$$Material(e, Z_1) \not\Rightarrow Material(e, Z_2)$$

**Correct.** This recapitulates the R604.8 discipline at the impact-closure level.

**Consequence.** Impact closure must be computed per target, not globally.

## 2.5 The dependency chain

R582 §14:

$$\text{EvidenceRevision} \to \text{DependencyDiscovery} \to \text{DependencyValidation} \to \text{MaterialityAssessment} \to \text{ImpactClosure} \to \text{RevalidationRequired} \to \text{Revalidation} \to \text{Assessment'} \to \text{CertificateLifecycle'}$$

**Correct.** This is the operational integration of R579–R581.

## 2.6 The architectural result

R582 §15 states:

> No new BC. No new layer. No new Kernel primitive.

**Correct.** The impact closure is a computation over existing objects. The three-valued status is an existing assessment type instantiated with a specific target.

---

# Part III — Seven Defects to Fix in R582.1 / R583

## Defect 1 — `NOT_AFFECTED` proof obligation is not typed

R582 §28 introduces `NOT_AFFECTED` but does not state what justifies it.

**Corrected statement.**

$$NOT\_AFFECTED(C, E) \iff \text{no path from } E \text{ to } C \text{ in } D \land Complete(D \mid Z_C, C, \Sigma)$$

where $Z_C$ is $C$'s target, $C$ is $C$'s contract, and $\Sigma$ is the declared scope.

**Without $Complete(D \mid Z_C, C, \Sigma)$:** the status is $UNKNOWN$, not $NOT\_AFFECTED$.

## Defect 2 — `Complete(D)` witness structure is not stated

R582 §11 defines completeness as a predicate over $(Z, C, \Sigma)$ but does not state what evidence justifies a claim of completeness.

**Corrected typing.**

$$Complete(D, Z, C, \Sigma) \iff \exists w : \text{Witness}(w, D, Z, C, \Sigma)$$

where a *witness of completeness* can be:

- **Structural:** an exhaustive enumeration of all dependency-producing relations within $\Sigma$, declared and verified.
- **Formal:** a theorem that the declared model covers all relevant dependencies.
- **Empirical:** a benchmark demonstrating high recall against ground truth, with a statistical confidence bound.
- **Adversarial:** an adversarial test demonstrating robustness to hidden dependencies.

The witness is a *certificate*, and like all certificates, it is scoped and carries provenance.

## Defect 3 — MRR ground truth is not defined

R582 §19 defines:

$$MRR = \frac{|\text{materially affected certificates not flagged}|}{|\text{materially affected certificates}|}$$

But "materially affected" must be defined against ground truth.

**Corrected definition.**

$$\text{MateriallyAffected}_\text{true}(C, E) \iff \text{the true dependency graph } D^* \text{ has a material path } E \to C$$

Ground truth $D^*$ is available only for synthetic benchmarks. In production, MRR cannot be computed directly; it can only be estimated against declared completeness conditions.

**Consequence.** MRR is a *benchmark metric* (evaluated against synthetic ground truth), not a production metric. In production, the analogous signal is the presence or absence of a completeness certificate.

## Defect 4 — Mapping to existing certificate types is not stated

R582 §22 says:

> Different target ≠ New architectural primitive.

**Correct.** But the mapping from target to existing certificate type is not stated.

**Corrected mapping.**

| Target | Certificate type |
|---|---|
| Dependency existence | Dependency Assessment |
| Dependency completeness | Assumption Validation + Conformance |
| Impact closure | Dependency Assessment with ImpactAssessment target |
| Revalidation result | Certificate with lifecycle transition |
| Use eligibility | Derived; not a certificate |

Each of these is expressible as an existing certificate type with a different target.

## Defect 5 — The hybrid completeness approach is not defined

R582 §23 lists five approaches but does not define the hybrid.

**Corrected definition.**

$$HybridCompleteness(D, Z, C, \Sigma) = \text{Structural} \land (\text{Formal} \lor \text{Empirical} \lor \text{Adversarial})$$

That is: a *structural* enumeration is always required (you must have declared the space), and at least one additional form of evidence (formal proof, empirical benchmark, or adversarial test) must support the completeness claim.

**Rationale.** Structural completeness alone is an assertion; combining it with independent evidence reduces the risk of a systematic blind spot.

## Defect 6 — `ImpactAssessment` vs lifecycle is not disambiguated

R582 §28 introduces `ImpactAssessment` but does not state how it relates to R581's `CertificateLifecycle`.

**Corrected distinction.**

- `ImpactAssessment` is an **L3 epistemic assessment** about a certificate's relationship to a revision.
- `CertificateLifecycle` is an **L4 assurance lifecycle state**.

The two are related but not the same:

$$\text{ImpactAssessment} = AFFECTED \Rightarrow \text{Lifecycle} \to REVALIDATION\_REQUIRED$$

But:

$$\text{ImpactAssessment} = NOT\_AFFECTED \not\Rightarrow \text{Lifecycle unchanged}$$

because the lifecycle may change for other reasons (e.g., contract revision, temporal expiry).

**Consequence.** Impact assessment is a *trigger* for lifecycle transitions, not the transition itself.

## Defect 7 — Report discipline

Same pattern. 23+ rounds of debt.

**Recommendation.** R582.1 must produce, for each test:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R582.1.<k>",
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

## R574–R581 (recap)

### Admission
- **Type:** $\{Admitted, Rejected, Conditional, Unknown\}$.

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### PreservationBridge
- **Type:** $B(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}} : Z_X(x) = Z_Y(T(x))$.

### GlobalPreserve
- **Type:** $\forall x \in W : Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$.

### DimensionRelevant
- **Type:** $DimensionRelevant(d, Z, W) \iff \exists x_1, x_2 \in W : d(x_1) \neq d(x_2) \land Z(x_1) \neq Z(x_2)$.

### EpistemicState
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.

### CertificateLifecycle
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

### AssessmentStatus
- **Type:** $\{PASS, FAIL, UNKNOWN, CONDITIONAL\}$.

### Transition Function
- **Type:** $\delta : (A, L, E, C) \rightharpoonup (A', L')$.

### Explicit Fail Assessment
- **Definition:** The event type that can produce `UNKNOWN → FAIL`.

### UseEligibility (derived)
- **Type:** $f(A, L, \text{Scope}, \text{Contract}, \text{Authority})$.

## R582 new terms

### Evidence (recap)
- **Definition:** An epistemic object used as input to an assessment.
- **Real-world:** A document, measurement, or record.

### Dependency (recap)
- **Definition:** A declared or assessed relationship through which a change in one object may affect another.

### DependencyEdge
- **Definition:** A typed edge in the dependency graph.
- **Type:** $e = (\text{source}, \text{target}, \text{kind}, \text{material}, \text{established})$.

### Materiality (recap)
- **Type:** $Material(e, Z)$ — target-relative.

### Established Dependency
- **Definition:** A dependency relationship that has passed L4 validation.
- **Distinction:** $CandidateDependency \neq EstablishedDependency$.

### PotentiallyAffected
- **Definition:** A certificate is potentially affected when an admissible path connects a changed object to it.
- **Type:** $PotentiallyAffected(C, E) \iff C \in Closure_D(E)$.

### ProvenUnaffected (corrected)
- **Definition:** A certificate is proven unaffected when no path exists from a change to it and the dependency graph is complete for the relevant scope.
- **Type:** $ProvenUnaffected(C, E) \iff C \notin Closure_D(E) \land Complete(D, Z_C, C, \Sigma)$.

### DependencyClosure
- **Definition:** All objects reachable from a changed object through established, material edges.
- **Type:** $Closure_D(E) = \{x : E \to^* x \text{ through material established edges}\}$.
- **Invariant:** $DependencyClosure \neq CausalTruth$.

### Dependency Completeness (corrected)
- **Definition:** A dependency relation is complete for $(Z, C, \Sigma)$ if every materially relevant dependency in that scope is represented.
- **Type:** $Complete(D, Z, C, \Sigma) \iff \exists w : \text{Witness}(w, D, Z, C, \Sigma)$.
- **Invariant:** Completeness is scoped, not global.

### Completeness Witness
- **Definition:** Evidence that a dependency model is complete for a declared scope.
- **Types:**
  - Structural: exhaustive enumeration.
  - Formal: theorem.
  - Empirical: benchmark against ground truth with confidence bound.
  - Adversarial: robustness to hidden-dependency injections.
  - Hybrid: structural + (formal | empirical | adversarial).

### ImpactAssessment (corrected)
- **Definition:** A three-valued assessment of whether a change affects a certificate.
- **Type:** $\{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$.
- **Rules:**
  - $C \in Closure_D(E) \Rightarrow AFFECTED$.
  - $C \notin Closure_D(E) \land Complete(D) \Rightarrow NOT\_AFFECTED$.
  - $C \notin Closure_D(E) \land \neg Complete(D) \Rightarrow UNKNOWN$.
- **Distinction:** Impact assessment is L3; lifecycle is L4.

### DependencyClosure vs TrueImpact
- **Invariant:** $Closure_D(E) \neq TrueImpact(E)$ when $D$ is incomplete.

### CompletenessCertificate
- **Definition:** A certificate that attests to $Complete(D, Z, C, \Sigma)$.
- **Type:** Existing certificate type with target = "completeness of $D$ for $(Z, C, \Sigma)$".
- **Note:** Not a new primitive; a specialization.

### ImpactRecall
- **Definition:** The fraction of truly materially affected certificates detected.
- **Type:** $\frac{|\text{true affected} \cap \text{detected}|}{|\text{true affected}|}$.

### MissedRevalidationRate (MRR)
- **Definition:** The fraction of materially affected certificates not flagged for revalidation.
- **Type:** $\frac{|\text{materially affected, not flagged}|}{|\text{materially affected}|}$.
- **Note:** Measurable only against synthetic ground truth or with a declared completeness certificate.

### DependencyCompleteness (four approaches)
- **Structural:** Exhaustive declaration.
- **Formal:** Proof.
- **Empirical:** Benchmark.
- **Adversarial:** Injection testing.
- **Hybrid:** Structural + at least one of the others.

---

# Part V — Worked Examples

## Example 1 — Direct dependency closure

**Setup.** $E_1 \to C_1 \to C_3$.

**Change.** $E_1$ revised.

**Result.** $Closure_D(E_1) = \{C_1, C_3\}$. Both `AFFECTED`.

## Example 2 — Cascading with materiality filter

**Setup.**

- $E_1 \xrightarrow{\text{material}} A_1 \xrightarrow{\text{material}} C_1$.
- $E_1 \xrightarrow{\text{immaterial}} C_2$.

**Change.** $E_1$ revised.

**Result.** $Closure_D(E_1)$ for material edges only $= \{A_1, C_1\}$. $C_2$ is not affected because the edge is immaterial.

## Example 3 — Missing edge, empty closure

**Setup.** True graph contains $E_1 \to C_5$, but this edge is not in $D$.

**Change.** $E_1$ revised.

**Result.** $Closure_D(E_1)$ does not include $C_5$. ImpactAssessment of $C_5$ = `UNKNOWN` (not `NOT_AFFECTED`) unless $D$ is certified complete.

## Example 4 — Completeness certificate with structural witness

**Setup.** For a specific scope $\Sigma$ (e.g., "all dependencies among sources in registry $R$"), $D$ is declared to include all dependency-producing relations.

**Witness.** A structural enumeration: the registry $R$ is exhaustive, and each source's dependency relations are declared.

**Result.** $Complete(D, Z, C, \Sigma)$ holds. $C \notin Closure_D(E)$ now implies `NOT_AFFECTED`.

## Example 5 — Incompleteness produces UNKNOWN, not NOT_AFFECTED

**Setup.** $D$ contains only some dependency relations. No completeness witness exists.

**Change.** $E_1$ revised.

**Result.** For $C$ not in $Closure_D(E_1)$, the assessment is `UNKNOWN`.

**Consequence.** $C$ may still require revalidation under conservative policy, even though it's not flagged by closure.

## Example 6 — Impact recall vs dependency recall

**Setup.**

- True dependencies: 100.
- Detected: 90.

**Dependency Recall** = 0.90.

But the 10 missed edges all sit upstream of major certificate cascades. Suppose they affect 50 certificates out of 200.

**Impact Recall** = 150 / 200 = 0.75.

**Missed Revalidation Rate** = 50 / 200 = 0.25.

**Conclusion.** Intermediate edge-level recall underestimates downstream error.

## Example 7 — Materiality filters spurious edges

**Setup.**

- $E_1 \xrightarrow{\text{material}} C_1$.
- $E_1 \xrightarrow{\text{immaterial}} C_2$.
- $E_1 \to C_3$ (no materiality declared).

**Change.** $E_1$ revised.

**Result.**

- $C_1$: `AFFECTED`.
- $C_2$: `NOT_AFFECTED` (immaterial edges don't propagate).
- $C_3$: `UNKNOWN` (materiality not declared).

**Corrected definition.**

$$Propagate(E, D) = \{C : MateriallyDepends(E, C)\}$$

Non-material edges do not propagate; undeclared-materiality edges produce `UNKNOWN`.

## Example 8 — The three-valued status

**Setup.**

- $C_1$: path exists from $E_1$. → `AFFECTED`.
- $C_2$: no path; $D$ is certified complete. → `NOT_AFFECTED`.
- $C_3$: no path; completeness unverified. → `UNKNOWN`.

**Corrected handling.** These three outcomes are distinct and must not collapse.

## Example 9 — Contract revision as impact source

**Setup.**

- $C$ issued under Contract $C_1$.
- Contract revision changes $C_2$ on a dimension relevant to $C$'s target.

**Change.** Not evidence revision, but contract revision.

**Result.** $C \to REVALIDATION\_REQUIRED$.

**Consequence.** Impact assessment must include contract changes, not only evidence changes.

## Example 10 — ImpactAssessment triggers lifecycle

**Setup.** `ImpactAssessment(C, E) = AFFECTED`.

**Result.** `Lifecycle(C) := REVALIDATION_REQUIRED`.

**Inference.** Impact assessment is a *trigger*, not a lifecycle state.

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
        DependencyGraph (separate from TransformationGraph)
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     CompositionStatus | EpistemicState | Revision | Acquisition
     ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     ImpactAnalysis / Revalidation
     AssessmentStatus × CertificateLifecycle State Machine
     CompletenessCertificate
     GovernanceAuthority
                              │
                     L5 Intelligence
       CandidateImpact / CandidateDependency / CandidateAcquisition
       CandidateBridgeGenerator / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       UseEligibility (derived)
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

**ML may do:**

- Propose `CandidateDependency(E, C, score)`.
- Propose `CandidateImpact(E, C, score)`.
- Propose `CandidateMateriality(E, C, score)`.
- Propose `CandidateCompletenessSignal(D, Z, C, \Sigma)`.

**ML may not do:**

- Assert dependency, impact, or materiality.
- Declare completeness.
- Trigger lifecycle transitions.
- Write to X.

**Firewall:**

```
ML candidate
  → TypeCheck
  → DependencyCheck
  → MaterialityCheck
  → CompletenessCheck (if claiming completeness)
  → CandidateQuarantine
  → L4 Validation
  → ImpactAssessment
  → Lifecycle transition via δ
```

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Dependency}$$
$$\boxed{MLCandidateImpact \not\Rightarrow ImpactAssessment}$$
$$\boxed{MLSignal \not\Rightarrow Completeness}$$

**Adversarial benchmark for R583+:**

- **Class A:** Direct dependency.
- **Class B:** Cascading dependency.
- **Class C:** Hidden common mode (W2, W3).
- **Class D:** Multi-factor hidden (W7).
- **Class E:** Adversarial similar-but-independent.
- **Class F:** Missing edge with high local signal.

**Metrics:** Dependency Precision, Recall, FDR, FIR, Impact Recall, MRR, Completeness Recall, Calibration Error, Abstention Quality.

**Cost-sensitive objective:**

$$Loss = C_{miss} \cdot FN + C_{false} \cdot FP$$

with $C_{miss} \gg C_{false}$ declared by governance, not by ML.

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
- **Closure ≠ TrueImpact when D is incomplete:** established.
- **No-edge ≠ proven-unaffected:** established.
- **Three-valued ImpactAssessment:** established.
- **Completeness as scoped property:** established.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R582.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R582.2** — `NOT_AFFECTED` proof obligation typed.
- **R582.3** — `Complete(D)` witness structure typed.
- **R582.4** — MRR ground-truth definition.
- **R582.5** — hybrid completeness definition.
- **R582.6** — ImpactAssessment vs lifecycle disambiguation.
- **R583** — Dependency Completeness and Negative Impact Proof.
- **R584** — Step 545 full benchmark execution (W1–W7).
- **R585** — ML revalidation-priority benchmark.
- **R586** — full invariant engine.
- **R587** — terminology freeze.
- **R588** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Cascade termination:** open.
- **Completeness witnesses for arbitrary dependency domains:** open.

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
- `No-edge ≠ proven-unaffected` without completeness.
- `ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}`.

### The single most important next thing

$$\boxed{\text{Build R582.1 (report discipline) and R583 (dependency completeness + negative impact proof).}}$$

Because R582 has correctly identified the core operational problem — when can KnowledgeOS legitimately assert `NOT_AFFECTED` — and R583 is where the answer becomes provable or refutable in a finite model. The report discipline fix is now the longest-running debt in the series.

### Two open questions I flag

1. **What is the practical form of a completeness witness in production?** Structural enumeration is realistic for closed systems (e.g., a specific registry of dependencies). For open systems, formal completeness may be impossible, and the fallback is empirical or adversarial. R583 must state the conditions under which each is admissible.

2. **Does completeness compose?** If $D_1$ is complete for scope $\Sigma_1$ and $D_2$ is complete for scope $\Sigma_2$, is $D_1 \cup D_2$ complete for $\Sigma_1 \cup \Sigma_2$? The answer is likely **no** in general (disjoint scopes may have cross-scope dependencies), so composition of completeness requires a condition similar to the preservation bridge in R577. R583 must address this.