# C.5c — Replay Stability Audit Report

**Date:** 2026-05-28  
**Phase:** M.1 Phase C.5 Constitutional Stabilization Audit — Subphase C.5c  
**Baseline:** C.5b completed (6 hidden sovereignty paths documented and sequenced)  
**Scope:** Temporal topology drift, replay determinism, serialization safety, cache governance

---

## Executive Summary

Phase C.5c audits whether constitutional legitimacy **remains identical** when replayed across:
- Different processes
- Different nodes
- Different timezones
- Different serialization engines
- Different deployment cycles
- With caches disabled/enabled
- With queued jobs executed/deferred
- With event order permuted
- With restart boundaries crossed

**Critical Finding:** Current procedural sovereignty paths (H.1-H.6) are inherently **unreplayable** due to mutable evidence sources. But **constitutional path is deterministically replayable** IF evidence is frozen at evaluation time.

**Constitutional Assessment:**
- **Frozen evidence path:** ✅ Replay-safe
- **Mutable source paths:** ❌ Replay-drift vectors
- **Cache dependency paths:** ❌ Nondeterministic
- **Temporal ordering paths:** ❌ Topology-dependent outcomes
- **Async job paths:** ⚠️ Temporal contamination risk

**Phase D.0 Impact:** Procedural authority retirement REQUIRED before replay certification. Cannot verify determinism if topology-dependent authority still executes.

---

## Replay Authority Source Boundary

**Definition:** Data that is ALLOWED to influence replay legitimacy outcome.

### Permitted Replay Authority Sources

| Source | Type | Frozen? | Deterministic? | Risk |
|--------|------|---------|----------------|------|
| `ConstitutionalEvidenceSnapshot` | Evidence | ✅ YES | ✅ YES | SAFE |
| `PolicySequence` logic | Resolver | ✅ YES | ✅ YES | SAFE |
| Evidence canonicalization rules | Normalization | ✅ YES | ✅ YES | SAFE |
| Versioned schema hash | Versioning | ✅ YES | ✅ YES | SAFE |
| Replay certification timestamp | Audit | ✅ YES | ✅ YES | SAFE |

### Forbidden Replay Authority Sources (Nondeterministic Contamination)

| Source | Type | Frozen? | Deterministic? | Risk | Found In |
|--------|------|---------|----------------|------|----------|
| `request()->ip()` | Temporal | ❌ NO | ❌ NO | CRITICAL | H.1, H.2, H.4, H.6 |
| `User.voting_ip` | Mutable DB | ❌ NO | ❌ NO | CRITICAL | H.1, H.2, H.4 |
| `now()` / `time()` | Temporal | ❌ NO | ❌ NO | HIGH | Expiration logic |
| Cache hit/miss state | State-dependent | ❌ NO | ❌ NO | CRITICAL | Cache layer |
| Queue execution order | Topology | ❌ NO | ❌ NO | HIGH | Job execution |
| Event listener order | Topology | ❌ NO | ❌ NO | HIGH | Eloquent events |
| Global vote counts | Mutable aggregate | ❌ NO | ❌ NO | CRITICAL | H.3 |
| Command execution timestamp | Temporal | ❌ NO | ❌ NO | HIGH | Console commands |

---

## Temporal Topology Drift — Critical Findings

### Finding R.1: BulkApproveVoters Command (Legitimacy Without Constitutional Path)

**File:** `app/Console/Commands/BulkApproveVoters.php`  
**Lines:** 47-57  
**Constitutional Violation:** CRITICAL

```php
// Line 55: Sets can_vote=true directly on User model
$votersToApprove = $query->where('can_vote', false)->get();
// (later) Sets can_vote = true for all users
```

### The Temporal Drift Problem

**Timeline Scenario:**

```
T1 (Approval Command Runs):
  BulkApproveVoters sets User.can_vote = true
  User legitimacy NOW: can_vote=true
  But evidence snapshot frozen at T0 (before approval)

T2 (Voting Attempt):
  Constitutional evaluation uses frozen snapshot from T0
  Snapshot: can_vote status = false (at T0)
  Runtime check: User.can_vote = true (at T2)
  
Result: DIVERGENCE
  - Frozen evidence says INELIGIBLE
  - Runtime user state says ELIGIBLE
  - Which legitimacy is correct? UNDEFINED
```

### Replay Instability

**Cross-evaluation divergence:**
```
Evaluation 1 (before BulkApproveVoters):
  User.can_vote = false
  Frozen snapshot: can_vote = false
  → Constitutional evaluation: INELIGIBLE
  Result: Access blocked ✓

Evaluation 2 (after BulkApproveVoters, same evidence snapshot):
  User.can_vote = true (in database NOW)
  Frozen snapshot: still can_vote = false (at capture time)
  → Constitutional evaluation: INELIGIBLE (uses snapshot)
  Result: Access blocked ✓
  
Evaluation 3 (re-evaluate with current user state):
  User.can_vote = true
  Fresh snapshot: can_vote = true
  → Constitutional evaluation: ELIGIBLE
  Result: Access ALLOWED ✗
  
Verdict: Same voter, same intent, DIFFERENT outcomes
         based on replay timing and which evidence source was used.
```

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | D (Actual Runtime Regression) + **Temporal Drift Vector** |
| **Criticality** | CRITICAL |
| **Replay Risk** | **EXISTENTIAL** (breaks deterministic legitimacy) |
| **Constitutional Impact** | Legitimacy validity is time-dependent |
| **Frozen Evidence Compatibility** | BREAKS (approval happens outside snapshot) |
| **Cross-Process Equivalence** | FAILS (process 1 sees approved, process 2 sees un-approved) |
| **Found By** | C.5c temporal topology audit |

### Root Cause Analysis

**Why this is temporal topology drift:**
1. Legitimacy depends on WHEN approval command ran
2. Evidence snapshot frozen at T0
3. Approval happens at T1 > T0
4. Subsequent evaluations see different user state
5. This is **temporal ordering sovereignty**: when authorities run determines legitimacy

---

### Finding R.2: InvalidateMembershipDashboardCache Listener (Cache-Derived Authority)

**File:** `app/Listeners/InvalidateMembershipDashboardCache.php`  
**Constitutional Violation:** CRITICAL

```php
// Lines 18: Cache forget logic
foreach (['owner', 'admin', 'commission', 'member'] as $role) {
    Cache::forget("membership_dashboard_{$organisationId}_{$role}");
}
```

### The Cache Doctrine Violation

**Constitutional Principle (Forbidden):**
> Cached state must NEVER become sovereign authority.

**Current Implementation:**
- Listener observes event
- Forgets cache keys
- Next request hits fresh data
- But legitimacy could depend on cache state

### Cache Drift Scenario

```
Request A (Time T1):
  Cache miss on membership_dashboard_org1_member
  → Queries database
  → Builds membership list
  → Returns ELIGIBLE members
  → Legitimacy: ELIGIBLE ✓

Event fires (user is removed from org)
  InvalidateMembershipDashboardCache listener runs
  Cache::forget("membership_dashboard_org1_member")

Request B (Time T2, same user):
  Cache miss (was forgotten)
  → Queries database
  → User no longer in membership table
  → Returns INELIGIBLE
  → Legitimacy: INELIGIBLE ✗

Result: SAME USER, DIFFERENT LEGITIMACY based on cache state
```

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | D (Cache-Derived Authority Contamination) |
| **Criticality** | CRITICAL |
| **Replay Risk** | **EXISTENTIAL** (cache state not replayed) |
| **Constitutional Impact** | Legitimacy validity depends on cache warm/cold state |
| **Cross-Process Equivalence** | FAILS (different processes have different cache states) |
| **Deterministic Replay** | IMPOSSIBLE (cache state not serialized in snapshot) |
| **Found By** | C.5c cache governance audit |

---

### Finding R.3: Temporal Ordering Sovereignty in Queue Jobs

**File:** `app/Jobs/` directory  
**Risk:** Queue execution order affects legitimacy outcomes

### The Job Ordering Problem

```
Scenario A (Job Order: ApproveUser → EvaluateLegitimacy):
  1. ApproveUser job runs
     → Sets User.can_vote = true
  2. EvaluateLegitimacy job runs
     → Reads User.can_vote
     → Result: ELIGIBLE ✓

Scenario B (Same jobs, reversed order: EvaluateLegitimacy → ApproveUser):
  1. EvaluateLegitimacy job runs
     → Reads User.can_vote = false
     → Result: INELIGIBLE ✗
  2. ApproveUser job runs
     → Sets User.can_vote = true (too late)

Result: Same user, same jobs, DIFFERENT LEGITIMACY based on execution order
```

**Critical Issue:** If legitimacy is derived in a queued job, queue execution order becomes implicit governance topology.

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | E (Topology-Dependent Legitimacy) |
| **Criticality** | CRITICAL |
| **Replay Risk** | HIGH (queue order not deterministic across restarts) |
| **Constitutional Impact** | Legitimacy validity depends on job queue order |
| **Nondeterministic Contamination** | Queue topology |
| **Found By** | C.5c temporal topology audit |

---

### Finding R.4: Event Listener Ordering Contamination

**File:** `app/Listeners/` directory  
**Constitutional Violation:** Implicit topology-based sovereignty

### The Event Ordering Problem

```
Event: UserApprovedForVoting fires

Listener A (Listener Order = 1):
  if (user.approved) {
    cache.forget("ineligible_users")
  }

Listener B (Listener Order = 2):
  if (user.approved) {
    event.log("User approved")
  }

If Listener A caches authority:
  Legitimacy derived from cache state
  But listeners run in CONFIG order (not deterministic across deployments)
  
Result: LISTENER ORDER → TOPOLOGY SOVEREIGNTY
```

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | E (Event Topology-Dependent Authority) |
| **Criticality** | HIGH |
| **Replay Risk** | MEDIUM-HIGH (event order can vary) |
| **Constitutional Impact** | Authority can depend on listener registration order |
| **Nondeterministic Contamination** | Event listener queue |
| **Found By** | C.5c event governance audit |

---

### Finding R.5: Serialization Schema Divergence (Replay Corruption)

**Issue:** If evidence snapshot serialization format changes, replay becomes impossible.

### Serialization Drift Scenario

```
Original Snapshot (v1):
  ConstitutionalEvidenceSnapshot {
    verification: VerificationEvidence,
    network: NetworkEvidence,
    device: DeviceEvidence,
    continuity: SessionContinuity,
    evaluatedAt: DateTimeImmutable,
    constitutionalHash: string
  }

Later Migration (v2) — adds field:
  ConstitutionalEvidenceSnapshot {
    ... (all v1 fields)
    + riskProfile: array  // NEW FIELD
  }

Replay Problem:
  Try to deserialize v1 snapshot into v2 class
  → Missing riskProfile field
  → Legitimacy evaluation fails
  → Replay BROKEN
  
Result: CANNOT REPLAY HISTORICAL SNAPSHOTS
```

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | D (Replay Corruption via Schema Incompatibility) |
| **Criticality** | EXISTENTIAL |
| **Replay Risk** | **EXISTENTIAL** (breaks across versions) |
| **Constitutional Impact** | Historical evidence becomes unreplayable |
| **Cross-Version Compatibility** | FAILS |
| **Federal Portability** | BLOCKED (cannot transfer snapshots between versions) |
| **Found By** | C.5c serialization safety audit |

---

## Replay Drift Vectors Table

**Master risk matrix for replay stability:**

| Vector | Risk | Severity | Location | Mitigation | Status |
|--------|------|----------|----------|-----------|--------|
| **DB Re-Query** | Mutable legitimacy | HIGH | H.1, H.3, H.4, H.5 | Freeze evidence snapshot | ❌ Not implemented |
| **Temporal Ordering** | Job/command precedence | CRITICAL | BulkApproveVoters, queue jobs | Remove async legitimacy derivation | ❌ Not implemented |
| **Cache Reuse** | Stale authority | CRITICAL | InvalidateMembershipDashboardCache | No cache in sovereignty path | ❌ Violated |
| **Queue Ordering** | Topology replay drift | HIGH | Jobs directory | Deterministic replay ID assignment | ❌ Not implemented |
| **Event Listener Order** | Implicit authority | HIGH | Listeners directory | Remove listener-based gates | ⚠️ Partial |
| **Request Lifecycle Variation** | Temporal drift | MEDIUM | Middleware execution | Freeze at boundary | ❌ Not implemented |
| **Async Broadcast** | Event ordering | HIGH | Broadcasting events | Deterministic message ordering | ❌ Not implemented |
| **Serialization Schema** | Cross-version corruption | EXISTENTIAL | Model snapshots | Schema versioning with migration | ❌ Not implemented |
| **Timezone Variance** | Temporal ambiguity | MEDIUM | Expiration/renewal logic | UTC-only timestamps | ⚠️ Partial |
| **Restart Boundary Crossing** | State loss | MEDIUM | In-memory authority | All state persisted immutably | ❌ Not implemented |

---

## Frozen Evidence Verification

### What IS Frozen in ConstitutionalEvidenceSnapshot?

```php
// app/Domain/Election/Security/Simplified/ConstitutionalEvidenceSnapshot.php

class ConstitutionalEvidenceSnapshot {
    public readonly ElectionConstitutionSnapshot $constitution,
    public readonly VerificationEvidence $verification,
    public readonly NetworkEvidence $network,
    public readonly DeviceEvidence $device,
    public readonly SessionContinuity $continuity,
    public readonly DateTimeImmutable $evaluatedAt,
    public readonly string $constitutionalHash,
}
```

### Frozen ✅ (Safe for Replay)

- ✅ Constitution parameters (network strategy, max votes per IP, etc.)
- ✅ Verification evidence (attested, registrar, attestation time)
- ✅ Network hash (computed at freeze time)
- ✅ Device fingerprint
- ✅ Session continuity state
- ✅ Evaluation timestamp
- ✅ Constitutional hash (versioning)

### NOT Frozen ❌ (Replay Risk)

- ❌ User.can_vote (read at evaluation time, not snapshot)
- ❌ User.voting_ip (read at evaluation time, not snapshot)
- ❌ Cache state (not in snapshot at all)
- ❌ Current request()->ip() (temporal, not frozen)
- ❌ Global vote count (queried live, not in snapshot)
- ❌ Legitimacy metadata (computed, not stored)

---

## Cross-Process Replay Equivalence Test

### Test Protocol

For each constitutional path, verify:

```
Process A (Primary):
  Input: frozen ConstitutionalEvidenceSnapshot S1
  Output: legitimacy decision L1
  Time: T1

Process B (Isolated):
  Input: serialized S1 (deserialized fresh)
  Output: legitimacy decision L2
  Time: T2 (much later)

Assertion: L1 === L2
Failure: Replay divergence detected
```

### Current Status: NOT TESTED

No current test suite verifies replay equivalence across:
- Process restarts ❌
- Node boundaries ❌
- Serialization cycles ❌
- Timezone changes ❌
- Deployment cycles ❌

---

## Cross-Runtime Determinism Requirements

**To be replay-safe, legitimacy MUST remain identical across:**

| Dimension | Current Status | Required |
|-----------|---|---|
| Same process, repeated call | ✅ YES (PHP readonly) | YES |
| Different process, same machine | ❌ UNKNOWN | YES |
| Different node | ❌ NO (cache/queue different) | YES |
| Different timezone | ❌ UNKNOWN | YES |
| After restart | ❌ UNKNOWN | YES |
| After serialization roundtrip | ❌ NO (schema version risk) | YES |
| After deployment | ❌ UNKNOWN | YES |
| With cache enabled/disabled | ❌ NO (cache leaked authority) | YES |
| With queue deferred/immediate | ❌ NO (job order varies) | YES |

---

## Constitutional Cache Doctrine (Violation Assessment)

**Rule:** Cached state must NEVER become sovereign authority.

### Forbidden Pattern (Found)

```php
// Pattern: Cache-derived legitimacy
function isEligible(User $user): bool {
    return Cache::remember("eligible_users_{$user->id}", 3600, function() use ($user) {
        // Legitimacy derived here and cached
        return $user->can_vote;  // ❌ FORBIDDEN
    });
}
```

### Why This Violates Constitutional Doctrine

1. **Replay problem:** Replay cannot reconstruct cache state
2. **Determinism problem:** Cache hit/miss is nondeterministic
3. **Topology problem:** Cache refresh order creates implicit authority
4. **Portability problem:** Cross-process transfer requires shared cache

### Current Violations

| Location | Risk | Status |
|----------|------|--------|
| `InvalidateMembershipDashboardCache` listener | HIGH | Found in C.5c |
| Possible membership caching | UNKNOWN | Requires audit |
| Dashboard state caching | UNKNOWN | Requires audit |

---

## Required Replay Certification Steps

**For Phase D.2 to proceed, MUST certify:**

- [ ] Frozen evidence snapshot remains unchanged across evaluation calls
- [ ] PolicySequence evaluation produces identical results for same snapshot
- [ ] Evidence canonicalization is deterministic and version-stable
- [ ] No mutable DB re-querying during evaluation
- [ ] No cache-derived legitimacy
- [ ] No queue-order-dependent outcomes
- [ ] No listener-order authority
- [ ] No temporal ordering sovereignty
- [ ] Serialization schema versioning prevents cross-version corruption
- [ ] Cross-process replay produces identical legitimacy

---

## Nondeterministic Contamination Vectors Identified

| Vector | Mechanism | Impact | Severity | Requires D.0 Retirement? |
|--------|-----------|--------|----------|--------------------------|
| **Procedural authority (H.1-H.3)** | Middleware/helper blocking | Prevents constitutional evaluation | CRITICAL | YES |
| **Mutable DB evidence (H.4-H.6)** | Re-querying at evaluation time | Different result each call | CRITICAL | YES |
| **Cache-derived legitimacy** | Cache hit/miss determines outcome | Nondeterministic determinism | CRITICAL | YES |
| **Queue job ordering** | Job execution order impacts authority | Topology-dependent legitimacy | HIGH | YES |
| **Event listener order** | Listener registration order matters | Implicit authority precedence | HIGH | MAYBE |
| **Temporal approval commands** | BulkApproveVoters runs outside snapshot | Evidence/reality divergence | CRITICAL | YES |
| **Serialization schema drift** | Snapshot format changes | Replay incompatibility | EXISTENTIAL | YES |
| **Timezone-dependent logic** | Expiration checks use local time | Non-UTC drift | MEDIUM | MAYBE |

---

## Phase D.0 Blocking Conditions

**Replay stability audit reveals BLOCKING conditions for D.0:**

```
D.0 BLOCKED UNTIL:

✓ H.1-H.3 retired (topology-derived authority removed)
✓ Cache doctrine violations remediated (no cached legitimacy)
✓ BulkApproveVoters refactored (no outside-snapshot approval)
✓ Queue job legitimacy removed (no async authority derivation)
✓ Event listener authority removed (no implicit ordering governance)
✓ Serialization schema versioning implemented (replay-safe snapshots)
```

---

## Phase D.3+ (Replay Certification) Readiness

**After D.0-D.2, Phase D.3 (Replay Certification) will require:**

1. **Snapshot equivalence test suite:**
   ```
   For 100 historical snapshots:
     replay(snapshot) ≡ original_legitimacy
   ```

2. **Cross-process replay test:**
   ```
   For each snapshot:
     Process A: evaluate(snapshot) → decision A
     Process B: evaluate(deserialized(serialize(snapshot))) → decision B
     Assert: A === B
   ```

3. **Serialization stability test:**
   ```
   For current and future schema versions:
     Can deserialize v1 snapshots in vN code?
   ```

4. **Distributed replay equivalence test:**
   ```
   For each snapshot:
     Node1: evaluate() → L1
     Node2: evaluate() → L2
     Node3: evaluate() → L3
     Assert: L1 === L2 === L3
   ```

These are NOT currently implementable because procedural authority (H.1-H.3) and cache violations still execute.

---

## Conclusion

**Replay Stability Assessment:** Current codebase has **EXISTENTIAL replay risks** due to:

1. **Temporal ordering sovereignty** (approval commands outside constitutional path)
2. **Cache-derived authority** (legitimacy depends on cache state)
3. **Mutable evidence sources** (re-querying at evaluation time)
4. **Queue topology contamination** (job order affects legitimacy)
5. **Serialization incompatibility** (schema changes break replay)

**Stabilization Status:** Replay determinism IS achievable IF and ONLY IF:
- Procedural authority (H.1-H.3) is retired
- Cache violations are remediated
- Temporal ordering is eliminated
- Serialization schema versioning is implemented

**Phase D.0 Impact:** Replay stability audit confirms that Phase D.0 **MUST** retire H.1-H.3 and remediate cache/queue/async violations before replay certification can be achieved.

**Next Step:** Phase C.5d (Topology Leakage Audit) will audit middleware stack order, route registration, and execution precedence to confirm ValidateVotingIp and other procedural gates are properly sequenced for retirement.

---

**Artifact Status:** READY FOR MANUAL REVIEW

**Critical Finding:** Temporal topology drift (especially BulkApproveVoters) is a **new failure type** discovered by C.5c — not visible in test failure counts but fundamental to replay stability.

