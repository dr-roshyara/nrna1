# Migration Strategy & Testing Guide

## Strangler Fig Migration

Phase E.1 follows the **strangler fig** pattern. The old pipeline remains authoritative while the new constitutional pipeline runs alongside it:

```
PHASE 1: OLD AUTHORITATIVE (current — Phase D/E.1)
┌──────────────────────┐   ┌──────────────────────┐
│  OLD Pipeline        │   │  NEW Pipeline        │
│  (controllers,       │   │  (constitutional     │
│   middleware,         │   │   policies)           │
│   services)          │   │                       │
│   ═══ AUTHORITATIVE  │   │   ═══ SHADOW          │
│                      │   │                       │
│  Voting decisions    │   │  Logs divergence      │
│  come from here      │   │  only — no effect     │
└──────────────────────┘   └──────────────────────┘
        ↓                           ↓
  ┌──────────┐             ┌──────────────┐
  │ PRODUCTION│            │ DETERMINISTIC │
  │ BEHAVIOR  │            │ PARITY LOG    │
  └──────────┘             └──────────────┘

PHASE 2: PARITY VALIDATION
  Compare OLD vs NEW outcomes for every voting action
  When 100% deterministic parity confirmed → CUTOVER

PHASE 3: NEW AUTHORITATIVE
┌──────────────────────┐
│  NEW Pipeline        │
│   ═══ AUTHORITATIVE  │
│                       │
│  OLD pipeline         │
│  removed or inert     │
└──────────────────────┘
```

**No controller/middleware/service changes during implementation.** The NEW pipeline runs in shadow (logged but not authoritative). Only after parity is proven does cutover occur.

## Constitutional Vocabulary Doctrine

Every layer has specific vocabulary. Violations cause semantic sovereignty leakage:

| Layer | Allowed | Forbidden |
|-------|---------|-----------|
| **Evidence** | `ipHash`, `fingerprintHash`, `attested`, `votesFromThisIp` | `passed`, `denied`, `trusted` |
| **Policy** | `PolicyFinding(passed, constitutionalBasis, supportingFacts)` | `allow()`, `deny()`, `TrustLevel` |
| **Overlay** | `CONCERN_PRESENT`, `EVIDENCE_INCONSISTENT`, `ATTESTATION_AVAILABLE` | `ALLOW`, `DENY`, `ELEVATE_TRUST` |
| **ThresholdInterpreter** | `TrustLevel`, `int` threshold | `PolicyFinding`, `allow/deny` |
| **Resolver** | `CapabilityDecision(allow/prohibited/abstain)` | `TrustLevel`, `passed` |
| **Snapshot** | serialized evidence + evaluation state | behavioral methods, allow/deny |
| **Controller** | collect evidence, invoke resolver, render response | `computeTrust()`, `checkIp()` |
| **Event Ledger** | `recordedAt`, `evaluationState`, `trustLevelBefore/After` | allow/deny, capability |

Enforced by `SovereigntyBoundaryTest` and `PolicyPurityTest`.

## Architectural Fitness Functions

### SovereigntyBoundaryTest

Tests in `tests/Unit/Application/Election/Security/SovereigntyBoundaryTest.php`:

| Test | What It Enforces |
|------|-----------------|
| `test_policies_never_allow_or_deny` | Policies don't have allow()/deny() methods |
| `test_overlays_never_return_authority_types` | Overlays don't return CapabilityDecision |
| `test_simplified_policies_never_reference_capability` | Policies don't import CapabilityDecision |
| `test_simplified_policies_never_reference_evaluation_state` | Policies don't import TrustEvaluationState |
| `test_policy_finding_has_no_trust_level` | PolicyFinding doesn't carry TrustLevel |
| `test_simplified_policies_have_minimal_public_api` | Only evaluate() is public |
| `test_domain_never_imports_application` | Domain is pure PHP |

### PolicyPurityTest

Tests in `tests/Unit/Application/Election/Security/Simplified/PolicyPurityTest.php`:

| Test | What It Enforces |
|------|-----------------|
| `test_policies_do_not_import_eloquent` | No Illuminate imports |
| `test_policies_do_not_use_authority_methods` | No allow/deny/grant/authorize/permit |
| `test_policies_have_unique_identifiers` | No duplicate policyIdentifier values |
| `test_all_policies_implement_constitutional_policy` | Implements ConstitutionalPolicy interface |
| `test_policies_do_not_return_boolean_or_trust_level` | Returns PolicyFinding, not bool/TrustLevel |
| `test_policy_file_name_matches_class` | PSR-4 compliance |

### ReplayDeterminismContractTest

Tests in `tests/Replay/ReplayDeterminismContractTest.php`:

| Test | What It Proves |
|------|---------------|
| `test_verification_policy_replay_determinism` | Same input → same output × 3 runs |
| `test_network_binding_policy_replay_determinism` | Same input → same output × 3 runs |
| `test_device_binding_policy_replay_determinism` | Same input → same output × 3 runs |
| `test_all_policies_deterministic_end_to_end` | All 3 policies deterministic simultaneously |
| `test_identical_snapshot_produces_identical_hash` | Same constitution → same hash |
| `test_different_snapshot_produces_different_hash` | Different constitution → different hash |
| `test_hash_is_deterministic_across_calls` | 5 consecutive hash calls → same value |
| `test_policy_dependency_order_is_acyclic` | No circular dependencies |
| `test_policy_evaluation_order_is_stable` | Order is verification → network → device |
| `test_evidence_snapshot_is_immutable` | Readonly class enforced |

## Running Tests

```bash
# Run ALL Phase D + Phase E.1 tests
php artisan test --env=testing

# Targeted test suites
php artisan test --env=testing tests/Replay/
php artisan test --env=testing --filter=SovereigntyBoundaryTest
php artisan test --env=testing --filter=PolicyPurityTest
php artisan test --env=testing --filter=SimplifiedPolicySequence
php artisan test --env=testing --filter=BallotAuthorization
php artisan test --env=testing --filter=VoterVerification
php artisan test --env=testing --filter=TrustCapability
php artisan test --env=testing --filter=NetworkThresholdInterpreter

# Core security tests (D1-D6)
php artisan test --env=testing tests/Unit/Domain/Election/Security/
```

## Adding a New Policy

1. **Create the test** (TDD: Red → Green → Refactor)
2. **Implement `ConstitutionalPolicy` interface** in `app/Application/Election/Security/Simplified/Policies/`
3. **Return `PolicyFinding`** — never bool, never `TrustLevel`, never `CapabilityDecision`
4. **Register in `SimplifiedPolicySequence`** constructor
5. **Add to `PolicyPurityTest`** `$policyClasses` array
6. **Add replay determinism test** to `ReplayDeterminismContractTest`

## Phase E.1 Completion Conditions (Verified)

- [x] `ConstitutionalEvidenceSnapshot` freezes all evaluation inputs
- [x] `PolicyFinding` carries evidence facts only (no TrustLevel, no allow/deny)
- [x] All simplified policies implement `ConstitutionalPolicy` interface
- [x] `SimplifiedPolicySequence` short-circuits with causality chain preserved
- [x] Overlay signals use descriptive names (CONCERN_PRESENT, EVIDENCE_INCONSISTENT, ATTESTATION_AVAILABLE)
- [x] `BallotAuthorizationPolicy` evaluates independently of trust
- [x] `VoterVerificationPolicy` never returns authorized
- [x] `TrustCapabilityPolicy` handles both old and simplified overlay signals
- [x] `PolicyPurityTest` and `SovereigntyBoundaryTest` enforce vocabulary doctrine
- [x] Replay determinism proven by contract tests

## Files Created/Modified (Phase E.1)

```
NEW — Domain Layer:
  app/Domain/Election/Security/Simplified/ConstitutionalEvidenceSnapshot.php
  app/Domain/Election/Security/BallotAuthorization/AuthorizationCode.php
  app/Domain/Election/Security/BallotAuthorization/BallotSession.php
  app/Domain/Election/Security/VoterVerification/VerificationSession.php
  app/Domain/Election/Constitution/NetworkThresholdInterpreter.php

NEW — Application Layer:
  app/Application/Election/Security/Simplified/PolicyFinding.php
  app/Application/Election/Security/Simplified/ConstitutionalPolicy.php
  app/Application/Election/Security/SimplifiedPolicySequence.php
  app/Application/Election/Security/Simplified/Policies/VerificationPolicy.php
  app/Application/Election/Security/Simplified/Policies/NetworkBindingPolicy.php
  app/Application/Election/Security/Simplified/Policies/DeviceBindingPolicy.php
  app/Application/Election/BallotAuthorization/Policies/BallotAuthorizationPolicy.php
  app/Application/Election/VoterVerification/Policies/VoterVerificationPolicy.php

NEW — Tests:
  tests/Unit/Domain/Election/Constitution/NetworkThresholdInterpreterTest.php
  tests/Unit/Application/Election/Security/Simplified/ConstitutionalEvidenceSnapshotTest.php
  tests/Unit/Application/Election/Security/Simplified/PolicyFindingTest.php
  tests/Unit/Application/Election/Security/Simplified/ConstitutionalPolicyTest.php
  tests/Unit/Application/Election/Security/Simplified/VerificationPolicyTest.php
  tests/Unit/Application/Election/Security/Simplified/NetworkBindingPolicyTest.php
  tests/Unit/Application/Election/Security/Simplified/DeviceBindingPolicyTest.php
  tests/Unit/Application/Election/Security/Simplified/PolicyPurityTest.php
  tests/Unit/Application/Election/Security/Simplified/SimplifiedPolicySequenceTest.php
  tests/Unit/Application/Election/Security/SovereigntyBoundaryTest.php
  tests/Unit/Application/Election/BallotAuthorization/BallotAuthorizationPolicyTest.php
  tests/Unit/Application/Election/VoterVerification/VoterVerificationPolicyTest.php
  tests/Unit/Application/Election/Capabilities/Policies/TrustCapabilityPolicySimplifiedTest.php
  tests/Replay/ReplayDeterminismContractTest.php

MODIFIED:
  app/Domain/Election/Security/Simplified/OverlaySignal.php (descriptive rename)
  app/Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php (simplified overlay path)
  phpunit.xml (added Replay testsuite)
```
