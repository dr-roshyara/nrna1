# Independent Review — R581 (Assessment × Certificate Lifecycle State Machine)

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

No new attachment beyond R581. No external book needed; if a theorem requires one I will name it.

I will do six things:

1. **Verdict** on R581.
2. **What R581 actually establishes** — honest scope.
3. **Seven precise defects** to fix in R581.1 / R582.
4. **Full term definitions** (extended).
5. **Worked examples** proving or refuting the central claims.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

R581 is a **clean, disciplined, and correct step**, and it makes five contributions:

$$\boxed{UNKNOWN \to FAIL \Rightarrow ExplicitFailAssessment \text{ (the refined invariant)}}$$
$$\boxed{State_C = (AssessmentStatus, CertificateLifecycle) \text{ — independent dimensions, not a merged enum}}$$
$$\boxed{Partial transition function \delta : S \times E \rightharpoonup S \text{ (not total)}}$$
$$\boxed{\text{Terminal lifecycle states (SUPERSEDED, EXPIRED) cannot resurrect}}$$
$$\boxed{CertificateIdentity \text{ is historical, not mutable}}$$

Each is correct. The first is the strongest methodological result: R581's computation **refuted an over-strong invariant** (the naive `UNKNOWN ⇏ FAIL`), and the corrected invariant was sharper than the original. That is the third distinct self-falsification pattern in the series (R604.4 → R604.3; R604.6 → R604.5; R581 → its own first draft). The methodology is stable.

The second is the deepest architectural contribution: **do not create a merged enum of `PASS_CURRENT`, `FAIL_SUPERSEDED`, etc.** Two independent dimensions is correct, and it prevents the combinatorial state explosion that would otherwise occur.

The third is a computer-logic result: partiality is essential because some transitions must be undefined, and `Undefined ≠ Fail` (an invariant carried from R577).

The fifth is a subtle but critical point: **certificates are historical artifacts, not mutable records**. Once `SUPERSEDED`, they cannot become `CURRENT` again via the same identity. Revalidation of a superseded certificate creates a *new* certificate identity. This preserves the audit trail.

**Seven residual issues**, each requiring correction before R582:

1. The 16-state enumeration is claimed but not fully shown. The report says "16 state pairs checked, 80 admissible transition witnesses" but does not list them.
2. The transition function's **domain** is partially specified. Some (state, event) pairs are undefined by design; some may be undefined by accident. The exact undefined set must be enumerated.
3. §12's CONDITIONAL case is correctly labeled contract-dependent, but the **contract interface** is not typed.
4. §22 correctly rejects persisting `UseEligibility` but does not define the **derivation function** $f$ precisely.
5. §27's invariants again mix axioms, theorems, rules, and derived observations.
6. §28's "what R581 proves" list is scoped to a *finite* model, but the document does not explicitly say the model is finite over which domain.
7. Report discipline: no `ExecutionRunID`, no `certificate.json`, no per-test `Method`. This is 22+ rounds of accumulating debt.

---

# Part II — What R581 Actually Establishes

## 2.1 The refined `UNKNOWN → FAIL` invariant

R581 §20 reports a genuine computational discovery:

**First attempt (wrong):** `UNKNOWN ⇏ FAIL` always.

**Counterexample found by the model:** $UNKNOWN + REVALIDATION\_REQUIRED$ with an explicit `REVALIDATE_FAIL` event transitions to $FAIL + SUPERSEDED$.

**Corrected invariant:**

$$UNKNOWN \to FAIL \Rightarrow ExplicitFailAssessment$$

This is sharper and correct. It states a *sufficient condition* for the transition, not a blanket prohibition.

**Significance.** This is the third time in the series that a finite executable model has caught an over-strong invariant. It is the methodology working as intended.

## 2.2 The two-dimension state model

R581 §5:

$$State_C = AssessmentStatus \times CertificateLifecycle$$

with:

$$|A| = 4, \quad |L| = 4, \quad |State_C| = 16$$

**Correct.** This avoids the combinatorial explosion of a merged enum like:

$$\{PASS\_CURRENT, PASS\_REVALIDATION\_REQUIRED, PASS\_SUPERSEDED, \ldots\}$$

which would have $|A| \times |L| = 16$ atomic states and require an event-driven table of $16 \times |E|$ entries. Keeping the two dimensions separate is more maintainable and more *typed*.

**Real-world analogy.** A bank account has `{balance}` and `{status: active/frozen}` as independent dimensions. A merged `{active-with-balance, active-without-balance, frozen-with-balance, ...}` enum would be absurd.

## 2.3 Partial transition function

R581 §6 and §18:

$$\delta : (A, L, E, C) \rightharpoonup (A', L')$$

The arrow is partial because some transitions are undefined by design.

**Correct.** This preserves $Undefined \neq Fail$ from R577 and prevents the system from silently inventing behavior.

**Critical example.**

$$\delta(FAIL, SUPERSEDED, REVALIDATE\_PASS) = \text{undefined}$$

If this transition were allowed, the certificate would resurrect — violating §14's safety property.

## 2.4 Terminal state irreversibility

R581 §14:

$$SUPERSEDED \Rightarrow \text{no future transition on the same certificate}$$

**Correct.** The old certificate is a historical artifact. Revalidation of the *revalidation result* creates a new certificate.

**Real-world analogy.** A revoked passport cannot become un-revoked; a new passport must be issued.

## 2.5 The certificate identity distinction

R581 §15:

$$CertificateVersion_1 \neq CertificateVersion_2$$

when a new certification event creates a new artifact.

**Correct.** This preserves the append-only history discipline.

## 2.6 The 80-witness enumeration

R581 §19 claims:

- 16 assessment/lifecycle state pairs.
- 80 admissible transition witnesses.

If 16 states × 5 events = 80 possible (state, event) pairs, and the model checked all 80, then the model has fully enumerated the admissible transition space. But the report does not list them.

**Interpretation.** The claim is that the model is *complete* over the finite transition space. This must be verified by listing the 80 (state, event) pairs and their results in R581.1.

---

# Part III — Seven Defects to Fix in R581.1 / R582

## Defect 1 — The 16 state pairs and 80 witnesses are not shown

R581 §19 claims verification but does not list the state pairs or witness results.

**Required in R581.1.** Full enumeration table:

| # | (A, L) | Event | Result (A', L') | Undefined? |
|---|---|---|---|---|
| 1 | (PASS, CURRENT) | MATERIAL_REVISION | (PASS, REVALIDATION_REQUIRED) | — |
| 2 | (PASS, CURRENT) | REVALIDATE_PASS | (PASS, CURRENT) | — |
| 3 | (PASS, CURRENT) | REVALIDATE_FAIL | undefined | ✓ |
| ... | ... | ... | ... | ... |
| 80 | (CONDITIONAL, EXPIRED) | ... | ... | ... |

Without this enumeration, the claim "80 witnesses checked" is not auditable.

## Defect 2 — The domain of `δ` is not fully stated

Some `(state, event)` pairs are undefined by design (e.g., `SUPERSEDED + REVALIDATE_PASS`). Others might be undefined by accident (e.g., a `(CONDITIONAL, CURRENT)` state that should have received a `CONDITION_EXPIRED` event).

**Corrected statement.** The domain of δ must be declared:

$$Dom(\delta) \subseteq State_C \times Event$$

and the **complement** (undefined pairs) must be explicitly listed as forbidden.

**Counterexample.** If `(PASS, CURRENT) + TIME_ELAPSED` is undefined, the certificate cannot expire. But expiration is a legitimate transition. So either `TIME_ELAPSED` is not in the event set, or the transition is missed.

## Defect 3 — The contract interface for CONDITIONAL is not typed

R581 §12 says `CONDITIONAL → CURRENT` requires a contract that "explicitly permits conditional validity." But the contract interface is not stated.

**Corrected type.**

$$\text{Contract}(C) \vdash \text{ConditionalValidityPermitted} : \{\text{Yes}, \text{No}, \text{Unknown}\}$$

The transition is permitted iff the contract yields `Yes`.

**Real-world.** A regulatory certificate may be "conditional on X being maintained" — the certificate's contract must state whether a conditional certificate can remain `CURRENT`.

## Defect 4 — `UseEligibility` derivation is not typed

R581 §22 says:

$$UseEligibility = f(AssessmentStatus, CertificateLifecycle, Scope, Contract, Authority)$$

but does not state $f$.

**Corrected definition.**

$$UseEligibility = \begin{cases} \text{USABLE} & \text{if } A = PASS \land L = CURRENT \land \text{Authority}(a, C, \Gamma) \\ \text{CONDITIONALLY\_USABLE} & \text{if } A = CONDITIONAL \land L = CURRENT \\ \text{NOT\_USABLE} & \text{otherwise} \end{cases}$$

The function is derived, not persisted. Any change to $A$, $L$, scope, contract, or authority recomputes it.

## Defect 5 — Invariants mix axioms, theorems, rules, and observations

R581 §27 lists I-C07..I-C14. Split into:

- **Axioms:** I-C11 (history append).
- **Definitions:** I-C13 (assessment ≠ lifecycle).
- **Rules:** I-C08 (material revision preserves assessment), I-C14 (conditional validity is contract-indexed).
- **Theorems:** I-C07 (`UNKNOWN → FAIL ⇒ ExplicitFailAssessment`), I-C09 (revalidation determines assessment), I-C10 (terminal irreversibility).
- **Observations:** I-C12 (expiration ⇏ refutation).

## Defect 6 — The finite model's scope is not stated

R581 §28 says "finite formal model" but does not state:

- The finite domain (16 states × which events?).
- What is *outside* the model (contracts, authority, real dependency graphs).
- What generalizations remain open.

**Corrected statement.** The model verifies:

- 16 states.
- 5 events (assumed: MATERIAL_REVISION, REVALIDATE_PASS, REVALIDATE_FAIL, REVALIDATE_UNKNOWN, TIME_ELAPSED).
- 80 (state, event) pairs.
- Safety properties: no silent `UNKNOWN → FAIL`, no resurrection, no assessment mutation on lifecycle-only changes.

It does **not** verify:

- That real certificates can be represented by this finite model.
- That dependency completeness holds.
- That ML impact detection is sound.
- That acquisition always produces the needed evidence.

## Defect 7 — Report discipline

Same pattern. 22+ rounds of debt. Must be closed.

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

## R574–R580 (recap)

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

### EpistemicState
- **Type:** $\{SUPPORTED, UNRESOLVED, REFUTED, CONDITIONAL\}$.

### AssuranceLifecycleState
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

### Dependency Completeness
- **Type:** $Complete(G_E, C, S)$.

### Impact Propagation
- **Type:** $\text{Propagate}(E', G_E) = \{C : MateriallyDepends(E', C)\}$.

### CurrentValidity vs HistoricalValidity
- **Invariant:** A certificate can be historically valid but currently superseded.

## R581 new terms

### AssessmentStatus (refined for R581)
- **Definition:** The finite status of an assessment under a declared contract.
- **Type:** $\{PASS, FAIL, UNKNOWN, CONDITIONAL\}$.
- **Real-world:** The result of evaluating a hypothesis.

### CertificateLifecycle (refined)
- **Type:** $\{CURRENT, REVALIDATION\_REQUIRED, SUPERSEDED, EXPIRED\}$.

### CertificateState
- **Definition:** The pair $(A, L)$.
- **Type:** $A \times L$, $|A \times L| = 16$.

### Transition Function
- **Definition:** Maps (assessment status, lifecycle, event, contract) to a new (assessment status, lifecycle) or is undefined.
- **Type:** $\delta : (A, L, E, C) \rightharpoonup (A', L')$.
- **Properties:**
  - Partiality: `Dom(δ) ⊆ A × L × E × C`, but not all tuples are in the domain.
  - Determinism: for a given input, at most one output.
  - Terminal irreversibility: `(A, SUPERSEDED)` and `(A, EXPIRED)` are terminal.

### Material Revision Event
- **Definition:** An event indicating that a dependency of a certificate has been materially revised.
- **Type:** `MATERIAL_REVISION`.
- **Effect:** $L \to REVALIDATION\_REQUIRED$; $A$ unchanged.

### Revalidation Pass Event
- **Definition:** An event indicating that a revalidation produced a PASS.
- **Effect:** $(A, REVALIDATION\_REQUIRED) \to (PASS, CURRENT)$.

### Revalidation Fail Event
- **Definition:** An event indicating that a revalidation produced a FAIL.
- **Effect:** $(A, REVALIDATION\_REQUIRED) \to (FAIL, SUPERSEDED)$.

### Revalidation Unknown Event
- **Definition:** An event indicating that revalidation produced UNKNOWN.
- **Effect:** $(A, REVALIDATION\_REQUIRED) \to (UNKNOWN, REVALIDATION\_REQUIRED)$.

### Expiration Event
- **Definition:** An event indicating the temporal validity of the certificate has passed.
- **Effect:** $(A, CURRENT) \to (A, EXPIRED)$; $A$ unchanged.

### Explicit Fail Assessment (new)
- **Definition:** A specific assessment event that produces `FAIL` with supporting evidence.
- **Type:** An event in the transition function's domain.
- **Constraint:** The only event that can produce `UNKNOWN → FAIL`.

### ConditionalValidityPermission
- **Definition:** A contract attribute declaring whether conditional validity is permitted.
- **Type:** $\{\text{Yes}, \text{No}, \text{Unknown}\}$.

### UseEligibility (derived, not persisted)
- **Definition:** Whether a certificate is permitted for a specific use.
- **Type:** $f(A, L, \text{Scope}, \text{Contract}, \text{Authority}) \in \{\text{USABLE}, \text{CONDITIONALLY\_USABLE}, \text{NOT\_USABLE}\}$.

### Certificate Identity
- **Definition:** The identity of a specific historical certification event.
- **Invariant:** A certificate that has reached a terminal state is not resurrected; a new certificate identity is created.

### GovernanceAuthority (three-axis, from R580)
- **Type:** $\{AUTHORIZED, NOT\_AUTHORIZED, CONDITIONAL, UNKNOWN\}$.

---

# Part V — Worked Examples

## Example 1 — The 16 state pairs

| (A, L) | Meaning |
|---|---|
| (PASS, CURRENT) | Normal, verified |
| (PASS, REVALIDATION_REQUIRED) | Prior PASS, revision pending |
| (PASS, SUPERSEDED) | Prior PASS, no longer operative |
| (PASS, EXPIRED) | Prior PASS, temporal validity ended |
| (FAIL, CURRENT) | Failed and operative |
| (FAIL, REVALIDATION_REQUIRED) | Failed, revision pending |
| (FAIL, SUPERSEDED) | Prior FAIL, retired |
| (FAIL, EXPIRED) | Prior FAIL, expired |
| (UNKNOWN, CURRENT) | Unresolved and operative |
| (UNKNOWN, REVALIDATION_REQUIRED) | Unresolved, revision pending |
| (UNKNOWN, SUPERSEDED) | Prior UNKNOWN, retired |
| (UNKNOWN, EXPIRED) | Prior UNKNOWN, expired |
| (CONDITIONAL, CURRENT) | Conditionally valid |
| (CONDITIONAL, REVALIDATION_REQUIRED) | Conditional, revision pending |
| (CONDITIONAL, SUPERSEDED) | Conditional, retired |
| (CONDITIONAL, EXPIRED) | Conditional, expired |

All 16 combinations are logically possible. Each has different implications for use.

## Example 2 — Material revision preserves assessment

**Setup.** `(PASS, CURRENT)`, then `MATERIAL_REVISION`.

**Result.** `(PASS, REVALIDATION_REQUIRED)`.

**Correct inference.** Assessment unchanged.

**Incorrect inference.** "Assessment is now FAIL."

## Example 3 — Successful revalidation

**Setup.** `(PASS, REVALIDATION_REQUIRED)`, then `REVALIDATE_PASS`.

**Result.** `(PASS, CURRENT)`.

## Example 4 — Failed revalidation

**Setup.** `(PASS, REVALIDATION_REQUIRED)`, then `REVALIDATE_FAIL`.

**Result.** `(FAIL, SUPERSEDED)`.

**Note.** This is a genuine epistemic change: a new assessment occurred and produced FAIL.

## Example 5 — UNKNOWN transitions

**Setup A.** `(UNKNOWN, CURRENT)`, then `MATERIAL_REVISION`.

**Result.** `(UNKNOWN, REVALIDATION_REQUIRED)`. **Not** `FAIL`.

**Setup B.** `(UNKNOWN, REVALIDATION_REQUIRED)`, then `REVALIDATE_UNKNOWN`.

**Result.** `(UNKNOWN, REVALIDATION_REQUIRED)`. Still queued.

**Setup C.** `(UNKNOWN, REVALIDATION_REQUIRED)`, then `REVALIDATE_FAIL`.

**Result.** `(FAIL, SUPERSEDED)`. Legitimate — the explicit fail event justifies the transition.

**Corrected invariant:** The transition `UNKNOWN → FAIL` requires the explicit `REVALIDATE_FAIL` event, not merely a lifecycle change.

## Example 6 — Terminal irreversibility

**Setup.** `(FAIL, SUPERSEDED)`, then `REVALIDATE_PASS`.

**Result.** `undefined`. The operation is not permitted.

**Consequence.** If revalidation produces a new PASS, it creates a *new* certificate identity.

## Example 7 — Expiration

**Setup.** `(PASS, CURRENT)`, then `TIME_ELAPSED` (with temporal validity passed).

**Result.** `(PASS, EXPIRED)`.

**Correct inference.** The certificate is no longer current, but the underlying claim was not refuted.

## Example 8 — CONDITIONAL contract-dependent

**Setup A.** `(CONDITIONAL, REVALIDATION_REQUIRED)`, contract permits conditional validity, then `REVALIDATE_CONDITIONAL`.

**Result.** `(CONDITIONAL, CURRENT)`.

**Setup B.** `(CONDITIONAL, REVALIDATION_REQUIRED)`, contract does **not** permit conditional validity, then `REVALIDATE_CONDITIONAL`.

**Result.** `(CONDITIONAL, REVALIDATION_REQUIRED)` (stuck) or `undefined` depending on contract semantics.

## Example 9 — UseEligibility derivation

**Setup A.** `A = PASS, L = CURRENT, Authority = AUTHORIZED`.

**Result.** `USABLE`.

**Setup B.** `A = PASS, L = REVALIDATION_REQUIRED, Authority = AUTHORIZED`.

**Result.** `NOT_USABLE` (revalidation required).

**Setup C.** `A = PASS, L = CURRENT, Authority = NOT_AUTHORIZED`.

**Result.** `NOT_USABLE` (governance denies).

**Setup D.** `A = CONDITIONAL, L = CURRENT, Authority = AUTHORIZED`.

**Result.** `CONDITIONALLY_USABLE`.

## Example 10 — Certificate identity over a lifecycle

**Timeline.**

- $t_1$: Certificate C1 issued: `(PASS, CURRENT)`.
- $t_2$: MATERIAL_REVISION: C1 becomes `(PASS, REVALIDATION_REQUIRED)`.
- $t_3$: REVALIDATE_FAIL: C1 becomes `(FAIL, SUPERSEDED)`.
- $t_4$: New evidence supports the claim again.
- $t_5$: Certificate C2 issued: `(PASS, CURRENT)`.

**C1's identity is preserved as a historical artifact.** C2 is a new certificate. History shows both.

**Invalid handling.** Mutating C1 back to `(PASS, CURRENT)`.

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
        Types / Operations / Transformations (partial)
        CompatibilityWitness / RegimeBridge / PreservationBridge
        TPP / Composition / Preservation / Loss
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
     ImpactAnalysis / Revalidation
     AssessmentStatus × CertificateLifecycle State Machine
     Transition Function δ : (A, L, E, C) ⇀ (A', L')
     GovernanceAuthority
                              │
                     L5 Intelligence
       CandidateImpact / CandidateDependency / CandidateAcquisition
       CandidateBridgeGenerator / OOD
                              │
                     L6 Governance
       Authority / Permission / Decision
       BridgeAuthorization / RevalidationAuthorization
       UseEligibility (derived, not persisted)
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
- Trigger lifecycle transitions.
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
  → Assessment / Lifecycle transition via δ
```

**Cardinal rules:**

$$\boxed{MLSimilarity \not\Rightarrow Impact}$$
$$\boxed{MLCandidateImpact \not\Rightarrow LifecycleChange}$$
$$\boxed{LifecycleChange \text{ requires an explicit event in } Dom(\delta)}$$

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
- **Assessment × Lifecycle state machine:** R581 passed finite tests (16 states, 80 witnesses).
- **`UNKNOWN → FAIL` requires explicit fail assessment:** established.
- **Terminal irreversibility:** established.
- **Certificate identity as historical:** established.
- **UseEligibility as derived, not persisted:** established.
- **ML firewall:** preserved.
- **Report discipline:** still partial.

### Not yet done

- **R581.1** — report discipline: `ExecutionRunID` + `certificate.json` per test; full 16×5 transition table.
- **R581.2** — `Dom(δ)` complement explicitly listed.
- **R581.3** — CONDITIONAL contract interface typed.
- **R581.4** — `UseEligibility` derivation function typed.
- **R581.5** — invariants split by category.
- **R582** — Selective Revalidation Completeness and Impact Closure.
- **R583** — cascading and simultaneous revisions.
- **R584** — full invariant engine.
- **R585** — terminology freeze.
- **R586** — Theory Specification v1.0.

### Open

- **Universal regime inference:** pending.
- **Automatic bridge discovery:** pending.
- **Optimal acquisition planning:** NP-hard in general.
- **Category-theoretic structure of composition:** pending.
- **Matroid / lattice universality:** partially falsified.
- **n-stage loss interaction characterization:** open.
- **Cascade termination:** open.
- **Dependency completeness for arbitrary certificates:** open.

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
- `UNKNOWN ≠ FAIL` (except via explicit revalidation event).
- `UNKNOWN → FAIL ⇒ ExplicitFailAssessment`.
- `Terminal lifecycle states cannot resurrect`.
- `CertificateIdentity is historical, not mutable`.
- `UseEligibility is derived, not persisted`.

### The single most important next thing

$$\boxed{\text{Build R581.1 (report discipline + full transition table) and R582 (selective revalidation completeness).}}$$

Because R581's state machine is correct and small, and its practical value depends on producing an auditable transition table and a certificate per test. R582 must then characterize when selective revalidation is *complete* — i.e., when no affected certificate is missed — which is the dependency-completeness assumption R580 left implicit.

### Two open questions I flag

1. **Is `TIME_ELAPSED` in the event set?** If not, expiration cannot be triggered. If yes, it needs to be a first-class event with a declared temporal-validity check. R581.1 must resolve this.

2. **What happens if the contract changes during a certificate's lifetime?** A certificate issued under contract C1 may find itself under contract C2 after a contract revision. Does it become `REVALIDATION_REQUIRED` automatically? The correct answer likely depends on whether the contract revision is material to the certificate's target. R582 must address this — it is a contract-level analogue of evidence revision.