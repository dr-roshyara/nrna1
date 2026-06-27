# C.5e — Sovereignty Source Graphs

**Date:** 2026-05-28
**Phase:** C.5e.1 — Sovereignty Source Graphs
**Status:** IN PROGRESS

---

## SG-1: Vote Allowed/Denied (Real Election — Steps 3/5)

### Complete Sovereignty Path

```text
HTTP Request
│
├─ Middleware Stack (10 layers, positions 1-10)
│  1. SubstituteBindings           [T-D] topology-framework
│  2. voter.slug.verify            [T-D] topology-ordered
│  3. voter.slug.window            [T-D] topology-ordered
│  4. voter.slug.consistency       [T-D] topology-ordered
│  5. ensure.election.voter        [T-D] topology-ordered
│  6. voter.step.order             [T-D] topology-ordered
│  7. vote.eligibility             [S1] constitutional delegation (ElectionLifecycle)
│  8. validate.voting.ip           [S8] BYPASS — blocks pre-snapshot
│  9. vote.organisation            [T-D] topology-ordered
│ 10. throttle:10,1                [T-D] framework-default
│
├─ Inner Middleware (Step 3-5 only)
│ 11. voting.active                [S1] constitutional delegation (ElectionLifecycle)
│
├─ VoteController::create() (Step 3) / store() (Step 5)
│
│  STEP 3: create()
│  ├── ensureVoterMembership(election, user)         [S5] mutable-runtime — cache/DB query
│  ├── Code query (election-scoped)                  [S5] mutable-runtime — DB query
│  ├── has_voted check                               [S5] mutable-runtime — column read
│  ├── vote_submitted flag set                       mutable write
│  ├── agreement check (has_agreed_to_vote)          [S5] mutable-runtime — column read
│  ├── validateVotingIpWithResponse()                [S8] BYPASS — renders VoteDenied page
│  │   → reads $auth_user->voting_ip cleartext
│  │   → compares to request()->ip()
│  │   → on mismatch: Inertia::render('Vote/VoteDenied')
│  │   → TERMINATES before TrustPolicyEvaluator
│  ├── (real path) Post::with('candidacies') query    [S5] mutable-runtime — DB query
│  ├── (demo path) DemoCandidacy query                 [S5] mutable-runtime — DB query
│  └── return Inertia::render('Vote/Create')           NO CONSTITUTIONAL EVALUATION
│
│  STEP 5: store()
│  ├── registeredIpHash = hash($auth_user->voting_ip)  IP capture (cleartext read)
│  ├── votesFromThisIp = Code::count() query           [S5] mutable-runtime — DB query
│  ├── ★ TrustPolicyEvaluator::evaluate()              [S1] OBSERVATIONAL ONLY
│  │   ├── hashIp(rawIp)
│  │   ├── hashFingerprint(rawFingerprint)
│  │   ├── buildNetworkEvidence()       ← $election->max_votes_per_ip (24h cache stale?)
│  │   ├── buildDeviceContext()
│  │   ├── buildAttestationRecord()
│  │   ├── buildSessionContinuity()
│  │   ├── OverlayCoordinator::aggregate()
│  │   ├── PolicySequence::evaluate()
│  │   ├── buildEligibilityEvidence()   ← ElectionMembership query (frozen here)
│  │   ├── TrustSnapshotAssembler::assemble()
│  │   ├── SecurityEventRecorder::record()
│  │   └── return TrustEvaluationEnvelope
│  │
│  ├── ★ RESULT: LOGGED → DISCARDED                    [S6] SPLIT SOVEREIGNTY
│  │   \Log::channel('voting_audit')->info(...)
│  │   # trustEnvelope is NEVER checked for enforcement
│  │
│  ├── ElectionLifecycle::canVote()                     [S6] actual sovereign gate
│  │   → reads $this->snapshot->canVote
│  │   → NOT derived from TrustEval result
│  │   → if false: DB::rollBack(), return error
│  │
│  ├── ensureVoterMembership(election, user, fresh)     [S5] mutable-runtime — fresh DB query
│  │   → ElectionMembership::where('status','active')->exists()
│  │   → if false: DB::rollBack(), return redirect
│  │
│  ├── DB transaction lock (Cache::lock, 10s)
│  ├── (real) Code query / (demo) DemoCode query
│  ├── has_voted re-check                              [S5] mutable-runtime
│  ├── legacy check_ip_address()                        [S8] BYPASS — raw DB query, all elections
│  ├── Legacy vote_pre_check()                          [S3] procedural sovereign
│  ├── vote data persistence
│  ├── markUserAsVoted()                                [S5] mutable write
│  └── return redirect
│
└── Response
```

### Sovereignty Graph — Canonical Form

| Property | Value |
|---|---|
| **Action** | Vote Allowed/Denied (real election) |
| **Entry point** | Route `slug.vote.create` / `slug.vote.submit` |
| **Middleware sovereigns** | validate.voting.ip (position 8) — blocks pre-evaluation |
| **Constitutional evaluation** | TrustPolicyEvaluator::evaluate() — runs at Step 5 |
| **Enforcement sovereign** | canVote() + ensureVoterMembership() + legacy checks |
| **TrustEval result used?** | **NO** — logged only, never enforced |
| **Primary sovereign gate** | ElectionLifecycle::canVote() — reads snapshot, not TrustEval |
| **Secondary gates** | ensureVoterMembership (DB), validateVotingIpWithResponse (helper), Code::has_voted (column), vote_pre_check (legacy) |
| **Sovereignty class** | **S6** — split sovereignty between constitutional observation and procedural enforcement |
| **Replay-addressable?** | PARTIAL — snapshot exists but enforcement doesn't use it |
| **Topology sensitive?** | YES — 11 middleware positions, helper call sites, controller ordering |
| **Temporal sensitive?** | YES — cache staleness, after_commit=false, async membership mutation |
| **Snapshot boundary** | TrustPolicyEvaluator::evaluate() line 1474 — feeds into snapshot, not enforcement |
| **Number of sovereignty points** | 7+ distinct enforcement points, 1 observational point |
| **Divergence potential** | canVote() could return false while TrustEval says allowed, or vice versa |

### Authority Sources (ranked by sovereignty)

| Rank | Source | Type | Location | Lines |
|---|---|---|---|---|
| 1 | `canVote()` | ElectionLifecycle::canVote() | VoteController::store() | 1493-1505 |
| 2 | `validateVotingIpWithResponse()` | Global helper (cleartext IP) | VoteController::create() | 302-305 |
| 3 | `ensureVoterMembership()` | DB query (active status) | VoteController::create/store | 523, 1508 |
| 4 | `Code::has_voted` | DB column read | VoteController::create/store | 573 |
| 5 | `vote_pre_check()` | Legacy validation | VoteController::create | 593 |
| 6 | `check_ip_address()` | Raw DB query | VoteController::store (legacy) | 2966 |
| 7 | `TrustPolicyEvaluator::evaluate()` | **Observational only** | VoteController::store | 1474-1490 |

### Sovereign Convergence Path

```text
Current:
  TrustEval → LOG → DISCARD
  canVote() → ENFORCE (independent source)

Target:
  ConstitutionalEvidenceSnapshot → ConstitutionalLegitimacyDecision → ENFORCE

Divergence risk:
  TrustEval says ALLOWED, canVote() says DENIED → voter incorrectly blocked
  TrustEval says DENIED, canVote() says ALLOWED → sovereignty leak (unlikely but possible)
```

---

## SG-2: Vote Allowed/Denied (Demo Election)

### Sovereignty Path

```text
HTTP Request
│
├─ Middleware Stack (demo slug routes, line 565)
│  auth:sanctum, verified, SubstituteBindings, voter.slug.verify,
│  voter.slug.window, voter.slug.consistency, voting.code.window,
│  voter.step.order, vote.eligibility, vote.organisation
│  ★ NOTE: No validate.voting.ip, no voting.active
│
├─ DemoVoteController::create() (Step 3)
│  ├── (NO membership check — demo bypasses)
│  ├── User authentication check
│  ├── Code query (DemoCode, user_id + election_id)
│  ├── validateVotingIpWithResponse() call site          [S8] BYPASS
│  ├── DemoElection data query (candidates + posts)
│  └── return Inertia::render('Vote/Create)
│
├─ DemoVoteController::first_submission() (Step 4)
│  ├── Code query
│  ├── has_voted check
│  ├── check_ip_address() raw query                       [S8] BYPASS — uses demo_codes table
│  ├── vote data validation
│  ├── session store
│  └── return redirect to verify
│
└─ DemoVoteController::store() (Step 6)
   ├── ★ TrustPolicyEvaluator does NOT run in demo path
   ├── Code query + has_voted check
   ├── vote persistence (DemoVote table)
   ├── markUserAsVoted()
   └── return thank-you page
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S3/S8** — entirely procedural, no constitutional evaluation |
| **TrustPolicyEvaluator runs?** | **NO** — demo path does not invoke it |
| **Primary sovereign** | Controller procedural logic |
| **Replay-addressable?** | **NO** — no snapshot, no evidence capture |
| **Topology consistent with real?** | **NO** — different middleware stack, different controller |

---

## SG-3: Code Creation Allowed/Denied

**Location:** CodeController::create() + store()

### Sovereignty Path

```text
CodeController::create() (Step 1)
├── Middleware: SubstituteBindings, voter.slug.verify, voter.slug.consistency,
│              ensure.election.voter, vote.organisation
│   ★ NOTE: No vote.eligibility, no validate.voting.ip
├── $user->can_vote check (legacy column)                [S5] mutable-runtime
│   return $user && $user->can_vote === true;
├── session election context
└── return Inertia::render('Code/Create')

CodeController::store()
├── has_voted check                                       [S5] column read
├── code generation + persistence
├── (real) $code->save()
└── return redirect to agreement
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S5** — legacy column read (`can_vote`) |
| **Constitutional path** | NOT USED — should delegate to ElectionLifecycle::canVote() |
| **Topology note** | Step 1 middleware stack is DIFFERENT from Steps 3-5 |
| **Replay-addressable?** | NO — no evidence capture |

---

## SG-4: Voter Approved (BulkApproveVoters Command)

**Location:** `app/Console/Commands/BulkApproveVoters.php`

### Sovereignty Graph

```text
php artisan voters:can-vote
│
├── Query: User::where('is_voter', true)->where('can_vote', false)
├── [--exclude-voted] filter: ->where('has_voted', false)
├── Confirmation prompt (unless --force)
├── foreach voter:
│   ├── can_vote = true                                    MUTATION
│   ├── approvedBy = admin name                            MUTATION
│   ├── suspendedBy = null                                 CLEARS SUSPENSION
│   ├── suspended_at = null                                CLEARS SUSPENSION
│   ├── [--enable-ip-check] voting_ip = user_ip             MUTATION
│   └── $voter->update($updateData)                        WRITE
│
│   ★ NO constitutional evidence
│   ★ NO replay capture
│   ★ NO PolicySequence evaluation
│   ★ NO audit of approval decision
│   ★ CAN OVERRIDE SUSPENSIONS
│
└── Report: X approved, Y failed
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S8** — BYPASS sovereign |
| **Can override suspension?** | YES — explicitly sets suspendedBy=null |
| **Is replay-addressable?** | NO |
| **Constitutional guard?** | NONE |
| **Evidence captured?** | NONE |

---

## SG-5: Voter Disapproved (BulkDisapproveVoters Command)

Symmetric to SG-4. Direct `can_vote=false` mutation. Same constitutional bypass analysis.

---

## SG-6: Membership Status Change

**Locations:** `MarkOverdueMembers` job, `ExpireMemberships` job, `ProcessMemberImportJob`

### Sovereignty Graph

```text
Scheduled Job / Queue Dispatch
│
├── MarkOverdueMembers:
│   if (!app()->isProduction()) { return; }               UNTESTABLE
│   Member::where('fees_status', 'paid')
│       ->where('fees_due_at', '<', now())
│       ->update(['fees_status' => 'overdue'])             MUTATION
│
├── ExpireMemberships:
│   if (!app()->isProduction()) { return; }               UNTESTABLE
│   ElectionMembership::where('expires_at', '<', now())
│       ->update(['status' => 'expired'])                  MUTATION
│
├── ProcessMemberImportJob:
│   $tries = 1                                             SILENT FAILURE
│   Bulk voter creation + assignment                       MUTATION
│
│   ★ ALL affect eligibility without constitutional evidence
│   ★ ALL run async — timing-dependent
│   ★ production-only guards = untestable
│
└── Effect on eligibility:
    fees_status=overdue → membership may be suspended
    status=expired → membership invalid
    → next TrustEval evaluation sees different state
    → BUT: enforcement was already decided by async timing
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S5** (mutable-runtime) + **S7** (non-deterministic) |
| **Async timing affects enforcement?** | YES — job runs at scheduler time, not evaluation time |
| **Replay-addressable?** | NO — mutation is outside snapshot boundary |
| **Testable in CI?** | NO — production-only guards |

---

## SG-7: Election State Transition

**Locations:** Election lifecycle state machine transitions

### Sovereignty Graph

```text
Election State Change (e.g., Draft → ReadyForVoting → VotingActive → ...)
│
├── ElectionStateChangedEvent dispatched                      🟡 orphaned
├── VotingOpened event dispatched                             🟡 orphaned
├── VotingClosed event dispatched                             🟡 orphaned
│
├── ElectionLifecycle snapshot reflects new state             ✅ constitutional
│   → canVote() returns updated value
│
├── BUT: no event consumers register the transition           🔴
│   → no constitutional guard on state change
│   → no evidence of transition in snapshot
│   → no audit projection
│
└── Effect on sovereignty:
    State change affects canVote() which IS the sovereign gate
    BUT: state change itself has no constitutional guard beyond the state machine
    → ActivateElectionCommand explicitly bypasses ConstitutionalTransitionGuard
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S6** — split: state is constitutional, transitions are not |
| **Events consumed?** | **NO** — all orphaned |
| **Transition guard active?** | **NO** — explicitly bypassed by ActivateElectionCommand |
| **Replay-addressable?** | PARTIAL — state is in snapshot, transition evidence is not |

---

## SG-8: IP Address Enforcement

### Sovereignty Graph

```text
Three independent enforcement mechanisms:

1. ValidateVotingIp middleware (HTTP middleware, position 8)
   → reads $user->voting_ip cleartext
   → compares to request()->ip()
   → on mismatch: back()->withErrors() → redirect
   → config: voting_security.control_ip_address (feature flag)
   → config: voting_security.ip_mismatch_action (block|warn)
   ★ EXECUTES BEFORE TrustPolicyEvaluator
   ★ CLEARTEXT IP comparison (privacy concern)

2. validateVotingIpWithResponse() helper (called at 6 controller sites)
   → reads $auth_user->voting_ip cleartext
   → compares to request()->ip()
   → on mismatch: Inertia::render('Vote/VoteDenied') → full denial page
   → exposes registered_ip and current_ip in denial response (privacy concern)
   ★ 6 CALL SITES, EACH IS INDEPENDENT SOVEREIGNTY
   ★ BYPASSES constitutional layer entirely

3. NetworkEvidence in TrustPolicyEvaluator (constitutional)
   → uses HASHED ip (privacy-preserving)
   → compares registeredIpHash vs currentIpHash
   → result is recorded but NEVER ENFORCED
   ★ Observational only
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S8** (bypass — helpers) + **S1** (observational — constitutional) |
| **Bypasses constitutional?** | YES — middleware and helper both block pre-evaluation |
| **Privacy concern?** | YES — cleartext IP exposure in denial responses |
| **Convergence target** | Remove enforcement 1+2, enforce constitutional NetworkEvidence |

---

## SG-9: Eligibility Cache Hit/Miss

### Sovereignty Graph

```text
Cache key: user:{uid}:election:{eid}:can_vote
TTL: 10 minutes

Cache SET
│
├── Set on: eligibility check
├── Value: true/false (derived from runtime query)
├── ★ NO snapshot hash in cache key
├── ★ NO constitutional evidence at cache time
└── NO invalidation on eligibility change

Cache GET
│
├── Used by: ensureVoterMembership() when $useCache=true
├── Hits: returns stale value (up to 10 min old)
├── Misses: fresh DB query
├── ★ Stale true: allows voter whose eligibility was revoked
├── ★ Stale false: blocks voter whose eligibility was granted
└── ★ NO divergence detection (no snapshot to compare against)
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S7** — non-deterministic sovereign (time-dependent) |
| **Can diverge from truth?** | YES — 10-minute window |
| **Replay-addressable?** | NO — cache is outside constitutional snapshot |
| **Evidence captured?** | NO |
| **Convergence target** | Remove sovereign cache — use snapshot-frozen evidence |

---

## SG-10: Slug Creation Allowed/Denied

**Location:** VoterSlugService::getOrCreateSlug()

### Sovereignty Path

```text
VoterSlugService::getOrCreateSlug(user, election, forceNew)
│
├── (forceNew) hard delete all existing slugs for user+election
├── check existing active slugs
├── create new VoterSlug record
│
├── Middleware context:
│   → election state checked by EnsureElectionState middleware
│   → voter membership checked by EnsureElectionVoter middleware
│   → NO TrustPolicyEvaluator call
│   → NO constitutional evidence capture
│
└── Slug is the prerequisite for all voting steps
    → its creation determines whether voter can reach constitutional evaluation
```

| Property | Value |
|---|---|
| **Sovereignty class** | **S3** — procedural (middleware-gated) |
| **TrustEval runs?** | NO — slug creation is pre-constitutional |
| **Replay-addressable?** | NO |
| **Topology sensitive?** | YES — depends on middleware order for the route |

---

## Cross-Graph Analysis

### Sovereignty Point Count

| Sovereignty Class | Count | Locations |
|---|---|---|
| **S1** Observational-only | 1 | TrustPolicyEvaluator::evaluate() |
| **S2** Replay-safe sovereign | 0 | *None yet* |
| **S3** Procedural sovereign | 3 | Middleware positions, vote_pre_check, slug creation |
| **S4** Topology-sensitive | 5 | Middleware ordering, route grouping |
| **S5** Mutable-runtime sovereign | 6 | can_vote column, membership DB, Code::has_voted, agreement, async jobs |
| **S6** Split sovereignty | 2 | canVote() vs TrustEval, election state vs transitions |
| **S7** Non-deterministic sovereign | 2 | Cache (10min), async membership mutation |
| **S8** Bypass sovereign | 4 | validateVotingIpWithResponse (6 sites), BulkApprove, BulkDisapprove, check_ip_address |

### All Sovereignty Points (Ranked by Risk)

| Rank | Sovereignty Point | Class | Risk | Graph Source |
|---|---|---|---|---|
| 1 | BulkApproveVoters direct mutation | S8 | CRITICAL | SG-4 |
| 2 | BulkDisapproveVoters direct mutation | S8 | CRITICAL | SG-5 |
| 3 | TrustEval result discarded | S6 | CRITICAL | SG-1 |
| 4 | validateVotingIpWithResponse (6 sites) | S8 | CRITICAL | SG-8 |
| 5 | ValidateVotingIp middleware position 8 | S8 | HIGH | SG-8 |
| 6 | canVote() not constitutionally derived | S6 | HIGH | SG-1 |
| 7 | Cache-derived eligibility (10min stale) | S7 | HIGH | SG-9 |
| 8 | after_commit=false pre-commit divergence | S7 | HIGH | SG-6 |
| 9 | CodeController legacy can_vote column | S5 | HIGH | SG-3 |
| 10 | Membership async mutation (MarkOverdueMembers) | S5/S7 | MEDIUM | SG-6 |
| 11 | Election state transition bypasses guard | S6 | MEDIUM | SG-7 |
| 12 | 12 orphaned events (symbolic topology) | S1 | MEDIUM | SG-7 |
| 13 | Legacy run-time membership DB query | S5 | MEDIUM | SG-1 |
| 14 | vote_pre_check legacy procedural gate | S3 | LOW | SG-1 |
| 15 | Middleware stack position differences | S4 | LOW | SG-1/2 |
