# Core Domain Protection

**Phase:** DD.3b — Strategic DDD Discovery (P9 gate prerequisite)
**Date:** 2026-05-29
**Status:** Final — for P9 boundary review

## Purpose

Explicitly document which domains are Core, Supporting, Generic, and Temporary — and what protection rules apply to each. Prevents equal treatment of unequal domains.

---

## Domain Classification

### Core Domain

| Domain | Rationale | Protection Level |
|--------|-----------|-----------------|
| **Legitimacy** | Generates business value. Differentiates the platform. The constitutional participation decision is the reason the system exists. | **Maximum** |

The Core Domain is the source of competitive advantage. It receives the most architectural investment, the strongest invariant protection, and the strictest boundary enforcement.

### Supporting Domains

| Domain | Rationale | Protection Level |
|--------|-----------|-----------------|
| **Evidence** (containing Evaluation) | Necessary for legitimacy derivation but does not differentiate. Any voting system needs evidence; how it evaluates is infrastructure, not competitive advantage. | **Standard** |
| **Observation** | Provides input to Evidence. Necessary but commodity — overlay signals are standard security monitoring. | **Standard** |
| **Replay** | Audit and certification infrastructure. Necessary for trust but not differentiating — any verifiable system needs replay. | **Standard** |

Supporting domains are necessary but not differentiating. They receive architectural investment proportional to their coupling to the Core Domain, not proportional to their internal complexity.

### Generic Domains

| Domain | Rationale | Protection Level |
|--------|-----------|-----------------|
| **Governance** | Enforcement of legitimacy decisions. Best handled by Laravel conventions (controllers, middleware, policies). Not a formal bounded context. | **Minimal** |
| **Projection** | UI rendering. Standard Inertia + Vue patterns. No DDD formalization needed beyond F8. | **Minimal** |

Generic domains are handled by framework conventions. No DDD bounded-context formalization. No aggregate design. No domain events.

### Temporary Domains

| Domain | Rationale | Protection Level |
|--------|-----------|-----------------|
| **Migration** | D.0-D.5 only. Sovereignty transition. Will be deleted. | **Scaffold** |
| **Retirement** | D.0-D.5 only. Sequencing and orchestration. Will be deleted. | **Scaffold** |

Temporary domains receive scaffold-level investment: enough to function during their lifetime, explicitly designed for deletion.

---

## Protection Rules

### P-CORE: Core Domain Protection

```
Legitimacy derives from PolicySequence.
Legitimacy must NEVER be derived outside ConstitutionalLegitimacyDecision.
```

| Rule | Enforcement | Violation Consequence |
|------|------------|----------------------|
| P-CORE-1 | F4, F10 fitness functions | Test failure, deployment blocked |
| P-CORE-2 | No controller/middleware may derive LegitimacyOutcome (F7) | Test failure, deployment blocked |
| P-CORE-3 | No UI may reference LegitimacyOutcome (F8) | Test failure, deployment blocked |
| P-CORE-4 | Evidence must not import from Legitimacy | Architectural review failure |

### P-SUPPORT: Supporting Domain Protection

```
Supporting domains serve the Core Domain.
They must not create coupling that prevents Core Domain evolution.
```

| Rule | Enforcement |
|------|------------|
| P-SUPPORT-1 | Supporting domains must not reference Core Domain types (one-way dependency toward Core) |
| P-SUPPORT-2 | Supporting domains may be replaced without Core Domain changes |
| P-SUPPORT-3 | Supporting domain boundaries are stable but not locked (may be refactored internally) |

### P-GENERIC: Generic Domain Protection

```
Generic domains use framework conventions.
No DDD formalization. No aggregate design. No domain events.
```

| Rule | Enforcement |
|------|------------|
| P-GENERIC-1 | No bounded-context namespace for Generic domains |
| P-GENERIC-2 | No domain events emitted from Generic domains (use framework events) |
| P-GENERIC-3 | Generic domains may consume but must not produce Core Domain types |

### P-TEMP: Temporary Domain Protection

```
Temporary domains are designed for deletion.
They must not create permanent coupling with permanent domains.
```

| Rule | Enforcement |
|------|------------|
| P-TEMP-1 | All temporary types must be in `TemporaryContextRegistry.md` |
| P-TEMP-2 | No permanent domain may depend on a temporary domain (blocks deletion) |
| P-TEMP-3 | Temporary domains must not be referenced in permanent integration tests |
| P-TEMP-4 | After hard deletion date, types are eligible for deletion without replacement |

---

## Investment Strategy

| Domain | Investment Level | What to Invest In |
|--------|-----------------|-------------------|
| **Legitimacy** (Core) | **High** | Invariant protection, boundary enforcement, replay determinism, structural guarantees |
| **Evidence** (Supporting) | **Medium** | Clear published language, typed reason codes, evidence integrity |
| **Observation** (Supporting) | **Medium** | Flat signal sets, non-sovereign signals, overlay clarity |
| **Replay** (Supporting) | **Medium** | Cross-runtime determinism, certification integrity, schema versioning |
| **Governance** (Generic) | **Low** | Laravel conventions, controller patterns, no DDD overhead |
| **Projection** (Generic) | **Low** | F8 compliance, no UI authority, standard frontend tooling |
| **Migration** (Temporary) | **Scaffold** | Minimum viable telemetry, designed for deletion |
| **Retirement** (Temporary) | **Scaffold** | Sequencing configuration, designed for deletion |

---

## Domain Dependency Rules

```
Allowed:
  Observation → Evidence → Legitimacy
  Replay → Evidence (reads snapshots)
  Governance → Legitimacy (receives outcome)
  Projection → Observation (reads state, no F8 violation)

Forbidden:
  Legitimacy → Evidence (reverse dependency)
  Legitimacy → Observation (bypasses Evidence)
  Evidence → Governance (conflates domains)
  Projection → Legitimacy (F8 violation)
  Evaluation → Legitimacy (EVL-1 violation)
  Temporary → Permanent (P-TEMP-2 violation)
```

---

## Cost of Misclassification

| Classified As | But Actually Is | Cost |
|--------------|-----------------|------|
| Generic | Core | Lost differentiation, under-investment in Legitimacy |
| Core | Supporting | Over-investment in commodity capability, inflated architecture |
| Supporting | Generic | Missing domain clarity for evidence/invariant ownership |
| Temporary | Permanent | Abandoned scaffolding becomes permanent debt (most common failure) |
| Permanent | Temporary | Architecture disruption when deletion time comes |

---

## References

| Artifact | Section |
|----------|---------|
| CoreDomainIdentification.md (P1a) | Original core/supporting/generic/temporary classification |
| AuthorityOwnershipMatrix.md (P1) | Authority boundaries per domain |
| ConstitutionalContextMap.md (P2) | Full context map with BC formalization status |
| BusinessCapabilityMap.md (P7b) | Capability-to-domain cross-reference |
| TemporaryContextRegistry.md | Deletion criteria for temporary domains |
| EvaluationAutonomyAssessment.md | Evaluation ⊂ Evidence conclusion |
