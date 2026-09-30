# Independent Review — R583 (Dependency Completeness & Negative Impact Proof)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R583. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R583.
2. **What R583 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R583.1 / R584.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R583 is a **correct and important step**, and it makes five contributions:

$$\boxed{Dependency \neq Impact}$$
$$\boxed{Complete(D) \land Complete(M) \land Complete(P) \text{ — three distinct completeness requirements}}$$
$$\boxed{PositiveDetection \text{ is existential, } NegativeProof \text{ is universal}}$$
$$\boxed{NoPathFound \Rightarrow UNKNOWN \text{ (unless completeness holds)}}$$
$$\boxed{RepresentationLoss \to DependencyObservabilityLoss \to ImpactUncertainty}$$

Each is correct. The third is the strongest: it names a fundamental logical asymmetry between positive detection and negative proof, and it correctly identifies the *existential/universal* quantifier structure at play.

The first is also important: it is a refinement of R582's closure result. R582 correctly noted that closure depends on the graph; R583 correctly notes that even within the graph, dependency does not imply impact — a dependency edge may carry information that is immaterial, non-propagating, or contextual.

The fifth is the sharpest unification: R583 connects TPP (from R602 onwards) to dependency impact assessment. When a projection loses a dependency-relevant factor, the impact assessment on the transformed representation cannot recover the lost distinction, producing `UNKNOWN` impact instead of `NOT_AFFECTED`.

R583 also correctly preserves the L0–L6 architecture and refuses to add `IMPACT_UNCERTAIN` to the certificate lifecycle enum. This is the same discipline as R574–R582.

**Seven residual issues**, each requiring correction before R584:

1. The `ImpactPropagation` predicate is introduced but not typed precisely.
2. The three completeness dimensions are stated but their **relationship to each other** is not typed. Are they independent? Does `Complete_D` imply `Complete_P`?
3. §6's central negative-impact rule is correct but does not state the **witness structure** for each completeness dimension.
4. §16's asymmetry (existential positive, universal negative) is correct but does not state the **dual**: what evidence is required for each?
5. §19's calibration remarks are correct but the **decision rule** for using ML probabilities in the impact pipeline is not stated.
6. §23's three-axis decomposition (`AssessmentStatus × ImpactAssessment × CertificateLifecycle`) is correct but the cross product (4 × 3 × 4 = 48) is not enumerated.
7. Report discipline: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`. This is 24+ rounds of accumulating debt.

---

# Part II — What R583 Actually Establishes

## 2.1 Dependency ≠ Impact

R583 §1:

$$D(E, C) \not\Rightarrow Impact(E, C)$$

A dependency edge can carry information that is immaterial to the target, non-propagating through the change event, or merely contextual.

**Correct.** This refines R582's closure result. R582 established that closure depends on the graph; R583 establishes that even within the graph, dependency is a *necessary but not sufficient* condition for impact.

**Example.** A certificate's provenance records a document ID (dependency exists) but the certificate's target does not depend on the document's content (no impact).

## 2.2 Three completeness dimensions

R583 §7:

$$Complete_D \land Complete_M \land Complete_P$$

- `Complete_D`: all dependency edges are represented.
- `Complete_M`: materiality classification is correct.
- `Complete_P`: impact-propagation classification is correct.

**Correct.** These are three genuinely distinct properties.

**Why they matter.** A model can have:
- Complete dependency graph, incomplete materiality classification (misses material edges).
- Complete materiality, incomplete propagation (material edges don't propagate).
- All three complete — the only sufficient condition for `NOT_AFFECTED`.

## 2.3 Existential vs universal asymmetry

R583 §16:

$$\text{PositiveDetection} \equiv \exists d : ImpactPath(d)$$
$$\text{NegativeProof} \equiv \forall d : \neg ImpactPath(d)$$

**Correct.** This is a fundamental logical asymmetry.

**Consequences.**
- Positive detection needs one valid witness.
- Negative proof needs a universal argument.
- A counterexample has *asymmetric* evidentiary power (established in R604.3 as I-A07).
- This is why "no dependency observed" is much weaker than "no dependency exists."

## 2.4 The central negative-impact rule

R583 §6:

$$Complete(D, M, P \mid \Gamma, \Sigma, Z) \land C \notin ImpactClosure_D(E) \Rightarrow NOT\_AFFECTED(C)$$

**Correct.** The completeness condition is the *only* legitimate basis for `NOT_AFFECTED`.

**Without completeness:**

$$C \notin ImpactClosure_D(E) \Rightarrow UNKNOWN(C)$$

## 2.5 TPP → dependency observability

R583 §13:

$$\text{RepresentationLoss} \to \text{DependencyObservabilityLoss} \to \text{ImpactUncertainty}$$

**Correct.** If a projection loses a factor relevant to a certificate's target, the transformed representation cannot establish the dependency's absence.

**Example.** $Z(F, G) = F$, $\pi(F, G) = G$. Two states $(0, 1)$ and $(1, 1)$ map to the same projection $G = 1$ but have different $Z$. So $\neg TPP(\pi, Z)$, and the transformed representation cannot assert `NOT_AFFECTED` about the dependency on $F$.

## 2.6 ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}

R583 §4:

$$\{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$$

**Correct.** This is the right status vocabulary. The three values are not collapsible.

## 2.7 Architecture discipline

R583 §12 states:

> No new BC. No new layer. No new Kernel primitive.

**Correct.** Impact assessment is an L3 concept; completeness verification is L4.

---

# Part III — Seven Defects to Fix in R583.1 / R584

## Defect 1 — `ImpactPropagation` is not typed

R583 §2 says a dependency is *impact-propagating* when a material change in the source can contractually affect the target or certificate state. But the predicate is not formally typed.

**Corrected type.**

$$PropagatesImpact(e, C, \Delta, \Sigma) \iff \exists s' \in \Delta : Z_C(s') \neq Z_C(s)$$

where $e = (E, C, \text{kind}, \text{material}, \text{established})$ is the edge, $\Delta$ is the admissible change set, $\Sigma$ is the scope, and $Z_C$ is the certificate's target.

**Consequence.** Propagation is not a fixed property of an edge — it depends on the change set $\Delta$. An edge might propagate some changes and not others.

## Defect 2 — The relationship between `Complete_D`, `Complete_M`, `Complete_P` is not typed

Are these independent properties, or does one imply another?

**Corrected typing.**

$$\text{Complete}_D \not\Rightarrow \text{Complete}_M$$
$$\text{Complete}_M \not\Rightarrow \text{Complete}_P$$
$$\text{Complete}_P \not\Rightarrow \text{Complete}_D$$

All three are independent.

**Example.**

- `Complete_D`: all edges enumerated.
- `Complete_M`: materiality classification correct. A graph can be complete in structure but wrong in materiality labels.
- `Complete_P`: propagation classification correct. A material edge may not propagate for the specific change set.

**Consequence.** The negative-impact proof requires the *conjunction* of all three.

## Defect 3 — Completeness witnesses are not typed per dimension

R583 §7 states three completeness dimensions but does not state what evidence justifies each.

**Corrected witness typing.**

- **Witness for Complete_D:** structural enumeration (registry exhaustiveness), or formal closure theorem, or empirical enumeration with confidence bound.
- **Witness for Complete_M:** materiality test suite against ground truth, or formal materiality analysis.
- **Witness for Complete_P:** propagation test suite, or formal propagation analysis, or adversarial injection with declared coverage.

Each witness is scoped: `(Witness, Σ, Z, C)`.

## Defect 4 — The existential/universal duality is not fully typed

R583 §16 correctly identifies the logical asymmetry but does not state what evidence is *required* for each side.

**Corrected typing.**

**For PositiveDetection:**
- Sufficient: one validated impact path.
- Type: $\exists d : ImpactPath(d)$.
- Evidence: a single witness (path, edge, change).
- Failure mode: `UNKNOWN` (no path found, no proof of absence).

**For NegativeProof:**
- Sufficient: `Complete_D ∧ Complete_M ∧ Complete_P`.
- Type: $\forall d : \neg ImpactPath(d)$.
- Evidence: structural + (formal ∨ empirical ∨ adversarial) witness of completeness.
- Failure mode: `UNKNOWN` (partial completeness).

**Consequence.** The two sides have *asymmetric* evidence requirements. This is the fundamental reason negative proof is harder.

## Defect 5 — ML decision rule is not stated

R583 §19 correctly notes that `P(Affected) = 0.02` does not establish `NOT_AFFECTED`. But it does not state the decision rule for using ML output.

**Corrected rule.**

ML output `score ∈ [0, 1]` is a **candidate-generation signal**, not a decision. The ML output enters the pipeline as follows:

- `score > τ_high` → prioritize for impact investigation (produce `CandidateImpact`).
- `score < τ_low` → deprioritize (produce no candidate).
- Otherwise → defer.

But no ML score alone can produce `AFFECTED`, `NOT_AFFECTED`, or `UNKNOWN` in the impact assessment. Only the closure + completeness pipeline can.

**Consequence.** ML thresholds affect *workload prioritization*, not the impact assessment itself.

## Defect 6 — The three-axis cross product is not enumerated

R583 §23 proposes:

$$AssessmentStatus \times ImpactAssessment \times CertificateLifecycle$$

with sizes $4 \times 3 \times 4 = 48$.

The document correctly states they are independent, but does not enumerate which combinations are logically possible.

**Recommendation.** R583.1 should state that *all 48* combinations are logically possible, and provide at least one example of each:

- `(PASS, AFFECTED, REVALIDATION_REQUIRED)`: prior PASS; revision affects; awaiting revalidation.
- `(PASS, UNKNOWN, REVALIDATION_REQUIRED)`: prior PASS; impact unknown; conservative revalidation.
- `(PASS, NOT_AFFECTED, CURRENT)`: prior PASS; no impact; no action.
- `(FAIL, AFFECTED, SUPERSEDED)`: prior FAIL; revision affects; superseded.
- etc.

## Defect 7 — Report discipline

Same pattern. 24+ rounds of debt.

**Recommendation.** R583.1 must produce, for each test:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R583.1.<k>",
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

## R574–R582 (recap)

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

### ImpactAssessment (from R582)
- **Type:** $\{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$.

### DependencyClosure
- **Type:** $Closure_D(E) = \{x : E \to^* x \text{ through material established edges}\}$.

## R583 new terms

### Dependency (recap)
- **Definition:** A relationship indicating that one epistemic object refers to, derives from, uses, constrains, or otherwise depends upon another.
- **Type:** $D(x, y)$.

### Material Dependency (recap)
- **Type:** $Material(D, Z)$ — target-relative.

### ImpactPropagation (new, typed)
- **Definition:** A dependency relation is impact-propagating when a material change in the source can affect the target or certificate state.
- **Type:** $PropagatesImpact(e, C, \Delta, \Sigma) \iff \exists s' \in \Delta : Z_C(s') \neq Z_C(s)$.
- **Real-world:** An exchange-rate feed where rate changes affect the target.
- **Invalid:** A provenance reference whose change does not affect the target.
- **Invariant:** $Dependency \neq ImpactPropagation$.

### Impact Closure
- **Definition:** Reachable objects through established, material, impact-propagating edges.
- **Type:** $ImpactClosure_D(E) = \{x : E \to^* x \text{ through edges satisfying } Established \land Material \land PropagatesImpact\}$.

### Negative Impact Proof
- **Definition:** A proof that no impact path from a change to a certificate exists within a declared scope.
- **Type:** $NOT\_AFFECTED(C) \iff C \notin ImpactClosure_D(E) \land Complete(D, M, P \mid \Gamma, \Sigma, Z_C)$.
- **Invariant:** Requires all three completeness dimensions.

### Complete_D
- **Definition:** All relevant dependency edges are represented.
- **Type:** A predicate on $(D, Z, C, \Sigma)$.
- **Witness:** Structural enumeration, formal proof, or empirical + confidence bound.

### Complete_M
- **Definition:** Materiality classification is correct.
- **Witness:** Materiality test suite against ground truth, or formal analysis.

### Complete_P
- **Definition:** Impact-propagation classification is correct.
- **Witness:** Propagation test suite, formal analysis, or adversarial injection with declared coverage.

### Three Completeness Dimensions (independence)
- **Invariant:** $\text{Complete}_D \not\Rightarrow \text{Complete}_M \not\Rightarrow \text{Complete}_P$.
- **Invariant:** All three are required for `NOT_AFFECTED`.

### Existential/Universal Duality (new)
- **PositiveDetection:** $\exists d : ImpactPath(d)$ — witness is a single path.
- **NegativeProof:** $\forall d : \neg ImpactPath(d)$ — witness is a completeness argument.
- **Invariant:** Positive detection is *existential*; negative proof is *universal*.
- **Invariant:** Counterexamples have asymmetric evidentiary power (from I-A07).

### Counterexample Power (recap)
- **Invariant:** A single validated impact path refutes `NOT_AFFECTED`.

### ImpactRecall (recap)
- **Type:** $\frac{|\text{true affected} \cap \text{detected}|}{|\text{true affected}|}$.

### MissedRevalidationRate (recap)
- **Type:** $\frac{|\text{materially affected, not flagged}|}{|\text{materially affected}|}$.

### FalseNegativeRate_Impact (new)
- **Type:** $FNR_\text{impact} = \frac{|\text{MissedAffectedCertificates}|}{|\text{AllAffectedCertificates}|}$.
- **Real-world:** A metric for prioritizing safety in ML-assisted impact detection.

### RepresentationLoss → DependencyObservabilityLoss (new)
- **Invariant:** If $\neg TPP(\pi, Z_C)$ for a projection $\pi$, the transformed representation cannot prove `NOT_AFFECTED` for dependencies relevant to $Z_C$.
- **Connection:** Direct link to TPP and identifiability.

### CompletenessAssessment (new)
- **Definition:** An L4 assessment that a particular completeness dimension holds for a scope.
- **Type:** A scoped assessment, not a new certificate type.
- **Rationale:** Uses existing L3/L4 machinery; no new primitive.

### ML Impact Score (new)
- **Definition:** A candidate-generation signal for impact prioritization.
- **Type:** $s \in [0, 1]$.
- **Decision rule:** Thresholds for prioritization, not for assessment.
- **Invariant:** `MLImpactScore` cannot produce `AFFECTED`, `NOT_AFFECTED`, or `UNKNOWN` directly.

---

# Part V — Worked Examples

## Example 1 — Dependency but no impact

**Setup.**

- Edge: $E_1 \to C$ with kind "provenance reference".
- $C$'s target $Z_C$ does not depend on $E_1$'s content.

**Change.** $E_1$ revised.

**Result.** `Dependency(E_1, C) = True` but `Impact(E_1, C) = False`. Certificate remains `CURRENT`.

## Example 2 — Dependency, material, but not propagating

**Setup.**

- Edge: $E_1 \to C$ with kind "material dependency".
- $Z_C$ depends on $E_1$.
- But the change to $E_1$ is a metadata update that does not alter $E_1$'s content.

**Result.** `Material(E_1, C) = True`, but `PropagatesImpact(E_1, C, \Delta) = False` for this change set.

**Assessment.** `NOT_AFFECTED(C)` if other conditions hold.

## Example 3 — Complete model → NOT_AFFECTED

**Setup.**

- $E_1 \to C_1$ and $E_1 \to C_2$.
- $D$ is certified complete for the scope.
- $M$, $P$ also certified complete.

**Change.** $E_1$ revised.

**Result.** $ImpactClosure_D(E_1) = \{C_1, C_2\}$. $C_1, C_2$ = `AFFECTED`.

Consider $C_3$ not in the closure.

**Result.** $C_3$ = `NOT_AFFECTED` (because all three completeness dimensions hold).

## Example 4 — Incomplete model → UNKNOWN

**Setup.** Same as above but $D$ is not certified complete.

**Change.** $E_1$ revised.

**Result.** $C_3$ = `UNKNOWN` (not `NOT_AFFECTED`).

**Consequence.** Conservative response: `C_3` may enter `REVALIDATION_REQUIRED` under the contract's conservative policy.

## Example 5 — TPP failure → UNKNOWN impact

**Setup.**

- $X = (F, G)$.
- $Z_C(F, G) = F$.
- $\pi(F, G) = G$.
- Two states $(0, 1)$ and $(1, 1)$ map to the same projection $G = 1$ but have different $Z$.

**Consequence.** $\neg TPP(\pi, Z_C)$. The transformed representation cannot distinguish whether $F$ affects $Z_C$.

**Result.** Impact assessment on the transformed representation: `UNKNOWN`.

**Interpretation.** The representation lost the dependency observability.

## Example 6 — Existential vs universal

**Setup A.** Detection of a positive impact: one path found.

- $E_1 \to C_1$.
- Result: `AFFECTED(C_1)`.

**Setup B.** Proof of no impact: requires universal argument.

- $D$ declared complete for scope.
- $M$ verified.
- $P$ verified.
- $C_2$ not in closure.
- Result: `NOT_AFFECTED(C_2)`.

**Asymmetry.** Setup A needed one path; Setup B needed three completeness proofs.

## Example 7 — Three-axis state

**Setup.** $A = PASS$, $I = AFFECTED$, $L = REVALIDATION_REQUIRED$.

**Meaning.** Prior assessment was PASS; a revision materially affects this certificate; awaiting revalidation.

**Setup.** $A = PASS$, $I = UNKNOWN$, $L = REVALIDATION_REQUIRED$.

**Meaning.** Prior assessment was PASS; impact unknown; conservative revalidation triggered.

**Setup.** $A = PASS$, $I = NOT_AFFECTED$, $L = CURRENT$.

**Meaning.** Prior assessment was PASS; no impact; no action.

All three are distinct and coherent.

## Example 8 — ML score does not determine impact

**Setup.** ML emits `P(Affected) = 0.02`.

**Incorrect inference.** `NOT_AFFECTED`.

**Correct handling.** Low score means "deprioritize investigation." The impact assessment remains `UNKNOWN` unless the closure + completeness pipeline produces `NOT_AFFECTED`.

## Example 9 — Acquisition triggered by UNKNOWN

**Setup.** Impact assessment = `UNKNOWN`.

**Correct response.**

$$\text{UNKNOWN} \to \text{AcquisitionFrontier} \to \text{InformationAcquisition} \to \text{ImpactAssessment'}$$

KnowledgeOS identifies which observation could distinguish `AFFECTED` from `NOT_AFFECTED` and acquires it.

**Connection.** This is the natural integration of R549's acquisition theory with the impact assessment pipeline.

## Example 10 — Contract revision as an impact source

**Setup.**

- $C$ issued under Contract $C_1$.
- Contract revision changes $C_2$ on a dimension relevant to $C$'s target.
- $D$ includes a contract-dependency edge from $C_2$ to $C$.

**Change.** Contract revision.

**Result.** $C \to REVALIDATION\_REQUIRED$.

**Note.** Impact analysis must include contract changes, not only evidence revisions.

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
        DependencyGraph (with edges typed: Established, Material, PropagatesImpact)
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality | ImpactPropagation
     CompositionStatus | EpistemicState | Revision | Acquisition
     ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     ImpactAnalysis / Revalidation
     CompletenessAssessment (Complete_D, Complete_M, Complete_P)
     CompletenessCertificate
     AssessmentStatus × ImpactAssessment × CertificateLifecycle (48 states)
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
- Propose `CandidateMateriality(E, C, score)`.
- Propose `CandidatePropagation(E, C, score)`.
- Propose `CandidateImpact(E, C, score)` — used for prioritization, not assessment.
- Propose `CandidateCompletenessSignal(D, Z, C, Σ)`.

**ML may not do:**

- Assert dependency, materiality, propagation, or impact.
- Declare completeness.
- Produce `AFFECTED`, `NOT_AFFECTED`, or `UNKNOWN` directly.
- Trigger lifecycle transitions.
- Write to X.

**Firewall:**

```
ML candidate
  → TypeCheck
  → DependencyCheck
  → MaterialityCheck
  → PropagationCheck
  → CompletenessCheck (if claiming completeness)
  → CandidateQuarantine
  → L4 Validation
  → ImpactAssessment
  → Lifecycle transition via δ
```

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Dependency}$$
$$\boxed{MLScore \not\Rightarrow ImpactAssessment}$$
$$\boxed{MLSignal \not\Rightarrow Completeness}$$
$$\boxed{LowMLScore \not\Rightarrow NOT\_AFFECTED}$$

**Adversarial benchmark for R584+:**

- **Class A:** Direct material impact.
- **Class B:** Dependency without impact (metadata-only).
- **Class C:** Material but non-propagating (change-set dependent).
- **Class D:** Hidden dependency (W2, W3).
- **Class E:** Multi-factor hidden (W7).
- **Class F:** TPP-loss-induced impact uncertainty.
- **Class G:** Adversarial similarity (high score but no impact).

**Metrics:** Dependency Precision/Recall, Materiality Precision/Recall, Propagation Recall, Impact Recall, Impact FNR, Missed Revalidation Rate, Completeness Recall, Calibration Error, Abstention Quality.

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
- **Negative impact proof conditions:** R583 passed finite tests.
- **Dependency ≠ Impact:** established.
- **Three completeness dimensions:** established.
- **Existential/Universal asymmetry:** established.
- **TPP → dependency observability loss:** established.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R583.1** — report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R583.2** — `ImpactPropagation` typed with $\Delta$.
- **R583.3** — independence of completeness dimensions typed.
- **R583.4** — completeness witnesses typed per dimension.
- **R583.5** — existential/universal duality with evidence requirements.
- **R583.6** — 48-state enumeration.
- **R584** — Completeness Certificates and Adversarial Hidden-Dependency Testing.
- **R585** — Step 545 full benchmark execution (W1–W7).
- **R586** — ML revalidation-priority benchmark.
- **R587** — full invariant engine.
- **R588** — terminology freeze.
- **R589** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Completeness composition:** open (does $Complete(D_1) \land Complete(D_2)$ imply $Complete(D_1 \cup D_2)$?).

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
- `Counterexamples have asymmetric evidentiary power`.
- `MLScore does not produce ImpactAssessment`.

### The single most important next thing

$$\boxed{\text{Build R583.1 (report discipline) and R584 (completeness certificates + adversarial hidden-dependency testing).}}$$

Because R583 has correctly established the negative-impact proof conditions, and R584 is where the theory becomes operational: how does KnowledgeOS actually *obtain* evidence strong enough to justify a completeness claim? The four approaches (closed-world, formal, empirical, adversarial) must be tested against hidden-dependency injection, and the resulting completeness certificate must be representable with existing L3/L4 machinery.

The report discipline fix is now the longest-running debt in the series and must be closed.

### Two open questions I flag

1. **Does completeness compose?** If $D_1$ is complete for scope $\Sigma_1$ and $D_2$ is complete for scope $\Sigma_2$, is $D_1 \cup D_2$ complete for $\Sigma_1 \cup \Sigma_2$? The answer is likely **no** in general — cross-scope dependencies may exist. A composition condition similar to the preservation bridge in R577 is required. R584 must address this.

2. **What is the operational fallback when completeness cannot be established?** If `Complete_D ∧ Complete_M ∧ Complete_P` cannot be certified, the impact assessment must be `UNKNOWN`. But `UNKNOWN` is not operationally actionable — it must trigger either acquisition (R549's machinery) or conservative revalidation. R584 must state which, and under what contract conditions.