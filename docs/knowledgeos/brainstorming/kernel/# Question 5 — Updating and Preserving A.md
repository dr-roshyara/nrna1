# Question 5 — Updating and Preserving Assertions

## A Formal Definition

We now have the complete knowledge atom: Proposition → Assertion → Epistemic State. The final piece is defining how KnowledgeOS maintains this knowledge over time through updates and preservation. This is essential for temporal reasoning, provenance, and the epistemic lifecycle.

---

## 1. The Core Problem

### What Does It Mean to Update an Assertion?

> **Updating an assertion means changing its content or epistemic state in response to new evidence, without losing the history of what was previously known.**

### What Does It Mean to Preserve an Assertion?

> **Preserving an assertion means maintaining its provenance, history, and temporal validity so that KnowledgeOS can trace how knowledge evolved over time.**

### The Key Insight

$$
\boxed{
\text{Update} \neq \text{Overwrite}
}
$$

$$
\boxed{
\text{Update} = \text{Create New Version} + \text{Preserve History}
}
$$

---

## 2. The Assertion Lifecycle

### 2.1 The Lifecycle States

```text
Proposition
    ↓
Assertion Created
    ↓
Assertion Evaluated
    ↓
Assertion Accepted
    ↓
Assertion Updated (with new evidence)
    ↓
Assertion Challenged
    ↓
Assertion Revised
    ↓
Assertion Retired / Rejected
```

### 2.2 The Lifecycle Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    ASSERTION LIFECYCLE                      │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                    CREATED                            │   │
│  │  (Proposition → Assertion)                           │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   EVALUATED                           │   │
│  │  (Evidence applied, Epistemic State assigned)        │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                    ACCEPTED                           │   │
│  │  (Epistemically Accepted)                            │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                    UPDATED                            │   │
│  │  (New evidence → New version)                        │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   CHALLENGED                          │   │
│  │  (Questioned, re-evaluated)                          │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                    REVISED                            │   │
│  │  (Changed in response to challenge)                  │   │
│  └────────────────────┬─────────────────────────────────┘   │
│                       │                                      │
│                       ▼                                      │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              RETIRED / REJECTED                       │   │
│  │  (No longer accepted)                                │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Updating Assertions

### 3.1 The Update Operation

$$
\boxed{
\text{Update}(A, E, \tau) \rightarrow A_{\text{new}}
}
$$

Where:
- $A$ = The current assertion.
- $E$ = New evidence.
- $\tau$ = Current time.
- $A_{\text{new}}$ = The updated assertion (new version).

### 3.2 The Update Process

```text
1. Receive new evidence (E)
2. Apply evidence to current assertion (A)
3. Determine if epistemic state changes
4. If yes, create new version (A_new)
5. Preserve old version (A_old)
6. Update provenance chain
7. Update temporal validity
8. Return new assertion
```

### 3.3 Update Triggers

| Trigger | Description | Action |
| :--- | :--- | :--- |
| **New Evidence** | New information supports or contradicts. | Re-evaluate epistemic state. |
| **Time Passage** | Temporal validity expires. | Mark as stale or expired. |
| **Context Change** | Context changes applicability. | Re-evaluate context validity. |
| **Challenge Resolution** | Challenge results in change. | Revise assertion. |

### 3.4 Update Types

| Type | Description | Example |
| :--- | :--- | :--- |
| **State Update** | Only epistemic state changes. | "Version 3.69 is now confirmed." |
| **Value Update** | The value changes. | "Version was 3.69, now 3.70." |
| **Dimension Update** | New dimension discovered. | "Added 'Security_Status' dimension." |
| **Entity Update** | Entity changes. | "Nexus → Nexus-Enterprise." |
| **Retirement** | Assertion no longer accepted. | "This version is deprecated." |

---

## 4. Preserving Assertions

### 4.1 The Preservation Principle

> **Preservation means that KnowledgeOS never loses the history of an assertion. Every change is recorded, and old versions remain accessible.**

### 4.2 The Preservation Structure

$$
\boxed{
\text{Assertion History} = \{A_{\tau_1}, A_{\tau_2}, A_{\tau_3}, \ldots\}
}
$$

Where each $A_{\tau_i}$ is a version of the assertion at time $\tau_i$.

### 4.3 The Provenance Chain

Each assertion version includes:

| Component | Description |
| :--- | :--- |
| **Version ID** | Unique identifier for this version. |
| **Timestamp** | When this version was created. |
| **Previous Version** | Reference to the previous version. |
| **Change Reason** | Why this version was created. |
| **Evidence** | Evidence that led to this version. |
| **Actor** | Who made the change. |

### 4.4 The Provenance Structure

$$
\boxed{
\text{Provenance} = (\text{Version ID}, \tau, \text{Previous}, \text{Reason}, \text{Evidence}, \text{Actor})
}
$$

---

## 5. The Assertion Versioning Model

### 5.1 The Versioned Assertion

$$
\boxed{
A_{\text{versioned}} = (A, \text{Version ID}, \tau, \text{Provenance})
}
$$

### 5.2 Version History Example

**Version 1:** "Nexus version is 3.69." (Observed, Assumed)

```
A_1 = (Nexus, Version, 3.69, Σ_Assumed, τ_1, P_1)
```

**Version 2:** "Nexus version is 3.69." (Observed, Confirmed)

```
A_2 = (Nexus, Version, 3.69, Σ_Confirmed, τ_2, P_2)
Where: Previous = A_1, Reason = "Additional evidence confirms."
```

**Version 3:** "Nexus version is 3.70." (Observed, Confirmed)

```
A_3 = (Nexus, Version, 3.70, Σ_Confirmed, τ_3, P_3)
Where: Previous = A_2, Reason = "New version discovered."
```

### 5.3 History Query

KnowledgeOS can query:

- "What was the version on Monday?" → A_1
- "When was it updated?" → τ_2, τ_3
- "Why was it updated?" → Reason field
- "Who updated it?" → Actor field

---

## 6. The Update Functions

### 6.1 State Update

$$
\boxed{
\text{StateUpdate}(A, \Sigma_{\text{new}}, \tau) \rightarrow A_{\text{new}}
}
$$

Where:
- $A_{\text{new}} = (E, D, V, \Sigma_{\text{new}}, \text{Evidence}, \tau, \text{Provenance})$
- The provenance chain includes $A$ as previous.

### 6.2 Value Update

$$
\boxed{
\text{ValueUpdate}(A, V_{\text{new}}, \tau) \rightarrow A_{\text{new}}
}
$$

Where:
- $A_{\text{new}} = (E, D, V_{\text{new}}, \Sigma, \text{Evidence}, \tau, \text{Provenance})$

### 6.3 Retirement

$$
\boxed{
\text{Retire}(A, \tau) \rightarrow A_{\text{retired}}
}
$$

Where:
- $A_{\text{retired}} = (E, D, V, \text{Retired}, \text{Evidence}, \tau, \text{Provenance})$

---

## 7. The Lenses and Updates

### 7.1 Zero Lens and Updates

Zero detects when an assertion needs updating:

| Detection | Action |
| :--- | :--- |
| **Stale Knowledge** | Validity = Stale → Needs update. |
| **Weak Evidence** | Support = Weak → Needs stronger evidence. |
| **Unresolved Conflict** | Conflict = Active → Needs resolution. |
| **Unresolved** | Resolution = Open → Needs investigation. |

### 7.2 Lord Lens and Updates

Lord suggests potential updates:

| Suggestion | Action |
| :--- | :--- |
| **New Dimension** | Add a dimension to the assertion. |
| **Alternative Value** | Consider a different value. |
| **Alternative Interpretation** | Reinterpret the evidence. |

### 7.3 Sārathi and Updates

Sārathi guides the update process:

| Guidance | Action |
| :--- | :--- |
| **Evidence Collection** | Gather evidence to update. |
| **Re-evaluation** | Re-evaluate with new evidence. |
| **Resolution** | Resolve conflicts. |

---

## 8. The Arjuna Example: Updates Over Time

### 8.1 Initial Assertion

**Proposition:** "Bhīṣma is Arjuna's grandfather."

**Version 1:** (Confirmed, based on family testimony)

```
A_1 = (Bhīṣma, Relationship_To_Arjuna, Grandfather, Σ_Confirmed, E_family, τ_1, P_1)
```

### 8.2 Update 1: New Evidence

**New Evidence:** "Bhīṣma is on the opposing side."

**Update:** New assertion created.

```
A_2 = (Bhīṣma, Side_In_Conflict, Opposing, Σ_Observed, E_opposing, τ_2, P_2)
```

**Conflict Detected:** A_1 and A_2 are in conflict.

**Update:** Both assertions updated with conflict status.

```
A_1' = (Bhīṣma, Relationship_To_Arjuna, Grandfather, Σ_Confirmed, E_family, τ_2, P_1, Conflict = Active)
```

```
A_2' = (Bhīṣma, Side_In_Conflict, Opposing, Σ_Observed, E_opposing, τ_2, P_2, Conflict = Active)
```

### 8.3 Update 2: Resolution

**Resolution:** The conflict is normative, not logical.

**Update:** New assertion created.

```
A_3 = (Arjuna, Moral_Conflict, True, Σ_Inferred, E_moral, τ_3, P_3)
```

**Conflict Resolved:**

```
A_1'' = (Bhīṣma, Relationship_To_Arjuna, Grandfather, Σ_Confirmed, E_family, τ_3, P_1, Conflict = Resolved)
```

```
A_2'' = (Bhīṣma, Side_In_Conflict, Opposing, Σ_Observed, E_opposing, τ_3, P_2, Conflict = Resolved)
```

### 8.4 The History

| Version | Time | Value | State | Conflict |
| :--- | :--- | :--- | :--- | :--- |
| A_1 | τ_1 | Grandfather | Confirmed | None |
| A_1' | τ_2 | Grandfather | Confirmed | Active |
| A_1'' | τ_3 | Grandfather | Confirmed | Resolved |

---

## 9. Formal Mathematical Model

### 9.1 The Update Function

$$
\boxed{
\text{Update}(A, \Delta, \tau) \rightarrow A_{\text{new}}
}
$$

Where $\Delta$ is the change (evidence, new value, new state, etc.).

### 9.2 The Versioned Assertion

$$
\boxed{
A_{\text{versioned}} = (A, \text{id}, \tau, \text{previous}, \text{reason}, \text{evidence}, \text{actor})
}
$$

### 9.3 The History

$$
\boxed{
\text{History}(A) = \{A_{\text{versioned}}^{(1)}, A_{\text{versioned}}^{(2)}, \ldots\}
}
$$

### 9.4 The Preservation Invariant

$$
\boxed{
\forall A : \text{History}(A) \neq \emptyset
}
$$

$$
\boxed{
\forall A, \tau_i : A_{\tau_i} \in \text{History}(A)
}
$$

---

## 10. Summary

### 10.1 Update Defined

> **Update is the process of changing an assertion in response to new evidence, creating a new version while preserving the old version's history.**

$$
\boxed{
\text{Update}(A, E, \tau) \rightarrow A_{\text{new}}
}
$$

### 10.2 Preservation Defined

> **Preservation is the process of maintaining the complete history of an assertion, including all versions, evidence, and provenance.**

$$
\boxed{
\text{Preserve}(A) = \text{History}(A)
}
$$

### 10.3 The Invariants

$$
\boxed{
\text{Update} \neq \text{Overwrite}
}
$$

$$
\boxed{
\text{Every assertion has a provenance chain}
}
$$

$$
\boxed{
\text{Every update creates a new version}
}
$$

$$
\boxed{
\text{Old versions are preserved and queryable}
}
$$

---

## 11. The Complete KnowledgeOS Model

We have now defined the complete KnowledgeOS knowledge model:

1. **Proposition** — Semantic content. $(E, D, V)$
2. **Assertion** — Epistemic commitment. $(P, \Sigma, E, \tau, \Pi)$
3. **Epistemic State** — Multidimensional state. $(A, S, R, V, C)$
4. **Evidence** — Support for assertions. $(S, T, C, R, \rho, K, \tau)$
5. **Comparison** — Relationships between assertions.
6. **Challenge** — Epistemic validity determination.
7. **Update** — Creating new versions.
8. **Preservation** — Maintaining history.

---

## 12. Next Steps

We have now completed the core knowledge model. The remaining questions are:

1. **How do we form a coherent Knowledge State from a set of Assertions?**
2. **How do we resolve conflicts between Assertions?**
3. **How do we measure the quality of a Knowledge State?**

These will be addressed in the next phase, culminating in a complete formal specification of KnowledgeOS.
#
