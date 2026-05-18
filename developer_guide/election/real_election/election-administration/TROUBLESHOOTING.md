# Troubleshooting: Common Issues During Phase 3 Migration

**Debugging guide for issues encountered while migrating controllers to the SSOT architecture.**

---

## Deprecation Warnings in voter_audit Log

### Issue: Logs show "Deprecated field 'status' accessed"

**Symptoms:**
```
[2026-05-19 10:30:45] voter_audit.WARNING: Deprecated field 'status' accessed in context: MyController::show
```

**Cause:** Controller (or code called by it) is still accessing legacy `$election->status` field.

**Solution:**

1. **Find where field is accessed:**
   ```bash
   grep -n "->status" app/Http/Controllers/MyController.php
   ```

2. **Replace with facade:**
   ```php
   // ❌ BEFORE
   if ($election->status === 'voting') { }
   
   // ✅ AFTER
   if (ElectionLifecycle::of($election)->isVotingPhase()) { }
   ```

3. **Check views too:**
   ```bash
   grep -n "election.status" resources/views/
   ```

4. **Update view:**
   ```blade
   {{-- ❌ BEFORE --}}
   Status: {{ $election->status }}
   
   {{-- ✅ AFTER --}}
   Status: {{ $lifecycle->state()->label() }}
   ```

5. **Re-run and verify:**
   ```bash
   php artisan test tests/Feature/YourControllerTest.php
   tail -f storage/logs/voter_audit.log  # Should show no new warnings
   ```

---

## DeprecatedQueryException: Query Uses Deprecated Field

### Issue: "Deprecated field 'is_active' used in query context"

**Symptoms:**
```
DeprecatedQueryException: Deprecated field 'is_active' used in query context: 
ElectionRepository::findActive. Use ElectionReadModel::lifecycle() instead of direct field access.
```

**Cause:** Repository is building a query with a deprecated field (likely `status` or `is_active`).

**Solution:**

1. **Find the query:**
   ```bash
   # Find which repository is throwing
   grep -n "is_active" app/Repositories/ElectionRepository.php
   ```

2. **Add QueryPolicyGuard check before query:**
   ```php
   use App\Application\Election\Deprecation\QueryPolicyGuard;
   
   class ElectionRepository
   {
       public function __construct(
           private readonly QueryPolicyGuard $guard
       ) {}
       
       public function findActive()
       {
           // ✅ GUARD prevents deprecated fields
           $this->guard->assertAllowedQuery(
               ['state' => 'voting_active'],
               'ElectionRepository::findActive'
           );
           
           return Election::where('state', 'voting_active')->get();
       }
   }
   ```

3. **Or use engine-derived state instead:**
   ```php
   // If you need active elections, get them a different way
   public function findVotingNow()
   {
       // Use state field, not is_active
       $this->guard->assertAllowedQuery(
           ['state' => 'voting_active'],
           __METHOD__
       );
       
       return Election::where('state', 'voting_active')->get();
   }
   ```

4. **Test the fix:**
   ```bash
   php artisan test --no-coverage
   ```

---

## Undefined Variable in View: `$lifecycle`

### Issue: "Undefined variable: lifecycle" in view

**Symptoms:**
```
View [election/show] not found. [Undefined variable: lifecycle]
```

**Cause:** Controller didn't pass `$lifecycle` to the view.

**Solution:**

1. **Find controller method:**
   ```php
   public function show(Election $election)
   {
       // ❌ BEFORE: Forgot to pass lifecycle
       return view('election.show', ['election' => $election]);
       
       // ✅ AFTER: Pass lifecycle
       return view('election.show', [
           'election' => $election,
           'lifecycle' => ElectionLifecycle::of($election),  // ← ADD THIS
       ]);
   }
   ```

2. **Or compute in view:**
   ```blade
   {{-- Not ideal but works: --}}
   @php
       $lifecycle = \App\Application\Election\Facades\ElectionLifecycle::of($election);
   @endphp
   
   {{-- Use $lifecycle in template --}}
   @if($lifecycle->canVote())
       Vote now!
   @endif
   ```

3. **Best practice: Pass from controller**
   ```php
   public function show(Election $election)
   {
       $lifecycle = ElectionLifecycle::of($election);
       
       return view('election.show', compact('election', 'lifecycle'));
   }
   ```

---

## Feature Not Updating When Expected

### Issue: Election permission hasn't changed but snapshot shows different state

**Symptoms:**
- Election moved from Draft to Setup
- `$lifecycle->canEdit()` still returns false
- "Something changed but facade didn't notice"

**Cause:** Snapshot is computed once and cached in memory for that request.

**Solution:**

**Option 1: Re-compute snapshot**
```php
public function update(Request $request, Election $election)
{
    $election->update($request->validated());
    
    // ❌ OLD SNAPSHOT (from before update)
    $oldLifecycle = ElectionLifecycle::of($election);
    
    // ✅ RE-FETCH (gets fresh snapshot)
    $election->refresh();
    $newLifecycle = ElectionLifecycle::of($election);
    
    // Now use fresh snapshot
    if ($newLifecycle->canEdit()) {
        // ...
    }
}
```

**Option 2: Use pre-computed snapshot in same request**
```php
public function showDashboard(Election $election)
{
    // Compute ONCE per request
    $lifecycle = ElectionLifecycle::of($election);
    
    // Use same snapshot for all logic in this request
    if ($lifecycle->canVote()) { }
    if ($lifecycle->canEdit()) { }
    
    return view('dashboard', compact('lifecycle'));
}
```

**Option 3: Check what changed**
```php
public function debug(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    // ✅ Debug snapshot contents
    Log::info('Election lifecycle snapshot', [
        'state' => $lifecycle->state()->value,
        'can_vote' => $lifecycle->canVote(),
        'can_edit' => $lifecycle->canEdit(),
        'blocked_reason' => $lifecycle->blockedReason(),
        'allowed_actions' => $lifecycle->allowedActions(),
    ]);
}
```

---

## Test Failure: "Call to undefined method setupAsReadyForVoting()"

### Issue: Factory method doesn't exist

**Symptoms:**
```
BadMethodCallException: Call to undefined method 
App\Models\Election::setupAsReadyForVoting()
```

**Cause:** Using factory methods that don't exist in current codebase.

**Solution:**

```php
// ❌ WRONG: Method doesn't exist
$election = Election::factory()->create()->setupAsReadyForVoting();

// ✅ CORRECT: Set state directly
$election = Election::factory()->create([
    'state' => 'ready_for_voting',
    'voting_starts_at' => now()->subHour(),
    'voting_ends_at' => now()->addHour(),
]);

// ✅ OR: Use factory state (if defined)
$election = Election::factory()->readyForVoting()->create();

// ✅ OR: Create then update
$election = Election::factory()->create();
$election->update([
    'state' => 'ready_for_voting',
    'voting_starts_at' => now()->subHour(),
    'voting_ends_at' => now()->addHour(),
]);
```

---

## Test Failure: ElectionLifecycleState Method Doesn't Exist

### Issue: "Call to undefined method isActive()"

**Symptoms:**
```
Error: Call to undefined method App\Domain\Election\Enum\ElectionLifecycleState::isActive()
```

**Cause:** Method name doesn't match. State enum has `canVote()`, not `isActive()`.

**Solution:**

Check available methods:
```php
// Available methods on ElectionLifecycleState:
$state->label()           // Human-readable string
$state->isTerminal()      // Is archived?
$state->isInSetup()       // Is draft or setup?
$state->isVotingPhase()   // Is ready_for_voting or voting_active?

// NOT available (wrong object):
$state->isActive()        // ❌ NO (that's on snapshot)
$state->canVote()         // ❌ NO (that's on snapshot)
```

```php
// ✅ CORRECT
$lifecycle = ElectionLifecycle::of($election);
$can_vote = $lifecycle->canVote();      // On facade
$can_vote = $lifecycle->snapshot()->canVote;  // On snapshot
$is_terminal = $lifecycle->state()->isTerminal();  // On state enum
```

---

## Deprecation Mode Is 'strict' But Should Be 'warning'

### Issue: DeprecatedFieldException thrown during Phase 3.1 (warning mode)

**Symptoms:**
```
DeprecatedFieldException: Deprecated field 'status' accessed. 
Use ElectionLifecycle instead of direct field access.
```

But you're in Phase 3.1 which should allow usage (with warnings).

**Cause:** Deprecation mode is 'strict' but should be 'warning' during Phase 3.1.

**Solution:**

1. **Check current mode:**
   ```php
   // app/Application/Election/Deprecation/DeprecationPolicy.php
   
   const MODE = 'warning';  // ← Should be 'warning' in Phase 3.1
   // const MODE = 'strict';  // ← Should be 'strict' in Phase 3.2
   ```

2. **Change to warning mode temporarily:**
   ```php
   const MODE = 'warning';
   ```

3. **Run tests again:**
   ```bash
   php artisan test --no-coverage
   ```

4. **After all migrations complete, change to 'strict':**
   ```php
   const MODE = 'strict';
   ```

---

## Circular Dependency Issue

### Issue: Service A needs Service B, Service B needs Service A

**Symptoms:**
```
CircularDependencyException: Detected 3 circular dependencies in your service configuration.
```

**Cause:** Injecting `QueryPolicyGuard` and other services in wrong places.

**Solution:**

```php
// ❌ WRONG: Circular dependency
class ElectionRepository
{
    public function __construct(
        private readonly ElectionLifecycle $facade  // ← Facade needs services
    ) {}
}

// ✅ CORRECT: Inject only what you need
class ElectionRepository
{
    public function __construct(
        private readonly QueryPolicyGuard $guard  // ← Guard is direct service
    ) {}
    
    public function findByState($state)
    {
        $this->guard->assertAllowedQuery(['state' => $state], __METHOD__);
        return Election::where('state', $state)->get();
    }
}

// ✅ CORRECT: Use facade in controllers (not services)
class ElectionController
{
    public function show(Election $election)
    {
        $lifecycle = ElectionLifecycle::of($election);  // ← OK in controllers
        // ...
    }
}
```

**Rule of thumb:**
- Controllers: Use `ElectionLifecycle::of($election)` facade
- Services: Inject `QueryPolicyGuard` or `ElectionLifecycleEngine` directly
- Never inject facade into services (causes circular dependencies)

---

## Snapshot Properties Are Null

### Issue: `$snapshot->blockedReason` returns null unexpectedly

**Symptoms:**
```php
$lifecycle = ElectionLifecycle::of($election);
$reason = $lifecycle->blockedReason();  // Returns null but expected a string
```

**Cause:** Snapshot computed at different time or state changed.

**Solution:**

1. **Debug the snapshot:**
   ```php
   $snapshot = ElectionLifecycle::of($election)->snapshot();
   
   dd([
       'state' => $snapshot->state->value,
       'can_vote' => $snapshot->canVote,
       'can_edit' => $snapshot->canEdit,
       'blocked_reason' => $snapshot->blockedReason,
       'allowed_actions' => $snapshot->allowedActions,
   ]);
   ```

2. **Check state value:**
   ```php
   $state = $lifecycle->state();
   // Is it Draft, Setup, ReadyForVoting, VotingActive, Counting, ResultsPublished, or Archived?
   
   if ($state === ElectionLifecycleState::VotingActive && $lifecycle->blockedReason() === null) {
       // ✅ CORRECT: VotingActive state with no block reason
   }
   ```

3. **Understand blocking logic:**
   ```php
   // blockedReason is null when election CAN proceed with actions
   // It's a string when election is BLOCKED from actions
   
   if ($lifecycle->blockedReason() === null) {
       // Election is allowed to proceed
       if ($lifecycle->canVote()) {
           // Can vote right now
       } elseif ($lifecycle->canEdit()) {
           // Can edit configuration
       }
   } else {
       // Election is blocked - show reason
       return back()->withError($lifecycle->blockedReason());
   }
   ```

---

## Performance: Snapshot Computation Too Slow

### Issue: ElectionLifecycle::of($election) takes too long (~100ms+ per call)

**Symptoms:**
- Page takes long time to render
- Each `ElectionLifecycle::of()` call adds delay
- Batch operations are very slow

**Solution:**

**Option 1: Cache snapshot per request**
```php
// Service Provider setup
app()->bindShared('election.lifecycle.cache', function() {
    return new \Illuminate\Support\Collection();
});

// In controller
public function processManyElections(Collection $elections)
{
    $cache = app('election.lifecycle.cache');
    
    foreach ($elections as $election) {
        if (!$cache->has($election->id)) {
            $cache->put(
                $election->id,
                ElectionLifecycle::of($election)->snapshot()
            );
        }
        
        $snapshot = $cache->get($election->id);
        // Use cached snapshot
    }
}
```

**Option 2: Batch pre-computation**
```php
public function listElections()
{
    $elections = Election::all();
    
    // Compute all snapshots once
    $engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
    $snapshots = $elections->mapWithKeys(fn($e) => [
        $e->id => $engine->compute($e)
    ]);
    
    // Use pre-computed snapshots
    foreach ($elections as $election) {
        $lifecycle = ElectionLifecycle::withSnapshot(
            $election,
            $snapshots[$election->id]
        );
        
        // Process with no recomputation
    }
}
```

**Option 3: Profile to find bottleneck**
```php
$start = microtime(true);
$lifecycle = ElectionLifecycle::of($election);
$duration = (microtime(true) - $start) * 1000;

Log::info("Snapshot computation took {$duration}ms");
```

If computation is >5ms:
- Check `ElectionLifecycleEngine::compute()` for database queries
- Ensure relationships are eagerly loaded
- Consider query optimization

---

## Migration Incomplete: Some Controllers Still Use Legacy Fields

### Issue: After "completing" Phase 3.1, some controllers still use `->status`

**Symptoms:**
```bash
# Run this to find remaining legacy usage
grep -r "->status\|->is_active" app/Http/Controllers/

# Output shows controllers still using old fields
app/Http/Controllers/ReportController.php:15:    if ($election->status === 'active') {
app/Http/Controllers/AdminController.php:22:    if (!$election->is_active) {
```

**Solution:**

1. **Create list of remaining controllers:**
   ```bash
   grep -l "->status\|->is_active" app/Http/Controllers/*.php
   ```

2. **Migrate each one:**
   ```bash
   # For each controller found:
   # 1. Open it
   # 2. Replace field access with facade
   # 3. Run tests
   # 4. Check logs
   ```

3. **Automated check (add to CI):**
   ```bash
   #!/bin/bash
   if grep -r "->status\|->is_active" app/Http/Controllers/ > /dev/null; then
       echo "ERROR: Legacy field usage found in controllers"
       exit 1
   fi
   ```

---

## Safe Mode: Testing Without Breaking Production

### Issue: Want to test Phase 3.2 (strict mode) without breaking production

**Solution:**

**Option 1: Test strict mode locally**
```php
// In phpunit.xml or test setup
<env name="ELECTION_DEPRECATION_MODE" value="strict"/>
```

Then only tests see strict mode, production stays in warning.

**Option 2: Use environment variable**
```php
// app/Application/Election/Deprecation/DeprecationPolicy.php

const MODE = env('ELECTION_DEPRECATION_MODE', 'warning');
```

Then control via `.env`:
```bash
# .env (development)
ELECTION_DEPRECATION_MODE=strict

# .env.production
ELECTION_DEPRECATION_MODE=warning
```

**Option 3: Gradual roll-out**
```php
// Strict mode for 10% of traffic
const MODE = rand(1, 100) <= 10 ? 'strict' : 'warning';

// Or per-user
const MODE = auth()->user()?->is_admin ? 'strict' : 'warning';
```

---

## Getting Help

If you're stuck:

1. **Check [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** — Step-by-step instructions
2. **Review [PATTERNS.md](PATTERNS.md)** — Code examples for your use case
3. **Read [API_REFERENCE.md](API_REFERENCE.md)** — All available methods
4. **Look at passing tests** — `tests/Unit/Application/Election/` — 64 examples of correct usage
5. **Check git history** — Look at Phase 1-2 commits for reference implementation

---

## Error Summary Table

| Error | Cause | Solution |
|-------|-------|----------|
| "Deprecated field" in logs | Still accessing `->status` or `->is_active` | Use `ElectionLifecycle::of()` facade |
| DeprecatedQueryException | Repository query uses deprecated field | Add QueryPolicyGuard check |
| Undefined variable `$lifecycle` | Controller didn't pass to view | Add `compact('lifecycle')` |
| "Call to undefined method" | Wrong class/method name | Check API_REFERENCE.md for correct method |
| isActive() not found | Method is on snapshot, not state | Use `$lifecycle->canVote()` not `$state->isActive()` |
| Circular dependency | Wrong service injected | Inject guard, not facade, in services |
| Snapshot properties null | State not in expected condition | Debug snapshot contents with dd() |
| Performance slow | Computing too many snapshots | Use batch pre-computation |
| Still finding legacy usage | Migration incomplete | Grep for `->status` and migrate remaining controllers |

---

See [README.md](README.md) for overview, or [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) to get started.
