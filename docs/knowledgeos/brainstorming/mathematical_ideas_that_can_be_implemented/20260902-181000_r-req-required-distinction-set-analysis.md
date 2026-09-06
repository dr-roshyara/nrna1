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