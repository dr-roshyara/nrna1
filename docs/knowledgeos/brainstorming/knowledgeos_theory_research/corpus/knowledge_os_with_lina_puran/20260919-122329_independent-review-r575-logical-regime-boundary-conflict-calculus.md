# Independent Review — R575 (Logical-Regime Boundary and Conflict Calculus)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R575. No external book needed; if a theorem demands it I will name the exact reference.

I will do six things:

1. **Verdict** on R575.
2. **What R575 actually establishes.**
3. **Six precise defects** to fix in R575.1 / R576.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R575 is a **correct and disciplined step**, and it makes four substantive contributions:

$$\boxed{\Gamma_1 \neq \Gamma_2 \not\Rightarrow Conflict}$$
$$\boxed{Unknown \neq False}$$
$$\boxed{MLCandidate \neq EstablishedTranslation}$$
$$\boxed{Conflict \neq Contradiction}$$

Each is correct. The third is the most important operationally: it extends the ML firewall to regime-bridge generation, and it does so by *reusing* the existing Candidate → Validation → Assessment → Certificate chain rather than inventing a new mechanism. That is exactly the theory-compression discipline.

R575 also makes one architecturally correct decision: it refuses to introduce `LogicalRegimeBC`, `ConflictBC`, `RegimeBC`, or any new Bounded Context. The pipeline

$$\text{Evidence} \to \text{Context} \to \text{Meaning} \to \text{Regime} \to \text{Compatibility} \to \text{Admission} \to \text{Assessment} \to \text{Conflict/Contradiction}$$

fits within L0–L6. That is consistent with the discipline established since R601.

**Six residual issues**, each requiring correction before R576:

1. The four-valued Belnap lattice `{T, F, B, N}` is used but its **meet and join** are not formalized. This is required for composition.
2. The "unknown regime" case is admitted as `UNKNOWN` but the lattice does not say how `UNKNOWN` composes with `T`, `F`, `B`, `N`.
3. The RegimeBridge candidate from ML is distinguished from an established bridge, but the **exact rule for promotion** is not stated.
4. §10 (two doctors) and §11 (database) are correct in spirit, but the *conditions of comparison* they list are not typed as a first-class object. R574 already introduced `Scope`, `Context`, `Regime`, and `Compatibility` — but the "comparison contract" that precedes conflict is missing.
5. The report does not surface `ExecutionRunID`, printed counterexamples, or `certificate.json`. This is the same report-discipline gap flagged since R604.3.
6. §15 explicitly limits the result to finite model checks — good — but the *scope* of that limitation is not stated (what, exactly, has been verified, and what has not).

---

# Part II — What R575 Actually Establishes

## 2.1 The regime-difference ≠ conflict lemma

R575 correctly demonstrates that two sources operating under different logical regimes do **not** automatically conflict. The proposed sequence:

$$\text{Evidence} \to \text{RegimeIdentification} \to \text{RegimeComparison} \to \text{Admission} \to \text{ConflictAssessment}$$

is the correct pipeline. It parallels the pipeline established in R574 for general admission. R575 specializes it to logical regimes.

## 2.2 The Belnap-inspired finite model

R575 uses the four-valued representation `{T, F, B, N}`:

- `T` = true only
- `F` = false only
- `B` = both
- `N` = neither

This is Belnap's **FOUR** logic. R575 correctly notes that `B` does not entail explosion — arbitrary propositions are not derivable from a both-valued state. This is the essential difference between paraconsistent and classical logic.

## 2.3 The bridge as a candidate-then-established artifact

R575 §6 correctly treats ML-proposed bridges as `CandidateBridge`, not `EstablishedBridge`. The pipeline:

$$\text{ML} \to \text{Candidate} \to \text{L4 Validation} \to \text{Assessment} \to \text{Certificate}$$

preserves the firewall. R575 explicitly states:

$$MLCandidate \neq EstablishedTranslation$$

This is the correct extension of the R574 admission firewall to regime bridges.

## 2.4 The Unknown ≠ False invariant

R575 §7 restates the invariant:

$$Unknown \neq False$$

and connects it to:

$$NoEvidence(P) \neq Evidence(\neg P)$$

This is essential at the implementation level, because the absence of an established bridge must yield `UNKNOWN`, not `REJECTED`. A missing bridge and a contradicted bridge are different situations with different remedies:

- `UNKNOWN`: acquire more evidence, or construct the bridge.
- `REJECTED`: no bridge exists in the declared world; stop.

---

# Part III — Six Defects to Fix in R575.1 / R576

## Defect 1 — The four-valued Belnap lattice is used but not formalized

R575 §2.3 uses `{T, F, B, N}` but does not state the partial order or the operations.

**Corrected formalization.**

Belnap's FOUR lattice has the order (by information content, with `B` and `N` on opposite sides):

$$N \sqsubseteq T \sqsubseteq B, \quad N \sqsubseteq F \sqsubseteq B$$

with:

- `N` = least (no information)
- `B` = greatest (maximum information, including contradictions)
- `T` and `F` incomparable

**Meet (∧, greatest lower bound):**

| ∧ | T | F | B | N |
|---|---|---|---|---|
| T | T | N | F | N |
| F | N | F | T | N |
| B | F | T | B | N |
| N | N | N | N | N |

Wait — this is the *truth-functional* meet, not the *information* meet. There are two orderings used in Belnap's logic: the **truth order** ($\leq_t$: F ≤ N/B ≤ T) and the **information order** ($\leq_i$: N ≤ T, F ≤ B). R575 must declare **which** order is in use.

**Recommendation.** Use the information order for composition of evidence:

- Composing two evidence items is a *meet* in the information order.
- `B ∧ T = B` (both "true" and "both" is "both").
- `N ∧ x = N` (no evidence combined with anything is no evidence).
- `T ∧ F = N` (support for true combined with support for false is a contradictory-but-incomplete state).

This must be stated. Without it, `T ∧ F` could be `B` (if using truth order) or `N` (if using information order), and the two mean different things.

**Real-world.** Two independent reports — one says the bridge is open, the other says it is closed — combined in Belnap's information order give `N` (neither established), not `B` (both true). This is the correct epistemic reading.

## Defect 2 — `UNKNOWN` at the admission level is orthogonal to the Belnap values

R575 mixes two different four-valued systems:

- Admission status: `{Admitted, Rejected, Conditional, Unknown}` (from R574).
- Logical values: `{T, F, B, N}` (Belnap).

These are **different lattices** with different composition rules. Conflating them is a type error.

**Corrected distinction.**

- **Admission status** concerns whether an operation may participate in a computation.
- **Belnap value** concerns the truth-support status of a proposition under a paraconsistent regime.

They compose independently:

$$\text{Admitted} \land \text{BelnapValue} = \text{something with both dimensions}$$

**Counterexample.** A paraconsistent-regime evidence item (Belnap value `B`) under an admission status `Unknown` because the regime itself is not yet identified. The composition preserves both statuses; they do not collapse.

## Defect 3 — The promotion rule from `CandidateBridge` to `EstablishedBridge` is not stated

R575 §6 correctly distinguishes candidate from established but does not state what promotes a candidate.

**Corrected rule.**

$$CandidateBridge \to EstablishedBridge \iff \text{all of:}$$

1. Type check passed.
2. Regime compatibility verified (see R574).
3. Target preservation verified via $TPP(\pi_B, Z \mid W_{\text{adm}})$.
4. Assumptions checked.
5. Provenance chain complete.
6. Authority verified.
7. No counterexample found in the finite domain.

The promotion is **not** a score threshold. It is a contract satisfaction.

## Defect 4 — The comparison contract that precedes conflict is not typed

R575 §10 and §11 list conditions for conflict assessment (same patient, same time, same definition, same regime, same observation window) but do not type these as a first-class object.

**Recommended type.**

$$ComparisonContract = (\text{Subject}, \text{Time}, \text{Definition}, \text{Regime}, \text{Window}, \text{Scope}, \text{Granularity})$$

The comparison contract declares which coordinates must match for a conflict to be assessed. Without it, "conflict" is under-specified.

**Counterexample.** Two doctors say "stable" and "unstable." If the comparison contract requires the same observation window but the windows differ by 6 hours, the correct verdict is **not** `Conflict` — it is `Incomparable` (or `NOT_APPLICABLE` under the given contract).

## Defect 5 — Report discipline

The report contains:

```
R575 logical-regime boundary tests: 10/10 passed
```

but not:

- `ExecutionRunID` per test.
- Printed counterexamples.
- `certificate.json`.
- `Method` field distinguishing `finite_exhaustive` from `hand_checked`.

**Recommendation.** R576 must adopt the full report format:

```json
{
  "ExecutionRunID": "<hash>",
  "Spec": "R576.<k>",
  "Method": "finite_exhaustive | hand_checked | property_based",
  "Expected": "<status>",
  "Actual": "<status>",
  "Status": "PASS | FAIL | UNKNOWN | CONDITIONAL | UNDEFINED | NOT_APPLICABLE",
  "Counterexample": "<if applicable>",
  "Certificate": { ... }
}
```

This is the same gap flagged in R602.4, R604.3, R604.6, R604.7, R604.8, R604.9, and R574. It is now the project's clearest discipline debt.

## Defect 6 — The scope of the finite result is not stated

R575 §15 correctly limits the result to finite model checks. But it does not say:

- What the finite model actually covers.
- What it *does not* cover.
- What would be required to generalize.

**Corrected statement.** R575 verifies:

- 10 specific boundary cases.
- Two logical regimes (`classical`, four-valued paraconsistent).
- Zero composition of regime bridges.

R575 does **not** verify:

- Full classical logic (no quantifiers, no higher-order).
- Full paraconsistent logic (no axioms for the FOUR consequence relation).
- Regime bridge composition.
- Regime bridge associativity.
- Regime bridge preservation under composition.
- Adversarial ML bridge behavior.

Generalizations to these would require their own executable models. R575's claim is **exactly scoped**, not universal.

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

## R574 admission terms (recap)

### Admission
- **Type:** $Adm(x, \Gamma, C, S) \in \{Admitted, Rejected, Conditional, Unknown\}$.

### Admission Lattice
- **Type:** $\text{Rejected} \sqsubset \text{Unknown} \sqsubset \text{Conditional} \sqsubset \text{Admitted}$.

### Regime Compatibility
- **Type:** $Compatible(\Gamma_1, \Gamma_2, Z, C) \iff \exists B : \text{Bridge}(B, \Gamma_1, \Gamma_2) \land \text{Adm}(B) \land TPP(\pi_B, Z \mid W)$.

### Regime Bridge
- **Type:** Predicate on CompatibilityWitnesses.

## R575 new terms

### Logical Regime
- **Definition:** The rules by which propositions are combined and what follows from them.
- **Type:** A component of $\Gamma$.
- **Real-world:** Classical, intuitionistic, paraconsistent.
- **Invalid:** Treating "which solver was used" as the regime.

### Belnap Value (four-valued)
- **Definition:** Truth-support status under a paraconsistent regime.
- **Type:** $\{T, F, B, N\}$ where `T` = true-only support, `F` = false-only support, `B` = both, `N` = neither.
- **Real-world:** Two contradicting sources both present → `B`. No sources → `N`.
- **Invalid:** Treating `B` as `true` or as "everything follows."

### Information Order (on Belnap values)
- **Definition:** Partial order where `N` is least, `B` is greatest, `T` and `F` are incomparable.
- **Type:** $N \sqsubseteq T \sqsubseteq B$, $N \sqsubseteq F \sqsubseteq B$.
- **Real-world:** More information about a proposition is greater in this order.

### Truth Order (on Belnap values)
- **Definition:** Partial order where `F` is least, `T` is greatest, `N` and `B` are intermediate.
- **Type:** $F \sqsubseteq N \sqsubseteq T$, $F \sqsubseteq B \sqsubseteq T$.
- **Real-world:** Truth approximation toward `true`.

**Note.** The distinction between information order and truth order matters. R575.1 must declare which is in use.

### Conflict
- **Definition:** An epistemic situation where available evidence or assessments support incompatible conclusions according to a declared comparison contract.
- **Type:** $Conflict(E_1, E_2, CC)$ where $CC$ is the comparison contract.
- **Real-world:** Two doctors' statements about the same patient, same time, same definition, disagreeing.
- **Invalid:** Reducing to $P \land \neg P$ without a comparison contract.
- **Invariant:** $Conflict \neq Contradiction$.

### Contradiction
- **Definition:** A relation between propositions under a specified logical regime where the regime's rules forbid the joint assignment.
- **Type:** $Contradiction_\Gamma(P, \neg P)$.
- **Real-world:** Classical logic: `P = true` and `P = false` cannot coexist.
- **Invalid:** Treating as universal across regimes.
- **Invariant:** $Contradiction \neq Conflict$.

### Comparison Contract (new)
- **Definition:** Declared conditions under which two assessments or evidence items can be meaningfully compared.
- **Type:** $CC = (\text{Subject}, \text{Time}, \text{Definition}, \text{Regime}, \text{Window}, \text{Scope}, \text{Granularity})$.
- **Real-world:** "Same patient, same day, same clinical definition of 'stable'."
- **Invalid:** Comparing two assessments without declaring the comparison contract.

### CandidateBridge
- **Definition:** An ML or heuristic proposal for a regime bridge, not yet validated.
- **Type:** A typed candidate.
- **Real-world:** ML says "classical→paraconsistent is a plausible translation."
- **Invalid:** Treating as established.

### EstablishedBridge
- **Definition:** A bridge that has satisfied its validation contract.
- **Type:** A verified bridge in L4.
- **Real-world:** A bridge with an accompanying certificate.
- **Invalid:** Candidate bridge treated as established.

### Regime Boundary
- **Definition:** A transition between regimes that is not same-regime, and where the bridge status is either absent or unverified.
- **Type:** A boundary condition in the pipeline.
- **Real-world:** Classical source offering evidence to a paraconsistent target without a bridge.
- **Invalid:** Treating boundary as conflict.

## Report-discipline terms (unchanged)

### ExecutionRunID
- **Type:** Hash of (SpecID, CodeVersion, InputHash, Timestamp).

### Certificate
- **Type:** $(Property, Scope, Method, Result, Provenance, Signature, Time)$.

### Status
- **Values:** `PASS`, `FAIL`, `UNKNOWN`, `CONDITIONAL`, `UNDEFINED`, `NOT_APPLICABLE`.

---

# Part V — Worked Examples

## Example 1 — Same regime, no conflict

**Setup.** Two sources under classical logic both assert `P = true`.

**Composition.** $\text{true} \land \text{true} = \text{true}$ (in both truth and information orders).

**Result.** `ADMITTED`. No conflict.

## Example 2 — Different regime, no conflict

**Setup.** Source A (classical) asserts `P = true`. Source B (paraconsistent) asserts `P = B` (both).

**Regime difference.** Yes.

**Conflict?** No — because the two sources have different regimes, their assertions are not directly comparable without a bridge. The correct status is `UNKNOWN` (or `INCOMPARABLE`) until a bridge is established.

**Rejected pipeline.**
$$\text{Evidence} \to \text{Conflict}$$
This incorrectly reports conflict.

**Correct pipeline.**
$$\text{Evidence} \to \text{RegimeIdent} \to \text{RegimeComparison} \to \text{Admission} \to \text{ConflictAssessment}$$

## Example 3 — Belnap information order

**Setup.** Two sources under paraconsistent regime.

- Source A: `P = T`.
- Source B: `P = F`.

**Composition (information order).**
$$T \land F = N$$

**Composition (truth order).**
$$T \land F = F$$

**Which is right?** For evidence aggregation under lack of a corroborating source, information order is correct: two conflicting supports leave the proposition *unestablished*, not *false*. The `N` result is the correct epistemic value.

**Conclusion.** The lattice order matters. R575.1 must declare it.

## Example 4 — Two doctors, with comparison contract

**Setup.**

- Doctor A: "Patient stable" at 10:00.
- Doctor B: "Patient unstable" at 16:00.

**Comparison contract.**
$$CC = (\text{Patient: P}, \text{Time: within 2h}, \text{Definition: clinical}, \text{Regime: classical}, \text{Window: 1h}, \text{Scope: hospital}, \text{Granularity: hour})$$

**Evaluation.** The 6-hour gap violates `Time: within 2h`.

**Result.** `INCOMPARABLE`, not `CONFLICT`.

**Conclusion.** The comparison contract is what allows `Conflict` to be assessed meaningfully.

## Example 5 — Database snapshots

**Setup.**

- System A: `status = ACTIVE` at 10:00.
- System B: `status = INACTIVE` at 12:00.

**Regime.** Both classical.

**Comparison contract.** Requires same timestamp.

**Result.** `INCOMPARABLE`.

**Real-world.** Replication lag explains the difference. Not a conflict.

## Example 6 — ML candidate bridge

**Setup.** ML proposes classical→paraconsistent translation with score 0.94.

**Correct handling.** Produce `CandidateBridge`. Pass through L4 validation. If validation requires target preservation and the target is "boolean truth value", the translation fails (Belnap `B` is not a boolean). The candidate is rejected.

**Result.** `CandidateBridge` never becomes `EstablishedBridge`.

**Real-world.** An embedding model might consider "classical" and "paraconsistent" similar because they are both logics; but their semantic difference is decisive.

## Example 7 — Bridge composition (to be tested in R576)

**Setup.**

- $B_1 : \Gamma_1 \to \Gamma_2$ valid.
- $B_2 : \Gamma_2 \to \Gamma_3$ valid.

**Question.** Is $B_2 \circ B_1$ valid?

**Naive answer.** Yes.

**Correct answer.** Only if additional conditions hold:

- Preconditions compose: $pre_{comp}(B_1) \land pre_{comp}(B_2 \circ B_1)$.
- Target preservation composes: $TPP(\pi_{B_2 \circ B_1}, Z \mid W)$.
- Assumptions do not conflict.
- Loss composition satisfies target.

R576 must construct counterexamples where $B_1$ and $B_2$ are individually valid but $B_2 \circ B_1$ is not.

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
             ComparisonContract (new)
                              │
                     L2 Formal Fabric
        Types / Operations / Transformations
        CompatibilityWitness / RegimeBridge
        TPP / Composition / Preservation / Loss
                              │
                     L3 Epistemic Assessment
     Admission | BelnapValue | Conflict | Contradiction
     Dependency | Materiality | Minimality
     Zero (Unknown | Unobservable | Unidentifiable |
           Unacquired | Unavailable | Unvalidated)
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
- Propose `CandidateRegime` for an unlabeled source.
- Propose `CandidateComparisonContract` based on observed co-reference.
- Propose adversarial near-miss bridges (e.g., Celsius→Fahrenheit under Z = statistical shape).

**ML may not do:**

- Assert bridge validity.
- Assert regime identity.
- Assert conflict.
- Write to X.
- Bypass L4.

**Firewall:**

```
ML candidate
  → TypeCheck
  → RegimeCheck
  → CompatibilityCheck
  → TPPCheck
  → AssumptionCheck
  → ProvenanceCheck
  → CandidateQuarantine
  → L4 Validation
  → Assessment
```

**Concrete technique for R576's adversarial benchmark:**

Features per candidate bridge: source regime signature, target regime signature, declared target $Z$, textual similarity, embedding similarity, dimensional-analysis features (for physical units), known-bridge corpus.

Model: gradient-boosted trees on tabular features + embeddings. Justify: mixed types, small positive class.

Labels: synthetic positives (admissible bridges) and negatives (invalid bridges) with high similarity.

Metrics: Precision, Recall, FDR, FIR, calibration, abstention quality.

Cardinal rule:

$$\boxed{High\ Similarity \not\Rightarrow Admission}$$

## VI.3 — Short bullet status

### Achieved

- **Kernel:** stable; no new primitive across 600+ rounds.
- **L0–L6 architecture:** stable; no new BC, no new layer.
- **Operation algebra, composition, associativity, non-commutativity:** executable.
- **Preservation, loss, TPP, recovery, provenance, history:** executable.
- **Dependency (multi-factor, target/scope/regime-relative):** executable.
- **No-information impossibility theorem:** established.
- **Information ≠ Identifiability:** established.
- **Greedy ≠ Optimal acquisition (XOR):** established.
- **Admission ≠ Assessment ≠ Determination:** established conceptually, partially executed (R574).
- **RegimeDifference ≠ Conflict:** established conceptually, executed in R575 (10/10).
- **Unknown ≠ False:** preserved across admissions and Belnap values.
- **Rejected ≠ False:** preserved.
- **MLCandidate ≠ EstablishedTranslation:** preserved.
- **Belnap four-valued model:** used in R575.
- **ML firewall:** preserved and extended to regime bridges.

### Not yet done (executable)

- **R575.1** — Belnap lattice formalization with meet/join tables; declare information vs truth order.
- **R575.2** — Comparison Contract as a first-class object; test conflict assessment under varying contracts.
- **R575.3** — Report discipline: `ExecutionRunID` + `certificate.json` per test.
- **R576** — Cross-regime translation composition:
  - R576.1 Valid bridge × valid bridge → valid composite.
  - R576.2 Valid × valid but composite loses target.
  - R576.3 Valid × valid but composite undefined.
  - R576.4 Non-associative witness composition.
  - R576.5 Preservation counterexample.
  - R576.6 Adversarial ML-generated bridge chain.
- **R577** — ML adversarial regime-bridge benchmark executed.
- **R578** — Full invariant catalogue (I-S, I-T, I-E, I-X, I-A, I-G, I-R).
- **R579** — Terminology freeze.
- **R580** — Theory Specification v1.0.

### Open (not "not proven")

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general; submodular case tractable.
- **Belnap order choice:** R575.1 must decide information vs truth order for composition.
- **Category-theoretic structure:** pending.
- **Matroid / lattice universality:** partially falsified; specific orderings may still apply.

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
- `AdmissionStatus` is a lattice, not a Boolean.
- `BelnapValue` is a lattice, not a Boolean.
- `Conflict ≠ Contradiction`.
- `RegimeDifference ≠ Conflict`.

### The single most important next thing

$$\boxed{\text{Build R575.1 (lattice + comparison contract). Then build R576 (bridge composition). Then fix report discipline.}}$$

Because R575's conceptual contribution is correct, and the immediate next questions — Belnap order, comparison contract, bridge composition — are all within reach of a finite executable model. The report discipline fix is overdue and should be rolled into R575.1.

### Two open questions I flag

1. **Which order on Belnap's values is canonical for composition of evidence?** My recommendation: information order for evidence aggregation, truth order for proposition-level inference. R575.1 must state this and test it.

2. **Does bridge composition have a closure?** If $B_1$ and $B_2$ are bridges, is their composite always a bridge? Probably not without extra conditions. R576 must characterize the exact conditions.