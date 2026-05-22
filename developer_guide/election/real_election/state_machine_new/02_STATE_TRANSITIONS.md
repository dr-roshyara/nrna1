# State Transitions — Complete Flow Map

## State Diagram

```
                           ┌─────────────────────────────────────────────┐
                           │                    DRAFT                     │
                           │  Election created, not yet submitted         │
                           └──────────────┬──────────────────────────────┘
                                          │
                                          │ submit_for_approval
                                          ↓
                           ┌─────────────────────────────────────────────┐
                           │         SUBMITTED_FOR_APPROVAL               │
                           │  Awaiting platform admin decision            │
                           └──────┬──────────────────┬────────────────────┘
                                  │                  │
                    approve        │                  │ reject
                                   ↓                  ↓
                    ┌──────────────────────┐  ┌──────────────────┐
                    │   APPROVED           │  │   REJECTED       │
                    │ Ready for setup      │  │ (Terminal)       │
                    └─────────┬────────────┘  └──────────────────┘
                              │
                              │ begin_setup
                              ↓
                    ┌──────────────────────┐
                    │   SETUP              │
                    │ Configure phases     │
                    │ Import voters        │
                    │ Set up candidates    │
                    └─────────┬────────────┘
                              │
                              │ complete_administration
                              ↓
                    ┌──────────────────────┐
                    │   READY_FOR_VOTING   │
                    │ All config complete  │
                    │ Voting not started   │
                    └─────────┬────────────┘
                              │
                              │ open_voting
                              ↓
                    ┌──────────────────────┐
                    │  VOTING_ACTIVE       │
                    │ Voting in progress   │
                    │ Accepting ballots    │
                    └─────────┬────────────┘
                              │
                              │ close_voting
                              ↓
                    ┌──────────────────────┐
                    │   COUNTING           │
                    │ Votes being tallied  │
                    │ Results not public   │
                    └─────────┬────────────┘
                              │
                              │ publish_results
                              ↓
                    ┌──────────────────────┐
                    │ RESULTS_PUBLISHED    │
                    │ Final results shown  │
                    │ (Terminal)           │
                    └──────────────────────┘

At any point → archive (terminal state)
```

---

## Allowed Transitions by State

### DRAFT → ?
**Preconditions:** 
- At least one post created
- At least one voter added
- At least one officer assigned

**Allowed Transitions:**
- `submit_for_approval` → Move to SUBMITTED_FOR_APPROVAL

**Side Effects of `submit_for_approval`:**
- Sets `submitted_at = now()`
- Creates ElectionStateTransition record

### SUBMITTED_FOR_APPROVAL → ?
**Who Can Transition:** Platform admin only

**Allowed Transitions:**
- `approve` → Move to APPROVED
- `reject` → Move to REJECTED

**Side Effects of `approve`:**
- Sets `approved_at = now()`

**Side Effects of `reject`:**
- Sets `rejected_at = now()`

### APPROVED → ?
**Preconditions:**
- Election was approved (approved_at is set)
- Not yet in setup phase

**Allowed Transitions:**
- `begin_setup` → Move to SETUP

**Side Effects of `begin_setup`:**
- None (phase dates will be set later)

### REJECTED → ?
**State:** Terminal — no transitions allowed

### SETUP → ?
**Preconditions:**
- Administration phase has NOT been completed yet
- Can still configure dates, import voters, manage candidates

**Allowed Transitions:**
- `complete_administration` → Move to READY_FOR_VOTING

**Side Effects of `complete_administration`:**
- Sets `administration_completed = true`
- Sets `administration_completed_at = now()`
- Sets `nomination_completed = true`
- Sets `nomination_completed_at = now()`

### READY_FOR_VOTING → ?
**Preconditions:**
- Both administration and nomination are completed
- Voting window is configured but NOT started yet
  - `voting_starts_at` is set
  - `voting_ends_at` is set
  - Now is NOT between voting_starts_at and voting_ends_at

**Allowed Transitions:**
- `open_voting` → Move to VOTING_ACTIVE

**Side Effects of `open_voting`:**
- Sets `voting_starts_at = now()` (if not already started)
- Sets `voting_ends_at = now() + 4 days` (default, configurable)
- Sets `voting_locked = true`
- Sets `voting_locked_at = now()`
- Sets `voting_locked_by = $userId`

### VOTING_ACTIVE → ?
**Preconditions:**
- Voting window is open (now is between voting_starts_at and voting_ends_at)
- Voting is locked
- Votes are being accepted

**Allowed Transitions:**
- `close_voting` → Move to COUNTING
- `lock_voting` → Stay in VOTING_ACTIVE (already locked, no-op)

**Side Effects of `close_voting`:**
- Sets `voting_ends_at = now()` (forces window closed)
- Creates ElectionStateTransition record

### COUNTING → ?
**Preconditions:**
- Voting window has ended (now > voting_ends_at)
- Results not yet published

**Allowed Transitions:**
- `publish_results` → Move to RESULTS_PUBLISHED

**Side Effects of `publish_results`:**
- Sets `results_published_at = now()`
- Creates ElectionStateTransition record

### RESULTS_PUBLISHED → ?
**State:** Terminal — no transitions except archive

**Preconditions:**
- Results have been published
- Election is complete and concluded

---

## Automatic State Derivation Rules

The engine checks facts IN THIS ORDER:

```php
1. IF rejected_at IS SET → REJECTED (terminal)

2. IF results_published_at IS SET → RESULTS_PUBLISHED (terminal)

3. IF voting_ends_at IS PAST AND NOT results_published_at
   → COUNTING

4. IF voting_starts_at IS PAST 
   AND voting_ends_at IS FUTURE
   → VOTING_ACTIVE

5. IF voting_starts_at IS FUTURE
   AND nomination_completed = true
   AND administration_completed = true
   → READY_FOR_VOTING

6. IF administration_completed = false
   AND approved_at IS SET
   → SETUP

7. IF approved_at IS SET
   AND submitted_at IS SET
   → APPROVED

8. IF submitted_at IS SET
   AND approved_at IS NULL
   → SUBMITTED_FOR_APPROVAL

9. DEFAULT → DRAFT
```

---

## Special Cases & Edge Cases

### Election Submitted But Needs Major Edits
**Situation:** User submitted for approval, but wants to change posts/voters
**Solution:** Contact platform admin to reject and return to DRAFT

### Voting Window Hasn't Started Yet
**Situation:** `voting_starts_at = tomorrow`, `voting_ends_at = next week`
**State:** READY_FOR_VOTING (not yet VOTING_ACTIVE)
**Why:** The engine checks if NOW is between start and end

### Voting Ended Unexpectedly
**Situation:** Election officer needs to close voting early
**Solution:** Call `closeVoting()` endpoint which:
- Sets `voting_ends_at = now()`
- Engine derivation immediately returns COUNTING

### Results Partially Published
**Situation:** Results visible to some users but not all
**Note:** State doesn't track this. State is purely PUBLISHED or NOT. Visibility is separate.

### Multiple Officers In Election
**Situation:** Two officers try to open voting simultaneously
**Guard:** ConstitutionalTransitionGuard prevents duplicate transitions
**Result:** First succeeds, second gets InvalidTransitionException

### Election State Column Out of Sync
**Situation:** `state = 'draft'` but `approved_at` is set (stale cache)
**Fix:** Don't read state column. Always use `ElectionLifecycle::of($election)->state()`
**Root Cause:** Someone wrote to state column directly (WRONG)

---

## Transition Authorization Rules

### Who Can Approve Elections?
**Permission:** Only platform admins
**Action:** `approve` and `reject`

### Who Can Open/Close Voting?
**Roles:** Chief, Deputy
**Permission:** `manageSettings` on election
**Action:** `open_voting`, `close_voting`, `lock_voting`

### Who Can Complete Phases?
**Roles:** Chief, Deputy  
**Permission:** `manageSettings` on election
**Action:** `complete_administration`, `complete_nomination`

### Who Can Submit for Approval?
**Roles:** Chief only
**Permission:** `manageSettings` on election
**Action:** `submit_for_approval`

See `app/Domain/Election/Constitution/ElectionConstitution.php` for complete rules.

---

## Timeline of Typical Election

```
Day 0: Create election
  ├─ State: DRAFT
  └─ Actions: Edit config, import voters, submit for approval

Day 1: Submit for approval
  ├─ State: SUBMITTED_FOR_APPROVAL
  └─ Actions: Wait for admin

Day 2: Admin approves
  ├─ State: APPROVED
  └─ Actions: Begin setup

Days 2-5: Configure phases
  ├─ State: SETUP
  ├─ Actions: Import voters, manage candidates, set phase dates
  └─ Complete administration phase

Day 5: Administration complete
  ├─ State: READY_FOR_VOTING
  └─ Actions: Configure voting window dates, check readiness

Day 6: Voting opens
  ├─ State: VOTING_ACTIVE
  ├─ Actions: Accept votes, monitor, prepare to close
  └─ Voting window: 08:00 - 20:00 today

Day 6 20:00: Voting closes
  ├─ State: COUNTING
  └─ Actions: Verify votes, generate results

Day 7: Results published
  ├─ State: RESULTS_PUBLISHED
  └─ Actions: View results, archive
```

---

## Testing State Transitions

### TDD Pattern for New Transitions

```php
// 1️⃣ RED: Write failing test with constitutional facts
public function test_open_voting_transition(): void
{
    $election = Election::factory()
        ->create([
            'nomination_completed' => true,
            'nomination_completed_at' => now()->subHour(),
            'administration_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
    
    $initialState = ElectionLifecycle::of($election)->state();
    $this->assertEquals('ready_for_voting', $initialState->value);
    
    // 2️⃣ GREEN: Make transition
    $election->transitionTo(Transition::manual('open_voting', auth()->id(), 'Test'));
    
    // 3️⃣ VERIFY: State changed
    $newState = ElectionLifecycle::of($election)->state();
    $this->assertEquals('voting_active', $newState->value);
}
```

### Using ElectionScenarioFactory

```php
// Instead of setting state directly, set facts and verify derivation
$election = ElectionScenarioFactory::votingActive($org);
// ✅ Facts set by factory
// ✅ Derived state verified by factory
// ✅ State column synced by factory
// ✅ Ready for testing
```

---

## Debugging State Issues

### "Why is the state wrong?"

1. **Check constitutional facts first**
   ```php
   $election = Election::find($id);
   Log::info('Election facts', [
       'approved_at' => $election->approved_at,
       'administration_completed' => $election->administration_completed,
       'nomination_completed' => $election->nomination_completed,
       'voting_starts_at' => $election->voting_starts_at,
       'voting_ends_at' => $election->voting_ends_at,
       'results_published_at' => $election->results_published_at,
   ]);
   ```

2. **Derive state from engine**
   ```php
   $derived = ElectionLifecycle::of($election)->state()->value;
   Log::info('Derived state', ['state' => $derived]);
   ```

3. **Compare to column**
   ```php
   Log::info('State column', ['state' => $election->state]);
   // If different, column is stale — use derived state instead
   ```

4. **Check allowed actions**
   ```php
   $actions = ElectionLifecycle::of($election)->allowedActions();
   Log::info('Allowed actions', ['actions' => $actions]);
   ```

---

## Common Mistakes

| Mistake | Why It's Wrong | Fix |
|---------|---------------|-----|
| `if ($election->state === 'voting')` | Old state name, also reads stale column | `if (ElectionLifecycle::of($election)->state()->value === 'voting_active')` |
| `$election->update(['state' => 'X'])` | Bypasses transition validation & side effects | `$election->transitionTo(Transition::manual(...))` |
| Testing with hardcoded state | State should be derived, not set | Use ElectionScenarioFactory to set facts |
| Checking `$election->is_active` | Deprecated column doesn't exist | `ElectionLifecycle::of($election)->isActive()` |
| Reading state, then changing facts | State becomes stale | Always re-derive: `ElectionLifecycle::of($election->fresh())->state()` |

---

**Last Updated:** May 21, 2026
**Diagram Status:** Current as of Phase 3.1.F
