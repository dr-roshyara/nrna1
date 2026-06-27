# Sovereign Enforcement Flow Specification

**Date:** 2026-05-28
**Phase:** Pre-D.0 — Sovereign Runtime Constitution
**Status:** SPECIFICATION
**Authority:** This document is the canonical authority chain for ALL legitimacy enforcement in the constitutional runtime.

---

## Preamble

This specification defines the exact authority chain from constitutional evidence to sovereign enforcement. It is the **sovereign runtime constitution** — no legitimacy decision may deviate from this flow.

```text
ConstitutionalEvidenceSnapshot
→ PolicySequence
→ ConstitutionalLegitimacyDecision
→ EnforcementBoundary
→ Sovereign Outcome (Allowed / Denied / Deferred / Investigate)
```

---

## Article 1: The Authority Chain

### 1.1 Sovereign Path (Target State)

Only this path may produce legitimacy outcomes:

```
┌─────────────────────────────────────────────────────────────────┐
│                    EVIDENCE LAYER                                 │
│  ConstitutionalEvidenceSnapshot                                  │
│  ├── ElectionConstitutionSnapshot  (constitutional rules)        │
│  ├── VerificationEvidence          (voter verification)          │
│  ├── NetworkEvidence               (IP, binding strategy)        │
│  ├── DeviceEvidence                (fingerprint, device trust)   │
│  ├── SessionContinuity             (session integrity)           │
│  ├── ParticipationEligibilityEvidence (membership, status)       │
│  └── constitutionalHash            (deterministic fingerprint)   │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    INTERPRETATION LAYER                           │
│  PolicySequence                                                  │
│  ├── Evaluates evidence against constitutional rules             │
│  ├── OverlayCoordinator provides observational context           │
│  ├── Returns ConstitutionalLegitimacyDecision                    │
│  └── NEVER short-circuits, always evaluates full evidence set    │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    DECISION LAYER                                 │
│  ConstitutionalLegitimacyDecision                                │
│  ├── legitimacyOutcome:    Allowed | Denied | Deferred | Invest │
│  ├── constitutionalBasis:  Rule traceability                     │
│  ├── replayHash:           Deterministic input fingerprint       │
│  ├── evidenceLineage:      All evidence items consumed           │
│  ├── sovereigntyVersion:   Evolution tracking                    │
│  ├── derivedAt:            Temporal freeze point                 │
│  ├── resolverVersion:      For deterministic replay             │
│  └── enforcementBasis:     Exclusive authority source            │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    ENFORCEMENT BOUNDARY                           │
│  EnforcementBoundary                                             │
│  ├── ─── RECEIVES ConstitutionalLegitimacyDecision ───          │
│  ├── Maps outcome → HTTP response / redirect / denial page      │
│  ├── Captures enforcement evidence for replay audit              │
│  ├── NEVER derives its own legitimacy                           │
│  ├── NEVER blocks pre-decision                                  │
│  └── Reports divergence if legacy check disagrees               │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    OUTCOME                                        │
│  Allowed:     → voter proceeds to vote                           │
│  Denied:      → voter redirected with constitutional reason     │
│  Deferred:    → additional evidence required (overlay triggered) │
│  Investigate: → logged for manual review, voter queued          │
└─────────────────────────────────────────────────────────────────┘
```

### 1.2 Invariants

| # | Invariant | Violation Consequence |
|---|---|---|
| A1.1 | NO legitimacy enforcement outside this chain | Constitutional corruption |
| A1.2 | EnforcementBoundary NEVER derives legitimacy | Procedural sovereignty re-emergence |
| A1.3 | ConstitutionalLegitimacyDecision is the ONLY sovereign object | Split sovereignty persists |
| A1.4 | PolicySequence evaluates ALL evidence before returning | Partial-traversal sovereignty |
| A1.5 | Evidence is frozen at snapshot time, not queried at decision time | Replay divergence |
| A1.6 | OverlayCoordinator observes only — never contributes to legitimacy arithmetic | Probabilistic sovereignty |

---

## Article 2: ConstitutionalLegitimacyDecision Specification

### 2.1 Type Definition

```php
readonly class ConstitutionalLegitimacyDecision
{
    public function __construct(
        // ── Sovereign Outcome ──
        public LegitimacyOutcome $outcome,
        //   Allowed  = voter may proceed
        //   Denied   = voter blocked, constitutional reason provided
        //   Deferred = insufficient evidence, overlay triggered
        //   Investigate = anomaly detected, queued for manual review

        // ── Constitutional Basis ──
        public string $constitutionalBasis,
        //   Which constitutional rule produced this outcome.
        //   Format: "PolicyName:RuleName:Version"
        //   Example: "TrustCapabilityPolicy:NetworkBinding:1.0"

        // ── Resolver Path ──
        public ResolverPath $resolverPath,
        //   Which resolver path was taken through PolicySequence.
        //   Enables deterministic replay of the interpretation chain.

        // ── Replay Integrity ──
        public string $replayHash,
        //   Deterministic SHA-256 of ALL inputs:
        //   snapshot + evidence + overlay observations + resolver version
        //   Same hash → same evaluation → same outcome (DETERMINISTIC)

        public int $snapshotVersion,
        //   Schema version of ConstitutionalEvidenceSnapshot used.
        //   Enables migration-safe replay across schema versions.

        // ── Evidence Lineage ──
        public array $evidenceLineage,
        //   Every evidence item consumed during evaluation.
        //   Each entry: { type: NetworkEvidence, version: 1.0, hash: abc }
        //   Enables full audit traceability.

        public ConstitutionalObservationContext $observationSet,
        //   All overlay observations applied during evaluation.
        //   Flat set — no ordering, no weighting, no arithmetic.

        // ── Sovereignty Tracking ──
        public string $sovereigntyVersion,
        //   Evolution tracking for the sovereignty model.
        //   Incremented when enforcement boundaries change.

        public \DateTimeImmutable $derivedAt,
        //   Temporal freeze point — when this decision was derived.
        //   NOT when it was enforced (enforcement may be async-safe).

        public string $resolverVersion,
        //   Version of the resolver engine for deterministic replay.
        //   Different versions may produce different outcomes from same input.

        public string $enforcementBasis,
        //   Exclusive authority source citation.
        //   Always: "SovereignEnforcementExclusivityDoctrine:v1.0"
    ) {}
}
```

### 2.2 LegitimacyOutcome Enum

```php
enum LegitimacyOutcome: string
{
    case Allowed    = 'allowed';      // Voter may proceed
    case Denied     = 'denied';       // Voter blocked (reason in constitutionalBasis)
    case Deferred   = 'deferred';     // Insufficient evidence — trigger overlay
    case Investigate = 'investigate'; // Anomaly — queue for manual review
}
```

### 2.3 Production Rules

| Condition | Outcome | constitutionalBasis |
|---|---|---|
| All evidence matches constitutional rules | `Allowed` | `FullConstitutionalCompliance` |
| Network evidence inconsistent | `Denied` | `NetworkBindingViolation` |
| Device evidence inconsistent | `Denied` | `DeviceBindingViolation` |
| Participation eligibility insufficient | `Denied` | `ParticipationIneligibility` |
| Session continuity broken | `Denied` | `SessionContinuityViolation` |
| Overlay signals contradictory (non-blocking) | `Deferred` | `OverlayContradiction` |
| Evidence unavailable for required check | `Deferred` | `InsufficientEvidence` |
| Replay hash mismatch (prevention only) | `Investigate` | `ReplayDivergenceDetected` |
| Multiple blocking violations | `Denied` | `CompoundViolation` |

---

## Article 3: Enforcement Boundary Specification

### 3.1 Responsibility

The EnforcementBoundary is the **ONLY** code path that may translate a `ConstitutionalLegitimacyDecision` into an HTTP response. It has no authority to derive legitimacy — only to enforce.

### 3.2 Interface

```php
interface EnforcementBoundary
{
    /**
     * Enforce a ConstitutionalLegitimacyDecision.
     * 
     * @param ConstitutionalLegitimacyDecision $decision The sovereign decision
     * @param Request $request The HTTP request context
     * 
     * @return RedirectResponse|InertiaResponse
     * 
     * @throws EnforcementWithoutDecisionException If called without a decision
     */
    public function enforce(
        ConstitutionalLegitimacyDecision $decision,
        Request $request
    ): RedirectResponse|InertiaResponse;
}
```

### 3.3 Enforcement Mapping

| Outcome | HTTP Result | Notes |
|---|---|---|
| `Allowed` | `redirect()->route('vote.create')` | With constitutional attestation in session |
| `Denied` | `redirect()->route('dashboard')->withErrors(...)` | Error includes `constitutionalBasis`, NOT cleartext evidence |
| `Deferred` | `redirect()->route('verify.identity')` | Additional verification step injected |
| `Investigate` | `redirect()->route('dashboard')->with('pending_review', true)` | Admin notification triggered |

### 3.4 Forbidden Patterns (EnforcementBoundary MUST NOT)

```
❌ Read $user->voting_ip directly
❌ Query ElectionMembership directly
❌ Check can_vote column
❌ Call isEligibleToVote()
❌ Execute legacy helper functions
❌ Access cache for legitimacy decisions
❌ Block based on middleware ordering
❌ Derive authority from topology position
```

### 3.5 Implementation Location

The EnforcementBoundary replaces the current sovereign gates:

| Current Gate | Replacement |
|---|---|
| `VoteController::store()` lines 1493-1505 `canVote()` check | `EnforcementBoundary::enforce(decision, request)` |
| `VoteController::create()` line 302 `validateVotingIpWithResponse()` | REMOVED (IP in constitutional evidence) |
| `CodeController::create()` legacy `can_vote` | REMOVED (eligibility in decision) |
| `ValidateVotingIp` middleware | REMOVED (IP in constitutional evidence) |

---

## Article 4: Shadow Enforcement Mode

### 4.1 Purpose

Before the constitutional decision becomes authoritative, run both old and new enforcement paths in parallel. Track divergence. Only switch authority after stable convergence.

### 4.2 Architecture

```text
HTTP Request
│
├── LEGACY PATH (still enforces)
│   ├── canVote() check
│   ├── ensureVoterMembership()
│   ├── validateVotingIpWithResponse() (if still present)
│   └── legacy sovereign outcome
│
├── CONSTITUTIONAL PATH (shadow — observes only)
│   ├── TrustPolicyEvaluator::evaluate()
│   ├── ConstitutionalLegitimacyDecision derived
│   ├── ENFORCEMENT BOUNDARY (records what it WOULD do)
│   └── shadow outcome
│
├── DIVERGENCE DETECTOR
│   ├── Compare: legacy outcome vs constitutional outcome
│   ├── If match:    log_ok(legacy, constitutional, latency)
│   ├── If mismatch: log_divergence(legacy, constitutional, context, snapshot)
│   │   └── alert if drift budget exceeded
│   └── store SovereigntyDivergenceRecord
│
└── LEGACY PATH ENFORCES (until switch)
```

### 4.3 SovereigntyDivergenceRecord

```php
readonly class SovereigntyDivergenceRecord
{
    public function __construct(
        public string           $divergenceType,       // outcome_mismatch, evidence_mismatch, topology_mismatch
        public LegitimacyOutcome $legacyOutcome,
        public LegitimacyOutcome $constitutionalOutcome,
        public bool             $matched,              // true = no divergence
        public string           $snapshotHash,         // for replay archaeology
        public array            $context,              // election_id, user_id, route, middleware
        public \DateTimeImmutable $observedAt,
        public ?string          $divergenceReason,     // why they differed
    ) {}
}
```

### 4.4 Shadow Switch Conditions

| Condition | Action |
|---|---|
| 0 divergences in evaluation window | Ready for authority switch |
| < 0.1% divergence rate | Monitor, investigate root cause |
| 0.1% - 1% divergence rate | Extend observation window |
| > 1% divergence rate | BLOCK switch, investigate |
| Systemic mismatch (all evaluations diverge) | ABORT — constitutional path has bug |

### 4.5 Divergence Categories

| Category | Meaning | Severity |
|---|---|---|
| Legacy allowed, constitutional denied | Legacy is over-permissive | HIGH (sovereignty leak) |
| Legacy denied, constitutional allowed | Legacy is over-restrictive | MEDIUM (false positive block) |
| Different denial reason | Both deny, different constitutional basis | LOW (semantic) |
| Legacy blocked pre-constitutional | Middleware/helper denied before TrustEval | CRITICAL (T-A finding active) |

---

## Article 5: Replay Boundaries

### 5.1 Replay Contract

```text
Given the same ConstitutionalEvidenceSnapshot,
ConstitutionalLegitimacyDecision MUST be identical
across time, space, and runtime environment.
```

### 5.2 What Must Be Frozen

| Input | Frozen At | Format |
|---|---|---|
| Election constitution | Snapshot assembly | ElectionConstitutionSnapshot |
| Verification evidence | Snapshot assembly | VerificationEvidence |
| Network evidence | Snapshot assembly | NetworkEvidence |
| Device evidence | Snapshot assembly | DeviceEvidence |
| Session continuity | Snapshot assembly | SessionContinuity |
| Participation eligibility | Snapshot assembly | ParticipationEligibilityEvidence |
| Overlay observations | Evaluation time | ConstitutionalObservationContext |
| Resolver version | Deployment | Versioned PolicySequence |

### 5.3 What Must NOT Be Queried At Enforcement Time

```
❌ ElectionMembership::where(...)->exists()
❌ User::where('can_vote', true)
❌ Code::where('has_voted', true)
❌ Member::where('fees_status', 'overdue')
❌ Cache::get('user:{uid}:election:{eid}:can_vote')
❌ DB::table('codes')->selectRaw('count(case when ...)')
```

All of these are captured in the snapshot. Enforcement reads the snapshot, not the database.

---

## Article 6: Failure Semantics

### 6.1 Failure Modes

| Failure | Behavior | Recovery |
|---|---|---|
| Evidence unavailable for required field | Outcome = `Deferred`, reason = `InsufficientEvidence` | Complete evidence before retry |
| Snapshot hash mismatch | Outcome = `Investigate`, replay divergence recorded | Evaluate fresh snapshot |
| Resolver version mismatch | Outcome depends on compatibility policy | Migrate or re-evaluate |
| Evidence schema version mismatch | Outcome = `Deferred`, version migration required | Schema migration |
| ConstitutionalLegitimacyDecision cannot be produced | Outcome = `Denied`, `constitutionalBasis` = `EvaluationFailure` | System error — admin notified |
| EnforcementBoundary called without decision | `EnforcementWithoutDecisionException` thrown | Developer error — must fix |

### 6.2 Fail-Closed Principle

If the constitutional path cannot produce a decision, the system MUST fail closed (deny access) rather than fail open (allow access). The `Denied` outcome with `EvaluationFailure` basis is the safe default.

### 6.3 Fail-Open Forbidden

```text
NO circumstance permits vote to proceed without
ConstitutionalLegitimacyDecision.
```

This includes: timeouts, cache misses, DB connection failures, resolver crashes, snapshot corruption.

---

## Article 7: Telemetry Semantics

### 7.1 Required Telemetry

| Metric | Source | Purpose |
|---|---|---|
| `constitutional.legitimacy.outcome` | Decision | Outcome distribution tracking |
| `constitutional.legitimacy.latency_ms` | Decision | Performance monitoring |
| `constitutional.legitimacy.snapshot_version` | Snapshot | Schema version tracking |
| `constitutional.shadow.divergence` | Shadow mode | Divergence rate tracking |
| `constitutional.shadow.divergence_reason` | Shadow mode | Root cause categorization |
| `constitutional.boundary.enforcement` | Boundary | Enforcement point tracking |
| `constitutional.boundary.latency_ms` | Boundary | Enforcement performance |
| `constitutional.pre_constitutional.block` | Middleware (pre-migration) | T-A finding tracking |

### 7.2 Alert Thresholds

| Metric | Warning | Critical |
|---|---|---|
| Divergence rate | > 0.1% | > 1% |
| Decision latency | > 500ms | > 2s |
| Pre-constitutional block rate | > 0 (any) | > 1% of evaluations |
| Evidence unavailable rate | > 1% | > 5% |
| Enforcement without decision | > 0 (any) | > 0 (any) |

---

## Article 8: Migration Sequencing (D.0 → D.5)

### D.0 — Bypass Sovereign Isolation

**Target:** All S8 (bypass sovereign) paths

```
Action 1:  Add ConstitutionalTransitionGuard to BulkApproveVoters
Action 2:  Add ConstitutionalTransitionGuard to BulkDisapproveVoters
Action 3:  Add ConstitutionalTransitionGuard to ActivateElectionCommand
Action 4:  Feature-flag OFF validate.voting.ip middleware
Action 5:  Create SovereignEnforcementFlowSpec (this document)
```

**Gate:** No S8 bypass sovereign active

### D.0s — Shadow Enforcement Deployment

**Target:** Dual-sovereignty telemetry

```
Action 1:  Implement ConstitutionalLegitimacyDecision type
Action 2:  Implement SovereigntyDivergenceRecord type
Action 3:  Deploy shadow enforcement in VoteController::store()
Action 4:  Track divergence during observation window (full election cycle)
Action 5:  Deploy shadow in VoteController::create()
Action 6:  Track divergence for all voting paths
```

**Gate:** < 0.1% divergence rate across full cycle

### D.1 — Constitutional Enforcement Wiring

**Target:** Wire TrustEval into sovereign gate

```
Action 1:  Implement EnforcementBoundary interface
Action 2:  Replace canVote() gate with EnforcementBoundary::enforce()
Action 3:  Remove ValidateVotingIp middleware
Action 4:  Remove validateVotingIpWithResponse() call sites (6)
Action 5:  Replace CodeController legacy can_vote check
```

**Gate:** ConstitutionalLegitimacyDecision enforces all vote paths

### D.2 — Procedural Sovereignty Retirement

**Target:** All S5 (mutable-runtime) + S7 (non-deterministic) paths

```
Action 1:  Retire legacy can_vote column (no code reads it)
Action 2:  Retire cache sovereignty (no cache used for legitimacy)
Action 3:  Retire isEligibleToVote() legacy method
Action 4:  Retire check_ip_address() global function
Action 5:  Retire vote_pre_check() legacy validation
Action 6:  Set after_commit=true for all eligibility jobs
```

**Gate:** All enforcement derives from snapshot-frozen evidence

### D.3 — Replay Certification

**Target:** Cross-runtime replay determinism

```
Action 1:  Snapshot sufficiency completion (fee state, approval lineage)
Action 2:  Schema versioning for all evidence types
Action 3:  Cross-runtime replay certification test
Action 4:  Replay divergence detection in CI pipeline
```

**Gate:** Certify same snapshot → same decision everywhere

### D.4 — Monotonicity Certification

**Target:** Sovereignty monotonicity + constitutional algebra boundary

```
Action 1:  Verify no overlay arithmetic produces sovereignty
Action 2:  Verify additional observations never weaken insufficiency
Action 3:  Formal monotonicity test suite
```

**Gate:** Constitutional algebra boundary certified

### D.5 — Full Sovereignty Convergence

**Target:** ALL legitimacy derives from constitutional runtime

```
Action 1:  Sovereign enforcement exclusivity certification
Action 2:  Zero divergence enforcement
Action 3:  Replay-certifiable evaluation
Action 4:  Monotonicity-certified observation
Action 5:  Temporal sovereignty guaranteed
Action 6:  Snapshot-sufficient for legitimacy reconstruction
```

**Gate:** System is constitutional sovereign runtime

---

## Article 9: Constitutional Governance

### 9.1 Document Authority

This specification is binding. No code change may deviate from the authority chain defined herein without an amendment to this document.

### 9.2 Amendment Process

1. Proposed amendment must document the deviation
2. Must include: rationale, replay impact, divergence impact, rollback plan
3. Must be approved by constitutional governance review
4. Specification updated to reflect amendment

### 9.3 Violation Consequences

| Violation | Consequence |
|---|---|
| Enforcement outside authority chain | Rollback, constitutional review |
| Shadow divergence exceeds budget | Block switch, investigate |
| Pre-constitutional authority introduced | Block merge, architectural review |
| Cache used for legitimacy | Invalidate, remove cache, review |
| Evidence queried at enforcement time | Freeze in snapshot, review |

---

## Appendix A: Current State vs Target State

### VoteController::store() — Current

```php
// Current: S6 split sovereignty
$trustEnvelope = $this->trustEvaluator->evaluate(...);
\Log::info('trust result', [...trustEnvelope...]); // DISCARDED

$lifecycle = ElectionLifecycle::of($election);
if (!$lifecycle->canVote()) { /* block */ } // INDEPENDENT GATE

if ($redirect = $this->ensureVoterMembership(...)) { /* block */ } // DB QUERY
```

### VoteController::store() — Target

```php
// Target: S2 replay-safe sovereign
$decision = $this->trustEvaluator->evaluate(...); // ConstitutionalLegitimacyDecision

return $this->enforcementBoundary->enforce($decision, $request);
// EnforcementBoundary maps outcome → response
// NEVER queries DB, NEVER checks legacy state, NEVER derives its own authority
```

### Middleware Stack — Current

```php
// Position 8: validate.voting.ip  — S8 bypass sovereign
// Position 7: vote.eligibility    — constitutionally delegated (observational)
// Position 11: voting.active      — constitutionally delegated (observational)
```

### Middleware Stack — Target

```php
// All middleware above controller is evidence-preserving, not sovereign
// voting.active remains as defense-in-depth (already delegates to ElectionLifecycle)
// validate.voting.ip REMOVED (IP in constitutional evidence)
// vote.eligibility remains as defense-in-depth (already delegates)
```

---

## Appendix B: Transition Guard Design

```php
/**
 * ConstitutionalTransitionGuard
 * 
 * Blocks command execution if it would bypass the constitutional
 * sovereignty path. All legitimacy mutations must go through
 * the constitutional mutation workflow.
 */
final class ConstitutionalTransitionGuard
{
    /**
     * Assert that a command is allowed to proceed.
     *
     * @throws ConstitutionalBypassException
     */
    public function assertCanExecute(string $commandName, array $context): void
    {
        throw new ConstitutionalBypassException(
            command: $commandName,
            message: sprintf(
                'Command %s bypasses constitutional sovereignty. '
                . 'Use ConstitutionalLegitimacyMutation instead.',
                $commandName
            )
        );
    }
}
```

---

## Appendix C: EnforcementBoundary Implementation Sketch

```php
final class VoteEnforcementBoundary implements EnforcementBoundary
{
    public function __construct(
        private readonly SovereigntyDivergenceTracker $divergenceTracker,
        private readonly LegacyGate $legacyGate, // REMOVED after D.1
    ) {}

    public function enforce(
        ConstitutionalLegitimacyDecision $decision,
        Request $request
    ): RedirectResponse|InertiaResponse {
        
        // ── Shadow mode: track divergence against legacy gate ──
        if ($this->legacyGate->isActive()) {
            $legacyOutcome = $this->legacyGate->evaluate($request);
            $this->divergenceTracker->track(
                legacy: $legacyOutcome,
                constitutional: $decision->outcome,
                context: ['election_id' => ..., 'user_id' => ...],
                snapshotHash: $decision->replayHash,
            );
        }

        // ── Sovereign enforcement ──
        return match ($decision->outcome) {
            LegitimacyOutcome::Allowed => redirect()->route('vote.create')
                ->with('constitutional_attestation', $decision->replayHash),

            LegitimacyOutcome::Denied => redirect()->route('dashboard')
                ->withErrors(['vote' => $this->denialMessage($decision)]),

            LegitimacyOutcome::Deferred => redirect()->route('verify.identity')
                ->with('constitutional_context', $decision->replayHash),

            LegitimacyOutcome::Investigate => redirect()->route('dashboard')
                ->with('pending_review', true)
                ->with('constitutional_context', $decision->replayHash),
        };
    }

    /**
     * Produce user-facing denial message from constitutional basis.
     * NEVER exposes cleartext evidence or internal state.
     */
    private function denialMessage(ConstitutionalLegitimacyDecision $decision): string
    {
        return match ($decision->constitutionalBasis) {
            'NetworkBindingViolation' => 'Your network location does not match the registered address for voting.',
            'DeviceBindingViolation' => 'Your device could not be verified. Please use your registered device.',
            'ParticipationIneligibility' => 'You are not currently eligible to vote in this election.',
            'SessionContinuityViolation' => 'Your voting session could not be verified. Please start again.',
            'ReplayDivergenceDetected' => 'A security check failed. Please contact the election committee.',
            default => 'Voting is not currently permitted.',
        };
    }
}
```
