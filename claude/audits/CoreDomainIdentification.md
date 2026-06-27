# Core Domain Identification

**Phase:** DD.3b — Strategic DDD Discovery (P1a)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md
**Status:** Initial — open for Senior Architect review

## Purpose

Not all contexts deserve equal attention. Classify each by strategic value to ensure modeling effort is proportional to business value and architectural risk.

---

## Classification Framework

| Category | Definition | Investment Level |
|----------|-----------|-----------------|
| **Core Domain** | Generates business value, differentiates the platform, must be protected most. | Highest modeling rigor, invariants structurally enforced. |
| **Supporting Domain** | Necessary for core domain to function but not differentiating. | Modeled with discipline but lower rigor than core. May use simplified approaches where appropriate. |
| **Generic Domain** | Could be off-the-shelf, outsourced, or built with standard approaches. | Minimal modeling investment. Convention over configuration. |

---

## Classification

### Core Domain

| Context | Rationale |
|---------|-----------|
| **Legitimacy** | The platform's constitutional purpose is determining whether participation is legitimate. This is what differentiates the system — a constitutional governance engine rather than a simple voting database. If legitimacy derivation is wrong, nothing else matters. |

**Why Legitimacy is Core:**
- Generates the primary constitutional value (participation decisions)
- Differentiates the platform from simple ballot boxes
- Must be protected most — failure means constitutional collapse
- Every other context exists to serve Legitimacy's decision

**Modeling implications:**
- Exclusivity (F4, F10) must be COMPILE-TIME enforced, not test-only
- Resolver must remain the sole derivation path
- LegitimacyOutcome construction must be restricted to ConstitutionalLegitimacyDecision
- Highest priority for aggregate discovery

### Supporting Domains

| Context | Rationale |
|---------|-----------|
| **Evidence** | Evidence must be preserved and classified for Legitimacy to derive outcomes. Essential but generic in the DDD sense — evidence preservation patterns are well-understood. |
| **Evaluation** | Evaluation bridges evidence and legitimacy. Architecturally critical (must not derive legitimacy) but the logic itself (assessing evidence quality) is a supporting function. |
| **Observation** | Observations feed the pipeline. Critical that they never become authoritative, but observation itself (signal creation, flat collection) is a supporting concern. |
| **Replay** | Deterministic reconstruction is essential for audit and trust, but replay is a well-understood mechanism. The value is in what replay PROTECTS (Legitimacy determinism), not in replay itself. |

**Why these are Supporting, not Core:**
- They exist to serve Legitimacy's decision
- They do not differentiate the platform (other systems also collect evidence, evaluate quality, and support replay)
- They can be modeled with discipline but do not require Legitimacy-level rigor

**Modeling implications:**
- Anti-corruption boundaries must prevent these contexts from leaking into Legitimacy
- Published language boundaries must be explicit
- Observation must have structural guards against becoming authoritative
- Evidence and Evaluation may merge into a single BC if boundary analysis supports it

### Generic Domains

| Context | Rationale |
|---------|-----------|
| **Projection** | Read-only presentation of constitutional state. This is a standard read-model concern. Could use conventional Laravel read-model patterns. |
| **Governance** | Election rules and enforcement actions. Important but convention-based — Laravel policies and middleware patterns handle this adequately. Not a differentiator. |
| **Migration** | Sovereignty transition (D.0-D.5). Temporary by nature — will be removed after D.5. Does not warrant permanent domain modeling investment. |
| **Certification** | Sub-authority of Replay. Value object, not an independent context. Modeled within Replay without separate investment. |
| **Retirement** | Legacy path removal. Temporary scaffolding. Minimal investment. |

**Why these are Generic:**
- They use standard patterns (read models, policies, feature flags)
- They do not differentiate the platform
- Migration and Retirement are temporary
- Certification is a value object, not an aggregate

**Modeling implications:**
- Use Laravel conventions (read models, policies)
- No special structural enforcement needed
- Migration divergences are operational telemetry, not domain events
- Projection must still respect F8 (no projection legitimacy) — but this is a rule, not a modeling investment

---

## Visual Hierarchy

```
                          ╔══════════════════════════╗
                          ║       CORE DOMAIN        ║
                          ║       Legitimacy         ║
                          ║  (Sole derivation path)  ║
                          ╚══════════════════════════╝
                                      ▲
                                      │ EvaluationEnvelope
                          ┌───────────┴───────────┐
              ┌───────────┤  SUPPORTING DOMAINS   ├───────────┐
              │           └───────────────────────┘           │
              ▼                                               ▼
     ┌────────────────┐                              ┌────────────────┐
     │   Evidence     │                              │  Observation   │
     │ (Preservation) │◄──── OverlaySignal ──────────│    (Flat       │
     │ (Classification)│                             │   Collection)  │
     └────────┬───────┘                              └────────────────┘
              │ EvidenceSnapshot
              ▼
     ┌────────────────┐
     │   Evaluation   │
     │   (Quality     │
     │  Assessment)   │
     └────────┬───────┘
              │ EvaluationEnvelope
              │
              ▼
     ╔════════════════════╗
     ║     Legitimacy     ║  ← CORE
     ╚════════════════════╝
              │
              │ LegitimacyOutcome
              ▼
     ┌────────────────┐     ┌────────────────┐
     │  Governance    │     │   Projection   │
     │ (Enforcement)  │     │  (Read Model)  │
     └────────────────┘     └────────────────┘
     GENERIC                 GENERIC

     ┌────────────────┐
     │    Replay      │  ← SUPPORTING (exists, well-defined)
     │  +Certification│
     └────────────────┘

     ┌────────────────┐     ┌────────────────┐
     │   Migration    │     │  Retirement    │
     │  (Temporary)   │     │  (Temporary)   │
     └────────────────┘     └────────────────┘
     GENERIC                 GENERIC
```

---

## What This Means for Sequence

| Phase | Focus |
|-------|-------|
| **Authority discovery** | All contexts — no prioritization yet |
| **Context mapping** | Supporting + Core domains most detailed mappings |
| **Observation assessment** | Critical — Observation is the riskiest boundary (must never become authoritative) |
| **Governance boundary** | Low urgency — Governance is generic. Don't over-model. |
| **Published language** | Focus on Supporting → Core boundaries (Evidence→Evaluation→Legitimacy) |
| **Stability assessment** | Core domain (Legitimacy) gets most conservative stability assessment |
| **Aggregate discovery** | Legitimacy first (highest value), then Supporting domains |
| **Namespace decisions** | Legitimacy namespace already correct. Focus on Supporting domain boundaries. |

## Risk Assessment

| Risk | Mitigation |
|------|-----------|
| Over-investing in Generic domains | Classification prevents equal attention. Governance, Projection, Migration use Laravel conventions. |
| Under-investing in Supporting domains | Evidence and Evaluation are still architecturally critical — they are Supporting, not Ignored. They still need formal BC boundaries. |
| Legitimacy boundary leakage | Core domain status justifies compile-time enforcement (F4, F10). Higher investment in structural guards. |
| Temporary contexts becoming permanent | Migration and Retirement have explicit D.5 expiration. Plan for their removal. |
