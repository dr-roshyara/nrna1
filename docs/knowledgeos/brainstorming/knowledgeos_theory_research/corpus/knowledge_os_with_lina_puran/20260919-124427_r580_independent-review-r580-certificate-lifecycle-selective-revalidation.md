# Independent Review — R580 (Certificate Lifecycle & Selective Revalidation)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R580. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R580.
2. **What R580 actually establishes.**
3. **Seven precise defects** to fix in R580.1 / R581.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R580 is a **strong operational step**, and it makes four contributions:

$$\boxed{AssessmentStatus \neq CertificateLifecycle}$$
$$\boxed{CurrentValidity \neq HistoricalValidity}$$
$$\boxed{SelectiveRevalidation \text{ requires dependency completeness}}$$
$$\boxed{\text{EpistemicState} \times \text{Lifecycle} \times \text{GovernanceAuthority as orthogonal axes}}$$

Each is correct. The third is the most subtle and the most important: R580 correctly notes that selective revalidation depends on the **completeness** of the dependency graph, and it correctly refuses to conflate:

$$\neg ObservedDependency \neq ProvenIndependent$$

This is the same discipline the project has enforced since R604.7, applied now to certificate impact analysis. The consequence — that "no dependency observed" should produce a *diagnostic* ("no impact detected") rather than a *claim* ("no impact exists") — is the correct operational reading.

The fourth is the deepest architectural point: the round states explicitly that epistemic state, lifecycle, and governance authority are three independent dimensions. That is a refinement of the R579 separation and should be frozen.

R580 also exposes and refuses to guess a genuinely under-specified case (the `UNKNOWN` transition during revalidation). This is exactly the discipline the project has repeatedly benefited from.

**Seven residual issues**, each requiring correction before R581:

1. §5's lifecycle diagram is correct but the **transition table** is missing. R579 already flagged this; R580 does not fix it.
2. §8's "dependency completeness" assumption is stated but its **scope** is not typed. What does it mean for the graph to be "complete for the relevant scope"?
3. §12's cascading propagation says "typed" but does not state the **exact rule** for propagating impact along a typed edge.
4. §15 correctly refuses to guess the `UNKNOWN` transition but does not enumerate the **candidate semantics** for R581 to test.
5. §16's invariants again mix types (scope axiom, version-awareness definition, counterexample theorem, rule, observation).
6. §20's three-axis decomposition (EpistemicState × Lifecycle × GovernanceAuthority) is correct but the **cross product** is not enumerated.
7. Report discipline remains partial: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`.

---

# Part II — What R580 Actually Establishes

## 2.1 The status-vs-lifecycle separation

R580 §3 and §20:

$$\text{AssessmentStatus} \in \{\text{PASS}, \text{FAIL}, \text{UNKNOWN}, \text{CONDITIONAL}, \text{UNDEFINED}, \text{NOT\_APPLICABLE}\}$$
$$\text{Lifecycle} \in \{\text{CURRENT}, \text{REVALIDATION\_REQUIRED}, \text{SUPERSEDED}, \text{EXPIRED}\}$$

These are **independent dimensions**. A certificate can be `PASS + REVALIDATION_REQUIRED` — the claim still holds, but a revision has touched the dependency set and revalidation has not run.

**Correct.** This parallels the R575 separation of `AdmissionStatus` from `BelnapValue`, and the R574 separation of `Admission` from `Truth`.

## 2.2 The selective revalidation rule

R580 §6:

$$E' \in Dep(C) \land \text{material revision} \Rightarrow Lifecycle(C) \to \text{REVALIDATION\_REQUIRED}$$
$$\text{Assessment}(C) \text{ unchanged until revalidation}$$

**Correct.** Revalidation is a *lifecycle event*, not an *assessment event*. The assessment status is preserved until revalidation produces a new assessment.

## 2.3 The dependency-completeness caveat

R580 §8:

$$E' \notin Dep(C) \Rightarrow C \text{ does not require revalidation}$$

is **conditional on the completeness of the dependency graph for the relevant scope**. R580 correctly warns:

$$\neg ObservedDependency \neq ProvenIndependent$$

and requires the system to distinguish "no dependency established" from "independence established."

**Correct.** This is the same discipline from R604.7 (dependency), R604.8 (materiality), and R604.9 (multi-factor). It is applied here to certificate impact analysis.

## 2.4 Current vs historical validity

R580 §11:

$$\text{CurrentValidity} \neq \text{HistoricalValidity}$$

A certificate that was `CURRENT` at $t_1$ and `SUPERSEDED` at $t_2$ did **not** become invalid at $t_1$. It became `SUPERSEDED` at $t_2$ because later information arrived.

**Correct.** This preserves the audit trail and prevents the silent rewriting of epistemic history.

## 2.5 The three-axis decomposition

R580 §20:

$$\text{EpistemicState} \times \text{Lifecycle} \times \text{GovernanceAuthority}$$

**Correct.** Each axis answers a different question:

- **EpistemicState:** What do we believe?
- **Lifecycle:** What is the operational status of the assurance artifact?
- **GovernanceAuthority:** What is the artifact permitted to influence?

These must not collapse.

## 2.6 The unresolved `UNKNOWN` transition

R580 §15 correctly refuses to guess whether:

$$UNKNOWN \text{ during revalidation} \Rightarrow \text{REVALIDATION\_REQUIRED} \lor \text{CURRENT} \lor \text{SUPERSEDED}$$

and defers to R581. This is the correct discipline: do not encode semantics that the contract should decide.

---

# Part III — Seven Defects to Fix in R580.1 / R581

## Defect 1 — The lifecycle transition table is still missing

R579.1 was supposed to fix this. R580 does not.

**Corrected table:**

| From \ To | CURRENT | REVALIDATION_REQUIRED | SUPERSEDED | EXPIRED |
|---|---|---|---|---|
| CURRENT | self | on material revision | on contract supersession | on time expiry |
| REVALIDATION_REQUIRED | on pass | self (if revalidation yields UNKNOWN and contract requires re-queue) | on fail | on time expiry |
| SUPERSEDED | ✗ (terminal) | ✗ | self | ✗ |
| EXPIRED | ✗ (terminal) | ✗ | ✗ | self |

R580.1 must include this. Without it, the lifecycle is not a state machine.

## Defect 2 — "Dependency completeness for the relevant scope" is not typed

R580 §8 says selective revalidation depends on dependency completeness, but it does not define what "completeness" means.

**Corrected definition:**

$$Complete(G_E, C, S) \iff \forall E : Dep(E, C) \Rightarrow E \in G_E \text{ within scope } S$$

where $G_E$ is the epistemic dependency graph.

**Sharper statement.** Selective revalidation is sound if and only if:

$$Complete(G_E, C, S)$$

If completeness cannot be verified, the correct operational response is:

$$Lifecycle(C) \to \text{REVALIDATION\_REQUIRED}$$

for any revision within the certificate's *possible* dependency scope — a conservative overapproximation, not an underapproximation.

## Defect 3 — Cascading propagation rule is not typed

R580 §12 correctly says propagation must be "typed," but does not state the rule.

**Corrected rule.** Define the propagation as a function over typed edges:

$$\text{Propagate}(E', G_E) = \{C : E' \in Dep(C) \text{ materially}\}$$

where material membership requires:

$$MateriallyDepends(E, C) \iff E \in Dep(C) \land \text{the edge's declared materiality is } \text{True}$$

Non-material edges do not propagate.

**Consequence.** A revision at $E_1$ propagates through $A_1 \to C_1 \to C_2$ only if each intermediate edge is declared material. Otherwise, propagation stops.

## Defect 4 — The `UNKNOWN` transition candidates are not enumerated

R580 §15 correctly refuses to guess, but does not enumerate the candidates for R581.

**Recommended candidates:**

**Candidate A (Conservative).** `UNKNOWN` during revalidation ⟹ `REVALIDATION_REQUIRED` (stay queued).

- Rationale: cannot establish validity; keep re-validating.
- Risk: infinite loop if revalidation always yields `UNKNOWN`.

**Candidate B (Optimistic).** `UNKNOWN` during revalidation ⟹ `CURRENT` (retain prior lifecycle).

- Rationale: prior certificate is still the best evidence; no new failure.
- Risk: stale certificate persists.

**Candidate C (Pessimistic).** `UNKNOWN` during revalidation ⟹ `SUPERSEDED` (retire certificate).

- Rationale: cannot confirm validity; retire to avoid staleness.
- Risk: valid certificates retired unnecessarily.

**Candidate D (Contract-dependent).** The certificate's contract declares the transition.

- Rationale: this is what R580 §15 hints at.
- Risk: too many contracts to write.

R581 must pick one (or support contract-dependent selection with a default) and test it.

## Defect 5 — Invariants again mix types

R580 §16 lists I-C01..I-C07. But:

- **I-C01** (scope-indexed): **axiom**.
- **I-C02** (version-aware): **definition**.
- **I-C03** (`Affected ⇏ Failed`): **counterexample theorem**.
- **I-C04** (material revision ⟹ revalidation): **rule**.
- **I-C05** (`Expired ⇏ False`): **observation**.
- **I-C06** (history append): **axiom**.
- **I-C07** (`UNKNOWN ⇏ FAIL`): **rule**.

**Recommendation.** Split into axioms, definitions, rules, theorems, observations — the same taxonomy the project has used since R574.

## Defect 6 — Three-axis cross product is not enumerated

R580 §20 proposes the three axes but does not enumerate the cross product.

**Recommended:** the three axes are *independent* — every combination is logically possible. The cross product is:

$$|\text{EpistemicState}| \times |\text{Lifecycle}| \times |\text{GovernanceAuthority}|$$

With the values listed:

$$4 \times 4 \times k$$

for some $k$ governance-authority values.

R580.1 should state explicitly that all combinations are possible, and provide at least one example of each.

## Defect 7 — Report discipline

Same pattern. R580 reports:

```
R580 Certificate Lifecycle & Selective Revalidation tests: 10/10 passed
```

but does not surface `ExecutionRunID`, `certificate.json`, `Method`. This is the discipline debt that has now persisted for 21+ rounds. It must be closed in R580.1.

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

## R574–R579 (recap)

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

### Revision
- **Type:** $Rev : K_t \to K_{t+1}$ with $H_{t+1} = H_t \mathbin{\|} e_{rev}$.

### Retraction
- **Invariant:** $Retraction \neq HistoryDeletion$.

### Expiration
- **Invariant:** $Expiration \neq Refutation$.

### Epistemic History
- **Type:** $H_t = (e_1, \ldots, e_t)$.

### Dependency Closure of a Certificate
- **Type:** $Dep(C) = \{E_i : E_i \text{ in } C\text{'s declared dependency closure}\}$.

### Affected
- **Type:** $Affected(E, C) \iff E \in Dep(C) \land \text{version}(E) > \text{version referenced by } C$.

### MateriallyAffected
- **Type:** $MateriallyAffected(E, C) \iff Affected(E, C) \land \text{the change can affect } C\text{'s target}$.

### EpistemicState
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.

### AssuranceLifecycleState
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

## R580 new terms

### Certificate (recap, more precise)
- **Definition:** A persisted assurance artifact stating that a particular assessment was verified under specified conditions.
- **Type:** $C = (\text{Property}, \text{Scope}, \text{Method}, \text{Result}, \text{Provenance}, \text{Signature}, \text{Time}, \text{EvidenceVersions}, \text{Lifecycle})$.
- **Real-world:** A safety certificate for a device.
- **Invalid:** A truth claim.
- **Invariant:** $Certificate \neq Truth$.

### Certificate Scope
- **Definition:** The exact where/for-whom/under-which the certificate applies.
- **Type:** $(Domain, Population, TimeRange, Regime)$.
- **Real-world:** "German voters in 2026 under Regulation X."
- **Invalid:** Global scope without declaration.

### Certificate Dependency Set
- **Definition:** The set of evidence, assumptions, transformations upon which the certificate's validity depends.
- **Type:** $Dep(C) \subseteq \text{Artifacts}$.
- **Real-world:** A certificate that depends on three sensor readings.

### Certificate Lifecycle
- **Definition:** The operational status of the certificate as an assurance artifact.
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.
- **Real-world:** A certificate that needs re-issuance after a policy change.

### Lifecycle Transition Table
- **Definition:** The allowed transitions between lifecycle states.
- **Type:** A partial function from (state, event) to state.
- **Real-world:** A workflow state machine.
- **Invalid:** Undefined transitions.

### Dependency Completeness
- **Definition:** The property that a dependency graph contains all material dependencies within a declared scope.
- **Type:** $Complete(G_E, C, S)$.
- **Real-world:** A well-maintained catalog.
- **Invalid:** Assuming completeness without verification.

### Impact Propagation (new)
- **Definition:** The propagation of a revision's effect along material typed edges.
- **Type:** $\text{Propagate}(E', G_E) = \{C : MateriallyDepends(E', C)\}$.
- **Real-world:** A dependency update triggering downstream rebuilds.

### MateriallyDepends (new)
- **Definition:** A dependency relation whose edge is declared material to the target.
- **Type:** $MateriallyDepends(E, C) \iff E \in Dep(C) \land \text{edge}(E, C).\text{material} = \text{True}$.

### CurrentValidity vs HistoricalValidity
- **Definition:** Current validity is the certificate's status now; historical validity is its status at the time it was issued.
- **Invariant:** $CurrentValidity \neq HistoricalValidity$ (a certificate can be historically valid but currently superseded).

### Revalidation
- **Definition:** The process of re-evaluating a certificate after a materially affecting change.
- **Type:** $Rev : C \to C'$ or $C \to \text{INVALID}$.
- **Invariant:** $Affected \neq Invalid$.

### GovernanceAuthority (new axis)
- **Definition:** The permission of a certificate to influence a specific decision under a contract.
- **Type:** $\{AUTHORIZED, NOT\_AUTHORIZED, CONDITIONAL, UNKNOWN\}$.
- **Real-world:** A certificate valid for internal use but not for regulatory filing.
- **Invalid:** Conflating with epistemic state or lifecycle.

### CertificateStalenessRate (metric)
- **Definition:** The fraction of materially affected certificates not revalidated.
- **Type:** $\frac{|\text{materially affected, not revalidated}|}{|\text{materially affected}|}$.
- **Real-world:** A high value indicates a system that is not keeping up with revisions.

---

# Part V — Worked Examples

## Example 1 — Selective revalidation

**Setup.**

- $C_1$ depends on $E_1$.
- $C_2$ depends on $E_2$.
- $E_1$ revised.

**Result.** $C_1$: `REVALIDATION_REQUIRED`. $C_2$: `CURRENT`.

## Example 2 — Non-material revision

**Setup.**

- $C$ depends on $E_1$ (sensor reading), version 1.
- $E_1$ updated to version 2 with metadata change; reading unchanged.

**Result.** `Affected(E_1, C) = True`. `MateriallyAffected(E_1, C) = False`. Lifecycle remains `CURRENT`.

## Example 3 — Revalidation with PASS

**Setup.** $C$ in `REVALIDATION_REQUIRED`. Revalidation runs against new evidence.

**Result.** `PASS + CURRENT`. Prior lifecycle was `REVALIDATION_REQUIRED`; new lifecycle is `CURRENT`.

## Example 4 — Revalidation with FAIL

**Setup.** $C$ in `REVALIDATION_REQUIRED`. Revalidation fails.

**Result.** `FAIL + SUPERSEDED`.

## Example 5 — Revalidation with UNKNOWN (to be formalized in R581)

**Setup.** $C$ in `REVALIDATION_REQUIRED`. Revalidation yields `UNKNOWN`.

**Candidate A (Conservative):** $C$ returns to `REVALIDATION_REQUIRED`.

**Candidate B (Optimistic):** $C$ returns to `CURRENT`.

**Candidate C (Pessimistic):** $C$ becomes `SUPERSEDED`.

**Candidate D (Contract-dependent):** $C$'s contract declares.

R581 must pick.

## Example 6 — Historical vs current validity

**Setup.**

- $t_1$: Certificate issued `PASS + CURRENT`.
- $t_2$: New evidence arrives.
- $t_3$: Certificate marked `REVALIDATION_REQUIRED`.
- $t_4$: Revalidation fails.
- $t_5$: Certificate becomes `FAIL + SUPERSEDED`.

**Correct interpretation.**

- At $t_1$, the certificate was valid.
- At $t_4$–$t_5$, the certificate is no longer valid.
- The certificate was **not** invalid at $t_1$. It was invalidated at $t_4$–$t_5$.

**Audit trail.** All events are recorded in $H$; no rewriting.

## Example 7 — Cascade with typed propagation

**Setup.**

- $E_1 \xrightarrow{\text{material}} A_1 \xrightarrow{\text{material}} C_1 \xrightarrow{\text{immaterial}} C_2$.
- $E_1$ revised.

**Result.** $A_1$: revalidated. $C_1$: revalidated. $C_2$: **not** revalidated (immaterial edge).

## Example 8 — Three-axis independence

**Setup.** A certificate with:

- EpistemicState: `SUPPORTED`.
- Lifecycle: `CURRENT`.
- GovernanceAuthority: `NOT_AUTHORIZED` (valid, but not for the current decision).

**Result.** The certificate is *epistemically* supported and *operationally* current, but *governance-wise* unavailable. Three different facts.

## Example 9 — Dependency incompleteness

**Setup.**

- $C$ depends on $E_1, E_2$ (declared).
- $E_3$ revised but $E_3 \notin Dep(C)$.
- It is unknown whether $E_3$ should be in $Dep(C)$.

**Conservative handling.** $C$ marked `REVALIDATION_REQUIRED`.

**Less conservative.** $C$ remains `CURRENT` but `Lifecycle` includes `dependency-completeness: unverified`.

R580's discipline favors the conservative option unless the contract permits otherwise.

## Example 10 — Certificate staleness metric

**Setup.** A system with 1000 certificates. A revision affects 50 materially.

- 45 revalidated.
- 5 missed.

**CertificateStalenessRate = 5/50 = 0.10.**

**Interpretation.** 10% of materially affected certificates were not revalidated. This is a measure of *operational integrity*, not classifier accuracy.

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
        CompositionWitness / TargetPreservationBridge (predicate)
        TPP / Composition / Preservation / Loss
        DimensionRelevance
        TransformationGraph ⊕ DependencyGraph (separate)
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     CompositionStatus | EpistemicState | Revision | Acquisition
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     RegimeClosure / FirewallVerification / GlobalPreservationVerification
     ImpactAnalysis / Revalidation
     CertificateLifecycle / GovernanceAuthority
     EpistemicState × Lifecycle × GovernanceAuthority (three axes)
                              │
                     L5 Intelligence
       CandidateImpact / CandidateDependency / CandidateAcquisition
       CandidateBridgeGenerator / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       BridgeAuthorization / RevalidationAuthorization
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

**ML may do:**

- Propose `CandidateImpact(E, C, score)`.
- Propose `CandidateDependency(E₁, E₂, score)`.
- Propose `CandidateAcquisition(target, action, score)`.
- Propose `CandidateBridge(Γ₁, Γ₂, score)`.
- Propose `CandidateTargetAlignment(Z₁, Z₂, score)`.

**ML may not do:**

- Assert impact.
- Assert dependency.
- Assert bridge validity.
- Change certificate lifecycle.
- Write to X.
- Bypass L4.

**Firewall:**

```
ML candidate
  → TypeCheck
  → RegimeCheck
  → CompatibilityCheck
  → DependencyCheck
  → MaterialityCheck
  → CandidateQuarantine
  → L4 Validation (ImpactAnalysis / Revalidation)
  → Assessment / Lifecycle transition
```

**Adversarial benchmark for R581+:**

- **Class A:** True impact.
- **Class B:** False impact (superficially related).
- **Class C:** Hidden impact (indirect dependency).
- **Class D:** Adversarial similarity (embedding-similar but irrelevant).

**Metrics:** Impact Precision, Recall, False Revalidation Rate, Missed Revalidation Rate, Certificate Staleness Rate, Calibration Error, Abstention Quality.

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Impact}$$
$$\boxed{Affected \neq Invalid}$$
$$\boxed{MLCandidateImpact \not\Rightarrow LifecycleChange}$$

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
- **AssessmentStatus ≠ Lifecycle:** established.
- **CurrentValidity ≠ HistoricalValidity:** established.
- **EpistemicState × Lifecycle × GovernanceAuthority:** proposed as three axes.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R580.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R580.2** — lifecycle transition table.
- **R580.3** — dependency completeness typed.
- **R580.4** — impact propagation rule typed.
- **R580.5** — three-axis cross product enumerated.
- **R580.6** — invariants split into axioms/definitions/rules/theorems/observations.
- **R581** — formal state machine for AssessmentStatus × LifecycleStatus, especially the `UNKNOWN` transition.
- **R582** — cascading and simultaneous revisions.
- **R583** — full invariant engine.
- **R584** — terminology freeze.
- **R585** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **`UNKNOWN` revalidation semantics:** candidate A/B/C/D, R581 must decide.
- **Cascade termination:** open.

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
- `UNKNOWN ≠ FAIL` (including during revalidation).

### The single most important next thing

$$\boxed{\text{Build R580.1 (report discipline) and R581 (AssessmentStatus × LifecycleStatus state machine, especially UNKNOWN).}}$$

Because R580's architecture is correct and its one unresolved case (`UNKNOWN` during revalidation) has a small, enumerable set of candidates. R581 must pick one and test it, or it must implement contract-dependent selection with a declared default. Deferring this risk to hidden implementation semantics would violate the discipline established since R574.

### Two open questions I flag

1. **Should the `UNKNOWN` revalidation transition be contract-dependent or global?** My recommendation: contract-dependent with a declared default (Candidate A — stay in `REVALIDATION_REQUIRED`). This preserves the certificate as an open question rather than silently resolving it in either direction.

2. **Does revalidation itself generate evidence?** If revalidation runs a check that produces a new observation (e.g., a counterexample), does that observation enter the epistemic fabric and trigger further revision? The correct answer should be yes, with the same history-preservation discipline. R581 must handle the revalidation cycle without introducing infinite loops — likely by requiring revalidation events to be recorded in $H$ and by capping the depth or requiring explicit contract authorization for cascading.