# State Machine Implementation Guide

## Overview

The election state machine is a **domain-driven service** that manages state transitions and validates business rules.

---

## Architecture

### File Structure

```
app/Domain/Election/StateMachine/
├── ElectionStateMachine.php           (Domain service)
└── Exceptions/
    └── InvalidTransitionException.php (Domain exception)

app/Models/
├── Election.php                        (State derivation)
├── ElectionStateTransition.php         (Immutable audit)
└── ElectionAuditLog.php               (Mutable audit)
```

### Design Pattern

```
User Action
    ↓
Controller
    ↓
Authorization Check (ElectionPolicy)
    ↓
Domain Service (ElectionStateMachine)
    ↓
Business Logic (validate prerequisites)
    ↓
Model Update (Election model)
    ↓
Audit Logging (ElectionAuditLog)
```

---

## State Derivation

State is **calculated**, not stored as a column:

```php
// app/Models/Election.php

#[Attribute]
public function getCurrentStateAttribute(): string
{
    // Check in priority order
    if ($this->results_published_at !== null) {
        return 'results';
    }
    if ($this->voting_starts_at && 
        now()->isBetween($this->voting_starts_at, $this->voting_ends_at)) {
        return 'voting';
    }
    if ($this->voting_starts_at && now()->isAfter($this->voting_ends_at)) {
        return 'results_pending';
    }
    if ($this->nomination_completed_at !== null) {
        return 'nomination';
    }
    if ($this->administration_completed_at !== null) {
        return 'nomination';
    }
    return 'administration';
}
```

### State Determination Logic

| Condition | State |
|-----------|-------|
| `results_published_at` is set | `results` |
| Within voting window | `voting` |
| After voting window | `results_pending` |
| `nomination_completed_at` is set | `nomination` |
| `administration_completed_at` is set | `nomination` |
| Default | `administration` |

---

## ElectionStateMachine Service

### Location

```php
namespace App\Domain\Election\StateMachine;

class ElectionStateMachine
{
    private const TRANSITIONS = [
        'administration'  => ['nomination'],
        'nomination'      => ['voting'],
        'voting'          => ['results_pending'],
        'results_pending' => ['results'],
        'results'         => [],
    ];
    
    public function __construct(
        private Election $election
    ) {}
}
```

### Core Methods

#### Get Current State

```php
public function getCurrentState(): string
{
    return $this->election->current_state;
}
```

#### Check Can Transition

```php
public function canTransition(string $toState): bool
{
    $from = $this->getCurrentState();
    $validTransitions = self::TRANSITIONS[$from] ?? [];
    return in_array($toState, $validTransitions);
}
```

#### Validate Transition

```php
public function validateTransition(string $toState): void
{
    if (!$this->canTransition($toState)) {
        $from = $this->getCurrentState();
        $valid = implode(', ', self::TRANSITIONS[$from] ?? []);
        
        throw new InvalidTransitionException(
            "Cannot transition from '{$from}' to '{$toState}'. " .
            "Valid transitions: {$valid}"
        );
    }
}
```

#### Transition To State

```php
public function transitionTo(
    string $toState,
    string $trigger = 'manual',
    string $reason = '',
    ?string $actorId = null
): ElectionStateTransition
{
    // Validate transition
    $this->validateTransition($toState);
    
    // Create audit record
    return DB::transaction(fn() =>
        ElectionStateTransition::create([
            'election_id' => $this->election->id,
            'from_state' => $this->getCurrentState(),
            'to_state' => $toState,
            'trigger' => $trigger,
            'actor_id' => $actorId,
            'reason' => $reason,
            'metadata' => ['transitioned_at' => now()],
        ])
    );
}
```

#### Check Allowed Action

```php
public function allowsAction(string $action): bool
{
    return $this->election->allowsAction($action);
}
```

---

## Election Model Methods

### Get State Machine

```php
// app/Models/Election.php

private ?ElectionStateMachine $stateMachine = null;

public function getStateMachine(): ElectionStateMachine
{
    if ($this->stateMachine === null) {
        $this->stateMachine = new ElectionStateMachine($this);
    }
    return $this->stateMachine;
}
```

### Check Allowed Actions

```php
public function allowsAction(string $action): bool
{
    $state = $this->current_state;
    
    return match ($action) {
        'manage_posts' => in_array($state, ['administration', 'nomination']),
        'manage_candidacies' => $state === 'nomination',
        'cast_vote' => $state === 'voting',
        'view_results' => in_array($state, ['results_pending', 'results']),
        default => false,
    };
}
```

### Complete Administration

```php
public function completeAdministration(
    string $reason,
    string $actorId
): void
{
    // Validate prerequisites
    if ($this->posts()->count() === 0) {
        throw new DomainException('Cannot complete administration: no posts created');
    }
    if ($this->memberships()->count() === 0) {
        throw new DomainException('Cannot complete administration: no voters added');
    }
    
    // Update state
    $this->update([
        'administration_completed_at' => now(),
        'nomination_suggested_start' => now(),
        'nomination_suggested_end' => now()->addDays(7),
    ]);
    
    // Log transition
    $this->logStateChange([
        'action' => 'administration_completed',
        'from_state' => 'administration',
        'to_state' => 'nomination',
        'reason' => $reason,
        'actor_id' => $actorId,
    ]);
}
```

### Complete Nomination

```php
public function completeNomination(
    string $reason,
    ?string $actorId = null
): void
{
    // Validate prerequisites
    if ($this->candidacies()
        ->where('status', 'pending')
        ->exists()) {
        throw new DomainException('Cannot complete nomination: pending candidates exist');
    }
    
    // Update state
    $this->update([
        'nomination_completed_at' => now(),
        'voting_suggested_start' => now(),
        'voting_suggested_end' => now()->addDays(3),
    ]);
    
    // Lock voting immediately
    $this->lockVoting($actorId);
    
    // Log transition
    $this->logStateChange([
        'action' => 'nomination_completed',
        'from_state' => 'nomination',
        'to_state' => 'voting',
        'reason' => $reason,
        'actor_id' => $actorId,
    ]);
}
```

---

## State Info Helper

```php
// Returns color and display name for UI

public function stateInfo(): array
{
    return match ($this->current_state) {
        'administration' => [
            'state' => 'administration',
            'name' => 'Administration',
            'color' => 'blue',
            'icon' => '⚙️',
        ],
        'nomination' => [
            'state' => 'nomination',
            'name' => 'Nomination',
            'color' => 'green',
            'icon' => '📋',
        ],
        'voting' => [
            'state' => 'voting',
            'name' => 'Voting',
            'color' => 'purple',
            'icon' => '🗳️',
        ],
        'results_pending' => [
            'state' => 'results_pending',
            'name' => 'Results Pending',
            'color' => 'yellow',
            'icon' => '⏳',
        ],
        'results' => [
            'state' => 'results',
            'name' => 'Results Published',
            'color' => 'red',
            'icon' => '📊',
        ],
        default => [],
    };
}
```

---

## Authorization Integration

### ElectionPolicy

```php
// app/Policies/ElectionPolicy.php

public function manageSettings(User $user, Election $election): bool
{
    // Check state allows management
    if (!$election->allowsAction('manage_posts')) {
        return false;
    }
    
    // Check user is officer or owner
    return ElectionOfficer::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->whereIn('role', ['chief', 'deputy'])
        ->exists();
}

public function castVote(User $user, Election $election): bool
{
    // Check state allows voting
    if (!$election->allowsAction('cast_vote')) {
        return false;
    }
    
    // Check voting not locked
    if ($election->voting_locked) {
        return false;
    }
    
    // Check user is active member
    return $election->memberships()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->exists();
}
```

### Middleware

```php
// app/Http/Middleware/EnsureElectionState.php

public function handle($request, $next, $operation)
{
    $election = $request->route('election');
    
    // Delegate to state machine
    if (!$election->getStateMachine()->allowsAction($operation)) {
        abort(403, "This operation is not allowed in {$election->current_state} state");
    }
    
    return $next($request);
}
```

---

## Exception Handling

### InvalidTransitionException

```php
// app/Domain/Election/StateMachine/Exceptions/InvalidTransitionException.php

namespace App\Domain\Election\StateMachine\Exceptions;

class InvalidTransitionException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
```

### Usage

```php
try {
    $election->getStateMachine()->transitionTo('voting');
} catch (InvalidTransitionException $e) {
    Log::warning("Invalid transition: {$e->getMessage()}");
    return back()->withErrors(['error' => $e->getMessage()]);
}
```

---

## Audit Logging

### Dual-Write Pattern

Every state change is recorded in two places:

#### 1. JSON Append to Election Model

```php
$this->state_transitions_log = array_merge(
    $this->state_transitions_log ?? [],
    [
        [
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'action' => 'administration_completed',
            'actor_id' => $userId,
            'reason' => 'Ready for nominations',
            'timestamp' => now()->toIso8601String(),
        ]
    ]
);

// Capped at 200 entries (FIFO)
if (count($this->state_transitions_log) > 200) {
    array_shift($this->state_transitions_log);
}
```

#### 2. Dedicated ElectionAuditLog Record

```php
ElectionAuditLog::create([
    'election_id' => $this->id,
    'action' => 'administration_completed',
    'old_values' => ['administration_completed_at' => null],
    'new_values' => ['administration_completed_at' => now()],
    'user_id' => $userId,
    'ip_address' => request()->ip(),
    'session_id' => session()->getId(),
]);
```

---

## Testing

### State Transition Tests

```php
// tests/Feature/ElectionStateMachineTest.php

public function test_can_transition_from_administration_to_nomination()
{
    $election = Election::factory()
        ->withPosts(2)
        ->withVoters(5)
        ->create();
    
    $election->completeAdministration('Ready', $userId);
    
    $this->assertEquals('nomination', $election->current_state);
}

public function test_cannot_transition_without_prerequisites()
{
    $election = Election::factory()->create();
    
    $this->expectException(DomainException::class);
    $election->completeAdministration('Ready', $userId);
}

public function test_invalid_transition_throws_exception()
{
    $election = Election::factory()->create();
    
    $this->expectException(InvalidTransitionException::class);
    $election->getStateMachine()->transitionTo('results');
}
```

---

## Best Practices

✅ **Do**
- Use state machine for all transition validation
- Log all state changes with actor ID
- Check `allowsAction()` before business logic
- Memoize state machine instance per request
- Test state transitions in isolation

❌ **Don't**
- Bypass state machine for transitions
- Skip audit logging
- Hard-code state values in controllers
- Mix state checks across layers
- Assume state without deriving

---

**Document Version:** 1.0  
**Implementation Status:** Complete  
**Test Coverage:** 25 tests
