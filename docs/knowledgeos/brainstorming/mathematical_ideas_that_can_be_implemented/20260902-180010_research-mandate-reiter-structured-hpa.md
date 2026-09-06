# RESEARCH MANDATE: REITER / KNOWLEDGEOS IMPLEMENTATION DERIVATION

**Date:** 2026-09-02
**Status:** `[RESEARCH]` — Formal Derivation from Reiter (2001)
**Authority:** HPA Supervisory
**Purpose:** Analyze Reiter's *Knowledge in Action* as an independent formal-methods source and determine what can be legitimately derived for KnowledgeOS implementation.

---

## Executive Summary

Reiter's situation calculus provides **formal machinery** for exactly the parts of KnowledgeOS that have remained unresolved:
- **δ** — state transition
- **Frame Problem** — persistence and what does NOT change
- **Regression** — backward verification
- **Progression** — forward state update
- **Sensing** — knowledge-producing actions
- **Golog** — composition of actions

**The critical distinction:** Reiter gives us a **candidate formal foundation** for δ, but he does not automatically define KnowledgeOS's δ. The derivation must be controlled and evidence-based.

---

## Part 1: The Primary Research Question

### 1.1 Core Question

> Which parts of Reiter's situation calculus provide a formally defensible implementation model for KnowledgeOS, and which parts do not transfer?

### 1.2 Key Constructs to Analyze

| Reiter Construct | KnowledgeOS Candidate |
|------------------|----------------------|
| Situation | History / State |
| State | \(K_t\) |
| Action | Event / Operation / Command |
| Poss | Precondition |
| Fluent | State Component |
| \(do(a,s)\) | \(\delta(K_t, e_t)\) |
| Successor State Axiom | Transition Semantics |
| Frame Problem | Boundary / Persistence |
| Regression | Verification / Query |
| Progression | Forward Simulation |
| Sensing | Observation / Knowledge Acquisition |
| Accessibility | Epistemic State |
| Golog | Composition / Workflow |
| Reactive Golog | Event-Triggered Operations |

---

## Part 2: The Situation ≠ State Distinction

### 2.1 Source Statement

> "Situations are histories, not states. Two different action histories can produce the same fluent state."

### 2.2 KnowledgeOS Translation — Candidate Model

```
History H
    │
    ▼
Situation S
    │
    ▼
State Projection K
```

### 2.3 Test Propositions

| Proposition | Status | Evidence |
|-------------|--------|----------|
| \( \text{Situation} = K_t \) | `[TEST]` | History vs. state distinction |
| \( \text{History} \subset \text{Situation} \) | `[TEST]` | Foundational axioms |
| \( \text{State} \subset \text{Knowledge} \) | `[TEST]` | Epistemic vs. physical |
| \( K_t \neq \text{Truth} \) | `[ESTABLISHED]` | Corpus invariant |

### 2.4 Falsification Test

> **Question:** Can two different histories produce the same \(K_t\)?

If yes: Situation ≠ Knowledge State is established.

If no: The distinction may not be relevant to KnowledgeOS.

---

## Part 3: The δ Transition Model

### 3.1 Reiter's Successor State Axiom

**Relational Fluent:**
\[
F(\vec{x}, do(a, s)) \equiv \gamma_F^+(\vec{x}, a, s) \lor (F(\vec{x}, s) \land \neg \gamma_F^-(\vec{x}, a, s))
\]

**Functional Fluent:**
\[
f(\vec{x}, do(a, s)) = y \equiv \gamma_f(\vec{x}, y, a, s) \lor (f(\vec{x}, s) = y \land \neg (\exists y')\gamma_f(\vec{x}, y', a, s))
\]

### 3.2 KnowledgeOS Candidate

\[
F_{t+1} = \gamma_F^+(a, K_t) \lor (F_t \land \neg \gamma_F^-(a, K_t))
\]

### 3.3 The Correspondence Question

| Dimension | Reiter | KnowledgeOS Candidate | Status |
|-----------|--------|----------------------|--------|
| Action \(a\) | Primitive action | Event / Operation | `[OPEN]` |
| Situation \(s\) | History | \(K_t\) | `[TEST]` |
| Fluent \(F\) | State property | State component | `[PROP]` |
| \(\gamma_F^+\) | Positive effect | What the operation adds | `[PROP]` |
| \(\gamma_F^-\) | Negative effect | What the operation removes | `[PROP]` |
| Persistence | What does NOT change | Boundary | `[PROP]` |

### 3.4 What δ Must Handle

| Requirement | Status |
|-------------|--------|
| Precondition evaluation | `[OPEN]` |
| Positive effects | `[PROP]` |
| Negative effects | `[PROP]` |
| Persistence | `[PROP]` |
| Authority requirements | `[OPEN]` |
| Evidence requirements | `[OPEN]` |
| Provenance tracking | `[OPEN]` |
| Rejection | `[OPEN]` |
| Partiality | `[OPEN]` |

---

## Part 4: The Frame Problem — Boundary/Persistence

### 4.1 Reiter's Insight

> "Frame axioms specify the action invariants of the domain, i.e., those fluents unaffected by the performance of an action."

### 4.2 KnowledgeOS Candidate

Instead of specifying every state transition as:

```
new_state = entire_state_recalculation(...)
```

Model an operation as:

```
Operation
 ├── Precondition
 ├── Positive Effects (what becomes true)
 ├── Negative Effects (what becomes false)
 └── Persistence (what stays the same)
```

### 4.3 Operation Registry Candidate

```
Operation
 ├── identity
 ├── precondition
 ├── positive_effects
 ├── negative_effects
 ├── persistence_policy
 ├── authority_requirement
 ├── evidence_requirement
 ├── provenance_effect
 └── resulting_state
```

**Status:** `[PROP]` — Candidate architecture, not yet ratified.

---

## Part 5: Regression — Verification Mechanism

### 5.1 Reiter's Regression

> "Instead of executing an entire action sequence forward, a query about a future state can be transformed backward toward the initial state."

### 5.2 KnowledgeOS Candidate

```
Question: "Could K₇ satisfy P?"

Instead of:
K₀ → K₁ → K₂ → ... → K₇ → P

Reason backward:
P @ K₇ → condition @ K₆ → ... → condition @ K₀
```

### 5.3 Use Cases for KnowledgeOS

| Use Case | Status |
|----------|--------|
| Policy verification | `[PROP]` |
| State verification | `[PROP]` |
| Invariant checking | `[PROP]` |
| Operation admissibility | `[PROP]` |
| Provenance-aware reasoning | `[OPEN]` |

---

## Part 6: Progression — Forward State Update

### 6.1 Reiter's Progression

> "Progression is a forward operation: given an initial database and an action, compute the new initial database."

### 6.2 KnowledgeOS Candidate

```
K_t + operation → K_{t+1}
```

### 6.3 Known Limitations

| Limitation | Status |
|------------|--------|
| Progression is not always first-order definable | `[ESTABLISHED]` |
| Finite progression is second-order definable | `[ESTABLISHED]` |
| STRIPS is a restricted form of progression | `[ESTABLISHED]` |

### 6.4 Comparison with KnowledgeOS

| Dimension | Progression | KnowledgeOS Need |
|-----------|-------------|------------------|
| Forward evolution | Yes | Yes |
| Non-monotonic support | Limited | Required |
| Retraction support | Limited | Required |
| Rejection support | Not addressed | Required |
| Observation ingestion | Not addressed | Required |

---

## Part 7: Sensing and Observation

### 7.1 Reiter's Knowledge-Producing Actions

> "A sensing action changes the agent's accessibility relation / knowledge state rather than necessarily changing the physical world."

### 7.2 KnowledgeOS Mapping

```
World
    ↓
Observation
    ↓
Evidence
    ↓
Qualification
    ↓
Assertion
    ↓
Knowledge State
```

### 7.3 Research Questions

| Question | Status |
|----------|--------|
| Is Observation an action? | `[OPEN]` |
| Is Observation a fluent? | `[OPEN]` |
| Is sensing an action whose effect is on knowledge? | `[PROP]` |
| Is Qualification a precondition? | `[OPEN]` |
| Is Qualification an operation? | `[OPEN]` |
| Is Qualification an epistemic boundary? | `[OPEN]` |

**Status:** Reiter provides vocabulary but does **not** close these questions.

---

## Part 8: Golog — Composition

### 8.1 Reiter's Golog Constructs

| Construct | Definition | KnowledgeOS Candidate |
|-----------|------------|----------------------|
| Sequence | \(Do(\delta_1;\delta_2, s, s')\) | Operation composition |
| Test | \(Do(\phi?, s, s')\) | Guard/condition |
| Choice | \(Do(\delta_1|\delta_2, s, s')\) | Nondeterministic selection |
| Iteration | \(Do(\delta^*, s, s')\) | Loops |
| Procedure | \(Do(P(\vec{t}), s, s')\) | Named operation |

### 8.2 KnowledgeOS Candidate

```
Operation Composition:
    sequence: A ; B
    choice: A | B
    iteration: while φ do A
    test: φ?
    procedure: named operation
```

**Status:** `[PROP]` — Candidate formal semantics for composition.

---

## Part 9: Reactive Golog — Interrupts

### 9.1 Reiter's Interrupt Model

**Rule:**
\[
\varphi \rightarrow \alpha
\]

**Compiles to:**
\[
(\pi \vec{x})[\varphi?; \alpha]
\]

### 9.2 KnowledgeOS Application

```
Interrupt
 ├── condition
 ├── action
 └── priority
```

**Status:** `[PROP]` — Candidate for event-triggered operations.

---

## Part 10: Equality and Identity Audit

### 10.1 Reiter's Distinction

> "Situations are histories, not states."

### 10.2 KnowledgeOS Relations

| Relation | Meaning | Reiter Support |
|----------|---------|----------------|
| \(=\) | Identity | Situations: unique names axioms |
| \(\equiv\) | Semantic equivalence | Not directly addressed |
| \(\approx\) | Observational equivalence | Possible via fluent equality |
| \(\cong_\lambda\) | Provenance-sensitive equivalence | Not addressed |

### 10.3 Test Propositions

| Proposition | Status |
|-------------|--------|
| \(K_1 = K_2\) requires history equality? | `[TEST]` |
| \(K_1 \equiv K_2\) requires fluent equality? | `[TEST]` |
| \(K_1 \approx K_2\) requires observable equality? | `[TEST]` |
| \(K_1 \cong_\lambda K_2\) requires provenance equality? | `[OPEN]` |

**Status:** Reiter does **not** resolve equality. It provides an independent lens for the equality programme.

---

## Part 11: Negative Tests

### 11.1 Falsification Propositions

| Proposition | Status | Evidence |
|-------------|--------|----------|
| P1: Situation = KnowledgeOS state | `[TEST]` | History ≠ state |
| P2: Action = KnowledgeOS event | `[TEST]` | Operation vs. primitive |
| P3: Reiter's δ completely defines KnowledgeOS δ | `[TEST]` | Missing authority, evidence |
| P4: Reiter solves Qualification | `[TEST]` | Not addressed |
| P5: Reiter resolves KnowledgeOS equality | `[TEST]` | Not addressed |
| P6: Reiter makes \(K_t\) history-complete | `[TEST]` | Not claimed |
| P7: Reiter makes provenance unnecessary | `[TEST]` | Not addressed |
| P8: Reiter's knowledge operator = KnowledgeOS Knowledge | `[TEST]` | Different semantics |
| P9: Golog is directly usable as KnowledgeOS workflow | `[TEST]` | Composition only |
| P10: Progression is sufficient for KnowledgeOS evolution | `[TEST]` | Non-monotonic issues |

---

## Part 12: Implementation Candidates

### 12.1 Operation Registry Candidate

```
Operation
 ├── identity
 ├── precondition
 ├── positive_effects
 ├── negative_effects
 ├── persistence_policy
 ├── authority_requirement
 ├── evidence_requirement
 ├── provenance_effect
 └── resulting_state
```

**Status:** `[PROP]` — Candidate, not ratified.

### 12.2 δ Implementation Candidate

\[
\delta(K_t, e_t) = \text{Apply}(\text{Effects}(e_t), \text{Persist}(K_t, e_t))
\]

**Status:** `[PROP]` — Candidate, not ratified.

### 12.3 Verification Pipeline

```
Query @ K_t
    │
    ▼
Regression
    │
    ▼
Initial Condition
    │
    ▼
Verify against Corpus
```

**Status:** `[PROP]` — Candidate, not ratified.

---

## Part 13: Gap Update

### 13.1 What Reiter Advances

| TODO | Impact |
|------|--------|
| **δ** | Formal transition framework: successor-state axioms |
| **Boundary** | Frame problem: persistence and non-effects |
| **Composition** | Golog: sequence, choice, iteration, procedures |
| **Observation/Sensing** | Knowledge-producing actions: effects on epistemic state |
| **Regression** | Backward verification mechanism |
| **Progression** | Forward state update mechanism |
| **Operation Registry** | Action + Preconditions + Effects + Persistence |

### 13.2 What Remains OPEN

| TODO | Status | Reason |
|------|--------|--------|
| **Qualification** | `[OPEN]` | Not addressed by Reiter |
| **Equality/Identity** | `[OPEN]` | Situations ≠ states; not resolved |
| **Provenance** | `[OPEN]` | Not addressed |
| **Authority** | `[OPEN]` | Not addressed |
| **Lifecycle** | `[OPEN]` | Non-monotonicity hinted, not formalized |
| **Contr** | `[OPEN]` | Knowledge of contradictions, not definition |
| **\(\mathcal{R}_{req}\)** | `[OPEN]` | Not addressed |

---

## Part 14: Governance Impact

### 14.1 What Can Be Frozen

| Item | Action |
|------|--------|
| Successor-state transition model | `[PROP]` — Candidate |
| Operation registry structure | `[PROP]` — Candidate |
| Regression as verification | `[PROP]` — Candidate |
| Progression as forward update | `[PROP]` — Candidate |

### 14.2 What Cannot Be Frozen

| Item | Reason |
|------|--------|
| δ = Reiter's successor-state axiom | Authority/evidence missing |
| Situation = Knowledge State | History ≠ state |
| Qualification = Precondition | Not addressed |
| Observation = Sensing | Epistemic vs. physical |

### 14.3 Status Summary

| Element | Status |
|---------|--------|
| Situation ≠ State | `[PROP]` — Needs testing |
| δ as successor-state construction | `[PROP]` — Candidate |
| Frame problem as Boundary | `[PROP]` — Candidate |
| Regression as verification | `[PROP]` — Candidate |
| Sensing as knowledge-producing | `[PROP]` — Candidate |
| Golog as composition | `[PROP]` — Candidate |
| Operation Registry | `[PROP]` — Candidate |

---

## Part 15: Final Deliverables

### 15.1 Research Artifacts

```
research/REFINED-STEP-291.md
├── 01-source-formal-model.md
├── 02-knowledgeos-crosswalk.md
├── 03-situation-state-history.md
├── 04-delta-transition-audit.md
├── 05-frame-problem-audit.md
├── 06-operation-registry-audit.md
├── 07-observation-sensing-qualify.md
├── 08-regression-progression.md
├── 09-equality-identity-audit.md
├── 10-composition-reactivity.md
├── 11-negative-tests.md
├── 12-implementation-candidates.md
├── 13-gap-update.md
└── 14-governance-impact.md
```

### 15.2 Final Verdict

**What Reiter establishes:**
- Successor-state axioms as transition semantics
- Frame problem as persistence/non-effects
- Regression as verification mechanism
- Progression as forward update
- Sensing as knowledge-producing actions
- Golog as composition of actions

**What KnowledgeOS independently establishes:**
- History ≠ state distinction
- Authority, evidence, provenance requirements
- Non-monotonic lifecycle

**What remains unresolved:**
- Qualification
- Equality/Identity
- Provenance
- Authority
- Lifecycle
- Contr
- \(\mathcal{R}_{req}\)

---

**HPA Supervisory Research Mandate**
**Date: 2026-09-02**
**Status: `[RESEARCH]` — Derivation mandate issued**
**Action: Execute research sequence and produce artifacts**

---

*END OF RESEARCH MANDATE*