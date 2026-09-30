# Independent Review — R586 (Adversarial Completeness Benchmark)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R586. No external book needed; if a theorem requires one I will name it.

**Note:** This is the same R586 document reviewed in the previous turn. Rather than repeat, I will focus on (a) the residual issues that persisted through that review, and (b) the **forward step into R587**, which the document itself explicitly requests. My review will be shorter and more directed than the last one.

---

# Part I — Verdict (concise)

R586 remains a **correct and empirically valuable round**. Its core results stand:

$$\boxed{6 \text{ unsafe false-}NOT\_AFFECTED \text{ cases found}}$$
$$\boxed{64/64 \text{ exhaustive finite completeness cases passed}}$$
$$\boxed{HiddenDependency \neq MaterialImpact}$$
$$\boxed{I\text{-}C19: NOT\_AFFECTED \Rightarrow EstablishedCompleteness}$$

None of these is weakened by re-reading. But the same seven defects flagged previously remain unfixed:

1. The 17 scenarios / 6 false-negative / 7 false-completeness / 10 UNKNOWN-preservation counts are **not enumerated**.
2. $D^*$ is **not specified per world**.
3. `FalseCompleteness` vs `FalseNOT_AFFECTED` containment is **not typed**.
4. Hidden-materiality propagation rule is **not stated**.
5. `I-C19`'s `D_E` scoping is **not restated**.
6. Specific limitations are **not enumerated**.
7. Report discipline: **no `ExecutionRunID`, no `certificate.json`**.

These must be closed in R586.1. But R586's own closing paragraph requests R587, and that is the correct next step. Below I specify R587 and its relation to the still-unfixed items.

---

# Part II — Why R587 is the Correct Next Step

R586 proved:

$$D_O = \emptyset \land D^* = \{E_1 \to C\} \text{ is possible, and produces 6 unsafe } NOT\_AFFECTED \text{ cases in a naïve system.}$$

R584 established that:

$$\text{CompletenessBasis} = (Mode, Scope, Target, Contract, Coverage, Verification)$$

R586 established that a completeness claim can be **defeated** by a hidden dependency.

The natural next question is therefore:

$$\boxed{\text{What evidence is sufficient for a completeness claim to become a valid L4 certificate?}}$$

But before answering it, R586's paragraph "R587 — Completeness Certificate Soundness" correctly proposes four separations:

$$\boxed{CompletenessBasis \neq CompletenessEvidence \neq CompletenessAssessment \neq CompletenessCertificate}$$

This is the right framing. R587 must build on it.

---

# Part III — R587 Specification: Completeness Certificate Soundness

## 3.1 The four-way separation (typed)

R586's paragraph lists four concepts but does not type them. R587 must:

**CompletenessBasis**

$$\text{Basis} = (Mode, Scope, Target, Contract)$$

The *declared mechanism* by which completeness is claimed to hold. Modes: `closed-world`, `exhaustive-finite`, `verified-generator`, `formal`, `adversarial`, `hybrid`.

**CompletenessEvidence**

$$\text{Evidence} = (Basis, Content, Provenance, Time)$$

The *content* — the enumeration, the proof, the audit record, the injection test results. This is what the basis produces.

**CompletenessAssessment**

$$\text{Assessment} = (Basis, Evidence, \Gamma, \Sigma, Result)$$

The *evaluation* that the evidence sufficiently supports the basis, under a regime and scope.

$$Result \in \{ESTABLISHED, CONDITIONAL, UNKNOWN\}$$

**CompletenessCertificate**

$$\text{Certificate} = (Assessment, Signature, Time, Validity)$$

The *durable, signed artifact* that can be cited as an assurance. The certificate is not the assessment; the certificate *attests to* the assessment.

The four-way separation is the R587 deliverable's first requirement.

## 3.2 The counterexample calculus

R586's paragraph asks:

> can a completeness certificate be falsified by a counterexample?

This is the correct question. R587 must construct:

**R587.1 — Direct counterexample.** Find a dependency $d \in D^* \setminus D_E$ within the declared scope. This refutes the certificate.

**R587.2 — Scope counterexample.** Find a dependency within scope $\Sigma$ that the certificate's basis claims to cover, but that is absent.

**R587.3 — Materiality counterexample.** Find a materiality classification error: an edge declared `material = False` that is actually material to $Z$.

**R587.4 — Propagation counterexample.** Find a change set $\Delta$ for which an edge declared non-propagating actually propagates.

**R587.5 — Construction counterexample.** Show that the basis's enumeration is not exhaustive: exhibit an admissible edge not in the enumeration.

**R587.6 — Verification counterexample.** Show that the verification record is inconsistent (e.g., timestamp before the enumeration, signature invalid, scope mismatch).

**R587.7 — Adversarial counterexample.** ML or hand-constructed injection of a hidden dependency that defeats the basis.

Each refutes the certificate. Any refutation triggers `CompletenessStatus := UNKNOWN` (or a new `REFUTED` state) and cascades to all `NOT_AFFECTED` conclusions within the certificate's scope.

## 3.3 The minimal sufficiency conditions

R587's central deliverable is the statement of **minimal conditions** for a completeness certificate to be sound:

$$\boxed{\text{Sound}(CB) \iff \text{Auditable}(CB) \land \text{Scoped}(CB) \land \text{Refutable}(CB)}$$

- **Auditable:** the basis and its evidence can be inspected and re-verified.
- **Scoped:** the basis is declared for a specific $(Z, \Gamma, \Sigma)$ and does not silently extend.
- **Refutable:** the basis admits counterexamples; there is a procedure by which it can fail.

A certificate that is not refutable is not a certificate — it is an assertion. R587 must formalize this.

## 3.4 Composition of completeness bases

R586 left this open. R587 must type it:

$$\text{Sound}(CB_1, Z_1, \Gamma_1, \Sigma_1) \land \text{Sound}(CB_2, Z_2, \Gamma_2, \Sigma_2) \not\Rightarrow \text{Sound}(CB_1 \land CB_2, Z_1 \land Z_2, \Gamma_1 \land \Gamma_2, \Sigma_1 \cup \Sigma_2)$$

unless a **composition condition** holds:

$$\text{Composable}(CB_1, CB_2) \iff \text{no cross-scope dependency exists between } \Sigma_1 \text{ and } \Sigma_2$$

The condition is analogous to the preservation-bridge condition from R577. R587 must state it.

## 3.5 The certificate soundness theorem

R587 should prove (or refute):

$$\text{Sound}(CB) \Rightarrow \forall d \in D^* \text{ within } (Z, \Gamma, \Sigma) : d \in D_E$$

with proof by the soundness conditions. Counterexamples would defeat the theorem.

---

# Part IV — Terms (extended for R587)

The following additions to the vocabulary are required by R587's central questions. They extend R586 without introducing new Kernel primitives.

### CompletenessBasis (typed)
- **Definition:** The declared mechanism by which completeness is claimed.
- **Type:** $(Mode, Scope, Target, Contract)$.
- **Real-world:** "The registry enumerates all sources."
- **Invalid:** An unstated basis.

### CompletenessEvidence (new)
- **Definition:** The content produced by a basis.
- **Type:** $(Basis, Content, Provenance, Time)$.
- **Real-world:** An enumeration log, a formal proof, an audit certificate.
- **Invalid:** An assertion without evidence.

### CompletenessAssessment (existing, refined)
- **Definition:** The L4 evaluation that evidence supports a basis.
- **Type:** $(Basis, Evidence, \Gamma, \Sigma, Result)$ where $Result \in \{ESTABLISHED, CONDITIONAL, UNKNOWN\}$.
- **Real-world:** An L4 verdict that the registry is complete for the scope.

### CompletenessCertificate (existing, refined)
- **Definition:** A durable signed artifact attesting to a CompletenessAssessment.
- **Type:** $(Assessment, Signature, Time, Validity)$.
- **Real-world:** A signed audit report.
- **Invariant:** $Certificate \neq Assessment \neq Evidence \neq Basis$.

### Sound(CB)
- **Definition:** The certificate is auditable, scoped, and refutable.
- **Type:** $Sound(CB) \iff Auditable(CB) \land Scoped(CB) \land Refutable(CB)$.
- **Real-world:** A registry audit whose scope is declared and whose tests can fail.

### Auditable(CB)
- **Definition:** The basis and its evidence can be inspected and re-verified.
- **Type:** A predicate on $CB$.
- **Invalid:** An opaque black-box completeness claim.

### Scoped(CB)
- **Definition:** The basis is declared for a specific $(Z, \Gamma, \Sigma)$.
- **Type:** A predicate on $CB$.
- **Invalid:** A completeness claim silently extended to a new scope.

### Refutable(CB)
- **Definition:** The basis admits counterexamples; there is a procedure by which it can fail.
- **Type:** A predicate on $CB$.
- **Invalid:** A basis that cannot be tested.

### Refutation(CB, CE)
- **Definition:** A counterexample CE that defeats CB.
- **Type:** $(CB, CE)$ where CE is a valid counterexample.
- **Effect:** $CompletenessStatus(CB) := UNKNOWN$ or $REFUTED$.
- **Cascade:** All `NOT_AFFECTED` conclusions within CB's scope require re-evaluation.

### Composable(CB₁, CB₂)
- **Definition:** Two completeness bases compose when no cross-scope dependency exists between their scopes.
- **Type:** $Composable(CB_1, CB_2) \iff \neg \exists d : d \text{ crosses } \Sigma_1 \leftrightarrow \Sigma_2$.
- **Invariant:** $Sound(CB_1) \land Sound(CB_2) \not\Rightarrow Sound(CB_1 \land CB_2)$ without `Composable`.

### REFUTED (new CompletenessStatus value)
- **Definition:** A completeness claim has been defeated by a counterexample.
- **Type:** An extension of the CompletenessStatus lattice.
- **Lattice:** $UNKNOWN \sqsubset CONDITIONAL \sqsubset ESTABLISHED$, with $REFUTED$ as a distinct state (not comparable, or strictly below UNKNOWN).

---

# Part V — Worked Examples

## Example 1 — Direct refutation

**Setup.** CB claims `Complete(D_E | Z, Γ, Σ)` for scope Σ = {sensors 1–5}. Evidence: enumeration of edges among sensors 1–5.

**Counterexample.** A hidden edge from sensor 3 to sensor 7 within Σ (sensor 7 was in scope but excluded from the enumeration).

**Result.** $Refuted(CB)$. $CompletenessStatus := REFUTED$. All `NOT_AFFECTED` claims within Σ become `UNKNOWN`.

## Example 2 — Scope counterexample

**Setup.** CB is declared for scope Σ₁ = {sensor model 2}. Claim extends to Σ₂ = {sensor model 3}.

**Counterexample.** No counterexample is needed — the scope violation is itself a refutation of the certificate as applied to Σ₂.

**Result.** $Scoped(CB) = False$ for Σ₂. `CompletenessStatus := UNKNOWN` for Σ₂.

## Example 3 — Materiality counterexample

**Setup.** CB declares edge $(E_1, C)$ with `material = False` for target $Z$.

**Counterexample.** A state pair $s, s'$ differing only in $E_1$ with $Z(s) \neq Z(s')$.

**Result.** $Refuted(CB)$. Materiality classification was wrong. `CompletenessStatus := REFUTED`.

## Example 4 — Composition failure

**Setup.** CB₁ complete for Σ₁ = {German sensors}, CB₂ complete for Σ₂ = {Austrian sensors}.

**Attempted composition.** CB₁ ∧ CB₂ claimed for Σ₁ ∪ Σ₂.

**Cross-scope counterexample.** A German sensor feeds an Austrian certificate via a shared model.

**Result.** $Composable(CB_1, CB_2) = False$. $Sound(CB_1 \land CB_2) = False$.

## Example 5 — Auditable but not refutable

**Setup.** CB claims completeness from an internal audit whose procedure cannot be inspected (opaque implementation).

**Result.** $Auditable(CB) = False$. $Sound(CB) = False$.

**Consequence.** The certificate is not an assurance artifact; it is an assertion.

## Example 6 — The four-way separation in action

**Basis:** "Registry enumeration is exhaustive."

**Evidence:** A log file listing all registry entries with timestamps and hashes.

**Assessment:** L4 verdict: $ESTABLISHED$ for scope Σ.

**Certificate:** A signed artifact referencing the assessment, with validity window and signature.

**Invalid conflation.** Treating the log file as a certificate; treating the basis as the assessment.

## Example 7 — Refutation with cascade

**Setup.** CB₁ complete for Σ. CB₂ complete for Σ' ⊂ Σ (subscope). CB₃ applies CB₁ to a certificate C within Σ.

**Counterexample against CB₁.** A hidden edge within Σ.

**Cascade.**
- CB₁ refuted.
- CB₂ not directly affected (different subscope) — but review required.
- CB₃ invalidated: `NOT_AFFECTED(C)` retracted; $C \to REVALIDATION\_REQUIRED$.

**Consequence.** Refutations propagate through the certificate-dependency graph, not merely the dependency graph.

---

# Part VI — Architecture, ML Positioning, Short Status

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
        DependencyGraph (D_O, D_C, D_E)
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality | ImpactPropagation
     ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}
     CompletenessAssessment ∈ {ESTABLISHED, CONDITIONAL, UNKNOWN, REFUTED}
     Zero
                              │
                     L4 Assurance
     Invariants / Verification / Counterexample
     Spec → Run(ExecutionRunID) → Result → Cert
     ImpactAnalysis / Revalidation
     CompletenessBasis / CompletenessEvidence
     CompletenessAssessment / CompletenessCertificate
     Refutation calculus (7 classes)
     Sound(CB) = Auditable ∧ Scoped ∧ Refutable
     Composable(CB₁, CB₂) — cross-scope condition
     GovernanceAuthority
                              │
                     L5 Intelligence
       CandidateDependency (D_C)
       CandidateGap / CandidateCompletenessSignal
       CandidateRefutation
                              │
                     L6 Governance
       Authority / Permission / Decision
       UseDecision (derived)
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

**ML may do:**

- Propose `CandidateDependency(E, C, score)`.
- Propose `CandidateGap(D_E)` — a missing-edge hypothesis.
- Propose `CandidateRefutation(CB, CE, score)` — a suspected counterexample.
- Propose `CandidateImpact(E, C, score)`.

**ML may not do:**

- Assert dependency, materiality, or impact.
- Declare completeness.
- Issue or revoke a completeness certificate.
- Write to X.

**Firewall:**

```
ML candidate (D_C or CandidateRefutation)
  → TypeCheck
  → DependencyCheck
  → MaterialityCheck
  → PropagationCheck
  → RefutationValidation (L4)
  → CandidateQuarantine
  → L4 Validation → D_E or Refutation
```

**Cardinal rules:**

$$\boxed{MLScore \not\Rightarrow Refutation}$$
$$\boxed{MLCandidateGap \not\Rightarrow Counterexample}$$
$$\boxed{MLSignal \not\Rightarrow Completeness}$$

## VI.3 — Short bullet status

### Achieved

- **Kernel:** stable across 600+ rounds; no new primitive.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency (multi-factor, target/scope/regime-relative):** executable.
- **Admission, regime compatibility, regime bridge:** executable.
- **Logical regime boundaries (R575), cross-regime composition (R576), target-preserving composition (R577), global closure (R578):** executed.
- **Dependency + Acquisition + Revision + Composition (R579):** executed.
- **Certificate lifecycle + selective revalidation (R580):** executed.
- **Assessment × Lifecycle state machine (R581):** executed.
- **Selective revalidation impact closure (R582):** executed.
- **Negative impact proof conditions (R583):** executed.
- **Completeness basis formalized (R584):** executed.
- **Adversarial completeness benchmark (R586):** executed — 6 unsafe false-`NOT_AFFECTED` cases found, 64/64 exhaustive cases passed.
- **Four-level dependency distinction (D\*, D_O, D_C, D_E):** established.
- **I-C19:** `NOT_AFFECTED` requires established completeness.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R586.1** — report discipline + full enumeration of the 17/6/7/10/64 cases.
- **R586.2** — $D^*$ per world.
- **R586.3** — containment `FalseNOT_AFFECTED ⇒ FalseCompleteness`.
- **R587** — Completeness Certificate Soundness: four-way separation, seven refutation classes, `Sound(CB)` conditions, composition condition.
- **R588** — Step 545 full benchmark with metrics.
- **R589** — ML revalidation-priority with adversarial robustness.
- **R590** — full invariant engine.
- **R591** — terminology freeze.
- **R592** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction:** open.
- **Completeness composition (cross-scope):** R587 target.
- **Minimal sufficiency for `Sound(CB)`:** R587 target.

### Standing rules (consolidated)

- No new Kernel primitive.
- No new BC, no new layer.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- Report discipline: `ExecutionRunID` + `certificate.json` per test.
- ML cannot write to X, cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.
- `Greedy ≠ Optimal` under synergy.
- `AdmissionStatus`, `BelnapValue`, `CompletenessStatus` are lattices.
- `Conflict ≠ Contradiction`; `RegimeDifference ≠ Conflict`.
- `Valid(B₁) ∧ Valid(B₂) ⇏ Valid(B₂ ∘ B₁)` without conditions.
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
- `UNKNOWN ≠ FALSE`.
- `FalseNegativeImpact is the most dangerous failure`.
- `D* ≠ D_O ≠ D_C ≠ D_E`.
- `HiddenDependency ≠ MaterialImpact`.
- `FalseNOT_AFFECTED ⇒ FalseCompleteness`.
- `I-C19: NOT_AFFECTED requires established completeness basis`.
- `CompletenessBasis ≠ CompletenessEvidence ≠ CompletenessAssessment ≠ CompletenessCertificate` (R587).
- `Sound(CB) = Auditable(CB) ∧ Scoped(CB) ∧ Refutable(CB)` (R587).
- `Refutation cascades to NOT_AFFECTED conclusions within CB's scope` (R587).
- `Sound(CB₁) ∧ Sound(CB₂) ⇏ Sound(CB₁ ∧ CB₂)` without `Composable` (R587).

### The single most important next thing

$$\boxed{\text{Build R586.1 (report discipline + enumeration) and R587 (Completeness Certificate Soundness).}}$$

R586's empirical finding (6 unsafe false-`NOT_AFFECTED` cases) is the strongest negative result in the series, and it is worth only as much as its auditability. The report-discipline fix — with `ExecutionRunID`, `certificate.json`, and full case enumeration — is now 27+ rounds overdue. R587 then attacks the completeness certificate itself, which is the natural next step before ML enters.

### Two open questions I flag

1. **Should `REFUTED` be a distinct CompletenessStatus value, or is `UNKNOWN` sufficient after a refutation?** My recommendation: `REFUTED` is distinct and strictly below `UNKNOWN` — a refuted claim is *worse* than an unknown one, because it was previously asserted and is now known false. This distinction affects cascade semantics. R587 must decide.

2. **Does refutation cascade through the certificate-dependency graph or only through the dependency graph?** If CB₁ is refuted, do all `NOT_AFFECTED` conclusions *depending on CB₁* become `UNKNOWN`, or only those *within CB₁'s scope*? My recommendation: both, but as distinct cascades with different audit trails. R587 must construct the test.