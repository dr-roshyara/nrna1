# C.5e — Sovereignty Convergence Audit Plan

**Date:** 2026-05-28
**Phase:** C.5e — Sovereignty Convergence Audit
**Status:** PLANNING
**Commandment:** *Move all legitimacy authority from procedural topology into replay-addressable constitutional derivation.*

---

## Executive Framework

This is NOT a code audit. This is **sovereignty relocation engineering**.

The mission:

```text
Current state:   constitutional runtime observes legitimacy
Target state:    constitutional runtime owns legitimacy
```

This requires: Sovereignty Source Graphs, Convergence Matrix, Snapshot Sufficiency,
Temporal Graphs, Doctrine, ConstitutionalLegitimacyDecision, Telemetry, Drift Budget.

---

## Audit Dimension 1: Sovereignty Source Graphs

For every legitimacy-affecting action, build a complete sovereignty graph:

```text
ACTION
→ authority source (where does legitimacy come from?)
→ mutable dependency (what runtime state does it read?)
→ replay source (is it frozen in a snapshot?)
→ enforcement location (where is the actual gate?)
→ snapshot participation (does it produce or consume evidence?)
→ resolver participation (does PolicySequence interpret it?)
→ topology sensitivity (does ordering/position matter?)
→ temporal sensitivity (does async timing affect it?)
→ sovereignty class (S1-S8)
```

### Sovereignty Graphs to Build

| # | Action | Currently Located |
|---|---|---|
| SG-1 | Vote Allowed/Denied (real election) | VoteController::first_submission() |
| SG-2 | Vote Allowed/Denied (demo election) | DemoVoteController::first_submission() |
| SG-3 | Code Creation Allowed/Denied | CodeController::create() |
| SG-4 | Voter Approved | BulkApproveVoters command |
| SG-5 | Voter Disapproved | BulkDisapproveVoters command |
| SG-6 | Membership Status Change | MarkOverdueMembers / ExpireMemberships jobs |
| SG-7 | Election State Transition | Election lifecycle transition |
| SG-8 | IP Address Enforcement | ValidateVotingIp middleware + helper |
| SG-9 | Eligibility Cache Hit/Miss | Cache layer for can_vote |
| SG-10 | Slug Creation Allowed/Denied | VoterSlugService |

### SG-1 Example Graph

```text
Vote Allowed (real election)
→ authority source: ElectionLifecycle::canVote() + ensureVoterMembership()
→ mutable dependency: ElectionMembership.status (DB query at runtime)
→ replay source: NOT snapshot-bound (ConstitutionalEvidenceSnapshot exists but unused)
→ enforcement location: VoteController::first_submission() line 1493-1505
→ snapshot participation: TrustPolicyEvaluator produces snapshot, but it's discarded
→ resolver participation: PolicySequence evaluates, but result is not enforced
→ topology sensitivity: HIGH — 10 middleware layers execute before this point
→ temporal sensitivity: MEDIUM — cache staleness, async membership changes
→ sovereignty class: S6 (split-sovereignty — observes in one path, enforces in another)
```

---

## Audit Dimension 2: Enforcement Convergence Matrix

For every enforcement point:

### Real Election Voting

| Enforcement Path | Current Authority | S-Class | Future Authority | Convergence Strategy | D-Phase |
|---|---|---|---|---|---|
| `validateVotingIpWithResponse()` | Helper function | S8 bypass | PolicySequence derived | Retire: replace with constitutional IP evidence | D.0 |
| `ValidateVotingIp` middleware | Middleware position 8 | S3 procedural | Snapshot evaluation | Remove: IP evidence already in NetworkTrustEvidence | D.0 |
| `VoteEligibility` legacy fallback | `$user->isEligibleToVote()` | S5 mutable | ElectionLifecycle::canVote() | Retire: complete slug migration | D.2 |
| `CodeController::can_vote` check | `$user->can_vote` column | S5 mutable | ConstitutionalLegitimacyDecision | Replace: constitutional gate | D.2 |
| `ElectionLifecycle::canVote()` | Membership query + state | S6 split | ConstitutionalEvidenceSnapshot | Rewrite: derive from frozen evidence | D.1 |
| `ensureVoterMembership()` | Cache + DB query | S5 mutable | Snapshot-bound check | Rewrite: use snapshot evidence | D.1 |
| `TrustPolicyEvaluator` result | Logged only | S1 observe | Sovereign gate | Wire: enforce instead of discard | D.1 |

### Commands & Async

| Enforcement Path | Current Authority | S-Class | Future Authority | Convergence Strategy | D-Phase |
|---|---|---|---|---|---|
| `BulkApproveVoters` | Direct `can_vote=true` | S8 bypass | ConstitutionalLegitimacyMutation | Replace: constitutional mutation workflow | D.0 |
| `BulkDisapproveVoters` | Direct `can_vote=false` | S8 bypass | ConstitutionalLegitimacyMutation | Replace: constitutional mutation workflow | D.0 |
| `MarkOverdueMembers` | Direct fees_status mutation | S5 mutable | Evidence-producing transition | Rewrite: emit constitutional evidence | D.2 |
| `ActivateElectionCommand` | Bypasses ConstitutionalGuard | S8 bypass | ConstitutionalTransitionGuard | Block: require guard | D.0 |

### Cache

| Enforcement Path | Current Authority | S-Class | Future Authority | Convergence Strategy | D-Phase |
|---|---|---|---|---|---|
| `election:{eid}` 24h cache | Stale config | S7 non-det | Snapshot-frozen config | Fix: bind to snapshot or invalidate | D.1 |
| `user:{uid}:election:{eid}:can_vote` | Stale eligibility | S7 non-det | No cache for legitimacy | Fix: remove sovereign cache | D.1 |
| `election.voting_period_active` | Stale period state | S7 non-det | State machine check | Fix: remove or snapshot-bind | D.2 |

---

## Audit Dimension 3: Snapshot Sufficiency Analysis

**Question:** Can legitimacy be fully reconstructed from `ConstitutionalEvidenceSnapshot` alone?

### Current Snapshot Contents

| Evidence Field | Present in Snapshot? | Mutable Runtime Equivalent |
|---|---|---|
| Election constitution | YES (ElectionConstitutionSnapshot) | `election` table row |
| Verification evidence | YES (VerificationEvidence) | VoterVerification records |
| Network evidence | YES (NetworkEvidence) | IP, whitelist, binding strategy |
| Device evidence | YES (DeviceEvidence) | Fingerprint, device trust |
| Session continuity | YES (SessionContinuity) | Session state |
| Participation eligibility | YES (ParticipationEligibilityEvidence) | ElectionMembership status |
| **Evaluated at** | YES | N/A |
| **Constitutional hash** | YES | N/A |

### Missing Snapshot Evidence

| Missing Evidence | Current Runtime Dependency | Replay Risk | Convergence Priority |
|---|---|---|---|
| **Membership status history** | `ElectionMembership.status` (live query) | CRITICAL — can change between evaluations | HIGH |
| **Fee/dues state** | `Member.fees_status` (live query or async mutation) | HIGH — changed by async jobs | MEDIUM |
| **Approval lineage** | `User.can_vote` legacy column | CRITICAL — mutated by commands | HIGH |
| **Suspension state** | User suspension columns | CRITICAL — BulkApprove clears suspension | HIGH |
| **Election state machine** | `Election.state` (live query or cached) | HIGH — state transitions, 24h cache | MEDIUM |
| **Voter registration history** | `ElectionMembership` timestamps | MEDIUM — mutable between evaluations | LOW |

### Snapshot Completeness Score

| Aspect | Score | Notes |
|---|---|---|
| Evidence captured | 7/7 evidence types present | ParticipationEligibilityEvidence added by TSC-1 |
| Evidence frozen | ✅ All 7 frozen at evaluation time | buildEligibilityEvidence() queries membership once |
| Replay determinism | ✅ Same snapshot → same result | Deterministic hash for divergence detection |
| **Enforcement derivation** | **❌ NOT USED** | Snapshot captured but never enforced |
| **All legitimacy dependencies** | **❌ INCOMPLETE** | Missing: fee state, approval lineage, suspension, state machine |

**Conclusion:** Snapshot is content-complete for constitutional evaluation but procedurally irrelevant because enforcement does not derive from it. Additionally, several legitimacy dependencies (fee state, approval lineage) are not captured in the snapshot.

---

## Audit Dimension 4: Temporal Sovereignty Graphs

Map timing itself as authority source.

### TG-1: Pre-Commit Dispatch Divergence

```text
mutation (DB transaction)
→ after_commit = false job dispatch
→ job executes BEFORE commit
→ job sees pre-commit state
→ if transaction rolls back: job operates on phantom state
→ replay cannot reproduce: replay sees committed state only
→ SOVEREIGNTY DIVERGENCE: original run ≠ replay
```

**Affected paths:** All queue jobs dispatched during constitutional evaluation without `after_commit: true`

### TG-2: Cache Freshness Window

```text
constitutional evaluation
→ reads cached election config (24h stale)
→ produces snapshot with stale values
→ actual config changes
→ next evaluation within 24h: same stale values
→ DIVERGENCE: snapshot is deterministically wrong
```

**Affected paths:** T-E.1, T-E.2

### TG-3: Async Eligibility Mutation Race

```text
BulkApproveVoters runs at T+0
→ sets can_vote=true for voter
→ voter's evaluation at T+1: sees can_vote=true
→ evaluation at T-1 (before approval): would see can_vote=false
→ REPLAY: same snapshot evaluated at different times → different result
```

**Affected paths:** All T-B findings

### TG-4: Event Emission → Consumption Gap

```text
ElectionStateChangedEvent dispatched at T+0
→ zero listeners registered
→ no consumer processes the transition
→ next evaluation at T+1: no evidence of transition
→ REPLAY GAP: transition is invisible to constitutional runtime
```

**Affected paths:** T-C.1 (12 orphaned events)

---

## Audit Dimension 5: Constitutional Enforcement Boundary

### Sovereign Enforcement Exclusivity Doctrine (Final Form)

```text
NO legitimacy denial
outside ConstitutionalLegitimacyDecision.
```

**Forbidden:**

| Pattern | Current Example | Enforcement |
|---|---|---|
| Controller deny | `if (!$canVote()) return back()->withErrors(...)` | MUST derive from decision |
| Middleware deny | `ValidateVotingIp` blocks before snapshot | MUST be removed |
| Helper deny | `validateVotingIpWithResponse()` renders denial | MUST be removed |
| Queue deny | No current example — but async denial forbidden | Guard added |
| Cache deny | Stale `can_vote=false` blocks legitimate voter | MUST be removed |
| Event deny | No current example — but event-sourced denial forbidden | Guard added |
| Command deny | `BulkDisapproveVoters` revokes sovereignty | MUST be blocked |

**Only allowed:**

```text
ConstitutionalEvidenceSnapshot
→ ConstitutionalLegitimacyDecision
→ sovereign outcome (allowed/denied/deferred/investigate)
→ enforcement occurs at decision boundary
```

---

## Audit Dimension 6: ConstitutionalLegitimacyDecision Design

This becomes the sovereign object of the entire runtime.

### Proposed Structure

```php
readonly class ConstitutionalLegitimacyDecision
{
    public function __construct(
        // Sovereign outcome
        public LegitimacyOutcome       $outcome,          // Allowed | Denied | Deferred | Investigate
        
        // Constitutional basis
        public string                  $constitutionalBasis,  // Which rule produced this outcome
        public string                  $resolverPath,         // Which resolver path was taken
        
        // Replay integrity
        public string                  $replayHash,           // Deterministic hash of all inputs
        public int                     $snapshotVersion,      // Which snapshot schema version
        
        // Evidence lineage
        public array                   $evidenceLineage,      // All evidence items used
        public array                   $observationSet,       // All overlay observations applied
        
        // Sovereignty tracking
        public string                  $sovereigntyVersion,   // Evolution of sovereignty model
        public \DateTimeImmutable      $derivedAt,            // Temporal freeze point
        public string                  $resolverVersion,      // For deterministic replay
        public string                  $enforcementBasis,     // Exclusive authority source
    ) {}
}
```

### Integration Points

| Integration | Current | Future |
|---|---|---|
| VoteController gate | `ElectionLifecycle::canVote()` | `decision->outcome` |
| Overlay influence | Logged only | Contributes to `observationSet` |
| PolicySequence | Returns `VotingTrustResult` | Produces `LegitimacyOutcome` |
| TrustEvaluationEnvelope | Contains result + observations | `ConstitutionalLegitimacyDecision` |
| Middleware | Blocks pre-evaluation | Removed (decision is authoritative) |
| Commands | Direct mutation | `LegitimacyMutation` workflow |

---

## Audit Dimension 7: Dual-Sovereignty Telemetry

Before replacing procedural sovereignty, run observation window.

### Telemetry Points

| Telemetry | Source | Compares |
|---|---|---|
| `trust_vs_canVote` | VoteController | TrustEval result vs canVote() outcome |
| `trust_vs_middleware` | Middleware exit points | What middleware blocked vs what TrustEval would decide |
| `trust_vs_helper` | validateVotingIpWithResponse call sites | Helper denial vs TrustEval network evidence |
| `trust_vs_legacy_canVote` | CodeController | Legacy column vs constitutional eligibility |
| `snapshot_vs_runtime` | Evaluation boundary | Snapshot-frozen values vs current DB values |
| `cache_vs_fresh` | Cache hit points | Cached value vs fresh query |
| `async_divergence` | Queue job boundaries | Pre-commit vs post-commit state |

### Telemetry Envelope

```php
readonly class SovereigntyDivergenceRecord
{
    public function __construct(
        public string           $divergenceType,     // trust_vs_canVote, cache_vs_fresh, etc.
        public string           $legacyOutcome,
        public string           $constitutionalOutcome,
        public bool             $matched,
        public ?string          $snapshotHash,
        public \DateTimeImmutable $observedAt,
    ) {}
}
```

### Observation Window

| Phase | Duration | Gate |
|---|---|---|
| D.0 observation | Full election cycle | Telemetry installed |
| D.1 analysis | Post-election | < 1% divergence rate |
| D.2 convergence | Next election cycle | Zero divergence |

---

## Audit Dimension 8: Authority Drift Budget

During convergence, define maximum tolerated legitimacy divergence.

### Drift Budget Table

| Drift Type | Allowed During Convergence? | Maximum Tolerance | Convergence Target |
|---|---|---|---|
| Replay mismatch | NO | Zero | Zero |
| Topology mismatch | NO | Zero | Zero |
| Cache mismatch (election config) | TEMPORARY | 1 hour window | Zero |
| Cache mismatch (eligibility) | TEMPORARY | 5 minute window | Zero |
| Async ordering mismatch | TEMPORARY | < 0.1% of evaluations | Zero |
| Stale eligibility mismatch | TEMPORARY | < 1% of evaluations | Zero |
| Pre-commit dispatch divergence | TEMPORARY | Zero tolerance for eligibility | after_commit=true |

### Budget Enforcement

```text
Drift budget is NOT advisory.
Exceeding drift budget = ROLLBACK trigger.
```

---

## Audit Execution Sequence

| Step | Dimension | Output | Effort |
|---|---|---|---|
| **C.5e.1** | Sovereignty Source Graphs (SG-1 through SG-10) | 10 complete sovereignty graphs | 3-4h |
| **C.5e.2** | Enforcement Convergence Matrix | Complete matrix (all enforcement paths) | 2h |
| **C.5e.3** | Snapshot Sufficiency Analysis | Completeness score + gap list | 2h |
| **C.5e.4** | Temporal Sovereignty Graphs | TG-1 through TG-4 complete | 1-2h |
| **C.5e.5** | Constitutional Enforcement Boundary (Doctrine) | Sovereign Enforcement Exclusivity Doctrine codified | 1h |
| **C.5e.6** | ConstitutionalLegitimacyDecision Design | Type specification + integration plan | 2h |
| **C.5e.7** | Dual-Sovereignty Telemetry Design | Telemetry points + SovereigntyDivergenceRecord | 1-2h |
| **C.5e.8** | Authority Drift Budget | Budget table + enforcement mechanism | 1h |
| **C.5e.9** | D.0-D.5 Sovereignty Transfer Protocol | Phase redefinition + sequencing | 2h |

### Total Estimated Effort: 15-18 hours

---

## D.0-D.5 as Sovereignty Transfer Protocols

Redefining phases as sovereignty transfers, not deletion phases:

### D.0 — Pre-Constitutional Authority Transfer

Transfer from middleware/helper topology to constitutional evidence:

| Transfer | Source | Target | Success Criteria |
|---|---|---|---|
| IP enforcement | ValidateVotingIp middleware | NetworkTrustEvidence in snapshot | No middleware IP blocking |
| IP enforcement | validateVotingIpWithResponse() | Constitutional IP evaluation | No helper-based IP denial |
| Can vote mutation | BulkApprove/BulkDisapprove | ConstitutionalTransitionGuard | No direct can_vote mutation |

**Gate:** All S3/S8 paths neutralized

### D.1 — Constitutional Enforcement Wiring

Wire TrustEval result into sovereign gate:

| Transfer | Source | Target | Success Criteria |
|---|---|---|---|
| Vote gating | canVote() procedural | ConstitutionalLegitimacyDecision | Decision gates voting |
| Telemetry | Observation | SovereigntyDivergenceRecord | Full divergence tracking |
| Membership evidence | DB query | Snapshot-frozen | No runtime membership read during evaluation |

**Gate:** Zero enforcement divergence observed

### D.2 — Procedural Sovereignty Retirement

| Transfer | Source | Target | Success Criteria |
|---|---|---|---|
| Legacy can_vote column | Column read | Retired | No code reads can_vote |
| Legacy eligibility methods | isEligibleToVote() | Retired | No fallback path active |
| Cache sovereignty | Cache as authority | Removed | No cache used for legitimacy |

**Gate:** All S5/S7 paths retired

### D.3 — Replay Certification

| Transfer | Source | Target | Success Criteria |
|---|---|---|---|
| Snapshot completeness | Partial | Complete | All legitimacy deps in snapshot |
| Cross-runtime replay | Not verified | Certified | Same snapshot → same result everywhere |
| Schema versioning | None | Versioned | Schema evolution preserves replay |

**Gate:** Replay certification complete

### D.4 — Monotonicity Certification

| Transfer | Source | Target | Success Criteria |
|---|---|---|---|
| Sovereignty monotonicity | Not verified | Certified | No observational abundance weakens insufficiency |
| Constitutional algebra | Not verified | Certified | No arithmetic on observations |

**Gate:** Monotonicity certification complete

### D.5 — Sovereignty Transfer Complete

**Gate:** All sovereignty now lives in constitutional runtime. Procedural topology is observational only.

```text
D.5 Certification:
✅ All legitimacy enforcement derives from ConstitutionalLegitimacyDecision
✅ Zero divergence between constitutional and enforcement paths
✅ Replay-certifiable for all evaluation paths
✅ Monotonicity-certified for all observation paths
✅ Temporal sovereignty guaranteed (no timing-based divergence)
✅ Snapshot-sufficient for legitimacy reconstruction
```

---

## C.5e Gate Conditions

Before proceeding to D.0:

- [ ] SG-1 through SG-10 complete — sovereignty graphs for all 10 legitimacy-affecting actions
- [ ] Enforcement Convergence Matrix complete — every enforcement path has convergence strategy
- [ ] Snapshot Sufficiency Analysis complete — all missing evidence identified
- [ ] TG-1 through TG-4 complete — temporal sovereignty mapped
- [ ] Sovereign Enforcement Exclusivity Doctrine codified
- [ ] ConstitutionalLegitimacyDecision designed and planned
- [ ] Dual-sovereignty telemetry designed and planned
- [ ] Authority drift budget defined
- [ ] D.0-D.5 Sovereignty Transfer Protocols defined
- [ ] **C.5d findings (all 15) confirmed no unaddressed CRITICAL paths**
