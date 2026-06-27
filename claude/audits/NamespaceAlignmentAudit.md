# Namespace Alignment Audit

**Phase:** DD.3b — Namespace Alignment
**Audit Date:** 2026-05-29
**Scope:** `App\Domain\Election\Security` vs `App\Domain\Election\Security\Simplified`
**Method:** Structural analysis of namespace contents, production dependency mapping, duplicate type comparison
**Prerequisite:** N/A

---

## Executive Summary

The `Security\Simplified` namespace is a **partially-completed constitutional promotion** — a transitional namespace that was never transitioned out of. 6 types were promoted to the root namespace with enriched constitutional doctrine documentation; 9 remain actively used from Simplified; ~15 are effectively dead (zero production references). The namespace name `Simplified` itself is a historical artifact, not a description of architectural purpose.

---

## Question: What is the purpose of `Simplified` today?

**Classification: Transitional Constitutional Subdomain (Hybrid)**

The evidence supports this classification because:

| Evidence | Finding |
|----------|---------|
| 6 types promoted but dead copies remain | Migration happened but cleanup didn't |
| Promoted copies carry constitutional doc; Simplified copies do not | Promoted versions are the canonical enriched types |
| 9 Simplified-only types still actively used in production | Migration was not completed — survivors remain |
| 0 production references to dead Simplified duplicates | Promotion is safe and uncontested |

The namespace functions as a **survival zone** for types that were part of the constitutional model redesign but never got promoted to root.

---

## Namespace State

### Files in BOTH namespaces (6 duplicates)

| File | Root Lines | Simplified Lines | Root Refs | Simplified Refs | Root Has Doc? | Verdict |
|------|-----------|-----------------|-----------|----------------|--------------|---------|
| `BallotAuthorizationProtocol` | 27 | 9 | 2 | 0 | Yes | Simplified copy dead |
| `ElectionSecurityEvent` | TBD | TBD | TBD | 0 | TBD | Simplified copy dead |
| `TrustEvaluationEnvelope` | TBD | TBD | TBD | 0 | TBD | Simplified copy dead |
| `TrustEvaluationState` | 27 | 12 | 6 | 0 | Yes | Simplified copy dead |
| `TrustLevel` | 11 | 11 | 3 | 0 | Same (simple enum) | Simplified copy dead |
| `VotingTrustResult` | 73 | 14 | 7 | 0 | Yes | Simplified copy dead |

**Pattern:** In every case, the Root version is canonical (enriched documentation, active production references). The Simplified copy has 0 production references.

### Files ONLY in Root (23 types — canonical)

`CapabilityParitySnapshot`, `CommitAuthorizationFreshness`, `ConstitutionalConcernLevel`, `ConstitutionalDivergenceLedger`, `ConstitutionalDivergenceType`, `ConstitutionalTrustViolation`, `DeviceTrustContext`, `DivergenceCategory`, `DivergenceSeverity`, `DivergenceType`, `EvidenceWeightCategory`, `FingerprintMatchType`, `LegitimacyOutcome`, `NetworkTrustEvidence`, `OverlayObservationSummary`, `OverlaySignalCategory`, `OverlayStratification`, `SovereigntyConvergenceFitnessFunction`, `SovereigntyDivergenceRecord`, `TrustEvidencePrivacyPolicy`, `TrustValidityScope`, `VerificationAttestationRecord`, `VotingSessionTrustContinuity`

These are all types that were either:
- Created directly in root (never in Simplified)
- Promoted from Simplified and the Simplified copy was already deleted

### Files ONLY in Simplified — ACTIVELY USED (9 types)

| File | Production Refs | Primary Consumers |
|------|---------------|-------------------|
| `OverlaySignal` | 11 | All overlays, aggregator, security event recorder |
| `ConstitutionalObservationContext` | 5 | OverlayAggregator, overlays consuming contexts |
| `ConstitutionalEvidenceSnapshot` | 5 | Snapshot assemblers |
| `EvidenceEvaluationState` | 3 | Policy evaluators |
| `EvidenceClassification` | 2 | Policy chain |
| `ParticipationEligibilityEvidence` | 2 | Capability policies |
| `EvaluationEnvelope` | 1 | Evaluation pipeline |
| `EvaluationReasonCode` | 1 | Evaluation pipeline |
| `EvidenceEvaluationResult` | 1 | Evaluation pipeline |

These types are **active constitutional infrastructure** living in a namespace named `Simplified` — a name that no longer describes their role.

### Files ONLY in Simplified — ZERO PRODUCTION REFERENCES (15 types)

`BallotAuthorizationProtocol`, `ConstitutionalTrustSnapshot`, `DeviceEvidence`, `DivergenceRecord`, `ElectionConstitutionHasher`, `ElectionConstitutionSchema`, `ElectionConstitutionSnapshot`, `ElectionConstitutionValidator`, `ElectionSecurityEvent`, `EvaluationAuditTrail`, `EvidenceSeverity`, `EvidenceSnapshot`, `NetworkEvidence`, `SessionContinuity`, `TrustEvidenceAggregate`, `TrustEvaluationEnvelope`, `TrustEvaluationState`, `TrustLevel`, `VerificationEvidence`, `VotingTrustResult`

Of these, some are test-only (5 files reference `ConstitutionalTrustSnapshot`), and the rest appear fully dead — no production code, no test code references them.

---

## Dependency Map

### Import Directionality

```
Application Layer
  └── app/Application/Election/Security/
        ├── → Security\ (root)         — 25 files reference root types
        ├── → Security\Simplified\     — 17 files reference Simplified types
        │     (primarily overlays consuming OverlaySignal,
        │      ConstitutionalObservationContext)
        └── app/Application/Election/Security/Simplified/
              └── Simplified Policies — internal Simplified use only

Domain Layer
  ├── Root types are self-contained within Security\
  └── Simplified types are self-contained within Security\Simplified\
        (cross-domain: NetworkThresholdInterpreter → Simplified)
```

### Key Finding: No Circular Dependencies

Root and Simplified do not import each other. The dependency is strictly:
- **Application** → both namespaces (choosing which type to import)
- **Root** → self-contained
- **Simplified** → self-contained (+ 1 cross-domain consumer from `Constitution\`)

This means the namespaces are cleanly separable — no tangled dependency to unravel.

---

## OverlaySignal Disposition

### The Symptom

Test at `tests/Unit/Domain/Election/Security/OverlaySignalTest.php` imports:
```php
use App\Domain\Election\Security\OverlaySignal;  // WRONG — class is in Simplified
```

While the actual class lives at:
```php
App\Domain\Election\Security\Simplified\OverlaySignal;
```

### The Architectural Question

**Why does the test expect `OverlaySignal` in root?**

The test was written after 6 types had been promoted from Simplified → root. The author assumed `OverlaySignal` would follow the same path. It was a correct architectural instinct applied to an incomplete migration.

**Disposition: Promote `OverlaySignal` to root.**

Rationale:
- It is actively used by 11 production call sites across 7 overlays and infrastructure
- It is a constitutional security concept, not a "simplified" concept — the namespace name is misleading
- 6 similar types were already promoted successfully with zero disruption
- The root namespace already contains related signal types: `OverlaySignalCategory`, `OverlayObservationSummary`
- The Simplified copy becomes dead after promotion (same pattern as the other 6)

**Recommendation:** Promote `OverlaySignal` (and its dependents) to `App\Domain\Election\Security`, not as a standalone fix but as part of the namespace promotion plan below.

---

## Canonical Ownership Matrix

After the audit, every class should have one canonical owner:

| Owner | Classes | Justification |
|-------|---------|---------------|
| **Root** | All constitutional security types (legitimacy, trust, evidence, evaluation, divergence, sovereignty) | Root is the canonical namespace — enriched with constitutional doctrine documentation |
| **Subdomain (renamed from Simplified)** | Observation types that currently reside in Simplified | If migration to root is not preferred, rename `Simplified` to a semantically accurate name |
| **Deleted** | 6 stale Simplified duplicates + 15 dead Simplified-only types | Zero production references, no architectural purpose |

### Recommended: Eliminate Simplified Entirely

The cleanest architecture is to eliminate the `Simplified` namespace:

1. **Promote 9 active types** → `Security\` root (with constitutional doc enrichment)
2. **Delete 6 stale duplicates** (already have canonical root versions)
3. **Clean up ~15 dead types** (verify test coverage, then delete)
4. **Remove empty `Simplified`** directory

This eliminates the bounded-context leakage at its source.

The namespace itself is a historical accident — it should not become a permanent architectural fixture.

---

## Migration Impact Assessment

### Promoting the 9 Active Types

| Type | Production Refs | Complexity | Test Impact | Risk |
|------|----------------|------------|-------------|------|
| `OverlaySignal` | 11 | Low — class only, no deps on Simplified internals | OverlaySignalTest imports wrong namespace already | Low — class is self-contained |
| `ConstitutionalObservationContext` | 5 | Low — holds `OverlaySignal[]` only | Tests reference Simplified path | Low |
| `ConstitutionalEvidenceSnapshot` | 5 | Low — readonly snapshot data | Tests reference Simplified path | Low |
| `EvidenceEvaluationState` | 3 | Low — simple enum | Few test refs | Low |
| `EvidenceClassification` | 2 | Low — simple enum | Few test refs | Low |
| `ParticipationEligibilityEvidence` | 2 | Low — readonly DTO | Few test refs | Low |
| `EvaluationEnvelope` | 1 | Low — sealed container | Few test refs | Low |
| `EvaluationReasonCode` | 1 | Low — string enum | Few test refs | Low |
| `EvidenceEvaluationResult` | 1 | Low — result DTO | Few test refs | Low |

**Overall risk: LOW** — All 9 types are self-contained data objects (enums, readonly DTOs, readonly snapshots). None depend on other Simplified-internal types that won't also be promoted. The dependency map shows no circularity.

### Delete Path (6 stale duplicates + ~15 dead types)

Lower risk than promotion — simply remove files with 0 production references. Test references (e.g., `ConstitutionalTrustSnapshot` in test files) need updating to point to the surviving canonical copy or removal if the test was testing a dead type.

---

## Architect's Correction: Not "Incomplete Migration"

The initial audit characterized the 9 active survivors as "unfinished migration artifacts." **This is incorrect.** Doc comments on the survivors explicitly state:

| Simplified Type | Doc Comment |
|----------------|-------------|
| `EvidenceEvaluationState` | "Replaces `TrustEvaluationState` in the simplified/constitutional runtime" |
| `EvidenceEvaluationResult` | "Replaces `VotingTrustResult` in the simplified/constitutional runtime" |
| `EvaluationEnvelope` | "Replaces `TrustEvaluationEnvelope` in the simplified/constitutional runtime" |
| `EvidenceClassification` | "Replaces `TrustLevel` in the simplified/constitutional runtime" |

These are **deliberate replacements**, not migration stragglers. They form a parallel evidence/observation evaluation model that was intentionally isolated from the root's legitimacy/trust/authority model.

The true architecture is:

```
Security\                          Security\Simplified
├── LegitimacyOutcome              ├── EvidenceEvaluationState (replaces TrustEvaluationState)
├── TrustEvaluationState           ├── EvidenceEvaluationResult (replaces VotingTrustResult)
├── VotingTrustResult              ├── EvaluationEnvelope (replaces TrustEvaluationEnvelope)
├── TrustLevel                     ├── EvidenceClassification (replaces TrustLevel)
├── TrustEvaluationEnvelope        ├── EvaluationReasonCode (new — no root equivalent)
├── OverlaySignalCategory          ├── OverlaySignal (observation primitive)
├── OverlayStratification          ├── ConstitutionalObservationContext (flat signal collection)
├── ConstitutionalConcernLevel     ├── ConstitutionalEvidenceSnapshot (frozen evidence set)
├── ... (legitimacy, sovereignty)  ├── ParticipationEligibilityEvidence (frozen eligibility evidence)
```

The root namespace owns: **Governance, Legitimacy, Trust, Sovereignty**
The Simplified namespace owns: **Evidence, Observation, Evaluation Pipeline**

The namespace name `Simplified` is the problem — it describes a historical implementation state, not a bounded context.

## Revised Classification: Not All 6 Were Dead Duplicates

After comparing constructor signatures between root and Simplified copies:

| Simplified Type | Root Interface | Simplified Interface | Verdict |
|----------------|---------------|---------------------|---------|
| `BallotAuthorizationProtocol` | Same enum cases + methods | Same enum cases | ✅ DELETED — true dead duplicate |
| `TrustEvaluationState` | Same enum cases + constitutional doc | Same bare enum | ✅ DELETED — true dead duplicate |
| `TrustLevel` | Same enum cases | Same enum cases | ✅ DELETED — true dead duplicate |
| `VotingTrustResult` | `string $reason` | `EvaluationReasonCode $reason` | ⏳ KEPT — diverged interface; uses Evidence Context's `EvaluationReasonCode` |
| `ElectionSecurityEvent` | 14 fields incl. `$networkEvidence, $deviceEvidence` | 9 fields incl. `$auditContext, $policySequence` | ⏳ KEPT — completely different constructor |
| `TrustEvaluationEnvelope` | Bridges root→Application→Simplified | Internal evidence-only envelope | ⏳ KEPT — different architectural role |

The 3 kept types have diverged interfaces because they belong to the Evidence Context, not the root Security namespace. They will be resolved when the Evidence Context namespace decision is made.

## Corrected Recommendation

| Step | Action | Scope | Risk | Status |
|------|--------|-------|------|--------|
| 1 | **Capability Audit** — classify 9 survivors' authority ownership | Analysis | None | ✅ Complete (EvidenceObservationCapabilityAudit.md) |
| 2 | **Evidence Context Formalization** — bounded context document | Architecture doc | None | ✅ Complete (docs/architecture/contexts/EvidenceContext.md) |
| 3 | Delete 3 true dead duplicate Simplified classes | 3 file deletions | Very low | ✅ Complete |
| 4 | Rename `Simplified` → `Evidence` | Namespace rename | TBD | Post-context-map decision |
| 5 | Promote 3 diverged types to `Evidence` | 3 file moves | Low | Part of rename (step 4) |

**This namespace has architectural purpose, but its name is debt.** The Evidence Context document now defines that purpose.
