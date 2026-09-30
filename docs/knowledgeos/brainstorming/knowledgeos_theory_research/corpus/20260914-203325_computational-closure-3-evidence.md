# Computational Closure 3 — Evidence

We have reached the next difficult boundary:

$$
\boxed{Evidence}
$$

We need to answer:

> What exactly counts as evidence, how is evidence related to an assertion, and how can KnowledgeOS computationally evaluate evidence without reducing epistemic reasoning to an arbitrary confidence score?

This is probably one of the most important mathematical components of the entire system.

We should test it against:

1. direct measurement
2. database output
3. document statement
4. human testimony
5. expert opinion
6. LLM output
7. derived calculation
8. multiple independent sources
9. circular sources
10. stale sources
11. contradictory sources

If we can close Evidence, then the epistemic assessment function:

$$
\boxed{
Assess(A, E, \Pi, C) \rightarrow \Sigma_A
}
$$

becomes computationally realizable rather than merely philosophical.

---

## 1. First: What Evidence Is Not

Before defining evidence, we must establish what it is not:

$$
Evidence \neq Proposition
$$

$$
Evidence \neq Assertion
$$

$$
Evidence \neq Source
$$

$$
Evidence \neq Confidence
$$

$$
Evidence \neq Truth
$$

A proposition is the content. Evidence is what supports (or contradicts) that content.

An assertion is a proposition with an epistemic state. Evidence is an input to determining that epistemic state.

A source is an origin. Evidence is a structured relationship between a source's output and a proposition.

Confidence is an assessment. Evidence is the basis for that assessment.

Truth is a property of reality. Evidence is information about reality.

---

## 2. The Formal Definition

I propose that Evidence is:

$$
\boxed{
E = (P, S, R, T, Q, \Pi, C)
}
$$

Where:

| Component | Meaning |
| :--- | :--- |
| **P** | The proposition to which this evidence relates |
| **S** | The source observation (or set of source observations) that produced this evidence |
| **R** | The relationship type (Supports, Contradicts, Contextualizes, Qualifies) |
| **T** | The temporal validity/currency |
| **Q** | The quality/reliability assessment of the evidence itself |
| **Π** | Provenance |
| **C** | Context |

This distinguishes evidence as a **relational object**, not a property of the proposition or the source.

---

## 3. Evidence Relationship Types

$$
R \in \{Supports, Contradicts, Contextualizes, Qualifies\}
$$

### Supports

The source observation provides direct or indirect support for the proposition.

Examples:
- DB query returns version 3.69 → supports `Nexus.version = 3.69`
- Document states "Nexus is running 3.69" → supports the same proposition

### Contradicts

The source observation provides information that conflicts with the proposition.

Examples:
- DB query returns version 3.70 → contradicts `Nexus.version = 3.69`
- Document states "Nexus was decommissioned" → contradicts `Nexus.isRunning = true`

### Contextualizes

The source observation provides additional context that changes the meaning or relevance of the proposition.

Examples:
- "This applies only to production" → contextualizes a general proposition
- "According to the 2025 security audit" → contextualizes a security claim

### Qualifies

The source observation modifies the scope, certainty, or conditions of the proposition.

Examples:
- "Nexus version 3.69 has a known vulnerability in the admin console" → qualifies a version assertion
- "If the firewall is misconfigured, then the service is exposed" → qualifies a security assertion

---

## 4. The Evidence Quality Model

This is where we avoid reducing everything to a single confidence score.

I propose:

$$
\boxed{
Q = (Reliability, Relevance, Currency, Independence, Completeness)
}
$$

### Reliability

How trustworthy is the source?

```
Reliability ∈ {Unknown, Low, Medium, High, VeryHigh}
```

This is a property of the source type and specific source instance.

For example:
- Calibrated sensor: High
- Official database: High
- Expert testimony: Medium (depending on expertise)
- Document from unknown source: Low
- LLM output: Low (unless verified)

### Relevance

How directly does this evidence speak to the proposition?

```
Relevance ∈ {Irrelevant, WeaklyRelevant, Relevant, DirectlyRelevant}
```

For example:
- A document stating "Nexus is running 3.69" is DirectlyRelevant to `Nexus.version = 3.69`
- A document stating "Nexus was upgraded last year" is WeaklyRelevant (it suggests a version but doesn't state it)
- A document about a different system is Irrelevant

### Currency

How current is the evidence?

```
Currency ∈ {Unknown, Stale, Current, Future, Historical}
```

For example:
- A monitoring system from 1 minute ago: Current
- A document from 2025: Stale (unless we know the system hasn't changed)
- A migration plan for next month: Future

### Independence

How independent is this evidence from other evidence?

```
Independence ∈ {Unknown, Dependent, PartiallyIndependent, Independent}
```

This is crucial for preventing circular reasoning and false confidence.

For example:
- Two LLMs that both trained on the same documentation are Dependent
- A database query and a separate monitoring system are Independent
- A human testimony and their own written document are Dependent

### Completeness

Does the evidence tell the whole story or only part of it?

```
Completeness ∈ {Unknown, Partial, Complete}
```

For example:
- A database query returns the version but not the installation date → Partial
- A full inventory report includes version, date, and configuration → Complete
- A document mentions Nexus but not its version → Partial

---

## 5. The Evidence Graph

Evidence forms a graph:

```
SourceObservation
       │
       ▼
  Evidence
       │
       ├── supports ────────► Assertion
       ├── contradicts ────► Assertion
       ├── contextualizes ─► Assertion
       └── qualifies ──────► Assertion
```

But more importantly, evidence can relate to evidence:

```
Evidence A (supports P)
       │
       ▼
Evidence B (contextualizes A)
```

For example:
- Evidence A: "Nexus is running 3.69" (from a database)
- Evidence B: "This database is a replica, not the primary" (from the configuration)

Evidence B contextualizes Evidence A, which should reduce its weight.

---

## 6. The Evidence Evaluation Function

Now we can define the evidence evaluation function:

$$
\boxed{
Evaluate(E, C) \rightarrow W
}
$$

Where `W` is a weight vector:

$$
W = (SupportStrength, ContradictionStrength, ContextualEffect, QualificationEffect)
$$

But crucially, these are not combined into a single scalar. They remain distinct.

The epistemic assessment function then takes all evidence and produces a summary epistemic state:

$$
\boxed{
\Sigma_A = Assess(\{E_1, E_2, \ldots, E_n\}, Rules, Context)
}
$$

This is a **policy function**, not a mathematical universal. Different domains can have different assessment rules.

---

## 7. The Eleven Test Cases

### Case 1: Direct Measurement

```
Source: Calibrated sensor reading: Nexus.version = 3.69.0
Evidence: {
    P: Nexus.version = 3.69.0,
    S: SensorObservation,
    R: Supports,
    T: Current,
    Q: { Reliability: VeryHigh, Relevance: DirectlyRelevant, Currency: Current, Independence: Independent, Completeness: Complete }
}
Assessment: {
    acquisition: Observed,
    support: VeryStrong,
    uncertainty: Low,
    validity: Current
}
```

**Verdict:** ✅ Clean.

---

### Case 2: Database Output

```
Source: SELECT version FROM nexus; → "3.69.0"
Evidence: {
    P: Nexus.version = 3.69.0,
    S: DBQuery,
    R: Supports,
    T: Current,
    Q: { Reliability: High, Relevance: DirectlyRelevant, Currency: Current, Independence: Independent, Completeness: Complete }
}
Assessment: {
    acquisition: Observed,
    support: Strong,
    uncertainty: Low,
    validity: Current
}
```

**Verdict:** ✅ Clean.

---

### Case 3: Document Statement

```
Source: Document contains "Nexus is running 3.69.0"
Evidence: {
    P: Nexus.version = 3.69.0,
    S: DocumentContains,
    R: Supports,
    T: Current (if recently written),
    Q: { Reliability: Medium, Relevance: DirectlyRelevant, Currency: Stale (if written in 2025), Independence: Independent, Completeness: Partial (document may be incomplete) }
}
Assessment: {
    acquisition: Observed,
    support: Moderate,
    uncertainty: Medium,
    validity: Stale
}
```

**Verdict:** ✅ Clean. Currency captures staleness.

---

### Case 4: Human Testimony

```
Source: Alice says "Nexus is running 3.69.0"
Evidence: {
    P: Nexus.version = 3.69.0,
    S: HumanStatement,
    R: Supports,
    T: Current,
    Q: { Reliability: Low (without verification), Relevance: DirectlyRelevant, Currency: Current, Independence: Unknown, Completeness: Unknown }
}
Assessment: {
    acquisition: Reported,
    support: Weak,
    uncertainty: High,
    validity: Current
}
```

**Verdict:** ✅ Clean.

---

### Case 5: Expert Opinion

```
Source: Dr. Smith (Nexus expert) says "Nexus is running 3.69.0"
Evidence: {
    P: Nexus.version = 3.69.0,
    S: ExpertStatement,
    R: Supports,
    T: Current,
    Q: { Reliability: Medium-High (expert but fallible), Relevance: DirectlyRelevant, Currency: Current, Independence: Unknown, Completeness: Unknown }
}
Assessment: {
    acquisition: Reported,
    support: Moderate,
    uncertainty: Medium,
    validity: Current
}
```

**Verdict:** ✅ Clean. Expert opinion is treated differently from lay testimony.

---

### Case 6: LLM Output

```
Source: GPT-4 says "Nexus is running 3.69.0"
Evidence: {
    P: Nexus.version = 3.69.0,
    S: LLMOutput,
    R: Supports,
    T: Current,
    Q: { Reliability: Low, Relevance: DirectlyRelevant, Currency: Unknown, Independence: Unknown (may have learned from the same sources), Completeness: Unknown }
}
Assessment: {
    acquisition: AI_Generated,
    support: Weak,
    uncertainty: VeryHigh,
    validity: Unknown
}
```

**Verdict:** ✅ Clean.

---

### Case 7: Derived Calculation

```
Source: Nexus version calculated from build date
Evidence: {
    P: Nexus.version = 3.69.0,
    S: DerivedCalculation,
    R: Supports,
    T: Current,
    Q: { Reliability: Medium (depends on assumptions), Relevance: IndirectlyRelevant, Currency: Current, Independence: PartiallyIndependent (depends on other assumptions), Completeness: Partial }
}
Assessment: {
    acquisition: Inferred,
    support: Moderate,
    uncertainty: Medium-High,
    validity: Current
}
```

**Verdict:** ✅ Clean.

---

### Case 8: Multiple Independent Sources

```
Source A: Database → 3.69.0
Source B: Monitoring → 3.69.0
Source C: Deployment script → 3.69.0

Evidence Set: Three independent sources
Assessment: {
    acquisition: Observed (aggregated),
    support: VeryStrong,
    uncertainty: Low,
    validity: Current
}
```

**Verdict:** ✅ Clean. Independence increases support.

---

### Case 9: Circular Sources

```
Source A: Document D1 says "Nexus is 3.69"
Source B: LLM trained on D1 says "Nexus is 3.69"
Source C: Human verified by reading D1 says "Nexus is 3.69"

Evidence Set: Three sources, but all trace to D1
Assessment: {
    acquisition: Reported,
    support: Moderate (single original source),
    uncertainty: Medium,
    validity: Current
}
```

**Verdict:** ✅ Clean. Independence detection prevents false confidence.

---

### Case 10: Stale Sources

```
Source: Document from 2025: "Nexus is 3.69"
Evidence: {
    P: Nexus.version = 3.69,
    S: DocumentContains,
    R: Supports,
    T: Stale (2025),
    Q: { Reliability: Medium, Relevance: DirectlyRelevant, Currency: Stale, Independence: Independent, Completeness: Partial }
}
Assessment: {
    acquisition: Observed,
    support: Weak (because stale),
    uncertainty: High,
    validity: Stale
}
```

**Verdict:** ✅ Clean.

---

### Case 11: Contradictory Sources

```
Evidence A: DB → 3.69.0 (supports)
Evidence B: LLM → 3.70.0 (supports, but low reliability)
Evidence C: Expert → 3.69.0 (supports, high reliability)

Assessment: {
    acquisition: Observed + Reported,
    support: Strong (2 of 3 sources),
    uncertainty: Medium (one source contradicts),
    validity: Current,
    conflict: Active
}
```

**Verdict:** ✅ Clean. Conflict is represented.

---

## 8. The Evidence Evaluation Summary

| Case | Type | Reliability | Relevance | Currency | Independence | Completeness | Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Direct Measurement | VeryHigh | Direct | Current | Independent | Complete | VeryStrong |
| 2 | Database Output | High | Direct | Current | Independent | Complete | Strong |
| 3 | Document Statement | Medium | Direct | Stale | Independent | Partial | Moderate |
| 4 | Human Testimony | Low | Direct | Current | Unknown | Unknown | Weak |
| 5 | Expert Opinion | Medium-High | Direct | Current | Unknown | Unknown | Moderate |
| 6 | LLM Output | Low | Direct | Unknown | Unknown | Unknown | VeryWeak |
| 7 | Derived Calculation | Medium | Indirect | Current | Partial | Partial | Moderate |
| 8 | Multiple Independent | VeryHigh | Direct | Current | Independent | Complete | VeryStrong |
| 9 | Circular Sources | Medium | Direct | Current | Dependent | Complete | Moderate |
| 10 | Stale Sources | Medium | Direct | Stale | Independent | Partial | Weak |
| 11 | Contradictory | Mixed | Direct | Current | Mixed | Mixed | Strong with Conflict |

---

## 9. The Evidence Lifecycle

I propose the following lifecycle:

```
SourceObservation
       │
       ▼
  Evidence Candidate
       │
       ▼
  Quality Assessment
       │
       ▼
  Evidence (with Q)
       │
       ▼
  Evidence Graph
       │
       ▼
  Epistemic Assessment
       │
       ▼
  Assertion
```

This gives us:

1. **Evidence Candidates** are generated from Source Observations.
2. **Quality Assessment** produces the `Q` tuple (Reliability, Relevance, Currency, Independence, Completeness).
3. **Evidence** is the final evidence object with its quality assessment.
4. **Evidence Graph** captures relationships between evidence and assertions.
5. **Epistemic Assessment** evaluates all evidence to produce `Σ_A`.

---

## 10. The Constitutional Invariants

1. **Evidence ≠ Proposition.** Evidence is about a proposition, not the proposition itself.

2. **Evidence ≠ Confidence.** Confidence is derived from evidence, not equivalent to it.

3. **Evidence ≠ Source.** A source produces observations; evidence is a structured relationship.

4. **Evidence Evaluation is Multi-Dimensional.** Reliability, Relevance, Currency, Independence, and Completeness are all distinct.

5. **Evidence Aggregation is Policy-Dependent.** The same evidence can be assessed differently in different contexts.

6. **Evidence Graph Captures Dependencies.** Evidence relationships are first-class objects.

7. **Circular Evidence is Detectable.** Independence tracking prevents false confidence.

8. **Staleness is Explicit.** Currency tracks whether evidence is current.

9. **Contradiction is Represented.** Conflicting evidence does not invalidate the system; it is a valid epistemic state.

10. **Evidence Quality is Not Arbitrary.** It is derived from source characteristics, not assigned arbitrarily.

---

## 11. Summary

The **Evidence boundary** is now computationally closed:

```
Evidence = (P, S, R, T, Q, Π, C)
```

Where:
- `P` = Proposition
- `S` = Source Observation(s)
- `R` = Relationship (Supports, Contradicts, Contextualizes, Qualifies)
- `T` = Temporal Validity
- `Q` = Quality tuple (Reliability, Relevance, Currency, Independence, Completeness)
- `Π` = Provenance
- `C` = Context

The **Evidence Quality model** is:
```
Q = (Reliability, Relevance, Currency, Independence, Completeness)
```

All eleven test cases survive.

---

## Decision

**The Evidence boundary is computationally closed.**

We can now proceed to the final closure: **Computational Closure 4 — Epistemic Assessment** where we define how evidence is aggregated to produce the full epistemic state `Σ_A`.