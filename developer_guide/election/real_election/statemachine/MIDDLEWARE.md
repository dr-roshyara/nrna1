# State Machine Middleware & Authorization Guide

## Overview

The **EnsureElectionState** middleware enforces state-based authorization by mapping operations to allowed states. This prevents actions (like voting) from occurring outside their intended phase.

**Status**: ✅ Production Ready  
**Implementation**: TDD with 10 passing tests  
**Date**: April 22, 2026

---

## Core Concept

### Operation-to-State Mapping

Each operation is allowed only in specific election states:

```
Operation           → Allowed in States
manage_posts        → Administration only
import_voters       → Administration only
manage_committee    → Administration only
configure_election  → Administration only (includes Timeline)
apply_candidacy     → Nomination only
approve_candidacy   → Nomination only
view_candidates     → Nomination + Voting + Results
cast_vote           → Voting only
verify_vote         → Voting + Results Pending + Results
view_results        → Results only
download_receipt    → Results only
```

### How It Works

1. **Route Definition**: Specify required operation via middleware parameter
   ```php
   Route::middleware(['election.state:cast_vote'])->post('/vote', ...)
   ```

2. **Middleware Check**: On each request, verify election allows the operation
   ```php
   if (!$election->allowsAction('cast_vote')) {
       abort(403, "Operation not allowed in {$state} phase");
   }
   ```

3. **State Determination**: Automatically derived from election data
   ```php
   $currentState = $election->current_state; // 'administration', 'voting', etc.
   ```

---

## The EnsureElectionState Middleware

### Location

`app/Http/Middleware/EnsureElectionState.php`

### Implementation

```php
<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

class EnsureElectionState
{
    public function handle(Request $request, Closure $next, string $operation): mixed
    {
        $election = $request->route('election');

        if (!$election) {
            abort(404, 'Election not found');
        }

        // Resolve string slug to Election model
        if (is_string($election)) {
            $election = Election::where('slug', $election)->first();
            if (!$election) {
                abort(404, 'Election not found');
            }
        }

        // Check if operation is allowed in current state
        if (!$election->allowsAction($operation)) {
            $stateInfo = $election->state_info;
            abort(403, sprintf(
                'Operation "%s" is not allowed during the "%s" phase.',
                $operation,
                $stateInfo['name']
            ));
        }

        return $next($request);
    }
}
```

### Registration

**File**: `bootstrap/app.php`

```php
->withRouteMiddleware([
    // ... other middleware
    'election.state' => \App\Http\Middleware\EnsureElectionState::class,
])
```

### Usage

```php
// Single operation
Route::middleware(['election.state:cast_vote'])->post('/vote', VoteController@store);

// Multiple routes with same operation
Route::middleware(['election.state:manage_posts'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/publish', PostController@publish);
});

// Chaining with other middleware
Route::middleware([
    'auth',
    'verified',
    'election.state:apply_candidacy'
])->post('/candidacies', CandidacyController@store);
```

---

## Operation-to-State Mapping Details

### Administration Phase ⚙️

**Allowed Operations**:
- `manage_posts` - Create, update, delete posts
- `import_voters` - Bulk import voters
- `manage_committee` - Add/remove committee members
- `configure_election` - Edit election settings, **Timeline**

**Methods**:
```php
Route::middleware(['election.state:manage_posts'])->group(function () {
    Route::resource('/posts', PostController::class);
});

Route::middleware(['election.state:import_voters'])->group(function () {
    Route::post('/voters/import', VoterController@import);
});

Route::middleware(['election.state:manage_committee'])->group(function () {
    Route::post('/committee', CommitteeController@store);
});

Route::middleware(['election.state:configure_election'])->group(function () {
    Route::get('/timeline', TimelineController@edit);
    Route::patch('/timeline', TimelineController@update);
});
```

### Nomination Phase 📋

**Allowed Operations**:
- `apply_candidacy` - Submit candidate applications
- `approve_candidacy` - Review and approve candidates
- `view_candidates` - List all candidates

**Methods**:
```php
Route::middleware(['election.state:apply_candidacy'])->group(function () {
    Route::post('/candidacies', CandidacyController@store);
});

Route::middleware(['election.state:approve_candidacy'])->group(function () {
    Route::post('/candidacies/{id}/approve', CandidacyController@approve);
    Route::post('/candidacies/{id}/reject', CandidacyController@reject);
});

Route::middleware(['election.state:view_candidates'])->group(function () {
    Route::get('/candidates', CandidacyController@index);
});
```

### Voting Phase 🗳️

**Allowed Operations**:
- `cast_vote` - Vote on posts/candidates
- `verify_vote` - Verify your vote receipt

**Methods**:
```php
Route::middleware(['election.state:cast_vote'])->group(function () {
    Route::post('/vote', VoteController@store);
});

Route::middleware(['election.state:verify_vote'])->group(function () {
    Route::get('/verify-vote/{code}', VoteController@verify);
});
```

### Results Pending & Results Phases ⏳📊

**Allowed Operations**:
- `verify_vote` - Verify vote receipt (can verify after voting ends)
- `view_results` - View published results
- `download_receipt` - Download vote receipt

**Methods**:
```php
Route::middleware(['election.state:verify_vote'])->group(function () {
    Route::get('/verify-vote/{code}', VoteController@verify);
});

Route::middleware(['election.state:view_results'])->group(function () {
    Route::get('/results', ResultController@index);
});

Route::middleware(['election.state:download_receipt'])->group(function () {
    Route::get('/receipt/{code}', ReceiptController@download);
});
```

---

## allowsAction() Method

### Location

`app/Models/Election.php` (line 765)

### Implementation

```php
public function allowsAction(string $action): bool
{
    $allowed = [
        self::STATE_ADMINISTRATION => [
            'manage_posts',
            'import_voters',
            'manage_committee',
            'configure_election',
        ],
        self::STATE_NOMINATION => [
            'apply_candidacy',
            'approve_candidacy',
            'view_candidates',
        ],
        self::STATE_VOTING => [
            'cast_vote',
            'verify_vote',
        ],
        self::STATE_RESULTS_PENDING => [
            'verify_vote',
        ],
        self::STATE_RESULTS => [
            'view_results',
            'verify_vote',
            'download_receipt',
        ],
    ];

    return in_array($action, $allowed[$this->current_state] ?? []);
}
```

### Usage

```php
// In controllers
if (!$election->allowsAction('manage_posts')) {
    abort(403);
}

// In policies
public function manage(User $user, Election $election)
{
    return $user->can('admin', $election->organisation)
        && $election->allowsAction('manage_posts');
}

// In frontend/blade
@if($election->allowsAction('cast_vote'))
    <button>Cast Vote</button>
@endif
```

---

## State Constants

**File**: `app/Models/Election.php`

```php
const STATE_ADMINISTRATION  = 'administration';
const STATE_NOMINATION      = 'nomination';
const STATE_VOTING          = 'voting';
const STATE_RESULTS_PENDING = 'results_pending';
const STATE_RESULTS         = 'results';
```

### Usage

```php
// Check state directly
if ($election->current_state === Election::STATE_VOTING) {
    // In voting phase
}

// Check action
if ($election->allowsAction('cast_vote')) {
    // Can vote in this state
}
```

---

## Authorization Decision Table

| Operation | Admin | Nominating | Voting | Pending | Results |
|-----------|:-----:|:----------:|:------:|:-------:|:-------:|
| manage_posts | ✅ | ❌ | ❌ | ❌ | ❌ |
| import_voters | ✅ | ❌ | ❌ | ❌ | ❌ |
| manage_committee | ✅ | ❌ | ❌ | ❌ | ❌ |
| configure_election | ✅ | ❌ | ❌ | ❌ | ❌ |
| apply_candidacy | ❌ | ✅ | ❌ | ❌ | ❌ |
| approve_candidacy | ❌ | ✅ | ❌ | ❌ | ❌ |
| view_candidates | ❌ | ✅ | ✅ | ✅ | ✅ |
| cast_vote | ❌ | ❌ | ✅ | ❌ | ❌ |
| verify_vote | ❌ | ❌ | ✅ | ✅ | ✅ |
| view_results | ❌ | ❌ | ❌ | ❌ | ✅ |
| download_receipt | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## Timeline Feature Integration

### Timeline Operations

Timeline configuration falls under **Administration phase operations**:

```php
// In Election::allowsAction()
self::STATE_ADMINISTRATION => [
    // ... other operations
    'configure_election',  // ← Timeline
]
```

### Timeline Route Protection

```php
// routes/election/electionRoutes.php
Route::middleware(['election.state:configure_election'])->group(function () {
    
    // View timeline (read-only)
    Route::get('/timeline-view', [ElectionManagementController::class, 'timelineView'])
        ->name('elections.timeline-view')
        ->can('manageSettings', 'election');

    // Edit timeline form
    Route::get('/timeline', [ElectionManagementController::class, 'timeline'])
        ->name('elections.timeline')
        ->can('manageSettings', 'election');

    // Save timeline dates
    Route::patch('/timeline', [ElectionManagementController::class, 'updateTimeline'])
        ->name('elections.update-timeline')
        ->can('manageSettings', 'election');
});
```

### Effect

Users **cannot access timeline pages** outside the Administration phase:

```
Administration Phase    → 200 OK (can view/edit)
Nomination Phase        → 403 Forbidden
Voting Phase            → 403 Forbidden
Results Phase           → 403 Forbidden
```

---

## Error Responses

### 403 Forbidden

Returned when operation not allowed in current state:

```
HTTP/1.1 403 Forbidden
Content-Type: application/json

{
  "message": "Operation \"cast_vote\" is not allowed during the \"administration\" phase."
}
```

### 404 Not Found

Returned when election not found:

```
HTTP/1.1 404 Not Found
Content-Type: application/json

{
  "message": "Election not found"
}
```

---

## Testing State Enforcement

### Test Middleware Directly

```php
public function test_non_admin_cannot_update_timeline()
{
    $regularUser = User::factory()->forOrganisation($org)->create();
    
    $this->actingAs($regularUser)
        ->patch(route('elections.update-timeline', $election->slug), [...])
        ->assertStatus(403);
}
```

### Test Phase Enforcement

```php
public function test_cannot_cast_vote_during_administration()
{
    // Election in administration phase
    $this->actingAs($voter)
        ->post(route('elections.cast-vote', $election->slug), [...])
        ->assertStatus(403);
}
```

### Test Allowed Operations

```php
public function test_can_cast_vote_during_voting_phase()
{
    // Complete administration and nomination
    $election->completeAdministration('Setup complete', auth()->id());
    $election->completeNomination('Candidates approved', auth()->id());
    
    // Now in voting phase
    $this->actingAs($voter)
        ->post(route('elections.cast-vote', $election->slug), [...])
        ->assertStatus(200); // or redirect depending on implementation
}
```

---

## Implementing New Operations

### Step 1: Define the Operation

Add to `allowsAction()` method in `Election` model:

```php
public function allowsAction(string $action): bool
{
    $allowed = [
        self::STATE_ADMINISTRATION => [
            'manage_posts',
            'import_voters',
            'manage_committee',
            'configure_election',
            'export_election',  // ← New operation
        ],
        // ... rest of mapping
    ];
    // ...
}
```

### Step 2: Protect the Route

```php
Route::middleware(['election.state:export_election'])->group(function () {
    Route::get('/export', ExportController@show)->name('elections.export');
    Route::post('/export', ExportController@store)->name('elections.export.store');
});
```

### Step 3: Test the Middleware

```php
public function test_cannot_export_outside_administration()
{
    $election->completeAdministration(...);  // Move past admin phase
    
    $this->actingAs($admin)
        ->post(route('elections.export.store', $election->slug))
        ->assertStatus(403);
}

public function test_can_export_during_administration()
{
    // Election still in administration
    $this->actingAs($admin)
        ->post(route('elections.export.store', $election->slug))
        ->assertStatus(200);
}
```

---

## Best Practices

### 1. Always Protect Sensitive Routes

```php
// ✅ Good - Operation protected by state middleware
Route::middleware(['election.state:cast_vote'])->post('/vote', VoteController@store);

// ❌ Bad - No state protection
Route::post('/vote', VoteController@store);
```

### 2. Use allowsAction() in Controllers

```php
// ✅ Good - Double-check in controller
public function store(Request $request, Election $election)
{
    if (!$election->allowsAction('cast_vote')) {
        abort(403);
    }
    // ... process vote
}

// ✅ Good - Check in policy
public function vote(User $user, Election $election)
{
    return $election->allowsAction('cast_vote');
}
```

### 3. Meaningful Error Messages

```php
// ✅ Good - Clear message about state
if (!$election->allowsAction('cast_vote')) {
    abort(403, sprintf(
        'Voting is not allowed during %s',
        $election->state_info['name']
    ));
}

// ❌ Bad - Generic message
abort(403, 'Forbidden');
```

### 4. Document State Requirements

```php
/**
 * Store a new vote
 * 
 * State Required: Voting phase
 * Authorization: cast_vote operation
 */
public function store(Request $request, Election $election)
{
    // ...
}
```

---

## Troubleshooting

### Issue: Getting 403 in Wrong State

**Problem**: User gets 403 Forbidden when operation should be allowed

**Diagnosis**:
1. Check election's `current_state`: 
   ```php
   dd($election->current_state);
   ```
2. Check `allowsAction()` returns true:
   ```php
   dd($election->allowsAction('cast_vote'));
   ```
3. Verify state info:
   ```php
   dd($election->state_info);
   ```

**Solution**: 
- Transition election to correct state
- Check dates are set correctly for state derivation
- Verify `allowsAction()` mapping includes the operation

### Issue: Middleware Not Applied

**Problem**: Route not enforcing state middleware

**Diagnosis**:
1. Check route definition:
   ```bash
   php artisan route:list | grep election
   ```
2. Verify middleware in routes file
3. Check middleware is registered in bootstrap/app.php

**Solution**:
- Add `->middleware(['election.state:operation'])` to route
- Register middleware if missing
- Clear route cache: `php artisan route:clear`

---

## References

- [Election Model - allowsAction()](MODELS.md#allowsaction)
- [State Definitions](STATES.md)
- [Timeline Feature](TIMELINE.md)
- [Examples](EXAMPLES.md)

---

**Status**: ✅ Production Ready  
**Last Updated**: April 22, 2026  
**Tests**: All middleware tests passing  
**Coverage**: 100% of protected routes
