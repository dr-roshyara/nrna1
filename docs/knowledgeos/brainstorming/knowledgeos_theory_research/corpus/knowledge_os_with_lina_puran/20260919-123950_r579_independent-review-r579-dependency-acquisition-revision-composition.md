# Independent Review — R579 (Dependency, Acquisition, Revision, and Composition)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R579. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R579.
2. **What R579 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R579.1 / R580.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R579 is a **substantive and correct step**, and it makes five contributions:

$$\boxed{Revision \Rightarrow Revalidation\ of\ affected\ certificates, \text{not } HistoryDeletion}$$
$$\boxed{Affected \neq Invalid}$$
$$\boxed{EpistemicState \neq AssuranceLifecycleState}$$
$$\boxed{Acquisition \neq Transformation \neq Evidence}$$
$$\boxed{TransformationGraph \leftrightarrow EpistemicDependencyGraph, \text{not one giant graph}}$$

Each is correct. The third is the strongest: it separates two dimensions that earlier rounds had begun to conflate, and it prevents a class of errors (e.g., "certificate expired" ⟹ "claim false") that would otherwise be encoded into the system.

The fifth is also important: it preserves the multi-relational discipline of the theory. The temptation to collapse transformation, dependency, provenance, and causal graphs into a single graph is real; R579 correctly resists it, and it grounds the resistance in the deepest KnowledgeOS principle: *preserve distinctions that determine the target*.

R579 also preserves the L0–L6 architecture and adds no new BC, layer, or Kernel primitive. That is now consistent across R574–R579.

**Seven residual issues**, each requiring correction before R580:

1. `REVALIDATION_REQUIRED` is proposed as a lifecycle state but the **transition table** between lifecycle states is not given.
2. §4's claim that "revision ⟹ revalidation of affected certificates" is correct, but the **exact condition** (materially affected vs merely reachable) is not typed.
3. §5's `Affected(C, E)` predicate is not formally defined. Reachability vs materiality distinction is critical and missing.
4. §14–§15 correctly separate `Status` from `Lifecycle`, but the **lattice operations on Lifecycle** are not defined (what is `CURRENT ∧ SUPERSEDED`?).
5. §21 is a strong principle (EpistemicState vs AssuranceLifecycle), but the tuple structure is not written out. Which state composes with which?
6. §22's invariants I-RV01..I-RV07 again mix axioms, definitions, and derived observations.
7. Report discipline: `ExecutionRunID`, `certificate.json`, and per-test `Method` field are still absent. This discipline debt has now persisted for 20+ rounds and must be closed in R579.1.

---

# Part II — What R579 Actually Establishes

## 2.1 The history-preservation axiom

R579 §3 states:

$$K_t = (X_t, H_t), \quad X_{t+1} \neq X_t \quad \text{and} \quad H_{t+1} = H_t \mathbin{\|} e_{revision}$$

**Correct.** This is the correct formalization of the invariant: *governance and epistemic revision do not rewrite epistemic history*.

The document correctly notes this reinforces a deepest invariant from R601.

## 2.2 Selective revalidation vs global reset

R579 §6:

$$\text{NewEvidence} \to \text{DependencyAnalysis} \to \text{AffectedArtifacts} \to \text{Revalidation} \to \text{Assessment}$$

instead of:

$$\text{NewEvidence} \to \text{InvalidateEverythingDownstream}$$

**Correct.** This is the operationally correct architecture. The alternative (global reset) is computationally simpler but epistemically wrong: it discards valid certificates that are unaffected by the revision.

## 2.3 The Affected ≠ Invalid distinction

R579 §5:

$$Affected(C, E) \neq Invalid(C)$$

**Correct.** Affected means "the revision touches the certificate's dependency closure"; Invalid means "the certificate no longer holds". The two are logically independent: an affected certificate may remain valid; an unaffected certificate may become invalid for unrelated reasons (e.g., contract change).

This is the correct epistemic discipline.

## 2.4 The epistemic-state vs lifecycle-state separation

R579 §21:

$$\text{EpistemicState} \in \{\text{SUPPORTED}, \text{UNRESOLVED}, \text{REFUTED}, \text{CONDITIONAL}\}$$

$$\text{AssuranceLifecycleState} \in \{\text{CURRENT}, \text{REVALIDATION\_REQUIRED}, \text{SUPERSEDED}, \text{EXPIRED}\}$$

**Correct.** These are orthogonal dimensions.

**Counterexample.** A certificate may have:
- Epistemic state: `SUPPORTED` (the underlying claim is still believed).
- Lifecycle: `REVALIDATION_REQUIRED` (evidence has changed but the claim may still hold).

Conflating them produces the invalid inference:

$$\text{Expired} \Rightarrow \text{False}$$

which is precisely the kind of error the theory is designed to prevent.

## 2.5 The two-graph separation

R579 §23–§24 correctly distinguishes:

- Transformation Graph: $X_0 \xrightarrow{T_1} X_1 \xrightarrow{T_2} \cdots$
- Epistemic Dependency Graph: $E_1 \to E_2 \to A \to D \to C$

and requires **explicit contracts** for interaction rather than collapsing them into one graph.

This is the correct architecture. The single-graph temptation is common in knowledge-graph systems and it destroys the ability to distinguish mechanism from structure.

## 2.6 The acquisition pipeline

R579 §8 correctly preserves the pipeline:

$$ML \to Candidate \to Authorization \to Acquisition \to Observation \to Evidence \to Assessment$$

**Correct.** Acquisition is an operation, not evidence. The acquired observation becomes evidence only after it enters the epistemic fabric.

---

# Part III — Seven Defects to Fix in R579.1 / R580

## Defect 1 — The lifecycle transition table is not given

R579 §14–§15 proposes lifecycle states but does not define the transitions.

**Recommended transition table:**

| From \ To | CURRENT | REVALIDATION_REQUIRED | SUPERSEDED | EXPIRED |
|---|---|---|---|---|
| CURRENT | ✓ (self) | ✓ (on affected) | ✓ (on supersession) | ✓ (on time expiry) |
| REVALIDATION_REQUIRED | ✓ (on pass) | ✓ (self, evidence still unresolved) | ✓ (on fail) | ✓ (on time expiry) |
| SUPERSEDED | ✗ | ✗ | ✗ (terminal) | ✗ (terminal) |
| EXPIRED | ✗ | ✗ | ✗ (terminal) | ✗ (terminal) |

Without this table, the lifecycle is a set, not a system.

## Defect 2 — The revalidation condition is not typed

R579 §4 says "revision ⟹ revalidation of affected certificates" but "affected" is not defined.

**Corrected definition:**

$$Affected(C, E) \iff E \in Dep(C)$$

where $Dep(C)$ is the set of evidence items in the certificate's declared dependency closure.

**Sharper version:** An evidence item $E$ materially affects $C$ iff:

$$MateriallyAffects(E, C) \iff E \in Dep(C) \land \text{version}(E) > \text{version referenced by } C$$

The revalidation trigger is `MateriallyAffects`, not `Reachable` (reachability is necessary but not sufficient; the version check matters).

## Defect 3 — `Affected` vs `MateriallyAffected` is not distinguished

R579 §5 uses "affected" but the operational trigger should be "materially affected".

**Corrected distinction:**

- **Reachable:** $E$ is in the transitive closure of $C$'s declared dependencies.
- **Affected:** $E$'s version has changed since $C$ was issued.
- **MateriallyAffected:** $E$'s change can affect the *target* of $C$ under the declared contract.

Only `MateriallyAffected` triggers revalidation. `Reachable` alone is a diagnostic signal but not a trigger.

**Counterexample.** A new version of an evidence item fixes a typo in its provenance metadata but leaves the content unchanged. The certificate should not require revalidation because the change is immaterial to the target.

## Defect 4 — Lifecycle lattice operations are not defined

R579 does not state how lifecycle states combine. Consider a certificate depending on two evidence items:

- $E_1$ triggers `REVALIDATION_REQUIRED`.
- $E_2$ is `SUPERSEDED`.

What is the certificate's lifecycle?

**Recommended meet operation:**

$$\text{CURRENT} \sqsubset \text{REVALIDATION\_REQUIRED} \sqsubset \text{SUPERSEDED} = \text{EXPIRED}$$

with meet (weakest link):

$$\text{Lifecycle}(C) = \bigsqcap_{E \in Dep(C)} \text{Lifecycle}(E)$$

This makes the composition of lifecycle states deterministic.

## Defect 5 — The EpistemicState × Lifecycle tuple is not enumerated

R579 §21 lists the two dimensions but not their cross product. Some combinations are possible; others may not be.

**Recommended enumeration:**

| EpistemicState | CURRENT | REVALIDATION_REQUIRED | SUPERSEDED | EXPIRED |
|---|---|---|---|---|
| SUPPORTED | ✓ | ✓ | ✓ | ✓ |
| UNRESOLVED | ✓ | ✓ | ✓ | ✓ |
| REFUTED | ✓ | ✓ | ✓ | ✓ |
| CONDITIONAL | ✓ | ✓ | ✓ | ✓ |

All combinations are logically possible. R579.1 should state this explicitly.

## Defect 6 — Invariants mix axioms, definitions, and observations

R579 §22 lists I-RV01..I-RV07. But:

- I-RV01 (`H_{t+1} = H_t ‖ e`): **axiom** (the history model).
- I-RV02 (`Revision(P) ⇏ ¬P`): **derived observation**.
- I-RV03 (`Affected(C, E) ⇏ Invalid(C)`): **counterexample theorem**.
- I-RV04 (Material dependency requires revalidation): **rule** (operational policy).
- I-RV05 (`CandidateAcquisition ≠ Evidence`): **definition**.
- I-RV06 (`Acquisition ≠ Evidence ≠ Determination`): **definition**.
- I-RV07 (`Expiration ≠ Refutation`): **derived observation**.

**Recommendation.** Split into:

- **Axioms:** I-RV01.
- **Definitions:** I-RV05, I-RV06.
- **Rules:** I-RV04.
- **Theorems:** I-RV03.
- **Observations:** I-RV02, I-RV07.

This is the same status discipline applied to admission status and the zero-state taxonomy.

## Defect 7 — Report discipline

Same as R577/R578. `ExecutionRunID`, `certificate.json`, per-test `Method`. This debt spans 20+ rounds.

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

## R574–R578 (recap)

### Admission
- **Type:** $\{Admitted, Rejected, Conditional, Unknown\}$.

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### Conflict vs Contradiction
- **Distinction:** Conflict requires a comparison contract; contradiction requires a logical regime.

### PreservationBridge
- **Type:** $B(T, Z_X, Z_Y) \iff \forall x \in W_{\text{adm}} : Z_X(x) = Z_Y(T(x))$.

### GlobalPreserve
- **Type:** $\forall x \in W : Z_0(x) = Z_n(T_n \circ \cdots \circ T_1(x))$.

### DimensionRelevant
- **Type:** $DimensionRelevant(d, Z, W) \iff \exists x_1, x_2 \in W : d(x_1) \neq d(x_2) \land Z(x_1) \neq Z(x_2)$.

## R579 new terms

### Dependency (recap)
- **Definition:** Declared or assessed relationship in which the interpretation or target relevance of one epistemic object depends on another.
- **Type:** $E_1 \leftarrow C \rightarrow E_2$ or $E_1 \to E_2$.

### Dependency Factor (recap)
- **Definition:** Variable, assumption, source, model, transformation, or condition whose intervention can materially affect the target.

### Intervention (recap)
- **Type:** $do(F = f')$.

### Materiality (recap)
- **Type:** $Material(F, Z \mid B)$.

### Acquisition
- **Definition:** Authorized operation intended to obtain information that may reduce an unresolved epistemic state.
- **Type:** $Acq : (E, Q, C) \to E'$.
- **Real-world:** Querying an authoritative database.
- **Invalid:** Treating acquisition as evidence.

### Acquisition Action
- **Type:** $a = (Target, Source, Observation, Cost, Authority, Time, Contract)$.

### Information Gain
- **Type:** $IG(Z; O) = H(Z) - H(Z \mid O)$.

### Revision
- **Definition:** A change to the currently assessed epistemic state because new evidence, correction, expiry, or changed assumptions change what is currently supported.
- **Type:** $Rev : K_t \to K_{t+1}$ with $H_{t+1} = H_t \mathbin{\|} e_{rev}$.
- **Real-world:** Updating a diagnosis after a new test result.
- **Invalid:** Rewriting the historical record.

### Retraction
- **Definition:** Removal of current entitlement to a previous conclusion.
- **Type:** $Retract : K_t \to K_{t+1}$.
- **Invariant:** $Retraction \neq HistoryDeletion$.

### Expiration
- **Definition:** An assertion is no longer valid for the current temporal scope.
- **Invariant:** $Expiration \neq Refutation$.

### Certificate
- **Type:** $(Property, Scope, Method, Result, Provenance, Signature, Time)$.

### Certificate Validity
- **Definition:** The certificate's stated claim remains supported under its declared validity conditions.
- **Type:** A predicate on (certificate, current state).
- **Real-world:** A safety certification valid for a specific product under a specific test suite.
- **Invalid:** Treating a certificate as eternal truth.

### Epistemic History
- **Definition:** Append-only record of events (evidence, assessment, determination, certificate, revision, invalidation).
- **Type:** $H_t = (e_1, \ldots, e_t)$.

### Dependency Closure of a Certificate (new)
- **Definition:** Set of evidence items that the certificate's validity depends on.
- **Type:** $Dep(C) = \{E_i : E_i \text{ in the certificate's declared dependency closure}\}$.

### Affected (new)
- **Definition:** An evidence item is in a certificate's dependency closure and its version has changed.
- **Type:** $Affected(E, C) \iff E \in Dep(C) \land \text{version}(E) > \text{version referenced by } C$.

### MateriallyAffected (new)
- **Definition:** An evidence change can affect the target of $C$ under the declared contract.
- **Type:** $MateriallyAffected(E, C) \iff Affected(E, C) \land \text{the change can affect } C\text{'s target}$.

### Revalidation
- **Definition:** The process of re-evaluating a certificate after a materially affecting change.
- **Type:** $Rev : C \to C'$ or $C \to \text{INVALID}$.
- **Invariant:** $Affected \neq Invalid$.

### EpistemicState (new)
- **Definition:** The current epistemic status of a claim or certificate.
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.
- **Real-world:** Whether the diagnosis is believed.

### AssuranceLifecycleState (new)
- **Definition:** The lifecycle stage of a certificate as an assurance artifact.
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.
- **Real-world:** Whether the certificate needs to be re-issued.
- **Invariant:** `Expired ≠ False`.

### TransformationGraph
- **Definition:** Graph of state transformations.
- **Type:** $G_T = (W, \{T_i\})$.

### EpistemicDependencyGraph
- **Definition:** Graph of epistemic dependencies among evidence, assessments, determinations, certificates.
- **Type:** $G_E = (E \cup A \cup D \cup C, \text{dependency relations})$.

### Two-graph separation
- **Definition:** The two graphs are distinct and interact only through declared contracts.
- **Invariant:** $\neg$ collapse into one graph.

---

# Part V — Worked Examples

## Example 1 — Revision preserves history

**Setup.**

- $E_1 = (A = 1, B = 1)$, $Z = A \land B = 1$.
- Certificate $C$ issued: `SUPPORTED`, `CURRENT`.
- New evidence: $A = 0$.

**Revision.**

- $X_{t+1}$: $Z = 0$.
- $H_{t+1} = H_t \mathbin{\|} e_{revision}$.
- Certificate $C$: `Affected` (depends on $A$).
- Lifecycle: `REVALIDATION_REQUIRED`.

**Revalidation.**

- New certificate $C'$: `SUPPORTED` with respect to the new state.
- Old certificate $C$: `SUPERSEDED`.
- History contains both $C$ and $C'$ and the revision event.

## Example 2 — Affected ≠ Invalid

**Setup.**

- Certificate $C$ depends on $E_1, E_2$.
- $E_2$ updated: version 2 with corrected provenance metadata but identical content.

**Analysis.**

- $Affected(E_2, C) = \text{True}$ (version changed).
- $MateriallyAffected(E_2, C) = \text{False}$ (content unchanged, target unaffected).

**Result.** $C$ remains `CURRENT`. No revalidation required.

## Example 3 — Epistemic state ≠ lifecycle

**Setup.**

- Certificate $C$: `SUPPORTED` (claim believed), `EXPIRED` (time-based expiry passed).

**Correct inference.** The claim is still believed; the certificate is no longer current.

**Invalid inference.** "Certificate expired ⟹ Claim false."

## Example 4 — The two-graph separation

**Setup.**

- Transformation graph: $T_1 : \text{Meters} \to \text{Centimeters}$.
- Epistemic dependency graph: $E_1 \to T_1 \to C_1$.

**Interaction.** A change to $E_1$ affects $T_1$'s application, which affects $C_1$.

**Correct handling.** Traverse the epistemic dependency graph from $E_1$; identify $C_1$ as affected; revalidate $C_1$.

**Invalid handling.** Treating the two graphs as one; the transformation $T_1$ is a *mechanism*, not evidence.

## Example 5 — Acquisition pipeline

**Setup.** KnowledgeOS determines that $A$ is material and unresolved.

**Steps.**

1. `CandidateAcquisition`: "fetch authoritative record for A".
2. `AuthorizedAcquisition`: authorization granted.
3. `ExecutedAcquisition`: record fetched.
4. `Observation`: value `A = 1`.
5. `Evidence`: observation entered the epistemic fabric as evidence.
6. `Assessment`: $Z = A \land B \land C = 0 \land \text{unknown} \land \text{unknown}$.

**Key distinction.** The acquisition is not itself evidence.

## Example 6 — Selective revalidation over a graph

**Setup.**

- $E_1, E_2 \to T_1 \to C_1$.
- $E_3, E_4 \to T_2 \to C_2$.

**Change.** $E_2$ updated.

**Analysis.**

- $E_2 \in Dep(C_1)$, $E_2 \notin Dep(C_2)$.
- $C_1$: `REVALIDATION_REQUIRED`.
- $C_2$: `CURRENT`.

**Result.** Only $C_1$ revalidates. $C_2$ remains current.

## Example 7 — Non-materially affected certificate

**Setup.**

- Certificate $C$ depends on $E_1$ (a sensor reading) and $E_2$ (a derived feature).
- $E_1$ updated: sensor recalibrated but reading unchanged within noise.

**Analysis.** $Affected(E_1, C) = \text{True}$, but $MateriallyAffected(E_1, C) = \text{False}$ (target unchanged).

**Result.** No revalidation.

## Example 8 — Cascade with selective revalidation

**Setup.** $E_1 \to T_1 \to C_1 \to T_3 \to C_3$ and $E_1 \to T_2 \to C_2 \to T_3 \to C_3$.

**Change.** $E_1$ updated.

**Correct analysis.** $C_1, C_2, C_3$ are all affected.

**Naive alternative.** Marking everything downstream invalid.

**Correct result.** Revalidate $C_1, C_2, C_3$ selectively; those that pass remain `CURRENT`; those that fail become `SUPERSEDED`.

## Example 9 — Lifecycle composition

**Setup.**

- Certificate $C$ depends on $E_1$ (lifecycle: `REVALIDATION_REQUIRED`) and $E_2$ (lifecycle: `SUPERSEDED`).

**Composition (meet).**

$$\text{Lifecycle}(C) = \text{REVALIDATION\_REQUIRED} \sqcap \text{SUPERSEDED} = \text{SUPERSEDED}$$

**Result.** Certificate's lifecycle is `SUPERSEDED`.

**Justification.** The weakest link determines the composite lifecycle.

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
             ComparisonContract
                              │
                     L2 Formal Fabric
        Types / Operations / Partial Transformations
        CompatibilityWitness / RegimeBridge / PreservationBridge
        CompositionWitness / TargetPreservationBridge (predicate)
        TPP / Composition / Preservation / Loss
        DimensionRelevance
        TransformationGraph (separate from DependencyGraph)
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
     ImpactAnalysis / Revalidation / AssuranceLifecycleState
                              │
                     L5 Intelligence
       CandidateBridgeGenerator / CandidateDependency
       CandidateAcquisition / CandidateImpact
       Pairwise ML / Group ML / Embeddings / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision / BridgeAuthorization
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
  → Assessment
```

**Adversarial benchmark for R580:**

- **Class A:** True impact — new evidence genuinely changes the target.
- **Class B:** False impact — new evidence is related but immaterial.
- **Class C:** Hidden impact — indirect dependency.
- **Class D:** Adversarial similarity — superficially similar but irrelevant.

**Metrics:** Impact Precision, Recall, False Revalidation Rate, Missed Revalidation Rate, Certificate Staleness Rate.

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Impact}$$
$$\boxed{Affected \neq Invalid}$$

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
- **Dependency + Acquisition + Revision + Composition:** R579 passed 10/10.
- **Revision preserves history:** axiom established.
- **Selective revalidation:** architecture established.
- **Epistemic state vs lifecycle:** separated.
- **Two-graph architecture:** established (transformation ≠ dependency).
- **ML firewall:** preserved and extended to impact/revalidation.
- **Report discipline:** still partial.

### Not yet done

- **R579.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R579.2** — lifecycle transition table.
- **R579.3** — `Affected` vs `MateriallyAffected` typed distinction.
- **R579.4** — lifecycle lattice operations.
- **R579.5** — EpistemicState × Lifecycle enumeration.
- **R579.6** — invariants split into axioms/definitions/rules/theorems/observations.
- **R580** — Certificate Lifecycle & Selective Revalidation Calculus: cascading and simultaneous revisions, acquisition-revision-revalidation cycles.
- **R581** — full global invariant engine.
- **R582** — terminology freeze.
- **R583** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Cascade complexity of revalidation:** open (how deep does revalidation propagate?).

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
- `EpistemicState ≠ AssuranceLifecycleState`.
- `Acquisition ≠ Transformation ≠ Evidence`.

### The single most important next thing

$$\boxed{\text{Build R579.1 (report discipline) and R580 (certificate lifecycle + selective revalidation calculus).}}$$

Because R579 has articulated the correct architecture for revision under dependency, and R580 is where the architecture becomes executable at the certificate lifecycle level. The report discipline fix is now 20+ rounds overdue and must be closed.

### Two open questions I flag

1. **Does revalidation cascade indefinitely, or is there a termination condition?** In a chain $C_1 \to C_2 \to C_3 \to \cdots$, if $C_1$ is revalidated and passes, does $C_2$ need revalidation? The answer likely depends on whether $C_1$'s *value* changed, not merely its lifecycle state. R580 must characterize the exact condition.

2. **Can a revalidation itself trigger a revision?** If revalidation of $C$ produces new evidence (e.g., the revalidation run generates a counterexample), does that constitute evidence that triggers further revision? The correct answer should be yes, with the same history-preservation discipline. R580 must handle this cycle.