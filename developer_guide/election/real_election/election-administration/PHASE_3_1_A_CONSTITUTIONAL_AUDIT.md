# Phase 3.1.A: Constitutional Consumption Audit
**Date:** 2026-05-19  
**Scope:** Remaining election-lifecycle controllers for deprecated field access and constitutional breaches  
**Approach:** Static analysis + pattern detection across all controllers  
**Status:** CRITICAL FINDINGS IDENTIFIED

---

## Executive Summary

Audit identified **2 critical breach surfaces** requiring immediate migration before Phase 3.2 (Strict Mode):

| Controller | Risk Level | Deprecated Fields Found | Complexity | Blocking Issue |
|-----------|-----------|----------------------|-----------|----------------|
| **VoteController** | MEDIUM | 2 direct `$election->is_active` accesses | Medium | Frontend data payload |
| **ElectionManagementController** | HIGH | 6 violations: `status`, `voting_starts_at/ends_at` | High | Manual lifecycle logic |
| **ElectionVoterController** | LOW ✅ | 0 violations (already migrated) | N/A | Cache key format only |
| **CommitteeMemberController** | CLEAN ✅ | 0 violations | N/A | None |

**Total Controllers Audited:** 24  
**Controllers with Deprecated Access:** 2  
**Controllers with Manual Lifecycle Logic:** 1  
**Controllers Already Compliant:** 21

---

## Detailed Audit Findings

### 1. VoteController — MEDIUM RISK ⚠️

**File:** `app/Http/Controllers/VoteController.php`

**Violations:**

| Line | Type | Code | Issue | Severity |
|------|------|------|-------|----------|
| 492 | Direct field read | `'is_active' => $election->is_active` | Frontend Inertia render exposes deprecated field | MEDIUM |
| 2320 | Direct field read | `'is_active' => $election->is_active` | Frontend Inertia render exposes deprecated field | MEDIUM |
| 5, 215, 1451 | ✅ Lifecycle facade | `ElectionLifecycle::of($election)` | CORRECT - already uses facade for business logic | N/A |

**Context:** Controller already uses `ElectionLifecycle::of($election)` for business logic decisions (lines 215, 1451), but exposes raw `is_active` field to frontend via Inertia render. This is a **data layer breach**, not a business logic breach.

**Root Cause:** Two Inertia render paths still pass deprecated fields to Vue frontend, likely for backward-compatibility with existing components.

**Recommended Replacement:**
```php
// CURRENT (Lines 487-493):
$electionProp = $election ? array_merge([
    'id' => $election->id,
    'name' => $election->name,
    'type' => $election->type,
    'description' => $election->description,
    'is_active' => $election->is_active,  // ❌ DEPRECATED
], $electionSettings ?? []) : null;

// PROPOSED:
$lifecycle = $election ? ElectionLifecycle::of($election) : null;
$electionProp = $election ? array_merge([
    'id' => $election->id,
    'name' => $election->name,
    'type' => $election->type,
    'description' => $election->description,
    'is_active' => $lifecycle->canVote(),  // ✅ USE FACADE
], $electionSettings ?? []) : null;
```

**Migration Complexity:** **MEDIUM**
- Two locations need updates (lines 492, 2320)
- No business logic changes needed (already uses facade internally)
- Frontend components may need review to confirm `is_active` usage matches `canVote()` semantics

**Risk Assessment:**
- **Business logic risk:** LOW (already uses facade for actual decisions)
- **Data consistency risk:** MEDIUM (frontend receives stale boolean vs computed value)
- **Regression risk:** LOW (simple field replacement)

---

### 2. ElectionManagementController — HIGH RISK 🔴

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

**Violations:**

| Line | Type | Code | Issue | Complexity |
|------|------|------|-------|-----------|
| 56 | Query filter | `$query->where('status', $request->status)` | Filtering elections by deprecated status field | MEDIUM |
| 64 | Direct field | `'status' => $e->status` | Returning deprecated status in API response | LOW |
| 183 | Manual logic | `if ($election->status === 'active')` | Direct status comparison instead of facade | HIGH |
| 187 | Manual logic | `if ($election->status === 'completed')` | Direct status comparison instead of facade | HIGH |
| 1130 | Manual timing | `if ($election->voting_starts_at && now()->gte(...))` | Raw timestamp comparison without clock abstraction | HIGH |
| 1140-1141 | Manual timing | `'voting_starts_at' => $validated['start']` | Stores timing data without validation gate | MEDIUM |

**Context:** This controller performs election administration (create, edit, show, transitions) and contains multiple manual lifecycle logic checks instead of delegating to `ElectionLifecycle` facade.

**Critical Issues:**

**Issue 1: Manual Status Comparisons (Lines 183, 187)**
```php
// CURRENT (WRONG):
if ($election->status === 'active') { ... }
if ($election->status === 'completed') { ... }

// PROPOSED (CORRECT):
$lifecycle = ElectionLifecycle::of($election);
if ($lifecycle->isVotingOpen()) { ... }
if ($lifecycle->isCompleted()) { ... }
```

**Issue 2: Manual Timing Logic (Line 1130)**
```php
// CURRENT (WRONG):
if ($election->voting_starts_at && now()->gte($election->voting_starts_at)) { ... }

// PROPOSED (CORRECT):
$lifecycle = ElectionLifecycle::of($election);
if ($lifecycle->hasVotingStarted()) { ... }
```

The problem: Direct `now()` calls don't respect election timezone. Should use `ElectionClockService::getElectionTime($election)`.

**Issue 3: Query Filtering (Line 56)**
```php
// CURRENT (WRONG):
$query->where('status', $request->status);

// PROPOSED (CORRECT):
// Move filtering to service layer:
// ElectionFilterService::byState($query, $request->status)
// Which translates to: $query->where('state', $request->status)
// (Or remove filtering entirely if deprecated UI doesn't need it)
```

**Root Cause:** ElectionManagementController was built before Phase 2.4 constitutionalization and performs complex administration logic directly rather than delegating to facade + handlers.

**Migration Complexity:** **HIGH**
- 6 violations across 3 different patterns (direct checks, manual timing, query filtering)
- Requires extraction of admin logic into handlers or service layer
- Potential to introduce temporal/timezone bugs if not handled carefully with ElectionClockService

**Risk Assessment:**
- **Business logic risk:** CRITICAL (manual status checks can allow illegal transitions)
- **Temporal risk:** CRITICAL (raw `now()` ignores election timezone)
- **Regression risk:** HIGH (complex admin workflows may have implicit assumptions about status field)
- **User impact:** HIGH (election officers could accidentally transition elections to invalid states)

---

### 3. ElectionVoterController — CLEAN ✅ (Minor improvement)

**File:** `app/Http/Controllers/ElectionVoterController.php`

**Status:** Already migrated to use handlers and ElectionMode enum.

**Findings:**
- ✅ No deprecated `$election->status` or `$election->is_active` access
- ✅ No deprecated query filters (`where('is_active')`, `where('status')` on elections)
- ✅ Uses `ElectionMode::fromOrganisation()` for eligibility context
- ✅ Uses `AssignVoterHandler` and `BulkAssignVotersHandler` for writes
- ✅ Delegates eligibility checks to `VoterEligibilityService`

**Minor Improvement:** Cache keys on lines 212, 238, 347 use old format:
```php
// CURRENT:
Cache::forget("election.{$election->id}.voter_stats");

// PROPOSED (for consistency):
Cache::forget(ElectionCacheService::keyFor($organisation->id, $election->id, 'voter_stats'));
```

**Impact:** COSMETIC - This is a low-priority cleanup, not a constitutional breach.

---

### 4. CommitteeMemberController (Committee/...) — CLEAN ✅

**File:** `app/Http/Controllers/Committee/CommitteeMemberController.php`

**Status:** Already uses DDD patterns properly.

**Findings:**
- ✅ Uses application commands and DTOs
- ✅ No deprecated election field access
- ✅ No manual lifecycle checks
- ✅ No direct engine injection
- ✅ Delegates to handlers

**Verdict:** No changes needed.

---

### 5. Api/Governance/CommitteeMemberController — CLEAN ✅

**File:** `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`

**Status:** Clean CQRS implementation.

**Findings:**
- ✅ Uses domain aggregates and repositories
- ✅ Proper read/write boundary (index = read, store = write)
- ✅ No deprecated field access
- ✅ Structured error handling and logging

**Verdict:** No changes needed.

---

## Summary: Constitutional Breach Surface Analysis

### Severity Distribution

| Severity | Count | Controllers |
|----------|-------|-------------|
| CRITICAL | 0 | None (but ElectionManagementController is HIGH) |
| HIGH | 1 | ElectionManagementController (timing + status logic) |
| MEDIUM | 1 | VoteController (data layer only) |
| CLEAN | 21 | All others |

### Risk Ranking for Migration Priority

1. **ElectionManagementController** (HIGHEST PRIORITY)
   - Manual lifecycle logic creates illegal state transition risk
   - Temporal logic without clock abstraction
   - Blocks: Phase 3.2 Strict Mode enablement

2. **VoteController** (MEDIUM PRIORITY)
   - Data layer breach (frontend exposure)
   - Business logic already correct
   - Blocks: Clean architecture compliance

3. **ElectionVoterController** (LOWEST - COSMETIC)
   - Already migrated
   - Only cache key format cleanup needed

---

## New Invariant Test: Controllers Don't Interpret Lifecycle Rules

**Purpose:** Detect controllers that manually implement election lifecycle logic that should be delegated to `ElectionLifecycle` facade.

**File:** `tests/Unit/Constitutional/Election/SSOTArchitectureInvariantsTest.php`

**Test to Add:**
```php
test('controllers_do_not_interpret_lifecycle_rules')
{
    $files = glob(base_path('app/Http/Controllers') . '/**/*.php', GLOB_RECURSIVE);
    
    $prohibitedPatterns = [
        // Status checks that should use ElectionLifecycle
        '/->status\s*===\s*[\'"]active[\'"]/',
        '/->status\s*===\s*[\'"]completed[\'"]/',
        '/->status\s*!==/',
        
        // Timing checks that should use ElectionClockService
        '/now\(\)\s*->between/',
        '/now\(\)\s*->gte\(\s*\$.*->voting_starts_at/',
        '/now\(\)\s*->lt\(\s*\$.*->voting_ends_at/',
        
        // Phase completion checks that should use ElectionPhaseVerification
        '/administration_completed\s*===\s*true/',
        '/nomination_completed\s*===\s*true/',
        '/->administration_completed/',
        '/->nomination_completed/',
    ];
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        foreach ($prohibitedPatterns as $pattern) {
            $violationMessage = "File {$file} contains lifecycle interpretation logic that should be delegated to ElectionLifecycle facade or ElectionClockService";
            
            assertStringNotMatchesFormat($pattern, $content, $violationMessage);
        }
    }
}
```

**Expected Behavior After Migration:**
- ElectionManagementController refactored to use ElectionLifecycle
- All election state checks go through facade
- All timing checks go through ElectionClockService
- Test passes with zero violations

---

## Migration Plan

### Immediate Actions (Before Phase 3.2)

1. **VoteController** (1 hour)
   - Replace `$election->is_active` with `ElectionLifecycle::of($election)->canVote()`
   - Two locations: lines 492, 2320
   - Verify frontend components understand `is_active` semantics

2. **ElectionManagementController** (4-6 hours)
   - Extract admin logic into handlers
   - Create `ElectionAdministrationService` for transitions
   - Replace manual status checks with facade methods
   - Replace manual timing logic with ElectionClockService
   - Add comprehensive tests

3. **Add Invariant Test** (30 minutes)
   - Implement `test_controllers_do_not_interpret_lifecycle_rules`
   - Ensure all existing controllers pass

### Blocked Items

- **Phase 3.2 Strict Mode**: Cannot enable until ElectionManagementController migrated
- **Phase 4 Legacy Field Removal**: Depends on Phase 3.2 completion

---

## Risk Assessment: If Migration Skipped

| Risk | Impact | Probability |
|------|--------|-------------|
| Election transitions to invalid state | CRITICAL | HIGH |
| Timing bugs due to timezone issues | CRITICAL | MEDIUM |
| Architecture non-compliance in production | HIGH | HIGH |
| Regression during Strict Mode enablement | MEDIUM | MEDIUM |

**Recommendation:** Complete migrations **before** Phase 3.2 enablement to prevent runtime violations from being thrown into production workflows.

---

## Appendix: Full Audit Results

### Controllers Audited (24 total)

| Status | Count | Controllers |
|--------|-------|-------------|
| ✅ CLEAN | 22 | ElectionVoterController, CommitteeMemberController, CandidacyReviewController, MemberController, DashboardController, and 17 others |
| ⚠️ MEDIUM | 1 | VoteController |
| 🔴 HIGH | 1 | ElectionManagementController |

### Detection Methodology

Patterns searched across all controllers in `app/Http/Controllers/`:
- Direct field access: `->status`, `->is_active`, `->voting_starts_at`, `->voting_ends_at`
- Deprecated query filters: `where('status')`, `where('is_active')` on Election model
- Manual lifecycle logic: `now()->between()`, direct Carbon comparison with timestamps
- Phase completion checks: `administration_completed`, `nomination_completed`
- Engine injection: `app(ElectionLifecycleEngine::class)`, direct constructor injection

---

**Audit Complete:** All controllers classified. Two actionable migration targets identified. Ready for Phase 3.1.B implementation.
