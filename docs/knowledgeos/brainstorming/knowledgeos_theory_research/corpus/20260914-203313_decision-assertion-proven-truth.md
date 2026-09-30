## Decision: Assertion ≠ Proven Truth

I agree with your recommendation. **An Assertion can legitimately hold an unresolved epistemic state** (e.g., `Unknown`, `Hypothesized`, `WeaklySupported`). This is a foundational design choice for KnowledgeOS.

---

## The Rationale

The alternative—requiring a minimum admission threshold before creating an Assertion—would force KnowledgeOS into a binary false dichotomy:

```
Known / Unknown
```

This would destroy several of our most important invariants:

1. **The Zero Lens** would lose its ability to detect gaps, because we would have no representation for `Unknown` or `Hypothesized`. Zero can only identify a gap if the gap is explicitly modeled.

2. **The Lord Lens** would lose its ability to generate candidate dimensions, because candidates would have no place to reside. A `Hypothesized` assertion is a candidate awaiting evaluation.

3. **Provenance** would be broken, because the chain from `Observation → Interpretation → Candidate → Assertion` would be severed. We would have to discard candidate information or store it separately, losing lineage.

4. **Auditability** would be damaged, because we would have no record of "what we considered but did not yet accept."

5. **The incomplete question problem** (Arjuna's "those") would be unsolvable, because we would have no way to represent an underdetermined proposition.

6. **The distinction between "Unknown" and "Absent"** would be lost. If we only store assertions that have passed a threshold, we cannot distinguish "we don't know" from "it doesn't exist."

7. **The learning process** would be broken, because knowledge often evolves from `Hypothesized` → `WeaklySupported` → `StronglySupported`. If we discard the intermediate states, we lose the epistemic trajectory.

---

## The Formal Definition

Therefore:

```
Assertion = {
    id: AssertionID,
    proposition: Proposition,
    epistemic_state: Sigma,  // Can be Unknown, Hypothesized, etc.
    evidence: List<EvidenceRef>,
    lineage: LineageRef,
    asserted_at: Timestamp,
    context: ContextRef,
    provenance: ProvenanceRef
}
```

And the key invariant is:

```
Assertion ≠ Proven Truth
```

An Assertion is a **knowledge claim with an explicit epistemic status**. The epistemic status tells us how the claim is held.

---

## The Epistemic State Space

The `Sigma` state space now includes:

| Status | Meaning | Example |
| :--- | :--- | :--- |
| **Unknown** | We know the dimension exists but do not know its value. | `Nexus.version = Unknown` |
| **Hypothesized** | A candidate value has been proposed but not evaluated. | `Nexus.version = Hypothesis(3.70)` |
| **Reported** | A source has reported a value, but it has not been verified. | `Nexus.version = 3.69 (Reported)` |
| **WeaklySupported** | Some evidence exists but is insufficient for confidence. | `Nexus.version = 3.69 (Weak)` |
| **ModeratelySupported** | Decent evidence exists. | `Nexus.version = 3.69 (Moderate)` |
| **StronglySupported** | Strong evidence exists. | `Nexus.version = 3.69 (Strong)` |
| **Confirmed** | Verified through multiple independent sources. | `Nexus.version = 3.69 (Confirmed)` |
| **Conflicting** | Multiple sources give different values. | `Nexus.version = {3.69, 3.70}` |
| **Stale** | Was once known but may no longer be current. | `Nexus.version = 3.69 (Stale)` |
| **Invalidated** | Was believed but has been disproven. | `Nexus.version = 3.69 (Invalidated)` |
| **Absent** | Determined not to exist. | `Nexus.version = Absent` |

---

## The Admission Boundary

The **Epistemic Admission Boundary** is the point at which a `CandidateAssertion` becomes an `Assertion`.

```
Admit: CandidateAssertion × Evidence × Rules × Context → Assertion
```

But crucially:

```
Admission does not require StronglySupported.
Admission does not require Confirmed.
Admission does not require Truth.
```

Admission means:

> "This proposition is now part of the Knowledge State, with an explicit epistemic status."

This is a much lower, more defensible boundary.

---

## The Eight Cases Revisited

### Case 1: Database Result
```
Candidate: Nexus.version = 3.69
Evidence: SQL query result (reliable)
Admission: Assertion(epistemic_state = StronglySupported)
```

### Case 2: Unstructured Document
```
Candidate: Nexus.version = 3.69
Evidence: Document contains the statement
Admission: Assertion(epistemic_state = WeaklySupported)
```

### Case 3: Internet Page
```
Candidate: Nexus.version = 3.69
Evidence: Unknown website
Admission: Assertion(epistemic_state = WeaklySupported) with caveats
```

### Case 4: Human Statement
```
Candidate: Nexus.version = 3.69
Evidence: Human said "I believe..."
Admission: Assertion(epistemic_state = Reported) with provenance
```

### Case 5: LLM Output
```
Candidate: Nexus.version = 3.69
Evidence: LLM generated the statement
Admission: Assertion(epistemic_state = Hypothesized) with provenance
```

### Case 6: Incomplete Question
```
Candidate: None (underdetermined)
Admission: No assertion created. Zero detects gap. Clarification requested.
```

### Case 7: Conflicting Sources
```
Assertion A: Nexus.version = 3.69 (StronglySupported)
Assertion B: Nexus.version = 3.70 (WeaklySupported)
Conflict: Detected. Both remain in Knowledge State.
```

### Case 8: Unknown Value
```
Candidate: Nexus.version = Unknown
Admission: Assertion(epistemic_state = Unknown)
```

---

## The Key Insight

The `Assertion` with an explicit epistemic state is the **single source of truth** for KnowledgeOS.

But "truth" here means:

```
Truth = "This is what we currently represent, with this degree of support, uncertainty, and validity."
```

It does **not** mean:

```
Truth = "This is factually correct in reality."
```

This is the formalization of:

```
Knowledge ≠ Reality
```

---

## The Complete Pipeline

```
Input
   ↓
Artifact
   ↓
SourceObservation  ← What was produced?
   ↓
SemanticInterpretation  ← What does it mean?
   ↓
CandidateAssertion  ← What proposition is suggested?
   ↓
EpistemicAssessment  ← How justified is it?
   ↓
Assertion  ← Knowledge State (with explicit epistemic status)
   ↓
Zero  ← Diagnose gaps, conflicts, inconsistencies
   ↓
Lord  ← Generate new candidates, syntheses
   ↓
Sārathi  ← Contextual guidance
```

---

## The Constitutional Invariants

1. **Assertion ≠ Proven Truth.** An assertion is a knowledge claim with an explicit epistemic status.

2. **Admission does not require verification.** A proposition can be admitted as `Hypothesized` or `WeaklySupported`.

3. **Unknown is a valid epistemic state.** It is not the absence of an assertion; it is a specific kind of assertion.

4. **Epistemic Status can evolve.** `Hypothesized` → `WeaklySupported` → `StronglySupported` → `Confirmed`.

5. **Multiple epistemic states can coexist.** `Conflicting` is a valid state.

6. **Provenance is preserved.** Every assertion traces back to its source(s).

---

## Summary

The **Epistemic Admission Boundary** is now computationally closed:

```
Admit: CandidateAssertion × Evidence × Rules × Context → Assertion
```

where `Assertion` has an explicit epistemic state that can be:

- `Unknown`
- `Hypothesized`
- `Reported`
- `WeaklySupported`
- `ModeratelySupported`
- `StronglySupported`
- `Confirmed`
- `Conflicting`
- `Stale`
- `Invalidated`
- `Absent`

This gives us:

1. **A complete epistemic spectrum** from `Unknown` to `Confirmed`.
2. **Provenance preservation** for every assertion.
3. **Support for ambiguity** and underdetermination.
4. **Support for the Zero Lens** (gaps are explicitly represented).
5. **Support for the Lord Lens** (candidates are explicitly represented).
6. **Support for learning** (knowledge evolves through epistemic states).
7. **Support for auditability** (we know when and how knowledge was acquired).

---

## Decision

**The Epistemic Admission Boundary is computationally closed.**

We can now proceed to **Computational Closure 3 — The Knowledge State**, where we define how all these pieces fit together into a coherent, governed, queryable whole.