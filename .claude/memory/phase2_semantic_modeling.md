---
name: Phase 2 Semantic Modeling - Descriptive vs Executable
description: Critical distinction between governance as constitutional fact ledger vs runtime decision engine; authority as historical evidence vs computed permission
type: feedback
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
## Core Distinction: Descriptive vs Executable Models

| Dimension | Executable Model | Descriptive Model (Phase 2) |
|-----------|------------------|---------------------------|
| **Purpose** | Performs decisions | Records legitimacy |
| **Time Orientation** | Current truth | Historical evidence |
| **Mutability** | Mutable state | Immutable ledger |
| **Authority** | Computed at runtime | Captured as snapshot |
| **Auditability** | Difficult (mutable) | Natural (append-only) |
| **Replay Capability** | Not supported | Built-in |

**Rule:** Phase 2 is descriptive. Never implement executable logic (policy engines, rule evaluators, dynamic authority computation).

---

## Authority Ontology: Snapshot vs Computation

### WRONG (Execution-Based)
```
"Who has authority NOW?" 
→ compute dynamically
→ evaluate policies
→ resolve at runtime
```

### CORRECT (Historical Evidence)
```
"Who had authority at THAT moment?"
→ snapshot captured at decision time
→ immutable record
→ legally defensible proof
```

**Rule:** Authority is captured as historical evidence, never computed as current truth.

---

## Ontology Collapse Prevention

The biggest risk was silent transformation:

```
constitutional legitimacy system
    ↓ (WRONG)
runtime authorization framework
```

Symptoms of collapse:
- Adding policy engines to GovernanceDecision
- Implementing rule evaluators in trace
- Computing permissions dynamically
- Making decisions mutable

**Prevention:** Keep layers separate. Phase 1 (structure) ↔ Phase 2 (legitimacy). Dependency direction: Phase 2 depends on Phase 1 identities, but Phase 1 does NOT depend on Phase 2 legitimacy.

---

## Event Semantics: Evidence vs Permission

### WRONG Event
```php
GovernanceDecisionApproved
// Meaning: "System authorized action"
// Implies: executable permission
// Encourages: policy engine
// Enables: collapse
```

### CORRECT Event
```php
GovernanceDecisionRecorded
// Meaning: "Governance truth was constitutionally recorded"
// Implies: historical ledger entry
// Encourages: auditability
// Protects: descriptive boundary
```

**Rule:** Events record facts, never grant permissions.

---

## Single Semantic Responsibility (ConstitutionalBasis Example)

### Semantic Inflation (WRONG)
```
ConstitutionalBasis
  - article (citation)
  - document (metadata)
  - version (lifecycle)
  - effective_date (temporal validity)
```
This answers: "What is the lifecycle of constitutional documents?"

### Semantic Purity (CORRECT)
```
ConstitutionalBasis
  - article (citation)
  - referenceText (optional context)
```
This answers: "Which constitutional article justified this decision?"

**Rule:** Each VO answers exactly one semantic question. If you add a second concern, you've inflated the boundary.

---

## What Phase 2 Owns vs Phase 1 Owns

### Phase 1: Operational Structure
- Committee lifecycle (create, suspend, restore, dissolve)
- Term boundaries (start, extend)
- Structural hierarchy (parent-child committees)
- Committee membership

### Phase 2: Constitutional Interpretation
- Legitimacy classification (LEGITIMATE, REVOKED, DISPUTED, UNAUTHORIZED)
- Authority delegation (captured as AuthorityChain snapshot)
- Constitutional grounding (via ConstitutionalBasis)
- Forensic record (via DecisionTrace)

**Rule:** Never implement legitimacy evaluation in Phase 1. Never implement structure in Phase 2.

---

## DDD Maturity Level Achieved

Moved from:
- **Syntax Level**: entities, aggregates, repositories, value objects
- **Pattern Level**: factories, events, domain services

To:
- **Semantic Level**: legitimacy ontology, authority provenance, governance evidence

This is where most DDD implementations stop. Advanced systems continue to:
- Semantic duplication detection
- Ontology drift prevention
- Dependency purity verification
- Governance semantics stability

**Insight:** Systems that reach semantic modeling are architecturally mature. Those that don't collapse into CRUD.

---

## Next Critical Checkpoint: GovernanceDecisionRecorded

The event must protect the boundary:

### Must Contain
- decision ID
- committee ID
- legitimacy type
- timestamp
- constitutional references
- authority snapshot

### Must NOT Contain
- executable policy logic
- runtime evaluators
- mutable permissions
- recalculation behavior

**Rule:** If the event description includes "system authorized" or "grant permission", you've crossed back into executable semantics.

---

## Architecture Review Phase Checklist

After STEP 5, before expanding further:

1. **Dependency Graph**: Verify Phase 2 depends on Phase 1, not vice versa
2. **Namespace Purity**: No Phase 1 files importing Phase 2 legitimacy logic
3. **Aggregate Boundary**: Each aggregate owns exactly one semantic concern
4. **Semantic Duplication**: No two VOs answer the same question
5. **Ontology Drift**: Authority stays as evidence, not authorization
6. **Event Naming**: Each event records a fact, not a permission
7. **Immutability Verification**: No mutable state in Phase 2 core domain

This review separates durable governance systems from future legacy systems.
