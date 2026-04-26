# 08: Action-Based State Machine Architecture

**Date:** April 26, 2026  
**Status:** ✅ Complete - 67/67 Tests Passing  
**Version:** 2.0 (Refactored from state-based to action-based)

---

## Overview

The election state machine has been refactored from a **state-to-state** model to an **action-based** model. Instead of transitions saying "go to voting state," they now say "perform open_voting action" which results in the voting state. This provides:

- **Clearer semantics** - actions describe what happens (open_voting, close_voting, approve)
- **Single responsibility** - `transitionTo()` is the only place that sets state
- **Better events** - events are dispatched by action name, not target state
- **Testability** - side effects are separated from state logic

---

## Architecture (Action-Based)

### The Two Constants: ALLOWED_ACTIONS + ACTION_RESULTS

```php
// app/Domain/Election/StateMachine/TransitionMatrix.php

const ALLOWED_ACTIONS = [
    'draft'            => ['submit_for_approval'],
    'pending_approval' => ['approve', 'reject'],
    'administration'   => ['complete_administration'],
    'nomination'       => ['open_voting'],
    'voting'           => ['close_voting'],
    'results_pending'  => ['publish_results'],
    'results'          => [],
];

const ACTION_RESULTS = [
    'submit_for_approval'    => 'pending_approval',
    'approve'                => 'administration',
    'reject'                 => 'draft',
    'complete_administration' => 'nomination',
    'open_voting'            => 'voting',
    'close_voting'           => 'results_pending',
    'publish_results'        => 'results',
];
```

**Key Insight:** An action maps to ONE resulting state. This is deterministic and testable.

---

## API: transitionTo() Method

### Signature

```php
public function transitionTo(
    string  $action,      // What to do: 'open_voting', 'approve', etc.
    string  $trigger,     // Who triggered: 'manual', 'time', 'grace_period'
    ?string $reason = null,      // Why (optional): "Approved by officer"
    ?string $actorId = null      // Who did it (optional): user ID
): ElectionStateTransition
```

### How It Works (Step by Step)

```php
// 1. VALIDATE: Check if action is allowed from current state
if (!TransitionMatrix::canPerformAction($fromState, $action)) {
    throw new InvalidTransitionException(
        "Action '{$action}' not allowed from '{$fromState}'"
    );
}

// 2. DERIVE: Get target state from action
$toState = TransitionMatrix::getResultingState($action);

// 3. LOCK: Acquire cache lock to prevent race conditions
$lock = Cache::lock("election_transition:{$this->id}", 10);

// 4. AUDIT: Create ElectionStateTransition record
$transition = ElectionStateTransition::create([
    'election_id' => $this->id,
    'from_state'  => $fromState,
    'to_state'    => $toState,
    'trigger'     => $trigger,
    'actor_id'    => $actorId,
    'reason'      => $reason,
]);

// 5. SET STATE: Only place in codebase that sets state
$this->updateQuietly(['state' => $toState]);

// 6. SIDE EFFECTS: Execute action-specific side effects
match ($action) {
    'open_voting'  => $this->applySideEffectsForOpenVoting($actorId, $currentTime),
    'close_voting' => $this->applySideEffectsForCloseVoting($currentTime),
    'approve'      => $this->applySideEffectsForApprove($actorId, $currentTime),
    // ... etc
};

// 7. EVENTS: Dispatch action-based events
match ($action) {
    'open_voting'  => event(new VotingOpened($this, $actorId)),
    'close_voting' => event(new VotingClosed($this, $actorId)),
    'approve'      => event(new ElectionApproved($this, $actorId, $reason)),
    // ... etc
};
```

---

## Usage Examples

### Example 1: Opening Voting (Controller)

```php
// app/Http/Controllers/Election/ElectionManagementController.php

public function openVoting(Election $election): RedirectResponse
{
    // Validation happens in controller
    if ($election->current_state !== 'nomination') {
        return back()->with('error', 'Must be in nomination phase');
    }

    if (!$election->canEnterVotingPhase()) {
        return back()->with('error', $election->getVotingPhaseBlockedReasons());
    }

    try {
        // Call action (NOT state name)
        $transition = $election->transitionTo(
            action: 'open_voting',           // ← Action name, not 'voting'
            trigger: 'manual',
            reason: 'Opened by election officer',
            actorId: auth()->id()
        );

        return back()->with('success', 'Voting opened');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

### Example 2: Approval Workflow (Domain)

```php
// app/Models/Election.php

public function submitForApproval(string $userId): void
{
    $this->transitionTo(
        'submit_for_approval',  // ← Action
        'manual',
        'Submitted for admin review',
        $userId
    );
    
    $this->updateQuietly([
        'submitted_for_approval_at' => now(),
        'submitted_by' => $userId,
    ]);
}

public function approve(string $approvedBy, ?string $notes = null): void
{
    // State transitions from pending_approval → administration
    $this->transitionTo(
        'approve',              // ← Action (not 'administration')
        'manual',
        $notes ?? 'Approved',
        $approvedBy
    );
}

public function reject(string $rejectedBy, string $reason): void
{
    // State transitions from pending_approval → draft
    $this->transitionTo(
        'reject',               // ← Action (not 'draft')
        'manual',
        $reason,
        $rejectedBy
    );
}
```

### Example 3: Querying by State

```php
// Find elections pending approval
$pending = Election::where('state', Election::STATE_PENDING_APPROVAL)->get();

// Or use scope
$pending = Election::pendingApproval()->get();

// Check if in specific state
if ($election->current_state === 'voting') {
    // Can call closeVoting
}
```

---

## Side Effects (Separated from State)

### Rule: Side Effects Do NOT Set State

Side-effect methods handle flag updates, timestamp setting, and database cleanup—but **never** touch the `state` column. The `transitionTo()` method sets state before calling side effects.

```php
// ✅ CORRECT: Side effects use DB::table()->update()
private function applySideEffectsForOpenVoting(?string $actorId, Carbon $currentTime): void
{
    // Validation
    if (($this->candidates_count ?? 0) === 0) {
        throw new DomainException('No candidates registered');
    }

    // Data to update (NO state column!)
    $updateData = [
        'nomination_completed' => true,
        'nomination_completed_at' => $currentTime,
        'voting_locked' => true,
        'voting_locked_at' => $currentTime,
        'voting_locked_by' => $actorId,
    ];

    if (!$this->voting_starts_at) {
        $updateData['voting_starts_at'] = $currentTime;
        $updateData['voting_ends_at'] = $currentTime->addDays(4);
    }

    // Use raw query for consistency (avoids Eloquent hooks)
    DB::table('elections')
        ->where('id', $this->id)
        ->update($updateData);
        
    $this->refresh();
}

// ❌ WRONG: Don't set state in side effects
private function applySideEffectsForOpenVoting(...): void
{
    $this->update(['state' => 'voting']); // ← NO! transitionTo() already did this
}
```

---

## Events (Action-Based Dispatch)

### Event Classes

```php
// app/Domain/Election/Events/VotingOpened.php
class VotingOpened {
    public function __construct(
        public readonly Election $election,
        public readonly ?string $openedBy = null
    ) {}
}

// app/Domain/Election/Events/ElectionApproved.php
class ElectionApproved {
    public function __construct(
        public readonly Election $election,
        public readonly ?string $approvedBy = null,
        public readonly ?string $notes = null
    ) {}
}
```

### Event Dispatching in transitionTo()

```php
match ($action) {
    'open_voting'         => event(new VotingOpened($this, $actorId)),
    'close_voting'        => event(new VotingClosed($this, $actorId)),
    'approve'             => event(new ElectionApproved($this, $actorId, $reason)),
    'submit_for_approval' => event(new ElectionSubmittedForApproval($this, $actorId)),
    'reject'              => event(new ElectionRejected($this, $actorId, $reason)),
    default               => event(new ElectionStateChangedEvent($this, $fromState, $toState, $trigger, $actorId)),
};
```

### Listening to Events

```php
// app/Listeners/NotifyOnApproval.php
public function handle(ElectionApproved $event): void
{
    // Send email to election officer
    Mail::to($event->election->officer)->send(new ApprovedMail($event->election));
}

// Register in EventServiceProvider
protected $listen = [
    ElectionApproved::class => [
        NotifyOnApproval::class,
        LogApprovalAction::class,
    ],
];
```

---

## Testing the Action-Based System

### Unit Tests: TransitionMatrix

```php
// tests/Unit/Domain/Election/TransitionMatrixTest.php

public function test_can_perform_action_from_draft(): void
{
    $this->assertTrue(
        TransitionMatrix::canPerformAction('draft', 'submit_for_approval')
    );
    $this->assertFalse(
        TransitionMatrix::canPerformAction('draft', 'approve')
    );
}

public function test_get_resulting_state(): void
{
    $this->assertEquals('pending_approval',
        TransitionMatrix::getResultingState('submit_for_approval')
    );
}
```

### Feature Tests: Controller Integration

```php
// tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php

public function test_open_voting_transitions_from_nomination_to_voting(): void
{
    $election = $this->createApprovedElection();
    $this->advanceToVotingState($election);

    // Call controller
    $response = $this->actingAs($this->officer)
        ->post(route('elections.open-voting', $election->slug));

    // Verify state changed
    $election->refresh();
    $this->assertEquals('voting', $election->current_state);

    // Verify audit trail
    $this->assertDatabaseHas('election_state_transitions', [
        'election_id' => $election->id,
        'to_state' => 'voting',
        'trigger' => 'manual',
        'actor_id' => $this->officer->id,
    ]);
}
```

### Feature Tests: Event Dispatch

```php
public function test_voting_opened_event_is_dispatched(): void
{
    $election = $this->createApprovedElection();
    $this->advanceToVotingState($election);

    Event::fake();

    $election->transitionTo('open_voting', 'manual', 'Opening', $this->officer->id);

    Event::assertDispatched(
        VotingOpened::class,
        function ($event) {
            return $event->election->id === $this->election->id
                && $event->openedBy === $this->officer->id;
        }
    );
}
```

---

## Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Controller Action                         │
│         (e.g., POST /elections/{id}/open-voting)            │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│              Controller Validation                           │
│  • Check permission (manageSettings)                        │
│  • Check state (must be in 'nomination')                    │
│  • Check business rules (candidates exist, etc.)            │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│              transitionTo('open_voting', ...)               │
│                                                              │
│  1. Validate: canPerformAction('nomination', 'open_voting') │
│  2. Derive:   getResultingState('open_voting') → 'voting'  │
│  3. Lock:     Cache::lock()                                 │
│  4. Audit:    Create ElectionStateTransition                │
│  5. State:    updateQuietly(['state' => 'voting'])          │
│  6. Effects:  applySideEffectsForOpenVoting()               │
│  7. Events:   event(new VotingOpened(...))                  │
└──────────────────────────┬──────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        ▼                  ▼                  ▼
   ┌────────────┐   ┌──────────────┐   ┌──────────────┐
   │   Audit    │   │  Side Effects │   │   Events    │
   │ (immutable)│   │ (vote locked, │   │ (listeners) │
   │            │   │  dates set)   │   │             │
   └────────────┘   └──────────────┘   └──────────────┘
                           │
                           ▼
              ┌──────────────────────────┐
              │  Database Updated ✓      │
              │  Events Fired ✓          │
              │  Audit Trail Recorded ✓  │
              └──────────────────────────┘
```

---

## Common Patterns

### Safe Transition with Error Handling

```php
try {
    $transition = $election->transitionTo(
        'open_voting',
        'manual',
        'Officer opened voting',
        auth()->id()
    );
    
    // Action succeeded
    Log::info('Voting opened', ['election_id' => $election->id]);
    
} catch (InvalidTransitionException $e) {
    // Action not allowed from current state
    Log::warning('Invalid transition', ['error' => $e->getMessage()]);
    return back()->with('error', 'Cannot transition: ' . $e->getMessage());
    
} catch (DomainException $e) {
    // Business rule violated (e.g., no candidates)
    Log::warning('Business rule violation', ['error' => $e->getMessage()]);
    return back()->with('error', $e->getMessage());
    
} catch (\Exception $e) {
    // Unexpected error
    Log::error('Transition failed', ['error' => $e->getMessage()]);
    return back()->with('error', 'Failed to transition election');
}
```

### Pre-Flight Validation

```php
// Check if transition is possible BEFORE attempting
if (!TransitionMatrix::canPerformAction($election->current_state, 'open_voting')) {
    $allowed = TransitionMatrix::getAllowedActions($election->current_state);
    return back()->with('error', 
        "Cannot open voting. Allowed actions: " . implode(', ', $allowed)
    );
}

// Now safe to call transition
$election->transitionTo('open_voting', 'manual', 'Ready', auth()->id());
```

### Query by State

```php
// Find elections in voting state
$voting = Election::where('state', 'voting')->get();

// Or use helper
$voting = Election::query()
    ->where('state', Election::STATE_VOTING)
    ->with('posts', 'members')
    ->orderByDesc('created_at')
    ->paginate(15);

// Or use scope (if defined)
$voting = Election::inVotingState()->get();
```

### Audit Trail Report

```php
// Get all transitions for an election
$transitions = $election->stateTransitions()->orderBy('created_at')->get();

foreach ($transitions as $t) {
    echo sprintf(
        "%s: %s → %s by user %s (%s)\n",
        $t->created_at->format('H:i:s'),
        $t->from_state,
        $t->to_state,
        $t->actor_id,
        $t->reason
    );
}

// Output:
// 10:30:45: draft → pending_approval by user 5 (Submitted by officer)
// 10:35:20: pending_approval → administration by user 8 (Approved)
// 14:00:15: administration → nomination by user 5 (Admin setup complete)
```

---

## Migration from State-Based to Action-Based

If you're updating existing code, here are the key changes:

### Old Way (State-Based)

```php
// ❌ OLD: Pass target state
$election->transitionTo('voting', 'manual', 'Opened voting', $userId);

// ❌ OLD: Check if can transition to state
if (!TransitionMatrix::canTransition('nomination', 'voting')) {
    // ...
}
```

### New Way (Action-Based)

```php
// ✅ NEW: Pass action name
$election->transitionTo('open_voting', 'manual', 'Opened voting', $userId);

// ✅ NEW: Check if can perform action
if (!TransitionMatrix::canPerformAction('nomination', 'open_voting')) {
    // ...
}
```

### Mapping Reference

| Old State | Action | New State |
|-----------|--------|-----------|
| draft | submit_for_approval | pending_approval |
| pending_approval | approve | administration |
| pending_approval | reject | draft |
| administration | complete_administration | nomination |
| nomination | open_voting | voting |
| voting | close_voting | results_pending |
| results_pending | publish_results | results |

---

## File Locations

### Core Implementation

```
app/
├── Domain/
│   └── Election/
│       ├── StateMachine/
│       │   └── TransitionMatrix.php        ← Two constants (ALLOWED_ACTIONS, ACTION_RESULTS)
│       └── Events/
│           ├── ElectionApproved.php
│           ├── ElectionRejected.php
│           ├── ElectionSubmittedForApproval.php
│           ├── VotingOpened.php
│           └── VotingClosed.php
├── Models/
│   └── Election.php                        ← transitionTo() method (1383-1520)
└── Http/
    └── Controllers/
        └── Election/
            └── ElectionManagementController.php  ← openVoting(), closeVoting()
```

### Testing

```
tests/
├── Unit/
│   └── Domain/
│       └── Election/
│           └── TransitionMatrixTest.php    ← 23 tests
├── Feature/
│   └── Election/
│       ├── ElectionStateMachineTest.php    ← 35 tests
│       └── VotingButtonsStateMachineIntegrationTest.php  ← 9 tests
```

### Routes

```
routes/
└── election/
    └── electionRoutes.php                   ← Lines 270-276 (voting routes)
```

---

## Troubleshooting

### "Action 'approve' is not allowed from state 'draft'"

**Cause:** You're trying to approve an election still in draft state.

**Fix:** First submit for approval, then approve:

```php
// ✅ Correct workflow
$election->submitForApproval($userId);  // draft → pending_approval
$election->approve($userId, 'Approved'); // pending_approval → administration

// ❌ Wrong: Can't skip pending_approval
$election->approve($userId, 'Approved'); // Error!
```

### "Cannot open voting: No candidates registered"

**Cause:** The election has 0 candidates but voting requires at least 1.

**Fix:** Add candidates first:

```php
// Create post and candidate
$post = Post::factory()->create(['election_id' => $election->id]);
Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'approved']);

// Update count
$election->update(['candidates_count' => 1, 'pending_candidacies_count' => 0]);

// Now safe to open voting
$election->transitionTo('open_voting', 'manual', 'Ready', $userId);
```

### State not transitioning in tests

**Cause:** Test setup didn't satisfy `canEnterVotingPhase()` preconditions.

**Fix:** Ensure test setup has:

```php
$election = Election::factory()->create([
    'state' => 'nomination',                    // ← State must be correct
    'nomination_completed' => true,             // ← Must be true
    'candidates_count' => 1,                    // ← At least one
    'pending_candidacies_count' => 0,           // ← No pending
]);
```

---

## Performance Considerations

### Cache Locks

State transitions use cache locks to prevent race conditions:

```php
$lock = Cache::lock("election_transition:{$this->id}", 10);
return $lock->block(5, function () { /* transition */ });
```

- **Timeout:** 10 seconds (lock automatically released)
- **Block wait:** 5 seconds (how long to wait for lock)
- **Impact:** Concurrent transitions for same election are serialized

### Database Transactions

Side effects happen within a transaction:

```php
return DB::transaction(function () {
    // All updates are atomic
    // Rollback on exception
});
```

- **Atomic:** All-or-nothing guarantee
- **Rollback:** On exception, all changes revert
- **Performance:** Minimal impact for typical usage

---

## Summary

The action-based state machine provides:

1. **Clarity** - Actions describe what happens
2. **Safety** - Validation and locking prevent race conditions
3. **Auditability** - Every transition is recorded
4. **Testability** - Clear inputs/outputs, easy to test
5. **Extensibility** - Add new actions by updating two constants

**Key API:**

```php
TransitionMatrix::canPerformAction($state, $action)    // Validate action allowed
TransitionMatrix::getResultingState($action)           // Get target state
$election->transitionTo($action, $trigger, $reason, $actorId)  // Perform transition
```

**Test All:** `php artisan test tests/Feature/Election/ tests/Unit/Domain/Election/ --no-coverage`

---

**Last Updated:** April 26, 2026  
**Status:** ✅ Complete (67/67 tests passing)  
**Author:** System Architecture
