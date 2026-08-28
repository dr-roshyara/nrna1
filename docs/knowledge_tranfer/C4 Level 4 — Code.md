# C4 Level 4 — Code View

## The Short Answer

**No — Level 4 (Code View) is NOT supplied.**

C4 Level 4 (Code View) conventionally shows the **detailed design** of individual components — classes, interfaces, methods, and their relationships. This is essentially the **implementation design** — which is not yet defined.

---

## Why L4 Is Not Supplied

| Reason | Explanation |
|--------|-------------|
| **No implementation design exists** | Phase II.D (Implementation) has not started |
| **Tactical DDD not yet defined** | Aggregates, entities, repositories, services are not yet designed |
| **Technology not chosen** | PHP, Laravel, PostgreSQL, etc. — implementation decisions not yet made |
| **AD-1 forbids tactical decomposition** | AD-1's boundary explicitly forbids tactical DDD |
| **C4-1 records L4 as "not derivable"** | C4-1's KO-3: "A component decomposition is not derivable from AD-1" |

---

## The C4 Level 4 That Will Be Supplied (In Phase II.D)

When Phase II.D (Implementation) begins, the following will be defined:

### What Level 4 Will Show (Future)

| Element | Description |
|---------|-------------|
| **Aggregates** | Knowledge Assessment Aggregate, Knowledge Projection Aggregate |
| **Entities** | Decision, Rule, Invariant, Finding, Observation, Verdict |
| **Value Objects** | DecisionId, VerdictType, ConfidenceLevel, EvidenceSource |
| **Repositories** | DecisionRepository, RuleRepository, VerdictRepository |
| **Domain Services** | KnowledgeAssessmentService, KnowledgeProjectionService |
| **Application Services** | CreateDecisionService, IssueVerdictService, GenerateProjectionService |
| **Commands & Queries** | CQRS pattern (if adopted) |
| **Domain Events** | DecisionMade, VerdictIssued, BoundaryDiscovered |
| **Event Handlers** | DecisionMadeHandler, VerdictIssuedHandler |

### The Implementation Stack (Phase II.D)

| Layer | Technology | Status |
|-------|------------|--------|
| **Backend** | PHP 8.3+ / Laravel 12 | ⏳ To be implemented |
| **Database** | PostgreSQL (primary), Neo4j (optional graph) | ⏳ To be implemented |
| **API** | REST (Laravel API Resources) + GraphQL (optional) | ⏳ To be implemented |
| **Frontend** | Vue 3 / Inertia.js | ⏳ To be implemented |
| **Testing** | PHPUnit, PHPStan, Infection | ⏳ To be implemented |
| **Messaging** | Transactional Outbox | ⏳ To be implemented |

---

## What We Have Instead

### The Knowledge Structure View (C4-1) — Responsibility Allocation

This shows **responsibilities** allocated to AC-1 and AC-2 — not code-level design.

```
AC-1 — KNOWLEDGE ASSESSMENT — Responsibilities Allocated by AD-1 §7
┌─────────────────────────────────────────────────────────────────┐
│  Responsibilities:                                              │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ 1. Record observations and evidence                     │    │
│  │ 2. Evaluate observations against criteria               │    │
│  │ 3. Issue verdicts (judgments against criteria)          │    │
│  │ 4. Enforce boundary constraints                        │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

### The Future Level 4 (When Implemented)

```
AC-1 — Knowledge Assessment — Code View (Future)
┌─────────────────────────────────────────────────────────────────┐
│  Domain Layer:                                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  Assessment Aggregate                                   │    │
│  │  ├── Observation Entity                                 │    │
│  │  ├── Criterion Value Object                             │    │
│  │  ├── Verdict Entity                                     │    │
│  │  └── EvidenceCollection Value Object                    │    │
│  ├── AssessmentService                                     │    │
│  │  ├── evaluate(Observation, Criterion) → Verdict        │    │
│  │  └── issueVerdict(Assessment) → Verdict                │    │
│  └── AssessmentRepository                                  │    │
│      ├── save(Assessment)                                  │    │
│      └── findById(AssessmentId) → Assessment              │    │
│  └── VerdictIssued Domain Event                            │    │
└─────────────────────────────────────────────────────────────────┘
```

---

## Where L4 Will Be Defined

When Phase II.D begins, L4 will be defined in:

```
docs/architecture/c4/c4_level4_code_view.puml
```

---

## Summary

| Question | Answer |
|----------|--------|
| Is L4 supplied now? | ❌ No — not derivable from AD-1 |
| What is the reason? | Implementation design does not exist yet |
| When will L4 be supplied? | Phase II.D — Implementation |
| What will L4 show? | Aggregates, entities, value objects, repositories, services, events |
| What do we have instead? | Knowledge Structure View (responsibility allocation) |

---

**C4 Level 4 is NOT supplied. It will be defined during Phase II.D — Implementation. The Knowledge Structure View (responsibility allocation) is the closest equivalent available.**