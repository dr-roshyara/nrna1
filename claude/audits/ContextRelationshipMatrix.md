# Context Relationship Matrix

**Phase:** DD.3b — Strategic DDD Discovery (P2a)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

The Context Map describes bounded contexts; this matrix describes **how they relate**. DDD defines specific relationship patterns that make context maps operational rather than merely descriptive.

---

## Relationship Type Reference

| Relationship | Meaning | When to Use |
|-------------|---------|-------------|
| **Partnership** | Two contexts, coordinated evolution | Teams/contexts must synchronize changes; failure in one affects the other |
| **Customer/Supplier** | Upstream supplies, downstream consumes. Upstream may succeed or fail independently. | Clear dependency chain with published interface |
| **Conformist** | Downstream conforms to upstream's model without translation | Upstream model is simple/stable enough to adopt directly |
| **Anti-Corruption Layer (ACL)** | Translation layer protects downstream from upstream's model | Upstream model would corrupt downstream if used directly |
| **Shared Kernel** | Shared subset of model across contexts | Both contexts need the same concepts; shared maintenance cost accepted |
| **Open Host Service (OHS)** | Published protocol for multiple consumers | One context serves many downstream consumers |
| **Published Language (PL)** | Well-documented shared language, often via OHS | Formal translation contract between contexts |
| **Separate Ways** | No relationship; complete independence | Contexts with no integration point |

---

## Relationship Matrix

### Primary Pipeline (upstream → downstream)

| Upstream | Downstream | Relationship | Rationale |
|----------|-----------|-------------|-----------|
| **Observation** | **Evidence** | **Customer/Supplier** | Observation supplies signals; Evidence freezes and preserves them. Observation can produce signals independently of how Evidence consumes them. Evidence is the "customer" of Observation's output. |
| **Evidence** | **Evaluation** | **Customer/Supplier** | Evidence provides frozen snapshots; Evaluation assesses their quality. Evidence succeeds when it preserves facts; Evaluation is the customer consuming those facts. |
| **Evaluation** | **Legitimacy** | **Published Language** | Evaluation produces `EvaluationEnvelope` as a formal published contract. Legitimacy is the sole consumer. The envelope IS the published language — it seals the complete evaluation output. No translation layer needed. |
| **Legitimacy** | **Governance** | **Conformist** | Governance receives `LegitimacyOutcome` and conforms to it. Governance does not translate, filter, or reinterpret legitimacy — it acts on the outcome as-is. |
| **Legitimacy** | **Projection** | **ACL (blocked)** | F8 forbids projection from referencing `LegitimacyOutcome`. An ACL (structural: F8 fitness function) blocks legitimacy details from appearing in the projection layer. |

### Cross-cutting Relationships

| Upstream | Downstream | Relationship | Rationale |
|----------|-----------|-------------|-----------|
| **Evidence** | **Replay** | **Open Host Service** | Replay wraps `ConstitutionalEvidenceSnapshot` into `ReplayEvidenceEnvelope` as a standardized published container. Evidence does not know about Replay. Replay adapts Evidence's output. |
| **Evaluation** | **Replay** | **Open Host Service** | Replay consumes policy sequence hashes from Evaluation. Same OHS pattern. |
| **Replay** | **(All)** | **Open Host Service** | `ReplayCertification` is a published result available to all contexts. Replay's interface is a standardized service contract. |
| **Migration** | **Legitimacy** | **Partnership** (temporary) | During D.0-D.5, Migration and Legitimacy must coordinate: Migration detects divergence, Legitimacy provides outcomes for comparison. This is a temporary partnership that dissolves after D.5. |
| **Migration** | **(Legacy)** | **ACL** | Migration provides an ACL between procedural (legacy) and constitutional enforcement paths. Shadow mode, feature flags, and divergence recording are ACL mechanisms. |

### Non-Relationships (Separate Ways)

| Context A | Context B | Rationale |
|-----------|-----------|-----------|
| **Observation** | **Legitimacy** | Separate Ways. Observation must NEVER flow directly to Legitimacy. All observation data passes through Evidence → Evaluation first. Direct Observation → Legitimacy is a constitutional violation (F1/F2/F4). |
| **Observation** | **Governance** | Separate Ways. Observations cannot directly trigger governance actions. |
| **Projection** | **Legitimacy** | Separate Ways (enforced by F8). Projection must not reference legitimacy outcomes. |
| **Replay** | **Legitimacy** | Separate Ways in derivation sense. Replay certifies past outcomes; it does not participate in new legitimacy decisions. |

---

## Relationship Diagram

```
                      LEGEND
    ─────▶  Customer/Supplier
    ═════▶  Published Language
    ─ ─ ─▶  Conformist
    ─ ─ ─▶  ACL (blocked)
    ◀═══▶   Open Host Service
    ◀─ ─▶   Partnership (temporary)
    ~~~▶    Separate Ways (forbidden flow)


                         ┌──────────────────────┐
                    ────▶│      Evidence         │
    ┌──────────┐         │  (Customer/Supplier)  │────▶ Evaluation
    │Observation│────────▶│   receives signals    │     (Customer/Supplier)
    │ (Supplier)│  ───▶  └──────────┬───────────┘     ───▶
    └──────────┘                    │                       │
                                    │ Evidence Snapshot     │ EvaluationEnvelope
                                    ▼                       ▼
                           ┌──────────────────────┐═════▶┌──────────────────────┐
                           │       Replay          │      │     Evaluation       │
                           │  (Open Host Service)  │      │ (Published Language) │
                           │  certifies integrity  │      │  produces envelope   │
                           └──────────────────────┘      └──────────┬───────────┘
                                                                     │
                                                                     ▼
                                                           ┌──────────────────────┐
                                                           │     Legitimacy       │
                                                           │   (Core Domain)      │
                                                           └──────┬───────┬───────┘
                                                                  │       │
                                         ┌────────────────────────┘       └──────────────┐
                                         ▼                                              ▼
                                ┌──────────────────┐                          ┌──────────────────┐
                                │   Governance     │                          │   Projection     │
                                │  (Conformist)    │                          │  (ACL — blocked) │
                                │  acts on outcome │                          │  F8 forbids ref  │
                                └──────────────────┘                          └──────────────────┘

    ┌──────────────────────┐     ┌──────────────────────┐
    │      Migration       │◀════│     Legitimacy        │  (Partnership, temporary)
    │   (D.0-D.5 only)     │════▶│  divergence compare   │
    └──────────────────────┘     └──────────────────────┘
```

---

## Boundary Enforcement Mechanisms

| Relationship | Enforcement |
|-------------|-------------|
| **Customer/Supplier** (Observation→Evidence) | No enforcement needed — natural dependency direction. Observation doesn't import from Evidence. |
| **Customer/Supplier** (Evidence→Evaluation) | Evidence snapshot interface is the contract. No circular deps. |
| **Published Language** (Evaluation→Legitimacy) | `EvaluationEnvelope` struct is the published language. Evaluation must not import Legitimacy types. |
| **Conformist** (Legitimacy→Governance) | Governance adopts `LegitimacyOutcome` as-is. |
| **ACL (blocked)** (Legitimacy→Projection) | F8 fitness function scans for `LegitimacyOutcome` references in UI/projection code. |
| **Open Host Service** (Replay→All) | `ReplayCertification` is the published contract. Replay is in its own namespace. |
| **Partnership** (Migration↔Legitimacy) | Temporary — managed via D.0 phase sequencing. Dissolves after D.5. |
| **Separate Ways** (Observation→Legitimacy) | F4 + F10 prevent Observation from reaching Legitimacy. Structural enforcement. |

## Risk Assessment

| Boundary | Risk | Mitigation |
|----------|------|-----------|
| Observation → Legitimacy (forbidden) | **HIGH** — direct flow would bypass Evaluation | F1, F2, F4, F5 structural enforcement |
| Legitimacy → Projection (blocked) | **MEDIUM** — UI displaying legal outcomes implies authority | F8 scans for LegitimacyOutcome refs |
| Migration → Legitimacy (partnership) | **LOW** — temporary, well-scoped | D.0 sequence gates |
| Evaluation → Legitimacy (translation) | **LOW** — envelope is formal contract | Integration tests verify envelope shape |
