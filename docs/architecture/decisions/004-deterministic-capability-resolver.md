# ADR-004: Deterministic Capability Resolver

## Status
Accepted — 2026-05-23

## Context

The capability resolver determines which governance actions are allowed for each election state + user role combination. Early implementations made it non-deterministic by:

1. **Calling `auth()`** — User context came from Laravel's global auth guard, not passed as argument
2. **Using `now()`** — Time came from system clock, not from test-provided Clock abstraction
3. **Querying Eloquent models** — Resolver fetched data from database on every invocation
4. **Caching without invalidation** — Cached results that became stale when state changed

Problems this caused:
- ❌ Impossible to test without full HTTP context
- ❌ Time-dependent tests failed non-deterministically
- ❌ N+1 query problems from fetching relationships
- ❌ Stale cache masks real bugs
- ❌ Cannot run resolver in worker/queue contexts

## Decision

**Resolver is a deterministic pure function: same inputs always produce same outputs.**

### Resolver Invariants

The resolver receives ALL context as arguments (never calls infrastructure):

```typescript
class ElectionConstitution {
  canResolveAction(
    action: string,
    election: Election,           // All data passed in
    stateMachine: StateMachine,   // Never calls Eloquent
    user: User,                    // Never calls auth()
    clock: Clock                   // Never calls now()
  ): boolean {
    // Pure function: return true/false based only on arguments
    // No side effects, no database calls, no global state
  }
}
```

**Required Context Arguments:**
- `action` (string) — Action to check: 'open_voting', 'complete_administration', etc.
- `election` (aggregate) — Election data: dates, flags, config
- `stateMachine` (snapshot) — Current state + capabilities (for overlays)
- `user` (entity) — User with role, permissions (must be passed in)
- `clock` (abstraction) — Time source (test can provide fixed time)

**Forbidden Calls:**
- ❌ `auth()` or `auth()->user()`
- ❌ `now()` or `Carbon::now()`
- ❌ Eloquent queries: `User::find()`, `Election::where()`, etc.
- ❌ Facade calls: `Cache::get()`, `Log::info()`, etc.
- ❌ Any I/O: HTTP requests, file reads, cache operations

### Structure

```php
// ✅ Correct: Pure function, all context passed in
class ElectionConstitution {
  public function canOpenVoting(
    Election $election,
    StateMachine $stateMachine,
    User $user,
    Clock $clock
  ): bool {
    // Check state machine is ready_for_voting
    if ($stateMachine->currentState !== 'ready_for_voting') {
      return false;
    }

    // Check user is chief/officer
    if (!$user->hasRole(['chief', 'election_officer'])) {
      return false;
    }

    // Check voting window configured
    if (!$election->voting_starts_at || !$election->voting_ends_at) {
      return false;
    }

    // No IO, no globals, no ambiguity
    return true;
  }
}

// ❌ Wrong: Non-deterministic, calls infrastructure
class ElectionConstitution {
  public function canOpenVoting(Election $election): bool {
    $user = auth()->user();  // ❌ Non-deterministic: depends on request context
    $now = now();            // ❌ Non-deterministic: changes every test run
    
    // Query the database every time
    $couldHaveOpenedBefore = VotingHistory::where('election_id', $election->id)
      ->where('action', 'open_voting')
      ->exists();  // ❌ N+1 query problem
    
    return $couldHaveOpenedBefore === false;
  }
}
```

## Consequences

### Resolver Development
- ✅ Easy to test (no HTTP setup required)
- ✅ Tests run in milliseconds (pure logic)
- ✅ Same rules everywhere (backend, API, CLI)
- ⚠️ Must pass ALL context as arguments (verbose)
- ⚠️ Cannot lazy-load related data (must be eagerly loaded before calling)
- ⚠️ Time-based rules need injected Clock, not system time

### Testing
- ✅ Unit tests are simple: `assert canOpenVoting($election, $sm, $user, $clock) === true`
- ✅ Tests are deterministic (no flaky time-based failures)
- ✅ Can test edge cases easily (inject different clock values)
- ✅ Integration tests validate only the Inertia prop flow, not the resolver logic
- ⚠️ Must test all user roles (cannot mock auth())
- ⚠️ Must eagerly load all relationships before calling resolver

### Performance
- ✅ No database queries in resolver
- ⚠️ All data must be loaded before resolver is called (eager loading requirement)
- ⚠️ Controller must fetch election + relationships upfront, not lazy

### Architecture
- ✅ Clear separation: Controller loads data → passes to resolver → resolver decides
- ✅ Easy to test resolver in isolation
- ✅ Easy to version resolver separately from controller
- ✅ Can call resolver from API, CLI, queue, webhook contexts
- ⚠️ Resolver cannot be invoked without full context

## Enforcement

**Architecture tests validate determinism:**

```php
// Ensure resolver receives context as arguments
class ElectionConstitutionDeterminismTest extends TestCase {
  public function test_resolver_does_not_call_auth() {
    // Grep for 'auth()' in ElectionConstitution.php
    $content = file_get_contents(app_path('Domain/Election/Constitution/ElectionConstitution.php'));
    $this->assertStringNotContainsString('auth()', $content);
  }

  public function test_resolver_does_not_call_now() {
    // Grep for 'now()' or 'Carbon::now()'
    $content = file_get_contents(app_path('Domain/Election/Constitution/ElectionConstitution.php'));
    $this->assertStringNotContainsString('now()', $content);
    $this->assertStringNotContainsString('Carbon::now()', $content);
  }

  public function test_resolver_does_not_query_eloquent() {
    // Check method bodies don't contain Eloquent query patterns
    $violations = grep('User::find|Election::where|Model::', $file);
    $this->assertEmpty($violations);
  }
}
```

## Related

- [[001-constitutional-capability-sovereignty]] — Resolver is authority
- [[002-frontend-anti-corruption-boundary]] — Frontend cannot call resolver
- [[003-lifecycle-vs-phase-projection]] — Resolver uses lifecycle states
- **Implementation:** `app/Domain/Election/Constitution/ElectionConstitution.php`, `app/Http/Controllers/Election/ElectionManagementController.php`

## Examples

### ✅ Correct: Deterministic Resolution

```php
// Domain: Pure function
class ElectionConstitution {
  public function canCompleteAdministration(
    Election $election,
    StateMachine $stateMachine,
    User $user,
    Clock $clock
  ): bool {
    // Check state
    if ($stateMachine->currentState !== 'setup_administration') {
      return false;
    }

    // Check role
    if (!$user->hasAnyRole(['chief', 'election_officer'])) {
      return false;
    }

    // Check preconditions (all data already loaded)
    if (!$election->administration_completed) {
      return false;  // Precondition not met
    }

    return true;
  }
}

// Controller: Loads context, calls resolver
class ElectionManagementController {
  public function beginSetup(Request $request, Election $election) {
    // Eager-load all data
    $election->load(['posts', 'voters', 'candidates']);

    // Get current state machine (from database or session)
    $stateMachine = $this->getStateMachine($election);

    // Call resolver with full context
    $allowed = $this->constitution->canCompleteAdministration(
      $election,
      $stateMachine,
      auth()->user(),  // ← Resolved here, not in domain
      new SystemClock()
    );

    if (!$allowed) {
      return back()->withErrors(['error' => 'Not allowed']);
    }

    return redirect()->route('elections.management', $election);
  }
}

// Test: Inject time, user, state
public function test_can_complete_administration_when_state_is_right() {
  $election = Election::factory()->create(['administration_completed' => true]);
  $user = User::factory()->create(['role' => 'chief']);
  $stateMachine = new StateMachine(currentState: 'setup_administration');
  $clock = new FrozenClock(Carbon::parse('2026-05-23 10:00:00'));

  $result = $this->constitution->canCompleteAdministration(
    $election,
    $stateMachine,
    $user,
    $clock
  );

  $this->assertTrue($result);
}

public function test_cannot_complete_administration_without_precondition() {
  $election = Election::factory()->create(['administration_completed' => false]);
  $user = User::factory()->create(['role' => 'chief']);
  $stateMachine = new StateMachine(currentState: 'setup_administration');

  $result = $this->constitution->canCompleteAdministration(
    $election,
    $stateMachine,
    $user,
    new SystemClock()
  );

  $this->assertFalse($result);
}
```

### ❌ Wrong: Non-Deterministic Resolution

```php
// ❌ WRONG: Calls auth() internally
class ElectionConstitution {
  public function canCompleteAdministration(Election $election, Clock $clock): bool {
    $user = auth()->user();  // ❌ Depends on request context
    // ... rest of logic
  }
}

// ❌ WRONG: Uses system clock
class ElectionConstitution {
  public function canPublishResults(Election $election): bool {
    if (now()->isBefore($election->voting_ends_at)) {  // ❌ Non-deterministic
      return false;
    }
    return true;
  }
}

// ❌ WRONG: Queries database in resolver
class ElectionConstitution {
  public function canOpenVoting(Election $election): bool {
    // Called every request, queries N times
    $election->load('voters', 'candidates', 'posts');  // ❌ N queries
    
    if ($election->voters()->count() === 0) {
      return false;
    }
    return true;
  }
}

// Test: Cannot test without full HTTP context
public function test_can_open_voting() {
  $this->actingAs($user);  // ❌ Required because resolver calls auth()
  $response = $this->get('/elections/1');  // ❌ Full HTTP request needed
  // Still cannot control time or other context
}
```
