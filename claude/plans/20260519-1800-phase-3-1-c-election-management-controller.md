# Phase 3.1.C: ElectionManagementController Migration
**Type:** Constitutional Breach Fix — Manual Lifecycle Logic Extraction  
**Scope:** Fix 3 violation types in ElectionManagementController  
**Date:** 2026-05-19  
**Complexity:** HIGH (architectural refactor, not simple field replacement)

---

## Violations Summary

| Line | Type | Current | Required Fix | Complexity |
|------|------|---------|-------------|------------|
| 183, 187 | Status checks | `if ($election->status === 'active\|completed')` | Use facade methods: `canActivate()`, `canDeactivate()` | MEDIUM |
| 1130 | Timing check | `if ($election->voting_starts_at && now()->gte(...))` | Use `ElectionClockService::hasVotingStarted($election)` | HIGH |
| 56 | Query filter | `where('status', $request->status)` | Translate to `where('state', ...)` or remove | LOW |

---

## Violation Details

### Violation 1: Manual Status Checks (Lines 183, 187)

**Context:** `activate()` method checks election status before allowing activation.

```php
// CURRENT (WRONG):
public function activate(Election $election): RedirectResponse
{
    if ($election->status === 'active') {
        return back()->with('error', 'Cannot activate an election that is already active.');
    }
    if ($election->status === 'completed') {
        return back()->with('error', 'Cannot activate an election that is already completed.');
    }
    
    Election::withoutGlobalScopes()
        ->where('id', $election->id)
        ->update([
            'status'            => 'active',
            'results_published' => false,
        ]);
}
```

**Problem:** 
- Direct `$election->status` comparison interprets lifecycle rules in controller
- Updates `status` field directly instead of going through state machine
- No validation through ElectionConstitution

**Fix Strategy:** 
1. Check if ElectionLifecycle has `canActivate()` method (or needs one)
2. If lifecycle method doesn't exist: Create `ElectionLifecycle::canActivate()` that consults constitution
3. Replace direct status checks with facade check
4. Replace direct update with `ElectionLifecycle::of($election)->transitionTo('voting')` or similar through a handler

**Test Plan:**
- Test: cannot activate already-active election
- Test: cannot activate completed election
- Test: can activate planned election
- Test: transition goes through constitution validation

---

### Violation 2: Manual Timing Check (Line 1130)

**Context:** `updateVotingDates()` prevents voting date changes after voting has started.

```php
// CURRENT (WRONG):
public function updateVotingDates(Request $request, ..., Election $election): RedirectResponse
{
    // Guard: cannot change voting dates after voting has started
    if ($election->voting_starts_at && now()->gte($election->voting_starts_at)) {
        return back()->withErrors(['error' => 'Cannot modify voting dates after voting has started.']);
    }
    
    $election->update([
        'voting_starts_at' => $validated['start'],
        'voting_ends_at'   => $validated['end'],
    ]);
}
```

**Problem:**
- Uses raw `now()` without timezone context
- Compares directly with `$election->voting_starts_at` without clock service
- No respect for election timezone
- Doesn't use ElectionClockService abstraction

**Fix Strategy:**
1. Replace `now()->gte($election->voting_starts_at)` with `ElectionClockService::hasVotingStarted($election)`
2. ElectionClockService method needs to:
   - Get election timezone (from `ElectionTimezoneResolver` if it exists)
   - Get current time in that timezone
   - Compare correctly
3. Consider: Should this be in a handler that validates through constitution?

**Implementation:**
```php
// PROPOSED:
$clockService = app(ElectionClockService::class);
if ($clockService->hasVotingStarted($election)) {
    return back()->withErrors(['error' => 'Cannot modify voting dates after voting has started.']);
}

$election->update([
    'voting_starts_at' => $validated['start'],
    'voting_ends_at'   => $validated['end'],
]);
```

**Test Plan:**
- Test: can change dates before voting starts
- Test: cannot change dates after voting started
- Test: respects election timezone
- Test: date validation doesn't use raw now()

---

### Violation 3: Query Filter on Deprecated Field (Line 56)

**Context:** Elections index filters by deprecated `status` field.

```php
// CURRENT (QUESTIONABLE):
public function index(...)
{
    // ...
    $query->where('status', $request->status);  // Deprecated field
}
```

**Problem:**
- Queries deprecated `status` field instead of `state`
- But removing it entirely would break admin UI for filtering

**Options:**
1. **Option A (BEST):** Translate filter to `state` field
   - `status='active'` → `state='voting'`
   - `status='completed'` → `state='results'`
   - Cleaner, SSOT-aligned

2. **Option B (TEMPORARY):** Keep filter but add deprecation notice
   - Mark for future removal
   - Works but not architecturally clean

3. **Option C (DEFER):** Leave alone, filter is read-only
   - Lowest risk
   - Can address in Phase 4 (legacy field removal)

**Recommendation:** Option A (translate to state field)

---

## Implementation Plan

### Step 1: Add Missing ElectionLifecycle Methods (if needed)

**Check:** Does `ElectionLifecycle` have these methods?
- `canActivate()` 
- `canDeactivate()`
- `canUpdateVotingDates()`

If not, add them to the facade.

**File:** `app/Application/Election/Facades/ElectionLifecycle.php`

**Implementation:**
```php
public function canActivate(): bool
{
    return $this->constitution->allows('activate', ElectionMode::of($this->election));
}
```

---

### Step 2: Fix Line 183-187 (Status Checks in activate())

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

**Change:**
```php
// FROM:
if ($election->status === 'active') { ... }
if ($election->status === 'completed') { ... }

// TO:
$lifecycle = ElectionLifecycle::of($election);
if (!$lifecycle->canActivate()) {
    return back()->with('error', 'Election cannot be activated in its current state: ' . $lifecycle->blockedReason());
}
```

**Commit Message:** `fix: ElectionManagementController.activate uses ElectionLifecycle facade instead of status field`

---

### Step 3: Fix Line 1130 (Timing Check)

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

**Change:**
```php
// FROM:
if ($election->voting_starts_at && now()->gte($election->voting_starts_at)) { ... }

// TO:
$clockService = app(ElectionClockService::class);
if ($clockService->hasVotingStarted($election)) {
    return back()->withErrors(['error' => 'Cannot modify voting dates after voting has started.']);
}
```

**Assumptions:**
- `ElectionClockService` exists and has `hasVotingStarted()` method
- If not, need to create it

**Commit Message:** `fix: ElectionManagementController.updateVotingDates uses ElectionClockService instead of raw now()`

---

### Step 4: Fix Line 56 (Query Filter)

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

**Change:**
```php
// FROM:
$query->where('status', $request->status);

// TO:
if ($request->status) {
    // Map deprecated status values to state values
    $stateMap = [
        'active' => 'voting',
        'completed' => 'results',
        'planned' => 'administration',
    ];
    $state = $stateMap[$request->status] ?? null;
    if ($state) {
        $query->where('state', $state);
    }
}
```

**Commit Message:** `fix: ElectionManagementController filters by state field instead of deprecated status`

---

## Test Plan

### Unit Tests: `ElectionManagementControllerConstitutionalTest.php`

```php
test('activate requires election to not already be active')
test('activate requires election to not be completed')
test('activate uses ElectionLifecycle facade')
test('updateVotingDates prevents changes after voting started')
test('updateVotingDates uses ElectionClockService')
test('updateVotingDates respects election timezone')
test('index filters by state field not deprecated status')
```

### Integration Tests
- Verify state transitions through `activate()` go through constitution
- Verify clock service is called with correct timezone context
- Verify filter works for all mapped states

---

## Risks & Mitigations

| Risk | Mitigation |
|------|-----------|
| `activate()` breaks if ElectionLifecycle.canActivate() not implemented | Implement method before fixing controller |
| `ElectionClockService.hasVotingStarted()` doesn't exist | Create it or use existing method |
| State mapping changes break existing filters | Map all currently-used status values |
| Admin UI relies on specific state values | Test UI with mapped values |

---

## Execution Checklist

- [ ] Check ElectionLifecycle has required methods (canActivate, etc)
- [ ] Check ElectionClockService exists and has hasVotingStarted()
- [ ] Write RED tests for all three violations
- [ ] Fix line 183-187 (activate method)
- [ ] Fix line 1130 (updateVotingDates timing)
- [ ] Fix line 56 (index query filter)
- [ ] All tests GREEN
- [ ] Verify no existing tests broken
- [ ] Commit with constitutional narrative

---

## Next Steps After Completion

1. Run full test suite to verify no regressions
2. Add to constitutional CI group (alongside Phase 3.1.B tests)
3. Move to Phase 3.2 preparation (Strict Mode enablement)
4. Update audit document with completion status
