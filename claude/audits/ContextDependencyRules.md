# Context Dependency Rules

**Phase:** DD.3b — Strategic DDD Discovery (P9 gate final prerequisite)
**Date:** 2026-05-29
**Status:** Final — for P9 boundary approval

## Purpose

Formal dependency direction rules between approved bounded contexts. Prevents coupling that would violate the constitutional architecture. This artifact is the final prerequisite before P9 approval and subsequent namespace migration (P10).

---

## Dependency Rule Matrix

| Consumer ↓ | Producer → | Observation | Evidence | Legitimacy | Replay | Governance | Projection | Migration |
|------------|-----------|-------------|----------|------------|--------|------------|------------|-----------|
| **Observation** | — | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Evidence** | ✅ PL | — | ❌ | ⚠️ PL | ❌ | ❌ | ❌ | ❌ |
| **Legitimacy** | ❌ | ✅ PL | — | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Replay** | ❌ | ⚠️ PL | ❌ | — | ❌ | ❌ | ❌ | ❌ |
| **Governance** | ❌ | ❌ | ✅ PL | ❌ | — | ❌ | ❌ | ❌ |
| **Projection** | ✅ RO | ❌ | ❌ | ❌ | ❌ | — | ❌ | ❌ |
| **Migration** | ❌ | ❌ | ⚠️ RO | ❌ | ❌ | ❌ | — | ❌ |

**Legend:**
- ✅ = **Allowed** (with qualification)
- ❌ = **Forbidden** (must never occur)
- ⚠️ = **Allowed with restrictions** (see rule below)
- PL = Published Language only (consumer imports published type, never internal types)
- RO = Read-only (consumer reads but never writes or derives)

---

## Allowed Dependencies

### ADR-1: Evidence → Observation (Published Language)

```
Evidence imports: OverlaySignal, ConstitutionalObservationContext
Evidence must NOT import: Observation's internal types, evaluators, or state
```

| Allowed | Forbidden |
|---------|-----------|
| `use OverlaySignal` | `use DeviceAnomalyOverlay` (internal to Observation) |
| `use ConstitutionalObservationContext` | `use OverlayCoordinator` (internal to Observation) |

**Rationale:** Evidence consumes the output of Observation. It must not reach into Observation's internal implementation.

---

### ADR-2: Legitimacy → Evidence (Published Language)

```
Legitimacy imports: EvaluationEnvelope
Legitimacy must NOT import: Evidence's internal types, snapshot internals, or evaluators
```

| Allowed | Forbidden |
|---------|-----------|
| `use EvaluationEnvelope` | `use ConstitutionalEvidenceSnapshot` (Evidence internal) |
| `use EvidenceEvaluationResult` (via envelope) | `use PolicySequence` (Evidence internal) |

**Rationale:** Legitimacy consumes the sealed evaluation result. It must not reach into Evidence internals or inspect evidence before evaluation.

---

### ADR-3: Governance → Legitimacy (Published Language)

```
Governance imports: LegitimacyOutcome
Governance must NOT import: Legitimacy's derivation logic, resolver internals
```

| Allowed | Forbidden |
|---------|-----------|
| `use LegitimacyOutcome` | `use ConstitutionalLegitimacyDecision` (resolver) |
| `switch ($outcome) {...}` | `LegitimacyOutcome::Denied` creation outside resolver |

**Rationale:** Governance enforces the outcome; it does not re-derive it. GOV-1 violation if Governance creates `LegitimacyOutcome`.

---

### ADR-4: Projection → Observation (Read-Only)

```
Projection reads: OverlaySignal[] for display
Projection must NOT: Rank, order, or imply authority from signal content
```

| Allowed | Forbidden |
|---------|-----------|
| `{{ signal.description }}` | Sorting by signal type (PRJ-2) |
| Rendering as flat list | Coloring by "criticality" |

**Rationale:** Projection presents non-authoritative state. F8 blocks Legitimacy references; PRJ-2 blocks implied precedence.

---

### ADR-5: Replay → Evidence (Published Language, Read-Only)

```
Replay imports: ConstitutionalEvidenceSnapshot
Replay must NOT: Modify evidence, re-evaluate, or derive outcomes
```

| Allowed | Forbidden |
|---------|-----------|
| Reading snapshot for replay | Calling `PolicySequence::evaluate()` on snapshot |
| `certify()` with snapshot hash | Creating `EvaluationEnvelope` from snapshot |

**Rationale:** Replay certifies that same evidence produces same outcome. It must not re-execute evaluation or derive its own outcomes.

---

### ADR-6: Migration → Legitimacy (Read-Only, Temporary)

```
Migration reads: LegitimacyOutcome for divergence comparison
Migration must NOT: Create, modify, or intercept outcome derivation
```

| Allowed | Forbidden |
|---------|-----------|
| Comparing procedural vs constitutional outcomes | Creating `LegitimacyOutcome` |
| Recording divergence telemetry | Blocking based on divergence |

**Rationale:** Migration is temporary (D.0-D.5). It observes outcomes without affecting derivation.

---

## Forbidden Dependencies

### FDR-1: Evidence → Legitimacy

```php
// FORBIDDEN:
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\ConstitutionalLegitimacyDecision;
```

**Why:** F4, F10 — resolver exclusivity. Evidence evaluates; Legitimacy decides. Evidence must not derive, reference, or depend on legitimacy types.

**Violation consequence:** Deployment blocked. F4 fitness function scans for this import.

---

### FDR-2: Legitimacy → Observation (Direct)

```php
// FORBIDDEN:
use App\Domain\Election\Security\Simplified\OverlaySignal;
```

**Why:** OBS-3 — observations must pass through Evidence before reaching Legitimacy. Direct import bypasses evidence evaluation.

**Violation consequence:** Architectural review failure. The `OverlaySignal` type must not appear in Legitimacy context code.

---

### FDR-3: Observation → Legitimacy

```php
// FORBIDDEN:
use App\Domain\Election\Security\LegitimacyOutcome;
```

**Why:** OBS-1 — observations are non-sovereign. They must not reference, produce, or imply legitimacy outcomes.

**Violation consequence:** Deployment blocked. F1 fitness function enforces this.

---

### FDR-4: Projection → Legitimacy

```php
// FORBIDDEN in Vue components, Inertia pages:
import { LegitimacyOutcome } from '@/types';
```

**Why:** F8 — UI must not reference `LegitimacyOutcome`. Projection must not know about legitimacy decisions.

**Violation consequence:** Deployment blocked. F8 fitness function scans Vue/JS files for `LegitimacyOutcome`.

---

### FDR-5: Evaluation → Legitimacy

```php
// FORBIDDEN within evaluation code:
return LegitimacyOutcome::Allowed;
```

**Why:** EVL-1 — evaluation must not derive legitimacy. This is a subdomain-of-Evidence rule.

**Violation consequence:** Deployment blocked. EVL-1 is enforced by F4/F10 (same fitness functions cover both Evidence and its Evaluation subdomain).

---

### FDR-6: Temporary → Permanent (Reverse)

```php
// FORBIDDEN: permanent context importing from temporary
use App\Domain\Election\Security\Migration\SovereigntyDivergenceRecord;
```

**Why:** P-TEMP-2 — no permanent domain may depend on a temporary domain. Temporary types must be deletable without affecting permanent contexts.

**Violation consequence:** Blocks deletion of temporary types. Must be refactored before D.5 deadline.

---

### FDR-7: Generic Domain → Core Domain (Upstream)

```php
// FORBIDDEN: Governance importing Legitimacy derivation
use App\Domain\Election\Security\ConstitutionalLegitimacyDecision;
```

**Why:** P-GENERIC-3 — Generic domains must not produce Core Domain types. Governance enforces outcomes, it does not participate in legitimacy derivation.

**Violation consequence:** Architectural review failure.

---

## Dependency Direction Summary

ALLOWED dependencies (consumer imports published language from producer):

| Consumer | Producer | Type | Mechanism |
|----------|----------|------|-----------|
| **Evidence** | **Observation** | Code dependency | Evidence imports `OverlaySignal[]` |
| **Legitimacy** | **Evidence** | Code dependency | Legitimacy imports `EvaluationEnvelope` |
| **Replay** | **Evidence** | Code dependency | Replay imports `ConstitutionalEvidenceSnapshot` |
| **Governance** | **Legitimacy** | Code dependency | Governance imports `LegitimacyOutcome` |
| **Projection** | **Observation** | Read-only display | Projection reads signals for UI |
| **Migration** | **Legitimacy** | Read-only, temporary | Migration reads outcome for divergence |

FORBIDDEN dependencies (in any direction):

| Violation | Rule | Why |
|-----------|------|-----|
| **Evidence → Legitimacy** | FDR-1 | Resolver exclusivity — must not derive or reference legitimacy |
| **Legitimacy → Observation** | FDR-2 | Bypasses Evidence — observations must pass through evaluation |
| **Observation → Legitimacy** | FDR-3 | Observations are non-sovereign (OBS-1, F1) |
| **Projection → Legitimacy** | FDR-4 | F8 violation — UI must not reference legitimacy |
| **Evaluation → Legitimacy** | FDR-5 | EVL-1 violation — evaluation subdomain must not derive |
| **Temporary → Permanent** | FDR-6 | Blocks deletion of temporary types |
| **Generic → Core** (producer) | FDR-7 | Generic must not produce Core domain types |

DIRECTION RULE:
```
Data flows DOWNSTREAM:    Observation → Evidence → Legitimacy
Code depends UPSTREAM:    Evidence → Observation, Legitimacy → Evidence

Consumers always depend on the producer's published language.
The code-dependency arrow always points TOWARD the published language producer.
```

---

## Enforcement Mapping

| Rule | Enforcement Type | Fitness Function | Detection Method |
|------|-----------------|-----------------|-----------------|
| ADR-1 | Convention + test | F2 (envelope plurality) | Import scan |
| ADR-2 | Convention + test | F4 (resolver exclusivity) | Import scan |
| ADR-3 | Convention | F4, F10 | Import scan |
| ADR-4 | Convention + test | F8 (no projection legitimacy) | Vue file scan |
| ADR-5 | Convention | F3, F9 | Import scan |
| ADR-6 | Convention | None | Temporary — manual review |
| FDR-1 | **Structural** | F4, F10 | Compile-time + test |
| FDR-2 | Test | F1, F4 | Import scan |
| FDR-3 | **Structural** | F1 | Domain type check |
| FDR-4 | **Structural** | F8 | Vue file scan |
| FDR-5 | **Structural** | F4, F10 | Same as FDR-1 |
| FDR-6 | Convention | None | Manual review at D.5 |
| FDR-7 | Convention | None | Architectural review |

---

## Dependency Graph (Visual)

```
                    ┌──────────────┐
                    │ Observation  │
                    │    (BC)      │
                    └──────┬───────┘
                           │ OverlaySignal[]
                           │ (Published Language)
                           ▼
                    ┌──────────────┐
              ┌────▶│   Evidence   │◀──────────┐
              │     │ (BC + Eval)  │           │
              │     └──────┬───────┘           │
              │            │ EvaluationEnvelope │
              │            │ (Published Lang.)  │
              │            ▼                    │
              │     ┌──────────────┐   ┌────────┴────────┐
              │     │  Legitimacy  │   │     Replay      │
              │     │   (Core BC)  │   │  (Core-Support) │
              │     └──────┬───────┘   └─────────────────┘
              │            │ LegitimacyOutcome
              │            │ (Published Language)
              │            ▼
              │     ┌──────────────┐
              │     │  Governance  │
              │     │  (Generic)   │
              │     └──────────────┘
              │
              │     ┌──────────────┐
              └─────│  Projection  │
                    │  (Generic)   │
                    └──────────────┘

        KEY:
        ──▶ Allowed dependency (with published language)
        ──✅ Read-only / published language boundary
        All reverse arrows are FORBIDDEN
```

---

## Published Language Boundaries

Every allowed dependency crosses its boundary through a published language type (per PublishedLanguageMatrix.md — P6):

| Boundary | Published Language | Producer | Consumer |
|----------|-------------------|----------|----------|
| Observation → Evidence | `OverlaySignal[]` in `ConstitutionalObservationContext` | Observation | Evidence |
| Evidence → Legitimacy | `EvaluationEnvelope` | Evidence | Legitimacy |
| Evidence → Replay | `ConstitutionalEvidenceSnapshot` in `ReplayEvidenceEnvelope` | Evidence | Replay |
| Legitimacy → Governance | `LegitimacyOutcome` | Legitimacy | Governance |
| Observation → Projection | `OverlaySignal[]` (read-only display) | Observation | Projection |
| Legitimacy → Migration | `LegitimacyOutcome` (read-only, temporary) | Legitimacy | Migration |

**Invariant:** No type may cross a boundary without being a published language entry.

---

## Namespace Implications

When namespace migration proceeds (P10), dependencies must follow directory structure:

```
Allowed import pattern:
  App\Domain\Election\Evidence\*  →  may import  App\Domain\Election\Observation\*
  App\Domain\Election\Legitimacy\*  →  may import  App\Domain\Election\Evidence\*
  App\Domain\Election\Replay\*  →  may import  App\Domain\Election\Evidence\*

Forbidden import pattern:
  App\Domain\Election\Evidence\*  →  must NOT import  App\Domain\Election\Legitimacy\*
  App\Domain\Election\Legitimacy\*  →  must NOT import  App\Domain\Election\Observation\*
  App\*  →  must NOT import  App\Domain\Election\* (Infrastructure → Domain)
```

This provides a **directory-structure-enforced dependency graph** that can be mechanically checked.

---

## References

| Artifact | Section |
|----------|---------|
| PublishedLanguageMatrix.md (P6) | Full cross-boundary type map |
| ConstitutionalContextMap.md (P2) | Context mission and upstream/downstream |
| ContextRelationshipMatrix.md (P2a) | DDD relationship types per boundary |
| CoreDomainProtection.md | Protection rules (P-CORE, P-SUPPORT, etc.) |
| BusinessInvariantCatalog.md (P7a) | Invariant cross-reference |
| EvaluationAutonomyAssessment.md | Evaluation ⊂ Evidence conclusion |
