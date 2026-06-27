# Discovery: Voting Trustworthiness Architecture

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 5B)  
**Status:** Complete

## Files Analyzed

| File | Role |
|------|------|
| `Domain/Election/Replay/ReplaySession.php` | Aggregate root for replay certification cycle |
| `Domain/Election/Replay/ReplayAssertion.php` | Deterministic contract binding evidence → outcome |
| `Domain/Election/Replay/ReplayCertification.php` | Immutable outcome comparison result |
| `Domain/Election/Replay/ReplayEvidenceEnvelope.php` | Sealed evidence container with hash integrity |
| `Domain/Election/Replay/ReplayEvidenceEnvelope.php` | Schema version marker for compatibility |
| `Domain/Election/Replay/ReplayCompatibilityVersion.php` | Schema versioning for replay determinism |
| `Domain/Election/Security/Simplified/TrustEvaluationEnvelope.php` | Trust evaluation sealed envelope |
| `Domain/Election/Security/TrustPolicyEvaluator.php` | Evaluates trust policies against context |
| `Domain/Election/Security/TrustSnapshotAssembler.php` | Assembles trust snapshot for certification |
| `Domain/Election/Security/EvidenceClassification.php` | Evidence type classification |
| `Domain/Election/Security/EvidenceWeightCategory.php` | Weight/severity of evidence |
| `Contexts/Trust/` | Dedicated Trust bounded context with domain events |

## Trustworthiness Subsystems Identified

### 1. Replay Certification (6 files, Domain/Election/Replay/)

```
ReplaySession (aggregate)
    → evidence sealed at creation
    → assertion recorded (expected outcome + policy hash)
    → certification produced (compare expected vs actual)
    → matched = certified | mismatched = diverged

State machine:
Created → Sealed → Replayed → Certified (or Diverged)
```

**Key invariants:**
- Session used for exactly one certification cycle (state guard)
- Evidence frozen at envelope creation — no mutation
- Certification is immutable once produced
- Same evidence → identical hash across runtimes (determinism)

### 2. Evidence Framework (10+ files, Domain/Election/Security/)

| Concept | Files | Purpose |
|---------|-------|---------|
| EvidenceClassification | 1 | Type of constitutional evidence |
| EvidenceWeightCategory | 1 | Weight/severity of evidence |
| EvidenceEvaluationResult | 1 | Result of evaluating evidence |
| EvidenceEvaluationState | 1 | State machine for evidence evaluation |
| EvidenceSeverity | 1 | Severity classification |
| EvidenceSnapshot | 1 | Snapshot of evidence at a point in time |
| TrustEvidenceAggregate | 1 | Aggregate of all trust evidence |
| TrustEvaluationEnvelope | 2 | Sealed evaluation envelope |
| TrustSnapshotAssembler | 1 | Assembles trust snapshot |

### 3. Trust Policy Evaluation

```
TrustPolicyEvaluator
    → evaluates TrustCapabilityContext
    → applies trust policies
    → produces policy findings
    → feeds into CapabilityPolicy chain (layer 3)
```

### 4. Trust Context Candidate (app/Contexts/Trust/)

```
Contexts/Trust/
    Domain/
        Events/
            VerificationRevokedEvent.php
```

A dedicated bounded context for trust concerns, separate from Election Governance.

## Replay Session Lifecycle

```
ReplaySession created with evidence envelope
    → state: 'sealed'
    ↓
recordAssertion(expectedOutcome, policySequenceHash)
    → state: 'replayed'
    ↓
certify(currentOutcome)
    → if matched: state: 'certified'  → ReplayCertification(matched=true)
    → if diverged: state: 'diverged'  → ReplayCertification(matched=false)
```

## Hash Chain Architecture

```
Evidence in Envelope
    → sha256(|evidence items|) → envelopeHash
    ↓
envelopeHash + expectedOutcome + policySequenceHash + timestamp
    → sha256() → assertionHash
    ↓
sessionId + envelopeHash + expected + actual + matched + compatibility
    → sha256() → certificationHash
```

This creates an auditable hash chain from evidence → assertion → certification.

## Architectural Significance

The system has a **complete trustworthiness architecture** that goes well beyond standard election management:

| Capability | Implementation | Constitutional Value |
|-----------|---------------|---------------------|
| Deterministic replay | `ReplaySession::certify()` | Verifiability |
| Evidence sealing | `ReplayEvidenceEnvelope` | Auditability |
| Hash chain integrity | SHA256 at 3 levels | Tamper evidence |
| Schema versioning | `ReplayCompatibilityVersion` | Future-proof verification |
| Trust policy evaluation | `TrustPolicyEvaluator` | Trustworthiness |
| Separate trust context | `app/Contexts/Trust/` | Domain isolation |
| Evidence classification | `EvidenceClassification`, `EvidenceWeightCategory` | Evidence maturity |
| Capability-trust bridge | `TrustCapabilityPolicy` (layer 3 in resolver chain) | Governance → trust |

## Language: Trustworthiness Vocabulary

| Term | Source | Classification |
|------|--------|---------------|
| ReplaySession | Domain/Election/Replay/ | Aggregate Candidate |
| ReplayAssertion | Domain/Election/Replay/ | Value Object |
| ReplayCertification | Domain/Election/Replay/ | Value Object |
| ReplayEvidenceEnvelope | Domain/Election/Replay/ | Value Object |
| ReplayCompatibilityVersion | Domain/Election/Replay/ | Value Object |
| EvidenceClassification | Domain/Election/Security/ | Enum |
| EvidenceWeightCategory | Domain/Election/Security/ | Enum |
| TrustEvaluationEnvelope | Domain/Election/Security/ | Value Object |
| TrustPolicyEvaluator | App/Election/Security/ | Domain Service |
| TrustSnapshotAssembler | App/Election/Security/ | Domain Service |
| VerificationRevokedEvent | Contexts/Trust/Domain/Events/ | Domain Event |

## Conclusion

The system contains a **Constitutional Trustworthiness Architecture** that spans three layers:

1. **Replay Certification** (Domain/Election/Replay/) — verifies deterministic reproducibility of outcomes
2. **Evidence & Trust Evaluation** (Domain/Election/Security/) — classifies evidence, evaluates trust policies
3. **Trust Context Candidate** (app/Contexts/Trust/) — domain events for trust concerns. Whether Trust is a fully independent bounded context or a supporting subdomain of Election Governance requires context-mapping validation.

This is not merely an election management system. It is a **Trustworthy Constitutional Governance Platform** with verifiability, auditability, and replay certification built into its core domain.

The trustworthiness vocabulary (ReplaySession, ReplayCertification, EvidenceClassification, TrustEvaluationEnvelope) forms a **candidate second ubiquitous language** alongside the 14 constitutional governance actions. Determining whether these are one language or two collaborating languages requires context-mapping investigation before any bounded-context declarations can be made.
