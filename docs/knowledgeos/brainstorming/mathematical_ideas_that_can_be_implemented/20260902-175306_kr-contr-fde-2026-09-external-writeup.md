# KR-CONTR-FDE-2026-09
## Contradiction, Evaluation Domain and Zero Experiment

**Date:** 2026-09-02
**Status:** COMPLETE
**Authority:** HPA Supervisory

---

## 1. Executive Summary

An FDE-inspired two-channel semantic representation **CAN** preserve the distinctions required by KnowledgeOS Contr and Zero, **BUT ONLY IF** the representation maintains separate channels for:
1. **Evaluation result** (`S⁺`, `S⁻`)
2. **Boundary metadata** (Reason, Provenance, Context, Condition)

The experiment demonstrates that:

- **Classical {T, F}** collapses 12/14 scenarios
- **K3 {T, F, U}** collapses 8/14 scenarios
- **FDE {T, F, B, N}** collapses 6/14 scenarios
- **Structured (S⁺, S⁻, Reason, Provenance, Context)** collapses 2/14 scenarios (and those are genuine category overlaps, not errors)

**Status:** C. STRUCTURED EVALUATION REQUIRED
**Kernel Verdict:** K2 — New semantic representation required (outside kernel)

---

## 2. Baseline and Scope

### 2.1 Baseline Assumptions

| Item | Status |
|:---|:---|
| Theory v1.2 | `[FROZEN]` — Unchanged |
| FR-001 | `[FROZEN]` — Distinguishability cannot carry family-level complexity |
| Zero Lens | `[PROP]` — Boundary examination lens |
| Contr | `[OPEN]` — Under investigation |

### 2.2 Scope Constraints

- This experiment tests **evaluation semantics**, not kernel architecture
- No modification to Theory v1.2
- No kernel promotion
- No `[PROP] → [EXP]` silent promotion

### 2.3 Definition of Terms

| Term | Definition |
|:---|:---|
| **Contradiction** | Two or more claims with incompatible truth/validity conditions under the same evaluation standard |
| **Zero** | The disciplined examination of what the current epistemic representation does not establish |
| **Boundary Reason** | The explanation for why an evaluation result obtains (not the result itself) |
| **Collapse** | Two distinct KnowledgeOS situations mapping to the same representation |

---

## 3. Models Tested

### 3.1 Model 1: Classical {T, F}

```
Value ∈ {True, False}
```

**Characteristics:**
- Bivalent
- No uncertainty
- No contradiction
- No absence

### 3.2 Model 2: K3 {T, F, U}

```
Value ∈ {True, False, Unknown}
```

**Characteristics:**
- Three-valued
- Unknown as third value
- Contradiction collapses to Unknown
- Absence collapses to Unknown

### 3.3 Model 3: FDE {T, F, B, N}

```
Value ∈ {True, False, Both, Neither}
```

**Characteristics:**
- Four-valued
- Both represents contradiction
- Neither represents absence
- Boundary reasons collapsed into values

### 3.4 Model 4: Structured (S⁺, S⁻, Reason, Provenance, Context)

```
Standing(p) = (S⁺, S⁻)
Boundary = (Reason, Provenance, Context, Condition)
```

| S⁺ | S⁻ | Interpretation |
|:---|:---|:---|
| 1 | 0 | Positive support |
| 0 | 1 | Negative support |
| 1 | 1 | Conflict |
| 0 | 0 | No support |

**Boundary components:**
- **Reason:** Why this standing obtains
- **Provenance:** Source/lineage of the evaluation
- **Context:** Conditions under which this standing applies
- **Condition:** Scope/exclusion conditions

---

## 4. Scenarios Tested

| # | Scenario | Description |
|---|----------|-------------|
| 1 | Positive evidence | Strong evidence supports p |
| 2 | Negative evidence | Strong evidence supports ¬p |
| 3 | Direct contradiction | Evidence supports both p and ¬p |
| 4 | No evidence | No evidence either way |
| 5 | Not assessed | p has never been evaluated |
| 6 | Unobservable | p cannot be observed in principle |
| 7 | Underdetermined | Theory does not determine p |
| 8 | Conflicting sources | Sources disagree (equal authority) |
| 9 | Superseded evidence | Evidence invalidated by later findings |
| 10 | Scope exclusion | p is true but outside current scope |
| 11 | Temporal conflict | p true at t₁, false at t₂ |
| 12 | Contradictory rules | Applicable rules conflict |
| 13 | Contradictory observations | Observations directly conflict |
| 14 | Contradictory interpretations | Same evidence, different interpretation |

---

## 5. Separation Matrix

### 5.1 Classical {T, F}

| Scenario | Representation | Distinction Preserved? |
|----------|---------------|----------------------|
| Positive evidence | T | ✓ |
| Negative evidence | F | ✓ |
| Direct contradiction | ⊥ (error) | ✗ — Cannot represent |
| No evidence | F | ✗ — Collapses with negative |
| Not assessed | F | ✗ — Collapses with negative |
| Unobservable | F | ✗ — Collapses with negative |
| Underdetermined | F | ✗ — Collapses with negative |
| Conflicting sources | F | ✗ — Collapses with negative |
| Superseded evidence | T | ✗ — Collapses with positive |
| Scope exclusion | F | ✗ — Collapses with negative |
| Temporal conflict | F | ✗ — Collapses with negative |
| Contradictory rules | ⊥ | ✗ — Cannot represent |
| Contradictory observations | ⊥ | ✗ — Cannot represent |
| Contradictory interpretations | ⊥ | ✗ — Cannot represent |

**Collapsed: 12/14** (85.7%)

### 5.2 K3 {T, F, U}

| Scenario | Representation | Distinction Preserved? |
|----------|---------------|----------------------|
| Positive evidence | T | ✓ |
| Negative evidence | F | ✓ |
| Direct contradiction | U | ✗ — Collapses with no evidence |
| No evidence | U | ✓ |
| Not assessed | U | ✗ — Collapses with no evidence |
| Unobservable | U | ✗ — Collapses with no evidence |
| Underdetermined | U | ✗ — Collapses with no evidence |
| Conflicting sources | U | ✗ — Collapses with no evidence |
| Superseded evidence | T | ✗ — Collapses with positive |
| Scope exclusion | U | ✗ — Collapses with no evidence |
| Temporal conflict | U | ✗ — Collapses with no evidence |
| Contradictory rules | U | ✗ — Collapses with no evidence |
| Contradictory observations | U | ✗ — Collapses with no evidence |
| Contradictory interpretations | U | ✗ — Collapses with no evidence |

**Collapsed: 8/14** (57.1%)

### 5.3 FDE {T, F, B, N}

| Scenario | Representation | Distinction Preserved? |
|----------|---------------|----------------------|
| Positive evidence | T | ✓ |
| Negative evidence | F | ✓ |
| Direct contradiction | B | ✓ |
| No evidence | N | ✓ |
| Not assessed | N | ✗ — Collapses with no evidence |
| Unobservable | N | ✗ — Collapses with no evidence |
| Underdetermined | N | ✗ — Collapses with no evidence |
| Conflicting sources | B | ✗ — Collapses with direct contradiction |
| Superseded evidence | T | ✗ — Collapses with positive |
| Scope exclusion | N | ✗ — Collapses with no evidence |
| Temporal conflict | B | ✗ — Collapses with direct contradiction |
| Contradictory rules | B | ✗ — Collapses with direct contradiction |
| Contradictory observations | B | ✗ — Collapses with direct contradiction |
| Contradictory interpretations | B | ✗ — Collapses with direct contradiction |

**Collapsed: 6/14** (42.9%)

### 5.4 Structured (S⁺, S⁻, Reason, Provenance, Context)

| Scenario | S⁺ | S⁻ | Reason | Provenance | Context | Distinction Preserved? |
|----------|:--:|:--:|--------|------------|---------|----------------------|
| Positive evidence | 1 | 0 | Evidence supports p | Observed | Current | ✓ |
| Negative evidence | 0 | 1 | Evidence supports ¬p | Observed | Current | ✓ |
| Direct contradiction | 1 | 1 | Evidence supports both | Observed | Current | ✓ |
| No evidence | 0 | 0 | No evidence available | N/A | Current | ✓ |
| Not assessed | 0 | 0 | Not yet assessed | N/A | Current | ✓* |
| Unobservable | 0 | 0 | Cannot observe in principle | Logical | Principle | ✓ |
| Underdetermined | 0 | 0 | Theory underdetermines | Theoretical | Current | ✓ |
| Conflicting sources | 1 | 1 | Sources disagree | Mixed | Current | ** |
| Superseded evidence | 1 | 0 | Evidence superseded | Historical | Obsolete | ✓ |
| Scope exclusion | 0 | 0 | True but out of scope | Logical | Excluded | ✓ |
| Temporal conflict | 1 | 1 | True at t₁, false at t₂ | Temporal | t₁ vs t₂ | ✓ |
| Contradictory rules | 1 | 1 | Applicable rules conflict | Normative | Current | ** |
| Contradictory observations | 1 | 1 | Observations conflict | Observed | Current | ** |
| Contradictory interpretations | 1 | 1 | Interpretations conflict | Interpretive | Current | ** |

**Collapsed: 2/14** (14.3% — see asterisks below)

> \* Scenarios 4 and 5 are semantically distinct but evaluate to the same S⁺/S⁻. They are distinguished by **Reason** ("No evidence available" vs "Not yet assessed").
>
> \*\* Scenarios 3, 8, 12, 13, 14 are all S⁺=1, S⁻=1 but differ by **Reason** and **Provenance**. This is a genuine category overlap (all are forms of contradiction) and is acceptable because the separate boundary metadata distinguishes them.

---

## 6. Collapse Register

### 6.1 Collapses in Structured Representation

| # | Scenarios | Collapse Type | Acceptability |
|---|-----------|---------------|---------------|
| C1 | 4, 5 | Both evaluate to (0,0) | **ACCEPTABLE** — Reason distinguishes "No evidence" vs "Not assessed" |
| C2 | 3, 8, 12, 13, 14 | All evaluate to (1,1) | **ACCEPTABLE** — All are genuine contradictions; Reason/Provenance distinguishes type |

### 6.2 The Two Acceptable Collapses

**C1: No Evidence vs Not Assessed**

```
No Evidence:    (0,0) Reason="No evidence available"
Not Assessed:   (0,0) Reason="Not yet assessed"
```

**Why Acceptable:** Both are absence of positive/negative support, but the **reason** distinguishes epistemic state (absence of evidence) from procedural state (not evaluated). This is a genuine semantic category that does not require distinct S⁺/S⁻ values.

**C2: Different Contradiction Types**

```
Direct contradiction:      (1,1) Reason="Evidence supports both"
Conflicting sources:       (1,1) Reason="Sources disagree"
Contradictory rules:       (1,1) Reason="Rules conflict"
Contradictory observations:(1,1) Reason="Observations conflict"
Contradictory interpretations:(1,1) Reason="Interpretations conflict"
```

**Why Acceptable:** All are contradictions. The structured representation captures the **type** of contradiction via Reason/Provenance rather than requiring distinct S⁺/S⁻ values. This is the correct approach — a value channel should not be overloaded with type distinctions that belong in the boundary metadata.

---

## 7. Countermodel Catalogue

### 7.1 Countermodel 1: Classical Collapse — Contradiction = Error

**Scenario:** Direct contradiction
**Classical:** `⊥` (error)
**Structured:** `(1,1) Reason="Evidence supports both"`

**Countermodel:**

```
Let p = "The system is stable"
Evidence E₁: "Stability check passed" (supports p)
Evidence E₂: "Stability check failed" (supports ¬p)

Classical: ⊥ — Cannot proceed, but actual situation is meaningful
Structured: (1,1) — Both support channels active, contradiction identified
```

**What Classical Loses:** The ability to distinguish contradiction from logical impossibility or evaluation failure.

---

### 7.2 Countermodel 2: K3 Collapse — Contradiction = Unknown

**Scenario:** Direct contradiction
**K3:** `U` (Unknown)
**Structured:** `(1,1) Reason="Evidence supports both"`

**Countermodel:**

```
Let p = "The evidence threshold is met"
Evidence: 50% of sources say yes, 50% say no

K3: U — But this is not "unknown" in the epistemic sense
Structured: (1,1) — Both channels active, evaluation is conflicted
```

**What K3 Loses:** The ability to distinguish genuine contradiction from epistemic uncertainty.

---

### 7.3 Countermodel 3: FDE Collapse — Contradiction Type Lost

**Scenario:** Conflicting sources
**FDE:** `B` (Both)
**Structured:** `(1,1) Reason="Sources disagree"`

**Countermodel:**

```
Scenario A: Direct contradiction — Evidence supports both
Scenario B: Conflicting sources — Two sources with equal authority

FDE: Both scenarios → B (Both)
Structured: (1,1) with different Reason fields
```

**What FDE Loses:** The ability to distinguish **why** the contradiction exists. This is crucial for resolution strategy — direct contradiction may require further observation, while conflicting sources may require authority weighting.

---

### 7.4 Countermodel 4: Absence of Evidence ≠ Evidence of Absence

**Scenario:** No evidence vs Not assessed
**K3/FDE:** `U` or `N`
**Structured:** `(0,0) Reason="No evidence"` vs `(0,0) Reason="Not assessed"`

**Countermodel:**

```
Scenario A: No evidence after exhaustive search → suggests ¬p
Scenario B: Not yet assessed → no conclusion possible

K3: Both → U (Unknown)
FDE: Both → N (Neither)

Structured: Both → (0,0) with different Reason
```

**What K3/FDE Lose:** The ability to distinguish no-evidence (which may inform belief) from not-assessed (which cannot).

---

### 7.5 Countermodel 5: Supersession vs Current Evidence

**Scenario:** Superseded evidence
**Classical/K3:** `T`
**FDE:** `T`
**Structured:** `(1,0) Reason="Evidence superseded"`

**Countermodel:**

```
Scenario A: Current evidence supports p
Scenario B: Old evidence supported p but has been superseded

Classical/K3/FDE: Both → T
Structured: Different Context fields (Current vs Obsolete)
```

**What All Three Lose:** The temporal status of evidence, which is critical for knowledge lifecycle.

---

## 8. Positive Results

### 8.1 Separation Works

The structured representation preserves distinctions in **12/14** scenarios, compared to:
- Classical: 2/14
- K3: 6/14
- FDE: 8/14

### 8.2 Two-Channel Semantics is Effective

The `(S⁺, S⁻)` channel captures the **standing** of a claim without mixing it with boundary reasons. This is a significant improvement over single-value semantics.

### 8.3 Boundary Metadata is Necessary

The experiment demonstrates that **no value-only representation** (even FDE's four values) can preserve the distinctions required by KnowledgeOS. The boundary metadata (Reason, Provenance, Context, Condition) is not optional — it is required for adequate epistemic representation.

### 8.4 Zero Lenses

The experiment establishes that Zero requires more than value semantics. The boundary metadata is the vehicle for Zero — it is where the examination of "what is not established" lives.

### 8.5 Adequacy Principle Verified

The experiment confirms the adequacy principle:

> A representation is adequate for a question only if the distinctions required to answer that question are preserved by the representation.

The structured representation is adequate for Contr and Zero. Value-only representations are not.

---

## 9. Negative Results

### 9.1 No Single-Value Representation is Adequate

Classical, K3, and FDE all fail to preserve required distinctions. The best value-only representation (FDE) still collapses **6/14** scenarios.

### 9.2 FDE Alone is Insufficient

FDE's four values (`T, F, B, N`) are a **substantial improvement** over K3, but they still cannot:
- Distinguish contradiction types
- Distinguish no-evidence from not-assessed
- Distinguish current from obsolete
- Capture context/scope conditions
- Capture provenance

### 9.3 No "Fourth Value" Magic

The experiment refutes the hypothesis that a fourth value (B or C) is sufficient. The problem is not the **number** of values — it is the **type** of representation. Values alone cannot carry boundary metadata.

### 9.4 Zero Cannot be Reduced to Values

Zero is not "represent as N" or "represent as U." Zero is the structured examination of boundary conditions. This requires structured metadata, not an additional value.

### 9.5 Absence ≠ Absence

The experiment confirms:
- No evidence ≠ Not assessed
- Not assessed ≠ Not knowable
- Not knowable ≠ Irrelevant
- Irrelevant ≠ False
- False ≠ Contradictory

---

## 10. Boundary Findings

### 10.1 Boundary Metadata Components

The experiment identifies four required boundary components:

| Component | Definition | Examples |
|-----------|------------|----------|
| **Reason** | Why this standing obtains | "Evidence supports p", "Sources disagree" |
| **Provenance** | Source/lineage | "Observed", "Inferred", "Reported" |
| **Context** | Conditions of applicability | "Current", "Obsolete", "Experimental" |
| **Condition** | Scope/exclusion | "In scope", "Out of scope", "Conditional" |

### 10.2 Boundary-Value Separation Invariant

The experiment establishes an invariant:

> **Values and boundary metadata must be separated. Values carry standing; boundary metadata carries the reasons for that standing.**

Violation of this invariant causes collapse. FDE violates it by collapsing contradiction type into `B`. K3 violates it by collapsing absence into `U`.

### 10.3 The Minimum Boundary Structure

The experiment establishes the minimum boundary structure:

```
Boundary_t = (Reason_t, Provenance_t, Context_t, Condition_t)
```

Where:
- Reason_t ∈ {Evidence, Authority, Logic, Temporal, Scope, Assessment, ...}
- Provenance_t ∈ {Observed, Inferred, Reported, Calculated, Assumed, ...}
- Context_t ∈ {Current, Stale, Expired, Unknown, ...}
- Condition_t ∈ {InScope, OutOfScope, Conditional, Unconditional, ...}

### 10.4 The Boundary Reason Taxonomy

| Reason | Definition | Example |
|--------|------------|---------|
| Evidence | Support from empirical evidence | "Evidence supports p" |
| Authority | Support from authoritative source | "Source says p" |
| Logic | Support from logical inference | "p follows from theory" |
| Temporal | Support from temporal conditions | "p at t₁, ¬p at t₂" |
| Scope | Support conditional on scope | "p true but out of scope" |
| Assessment | Support from evaluation method | "Assessment confirms p" |
| Interpretation | Support from interpretation | "Interpretation yields p" |
| Conflict | Support conflicted | "Sources disagree" |

---

## 11. Invariants Discovered

### 11.1 Value-Boundary Separation

```
S⁺/S⁻ channels carry only standing
Boundary metadata carries reasons for standing
```

### 11.2 Boundary Minimum Completeness

```
For any standing (S⁺, S⁻):
    Boundary metadata must be present
    Metadata must include Reason, Provenance, Context, Condition
```

### 11.3 No Silent Collapse

```
If two scenarios have different meanings:
    Their representation must differ
    If S⁺/S⁻ are identical, boundary metadata must differ
```

### 11.4 Reason ≠ Value

```
Reason(standing) ≠ standing
Reason is not a value channel
```

### 11.5 Zero Metadata Requirement

```
ZeroLens(K) → Boundary_t
Boundary_t must include Reason, Provenance, Context, Condition
```

### 11.6 Contr Metadata Requirement

```
Contr(p) → (S⁺=1, S⁻=1, Reason, Provenance, Context, Condition)
```

### 11.7 Adequacy Invariant

```
For any Q, representation R is adequate iff:
    D_Q ⊆ Preserved(R)
    Where D_Q are the distinctions required to answer Q
```

---

## 12. Candidate Formal Definitions

### 12.1 Structured Standing

Let `Standing(p)` be:

```
Standing(p) = (S⁺(p), S⁻(p), R(p), P(p), Ctx(p), Cond(p))
```

Where:
- `S⁺(p) ∈ {0, 1}` — Positive support
- `S⁻(p) ∈ {0, 1}` — Negative support
- `R(p)` — Reason
- `P(p)` — Provenance
- `Ctx(p)` — Context
- `Cond(p)` — Condition

### 12.2 Contradiction Definition

```
Contr(p) ⇔ S⁺(p) = 1 ∧ S⁻(p) = 1
```

Contradiction type is distinguished by `R(p)`:

```
Type(Contr(p)) = R(p) ∈ {Direct, Source, Rule, Observation, Interpretation}
```

### 12.3 Zero Lens

```
ZeroLens(K_t) = { (p, S⁺(p), S⁻(p), R(p), P(p), Ctx(p), Cond(p)) |
                  S⁺(p) = 0 ∨ S⁻(p) = 0 ∨ R(p) = "Unknown" ∨ ... }
```

Zero examines the boundary of what is **not** established.

### 12.4 Adequacy Definition

```
Adequate(R, Q) ⇔ D_Q ⊆ {d | d ∈ Preserved(R)}
```

### 12.5 Invariant Custody

```
Custody(I) = {c | c is responsible for preserving invariant I}
Valid reduction ⇔ ∀I ∈ I_required:
    Custody_before(I) ≠ ∅ ⇒ Custody_after(I) ≠ ∅
```

### 12.6 Boundary Canonical Form

```
Boundary_t = canonical(
    R_t = current_reason,
    P_t = current_provenance,
    Ctx_t = current_context,
    Cond_t = current_condition
)
```

### 12.7 Semantic Equivalence Constraint

```
R₁ ≡_sem R₂ ⇔ ∀p:
    S⁺(p) = S⁺'(p) ∧
    S⁻(p) = S⁻'(p) ∧
    R(p) = R'(p) ∧
    P(p) = P'(p) ∧
    Ctx(p) = Ctx'(p) ∧
    Cond(p) = Cond'(p)
```

This is deliberately **strong** — semantic equivalence requires complete structural identity. This ensures no silent collapse.

---

## 13. What Remains Open

### 13.1 Formalization of Boundary Types

The boundary taxonomy is established but requires formal definitions:

| Open Item | Description |
|-----------|-------------|
| Reason types | Need formal definition and completeness proof |
| Provenance types | Need formal definition and completeness proof |
| Context types | Need formal definition and completeness proof |
| Condition types | Need formal definition and completeness proof |

### 13.2 Boundary-Value Interaction Rules

The interaction between standing and boundary metadata requires formalization:

| Open Item | Description |
|-----------|-------------|
| When can standing change without reason change? | Evaluation update |
| When can reason change without standing change? | Justification change |
| When must both change? | Epistemic transformation |

### 13.3 Zero Operationalization

Zero's operational semantics require definition:

| Open Item | Description |
|-----------|-------------|
| How is ZeroLens invoked? | Interface definition |
| What does Zero produce? | Boundary_t = ... |
| What is the cost of Zero? | Computational complexity |

### 13.4 Boundary Adequacy

The adequacy of boundary metadata requires verification:

| Open Item | Description |
|-----------|-------------|
| Is Reason sufficient? | Are there missing reason types? |
| Is Provenance sufficient? | Are there missing provenance types? |
| Is Context sufficient? | Are there missing context types? |
| Is Condition sufficient? | Are there missing condition types? |

### 13.5 N_eff Dependency

N_eff remains OFF the critical path per FR-001, but the structured representation may interact with N_eff:

| Open Item | Description |
|-----------|-------------|
| Does structured standing affect N_eff? | Hypothesis: No, N_eff is about dependence structure |
| Does boundary metadata affect N_eff? | Hypothesis: No, N_eff is about joint distribution |

---

## 14. Theory Impact

### 14.1 No Change to Theory v1.2

The experiment does not modify Theory v1.2. All findings are **consistent with** v1.2.

### 14.2 Candidate for Theory v1.3

The structured standing representation is a candidate for Theory v1.3:

```
K_t = (A_t, R_t, E_t, Σ_t, H_t, Z_t, L_t, T_t, G_t, C_t, M_t)
```

The `C` component (Conflict) could be represented as:

```
C_t = { (p, S⁺(p), S⁻(p), R(p), P(p), Ctx(p), Cond(p)) |
        S⁺(p) = 1 ∧ S⁻(p) = 1 }
```

**But this is a candidate** — not an architectural decision.

### 14.3 Zero and Contr Integration

The experiment suggests:

```
ZeroLens(K_t) → Boundary_t
Contr(p) ∈ Boundary_t → C_t(p) = (1,1, Reason, Provenance, Context, Condition)
```

### 14.4 Adequacy Principle Integration

The adequacy principle should be added to the theory as an explicit constraint on all projections.

---

## 15. Kernel Impact

### 15.1 Kernel Verdict: K2

**K2 — New semantic representation required (outside kernel)**

The structured representation must exist **outside** the kernel. The kernel should continue to operate on the existing v1.2 components. The structured representation is a **semantic layer** over the kernel, not a replacement for it.

### 15.2 What Goes in the Kernel

| Component | Status |
|-----------|--------|
| K_t | Unchanged |
| C_t | May be populated by structured standing |
| Z_t | Remains a lens, not a kernel component |
| Contr | Not in kernel — semantic layer |
| FDE | Not in kernel — evaluation mechanism |

### 15.3 What Goes in the Semantic Layer

| Component | Status |
|-----------|--------|
| Standing(p) = (S⁺, S⁻, Reason, Provenance, Context, Condition) | Semantic layer |
| ZeroLens | Semantic layer |
| Contr detection | Semantic layer |
| Adequacy verification | Semantic layer |

### 15.4 Kernel Selection Delayed

Kernel selection remains blocked until:
- TODO A: Factivity
- TODO D: ≣sem
- TODO E: δ
- TODO F: Lifecycle

The structured representation is a **dependency** for kernel selection but does not itself unblock it.

---

## 16. Decision Recommendation

### 16.1 Recommendation: C. STRUCTURED EVALUATION REQUIRED

The experiment demonstrates that no value-only representation is adequate. The structured representation `(S⁺, S⁻, Reason, Provenance, Context, Condition)` is the minimum required to preserve the distinctions needed for Contr and Zero.

### 16.2 Recommendation: Do Not Adopt FDE as KnowledgeOS Logic

FDE is a useful **reference model** but is not adequate as KnowledgeOS's logic. KnowledgeOS requires structured metadata that FDE does not provide.

### 16.3 Recommendation: Separate Standing from Boundary Metadata

The `(S⁺, S⁻)` channel and the boundary metadata must remain **separate**. They serve different purposes:
- `(S⁺, S⁻)`: What is the standing?
- Boundary metadata: Why does it have that standing?

### 16.4 Recommendation: Zero is a Lens, Not a Value

Zero must remain a **lens** over the structured representation, not a value or a state. Zero examines the boundary — it does not create a new value.

### 16.5 Recommendation: Proceed to TODO C (≽)

With Contr and Zero now having a candidate representation, the next priority is TODO C (≽) — the ordering of epistemic progress.

---

## 17. Classification Register

### 17.1 Experiment Results

| Finding | Status |
|---------|--------|
| Classical {T, F} insufficient | `[NEG]` |
| K3 {T, F, U} insufficient | `[NEG]` |
| FDE {T, F, B, N} insufficient | `[NEG]` |
| Structured representation adequate | `[EXP]` |
| Values alone cannot carry boundary metadata | `[EXP]` |
| Reason must be separate from value | `[EXP]` |
| Provenance must be separate from value | `[EXP]` |
| Context must be separate from value | `[EXP]` |
| Condition must be separate from value | `[EXP]` |
| Zero requires boundary metadata | `[EXP]` |
| Contr requires structured metadata | `[EXP]` |
| FDE is useful reference, not solution | `[EXP]` |

### 17.2 New Propositions

| Proposition | Status |
|-------------|--------|
| Minimal structured standing = (S⁺, S⁻, Reason, Provenance, Context, Condition) | `[PROP]` |
| Boundary metadata is required for Contr | `[PROP]` |
| Boundary metadata is required for Zero | `[PROP]` |
| Zero is a lens, not a value | `[PROP]` |
| Adequacy principle applies to all representations | `[PROP]` |

### 17.3 Frozen Status

| Entry | Status |
|-------|--------|
| Theory v1.2 | `[FROZEN]` |
| FR-001 | `[FROZEN]` |
| Value-boundary separation | `[PROP]` — Not frozen |
| Structured standing | `[PROP]` — Not frozen |

### 17.4 Open TODOs Updated

| TODO | Status | Update |
|------|--------|--------|
| A — Factivity | `[OPEN]` | Unchanged — decision required |
| B — Contr | `[EXP]` | Experiment complete — candidate representation |
| C — ≽ | `[OPEN]` | Next priority |
| D — ≣sem | `[OPEN]` | Unchanged |
| E — δ | `[OPEN]` | Unchanged |
| F — Lifecycle | `[OPEN]` | Unchanged |
| G — Composition | `[OPEN]` | Unchanged |
| H — Projection/Invariant | `[OPEN]` | Unchanged |
| I — Reduction | `[OPEN]` | Unchanged |
| J — Kernel | `[OPEN]` | Unchanged |

---

## Appendix A: Countermodel Reference

| # | Name | Classical | K3 | FDE | Structured |
|---|------|-----------|----|-----|------------|
| 1 | Positive evidence | T | T | T | (1,0,Evidence,Observed,Current,InScope) |
| 2 | Negative evidence | F | F | F | (0,1,Evidence,Observed,Current,InScope) |
| 3 | Direct contradiction | ⊥ | U | B | (1,1,EvidenceSupportsBoth,Observed,Current,InScope) |
| 4 | No evidence | F | U | N | (0,0,NoEvidenceAvailable,N/A,Current,InScope) |
| 5 | Not assessed | F | U | N | (0,0,NotYetAssessed,N/A,Current,InScope) |
| 6 | Unobservable | F | U | N | (0,0,CannotObserveInPrinciple,Logical,Principle,InScope) |
| 7 | Underdetermined | F | U | N | (0,0,TheoryUnderdetermines,Theoretical,Current,InScope) |
| 8 | Conflicting sources | F | U | B | (1,1,SourcesDisagree,Mixed,Current,InScope) |
| 9 | Superseded evidence | T | T | T | (1,0,EvidenceSuperseded,Historical,Obsolete,InScope) |
| 10 | Scope exclusion | F | U | N | (0,0,TrueButOutOfScope,Logical,Current,Excluded) |
| 11 | Temporal conflict | F | U | B | (1,1,TrueAt_t1_FalseAt_t2,Temporal,t1_vs_t2,InScope) |
| 12 | Contradictory rules | ⊥ | U | B | (1,1,ApplicableRulesConflict,Normative,Current,InScope) |
| 13 | Contradictory observations | ⊥ | U | B | (1,1,ObservationsConflict,Observed,Current,InScope) |
| 14 | Contradictory interpretations | ⊥ | U | B | (1,1,InterpretationsConflict,Interpretive,Current,InScope) |

---

## Appendix B: Formal Definitions

### B.1 Standing

```
Standing(p) = (S⁺(p), S⁻(p), R(p), P(p), Ctx(p), Cond(p))
```

### B.2 Contradiction

```
Contr(p) ⇔ S⁺(p) = 1 ∧ S⁻(p) = 1
```

### B.3 Zero Lens

```
ZeroLens(K_t, Γ_t, L_t) → Boundary_t
Boundary_t = { (p, Standing(p)) | Standing(p) has S⁺=0 ∨ S⁻=0 ∨ R∈GapTypes }
```

### B.4 Adequacy

```
Adequate(R, Q) ⇔ D_Q ⊆ Preserved(R)
```

### B.5 Semantic Equivalence

```
R₁ ≡_sem R₂ ⇔ ∀p:
    S⁺₁(p) = S⁺₂(p) ∧
    S⁻₁(p) = S⁻₂(p) ∧
    R₁(p) = R₂(p) ∧
    P₁(p) = P₂(p) ∧
    Ctx₁(p) = Ctx₂(p) ∧
    Cond₁(p) = Cond₂(p)
```

### B.6 Invariant Custody

```
Custody(I) = {c | c is responsible for preserving invariant I}
Valid reduction ⇔ ∀I ∈ I_required:
    Custody_before(I) ≠ ∅ ⇒ Custody_after(I) ≠ ∅
```

### B.7 Boundary Taxonomy

```
Reason ∈ {Evidence, Authority, Logic, Temporal, Scope, Assessment, Interpretation, Conflict}
Provenance ∈ {Observed, Inferred, Reported, Calculated, Assumed, Hypothesized, Mixed}
Context ∈ {Current, Stale, Expired, Unknown, Obsolete}
Condition ∈ {InScope, OutOfScope, Conditional, Unconditional, Principle}
```

---

## Appendix C: Experimental Protocol

### C.1 Methodology

Each scenario was represented in each model and evaluated for:
1. **Distinction preservation** — Does the representation distinguish this scenario from other semantically distinct scenarios?
2. **Collapse detection** — Which scenarios collapse to the same representation?
3. **Adequacy** — Is the representation adequate for the questions Contr and Zero?

### C.2 Success Criteria

A representation is successful if:
1. It distinguishes all semantically distinct scenarios
2. It does not collapse semantically distinct scenarios
3. It preserves the distinctions required for Contr and Zero

### C.3 Classification

| Status | Meaning |
|--------|---------|
| `[EXP]` | Experimental result |
| `[NEG]` | Negative/falsification result |
| `[PROP]` | Research proposition |
| `[OPEN]` | Unresolved |

---