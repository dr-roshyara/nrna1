---
name: GEO-3 Architectural Improvements (10 Critical Requirements)
description: Mandatory and recommended improvements for GEO-3.1 before implementation to ensure constitutional governance platform is properly architected
type: feedback
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
## Context

GEO-3.0 Temporal Legitimacy Foundation is complete and approved. Before proceeding to GEO-3.1 (Constitutional Arbitration Engine), the user identified 10 critical architectural improvements required.

**Overall Assessment:** System is evolving from "authority graph engine" to "constitutional governance decision platform." This is major domain transition requiring greater architectural discipline around determinism, replayability, and legal explainability.

---

## 10 Required Improvements for GEO-3.1

### 1. **CRITICAL: Separate `ConstitutionalGovernanceDecision` Wrapper (NOT optional field in `GovernanceDecision`)**

**Problem:** Embedding `?ConstitutionalDecision` in `GovernanceDecision` mixes operational governance (who won) with constitutional interpretation (whether winner was legitimate). These are distinct semantic layers.

**Solution:**

```
GovernanceDecision (what operational authority won)
    └─ purely graph resolution

ConstitutionalDecision (whether that winner was legitimate)
    └─ purely constitutional evaluation

ConstitutionalGovernanceDecision (combined interpretation)
    ├─ GovernanceDecision
    └─ ConstitutionalDecision
```

**Why:** Preserves layering, enables future doctrine overlays, keeps operational governance deterministic, prevents replay complexity.

---

### 2. **MANDATORY: Temporal Window Invariant Validation**

**Problem:** Nothing prevents `validUntil < validFrom` in `TemporalAuthorityWindow`. Creates invalid constitutional timelines.

**Solution:** Add to `TemporalAuthorityWindow` constructor:

```php
if ($validUntil !== null && $validUntil < $validFrom) {
    throw new InvalidTemporalWindow('validUntil cannot be earlier than validFrom');
}
```

**Status:** Already implemented in GEO-3.0. ✅

---

### 3. **MANDATORY: `LegitimacyEvaluator` → Policy Interface Abstraction**

**Problem:** `final class LegitimacyEvaluator` is concrete. GEO-3.1 introduces SUSPENDED, EMERGENCY, CARETAKER, REVOKED which are doctrine-dependent. Different constitutions (federal, emergency, wartime, NGO, municipal) evaluate legitimacy differently.

**Solution:**

```php
interface LegitimacyPolicy {
    public function evaluate(TemporalAuthorityWindow, DateTimeImmutable): GovernanceLegitimacy;
}

class DefaultTemporalLegitimacyPolicy implements LegitimacyPolicy { ... }
```

**Why:** Enables constitutional doctrine variations; avoids future evaluator rewrites; allows federated/emergency/wartime constitutions.

---

### 4. **MANDATORY: `ConstitutionalArticleReferenceCollection` NOT `array $constitutionalArticles`**

**Problem:** Primitive arrays will collapse under growth. Eventually need: article number, section, amendment, revision, legal citation, doctrine source, language, precedence notes.

**Solution:**

Create VOs:
```php
ConstitutionalArticleReference (number, section, amendment, revision, citation, doctrine, language)
ConstitutionalArticleReferenceCollection (readonly array of references)
```

**Why:** Audit-safe, UI-safe, localization-ready, explainability-ready.

---

### 5. **MANDATORY: `ConstitutionalReason` Must Be Structured VO, NOT String**

**Problem:** Current plan replaces `string $resolutionReason` with `ConstitutionalReason`. But needs rich structure.

**Solution:**

```php
ConstitutionalReason {
    - code (string)
    - summary (string)
    - explanation (string)
    - articles[] (ConstitutionalArticleReferenceCollection)
    - severity (enum: DETERMINATIVE, BINDING, PERSUASIVE)
    - legitimacyImpact (enum: VALID, QUESTIONABLE, INVALID)
}
```

**Why:** Audit-safe, legal-safe, localization-ready, governance archaeology-ready.

---

### 6. **CRITICAL: Replay Immutability Rule (Prevent Historical Corruption)**

**Problem:** Replay must NEVER depend on current infrastructure state. If replay evaluates against current authority graphs or current constitutional doctrines, historical decisions become incorrect.

**Solution:**

Add explicit architectural constraint:

```
REPLAY IMMUTABILITY RULE:
Historical governance replay must never evaluate against current authority graphs or current constitutional doctrine.
All replay operations must use persisted historical snapshots only.
```

**Why:** Prevents governance archaeology corruption; enables legal audit trail integrity; ensures historical reconstruction is accurate.

---

### 7. **STRONGLY RECOMMENDED: Introduce `GovernanceClock` Abstraction NOW**

**Problem:** Future replay, simulations, governance archaeology, hypothetical elections, timeline debugging will require injectable time.

**Solution:**

```php
interface GovernanceClock {
    public function now(): DateTimeImmutable;
}

class SystemClock implements GovernanceClock { }
class FixedClock implements GovernanceClock { }
class ReplayClock implements GovernanceClock { }
```

**Why:** Prevents future temporal coupling; enables testability; allows time simulation; critical for governance archaeology.

---

### 8. **CRITICAL: Constitutional Arbitration Must Produce Traceable Explanations**

**Problem:** Current arbitration only returns winner + legitimacy. For governance systems, every decision must explain WHY something won and WHY others lost.

**Solution:**

Add mandatory `ConstitutionalArbitrationTrace`:

```php
ConstitutionalArbitrationTrace {
    - evaluatedCandidates[] (with rejection reasons)
    - temporalEvaluations[] (window validity per candidate)
    - precedenceEvaluations[] (rank order & reasoning)
    - doctrineRulesApplied[] (which rules triggered)
    - finalReasoning (summary)
}
```

**Why:** Becomes debugging tool, audit trail, legal explainability engine, governance analytics foundation.

---

### 9. **MANDATORY: GEO-3.2 Persistence Must Use Snapshots, NOT Serialize Domain Objects**

**Problem:** Serializing raw domain VOs/entities directly creates schema evolution problems and governance archaeology brittleness.

**Solution:**

```php
GovernanceDecisionSnapshot {
    - decisionId
    - decidedAt
    - capabilityType
    - winningAuthorityId
    - legitimacy
    - constitutionalReason (as structured JSON)
    - arbitrationTrace (as structured JSON)
    - originalAuthoritiesGraph (snapshot)
    - originalDocsrineRules (snapshot)
}
```

**Why:** Schema evolution safe; governance archaeology safe; enables future schema migrations without corrupting historical data.

---

### 10. **EXCELLENT APPROVAL: Wrap vs Replace (Keep This Pattern)**

**What was approved:** `ConstitutionalArbitrationPolicy` wraps `ConflictResolutionPolicy` rather than replacing it.

**Why this is correct:**
- Preserves layering
- Preserves backward compatibility
- Allows doctrine overlays
- Keeps operational governance deterministic
- Enables constitutional interpretation without rewriting engine core

**Pattern:** This is exactly how enterprise policy overlays should evolve. Keep this principle.

---

## Summary: Mandatory vs Optional

| # | Improvement | Priority | Status |
|---|-------------|----------|--------|
| 1 | ConstitutionalGovernanceDecision wrapper | CRITICAL | Must change before GEO-3.1 |
| 2 | Temporal window invariants | MANDATORY | ✅ Already done in GEO-3.0 |
| 3 | LegitimacyPolicy interface | MANDATORY | Must implement in GEO-3.1 |
| 4 | ConstitutionalArticleReferenceCollection | MANDATORY | Must implement in GEO-3.1 |
| 5 | Structured ConstitutionalReason VO | MANDATORY | Must implement in GEO-3.1 |
| 6 | Replay immutability rule | CRITICAL | Add as architectural constraint before GEO-3.2 |
| 7 | GovernanceClock abstraction | STRONGLY RECOMMENDED | Should implement before GEO-3.1 |
| 8 | ConstitutionalArbitrationTrace | CRITICAL | Add to GEO-3.1 design |
| 9 | Snapshot-based persistence | MANDATORY | Must implement in GEO-3.2 |
| 10 | Wrap not replace pattern | ✅ APPROVED | Keep this approach |

---

## How to Apply

**Before GEO-3.1 implementation:**
1. Update plan to include improvements 1, 3, 4, 5, 7, 8
2. Ensure GEO-3.2 plan includes improvement 6 and 9
3. Verify all 10 improvements are reflected in class designs

**Why:** System is no longer just a "governance engine." It's becoming a "constitutional governance decision and audit platform" requiring higher architectural discipline.
