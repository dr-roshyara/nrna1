# Trust Evaluation Flow

## Complete Evaluation Pipeline

```
┌─────────────────────────────────────────────────────────┐
│ 1. Evidence Collection (Controller/Infrastructure)      │
│    - IP hash (SHA-256 of normalized IP)                 │
│    - Device fingerprint hash (SHA-256)                   │
│    - Session continuity state                            │
│    - Attestation status                                  │
└────────────────────────┬────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ 2. ConstitutionalEvidenceSnapshot (Frozen)               │
│    Freezes ALL evidence + constitution at evaluation     │
│    start. Same input → same output, always.              │
└────────────────────────┬────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ 3. SimplifiedPolicySequence                              │
│    Runs 3 policies in constitutional order:              │
│                                                          │
│    VerificationPolicy (Article 6)                        │
│      └── passed? ──NO──→ INSUFFICIENT_EVIDENCE [STOP]    │
│      └── YES                                              │
│                                                          │
│    NetworkBindingPolicy (Article 1/4)                    │
│      └── passed? ──NO──→ INSUFFICIENT_EVIDENCE [STOP]    │
│      └── YES                                              │
│                                                          │
│    DeviceBindingPolicy (Article 5)                       │
│      └── passed? ──NO──→ INSUFFICIENT_EVIDENCE [STOP]    │
│      └── YES → SUFFICIENT_EVIDENCE                       │
└────────────────────────┬────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ 4. OverlayCoordinator                                    │
│    Evaluates overlays → produces descriptive signal:     │
│    - CONCERN_PRESENT (neutral)                           │
│    - EVIDENCE_INCONSISTENT (influence only)              │
│    - ATTESTATION_AVAILABLE (override)                    │
└────────────────────────┬────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ 5. TrustCapabilityPolicy                                 │
│    Interprets evaluation state + overlay signal:         │
│                                                          │
│    ATTESTATION_AVAILABLE → ConstitutionalReviewPending   │
│    SUFFICIENT_EVIDENCE    → abstain (let others decide)  │
│    INSUFFICIENT_EVIDENCE  → TrustDenied                  │
│    REVIEW_REQUIRED        → ConstitutionalReviewPending  │
│    INCONCLUSIVE           → TrustEvaluationInconclusive  │
└────────────────────────┬────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ 6. ElectionCapabilityResolver                            │
│    SOLE authority derivation point                       │
│    Combines: trust + authorization + lifecycle + overlay │
└─────────────────────────────────────────────────────────┘
```

## Policy Details

### VerificationPolicy (Article 6)

Evaluates whether verification evidence satisfies constitutional requirements.

| Condition | Outcome |
|-----------|---------|
| `verificationRequired = false` | passed (verification not needed) |
| `verificationRequired = true, attested = true` | passed |
| `verificationRequired = true, attested = false` | failed |

**Returns:** `PolicyFinding(passed, constitutionalBasis, policyIdentifier, supportingFacts)`

### NetworkBindingPolicy (Article 1/4)

Evaluates IP/network evidence against constitutional binding strategy.

| Strategy | Pass Condition | Constitutional Basis |
|----------|---------------|---------------------|
| `none` | Always passes | "Network binding not required by Article 1" |
| `ip_count` | `votesFromThisIp <= maxVotesPerIp` | "IP vote count within constitutional limit per Article 1" |
| `ip_strict` | `currentIpHash === ipHashAtStart` | "IP continuity preserved per Article 4" |

**Note:** This policy observes evidence only. Threshold math (trust-level-adjusted limits) is handled by `NetworkThresholdInterpreter` in the Constitution domain.

### DeviceBindingPolicy (Article 5)

Evaluates device fingerprint evidence against constitutional strategy.

| Strategy | Pass Condition |
|----------|---------------|
| `none` | Always passes |
| `fingerprint_required` | `matchType === 'exact_match'` |

### Short-Circuit Rules

The `SimplifiedPolicySequence` short-circuits on first failure, but preserves the **full causality chain**:

```
verification_policy  → passed
network_binding_policy → failed (IP vote count exceeds constitutional limit)
device_binding_policy  → NOT EVALUATED (short-circuited)
```

The `policyOutcomeSequence` in `VotingTrustResult` captures which policies ran and their outcomes, enabling replay audit.

## Overlay Descriptive Signals

Overlays use descriptive state observations, NOT procedural verbs:

| Old Name | New Name | Meaning |
|----------|----------|---------|
| `CONTINUE` | `CONCERN_PRESENT` | Anomaly pattern observed but no conclusion drawn |
| `ELEVATE_TRUST` | `EVIDENCE_INCONSISTENT` | Evidence received contradicts established pattern |
| `REQUIRE_REVERIFICATION` | `ATTESTATION_AVAILABLE` | Attestation evidence is available for verification |

This is the **descriptive vs prescriptive** distinction: overlays describe what they found, not what action to take. The Resolver interprets descriptions into action.

## TrustLevel Derivation

`TrustLevel` is NOT derived by `SimplifiedPolicySequence`. The sequence returns `TrustLevel::Unverified` as a **placeholder**. Only the Resolver (`TrustCapabilityPolicy` inside `ElectionCapabilityResolver`) derives the actual trust level from aggregated policy findings.

This prevents orchestration from becoming sovereign.

## Replay Determinism

The evaluation pipeline is fully replay-deterministic:

1. **Same `ConstitutionalEvidenceSnapshot`** → same `PolicyFinding` from every policy, every time
2. **Same `ElectionConstitutionSnapshot`** → same constitutional hash every time
3. **Policy evaluation order is frozen** (verification → network → device) and verified by contract tests

Tests in `tests/Replay/ReplayDeterminismContractTest.php` prove this by running each policy 3+ times with identical input and asserting identical output.
