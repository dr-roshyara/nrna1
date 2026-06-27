# C.5d — Sovereignty Topology Archaeology Report

**Date:** 2026-05-28
**Phase:** C.5d — Topology Leakage Audit
**Status:** COMPLETE
**Commandment:** *Prove that sovereignty does NOT emerge from execution topology.*

---

## Executive Summary

C.5d uncovered the deepest architectural finding of the entire constitutional migration:

> **The constitutional runtime is currently non-sovereign infrastructure.**

The system exists in a **dual-sovereignty transitional state**:

| Sovereignty Type | Current Location |
|---|---|
| Observational sovereignty | Constitutional runtime (TrustPolicyEvaluator, Snapshots, Overlays) |
| Enforcement sovereignty | Controllers, Middleware, Commands (procedural topology) |

The architecture currently:
```text
evaluate constitutional legitimacy
→ discard result
→ enforce via procedural paths
```

**6 topology classes identified** across 15 distinct findings, 3 rated CRITICAL, 5 rated HIGH.

---

## Topology Finding Inventory

| ID | Class | Finding | Criticality |
|---|---|---|---|
| T-A.1 | Pre-constitutional | `ValidateVotingIp` middleware blocks before snapshot | 🔴 CRITICAL |
| T-A.2 | Pre-constitutional | `validateVotingIpWithResponse()` bypasses constitutional layer entirely | 🔴 CRITICAL |
| T-A.3 | Pre-constitutional | `CodeController` legacy `$user->can_vote` check | 🔴 HIGH |
| T-A.4 | Pre-constitutional | `VoteEligibility` legacy fallback reads `$user->isEligibleToVote()` | 🟡 MEDIUM |
| T-B.1 | Temporal mutation | `BulkApproveVoters` command: direct `can_vote=true` mutation | 🔴 CRITICAL |
| T-B.2 | Temporal mutation | `BulkDisapproveVoters` command: direct sovereignty revocation | 🔴 CRITICAL |
| T-B.3 | Temporal mutation | `MarkOverdueMembers` affects eligibility outside constitutional closure | 🟡 MEDIUM |
| T-B.4 | Temporal mutation | `after_commit = false` — pre-commit sovereignty divergence | 🔴 HIGH |
| T-C.1 | Async leakage | 12 orphaned domain events with zero registered listeners | 🟡 MEDIUM |
| T-C.2 | Async leakage | Default queue = sync; production-only job guards (untestable) | 🟡 MEDIUM |
| T-D.1 | Topology precedence | Middleware stack order creates implicit sovereignty (10 layers) | 🔴 HIGH |
| T-D.2 | Topology precedence | Demo routes lack `validate.voting.ip` — inconsistent topology | 🟢 LOW |
| T-E.1 | Cache legitimacy | `election:{eid}` 24h cache — stale objects persist across evaluations | 🔴 HIGH |
| T-E.2 | Cache legitimacy | `user:{uid}:election:{eid}:can_vote` 10min cache — stale eligibility | 🟡 MEDIUM |
| T-F.1 | Replay divergence | TrustPolicyEvaluator result logged but NEVER used to gate voting | 🔴 CRITICAL |

---

## T-A: Pre-Constitutional Authority

### Finding T-A.1 — ValidateVotingIp Middleware (SOVEREIGN)

**Location:** `app/Http/Middleware/ValidateVotingIp.php:22-76`
**Position:** Voting route middleware stack, position 8 of 10

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — can block vote before constitutional evaluation |
| **Is replay-addressable?** | NO — blocks before TrustPolicyEvaluator runs |
| **Is deterministic?** | YES — deterministic IP comparison |
| **Is ordering-sensitive?** | YES — position 8 in stack, runs AFTER vote.eligibility |
| **Can async timing affect it?** | NO — synchronous middleware |
| **Can retries alter outcome?** | NO — same IP → same result |
| **Is snapshot-bound?** | NO — no snapshot exists when this runs |
| **Does it bypass resolver exclusivity?** | YES — sovereign denial without PolicySequence |

**Description:**
Reads `$user->voting_ip` cleartext, compares to `request()->ip()`, blocks via `back()->withErrors()`. Executes BEFORE `TrustPolicyEvaluator::evaluate()` in the controller. The constitutional system could grant legitimacy; middleware blocks anyway.

**Constitutional violation:** Topology order creates sovereignty. Feature flag `voting_security.control_ip_address` controls enablement.

**Risk Assessment:**
- The middleware is config-driven (`config('voting_security.ip_mismatch_action', 'block')`) — can be set to 'warn' for observation
- But in default 'block' mode, it is a **sovereign pre-constitutional gate**
- Cannot be replayed because no snapshot exists when it executes
- H.1 finding from C.5b confirmed

**Recommendation:**
- D.0 retirement target: feature-flag OFF first
- Migrate IP evidence into constitutional evaluation (already partially done via `NetworkTrustEvidence`)
- `validate.voting.ip` should become constitutional evidence, not middleware authority

---

### Finding T-A.2 — validateVotingIpWithResponse() Helper (SOVEREIGN)

**Location:** `app/Helpers/helpers.php:192-244`
**Call sites:** VoteController:302, 1582, 1950; DemoVoteController:364, 1579, 1966

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — renders VoteDenied page, terminates flow |
| **Is replay-addressable?** | NO — bypasses TrustPolicyEvaluator entirely |
| **Is deterministic?** | YES — deterministic IP comparison |
| **Is ordering-sensitive?** | YES — called at specific points in controller logic |
| **Can async timing affect it?** | NO — synchronous |
| **Can retries alter outcome?** | NO — same IP → same result |
| **Is snapshot-bound?** | NO — no constitutional context |
| **Does it bypass resolver exclusivity?** | YES — full sovereign authority without constitutional path |

**Description:**
Global helper function that reads `$auth_user->voting_ip` cleartext and compares to `request()->ip()`. On mismatch, renders `Vote/VoteDenied` Inertia page with full denial UI. Exposes `registered_ip` cleartext in the denied response (privacy concern).

**6 call sites** across VoteController and DemoVoteController. Each call site is a separate sovereignty point that can terminate voting without constitutional evaluation.

**Risk Assessment:**
- Each of the 6 call sites is a sovereign authority point outside constitutional topology
- Exposes cleartext IP addresses in denial response (privacy)
- Bypasses ALL constitutional infrastructure: evidence freezing, replay capture, resolver interpretation
- H.2 finding from C.5b confirmed

**Recommendation:**
- Replace with constitutional evaluation BEFORE any legacy check
- Remove cleartext IP exposure from denial responses
- 6 call sites must be reduced to 0 by D.0

---

### Finding T-A.3 — CodeController Legacy can_vote Check

**Location:** `app/Http/Controllers/CodeController.php:698`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — directly denies code creation |
| **Is replay-addressable?** | NO — reads DB at runtime, no frozen evidence |
| **Is deterministic?** | YES — deterministic column read |
| **Is ordering-sensitive?** | YES — runs at code creation (Step 1) before any constitutional evaluation |
| **Can async timing affect it?** | YES — if can_vote is mutated asynchronously |
| **Can retries alter outcome?** | YES — if retry occurs after can_vote mutation |
| **Is snapshot-bound?** | NO — reads mutable DB column |
| **Does it bypass resolver exclusivity?** | YES — `$user->can_vote` is a legacy column, not resolver-derived |

**Description:**
`return $user && $user->can_vote === true;` — direct column read from User model. This legacy column bypasses all constitutional infrastructure. The constitutional resolver path (`ElectionLifecycle::canVote()`) exists but is NOT used here.

**Risk Assessment:**
- Mutated by BulkApproveVoters command (see T-B.1)
- Can change between retries
- No snapshot evidence captured at time of check
- Migrated voters may have correct ElectionMembership but legacy `can_vote=false`

**Recommendation:**
- Replace with constitutional `ElectionLifecycle::canVote()` delegation
- Legacy `can_vote` column must be feature-flagged and retired in D.2

---

### Finding T-A.4 — VoteEligibility Legacy Fallback

**Location:** `app/Http/Middleware/VoteEligibility.php:97-111`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — denies access when legacy path triggered |
| **Is replay-addressable?** | NO — reads mutable DB state |
| **Is deterministic?** | PARTIAL — depends on `isEligibleToVote()` implementation |
| **Is ordering-sensitive?** | YES — runs as middleware position 6, BEFORE validate.voting.ip |
| **Can async timing affect it?** | YES — if eligibility mutated async |
| **Can retries alter outcome?** | MAYBE — if eligibility state changes between retries |
| **Is snapshot-bound?** | NO — runtime DB query |
| **Does it bypass resolver exclusivity?** | YES — `$user->isEligibleToVote()` reads `can_vote` column |

**Description:**
When voter_slug is NOT present (legacy flow), the middleware falls back to `$user->isEligibleToVote()` and `$user->getBallotAccessStatus()`. These are legacy methods reading `can_vote` and related User columns.

Note: The voter_slug path (lines 55-82) delegates to `ElectionLifecycle::canVote()` which IS constitutional ✅. The legacy fallback is only triggered when no voter_slug exists.

**Risk Assessment:**
- Legacy flow fallback path — decreasing usage as slug migration progresses
- Still active for non-slug voting routes
- Constitutional path EXISTS (voter_slug branch) but is not universally used

**Recommendation:**
- Complete slug migration to eliminate legacy fallback
- Track legacy fallback activations via telemetry
- D.2 deletion target

---

## T-B: Temporal Mutation Drift

### Finding T-B.1 — BulkApproveVoters Command (CRITICAL)

**Location:** `app/Console/Commands/BulkApproveVoters.php:121-139`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — directly sets `can_vote=true` |
| **Is replay-addressable?** | NO — direct mutation, no evidence capture |
| **Is deterministic?** | YES — always sets to true |
| **Is ordering-sensitive?** | YES — running after checks creates window |
| **Can async timing affect it?** | YES — async mutation during active evaluation |
| **Can retries alter outcome?** | YES — multiple runs = cumulative effect |
| **Is snapshot-bound?** | NO — direct User model update |
| **Does it bypass resolver exclusivity?** | YES — writes sovereignty directly without PolicySequence |

**Description:**
Console command that directly sets `can_vote=true`, `suspendedBy=null`, `suspended_at=null`, and optionally `voting_ip` on the User model. No ConstitutionalLegitimacyTransition, no replay evidence, no constitutional guard.

The command explicitly clears suspension info (`suspendedBy` → null) — meaning it can **override active suspensions**.

**Risk Assessment:**
- **Constitutional emergency vector**: can override suspensions, bypass replay, bypass resolver
- Can be run by any admin with CLI access
- Options include `--exclude-voted` and `--enable-ip-check` — non-deterministic behavior
- H.4 finding from C.5b confirmed

**Recommendation:**
- MUST be retrofitted with ConstitutionalLegitimacyTransition before D.0
- Each approval must produce replay-certifiable evidence
- Suspension override MUST be removed (cannot unsuspend via approval)

---

### Finding T-B.2 — BulkDisapproveVoters Command (CRITICAL)

**Status:** T-B.1 companion. Same pattern: direct `can_vote=false` mutation. Can revoke sovereignty without constitutional evidence, replay capture, resolver path, or audit topology. Identical constitutional emergency vector.

**Risk Assessment:**
- Can revoke sovereignty instantly — no constitutional guard
- No evidence captured for replay
- No resolver path
- No audit of the revocation decision

**Recommendation:**
- Retirement: block via ConstitutionalTransitionGuard
- Replace with ConstitutionalLegitimacyTransition

---

### Finding T-B.3 — MarkOverdueMembers Queue Job

**Location:** `app/Jobs/MarkOverdueMembers.php`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — directly sets `fees_status=overdue` on Member model |
| **Is replay-addressable?** | NO — async mutation, no snapshot |
| **Is deterministic?** | PARTIAL — depends on scheduling timing |
| **Is ordering-sensitive?** | YES — if dispatched mid-evaluation |
| **Can async timing affect it?** | YES — async dispatch means execution timing is non-deterministic |
| **Can retries alter outcome?** | YES — retry could mean double-processing |
| **Is snapshot-bound?** | NO — directly mutates Member model |
| **Does it bypass resolver exclusivity?** | YES — affects membership eligibility outside constitutional path |

**Description:**
Job that sets `Member.fees_status = 'overdue'`. Since membership status feeds into eligibility evidence, this job can change legitimacy outcomes between evaluations. The job has `if (!app()->isProduction()) { return; }` guard — **untestable in non-production environments**.

**Risk Assessment:**
- Only runs in production — cannot be tested in CI/development
- Timing-dependent: running mid-election affects eligibility for voters whose membership evaluation hasn't been frozen yet
- Membership eligibility is used in `buildEligibilityEvidence()` (TSC-1)

**Recommendation:**
- Remove production-only guard (violation of replay determinism + testability)
- Ensure membership changes produce constitutional evidence
- Async membership mutation MUST be replay-safe

---

### Finding T-B.4 — after_commit = false (CRITICAL)

**Locations:** Various queue job dispatches

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — job may execute before transaction commits |
| **Is replay-addressable?** | NO — no snapshot exists for pre-commit state |
| **Is deterministic?** | NO — depends on transaction timing |
| **Is ordering-sensitive?** | YES — relative to transaction commit |
| **Can async timing affect it?** | YES — pre-commit dispatch = temporal race |
| **Can retries alter outcome?** | YES — retry after commit = different state |
| **Is snapshot-bound?** | NO |
| **Does it bypass resolver exclusivity?** | INDIRECT — can read uncommitted state |

**Description:**
Jobs dispatched with default `after_commit = false` may execute BEFORE the current database transaction commits. This means the job sees a different database state than the dispatching context. For jobs that affect membership, eligibility, or voting state, this creates **pre-commit sovereignty divergence**: the job operates on uncommitted (and possibly rolled back) state.

**Risk Assessment:**
- Pre-commit job execution sees stale or uncommitted data
- If transaction rolls back, job operates on phantom state
- Replay determinism violated: replay sees committed state, original run saw pre-commit state
- Affects any job dispatched during constitutional evaluation

**Recommendation:**
- ALL jobs affecting eligibility/membership MUST use `after_commit: true`
- Critical for replay determinism
- Audit ALL queue dispatch calls for `after_commit` setting

---

## T-C: Async Sovereignty Leakage

### Finding T-C.1 — 12 Orphaned Domain Events (MEDIUM)

**Events with zero registered listeners:**

| Event | File | Dispatched From |
|---|---|---|
| ElectionStateChangedEvent | `app/Events/ElectionStateChangedEvent.php` | Election lifecycle transitions |
| VotingOpened | `app/Events/VotingOpened.php` | Voting window open |
| VotingClosed | `app/Events/VotingClosed.php` | Voting window close |
| ElectionApproved | `app/Events/ElectionApproved.php` | Approval flow |
| ElectionSubmittedForApproval | `app/Events/ElectionSubmittedForApproval.php` | Submission flow |
| ElectionRejected | `app/Events/ElectionRejected.php` | Rejection flow |
| AdministrationCompleted | `app/Events/AdministrationCompleted.php` | Admin phase complete |
| NominationCompleted | `app/Events/NominationCompleted.php` | Nomination phase complete |
| ElectionCreated | `app/Events/ElectionCreated.php` | Election creation |
| ResultsPublished | `app/Events/ResultsPublished.php` | Results publication |
| BulkVotersAssignedToElection | `app/Events/BulkVotersAssignedToElection.php` | Bulk assignment |
| VoterAssignedToElection | `app/Events/VoterAssignedToElection.php` | Individual assignment |

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | NO — no listeners, no effect |
| **Is replay-addressable?** | N/A — not consumed |
| **Is deterministic?** | N/A |
| **Is ordering-sensitive?** | N/A |
| **Can async timing affect it?** | NO — zero listeners = zero effect |
| **Can retries alter outcome?** | NO |
| **Is snapshot-bound?** | NO |
| **Does it bypass resolver exclusivity?** | NO (currently dormant) — BUT if listeners added without constitutional guard = YES |

**Description:**
12 election/voter lifecycle events are dispatched but have zero registered listeners. The event system is **symbolic topology**: events exist as architectural signals but sovereignty does not flow through them. If listeners were registered without constitutional guards, they could silently reintroduce procedural sovereignty.

**Risk Assessment:**
- Currently benign (zero listeners = zero effect)
- High risk if future developers add listeners without constitutional guard
- Events lack: evidence type, constitutional hash, replay context
- Adding listeners = topology change = potential sovereignty leak

**Recommendation:**
- Document all 12 events as "symbolic topology — pending constitutional listener registration"
- Any future listener MUST: use frozen evidence, preserve replay determinism, not derive sovereignty
- Consider ConstitutionalEvent base class with replay context

---

### Finding T-C.2 — Queue Default = Sync + Production Guards (MEDIUM)

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | INDIRECT — untestable jobs may have latent sovereignty bugs |
| **Is replay-addressable?** | NO — production-only execution cannot be replayed in test |
| **Is deterministic?** | NO — production environment differs from test |
| **Is ordering-sensitive?** | YES — depends on queue worker timing in production |
| **Can async timing affect it?** | YES — production queuing = non-deterministic execution |
| **Can retries alter outcome?** | YES — depends on retry timing |
| **Is snapshot-bound?** | NO |
| **Does it bypass resolver exclusivity?** | INDIRECT — untestable = unverifiable |

**Description:**
Default queue driver is `sync` (all jobs run synchronously). Jobs like `MarkOverdueMembers`, `ExpireMemberships` have `if (!app()->isProduction()) { return; }` guards. These jobs:
1. Run synchronously in development/test (due to sync driver)
2. Immediately exit due to production guard
3. Cannot be tested for sovereignty correctness
4. Run on actual queue workers in production with different timing

The sync driver also means that **any "queued" job that affects constitutional state runs inline during the request** — no async isolation, no retry safety.

**Risk Assessment:**
- Production-only job guards are an anti-pattern for constitutional systems
- Cannot verify replay determinism for production-only code paths
- `MarkOverdueMembers` can affect eligibility (see T-B.3)
- Sync queue means "queued" is a misnomer — failures are request-scoped

**Recommendation:**
- Remove production-only guards from eligibility-affecting jobs
- Add explicit queue configuration for constitutional jobs
- Ensure all jobs affecting constitutional state are testable in CI
- Document which jobs are truly async vs sync-by-default

---

## T-D: Topology-Derived Precedence

### Finding T-D.1 — Middleware Stack Creates Implicit Sovereignty (HIGH)

**Location:** `routes/election/electionRoutes.php:502`

**Voting routes middleware stack (Steps 3-5):**
```
Position 1:  SubstituteBindings
Position 2:  voter.slug.verify
Position 3:  voter.slug.window
Position 4:  voter.slug.consistency
Position 5:  ensure.election.voter
Position 6:  voter.step.order
Position 7:  vote.eligibility          ← constitutional (ElectionLifecycle::canVote())
Position 8:  validate.voting.ip        ← SOVEREIGN (T-A.1)
Position 9:  vote.organisation
Position 10: throttle:10,1
--- then inner group ---
Position 11: voting.active             ← constitutional (ElectionLifecycle::canVote())
```

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — middleware order determines which gate executes first |
| **Is replay-addressable?** | NO — no snapshot when middleware executes |
| **Is deterministic?** | YES — fixed stack order |
| **Is ordering-sensitive?** | YES — THE finding: order = sovereignty |
| **Can async timing affect it?** | NO — synchronous stack |
| **Can retries alter outcome?** | NO — same order each request |
| **Is snapshot-bound?** | NO |
| **Does it bypass resolver exclusivity?** | YES — validate.voting.ip at position 8 is outside constitutional path |

**Description:**
The middleware stack is hardcoded with 10 layers (11 for Steps 3-5). Position matters: `vote.eligibility` (position 7) is constitutional, but `validate.voting.ip` (position 8) is sovereign procedural code. If position 7 passes but position 8 blocks, the constitutional evaluation never runs.

For Step 1 routes (code creation), the middleware stack is different (lines 485-488):
```
SubstituteBindings, voter.slug.verify, voter.slug.consistency, ensure.election.voter, vote.organisation
```
No `vote.eligibility`, no `validate.voting.ip` — different sovereignty topology for the same election.

**Voting routes position 7 vs position 11 overlap:**
Both `vote.eligibility` (position 7) and `voting.active` (position 11) delegate to `ElectionLifecycle::canVote()`. This is redundant defense-in-depth but creates ambiguity about which is authoritative.

**Risk Assessment:**
- Middleware order is **the** sovereignty topology for HTTP requests
- Constitutional middleware is sandwiched between procedural gates
- Step 1 routes have DIFFERENT sovereignty topology than Steps 3-5
- Reordering middleware = changing sovereignty (implicit governance)
- Framework topology (Laravel middleware stack) = governance topology

**Recommendation:**
- Document middleware stack as constitutionally significant
- All pre-constitutional middleware must be auditable (config-driven)
- validate.voting.ip MUST be migrated to constitutional evidence (D.0 target)
- Step 1 and Steps 3-5 should have consistent sovereignty topology

---

### Finding T-D.2 — Demo Routes Lack validate.voting.ip (LOW)

**Location:** `routes/election/electionRoutes.php:565`

**Demo slug routes middleware stack:**
```
auth:sanctum, verified, SubstituteBindings, voter.slug.verify, voter.slug.window,
voter.slug.consistency, voting.code.window, voter.step.order, vote.eligibility,
vote.organisation
```

Note: `validate.voting.ip` is ABSENT from demo routes. `voting.active` is also absent (Steps 3-5 not separated).

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | NO — demo elections are test-only |
| **Is topology consistent with real routes?** | NO — different middleware stack |
| **Constitutional risk?** | LOW — demo elections intended for testing |

**Description:**
Demo voting routes have a DIFFERENT middleware topology than real voting routes. No `validate.voting.ip`, no `voting.active`. This is intentional (demo = relaxed restrictions) but creates topology inconsistency.

**Risk Assessment:**
- Low risk because demo elections are isolated
- But topology inconsistency means constitutional testing via demo is not representative
- Could mask sovereignty issues that only manifest in real election topology

**Recommendation:**
- Document the topology difference explicitly
- Consider whether demo should match real topology for representative testing

---

## T-E: Cache-Derived Legitimacy

### Finding T-E.1 — 24-Hour Election Cache (HIGH)

**Location:** Cache pattern for `election:{eid}`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — stale `max_votes_per_ip`, `network_binding_strategy`, `voter_verification_required` |
| **Is replay-addressable?** | PARTIAL — cache is outside snapshot, but values used to build NetworkTrustEvidence |
| **Is deterministic?** | NO — cache TTL makes it time-dependent |
| **Is ordering-sensitive?** | YES — relative to cache invalidation timing |
| **Can async timing affect it?** | YES — async cache invalidation = different evaluation results |
| **Can retries alter outcome?** | YES — retry after cache expiry = different configuration |
| **Is snapshot-bound?** | NO — cache is NOT part of the constitutional snapshot |
| **Does it bypass resolver exclusivity?** | INDIRECT — stale configuration feeds into resolver inputs |

**Description:**
Election model cached for 24 hours (`election:{eid}`). This means `max_votes_per_ip`, `network_binding_strategy`, `voter_verification_required`, and other constitutional configuration values can be up to 24 hours stale when `TrustPolicyEvaluator::evaluate()` reads them via `buildNetworkEvidence()` and `buildAttestationRecord()`.

**Risk Assessment:**
- 24-hour window where election configuration is frozen regardless of actual changes
- If admin changes `max_votes_per_ip` during voting, cache returns old value for up to 24h
- Constitutional snapshot captures these stale values — snapshot is deterministically stale
- Replay reproduces stale values correctly → replay is consistent but wrong

**Recommendation:**
- Election configuration used in constitutional evaluation MUST be fresh or snapshot-frozen
- Two options: (a) reduce cache TTL to 0 for voting routes, or (b) explicitly bind configuration to snapshot
- Cache should be invalidated on election config changes

---

### Finding T-E.2 — can_vote Cache (MEDIUM)

**Location:** `user:{uid}:election:{eid}:can_vote` — 10 minute TTL

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | YES — stale `can_vote` state blocks or allows votes |
| **Is replay-addressable?** | NO — cache outside constitutional snapshot |
| **Is deterministic?** | NO — time-dependent (10 minute windows) |
| **Is ordering-sensitive?** | YES — relative to cache invalidation |
| **Can async timing affect it?** | YES — async invalidation can race with evaluation |
| **Can retries alter outcome?** | YES — retry after cache expiry = different eligibility |
| **Is snapshot-bound?** | NO |
| **Does it bypass resolver exclusivity?** | INDIRECT — cached value replaces resolver evaluation |

**Description:**
User eligibility cached for 10 minutes. If eligibility changes (e.g., `BulkDisapproveVoters` sets `can_vote=false`), the cache returns stale `true` for up to 10 minutes — allowing votes from users whose sovereignty was revoked.

Conversely, if `BulkApproveVoters` sets `can_vote=true`, the cache returns stale `false` for up to 10 minutes — blocking legitimate voters.

**Risk Assessment:**
- 10-minute divergence window where cache state ≠ actual state
- Cache can both over-allow and over-deny
- No constitutional evidence captured at cache set time
- Cache invalidation is not atomic with eligibility mutations

**Recommendation:**
- Cache MUST NOT be used for sovereign legitimacy decisions
- Evidence should be frozen at evaluation time, not cached
- If caching is needed for performance, use snapshot hash as cache key
- Invalidate cache atomically with eligibility changes

---

## T-F: Replay Sequencing Divergence

### Finding T-F.1 — TrustPolicyEvaluator Result Unused (CRITICAL)

**Location:** `app/Http/Controllers/VoteController.php:1474-1490`

| Property | Value |
|---|---|
| **Can alter legitimacy outcome?** | NO (currently) — result is discarded. COULD — if enforcement is added |
| **Is replay-addressable?** | YES — evaluation produces snapshot and evidence |
| **Is deterministic?** | YES — given same inputs, same result |
| **Is ordering-sensitive?** | PARTIAL — runs BEFORE canVote(), but result unused |
| **Can async timing affect it?** | NO — synchronous call |
| **Can retries alter outcome?** | NO — same inputs → same result |
| **Is snapshot-bound?** | YES — snapshot assembled in evaluate() |
| **Does it bypass resolver exclusivity?** | NO — but the opposite problem: resolver is bypassed BY the enforcement layer |

**Description:**
This is the single most important finding of C.5d.

```php
// Line 1474: TrustPolicyEvaluator runs — produces full constitutional evaluation
$trustEnvelope = $this->trustEvaluator->evaluate(
    election: $election,
    user: $auth_user,
    rawIp: request()->ip(),
    rawFingerprint: $request->input('device_fingerprint'),
    sessionId: $request->session()->getId(),
    registeredIpHash: $registeredIpHash,
    votesFromThisIp: $votesFromThisIp,
);

// Line 1485: Result is LOGGED ONLY — NEVER ENFORCED
\Log::channel('voting_audit')->info('Trust evaluation completed in vote submission', [
    'election_id' => $election->id,
    'user_id' => $auth_user->id,
    'trust_result' => $trustEnvelope->trusted ? 'allowed' : $trustEnvelope->reason,
    'ip' => request()->ip(),
]);

// Line 1493: Actual gate is ElectionLifecycle::canVote() — NOT trust result
$lifecycle = ElectionLifecycle::of($election);
if (!$lifecycle->canVote()) { ... }
```

The constitutional runtime produces: `VotingTrustResult`, overlay observations, `ConstitutionalTrustSnapshot`, and frozen `ParticipationEligibilityEvidence` — all fully assembled and then **discarded without enforcement**.

**The chain is:**
```text
TrustPolicyEvaluator::evaluate()   → produces trust evidence
                                 → logs it
                                 → discards it
ElectionLifecycle::canVote()       → actual sovereign gate
                                 → not derived from constitutional evidence
                                 → depends on procedural checks
```

**Risk Assessment:**
- This is the core architectural finding: **constitutional irrelevance**
- The constitutional runtime is fully functional but **non-sovereign**
- All evidence freezing, overlay observation, snapshot assembly, eligibility hashing → **observational only**
- The actual sovereign authority lives in procedural topology (controllers, middleware, commands)
- Replay correctness exists but is irrelevant because enforcement doesn't use it

**Recommendation:**
- This finding changes the architectural interpretation of everything
- Phase D mission is no longer "replace old code" — it is **converge sovereignty**
- Every enforcement path must eventually derive from constitutional evaluation
- TrustPolicyEvaluator result MUST become the sovereign gate
- This is the bridge between C.5d and C.5e (Sovereignty Convergence Audit)

---

## Senior Architecture Assessment — Sovereignty Relocation Engineering

C.5d revealed the true architectural condition: the system is in a **dual-sovereignty transitional state** where the constitutional runtime observes legitimacy but does not yet own legitimacy.

### Current Runtime Classification (S1-S8)

| Class | Meaning | Current Location |
|---|---|---|
| **S1** | Observational-only | TrustPolicyEvaluator, OverlayCoordinator, PolicySequence, Snapshots |
| **S2** | Replay-safe sovereign | *None currently* — no enforcement derives from replay |
| **S3** | Procedural sovereign | Middleware stack (position 8), Controller gates |
| **S4** | Topology-sensitive sovereign | Route middleware order, Step 1 vs Steps 3-5 topology diff |
| **S5** | Mutable-runtime sovereign | `can_vote` column reads, membership DB queries at runtime |
| **S6** | Split-sovereignty path | VoteController: TrustEval observes, canVote() enforces — different sources |
| **S7** | Non-deterministic sovereign | Cache-derived legitimacy (24h/10min TTL), after_commit=false jobs |
| **S8** | Bypass sovereign | BulkApprove/BulkDisapprove, validateVotingIpWithResponse() |

### Sovereignty Partition Map

```
                        OBSERVATIONAL                    ENFORCEMENT
                     ┌──────────────────┐           ┌──────────────────┐
                     │ TrustPolicyEval   │           │ ValidateVotingIp  │
                     │ OverlayCoordinator│           │ validateIpWithResp│
                     │ PolicySequence    │           │ CodeController    │
                     │ Snapshots         │           │ BulkApproveVoters │
                     │ Evidence Freezing │           │ BulkDisapprove    │
                     │ Replay Capture    │           │ Cache (stale)     │
                     └──────────────────┘           └──────────────────┘
                              │                              │
                              │         ┌──────────┐          │
                              └────────►│ DISCARDED│◄─────────┘
                                        └──────────┘
```

### Topology Dependency Graph

```
Request enters
    │
    ▼
SubstituteBindings ────────────── T-D (framework default)
    │
    ▼
voter.slug.verify ─────────────── T-D (position 2)
    │
    ▼
voter.slug.window ─────────────── T-D (position 3)
    │
    ▼
voter.slug.consistency ────────── T-D (position 4)
    │
    ▼
ensure.election.voter ─────────── T-D (position 5)
    │
    ▼
voter.step.order ──────────────── T-D (position 6)
    │
    ▼
vote.eligibility ──────────────── ✅ constitutional (positions 7)
    │
    ▼
validate.voting.ip ────────────── 🔴 T-A SOVEREIGN (position 8)
    │
    ▼
vote.organisation ─────────────── T-D (position 9)
    │
    ▼
throttle:10,1 ─────────────────── T-D (position 10)
    │
    ▼
voting.active ─────────────────── ✅ constitutional (position 11, inner)
    │
    ▼
Controller ────────────────────── TrustEval → LOGGED → DISCARDED (T-F.1)
    │
    ├── canVote() ─────────────── gate (not constitutionally derived)
    ├── ensureVoterMembership() ── gate (DB query)
    └── legacy checks ─────────── T-A.3
```

---

## Doctrine Impact Assessment

### Sovereignty Monotonicity Doctrine

**Status:** PRESERVED (within constitutional runtime)
**Violation risk:** T-E.1 (stale cache could violate monotonicity if cache returns different evidence than fresh evaluation)

### Constitutional Algebra Boundary

**Status:** PRESERVED (within constitutional runtime)
**Note:** Constitutional runtime never performs arithmetic on observations. Enforcement topology does not use algebraic operations either — it uses hard gates.

### Sovereign Enforcement Exclusivity Doctrine (New)

**Status:** NOT YET ESTABLISHED
**Required by:** All T-A, T-B, T-E findings
**The doctrine:** *All legitimacy enforcement must derive exclusively from replay-addressable constitutional evaluation.*

---

## Phase D Implications

### D.0 Retirement Targets (in order)

| Priority | Target | Class | Prerequisite |
|---|---|---|---|
| 1 | `validate.voting.ip` middleware feature-flag OFF | T-A.1 | Admin reconfiguration |
| 2 | `validateVotingIpWithResponse()` call sites removed | T-A.2 | Constitutional IP evidence complete |
| 3 | `BulkApproveVoters` blocked by ConstitutionalTransitionGuard | T-B.1 | Guard implemented |
| 4 | `BulkDisapproveVoters` blocked by ConstitutionalTransitionGuard | T-B.2 | Guard implemented |
| 5 | `CodeController` legacy `can_vote` replaced | T-A.3 | Slug migration complete |
| 6 | `after_commit = true` for all eligibility jobs | T-B.4 | Config change |

### D.1 Drift Telemetry Additions

| New Metric | Source | Relates To |
|---|---|---|
| TrustEval result vs canVote() divergence | VoteController | T-F.1 |
| Middleware block frequency | Each middleware | T-A.1, T-A.4 |
| Cache staleness at evaluation time | Cache hit timestamps | T-E.1, T-E.2 |
| Pre-commit job dispatch count | Queue before commit | T-B.4 |
| Legacy fallback activation rate | VoteEligibility middleware | T-A.4 |

### D.0 Rollback Triggers

| Trigger | Related Finding | Action |
|---|---|---|
| TrustEval result ≠ canVote() outcome | T-F.1 | Abort D.0, investigate divergence |
| Middleware blocks after constitutional gate passes | T-A.1 | Rollback middleware change |
| Stale cache causes denied legitimate vote | T-E.1 | Flush cache, reduce TTL |
| Bulk command mutates sovereignty | T-B.1/T-B.2 | Block command, require constitutional path |

---

## Conclusion

C.5d proves that sovereignty DOES emerge from execution topology — in two distinct forms:

1. **Pre-constitutional topology** (T-A): Middleware and helpers that block voting BEFORE constitutional evaluation runs
2. **Post-constitutional topology** (T-F.1): Constitutional evaluation result is DISCARDED, and actual enforcement comes from non-constitutional gates

The deepest finding: **the constitutional runtime is currently non-sovereign infrastructure**. It observes, freezes evidence, assembles snapshots, and records events — but its output is never used to gate voting.

This transforms the architectural mission from "replace old code" to **converge sovereignty**: all legitimacy enforcement must eventually derive from replay-addressable constitutional evaluation.

---

---

## Post-Audit Cross-Reference (C.5f → C.5i)

Cross-reference verifying whether findings from the resolver exclusivity (C.5f), scalar sovereignty (C.5g), early return (C.5h), and projection leakage (C.5i) audits reveal undocumented topology concerns.

### Methodology

Each new finding was classified against the existing 6 topology classes (T-A through T-F). Only findings that introduce a **previously undocumented topology concern** are listed below.

### Cross-Reference Findings

#### X-1: ConstitutionalLegitimacyDecision Missing (from C.5f F-4)

**Source:** C.5f — Resolver Exclusivity Audit, finding F-4
**Existing C.5d coverage:** Partial — T-F.1 documents that TrustPolicyEvaluator result is unused

**Gap:** The C.5d report identifies that TrustPolicyEvaluator output is discarded (T-F.1), but does not document that the **designated resolver exclusivity class does not exist**. The F4 fitness function (`SovereigntyConvergenceFitnessFunction.php`) references `ConstitutionalLegitimacyDecision` as the exclusive resolver authority, but this class has never been implemented.

**Topology implication:** This is not a runtime topology concern (the class doesn't exist, so it can't affect request flow). However, it creates an **architectural topology gap**: there is no single class that can be pointed to as "the sovereign resolver." This means:
- The resolver exclusivity invariant cannot be formally verified
- Any code path could theoretically become the sovereign without a designated authority class to check against
- F4 tests pass because they check nothing OUTSIDE the resolver, but cannot verify the resolver IS the exclusive authority

**Update to C.5d:** Add `ConstitutionalLegitimacyDecision missing` as a **structural topology finding** — not a runtime concern, but an architectural prerequisite for topology verification.

**Classification:** Structural (not runtime topology)

---

#### X-2: VoteController LegitimacyOutcome Derivation (from C.5f F-3)

**Source:** C.5f — Resolver Exclusivity Audit, finding F-3
**Location:** `app/Http/Controllers/VoteController.php:1552`
**Existing C.5d coverage:** T-F.1 partially covers this (result unused)

**Gap:** The C.5d report documents that TrustPolicyEvaluator result is logged and discarded. But at line 1552, the controller **creates its own LegitimacyOutcome** from the trust envelope:

```php
$constitutionalOutcome = LegitimacyOutcome::fromTrustState(
    $trustEnvelope->result->evaluationState
);
```

This is topology-adjacent: the controller derives constitutional interpretation directly rather than receiving it from a resolver. While the F4 fitness function approves this as a telemetry-only exception, the **derivation topology** is inverted — constitutional meaning flows from controller code, not from resolver infrastructure.

**Topology implication:** The controller sits above the constitutional runtime in the derivation chain. If `LegitimacyOutcome::fromTrustState()` were ever used for enforcement (not just telemetry), this would be a resolver exclusivity violation at the controller topology level. Currently mitigated by telemetry-only usage.

**Update to C.5d:** Add as a **T-F subclass** — the derivation path trusts the resolver output but re-interprets it in the controller layer.

**Classification:** LOW (telemetry-only exception, F4-approved)

---

#### X-3: confidence_score UI Gating (from C.5i P-2)

**Source:** C.5i — Projection Sovereignty Leakage Audit, finding P-2
**Files:** `resources/js/Pages/Dashboard/Welcome.vue:358`, `resources/js/composables/useDashboard.js:63-79`
**Existing C.5d coverage:** None — C.5d scope was backend topology only

**Gap:** The C.5d report exclusively covers backend topology (middleware, controllers, services, commands, cache). The projection layer (frontend) was out of scope. P-2 reveals that a backend-derived `confidence_score` drives UI visibility tiers in the Vue frontend:

| Score Range | Tier | UI effects |
|-------------|------|------------|
| >= 80 | "expert" | Advanced features shown |
| >= 60 | "intermediate" | Mid-level features |
| >= 40 | "beginner" | Basic features |
| < 40 | "new" | Onboarding content |

**Topology implication:** This creates a **cross-layer topology chain**: backend → UserStateBuilder → `confidence_score` → frontend visibility gates. While not enforcement topology (no voting is blocked), it is capability gating driven by a procedural score — a form of topology-derived authority in the projection layer.

**Should this be added to C.5d?** Borderline. C.5d's topology classes (T-A through T-F) are focused on **sovereignty enforcement topology** — paths that can allow/deny voting. The confidence_score gates UI capabilities, not voting access. This is a **projection topology** concern, not an enforcement topology concern.

**Recommendation:** Do not add to C.5d. Document in C.5i as-is. The projection layer has its own topology concerns that should be addressed during D.0.3 (projection cleanup), not mixed into the enforcement topology report.

**Classification:** OUT OF SCOPE for C.5d (projection topology, not enforcement topology)

---

### Cross-Reference Summary

| Source | Finding | C.5d Relevance | Action |
|--------|---------|----------------|--------|
| C.5f F-4 | ConstitutionalLegitimacyDecision missing | Structural topology — architectural prerequisite | **Add to C.5d** as structural finding |
| C.5f F-3 | Controller LegitimacyOutcome derivation | Derivative of T-F.1 (result unused) | **Note** as T-F subclass, LOW |
| C.5f F-1/F-2 | ValidateVotingIp/VotingSecurityService | Already documented (T-A.1, T-A.2, T-D.1) | No change |
| C.5g S1-S5 | Scalar naming in telemetry/observation | Not topology concerns | No change |
| C.5h E1-E2 | SnapshotAssembler break patterns | Projection-layer metadata selection | No change |
| C.5i P-1/P-3/P-4 | Trust score, "Critical" labels, alerts | Projection-layer vocabulary | No change |
| C.5i P-2 | confidence_score UI gating | Projection topology, not enforcement | **Out of scope** for C.5d |

### Conclusion

**One new structural topology finding discovered:** ConstitutionalLegitimacyDecision is missing (X-1). This is not a runtime topology concern (the class doesn't execute in any request path) but is an architectural prerequisite for resolver exclusivity verification. It must be implemented before D.0.3a (constitutional primary enforcement mode).

All other C.5f/C.5g/C.5h/C.5i findings either:
- Are already documented in the existing C.5d report
- Are projection-layer concerns that don't affect enforcement topology
- Are scalar naming residue in observation/telemetry code

**No new runtime topology concerns discovered. The existing C.5d topology map remains complete.**

---

## Next: C.5e — Sovereignty Convergence Audit

Required before D.0 planning:

| Question | Status |
|---|---|
| Which paths still enforce legitimacy? | Identified (T-A.1, T-A.2, T-A.3, T-A.4) |
| Which paths only observe? | Identified (TrustPolicyEvaluator, PolicySequence) |
| Which paths mutate authority? | Identified (T-B.1, T-B.2, T-B.3, T-B.4) |
| Which paths bypass replay? | All T-A, T-B, T-E |
| Which paths bypass resolver? | All T-A, T-B |
| Which paths run before snapshot freezing? | T-A.1, T-A.2, T-A.4 |
| Which paths can diverge under async timing? | T-B.3, T-B.4, T-C.2, T-E.1, T-E.2 |
| Does TrustEval result gate voting? | **NO** — T-F.1 (single most important finding) |
| Is canVote() constitutionally derived? | **NO** — procedural checks, not frozen evidence |
| Is sovereignty partitioned? | **YES** — observational vs enforcement |
