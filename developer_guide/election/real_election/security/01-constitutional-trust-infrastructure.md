# Constitutional Voting Trust Infrastructure

## Overview

The Constitutional Voting Trust Infrastructure is a **replay-deterministic, sovereignty-separated** trust evaluation system for elections. It replaces scattered procedural security logic (IP blocking in controllers, fingerprint checking in middleware, attestation logic in services) with a **constitutional runtime** where participation authority is derived from immutable law and evidence, not from procedural code.

### Core Invariant

> **Only `ElectionCapabilityResolver` derives participation authority. Period.**

### Architecture Diagram

```
Evidence Collection
      ↓
SimplifiedPolicySequence     ← deterministic policy orchestration
  ├── VerificationPolicy     ← Article 6: evidence legitimacy
  ├── NetworkBindingPolicy   ← Article 1/4: IP limits & continuity
  └── DeviceBindingPolicy    ← Article 5: device continuity
      ↓
OverlaySignal                ← descriptive concern signals (influence only)
      ↓
TrustCapabilityPolicy        ← interprets evidence + overlay → capability
      ↓
ElectionCapabilityResolver   ← SOLE authority derivation point
```

## Sovereignty Dimensions

The architecture separates 4 orthogonal sovereignty dimensions:

| Dimension | Responsibility | Files |
|-----------|---------------|-------|
| **Trust** | "Can this participant be trusted?" | `SimplifiedPolicySequence`, policies, evidence VOs |
| **Authorization** | "Is the ballot workflow constitutionally valid?" | `BallotAuthorizationPolicy` |
| **Verification** | "Was officer evidence capture complete?" | `VoterVerificationPolicy` |
| **Lifecycle** | "Is the election in a valid state?" | `LifecycleCapabilityBaselinePolicy` |

Each dimension evaluates independently. Only `ElectionCapabilityResolver` converges them into a capability outcome.

## Key Concept: Overlays Influence, Never Decide

Overlays produce **descriptive signals only**. They never return allow/deny:

| Signal | Meaning | Interpretation |
|--------|---------|---------------|
| `CONCERN_PRESENT` | Anomaly pattern observed, no conclusion drawn | Neutral — delegate to evidence |
| `EVIDENCE_INCONSISTENT` | Evidence contradicts established pattern | Influence only — evidence decides |
| `ATTESTATION_AVAILABLE` | Attestation evidence available for verification | Override — constitutional review needed |

## File Map

### Domain Layer (Pure PHP — no Laravel)

```
app/Domain/Election/Security/Simplified/
├── NetworkEvidence.php                # IP hash, vote count, strategy
├── DeviceEvidence.php                 # Fingerprint hash, match type
├── VerificationEvidence.php           # Attestation required/attested
├── SessionContinuity.php              # Session IP/device continuity
├── TrustEvidenceAggregate.php         # All evidence in one boundary
├── VotingTrustResult.php              # Evaluation outcome (NOT authority)
├── ConstitutionalTrustSnapshot.php    # Read-only projection
├── ConstitutionalEvidenceSnapshot.php # Frozen replay-safe evaluation input
├── OverlaySignal.php                  # Descriptive influence signal
├── TrustEvaluationEnvelope.php        # Bundles result + signal + snapshot
├── TrustEvaluationState.php           # SUFFICIENT/INSUFFICIENT/REVIEW/INCONCLUSIVE
├── TrustLevel.php                     # Unverified/Attested/ContinuityVerified/RegistrarAttested
├── ElectionConstitutionSnapshot.php   # Immutable constitutional articles
├── ElectionConstitutionHasher.php     # Integrity hash
├── ElectionConstitutionSchema.php     # Constitutional structure validation
├── ElectionConstitutionValidator.php  # Business rule validation
├── ElectionSecurityEvent.php          # Append-only audit entry
└── BallotAuthorizationProtocol.php    # Single/dual code protocol enum

app/Domain/Election/Constitution/
└── NetworkThresholdInterpreter.php    # Pure threshold math (intdiv only)

app/Domain/Election/Security/BallotAuthorization/
├── BallotSession.php                  # Ballot session state
└── AuthorizationCode.php              # Single code state

app/Domain/Election/Security/VoterVerification/
└── VerificationSession.php            # Officer verification state
```

### Application Layer (Orchestration)

```
app/Application/Election/Security/Simplified/
├── PolicyFinding.php                  # Evidence evaluation result
├── ConstitutionalPolicy.php           # Interface: evaluate(evidence): finding
├── SimplifiedPolicySequence.php       # Deterministic orchestrator
└── Policies/
    ├── VerificationPolicy.php         # Article 6 — evidence legitimacy
    ├── NetworkBindingPolicy.php       # Article 1/4 — IP limits
    └── DeviceBindingPolicy.php        # Article 5 — device continuity

app/Application/Election/BallotAuthorization/Policies/
└── BallotAuthorizationPolicy.php      # Preconditions: ballot workflow

app/Application/Election/VoterVerification/Policies/
└── VoterVerificationPolicy.php        # Preconditions: officer verification

app/Application/Election/Capabilities/
└── Policies/
    └── TrustCapabilityPolicy.php      # Trust → CapabilityDecision (SOLE converter)
```

### Tests

```
tests/Unit/Domain/Election/Security/
├── D1DomainObjectsTest.php            # Phase D: domain object invariants
├── D3TrustPolicyEvaluationTest.php    # Phase D: policy evaluation
├── D4OverlayGovernanceTest.php        # Phase D: overlay governance
├── D5ResolverIntegrationTest.php      # Phase D: resolver integration
└── D6SovereigntyConvergenceTest.php   # Phase D: sovereignty invariants

tests/Unit/Application/Election/Security/Simplified/
├── ConstitutionalEvidenceSnapshotTest.php   # E.1 Step 0
├── PolicyFindingTest.php                     # E.1 Step 1
├── ConstitutionalPolicyTest.php              # E.1 Step 1
├── VerificationPolicyTest.php                # E.1 Step 2
├── NetworkBindingPolicyTest.php              # E.1 Step 4
├── DeviceBindingPolicyTest.php               # E.1 Step 5
├── PolicyPurityTest.php                      # E.1 Step 7
└── SimplifiedPolicySequenceTest.php          # E.1 Step 11

tests/Unit/Domain/Election/Constitution/
└── NetworkThresholdInterpreterTest.php       # E.1 Step 3

tests/Unit/Application/Election/Security/
└── SovereigntyBoundaryTest.php               # E.1 Step 7

tests/Replay/
└── ReplayDeterminismContractTest.php          # E.1 Step 8

tests/Unit/Application/Election/BallotAuthorization/
└── BallotAuthorizationPolicyTest.php         # E.1 Step 9

tests/Unit/Application/Election/VoterVerification/
└── VoterVerificationPolicyTest.php            # E.1 Step 10

tests/Unit/Application/Election/Capabilities/Policies/
└── TrustCapabilityPolicySimplifiedTest.php    # E.1 Step 12
```
