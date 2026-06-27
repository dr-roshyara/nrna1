# Troubleshooting Guide

## Quick Diagnosis Steps

When something is wrong with an election, follow this checklist:

1. **Check constitutional facts** — Are the foundation values correct?
2. **Verify derived state** — What does the engine compute?
3. **Compare to column** — Is the cache stale?
4. **Check allowed actions** — What transitions are permitted?
5. **Review error messages** — What is the exact error?

---

## Common Issues & Solutions

### Issue 1: "State Machine Not Responding / 500 Error"

**Symptoms:**
- HTTP 500 error when viewing election
- Error log shows `InvalidTransitionException` or `DomainException`
- Cannot navigate election management page

**Diagnosis:**
```php
// Run these commands in tinker to debug
$election = Election::find($id);

// Step 1: Check constitutional facts
Log::info('Facts:', [
    'approved_at' => $election->approved_at,
    'administration_completed' => $election->administration_completed,
    'nomination_completed' => $election->nomination_completed,
    'voting_starts_at' => $election->voting_starts_at,
    'voting_ends_at' => $election->voting_ends_at,
    'results_published_at' => $election->results_published_at,
]);

// Step 2: Try to derive state
try {
    $lifecycle = ElectionLifecycle::of($election);
    $state = $lifecycle->state();
    Log::info('Derived state OK', ['state' => $state->value]);
} catch (Throwable $e) {
    Log::error('State derivation failed', ['error' => $e->getMessage()]);
}
```

**Solutions:**

**If facts are missing/corrupt:**
```php
// For draft election with no dates:
$election->update([
    'voting_starts_at' => null,
    'voting_ends_at' => null,
    'administration_completed' => false,
    'nomination_completed' => false,
]);

// For election stuck in old state:
$election->update([
    'submitted_at' => now(), // If needs approval
    'approved_at' => now(),  // If approved
]);
```

**If derivation still fails:**
- Check ElectionLifecycleEngineImpl.php for bugs
- Ensure all constitutional fact columns exist in database
- Run migrations: `php artisan migrate`

---

### Issue 2: "No Buttons Showing in Management Page"

**Symptoms:**
- Election management page loads but no action buttons visible
- "Begin Setup", "Open Voting", etc. buttons missing
- No error messages

**Root Cause:** `getAllowedActionsForUser()` returns empty array

**Diagnosis:**
```php
$election = Election::find($id);
$userId = auth()->id();

// Step 1: Check derived state
$state = ElectionLifecycle::of($election)->state()->value;
Log::info('Election state', ['state' => $state]);

// Step 2: Check allowed actions
$actions = ElectionLifecycle::of($election)->allowedActions();
Log::info('Allowed actions', ['actions' => $actions]);

// Step 3: Check user role
$officer = ElectionOfficer::where([
    'election_id' => $election->id,
    'user_id' => $userId,
])->first();
Log::info('Officer record', ['officer' => $officer]);

// Step 4: Check getAllowedActionsForUser directly
$userActions = $election->getAllowedActionsForUser($userId);
Log::info('User allowed actions', ['actions' => $userActions]);
```

**Solutions:**

**If state is wrong:**
- See Issue 1 above (check constitutional facts)

**If actions list is empty but state is correct:**
- Check if user is an ElectionOfficer
  ```php
  $officer = ElectionOfficer::where([
      'election_id' => $election->id,
      'user_id' => auth()->id(),
  ])->first();
  
  if (!$officer) {
      // Create the relationship
      ElectionOfficer::create([
          'election_id' => $election->id,
          'user_id' => auth()->id(),
          'organisation_id' => $election->organisation_id,
          'role' => 'chief',
          'status' => 'active',
      ]);
  }
  ```

**If user role doesn't have permission for state:**
- Check ElectionConstitution.php rules
- Ensure user's role is in `allowed_roles` for the action

---

### Issue 3: "Stale State Column"

**Symptoms:**
- Election shows as "Draft" but voting is happening
- State column doesn't match derived state
- Buttons behave unexpectedly

**Diagnosis:**
```php
$election = Election::find($id);

$columnState = $election->state;
$derivedState = ElectionLifecycle::of($election)->state()->value;

if ($columnState !== $derivedState) {
    Log::warning('STALE STATE DETECTED', [
        'election_id' => $election->id,
        'column_state' => $columnState,
        'derived_state' => $derivedState,
    ]);
}
```

**Root Cause:** Someone wrote to state column directly (WRONG):
```php
// ❌ THIS IS WHY STATE IS STALE
$election->update(['state' => 'voting_active']);
```

**Fix:**

**Option 1: Sync from engine**
```php
$election->refresh();
$derivedState = ElectionLifecycle::of($election)->state()->value;
$election->update(['state' => $derivedState]);
Log::info('State synced', ['state' => $derivedState]);
```

**Option 2: Use transitionTo() for real changes**
```php
// If you meant to transition, use the proper API
$election->transitionTo(
    Transition::manual('open_voting', auth()->id(), 'Manual correction')
);
```

**Option 3: Check facts if state is wrong**
- If derived state is wrong, constitutional facts are wrong
- Correct the facts, not the state:
  ```php
  // Don't do this:
  $election->update(['state' => 'voting_active']);
  
  // Do this:
  $election->update([
      'voting_starts_at' => now()->subHour(),
      'voting_ends_at' => now()->addHour(),
      'voting_locked' => true,
      'nomination_completed' => true,
  ]);
  ```

---

### Issue 4: "InvalidTransitionException"

**Symptoms:**
- Clicking button gives error: "open_voting not allowed in current state"
- Cannot complete phase transitions
- State transition fails

**Error Message Example:**
```
InvalidTransitionException: 'open_voting' not allowed in state 'setup'
```

**Diagnosis:**
```php
$election = Election::find($id);
$action = 'open_voting'; // Or whatever action failed

$lifecycle = ElectionLifecycle::of($election);
$state = $lifecycle->state()->value;
$allowed = $lifecycle->allowedActions();
$blocked = $lifecycle->blockedReason();

Log::info('Transition debug', [
    'action' => $action,
    'state' => $state,
    'allowed_actions' => $allowed,
    'blocked_reason' => $blocked,
    'can_transition' => $lifecycle->canTransitionTo($action),
]);
```

**Solutions:**

**If action is not in allowedActions:**
- Election is in wrong state for this action
- Check constitutional facts:
  ```php
  // For open_voting, need:
  // - State: ready_for_voting
  // - nomination_completed = true
  // - candidates_count > 0
  // - pending_candidacies_count = 0
  // - voting_starts_at is set and in future
  
  Log::info('OpenVoting preconditions:', [
      'state' => ElectionLifecycle::of($election)->state()->value,
      'nomination_completed' => $election->nomination_completed,
      'candidates_count' => $election->candidates_count,
      'pending_candidacies' => $election->pending_candidacies_count,
      'voting_starts_at' => $election->voting_starts_at,
  ]);
  ```

**If blockedReason is set:**
- Fix the reason:
  ```php
  $blocked = ElectionLifecycle::of($election)->blockedReason();
  
  // Example: "No candidates have been registered"
  // Solution: Create some candidates
  
  // Example: "There are pending candidacy applications"
  // Solution: Approve or reject pending applications
  ```

---

### Issue 5: "Cannot Approve Voters / Manage Settings"

**Symptoms:**
- Authorization error when trying to approve voters
- Cannot access management page
- 403 Forbidden error

**Diagnosis:**
```php
$election = Election::find($id);
$user = auth()->user();

// Check policy
$canManage = $user->can('manageSettings', $election);
Log::info('Authorization check', [
    'user_id' => $user->id,
    'election_id' => $election->id,
    'can_manage' => $canManage,
]);

// Check if user is officer
$officer = ElectionOfficer::where([
    'user_id' => $user->id,
    'election_id' => $election->id,
])->first();
Log::info('Officer status', ['officer' => $officer]);

// Check if election is editable
$editable = ElectionLifecycle::of($election)->canEdit();
Log::info('Election editable', ['editable' => $editable]);
```

**Solutions:**

**If user is not an officer:**
```php
// Create the ElectionOfficer relationship
ElectionOfficer::create([
    'election_id' => $election->id,
    'user_id' => $user->id,
    'organisation_id' => $election->organisation_id,
    'role' => 'chief',  // or 'deputy'
    'status' => 'active',
]);
```

**If election is not editable:**
- Election is in voting or later phase
- Can only manage before voting starts
- See Issue 1 to fix state

**If policy returns false even though officer exists:**
- Check `ElectionPolicy::manageSettings()`
- Ensure `canEdit()` returns true
- Verify role is 'chief' or 'deputy'

---

### Issue 6: "Voting Window Not Opening"

**Symptoms:**
- Click "Open Voting" button, nothing happens
- State doesn't change to voting_active
- No error message shown

**Diagnosis:**
```php
$election = Election::find($id);

// Check current state
$state = ElectionLifecycle::of($election)->state()->value;
Log::info('Before transition', ['state' => $state]);

// Check preconditions for open_voting
Log::info('Open voting preconditions:', [
    'state' => $state,
    'nomination_completed' => $election->nomination_completed,
    'candidates_count' => $election->candidates_count,
    'pending_candidacies' => $election->pending_candidacies_count,
    'can_transition' => ElectionLifecycle::of($election)->canTransitionTo('open_voting'),
    'blocked_reason' => ElectionLifecycle::of($election)->blockedReason(),
]);
```

**Solutions:**

**If state is not ready_for_voting:**
- Must complete administration and nomination first
- Or election was already opened

**If candidates_count is 0:**
```php
// Create some candidates
$post = Post::factory()->create(['election_id' => $election->id]);
$user = User::factory()->create();
Candidacy::factory()
    ->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'status' => 'approved',
    ]);

// Update count
$election->update(['candidates_count' => 1]);
```

**If pending_candidacies_count > 0:**
```php
// Approve or reject pending candidacies
Candidacy::where('election_id', $election->id)
    ->where('status', 'pending')
    ->update(['status' => 'approved']);

$election->update(['pending_candidacies_count' => 0]);
```

**Manual workaround (if needed):**
```php
// Set facts directly
$election->update([
    'voting_starts_at' => now(),
    'voting_ends_at' => now()->addDays(4),
    'voting_locked' => true,
    'voting_locked_at' => now(),
    'voting_locked_by' => auth()->id(),
]);

// Verify state changed
$newState = ElectionLifecycle::of($election)->state()->value;
Log::info('After manual set', ['state' => $newState]);
```

---

### Issue 7: "Tests Failing - State Mismatch"

**Symptoms:**
- Test creates election, expects state X, gets state Y
- `ElectionScenarioFactory` methods not working
- Tests were passing, now failing

**Root Cause:** Test didn't sync state column to database

**Diagnosis:**
```php
// In test
$election = Election::factory()->create([
    'nomination_completed' => true,
    'voting_starts_at' => now(),
    'voting_ends_at' => now()->addDay(),
]);

$derivedState = ElectionLifecycle::of($election)->state()->value;
Log::info('Derived state', ['state' => $derivedState]);

Log::info('Column state', ['state' => $election->state]); // Might be NULL
```

**Solution:**
```php
// ✅ Correct: Use ElectionScenarioFactory
$election = ElectionScenarioFactory::votingActive($org);
// Factory sets facts AND syncs state column

// ❌ Wrong: Set facts but forget to sync
$election = Election::factory()->create([...]);
$election->update(['state' => ElectionLifecycle::of($election)->state()->value]);

// ✅ Also correct: Sync after setting facts
$election = Election::factory()->create([...]);
$state = ElectionLifecycle::of($election)->state()->value;
$election->update(['state' => $state]);
```

**If using custom factories:**
```php
// Pattern for test factories
public function test_custom_election_state()
{
    $election = Election::factory()->create([
        'name' => 'Test Election',
        'type' => 'real',
        'approved_at' => now()->subDay(),
        'voting_starts_at' => now()->subHour(),
        'voting_ends_at' => now()->addHour(),
        'nomination_completed' => true,
        'voting_locked' => true,
    ]);
    
    // CRITICAL: Sync derived state to column
    $state = ElectionLifecycle::of($election)->state()->value;
    $election->update(['state' => $state]);
    
    // NOW verify
    $this->assertEquals('voting_active', $election->fresh()->state);
}
```

---

## Debug Commands

### Quick State Check
```bash
# In tinker or artisan shell
> $e = Election::find(1)
> $s = ElectionLifecycle::of($e)->state()->value
> echo $s; // voting_active
```

### Check All Elections' States
```bash
Election::all()->each(fn($e) => 
    echo "{$e->id}: " . ElectionLifecycle::of($e)->state()->value . "\n"
);
```

### Sync All Stale States
```bash
Election::all()->each(function($election) {
    $derived = ElectionLifecycle::of($election)->state()->value;
    if ($election->state !== $derived) {
        Log::info('Syncing stale state', [
            'id' => $election->id,
            'old' => $election->state,
            'new' => $derived,
        ]);
        $election->update(['state' => $derived]);
    }
});
```

### Test State Transitions
```bash
> $e = Election::find(1)
> ElectionLifecycle::of($e)->canTransitionTo('open_voting')
true

> $e->transitionTo(Transition::manual('open_voting', 1, 'test'))
# If error, check logs
```

---

## When to Contact Support

If you've gone through this troubleshooting guide and still can't solve it:

1. **Gather debugging info:**
   ```php
   $election = Election::find($problematicId);
   Log::error('State machine issue', [
       'election_id' => $election->id,
       'state_column' => $election->state,
       'derived_state' => ElectionLifecycle::of($election)->state()->value,
       'facts' => [
           'approved_at' => $election->approved_at,
           'voting_starts_at' => $election->voting_starts_at,
           'voting_ends_at' => $election->voting_ends_at,
           'results_published_at' => $election->results_published_at,
       ],
       'allowed_actions' => ElectionLifecycle::of($election)->allowedActions(),
       'blocked_reason' => ElectionLifecycle::of($election)->blockedReason(),
   ]);
   ```

2. **Check logs:** `storage/logs/laravel.log`

3. **Provide:**
   - Election ID
   - The exact error message or symptom
   - The debug output above
   - What action the user was trying to perform

---

**Last Updated:** May 21, 2026
**Debug Guide Version:** 1.0
