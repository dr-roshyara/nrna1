# Results Publication Workflow — Complete Trace

**Objective:** Map the complete execution flow from HTTP request to persistence.

---

## Entry Point: ElectionManagementController::publish()

**File:** `app/Http/Controllers/Election/ElectionManagementController.php:795-837`

**Signature:**
```php
public function publish(Election $election): \Illuminate\Http\RedirectResponse
```

**Current Flow:**

### Step 1: Authorization Check
```php
$this->authorize('publishResults', $election);
```
- **Location:** Controller
- **Policy:** `ElectionPolicy::publishResults()`
- **Rule:** Chief officer only
- **Effect:** Throws 403 if unauthorized

### Step 2: Integrity Verification (Pre-publication)
```php
foreach (\App\Models\Vote::where('election_id', $election->id)->get() as $vote) {
    $integrity = $vote->verifyResultsIntegrity();
    if (!$integrity['is_valid']) {
        $vote->syncResults();  // Auto-correct drift
    }
}
```
- **Location:** Controller
- **Purpose:** Verify vote-result consistency before publishing
- **Side Effect:** May update `results` table
- **No state change:** This doesn't transition election state

### Step 3: State Machine Transition
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
- **Location:** Election aggregate model
- **Mechanism:** `transitionTo()` method on Election model
- **Action:** `'publish_results'`
- **Target State:** `'results_published'` (per constitution)

### Step 4: State Machine Internal Processing

**Where the magic happens:** `app/Models/Election.php`

The `transitionTo()` method (location TBD - need to find it):
1. Looks up action rules from constitution
2. Validates current state is in `allowed_states`
3. Calls side effect method if transition succeeds
4. Updates `state` column

**Relevant Constitution Rules:**
```
Action: 'publish_results'
Target State: 'results_published'
Allowed From States: ['counting']
Side Effects: applySideEffectsForPublishResults()
```

### Step 5: Side Effect Execution
```php
private function applySideEffectsForPublishResults(\Carbon\Carbon $currentTime): void
{
    \Illuminate\Support\Facades\DB::table('elections')
        ->where('id', $this->id)
        ->update([
            'results_published'    => true,
            'results_published_at' => $currentTime,
        ]);
}
```
- **Location:** Election model, called by state machine
- **Effect:** Sets `results_published = true` and `results_published_at = now()`
- **Persistence:** Direct DB update via raw query

### Step 6: Controller Event Dispatch (NEW — currently added)
```php
$election->refresh();  // NEW: Get updated state from DB

event(new ResultsPublishedEvent(
    electionId: $election->id,
    publishedBy: auth()->id(),
    publishedAt: new DateTimeImmutable($election->results_published_at->toDateTimeString()),
    state: $election->state,
));
```
- **Location:** Controller
- **Trigger:** After state transition completes
- **Event Contract:** ResultsPublishedEvent domain event

### Step 7: Response
```php
return back()->with('success', 'Results published successfully.');
```
- **Redirect:** Back to referrer
- **Session Flash:** Success message

---

## Parallel Flow: Unpublish

**File:** `app/Http/Controllers/Election/ElectionManagementController.php:842+`

### Step 1: Authorization (NEW — currently added)
```php
$this->authorize('publishResults', $election);
```

### Step 2: Direct Column Update
```php
$election->update(['results_published' => false]);
```
- **Location:** Controller
- **Mechanism:** Eloquent mass update
- **No state machine:** Does NOT transition election state
- **Side effect:** No event originally; event dispatch added

### Step 3: Event Dispatch (NEW — currently added)
```php
event(new ResultsUnpublishedEvent(
    electionId: $election->id,
    unpublishedBy: auth()->id(),
    unpublishedAt: new DateTimeImmutable(),
    previousState: $previousState,
));
```

---

## Architecture Observations

| Aspect | Publish | Unpublish |
|--------|---------|-----------|
| **Authorization** | Policy gate (publish results) | Policy gate (publish results) |
| **State Transition** | Via state machine | Direct column update |
| **Side Effects** | Via state machine method | Manual event dispatch |
| **Persistence** | DB raw query (via side effect) | Eloquent mass update |
| **Event Emission** | After state machine completes | After column update |

---

## The Inconsistency

**Publish flow:**
```
Controller
    ↓
State Machine (owns the decision + transition + side effects)
    ↓
Side Effects apply
    ↓
Controller dispatches event
```

**Unpublish flow:**
```
Controller
    ↓
Direct column update (no state machine)
    ↓
Controller dispatches event
```

**Questions:**
1. Why does publish use state machine but unpublish doesn't?
2. Who actually owns the "publication decision"?
3. Should unpublish also use the state machine?
4. If state machine is used for publish, why isn't there a `unpublish_results` transition?

---

## Missing Information

Need to locate and understand:
1. `Election::transitionTo()` method - what does it do exactly?
2. `ElectionConstitution::RULES` - what are the exact rules for `publish_results`?
3. Is there a `unpublish_results` action defined in the constitution?
4. Does the constitution have state transition rules that would prevent unpublish from working?

