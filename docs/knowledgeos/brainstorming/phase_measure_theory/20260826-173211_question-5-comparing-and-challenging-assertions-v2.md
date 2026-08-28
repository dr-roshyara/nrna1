# Question 5 — Comparing and Challenging Assertions

## A Formal Definition

This is the operational heart of KnowledgeOS. With a formal model of Assertions and Evidence, we can now define how KnowledgeOS compares, challenges, and updates knowledge. These operations are essential for the Zero Lens (detecting gaps and conflicts), the Lord Lens (suggesting alternatives), and the Sārathi (guiding investigation).

---

## 1. The Core Problem

### What Does It Mean to Compare Assertions?

$$
\boxed{
\text{Compare}(A_1, A_2) \rightarrow \text{Relationship}
}
$$

Where the relationship can be:

- **Identical** — Same entity, dimension, value, and epistemic status.
- **Equivalent** — Same entity, dimension, value, but different epistemic status.
- **Consistent** — Same entity, dimension, but values are compatible.
- **Contradictory** — Same entity, dimension, but values are incompatible.
- **Unrelated** — Different entity or dimension.

### What Does It Mean to Challenge an Assertion?

$$
\boxed{
\text{Challenge}(A) \rightarrow \{\text{Evidence}, \text{Counterarguments}, \text{Unresolved Issues}\}
}
$$

Challenging an assertion means subjecting it to scrutiny:

- Is the evidence sufficient?
- Is the source reliable?
- Is there contradictory evidence?
- Is the temporal validity current?
- Is the context appropriate?

---

## 2. Comparing Assertions

### 2.1 The Comparison Space

Two assertions can be compared along multiple dimensions:

| Aspect | Comparison | Result |
| :--- | :--- | :--- |
| **Entity** | Same or different? | Same / Different |
| **Dimension** | Same or different? | Same / Different |
| **Value** | Same, compatible, or incompatible? | Same / Compatible / Incompatible |
| **Epistemic Status** | Same or different? | Same / Different |
| **Evidence** | Same, stronger, weaker, or conflicting? | Same / Stronger / Weaker / Conflicting |
| **Temporal Validity** | Same, newer, older? | Same / Newer / Older |
| **Provenance** | Same or different source? | Same / Different |

### 2.2 The Comparison Function

$$
\boxed{
\text{Compare}(A_1, A_2) \rightarrow \text{ComparisonResult}
}
$$

Where:
- **ComparisonResult** = $\{\text{Identical}, \text{Equivalent}, \text{Consistent}, \text{Contradictory}, \text{Unrelated}\}$

### 2.3 Comparison Rules

| Condition | Result |
| :--- | :--- |
| $E_1 = E_2$, $D_1 = D_2$, $V_1 = V_2$, $\Sigma_1 = \Sigma_2$ | **Identical** |
| $E_1 = E_2$, $D_1 = D_2$, $V_1 = V_2$, $\Sigma_1 \neq \Sigma_2$ | **Equivalent** |
| $E_1 = E_2$, $D_1 = D_2$, $V_1$ and $V_2$ are compatible | **Consistent** |
| $E_1 = E_2$, $D_1 = D_2$, $V_1$ and $V_2$ are incompatible | **Contradictory** |
| $E_1 \neq E_2$ or $D_1 \neq D_2$ | **Unrelated** |

### 2.4 Value Compatibility

Values can be:

- **Identical:** $V_1 = V_2$
- **Compatible:** $V_1 \subseteq V_2$ or $V_2 \subseteq V_1$ or $V_1$ and $V_2$ are in the same category
- **Incompatible:** $V_1 \neq V_2$ and $V_1$ and $V_2$ are mutually exclusive

**Example:**
- Version 3.69 and Version 3.70 → Compatible (they are different versions)
- "True" and "False" → Incompatible
- "Grandfather" and "Teacher" → Compatible (both are relationships to Arjuna)

---

## 3. Challenging Assertions

### 3.1 What is a Challenge?

> **A challenge is a systematic examination of an assertion to determine its epistemic validity.**

$$
\boxed{
\text{Challenge}(A) \rightarrow \text{ChallengeResult}
}
$$

Where:
- **ChallengeResult** = $\{\text{Supported}, \text{Weakened}, \text{Contradicted}, \text{Unresolved}, \text{Requires Investigation}\}$

### 3.2 The Challenge Process

```text
Select Assertion
        ↓
Examine Evidence
        ↓
Check Source Reliability
        ↓
Check Temporal Validity
        ↓
Check for Contradictions
        ↓
Check Contextual Applicability
        ↓
Determine Challenge Result
```

### 3.3 Challenge Criteria

| Criterion | Question | Result If Failed |
| :--- | :--- | :--- |
| **Sufficient Evidence** | Is there enough evidence? | Weakened |
| **Reliable Source** | Is the source trustworthy? | Weakened |
| **Current Temporal Validity** | Is the assertion current? | Weakened |
| **No Contradictions** | Are there conflicts? | Contradicted |
| **Appropriate Context** | Does the context apply? | Weakened |

### 3.4 The Challenge Function

$$
\boxed{
\text{Challenge}(A) = \text{Aggregate}(\text{ChallengeEvidence}(A), \text{ChallengeSource}(A), \text{ChallengeTime}(A), \text{ChallengeContext}(A))
}
$$

Where each sub-function returns a score between -1 and 1.

### 3.5 Challenge Result

| Aggregate Score | Result |
| :--- | :--- |
| $> 0.8$ | Supported |
| $0.5 - 0.8$ | Weakened |
| $0.2 - 0.5$ | Requires Investigation |
| $-0.2 - 0.2$ | Unresolved |
| $< -0.2$ | Contradicted |

---

## 4. Zero and Challenges

### 4.1 Zero as a Challenger

Zero challenges assertions to detect:

| Finding | Description |
| :--- | :--- |
| **Missing Evidence** | The assertion lacks supporting evidence. |
| **Weak Evidence** | Evidence is insufficient to support the assertion. |
| **Unreliable Source** | The source is not trustworthy. |
| **Stale Knowledge** | Temporal validity has expired. |
| **Contradictory Evidence** | There is conflicting evidence. |
| **Contextual Mismatch** | The assertion may not apply in this context. |

### 4.2 Zero Challenge Function

$$
\boxed{
\text{ZeroChallenge}(A) = \{\text{Findings}\}
}
$$

Where:
- **Findings** = $\{\text{Missing Evidence}, \text{Weak Evidence}, \text{Unreliable Source}, \text{Stale Knowledge}, \text{Contradictory Evidence}, \text{Contextual Mismatch}\}$

---

## 5. Lord and Challenges

### 5.1 Lord as an Alternative Generator

Lord challenges assertions by suggesting alternatives:

| Suggestion | Description |
| :--- | :--- |
| **Alternative Dimensions** | There may be dimensions not yet considered. |
| **Alternative Values** | The value may be different from what is asserted. |
| **Alternative Interpretations** | The evidence may be interpreted differently. |
| **Alternative Sources** | There may be other sources of evidence. |

### 5.2 Lord Challenge Function

$$
\boxed{
\text{LordChallenge}(A) = \{\text{Candidates}\}
}
$$

Where:
- **Candidates** = $\{\text{Alternative Dimensions}, \text{Alternative Values}, \text{Alternative Interpretations}, \text{Alternative Sources}\}$

---

## 6. Sārathi and Challenges

### 6.1 Sārathi as a Guide

Sārathi guides challenges by determining:

| Guidance | Description |
| :--- | :--- |
| **Prioritization** | Which challenges are most important? |
| **Investigation Sequence** | What is the best order to investigate? |
| **Evidence Collection** | What evidence is needed? |
| **Resolution Strategy** | How to resolve conflicts? |

### 6.2 Sārathi Challenge Function

$$
\boxed{
\text{SārathiChallenge}(A) = \{\text{Next Steps}\}
}
$$

Where:
- **Next Steps** = $\{\text{Priorities}, \text{Investigation Order}, \text{Evidence Needed}, \text{Resolution Strategy}\}$

---

## 7. The Arjuna Example: Comparing and Challenging

### 7.1 Assertions to Compare

**A1:** "Bhīṣma is Arjuna's grandfather." (Confirmed, based on family testimony)

$$
A_1 = (\text{Bhīṣma}, \text{Relationship\_To\_Arjuna}, \text{Grandfather}, \Sigma_C, E_1, \tau_1, \Pi_1)
$$

**A2:** "Bhīṣma is Arjuna's teacher." (Confirmed, based on historical record)

$$
A_2 = (\text{Bhīṣma}, \text{Relationship\_To\_Arjuna}, \text{Teacher}, \Sigma_C, E_2, \tau_2, \Pi_2)
$$

### 7.2 Comparison

$$
\text{Compare}(A_1, A_2) \rightarrow \text{Consistent}
$$

**Reason:** Grandfather and Teacher are compatible values on the dimension `Relationship_To_Arjuna`.

### 7.3 Challenge

**A3:** "Bhīṣma is Arjuna's enemy." (Assumed, based on side in conflict)

$$
A_3 = (\text{Bhīṣma}, \text{Relationship\_To\_Arjuna}, \text{Enemy}, \Sigma_A, E_3, \tau_3, \Pi_3)
$$

**Challenge Process:**

| Criterion | Assessment |
| :--- | :--- |
| **Sufficient Evidence** | Partially: Bhīṣma is on the opposing side, but this alone doesn't make him an enemy. |
| **Reliable Source** | Yes: Direct observation of his side. |
| **Current Temporal Validity** | Yes: Currently on the opposing side. |
| **No Contradictions** | Contradiction: Grandfather + Teacher vs. Enemy. |
| **Appropriate Context** | Yes: Battlefield context. |

**Challenge Result:**

- **Contradicted** — The assertion is contradicted by the relationship assertions.
- **Requires Investigation** — Need to understand the nature of "enemy" in this context.

### 7.4 Resolution

**New Assertion:** "Bhīṣma is Arjuna's grandfather and teacher, but is on the opposing side."

$$
A_4 = (\text{Bhīṣma}, \text{Relationship\_Status}, \{\text{Grandfather}, \text{Teacher}, \text{Opposing}\}, \Sigma_C, E_4, \tau_4, \Pi_4)
$$

**Zero detects:** The moral dimension is unresolved.

**Lord suggests:** "What about `Duty`? What about `Family_Obligation`?"

**Sārathi guides:** "Investigate the moral dimension next."

---

## 8. Formal Mathematical Model

### 8.1 Comparison Function

$$
\boxed{
\text{Compare}(A_1, A_2) = \text{Result}
}
$$

Where:

$$
\text{Result} = \begin{cases}
\text{Identical} & \text{if } E_1 = E_2, D_1 = D_2, V_1 = V_2, \Sigma_1 = \Sigma_2 \\
\text{Equivalent} & \text{if } E_1 = E_2, D_1 = D_2, V_1 = V_2, \Sigma_1 \neq \Sigma_2 \\
\text{Consistent} & \text{if } E_1 = E_2, D_1 = D_2, \text{Compatible}(V_1, V_2) \\
\text{Contradictory} & \text{if } E_1 = E_2, D_1 = D_2, \text{Incompatible}(V_1, V_2) \\
\text{Unrelated} & \text{otherwise}
\end{cases}
$$

### 8.2 Challenge Function

$$
\boxed{
\text{Challenge}(A) = \text{Aggregate}(C_E, C_S, C_T, C_C)
}
$$

Where:

$$
C_E = \text{ChallengeEvidence}(A) \in [-1, 1]
$$

$$
C_S = \text{ChallengeSource}(A) \in [-1, 1]
$$

$$
C_T = \text{ChallengeTime}(A) \in [-1, 1]
$$

$$
C_C = \text{ChallengeContext}(A) \in [-1, 1]
$$

### 8.3 Challenge Result

$$
\boxed{
\text{ChallengeResult} = \begin{cases}
\text{Supported} & \text{if } \text{Aggregate} > 0.8 \\
\text{Weakened} & \text{if } 0.5 < \text{Aggregate} \leq 0.8 \\
\text{Requires Investigation} & \text{if } 0.2 < \text{Aggregate} \leq 0.5 \\
\text{Unresolved} & \text{if } -0.2 \leq \text{Aggregate} \leq 0.2 \\
\text{Contradicted} & \text{if } \text{Aggregate} < -0.2
\end{cases}
}
$$

---

## 9. Summary

### 9.1 Comparison Defined

> **Comparison is the process of determining the relationship between two assertions based on their entity, dimension, value, and epistemic status.**

$$
\boxed{
\text{Compare}(A_1, A_2) \rightarrow \{\text{Identical}, \text{Equivalent}, \text{Consistent}, \text{Contradictory}, \text{Unrelated}\}
$$

### 9.2 Challenge Defined

> **Challenge is the systematic examination of an assertion to determine its epistemic validity, based on evidence, source, temporal validity, and context.**

$$
\boxed{
\text{Challenge}(A) \rightarrow \{\text{Supported}, \text{Weakened}, \text{Contradicted}, \text{Unresolved}, \text{Requires Investigation}\}
$$

### 9.3 The Invariants

$$
\boxed{
\text{Comparison} \neq \text{Challenge}
}
$$

$$
\boxed{
\text{Comparison determines relationships; Challenge determines validity.}
}
$$

$$
\boxed{
\text{Zero challenges to detect gaps; Lord challenges to suggest alternatives; Sārathi challenges to guide investigation.}
}
$$

---

## 10. Next Steps

We have now formalized:

1. **Proposition** — Semantic content.
2. **Assertion** — Epistemic commitment.
3. **Epistemically Accepted** — Justified knowledge.
4. **Evidence** — Support for assertions.
5. **Comparison** — Determining relationships between assertions.
6. **Challenge** — Determining the epistemic validity of assertions.

The remaining questions are:

1. **How do we update Assertions when new evidence emerges?**
2. **How do we preserve the history of Assertions?**
3. **How do we form a coherent Knowledge State from a set of Assertions?**

These will be addressed in the next phase.
#
# Question 4A — What exactly is an Epistemic State?

## A Formal Definition

This is a critical refinement. The analysis of Question 4 has revealed that a single scalar epistemic state is insufficient. We need a multidimensional model that captures the different aspects of how knowledge is held. This question is mathematically fundamental and will determine the architecture of everything that follows.

---

## 1. The Core Problem

### The Inadequacy of a Single Epistemic State

The previous model assumed:

$$
\Sigma \in \{\text{Unknown}, \text{Hypothesized}, \text{Assumed}, \text{Inferred}, \text{Observed}, \text{Confirmed}, \text{Conflicting}, \text{Unresolved}, \text{Rejected}, \text{ABSENT}\}
$$

This is insufficient because:

1. **Observed** describes acquisition mode, not evaluation.
2. **Confirmed** describes evaluation, not acquisition.
3. **Conflicting** describes a relationship, not a state.
4. **Unresolved** describes a status, not a state.

These are different **dimensions** of epistemic state, not positions on a single axis.

### The Key Insight

$$
\boxed{
\text{Epistemic State is multidimensional}
}
$$

$$
\boxed{
\text{Acquisition} \neq \text{Support} \neq \text{Resolution} \neq \text{Validity} \neq \text{Conflict}
}
$$

---

## 2. The Multidimensional Epistemic State

### 2.1 The Dimensions

I propose that an Epistemic State consists of **five independent dimensions**:

$$
\boxed{
\Sigma = (\text{Acquisition}, \text{Support}, \text{Resolution}, \text{Validity}, \text{Conflict})
}
$$

### 2.2 Acquisition Mode

**Definition:** How the assertion was obtained.

$$
\boxed{
\text{Acquisition} \in \{\text{Observed}, \text{Reported}, \text{Inferred}, \text{Calculated}, \text{Assumed}, \text{Hypothesized}, \text{Unknown}\}
}
$$

| Mode | Definition | Example |
| :--- | :--- | :--- |
| **Observed** | Direct sensory or sensor data. | "I see Bhīṣma." |
| **Reported** | From another observer. | "Sañjaya reports..." |
| **Inferred** | Derived from other assertions. | "Nexus uses Postgres, so it needs Postgres." |
| **Calculated** | Computed from data. | "CPU usage is 45%." |
| **Assumed** | Taken as true without verification. | "Certificate is valid." |
| **Hypothesized** | Proposed for investigation. | "Bhīṣma may be on the opposing side." |
| **Unknown** | No information. | "Version is unknown." |

### 2.3 Support Level

**Definition:** How strongly the evidence supports the assertion.

$$
\boxed{
\text{Support} \in \{\text{None}, \text{Weak}, \text{Moderate}, \text{Strong}, \text{Very Strong}\}
}
$$

| Level | Definition | Formalization |
| :--- | :--- | :--- |
| **None** | No evidence. | $S = 0$ |
| **Weak** | Minimal supporting evidence. | $0 < S < 0.3$ |
| **Moderate** | Some supporting evidence. | $0.3 \leq S < 0.6$ |
| **Strong** | Significant supporting evidence. | $0.6 \leq S < 0.9$ |
| **Very Strong** | Overwhelming evidence. | $S \geq 0.9$ |

### 2.4 Resolution Status

**Definition:** Whether the assertion has been resolved or is still open.

$$
\boxed{
\text{Resolution} \in \{\text{Open}, \text{In Progress}, \text{Resolved}, \text{Unresolvable}\}
}
$$

| Status | Definition | Example |
| :--- | :--- | :--- |
| **Open** | Not yet investigated. | "We haven't checked the version." |
| **In Progress** | Currently being investigated. | "We are checking the version." |
| **Resolved** | Investigation complete. | "Version is 3.69." |
| **Unresolvable** | Cannot be resolved with available means. | "We cannot access that system." |

### 2.5 Temporal Validity

**Definition:** Whether the assertion is current or stale.

$$
\boxed{
\text{Validity} \in \{\text{Current}, \text{Stale}, \text{Expired}, \text{Unknown}\}
}
$$

| Status | Definition | Example |
| :--- | :--- | :--- |
| **Current** | Valid at present. | "The current version is 3.69." |
| **Stale** | May be outdated. | "Last checked a month ago." |
| **Expired** | Known to be outdated. | "This is no longer true." |
| **Unknown** | Validity unknown. | "We don't know when this was last checked." |

### 2.6 Conflict Status

**Definition:** Whether the assertion conflicts with other assertions.

$$
\boxed{
\text{Conflict} \in \{\text{None}, \text{Potential}, \text{Active}, \text{Resolved}\}
}
$$

| Status | Definition | Example |
| :--- | :--- | :--- |
| **None** | No known conflicts. | "All sources agree." |
| **Potential** | Possible conflict detected. | "These may conflict." |
| **Active** | Conflicting evidence exists. | "Source A says X, Source B says Y." |
| **Resolved** | Conflict has been resolved. | "We resolved the discrepancy." |

---

## 3. The Complete Epistemic State

### 3.1 The Formal Definition

$$
\boxed{
\Sigma = (\text{Acquisition}, \text{Support}, \text{Resolution}, \text{Validity}, \text{Conflict})
}
$$

### 3.2 Examples

**Example 1: Direct Observation**

$$
\Sigma = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{None})
$$

**Example 2: Reported but Unverified**

$$
\Sigma = (\text{Reported}, \text{Weak}, \text{Open}, \text{Current}, \text{None})
$$

**Example 3: Inferred with Conflict**

$$
\Sigma = (\text{Inferred}, \text{Moderate}, \text{Resolved}, \text{Current}, \text{Active})
$$

**Example 4: Assumed and Stale**

$$
\Sigma = (\text{Assumed}, \text{None}, \text{Open}, \text{Stale}, \text{None})
$$

### 3.3 The State Space

The complete epistemic state space is:

$$
\boxed{
\mathcal S = \text{Acquisition} \times \text{Support} \times \text{Resolution} \times \text{Validity} \times \text{Conflict}
}
$$

This gives us $7 \times 5 \times 4 \times 4 \times 4 = 2240$ possible states.

---

## 4. Evidence and Epistemic State Transition

### 4.1 The Transition Function

Evidence can change one or more dimensions of the epistemic state:

$$
\boxed{
\Sigma_{\text{new}} = \text{Transition}(\Sigma_{\text{old}}, E)
}
$$

### 4.2 Transition Rules by Dimension

| Dimension | Evidence Effect | New State |
| :--- | :--- | :--- |
| **Acquisition** | Direct observation → Observed | Acquisition = Observed |
| **Support** | Strong evidence → Strong Support | Support = Strong |
| **Resolution** | Evidence resolves → Resolved | Resolution = Resolved |
| **Validity** | New evidence → Current | Validity = Current |
| **Conflict** | Contradictory evidence → Active | Conflict = Active |

### 4.3 Example Transitions

**Initial State:**

$$
\Sigma_0 = (\text{Assumed}, \text{None}, \text{Open}, \text{Current}, \text{None})
$$

**Evidence 1:** Direct observation.

$$
\Sigma_1 = \text{Transition}(\Sigma_0, E_{\text{Observe}}) = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{None})
$$

**Evidence 2:** Contradictory report.

$$
\Sigma_2 = \text{Transition}(\Sigma_1, E_{\text{Contradict}}) = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Active})
$$

**Evidence 3:** Resolution of conflict.

$$
\Sigma_3 = \text{Transition}(\Sigma_2, E_{\text{Resolve}}) = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Resolved})
$$

---

## 5. The Lenses and the Epistemic State

### 5.1 Zero Lens

Zero examines each dimension for gaps:

| Dimension | Zero Questions |
| :--- | :--- |
| **Acquisition** | "How was this acquired? Is the method reliable?" |
| **Support** | "What is the support level? Is it sufficient?" |
| **Resolution** | "Is this resolved? What is unresolved?" |
| **Validity** | "Is this current? Is it stale?" |
| **Conflict** | "Are there conflicts? Are they resolved?" |

$$
\boxed{
\text{Zero}(\Sigma) \rightarrow \{\text{Gaps in Acquisition, Support, Resolution, Validity, Conflict}\}
}
$$

### 5.2 Lord Lens

Lord suggests alternatives for each dimension:

| Dimension | Lord Questions |
| :--- | :--- |
| **Acquisition** | "Could this be acquired differently?" |
| **Support** | "Is there stronger evidence available?" |
| **Resolution** | "Could this be resolved another way?" |
| **Validity** | "Could this be updated?" |
| **Conflict** | "What if the conflict is actually a different dimension?" |

### 5.3 Sārathi

Sārathi guides which dimension to address next:

| Dimension | Guidance |
| :--- | :--- |
| **Acquisition** | "Seek direct observation." |
| **Support** | "Find additional evidence." |
| **Resolution** | "Investigate the unresolved aspect." |
| **Validity** | "Recheck the assertion." |
| **Conflict** | "Resolve the conflict." |

---

## 6. The Arjuna Example: Epistemic State Evolution

### 6.1 Initial State

**Proposition:** "Bhīṣma is Arjuna's grandfather."

$$
\Sigma_0 = (\text{Unknown}, \text{None}, \text{Open}, \text{Unknown}, \text{None})
$$

### 6.2 After Observation

Arjuna sees Bhīṣma and recognizes him.

$$
\Sigma_1 = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{None})
$$

### 6.3 After Contradictory Evidence

Bhīṣma is on the opposing side.

**New Proposition:** "Bhīṣma is Arjuna's enemy."

$$
\Sigma_2 = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{None})
$$

**Conflict Detected:**

- "Bhīṣma is Arjuna's grandfather." ($\Sigma_1$)
- "Bhīṣma is Arjuna's enemy." ($\Sigma_2$)

**Conflict State Updated:**

$$
\Sigma_1' = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Active})
$$

$$
\Sigma_2' = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Active})
$$

### 6.4 After Moral Investigation

Arjuna realizes the conflict is normative, not logical.

**New Proposition:** "Arjuna has a moral conflict."

$$
\Sigma_3 = (\text{Inferred}, \text{Strong}, \text{Resolved}, \text{Current}, \text{None})
$$

**Conflict Resolved:**

$$
\Sigma_1'' = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Resolved})
$$

$$
\Sigma_2'' = (\text{Observed}, \text{Strong}, \text{Resolved}, \text{Current}, \text{Resolved})
$$

---

## 7. Formal Mathematical Model

### 7.1 The Epistemic State Vector

$$
\boxed{
\Sigma = (A, S, R, V, C)
}
$$

Where:
- $A \in \{\text{Observed}, \text{Reported}, \text{Inferred}, \text{Calculated}, \text{Assumed}, \text{Hypothesized}, \text{Unknown}\}$
- $S \in \{\text{None}, \text{Weak}, \text{Moderate}, \text{Strong}, \text{Very Strong}\}$
- $R \in \{\text{Open}, \text{In Progress}, \text{Resolved}, \text{Unresolvable}\}$
- $V \in \{\text{Current}, \text{Stale}, \text{Expired}, \text{Unknown}\}$
- $C \in \{\text{None}, \text{Potential}, \text{Active}, \text{Resolved}\}$

### 7.2 The Transition Function

$$
\boxed{
\Sigma_{\text{new}} = T(\Sigma_{\text{old}}, E)
}
$$

Where $T$ is defined per dimension:

$$
A_{\text{new}} = T_A(A_{\text{old}}, E)
$$

$$
S_{\text{new}} = T_S(S_{\text{old}}, E)
$$

$$
R_{\text{new}} = T_R(R_{\text{old}}, E)
$$

$$
V_{\text{new}} = T_V(V_{\text{old}}, E)
$$

$$
C_{\text{new}} = T_C(C_{\text{old}}, E)
$$

### 7.3 The State Space Size

$$
|\mathcal S| = 7 \times 5 \times 4 \times 4 \times 4 = 2240
$$

---

## 8. Summary

### 8.1 The Core Insight

$$
\boxed{
\text{Epistemic State is multidimensional}
}
$$

### 8.2 The Five Dimensions

| Dimension | Definition | Values |
| :--- | :--- | :--- |
| **Acquisition** | How the assertion was obtained. | 7 values |
| **Support** | How strongly the evidence supports it. | 5 values |
| **Resolution** | Whether it is resolved. | 4 values |
| **Validity** | Whether it is current. | 4 values |
| **Conflict** | Whether it conflicts with others. | 4 values |

### 8.3 The Formal Definition

$$
\boxed{
\Sigma = (A, S, R, V, C)
}
$$

### 8.4 The Invariants

$$
\boxed{
\text{Acquisition} \neq \text{Support} \neq \text{Resolution} \neq \text{Validity} \neq \text{Conflict}
}
$$

$$
\boxed{
\text{Evidence can change one or more dimensions independently}
}
$$

$$
\boxed{
\text{The epistemic state space is multidimensional, not a single scalar}
}
$$

---

## 9. Next Steps

We have now formalized:

1. **Proposition** — Semantic content.
2. **Assertion** — Epistemic commitment.
3. **Epistemically Accepted** — Justified knowledge.
4. **Evidence** — Support for assertions.
5. **Comparison** — Determining relationships between assertions.
6. **Challenge** — Determining epistemic validity.
7. **Epistemic State** — A multidimensional model of how knowledge is held.

The next question is:

> **Question 5 — How do we update and preserve Assertions?**

This will define the operations for maintaining the knowledge state over time.