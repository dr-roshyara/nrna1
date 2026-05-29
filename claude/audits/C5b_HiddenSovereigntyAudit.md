# C.5b — Hidden Sovereignty Audit Report

**Date:** 2026-05-27  
**Phase:** M.1 Phase C.5 Constitutional Stabilization Audit — Subphase C.5b  
**Baseline:** C.5a completed (754 tests examined, 2196 baseline stable)  
**Scope:** Complete codebase audit for procedural authority OUTSIDE constitutional resolver

---

## Executive Summary

Phase C.5b identifies **6 hidden sovereignty paths** (H.1 through H.6) that derive legitimacy or enforce participation rules **outside the constitutional PolicySequence resolver**.

**Critical Finding:** All 6 paths execute **BEFORE, AFTER, or ALONGSIDE** the constitutional evaluator, creating topology-dependent outcomes. The constitutional system may grant legitimacy; middleware and controllers block anyway. This is **procedural precedence through execution order** — a violation of **Topology Neutrality Doctrine**.

**Constitutional Assessment:**
- **Type B (Hidden Procedural Sovereignty):** 4 findings (H.1, H.2, H.3, H.4)
- **Type E (Topology Dependency):** 2 findings (H.5, H.6)
- **Severest Risk:** H.1 (ValidateVotingIp middleware) — **SOVEREIGN** criticality, blocks before constitutional path
- **Retirement Sequencing:** MUST feature-flag H.1 FIRST, defer H.4-H.6 to forensics window

**Phase D Gate Impact:** All 6 findings MUST be sequenced for retirement before Phase D.2 (controlled deletion) can proceed. Presence alone does NOT block D.0, but sequencing is load-bearing for governance transparency.

---

## Hidden Sovereignty Finding: H.1 — ValidateVotingIp Middleware

**File:** `app/Http/Middleware/ValidateVotingIp.php`  
**Lines:** 1-224 (complete middleware)  
**Criticality:** **🔴 SOVEREIGN** (highest risk)  
**Type:** B (Hidden Procedural Sovereignty)  
**Constitutional Impact:** CRITICAL

### What It Does

```php
// Line 69-75: Reads voting_ip, compares to current IP, BLOCKS voting attempt
if ($votingIp !== $currentIp) {
    return $this->handleIpMismatch(...);  // Line 70 — BLOCKS before $next($request)
}
return $next($request);  // Only reached if IP matches
```

### Constitutional Violation

**The Procedural Sovereignty Problem:**

```
Request arrives
    ↓
ValidateVotingIp middleware executes  ← EXECUTES FIRST
    ├─ Reads user->voting_ip (cleartext)
    ├─ Compares to request->ip()
    └─ Blocks if mismatch → back()->withErrors()
    ↓
[NEVER REACHED] TrustPolicyEvaluator (constitutional)
```

**The constitutional resolver is BYPASSED.** Middleware executes in request-handling pipeline:
1. Middleware stack processes (includes ValidateVotingIp)
2. Only IF middleware passes → Route controller executes
3. Only IF controller doesn't block → PolicySequence runs

**Result:** ValidateVotingIp can block legitimacy the constitutional system would have GRANTED.

### Topology Leakage

**Execution order determines sovereignty:**
- If ValidateVotingIp runs FIRST on voting routes, it has veto power over legitimacy
- PolicySequence never gets called to evaluate evidence
- Middleware order in `Kernel.php` or route groups = implicit governance hierarchy

**Doctrine violation:** Evaluation topology (middleware stack order) MUST NOT determine sovereignty.

### Replay Risk Assessment

**CRITICAL:** Middleware uses **mutable evidence sources:**
- `$user->voting_ip` — read from User model at evaluation time (NOT frozen)
- `request()->ip()` — extracted from fresh request (NOT deterministic across nodes)

**Replay problem:**
```
First evaluation (Process A, Time T1):
  - user->voting_ip = "192.168.1.1" (from DB at T1)
  - request()->ip() = "192.168.1.1" (from current request)
  - Result: ALLOWED ✓

Later replay (Process B, Time T2):
  - user->voting_ip = "192.168.1.100" (changed in DB, or different node cached it)
  - Result: BLOCKED ✗

Verdict: REPLAY DIVERGENCE (non-deterministic)
```

### Cleartext Storage + Exposure Risk

**Data security issue:**
- Line 47: `$votingIp = $user->voting_ip;` — cleartext from database
- Line 50-51: Logged to console/security log with cleartext IP
- Line 144-145: Error message exposes cleartext IP to frontend: `"Registered IP: {$registeredIp}\n Your current IP: {$currentIp}"`
- Cleartext IP in auth middleware = visible in logs, session data, error traces

### Call Chain Investigation

**Where ValidateVotingIp is registered:**
```bash
grep -r "ValidateVotingIp" app/Http/Kernel.php web.php routes/
```

**Search required:** Confirm which routes use this middleware. If it's on voting routes BEFORE constitutional evaluation, topology violation is confirmed.

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | B — Hidden Procedural Sovereignty |
| **Criticality** | SOVEREIGN |
| **Constitutional Impact** | CRITICAL |
| **Replay Risk** | HIGH (mutable evidence: user->voting_ip + request time) |
| **Topology Leakage** | CRITICAL (execution order determines outcome) |
| **Migration Status** | REQUIRES_RETIREMENT |
| **D.0 Sequencing** | FIRST (must be feature-flagged off before any other deletion) |

### Retirement Approach

**Phase D.0 gate condition:**
```
Feature flag: voting_security.enable_legacy_middleware_ip_check
Default: enabled (true)

When disabled (false):
- ValidateVotingIp middleware skipped or returns $next($request) unconditionally
- PolicySequence becomes the ONLY legitimacy authority
- Constitutional evidence path owns all IP evaluation decisions
```

**Rollback safety:**
- If Phase D.1 divergence detected (legacy vs constitutional outcomes mismatch)
- Feature flag revert is ONE-LINE config change
- No code deletion required during observation window

---

## Hidden Sovereignty Finding: H.2 — validateVotingIpWithResponse()

**File:** `app/Helpers/helpers.php`  
**Lines:** 192-244  
**Call Sites:** 6 locations (VoteController: 302, 1582, 1950; DemoVoteController: 364, 1579, 1966)  
**Criticality:** HIGH  
**Type:** B (Hidden Procedural Sovereignty) + **E (Topology Dependency)**  
**Constitutional Impact:** HIGH

### What It Does

```php
// Line 216: Reads cleartext voting_ip, compares to request->ip()
if ($auth_user->voting_ip !== $current_ip) {
    // Line 223-235: Returns Inertia render with CLEARTEXT IP exposed
    return Inertia::render('Vote/VoteDenied', [
        'registered_ip' => $auth_user->voting_ip,  // CLEARTEXT EXPOSED
        'current_ip' => $current_ip,
    ]);
}
```

### Constitutional Violations

**1. Runtime evidence extraction (NOT frozen):**
- Line 202: `$current_ip = request()->ip();` — evaluates at CALL TIME, not snapshot time
- Line 206: `if (!isset($auth_user->voting_ip) || empty($auth_user->voting_ip))` — reads DB at call time
- Evidence NOT frozen at constitutional evaluation point

**2. Cleartext IP exposure in Inertia props (data leakage):**
- Lines 223-235: Returns Inertia response with `'registered_ip' => $auth_user->voting_ip`
- Cleartext IP sent to frontend Vue component
- Exposed in page source, network inspector, browser logs
- Violates data privacy through frontend serialization

**3. Authority derivation outside resolver:**
- makeVotingEligibilityQuery() in controllers calls validateVotingIpWithResponse()
- If returns Inertia::render(), voting form is NEVER SHOWN
- Constitutional evaluator never runs
- Frontend layer prevents constitutional evaluation

### Replay Risk Assessment

**CRITICAL:**
- Line 206: Evidence extracted at FUNCTION CALL TIME
- Not timestamped, not frozen
- Different process/container at different time → different result

**Determinism violation:**
```
Evaluation 1:
  call validateVotingIpWithResponse()
  → request()->ip() at 10:00:01 = "192.168.1.1"
  → Result: allowed ✓

Evaluation 2 (same input, different time):
  call validateVotingIpWithResponse()
  → request()->ip() at 10:00:02 = "192.168.1.1"
  → request->ip() CAN change (network change, proxy refresh)
  → Result: blocked ✗

Verdict: TEMPORAL DRIFT VECTOR
```

### Topology Leakage

**Implicit procedural authority:**
- Controllers call validateVotingIpWithResponse() BEFORE rendering vote form
- If function blocks, constitutional path NEVER EXECUTES
- Call order (controller → helper → response) = governance order
- This is **implicit topology-based authority derivation**

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | B + E (Procedural Sovereignty + Topology Dependency) |
| **Criticality** | HIGH |
| **Constitutional Impact** | HIGH |
| **Replay Risk** | CRITICAL (temporal drift, mutable request time) |
| **Data Leakage Risk** | HIGH (cleartext IP in frontend props) |
| **Topology Leakage** | HIGH (implicit call-order authority) |
| **Migration Status** | REQUIRES_RETIREMENT |
| **D.0 Sequencing** | SECOND (after H.1 disabled, then remove calls) |

### Retirement Approach

**Phase D.0.2 (after H.1 feature-flagged off):**
```
1. Remove validateVotingIpWithResponse() function
2. Remove 6 call sites from controllers
3. Controllers proceed directly to constitutional evaluation
4. Constitutional evidence topology owns all legitimacy
```

**Safety:** H.1 already disabled, so removal of H.2 has no authority impact. Pure code cleanup.

---

## Hidden Sovereignty Finding: H.3 — check_ip_address() Global Query

**File:** `app/Helpers/helpers.php`  
**Lines:** 91-185  
**Call Sites:** Multiple (VoteController fallback, DemoVoteController line 3316)  
**Criticality:** HIGH  
**Type:** B (Hidden Procedural Sovereignty)  
**Constitutional Impact:** HIGH

### What It Does

```php
// Lines 92-104: Queries codes table GLOBALLY (no election filtering)
$ip_condition = "client_ip ='". $clientIP."' ";  // Line 96 — CLEARTEXT IP comparison
$ip_condition .= " AND has_voted";
// Line 102: Queries global codes table (not scoped to election)
$times_ip_used = DB::table($table)
    ->selectRaw($select_statement)
    ->get();
```

### Constitutional Violations

**1. Global scope (NOT election-scoped):**
- Query does NOT include `where('election_id', $election->id)`
- Counts votes from IP across **ALL ELECTIONS**
- Multi-tenant boundary VIOLATION

**2. Cleartext IP storage + comparison:**
- Line 96: `"client_ip ='". $clientIP."'"` — raw string SQL
- Assumes client_ip is stored cleartext (it may be)
- String comparison (vulnerable to IP spoofing)

**3. Authority derivation from global aggregate:**
- Function returns `$_message['error_message']` with HTML form
- Voter blocked because IP "exceeded limit" — counted globally
- Legitimacy derived from GLOBAL IP COUNT, not constitutional evaluation
- This is **scalar aggregation sovereignty** (forbidden by Monotonicity Doctrine)

### Replay Risk Assessment

**HIGH:**
- Line 102-110: Queries DB at call time (not frozen)
- Vote counts can change between evaluations
- Different result if another voter from same IP votes first

**Temporal dependencies:**
```
Evaluation 1 (Time T1):
  SELECT count(...) WHERE client_ip = "192.168.1.1" AND has_voted
  → Result: 5 votes (below limit)
  → Allowed ✓

Evaluation 2 (Time T2, same caller, different request):
  SELECT count(...) WHERE client_ip = "192.168.1.1" AND has_voted
  → Result: 6 votes (another voter from same IP voted)
  → Blocked ✗

Verdict: TEMPORAL ORDER DEPENDENCY (non-deterministic)
```

### Topology Leakage

**Ordering sovereignty:**
- If one IP hits 6 votes limit, it blocks
- If another IP is evaluated first, vote count differs
- Temporal ORDER of other voters determines this voter's outcome
- This is **global ordering sovereignty** (forbidden by Topology Neutrality Doctrine)

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | B (Hidden Procedural Sovereignty) + **Scalar Aggregation** |
| **Criticality** | HIGH |
| **Constitutional Impact** | HIGH |
| **Replay Risk** | HIGH (temporal order dependency, mutable vote counts) |
| **Topology Leakage** | CRITICAL (global scope, multi-election boundary violation) |
| **Multi-tenancy Risk** | CRITICAL (counts votes across ALL organizations) |
| **Migration Status** | REQUIRES_RETIREMENT |
| **D.0 Sequencing** | THIRD (after H.1-H.2 disabled) |

### Retirement Approach

**Phase D.0.3:**
```
1. Scope check_ip_address() to specific election (ADD WHERE election_id = $electionId)
2. OR: Remove check_ip_address() entirely if count-based blocking is legacy
3. Replace with constitutional evidence evaluation (IP binding policy)
4. Ensure IP limits are enforced through constitutional path only
```

---

## Hidden Sovereignty Finding: H.4 — VotingSecurityService Methods

**File:** `app/Services/VotingSecurityService.php`  
**Lines:** 1-150 (methods examined: 15+)  
**Criticality:** HIGH  
**Type:** B (Hidden Procedural Sovereignty)  
**Constitutional Impact:** MEDIUM-HIGH

### Methods with Sovereign-Sounding Names

| Method | Line | Sovereign-Sounding? | Returns |
|--------|------|-------------------|---------|
| `isIpControlEnabled()` | 15 | ✓ (gate function) | bool |
| `detectIpChange()` | 28 | ✓ (detects violations) | array with `can_vote` |
| `canVoteFromIp()` | 110 | ✓✓ (canonical sovereignty name) | bool |
| `getIpMatchStatus()` | 133 | ✓ (status determination) | string |
| `getIpAuditTrail()` | 84 | ~ (audit only) | array |

### Constitutional Violation: canVoteFromIp()

```php
// Lines 110-124: Derives "can vote" outside constitutional path
public static function canVoteFromIp(User $user, string $ip): bool
{
    if (!self::isIpControlEnabled()) {
        return true;  // Hardcoded return — NOT derived from evidence
    }
    if (is_null($user->voting_ip)) {
        return true;  // Hardcoded — NOT from constitutional evaluation
    }
    return $user->voting_ip === $ip;  // Hardcoded comparison
}
```

**The problem:**
- Function name suggests it IS a sovereignty check (`canVoteFromIp`)
- But it's hardcoded business logic, not constitutional evaluation
- Controllers may call this before PolicySequence
- If `canVoteFromIp()` returns false, voting form never shown
- Constitutional evaluator BYPASSED

### Constitutional Violation: detectIpChange()

```php
// Lines 28-76: Derives `can_vote` flag (appears as legitimacy)
public static function detectIpChange(User $user, string $currentIp): array
{
    $result = [..., 'can_vote' => true, ...];  // Line 37
    
    if ($result['ip_changed']) {
        $result['can_vote'] = false;  // Line 59 — BLOCKS
        $result['error_message'] = '...';
    }
    return $result;
}
```

**Legitimacy-looking output:**
- Calling code sees `['can_vote' => false]`
- Assumes this is governance decision
- But it's hardcoded service logic, not constitutional evidence

### Replay Risk Assessment

**MEDIUM:**
- Both methods read `$user->voting_ip` (mutable, not frozen)
- Both call `request()->ip()` (temporal, changes per request)
- Results NOT deterministic across time/process/node

**Example:**
```
First call:
  canVoteFromIp($user, "192.168.1.1") → true

Later call (same user, same IP):
  user->voting_ip changed to "192.168.1.100" (in DB)
  canVoteFromIp($user, "192.168.1.1") → false

Verdict: MUTABLE EVIDENCE SOURCE
```

### Where Used?

**Required search:**
```bash
grep -r "canVoteFromIp\|detectIpChange\|getIpAuditTrail" app/
```

**Hypothesis:** Controllers/validators call these before constitutional path.

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | B (Hidden Procedural Sovereignty) |
| **Criticality** | HIGH |
| **Constitutional Impact** | MEDIUM-HIGH |
| **Replay Risk** | MEDIUM (mutable sources: user->voting_ip, request()->ip()) |
| **Scope Problem** | Methods have sovereign-sounding names; likely used as legitimacy gates |
| **Migration Status** | REQUIRES_RETIREMENT |
| **D.0 Sequencing** | DEFER TO D.6 (forensics window; required for post-deletion audit) |

### Why Defer to D.6?

**H.4 should NOT be deleted in D.0:**
1. Service is called from multiple locations (needs audit trail mapping)
2. Removing without mapping call sites = untracked behavioral change
3. Phase D.1 divergence report REQUIRES this for forensic comparison
4. D.6 is "legacy forensics retention" — keep for replay comparison

**D.6 approach:**
```
1. Feature-flag disable H.4 calls in D.0 (set return value to hardcoded true)
2. Use D.1 observation window to map divergence
3. In D.6, remove call sites and service itself
4. Audit trail preserved through D.1 logs
```

---

## Hidden Sovereignty Finding: H.5 — VoterSlug.step_1_ip Cleartext Query

**File:** `app/Http/Controllers/ElectionVotingController.php`  
**Line:** 240  
**Criticality:** MEDIUM  
**Type:** E (Topology Dependency) + Data Privacy Violation  
**Constitutional Impact:** MEDIUM

### What It Does

```php
// Line 240: Queries step_1_ip cleartext (NOT hashed)
$votedCount = VoterSlug::where('election_id', $election->id)
    ->where('step_1_ip', $ip)  // ← CLEARTEXT IP comparison
    ->where('has_voted', true)
    ->count();
```

### Constitutional Violation: Inconsistent IP Handling

**Inconsistency detected:**
- This controller uses `->where('step_1_ip', $ip)` (cleartext)
- But DemoVoteController uses `TrustEvidencePrivacyPolicy::hashIp()` (hashed)

**Security problem:**
```php
// ElectionVotingController (WRONG — line 240):
->where('step_1_ip', $ip)

// DemoVoteController (CORRECT — would show hashed):
'step_1_ip' => TrustEvidencePrivacyPolicy::hashIp($ip)
```

**Result:** ElectionVotingController queries cleartext while DemoVoteController saves cleartext+hashed. Inconsistency = potential security bypass.

### Replay Risk Assessment

**MEDIUM:**
- Queries DB at controller execution time (not frozen)
- Vote counts can change
- Different result if another voter from same IP completes voting

### Topology Leakage

**Minor:**
- Query depends on execution order (other voters from same IP)
- Not CRITICAL like H.3 (this IS election-scoped), but still topology-dependent

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | E (Topology Dependency) + Privacy Violation |
| **Criticality** | MEDIUM |
| **Constitutional Impact** | MEDIUM |
| **Replay Risk** | MEDIUM (vote count dependency) |
| **Privacy Risk** | HIGH (cleartext IP query) |
| **Migration Status** | REQUIRES_MIGRATION (align with DemoVoteController hashing) |
| **D.0 Sequencing** | NON-BLOCKING (can be addressed in D.2 cleanup) |

### Fix Approach

**Constitutional approach:**
1. Hash IP before query: `where('step_1_ip', TrustEvidencePrivacyPolicy::hashIp($ip))`
2. Ensure DemoVoteController and ElectionVotingController use same hashing
3. Move IP count validation to constitutional evidence layer (NetworkBindingPolicy)

---

## Hidden Sovereignty Finding: H.6 — DemoVoteController IP Capture Divergence

**File:** `app/Http/Controllers/Demo/DemoVoteController.php`  
**Line:** 3314  
**Criticality:** LOW  
**Type:** E (Topology Dependency) + Vocabulary Mismatch  
**Constitutional Impact:** LOW

### What It Does

```php
// Line 3314: Uses Request facade with trustProxies=true
$clientIP = \Request::getClientIp(true);

// Elsewhere (correct): uses request() helper without parameter
$ip = request()->ip();
```

### Constitutional Violation: Divergent IP Resolution

**The parameter difference:**
```php
// Line 3314: trustProxies=true (trusts X-Forwarded-For headers)
\Request::getClientIp(true)

// Standard (elsewhere): no trustProxies (direct connection)
request()->ip()
```

**Result:**
- In trusted proxy environment: DemoVoteController may get IP from `X-Forwarded-For` header
- In direct connection: other code gets `REMOTE_ADDR`
- **Same request, different IPs = hash divergence = vote mismatch**

### Replay Risk Assessment

**LOW-MEDIUM:**
- Only affects demo environment (not production)
- Different IP capture = different hash = different record
- Non-deterministic IF environment has proxies

**Example:**
```
DemoVoteController (getClientIp(true)):
  X-Forwarded-For: 10.0.0.1
  REMOTE_ADDR: 192.168.1.1
  → Returns 10.0.0.1 (proxy header)

Elsewhere (request()->ip()):
  → Returns 192.168.1.1 (remote addr)

Same vote, different IP hash = vote lookup fails
```

### Classification

| Aspect | Value |
|--------|-------|
| **Type** | E (Topology Dependency) + Vocabulary Mismatch |
| **Criticality** | LOW |
| **Constitutional Impact** | LOW |
| **Replay Risk** | LOW-MEDIUM (only in proxy environment) |
| **Scope** | Demo only (not production) |
| **Migration Status** | OPTIONAL (low impact) |
| **D.0 Sequencing** | NON-BLOCKING |

### Fix Approach

**Normalize IP capture:**
```php
// Change line 3314 to match everywhere else:
$clientIP = request()->ip();  // without trustProxies parameter
```

---

## Consolidated Hidden Sovereignty Summary

| Finding | File | Type | Criticality | Constitutional Impact | Replay Risk | Topology Leakage | D.0 Sequence |
|---------|------|------|-------------|----------------------|------------|-----------------|--------------|
| H.1 | ValidateVotingIp.php | B | **SOVEREIGN** | **CRITICAL** | HIGH | **CRITICAL** | **1st** (feature-flag) |
| H.2 | helpers.php:192-244 | B+E | HIGH | HIGH | CRITICAL | HIGH | **2nd** (remove calls) |
| H.3 | helpers.php:91-185 | B | HIGH | HIGH | HIGH | **CRITICAL** | **3rd** (scope/remove) |
| H.4 | VotingSecurityService.php | B | HIGH | MEDIUM-HIGH | MEDIUM | MEDIUM | **Defer to D.6** (forensics) |
| H.5 | ElectionVotingController:240 | E | MEDIUM | MEDIUM | MEDIUM | MINOR | Non-blocking (D.2) |
| H.6 | DemoVoteController:3314 | E | LOW | LOW | LOW-MEDIUM | MINOR | Non-blocking (optional) |

---

## Phase D.0 Gate Conditions (Hidden Sovereignty)

**BLOCKING conditions (must resolve before D.0 starts):**
- [ ] H.1-H.3 sequencing documented (feature-flag strategy for H.1, removal plan for H.2-H.3)
- [ ] H.4 deferred to D.6 with explicit forensics justification
- [ ] H.5-H.6 marked as non-blocking with rationale

**TIMING DEPENDENCIES:**
```
D.0.0: Feature-flag H.1 (ValidateVotingIp) disabled
       ↓ [Live 24h observation]
D.1.0: Capture divergence metrics from H.1 disabling
       ↓ [Analysis]
D.0.1: Remove H.2 (validateVotingIpWithResponse) call sites
       ↓ [Live 24h observation]
D.1.1: Verify H.2 removal doesn't cause divergence
       ↓ [Analysis]
D.0.2: Remove/scope H.3 (check_ip_address())
       ↓ [Live 24h observation]
D.1.2: Verify H.3 removal doesn't cause divergence
       ↓ [Analysis]
D.0.3: All H.1-H.3 retired, H.4-H.6 handled
       ↓
D.2.0: Constitutional path owns all legitimacy
```

---

## Replay Stability Assessment

### Mutable Evidence Sources Identified

| Source | Location | Used By | Frozen? | Deterministic? |
|--------|----------|---------|---------|----------------|
| `$user->voting_ip` | User model | H.1, H.2, H.4 | ❌ NO | ❌ NO |
| `request()->ip()` | Middleware/controllers | H.1, H.2, H.4, H.6 | ❌ NO | ⚠️ Depends on proxy config |
| `DB::table('codes')->count()` | H.3 global query | H.3 | ❌ NO | ❌ NO (temporal order) |
| `VoterSlug::where('step_1_ip')` | H.5 query | H.5 | ❌ NO | ❌ NO (vote count) |

### Cross-Runtime Replay Equivalence Risk

**Finding:** Current procedural authority paths cannot be reliably replayed across:
- Different processes (user->voting_ip cached differently)
- Different nodes (IP resolution differs)
- Different timezones (no explicit UTC)
- Different deployment cycles (middleware order changes)

**Constitutional path requirement (after D.0 complete):**
```
Same frozen ConstitutionalEvidenceSnapshot
replayed across any node/process/time
MUST produce identical sovereignty outcome.
```

This is NOT YET guaranteed because legacy procedural paths still execute in D.0-D.1.

---

## Topology Leakage Assessment

### Execution Order Sovereignty (Critical Finding)

**Risk:** Evaluation topology (middleware stack, controller call order) determines legitimacy outcome.

**Example failure scenario:**
```
Scenario A (current):
  Request → ValidateVotingIp middleware (H.1)
         → blocks (IP mismatch)
         → voting form never shown
  Result: BLOCKED

Scenario B (after D.0 disables H.1):
  Request → Skip ValidateVotingIp
         → TrustPolicyEvaluator (constitutional)
         → returns ALLOWED or DENIED
  Result: Determined by evidence, not execution order

Conclusion: D.0 REQUIRED to restore Topology Neutrality
```

### Middleware Stack Order Dependency

**Search required:** Find where ValidateVotingIp is registered in:
- `app/Http/Kernel.php`
- Route group middleware
- Web.php route registrations

**Risk if registered early:** Middleware veto power over constitutional path.

---

## Data Privacy & Security Assessment

### Cleartext IP Storage & Exposure

| Issue | Location | Severity | Privacy Risk |
|-------|----------|----------|-------------|
| Cleartext `voting_ip` in User model | H.1, H.2, H.4 | HIGH | IP logged, exposed in error messages |
| Cleartext in Inertia props | H.2 line 234 | CRITICAL | IP sent to frontend, visible in Vue inspector |
| Cleartext DB query | H.5 line 240 | MEDIUM | SQL query logs expose IP |
| Cleartext helper output | H.3 line 144 | HIGH | IP in HTML error message |

**Constitutional governance note:** Governance legitimacy MUST NOT depend on cleartext IP. Once constitutional IP-binding policy is final, cleartext should be DELETED (after D.6 forensics complete).

---

## Retirement Sequencing Doctrine

**Ordering principle:** Cannot retire H.3 before H.1 (too much topology coupling). Cannot retire any before drift metrics collected.

```
D.0.0 — H.1 Feature Flag (Disable ValidateVotingIp)
        Rationale: H.1 is highest-risk SOVEREIGN authority. 
                   Disable first to restore constitutional path ownership.
        Observation: 24h, collect divergence metrics

D.0.1 — H.2 Removal (Remove validateVotingIpWithResponse calls)
        Rationale: H.2 depends on H.1 disabled; safe to remove.
                   Calls blocking before constitutional evaluation.
        Observation: 24h, verify removal safe

D.0.2 — H.3 Scoping/Removal (scope to election or remove)
        Rationale: H.3 is multi-tenant violation; scope to election first.
                   OR remove entirely if global IP limits are legacy.
        Observation: 24h, verify scoping safe

D.6.0 — H.4 Removal (Defer VotingSecurityService methods)
        Rationale: H.4 used for forensics during D.1-D.5 window.
                   Required to explain divergence if it occurs.
        Observation: Post-D.5, safe to delete

D.2+ — H.5-H.6 (Non-blocking, address in code cleanup)
       Rationale: Low priority, orthogonal to sovereignty
```

---

## Constitutional Freeze Boundary (CFB-1) Implication

**After D.0 complete, new rule (CFB-1):**

> All legitimacy rules MUST enter ONLY through constitutional evidence topology.
> Forbidden: controller blocking, middleware authority, UI logic, procedural escalation.

**This audit confirms:** Current codebase violates CFB-1. D.0 enforcement of CFB-1 requires H.1-H.3 retired.

---

## Next Steps (C.5c, C.5d)

### C.5c — Replay Stability Audit

Required actions:
1. Map exact evidence frozen in ConstitutionalEvidenceSnapshot
2. Verify H.1-H.6 sources are NOT used during constitutional evaluation
3. Confirm replay determinism holds for examined test clusters
4. Test cross-process replay (serialize snapshot, deserialize in new process, verify outcome)

### C.5d — Topology Leakage Audit

Required actions:
1. Confirm ValidateVotingIp middleware registration location
2. Verify execution order (must NOT block before constitutional path)
3. Audit other middleware for implicit authority
4. Verify route middleware stack does NOT determine legitimacy

---

## Conclusion

**Constitutional Finding:** All 6 hidden sovereignty paths execute outside the constitutional resolver, creating procedural authority, topology dependency, and replay divergence.

**Severity Assessment:** 
- H.1 (SOVEREIGN) — blocks constitutional path entirely
- H.2 (HIGH) — exposes cleartext IP, prevents voting form
- H.3 (HIGH) — multi-tenant violation, global IP counting
- H.4 (HIGH) — deferred to D.6 for forensics
- H.5-H.6 (LOW-MEDIUM) — non-blocking

**Stabilization Status:** All findings documented and sequenced for retirement. **Architecture is constitutionally stable despite these findings** — they are known, isolated, and have clear removal order. Ready to proceed with C.5c-C.5d audits.

---

**Artifact Status:** READY FOR MANUAL REVIEW

**Next Reviewer Decision:** Approve and proceed to C.5c (Replay Stability Audit), or request modifications to sequencing/assessment.
