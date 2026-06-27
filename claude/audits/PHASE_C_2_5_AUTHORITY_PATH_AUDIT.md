# Phase C.2.5 — Authority-Path Audit (Step 0)

**Date:** 2026-05-25  
**Scope:** Comprehensive mapping of all remaining sovereignty reconstruction paths before deletion  
**Methodology:** Codebase grep + code inspection, classified by severity and type

---

## Executive Summary

| Category | Count | Status |
|----------|-------|--------|
| **Critical sovereignty bridges** | 2 | Require immediate quarantine + adapter layer |
| **Middleware authority derivation** | 2 | Partially migrated — 1 critical, 1 acceptable |
| **State-based permission logic** | 5 | Controllers + helpers with state interpretation |
| **Test infrastructure duplication** | 16+ test methods | Preserve failing tests as diagnostic info |
| **Deprecated wrappers** | 1 | ElectionStateMachine compatibility shell |
| **Safe delegation paths** | 2 | EnsureVotingActive, VoteEligibility (already correct) |

---

## CRITICAL: Sovereignty Reconstruction Paths

### 🔴 CRITICAL-1: Election::allowsAction()

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Models/Election.php:934` |
| **Type** | Legacy authority bridge |
| **Severity** | CRITICAL |
| **Current Pattern** | Interprets lifecycle state + operation → authority decision |
| **Authority Chain** | `allowsAction()` → delegates to `ElectionLifecycle::of()` facade → `ElectionLifecycleEngineImpl::compute()` |
| **Call Sites** | 1 direct consumer: `ElectionStateMachine::allowsAction()` (line 79) |
| **Blast Radius** | `EnsureElectionState` middleware (line 33) |

**Code Snippet (lines 934-963):**
```php
public function allowsAction(string $action): bool
{
    $snapshot = \App\Application\Election\Facades\ElectionLifecycle::of($this)->snapshot();
    
    return match ($action) {
        'manage_posts' => $snapshot->canEdit,
        'import_voters' => $snapshot->canManageVoters,
        // ... 13 more action mappings
        'apply_candidacy' => $snapshot->state === ElectionLifecycleState::SetupNomination,
        // ...
        default => false,
    };
}
```

**Classification:**
- ✅ Does delegate to `ElectionLifecycle` (SSOT)
- ⚠️ But maintains operation→capability mapping (transitional shell)
- ⚠️ Is a public API that middleware depends on

**Decontamination Path:**
1. Step 3 (P4): Introduce deprecation adapter that emits telemetry
2. Step 2 (P3): Middleware directly consume capabilities instead of calling allowsAction()
3. Step 7 (P7): Remove method after migration complete

---

### 🔴 CRITICAL-2: EnsureElectionState Middleware

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Middleware/EnsureElectionState.php:11-46` |
| **Type** | Middleware sovereignty enforcement |
| **Severity** | CRITICAL |
| **Current Pattern** | Takes operation string → calls election.getStateMachine().allowsAction() → returns 403 |
| **Routes Protected** | Unknown — must grep routes |
| **Authority Chain** | Middleware → ElectionStateMachine → Election::allowsAction() → ElectionLifecycle |

**Code Snippet (lines 33-43):**
```php
$allowsAction = $election->getStateMachine()->allowsAction($operation);

if (!$allowsAction) {
    $stateInfo = $election->state_info;
    abort(403, sprintf(
        'Operation "%s" is not allowed during the "%s" phase.',
        $operation,
        $stateInfo['name']
    ));
}
```

**Classification:**
- ⚠️ Delegation chain is correct (→ allowsAction → ElectionLifecycle)
- ⚠️ But middleware is a sovereignty ENFORCEMENT point — changes here affect request-level authorization
- 🔴 **HIDDEN RISK:** Inverse of SSOT — tests what middleware blocks, not what resolver enables

**Decontamination Path:**
1. Step 2 (P3): Refactor middleware to ask resolver directly (not via allowsAction bridge)
2. Step 3 (P4): Quarantine old pattern with deprecation telemetry
3. Step 7 (P7): Remove bridge after verification

---

## ORANGE: Middleware Sovereignty Derivation (Partially OK)

### 🟠 MEDIUM-1: EnsureVotingActive Middleware

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Middleware/EnsureVotingActive.php:38-64` |
| **Type** | Middleware defense-in-depth |
| **Severity** | MEDIUM (acceptable) |
| **Current Pattern** | Delegates to `ElectionLifecycle::canVote()` ✅ |

**Code (lines 49-61):**
```php
$lifecycle = ElectionLifecycle::of($election);
if (! $lifecycle->canVote()) {
    $blockedReason = $lifecycle->blockedReason() ?? 'Voting is not currently active.';
    return redirect()->route('dashboard')->with('error', $blockedReason);
}
```

**Classification:**
- ✅ Correctly delegates to ElectionLifecycle (SSOT)
- ✅ No state interpretation or permission reconstruction
- ✅ Defense-in-depth pattern (middleware asks resolver, doesn't derive)

**Action:** NO CHANGE NEEDED. This is the correct pattern to replicate.

---

### 🟠 MEDIUM-2: VoteEligibility Middleware

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Middleware/VoteEligibility.php:62-77` |
| **Type** | Middleware defense-in-depth |
| **Severity** | MEDIUM (acceptable) |
| **Current Pattern** | Delegates to `ElectionLifecycle::canVote()` ✅ |

**Code (lines 62-77):**
```php
$lifecycle = ElectionLifecycle::of($election);
if (!$lifecycle->canVote()) {
    $blockedReason = $lifecycle->blockedReason()
        ?? 'Voting is not currently active for this election.';
    return redirect()->route('dashboard')->with('error', $blockedReason);
}
```

**Classification:**
- ✅ Correctly delegates to ElectionLifecycle (SSOT)
- ✅ No state interpretation
- ✅ Defense-in-depth pattern

**Action:** NO CHANGE NEEDED. This is the correct pattern.

---

## YELLOW: Controller & Helper State Interpretation

### 🟡 MEDIUM-3: AdminElectionController State Checks

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Controllers/Admin/AdminElectionController.php:35, 55` |
| **Type** | Controller-local state interpretation |
| **Severity** | MEDIUM |
| **Current Pattern** | Direct state comparison: `$election->state !== 'pending_approval'` |

**Code (lines 35, 55):**
```php
if ($election->state !== 'pending_approval') {
    abort(403, 'Election must be in pending approval state to approve.');
}

if ($election->state !== 'pending_approval') {
    abort(403, 'Election must be in pending approval state to reject.');
}
```

**Classification:**
- ⚠️ Hardcoded state string
- ⚠️ Local permission derivation (not using resolver)
- ⚠️ Bypasses ElectionCapabilityResolver
- This is controller-local permission logic

**Decontamination Path:**
1. Step 2 (P3): Refactor to use `ElectionCapabilityResolver` for `approve` / `reject` capabilities
2. Or: Route through policy instead of controller

---

### 🟡 MEDIUM-4: ElectionManagementController Voting Window Checks

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Controllers/Election/ElectionManagementController.php:279, 382` |
| **Type** | Controller state filtering |
| **Severity** | MEDIUM |
| **Current Pattern** | State equality check in filter: `ElectionLifecycle::of($e)->state()->value === 'voting_active'` |

**Code (lines 279, 382):**
```php
$activeElection = $elections->first(fn($e) => 
    ElectionLifecycle::of($election)->state()->value === 'voting_active'
);
```

**Classification:**
- ⚠️ Uses constant comparison instead of `ElectionLifecycleStates::VOTING_ACTIVE`
- ⚠️ But delegates state computation to ElectionLifecycle (SSOT)
- ✅ Not deriving permission — just filtering visualization

**Action:** Refactor to use constant + maybe wrap in capability check if this is an access guard (not visualization).

---

### 🟡 MEDIUM-5: ElectionStateMachine Wrapper

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Domain/Election/StateMachine/ElectionStateMachine.php:14-80` |
| **Type** | Deprecated compatibility wrapper |
| **Severity** | MEDIUM |
| **Current Pattern** | Delegates all authority to `Election::allowsAction()` |
| **Deprecation Status** | Marked deprecated in docblock (line 11) |

**Code (lines 77-80):**
```php
public function allowsAction(string $action): bool
{
    return $this->election->allowsAction($action);
}
```

**Classification:**
- ✅ Is a pure delegation wrapper (no duplication)
- ✅ Marked @deprecated
- ⚠️ Only 1 call site: EnsureElectionState middleware
- ⚠️ Hiding the real authority bridge (Election::allowsAction)

**Decontamination Path:**
1. Step 2 (P3): Remove wrapper, have middleware call Election::allowsAction() directly (visibility)
2. Step 4 (P4): Introduce deprecation telemetry on Election::allowsAction()
3. Step 7 (P7): Remove Election::allowsAction() entirely

---

## BLUE: Safe Delegation Patterns (No Action Needed)

### ✅ SAFE-1: ElectionLifecycle Facade

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Application/Election/Facades/ElectionLifecycle.php` |
| **Type** | SSOT facade |
| **Severity** | NONE (correct pattern) |
| **Current Pattern** | Aggregates ElectionLifecycleEngine → QueryPolicyGuard → ConstitutionalMetrics |

**Why Safe:**
- ✅ Single source of truth
- ✅ All capability consumers go through this
- ✅ No state interpretation locally
- ✅ Emits observability metrics

**Action:** NO CHANGE NEEDED.

---

### ✅ SAFE-2: EnsureRealVoteOrganisation Middleware

| Attribute | Value |
|-----------|-------|
| **Location** | `app/Http/Middleware/EnsureRealVoteOrganisation.php` |
| **Type** | Multi-tenancy enforcement |
| **Severity** | NONE (correct pattern) |
| **Current Pattern** | Validates voter_slug ↔ election org consistency |

**Why Safe:**
- ✅ Not an authority derivation point
- ✅ Multi-tenancy boundary check
- ✅ Logs security events

**Action:** NO CHANGE NEEDED.

---

## RED: Test Infrastructure Duplication

### 🔴 TEST-1: Authority Derivation Duplication in Tests

| Test File | Location | Pattern | Severity |
|-----------|----------|---------|----------|
| `ElectionPolicyStateAwareTest.php` | Multiple | Tests policy + state → derives expected permission | CRITICAL |
| `TimelineCapabilityAuthorizationTest.php` | Multiple | Tests capability resolver + asserts permission | CRITICAL |
| `ElectionStateMachineTest.php` | Multiple | Tests state machine transitions + permission derivation | CRITICAL |

**Classification:**
- 🔴 Tests currently validate state → permission mapping
- 🔴 If we change state machine, tests must be rewritten
- 🔴 BUT: Tests are diagnostic — they show WHERE authority duplication exists
- ✅ DO NOT REWRITE TESTS FIRST — preserve them as failure diagnosis tools

**Decontamination Path (Step 5, P5 - LAST):**
1. Refactor runtime code to use resolver only
2. Preserve failing tests
3. THEN update tests to match new SSOT pattern
4. Use test failures as verification that authority has migrated

---

## Summary Classified Map

| Location | Type | Severity | Current Pattern | Decontamination Step |
|----------|------|----------|-----------------|----------------------|
| `Election::allowsAction()` | Bridge | 🔴 CRITICAL | Operation → Lifecycle → Snapshot | Step 4 (P4) quarantine |
| `EnsureElectionState` middleware | Enforcement | 🔴 CRITICAL | Calls allowsAction() | Step 2 (P3) refactor |
| `ElectionStateMachine::allowsAction()` | Wrapper | 🟡 MEDIUM | Pure delegation | Step 1 (P2) freeze |
| `AdminElectionController` state checks | Controller | 🟡 MEDIUM | Direct state comparison | Step 2 (P3) refactor |
| `ElectionManagementController` filtering | Controller | 🟡 MEDIUM | State filtering (visualization) | Minor: use constants |
| `EnsureVotingActive` middleware | Middleware | ✅ SAFE | Delegates to canVote() | NO CHANGE |
| `VoteEligibility` middleware | Middleware | ✅ SAFE | Delegates to canVote() | NO CHANGE |
| `ElectionLifecycle` facade | SSOT | ✅ SAFE | All authority flows through | NO CHANGE |
| Test infrastructure | Tests | 🔴 CRITICAL | Validates authority duplication | Step 5 (P5) — preserve, migrate last |

---

## Hidden Sovereignty Reconstruction Risks

### Risk 1: Inverse Authority Problem
- **What:** Middleware checks `!allowsAction()` → aborts 403
- **Why It Matters:** Tests what's BLOCKED, not what's ENABLED
- **Example:** EnsureElectionState blocks undefined actions as 403 (correct), but this is inverse of capability snapshot (which says what IS allowed)
- **Mitigation:** Step 2 refactor must reverse direction: ask resolver for capability, check `capability.allowed`

### Risk 2: Deprecated Bridge Visibility
- **What:** Old bridges remain public API (Election::allowsAction)
- **Why It Matters:** Controllers, tests, helpers can continue using the old pattern silently
- **Example:** AdminElectionController could call election.allowsAction('approve') instead of using resolver
- **Mitigation:** Step 3 must introduce deprecation telemetry to track all old-pattern usage before removal

### Risk 3: State Constants Bypass
- **What:** Hardcoded state string comparisons in controllers
- **Why It Matters:** State constant can change, hardcoded strings remain stale
- **Example:** AdminElectionController::35 checks `!== 'pending_approval'` (should use constant OR resolver)
- **Mitigation:** Step 2 refactor + architecture test to forbid hardcoded state strings

### Risk 4: Test Rewrite Too Early
- **What:** Rewriting tests before runtime refactor completes
- **Why It Matters:** Hides the real path of authority duplication
- **Example:** If test is rewritten to match new resolver behavior, we lose visibility into what the old code was doing
- **Mitigation:** Step 5 (P5) refactor tests LAST, after runtime is clean

---

## Recommended Immediate Actions

### ✅ Action 1: Lock Down P2 (Freeze New Leakage)
Add architecture tests to prevent:
- New `allowsAction()` call sites
- New hardcoded state string comparisons
- New direct state-based permission checks

### ✅ Action 2: Telemetry Instrumentation (Step 4 Prep)
Add deprecation warnings to:
- `Election::allowsAction()` calls
- `ElectionStateMachine::allowsAction()` calls
- Direct state equality comparisons in permissions

### ✅ Action 3: Document State Machine Purity
Clarify (in ElectionStateMachine and Election docs):
- ✅ state machine CAN manage transitions
- ❌ state machine CANNOT derive permissions
- All permission queries must go through ElectionLifecycle facade

---

## Next Steps

**Step 0 Complete.** Authority-path audit delivered.

**Ready for Step 1:** Anti-leak architecture guards (P2)  
**Key Output:** Tests that forbid new sovereignty leakage patterns

Execute? **[Y/N]**

