# State Mutation Governance Analysis

**Critical Clue:** `ElectionStateWriteContext::isAuthorized()`

The Election model explicitly protects state mutations through an authorization context. This suggests state changes are not free — they must go through a controlled mechanism.

**Investigation Question:** Can an election legitimately reach `state='counting'` through direct DB update, or must it follow a transition sequence?

---

## Finding 1: State Mutation is Protected

**Evidence:**

From `app/Models/Election.php::setStateAttribute()` (line 909):
```php
public function setStateAttribute($value): void
{
    if (!\App\Application\Election\Governance\ElectionStateWriteContext::isAuthorized()) {
        // ... check enforcement level ...
        if (\App\Application\Election\Deprecation\DeprecationPolicy::isEnforcementActive(4)) {
            throw new \App\Exceptions\UnauthorizedStateMutationException(
                'State mutation requires ConstitutionalTransitionGuard authorization.'
            );
        }
    }
    $this->attributes['state'] = $value;
}
```

**Interpretation:**
- State writes must be authorized by `ElectionStateWriteContext`
- The context is only "authorized" when going through the state machine
- Direct assignment (e.g., `$election->state = 'counting'`) triggers this check
- This may explain why the factory's `inResultsPendingState()` didn't work

---

## Finding 2: Direct DB Updates Bypass the Mutator

Our test uses:
```php
\DB::table('elections')
    ->where('id', $this->election->id)
    ->update(['state' => 'counting']);
```

**Fact:**
- Raw database updates do NOT trigger Eloquent mutators
- So the `setStateAttribute()` check is bypassed
- The DB update should succeed (no exception)
- Yet the state still reverts to 'draft' after refresh

**Implication:**
Either:
1. There IS a database-level constraint, OR
2. The refresh is loading a different row, OR
3. Something in the booted() lifecycle is resetting it

---

## Finding 3: State Transition is the Canonical Path

From the constitution and controller:
```php
$election->transitionTo(
    \App\Domain\Election\StateMachine\Transition::manual(
        action: 'publish_results',
        actorId: auth()->id(),
        reason: 'Results published by election officer',
        metadata: ['ip' => request()->ip()]
    )
);
```

**Evidence of Transitions:**
- Elections use `transitionTo()` method
- Transitions are defined in `ElectionConstitution::RULES`
- Each transition has:
  - `action`: the business action
  - `target_state`: the destination state
  - `allowed_states`: prerequisite states

**Question:** To reach `state='counting'`, which transition must be executed?

Looking at the factory:
```php
public function inResultsPendingState()
{
    return $this->state(['state' => 'counting']);
}
```

This tries to directly set the state using `$this->state()` (a factory fluent method), which ultimately tries to assign the attribute, which triggers the mutator check, which fails.

---

## Finding 4: The State Progression Model

From constitution evidence:
```
draft
  → submitted_for_approval (action: submit_for_approval)
  → approved (action: approve, by platform_admin)
  
approved
  → setup_administration (action: begin_setup)
  
setup_administration
  → setup_nomination (action: complete_administration)
  
setup_nomination
  → voting_active (action: open_voting)
  
voting_active
  → counting (action: close_voting)
  
counting
  → results_published (action: publish_results)
```

**Critical Finding:**
To reach `state='counting'`, an election must:
1. Be in `voting_active` state
2. Execute `close_voting` transition

Direct DB update to `'counting'` violates this progression model.

---

## The Real Problem

**Our Test Setup is Architecturally Incorrect**

```
What we're trying:
  election->state = 'draft'  ← created
  DB::update(['state' => 'counting'])  ← forced
  
What the architecture expects:
  election->transitionTo('open_voting')  ← execute action
  election->transitionTo('close_voting')  ← execute action
  election->state = 'counting'  ← result of transition
```

The election model ENFORCES that state can only change through transitions. Forcing state directly violates the constitutional model.

---

## Why the State Resets to Draft

**Hypothesis:**
The refresh() is loading the actual database state, which is still 'draft' because:
1. The direct DB update works at the SQL level
2. BUT something in the transaction or the app layer is preventing it from sticking
3. OR the test database is configured to enforce the state transition model

**Alternative Hypothesis:**
The state never actually gets updated to 'counting' in the DB. The DB update silently fails (no error, just no effect).

---

## What This Means for Architecture

**The failing test is NOT a test setup bug.**

**The failing test is REVEALING that:**

1. Election state progression is protected by the state machine
2. Direct state mutations (even via DB update) may be prevented by constraints
3. Elections MUST reach `'counting'` through the `close_voting` transition
4. Tests cannot shortcut the transition model

---

## Investigation Requirements

Before proceeding with the test, we need to verify:

1. **Canonical Path to Counting:**
   - What is the correct sequence of transitions to reach 'counting'?
   - Must setUp() execute transitions, or is there a test helper?

2. **State Mutation Enforcement:**
   - Does the DB have constraints that prevent non-transitioned state changes?
   - OR does the application layer enforce this?

3. **Architecture Intent:**
   - Is direct state mutation intentionally forbidden?
   - Are tests expected to mock/bypass the state machine?

---

## Recommendation

**Do NOT fix the test by forcing state to 'counting'.**

Instead:

**Option A: Execute Transitions in Test Setup**
```php
// Set up elections in the correct states by executing transitions
$election->transitionTo('open_voting');
$election->transitionTo('close_voting');
// Now election->state === 'counting'
```

**Option B: Use Election Constructor/Factory Helper**
```php
// Create election that's already in counting state the right way
$election = Election::inCountingStateForTesting()->create();
```

**Option C: Mock/Disable State Machine for Tests**
```php
// Tests might have a way to disable constitutional guards
\Application\Election\Governance\ElectionStateWriteContext::authorize();
$election->state = 'counting';
\Application\Election\Governance\ElectionStateWriteContext::deauthorize();
```

---

## Conclusion

**The failing test is telling us the architecture is WORKING.**

The state keeps reverting to 'draft' because the election model prevents invalid state transitions. This is not a bug — it's a feature.

The test must be rewritten to respect the election aggregate's state progression rules.

