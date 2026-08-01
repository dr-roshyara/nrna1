# Claude CLI Prompt: Add `lock_voting` Transition — Manual Voting Start

---

## Role: Senior Architect — Election State Machine System

You are adding a new `lock_voting` action to the election state machine. This separates the "Open Voting" (enter voting phase) from "Lock Voting" (actually start voting). Currently, `open_voting` locks voting immediately, preventing date edits before voting begins.

---

## Business Requirement

Election chiefs need to:
1. Open voting → enter voting phase (dates still editable)
2. Configure/update voting dates if needed
3. Lock voting → voting officially begins (dates frozen)
4. Close voting → end voting period

---

## Current Architecture (Read Only)

### `TransitionMatrix.php` — Current State
```php
ALLOWED_ACTIONS:
  'voting' => ['close_voting'],

ACTION_RESULTS:
  'close_voting' => 'results_pending',

ACTION_PERMISSIONS:
  'close_voting' => ['chief', 'deputy'],
```

### `Election.php` — Current `applySideEffectsForOpenVoting()`
```php
private function applySideEffectsForOpenVoting(?string $actorId, \Carbon\Carbon $currentTime): void
{
    $updateData = [
        'nomination_completed' => true,
        'nomination_completed_at' => $currentTime,
        'voting_locked' => true,        // ← REMOVE THIS
        'voting_locked_at' => $currentTime,  // ← REMOVE THIS
    ];
    // ...
}
```

### `Management.vue` — Voting Period Control Section
Currently has three buttons:
- "Submit for Approval" (draft state)
- "Open Voting" (nomination state)
- "Close Voting" (voting state)

---

## Files to Modify

| File | Change |
|------|--------|
| `app/Domain/Election/StateMachine/TransitionMatrix.php` | Add `lock_voting` to 3 arrays |
| `app/Models/Election.php` | Remove voting_locked from `applySideEffectsForOpenVoting()`, add `applySideEffectsForLockVoting()`, add to match block |
| `resources/js/Pages/Election/Management.vue` | Add `canLockVoting` computed, add "Lock & Start Voting" button in template, add `lockVoting()` method |

---

## Implementation

### Step 1: Update TransitionMatrix

File: `app/Domain/Election/StateMachine/TransitionMatrix.php`

Three changes:

```php
// ALLOWED_ACTIONS — change voting entry:
'voting' => ['close_voting', 'lock_voting'],

// ACTION_RESULTS — add:
'lock_voting' => 'voting',  // Stays in voting state, just sets the lock

// ACTION_PERMISSIONS — add:
'lock_voting' => ['chief', 'deputy'],
```

---

### Step 2: Update Election Model

File: `app/Models/Election.php`

#### 2a: Remove `voting_locked` from `applySideEffectsForOpenVoting()`

Find the method (around line ~1680) and remove these two lines from the `$updateData` array:

```php
// REMOVE these two lines:
'voting_locked' => true,
'voting_locked_at' => $currentTime,
```

The method should keep everything else (`nomination_completed`, `voting_starts_at`, `voting_ends_at`, `voting_locked_by`).

#### 2b: Add `applySideEffectsForLockVoting()`

Insert after `applySideEffectsForOpenVoting()`:

```php
/**
 * Lock voting — marks voting as officially started.
 * After this, dates can no longer be edited.
 */
private function applySideEffectsForLockVoting(\Carbon\Carbon $currentTime): void
{
    \Illuminate\Support\Facades\DB::table('elections')
        ->where('id', $this->id)
        ->update([
            'voting_locked'    => true,
            'voting_locked_at' => $currentTime,
        ]);
}
```

#### 2c: Add to transitionTo() match block

In `transitionTo()`, find the match block for side effects (~line 1465) and add:

```php
'lock_voting' => $this->applySideEffectsForLockVoting($currentTime),
```

The match block should now include:
```php
match ($transition->action) {
    'open_voting'  => $this->applySideEffectsForOpenVoting($transition->actorId, $currentTime),
    'close_voting' => $this->applySideEffectsForCloseVoting($currentTime),
    'approve'      => $this->applySideEffectsForApprove($transition->actorId, $currentTime),
    'complete_administration' => $this->applySideEffectsForCompleteAdministration($currentTime),
    'publish_results' => $this->applySideEffectsForPublishResults($currentTime),
    'lock_voting'  => $this->applySideEffectsForLockVoting($currentTime),  // ← ADD THIS
    default        => null,
};
```

---

### Step 3: Update Management.vue

File: `resources/js/Pages/Election/Management.vue`

#### 3a: Add `canLockVoting` computed

Find the other `can*` computed properties (~line 780) and add:

```js
const canLockVoting = computed(() => 
    allowedActions.value.includes('lock_voting') && 
    !props.election.voting_locked
)
```

#### 3b: Add button in template

In the Voting Period Control section, add the "Lock & Start Voting" button **between** the "Open Voting" button and the "Close Voting" button. Insert after the "Open Voting" transition block, before the "Close Voting" transition block:

```vue
<!-- Lock Voting: Show when in Voting phase, not yet locked -->
<transition name="fade-scale" mode="out-in">
  <div v-if="canLockVoting" key="lock" class="w-full">
    <ActionButton
      variant="warning"
      size="lg"
      :loading="isLoading"
      class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 shadow-lg hover:shadow-xl transition-all duration-200"
      @click="lockVoting"
    >
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
      </svg>
      <span class="font-bold text-base">🔒 Lock & Start Voting</span>
      <span class="text-xs opacity-90 ml-2 hidden sm:inline">→ Begin voting</span>
    </ActionButton>
  </div>
</transition>
```

#### 3c: Add `lockVoting()` method

Add after the other action methods (`openVoting`, `closeVoting`):

```js
const lockVoting = () => {
  if (!confirm(t.value.confirm?.lock_voting || 'Lock voting and officially begin the election? This cannot be undone.')) return
  isLoading.value = true
  router.post(route('elections.lock-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onSuccess: () => {
      router.reload({ preserveScroll: true })
    },
    onFinish: () => { isLoading.value = false },
  })
}
```

#### 3d: Update the empty state condition

Find the empty state fallback (~line 455) and add `canLockVoting`:

```vue
<div v-if="!canSubmitForApproval && !canOpenVoting && !canLockVoting && !canCloseVoting" key="empty" ...>
```

---

### Step 4: Add Route

File: `routes/election/electionRoutes.php` (or wherever election routes are defined)

Add:

```php
Route::post('/elections/{election}/lock-voting', [ElectionManagementController::class, 'lockVoting'])
    ->name('elections.lock-voting');
```

---

### Step 5: Add Controller Method

File: `app/Http/Controllers/Election/ElectionManagementController.php`

Add:

```php
/**
 * Lock voting — officially begin the voting period.
 * Only available when election is in voting state and not yet locked.
 */
public function lockVoting(Election $election): RedirectResponse
{
    $this->authorize('manageElection', $election);

    $election->transitionTo(
        \App\Domain\Election\StateMachine\Transition::manual(
            action: 'lock_voting',
            actorId: auth()->id(),
            reason: 'Voting locked and officially started',
        )
    );

    return back()->with('success', 'Voting is now locked and officially started.');
}
```

---

## Verification

1. Create an election, move through phases to voting (or use existing: `namaste-lk0dziy6`)
2. Run: `UPDATE elections SET voting_locked = false WHERE slug = 'namaste-lk0dziy6'`
3. Go to management page → voting phase
4. "Lock & Start Voting" button should appear (amber/orange)
5. Click it → confirm dialog → voting locks
6. After lock, "Close Voting" button appears, dates are frozen
7. Before lock, dates should still be editable

---

## What NOT to Do

- Do NOT remove `voting_locked = true` from `applySideEffectsForCloseVoting()` — closing still locks
- Do NOT change the `open_voting` transition flow — it still moves nomination → voting
- Do NOT add a new database column — `voting_locked` already exists
- Do NOT modify `StateMachinePanel.vue` — date editing logic already handles this correctly

---

**Total: ~60 lines across 5 files.**
## Plan Review & TDD-First Improvements

---

## Issues Found

### 1. Tests Missing Entirely

The plan has no tests. The first step should be writing failing tests.

### 2. Route Placement Unclear

The plan says `routes/election/electionRoutes.php` but we need to verify the exact file and route pattern used by existing election actions (`open-voting`, `close-voting`).

### 3. Controller Method Unclear

The plan says add `lockVoting()` to `ElectionManagementController` but doesn't verify the controller already has similar methods (`openVoting`, `closeVoting`) to match patterns.

### 4. Side Effect Missing Actor Tracking

`applySideEffectsForLockVoting()` doesn't record `voting_locked_by` (who locked it). Compare with `applySideEffectsForCloseVoting()` which tracks the actor.

### 5. No Validation for Voting Dates

The `lock_voting` transition doesn't validate that voting dates are actually set. If `voting_starts_at` is null, locking doesn't make sense.

---

## Improved TDD-First Plan

### Step 1: Write Tests First (RED)

File: `tests/Feature/Election/LockVotingTest.php`

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockVotingTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $chief;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create();
        $this->chief = User::factory()->create();
        
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'state'           => 'voting',
            'voting_locked'   => false,
            'voting_starts_at'=> now()->addDay(),
            'voting_ends_at'  => now()->addDays(5),
        ]);

        ElectionOfficer::create([
            'user_id'         => $this->chief->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);
    }

    /** @test */
    public function chief_can_lock_voting(): void
    {
        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertRedirect();

        $fresh = $this->election->fresh();
        $this->assertTrue($fresh->voting_locked);
        $this->assertNotNull($fresh->voting_locked_at);
        $this->assertEquals('voting', $fresh->state); // Still in voting
    }

    /** @test */
    public function lock_voting_creates_audit_record(): void
    {
        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]));

        $transition = ElectionStateTransition::where('election_id', $this->election->id)
            ->latest()
            ->first();

        $this->assertEquals('voting', $transition->from_state);
        $this->assertEquals('voting', $transition->to_state);
        $this->assertEquals('MANUAL', $transition->trigger);
        $this->assertEquals($this->chief->id, $transition->actor_id);
    }

    /** @test */
    public function cannot_lock_voting_if_already_locked(): void
    {
        $this->election->update(['voting_locked' => true]);

        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function cannot_lock_voting_if_not_in_voting_state(): void
    {
        $this->election->update(['state' => 'nomination']);

        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function non_chief_cannot_lock_voting(): void
    {
        $regularUser = User::factory()->create();

        $this->actingAs($regularUser)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertStatus(403);
    }

    /** @test */
    public function open_voting_does_not_lock_voting(): void
    {
        // Create election in nomination
        $nomElection = Election::factory()->create([
            'organisation_id'    => $this->org->id,
            'state'              => 'nomination',
            'nomination_completed' => true,
            'candidates_count'   => 1,
        ]);

        ElectionOfficer::create([
            'user_id'         => $this->chief->id,
            'election_id'     => $nomElection->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        $this->actingAs($this->chief)
            ->post(route('elections.open-voting', ['election' => $nomElection->slug]));

        $fresh = $nomElection->fresh();
        $this->assertEquals('voting', $fresh->state);
        $this->assertFalse($fresh->voting_locked); // Should NOT be locked
    }
}
```

**Run:** `php artisan test tests/Feature/Election/LockVotingTest.php` → **ALL 6 FAIL (RED)**

---

### Step 2: Update TransitionMatrix

File: `app/Domain/Election/StateMachine/TransitionMatrix.php`

```php
'voting' => ['close_voting', 'lock_voting'],
'lock_voting' => 'voting',
'lock_voting' => ['chief', 'deputy'],
```

---

### Step 3: Update Election Model

File: `app/Models/Election.php`

#### 3a: Remove from `applySideEffectsForOpenVoting()`
Remove `voting_locked` and `voting_locked_at` from `$updateData`.

#### 3b: Add `applySideEffectsForLockVoting()`
```php
private function applySideEffectsForLockVoting(?\Carbon\Carbon $currentTime = null): void
{
    \Illuminate\Support\Facades\DB::table('elections')
        ->where('id', $this->id)
        ->update([
            'voting_locked'    => true,
            'voting_locked_at' => $currentTime ?? now(),
        ]);
}
```

#### 3c: Add to match block
```php
'lock_voting' => $this->applySideEffectsForLockVoting($currentTime),
```

#### 3d: Add validation
```php
private function validateLockVoting(): void
{
    if (!$this->voting_starts_at) {
        throw new \DomainException('Cannot lock voting: Voting start date is not set.');
    }
    if ($this->voting_locked) {
        throw new \DomainException('Voting is already locked.');
    }
}
```

The `transitionTo()` method automatically calls `validateLockVoting()` if it exists (via `validateTransitionRules`).

---

### Step 4: Add Route

Find existing election routes. Match the pattern used by `open-voting` and `close-voting`:

```php
Route::post('/elections/{election}/lock-voting', [ElectionManagementController::class, 'lockVoting'])
    ->name('elections.lock-voting');
```

---

### Step 5: Add Controller Method

```php
public function lockVoting(Election $election): RedirectResponse
{
    $this->authorize('manageElection', $election);

    if ($election->state !== 'voting') {
        return back()->with('error', 'Election must be in voting phase to lock.');
    }

    if ($election->voting_locked) {
        return back()->with('error', 'Voting is already locked.');
    }

    $election->transitionTo(
        \App\Domain\Election\StateMachine\Transition::manual(
            action: 'lock_voting',
            actorId: auth()->id(),
            reason: 'Voting locked and officially started',
        )
    );

    return back()->with('success', 'Voting is now locked and officially started.');
}
```

---

### Step 6: Update Management.vue

Add computed, button template, and method as in the original plan.

---

### Step 7: Run All Tests (GREEN)

```bash
# New tests
php artisan test tests/Feature/Election/LockVotingTest.php

# All election tests — no regressions
php artisan test tests/Feature/Election/
```

Expected: 6 new tests + 39 existing = **45 passing**.

---

## Updated File Summary

| # | File | Action |
|---|------|--------|
| 1 | `tests/Feature/Election/LockVotingTest.php` | **NEW** — 6 TDD tests |
| 2 | `app/Domain/Election/StateMachine/TransitionMatrix.php` | MODIFY — 3 lines |
| 3 | `app/Models/Election.php` | MODIFY — remove 2 lines, add 3 methods |
| 4 | `routes/election/electionRoutes.php` | MODIFY — 1 route |
| 5 | `app/Http/Controllers/Election/ElectionManagementController.php` | MODIFY — 1 method |
| 6 | `resources/js/Pages/Election/Management.vue` | MODIFY — computed + button + method |

---

**Proceed with TDD: Step 1 first, watch tests fail, then implement Steps 2-6.** 