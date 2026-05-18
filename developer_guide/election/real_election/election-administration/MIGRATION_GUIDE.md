# Phase 3 Migration Guide: Controller-by-Controller SSOT Migration

**Comprehensive step-by-step guide for migrating from legacy election status fields to the unified SSOT architecture.**

---

## Overview

Phase 3 consists of two sub-phases:

### Phase 3.1: Parallel Operation (Warning Mode)
- New SSOT paths and old legacy paths run simultaneously
- Deprecation mode: `warning` (logs to `voter_audit` channel)
- All legacy field access is logged but still works
- Tests pass, feature works, visibility is high

### Phase 3.2: Legacy Removal (Strict Mode)
- Only SSOT paths work, legacy paths throw exceptions
- Deprecation mode: `strict` (throws DeprecatedFieldException)
- Any remaining legacy field access fails loudly
- Safe because we've had time to identify all edge cases

---

## Pre-Migration Checklist

Before starting Phase 3.1, verify:

```bash
# All Phase 1-2 tests must be GREEN
php artisan test tests/Unit/Application/Election/ tests/Unit/Domain/Election/ --no-coverage
# Expected: 64 tests passed

# Check that deprecation mode is currently 'warning' (permissive)
# Location: app/Application/Election/Deprecation/DeprecationPolicy.php
# const MODE = 'warning';  ← This should be the case

# Identify all controllers using legacy fields
grep -r "->status\|->is_active" app/Http/Controllers/
```

---

## Controller Migration Pattern

Every controller migration follows the same pattern:

### Step 1: Identify Legacy Field Usage

For example, in `ElectionVotingController`:

```php
// ❌ BEFORE: Legacy field access scattered
public function show($election)
{
    $election = Election::findOrFail($election);
    
    if ($election->status !== 'active' || !$election->is_active) {
        return redirect()->route('home')->withError('Election not active');
    }
    
    return view('election.voting', ['election' => $election]);
}
```

### Step 2: Import Facade

```php
<?php

namespace App\Http\Controllers;

use App\Application\Election\Facades\ElectionLifecycle;  // ← ADD THIS
use App\Models\Election;

class ElectionVotingController extends Controller
{
    // ...
}
```

### Step 3: Replace Field Access with Facade

```php
// ✅ AFTER: Using ElectionLifecycle facade
public function show($election)
{
    $election = Election::findOrFail($election);
    $lifecycle = ElectionLifecycle::of($election);  // ← NEW
    
    if (!$lifecycle->canVote()) {  // ← REPLACES scattered checks
        return redirect()->route('home')
            ->withError('Voting not currently allowed: ' . $lifecycle->blockedReason());
    }
    
    return view('election.voting', [
        'election' => $election,
        'lifecycle' => $lifecycle,  // ← Pass to view for complex logic
    ]);
}
```

### Step 4: Update Views to Use Snapshot

In Blade templates:

```blade
{{-- ❌ BEFORE: Direct field access --}}
@if($election->is_active && $election->status === 'active')
    <button>Vote Now</button>
@endif

{{-- ✅ AFTER: Use lifecycle snapshot --}}
@if($lifecycle->canVote())
    <button>Vote Now</button>
@endif
```

### Step 5: Update Repository Queries

If your controller uses repository queries with legacy fields:

```php
// ❌ BEFORE: Repository query with deprecated field
public function findActiveElections()
{
    return Election::where('is_active', true)
        ->where('status', 'active')
        ->get();
}

// ✅ AFTER: Repository query with guard + proper field
public function findActiveElections()
{
    $this->guard->assertAllowedQuery(
        ['state' => 'voting_active'],
        'ElectionRepository::findActiveElections'
    );
    
    return Election::where('state', 'voting_active')->get();
}
```

### Step 6: Run Tests

```bash
# Run specific controller tests
php artisan test tests/Feature/Election/ElectionVotingControllerTest.php

# Run full suite to catch regressions
php artisan test --no-coverage

# Check voter_audit logs for deprecation warnings
tail -f storage/logs/voter_audit.log
```

### Step 7: Verify No Deprecation Logs

```bash
# Count deprecation warnings (should be 0 after successful migration)
grep -c "Deprecated field" storage/logs/voter_audit.log
# Expected: 0
```

---

## Detailed Migration: VoteController Example

This is the most critical controller—it handles actual vote submission. Here's the complete migration:

### VoteController: Before
```php
<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function submit(Request $request)
    {
        $election = Election::findOrFail($request->election_id);
        
        // ❌ Multiple competing checks
        if (!$election->is_active) {
            return back()->withError('Election not active');
        }
        
        if ($election->status !== 'active') {
            return back()->withError('Election not in voting state');
        }
        
        // ❌ Missing: voting window check (happens in model)
        // ❌ Missing: voter eligibility check (happens elsewhere)
        
        $this->submitVote($election, $request);
        return redirect()->route('election.verify');
    }
}
```

### VoteController: After
```php
<?php

namespace App\Http\Controllers;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function submit(Request $request)
    {
        $election = Election::findOrFail($request->election_id);
        $lifecycle = ElectionLifecycle::of($election);
        
        // ✅ Single unified check
        if (!$lifecycle->canVote()) {
            return back()->withError(
                'Voting not allowed: ' . $lifecycle->blockedReason()
            );
        }
        
        // ✅ All checks in one place: state + clock + window + eligibility
        $this->submitVote($election, $request);
        return redirect()->route('election.verify');
    }
}
```

**What Changed:**
- Removed duplicate `is_active` and `status` checks
- Single `canVote()` check replaces all scattered logic
- Error message includes reason from snapshot
- Voting window and eligibility are now guaranteed in snapshot

---

## Migration Checklist: All Controllers

Use this checklist to track Phase 3.1 progress:

### High Priority (Most Critical)
```
[ ] VoteController
    - Location: app/Http/Controllers/VoteController.php
    - Legacy fields: is_active, status
    - Impact: Actual vote submission
    - Tests: tests/Feature/VoteSubmissionTest.php

[ ] ElectionVotingController
    - Location: app/Http/Controllers/ElectionVotingController.php
    - Legacy fields: status, is_active
    - Impact: Voting UI rendering
    - Tests: tests/Feature/Election/ElectionVotingControllerTest.php

[ ] ElectionController
    - Location: app/Http/Controllers/ElectionController.php
    - Legacy fields: status, is_active
    - Impact: Election CRUD operations
    - Tests: tests/Feature/ElectionControllerTest.php
```

### Medium Priority
```
[ ] VoterSlugController
    - Location: app/Http/Controllers/VoterSlugController.php
    - Legacy fields: is_active, status
    - Impact: Voter registration flow

[ ] ElectionResultsController
    - Location: app/Http/Controllers/ElectionResultsController.php
    - Legacy fields: status (checking if results can be published)
    - Impact: Results publication
```

### Low Priority (Rarely Touched)
```
[ ] AdminElectionController
    - Legacy fields: status
    - Impact: Admin-only operations

[ ] ReportController
    - Legacy fields: status (for filtering elections)
    - Impact: Reporting/analytics
```

---

## Testing Strategy During Phase 3.1

### Test Before Migration
```bash
# Run tests to establish baseline
php artisan test tests/Feature/Election/ --no-coverage
# Record: ____ tests passed
```

### Test After Each Controller Migration
```bash
# Run specific controller's test suite
php artisan test tests/Feature/VoteSubmissionTest.php

# Run full suite to catch regressions
php artisan test --no-coverage

# Check that no new deprecation warnings appeared
tail -20 storage/logs/voter_audit.log
```

### Final Phase 3.1 Verification
```bash
# All tests still pass
php artisan test --no-coverage

# No deprecation warnings in voter_audit log
wc -l storage/logs/voter_audit.log  # Should be minimal

# Manual testing: Create election, vote, verify results
# (Instructions below)
```

---

## Manual Testing Checklist

For each major controller migration, perform these manual tests:

### Election Creation Flow
```
1. Log in as admin
2. Navigate to Create Election
3. Fill in election details
4. Click Create
   ✓ Election created successfully
   ✓ Redirected to election detail page
   ✓ Status shows correctly (e.g., "Draft Setup")
5. View voter audit log
   ✓ No deprecation warnings logged
```

### Voting Flow
```
1. Log in as voter
2. Navigate to voting election
3. Enter voting code
4. Select candidates
5. Review selections
6. Submit vote
   ✓ Vote submitted successfully
   ✓ Verification page displays
7. View voter audit log
   ✓ No deprecation warnings logged
   ✓ All steps recorded with timestamps
```

### Results Publication
```
1. Log in as election admin
2. Navigate to election that's ready for results
3. Click "Publish Results"
   ✓ Results published successfully
   ✓ Voter-facing view shows results
4. View voter audit log
   ✓ No deprecation warnings logged
```

---

## Deprecation Mode Transition

### Phase 3.1: Warning Mode (Current)
```php
// app/Application/Election/Deprecation/DeprecationPolicy.php
const MODE = 'warning';  // ← THIS VALUE
```

**Behavior:**
- Legacy field access is logged
- Features still work
- No exceptions thrown
- Logs go to `storage/logs/voter_audit.log`

### Phase 3.2: Strict Mode (After All Controllers Migrated)
```php
// app/Application/Election/Deprecation/DeprecationPolicy.php
const MODE = 'strict';  // ← CHANGE TO THIS
```

**Behavior:**
- Legacy field access throws DeprecatedFieldException
- Any missed migration becomes immediately visible
- Safe because we've had time to find all edge cases

**Transition Procedure:**
1. Complete all controller migrations
2. Run full test suite (all pass)
3. Monitor voter_audit log for 24-48 hours (zero deprecation logs)
4. Verify no production issues from Phase 3.1 deployment
5. Change DeprecationPolicy::MODE to 'strict'
6. Deploy Phase 3.2
7. Monitor closely for exceptions (should be zero)

---

## Common Migration Issues

### Issue 1: "Call to undefined method setupAsReadyForVoting()"

**Cause:** Using factory methods that don't exist in tests.

**Solution:** Use direct state setting instead:
```php
// ❌ WRONG
$election = Election::factory()->create()->setupAsReadyForVoting();

// ✅ CORRECT
$election = Election::factory()->create([
    'state' => 'ready_for_voting',
    'voting_starts_at' => now()->subHour(),
    'voting_ends_at' => now()->addHour(),
]);
```

### Issue 2: "Deprecated field 'status' used in query"

**Cause:** Repository query still uses legacy field.

**Solution:** Add QueryPolicyGuard and use new field:
```php
public function findByState($state)
{
    $this->guard->assertAllowedQuery(['state' => $state], __METHOD__);
    return Election::where('state', $state)->get();
}
```

### Issue 3: "ElectionLifecycle not found" in views

**Cause:** Forgot to pass lifecycle to view.

**Solution:** Pass lifecycle in all controller returns:
```php
return view('election.voting', [
    'election' => $election,
    'lifecycle' => ElectionLifecycle::of($election),  // ← ADD THIS
]);
```

---

## Rollback Plan (If Needed)

If Phase 3.1 reveals critical issues:

1. **Revert the failing controller** to legacy code
2. **Keep passing controllers** migrated
3. **Run tests** to ensure stability
4. **Investigate issue** with architecture team
5. **Fix root cause** before re-attempting migration

**Partial rollback is safe** because:
- Legacy and new code coexist in warning mode
- Each controller is independent
- Tests verify each migration in isolation

---

## Performance Considerations

### SSOT Computation Cost

`ElectionLifecycle::of($election)` computes the snapshot by running the full state derivation logic:

```php
// This checks: state machine → voting window → administration completion → etc.
$lifecycle = ElectionLifecycle::of($election);  // ~1-2ms per call
```

**Optimization for High-Traffic Pages:**

```php
// ✅ GOOD: Compute once, use multiple times
$lifecycle = ElectionLifecycle::of($election);

if ($lifecycle->canVote()) { }
if ($lifecycle->canEdit()) { }
if ($lifecycle->isTerminal()) { }
// Single computation, three uses

// ❌ BAD: Recompute unnecessarily
if (ElectionLifecycle::of($election)->canVote()) { }
if (ElectionLifecycle::of($election)->canEdit()) { }
if (ElectionLifecycle::of($election)->isTerminal()) { }
// Three computations, same information
```

**For Batch Operations:**

```php
// ✅ CORRECT: Use pre-computed snapshot factory
$elections = Election::where('state', 'voting_active')->get();
$snapshots = $elections->map(fn($e) => ElectionLifecycle::of($e)->snapshot());

foreach ($elections as $index => $election) {
    $lifecycle = ElectionLifecycle::withSnapshot($election, $snapshots[$index]);
    // Use pre-computed snapshot, no recomputation
}
```

---

## Definition of Done (Phase 3.1)

Phase 3.1 is complete when:

- [ ] All controllers identified and migrated
- [ ] All 64 Phase 1-2 tests still pass
- [ ] All new controller tests pass
- [ ] Full test suite passes: `php artisan test --no-coverage`
- [ ] Zero deprecation logs in voter_audit.log (after 48 hours production)
- [ ] Manual testing checklist completed for each migration
- [ ] No critical issues reported in production
- [ ] Code review approved by architecture team
- [ ] Documentation updated with any new patterns discovered

**Then:** Proceed to Phase 3.2 (strict mode)

---

## Definition of Done (Phase 3.2)

Phase 3.2 is complete when:

- [ ] DeprecationPolicy::MODE changed to 'strict'
- [ ] All tests pass with strict mode
- [ ] No DeprecatedFieldException thrown in production
- [ ] Legacy paths fully removed from codebase
- [ ] Final regression testing completed
- [ ] Performance verified (no degradation)
- [ ] Documentation updated with lessons learned

**Then:** Phase 3 complete, SSOT architecture fully operational

---

## Next Steps

1. **Start Phase 3.1 with VoteController** (most critical)
2. **Follow the pattern** for each controller
3. **Run tests after each migration**
4. **Monitor deprecation logs** for unexpected usage
5. **Proceed incrementally** rather than trying to do all at once

**Expected Timeline:**
- Phase 3.1: 2-3 days (5-10 controllers, testing included)
- Phase 3.2: 1 day (mode change, final verification)
- **Total Phase 3: 3-4 days**

See [PATTERNS.md](PATTERNS.md) for more code examples and [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for common issues.
