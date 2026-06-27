# Election Model State Machine Methods

## Location

`app/Models/Election.php`

## State Query Methods

### Getting Current State

#### `current_state` (Attribute)
```php
public function getCurrentStateAttribute(): string
```

**Returns**: One of: `'administration'`, `'nomination'`, `'voting'`, `'results_pending'`, `'results'`

**Example**:
```php
$election = Election::find($id);
$state = $election->current_state;  // 'voting'

// Use in conditionals
if ($election->current_state === 'voting') {
    // Voting is happening
}
```

**Note**: This is a computed attribute, not a stored column. Always returns current state based on timestamps.

---

#### `state_info` (Attribute)
```php
public function getStateInfoAttribute(): array
```

**Returns**: Array with state information:
```php
[
    'state'           => 'voting',          // State identifier
    'name'            => 'Voting',          // Display name
    'emoji'           => '🗳️',              // Phase emoji
    'color'           => 'purple',          // CSS color class
    'description'     => 'Cast votes',      // Phase description
    'phase_status'    => 'In Progress',     // Status label
]
```

**Example**:
```php
$election->load('organisation');
$stateInfo = $election->state_info;

echo $stateInfo['emoji'];         // 🗳️
echo $stateInfo['name'];          // Voting
echo $stateInfo['description'];   // Cast votes
```

**Use Case**: Perfect for UI rendering, email templates, audit logs

---

### Authorization Methods

#### `allowsAction(string $action): bool`
```php
public function allowsAction(string $action): bool
```

**Purpose**: Check if an operation is allowed in the current state

**Parameters**:
- `$action` (string): Operation identifier

**Returns**: `true` if action is allowed, `false` otherwise

**Supported Actions**:

| Action | Administration | Nomination | Voting | Results Pending | Results |
|--------|---|---|---|---|---|
| `manage_posts` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `import_voters` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `manage_committee` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `configure_election` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `apply_candidacy` | ❌ | ✅ | ❌ | ❌ | ❌ |
| `approve_candidacy` | ❌ | ✅ | ❌ | ❌ | ❌ |
| `view_candidates` | ❌ | ✅ | ❌ | ❌ | ❌ |
| `cast_vote` | ❌ | ❌ | ✅ | ❌ | ❌ |
| `verify_vote` | ❌ | ❌ | ✅ | ✅ | ✅ |
| `view_results` | ❌ | ❌ | ❌ | ❌ | ✅ |
| `download_receipt` | ❌ | ❌ | ❌ | ❌ | ✅ |

**Example**:
```php
if ($election->allowsAction('manage_posts')) {
    // Can manage posts in current state
    $post = $election->posts()->create(['name' => 'President']);
} else {
    abort(403, 'Cannot manage posts in ' . $election->state_info['name'] . ' phase');
}
```

**Middleware Usage**:
```php
// In routes
Route::middleware(['election.state:manage_posts'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});

// In middleware (automatic check)
if (!$election->allowsAction('manage_posts')) {
    abort(403, 'Operation not allowed');
}
```

---

## Phase Transition Methods

### Manual Transitions

#### `completeAdministration(string $reason, int $actorId): void`
```php
public function completeAdministration(string $reason, int $actorId): void
```

**Purpose**: Move from Administration to Nomination phase

**Parameters**:
- `$reason` (string): Why phase is being completed (for audit)
- `$actorId` (int): User ID performing the action

**Updates**:
- `administration_completed` = true
- `administration_completed_at` = now()
- `nomination_suggested_start` = now() + 1 day (if not set)
- `nomination_suggested_end` = now() + 3 days (if not set)

**Validations**:
- Must have at least one post
- Must have at least one voter
- Throws: `DomainException` if validation fails

**Logging**:
- Appends to `state_audit_log`
- Records actor, timestamp, reason

**Example**:
```php
$election = Election::find($id);

try {
    $election->completeAdministration(
        reason: 'All posts and voters configured',
        actorId: auth()->id()
    );
    
    return redirect()->back()->with('success', 'Administration phase completed');
} catch (DomainException $e) {
    return redirect()->back()->withErrors(['error' => $e->getMessage()]);
}
```

---

#### `completeNomination(string $reason, int $actorId): void`
```php
public function completeNomination(string $reason, int $actorId): void
```

**Purpose**: Move from Nomination to Voting phase

**Parameters**:
- `$reason` (string): Why phase is being completed
- `$actorId` (int): User ID performing the action

**Updates**:
- `nomination_completed` = true
- `nomination_completed_at` = now()
- `voting_starts_at` = now() + 1 day (if not set)
- `voting_ends_at` = now() + 4 days (if not set)

**Validations**:
- No pending candidacies
- At least one approved candidate for each post
- Throws: `DomainException` if validation fails

**Logging**:
- Appends to `state_audit_log`

**Example**:
```php
$election = Election::find($id);

// Check if can complete
$pending = $election->candidacies()
    ->where('status', 'pending')
    ->count();

if ($pending > 0) {
    return redirect()->back()->withErrors([
        'error' => "Cannot complete nomination with $pending pending candidacies"
    ]);
}

try {
    $election->completeNomination(
        reason: 'All candidates approved',
        actorId: auth()->id()
    );
    
    return redirect()->back()->with('success', 'Nomination phase completed');
} catch (DomainException $e) {
    return redirect()->back()->withErrors(['error' => $e->getMessage()]);
}
```

---

#### `forceCloseNomination(string $reason, int $actorId): void`
```php
public function forceCloseNomination(string $reason, int $actorId): void
```

**Purpose**: Force-close nomination phase, auto-reject pending candidates

**Parameters**:
- `$reason` (string): Why nomination is being force-closed
- `$actorId` (int): User ID performing the action

**Updates**:
- `nomination_completed` = true
- `nomination_completed_at` = now()
- All pending candidacies are rejected
- `voting_starts_at` = now() + 1 day (if not set)
- `voting_ends_at` = now() + 4 days (if not set)

**Validations**:
- Must not be in voting or later phases
- Throws: `DomainException` if validation fails

**Example**:
```php
$election = Election::find($id);

if ($election->current_state !== 'nomination') {
    return redirect()->back()->withErrors(['error' => 'Can only force-close during nomination']);
}

try {
    $election->forceCloseNomination(
        reason: 'Admin override due to insufficient candidates',
        actorId: auth()->id()
    );
    
    return redirect()->back()->with('success', 'Nomination phase force-closed');
} catch (DomainException $e) {
    return redirect()->back()->withErrors(['error' => $e->getMessage()]);
}
```

---

### Automatic Transitions

#### `results_published_at` = now()
```php
// Not a method - direct assignment
$election->results_published_at = now();
$election->save();
// Automatically transitions to 'results' state
```

**Purpose**: Publish results (move to Results phase)

**Example**:
```php
$election = Election::find($id);

if ($election->current_state !== 'results_pending') {
    return redirect()->back()->withErrors(['error' => 'Can only publish from results_pending']);
}

$election->results_published_at = now();
$election->save();

return redirect()->back()->with('success', 'Results published');
```

---

## Validation Methods

#### `validateTimeline(): void`
```php
public function validateTimeline(): void
```

**Purpose**: Validate that all phase dates are in chronological order

**Validations**:
```
administration_suggested_end ≤ nomination_suggested_start
nomination_suggested_start   ≤ nomination_suggested_end
nomination_suggested_end     ≤ voting_starts_at
voting_starts_at             ≤ voting_ends_at
```

**Throws**: `InvalidHierarchyException` if validation fails

**Called Automatically**: On model save (via boot hook)

**Example**:
```php
$election->voting_starts_at = now();
$election->voting_ends_at = now()->subDay();  // WRONG - end before start

try {
    $election->save();  // validateTimeline() called automatically
} catch (InvalidHierarchyException $e) {
    // Caught: voting_starts_at must be before voting_ends_at
}
```

---

## Logging Methods

#### `logStateChange(string $action, array $metadata, int $actorId): void`
```php
public function logStateChange(
    string $action,
    array $metadata,
    int $actorId
): void
```

**Purpose**: Append a state change record to audit log

**Parameters**:
- `$action` (string): Action name (e.g., 'completeAdministration')
- `$metadata` (array): Additional context data
- `$actorId` (int): User ID performing action

**Updates**:
- Appends to `state_audit_log` JSON array
- Keeps last 200 entries (oldest removed if exceeded)

**Logged Data**:
```php
[
    'timestamp' => '2026-04-21T10:30:00Z',
    'actor_id' => 123,
    'action' => 'completeAdministration',
    'old_state' => 'administration',
    'new_state' => 'nomination',
    'reason' => 'Manual completion',
    'metadata' => [...]
]
```

---

## Constants

```php
class Election extends Model
{
    const STATE_ADMINISTRATION = 'administration';
    const STATE_NOMINATION = 'nomination';
    const STATE_VOTING = 'voting';
    const STATE_RESULTS_PENDING = 'results_pending';
    const STATE_RESULTS = 'results';
}
```

**Usage**:
```php
if ($election->current_state === Election::STATE_VOTING) {
    // Is voting
}
```

---

## Fillable Attributes

Added to `$fillable` array for mass assignment:

```php
protected $fillable = [
    // ... existing attributes ...
    'administration_suggested_start',
    'administration_suggested_end',
    'administration_completed',
    'administration_completed_at',
    'nomination_suggested_start',
    'nomination_suggested_end',
    'nomination_completed',
    'nomination_completed_at',
    'voting_starts_at',
    'voting_ends_at',
    'allow_auto_transition',
    'auto_transition_grace_days',
    'state_audit_log',
];
```

---

## Casts

```php
protected $casts = [
    'administration_suggested_start' => 'datetime',
    'administration_suggested_end' => 'datetime',
    'administration_completed' => 'boolean',
    'administration_completed_at' => 'datetime',
    'nomination_suggested_start' => 'datetime',
    'nomination_suggested_end' => 'datetime',
    'nomination_completed' => 'boolean',
    'nomination_completed_at' => 'datetime',
    'voting_starts_at' => 'datetime',
    'voting_ends_at' => 'datetime',
    'allow_auto_transition' => 'boolean',
];
```

---

## Helper Methods (from tests)

These methods are useful for checking phase state:

```php
// Get state order number
$phaseOrder = ['administration', 'nomination', 'voting', 'results_pending', 'results'];

// Check if phase is completed (past)
$isCompleted = array_search($election->current_state, $phaseOrder) 
    > array_search('administration', $phaseOrder);

// Check if phase is upcoming (future)
$isUpcoming = array_search($election->current_state, $phaseOrder) 
    < array_search('voting', $phaseOrder);
```

---

## Usage Examples

### Complete Workflow

```php
// Create election
$election = Election::create([
    'name' => 'Board Elections 2026',
    'organisation_id' => $org->id,
    'type' => 'real',
]);

// Current state is 'administration'
echo $election->current_state;  // 'administration'

// Add posts and voters
$election->posts()->create(['name' => 'President']);
$election->electionMemberships()->create(['user_id' => 1]);

// Complete administration
$election->completeAdministration('Setup complete', auth()->id());
echo $election->current_state;  // 'nomination'

// Complete nomination
$election->completeNomination('All candidates approved', auth()->id());
echo $election->current_state;  // 'voting'

// Wait for voting window to close...
// State automatically becomes 'results_pending'

// Publish results
$election->results_published_at = now();
$election->save();
echo $election->current_state;  // 'results'
```

---

## References

- File: `app/Models/Election.php`
- Tests: `tests/Feature/ElectionStateMachineTest.php`
- Middleware: `app/Http/Middleware/EnsureElectionState.php`
- See [ARCHITECTURE.md](ARCHITECTURE.md) for patterns
- See [STATES.md](STATES.md) for state definitions

---

**All methods are tested** in `ElectionStateMachineTest.php` (25 tests, all passing)
