# ElectionLifecycle Facade: Complete API Reference

**Comprehensive documentation of all methods in the ElectionLifecycle facade and supporting classes.**

---

## Table of Contents

1. [ElectionLifecycle Facade](#electionlifecycle-facade)
2. [ElectionLifecycleSnapshot](#electionlifecyclesnapshot)
3. [ElectionLifecycleState Enum](#electionlifecyclestate-enum)
4. [Supporting Services](#supporting-services)

---

## ElectionLifecycle Facade

The main entry point for all election lifecycle queries. Use this in all controllers.

### Location
```php
App\Application\Election\Facades\ElectionLifecycle
```

### Factory Methods

#### `ElectionLifecycle::of(Election $election): self`

Wrap an election with its lifecycle context.

```php
use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;

$election = Election::findOrFail(1);
$lifecycle = ElectionLifecycle::of($election);
```

**Returns:** ElectionLifecycle instance with computed snapshot

**Computation Time:** ~1-2ms (runs full state derivation)

**Usage Pattern:**
```php
// In controllers
$lifecycle = ElectionLifecycle::of($election);
if ($lifecycle->canVote()) {
    // Process vote
}
```

---

#### `ElectionLifecycle::withSnapshot(Election $election, ElectionLifecycleSnapshot $snapshot): self`

Create facade with pre-computed snapshot (optimization for batch operations).

```php
$election = Election::findOrFail(1);
$engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
$snapshot = $engine->compute($election);

// Create facade with existing snapshot (no recomputation)
$lifecycle = ElectionLifecycle::withSnapshot($election, $snapshot);
```

**Use When:**
- Processing many elections (avoid recomputing snapshot for each)
- Snapshot already computed elsewhere
- Performance-critical code path

**Example - Batch Processing:**
```php
$elections = Election::where('state', 'voting_active')->get();

// Compute all snapshots once
$snapshots = $elections->mapWithKeys(function($election) {
    return [$election->id => ElectionLifecycle::of($election)->snapshot()];
});

// Use precomputed snapshots for batch operations
foreach ($elections as $election) {
    $lifecycle = ElectionLifecycle::withSnapshot($election, $snapshots[$election->id]);
    $this->processElection($election, $lifecycle);
}
```

---

### Snapshot Access Methods

#### `snapshot(): ElectionLifecycleSnapshot`

Get the complete lifecycle snapshot.

```php
$snapshot = ElectionLifecycle::of($election)->snapshot();

// $snapshot contains all state information:
// - state: Current lifecycle state
// - canVote: Whether voting is allowed
// - canEdit: Whether editing is allowed
// - canManageVoters: Whether voter management is allowed
// - canPublishResults: Whether results can be published
// - isLocked: Whether election is in terminal state
// - blockedReason: Why election is blocked (if any)
// - allowedActions: List of allowed next actions
```

**Returns:** `ElectionLifecycleSnapshot` (immutable value object)

**Usage:**
```php
$snapshot = $lifecycle->snapshot();

// Pass to view for complex rendering
return view('election.dashboard', ['snapshot' => $snapshot]);

// Store for logging
Log::info('Election state', $snapshot->toArray());
```

---

#### `state(): ElectionLifecycleState`

Get the current canonical lifecycle state.

```php
$state = ElectionLifecycle::of($election)->state();
// Returns: ElectionLifecycleState enum (Draft, Setup, ReadyForVoting, VotingActive, etc.)
```

**Returns:** `ElectionLifecycleState` enum

**Possible Values:**
- `Draft` — Setup not started
- `Setup` — Configuration in progress
- `ReadyForVoting` — Awaiting voting window
- `VotingActive` — Voting currently open
- `Counting` — Results being tallied
- `ResultsPublished` — Results public
- `Archived` — Election complete

**Usage:**
```php
match($lifecycle->state()) {
    ElectionLifecycleState::Draft => 'Setup in progress...',
    ElectionLifecycleState::VotingActive => 'Vote now!',
    ElectionLifecycleState::ResultsPublished => 'Results available',
    default => 'Election not currently active',
}
```

---

### Permission Check Methods

#### `canVote(): bool`

Check if voting is currently allowed.

```php
if (ElectionLifecycle::of($election)->canVote()) {
    // Voting window is open AND
    // Voting state is VotingActive AND
    // All setup requirements met
}
```

**Returns:** `bool`

**True When:**
- State is `VotingActive`
- Voting window is open (now between voting_starts_at and voting_ends_at)
- All administration requirements completed
- Voting is not locked

**Usage - Vote Submission:**
```php
public function submitVote(Request $request)
{
    $election = Election::findOrFail($request->election_id);
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canVote()) {
        return back()->withError(
            'Voting not allowed: ' . $lifecycle->blockedReason()
        );
    }
    
    // Process vote
}
```

---

#### `canEdit(): bool`

Check if election configuration can be edited.

```php
if (ElectionLifecycle::of($election)->canEdit()) {
    // Can modify posts, candidates, voters, etc.
}
```

**Returns:** `bool`

**True When:**
- State is `Draft` or `Setup` (before voting starts)
- Election is not locked

**Usage - Edit Election:**
```php
public function edit(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canEdit()) {
        return abort(403, 'Cannot edit election after voting begins');
    }
    
    return view('election.edit', ['election' => $election]);
}
```

---

#### `canManageVoters(): bool`

Check if voters can be added/removed/modified.

```php
if (ElectionLifecycle::of($election)->canManageVoters()) {
    // Can import voters, assign voting codes, etc.
}
```

**Returns:** `bool`

**True When:**
- State allows voter management (Draft, Setup, ReadyForVoting)
- Not in terminal state

**Usage - Voter Management:**
```php
public function importVoters(Request $request)
{
    $election = Election::findOrFail($request->election_id);
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canManageVoters()) {
        return back()->withError('Cannot modify voters after voting begins');
    }
    
    // Process voter import
}
```

---

#### `canPublishResults(): bool`

Check if election results can be published.

```php
if (ElectionLifecycle::of($election)->canPublishResults()) {
    // Can publish results to members
}
```

**Returns:** `bool`

**True When:**
- Voting has ended
- All votes counted
- Results are ready to publish

**Usage - Results Publication:**
```php
public function publishResults(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canPublishResults()) {
        return back()->withError(
            'Results cannot be published yet. ' . $lifecycle->blockedReason()
        );
    }
    
    // Publish results to members
}
```

---

### State Classification Methods

#### `isTerminal(): bool`

Check if election is in a terminal (final) state.

```php
if (ElectionLifecycle::of($election)->isTerminal()) {
    // No further changes possible - election is complete
}
```

**Returns:** `bool`

**True When:**
- State is `Archived`

**Usage:**
```php
// Prevent archival operations on active elections
if (!$lifecycle->isTerminal()) {
    $election->can_be_deleted = true;
}
```

---

#### `isInSetup(): bool`

Check if election is in setup phase (Draft or Setup).

```php
if (ElectionLifecycle::of($election)->isInSetup()) {
    // Configuration still possible
}
```

**Returns:** `bool`

**True When:**
- State is `Draft` or `Setup`

**Usage:**
```php
@if($lifecycle->isInSetup())
    <div class="alert">This election is still in setup phase</div>
@endif
```

---

#### `isVotingPhase(): bool`

Check if election is in voting phase (ReadyForVoting or VotingActive).

```php
if (ElectionLifecycle::of($election)->isVotingPhase()) {
    // Voting preparations complete or voting is open
}
```

**Returns:** `bool`

**True When:**
- State is `ReadyForVoting` or `VotingActive`

**Usage:**
```php
@if($lifecycle->isVotingPhase())
    <div class="voting-interface">...</div>
@endif
```

---

#### `isLocked(): bool`

Check if election is locked (no mutations possible).

```php
if (ElectionLifecycle::of($election)->isLocked()) {
    // Election is in terminal state, no changes allowed
}
```

**Returns:** `bool`

**True When:**
- State is `Archived`

**Usage - Permission Check:**
```php
public function delete(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    abort_if($lifecycle->isLocked(), 403, 'Cannot delete archived election');
    
    // Delete election
}
```

---

### Block Reason Methods

#### `blockedReason(): ?string`

Get the reason why the election is blocked from actions.

```php
$reason = ElectionLifecycle::of($election)->blockedReason();
// Examples: "Election not started", "Voting window not open", null
```

**Returns:** `?string` (null if not blocked)

**Possible Values:**
- `null` — Election is not blocked (actions are allowed)
- `"Election setup not started"` — Draft state
- `"Setup in progress"` — Setup state
- `"Awaiting voting window"` — ReadyForVoting state
- `"Voting window closed"` — After voting_ends_at
- `"Election archived"` — Archived state
- (Other domain-specific reasons from engine)

**Usage - Error Messages:**
```php
public function submitVote(Request $request)
{
    $election = Election::findOrFail($request->election_id);
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canVote()) {
        return back()->withError(
            'Unable to vote: ' . ($lifecycle->blockedReason() ?? 'Unknown reason')
        );
    }
    
    // Process vote
}
```

---

### Action Authorization Methods

#### `allowedActions(): array`

Get the list of actions allowed from the current state.

```php
$actions = ElectionLifecycle::of($election)->allowedActions();
// Returns: ['complete_administration', 'open_voting', ...]
```

**Returns:** `array` of action names (strings)

**Example Response:**
```php
// From Draft state
['complete_administration']

// From Setup state
['complete_nomination', 'open_voting']

// From ReadyForVoting state
['open_voting', 'cancel_election']

// From VotingActive state
['close_voting']

// From Results/Archived state
[]
```

**Usage - Dynamic UI:**
```php
<div class="actions">
    @foreach($lifecycle->allowedActions() as $action)
        @if($action === 'complete_administration')
            <button @click="completeAdministration">Complete Setup</button>
        @elseif($action === 'open_voting')
            <button @click="openVoting">Open Voting</button>
        @endif
    @endforeach
</div>
```

---

#### `canTransitionTo(string $action): bool`

Check if a specific action is allowed.

```php
if (ElectionLifecycle::of($election)->canTransitionTo('open_voting')) {
    // Can transition to voting phase
}
```

**Returns:** `bool`

**Parameters:**
- `$action` (string) — Action name (e.g., 'open_voting', 'publish_results')

**Usage - Button Rendering:**
```php
<button 
    @click="publishResults"
    :disabled="!lifecycle.canTransitionTo('publish_results')"
>
    Publish Results
</button>
```

---

### Query Guard Methods

#### `assertQueryAllowed(array $criteria, string $context): void`

Guard a query against deprecated field usage.

```php
$lifecycle->assertQueryAllowed(
    ['status' => 'active'],
    'MyRepository::findActive'
);
// Throws: DeprecatedQueryException (status is deprecated)

$lifecycle->assertQueryAllowed(
    ['state' => 'voting_active'],
    'MyRepository::findActive'
);
// Passes: state is not deprecated
```

**Parameters:**
- `$criteria` (array) — Query criteria where keys are field names
- `$context` (string) — Human-readable context (for logging)

**Returns:** `void`

**Throws:** `DeprecatedQueryException` if deprecated field detected

**Usage - Repository Query Guard:**
```php
public function findByState($state)
{
    // Guard against deprecated fields
    $lifecycle = ElectionLifecycle::of(Election::first());
    $lifecycle->assertQueryAllowed(
        ['state' => $state],
        'ElectionRepository::findByState'
    );
    
    return Election::where('state', $state)->get();
}
```

---

### Election Access Methods

#### `election(): Election`

Get the wrapped election model.

```php
$election = ElectionLifecycle::of($election)->election();
// Returns the original Election model
```

**Returns:** `Election` model

**Usage:**
```php
$lifecycle = ElectionLifecycle::of($election);

// Access election through lifecycle
$election = $lifecycle->election();
$name = $election->name;
```

---

#### `id(): string`

Get the election ID.

```php
$id = ElectionLifecycle::of($election)->id();
```

**Returns:** `string` (UUID)

**Usage:**
```php
$id = $lifecycle->id();
Log::info("Processing election: $id");
```

---

#### `name(): string`

Get the election name.

```php
$name = ElectionLifecycle::of($election)->name();
```

**Returns:** `string`

**Usage:**
```php
echo "Election: " . $lifecycle->name();
```

---

## ElectionLifecycleSnapshot

Immutable value object containing complete election state information.

### Location
```php
App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot
```

### Properties (Readonly)

```php
class ElectionLifecycleSnapshot
{
    public function __construct(
        public readonly ElectionLifecycleState $state,
        public readonly bool $canEdit,
        public readonly bool $canVote,
        public readonly bool $canManageVoters,
        public readonly bool $canPublishResults,
        public readonly bool $isLocked,
        public readonly ?string $blockedReason,
        public readonly array $allowedActions,
    ) {}
}
```

### Methods

#### `canTransitionTo(string $action): bool`

Check if a specific action is allowed.

```php
$snapshot = $lifecycle->snapshot();
if ($snapshot->canTransitionTo('open_voting')) {
    // Can open voting
}
```

**Returns:** `bool`

---

## ElectionLifecycleState Enum

Canonical election lifecycle state enum.

### Location
```php
App\Domain\Election\Enum\ElectionLifecycleState
```

### Cases

```php
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
    case Setup = 'setup';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';
}
```

### Methods

#### `label(): string`

Get human-readable label for UI display.

```php
echo ElectionLifecycleState::VotingActive->label();  // "Voting Active"
```

**Returns:** `string`

**Values:**
- Draft → "Draft Setup"
- Setup → "Setup in Progress"
- ReadyForVoting → "Ready for Voting"
- VotingActive → "Voting Active"
- Counting → "Counting Votes"
- ResultsPublished → "Results Published"
- Archived → "Archived"

---

#### `isTerminal(): bool`

Check if state is terminal.

```php
if (ElectionLifecycleState::Archived->isTerminal()) {
    // No further transitions possible
}
```

**Returns:** `bool`

**True For:** Archived only

---

#### `isInSetup(): bool`

Check if state is in setup phase.

```php
if ($state->isInSetup()) {
    // Configuration still possible
}
```

**Returns:** `bool`

**True For:** Draft, Setup

---

#### `isVotingPhase(): bool`

Check if state is in voting phase.

```php
if ($state->isVotingPhase()) {
    // Voting preparations complete or active
}
```

**Returns:** `bool`

**True For:** ReadyForVoting, VotingActive

---

## Supporting Services

### ElectionLifecycleEngine

Interface and implementation for SSOT computation.

#### Location
```php
App\Domain\Election\Services\ElectionLifecycleEngine (interface)
App\Application\Election\Services\ElectionLifecycleEngineImpl (implementation)
```

#### Main Method

```php
public function compute(Election $election): ElectionLifecycleSnapshot
```

Computes the authoritative lifecycle snapshot for an election.

**Returns:** `ElectionLifecycleSnapshot`

**Usage - Direct (rare):**
```php
$engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
$snapshot = $engine->compute($election);
```

**Recommended:** Use `ElectionLifecycle::of()` facade instead (cleaner API)

---

### QueryPolicyGuard

Runtime enforcement of query-level deprecation rules.

#### Location
```php
App\Application\Election\Deprecation\QueryPolicyGuard
```

#### Main Method

```php
public function assertAllowedQuery(array $criteria, string $context): void
```

Validates that a query doesn't use deprecated fields.

**Usage - In Repositories:**
```php
$guard = app(QueryPolicyGuard::class);
$guard->assertAllowedQuery(['state' => 'voting_active'], 'ElectionRepository::findActive');
// Passes if 'state' is not deprecated

$guard->assertAllowedQuery(['status' => 'active'], 'ElectionRepository::findActive');
// Throws DeprecatedQueryException (status is deprecated)
```

---

### DeprecationAccessGuard

Runtime enforcement of field-level deprecation rules.

#### Location
```php
App\Application\Election\Deprecation\DeprecationAccessGuard
```

#### Main Method

```php
public function checkFieldAccess(string $field, string $context, ?string $mode): void
```

Validates field access based on severity and mode.

**Usage - In Wrappers:**
```php
$guard = app(DeprecationAccessGuard::class);
$guard->checkFieldAccess('status', 'MyService::getStatus', 'warning');
// In warning mode: Logs warning but allows access
```

---

## Deprecation Policy

Configuration for all deprecation rules.

#### Location
```php
App\Application\Election\Deprecation\DeprecationPolicy
```

#### Current Settings

```php
const MODE = 'warning';  // Phase 3.1: 'warning', Phase 3.2: 'strict'

const FIELDS = [
    'status' => [
        'severity' => 'warning',
        'replacement' => 'ElectionLifecycleEngine::compute($election)->state->value',
    ],
    'is_active' => [
        'severity' => 'strict',
        'replacement' => 'ElectionLifecycleEngine::compute($election)->isActive()',
    ],
];
```

---

## Exception Classes

### DeprecatedFieldException

Thrown when strict-mode deprecation blocks field access.

```php
throw new DeprecatedFieldException(
    "Deprecated field 'is_active' accessed. Use ElectionLifecycle instead."
);
```

---

### DeprecatedQueryException

Thrown when QueryPolicyGuard blocks a query.

```php
throw new DeprecatedQueryException(
    "Deprecated field 'status' used in query context: ElectionRepository::findActive"
);
```

---

### InvalidTransitionException

Thrown when ConstitutionalTransitionGuard blocks an action.

```php
throw new InvalidTransitionException(
    "Cannot transition from 'draft' to 'voting'. Valid transitions: [complete_administration]"
);
```

---

## Summary: Which Method to Use

| Use Case | Method | Returns |
|----------|--------|---------|
| **Can vote now?** | `canVote()` | bool |
| **Can edit config?** | `canEdit()` | bool |
| **Can manage voters?** | `canManageVoters()` | bool |
| **Can publish results?** | `canPublishResults()` | bool |
| **Current state?** | `state()` | ElectionLifecycleState |
| **All info at once?** | `snapshot()` | ElectionLifecycleSnapshot |
| **What's blocked?** | `blockedReason()` | ?string |
| **What's allowed?** | `allowedActions()` | array |
| **Is specific action allowed?** | `canTransitionTo('action')` | bool |
| **Is terminal/archived?** | `isTerminal()` | bool |
| **In setup phase?** | `isInSetup()` | bool |
| **In voting phase?** | `isVotingPhase()` | bool |

---

## Quick Reference: Common Patterns

### Vote Submission
```php
if (!ElectionLifecycle::of($election)->canVote()) {
    return back()->withError('Voting not allowed');
}
```

### Edit Check
```php
if (!ElectionLifecycle::of($election)->canEdit()) {
    return abort(403);
}
```

### State-Based Rendering
```php
$state = ElectionLifecycle::of($election)->state();
echo $state->label();  // "Voting Active"
```

### Batch Processing (Performance)
```php
$snapshot = ElectionLifecycle::of($election)->snapshot();
foreach ($actions as $action) {
    if ($snapshot->canTransitionTo($action)) {
        // Process action
    }
}
```

See [PATTERNS.md](PATTERNS.md) for more examples.
