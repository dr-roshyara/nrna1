# Developer API Reference

## ElectionLifecycle Facade

The main API for working with election state. Located at `App\Application\Election\Facades\ElectionLifecycle`.

### Getting Started

```php
use App\Application\Election\Facades\ElectionLifecycle;

// Get lifecycle for an election
$lifecycle = ElectionLifecycle::of($election);

// Now use the API...
$state = $lifecycle->state();
$canVote = $lifecycle->canVote();
```

---

## API Methods

### State Information

#### `state(): ElectionLifecycleState`
Get the current election state enum value.

**Returns:** `ElectionLifecycleState` enum with 10 values

**Example:**
```php
$lifecycle = ElectionLifecycle::of($election);
$state = $lifecycle->state();

echo $state->value;  // 'voting_active'
echo $state->label(); // 'Voting Active'

if ($state->value === 'voting_active') {
    // Can accept votes
}
```

**Available States:**
- `draft`
- `submitted_for_approval`
- `approved`
- `rejected`
- `setup`
- `ready_for_voting`
- `voting_active`
- `counting`
- `results_published`
- `archived`

---

#### `snapshot(): ElectionLifecycleSnapshot`
Get complete immutable snapshot of election lifecycle state.

**Returns:** `ElectionLifecycleSnapshot` object containing:
- `state` — Current state
- `canVote` — Can voting happen?
- `canEdit` — Can election be edited?
- `canManageVoters` — Can voters be added/removed?
- `canPublishResults` — Can results be published?
- `canEditTimeline` — Can dates be edited?
- `allowedActions` — Array of allowed transition actions
- `blockedReason` — Why election is blocked (if any)

**Example:**
```php
$snapshot = ElectionLifecycle::of($election)->snapshot();

Log::info('Election snapshot', [
    'state' => $snapshot->state->value,
    'can_vote' => $snapshot->canVote,
    'allowed_actions' => $snapshot->allowedActions,
    'blocked_reason' => $snapshot->blockedReason,
]);
```

---

### Capability Checks

#### `canVote(): bool`
Can voting currently happen in this election?

**Returns:** `true` if voting is active and accepting ballots

**Example:**
```php
if (ElectionLifecycle::of($election)->canVote()) {
    return Inertia::render('Voting/Ballot', [...]);
} else {
    return Inertia::render('Voting/Unavailable', [...]);
}
```

**Conditions:**
- ✅ State is `voting_active`
- ✅ Voting window is open (now between start/end)
- ✅ Not archived

---

#### `canEdit(): bool`
Can the election configuration be edited?

**Returns:** `true` if election is still in editable states

**Example:**
```php
public function store(Request $request, Election $election)
{
    if (!ElectionLifecycle::of($election)->canEdit()) {
        return back()->with('error', 'Cannot edit after voting starts');
    }
    // ... update election
}
```

**Editable States:**
- `draft`
- `approved`
- `setup`
- `ready_for_voting` (if voting hasn't started)

---

#### `canManageVoters(): bool`
Can voters be added/removed/approved?

**Returns:** `true` if in setup phase or before voting starts

**Example:**
```php
if (ElectionLifecycle::of($election)->canManageVoters()) {
    // Show import voters button
}
```

---

#### `canPublishResults(): bool`
Can results be published?

**Returns:** `true` if voting is closed and results not yet published

**Example:**
```php
if (ElectionLifecycle::of($election)->canPublishResults()) {
    return redirect()->route('elections.publish', $election);
}
```

---

#### `canEditTimeline(): bool`
Can voting dates and phase timelines be edited?

**Returns:** `true` if election hasn't entered terminal state

**Example:**
```php
public function updateDates(Request $request, Election $election)
{
    if (!ElectionLifecycle::of($election)->canEditTimeline()) {
        return back()->with('error', 'Cannot edit timeline now');
    }
    // ... update dates
}
```

---

#### `isActive(): bool`
Is election currently in voting phase?

**Returns:** `true` if state is `voting_active`

**Example:**
```php
$activeElections = Election::all()
    ->filter(fn($e) => ElectionLifecycle::of($e)->isActive())
    ->values();
```

---

#### `isTerminal(): bool`
Has election reached a terminal state (no more transitions)?

**Returns:** `true` if state is `results_published`, `rejected`, or `archived`

**Example:**
```php
if (ElectionLifecycle::of($election)->isTerminal()) {
    // Don't allow further actions
    return back()->with('error', 'Election is archived');
}
```

---

### Transition Management

#### `allowedActions(): array`
Get list of all allowed transition actions in current state.

**Returns:** Array of action names as strings

**Example:**
```php
$actions = ElectionLifecycle::of($election)->allowedActions();

// Array: ['open_voting', 'close_voting', 'lock_voting']

foreach ($actions as $action) {
    echo "Button: {$action}";
}
```

---

#### `canTransitionTo(string $action): bool`
Check if a specific action is allowed.

**Parameters:**
- `$action` (string) — Action name like `'open_voting'`, `'close_voting'`

**Returns:** `true` if action is in allowed actions

**Example:**
```php
public function openVoting(Election $election)
{
    if (!ElectionLifecycle::of($election)->canTransitionTo('open_voting')) {
        return back()->with('error', 'Cannot open voting now');
    }
    
    // Perform transition
    $election->transitionTo(
        Transition::manual('open_voting', auth()->id(), 'Opened by officer')
    );
}
```

---

#### `blockedReason(): ?string`
Get the reason why election is blocked (if any).

**Returns:** Human-readable reason string or `null` if not blocked

**Example:**
```php
$reason = ElectionLifecycle::of($election)->blockedReason();
if ($reason) {
    // Show user: "Cannot proceed: {$reason}"
    echo "Blocked: {$reason}";
} else {
    // All good, can proceed
}
```

**Sample Reasons:**
- "Nomination phase has not been completed."
- "No candidates have been registered."
- "There are pending candidacy applications."

---

### Transition Execution

#### `transitionTo(Transition $transition): void`
Execute a state transition with validation, authorization, and side effects.

**Parameters:**
- `$transition` (Transition) — Transition object built by `Transition::manual()` or `Transition::auto()`

**Returns:** Void (modifies election in place)

**Throws:**
- `InvalidTransitionException` — If transition not allowed
- `DomainException` — If preconditions not met

**Example:**
```php
// ✅ Correct usage
try {
    $election->transitionTo(
        Transition::manual(
            action: 'open_voting',
            actorId: auth()->id(),
            reason: 'Opened voting as requested by chief'
        )
    );
    return back()->with('success', 'Voting opened');
} catch (InvalidTransitionException $e) {
    return back()->with('error', 'Cannot open: ' . $e->getMessage());
}
```

---

## Transition Building

### Transition::manual()
Create a manually triggered transition (officer action).

```php
Transition::manual(
    action: 'open_voting',           // Required: action name
    actorId: auth()->id(),           // Required: who triggered it
    reason: 'Opened by chief',       // Optional: human-readable reason
    metadata: ['ip' => request()->ip()] // Optional: extra data
)
```

**Example:**
```php
$election->transitionTo(
    Transition::manual(
        action: 'close_voting',
        actorId: auth()->id(),
        reason: 'Closed early per officer request'
    )
);
```

---

### Transition::auto()
Create an automatically triggered transition (time-based, system).

```php
Transition::auto(
    action: 'close_voting',          // Required: action name
    reason: 'Auto-closed at end time' // Optional: system reason
)
```

**Example:**
```php
// In a scheduled job
if (ElectionLifecycle::of($election)->canTransitionTo('close_voting')) {
    $election->transitionTo(
        Transition::auto('close_voting', 'Auto-closed at scheduled end time')
    );
}
```

---

## Usage in Controllers

### Example: Open Voting Endpoint

```php
public function openVoting(Election $election): RedirectResponse
{
    // 1. Check authorization (done by route middleware)
    $this->authorize('manageSettings', $election);
    
    // 2. Check capability
    $lifecycle = ElectionLifecycle::of($election);
    if (!$lifecycle->canTransitionTo('open_voting')) {
        $reason = $lifecycle->blockedReason() 
            ?? 'Cannot open voting in current state';
        return back()->with('error', $reason);
    }
    
    // 3. Execute transition
    try {
        $election->transitionTo(
            Transition::manual(
                action: 'open_voting',
                actorId: auth()->id(),
                reason: 'Opened voting as requested',
                metadata: ['ip' => request()->ip()]
            )
        );
        return back()->with('success', 'Voting opened successfully');
        
    } catch (InvalidTransitionException $e) {
        Log::warning('Voting open failed', [
            'election_id' => $election->id,
            'error' => $e->getMessage()
        ]);
        return back()->with('error', 'Operation failed: ' . $e->getMessage());
    }
}
```

---

## Usage in Policies

### Example: Election Management Policy

```php
class ElectionPolicy
{
    public function manageSettings(User $user, Election $election): bool
    {
        // Only officers in the election can manage
        $isOfficer = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'status' => 'active'
        ])->exists();
        
        if (!$isOfficer) {
            return false;
        }
        
        // And only if election is still editable
        return ElectionLifecycle::of($election)->canEdit();
    }
    
    public function publishResults(User $user, Election $election): bool
    {
        // Must be chief officer
        $isChief = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'role' => 'chief'
        ])->exists();
        
        if (!$isChief) {
            return false;
        }
        
        // And results must be publishable
        return ElectionLifecycle::of($election)->canPublishResults();
    }
}
```

---

## Usage in Vue Components

### Example: Show Buttons Based on State

```vue
<template>
  <div class="election-actions">
    <!-- Show different buttons based on allowed actions -->
    <button 
      v-if="stateMachine.allowedActions.includes('open_voting')"
      @click="openVoting"
      class="btn btn-success"
    >
      Open Voting
    </button>
    
    <button 
      v-if="stateMachine.allowedActions.includes('close_voting')"
      @click="closeVoting"
      class="btn btn-warning"
    >
      Close Voting
    </button>
    
    <button 
      v-if="stateMachine.allowedActions.includes('publish_results')"
      @click="publishResults"
      class="btn btn-primary"
    >
      Publish Results
    </button>
    
    <!-- Show blocked reason if election is blocked -->
    <div v-if="stateMachine.blockedReason" class="alert alert-danger">
      Cannot proceed: {{ stateMachine.blockedReason }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    stateMachine: {
      type: Object,
      required: true
      // Contains: currentState, allowedActions, blockedReason
    }
  },
  methods: {
    openVoting() {
      this.$inertia.post(`/elections/${this.election.id}/open-voting`);
    },
    closeVoting() {
      this.$inertia.post(`/elections/${this.election.id}/close-voting`);
    },
    publishResults() {
      this.$inertia.post(`/elections/${this.election.id}/publish-results`);
    }
  }
}
</script>
```

---

## Usage in Tests

### Example: TDD Pattern

```php
class VotingButtonsStateMachineTest extends TestCase
{
    public function test_open_voting_transitions_to_voting_active(): void
    {
        // 1. Create election with facts set (not by state column)
        $election = ElectionScenarioFactory::configurationComplete($this->org);
        
        // 2. Verify initial state
        $this->assertEquals(
            'ready_for_voting',
            ElectionLifecycle::of($election)->state()->value
        );
        
        // 3. Verify action is allowed
        $this->assertTrue(
            ElectionLifecycle::of($election)->canTransitionTo('open_voting')
        );
        
        // 4. Execute transition
        $this->actingAs($this->officer);
        $response = $this->post(
            route('elections.open-voting', ['election' => $election->slug])
        );
        
        // 5. Verify transition succeeded
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        
        // 6. Verify new state
        $election->refresh();
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($election)->state()->value
        );
    }
}
```

---

## Common Patterns

### Pattern 1: Check State and Act

```php
$lifecycle = ElectionLifecycle::of($election);

match ($lifecycle->state()->value) {
    'draft' => $this->handleDraft($election),
    'ready_for_voting' => $this->handleReady($election),
    'voting_active' => $this->handleVoting($election),
    'counting' => $this->handleCounting($election),
    'results_published' => $this->handleResults($election),
    default => $this->handleOther($election),
};
```

### Pattern 2: Guard Against Wrong State

```php
// Early return if not in correct state
if (!ElectionLifecycle::of($election)->canVote()) {
    throw new DomainException('Election is not accepting votes');
}

// Safe to proceed
$this->recordVote($election, $ballot);
```

### Pattern 3: Validate Before Transition

```php
$lifecycle = ElectionLifecycle::of($election);

// Check preconditions
if ($lifecycle->blockedReason()) {
    return back()->with('error', $lifecycle->blockedReason());
}

// Check authorization
if (!$lifecycle->canTransitionTo('open_voting')) {
    return back()->with('error', 'Action not allowed');
}

// Execute
$election->transitionTo(Transition::manual('open_voting', ...));
```

### Pattern 4: Batch Operations on Multiple Elections

```php
$elections = Election::all();

foreach ($elections as $election) {
    $state = ElectionLifecycle::of($election)->state();
    
    // Group by state
    match ($state->value) {
        'voting_active' => $this->monitorVoting($election),
        'counting' => $this->prepareResults($election),
        'ready_for_voting' => $this->scheduleOpening($election),
    };
}
```

---

## Error Handling

### InvalidTransitionException

Thrown when trying to transition in wrong state or without permission.

```php
try {
    $election->transitionTo(Transition::manual(...));
} catch (InvalidTransitionException $e) {
    Log::warning('Transition failed', ['error' => $e->getMessage()]);
    return back()->with('error', 'Cannot perform this action: ' . $e->getMessage());
}
```

### DomainException

Thrown when business logic constraints are violated.

```php
try {
    $election->transitionTo(...);
} catch (DomainException $e) {
    // More serious — log it
    Log::error('Domain constraint violated', [
        'error' => $e->getMessage(),
        'election_id' => $election->id
    ]);
    return back()->with('error', 'Business logic constraint failed');
}
```

---

## Performance Notes

### Snapshot Computation

The first call to `ElectionLifecycle::of($election)` computes the snapshot (evaluates 10 derivation rules). This is cached within the same object instance.

**Good:**
```php
$lifecycle = ElectionLifecycle::of($election);
$canVote = $lifecycle->canVote();
$state = $lifecycle->state();
$actions = $lifecycle->allowedActions();
// All use same snapshot (no re-computation)
```

**Less Efficient:**
```php
if (ElectionLifecycle::of($election)->canVote()) { ... }
if (ElectionLifecycle::of($election)->canTransitionTo('open')) { ... }
// Creates 2 separate snapshots (both computed)
```

### Query Optimization

For bulk operations on many elections, derive state in memory rather than querying:

**Good:**
```php
$elections = Election::all();
$activeElections = $elections
    ->filter(fn($e) => ElectionLifecycle::of($e)->isActive())
    ->values();
```

**Avoid:**
```php
// Don't try to filter by state column in SQL
$active = Election::where('state', 'voting_active')->get();
// Column is cache, not source of truth
```

---

**Last Updated:** May 21, 2026
**API Version:** 1.0 (Stable)
