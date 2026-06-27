# Domain Event Ownership Matrix

**Phase:** DD.3b — Strategic DDD Discovery (P5)
**Date:** 2026-05-29
**Prerequisite:** ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

Every domain event must have exactly **one context owner**. Without this matrix, event ownership becomes ambiguous as the system evolves — events float between contexts, handlers overlap, and the provenance of sovereign events becomes untraceable.

---

## Event Inventory

### Existing Events (Implemented)

| Event | File | Context Owner | Category |
|-------|------|---------------|----------|
| `ObservationRecorded` | `Security/Event/ObservationRecorded.php` | **Observation** | Observational |
| `EvidenceEvaluationCompleted` | (not yet in Event/ — inferred from pipeline) | **Evaluation** | Observational |
| `LegitimacyEvaluated` | `Security/Event/LegitimacyEvaluated.php` | **Legitimacy** | Sovereign |
| `LegitimacyGranted` | `Security/Event/LegitimacyGranted.php` | **Legitimacy** | Sovereign |
| `ConstitutionalDenialIssued` | `Security/Event/ConstitutionalDenialIssued.php` | **Legitimacy** | Sovereign |
| `SovereigntyBoundaryCrossed` | `Security/Event/SovereigntyBoundaryCrossed.php` | **Migration** | Migration |
| `ConstitutionalFallbackActivated` | `Security/Event/ConstitutionalFallbackActivated.php` | **Migration** | Migration |
| `DivergenceObserved` | `Security/Event/DivergenceObserved.php` | **Migration** | Migration |
| `ReplaySessionOpened` | `Replay/Event/ReplaySessionOpened.php` | **Replay** | Replay |
| `ReplayCertificationIssued` | `Replay/Event/ReplayCertificationIssued.php` | **Replay** | Replay |
| `ReplayDivergenceDetected` | `Replay/Event/ReplayDivergenceDetected.php` | **Replay** | Replay |

### Proposed Events (Not Yet Implemented)

| Event | Context Owner | Category | Notes |
|-------|---------------|----------|-------|
| `EvidenceFrozen` | **Evidence** | Observational | Emitted when evidence snapshot is sealed |
| `EvaluationStateChanged` | **Evaluation** | Observational | Evidence quality state transition |

---

## Event Categories

### Sovereign Events

These are authority-significant — they represent constitutional decisions. Must be:
- Emitted by the Legitimacy Context exclusively
- Immutable after emission
- Traceable to a specific evaluation envelope
- Never emitted by Observation, Evidence, or Evaluation

| Event | Why Sovereign | Current Owner |
|-------|--------------|---------------|
| `LegitimacyEvaluated` | Records a legitimacy outcome derivation | Legitimacy ✅ |
| `LegitimacyGranted` | Records an Allowed outcome specifically | Legitimacy ✅ |
| `ConstitutionalDenialIssued` | Records a Denied outcome specifically | Legitimacy ✅ |

### Observational Events

These are non-authoritative — they inform but do not decide. May be emitted by Observation, Evidence, or Evaluation.

| Event | Why Observational | Current Owner |
|-------|------------------|---------------|
| `ObservationRecorded` | An overlay emitted a signal | Observation ✅ |
| `EvidenceEvaluationCompleted` | Evaluation produced a result | Evaluation (proposed) |
| `EvidenceFrozen` | Evidence snapshot was sealed | Evidence (proposed) |

### Migration Events

These are temporary (D.0-D.5 only). Record sovereignty transition state.

| Event | Why Migration | Current Owner |
|-------|--------------|---------------|
| `SovereigntyBoundaryCrossed` | Constitutional mode state changed | Migration ✅ |
| `ConstitutionalFallbackActivated` | Rollback triggered | Migration ✅ |
| `DivergenceObserved` | Dual-authority mismatch detected | Migration ✅ |

### Replay Events

These are certification-scoped. Record replay lifecycle.

| Event | Why Replay | Current Owner |
|-------|-----------|---------------|
| `ReplaySessionOpened` | Replay cycle started | Replay ✅ |
| `ReplayCertificationIssued` | Certification result produced | Replay ✅ |
| `ReplayDivergenceDetected` | Replay mismatch detected | Replay ✅ |

---

## Ownership Rules

| Rule | Description |
|------|-------------|
| E1 | Every event has exactly one context owner |
| E2 | Observation must not emit sovereign events |
| E3 | Evidence must not emit sovereign events |
| E4 | Evaluation must not emit sovereign events |
| E5 | Only Legitimacy may emit sovereign events |
| E6 | Replay events are scoped to Replay Context |
| E7 | Migration events are temporary (D.0-D.5 — will be retired) |
| E8 | Event handlers in different contexts must not modify event payload |

---

## Current Gap: Evidence + Evaluation Events

`EvidenceEvaluationCompleted` is referenced in EvidenceContext.md but does not yet exist as a domain event class. Similarly, `EvidenceFrozen` would capture the snapshot-sealing moment. These are lower priority than sovereign event ownership.

---

## Cross-Context Event Flow

```
Observation ───▶ ObservationRecorded (observational)
     │
     │  (signals flow inline, not via event bus)
     ▼
Evidence ───▶ EvidenceFrozen (proposed — observational)
     │
     ▼
Evaluation ───▶ EvidenceEvaluationCompleted (observational)
     │
     ▼
Legitimacy ───▶ LegitimacyEvaluated (SOVEREIGN)
     │           LegitimacyGranted (SOVEREIGN)
     │           ConstitutionalDenialIssued (SOVEREIGN)
     ▼
Migration ───▶ SovereigntyBoundaryCrossed (migration)
                ConstitutionalFallbackActivated (migration)
                DivergenceObserved (migration)

Replay ───▶ ReplaySessionOpened (replay)
            ReplayCertificationIssued (replay)
            ReplayDivergenceDetected (replay)
```

**Key design note:** The primary pipeline (Observation → Evidence → Evaluation → Legitimacy) flows via **direct invocation** (evaluation call chain), not via events. Events are emitted for telemetry, audit, and external consumers. The event bus is NOT the primary evaluation path.
