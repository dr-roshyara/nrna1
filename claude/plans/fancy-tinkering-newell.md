# Phase D — Constitutional Voting Trust Infrastructure
**Plan File:** `fancy-tinkering-newell.md`
**Source authority:** `architecture/election/security/20260525_2340_ip_protection_new_architecture_plan.md`
**TDD-first. All tests run via `php artisan test --env=testing`.**
**Estimated timeline: ~12 days across 7 phases (D.0–D.6).**

---

## Context

IP/device/code security rules currently live scattered across:
- `ElectionVotingController::resolveIpBlock()` / `evaluateIpCount()`
- `ValidateVotingIp` middleware
- `VotingSecurityService`
- `ElectionSettingsService`

These are procedural, bypassable, non-sovereign, controller-scattered. Per the constitutional architecture doc, these must become **constitutional trust policies** integrated as a policy family inside `ElectionCapabilityResolver`. This is not security hardening — it is **constitutional participation legitimacy infrastructure**.

**What this plan does:** Model voting trust as constitutional domain objects with enforced policies integrated into the ONE authority pipeline.

**What this plan does NOT do:** Remove existing working controller logic until policies are proven by passing tests (Phase D.6 is the final cleanup step).

---

## Constitutional Authority Invariant (ONE RESOLVER)

```
Constitution
  → ElectionCapabilityResolver      ← sovereign authority derivation
    → TrustPolicyEvaluator          ← orchestration only (3 injected components)
      → OverlayCoordinator          ← overlay ordering only
      → PolicySequence              ← policy evaluation order only
      → SecurityEventRecorder       ← immutable recording + sampling
    → ConstitutionalTrustSnapshot   ← provenance-aware projection
  → ElectionCapabilitySnapshot      ← consumed by controllers / frontend / middleware
```

Consumers NEVER call `TrustPolicyEvaluator` directly — they read from `ElectionCapabilitySnapshot.trust`. This prevents distributed sovereignty.

**Deferred to Phase D+:** Snapshot decomposition into `TrustProjection`, `ProtocolProjection`, etc. — YAGNI for now.

---

## Threat Models (Two Separate Classes)

### Technical Attack Threats
| Threat | Defended By |
|---|---|
| Shared-device fraud | Device fingerprint attestation |
| Mass IP voting | IP velocity overlays + count policies |
| Replay attacks | `CommitAuthorizationFreshness` (single-use tokens) |
| VPN/proxy masking | Network evidence as evidence, not identity |
| Session hijack | Trust continuity semantics |
| Credential sharing | Dual-protocol authorization |

### Constitutional Governance Threats
| Threat | Defended By |
|---|---|
| Registrar abuse | Immutable attestation audit trail |
| Unlawful denial | Trust snapshot provenance (auditable in disputes) |
| Unequal participation | Trust elevation semantics (not bypass) |
| Emergency suspension abuse | Overlay precedence hierarchy + constitution still governs |
| Disenfranchisement via over-binding | Multi-device transition acknowledgment (Phase D+) |

---

## TDD Rules

- Run: `php artisan test --env=testing`
- Test files: `tests/Feature/Election/Security/` and `tests/Unit/Domain/Election/Security/`
- Pattern: **Red → Green → Refactor** — failing test before implementation, always
- Preserve all **17/17 currently passing** tests
- Eliminate **5 skipped enforcement tests** in `tests/Feature/Election/VoterVerificationTest.php`
- Eliminate **8 skipped legacy tests** in `tests/Feature/Voting/IpValidationTest.php`

---

## Phase D.0 — Trust Sovereignty Modeling (Vocabulary Lock) — 1 day

No files created. Canonical vocabulary locked here — all phases must respect it.

### Architectural Rules (Locked)

**1. ONE resolver, multiple policy families.** No separate bounded-context engines.

**2. IP addresses are network evidence, NOT identity primitives.** Use `NetworkTrustEvidence` + `satisfiesNetworkAttestation()`.

**3. Device fingerprints are binary attestation evidence.** Use `FingerprintMatchType` enum — no float confidence.

**4. SplitAuthorizationProtocol has TWO constitutionally distinct sovereign actions:**
- **Ballot viewing authority** (view token) — the right to see the ballot
- **Ballot commit authority** (commit token) — the sovereign act of casting
- These are constitutionally distinct and future capabilities will model them separately.

**5. Verification records are registrar attestation artifacts** — not merely `verified = true`.

**6. Trust elevation is NOT bypass. Explicit thresholds:**
- `registrar_attested` → `floor(maxVotesPerIp * 2)`
- `continuity_verified` → `floor(maxVotesPerIp * 1.5)`
- `attested` → `maxVotesPerIp`
- `unverified` → `max(1, floor(maxVotesPerIp / 2))`

**7. Trust vocabulary is attestation-oriented — use enum, not strings:**

```php
enum TrustLevel: string {
    case Unverified = 'unverified';
    case Attested = 'attested';
    case ContinuityVerified = 'continuity_verified';
    case RegistrarAttested = 'registrar_attested';
}
```

This is constitutional vocabulary, not arbitrary strings. Critical for federation, dispute replay, overlay precedence, and policy composition later.

**8. Overlays influence trust dimensions — NOT constitutional legality.** Even `RegistrarOverrideOverlay` must not bypass lifecycle, participation eligibility, or emergency suspension.

**9. Privacy-first evidence storage (GDPR minimum):** Raw IP → hashed. Fingerprint → hashed + salted. No raw evidence in security events. Add `retention_days` column — full legal lifecycle policy deferred to compliance review.

**10. Trust has a lifecycle — validity is scoped via `TrustValidityScope` enum** (see D.1).

**11. Session continuity boundaries:** Preserved when same session + same network hash. Re-attestation triggers on network change. Invalidated on commitment completed or election close.

**12. Multi-device participation is acknowledged — not over-constrained.** Architecture must permit `AuthorizedTrustTransition` (supervised terminals, accessibility devices, phone+desktop flows) without rework. Implementation deferred to Phase D+.

**13. Replay protection semantics:** Commit tokens are single-use. Stale tokens rejected via `CommitAuthorizationFreshness`. View token + commit token must be linked in dual-code mode.

### Performance Governance Notes (Implementation Guidelines)

- **Trust recomputation:** Recompute on every capability check. No cross-request caching.
- **Event storage growth:** ~2–3 events per vote for 10,000 votes ≈ ~50MB JSON. Acceptable for audit.
- **Overlay burst handling:** `IpVelocityOverlay` queries last 5 minutes only. Composite index on `(election_id, recorded_at)` required.
- **Replay verification cost:** Commit token lookup O(log n) with index on `(election_id, commit_token_hash)`.
- **Event sampling:** DENY events always recorded. ALLOW events sampled at ~10% or queued.

### Additional Enum: OverlayEffect (Deferred Architecture Boundary)

Future overlays may produce different effects than simple elevation/denial. Add placeholder enum for future implementation:

```php
enum OverlayEffect: string {
    case Deny = 'deny';
    case ElevateTrust = 'elevate_trust';
    case RequireReview = 'require_review';           // deferred
    case RestrictContinuity = 'restrict_continuity'; // deferred
    case ForceReAttestation = 'force_reattestion';   // deferred
}
```

Current phase uses only `Deny` and `ElevateTrust`. Anchors architecture for future overlay semantics.

### Additional Vocabulary: AuthorizedTrustTransition (Deferred)

Multi-device participation scenarios (embassy terminals, accessibility terminals, supervised voting stations, phone verification + desktop voting) require constitutional trust migration semantics. Not implemented in Phase D — only vocabulary reserved. This future-proofs architecture against over-binding voters to single devices.

### Overlay Governance Authority (Deferred Constitutional Semantics)

**Important distinction:** Overlay activation authority (especially `EmergencyElectionSuspensionOverlay`) is NOT merely external operational policy. It is **deferred constitutional governance modeling**. Future phases must model:
- Activation legitimacy authority
- Justification requirements
- Reviewability / appeal process
- Revocation authority
- Constitutional accountability

Do NOT externalize emergency governance from the constitutional model. Phase D defers this modeling but reserves it as constitutional authority semantics.

### Federation Trust Boundary

Explicitly document: **Trust provenance is election-scoped constitutional lineage, NOT globally portable federation identity trust.**

A voter's `registrar_attested` trust level within election X does NOT automatically mean their trust level propagates to election Y under a different registrar. Each election's trust derivation is sovereign.

### Constitutional Clock and Event Ordering (Deferred)

Current `recordedAt` timestamp is sufficient for Phase D. However, future phases may require constitutional causality semantics:
- Monotonic event ordering within authority derivation
- Authority epoch references for dispute replay
- Causality replay boundaries for appeals
- Federation event synchronization

No implementation required now. Acknowledge future direction.

**Deferred to Phase D+:** Constitutional dispute semantics (constitutionalBasis, authorityLineageReference), emergency governance process full modeling, full GDPR retention lifecycle, ConstitutionalTrustSnapshot decomposition (TrustProjection, ProtocolProjection, OverlayProjection, AttestationProjection).

---

## Phase D.1 — Security Sovereignty Vocabulary (Domain Objects) — 2 days

Pure PHP in `app/Domain/Election/Security/`. Zero Laravel dependencies.

### `TrustLevel.php`
```php
enum TrustLevel: string {
    case Unverified = 'unverified';
    case Attested = 'attested';
    case ContinuityVerified = 'continuity_verified';
    case RegistrarAttested = 'registrar_attested';
}
```

Constitutional vocabulary enum — not arbitrary strings. Used throughout trust evaluation, policies, and snapshot projection.

### `TrustValidityScope.php`
```php
enum TrustValidityScope: string {
    case ElectionScoped        = 'election_scoped';   // Expires when election closes
    case SessionScoped         = 'session_scoped';    // Expires on session end
    case DeviceScoped          = 'device_scoped';     // Tied to fingerprint stability
    case OverlayScoped         = 'overlay_scoped';    // Tied to overlay being active
    case ManualClearRequired   = 'manual_clear';      // Registrar must revoke explicitly
}
```

### `OverlayEffect.php` (Deferred Implementation — Architecture Placeholder)
```php
enum OverlayEffect: string {
    case Deny = 'deny';                              // implemented in Phase D
    case ElevateTrust = 'elevate_trust';            // implemented in Phase D
    case RequireReview = 'require_review';           // deferred for Phase D+
    case RestrictContinuity = 'restrict_continuity'; // deferred for Phase D+
    case ForceReAttestation = 'force_reattestion';   // deferred for Phase D+
}
```

Current phase uses only `Deny` and `ElevateTrust`. Enum anchors architecture for future overlay complexity without modification.

### `NetworkTrustEvidence.php`
```php
readonly class NetworkTrustEvidence {
    public function __construct(
        public string  $currentIpHash,       // SHA-256 normalized hash, NOT raw IP
        public ?string $registeredIpHash,    // hashed at attestation time
        public ?array  $whitelist,           // CIDR array
        public int     $maxVotesPerIp,
        public int     $votesFromThisIp,
        public bool    $restrictionEnabled,
        public string  $bindingStrategy,    // 'none'|'ip_count'|'ip_strict'|'whitelist_only'
    ) {}
    public function isWhitelisted(): bool
    public function exceedsLimit(TrustLevel $trustLevel): bool   // trust-elevated thresholds
    public function satisfiesNetworkAttestation(): bool
    public function remainingVotes(TrustLevel $trustLevel): int
    private function effectiveLimit(TrustLevel $trustLevel): int
}
```

### `FingerprintMatchType.php` + `DeviceTrustContext.php`
```php
enum FingerprintMatchType: string {
    case EXACT_MATCH   = 'exact';
    case NO_MATCH      = 'no_match';
    case NOT_REQUIRED  = 'not_required';
}

readonly class DeviceTrustContext {
    public function __construct(
        public ?string              $fingerprintHash,
        public ?string              $registeredFingerprintHash,
        public FingerprintMatchType $matchType,
        public string               $captureMethod,  // 'browser_api'|'canvas'|'none'
        public string               $volatility,     // 'stable'|'semi_stable'|'volatile'
    ) {}
    public function satisfiesDeviceAttestation(): bool  // EXACT_MATCH || NOT_REQUIRED
    public function isVolatile(): bool
}
```

### `VerificationAttestationRecord.php`
```php
readonly class VerificationAttestationRecord {
    public function __construct(
        public bool               $required,
        public bool               $attested,
        public ?string            $registrarId,
        public ?\DateTimeImmutable $attestationTimestamp,
        public string             $protocol,            // 'none'|'ip_only'|'fingerprint_only'|'both'
        public ?string            $networkEvidenceHash, // minimized hash at attestation
        public ?string            $deviceEvidenceHash,
        public bool               $revoked,
        public TrustValidityScope $validityScope,
    ) {}
    public function isSatisfied(): bool
    public function networkMismatch(NetworkTrustEvidence $current): bool
    public function deviceMismatch(DeviceTrustContext $current): bool
    public function trustLevel(): TrustLevel
    public function isExpired(\DateTimeImmutable $validUntil): bool
}
```

### `BallotAuthorizationProtocol.php`
```php
enum BallotAuthorizationProtocol: string {
    case UnifiedTokenProtocol       = 'single_code';
    case SplitAuthorizationProtocol = 'dual_code';
    // SplitAuthorization: ballot viewing authority (view token) ≠ ballot commit authority (commit token)
    // These are constitutionally distinct sovereign actions. Future capabilities will model them separately.
    public static function fromElection(Election $election): self
    public function authorizationSteps(): int
    public function requiresSeparateCommit(): bool
    public function requiresViewToken(): bool
}
```

### `CommitAuthorizationFreshness.php`
```php
readonly class CommitAuthorizationFreshness {
    public function __construct(
        public string              $commitTokenHash,
        public string              $viewTokenHash,    // linked in dual-code mode
        public \DateTimeImmutable  $issuedAt,
        public bool                $isUsed,
        public ?\DateTimeImmutable $usedAt,
    ) {}
    public function isFresh(int $maxAgeSeconds = 3600): bool
    public function isLinkedToView(string $viewTokenHash): bool
    public function isReplay(): bool  // isUsed && usedAt !== null
}
```

### `VotingTrustResult.php`
```php
readonly class VotingTrustResult {
    public function __construct(
        public bool       $trusted,
        public string     $reason,
        public TrustLevel $trustLevel,
        public array      $auditContext,           // minimized evidence for event ledger
        public array      $policyOutcomeSequence,  // causality chain: each policy's outcome
    ) {}
    public static function allow(TrustLevel $trustLevel, array $context, array $sequence): self
    public static function deny(string $reason, array $context, array $sequence): self
}
```

### `ElectionSecurityEvent.php`
```php
readonly class ElectionSecurityEvent {
    public function __construct(
        public string  $eventType,          // ip_mismatch|fingerprint_mismatch|velocity_violation|
                                            // registrar_attestation|overlay_activation|trust_elevation|
                                            // trust_denied|trust_allowed|replay_rejected
        public int          $electionId,
        public ?string      $voterSlugId,
        public array        $networkEvidence,    // hashed/minimized — NO raw IPs
        public array        $deviceEvidence,     // hashed/minimized — NO raw fingerprints
        public TrustLevel   $trustLevelBefore,
        public TrustLevel   $trustLevelAfter,
        public string  $policyEvaluated,
        public ?string $overlayApplied,
        public array   $policyEvaluationSequence,   // causality: ['verification_policy' => 'passed', ...]
        public array   $overlayInfluenceChain,       // causality: ['ip_velocity_overlay' => 'triggered']
        public string  $trustStateTransition,        // 'unverified → denied'
        public string  $finalConstitutionalOutcome,  // 'allow'|'deny'
        public \DateTimeImmutable $recordedAt,        // immutable
    ) {}
    // NO setters. Append-only.
}
```

### `TrustEvidencePrivacyPolicy.php`
```php
final class TrustEvidencePrivacyPolicy {
    public function hashIp(string $rawIp, string $electionSalt): string
    public function hashFingerprint(string $fp, string $salt): string
    public function minimizeNetworkEvidence(NetworkTrustEvidence $e): array
    public function minimizeDeviceEvidence(DeviceTrustContext $d): array
    public function canRetainRawIp(Election $election): bool  // false unless legally required
}
```

### `VotingSessionTrustContinuity.php`
```php
readonly class VotingSessionTrustContinuity {
    public function __construct(
        public string $sessionId,
        public string $ipHashAtStart,
        public string $ipHashCurrent,
        public bool   $deviceChanged,
        public string $continuityState,  // 'continuous'|'interrupted'|'invalidated'
    ) {}
    public function isPreserved(): bool
    public function requiresReAttestation(): bool
    public function isInvalidated(): bool
    // Architecture explicitly permits AuthorizedTrustTransition (multi-device) in Phase D+
}
```

### Tests to write first (unit):
- `TrustValidityScopeTest.php` — 4 tests: each case has correct string value
- `NetworkTrustEvidenceTest.php` — 11 tests: whitelist, all 4 trust-level thresholds, satisfiesNetworkAttestation, remainingVotes
- `DeviceTrustContextTest.php` — 5 tests: EXACT_MATCH, NO_MATCH, NOT_REQUIRED, isVolatile, matchType
- `VerificationAttestationRecordTest.php` — 9 tests: isSatisfied (3 cases), networkMismatch, deviceMismatch, trustLevel tiers, revoked, isExpired, TrustValidityScope propagates
- `BallotAuthorizationProtocolTest.php` — 5 tests: Unified (1 step, no viewToken, no separateCommit), Split (2 steps, requires both), fromElection mapping
- `CommitAuthorizationFreshnessTest.php` — 5 tests: isFresh within window, stale rejected, isReplay when used, isLinkedToView match/mismatch
- `VotingTrustResultTest.php` — 5 tests: allow/deny constructors, policyOutcomeSequence, auditContext shape
- `ElectionSecurityEventTest.php` — 5 tests: causality chain populated, trustStateTransition format, finalConstitutionalOutcome, recordedAt immutable, replay_rejected eventType
- `TrustEvidencePrivacyPolicyTest.php` — 4 tests: hash not raw, deterministic same salt, minimizeNetworkEvidence excludes raw IP, canRetainRawIp default false
- `VotingSessionTrustContinuityTest.php` — 4 tests: preserved same IP, requiresReAttestation on change, invalidated state, multi-device architecture acknowledged

---

## Phase D.2 — Constitutional Security Schema (Migration) — 1 day

### Migration 1: Elections table additions

```sql
network_binding_strategy         VARCHAR(30) DEFAULT 'ip_count'
device_binding_strategy          VARCHAR(30) DEFAULT 'none'
ballot_authorization_protocol    VARCHAR(30) DEFAULT 'single_code'
trust_overlay_active             BOOLEAN DEFAULT FALSE
trust_overlay_priority           VARCHAR(30) NULLABLE
trust_overlay_reason             VARCHAR(255) NULLABLE
```

### Migration 2: Election security events table (append-only)

```sql
election_security_events:
  id BIGINT PRIMARY KEY
  event_type VARCHAR(50) NOT NULL
  election_id BIGINT NOT NULL (FK elections)
  voter_slug_id VARCHAR(100) NULLABLE
  network_evidence JSON NOT NULL        -- hashed/minimized, NO raw IP
  device_evidence JSON NOT NULL         -- hashed/minimized, NO raw fingerprint
  trust_level_before VARCHAR(30) NOT NULL
  trust_level_after VARCHAR(30) NOT NULL
  policy_evaluated VARCHAR(100) NOT NULL
  overlay_applied VARCHAR(100) NULLABLE
  policy_evaluation_sequence JSON NOT NULL
  overlay_influence_chain JSON NOT NULL
  trust_state_transition VARCHAR(100) NOT NULL
  final_constitutional_outcome VARCHAR(20) NOT NULL
  retention_days INT NOT NULL DEFAULT 730  -- GDPR minimum gesture; full lifecycle = compliance review
  recorded_at TIMESTAMP NOT NULL DEFAULT NOW()
  -- NO updated_at — append-only by design
  INDEX (election_id, recorded_at)
  INDEX (election_id, event_type)       -- for audit query by type
```

**Append-only enforcement:**
- No `$table->timestamps()` — only `recorded_at`
- Eloquent model overrides `save()` on existing records to throw `\LogicException`
- Repository interface: `record(ElectionSecurityEvent $event): void` only — no update/delete

### Tests: `SecuritySchemaTest.php` — 7 tests: election columns, defaults, model writes, events table exists, no updated_at, update throws, retention_days default 730

---

## Phase D.2.5 — Constitutional Security Articles (Governance Layer) — 1 day

**CRITICAL ARCHITECTURAL DISCOVERY:** Security rules are NOT controller behavior. They are **constitutional election articles** that declare participation legitimacy.

### Why This Layer Exists

Current gap: We built the evaluation engine (D.3+) but not the constitutional declaration that tells the engine what rules apply.

```
BEFORE (Weak):     Controller IP checks → procedural, bypassable
AFTER (Strong):    Election Constitution → immutable governance
                        ↓ frozen at election creation
                     TrustPolicyEvaluator
                        ↓
                   ConstitutionalTrustSnapshot
```

### Constitutional Security Articles Declaration

**File:** `app/Domain/Election/Constitution/ElectionConstitution.php`

Add new constant:

```php
const SECURITY_ARTICLES = [
    'network_binding' => [
        'strategy' => ['none', 'ip_count', 'ip_strict', 'whitelist_only'],
        'max_votes_per_ip' => 'integer|min:1|max:100',
        'capture_during_verification' => 'boolean',
    ],
    'device_binding' => [
        'strategy' => ['none', 'fingerprint_required'],
        'capture_during_verification' => 'boolean',
    ],
    'ballot_authorization' => [
        'protocol' => ['single_code', 'dual_code'],
    ],
    'voter_verification' => [
        'required' => 'boolean',
        'protocol' => ['none', 'officer_captures_evidence'],
        'registrar_role' => 'string',
    ],
    'trust_articles' => [
        'base_density_policy' => 'integer',
        'elevation_authority' => ['none', 'registrar', 'continuity'],
    ],
];
```

### Your 7 Rules → Constitutional Articles Mapping

| Your Rule | Constitutional Article | Domain Enforcement |
|-----------|------------------------|-------------------|
| Rule 1 & 4: IP capture + vote only through that IP | `network_binding.strategy = 'ip_strict'` | `NetworkBindingPolicy` |
| Rule 7: Max N votes per IP | `network_binding.max_votes_per_ip = 6` | `NetworkBindingPolicy::exceedsLimit()` |
| Rule 5: Same fingerprint | `device_binding.strategy = 'fingerprint_required'` | `DeviceBindingPolicy` |
| Rule 2: Single code system | `ballot_authorization.protocol = 'single_code'` | `BallotAuthorizationProtocol` enum |
| Rule 3: Dual code system | `ballot_authorization.protocol = 'dual_code'` | `BallotAuthorizationProtocol` enum |
| Rule 6: Officer captures IP + fingerprint | `voter_verification.required = true` + `voter_verification.protocol = 'officer_captures_evidence'` | `VerificationAttestationPolicy` |

### **CRITICAL IMPROVEMENTS (Expert-Reviewed)**

#### **IMPROVEMENT #1: Security Articles MUST Be Snapshotted**

Danger: If rules mutate during election, trust retroactively becomes illegitimate.

**Solution:** Freeze articles at election creation.

**File:** `database/migrations/2026_05_26_000002_add_security_articles_snapshot_to_elections.php`

```php
Schema::table('elections', function (Blueprint $table) {
    // Frozen snapshot of constitutional articles at election creation
    $table->json('security_articles_snapshot')->after('ballot_authorization_protocol');
    $table->string('security_articles_version')->default('D.2.5')->after('security_articles_snapshot');
    $table->string('constitutional_hash')->nullable()->after('security_articles_version');
});
```

**Why:** Election disputes require proving which legitimacy rules governed votes.

#### **IMPROVEMENT #2: Articles Are NOT Settings**

Vocabulary: These are **constitutional participation legitimacy articles**, not "security settings."

**Why:** Prevents casual mutation, admin panel alterations, governance bypass.

#### **IMPROVEMENT #3: BASE vs OVERLAY DISTINCTION**

**Base Constitutional Articles** (immutable):
- IP strictness strategy
- Fingerprint requirement
- Dual-code requirement
- Verification protocol

**Runtime Overlays** (exceptional governance):
- Suspicious activity threshold
- Emergency suspension
- Velocity anomaly detection

**Never mix.** Overlays may *elevate* base rules (e.g., 2x IP count), but never *bypass* them.

#### **IMPROVEMENT #4: Trust Elevation vs Bypass**

**Base article:**
```php
'max_votes_per_ip' => 6
```

**Elevation thresholds (non-bypass):**
- Unverified: max(1, floor(6 / 2)) = 3
- Attested: 6
- ContinuityVerified: floor(6 * 1.5) = 9
- RegistrarAttested: floor(6 * 2) = 12

**Important:** Elevated thresholds still respect base rule. They don't bypass it.

#### **IMPROVEMENT #5: Versioned Constitutional Lineage**

Each election locks:
```php
security_articles_version: 'D.2.5'
constitutional_hash: SHA-256(json_encode(snapshot))
```

**Why:** Federation, dispute replay, constitutional amendment tracking.

#### **IMPROVEMENT #6: Verification Protocol ≠ Simple Boolean**

**Too weak:**
```php
'required' => true
```

**Correct:**
```php
'voter_verification' => [
    'required' => true,
    'protocol' => 'officer_captures_evidence',
    'registrar_role' => 'election_officer',
    'evidence_types' => ['ip_address', 'device_fingerprint'],
    'validity_scope' => 'election_scoped',  // future: session_scoped, device_scoped
]
```

#### **IMPROVEMENT #7: Future Decomposition Path**

Current structure (unified):
```php
'security_articles'
```

Future structure (when complexity grows):
```php
'participation_legitimacy_articles'
'trust_elevation_articles'
'authorization_protocol_articles'
'overlay_governance_articles'
```

**For Phase D:** Keep unified. Anchor structure for decomposition.

### Election Constitution Integration

**File:** `app/Domain/Election/Constitution/ElectionConstitution.php`

```php
public static function getDefaultSecurityArticles(): array
{
    return self::SECURITY_ARTICLES;
}

public static function validateSecurityArticles(array $articles): bool
{
    // Validates against SECURITY_ARTICLES schema
    // Throws ConstitutionalViolationException if invalid
}

public function snapshotSecurityArticles(): array
{
    // Called at election creation
    // Freezes SECURITY_ARTICLES into immutable snapshot
}
```

### Tests for Phase D.2.5

**File:** `tests/Unit/Domain/Election/Constitution/SecurityArticlesTest.php`

```php
public function test_security_articles_frozen_at_creation(): void
{
    $election = Election::factory()->create();
    $this->assertNotNull($election->security_articles_snapshot);
    $this->assertEquals('D.2.5', $election->security_articles_version);
}

public function test_network_binding_strategy_validates(): void
{
    $this->assertContains('ip_strict', ElectionConstitution::SECURITY_ARTICLES['network_binding']['strategy']);
}

public function test_max_votes_per_ip_is_constitutional(): void
{
    $election = Election::factory()->create([
        'max_votes_per_ip' => 6,
    ]);
    $snapshot = json_decode($election->security_articles_snapshot, true);
    $this->assertEquals(6, $snapshot['network_binding']['max_votes_per_ip']);
}

public function test_verification_protocol_not_boolean(): void
{
    $snapshot = json_decode($election->security_articles_snapshot, true);
    $this->assertIsArray($snapshot['voter_verification']);
    $this->assertArrayHasKey('protocol', $snapshot['voter_verification']);
}

public function test_constitutional_hash_matches_snapshot(): void
{
    $expected = hash('sha256', $election->security_articles_snapshot);
    $this->assertEquals($expected, $election->constitutional_hash);
}
```

### Files to Create/Modify

| File | Action |
|------|--------|
| `app/Domain/Election/Constitution/ElectionConstitution.php` | ADD `const SECURITY_ARTICLES` |
| `database/migrations/2026_05_26_000002_add_security_articles_snapshot_to_elections.php` | CREATE |
| `tests/Unit/Domain/Election/Constitution/SecurityArticlesTest.php` | CREATE (5 tests) |

---

## Phase D.3 — Constitutional Trust Derivation Infrastructure — 3 days

**This is the most sovereignty-sensitive phase.** The primary risk is hidden parallel authority engines emerging through orchestration boundaries. Discipline is enforced via 10 non-negotiable invariants.

### NON-NEGOTIABLE INVARIANTS FOR D.3

| # | Invariant |
|---|-----------|
| 1 | `ElectionCapabilityResolver` is the ONLY sovereign authority engine — D.3 components never derive authority independently |
| 2 | `TrustCapabilityContext` holds ONLY facts: `verifiedIp`, `currentIp`, `fingerprint`, `attestation`, `votesFromIp`. Never `canVote`, `isAuthorized` |
| 3 | `TrustPolicyEvaluator` returns `VotingTrustResult` ONLY — never `can_vote`, capability arrays, or authority decisions |
| 4 | Policies are isolated evaluators — they NEVER call each other, share state, or mutate overlays |
| 5 | `OverlayCoordinator` influences evaluation via `OverlayEffect` — NEVER mutates `TrustCapabilityContext` facts |
| 6 | `ConstitutionalTrustSnapshot` is immutable projection-only — no `recalculate()`, no `grantVotingAccess()`, no authority behavior |
| 7 | `TrustSnapshotAssembler` exists to keep the evaluator thin — prevents projection inflation inside evaluator |
| 8 | `SecurityEventRecorder` is fire-and-forget — audit failure MUST NEVER cause trust denial |
| 9 | Overlays NEVER directly grant authority — only `ElevateTrust`, `Deny`, `RequireReview` via `OverlayEffect` |
| 10 | ALL tests prove behavioral invariants — not architectural confidence (e.g. actual policy isolation, not grep-based checks) |

### D.3 is NOT yet wired into `ElectionCapabilityResolver`

The resolver integration happens in Phase D.5. D.3 builds and tests the trust infrastructure in isolation. This is intentional — it prevents premature coupling and keeps test scope focused.

---

### Component Architecture

```
TrustPolicyEvaluator (thin orchestration)
  ├── OverlayCoordinator         ← influence only, no fact mutation
  ├── PolicySequence             ← constitutional evaluation order
  │     ├── VerificationAttestationPolicy   ← isolated evaluator
  │     ├── NetworkBindingPolicy            ← isolated evaluator (uses trust from step 1)
  │     └── DeviceBindingPolicy             ← isolated evaluator
  ├── SecurityEventRecorder      ← fire-and-forget, non-authoritative
  └── [returns VotingTrustResult — NOT authority]

TrustSnapshotAssembler (separate)  ← assembles ConstitutionalTrustSnapshot from result
ConstitutionalTrustSnapshot        ← immutable projection, no behavior
```

---

### `TrustCapabilityContext.php`

**File:** `app/Application/Election/Security/TrustCapabilityContext.php`

```php
readonly class TrustCapabilityContext {
    public function __construct(
        public Election                      $election,
        public ?User                         $user,
        public NetworkTrustEvidence          $network,
        public DeviceTrustContext            $device,
        public VerificationAttestationRecord $attestation,
        public VotingSessionTrustContinuity  $sessionContinuity,
    ) {}

    public function currentTrustLevel(): TrustLevel  // derived from attestation record
    public function continuityPreserved(): bool       // delegates to sessionContinuity
}
```

**INVARIANT 2**: No `canVote()`, `isAuthorized()`, `isEligible()`, `trustSufficient()`. Facts ONLY.

---

### `PolicySequence.php`

**File:** `app/Application/Election/Security/PolicySequence.php`

```php
final class PolicySequence {
    public function __construct(
        private VerificationAttestationPolicy $verificationPolicy,
        private NetworkBindingPolicy          $networkPolicy,
        private DeviceBindingPolicy           $devicePolicy,
    ) {}

    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    // Constitutional evaluation order (non-negotiable):
    // Step 1: VerificationAttestationPolicy → establishes attestation legitimacy, captures trust level
    // Step 2: NetworkBindingPolicy → validates network continuity (receives trust level from step 1)
    // Step 3: DeviceBindingPolicy → validates device continuity
    // Returns first deny result (causality chain preserved), else allow with composite trust level
    // Each policy receives ONLY $ctx — policies NEVER call each other directly
}
```

**INVARIANT 4**: Step ordering is constitutional precedence, not technical pipeline order. Policies receive `$ctx` only, never each other's results directly.

---

### `OverlayCoordinator.php`

**File:** `app/Application/Election/Security/OverlayCoordinator.php`

```php
final class OverlayCoordinator {
    public function apply(TrustCapabilityContext $ctx): ?VotingTrustResult
    // Applies overlays in OverlayPriority order (deferred in D.3, wired in D.4)
    // Returns VotingTrustResult::deny() if any overlay denies
    // Returns null if all overlays pass (letting PolicySequence proceed)
    // ElevateTrust overlays return null — trust elevation is communicated via context extension in D.4
    // INVARIANT 5: Never mutates $ctx — reads $election->trust_overlay_active for basic check
}
```

**For Phase D.3:** Simple implementation — checks `$ctx->election->trust_overlay_active` and returns deny if true. Full overlay evaluation (D.4) wires in the 5 overlay classes.

---

### `SecurityEventRecorder.php`

**File:** `app/Application/Election/Security/SecurityEventRecorder.php`

```php
final class SecurityEventRecorder {
    public function record(VotingTrustResult $result, TrustCapabilityContext $ctx): void
    // INVARIANT 8: This method returns void. Never throws. Never affects trust outcome.
    // Implementation wraps ORM write in try/catch — failure logs warning but does NOT propagate
    // DENY events: always record immediately
    // ALLOW events: ~10% sampled (or dispatch queue job for non-blocking)
    // Builds ElectionSecurityEvent domain object with full causality chain
    // Passes to append-only Eloquent model (ElectionSecurityEvent::create only)
}
```

---

### `TrustSnapshotAssembler.php` ← NEW (Correction #7)

**File:** `app/Application/Election/Security/TrustSnapshotAssembler.php`

```php
final class TrustSnapshotAssembler {
    public function assemble(
        VotingTrustResult    $result,
        TrustCapabilityContext $ctx,
        ?string              $activeOverlay = null,
    ): ConstitutionalTrustSnapshot
    // Translates VotingTrustResult + context into immutable projection
    // Assembles trustProvenance from policyOutcomeSequence
    // Determines authorizationProtocol from election's ballot_authorization_protocol column
    // INVARIANT 6: Returns readonly ConstitutionalTrustSnapshot — no calculation methods inside
}
```

---

### `TrustPolicyEvaluator.php` — thin orchestration only

**File:** `app/Application/Election/Security/TrustPolicyEvaluator.php`

```php
final class TrustPolicyEvaluator {
    public function __construct(
        private OverlayCoordinator         $overlayCoordinator,
        private PolicySequence             $policySequence,
        private SecurityEventRecorder      $eventRecorder,
        private TrustEvidencePrivacyPolicy $privacyPolicy,
    ) {}

    public function evaluate(
        Election $election,
        ?User    $user,
        string   $rawIp,
        ?string  $rawFingerprint,
        string   $sessionId,
    ): VotingTrustResult
    // Step 1: Hash evidence via privacyPolicy (raw IP never reaches any other method)
    // Step 2: Build TrustCapabilityContext from hashed facts
    // Step 3: Run overlayCoordinator — deny short-circuits and skips PolicySequence
    // Step 4: Run policySequence (if overlay passed)
    // Step 5: Fire-and-forget eventRecorder (never awaits, never checks return)
    // Step 6: Return VotingTrustResult — NEVER can_vote, capability array, or authority decision

    private function buildNetworkEvidence(Election $election, string $ipHash): NetworkTrustEvidence
    private function buildDeviceContext(?string $fpHash, ?User $user): DeviceTrustContext
    private function buildAttestationRecord(Election $election, ?User $user): VerificationAttestationRecord
    private function buildSessionContinuity(string $sessionId, string $ipHash, Election $election): VotingSessionTrustContinuity
}
```

**INVARIANT 3**: Returns `VotingTrustResult` only. No `can_vote`, no array of permissions, no capability decisions.

---

### `ConstitutionalTrustSnapshot.php` — immutable projection only

**File:** `app/Application/Election/Security/ConstitutionalTrustSnapshot.php`

```php
readonly class ConstitutionalTrustSnapshot {
    public function __construct(
        public bool       $trusted,
        public TrustLevel $trustLevel,
        public string     $authorizationProtocol,    // from BallotAuthorizationProtocol enum value
        public bool       $requiresViewToken,
        public bool       $requiresSeparateCommit,
        public bool       $attestationValid,
        public string     $attestationSource,        // 'registrar'|'session'|'none'
        public bool       $continuityPreserved,
        public ?string    $activeOverlay,
        public ?string    $overlayInfluence,          // 'elevated'|'denied'|null
        public string     $denialReason,
        public array      $trustProvenance,           // causality lineage only — NO raw evidence
    ) {}
    // INVARIANT 6: No recalculate(), no grantVotingAccess(), no authority methods
    // Future decomposition (TrustProjection etc.) deferred to Phase D+
}
```

---

### Trust Policies (in `app/Application/Election/Security/Policies/`)

All policies are **isolated evaluators**. They receive only `TrustCapabilityContext` and return `VotingTrustResult`. They NEVER call each other.

**`VerificationAttestationPolicy.php`**
```php
final class VerificationAttestationPolicy {
    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    // Checks: $ctx->attestation->isSatisfied()
    // If not satisfied AND required: deny('verification_required', ...)
    // If satisfied: allow with trustLevel from $ctx->attestation->trustLevel()
    // Records attestation source in policyOutcomeSequence
}
```

**`NetworkBindingPolicy.php`**
```php
final class NetworkBindingPolicy {
    public function evaluate(TrustCapabilityContext $ctx, TrustLevel $currentTrustLevel): VotingTrustResult
    // Checks (in order):
    // 1. If restriction disabled: allow (strategy = 'none')
    // 2. If whitelisted: allow (strategy = 'whitelist_only' or whitelist contains current IP hash)
    // 3. If IP count exceeds limit for $currentTrustLevel: deny('network_limit_exceeded', ...)
    // Trust elevation thresholds from $ctx->network->exceedsLimit($currentTrustLevel)
    // Reuses: Election::isIpWhitelisted() via NetworkTrustEvidence::isWhitelisted()
}
```

**`DeviceBindingPolicy.php`**
```php
final class DeviceBindingPolicy {
    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    // Checks: $ctx->device->satisfiesDeviceAttestation()
    // If NOT_REQUIRED: allow
    // If EXACT_MATCH: allow
    // If NO_MATCH and required: deny('device_attestation_failed', ...)
}
```

---

### Reuse (existing code, DO NOT duplicate)
- `Election::ipInRange()` → `app/Models/Election.php` — used by `NetworkTrustEvidence::isWhitelisted()`
- `Election::isIpWhitelisted()` → `app/Models/Election.php` — reuse via model method
- `CapabilityContext` pattern → `app/Application/Election/Capabilities/CapabilityContext.php` — structural reference
- `ElectionSecurityEvent` Eloquent model → `app/Models/ElectionSecurityEvent.php` — used by `SecurityEventRecorder`

---

### Tests for Phase D.3 (TDD: tests first, then implementation)

All tests prove behavioral invariants, not architectural confidence.

**`TrustCapabilityContextTest.php`** — 5 tests:
- `currentTrustLevel()` delegates to attestation record
- `continuityPreserved()` delegates to sessionContinuity
- Context holds only facts (no authority-deriving methods exist)
- Immutable (readonly properties cannot be reassigned)
- RegistrarAttested trustLevel from context when registrarId present

**`VerificationAttestationPolicyTest.php`** — 7 tests:
- Not satisfied + required → deny with 'verification_required' reason
- Satisfied + registrar present → allow with RegistrarAttested trust level
- Not required → allow with Unverified trust level
- Revoked record → deny even if attested
- Network mismatch preserved in policyOutcomeSequence
- Device mismatch preserved in policyOutcomeSequence
- Satisfied without registrar → allow with Attested trust level

**`NetworkBindingPolicyTest.php`** — 9 tests:
- Strategy 'none' → always allow
- Whitelisted IP → allow regardless of count
- Unverified + exceeds 0.5x limit → deny
- Attested + within 1x limit → allow
- ContinuityVerified + within 1.5x limit → allow
- RegistrarAttested + within 2x limit → allow
- RegistrarAttested + exceeds 2x limit → deny
- policyOutcomeSequence contains remaining vote count
- Denial reason is 'network_limit_exceeded'

**`DeviceBindingPolicyTest.php`** — 4 tests:
- NOT_REQUIRED → allow
- EXACT_MATCH → allow
- NO_MATCH when required → deny with 'device_attestation_failed'
- NO_MATCH when NOT_REQUIRED (fingerprint not required for election) → allow

**`OverlayCoordinatorTest.php`** — 4 tests:
- `trust_overlay_active = false` → returns null (let policies run)
- `trust_overlay_active = true` → returns VotingTrustResult::deny
- Does NOT mutate `$ctx` in any test case (verify context facts unchanged)
- Returns null when election has no overlay configured

**`PolicySequenceTest.php`** — 5 tests:
- VerificationAttestationPolicy denial short-circuits (NetworkBindingPolicy not called)
- NetworkBindingPolicy receives trust level from VerificationAttestationPolicy result
- DeviceBindingPolicy denial short-circuits
- All policies pass → allow with composite policyOutcomeSequence
- policyOutcomeSequence contains all three policy outcomes when all pass

**`SecurityEventRecorderTest.php`** — 4 tests:
- Deny result → event always recorded immediately
- Allow result with 0.0 sample rate → no event (test rate override)
- Allow result with 1.0 sample rate → event recorded (test rate override)
- ORM failure → no exception propagated (try/catch swallows, logs warning)

**`TrustSnapshotAssemblerTest.php`** — 4 tests:
- Assembles snapshot from allow result with correct fields
- Assembles snapshot from deny result with denialReason populated
- trustProvenance contains policy outcome sequence entries
- requiresSeparateCommit true when ballot_authorization_protocol = 'dual_code'

**`TrustPolicyEvaluatorTest.php`** — 6 tests:
- Raw IP is hashed before entering TrustCapabilityContext
- Overlay denial short-circuits PolicySequence (policy never called)
- PolicySequence denial returns VotingTrustResult (NOT capability array)
- Full allow path: overlay passes, all policies pass → VotingTrustResult::allow
- Recorder always called, never affects return value
- Returns VotingTrustResult, never has `can_vote` key or similar

**`ConstitutionalTrustSnapshotTest.php`** — 5 tests:
- Snapshot is readonly (immutable projection)
- No `recalculate()` or `grantVotingAccess()` methods exist on snapshot
- SplitAuthorizationProtocol flags: requiresViewToken=true, requiresSeparateCommit=true
- trustProvenance contains causality chain entries
- denialReason populated when trusted=false

Total D.3 tests: **~53 behavioral tests**

---

### Files to Create in Phase D.3

| File | Type |
|------|------|
| `app/Application/Election/Security/TrustCapabilityContext.php` | CREATE |
| `app/Application/Election/Security/PolicySequence.php` | CREATE |
| `app/Application/Election/Security/OverlayCoordinator.php` | CREATE |
| `app/Application/Election/Security/SecurityEventRecorder.php` | CREATE |
| `app/Application/Election/Security/TrustSnapshotAssembler.php` | CREATE (Correction #7) |
| `app/Application/Election/Security/TrustPolicyEvaluator.php` | CREATE |
| `app/Application/Election/Security/ConstitutionalTrustSnapshot.php` | CREATE |
| `app/Application/Election/Security/Policies/VerificationAttestationPolicy.php` | CREATE |
| `app/Application/Election/Security/Policies/NetworkBindingPolicy.php` | CREATE |
| `app/Application/Election/Security/Policies/DeviceBindingPolicy.php` | CREATE |
| `tests/Unit/Application/Election/Security/TrustCapabilityContextTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/PolicySequenceTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/OverlayCoordinatorTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/SecurityEventRecorderTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/TrustSnapshotAssemblerTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/TrustPolicyEvaluatorTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/ConstitutionalTrustSnapshotTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/Policies/VerificationAttestationPolicyTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/Policies/NetworkBindingPolicyTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/Policies/DeviceBindingPolicyTest.php` | CREATE |

---

## Phase D.4 — Constitutional Overlay Governance & Trust Sovereignty Hardening — 3 days

**This is the highest sovereignty-risk phase.** Six critical corrections apply before any implementation.

---

### CONSTITUTIONAL LAW: OVERLAY NON-SOVEREIGNTY CONTRACT

**Overlays MAY:**
- Signal constitutional influence conditions (scrutiny elevation, reverification requirement)
- Suggest trust level elevation to the Resolver
- Require explicit manual review before participation
- Report trust evaluation inconclusiveness
- Attach evidence context to the evaluation record
- Escalate to registrar attention

**Overlays MAY NEVER:**
- Grant participation authority
- Deny participation authority directly
- Mutate `TrustCapabilityContext` facts
- Bypass `ElectionCapabilityResolver`
- Override constitutional lifecycle decisions
- Resolve conflicts between overlays

**Authority Flow (immutable):**
```
TrustCapabilityContext (facts only)
  → OverlayCoordinator::aggregate()    ← accumulates influence signals ONLY
  → OverlayInfluenceContext            ← pure signal collection, no authority
  → PolicySequence::evaluate(ctx)      ← constitutional evaluation (unchanged)
  → VotingTrustResult                  ← trust result (not authority decision)
  → TrustSnapshotAssembler             ← assembles with overlay influence populated
  → ConstitutionalTrustSnapshot        ← exposes overlay signals to resolver
  → ElectionCapabilityResolver         ← ONLY engine that derives authority
  → ElectionCapabilitySnapshot         ← consumed by controllers / frontend
```

**Banned vocabulary in all D.4 overlay objects:**
`allow`, `deny`, `grant`, `authorize`, `permit`, `revoke`, `block`, `suspend`

**Required vocabulary:** `influence`, `signal`, `require_review`, `require_reverification`, `elevate`, `inconclusive`

---

### NON-NEGOTIABLE D.4 INVARIANTS

| # | Invariant |
|---|-----------|
| 1 | `OverlayCoordinator::aggregate()` returns `OverlayInfluenceContext` — NEVER `VotingTrustResult` |
| 2 | Overlay coordination is aggregate-only — coordinator NEVER resolves which signal "wins" |
| 3 | `ElectionCapabilityResolver` (Phase D.5) interprets overlay influence — not `TrustPolicyEvaluator` |
| 4 | All overlay signals are immutable `OverlaySignal` readonly objects — no mutable state |
| 5 | `OverlayStratification` defines ordering metadata only — NOT conflict resolution authority |
| 6 | `ConstitutionalOverlayRegistry` holds declarative `OverlayDefinition` metadata — never instantiates overlays directly |
| 7 | Emergency governance is NOT casual overlay behavior — `EmergencyConditionOverlay` uses `REQUIRE_CONSTITUTIONAL_REVIEW` signal only |
| 8 | `RegistrarAttestationElevation` elevates trust via signal — never "overrides" anything |
| 9 | ALL overlay tests prove behavioral influence only — no grep-confidence architecture tests |
| 10 | `TrustCapabilityContext` facts remain unchanged after overlay aggregation — proven by test |

---

### New Domain Objects (in `app/Domain/Election/Security/`)

#### `OverlayInfluence.php` — Constitutional Influence Signal Enum
```php
enum OverlayInfluence: string {
    case CONTINUE_UNCHANGED              = 'continue_unchanged';               // No overlay influence
    case TRUST_ELEVATION_REQUEST         = 'trust_elevation_request';          // Suggest higher trust (Resolver decides)
    case REQUIRE_RE_VERIFICATION         = 'require_re_verification';          // Must re-attest before participation
    case REQUIRE_CONSTITUTIONAL_REVIEW   = 'require_constitutional_review';    // Explicit manual review required
    case TRUST_EVALUATION_INCONCLUSIVE   = 'trust_evaluation_inconclusive';    // Evidence uncertain, cannot establish trust
    case FEDERATION_CONCERN              = 'federation_concern';               // Cross-jurisdiction signal (future)
    case REGISTRAR_ATTENTION_REQUIRED    = 'registrar_attention_required';     // Escalation to registrar

    // BANNED: DENY, ALLOW, SUSPEND_TRUST, GRANT, AUTHORIZE, PERMIT
}
```

#### `OverlaySignal.php` — Influence Signal (readonly, influence-only)
```php
readonly class OverlaySignal {
    public function __construct(
        public OverlayInfluence $influence,
        public string           $overlayIdentifier,      // which overlay produced this
        public string           $constitutionalBasis,    // why this influence was produced
        public array            $evidenceContext,         // hashed facts that triggered it (NO raw PII)
        public ?string          $requiredReviewRole,      // who must review (if applicable)
        public ?TrustLevel      $suggestedElevatedTrustLevel, // ONLY for TRUST_ELEVATION_REQUEST
    ) {}

    public function requiresConstitutionalReview(): bool
    {
        return $this->influence === OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW;
    }

    public static function continue(string $overlayIdentifier): self
    {
        return new self(OverlayInfluence::CONTINUE_UNCHANGED, $overlayIdentifier, 'no_condition', [], null, null);
    }
}
```

#### `OverlayInfluenceContext.php` — Aggregated Signals (resolver reads this)
```php
readonly class OverlayInfluenceContext {
    public function __construct(
        public array  $signals,             // OverlaySignal[] — all signals from all overlays
        public bool   $hasInfluence,        // true if any non-CONTINUE signals present
        public bool   $requiresReview,      // true if any REQUIRE_CONSTITUTIONAL_REVIEW present
        public bool   $requiresReverification, // true if any REQUIRE_RE_VERIFICATION present
        public bool   $isInconclusive,      // true if any TRUST_EVALUATION_INCONCLUSIVE present
        public ?TrustLevel $elevationRequest, // highest suggested elevation (for Resolver to accept/reject)
    ) {}

    public static function noInfluence(): self
    // Returns context with all booleans false, empty signals

    // INVARIANT: No authority derivation methods. Resolver reads properties directly.
}
```

#### `OverlayStratification.php` — Ordering Metadata (NOT conflict resolution)
```php
enum OverlayStratification: string {
    case EMERGENCY_CONSTITUTIONAL = 'emergency_constitutional';   // Emergency governance (deferred modeling)
    case GOVERNANCE_LAYER         = 'governance_layer';           // Registrar attestation signals
    case OPERATIONAL_LAYER        = 'operational_layer';          // Suspicious activity, velocity
    case CONTEXTUAL_LAYER         = 'contextual_layer';           // Device anomaly, continuity

    // Purpose: ordering metadata for aggregation traversal ONLY.
    // Does NOT determine which signal "wins" — that is Resolver responsibility.
}
```

#### `OverlayDefinition.php` — Declarative Registry Metadata (no instantiation)
```php
readonly class OverlayDefinition {
    public function __construct(
        public string               $identifier,                 // machine name slug
        public string               $overlayClass,               // FQCN — container resolves, registry NEVER instantiates
        public OverlayStratification $stratification,
        public array                $capableInfluences,          // OverlayInfluence[] this overlay may produce
        public bool                 $requiresRegistrarActivation,
        public bool                 $federationAware,
        public int                  $stratificationOrder,        // evaluation traversal order within stratification
    ) {}
}
```

---

### New Application Objects (in `app/Application/Election/Security/`)

#### `ConstitutionalOverlay.php` — Overlay Interface
```php
interface ConstitutionalOverlay {
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal;
    public function identifier(): string;
    public function stratification(): OverlayStratification;
}
```

#### `ConstitutionalOverlayRegistry.php` — Declarative Metadata (no new Overlay() calls)
```php
final class ConstitutionalOverlayRegistry {
    // Holds OverlayDefinition[] metadata — NOT overlay instances
    // Container resolves actual instances when OverlayCoordinator requests them
    // Registry declares: identifier, class name, stratification, capable influences

    private static array $definitions = [
        [
            'identifier'    => 'emergency_condition',
            'class'         => EmergencyConditionOverlay::class,
            'stratification'=> OverlayStratification::EMERGENCY_CONSTITUTIONAL,
            'influences'    => [OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'         => 1,
        ],
        [
            'identifier'    => 'registrar_attestation_elevation',
            'class'         => RegistrarAttestationElevation::class,
            'stratification'=> OverlayStratification::GOVERNANCE_LAYER,
            'influences'    => [OverlayInfluence::TRUST_ELEVATION_REQUEST],
            'registrar_required' => true,
            'federation_aware'   => true,
            'order'         => 2,
        ],
        [
            'identifier'    => 'suspicious_activity',
            'class'         => SuspiciousActivityOverlay::class,
            'stratification'=> OverlayStratification::OPERATIONAL_LAYER,
            'influences'    => [OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'         => 3,
        ],
        [
            'identifier'    => 'ip_velocity',
            'class'         => IpVelocityOverlay::class,
            'stratification'=> OverlayStratification::OPERATIONAL_LAYER,
            'influences'    => [OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'         => 4,
        ],
        [
            'identifier'    => 'device_anomaly',
            'class'         => DeviceAnomalyOverlay::class,
            'stratification'=> OverlayStratification::CONTEXTUAL_LAYER,
            'influences'    => [OverlayInfluence::REQUIRE_RE_VERIFICATION],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'         => 5,
        ],
    ];

    /** @return OverlayDefinition[] */
    public function getOrderedDefinitions(): array  // Sorted by stratification order
    public function findByIdentifier(string $identifier): ?OverlayDefinition
}
```

#### Revised `OverlayCoordinator.php` — Aggregation ONLY
```php
final class OverlayCoordinator {
    public function __construct(
        private array $overlays, // ConstitutionalOverlay[] — injected via DI
    ) {}

    // D.3 stub replaced: returns OverlayInfluenceContext, NEVER VotingTrustResult
    public function aggregate(TrustCapabilityContext $ctx): OverlayInfluenceContext
    {
        $signals = [];

        foreach ($this->overlays as $overlay) {
            $signal = $overlay->evaluate($ctx);
            $signals[] = $signal;

            // Early break when review required — cannot proceed with normal evaluation
            // NOTE: This is NOT "highest priority wins" — it is "stop collecting when conclusive review needed"
            if ($signal->requiresConstitutionalReview()) {
                break;
            }
        }

        return $this->buildInfluenceContext($signals);
    }
    // INVARIANT 2: Never resolves conflict outcomes. Resolver interprets $signals.
    // INVARIANT 10: Never mutates $ctx — proven by behavioral test
}
```

#### Five Concrete Overlay Classes (in `app/Application/Election/Security/Overlays/`)

**`EmergencyConditionOverlay.php`**
```php
final class EmergencyConditionOverlay implements ConstitutionalOverlay {
    // Reads trust_overlay_priority = 'emergency' from election
    // Returns REQUIRE_CONSTITUTIONAL_REVIEW — NOT a suspension, NOT a denial
    // Emergency governance is separate constitutional domain (Phase D.4+)
    // This overlay merely signals: constitutional review is required
}
```

**`RegistrarAttestationElevation.php`** (NOT "RegistrarOverrideOverlay")
```php
final class RegistrarAttestationElevation implements ConstitutionalOverlay {
    // Returns TRUST_ELEVATION_REQUEST when registrarId present and not revoked
    // suggestedElevatedTrustLevel = TrustLevel::RegistrarAttested
    // Resolver DECIDES whether to accept elevation — overlay only suggests
    // "override" vocabulary entirely absent from this class
}
```

**`SuspiciousActivityOverlay.php`**
```php
final class SuspiciousActivityOverlay implements ConstitutionalOverlay {
    // Reads trust_overlay_active = true from election
    // Returns REQUIRE_CONSTITUTIONAL_REVIEW with trust_overlay_reason as basis
}
```

**`IpVelocityOverlay.php`**
```php
final class IpVelocityOverlay implements ConstitutionalOverlay {
    // Reads recent vote count for this IP from election_security_events
    // Returns TRUST_EVALUATION_INCONCLUSIVE when velocity threshold exceeded
    // Threshold: env('IP_VELOCITY_THRESHOLD', 10) votes per IP in 5 minutes
    // Indexes required: (election_id, recorded_at) — already exists from D.2
}
```

**`DeviceAnomalyOverlay.php`**
```php
final class DeviceAnomalyOverlay implements ConstitutionalOverlay {
    // Returns REQUIRE_RE_VERIFICATION when:
    //   $ctx->device->volatility === 'volatile' AND session previously stable
    // Compares current device fingerprint volatility to session continuity record
}
```

---

### Updated TrustPolicyEvaluator Signature Change

`OverlayCoordinator::aggregate()` now returns `OverlayInfluenceContext` (not `?VotingTrustResult`).

`TrustPolicyEvaluator::evaluate()` updated:
```php
// Step 3: Aggregate overlay influence signals (NEVER short-circuits — resolver interprets)
$overlayInfluence = $this->overlayCoordinator->aggregate($ctx);

// Step 4: PolicySequence evaluates constitutional trust (unchanged)
$result = $this->policySequence->evaluate($ctx);

// Step 5: Assemble snapshot WITH overlay influence (now populated)
// Passed to assembler so snapshot.activeOverlay and snapshot.overlayInfluence are correct

// Step 6: Record event with full overlay lineage
$this->eventRecorder->record($result, $ctx, $overlayInfluence);
```

**Deferred to D.5:** `ElectionCapabilityResolver` interprets `overlayInfluence` context to adjust capability derivation.

---

### Updated TrustSnapshotAssembler

Now accepts `OverlayInfluenceContext` as third parameter:
- `activeOverlay`: set to first non-CONTINUE overlay identifier if any
- `overlayInfluence`: set to the strongest influence signal value string if any

---

### ConstitutionalFailureTaxonomy (Introduced in D.4)

Replace freeform string denial reasons with structured type across all D.4 objects.

**File:** `app/Domain/Election/Security/ConstitutionalTrustViolation.php`
```php
enum ConstitutionalTrustViolation: string {
    case LEGITIMACY_FAILURE            = 'legitimacy_failure';            // Verification not satisfied
    case ATTESTATION_REVOKED           = 'attestation_revoked';           // Attestation was revoked
    case CONTINUITY_FAILURE            = 'continuity_failure';            // Device/network continuity broken
    case NETWORK_LIMIT_EXCEEDED        = 'network_limit_exceeded';        // IP vote count exceeded
    case REVERIFICATION_REQUIRED       = 'reverification_required';       // Overlay requires re-attestation
    case CONSTITUTIONAL_REVIEW_PENDING = 'constitutional_review_pending'; // Manual review required
    case TRUST_EVALUATION_INCONCLUSIVE = 'trust_evaluation_inconclusive'; // Cannot establish trust
    case OVERLAY_RESTRICTION           = 'overlay_restriction';           // Overlay restriction in force
    case REGISTRAR_HOLD                = 'registrar_hold';                // Registrar escalation pending
}
```

---

### Behavioral Test Blueprint (D.4)

**`OverlayCoordinatorTest.php`** — Updated (9 tests):
- `aggregate_returns_overlay_influence_context_not_trust_result` — return type is `OverlayInfluenceContext`
- `aggregate_never_mutates_context_facts` — ipHash, fingerprint unchanged after aggregation
- `aggregate_with_no_overlays_returns_no_influence` — empty coordinator returns `CONTINUE_UNCHANGED`
- `aggregate_stops_after_constitutional_review_required` — lower-priority overlays not evaluated
- `aggregate_accumulates_all_signals_when_no_review_required` — all signals collected
- `aggregate_never_produces_authority_decision` — no trusted/trusted fields, no can_vote
- `overlay_influence_context_has_requires_review_true_when_any_signal_requires_review`
- `overlay_elevation_request_passes_through_to_context_without_resolution`
- `context_facts_unchanged_after_aggregate_with_active_suspicious_overlay`

**`OverlaySignalTest.php`** — (5 tests):
- `continue_signal_has_continue_unchanged_influence`
- `require_constitutional_review_signal_returns_true_for_requiresConstitutionalReview`
- `trust_elevation_request_carries_suggested_level`
- `signal_evidence_context_contains_no_raw_pii`
- `signal_vocabulary_test` — asserts string values contain no 'deny', 'allow', 'grant'

**Per Overlay (5 overlays × 3 tests = 15 tests):**
- `evaluate_returns_continue_when_condition_not_met`
- `evaluate_returns_correct_influence_when_condition_met`
- `evaluate_never_mutates_context`

**`ConstitutionalOverlayRegistryTest.php`** — (4 tests):
- `registry_holds_definitions_not_instances` — definitions are arrays/OverlayDefinition, not overlay objects
- `definitions_ordered_by_stratification`
- `no_overlay_classes_instantiated_at_registration_time`
- `find_by_identifier_returns_correct_definition`

**`ConstitutionalTrustViolationTest.php`** — (5 tests):
- Each enum case has correct string value
- No 'deny', 'suspend', 'block' in any value string
- Enum covers legitimacy, continuity, network, review, and inconclusive failures

**Total D.4 behavioral tests: ~38**

---

### Files to Create in Phase D.4

| File | Type |
|------|------|
| `app/Domain/Election/Security/OverlayInfluence.php` | CREATE |
| `app/Domain/Election/Security/OverlaySignal.php` | CREATE |
| `app/Domain/Election/Security/OverlayInfluenceContext.php` | CREATE |
| `app/Domain/Election/Security/OverlayStratification.php` | CREATE |
| `app/Domain/Election/Security/ConstitutionalTrustViolation.php` | CREATE |
| `app/Application/Election/Security/ConstitutionalOverlay.php` (interface) | CREATE |
| `app/Application/Election/Security/OverlayDefinition.php` | CREATE |
| `app/Application/Election/Security/ConstitutionalOverlayRegistry.php` | CREATE |
| `app/Application/Election/Security/OverlayCoordinator.php` | **REPLACE** D.3 stub |
| `app/Application/Election/Security/TrustPolicyEvaluator.php` | **MODIFY** (aggregate call) |
| `app/Application/Election/Security/TrustSnapshotAssembler.php` | **MODIFY** (overlay fields) |
| `app/Application/Election/Security/SecurityEventRecorder.php` | **MODIFY** (overlay lineage) |
| `app/Application/Election/Security/Overlays/EmergencyConditionOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/RegistrarAttestationElevation.php` | CREATE |
| `app/Application/Election/Security/Overlays/SuspiciousActivityOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/IpVelocityOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/DeviceAnomalyOverlay.php` | CREATE |
| `tests/Unit/Application/Election/Security/OverlayCoordinatorTest.php` | **REPLACE** (9 tests) |
| `tests/Unit/Domain/Election/Security/OverlaySignalTest.php` | CREATE |
| `tests/Unit/Application/Election/Security/Overlays/*Test.php` (×5) | CREATE |
| `tests/Unit/Application/Election/Security/ConstitutionalOverlayRegistryTest.php` | CREATE |
| `tests/Unit/Domain/Election/Security/ConstitutionalTrustViolationTest.php` | CREATE |

### Deferred to D.5

- `ElectionCapabilityResolver::applyOverlayInfluence()` — resolver interprets `OverlayInfluenceContext`
- Trust elevation applied by resolver, not coordinator
- REQUIRE_CONSTITUTIONAL_REVIEW causing participation denial — resolver decision only
- Inertia props: `overlayInfluence`, `requiresReview`, `reviewRole`

---

## Phase D.5 — Controller Wiring + Replay + Ballot Protocol — 2 days

```php
// In ElectionVotingController — via ElectionCapabilityResolver, never TrustPolicyEvaluator directly
$snapshot = $this->capabilityResolver->resolve(...)->trust; // ConstitutionalTrustSnapshot

if (!$snapshot->trusted) {
    return redirect()->back()->with('error', $snapshot->denialReason);
}

// Replay protection for dual-code:
if ($snapshot->requiresSeparateCommit) {
    $freshness = $this->freshnesService->check($request, $election);
    if ($freshness->isReplay()) return redirect()->back()->with('error', 'Token already used.');
    if (!$freshness->isFresh()) return redirect()->back()->with('error', 'Token expired.');
}

// Ballot protocol from snapshot:
if ($snapshot->requiresViewToken && !$request->has('view_token')) {
    return redirect()->back()->with('error', 'View token required.');
}
```

Inertia props: `ipBlocked`, `ipBlockMessage`, `remainingVotes`, `trustLevel`, `authorizationProtocol`, `requiresViewToken`, `requiresSeparateCommit`

Enable 5 skipped tests in `tests/Feature/Election/VoterVerificationTest.php`.

---

## Phase D.6 — Controller Cleanup — 1 day

Remove: `resolveIpBlock()`, `evaluateIpCount()`, `ValidateVotingIp` middleware (or thin delegate), deprecated `VotingSecurityService` methods.

Verify: 17/17 passing + 8 skipped legacy tests pass or replaced + 0 regressions.

---

## Critical Files Map

| File | Action |
|---|---|
| `app/Domain/Election/Security/TrustLevel.php` | CREATE |
| `app/Domain/Election/Security/OverlayEffect.php` | CREATE (enum, deferred semantics anchor) |
| `app/Domain/Election/Security/TrustValidityScope.php` | CREATE |
| `app/Domain/Election/Security/NetworkTrustEvidence.php` | CREATE |
| `app/Domain/Election/Security/FingerprintMatchType.php` | CREATE |
| `app/Domain/Election/Security/DeviceTrustContext.php` | CREATE |
| `app/Domain/Election/Security/VerificationAttestationRecord.php` | CREATE |
| `app/Domain/Election/Security/BallotAuthorizationProtocol.php` | CREATE |
| `app/Domain/Election/Security/CommitAuthorizationFreshness.php` | CREATE |
| `app/Domain/Election/Security/VotingTrustResult.php` | CREATE |
| `app/Domain/Election/Security/ElectionSecurityEvent.php` | CREATE |
| `app/Domain/Election/Security/TrustEvidencePrivacyPolicy.php` | CREATE |
| `app/Domain/Election/Security/VotingSessionTrustContinuity.php` | CREATE |
| `app/Application/Election/Security/TrustCapabilityContext.php` | CREATE |
| `app/Application/Election/Security/ConstitutionalTrustSnapshot.php` | CREATE |
| `app/Application/Election/Security/OverlayCoordinator.php` | CREATE |
| `app/Application/Election/Security/PolicySequence.php` | CREATE |
| `app/Application/Election/Security/SecurityEventRecorder.php` | CREATE |
| `app/Application/Election/Security/TrustPolicyEvaluator.php` | CREATE (orchestration only) |
| `app/Application/Election/Security/Policies/VerificationAttestationPolicy.php` | CREATE |
| `app/Application/Election/Security/Policies/NetworkBindingPolicy.php` | CREATE |
| `app/Application/Election/Security/Policies/DeviceBindingPolicy.php` | CREATE |
| `app/Application/Election/Security/Overlays/OverlayPriority.php` | CREATE |
| `app/Application/Election/Security/Overlays/EmergencyElectionSuspensionOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/RegistrarOverrideOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/SuspiciousActivityOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/IpVelocityOverlay.php` | CREATE |
| `app/Application/Election/Security/Overlays/DeviceAnomalyOverlay.php` | CREATE |
| `database/migrations/2026_05_25_100000_add_constitutional_security_strategy_to_elections.php` | CREATE |
| `database/migrations/2026_05_25_100001_create_election_security_events_table.php` | CREATE |
| `app/Models/Election.php` | MODIFY |
| `app/Http/Controllers/ElectionVotingController.php` | MODIFY |

### Reuse:
- `Election::ipInRange()` → `app/Models/Election.php:813`
- `Election::isIpWhitelisted()` → `app/Models/Election.php:820`
- `VoterVerification` model → `app/Models/VoterVerification.php`
- `CapabilityContext` pattern → `app/Application/Election/Capabilities/CapabilityContext.php`
- `ElectionConstitution::RULES` pattern → `app/Domain/Election/Constitution/ElectionConstitution.php`

### Deferred to Phase D+:
- Constitutional dispute semantics (`constitutionalBasis`, `authorityLineageReference`)
- Snapshot decomposition (`TrustProjection` etc.)
- Emergency governance process (activation authority, quorum, appeal)
- Multi-device transition implementation
- Full GDPR retention lifecycle (beyond `retention_days` column)

---

## Execution Order (TDD)

```
D.0   → Vocabulary locked (this document)                                   [1 day]   ✅ DONE
D.1   → Unit tests → domain objects (71 tests)                               [2 days]  ✅ DONE
D.2   → Schema tests → migrations (append-only, causality, retention_days)  [1 day]   ✅ DONE
D.2.5 → Constitutional security articles snapshot + validation               [1 day]   📋 QUEUED (deferred — D.3 took priority)
D.3   → Policy tests → policies + 7 evaluator components (54 tests)         [3 days]  ✅ DONE (125 total security tests)
D.4   → Overlay influence tests → 5 overlays + OverlayCoordinator refactor  [3 days]  🎯 CURRENT (post-sovereignty-review)
        Sovereignty corrections applied:
          - OverlayCoordinator returns OverlayInfluenceContext (not VotingTrustResult)
          - OverlayPriority → OverlayStratification (ordering metadata, no "wins")
          - SUSPEND_TRUST → TRUST_EVALUATION_INCONCLUSIVE
          - RegistrarOverrideOverlay → RegistrarAttestationElevation
          - EmergencyConditionOverlay uses REQUIRE_CONSTITUTIONAL_REVIEW (not suspension)
          - Registry is declarative metadata + DI (never new Overlay())
          - ConstitutionalTrustViolation enum replaces freeform reason strings
D.5   → Resolver wiring + overlay interpretation + replay + ballot protocol [2 days]  ⬜ PENDING
        Resolver::applyOverlayInfluence() interprets OverlayInfluenceContext
D.6   → Full suite → cleanup → 0 regressions                                [1 day]   ⬜ PENDING
                                                                    Total: ~15 days
```

---

## Verification

```bash
php artisan test --env=testing --filter=Security
php artisan test --env=testing --filter="ElectionVoting|IpRestriction|VoterVerification"
php artisan test --env=testing
```

Expected: **17/17** currently passing + all new tests pass + **5** skipped enforcement tests now pass.
