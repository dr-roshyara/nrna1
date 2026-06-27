# Domain Command Ownership Matrix

**Phase:** DD.3b — Strategic DDD Discovery (P5a)
**Date:** 2026-05-29
**Prerequisite:** ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

Every command must have exactly **one context owner**. Events capture what happened; commands express intent. Commands often cross context boundaries even when events are clean. Without command ownership, a context can inadvertently control behavior in another context's domain.

---

## Command Inventory

### Existing Commands (Runtime)

| Command | Current Location | Owner Context | Notes |
|---------|-----------------|---------------|-------|
| `ObserveOverlaySignal` | OverlayCoordinator | **Observation** | Overlays produce signals; Observation collects them |
| `FreezeEvidence` | Snapshot assembler | **Evidence** | Freezes evaluation input into immutable snapshot |
| `EvaluateEvidence` | Policy chain | **Evaluation** | Assesses evidence quality against policies |
| `DeriveLegitimacy` | ConstitutionalLegitimacyDecision | **Legitimacy** | The sole resolver — maps evidence state to outcome |
| `EnforceLegitimacyOutcome` | Controllers | **Governance** | Enforces the decision (block, allow, redirect) |
| `RecordDivergence` | DivergenceObserver | **Migration** | Records authority mismatch |
| `CertifyReplay` | ReplaySession | **Replay** | Produces certification result |
| `ProjectConstitutionalState` | Inertia responses | **Projection** | Renders state for UI |
| `ConfigureConstitutionalMode` | Config/system | **Governance** | Switches enforcement mode |

### Proposed Commands (Future)

| Command | Owner Context | Notes |
|---------|---------------|-------|
| `SealReplayEnvelope` | Replay | Wraps evidence into replay envelope |
| `AssertReplayOutcome` | Replay | Binds expected outcome before certification |
| `RetireLegacyGate` | Migration | Removes a legacy sovereignty path |

---

## Ownership Matrix

| Command | Owner | Must Not Be Owned By | Rationale |
|---------|-------|---------------------|-----------|
| `ObserveOverlaySignal` | Observation | Evidence, Legitimacy | Observation owns signal creation. Evidence freezes, doesn't observe. |
| `FreezeEvidence` | Evidence | Observation, Evaluation | Evidence owns preservation. Observation creates signals but doesn't freeze them. |
| `EvaluateEvidence` | Evaluation | Evidence, Legitimacy | Evaluation owns quality assessment. Evidence preserves; Legitimacy derives. |
| `DeriveLegitimacy` | Legitimacy | ANY other context | F4/F10 — resolver exclusivity. This is the most protected command. |
| `EnforceLegitimacyOutcome` | Governance | Legitimacy | Governance enforces; Legitimacy decides. Separating command from decision allows Governance to apply rules (e.g., "election closed" blocks an otherwise Allowed outcome). |
| `RecordDivergence` | Migration | Legitimacy | Migration owns divergence telemetry. Legitimacy should not know about divergence. |
| `CertifyReplay` | Replay | Evidence, Evaluation | Replay owns certification lifecycle. Evidence provides the data; Replay certifies it. |
| `ProjectConstitutionalState` | Projection | Legitimacy | F8 forbids projection from referencing legitimacy outcomes. Projection owns presentation. |
| `ConfigureConstitutionalMode` | Governance | Migration | Governance controls enforcement mode. Migration observes the transition. |

---

## Critical Command Boundaries

### DeriveLegitimacy — The Protected Command

```
FORBIDDEN callers:
  - Controllers (F7)
  - Middleware
  - Services
  - Queue workers
  - Commands
  - UI components

ALLOWED callers:
  - ConstitutionalLegitimacyDecision (the resolver itself)
  - (via TelemetryException) VoteController may receive the OUTCOME, not derive it
```

### Crossing Analysis

| Command | Crosses Boundary? | Details |
|---------|------------------|---------|
| `ObserveOverlaySignal` | No | Starts and ends in Observation |
| `FreezeEvidence` | No | Evidence receives signals, produces snapshots — single context |
| `EvaluateEvidence` | No | Evaluation receives snapshots, produces envelopes — single context |
| `DeriveLegitimacy` | **Boundary** | Receives `EvaluationEnvelope` from Evaluation (published language); produces `LegitimacyOutcome` |
| `EnforceLegitimacyOutcome` | **Boundary** | Receives outcome from Legitimacy; enforces in Governance |
| `RecordDivergence` | **Boundary** | Receives outcomes from both Legitimacy and legacy middleware; records in Migration |
| `CertifyReplay` | **Boundary** | Receives evidence from Evidence; produces certification |
| `ProjectConstitutionalState` | **Boundary** | Reads from Observation; renders in UI |

---

## Command Flow Diagram

```
ObserveOverlaySignal ──▶ FreezeEvidence ──▶ EvaluateEvidence
  (Observation)            (Evidence)         (Evaluation)
                                                  │
                                                  │ EvaluationEnvelope
                                                  ▼
                                          DeriveLegitimacy
                                          (Legitimacy — CORE)
                                                  │
                                                  │ LegitimacyOutcome
                                                  │
                         ┌────────────────────────┼──────────────────┐
                         ▼                        ▼                  ▼
               EnforceLegitimacyOutcome   ProjectState      RecordDivergence
               (Governance)               (Projection)      (Migration)
                                                                    ▲
                         CertifyReplay ──────────────────────────────┘
                         (Replay — cross-cutting)
```

---

## Boundary Command Risks

| Crossing | Risk | Mitigation |
|----------|------|-----------|
| Evaluation → Legitimacy (`DeriveLegitimacy`) | LOW — published language is formal `EvaluationEnvelope` | F4, F10 structural enforcement |
| Legitimacy → Governance (`EnforceLegitimacyOutcome`) | MEDIUM — Governance could re-derive rather than enforce | Convention + F4 |
| Replay → All (`CertifyReplay`) | LOW — OHS pattern with published `ReplayCertification` | Immutable certification, no post-creation mutation |
| Migration → Multiple (`RecordDivergence`) | LOW — temporary (D.0-D.5), telemetry only | Scope-bound by phase |
