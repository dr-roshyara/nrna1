# Phase 1: Constitutional Parity Verification Specification

**Current Date:** 2026-05-26  
**Phase Status:** SPECIFICATION (awaiting approval)  
**Corrected Structures:** CapabilityParitySnapshot, ConstitutionalDivergenceType, ConstitutionalDivergenceLedger

---

## Overview

Constitutional Parity Verification proves that legacy voting system and new `ElectionCapabilityResolver` derive **identical constitutional authority decisions** across all participation legitimacy dimensions.

This is NOT code equivalence testing. This is governance verification: proving that the resolver's authority decisions are constitutionally indistinguishable from legacy decisions, allowing safe migration.

---

## Critical Invariant

> **Do NOT reconstruct legacy logic. Extract actual runtime behavior exactly.**

The `getLegacyResult()` method must capture what the legacy system **actually does at runtime**, not what the code appears to do. This prevents subtle divergences from being missed.

---

## Corrected Domain Objects

### 1. CapabilityParitySnapshot

**File:** `app/Domain/Election/Security/CapabilityParitySnapshot.php`

Eight constitutional fields, all typed with domain enums/types:

```php
readonly class CapabilityParitySnapshot
{
    public function __construct(
        public ?TrustLevel $networkLegitimate,           // Network evidence confirms participation
        public ?TrustLevel $deviceLegitimate,            // Device evidence confirms participation
        public ?TrustLevel $verificationLegitimate,      // Attestation confirms participation
        public ?TrustLevel $trustLegitimate,             // Composite: network+device+verification
        public ?string $overlayInfluence,                // OverlayInfluence enum value
        public ?string $authorizationProtocol,           // BallotAuthorizationProtocol enum value
        public ElectionLifecycleState $lifecycleState,   // Enum from election snapshot
        public string $participationAllowed,             // 'allowed' | CapabilityDenialReason->value
    ) {}

    // CORRECTION #1: All fields use TYPES, not strings
    // CORRECTION #2: equals() compares ALL 8 fields
    public function equals(self $other): bool
    {
        return $this->networkLegitimate === $other->networkLegitimate
            && $this->deviceLegitimate === $other->deviceLegitimate
            && $this->verificationLegitimate === $other->verificationLegitimate
            && $this->trustLegitimate === $other->trustLegitimate
            && $this->overlayInfluence === $other->overlayInfluence
            && $this->authorizationProtocol === $other->authorizationProtocol
            && $this->lifecycleState === $other->lifecycleState
            && $this->participationAllowed === $other->participationAllowed;
    }

    // CORRECTION #3: divergentFields() supports ledger classification
    public function divergentFields(self $other): array { ... }
}
```

**CORRECTION #3 (participationLegitimate → trustLegitimate):**
- Renamed to avoid semantic overlap with final `participationAllowed`
- `trustLegitimate`: Constitutional trust infrastructure confirms legitimacy
- `participationAllowed`: Final authority decision (can this voter vote?)
- These are DISTINCT: trust can be legitimate but lifecycle-denied

---

### 2. ConstitutionalDivergenceType

**File:** `app/Domain/Election/Security/ConstitutionalDivergenceType.php`

Typed enum (CORRECTION #4) replacing freeform strings:

```php
enum ConstitutionalDivergenceType: string
{
    // Network Legitimacy Divergences
    case NetworkBindingThresholdDifference = 'network_binding_threshold';
    case NetworkContinuityInterpretation = 'network_continuity_interpretation';
    case NetworkEvidenceHashingChange = 'network_evidence_hashing';

    // Device Legitimacy Divergences
    case DeviceFingerprintRequirementChange = 'device_fingerprint_requirement';
    // ... 15 more typed cases covering all constitutional domains

    public function article(): string { ... }      // Constitutional Article reference
    public function severity(): Severity { ... }   // Critical|High|Medium|Low|Unknown
}

enum Severity: string
{
    case Critical = 'critical';      // Constitutional principle changed
    case High = 'high';              // Core legitimacy assessment changed
    case Medium = 'medium';          // Interpretation or implementation changed
    case Low = 'low';                // Policy or timing changed
    case Unknown = 'unknown';        // Not yet classified
}
```

---

### 3. ConstitutionalDivergenceLedger

**File:** `app/Domain/Election/Security/ConstitutionalDivergenceLedger.php`

Immutable governance record with CORRECTION #4 columns:

```php
readonly class ConstitutionalDivergenceEntry
{
    public function __construct(
        private int $electionId,
        private ConstitutionalDivergenceType $divergenceType,    // Typed classification
        private Severity $severity,                              // Critical|High|Medium|Low
        private string $constitutionalArticle,                   // From enum.article()
        private string $legacyBehavior,                          // What legacy does
        private string $resolverBehavior,                        // What resolver does
        private string $resolverDecision,                        // Resolver's authority outcome
        private string $approvedBy,                              // 'system_architect'|'board_chairman'
        private ?string $rationale,
        private \DateTimeImmutable $recordedAt,
    ) {}

    // Full governance metadata accessible for dispute/audit
    public function summary(): string { ... }
}

readonly class ConstitutionalDivergenceLedger
{
    // Supports grouping by: article, election, severity, approver
    public function divergencesByArticle(): array { ... }
    public function divergencesByElection(): array { ... }
    public function criticalDivergences(): array { ... }
    public function allApprovedBy(): array { ... }
}
```

---

## Extraction Methods (CORRECTION #5)

### getLegacyResult()

Extracts **actual runtime behavior** from legacy system. NOT a reimplementation.

**Location:** `tests/Support/LegacyVotingBehavior.php`

```php
final class LegacyVotingBehavior
{
    /**
     * Execute actual legacy voting logic and capture outcome.
     *
     * CRITICAL: This method calls REAL legacy code paths, not reimplements them.
     * Purpose: Prove what the legacy system actually does.
     */
    public function getLegacyResult(
        int $electionId,
        int $userId,
        string $rawIp,
        ?string $rawFingerprint,
        bool $isVerified,
    ): CapabilityParitySnapshot {
        // Step 1: Execute actual legacy IP validation (in DemoVoteController)
        $ipCheckResult = $this->resolveIpBlock(
            election: Election::findOrFail($electionId),
            ip: $rawIp,
            userId: $userId,
        );

        // Step 2: Execute actual device validation (in VotingSecurityService)
        $deviceCheckResult = $this->evaluateDeviceLegitimacy(
            user: User::findOrFail($userId),
            fingerprint: $rawFingerprint,
            election: Election::findOrFail($electionId),
        );

        // Step 3: Execute actual verification check
        $verificationResult = $this->evaluateVerification(
            election: Election::findOrFail($electionId),
            userId: $userId,
        );

        // Step 4: Compute combined legitimacy (legacy rules)
        $trustResult = $this->compositeTrust(
            networkLegitimate: $ipCheckResult,
            deviceLegitimate: $deviceCheckResult,
            verificationLegitimate: $verificationResult,
        );

        // Step 5: Check lifecycle
        $lifecycleResult = $this->checkLifecycle(
            election: Election::findOrFail($electionId),
        );

        // Step 6: Check overlay (legacy suspension check)
        $overlayResult = $this->checkOverlay(
            election: Election::findOrFail($electionId),
        );

        // Step 7: Derive final authorization (legacy algorithm)
        $finalAuthorization = $this->deriveFinalAuthority(
            trust: $trustResult,
            lifecycle: $lifecycleResult,
            overlay: $overlayResult,
        );

        // Return snapshot capturing ALL constitutional semantics
        return new CapabilityParitySnapshot(
            networkLegitimate: $ipCheckResult,
            deviceLegitimate: $deviceCheckResult,
            verificationLegitimate: $verificationResult,
            trustLegitimate: $trustResult,
            overlayInfluence: $overlayResult,
            authorizationProtocol: 'single_code',  // legacy uses this
            lifecycleState: Election::findOrFail($electionId)->lifecycle_state,
            participationAllowed: $finalAuthorization ? 'allowed' : 'trust_denied',
        );
    }

    // Private methods calling ACTUAL legacy code
    private function resolveIpBlock(Election $election, string $ip, int $userId): ?TrustLevel
    {
        // Call ACTUAL legacy method from DemoVoteController
        // Return TrustLevel extracted from controller's response
    }

    private function evaluateDeviceLegitimacy(...): ?TrustLevel
    {
        // Call ACTUAL legacy device fingerprint logic
        // Return TrustLevel based on device legitimacy
    }

    // ... more methods extracting actual behavior
}
```

### getResolverResult()

Extracts constitutional semantics from `ElectionCapabilityResolver`.

```php
final class ResolverVotingBehavior
{
    public function getResolverResult(
        int $electionId,
        int $userId,
        string $rawIp,
        ?string $rawFingerprint,
        bool $isVerified,
    ): CapabilityParitySnapshot {
        $election = Election::findOrFail($electionId);
        $user = User::findOrFail($userId);

        // Step 1: Run TrustPolicyEvaluator (the resolver's trust pipeline)
        $envelope = app(TrustPolicyEvaluator::class)->evaluate(
            election: $election,
            user: $user,
            rawIp: $rawIp,
            rawFingerprint: $rawFingerprint,
            sessionId: 'test_session',
        );

        // Step 2: Extract constitutional semantics from envelope
        $snapshot = $envelope->snapshot;  // ConstitutionalTrustSnapshot

        // Step 3: Build CapabilityContext for resolver
        $context = new CapabilityContext(
            election: $election,
            user: $user,
            action: 'vote',
            actionMetadata: [],
            state: $election->lifecycleState(),
            trust: $envelope,
        );

        // Step 4: Run ElectionCapabilityResolver
        $capabilitySnapshot = app(ElectionCapabilityResolver::class)->evaluate($context);

        // Step 5: Extract CapabilityParitySnapshot from resolver result
        return new CapabilityParitySnapshot(
            networkLegitimate: $this->extractNetworkLegitimacy($snapshot),
            deviceLegitimate: $this->extractDeviceLegitimacy($snapshot),
            verificationLegitimate: $this->extractVerificationLegitimacy($snapshot),
            trustLegitimate: $snapshot->trustLevel,
            overlayInfluence: $this->extractOverlayInfluence($envelope->overlayInfluence),
            authorizationProtocol: $snapshot->authorizationProtocol,
            lifecycleState: $capabilitySnapshot->lifecycleState,
            participationAllowed: $capabilitySnapshot->capabilities['vote'] ? 'allowed' : $this->extractDenialReason($capabilitySnapshot),
        );
    }

    private function extractNetworkLegitimacy(ConstitutionalTrustSnapshot $snapshot): ?TrustLevel
    {
        // Extract from snapshot fields populated by NetworkBindingPolicy
    }

    // ... more extraction methods
}
```

---

## Constitutional Scenario Matrix

**All parity tests use this matrix to ensure comprehensive coverage.**

### Dimensions (6 axes)

| Dimension | Values | Combinations |
|-----------|--------|--------------|
| Network Legitimacy | None, Attested, Unverified, RegistrarAttested | 4 |
| Device Legitimacy | None, Match, NoMatch | 3 |
| Verification Legitimacy | None, Satisfied, Revoked | 3 |
| Overlay Influence | None, ReviewRequired, Inconclusive, ElevationRequest | 4 |
| Authorization Protocol | SingleCode, DualCode | 2 |
| Lifecycle State | VotingActive, Suspended, Completed | 3 |

**Total scenario combinations: 4 × 3 × 3 × 4 × 2 × 3 = 432 combinations**

### Priority Scenarios (Must test)

**CRITICAL PATHS (Approval Required):**
1. **Happy Path** — All legitimate, no overlay, voting active, single-code → `allowed`
2. **Emergency Suspension** — Valid trust, suspended lifecycle, no overlay → `suspended` (lifecycle denial)
3. **Overlay Review** — Valid trust, active lifecycle, review-required overlay → `constitutional_review_pending`
4. **Trust Denied** — Network limit exceeded, trust becomes inconclusive → `trust_evaluation_inconclusive`
5. **Dual Authorization** — Split protocol, both view+commit required → protocol enforced in snapshot

**DIVERGENCE DETECTION PATHS (Core parity test):**
6. **Network Threshold Change** — IP vote count limit differs between legacy and resolver
7. **Device Volatility Assessment** — Fingerprint stability interpreted differently
8. **Verification Revocation** — Attestation revocation semantics differ
9. **Overlay Priority** — Overlay ordering produces different final outcome
10. **Trust Composition** — Network+device+verification combined differently

---

## Parity Test Structure

### Test Template

```php
class ConstitutionalParityTest extends TestCase
{
    use RefreshDatabase;

    public function test_parity_for_scenario(
        ?TrustLevel $networkLegitimate,
        ?TrustLevel $deviceLegitimate,
        ?TrustLevel $verificationLegitimate,
        ?string $overlayInfluence,
        string $authorizationProtocol,
        ElectionLifecycleState $lifecycleState,
    ): void {
        $election = Election::factory()->create([
            'ballot_authorization_protocol' => $authorizationProtocol,
        ]);
        $user = User::factory()->create();

        // Get legacy result (what legacy system does)
        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_hash_123',
            isVerified: $verificationLegitimate !== null,
        );

        // Get resolver result (what resolver does)
        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_hash_123',
            isVerified: $verificationLegitimate !== null,
        );

        // Parity check: must be identical
        if (!$legacyResult->equals($resolverResult)) {
            $divergences = $legacyResult->divergentFields($resolverResult);
            $this->divergenceLedger->addEntry(
                electionId: $election->id,
                type: $this->classifyDivergence($divergences),
                legacyBehavior: $legacyResult->participationAllowed,
                resolverBehavior: $resolverResult->participationAllowed,
                approvedBy: 'system_test_harness',
                rationale: "Scenario: {$networkLegitimate}, {$deviceLegitimate}, {$verificationLegitimate}, {$overlayInfluence}, {$authorizationProtocol}, {$lifecycleState}",
            );
        }

        // Assertion: they must match (or divergence must be approved)
        $this->assertTrue(
            $legacyResult->equals($resolverResult),
            "Parity failed for scenario: " . implode(', ', $divergences ?? []),
        );
    }
}
```

---

## Expected Divergences

Based on architectural improvements in D.5, these divergences are **expected and approved**:

| Type | Classification | Approval | Rationale |
|------|---|---|---|
| Network binding hashed | `NetworkEvidenceHashingChange` | APPROVED | Privacy improvement (raw IPs never stored) |
| Device volatility semantics | `DeviceContinuitySemantics` | APPROVED | More precise stability assessment |
| Overlay priority ordering | `OverlayPriorityOrdering` | APPROVED | Constitutional stratification (Emergency→Governance→Operational) |
| Verification revocation scope | `VerificationRevocationSemantics` | APPROVED | Session-scoped vs election-scoped authority |

**Any other divergence triggers CRITICAL severity and requires manual investigation.**

---

## Execution Plan

1. **Create Test Classes** (~2 hours)
   - `ConstitutionalParityTest.php` — main test matrix
   - `LegacyVotingBehavior.php` — extraction from legacy
   - `ResolverVotingBehavior.php` — extraction from resolver

2. **Generate Scenarios** (~1 hour)
   - Parametrized tests covering 432 combinations
   - Focus first on 10 critical paths
   - Expand to full matrix after critical paths pass

3. **Run Parity Verification** (~3 hours)
   - Execute all scenarios
   - Collect divergences into ledger
   - Classify each divergence with ConstitutionalDivergenceType
   - Verify all divergences are approved (not regressions)

4. **Documentation** (~1 hour)
   - Generate governance report
   - List critical vs low-severity divergences
   - Sign off on parity verification

---

## Success Criteria

✅ **All 10 critical path scenarios pass parity** — Happy path, suspension, overlay, trust denial, dual auth, and 5 divergence scenarios
✅ **All divergences are approved and typed** — No unclassified divergences remain
✅ **No regressions detected** — All divergences are expected improvements (not bugs)
✅ **Parity ledger is signed** — Governance authority approval on record
✅ **10 senior-architect corrections all applied** — From D.5 review + Phase 1 corrections

---

## Next Steps

After parity verification passes:
1. **Phase 2: Resolver Wiring** — Integrate TrustPolicyEvaluator into AppServiceProvider
2. **Phase 3: Controller Integration** — Wire TrustEvaluationEnvelope into voting flow
3. **Phase 4: Invariant Hardening** — Add regression tests preventing distributed authority
4. **Phase 5: D.6 Legacy Removal** — Delete resolveIpBlock(), evaluateIpCount(), ValidateVotingIp middleware

---

**Status:** AWAITING APPROVAL (5 corrections applied, ready for implementation)
