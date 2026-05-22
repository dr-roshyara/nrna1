# Migration Guide — Old System to SSOT

## Overview

This guide helps you migrate code from the old mutable-state system to the new Single Source of Truth (SSOT) architecture.

**Migration Timeline:**
- ✅ **Phase 3.1.A-D (Completed):** Controllers migrated to SSOT
- ✅ **Phase 3.1.E (Completed):** Test suite stabilized
- ✅ **Phase 3.1.F (Current):** Documentation & remaining cleanups
- 📋 **Phase 3.2 (Pending):** Strict mode activation

---

## What Changed at a Glance

| Aspect | Old | New |
|--------|-----|-----|
| State Source | Database column `state` | Computed from constitutional facts |
| Authorization | Manual checks | `ConstitutionalTransitionGuard` |
| Transitions | Direct DB writes | `transitionTo()` with validation |
| Tests | Set state directly | Set facts, verify derivation |
| State Names | 7 names (`draft`, `voting`, etc.) | 10 SSOT names (`voting_active`, etc.) |

---

## Migration Checklist

### For Controllers

- [ ] Replace `$election->status` with `ElectionLifecycle::of($election)->state()->value`
- [ ] Replace `Election::where('status', '...')` with in-memory state derivation
- [ ] Replace `$election->update(['state' => '...'])` with `transitionTo()`
- [ ] Replace `authorize('manage')` with `authorize('manageSettings')`
- [ ] Verify transition error handling with try-catch blocks

### For Tests

- [ ] Replace `ElectionScenarioFactory::state('voting')` patterns with fact-based factories
- [ ] Use `ElectionScenarioFactory` helpers instead of direct state setting
- [ ] Verify state column is synced after setting facts
- [ ] Remove all `$election->state = '...'` assignments
- [ ] Add Carbon::setTestNow() for deterministic time-based tests

### For Policies

- [ ] Replace hardcoded state checks with `ElectionLifecycle` capability methods
- [ ] Use `canEdit()`, `canVote()`, `canPublishResults()` instead of state comparisons
- [ ] Verify authorization still works with new state names

### For Views & Components

- [ ] Replace `election.status` references with `stateMachine.currentState`
- [ ] Replace `election.is_active` with `stateMachine.canVote` or similar capability
- [ ] Update state display to use new 10 state names
- [ ] Use `stateMachine.allowedActions` for conditional button visibility

### For Queries

- [ ] Remove SQL filters on `status` column
- [ ] Load elections, then filter by derived state in PHP
- [ ] Or use `ElectionClockService` for time-based filtering

---

## Step-by-Step Migration

### Step 1: Update Controllers

**OLD:**
```php
public function dashboard() {
    $activeElections = Election::where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->get();
}
```

**NEW:**
```php
public function dashboard() {
    $elections = Election::all();
    $activeElections = $elections->filter(
        fn($e) => ElectionLifecycle::of($e)->state()->value === 'voting_active'
    )->values();
}
```

---

**OLD:**
```php
public function openVoting(Election $election) {
    if ($election->status !== 'administration') {
        abort(403, 'Wrong state');
    }
    
    $election->update([
        'status' => 'voting',
        'voting_locked' => true
    ]);
}
```

**NEW:**
```php
public function openVoting(Election $election): RedirectResponse {
    $this->authorize('manageSettings', $election);
    
    try {
        $election->transitionTo(
            Transition::manual('open_voting', auth()->id(), 'Opened voting')
        );
        return back()->with('success', 'Voting opened');
    } catch (InvalidTransitionException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

---

### Step 2: Update Tests

**OLD:**
```php
public function test_voting_accepted() {
    $election = Election::factory()->create(['status' => 'voting']);
    
    // Test voting...
}
```

**NEW:**
```php
public function test_voting_accepted() {
    $election = ElectionScenarioFactory::votingActive($org);
    
    // Facts are set, state is verified
    $this->assertEquals('voting_active', 
        ElectionLifecycle::of($election)->state()->value);
    
    // Test voting...
}
```

---

**OLD:**
```php
public function test_state_transition() {
    $election = Election::factory()->create();
    $election->state = 'setup';  // ❌ Wrong!
    $election->save();
    
    // Test...
}
```

**NEW:**
```php
public function test_state_transition() {
    $election = Election::factory()->create([
        'approved_at' => now()->subDay(),
        // Other facts...
    ]);
    
    $state = ElectionLifecycle::of($election)->state();
    $election->update(['state' => $state->value]); // ✅ Sync after deriving
    
    // Test...
}
```

---

### Step 3: Update State Checks in Logic

**OLD:**
```php
if ($election->status === 'voting') {
    // Accept votes
}
```

**NEW:**
```php
if (ElectionLifecycle::of($election)->state()->value === 'voting_active') {
    // Accept votes
}
```

Or better yet:

```php
if (ElectionLifecycle::of($election)->canVote()) {
    // Accept votes
}
```

---

### Step 4: Update Policies

**OLD:**
```php
public function manageSettings(User $user, Election $election): bool {
    $isOfficer = ElectionOfficer::where([
        'user_id' => $user->id,
        'election_id' => $election->id,
    ])->exists();
    
    return $isOfficer && in_array($election->status, ['draft', 'setup', 'administration']);
}
```

**NEW:**
```php
public function manageSettings(User $user, Election $election): bool {
    $isOfficer = ElectionOfficer::where([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'status' => 'active',
    ])->exists();
    
    return $isOfficer && ElectionLifecycle::of($election)->canEdit();
}
```

---

### Step 5: Update Vue Components

**OLD:**
```vue
<template>
  <button v-if="election.status === 'ready_for_voting'" @click="open">
    Open Voting
  </button>
  <button v-if="election.is_active">
    Show Voting
  </button>
</template>
```

**NEW:**
```vue
<template>
  <button 
    v-if="stateMachine.allowedActions.includes('open_voting')"
    @click="open"
  >
    Open Voting
  </button>
  <button v-if="stateMachine.canVote">
    Show Voting
  </button>
</template>

<script setup>
defineProps({
  stateMachine: {
    type: Object,
    required: true
    // Contains: currentState, canVote, allowedActions, etc.
  }
});
</script>
```

---

## State Name Mapping

**Old → New:**

| Old | New |
|-----|-----|
| `draft` | `draft` |
| `pending_approval` | `submitted_for_approval` |
| (no equivalent) | `approved` |
| (no equivalent) | `rejected` |
| `administration` | `setup` |
| `nomination` | (part of `setup`) |
| (no equivalent) | `ready_for_voting` |
| `voting` | `voting_active` |
| `results_pending` | `counting` |
| `results` | `results_published` |

**Important:** Old state names are DEPRECATED. Update all references to use new SSOT names.

---

## Common Migration Patterns

### Pattern 1: Conditional Button Display

**OLD:**
```php
// In controller
$data['can_open_voting'] = $election->status === 'administration' 
    && $election->candidates_count > 0
    && $election->pending_candidacies_count === 0;
```

**NEW:**
```php
// In controller
$stateMachine = $this->getStateMachineData($election);

// Then in Vue:
<button v-if="stateMachine.allowedActions.includes('open_voting')">
  Open Voting
</button>
```

---

### Pattern 2: State-Based Processing

**OLD:**
```php
foreach ($elections as $election) {
    switch ($election->status) {
        case 'administration':
            // Process
            break;
        case 'voting':
            // Process
            break;
    }
}
```

**NEW:**
```php
foreach ($elections as $election) {
    $state = ElectionLifecycle::of($election)->state()->value;
    
    match ($state) {
        'setup' => $this->processSetup($election),
        'voting_active' => $this->processVoting($election),
        default => null,
    };
}
```

---

### Pattern 3: Authorization Checks

**OLD:**
```php
if (!in_array($election->status, ['draft', 'setup'])) {
    abort(403, 'Cannot edit');
}
```

**NEW:**
```php
if (!ElectionLifecycle::of($election)->canEdit()) {
    abort(403, 'Cannot edit');
}
```

---

## Before & After Code Examples

### Example 1: Election Dashboard

**BEFORE:**
```php
class ElectionController extends Controller {
    public function dashboard() {
        $elections = Election::where('organisation_id', session('current_organisation_id'))
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
        
        return Inertia::render('Elections/Dashboard', [
            'elections' => $elections,
            'stats' => [
                'total' => Election::count(),
                'active' => $elections->count(),
            ],
        ]);
    }
}
```

**AFTER:**
```php
class ElectionController extends Controller {
    public function dashboard() {
        $orgId = session('current_organisation_id');
        $elections = Election::where('organisation_id', $orgId)->get();
        
        $activeElections = $elections->filter(
            fn($e) => ElectionLifecycle::of($e)->state()->value === 'voting_active'
        )->values();
        
        return Inertia::render('Elections/Dashboard', [
            'elections' => $activeElections->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'state' => ElectionLifecycle::of($e)->state()->value,
            ]),
            'stats' => [
                'total' => $elections->count(),
                'active' => $activeElections->count(),
            ],
        ]);
    }
}
```

---

### Example 2: State Transition Controller

**BEFORE:**
```php
public function openVoting(Election $election) {
    if ($election->status !== 'administration') {
        return back()->with('error', 'Wrong state for voting');
    }
    
    if (!$election->candidates_count || $election->pending_candidacies_count) {
        return back()->with('error', 'Candidates not ready');
    }
    
    DB::table('elections')
        ->where('id', $election->id)
        ->update([
            'status' => 'voting',
            'voting_locked' => true,
            'voting_locked_at' => now(),
        ]);
    
    return back()->with('success', 'Voting opened');
}
```

**AFTER:**
```php
public function openVoting(Election $election): RedirectResponse {
    $this->authorize('manageSettings', $election);
    
    $lifecycle = ElectionLifecycle::of($election);
    if (!$lifecycle->canTransitionTo('open_voting')) {
        return back()->with('error', $lifecycle->blockedReason() ?? 'Cannot open');
    }
    
    try {
        $election->transitionTo(
            Transition::manual('open_voting', auth()->id(), 'Opened by officer')
        );
        return back()->with('success', 'Voting opened successfully');
    } catch (InvalidTransitionException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

---

## Database Considerations

### The State Column

The `state` column in the `elections` table:

- **Is:** A cache for query optimization
- **Is Not:** The source of truth
- **Should:** Be kept in sync with derived state
- **Can:** Become stale if code writes to it directly

**Best Practice:** Never write to state column directly. Let `transitionTo()` handle it.

```php
// ❌ WRONG
$election->update(['state' => 'voting_active']);

// ✅ RIGHT
$election->transitionTo(Transition::manual('open_voting', ...));
```

### Migration Helpers

If you have stale state columns in existing data:

```php
// Repair stale states
Artisan::call('app:sync-election-states');

// Or manually in tinker:
Election::all()->each(function($e) {
    $derived = ElectionLifecycle::of($e)->state()->value;
    if ($e->state !== $derived) {
        $e->update(['state' => $derived]);
    }
});
```

---

## Testing Migration

### Verify Old Code Doesn't Exist

Search your codebase for:

```bash
# Find old state column reads
grep -r "\$election->state" app/ --include="*.php" | grep -v "ElectionLifecycle"

# Find old status column reads
grep -r "->where('status'" app/ --include="*.php" | grep -v test

# Find direct state assignments
grep -r "update\(\['state'" app/ --include="*.php" | grep -v test

# Find old state names
grep -r "'voting'" app/ --include="*.php" | grep -v "voting_active"
grep -r "'administration'" app/ --include="*.php" | grep -v test
```

### Run Test Suite

```bash
# Run election state machine tests
php artisan test tests/Feature/Election/

# Run feature tests
php artisan test tests/Feature/

# Run all tests
php artisan test
```

### Manual Verification

```bash
# In tinker, verify a few elections
$e = Election::find(1);
$state = ElectionLifecycle::of($e)->state()->value;
echo $state;  // Should be one of 10 SSOT states
```

---

## Rollback (If Needed)

If you need to roll back to old system:

1. Keep the old code in a branch: `git branch old-state-system`
2. The new system is backward-compatible for reads
3. Old tests might still work with ElectionScenarioFactory

**However:** We strongly recommend completing the migration instead of rolling back, as SSOT is more robust.

---

## Team Training

### For Developers

1. **Read:** `01_OVERVIEW.md` (15 min)
2. **Study:** `02_STATE_TRANSITIONS.md` (20 min)
3. **Reference:** `03_DEVELOPER_API.md` (keep bookmarked)
4. **Learn:** `05_CODE_RECIPES.md` (copy examples)
5. **Practice:** Build a test using `ElectionScenarioFactory`

### For QA/Testers

1. **Understand:** State machine has 10 states (not 7)
2. **Know:** Can't set state directly in tests
3. **Use:** ElectionScenarioFactory for consistent test data
4. **Debug:** Use scripts in `04_TROUBLESHOOTING.md`

### For DevOps

1. **No schema changes:** State column already exists
2. **No migrations needed:** SSOT is app-level, not DB-level
3. **Monitoring:** Check for stale state columns
4. **Performance:** State computation is fast, caching optional

---

## Success Criteria

Migration is complete when:

- [ ] No direct `$election->state` reads (except in debug logs)
- [ ] No `Election::where('status', ...)` queries
- [ ] No `update(['state' => ...])` assignments
- [ ] All transitions use `transitionTo()`
- [ ] All tests use fact-based factories
- [ ] All controllers use `ElectionLifecycle` API
- [ ] All policies use `canEdit()`, `canVote()`, etc.
- [ ] All views use `stateMachine` props
- [ ] State column is always in sync
- [ ] Tests pass 100%

---

## Troubleshooting Migration

**Q: Tests fail with "Cannot derive state"**
A: Check constitutional facts are set. Use `ElectionScenarioFactory` instead of manual setup.

**Q: State column becomes stale**
A: Never write to state directly. Use `transitionTo()` or manually sync from engine.

**Q: Authorization fails with new system**
A: Verify user has ElectionOfficer record with correct role and status='active'.

**Q: Old state names still referenced**
A: Use grep to find them, update to new SSOT names (e.g., 'voting' → 'voting_active').

**Q: Performance degradation**
A: State derivation is fast. If slow, verify no N+1 queries. Consider caching for bulk operations.

---

## Phase 3.2: Strict Mode

After migration is complete, Phase 3.2 will activate **Strict Mode**:

```php
// app/Application/Election/Deprecation/DeprecationPolicy.php
const MODE = 'strict'; // Currently 'warning'
```

In strict mode:
- Direct state column reads throw exceptions
- Old API calls fail loudly
- No warnings, just errors

This ensures old code can't accidentally sneak in.

---

**Last Updated:** May 21, 2026
**Migration Status:** Phase 3.1 Complete
**Next Phase:** 3.2 Strict Mode Activation
