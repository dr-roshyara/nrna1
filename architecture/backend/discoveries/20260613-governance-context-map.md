# Discovery: Governance ↔ Trust Context Mapping

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 5C)  
**Status:** Complete

## Ownership Questions

### Who owns certification?

`ReplaySession` → under `Domain/Election/Replay/`  
Dependencies: `ReplayEvidenceEnvelope`, `ReplayAssertion`, `ReplayCertification`, `ReplayCompatibilityVersion`  
All live inside **Election Governance**.

**Verdict:** Certification is owned by Election Governance, not a separate Trust context.

### Who owns verification?

`VerificationRevokedEvent` → under `Contexts/Trust/Domain/Events/`  
BUT verification attestation is built in `TrustPolicyEvaluator.buildAttestationRecord()` which takes `Election` and queries `ElectionMembership`.

**Verdict:** Verification has a dedicated event in a Trust directory, but evaluation logic depends on Election Governance models.

### Who owns evidence?

| Artifact | Location | Owner |
|----------|----------|-------|
| `EvidenceClassification` | `Domain/Election/Security/` | Election Governance |
| `EvidenceWeightCategory` | `Domain/Election/Security/` | Election Governance |
| `EvidenceSnapshot` | `Domain/Election/Security/` | Election Governance |
| `TrustEvidenceAggregate` | `Domain/Election/Security/` | Election Governance |
| `EvaluationEnvelope` | `Domain/Election/Security/` | Election Governance |

**Verdict:** Evidence is owned by Election Governance.

### Who owns trust policy?

`TrustPolicyEvaluator` → under `Application/Election/Security/`  
Constructor dependencies: `OverlayAggregator`, `PolicySequence`, `SecurityEventRecorder`, `TrustEvidencePrivacyPolicy`, `TrustSnapshotAssembler`  
All live inside **Election governance**.

**Verdict:** Trust policy evaluation is owned by Election Governance.

### Who consumes trust decisions?

`TrustCapabilityPolicy` (layer 3 in `ElectionCapabilityResolver` policy chain)  
Feeds into capability model consumed by frontend alongside lifecycle and overlay policies.

**Verdict:** Trust decisions flow into the same capability model as governance decisions.

## Context Map

```
┌─────────────────────────────────────────────────────────┐
│                ELECTION GOVERNANCE                         │
│                                                           │
│  Constitution ──→ Guard ──→ Election ──→ LifecycleEngine │
│       ↓                                  ↓               │
│  CapabilityResolver                                       │
│    ├── LifecycleCapabilityBaselinePolicy  (layer 1)       │
│    ├── OverlayCapabilityPolicy            (layer 2)       │
│    ├── TrustCapabilityPolicy              (layer 3)       │  ← consumes Trust
│    └── EvidenceCapabilityPolicy           (layer 4)       │
│       ↓                                                    │
│  CapabilitySnapshot ──→ Frontend                         │
│                                                           │
│  ReplaySession (certification)                            │
│  TrustPolicyEvaluator (trust evaluation)                  │
│  EvidenceClassification, Weight, Snapshot                 │
│                                                           │
│  ┌─ Contexts/Trust/ ──────────────────────────────┐      │
│  │  VerificationRevokedEvent (domain event)        │      │
│  │  (Supporting subdomain — not independent)      │      │
│  └────────────────────────────────────────────────┘      │
└─────────────────────────────────────────────────────────┘
```

## Relationship Type

Trust is a **Supporting Subdomain** of Election Governance, not an independent bounded context. Evidence:
1. `TrustPolicyEvaluator.evaluate()` takes `Election` and `User` as parameters
2. `ReplaySession` (certification) lives under `Domain/Election/Replay/`
3. All evidence classification lives under `Domain/Election/Security/`
4. Trust capability policy is layer 3 in the Election governance capability chain
5. `Contexts/Trust/` currently contains only a single domain event

The relationship is **upstream/downstream** within the same bounded context:
- Election Governance owns the concepts (eligibility, verification, trust state)
- Trust policy evaluation reads from Governance models
- Trust decisions flow into the capability model

## Architectural Implications

| Question | Answer |
|----------|--------|
| Is Trust a separate bounded context? | ❌ No — Supporting Subdomain of Election Governance |
| Can Trust make decisions without Governance? | ❌ No — depends on Election + ElectionMembership models |
| Should ReplaySession be extracted? | ❌ No — it's a governance capability |
| Is there a second ubiquitous language? | ⚠️ Candidate — trust vocabulary may eventually separate if Trust becomes independent |
| What about the `Contexts/Trust/` directory? | Currently contains 1 event. If it grows significantly with independent invariants, re-evaluate. |

## Next: Round 5D — Aggregate Boundary Analysis

With the context map established, the final round can analyze whether `Election` (the Eloquent model), `VotingSession` (the immutable value object), and `ReplaySession` (the stateful aggregate) have clear consistency boundaries.
