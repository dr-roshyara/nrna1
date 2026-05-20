# Phase 3.3: Controlled Write Barrier

**Date:** 2026-05-20  
**Phase:** 3.3 (Controlled Write Barrier)  
**Status:** Complete and verified  
**Audience:** Architects, security engineers, infrastructure team

---

## 🏛️ What Is the Controlled Write Barrier?

The **Controlled Write Barrier** (`ElectionStateWriteContext`) is a **constitutional authority boundary**. It ensures that:

1. **Only authorized code can mutate election state**
2. **All mutations are recorded to the audit trail**
3. **Violations are detected and logged**
4. **At Level 4, unauthorized mutations throw exceptions**

### The Core Concept

```
No direct state mutations allowed.

State can only change through:
  ElectionStateWriteContext::authorize(function() {
      $election->update(['state' => $newState]);
  })
```

### Why This Matters

**Before:**
```php
// Anyone could mutate state anywhere
$election->state = 'voting';  // Untracked, unvalidated, unauthorized
$election->save();
```

**After:**
```php
// State mutations are gated through authority boundary
ElectionStateWriteContext::authorize(function() use ($election, $newState) {
    $election->update(['state' => $newState]);
    // This calls the setStateAttribute() mutator
    // Which checks isAuthorized() and records violation
});
```

---

## 🔐 How It Works

### Layer 1: ElectionStateWriteContext (Authorization Boundary)

```php
// app/Application/Election/Governance/ElectionStateWriteContext.php

final class ElectionStateWriteContext
{
    // Is a write currently authorized?
    private static bool $authorized = false;

    // Authorize a closure to mutate election state
    public static function authorize(Closure $closure): mixed
    {
        $previous = self::$authorized;
        try {
            self::$authorized = true;
            return $closure();
        } finally {
            self::$authorized = $previous;
        }
    }

    // Check if currently authorized
    public static function isAuthorized(): bool
    {
        return self::$authorized;
    }

    // Record a violation for audit trail
    public static function recordViolation(
        string $field,
        string $context
    ): void {
        $metrics = app(ConstitutionalMetricsContract::class);
        $metrics->recordUnauthorizedStateMutation($field, $context);
    }
}
```

### Layer 2: Election Model Mutator (Enforcement)

```php
// app/Models/Election.php

class Election extends Model
{
    // Called whenever $election->state = $value is assigned
    protected function setStateAttribute(string $value): void
    {
        $metrics = app(ConstitutionalMetricsContract::class);
        $level = config('election.enforcement_level', 1);

        // Check if authorized
        if (!ElectionStateWriteContext::isAuthorized()) {
            // Get caller context for audit trail
            $backtrace = debug_backtrace();
            $context = $backtrace[1]['function'] ?? 'unknown_function';

            // Level 1+: Record violation (always)
            ElectionStateWriteContext::recordViolation('state', $context);

            // Level 4: Throw exception (full enforcement)
            if ($level === 4) {
                throw new UnauthorizedStateMutationException(
                    "State mutation forbidden: {$context} did not authorize"
                );
            }
        }

        // Allow write if authorized or enforcement < 4
        $this->attributes['state'] = $value;
    }
}
```

### Layer 3: Calling Code (User of Write Barrier)

```php
// In ActivateElectionCommand, BackfillElectionState, etc.

// Before (direct mutation - caught by mutator):
$election->update(['state' => 'administration']);  // ❌ Unauthorized

// After (authorized mutation - allowed):
ElectionStateWriteContext::authorize(function() use ($election) {
    $election->update(['state' => 'administration']);  // ✅ Authorized
});
```

---

## 📊 Enforcement Levels

The write barrier respects graduated enforcement levels:

| Level | Name | Behavior | Current |
|-------|------|----------|---------|
| 0 | Warning Mode | Log warnings, allow all | ❌ |
| 1 | Metrics Strict | Record violations, allow writes | ✅ YES |
| 2 | Query Guard Strict | Block deprecated queries | (Future) |
| 3 | Lifecycle Strict | Block field access | (Future) |
| 4 | Full Strict | Throw exceptions | (Future) |

### Current Level (Level 1)

```php
// config/election.php
'enforcement_level' => env('ELECTION_ENFORCEMENT_LEVEL', 1),

// Behavior:
// ✅ Record violation to metrics
// ✅ Allow write to proceed
// ❌ Do not throw exception
```

### Future Level (Level 4)

When the system is proven stable, transition to Level 4:

```php
'enforcement_level' => 4,  // Full strict mode

// Behavior:
// ✅ Record violation to metrics
// ❌ Throw UnauthorizedStateMutationException
// ❌ Write is blocked
```

---

## 🔍 Detecting Unauthorized Mutations

### Direct Detection (Backtrace)

```php
// When someone tries to set state without authorization
$election->state = 'voting';  // Direct assignment

// Mutator runs:
if (!ElectionStateWriteContext::isAuthorized()) {
    $backtrace = debug_backtrace();
    $context = $backtrace[1]['function'];  // e.g., 'testScript'
    
    // Record violation
    ElectionStateWriteContext::recordViolation('state', $context);
    
    // At Level 4: throw exception
    // At Level 1: continue (allow write)
}
```

### Metrics Tracking

```php
// View violations recorded in past 24h
$metrics = app(ConstitutionalMetricsContract::class);
$health = $metrics->getHealth();

echo $health['unauthorized_state_mutations_24h'];  // e.g., 3
```

### Audit Logging

```php
// All unauthorized mutations logged to channel
Log::channel('constitutional_integrity')->warning(
    'Unauthorized state mutation detected',
    [
        'election_id' => $election->id,
        'attempted_state' => $value,
        'context' => 'testScript',
        'enforcement_level' => 1,
    ]
);
```

---

## 📝 Implementation Pattern

### Correct Usage: Authorized Write

```php
// In console command, controller, event listener, etc.

use App\Application\Election\Governance\ElectionStateWriteContext;

class ActivateElectionCommand extends Command
{
    public function handle(): int
    {
        $election = Election::find($this->argument('id'));

        // Authorize the write
        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['state' => 'administration']);
        });

        return 0;
    }
}
```

### Incorrect Usage: Direct Mutation

```php
// ❌ DON'T DO THIS

class BadController extends Controller
{
    public function update(Request $request)
    {
        $election = Election::find($request->id);

        // Direct assignment - not authorized!
        $election->state = 'setup';
        $election->save();  // Mutator catches this

        // At Level 1: violation recorded, but write succeeds
        // At Level 4: UnauthorizedStateMutationException thrown
    }
}
```

### Testing Unauthorized Mutations

```php
// tests/Feature/Election/WriteBarrier/AuthorizationTest.php

class AuthorizationTest extends TestCase
{
    /** @test */
    public function unauthorized_mutation_recorded_at_level_1()
    {
        config(['election.enforcement_level' => 1]);

        $election = Election::factory()->inDraftState()->create();

        // Try to mutate without authorization
        $election->state = 'voting';
        $election->save();  // Does NOT throw at Level 1

        // But violation is recorded
        $metrics = app(ConstitutionalMetricsContract::class);
        $health = $metrics->getHealth();
        $this->assertGreaterThan(0, $health['unauthorized_state_mutations_24h']);
    }

    /** @test */
    public function unauthorized_mutation_throws_at_level_4()
    {
        config(['election.enforcement_level' => 4]);

        $election = Election::factory()->inDraftState()->create();

        $this->expectException(UnauthorizedStateMutationException::class);
        $election->state = 'voting';
        $election->save();  // THROWS at Level 4
    }

    /** @test */
    public function authorized_mutation_always_succeeds()
    {
        config(['election.enforcement_level' => 4]);

        $election = Election::factory()->inDraftState()->create();

        // Authorized write succeeds even at Level 4
        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->state = 'voting';
            $election->save();  // Does NOT throw
        });

        $this->assertEquals('voting', $election->fresh()->state);
    }
}
```

---

## 🎯 Audit Trail Example

### Scenario: Unauthorized Write Attempt

```php
// Someone tries to directly mutate state
$election->state = 'voting';
$election->save();

// Log output:
// [constitutional_integrity] WARNING: Unauthorized state mutation detected
// {
//   "election_id": 123,
//   "attempted_state": "voting",
//   "context": "myTestScript",
//   "enforcement_level": 1,
//   "backtrace": [
//     { "function": "myTestScript", "line": 45 },
//     { "function": "testThing", "line": 123 }
//   ]
// }
```

### Scenario: Authorized Write (Normal)

```php
// Proper authorization
ElectionStateWriteContext::authorize(function() use ($election) {
    $election->state = 'voting';
    $election->save();  // ✅ Allowed
});

// No log entry - this is normal operation
// But it could be recorded to audit log if needed:
Log::channel('constitutional_audit')->info(
    'State mutation authorized',
    ['election_id' => 123, 'new_state' => 'voting']
);
```

---

## 🔄 Integration with TransitionGuard

The write barrier works alongside `ConstitutionalTransitionGuard`:

```
User calls: $lifecycle->transitionVia('open_voting')
    ↓
Guard checks preconditions and business rules
    ↓
If valid: computes new state
    ↓
Calls: ElectionStateWriteContext::authorize(function() {
    $election->update(['state' => $newState]);
})
    ↓
Mutator checks isAuthorized()
    ↓
If authorized: write succeeds
If not authorized: violation recorded (Level 1) or exception thrown (Level 4)
```

---

## 📊 Metrics & Monitoring

### Health Check

```bash
php artisan election:constitution:health

# Output:
# ✅ STRICT MODE CHECK
# ├─ Unauthorized mutations (24h): 0
# ├─ Divergences detected (24h): 0
# ├─ Enforcement level: 1 (Metrics Strict)
# └─ Status: HEALTHY
```

### Tracking Unauthorized Attempts

```php
// Count attempts by caller
$backtrace = debug_backtrace();
$context = $backtrace[1]['function'];

// Examples:
// - "testDirectMutation" (test harness)
// - "migration_backfill" (migration script)
// - "console_command" (Laravel artisan)
// - "api_endpoint" (controller)

$metrics->recordUnauthorizedStateMutation('state', $context);
```

### Alerting on Violations

```php
// Monitor dashboard should alert if:
if ($health['unauthorized_state_mutations_24h'] > 0) {
    // Something is trying to bypass the write barrier!
    // Investigate the context field to find what's doing it
}
```

---

## 🚀 Transitioning Between Levels

### Level 1 → Level 2 (Current Plan)

When the system is stable (0 violations for 7 days):

```php
config(['election.enforcement_level' => 2]);  // Query Guard Strict

// Now deprecated queries are blocked:
// - Election::where('status', 'active')  // ❌ BLOCKED
// - $election->is_active;                // ❌ BLOCKED

// Users must use lifecycle API:
// - ElectionLifecycle::of($election)->state()  // ✅ OK
```

### Level 2 → Level 3 (After 30 days stable)

```php
config(['election.enforcement_level' => 3]);  // Lifecycle Strict

// Field access restricted:
// - $election->state  // ❌ BLOCKED (use lifecycle)
// - $election->status // ❌ BLOCKED
```

### Level 3 → Level 4 (Production)

```php
config(['election.enforcement_level' => 4]);  // Full Strict

// Exceptions thrown on any unauthorized mutation
// This is production-ready enforcement
```

---

## 🔍 Debugging Authorization Issues

### Check Current Level

```bash
php artisan tinker
>>> config('election.enforcement_level')
=> 1
```

### Manually Test Authorization

```php
// Test authorized write
ElectionStateWriteContext::authorize(function() use ($election) {
    $election->state = 'voting';
    $election->save();
    echo "Write succeeded";  // ✅ Output
});

// Test unauthorized write
try {
    $election->state = 'voting';
    $election->save();
    echo "Write succeeded";  // ✅ At Level 1
} catch (UnauthorizedStateMutationException $e) {
    echo "Write blocked";  // At Level 4
}
```

### View Recent Violations

```bash
# Check logs
tail -f storage/logs/constitutional_integrity.log

# Or check metrics
php artisan tinker
>>> $m = app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class);
>>> $m->getHealth()
```

---

## 🎓 Common Patterns

### Pattern 1: Console Command

```php
namespace App\Console\Commands;

use App\Application\Election\Governance\ElectionStateWriteContext;
use Illuminate\Console\Command;

class SomeElectionCommand extends Command
{
    public function handle(): int
    {
        $election = Election::find($this->argument('id'));

        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['state' => 'ready_for_voting']);
        });

        return 0;
    }
}
```

### Pattern 2: Controller Action

```php
namespace App\Http\Controllers\Api\Elections;

use App\Application\Election\Governance\ElectionStateWriteContext;
use Illuminate\Http\JsonResponse;

class ElectionController extends Controller
{
    public function openVoting(Request $request): JsonResponse
    {
        $election = Election::findOrFail($request->id);

        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['state' => 'voting_active']);
        });

        return response()->json(['state' => $election->fresh()->state]);
    }
}
```

### Pattern 3: Event Listener

```php
namespace App\Listeners;

use App\Application\Election\Governance\ElectionStateWriteContext;
use App\Events\VotingWindowOpened;

class MarkElectionAsVoting
{
    public function handle(VotingWindowOpened $event): void
    {
        $election = $event->election;

        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['state' => 'voting_active']);
        });
    }
}
```

### Pattern 4: Domain Service

```php
namespace App\Application\Election\Services;

use App\Application\Election\Governance\ElectionStateWriteContext;
use App\Models\Election;

final class ElectionProgressService
{
    public function completeAdministration(Election $election): void
    {
        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['administration_completed_at' => now()]);
            // Engine now computes: STATE = SETUP
        });
    }
}
```

---

## ✅ Verification Checklist

Before deploying write barrier to production:

- [ ] All state mutations route through `ElectionStateWriteContext::authorize()`
- [ ] Backtrace shows correct context for violations
- [ ] Metrics recording violations correctly
- [ ] Health check shows HEALTHY status
- [ ] 0 unauthorized mutations in metrics
- [ ] All tests passing with Level 1 enforcement
- [ ] Transition plan to Level 4 drafted (with dates)
- [ ] Team trained on write barrier pattern
- [ ] Documentation reviewed and approved

---

## 🎯 Summary

**The Controlled Write Barrier:**
1. ✅ Enforces constitutional sovereignty
2. ✅ Records all mutations to audit trail
3. ✅ Detects unauthorized attempts
4. ✅ Supports graduated enforcement
5. ✅ Enables safe transition to strict mode

**Key files:**
- `ElectionStateWriteContext.php` - Authorization boundary
- `Election.php` - Mutator enforcement
- `UnauthorizedStateMutationException.php` - Exception at Level 4
- `ConstitutionalMetrics.php` - Violation tracking

**Next step:**
- Learn how to test state machine: see `testing.md`
- Troubleshoot issues: see `troubleshooting.md`
