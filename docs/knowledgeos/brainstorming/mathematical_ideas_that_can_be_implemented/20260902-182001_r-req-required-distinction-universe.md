# $\mathcal{R}_{\text{req}}$ — Required Distinction Universe

## 1. Mathematical and Semantic Foundations

To establish a formally rigorous specification of $\mathcal{R}_{\text{req}}$, we model the system state space as a set $S$. A **semantic distinction** $d$ is an equivalence relation $\sim_d$ on $S$, partitioning states into equivalence classes.

$$\mathcal{R}_{\text{req}} = \{ d_1, d_2, \dots, d_k \}$$

### Definition: Distinction Preservation

Let $\mathcal{K}$ be a knowledge representation language with an encoding mapping $\mathcal{E}: S \to \mathcal{K}$.
$\mathcal{K}$ **preserves** a distinction $d \in \mathcal{R}_{\text{req}}$ if and only if:

$$\forall s_1, s_2 \in S : (s_1 \nsim_d s_2) \implies (\mathcal{E}(s_1) \neq \mathcal{E}(s_2))$$

If $\mathcal{E}(s_1) = \mathcal{E}(s_2)$ when $s_1 \nsim_d s_2$, the language $\mathcal{K}$ **collapses** the distinction $d$.

### Formal Preservation Criterion

A transformation or projection $\pi: \mathcal{K} \to \mathcal{K}'$ is **$\mathcal{R}_{\text{req}}$-adequate** if:

$$\forall d \in \mathcal{R}_{\text{req}}, \forall s_1, s_2 \in S : (s_1 \nsim_d s_2) \implies (\pi(\mathcal{E}(s_1)) \neq \pi(\mathcal{E}(s_2)))$$

---

## 2. Comprehensive Inventory of Required Distinctions

### 2.1 Epistemic Status Distinctions

```
                       ┌───────────────────────┐
                       │     Epistemic State   │
                       └───────────┬───────────┘
                                   │
            ┌──────────────────────┴──────────────────────┐
            ▼                                             ▼
  ┌───────────────────┐                         ┌───────────────────┐
  │   Assessed State  │                         │  Unassessed State │
  └─────────┬─────────┘                         └───────────────────┘
            │
      ┌─────┴─────────────────────┬──────────────────────────┐
      ▼                           ▼                          ▼
┌───────────┐             ┌───────────────┐          ┌───────────────┐
│ Consistent│             │ Contradictory │          │ Underdetermined│
└─────┬─────┘             └───────────────┘          └───────────────┘
      │
  ┌───┴───────────────┐
  ▼                   ▼
┌───────────┐   ┌───────────┐
│   Known   │   │  Unknown  │
└───────────┘   └───────────┘

```

#### 1.1 Knowledge Status

* **Definition:** The fundamental truth and availability state of an assertion within the system's epistemic frame.
* **Values:** `KnownTrue`, `KnownFalse`, `Unknown` (Open), `Contradictory` (Both), `Underdetermined` (Insufficient information to decide), `NotAssessed`.
* **Formal Semantics:** Evaluated over First-Degree Entailment (FDE) double-powerset truth valuations $V(\phi) \subseteq \{T, F\}$.
* **Evidence:** Demonstrated by *KR-CONTR-FDE-2026-09*. Classical logic collapses `Contradictory` to explosive falsity; 3-valued logics (K3/L3) collapse `Contradictory` and `Underdetermined` into a single `Undefined` state.
* **Priority:** **P1 (Core Invariant)**

#### 1.2 Justification Mode

* **Definition:** The operational origin and structural lineage supporting an epistemic claim.
* **Values:** `Evidenced` (Direct observation), `Inferred` (Logical deduction/induction), `Reported` (External testimony/oracle), `Assumed` (Axiomatic default).
* **Formal Semantics:** A tuple $\langle \phi, J, m \rangle$ where $m \in \text{Mode}$ and $J$ is a proof tree or justification graph.
* **Evidence:** Required by *Gap Theory v1.0* (Class G3: Lineage/Justification Gap).
* **Priority:** **P1 (Core Invariant)**

#### 1.3 Intensional vs. Extensional Identity

* **Definition:** The distinction between an object's structural definition/sense ($g_1 \equiv g_2$) and its current domain value/reference ($e_1 = e_2$).
* **Values:** `IntensionallyIdentical`, `ExtensionallyEqual`, `Distinct`.
* **Formal Semantics:** $a \sim_{\text{ext}} b \iff \mathbb{I}(a) = \mathbb{I}(b)$ in model $\mathcal{M}$; $a \sim_{\text{int}} b \iff \forall \mathcal{M}, \mathbb{I}_{\mathcal{M}}(a) = \mathbb{I}_{\mathcal{M}}(b)$.
* **Evidence:** Essential for handling identity shifts in dynamic knowledge graphs (e.g., merging duplicate entities without losing distinct lineage).
* **Priority:** **P2 (Required)**

---

### 2.2 Temporal Distinctions

#### 2.1 Currency & Validity State

* **Definition:** The status of knowledge relative to real-time evolution and shelf-life expiration.
* **Values:** `Current` (Valid now), `Stale` (Requires re-evaluation), `Expired` (Formally invalid due to time-out), `Historical` (Valid only in a past frame).
* **Formal Semantics:** Let $t_{\text{now}}$ be system time, $t_{\text{valid}}$ be validity window $[t_{\text{start}}, t_{\text{end}}]$, and $t_{\text{ttl}}$ be time-to-live.
* **Evidence:** Required to prevent stale state propagation in dynamic automated reasoning.
* **Priority:** **P1 (Core Invariant)**

#### 2.2 Allen Interval Temporal Relations

* **Definition:** The primitive topological relations between two temporal intervals $I_1 = [s_1, e_1]$ and $I_2 = [s_2, e_2]$.
* **Values:** `Before`, `After`, `Meets`, `MetBy`, `Overlaps`, `OverlappedBy`, `Starts`, `StartedBy`, `During`, `Contains`, `Finishes`, `FinishedBy`, `Equals`.
* **Formal Semantics:** Standard Allen Algebra axioms over ordered interval endpoints.
* **Evidence:** Necessary for causality tracing and event-driven state transitions.
* **Priority:** **P2 (Required)**

---

### 2.3 Evidential & Justification Distinctions

#### 3.1 Evidence Directionality

* **Definition:** The polar impact of evidence on a given hypothesis.
* **Values:** `Supporting` ($e \Rightarrow h$), `RefUTING` ($e \Rightarrow \neg h$), `Neutral`, `Dual` (Supports both $h$ and $\neg h$ under different interpretations).
* **Formal Semantics:** Represented as signed weight functions $W: E \times H \to [-1, 1]$.
* **Evidence:** *KR-CONTR-FDE-2026-09* (Scenario 4: Conflicting Multi-Source Data).
* **Priority:** **P1 (Core Invariant)**

#### 3.2 Absence of Evidence vs. Evidence of Absence

* **Definition:** The critical epistemic distinction between lacking observations and observing the non-existence/failure of a phenomenon.
* **Values:** `AbsenceOfEvidence` ($\neg \mathbf{K} \phi$), `EvidenceOfAbsence` ($\mathbf{K} \neg \phi$).
* **Formal Semantics:**

$$\text{AbsenceOfEvidence} \iff \phi \notin \mathcal{K} \land \neg \phi \notin \mathcal{K}$$


$$\text{EvidenceOfAbsence} \iff \exists e \in E : e \vdash \neg \phi$$


* **Evidence:** *Zero Lens Specification* (Non-collapse Invariant #3).
* **Priority:** **P1 (Core Invariant)**

---

### 2.4 Evaluative & Boundary Distinctions

#### 4.1 Resolution Status

* **Definition:** The convergence state of a system query or contradiction.
* **Values:** `Resolved`, `Unresolved`, `InProgress`, `Irresolvable` (Structural paradox).
* **Formal Semantics:** State machine transitions mapping open goals $G$ to terminal or non-terminal states.
* **Evidence:** *Gap Theory v1.0* (Class G7: Unresolved Contradiction Gap).
* **Priority:** **P2 (Required)**

#### 4.2 Boundary & Scope

* **Definition:** Domain containment status relative to system boundaries.
* **Values:** `InScope`, `OutOfScope`, `ConditionalScope`.
* **Formal Semantics:** Set membership predicate $\phi \in \text{Domain}_{\text{active}}$.
* **Evidence:** Prevents open-world assumptions from triggering invalid inferences outside designated context boundaries.
* **Priority:** **P2 (Required)**

---

## 3. Structural Comparison Matrix

| Distinction Category | Classical Logic (FOL) | 3-Valued Logics (K3/L3) | 4-Valued FDE | KnowledgeOS ($\mathcal{R}_{\text{req}}$) |
| --- | --- | --- | --- | --- |
| **Contradiction ($\top$) vs. Unknown ($\bot$)** | Collapsed (Explosion) | Collapsed (Both = $U$) | **Preserved** | **Preserved** |
| **Absence of Evidence vs. Evidence of Absence** | Collapsed ($\neg P \equiv \text{False}$) | Collapsed | Collapsed | **Preserved** |
| **Justification Source** | Omitted | Omitted | Omitted | **Preserved** |
| **Temporal Currency (Stale vs Expired)** | Omitted | Omitted | Omitted | **Preserved** |
| **Intensional vs Extensional Identity** | Partial (Leibniz Equality) | Omitted | Omitted | **Preserved** |

---

## 4. Distinction Relationship & Dependency Map

```
                     ┌───────────────────────────────┐
                     │ Epistemic Status (1.1)        │
                     └──────────────┬────────────────┘
                                    │
            ┌───────────────────────┼───────────────────────┐
            │                       │                       │
            ▼                       ▼                       ▼
┌───────────────────────┐ ┌───────────────────┐ ┌───────────────────────┐
│ Justification (1.2)   │ │ Evidential (3.2)  │ │ Temporal Currency(2.1)│
└───────────┬───────────┘ └─────────┬─────────┘ └───────────┬───────────┘
            │                       │                       │
            └───────────────────────┼───────────────────────┘
                                    │
                                    ▼
                     ┌───────────────────────────────┐
                     │ Resolution & Boundary (4.1/2) │
                     └───────────────────────────────┘

```

* **Core Dependencies:**
1. **Epistemic Status (1.1)** is the root dependent for **Justification (1.2)** and **Evidential Direction (3.1)**.
2. **Absence of Evidence (3.2)** requires **Knowledge Status (1.1)** to evaluate open-world assumptions ($\neg \mathbf{K} \phi$).
3. **Resolution Status (4.1)** depends on detecting a **Contradiction** state in **Epistemic Status (1.1)**.



---

## 5. Formal Verification Criteria

To verify that a proposed KnowledgeOS kernel or projection $\pi$ preserves $\mathcal{R}_{\text{req}}$, it must pass the following test suite:

### 1. The Separation Test

For every pair of distinct values $(v_i, v_j)$ in any distinction $d \in \mathcal{R}_{\text{req}}$, construct two minimal test states $s_i, s_j \in S$.

* **Criterion:** $\pi(\mathcal{E}(s_i)) \neq \pi(\mathcal{E}(s_j))$. If equality holds, verification **FAILS** (Distinction Collapsed).

### 2. The Round-Trip Transformation Test

For any valid projection function $f: \mathcal{K} \to \mathcal{K}'$ and reconstruction function $g: \mathcal{K}' \to \mathcal{K}$:

* **Criterion:** $\forall d \in \mathcal{R}_{\text{req}}, \quad s \sim_d (g \circ f)(s)$.

---

## 6. Prioritization and Ratification Strategy

### Priority Tiers

* **Tier P1 (Core Invariants):** Epistemic Status (1.1), Justification Mode (1.2), Currency (2.1), Absence vs. Evidence of Absence (3.2). *Must be preserved by all core kernels and projections.*
* **Tier P2 (Required):** Allen Relations (2.2), Resolution Status (4.1), Scope Boundary (4.2), Intensional Identity (1.3). *Must be supported natively by the top-level KR language.*
* **Tier P3 (Extended Domain-Specific):** Deontic/Normative distinctions (`Can` vs `Should`), Fine-grained probability distributions.
#
# $\mathcal{R}_{\text{req}}$ — Required Distinction Universe

## 1. Mathematical and Semantic Foundations

To establish a formally rigorous specification of $\mathcal{R}_{\text{req}}$, we model the system state space as a set $S$. A **semantic distinction** $d$ is an equivalence relation $\sim_d$ on $S$, partitioning states into equivalence classes.

$$\mathcal{R}_{\text{req}} = \{ d_1, d_2, \dots, d_k \}$$

### Definition: Distinction Preservation

Let $\mathcal{K}$ be a knowledge representation language with an encoding mapping $\mathcal{E}: S \to \mathcal{K}$.
$\mathcal{K}$ **preserves** a distinction $d \in \mathcal{R}_{\text{req}}$ if and only if:

$$\forall s_1, s_2 \in S : (s_1 \nsim_d s_2) \implies (\mathcal{E}(s_1) \neq \mathcal{E}(s_2))$$

If $\mathcal{E}(s_1) = \mathcal{E}(s_2)$ when $s_1 \nsim_d s_2$, the language $\mathcal{K}$ **collapses** the distinction $d$.

### Formal Preservation Criterion

A transformation or projection $\pi: \mathcal{K} \to \mathcal{K}'$ is **$\mathcal{R}_{\text{req}}$-adequate** if:

$$\forall d \in \mathcal{R}_{\text{req}}, \forall s_1, s_2 \in S : (s_1 \nsim_d s_2) \implies (\pi(\mathcal{E}(s_1)) \neq \pi(\mathcal{E}(s_2)))$$

---

## 2. Comprehensive Inventory of Required Distinctions

### 2.1 Epistemic Status Distinctions

```
                       ┌───────────────────────┐
                       │     Epistemic State   │
                       └───────────┬───────────┘
                                   │
            ┌──────────────────────┴──────────────────────┐
            ▼                                             ▼
  ┌───────────────────┐                         ┌───────────────────┐
  │   Assessed State  │                         │  Unassessed State │
  └─────────┬─────────┘                         └───────────────────┘
            │
      ┌─────┴─────────────────────┬──────────────────────────┐
      ▼                           ▼                          ▼
┌───────────┐             ┌───────────────┐          ┌───────────────┐
│ Consistent│             │ Contradictory │          │ Underdetermined│
└─────┬─────┘             └───────────────┘          └───────────────┘
      │
  ┌───┴───────────────┐
  ▼                   ▼
┌───────────┐   ┌───────────┐
│   Known   │   │  Unknown  │
└───────────┘   └───────────┘

```

#### 1.1 Knowledge Status

* **Definition:** The fundamental truth and availability state of an assertion within the system's epistemic frame.
* **Values:** `KnownTrue`, `KnownFalse`, `Unknown` (Open), `Contradictory` (Both), `Underdetermined` (Insufficient information to decide), `NotAssessed`.
* **Formal Semantics:** Evaluated over First-Degree Entailment (FDE) double-powerset truth valuations $V(\phi) \subseteq \{T, F\}$.
* **Evidence:** Demonstrated by *KR-CONTR-FDE-2026-09*. Classical logic collapses `Contradictory` to explosive falsity; 3-valued logics (K3/L3) collapse `Contradictory` and `Underdetermined` into a single `Undefined` state.
* **Priority:** **P1 (Core Invariant)**

#### 1.2 Justification Mode

* **Definition:** The operational origin and structural lineage supporting an epistemic claim.
* **Values:** `Evidenced` (Direct observation), `Inferred` (Logical deduction/induction), `Reported` (External testimony/oracle), `Assumed` (Axiomatic default).
* **Formal Semantics:** A tuple $\langle \phi, J, m \rangle$ where $m \in \text{Mode}$ and $J$ is a proof tree or justification graph.
* **Evidence:** Required by *Gap Theory v1.0* (Class G3: Lineage/Justification Gap).
* **Priority:** **P1 (Core Invariant)**

#### 1.3 Intensional vs. Extensional Identity

* **Definition:** The distinction between an object's structural definition/sense ($g_1 \equiv g_2$) and its current domain value/reference ($e_1 = e_2$).
* **Values:** `IntensionallyIdentical`, `ExtensionallyEqual`, `Distinct`.
* **Formal Semantics:** $a \sim_{\text{ext}} b \iff \mathbb{I}(a) = \mathbb{I}(b)$ in model $\mathcal{M}$; $a \sim_{\text{int}} b \iff \forall \mathcal{M}, \mathbb{I}_{\mathcal{M}}(a) = \mathbb{I}_{\mathcal{M}}(b)$.
* **Evidence:** Essential for handling identity shifts in dynamic knowledge graphs (e.g., merging duplicate entities without losing distinct lineage).
* **Priority:** **P2 (Required)**

---

### 2.2 Temporal Distinctions

#### 2.1 Currency & Validity State

* **Definition:** The status of knowledge relative to real-time evolution and shelf-life expiration.
* **Values:** `Current` (Valid now), `Stale` (Requires re-evaluation), `Expired` (Formally invalid due to time-out), `Historical` (Valid only in a past frame).
* **Formal Semantics:** Let $t_{\text{now}}$ be system time, $t_{\text{valid}}$ be validity window $[t_{\text{start}}, t_{\text{end}}]$, and $t_{\text{ttl}}$ be time-to-live.
* **Evidence:** Required to prevent stale state propagation in dynamic automated reasoning.
* **Priority:** **P1 (Core Invariant)**

#### 2.2 Allen Interval Temporal Relations

* **Definition:** The primitive topological relations between two temporal intervals $I_1 = [s_1, e_1]$ and $I_2 = [s_2, e_2]$.
* **Values:** `Before`, `After`, `Meets`, `MetBy`, `Overlaps`, `OverlappedBy`, `Starts`, `StartedBy`, `During`, `Contains`, `Finishes`, `FinishedBy`, `Equals`.
* **Formal Semantics:** Standard Allen Algebra axioms over ordered interval endpoints.
* **Evidence:** Necessary for causality tracing and event-driven state transitions.
* **Priority:** **P2 (Required)**

---

### 2.3 Evidential & Justification Distinctions

#### 3.1 Evidence Directionality

* **Definition:** The polar impact of evidence on a given hypothesis.
* **Values:** `Supporting` ($e \Rightarrow h$), `RefUTING` ($e \Rightarrow \neg h$), `Neutral`, `Dual` (Supports both $h$ and $\neg h$ under different interpretations).
* **Formal Semantics:** Represented as signed weight functions $W: E \times H \to [-1, 1]$.
* **Evidence:** *KR-CONTR-FDE-2026-09* (Scenario 4: Conflicting Multi-Source Data).
* **Priority:** **P1 (Core Invariant)**

#### 3.2 Absence of Evidence vs. Evidence of Absence

* **Definition:** The critical epistemic distinction between lacking observations and observing the non-existence/failure of a phenomenon.
* **Values:** `AbsenceOfEvidence` ($\neg \mathbf{K} \phi$), `EvidenceOfAbsence` ($\mathbf{K} \neg \phi$).
* **Formal Semantics:**

$$\text{AbsenceOfEvidence} \iff \phi \notin \mathcal{K} \land \neg \phi \notin \mathcal{K}$$


$$\text{EvidenceOfAbsence} \iff \exists e \in E : e \vdash \neg \phi$$


* **Evidence:** *Zero Lens Specification* (Non-collapse Invariant #3).
* **Priority:** **P1 (Core Invariant)**

---

### 2.4 Evaluative & Boundary Distinctions

#### 4.1 Resolution Status

* **Definition:** The convergence state of a system query or contradiction.
* **Values:** `Resolved`, `Unresolved`, `InProgress`, `Irresolvable` (Structural paradox).
* **Formal Semantics:** State machine transitions mapping open goals $G$ to terminal or non-terminal states.
* **Evidence:** *Gap Theory v1.0* (Class G7: Unresolved Contradiction Gap).
* **Priority:** **P2 (Required)**

#### 4.2 Boundary & Scope

* **Definition:** Domain containment status relative to system boundaries.
* **Values:** `InScope`, `OutOfScope`, `ConditionalScope`.
* **Formal Semantics:** Set membership predicate $\phi \in \text{Domain}_{\text{active}}$.
* **Evidence:** Prevents open-world assumptions from triggering invalid inferences outside designated context boundaries.
* **Priority:** **P2 (Required)**

---

## 3. Structural Comparison Matrix

| Distinction Category | Classical Logic (FOL) | 3-Valued Logics (K3/L3) | 4-Valued FDE | KnowledgeOS ($\mathcal{R}_{\text{req}}$) |
| --- | --- | --- | --- | --- |
| **Contradiction ($\top$) vs. Unknown ($\bot$)** | Collapsed (Explosion) | Collapsed (Both = $U$) | **Preserved** | **Preserved** |
| **Absence of Evidence vs. Evidence of Absence** | Collapsed ($\neg P \equiv \text{False}$) | Collapsed | Collapsed | **Preserved** |
| **Justification Source** | Omitted | Omitted | Omitted | **Preserved** |
| **Temporal Currency (Stale vs Expired)** | Omitted | Omitted | Omitted | **Preserved** |
| **Intensional vs Extensional Identity** | Partial (Leibniz Equality) | Omitted | Omitted | **Preserved** |

---

## 4. Distinction Relationship & Dependency Map

```
                     ┌───────────────────────────────┐
                     │ Epistemic Status (1.1)        │
                     └──────────────┬────────────────┘
                                    │
            ┌───────────────────────┼───────────────────────┐
            │                       │                       │
            ▼                       ▼                       ▼
┌───────────────────────┐ ┌───────────────────┐ ┌───────────────────────┐
│ Justification (1.2)   │ │ Evidential (3.2)  │ │ Temporal Currency(2.1)│
└───────────┬───────────┘ └─────────┬─────────┘ └───────────┬───────────┘
            │                       │                       │
            └───────────────────────┼───────────────────────┘
                                    │
                                    ▼
                     ┌───────────────────────────────┐
                     │ Resolution & Boundary (4.1/2) │
                     └───────────────────────────────┘

```

* **Core Dependencies:**
1. **Epistemic Status (1.1)** is the root dependent for **Justification (1.2)** and **Evidential Direction (3.1)**.
2. **Absence of Evidence (3.2)** requires **Knowledge Status (1.1)** to evaluate open-world assumptions ($\neg \mathbf{K} \phi$).
3. **Resolution Status (4.1)** depends on detecting a **Contradiction** state in **Epistemic Status (1.1)**.



---

## 5. Formal Verification Criteria

To verify that a proposed KnowledgeOS kernel or projection $\pi$ preserves $\mathcal{R}_{\text{req}}$, it must pass the following test suite:

### 1. The Separation Test

For every pair of distinct values $(v_i, v_j)$ in any distinction $d \in \mathcal{R}_{\text{req}}$, construct two minimal test states $s_i, s_j \in S$.

* **Criterion:** $\pi(\mathcal{E}(s_i)) \neq \pi(\mathcal{E}(s_j))$. If equality holds, verification **FAILS** (Distinction Collapsed).

### 2. The Round-Trip Transformation Test

For any valid projection function $f: \mathcal{K} \to \mathcal{K}'$ and reconstruction function $g: \mathcal{K}' \to \mathcal{K}$:

* **Criterion:** $\forall d \in \mathcal{R}_{\text{req}}, \quad s \sim_d (g \circ f)(s)$.

---

## 6. Prioritization and Ratification Strategy

### Priority Tiers

* **Tier P1 (Core Invariants):** Epistemic Status (1.1), Justification Mode (1.2), Currency (2.1), Absence vs. Evidence of Absence (3.2). *Must be preserved by all core kernels and projections.*
* **Tier P2 (Required):** Allen Relations (2.2), Resolution Status (4.1), Scope Boundary (4.2), Intensional Identity (1.3). *Must be supported natively by the top-level KR language.*
* **Tier P3 (Extended Domain-Specific):** Deontic/Normative distinctions (`Can` vs `Should`), Fine-grained probability distributions.
# FDE Kernel Candidate: Test Vectors & Expected Outputs

**Document ID:** TV-RREQ-FDE-2026-V1

**Target Candidate Architecture:** First-Degree Entailment (FDE) / Belnap-Dunn 4-Valued Logic Kernel

**Evaluation Harness:** `SPEC-RREQ-TEST-2026-V1`

---

## 1. Candidate Architecture Model

An FDE kernel maps proposition states to the truth-value powerset $\mathcal{P}(\{T, F\})$, yielding four core logical values:

* **$\mathbf{N}$ (Neither / $\emptyset$):** Neither True nor False ($v(\phi) = \emptyset$)
* **$\mathbf{T}$ (Just True / $\{T\}$):** True only ($v(\phi) = \{T\}$)
* **$\mathbf{F}$ (Just False / $\{F\}$):** False only ($v(\phi) = \{F\}$)
* **$\mathbf{B}$ (Both / $\{T, F\}$):** Both True and False ($v(\phi) = \{T, F\}$)

To evaluate Tier P1 compliance, we test how a standard 4-valued FDE state space $\mathcal{S}_{\text{FDE}}$ models knowledge status, justification, currency, and evidential absence.

---

## 2. Test Vectors & Expected Evaluation Outcomes

### Test Vector 1: Knowledge Status (D-1.1)

#### Input Test Vector ($TV_{1.1}$)

* **State $s_1$ (Contradictory):**
```json
{
  "propositions": [
    { "id": "P_1", "evidence_positive": ["sensor_A"], "evidence_negative": ["sensor_B"] }
  ]
}

```


* **State $s_2$ (Unknown):**
```json
{
  "propositions": [
    { "id": "P_2", "evidence_positive": [], "evidence_negative": [] }
  ]
}

```


* **State $s_3$ (Underdetermined):**
```json
{
  "propositions": [
    { "id": "P_3", "rule": "P_3 :- Q_1 AND Q_2", "bindings": { "Q_1": "T", "Q_2": "N" } }
  ]
}

```



#### Expected Output Matrix

| Property / Query | FDE Internal Value | Target KnowledgeStatus | Vector Evaluation |
| --- | --- | --- | --- |
| `queryStatus("P_1")` | $\mathbf{B} = \{T, F\}$ | `Contradictory` | **PASS** |
| `queryStatus("P_2")` | $\mathbf{N} = \emptyset$ | `Unknown` | **PASS** |
| `queryStatus("P_3")` | $\mathbf{N} = \emptyset$ | `Underdetermined` | **FAIL (Collapsed)** |
| `isExploded()` | `false` | `false` | **PASS** |

#### Diagnostic Analysis

* **Result:** **PARTIAL FAIL**
* **Root Cause:** Standard 4-valued FDE maps both unassessed propositions ($P_2$) and rule evaluations with missing antecedents ($P_3$) to the same logical value $\mathbf{N}$ ($\emptyset$). FDE fails to separate `Unknown` from `Underdetermined` without an explicit operational/metadata wrapper.

---

### Test Vector 2: Justification Mode (D-1.2)

#### Input Test Vector ($TV_{1.2}$)

* **State $s_1$ (Inferred Lineage):**
```json
{
  "propositions": [
    { "id": "A", "val": "{T}", "mode": "Evidenced" },
    { "id": "B", "val": "{T}", "derivation": "A -> B" }
  ]
}

```


* **State $s_2$ (Assumed Default):**
```json
{
  "propositions": [
    { "id": "B", "val": "{T}", "derivation": "Axiom/Default" }
  ]
}

```



#### Expected Output Matrix

| Query | Bare FDE Value | Annotated FDE Value | Expected Output | Status |
| --- | --- | --- | --- | --- |
| `queryJustification("B")` in $s_1$ | $\{T\}$ | $\langle \{T\}, \text{DerivationTree}(A \to B) \rangle$ | `Inferred` | **FAIL (Bare) / PASS (Annotated)** |
| `queryJustification("B")` in $s_2$ | $\{T\}$ | $\langle \{T\}, \text{Default} \rangle$ | `Assumed` | **FAIL (Bare) / PASS (Annotated)** |
| `stateHash(s_1) != stateHash(s_2)` | `false` (Bare) | `true` (Annotated) | `true` | **FAIL (Pure Logic)** |

#### Diagnostic Analysis

* **Result:** **FAIL (Pure FDE Logic)**
* **Root Cause:** Truth valuations alone in FDE contain no structural lineage. $v(B) = \{T\}$ in both states. A pure FDE kernel collapses `Inferred` and `Assumed` unless extended into a **Justification-Annotated Bilattice** or **Labeled Deductive System**.

---

### Test Vector 3: Currency & Validity State (D-2.1)

#### Input Test Vector ($TV_{2.1}$)

* **State $s_1$ (Stale - Mutated Dependency):**
```json
{
  "propositions": [
    { "id": "X", "val": "{T}", "depends_on": ["Y"], "last_computed": "2026-09-02T10:00:00Z" },
    { "id": "Y", "val": "{F}", "last_updated": "2026-09-02T11:00:00Z" }
  ]
}

```


* **State $s_2$ (Expired - TTL Exceeded):**
```json
{
  "propositions": [
    { "id": "Z", "val": "{T}", "valid_until": "2026-09-02T12:00:00Z", "current_time": "2026-09-02T13:00:00Z" }
  ]
}

```



#### Expected Output Matrix

| Query | Pure FDE Value | Currency State Target | Evaluation |
| --- | --- | --- | --- |
| `queryCurrency("X")` | $\{T\}$ | `Stale` | **FAIL (Collapses to Current)** |
| `queryCurrency("Z")` | $\{T\}$ | `Expired` | **FAIL (Collapses to Current)** |
| `s1.stateHash() != s2.stateHash()` | Dependent on metadata | `true` | **CONDITIONAL** |

#### Diagnostic Analysis

* **Result:** **FAIL**
* **Root Cause:** Temporal mechanics are orthogonal to static FDE truth values. Standard FDE does not natively track state staleness or temporal validity without an integrated frame time projection.

---

### Test Vector 4: Absence of Evidence vs. Evidence of Absence (D-3.2)

#### Input Test Vector ($TV_{3.2}$)

* **State $s_1$ (Absence of Evidence):**
```json
{
  "entity": "Target_Alpha",
  "observations": []
}

```


* **State $s_2$ (Evidence of Absence):**
```json
{
  "entity": "Target_Alpha",
  "observations": [
    { "sensor": "Radar_01", "result": "Clear / Negative", "target": "Target_Alpha" }
  ]
}

```



#### Expected Output Matrix

| Query | FDE Semantic Valuation | Target State Interpretation | Evaluation |
| --- | --- | --- | --- |
| `s1: queryStatus("Present(Target_Alpha)")` | $\mathbf{N} = \emptyset$ | `Unknown` ($\neg \mathbf{K} \phi$) | **PASS** |
| `s2: queryStatus("Present(Target_Alpha)")` | $\mathbf{F} = \{F\}$ | `KnownFalse` ($\mathbf{K} \neg \phi$) | **PASS** |
| `stateHash(s1) != stateHash(s2)` | `true` ($\emptyset \neq \{F\}$) | `true` | **PASS** |

#### Diagnostic Analysis

* **Result:** **PASS**
* **Root Cause:** FDE naturally distinguishes non-observation ($\mathbf{N} = \emptyset$) from explicit negative observation ($\mathbf{F} = \{F\}$). This is one of FDE's primary structural strengths over classical logic.

---

## 3. Compliance Summary & Kernel Recommendation

```
              ┌──────────────────────────────────────────────────┐
              │          FDE Kernel P1 Verification              │
              └────────────────────────┬─────────────────────────┘
                                       │
     ┌──────────────────┬──────────────┴───────┬──────────────────┐
     ▼                  ▼                      ▼                  ▼
┌─────────┐        ┌─────────┐            ┌─────────┐        ┌─────────┐
│ D-1.1   │        │ D-1.2   │            │ D-2.1   │        │ D-3.2   │
│ Knowledge│       │ Justif. │            │ Currency│        │ Absence │
└────┬────┘        └────┬────┘            └────┬────┘        └────┬────┘
     │                  │                      │                  │
  PARTIAL             FAIL                   FAIL               PASS
 (Collapse           (Requires               (Requires         (Native
 Unknown vs          Lineage)               Temporal)          Support)
 Underdetermined)

```

### Overall Suite Result: `FAIL (Unaugmented FDE)`

### Key Takeaway for KnowledgeOS Architecture

Pure 4-valued First-Degree Entailment (FDE) is **necessary but insufficient** as a standalone KnowledgeOS kernel:

1. **Strengths:** Perfectly handles `Contradictory` ($\mathbf{B}$) and preserves the distinction between `Absence of Evidence` ($\mathbf{N}$) and `Evidence of Absence` ($\mathbf{F}$).
2. **Deficiencies:** Collapses `Underdetermined` into `Unknown` ($\mathbf{N}$), fails to carry justification lineage, and lacks temporal validity tracking.
3. **Required Architectural Enhancement:** To qualify as an $\mathcal{R}_{\text{req}}$-compliant kernel, FDE must be wrapped in a **Annotated/Graded Labeled Frame Space** that pairs FDE truth values with explicit justification graphs and temporal validity intervals.