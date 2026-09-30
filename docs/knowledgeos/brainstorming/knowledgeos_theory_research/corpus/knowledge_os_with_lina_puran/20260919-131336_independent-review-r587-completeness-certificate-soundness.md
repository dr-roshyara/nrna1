# Independent Review — R587 (Completeness Certificate Soundness)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R587. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R587.
2. **What R587 actually establishes** — honest scope, including the new discovery.
3. **Seven precise defects** to fix in R587.1 / R588.
4. **Full term definitions** (extended for R587).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, short status.**

---

# Part I — Verdict

R587 is the **strongest theoretical step since R577**, and it makes five contributions:

$$\boxed{ExhaustiveSearch(U) \not\Rightarrow Complete(D_O)}$$
$$\boxed{\text{UniverseClosure is a distinct requirement from Coverage}}$$
$$\boxed{CompletenessCertificate \Rightarrow VerifiedUniverseClosure}$$
$$\boxed{Certificate \to Assessment \to Coverage \to UniverseClosure \text{ — a four-level chain}}$$
$$\boxed{\text{"What justifies the boundary of the universe?" is the deepest epistemic question}}$$

Each is correct. The first is the strongest: R587's own first implementation contained a plausible-looking rule ("exhaustive search certifies completeness"), the adversarial test exposed it as wrong, and the fix was not merely a patch but a theoretical refinement — the introduction of `UniverseClosure` as a distinct requirement from `Coverage`. This is the **fourth self-falsification in the series** (following R604.4 → R604.3, R604.6 → R604.5, and R581 → its own first draft), and it is the most theoretically consequential.

The fourth is the second most important: it names a **four-level chain** (Certificate → Assessment → Coverage → UniverseClosure) and identifies the deepest layer as the *justification of the universe boundary*. This is a genuinely new epistemic insight.

R587 also correctly preserves L0–L6, refuses to add a new BC or Kernel primitive, and continues the pattern of architectural compression.

**Seven residual issues**, each requiring correction before R588:

1. `UniverseClosure` is introduced but its **witness structure** is not typed. What evidence justifies closure?
2. §5 lists seven certificate conditions (A–G) but they are not shown to be **independent**. Are some implied by others?
3. §8 reports "11 adversarial cases / 16 exhaustive cases / 0 false certificates" but the cases are not **individually listed**.
4. §9's "certificate soundness depends one level below" is correct but the **dependency chain is not typed**. Each arrow requires a proof obligation.
5. §12's invariants I-C20..I-C24 mix axioms, theorems, and rules.
6. §13's ten-question checklist is correct but not typed as a **contract object**.
7. Report discipline: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`. This is 28+ rounds of debt.

---

# Part II — What R587 Actually Establishes

## 2.1 The exhaustive-search fallacy

R587 §2 reconstructs an initially plausible rule:

$$\text{ExhaustiveSearch}(U) \Rightarrow \text{Complete}(D_O)$$

and refutes it with a counterexample:

- Declared universe $U = \{e_1, e_2\}$.
- Ground truth $D^* = U \cup \{e_3\}$.
- Exhaustive search over $U$ finds nothing outside $U$ **within $U$**, but $e_3$ is outside $U$ and was never searched.

$$\text{ExhaustiveSearch}(U) = \text{True}, \quad \text{Complete}(D_O) = \text{False}$$

**Correct.** This is a genuine counterexample, not a hypothetical.

**Real-world analog.** A registry audit that verifies the registry's internal consistency but does not verify that the registry contains every relevant entry.

## 2.2 UniverseClosure as a distinct requirement

R587 §3 defines:

$$Closed(U \mid Z, \Gamma, \Sigma, C) \iff \forall d \in D^*_{admissible}(Z, \Gamma, \Sigma, C) : d \in U$$

**Correct.** Closure is a *boundary* condition: it asserts that $U$ covers the admissible dependency universe. It is independent of what is *inside* $U$ (that's Coverage).

**Example.** A registry claims to contain "all German sensor dependencies for the 2026 election." Closure asserts that no German sensor dependency is missing; Coverage asserts that all the entries in the registry are correct and enumerated.

## 2.3 The four-level chain

R587 §9:

$$\text{Certificate} \to \text{Assessment} \to \text{Coverage} \to \text{UniverseClosure}$$

**Correct.** Each level has its own evidence requirement. An attack at any level defeats the certificate.

**Consequences.**

- A certificate can be defeated by refuting its assessment.
- An assessment can be defeated by refuting its coverage claim.
- A coverage claim can be defeated by refuting its universe closure.

Each defeat is a distinct counterexample class.

## 2.4 Certificate conditions A–G

R587 §5 lists seven conditions:

- A: Universe declared
- B: Universe closure verified
- C: Coverage verified
- D: Edge soundness verified
- E: Scope match
- F: Target match
- G: Contract match

**Correct.** These are the required conditions for a completeness certificate.

## 2.5 Counterexample search ≠ Completeness proof

R587 §6:

$$\text{CounterexampleSearch} \not\Rightarrow \text{CompletenessProof}$$

**Correct.** This restates the existential/universal asymmetry from R583 with the specific machinery of completeness certification. Large searches provide evidence, not proof.

## 2.6 ML cannot certify completeness

R587 §7:

$$MLConfidence \neq CompletenessCertificate$$

**Correct.** ML output is a candidate signal; the L4 assurance boundary is not bypassable.

## 2.7 The 16-case exhaustive result

R587 §8 reports:

- 11 adversarial cases.
- 16 exhaustive cases.
- 0 false certificate acceptances.
- 0 false certificate rejections.

**Interpretation.** The 16 cases correspond to $2^4$ combinations of two-edge ground truth and observed relations. This is a finite, exhaustive check of the certificate-soundness rule.

**This must be enumerated.** Without listing the 16 cases, the result is a claim, not an audit.

## 2.8 Architecture discipline

R587 §10 states:

> No new Kernel primitive. No new bounded context. No new architectural layer.

**Correct.**

---

# Part III — Seven Defects to Fix in R587.1 / R588

## Defect 1 — `UniverseClosure` witness structure is not typed

R587 §3 defines closure as a predicate but does not state what evidence justifies it.

**Corrected typing.**

$$Closed(U \mid Z, \Gamma, \Sigma, C) \iff \exists w : \text{Witness}_{closure}(w, U, Z, \Gamma, \Sigma, C)$$

with witness types:

- **Structural:** an exhaustive enumeration of dependency-producing relations, with the relations themselves verified as covering the admissible dependency types.
- **Registry-based:** an authoritative registry is declared as exhaustive for the scope, with an independent audit.
- **Formal:** a theorem that the declared model of admissible dependencies is closed under the operations that generate them.
- **Generator-based:** a generator is proven to produce exactly the admissible dependency set.
- **Adversarial:** a set of injection tests demonstrating that no hidden dependency within the declared types escapes the universe.
- **Hybrid:** a combination declared by contract.

Each witness type has its own proof obligations. R588 must enumerate them.

## Defect 2 — Independence of conditions A–G is not established

R587 §5 lists A–G. Are they independent, or does one imply another?

**Corrected typing.**

- A (Universe declared) is a *precondition* for B.
- B (Universe closure) implies nothing about C, D, E, F, G.
- C (Coverage) requires A and B.
- D (Edge soundness) is independent of A–C.
- E, F, G (Scope/Target/Contract match) are independent of A–D.

**Consequence.** The conditions form a *conjunction*, not a chain. All seven must be satisfied. Removing any one invalidates the certificate.

**Counterexample.** A certificate with A, B, C, D satisfied but E (scope match) violated: the certificate is invalid.

## Defect 3 — The 16 exhaustive cases are not enumerated

R587 §8 reports the count but not the cases.

**Required in R587.1.** Full enumeration table:

| # | $D^*$ | $D_O$ | $U$ | Expected Certificate | Actual | Correct? |
|---|---|---|---|---|---|---|
| 1 | ∅ | ∅ | ∅ | INVALID | INVALID | ✓ |
| 2 | {e₁} | ∅ | ∅ | INVALID | INVALID | ✓ |
| ... | ... | ... | ... | ... | ... | ... |
| 16 | {e₁,e₂} | {e₁,e₂} | {e₁,e₂} | VALID | VALID | ✓ |

Without this, "16/16 passed" is not auditable.

## Defect 4 — The four-level chain is not typed as proof obligations

R587 §9 states:

$$\text{Certificate} \to \text{Assessment} \to \text{Coverage} \to \text{UniverseClosure}$$

But each arrow is not typed as an obligation.

**Corrected typing.**

- **UniverseClosure → Coverage:** if $U$ is closed and every element of $U$ is enumerated, then Coverage holds.
- **Coverage → Assessment:** if Coverage, Edge Soundness, Scope/Target/Contract match hold, then Assessment holds.
- **Assessment → Certificate:** if Assessment is `ESTABLISHED`, a certificate can be issued.

Each arrow is a *rule* with preconditions. Failure at any level invalidates all downstream claims.

## Defect 5 — Invariants I-C20..I-C24 mix categories

R587 §12 lists five invariants. Split into:

- **Theorem:** I-C20 (exhaustive search is universe-relative).
- **Axiom/Rule:** I-C21 (universe closure mandatory for certification).
- **Definition:** I-C22 (universe closure is target-relative).
- **Rule:** I-C23 (certificate ≠ assessment).
- **Observation:** I-C24 (counterexample search ≠ closure).

The same taxonomy used in R574, R578, R579, R581, R582, R583, R584.

## Defect 6 — The ten-question checklist is not typed

R587 §13 lists ten questions but does not type them as a contract object.

**Corrected typing.**

$$CompletenessContract = (Universe, Scope, Target, Regime, Contract, Coverage, Soundness, TemporalValidity, ContractMatch, Authority)$$

Each field is checked independently. The contract is satisfied iff all checks pass.

## Defect 7 — Report discipline

Same pattern. 28+ rounds of debt.

**Recommendation.** R587.1 must produce, for each test:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R587.1.<k>",
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

## R574–R586 (recap)

### Admission
- **Type:** $\{Admitted, Rejected, Conditional, Unknown\}$.

### BelnapValue
- **Type:** $\{T, F, B, N\}$.

### EpistemicState
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.

### CertificateLifecycle
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

### AssessmentStatus
- **Type:** $\{PASS, FAIL, UNKNOWN, CONDITIONAL\}$.

### ImpactAssessment
- **Type:** $\{AFFECTED, NOT\_AFFECTED, UNKNOWN\}$.

### CompletenessStatus
- **Type:** $\{ESTABLISHED, CONDITIONAL, UNKNOWN\}$, with $REFUTED$ proposed.

### Complete_D, Complete_M, Complete_P
- **Types:** Three independent completeness predicates.

### $D^*$, $D_O$, $D_C$, $D_E$
- **Types:** Four-level dependency relations.

## R587 new terms

### DeclaredUniverse (U)
- **Definition:** The declared dependency universe for a specific claim.
- **Type:** $U \subseteq \text{admissible dependencies}$.
- **Real-world:** "All German sensor dependencies."
- **Invalid:** Treating $U$ as the entire world.

### UniverseClosure (corrected)
- **Definition:** The property that a declared universe covers every admissible dependency for a claim.
- **Type:** $Closed(U \mid Z, \Gamma, \Sigma, C) \iff \forall d \in D^*_{admissible} : d \in U$.
- **Witness:** Typed as structural, registry-based, formal, generator-based, adversarial, or hybrid.
- **Invariant:** $ExhaustiveSearch(U) \not\Rightarrow Closed(U)$.

### Coverage
- **Definition:** The observed/established dependency set covers the declared universe.
- **Type:** $Coverage(D_O, U) \iff U \subseteq D_O \cup (\text{declared-absent in } U)$.

### Coverage Witness
- **Type:** Evidence that every element of $U$ is accounted for.

### EdgeSoundness
- **Definition:** The dependencies in the set are valid (established, material, correctly typed).
- **Type:** $Sound(D_O)$.

### CompletenessAssessment (recap, refined)
- **Type:** $(Basis, Evidence, \Gamma, \Sigma, Result)$ where $Result \in \{ESTABLISHED, CONDITIONAL, UNKNOWN, REFUTED\}$.

### CompletenessCertificate (recap, refined)
- **Type:** $(Assessment, Signature, Time, Validity)$.
- **Invariant:** $CompletenessAssessment \neq CompletenessCertificate$.

### Certificate Conditions (A–G)
- A: Universe declared.
- B: Universe closure verified.
- C: Coverage verified.
- D: Edge soundness verified.
- E: Scope match.
- F: Target match.
- G: Contract match.
- **Invariant:** All seven are independently required; the conditions form a conjunction, not a chain.

### CompletenessContract (new)
- **Type:** $(Universe, Scope, Target, Regime, Contract, Coverage, Soundness, TemporalValidity, ContractMatch, Authority)$.
- **Real-world:** A declared audit specification.

### Four-Level Chain
- **Type:** Certificate → Assessment → Coverage → UniverseClosure.
- **Each arrow:** A rule with preconditions.
- **Invariant:** Failure at any level invalidates all downstream claims.

### I-C20 (Theorem)
- **Statement:** $ExhaustiveSearch(U) \not\Rightarrow Complete(D_O)$.
- **Proof:** Refuted by the counterexample $U \subsetneq D^*$.

### I-C21 (Rule)
- **Statement:** $CompletenessCertificate \Rightarrow VerifiedUniverseClosure$.

### I-C22 (Definition)
- **Statement:** $Closed(U \mid Z_1) \not\Rightarrow Closed(U \mid Z_2)$ — target-relative.

### I-C23 (Rule)
- **Statement:** $CompletenessAssessment \neq CompletenessCertificate$.

### I-C24 (Observation)
- **Statement:** $CounterexampleSearch \not\Rightarrow UniverseClosure$.

### REFUTED (proposed CompletenessStatus value)
- **Definition:** A completeness claim that has been defeated by a counterexample.
- **Recommendation:** Distinct from `UNKNOWN`, strictly weaker (a refuted claim was previously asserted).
- **Invariant:** Refutation cascades to all downstream `NOT_AFFECTED` conclusions.

---

# Part V — Worked Examples

## Example 1 — The exhaustive-search fallacy

**Setup.**

- Declared $U = \{e_1, e_2\}$.
- Exhaustive search within $U$.
- Ground truth $D^* = \{e_1, e_2, e_3\}$.

**Naïve inference.** `Complete(D_O)`.

**Correct result.** Not complete. $e_3 \notin U$.

**Reason.** Exhaustive search inside $U$ establishes Coverage, not UniverseClosure.

## Example 2 — Registry-based closure

**Setup.** Contract: "Registry $R$ enumerates every admissible dependency for election $E$."

**Witness.** An independent audit certificate attesting that $R$ is exhaustive for $E$.

**Result.** $Closed(U \mid Z, \Gamma, \Sigma, C)$ is verified.

## Example 3 — Four-level chain

**Setup.** A certificate is issued with the four-level chain satisfied.

**Attack.** A counterexample refutes the closure witness.

**Cascade.**

- UniverseClosure: refuted.
- Coverage: invalidated.
- Assessment: invalidated.
- Certificate: revoked.

**Consequence.** All `NOT_AFFECTED` claims depending on this certificate become `UNKNOWN` or `REFUTED`.

## Example 4 — Independence of conditions A–G

**Setup.**

- A: satisfied.
- B: satisfied.
- C: satisfied.
- D: violated (an edge in $D_O$ is invalid).

**Result.** Certificate invalid. Conjunction fails.

## Example 5 — Target-relative closure

**Setup.**

- $U$ = "all dependencies of sensor $S$."
- Target $Z_1$ = "physical temperature."
- Target $Z_2$ = "sensor calibration offset."

**Analysis.**

- $U$ is closed for $Z_1$ (all temperature dependencies enumerated).
- $U$ is not necessarily closed for $Z_2$ (calibration offsets may not be captured).

**Result.** $Closed(U \mid Z_1) \neq Closed(U \mid Z_2)$.

## Example 6 — Scope mismatch prevents certification

**Setup.**

- Closure verified for scope $\Sigma_1$ = {German sensors}.
- Certificate claimed for scope $\Sigma_2$ = {European sensors}.

**Result.** $Scoped(CB) = False$ for $\Sigma_2$. Certificate invalid.

## Example 7 — Counterexample search ≠ proof

**Setup.** 10 million candidate dependencies searched; none found.

**Naïve inference.** `Complete(D_O)`.

**Correct result.** Not proven. Unless the search is exhaustive over a verified finite universe, it is evidence, not proof.

## Example 8 — ML confidence vs certificate

**Setup.** ML emits `P(complete) = 0.998`.

**Correct handling.** Used as a candidate signal for prioritization. Not sufficient to issue a certificate.

## Example 9 — Composition of closure

**Setup.**

- $Closed(U_1 \mid Z, \Gamma, \Sigma_1)$ verified.
- $Closed(U_2 \mid Z, \Gamma, \Sigma_2)$ verified.
- Claim for $\Sigma_1 \cup \Sigma_2$.

**Counterexample.** A dependency crosses $\Sigma_1$ and $\Sigma_2$.

**Result.** $Closed(U_1 \cup U_2 \mid Z, \Gamma, \Sigma_1 \cup \Sigma_2) = \text{False}$. Composition requires the cross-scope condition.

## Example 10 — Refutation cascade

**Setup.**

- $CB_1$ certified for $\Sigma$.
- $CB_2$ certified for $\Sigma' \subset \Sigma$.
- $CB_3$ uses $CB_1$.

**Refutation of $CB_1$.**

- $CB_1$: `REFUTED`.
- $CB_2$: unaffected (subscope).
- $CB_3$: invalidated.

**Consequence.** Refutations propagate through certificate-dependency edges, not merely the dependency graph.

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
        DeclaredUniverse U
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
     UniverseClosure / Coverage / EdgeSoundness
     CompletenessCertificate (with conditions A–G)
     Certificate → Assessment → Coverage → UniverseClosure
     Refutation calculus
     GovernanceAuthority
                              │
                     L5 Intelligence
       CandidateDependency / CandidateGap
       CandidateCompletenessSignal / CandidateRefutation / OOD
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
- Propose `CandidateClosureSignal(U, Z, Γ, Σ, score)` — as a candidate.
- Propose `CandidateRefutation(CB, CE, score)`.

**ML may not do:**

- Assert dependency, materiality, coverage, or closure.
- Issue or revoke a certificate.
- Extend $D_E$ without L4 validation.
- Write to X.

**Firewall:**

```
ML candidate
  → TypeCheck
  → DependencyCheck
  → ClosureCheck (only if supporting a declared basis)
  → RefutationValidation (L4)
  → CandidateQuarantine
  → L4 Validation
  → Assessment / Refutation
```

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Dependency}$$
$$\boxed{MLScore \not\Rightarrow ClosureProof}$$
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
- **Logical regime boundaries, cross-regime composition, target-preserving composition, global closure:** executed.
- **Dependency + Acquisition + Revision + Composition:** executed.
- **Certificate lifecycle + selective revalidation:** executed.
- **Assessment × Lifecycle state machine:** executed.
- **Selective revalidation impact closure:** executed.
- **Negative impact proof conditions:** executed.
- **Completeness basis formalized:** executed.
- **Adversarial completeness benchmark:** executed.
- **Completeness certificate soundness:** R587 passed (11 adversarial, 16 exhaustive, 0 false acceptances).
- **UniverseClosure distinct from ExhaustiveSearch:** established.
- **Certificate → Assessment → Coverage → UniverseClosure chain:** established.
- **I-C20..I-C24:** proposed.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R587.1** — report discipline + enumeration of the 11 + 16 cases.
- **R587.2** — `UniverseClosure` witness types typed.
- **R587.3** — independence of A–G proven.
- **R587.4** — four-level chain typed as proof obligations.
- **R587.5** — invariants split by category.
- **R587.6** — `CompletenessContract` typed.
- **R588** — Universe Closure Calculus.
- **R589** — Step 545 full benchmark (W1–W7) with metrics.
- **R590** — ML revalidation-priority benchmark.
- **R591** — full invariant engine.
- **R592** — terminology freeze.
- **R593** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Completeness composition across scopes:** open.
- **UniverseClosure witness structure:** R588 target.
- **Open-world environments:** R588 target.

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
- `ExhaustiveSearch(U) ⇏ Complete(D_O)`.
- `CompletenessCertificate ⇒ VerifiedUniverseClosure`.
- `UniverseClosure is target-relative`.
- `Certificate ≠ Assessment`.
- `CounterexampleSearch ⇏ UniverseClosure`.

### The single most important next thing

$$\boxed{\text{Build R587.1 (report discipline + enumeration) and R588 (Universe Closure Calculus).}}$$

Because R587 has produced the deepest epistemic refinement in the series — the recognition that *exhaustive search is insufficient without universe closure* — and R588 is where the closure conditions become provable or refutable. The report-discipline fix is now 28+ rounds overdue and remains the single largest auditability gap.

### Two open questions I flag

1. **Is UniverseClosure decidable in open-world environments?** R587 §15 proposes R588 should test four regimes, of which the fourth is "open-world." My expectation: closure is not decidable in general, but *bounded* or *conditional* closure may be. R588 must state the boundary conditions. This is where I would introduce an external reference if needed (e.g., work on model closure / certification in formal methods).

2. **Does refutation of `UniverseClosure` cascade to `ImpactAssessment`?** If a closure witness is refuted, all downstream `NOT_AFFECTED` claims must become `UNKNOWN`. But should they become `UNKNOWN` (re-queue for revalidation) or `REFUTED` (admit they were wrong)? My recommendation: `UNKNOWN` — because the impact is now unresolved, not necessarily wrong. R588 must decide.