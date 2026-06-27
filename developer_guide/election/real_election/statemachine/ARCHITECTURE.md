# State Machine Architecture

## Design Philosophy

The state machine is built on **derived state** rather than stored state. This ensures:

- **Single Source of Truth**: State is always calculated from timestamps
- **No Inconsistency**: Cannot have contradictory state data
- **Auditability**: State transitions are implicit and verifiable
- **Simplicity**: No complex state transition tables

## Core Patterns

### 1. Derived State Pattern

**State is NEVER stored, ALWAYS derived.**

```php
// ❌ WRONG - storing state
$election->state = 'voting';
$election->save();

// ✅ RIGHT - deriving state
$state = $election->current_state;  // Calculated from timestamps
```

**Benefits**:
- Eliminates sync problems
- Prevents invalid state combinations
- Audit trail is implicit in timestamps

### 2. Computed Attribute Pattern

State is a computed attribute that reads from timestamps:

```php
// In Election model
#[Attribute]
public function getCurrentStateAttribute(): string
{
    // Reads: results_published_at, voting_starts_at/ends_at,
    //        nomination_completed, administration_completed
    // Never modifies database
    // Always returns current state
}
```

### 3. Timeline Validation Pattern

Before allowing transitions, validate the timeline is chronologically sound:

```php
// In Election model
protected function validateTimeline(): void
{
    if ($this->administration_suggested_end && $this->nomination_suggested_start) {
        if ($this->administration_suggested_end > $this->nomination_suggested_start) {
            throw new InvalidHierarchyException('...');
        }
    }
}
```

### 4. Grace Period Auto-Transition Pattern

Administration and Nomination phases auto-complete after their suggested end date + grace period:

```php
// In ProcessElectionGracePeriods command
$elections = Election::where('allow_auto_transition', true)
    ->where('administration_completed', false)
    ->where('administration_suggested_end', '<', now()->subDays(7))
    ->get();

foreach ($elections as $election) {
    $election->completeAdministration('Auto-transition via grace period', SYSTEM_ACTOR_ID);
}
```

**Why Grace Period?**
- Gives admins time to manually complete if needed
- Prevents accidental auto-transition
- Configurable per election (`auto_transition_grace_days`)

### 5. Action Authorization Pattern

Instead of role-based authorization, use state-based:

```php
// ❌ WRONG - only checks role
if (auth()->user()->can('manage_posts')) { }

// ✅ RIGHT - checks both role AND state
if (auth()->user()->can('manage_posts') && $election->allowsAction('manage_posts')) { }
```

**Method**: `allowsAction(string $operation): bool`

```php
public function allowsAction(string $action): bool
{
    $allowed = [
        'administration'  => ['manage_posts', 'import_voters', 'manage_committee'],
        'nomination'      => ['apply_candidacy', 'approve_candidacy', 'view_candidates'],
        'voting'          => ['cast_vote', 'verify_vote'],
        'results_pending' => ['verify_vote'],
        'results'         => ['view_results', 'verify_vote', 'download_receipt'],
    ];
    return in_array($action, $allowed[$this->current_state] ?? []);
}
```

### 6. Middleware Authorization Pattern

Routes are protected by state-aware middleware:

```php
// In routes
Route::middleware(['election.state:manage_posts'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});

// In EnsureElectionState middleware
public function handle(Request $request, Closure $next, string $operation): mixed
{
    $election = $request->route('election');
    
    if (!$election->allowsAction($operation)) {
        abort(403, sprintf(
            'Operation "%s" is not allowed during "%s" phase.',
            $operation,
            $election->state_info['name']
        ));
    }
    
    return $next($request);
}
```

**Benefits**:
- Centralized authorization
- Consistent across all routes
- Easy to audit

## Database Architecture

### State-Related Columns

The `elections` table has columns for each phase:

```
Administration Phase:
  - administration_suggested_start (nullable timestamp)
  - administration_suggested_end (nullable timestamp)
  - administration_completed (boolean, default false)
  - administration_completed_at (nullable timestamp)

Nomination Phase:
  - nomination_suggested_start (nullable timestamp)
  - nomination_suggested_end (nullable timestamp)
  - nomination_completed (boolean, default false)
  - nomination_completed_at (nullable timestamp)

Voting Phase:
  - voting_starts_at (nullable timestamp)
  - voting_ends_at (nullable timestamp)

Results Phase:
  - results_published_at (nullable timestamp)

Configuration:
  - allow_auto_transition (boolean, default true)
  - auto_transition_grace_days (integer, default 7)
  - state_audit_log (json, nullable)
```

### State Transition Rules

```
START → Administration (initialization)
  ↓ (when: administration_completed = true)
Nomination
  ↓ (when: nomination_completed = true)
Voting (when: now() between voting_starts_at and voting_ends_at)
  ↓ (when: now() > voting_ends_at)
Results Pending
  ↓ (when: results_published_at is set)
Results (final)
```

## Key Methods

### State Queries

```php
// Get current state
$election->current_state  // 'administration', 'nomination', etc.

// Get state info for UI
$election->state_info     // Array with name, color, description, emoji

// Check if action allowed
$election->allowsAction('manage_posts')  // boolean
```

### Phase Transitions

```php
// Complete administration (moves to nomination)
$election->completeAdministration($reason, $actorId);

// Complete nomination (moves to voting)
$election->completeNomination($reason, $actorId);

// Force-close nomination (rejects pending, moves to voting)
$election->forceCloseNomination($reason, $actorId);

// Publish results (moves to results)
$election->results_published_at = now();
$election->save();
```

### Logging

```php
// All state changes are logged
protected function logStateChange(
    string $action,
    array $metadata,
    int $actorId
): void {
    // Appends to state_audit_log JSON array
    // Keeps last 200 entries
}
```

## Security Considerations

1. **State Derivation is Immutable**: State cannot be directly set, preventing bypass attempts
2. **Middleware Enforcement**: All state-sensitive routes use `election.state:operation` middleware
3. **Timestamp Validation**: Ensures votes only happen within voting window
4. **Audit Trail**: All transitions logged to JSON column for forensics

## Performance Considerations

1. **Computed Attribute**: State calculation is O(1) - just reads timestamps
2. **No Extra Queries**: State calculation doesn't require additional database queries
3. **Caching**: State info (color, name, emoji) could be cached if needed
4. **Batch Operations**: Grace period processing uses efficient batch queries

## Testing Strategy

The state machine is tested with:

- **Unit Tests**: State derivation logic (`getCurrentStateAttribute()`)
- **Integration Tests**: Phase transitions and database state
- **Authorization Tests**: `allowsAction()` for each phase
- **Timeline Tests**: Validation rules
- **HTTP Tests**: Route protection with middleware
- **Edge Cases**: Grace periods, concurrent operations, edge timestamps

See `TESTING.md` for comprehensive test coverage.

## Future Extensions

### Potential Enhancements

1. **State Machine Diagram Export**: Generate visual timeline
2. **Webhook Notifications**: Trigger integrations on state change
3. **Custom Phase Duration**: Allow variable phase lengths
4. **Parallel Phases**: Support overlapping phases if needed
5. **Rollback**: Ability to reverse transitions (careful with voting!)

### Backward Compatibility

The old `status` field (`planned/active/completed/archived`) is preserved:

- Not used by state machine
- Kept for existing code compatibility
- Can be removed in future major version

## Related Architecture

- **Election Model**: `app/Models/Election.php`
- **Middleware**: `app/Http/Middleware/EnsureElectionState.php`
- **Controllers**: `app/Http/Controllers/Election/ElectionManagementController.php`
- **Frontend**: `resources/js/Pages/Election/Partials/StateMachinePanel.vue`
- **Tests**: `tests/Feature/ElectionStateMachineTest.php`

## References

- See [STATES.md](STATES.md) for phase definitions
- See [MODELS.md](MODELS.md) for method signatures
- See [MIDDLEWARE.md](MIDDLEWARE.md) for route protection
- See [TESTING.md](TESTING.md) for test patterns
- See [EXAMPLES.md](EXAMPLES.md) for code samples

---

**Design Principles**:
1. Derived state (never stored)
2. Immutable transitions (only forward movement)
3. Strict voting window (no overrides)
4. Comprehensive audit trail
5. Clear authorization boundaries
