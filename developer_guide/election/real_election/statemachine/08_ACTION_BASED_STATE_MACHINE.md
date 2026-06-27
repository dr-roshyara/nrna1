# 08: Action-Based State Machine Architecture

**Date:** April 26, 2026  
**Status:** ✅ Complete - 40/40 Tests Passing (ElectionStateMachineTest)  
**Version:** 2.2 (Transition VO, role auth, temporal guards)

---

## Overview

The election state machine has been refactored from a **state-to-state** model to an **action-based** model. Instead of transitions saying "go to voting state," they now say "perform open_voting action" which results in the voting state. This provides:

- **Clearer semantics** - actions describe what happens (open_voting, close_voting, approve)
- **Single responsibility** - `transitionTo()` is the only place that sets state
- **Better events** - events are dispatched by action name, not target state
- **Testability** - side effects are separated from state logic

---

## Architecture (Action-Based)

### The Single Source of Truth: TRANSITIONS Constant

```php
// app/Domain/Election/StateMachine/TransitionMatrix.php

const TRANSITIONS = [
    'draft' => [
        'submit_for_approval' => ['to' => 'pending_approval', 'roles' => ['chief']],
        'auto_submit'         => ['to' => 'administration',   'roles' => ['system']],
    ],
    'pending_approval' => [
        'approve' => ['to' => 'administration', 'roles' => ['super_admin', 'platform_admin']],
        'reject'  => ['to' => 'draft',          'roles' => ['super_admin', 'platform_admin']],
    ],
    'administration' => [
        'complete_administration' => ['to' => 'nomination', 'roles' => ['chief', 'deputy']],
    ],
    'nomination' => [
        'open_voting' => ['to' => 'voting', 'roles' => ['chief', 'deputy']],
    ],
    'voting' => [
        'close_voting' => ['to' => 'results_pending', 'roles' => ['chief', 'deputy']],
        'lock_voting'  => ['to' => 'voting',          'roles' => ['chief', 'deputy']],
    ],
    'results_pending' => [
        'publish_results' => ['to' => 'results', 'roles' => ['chief']],
    ],
    'results' => [],
];
```

**Key Insight:** A single constant defines the entire state machine: which actions are allowed from each state, the resulting state for each action, and the roles permitted to perform it. This is deterministic, testable, and self-validating (`TransitionMatrix::validate()` is called at boot).

---

## API: transitionTo() Method

### Signature

```php
public function transitionTo(Transition $transition): ElectionStateTransition
```

Takes a `Transition` value object — immutable, typed, with metadata support:

```php
// Factories
Transition::manual(string $action, string|int $actorId, ?string $reason = null, array $metadata = [])
Transition::automatic(string $action, TransitionTrigger $trigger = TransitionTrigger::TIME, ?string $reason = null, array $metadata = [])
Transition::gracePeriod(string $action, ?string $reason = null, array $metadata = [])
```

The `TransitionTrigger` enum classifies the trigger source:

```php
enum TransitionTrigger: string {
    case MANUAL       = 'manual';        // User-initiated
    case TIME         = 'time';          // Scheduled/cron
    case GRACE_PERIOD = 'grace_period';  // Grace period expiry
    case SYSTEM       = 'system';        // Auto-approval, system-initiated
}
```

### How It Works (7-Step Process)

```php
// LOCK: Acquire cache lock to prevent race conditions (10s TTL, 5s block wait)
$lock = Cache::lock("election_transition:{$this->id}", 10);

return $lock->block(5, function () use ($transition, $currentTime) {
    return DB::transaction(function () use ($transition, $currentTime) {

        // ── 1. VALIDATE STATE: Action allowed from current state? ──────────
        // Throws InvalidTransitionException (extends DomainException)
        if (!TransitionMatrix::canPerformAction($fromState, $transition->action)) {
            throw new InvalidTransitionException(
                "Action '{$transition->action}' is not allowed from state '{$fromState}'. " .
                "Allowed: " . implode(', ', TransitionMatrix::getAllowedActions($fromState))
            );
        }

        // ── 2. AUTHORIZE ROLE: Does the actor have permission? ─────────────
        // System transitions (auto_submit) bypass role check.
        // Uses resolveActorRole() with priority: ElectionOfficer > super_admin > org_role > 'observer'
        // Throws DomainException if role is not in TRANSITIONS[state][action]['roles']
        if (!$transition->isSystemTriggered()) {
            $actorRole = $this->resolveActorRole($transition->actorId);
            if (!TransitionMatrix::actionRequiresRole($transition->action, $actorRole)) {
                throw new DomainException(
                    "Action '{$transition->action}' is not permitted for role '{$actorRole}'."
                );
            }
        }

        // ── 3. GUARD: Business rule validation ────────────────────────────
        // Dispatches to validate{Action}() method via naming convention.
        // E.g. 'open_voting' → validateOpenVoting() → calls whyCannotOpenVoting()
        // Throws InvalidArgumentException with human-readable reason.
        $freshElection->validateTransitionRules($transition);

        // ── 4. AUDIT: Create immutable ElectionStateTransition record ──────
        $record = ElectionStateTransition::create([
            'election_id' => $this->id,
            'from_state'  => $fromState,
            'to_state'    => $toState,
            'trigger'     => $transition->trigger->value,
            'actor_id'    => $transition->actorId === 'system' ? null : $transition->actorId,
            'reason'      => $transition->reason,
            'metadata'    => $transition->metadata ?: null,
            'created_at'  => $currentTime,
        ]);

        // ── 5. STATE CHANGE: Only place in codebase that sets state ───────
        $this->updateQuietly(['state' => $toState]);

        // ── 6. SIDE EFFECTS: Apply action-specific side effects (no state!) ─
        match ($transition->action) {
            'open_voting'  => $this->applySideEffectsForOpenVoting(...),
            'lock_voting'  => $this->applySideEffectsForLockVoting(...),
            'close_voting' => $this->applySideEffectsForCloseVoting(...),
            'approve'      => $this->applySideEffectsForApprove(...),
            'complete_administration' => $this->applySideEffectsForCompleteAdministration(...),
            'publish_results' => $this->applySideEffectsForPublishResults(...),
            default        => null,
        };

        return $record;
    });
    // Exception → original flags restored via forceFill()

    // ── 7. EVENTS (after commit): Action-based event dispatch ─────────────
    match ($transition->action) {
        'open_voting'         => event(new VotingOpened($this, $transition->actorId)),
        'close_voting'        => event(new VotingClosed($this, $transition->actorId)),
        'approve'             => event(new ElectionApproved($this, $transition->actorId, $transition->reason)),
        'submit_for_approval' => event(new ElectionSubmittedForApproval($this, $transition->actorId)),
        'reject'              => event(new ElectionRejected($this, $transition->actorId, $transition->reason)),
        default               => event(new ElectionStateChangedEvent(...)),
    };
});
```

### Exception Hierarchy

| Step | Exception | When |
|------|-----------|------|
| 1 | `InvalidTransitionException` (extends `DomainException`) | Action not allowed from current state |
| 2 | `DomainException` | Actor role lacks permission |
| 3 | `InvalidArgumentException` | Business rule violated (no posts, no voters, temporal guard, etc.) |

---

## Usage Examples

### Example 1: Opening Voting (Controller with Transition VO)

```php
use App\Domain\Election\StateMachine\Transition;

public function openVoting(Election $election): RedirectResponse
{
    try {
        $transition = $election->transitionTo(
            Transition::manual(
                action: 'open_voting',
                actorId: auth()->id(),
                reason: 'Opened by election officer',
            )
        );

        return back()->with('success', 'Voting opened');

    } catch (\DomainException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

### Example 2: Approval Workflow (with auto-approval)

```php
// app/Models/Election.php

public function submitForApproval(string $submittedBy): void
{
    $this->refresh();
    if ($this->requiresApproval()) {
        // expected_voter_count > 40 → manual: draft → pending_approval
        $this->processManualApproval($submittedBy);
    } else {
        // expected_voter_count ≤ 40 → auto: draft → administration
        $this->processAutoApproval($submittedBy);
    }
}

// Manual: needs super_admin/platform_admin to approve
private function processManualApproval(string $submittedBy): void
{
    $this->transitionTo(Transition::manual(
        action: 'submit_for_approval',
        actorId: $submittedBy,
        reason: 'Submitted for admin approval',
    ));
}

// Auto: system-triggered, skips pending_approval
private function processAutoApproval(string $submittedBy): void
{
    $this->transitionTo(Transition::automatic(
        action: 'auto_submit',
        trigger: TransitionTrigger::SYSTEM,
        reason: 'Auto-approved (self-service)',
    ));
}

public function approve(string $approvedBy, ?string $notes = null): void
{
    $this->transitionTo(Transition::manual(
        action: 'approve',
        actorId: $approvedBy,
        reason: $notes ?? 'Approved',
    ));
}
```

### Example 3: Complete Administration (with temporal guard)

```php
public function completeAdministration(string $reason, string $actorId): void
{
    $this->transitionTo(Transition::manual(
        action: 'complete_administration',
        actorId: $actorId,
        reason: $reason,
    ));
}

// Business validation (called via guard layer, step 3)
private function whyCannotCompleteAdministration(): ?string
{
    if (!$this->posts()->exists())               return 'No election posts have been created.';
    if (!$this->memberships()->where('role', 'voter')->where('status', 'active')->exists())
        return 'No voters have been added.';
    if (!ElectionOfficer::where('election_id', $this->id)->active()->exists())
        return 'No committee members have been added.';
    // Temporal guard: prevent completing admin before scheduled start
    if ($this->administration_suggested_start && now()->lt($this->administration_suggested_start))
        return 'Administration phase has not yet started.';
    return null;
}
```

### Example 4: Querying by State

```php
$pending = Election::where('state', Election::STATE_PENDING_APPROVAL)->get();
$pending = Election::pendingApproval()->get();

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

## Role Authorization

### Role Resolution Priority

The `resolveActorRole()` method resolves the actor's role with election-level taking precedence:

```php
public function resolveActorRole(string $actorId): string
{
    // 1. Election-level: ElectionOfficer role (highest priority)
    $electionRole = ElectionOfficer::withoutGlobalScopes()
        ->where('user_id', $actorId)
        ->where('election_id', $this->id)
        ->where('status', 'active')
        ->value('role');
    if ($electionRole) return $electionRole;

    // 2. Platform-level: super_admin or platform_admin
    if ($user->isSuperAdmin()) return 'super_admin';
    if ($user->platform_role === 'platform_admin') return 'platform_admin';

    // 3. Org-level: admin or owner via user_organisation_roles
    $orgRole = UserOrganisationRole::where('user_id', $actorId)
        ->where('organisation_id', $this->organisation_id)
        ->value('role');
    if (in_array($orgRole, ['admin', 'owner'], strict: true)) return $orgRole;

    return 'observer';  // Read-only
}
```

### Required Roles Per Action (from TRANSITIONS)

| Action | Required Roles |
|--------|---------------|
| `submit_for_approval` | chief |
| `auto_submit` | system (bypasses check) |
| `approve` | super_admin, platform_admin |
| `reject` | super_admin, platform_admin |
| `complete_administration` | chief, deputy |
| `open_voting` | chief, deputy |
| `close_voting` | chief, deputy |
| `lock_voting` | chief, deputy |
| `publish_results` | chief |

### Exception on Permission Denied

```
Step 2 throws: DomainException("Action 'open_voting' is not permitted for role 'admin'.")
```

This is distinct from step 1 (`InvalidTransitionException` — wrong state) and step 3 (`InvalidArgumentException` — business rule violation).

## Temporal Guards

The guard layer enforces scheduled dates, preventing phase transitions before their configured start times:

### Administration Phase

```php
// Guard: cannot complete admin before administration_suggested_start
if ($this->administration_suggested_start && now()->lt($this->administration_suggested_start)) {
    throw new \InvalidArgumentException(
        'Administration phase has not yet started. Scheduled start: ' . $this->administration_suggested_start->format('Y-m-d H:i')
    );
}
```

### Voting Phase

```php
// Guard: cannot open voting before voting_starts_at
if ($this->voting_starts_at && now()->lt($this->voting_starts_at)) {
    throw new \InvalidArgumentException(
        'Voting phase has not yet started. Scheduled start: ' . $this->voting_starts_at->format('Y-m-d H:i')
    );
}
```

Both guards are checked inside the respective `whyCannot{Action}()` methods, called by the guard layer at step 3. They pass when the scheduled start is in the past (or null), and block when it is in the future.

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
        Transition::manual(
            action: 'open_voting',
            actorId: auth()->id(),
            reason: 'Officer opened voting',
        )
    );
    
    Log::info('Voting opened', ['election_id' => $election->id]);
    
} catch (InvalidTransitionException $e) {
    // Step 1: Action not allowed from current state
    Log::warning('Invalid transition', ['error' => $e->getMessage()]);
    return back()->with('error', 'Cannot transition: ' . $e->getMessage());
    
} catch (DomainException $e) {
    // Step 2: Permission denied
    Log::warning('Permission denied', ['error' => $e->getMessage()]);
    return back()->with('error', $e->getMessage());
    
} catch (\InvalidArgumentException $e) {
    // Step 3: Business rule violation (no posts, temporal guard, etc.)
    Log::warning('Business rule violation', ['error' => $e->getMessage()]);
    return back()->with('error', $e->getMessage());
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

### Old Way (v2.0 — string params + two constants)

```php
// ❌ OLD: transitionTo took 4 string params
$election->transitionTo('open_voting', 'manual', 'Opened voting', $userId);

// ❌ OLD: Two separate constants: ALLOWED_ACTIONS + ACTION_RESULTS
TransitionMatrix::ALLOWED_ACTIONS['nomination']  // → ['open_voting']
TransitionMatrix::ACTION_RESULTS['open_voting']  // → 'voting'

// ❌ OLD: Check via state-to-state
TransitionMatrix::canTransition('nomination', 'voting')
```

### New Way (v2.2 — Transition VO + single TRANSITIONS constant)

```php
// ✅ NEW: Transition value object with typed trigger
$election->transitionTo(
    Transition::manual(action: 'open_voting', actorId: auth()->id(), reason: 'Opened voting')
);

// ✅ NEW: Single TRANSITIONS constant with roles embedded
TransitionMatrix::TRANSITIONS['nomination']['open_voting']
// → ['to' => 'voting', 'roles' => ['chief', 'deputy']]

// ✅ NEW: Role-aware checks
TransitionMatrix::canPerformAction('nomination', 'open_voting')
TransitionMatrix::actionRequiresRole('open_voting', 'chief')
TransitionMatrix::getAllowedRoles('open_voting')
```

### Action Mapping Reference

| Current State | Action | Target State | Required Roles |
|---------------|--------|-------------|----------------|
| draft | submit_for_approval | pending_approval | chief |
| draft | auto_submit | administration | system |
| pending_approval | approve | administration | super_admin, platform_admin |
| pending_approval | reject | draft | super_admin, platform_admin |
| administration | complete_administration | nomination | chief, deputy |
| nomination | open_voting | voting | chief, deputy |
| voting | close_voting | results_pending | chief, deputy |
| voting | lock_voting | voting | chief, deputy |
| results_pending | publish_results | results | chief |

---

## File Locations

### Core Implementation

```
app/
├── Domain/
│   └── Election/
│       ├── StateMachine/
│       │   ├── TransitionMatrix.php         ← Single TRANSITIONS constant (state → actions → to + roles)
│       │   ├── Transition.php               ← Immutable value object with factories
│       │   └── TransitionTrigger.php        ← Backed enum (MANUAL, TIME, GRACE_PERIOD, SYSTEM)
│       ├── Events/
│       │   ├── ElectionApproved.php
│       │   ├── ElectionRejected.php
│       │   ├── ElectionSubmittedForApproval.php
│       │   ├── VotingOpened.php
│       │   └── VotingClosed.php
│       └── Exceptions/
│           └── InvalidTransitionException.php
├── Models/
│   └── Election.php                        ← transitionTo() method (1554-1652), guard layer, role resolution
└── Http/
    └── Controllers/
        └── Election/
            └── ElectionManagementController.php  ← openVoting(), closeVoting()
            └── CandidacyManagementController.php
```

### Testing

```
tests/
├── Unit/
│   └── Domain/
│       └── Election/
│           ├── TransitionMatrixTest.php    ← Unit tests for action/role checks
│           └── TransitionTest.php          ← 14 tests for Transition VO
├── Feature/
│   └── Election/
│       ├── ElectionStateMachineTest.php    ← 40 tests (state derivation, transitions, events, temporal guards)
│       └── VotingButtonsStateMachineTest.php  ← 10 tests (voting button flows)
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
TransitionMatrix::canPerformAction($state, $action)      // Validate action allowed
TransitionMatrix::getResultingState($action)             // Get target state
TransitionMatrix::actionRequiresRole($action, $role)     // Check role permission
TransitionMatrix::getAllowedRoles($action)               // Get roles permitted for action
$election->transitionTo(Transition::manual(...))         // Perform transition
$election->resolveActorRole($userId)                    // Resolve user role
$election->whyCannotCompleteAdministration()            // Pre-flight check
$election->whyCannotOpenVoting()                        // Pre-flight check
```

**Test All:**
```bash
php artisan test tests/Feature/ElectionStateMachineTest.php
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php
php artisan test tests/Unit/Domain/Election/
```

---

**Last Updated:** May 12, 2026  
**Status:** ✅ Complete (40/40 ElectionStateMachineTest + 10 VotingButtonsStateMachineTest passing)
