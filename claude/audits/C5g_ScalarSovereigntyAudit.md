# C.5g — Scalar Sovereignty Audit

**Status:** COMPLETE
**Date:** 2026-05-28
**Phase:** C.5 — Sovereignty Stabilization Barrier
**Protocol:** Read-only sovereign archaeology (zero production code changes)

---

## Audit Purpose

Detect surviving scalar authority semantics from the old governance model.

**Invariant:**
```
Constitutional legitimacy must NEVER derive from scalar aggregation.
```

**Forbidden vocabulary:**
```
score, weight, severity, criticality, confidence,
risk level, escalation, priority, strongest signal, trust score
```

---

## Audit Methodology

Searched `app/Domain/Election/Security/` and `app/Application/Election/Security/`
for scalar vocabulary patterns. Each match was classified by:

1. **Layer:** Is this in observation/telemetry, legitimacy derivation, or projection?
2. **Usage:** Is the scalar term used as an INPUT to derivation, or as OUTPUT classification?
3. **Risk:** Does this create probabilistic sovereignty corruption?

---

## Findings

### S-1: DivergenceSeverity Enum

**File:** `app/Domain/Election/Security/DivergenceSeverity.php`
**Values:** Info → Warning → High → Critical → Existential

**Classification:** Telemetry/observation layer — operational severity of
sovereignty divergence. Used to determine alerting thresholds and migration
blocking criteria.

**Constitutional violation:** This is a full severity hierarchy embedded in
constitutional domain code. The `fromDivergence()` method at line 37 maps
typed divergences into unidimensional severity values. This is scalar
reduction of constitutional semantics — a Constitutional Algebra Boundary
violation.

**Mitigating factor:** The severity determines *alerting behavior* and
*transfer blocking*, not legitimacy outcomes. It is operational governance
metadata, not a derivation input to a sovereign decision.

**Remediation:** Deferred to D.0.3 retirement (telemetry concern).
The severity hierarchy will be replaced with typed divergence
classifications when telemetry is migrated to the constitutional runtime.

**Criticality:** MEDIUM

---

### S-2: ConstitutionalDivergenceType::severity() + Severity Enum

**File:** `app/Domain/Election/Security/ConstitutionalDivergenceType.php`
**Values (Severity enum at line 134):** Unknown → Low → Medium → High → Critical

**Classification:** Telemetry/observation layer. The `severity()` method at
line 96 maps each divergence type to a severity level. The Severity enum
at line 134 is a scalar priority ranking.

**Constitutional violation:** Same pattern as S-1 — scalar ranking of
constitutional divergence types violates the Constitutional Algebra
Boundary. The severity mapping at lines 96-131 assigns Critical/High/
Medium/Low/Unknown to 21 typed divergences.

**Mitigating factor:** This is a classification mapping for operational
telemetry. The severity value is never used as an input to legitimacy
derivation. It is an OUTPUT label for human governance review.

**Remediation:** Deferred to D.0.3 retirement (telemetry concern).

**Criticality:** MEDIUM

---

### S-3: Simplified/EvidenceSeverity Enum

**File:** `app/Domain/Election/Security/Simplified/EvidenceSeverity.php`
**Values:** LOW → MODERATE → HIGH

**Classification:** Observation layer — overlay evidence severity
classification. The doc at lines 11-12 explicitly states:
> "Overlays describe the severity of what they found, NOT what action to take."
> "The Resolver interprets severity as part of authority derivation."

**Constitutional violation:** Scalar naming in observation layer. Even
though the doc correctly forbids scalar-as-sovereign, the scalar
naming itself is residue from the old governance model.

**Mitigating factor:** The explicit doc guard at lines 11-12 shows
awareness of the constitutional boundary. The enum values are used
as descriptive signal classification, not as arithmetic operands.

**Remediation:** Deferred to D.0.3 retirement (observation layer cleanup).
Should be replaced with categorical evidence ontology (e.g.,
EVIDENCE_SUFFICIENT / EVIDENCE_INSUFFICIENT / EVIDENCE_AMBIGUOUS).

**Criticality:** LOW

---

### S-4: ConstitutionalConcernLevel Enum

**File:** `app/Domain/Election/Security/ConstitutionalConcernLevel.php`
**Values:** NONE → LOW → MEDIUM → HIGH → CRITICAL

**Classification:** Observation layer — overlay signal concern classification.
The doc at line 8 uses "severity" vocabulary which is scalar-adjacent.

**Constitutional violation:** Minor — the doc uses forbidden vocabulary
("severity of constitutional concern" at line 8). The enum values
themselves are categorical classification labels (NONE/LOW/MEDIUM/
HIGH/CRITICAL) that describe what was observed, not a derivation input.

**Mitigating factor:** These are overlay signal classifications —
description of observations, not inputs to aggregation. The values
are semantically equivalent to constitutional observation categories.

**Remediation:** Doc fix only. The enum is constitutionally acceptable
as categorical observation classification. Delete "severity" from
the doc comment. Deferred to D.0.3.

**Criticality:** LOW

---

### S-5: EvidenceWeightCategory Enum

**File:** `app/Domain/Election/Security/EvidenceWeightCategory.php`
**Values:** WEAK → MODERATE → STRONG → DEFINITIVE

**Classification:** Observation layer — evidence reliability classification.

**Constitutional assessment:** GREY ZONE. The values are an evidence
ontology (categorical classification of evidence reliability), not a
scalar score used in aggregation. However, the naming uses "weight"
which is scalar-adjacent. If this enum is ever used as a multiplier
or weight factor in an aggregation function, it becomes active scalar
sovereignty corruption. Currently it is used only as descriptive
classification.

**Mitigating factor:** The values are categorical labels describing
evidence reliability. No aggregation function currently consumes these
as arithmetic inputs.

**Remediation:** Flag for monitoring. If any code path is added that
uses EvidenceWeightCategory as a multiplier/weight/score in a
legitimacy calculation, that would be an ACTIVE SCALAR SOVEREIGNTY
VIOLATION requiring immediate remediation.

**Criticality:** LOW (monitoring) / HIGH (if used in aggregation)

---

## Summary Table

| ID | File | Scalar Pattern | Layer | Active Risk | Remediation |
|----|------|----------------|-------|-------------|-------------|
| S-1 | `DivergenceSeverity.php` | Full severity hierarchy | Telemetry | None (operational metadata) | D.0.3 retirement |
| S-2 | `ConstitutionalDivergenceType.php` | severity() + Severity enum | Telemetry | None (output classification) | D.0.3 retirement |
| S-3 | `Simplified/EvidenceSeverity.php` | LOW/MODERATE/HIGH | Observation | None (doc-guarded) | D.0.3 cleanup |
| S-4 | `ConstitutionalConcernLevel.php` | "severity" in doc + values | Observation | None (categorical labels) | Doc fix |
| S-5 | `EvidenceWeightCategory.php` | "weight" naming | Observation | MONITOR (grey zone) | Flag for D.0.3 |

---

## Constitutional Classification

All five findings are in **observation/telemetry** code, not in **legitimacy
derivation**. None of these scalar values are used as inputs to scoring,
weighting, or aggregation functions that produce a sovereign outcome.

This means the system has:

```
semantic scalar residue — YES
active probabilistic sovereignty corruption — NO
```

This is an important constitutional distinction. The system has scalar
*naming* in domain types, but not scalar *aggregation* producing legitimacy.

---

## Phase D.0.3 Implications

Before D.0.3e (final retirement), all scalar naming in constitutional
domain code must be remediated. The remediation approach is:

1. Replace scalar hierarchies with typed categorical classifications
   (e.g., `DivergenceBlocker` / `DivergenceObserver` instead of
   `DivergenceSeverity::Critical` / `DivergenceSeverity::Info`)
2. Replace "severity" and "weight" vocabulary with domain-appropriate
   constitutional semantics
3. Verify no aggregation function consumes scalar values as arithmetic
   operands

This does NOT block C.5 certification or D.0.3a (constitutional primary
enforcement) — the scalar residue is in observation/telemetry, not in
the enforcement path. But it must be remediated before the system can
claim full constitutional governance maturity.
